<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;
use App\Models\FacultySubject;
use App\Models\Building;
use App\Models\ScheduleDraft;
use App\Models\RoomSwapRequest;
use App\Models\ScheduleMerge;
use App\Models\AcademicTerm;
use App\Models\Program;
use App\Models\Subject;
use App\Models\ScheduleTimeSlot;
use App\Models\RoomScheduleTimeSlot;
use App\Models\RoomReassignment;

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

$mergeRequests = ScheduleMerge::with([
    'schedule.room',
    'schedule.department',
    'facultySubject',
    'department',
])
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
->get();
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
        'mergeRequests'
    )
);
}
public function drafts(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $activeTerm = $this->resolveAcademicTerm($request);
    $chair = Auth::user();

    $drafts = $this->termDraftQuery(
        ScheduleDraft::with(['room', 'creator'])
            ->where('created_by', $chair->id),
        $activeTerm
    )
    ->orderByDesc('created_at')
    ->get();

    return view('chair.drafts', compact('drafts', 'activeTerm'));
}
public function saveDraft( Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }


    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR
    |--------------------------------------------------------------------------
    */

    $chair = Auth::user();
    $activeTerm = $this->resolveAcademicTerm($request);


    /*
    |--------------------------------------------------------------------------
    | VALIDATE
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'room_id' =>
            'required|exists:rooms,id',

        'schedule' =>
            'required|array',

        'time_slots' =>
            'nullable|array',

            'room_time_slots' =>
    'nullable|array',

'room_time_slots.*.day' =>
    'required|string|max:20',

'room_time_slots.*.time' =>
    'required|string|max:50',

'room_time_slots.*.row_position' =>
    'required|integer',

        'time_slots.*.day' =>
            'required|string|max:20',

        'time_slots.*.time' =>
            'required|string|max:50',

        'time_slots.*.row_position' =>
            'required|integer',

        'schedule.*.day' =>
            'required|string|max:20',

        'schedule.*.time' =>
            'required|string|max:50',

            'schedule.*.row_position' =>
    'required|integer',

        'schedule.*.course_code' =>
            'required|string|max:50',

        'schedule.*.faculty_subject_id' =>
            'required|integer|exists:faculty_subjects,id',

        'schedule.*.subject' =>
            'required|string|max:255',

        'schedule.*.year_level' =>
            'required|string|max:50',

        'schedule.*.instructor' =>
            'required|string|max:255',

        'schedule.*.major' =>
    'nullable|string|max:50',

        'schedule.*.program' =>
            'required|string|max:100',

        'schedule.*.color' =>
            'nullable|string|max:20',
    ]);


    /*
    |--------------------------------------------------------------------------
    | GET ROOM
    |--------------------------------------------------------------------------
    */

    $room = Room::findOrFail(
        $request->room_id
    );

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

    RoomScheduleTimeSlot::where(
    'room_id',
    $room->id
)
    ->where(
        'academic_term_id',
        $activeTerm->id
    )
    ->delete();


