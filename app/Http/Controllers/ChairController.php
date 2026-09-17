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

class ChairController extends Controller
{
  public function dashboard()
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

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
    | FACULTY ROOM RESERVATION REQUESTS
    |--------------------------------------------------------------------------
    |
    | KEEP YOUR EXISTING RESERVATION LOGIC HERE.
    |
    */

    $reservations = Reservation::with([
        'room',
        'user'
    ])
    ->where('status', 'Pending')
    ->latest()
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
    'user',
    'requesterRoom',
    'targetRoom'
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
        'reservations',
        'swapRequests',
        'mergeRequests'
    )
);
}
public function drafts()
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $drafts = ScheduleDraft::with([
        'room',
        'creator'
    ])
    ->where(
        'created_by',
        Auth::id()
    )
    ->orderByDesc('created_at')
    ->get();


    return view(
        'chair.drafts',
        compact('drafts')
    );
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

        'schedule.*.day' =>
            'required|string|max:20',

        'schedule.*.time' =>
            'required|string|max:50',

            'schedule.*.row_position' =>
    'required|integer',

        'schedule.*.course_code' =>
            'required|string|max:50',

        'schedule.*.subject' =>
            'required|string|max:255',

        'schedule.*.year_level' =>
            'required|string|max:50',

        'schedule.*.instructor' =>
            'required|string|max:255',

        'schedule.*.major' =>
    'nullable|string|max:50',

        'schedule.*.program' =>
            'required|string|max:50',

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
    | REMOVE OLD DRAFT FOR THIS ROOM
    |
    | This makes "Update Draft" replace the old version
    | instead of creating duplicates.
    |--------------------------------------------------------------------------
    */

    ScheduleDraft::where(
        'room_id',
        $room->id
    )
    ->where(
        'created_by',
        $chair->id
    )
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

            'subject' =>
                $facultySubject->subject,

            'year_level' =>
                $facultySubject->year_level,

                'major' =>
    $facultySubject->major,

            'program' =>
    $facultySubject->program,

            'subject_type' =>
            str_contains($courseCode, '.') ? 'Lab' : 'Lec',

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
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }


    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR
    |--------------------------------------------------------------------------
    */

    $chair = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | GET ROOM
    |--------------------------------------------------------------------------
    */

    $room = Room::with([
        'department',
        'building',
    ])->findOrFail($roomId);


    /*
    |--------------------------------------------------------------------------
    | GET FACULTY SUBJECTS
    |--------------------------------------------------------------------------
    |
    | Only faculty belonging to this room's department.
    |
    */

    $facultySubjects =
        FacultySubject::with([
            'faculty.department'
        ])
        ->whereHas(
            'faculty',
            function ($query) use ($room) {

                $query
                    ->where(
                        'role',
                        'faculty'
                    )
                    ->where(
                        'department_id',
                        $room->department_id
                    );

            }
        )
        ->orderBy(
            'course_code'
        )
        ->get();


    /*
    |--------------------------------------------------------------------------
    | GET PUBLISHED SCHEDULES
    |--------------------------------------------------------------------------
    */
