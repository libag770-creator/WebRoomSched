<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

    // Get users from Manage Users
    // Only faculty users can be assigned as instructors
    $users = User::where('role', 'faculty')
                 ->orderBy('name', 'asc')
                 ->get();

    return view('chair.excel', [
        'room' => $room,
        'users' => $users
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
        // Check if instructor exists in Manage Users
        $instructor = User::where('id', $data['instructor_id'] ?? null)
                          ->where('role', 'faculty')
                          ->first();

        // If instructor does not exist
        if (!$instructor)
        {
            return back()
                ->with('error', 'User not found. Please select a valid faculty instructor.');
        }

        Schedule::create([

            'room_id' => $request->room_id,

            'day' => $data['day'],

            'time' => $data['time'],

            'course_code' => $data['course_code'] ?? null,

            'subject' => $data['subject'] ?? null,

            // Save the actual name from the users table
            'instructor' => $instructor->name,

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
    /*
    |--------------------------------------------------------------------------
    | Make sure reservation is still pending
    |--------------------------------------------------------------------------
    */

    if ($reservation->status !== 'Pending') {

        return redirect()
            ->route('chair.dashboard')
            ->with(
                'error',
                'This reservation has already been processed.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Make sure reservation has valid start/end times
    |--------------------------------------------------------------------------
    */

    if (
        !$reservation->start_time ||
        !$reservation->end_time
    ) {

        return redirect()
            ->route('chair.dashboard')
            ->with(
                'error',
                'This reservation does not have a valid start and end time.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Check for overlapping approved reservation
    |--------------------------------------------------------------------------
    */

    $overlap = Reservation::where(
        'room_id',
        $reservation->room_id
    )
    ->where(
        'date',
        $reservation->date
    )
    ->where(
        'status',
        'Approved'
    )
    ->where(
        'id',
        '!=',
        $reservation->id
    )
    ->where(function ($query) use ($reservation) {

        $query
            ->where(
                'start_time',
                '<',
                $reservation->end_time
            )
            ->where(
                'end_time',
                '>',
                $reservation->start_time
            );

    })
    ->exists();


    /*
    |--------------------------------------------------------------------------
    | Don't approve overlapping reservation
    |--------------------------------------------------------------------------
    */

    if ($overlap) {

        return redirect()
            ->route('chair.dashboard')
            ->with(
                'error',
                'This room is already reserved during the selected time.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Check class schedule
    |--------------------------------------------------------------------------
    */

    $day = strtoupper(
        Carbon::parse($reservation->date)->format('D')
    );


    $occupied = Schedule::where(
        'room_id',
        $reservation->room_id
    )
    ->where(
        'day',
        $day
    )
    ->where(
        'time',
        $reservation->time
    )
    ->exists();


    /*
    |--------------------------------------------------------------------------
    | Don't approve if room has a class
    |--------------------------------------------------------------------------
    */

    if ($occupied) {

        return redirect()
            ->route('chair.dashboard')
            ->with(
                'error',
                'This room already has a scheduled class during the selected time.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve reservation
    |--------------------------------------------------------------------------
    */

    $reservation->update([
        'status' => 'Approved'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('chair.dashboard')
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
        ->route('chair.dashboard')
        ->with('success', 'Reservation declined.');
}


public function vacantRooms(Request $request)
{
    $rooms = Room::with('department')->get();

    $results = [];

    if ($request->filled('date') && $request->filled('time')) {

        /*
        |--------------------------------------------------------------------------
        | Get selected date
        |--------------------------------------------------------------------------
        */

        $day = strtoupper(
            Carbon::parse($request->date)->format('D')
        );


        /*
        |--------------------------------------------------------------------------
        | Convert selected time
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | 8:00-9:00
        |
        | becomes:
        |
        | start = 08:00
        | end   = 09:00
        |
        */

        $timeParts = explode('-', $request->time);

        $searchStart = Carbon::createFromFormat(
            'H:i',
            trim($timeParts[0])
        );

        $searchEnd = Carbon::createFromFormat(
            'H:i',
            trim($timeParts[1])
        );


        /*
        |--------------------------------------------------------------------------
        | Check every room
        |--------------------------------------------------------------------------
        */

        foreach ($rooms as $room) {


            /*
            |--------------------------------------------------------------------------
            | Check Class Schedule
            |--------------------------------------------------------------------------
            */

            $occupied = Schedule::where(
                'room_id',
                $room->id
            )
            ->where(
                'day',
                $day
            )
            ->where(
                'time',
                $request->time
            )
            ->exists();


            /*
            |--------------------------------------------------------------------------
            | Check Approved Reservation
            |--------------------------------------------------------------------------
            |
            | We check whether the reservation overlaps
            | the selected time.
            |
            | Example:
            |
            | Reservation: 8:00 - 9:00
            |
            | Search:      8:00 - 9:00
            | Result:      RESERVED
            |
            | Reservation: 8:00 - 9:00
            |
            | Search:      9:00 - 10:00
            | Result:      AVAILABLE
            |
            */

            $reserved = false;


            $reservations = Reservation::where(
                'room_id',
                $room->id
            )
            ->where(
                'date',
                $request->date
            )
            ->where(
                'status',
                'Approved'
            )
            ->get();


            foreach ($reservations as $reservation) {

                /*
                |--------------------------------------------------------------------------
                | Make sure reservation has start/end times
                |--------------------------------------------------------------------------
                */

                if (
                    !$reservation->start_time ||
                    !$reservation->end_time
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Convert reservation times
                |--------------------------------------------------------------------------
                */

                $reservationStart = Carbon::createFromFormat(
                    'H:i:s',
                    $reservation->start_time
                );

                $reservationEnd = Carbon::createFromFormat(
                    'H:i:s',
                    $reservation->end_time
                );


                /*
                |--------------------------------------------------------------------------
                | Check overlap
                |--------------------------------------------------------------------------
                */

                if (
                    $reservationStart->lt($searchEnd) &&
                    $reservationEnd->gt($searchStart)
                ) {

                    $reserved = true;

                    break;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Determine Room Status
            |--------------------------------------------------------------------------
            */

            if ($occupied) {

                $status = 'Occupied';

            } elseif ($reserved) {

                $status = 'Reserved';

            } else {

                $status = 'Available';
            }


            /*
            |--------------------------------------------------------------------------
            | Add room to results
            |--------------------------------------------------------------------------
            */

            $results[] = [
                'room' => $room,
                'status' => $status
            ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'faculty.vacant-rooms',
        compact(
            'rooms',
            'results'
        )
    );
}
}