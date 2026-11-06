<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Building;
use App\Models\Room;
use App\Models\RoomScheduleTimeSlot;
use App\Models\ScheduleCellMerge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Reservation;
use App\Models\RoomSwapRequest;
use App\Models\ScheduleMerge;
use App\Models\ScheduleOverride;
use App\Models\AcademicTerm;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $totalRooms = Room::count();
        $totalDepartments = Department::count();
        $totalBuildings = Building::count();
        $totalUsers = User::count();
        $activeSchedules = Schedule::count();

        return view('admin.dashboard', compact(
            'totalDepartments',
            'totalBuildings',
            'totalRooms',
            'totalUsers',
            'activeSchedules'
        ));
    }

    public function buildings()
    {
       if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $departments = Department::with([
        'buildings.rooms'
    ])->get();

    return view('admin.buildings', compact('departments'));
    }


    // Add Building
    public function storeBuilding(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
        ]);

          $building = Building::create([
            'department_id' => $request->department_id,
            'name' => $request->name,
        ]);

          return back()->with('success', 'Building added successfully.')
              ->with('building_added', true)
              ->with('building_department_id', $building->department_id)
              ->with('building_id', $building->id);
    }


    // Update Building
    public function updateBuilding(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
        ]);

        $building = Building::findOrFail($id);

        $building->update([
            'department_id' => $request->department_id,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Building updated successfully.');
    }


    // Delete Building
    public function destroyBuilding($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $building = Building::findOrFail($id);

        $building->delete();

        return back()->with('success', 'Building deleted successfully.');
    }


// Add Room
public function storeRoom(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $request->validate([
        'department_id' => 'required|exists:departments,id',
        'building_id' => 'required|exists:buildings,id',
        'room_name' => 'required|string|max:255',
        'capacity' => 'nullable|integer|min:1',
        'computers' => 'nullable|integer|min:0',
        'purpose' => 'nullable|string|max:100',
        'description' => 'nullable|string',
    ]);

    $building = Building::findOrFail($request->building_id);

      $room = Room::create([
        'department_id' => $request->department_id,
        'building_id' => $building->id,
        'building' => $building->name,
        'room_name' => $request->room_name,
        'capacity' => $request->capacity,

        // FIXED
        'has_tv' => $request->has('has_tv'),
        'has_projector' => $request->has('has_projector'),

        'computers' => $request->computers ?? 0,
        'purpose' => $request->purpose,
        'description' => $request->description,
    ]);

      return back()->with('success', 'Room added successfully.')
          ->with('room_added', true)
          ->with('room_department_id', $building->department_id)
          ->with('room_building_id', $building->id)
          ->with('room_id', $room->id);
}

// Update Room
public function updateRoom(Request $request, $id)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $request->validate([
        'department_id' => 'required|exists:departments,id',
        'building_id' => 'required|exists:buildings,id',
        'room_name' => 'required|string|max:255',
        'capacity' => 'nullable|integer|min:1',
        'computers' => 'nullable|integer|min:0',
        'purpose' => 'nullable|string|max:100',
        'description' => 'nullable|string',
    ]);

    $room = Room::findOrFail($id);

    $building = Building::findOrFail(
        $request->building_id
    );

    $room->update([

        'department_id' => $request->department_id,

        'building_id' => $building->id,

        'building' => $building->name,

        'room_name' => $request->room_name,

        'capacity' => $request->capacity,

        // FIXED
        'has_tv' => $request->has('has_tv'),

        'has_projector' =>
            $request->has('has_projector'),

        'computers' =>
            $request->computers ?? 0,

        'purpose' =>
            $request->purpose,

        'description' =>
            $request->description,
    ]);

    return back()->with(
        'success',
        'Room updated successfully.'
    );
}

// Delete Room
public function destroyRoom($id)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $room = Room::findOrFail($id);
    $room->delete();

    return back()->with('success', 'Room deleted successfully.');
}

