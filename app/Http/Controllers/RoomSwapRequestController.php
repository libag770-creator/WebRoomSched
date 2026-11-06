<?php



namespace App\Http\Controllers;



use App\Models\AcademicTerm;

use App\Models\Department;

use App\Models\RoomSwapRequest;

use App\Models\Schedule;
use App\Models\ScheduleOverride;

use App\Models\User;
use App\Notifications\RoomSwapNotification;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;



class RoomSwapRequestController extends Controller

{

    /**

     * Display room swap schedules from the currently published academic term.

     * The term resolver prefers the database's active term so a newly activated

     * term is reflected when the user opens this page again.

     */

    public function create(Request $request)

    {

        abort_unless(Auth::check(), 403, 'Unauthorized.');



        $user = Auth::user();

        // Restore any approved swaps whose scheduled end time has already passed.
        // This is also a safety net when the Laravel scheduler has not run yet.
        $this->cleanupExpiredSwaps();

        $activeTerm = $this->resolveAcademicTerm($request);



        // The faculty member's own schedules must come from the current term.

        $mySchedulesQuery = Schedule::with(['room.department', 'room.building'])

            ->where('instructor', $user->name);



        if ($activeTerm) {

            $this->applyAcademicTermFilter($mySchedulesQuery, $activeTerm);

        }



        $mySchedules = $mySchedulesQuery

            ->orderBy('day')

            ->orderBy('time')

            ->get();



        // Filter every room's schedule list too; otherwise the page can still

        // show schedules from a previous semester in the target schedule picker.

        $scheduleTermFilter = function ($query) use ($activeTerm) {

            if ($activeTerm) {

                $this->applyAcademicTermFilter($query, $activeTerm);

            } else {

                // No academic term configured: do not mix all historical terms.

                $query->whereRaw('1 = 0');

            }

        };



       $departments = Department::with([
    'buildings.rooms',
    'buildings.rooms.schedules' => $scheduleTermFilter,
    'buildings.rooms.schedules.merges.facultySubject',
])->orderBy('name')->get();

/*
|--------------------------------------------------------------------------
| ALL TARGET SCHEDULES FOR ROOM SWAP
|--------------------------------------------------------------------------
| Load the schedules separately so Step 2 does not depend on the nested
| Department -> Building -> Room relationship structure.
|
| This includes schedules from:
| - other departments
| - other buildings
| - other rooms
| - other faculty
| - the active academic term only
|--------------------------------------------------------------------------
*/

$targetSchedulesQuery = Schedule::with([
    'room.department',
    'room.building',
    'merges.facultySubject',
]);

if ($activeTerm) {
    $this->applyAcademicTermFilter(
        $targetSchedulesQuery,
        $activeTerm
    );
} else {
    $targetSchedulesQuery->whereRaw('1 = 0');
}

$targetSchedules = $targetSchedulesQuery
    ->orderBy('day')
    ->orderBy('time')
    ->get();

        // Show current and upcoming admin room overrides and use them in the
        // date-specific room availability check on the faculty page.
        $scheduleOverridesQuery = ScheduleOverride::with([
            'schedule.room.buildingRelation',
            'schedule.room.department',
            'faculty',
        ])
            ->where('status', 'active')
            ->whereDate('override_date', '>=', today()->toDateString())
            ->where('expires_at', '>', now());

        if ($activeTerm) {
            $scheduleOverridesQuery->whereHas('schedule', function ($query) use ($activeTerm) {
                $this->applyAcademicTermFilter($query, $activeTerm);
            });
        } else {
            $scheduleOverridesQuery->whereRaw('1 = 0');
        }

        $scheduleOverrides = $scheduleOverridesQuery
            ->orderBy('override_date')
            ->orderBy('created_at')
            ->get();

        $scheduleOverrideData = $scheduleOverrides->map(function ($override) {
            $schedule = $override->schedule;
            $room = $schedule?->room;
            $building = $room?->getRelation('buildingRelation');

            return [
                'schedule_id' => (int) $override->schedule_id,
                'room_id' => (int) ($schedule?->room_id ?? 0),
                'date' => $override->override_date?->format('Y-m-d'),
                'occupied_by' => $override->occupied_by,
                'reason' => $override->reason,
                'faculty' => $override->faculty?->name,
                'course' => $schedule?->course_code ?: ($schedule?->subject ?: 'Class schedule'),
                'day' => $schedule?->day,
                'time' => $schedule?->time,
                'room' => $room?->room_name ?: ($room?->name ?: 'Room'),
                // Room has a legacy text column named `building`; use the
                // explicit relationship name to avoid treating that string
                // as a Building model.
                'building' => $building?->name ?: ($room?->building ?: null),
                'department' => $room?->department?->name,
            ];
        })->values();


        // Schedule rows store the instructor's name, so map it to users.id.

        $facultyIdsByName = User::query()

            ->get(['id', 'name'])

            ->mapWithKeys(fn ($faculty) => [mb_strtolower(trim((string) $faculty->name)) => $faculty->id]);



        // Decorate both selected schedules with their active swap state so the UI

        // can clearly mark pending requests in orange and completed swaps in green.

        $activeSwapsQuery = RoomSwapRequest::with(['requester', 'targetUser'])

            ->whereIn('status', ['pending', 'approved']);



        if ($activeTerm) {

            $activeSwapsQuery

                ->whereHas('requesterSchedule', function ($query) use ($activeTerm) {

                    $this->applyAcademicTermFilter($query, $activeTerm);

                })

                ->whereHas('targetSchedule', function ($query) use ($activeTerm) {

                    $this->applyAcademicTermFilter($query, $activeTerm);

                });

        } else {

            $activeSwapsQuery->whereRaw('1 = 0');

        }



        $activeSwaps = $activeSwapsQuery->latest()->get();

        $swapByScheduleId = [];



        foreach ($activeSwaps as $swap) {

            $isApproved = $swap->status === 'approved';

            foreach ([

                (int) $swap->requester_schedule_id => $swap->targetUser?->name ?? 'Another faculty',

                (int) $swap->target_schedule_id => $swap->requester?->name ?? 'Another faculty',

            ] as $scheduleId => $partnerName) {

                // Keep the most recent active state if legacy duplicate records exist.

                if (!isset($swapByScheduleId[$scheduleId]) || $isApproved) {

                    $swapByScheduleId[$scheduleId] = [

                        'is_swapped' => $isApproved,

                        'is_swap_pending' => !$isApproved,

                        'swap_status' => $swap->status,

                        'swap_request_id' => $swap->id,

                        'swap_partner_faculty' => $partnerName,

                    ];

                }

            }

        }



        $decorate = function ($schedule) use ($facultyIdsByName, $swapByScheduleId) {

            $nameKey = mb_strtolower(trim((string) ($schedule->instructor ?? '')));

            $swapState = $swapByScheduleId[(int) $schedule->id] ?? [];

            $schedule->setAttribute('faculty_id', $facultyIdsByName[$nameKey] ?? null);

            $schedule->setAttribute('is_swapped', (bool) ($swapState['is_swapped'] ?? false));

            $schedule->setAttribute('is_swap_pending', (bool) ($swapState['is_swap_pending'] ?? false));

            $schedule->setAttribute('swap_status', $swapState['swap_status'] ?? null);

            $schedule->setAttribute('swap_request_id', $swapState['swap_request_id'] ?? null);

            $schedule->setAttribute('swap_partner_faculty', $swapState['swap_partner_faculty'] ?? null);

        };



       $mySchedules->each($decorate);

foreach ($departments as $department) {
    foreach ($department->buildings as $building) {
        foreach ($building->rooms as $room) {
            foreach ($room->schedules as $schedule) {
                $decorate($schedule);
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| DECORATE FLAT TARGET SCHEDULE LIST
|--------------------------------------------------------------------------
*/

$targetSchedules->each($decorate);


/*
|--------------------------------------------------------------------------
| SEND DATA TO ROOM SWAP PAGE
|--------------------------------------------------------------------------
*/

return view(
    'faculty.room-swap',
    compact(
        'mySchedules',
        'departments',
        'targetSchedules',
        'scheduleOverrides',
        'scheduleOverrideData',
        'activeTerm'
    )
);

    }



    /** Create a pending request for the selected faculty member to accept or decline. */

    public function store(Request $request)

    {

        abort_unless(Auth::check(), 403, 'Unauthorized.');

        $user = Auth::user();

        // Do not let an expired swap keep a schedule locked.
        $this->cleanupExpiredSwaps();



        $validated = $request->validate([

            'requester_schedule_id' => ['required', 'integer', 'exists:schedules,id'],

            'target_schedule_id' => ['required', 'integer', 'exists:schedules,id', 'different:requester_schedule_id'],

            'requester_room_id' => ['required', 'integer', 'exists:rooms,id'],

            'target_room_id' => ['required', 'integer', 'exists:rooms,id', 'different:requester_room_id'],

            'target_user_id' => ['required', 'integer', 'exists:users,id'],

            'swap_date' => ['required', 'date', 'after_or_equal:today'],

            'start_time' => ['required', 'string', 'max:50'],

            'end_time' => ['required', 'string', 'max:50'],

            'reason' => ['required', 'string', 'max:255'],

        ]);



        $activeTerm = $this->resolveAcademicTerm($request);



        $requesterScheduleQuery = Schedule::whereKey($validated['requester_schedule_id'])

            ->where('room_id', $validated['requester_room_id'])

            ->where('instructor', $user->name);



        if ($activeTerm) {

            $this->applyAcademicTermFilter($requesterScheduleQuery, $activeTerm);

        }



        $requesterSchedule = $requesterScheduleQuery->first();



        if (!$requesterSchedule) {

            return back()->withInput()->with('error', 'The selected schedule does not belong to you or its room has changed.');

        }



     $targetScheduleQuery = Schedule::whereKey($validated['target_schedule_id'])
    ->where('room_id', $validated['target_room_id']);

if ($activeTerm) {
    $this->applyAcademicTermFilter($targetScheduleQuery, $activeTerm);
}

$targetSchedule = $targetScheduleQuery->first();

if (!$targetSchedule) {
    return back()
        ->withInput()
        ->with('error', 'The selected target schedule could not be found.');
}

/*
|--------------------------------------------------------------------------
| FIND THE TARGET FACULTY FROM THE TARGET SCHEDULE
|--------------------------------------------------------------------------
| Do not rely only on the hidden target_user_id field.
| The selected schedule's instructor is the actual owner.
|--------------------------------------------------------------------------
*/

$targetInstructorName = mb_strtolower(
    trim((string) $targetSchedule->instructor)
);

$targetFaculty = User::whereRaw(
    'LOWER(TRIM(name)) = ?',
    [$targetInstructorName]
)->first();

if (!$targetFaculty) {
    return back()
        ->withInput()
        ->with(
            'error',
            'The faculty owner of the selected target schedule could not be found.'
        );
}

if ((int) $targetFaculty->id === (int) $user->id) {
    return back()
        ->withInput()
        ->with(
            'error',
            'You cannot send a room swap request to yourself.'
        );
}



        if ($activeTerm) {

            $this->applyAcademicTermFilter($targetScheduleQuery, $activeTerm);

        }



        $targetSchedule = $targetScheduleQuery->first();



       


 


        if ((int) $targetFaculty->id === (int) $user->id) {

            return back()->withInput()->with('error', 'You cannot send a room swap request to yourself.');

        }



        if (mb_strtoupper(trim((string) $requesterSchedule->day)) !== mb_strtoupper(trim((string) $targetSchedule->day))) {

            return back()->withInput()->with('error', 'Room swap is only allowed when both schedules are on the same day.');

        }



        if ($this->normalizeScheduleTime($requesterSchedule->time) !== $this->normalizeScheduleTime($targetSchedule->time)) {

            return back()->withInput()->with('error', 'Room swap is only allowed when both schedules have the same time.');

        }

        try {
            $swapDate = Carbon::parse($validated['swap_date'])->startOfDay();
            $scheduleDay = strtoupper(substr(trim((string) $requesterSchedule->day), 0, 3));
            if (strtoupper($swapDate->format('D')) !== $scheduleDay) {
                return back()->withInput()->with('error', 'Choose a room swap date that falls on the selected schedules’ weekday.');
            }

            $swapStart = Carbon::parse($swapDate->format('Y-m-d') . ' ' . $validated['start_time']);
            $swapEnd = Carbon::parse($swapDate->format('Y-m-d') . ' ' . $validated['end_time']);
            if ($swapEnd->lessThanOrEqualTo($swapStart)) {
                return back()->withInput()->with('error', 'The room swap end time must be later than its start time.');
            }
            if ($swapStart->lessThanOrEqualTo(now())) {
                return back()->withInput()->with('error', 'Choose a room swap time that has not started yet.');
            }
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'The room swap date or time is invalid.');
        }



        if ((int) $requesterSchedule->room_id === (int) $targetSchedule->room_id) {

            return back()->withInput()->with('error', 'Both schedules already use the same room.');

        }

        // An admin override reserves its room for the selected schedule's date
        // and time. Check both rooms because the swap would exchange their use.
        $overrideRoomIds = array_values(array_unique([
            (int) $requesterSchedule->room_id,
            (int) $targetSchedule->room_id,
        ]));

        $adminOverride = ScheduleOverride::query()
            ->with('schedule.room')
            ->where('status', 'active')
            ->whereDate('override_date', $swapDate->toDateString())
            ->where('expires_at', '>', now())
            ->whereHas('schedule', function ($query) use ($overrideRoomIds) {
                $query->whereIn('room_id', $overrideRoomIds);
            })
            ->get()
            ->first(function (ScheduleOverride $override) use ($targetSchedule) {
                return $override->schedule
                    && $this->normalizeScheduleTime($override->schedule->time)
                        === $this->normalizeScheduleTime($targetSchedule->time);
            });

        if ($adminOverride) {
            $overrideRoom = $adminOverride->schedule?->room?->room_name
                ?: 'A room involved in this swap';

            return back()->withInput()->with(
                'error',
                $overrideRoom . ' is occupied under an admin override on ' . $swapDate->format('F j, Y') . '. Choose another date or room.'
            );
        }



        // Prevent either schedule from being involved in another pending or approved swap.

        $scheduleIds = [(int) $requesterSchedule->id, (int) $targetSchedule->id];

        $busySwapExists = RoomSwapRequest::whereIn('status', ['pending', 'approved'])

            ->where(function ($query) use ($scheduleIds) {

                $query->whereIn('requester_schedule_id', $scheduleIds)

                    ->orWhereIn('target_schedule_id', $scheduleIds);

            })

            ->exists();



        if ($busySwapExists) {

            return back()->withInput()->with('error', 'One of these schedules already has a pending or approved room swap.');

        }



        // Exclude both schedules being exchanged. Only unrelated schedules in either room

        // that overlap the selected schedule's actual time range should block the swap.

        if ($this->roomHasConflict($targetSchedule->room_id, $requesterSchedule, $scheduleIds, $activeTerm)) {

            return back()->withInput()->with('error', 'This swap cannot be requested: moving your selected schedule into the target room would overlap another schedule in the active academic term. The other schedule will remain unchanged.');

        }



        if ($this->roomHasConflict($requesterSchedule->room_id, $targetSchedule, $scheduleIds, $activeTerm)) {

            return back()->withInput()->with('error', 'This swap cannot be requested: moving the selected target schedule into your room would overlap another schedule in the active academic term. The other schedule will remain unchanged.');

        }



        $swap = RoomSwapRequest::create([

            'requester_id' => $user->id,

            'target_user_id' => $targetFaculty->id,

            'requester_schedule_id' => $requesterSchedule->id,

            'target_schedule_id' => $targetSchedule->id,

            'requester_room_id' => $requesterSchedule->room_id,

            'target_room_id' => $targetSchedule->room_id,

            'swap_date' => $validated['swap_date'],

            'start_time' => $validated['start_time'],

            'end_time' => $validated['end_time'],

            'reason' => trim($validated['reason']),

            'status' => 'pending',

        ]);

        $targetFaculty->notify(new RoomSwapNotification($swap, 'requested'));



        return redirect()->back()->with('success', 'Room swap request sent. The other faculty member can now accept or decline it from Received Room Swap Requests.');

    }



    /** Show pending requests addressed to the logged-in faculty member. */

    public function receivedRequests()

    {

        abort_unless(Auth::check(), 403, 'Unauthorized.');



        $requests = RoomSwapRequest::with([

            'requester',

            'targetUser',

            'requesterRoom',

            'targetRoom',

            'requesterSchedule',

            'targetSchedule',

        ])

            ->where('target_user_id', Auth::id())

            ->where('status', 'pending')

            ->latest()

            ->get();



        return view('faculty.room-swap-requests', compact('requests'));

    }


/**
 * Accept a pending room swap request.
 *
 * IMPORTANT:
 * This does NOT change schedules.room_id.
 * The RoomSwapRequest itself is used as the temporary
 * room override when the scheduled swap time becomes active.
 */
public function approve($id)
{
    abort_unless(Auth::check(), 403, 'Unauthorized.');

    try {

        DB::transaction(function () use ($id) {

            $swap = RoomSwapRequest::query()
                ->lockForUpdate()
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | ONLY PENDING REQUESTS CAN BE ACCEPTED
            |--------------------------------------------------------------------------
            */

            if (
                mb_strtolower(trim((string) $swap->status))
                !== 'pending'
            ) {
                throw ValidationException::withMessages([
                    'swap' => 'This room swap request has already been processed.'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | LOAD BOTH SCHEDULES
            |--------------------------------------------------------------------------
            */

            $requesterSchedule = Schedule::query()
                ->lockForUpdate()
                ->find($swap->requester_schedule_id);

            $targetSchedule = Schedule::query()
                ->lockForUpdate()
                ->find($swap->target_schedule_id);

            if (!$requesterSchedule || !$targetSchedule) {

                throw ValidationException::withMessages([
                    'swap' => 'One of the schedules no longer exists.'
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | VERIFY THE CURRENT USER IS THE TARGET FACULTY
            |--------------------------------------------------------------------------
            */

            $loggedInUserId = (int) Auth::id();

            $loggedInFacultyName = mb_strtolower(
                trim((string) Auth::user()->name)
            );

            $targetFacultyName = mb_strtolower(
                trim((string) $targetSchedule->instructor)
            );

            $targetUserMatches =
                (int) $swap->target_user_id === $loggedInUserId;

            $targetInstructorMatches =
                $loggedInFacultyName !== ''
                && $targetFacultyName !== ''
                && $loggedInFacultyName === $targetFacultyName;

            if (
                !$targetUserMatches
                && !$targetInstructorMatches
            ) {

                throw ValidationException::withMessages([
                    'swap' =>
                        'You are not the faculty member assigned to the target schedule.'
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | VERIFY ORIGINAL ROOM ASSIGNMENTS
            |--------------------------------------------------------------------------
            |
            | The rooms must still be the same rooms that were selected
            | when the request was created.
            |
            */

            if (
                (int) $requesterSchedule->room_id
                !== (int) $swap->requester_room_id
            ) {

                throw ValidationException::withMessages([
                    'swap' =>
                        'The requester schedule room has already changed. Please create a new room swap request.'
                ]);

            }

            if (
                (int) $targetSchedule->room_id
                !== (int) $swap->target_room_id
            ) {

                throw ValidationException::withMessages([
                    'swap' =>
                        'The target schedule room has already changed. Please create a new room swap request.'
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | VERIFY SAME DAY
            |--------------------------------------------------------------------------
            */

            $requesterDay = mb_strtoupper(
                trim((string) $requesterSchedule->day)
            );

            $targetDay = mb_strtoupper(
                trim((string) $targetSchedule->day)
            );

            if ($requesterDay !== $targetDay) {

                throw ValidationException::withMessages([
                    'swap' =>
                        'The two schedules no longer have the same day.'
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | VERIFY SAME TIME
            |--------------------------------------------------------------------------
            */

            $requesterTime = $this->normalizeScheduleTime(
                $requesterSchedule->time
            );

            $targetTime = $this->normalizeScheduleTime(
                $targetSchedule->time
            );

            if ($requesterTime !== $targetTime) {

                throw ValidationException::withMessages([
                    'swap' =>
                        'The two schedules no longer have the same time.'
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | CHECK IF EITHER SCHEDULE IS ALREADY INVOLVED
            |--------------------------------------------------------------------------
            */

            $scheduleIds = [
                (int) $requesterSchedule->id,
                (int) $targetSchedule->id,
            ];

            $existingSwap = RoomSwapRequest::query()
                ->whereIn('status', [
                    'pending',
                    'approved'
                ])
                ->where('id', '!=', $swap->id)
                ->where(function ($query) use ($scheduleIds) {

                    $query
                        ->whereIn(
                            'requester_schedule_id',
                            $scheduleIds
                        )
                        ->orWhereIn(
                            'target_schedule_id',
                            $scheduleIds
                        );

                })
                ->exists();

            if ($existingSwap) {

                throw ValidationException::withMessages([
                    'swap' =>
                        'One of these schedules is already involved in another room swap.'
                ]);

            }

            /*
|--------------------------------------------------------------------------
| RECHECK THE REQUESTED DATE BEFORE APPROVAL
|--------------------------------------------------------------------------
*/

            try {
                $selectedDate = $swap->swap_date instanceof Carbon
                    ? $swap->swap_date->format('Y-m-d')
                    : Carbon::parse($swap->swap_date)->format('Y-m-d');
                $selectedDay = strtoupper(Carbon::parse($selectedDate)->format('D'));
                $scheduleDay = strtoupper(substr(trim((string) $targetSchedule->day), 0, 3));
                $selectedStart = Carbon::parse($selectedDate . ' ' . $swap->start_time);
                $selectedEnd = Carbon::parse($selectedDate . ' ' . $swap->end_time);

                if ($selectedDay !== $scheduleDay) {
                    throw ValidationException::withMessages([
                        'swap' => 'The selected swap date no longer matches the schedule weekday.',
                    ]);
                }
                if ($selectedEnd->lessThanOrEqualTo($selectedStart)) {
                    throw ValidationException::withMessages([
                        'swap' => 'The room swap end time must be later than its start time.',
                    ]);
                }
                if ($selectedStart->lessThanOrEqualTo(now())) {
                    throw ValidationException::withMessages([
                        'swap' => 'This room swap date and time has already started.',
                    ]);
                }
            } catch (ValidationException $exception) {
                throw $exception;
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages([
                    'swap' => 'The scheduled room swap date or time is invalid.',
                ]);
            }

            $overrideRoomIds = array_values(array_unique([
                (int) $requesterSchedule->room_id,
                (int) $targetSchedule->room_id,
            ]));

            $adminOverride = ScheduleOverride::query()
                ->with('schedule.room')
                ->where('status', 'active')
                ->whereDate('override_date', $selectedDate)
                ->where('expires_at', '>', now())
                ->whereHas('schedule', function ($query) use ($overrideRoomIds) {
                    $query->whereIn('room_id', $overrideRoomIds);
                })
                ->get()
                ->first(function (ScheduleOverride $override) use ($targetSchedule) {
                    return $override->schedule
                        && $this->normalizeScheduleTime($override->schedule->time)
                            === $this->normalizeScheduleTime($targetSchedule->time);
                });

            if ($adminOverride) {
                $roomName = $adminOverride->schedule?->room?->room_name ?: 'A room involved in this swap';
                throw ValidationException::withMessages([
                    'swap' => $roomName . ' is occupied under an admin override on ' . Carbon::parse($selectedDate)->format('F j, Y') . '. The request cannot be accepted for that date.',
                ]);
            }

            /*
|--------------------------------------------------------------------------
| VALIDATE THE SCHEDULE TIME
|--------------------------------------------------------------------------
|
| The swap follows the schedule's day and time.
|
| Example:
| Schedule = THU, 8:00-9:00 AM
| Current time = Thursday 9:00 PM
|
| The current Thursday occurrence already ended, so the
| swap should use NEXT Thursday instead.
|
*/

if (
    !empty($swap->start_time)
    && !empty($swap->end_time)
) {

    try {

        /*
        |--------------------------------------------------------------------------
        | Get the schedule day
        |--------------------------------------------------------------------------
        */

        $scheduleDay = mb_strtoupper(
            trim((string) $targetSchedule->day)
        );

        /*
        |--------------------------------------------------------------------------
        | Convert schedule day to Carbon day number
        |--------------------------------------------------------------------------
        |
        | Carbon:
        | Sunday = 0
        | Monday = 1
        | Tuesday = 2
        | Wednesday = 3
        | Thursday = 4
        | Friday = 5
        | Saturday = 6
        |
        */

        $dayMap = [
            'SUN' => Carbon::SUNDAY,
            'SUNDAY' => Carbon::SUNDAY,

            'MON' => Carbon::MONDAY,
            'MONDAY' => Carbon::MONDAY,

            'TUE' => Carbon::TUESDAY,
            'TUESDAY' => Carbon::TUESDAY,

            'WED' => Carbon::WEDNESDAY,
            'WEDNESDAY' => Carbon::WEDNESDAY,

            'THU' => Carbon::THURSDAY,
            'THURSDAY' => Carbon::THURSDAY,

            'FRI' => Carbon::FRIDAY,
            'FRIDAY' => Carbon::FRIDAY,

            'SAT' => Carbon::SATURDAY,
            'SATURDAY' => Carbon::SATURDAY,
        ];

        if (!isset($dayMap[$scheduleDay])) {

            throw ValidationException::withMessages([
                'swap' =>
                    'The schedule day is invalid. Please create the room swap request again.'
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Start with today
        |--------------------------------------------------------------------------
        */

        $occurrence = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | Move to the next occurrence of the schedule day
        |--------------------------------------------------------------------------
        */

        $daysUntilSchedule =
            ($dayMap[$scheduleDay] - $occurrence->dayOfWeek + 7) % 7;

        if ($daysUntilSchedule > 0) {

            $occurrence->addDays($daysUntilSchedule);

        }

        /*
        |--------------------------------------------------------------------------
        | Create the start/end datetime
        |--------------------------------------------------------------------------
        */

        $start = Carbon::parse(
            $occurrence->format('Y-m-d')
            . ' '
            . $swap->start_time
        );

        $end = Carbon::parse(
            $occurrence->format('Y-m-d')
            . ' '
            . $swap->end_time
        );

        /*
        |--------------------------------------------------------------------------
        | If today's occurrence already ended,
        | move to the NEXT week.
        |--------------------------------------------------------------------------
        */

        if (now()->greaterThanOrEqualTo($end)) {

            $occurrence->addDays(7);

            $start = Carbon::parse(
                $occurrence->format('Y-m-d')
                . ' '
                . $swap->start_time
            );

            $end = Carbon::parse(
                $occurrence->format('Y-m-d')
                . ' '
                . $swap->end_time
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure end is after start
        |--------------------------------------------------------------------------
        */

        if ($end->lessThanOrEqualTo($start)) {

            throw ValidationException::withMessages([
                'swap' =>
                    'The room swap end time must be later than the start time.'
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Update the request with the actual next occurrence.
        |--------------------------------------------------------------------------
        */

        // Preserve the date selected by the requester; it controls when the
        // temporary room exchange appears and when it expires.

    }
    catch (ValidationException $exception) {

        throw $exception;

    }
    catch (\Throwable $exception) {

        throw ValidationException::withMessages([
            'swap' =>
                'The scheduled room swap date or time is invalid.'
        ]);

    }
}

            /*
            |--------------------------------------------------------------------------
            | APPROVE THE REQUEST
            |--------------------------------------------------------------------------
            |
            | VERY IMPORTANT:
            |
            | We intentionally DO NOT do:
            |
            | $requesterSchedule->room_id = ...
            | $targetSchedule->room_id = ...
            |
            | The original room assignments must remain unchanged.
            |
            */

            $swap->status = 'approved';
            $swap->save();

        });

    } catch (ValidationException $exception) {

        return back()->with(
            'error',
            collect($exception->errors())
                ->flatten()
                ->first()
            ?? 'The room swap could not be accepted.'
        );

    } catch (\Throwable $exception) {

        return back()->with(
            'error',
            'The room swap could not be accepted: '
            . $exception->getMessage()
        );

    }

    $completedSwap = RoomSwapRequest::with(['requester', 'targetUser', 'requesterRoom', 'targetRoom'])
        ->findOrFail($id);
    $this->markIncomingSwapNotificationRead((int) $completedSwap->target_user_id, (int) $completedSwap->id);
    $completedSwap->requester?->notify(new RoomSwapNotification($completedSwap, 'approved'));

    return back()->with(
        'success',
        'Room swap accepted successfully. The original rooms remain unchanged and the temporary room exchange will become active at the scheduled time.'
    );
}

    /** Decline a pending request addressed to the logged-in faculty member. */

    public function decline($id)

    {

        abort_unless(Auth::check(), 403, 'Unauthorized.');



        $swap = RoomSwapRequest::findOrFail($id);



        $ownershipSchedule = Schedule::find($swap->target_schedule_id);

        $isAssignedTarget = $ownershipSchedule

            && mb_strtolower(trim((string) $ownershipSchedule->instructor)) === mb_strtolower(trim((string) Auth::user()->name));



        if ((int) $swap->target_user_id !== (int) Auth::id() && !$isAssignedTarget) {

            abort(403, 'Only the faculty member assigned to the target schedule can decline this request.');

        }



        if ($swap->status !== 'pending') {

            return back()->with('error', 'This room swap request has already been processed.');

        }



        $swap->update(['status' => 'declined']);

        $swap->load(['requester', 'targetUser', 'requesterRoom', 'targetRoom']);
        $this->markIncomingSwapNotificationRead((int) $swap->target_user_id, (int) $swap->id);
        $swap->requester?->notify(new RoomSwapNotification($swap, 'declined'));



        return back()->with('success', 'Room swap request declined.');

    }



    /** Resolve the academic term currently selected/published by the system. */


    /**
     * Cancel a pending room swap request created by the logged-in faculty member.
     *
     * Only the original requester may cancel it, and only while it is still pending.
     * Cancelling does not change either schedule's room assignment.
     */
    public function cancel($id)
    {
        abort_unless(Auth::check(), 403, 'Unauthorized.');

        $swap = RoomSwapRequest::whereKey($id)->lockForUpdate()->firstOrFail();

        if ((int) $swap->requester_id !== (int) Auth::id()) {
            abort(403, 'Only the faculty member who sent this room swap request can cancel it.');
        }

        if (strtolower(trim((string) $swap->status)) !== 'pending') {
            return back()->with(
                'error',
                'This room swap request can no longer be cancelled because it has already been processed.'
            );
        }

        $swap->update([
            'status' => 'cancelled',
        ]);

        return back()->with(
            'success',
            'Room swap request cancelled successfully. No room assignments were changed.'
        );
    }

    /**
     * Restore room assignments for approved swaps whose end time has passed.
     *
     * The original room IDs are stored on room_swap_requests, so the schedules
     * can safely be returned to their pre-swap rooms.
     */
    private function cleanupExpiredSwaps(): void
{
    $approvedSwaps = RoomSwapRequest::query()
        ->where('status', 'approved')
        ->whereNotNull('swap_date')
        ->whereNotNull('end_time')
        ->get();

    foreach ($approvedSwaps as $swap) {

        try {
            $swapDate = $swap->swap_date instanceof Carbon
                ? $swap->swap_date->format('Y-m-d')
                : Carbon::parse($swap->swap_date)->format('Y-m-d');

            $end = Carbon::parse(
                $swapDate . ' ' . $swap->end_time
            );
        } catch (\Throwable $exception) {
            continue;
        }

        /*
         * The schedule room_id is NOT changed when a swap is approved.
         *
         * The approved RoomSwapRequest acts as a temporary room
         * override while the swap is active.
         *
         * Once the scheduled end time is reached, simply mark the
         * request as expired. The original room_id values remain
         * untouched in the schedules table.
         */
        if (now()->lt($end)) {
            continue;
        }

        DB::transaction(function () use ($swap) {

            $lockedSwap = RoomSwapRequest::whereKey($swap->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedSwap) {
                return;
            }

            if (
                mb_strtolower(
                    trim((string) $lockedSwap->status)
                ) !== 'approved'
            ) {
                return;
            }

            $lockedSwap->status = 'expired';
            $lockedSwap->save();
        });
    }
}
    private function resolveAcademicTerm(Request $request): ?AcademicTerm

    {

        $requestedTermId = $request->input('term_id') ?? $request->query('term_id');

        if ($requestedTermId) {

            $requestedTerm = AcademicTerm::find((int) $requestedTermId);

            if ($requestedTerm) {

                return $requestedTerm;

            }

        }



        // Set Schedule stores the chosen term in the session. Respect that choice

        // so pages opened afterward (including Room Swap) use the same term.

        $sessionTermId = session('active_academic_term_id');

        if ($sessionTermId) {

            $sessionTerm = AcademicTerm::find((int) $sessionTermId);

            if ($sessionTerm) {

                return $sessionTerm;

            }

        }



        $activeTerm = AcademicTerm::where('is_active', true)

            ->orderByDesc('id')

            ->first();



        if ($activeTerm) {

            session(['active_academic_term_id' => $activeTerm->id]);

            return $activeTerm;

        }



        return AcademicTerm::orderByDesc('id')->first();

    }



    /**

     * Match current-term schedules strictly. Legacy schedules are accepted only

     * when both academic_year and semester exactly match the selected term.

     */

    private function applyAcademicTermFilter($query, AcademicTerm $activeTerm)

    {

        return $query->where(function ($termQuery) use ($activeTerm) {

            $termQuery->where('academic_term_id', $activeTerm->id)

                ->orWhere(function ($legacyQuery) use ($activeTerm) {

                    $legacyQuery->whereNull('academic_term_id')

                        ->where('academic_year', $activeTerm->academic_year)

                        ->where('semester', $activeTerm->semester);

                });

        });

    }



    private function scheduleMatchesAcademicTerm(Schedule $schedule, AcademicTerm $activeTerm): bool

    {

        if ($schedule->academic_term_id !== null) {

            return (int) $schedule->academic_term_id === (int) $activeTerm->id;

        }



        return (string) $schedule->academic_year === (string) $activeTerm->academic_year

            && (string) $schedule->semester === (string) $activeTerm->semester;

    }



    /**

     * Return true only if an unrelated schedule overlaps the requested schedule's time

     * in the same room and day. Schedules being swapped are excluded from this check.

     */

    private function roomHasConflict($roomId, Schedule $schedule, array $excludeScheduleIds, ?AcademicTerm $activeTerm = null): bool

    {

        $wantedRange = $this->scheduleTimeRange($schedule->time);

        if ($wantedRange === null) {

            // If a schedule's time cannot be parsed, fall back to exact normalized matching

            // rather than blocking a swap based on an unparseable value.

            $wantedTime = $this->normalizeScheduleTime($schedule->time);

            $query = Schedule::where('room_id', $roomId)

                ->where('day', $schedule->day)

                ->whereNotIn('id', $excludeScheduleIds);



            if ($activeTerm) {

                $this->applyAcademicTermFilter($query, $activeTerm);

            }



            return $query->get(['id', 'time'])

                ->contains(fn ($other) => $this->normalizeScheduleTime($other->time) === $wantedTime);

        }



        [$wantedStart, $wantedEnd] = $wantedRange;

        $otherSchedulesQuery = Schedule::where('room_id', $roomId)

            ->where('day', $schedule->day)

            ->whereNotIn('id', $excludeScheduleIds);



        if ($activeTerm) {

            $this->applyAcademicTermFilter($otherSchedulesQuery, $activeTerm);

        }



        $otherSchedules = $otherSchedulesQuery->get(['id', 'time']);



        foreach ($otherSchedules as $otherSchedule) {

            $otherRange = $this->scheduleTimeRange($otherSchedule->time);

            if ($otherRange === null) {

                continue;

            }



            [$otherStart, $otherEnd] = $otherRange;

            // Half-open ranges mean 8:00-9:00 and 9:00-10:00 do not overlap.

            if ($wantedStart < $otherEnd && $otherStart < $wantedEnd) {

                return true;

            }

        }



        return false;

    }



   /**
 * Convert a schedule time string into a [startMinute, endMinute] range.
 */
private function scheduleTimeRange($time): ?array
{
    $raw = mb_strtolower(trim((string) $time));

    if ($raw === '') {
        return null;
    }

    // Normalize dash characters and the word "to".
    $raw = str_replace(
        ['–', '—', '−'],
        '-',
        $raw
    );

    $raw = preg_replace('/\s+/', ' ', $raw) ?? $raw;

    $raw = preg_replace_callback(
        '/\b(a\.?m\.?|p\.?m\.?)\b/i',
        static function ($match) {
            return str_replace('.', '', strtolower($match[0]));
        },
        $raw
    ) ?? $raw;

    // Allow "8:00 to 9:00" as well as "8:00-9:00".
    $raw = preg_replace('/\s+to\s+/i', '-', $raw) ?? $raw;

    // Accept:
    // 8:00-9:00
    // 8:00 AM - 9:00 AM
    // 8am-9am
    // 8 AM - 9 AM
    $parts = preg_split('/\s*-\s*/', $raw);

    if (!$parts || count($parts) !== 2) {
        return null;
    }

    $startText = trim($parts[0]);
    $endText = trim($parts[1]);

    // If only one endpoint has AM/PM, apply it to the other endpoint.
    $startPeriod = preg_match(
        '/(am|pm)$/i',
        $startText,
        $m
    ) ? strtolower($m[1]) : null;

    $endPeriod = preg_match(
        '/(am|pm)$/i',
        $endText,
        $m
    ) ? strtolower($m[1]) : null;

    if ($startPeriod && !$endPeriod) {
        $endText .= ' ' . $startPeriod;
    } elseif (!$startPeriod && $endPeriod) {
        $startText .= ' ' . $endPeriod;
    }

    $toMinutes = static function (string $value): ?int {
        $value = trim(
            str_replace(
                '.',
                '',
                strtolower($value)
            )
        );

        /*
         * Supported:
         * 8
         * 8am
         * 8 am
         * 8:00
         * 8:00am
         * 8:00 am
         * 08:00
         * 12:30pm
         */
        if (!preg_match(
            '/^(\d{1,2})(?::(\d{1,2}))?\s*(am|pm)?$/i',
            $value,
            $matches
        )) {
            return null;
        }

        $hour = (int) $matches[1];

        $minute = (
            isset($matches[2]) &&
            $matches[2] !== ''
        )
            ? (int) $matches[2]
            : 0;

        $period = strtolower(
            $matches[3] ?? ''
        );

        if ($minute > 59) {
            return null;
        }

        if ($period !== '') {

            // 12-hour clock.
            if ($hour < 1 || $hour > 12) {
                return null;
            }

            if ($period === 'am' && $hour === 12) {
                $hour = 0;
            } elseif ($period === 'pm' && $hour !== 12) {
                $hour += 12;
            }

        } elseif ($hour > 23) {

            // 24-hour clock.
            return null;
        }

        return ($hour * 60) + $minute;
    };

    $start = $toMinutes($startText);
    $end = $toMinutes($endText);

    if (
        $start === null ||
        $end === null ||
        $end <= $start
    ) {
        return null;
    }

    return [$start, $end];
}


    /** Normalize equivalent time strings for same-time schedule validation. */

    /**
 * Normalize equivalent time strings for same-time schedule validation.
 */
private function normalizeScheduleTime($time): string
{
    $range = $this->scheduleTimeRange($time);

    if ($range !== null) {
        return $range[0] . '-' . $range[1];
    }

    $raw = mb_strtolower(trim((string) $time));

    $raw = str_replace(
        ['–', '—', '−'],
        '-',
        $raw
    );

    $raw = preg_replace('/\s+/', '', $raw) ?? $raw;

    return str_replace('.', '', $raw);
}

private function markIncomingSwapNotificationRead(int $userId, int $swapId): void
{
    $user = User::find($userId);
    if (!$user) {
        return;
    }

    $user->unreadNotifications
        ->filter(function ($notification) use ($swapId) {
            return ($notification->data['type'] ?? null) === 'room_swap'
                && ($notification->data['event'] ?? null) === 'requested'
                && (int) ($notification->data['room_swap_id'] ?? 0) === $swapId;
        })
        ->each(fn ($notification) => $notification->markAsRead());
}

}
