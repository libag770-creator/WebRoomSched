<?php



namespace App\Http\Controllers;





use App\Models\Reservation;

use App\Models\Room;

use App\Models\Building;

use App\Models\Department;

use App\Models\User;

use App\Models\Schedule;

use App\Models\ScheduleTimeSlot;

use App\Models\RoomScheduleTimeSlot;

use App\Models\ScheduleMerge;
use App\Models\ScheduleCellMerge;

use App\Models\RoomSwapRequest;
use App\Models\ScheduleOverride;

use App\Models\FacultySubject;
use App\Models\Subject;

use Illuminate\Http\Request;

use App\Models\AcademicTerm;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    $today = $now->copy()->startOfDay();
    $weekdayName = strtolower($today->format('l'));
    $weekdayShort = strtolower($today->format('D'));

    $totalRooms = Room::count();
    $totalBuildings = Building::count();
    $totalDepartments = Department::count();
    $systemFacultyCount = User::query()
        ->whereRaw('LOWER(TRIM(role)) = ?', ['faculty'])
        ->count();

    // Older installs store weekly schedules as day/time only. Apply period
    // filtering and richer fields only when those columns exist in this DB.
    $scheduleColumns = Schema::getColumnListing((new Schedule())->getTable());
    $hasScheduleColumn = static fn (string $column): bool => in_array($column, $scheduleColumns, true);

    $currentSchedules = static function () use ($today, $hasScheduleColumn) {
        $query = Schedule::query();

        if ($hasScheduleColumn('schedule_start_date') && $hasScheduleColumn('schedule_expiration_date')) {
            $query->where(function ($period) use ($today) {
                $period
                    ->whereNull('schedule_start_date')
                    ->orWhereNull('schedule_expiration_date')
                    ->orWhere(function ($bounded) use ($today) {
                        $bounded
                            ->whereDate('schedule_start_date', '<=', $today)
                            ->whereDate('schedule_expiration_date', '>=', $today);
                    });
            });
        }

        return $query;
    };

    $forToday = static function ($query) use ($weekdayName, $weekdayShort, $hasScheduleColumn) {
        $dayColumns = array_values(array_filter(
            ['day', 'day_of_week'],
            $hasScheduleColumn
        ));

        if (!$dayColumns) {
            return;
        }

        $query->where(function ($dayQuery) use ($weekdayName, $weekdayShort, $dayColumns) {
            foreach ($dayColumns as $index => $column) {
                $method = $index === 0 ? 'whereRaw' : 'orWhereRaw';
                $dayQuery->{$method}("LOWER(`{$column}`) LIKE ?", ["%{$weekdayName}%"])
                    ->orWhereRaw("LOWER(`{$column}`) LIKE ?", ["%{$weekdayShort}%"]);
            }
        });
    };

    $systemActiveScheduleCount = $currentSchedules()->count();
    $systemTodayScheduleCount = $currentSchedules()->where($forToday)->count();
    $todaySchedules = $currentSchedules()->where($forToday);
    if ($hasScheduleColumn('start_time')) {
        $todaySchedules->orderBy('start_time');
    }
    if ($hasScheduleColumn('time')) {
        $todaySchedules->orderBy('time');
    }
    $todaySchedules = $todaySchedules->limit(8)->get();

    $faculty = Auth::user();
    $myCurrentSchedules = $currentSchedules()->where(function ($query) use ($faculty, $hasScheduleColumn) {
        $hasOwnerColumn = false;
        if ($hasScheduleColumn('faculty_id')) {
            $query->where('faculty_id', $faculty->id);
            $hasOwnerColumn = true;
        }

        if ($hasScheduleColumn('instructor') && !empty($faculty->name)) {
            $method = $hasOwnerColumn ? 'orWhereRaw' : 'whereRaw';
            $query->{$method}('LOWER(TRIM(instructor)) = ?', [mb_strtolower(trim($faculty->name))]);
            $hasOwnerColumn = true;
        }

        if (!$hasOwnerColumn) {
            $query->whereRaw('1 = 0');
        }
    });
    $weeklyScheduleCount = (clone $myCurrentSchedules)->count();
    $todayScheduleCount = (clone $myCurrentSchedules)->where($forToday)->count();
    $currentReservationsCount = Reservation::query()
        ->whereRaw('LOWER(TRIM(status)) = ?', ['reserved'])
        ->count();





    $allReservations = Reservation::with([

        'room',

        'room.building',

        'facultySubject',
        'subject.program',

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

            'totalRooms',

            'totalBuildings',

            'totalDepartments',

            'systemFacultyCount',

            'systemActiveScheduleCount',

            'systemTodayScheduleCount',

            'todaySchedules',

            'weeklyScheduleCount',

            'todayScheduleCount',

            'currentReservationsCount'

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
            'subject.program',

        ])

            ->where('user_id', Auth::id())

            ->latest()

            ->get();



        return view(

            'faculty.my-bookings',

            compact('allReservations')

        );

    }

    public function deleteReservation(Reservation $reservation)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        abort_unless((int) $reservation->user_id === (int) Auth::id(), 403);

        if ($reservation->status === 'Reserved') {
            return redirect()
                ->route('faculty.bookings')
                ->with('error', 'Cancel an active reservation before removing its history.');
        }

        $reservation->delete();

        return redirect()
            ->route('faculty.bookings')
            ->with('success', 'Reservation history removed.');
    }

    public function cancelReservation(Reservation $reservation)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        abort_unless((int) $reservation->user_id === (int) Auth::id(), 403);

        if ($reservation->status !== 'Reserved') {
            return redirect()
                ->route('faculty.bookings')
                ->with('error', 'Only active reservations can be cancelled.');
        }

        $reservation->update(['status' => 'Cancelled']);

        return redirect()
            ->route('faculty.bookings')
            ->with('success', 'Reservation cancelled. The room is available again, and the cancelled record remains in your history.');
    }



 public function reserveRoom(Request $request, Room $room)

