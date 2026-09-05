<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Reservation;

class AdminController extends Controller
{
    public function dashboard()
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $totalRooms = Room::count();

    $totalUsers = User::count();

    $pendingReservations = Reservation::where(
        'status',
        'Pending'
    )->count();

    $activeSchedules = Schedule::count();

    $reservations = Reservation::with([
        'room',
        'user'
    ])
    ->where('status', 'Pending')
    ->orderBy('created_at', 'desc')
    ->get();

    return view('admin.dashboard', compact(
        'totalRooms',
        'totalUsers',
        'pendingReservations',
        'activeSchedules',
        'reservations'
    ));
}

public function approveReservation(Reservation $reservation)
{
    $reservation->update([
        'status' => 'Approved'
    ]);

    return redirect()
        ->route('admin.dashboard')
        ->with(
            'success',
            'Reservation approved successfully.'
        );
}


public function declineReservation(Reservation $reservation)
{
    $reservation->update([
        'status' => 'Declined'
    ]);

    return redirect()
        ->route('admin.dashboard')
        ->with(
            'success',
            'Reservation declined successfully.'
        );
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
    $rooms = Room::with('schedules')->get();

    return view('admin.schedules', compact('rooms'));
}

public function viewSchedule(Room $room)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $schedules = Schedule::where('room_id', $room->id)
        ->orderBy('day')
        ->orderBy('time')
        ->get();

    return view('admin.view-schedule', compact('room', 'schedules'));
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
    ]);

    Department::create([
        'code' => strtoupper($request->code),
        'name' => $request->name,
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
    ]);

    $department->update([
        'code' => strtoupper($request->code),
        'name' => $request->name,
    ]);

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