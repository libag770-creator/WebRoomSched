<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\ScheduleMerge;
use App\Models\Subject;
use App\Models\AcademicTerm;
use App\Models\RoomScheduleTimeSlot;
use App\Models\ScheduleCellMerge;
use App\Models\Program;
use App\Models\RoomSwapRequest;
use Carbon\Carbon;

class StudentController extends Controller
{
    private function studentProgramLabel(string $value, $catalogPrograms): string
    {
        $value = trim($value);
        foreach ($catalogPrograms as $catalogProgram) {
            if (
                strcasecmp(trim((string) $catalogProgram->name), $value) === 0
                || strcasecmp(trim((string) $catalogProgram->code), $value) === 0
            ) {
                return trim((string) $catalogProgram->code) ?: $value;
            }
        }

        if (preg_match('/\(([^()]*)\)\s*$/u', $value, $matches) === 1) {
            return trim($matches[1]);
        }

        return $value;
    }

    /** Resolve name/code variants represented by the same Step 2 option. */
    private function studentProgramAliases(int $departmentId, int $termId, string $selectedProgram): array
    {
        $catalogPrograms = Program::query()
            ->where('department_id', $departmentId)
            ->get(['name', 'code']);

        $programValues = Schedule::query()
            ->where('academic_term_id', $termId)
            ->where('department_id', $departmentId)
            ->whereNotNull('program')
            ->pluck('program');

        $mergedValues = ScheduleMerge::with([
            'subject.program',
            'facultySubject.subjectCatalog.program',
            'facultySubject.faculty',
        ])
            ->where('status', 'approved')
            ->whereHas('schedule', fn ($query) => $query->where('academic_term_id', $termId))
            ->where(function ($query) use ($departmentId) {
                $query->whereHas('subject.program', fn ($programQuery) => $programQuery->where('department_id', $departmentId))
                    ->orWhereHas('facultySubject.subjectCatalog.program', fn ($programQuery) => $programQuery->where('department_id', $departmentId));
            })
            ->get()
            ->filter(function ($merge) use ($departmentId) {
                $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                $program = $subject?->program;
                $ownerDepartmentId = $program?->department_id
                    ?? $merge->facultySubject?->faculty?->department_id;

                return (int) $ownerDepartmentId === $departmentId;
            })
            ->map(function ($merge) {
                $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                return $subject?->program?->name ?? $merge->facultySubject?->program;
            })
            ->filter();

        $candidates = $programValues
            ->merge($mergedValues)
            ->merge($catalogPrograms->pluck('name'))
            ->merge($catalogPrograms->pluck('code'))
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values();

        $selectedLabel = $this->studentProgramLabel($selectedProgram, $catalogPrograms);
        $selectedKey = mb_strtolower(preg_replace('/\s+/u', ' ', trim($selectedLabel)));

        $aliases = $candidates
            ->filter(function ($candidate) use ($catalogPrograms, $selectedKey) {
                $label = $this->studentProgramLabel((string) $candidate, $catalogPrograms);
                $key = mb_strtolower(preg_replace('/\s+/u', ' ', trim($label)));
                return $key === $selectedKey;
            })
            ->values()
            ->all();

        if (!in_array(trim($selectedProgram), $aliases, true)) {
            $aliases[] = trim($selectedProgram);
        }

        return array_values(array_unique($aliases));
    }

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

            $mergedPrograms = ScheduleMerge::with([
                'subject.program',
                'facultySubject.subjectCatalog.program',
            ])
                ->where('status', 'approved')
                ->whereHas('schedule', fn ($query) => $query->where('academic_term_id', $activeTerm->id))
                ->where(function ($query) use ($request) {
                    $query->whereHas('subject.program', fn ($programQuery) => $programQuery->where('department_id', $request->department_id))
                        ->orWhereHas('facultySubject.subjectCatalog.program', fn ($programQuery) => $programQuery->where('department_id', $request->department_id));
                })
                ->get()
                ->map(function ($merge) {
                    $program = ($merge->subject ?? $merge->facultySubject?->subjectCatalog)?->program;
                    return $program?->name ?? $merge->facultySubject?->program;
                })
                ->filter()
                ->values();

            $catalogPrograms = Program::query()
                ->where('department_id', $request->department_id)
                ->get(['name', 'code']);
            $programGroups = [];
            foreach ($programs->merge($mergedPrograms)->filter()->values() as $program) {
                $program = trim((string) $program);
                if ($program === '') {
                    continue;
                }

                $label = $this->studentProgramLabel($program, $catalogPrograms);
                $key = mb_strtolower(preg_replace('/\s+/u', ' ', trim($label)));
                $programGroups[$key] ??= [
                    'value' => $program,
                    'label' => $label,
                ];
            }

