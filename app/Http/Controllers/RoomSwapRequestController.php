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
use Illuminate\Support\Facades\DB;

class RoomSwapRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW ROOM SWAP PAGE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized.');
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | STEP 1 — MY SCHEDULES
        |--------------------------------------------------------------------------
        */

        $mySchedules = Schedule::with([
            'room.department',
            'room.building',
        ])
            ->where('instructor', $user->name)
            ->orderBy('day')
            ->orderBy('time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STEP 2 — ALL DEPARTMENTS
        |--------------------------------------------------------------------------
        |
        | Loads departments, buildings, rooms, schedules,
        | and approved merge information.
        |
        */

        $departments = Department::with([
            'buildings.rooms.schedules.merges.facultySubject',
        ])
            ->orderBy('name')
            ->get();

        return view(
            'faculty.room-swap',
            compact(
                'mySchedules',
                'departments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEND ROOM SWAP REQUEST
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | VALIDATE REQUEST
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'requester_schedule_id' => [
                'required',
                'exists:schedules,id',
            ],

            'target_schedule_id' => [
                'required',
                'exists:schedules,id',
            ],

            'requester_room_id' => [
                'required',
                'exists:rooms,id',
            ],

            'target_room_id' => [
                'required',
                'exists:rooms,id',
            ],

            'target_user_id' => [
                'required',
                'exists:users,id',
            ],

            'swap_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'required',
            ],

            'end_time' => [
                'required',
                'after:start_time',
            ],

            'reason' => [
                'required',
                'string',
                'max:255',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | GET REQUESTER SCHEDULE
        |--------------------------------------------------------------------------
        */

        $requesterSchedule =
            Schedule::where(
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

            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected schedule does not belong to you.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | GET TARGET SCHEDULE
        |--------------------------------------------------------------------------
        */

        $targetSchedule =
            Schedule::where(
                'id',
                $request->target_schedule_id
            )
            ->where(
                'room_id',
                $request->target_room_id
            )
            ->first();


        if (!$targetSchedule) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected target schedule is invalid.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | GET TARGET FACULTY
        |--------------------------------------------------------------------------
        */

        $targetFaculty =
            User::find(
                $request->target_user_id
            );


        if (!$targetFaculty) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'The target faculty could not be found.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TARGET FACULTY MUST OWN TARGET SCHEDULE
        |--------------------------------------------------------------------------
        */

        if (
            trim(
                (string) $targetSchedule->instructor
            )
            !==
            trim(
                (string) $targetFaculty->name
            )
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected schedule does not belong to the selected faculty.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SAME SCHEDULE
        |--------------------------------------------------------------------------
        */

        if (
            (int) $requesterSchedule->id ===
            (int) $targetSchedule->id
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'You cannot swap a schedule with itself.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SAME DAY
        |--------------------------------------------------------------------------
        */

        $requesterDay =
            strtoupper(
                trim(
                    (string) $requesterSchedule->day
                )
            );

        $targetDay =
            strtoupper(
                trim(
                    (string) $targetSchedule->day
                )
            );


        if (
            $requesterDay !==
            $targetDay
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Room swap is only allowed when both schedules are on the same day.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SAME EXACT TIME
        |--------------------------------------------------------------------------
        */

        $requesterTime =
            preg_replace(
                '/\s+/',
                '',
                strtolower(
                    trim(
                        (string) $requesterSchedule->time
                    )
                )
            );

        $targetTime =
            preg_replace(
                '/\s+/',
                '',
                strtolower(
                    trim(
                        (string) $targetSchedule->time
                    )
                )
            );


        if (
            $requesterTime !==
            $targetTime
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Room swap is only allowed when both schedules have the same time.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DIFFERENT ROOM
        |--------------------------------------------------------------------------
        */

        if (
            (int) $requesterSchedule->room_id ===
            (int) $targetSchedule->room_id
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Both schedules already use the same room. There is no room to exchange.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK REQUESTER'S NEW ROOM
        |--------------------------------------------------------------------------
        |
        | Faculty A will receive Faculty B's room.
        |
        | IMPORTANT:
        | Exclude BOTH schedules because they are the two schedules
        | being exchanged.
        |
        */

        $requesterConflict =
            Schedule::where(
                'room_id',
                $targetSchedule->room_id
            )
            ->where(
                'day',
                $requesterSchedule->day
            )
            ->where(
                'time',
                $requesterSchedule->time
            )
            ->whereNotIn(
                'id',
                [
                    $requesterSchedule->id,
                    $targetSchedule->id,
                ]
            )
            ->exists();


        if ($requesterConflict) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'The target room is already occupied by another schedule at this day and time.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK TARGET FACULTY'S NEW ROOM
        |--------------------------------------------------------------------------
        |
        | Faculty B will receive Faculty A's room.
        |
        | IMPORTANT:
        | Exclude BOTH schedules because they are the two schedules
        | being exchanged.
        |
        */

        $targetConflict =
            Schedule::where(
                'room_id',
                $requesterSchedule->room_id
            )
            ->where(
                'day',
                $targetSchedule->day
            )
            ->where(
                'time',
                $targetSchedule->time
            )
            ->whereNotIn(
                'id',
                [
                    $requesterSchedule->id,
                    $targetSchedule->id,
                ]
            )
            ->exists();


        if ($targetConflict) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Your room is already occupied by another schedule at this day and time.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE PENDING REQUEST
        |--------------------------------------------------------------------------
        */

        $duplicate =
            RoomSwapRequest::where(
                'requester_id',
                $user->id
            )
            ->where(
                'target_user_id',
                $targetFaculty->id
            )
            ->where(
                'requester_schedule_id',
                $requesterSchedule->id
            )
            ->where(
                'target_schedule_id',
                $targetSchedule->id
            )
            ->where(
                'status',
                'pending'
            )
            ->exists();


        if ($duplicate) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'You already have a pending room swap request for these schedules.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE ROOM SWAP REQUEST
        |--------------------------------------------------------------------------
        */

        RoomSwapRequest::create([

            'requester_id' =>
                $user->id,

            'target_user_id' =>
                $targetFaculty->id,

            'requester_schedule_id' =>
                $requesterSchedule->id,

            'target_schedule_id' =>
                $targetSchedule->id,

            'requester_room_id' =>
                $requesterSchedule->room_id,

            'target_room_id' =>
                $targetSchedule->room_id,

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
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'faculty.room-swap'
            )
            ->with(
                'success',
                'Room swap request sent successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RECEIVED ROOM SWAP REQUESTS
    |--------------------------------------------------------------------------
    */

    public function receivedRequests()
    {
        $user = Auth::user();

        $requests =
            RoomSwapRequest::where(
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
            ->where(
                'status',
                'pending'
            )
            ->latest()
            ->get();


        return view(
            'faculty.room-swap-requests',
            compact('requests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE ROOM SWAP
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | GET REQUEST
        |--------------------------------------------------------------------------
        */

        $swap =
            RoomSwapRequest::with([
                'requesterSchedule',
                'targetSchedule',
            ])
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | ONLY TARGET FACULTY CAN APPROVE
        |--------------------------------------------------------------------------
        */

        if (
            (int) $swap->target_user_id !==
            (int) $user->id
        ) {

            abort(
                403,
                'Unauthorized.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REQUEST MUST STILL BE PENDING
        |--------------------------------------------------------------------------
        */

        if (
            $swap->status !==
            'pending'
        ) {

            return back()
                ->with(
                    'error',
                    'This room swap request has already been processed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | GET BOTH SCHEDULES
        |--------------------------------------------------------------------------
        */

        $requesterSchedule =
            $swap->requesterSchedule;

        $targetSchedule =
            $swap->targetSchedule;


        if (
            !$requesterSchedule ||
            !$targetSchedule
        ) {

            return back()
                ->with(
                    'error',
                    'One of the selected schedules no longer exists.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DO NOT SWAP A SCHEDULE WITH ITSELF
        |--------------------------------------------------------------------------
        */

        if (
            (int) $requesterSchedule->id ===
            (int) $targetSchedule->id
        ) {

            return back()
                ->with(
                    'error',
                    'You cannot swap a schedule with itself.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | RECHECK SAME DAY
        |--------------------------------------------------------------------------
        */

        $requesterDay =
            strtoupper(
                trim(
                    (string) $requesterSchedule->day
                )
            );

        $targetDay =
            strtoupper(
                trim(
                    (string) $targetSchedule->day
                )
            );


        if (
            $requesterDay !==
            $targetDay
        ) {

            return back()
                ->with(
                    'error',
                    'This room swap cannot be approved because the schedules are not on the same day.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | RECHECK SAME TIME
        |--------------------------------------------------------------------------
        */

        $requesterTime =
            preg_replace(
                '/\s+/',
                '',
                strtolower(
                    trim(
                        (string) $requesterSchedule->time
                    )
                )
            );

        $targetTime =
            preg_replace(
                '/\s+/',
                '',
                strtolower(
                    trim(
                        (string) $targetSchedule->time
                    )
                )
            );


        if (
            $requesterTime !==
            $targetTime
        ) {

            return back()
                ->with(
                    'error',
                    'This room swap cannot be approved because the schedules do not have the same time.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | GET CURRENT ROOMS
        |--------------------------------------------------------------------------
        */

        $requesterRoomId =
            $requesterSchedule->room_id;

        $targetRoomId =
            $targetSchedule->room_id;


        /*
        |--------------------------------------------------------------------------
        | VERIFY ORIGINAL ROOM VALUES
        |--------------------------------------------------------------------------
        |
        | These values are stored in the request when the request
        | was created. They are needed to restore the rooms later.
        |
        */

        if (
            !$swap->requester_room_id ||
            !$swap->target_room_id
        ) {

            return back()
                ->with(
                    'error',
                    'The original room information for this swap is missing.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SAME ROOM
        |--------------------------------------------------------------------------
        */

        if (
            (int) $requesterRoomId ===
            (int) $targetRoomId
        ) {

            return back()
                ->with(
                    'error',
                    'Both selected schedules are already assigned to the same room. There is no different room to exchange.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK REQUESTER'S NEW ROOM
        |--------------------------------------------------------------------------
        |
        | Faculty A receives Faculty B's room.
        |
        | IMPORTANT:
        | Exclude BOTH schedules.
        |
        */

        $requesterConflict =
            Schedule::where(
                'room_id',
                $targetRoomId
            )
            ->where(
                'day',
                $requesterSchedule->day
            )
            ->where(
                'time',
                $requesterSchedule->time
            )
            ->whereNotIn(
                'id',
                [
                    $requesterSchedule->id,
                    $targetSchedule->id,
                ]
            )
            ->exists();


        /*
        |--------------------------------------------------------------------------
        | CHECK TARGET FACULTY'S NEW ROOM
        |--------------------------------------------------------------------------
        |
        | Faculty B receives Faculty A's room.
        |
        | IMPORTANT:
        | Exclude BOTH schedules.
        |
        */

        $targetConflict =
            Schedule::where(
                'room_id',
                $requesterRoomId
            )
            ->where(
                'day',
                $targetSchedule->day
            )
            ->where(
                'time',
                $targetSchedule->time
            )
            ->whereNotIn(
                'id',
                [
                    $requesterSchedule->id,
                    $targetSchedule->id,
                ]
            )
            ->exists();


        /*
        |--------------------------------------------------------------------------
        | STOP IF A THIRD SCHEDULE CREATES A CONFLICT
        |--------------------------------------------------------------------------
        */

        if (
            $requesterConflict ||
            $targetConflict
        ) {

            return back()
                ->with(
                    'error',
                    'The room swap cannot be completed because another schedule is already using one of the rooms at this day and time.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | APPROVE AND EXCHANGE ROOMS
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $requesterSchedule,
                $targetSchedule,
                $targetRoomId,
                $requesterRoomId,
                $swap
            ) {

                /*
                |--------------------------------------------------------------------------
                | LOCK BOTH SCHEDULES
                |--------------------------------------------------------------------------
                */

                $lockedRequesterSchedule =
                    Schedule::where(
                        'id',
                        $requesterSchedule->id
                    )
                    ->lockForUpdate()
                    ->first();

                $lockedTargetSchedule =
                    Schedule::where(
                        'id',
                        $targetSchedule->id
                    )
                    ->lockForUpdate()
                    ->first();


                if (
                    !$lockedRequesterSchedule ||
                    !$lockedTargetSchedule
                ) {

                    throw new \Exception(
                        'One of the schedules no longer exists.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | EXCHANGE ROOMS
                |--------------------------------------------------------------------------
                */

                $lockedRequesterSchedule->update([
                    'room_id' =>
                        $targetRoomId,
                ]);

                $lockedTargetSchedule->update([
                    'room_id' =>
                        $requesterRoomId,
                ]);


                /*
                |--------------------------------------------------------------------------
                | MARK AS APPROVED
                |--------------------------------------------------------------------------
                */

                $swap->update([
                    'status' =>
                        'approved',
                ]);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return back()
            ->with(
                'success',
                'Room swap approved. The two room assignments have been exchanged temporarily until the scheduled end time.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DECLINE ROOM SWAP
    |--------------------------------------------------------------------------
    */

    public function decline($id)
    {
        $user = Auth::user();

        $swap =
            RoomSwapRequest::findOrFail(
                $id
            );


        /*
        |--------------------------------------------------------------------------
        | ONLY TARGET FACULTY CAN DECLINE
        |--------------------------------------------------------------------------
        */

        if (
            (int) $swap->target_user_id !==
            (int) $user->id
        ) {

            abort(
                403,
                'Unauthorized.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REQUEST MUST STILL BE PENDING
        |--------------------------------------------------------------------------
        */

        if (
            $swap->status !==
            'pending'
        ) {

            return back()
                ->with(
                    'error',
                    'This room swap request has already been processed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DECLINE
        |--------------------------------------------------------------------------
        */

        $swap->update([
            'status' =>
                'declined'
        ]);


        return back()
            ->with(
                'success',
                'Room swap request declined.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PARSE RESERVATION TIME
    |--------------------------------------------------------------------------
    */

    private function parseReservationTime($time)
    {
        $parts =
            preg_split(
                '/\s*-\s*/',
                trim($time)
            );

        if (
            count($parts) !== 2
        ) {
            return null;
        }

        try {

            $start =
                \Carbon\Carbon::parse(
                    trim($parts[0])
                );

            $end =
                \Carbon\Carbon::parse(
                    trim($parts[1])
                );

            return [
                'start' => $start,
                'end' => $end,
            ];

        } catch (\Exception $e) {

            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PARSE SCHEDULE TIME
    |--------------------------------------------------------------------------
    */

    private function parseScheduleTime($time)
    {
        $parts =
            preg_split(
                '/\s*-\s*/',
                trim($time)
            );

        if (
            count($parts) !== 2
        ) {
            return null;
        }

        try {

            $start =
                \Carbon\Carbon::parse(
                    trim($parts[0])
                );

            $end =
                \Carbon\Carbon::parse(
                    trim($parts[1])
                );

            return [
                'start' => $start,
                'end' => $end,
            ];

        } catch (\Exception $e) {

            return null;
        }
    }
}