<?php



namespace App\Http\Controllers;





use App\Models\Reservation;

use App\Models\Room;

use App\Models\Department;

use App\Models\Schedule;

use App\Models\ScheduleTimeSlot;

use App\Models\RoomScheduleTimeSlot;

use App\Models\ScheduleMerge;

use App\Models\RoomSwapRequest;

use App\Models\FacultySubject;

use Illuminate\Http\Request;

use App\Models\AcademicTerm;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;



class FacultyController extends Controller

{

   public function dashboard()

{

    $this->expireReservations();

    $now = Carbon::now();



    /*

    |--------------------------------------------------------------------------

    | TOTAL ROOMS

    |--------------------------------------------------------------------------

    */

    $totalRooms = Room::count();





    $allReservations = Reservation::with([

        'room',

        'room.building',

        'facultySubject',

    ])

        ->where('user_id', Auth::id())

        ->latest()

        ->get();



    /*

    |--------------------------------------------------------------------------

    | RESERVATION COUNTS

    |--------------------------------------------------------------------------

    */

    $reservedReservationsCount = $allReservations

        ->where('status', 'Reserved')

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

        ->where('status', 'Reserved')

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

    $faculty = Auth::user();

    $roomSwapRequests = RoomSwapRequest::with([
        'requester',
        'targetUser',
        'requesterSchedule',
        'targetSchedule',
        'requesterRoom',
        'targetRoom',
    ])
        ->whereRaw("LOWER(TRIM(status)) = ?", ['pending'])
        ->where(function ($query) use ($faculty) {
            $query->where('target_user_id', $faculty->id);

            if (!empty($faculty->name)) {
                $query->orWhereHas('targetSchedule', function ($scheduleQuery) use ($faculty) {
                    $scheduleQuery->whereRaw(
                        'LOWER(TRIM(instructor)) = ?',
                        [mb_strtolower(trim($faculty->name))]
                    );
                });
            }
        })
        ->latest()
        ->get();

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

            'reservedReservationsCount',

            'rejectedReservationsCount',

            'roomSwapRequests',

            'roomSwapHistory',

            'totalRooms'

        )

    );

}





    /*

    |--------------------------------------------------------------------------

    | MY RESERVATIONS

    |--------------------------------------------------------------------------

    |

    | Separate page for the faculty member's room reservation requests.

    | The my-bookings Blade handles the reservation list and status filters.

    |

    */

    public function myReservations()

    {

        $this->expireReservations();



        if (!Auth::check()) {

            return redirect()->route('login');

        }



        $allReservations = Reservation::with([

            'room',

            'room.building',

            'facultySubject',

        ])

            ->where('user_id', Auth::id())

            ->latest()

            ->get();



        return view(

            'faculty.my-bookings',

            compact('allReservations')

        );

    }



 public function reserveRoom(Request $request, Room $room)