foreach (
    $request->input('room_time_slots', [])
    as $roomTimeSlotData
) {

    $day = strtoupper(
        trim(
            $roomTimeSlotData['day'] ?? ''
        )
    );

    $time = trim(
        $roomTimeSlotData['time'] ?? ''
    );

    $rowPosition = isset(
        $roomTimeSlotData['row_position']
    )
        ? (int) $roomTimeSlotData['row_position']
        : 0;

    if (
        $day === ''
        ||
        $time === ''
    ) {
        continue;
    }

    $parts = preg_split(
        '/\s*-\s*/',
        $time,
        2
    );

    if (
        count($parts) !== 2
    ) {
        continue;
    }

    $startTime = $this->normalizeScheduleClock(
        trim($parts[0])
    );

    $endTime = $this->normalizeScheduleClock(
        trim($parts[1])
    );

    if (
        $startTime === null
        ||
        $endTime === null
        ||
        $endTime <= $startTime
    ) {
        continue;
    }

    RoomScheduleTimeSlot::create([
        'room_id' =>
            $room->id,

        'academic_term_id' =>
            $activeTerm->id,

        'day' =>
            $day,

        'start_time' =>
            $startTime,

        'end_time' =>
            $endTime,

        'row_position' =>
            $rowPosition,

        'is_active' =>
            true,
    ]);
}




    /*
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | THE CHAIR CAN ONLY SAVE COURSES FROM THEIR OWN DEPARTMENT
    |--------------------------------------------------------------------------
    */

    foreach (
        $request->schedule as $data
    ) {

        $courseCode =
            strtoupper(
                trim(
                    $data['course_code']
                )
            );


        /*
        |--------------------------------------------------------------------------
        | FIND COURSE ASSIGNMENT
        |--------------------------------------------------------------------------
        */

        $facultySubject =
            FacultySubject::with('faculty')
                ->where(
                    'course_code',
                    $courseCode
                )
                ->whereHas(
                    'faculty',
                    function ($query) use ($chair) {

                        $query
                            ->where(
                                'role',
                                'faculty'
                            )
                            ->where(
                                'department_id',
                                $chair->department_id
                            );

                    }
                )
                ->first();


        /*
        |--------------------------------------------------------------------------
        | INVALID COURSE
        |--------------------------------------------------------------------------
        */

        if (!$facultySubject) {

            return back()
                ->with(
                    'error',
                    'Course code "' .
                    $courseCode .
                    '" is not assigned to a faculty member in your department.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK THE ROOM/TIME/DAY
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


        /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING PUBLISHED SCHEDULE
        |
        | A draft must never overwrite another department's
        | published schedule.
        |--------------------------------------------------------------------------
        */

        $existingSchedule =
            Schedule::where(
                'room_id',
                $room->id
            )
            ->where(
                'day',
                $day
            )
            ->where(
                'time',
                $time
            )
            ->where(
                'academic_term_id',
                $activeTerm->id
            )
            ->first();


        if (
            $existingSchedule &&
            (int) $existingSchedule->department_id !==
            (int) $chair->department_id
        ) {

            return back()
                ->with(
                    'error',
                    'The selected time slot belongs to another department and cannot be overwritten.'
                )
                ->withInput();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FACULTY SCHEDULE CONFLICT CHECK FOR DRAFT
    |--------------------------------------------------------------------------
    |
    | Drafts are checked against published schedules as well as other
    | entries in the same draft request. This prevents a conflicting
    | faculty timetable from being prepared and later published.
    |
    */
    $pendingFacultySchedules = [];

    foreach ($request->input('schedule', []) as $data) {
        $courseCode = strtoupper(trim($data['course_code'] ?? ''));

        $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])
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

        $facultyConflict = $this->findFacultyScheduleConflict(
            (int) $facultySubject->faculty_id,
            $day,
            $time,
            $activeTerm->id
        );

        if ($facultyConflict) {
            return back()
                ->with(
                    'error',
                    'Schedule conflict: this faculty/instructor already has a schedule on '
                    . $this->formatScheduleConflictMessage($facultyConflict)
                    . '. A faculty member cannot have overlapping schedules in different rooms.'
                )
                ->withInput();
        }

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
                    'Schedule conflict: this faculty/instructor is assigned to more than one room during the overlapping time '
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
    | REMOVE OLD DRAFT FOR THIS ROOM
    |
    | This makes "Update Draft" replace the old version
    | instead of creating duplicates.
    |--------------------------------------------------------------------------
    */

    ScheduleDraft::where('room_id', $room->id)
        ->where('created_by', $chair->id)
        ->where('academic_term_id', $activeTerm->id)
        ->delete();


    /*
    |--------------------------------------------------------------------------
    | SAVE NEW DRAFT
    |--------------------------------------------------------------------------
    */

    foreach (
        $request->schedule as $data
    ) {

        $courseCode =
            strtoupper(
                trim(
                    $data['course_code']
                )
            );


        /*
        |--------------------------------------------------------------------------
        | LOOK UP COURSE AGAIN
        |
        | We do not trust the browser's faculty_id,
        | subject or year_level.
        |--------------------------------------------------------------------------
        */

        $facultySubject =
            FacultySubject::with('faculty')
                ->where(
                    'course_code',
                    $courseCode
                )
                ->whereHas(
                    'faculty',
                    function ($query) use ($chair) {

                        $query
                            ->where(
                                'role',
                                'faculty'
                            )
                            ->where(
                                'department_id',
                                $chair->department_id
                            );

                    }
                )
                ->first();


        if (!$facultySubject) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE DRAFT
        |--------------------------------------------------------------------------
        */

        ScheduleDraft::create([

            'room_id' =>
                $room->id,

            'faculty_id' =>
                $facultySubject->faculty_id,

            'department_id' =>
                $chair->department_id,

            'academic_term_id' =>
                $activeTerm->id,

            'semester' =>
                $activeTerm->semester,

            'academic_year' =>
                $activeTerm->academic_year,

            'created_by' =>
                $chair->id,

            'day' =>
                strtoupper(
                    trim(
                        $data['day']
                    )
                ),

            'time' =>
    trim(
        $data['time']
    ),

'row_position' =>
    isset($data['row_position'])
        ? (int) $data['row_position']
        : null,

'course_code' =>
    $facultySubject->course_code,

'faculty_subject_id' =>
    $facultySubject->id,


            'subject' =>
                $facultySubject->subject,

            'year_level' =>
                $facultySubject->year_level,

                'major' =>
    $facultySubject->major,

            'program' =>
    $facultySubject->program,

            'subject_type' => $facultySubject->subject_type
                ?: (preg_match('/\.1\s*$/', trim($courseCode)) === 1 ? 'Lab' : 'Lec'),

            'instructor' =>
                $facultySubject->faculty->name,

            'color' =>
                $data['color']
                ?? '#ffffff',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'chair.drafts'
        )
        ->with(
            'success',
            'Schedule draft saved successfully.'
        );
}

    // Open the schedule editor for a room
public function index(Request $request, $roomId)
{
    if (!$this->isChairUser()) {
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

    $chair = Auth::user();

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

    /*
    |--------------------------------------------------------------------------
    | FACULTY SUBJECTS
    |--------------------------------------------------------------------------
    */

    $facultySubjects =
        FacultySubject::with([
            'faculty.department',
            'subjectCatalog.program',
        ])
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
                    ->where(
                        'status',
                        'approved'
                    )
                    ->with([
                        'department',
                        'facultySubject'
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

    /*
    |--------------------------------------------------------------------------
    | ACTIVE TERM VALUES
    |--------------------------------------------------------------------------
    */

    $semester =
        $activeTerm->semester;

    $academicYear =
        $activeTerm->academic_year;

    $isEditingDraft = false;

    $timeSlots = ScheduleTimeSlot::where(
        'academic_term_id',
        $activeTerm->id
    )
        ->where('is_active', true)
        ->orderBy('row_position')
        ->orderBy('start_time')
        ->get();

    return view(
        'chair.excel',
        compact(
            'room',
            'facultySubjects',
            'schedules',
                'semester',
            'academicYear',
            'activeTerm',
            'isEditingDraft',
            'timeSlots'
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
                ->with(['facultySubjects.subjectCatalog.program'])
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
        ->with(['department', 'facultySubjects.subjectCatalog.program'])
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
        ->whereNotNull('department_id')
        ->where('department_id', '!=', $chair->department_id)
        ->firstOrFail();

    $alreadyImported = DB::table('department_faculty_imports')
        ->where('department_id', $chair->department_id)
        ->where('faculty_id', $faculty->id)
        ->exists();

    if ($alreadyImported) {
        return redirect()->route('chair.setFaculty')
            ->with('error', 'This faculty member has already been imported.');
    }

    DB::table('department_faculty_imports')->insert([
        'department_id' => $chair->department_id,
        'faculty_id' => $faculty->id,
        'imported_by' => $chair->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('chair.setFaculty')
        ->with('success', $faculty->name . ' was added to your imported faculty list.');
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
            ->with('error', 'The imported faculty relationship was not found.');
    }

    return redirect()->route('chair.setFaculty')
        ->with(
            'success',
            'Imported faculty removed. The faculty account and home department were not changed.'
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


    /*
    |--------------------------------------------------------------------------
    | GET SELECTED / ACTIVE TERM
    |--------------------------------------------------------------------------
    |
    | When term_id is supplied by the dropdown,
    | getActiveAcademicTerm() will:
    |
    | 1. Find that term
    | 2. Make it active
    | 3. Deactivate the previous term
    | 4. Save it in the session
    |
    */

    $termId = $request->query('term_id');


    $activeTerm = $this->getActiveAcademicTerm(
        $termId !== null && $termId !== ''
            ? (int) $termId
            : null
    );


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
            'Department not found.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUILDINGS
    |--------------------------------------------------------------------------
    */

    $buildings =
        $department->buildings;


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
            'timeSlots'
        )
    );
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
            'message' => 'You can only manage rooms in your department.'
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
                ? 'Other departments can now add schedules to empty slots.'
                : 'Other departments can no longer add schedules to empty slots.'
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

        'schedule' =>
            'required|array',

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
    ]);

    /*
    |--------------------------------------------------------------------------
    | GET ROOM
    |--------------------------------------------------------------------------
    */

    $room =
        Room::findOrFail(
            $request->room_id
        );

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

        $startTime = $this->normalizeScheduleClock(trim($parts[0]));
        $endTime = $this->normalizeScheduleClock(trim($parts[1]));

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
                $hour <= 5
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
                    'Faculty';

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
        $ignoreScheduleId = !empty($data['schedule_id'])
            ? (int) $data['schedule_id']
            : null;

        $facultyConflict = $this->findFacultyScheduleConflict(
            (int) $facultySubject->faculty_id,
            $day,
            $time,
            $academicTermId,
            $ignoreScheduleId
        );

        if ($facultyConflict) {
            return back()
                ->with(
                    'error',
                    'Schedule conflict: this faculty/instructor already has a schedule on '
                    . $this->formatScheduleConflictMessage($facultyConflict)
                    . '. A faculty member cannot have overlapping schedules in different rooms.'
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
                    'Schedule conflict: this faculty/instructor is assigned to more than one room during the overlapping time '
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
            $submittedSlots
        ) {

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
                $existingSchedule->delete();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | PROCESS SCHEDULES
    |--------------------------------------------------------------------------
    */

    foreach (
        $request->schedule as $data
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
                    . '" is not assigned to any faculty in your department.'
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

        // Keep the official searchable time-slot table synchronized with
        // every time row used by the Chair schedule for this academic term.
        $this->syncScheduleTimeSlot(
            $activeTerm,
            $day,
            $time,
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
                    'This schedule belongs to another department and cannot be changed.'
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

            $existing->update(
                $scheduleData
            );

            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        Schedule::create(
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
    }

    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'chair.setschedule',
            [
                'term_id' =>
                    $activeTerm->id,
            ]
        )
        ->with(
            'success',
            'Schedule uploaded successfully.'
        );
}
public function viewSchedule(Room $room)
{
    $this->expireReservations();

    $activeTerm = $this->resolveAcademicTerm(request());

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
        'facultySubject',
    ])
        ->whereIn('schedule_id', $schedules->pluck('id'))
        ->where('status', 'approved')
        ->get();

    $mergedPrograms = [];

    foreach ($merges as $merge) {
        if (!$merge->facultySubject) {
            continue;
        }

        $program = trim((string) $merge->facultySubject->program);
        if ($program === '') {
            continue;
        }

        $mergedPrograms[$merge->schedule_id] ??= [];

        if (!in_array($program, $mergedPrograms[$merge->schedule_id], true)) {
            $mergedPrograms[$merge->schedule_id][] = $program;
        }
    }

    $activeRoomSwaps = RoomSwapRequest::with([
        'requesterRoom',
        'targetRoom',
    ])
        ->where('status', 'approved')
        ->where(function ($query) use ($schedules) {
            $query->whereIn('requester_schedule_id', $schedules->pluck('id'))
                ->orWhereIn('target_schedule_id', $schedules->pluck('id'));
        })
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
    ])
        ->where('room_id', $room->id)
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

    $scheduleData = $schedules->map(function ($schedule) use ($mergedPrograms, $roomSwapsBySchedule) {
        return [
            'id' => $schedule->id,
            'course_code' => $schedule->course_code,
            'subject' => $schedule->subject,
            'subject_type' => $schedule->subject_type,
            'program' => $schedule->program,
            'major' => $schedule->major,
            'day' => strtoupper(trim((string) $schedule->day)),
            'time' => trim((string) $schedule->time),
            'row_position' => $schedule->row_position !== null
                ? (int) $schedule->row_position
                : null,
            'instructor' => $schedule->instructor,
            'color' => $schedule->color ?? '#ffffff',
            'merged_programs' => $mergedPrograms[$schedule->id] ?? [],
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
            'user_name' => $reservation->user?->name ?? 'Faculty',
            'course_code' => $reservation->facultySubject?->course_code,
            'subject' => $reservation->facultySubject?->subject,
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
                'department' => $room->department?->name ?? 'Department',
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
                'name' => $department?->name ?? 'Department',
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

    Schedule::where('room_id', $room->id)
        ->where('academic_term_id', $activeTerm->id)
        ->delete();

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
            'The selected subject is not assigned to your faculty account.'
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

    Reservation::create([
        'room_id' => $room->id,
        'user_id' => $faculty->id,
        'schedule_id' => $scheduleId,
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
    ]);

   $faculty = User::where('id', $request->faculty_id)
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

    $subjectEntry = Subject::with('program')
        ->whereHas('program', fn ($query) => $query->where('department_id', $chair->department_id))
        ->findOrFail($request->subject_id);

    $courseCode = strtoupper(trim($subjectEntry->course_code));
    if ($courseCode !== strtoupper(trim($request->course_code))) {
        return back()->withInput()->with('error', 'The course code changed. Enter it again and select the matching subject.');
    }

    $exists = FacultySubject::where('faculty_id', $faculty->id)
        ->where('subject_id', $subjectEntry->id)
        ->exists();

    if ($exists) {
        return redirect()
            ->route('chair.setFaculty', ['faculty' => $faculty->id])
            ->with('error', 'This course code is already assigned to this faculty member.');
    }

    FacultySubject::create([
        'faculty_id' => $faculty->id,
        'subject_id' => $subjectEntry->id,
        'course_code' => $courseCode,
        'subject' => $subjectEntry->course_title,
        'year_level' => $subjectEntry->year_level,
        'major' => $subjectEntry->program?->major,
        'program' => trim(($subjectEntry->program?->name ?? '') . ' (' . ($subjectEntry->program?->code ?? '') . ')'),
        'subject_type' => Subject::typeFromCourseCode($subjectEntry->course_code),
    ]);

    return redirect()
        ->route('chair.setFaculty', ['faculty' => $faculty->id])
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

    $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])->findOrFail($id);
    if (!$this->chairCanManageFacultySubject($facultySubject, $chair)) {
        abort(403, 'You cannot edit this original department subject.');
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
            ->with('error', 'This course code is already assigned to this faculty member.');
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

    $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])->findOrFail($id);
    if (!$this->chairCanManageFacultySubject($facultySubject, $chair)) {
        abort(403, 'You cannot remove this original department subject.');
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

public function editDraft($roomId)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR
    |--------------------------------------------------------------------------
    */

    $chair = Auth::user();
    $activeTerm = $this->resolveAcademicTerm(request());


    /*
    |--------------------------------------------------------------------------
    | GET ROOM
    |--------------------------------------------------------------------------
    */

    $room = Room::with([
        'department',
        'building'
    ])->findOrFail($roomId);


    /*
    |--------------------------------------------------------------------------
    | GET CURRENT CHAIR'S DRAFT FOR THIS ROOM
    |--------------------------------------------------------------------------
    */

    $drafts = $this->termDraftQuery(
        ScheduleDraft::where('room_id', $room->id)
            ->where('created_by', $chair->id),
        $activeTerm
    )->orderBy('id')->get();


    /*
    |--------------------------------------------------------------------------
    | NO DRAFT FOUND
    |--------------------------------------------------------------------------
    */

    if ($drafts->isEmpty()) {

        return redirect()
            ->route('chair.drafts')
            ->with(
                'error',
                'No draft was found for this room.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GET SEMESTER / ACADEMIC YEAR FROM DRAFT
    |--------------------------------------------------------------------------
    |
    | All entries in the same draft should normally have the
    | same semester and academic year.
    |
    */

    $semester =
        $drafts->first()->semester ?? '';

    $academicYear =
        $drafts->first()->academic_year ?? '';


    /*
    |--------------------------------------------------------------------------
    | GET FACULTY SUBJECTS
    |--------------------------------------------------------------------------
    |
    | Only subjects assigned to faculty belonging to the
    | logged-in Chair's department.
    |
    */

    $facultySubjects =
        FacultySubject::with([
            'faculty.department',
            'subjectCatalog.program',
        ])
        ->orderBy(
            'course_code'
        )
        ->get()
        ->filter(fn ($subject) => $this->chairCanManageFacultySubject($subject, $chair))
        ->values();


    /*
    |--------------------------------------------------------------------------
    | DRAFT EDIT MODE
    |--------------------------------------------------------------------------
    */

    $isEditingDraft = true;


    /*
    |--------------------------------------------------------------------------
    | OPEN EXCEL EDITOR
    |--------------------------------------------------------------------------
    */

    return view(
        'chair.excel',
        compact(
            'room',
            'facultySubjects',
            'drafts',
            'semester',
            'academicYear',
            'isEditingDraft',
            'activeTerm'
        )
    );
}

public function publishDraft(Request $request, $roomId)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $activeTerm = $this->resolveAcademicTerm($request);

    /*
    |--------------------------------------------------------------------------
    | GET ROOM
    |--------------------------------------------------------------------------
    */

    $room = Room::findOrFail($roomId);


    /*
    |--------------------------------------------------------------------------
    | GET CURRENT CHAIR'S DRAFT
    |--------------------------------------------------------------------------
    */

    $drafts = $this->termDraftQuery(
        ScheduleDraft::where('room_id', $room->id)
            ->where('created_by', $chair->id),
        $activeTerm
    )->orderBy('id')->get();

    if ($drafts->isEmpty()) {
        return redirect()
            ->route('chair.drafts')
            ->with(
                'error',
                'No draft was found for this room.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK OVERRIDE
    |--------------------------------------------------------------------------
    */

    $override =
        $request->boolean('override');


    /*
    |--------------------------------------------------------------------------
    | CHECK WHETHER ROOM ALREADY HAS A PUBLISHED SCHEDULE
    |--------------------------------------------------------------------------
    */

    $existingSchedules =
        Schedule::where('room_id', $room->id)
            ->where('academic_term_id', $activeTerm->id)
            ->get();


    /*
    |--------------------------------------------------------------------------
    | ROOM ALREADY HAS A SCHEDULE
    |--------------------------------------------------------------------------
    |
    | Do not replace anything unless the user explicitly
    | confirmed Override & Publish.
    |
    */

    if (
        $existingSchedules->isNotEmpty()
        && !$override
    ) {

        return redirect()
            ->route(
                'chair.drafts.edit',
                $room->id
            )
            ->with(
                'schedule_conflict',
                true
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FACULTY CONFLICT CHECK BEFORE PUBLISH
    |--------------------------------------------------------------------------
    |
    | Publishing a draft must obey the same faculty conflict rule as
    | direct scheduling. Existing schedules in this same room are ignored
    | because they will be replaced during publish. Schedules in other
    | rooms remain blocking conflicts.
    |
    */
    $ignoredScheduleIds = $existingSchedules->pluck('id')->all();
    $pendingFacultySchedules = [];

    foreach ($drafts as $draft) {
        $facultyConflict = $this->findFacultyScheduleConflict(
            (int) $draft->faculty_id,
            strtoupper(trim((string) $draft->day)),
            trim((string) $draft->time),
            $activeTerm->id,
            $ignoredScheduleIds
        );

        if ($facultyConflict) {
            return redirect()
                ->route('chair.drafts.edit', $room->id)
                ->with(
                    'error',
                    'Cannot publish this draft. The faculty/instructor already has a schedule on '
                    . $this->formatScheduleConflictMessage($facultyConflict)
                    . '. A faculty member cannot have overlapping schedules in different rooms.'
                );
        }

        $pendingConflict = $this->findPendingFacultyScheduleConflict(
            (int) $draft->faculty_id,
            strtoupper(trim((string) $draft->day)),
            trim((string) $draft->time),
            $pendingFacultySchedules
        );

        if ($pendingConflict) {
            return redirect()
                ->route('chair.drafts.edit', $room->id)
                ->with(
                    'error',
                    'Cannot publish this draft. The same faculty/instructor has overlapping schedules in multiple rooms.'
                );
        }

        $pendingFacultySchedules[] = [
            'faculty_id' => (int) $draft->faculty_id,
            'day' => strtoupper(trim((string) $draft->day)),
            'time' => trim((string) $draft->time),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLISH
    |--------------------------------------------------------------------------
    */

    DB::transaction(
        function () use (
            $room,
            $chair,
            $drafts,
            $activeTerm
        ) {

            /*
            |--------------------------------------------------------------------------
            | REMOVE EXISTING PUBLISHED SCHEDULE
            |--------------------------------------------------------------------------
            */

            Schedule::where('room_id', $room->id)
                ->where('academic_term_id', $activeTerm->id)
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | CREATE PUBLISHED SCHEDULES FROM DRAFT
            |--------------------------------------------------------------------------
            */

            foreach ($drafts as $draft) {

                Schedule::create([

                    'room_id' =>
                        $room->id,

                    'faculty_id' =>
                        $draft->faculty_id,

                    'faculty_subject_id' =>
                        $draft->faculty_subject_id,

                    'department_id' =>
                        $draft->department_id
                        ?? $chair->department_id,

                    'academic_term_id' =>
                        $activeTerm->id,

                    'day' =>
                        strtoupper(
                            trim($draft->day)
                        ),

                    'time' =>
                        trim($draft->time),

                    'row_position' =>
                        $draft->row_position,

                    'semester' =>
                        $draft->semester,

                    'academic_year' =>
                        $draft->academic_year,

                    'course_code' =>
                        $draft->course_code,


                    'subject' =>
                        $draft->subject,

                    'year_level' =>
                        $draft->year_level,

                    'major' =>
                        $draft->major,

                    'program' =>
                        $draft->program,

                    'subject_type' =>
                        $draft->subject_type,

                    'instructor' =>
                        $draft->instructor,

                    'color' =>
                        $draft->color
                        ?? '#ffffff',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | DELETE DRAFT
            |--------------------------------------------------------------------------
            */

            ScheduleDraft::where(
                'room_id',
                $room->id
            )
            ->where('created_by', $chair->id)
            ->where('academic_term_id', $activeTerm->id)
            ->delete();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('chair.drafts')
        ->with(
            'success',
            'Draft published successfully. The previous schedule in Room '
            . $room->room_name
            . ' was replaced.'
        );
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
        ->orderBy(
            'name'
        )
        ->get();

    /*
    |--------------------------------------------------------------------------
    | FACULTY SUBJECTS
    |--------------------------------------------------------------------------
    */

    $facultySubjects =
        FacultySubject::with([
            'faculty.department',
            'subjectCatalog.program',
        ])
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
            'activeTerm'
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
            'This schedule belongs to another department and cannot be cleared.'
        );
    }

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
                ->with('error', 'This schedule belongs to another department and cannot be modified.')
                ->withInput();
        }
    }

    /* Target slot must be empty in the active term. */
    $existingSchedule = Schedule::where('room_id', $room->id)
        ->where('day', $day)
        ->where('time', $time)
        ->where('academic_term_id', $activeTerm->id)
        ->when($currentSchedule, function ($query) use ($currentSchedule) {
            $query->where('id', '!=', $currentSchedule->id);
        })
        ->first();

    if ($existingSchedule) {
        if ((int) $existingSchedule->department_id !== (int) $chair->department_id) {
            return back()
                ->with('error', 'This schedule belongs to another department and cannot be modified.')
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
                ->with('error', 'This department does not allow other departments to add schedules to this room.')
                ->withInput();
        }
    }

    /* Course must belong to the logged-in Chair's department. */
    $courseCode = strtoupper(trim($request->course_code));

    $facultySubject = FacultySubject::with(['faculty', 'subjectCatalog.program'])
        ->find($request->faculty_subject_id);

    if ($facultySubject && (
        strtoupper(trim($facultySubject->course_code)) !== $courseCode
        || !$this->chairCanManageFacultySubject($facultySubject, $chair)
    )) {
        $facultySubject = null;
    }

    if (!$facultySubject) {
        return back()
            ->with('error', 'This course code is not assigned to a faculty member in your department.')
            ->withInput();
    }

    if (!$facultySubject->faculty) {
        return back()
            ->with('error', 'The faculty member assigned to this course could not be found.')
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | FACULTY SCHEDULE CONFLICT CHECK
    |--------------------------------------------------------------------------
    */
    $facultyConflict = $this->findFacultyScheduleConflict(
        (int) $facultySubject->faculty_id,
        $day,
        $time,
        $activeTerm->id,
        $currentSchedule?->id
    );

    if ($facultyConflict) {
        return back()
            ->with(
                'error',
                'Schedule conflict: this faculty/instructor already has a schedule on '
                . $this->formatScheduleConflictMessage($facultyConflict)
                . '. A faculty member cannot have overlapping schedules in different rooms.'
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

    if ($currentSchedule) {
        $currentSchedule->update($scheduleData);

        return redirect()
            ->route('chair.modifyschedule', ['term_id' => $activeTerm->id])
            ->with('success', 'Your schedule was updated successfully.');
    }

    Schedule::create($scheduleData);

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

        ->where(
            'schedules.course_code',
            'LIKE',
            '%' . $courseCode . '%'
        )

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

        ->orderBy(
            'schedules.course_code'
        )

        ->orderBy(
            'schedules.day'
        )

        ->orderBy(
            'schedules.time'
        )

        ->get();


    /*
    |--------------------------------------------------------------------------
    | RETURN RESULTS
    |--------------------------------------------------------------------------
    */

    return response()->json([
        'success' => true,

        'classes' => $classes
            ->map(function ($schedule) {

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
                        ?: 'Unknown Department',


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
            'You cannot browse your own department through merge.'
        );
    }

    /*
     |--------------------------------------------------------------------------
     | LOAD ROOM SCHEDULE
     |--------------------------------------------------------------------------
     */

$schedules = Schedule::with([
    'merges' => function ($query) {
        $query
            ->where('status', 'approved')
            ->with([
                'department',
                'facultySubject'
            ]);
    }
])
    ->where(
        'room_id',
        $room->id
    )
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

    if (
        $schedule->program &&
        trim($schedule->program) !== ''
    ) {
        $programs->push(
            trim($schedule->program)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET APPROVED MERGED PROGRAMS
    |--------------------------------------------------------------------------
    */

    foreach ($schedule->merges as $merge) {

        if (
            $merge->status === 'approved' &&
            $merge->facultySubject &&
            $merge->facultySubject->program
        ) {

            $programs->push(
                trim(
                    $merge->facultySubject->program
                )
            );
        }
    }


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
                    : 'Unknown Department',
        ],

        'schedules' => $schedules->map(
            function ($schedule) {

                return [

                    'id' =>
                        $schedule->id,

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

                    'year_level' =>
                        $schedule->year_level,

                    'major' =>
                        $schedule->major,

                    'program' => $schedule->program ?: 'Unknown Program',
                    
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
                'Your account is not assigned to a department.',
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
                'This schedule does not have an assigned department.',
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
                'The selected program does not belong to your department.',
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
                ? 'Your department is already merged with this class.'
                : 'Your department already has a pending merge request for this class.',
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
/*
|--------------------------------------------------------------------------
| APPROVE MERGE REQUEST
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

    /**
     * Only return drafts explicitly assigned to the selected academic term.
     */
    private function termDraftQuery($query, AcademicTerm $term)
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

    $activeTerm = $this->resolveAcademicTerm($request);

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

        return redirect()
            ->route('chair.schedule.library')
            ->with('success', 'Active academic term changed to ' . $term->academic_year . ' • ' . $term->semester . '.');
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

        // Terms are shared across departments, so protect any schedule or draft using this term.
        if ($term->schedules()->exists() || $term->drafts()->exists()) {
            return redirect()
                ->route('chair.schedule.library')
                ->with('error', 'This academic term cannot be deleted because schedules or drafts are linked to it.');
        }

        $term->delete();

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
            }

            return ($hour * 60) + $minute;
        };

        $start = $parsePart($parts[0]);
        $end = $parsePart($parts[1]);

        if ($start === null || $end === null || $end <= $start) {
            return null;
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
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

        $start = $this->normalizeScheduleClock($parts[0]);
        $end = $this->normalizeScheduleClock($parts[1]);

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

            // Preserve the existing Chair timetable convention.
            if ($hour >= 1 && $hour <= 5) {
                $hour += 12;
            }

            return sprintf('%02d:%02d:00', $hour, $minute);
        } catch (\Throwable $e) {
            return null;
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
public function manageSubjects(Request $request, $programId = null, $year = null)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();
    $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    $semesterOptions = ['1st Semester', '2nd Semester'];
    $programs = Program::query()
        ->where('department_id', $chair->department_id)
        ->withCount('subjects')
        ->orderByDesc('memorandum')
        ->orderBy('name')
        ->get();

    $selectedProgram = null;
    $selectedYear = null;
    $selectedSemester = null;
    $subjects = collect();

    if ($programId !== null) {
        $selectedProgram = Program::query()
            ->where('department_id', $chair->department_id)
            ->with('subjects')
            ->findOrFail($programId);

        if ($year !== null) {
            $selectedYear = rawurldecode((string) $year);
            abort_unless(in_array($selectedYear, $yearLevels, true), 404);
            $requestedSemester = $request->query('semester');
            if ($requestedSemester !== null) {
                $selectedSemester = rawurldecode((string) $requestedSemester);
                abort_unless(in_array($selectedSemester, $semesterOptions, true), 404);
                $subjects = $selectedProgram->subjects
                    ->where('year_level', $selectedYear)
                    ->where('semester', $selectedSemester)
                    ->sortBy('course_code')
                    ->values();
            }
        }
    }

    return view('chair.managesubjects', compact(
        'programs',
        'selectedProgram',
        'selectedYear',
        'selectedSemester',
        'yearLevels',
        'semesterOptions',
        'subjects'
    ));
}

public function storeProgram(Request $request)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);

    $data = $request->validate([
        'name' => ['required', 'string', 'max:200'],
        'code' => ['required', 'string', 'max:40'],
        'memorandum' => ['required', 'string', 'max:500'],
        'major' => ['nullable', 'string', 'max:120'],
    ]);

    $code = strtoupper(trim($data['code']));
    $memorandum = trim($data['memorandum']);
    $major = trim((string) ($data['major'] ?? '')) ?: null;
    $duplicate = Program::query()
        ->where('department_id', $chair->department_id)
        ->whereRaw('UPPER(code) = ?', [$code])
        ->where('memorandum', $memorandum)
        ->where('name', trim($data['name']))
        ->where(function ($query) use ($major) {
            $major === null ? $query->whereNull('major') : $query->where('major', $major);
        })
        ->exists();

    if ($duplicate) {
        return back()->withInput()->with('error', 'This program and memorandum already exist.');
    }

    Program::create([
        'department_id' => $chair->department_id,
        'name' => trim($data['name']),
        'code' => $code,
        'memorandum' => $memorandum,
        'major' => $major,
    ]);

    return redirect()->route('chair.managesubjects')->with('success', 'Program created. Open it to choose a year level and add subjects.');
}

public function updateProgram(Request $request, $programId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);

    $program = Program::query()
        ->where('department_id', $chair->department_id)
        ->findOrFail($programId);

    $data = $request->validate([
        'name' => ['required', 'string', 'max:200'],
        'code' => ['required', 'string', 'max:40'],
        'memorandum' => ['required', 'string', 'max:500'],
        'major' => ['nullable', 'string', 'max:120'],
    ]);

    $code = strtoupper(trim($data['code']));
    $memorandum = trim($data['memorandum']);
    $major = trim((string) ($data['major'] ?? '')) ?: null;

    $duplicate = Program::query()
        ->where('department_id', $chair->department_id)
        ->where('id', '!=', $program->id)
        ->whereRaw('UPPER(code) = ?', [$code])
        ->where('memorandum', $memorandum)
        ->where('name', trim($data['name']))
        ->where(function ($query) use ($major) {
            $major === null ? $query->whereNull('major') : $query->where('major', $major);
        })
        ->exists();

    if ($duplicate) {
        return back()->withInput()->with('error', 'This program and memorandum already exist.');
    }

    $program->update([
        'name' => trim($data['name']),
        'code' => $code,
        'memorandum' => $memorandum,
        'major' => $major,
    ]);

    $subjectIds = $program->subjects()->pluck('id');
    if ($subjectIds->isNotEmpty()) {
        FacultySubject::whereIn('subject_id', $subjectIds)->update([
            'program' => $program->name . ' (' . $program->code . ')',
            'major' => $program->major,
        ]);
    }

    return back()->with('success', 'Program information updated. Its major is now used for all subjects in this program.');
}

public function deleteProgram($programId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);

    Program::query()
        ->where('department_id', $chair->department_id)
        ->findOrFail($programId)
        ->delete();

    return redirect()->route('chair.managesubjects')->with('success', 'Program deleted. Existing faculty assignments keep their saved course details.');
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

    return redirect()
        ->route('chair.managesubjects', [
            'program' => $program->id,
            'year' => $year,
            'semester' => $subject->semester,
        ])
        ->with('success', 'Subject details updated.');
}

public function deleteCatalogSubject($programId, $year, $subjectId)
{
    $chair = Auth::user();
    abort_unless($chair && $chair->role === 'chair', 403);
    $program = Program::query()->where('department_id', $chair->department_id)->findOrFail($programId);
    $subject = Subject::query()
        ->where('program_id', $program->id)
        ->where('year_level', rawurldecode((string) $year))
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

