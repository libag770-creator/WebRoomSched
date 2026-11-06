<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Reservation;
use App\Models\RoomSwapRequest;
use App\Models\ScheduleMerge;
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

        Building::create([
            'department_id' => $request->department_id,
            'name' => $request->name,
        ]);

        return back()->with('success', 'Building added successfully.');
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

    Room::create([
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

    return back()->with(
        'success',
        'Room added successfully.'
    );
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
        'buildings.rooms.schedules'
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
        ->when($activeTerm, fn ($query) => $query->where('academic_term_id', $activeTerm->id))
        ->orderByRaw('CASE WHEN row_position IS NULL THEN 999 ELSE row_position END')
        ->orderBy('id')
        ->get();

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
            ->where(function ($query) use ($room) {
                $query->where('requester_room_id', $room->id)
                    ->orWhere('target_room_id', $room->id);
            })
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
                $swappedSchedule = $swap->targetSchedule;
                $originalRoom = $swap->targetRoom;
                $displayRoom = $swap->requesterRoom;
            } else {
                $swappedSchedule = $swap->requesterSchedule;
                $originalRoom = $swap->requesterRoom;
                $displayRoom = $swap->targetRoom;
            }

            if (!$swappedSchedule || ($activeTerm && (int) $swappedSchedule->academic_term_id !== (int) $activeTerm->id)) {
                continue;
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
        $allMerges = ScheduleMerge::with('facultySubject')
            ->whereIn('schedule_id', $schedules->pluck('id'))
            ->where('status', 'approved')
            ->get();

        foreach ($allMerges as $merge) {
            $program = trim((string) $merge->facultySubject?->program);
            if ($program === '') {
                continue;
            }

            $mergedPrograms[$merge->schedule_id] ??= [];
            if (!in_array($program, $mergedPrograms[$merge->schedule_id], true)) {
                $mergedPrograms[$merge->schedule_id][] = $program;
            }
        }

        $scheduleData = $schedules->map(function ($schedule) use ($mergedPrograms, $roomSwapsBySchedule, $room) {
            $roomSwap = $roomSwapsBySchedule[$schedule->id] ?? null;

            return [
                'id' => $schedule->id,
                'course_code' => $schedule->course_code,
                'subject' => $schedule->subject,
                'subject_type' => $schedule->subject_type,
                'program' => $schedule->program,
                'major' => $schedule->major,
                'year_level' => $schedule->year_level,
                'day' => strtoupper(trim((string) $schedule->day)),
                'time' => trim((string) $schedule->time),
                'row_position' => $schedule->row_position !== null ? (int) $schedule->row_position : null,
                'instructor' => $schedule->instructor,
                'color' => $schedule->color ?? '#ffffff',
                'merged_programs' => $mergedPrograms[$schedule->id] ?? [],
                'room_id' => $roomSwap['swapped_to_room_id'] ?? $room->id,
                'room_name' => $roomSwap['swapped_to_room_name'] ?? $room->room_name,
                'original_room_id' => $roomSwap['original_room_id'] ?? $room->id,
                'original_room_name' => $roomSwap['original_room_name'] ?? null,
                'room_swap' => $roomSwap,
                'is_room_swap_active' => (bool) ($roomSwap['active'] ?? false),
            ];
        })->values();

        $reservations = Reservation::with(['user', 'facultySubject'])
            ->where('room_id', $room->id)
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
                    'course_code' => $reservation->facultySubject?->course_code,
                    'subject' => $reservation->facultySubject?->subject,
                    'user_name' => $reservation->user?->name ?? 'Faculty',
                ];
            })
            ->values();

        $buildingName = is_object($room->building)
            ? ($room->building->name ?? $room->building->building_name ?? 'Building')
            : ($room->building ?: 'Building');

        return response()->json([
            'success' => true,
            'room' => [
                'id' => $room->id,
                'room_name' => $room->room_name,
                'building' => $buildingName,
                'department' => $room->department?->name ?? 'Department',
            ],
            'schedules' => $scheduleData,
            'reservations' => $reservations,
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
                    'The selected building does not belong to the selected department.'
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
            'Department added successfully.'
        );
}


// Edit Department
public function editDepartment($id)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $department = Department::findOrFail($id);

    return view(
        'admin.edit-department',
        compact('department')
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
            'Department updated successfully.'
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
            'Department deleted successfully.'
        );
}
}