            $programs = collect($programGroups)
                ->sortBy(fn ($option) => mb_strtolower($option['label']))
                ->values()
                ->pluck('value');
            $programLabels = [];
            foreach ($programGroups as $option) {
                $programLabels[$option['value']] = $option['label'];
            }


            return response()->json([
                'success' => true,

                'programs' => $programs,
                'program_labels' => $programLabels,

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

            $programAliases = $this->studentProgramAliases(
                (int) $request->department_id,
                (int) $activeTerm->id,
                (string) $request->program
            );
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
                ->whereIn('program', $programAliases)

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

            $mergedYearLevels = ScheduleMerge::with([
                'subject.program',
                'facultySubject.subjectCatalog.program',
                'facultySubject.faculty',
            ])
                ->where('status', 'approved')
                ->whereHas('schedule', fn ($query) => $query->where('academic_term_id', $activeTerm->id))
                ->where(function ($mergeQuery) use ($request, $programAliases) {
                    $mergeQuery->whereHas('subject', function ($query) use ($request, $programAliases) {
                    $query->whereNotNull('year_level')
                        ->whereRaw("TRIM(year_level) <> ''")
                        ->whereHas('program', function ($programQuery) use ($request, $programAliases) {
                            $programQuery->where('department_id', $request->department_id)
                                ->whereIn('name', $programAliases);
                        });
                    })->orWhereHas('facultySubject.subjectCatalog', function ($query) use ($request, $programAliases) {
                        $query->whereNotNull('year_level')
                        ->whereRaw("TRIM(year_level) <> ''")
                        ->whereHas('program', function ($programQuery) use ($request, $programAliases) {
                                $programQuery->where('department_id', $request->department_id)
                                    ->whereIn('name', $programAliases);
                            });
                    });
                })
                ->get()
                ->map(fn ($merge) => trim((string) (($merge->subject ?? $merge->facultySubject?->subjectCatalog)?->year_level ?? $merge->facultySubject?->year_level)))
                ->filter()
                ->values();

            $yearLevels = $yearLevels->merge($mergedYearLevels)->unique()->sort()->values();


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
        RoomSwapRequest::expireElapsedTemporarySwaps();

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

            $programAliases = $this->studentProgramAliases(
                (int) $request->department_id,
                (int) $activeTerm->id,
                (string) $request->program
            );
            $selectedProgramCode = Program::query()
                ->where('department_id', (int) $request->department_id)
                ->where(function ($query) use ($request) {
                    $query->where('code', trim((string) $request->program))
                        ->orWhere('name', trim((string) $request->program));
                })
                ->value('code');
            if (!$selectedProgramCode && preg_match('/\(([^()]*)\)\s*$/u', trim((string) $request->program), $programCodeMatch) === 1) {
                $selectedProgramCode = trim($programCodeMatch[1]);
            }
            $selectedProgramCode = trim((string) ($selectedProgramCode ?: $request->program));

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
                ->where(function ($classQuery) use ($request, $programAliases) {
                    $classQuery->where(function ($primary) use ($request, $programAliases) {
                        $primary->where('department_id', $request->department_id)
                            ->whereIn('program', $programAliases)
                            ->where('year_level', $request->year_level);
                    })->orWhereHas('merges', function ($mergeQuery) use ($request, $programAliases) {
                        $mergeQuery->where('status', 'approved')
                            ->where(function ($catalogQuery) use ($request, $programAliases) {
                                $catalogQuery->whereHas('subject', function ($subjectQuery) use ($request, $programAliases) {
                                    $subjectQuery->where('year_level', $request->year_level)
                                        ->whereHas('program', function ($programQuery) use ($request, $programAliases) {
                                            $programQuery->where('department_id', $request->department_id)
                                                ->whereIn('name', $programAliases);
                                        });
                                })->orWhereHas('facultySubject.subjectCatalog', function ($subjectQuery) use ($request, $programAliases) {
                                    $subjectQuery->where('year_level', $request->year_level)
                                        ->whereHas('program', function ($programQuery) use ($request, $programAliases) {
                                            $programQuery->where('department_id', $request->department_id)
                                                ->whereIn('name', $programAliases);
                                        });
                                })->orWhereHas('facultySubject', function ($facultySubjectQuery) use ($request, $programAliases) {
                                    $facultySubjectQuery->whereIn('program', $programAliases)
                                        ->where('year_level', $request->year_level)
                                        ->whereHas('faculty', function ($facultyQuery) use ($request) {
                                            $facultyQuery->where('department_id', $request->department_id);
                                        });
                                });
                            });
                    });
                })

                /*
                 * Room information
                 */
                ->with([
                    'room',
                    'merges' => function ($mergeQuery) {
                        $mergeQuery->where('status', 'approved')->with([
                            'subject.program.department',
                            'facultySubject.subjectCatalog.program.department',
                            'facultySubject.faculty',
                        ]);
                    },
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

            $roomSwapByScheduleId = [];
            if ($schedules->isNotEmpty()) {
                $now = Carbon::now();
                $approvedTemporarySwaps = RoomSwapRequest::with(['requesterRoom', 'targetRoom'])
                    ->whereRaw('LOWER(TRIM(status)) = ?', ['approved'])
                    ->where('swap_type', 'temporary')
                    ->where(function ($query) use ($schedules) {
                        $query->whereIn('requester_schedule_id', $schedules->pluck('id'))
                            ->orWhereIn('target_schedule_id', $schedules->pluck('id'));
                    })
                    ->whereHas('requesterSchedule', fn ($query) => $query->where('academic_term_id', $activeTerm->id))
                    ->whereHas('targetSchedule', fn ($query) => $query->where('academic_term_id', $activeTerm->id))
                    ->whereDate('swap_date', '>=', Carbon::today()->toDateString())
                    ->get();

                foreach ($approvedTemporarySwaps as $swap) {
                    try {
                        $date = $swap->swap_date?->format('Y-m-d');
                        $rawStart = $swap->getRawOriginal('start_time');
                        $rawEnd = $swap->getRawOriginal('end_time');
                        if (!$date || !$rawStart || !$rawEnd) continue;

                        $start = Carbon::parse($date . ' ' . $rawStart);
                        $end = Carbon::parse($date . ' ' . $rawEnd);
                        if (!$end->greaterThan($now)) continue;

                        $swapInfo = [
                            'swap_type' => 'temporary',
                            'active' => $now->greaterThanOrEqualTo($start),
                            'scheduled' => $now->lessThan($start),
                            'swap_id' => (int) $swap->id,
                            'swap_date' => $date,
                            'start_at' => $start->format('Y-m-d\\TH:i:s'),
                            'end_at' => $end->format('Y-m-d\\TH:i:s'),
                            'start_time' => $start->format('g:i A'),
                            'end_time' => $end->format('g:i A'),
                        ];

                        if ($swap->requester_schedule_id) {
                            $roomSwapByScheduleId[(int) $swap->requester_schedule_id] = $swapInfo + [
                                'original_room_id' => (int) $swap->requester_room_id,
                                'original_room_name' => $swap->requesterRoom?->room_name,
                                'swapped_to_room_id' => (int) $swap->target_room_id,
                                'swapped_to_room_name' => $swap->targetRoom?->room_name,
                            ];
                        }
                        if ($swap->target_schedule_id) {
                            $roomSwapByScheduleId[(int) $swap->target_schedule_id] = $swapInfo + [
                                'original_room_id' => (int) $swap->target_room_id,
                                'original_room_name' => $swap->targetRoom?->room_name,
                                'swapped_to_room_id' => (int) $swap->requester_room_id,
                                'swapped_to_room_name' => $swap->requesterRoom?->room_name,
                            ];
                        }
                    } catch (\Throwable $exception) {
                        continue;
                    }
                }
            }

            $roomIds = $schedules->pluck('room_id')
                ->merge(collect($roomSwapByScheduleId)->pluck('swapped_to_room_id'))
                ->filter()
                ->unique()
                ->values();
            $roomLayouts = collect();
            if ($roomIds->isNotEmpty()) {
                $roomSlots = RoomScheduleTimeSlot::query()
                    ->whereIn('room_id', $roomIds)
                    ->where('academic_term_id', $activeTerm->id)
                    ->where('is_active', true)
                    ->orderBy('row_position')
                    ->orderBy('start_time')
                    ->get(['room_id', 'day', 'start_time', 'end_time', 'row_position'])
                    ->groupBy('room_id');
                $roomMerges = ScheduleCellMerge::query()
                    ->whereIn('room_id', $roomIds)
                    ->where('academic_term_id', $activeTerm->id)
                    ->orderBy('id')
                    ->get(['room_id', 'axis', 'cells'])
                    ->groupBy('room_id');

                $roomLayouts = $roomIds->mapWithKeys(function ($roomId) use ($roomSlots, $roomMerges) {
                    return [(int) $roomId => [
                        'time_slots' => ($roomSlots->get($roomId) ?? collect())->map(function ($slot) {
                            return [
                                'day' => strtoupper(trim((string) $slot->day)),
                                'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                                'end_time' => Carbon::parse($slot->end_time)->format('H:i'),
                                'row_position' => $slot->row_position !== null ? (int) $slot->row_position : null,
                            ];
                        })->values(),
                        'manual_merges' => ($roomMerges->get($roomId) ?? collect())->map(fn ($merge) => [
                            'axis' => $merge->axis,
                            'cells' => $merge->cells,
                        ])->values(),
                    ]];
                });
            }

            /*
             * Add the room's saved timetable layout and a simple room value for JavaScript.
             */
            $schedules = $schedules->map(
                function ($schedule) use ($request, $roomLayouts, $programAliases, $selectedProgramCode, $roomSwapByScheduleId) {
                    $isPrimaryCourse = (int) $schedule->department_id === (int) $request->department_id
                        && in_array(trim((string) $schedule->program), $programAliases, true)
                        && trim((string) $schedule->year_level) === trim((string) $request->year_level);

                    $matchedMerge = $isPrimaryCourse ? null : $schedule->merges->first(function ($merge) use ($request, $programAliases) {
                        $catalogSubject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                        $catalogProgram = $catalogSubject?->program;
                        $programName = $catalogProgram?->name ?? $merge->facultySubject?->program;
                        $departmentId = $catalogProgram?->department_id ?? $merge->facultySubject?->faculty?->department_id;
                        $yearLevel = $catalogSubject?->year_level ?? $merge->facultySubject?->year_level;

                        return ($catalogSubject || $merge->facultySubject)
                            && (int) $departmentId === (int) $request->department_id
                            && in_array(trim((string) $programName), $programAliases, true)
                            && trim((string) $yearLevel) === trim((string) $request->year_level);
                    });

                    $displaySubject = $matchedMerge
                        ? ($matchedMerge->subject ?? $matchedMerge->facultySubject?->subjectCatalog)
                        : null;

                    if ($matchedMerge) {
                        $facultySubject = $matchedMerge->facultySubject;
                        $schedule->course_code = $displaySubject?->course_code ?? $facultySubject?->course_code ?? $schedule->course_code;
                        $schedule->subject = $displaySubject?->course_title ?? $facultySubject?->subject ?? $schedule->subject;
                        $schedule->subject_type = Subject::typeFromCourseCode((string) $schedule->course_code);
                        $schedule->program = $displaySubject?->program?->name ?? $facultySubject?->program ?? $schedule->program;
                        $schedule->major = $displaySubject?->major ?? $facultySubject?->major ?? $displaySubject?->program?->major ?? $schedule->major;
                        $schedule->year_level = $displaySubject?->year_level ?? $facultySubject?->year_level ?? $schedule->year_level;
                    }

                    $schedule->setAttribute(
                        'program_code',
                        $displaySubject?->program?->code ?? $selectedProgramCode
                    );

                    // Older databases can still expose a legacy `room` text
                    // column, while current databases use the Room relation.
                    // Read the relation and legacy value separately so a
                    // string is never treated as a Room model.
                    $roomRelation = $schedule->relationLoaded('room')
                        ? $schedule->getRelation('room')
                        : null;
                    $legacyRoomValue = $schedule->getAttribute('room');

                    if (is_object($roomRelation)) {
                        $roomName = $roomRelation->room_name
                            ?? $roomRelation->room
                            ?? $roomRelation->name
                            ?? $roomRelation->code
                            ?? $schedule->room_id;
                    } elseif (is_string($legacyRoomValue) || is_numeric($legacyRoomValue)) {
                        $roomName = trim((string) $legacyRoomValue);
                    } else {
                        $roomName = '';
                    }

                    $roomSwap = $roomSwapByScheduleId[(int) $schedule->id] ?? null;
                    if ($roomSwap) {
                        $roomName = $roomSwap['swapped_to_room_name'] ?: $roomName;
                        $schedule->setAttribute('room_id', $roomSwap['swapped_to_room_id'] ?: $schedule->room_id);
                    }

                    $schedule->unsetRelation('room');
                    $schedule->setAttribute('room', $roomName);
                    $schedule->setAttribute('room_layout', $roomLayouts->get((int) $schedule->room_id));
                    $schedule->setAttribute('room_swap', $roomSwap);


                    return $schedule;
                }
            )->values();


            return response()->json([
                'success' => true,

                'schedules' =>
                    $schedules,

                'room_layouts' => $roomLayouts,

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
