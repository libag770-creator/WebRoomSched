<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\AcademicTerm;

class StudentController extends Controller
{
    /**
     * =========================================================
     * GET ACTIVE ACADEMIC TERM
     * =========================================================
     *
     * This always reads the current active term from the
     * academic_terms table.
     *
     * IMPORTANT:
     * We DO NOT use the latest schedule.
     *
     * Only the term where is_active = true is displayed.
     */
    private function getActiveAcademicTerm()
    {
        return AcademicTerm::query()
            ->where('is_active', true)
            ->first();
    }


    /**
     * =========================================================
     * STUDENT SCHEDULE PAGE
     * =========================================================
     */
    public function schedule()
    {
        $departments = Department::query()
            ->orderBy('name', 'asc')
            ->get();

        /*
         * Get the currently active academic term.
         */
        $activeTerm = $this->getActiveAcademicTerm();

        /*
         * If there is an active term, use its values.
         *
         * Do NOT get semester/year from the latest schedule.
         */
        $semester = $activeTerm?->semester;

        $academicYear = $activeTerm?->academic_year;

        return view(
            'student.schedule',
            compact(
                'departments',
                'activeTerm',
                'semester',
                'academicYear'
            )
        );
    }


    /**
     * =========================================================
     * GET PROGRAMS
     * =========================================================
     *
     * Only programs belonging to the ACTIVE ACADEMIC TERM
     * are returned.
     *
     * Department
     *      ↓
     * Active Term
     *      ↓
     * Program
     */
    public function getPrograms(Request $request)
    {
        $request->validate([
            'department_id' => [
                'required',
                'integer',
            ],
        ]);


        /*
         * Get current active term.
         */
        $activeTerm = $this->getActiveAcademicTerm();


        /*
         * No active term = no programs.
         */
        if (!$activeTerm) {

            return response()->json([
                'success' => true,
                'programs' => [],
                'message' =>
                    'There is currently no active academic term.',
            ]);
        }


        try {

            $programs = Schedule::query()

                /*
                 * VERY IMPORTANT:
                 * Only schedules belonging to the
                 * active academic term.
                 */
                ->where(
                    'academic_term_id',
                    $activeTerm->id
                )

                /*
                 * Department
                 */
                ->where(
                    'department_id',
                    $request->department_id
                )

                /*
                 * Program must have a value.
                 */
                ->whereNotNull('program')

                ->whereRaw(
                    "TRIM(program) <> ''"
                )

                ->select('program')

                ->distinct()

                ->orderBy(
                    'program',
                    'asc'
                )

                ->pluck('program')

                ->map(function ($program) {

                    return trim(
                        (string) $program
                    );

                })

                ->filter(function ($program) {

                    return $program !== '';

                })

                ->unique()

                ->values();


            return response()->json([
                'success' => true,

                'programs' => $programs,

                'academic_term_id' =>
                    $activeTerm->id,

                'academic_year' =>
                    $activeTerm->academic_year,

                'semester' =>
                    $activeTerm->semester,
            ]);

        } catch (\Throwable $e) {

            \Log::error(
                'Student program loading failed.',
                [
                    'department_id' =>
                        $request->department_id,

                    'academic_term_id' =>
                        $activeTerm->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([
                'success' => false,

                'programs' => [],

                'message' =>
                    'Unable to load programs.',
            ], 500);
        }
    }


    /**
     * =========================================================
     * GET YEAR LEVELS
     * =========================================================
     *
     * Department
     *      ↓
     * Program
     *      ↓
     * Active Term
     *      ↓
     * Year Level
     */
    public function getYearLevels(Request $request)
    {
        $request->validate([
            'department_id' => [
                'required',
                'integer',
            ],

            'program' => [
                'required',
                'string',
            ],
        ]);


        /*
         * Get active term.
         */
        $activeTerm =
            $this->getActiveAcademicTerm();


        /*
         * No active term.
         */
        if (!$activeTerm) {

            return response()->json([
                'success' => true,
                'year_levels' => [],

                'message' =>
                    'There is currently no active academic term.',
            ]);
        }


        try {

            $yearLevels = Schedule::query()

                /*
                 * ONLY ACTIVE TERM
                 */
                ->where(
                    'academic_term_id',
                    $activeTerm->id
                )

                /*
                 * Department
                 */
                ->where(
                    'department_id',
                    $request->department_id
                )

                /*
                 * Program
                 */
                ->where(
                    'program',
                    $request->program
                )

                /*
                 * Year Level
                 */
                ->whereNotNull(
                    'year_level'
                )

                ->whereRaw(
                    "TRIM(year_level) <> ''"
                )

                ->select(
                    'year_level'
                )

                ->distinct()

                ->orderBy(
                    'year_level',
                    'asc'
                )

                ->pluck(
                    'year_level'
                )

                ->map(function ($year) {

                    return trim(
                        (string) $year
                    );

                })

                ->filter(function ($year) {

                    return $year !== '';

                })

                ->unique()

                ->values();


            return response()->json([
                'success' => true,

                'year_levels' =>
                    $yearLevels,

                'academic_term_id' =>
                    $activeTerm->id,

                'academic_year' =>
                    $activeTerm->academic_year,

                'semester' =>
                    $activeTerm->semester,
            ]);

        } catch (\Throwable $e) {

            \Log::error(
                'Student year level loading failed.',
                [
                    'department_id' =>
                        $request->department_id,

                    'program' =>
                        $request->program,

                    'academic_term_id' =>
                        $activeTerm->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([
                'success' => false,

                'year_levels' => [],

                'message' =>
                    'Unable to load year levels.',
            ], 500);
        }
    }


    /**
     * =========================================================
     * GET PUBLISHED SCHEDULES
     * =========================================================
     *
     * ONLY schedules from the current active academic term
     * are returned.
     *
     * Department
     *      ↓
     * Program
     *      ↓
     * Year Level
     *      ↓
     * ACTIVE TERM
     *      ↓
     * Published Schedule
     */
    public function getSchedules(Request $request)
    {
        $request->validate([
            'department_id' => [
                'required',
                'integer',
            ],

            'program' => [
                'required',
                'string',
            ],

            'year_level' => [
                'required',
                'string',
            ],
        ]);


        /*
         * Get current active academic term.
         */
        $activeTerm =
            $this->getActiveAcademicTerm();


        /*
         * No active term.
         */
        if (!$activeTerm) {

            return response()->json([
                'success' => true,

                'schedules' => [],

                'academic_term' => null,

                'message' =>
                    'There is currently no active academic term.',
            ]);
        }


        try {

            $schedules = Schedule::query()

                /*
                 * =====================================================
                 * MOST IMPORTANT FILTER
                 * =====================================================
                 *
                 * This guarantees that old schedules from another
                 * academic year or semester are NOT displayed.
                 */
                ->where(
                    'academic_term_id',
                    $activeTerm->id
                )

                /*
                 * Department
                 */
                ->where(
                    'department_id',
                    $request->department_id
                )

                /*
                 * Program
                 */
                ->where(
                    'program',
                    $request->program
                )

                /*
                 * Year Level
                 */
                ->where(
                    'year_level',
                    $request->year_level
                )

                /*
                 * Room information
                 */
                ->with([
                    'room',
                ])

                /*
                 * Schedule order
                 */
                ->orderBy(
                    'day',
                    'asc'
                )

                ->orderBy(
                    'time',
                    'asc'
                )

                ->get();


            /*
             * Add a simple room value for JavaScript.
             */
            $schedules->transform(
                function ($schedule) {

                    $schedule->room =
                        $schedule->room
                            ? (
                                $schedule->room->room
                                ?? $schedule->room->name
                                ?? $schedule->room->room_name
                                ?? $schedule->room->code
                                ?? $schedule->room_id
                            )
                            : '';


                    return $schedule;
                }
            );


            return response()->json([
                'success' => true,

                'schedules' =>
                    $schedules,

                /*
                 * Send active term information to the
                 * student page as well.
                 */
                'academic_term' => [
                    'id' =>
                        $activeTerm->id,

                    'academic_year' =>
                        $activeTerm->academic_year,

                    'semester' =>
                        $activeTerm->semester,
                ],
            ]);

        } catch (\Throwable $e) {

            \Log::error(
                'Student schedule loading failed.',
                [
                    'department_id' =>
                        $request->department_id,

                    'program' =>
                        $request->program,

                    'year_level' =>
                        $request->year_level,

                    'academic_term_id' =>
                        $activeTerm->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([
                'success' => false,

                'schedules' => [],

                'message' =>
                    'Unable to load the schedule.',
            ], 500);
        }
    }
}