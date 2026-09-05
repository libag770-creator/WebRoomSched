<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Department;
use App\Models\FacultySubject;
use App\Models\Building;
use App\Models\ScheduleDraft;
use App\Models\RoomSwapRequest;

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
    | DASHBOARD VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'chair.dashboard',
        compact(
            'departments',
            'reservations',
            'swapRequests'
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

        'schedule.*.course_code' =>
            'required|string|max:50',

        'schedule.*.subject' =>
            'required|string|max:255',

        'schedule.*.year_level' =>
            'required|string|max:50',

        'schedule.*.instructor' =>
            'required|string|max:255',

        'schedule.*.description' =>
            'nullable|string',

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

            'course_code' =>
                $facultySubject->course_code,

            'subject' =>
                $facultySubject->subject,

            'year_level' =>
                $facultySubject->year_level,

            'instructor' =>
                $facultySubject->faculty->name,

            'description' =>
                $data['description']
                ?? null,

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
        Schedule::where(
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

        'schedule.*.course_code' =>
            'required|string|max:50',

        'schedule.*.description' =>
            'nullable|string',

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


        /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING SLOT
        |--------------------------------------------------------------------------
        */

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
                'time',
                $time
            )
            ->first();


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

            'subject_type' =>
                $data['subject_type'] ?? null,

            'instructor' =>
                $facultySubject->faculty->name,

            'description' =>
                $data['description'] ?? null,

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

        'subject_type' =>
            'nullable|string|in:Major,Non-major',

        'description' =>
            'nullable|string|max:255',

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

        'subject_type' =>
            $request->subject_type
                ?: null,

        'instructor' =>
            $facultySubject->faculty->name,

        'description' =>
            $request->description
                ? trim($request->description)
                : null,

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
}