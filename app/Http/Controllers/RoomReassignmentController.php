<?php



namespace App\Http\Controllers;



use App\Models\AcademicTerm;

use App\Models\Building;

use App\Models\Department;

use App\Models\Room;

use App\Models\RoomReassignment;

use App\Models\RoomScheduleTimeSlot;

use App\Models\Schedule;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;



use Illuminate\Support\Facades\DB;



class RoomReassignmentController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | INDEX

    |--------------------------------------------------------------------------

    */



  public function index(Request $request)

{

    /*

    |--------------------------------------------------------------------------

    | ADMIN CHECK

    |--------------------------------------------------------------------------

    */



    if (!auth()->check()) {

        abort(403, 'Unauthorized.');

    }



    if (

        isset(auth()->user()->role) &&

        strtolower((string) auth()->user()->role) !== 'admin'

    ) {

        abort(403, 'Unauthorized.');

    }





    /*

    |--------------------------------------------------------------------------

    | ACADEMIC TERM

    |--------------------------------------------------------------------------

    */



    $activeTerm = $this->resolveAcademicTerm($request);





    /*

    |--------------------------------------------------------------------------

    | EXPIRE OLD REASSIGNMENTS

    |--------------------------------------------------------------------------

    */



    RoomReassignment::query()

        ->where('status', 'active')

        ->whereNotNull('expires_at')

        ->where('expires_at', '<=', now())

        ->update([

            'status' => 'expired',

        ]);





    /*

    |--------------------------------------------------------------------------

    | DEPARTMENTS

    |--------------------------------------------------------------------------

    |

    | Department

    |    └── Building

    |          └── Room

    |

    | This is used by:

    | Department -> Building -> Room dropdowns.

    |

    */



    $departments = Department::with([

        'buildings.rooms'

    ])

        ->orderBy('name')

        ->get();





    /*

    |--------------------------------------------------------------------------

    | ALL ROOMS

    |--------------------------------------------------------------------------

    */



    $rooms = Room::with([

        'department',

        'building',

    ])

        ->orderBy('room_name')

        ->get();







/*
|--------------------------------------------------------------------------
| LOAD PUBLISHED SCHEDULES FOR THE SELECTED ACADEMIC TERM
|--------------------------------------------------------------------------
*/

$schedulesQuery = Schedule::with([
    'room',
    'room.building',
    'room.department',
])
    ->whereNull('deleted_at');

if ($activeTerm) {
    $schedulesQuery->where(
        'academic_term_id',
        $activeTerm->id
    );
}

$schedules = $schedulesQuery
    ->orderBy('room_id')
    ->orderBy('row_position')
    ->orderBy('id')
    ->get();


    /*

    |--------------------------------------------------------------------------

    | ROOM TIMETABLE ROWS — INCLUDING EMPTY CELLS

    |--------------------------------------------------------------------------

    |

    | Set Schedule saves the timetable rows separately from the actual

    | schedules. This table is therefore the source of truth for the

    | timetable layout. It contains rows even when no subject was assigned.

    |

    */



   $roomTimeSlots = RoomScheduleTimeSlot::query()
    ->where('academic_term_id', $activeTerm->id)
    ->where('is_active', true)
    ->orderBy('room_id')
    ->orderBy('row_position')
    ->orderBy('start_time')
    ->get();





    /*

    |--------------------------------------------------------------------------

    | ACTIVE REASSIGNMENTS

    |--------------------------------------------------------------------------

    */



    $reassignments = RoomReassignment::with([

        'schedule',

        'originalRoom',

        'newRoom',

    ])

        ->active()

        ->latest()

        ->get();





    /*

    |--------------------------------------------------------------------------

    | SEND EVERYTHING TO BLADE

    |--------------------------------------------------------------------------

    */



    return view(

        'admin.roomreassignment',

        [

            'departments'   => $departments,

            'rooms'         => $rooms,

            'schedules'     => $schedules,

            'roomTimeSlots'  => $roomTimeSlots,

            'reassignments' => $reassignments,

            'activeTerm'    => $activeTerm,

        ]

    );

}



    /*

    |--------------------------------------------------------------------------

    | STORE

    |--------------------------------------------------------------------------

    */



    public function store(Request $request)

    {

        if (!Auth::check() || Auth::user()->role !== 'admin') {

            abort(403, 'Unauthorized.');

        }



        /*

        |--------------------------------------------------------------------------

        | VALIDATION

        |--------------------------------------------------------------------------

        */



        
$validated = $request->validate([
    'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
    'original_room_id' => ['required', 'integer', 'exists:rooms,id'],
    'new_room_id' => [
        'required',
        'integer',
        'exists:rooms,id',
        'different:original_room_id',
    ],
    'day' => ['required', 'string', 'in:MON,TUE,WED,THU,FRI,SAT'],
    'time' => ['required', 'string'],
    'reason' => ['required', 'string', 'max:255'],
    'details' => ['nullable', 'string', 'max:2000'],

    'occupancy_decision' => ['required', 'in:yes,no'],
    'occupied_by' => ['nullable', 'string', 'max:255', 'required_if:occupancy_decision,yes'],
    'occupancy_purpose' => ['nullable', 'string', 'max:255', 'required_if:occupancy_decision,yes'],
    'occupancy_details' => ['nullable', 'string', 'max:2000'],
    'not_occupied_reason' => ['nullable', 'string', 'max:255', 'required_if:occupancy_decision,no'],
    'not_occupied_details' => ['nullable', 'string', 'max:2000'],
]);


    





        /*

        |--------------------------------------------------------------------------

        | FIND ORIGINAL SCHEDULE

        |--------------------------------------------------------------------------

        */



        $schedule = Schedule::with([

            'room'

        ])->findOrFail(

            $validated['schedule_id']

        );





        /*

        |--------------------------------------------------------------------------

        | VERIFY ORIGINAL ROOM

        |--------------------------------------------------------------------------

        */



        if (

            (int) $schedule->room_id

            !==

            (int) $validated['original_room_id']

        ) {



            return back()

                ->withInput()

                ->with(

                    'error',

                    'The selected schedule does not belong to the selected room.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | VERIFY DAY

        |--------------------------------------------------------------------------

        */



        if (

            strtoupper(

                trim(

                    (string) $schedule->day

                )

            )

            !==

            strtoupper(

                trim(

                    (string) $validated['day']

                )

            )

        ) {



            return back()

                ->withInput()

                ->with(

                    'error',

                    'The selected day does not match the schedule.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | VERIFY TIME

        |--------------------------------------------------------------------------

        */



        $selectedTime =

            $this->normalizeTimeRange(

                $validated['time']

            );



        $scheduleTime =

            $this->normalizeTimeRange(

                $schedule->time

            );





        if (

            $selectedTime['start']

            !==

            $scheduleTime['start']

            ||

            $selectedTime['end']

            !==

            $scheduleTime['end']

        ) {



            return back()

                ->withInput()

                ->with(

                    'error',

                    'The selected time does not match the schedule.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | TARGET ROOM

        |--------------------------------------------------------------------------

        */



        $newRoom = Room::findOrFail(

            $validated['new_room_id']

        );





        /*

        |--------------------------------------------------------------------------

        | CHECK TARGET ROOM

        |--------------------------------------------------------------------------

        */



        if (

            (int) $newRoom->id

            ===

            (int) $schedule->room_id

        ) {



            return back()

                ->withInput()

                ->with(

                    'error',

                    'The new room must be different from the original room.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | ACTIVE TERM

        |--------------------------------------------------------------------------

        */



        $activeTerm =

            $this->resolveAcademicTerm(

                $request

            );





        /*

        |--------------------------------------------------------------------------

        | CHECK TARGET ROOM SCHEDULE

        |--------------------------------------------------------------------------

        */



        $targetOccupied =

            Schedule::query()

                ->where(

                    'room_id',

                    $newRoom->id

                )

                ->where(

                    'day',

                    strtoupper(

                        trim(

                            $validated['day']

                        )

                    )

                )

                ->where(

                    'time',

                    $schedule->time

                )

                ->where(function ($query) use ($activeTerm) {



                    $query

                        ->where(

                            'academic_term_id',

                            $activeTerm->id

                        )



                        ->orWhere(function ($legacy) use ($activeTerm) {



                            $legacy

                                ->whereNull(

                                    'academic_term_id'

                                )



                                ->where(function ($semester) use ($activeTerm) {



                                    $semester

                                        ->where(

                                            'semester',

                                            $activeTerm->semester

                                        )

                                        ->orWhereNull('semester')

                                        ->orWhere('semester', '');



                                })



                                ->where(function ($year) use ($activeTerm) {



                                    $year

                                        ->where(

                                            'academic_year',

                                            $activeTerm->academic_year

                                        )

                                        ->orWhereNull('academic_year')

                                        ->orWhere('academic_year', '');



                                });



                        });



                })

                ->exists();





        if ($targetOccupied) {



            return back()

                ->withInput()

                ->with(

                    'error',

                    'The selected room is already occupied during this time.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | CHECK ACTIVE REASSIGNMENT

        |--------------------------------------------------------------------------

        */



        $targetAlreadyReassigned =

            RoomReassignment::query()

                ->active()

                ->where(

                    'new_room_id',

                    $newRoom->id

                )

                ->where(

                    'day',

                    strtoupper(

                        trim(

                            $validated['day']

                        )

                    )

                )

                ->where(

                    'start_time',

                    $selectedTime['start']

                )

                ->where(

                    'end_time',

                    $selectedTime['end']

                )

                ->exists();





        if ($targetAlreadyReassigned) {



            return back()

                ->withInput()

                ->with(

                    'error',

                    'The selected room is already being used by another reassignment.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | CHECK SAME CELL

        |--------------------------------------------------------------------------

        */



        $sameCell =

            RoomReassignment::query()

                ->active()

                ->where(

                    'schedule_id',

                    $schedule->id

                )

                ->where(

                    'day',

                    strtoupper(

                        trim(

                            $validated['day']

                        )

                    )

                )

                ->where(

                    'start_time',

                    $selectedTime['start']

                )

                ->where(

                    'end_time',

                    $selectedTime['end']

                )

                ->exists();





        if ($sameCell) {



            return back()

                ->withInput()

                ->with(

                    'error',

                    'This schedule cell already has an active reassignment.'

                );

        }





        /*

        |--------------------------------------------------------------------------

        | CREATE EXPIRATION PERIOD

        |--------------------------------------------------------------------------

        */



        $period =

            $this->buildOccurrencePeriod(

                $validated['day'],

                $selectedTime['start'],

                $selectedTime['end']

            );





        /*

        |--------------------------------------------------------------------------

        | CREATE REASSIGNMENT

        |--------------------------------------------------------------------------

        |

        | IMPORTANT:

        |

        | The original schedules table is NOT modified.

        |

        */



        DB::transaction(function () use (

            $schedule,

            $newRoom,

            $validated,

            $period

        ) {



            RoomReassignment::create([

            

'occupancy_decision' => $validated['occupancy_decision'],

'occupied_by' => $validated['occupancy_decision'] === 'yes'
    ? $validated['occupied_by']
    : null,

'occupancy_purpose' => $validated['occupancy_decision'] === 'yes'
    ? $validated['occupancy_purpose']
    : null,

'occupancy_details' => $validated['occupancy_decision'] === 'yes'
    ? ($validated['occupancy_details'] ?? null)
    : null,

'not_occupied_reason' => $validated['occupancy_decision'] === 'no'
    ? $validated['not_occupied_reason']
    : null,

'not_occupied_details' => $validated['occupancy_decision'] === 'no'
    ? ($validated['not_occupied_details'] ?? null)
    : null,




                'schedule_id' =>

                    $schedule->id,



                'original_room_id' =>

                    $schedule->room_id,



                'new_room_id' =>

                    $newRoom->id,



                'day' =>

                    strtoupper(

                        trim(

                            $validated['day']

                        )

                    ),



                'start_time' =>

                    $period['start_time'],



                'end_time' =>

                    $period['end_time'],



                'reason' =>

                    $validated['reason'],



                'details' =>

                    $validated['details'] ?? null,



               'starts_at' => now(),



                'expires_at' =>

                    $period['expires_at'],



                'status' =>

                    'active',

            ]);

        });





        /*

        |--------------------------------------------------------------------------

        | SUCCESS

        |--------------------------------------------------------------------------

        */



        return redirect()

            ->route(

                'admin.roomreassignment'

            )

            ->with(

                'success',

                'Room reassigned successfully. The original schedule was not changed.'

            );

    }





    /*

    |--------------------------------------------------------------------------

    | CANCEL REASSIGNMENT

    |--------------------------------------------------------------------------

    */



    public function destroy(

        RoomReassignment $roomReassignment

    ) {



        if (!Auth::check() || Auth::user()->role !== 'admin') {

            abort(403, 'Unauthorized.');

        }





        $roomReassignment->update([



            'status' =>

                'cancelled',



        ]);





        return redirect()

            ->route(

                'admin.roomreassignment'

            )

            ->with(

                'success',

                'Room reassignment cancelled successfully.'

            );

    }





    /*

    |--------------------------------------------------------------------------

    | ACADEMIC TERM

    |--------------------------------------------------------------------------

    */



    private function resolveAcademicTerm(

        Request $request

    ): AcademicTerm {



        $termId =

            $request->input('term_id')

            ??

            $request->query('term_id');





        if ($termId) {



            $term =

                AcademicTerm::find(

                    (int) $termId

                );



            if ($term) {

                return $term;

            }

        }





        $sessionTermId =

            session(

                'active_academic_term_id'

            );





        if ($sessionTermId) {



            $term =

                AcademicTerm::find(

                    (int) $sessionTermId

                );



            if ($term) {

                return $term;

            }

        }





        $term =

            AcademicTerm::where(

                'is_active',

                true

            )->first();





        if ($term) {

            return $term;

        }





        $term =

            AcademicTerm::orderByDesc(

                'id'

            )->first();





        if ($term) {

            return $term;

        }





        return AcademicTerm::create([



            'academic_year' =>

                date('Y')

                . '-'

                .

                (date('Y') + 1),



            'semester' =>

                '1st Semester',



            'status' =>

                'active',



            'is_active' =>

                true,



        ]);

    }





    /*

    |--------------------------------------------------------------------------

    | NORMALIZE TIME

    |--------------------------------------------------------------------------

    */



    private function normalizeTimeRange(

        string $time

    ): array {



        $parts =

            explode(

                '-',

                $time,

                2

            );





        if (count($parts) !== 2) {



            abort(

                422,

                'Invalid schedule time.'

            );

        }





        $start =

            trim(

                $parts[0]

            );



        $end =

            trim(

                $parts[1]

            );





        /*

        |--------------------------------------------------------------------------

        | SUPPORT 24-HOUR

        |--------------------------------------------------------------------------

        */



        try {



            $startCarbon =

                Carbon::createFromFormat(

                    'H:i',

                    $start

                );



        } catch (\Throwable $e) {



            $startCarbon =

                Carbon::createFromFormat(

                    'g:i',

                    $start

                );

        }





        try {



            $endCarbon =

                Carbon::createFromFormat(

                    'H:i',

                    $end

                );



        } catch (\Throwable $e) {



            $endCarbon =

                Carbon::createFromFormat(

                    'g:i',

                    $end

                );

        }





        return [



            'start' =>

                $startCarbon->format(

                    'H:i:s'

                ),



            'end' =>

                $endCarbon->format(

                    'H:i:s'

                ),



        ];

    }





    /*

    |--------------------------------------------------------------------------

    | BUILD NEXT OCCURRENCE

    |--------------------------------------------------------------------------

    */



    private function buildOccurrencePeriod(

        string $day,

        string $startTime,

        string $endTime

    ): array {



        $day =

            strtoupper(

                trim(

                    $day

                )

            );





        $dayNumbers = [



            'SUN' =>

                Carbon::SUNDAY,



            'MON' =>

                Carbon::MONDAY,



            'TUE' =>

                Carbon::TUESDAY,



            'WED' =>

                Carbon::WEDNESDAY,



            'THU' =>

                Carbon::THURSDAY,



            'FRI' =>

                Carbon::FRIDAY,



            'SAT' =>

                Carbon::SATURDAY,



        ];





        $targetDay =

            $dayNumbers[$day]

            ??

            Carbon::MONDAY;





        $now =

            Carbon::now();





        $date =

            $now->copy()

                ->startOfDay();





        while (

            $date->dayOfWeek

            !==

            $targetDay

        ) {



            $date->addDay();



        }





        $startCarbon =

            Carbon::createFromFormat(

                'H:i:s',

                $startTime

            );





        $endCarbon =

            Carbon::createFromFormat(

                'H:i:s',

                $endTime

            );





        $start =

            $date->copy()

                ->setTime(

                    $startCarbon->hour,

                    $startCarbon->minute,

                    0

                );





        $end =

            $date->copy()

                ->setTime(

                    $endCarbon->hour,

                    $endCarbon->minute,

                    0

                );





        /*

        |--------------------------------------------------------------------------

        | IF TODAY'S PERIOD HAS PASSED

        |--------------------------------------------------------------------------

        */



        if (

            $date->isSameDay($now)

            &&

            $end->lessThanOrEqualTo($now)

        ) {



            $start->addWeek();

            $end->addWeek();



        }





        return [



            'start_time' =>

                $start->format(

                    'H:i:s'

                ),



            'end_time' =>

                $end->format(

                    'H:i:s'

                ),



            'starts_at' =>

                $start,



            'expires_at' =>

                $end,



        ];

    }
public function buildings(\App\Models\Department $department)
{
    if (!auth()->check() ||
        strtolower((string) auth()->user()->role) !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $buildings = \App\Models\Building::query()
        ->where('department_id', $department->id)
        ->withCount('rooms')
        ->orderBy('name')
        ->get(['id', 'name']);

    return response()->json(
        $buildings->map(function ($building) {
            return [
                'id' => $building->id,
                'name' => $building->name,
                'rooms_count' => (int) $building->rooms_count,
            ];
        })->values()
    );
}

public function rooms(\App\Models\Building $building)
{
    if (!auth()->check() ||
        strtolower((string) auth()->user()->role) !== 'admin') {
        abort(403, 'Unauthorized.');
    }

    $rooms = \App\Models\Room::query()
        ->where('building_id', $building->id)
        ->orderBy('room_name')
        ->get(['id', 'room_name', 'building_id', 'department_id']);

    return response()->json(
        $rooms->map(function ($room) use ($building) {
            return [
                'id' => $room->id,
                'name' => $room->room_name,
                'building_id' => $room->building_id,
                'department_id' => $room->department_id,
                'building_name' => $building->name,
            ];
        })->values()
    );
}
}