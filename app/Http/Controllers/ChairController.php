<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Department;
use App\Models\FacultySubject;
use App\Models\Building;
use App\Models\RoomSwapRequest;
use App\Models\ScheduleMerge;
use App\Models\AcademicTerm;
use App\Models\Program;
use App\Models\Subject;
use App\Models\ScheduleTimeSlot;
use App\Models\RoomScheduleTimeSlot;
use App\Models\ScheduleCellMerge;
use App\Models\ScheduleOverride;
use App\Models\RoomReassignment;
use App\Notifications\ScheduleMergeNotification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ChairController extends Controller
{
  public function dashboard()
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    // Automatically expire completed temporary room reservations.
    $this->expireReservations();

    /*
    |--------------------------------------------------------------------------
    | GET ALL DEPARTMENTS
    |--------------------------------------------------------------------------
    |
    | Every department created in the database will automatically
    | appear on the Chair Dashboard.
    |
    */

    $departments = Department::withCount('rooms')
        ->orderBy('name')
        ->get();



    /*
    |--------------------------------------------------------------------------
    | ROOM SWAP REQUESTS
    |--------------------------------------------------------------------------
    |
    | KEEP YOUR EXISTING ROOM SWAP LOGIC HERE.
    |
    */

  $swapRequests = RoomSwapRequest::with([
    'requester',
    'targetUser',
    'requesterRoom',
    'targetRoom',
    'requesterSchedule',
    'targetSchedule',
])
->latest()
->get();

/*
|--------------------------------------------------------------------------
| PENDING MERGE REQUESTS
|--------------------------------------------------------------------------
|
| Show only pending merge requests for classes
| owned by this chair's department.
|
*/

$mergeRelations = [
    'schedule.room',
    'schedule.department',
    'facultySubject.subjectCatalog.program.department',
    'subject.program.department',
    'department',
    'requester',
    'approver',
];

$mergeRequests = ScheduleMerge::with($mergeRelations)
->whereIn('status', [
    'pending',
    'approved'
])
->whereHas(
    'schedule',
    function ($query) {

        $query->where(
            'department_id',
            Auth::user()->department_id
        );

    }
)
->latest()
->get()
->unique(fn ($merge) => $merge->request_group_id ?: 'merge-' . $merge->id)
->values();

$sentMergeRequests = ScheduleMerge::with($mergeRelations)
    ->where('department_id', Auth::user()->department_id)
    ->whereIn('status', ['pending', 'approved', 'rejected'])
    ->latest()
    ->get()
    ->unique(fn ($merge) => $merge->request_group_id ?: 'merge-' . $merge->id)
    ->values();
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD VIEW
    |--------------------------------------------------------------------------
    */

    return view(
    'chair.dashboard',
    compact(
        'departments',
        'swapRequests',
        'mergeRequests',
        'sentMergeRequests'
    )
);
}

public function requests()
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $this->expireReservations();

    $swapRequests = RoomSwapRequest::with([
        'requester',
        'targetUser',
        'requesterRoom',
        'targetRoom',
        'requesterSchedule',
        'targetSchedule',
    ])->latest()->get();

    $mergeRelations = [
        'schedule.room',
        'schedule.department',
        'facultySubject.subjectCatalog.program.department',
        'subject.program.department',
        'department',
        'requester',
        'approver',
    ];

    $mergeRequests = ScheduleMerge::with($mergeRelations)
        ->whereIn('status', ['pending', 'approved'])
        ->whereHas('schedule', function ($query) {
            $query->where('department_id', Auth::user()->department_id);
        })
        ->latest()
        ->get()
        ->unique(fn ($merge) => $merge->request_group_id ?: 'merge-' . $merge->id)
        ->values();

    $sentMergeRequests = ScheduleMerge::with($mergeRelations)
        ->where('department_id', Auth::user()->department_id)
        ->whereIn('status', ['pending', 'approved', 'rejected'])
        ->latest()
        ->get()
        ->unique(fn ($merge) => $merge->request_group_id ?: 'merge-' . $merge->id)
        ->values();

    return view('chair.requests', compact('swapRequests', 'mergeRequests', 'sentMergeRequests'));
}

    // Open the schedule editor for a room