{

    $this->expireReservations();



    /*

    |--------------------------------------------------------------------------

    | VALIDATE RESERVATION REQUEST

    |--------------------------------------------------------------------------

    |

    | The faculty chooses a SUBJECT from Set Faculty. The reservation time

    | comes from the available room slot selected on the Vacant Rooms page.

    |

    | schedule_id is optional because an available room does not need to have

    | an existing schedule row. faculty_subject_id is the actual subject

    | assignment owned by the logged-in faculty member.

    |--------------------------------------------------------------------------

    */



   $request->validate([

    'date' => 'required|date',

    'time_slot_id' => 'required|integer|exists:schedule_time_slots,id',

    'time' => 'nullable|string|max:50',

    'start_time' => 'nullable|string|max:20',

    'end_time' => 'nullable|string|max:20',

    'faculty_subject_id' => 'required|integer|exists:faculty_subjects,id',

    'schedule_id' => 'nullable|integer|exists:schedules,id',

]);



    $faculty = Auth::user();



    if (!$faculty) {

        return redirect()->route('login');

    }



    /*

    |--------------------------------------------------------------------------

    | SECURITY: SUBJECT MUST BELONG TO THIS FACULTY

    |--------------------------------------------------------------------------

    */



    $facultySubject = FacultySubject::with([

        'faculty',

        'curriculumCourse'

    ])

        ->where('id', $request->faculty_subject_id)

        ->where('faculty_id', $faculty->id)

        ->first();



    if (!$facultySubject) {

        return back()->with(

            'error',

            'The selected subject is not assigned to your faculty account.'

        );

    }



    /*

    |--------------------------------------------------------------------------

    | RESERVATION DAY

    |--------------------------------------------------------------------------

    */



    try {

        $date = Carbon::parse($request->date);

    } catch (\Throwable $e) {

        return back()->with(

            'error',

            'The selected reservation date is invalid.'

        );

    }



    $day = strtoupper($date->format('D'));



    /*

    |--------------------------------------------------------------------------

    | GET ROOM SLOT TIME

    |--------------------------------------------------------------------------

    |

    | The Vacant Rooms page already determines the actual available room time.

    | Use start_time/end_time when available. Otherwise parse the time field.

    |--------------------------------------------------------------------------

    */



    $startTime = trim((string) $request->input('start_time', ''));

    $endTime = trim((string) $request->input('end_time', ''));



    if ($startTime === '' || $endTime === '') {

        $timeValue = trim((string) $request->input('time', ''));

        $timeParts = preg_split('/**\s**\*-**\s***/', $timeValue, 2);



        $startTime = trim($timeParts[0] ?? '');

        $endTime = trim($timeParts[1] ?? '');

    }



    if ($startTime === '' || $endTime === '') {

        return back()->with(

            'error',

            'The selected room does not have a valid reservation time.'

        );

    }



    try {

        $startTime = Carbon::createFromFormat('H:i', $startTime)->format('H:i');

        $endTime = Carbon::createFromFormat('H:i', $endTime)->format('H:i');

    } catch (\Throwable $e) {

        return back()->with(

            'error',

            'The selected room has an invalid time format.'

        );

    }



    $reservationTime = $startTime . '-' . $endTime;



    $requestedStart = Carbon::parse(

        $date->format('Y-m-d') . ' ' . $startTime

    );



    $requestedEnd = Carbon::parse(

        $date->format('Y-m-d') . ' ' . $endTime

    );



    if (!$requestedEnd->greaterThan($requestedStart)) {

        return back()->with(

            'error',

            'The selected room has an invalid reservation time range.'

        );

    }



    /*

    |--------------------------------------------------------------------------

    | PREVENT RESERVING OVER AN EXISTING CLASS

    |--------------------------------------------------------------------------

    */



    $roomSchedules = Schedule::where('room_id', $room->id)

        ->where(function ($query) use ($day) {

            $query->whereRaw('UPPER(day) = ?', [$day])

                ->orWhereRaw('UPPER(day) = ?', [

                    match ($day) {

                        'MON' => 'MONDAY',

                        'TUE' => 'TUESDAY',

                        'WED' => 'WEDNESDAY',

                        'THU' => 'THURSDAY',

                        'FRI' => 'FRIDAY',

                        'SAT' => 'SATURDAY',

                        'SUN' => 'SUNDAY',

                        default => $day,

                    }

                ]);

        })

        ->get();



    foreach ($roomSchedules as $roomSchedule) {

        $roomTime = preg_split(

            '/**\s**\*-**\s***/',

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

                $date->format('Y-m-d') . ' ' . $roomStart

            );



            $existingEnd = Carbon::parse(

                $date->format('Y-m-d') . ' ' . $roomEnd

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

                'This room already has a class schedule during the selected reservation time.'

            );

        }

    }



    /*

    |--------------------------------------------------------------------------

    | PREVENT DUPLICATE RESERVATIONS

    |--------------------------------------------------------------------------

    */



    $existingReservations = Reservation::where('room_id', $room->id)

        ->whereDate('date', $date->format('Y-m-d'))

        ->where('status', 'Reserved')

        ->get();



    foreach ($existingReservations as $reservation) {

        if (!$reservation->start_time || !$reservation->end_time) {

            continue;

        }



        try {

            $reservationStart = Carbon::parse(

                $date->format('Y-m-d') . ' ' . $reservation->start_time

            );



            $reservationEnd = Carbon::parse(

                $date->format('Y-m-d') . ' ' . $reservation->end_time

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

                'This room already has a reservation request during the selected time.'

            );

        }

    }



    /*

    |--------------------------------------------------------------------------

    | CREATE RESERVATION

    |--------------------------------------------------------------------------

    |

    | faculty_subject_id identifies the Set Faculty subject selected in the

    | popup. schedule_id stays nullable because the room is being requested

    | precisely because that room slot is available.

    |--------------------------------------------------------------------------

    */



    Reservation::create([

        'room_id' => $room->id,

        'user_id' => $faculty->id,

        'schedule_id' => $request->filled('schedule_id')

            ? (int) $request->schedule_id

            : null,

        'faculty_subject_id' => $facultySubject->id,

        'date' => $date->format('Y-m-d'),

        'day' => $day,

        'time' => $reservationTime,

        'start_time' => $startTime,

        'end_time' => $endTime,

        'purpose' => $room->purpose ?? 'Room Reservation',

        'status' => 'Reserved',

    ]);



  return redirect()

    ->route('faculty.vacant', [

        'day' => $day,

        'time_slot_id' => $request->input('time_slot_id'),

        'date' => $date->format('Y-m-d'),

    ])

    ->with(

        'success',

        'Room reserved successfully for ' .

        $facultySubject->course_code .

        '. The room is now reserved.'

    );

}



public function vacantRooms(Request $request)

