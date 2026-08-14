<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChairController extends Controller
{
   public function dashboard()
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $reservations = Reservation::with(['room', 'user'])
        ->where('status', 'Pending')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('chair.dashboard', compact('reservations'));
}

    // Open the schedule editor for a room
    public function index($room)
    {
        $room = Room::findOrFail($room);

        return view('chair.excel', [
            'room' => $room
        ]);
    }

    // Display all rooms in the Chair page
    public function setschedule()
{
    $rooms = Room::with(['department', 'schedules'])->get();

    return view('chair.setschedule', compact('rooms'));
}

    // Save schedule to the database
   public function saveSchedule(Request $request)
{
    foreach ($request->schedule as $data)
    {
        Schedule::create([

            'room_id' => $request->room_id,

            'day' => $data['day'],

            'time' => $data['time'],

            'course_code' => $data['course_code'] ?? null,

            'subject' => $data['subject'] ?? null,

            'instructor' => $data['instructor'] ?? null,

            'description' => $data['description'] ?? null,

            'color' => $data['color'] ?? '#ffffff',

        ]);
    }


    return redirect()
        ->route('chair.setschedule')
        ->with('success', 'Schedule uploaded successfully.');
}

    // Faculty schedule viewer
    public function viewSchedule(Room $room)
    {
        $schedules = Schedule::where('room_id', $room->id)->get();

        return view('faculty.view-schedule', compact('room', 'schedules'));
    }

    

   public function facultySchedules()
{
    $rooms = Room::with([
        'department',
        'schedules'
    ])->get();


    return view('faculty.schedules', compact('rooms'));
}



public function deleteSchedule(Room $room)
{
    Schedule::where('room_id', $room->id)->delete();

    return redirect()
        ->route('chair.setschedule')
        ->with('success', 'Schedule deleted successfully.');

}


public function reserveRoom(Request $request, Room $room)
{
    $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'purpose' => 'nullable|string',
    ]);

    Reservation::create([
        'room_id' => $room->id,
        'user_id' => auth()->id(),
        'date' => $request->date,
        'day' => strtoupper(Carbon::parse($request->date)->format('D')),
        'time' => $request->time,
        'purpose' => $request->purpose,
        'status' => 'Pending',
    ]);

    return redirect()
        ->route('faculty.vacant', [
            'date' => $request->date,
            'time' => $request->time,
        ])
        ->with('success', 'Reservation submitted. Waiting for admin approval.');
}



public function reservations()
{
    $reservations = Reservation::with(['room', 'user'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('chair.reservations', compact('reservations'));
}

public function approveReservation(Reservation $reservation)
{
    $reservation->update([
        'status' => 'Approved'
    ]);

    return redirect()
        ->route('chair.dashboard')
        ->with('success', 'Reservation approved successfully.');
}


public function declineReservation(Reservation $reservation)
{
    $reservation->update([
        'status' => 'Declined'
    ]);

    return redirect()
        ->route('chair.dashboard')
        ->with('success', 'Reservation declined.');
}


public function vacantRooms(Request $request)
{
    $rooms = Room::with('department')->get();

    $results = [];

    if ($request->filled('date') && $request->filled('time')) {

        $day = strtoupper(Carbon::parse($request->date)->format('D'));

        foreach ($rooms as $room) {

            // Check class schedule
            $occupied = Schedule::where('room_id', $room->id)
                ->where('day', $day)
                ->where('time', $request->time)
                ->exists();

            // Check approved reservation
            $reserved = Reservation::where('room_id', $room->id)
                ->where('date', $request->date)
                ->where('time', $request->time)
                ->where('status', 'Approved')
                ->exists();

            if ($occupied) {
                $status = 'Occupied';
            } elseif ($reserved) {
                $status = 'Reserved';
            } else {
                $status = 'Available';
            }

            $results[] = [
                'room' => $room,
                'status' => $status
            ];
        }
    }
    

    return view('faculty.vacant-rooms', compact('results'));
}
}