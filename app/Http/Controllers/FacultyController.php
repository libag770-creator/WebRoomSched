<?php

namespace App\Http\Controllers;


use App\Models\Reservation;
use App\Models\Room;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\ScheduleMerge;
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

public function schedule()
{
    $rooms = Room::with([
        'department',
        'schedules' => function ($query) {
            $query
                ->orderBy('row_position')
                ->orderBy('id');
        }
    ])
    ->orderBy('department_id')
    ->orderBy('building_id')
    ->orderBy('room_name')
    ->get();


    /*
    |--------------------------------------------------------------------------
    | Group Rooms by Department
    |--------------------------------------------------------------------------
    */

    $departments = $rooms
        ->groupBy('department_id')
        ->map(function ($departmentRooms, $departmentId) {

            $department =
                $departmentRooms->first()->department;


            /*
            |--------------------------------------------------------------------------
            | Group Rooms by Building
            |--------------------------------------------------------------------------
            */

            $buildings = $departmentRooms
                ->groupBy('building_id')
                ->map(function ($buildingRooms, $buildingId) {

                    $firstRoom =
                        $buildingRooms->first();


                    /*
                    |--------------------------------------------------------------------------
                    | Building Name
                    |--------------------------------------------------------------------------
                    */

                    $buildingName =
                        is_string($firstRoom->building)
                            ? $firstRoom->building
                            : (
                                $firstRoom->building?->building_name
                                ?? $firstRoom->building?->name
                                ?? 'Building'
                            );


                    return [
                        'id' => $buildingId,
                        'name' => $buildingName,
                        'rooms' => $buildingRooms,
                    ];

                })
                ->values();


            return [
                'id' => $departmentId,

                'name' =>
                    $department?->name
                    ?? 'Department',

                'rooms' => $departmentRooms,

                'buildings' => $buildings,
            ];

        })
        ->values();


    return view(
        'faculty.schedules',
        compact(
            'rooms',
            'departments'
        )
    );
}
public function viewSchedule(Room $room)
{
    /*
    |--------------------------------------------------------------------------
    | LOAD ROOM
    |--------------------------------------------------------------------------
    */

    $room->load([
        'department',
        'building',
    ]);


    /*
    |--------------------------------------------------------------------------
    | LOAD SCHEDULES
    |--------------------------------------------------------------------------
    */

    $schedules = Schedule::where(
        'room_id',
        $room->id
    )
    ->orderByRaw(
        'CASE WHEN row_position IS NULL THEN 999 ELSE row_position END'
    )
    ->orderBy('id')
    ->get();


    /*
    |--------------------------------------------------------------------------
    | LOAD APPROVED MERGES
    |--------------------------------------------------------------------------
    */

    $merges = ScheduleMerge::with([
        'department',
        'facultySubject',
    ])
    ->whereIn(
        'schedule_id',
        $schedules->pluck('id')
    )
    ->where(
        'status',
        'approved'
    )
    ->get();


    /*
    |--------------------------------------------------------------------------
    | GROUP MERGED PROGRAMS BY SCHEDULE
    |--------------------------------------------------------------------------
    */

    $mergedPrograms = [];


    foreach ($merges as $merge) {

        if (!$merge->facultySubject) {
            continue;
        }


        $scheduleId =
            $merge->schedule_id;


        $program =
            trim(
                (string)
                $merge
                    ->facultySubject
                    ->program
            );


        if ($program === '') {
            continue;
        }


        if (
            !isset(
                $mergedPrograms[
                    $scheduleId
                ]
            )
        ) {

            $mergedPrograms[
                $scheduleId
            ] = [];

        }


        if (
            !in_array(
                $program,
                $mergedPrograms[
                    $scheduleId
                ]
            )
        ) {

            $mergedPrograms[
                $scheduleId
            ][] =
                $program;

        }

    }


  /*
|--------------------------------------------------------------------------
| FORMAT SCHEDULE DATA
|--------------------------------------------------------------------------
*/

$scheduleData =
    $schedules->map(
        function ($schedule)
        use ($mergedPrograms) {

            return [

               
                'id' =>
                    $schedule->id,

                'course_code' =>
                    $schedule->course_code,

                'subject' =>
                    $schedule->subject,

              
                'subject_type' =>
                    $schedule->subject_type,

               
                'program' =>
                    $schedule->program,

                
                'major' =>
                    $schedule->major,

                
                'day' =>
                    strtoupper(
                        trim(
                            (string)
                            $schedule->day
                        )
                    ),

                'time' =>
                    trim(
                        (string)
                        $schedule->time
                    ),

               
                'row_position' =>
                    $schedule->row_position !== null
                        ? (int)
                            $schedule->row_position
                        : null,

                
                'instructor' =>
                    $schedule->instructor,

              
                'color' =>
                    $schedule->color
                    ?? '#ffffff',

             
                'merged_programs' =>
                    $mergedPrograms[
                        $schedule->id
                    ] ?? [],

            ];

        }
    )
    ->values();


    /*
    |--------------------------------------------------------------------------
    | BUILDING NAME
    |--------------------------------------------------------------------------
    */

    $buildingName =
        'Building';


    if ($room->building) {

        if (
            is_object(
                $room->building
            )
        ) {

            $buildingName =
                $room
                    ->building
                    ->name
                ??
                $room
                    ->building
                    ->building_name
                ??
                'Building';

        }
        else {

            $buildingName =
                $room->building;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | RETURN JSON
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => true,

        'room' => [

            'id' =>
                $room->id,

            'room_name' =>
                $room->room_name,

            'building' =>
                $buildingName,

            'department' =>
                $room->department?->name
                ?? 'Department',

        ],

        'schedules' =>
            $scheduleData,

    ]);
}
}