{

    /*

    |--------------------------------------------------------------------------

    | RESOLVE THE ACADEMIC TERM

    |--------------------------------------------------------------------------

    | Use the term selected by the system/session first. Only fall back to the

    | database active term when no valid selected term exists.

    |--------------------------------------------------------------------------

    */

    $activeTerm = null;



    $sessionTermId = session('active_academic_term_id');



    if ($sessionTermId) {

        $activeTerm = AcademicTerm::find($sessionTermId);

    }



    if (!$activeTerm) {

        $activeTerm = AcademicTerm::where('is_active', true)

            ->orderByDesc('id')

            ->first();

    }



    if (!$activeTerm) {

        $activeTerm = AcademicTerm::orderByDesc('id')->first();

    }



    if ($activeTerm) {

        session(['active_academic_term_id' => $activeTerm->id]);

    }



    $rooms = Room::with([

        'department',

        'building',

    ])

        ->orderBy('room_name')

        ->get();



    $results = [];



    /*

    |--------------------------------------------------------------------------

    | LOAD ACTUAL PUBLISHED ROOM TIME ROWS

    |--------------------------------------------------------------------------

    | These rows are room + academic-term specific. They are the source of

    | truth for which times can actually be searched for each room.

    |--------------------------------------------------------------------------

    */

    $roomTimeSlots = collect();



    if ($activeTerm) {

        $roomTimeSlots = RoomScheduleTimeSlot::query()

            ->where('academic_term_id', $activeTerm->id)

            ->where('is_active', true)

            ->get();

    }



    /*

    |--------------------------------------------------------------------------

    | BUILD SEARCH TIME OPTIONS

    |--------------------------------------------------------------------------

    | Use ScheduleTimeSlot when available, then add any custom room time that

    | exists in room_schedule_time_slots but is missing from the global table.

    | This makes newly added/editable times searchable without hard-coding

    | 8-9, 9-10, etc.

    |--------------------------------------------------------------------------

    */

    $dayOrder = [

        'MON' => 1,

        'TUE' => 2,

        'WED' => 3,

        'THU' => 4,

        'FRI' => 5,

        'SAT' => 6,

    ];



    $normalizeDay = function ($day) {

        $value = strtoupper(trim((string) $day));



        return match ($value) {

            'MONDAY' => 'MON',

            'TUESDAY' => 'TUE',

            'WEDNESDAY' => 'WED',

            'THURSDAY' => 'THU',

            'FRIDAY' => 'FRI',

            'SATURDAY' => 'SAT',

            'SUNDAY' => 'SUN',

            default => $value,

        };

    };



    $clock = function ($value) {

        $value = trim((string) $value);



        if ($value === '') {

            return null;

        }



        try {

            return Carbon::parse($value)->format('H:i:s');

        } catch (\Throwable $e) {

            return null;

        }

    };



    $timeKey = function ($day, $start, $end) use ($normalizeDay, $clock) {

        $day = $normalizeDay($day);

        $start = $clock($start);

        $end = $clock($end);



        if (!$day || !$start || !$end) {

            return null;

        }



        return $day . '|' . $start . '|' . $end;

    };



    $timeSlots = collect();

    $seenTimeSlots = [];



    if ($activeTerm) {

        $globalSlots = ScheduleTimeSlot::query()

            ->where('academic_term_id', $activeTerm->id)

            ->where('is_active', true)

            ->get();



        foreach ($globalSlots as $slot) {

            $day = $normalizeDay($slot->day);

            $start = $clock($slot->start_time);

            $end = $clock($slot->end_time);



            if (!$day || !$start || !$end) {

                continue;

            }



            $key = $day . '|' . $start . '|' . $end;



            /* Only expose global time rows that actually exist for at least one room. */

            $existsForARoom = $roomTimeSlots->contains(function ($roomSlot) use (

                $key,

                $timeKey

            ) {

                $roomKey = $timeKey(

                    $roomSlot->day,

                    $roomSlot->start_time,

                    $roomSlot->end_time

                );



                return $roomKey === $key && (bool) $roomSlot->is_active;

            });



            if (!$existsForARoom || isset($seenTimeSlots[$key])) {

                continue;

            }



            $seenTimeSlots[$key] = true;

            $timeSlots->push($slot);

        }



        /* Add custom room times that are not in ScheduleTimeSlot. */

        foreach ($roomTimeSlots as $roomSlot) {

            $day = $normalizeDay($roomSlot->day);

            $start = $clock($roomSlot->start_time);

            $end = $clock($roomSlot->end_time);



            if (!$day || !$start || !$end) {

                continue;

            }



            $key = $day . '|' . $start . '|' . $end;



            if (isset($seenTimeSlots[$key])) {

                continue;

            }



            $seenTimeSlots[$key] = true;



            $timeSlots->push((object) [

                'id' => 'room:' . $key,

                'day' => $day,

                'start_time' => $start,

                'end_time' => $end,

                'row_position' => $roomSlot->row_position,

                'is_active' => true,

            ]);

        }

    }



    $timeSlots = $timeSlots

        ->sortBy(function ($slot) use ($dayOrder, $normalizeDay, $clock) {

            $day = $normalizeDay($slot->day);

            $start = $clock($slot->start_time) ?? '99:99:99';



            return sprintf(

                '%02d-%s',

                $dayOrder[$day] ?? 99,

                $start

            );

        })

        ->values();



    /*

    |--------------------------------------------------------------------------

    | SELECTED DAY + TIME

    |--------------------------------------------------------------------------

    */

    $selectedDay = $normalizeDay($request->input('day', ''));

    $selectedTimeSlotId = $request->input('time_slot_id');

    $selectedTimeSlot = null;



    if ($selectedTimeSlotId !== null && $selectedTimeSlotId !== '') {

        $selectedTimeSlot = $timeSlots->first(function ($slot) use ($selectedTimeSlotId) {

            return (string) $slot->id === (string) $selectedTimeSlotId;

        });



        if ($selectedTimeSlot) {

            $selectedDay = $normalizeDay($selectedTimeSlot->day);

        }

    }



    /* Old links/forms may send day + time instead of time_slot_id. */

    if (!$selectedTimeSlot && $request->filled('time')) {

        $legacyTime = trim((string) $request->input('time'));

        $legacyParts = preg_split('/**\s**\*-**\s***/', $legacyTime, 2);



        if (count($legacyParts) === 2) {

            $legacyStart = $clock($legacyParts[0]);

            $legacyEnd = $clock($legacyParts[1]);



            $selectedTimeSlot = $timeSlots->first(function ($slot) use (

                $selectedDay,

                $legacyStart,

                $legacyEnd,

                $normalizeDay,

                $clock

            ) {

                return $normalizeDay($slot->day) === $selectedDay

                    && $clock($slot->start_time) === $legacyStart

                    && $clock($slot->end_time) === $legacyEnd;

            });



            if ($selectedTimeSlot) {

                $selectedTimeSlotId = $selectedTimeSlot->id;

            }

        }

    }



    /*

    |--------------------------------------------------------------------------

    | SEARCH ROOMS

    |--------------------------------------------------------------------------

    */

    if ($selectedTimeSlot && $activeTerm) {

        $day = $normalizeDay($selectedTimeSlot->day);

        $slotStartValue = $clock($selectedTimeSlot->start_time);

        $slotEndValue = $clock($selectedTimeSlot->end_time);



        if ($slotStartValue && $slotEndValue) {

            $slotStart = Carbon::parse($slotStartValue);

            $slotEnd = Carbon::parse($slotEndValue);



            if ($slotEnd->greaterThan($slotStart)) {

                $slotTime =

                    $slotStart->format('H:i') . '-' .

                    $slotEnd->format('H:i');



                $fullDay = match ($day) {

                    'MON' => 'MONDAY',

                    'TUE' => 'TUESDAY',

                    'WED' => 'WEDNESDAY',

                    'THU' => 'THURSDAY',

                    'FRI' => 'FRIDAY',

                    'SAT' => 'SATURDAY',

                    'SUN' => 'SUNDAY',

                    default => $day,

                };



                /* Resolve an actual calendar date for reservations. */

                $reservationDate = null;



                if ($request->filled('date')) {

                    try {

                        $parsedDate = Carbon::parse($request->input('date'));



                        if (strtoupper($parsedDate->format('D')) === $day) {

                            $reservationDate = $parsedDate->format('Y-m-d');

                        }

                    } catch (\Throwable $e) {

                        $reservationDate = null;

                    }

                }



                foreach ($rooms as $room) {

                    /*

                    |------------------------------------------------------------------

                    | ROOM MUST HAVE THIS EXACT PUBLISHED TIME ROW

                    |------------------------------------------------------------------

                    */

                    $hasRoomTimeSlot = $roomTimeSlots->contains(function ($roomSlot) use (

                        $room,

                        $day,

                        $slotStartValue,

                        $slotEndValue,

                        $normalizeDay,

                        $clock

                    ) {

                        return (int) $roomSlot->room_id === (int) $room->id

                            && $normalizeDay($roomSlot->day) === $day

                            && $clock($roomSlot->start_time) === $slotStartValue

                            && $clock($roomSlot->end_time) === $slotEndValue

                            && (bool) $roomSlot->is_active;

                    });



                    if (!$hasRoomTimeSlot) {

                        continue;

                    }



                    /*

                    |------------------------------------------------------------------

                    | LOAD THIS ROOM'S PUBLISHED CLASSES FOR THIS DAY + TERM

                    |------------------------------------------------------------------

                    */

                    $roomSchedules = Schedule::where('room_id', $room->id)

                        ->where('academic_term_id', $activeTerm->id)

                        ->get()

                        ->filter(function ($schedule) use ($day, $normalizeDay) {

                            return $normalizeDay($schedule->day) === $day;

                        })

                        ->values();



                    $occupied = false;

                    $hasConflictingSchedule = false;



                    foreach ($roomSchedules as $schedule) {

                        $hasClass =

                            trim((string) $schedule->course_code) !== ''

                            || trim((string) $schedule->subject) !== ''

                            || trim((string) $schedule->instructor) !== ''

                            || !empty($schedule->faculty_id);



                        if (!$hasClass) {

                            continue;

                        }



                        $parts = preg_split(

                            '/**\s**\*-**\s***/',

                            trim((string) $schedule->time),

                            2

                        );



                        if (count($parts) !== 2) {

                            continue;

                        }



                        $scheduleStartValue = $clock($parts[0]);

                        $scheduleEndValue = $clock($parts[1]);



                        if (!$scheduleStartValue || !$scheduleEndValue) {

                            continue;

                        }



                        $scheduleStart = Carbon::parse($scheduleStartValue);

                        $scheduleEnd = Carbon::parse($scheduleEndValue);



                        if (!$scheduleEnd->greaterThan($scheduleStart)) {

                            continue;

                        }



                        $isExact =

                            $scheduleStartValue === $slotStartValue

                            && $scheduleEndValue === $slotEndValue;



                        if ($isExact) {

                            $occupied = true;

                            break;

                        }



                        $overlaps =

                            $scheduleStart->lt($slotEnd)

                            && $scheduleEnd->gt($slotStart);



                        if ($overlaps) {

                            $hasConflictingSchedule = true;

                            break;

                        }

                    }



                    /*

                    |------------------------------------------------------------------

                    | DATE-SPECIFIC RESERVATION

                    |------------------------------------------------------------------

                    */

                    $reserved = false;



                    if ($reservationDate !== null) {

                        $reservations = Reservation::where('room_id', $room->id)

                            ->whereDate('date', $reservationDate)

                            ->where('status', 'Reserved')

                            ->get();



                        foreach ($reservations as $reservation) {

                            if (!$reservation->start_time || !$reservation->end_time) {

                                continue;

                            }



                            $reservationStartValue = $clock($reservation->start_time);

                            $reservationEndValue = $clock($reservation->end_time);



                            if (!$reservationStartValue || !$reservationEndValue) {

                                continue;

                            }



                            $reservationStart = Carbon::parse($reservationStartValue);

                            $reservationEnd = Carbon::parse($reservationEndValue);



                            if (!$reservationEnd->greaterThan($reservationStart)) {

                                continue;

                            }



                            if (

                                $reservationStart->lt($slotEnd)

                                && $reservationEnd->gt($slotStart)

                            ) {

                                $reserved = true;

                                break;

                            }

                        }

                    }



                    /*

                    |------------------------------------------------------------------

                    | FINAL STATUS

                    |------------------------------------------------------------------

                    | Exact class > reservation > conflicting class > available.

                    |------------------------------------------------------------------

                    */

                    if ($occupied) {

                        $status = 'Occupied';

                    } elseif ($reserved) {

                        $status = 'Reserved';

                    } elseif ($hasConflictingSchedule) {

                        continue;

                    } else {

                        $status = 'Available';

                    }



                    $results[] = [

                        'room' => $room,

                        'status' => $status,

                        'day' => $day,

                        'time' => $slotTime,

                        'time_slot_id' => $selectedTimeSlot->id,

                        'reservation_date' => $reservationDate,

                    ];

                }

            }

        }

    }



    /*

    |--------------------------------------------------------------------------

    | FACULTY SUBJECTS

    |--------------------------------------------------------------------------

    */

    $loggedInFaculty = Auth::user();



    $facultySubjectOptions = FacultySubject::with([

        'faculty',

        'curriculumCourse',

    ])

        ->where('faculty_id', $loggedInFaculty?->id)

        ->orderBy('course_code')

        ->orderBy('subject')

        ->get()

        ->map(function ($facultySubject) {

            return [

                'id' => $facultySubject->id,

                'course_code' => $facultySubject->course_code,

                'subject' => $facultySubject->subject,

                'program' => $facultySubject->program,

                'year_level' => $facultySubject->year_level,

                'major' => $facultySubject->major,

                'subject_type' => $facultySubject->curriculumCourse?->subject_type,

            ];

        })

        ->values();



    return view(

        'faculty.vacant-rooms',

        compact(

            'rooms',

            'results',

            'activeTerm',

            'timeSlots',

            'selectedDay',

            'selectedTimeSlotId',

            'facultySubjectOptions'

        )

    );

}



