<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class AdminController extends Controller
{
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
        'tv' => $request->has('tv'),
        'projector' => $request->has('projector'),
        'computers' => $request->computers ?? 0,
        'purpose' => $request->purpose,
        'description' => $request->description,
    ]);

    return back()->with('success', 'Room added successfully.');
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

    $building = Building::findOrFail($request->building_id);

    $room->update([
        'department_id' => $request->department_id,
        'building_id' => $building->id,
        'building' => $building->name,
        'room_name' => $request->room_name,
        'capacity' => $request->capacity,
        'tv' => $request->has('tv'),
        'projector' => $request->has('projector'),
        'computers' => $request->computers ?? 0,
        'purpose' => $request->purpose,
        'description' => $request->description,
    ]);

    return back()->with('success', 'Room updated successfully.');
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
}