{

    $this->expireReservations();



    /*

    |--------------------------------------------------------------------------

    | VALIDATE RESERVATION REQUEST

    |--------------------------------------------------------------------------

    |

    | The faculty chooses a subject from this college's Manage Subjects

    | catalog. The reservation time comes from the available room slot

    | selected on the Vacant Rooms page.

    |

    | schedule_id is optional because an available room does not need to have

    | an existing schedule row. subject_id points to the catalog entry,

    | whether or not it has been assigned to an instructor.

    |--------------------------------------------------------------------------

    */



   $request->validate([

    'date' => 'required|date',

    'time_slot_id' => 'required|string|max:120',

    'time' => 'nullable|string|max:50',

    'start_time' => 'nullable|string|max:20',

    'end_time' => 'nullable|string|max:20',

    'faculty_subject_id' => 'nullable|integer|exists:faculty_subjects,id',
    'subject_id' => 'nullable|integer|exists:subjects,id',

    'schedule_id' => 'nullable|integer|exists:schedules,id',

]);



    $faculty = Auth::user();



    if (!$faculty) {

        return redirect()->route('login');

    }

    // A room's owning college controls whether instructors from other
    // colleges may reserve it. Enforce this on submission as well as in the UI.
    if (
        (int) $room->department_id !== (int) $faculty->department_id
        && !$room->allow_other_departments
    ) {
        return back()->with(
            'error',
            'This college has not enabled room reservations from other colleges.'
        );
    }



    /*

    |--------------------------------------------------------------------------

    | SECURITY: SUBJECT MUST BELONG TO THIS FACULTY

    |--------------------------------------------------------------------------

    */



    $facultySubject = null;
    if ($request->filled('faculty_subject_id')) {
        $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])
            ->where('id', $request->faculty_subject_id)
            ->where('faculty_id', $faculty->id)
            ->first();
    }

    if ($request->filled('faculty_subject_id') && !$facultySubject) {

        return back()->with(

            'error',

            'The selected subject is not assigned to your instructor account.'

        );

    }

    $catalogSubject = null;
    if ($request->filled('subject_id')) {
        $catalogSubject = Subject::with('program')
            ->whereKey((int) $request->input('subject_id'))
            ->whereHas('program', fn ($query) => $query->where('department_id', $faculty->department_id))
            ->first();

        if (!$catalogSubject) {
            return back()->with('error', 'Choose a subject from your college’s Manage Subjects catalog.');
        }
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

    if ($date->lt(Carbon::today())) {
        return back()->with('error', 'Choose today or a future date for your reservation.');
    }

    $activeTermId = session('active_academic_term_id');
    $activeTerm = $activeTermId
        ? AcademicTerm::whereKey($activeTermId)->where('is_active', true)->first()
        : null;
    $activeTerm ??= AcademicTerm::where('is_active', true)->orderByDesc('id')->first();

    if (!$activeTerm) {
        return back()->with('error', 'There is no active academic term for room reservations.');
    }



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

        $timeParts = preg_split('/\s*-\s*/', $timeValue, 2);



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

    $fullDay = match ($day) {
        'MON' => 'MONDAY', 'TUE' => 'TUESDAY', 'WED' => 'WEDNESDAY',
        'THU' => 'THURSDAY', 'FRI' => 'FRIDAY', 'SAT' => 'SATURDAY',
        'SUN' => 'SUNDAY', default => $day,
    };

    $publishedRoomSlot = RoomScheduleTimeSlot::query()
        ->where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->where('is_active', true)
        ->whereIn(DB::raw('UPPER(TRIM(day))'), [$day, $fullDay])
        ->whereTime('start_time', $startTime)
        ->whereTime('end_time', $endTime)
        ->exists();

    if (!$publishedRoomSlot) {
        return back()->with('error', 'That room is not published for the selected day and time slot. Please search again.');
    }



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

    if ($date->isToday() && $requestedStart->lessThanOrEqualTo(Carbon::now())) {
        return back()->with('error', 'A room cannot be reserved for a time that has already started today.');
    }



    /*

    |--------------------------------------------------------------------------

    | PREVENT RESERVING OVER AN EXISTING CLASS

    |--------------------------------------------------------------------------

    */



    $roomSchedules = Schedule::where('room_id', $room->id)

        ->where('academic_term_id', $activeTerm->id)

        ->where(function ($query) use ($day) {

            $query->whereRaw('UPPER(TRIM(day)) = ?', [$day])

                ->orWhereRaw('UPPER(TRIM(day)) = ?', [

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

    | subject_id identifies the Manage Subjects catalog selection. The

    | instructor assignment is optional. schedule_id stays nullable because the room is being requested

    | precisely because that room slot is available.

    |--------------------------------------------------------------------------

    */



    $reservationResult = DB::transaction(function () use (
        $room, $date, $requestedStart, $requestedEnd, $faculty, $request,
        $facultySubject, $catalogSubject, $day, $reservationTime, $startTime, $endTime, $activeTerm
    ) {
        // Serialize attempts for the same room, then recheck before inserting.
        $lockedRoom = Room::whereKey($room->id)->lockForUpdate()->firstOrFail();
        if (
            (int) $lockedRoom->department_id !== (int) $faculty->department_id
            && !$lockedRoom->allow_other_departments
        ) {
            return 'permission_denied';
        }

        $activeForDate = Reservation::query()
            ->where('room_id', $room->id)
            ->whereDate('date', $date->format('Y-m-d'))
            ->where('status', 'Reserved')
            ->lockForUpdate()
            ->get();

        foreach ($activeForDate as $existing) {
            if (!$existing->start_time || !$existing->end_time) continue;
            try {
                $existingStart = Carbon::parse($date->format('Y-m-d') . ' ' . $existing->start_time);
                $existingEnd = Carbon::parse($date->format('Y-m-d') . ' ' . $existing->end_time);
            } catch (\Throwable $e) {
                continue;
            }
            if ($requestedStart->lt($existingEnd) && $requestedEnd->gt($existingStart)) return 'conflict';
        }

        Reservation::create([
            'room_id' => $room->id,
            'user_id' => $faculty->id,
            'schedule_id' => $request->filled('schedule_id') ? (int) $request->schedule_id : null,
            'academic_term_id' => $activeTerm->id,
            'faculty_subject_id' => $facultySubject?->id,
            'subject_id' => $catalogSubject?->id,
            'date' => $date->format('Y-m-d'),
            'day' => $day,
            'time' => $reservationTime,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => $catalogSubject
                ? trim($catalogSubject->course_code . ' — ' . $catalogSubject->course_title)
                : ($room->purpose ?? 'Room Reservation'),
            'status' => 'Reserved',
        ]);

        return false;
    });

    if ($reservationResult === 'permission_denied') {
        return back()->with('error', 'This college has not enabled room reservations from other colleges.');
    }

    if ($reservationResult === 'conflict') {
        return back()->with('error', 'This room and time were just reserved by another instructor. Please choose another room or date.');
    }

    $startClock = $startTime instanceof Carbon ? $startTime->format('H:i') : Carbon::parse($startTime)->format('H:i');
    $endClock = $endTime instanceof Carbon ? $endTime->format('H:i') : Carbon::parse($endTime)->format('H:i');
    $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);
    $reservationSchedules = collect();
    if ($facultySubject || $catalogSubject) {
        $reservationSchedules = Schedule::with('room')
            ->where('academic_term_id', $activeTerm->id)
            ->when($request->filled('schedule_id'), fn ($query) => $query->whereKey((int) $request->schedule_id))
            ->when($facultySubject, fn ($query) => $query->where('faculty_subject_id', $facultySubject->id))
            ->when(!$facultySubject && $catalogSubject, fn ($query) => $query->whereHas('facultySubject', fn ($assignment) => $assignment
                ->where('faculty_id', $faculty->id)
                ->where('subject_id', $catalogSubject->id)))
            ->where('faculty_id', $faculty->id)
            ->get()
            ->filter(fn (Schedule $scheduledClass) => $studentUpdates->overlapsScheduledMeeting(
                $scheduledClass,
                $date->format('Y-m-d'),
                $startClock,
                $endClock
            ));
    }

    foreach ($reservationSchedules as $scheduledClass) {
        $studentUpdates->recordForSchedule(
            $scheduledClass,
            'dated_room_reservation',
            'One-day room assignment',
            trim(($scheduledClass->course_code ?: 'Class') . ' — ' . ($scheduledClass->subject ?: ''))
                . ' has a room reservation in ' . ($room->room_name ?: 'another room')
                . ' on ' . $date->format('F j, Y') . ' from ' . $startClock . ' to ' . $endClock . '.',
            [
                'room_change' => [
                    'type' => 'reservation',
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $startClock,
                    'end_time' => $endClock,
                    'scheduled_room' => $studentUpdates->roomNameFor($scheduledClass),
                    'new_room' => $room->room_name,
                ],
            ],
            Carbon::parse($date->format('Y-m-d') . ' ' . $startClock),
            Carbon::parse($date->format('Y-m-d') . ' ' . $endClock)
        );
    }



  return redirect()

    ->route('faculty.vacant', [

        'day' => $day,

        'time_slot_id' => $request->input('time_slot_id'),

        'date' => $date->format('Y-m-d'),

    ])

    ->with(

        'success',

        'Room reserved successfully. The room is reserved for the selected date and time.'

    );

}



public function vacantRooms(Request $request)

{

    $this->expireReservations();

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



    if ($activeTerm) {

        session(['active_academic_term_id' => $activeTerm->id]);

    } else {

        session()->forget('active_academic_term_id');

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

    try {
        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->input('date'))->startOfDay()
            : Carbon::today();
    } catch (\Throwable $e) {
        $selectedDate = Carbon::today();
    }

    $selectedDay = $normalizeDay($request->input('day', ''));
    if ($selectedDay === '') {
        $selectedDay = $normalizeDay($selectedDate->format('D'));
    }

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

        $legacyParts = preg_split('/\s*-\s*/', $legacyTime, 2);



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

    if (!$request->filled('date') && $selectedDay !== '') {
        while ($normalizeDay($selectedDate->format('D')) !== $selectedDay) {
            $selectedDate->addDay();
        }
    }

    if ($selectedTimeSlot && $activeTerm) {

        $day = $normalizeDay($selectedTimeSlot->day);

        $slotStartValue = $clock($selectedTimeSlot->start_time);

        $slotEndValue = $clock($selectedTimeSlot->end_time);



        if (
            $slotStartValue && $slotEndValue
            && $normalizeDay($selectedDate->format('D')) === $normalizeDay($selectedTimeSlot->day)
        ) {

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

                $reservationDate = $selectedDate->format('Y-m-d');



                if ($request->filled('date')) {

                    try {

                        $parsedDate = Carbon::parse($request->input('date'));



                        if (strtoupper($parsedDate->format('D')) === $day) {

                            $reservationDate = $parsedDate->format('Y-m-d');

                        } else {

                            $reservationDate = null;

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

                            '/\s*-\s*/',

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
                    $overlappingReservations = collect();



                    if ($reservationDate !== null) {

                        $reservations = Reservation::with(['user', 'facultySubject', 'subject.program'])
                            ->where('room_id', $room->id)

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
                                $overlappingReservations->push($reservation);

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

                        'start_time' => $slotStart->format('H:i'),

                        'end_time' => $slotEnd->format('H:i'),

                        'time_slot_id' => $selectedTimeSlot->id,

                        'reservation_date' => $reservationDate,

                        'reserved_by_me' => $overlappingReservations->contains(
                            fn ($reservation) => (int) $reservation->user_id === (int) Auth::id()
                        ),

                        'reservations' => $overlappingReservations->map(function ($reservation) {
                            return [
                                'faculty' => $reservation->user?->name ?? 'Instructor',
                                'purpose' => $reservation->subject
                                    ? trim(($reservation->subject->course_code ?? '') . ' - ' . ($reservation->subject->course_title ?? ''))
                                    : ($reservation->facultySubject
                                        ? trim(($reservation->facultySubject->course_code ?? '') . ' - ' . ($reservation->facultySubject->subject ?? ''))
                                        : ($reservation->purpose ?? 'Room reservation')),
                                'date' => $reservation->date
                                    ? Carbon::parse($reservation->date)->format('Y-m-d')
                                    : null,
                                'time' => $reservation->time,
                            ];
                        })->values()->all(),

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

    // Use the department's Manage Subjects catalog as the source, whether or
    // not a catalog subject has been assigned to an instructor yet.
    $manageSubjectOptions = Subject::with('program:id,department_id,name,code,major')
        ->whereHas('program', fn ($query) => $query->where('department_id', $loggedInFaculty?->department_id))
        ->orderBy('course_code')
        ->orderBy('course_title')
        ->get(['id', 'program_id', 'year_level', 'course_code', 'course_title', 'major'])
        ->map(fn (Subject $subject) => [
            'id' => $subject->id,
            'course_code' => $subject->course_code,
            'course_title' => $subject->course_title,
            'program' => $subject->program?->name,
            'program_code' => $subject->program?->code,
            'major' => $subject->major ?: $subject->program?->major,
            'year_level' => $subject->year_level,
        ])
        ->values();



    return view(

        'faculty.vacant-rooms',

        compact(

            'rooms',

            'results',

            'activeTerm',

            'timeSlots',

            'selectedDay',

            'selectedDate',

            'selectedTimeSlotId',

            'manageSubjectOptions'

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



        $activeTerm = AcademicTerm::whereKey($activeTermId)
            ->where('is_active', true)
            ->first();



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

    if ($activeTerm) {
        session(['active_academic_term_id' => $activeTerm->id]);
    } else {
        session()->forget('active_academic_term_id');
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



            $query->where('academic_term_id', $activeTerm?->id ?? 0);



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

                    ?? 'College',



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
    $this->expireRoomSwaps();



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

        \App\Models\AcademicTerm::whereKey($activeTermId)->where('is_active', true)->first();



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



if ($activeTerm) {
    session(['active_academic_term_id' => $activeTerm->id]);
} else {
    session()->forget('active_academic_term_id');
}

$schedulesQuery = Schedule::where('room_id', $room->id)
    ->where('academic_term_id', $activeTerm?->id ?? 0);





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
        'subject.program.department',
        'facultySubject.subjectCatalog.program.department',

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
    $mergedCourses = [];



    foreach ($merges as $merge) {

        $catalogSubject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
        if ($catalogSubject) {
            $scheduleId = $merge->schedule_id;
            $programName = $catalogSubject->program?->code
                ?? $merge->facultySubject?->subjectCatalog?->program?->code
                ?? $merge->facultySubject?->program;
            $mergedCourses[$scheduleId] ??= [];
            $mergedCourses[$scheduleId][] = [
                'course_code' => $catalogSubject->course_code ?? $merge->facultySubject?->course_code,
                'course_title' => $catalogSubject->course_title ?? $merge->facultySubject?->subject,
                'program' => $programName,
                'major' => $catalogSubject->program?->major ?? $merge->facultySubject?->major,
                'share_type' => $merge->share_type ?? 'merge',
            ];
            if ($programName) {
                $mergedPrograms[$scheduleId] ??= [];
                if (!in_array($programName, $mergedPrograms[$scheduleId], true)) {
                    $mergedPrograms[$scheduleId][] = $programName;
                }
            }
        }



        if (!$merge->facultySubject) {

            continue;

        }



        $scheduleId =

            $merge->schedule_id;



        $program = trim((string) (
            $merge->facultySubject->subjectCatalog?->program?->code
            ?? $merge->facultySubject->program
        ));



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
        ->where('swap_type', 'temporary')
        ->where(function ($query) use ($room) {

            $query
                ->where('requester_room_id', $room->id)
                ->orWhere('target_room_id', $room->id);

        })
        ->whereHas('requesterSchedule', function ($query) use ($activeTerm) {
            $query->where('academic_term_id', $activeTerm?->id ?? 0);
        })
        ->whereHas('targetSchedule', function ($query) use ($activeTerm) {
            $query->where('academic_term_id', $activeTerm?->id ?? 0);
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
                // Keep approved upcoming swaps visible with their date/time;
                // the front end labels them as scheduled until they begin.
                return $end->greaterThan($now);

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
            'active' => $now->greaterThanOrEqualTo(Carbon::parse(
                $swap->getRawOriginal('swap_date') . ' ' . $swap->getRawOriginal('start_time')
            )) && $now->lessThan(Carbon::parse(
                $swap->getRawOriginal('swap_date') . ' ' . $swap->getRawOriginal('end_time')
            )),

            'swap_type' => 'temporary',

            'scheduled' => $now->lessThan(Carbon::parse(
                $swap->getRawOriginal('swap_date') . ' ' . $swap->getRawOriginal('start_time')
            )),

            'swap_id' => $swap->id,

            'swap_date' => $swap->swap_date
                ? Carbon::parse($swap->swap_date)->format('Y-m-d')
                : null,

            'start_at' => Carbon::parse(
                $swap->getRawOriginal('swap_date') . ' ' . $swap->getRawOriginal('start_time')
            )->format('Y-m-d\\TH:i:s'),

            'end_at' => Carbon::parse(
                $swap->getRawOriginal('swap_date') . ' ' . $swap->getRawOriginal('end_time')
            )->format('Y-m-d\\TH:i:s'),

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

            $leavingSchedule = $swap->requesterSchedule;

            $swappedSchedule = $swap->targetSchedule;

            if ($leavingSchedule) {
                $schedules = $schedules
                    ->reject(function ($schedule) use ($leavingSchedule) {
                        return (int) $schedule->id === (int) $leavingSchedule->id;
                    })
                    ->values();
            }

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

            $leavingSchedule = $swap->targetSchedule;

            $swappedSchedule = $swap->requesterSchedule;

            if ($leavingSchedule) {
                $schedules = $schedules
                    ->reject(function ($schedule) use ($leavingSchedule) {
                        return (int) $schedule->id === (int) $leavingSchedule->id;
                    })
                    ->values();
            }

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

    | LOAD CURRENT AND UPCOMING ROOM RESERVATIONS

    |--------------------------------------------------------------------------

    |

    | Reserved:

    |   Shows as "Temporary Reservation".

    |

    | Both automatically disappear from the timetable after

    | their individual end time. The original weekly schedule is never

    | modified by the reservation.

    |

    */



    $today =

        Carbon::today();



    $temporaryReservations =

        Reservation::with([

            'user',
            'facultySubject',
            'subject.program',

        ])

        ->where(

            'room_id',

            $room->id

        )

        ->where('academic_term_id', $activeTerm?->id ?? 0)

        ->whereDate('date', '>=', $today->format('Y-m-d'))

        ->whereRaw('LOWER(TRIM(status)) = ?', ['reserved'])

        ->get()

        // expireReservations() ran at the start of this endpoint. Return all
        // remaining current/future reservations without a second Carbon
        // timestamp filter that can disagree with the app/database timezone.
        ->filter(fn ($reservation) => $reservation->start_time && $reservation->end_time)

        ->values();

    $overrideCandidates = ScheduleOverride::query()
        ->where(function ($query) use ($schedules, $room) {
            $query->whereIn('schedule_id', $schedules->pluck('id')->unique())
                ->orWhere('room_id', $room->id);
        })
        ->where('status', 'active')
        ->whereDate('override_date', '>=', Carbon::today()->toDateString())
        ->where('expires_at', '>', now())
        ->orderBy('override_date')
        ->orderBy('start_time')
        ->get()
        ;

    $parseOverrideMinute = static function ($value): ?int {
        if ($value instanceof \DateTimeInterface) {
            return ((int) $value->format('H') * 60) + (int) $value->format('i');
        }

        $raw = trim((string) $value);
        if ($raw === '') return null;

        try {
            $time = Carbon::parse(str_contains($raw, 'T') ? $raw : '2000-01-01 ' . $raw);
            return ($time->hour * 60) + $time->minute;
        } catch (\Throwable $exception) {
            return null;
        }
    };

    $scheduleOverridesBySchedule = [];
    foreach ($schedules as $schedule) {
        $scheduleDay = strtoupper(substr(trim((string) ($schedule->day_of_week ?: $schedule->day)), 0, 3));
        $scheduleStart = $parseOverrideMinute($schedule->start_time);
        $scheduleEnd = $parseOverrideMinute($schedule->end_time);
        if (($scheduleStart === null || $scheduleEnd === null) && preg_match('/^\s*(.+?)\s*(?:-|–|—|to)\s*(.+?)\s*$/i', (string) $schedule->time, $timeParts)) {
            $scheduleStart = $parseOverrideMinute($timeParts[1]);
            $scheduleEnd = $parseOverrideMinute($timeParts[2]);
        }

        $scheduleOverridesBySchedule[$schedule->id] = $overrideCandidates
            ->filter(function (ScheduleOverride $override) use ($schedule, $room, $scheduleDay, $scheduleStart, $scheduleEnd, $parseOverrideMinute) {
                $directScheduleMatch = (int) $override->schedule_id === (int) $schedule->id;
                if ($override->room_id && (int) $override->room_id !== (int) $room->id) return false;
                if (!$directScheduleMatch && (int) $override->room_id !== (int) $room->id) return false;
                if (!$override->override_date) return false;
                if (strtoupper($override->override_date->format('D')) !== $scheduleDay) return false;

                $overrideStart = $parseOverrideMinute($override->start_time);
                $overrideEnd = $parseOverrideMinute($override->end_time);
                if ($scheduleStart === null || $scheduleEnd === null || $overrideStart === null || $overrideEnd === null) return false;

                return $scheduleStart < $overrideEnd && $scheduleEnd > $overrideStart;
            })
            ->values();
    }





    /*

    |--------------------------------------------------------------------------

    | FORMAT SCHEDULE DATA

    |--------------------------------------------------------------------------

    */


    $schedules->loadMissing('facultySubject.subjectCatalog.program');
    $programCodesByName = \App\Models\Program::query()
        ->whereIn('name', $schedules->pluck('program')->filter()->unique()->values())
        ->pluck('code', 'name')
        ->all();
    $programCodeForSchedule = static function ($schedule) use ($programCodesByName) {
        $code = trim((string) ($schedule->facultySubject?->subjectCatalog?->program?->code ?? ''));
        if ($code !== '') return $code;
        $label = trim((string) ($schedule->program ?? ''));
        if (preg_match('/\(([^()]*)\)\s*$/u', $label, $matches) === 1) return trim($matches[1]);
        return trim((string) ($programCodesByName[$label] ?? $label));
    };

    $scheduleData =

        $schedules->map(

            function ($schedule)

            use (
                $mergedPrograms,
                $mergedCourses,
                $roomSwapsBySchedule,
                $scheduleOverridesBySchedule,
                $programCodeForSchedule
            ) {

                $roomSwap =
                    $roomSwapsBySchedule[
                        $schedule->id
                    ] ?? null;

                $scheduleOverrides = ($scheduleOverridesBySchedule[$schedule->id] ?? collect())
                    ->map(function (ScheduleOverride $override) use ($room) {
                        return [
                            'id' => (int) $override->id,
                            'room_id' => (int) ($override->room_id ?: $room->id),
                            'day' => strtoupper($override->override_date?->format('D') ?? ''),
                            'date' => $override->override_date?->format('Y-m-d'),
                            'start_time' => $override->start_time ? Carbon::parse($override->start_time)->format('H:i') : null,
                            'end_time' => $override->end_time ? Carbon::parse($override->end_time)->format('H:i') : null,
                            'occupied_by' => $override->occupied_by,
                            'reason' => $override->reason,
                            'details' => $override->details,
                            'state' => $override->override_date?->isToday() ? 'today' : 'scheduled',
                        ];
                    })
                    ->values();


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

                    'program_code' => $programCodeForSchedule($schedule),

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

                    'merged_courses' =>
                        $mergedCourses[
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
                        (bool) ($roomSwap['active'] ?? false),

                    'schedule_overrides' => $scheduleOverrides,

                ];

            }

        )

        ->values();




    /*

    |--------------------------------------------------------------------------

    | FORMAT TEMPORARY RESERVATIONS

    |--------------------------------------------------------------------------

    */



    $roomOverrideData = ScheduleOverride::query()
        ->where('room_id', $room->id)
        ->whereNull('schedule_id')
        ->where('status', 'active')
        ->whereDate('override_date', '>=', Carbon::today()->toDateString())
        ->where('expires_at', '>', now())
        ->orderBy('override_date')
        ->orderBy('start_time')
        ->get()
        ->map(function (ScheduleOverride $override) {
            return [
                'id' => (int) $override->id,
                'room_id' => (int) $override->room_id,
                'day' => strtoupper($override->override_date?->format('D') ?? ''),
                'date' => $override->override_date?->format('Y-m-d'),
                'start_time' => $override->start_time ? Carbon::parse($override->start_time)->format('H:i') : null,
                'end_time' => $override->end_time ? Carbon::parse($override->end_time)->format('H:i') : null,
                'reason' => $override->reason,
                'occupied_by' => $override->occupied_by,
                'details' => $override->details,
            ];
        })
        ->values();

    $roomTimeSlotRows = RoomScheduleTimeSlot::query()
        ->where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm?->id ?? 0)
        ->where('is_active', true)
        ->get();

    $manualMerges = $activeTerm
        ? ScheduleCellMerge::where('room_id', $room->id)
            ->where('academic_term_id', $activeTerm->id)
            ->orderBy('id')
            ->get(['axis', 'cells'])
            ->map(fn ($merge) => [
                'axis' => $merge->axis,
                'cells' => $merge->cells,
            ])
            ->values()
        : collect();

    $reservationData =

        $temporaryReservations

            ->map(

                function ($reservation) use ($roomTimeSlotRows) {



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

                    $reservationDay = strtoupper(trim((string) ($reservation->day ?? Carbon::parse($reservation->date)->format('D'))));
                    $reservationDay = substr($reservationDay, 0, 3);
                    $roomTimeSlot = $roomTimeSlotRows->first(function ($slot) use ($reservationDay, $startTime, $endTime) {
                        return substr(strtoupper(trim((string) $slot->day)), 0, 3) === $reservationDay
                            && Carbon::parse($slot->start_time)->format('H:i') === $startTime
                            && Carbon::parse($slot->end_time)->format('H:i') === $endTime;
                    });



                    return [



                        'id' =>

                            $reservation->id,

                        'user_id' => $reservation->user_id,



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

                        'row_position' => $roomTimeSlot?->row_position,



                        'purpose' =>

                            $reservation->purpose

                            ?? 'Room Reservation',

                        'course_code' => $reservation->subject?->course_code ?? $reservation->facultySubject?->course_code,

                        'subject' => $reservation->subject?->course_title ?? $reservation->facultySubject?->subject,



                        'user_name' =>

                            $reservation->user?->name

                            ?? 'Instructor',



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

                ?? 'College',

        ],



        'schedules' =>

            $scheduleData,



        'reservations' =>

            $reservationData,

        'room_overrides' =>

            $roomOverrideData,

        'room_time_slots' => $roomTimeSlotRows
            ->sortBy([['row_position', 'asc'], ['start_time', 'asc']])
            ->map(function ($slot) {
                return [
                    'id' => (int) $slot->id,
                    'day' => strtoupper(trim((string) $slot->day)),
                    'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                    'time' => Carbon::parse($slot->start_time)->format('g:i')
                        . '-'
                        . Carbon::parse($slot->end_time)->format('g:i'),
                    'row_position' => $slot->row_position !== null ? (int) $slot->row_position : null,
                ];
            })
            ->values(),

        'manual_merges' => $manualMerges,



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
    $this->expireRoomSwaps();



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
        'facultySubject.subjectCatalog.program',
        'merges' => function ($mergeQuery) {
            $mergeQuery->where('status', 'approved')->with([
                'subject.program',
                'facultySubject.subjectCatalog.program',
            ]);
        },
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



    $query->where('academic_term_id', $activeTerm?->id ?? 0);



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

    $programCodesByName = \App\Models\Program::query()
        ->whereIn('name', $mySchedules->pluck('program')->filter()->unique()->values())
        ->pluck('code', 'name')
        ->all();
    $mySchedules->each(function ($schedule) use ($programCodesByName) {
        $code = trim((string) ($schedule->facultySubject?->subjectCatalog?->program?->code ?? ''));
        $label = trim((string) ($schedule->program ?? ''));
        if ($code === '' && preg_match('/\(([^()]*)\)\s*$/u', $label, $matches) === 1) {
            $code = trim($matches[1]);
        }
        $schedule->setAttribute('program_code', $code ?: trim((string) ($programCodesByName[$label] ?? $label)));
    });



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



    $temporaryReservations = Reservation::with([
        'room.building',
        'facultySubject',
        'facultySubject.subjectCatalog.program',
        'subject.program',
    ])
        ->where('user_id', $faculty->id)
        ->where('academic_term_id', $activeTerm?->id ?? 0)
        ->where('status', 'Reserved')
        ->whereDate('date', '>=', Carbon::today()->toDateString())
        ->orderBy('date')
        ->orderBy('start_time')
        ->get();

    $roomSwapRequests = RoomSwapRequest::with([
        'requester', 'targetUser', 'requesterRoom', 'targetRoom',
        'requesterRoom.building', 'targetRoom.building',
        'requesterSchedule', 'targetSchedule',
    ])
        ->whereRaw('LOWER(TRIM(status)) = ?', ['approved'])
        ->where('swap_type', 'temporary')
        ->whereDate('swap_date', '>=', Carbon::today()->toDateString())
        ->where(function ($query) use ($faculty) {
            $query->where('requester_id', $faculty->id)
                ->orWhere('target_user_id', $faculty->id);
        })
        ->whereHas('requesterSchedule', function ($query) use ($activeTerm) {
            $query->where('academic_term_id', $activeTerm?->id ?? 0);
        })
        ->whereHas('targetSchedule', function ($query) use ($activeTerm) {
            $query->where('academic_term_id', $activeTerm?->id ?? 0);
        })
        ->orderBy('swap_date')
        ->orderBy('start_time')
        ->get();

    $myScheduleIds = $mySchedules->pluck('id')->unique();
    $myRoomIds = $mySchedules->pluck('room_id')->filter()->unique();
    $scheduleOverrideCandidates = ScheduleOverride::with(['schedule', 'room'])
        ->where('status', 'active')
        ->whereDate('override_date', '>=', Carbon::today()->toDateString())
        ->where('expires_at', '>', now())
        ->where(function ($query) use ($myScheduleIds, $myRoomIds, $faculty) {
            $query->where(function ($legacy) use ($myScheduleIds, $faculty) {
                $legacy->whereIn('schedule_id', $myScheduleIds)->where('faculty_id', $faculty->id);
            })->orWhereIn('room_id', $myRoomIds);
        })
        ->orderBy('override_date')
        ->orderBy('start_time')
        ->get();

    $parseMinutes = function ($value) {
        if ($value instanceof \DateTimeInterface) {
            return ((int) $value->format('H') * 60) + (int) $value->format('i');
        }
        $raw = trim((string) $value);
        if (!preg_match('/^(\d{1,2})(?::(\d{2}))?(?::\d{2})?\s*(AM|PM)?$/i', $raw, $match)) return null;
        $hour = (int) $match[1];
        $minute = isset($match[2]) && $match[2] !== '' ? (int) $match[2] : 0;
        $period = strtoupper($match[3] ?? '');
        if ($period === 'AM' && $hour === 12) $hour = 0;
        if ($period === 'PM' && $hour !== 12) $hour += 12;
        return ($hour * 60) + $minute;
    };

    $scheduleOverridesByScheduleId = collect();
    foreach ($mySchedules as $mySchedule) {
        $applicable = $scheduleOverrideCandidates->filter(function (ScheduleOverride $override) use ($mySchedule, $faculty, $parseMinutes) {
            if ((int) $override->schedule_id === (int) $mySchedule->id && (int) $override->faculty_id === (int) $faculty->id) {
                return true;
            }

            if (!$override->room_id || (int) $override->room_id !== (int) $mySchedule->room_id || !$override->override_date) {
                return false;
            }

            $scheduleDay = strtoupper(substr(trim((string) ($mySchedule->day_of_week ?: $mySchedule->day)), 0, 3));
            if ($scheduleDay !== strtoupper($override->override_date->format('D'))) {
                return false;
            }

            $scheduleRange = null;
            if ($mySchedule->start_time && $mySchedule->end_time) {
                $scheduleRange = [$parseMinutes($mySchedule->start_time), $parseMinutes($mySchedule->end_time)];
            } elseif (preg_match('/^\s*(.+?)\s*(?:-|–|—|to)\s*(.+?)\s*$/i', (string) $mySchedule->time, $parts)) {
                $scheduleRange = [$parseMinutes($parts[1]), $parseMinutes($parts[2])];
            }

            $overrideRange = [$parseMinutes($override->start_time), $parseMinutes($override->end_time)];
            if (!$scheduleRange || in_array(null, $scheduleRange, true) || in_array(null, $overrideRange, true)) {
                return false;
            }

            return $scheduleRange[0] < $overrideRange[1] && $scheduleRange[1] > $overrideRange[0];
        })->values();

        $scheduleOverridesByScheduleId->put((int) $mySchedule->id, $applicable);
    }

    $facultyScheduleOverrides = $scheduleOverridesByScheduleId
        ->flatten(1)
        ->unique('id')
        ->sortBy(fn (ScheduleOverride $override) => ($override->override_date?->format('Y-m-d') ?? '') . ' ' . ($override->start_time ?? ''))
        ->values();

    return view(

        'faculty.myschedules',

        compact(

            'mySchedules',

            'activeTerm',

            'timeSlots',

            'temporaryReservations',

            'roomSwapRequests',

            'scheduleOverridesByScheduleId',

            'facultyScheduleOverrides'

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

    private function expireRoomSwaps(): void
    {
        RoomSwapRequest::expireElapsedTemporarySwaps();
    }



}