/*

\|--------------------------------------------------------------------------

\| FACULTY SUBJECTS

\|--------------------------------------------------------------------------

*/



public function schedule()

{

    /*

    |--------------------------------------------------------------------------

    | GET ACTIVE ACADEMIC TERM

    |--------------------------------------------------------------------------

    */



    $activeTermId =

        session('active_academic_term_id');



    $activeTerm = null;



    if ($activeTermId) {



        $activeTerm =

            AcademicTerm::find($activeTermId);



    }



    /*

    |--------------------------------------------------------------------------

    | FALLBACK TO DATABASE ACTIVE TERM

    |--------------------------------------------------------------------------

    */



    if (!$activeTerm) {



        $activeTerm =

            AcademicTerm::where(

                'is_active',

                true

            )

            ->orderByDesc('id')

            ->first();



    }



    /*

    |--------------------------------------------------------------------------

    | LOAD ROOMS

    |--------------------------------------------------------------------------

    */



    $rooms = Room::with([

        'department',



        'building',



        'schedules' => function ($query) use ($activeTerm) {



            if ($activeTerm) {



                $query->where(

                    'academic_term_id',

                    $activeTerm->id

                );



            }



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

    | GROUP ROOMS BY DEPARTMENT

    |--------------------------------------------------------------------------

    */



    $departments = $rooms

        ->groupBy('department_id')

        ->map(function (

            $departmentRooms,

            $departmentId

        ) {



            $department =

                $departmentRooms

                    ->first()

                    ->department;





            /*

            |--------------------------------------------------------------------------

            | GROUP BY BUILDING

            |--------------------------------------------------------------------------

            */



            $buildings = $departmentRooms

                ->groupBy('building_id')

                ->map(function (

                    $buildingRooms,

                    $buildingId

                ) {



                    $firstRoom =

                        $buildingRooms->first();





                    $buildingName =

                        is_string(

                            $firstRoom->building

                        )

                        ? $firstRoom->building

                        : (

                            $firstRoom

                                ->building

                                ?->building_name



                            ??



                            $firstRoom

                                ->building

                                ?->name



                            ??



                            'Building'

                        );





                    return [



                        'id' =>

                            $buildingId,



                        'name' =>

                            $buildingName,



                        'rooms' =>

                            $buildingRooms,



                    ];



                })

                ->values();





            return [



                'id' =>

                    $departmentId,



                'name' =>

                    $department?->name

                    ?? 'Department',



                'rooms' =>

                    $departmentRooms,



                'buildings' =>

                    $buildings,



            ];



        })

        ->values();





    /*

    |--------------------------------------------------------------------------

    | OFFICIAL TIME SLOTS FOR THE PUBLISHED FACULTY VIEW

    |--------------------------------------------------------------------------

    */

    $timeSlots = collect();



    if ($activeTerm) {

        $timeSlots = ScheduleTimeSlot::where(

            'academic_term_id',

            $activeTerm->id

        )

        ->where('is_active', true)

        ->orderByRaw(

            'CASE WHEN row_position IS NULL THEN 999999 ELSE row_position END'

        )

        ->orderBy('start_time')

        ->orderBy('id')

        ->get();

    }



    return view(

        'faculty.schedules',

        compact(

            'rooms',

            'departments',

            'activeTerm',

            'timeSlots'

        )

    );

}

public function viewSchedule(Room $room)

{

    $this->expireReservations();



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



    /*

\|--------------------------------------------------------------------------

\| GET ACTIVE ACADEMIC TERM

\|--------------------------------------------------------------------------

*/



$activeTermId =

    session('active_academic_term_id');



$activeTerm = null;



if ($activeTermId) {



    $activeTerm =

        \App\Models\AcademicTerm::find(

            $activeTermId

        );



}





/*

\|--------------------------------------------------------------------------

\| FALLBACK TO CURRENT ACTIVE TERM

\|--------------------------------------------------------------------------

*/



if (!$activeTerm) {



    $activeTerm =

        \App\Models\AcademicTerm::where(

            'is_active',

            true

        )

        ->orderByDesc('id')

        ->first();



}





/*

\|--------------------------------------------------------------------------

\| LOAD ONLY CURRENT TERM SCHEDULES

\|--------------------------------------------------------------------------

*/



$schedulesQuery = Schedule::where(

    'room_id',

    $room->id

);





if ($activeTerm) {



    $schedulesQuery->where(

        'academic_term_id',

        $activeTerm->id

    );



}





$schedules = $schedulesQuery

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
    | Room swaps are temporary.
    |
    | IMPORTANT:
    | The schedules.room_id values are NOT changed.
    |
    | When an approved swap is currently active, the schedule belonging
    | to the other room is temporarily added to this room's display.
    |
    */

    $now = Carbon::now();

    $activeRoomSwaps = RoomSwapRequest::with([
        'requesterRoom',
        'targetRoom',
        'requesterSchedule',
        'targetSchedule',
    ])
        ->where('status', 'approved')
        ->where(function ($query) use ($room) {

            $query
                ->where('requester_room_id', $room->id)
                ->orWhere('target_room_id', $room->id);

        })
        ->get()
        ->filter(function ($swap) use ($now) {

            try {

                $date = $swap->getRawOriginal('swap_date');
                $startTime = $swap->getRawOriginal('start_time');
                $endTime = $swap->getRawOriginal('end_time');

                if (
                    !$date ||
                    !$startTime ||
                    !$endTime
                ) {
                    return false;
                }

                $start = Carbon::parse(
                    $date . ' ' . $startTime
                );

                $end = Carbon::parse(
                    $date . ' ' . $endTime
                );

                /*
                 * Active only during the approved swap period.
                 *
                 * Before start:
                 *   original room remains unchanged.
                 *
                 * During start/end:
                 *   swapped schedule is displayed.
                 *
                 * After end:
                 *   original schedule is displayed again.
                 */
                return $now->greaterThanOrEqualTo($start)
                    && $now->lessThan($end);

            } catch (\Throwable $e) {

                return false;
            }

        })
        ->values();


    /*
    |--------------------------------------------------------------------------
    | ADD TEMPORARY SWAPPED SCHEDULES TO THIS ROOM
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | Faculty A
    | Original Room 41
    |
    | Faculty B
    | Original Room 42
    |
    | During the approved swap:
    |
    | Room 41 displays Faculty B's schedule.
    | Room 42 displays Faculty A's schedule.
    |
    | The database room_id values remain unchanged.
    |
    */

    $roomSwapsBySchedule = [];

    foreach ($activeRoomSwaps as $swap) {

        $swapData = [
            'active' => true,

            'swap_id' => $swap->id,

            'swap_date' => $swap->swap_date
                ? Carbon::parse($swap->swap_date)->format('Y-m-d')
                : null,

            'start_time' => $swap->start_time
                ? Carbon::parse($swap->start_time)->format('g:i A')
                : null,

            'end_time' => $swap->end_time
                ? Carbon::parse($swap->end_time)->format('g:i A')
                : null,

            'reason' => $swap->reason,
        ];


        /*
        |--------------------------------------------------------------------------
        | CURRENT ROOM IS REQUESTER ROOM
        |--------------------------------------------------------------------------
        |
        | The requester normally belongs to requester_room_id.
        | During the swap, the target schedule temporarily appears here.
        |
        */

        if (
            (int) $swap->requester_room_id ===
            (int) $room->id
        ) {

            $swappedSchedule = $swap->targetSchedule;

            if ($swappedSchedule) {

                /*
                 * Prevent duplicate schedule entries.
                 */
                $alreadyLoaded = $schedules->contains(
                    function ($schedule) use ($swappedSchedule) {
                        return (int) $schedule->id ===
                            (int) $swappedSchedule->id;
                    }
                );

                if (!$alreadyLoaded) {

                    $schedules->push(
                        $swappedSchedule
                    );
                }


                $roomSwapsBySchedule[
                    $swappedSchedule->id
                ] = array_merge(
                    $swapData,
                    [
                        'original_room_id' =>
                            $swap->target_room_id,

                        'original_room_name' =>
                            $swap->targetRoom?->room_name
                            ?? 'Original room',

                        'swapped_to_room_id' =>
                            $swap->requester_room_id,

                        'swapped_to_room_name' =>
                            $swap->requesterRoom?->room_name
                            ?? $room->room_name,
                    ]
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT ROOM IS TARGET ROOM
        |--------------------------------------------------------------------------
        |
        | The target normally belongs to target_room_id.
        | During the swap, the requester schedule temporarily appears here.
        |
        */

        if (
            (int) $swap->target_room_id ===
            (int) $room->id
        ) {

            $swappedSchedule = $swap->requesterSchedule;

            if ($swappedSchedule) {

                /*
                 * Prevent duplicate schedule entries.
                 */
                $alreadyLoaded = $schedules->contains(
                    function ($schedule) use ($swappedSchedule) {
                        return (int) $schedule->id ===
                            (int) $swappedSchedule->id;
                    }
                );

                if (!$alreadyLoaded) {

                    $schedules->push(
                        $swappedSchedule
                    );
                }


                $roomSwapsBySchedule[
                    $swappedSchedule->id
                ] = array_merge(
                    $swapData,
                    [
                        'original_room_id' =>
                            $swap->requester_room_id,

                        'original_room_name' =>
                            $swap->requesterRoom?->room_name
                            ?? 'Original room',

                        'swapped_to_room_id' =>
                            $swap->target_room_id,

                        'swapped_to_room_name' =>
                            $swap->targetRoom?->room_name
                            ?? $room->room_name,
                    ]
                );
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RE-SORT SCHEDULES
    |--------------------------------------------------------------------------
    |
    | The swapped schedule was temporarily added to the collection,
    | so sort it using the same timetable order as the original schedules.
    |
    */

    $schedules = $schedules
        ->sortBy([
            [
                function ($schedule) {
                    return $schedule->row_position ?? 999;
                },
                'asc'
            ],
            [
                function ($schedule) {
                    return $schedule->id ?? 0;
                },
                'asc'
            ],
        ])
        ->values();


    /*

    |--------------------------------------------------------------------------

    | LOAD TODAY'S TEMPORARY ROOM RESERVATIONS

    |--------------------------------------------------------------------------

    |

    | Reserved:

    |   Shows as "Temporary Reservation".

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

        ->where('status', 'Reserved')

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

                $roomSwap =
                    $roomSwapsBySchedule[
                        $schedule->id
                    ] ?? null;


                /*
                |--------------------------------------------------------------------------
                | DETERMINE DISPLAY ROOM
                |--------------------------------------------------------------------------
                |
                | The database room_id remains unchanged.
                |
                | If this schedule is currently involved in an active room swap,
                | the frontend should know which room it is temporarily occupying.
                |
                */

                $displayRoomId = $schedule->room_id ?? null;

                $displayRoomName = null;


                if ($roomSwap) {

                    $displayRoomId =
                        $roomSwap['swapped_to_room_id']
                        ?? $displayRoomId;

                    $displayRoomName =
                        $roomSwap['swapped_to_room_name']
                        ?? null;
                }


                return [

                    'id' => $schedule->id,

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
                                (string) $schedule->day
                            )
                        ),

                    'time' =>
                        trim(
                            (string) $schedule->time
                        ),

                    'row_position' =>
                        $schedule->row_position !== null
                            ? (int) $schedule->row_position
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

                    /*
                    |--------------------------------------------------------------------------
                    | ROOM INFORMATION
                    |--------------------------------------------------------------------------
                    */

                    'room_id' =>
                        $displayRoomId,

                    'room_name' =>
                        $displayRoomName,

                    'original_room_id' =>
                        $roomSwap['original_room_id']
                        ?? ($schedule->room_id ?? null),

                    'original_room_name' =>
                        $roomSwap['original_room_name']
                        ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | ROOM SWAP INFORMATION
                    |--------------------------------------------------------------------------
                    */

                    'room_swap' =>
                        $roomSwap,

                    'is_room_swap_active' =>
                        $roomSwap !== null,

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



        'time_slots' =>

            $activeTerm

                ? ScheduleTimeSlot::where(

                    'academic_term_id',

                    $activeTerm->id

                )

                ->where('is_active', true)

                ->orderByRaw(

                    'CASE WHEN row_position IS NULL THEN 999999 ELSE row_position END'

                )

                ->orderBy('start_time')

                ->orderBy('id')

                ->get([

                    'id',

                    'day',

                    'start_time',

                    'end_time',

                    'row_position',

                ])

                ->map(function ($slot) {

                    return [

                        'id' => $slot->id,

                        'day' => strtoupper(trim((string) $slot->day)),

                        'start_time' => Carbon::parse($slot->start_time)->format('H:i'),

                        'end_time' => Carbon::parse($slot->end_time)->format('H:i'),

                        'time' =>

                            Carbon::parse($slot->start_time)->format('g:i')

                            . '-'

                            . Carbon::parse($slot->end_time)->format('g:i'),

                        'row_position' => $slot->row_position !== null

                            ? (int) $slot->row_position

                            : null,

                    ];

                })

                ->values()

            : [],



    ]);

}

/*

\|--------------------------------------------------------------------------

\| MY SCHEDULES

\|--------------------------------------------------------------------------

|

\| Shows ONLY the schedules assigned to the currently logged-in faculty.

\| Uses the active academic term when one is available.

|

*/

public function mySchedules()

{

    $this->expireReservations();



    if (!Auth::check()) {

        return redirect()->route('login');

    }



    $faculty = Auth::user();



    /*

    |--------------------------------------------------------------------------

    | RESOLVE ACTIVE ACADEMIC TERM

    |--------------------------------------------------------------------------

    */



    $activeTerm = null;

    $activeTermId = session('active_academic_term_id');



    if ($activeTermId) {

        $activeTerm = AcademicTerm::where(

            'id',

            $activeTermId

        )

        ->where('is_active', true)

        ->first();

    }



    if (!$activeTerm) {

        $activeTerm = AcademicTerm::where('is_active', true)

            ->orderByDesc('id')

            ->first();

    }



    if (!$activeTerm) {

        $activeTerm = AcademicTerm::orderByDesc('id')->first();

    }



    if ($activeTerm) {

        session(['active_academic_term_id' => $activeTerm->id]);

    }



    /*

    |--------------------------------------------------------------------------

    | ONLY THE LOGGED-IN FACULTY

    |--------------------------------------------------------------------------

    |

    | New schedule rows use faculty_id.

    | Legacy rows without faculty_id use instructor = faculty name.

    |

    */



    $query = Schedule::with([

        'room.building',

        'room.department',

    ])

    ->where(function ($query) use ($faculty) {

        $query

            ->where('faculty_id', $faculty->id)

            ->orWhere(function ($legacyQuery) use ($faculty) {

                $legacyQuery

                    ->whereNull('faculty_id')

                    ->where('instructor', $faculty->name);

            });

    });



    /*

    |--------------------------------------------------------------------------

    | ACTIVE TERM ONLY

    |--------------------------------------------------------------------------

    */



    if ($activeTerm) {

        $query->where('academic_term_id', $activeTerm->id);

    }



    $mySchedules = $query

        ->orderByRaw("

            CASE

                WHEN UPPER(day) IN ('MON','MONDAY') THEN 1

                WHEN UPPER(day) IN ('TUE','TUESDAY') THEN 2

                WHEN UPPER(day) IN ('WED','WEDNESDAY') THEN 3

                WHEN UPPER(day) IN ('THU','THURSDAY') THEN 4

                WHEN UPPER(day) IN ('FRI','FRIDAY') THEN 5

                WHEN UPPER(day) IN ('SAT','SATURDAY') THEN 6

                ELSE 7

            END

        ")

        ->orderByRaw(

            'CASE WHEN row_position IS NULL THEN 999 ELSE row_position END'

        )

        ->orderBy('time')

        ->orderBy('id')

        ->get();



    /*

    |--------------------------------------------------------------------------

    | OFFICIAL TIME SLOTS

    |--------------------------------------------------------------------------

    |

    | My Schedules is a published view, so it follows the same official

    | timetable rows that were published by the Chair.

    |--------------------------------------------------------------------------

    */

    $timeSlots = collect();



    if ($activeTerm) {

        $timeSlots = ScheduleTimeSlot::where(

            'academic_term_id',

            $activeTerm->id

        )

        ->where('is_active', true)

        ->orderByRaw(

            'CASE WHEN row_position IS NULL THEN 999999 ELSE row_position END'

        )

        ->orderBy('start_time')

        ->orderBy('id')

        ->get();

    }



    return view(

        'faculty.myschedules',

        compact(

            'mySchedules',

            'activeTerm',

            'timeSlots'

        )

    );

}



    /*

    |--------------------------------------------------------------------------

    | EXPIRE TEMPORARY RESERVATIONS

    |--------------------------------------------------------------------------

    |

    | A reservation is temporary. Once its date + end_time has passed,

    | it becomes Expired. This is checked whenever a relevant page/request

    | is opened, so expired reservations stop blocking rooms and disappear

    | from active schedule displays.

    |

    */

    /*\*

     \* Make sure every academic term has the official Monday-Friday

     \* timetable slots used by Faculty -> Vacant Rooms.

     \* Existing/custom slots are preserved.

     */

    private function ensureDefaultTimeSlots(AcademicTerm $term): void

    {

        $defaults = [

            ['08:00:00', '09:00:00', 0],

            ['09:00:00', '10:00:00', 1],

            ['10:00:00', '11:00:00', 2],

            ['11:00:00', '12:00:00', 3],

            ['12:00:00', '13:00:00', 4],

            ['13:00:00', '14:00:00', 5],

            ['14:00:00', '15:00:00', 6],

            ['15:00:00', '16:00:00', 7],

            ['16:00:00', '17:00:00', 8],

        ];



        foreach (['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'] as $day) {

            foreach ($defaults as [$start, $end, $position]) {

                ScheduleTimeSlot::firstOrCreate(

                    [

                        'academic_term_id' => $term->id,

                        'day' => $day,

                        'start_time' => $start,

                        'end_time' => $end,

                    ],

                    [

                        'row_position' => $position,

                        'is_active' => true,

                    ]

                );

            }

        }

    }



    private function expireReservations(): void

    {

        $now = Carbon::now();



        Reservation::where('status', 'Reserved')

            ->whereNotNull('date')

            ->whereNotNull('end_time')

            ->get()

            ->each(function ($reservation) use ($now) {



                try {

                    $date = Carbon::parse($reservation->date)->format('Y-m-d');

                    $end = Carbon::parse(

                        $date . ' ' . trim((string) $reservation->end_time)

                    );



                    if ($end->lessThanOrEqualTo($now)) {

                        $reservation->update([

                            'status' => 'Expired',

                        ]);

                    }

                } catch (\Throwable $e) {

                    // Keep malformed legacy reservations untouched.

                }

            });

    }



}