public function schedules()
{
    // Only admin can access this page
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    /*
    |--------------------------------------------------------------------------
    | Load the complete hierarchy
    |--------------------------------------------------------------------------
    |
    | Department
    |     -> Buildings
    |          -> Rooms
    |               -> Schedules
    |
    | IMPORTANT:
    | We keep buildings and rooms as Eloquent Collections/models because
    | admin/schedules.blade.php uses:
    |
    |     $department['buildings']->count()
    |     $building['rooms'] as $room
    |     $room->id
    |     $room->room_name
    |
    */

    $departments = Department::with([
        'buildings.rooms.schedules' => function ($query) {
            $query->with([
                'merges' => function ($mergeQuery) {
                    $mergeQuery->where('status', 'approved')->with([
                        'subject.program.department',
                        'facultySubject.subjectCatalog.program.department',
                    ]);
                },
            ]);
        },
    ])
    ->orderBy('name')
    ->get();

    return view('admin.schedules', compact('departments'));
}
public function viewSchedule(Room $room)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    RoomSwapRequest::expireElapsedTemporarySwaps();

    $room->load(['department', 'building']);

    $activeTerm = null;
    $sessionTermId = session('active_academic_term_id');

    if ($sessionTermId) {
        $activeTerm = AcademicTerm::where('id', (int) $sessionTermId)
            ->where('is_active', true)
            ->first();
    }

    if (!$activeTerm) {
        $activeTerm = AcademicTerm::where('is_active', true)
            ->orderByDesc('id')
            ->first();
    }

    if ($activeTerm) {
        session(['active_academic_term_id' => $activeTerm->id]);
    }

    $schedules = Schedule::query()
        ->where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm?->id ?? 0)
        ->orderByRaw('CASE WHEN row_position IS NULL THEN 999 ELSE row_position END')
        ->orderBy('id')
        ->get();

    $schedules->loadMissing('facultySubject.subjectCatalog.program');

    if (request()->ajax() || request()->wantsJson()) {
        $today = Carbon::today()->format('Y-m-d');
        $nowTime = Carbon::now()->format('H:i:s');

        Reservation::whereRaw('LOWER(TRIM(status)) = ?', ['reserved'])
            ->where(function ($query) use ($today, $nowTime) {
                $query->whereDate('date', '<', $today)
                    ->orWhere(function ($query) use ($today, $nowTime) {
                        $query->whereDate('date', $today)
                            ->where('end_time', '<=', $nowTime);
                    });
            })
            ->update(['status' => 'Expired']);

        $roomSwapsBySchedule = [];
        $swaps = RoomSwapRequest::with([
            'requesterRoom',
            'targetRoom',
            'requesterSchedule',
            'targetSchedule',
        ])
            ->whereRaw('LOWER(TRIM(status)) = ?', ['approved'])
            ->where('swap_type', 'temporary')
            ->where(function ($query) use ($room) {
                $query->where('requester_room_id', $room->id)
                    ->orWhere('target_room_id', $room->id);
            })
            ->whereHas('requesterSchedule', fn ($query) => $query->where('academic_term_id', $activeTerm?->id ?? 0))
            ->whereHas('targetSchedule', fn ($query) => $query->where('academic_term_id', $activeTerm?->id ?? 0))
            ->whereDate('swap_date', '>=', $today)
            ->get();

        foreach ($swaps as $swap) {
            if (!$swap->swap_date || !$swap->start_time || !$swap->end_time) {
                continue;
            }

            try {
                $date = Carbon::parse($swap->swap_date)->format('Y-m-d');
                $start = Carbon::parse($date . ' ' . $swap->getRawOriginal('start_time'));
                $end = Carbon::parse($date . ' ' . $swap->getRawOriginal('end_time'));
                $now = Carbon::now();
                if ($end->lessThanOrEqualTo($now)) {
                    continue;
                }
            } catch (\Throwable $exception) {
                continue;
            }

            $swapInfo = [
                'swap_type' => 'temporary',
                'active' => $now->greaterThanOrEqualTo($start),
                'scheduled' => $now->lessThan($start),
                'swap_id' => $swap->id,
                'swap_date' => $date,
                'start_at' => $start->format('Y-m-d\TH:i:s'),
                'end_at' => $end->format('Y-m-d\TH:i:s'),
                'start_time' => $start->format('g:i A'),
                'end_time' => $end->format('g:i A'),
                'reason' => $swap->reason,
            ];

            if ((int) $swap->requester_room_id === (int) $room->id) {
                $leavingSchedule = $swap->requesterSchedule;
                $swappedSchedule = $swap->targetSchedule;
                $originalRoom = $swap->targetRoom;
                $displayRoom = $swap->requesterRoom;
            } else {
                $leavingSchedule = $swap->targetSchedule;
                $swappedSchedule = $swap->requesterSchedule;
                $originalRoom = $swap->requesterRoom;
                $displayRoom = $swap->targetRoom;
            }

            if (!$swappedSchedule || ($activeTerm && (int) $swappedSchedule->academic_term_id !== (int) $activeTerm->id)) {
                continue;
            }

            if ($leavingSchedule) {
                $schedules = $schedules
                    ->reject(fn ($item) => (int) $item->id === (int) $leavingSchedule->id)
                    ->values();
            }

            if (!$schedules->contains(fn ($item) => (int) $item->id === (int) $swappedSchedule->id)) {
                $schedules->push($swappedSchedule);
            }

            $roomSwapsBySchedule[$swappedSchedule->id] = array_merge($swapInfo, [
                'original_room_id' => $originalRoom?->id,
                'original_room_name' => $originalRoom?->room_name ?? 'Original room',
                'swapped_to_room_id' => $displayRoom?->id,
                'swapped_to_room_name' => $displayRoom?->room_name ?? $room->room_name,
            ]);
        }

        $schedules = $schedules->sortBy([
            ['row_position', 'asc'],
            ['id', 'asc'],
        ])->values();

        // Include merged program labels for both room-owned and swapped classes.
        $mergedPrograms = [];
        $allMerges = ScheduleMerge::with([
            'subject.program.department',
            'facultySubject.subjectCatalog.program.department',
        ])
            ->whereIn('schedule_id', $schedules->pluck('id'))
            ->where('status', 'approved')
            ->get();

        $mergedCourses = [];
        foreach ($allMerges as $merge) {
            $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
            $program = $subject?->program?->code
                ?? $merge->facultySubject?->subjectCatalog?->program?->code
                ?? $merge->facultySubject?->program;
            if ($program) {
                $mergedPrograms[$merge->schedule_id] ??= [];
                if (!in_array($program, $mergedPrograms[$merge->schedule_id], true)) {
                    $mergedPrograms[$merge->schedule_id][] = $program;
                }
            }

            if ($subject) {
                $mergedCourses[$merge->schedule_id] ??= [];
                $mergedCourses[$merge->schedule_id][] = [
                    'course_code' => $subject->course_code ?? $merge->facultySubject?->course_code,
                    'course_title' => $subject->course_title ?? $merge->facultySubject?->subject,
                    'program' => $program,
                    'major' => $subject->program?->major ?? $merge->facultySubject?->major,
                    'share_type' => $merge->share_type ?? 'merge',
                ];
            }
        }

        $programCodesByName = \App\Models\Program::query()
            ->whereIn('name', $schedules->pluck('program')->filter()->unique()->values())
            ->pluck('code', 'name')
            ->all();
        $programCodeForSchedule = static function ($schedule) use ($programCodesByName) {
            $code = trim((string) ($schedule->facultySubject?->subjectCatalog?->program?->code ?? ''));
            if ($code !== '') return $code;
            $label = trim((string) ($schedule->program ?? ''));
            if (preg_match('/\(([^()]*)\)\s*$/u', $label, $matches) === 1) return trim($matches[1]);
            return trim((string) ($programCodesByName[$label] ?? $label));
        };

        $overrideCandidates = ScheduleOverride::query()
            ->where(function ($query) use ($schedules, $room) {
                $query->whereIn('schedule_id', $schedules->pluck('id')->unique())
                    ->orWhere('room_id', $room->id);
            })
            ->where('status', 'active')
            ->whereDate('override_date', '>=', $today)
            ->where('expires_at', '>', now())
            ->orderBy('override_date')
            ->orderBy('start_time')
            ->get();

        $parseOverrideMinute = static function ($value): ?int {
            if ($value instanceof \DateTimeInterface) {
                return ((int) $value->format('H') * 60) + (int) $value->format('i');
            }

            $raw = trim((string) $value);
            if ($raw === '') return null;

            try {
                $time = Carbon::parse(str_contains($raw, 'T') ? $raw : '2000-01-01 ' . $raw);
                return ($time->hour * 60) + $time->minute;
            } catch (\Throwable $exception) {
                return null;
            }
        };

        $scheduleOverridesBySchedule = [];
        foreach ($schedules as $schedule) {
            $scheduleDay = strtoupper(substr(trim((string) ($schedule->day_of_week ?: $schedule->day)), 0, 3));
            $scheduleStart = $parseOverrideMinute($schedule->start_time);
            $scheduleEnd = $parseOverrideMinute($schedule->end_time);
            if (($scheduleStart === null || $scheduleEnd === null) && preg_match('/^\s*(.+?)\s*(?:-|–|—|to)\s*(.+?)\s*$/i', (string) $schedule->time, $timeParts)) {
                $scheduleStart = $parseOverrideMinute($timeParts[1]);
                $scheduleEnd = $parseOverrideMinute($timeParts[2]);
            }

            $scheduleOverridesBySchedule[$schedule->id] = $overrideCandidates
                ->filter(function (ScheduleOverride $override) use ($schedule, $room, $scheduleDay, $scheduleStart, $scheduleEnd, $parseOverrideMinute) {
                    $directScheduleMatch = (int) $override->schedule_id === (int) $schedule->id;
                    if ($override->room_id && (int) $override->room_id !== (int) $room->id) return false;
                    if (!$directScheduleMatch && (int) $override->room_id !== (int) $room->id) return false;
                    if (!$override->override_date) return false;
                    if (strtoupper($override->override_date->format('D')) !== $scheduleDay) return false;

                    $overrideStart = $parseOverrideMinute($override->start_time);
                    $overrideEnd = $parseOverrideMinute($override->end_time);
                    if ($scheduleStart === null || $scheduleEnd === null || $overrideStart === null || $overrideEnd === null) return false;

                    return $scheduleStart < $overrideEnd && $scheduleEnd > $overrideStart;
                })
                ->values();
        }

        $scheduleData = $schedules->map(function ($schedule) use ($mergedPrograms, $mergedCourses, $roomSwapsBySchedule, $scheduleOverridesBySchedule, $room, $programCodeForSchedule) {
            $roomSwap = $roomSwapsBySchedule[$schedule->id] ?? null;
            $scheduleOverrides = ($scheduleOverridesBySchedule[$schedule->id] ?? collect())
                ->map(function (ScheduleOverride $override) use ($room) {
                    return [
                        'id' => (int) $override->id,
                        'room_id' => (int) ($override->room_id ?: $room->id),
                        'day' => strtoupper($override->override_date?->format('D') ?? ''),
                        'date' => $override->override_date?->format('Y-m-d'),
                        'start_time' => $override->start_time ? Carbon::parse($override->start_time)->format('H:i') : null,
                        'end_time' => $override->end_time ? Carbon::parse($override->end_time)->format('H:i') : null,
                        'occupied_by' => $override->occupied_by,
                        'reason' => $override->reason,
                        'details' => $override->details,
                        'state' => $override->override_date?->isToday() ? 'today' : 'scheduled',
                    ];
                })
                ->values();

            return [
                'id' => $schedule->id,
                'course_code' => $schedule->course_code,
                'subject' => $schedule->subject,
                'subject_type' => $schedule->subject_type,
                'program' => $schedule->program,
                'program_code' => $programCodeForSchedule($schedule),
                'major' => $schedule->major,
                'year_level' => $schedule->year_level,
                'day' => strtoupper(trim((string) $schedule->day)),
                'time' => trim((string) $schedule->time),
                'row_position' => $schedule->row_position !== null ? (int) $schedule->row_position : null,
                'instructor' => $schedule->instructor,
                'color' => $schedule->color ?? '#ffffff',
                'merged_programs' => $mergedPrograms[$schedule->id] ?? [],
                'merged_courses' => $mergedCourses[$schedule->id] ?? [],
                'room_id' => $roomSwap['swapped_to_room_id'] ?? $room->id,
                'room_name' => $roomSwap['swapped_to_room_name'] ?? $room->room_name,
                'original_room_id' => $roomSwap['original_room_id'] ?? $room->id,
                'original_room_name' => $roomSwap['original_room_name'] ?? null,
                'room_swap' => $roomSwap,
                'is_room_swap_active' => (bool) ($roomSwap['active'] ?? false),
                'schedule_overrides' => $scheduleOverrides,
            ];
        })->values();

        $reservations = Reservation::with(['user', 'facultySubject', 'subject.program'])
            ->where('room_id', $room->id)
            ->where('academic_term_id', $activeTerm?->id ?? 0)
            ->whereRaw('LOWER(TRIM(status)) = ?', ['reserved'])
            ->whereDate('date', '>=', $today)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'user_id' => $reservation->user_id,
                    'status' => $reservation->status,
                    'date' => $reservation->date ? Carbon::parse($reservation->date)->format('Y-m-d') : null,
                    'day' => strtoupper(trim((string) ($reservation->day ?? Carbon::parse($reservation->date)->format('D')))),
                    'time' => trim((string) $reservation->time),
                    'start_time' => $reservation->start_time ? Carbon::parse($reservation->start_time)->format('H:i') : null,
                    'end_time' => $reservation->end_time ? Carbon::parse($reservation->end_time)->format('H:i') : null,
                    'purpose' => $reservation->purpose ?? 'Room Reservation',
                    'course_code' => $reservation->subject?->course_code ?? $reservation->facultySubject?->course_code,
                    'subject' => $reservation->subject?->course_title ?? $reservation->facultySubject?->subject,
                    'user_name' => $reservation->user?->name ?? 'Instructor',
                ];
            })
            ->values();

        $roomOverrides = ScheduleOverride::query()
            ->where('room_id', $room->id)
            ->whereNull('schedule_id')
            ->where('status', 'active')
            ->whereDate('override_date', '>=', $today)
            ->where('expires_at', '>', now())
            ->orderBy('override_date')
            ->orderBy('start_time')
            ->get()
            ->map(function (ScheduleOverride $override) {
                return [
                    'id' => (int) $override->id,
                    'room_id' => (int) $override->room_id,
                    'day' => strtoupper($override->override_date?->format('D') ?? ''),
                    'date' => $override->override_date?->format('Y-m-d'),
                    'start_time' => $override->start_time ? Carbon::parse($override->start_time)->format('H:i') : null,
                    'end_time' => $override->end_time ? Carbon::parse($override->end_time)->format('H:i') : null,
                    'reason' => $override->reason,
                    'occupied_by' => $override->occupied_by,
                    'details' => $override->details,
                ];
            })
            ->values();

        $buildingName = is_object($room->building)
            ? ($room->building->name ?? $room->building->building_name ?? 'Building')
            : ($room->building ?: 'Building');

        $roomTimeSlots = RoomScheduleTimeSlot::query()
            ->where('room_id', $room->id)
            ->where('academic_term_id', $activeTerm?->id ?? 0)
            ->where('is_active', true)
            ->orderByRaw('CASE WHEN row_position IS NULL THEN 999999 ELSE row_position END')
            ->orderBy('start_time')
            ->get()
            ->map(function ($slot) {
                return [
                    'id' => (int) $slot->id,
                    'day' => strtoupper(trim((string) $slot->day)),
                    'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                    'time' => Carbon::parse($slot->start_time)->format('g:i')
                        . '-'
                        . Carbon::parse($slot->end_time)->format('g:i'),
                    'row_position' => $slot->row_position !== null ? (int) $slot->row_position : null,
                ];
            })
            ->values();

        $manualMerges = $activeTerm
            ? ScheduleCellMerge::where('room_id', $room->id)
                ->where('academic_term_id', $activeTerm->id)
                ->orderBy('id')
                ->get(['axis', 'cells'])
                ->map(fn ($merge) => [
                    'axis' => $merge->axis,
                    'cells' => $merge->cells,
                ])
                ->values()
            : collect();

        return response()->json([
            'success' => true,
            'room' => [
                'id' => $room->id,
                'room_name' => $room->room_name,
                'building' => $buildingName,
                'department' => $room->department?->name ?? 'College',
            ],
            'schedules' => $scheduleData,
            'reservations' => $reservations,
            'room_overrides' => $roomOverrides,
            'room_time_slots' => $roomTimeSlots,
            'manual_merges' => $manualMerges,
            'active_term' => $activeTerm ? [
                'id' => $activeTerm->id,
                'academic_year' => $activeTerm->academic_year,
                'semester' => $activeTerm->semester,
            ] : null,
        ]);
    }

    return view('admin.view-schedule', compact('room', 'schedules', 'activeTerm'));
}
public function overrideRequest()
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    return view('admin.overriderequest');
}

