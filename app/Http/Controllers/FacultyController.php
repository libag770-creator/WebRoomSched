<?php

namespace App\Http\Controllers;


use App\Models\Reservation;
use App\Models\Room;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\ScheduleMerge;
use App\Models\RoomSwapRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FacultyController extends Controller
{
   public function dashboard()
{
    $now = Carbon::now();

    /*
    |--------------------------------------------------------------------------
    | TOTAL ROOMS
    |--------------------------------------------------------------------------
    */
    $totalRooms = Room::count();


    $allReservations = Reservation::with([
        'room',
    ])
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | RESERVATION COUNTS
    |--------------------------------------------------------------------------
    */
    $pendingReservationsCount = $allReservations
        ->where('status', 'Pending')
        ->count();

    $approvedReservationsCount = $allReservations
        ->where('status', 'Approved')
        ->count();

    /*
    | The database keeps the original status "Declined".
    | The dashboard displays it as "Rejected" for clearer wording.
    */
    $rejectedReservationsCount = $allReservations
        ->where('status', 'Declined')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | BACKWARD COMPATIBILITY
    |--------------------------------------------------------------------------
    |
    | Keep $reservations available because other dashboard code may still
    | reference it.
    |
    */
    $reservations = $allReservations
        ->where('status', 'Approved')
        ->filter(function ($reservation) use ($now) {

            if (
                !$reservation->start_time ||
                !$reservation->end_time
            ) {
                return true;
            }

            try {
                $endDateTime = Carbon::parse(
                    $reservation->date . ' ' . $reservation->end_time
                );

                return $endDateTime->greaterThan($now);

            } catch (\Throwable $e) {
                return true;
            }
        })
        ->sortBy(function ($reservation) {

            return $reservation->date .
                ' ' .
                $reservation->start_time;

        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | PENDING ROOM SWAP REQUESTS
    |--------------------------------------------------------------------------
    */
    $roomSwapRequests = RoomSwapRequest::with([
        'requester',
        'requesterSchedule',
        'targetSchedule',
        'requesterRoom',
        'targetRoom',
    ])
        ->where(
            'target_user_id',
            Auth::id()
        )
        ->where(
            'status',
            'pending'
        )
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | ROOM SWAP HISTORY
    |--------------------------------------------------------------------------
    */
    $roomSwapHistory = RoomSwapRequest::with([
        'requester',
        'targetUser',
        'requesterSchedule',
        'targetSchedule',
        'requesterRoom',
        'targetRoom',
    ])
        ->where(function ($query) {

            $query
                ->where(
                    'requester_id',
                    Auth::id()
                )
                ->orWhere(
                    'target_user_id',
                    Auth::id()
                );

        })
        ->whereIn(
            'status',
            [
                'approved',
                'declined',
                'expired',
            ]
        )
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | RETURN DASHBOARD
    |--------------------------------------------------------------------------
    */
    return view(
        'faculty.dashboard',
        compact(
            'allReservations',
            'reservations',
            'pendingReservationsCount',
            'approvedReservationsCount',
            'rejectedReservationsCount',
            'roomSwapRequests',
            'roomSwapHistory',
            'totalRooms'
        )
    );
}

 public function reserveRoom(Request $request, Room $room)
{
    $request->validate([
        'date' => 'required|date',
        'time' => 'nullable|string',
        'schedule_id' => 'required|integer|exists:schedules,id',
    ]);

    $faculty = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | SELECT ONLY A SCHEDULE SET FOR THIS FACULTY
    |--------------------------------------------------------------------------
    | The faculty cannot type or invent a class. The selected schedule must
    | already exist in Set Faculty and must belong to the logged-in faculty.
    |--------------------------------------------------------------------------
    */

    $selectedSchedule = Schedule::where('id', $request->schedule_id)
        ->where(function ($query) use ($faculty) {
            $query->where('faculty_id', $faculty->id)
                ->orWhere(function ($legacyQuery) use ($faculty) {
                    $legacyQuery->whereNull('faculty_id')
                        ->where('instructor', $faculty->name);
                });
        })
        ->first();

    if (!$selectedSchedule) {
        return back()->with(
            'error',
            'The selected schedule is not assigned to your faculty account.'
        );
    }

    $day = strtoupper(
        Carbon::parse($request->date)->format('D')
    );

    $scheduleDay = strtoupper(
        trim((string) $selectedSchedule->day)
    );

    if ($scheduleDay !== $day) {
        return back()->with(
            'error',
            'The selected schedule is not scheduled on the selected date.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GET THE REAL CLASS TIME FROM SET FACULTY
    |--------------------------------------------------------------------------
    | We do NOT trust the browser's typed time for the class duration.
    | The reservation uses the time saved by the Chair in the schedule.
    |--------------------------------------------------------------------------
    */

    $scheduleTime = trim((string) $selectedSchedule->time);
    $timeParts = preg_split('/\s*-\s*/', $scheduleTime, 2);

    $startTime = trim($timeParts[0] ?? '');
    $endTime = trim($timeParts[1] ?? '');

    if ($startTime === '' || $endTime === '') {
        return back()->with(
            'error',
            'The selected faculty schedule has an invalid time range.'
        );
    }

    try {
        $startTime = Carbon::createFromFormat('H:i', $startTime)
            ->format('H:i');

        $endTime = Carbon::createFromFormat('H:i', $endTime)
            ->format('H:i');
    } catch (\Throwable $e) {
        return back()->with(
            'error',
            'The selected faculty schedule has an invalid time format.'
        );
    }

    $request->merge([
        'time' => $startTime . '-' . $endTime,
        'start_time' => $startTime,
        'end_time' => $endTime,
    ]);

    $requestedStart = Carbon::parse(
        $request->date . ' ' . $startTime
    );

    $requestedEnd = Carbon::parse(
        $request->date . ' ' . $endTime
    );

    if (!$requestedEnd->greaterThan($requestedStart)) {
        return back()->with(
            'error',
            'The selected faculty schedule has an invalid time range.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PREVENT A REGULAR CLASS FROM BEING RESERVED OVER
    |--------------------------------------------------------------------------
    */

    $roomSchedules = Schedule::where('room_id', $room->id)
        ->where('day', $day)
        ->get();

    foreach ($roomSchedules as $roomSchedule) {
        $roomTime = preg_split(
            '/\s*-\s*/',
            trim((string) $roomSchedule->time),
            2
        );

        $roomStart = trim($roomTime[0] ?? '');
        $roomEnd = trim($roomTime[1] ?? '');

        if ($roomStart === '' || $roomEnd === '') {
            continue;
        }

        $hasClass =
            !empty($roomSchedule->course_code) ||
            !empty($roomSchedule->subject) ||
            !empty($roomSchedule->instructor);

        if (!$hasClass) {
            continue;
        }

        try {
            $existingStart = Carbon::parse(
                $request->date . ' ' . $roomStart
            );

            $existingEnd = Carbon::parse(
                $request->date . ' ' . $roomEnd
            );
        } catch (\Throwable $e) {
            continue;
        }

        if (
            $requestedStart < $existingEnd &&
            $requestedEnd > $existingStart
        ) {
            return back()->with(
                'error',
                'This room already has a class schedule during the selected faculty schedule time.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PREVENT DUPLICATE RESERVATIONS
    |--------------------------------------------------------------------------
    */

    $existingReservations = Reservation::where('room_id', $room->id)
        ->whereDate('date', $request->date)
        ->whereIn('status', ['Pending', 'Approved'])
        ->get();

    foreach ($existingReservations as $reservation) {
        if (!$reservation->start_time || !$reservation->end_time) {
            continue;
        }

        try {
            $reservationStart = Carbon::parse(
                $reservation->date . ' ' . $reservation->start_time
            );

            $reservationEnd = Carbon::parse(
                $reservation->date . ' ' . $reservation->end_time
            );
        } catch (\Throwable $e) {
            continue;
        }

        if (
            $requestedStart < $reservationEnd &&
            $requestedEnd > $reservationStart
        ) {
            return back()->with(
                'error',
                'This room already has a reservation request during the selected faculty schedule time.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE TEMPORARY ROOM RESERVATION
    |--------------------------------------------------------------------------
    */

    Reservation::create([
        'room_id' => $room->id,
        'user_id' => $faculty->id,
        'schedule_id' => $selectedSchedule->id,
        'date' => $request->date,
        'day' => $day,
        'time' => $request->time,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'purpose' => $room->purpose,
        'status' => 'Pending',
    ]);

    return redirect() 
        ->route('faculty.vacant', [
            'date' => $request->date,
            'time' => $request->time,
        ])
        ->with(
            'success',
            'Room reservation submitted for the selected faculty schedule. Waiting for Chair approval.'
        );
}

public function vacantRooms(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | LOAD ALL ROOMS
    |--------------------------------------------------------------------------
    |
    | Every room keeps its own schedules.
    | Rooms are still loaded even when they have no schedule for the
    | searched time, because a room with no class is AVAILABLE.
    |
    */

    $rooms = Room::with([
        'department',
        'building',
        'schedules' => function ($query) {
            $query
                ->orderByRaw(
                    'CASE WHEN row_position IS NULL THEN 999 ELSE row_position END'
                )
                ->orderBy('id');
        }
    ])
        ->orderBy('department_id')
        ->orderBy('building_id')
        ->orderBy('room_name')
        ->get();

    $results = [];

    /*
    |--------------------------------------------------------------------------
    | LOAD THE LOGGED-IN FACULTY'S SET FACULTY SCHEDULES
    |--------------------------------------------------------------------------
    | These are the schedules created by the Chair under Set Faculty.
    | They are the only schedules that can be selected for a reservation.
    |--------------------------------------------------------------------------
    */

    $loggedInFaculty = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | ONLY LOAD THE LOGGED-IN FACULTY'S SET-FACULTY SCHEDULES
    |--------------------------------------------------------------------------
    |
    | Normal records are matched by faculty_id.
    | Older schedule records that have no faculty_id are allowed only when
    | their instructor name exactly matches the logged-in faculty name.
    | This prevents schedules belonging to other faculty members from
    | appearing in the reservation popup.
    |--------------------------------------------------------------------------
    */
    $facultySchedules = Schedule::with(['room', 'faculty'])
        ->where(function ($query) use ($loggedInFaculty) {
            $query->where('faculty_id', $loggedInFaculty->id)
                ->orWhere(function ($legacyQuery) use ($loggedInFaculty) {
                    $legacyQuery->whereNull('faculty_id')
                        ->where('instructor', $loggedInFaculty->name);
                });
        })
        ->orderBy('day')
        ->orderByRaw(
            'CASE WHEN row_position IS NULL THEN 999 ELSE row_position END'
        )
        ->orderBy('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | PLAIN DATA FOR THE RESERVATION POPUP
    |--------------------------------------------------------------------------
    |
    | Prepare this in the controller instead of using a PHP closure inside
    | @json() in Blade. This also prevents the previous ParseError.
    |--------------------------------------------------------------------------
    */
    $facultyScheduleOptions = $facultySchedules->map(function ($schedule) {
        return [
            'id' => $schedule->id,
            'course_code' => $schedule->course_code,
            'subject' => $schedule->subject,
            'program' => $schedule->program,
            'year_level' => $schedule->year_level,
            'major' => $schedule->major,
            'subject_type' => $schedule->subject_type,
            'day' => strtoupper(trim((string) $schedule->day)),
            'time' => trim((string) $schedule->time),
            'room_id' => $schedule->room_id,
            'room_name' => $schedule->room?->room_name,
            'instructor' => $schedule->instructor,
        ];
    })->values();

    /*
    |--------------------------------------------------------------------------
    | SEARCH DATE + TIME
    |--------------------------------------------------------------------------
    */

    if (
        $request->filled('date') &&
        $request->filled('time')
    ) {

        $date = Carbon::parse(
            $request->date
        );

        $day = strtoupper(
            $date->format('D')
        );

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE USER TIME SEARCH
        |--------------------------------------------------------------------------
        |
        | Examples:
        |
        | 8       -> 08
        | 8:      -> 08:
        | 8:0     -> 08:0
        | 8:30    -> 08:30
        |
        */

        $timeSearch = trim(
            (string) $request->time
        );

        $timeSearch = strtoupper(
            $timeSearch
        );

        $timeSearch = preg_replace(
            '/\s+/',
            '',
            $timeSearch
        );

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE HOUR
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/^(\d{1,2})(:?.*)$/',
                $timeSearch,
                $matches
            )
        ) {

            $hour = (int) $matches[1];

            $rest = $matches[2] ?? '';

            if (
                $hour >= 0 &&
                $hour <= 23
            ) {

                $timeSearch =
                    str_pad(
                        (string) $hour,
                        2,
                        '0',
                        STR_PAD_LEFT
                    ) . $rest;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK IF A SAVED START TIME MATCHES THE SEARCH
        |--------------------------------------------------------------------------
        |
        | 8     -> 08:00, 08:30, 08:45, etc.
        | 8:    -> 08:00, 08:30, 08:45, etc.
        | 8:0   -> 08:00, 08:05, 08:09, etc.
        | 8:30  -> 08:30 only.
        |
        */

        $matchesSearch = function ($savedStart) use ($timeSearch) {

            if (!$savedStart) {
                return false;
            }

            return str_starts_with(
                $savedStart,
                $timeSearch
            );
        };

        /*
        |--------------------------------------------------------------------------
        | CREATE FALLBACK TIME
        |--------------------------------------------------------------------------
        |
        | If a room has no schedule row for the searched time, the room is
        | still shown as AVAILABLE. We use the searched time as the
        | reservation slot.
        |
        */

        $fallbackTime = function () use ($timeSearch) {

            $cleanSearch = trim(
                (string) $timeSearch
            );

            /*
            | Search such as 08:
            */
            if (
                str_ends_with(
                    $cleanSearch,
                    ':'
                )
            ) {

                $cleanSearch = rtrim(
                    $cleanSearch,
                    ':'
                );
            }

            /*
            | Search such as 08:0
            |
            | Treat the typed minute as the beginning of the minute
            | range. For reservation purposes, use 08:00.
            */
            if (
                preg_match(
                    '/^(\d{2}):(\d)$/',
                    $cleanSearch,
                    $parts
                )
            ) {

                $hour = (int) $parts[1];

                $minute = ((int) $parts[2]) * 10;

                $start = Carbon::createFromTime(
                    $hour,
                    $minute,
                    0
                );

                $end = $start->copy()->addHour();

                return $start->format('H:i') .
                    '-' .
                    $end->format('H:i');
            }

            /*
            | Search such as 08
            */
            if (
                preg_match(
                    '/^\d{2}$/',
                    $cleanSearch
                )
            ) {

                $start = Carbon::createFromTime(
                    (int) $cleanSearch,
                    0,
                    0
                );

                $end = $start->copy()->addHour();

                return $start->format('H:i') .
                    '-' .
                    $end->format('H:i');
            }

            /*
            | Search such as 08:30
            */
            if (
                preg_match(
                    '/^\d{2}:\d{2}$/',
                    $cleanSearch
                )
            ) {

                $start = Carbon::createFromFormat(
                    'H:i',
                    $cleanSearch
                );

                $end = $start->copy()->addHour();

                return $start->format('H:i') .
                    '-' .
                    $end->format('H:i');
            }

            /*
            | Safe fallback.
            */
            $start = Carbon::createFromTime(
                8,
                0,
                0
            );

            $end = $start->copy()->addHour();

            return $start->format('H:i') .
                '-' .
                $end->format('H:i');
        };

        /*
        |--------------------------------------------------------------------------
        | CHECK EVERY ROOM
        |--------------------------------------------------------------------------
        */

        foreach ($rooms as $room) {

            /*
            |--------------------------------------------------------------------------
            | FIND ALL SCHEDULES MATCHING THE SEARCH
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | We do NOT skip the room when there is no schedule.
            |
            | No saved class at the searched time = AVAILABLE.
            |
            */

            $matchingSchedules = [];

            foreach (
                $room->schedules
                    ->where('day', $day)
                    as $schedule
            ) {

                $savedTime = trim(
                    (string) $schedule->time
                );

                if ($savedTime === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | GET START TIME
                |--------------------------------------------------------------------------
                */

                $savedTimeParts = explode(
                    '-',
                    $savedTime,
                    2
                );

                $savedStart = trim(
                    $savedTimeParts[0] ?? ''
                );

                if ($savedStart === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | NORMALIZE SAVED START TIME
                |--------------------------------------------------------------------------
                |
                | 8:00 -> 08:00
                | 8:30 -> 08:30
                |
                */

                $savedStart = strtoupper(
                    preg_replace(
                        '/\s+/',
                        '',
                        $savedStart
                    )
                );

                if (
                    preg_match(
                        '/^(\d{1,2}):(\d{2})$/',
                        $savedStart,
                        $timeParts
                    )
                ) {

                    $savedHour = (int) $timeParts[1];

                    $savedMinute = $timeParts[2];

                    $savedStart =
                        str_pad(
                            (string) $savedHour,
                            2,
                            '0',
                            STR_PAD_LEFT
                        )
                        . ':'
                        . $savedMinute;
                }

                /*
                |--------------------------------------------------------------------------
                | MATCH SEARCH
                |--------------------------------------------------------------------------
                */

                if (
                    $matchesSearch(
                        $savedStart
                    )
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | DETERMINE WHETHER THIS SCHEDULE ACTUALLY HAS A CLASS
                    |--------------------------------------------------------------------------
                    |
                    | A schedule row by itself does NOT mean occupied.
                    | Some rows exist only as empty timetable slots.
                    |
                    */

                    $hasClass =
                        !empty($schedule->course_code) ||
                        !empty($schedule->subject) ||
                        !empty($schedule->instructor);

                    $matchingSchedules[] = [
                        'schedule' => $schedule,
                        'time' => $schedule->time,
                        'start' => $savedStart,
                        'occupied' => $hasClass,
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SELECT THE TIME SLOT
            |--------------------------------------------------------------------------
            |
            | If there is an AVAILABLE matching slot, use that slot first.
            |
            | Example:
            |
            | 8:00-9:00 = occupied
            | 8:30-9:30 = empty
            |
            | The room can still be requested at 8:30.
            |
            */

            $selectedMatch = collect(
                $matchingSchedules
            )->first(function ($matched) {

                return $matched['occupied'] === false;
            });

            /*
            |--------------------------------------------------------------------------
            | If every matching schedule is occupied, use the first occupied
            | schedule so the room can still be displayed as OCCUPIED.
            |--------------------------------------------------------------------------
            */

            if (!$selectedMatch && !empty($matchingSchedules)) {

                $selectedMatch =
                    $matchingSchedules[0];
            }

            /*
            |--------------------------------------------------------------------------
            | DETERMINE ACTUAL TIME
            |--------------------------------------------------------------------------
            */

            $actualTime =
                $selectedMatch['time']
                ?? $fallbackTime();

            $actualTimeParts =
                explode(
                    '-',
                    $actualTime,
                    2
                );

            $actualStartTime =
                trim(
                    $actualTimeParts[0] ?? ''
                );

            $actualEndTime =
                trim(
                    $actualTimeParts[1] ?? ''
                );

            if (
                $actualStartTime === '' ||
                $actualEndTime === ''
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE REQUESTED DATETIME
            |--------------------------------------------------------------------------
            */

            try {

                $requestedStart =
                    Carbon::parse(
                        $request->date .
                        ' ' .
                        $actualStartTime
                    );

                $requestedEnd =
                    Carbon::parse(
                        $request->date .
                        ' ' .
                        $actualEndTime
                    );

            } catch (\Throwable $e) {

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK APPROVED RESERVATIONS
            |--------------------------------------------------------------------------
            */

            $reserved = false;

            $approvedReservations =
                Reservation::where(
                    'room_id',
                    $room->id
                )
                ->whereDate(
                    'date',
                    $date->format('Y-m-d')
                )
                ->where(
                    'status',
                    'Approved'
                )
                ->get();

            foreach (
                $approvedReservations
                as $reservation
            ) {

                if (
                    !$reservation->start_time ||
                    !$reservation->end_time
                ) {
                    continue;
                }

                try {

                    $reservationStart =
                        Carbon::parse(
                            $reservation->date .
                            ' ' .
                            $reservation->start_time
                        );

                    $reservationEnd =
                        Carbon::parse(
                            $reservation->date .
                            ' ' .
                            $reservation->end_time
                        );

                } catch (\Throwable $e) {

                    continue;
                }

                if (
                    $requestedStart < $reservationEnd &&
                    $requestedEnd > $reservationStart
                ) {

                    $reserved = true;

                    break;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK PENDING RESERVATIONS
            |--------------------------------------------------------------------------
            */

            $pending = false;

            $pendingReservations =
                Reservation::where(
                    'room_id',
                    $room->id
                )
                ->whereDate(
                    'date',
                    $date->format('Y-m-d')
                )
                ->where(
                    'status',
                    'Pending'
                )
                ->get();

            foreach (
                $pendingReservations
                as $reservation
            ) {

                if (
                    !$reservation->start_time ||
                    !$reservation->end_time
                ) {
                    continue;
                }

                try {

                    $reservationStart =
                        Carbon::parse(
                            $reservation->date .
                            ' ' .
                            $reservation->start_time
                        );

                    $reservationEnd =
                        Carbon::parse(
                            $reservation->date .
                            ' ' .
                            $reservation->end_time
                        );

                } catch (\Throwable $e) {

                    continue;
                }

                if (
                    $requestedStart < $reservationEnd &&
                    $requestedEnd > $reservationStart
                ) {

                    $pending = true;

                    break;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | DETERMINE ROOM STATUS
            |--------------------------------------------------------------------------
            |
            | Priority:
            |
            | Occupied
            | Reserved
            | Pending
            | Available
            |
            | IMPORTANT:
            | A schedule row is occupied only when it contains actual
            | course/subject/instructor information.
            |
            */

            $hasOccupiedSchedule =
                collect(
                    $matchingSchedules
                )->contains(function ($matched) {

                    return $matched['occupied'] === true;
                });

            /*
            | If the selected matching slot is empty, it is available
            | unless a reservation already exists for that exact time.
            |
            */

            if (
                $selectedMatch &&
                $selectedMatch['occupied'] === true
            ) {

                $status = 'Occupied';

            } elseif ($reserved) {

                $status = 'Reserved';

            } elseif ($pending) {

                $status = 'Pending';

            } else {

                $status = 'Available';
            }

            /*
            |--------------------------------------------------------------------------
            | ADD RESULT
            |--------------------------------------------------------------------------
            */

            $results[] = [

                'room' =>
                    $room,

                'status' =>
                    $status,

                /*
                | Exact time that should be used when reserving.
                */
                'time' =>
                    $actualTime,

                'start_time' =>
                    $actualStartTime,

                'end_time' =>
                    $actualEndTime,

                /*
                | Selected schedule.
                */
                'schedule' =>
                    $selectedMatch['schedule']
                    ?? null,

                /*
                | All schedules matching the typed search.
                | The Blade can display these times.
                */
                'matched_schedules' =>
                    $matchingSchedules,

                /*
                | Helpful flag for the Blade/JavaScript.
                */
                'has_occupied_schedule' =>
                    $hasOccupiedSchedule,
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX RESPONSE
    |--------------------------------------------------------------------------
    |
    | When the faculty types 8, 8:0, or 8:30, only the room results
    | are returned. The header/sidebar/search page are NOT returned.
    |
    */

    if (
        $request->ajax() ||
        $request->boolean('partial')
    ) {

        return view(
            'faculty.partials.vacant-results',
            compact(
                'rooms',
                'results',
                'facultySchedules',
                'facultyScheduleOptions'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NORMAL PAGE RESPONSE
    |--------------------------------------------------------------------------
    */

    return view(
        'faculty.vacant-rooms',
        compact(
            'rooms',
            'results',
            'facultySchedules',
            'facultyScheduleOptions'
        )
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
    | LOAD ORIGINAL WEEKLY SCHEDULE
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
    | GROUP MERGED PROGRAMS
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
                (string) $merge
                    ->facultySubject
                    ->program
            );

        if ($program === '') {
            continue;
        }

        if (
            !isset(
                $mergedPrograms[$scheduleId]
            )
        ) {
            $mergedPrograms[$scheduleId] = [];
        }

        if (
            !in_array(
                $program,
                $mergedPrograms[$scheduleId]
            )
        ) {
            $mergedPrograms[$scheduleId][] =
                $program;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD ACTIVE ROOM SWAPS
    |--------------------------------------------------------------------------
    |
    | Room swaps are temporary. They are shown only while the
    | approved swap is inside its start/end time.
    |
    */

    $activeRoomSwaps =
        RoomSwapRequest::with([
            'requesterRoom',
            'targetRoom',
        ])
        ->where(
            'status',
            'approved'
        )
        ->where(function ($query) use ($schedules) {

            $query
                ->whereIn(
                    'requester_schedule_id',
                    $schedules->pluck('id')
                )
                ->orWhereIn(
                    'target_schedule_id',
                    $schedules->pluck('id')
                );

        })
        ->get()
        ->filter(function ($swap) {

            try {

                $date =
                    $swap->getRawOriginal(
                        'swap_date'
                    );

                $startTime =
                    $swap->getRawOriginal(
                        'start_time'
                    );

                $endTime =
                    $swap->getRawOriginal(
                        'end_time'
                    );

                if (
                    !$date ||
                    !$startTime ||
                    !$endTime
                ) {
                    return false;
                }

                $start =
                    Carbon::parse(
                        $date . ' ' . $startTime
                    );

                $end =
                    Carbon::parse(
                        $date . ' ' . $endTime
                    );

                return Carbon::now()
                    ->between(
                        $start,
                        $end,
                        true
                    );

            } catch (\Throwable $e) {

                return false;
            }

        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | GROUP ACTIVE ROOM SWAPS BY SCHEDULE
    |--------------------------------------------------------------------------
    */

    $roomSwapsBySchedule = [];

    foreach (
        $activeRoomSwaps
        as $swap
    ) {

        $swapData = [

            'active' =>
                true,

            'swap_id' =>
                $swap->id,

            'swap_date' =>
                $swap->swap_date
                    ? $swap->swap_date
                        ->format('Y-m-d')
                    : null,

            'start_time' =>
                $swap->start_time
                    ? Carbon::parse(
                        $swap->start_time
                    )->format('g:i A')
                    : null,

            'end_time' =>
                $swap->end_time
                    ? Carbon::parse(
                        $swap->end_time
                    )->format('g:i A')
                    : null,

            'reason' =>
                $swap->reason,
        ];


        if (
            $swap->requester_schedule_id
        ) {

            $roomSwapsBySchedule[
                $swap->requester_schedule_id
            ] = array_merge(
                $swapData,
                [
                    'original_room_id' =>
                        $swap->requester_room_id,

                    'original_room_name' =>
                        $swap->requesterRoom?->room_name
                        ?? 'Original room',
                ]
            );
        }


        if (
            $swap->target_schedule_id
        ) {

            $roomSwapsBySchedule[
                $swap->target_schedule_id
            ] = array_merge(
                $swapData,
                [
                    'original_room_id' =>
                        $swap->target_room_id,

                    'original_room_name' =>
                        $swap->targetRoom?->room_name
                        ?? 'Original room',
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD TODAY'S TEMPORARY ROOM RESERVATIONS
    |--------------------------------------------------------------------------
    |
    | Approved:
    |   Shows as "Temporary Reservation".
    |
    | Pending:
    |   Shows as "Reservation Requested".
    |
    | Both automatically disappear from the timetable after
    | their end time. The original weekly schedule is never
    | modified by the reservation.
    |
    */

    $today =
        Carbon::today();

    $now =
        Carbon::now();

    $temporaryReservations =
        Reservation::with([
            'user',
        ])
        ->where(
            'room_id',
            $room->id
        )
        ->whereDate(
            'date',
            $today->format('Y-m-d')
        )
        ->whereIn(
            'status',
            [
                'Pending',
                'Approved',
            ]
        )
        ->get()
        ->filter(function ($reservation) use ($now) {

            if (
                !$reservation->start_time ||
                !$reservation->end_time
            ) {
                return false;
            }

            try {

                $date =
                    Carbon::parse(
                        $reservation->date
                    )->format('Y-m-d');

                $start =
                    Carbon::parse(
                        $date .
                        ' ' .
                        $reservation->start_time
                    );

                $end =
                    Carbon::parse(
                        $date .
                        ' ' .
                        $reservation->end_time
                    );

                /*
                | Only show reservations that have
                | not ended yet.
                */

                return $end->greaterThan($now);

            } catch (\Throwable $e) {

                return false;
            }

        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | FORMAT SCHEDULE DATA
    |--------------------------------------------------------------------------
    */

    $scheduleData =
        $schedules->map(
            function ($schedule)
            use (
                $mergedPrograms,
                $roomSwapsBySchedule
            ) {

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

                    'room_swap' =>
                        $roomSwapsBySchedule[
                            $schedule->id
                        ] ?? null,
                ];
            }
        )
        ->values();


    /*
    |--------------------------------------------------------------------------
    | FORMAT TEMPORARY RESERVATIONS
    |--------------------------------------------------------------------------
    */

    $reservationData =
        $temporaryReservations
            ->map(
                function ($reservation) {

                    $startTime =
                        $reservation->start_time
                            ? Carbon::parse(
                                $reservation->start_time
                            )->format('H:i')
                            : null;

                    $endTime =
                        $reservation->end_time
                            ? Carbon::parse(
                                $reservation->end_time
                            )->format('H:i')
                            : null;

                    return [

                        'id' =>
                            $reservation->id,

                        'status' =>
                            $reservation->status,

                        'date' =>
                            $reservation->date
                                ? Carbon::parse(
                                    $reservation->date
                                )->format('Y-m-d')
                                : null,

                        'day' =>
                            strtoupper(
                                trim(
                                    (string)
                                    (
                                        $reservation->day
                                        ??
                                        Carbon::parse(
                                            $reservation->date
                                        )->format('D')
                                    )
                                )
                            ),

                        'time' =>
                            trim(
                                (string)
                                $reservation->time
                            ),

                        'start_time' =>
                            $startTime,

                        'end_time' =>
                            $endTime,

                        'purpose' =>
                            $reservation->purpose
                            ?? 'Room Reservation',

                        'user_name' =>
                            $reservation->user?->name
                            ?? 'Faculty',

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

        } else {

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

        'success' =>
            true,

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

        'reservations' =>
            $reservationData,

    ]);
}
}