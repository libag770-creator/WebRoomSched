<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\Building;
use App\Models\Department;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\ScheduleOverride;
use App\Models\User;
use App\Notifications\ScheduleOverrideNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomReassignmentController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureAdministrator();
        $activeTerm = $this->resolveAcademicTerm($request);

        ScheduleOverride::query()
            ->where('status', 'active')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        $departments = Department::with('buildings.rooms')->orderBy('name')->get();
        $rooms = Room::with(['department', 'buildingRelation'])->orderBy('room_name')->get();
        $overrides = ScheduleOverride::with([
            'room.buildingRelation',
            'room.department',
            'schedule.room.buildingRelation',
            'faculty',
        ])
            ->where('status', 'active')
            ->whereDate('override_date', '>=', today()->toDateString())
            ->where('expires_at', '>', now())
            ->orderBy('override_date')
            ->orderBy('start_time')
            ->orderBy('created_at')
            ->get();

        return view('admin.roomreassignment', compact('departments', 'rooms', 'overrides', 'activeTerm'));
    }

    public function store(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'override_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'reason' => ['required', 'string', 'max:255'],
            'occupied_by' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:2000'],
            'acknowledge_conflicts' => ['nullable', 'accepted'],
        ], [
            'override_date.after_or_equal' => 'Choose today or a future date.',
            'acknowledge_conflicts.accepted' => 'Confirm that you have reviewed the affected schedules before saving.',
        ]);

        if ($this->timeMinutes($validated['end_time']) <= $this->timeMinutes($validated['start_time'])) {
            return back()->withInput()->withErrors([
                'end_time' => 'The end time must be later than the start time.',
            ]);
        }

        $room = Room::with(['buildingRelation', 'department'])->findOrFail($validated['room_id']);
        $date = Carbon::createFromFormat('Y-m-d', $validated['override_date'])->startOfDay();
        $expiresAt = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $validated['end_time']);
        if ($date->isToday() && $expiresAt->lte(now())) {
            return back()->withInput()->withErrors([
                'end_time' => 'The end time must still be in the future for a reservation today.',
            ]);
        }

        $start = $this->timeMinutes($validated['start_time']);
        $end = $this->timeMinutes($validated['end_time']);
        $conflicts = $this->roomConflicts($room, $date, $start, $end);

        if (collect($conflicts)->contains(fn ($item) => $item['type'] === 'existing override')) {
            return back()->withInput()->withErrors([
                'override_conflicts' => 'This room already has an active override during part of that time. Choose a different time.',
            ]);
        }

        if (count($conflicts) && !$request->boolean('acknowledge_conflicts')) {
            return back()->withInput()->with('room_override_conflicts', $conflicts)->withErrors([
                'override_conflicts' => 'Review the affected classes or room reservations, then confirm that they may be interrupted.',
            ]);
        }

        $override = DB::transaction(function () use ($room, $date, $validated, $conflicts, $expiresAt) {
            $override = ScheduleOverride::create([
                'room_id' => $room->id,
                'schedule_id' => null,
                'faculty_id' => null,
                'created_by' => auth()->id(),
                'override_date' => $date->toDateString(),
                'start_time' => $validated['start_time'] . ':00',
                'end_time' => $validated['end_time'] . ':00',
                'reason' => trim($validated['reason']),
                'occupied_by' => trim($validated['occupied_by']),
                'details' => isset($validated['details']) ? trim($validated['details']) : null,
                'expires_at' => $expiresAt,
                'status' => 'active',
            ]);

            $override->load(['room.buildingRelation', 'room.department']);
            $this->notifyImpactedFaculty($override, $conflicts);

            return $override;
        });

        $this->notifyImpactedStudents($override, $conflicts, 'created');

        return redirect()
            ->route('admin.roomreassignment')
            ->with('success', 'The room reservation is set. Affected instructor have been notified, and the room becomes available again at the selected end time.');
    }

    public function conflicts(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'override_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
        ]);

        $start = $this->timeMinutes($validated['start_time']);
        $end = $this->timeMinutes($validated['end_time']);

        if ($end <= $start) {
            return response()->json(['conflicts' => [], 'message' => 'End time must be later than start time.'], 422);
        }

        $room = Room::findOrFail($validated['room_id']);
        $date = Carbon::createFromFormat('Y-m-d', $validated['override_date'])->startOfDay();

        return response()->json([
            'conflicts' => $this->roomConflicts($room, $date, $start, $end),
        ]);
    }

    public function cancel(ScheduleOverride $scheduleOverride)
    {
        $this->ensureAdministrator();

        if ($scheduleOverride->status === 'active') {
            $scheduleOverride->load(['room.buildingRelation', 'room.department', 'schedule.room.buildingRelation']);
            $scheduleOverride->update(['status' => 'cancelled']);

            $date = $scheduleOverride->override_date?->copy()->startOfDay();
            $range = $this->overrideRange($scheduleOverride);
            $overrideRoom = $scheduleOverride->room ?: $scheduleOverride->schedule?->room;
            if ($date && $overrideRoom && $range) {
                $conflicts = $this->roomConflicts(
                    $overrideRoom,
                    $date,
                    $range['start'],
                    $range['end'],
                    $scheduleOverride->id
                );
                $this->notifyImpactedFaculty($scheduleOverride, $conflicts, 'cancelled');
                $this->notifyImpactedStudents($scheduleOverride, $conflicts, 'cancelled');
            } elseif ($scheduleOverride->faculty) {
                $scheduleOverride->faculty->notify(new ScheduleOverrideNotification($scheduleOverride, 'cancelled'));
            }
        }

        return redirect()->route('admin.roomreassignment')->with('success', 'The room reservation was cancelled.');
    }

    public function buildings(Department $department)
    {
        $this->ensureAdministrator();

        return response()->json(
            Building::query()
                ->where('department_id', $department->id)
                ->withCount('rooms')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Building $building) => [
                    'id' => $building->id,
                    'name' => $building->name,
                    'rooms_count' => (int) $building->rooms_count,
                ])
                ->values()
        );
    }

    public function rooms(Building $building)
    {
        $this->ensureAdministrator();

        return response()->json(
            Room::query()
                ->where('building_id', $building->id)
                ->with('department')
                ->orderBy('room_name')
                ->get(['id', 'room_name', 'building_id', 'department_id', 'capacity', 'computers', 'has_tv', 'has_projector', 'purpose', 'description'])
                ->map(fn (Room $room) => [
                    'id' => $room->id,
                    'name' => $room->room_name,
                    'building_id' => $room->building_id,
                    'department_id' => $room->department_id,
                    'department_name' => $room->department?->name,
                    'building_name' => $building->name,
                    'capacity' => $room->capacity,
                    'computers' => $room->computers,
                    'has_tv' => $room->has_tv === null ? null : (bool) $room->has_tv,
                    'has_projector' => $room->has_projector === null ? null : (bool) $room->has_projector,
                    'purpose' => $room->purpose,
                    'description' => $room->description,
                ])
                ->values()
        );
    }

    private function roomConflicts(Room $room, Carbon $date, int $start, int $end, ?int $ignoreOverrideId = null): array
    {
        $activeTerm = $this->resolveAcademicTerm(request());
        $day = $date->format('D');
        $conflicts = [];

        $schedules = Schedule::with('faculty')
            ->where('room_id', $room->id)
            ->whereNull('deleted_at')
            ->when($activeTerm, fn ($query) => $query->where('academic_term_id', $activeTerm->id))
            ->get();

        foreach ($schedules as $schedule) {
            if ($this->normalizeDay($schedule->day_of_week ?: $schedule->day) !== $day) {
                continue;
            }

            $range = $this->scheduleRange($schedule);
            if (!$range || !$this->overlaps($start, $end, $range['start'], $range['end'])) {
                continue;
            }

            $faculty = $schedule->faculty;
            if (!$faculty && trim((string) $schedule->instructor) !== '') {
                $faculty = User::query()
                    ->whereRaw('LOWER(TRIM(role)) = ?', ['faculty'])
                    ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim((string) $schedule->instructor))])
                    ->first();
            }

            $conflicts[] = [
                'type' => 'class',
                'label' => trim(($schedule->course_code ?: $schedule->subject ?: 'Scheduled class') . ' · ' . ($schedule->section ?: '')),
                'time' => $this->formatMinutes($range['start']) . '–' . $this->formatMinutes($range['end']),
                'faculty' => $faculty?->name ?: ($schedule->instructor ?: 'Instructor not assigned'),
                'faculty_id' => $faculty?->id,
                'schedule_id' => $schedule->id,
            ];
        }

        $reservations = Reservation::with('user')
            ->where('room_id', $room->id)
            ->whereDate('date', $date->toDateString())
            ->whereRaw('LOWER(TRIM(status)) = ?', ['reserved'])
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->get();

        foreach ($reservations as $reservation) {
            $reservationStart = $this->timeMinutes($reservation->start_time);
            $reservationEnd = $this->timeMinutes($reservation->end_time);
            if ($reservationEnd <= $reservationStart || !$this->overlaps($start, $end, $reservationStart, $reservationEnd)) {
                continue;
            }

            $conflicts[] = [
                'type' => 'room reservation',
                'label' => $reservation->purpose ?: 'Room reservation',
                'time' => $this->formatMinutes($reservationStart) . '–' . $this->formatMinutes($reservationEnd),
                'faculty' => $reservation->user?->name ?: 'Reservation holder',
                'faculty_id' => strtolower((string) $reservation->user?->role) === 'faculty' ? $reservation->user?->id : null,
                'schedule_id' => null,
            ];
        }

        $overrides = ScheduleOverride::with('schedule')
            ->where('room_id', $room->id)
            ->where('status', 'active')
            ->whereDate('override_date', $date->toDateString())
            ->where('expires_at', '>', now())
            ->when($ignoreOverrideId, fn ($query) => $query->where('id', '!=', $ignoreOverrideId))
            ->get();

        foreach ($overrides as $override) {
            $range = $this->overrideRange($override);
            if (!$range || !$this->overlaps($start, $end, $range['start'], $range['end'])) {
                continue;
            }

            $conflicts[] = [
                'type' => 'existing override',
                'label' => 'Already reserved by ' . ($override->occupied_by ?: 'another occupant'),
                'time' => $this->formatMinutes($range['start']) . '–' . $this->formatMinutes($range['end']),
                'faculty' => '',
                'faculty_id' => null,
                'schedule_id' => null,
            ];
        }

        return $conflicts;
    }

    private function notifyImpactedFaculty(ScheduleOverride $override, array $conflicts, string $event = 'created'): void
    {
        $facultyIds = collect($conflicts)
            ->pluck('faculty_id')
            ->filter()
            ->unique()
            ->values();

        if ($facultyIds->isNotEmpty()) {
            User::whereIn('id', $facultyIds)->get()->each(
                fn (User $faculty) => $faculty->notify(new ScheduleOverrideNotification($override, $event))
            );
        }
    }

    private function notifyImpactedStudents(ScheduleOverride $override, array $conflicts, string $event): void
    {
        $scheduleIds = collect($conflicts)
            ->pluck('schedule_id')
            ->filter()
            ->unique();

        if ($scheduleIds->isEmpty()) return;

        $date = $override->override_date?->format('Y-m-d');
        $effectiveAt = null;
        $expiresAt = null;
        try {
            if ($date && $override->start_time) {
                $effectiveAt = Carbon::parse($date . ' ' . $override->start_time);
            }
            if ($date && $override->end_time) {
                $expiresAt = Carbon::parse($date . ' ' . $override->end_time);
            }
        } catch (\Throwable $exception) {
            $effectiveAt = null;
            $expiresAt = null;
        }

        foreach (Schedule::with('room')->whereIn('id', $scheduleIds)->get() as $schedule) {
            $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);
            $scheduledRoom = $studentUpdates->roomNameFor($schedule) ?: 'the scheduled room';
            $className = trim(($schedule->course_code ?: 'Class') . ' — ' . ($schedule->subject ?: ''));
            $time = trim((string) $override->start_time) . '–' . trim((string) $override->end_time);
            $cancelled = $event === 'cancelled';
            $message = $cancelled
                ? 'The room reservation in ' . ($override->room?->room_name ?: 'the scheduled room')
                    . ' was cancelled. ' . $className . ' remains scheduled in ' . $scheduledRoom
                    . ($date ? ' on ' . $date : '') . '.'
                : 'A reservation in ' . ($override->room?->room_name ?: 'the scheduled room')
                    . ' overlaps ' . $className . ($date ? ' on ' . $date : '')
                    . ($time !== '–' ? ' at ' . $time : '') . '. The timetable still lists '
                    . $scheduledRoom . '; check for a separate room change.';

            $studentUpdates->recordForSchedule(
                $schedule,
                $cancelled ? 'room_reservation_cancelled' : 'room_reservation_conflict',
                $cancelled ? 'Room reservation cancelled' : 'Reservation overlaps your class',
                $message,
                [
                    'room_change' => [
                        'type' => 'reservation',
                        'date' => $date,
                        'start_time' => $override->start_time,
                        'end_time' => $override->end_time,
                        'scheduled_room' => $studentUpdates->roomNameFor($schedule),
                        'reserved_room' => $override->room?->room_name,
                        'reason' => $override->reason,
                    ],
                ],
                $effectiveAt,
                $cancelled ? null : $expiresAt
            );
        }
    }

    private function scheduleRange(Schedule $schedule): ?array
    {
        if ($schedule->start_time && $schedule->end_time) {
            $start = $this->timeMinutes($schedule->start_time);
            $end = $this->timeMinutes($schedule->end_time);
            if ($end > $start) {
                return ['start' => $start, 'end' => $end];
            }
        }

        $parts = preg_split('/\s*(?:-|–|—|to)\s*/i', trim((string) $schedule->time), 2);
        if (count($parts) !== 2) {
            return null;
        }

        $startPeriod = preg_match('/(AM|PM)$/i', trim($parts[0]), $startMatch) ? strtoupper($startMatch[1]) : null;
        $endPeriod = preg_match('/(AM|PM)$/i', trim($parts[1]), $endMatch) ? strtoupper($endMatch[1]) : null;
        if ($startPeriod && !$endPeriod) {
            $parts[1] .= ' ' . $startPeriod;
        } elseif (!$startPeriod && $endPeriod) {
            $parts[0] .= ' ' . $endPeriod;
        }

        $start = $this->timeMinutes($parts[0]);
        $end = $this->timeMinutes($parts[1]);

        return $end > $start ? ['start' => $start, 'end' => $end] : null;
    }

    private function overrideRange(ScheduleOverride $override): ?array
    {
        if ($override->start_time && $override->end_time) {
            $start = $this->timeMinutes($override->start_time);
            $end = $this->timeMinutes($override->end_time);
            if ($end > $start) {
                return ['start' => $start, 'end' => $end];
            }
        }

        return $override->schedule ? $this->scheduleRange($override->schedule) : null;
    }

    private function timeMinutes($value): int
    {
        if ($value instanceof Carbon) {
            return ((int) $value->format('H') * 60) + (int) $value->format('i');
        }

        $raw = trim((string) $value);
        if (preg_match('/(?:^|T|\s)(\d{1,2}):(\d{2})(?::\d{2})?$/', $raw, $match)) {
            return ((int) $match[1] * 60) + (int) $match[2];
        }

        if (!preg_match('/^(\d{1,2})(?::(\d{2}))?\s*(AM|PM)?$/i', $raw, $match)) {
            return 0;
        }

        $hour = (int) $match[1];
        $minute = isset($match[2]) && $match[2] !== '' ? (int) $match[2] : 0;
        $period = strtoupper($match[3] ?? '');
        if ($period === 'AM' && $hour === 12) $hour = 0;
        if ($period === 'PM' && $hour !== 12) $hour += 12;

        return ($hour * 60) + $minute;
    }

    private function formatMinutes(int $minutes): string
    {
        return Carbon::createFromTime(intdiv($minutes, 60), $minutes % 60)->format('g:i A');
    }

    private function overlaps(int $startA, int $endA, int $startB, int $endB): bool
    {
        return $startA < $endB && $endA > $startB;
    }

    private function normalizeDay(?string $value): string
    {
        $day = strtoupper(trim((string) $value));
        $aliases = [
            'MONDAY' => 'Mon', 'MON' => 'Mon',
            'TUESDAY' => 'Tue', 'TUES' => 'Tue', 'TUE' => 'Tue',
            'WEDNESDAY' => 'Wed', 'WEDS' => 'Wed', 'WED' => 'Wed',
            'THURSDAY' => 'Thu', 'THURS' => 'Thu', 'THU' => 'Thu',
            'FRIDAY' => 'Fri', 'FRI' => 'Fri',
            'SATURDAY' => 'Sat', 'SAT' => 'Sat',
            'SUNDAY' => 'Sun', 'SUN' => 'Sun',
        ];

        return $aliases[$day] ?? $day;
    }

    private function ensureAdministrator(): void
    {
        if (!auth()->check() || strtolower((string) auth()->user()->role) !== 'admin') {
            abort(403, 'Unauthorized.');
        }
    }

    private function resolveAcademicTerm(Request $request): ?AcademicTerm
    {
        $termId = $request->input('term_id') ?? $request->query('term_id') ?? session('active_academic_term_id');
        if ($termId) {
            $term = AcademicTerm::find((int) $termId);
            if ($term) return $term;
        }

        return AcademicTerm::where('is_active', true)->first() ?? AcademicTerm::orderByDesc('id')->first();
    }
}