$schedules =
    Schedule::with([
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
    ->orderBy(
        'day'
    )
    ->orderBy(
        'time'
    )

    ->get();

    /*
    |--------------------------------------------------------------------------
    | GET SEMESTER
    |--------------------------------------------------------------------------
    |
    | Get it from the first existing schedule.
    |
    */

    $semester =
        $schedules->first()->semester
        ?? '';


    /*
    |--------------------------------------------------------------------------
    | GET ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

    $academicYear =
        $schedules->first()->academic_year
        ?? '';


    /*
    |--------------------------------------------------------------------------
    | NORMAL EXCEL MODE
    |--------------------------------------------------------------------------
    */

    $isEditingDraft =
        false;


    /*
    |--------------------------------------------------------------------------
    | RETURN EXCEL VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'chair.excel',
        compact(
            'room',
            'facultySubjects',
            'schedules',
            'semester',
            'academicYear',
            'isEditingDraft'
        )
    );
}
public function setFaculty(Request $request)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    /*
    |--------------------------------------------------------------------------
    | CURRENT LOGGED-IN CHAIR
    |--------------------------------------------------------------------------
    */

    $chair = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | GET CHAIR'S DEPARTMENT
    |--------------------------------------------------------------------------
    */

    $department = Department::with([
        'users' => function ($query) {
            $query
                ->where('role', 'faculty')
                ->with('facultySubjects')
                ->orderBy('name');
        }
    ])
    ->findOrFail(
        $chair->department_id
    );


    /*
    |--------------------------------------------------------------------------
    | SELECTED FACULTY
    |
    | The only thing that can change from the page
    | is which faculty member is selected.
    |--------------------------------------------------------------------------
    */

    $selectedFaculty = null;


    if ($request->filled('faculty')) {

        $selectedFaculty =
            $department->users
                ->where(
                    'role',
                    'faculty'
                )
                ->firstWhere(
                    'id',
                    $request->query('faculty')
                );

    }


    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'chair.setFaculty',
        compact(
            'department',
            'selectedFaculty'
        )
    );
}
    // Display all rooms in the Chair page
 public function setschedule()
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | ONLY LOAD THE CHAIR'S DEPARTMENT
    |--------------------------------------------------------------------------
    */

    $department = Department::with([
        'buildings.rooms'
    ])->find($chair->department_id);

    if (!$department) {
        abort(404, 'Department not found.');
    }

    /*
    |--------------------------------------------------------------------------
    | GET BUILDINGS AND ROOMS
    |--------------------------------------------------------------------------
    */

    $buildings = $department->buildings;

    /*
    |--------------------------------------------------------------------------
    | RETURN SET SCHEDULE
    |--------------------------------------------------------------------------
    */

    return view(
        'chair.setSchedule',
        compact(
            'department',
            'buildings'
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
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

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

        'schedule.*.day' =>
            'required|string|max:20',

        'schedule.*.time' =>
            'required|string|max:50',

        'schedule.*.row_position' => 'required|integer',
        
        'schedule.*.schedule_id' =>
            'nullable|integer|exists:schedules,id',

        'schedule.*.course_code' =>
            'required|string|max:50',
            
        'schedule.*.major' =>
    'nullable|string|max:50',

         'schedule.*.program' =>
            'required|string|max:50',


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

    $room = Room::findOrFail(
        $request->room_id
    );
  $semester =
        $request->input('semester');

    $academicYear =
        $request->input('academic_year');


        $submittedSlots = collect(
    $request->input('schedule', [])
)->map(function ($item) {
    return strtoupper(trim($item['day'] ?? ''))
        . '|'
        . trim($item['time'] ?? '');
})->filter()->values();

Schedule::where('room_id', $room->id)
    ->where('department_id', $chair->department_id)
    ->when(
        $semester !== null,
        function ($query) use ($semester) {
            $query->where('semester', $semester);
        }
    )
    ->when(
        $academicYear !== null,
        function ($query) use ($academicYear) {
            $query->where('academic_year', $academicYear);
        }
    )
    ->get()
    ->each(function ($existingSchedule) use ($submittedSlots) {

        $slotKey =
            strtoupper(trim($existingSchedule->day))
            . '|'
            . trim($existingSchedule->time);

        if (!$submittedSlots->contains($slotKey)) {
            $existingSchedule->delete();
        }
    });
    /*
    |--------------------------------------------------------------------------
    | PROCESS EACH SCHEDULE
    |--------------------------------------------------------------------------
    */

    foreach ($request->schedule as $data) {

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
        | COURSE NOT FOUND
        |--------------------------------------------------------------------------
        */

        if (!$facultySubject) {

            return back()
                ->with(
                    'error',
                    'Course code "' .
                    $courseCode .
                    '" is not assigned to any faculty in your department.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | DAY / TIME
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

        $scheduleId =
             $data['schedule_id'] ?? null;


       /*
|--------------------------------------------------------------------------
| FIND EXISTING SCHEDULE
|--------------------------------------------------------------------------
|
| If schedule_id exists, update that exact schedule.
| Otherwise, check whether the day/time already exists.
|
*/

$existing = null;

if ($scheduleId) {
    $existing = Schedule::where('id', $scheduleId)
        ->where('room_id', $room->id)
        ->first();
}

if (!$existing) {
    $existing = Schedule::where('room_id', $room->id)
        ->where('day', $day)
        ->where('row_position', (int) $data['row_position'])
        ->first();
}
        /*
        |--------------------------------------------------------------------------
        | OTHER DEPARTMENT SCHEDULE = LOCKED
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

            'day' =>
                $day,

            'time' =>
                $time,
                
            'row_position' => (int) $data['row_position'],

            'faculty_id' =>
                $facultySubject->faculty_id,

            'department_id' =>
                $chair->department_id,

            'semester' =>
                $semester,

            'academic_year' =>
                $academicYear,

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
            str_contains($courseCode, '.') ? 'Lab' : 'Lec',

            'instructor' =>
                $facultySubject->faculty->name,


            'color' =>
                $data['color'] ?? '#ffffff',
        ];


        /*
        |--------------------------------------------------------------------------
        | UPDATE OWN SCHEDULE
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
        | CREATE NEW SCHEDULE
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
                'department' =>
                    $room->department_id
            ]
        )
        ->with(
            'success',
            'Schedule uploaded successfully.'
        );
}
    public function viewSchedule(Room $room)
    {
        $schedules = Schedule::where('room_id', $room->id)->get();

        return view('faculty.view-schedule', compact('room', 'schedules'));
    }

    

   public function facultySchedules()
{
    $rooms = Room::with([
        'department',
        'schedules'
    ])->get();


    return view('faculty.schedules', compact('rooms'));
}



public function deleteSchedule(Room $room)
{
    Schedule::where('room_id', $room->id)->delete();

    return redirect()
        ->route('chair.setschedule')
        ->with('success', 'Schedule deleted successfully.');

}


public function reserveRoom(Request $request, Room $room)
{
    $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'purpose' => 'nullable|string',
    ]);

    Reservation::create([
        'room_id' => $room->id,
        'user_id' => auth()->id(),
        'date' => $request->date,
        'day' => strtoupper(Carbon::parse($request->date)->format('D')),
        'time' => $request->time,
        'purpose' => $request->purpose,
        'status' => 'Pending',
    ]);

    return redirect()
        ->route('faculty.vacant', [
            'date' => $request->date,
            'time' => $request->time,
        ])
        ->with('success', 'Reservation submitted. Waiting for admin approval.');
}



public function reservations()
{
    $reservations = Reservation::with(['room', 'user'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('chair.reservations', compact('reservations'));
}

public function approveReservation(Reservation $reservation)
{


    if ($reservation->status !== 'Pending') {

        return redirect()
            ->route('chair.dashboard')
            ->with(
                'error',
                'This reservation has already been processed.'
            );
    }


    /*Make sure reservation has valid start/end times*/

    if (
        !$reservation->start_time ||
        !$reservation->end_time
    ) {

        return redirect()
            ->route('chair.dashboard')
            ->with(
                'error',
                'This reservation does not have a valid start and end time.'
            );
    }


    /*Check for overlapping approved reservation*/

    $overlap = Reservation::where(
        'room_id',
        $reservation->room_id
    )
    ->where(
        'date',
        $reservation->date
    )
    ->where(
        'status',
        'Approved'
    )
    ->where(
        'id',
        '!=',
        $reservation->id
    )
    ->where(function ($query) use ($reservation) {

        $query
            ->where(
                'start_time',
                '<',
                $reservation->end_time
            )
            ->where(
                'end_time',
                '>',
                $reservation->start_time
            );

    })
    ->exists();


    /*Don't approve overlapping reservation*/

    if ($overlap) {

        return redirect()
            ->route('chair.dashboard')
            ->with(
                'error',
                'This room is already reserved during the selected time.'
            );
    }


    /* Check class schedule*/

    $day = strtoupper(
        Carbon::parse($reservation->date)->format('D')
    );


    $occupied = Schedule::where(
        'room_id',
        $reservation->room_id
    )
    ->where(
        'day',
        $day
    )
    ->where(
        'time',
        $reservation->time
    )
    ->exists();


    /*Don't approve if room has a class*/

    if ($occupied) {

        return redirect()
            ->route('chair.dashboard')
            ->with(
                'error',
                'This room already has a scheduled class during the selected time.'
            );
    }


    /*Approve reservation*/

    $reservation->update([
        'status' => 'Approved'
    ]);


    /*Success*/

    return redirect()
        ->route('chair.dashboard')
        ->with(
            'success',
            'Reservation approved successfully.'
        );
}


public function declineReservation(Reservation $reservation)
{
    $reservation->update([
        'status' => 'Declined'
    ]);

    return redirect()
        ->route('chair.dashboard')
        ->with('success', 'Reservation declined.');
}


public function vacantRooms(Request $request)
{
    $rooms = Room::with('department')->get();

    $results = [];

    if ($request->filled('date') && $request->filled('time')) {

        /*Get selected date*/

        $day = strtoupper(
            Carbon::parse($request->date)->format('D')
        );


        /*Convert selected time*/

        $timeParts = explode('-', $request->time);

        $searchStart = Carbon::createFromFormat(
            'H:i',
            trim($timeParts[0])
        );

        $searchEnd = Carbon::createFromFormat(
            'H:i',
            trim($timeParts[1])
        );


        /*Check every room*/

        foreach ($rooms as $room) {


            /*Check Class Schedule*/

            $occupied = Schedule::where(
                'room_id',
                $room->id
            )
            ->where(
                'day',
                $day
            )
            ->where(
                'time',
                $request->time
            )
            ->exists();


            /*Check Approved Reservation */

            $reserved = false;


            $reservations = Reservation::where(
                'room_id',
                $room->id
            )
            ->where(
                'date',
                $request->date
            )
            ->where(
                'status',
                'Approved'
            )
            ->get();


            foreach ($reservations as $reservation) {

                /*Make sure reservation has start/end times*/

                if (
                    !$reservation->start_time ||
                    !$reservation->end_time
                ) {
                    continue;
                }


                /* Convert reservation times*/

                $reservationStart = Carbon::createFromFormat(
                    'H:i:s',
                    $reservation->start_time
                );

                $reservationEnd = Carbon::createFromFormat(
                    'H:i:s',
                    $reservation->end_time
                );


                /*Check overlap*/

                if (
                    $reservationStart->lt($searchEnd) &&
                    $reservationEnd->gt($searchStart)
                ) {

                    $reserved = true;

                    break;
                }
            }


            /* Determine Room Status*/

            if ($occupied) {

                $status = 'Occupied';

            } elseif ($reserved) {

                $status = 'Reserved';

            } else {

                $status = 'Available';
            }


            /*Add room to results */

            $results[] = [
                'room' => $room,
                'status' => $status
            ];
        }
    }


    /*Return View*/

    return view(
        'faculty.vacant-rooms',
        compact(
            'rooms',
            'results'
        )
    );
}
/*
|--------------------------------------------------------------------------
| FACULTY SUBJECTS
|--------------------------------------------------------------------------
*/

public function facultySubjects($faculty)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $faculty = User::where('id', $faculty)
        ->where('role', 'faculty')
        ->with([
            'department',
            'facultySubjects'
        ])
        ->firstOrFail();

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

   $request->validate([
    'faculty_id' => 'required|exists:users,id',
    'course_code' => 'required|string|max:50',
    'subject' => 'required|string|max:255',
    'year_level' => 'required|string|max:50',
    'major' => 'nullable|string|max:100',
    'program' => 'required|string|max:100',
]);

    /*
    |--------------------------------------------------------------------------
    | GET FACULTY
    |--------------------------------------------------------------------------
    */

    $faculty = User::where('id', $request->faculty_id)
        ->where('role', 'faculty')
        ->firstOrFail();


    /*
    |--------------------------------------------------------------------------
    | OPTIONAL: PREVENT DUPLICATE COURSE CODE FOR SAME FACULTY
    |--------------------------------------------------------------------------
    */

    $exists = FacultySubject::where(
        'faculty_id',
        $faculty->id
    )
    ->where(
        'course_code',
        strtoupper(trim($request->course_code))
    )
    ->exists();


    if ($exists) {

        return redirect()
            ->route('chair.setFaculty', [
                'department' => $faculty->department_id,
                'faculty' => $faculty->id,
            ])
            ->with(
                'error',
                'This course code is already assigned to this faculty member.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE SUBJECT
    |--------------------------------------------------------------------------
    */

    FacultySubject::create([
        'faculty_id' => $faculty->id,

        'course_code' => strtoupper(
            trim($request->course_code)
        ),

        'subject' => trim(
            $request->subject
        ),

        'year_level' => trim(
            $request->year_level
        ),

        'major' => $request->filled('major')
    ? trim($request->major)
    : null,

        'program' => trim(
            $request->program
        ),
    ]);


    /*
    |--------------------------------------------------------------------------
    | RETURN TO SET FACULTY
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('chair.setFaculty', [
            'department' => $faculty->department_id,
            'faculty' => $faculty->id,
        ])
        ->with(
            'success',
            'Subject assigned successfully.'
        );
}
public function updateFacultySubject(
    Request $request,
    $id
) {
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $request->validate([
        'course_code' =>
            'required|string|max:50',

        'subject' =>
            'required|string|max:255',

        'year_level' =>
            'required|string|max:50',

          'major' =>
    'nullable|string|max:100',

        'program' =>
            'required|string|max:100',
    ]);


    $facultySubject =
        FacultySubject::findOrFail($id);


    $faculty =
        User::where(
            'id',
            $facultySubject->faculty_id
        )
        ->where(
            'role',
            'faculty'
        )
        ->firstOrFail();


    $facultySubject->update([
        'course_code' =>
            strtoupper(
                trim($request->course_code)
            ),

        'subject' =>
            trim($request->subject),

        'year_level' =>
            trim($request->year_level),

         'major' =>
    $request->filled('major')
        ? trim($request->major)
        : null,

        'program' =>
        trim($request->program),
    ]);


   return redirect()
    ->route('chair.setFaculty', [
        'department' => $faculty->department_id,
        'faculty' => $faculty->id,
    ])
    ->with(
        'success',
        'Subject updated successfully.'
    );
}
public function deleteFacultySubject($id)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $facultySubject =
        FacultySubject::findOrFail($id);


    $faculty =
        User::where(
            'id',
            $facultySubject->faculty_id
        )
        ->where(
            'role',
            'faculty'
        )
        ->firstOrFail();


    $facultySubject->delete();


 return redirect()
    ->route('chair.setFaculty', [
        'department' => $faculty->department_id,
        'faculty' => $faculty->id,
    ])
    ->with(
        'success',
        'Subject removed successfully.'
    );
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

    $drafts = ScheduleDraft::where(
        'room_id',
        $room->id
    )
    ->where(
        'created_by',
        $chair->id
    )
    ->orderBy('id')
    ->get();


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
            'faculty.department'
        ])
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
        ->orderBy(
            'course_code'
        )
        ->get();


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
            'isEditingDraft'
        )
    );
}

public function publishDraft(Request $request, $roomId)
{
    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();

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

    $drafts = ScheduleDraft::where('room_id', $room->id)
        ->where('created_by', $chair->id)
        ->orderBy('id')
        ->get();

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
        Schedule::where(
            'room_id',
            $room->id
        )->get();


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
    | PUBLISH
    |--------------------------------------------------------------------------
    */

    DB::transaction(
        function () use (
            $room,
            $chair,
            $drafts
        ) {

            /*
            |--------------------------------------------------------------------------
            | REMOVE EXISTING PUBLISHED SCHEDULE
            |--------------------------------------------------------------------------
            */

            Schedule::where(
                'room_id',
                $room->id
            )->delete();


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

                    'department_id' =>
                        $draft->department_id
                        ?? $chair->department_id,

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
            ->where(
                'created_by',
                $chair->id
            )
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

public function modifySchedule()
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


    /*
    |--------------------------------------------------------------------------
    | LOAD ALL DEPARTMENTS
    |--------------------------------------------------------------------------
    |
    | Modify Schedule can VIEW schedules from every department.
    |
    | Each room also loads:
    | - allow_other_departments
    | - schedules
    |
    */

    $departments = Department::with([
        'buildings.rooms' => function ($query) {

            $query->with([
                'schedules'
            ]);

        }
    ])
    ->orderBy('name')
    ->get();


    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'chair.modifyschedule',
        compact(
            'departments',
            'chair'
        )
    );
}
public function storeModifiedSchedule(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    if (!Auth::check() || Auth::user()->role !== 'chair') {
        abort(403, 'Unauthorized.');
    }

    $chair = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | VALIDATE
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'room_id' =>
            'required|exists:rooms,id',

        'day' =>
            'required|string|max:20',

        'time' =>
            'required|string|max:50',

        'course_code' =>
            'required|string|max:50',

        'semester' =>
            'nullable|string|max:100',

        'academic_year' =>
            'nullable|string|max:100',
        
        'major' =>
    'nullable|string|max:50',

        'program' =>
            'required|string|max:100',


        'color' =>
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
    | NORMALIZE DAY / TIME
    |--------------------------------------------------------------------------
    */

    $day =
        strtoupper(
            trim(
                $request->day
            )
        );

    $time =
        trim(
            $request->time
        );


    /*
    |--------------------------------------------------------------------------
    | FIND EXISTING SCHEDULE
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
        ->first();


    /*
    |--------------------------------------------------------------------------
    | CHECK EXISTING SCHEDULE
    |--------------------------------------------------------------------------
    |
    | If a schedule already exists:
    |
    | OWN DEPARTMENT
    |     -> Chair can modify it.
    |
    | OTHER DEPARTMENT
    |     -> Always locked.
    |
    */

    if ($existingSchedule) {

        if (
            (int) $existingSchedule->department_id !==
            (int) $chair->department_id
        ) {

            return back()
                ->with(
                    'error',
                    'This schedule belongs to another department and cannot be modified.'
                )
                ->withInput();
        }

    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY SLOT
    |--------------------------------------------------------------------------
    |
    | If there is NO existing schedule:
    |
    | OWN ROOM
    |     -> Always allowed.
    |
    | OTHER DEPARTMENT ROOM
    |     -> Only allowed when permission is ON.
    |
    */

    else {

        if (
            (int) $room->department_id !==
            (int) $chair->department_id
        ) {

            if (
                !$room->allow_other_departments
            ) {

                return back()
                    ->with(
                        'error',
                        'This department does not allow other departments to add schedules to this room.'
                    )
                    ->withInput();
            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | FIND COURSE ASSIGNMENT
    |--------------------------------------------------------------------------
    |
    | The course must belong to the logged-in Chair's department.
    |
    */

    $courseCode =
        strtoupper(
            trim(
                $request->course_code
            )
        );


    $facultySubject =
        FacultySubject::with(
            'faculty'
        )
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
    | COURSE NOT FOUND
    |--------------------------------------------------------------------------
    */

    if (!$facultySubject) {

        return back()
            ->with(
                'error',
                'This course code is not assigned to a faculty member in your department.'
            )
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | MAKE SURE FACULTY EXISTS
    |--------------------------------------------------------------------------
    */

    if (!$facultySubject->faculty) {

        return back()
            ->with(
                'error',
                'The faculty member assigned to this course could not be found.'
            )
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE DATA
    |--------------------------------------------------------------------------
    */

    $scheduleData = [

        'room_id' =>
            $room->id,

        'faculty_id' =>
            $facultySubject->faculty_id,

        /*
        | Department of the person creating/modifying
        */

        'department_id' =>
            $chair->department_id,

        'semester' =>
            $request->semester
                ? trim($request->semester)
                : null,

        'academic_year' =>
            $request->academic_year
                ? trim($request->academic_year)
                : null,

        'day' =>
            $day,

        'time' =>
            $time,

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
        str_contains($courseCode, '.') ? 'Lab' : 'Lec',

        'instructor' =>
            $facultySubject->faculty->name,


        'color' =>
            $request->color
                ?: '#ffffff',

    ];


    /*
    |--------------------------------------------------------------------------
    | UPDATE OWN EXISTING SCHEDULE
    |--------------------------------------------------------------------------
    */

    if ($existingSchedule) {

        $existingSchedule->update(
            $scheduleData
        );


        return redirect()
            ->route(
                'chair.modifyschedule'
            )
            ->with(
                'success',
                'Your schedule was updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE NEW SCHEDULE
    |--------------------------------------------------------------------------
    */

    Schedule::create(
        $scheduleData
    );


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'chair.modifyschedule'
        )
        ->with(
            'success',
            'Schedule added successfully to the empty slot.'
        );
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

    $schedule = Schedule::find(
        $validated['schedule_id']
    );


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
}