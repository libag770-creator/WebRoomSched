<?php

namespace App\Http\Controllers;

use App\Models\RoomSwapRequest;
use App\Models\Schedule;
use App\Models\Reservation;
use App\Models\Department;
use App\Models\Building;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomSwapRequestController extends Controller
{
    
    // showsroomswap page
   public function create()
{
    $user = Auth::user();

    // shows schedule
    $mySchedules = Schedule::with('room')
        ->where('instructor', $user->name)
        ->get();


    // shows approved reservations
    $myReservations = Reservation::with('room')
        ->where('user_id', $user->id)
        ->where('status', 'Approved')
        ->get();

    // departments
    $departments = Department::orderBy('name')->get();

    // buildings
    $buildings = Building::orderBy('name')->get();


    // faculty schedule 2
    $otherSchedules = Schedule::with([
        'room',
    ])
    ->where('instructor', '!=', $user->name)
    ->get();


    // approved reservation 2
    $otherReservations = Reservation::with([
        'room',
        'user',
    ])
    ->where('user_id', '!=', $user->id)
    ->where('status', 'Approved')
    ->get();


    //    build targetbookings
    $targetBookings = collect();

    // faculty schedule 2
    foreach ($otherSchedules as $schedule) {

        $faculty = User::where(
            'name',
            $schedule->instructor
        )->first();

        if (!$faculty) {
            continue;
        }

        $targetBookings->push([

            'type' => 'schedule',

            'id' => $schedule->id,

            'room_id' => $schedule->room_id,

            'room_name' => $schedule->room->room_name ?? 'Unknown Room',

            'user_id' => $faculty->id,

            'user_name' => $faculty->name,

            'department_id' =>
                $schedule->room->department_id ?? null,

            'department_name' =>
                $schedule->room->department->name ?? 'Unknown Department',

            'building_id' =>
                $schedule->room->building_id ?? null,

            'date' => now()->format('Y-m-d'),

            'time' => $schedule->time,

        ]);
    }


    // faculty reservations 2
    foreach ($otherReservations as $reservation) {

        $targetBookings->push([

            'type' => 'reservation',

            'id' => $reservation->id,

            'room_id' => $reservation->room_id,

            'room_name' =>
                $reservation->room->room_name ?? 'Unknown Room',

            'user_id' => $reservation->user_id,

            'user_name' =>
                $reservation->user->name ?? 'Unknown Faculty',

            'department_id' =>
                $reservation->room->department_id ?? null,

            'department_name' =>
                $reservation->room->department->name ?? 'Unknown Department',

            'building_id' =>
                $reservation->room->building_id ?? null,

            'date' => $reservation->date,

            'time' => $reservation->time,

        ]);
    }


    // return room swap page
    return view(
        'faculty.room-swap',
        compact(
            'mySchedules',
            'myReservations',
            'targetBookings',
            'departments',
            'buildings'
        )
    );
}



    //    sends room swao request
   public function store(Request $request)
{
    $user = Auth::user();

    // validate request
    $request->validate([

        // Faculty B
        'target_user_id' =>
            'required|exists:users,id',

        // Booking types
        'requester_type' =>
            'required|in:schedule,reservation',

        'target_type' =>
            'required|in:schedule,reservation',

        // Schedule IDs
        'requester_schedule_id' =>
            'nullable|exists:schedules,id',

        'target_schedule_id' =>
            'nullable|exists:schedules,id',

        // Reservation IDs
        'requester_reservation_id' =>
            'nullable|exists:reservations,id',

        'target_reservation_id' =>
            'nullable|exists:reservations,id',

        // Rooms
        'requester_room_id' =>
            'required|exists:rooms,id',

        'target_room_id' =>
            'required|exists:rooms,id',

        // Swap date/time
        'swap_date' =>
            'required|date',

        'start_time' =>
            'required',

        'end_time' =>
            'required|after:start_time',

        // Reason
        'reason' =>
            'nullable|string|max:255',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Prevent Faculty A From Requesting Themselves
    |--------------------------------------------------------------------------
    */

    if ($request->target_user_id == $user->id) {

        return back()->with(
            'error',
            'You cannot request a room swap with yourself.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Prevent Swapping The Same Room
    |--------------------------------------------------------------------------
    */

    if ($request->requester_room_id == $request->target_room_id) {

        return back()->with(
            'error',
            'You cannot swap the same room.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FACULTY A'S BOOKING
    |--------------------------------------------------------------------------
    */

    if ($request->requester_type === 'schedule') {

        /*
        |--------------------------------------------------------------------------
        | Faculty A Selected A Schedule
        |--------------------------------------------------------------------------
        */

        if (!$request->requester_schedule_id) {

            return back()->with(
                'error',
                'Please select your scheduled class.'
            );
        }


        $requesterSchedule = Schedule::where(
            'id',
            $request->requester_schedule_id
        )
        ->where(
            'room_id',
            $request->requester_room_id
        )
        ->where(
            'instructor',
            $user->name
        )
        ->first();


        if (!$requesterSchedule) {

            return back()->with(
                'error',
                'The selected schedule does not belong to you.'
            );
        }

    } else {

        /*
        |--------------------------------------------------------------------------
        | Faculty A Selected A Reservation
        |--------------------------------------------------------------------------
        */

        if (!$request->requester_reservation_id) {

            return back()->with(
                'error',
                'Please select your reservation.'
            );
        }


        $requesterReservation = Reservation::where(
            'id',
            $request->requester_reservation_id
        )
        ->where(
            'user_id',
            $user->id
        )
        ->where(
            'room_id',
            $request->requester_room_id
        )
        ->where(
            'date',
            $request->swap_date
        )
        ->where(
            'status',
            'Approved'
        )
        ->first();


        if (!$requesterReservation) {

            return back()->with(
                'error',
                'The selected reservation is not a valid approved reservation belonging to you.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 5
    | Determine Faculty A's Booking Time
    |--------------------------------------------------------------------------
    */

    $requesterStart = null;
    $requesterEnd = null;


    if ($request->requester_type === 'schedule') {

        $parsed = $this->parseScheduleTime(
            $requesterSchedule->time
        );

    } else {

        $parsed = $this->parseReservationTime(
            $requesterReservation->time
        );
    }


    if (!$parsed) {

        return back()->with(
            'error',
            'The selected booking has an invalid time format.'
        );
    }


    $requesterStart = $parsed['start'];
    $requesterEnd = $parsed['end'];


    /*
    |--------------------------------------------------------------------------
    | FACULTY B'S BOOKING
    |--------------------------------------------------------------------------
    */

    if ($request->target_type === 'schedule') {

        /*
        |--------------------------------------------------------------------------
        | Faculty B Selected A Schedule
        |--------------------------------------------------------------------------
        */

        if (!$request->target_schedule_id) {

            return back()->with(
                'error',
                'Please select the target schedule.'
            );
        }


        $targetSchedule = Schedule::where(
            'id',
            $request->target_schedule_id
        )
        ->where(
            'room_id',
            $request->target_room_id
        )
        ->where(
            'instructor',
            '!=',
            $user->name
        )
        ->first();


        if (!$targetSchedule) {

            return back()->with(
                'error',
                'The selected target schedule is invalid.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Make Sure Target User Owns The Schedule
        |--------------------------------------------------------------------------
        */

        $targetFaculty = \App\Models\User::where(
            'name',
            $targetSchedule->instructor
        )->first();


        if (!$targetFaculty) {

            return back()->with(
                'error',
                'The owner of the selected schedule could not be found.'
            );
        }


        if ($targetFaculty->id != $request->target_user_id) {

            return back()->with(
                'error',
                'The selected faculty does not own this schedule.'
            );
        }

    } else {

        /*
        |--------------------------------------------------------------------------
        | Faculty B Selected A Reservation
        |--------------------------------------------------------------------------
        */

        if (!$request->target_reservation_id) {

            return back()->with(
                'error',
                'Please select the target reservation.'
            );
        }


        $targetReservation = Reservation::where(
            'id',
            $request->target_reservation_id
        )
        ->where(
            'user_id',
            $request->target_user_id
        )
        ->where(
            'room_id',
            $request->target_room_id
        )
        ->where(
            'date',
            $request->swap_date
        )
        ->where(
            'status',
            'Approved'
        )
        ->first();


        if (!$targetReservation) {

            return back()->with(
                'error',
                'The selected target reservation is not a valid approved reservation.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 6
    | Determine Faculty B's Booking Time
    |--------------------------------------------------------------------------
    */

    $targetStart = null;
    $targetEnd = null;


    if ($request->target_type === 'schedule') {

        $parsed = $this->parseScheduleTime(
            $targetSchedule->time
        );

    } else {

        $parsed = $this->parseReservationTime(
            $targetReservation->time
        );
    }


    if (!$parsed) {

        return back()->with(
            'error',
            'The target booking has an invalid time format.'
        );
    }


    $targetStart = $parsed['start'];
    $targetEnd = $parsed['end'];


    /*
    |--------------------------------------------------------------------------
    | STEP 7
    | Check If Faculty A and Faculty B Times Overlap
    |--------------------------------------------------------------------------
    */

    if (!$this->timesOverlap(
        $requesterStart,
        $requesterEnd,
        $targetStart,
        $targetEnd
    )) {

        return back()->with(
            'error',
            'The selected rooms are not occupied during the same time period.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Make Sure Target Faculty Exists
    |--------------------------------------------------------------------------
    */

    $targetUser = \App\Models\User::find(
        $request->target_user_id
    );


    if (!$targetUser) {

        return back()->with(
            'error',
            'Target faculty could not be found.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Room Swap Request
    |--------------------------------------------------------------------------
    */

    RoomSwapRequest::create([

        'requester_id' =>
            $user->id,

        'target_user_id' =>
            $request->target_user_id,

        'requester_schedule_id' =>
            $request->requester_type === 'schedule'
                ? $request->requester_schedule_id
                : null,

        'target_schedule_id' =>
            $request->target_type === 'schedule'
                ? $request->target_schedule_id
                : null,

        'requester_room_id' =>
            $request->requester_room_id,

        'target_room_id' =>
            $request->target_room_id,

        'swap_date' =>
            $request->swap_date,

        'start_time' =>
            $request->start_time,

        'end_time' =>
            $request->end_time,

        'reason' =>
            $request->reason,

        'status' =>
            'pending',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('faculty.room-swap')
        ->with(
            'success',
            'Room swap request sent successfully.'
        );
}












    /*
    |--------------------------------------------------------------------------
    | Requests Received by Faculty B
    |--------------------------------------------------------------------------
    */









    public function receivedRequests()
    {
        $user = Auth::user();

        $requests = RoomSwapRequest::where(
            'target_user_id',
            $user->id
        )
        ->with([
            'requester',
            'requesterRoom',
            'targetRoom',
            'requesterSchedule',
            'targetSchedule'
        ])
        ->where('status', 'pending')
        ->latest()
        ->get();


        return view(
            'faculty.room-swap-requests',
            compact('requests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Faculty B Approves Request
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $user = Auth::user();

        $swap = RoomSwapRequest::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Only Faculty B can approve
        |--------------------------------------------------------------------------
        */

        if ($swap->target_user_id != $user->id) {

            abort(403, 'Unauthorized.');
        }


        if ($swap->status !== 'pending') {

            return back()->with(
                'error',
                'This room swap request has already been processed.'
            );
        }


        $swap->update([
            'status' => 'approved'
        ]);


        return back()->with(
            'success',
            'Room swap request approved.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Faculty B Declines Request
    |--------------------------------------------------------------------------
    */

    public function decline($id)
    {
        $user = Auth::user();

        $swap = RoomSwapRequest::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Only Faculty B can decline
        |--------------------------------------------------------------------------
        */

        if ($swap->target_user_id != $user->id) {

            abort(403, 'Unauthorized.');
        }


        if ($swap->status !== 'pending') {

            return back()->with(
                'error',
                'This room swap request has already been processed.'
            );
        }


        $swap->update([
            'status' => 'declined'
        ]);


        return back()->with(
            'success',
            'Room swap request declined.'
        );
    }

    private function timesOverlap($startA, $endA, $startB, $endB)
{
    return $startA < $endB && $endA > $startB;
}


private function parseReservationTime($time)
{
  

    $parts = preg_split('/\s*-\s*/', trim($time));

    if (count($parts) !== 2) {
        return null;
    }

    try {

        $start = \Carbon\Carbon::parse(trim($parts[0]));
        $end   = \Carbon\Carbon::parse(trim($parts[1]));

        return [
            'start' => $start,
            'end' => $end,
        ];

    } catch (\Exception $e) {

        return null;
    }
}

private function parseScheduleTime($time)
{
    $parts = preg_split('/\s*-\s*/', trim($time));

    if (count($parts) !== 2) {
        return null;
    }

    try {

        $start = \Carbon\Carbon::parse(trim($parts[0]));
        $end   = \Carbon\Carbon::parse(trim($parts[1]));

        return [
            'start' => $start,
            'end' => $end,
        ];

    } catch (\Exception $e) {

        return null;
    }
}
}