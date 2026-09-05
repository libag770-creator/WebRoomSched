<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FacultyController extends Controller
{
    public function dashboard()
{
    $now = Carbon::now();

    $reservations = Reservation::with('room')
        ->where('user_id', Auth::id())
        ->where('status', 'Approved')
        ->get()
        ->filter(function ($reservation) use ($now) {

            // If old reservation has no start/end time,
            // keep it visible for compatibility
            if (!$reservation->start_time || !$reservation->end_time) {
                return true;
            }

            $endDateTime = Carbon::parse(
                $reservation->date . ' ' . $reservation->end_time
            );

            // Keep only reservations that have not ended yet
            return $endDateTime->greaterThan($now);
        })
        ->sortBy(function ($reservation) {
            return $reservation->date . ' ' . $reservation->start_time;
        })
        ->values();

    return view('faculty.dashboard', compact('reservations'));
}

  public function reserveRoom(Request $request, Room $room)
{
    $request->validate([
        'date' => 'required|date',
        'time' => 'required|string',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'purpose' => 'required|string|max:255',
    ]);

    Reservation::create([
        'room_id' => $room->id,
        'user_id' => auth()->id(),
        'date' => $request->date,
        'day' => strtoupper(
            date('D', strtotime($request->date))
        ),
        'time' => $request->time,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'purpose' => $request->purpose,
        'status' => 'Pending',
    ]);

    return redirect()
        ->route('faculty.vacant')
        ->with(
            'success',
            'Room reservation submitted. Waiting for Chair approval.'
        );
}

public function vacantRooms(Request $request)
{
    $rooms = Room::with('department')->get();

    $results = [];

    if ($request->filled('date') && $request->filled('time')) {

        $day = strtoupper(
            Carbon::parse($request->date)->format('D')
        );

        foreach ($rooms as $room) {

            /*
            | Check regular class schedule
            */

            $occupied = Schedule::where('room_id', $room->id)
                ->where('day', $day)
                ->where('time', $request->time)
                ->exists();


            /*
            | Check approved reservation
            */

            $reserved = false;

            $reservations = Reservation::where('room_id', $room->id)
                ->where('date', $request->date)
                ->where('status', 'Approved')
                ->get();

            foreach ($reservations as $reservation) {

                $reservationStart = Carbon::parse(
                    $reservation->date . ' ' . $reservation->start_time
                );

                $reservationEnd = Carbon::parse(
                    $reservation->date . ' ' . $reservation->end_time
                );


                /*
                | Requested time
                */

                $requestedStart = Carbon::parse(
                    $request->date . ' ' .
                    trim(explode('-', $request->time)[0])
                );

                $requestedEnd = Carbon::parse(
                    $request->date . ' ' .
                    trim(explode('-', $request->time)[1])
                );


                /*
                | Check whether the requested time overlaps
                */

                if (
                    $requestedStart < $reservationEnd &&
                    $requestedEnd > $reservationStart
                ) {

                    $reserved = true;

                    break;
                }
            }


            /*
            | Determine room status
            */

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

    return view(
        'faculty.vacant-rooms',
        compact('rooms', 'results')
    );
}
}
