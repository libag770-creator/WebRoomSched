<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\Building;
use App\Models\Department;
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

        $departments = Department::with('buildings.rooms')
            ->orderBy('name')
            ->get();

        $rooms = Room::with(['department', 'building'])
            ->orderBy('room_name')
            ->get();

        $schedulesQuery = Schedule::with([
            'room.building',
            'room.department',
            'faculty',
        ])->whereNull('deleted_at');

        if ($activeTerm) {
            $schedulesQuery->where('academic_term_id', $activeTerm->id);
        }

        $schedules = $schedulesQuery
            ->orderBy('room_id')
            ->orderBy('row_position')
            ->orderBy('id')
            ->get();

        $overrides = ScheduleOverride::with([
            'schedule.room.building',
            'faculty',
        ])
            ->where('status', 'active')
            ->whereDate('override_date', '>=', today()->toDateString())
            ->where('expires_at', '>', now())
            ->orderBy('override_date')
            ->orderBy('created_at')
            ->get();

        return view('admin.roomreassignment', compact(
            'departments',
            'rooms',
            'schedules',
            'overrides',
            'activeTerm'
        ));
    }

    public function store(Request $request)
    {
        $this->ensureAdministrator();

        $validated = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'override_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'reason' => ['required', 'string', 'max:255'],
            'occupied_by' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:2000'],
        ], [
            'override_date.after_or_equal' => 'Choose today or a future date.',
        ]);

        $activeTerm = $this->resolveAcademicTerm($request);
        $schedule = Schedule::with(['room.building', 'room.department', 'faculty'])
            ->whereNull('deleted_at')
            ->findOrFail($validated['schedule_id']);

        if ($activeTerm && (int) $schedule->academic_term_id !== (int) $activeTerm->id) {
            return back()->withInput()->withErrors([
                'schedule_id' => 'Choose a schedule from the currently selected academic term.',
            ]);
        }

        $date = Carbon::createFromFormat('Y-m-d', $validated['override_date'])->startOfDay();
        $scheduleDay = $this->normalizeDay($schedule->day_of_week ?: $schedule->day);

        if ($date->format('D') !== $scheduleDay) {
            return back()->withInput()->withErrors([
                'override_date' => 'The selected date must fall on the same weekday as this schedule (' . $scheduleDay . ').',
            ]);
        }

        if ($schedule->schedule_start_date && $date->lt($schedule->schedule_start_date->copy()->startOfDay())) {
            return back()->withInput()->withErrors([
                'override_date' => 'The selected date is before this schedule begins.',
            ]);
        }

        if ($schedule->schedule_expiration_date && $date->gt($schedule->schedule_expiration_date->copy()->startOfDay())) {
            return back()->withInput()->withErrors([
                'override_date' => 'The selected date is after this schedule ends.',
            ]);
        }

        $faculty = $schedule->faculty;
        if (!$faculty && trim((string) $schedule->instructor) !== '') {
            $faculty = User::query()
                ->whereRaw('LOWER(TRIM(role)) = ?', ['faculty'])
                ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim((string) $schedule->instructor))])
                ->first();
        }

        if (!$faculty) {
            return back()->withInput()->withErrors([
                'schedule_id' => 'This schedule has no linked faculty account to notify. Assign its faculty member, then try again.',
            ]);
        }

        $duplicate = ScheduleOverride::query()
            ->where('schedule_id', $schedule->id)
            ->whereDate('override_date', $date->toDateString())
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();

        if ($duplicate) {
            return back()->withInput()->withErrors([
                'schedule_id' => 'This schedule already has an active override for the selected date.',
            ]);
        }

        DB::transaction(function () use ($schedule, $faculty, $validated, $date) {
            $override = ScheduleOverride::create([
                'schedule_id' => $schedule->id,
                'faculty_id' => $faculty->id,
                'created_by' => auth()->id(),
                'override_date' => $date->toDateString(),
                'reason' => trim($validated['reason']),
                'occupied_by' => trim($validated['occupied_by']),
                'details' => isset($validated['details']) ? trim($validated['details']) : null,
                'expires_at' => $date->copy()->endOfDay(),
                'status' => 'active',
            ]);

            $override->load(['schedule.room', 'faculty']);
            $faculty->notify(new ScheduleOverrideNotification($override));
        });

        return redirect()
            ->route('admin.roomreassignment')
            ->with('success', 'The schedule override is set. The faculty member has been notified, and the regular schedule returns automatically after the selected day.');
    }

    public function cancel(ScheduleOverride $scheduleOverride)
    {
        $this->ensureAdministrator();

        if ($scheduleOverride->status === 'active') {
            $scheduleOverride->update(['status' => 'cancelled']);
            $scheduleOverride->load(['schedule.room', 'faculty']);
            $scheduleOverride->faculty?->notify(new ScheduleOverrideNotification($scheduleOverride, 'cancelled'));
        }

        return redirect()
            ->route('admin.roomreassignment')
            ->with('success', 'The schedule override was cancelled.');
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
                ->orderBy('room_name')
                ->get(['id', 'room_name', 'building_id', 'department_id'])
                ->map(fn (Room $room) => [
                    'id' => $room->id,
                    'name' => $room->room_name,
                    'building_id' => $room->building_id,
                    'department_id' => $room->department_id,
                    'building_name' => $building->name,
                ])
                ->values()
        );
    }

    private function ensureAdministrator(): void
    {
        if (!auth()->check() || strtolower((string) auth()->user()->role) !== 'admin') {
            abort(403, 'Unauthorized.');
        }
    }

    private function resolveAcademicTerm(Request $request): ?AcademicTerm
    {
        $termId = $request->input('term_id')
            ?? $request->query('term_id')
            ?? session('active_academic_term_id');

        if ($termId) {
            $term = AcademicTerm::find((int) $termId);
            if ($term) {
                return $term;
            }
        }

        return AcademicTerm::where('is_active', true)->first()
            ?? AcademicTerm::orderByDesc('id')->first();
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
}