public function index(Request $request, $roomId)
{
    $user = Auth::user();
    $isPublishedScheduleView = ($request->boolean('readonly') || $request->route('readonly'))
        && $user
        && in_array($user->role, ['admin', 'faculty'], true);

    if (!$this->isChairUser() && !$isPublishedScheduleView) {
        abort(403, 'Unauthorized.');
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE TERM
    |--------------------------------------------------------------------------
    */

    $termId = $request->query(
        'term_id'
    );

    $activeTerm = $this->getActiveAcademicTerm(
        $termId ? (int) $termId : null
    );

    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR
    |--------------------------------------------------------------------------
    */

    $chair = $user;

    /*
    |--------------------------------------------------------------------------
    | ROOM
    |--------------------------------------------------------------------------
    */

    $room = Room::with([
        'department',
        'building',
    ])->findOrFail(
        $roomId
    );

    $isCrossScheduling = !$isPublishedScheduleView && $request->query('mode') === 'cross';
    $isOtherDepartmentRoom = (int) $room->department_id !== (int) $chair->department_id;

    $roomMatchesEditorMode = $isCrossScheduling
        ? $isOtherDepartmentRoom
        : !$isOtherDepartmentRoom;

    if (!$isPublishedScheduleView && !$roomMatchesEditorMode) {
        abort(403, 'Choose a room from the matching schedule editor.');
    }

    $isCrossRoomReadOnly = $isCrossScheduling && !$room->allow_other_departments;

    /*
    |--------------------------------------------------------------------------
    | FACULTY SUBJECTS
    |--------------------------------------------------------------------------
    */

    $facultySubjects = $isPublishedScheduleView ? collect() :
        FacultySubject::with([
            'faculty.department',
            'subjectCatalog.program',
            'sharedCohorts.program',
        ])
        ->whereHas('faculty', fn ($query) => $query->active())
        ->orderBy(
            'course_code'
        )
        ->get()
        ->filter(fn ($subject) => $this->chairCanManageFacultySubject($subject, $chair))
        ->values();

    /*
    |--------------------------------------------------------------------------
    | ONLY CURRENT TERM SCHEDULES
    |--------------------------------------------------------------------------
    */

    $schedules =
        Schedule::with([

            'merges' => function ($query) {

                $query
                    ->whereIn('status', ['approved', 'pending'])
                    ->with([
                        'department',
                        'subject.program.department',
                        'facultySubject.subjectCatalog.program.department',
                    ]);

            }

        ])
        ->where(
            'room_id',
            $room->id
        )
        ->where(
            'academic_term_id',
            $activeTerm->id
        )
        ->orderBy(
            'day'
        )
        ->orderBy(
            'time'
        )
        ->get();

    /*
    |--------------------------------------------------------------------------
    | RESERVATIONS
    |--------------------------------------------------------------------------
    */

    $reservations =
        Reservation::with('user')
        ->where(
            'room_id',
            $room->id
        )
        ->where('status', 'Reserved')
        ->where(function ($query) {

            $today =
                now()->format(
                    'Y-m-d'
                );

            $nowTime =
                now()->format(
                    'H:i:s'
                );

            $query
                ->whereDate(
                    'date',
                    '>',
                    $today
                )
                ->orWhere(
                    function ($query) use (
                        $today,
                        $nowTime
                    ) {

                        $query
                            ->whereDate(
                                'date',
                                $today
                            )
                            ->where(
                                'end_time',
                                '>',
                                $nowTime
                            );

                    }
                );

        })
        ->orderBy(
            'date'
        )
        ->orderBy(
            'start_time'
        )
        ->get();

    $publishedScheduleOverrides = collect();
    if ($isPublishedScheduleView) {
        $roomScheduleIds = $schedules->pluck('id')->map(fn ($id) => (int) $id)->all();
        $publishedScheduleOverrides = ScheduleOverride::with(['room', 'schedule.room'])
            ->where('status', 'active')
            ->whereDate('override_date', '>=', Carbon::today()->toDateString())
            ->where('expires_at', '>', now())
            ->where(function ($query) use ($room, $roomScheduleIds) {
                $query->where('room_id', $room->id);
                if ($roomScheduleIds) {
                    $query->orWhereIn('schedule_id', $roomScheduleIds);
                }
            })
            ->orderBy('override_date')
            ->orderBy('start_time')
            ->get()
            ->filter(function (ScheduleOverride $override) use ($room, $roomScheduleIds) {
                if ($override->room_id) {
                    return (int) $override->room_id === (int) $room->id
                        && (!$override->schedule_id || in_array((int) $override->schedule_id, $roomScheduleIds, true));
                }
                return $override->schedule_id
                    && in_array((int) $override->schedule_id, $roomScheduleIds, true);
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE TERM VALUES
    |--------------------------------------------------------------------------
    */

    $semester =
        $activeTerm->semester;

    $academicYear =
        $activeTerm->academic_year;

    $timeSlots = ScheduleTimeSlot::where(
        'academic_term_id',
        $activeTerm->id
    )
        ->where('is_active', true)
        ->orderBy('row_position')
        ->orderBy('start_time')
        ->get();

    $roomTimeSlots = RoomScheduleTimeSlot::where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->where('is_active', true)
        ->orderBy('row_position')
        ->orderBy('start_time')
        ->get();

    $hasRoomRowDeletionMarker = RoomScheduleTimeSlot::where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->where('day', 'ROW_DELETE')
        ->exists();

    $manualCellMerges = ScheduleCellMerge::where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->orderBy('id')
        ->get(['axis', 'cells'])
        ->map(fn ($merge) => [
            'axis' => $merge->axis,
            'cells' => $merge->cells,
        ])
        ->values()
        ->all();

    return view(
        'chair.excel',
        compact(
            'room',
            'facultySubjects',
            'schedules',
            'semester',
            'academicYear',
            'publishedScheduleOverrides',
            'activeTerm',
            'timeSlots',
            'roomTimeSlots',
            'hasRoomRowDeletionMarker',
            'manualCellMerges',
            'isCrossScheduling',
            'isCrossRoomReadOnly',
            'isPublishedScheduleView'
        )
    );
}
public function setFaculty(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $department = Department::with([
        'users' => function ($query) {
            $query->where('role', 'faculty')
                ->active()
                ->with(['facultySubjects.subjectCatalog.program', 'facultySubjects.sharedCohorts.program'])
                ->orderBy('name');
        }
    ])->findOrFail($chair->department_id);

    $importRecords = DB::table('department_faculty_imports')
        ->where('department_id', $chair->department_id)
        ->get(['faculty_id', 'created_at']);

    $importedFacultyIds = $importRecords->pluck('faculty_id');
    $importedAtByFaculty = $importRecords->keyBy('faculty_id');

    $importedFaculty = User::whereIn('id', $importedFacultyIds)
        ->where('role', 'faculty')
        ->active()
        ->with(['department', 'facultySubjects.subjectCatalog.program', 'facultySubjects.sharedCohorts.program'])
        ->orderBy('name')
        ->get();

    $importedFaculty->each(function ($faculty) use ($importedAtByFaculty, $chair) {
        $importedAt = $importedAtByFaculty->get($faculty->id)?->created_at;

        $faculty->setAttribute('imported_at', $importedAt);

        $departmentSubjects = $faculty->facultySubjects
            ->filter(function ($subject) use ($chair, $importedAt) {
                $catalogDepartmentId =
                    $subject->subjectCatalog?->program?->department_id;

                if ($catalogDepartmentId !== null) {
                    return (int) $catalogDepartmentId
                        === (int) $chair->department_id;
                }

                return $importedAt
                    && $subject->created_at
                    && strtotime($subject->created_at)
                        >= strtotime($importedAt);
            })
            ->values();

        $faculty->setRelation('departmentSubjects', $departmentSubjects);
    });

    $availableFaculty = User::where('role', 'faculty')
        ->active()
        ->whereNotNull('department_id')
        ->where('department_id', '!=', $chair->department_id)
        ->whereNotIn('id', $importedFacultyIds)
        ->with('department')
        ->orderBy('name')
        ->get();

    $selectedFaculty = null;

    if ($request->filled('faculty')) {
        $facultyId = (int) $request->query('faculty');

        $selectedFaculty = $department->users->firstWhere('id', $facultyId)
            ?? $importedFaculty->firstWhere('id', $facultyId);
    }

    return view('chair.setFaculty', compact(
        'department',
        'selectedFaculty',
        'importedFaculty',
        'availableFaculty'
    ));
}
    
public function importFaculty(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $validated = $request->validate([
        'faculty_id' => ['required', 'integer', 'exists:users,id'],
    ]);

    $faculty = User::where('id', $validated['faculty_id'])
        ->where('role', 'faculty')
        ->active()
        ->whereNotNull('department_id')
        ->where('department_id', '!=', $chair->department_id)
        ->firstOrFail();

    $alreadyImported = DB::table('department_faculty_imports')
        ->where('department_id', $chair->department_id)
        ->where('faculty_id', $faculty->id)
        ->exists();

    if ($alreadyImported) {
        return redirect()->route('chair.setFaculty')
            ->with('error', 'This instructor has already been imported.');
    }

    DB::table('department_faculty_imports')->insert([
        'department_id' => $chair->department_id,
        'faculty_id' => $faculty->id,
        'imported_by' => $chair->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('chair.setFaculty')
        ->with('success', $faculty->name . ' was added to your imported instructors list.');
}

public function removeImportedFaculty($facultyId)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $deleted = DB::table('department_faculty_imports')
        ->where('department_id', $chair->department_id)
        ->where('faculty_id', $facultyId)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('users')
                ->whereColumn(
                    'users.id',
                    'department_faculty_imports.faculty_id'
                )
                ->where('users.role', 'faculty')
                ->whereColumn(
                    'users.department_id',
                    '!=',
                    'department_faculty_imports.department_id'
                );
        })
        ->delete();

    if (!$deleted) {
        return redirect()->route('chair.setFaculty')
            ->with('error', 'The imported instructors relationship was not found.');
    }

    return redirect()->route('chair.setFaculty')
        ->with(
            'success',
            'Imported instructors removed. The instructor account and home college were not changed.'
        );
}

// Display all rooms in the Chair page
public function setschedule(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | CHAIR AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    if (!$this->isChairUser()) {
        abort(403, 'Unauthorized.');
    }


    $chair = Auth::user();


    // The dropdown submits an explicit activation request. This page always
    // opens on the globally active term saved in the database.
    $activeTerm = $this->getActiveAcademicTerm();


    /*
    |--------------------------------------------------------------------------
    | GET ALL ACADEMIC TERMS
    |--------------------------------------------------------------------------
    |
    | Used for the dropdown.
    |
    */

    $availableTerms = AcademicTerm::query()

        ->orderByDesc(
            'academic_year'
        )

        ->orderByRaw(
            "CASE
                WHEN semester = '1st Semester' THEN 1
                ELSE 2
            END"
        )

        ->orderByDesc(
            'id'
        )

        ->get();


    /*
    |--------------------------------------------------------------------------
    | LOAD OFFICIAL TIME SLOTS
    |--------------------------------------------------------------------------
    |
    | Time rows are scoped to the selected academic term. These rows will
    | later be used by Faculty Vacant Rooms instead of free-typed times.
    |
    */

    $timeSlots = ScheduleTimeSlot::where(
        'academic_term_id',
        $activeTerm->id
    )
        ->where('is_active', true)
        ->orderByRaw(
            "CASE day
                WHEN 'MON' THEN 1
                WHEN 'TUE' THEN 2
                WHEN 'WED' THEN 3
                WHEN 'THU' THEN 4
                WHEN 'FRI' THEN 5
                WHEN 'SAT' THEN 6
                WHEN 'SUN' THEN 7
                ELSE 8
            END"
        )
        ->orderBy('row_position')
        ->orderBy('start_time')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | LOAD DEPARTMENT
    |--------------------------------------------------------------------------
    |
    | Only schedules belonging to the ACTIVE TERM
    | are loaded.
    |
    */

    $department = Department::with([

        'buildings.rooms' => function ($query) use ($activeTerm) {

            $query->with([

                'schedules' => function ($scheduleQuery) use ($activeTerm) {

                    $scheduleQuery

                        ->where(
                            'academic_term_id',
                            $activeTerm->id
                        )

                        ->orderBy(
                            'day'
                        )

                        ->orderBy(
                            'time'
                        );

                }

            ]);

        }

    ])->find(
        $chair->department_id
    );


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT NOT FOUND
    |--------------------------------------------------------------------------
    */

    if (!$department) {

        abort(
            404,
            'College not found.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUILDINGS
    |--------------------------------------------------------------------------
    */

    $buildings =
        $department->buildings;

    $availabilityRooms = $buildings
        ->flatMap(function ($building) use ($department) {
            return $building->rooms->map(function ($room) use ($building, $department) {
                return [
                    'id' => (int) $room->id,
                    'room_name' => $room->room_name,
                    'building_name' => $building->name,
                    'department_name' => $department->name,
                    'is_current_department' => true,
                    'allow_other_departments' => (bool) $room->allow_other_departments,
                    'schedules' => $room->schedules->map(fn ($schedule) => [
                        'course_code' => $schedule->course_code,
                        'subject' => $schedule->subject,
                        'day' => strtoupper(trim((string) $schedule->day)),
                        'time' => $schedule->time,
                    ])->values()->all(),
                ];
            });
        })
        ->values()
        ->all();


    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'chair.setSchedule',
        compact(
            'department',
            'buildings',
            'activeTerm',
            'availableTerms',
            'timeSlots',
            'availabilityRooms'
        )
    );
}

public function roomAvailability(Request $request)
{
    if (!$this->isChairUser()) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $activeTerm = $this->getActiveAcademicTerm(
        $request->query('term_id') ? (int) $request->query('term_id') : null
    );

    $departments = Department::with([
        'buildings.rooms' => function ($query) use ($activeTerm) {
            $query->with(['schedules' => function ($scheduleQuery) use ($activeTerm) {
                $scheduleQuery
                    ->where('academic_term_id', $activeTerm->id)
                    ->orderBy('day')
                    ->orderBy('time');
            }]);
        },
    ])->orderBy('name')->get();

    $availabilityRooms = $departments
        ->flatMap(function ($department) use ($chair) {
            return $department->buildings->flatMap(function ($building) use ($department, $chair) {
                return $building->rooms->map(function ($room) use ($building, $department, $chair) {
                    return [
                        'id' => (int) $room->id,
                        'room_name' => $room->room_name,
                        'building_name' => $building->name,
                        'department_name' => $department->name,
                        'is_current_department' => (int) $department->id === (int) $chair->department_id,
                        'allow_other_departments' => (bool) $room->allow_other_departments,
                        'schedules' => $room->schedules->map(fn ($schedule) => [
                            'course_code' => $schedule->course_code,
                            'subject' => $schedule->subject,
                            'day' => strtoupper(trim((string) $schedule->day)),
                            'time' => $schedule->time,
                        ])->values()->all(),
                    ];
                });
            });
        })
        ->values()
        ->all();

    return view('chair.roomavailability', compact('chair', 'activeTerm', 'availabilityRooms'));
}

public function updateRoomPermission(Request $request, $room)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $room = Room::findOrFail($room);

    if (
        (int) $room->department_id !==
        (int) $chair->department_id
    ) {
        return response()->json([
            'success' => false,
            'message' => 'You can only manage rooms in your college.'
        ], 403);
    }

    $allowed = $request->boolean(
        'allow_other_departments'
    );

   $room->allow_other_departments = $allowed;

if (!$room->save()) {
    return response()->json([
        'success' => false,
        'message' => 'Failed to save permission.'
    ], 500);
}


    $room->refresh();

    return response()->json([
        'success' => true,

        'allow_other_departments' =>
            (bool) $room->allow_other_departments,

        'message' =>
            $allowed
                ? 'Other colleges can now add schedules to empty slots.'
                : 'Other colleges can no longer add schedules to empty slots.'
    ]);
}
public function checkScheduleFacultyConflict(Request $request)
{
    if (!$this->isChairUser()) {
        abort(403, 'Unauthorized.');
    }

    $validated = $request->validate([
        'room_id' => ['required', 'integer', 'exists:rooms,id'],
        'term_id' => ['required', 'integer', 'exists:academic_terms,id'],
        'faculty_subject_id' => ['required', 'integer', 'exists:faculty_subjects,id'],
        'course_code' => ['required', 'string', 'max:50'],
        'slots' => ['required', 'array', 'min:1', 'max:100'],
        'slots.*.day' => ['required', 'string', 'in:MON,TUE,WED,THU,FRI,SAT'],
        'slots.*.time' => ['required', 'string', 'max:64'],
        'slots.*.schedule_id' => ['nullable', 'integer', 'min:1'],
        'allow_same_course_conflict' => ['nullable', 'boolean'],
    ]);

    $allowSameCourseConflict = (bool) ($validated['allow_same_course_conflict'] ?? false);

    $chair = Auth::user();
    $room = Room::findOrFail($validated['room_id']);
    if ((int) $room->department_id !== (int) $chair->department_id && !$room->allow_other_departments) {
        return response()->json([
            'message' => 'This college does not allow other colleges to add schedules to this room.',
        ], 403);
    }

    $facultySubject = FacultySubject::with('faculty')
        ->whereHas('faculty', fn ($query) => $query->active())
        ->findOrFail($validated['faculty_subject_id']);
    if (
        strtoupper(trim($facultySubject->course_code)) !== strtoupper(trim($validated['course_code']))
        || !$this->chairCanManageFacultySubject($facultySubject, $chair)
        || !$facultySubject->faculty
    ) {
        return response()->json([
            'message' => 'This course code is not assigned to a instructor you can schedule.',
        ], 422);
    }

    $term = $this->getActiveAcademicTerm((int) $validated['term_id']);
    $requestedScheduleIds = collect($validated['slots'])
        ->pluck('schedule_id')
        ->filter()
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values();

    // Ignore only records from the current room and term that the selected
    // cells are replacing. All other rooms remain part of the conflict check.
    $ignoredScheduleIds = Schedule::whereIn('id', $requestedScheduleIds)
        ->where('room_id', $room->id)
        ->where('department_id', $chair->department_id)
        ->where('academic_term_id', $term->id)
        ->pluck('id')
        ->map(fn ($id) => (int) $id)
        ->all();

    $pendingSlots = [];
    foreach ($validated['slots'] as $slot) {
        $day = strtoupper(trim($slot['day']));
        $time = trim($slot['time']);
        if (!$this->scheduleTimeRangeToMinutes($time)) {
            return response()->json([
                'message' => 'The selected cell has an invalid time range. Refresh the schedule and try again.',
            ], 422);
        }

        $conflict = $this->findFacultyScheduleConflictForCourse(
            (int) $facultySubject->faculty_id,
            $day,
            $time,
            $term->id,
            $facultySubject->course_code,
            $ignoredScheduleIds,
            $allowSameCourseConflict,
            (int) $room->id
        );

        if ($conflict) {
            $sameCourseCode = strtoupper(trim((string) $conflict->course_code))
                === strtoupper(trim((string) $facultySubject->course_code));
            if ($allowSameCourseConflict && $sameCourseCode) {
                $pendingSlots[] = [
                    'faculty_id' => (int) $facultySubject->faculty_id,
                    'day' => $day,
                    'time' => $time,
                ];
                continue;
            }

            $dayName = [
                'MON' => 'Monday', 'TUE' => 'Tuesday', 'WED' => 'Wednesday',
                'THU' => 'Thursday', 'FRI' => 'Friday', 'SAT' => 'Saturday',
            ][$day] ?? $day;
            $roomName = $conflict->room?->room_name;
            $canOverrideSameCourse = $sameCourseCode
                && (int) $conflict->room_id !== (int) $room->id;
            $conflictMessage = 'This instructor is already assigned on ' . $dayName
                . ' from ' . $conflict->time
                . ($roomName ? ' in Room ' . $roomName : ' in another room');
            $conflictMessage .= $canOverrideSameCourse
                ? '. The existing course code is ' . $conflict->course_code . '.'
                : '. Choose a different time or instructor assignment.';

            return response()->json([
                'conflict' => true,
                'message' => $conflictMessage,
                'conflict_schedule' => [
                    'day' => $dayName,
                    'time' => $conflict->time,
                    'room' => $roomName,
                    'course_code' => $conflict->course_code,
                    'room_id' => (int) $conflict->room_id,
                ],
                'can_override_same_course' => $canOverrideSameCourse,
            ]);
        }

        $pendingConflict = $this->findPendingFacultyScheduleConflict(
            (int) $facultySubject->faculty_id,
            $day,
            $time,
            $pendingSlots
        );

        if ($pendingConflict) {
            $dayName = [
                'MON' => 'Monday', 'TUE' => 'Tuesday', 'WED' => 'Wednesday',
                'THU' => 'Thursday', 'FRI' => 'Friday', 'SAT' => 'Saturday',
            ][$day] ?? $day;

            return response()->json([
                'conflict' => true,
                'message' => 'This instructor assignment overlaps another selected cell on ' . $dayName
                    . ' at ' . $time . '. Select non-overlapping cells before confirming.',
            ]);
        }

        $pendingSlots[] = [
            'faculty_id' => (int) $facultySubject->faculty_id,
            'day' => $day,
            'time' => $time,
        ];
    }

    return response()->json(['conflict' => false]);
}

public function clearScheduleCells(Request $request)
{
    if (!$this->isChairUser()) {
        abort(403, 'Unauthorized.');
    }

    $validated = $request->validate([
        'room_id' => ['required', 'integer', 'exists:rooms,id'],
        'term_id' => ['required', 'integer', 'exists:academic_terms,id'],
        'schedule_ids' => ['required', 'array', 'min:1', 'max:100'],
        'schedule_ids.*' => ['required', 'integer', 'distinct', 'exists:schedules,id'],
    ]);

    $chair = Auth::user();
    $room = Room::findOrFail($validated['room_id']);
    if ((int) $room->department_id !== (int) $chair->department_id && !$room->allow_other_departments) {
        return response()->json([
            'message' => 'This college does not allow other colleges to add schedules to this room.',
        ], 403);
    }

    $scheduleIds = collect($validated['schedule_ids'])
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values();

    $schedules = Schedule::whereIn('id', $scheduleIds)
        ->where('room_id', $room->id)
        ->where('department_id', $chair->department_id)
        ->where('academic_term_id', $validated['term_id'])
        ->get();

    if ($schedules->count() !== $scheduleIds->count()) {
        return response()->json([
            'message' => 'One or more selected schedules are no longer editable. Refresh the spreadsheet and try again.',
        ], 422);
    }

    DB::transaction(function () use ($schedules) {
        $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);

        foreach ($schedules as $schedule) {
            $studentUpdates->recordForSchedule(
                $schedule,
                'schedule_removed',
                'Class removed from the timetable',
                trim(($schedule->course_code ?: 'Class') . ' — ' . ($schedule->subject ?: ''))
                    . ' was removed from ' . $schedule->day . ' ' . $schedule->time . '.'
            );

            $schedule->forceDelete();
        }
    });

    return response()->json([
        'success' => true,
        'deleted' => $schedules->count(),
        'message' => 'Selected schedule entries were deleted.',
    ]);
}

 public function saveSchedule(Request $request)
{
    if (!$this->isChairUser()) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | ACTIVE TERM
    |--------------------------------------------------------------------------
    */

    $activeTerm =
        $this->resolveAcademicTerm($request);

    $academicTermId =
        $activeTerm->id;

    $semester =
        $activeTerm->semester;

    $academicYear =
        $activeTerm->academic_year;

    /*
    |--------------------------------------------------------------------------
    | VALIDATE
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'room_id' =>
            'required|exists:rooms,id',

        'semester' =>
            'nullable|string|max:100',

        'academic_year' =>
            'nullable|string|max:100',

        'delete_time_rows' =>
            'nullable|boolean',

        'schedule' =>
            'nullable|array',

        'time_slots' =>
            'nullable|array',

        'time_slots.*.day' =>
            'required|string|max:20',

        'time_slots.*.time' =>
            'required|string|max:50',

        'time_slots.*.row_position' =>
            'required|integer',

        'room_time_slots' =>
            'nullable|array',

        'room_time_slots.*.day' =>
            'required|string|max:20',

        'room_time_slots.*.time' =>
            'required|string|max:50',

        'room_time_slots.*.row_position' =>
            'required|integer',

        'schedule.*.day' =>
            'required|string|max:20',

        'schedule.*.time' =>
            'required|string|max:50',

        'schedule.*.base_time' =>
            'nullable|string|max:50',

        'schedule.*.row_position' =>
            'required|integer',

        'schedule.*.schedule_id' =>
            'nullable|integer|exists:schedules,id',

        'schedule.*.course_code' =>
            'required|string|max:50',

        'schedule.*.faculty_subject_id' =>
            'required|integer|exists:faculty_subjects,id',

        'schedule.*.major' =>
            'nullable|string|max:50',

        'schedule.*.program' =>
            'required|string|max:100',

        'schedule.*.color' =>
            'nullable|string|max:20',

        'schedule.*.subject_type' =>
            'nullable|string|max:50',

        'manual_merges' =>
            'nullable|array|max:100',

        'manual_merges.*.axis' =>
            'required|in:horizontal,vertical',

        'manual_merges.*.cells' =>
            'required|array|min:2|max:100',

        'manual_merges.*.cells.*.day' =>
            'required|in:MON,WED,FRI,TUE,THU,SAT',

        'manual_merges.*.cells.*.side' =>
            'required|in:left,right,sat',

        'manual_merges.*.cells.*.time' =>
            'nullable|string|max:64',

        'manual_merges.*.cells.*.row_position' =>
            'nullable|integer|min:0',
    ]);

    $manualCellMerges = $this->normalizeManualScheduleCellMerges(
        $request->input('manual_merges', []),
        $request->input('room_time_slots', [])
    );

    /*
    |--------------------------------------------------------------------------
    | GET ROOM
    |--------------------------------------------------------------------------
    */

    $room =
        Room::findOrFail(
            $request->room_id
        );

    if ((int) $room->department_id !== (int) $chair->department_id && !$room->allow_other_departments) {
        abort(403, 'This room is view-only until its college allows cross-college scheduling.');
    }

    $roomScheduleConflict = $this->findSubmittedRoomScheduleConflict(
        $request->input('schedule', [])
    );

    if ($roomScheduleConflict) {
        return back()
            ->with('error', $roomScheduleConflict['message'])
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | SYNCHRONIZE OFFICIAL TIME SLOTS
    |--------------------------------------------------------------------------
    |
    | Time rows remain valid timetable slots even when they are empty.
    | This keeps the Chair timetable connected to Faculty time search.
    |
    */
    foreach ($request->input('time_slots', []) as $timeSlotData) {
        $this->syncScheduleTimeSlot(
            $activeTerm,
            strtoupper(trim($timeSlotData['day'] ?? '')),
            trim($timeSlotData['time'] ?? ''),
            isset($timeSlotData['row_position'])
                ? (int) $timeSlotData['row_position']
                : null
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE ROOM-SPECIFIC TIME ROWS
    |--------------------------------------------------------------------------
    |
    | Replace the room's submitted time slots, deduplicating repeated
    | day/start/end combinations before inserting them.
    |
    */

    $preserveDeletedRows = $request->boolean('delete_time_rows')
        || RoomScheduleTimeSlot::where('room_id', $room->id)
            ->where('academic_term_id', $activeTerm->id)
            ->where('day', 'ROW_DELETE')
            ->exists();

    RoomScheduleTimeSlot::where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->delete();

    $roomTimeSlots = $request->input('room_time_slots', []);

    // If room-specific rows were not submitted, use the official rows.
    if (empty($roomTimeSlots)) {
        $roomTimeSlots = $request->input('time_slots', []);
    }

    $uniqueRoomTimeSlots = [];

    foreach ($roomTimeSlots as $roomTimeSlotData) {
        $day = strtoupper(trim($roomTimeSlotData['day'] ?? ''));
        $time = trim($roomTimeSlotData['time'] ?? '');
        $rowPosition = isset($roomTimeSlotData['row_position'])
            ? (int) $roomTimeSlotData['row_position']
            : 0;

        if ($day === '' || $time === '') {
            continue;
        }

        $parts = preg_split('/\s*-\s*/', $time, 2);

        if (count($parts) !== 2) {
            continue;
        }

        [$startTime, $endTime] = $this->normalizeScheduleRangeClocks(trim($parts[0]), trim($parts[1]));

        if (
            $startTime === null ||
            $endTime === null ||
            $endTime <= $startTime
        ) {
            continue;
        }

        // The database unique key is room + term + day + start + end.
        $uniqueKey = implode('|', [
            $day,
            $startTime,
            $endTime,
        ]);

        if (!isset($uniqueRoomTimeSlots[$uniqueKey])) {
            $uniqueRoomTimeSlots[$uniqueKey] = [
                'room_id' => $room->id,
                'academic_term_id' => $activeTerm->id,
                'day' => $day,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'row_position' => $rowPosition,
                'is_active' => true,
            ];
        }
    }

    foreach ($uniqueRoomTimeSlots as $slotData) {
        RoomScheduleTimeSlot::create($slotData);
    }

    if ($preserveDeletedRows) {
        RoomScheduleTimeSlot::create([
            'room_id' => $room->id,
            'academic_term_id' => $activeTerm->id,
            'day' => 'ROW_DELETE',
            'start_time' => '00:00:00',
            'end_time' => '00:01:00',
            'row_position' => -1,
            'is_active' => false,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TIME CONVERTER
    |--------------------------------------------------------------------------
    */

    $timeToMinutes = function ($time) {

        $time =
            trim(
                (string) $time
            );

        if ($time === '') {
            return null;
        }

        if (
            preg_match(
                '/^(\d{1,2}):(\d{2})$/',
                $time,
                $matches
            )
        ) {

            $hour =
                (int) $matches[1];

            $minute =
                (int) $matches[2];

            if (
                $hour >= 1 &&
                $hour <= 6
            ) {
                $hour += 12;
            }

            return
                ($hour * 60)
                +
                $minute;
        }

        return null;
    };

    /*
    |--------------------------------------------------------------------------
    | BLOCK RESERVATION CONFLICTS
    |--------------------------------------------------------------------------
    */

    foreach (
        $request->input(
            'schedule',
            []
        ) as $data
    ) {

        $slotDay =
            strtoupper(
                trim(
                    $data['day'] ?? ''
                )
            );

        $slotTime =
            trim(
                $data['time'] ?? ''
            );

        $slotParts =
            explode(
                '-',
                $slotTime,
                2
            );

        $slotStart =
            $timeToMinutes(
                $slotParts[0] ?? ''
            );

        $slotEnd =
            $timeToMinutes(
                $slotParts[1] ?? ''
            );

        if ($slotStart !== null && $slotEnd !== null && $slotEnd <= $slotStart) {
            $slotEndLabel = trim((string) ($slotParts[1] ?? ''));
            if (!preg_match('/\b(?:AM|PM)\s*$/i', $slotEndLabel)
                && preg_match('/^(\d{1,2})/', $slotEndLabel, $endHourMatch)
                && (int) $endHourMatch[1] >= 1
                && (int) $endHourMatch[1] <= 11) {
                $slotEnd += 12 * 60;
            }
        }

        if (
            !$slotDay ||
            $slotStart === null ||
            $slotEnd === null
        ) {
            continue;
        }

        if (
            $slotEnd <=
            $slotStart
        ) {
            continue;
        }

        $reservationConflict =
            Reservation::where(
                'room_id',
                $room->id
            )
            ->whereIn(
                'status',
                [
                    'Pending',
                    'Approved'
                ]
            )
            ->whereRaw(
                'UPPER(day) = ?',
                [$slotDay]
            )
            ->where(
                function ($query) {

                    $today =
                        now()->format(
                            'Y-m-d'
                        );

                    $nowTime =
                        now()->format(
                            'H:i:s'
                        );

                    $query
                        ->whereDate(
                            'date',
                            '>',
                            $today
                        )
                        ->orWhere(
                            function ($query) use (
                                $today,
                                $nowTime
                            ) {

                                $query
                                    ->whereDate(
                                        'date',
                                        $today
                                    )
                                    ->where(
                                        'end_time',
                                        '>',
                                        $nowTime
                                    );

                            }
                        );

                }
            )
            ->get()
            ->first(
                function (
                    $reservation
                ) use (
                    $slotStart,
                    $slotEnd,
                    $timeToMinutes
                ) {

                    $reservationStart =
                        $timeToMinutes(
                            Carbon::parse(
                                $reservation->start_time
                            )->format('H:i')
                        );

                    $reservationEnd =
                        $timeToMinutes(
                            Carbon::parse(
                                $reservation->end_time
                            )->format('H:i')
                        );

                    if (
                        $reservationStart === null ||
                        $reservationEnd === null
                    ) {
                        return false;
                    }

                    return
                        $slotStart < $reservationEnd
                        &&
                        $slotEnd > $reservationStart;
                }
            );

        if ($reservationConflict) {

            $requestDate =
                Carbon::parse(
                    $reservationConflict->date
                )->format(
                    'M d, Y'
                );

            $requesterName =
                $reservationConflict
                    ->user?->name
                    ??
                    'Instructor';

            return back()
                ->with(
                    'error',
                    'Cannot set this schedule. Room '
                    . $room->room_name
                    . ' has a '
                    . strtolower(
                        $reservationConflict->status
                    )
                    . ' reservation for '
                    . $requesterName
                    . ' on '
                    . $requestDate
                    . ' at '
                    . $reservationConflict->time
                    . '. The reservation must end or be declined before this slot can be scheduled.'
                )
                ->withInput();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMITTED SLOTS
    |--------------------------------------------------------------------------
    */

    $submittedSlots =
        collect(
            $request->input(
                'schedule',
                []
            )
        )
        ->map(
            function ($item) {

                return
                    strtoupper(
                        trim(
                            $item['day'] ?? ''
                        )
                    )
                    . '|'
                    .
                    trim(
                        $item['time'] ?? ''
                    );
            }
        )
        ->filter()
        ->values();

    $submittedScheduleIds = collect($request->input('schedule', []))
        ->pluck('schedule_id')
        ->filter()
        ->map(fn ($id) => (int) $id)
        ->values();
    $studentScheduleEvents = [];

    /*
    |--------------------------------------------------------------------------
    | FACULTY SCHEDULE CONFLICT CHECK
    |--------------------------------------------------------------------------
    |
    | A faculty member cannot teach two schedules at the same time,
    | even when the schedules are in different rooms.
    | This is checked before any existing schedules are removed.
    |
    */
    $pendingFacultySchedules = [];

    foreach ($request->input('schedule', []) as $data) {
        $courseCode = strtoupper(trim($data['course_code'] ?? ''));

        $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])
            ->whereHas('faculty', fn ($query) => $query->active())
            ->find($data['faculty_subject_id'] ?? null);

        if ($facultySubject && (
            strtoupper(trim($facultySubject->course_code)) !== $courseCode
            || !$this->chairCanManageFacultySubject($facultySubject, $chair)
        )) {
            $facultySubject = null;
        }

        if (!$facultySubject) {
            continue;
        }

        $day = strtoupper(trim($data['day'] ?? ''));
        $time = trim($data['time'] ?? '');
        $facultyConflict = $this->findFacultyScheduleConflictForCourse(
            (int) $facultySubject->faculty_id,
            $day,
            $time,
            $academicTermId,
            $courseCode,
            $submittedScheduleIds->all(),
            true,
            (int) $room->id
        );

        if ($facultyConflict) {
            return back()
                ->with(
                    'error',
                    'Schedule conflict: this instructor/instructor already has a schedule on '
                    . $this->formatScheduleConflictMessage($facultyConflict)
                    . '. A instructor cannot have overlapping schedules in different rooms.'
                )
                ->withInput();
        }

        // Also detect two conflicting entries submitted together in the same save.
        $pendingConflict = $this->findPendingFacultyScheduleConflict(
            (int) $facultySubject->faculty_id,
            $day,
            $time,
            $pendingFacultySchedules
        );

        if ($pendingConflict) {
            return back()
                ->with(
                    'error',
                    'Schedule conflict: this instructor/instructor is assigned to more than one room during the overlapping time '
                    . $this->formatScheduleTimeForMessage($day, $time)
                    . '.'
                )
                ->withInput();
        }

        $pendingFacultySchedules[] = [
            'faculty_id' => (int) $facultySubject->faculty_id,
            'day' => $day,
            'time' => $time,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE ONLY CURRENT TERM SCHEDULES
    |--------------------------------------------------------------------------
    */

    Schedule::where(
        'room_id',
        $room->id
    )
    ->where(
        'department_id',
        $chair->department_id
    )
    ->where(
        'academic_term_id',
        $academicTermId
    )
    ->get()
    ->each(
        function (
            $existingSchedule
        ) use (
            $submittedSlots,
            $submittedScheduleIds,
            &$studentScheduleEvents
        ) {

            if ($submittedScheduleIds->contains((int) $existingSchedule->id)) {
                return;
            }

            $slotKey =
                strtoupper(
                    trim(
                        $existingSchedule->day
                    )
                )
                . '|'
                .
                trim(
                    $existingSchedule->time
                );

            if (
                !$submittedSlots->contains(
                    $slotKey
                )
            ) {
                $studentScheduleEvents[] = [
                    'event' => 'removed',
                    'schedule' => clone $existingSchedule,
                ];
                $existingSchedule->forceDelete();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | PROCESS SCHEDULES
    |--------------------------------------------------------------------------
    */

    foreach (
        $request->input('schedule', []) as $data
    ) {

        $courseCode =
            strtoupper(
                trim(
                    $data['course_code']
                )
            );

        /*
        |--------------------------------------------------------------------------
        | FIND FACULTY SUBJECT
        |--------------------------------------------------------------------------
        */

        $facultySubject = FacultySubject::with('faculty')
            ->whereHas('faculty', fn ($query) => $query->active())
            ->find($data['faculty_subject_id'] ?? null);

        if ($facultySubject && (
            strtoupper(trim($facultySubject->course_code)) !== $courseCode
            || !$this->chairCanManageFacultySubject($facultySubject, $chair)
        )) {
            $facultySubject = null;
        }

        if (!$facultySubject) {

            return back()
                ->with(
                    'error',
                    'Course code "'
                    . $courseCode
                    . '" is not assigned to any instructor in your college.'
                )
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | DAY AND TIME
        |--------------------------------------------------------------------------
        */

        $day =
            strtoupper(
                trim(
                    $data['day']
                )
            );

        $time =
            trim(
                $data['time']
            );

        // Keep official search slots aligned with the underlying hourly row;
        // the schedule's own `time` value may now end or start on a half hour.
        $this->syncScheduleTimeSlot(
            $activeTerm,
            $day,
            trim($data['base_time'] ?? $time),
            isset($data['row_position'])
                ? (int) $data['row_position']
                : null
        );

        $scheduleId =
            $data['schedule_id']
            ??
            null;

        /*
        |--------------------------------------------------------------------------
        | FIND EXISTING SCHEDULE IN SAME TERM ONLY
        |--------------------------------------------------------------------------
        */

        $existing =
            null;

        if ($scheduleId) {

            $existing =
                Schedule::where(
                    'id',
                    $scheduleId
                )
                ->where(
                    'room_id',
                    $room->id
                )
                ->where(
                    'academic_term_id',
                    $academicTermId
                )
                ->first();
        }

        if (!$existing) {

            $existing =
                Schedule::where(
                    'room_id',
                    $room->id
                )
                ->where(
                    'day',
                    $day
                )
                ->where(
                    'row_position',
                    (int) $data['row_position']
                )
                ->where(
                    'time',
                    $time
                )
                ->where(
                    'academic_term_id',
                    $academicTermId
                )
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | OTHER DEPARTMENT PROTECTION
        |--------------------------------------------------------------------------
        */

        if (
            $existing &&
            (int) $existing->department_id !==
            (int) $chair->department_id
        ) {

            return back()
                ->with(
                    'error',
                    'This schedule belongs to another college and cannot be changed.'
                )
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | SCHEDULE DATA
        |--------------------------------------------------------------------------
        */

        $scheduleData = [

            'academic_term_id' =>
                $academicTermId,

            'day' =>
                $day,

            'time' =>
                $time,

            'row_position' =>
                (int) $data['row_position'],

            'faculty_id' =>
                $facultySubject->faculty_id,

            'department_id' =>
                $chair->department_id,

            'semester' =>
                $semester,

            'academic_year' =>
                $academicYear,

            'faculty_subject_id' =>
                $facultySubject->id,

            'course_code' =>
                $facultySubject->course_code,

            'subject' =>
                $facultySubject->subject,

            'year_level' =>
                $facultySubject->year_level,

            'major' =>
                $facultySubject->major,

            'program' =>
                $facultySubject->program,

            'subject_type' =>
                $facultySubject->subject_type
                    ?: (preg_match('/\.1\s*$/', trim($courseCode)) === 1 ? 'Lab' : 'Lec'),

            'instructor' =>
                $facultySubject
                    ->faculty
                    ->name,

            'color' =>
                $data['color']
                ??
                '#ffffff',
        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        if ($existing) {
            $previousSchedule = clone $existing;
            $previousSharedAudiences = $existing->merges()
                ->where('share_type', 'same_faculty')
                ->where('status', 'approved')
                ->with('subject.program')
                ->get()
                ->map(function ($merge) {
                    $subject = $merge->subject;
                    return [
                        'id' => (int) $merge->subject_id,
                        'department_id' => (int) ($subject?->program?->department_id ?? $merge->department_id),
                        'program' => trim((string) ($subject?->program?->name ?? '')),
                        'year_level' => trim((string) ($subject?->year_level ?? '')),
                    ];
                });
            $cohortChanged = (int) $existing->department_id !== (int) $chair->department_id
                || trim((string) $existing->program) !== trim((string) $scheduleData['program'])
                || trim((string) $existing->year_level) !== trim((string) $scheduleData['year_level']);
            $existing->update(
                $scheduleData
            );
            $this->syncSameFacultyScheduleAudiences($existing, $facultySubject, $chair);
            $currentSharedIds = $facultySubject->sharedCohorts()->pluck('subjects.id')->map(fn ($id) => (int) $id)->all();
            $removedSharedAudiences = $previousSharedAudiences
                ->reject(fn ($audience) => in_array($audience['id'], $currentSharedIds, true))
                ->all();

            $importantFields = ['room_id', 'faculty_id', 'day', 'time', 'course_code', 'subject', 'program', 'year_level', 'major', 'instructor'];
            $hasStudentFacingChange = collect($importantFields)->contains(
                fn ($field) => (string) $previousSchedule->getAttribute($field) !== (string) $existing->getAttribute($field)
            );
            if ($hasStudentFacingChange || $previousSharedAudiences->pluck('id')->map(fn ($id) => (int) $id)->sort()->values()->all() !== collect($currentSharedIds)->sort()->values()->all()) {
                $studentScheduleEvents[] = [
                    'event' => 'updated',
                    'previous' => $cohortChanged ? $previousSchedule : null,
                    'schedule' => $existing->fresh(),
                    'removed_shared_audiences' => $removedSharedAudiences,
                ];
            }

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        $createdSchedule = Schedule::create(
            array_merge(
                $scheduleData,
                [
                    'room_id' =>
                        $room->id,

                    'day' =>
                        $day,

                    'time' =>
                        $time,
                ]
            )
        );
        $this->syncSameFacultyScheduleAudiences($createdSchedule, $facultySubject, $chair);
        $studentScheduleEvents[] = [
            'event' => 'created',
            'schedule' => $createdSchedule,
        ];
    }

    $studentUpdateService = app(\App\Services\StudentScheduleUpdateService::class);
    foreach ($studentScheduleEvents as $studentEvent) {
        $changedSchedule = $studentEvent['schedule'];
        if ($studentEvent['event'] === 'removed') {
            $studentUpdateService->recordForSchedule(
                $changedSchedule,
                'schedule_removed',
                'Class removed from the timetable',
                trim(($changedSchedule->course_code ?: 'Class') . ' — ' . ($changedSchedule->subject ?: ''))
                    . ' was removed from ' . $changedSchedule->day . ' ' . $changedSchedule->time . '.'
            );
            continue;
        }

        if (!empty($studentEvent['previous'])) {
            $previous = $studentEvent['previous'];
            $studentUpdateService->recordForSchedule(
                $previous,
                'schedule_updated',
                'Class schedule changed',
                trim(($previous->course_code ?: 'Class') . ' — ' . ($previous->subject ?: ''))
                    . ' has an updated timetable. Check the current class schedule for the latest details.'
            );
        }

        $created = $studentEvent['event'] === 'created';
        $studentUpdateService->recordForSchedule(
            $changedSchedule,
            $created ? 'schedule_published' : 'schedule_updated',
            $created ? 'New class schedule published' : 'Class schedule updated',
            trim(($changedSchedule->course_code ?: 'Class') . ' — ' . ($changedSchedule->subject ?: ''))
                . ' is scheduled for ' . $changedSchedule->day . ' ' . $changedSchedule->time
                . ($studentUpdateService->roomNameFor($changedSchedule) ? ' in ' . $studentUpdateService->roomNameFor($changedSchedule) : '') . '.'
        );
        foreach ($studentEvent['removed_shared_audiences'] ?? [] as $audience) {
            if ($audience['department_id'] === (int) $changedSchedule->department_id
                && mb_strtolower($audience['program']) === mb_strtolower(trim((string) $changedSchedule->program))
                && mb_strtolower($audience['year_level']) === mb_strtolower(trim((string) $changedSchedule->year_level))) {
                continue;
            }
            $studentUpdateService->recordForAudience(
                $changedSchedule,
                $audience['department_id'],
                $audience['program'],
                $audience['year_level'],
                'schedule_updated',
                'Shared class audience changed',
                trim(($changedSchedule->course_code ?: 'Class') . ' — ' . ($changedSchedule->subject ?: ''))
                    . ' is no longer shared with your program for ' . $changedSchedule->day . ' ' . $changedSchedule->time . '.'
            );
        }
    }

    DB::transaction(function () use ($room, $academicTermId, $manualCellMerges) {
        ScheduleCellMerge::where('room_id', $room->id)
            ->where('academic_term_id', $academicTermId)
            ->delete();

        foreach ($manualCellMerges as $merge) {
            ScheduleCellMerge::create([
                'room_id' => $room->id,
                'academic_term_id' => $academicTermId,
                'axis' => $merge['axis'],
                'cells' => $merge['cells'],
            ]);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('chair.excel', array_filter([
            'room' => $room->id,
            'term_id' => $activeTerm->id,
            'mode' => $request->input('editor_mode') === 'cross' ? 'cross' : null,
        ]))
        ->with(
            'success',
            'Schedule uploaded successfully.'
        );
}

private function syncSameFacultyScheduleAudiences(Schedule $schedule, FacultySubject $facultySubject, User $chair): void
{
    $cohorts = $facultySubject->sharedCohorts()->with('program')->get();
    $legacyCohorts = FacultySubject::with('subjectCatalog.program')
        ->where('faculty_id', $facultySubject->faculty_id)
        ->whereRaw('UPPER(course_code) = ?', [strtoupper(trim((string) $facultySubject->course_code))])
        ->where('id', '!=', $facultySubject->id)
        ->get()
        ->filter(function (FacultySubject $assignment) use ($chair, $facultySubject) {
            return $assignment->subjectCatalog
                && (int) $assignment->subjectCatalog->program?->department_id === (int) $chair->department_id
                && mb_strtolower(trim((string) $assignment->subject)) === mb_strtolower(trim((string) $facultySubject->subject));
        })
        ->map(fn (FacultySubject $assignment) => $assignment->subjectCatalog);
    $cohorts = $cohorts->concat($legacyCohorts)->unique('id')->values();
    $cohortIds = $cohorts->pluck('id')->map(fn ($id) => (int) $id)->all();

    $existing = $schedule->merges()
        ->where('share_type', 'same_faculty')
        ->get();

    foreach ($existing as $merge) {
        if (!in_array((int) $merge->subject_id, $cohortIds, true)) {
            $merge->delete();
        }
    }

    foreach ($cohorts as $cohort) {
        if ((int) $cohort->id === (int) $facultySubject->subject_id) continue;

        $merge = $schedule->merges()
            ->where('share_type', 'same_faculty')
            ->where('subject_id', $cohort->id)
            ->first();

        if ($merge) {
            if ($merge->status !== 'approved') {
                $merge->update([
                    'status' => 'approved',
                    'approved_by' => $chair->id,
                    'approved_at' => now(),
                ]);
            }
            continue;
        }

        ScheduleMerge::create([
            'schedule_id' => $schedule->id,
            'department_id' => $cohort->program?->department_id ?? $chair->department_id,
            'requested_by' => $chair->id,
            'faculty_subject_id' => $facultySubject->id,
            'subject_id' => $cohort->id,
            'status' => 'approved',
            'share_type' => 'same_faculty',
            'approved_by' => $chair->id,
            'approved_at' => now(),
        ]);
    }
}

private function publishFacultySubjectCohortChanges(FacultySubject $facultySubject, User $chair): void
{
    $activeTermId = AcademicTerm::where('is_active', true)->value('id');
    if (!$activeTermId) return;

    $schedules = Schedule::where('faculty_subject_id', $facultySubject->id)
        ->where('academic_term_id', $activeTermId)
        ->get();
    $updates = app(\App\Services\StudentScheduleUpdateService::class);

    foreach ($schedules as $schedule) {
        $this->syncSameFacultyScheduleAudiences($schedule, $facultySubject, $chair);
        $schedule->refresh();
        $updates->recordForSchedule(
            $schedule,
            'schedule_updated',
            'Class schedule now serves additional groups',
            trim(($schedule->course_code ?: 'Class') . ' — ' . ($schedule->subject ?: ''))
                . ' is also available to additional program or major groups for ' . $schedule->day . ' ' . $schedule->time . '.'
        );
    }
}

private function normalizeManualScheduleCellMerges(array $merges, array $roomTimeSlots = []): array
{
    $normalized = [];
    $occupiedCells = [];
    $roomRowsByDayAndTime = [];
    foreach ($roomTimeSlots as $slot) {
        $slotDay = strtoupper(trim((string) ($slot['day'] ?? '')));
        $slotTime = trim((string) ($slot['time'] ?? ''));
        $slotPosition = $slot['row_position'] ?? null;
        if ($slotDay === '' || $slotTime === '' || !is_numeric($slotPosition)) {
            continue;
        }

        $slotParts = preg_split('/\s*[-–—]\s*/u', $slotTime, 2);
        if (count($slotParts) !== 2) {
            continue;
        }
        [$slotStart, $slotEnd] = $this->normalizeScheduleRangeClocks(trim($slotParts[0]), trim($slotParts[1]));
        if ($slotStart === null || $slotEnd === null || $slotEnd <= $slotStart) {
            continue;
        }

        $timeKey = $slotStart . '|' . $slotEnd;
        $roomRowsByDayAndTime[$slotDay][$timeKey] = (int) $slotPosition;
    }

    $dayColumns = [
        'left' => ['MON', 'WED', 'FRI'],
        'right' => ['TUE', 'THU'],
        'sat' => ['SAT'],
    ];

    foreach ($merges as $merge) {
        $axis = $merge['axis'] ?? null;
        $cells = collect($merge['cells'] ?? [])
            ->map(fn ($cell) => [
                'day' => strtoupper(trim((string) ($cell['day'] ?? ''))),
                'side' => strtolower(trim((string) ($cell['side'] ?? ''))),
                'time' => trim((string) ($cell['time'] ?? '')),
                'row_position' => isset($cell['row_position']) && is_numeric($cell['row_position'])
                    ? (int) $cell['row_position']
                    : null,
            ])
            ->values()
            ->all();

        if (!in_array($axis, ['horizontal', 'vertical'], true) || count($cells) < 2) {
            throw ValidationException::withMessages([
                'manual_merges' => 'One of the saved cell merges is incomplete. Please merge the cells again and publish.',
            ]);
        }

        // The timetable column is determined by the day. Normalize it here
        // so a stale or blank DOM side value cannot invalidate an otherwise
        // valid merge coordinate.
        foreach ($cells as $cellIndex => $cell) {
            foreach ($dayColumns as $side => $days) {
                if (in_array($cell['day'], $days, true)) {
                    $cells[$cellIndex]['side'] = $side;
                    break;
                }
            }
        }

        $keys = [];
        foreach ($cells as $cellIndex => $cell) {
            if (($cell['row_position'] === null || $cell['row_position'] < 0) && $cell['time'] !== '') {
                $timeParts = preg_split('/\s*[-–—]\s*/u', $cell['time'], 2);
                if (count($timeParts) === 2) {
                    [$start, $end] = $this->normalizeScheduleRangeClocks(trim($timeParts[0]), trim($timeParts[1]));
                    $timeKey = $start && $end ? $start . '|' . $end : null;
                    $matchedPosition = $timeKey === null
                        ? null
                        : ($roomRowsByDayAndTime[$cell['day']][$timeKey] ?? null);
                    if ($matchedPosition !== null) {
                        $cell['row_position'] = $matchedPosition;
                        $cells[$cellIndex]['row_position'] = $matchedPosition;
                    }
                }
            }

            if ($cell['row_position'] === null || $cell['row_position'] < 0) {
                $knownIndex = null;
                $knownPosition = null;
                foreach ($cells as $siblingIndex => $sibling) {
                    if ($sibling['row_position'] !== null && $sibling['row_position'] >= 0) {
                        $knownIndex = $siblingIndex;
                        $knownPosition = $sibling['row_position'];
                        break;
                    }
                }

                if ($knownIndex !== null && $knownPosition !== null) {
                    $inferredPosition = $axis === 'horizontal'
                        ? $knownPosition
                        : $knownPosition + ($cellIndex - $knownIndex);
                    if ($inferredPosition >= 0) {
                        $cell['row_position'] = $inferredPosition;
                        $cells[$cellIndex]['row_position'] = $inferredPosition;
                    }
                }
            }

            $validDaySide = in_array($cell['day'], $dayColumns[$cell['side']] ?? [], true);
            if (!$validDaySide || $cell['row_position'] === null || $cell['row_position'] < 0) {
                throw ValidationException::withMessages([
                    'manual_merges' => 'A merged cell could not be matched to its time row. Please merge the cells again and publish.',
                ]);
            }

            $key = implode('|', [$cell['day'], $cell['side'], $cell['row_position']]);
            if (isset($occupiedCells[$key]) || in_array($key, $keys, true)) {
                throw ValidationException::withMessages([
                    'manual_merges' => 'Two saved merges overlap. Please correct the merged cells and publish again.',
                ]);
            }

            $keys[] = $key;
        }

        if ($axis === 'horizontal') {
            $sides = array_unique(array_column($cells, 'side'));
            $rows = array_unique(array_column($cells, 'row_position'));
            $side = $cells[0]['side'];
            $columnPositions = collect($cells)
                ->map(fn ($cell) => array_search($cell['day'], $dayColumns[$side] ?? [], true))
                ->sort()
                ->values()
                ->all();

            if (
                count($sides) !== 1
                || count($rows) !== 1
                || in_array(false, $columnPositions, true)
                || collect($columnPositions)->unique()->count() !== count($cells)
                || collect($columnPositions)->last() - collect($columnPositions)->first() + 1 !== count($cells)
            ) {
                throw ValidationException::withMessages([
                    'manual_merges' => 'A horizontal merge must contain adjacent cells in one timetable row.',
                ]);
            }
        } else {
            $days = array_unique(array_column($cells, 'day'));
            $sides = array_unique(array_column($cells, 'side'));
            $rowPositions = collect($cells)
                ->pluck('row_position')
                ->unique()
                ->sort()
                ->values();

            if (
                count($days) !== 1
                || count($sides) !== 1
                || $rowPositions->count() !== count($cells)
                || $rowPositions->last() - $rowPositions->first() + 1 !== count($cells)
            ) {
                throw ValidationException::withMessages([
                    'manual_merges' => 'A vertical merge must contain adjacent cells in one timetable column.',
                ]);
            }
        }

        foreach ($keys as $key) {
            $occupiedCells[$key] = true;
        }

        $normalized[] = [
            'axis' => $axis,
            'cells' => $cells,
        ];
    }

    return $normalized;
}

public function viewSchedule(Room $room)
{
    $this->expireReservations();
    RoomSwapRequest::expireElapsedTemporarySwaps();

    $activeTerm = $this->resolveAcademicTerm();

    $room->load([
        'department',
        'building',
    ]);

    /*
|--------------------------------------------------------------------------
| LOAD ROOM SCHEDULES + ACTIVE ROOM REASSIGNMENTS
|--------------------------------------------------------------------------
|
| IMPORTANT:
| The original schedules table is NOT modified.
| If a schedule was reassigned TO this room, we display it here
| as a temporary/virtual schedule.
|
*/

$normalSchedules = $this->termScheduleQuery(
    Schedule::with([
        'merges' => function ($query) {
            $query->where('status', 'approved')
                ->with(['department', 'facultySubject']);
        }
    ])->where('room_id', $room->id),
    $activeTerm
)
    ->orderByRaw(
        'CASE WHEN row_position IS NULL THEN 999 ELSE row_position END'
    )
    ->orderBy('id')
    ->get();


/*
|--------------------------------------------------------------------------
| ACTIVE ROOM REASSIGNMENTS FOR THIS ROOM
|--------------------------------------------------------------------------
*/

$activeReassignments = RoomReassignment::with([
    'schedule.merges' => function ($query) {
        $query->where('status', 'approved')
            ->with([
                'department',
                'facultySubject',
            ]);
    },
    'originalRoom',
    'newRoom',
])
    ->active()
    ->where(function ($query) use ($room) {

        $query
            ->where('original_room_id', $room->id)
            ->orWhere('new_room_id', $room->id);

    })
    ->whereHas('schedule', function ($query) use ($activeTerm) {

        $query->where(
            'academic_term_id',
            $activeTerm->id
        );

    })
    ->get();


/*
|--------------------------------------------------------------------------
| REMOVE ORIGINAL SCHEDULE FROM ORIGINAL ROOM
|--------------------------------------------------------------------------
|
| Example:
|
| Room FSM
|   IT101 8:00-9:00
|
| reassigned to:
|
| Room 201
|
| The original schedule remains in the database,
| but it should no longer be displayed inside FSM
| while the reassignment is active.
|
*/

$movedScheduleIds = $activeReassignments
    ->where('original_room_id', $room->id)
    ->pluck('schedule_id')
    ->map(fn ($id) => (int) $id)
    ->all();


$schedules = $normalSchedules
    ->reject(function ($schedule) use ($movedScheduleIds) {

        return in_array(
            (int) $schedule->id,
            $movedScheduleIds,
            true
        );

    })
    ->values();


/*
|--------------------------------------------------------------------------
| ADD REASSIGNED SCHEDULE TO NEW ROOM
|--------------------------------------------------------------------------
|
| The original schedules row is NOT changed.
| We only create a temporary in-memory copy.
|
*/

foreach ($activeReassignments as $reassignment) {

    if (
        (int) $reassignment->new_room_id
        !==
        (int) $room->id
    ) {
        continue;
    }


    if (!$reassignment->schedule) {
        continue;
    }


    $virtualSchedule = clone $reassignment->schedule;


    /*
    |--------------------------------------------------------------------------
    | MAKE THE COPIED SCHEDULE BELONG TO THIS ROOM
    |--------------------------------------------------------------------------
    */

    $virtualSchedule->room_id = $room->id;


    /*
    |--------------------------------------------------------------------------
    | KEEP THE ORIGINAL DAY AND TIME
    |--------------------------------------------------------------------------
    */

    $virtualSchedule->day =
        strtoupper(
            trim(
                (string) $reassignment->day
            )
        );


    $virtualSchedule->time =
        trim(
            (string) $reassignment->start_time
        )
        .
        ' - '
        .
        trim(
            (string) $reassignment->end_time
        );


    /*
    |--------------------------------------------------------------------------
    | MARK AS REASSIGNED
    |--------------------------------------------------------------------------
    */

    $virtualSchedule->is_reassigned = true;

    $virtualSchedule->reassignment_id =
        $reassignment->id;

    $virtualSchedule->original_room_id =
        $reassignment->original_room_id;

    $virtualSchedule->reassigned_from_room =
        $reassignment->originalRoom;


    /*
    |--------------------------------------------------------------------------
    | ADD TO CURRENT ROOM'S SCHEDULE LIST
    |--------------------------------------------------------------------------
    */

    $schedules->push(
        $virtualSchedule
    );
}


/*
|--------------------------------------------------------------------------
| SORT FINAL ROOM SCHEDULE
|--------------------------------------------------------------------------
*/

$schedules = $schedules
    ->sortBy(function ($schedule) {

        return [
            $schedule->row_position ?? 999999,
            strtoupper(
                trim(
                    (string) $schedule->day
                )
            ),
            (string) $schedule->time,
        ];

    })
    ->values();

    $merges = ScheduleMerge::with([
        'department',
        'subject.program.department',
        'facultySubject.subjectCatalog.program.department',
    ])
        ->whereIn('schedule_id', $schedules->pluck('id'))
        ->where('status', 'approved')
        ->get();

    $mergedPrograms = [];
    $mergedCourses = [];

    foreach ($merges as $merge) {
        $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
        $program = $subject?->program?->code
            ?? $merge->facultySubject?->subjectCatalog?->program?->code
            ?? $merge->facultySubject?->program;
        if ($program) {
            $mergedPrograms[$merge->schedule_id] ??= [];
            if (!in_array($program, $mergedPrograms[$merge->schedule_id], true)) {
                $mergedPrograms[$merge->schedule_id][] = $program;
            }
        }

        if ($subject) {
            $mergedCourses[$merge->schedule_id] ??= [];
            $mergedCourses[$merge->schedule_id][] = [
                'course_code' => $subject->course_code ?? $merge->facultySubject?->course_code,
                'course_title' => $subject->course_title ?? $merge->facultySubject?->subject,
                'program' => $program,
                'major' => $subject->program?->major ?? $merge->facultySubject?->major,
                'share_type' => $merge->share_type ?? 'merge',
            ];
        }
    }

    $activeRoomSwaps = RoomSwapRequest::with([
        'requesterRoom',
        'targetRoom',
    ])
        ->where('status', 'approved')
        ->where('swap_type', 'temporary')
        ->where(function ($query) use ($schedules) {
            $query->whereIn('requester_schedule_id', $schedules->pluck('id'))
                ->orWhereIn('target_schedule_id', $schedules->pluck('id'));
        })
        ->whereHas('requesterSchedule', fn ($query) => $query->where('academic_term_id', $activeTerm->id))
        ->whereHas('targetSchedule', fn ($query) => $query->where('academic_term_id', $activeTerm->id))
        ->get()
        ->filter(function ($swap) {
            try {
                $date = $swap->getRawOriginal('swap_date');
                $start = $swap->getRawOriginal('start_time');
                $end = $swap->getRawOriginal('end_time');

                if (!$date || !$start || !$end) {
                    return false;
                }

                return Carbon::now()->between(
                    Carbon::parse($date . ' ' . $start),
                    Carbon::parse($date . ' ' . $end),
                    true
                );
            } catch (\Throwable $e) {
                return false;
            }
        })
        ->values();

    $roomSwapsBySchedule = [];

    foreach ($activeRoomSwaps as $swap) {
        $swapData = [
            'swap_type' => 'temporary',
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

        if ($swap->requester_schedule_id) {
            $roomSwapsBySchedule[$swap->requester_schedule_id] = array_merge(
                $swapData,
                [
                    'original_room_id' => $swap->requester_room_id,
                    'original_room_name' => $swap->requesterRoom?->room_name ?? 'Original room',
                ]
            );
        }

        if ($swap->target_schedule_id) {
            $roomSwapsBySchedule[$swap->target_schedule_id] = array_merge(
                $swapData,
                [
                    'original_room_id' => $swap->target_room_id,
                    'original_room_name' => $swap->targetRoom?->room_name ?? 'Original room',
                ]
            );
        }
    }

    $temporaryReservations = Reservation::with([
        'user',
        'facultySubject',
        'subject.program',
    ])
        ->where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->where('status', 'Reserved')
        ->whereDate('date', Carbon::today()->format('Y-m-d'))
        ->get()
        ->filter(function ($reservation) {
            if (!$reservation->start_time || !$reservation->end_time) {
                return false;
            }

            try {
                $end = Carbon::parse(
                    Carbon::parse($reservation->date)->format('Y-m-d') .
                    ' ' .
                    $reservation->end_time
                );

                return $end->greaterThan(Carbon::now());
            } catch (\Throwable $e) {
                return false;
            }
        })
        ->values();

    $schedules->loadMissing('facultySubject.subjectCatalog.program');
    $programCodesByName = Program::query()
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

    $scheduleData = $schedules->map(function ($schedule) use ($mergedPrograms, $mergedCourses, $roomSwapsBySchedule, $programCodeForSchedule) {
        return [
            'id' => $schedule->id,
            'course_code' => $schedule->course_code,
            'subject' => $schedule->subject,
            'subject_type' => $schedule->subject_type,
            'program' => $schedule->program,
            'program_code' => $programCodeForSchedule($schedule),
            'major' => $schedule->major,
            'day' => strtoupper(trim((string) $schedule->day)),
            'time' => trim((string) $schedule->time),
            'row_position' => $schedule->row_position !== null
                ? (int) $schedule->row_position
                : null,
            'instructor' => $schedule->instructor,
            'color' => $schedule->color ?? '#ffffff',
            'merged_programs' => $mergedPrograms[$schedule->id] ?? [],
            'merged_courses' => $mergedCourses[$schedule->id] ?? [],
            'room_swap' => $roomSwapsBySchedule[$schedule->id] ?? null,
        ];
    })->values();

    $reservationData = $temporaryReservations->map(function ($reservation) {
        return [
            'id' => $reservation->id,
            'status' => $reservation->status,
            'date' => $reservation->date
                ? Carbon::parse($reservation->date)->format('Y-m-d')
                : null,
            'day' => strtoupper(trim((string) ($reservation->day ?? Carbon::parse($reservation->date)->format('D')))),
            'time' => trim((string) $reservation->time),
            'start_time' => $reservation->start_time
                ? Carbon::parse($reservation->start_time)->format('H:i')
                : null,
            'end_time' => $reservation->end_time
                ? Carbon::parse($reservation->end_time)->format('H:i')
                : null,
            'purpose' => $reservation->purpose ?? 'Room Reservation',
            'user_name' => $reservation->user?->name ?? 'Instructor',
            'course_code' => $reservation->subject?->course_code ?? $reservation->facultySubject?->course_code,
            'subject' => $reservation->subject?->course_title ?? $reservation->facultySubject?->subject,
        ];
    })->values();

    $buildingName = 'Building';

    if ($room->building) {
        $buildingName = is_object($room->building)
            ? ($room->building->name ?? $room->building->building_name ?? 'Building')
            : $room->building;
    }

    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'room' => [
                'id' => $room->id,
                'room_name' => $room->room_name,
                'building' => $buildingName,
                'department' => $room->department?->name ?? 'College',
            ],
            'schedules' => $scheduleData,
            'reservations' => $reservationData,
            'active_term' => [
                'id' => $activeTerm->id,
                'academic_year' => $activeTerm->academic_year,
                'semester' => $activeTerm->semester,
            ],
        ]);
    }

    return view(
        'faculty.view-schedule',
        compact('room', 'schedules', 'activeTerm')
    );
}

public function facultySchedules(Request $request)
{
    $activeTerm = $this->resolveAcademicTerm($request);

    $rooms = Room::with([
        'department',
        'building',
        'schedules' => function ($query) use ($activeTerm) {
            $this->termScheduleQuery($query, $activeTerm)
                ->orderBy('row_position')
                ->orderBy('day')
                ->orderBy('time');
        }
    ])
        ->orderBy('department_id')
        ->orderBy('building_id')
        ->orderBy('room_name')
        ->get();

    $departments = $rooms
        ->groupBy('department_id')
        ->map(function ($departmentRooms, $departmentId) {
            $department = $departmentRooms->first()->department;

            $buildings = $departmentRooms
                ->groupBy('building_id')
                ->map(function ($buildingRooms, $buildingId) {
                    $firstRoom = $buildingRooms->first();

                    $buildingName = is_string($firstRoom->building)
                        ? $firstRoom->building
                        : ($firstRoom->building?->building_name
                            ?? $firstRoom->building?->name
                            ?? 'Building');

                    return [
                        'id' => $buildingId,
                        'name' => $buildingName,
                        'rooms' => $buildingRooms,
                    ];
                })
                ->values();

            return [
                'id' => $departmentId,
                'name' => $department?->name ?? 'College',
                'rooms' => $departmentRooms,
                'buildings' => $buildings,
            ];
        })
        ->values();

    return view(
        'faculty.schedules',
        compact('rooms', 'departments', 'activeTerm')
    );
}



public function deleteSchedule(Room $room)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $activeTerm = $this->resolveAcademicTerm(request());

    $roomSchedules = Schedule::where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->get();
    foreach ($roomSchedules as $schedule) {
        app(\App\Services\StudentScheduleUpdateService::class)->recordForSchedule(
            $schedule,
            'schedule_removed',
            'Class removed from the timetable',
            trim(($schedule->course_code ?: 'Class') . ' — ' . ($schedule->subject ?: ''))
                . ' was removed from ' . $schedule->day . ' ' . $schedule->time . '.'
        );
        $schedule->delete();
    }

    return redirect()
        ->route('chair.setschedule')
        ->with('success', 'Schedule for the active term deleted successfully.');
}


public function reserveRoom(Request $request, Room $room)
{
    // Clean up reservations that already reached their end time.
    $this->expireReservations();

    $request->validate([
        'date' => 'required|date',
        'time_slot_id' => 'required|integer|exists:schedule_time_slots,id',
        'faculty_subject_id' => 'required|integer|exists:faculty_subjects,id',
        'schedule_id' => 'nullable|integer|exists:schedules,id',
    ]);

    $faculty = Auth::user();

    if (!$faculty) {
        return redirect()->route('login');
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE ACADEMIC TERM
    |--------------------------------------------------------------------------
    | The reservation must always belong to the currently selected/active
    | academic term. The recurring Chair schedule is never modified by a
    | reservation.
    |--------------------------------------------------------------------------
    */

    $activeTerm = $this->resolveAcademicTerm($request);

    /*
    |--------------------------------------------------------------------------
    | SUBJECT MUST BELONG TO THE LOGGED-IN FACULTY
    |--------------------------------------------------------------------------
    */

    $facultySubject = FacultySubject::with([
        'faculty',
        'subjectCatalog.program',
    ])
        ->where('id', $request->faculty_subject_id)
        ->where('faculty_id', $faculty->id)
        ->first();

    if (!$facultySubject) {
        return back()->with(
            'error',
            'The selected subject is not assigned to your instructor account.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | OFFICIAL TIME SLOT
    |--------------------------------------------------------------------------
    | The browser is not allowed to define the reservation time. The selected
    | time_slot_id must belong to the active academic term and be active.
    |--------------------------------------------------------------------------
    */

    $selectedTimeSlot = ScheduleTimeSlot::query()
        ->where('id', (int) $request->time_slot_id)
        ->where('academic_term_id', $activeTerm->id)
        ->where('is_active', true)
        ->first();

    if (!$selectedTimeSlot) {
        return back()->with(
            'error',
            'The selected time slot is no longer available in the active academic term.'
        );
    }

    $day = strtoupper(trim((string) $selectedTimeSlot->day));

    try {
        $date = Carbon::parse($request->date)->startOfDay();
    } catch (\Throwable $e) {
        return back()->with(
            'error',
            'The selected reservation date is invalid.'
        );
    }

    if ($date->lt(Carbon::today())) {
        return back()->with(
            'error',
            'The reservation date cannot be in the past.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DATE MUST MATCH THE SELECTED WEEKDAY
    |--------------------------------------------------------------------------
    */

    $dateDay = strtoupper($date->format('D'));

    if ($dateDay !== $day) {
        return back()->with(
            'error',
            'The selected reservation date does not match the selected weekday.'
        );
    }

    try {
        $startTime = Carbon::parse($selectedTimeSlot->start_time)->format('H:i');
        $endTime = Carbon::parse($selectedTimeSlot->end_time)->format('H:i');
    } catch (\Throwable $e) {
        return back()->with(
            'error',
            'The selected time slot has an invalid time format.'
        );
    }

    $reservationStart = Carbon::parse(
        $date->format('Y-m-d') . ' ' . $startTime
    );

    $reservationEnd = Carbon::parse(
        $date->format('Y-m-d') . ' ' . $endTime
    );

    if (!$reservationEnd->greaterThan($reservationStart)) {
        return back()->with(
            'error',
            'The selected time slot has an invalid time range.'
        );
    }

    $reservationTime = $startTime . '-' . $endTime;

    /*
    |--------------------------------------------------------------------------
    | OPTIONAL SCHEDULE ID VALIDATION
    |--------------------------------------------------------------------------
    | If a schedule ID is submitted by the browser, it must belong to the
    | selected room and active academic term. A reservation never changes it.
    |--------------------------------------------------------------------------
    */

    $scheduleId = null;

    if ($request->filled('schedule_id')) {
        $selectedSchedule = Schedule::query()
            ->where('id', (int) $request->schedule_id)
            ->where('room_id', $room->id)
            ->where('academic_term_id', $activeTerm->id)
            ->first();

        if (!$selectedSchedule) {
            return back()->with(
                'error',
                'The selected schedule does not belong to this room or active academic term.'
            );
        }

        $scheduleId = $selectedSchedule->id;
    }

    /*
    |--------------------------------------------------------------------------
    | DO NOT RESERVE OVER A REAL CLASS
    |--------------------------------------------------------------------------
    | This checks only the recurring schedule for the active academic term.
    | The Chair schedule itself is not changed.
    |--------------------------------------------------------------------------
    */

    $roomSchedules = Schedule::where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->whereRaw('UPPER(day) = ?', [$day])
        ->get();

    foreach ($roomSchedules as $roomSchedule) {
        $parts = preg_split(
            '/\s*-\s*/',
            trim((string) $roomSchedule->time),
            2
        );

        $roomStart = trim($parts[0] ?? '');
        $roomEnd = trim($parts[1] ?? '');

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
            $reservationStart->lt($existingEnd) &&
            $reservationEnd->gt($existingStart)
        ) {
            return back()->with(
                'error',
                'This room already has a class schedule during the selected reservation time.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DO NOT CREATE ANOTHER OVERLAPPING ACTIVE RESERVATION
    |--------------------------------------------------------------------------
    | Reserved is the current status. Pending/Approved are also treated as
    | active for legacy records so an old record cannot create a double-book.
    |--------------------------------------------------------------------------
    */

    $existingReservations = Reservation::where('room_id', $room->id)
        ->whereDate('date', $date->format('Y-m-d'))
        ->whereIn('status', ['Reserved', 'Pending', 'Approved'])
        ->get();

    foreach ($existingReservations as $existingReservation) {
        if (!$existingReservation->start_time || !$existingReservation->end_time) {
            continue;
        }

        try {
            $existingStart = Carbon::parse(
                $date->format('Y-m-d') . ' ' . $existingReservation->start_time
            );
            $existingEnd = Carbon::parse(
                $date->format('Y-m-d') . ' ' . $existingReservation->end_time
            );
        } catch (\Throwable $e) {
            continue;
        }

        if (
            $reservationStart->lt($existingEnd) &&
            $reservationEnd->gt($existingStart)
        ) {
            return back()->with(
                'error',
                'This room already has a reservation during the selected time.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE RESERVATION IMMEDIATELY
    |--------------------------------------------------------------------------
    | No admin approval is required. The new reservation is immediately
    | Reserved and will become Expired automatically after its end time.
    |--------------------------------------------------------------------------
    */

    $createdReservation = Reservation::create([
        'room_id' => $room->id,
        'user_id' => $faculty->id,
        'schedule_id' => $scheduleId,
        'academic_term_id' => $activeTerm->id,
        'faculty_subject_id' => $facultySubject->id,
        'date' => $date->format('Y-m-d'),
        'day' => $day,
        'time' => $reservationTime,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'purpose' => $room->purpose ?? 'Room Reservation',
        'status' => 'Reserved',
    ]);

    $startClock = Carbon::parse($startTime)->format('H:i');
    $endClock = Carbon::parse($endTime)->format('H:i');
    $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);
    $reservationSchedules = Schedule::with('room')
        ->where('academic_term_id', $activeTerm->id)
        ->when($scheduleId, fn ($query) => $query->whereKey($scheduleId))
        ->where('faculty_subject_id', $facultySubject->id)
        ->where('faculty_id', $faculty->id)
        ->get()
        ->filter(fn (Schedule $scheduledClass) => $studentUpdates->overlapsScheduledMeeting(
            $scheduledClass,
            $date->format('Y-m-d'),
            $startClock,
            $endClock
        ));

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
                    'reservation_id' => (int) $createdReservation->id,
                ],
            ],
            Carbon::parse($date->format('Y-m-d') . ' ' . $startClock),
            Carbon::parse($date->format('Y-m-d') . ' ' . $endClock)
        );
    }

    return redirect()
        ->route('faculty.vacant', [
            'date' => $date->format('Y-m-d'),
            'day' => $day,
            'time_slot_id' => $selectedTimeSlot->id,
        ])
        ->with(
            'success',
            'Room reserved for ' .
            $facultySubject->course_code .
            '. The room is now reserved.'
        );
}



public function vacantRooms(Request $request)
{
    $activeTerm = $this->resolveAcademicTerm($request);

    /*
    |--------------------------------------------------------------------------
    | ROOMS
    |--------------------------------------------------------------------------
    |
    | Load all rooms so faculty can search across buildings.
    |
    */

    $rooms = Room::with([
        'department',
        'building',
    ])
        ->orderBy('room_name')
        ->get();

    $results = [];

    /*
    |--------------------------------------------------------------------------
    | OFFICIAL TIME SLOTS
    |--------------------------------------------------------------------------
    |
    | Faculty must use the time slots created from the Chair's
    | schedule. Free-typed times are no longer the source of truth.
    |
    */

    $dayOrder = [
        'MON' => 1,
        'TUE' => 2,
        'WED' => 3,
        'THU' => 4,
        'FRI' => 5,
        'SAT' => 6,
    ];

    $timeSlots = ScheduleTimeSlot::query()
        ->where('academic_term_id', $activeTerm->id)
        ->where('is_active', true)
        ->whereIn('day', array_keys($dayOrder))
        ->get()
        ->sortBy(function ($slot) use ($dayOrder) {
            $day = strtoupper(trim((string) $slot->day));

            $time = Carbon::parse($slot->start_time)->format('H:i');

            return sprintf(
                '%02d-%s',
                $dayOrder[$day] ?? 99,
                $time
            );
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | SELECTED DAY AND TIME SLOT
    |--------------------------------------------------------------------------
    */

    $selectedDay = strtoupper(
        trim((string) $request->input('day', ''))
    );

    $selectedTimeSlotId = $request->input('time_slot_id');

    $selectedTimeSlot = null;

    if ($selectedTimeSlotId) {
        $selectedTimeSlot = $timeSlots->firstWhere(
            'id',
            (int) $selectedTimeSlotId
        );

        if ($selectedTimeSlot) {
            $selectedDay = strtoupper(
                trim((string) $selectedTimeSlot->day)
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BACKWARD COMPATIBILITY FOR OLD TIME INPUT
    |--------------------------------------------------------------------------
    |
    | This allows the controller to continue working while the new
    | Day + Time Slot Blade is being installed in Step 5.
    |
    */

    if (!$selectedTimeSlot && $request->filled('time')) {
        $legacyTime = trim((string) $request->input('time'));

        $selectedTimeSlot = $timeSlots->first(function ($slot) use (
            $selectedDay,
            $legacyTime
        ) {
            if (
                $selectedDay !== '' &&
                strtoupper(trim((string) $slot->day)) !== $selectedDay
            ) {
                return false;
            }

            $slotTime =
                Carbon::parse($slot->start_time)->format('H:i') .
                '-' .
                Carbon::parse($slot->end_time)->format('H:i');

            return $slotTime === $legacyTime;
        });

        if ($selectedTimeSlot) {
            $selectedTimeSlotId = $selectedTimeSlot->id;
            $selectedDay = strtoupper(
                trim((string) $selectedTimeSlot->day)
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALID SEARCH
    |--------------------------------------------------------------------------
    */

    if ($selectedTimeSlot) {

        $day = strtoupper(
            trim((string) $selectedTimeSlot->day)
        );

        $slotStart = Carbon::parse(
            $selectedTimeSlot->start_time
        );

        $slotEnd = Carbon::parse(
            $selectedTimeSlot->end_time
        );

        $slotTime =
            $slotStart->format('H:i') .
            '-' .
            $slotEnd->format('H:i');

        /*
        |------------------------------------------------------------------
        | OPTIONAL ACTUAL DATE
        |------------------------------------------------------------------
        |
        | The normal Step 4 search is Day + Time Slot only.
        | If an actual reservation date is supplied, reservations for
        | that exact date are also checked.
        |
        */

        $reservationDate = null;

        if ($request->filled('date')) {
            try {
                $parsedDate = Carbon::parse(
                    $request->input('date')
                );

                $reservationDate = $parsedDate->format('Y-m-d');

                $dateDay = strtoupper(
                    $parsedDate->format('D')
                );

                if ($dateDay !== $day) {
                    $reservationDate = null;
                }
            } catch (\Throwable $e) {
                $reservationDate = null;
            }
        }

        foreach ($rooms as $room) {

            /*
            |--------------------------------------------------------------
            | RECURRING CLASS SCHEDULE
            |--------------------------------------------------------------
            */

            $roomSchedules = Schedule::where(
                'room_id',
                $room->id
            )
                ->where(
                    'academic_term_id',
                    $activeTerm->id
                )
                ->where(
                    'day',
                    $day
                )
                ->get();

            $occupied = false;

            foreach ($roomSchedules as $schedule) {
                $scheduleTime = trim(
                    (string) $schedule->time
                );

                $parts = preg_split(
                    '/\s*-\s*/',
                    $scheduleTime,
                    2
                );

                if (count($parts) !== 2) {
                    continue;
                }

                try {
                    $scheduleStart = Carbon::parse(
                        trim($parts[0])
                    );

                    $scheduleEnd = Carbon::parse(
                        trim($parts[1])
                    );
                } catch (\Throwable $e) {
                    continue;
                }

                if (
                    $scheduleStart->lt($slotEnd) &&
                    $scheduleEnd->gt($slotStart)
                ) {
                    $occupied = true;
                    break;
                }
            }

            /*
            |--------------------------------------------------------------
            | ACTUAL DATE RESERVATION
            |--------------------------------------------------------------
            |
            | Reserved is date-specific. Therefore it is only checked
            | when the actual reservation date is known.
            |--------------------------------------------------------------
            */

            $reserved = false;

            if ($reservationDate !== null) {

                $reservations = Reservation::where(
                    'room_id',
                    $room->id
                )
                    ->where(
                        'date',
                        $reservationDate
                    )
                    ->where(
                        'status',
                        'Reserved'
                    )
                    ->get();

                foreach ($reservations as $reservation) {

                    if (
                        !$reservation->start_time ||
                        !$reservation->end_time
                    ) {
                        continue;
                    }

                    try {
                        $reservationStart = Carbon::parse(
                            $reservation->start_time
                        );

                        $reservationEnd = Carbon::parse(
                            $reservation->end_time
                        );
                    } catch (\Throwable $e) {
                        continue;
                    }

                    if (
                        $reservationStart->lt($slotEnd) &&
                        $reservationEnd->gt($slotStart)
                    ) {
                        $reserved = true;
                        break;
                    }
                }
            }

            if ($occupied) {
                $status = 'Occupied';
            } elseif ($reserved) {
                $status = 'Reserved';
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

    /*
    |--------------------------------------------------------------------------
    | LOGGED-IN FACULTY SET-FACULTY SUBJECTS
    |--------------------------------------------------------------------------
    | These are the subjects the faculty member may choose when reserving
    | an available room.
    |--------------------------------------------------------------------------
    */
    $loggedInFaculty = Auth::user();

    $facultySubjectOptions = FacultySubject::with([
        'faculty',
        'subjectCatalog.program',
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
                'subject_type' => $facultySubject->subject_type
                    ?: (preg_match('/\.1\s*$/', trim($facultySubject->course_code)) === 1 ? 'Lab' : 'Lec'),
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
|--------------------------------------------------------------------------
| FACULTY SUBJECTS
|--------------------------------------------------------------------------
*/

private function chairCanManageFacultySubject(FacultySubject $facultySubject, User $chair): bool
{
    $facultySubject->loadMissing(['faculty', 'subjectCatalog.program']);
    $faculty = $facultySubject->faculty;

    if (!$faculty) {
        return false;
    }

    // A faculty member's home department can manage its own assignments.
    if ((int) $faculty->department_id === (int) $chair->department_id) {
        return true;
    }

    $importedAt = DB::table('department_faculty_imports')
        ->where('department_id', $chair->department_id)
        ->where('faculty_id', $faculty->id)
        ->value('created_at');

    if (!$importedAt) {
        return false;
    }

    // Program ownership is the clearest signal for which department
    // created an assignment. Legacy manual entries added after import are
    // recognized by their creation time.
    $catalogDepartmentId = $facultySubject->subjectCatalog?->program?->department_id;
    if ($catalogDepartmentId !== null) {
        return (int) $catalogDepartmentId === (int) $chair->department_id;
    }

    return $facultySubject->created_at
        && $facultySubject->created_at->timestamp >= strtotime((string) $importedAt);
}

public function facultySubjects($faculty)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

 $chair = Auth::user();

$faculty = User::where('id', $faculty)
    ->where('role', 'faculty')
    ->active()
    ->where(function ($query) use ($chair) {
        $query->where('department_id', $chair->department_id)
            ->orWhereExists(function ($importQuery) use ($chair) {
                $importQuery->select(DB::raw(1))
                    ->from('department_faculty_imports')
                    ->whereColumn(
                        'department_faculty_imports.faculty_id',
                        'users.id'
                    )
                    ->where(
                        'department_faculty_imports.department_id',
                        $chair->department_id
                    );
            });
    })
    ->with(['department', 'facultySubjects.subjectCatalog.program'])
    ->firstOrFail();

    $faculty->setRelation('facultySubjects', $faculty->facultySubjects
        ->filter(fn ($subject) => $this->chairCanManageFacultySubject($subject, $chair))
        ->values());

    return response()->json([
        'faculty' => [
            'id' => $faculty->id,
            'name' => $faculty->name,
            'department' => $faculty->department?->name,
        ],
        'subjects' => $faculty->facultySubjects
            ->sortBy('course_code')
            ->values()
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'course_code' => $subject->course_code,
                    'subject' => $subject->subject,
                    'year_level' => $subject->year_level,
                    'major' => $subject->major,
                    'program' => $subject->program,
                ];
            }),
    ]);
}
public function storeFacultySubject(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $request->validate([
        'faculty_id' => 'required|exists:users,id',
        'subject_id' => 'required|integer|exists:subjects,id',
        'course_code' => 'required|string|max:50',
        'cohort_subject_ids' => 'nullable|array|max:40',
        'cohort_subject_ids.*' => 'integer|distinct|exists:subjects,id',
    ]);

   $faculty = User::where('id', $request->faculty_id)
    ->where('role', 'faculty')
    ->active()
    ->where(function ($query) use ($chair) {
        $query->where('department_id', $chair->department_id)
            ->orWhereExists(function ($importQuery) use ($chair) {
                $importQuery->select(DB::raw(1))
                    ->from('department_faculty_imports')
                    ->whereColumn(
                        'department_faculty_imports.faculty_id',
                        'users.id'
                    )
                    ->where(
                        'department_faculty_imports.department_id',
                        $chair->department_id
                    );
            });
    })
    ->firstOrFail();

    $subjectEntry = Subject::with('program')
        ->whereHas('program', fn ($query) => $query->where('department_id', $chair->department_id))
        ->findOrFail($request->subject_id);

    $courseCode = strtoupper(trim($subjectEntry->course_code));
    if ($courseCode !== strtoupper(trim($request->course_code))) {
        return back()->withInput()->with('error', 'The course code changed. Enter it again and select the matching subject.');
    }

    $selectedCohortIds = collect($request->input('cohort_subject_ids', []))
        ->map(fn ($id) => (int) $id)
        ->push((int) $subjectEntry->id)
        ->unique()
        ->values();
    $cohortSubjects = Subject::with('program')
        ->whereIn('id', $selectedCohortIds)
        ->get();
    $invalidCohort = $cohortSubjects->count() !== $selectedCohortIds->count()
        || $cohortSubjects->contains(function (Subject $subject) use ($chair, $subjectEntry) {
            return (int) $subject->program?->department_id !== (int) $chair->department_id
                || strtoupper(trim((string) $subject->course_code)) !== strtoupper(trim((string) $subjectEntry->course_code))
                || mb_strtolower(trim((string) $subject->course_title)) !== mb_strtolower(trim((string) $subjectEntry->course_title));
        });
    if ($invalidCohort) {
        return back()->withInput()->with('error', 'Choose only entries with the same course code and title from your department.');
    }

    $existingAssignment = FacultySubject::with(['faculty', 'subjectCatalog.program'])
        ->where('faculty_id', $faculty->id)
        ->whereRaw('UPPER(course_code) = ?', [$courseCode])
        ->get()
        ->first(fn (FacultySubject $assignment) => $this->chairCanManageFacultySubject($assignment, $chair));

    // Fold any older per-major assignments for this same faculty/course into
    // the selected assignment's saved cohort list without deleting their rows.
    $legacyCohortIds = FacultySubject::with('subjectCatalog.program')
        ->where('faculty_id', $faculty->id)
        ->whereRaw('UPPER(course_code) = ?', [$courseCode])
        ->get()
        ->filter(function (FacultySubject $assignment) use ($chair, $subjectEntry) {
            return $assignment->subjectCatalog
                && (int) $assignment->subjectCatalog->program?->department_id === (int) $chair->department_id
                && mb_strtolower(trim((string) $assignment->subject)) === mb_strtolower(trim((string) $subjectEntry->course_title));
        })
        ->pluck('subject_id')
        ->filter()
        ->map(fn ($id) => (int) $id);
    $selectedCohortIds = $selectedCohortIds->merge($legacyCohortIds)->unique()->values();

    if ($existingAssignment) {
        if (mb_strtolower(trim((string) $existingAssignment->subject)) !== mb_strtolower(trim((string) $subjectEntry->course_title))) {
            return redirect()
                ->route('chair.setFaculty', ['faculty' => $faculty->id])
                ->withFragment('faculty-add-subject')
                ->with('error', 'This instructor already has this course code with a different title. Use Merge Class for equivalent courses with different titles.');
        }

        $existingAssignment->sharedCohorts()->syncWithoutDetaching(
            $selectedCohortIds->reject(fn ($id) => (int) $id === (int) $existingAssignment->subject_id)->all()
        );
        $this->publishFacultySubjectCohortChanges($existingAssignment, $chair);

        return redirect()
            ->route('chair.setFaculty', ['faculty' => $faculty->id])
            ->withFragment('faculty-add-subject')
            ->with('success', 'The selected program and major groups were added to the existing instructor course assignment.');
    }

    $facultySubject = FacultySubject::create([
        'faculty_id' => $faculty->id,
        'subject_id' => $subjectEntry->id,
        'course_code' => $courseCode,
        'subject' => $subjectEntry->course_title,
        'year_level' => $subjectEntry->year_level,
        'major' => $subjectEntry->program?->major,
        'program' => trim(($subjectEntry->program?->name ?? '') . ' (' . ($subjectEntry->program?->code ?? '') . ')'),
        'subject_type' => Subject::typeFromCourseCode($subjectEntry->course_code),
    ]);

    $facultySubject->sharedCohorts()->sync(
        $selectedCohortIds->reject(fn ($id) => (int) $id === (int) $subjectEntry->id)->all()
    );

    return redirect()
        ->route('chair.setFaculty', ['faculty' => $faculty->id])
        ->withFragment('faculty-add-subject')
        ->with('success', 'Subject assigned successfully.');
}

public function updateFacultySubject(Request $request, $id)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $request->validate([
        'course_code' => 'nullable|string|max:50',
        'subject' => 'nullable|string|max:255',
        'year_level' => 'nullable|string|max:50',
        'major' => 'nullable|string|max:100',
        'program' => 'nullable|string|max:100',
    ]);

    $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])
        ->whereHas('faculty', fn ($query) => $query->active())
        ->findOrFail($id);
    if (!$this->chairCanManageFacultySubject($facultySubject, $chair)) {
        abort(403, 'You cannot edit this original college subject.');
    }

    // Get the faculty member using the existing subject assignment.
    $faculty = User::where('id', $facultySubject->faculty_id)
        ->where('role', 'faculty')
        ->where(function ($query) use ($chair) {
            $query->where('department_id', $chair->department_id)
                ->orWhereExists(function ($importQuery) use ($chair) {
                    $importQuery->select(DB::raw(1))
                        ->from('department_faculty_imports')
                        ->whereColumn(
                            'department_faculty_imports.faculty_id',
                            'users.id'
                        )
                        ->where(
                            'department_faculty_imports.department_id',
                            $chair->department_id
                        );
                });
        })
        ->firstOrFail();

    if ($facultySubject->subject_id) {
        return redirect()
            ->route('chair.setFaculty', ['faculty' => $faculty->id])
            ->with('error', 'This assignment is linked to Manage Subjects. Update its catalog entry or remove and reassign it.');
    }

    $courseCode = strtoupper(trim(
        $request->input('course_code', $facultySubject->course_code)
    ));

    $subject = trim(
        $request->input('subject', $facultySubject->subject)
    );

    $yearLevel = trim(
        $request->input('year_level', $facultySubject->year_level)
    );

    $program = trim(
        $request->input('program', $facultySubject->program)
    );

    $major = $request->filled('major') ? trim($request->major) : $facultySubject->major;

    if ($courseCode === '' || $subject === '' || $yearLevel === '' || $program === '') {
        return back()
            ->withInput()
            ->with('error', 'Course information is incomplete.');
    }

    $duplicate = FacultySubject::where('faculty_id', $faculty->id)
        ->where('course_code', $courseCode)
        ->where('id', '!=', $facultySubject->id)
        ->exists();

    if ($duplicate) {
        return redirect()
            ->route('chair.setFaculty', ['faculty' => $faculty->id])
            ->with('error', 'This course code is already assigned to this instructor.');
    }

    $facultySubject->update([
        'course_code' => $courseCode,
        'subject' => $subject,
        'year_level' => $yearLevel,
        'major' => $major,
        'program' => $program,
        'subject_type' => Subject::typeFromCourseCode($courseCode),
    ]);

    return redirect()
        ->route('chair.setFaculty', ['faculty' => $faculty->id])
        ->with('success', 'Subject updated successfully.');
}


public function deleteFacultySubject($id)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])
        ->whereHas('faculty', fn ($query) => $query->active())
        ->findOrFail($id);
    if (!$this->chairCanManageFacultySubject($facultySubject, $chair)) {
        abort(403, 'You cannot remove this original college subject.');
    }

    $faculty = User::where('id', $facultySubject->faculty_id)
        ->where('role', 'faculty')
        ->where(function ($query) use ($chair) {
            $query->where('department_id', $chair->department_id)
                ->orWhereExists(function ($importQuery) use ($chair) {
                    $importQuery->select(DB::raw(1))
                        ->from('department_faculty_imports')
                        ->whereColumn(
                            'department_faculty_imports.faculty_id',
                            'users.id'
                        )
                        ->where(
                            'department_faculty_imports.department_id',
                            $chair->department_id
                        );
                });
        })
        ->firstOrFail();

    $facultySubject->delete();

    return redirect()
        ->route('chair.setFaculty', ['faculty' => $faculty->id])
        ->with('success', 'Subject removed successfully.');
}

public function modifySchedule(Request $request)
{
    if (!$this->isChairUser()) {
        abort(403, 'Unauthorized.');
    }

    $chair =
        Auth::user();

    /*
    |--------------------------------------------------------------------------
    | ACTIVE TERM
    |--------------------------------------------------------------------------
    */

    $termId =
        $request->query(
            'term_id'
        );

    $activeTerm =
        $this->getActiveAcademicTerm(
            $termId
                ? (int) $termId
                : null
        );

    /*
    |--------------------------------------------------------------------------
    | LOAD ALL DEPARTMENTS
    |--------------------------------------------------------------------------
    */

    $departments =
        Department::with([

            'buildings.rooms' => function ($query) use (
                $activeTerm
            ) {

                $query->with([

                    'building',

                    'schedules' =>
                        function (
                            $scheduleQuery
                        ) use (
                            $activeTerm
                        ) {

                            $scheduleQuery
                                ->where(
                                    'academic_term_id',
                                    $activeTerm->id
                                )
                                ->with([
                                    'department',
                                    'merges' => function ($mergeQuery) {
                                        $mergeQuery
                                            ->whereIn('status', ['approved', 'pending'])
                                            ->with([
                                                'subject.program.department',
                                                'facultySubject.subjectCatalog.program.department',
                                            ]);
                                    },
                                ])
                                ->orderBy(
                                    'day'
                                )
                                ->orderBy(
                                    'time'
                                );
                        },

                ]);

            },

        ])
        ->where('id', '!=', (int) $chair->department_id)
        ->orderBy('name')
        ->get();

    $availabilityRooms = $departments
        ->flatMap(function ($department) {
            return $department->buildings->flatMap(function ($building) use ($department) {
                return $building->rooms->map(function ($room) use ($building, $department) {
                    return [
                        'id' => (int) $room->id,
                        'room_name' => $room->room_name,
                        'building_name' => $building->name,
                        'department_name' => $department->name,
                        'is_current_department' => false,
                        'allow_other_departments' => (bool) $room->allow_other_departments,
                        'schedules' => $room->schedules->map(fn ($schedule) => [
                            'course_code' => $schedule->course_code,
                            'subject' => $schedule->subject,
                            'day' => strtoupper(trim((string) $schedule->day)),
                            'time' => $schedule->time,
                        ])->values()->all(),
                    ];
                });
            });
        })
        ->values()
        ->all();

    /*
    |--------------------------------------------------------------------------
    | FACULTY SUBJECTS
    |--------------------------------------------------------------------------
    */

    $facultySubjects =
        FacultySubject::with([
            'faculty.department',
            'subjectCatalog.program',
            'sharedCohorts.program',
        ])
        ->whereHas('faculty', fn ($query) => $query->active())
        ->orderBy(
            'course_code'
        )
        ->get()
        ->filter(fn ($subject) => $this->chairCanManageFacultySubject($subject, $chair))
        ->values();

    return view(
        'chair.modifyschedule',
        compact(
            'departments',
            'chair',
            'facultySubjects',
            'activeTerm',
            'availabilityRooms'
        )
    );
}

/*
|--------------------------------------------------------------------------
| CLEAR MODIFIED SCHEDULE CELL
|--------------------------------------------------------------------------
|
| This allows the chair to clear a schedule that belongs to the
| chair's own department. A schedule belonging to another
| department remains protected.
|
*/
public function clearModifiedSchedule(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $activeTerm = $this->resolveAcademicTerm($request);

    $request->validate([
        'schedule_id' => 'required|exists:schedules,id',
    ]);

    $schedule = Schedule::with('room')
        ->where('id', $request->schedule_id)
        ->where('academic_term_id', $activeTerm->id)
        ->first();

    if (!$schedule) {
        return back()->with(
            'error',
            'The selected schedule does not belong to the active academic term.'
        );
    }

    if ((int) $schedule->department_id !== (int) $chair->department_id) {
        return back()->with(
            'error',
            'This schedule belongs to another college and cannot be cleared.'
        );
    }

    app(\App\Services\StudentScheduleUpdateService::class)->recordForSchedule(
        $schedule,
        'schedule_removed',
        'Class removed from the timetable',
        trim(($schedule->course_code ?: 'Class') . ' — ' . ($schedule->subject ?: ''))
            . ' was removed from ' . $schedule->day . ' ' . $schedule->time . '.'
    );
    $schedule->delete();

    return redirect()
        ->route('chair.modifyschedule', ['term_id' => $activeTerm->id])
        ->with('success', 'Schedule cell cleared successfully.');
}
public function storeModifiedSchedule(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $activeTerm = $this->resolveAcademicTerm($request);

    $request->validate([
        'schedule_id' => 'nullable|integer|exists:schedules,id',
        'room_id' => 'required|exists:rooms,id',
        'day' => 'required|string|max:20',
        'time' => 'required|string|max:50',
        'course_code' => 'required|string|max:50',
        'faculty_subject_id' => 'required|integer|exists:faculty_subjects,id',
        'semester' => 'nullable|string|max:100',
        'academic_year' => 'nullable|string|max:100',
        'major' => 'nullable|string|max:50',
        'program' => 'required|string|max:100',
        'color' => 'nullable|string|max:20',
        'adjustments' => 'nullable|string|max:20000',
    ]);

    $room = Room::findOrFail($request->room_id);
    $day = strtoupper(trim($request->day));
    $time = trim($request->time);

    /* Existing schedule being edited, restricted to the active term. */
    $currentSchedule = null;

    if ($request->filled('schedule_id')) {
        $currentSchedule = Schedule::where('id', $request->schedule_id)
            ->where('academic_term_id', $activeTerm->id)
            ->first();

        if (!$currentSchedule) {
            return back()
                ->with('error', 'The selected schedule does not belong to the active academic term.')
                ->withInput();
        }

        if ((int) $currentSchedule->department_id !== (int) $chair->department_id) {
            return back()
                ->with('error', 'This schedule belongs to another college and cannot be modified.')
                ->withInput();
        }
    }

    $adjustmentRows = json_decode((string) $request->input('adjustments', '[]'), true);
    if (!is_array($adjustmentRows)) {
        return back()->with('error', 'The adjacent schedule adjustments were not valid. Please try the resize again.')->withInput();
    }
    $adjustmentRows = collect($adjustmentRows)->filter(fn ($row) => is_array($row)
        && !empty($row['schedule_id']) && isset($row['day'], $row['time']))->values();
    $adjustmentIds = $adjustmentRows->pluck('schedule_id')->map(fn ($id) => (int) $id)->unique()->values();
    $adjustedSchedules = $adjustmentIds->isEmpty()
        ? collect()
        : Schedule::whereIn('id', $adjustmentIds)
            ->where('academic_term_id', $activeTerm->id)
            ->where('room_id', $room->id)
            ->where('department_id', $chair->department_id)
            ->get()
            ->keyBy('id');

    if ($adjustedSchedules->count() !== $adjustmentIds->count()) {
        return back()->with('error', 'Only your college’s adjacent schedules in this room can move automatically.')->withInput();
    }
    if ($currentSchedule && $adjustmentIds->contains((int) $currentSchedule->id)) {
        return back()->with('error', 'The selected class cannot also be listed as an adjacent adjustment.')->withInput();
    }

    $adjustmentTimes = [];
    $adjustmentDays = [];
    foreach ($adjustmentRows as $adjustment) {
        $scheduleId = (int) $adjustment['schedule_id'];
        $adjustmentTime = trim((string) $adjustment['time']);
        $adjustmentDay = strtoupper(trim((string) $adjustment['day']));
        if (!$adjustedSchedules->has($scheduleId)
            || strtoupper(trim((string) $adjustedSchedules[$scheduleId]->day)) !== $adjustmentDay
            || !$this->scheduleTimeRangeToMinutes($adjustmentTime)) {
            return back()->with('error', 'An adjacent schedule has an invalid time range. Please resize again.')->withInput();
        }
        $adjustmentTimes[$scheduleId] = $adjustmentTime;
        $adjustmentDays[$scheduleId] = $adjustmentDay;
    }

    if ($adjustmentIds->isNotEmpty()) {
        if ($currentSchedule) {
            $previousRange = $this->scheduleTimeRangeToMinutes($currentSchedule->time);
            $resizedRange = $this->scheduleTimeRangeToMinutes($time);
            $changedStart = $previousRange && $resizedRange && $previousRange['start'] !== $resizedRange['start'];
            $changedEnd = $previousRange && $resizedRange && $previousRange['end'] !== $resizedRange['end'];
            if (!$previousRange || !$resizedRange || $changedStart === $changedEnd) {
                return back()->with('error', 'The adjacent adjustment does not match a single resized class edge.')->withInput();
            }
            foreach ($adjustedSchedules as $adjustedSchedule) {
                $previousNeighborRange = $this->scheduleTimeRangeToMinutes($adjustedSchedule->time);
                $resizedNeighborRange = $this->scheduleTimeRangeToMinutes($adjustmentTimes[$adjustedSchedule->id]);
                $isAdjacentResize = $previousNeighborRange && $resizedNeighborRange && (
                    ($changedEnd
                        && $previousNeighborRange['start'] === $previousRange['end']
                        && $resizedNeighborRange['start'] === $resizedRange['end']
                        && $resizedNeighborRange['end'] === $previousNeighborRange['end'])
                    || ($changedStart
                        && $previousNeighborRange['end'] === $previousRange['start']
                        && $resizedNeighborRange['end'] === $resizedRange['start']
                        && $resizedNeighborRange['start'] === $previousNeighborRange['start'])
                );
                if (!$isAdjacentResize) {
                    return back()->with('error', 'Only the class directly beside the resized edge can be adjusted automatically.')->withInput();
                }
            }
        } else {
            $hasAdjacentResizePair = false;
            $entries = $adjustedSchedules->values();
            for ($leftIndex = 0; $leftIndex < $entries->count(); $leftIndex++) {
                for ($rightIndex = $leftIndex + 1; $rightIndex < $entries->count(); $rightIndex++) {
                    $left = $entries[$leftIndex];
                    $right = $entries[$rightIndex];
                    if ($adjustmentDays[$left->id] !== $adjustmentDays[$right->id]) continue;
                    $leftBefore = $this->scheduleTimeRangeToMinutes($left->time);
                    $rightBefore = $this->scheduleTimeRangeToMinutes($right->time);
                    $leftAfter = $this->scheduleTimeRangeToMinutes($adjustmentTimes[$left->id]);
                    $rightAfter = $this->scheduleTimeRangeToMinutes($adjustmentTimes[$right->id]);
                    if (!$leftBefore || !$rightBefore || !$leftAfter || !$rightAfter) continue;
                    $leftThenRight = $leftBefore['end'] === $rightBefore['start']
                        && $leftAfter['start'] === $leftBefore['start']
                        && $leftAfter['end'] === $rightAfter['start']
                        && $rightAfter['end'] === $rightBefore['end'];
                    $rightThenLeft = $rightBefore['end'] === $leftBefore['start']
                        && $rightAfter['start'] === $rightBefore['start']
                        && $rightAfter['end'] === $leftAfter['start']
                        && $leftAfter['end'] === $leftBefore['end'];
                    if ($leftThenRight || $rightThenLeft) {
                        $hasAdjacentResizePair = true;
                        break 2;
                    }
                }
            }
            if (!$hasAdjacentResizePair) {
                return back()->with('error', 'The automatic updates must come from one adjacent schedule resize.')->withInput();
            }
        }
    }

    $ignoredRoomScheduleIds = $adjustmentIds->all();
    if ($currentSchedule) {
        $ignoredRoomScheduleIds[] = (int) $currentSchedule->id;
    }

    $proposedRoomRanges = [[
        'day' => $day,
        'time' => $time,
        'range' => $this->scheduleTimeRangeToMinutes($time),
    ]];
    foreach ($adjustedSchedules as $adjustedSchedule) {
        $adjustedTime = $adjustmentTimes[$adjustedSchedule->id] ?? $adjustedSchedule->time;
        $proposedRoomRanges[] = [
            'day' => $adjustmentDays[$adjustedSchedule->id],
            'time' => $adjustedTime,
            'range' => $this->scheduleTimeRangeToMinutes($adjustedTime),
        ];
    }
    foreach ($proposedRoomRanges as $index => $candidate) {
        if (!$candidate['range']) {
            return back()->with('error', 'A resized schedule has an invalid time range.')->withInput();
        }
        foreach (array_slice($proposedRoomRanges, $index + 1) as $other) {
            if ($candidate['day'] === $other['day']
                && $candidate['range']['start'] < $other['range']['end']
                && $candidate['range']['end'] > $other['range']['start']) {
                return back()->with('error', 'The automatic adjustment would make two schedules overlap.')->withInput();
            }
        }
    }

    $affectedDays = collect([$day])->merge(array_values($adjustmentDays))->unique()->values();
    $otherRoomSchedules = Schedule::where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->whereIn('day', $affectedDays)
        ->when($ignoredRoomScheduleIds, fn ($query) => $query->whereNotIn('id', $ignoredRoomScheduleIds))
        ->get();
    foreach ($otherRoomSchedules as $otherRoomSchedule) {
        $otherRange = $this->scheduleTimeRangeToMinutes($otherRoomSchedule->time);
        if (!$otherRange) continue;
        foreach ($proposedRoomRanges as $candidate) {
            if (strtoupper(trim((string) $otherRoomSchedule->day)) === $candidate['day']
                && $candidate['range']['start'] < $otherRange['end']
                && $candidate['range']['end'] > $otherRange['start']) {
                return back()->with('error', 'The automatic adjustment would overlap another class in this room.')->withInput();
            }
        }
    }

    /* Target slot must be empty in the active term. */
    $existingSchedule = Schedule::where('room_id', $room->id)
        ->where('day', $day)
        ->where('time', $time)
        ->where('academic_term_id', $activeTerm->id)
        ->when($adjustmentIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $adjustmentIds->all()))
        ->when($currentSchedule, function ($query) use ($currentSchedule) {
            $query->where('id', '!=', $currentSchedule->id);
        })
        ->first();

    if ($existingSchedule) {
        if ((int) $existingSchedule->department_id !== (int) $chair->department_id) {
            return back()
                ->with('error', 'This schedule belongs to another college and cannot be modified.')
                ->withInput();
        }

        return back()
            ->with('error', 'The selected room, day, and time slot is already occupied in the active semester.')
            ->withInput();
    }

    /* Empty slot in another department's room requires permission. */
    if ((int) $room->department_id !== (int) $chair->department_id) {
        if (!$room->allow_other_departments) {
            return back()
                ->with('error', 'This college does not allow other colleges to add schedules to this room.')
                ->withInput();
        }
    }

    /* Course must belong to the logged-in Chair's department. */
    $courseCode = strtoupper(trim($request->course_code));

    $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])
        ->whereHas('faculty', fn ($query) => $query->active())
        ->find($request->faculty_subject_id);

    if ($facultySubject && (
        strtoupper(trim($facultySubject->course_code)) !== $courseCode
        || !$this->chairCanManageFacultySubject($facultySubject, $chair)
    )) {
        $facultySubject = null;
    }

    if (!$facultySubject) {
        return back()
            ->with('error', 'This course code is not assigned to a instructor in your college.')
            ->withInput();
    }

    if (!$facultySubject->faculty) {
        return back()
            ->with('error', 'The instructor assigned to this course could not be found.')
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY SCHEDULE CONFLICT CHECK
    |--------------------------------------------------------------------------
    */
    $ignoredFacultyScheduleIds = $adjustmentIds->all();
    if ($currentSchedule) {
        $ignoredFacultyScheduleIds[] = (int) $currentSchedule->id;
    }
    $ignoredFacultyScheduleIds = array_values(array_unique($ignoredFacultyScheduleIds));

    $proposedFacultySchedules = [[
        'faculty_id' => (int) $facultySubject->faculty_id,
        'day' => $day,
        'time' => $time,
    ]];
    foreach ($adjustedSchedules as $adjustedSchedule) {
        $proposedFacultySchedules[] = [
            'faculty_id' => (int) $adjustedSchedule->faculty_id,
            'day' => $adjustmentDays[$adjustedSchedule->id],
            'time' => $adjustmentTimes[$adjustedSchedule->id] ?? $adjustedSchedule->time,
        ];
    }

    $pendingAdjustedFacultySchedules = [];
    foreach ($proposedFacultySchedules as $proposal) {
        $facultyConflict = $this->findFacultyScheduleConflict(
            (int) $proposal['faculty_id'],
            (string) $proposal['day'],
            (string) $proposal['time'],
            $activeTerm->id,
            $ignoredFacultyScheduleIds
        );
        if (!$facultyConflict) {
            $facultyConflict = $this->findPendingFacultyScheduleConflict(
                (int) $proposal['faculty_id'],
                (string) $proposal['day'],
                (string) $proposal['time'],
                $pendingAdjustedFacultySchedules
            );
        }
        if ($facultyConflict) {
            return back()
                ->with('error', 'The automatic time adjustment conflicts with another class assigned to ' . ($facultyConflict instanceof Schedule ? $facultyConflict->instructor : 'the instructor') . '.')
                ->withInput();
        }
        $pendingAdjustedFacultySchedules[] = $proposal;
    }

    if ($facultyConflict) {
        return back()
            ->with(
                'error',
                'Schedule conflict: this instructor/instructor already has a schedule on '
                . $this->formatScheduleConflictMessage($facultyConflict)
                . '. A instructor cannot have overlapping schedules in different rooms.'
            )
            ->withInput();
    }

    /* Active term is the source of truth for semester/year. */
    $scheduleData = [
        'room_id' => $room->id,
        'faculty_id' => $facultySubject->faculty_id,
        'department_id' => $chair->department_id,
        'academic_term_id' => $activeTerm->id,
        'semester' => $activeTerm->semester,
        'academic_year' => $activeTerm->academic_year,
        'day' => $day,
        'time' => $time,
        'faculty_subject_id' => $facultySubject->id,
        'course_code' => $facultySubject->course_code,
        'subject' => $facultySubject->subject,
        'year_level' => $facultySubject->year_level,
        'major' => $facultySubject->major,
        'program' => $facultySubject->program,
        'subject_type' => $facultySubject->subject_type
            ?: (preg_match('/\.1\s*$/', trim($courseCode)) === 1 ? 'Lab' : 'Lec'),
        'instructor' => $facultySubject->faculty->name,
        'color' => $request->color ?: '#ffffff',
    ];

    $applyAdjacentScheduleUpdates = function () use ($adjustedSchedules, $adjustmentTimes) {
        $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);
        foreach ($adjustedSchedules as $adjustedSchedule) {
            $newTime = $adjustmentTimes[$adjustedSchedule->id] ?? null;
            if (!$newTime || $newTime === $adjustedSchedule->time) continue;
            $adjustedSchedule->update(['time' => $newTime]);
            $updated = $adjustedSchedule->fresh();
            $studentUpdates->recordForSchedule(
                $updated,
                'schedule_updated',
                'Class schedule time adjusted',
                trim(($updated->course_code ?: 'Class') . ' — ' . ($updated->subject ?: ''))
                    . ' was moved to ' . $updated->day . ' ' . $updated->time
                    . ' because an adjacent class time was adjusted.'
            );
        }
    };

    if ($currentSchedule) {
        $previousSchedule = clone $currentSchedule;
        $previousSharedAudiences = $currentSchedule->merges()
            ->where('share_type', 'same_faculty')
            ->where('status', 'approved')
            ->with('subject.program')
            ->get()
            ->map(function ($merge) {
                $subject = $merge->subject;
                return [
                    'id' => (int) $merge->subject_id,
                    'department_id' => (int) ($subject?->program?->department_id ?? $merge->department_id),
                    'program' => trim((string) ($subject?->program?->name ?? '')),
                    'year_level' => trim((string) ($subject?->year_level ?? '')),
                ];
            });
        $cohortChanged = (int) $previousSchedule->department_id !== (int) $scheduleData['department_id']
            || trim((string) $previousSchedule->program) !== trim((string) $scheduleData['program'])
            || trim((string) $previousSchedule->year_level) !== trim((string) $scheduleData['year_level']);
        $currentSchedule->update($scheduleData);
        $this->syncSameFacultyScheduleAudiences($currentSchedule, $facultySubject, $chair);
        $sharedOfferingIds = $facultySubject->sharedCohorts()->pluck('subjects.id')->map(fn ($id) => (int) $id)->all();
        $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);
        if ($cohortChanged) {
            $studentUpdates->recordForSchedule(
                $previousSchedule,
                'schedule_updated',
                'Class schedule changed',
                trim(($previousSchedule->course_code ?: 'Class') . ' — ' . ($previousSchedule->subject ?: ''))
                    . ' was updated. Check the current class schedule for the latest details.'
            );
        }
        $studentUpdates->recordForSchedule(
            $currentSchedule->fresh(),
            'schedule_updated',
            'Class schedule updated',
            trim(($currentSchedule->course_code ?: 'Class') . ' — ' . ($currentSchedule->subject ?: ''))
                . ' is scheduled for ' . $currentSchedule->day . ' ' . $currentSchedule->time
                . ($studentUpdates->roomNameFor($currentSchedule) ? ' in ' . $studentUpdates->roomNameFor($currentSchedule) : '') . '.'
        );

        foreach ($previousSharedAudiences->reject(fn ($audience) => in_array($audience['id'], $sharedOfferingIds, true)) as $audience) {
            if ($audience['department_id'] === (int) $currentSchedule->department_id
                && mb_strtolower($audience['program']) === mb_strtolower(trim((string) $currentSchedule->program))
                && mb_strtolower($audience['year_level']) === mb_strtolower(trim((string) $currentSchedule->year_level))) {
                continue;
            }
            $studentUpdates->recordForAudience(
                $currentSchedule->fresh(),
                $audience['department_id'],
                $audience['program'],
                $audience['year_level'],
                'schedule_updated',
                'Shared class audience changed',
                trim(($currentSchedule->course_code ?: 'Class') . ' — ' . ($currentSchedule->subject ?: ''))
                    . ' is no longer shared with your program for ' . $currentSchedule->day . ' ' . $currentSchedule->time . '.'
            );
        }

        $applyAdjacentScheduleUpdates();

        return redirect()
            ->route('chair.modifyschedule', ['term_id' => $activeTerm->id])
            ->with('success', 'Your schedule was updated successfully.');
    }

    $createdSchedule = Schedule::create($scheduleData);
    $this->syncSameFacultyScheduleAudiences($createdSchedule, $facultySubject, $chair);
    $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);
    $studentUpdates->recordForSchedule(
        $createdSchedule,
        'schedule_published',
        'New class schedule published',
        trim(($createdSchedule->course_code ?: 'Class') . ' — ' . ($createdSchedule->subject ?: ''))
            . ' is scheduled for ' . $createdSchedule->day . ' ' . $createdSchedule->time
            . ($studentUpdates->roomNameFor($createdSchedule) ? ' in ' . $studentUpdates->roomNameFor($createdSchedule) : '') . '.'
    );

    $applyAdjacentScheduleUpdates();

    return redirect()
        ->route('chair.modifyschedule', ['term_id' => $activeTerm->id])
        ->with('success', 'Schedule added successfully to the empty slot.');
}

public function searchMergeClasses(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    if (
        !Auth::check() ||
        Auth::user()->role !== 'chair'
    ) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | VALIDATE SEARCH
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'course_code' => 'required|string|max:50',
    ]);


    /*
    |--------------------------------------------------------------------------
    | CLEAN COURSE CODE
    |--------------------------------------------------------------------------
    */

    $courseCode = strtoupper(
        trim($request->course_code)
    );

    $activeTerm = $this->resolveAcademicTerm($request);


    /*
    |--------------------------------------------------------------------------
    | SEARCH EXISTING SCHEDULES
    |--------------------------------------------------------------------------
    |
    | We use direct joins instead of nested Eloquent relationships.
    |
    | This avoids errors from:
    | room.department
    | room.building
    | faculty.department
    |
    */

    $classes = Schedule::query()

        ->leftJoin(
            'rooms',
            'schedules.room_id',
            '=',
            'rooms.id'
        )

        ->leftJoin(
            'buildings',
            'rooms.building_id',
            '=',
            'buildings.id'
        )

        ->leftJoin(
            'departments',
            'schedules.department_id',
            '=',
            'departments.id'
        )

        ->leftJoin(
            'users',
            'schedules.faculty_id',
            '=',
            'users.id'
        )

        ->whereRaw(
            'UPPER(schedules.course_code) LIKE ?',
            ['%' . $courseCode . '%']
        )

        ->where('schedules.academic_term_id', $activeTerm->id)

        /*
        |--------------------------------------------------------------------------
        | ONLY OTHER DEPARTMENTS
        |--------------------------------------------------------------------------
        */

        ->where(
            'schedules.department_id',
            '!=',
            $chair->department_id
        )

        ->select([
            'schedules.id',
            'schedules.course_code',
            'schedules.subject',
            'schedules.day',
            'schedules.time',
            'schedules.room_id',
            'schedules.department_id',
            'schedules.faculty_id',
            'schedules.instructor',
            'schedules.program',

            'rooms.room_name',

            'buildings.name as building_name',

            'departments.name as department_name',

            'users.name as faculty_name',
        ])

        ->orderByRaw(
            'CASE WHEN UPPER(schedules.course_code) = ? THEN 0 WHEN UPPER(schedules.course_code) LIKE ? THEN 1 ELSE 2 END',
            [$courseCode, $courseCode . '%']
        )

        ->orderBy('schedules.course_code')

        ->orderBy(
            'schedules.day'
        )

        ->orderBy(
            'schedules.time'
        )

        ->get();

    $mergeRows = ScheduleMerge::with([
        'subject.program',
        'facultySubject.subjectCatalog.program',
    ])
        ->whereIn('schedule_id', $classes->pluck('id'))
        ->where('status', 'approved')
        ->get()
        ->groupBy('schedule_id');


    /*
    |--------------------------------------------------------------------------
    | RETURN RESULTS
    |--------------------------------------------------------------------------
    */

    return response()->json([
        'success' => true,

        'classes' => $classes
            ->map(function ($schedule) use ($mergeRows) {

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | SCHEDULE
                    |--------------------------------------------------------------------------
                    */

                    'id' =>
                        $schedule->id,

                    'course_code' =>
                        $schedule->course_code,

                    'subject' =>
                        $schedule->subject,

                    'day' =>
                        $schedule->day,

                    'time' =>
                        $schedule->time,


                    /*
                    |--------------------------------------------------------------------------
                    | ROOM
                    |--------------------------------------------------------------------------
                    */

                    'room_id' =>
                        $schedule->room_id,

                    'room' =>
                        $schedule->room_name
                        ?: 'Unknown Room',


                    /*
                    |--------------------------------------------------------------------------
                    | BUILDING
                    |--------------------------------------------------------------------------
                    */

                    'building' =>
                        $schedule->building_name
                        ?: 'Unknown Building',


                    /*
                    |--------------------------------------------------------------------------
                    | DEPARTMENT
                    |--------------------------------------------------------------------------
                    */

                    'department_id' =>
                        $schedule->department_id,

                    'department' =>
                        $schedule->department_name
                        ?: 'Unknown College',


                    /*
                    |--------------------------------------------------------------------------
                    | FACULTY
                    |--------------------------------------------------------------------------
                    */

                    'faculty_id' =>
                        $schedule->faculty_id,

                    'instructor' =>
                        $schedule->instructor
                        ?: $schedule->faculty_name
                        ?: 'Unknown Instructor',


                    /*
                    |--------------------------------------------------------------------------
                    | PROGRAM
                    |--------------------------------------------------------------------------
                    */

                    'program' =>
                        $schedule->program
                        ?: 'Unknown Program',

                    'merged_courses' => $mergeRows
                        ->get($schedule->id, collect())
                        ->map(function ($merge) {
                            $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                            if (!$subject) return null;
                            return [
                                'course_code' => $subject->course_code ?? $merge->facultySubject?->course_code,
                                'course_title' => $subject->course_title ?? $merge->facultySubject?->subject,
                                'program' => $subject->program?->code
                                    ?? $merge->facultySubject?->subjectCatalog?->program?->code
                                    ?? $merge->facultySubject?->program,
                                'major' => $subject->program?->major ?? $merge->facultySubject?->major,
                                'share_type' => $merge->share_type ?? 'merge',
                            ];
                        })
                        ->filter()
                        ->values(),

                ];
            })

            ->values(),
    ]);
}

public function mergeDepartments()
{
    if (
        !Auth::check() ||
        Auth::user()->role !== 'chair'
    ) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    /*
     |--------------------------------------------------------------------------
     | LOAD DEPARTMENTS
     |--------------------------------------------------------------------------
     |
     | Load:
     | Department
     |   → Buildings
     |       → Rooms
     |
     | The chair's own department is excluded because
     | manual merge browsing is only for other departments.
     |
     */

    $departments = Department::with([
        'buildings.rooms'
    ])
        ->where(
            'id',
            '!=',
            $chair->department_id
        )
        ->orderBy('name')
        ->get();

    return response()->json([
        'success' => true,

        'departments' => $departments->map(
            function ($department) {

                return [
                    'id' =>
                        $department->id,

                    'name' =>
                        $department->name,

                    'buildings' =>
                        $department->buildings
                            ->map(
                                function ($building) {

                                    return [
                                        'id' =>
                                            $building->id,

                                        'name' =>
                                            $building->name,

                                        'rooms' =>
                                            $building->rooms
                                                ->map(
                                                    function ($room) {

                                                        return [
                                                            'id' =>
                                                                $room->id,

                                                            'name' =>
                                                                $room->room_name,
                                                        ];

                                                    }
                                                )
                                                ->values(),
                                    ];

                                }
                            )
                            ->values(),
                ];

            }
        )->values(),
    ]);
}

public function mergeRoomSchedule($roomId)
{
    if (
        !Auth::check() ||
        Auth::user()->role !== 'chair'
    ) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $activeTerm = $this->resolveAcademicTerm(request());

    /*
     |--------------------------------------------------------------------------
     | FIND ROOM
     |--------------------------------------------------------------------------
     */

    $room = Room::with([
        'department',
        'building',
    ])->findOrFail($roomId);

    /*
     |--------------------------------------------------------------------------
     | PREVENT VIEWING OWN DEPARTMENT
     |--------------------------------------------------------------------------
     |
     | Manual merge browsing is only for other departments.
     |
     */

    if (
        (int) $room->department_id ===
        (int) $chair->department_id
    ) {
        abort(
            403,
            'You cannot browse your own college through merge.'
        );
    }

    /*
     |--------------------------------------------------------------------------
     | LOAD ROOM SCHEDULE
     |--------------------------------------------------------------------------
     */

$schedules = Schedule::with([
    'facultySubject.subjectCatalog.program',
    'merges' => function ($query) {
        $query
            ->where('status', 'approved')
            ->with([
                'department',
                'subject.program.department',
                'facultySubject.subjectCatalog.program.department',
            ]);
    }
])
    ->where(
        'room_id',
        $room->id
    )
    ->where('academic_term_id', $activeTerm->id)
    ->orderBy('row_position')
    ->orderBy('day')
    ->get();

$schedules->each(function ($schedule) {

    /*
    |--------------------------------------------------------------------------
    | GET ORIGINAL PROGRAM
    |--------------------------------------------------------------------------
    */

    $programs = collect();
    $programCodes = collect();

    if (
        $schedule->program &&
        trim($schedule->program) !== ''
    ) {
        $programs->push(
            trim($schedule->program)
        );
        $programCodes->push(
            $schedule->facultySubject?->subjectCatalog?->program?->code
                ?: (preg_match('/\(([^()]*)\)\s*$/u', trim($schedule->program), $matches) === 1
                    ? trim($matches[1])
                    : trim($schedule->program))
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET APPROVED MERGED PROGRAMS
    |--------------------------------------------------------------------------
    */

    $mergedCourses = collect();
    foreach ($schedule->merges as $merge) {
        $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
        if ($subject) {
            $mergedCourses->push([
                'course_code' => $subject->course_code ?? $merge->facultySubject?->course_code,
                'course_title' => $subject->course_title ?? $merge->facultySubject?->subject,
                'program' => $subject->program?->code
                    ?? $merge->facultySubject?->subjectCatalog?->program?->code
                    ?? $merge->facultySubject?->program,
            ]);
        }

        $mergedProgram = $subject?->program?->code
            ?? $merge->facultySubject?->subjectCatalog?->program?->code
            ?? $merge->facultySubject?->program;
        if ($merge->status === 'approved' && $mergedProgram) {

            $programs->push(
                trim((string) $mergedProgram)
            );
            $programCodes->push(trim((string) $mergedProgram));
        }
    }

    $schedule->merged_courses = $mergedCourses->values();


    /*
    |--------------------------------------------------------------------------
    | COMBINED PROGRAM
    |--------------------------------------------------------------------------
    */

    $schedule->program =
        $programs
            ->filter()
            ->unique()
            ->values()
            ->implode(' / ');

    $schedule->program_display = $programCodes
        ->map(function ($program) {
            $program = trim((string) $program);
            return preg_match('/\(([^()]*)\)\s*$/u', $program, $matches) === 1
                ? trim($matches[1])
                : $program;
        })
        ->filter()
        ->unique()
        ->implode(' / ');
});

    /*
     |--------------------------------------------------------------------------
     | RETURN ROOM + SCHEDULE
     |--------------------------------------------------------------------------
     */

    return response()->json([

        'success' => true,

        'room' => [

            'id' =>
                $room->id,

            'name' =>
                $room->room_name,
'building' =>
    $room->buildingRelation
        ? $room->buildingRelation->name
        : 'Unknown Building',

            'department' =>
                $room->department
                    ? $room->department->name
                    : 'Unknown College',
        ],

        'schedules' => $schedules->map(
            function ($schedule) use ($room) {

                return [

                    'id' =>
                        $schedule->id,

                    'department' => $room->department?->name,
                    'department_id' => $schedule->department_id,
                    'semester' => $schedule->semester,
                    'academic_year' => $schedule->academic_year,

                    'day' =>
                        $schedule->day,

                    'time' =>
                        $schedule->time,

                    'row_position' =>
                        $schedule->row_position,

                    'course_code' =>
                        $schedule->course_code,

                    'subject' =>
                        $schedule->subject,

                    'merged_courses' => $schedule->merged_courses ?? [],

                    'year_level' =>
                        $schedule->year_level,

                    'major' =>
                        $schedule->major,

                    'program' => $schedule->program ?: 'Unknown Program',
                    'program_display' => $schedule->program_display ?: $schedule->program ?: 'Unknown Program',
                    
                    'subject_type' =>
                        $schedule->subject_type,

                    'instructor' =>
                        $schedule->instructor,

                    'faculty_id' =>
                        $schedule->faculty_id,

                    'department_id' =>
                        $schedule->department_id,

                    'color' =>
                        $schedule->color,
                ];
            }
        )->values(),
    ]);
}

public function mergePrograms()
{
    if (
        !Auth::check() ||
        Auth::user()->role !== 'chair'
    ) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $programs = FacultySubject::whereHas(
        'faculty',
        function ($query) use ($chair) {
            $query->where(
                'department_id',
                $chair->department_id
            );
        }
    )
        ->whereNotNull('program')
        ->where('program', '!=', '')
        ->select('program')
        ->distinct()
        ->orderBy('program')
        ->pluck('program');

    return response()->json([
        'success' => true,
        'programs' => $programs->values(),
    ]);
}

public function mergeSubjects()
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $subjects = Subject::query()
        ->with(['program.department'])
        ->whereHas('program', function ($query) {
            $query->where('department_id', Auth::user()->department_id);
        })
        ->orderBy('course_code')
        ->orderBy('course_title')
        ->get()
        ->map(function (Subject $subject) {
            return [
                'id' => (int) $subject->id,
                'course_code' => $subject->course_code,
                'course_title' => $subject->course_title,
                'program_id' => (int) $subject->program_id,
                'program' => $subject->program?->name,
                'program_code' => $subject->program?->code,
                'department' => $subject->program?->department?->name,
                'year_level' => $subject->year_level,
                'semester' => $subject->semester,
                'major' => $subject->major,
                'subject_type' => Subject::typeFromCourseCode((string) $subject->course_code),
            ];
        })
        ->values();

    return response()->json([
        'success' => true,
        'subjects' => $subjects,
    ]);
}
public function createMergeRequest(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    if (
        !Auth::check() ||
        Auth::user()->role !== 'chair'
    ) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $activeTerm = $this->resolveAcademicTerm($request);


    /*
    |--------------------------------------------------------------------------
    | VALIDATE
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'schedule_id' => [
            'required',
            'integer',
            'exists:schedules,id',
        ],

        'program' => [
            'required',
            'string',
            'max:100',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | CHECK CHAIR DEPARTMENT
    |--------------------------------------------------------------------------
    */

    if (!$chair->department_id) {
        return response()->json([
            'success' => false,
            'message' =>
                'Your account is not assigned to a college.',
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | GET CLICKED SCHEDULE
    |--------------------------------------------------------------------------
    */

    $schedule = Schedule::where('id', $validated['schedule_id'])
        ->where('academic_term_id', $activeTerm->id)
        ->first();


    if (!$schedule) {
        return response()->json([
            'success' => false,
            'message' => 'Schedule not found.',
        ], 404);
    }


    /*
    |--------------------------------------------------------------------------
    | OWNER DEPARTMENT
    |--------------------------------------------------------------------------
    */

    $ownerDepartmentId =
        $schedule->department_id;


    if (!$ownerDepartmentId) {
        return response()->json([
            'success' => false,
            'message' =>
                'This schedule does not have an assigned college.',
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | PREVENT OWN DEPARTMENT MERGE
    |--------------------------------------------------------------------------
    */

    if (
        (int) $ownerDepartmentId ===
        (int) $chair->department_id
    ) {
        return response()->json([
            'success' => false,
            'message' =>
                'You cannot merge a class from your own department.',
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | FIND FACULTY SUBJECT FOR PARTICIPATING PROGRAM
    |--------------------------------------------------------------------------
    */

    $facultySubject = FacultySubject::where(
        'program',
        $validated['program']
    )
        ->whereHas(
            'faculty',
            function ($query) use ($chair) {

                $query->where(
                    'department_id',
                    $chair->department_id
                );

            }
        )
        ->first();


    if (!$facultySubject) {
        return response()->json([
            'success' => false,
            'message' =>
                'The selected program does not belong to your college.',
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | FIND ALL SCHEDULE CELLS THAT BELONG TO THIS SAME CLASS
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | MON 9:00 - 10:00  IT136
    | WED 9:00 - 10:00  IT136
    | FRI 9:00 - 10:00  IT136
    |
    | Clicking any one of these will find all three.
    |
    */

    $sameClassQuery = Schedule::query()
        ->where('academic_term_id', $activeTerm->id)
        ->where(
            'department_id',
            $schedule->department_id
        )
        ->where(
            'room_id',
            $schedule->room_id
        )
        ->where(
            'faculty_id',
            $schedule->faculty_id
        )
        ->where(
            'course_code',
            $schedule->course_code
        )
        ->where(
            'subject',
            $schedule->subject
        )
        ->where(
            'year_level',
            $schedule->year_level
        );


    /*
    |--------------------------------------------------------------------------
    | MATCH MAJOR
    |--------------------------------------------------------------------------
    */

    if (
        $schedule->major === null ||
        trim((string) $schedule->major) === ''
    ) {

        $sameClassQuery->where(function ($query) {

            $query->whereNull('major')
                ->orWhere('major', '');

        });

    } else {

        $sameClassQuery->where(
            'major',
            $schedule->major
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MATCH PROGRAM
    |--------------------------------------------------------------------------
    */

    if (
        $schedule->program === null ||
        trim((string) $schedule->program) === ''
    ) {

        $sameClassQuery->where(function ($query) {

            $query->whereNull('program')
                ->orWhere('program', '');

        });

    } else {

        $sameClassQuery->where(
            'program',
            $schedule->program
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MATCH SEMESTER
    |--------------------------------------------------------------------------
    */

    if (
        $schedule->semester === null ||
        trim((string) $schedule->semester) === ''
    ) {

        $sameClassQuery->where(function ($query) {

            $query->whereNull('semester')
                ->orWhere('semester', '');

        });

    } else {

        $sameClassQuery->where(
            'semester',
            $schedule->semester
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MATCH ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

    if (
        $schedule->academic_year === null ||
        trim((string) $schedule->academic_year) === ''
    ) {

        $sameClassQuery->where(function ($query) {

            $query->whereNull('academic_year')
                ->orWhere('academic_year', '');

        });

    } else {

        $sameClassQuery->where(
            'academic_year',
            $schedule->academic_year
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GET ALL CELLS
    |--------------------------------------------------------------------------
    */

    $sameClassSchedules =
        $sameClassQuery
            ->orderBy('row_position')
            ->orderBy('day')
            ->orderBy('time')
            ->get();


    /*
    |--------------------------------------------------------------------------
    | SAFETY
    |--------------------------------------------------------------------------
    */

    if ($sameClassSchedules->isEmpty()) {

        $sameClassSchedules =
            collect([$schedule]);

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK EXISTING MERGES
    |--------------------------------------------------------------------------
    */

    $scheduleIds =
        $sameClassSchedules
            ->pluck('id')
            ->values();


    $existingMerges =
        ScheduleMerge::whereIn(
            'schedule_id',
            $scheduleIds
        )
        ->where(
            'department_id',
            $chair->department_id
        )
        ->where('share_type', 'merge')
        ->whereIn(
            'status',
            [
                'pending',
                'approved',
            ]
        )
        ->get();


    /*
    |--------------------------------------------------------------------------
    | IF ALREADY FULLY MERGED
    |--------------------------------------------------------------------------
    */

    if (
        $existingMerges->count() >=
        $scheduleIds->count()
    ) {

        $hasApproved =
            $existingMerges->contains(
                function ($merge) {
                    return $merge->status === 'approved';
                }
            );

        return response()->json([
            'success' => false,
            'message' => $hasApproved
                ? 'Your college is already merged with this class.'
                : 'Your college already has a pending merge request for this class.',
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE MERGE REQUEST FOR EVERY CELL
    |--------------------------------------------------------------------------
    */

    $createdMerges = [];


    foreach ($sameClassSchedules as $classSchedule) {

        /*
        |--------------------------------------------------------------------------
        | CHECK IF THIS CELL ALREADY HAS A MERGE
        |--------------------------------------------------------------------------
        */

        $existingMerge =
            ScheduleMerge::where(
                'schedule_id',
                $classSchedule->id
            )
            ->where(
                'department_id',
                $chair->department_id
            )
            ->where('share_type', 'merge')
            ->whereIn(
                'status',
                [
                    'pending',
                    'approved',
                ]
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | SKIP ALREADY MERGED CELL
        |--------------------------------------------------------------------------
        */

        if ($existingMerge) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE MERGE
        |--------------------------------------------------------------------------
        */

        $merge =
            ScheduleMerge::create([
                'schedule_id' =>
                    $classSchedule->id,

                'department_id' =>
                    $chair->department_id,

                'faculty_subject_id' =>
                    $facultySubject->id,

                'status' =>
                    'pending',

                'approved_by' =>
                    null,

                'approved_at' =>
                    null,
            ]);


        $createdMerges[] =
            $merge->id;
    }


    /*
    |--------------------------------------------------------------------------
    | RETURN SUCCESS
    |--------------------------------------------------------------------------
    */

    return response()->json([
        'success' => true,

        'message' =>
            'Merge request submitted for the entire class.',

        'merge_ids' =>
            $createdMerges,

        'schedule_count' =>
            $scheduleIds->count(),

        'status' =>
            'pending',
    ]);
}
public function mergeLocalCourse(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $validated = $request->validate([
        'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
        'subject_id' => ['required', 'integer', 'exists:subjects,id'],
    ]);
    $activeTerm = $this->resolveAcademicTerm($request);

    $schedule = Schedule::with(['department', 'room'])
        ->where('id', $validated['schedule_id'])
        ->where('academic_term_id', $activeTerm->id)
        ->first();
    if (!$schedule) {
        return response()->json(['success' => false, 'message' => 'That schedule is not in the selected academic term.'], 404);
    }
    if ((int) $schedule->department_id !== (int) $chair->department_id) {
        return response()->json(['success' => false, 'message' => 'This class belongs to another college. Its chair must approve a merge request.'], 403);
    }

    $subject = Subject::with('program.department')->findOrFail($validated['subject_id']);
    if ((int) $subject->program?->department_id !== (int) $chair->department_id) {
        return response()->json(['success' => false, 'message' => 'Choose a subject from your college’s Manage Subjects list.'], 403);
    }

    if (
        mb_strtolower(trim((string) $schedule->course_code)) === mb_strtolower(trim((string) $subject->course_code))
        && mb_strtolower(trim((string) $schedule->subject)) === mb_strtolower(trim((string) $subject->course_title))
        && mb_strtolower(trim((string) $schedule->program)) === mb_strtolower(trim((string) ($subject->program?->name ?? '')))
    ) {
        return response()->json(['success' => false, 'message' => 'That subject already matches the scheduled course.'], 422);
    }

    $classSchedules = $this->courseMergeClassSchedules($schedule);
    if ($classSchedules->isEmpty()) $classSchedules = collect([$schedule]);
    $scheduleIds = $classSchedules->pluck('id')->map(fn ($id) => (int) $id)->values();

    $existing = ScheduleMerge::whereIn('schedule_id', $scheduleIds)
        ->where('department_id', $chair->department_id)
        ->where('share_type', 'merge')
        ->where('subject_id', $subject->id)
        ->get();
    if ($existing->contains(fn ($merge) => $merge->status === 'approved')) {
        return response()->json(['success' => false, 'message' => 'This subject is already merged with the selected class.'], 422);
    }
    if ($existing->contains(fn ($merge) => $merge->status === 'pending')) {
        return response()->json(['success' => false, 'message' => 'A merge request for this subject is already pending.'], 422);
    }

    $requestGroupId = (string) Str::uuid();
    $mergeIds = [];
    DB::transaction(function () use ($classSchedules, $chair, $subject, $requestGroupId, &$mergeIds) {
        foreach ($classSchedules as $classSchedule) {
            $mergeRow = ScheduleMerge::updateOrCreate(
                [
                    'schedule_id' => $classSchedule->id,
                    'department_id' => $chair->department_id,
                    'subject_id' => $subject->id,
                ],
                [
                    'requested_by' => $chair->id,
                    'faculty_subject_id' => null,
                    'request_group_id' => $requestGroupId,
                    'status' => 'approved',
                    'share_type' => 'merge',
                    'approved_by' => $chair->id,
                    'approved_at' => now(),
                    'decision_note' => null,
                ]
            );
            $mergeIds[] = (int) $mergeRow->id;
        }
    });

    return response()->json([
        'success' => true,
        'status' => 'approved',
        'message' => 'The subject has been added to this class and its matching meeting entries.',
        'schedule_ids' => $scheduleIds,
        'merge_ids' => $mergeIds,
        'course' => [
            'merge_id' => $mergeIds[0] ?? null,
            'department_id' => (int) $chair->department_id,
            'share_type' => 'merge',
            'id' => (int) $subject->id,
            'course_code' => $subject->course_code,
            'course_title' => $subject->course_title,
            'program' => $subject->program?->name,
            'program_code' => $subject->program?->code,
            'major' => $subject->program?->major,
            'year_level' => $subject->year_level,
            'semester' => $subject->semester,
            'subject_type' => Subject::typeFromCourseCode((string) $subject->course_code),
        ],
    ]);
}

public function createCourseMergeRequest(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $validated = $request->validate([
        'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
        'subject_id' => ['required', 'integer', 'exists:subjects,id'],
    ]);
    $activeTerm = $this->resolveAcademicTerm($request);

    $schedule = Schedule::with(['room', 'department', 'facultySubject'])
        ->where('id', $validated['schedule_id'])
        ->where('academic_term_id', $activeTerm->id)
        ->first();

    if (!$schedule) {
        return response()->json([
            'success' => false,
            'message' => 'That class is not in the selected academic term.',
        ], 404);
    }

    if ((int) $schedule->department_id === (int) $chair->department_id) {
        return response()->json([
            'success' => false,
            'message' => 'Choose a class owned by another college to request a course merge.',
        ], 422);
    }

    $subject = Subject::with(['program.department'])->findOrFail($validated['subject_id']);
    if ((int) $subject->program?->department_id !== (int) $chair->department_id) {
        return response()->json([
            'success' => false,
            'message' => 'Choose a course from your department’s Manage Subjects catalog.',
        ], 403);
    }

    if (
        strtoupper(trim((string) $schedule->course_code)) === strtoupper(trim((string) $subject->course_code))
        && trim((string) $schedule->subject) === trim((string) $subject->course_title)
        && trim((string) $schedule->program) === trim((string) $subject->program?->name)
    ) {
        return response()->json([
            'success' => false,
            'message' => 'This course is already the main course for the selected class.',
        ], 422);
    }

    $classSchedules = $this->courseMergeClassSchedules($schedule);
    if ($classSchedules->isEmpty()) {
        $classSchedules = collect([$schedule]);
    }

    $scheduleIds = $classSchedules->pluck('id')->map(fn ($id) => (int) $id)->values();
    $existing = ScheduleMerge::whereIn('schedule_id', $scheduleIds)
        ->where('department_id', $chair->department_id)
        ->where('share_type', 'merge')
        ->where('subject_id', $subject->id)
        ->get();

    if ($existing->contains(fn ($merge) => in_array($merge->status, ['pending', 'approved'], true))) {
        return response()->json([
            'success' => false,
            'message' => $existing->contains(fn ($merge) => $merge->status === 'approved')
                ? 'This course is already linked to the selected class.'
                : 'A request for this course and class is already waiting for review.',
        ], 422);
    }

    $requestGroupId = (string) Str::uuid();
    $existingBySchedule = $existing->keyBy('schedule_id');
    $mergeRecords = collect();

    foreach ($classSchedules as $classSchedule) {
        $merge = $existingBySchedule->get($classSchedule->id);
        if (!$merge) {
            $merge = new ScheduleMerge();
            $merge->schedule_id = $classSchedule->id;
            $merge->department_id = $chair->department_id;
        }

        $merge->requested_by = $chair->id;
        $merge->subject_id = $subject->id;
        $merge->faculty_subject_id = null;
        $merge->request_group_id = $requestGroupId;
        $merge->status = 'pending';
        $merge->share_type = 'merge';
        $merge->approved_by = null;
        $merge->approved_at = null;
        $merge->decision_note = null;
        $merge->save();
        $mergeRecords->push($merge);
    }

    $representative = $mergeRecords->first()->load([
        'schedule.room',
        'schedule.department',
        'department',
        'requester',
        'subject.program.department',
    ]);
    $this->notifyDepartmentChairs(
        (int) $schedule->department_id,
        new ScheduleMergeNotification($representative, 'requested')
    );

    return response()->json([
        'success' => true,
        'message' => 'Your request was sent to ' . ($schedule->department?->name ?? 'the owning college') . ' for approval.',
        'status' => 'pending',
        'request_group_id' => $requestGroupId,
        'schedule_count' => $classSchedules->count(),
        'course' => [
            'course_code' => $subject->course_code,
            'course_title' => $subject->course_title,
            'program' => $subject->program?->name,
        ],
    ]);
}

public function approveCourseMergeRequest(ScheduleMerge $merge)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    abort_if(($merge->share_type ?? 'merge') !== 'merge', 404);

    $merge->load(['schedule', 'subject.program', 'facultySubject.subjectCatalog.program']);
    if (!$merge->schedule || (int) $merge->schedule->department_id !== (int) Auth::user()->department_id) {
        abort(403, 'Only the class-owning college can approve this request.');
    }
    if ($merge->status !== 'pending') {
        return back()->with('error', 'This merge request has already been reviewed.');
    }

    $rows = $this->courseMergeRequestRows($merge)->where('status', 'pending')->get();
    if ($rows->isEmpty()) {
        return back()->with('error', 'The pending course merge request could not be found.');
    }

    $this->courseMergeRequestRows($merge)->where('status', 'pending')->update([
        'status' => 'approved',
        'approved_by' => Auth::id(),
        'approved_at' => now(),
        'decision_note' => null,
    ]);

    $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);
    $approvedRows = $this->courseMergeRequestRows($merge)->where('status', 'approved')->get();
    foreach ($approvedRows as $approvedRow) {
        $approvedRow->load(['schedule', 'subject.program', 'facultySubject.subjectCatalog.program']);
        $schedule = $approvedRow->schedule;
        $subject = $approvedRow->subject ?? $approvedRow->facultySubject?->subjectCatalog;
        $program = $subject?->program?->name ?? $approvedRow->facultySubject?->program;
        $yearLevel = $subject?->year_level ?? $approvedRow->facultySubject?->year_level;
        if (!$schedule || !$program || !$yearLevel) continue;

        $studentUpdates->recordForAudience(
            $schedule,
            (int) $approvedRow->department_id,
            (string) $program,
            (string) $yearLevel,
            'shared_class_added',
            'Shared class added to your timetable',
            trim(($subject?->course_code ?? $approvedRow->facultySubject?->course_code ?? $schedule->course_code) . ' — ' . ($subject?->course_title ?? $approvedRow->facultySubject?->subject ?? $schedule->subject))
                . ' is now included in the shared class timetable for ' . $schedule->day . ' ' . $schedule->time . '.',
            ['shared_class' => true]
        );
    }

    $this->notifyDepartmentChairs(
        (int) $merge->department_id,
        new ScheduleMergeNotification($merge->fresh(), 'approved')
    );

    return back()->with('success', 'The course was added to the shared class schedule.');
}

public function rejectCourseMergeRequest(Request $request, ScheduleMerge $merge)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    abort_if(($merge->share_type ?? 'merge') !== 'merge', 404);

    $validated = $request->validate([
        'decision_note' => ['nullable', 'string', 'max:1000'],
    ]);
    $merge->load(['schedule', 'subject.program', 'facultySubject.subjectCatalog.program']);
    if (!$merge->schedule || (int) $merge->schedule->department_id !== (int) Auth::user()->department_id) {
        abort(403, 'Only the class-owning college can decline this request.');
    }
    if ($merge->status !== 'pending') {
        return back()->with('error', 'This merge request has already been reviewed.');
    }

    $this->courseMergeRequestRows($merge)->where('status', 'pending')->update([
        'status' => 'rejected',
        'approved_by' => null,
        'approved_at' => null,
        'decision_note' => trim((string) ($validated['decision_note'] ?? '')) ?: null,
    ]);

    $merge->refresh();
    $this->notifyDepartmentChairs(
        (int) $merge->department_id,
        new ScheduleMergeNotification($merge, 'rejected')
    );

    return back()->with('success', 'The request was declined and the requesting college was notified.');
}

public function cancelCourseMergeRequest(ScheduleMerge $merge)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    abort_if(($merge->share_type ?? 'merge') !== 'merge', 404);

    $merge->load(['schedule', 'subject.program', 'facultySubject.subjectCatalog.program']);
    if ((int) $merge->department_id !== (int) Auth::user()->department_id) {
        abort(403, 'Only the requesting college can cancel this request.');
    }
    if ($merge->status !== 'pending') {
        return back()->with('error', 'Only a pending request can be cancelled.');
    }

    if ($merge->request_group_id) {
        $rows = ScheduleMerge::where('request_group_id', $merge->request_group_id);
    } else {
        $rows = ScheduleMerge::where('schedule_id', $merge->schedule_id)
            ->where('department_id', $merge->department_id)
            ->where('subject_id', $merge->subject_id);
    }

    $this->notifyDepartmentChairs(
        (int) $merge->schedule?->department_id,
        new ScheduleMergeNotification($merge, 'cancelled')
    );
    $rows->where('status', 'pending')->delete();

    return back()->with('success', 'The pending course merge request was cancelled.');
}

public function removeCourseMergeRequest(Request $request, ScheduleMerge $merge)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    abort_if(($merge->share_type ?? 'merge') !== 'merge', 404);

    $merge->load(['schedule', 'subject.program', 'facultySubject.subjectCatalog.program']);
    $actorDepartmentId = (int) Auth::user()->department_id;
    $ownerDepartmentId = (int) ($merge->schedule?->department_id ?? 0);
    $requestingDepartmentId = (int) $merge->department_id;
    if (!$merge->schedule || !in_array($actorDepartmentId, [$ownerDepartmentId, $requestingDepartmentId], true)) {
        abort(403, 'Only the class-owning or requesting college can remove this shared course.');
    }
    if ($merge->status !== 'approved') {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Only an active, approved merge can be removed.'], 422);
        }
        return back()->with('error', 'Only an approved course merge can be removed.');
    }

    $mergedSubject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
    $mergedProgram = $mergedSubject?->program?->name ?? $merge->facultySubject?->program;
    $mergedYear = $mergedSubject?->year_level ?? $merge->facultySubject?->year_level;
    $mergeRows = $this->courseMergeRequestRows($merge)->with('schedule')->get();
    if ($mergedProgram && $mergedYear) {
        foreach ($mergeRows as $mergeRow) {
            if (!$mergeRow->schedule) continue;
            app(\App\Services\StudentScheduleUpdateService::class)->recordForAudience(
                $mergeRow->schedule,
                (int) $mergeRow->department_id,
                (string) $mergedProgram,
                (string) $mergedYear,
                'shared_class_removed',
                'Shared class removed from your timetable',
                trim(($mergedSubject?->course_code ?? $merge->facultySubject?->course_code ?? $mergeRow->schedule->course_code) . ' — ' . ($mergedSubject?->course_title ?? $merge->facultySubject?->subject ?? $mergeRow->schedule->subject))
                    . ' is no longer included in the shared class timetable.'
            );
        }
    }

    $recipientDepartmentId = $actorDepartmentId === $ownerDepartmentId
        ? $requestingDepartmentId
        : $ownerDepartmentId;
    if ($recipientDepartmentId !== $actorDepartmentId) {
        $this->notifyDepartmentChairs($recipientDepartmentId, new ScheduleMergeNotification($merge, 'removed'));
    }
    $removedScheduleIds = $mergeRows->pluck('schedule_id')->map(fn ($id) => (int) $id)->unique()->values();
    $this->courseMergeRequestRows($merge)->delete();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'The merged course was removed from the class and all matching meetings.',
            'schedule_ids' => $removedScheduleIds,
        ]);
    }

    return back()->with('success', 'The course was removed from the shared class.');
}

private function courseMergeRequestRows(ScheduleMerge $merge)
{
    if ($merge->request_group_id) {
        return ScheduleMerge::where('request_group_id', $merge->request_group_id);
    }

    return ScheduleMerge::where('schedule_id', $merge->schedule_id)
        ->where('department_id', $merge->department_id)
        ->where('share_type', 'merge')
        ->when(
            $merge->subject_id,
            fn ($query) => $query->where('subject_id', $merge->subject_id),
            fn ($query) => $query->where('faculty_subject_id', $merge->faculty_subject_id)
        );
}

private function courseMergeClassSchedules(Schedule $schedule)
{
    $query = Schedule::query()
        ->where('academic_term_id', $schedule->academic_term_id)
        ->where('department_id', $schedule->department_id)
        ->where('room_id', $schedule->room_id)
        ->where('faculty_id', $schedule->faculty_id)
        ->where('course_code', $schedule->course_code)
        ->where('subject', $schedule->subject)
        ->where('year_level', $schedule->year_level);

    foreach (['major', 'program', 'semester', 'academic_year'] as $column) {
        if (trim((string) $schedule->{$column}) === '') {
            $query->where(function ($subQuery) use ($column) {
                $subQuery->whereNull($column)->orWhere($column, '');
            });
        } else {
            $query->where($column, $schedule->{$column});
        }
    }

    return $query->orderBy('row_position')->orderBy('day')->get();
}

private function notifyDepartmentChairs(int $departmentId, ScheduleMergeNotification $notification): void
{
    if ($departmentId <= 0) {
        return;
    }

    User::query()
        ->where('role', 'chair')
        ->where('department_id', $departmentId)
        ->get()
        ->each(fn (User $chair) => $chair->notify($notification));
}

/*
|--------------------------------------------------------------------------
| APPROVE LEGACY MERGE REQUEST
|--------------------------------------------------------------------------
*/

public function approveMergeRequest(
    ScheduleMerge $merge
) {
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    if (
        !Auth::check() ||
        Auth::user()->role !== 'chair'
    ) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | LOAD ORIGINAL SCHEDULE
    |--------------------------------------------------------------------------
    */

    $merge->load('schedule');


    if (!$merge->schedule) {

        return back()->with(
            'error',
            'The original schedule could not be found.'
        );
    }


    $schedule =
        $merge->schedule;


    /*
    |--------------------------------------------------------------------------
    | ONLY OWNER DEPARTMENT CAN APPROVE
    |--------------------------------------------------------------------------
    */

    if (
        (int) $schedule->department_id !==
        (int) $chair->department_id
    ) {
        abort(
            403,
            'You cannot approve this merge request.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FIND ALL CELLS OF THE SAME CLASS
    |--------------------------------------------------------------------------
    */

    $sameClassQuery =
        Schedule::query()

        ->where(
            'academic_term_id',
            $schedule->academic_term_id
        )

        ->where(
            'department_id',
            $schedule->department_id
        )

        ->where(
            'room_id',
            $schedule->room_id
        )

        ->where(
            'faculty_id',
            $schedule->faculty_id
        )

        ->where(
            'course_code',
            $schedule->course_code
        )

        ->where(
            'subject',
            $schedule->subject
        )

        ->where(
            'year_level',
            $schedule->year_level
        );


    /*
    |--------------------------------------------------------------------------
    | MATCH MAJOR
    |--------------------------------------------------------------------------
    */

    if (
        $schedule->major === null ||
        trim((string) $schedule->major) === ''
    ) {

        $sameClassQuery->where(function ($query) {

            $query->whereNull('major')
                ->orWhere('major', '');

        });

    } else {

        $sameClassQuery->where(
            'major',
            $schedule->major
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MATCH PROGRAM
    |--------------------------------------------------------------------------
    */

    if (
        $schedule->program === null ||
        trim((string) $schedule->program) === ''
    ) {

        $sameClassQuery->where(function ($query) {

            $query->whereNull('program')
                ->orWhere('program', '');

        });

    } else {

        $sameClassQuery->where(
            'program',
            $schedule->program
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MATCH SEMESTER
    |--------------------------------------------------------------------------
    */

    if (
        $schedule->semester === null ||
        trim((string) $schedule->semester) === ''
    ) {

        $sameClassQuery->where(function ($query) {

            $query->whereNull('semester')
                ->orWhere('semester', '');

        });

    } else {

        $sameClassQuery->where(
            'semester',
            $schedule->semester
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MATCH ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

    if (
        $schedule->academic_year === null ||
        trim((string) $schedule->academic_year) === ''
    ) {

        $sameClassQuery->where(function ($query) {

            $query->whereNull('academic_year')
                ->orWhere('academic_year', '');

        });

    } else {

        $sameClassQuery->where(
            'academic_year',
            $schedule->academic_year
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GET ALL SCHEDULE CELLS
    |--------------------------------------------------------------------------
    */

    $sameClassSchedules =
        $sameClassQuery->get();


    /*
    |--------------------------------------------------------------------------
    | GET SCHEDULE IDS
    |--------------------------------------------------------------------------
    */

    $scheduleIds =
        $sameClassSchedules
            ->pluck('id')
            ->values();


    /*
    |--------------------------------------------------------------------------
    | APPROVE ALL MERGES FOR THIS CLASS
    |--------------------------------------------------------------------------
    */

    ScheduleMerge::whereIn(
        'schedule_id',
        $scheduleIds
    )
    ->where(
        'department_id',
        $merge->department_id
    )
    ->where('share_type', 'merge')
    ->whereIn(
        'status',
        [
            'pending',
            'approved',
        ]
    )
    ->update([
        'status' =>
            'approved',

        'approved_by' =>
            $chair->id,

        'approved_at' =>
            now(),
    ]);

    $approvedMerges = ScheduleMerge::with([
        'schedule',
        'subject.program',
        'facultySubject.subjectCatalog.program',
    ])
        ->whereIn('schedule_id', $scheduleIds)
        ->where('department_id', $merge->department_id)
        ->where('status', 'approved')
        ->get();

    $studentUpdates = app(\App\Services\StudentScheduleUpdateService::class);
    foreach ($approvedMerges as $approvedMerge) {
        $sharedSchedule = $approvedMerge->schedule;
        $subject = $approvedMerge->subject ?? $approvedMerge->facultySubject?->subjectCatalog;
        $program = $subject?->program?->name ?? $approvedMerge->facultySubject?->program;
        $yearLevel = $subject?->year_level ?? $approvedMerge->facultySubject?->year_level;
        if (!$sharedSchedule || !$program || !$yearLevel) continue;

        $studentUpdates->recordForAudience(
            $sharedSchedule,
            (int) $approvedMerge->department_id,
            (string) $program,
            (string) $yearLevel,
            'shared_class_added',
            'Shared class added to your timetable',
            trim(($subject?->course_code ?? $approvedMerge->facultySubject?->course_code ?? $sharedSchedule->course_code) . ' — ' . ($subject?->course_title ?? $approvedMerge->facultySubject?->subject ?? $sharedSchedule->subject))
                . ' is now included in the shared class timetable for ' . $sharedSchedule->day . ' ' . $sharedSchedule->time . '.',
            ['shared_class' => true]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    return back()->with(
        'success',
        'The entire class merge was approved successfully.'
    );
}


/*
|--------------------------------------------------------------------------
| REJECT MERGE REQUEST
|--------------------------------------------------------------------------
*/

public function rejectMergeRequest(
    ScheduleMerge $merge
) {
    if (
        !Auth::check() ||
        Auth::user()->role !== 'chair'
    ) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    $merge->load('schedule');

    if (!$merge->schedule) {
        return back()->with(
            'error',
            'The original schedule could not be found.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ONLY OWNER DEPARTMENT CAN REJECT
    |--------------------------------------------------------------------------
    */

    if (
        (int) $merge->schedule->department_id !==
        (int) $chair->department_id
    ) {
        abort(403, 'You cannot reject this merge request.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    $merge->status = 'rejected';

    $merge->approved_by = null;

    $merge->approved_at = null;

    $merge->save();

    return back()->with(
        'success',
        'Merge request rejected.'
    );
}

/*
|--------------------------------------------------------------------------
| REMOVE APPROVED MERGE
|--------------------------------------------------------------------------
*/

public function removeMergeRequest(
    ScheduleMerge $merge
) {
    if (
        !Auth::check() ||
        Auth::user()->role !== 'chair'
    ) {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | LOAD ORIGINAL SCHEDULE
    |--------------------------------------------------------------------------
    */

    $merge->load('schedule');

    if (!$merge->schedule) {

        return back()->with(
            'error',
            'The original schedule could not be found.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ONLY OWNER CHAIR CAN REMOVE THE MERGE
    |--------------------------------------------------------------------------
    */

    if (
        (int) $merge->schedule->department_id !==
        (int) $chair->department_id
    ) {
        abort(
            403,
            'You cannot remove this merge.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ONLY APPROVED MERGES CAN BE REMOVED
    |--------------------------------------------------------------------------
    */

    if ($merge->status !== 'approved') {

        return back()->with(
            'error',
            'Only an approved merge can be removed.'
        );
    }

    $mergedSubject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
    $mergedProgram = $mergedSubject?->program?->name ?? $merge->facultySubject?->program;
    $mergedYear = $mergedSubject?->year_level ?? $merge->facultySubject?->year_level;
    if ($mergedProgram && $mergedYear) {
        app(\App\Services\StudentScheduleUpdateService::class)->recordForAudience(
            $merge->schedule,
            (int) $merge->department_id,
            (string) $mergedProgram,
            (string) $mergedYear,
            'shared_class_removed',
            'Shared class removed from your timetable',
            'The shared class ' . trim(($mergedSubject?->course_code ?? $merge->facultySubject?->course_code ?? $merge->schedule->course_code) . ' — ' . ($mergedSubject?->course_title ?? $merge->facultySubject?->subject ?? $merge->schedule->subject))
                . ' is no longer included in your timetable.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE PARTICIPATING DEPARTMENT
    |--------------------------------------------------------------------------
    */

    $merge->delete();

    /*
    |--------------------------------------------------------------------------
    | RETURN TO DASHBOARD
    |--------------------------------------------------------------------------
    */

    return back()->with(
        'success',
        'Merged program removed successfully.'
    );
}


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC TERM HELPERS
    |--------------------------------------------------------------------------
    */
private function resolveAcademicTerm(Request $request = null): AcademicTerm
{
    $termId = $request?->input('term_id')
        ?? $request?->query('term_id');

    if ($termId !== null && $termId !== '') {
        return $this->getActiveAcademicTerm((int) $termId);
    }

    /*
    |--------------------------------------------------------------------------
    | SESSION TERM
    |--------------------------------------------------------------------------
    |
    | Only use the session term when it is still the database ACTIVE term.
    | A previous session can contain an old term ID, and using that old
    | term would make Faculty Vacant Rooms load zero official time slots
    | even when the current active term already has them.
    |--------------------------------------------------------------------------
    */

    $sessionTermId = session('active_academic_term_id');

    if ($sessionTermId) {
        $sessionTerm = AcademicTerm::where('id', (int) $sessionTermId)
            ->where('is_active', true)
            ->first();

        if ($sessionTerm) {
            return $sessionTerm;
        }

        // Remove a stale session value so the database active term is used.
        session()->forget('active_academic_term_id');
    }

    return $this->getActiveAcademicTerm();
}

    /**
     * Only return schedules explicitly assigned to the selected academic term.
     * This prevents records from another year/semester from leaking into the view.
     */
    private function termScheduleQuery($query, AcademicTerm $term)
    {
        return $query->where('academic_term_id', $term->id);
    }

    private function getActiveAcademicTerm(?int $termId = null): AcademicTerm
{
    /*
    |--------------------------------------------------------------------------
    | REQUESTED TERM
    |--------------------------------------------------------------------------
    */

    if ($termId !== null) {
        // Selecting a term to VIEW must not change the system-wide active term.
        $requestedTerm = AcademicTerm::find($termId);

        if ($requestedTerm) {
            return $requestedTerm;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SESSION TERM
    |--------------------------------------------------------------------------
    */

    $sessionTermId = session(
        'active_academic_term_id'
    );

    if ($sessionTermId) {
        // A session may contain a term that another chair has since deactivated.
        $sessionTerm = AcademicTerm::where('id', (int) $sessionTermId)
            ->where('is_active', true)
            ->first();

        if ($sessionTerm) {
            return $sessionTerm;
        }

        session()->forget('active_academic_term_id');
    }

    /*
    |--------------------------------------------------------------------------
    | DATABASE ACTIVE TERM
    |--------------------------------------------------------------------------
    */

    $activeTerm = AcademicTerm::where(
        'is_active',
        true
    )->first();

    if ($activeTerm) {

        session()->put(
            'active_academic_term_id',
            $activeTerm->id
        );

        return $activeTerm;
    }

    /*
    |--------------------------------------------------------------------------
    | LAST TERM
    |--------------------------------------------------------------------------
    */

    $activeTerm = AcademicTerm::orderByDesc(
        'id'
    )->first();

    if ($activeTerm) {

        AcademicTerm::where(
            'id',
            '!=',
            $activeTerm->id
        )->update([
            'is_active' => false,
        ]);

        $activeTerm->update([
            'is_active' => true,
            'status' => 'active',
        ]);

        session()->put(
            'active_academic_term_id',
            $activeTerm->id
        );

        return $activeTerm;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE DEFAULT
    |--------------------------------------------------------------------------
    */

    $activeTerm = AcademicTerm::create([
        'academic_year' =>
            date('Y') . '-' . (date('Y') + 1),

        'semester' =>
            '1st Semester',

        'status' =>
            'active',

        'is_active' =>
            true,
    ]);

    session()->put(
        'active_academic_term_id',
        $activeTerm->id
    );

    return $activeTerm;
}
    /*
    |--------------------------------------------------------------------------
    | ACADEMIC TERMS / SCHEDULE LIBRARY
    |--------------------------------------------------------------------------
    */
public function academicTerms(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    /*
    |--------------------------------------------------------------------------
    | KEEP THE CURRENT ACTIVE TERM
    |--------------------------------------------------------------------------
    |
    | The ?view= parameter is ONLY for viewing.
    | It must NOT change the active academic term.
    |
    */

    $activeTerm = $this->getActiveAcademicTerm();

    $chairDepartmentId = (int) Auth::user()->department_id;


    /*
    |--------------------------------------------------------------------------
    | GET ALL ACADEMIC TERMS
    |--------------------------------------------------------------------------
    */

    $terms = AcademicTerm::withCount([
        'schedules as schedule_count' => function ($query) use ($chairDepartmentId) {

            $query
                ->where(
                    'department_id',
                    $chairDepartmentId
                );

        }
    ])
    ->orderByDesc('academic_year')
    ->orderByRaw(
        "CASE
            WHEN semester = '1st Semester' THEN 1
            ELSE 2
        END"
    )
    ->orderByDesc('id')
    ->get();

    // Count every database record for the destructive-delete warning,
    // including soft-deleted schedules that will also be physically removed.
    $allScheduleCounts = DB::table('schedules')
        ->select('academic_term_id', DB::raw('COUNT(*) as total_schedule_count'))
        ->whereNotNull('academic_term_id')
        ->groupBy('academic_term_id')
        ->pluck('total_schedule_count', 'academic_term_id');

    $terms->each(function (AcademicTerm $term) use ($allScheduleCounts) {
        $term->setAttribute('total_schedule_count', (int) $allScheduleCounts->get($term->id, 0));
    });


    /*
    |--------------------------------------------------------------------------
    | SELECTED TERM
    |--------------------------------------------------------------------------
    */

    $selectedTerm = null;

    $selectedSchedules = collect();


    /*
    |--------------------------------------------------------------------------
    | VIEW A SPECIFIC TERM
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | ONLY academic_term_id IS USED.
    |
    | There is deliberately NO fallback to:
    | semester + academic_year
    |
    | This guarantees:
    |
    | New Term = Empty
    |
    | Old Term = Only its own schedules
    |
    */

    if ($request->filled('view')) {

        $selectedTerm = AcademicTerm::find(
            (int) $request->input('view')
        );


        if ($selectedTerm) {

            $selectedSchedules = Schedule::query()
                ->with([
                    'room.building',
                    'faculty',
                ])

                /*
                |--------------------------------------------------------------
                | ONLY CHAIR'S DEPARTMENT
                |--------------------------------------------------------------
                */

                ->where(
                    'department_id',
                    $chairDepartmentId
                )

                /*
                |--------------------------------------------------------------
                | ONLY THE SELECTED ACADEMIC TERM
                |--------------------------------------------------------------
                */

                ->where(
                    'academic_term_id',
                    $selectedTerm->id
                )

                /*
                |--------------------------------------------------------------
                | ORDER
                |--------------------------------------------------------------
                */

                ->orderBy(
                    'room_id'
                )
                ->orderBy(
                    'row_position'
                )
                ->orderBy(
                    'day'
                )
                ->orderBy(
                    'time'
                )

                ->get();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RETURN LIBRARY
    |--------------------------------------------------------------------------
    */

    return view(
        'chair.academic-terms',
        compact(
            'terms',
            'activeTerm',
            'selectedTerm',
            'selectedSchedules'
        )
    );
}
    public function storeAcademicTerm(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'chair') {
            abort(403, 'Unauthorized.');
        }

        $data = $request->validate([
            'academic_year' => 'required|string|max:20|regex:/^\d{4}-\d{4}$/',
            'semester' => 'required|in:1st Semester,2nd Semester',
        ]);

        $term = AcademicTerm::firstOrCreate(
            [
                'academic_year' => trim($data['academic_year']),
                'semester' => $data['semester'],
            ],
            [
                'status' => 'saved',
                'is_active' => false,
            ]
        );

        if ($request->boolean('make_active')) {
            AcademicTerm::where('id', '!=', $term->id)->update(['is_active' => false]);
            $term->update([
                'is_active' => true,
                'status' => 'active',
            ]);
            session(['active_academic_term_id' => $term->id]);
        }

        return redirect()
            ->route('chair.schedule.library', ['view' => $term->id])
            ->with('success', $term->wasRecentlyCreated
                ? 'Academic term created successfully.'
                : 'Academic term already exists and is ready to use.');
    }

    public function activateAcademicTerm(Request $request, $termId)
    {
        if (!Auth::check() || Auth::user()->role !== 'chair') {
            abort(403, 'Unauthorized.');
        }

        $term = AcademicTerm::findOrFail($termId);

        DB::transaction(function () use ($term) {
            AcademicTerm::query()->update([
                'is_active' => false,
                'status' => DB::raw("CASE WHEN status = 'active' THEN 'saved' ELSE status END"),
            ]);

            $term->update([
                'is_active' => true,
                'status' => 'active',
            ]);
        });

        session(['active_academic_term_id' => $term->id]);

        $destination = $request->input('return_to') === 'setschedule'
            ? 'chair.setschedule'
            : 'chair.schedule.library';

        return redirect()
            ->route($destination)
            ->with('success', 'Active academic term changed to ' . $term->academic_year . ' • ' . $term->semester . '.');
    }

    public function activateSelectedAcademicTerm(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'chair') {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'term_id' => ['required', 'integer', 'exists:academic_terms,id'],
        ]);

        return $this->activateAcademicTerm($request, (int) $validated['term_id']);
    }

    public function deleteAcademicTerm(Request $request, $termId)
    {
        if (!Auth::check() || Auth::user()->role !== 'chair') {
            abort(403, 'Unauthorized.');
        }

        $term = AcademicTerm::findOrFail($termId);

        if ($term->is_active) {
            return redirect()
                ->route('chair.schedule.library')
                ->with('error', 'The active academic term cannot be deleted. Set another term as active first.');
        }

        $scheduleCount = DB::table('schedules')
            ->where('academic_term_id', $term->id)
            ->count();

        $confirmedScheduleCount = $request->input('confirmed_schedule_count');
        if (!is_numeric($confirmedScheduleCount) || (int) $confirmedScheduleCount !== $scheduleCount) {
            return redirect()
                ->route('chair.schedule.library')
                ->with('error', 'The schedule count changed or deletion was not confirmed. Reopen the confirmation card and review the current schedule count before deleting this term.');
        }

        DB::transaction(function () use ($term) {
            // Schedule-time rows and notifications have no cascading foreign
            // key, so remove their term-scoped records before deleting the term.
            if (Schema::hasTable('schedule_time_slots') && Schema::hasColumn('schedule_time_slots', 'academic_term_id')) {
                DB::table('schedule_time_slots')->where('academic_term_id', $term->id)->delete();
            }

            if (Schema::hasTable('student_schedule_updates') && Schema::hasColumn('student_schedule_updates', 'academic_term_id')) {
                DB::table('student_schedule_updates')->where('academic_term_id', $term->id)->delete();
            }

            // Use the query builder to physically remove every schedule row,
            // including soft-deleted records. Schedule-linked child records
            // follow their existing database foreign-key delete rules.
            DB::table('schedules')->where('academic_term_id', $term->id)->delete();

            $term->delete();
        });

        if ((int) session('active_academic_term_id') === (int) $term->id) {
            session()->forget('active_academic_term_id');
        }

        return redirect()
            ->route('chair.schedule.library')
            ->with('success', 'Academic term deleted successfully.');
    }

public function reuseAcademicSchedule(Request $request, $sourceTermId)
{
    /*
    |--------------------------------------------------------------------------
    | CHAIR AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE TARGET TERM
    |--------------------------------------------------------------------------
    */

    $data = $request->validate([
        'target_academic_year'
            => 'required|string|max:20|regex:/^\d{4}-\d{4}$/',

        'target_semester'
            => 'required|in:1st Semester,2nd Semester',
    ]);


    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR
    |--------------------------------------------------------------------------
    */

    $chair = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | SOURCE TERM
    |--------------------------------------------------------------------------
    */

    $source = AcademicTerm::findOrFail(
        $sourceTermId
    );


    /*
    |--------------------------------------------------------------------------
    | TARGET VALUES
    |--------------------------------------------------------------------------
    */

    $targetAcademicYear =
        trim(
            $data['target_academic_year']
        );

    $targetSemester =
        $data['target_semester'];


    /*
    |--------------------------------------------------------------------------
    | DO NOT COPY INTO THE SAME TERM
    |--------------------------------------------------------------------------
    */

    if (
        $source->academic_year ===
            $targetAcademicYear
        &&
        $source->semester ===
            $targetSemester
    ) {

        return back()->with(
            'error',
            'The target term must be different from the source term.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GET SOURCE SCHEDULES
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | We use the exact academic_term_id.
    |
    | This prevents schedules from another semester/year
    | from accidentally being copied.
    |
    */

    $sourceSchedules = Schedule::query()

        ->where(
            'department_id',
            $chair->department_id
        )

        ->where(
            'academic_term_id',
            $source->id
        )

        ->orderBy(
            'room_id'
        )

        ->orderBy(
            'row_position'
        )

        ->orderBy(
            'day'
        )

        ->orderBy(
            'time'
        )

        ->get();


    /*
    |--------------------------------------------------------------------------
    | SOURCE MUST CONTAIN SCHEDULES
    |--------------------------------------------------------------------------
    */

    if ($sourceSchedules->isEmpty()) {

        return back()->with(
            'error',

            'The selected academic term has no schedules to copy. Choose a term that already contains schedules.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CREATE / FIND TARGET TERM
    |--------------------------------------------------------------------------
    */

    $target = AcademicTerm::firstOrCreate(
        [
            'academic_year' =>
                $targetAcademicYear,

            'semester' =>
                $targetSemester,
        ],
        [
            'status' =>
                'saved',

            'is_active' =>
                false,
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | DO NOT OVERWRITE AN EXISTING TARGET SCHEDULE
    |--------------------------------------------------------------------------
    */

    $targetHasSchedules = Schedule::query()

        ->where(
            'department_id',
            $chair->department_id
        )

        ->where(
            'academic_term_id',
            $target->id
        )

        ->exists();


    if ($targetHasSchedules) {

        return back()->with(
            'error',

            'The target academic term already has schedules. Please choose another academic term.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COPY SCHEDULES
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        $sourceSchedules,
        $target,
        $chair
    ) {

        foreach (
            $sourceSchedules as $schedule
        ) {

            Schedule::create([

                /*
                |----------------------------------------------------------
                | ROOM
                |----------------------------------------------------------
                */

                'room_id' =>
                    $schedule->room_id,


                /*
                |----------------------------------------------------------
                | FACULTY
                |----------------------------------------------------------
                */

                'faculty_id' =>
                    $schedule->faculty_id,

                'faculty_subject_id' =>
                    $schedule->faculty_subject_id,


                /*
                |----------------------------------------------------------
                | DEPARTMENT
                |----------------------------------------------------------
                */

                'department_id' =>
                    $chair->department_id,


                /*
                |----------------------------------------------------------
                | IMPORTANT TERM LINK
                |----------------------------------------------------------
                */

                'academic_term_id' =>
                    $target->id,


                /*
                |----------------------------------------------------------
                | NEW TERM VALUES
                |----------------------------------------------------------
                */

                'semester' =>
                    $target->semester,

                'academic_year' =>
                    $target->academic_year,


                /*
                |----------------------------------------------------------
                | SCHEDULE INFORMATION
                |----------------------------------------------------------
                */

                'day' =>
                    $schedule->day,

                'time' =>
                    $schedule->time,

                'row_position' =>
                    $schedule->row_position,


                /*
                |----------------------------------------------------------
                | COURSE INFORMATION
                |----------------------------------------------------------
                */

                'course_code' =>
                    $schedule->course_code,

                'subject' =>
                    $schedule->subject,

                'year_level' =>
                    $schedule->year_level,

                'major' =>
                    $schedule->major,

                'program' =>
                    $schedule->program,

                'subject_type' =>
                    $schedule->subject_type,


                /*
                |----------------------------------------------------------
                | INSTRUCTOR / COLOR
                |----------------------------------------------------------
                */

                'instructor' =>
                    $schedule->instructor,

                'color' =>
                    $schedule->color
                    ?? '#ffffff',


                /*
                |----------------------------------------------------------
                | CURRICULUM COURSE
                |----------------------------------------------------------
                */


            ]);

        }

    });


    /*
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | DO NOT MAKE THE TARGET ACTIVE AUTOMATICALLY
    |--------------------------------------------------------------------------
    |
    | The user must explicitly click:
    |
    |       Set Active
    |
    | after copying.
    |
    | This keeps the active semester unchanged until the user chooses it.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | CLEAR ACTIVE STATE ON TARGET
    |--------------------------------------------------------------------------
    |
    | The target is a saved term.
    |
    */

    $target->update([
        'is_active' => false,
        'status' => 'saved',
    ]);


    /*
    |--------------------------------------------------------------------------
    | REDIRECT TO THE NEW COPIED TERM
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'chair.schedule.library',
            [
                'view' => $target->id
            ]
        )
        ->with(
            'success',

            'Schedule copied successfully. ' .
            $sourceSchedules->count() .
            ' schedule(s) were copied into ' .
            $target->academic_year .
            ' • ' .
            $target->semester .
            '. Click Set Active to use and edit this copied schedule.'
        );
}
    /*
    |--------------------------------------------------------------------------
    | CURRICULUM
    |--------------------------------------------------------------------------
    */
private function findFacultyScheduleConflict(
        int $facultyId,
        string $day,
        string $time,
        int $academicTermId,
        int|array|null $ignoreScheduleId = null
    ): ?Schedule {
        $requestedRange = $this->scheduleTimeRangeToMinutes($time);

        if (!$requestedRange) {
            return null;
        }

        $query = Schedule::where('academic_term_id', $academicTermId)
            ->where('faculty_id', $facultyId)
            ->whereRaw('UPPER(day) = ?', [strtoupper(trim($day))]);

        if (is_array($ignoreScheduleId) && !empty($ignoreScheduleId)) {
            $query->whereNotIn('id', array_map('intval', $ignoreScheduleId));
        } elseif ($ignoreScheduleId) {
            $query->where('id', '!=', (int) $ignoreScheduleId);
        }

        return $query->get()->first(function (Schedule $schedule) use ($requestedRange) {
            $existingRange = $this->scheduleTimeRangeToMinutes($schedule->time);

            if (!$existingRange) {
                return false;
            }

            return $requestedRange['start'] < $existingRange['end']
                && $requestedRange['end'] > $existingRange['start'];
        });
    }

    private function findFacultyScheduleConflictForCourse(
        int $facultyId,
        string $day,
        string $time,
        int $academicTermId,
        string $courseCode,
        int|array|null $ignoreScheduleId = null,
        bool $ignoreSameCourse = false,
        ?int $currentRoomId = null
    ): ?Schedule {
        $requestedRange = $this->scheduleTimeRangeToMinutes($time);
        if (!$requestedRange) {
            return null;
        }

        $query = Schedule::where('academic_term_id', $academicTermId)
            ->where('faculty_id', $facultyId)
            ->whereRaw('UPPER(day) = ?', [strtoupper(trim($day))]);

        if (is_array($ignoreScheduleId) && !empty($ignoreScheduleId)) {
            $query->whereNotIn('id', array_map('intval', $ignoreScheduleId));
        } elseif ($ignoreScheduleId) {
            $query->where('id', '!=', (int) $ignoreScheduleId);
        }

        $normalizedCourseCode = strtoupper(trim($courseCode));

        return $query->get()->first(function (Schedule $schedule) use ($requestedRange, $normalizedCourseCode, $ignoreSameCourse, $currentRoomId) {
            if ($ignoreSameCourse
                && ($currentRoomId === null || (int) $schedule->room_id !== $currentRoomId)
                && strtoupper(trim((string) $schedule->course_code)) === $normalizedCourseCode) {
                return false;
            }

            $existingRange = $this->scheduleTimeRangeToMinutes($schedule->time);
            return $existingRange
                && $requestedRange['start'] < $existingRange['end']
                && $requestedRange['end'] > $existingRange['start'];
        });
    }

    /**
     * Detect conflicts among schedules submitted in the same request before
     * those schedules have been written to the database.
     */
    private function findPendingFacultyScheduleConflict(
        int $facultyId,
        string $day,
        string $time,
        array $pendingSchedules
    ): ?array {
        $requestedRange = $this->scheduleTimeRangeToMinutes($time);

        if (!$requestedRange) {
            return null;
        }

        foreach ($pendingSchedules as $pending) {
            if (
                (int) ($pending['faculty_id'] ?? 0) !== $facultyId
                || strtoupper(trim($pending['day'] ?? '')) !== strtoupper(trim($day))
            ) {
                continue;
            }

            $existingRange = $this->scheduleTimeRangeToMinutes($pending['time'] ?? '');

            if (!$existingRange) {
                continue;
            }

            if (
                $requestedRange['start'] < $existingRange['end']
                && $requestedRange['end'] > $existingRange['start']
            ) {
                return $pending;
            }
        }

        return null;
    }

    /**
     * Convert a timetable range such as 8:00-9:00 or 1:00 PM-2:30 PM
     * into comparable minute values.
     */
    private function scheduleTimeRangeToMinutes(string $time): ?array
    {
        $raw = trim(str_replace(['–', '—'], '-', $time));

        if ($raw === '') {
            return null;
        }

        $parts = preg_split('/\s*-\s*/', $raw, 2);

        if (count($parts) !== 2) {
            return null;
        }

        $parsePart = function (string $value): ?int {
            $value = trim($value);

            if ($value === '') {
                return null;
            }

            if (!preg_match('/^(\d{1,2})(?::(\d{2}))?\s*(AM|PM)?$/i', $value, $match)) {
                return null;
            }

            $hour = (int) $match[1];
            $minute = isset($match[2]) && $match[2] !== '' ? (int) $match[2] : 0;
            $meridiem = strtoupper($match[3] ?? '');

            if ($minute < 0 || $minute > 59) {
                return null;
            }

            if ($meridiem !== '') {
                if ($hour < 1 || $hour > 12) {
                    return null;
                }

                if ($meridiem === 'AM' && $hour === 12) {
                    $hour = 0;
                } elseif ($meridiem === 'PM' && $hour !== 12) {
                    $hour += 12;
                }
            } else {
                if ($hour > 23) {
                    return null;
                }

                // Chair timetable labels use 1:00–6:00 for afternoon slots.
                if ($hour >= 1 && $hour <= 6) {
                    $hour += 12;
                }
            }

            return ($hour * 60) + $minute;
        };

        $start = $parsePart($parts[0]);
        $end = $parsePart($parts[1]);

        // In the Chair sheet, the end of the final afternoon row is shown as
        // "7:00" without a PM suffix. Inherit PM when the end would otherwise
        // fall before its start (for example, 6:00-7:00).
        $endHasMeridiem = preg_match('/\b(?:AM|PM)\s*$/i', trim($parts[1])) === 1;
        if ($start !== null && $end !== null && !$endHasMeridiem && $end <= $start) {
            preg_match('/^(\d{1,2})/', trim($parts[1]), $endHourMatch);
            $endHour = (int) ($endHourMatch[1] ?? 0);
            if ($endHour >= 1 && $endHour <= 11) {
                $end += 12 * 60;
            }
        }

        if ($start === null || $end === null || $end <= $start) {
            return null;
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * Reject overlapping entries submitted for the same room and day.
     */
    private function findSubmittedRoomScheduleConflict(iterable $entries): ?array
    {
        $pending = [];

        foreach ($entries as $entry) {
            $day = strtoupper(trim((string) data_get($entry, 'day', '')));
            $time = trim((string) data_get($entry, 'time', ''));
            $range = $this->scheduleTimeRangeToMinutes($time);

            if ($day === '' || !$range) {
                return ['message' => 'Each schedule needs a valid start and end time.'];
            }

            foreach ($pending as $existing) {
                if ($existing['day'] !== $day) {
                    continue;
                }

                if ($range['start'] < $existing['end'] && $range['end'] > $existing['start']) {
                    return [
                        'message' => 'Two schedules in this room overlap on ' . $day
                            . ': ' . $existing['time'] . ' and ' . $time
                            . '. Adjust the cell times so they do not overlap.',
                    ];
                }
            }

            $pending[] = [
                'day' => $day,
                'time' => $time,
                'start' => $range['start'],
                'end' => $range['end'],
            ];
        }

        return null;
    }

    private function formatScheduleConflictMessage(Schedule $schedule): string
    {
        $day = strtoupper(trim((string) $schedule->day));
        $time = trim((string) $schedule->time);
        $roomName = $schedule->room?->room_name;

        return $this->formatScheduleTimeForMessage($day, $time)
            . ($roomName ? ' in Room ' . $roomName : '');
    }

    private function formatScheduleTimeForMessage(string $day, string $time): string
    {
        return $day . ' from ' . $time;
    }

    private function syncScheduleTimeSlot(
        AcademicTerm $academicTerm,
        string $day,
        string $time,
        ?int $rowPosition = null
    ): ?ScheduleTimeSlot {
        $day = strtoupper(trim($day));
        $time = trim($time);

        if ($day === '' || $time === '') {
            return null;
        }

        $parts = preg_split('/\s*-\s*/', $time, 2);

        if (count($parts) !== 2) {
            return null;
        }

        [$start, $end] = $this->normalizeScheduleRangeClocks(trim($parts[0]), trim($parts[1]));

        if ($start === null || $end === null || $end <= $start) {
            return null;
        }

        return ScheduleTimeSlot::updateOrCreate(
            [
                'academic_term_id' => $academicTerm->id,
                'day' => $day,
                'start_time' => $start,
                'end_time' => $end,
            ],
            [
                'row_position' => $rowPosition,
                'is_active' => true,
            ]
        );
    }

    /**
     * Convert a timetable clock value into a database time string.
     *
     * Existing Chair timetable rows use simple values such as 8:00, 1:00,
     * or values containing AM/PM. For legacy rows without AM/PM, 1:00-5:00
     * are treated as afternoon to match the existing timetable behavior.
     */
    private function normalizeScheduleClock(string $value): ?string
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        try {
            if (preg_match('/\b(am|pm)\b/i', $value)) {
                return Carbon::parse($value)->format('H:i:s');
            }

            if (!preg_match('/^(\d{1,2})(?::(\d{2}))?$/', $value, $matches)) {
                return null;
            }

            $hour = (int) $matches[1];
            $minute = isset($matches[2]) ? (int) $matches[2] : 0;

            if ($hour < 0 || $hour > 23 || $minute < 0 || $minute > 59) {
                return null;
            }

            // Preserve the Chair timetable's afternoon convention.
            if ($hour >= 1 && $hour <= 6) {
                $hour += 12;
            }

            return sprintf('%02d:%02d:00', $hour, $minute);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Normalize a timetable range as a pair so an omitted PM suffix on the
     * final end time (6:00-7:00) inherits the afternoon period correctly.
     *
     * @return array{0: ?string, 1: ?string}
     */
    private function normalizeScheduleRangeClocks(string $startValue, string $endValue): array
    {
        $start = $this->normalizeScheduleClock($startValue);
        $end = $this->normalizeScheduleClock($endValue);
        $endHasMeridiem = preg_match('/\b(?:AM|PM)\s*$/i', trim($endValue)) === 1;

        if ($start !== null && $end !== null && !$endHasMeridiem && $end <= $start) {
            preg_match('/^(\d{1,2})/', trim($endValue), $endHourMatch);
            $endHour = (int) ($endHourMatch[1] ?? 0);
            if ($endHour >= 1 && $endHour <= 11) {
                $end = Carbon::parse($end)->addHours(12)->format('H:i:s');
            }
        }

        return [$start, $end];
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
                    $end = Carbon::parse(
                        Carbon::parse($reservation->date)->format('Y-m-d') .
                        ' ' .
                        trim((string) $reservation->end_time)
                    );

                    if ($end->lessThanOrEqualTo($now)) {
                        $reservation->update(['status' => 'Expired']);
                    }
                } catch (\Throwable $e) {
                    // Leave malformed legacy rows untouched.
                }
            });
    }

    private function isChairUser(): bool
{
    return Auth::check()
        && Auth::user()->role === 'chair';
}

private function chairCanManageFaculty(User $chair, User $faculty): bool
{
    if ((int) $faculty->department_id === (int) $chair->department_id) {
        return true;
    }

    return DB::table('department_faculty_imports')
        ->where('department_id', $chair->department_id)
        ->where('faculty_id', $faculty->id)
        ->exists();
}
public function manageSubjects(Request $request, $programId = null, $memorandumId = null)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    $programRows = Program::query()
        ->where('department_id', $chair->department_id)
        ->withCount('subjects')
        ->orderBy('id')
        ->get();

    $groupKey = fn (Program $program) => strtolower(trim($program->name)) . '|' . strtoupper(trim($program->code));
    $programGroups = $programRows->groupBy($groupKey)->map(function ($entries) {
        $baseProgram = $entries->first(fn (Program $entry) => trim((string) $entry->memorandum) === '')
            ?? $entries->first();
        $memoranda = $entries
            ->filter(fn (Program $entry) => trim((string) $entry->memorandum) !== '')
            ->sortBy('id')
            ->values();

        return (object) [
            'program' => $baseProgram,
            'memoranda' => $memoranda,
            'subjects_count' => (int) $entries->sum('subjects_count'),
        ];
    })->sortBy(fn ($group) => strtolower($group->program->name))->values();

    $selectedProgram = null;
    $selectedMemoranda = collect();
    $selectedMemorandum = null;
    $subjectsByYear = collect();
    $sheetYearLevels = $yearLevels;

    if ($programId !== null) {
        $requestedProgram = $programRows->firstWhere('id', (int) $programId);
        abort_unless($requestedProgram, 404);

        $selectedRows = $programRows->filter(fn (Program $entry) => $groupKey($entry) === $groupKey($requestedProgram));
        $selectedProgram = $selectedRows->first(fn (Program $entry) => trim((string) $entry->memorandum) === '')
            ?? $selectedRows->first();
        $selectedMemoranda = $selectedRows
            ->filter(fn (Program $entry) => trim((string) $entry->memorandum) !== '')
            ->sortBy('id')
            ->values();

        if ($memorandumId !== null) {
            $selectedMemorandum = $selectedMemoranda->firstWhere('id', (int) $memorandumId);
            abort_unless($selectedMemorandum, 404);
            if ($selectedMemorandum->year_level) {
                abort_unless(in_array($selectedMemorandum->year_level, $yearLevels, true), 404);
                $sheetYearLevels = [$selectedMemorandum->year_level];
            }
            $subjectsByYear = $selectedMemorandum->subjects()
                ->orderBy('year_level')
                ->orderBy('semester')
                ->orderBy('course_code')
                ->get()
                ->groupBy('year_level');
        }
    }

    return view('chair.managesubjects', compact(
        'programGroups',
        'selectedProgram',
        'selectedMemoranda',
        'selectedMemorandum',
        'subjectsByYear',
        'yearLevels',
        'sheetYearLevels'
    ));
}

private function programGroupQuery(Program $program)
{
    return Program::query()
        ->where('department_id', $program->department_id)
        ->whereRaw('LOWER(name) = ?', [strtolower(trim($program->name))])
        ->whereRaw('UPPER(code) = ?', [strtoupper(trim($program->code))]);
}

public function storeProgram(Request $request)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);

    $data = $request->validate([
        'name' => ['required', 'string', 'max:200'],
        'code' => ['required', 'string', 'max:40'],
    ]);

    $name = trim($data['name']);
    $code = strtoupper(trim($data['code']));
    $existing = Program::query()
        ->where('department_id', $chair->department_id)
        ->whereRaw('LOWER(name) = ?', [strtolower($name)])
        ->whereRaw('UPPER(code) = ?', [$code])
        ->orderBy('id')
        ->first();

    if ($existing) {
        return redirect()->route('chair.managesubjects.program', $existing->id)
            ->with('success', 'This program is already set up. Add another memorandum from its program page.');
    }

    $program = Program::create([
        'department_id' => $chair->department_id,
        'name' => $name,
        'code' => $code,
        'memorandum' => '',
        'major' => null,
        'year_level' => null,
    ]);

    return redirect()->route('chair.managesubjects.program', $program->id)
        ->with('success', 'Program created. Add a curriculum memorandum and major to continue.');
}

public function updateProgram(Request $request, $programId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);

    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);
    $data = $request->validate([
        'name' => ['required', 'string', 'max:200'],
        'code' => ['required', 'string', 'max:40'],
    ]);

    $name = trim($data['name']);
    $code = strtoupper(trim($data['code']));
    $duplicate = Program::query()
        ->where('department_id', $chair->department_id)
        ->whereRaw('LOWER(name) = ?', [strtolower($name)])
        ->whereRaw('UPPER(code) = ?', [$code])
        ->where(function ($query) use ($program) {
            $query->where('name', '!=', $program->name)->orWhere('code', '!=', $program->code);
        })
        ->exists();

    if ($duplicate) {
        return back()->withInput()->with('error', 'A program with that name and code already exists.');
    }

    $entries = $this->programGroupQuery($program)->get();
    $ids = $entries->pluck('id');
    Program::whereIn('id', $ids)->update(['name' => $name, 'code' => $code]);

    $subjectIds = Subject::whereIn('program_id', $ids)->pluck('id');
    if ($subjectIds->isNotEmpty()) {
        FacultySubject::whereIn('subject_id', $subjectIds)->update([
            'program' => $name . ' (' . $code . ')',
        ]);
    }

    return redirect()->route('chair.managesubjects.program', $entries->first()->id)
        ->with('success', 'Program details updated.');
}

public function deleteProgram($programId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);

    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);
    $this->programGroupQuery($program)->delete();

    return redirect()->route('chair.managesubjects')
        ->with('success', 'Program and its memorandum subject sheets were removed. Existing instructor assignments keep their saved details.');
}

public function storeProgramMemorandum(Request $request, $programId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);

    $data = $request->validate([
        'memorandum' => ['required', 'string', 'max:500'],
        'major' => ['nullable', 'string', 'max:120'],
        'year_level' => ['nullable', 'in:1st Year,2nd Year,3rd Year,4th Year'],
    ]);
    $memorandumText = trim($data['memorandum']);
    $major = trim((string) ($data['major'] ?? '')) ?: null;
    $yearLevel = $data['year_level'] ?: null;

    $duplicate = $this->programGroupQuery($program)
        ->where('memorandum', $memorandumText)
        ->where(function ($query) use ($major) {
            $major === null ? $query->whereNull('major') : $query->where('major', $major);
        })
        ->where(function ($query) use ($yearLevel) {
            $yearLevel === null ? $query->whereNull('year_level') : $query->where('year_level', $yearLevel);
        })
        ->exists();
    if ($duplicate) {
        return back()->withInput()->with('error', 'That memorandum is already listed for the selected year level.');
    }

    $memorandum = Program::create([
        'department_id' => $program->department_id,
        'name' => $program->name,
        'code' => strtoupper(trim($program->code)),
        'memorandum' => $memorandumText,
        'major' => $major,
        'year_level' => $yearLevel,
    ]);

    $baseProgram = $this->programGroupQuery($program)->orderBy('id')->first();
    return redirect()->route('chair.managesubjects.memorandum', [$baseProgram->id, $memorandum->id])
        ->with('success', 'Curriculum memorandum added. Add subjects in the year-level sheets below.');
}

public function updateProgramMemorandum(Request $request, $programId, $memorandumId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);
    $memorandum = $this->programGroupQuery($program)->whereKey($memorandumId)->findOrFail($memorandumId);

    $data = $request->validate([
        'memorandum' => ['required', 'string', 'max:500'],
        'major' => ['nullable', 'string', 'max:120'],
        'year_level' => ['nullable', 'in:1st Year,2nd Year,3rd Year,4th Year'],
    ]);
    $yearLevel = $data['year_level'] ?: null;

    if ($yearLevel && $memorandum->subjects()->where('year_level', '!=', $yearLevel)->exists()) {
        return back()->withInput()->with('error', 'Move or remove subjects from other year levels before limiting this memorandum to one year.');
    }

    $duplicate = $this->programGroupQuery($program)
        ->where('id', '!=', $memorandum->id)
        ->where('memorandum', trim($data['memorandum']))
        ->where(function ($query) use ($data) {
            $major = trim((string) ($data['major'] ?? '')) ?: null;
            $major === null ? $query->whereNull('major') : $query->where('major', $major);
        })
        ->where(function ($query) use ($yearLevel) {
            $yearLevel === null ? $query->whereNull('year_level') : $query->where('year_level', $yearLevel);
        })
        ->exists();
    if ($duplicate) {
        return back()->withInput()->with('error', 'That memorandum is already listed for the selected year level.');
    }

    $memorandum->update([
        'memorandum' => trim($data['memorandum']),
        'major' => trim((string) ($data['major'] ?? '')) ?: null,
        'year_level' => $yearLevel,
    ]);
    $this->syncFacultySubjectCatalogSnapshot($memorandum);

    $baseProgram = $this->programGroupQuery($program)->orderBy('id')->first();
    return redirect()->route('chair.managesubjects.memorandum', [$baseProgram->id, $memorandum->id])
        ->with('success', 'Memorandum details updated.');
}

public function deleteProgramMemorandum($programId, $memorandumId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);
    $memorandum = $this->programGroupQuery($program)->whereKey($memorandumId)->findOrFail($memorandumId);
    $groupName = $program->name;
    $groupCode = $program->code;
    $departmentId = $program->department_id;
    $memorandum->delete();

    $remaining = Program::query()
        ->where('department_id', $departmentId)
        ->whereRaw('LOWER(name) = ?', [strtolower(trim($groupName))])
        ->whereRaw('UPPER(code) = ?', [strtoupper(trim($groupCode))])
        ->orderBy('id')
        ->first();

    if (!$remaining) {
        $remaining = Program::create([
            'department_id' => $departmentId,
            'name' => $groupName,
            'code' => $groupCode,
            'memorandum' => '',
            'major' => null,
            'year_level' => null,
        ]);
    }

    return redirect()->route('chair.managesubjects.program', $remaining->id)
        ->with('success', 'Memorandum and its catalog subjects were removed. Existing instructor assignments keep their saved details.');
}

private function syncFacultySubjectCatalogSnapshot(Program $memorandum): void
{
    $subjectIds = $memorandum->subjects()->pluck('id');
    if ($subjectIds->isEmpty()) {
        return;
    }

    FacultySubject::whereIn('subject_id', $subjectIds)->update([
        'program' => $memorandum->name . ' (' . $memorandum->code . ')',
        'major' => $memorandum->major,
    ]);
}

public function saveCurriculumSheet(Request $request, $programId, $memorandumId, $year)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $year = rawurldecode((string) $year);
    $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    abort_unless(in_array($year, $yearLevels, true), 404);

    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);
    $memorandum = $this->programGroupQuery($program)
        ->whereKey($memorandumId)
        ->where('memorandum', '!=', '')
        ->findOrFail($memorandumId);
    if ($memorandum->year_level) {
        abort_unless($memorandum->year_level === $year, 404);
    }

    $data = $request->validate([
        'rows' => ['nullable', 'array'],
        'rows.*.first_id' => ['nullable', 'integer'],
        'rows.*.first_code' => ['nullable', 'string', 'max:50'],
        'rows.*.first_title' => ['nullable', 'string', 'max:255'],
        'rows.*.second_id' => ['nullable', 'integer'],
        'rows.*.second_code' => ['nullable', 'string', 'max:50'],
        'rows.*.second_title' => ['nullable', 'string', 'max:255'],
    ]);

    $rows = $data['rows'] ?? [];
    $existing = $memorandum->subjects()->where('year_level', $year)->get()->keyBy('id');
    $entries = [];
    $keptIds = [];
    $seenCourseCodes = [];
    $semesterFields = [
        'first' => '1st Semester',
        'second' => '2nd Semester',
    ];

    foreach ($rows as $row) {
        foreach ($semesterFields as $prefix => $semester) {
            $code = trim((string) ($row[$prefix . '_code'] ?? ''));
            $title = trim((string) ($row[$prefix . '_title'] ?? ''));
            $subjectId = $row[$prefix . '_id'] ?? null;

            if ($code === '' && $title === '') {
                continue;
            }
            if ($code === '' || $title === '') {
                return back()->withInput()->withErrors([
                    'sheet' => 'Fill in both the course code and course title for every subject row.',
                ]);
            }

            $normalizedCode = strtoupper($code);
            $duplicateKey = $semester . '|' . $normalizedCode;
            if (isset($seenCourseCodes[$duplicateKey])) {
                return back()->withInput()->withErrors([
                    'sheet' => 'A course code can appear only once in each semester of this year level.',
                ]);
            }
            $seenCourseCodes[$duplicateKey] = true;

            if ($subjectId !== null && $subjectId !== '') {
                $subjectId = (int) $subjectId;
                abort_unless($existing->has($subjectId) && !isset($keptIds[$subjectId]), 404);
                $keptIds[$subjectId] = true;
            }

            $entries[] = [
                'id' => $subjectId ? (int) $subjectId : null,
                'semester' => $semester,
                'course_code' => $normalizedCode,
                'course_title' => $title,
            ];
        }
    }

    DB::transaction(function () use ($memorandum, $year, $entries, $existing, $keptIds) {
        foreach ($entries as $entry) {
            $subject = $entry['id']
                ? $existing->get($entry['id'])
                : new Subject();
            $subject->fill([
                'program_id' => $memorandum->id,
                'year_level' => $year,
                'semester' => $entry['semester'],
                'course_code' => $entry['course_code'],
                'course_title' => $entry['course_title'],
            ]);
            $subject->save();

            FacultySubject::where('subject_id', $subject->id)->update([
                'course_code' => $subject->course_code,
                'subject' => $subject->course_title,
                'year_level' => $subject->year_level,
                'major' => $memorandum->major,
                'program' => $memorandum->name . ' (' . $memorandum->code . ')',
                'subject_type' => Subject::typeFromCourseCode($subject->course_code),
            ]);
        }

        $removedIds = $existing->keys()->filter(fn ($id) => !isset($keptIds[$id]));
        if ($removedIds->isNotEmpty()) {
            Subject::whereIn('id', $removedIds)->delete();
        }
    });

    $baseProgram = $this->programGroupQuery($program)->orderBy('id')->first();
    return redirect()->route('chair.managesubjects.memorandum', [$baseProgram->id, $memorandum->id])
        ->with('success', $year . ' subjects saved for both semesters.');
}

public function storeCatalogSubject(Request $request, $programId, $year)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $year = rawurldecode((string) $year);
    abort_unless(in_array($year, ['1st Year', '2nd Year', '3rd Year', '4th Year'], true), 404);

    $program = Program::query()
        ->where('department_id', $chair->department_id)
        ->findOrFail($programId);
    if ($program->year_level) {
        abort_unless($program->year_level === $year, 404);
    }

    $data = $request->validate([
        'semester' => ['required', 'in:1st Semester,2nd Semester'],
        'course_code' => ['required', 'string', 'max:50'],
        'course_title' => ['required', 'string', 'max:255'],
    ]);

    $courseCode = strtoupper(trim($data['course_code']));
    $duplicate = Subject::query()
        ->where('program_id', $program->id)
        ->where('year_level', $year)
        ->where('semester', $data['semester'])
        ->whereRaw('UPPER(course_code) = ?', [$courseCode])
        ->exists();

    if ($duplicate) {
        return back()->withInput()->with('error', 'That course code already exists in this program, year level, and semester.');
    }

    Subject::create([
        'program_id' => $program->id,
        'year_level' => $year,
        'semester' => $data['semester'],
        'course_code' => $courseCode,
        'course_title' => trim($data['course_title']),
    ]);

    return back()->with('success', 'Subject added to ' . $year . ', ' . $data['semester'] . '.');
}

public function updateCatalogSubject(Request $request, $programId, $year, $subjectId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $year = rawurldecode((string) $year);
    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);
    if ($program->year_level) {
        abort_unless($program->year_level === $year, 404);
    }
    $subject = Subject::query()->where('program_id', $program->id)->where('year_level', $year)->findOrFail($subjectId);
    $data = $request->validate([
        'semester' => ['required', 'in:1st Semester,2nd Semester'],
        'course_code' => ['required', 'string', 'max:50'],
        'course_title' => ['required', 'string', 'max:255'],
    ]);
    $courseCode = strtoupper(trim($data['course_code']));
    $duplicate = Subject::query()
        ->where('program_id', $program->id)
        ->where('year_level', $year)
        ->where('semester', $data['semester'])
        ->where('id', '!=', $subject->id)
        ->whereRaw('UPPER(course_code) = ?', [$courseCode])
        ->exists();

    if ($duplicate) {
        return back()->withInput()->with('error', 'That course code already exists in this program, year level, and semester.');
    }

    $subject->update([
        'semester' => $data['semester'],
        'course_code' => $courseCode,
        'course_title' => trim($data['course_title']),
    ]);

    // Keep the assignment snapshot synchronized with its catalog entry.
    FacultySubject::where('subject_id', $subject->id)->update([
        'course_code' => $subject->course_code,
        'subject' => $subject->course_title,
        'year_level' => $subject->year_level,
        'major' => $program->major,
        'program' => $program->name . ' (' . $program->code . ')',
        'subject_type' => Subject::typeFromCourseCode($subject->course_code),
    ]);

    $baseProgram = $this->programGroupQuery($program)->orderBy('id')->first();
    return redirect()
        ->route('chair.managesubjects.memorandum', [$baseProgram->id, $program->id])
        ->with('success', 'Subject details updated.');
}

public function deleteCatalogSubject($programId, $year, $subjectId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);
    $year = rawurldecode((string) $year);
    if ($program->year_level) {
        abort_unless($program->year_level === $year, 404);
    }
    $subject = Subject::query()
        ->where('program_id', $program->id)
        ->where('year_level', $year)
        ->findOrFail($subjectId);
    $subject->delete();

    return back()->with('success', 'Subject removed from the catalog. Existing faculty assignments keep their saved details.');
}

public function lookupCatalogSubjects(Request $request)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $data = $request->validate(['course_code' => ['required', 'string', 'max:50']]);
    $courseCode = strtoupper(trim($data['course_code']));

    $matches = Subject::query()
        ->with('program:id,name,code,major,department_id,memorandum')
        ->whereRaw('UPPER(course_code) = ?', [$courseCode])
        ->whereHas('program', fn ($query) => $query->where('department_id', $chair->department_id))
        ->orderBy('year_level')
        ->orderBy('semester')
        ->orderBy('program_id')
        ->get()
        ->map(fn (Subject $subject) => [
            'id' => $subject->id,
            'course_code' => $subject->course_code,
            'course_title' => $subject->course_title,
            'year_level' => $subject->year_level,
            'semester' => $subject->semester,
            'major' => $subject->program?->major,
            'program' => $subject->program?->name . ' (' . $subject->program?->code . ')',
            'memorandum' => $subject->program?->memorandum,
            'subject_type' => Subject::typeFromCourseCode($subject->course_code),
        ])
        ->values();

    return response()->json(['subjects' => $matches]);
}
}