public function moveRoom(Request $request)
{
    // Only admin can move rooms
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'department_id' => 'required|exists:departments,id',
        'building_id' => 'required|exists:buildings,id',
    ]);


    // Find the room
    $room = Room::findOrFail($request->room_id);


    // Make sure the selected building
    // belongs to the selected department
    $building = Building::where('id', $request->building_id)
        ->where('department_id', $request->department_id)
        ->first();


    if (!$building) {

        return back()
            ->withErrors([
                'building_id' =>
                    'The selected building does not belong to the selected college.'
            ]);
    }


    // Move the room
    $room->update([

        'department_id' => $request->department_id,

        'building_id' => $building->id,

        // IMPORTANT:
        // Your rooms table also stores the building name
        'building' => $building->name,

    ]);


    return back()->with(
        'success',
        'Room "' . $room->room_name . '" moved successfully to '
        . $building->name . '.'
    );
}

/*
|--------------------------------------------------------------------------
| MANAGE DEPARTMENTS
|--------------------------------------------------------------------------
*/

// Show departments
public function departments()
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $departments = Department::withCount('buildings')
        ->orderBy('name')
        ->get();

    return view(
        'admin.managedepartments',
        compact('departments')
    );
}


// Add Department
public function storeDepartment(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $request->validate([
        'code' => 'required|string|max:50|unique:departments,code',
        'name' => 'required|string|max:255|unique:departments,name',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $logoPath = $request->file('logo')?->store('departments', 'public');

    Department::create([
        'code' => strtoupper($request->code),
        'name' => $request->name,
        'logo' => $logoPath,
    ]);

    return redirect()
        ->route('admin.departments')
        ->with(
            'success',
            'College added successfully.'
        );
}


// Update Department
public function updateDepartment(
    Request $request,
    $id
) {
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $department = Department::findOrFail($id);

    $request->validate([
        'code' =>
            'required|string|max:50|unique:departments,code,' . $id,

        'name' =>
            'required|string|max:255|unique:departments,name,' . $id,
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $departmentData = [
        'code' => strtoupper($request->code),
        'name' => $request->name,
    ];

    if ($request->hasFile('logo')) {
        $newLogoPath = $request->file('logo')->store('departments', 'public');
        if ($department->logo) {
            Storage::disk('public')->delete($department->logo);
        }
        $departmentData['logo'] = $newLogoPath;
    }

    $department->update($departmentData);

    return redirect()
        ->route('admin.departments')
        ->with(
            'success',
            'College updated successfully.'
        );
}


// Delete Department
public function destroyDepartment($id)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $department = Department::findOrFail($id);


    // Prevent deletion if department
    // still has buildings

    if ($department->buildings()->exists()) {

        return redirect()
            ->route('admin.departments')
            ->with(
                'error',
                'This department cannot be deleted because it still has buildings. Move or delete its buildings first.'
            );
    }


    $department->delete();

    return redirect()
        ->route('admin.departments')
        ->with(
            'success',
            'College deleted successfully.'
        );
}
}

