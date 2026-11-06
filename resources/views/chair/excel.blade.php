<!DOCTYPE html>
<html lang="en">

<head>
  

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Class Program - {{ $room->room_name }}
    </title>

    <link rel="stylesheet" href="{{ asset('css/chair-excel.css') }}?v=20261029-same-course-conflict-v1">
    <link rel="stylesheet" href="{{ asset('css/schedule-sheet-controls.css') }}?v=20261023-fixed-cells-v2">
    <script src="{{ asset('js/schedule-sheet-controls.js') }}?v=20261023-fit-controls-v1" defer></script>

    <style>
        .excel-action-confirm[hidden] { display:none !important; }
        .excel-action-confirm { position:fixed; inset:0; z-index:1000200; display:grid; place-items:center; padding:20px; background:rgba(15,23,42,.48); backdrop-filter:blur(4px); }
        .excel-action-confirm-card { width:min(430px,100%); padding:27px; border:1px solid #e7e8e5; border-radius:18px; background:#fff; color:#26352c; box-shadow:0 28px 80px rgba(15,23,42,.24); text-align:center; }
        .excel-action-confirm-icon { display:grid; width:54px; height:54px; place-items:center; margin:0 auto 14px; border:1px solid #f1d6d1; border-radius:17px; background:#fff3f1; color:#ad453d; font-size:23px; }
        .excel-action-confirm-card h2 { margin:0 0 9px; color:#253b2d; font-size:21px; }
        .excel-action-confirm-card p { margin:0; color:#67756b; font-size:13px; line-height:1.55; }
        .excel-action-confirm-actions { display:flex; justify-content:center; gap:10px; flex-wrap:wrap; margin-top:22px; }
        .excel-action-confirm-actions button { min-width:112px; min-height:42px; padding:0 16px; border:1px solid #dce4dc; border-radius:9px; background:#f6f8f5; color:#395544; font:750 12px/1 Arial,sans-serif; cursor:pointer; }
        .excel-action-confirm-actions .confirm { border-color:#a63f38; background:#b74840; color:#fff; }
        .excel-sheet-export-button { display:inline-flex; align-items:center; justify-content:center; gap:7px; min-height:38px; padding:0 12px; border:1px solid #cbd7cf; border-radius:8px; background:#fff; color:#365444; font:750 12px/1.2 var(--wr-body, Arial,sans-serif); cursor:pointer; }
        .excel-sheet-export-button:hover { border-color:#94b29a; background:#f4f8f3; }
        .excel-sheet-export-button.download { border-color:#24543e; background:#24543e; color:#fff; }
        .excel-sheet-export-button.download:hover { background:#173b33; }
        .schedule-export-time { display:block; margin:3px 0 5px; color:#334d3d; font-size:11px; font-weight:850; line-height:1.3; }
        #scheduleTable td.time-cell.row-delete-selectable { position:relative; padding-left:32px; }
        .schedule-row-check-label { position:absolute; top:50%; left:7px; z-index:8; display:grid; width:18px; height:18px; place-items:center; transform:translateY(-50%); }
        .schedule-row-checkbox { width:16px; height:16px; margin:0; accent-color:#b74840; cursor:pointer; }
        .schedule-row-checkbox:disabled { cursor:not-allowed; opacity:.45; }
        #scheduleTable tr.schedule-row-delete-selected > td { outline:2px solid rgba(183,72,64,.35); outline-offset:-2px; background-color:#fff7f5; }
        .schedule-row-delete-confirm[hidden] { display:none !important; }
        .schedule-row-delete-confirm { position:fixed; inset:0; z-index:1000201; display:grid; place-items:center; padding:20px; background:rgba(15,23,42,.48); backdrop-filter:blur(4px); }
        .schedule-row-delete-confirm-card { width:min(430px,100%); padding:27px; border:1px solid #e7e8e5; border-radius:18px; background:#fff; color:#26352c; box-shadow:0 28px 80px rgba(15,23,42,.24); }
        .schedule-row-delete-confirm-card h2 { margin:0 0 9px; color:#253b2d; font-size:21px; }
        .schedule-row-delete-confirm-card p { margin:0; color:#67756b; font-size:13px; line-height:1.55; }
        .schedule-row-delete-confirm-actions { display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap; margin-top:22px; }
        .schedule-row-delete-confirm-actions button { min-width:112px; min-height:42px; padding:0 16px; border:1px solid #dce4dc; border-radius:9px; background:#f6f8f5; color:#395544; font:750 12px/1 Arial,sans-serif; cursor:pointer; }
        .schedule-row-delete-confirm-actions .confirm { border-color:#a63f38; background:#b74840; color:#fff; }
        .excel-mode-switch { display:flex; flex-wrap:wrap; gap:8px; margin:0 0 16px; padding:6px; border:1px solid #dfe7dd; border-radius:12px; background:#fff; }
        .excel-mode-link { display:inline-flex; min-height:40px; align-items:center; gap:8px; padding:0 14px; border-radius:8px; color:#53675a; font:800 13px/1 Arial,sans-serif; text-decoration:none; }
        .excel-mode-link.active { background:#24543e; color:#fff; }
        .cross-room-access-note { margin:0 0 16px; padding:13px 15px; border:1px solid #dce6dc; border-radius:11px; background:#f6f8f3; color:#465c4d; font:650 13px/1.5 Arial,sans-serif; }
        .cross-room-access-note.read-only { border-color:#ead9ad; background:#fff8e8; color:#765b27; }
        .cross-room-read-only #addRowButton,.cross-room-read-only #toggleDeleteRowsButton,.cross-room-read-only #deleteSelectedRowsButton,.cross-room-read-only .editor-actions .btn-green { display:none !important; }
        .published-schedule-readonly .excel-sheet-toolbar-left,
        .published-schedule-readonly .excel-sheet-merge-controls,
        .published-schedule-readonly .table-footer,
        .published-schedule-readonly .editor-actions,
        .published-schedule-readonly .schedule-entry-overlay,
        .published-schedule-readonly .merge-class-overlay,
        .published-schedule-readonly .same-course-conflict-overlay,
        .published-schedule-readonly .excel-action-confirm,
        .published-schedule-readonly .schedule-row-delete-confirm { display:none !important; }
        .published-schedule-readonly .excel-sheet-toolbar { justify-content:flex-end; }
        .published-schedule-readonly .excel-sheet-toolbar-actions > :not(#printScheduleSpreadsheet):not(#downloadScheduleSpreadsheet) { display:none !important; }
        .published-schedule-readonly #scheduleTable { pointer-events:none; user-select:text; }
        .published-schedule-readonly #scheduleForm { pointer-events:none; }
        .published-schedule-readonly #scheduleForm .excel-sheet-toolbar-actions { pointer-events:auto; }
        .published-schedule-readonly .schedule-block-layer,
        .published-schedule-readonly .schedule-resizable-block,
        .published-schedule-readonly .schedule-resize-handle { pointer-events:none !important; cursor:default !important; }
        .published-schedule-readonly .schedule-resize-handle { display:none !important; }
        .published-override-panel { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:9px; margin:0 0 14px; padding:14px; border:1px solid #efd0cd; border-radius:13px; background:#fff8f7; }
        .published-override-panel h2,.published-override-panel p { grid-column:1/-1; margin:0; }
        .published-override-panel h2 { color:#87332f; font:850 15px/1.3 Arial,sans-serif; }
        .published-override-panel p { margin-top:-5px; color:#765553; font:500 12px/1.45 Arial,sans-serif; }
        .published-override-button { display:grid; width:100%; gap:4px; padding:10px 12px; border:1px solid #efc4c1; border-left:4px solid #c63f3a; border-radius:9px; background:#fff; color:#63302d; font:500 12px/1.4 Arial,sans-serif; text-align:left; cursor:pointer; }
        .published-override-button:hover,.published-override-button[aria-pressed="true"] { border-color:#c63f3a; background:#ffe9e7; box-shadow:0 0 0 2px rgba(198,63,58,.12); }
        .published-override-button strong { color:#b33330; font-weight:850; }
        .published-override-highlight { border-color:#c63f3a !important; border-left:5px solid #c63f3a !important; background:#ffe7e5 !important; box-shadow:inset 0 0 0 1px rgba(198,63,58,.24),0 2px 8px rgba(138,36,32,.12) !important; }
        .published-schedule-readonly #scheduleTable .schedule-cell.published-override-cell-highlight { background:#ffe7e5 !important; box-shadow:inset 0 0 0 2px rgba(198,63,58,.38) !important; }
        #scheduleTable .room-overview-focused-cell { position:relative; z-index:4; outline:3px solid #c8872c !important; outline-offset:-3px; background:#fff8e8 !important; }
        @media(max-width:640px) { .excel-sheet-export-button { flex:1 1 auto; min-width:110px; } }

    </style>


</head>


<body class="chair-excel-clay {{ $isCrossRoomReadOnly ? 'cross-room-read-only' : '' }} {{ $isCrossScheduling ? 'cross-scheduling-editor' : '' }} {{ $isPublishedScheduleView ? 'published-schedule-readonly' : '' }}">

@php
    $programCodeLabel = static function ($program) {
        $program = trim((string) $program);
        if (preg_match('/\(([^()]*)\)\s*$/u', $program, $matches) === 1) {
            return trim($matches[1]);
        }

        return $program;
    };
    $mergeCourseInfoForSchedule = static function ($schedule) use ($programCodeLabel) {
        return collect($schedule?->merges ?? [])
            ->where('status', 'approved')
            ->reject(fn ($merge) => ($merge->share_type ?? 'merge') === 'same_faculty')
            ->map(function ($merge) {
                $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                if (!$subject) return null;
                return [
                    'merge_id' => (int) $merge->id,
                    'department_id' => (int) $merge->department_id,
                    'share_type' => $merge->share_type ?? 'merge',
                    'id' => (int) $subject->id,
                    'course_code' => $subject->course_code ?? $merge->facultySubject?->course_code,
                    'course_title' => $subject->course_title ?? $merge->facultySubject?->subject,
                    'program' => $subject->program?->name ?? $merge->facultySubject?->program,
                    'program_code' => $subject->program?->code ?? $programCodeLabel($subject->program?->name ?? $merge->facultySubject?->program),
                    'major' => $subject->program?->major ?? $merge->facultySubject?->major,
                    'year_level' => $subject->year_level ?? $merge->facultySubject?->year_level,
                    'subject_type' => \App\Models\Subject::typeFromCourseCode((string) ($subject->course_code ?? $merge->facultySubject?->course_code ?? '')),
                ];
            })
            ->filter()
            ->values();
    };
@endphp

<div class="page">


    <!-- =========================================================
         BACK
    ========================================================== -->

    <div class="top-bar">

        <a href="{{ $isPublishedScheduleView ? (auth()->user()->role === 'admin' ? route('admin.schedules') : route('faculty.schedules')) : ($isCrossScheduling ? route('chair.modifyschedule', ['term_id' => $activeTerm->id]) : route('chair.setschedule')) }}" class="back-link">
            ← Back to {{ $isPublishedScheduleView ? 'All Schedules' : ($isCrossScheduling ? 'Cross-Scheduling' : 'Schedule Editor') }}
        </a>

    </div>

    @unless($isPublishedScheduleView)
    <nav class="excel-mode-switch" aria-label="Schedule editor mode">
        <a class="excel-mode-link {{ !$isCrossScheduling ? 'active' : '' }}" href="{{ route('chair.setschedule') }}"><i class="fa-solid fa-building"></i> Schedule editor</a>
        <a class="excel-mode-link {{ $isCrossScheduling ? 'active' : '' }}" href="{{ route('chair.modifyschedule', ['term_id' => $activeTerm->id]) }}"><i class="fa-solid fa-arrow-right-arrow-left"></i> Cross-scheduling</a>
        <a class="excel-mode-link" href="{{ route('chair.roomavailability', ['term_id' => $activeTerm->id]) }}"><i class="fa-solid fa-table-cells-large"></i> All-room availability</a>
    </nav>
    @endunless


    <!-- =========================================================
         MESSAGES
    ========================================================== -->

    @if(session('success'))

        <div class="message message-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="message message-error">
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="message message-error">

            <strong>
                Please fix the following:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- =========================================================
         HEADER
    ========================================================== -->

    <header class="program-header">
        <div class="program-header-main">
            <div class="program-heading">
                <span class="program-heading-icon" aria-hidden="true">
                    <i class="fa-solid fa-calendar-days"></i>
                </span>
                <div class="program-heading-copy">
                    <div class="program-eyebrow"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> {{ $isPublishedScheduleView ? 'PUBLISHED ROOM SCHEDULE' : ($isCrossScheduling ? 'CROSS-COLLEGE WORKSPACE' : 'COLLEGE WORKSPACE') }}</div>
                    <h1 class="program-title">{{ $isPublishedScheduleView ? 'Room Schedule' : ($isCrossScheduling ? 'Cross-Scheduling Editor' : 'Class Program') }}</h1>
                    <p class="program-subtitle">{{ $isPublishedScheduleView ? 'Published timetable for ' . $room->room_name . '.' : ($isCrossScheduling ? 'Review ' . $room->department->name . ' · ' . $room->room_name . ' in the shared schedule editor.' : 'Set and review the timetable for ' . $room->room_name . '.') }}</p>
                </div>
            </div>

            <div class="program-active-term" aria-label="Active academic term">
                <span>ACTIVE TERM</span>
                <strong>{{ $activeTerm->academic_year ?? ($academicYear ?? '') }} <span aria-hidden="true">•</span> {{ $activeTerm->semester ?? ($semester ?? '') }}</strong>
            </div>
        </div>

        <div class="program-meta" aria-label="Class program details">
            <div class="meta-item">
                <i class="fa-solid fa-building-columns" aria-hidden="true"></i>
                <span><small>College</small><strong>{{ $room->department->name ?? 'N/A' }}</strong></span>
            </div>
            <div class="meta-item">
                <i class="fa-solid fa-building" aria-hidden="true"></i>
                <span><small>Building</small><strong>{{ $room->building->name ?? $room->building ?? 'N/A' }}</strong></span>
            </div>
            <div class="meta-item">
                <i class="fa-solid fa-door-open" aria-hidden="true"></i>
                <span><small>Room</small><strong>{{ $room->room_name }}</strong></span>
            </div>
            <div class="meta-item">
                <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
                <span><small>{{ $isPublishedScheduleView ? 'Schedule owner' : 'Scheduling college' }}</small><strong>{{ $isPublishedScheduleView ? ($room->department->name ?? 'N/A') : (auth()->user()->department->name ?? 'N/A') }}</strong></span>
            </div>
        </div>
    </header>

    @if($isCrossScheduling)
        <div class="cross-room-access-note {{ $isCrossRoomReadOnly ? 'read-only' : '' }}" role="status">
            @if($isCrossRoomReadOnly)
                <strong>View only.</strong> {{ $room->department->name }} has not enabled cross-college scheduling for this room. Existing classes are protected.
            @else
                <strong>Shared room.</strong> You can add classes to empty cells. Existing classes remain protected and can only be changed by {{ $room->department->name }}.
            @endif
        </div>
    @endif

    @if($isPublishedScheduleView && ($publishedScheduleOverrides ?? collect())->isNotEmpty())
        <section class="published-override-panel" aria-label="Administrator schedule overrides">
            <h2>Administrator overrides</h2>
            <p>Select an override to highlight only classes in this room, on the matching day, whose times overlap the override. Select it again to clear.</p>
            @foreach($publishedScheduleOverrides as $override)
                @php
                    $overrideRoomId = $override->room_id ?: $override->schedule?->room_id ?: $room->id;
                    $overrideRoomName = $override->room?->room_name ?: ($override->schedule?->room?->room_name ?: $room->room_name);
                    $overrideDateLabel = $override->override_date?->format('M j, Y') ?? 'Date unavailable';
                    $overrideStartLabel = $override->start_time ? \Carbon\Carbon::parse($override->start_time)->format('g:i A') : '';
                    $overrideEndLabel = $override->end_time ? \Carbon\Carbon::parse($override->end_time)->format('g:i A') : '';
                @endphp
                <button type="button" class="published-override-button" aria-pressed="false"
                    data-override-id="{{ $override->id }}"
                    data-override-room-id="{{ $overrideRoomId }}"
                    data-override-day="{{ strtoupper($override->override_date?->format('D') ?? '') }}"
                    data-override-start="{{ $override->start_time ? \Carbon\Carbon::parse($override->start_time)->format('H:i') : '' }}"
                    data-override-end="{{ $override->end_time ? \Carbon\Carbon::parse($override->end_time)->format('H:i') : '' }}">
                    <strong>Admin override · {{ $overrideDateLabel }} · {{ $overrideStartLabel }} – {{ $overrideEndLabel }}</strong>
                    <span>Room: {{ $overrideRoomName }}</span>
                    <span>Room occupant: {{ $override->occupied_by ?: 'Students and Faculties' }}</span>
                    <span>Purpose: {{ $override->reason ?: 'Schedule override' }}</span>
                    @if($override->details)<span>{{ $override->details }}</span>@endif
                </button>
            @endforeach
        </section>
    @endif


    <!-- =========================================================
         FACULTY SUBJECT DATA
    ========================================================== -->

    @php

        $facultySubjectsData =
            $facultySubjects
                ->groupBy(fn ($item) => implode('|', [
                    (int) $item->faculty_id,
                    strtoupper(trim((string) $item->course_code)),
                    mb_strtolower(trim((string) $item->subject)),
                ]))
                ->map(function ($group) {
                    $item = $group->first();
                    $cohorts = $item->sharedCohorts
                        ->concat($group->skip(1)->map(fn ($assignment) => $assignment->subjectCatalog))
                        ->filter()
                        ->unique('id')
                        ->values();

                   return [
    'id' => $item->id,
    'assignment_ids' => $group->pluck('id')->values()->all(),
    'course_code' => $item->course_code,
    'subject' => $item->subject,
    'year_level' => $item->year_level,
    'major' => $item->major,
    'program' => $item->program,
    'shared_cohorts' => $cohorts->map(fn ($cohort) => [
        'program' => $cohort->program?->name,
        'program_code' => $cohort->program?->code,
        'major' => $cohort->program?->major,
        'year_level' => $cohort->year_level,
        'semester' => $cohort->semester,
    ])->values()->all(),
    'subject_type' => $item->subject_type ?: (preg_match('/\.1\s*$/', trim($item->course_code)) ? 'Lab' : 'Lec'),

    'faculty_id' => $item->faculty
        ? $item->faculty->id
        : null,

    'instructor' => $item->faculty
        ? $item->faculty->name
        : '',
];
                })
                ->values()
                ->toArray();

    @endphp


    <!-- =========================================================
         FORM
    ========================================================== -->

    <form
        method="POST"
        action="{{ route('chair.save.schedule') }}"
        id="scheduleForm"
        @if($isPublishedScheduleView) onsubmit="return false;" @endif
    >

        @csrf

        <input type="hidden" name="editor_mode" value="{{ $isCrossScheduling ? 'cross' : 'department' }}">


        <input
            type="hidden"
            name="room_id"
            value="{{ $room->id }}"
        >


        <!-- =====================================================
             EDITOR
        ====================================================== -->

        <div class="schedule-entry-overlay" id="scheduleEntryPopup" hidden>
        <section class="editor-panel schedule-entry-card" role="dialog" aria-modal="false" aria-labelledby="scheduleEntryTitle">

            <div class="schedule-entry-heading">
                <div>
                    <div class="editor-title" id="scheduleEntryTitle">Schedule Entry</div>
                    <div class="editor-subtitle">Enter a course code, review the loaded details, then confirm to place it in the selected cell.</div>
                    <div class="schedule-entry-target" id="scheduleEntryTarget">Selected timetable cell</div>
                </div>
                <button type="button" class="schedule-entry-close" id="closeScheduleEntryPopup" aria-label="Close schedule entry">×</button>
            </div>


            <div class="form-grid">


                <!-- COURSE CODE -->

                <div class="form-group">

                    <label>
                        Course Code
                    </label>

                    <input
                        type="text"
                        id="course_code"
                        list="courseCodeList"
                        placeholder="Example: IT 111"
                        autocomplete="off"
                    >


                    <datalist id="courseCodeList">

                        @foreach(
                            $facultySubjects
                                ->unique('course_code')
                            as $facultySubject
                        )

                            <option
                                value="{{ $facultySubject->course_code }}"
                            ></option>

                        @endforeach

                    </datalist>

                    <select id="subjectMatchSelector" hidden style="width:100%;margin-top:10px;padding:11px;border:1px solid #b7c8d9;border-radius:8px;background:#fff;color:#20364b;">
                        <option value="">Choose the matching year level and instructor</option>
                    </select>

                    <div id="sharedAudiencePicker" class="shared-audience-picker" hidden>
                        <div class="shared-audience-heading">Groups included in this assignment</div>
                        <div id="sharedAudienceChoices" class="shared-audience-choices"></div>
                        <div class="shared-audience-help">These groups were selected in Instructor Setup and will share this scheduled meeting automatically.</div>
                    </div>

                </div>


                <!-- SUBJECT -->

                <div class="form-group">

                    <label>
                        Course Description
                    </label>

                    <input
                        type="text"
                        id="subject"
                        readonly
                        placeholder="Automatic"
                    >

                </div>


                <!-- YEAR LEVEL -->

                <div class="form-group">

                    <label>
                        Year Level
                    </label>

                    <input
                        type="text"
                        id="year_level"
                        readonly
                        placeholder="Automatic"
                    >

                </div>

<!-- SUBJECT TYPE -->
<div class="form-group">
    <label>
        Subject Type
    </label>

    <input
        type="text"
        id="subject_type"
        name="subject_type"
        readonly
        placeholder="Automatic"
    >
</div>
                <!-- FACULTY -->

                <div class="form-group">

                    <label>
                        Instructor
                    </label>

                    <input
                        type="text"
                        id="instructor"
                        readonly
                        placeholder="Automatic"
                    >

                </div>


               <!-- MAJOR -->

<div class="form-group">

    <label>
        Major
    </label>

    <input
        type="text"
        id="major"
        readonly
        placeholder="Automatic"
    >

</div>


<!-- PROGRAM -->
<div class="form-group">
    <label for="program">
        Program
    </label>

    <input
        type="text"
        id="program"
        name="program"
        readonly
        placeholder="Automatic"
    >
</div>

            </div>


            <div
                id="lookupStatus"
                class="lookup-status"
            ></div>

            <div class="schedule-entry-actions">
                <button type="button" class="btn btn-gray" id="cancelScheduleEntryButton">Cancel</button>
                <button type="button" class="btn btn-merge" id="mergeSelectedScheduleButton" hidden>Merge Class</button>
                <button type="button" class="btn btn-green" id="confirmScheduleEntryButton">Confirm Entry</button>
            </div>


        </section>
        </div>

        <div class="same-course-conflict-overlay" id="sameCourseConflictConfirm" hidden>
            <section class="same-course-conflict-card" role="dialog" aria-modal="true" aria-labelledby="sameCourseConflictTitle">
                <div class="same-course-conflict-icon" aria-hidden="true">!</div>
                <h2 id="sameCourseConflictTitle">Instructor schedule conflict</h2>
                <p id="sameCourseConflictMessage"></p>
                <p class="same-course-conflict-note">You may continue only because the course code matches the existing class. This is intended for the same class serving multiple groups.</p>
                <div class="same-course-conflict-actions">
                    <button type="button" id="cancelSameCourseConflict" class="same-course-conflict-cancel">Change assignment</button>
                    <button type="button" id="proceedSameCourseConflict" class="same-course-conflict-proceed">Schedule this same course</button>
                </div>
            </section>
        </div>


        <!-- =====================================================
             TABLE
        ====================================================== -->

        <div class="table-wrapper schedule-sheet" data-schedule-sheet data-fit-schedule-container>

        <div class="excel-sheet-toolbar" role="toolbar" aria-label="Schedule sheet actions">
            <div class="excel-sheet-merge-controls" id="selectedCellMergeControls" role="group" aria-label="Selected cell merge actions" hidden>
                <button type="button" class="schedule-merge-button" id="mergeSelectedCellsButton" disabled>Merge Cells</button>
                <button type="button" class="schedule-merge-button schedule-unmerge-button" id="unmergeSelectedCellsButton" disabled>Unmerge Cells</button>
            </div>
            <div class="excel-sheet-toolbar-actions" aria-label="Sheet and selected cell actions">
                <button type="button" class="excel-sheet-export-button" id="printScheduleSpreadsheet"><i class="fa-solid fa-print" aria-hidden="true"></i> Print</button>
                <button type="button" class="excel-sheet-export-button download" id="downloadScheduleSpreadsheet"><i class="fa-solid fa-file-excel" aria-hidden="true"></i> Download Excel</button>
                @include('components.schedule-sheet-controls')
            <button type="button" class="btn btn-red" id="clearCellButton" data-confirm-remove="Permanently delete the selected published schedule entries from the database? This cannot be undone." hidden>Clear Cell</button>
                <label class="color-group" id="cellColorControl" for="cellColor" hidden><span>Cell color</span><input type="color" id="cellColor" value="#ffffff" title="Set selected cell color"></label>
            </div>
        </div>

        @if($isPublishedScheduleView)
        <div class="instruction schedule-guide" role="note">
            <strong>Published schedule</strong>
            <span>This is the saved room timetable, including its custom time rows and merged cells.</span>
        </div>
        @else
        <div class="instruction schedule-guide" role="note">
            <strong>Quick guide</strong>
            <span><b>1</b> Click a cell to select it.</span>
            <span><b>2</b> Right-click the selected cell to enter a course code.</span>
            <span><b>3</b> Click <strong>Confirm Entry</strong>, then upload or save the schedule below.</span>
            <span><b>4</b> Click a scheduled cell, then drag its top or bottom grip to resize it in 30-minute steps.</span>
            <span class="schedule-guide-muted">Use Merge Class on a scheduled entry to add a same-college subject or request cross-college approval. Locked schedules stay view-only.</span>
        </div>
        @endif

            <table id="scheduleTable" data-fit-schedule>

             <thead>
    <tr>
        <th class="time-header">TIME</th>

        <th data-day="MON">MON</th>
        <th data-day="WED">WED</th>
        <th data-day="FRI">FRI</th>

        <th class="time-header">TIME</th>

        <th data-day="TUE">TUE</th>
        <th data-day="THU">THU</th>
        <th class="time-header" data-side="sat">TIME</th>
        <th data-day="SAT">SAT</th>
    </tr>
</thead>

                <tbody id="scheduleBody">

                    @php

    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR DEPARTMENT
    |--------------------------------------------------------------------------
    */

    $chairDepartmentId = $isPublishedScheduleView
        ? (int) $room->department_id
        : auth()->user()->department_id;

    /*
    |--------------------------------------------------------------------------
    | SAFE COLLECTIONS
    |--------------------------------------------------------------------------
    */

    $schedules = $schedules ?? collect();
    $reservations = $reservations ?? collect();
    $timeSlots = $timeSlots ?? collect();
    $roomTimeSlots = $roomTimeSlots ?? collect();

    /*
    |--------------------------------------------------------------------------
    | RESERVATION TIME HELPER
    |--------------------------------------------------------------------------
    */

    $timeToMinutes = function ($time) {
        $time = trim((string) $time);

        if (!preg_match('/^(\d{1,2}):(\d{2})$/', $time, $matches)) {
            return null;
        }

        $hour = (int) $matches[1];
        $minute = (int) $matches[2];

        if ($hour >= 1 && $hour <= 6) {
            $hour += 12;
        }

        return ($hour * 60) + $minute;
    };

    /*
    |--------------------------------------------------------------------------
    | DEFAULT TIME ROWS
    |--------------------------------------------------------------------------
    |
    | LEFT  = MWF
    | MIDDLE = TTH
    | RIGHT = SAT
    |
    */

    $defaultTimes = [
        '7:00-8:00',
        '8:00-9:00',
        '9:00-10:00',
        '10:00-11:00',
        '11:00-12:00',
        '1:00-2:00',
        '2:00-3:00',
        '3:00-4:00',
        '4:00-5:00',
        '5:00-6:00',
        '6:00-7:00',
    ];

    $hasRoomRowDeletionMarker = $hasRoomRowDeletionMarker ?? false;

    // Older saved rows begin at 8:00 and have position 0. Shift those saved
    // positions down one display row while a room is still on that layout.
    // Once a saved slot starts at 7:00 in position 0, positions already use
    // the new layout and must remain unchanged.
    $detectTimeRowPositionOffset = function ($slots) use ($defaultTimes) {
        foreach ($slots->filter(function ($slot) {
            return $slot->row_position !== null && !empty($slot->start_time);
        })->sortBy('row_position') as $slot) {
            $slotStart = \Carbon\Carbon::parse($slot->start_time)->format('H:i');

            foreach ($defaultTimes as $displayPosition => $range) {
                $start = trim(explode('-', $range, 2)[0]);
                if (!preg_match('/^(\d{1,2}):(\d{2})$/', $start, $matches)) {
                    continue;
                }

                $hour = (int) $matches[1];
                if ($hour >= 1 && $hour <= 6) {
                    $hour += 12;
                }
                $defaultStart = sprintf('%02d:%02d', $hour, (int) $matches[2]);

                if ($slotStart !== $defaultStart) {
                    continue;
                }

                $savedPosition = (int) $slot->row_position;
                if ($savedPosition === $displayPosition) {
                    return 0;
                }
                if ($savedPosition + 1 === $displayPosition) {
                    return 1;
                }
            }
        }

        return null;
    };

    $detectScheduleRowPositionOffset = function ($scheduleRows) use ($defaultTimes) {
        foreach ($scheduleRows->filter(function ($schedule) {
            return $schedule->row_position !== null && !empty($schedule->time);
        })->sortBy('row_position') as $schedule) {
            $startLabel = trim(preg_split('/\s*[-–—]\s*/u', trim((string) $schedule->time), 2)[0] ?? '');
            if (!preg_match('/^(\d{1,2})(?::(\d{2}))?\s*(AM|PM)?$/i', $startLabel, $matches)) {
                continue;
            }

            $hour = (int) $matches[1];
            $minute = (int) ($matches[2] ?? 0);
            $meridiem = strtoupper($matches[3] ?? '');
            if ($meridiem === 'AM' && $hour === 12) $hour = 0;
            elseif ($meridiem === 'PM' && $hour !== 12) $hour += 12;
            elseif ($meridiem === '' && $hour >= 1 && $hour <= 6) $hour += 12;
            $scheduleStart = ($hour * 60) + $minute;

            foreach ($defaultTimes as $displayPosition => $range) {
                $defaultStartLabel = trim(explode('-', $range, 2)[0]);
                if (!preg_match('/^(\d{1,2}):(\d{2})$/', $defaultStartLabel, $defaultMatches)) continue;
                $defaultHour = (int) $defaultMatches[1];
                if ($defaultHour >= 1 && $defaultHour <= 6) $defaultHour += 12;
                $defaultStart = ($defaultHour * 60) + (int) $defaultMatches[2];
                if ($scheduleStart !== $defaultStart) continue;

                $savedPosition = (int) $schedule->row_position;
                if ($savedPosition === $displayPosition) return 0;
                if ($savedPosition + 1 === $displayPosition) return 1;
            }
        }

        return null;
    };

    $timeRowPositionOffset = $hasRoomRowDeletionMarker
        ? 0
        : ($detectTimeRowPositionOffset($roomTimeSlots)
            ?? $detectTimeRowPositionOffset($timeSlots)
            ?? $detectScheduleRowPositionOffset($schedules)
            ?? 0);
    $storedDefaultRowCount = count($defaultTimes) - $timeRowPositionOffset;

    // A legacy 8 AM-starting sheet needs a one-row shift before lunch, but
    // its afternoon rows already line up with the current 1 PM-starting sheet.
    // Resolve standard rows from their clock time first so that the legacy
    // offset is only used for custom rows outside the standard timetable.
    $parseChairClockMinutes = function ($value) {
        $value = trim((string) $value);
        if (!preg_match('/^(\\d{1,2})(?::(\\d{2}))?\\s*(AM|PM)?$/i', $value, $matches)) {
            return null;
        }

        $hour = (int) $matches[1];
        $minute = (int) ($matches[2] ?? 0);
        $meridiem = strtoupper($matches[3] ?? '');
        if ($minute > 59) return null;

        if ($meridiem !== '') {
            if ($hour < 1 || $hour > 12) return null;
            if ($meridiem === 'AM' && $hour === 12) $hour = 0;
            if ($meridiem === 'PM' && $hour !== 12) $hour += 12;
        } else {
            if ($hour > 23) return null;
            if ($hour >= 1 && $hour <= 6) $hour += 12;
        }

        return ($hour * 60) + $minute;
    };

    $defaultTimeRanges = [];
    foreach ($defaultTimes as $displayPosition => $range) {
        $parts = preg_split('/\\s*[-–—]\\s*/u', $range, 2);
        if (count($parts) !== 2) continue;

        $start = $parseChairClockMinutes($parts[0]);
        $end = $parseChairClockMinutes($parts[1]);
        if ($start !== null && $end !== null && $end <= $start) $end += 12 * 60;
        if ($start !== null && $end !== null && $end > $start) {
            $defaultTimeRanges[] = [
                'position' => $displayPosition,
                'start' => $start,
                'end' => $end,
            ];
        }
    }

    $displayPositionForTime = function ($time) use ($parseChairClockMinutes, $defaultTimeRanges) {
        $startLabel = trim(preg_split('/\\s*[-–—]\\s*/u', trim((string) $time), 2)[0] ?? '');
        $start = $parseChairClockMinutes($startLabel);
        if ($start === null) return null;

        foreach ($defaultTimeRanges as $range) {
            if ($start >= $range['start'] && $start < $range['end']) {
                return $range['position'];
            }
        }

        return null;
    };

    $resolveDisplayPosition = function ($savedPosition, $time) use ($displayPositionForTime, $timeRowPositionOffset) {
        $timePosition = $displayPositionForTime($time);
        if ($timePosition !== null) return $timePosition;

        return is_numeric($savedPosition)
            ? (int) $savedPosition + $timeRowPositionOffset
            : null;
    };

    /*
    |--------------------------------------------------------------------------
    | SCHEDULE MAP
    |--------------------------------------------------------------------------
    |
    | KEY = DAY|TIME
    |
    */

    $scheduleMap = [];
    $scheduleRowMap = [];
    $scheduleEntriesByCell = [];

    foreach ($schedules as $row) {

        $key =
            strtoupper(trim($row->day))
            . '|'
            . trim($row->time);

        $scheduleMap[$key] = $row;

        $displayPosition = $resolveDisplayPosition($row->row_position, $row->time ?? '');
        if ($displayPosition !== null) {
            $day = strtoupper(trim((string) $row->day));
            $cellKey = $day . '|' . $displayPosition;
            $scheduleRowMap[$cellKey] ??= $row;
            $scheduleEntriesByCell[$cellKey][] = [
                'schedule_id' => (string) $row->id,
                'day' => $day,
                'time' => trim((string) $row->time),
                'row_position' => $displayPosition,
                'course' => trim((string) $row->course_code),
                'subject' => trim((string) $row->subject),
                'year' => trim((string) ($row->year_level ?? '')),
                'major' => trim((string) ($row->major ?? '')),
                'program' => trim((string) ($row->program ?? '')),
                'instructor' => trim((string) ($row->instructor ?? '')),
                'faculty_id' => (string) ($row->faculty_id ?? ''),
                'faculty_subject_id' => (string) ($row->faculty_subject_id ?? ''),
                'shared_offering_ids' => $row->merges
                    ->where('status', 'approved')
                    ->where('share_type', 'same_faculty')
                    ->pluck('subject_id')
                    ->filter()
                    ->map(fn ($id) => (string) $id)
                    ->values()
                    ->all(),
                'department_id' => (string) ($row->department_id ?? ''),
                'color' => (string) ($row->color ?? '#ffffff'),
                'subject_type' => trim((string) ($row->subject_type ?? '')),
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LEFT / RIGHT TIME SLOTS
    |--------------------------------------------------------------------------
    |
    | Both sides start with the same default times.
    | Existing custom times are also added to the
    | appropriate side.
    |
    */

   $leftTimes = collect($hasRoomRowDeletionMarker ? [] : $defaultTimes);
$rightTimes = collect($hasRoomRowDeletionMarker ? [] : $defaultTimes);
$saturdayTimes = collect($hasRoomRowDeletionMarker ? [] : $defaultTimes);

/*
|--------------------------------------------------------------------------
| RESTORE THE ROOM'S HOURLY ROW LABELS
|--------------------------------------------------------------------------
|
| Schedule time can now be a half-hour range (for example 8:00-9:30).
| The room time-slot records keep the underlying hourly row labels intact.
|
*/

$roomSlotPositions = [];

foreach ($roomTimeSlots as $slot) {
    $position = $resolveDisplayPosition(
        $slot->row_position,
        \Carbon\Carbon::parse($slot->start_time)->format('H:i')
    );
    if ($position === null) {
        continue;
    }

    $day = strtoupper(trim((string) $slot->day));
    $slotTime = \Carbon\Carbon::parse($slot->start_time)->format('g:i')
        . '-'
        . \Carbon\Carbon::parse($slot->end_time)->format('g:i');

    // Keep the published room's standard rows aligned with the timetable's
    // fixed 7 AM–7 PM layout. Older saved row labels may contain noon or
    // duplicate afternoon slots; custom rows after the standard range remain intact.
    if (!$hasRoomRowDeletionMarker && isset($defaultTimes[$position])) {
        $slotTime = $defaultTimes[$position];
    }

    $roomSlotPositions[$day . '|' . $position] = true;

    if (in_array($day, ['MON', 'WED', 'FRI'])) {
        $leftTimes->put($position, $slotTime);
    } elseif (in_array($day, ['TUE', 'THU'])) {
        $rightTimes->put($position, $slotTime);
    } elseif ($day === 'SAT') {
        $saturdayTimes->put($position, $slotTime);
    }
}

// Use the term's official slot as a fallback for rooms without a saved copy.
$fallbackTimeSlots = $hasRoomRowDeletionMarker ? collect() : $timeSlots;
foreach ($fallbackTimeSlots as $slot) {
    $position = $resolveDisplayPosition(
        $slot->row_position,
        \Carbon\Carbon::parse($slot->start_time)->format('H:i')
    );
    if ($position === null) {
        continue;
    }

    $day = strtoupper(trim((string) $slot->day));
    if (isset($roomSlotPositions[$day . '|' . $position])) {
        continue;
    }

    $slotTime = \Carbon\Carbon::parse($slot->start_time)->format('g:i')
        . '-'
        . \Carbon\Carbon::parse($slot->end_time)->format('g:i');

    if (!$hasRoomRowDeletionMarker && isset($defaultTimes[$position])) {
        $slotTime = $defaultTimes[$position];
    }

    if (in_array($day, ['MON', 'WED', 'FRI'])) {
        $leftTimes->put($position, $slotTime);
    } elseif (in_array($day, ['TUE', 'THU'])) {
        $rightTimes->put($position, $slotTime);
    } elseif ($day === 'SAT') {
        $saturdayTimes->put($position, $slotTime);
    }
}

// Legacy/custom rows may predate room-specific time-slot records.
foreach ($schedules as $row) {
    $position = $resolveDisplayPosition($row->row_position, $row->time ?? '');
    if ($position === null) {
        continue;
    }

    $storedPosition = (int) $row->row_position;
    $day = strtoupper(trim((string) $row->day));

    if ($storedPosition < $storedDefaultRowCount || isset($roomSlotPositions[$day . '|' . $position])) {
        continue;
    }

    if (in_array($day, ['MON', 'WED', 'FRI'])) {
        $leftTimes->put($position, trim((string) $row->time));
    } elseif (in_array($day, ['TUE', 'THU'])) {
        $rightTimes->put($position, trim((string) $row->time));
    } elseif ($day === 'SAT') {
        $saturdayTimes->put($position, trim((string) $row->time));
    }
}
/*
|--------------------------------------------------------------------------
| SORT TIME SLOTS
|--------------------------------------------------------------------------
|
| This prevents custom times from always appearing
| at the bottom.
|
*/

$sortTime = function ($time) {
    $start = trim(explode('-', $time)[0]);

    if (!preg_match('/^(\d{1,2}):(\d{2})$/', $start, $matches)) {
        return 9999;
    }

    $hour = (int) $matches[1];
    $minute = (int) $matches[2];

    // Treat 1:00-6:00 as afternoon times
    if ($hour >= 1 && $hour <= 6) {
        $hour += 12;
    }

    return ($hour * 60) + $minute;
};

$leftTimes = $leftTimes
    ->sortBy($sortTime)
    ->values();

$rightTimes = $rightTimes
    ->sortBy($sortTime)
    ->values();

$saturdayTimes = $saturdayTimes
    ->sortBy($sortTime)
    ->values();

    /*
    |--------------------------------------------------------------------------
    | MAKE ROW COUNT THE SAME
    |--------------------------------------------------------------------------
    */

$rowCount = max(
    ((int) ($leftTimes->keys()->max() ?? -1)) + 1,
    ((int) ($rightTimes->keys()->max() ?? -1)) + 1,
    ((int) ($saturdayTimes->keys()->max() ?? -1)) + 1
);

@endphp


@for($i = 0; $i < $rowCount; $i++)

    @php

        $leftTime =
            $leftTimes[$i] ?? '';

        $rightTime =
            $rightTimes[$i] ?? '';

        $saturdayTime =
            $saturdayTimes[$i] ?? '';

    @endphp


    {{-- =========================================================
         NOON BREAK
         ========================================================= --}}

  @if($i === 5)
    <tr class="add-time-before-break-row">
        <td colspan="9">
            <button
                type="button"
                class="btn btn-gray"
                id="addRowBeforeBreakButton"
            >
                + Add Time Row 
            </button>
        </td>
    </tr>

    <tr class="break-row">
        <td colspan="9">
            N O O N
            &nbsp;&nbsp;
            B R E A K
        </td>
    </tr>
@endif


    <tr>

        {{-- =====================================================
             LEFT TIME — MWF
             ===================================================== --}}

       <td data-side="left" class="time-cell editable-time" contenteditable="{{ $isPublishedScheduleView ? 'false' : 'true' }}" spellcheck="false">
         {{ $leftTime }}
       </td>


        {{-- =====================================================
             MON
             ===================================================== --}}

        @foreach(['MON', 'WED', 'FRI'] as $day)

            @php

                $key =
                    $day
                    . '|'
                    . trim($leftTime);

                $schedule =
                    $scheduleRowMap[$day . '|' . $i]
                    ?? $scheduleMap[$key]
                    ?? null;

                $reservation = $reservations->first(function ($item) use ($day, $leftTime, $timeToMinutes) {
                    if (strtoupper(trim((string) $item->day)) !== $day) {
                        return false;
                    }

                    $slotParts = explode('-', trim($leftTime), 2);
                    if (count($slotParts) !== 2) {
                        return false;
                    }

                    $slotStart = $timeToMinutes($slotParts[0]);
                    $slotEnd = $timeToMinutes($slotParts[1]);
                    $reservationStart = $timeToMinutes(\Carbon\Carbon::parse($item->start_time)->format('H:i'));
                    $reservationEnd = $timeToMinutes(\Carbon\Carbon::parse($item->end_time)->format('H:i'));

                    if ($slotStart === null || $slotEnd === null || $reservationStart === null || $reservationEnd === null) {
                        return false;
                    }

                    return $slotStart < $reservationEnd && $slotEnd > $reservationStart;
                });

                $isLocked =
                    $isCrossRoomReadOnly
                    ||
                    (bool) $reservation
                    ||
                    (
                        $schedule
                        &&
                        isset($schedule->department_id)
                        &&
                        (int) $schedule->department_id
                        !==
                        (int) $chairDepartmentId
                    );

            @endphp


            <td
                data-side="left"
                class="schedule-cell {{ $isLocked ? 'locked-cell' : '' }} {{ $reservation ? 'reserved-cell' : '' }}"

                data-schedule-id="{{ $schedule ? $schedule->id : '' }}"

                data-day="{{ $day }}"

                data-time="{{ $leftTime }}"

                data-schedule-base-time="{{ $leftTime }}"

                data-row-position="{{ $i }}"

                data-schedule-time="{{ $schedule ? $schedule->time : '' }}"

                data-course="{{ $schedule ? $schedule->course_code : '' }}"

                data-subject="{{ $schedule ? $schedule->subject : '' }}"

                data-year="{{ $schedule ? ($schedule->year_level ?? '') : '' }}"

                data-major="{{ $schedule ? ($schedule->major ?? '') : '' }}"

                data-program="{{ $schedule ? ($schedule->program ?? '') : '' }}"
                data-semester="{{ $schedule ? ($schedule->semester ?? '') : '' }}"
                data-academic-year="{{ $schedule ? ($schedule->academic_year ?? '') : '' }}"

                data-instructor="{{ $schedule ? $schedule->instructor : '' }}"

                data-faculty-id="{{ $schedule ? ($schedule->faculty_id ?? '') : '' }}"
                data-faculty-subject-id="{{ $schedule ? ($schedule->faculty_subject_id ?? '') : '' }}"
        data-shared-offering-ids="{{ $schedule ? $schedule->merges->where('status', 'approved')->where('share_type', 'same_faculty')->pluck('subject_id')->filter()->implode(',') : '' }}"

                data-department-id="{{ $schedule ? ($schedule->department_id ?? '') : '' }}"
                data-department-name="{{ $schedule?->department?->name ?? '' }}"
                data-room-name="{{ $room->room_name }}"
                data-building-name="{{ $room->building->name ?? $room->building ?? '' }}"
                data-approved-merged-courses="{{ e($mergeCourseInfoForSchedule($schedule)->toJson()) }}"

                @if($schedule && (int) ($schedule->department_id ?? 0) !== (int) $chairDepartmentId)
                    data-merge-schedule-id="{{ $schedule->id }}"
                    data-merge-course-code="{{ $schedule->course_code }}"
                    data-merge-subject="{{ $schedule->subject }}"
                    data-merge-instructor="{{ $schedule->instructor }}"
                    data-merge-college="{{ $schedule->department->name ?? '' }}"
                    data-merge-program="{{ $schedule->program ?? '' }}"
                    data-merge-building="{{ $room->building->name ?? $room->building ?? '' }}"
                    data-merge-room="{{ $room->room_name }}"
                    data-merge-day="{{ $day }}"
                    data-merge-time="{{ $leftTime }}"
                    data-merge-semester="{{ $schedule->semester ?? '' }}"
                    data-merge-academic-year="{{ $schedule->academic_year ?? '' }}"
                @endif

                data-color="{{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }}"

                data-locked="{{ $isLocked ? '1' : '0' }}"

                data-reservation-id="{{ $reservation ? $reservation->id : '' }}"
                data-reservation-time="{{ $reservation ? (\Carbon\Carbon::parse($reservation->start_time)->format('H:i') . '-' . \Carbon\Carbon::parse($reservation->end_time)->format('H:i')) : '' }}"

                data-subject-type="{{ $schedule ? ($schedule->subject_type ?? '') : '' }}"

                style="
                    background-color:
                    {{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }};
                "
            >

                @if($schedule)

                    <div class="schedule-cell-content">

                    @php
                        $mergeDisplayCourses = $mergeCourseInfoForSchedule($schedule);
                        $mergeDisplayValues = fn ($primary, $key) => collect([$primary])->merge($mergeDisplayCourses->pluck($key))
                            ->filter(fn ($value) => trim((string) $value) !== '')
                            ->unique(fn ($value) => mb_strtolower(trim((string) $value)))->values();
                        $mergeCourseCodes = $mergeDisplayValues($schedule->course_code, 'course_code');
                        $mergeCourseTitles = $mergeDisplayValues($schedule->subject, 'course_title');
                        $mergeYearLevels = $mergeDisplayValues($schedule->year_level, 'year_level');
                        $mergeMajors = $mergeDisplayValues($schedule->major, 'major');
                        $mergeSubjectTypes = $mergeDisplayValues($schedule->subject_type, 'subject_type');
                    @endphp

                    <div class="cell-course">
                        {{ $mergeCourseCodes->implode(' / ') }}
                    </div>

                    <div class="cell-subject">
                        {{ $mergeCourseTitles->implode(' / ') }}
                    </div>

                    <div class="cell-instructor">
                        {{ $schedule->instructor }}
                    </div>

                    <div class="cell-year">
                        {{ $mergeYearLevels->implode(' / ') }}
                    </div>

                    @if($mergeMajors->isNotEmpty())
                        <div class="cell-major">
                            {{ $mergeMajors->implode(' / ') }}
                        </div>
                    @endif

        
    @php
    $programs = collect();

    /*
    |--------------------------------------------------------------------------
    | MAIN PROGRAM
    |--------------------------------------------------------------------------
    */

    if (!empty($schedule->program)) {
        $programs->push(
            $schedule->facultySubject?->subjectCatalog?->program?->code
                ?: $programCodeLabel($schedule->program)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVED MERGED PROGRAMS
    |--------------------------------------------------------------------------
    |
    | Published Schedule records may have approved or pending merges.
    |
    */

    $scheduleMerges =
        $schedule->merges ?? collect();

    $mergedCourses = collect();
    $sharedMajors = collect();
    $pendingMergeCourses = collect();

    foreach ($scheduleMerges as $merge) {

        if ($merge->status === 'pending' && (int) $merge->department_id === (int) $chairDepartmentId) {
            $pendingMergeCourses->push($merge);
            continue;
        }

        if ($merge->status !== 'approved') {
            continue;
        }

        $mergedSubject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
        $mergedProgram = $mergedSubject?->program?->code
            ?? $merge->facultySubject?->subjectCatalog?->program?->code
            ?? $programCodeLabel($mergedSubject?->program?->name ?? $merge->facultySubject?->program);
        if ($mergedProgram) {
            $programs->push($mergedProgram);
        }
        if (($merge->share_type ?? 'merge') === 'same_faculty') {
            $major = trim((string) ($mergedSubject?->program?->major ?? $merge->facultySubject?->major ?? ''));
            if ($major !== '') $sharedMajors->push($major);
            continue;
        }
        if ($mergedSubject) {
            $mergedCourses->push([
                'course_code' => $mergedSubject->course_code ?? $merge->facultySubject?->course_code,
                'course_title' => $mergedSubject->course_title ?? $merge->facultySubject?->subject,
                'program' => $mergedProgram,
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE DUPLICATES
    |--------------------------------------------------------------------------
    */

  


    $programs = $programs
        ->map(fn ($program) => $programCodeLabel($program))
        ->filter()
        ->unique()
        ->values();

    $sharedMajors = $sharedMajors
        ->filter(fn ($major) => mb_strtolower($major) !== mb_strtolower(trim((string) ($schedule->major ?? ''))))
        ->unique(fn ($major) => mb_strtolower($major))
        ->values();
@endphp

@if($programs->count() > 0)
    <div class="cell-program">
        {{ $programs->implode(' / ') }}
    </div>
@endif

@if($sharedMajors->isNotEmpty())
    <div class="cell-shared-majors" title="This meeting also serves these majors">
        <strong>Other majors</strong>
        <span>{{ $sharedMajors->implode(' · ') }}</span>
    </div>
@endif

@if($pendingMergeCourses->isNotEmpty())
    <span class="schedule-merge-pending-label">Your merge request is waiting for approval</span>
@endif

                    @if($mergeSubjectTypes->isNotEmpty())
                        <div class="cell-subject-type">
                            {{ $mergeSubjectTypes->implode(' / ') }}
                        </div>
                    @endif

                    @if($reservation)
                        <div class="reservation-cell-label">
                            {{ $reservation->status === 'Reserved' ? 'TEMPORARY RESERVATION' : 'RESERVATION' }}
                        </div>
                        <div class="reservation-cell-user">
                            {{ $reservation->user?->name ?? 'Instructor' }}
                        </div>
                        <div class="reservation-cell-time">
                            {{ \Carbon\Carbon::parse($reservation->date)->format('M d, Y') }} · {{ $reservation->time }}
                        </div>
                        <div class="reservation-cell-note">
                            This slot is temporarily blocked and cannot be scheduled.
                        </div>
                    @endif

                    @if($isLocked)
                        <div class="locked-label">
                            🔒 LOCKED
                        </div>
                    @endif

                    </div>

                @else

                    <span class="empty-cell">
                        Empty
                    </span>

                @endif

            </td>

        @endforeach


        {{-- =====================================================
             RIGHT TIME — TTH
             ===================================================== --}}

        <td
            data-side="right"
            class="time-cell editable-time"
            contenteditable="{{ $isPublishedScheduleView ? 'false' : 'true' }}"
            spellcheck="false"
        >
            {{ $rightTime }}
        </td>


        {{-- =====================================================
             TUE / THU
             ===================================================== --}}

        @foreach(['TUE', 'THU', 'SAT'] as $day)

            @if($day === 'SAT')
                <td data-side="sat" class="time-cell editable-time" contenteditable="{{ $isPublishedScheduleView ? 'false' : 'true' }}" spellcheck="false">
                    {{ $saturdayTime }}
                </td>
                @php
                    $cellTime = $saturdayTime;
                    $cellSide = 'sat';
                @endphp
            @else
                @php
                    $cellTime = $rightTime;
                    $cellSide = 'right';
                @endphp
            @endif

            @php

                $key =
                    $day
                    . '|'
                    . trim($cellTime);

                $schedule =
                    $scheduleRowMap[$day . '|' . $i]
                    ?? $scheduleMap[$key]
                    ?? null;

                $reservation = $reservations->first(function ($item) use ($day, $cellTime, $timeToMinutes) {
                    if (strtoupper(trim((string) $item->day)) !== $day) {
                        return false;
                    }

                    $slotParts = explode('-', trim($cellTime), 2);
                    if (count($slotParts) !== 2) {
                        return false;
                    }

                    $slotStart = $timeToMinutes($slotParts[0]);
                    $slotEnd = $timeToMinutes($slotParts[1]);
                    $reservationStart = $timeToMinutes(\Carbon\Carbon::parse($item->start_time)->format('H:i'));
                    $reservationEnd = $timeToMinutes(\Carbon\Carbon::parse($item->end_time)->format('H:i'));

                    if ($slotStart === null || $slotEnd === null || $reservationStart === null || $reservationEnd === null) {
                        return false;
                    }

                    return $slotStart < $reservationEnd && $slotEnd > $reservationStart;
                });

                $isLocked =
                    $isCrossRoomReadOnly
                    ||
                    (bool) $reservation
                    ||
                    (
                        $schedule
                        &&
                        isset($schedule->department_id)
                        &&
                        (int) $schedule->department_id
                        !==
                        (int) $chairDepartmentId
                    );

            @endphp


            <td
                data-side="{{ $cellSide }}"
                class="schedule-cell {{ $isLocked ? 'locked-cell' : '' }} {{ $reservation ? 'reserved-cell' : '' }}"

                data-schedule-id="{{ $schedule ? $schedule->id : '' }}"

                data-day="{{ $day }}"

                data-time="{{ $cellTime }}"

                data-schedule-base-time="{{ $cellTime }}"

                data-row-position="{{ $i }}"

                data-schedule-time="{{ $schedule ? $schedule->time : '' }}"

                data-course="{{ $schedule ? $schedule->course_code : '' }}"

                data-subject="{{ $schedule ? $schedule->subject : '' }}"

                data-year="{{ $schedule ? ($schedule->year_level ?? '') : '' }}"

                data-major="{{ $schedule ? ($schedule->major ?? '') : '' }}"

                data-program="{{ $schedule ? ($schedule->program ?? '') : '' }}"
                data-semester="{{ $schedule ? ($schedule->semester ?? '') : '' }}"
                data-academic-year="{{ $schedule ? ($schedule->academic_year ?? '') : '' }}"

                data-instructor="{{ $schedule ? $schedule->instructor : '' }}"

                data-faculty-id="{{ $schedule ? ($schedule->faculty_id ?? '') : '' }}"
                data-faculty-subject-id="{{ $schedule ? ($schedule->faculty_subject_id ?? '') : '' }}"
                data-shared-offering-ids="{{ $schedule ? $schedule->merges->where('status', 'approved')->where('share_type', 'same_faculty')->pluck('subject_id')->filter()->implode(',') : '' }}"

                data-department-id="{{ $schedule ? ($schedule->department_id ?? '') : '' }}"
                data-department-name="{{ $schedule?->department?->name ?? '' }}"
                data-room-name="{{ $room->room_name }}"
                data-building-name="{{ $room->building->name ?? $room->building ?? '' }}"
                data-approved-merged-courses="{{ e($mergeCourseInfoForSchedule($schedule)->toJson()) }}"

                @if($schedule && (int) ($schedule->department_id ?? 0) !== (int) $chairDepartmentId)
                    data-merge-schedule-id="{{ $schedule->id }}"
                    data-merge-course-code="{{ $schedule->course_code }}"
                    data-merge-subject="{{ $schedule->subject }}"
                    data-merge-instructor="{{ $schedule->instructor }}"
                    data-merge-college="{{ $schedule->department->name ?? '' }}"
                    data-merge-program="{{ $schedule->program ?? '' }}"
                    data-merge-building="{{ $room->building->name ?? $room->building ?? '' }}"
                    data-merge-room="{{ $room->room_name }}"
                    data-merge-day="{{ $day }}"
                    data-merge-time="{{ $cellTime }}"
                    data-merge-semester="{{ $schedule->semester ?? '' }}"
                    data-merge-academic-year="{{ $schedule->academic_year ?? '' }}"
                @endif

                data-color="{{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }}"

                data-locked="{{ $isLocked ? '1' : '0' }}"

                data-reservation-id="{{ $reservation ? $reservation->id : '' }}"
                data-reservation-time="{{ $reservation ? (\Carbon\Carbon::parse($reservation->start_time)->format('H:i') . '-' . \Carbon\Carbon::parse($reservation->end_time)->format('H:i')) : '' }}"

                data-subject-type="{{ $schedule ? ($schedule->subject_type ?? '') : '' }}"

                style="
                    background-color:
                    {{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }};
                "
            >

                @if($schedule)

                    <div class="schedule-cell-content">

                    @php
                        $mergeDisplayCourses = $mergeCourseInfoForSchedule($schedule);
                        $mergeDisplayValues = fn ($primary, $key) => collect([$primary])->merge($mergeDisplayCourses->pluck($key))
                            ->filter(fn ($value) => trim((string) $value) !== '')
                            ->unique(fn ($value) => mb_strtolower(trim((string) $value)))->values();
                        $mergeCourseCodes = $mergeDisplayValues($schedule->course_code, 'course_code');
                        $mergeCourseTitles = $mergeDisplayValues($schedule->subject, 'course_title');
                        $mergeYearLevels = $mergeDisplayValues($schedule->year_level, 'year_level');
                        $mergeMajors = $mergeDisplayValues($schedule->major, 'major');
                        $mergeSubjectTypes = $mergeDisplayValues($schedule->subject_type, 'subject_type');
                    @endphp

                    <div class="cell-course">
                        {{ $mergeCourseCodes->implode(' / ') }}
                    </div>

                    <div class="cell-subject">
                        {{ $mergeCourseTitles->implode(' / ') }}
                    </div>

                    <div class="cell-instructor">
                        {{ $schedule->instructor }}
                    </div>

                    <div class="cell-year">
                        {{ $mergeYearLevels->implode(' / ') }}
                    </div>

                    @if($mergeMajors->isNotEmpty())
                        <div class="cell-major">
                            {{ $mergeMajors->implode(' / ') }}
                        </div>
                    @endif

                    @php
                        $displayProgramCodes = collect([
                            $schedule->facultySubject?->subjectCatalog?->program?->code ?: $programCodeLabel($schedule->program),
                        ])->merge($mergeDisplayCourses->pluck('program_code'))
                            ->filter(fn ($value) => trim((string) $value) !== '')
                            ->unique(fn ($value) => mb_strtolower(trim((string) $value)))
                            ->values();
                    @endphp
                    @if($displayProgramCodes->isNotEmpty())
                        <div class="cell-program">{{ $displayProgramCodes->implode(' / ') }}</div>
                    @endif

                    @php
                        $cellMerges = collect($schedule->merges ?? []);
                        $pendingMergeCourses = $cellMerges
                            ->where('status', 'pending')
                            ->where('department_id', $chairDepartmentId);
                        $approvedMerges = $cellMerges->where('status', 'approved');
                        $sharedMajors = $approvedMerges
                            ->filter(fn ($merge) => ($merge->share_type ?? 'merge') === 'same_faculty')
                            ->map(function ($merge) {
                                $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                                return trim((string) ($subject?->program?->major ?? $merge->facultySubject?->major ?? ''));
                            })
                            ->filter()
                            ->reject(fn ($major) => mb_strtolower($major) === mb_strtolower(trim((string) ($schedule->major ?? ''))))
                            ->unique(fn ($major) => mb_strtolower($major))
                            ->values();
                        $mergedCourses = $approvedMerges
                            ->reject(fn ($merge) => ($merge->share_type ?? 'merge') === 'same_faculty')
                            ->map(function ($merge) use ($programCodeLabel) {
                                $subject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                                if (!$subject) return null;
                                return [
                                    'course_code' => $subject->course_code ?? $merge->facultySubject?->course_code,
                                    'course_title' => $subject->course_title ?? $merge->facultySubject?->subject,
                                    'program' => $subject->program?->code
                                        ?? $merge->facultySubject?->subjectCatalog?->program?->code
                                        ?? $programCodeLabel($subject->program?->name ?? $merge->facultySubject?->program),
                                ];
                            })
                            ->filter()
                            ->values();
                    @endphp
                    @if($sharedMajors->isNotEmpty())
                        <div class="cell-shared-majors" title="This meeting also serves these majors">
                            <strong>Other majors</strong>
                            <span>{{ $sharedMajors->implode(' · ') }}</span>
                        </div>
                    @endif
                    @if($pendingMergeCourses->isNotEmpty())
                        <span class="schedule-merge-pending-label">Your merge request is waiting for approval</span>
                    @endif

                    @if($mergeSubjectTypes->isNotEmpty())
                        <div class="cell-subject-type">
                            {{ $mergeSubjectTypes->implode(' / ') }}
                        </div>
                    @endif

                    @if($reservation)
                        <div class="reservation-cell-label">
                            {{ $reservation->status === 'Reserved' ? 'TEMPORARY RESERVATION' : 'RESERVATION' }}
                        </div>
                        <div class="reservation-cell-user">
                            {{ $reservation->user?->name ?? 'Instructor' }}
                        </div>
                        <div class="reservation-cell-time">
                            {{ \Carbon\Carbon::parse($reservation->date)->format('M d, Y') }} · {{ $reservation->time }}
                        </div>
                        <div class="reservation-cell-note">
                            This slot is temporarily blocked and cannot be scheduled.
                        </div>
                    @endif

                    @if($isLocked)
                        <div class="locked-label">
                            🔒 LOCKED
                        </div>
                    @endif

                    </div>

                @else

                    <span class="empty-cell">
                        Empty
                    </span>

                @endif

            </td>

        @endforeach

    </tr>

@endfor


                </tbody>

            </table>

        </div>


        <!-- =====================================================
             ADD TIME ROW
        ====================================================== -->

        <div class="table-footer">

            <button
                type="button"
                class="btn btn-gray"
                id="addRowButton"
            >
                + Add Time Row
            </button>

            <button type="button" class="btn btn-gray" id="toggleDeleteRowsButton">Delete Row</button>
            <span id="deleteRowsModeHint" class="schedule-row-delete-hint" hidden>Select row checkboxes, then choose Delete Selected Rows.</span>
            <button type="button" class="btn btn-red" id="deleteSelectedRowsButton" hidden disabled>Delete Selected Rows</button>

        </div>

        <div class="schedule-row-delete-confirm" id="scheduleRowDeleteConfirm" role="alertdialog" aria-modal="true" aria-labelledby="scheduleRowDeleteConfirmTitle" aria-describedby="scheduleRowDeleteConfirmMessage" hidden>
            <section class="schedule-row-delete-confirm-card">
                <h2 id="scheduleRowDeleteConfirmTitle">Delete selected time rows?</h2>
                <p id="scheduleRowDeleteConfirmMessage"></p>
                <div class="schedule-row-delete-confirm-actions">
                    <button type="button" id="cancelScheduleRowDelete">Cancel</button>
                    <button type="button" class="confirm" id="confirmScheduleRowDelete">Delete Rows</button>
                </div>
            </section>
        </div>


        <!-- =====================================================
             ACTIONS
        ====================================================== -->

        <div class="editor-actions">


            <div class="action-group">

                <a
                    href="{{ $isCrossScheduling ? route('chair.modifyschedule', ['term_id' => $activeTerm->id]) : route('chair.setschedule') }}"
                    class="btn btn-gray"
                >
                    ← Back to {{ $isCrossScheduling ? 'Cross-Scheduling' : 'Schedule Editor' }}
                </a>

            </div>


            <div class="action-group">
                <button
                    type="submit"
                    class="btn btn-green"
                >
                    Upload Schedule
                </button>

            </div>

        </div>


        <!-- HIDDEN INPUTS -->

        <div id="hiddenInputs"></div>


    </form>


</div>

<script>

    /*
    |--------------------------------------------------------------------------
    | FACULTY SUBJECT DATA
    |--------------------------------------------------------------------------
    */

    const facultySubjects =
        @json($facultySubjectsData);

    const scheduleConflictCheckUrl = @json(route('chair.schedule.conflict-check'));
    const scheduleConflictTermId = @json($activeTerm->id ?? null);
    const scheduleClearCellsUrl = @json(route('chair.schedule.clear-cells'));


    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR DEPARTMENT
    |--------------------------------------------------------------------------
    */

    const chairDepartmentId =
        @json($isPublishedScheduleView ? (int) $room->department_id : auth()->user()->department_id);

    const isPublishedScheduleView = @json($isPublishedScheduleView);
    const publishedRoomId = @json((int) $room->id);
    let selectedPublishedOverride = null;

    /*
    |--------------------------------------------------------------------------
    | SELECTED CELL
    |--------------------------------------------------------------------------
    */

    let selectedCell = null;
    let selectedScheduleEntry = null;
    let selectedScheduleInterval = null;
    let activeAutoMergeCells = [];
    let scheduleBlockLayer = null;
    let suppressScheduleBlockClickUntil = 0;
    let scheduleRowDeleteMode = false;
    let scheduleRowDeletionPending = false;


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const scheduleForm =
        document.getElementById(
            'scheduleForm'
        );

    const overviewFocus = new URLSearchParams(window.location.search);
    const overviewFocusDay = String(overviewFocus.get('focus_day') || '').toUpperCase();
    const overviewFocusRange = parseScheduleRange(overviewFocus.get('focus_time') || '');
    if (overviewFocusDay && overviewFocusRange) {
        const focusCell = Array.from(document.querySelectorAll('#scheduleTable .schedule-cell[data-day][data-time]'))
            .find(function(cell) {
                if (String(cell.dataset.day || '').toUpperCase() !== overviewFocusDay) return false;
                const cellRange = parseScheduleRange(cell.dataset.time || '');
                return cellRange && overviewFocusRange.start >= cellRange.start && overviewFocusRange.start < cellRange.end;
            });
        if (focusCell) {
            focusCell.classList.add('room-overview-focused-cell');
            window.setTimeout(() => focusCell.scrollIntoView({behavior:'smooth', block:'center', inline:'center'}), 80);
        }
    }


    const courseCodeInput =
        document.getElementById(
            'course_code'
        );


    const subjectInput =
        document.getElementById(
            'subject'
        );


    const yearLevelInput =
        document.getElementById(
            'year_level'
        );

    const majorInput =
        document.getElementById(
            'major'
        );

    const programInput =
        document.getElementById(
            'program'
        );

    const instructorInput =
        document.getElementById(
            'instructor'
        );


    const colorInput =
        document.getElementById(
            'cellColor'
        );

    const cellColorControl = document.getElementById('cellColorControl');
    const clearCellButton = document.getElementById('clearCellButton');
    const selectedCellMergeControls = document.getElementById('selectedCellMergeControls');


    const lookupStatus =
        document.getElementById(
            'lookupStatus'
        );

    const subjectTypeInput =
    document.getElementById(
        'subject_type'
    );
    const subjectMatchSelector = document.getElementById('subjectMatchSelector');
    const sharedAudiencePicker = document.getElementById('sharedAudiencePicker');
    const sharedAudienceChoices = document.getElementById('sharedAudienceChoices');
    const scheduleEntryPopup = document.getElementById('scheduleEntryPopup');
    const scheduleEntryTarget = document.getElementById('scheduleEntryTarget');
    const mergeSelectedScheduleButton = document.getElementById('mergeSelectedScheduleButton');
    const sameCourseConflictConfirm = document.getElementById('sameCourseConflictConfirm');
    const sameCourseConflictMessage = document.getElementById('sameCourseConflictMessage');
    const excelSheetToolbar = document.querySelector('.excel-sheet-toolbar');

    function availableHalfHourAtPointer(cell, clientY)
    {
        const baseRange = parseScheduleRange(cell.dataset.time || '');
        if (!baseRange) return null;

        const entries = getCellScheduleEntries(cell);
        if (!entries.length) return null;

        const timeCell = cell.parentElement?.querySelector('.editable-time[data-side="' + cell.dataset.side + '"]');
        const rect = timeCell?.getBoundingClientRect() || cell.parentElement?.getBoundingClientRect();
        if (!rect || rect.height <= 0) return null;

        const ratio = Math.max(0, Math.min(0.999, (clientY - rect.top) / rect.height));
        const clickedMinute = baseRange.start + (ratio * (baseRange.end - baseRange.start));
        const clickedStart = baseRange.start + Math.floor((clickedMinute - baseRange.start) / 30) * 30;
        const starts = [clickedStart, clickedStart === baseRange.start ? clickedStart + 30 : baseRange.start];
        const day = String(cell.dataset.day || '').toUpperCase();
        const roomEntries = [];

        document.querySelectorAll('#scheduleTable .schedule-cell').forEach(function(candidateCell) {
            if (String(candidateCell.dataset.day || '').toUpperCase() !== day) return;
            getCellScheduleEntries(candidateCell).forEach(function(entry) {
                const range = parseScheduleRange(entry.time || '');
                if (range) roomEntries.push({ entry: entry, range: range });
            });
        });

        for (const start of starts) {
            const end = start + 30;
            if (start < baseRange.start || end > baseRange.end) continue;
            const occupied = roomEntries.some(function(item) {
                return start < item.range.end && end > item.range.start;
            });
            if (!occupied) return { cell: cell, start: start, end: end };
        }

        return null;
    }

    function openScheduleEntryPopup(cell = selectedCell, entry = selectedScheduleEntry, interval = selectedScheduleInterval)
    {
        const canOpenLockedMerge = Boolean(
            cell
            && cell.classList.contains('locked-cell')
            && document.body.classList.contains('cross-scheduling-editor')
            && (entry?.schedule_id || cell.dataset.scheduleId)
        );
        if (!cell || (!cell.classList.contains('selected-cell') && !canOpenLockedMerge)) {
            showWarning('Click a timetable cell to select it, then right-click to enter a course.');
            return;
        }

        selectCell(cell, false, document.body.classList.contains('cross-scheduling-editor') && cell.classList.contains('locked-cell'));
        const day = String(cell.dataset.day || '').toUpperCase();
        const time = entry?.time
            || (interval?.cell === cell ? formatScheduleRange(interval.start, interval.end) : '')
            || scheduleEntryTimeForCell(cell)
            || 'Selected time';
        if (scheduleEntryTarget) {
            const timeRange = parseScheduleRange(time);
            const displayTime = timeRange
                ? formatScheduleRangeLabel(timeRange.start, timeRange.end)
                : time;
            scheduleEntryTarget.textContent = [day, displayTime].filter(Boolean).join(' · ');
        }

        const savedCourse = String(entry?.course ?? (interval?.cell === cell ? '' : cell.dataset.course) ?? '').trim();
        const savedScheduleId = entry?.schedule_id || cell.dataset.scheduleId || '';
        const mergeOnly = document.body.classList.contains('cross-scheduling-editor')
            && cell.classList.contains('locked-cell')
            && Boolean(savedCourse && savedScheduleId);
        scheduleEntryPopup.dataset.mergeOnly = mergeOnly ? '1' : '0';
        scheduleEntryPopup.querySelectorAll('.form-grid input, .form-grid select').forEach(function(input) {
            input.disabled = mergeOnly;
        });
        const confirmEntryButton = document.getElementById('confirmScheduleEntryButton');
        if (confirmEntryButton) confirmEntryButton.hidden = mergeOnly;
        const cancelEntryButton = document.getElementById('cancelScheduleEntryButton');
        if (cancelEntryButton) cancelEntryButton.textContent = mergeOnly ? 'Close' : 'Cancel';
        if (mergeSelectedScheduleButton) {
            const mergeIsPending = cell.dataset.mergePending === '1'
                || Boolean(cell.querySelector('.schedule-merge-pending-label'));
            let approvedMerges = [];
            try { approvedMerges = JSON.parse(cell.dataset.approvedMergedCourses || '[]'); } catch (error) {}
            const currentDepartmentId = String(document.getElementById('scheduleMergeRequestModal')?.dataset.currentDepartmentId || '');
            const scheduleDepartmentId = String(cell.dataset.departmentId || '');
            const canRemoveApprovedMerge = approvedMerges.some(function(merge) {
                return merge.merge_id && (scheduleDepartmentId === currentDepartmentId || String(merge.department_id || '') === currentDepartmentId);
            });
            mergeSelectedScheduleButton.textContent = canRemoveApprovedMerge ? 'Unmerge Class' : 'Merge Class';
            mergeSelectedScheduleButton.dataset.mergeAction = canRemoveApprovedMerge ? 'unmerge' : 'merge';
            mergeSelectedScheduleButton.hidden = isPublishedScheduleView || !savedCourse || !savedScheduleId || (mergeIsPending && !canRemoveApprovedMerge);
            mergeSelectedScheduleButton.disabled = false;
        }
        if (savedCourse) {
            courseCodeInput.value = savedCourse;
            const matches = findCourse(savedCourse);
            const savedAssignmentId = entry?.faculty_subject_id ?? cell.dataset.facultySubjectId;
            const primary = matches.find(item => (item.assignment_ids || [item.id]).map(String).includes(String(savedAssignmentId || '')));
            if (primary) {
                if (matches.length > 1) {
                    subjectMatchSelector.replaceChildren(new Option('Choose the matching year level and instructor', ''));
                    matches.forEach(item => {
                        const details = [item.year_level, item.program, item.instructor, item.major ? `Major: ${item.major}` : ''].filter(Boolean);
                        subjectMatchSelector.add(new Option(details.join(' · '), String(item.id)));
                    });
                    subjectMatchSelector.value = String(primary.id);
                    subjectMatchSelector.hidden = false;
                } else if (subjectMatchSelector) {
                    subjectMatchSelector.hidden = true;
                }
                const sharedIds = entry?.shared_offering_ids
                    ?? String(cell.dataset.sharedOfferingIds || '').split(',').filter(Boolean);
                applyFacultySubject(primary, Array.isArray(sharedIds) ? sharedIds : String(sharedIds).split(',').filter(Boolean));
            }
        } else {
            courseCodeInput.value = '';
            subjectInput.value = '';
            yearLevelInput.value = '';
            instructorInput.value = '';
            majorInput.value = '';
            programInput.value = '';
            subjectTypeInput.value = '';
            if (subjectMatchSelector) subjectMatchSelector.hidden = true;
            renderSharedAudienceOptions(null);
        }

        scheduleEntryPopup.hidden = false;
        excelSheetToolbar?.classList.add('is-entry-open');
        window.requestAnimationFrame(function() {
            courseCodeInput.focus();
            courseCodeInput.select();
        });
    }

    mergeSelectedScheduleButton?.addEventListener('click', function() {
        const cell = selectedCell;
        const entry = selectedScheduleEntry;
        const scheduleId = entry?.schedule_id || cell?.dataset.scheduleId || '';
        if (!cell || !scheduleId) {
            showWarning('Select a saved schedule entry before merging a course.');
            return;
        }

        const schedule = {
            id: scheduleId,
            course_code: entry?.course || cell.dataset.course || '',
            subject: entry?.subject || cell.dataset.subject || '',
            instructor: entry?.instructor || cell.dataset.instructor || '',
            department_id: entry?.department_id || cell.dataset.departmentId || '',
            department: cell.dataset.departmentName || '',
            program: entry?.program || cell.dataset.program || '',
            building: cell.dataset.buildingName || '',
            room: cell.dataset.roomName || '',
            day: entry?.day || cell.dataset.day || '',
            time: entry?.time || cell.dataset.scheduleTime || cell.dataset.time || '',
            semester: cell.dataset.semester || '',
            academic_year: cell.dataset.academicYear || ''
        };

        let activeMerges = [];
        try { activeMerges = JSON.parse(cell.dataset.approvedMergedCourses || '[]'); } catch (error) {}
        const currentDepartmentId = String(document.getElementById('scheduleMergeRequestModal')?.dataset.currentDepartmentId || '');
        const scheduleDepartmentId = String(cell.dataset.departmentId || '');
        activeMerges = activeMerges.filter(function(merge) {
            return merge.merge_id && (scheduleDepartmentId === currentDepartmentId || String(merge.department_id || '') === currentDepartmentId);
        });

        closeScheduleEntryPopup();
        if (mergeSelectedScheduleButton?.dataset.mergeAction === 'unmerge' && activeMerges.length) {
            if (window.openScheduleUnmergeConfirm) {
                window.openScheduleUnmergeConfirm(schedule, activeMerges, cell);
            }
            return;
        }
        if (window.openScheduleMergeRequest) {
            window.openScheduleMergeRequest(schedule, cell);
        } else {
            showWarning('The course merge panel is still loading. Try again in a moment.');
        }
    });

    function closeScheduleEntryPopup()
    {
        if (scheduleEntryPopup) scheduleEntryPopup.hidden = true;
        if (scheduleEntryPopup) {
            scheduleEntryPopup.dataset.mergeOnly = '0';
            scheduleEntryPopup.querySelectorAll('.form-grid input, .form-grid select').forEach(function(input) {
                input.disabled = false;
            });
        }
        const confirmEntryButton = document.getElementById('confirmScheduleEntryButton');
        if (confirmEntryButton) confirmEntryButton.hidden = false;
        const cancelEntryButton = document.getElementById('cancelScheduleEntryButton');
        if (cancelEntryButton) cancelEntryButton.textContent = 'Cancel';
        if (sameCourseConflictConfirm) sameCourseConflictConfirm.hidden = true;
        excelSheetToolbar?.classList.remove('is-entry-open');
        selectedScheduleEntry = null;
        selectedScheduleInterval = null;
    }

    document.getElementById('cancelSameCourseConflict')?.addEventListener('click', function() {
        if (sameCourseConflictConfirm) sameCourseConflictConfirm.hidden = true;
    });
    document.getElementById('proceedSameCourseConflict')?.addEventListener('click', function() {
        if (sameCourseConflictConfirm) sameCourseConflictConfirm.hidden = true;
        saveCell(true);
    });

    document.getElementById('closeScheduleEntryPopup')?.addEventListener('click', closeScheduleEntryPopup);
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && scheduleEntryPopup && !scheduleEntryPopup.hidden) {
            closeScheduleEntryPopup();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(
        value
    )
    {

        const div =
            document.createElement(
                'div'
            );


        div.textContent =
            value ?? '';


        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    function showSuccess(
        message
    )
    {

        lookupStatus.textContent =
            message;

        lookupStatus.className =
            'lookup-status lookup-success';

    }


    function showError(
        message
    )
    {

        lookupStatus.textContent =
            message;

        lookupStatus.className =
            'lookup-status lookup-error';

    }


    function showWarning(
        message
    )
    {

        lookupStatus.textContent =
            message;

        lookupStatus.className =
            'lookup-status lookup-warning';

    }


    function clearStatus()
    {

        lookupStatus.textContent =
            '';

        lookupStatus.className =
            'lookup-status';

    }


    /*
    |--------------------------------------------------------------------------
    | FIND COURSE
    |--------------------------------------------------------------------------
    */

    function findCourse(
        code
    )
    {

        const searchCode =
            String(code)
                .trim()
                .toUpperCase();


        if (!searchCode) {

            return [];

        }


        return facultySubjects.filter(
            function(item)
            {

                return String(
                    item.course_code
                )
                .trim()
                .toUpperCase()
                ===
                searchCode;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COURSE CODE LOOKUP
    |--------------------------------------------------------------------------
    */

    courseCodeInput.addEventListener(
        'input',
        function()
        {

            const code =
                this.value
                    .trim()
                    .toUpperCase();


            subjectInput.value =
                '';

            yearLevelInput.value =
                '';

            instructorInput.value =
                '';

                majorInput.value =
    '';

     programInput.value =
    '';

            subjectTypeInput.value = 
                '';

            if (subjectMatchSelector) {
                subjectMatchSelector.hidden = true;
                subjectMatchSelector.replaceChildren(new Option('Choose the matching year level and instructor', ''));
            }
            if (sharedAudiencePicker) sharedAudiencePicker.hidden = true;
            sharedAudienceChoices?.replaceChildren();

            clearStatus();


            if (!code) {

                return;

            }


            const matches =
                findCourse(code);


            if (
                matches.length === 0
            ) {

                showError(
                    'Course code "' +
                    code +
                    '" was not found in Instructor Setup.'
                );

                return;

            }


            if (matches.length > 1) {
                const yearLevels = [...new Set(matches.map(item => item.year_level))];
                showWarning(yearLevels.length > 1
                    ? 'This course code is used in multiple year levels. Choose the correct year level and instructor below.'
                    : 'This course code is assigned to more than one instructor. Choose the correct assignment below.');
                subjectMatchSelector.replaceChildren(new Option('Choose the matching year level and instructor', ''));
                matches.forEach(item => {
                    const details = [item.year_level, item.program, item.instructor, item.major ? `Major: ${item.major}` : ''].filter(Boolean);
                    subjectMatchSelector.add(new Option(details.join(' · '), String(item.id)));
                });
                subjectMatchSelector.hidden = false;
                return;
            }

            if (subjectMatchSelector) subjectMatchSelector.hidden = true;
            applyFacultySubject(matches[0]);

        }
    );

    function displayProgramCode(program) {
        return String(program || '')
            .split(/\s*\/\s*/)
            .map(function (item) {
                const value = item.trim();
                const match = value.match(/\(([^()]*)\)\s*$/);
                return match ? match[1].trim() : value;
            })
            .filter(Boolean)
            .join(' / ');
    }

    function applyFacultySubject(match, savedSharedIds = null) {
            if (!match) return;

// Use the subject type confirmed when the faculty assignment was created.
const automaticSubjectType =
    match.subject_type || (/\.1\s*$/.test(match.course_code || '') ? 'Lab' : 'Lec');

subjectTypeInput.value =
    automaticSubjectType;

            subjectInput.value =
                match.subject;


            yearLevelInput.value =
                match.year_level;


            instructorInput.value =
                match.instructor;

            majorInput.value =
                match.major || '';

            programInput.value =
                match.program || '';

            renderSharedAudienceOptions(match, savedSharedIds ?? selectedCell?.dataset.sharedOfferingIds ?? '');

            showSuccess(

                'Course found: ' +
                match.subject +
                ' — ' +
                match.instructor +
                ' — ' +
                match.year_level

            );
    }

    subjectMatchSelector?.addEventListener('change', function () {
        const selected = facultySubjects.find(item => String(item.id) === this.value);
        applyFacultySubject(selected);
    });

    function renderSharedAudienceOptions(primary) {
        if (!sharedAudiencePicker || !sharedAudienceChoices) return;
        sharedAudienceChoices.replaceChildren();
        if (!primary) {
            sharedAudiencePicker.hidden = true;
            return;
        }
        const cohorts = Array.isArray(primary.shared_cohorts) ? primary.shared_cohorts : [];
        sharedAudiencePicker.hidden = cohorts.length === 0;
        cohorts.forEach(item => {
            const details = document.createElement('div');
            details.className = 'shared-audience-option';
            details.textContent = [item.program_code || item.program, item.major ? 'Major: ' + item.major : '', item.year_level, item.semester].filter(Boolean).join(' · ');
            sharedAudienceChoices.appendChild(details);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | SELECT CELL
    |--------------------------------------------------------------------------
    */

    function selectCell(
        cell,
        announceCourse = true,
        allowLocked = false
    )
    {

        if (
            !cell.classList.contains(
                'schedule-cell'
            )
        ) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | BLOCK OTHER DEPARTMENT
        |--------------------------------------------------------------------------
        */

        if (
            !allowLocked &&
            cell.dataset.locked ===
            '1'
        ) {

            alert(
                'This schedule belongs to another college and is locked.'
            );

            return;

        }


        selectedCell =
            cell;

        let valueCell = cell;
        if (cell.dataset.manualMergeId && !cell.dataset.course) {
            valueCell = Array.from(document.querySelectorAll('#scheduleTable .schedule-cell'))
                .find(function(member) {
                    return member.dataset.manualMergeId === cell.dataset.manualMergeId
                        && member.dataset.course;
                }) || cell;
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD CELL DATA
        |--------------------------------------------------------------------------
        */

        courseCodeInput.value =
            valueCell.dataset.course || '';


        subjectInput.value =
            valueCell.dataset.subject || '';


        yearLevelInput.value =
            valueCell.dataset.year || '';


        instructorInput.value =
            valueCell.dataset.instructor || '';

                       majorInput.value =
    valueCell.dataset.major || '';
    

        programInput.value =
            valueCell.dataset.program || '';

 
            subjectTypeInput.value =
    valueCell.dataset.subjectType || '';

        colorInput.value =
            valueCell.dataset.color ||
            '#ffffff';


        clearStatus();


        /*
        |--------------------------------------------------------------------------
        | COURSE INFORMATION
        |--------------------------------------------------------------------------
        */

        if (
            courseCodeInput.value
        ) {

            const matches =
                findCourse(
                    courseCodeInput.value
                );


            if (
                matches.length === 1 && announceCourse
            ) {

                showSuccess(

                    matches[0].subject +
                    ' — ' +
                    matches[0].instructor +
                    ' — ' +
                    matches[0].year_level

                );

            }

        }


    }


    /*
    |--------------------------------------------------------------------------
    | ATTACH EVENTS
    |--------------------------------------------------------------------------
    */

   function attachCellEvents()
{

    document
        .querySelectorAll(
            '#scheduleTable .schedule-cell'
        )
        .forEach(
            function(cell)
            {

                cell.tabIndex = 0;
                cell.setAttribute(
                    'aria-selected',
                    cell.classList.contains('selected-cell') ? 'true' : 'false'
                );

                cell.onclick =
                    function(event)
                    {
                        activeAutoMergeCells = [];

                        // Controls inside a schedule cell keep their own click behavior.
                        if (event.target.closest('[data-merge-request-button]')) {
                            return;
                        }

                        closeScheduleEntryPopup();
                        selectedScheduleEntry = null;
                        selectedScheduleInterval = event.isTrusted
                            ? availableHalfHourAtPointer(this, event.clientY)
                            : null;

                        // Do nothing if the cell has a faculty reservation
                        if (
                            this.classList.contains(
                                'reserved-cell'
                            )
                        ) {
                            alert(
                                'This timetable cell is blocked by a instructor room reservation and cannot be scheduled.'
                            );
                            return;
                        }

                        // Do nothing if the cell is locked
                        if (
                            this.classList.contains(
                                'locked-cell'
                            )
                        ) {
                            return;
                        }

                        const isSelected = this.classList.contains('selected-cell');

                        if (isSelected) {
                            this.classList.remove('selected-cell');
                            this.setAttribute('aria-selected', 'false');

                            const remainingCells = Array.from(document.querySelectorAll(
                                '#scheduleTable .schedule-cell.selected-cell'
                            ));
                            selectedCell = remainingCells.length
                                ? remainingCells[remainingCells.length - 1]
                                : null;

                            if (selectedCell) {
                                selectCell(selectedCell, false);
                            }
                        } else {
                            this.classList.add('selected-cell');
                            this.setAttribute('aria-selected', 'true');
                            selectCell(this);
                        }

                        updateMergeControls();
                        renderScheduleBlocks();

                    };

                cell.oncontextmenu = function(event) {
                    if (this.classList.contains('reserved-cell')) return;
                    if (this.classList.contains('locked-cell')) {
                        if (!document.body.classList.contains('cross-scheduling-editor') || !this.dataset.scheduleId) return;
                        event.preventDefault();
                        selectCell(this, false, true);
                        const entries = getCellScheduleEntries(this);
                        const entry = entries.find(item => String(item.schedule_id || '') === String(this.dataset.scheduleId)) || entries[0];
                        if (!entry) return;
                        selectedScheduleEntry = entry;
                        selectedScheduleInterval = null;
                        openScheduleEntryPopup(this, entry, null);
                        return;
                    }

                    event.preventDefault();
                    if (!this.classList.contains('selected-cell')) {
                        this.click();
                    }

                    selectedScheduleEntry = null;
                    selectedScheduleInterval = availableHalfHourAtPointer(this, event.clientY);
                    openScheduleEntryPopup(this, null, selectedScheduleInterval);
                };

                cell.onkeydown =
                    function(event)
                    {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            this.click();
                        }
                    };

            }
        );

}



    /*
    |--------------------------------------------------------------------------
    | MANUAL CELL MERGING
    |--------------------------------------------------------------------------
    */

    let manualMergeCounter = 0;

    const mergeSelectedCellsButton =
        document.getElementById('mergeSelectedCellsButton');

    const unmergeSelectedCellsButton =
        document.getElementById('unmergeSelectedCellsButton');

    const persistedManualCellMerges = @json($manualCellMerges ?? []);
    const persistedScheduleEntriesByCell = @json($scheduleEntriesByCell ?? []);

    function scheduleEntryFromCell(cell)
    {
        return {
            schedule_id: String(cell.dataset.scheduleId || ''),
            day: String(cell.dataset.day || '').toUpperCase(),
            time: String(cell.dataset.scheduleTime || cell.dataset.time || '').trim(),
            base_time: String(cell.dataset.scheduleBaseTime || cell.dataset.time || '').trim(),
            row_position: Number(cell.dataset.rowPosition || 0),
            course: String(cell.dataset.course || ''),
            subject: String(cell.dataset.subject || ''),
            year: String(cell.dataset.year || ''),
            major: String(cell.dataset.major || ''),
            program: String(cell.dataset.program || ''),
            instructor: String(cell.dataset.instructor || ''),
            faculty_id: String(cell.dataset.facultyId || ''),
            faculty_subject_id: String(cell.dataset.facultySubjectId || ''),
            shared_offering_ids: String(cell.dataset.sharedOfferingIds || '').split(',').filter(Boolean),
            department_id: String(cell.dataset.departmentId || ''),
            color: String(cell.dataset.color || '#ffffff'),
            subject_type: String(cell.dataset.subjectType || ''),
        };
    }

    function getCellScheduleEntries(cell)
    {
        if (!Array.isArray(cell._scheduleEntries)) {
            const key = String(cell.dataset.day || '').toUpperCase() + '|' + String(cell.dataset.rowPosition || '');
            const persisted = persistedScheduleEntriesByCell[key];
            cell._scheduleEntries = Array.isArray(persisted)
                ? persisted.map(entry => ({ ...entry }))
                : (String(cell.dataset.course || '').trim() ? [scheduleEntryFromCell(cell)] : []);
        }

        if (cell._scheduleEntries.length && String(cell.dataset.course || '').trim()) {
            Object.assign(cell._scheduleEntries[0], scheduleEntryFromCell(cell));
        }

        cell._scheduleEntries.forEach(entry => { entry.cell = cell; });

        return cell._scheduleEntries;
    }

    function syncCellPrimaryEntry(cell)
    {
        const entries = getCellScheduleEntries(cell);
        if (!String(cell.dataset.course || '').trim()) {
            cell._scheduleEntries = entries.filter(entry => String(entry.course || '').trim());
            return;
        }

        const primary = scheduleEntryFromCell(cell);
        if (entries.length) Object.assign(entries[0], primary);
        else cell._scheduleEntries = [primary];
    }

    function applyScheduleEntryToCell(cell, entry)
    {
        cell.dataset.scheduleId = entry.schedule_id || '';
        cell.dataset.course = entry.course || '';
        cell.dataset.subject = entry.subject || '';
        cell.dataset.year = entry.year || '';
        cell.dataset.major = entry.major || '';
        cell.dataset.program = entry.program || '';
        cell.dataset.instructor = entry.instructor || '';
        cell.dataset.facultyId = entry.faculty_id || '';
        cell.dataset.facultySubjectId = entry.faculty_subject_id || '';
        cell.dataset.sharedOfferingIds = Array.isArray(entry.shared_offering_ids)
            ? entry.shared_offering_ids.join(',')
            : String(entry.shared_offering_ids || '');
        cell.dataset.departmentId = entry.department_id || '';
        cell.dataset.color = entry.color || '#ffffff';
        cell.dataset.subjectType = entry.subject_type || '';
        cell.dataset.scheduleTime = entry.time || cell.dataset.time || '';
        cell.dataset.scheduleBaseTime = entry.base_time || cell.dataset.time || '';
        cell.dataset.rowPosition = String(entry.row_position ?? cell.dataset.rowPosition ?? '');
        cell.style.backgroundColor = cell.dataset.color;

        const subjectType = entry.subject_type
            ? '<div class="cell-subject-type">' + escapeHtml(entry.subject_type) + '</div>'
            : '';
        cell.innerHTML = '<div class="schedule-cell-content">'
            + '<div class="cell-course">' + escapeHtml(entry.course || '') + '</div>'
            + '<div class="cell-subject">' + escapeHtml(entry.subject || '') + '</div>'
            + '<div class="cell-instructor">' + escapeHtml(entry.instructor || '') + '</div>'
            + '<div class="cell-year">' + escapeHtml(entry.year || '') + '</div>'
            + '<div class="cell-major">' + escapeHtml(entry.major || '') + '</div>'
            + '<div class="cell-program">' + escapeHtml(displayProgramCode(entry.program || '')) + '</div>'
            + subjectType + '</div>';
    }

    document.querySelectorAll('#scheduleTable .schedule-cell').forEach(function(cell) {
        getCellScheduleEntries(cell);
    });

    function getLogicalScheduleCells(cells)
    {
        const logicalCells = new Set();

        cells.forEach(function(cell) {
            const mergeId = cell.dataset.manualMergeId || '';

            if (!mergeId) {
                logicalCells.add(cell);
                return;
            }

            document.querySelectorAll('#scheduleTable .schedule-cell').forEach(function(member) {
                if (member.dataset.manualMergeId === mergeId) {
                    logicalCells.add(member);
                }
            });
        });

        return Array.from(logicalCells);
    }

    function getManualMergeMembers(cell)
    {
        const mergeId = cell?.dataset.manualMergeId || '';
        if (!mergeId) return cell ? [cell] : [];

        return Array.from(document.querySelectorAll('#scheduleTable .schedule-cell'))
            .filter(function(member) { return member.dataset.manualMergeId === mergeId; })
            .sort(function(a, b) {
                return Array.from(a.parentElement.parentElement.children).indexOf(a.parentElement)
                    - Array.from(b.parentElement.parentElement.children).indexOf(b.parentElement);
            });
    }

    function scheduleEntryTimeForCell(cell)
    {
        if (selectedScheduleEntry && selectedScheduleEntry.cell === cell && selectedScheduleEntry.time) {
            return String(selectedScheduleEntry.time).trim();
        }
        if (selectedScheduleInterval && selectedScheduleInterval.cell === cell) {
            return formatScheduleRange(selectedScheduleInterval.start, selectedScheduleInterval.end);
        }

        const members = getManualMergeMembers(cell);
        if (cell?.dataset.manualMergeAxis !== 'vertical' || members.length < 2) {
            return String(cell?.dataset.scheduleTime || cell?.dataset.time || '').trim();
        }

        const currentRange = parseScheduleRange(cell.dataset.scheduleTime || '');
        const ownBaseRange = parseScheduleRange(cell.dataset.time || cell.dataset.scheduleBaseTime || '');
        if (cell.dataset.course && currentRange && ownBaseRange
            && (currentRange.start !== ownBaseRange.start || currentRange.end !== ownBaseRange.end)) {
            return formatScheduleRange(currentRange.start, currentRange.end);
        }

        const ranges = members.map(function(member) {
            return parseScheduleRange(member.dataset.time || member.dataset.scheduleBaseTime || '');
        }).filter(Boolean);
        if (ranges.length !== members.length) {
            return String(cell.dataset.scheduleTime || cell.dataset.time || '').trim();
        }

        return formatScheduleRange(ranges[0].start, ranges[ranges.length - 1].end);
    }

    function getScheduleAssignmentCells(cells)
    {
        if (selectedScheduleEntry?.cell && selectedCell && cells.includes(selectedCell)) {
            return [selectedScheduleEntry.cell];
        }
        if (selectedScheduleInterval && selectedCell && cells.includes(selectedCell)) {
            return [selectedCell];
        }

        const assignments = new Set();
        cells.forEach(function(cell) {
            const members = getManualMergeMembers(cell);
            if (cell.dataset.manualMergeAxis === 'vertical' && members.length > 1) {
                assignments.add(members[0]);
                return;
            }
            members.forEach(function(member) { assignments.add(member); });
        });
        return Array.from(assignments);
    }

    function clearScheduleEntryFromCell(cell)
    {
        cell._scheduleEntries = [];
        ['scheduleId', 'course', 'subject', 'year', 'major', 'program', 'instructor', 'facultyId',
            'facultySubjectId', 'sharedOfferingIds', 'departmentId', 'subjectType'].forEach(function(key) {
            cell.dataset[key] = '';
        });
        cell.dataset.scheduleTime = cell.dataset.time || '';
        cell.dataset.scheduleBaseTime = cell.dataset.time || '';
        cell.dataset.scheduleStartMinute = '';
        cell.dataset.scheduleEndMinute = '';
        cell.dataset.color = '#ffffff';
        cell.style.backgroundColor = '#ffffff';
        cell.innerHTML = '<div class="schedule-cell-content"><span class="empty-cell">Empty</span></div>';
    }

    function updateMergeControls()
    {
        if (!mergeSelectedCellsButton || !unmergeSelectedCellsButton) {
            return;
        }

        const selected = Array.from(document.querySelectorAll(
            '#scheduleTable .schedule-cell.selected-cell'
        ));

        mergeSelectedCellsButton.disabled = selected.length < 2 || selected.some(function(cell) {
            return cell.dataset.manualMergeId || cell.dataset.locked === '1' || cell.dataset.reservationId;
        });

        unmergeSelectedCellsButton.disabled = !selected.some(function(cell) {
            return Boolean(cell.dataset.manualMergeId);
        });

        if (selectedCellMergeControls) selectedCellMergeControls.hidden = selected.length === 0;
        if (cellColorControl) cellColorControl.hidden = selected.length === 0;
        if (clearCellButton) {
            clearCellButton.hidden = !selected.some(function(cell) {
                return getLogicalScheduleCells([cell]).some(function(member) {
                    return Boolean(member.dataset.course);
                });
            });
        }
    }

    function getMergeSelection()
    {
        const selected = Array.from(document.querySelectorAll(
            '#scheduleTable .schedule-cell.selected-cell'
        ));

        if (selected.length < 2 || selected.some(function(cell) {
            return cell.dataset.manualMergeId || cell.dataset.locked === '1' || cell.dataset.reservationId;
        })) {
            return null;
        }

        const first = selected[0];
        const sameRow = selected.every(function(cell) {
            return cell.parentElement === first.parentElement && cell.dataset.side === first.dataset.side;
        });

        if (sameRow) {
            const rowCells = Array.from(first.parentElement.querySelectorAll('.schedule-cell')).filter(function(cell) {
                return cell.dataset.side === first.dataset.side;
            });
            const positions = selected.map(function(cell) { return rowCells.indexOf(cell); }).sort(function(a, b) { return a - b; });

            if (positions.every(function(position, index) { return position >= 0 && (index === 0 || position === positions[index - 1] + 1); })) {
                return {
                    axis: 'horizontal',
                    cells: rowCells.slice(positions[0], positions[positions.length - 1] + 1),
                    colCount: positions.length
                };
            }
        }

        const day = first.dataset.day;
        const side = first.dataset.side;
        if (!selected.every(function(cell) { return cell.dataset.day === day && cell.dataset.side === side; })) {
            return null;
        }

        const rows = Array.from(document.getElementById('scheduleBody').rows);
        const rowPositions = selected.map(function(cell) { return rows.indexOf(cell.parentElement); }).sort(function(a, b) { return a - b; });
        if (rowPositions.some(function(position, index) {
            return position < 0 || (index > 0 && position !== rowPositions[index - 1] + 1);
        })) {
            return null;
        }

        const start = rowPositions[0];
        const end = rowPositions[rowPositions.length - 1];
        const orderedCells = [];
        for (let rowIndex = start; rowIndex <= end; rowIndex++) {
            const row = rows[rowIndex];
            if (row.classList.contains('break-row') || row.classList.contains('add-time-before-break-row')) {
                return null;
            }
            const cell = Array.from(row.querySelectorAll('.schedule-cell')).find(function(candidate) {
                return candidate.dataset.day === day && candidate.dataset.side === side;
            });
            if (!cell) return null;
            orderedCells.push(cell);
        }

        if (orderedCells.length !== selected.length || orderedCells.some(function(cell) { return !selected.includes(cell); })) {
            return null;
        }

        return { axis: 'vertical', cells: orderedCells, rowCount: orderedCells.length };
    }

    function mergeSelectedCells()
    {
        const selection = getMergeSelection();

        if (!selection) {
            showWarning('Select adjacent cells in a straight horizontal or vertical line.');
            return;
        }

        const mergeId = 'manual-' + Date.now() + '-' + (++manualMergeCounter);
        const anchor = selection.cells[0];

        selection.cells.forEach(function(cell, index) {
            cell.dataset.manualMergeId = mergeId;
            cell.dataset.manualMergeAxis = selection.axis;
            if (index > 0) {
                cell.hidden = true;
                cell.setAttribute('aria-hidden', 'true');
            }
        });

        if (selection.axis === 'horizontal') anchor.colSpan = selection.colCount;
        if (selection.axis === 'vertical') anchor.rowSpan = selection.rowCount;
        anchor.classList.add('manual-merged-schedule-cell');

        document.querySelectorAll('#scheduleTable .schedule-cell.selected-cell').forEach(function(cell) {
            cell.classList.remove('selected-cell');
            cell.setAttribute('aria-selected', 'false');
        });

        selectedCell = null;
        updateMergeControls();
        renderScheduleBlocks();
        showSuccess('Cells merged. Their individual schedule entries will be kept.');
    }

    function unmergeSelectedCells()
    {
        const mergeIds = new Set(Array.from(document.querySelectorAll(
            '#scheduleTable .schedule-cell.selected-cell'
        )).map(function(cell) { return cell.dataset.manualMergeId; }).filter(Boolean));

        if (mergeIds.size === 0) {
            return;
        }

        document.querySelectorAll('#scheduleTable .schedule-cell').forEach(function(cell) {
            if (!mergeIds.has(cell.dataset.manualMergeId)) {
                return;
            }

            cell.hidden = false;
            cell.removeAttribute('aria-hidden');
            cell.removeAttribute('colspan');
            cell.removeAttribute('rowspan');
            cell.classList.remove('manual-merged-schedule-cell', 'selected-cell');
            cell.setAttribute('aria-selected', 'false');
            delete cell.dataset.manualMergeId;
            delete cell.dataset.manualMergeAxis;
        });

        selectedCell = null;
        updateMergeControls();
        renderScheduleBlocks();
        showSuccess('Cells unmerged. Their original contents are visible again.');
    }

    mergeSelectedCellsButton?.addEventListener('click', mergeSelectedCells);
    unmergeSelectedCellsButton?.addEventListener('click', unmergeSelectedCells);

    function restoreManualCellMerges()
    {
        const cells = Array.from(document.querySelectorAll('#scheduleTable .schedule-cell'));

        (persistedManualCellMerges || []).forEach(function(merge, mergeIndex) {
            const coordinates = Array.isArray(merge.cells) ? merge.cells : [];
            const claimedCells = new Set();
            const members = coordinates.map(function(coordinate) {
                const day = String(coordinate.day || '').toUpperCase();
                const side = String(coordinate.side || '').toLowerCase();
                const expectedTime = String(coordinate.time || '').trim().replace(/\s+/g, ' ');
                const candidates = cells.filter(function(cell) {
                    return cell.dataset.day === day
                        && cell.dataset.side === side
                        && !claimedCells.has(cell);
                });

                if (expectedTime) {
                    const matchingTimeCells = candidates.filter(function(cell) {
                        const timeCell = cell.parentElement?.querySelector('.editable-time[data-side="' + side + '"]');
                        return String(timeCell?.innerText || '').trim().replace(/\s+/g, ' ') === expectedTime;
                    });
                    matchingTimeCells.sort(function(a, b) {
                        return Math.abs(Number(a.dataset.rowPosition) - Number(coordinate.row_position))
                            - Math.abs(Number(b.dataset.rowPosition) - Number(coordinate.row_position));
                    });
                    if (matchingTimeCells[0]) {
                        claimedCells.add(matchingTimeCells[0]);
                        return matchingTimeCells[0];
                    }
                }

                const positionMatch = candidates.find(function(cell) {
                    return cell.dataset.day === coordinate.day
                        && cell.dataset.side === coordinate.side
                        && Number(cell.dataset.rowPosition) === Number(coordinate.row_position);
                });
                if (positionMatch) claimedCells.add(positionMatch);
                return positionMatch;
            });

            if (members.length < 2 || members.some(function(cell) { return !cell; })) {
                return;
            }

            const mergeId = 'manual-restored-' + mergeIndex;
            members.forEach(function(cell, index) {
                cell.dataset.manualMergeId = mergeId;
                cell.dataset.manualMergeAxis = merge.axis;
                if (index > 0) {
                    cell.hidden = true;
                    cell.setAttribute('aria-hidden', 'true');
                }
            });

            const anchor = members[0];
            if (merge.axis === 'horizontal') anchor.colSpan = members.length;
            if (merge.axis === 'vertical') anchor.rowSpan = members.length;
            anchor.classList.add('manual-merged-schedule-cell');
        });
    }

    restoreManualCellMerges();
    attachCellEvents();
    renderScheduleBlocks();
    updateMergeControls();
    /*
|--------------------------------------------------------------------------
| SAVE CELL
|--------------------------------------------------------------------------
*/

async function saveCell(allowSameCourseConflict = false)
{
    const selectedCells =
        Array.from(
            document.querySelectorAll(
                '#scheduleTable .schedule-cell.selected-cell'
            )
        );

    /*
    |--------------------------------------------------------------------------
    | CHECK SELECTION
    |--------------------------------------------------------------------------
    */

    if (selectedCells.length === 0) {

        alert(
            'Please select at least one schedule cell first.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | GET INPUTS
    |--------------------------------------------------------------------------
    */

    const course =
        courseCodeInput.value
            .trim()
            .toUpperCase();


    const color =
        colorInput.value;


    /*
    |--------------------------------------------------------------------------
    | COURSE REQUIRED
    |--------------------------------------------------------------------------
    */

    if (!course) {

        alert(
            'Please enter a course code.'
        );

        courseCodeInput.focus();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | FIND COURSE
    |--------------------------------------------------------------------------
    */

    const matches =
        findCourse(course);


    if (
        matches.length === 0
    ) {

        alert(
            'Course code "' +
            course +
            '" was not found. Add the course under Set Instructor first.'
        );

        return;
    }


    const match = matches.length === 1
        ? matches[0]
        : matches.find(item => String(item.id) === String(subjectMatchSelector?.value || ''));

    if (!match) {
        alert('Choose the correct year level and instructor assignment for this course code first.');
        subjectMatchSelector?.focus();
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SUBJECT TYPE
    |--------------------------------------------------------------------------
    */

    const subjectType = match.subject_type || (/\.1\s*$/.test(course) ? 'Lab' : 'Lec');
    const sharedOfferingIds = Array.from(sharedAudienceChoices?.querySelectorAll('input[type="checkbox"]:checked') || [])
        .map(input => String(input.value))
        .filter(id => {
            const offering = facultySubjects.find(item => String(item.id) === id);
            return offering
                && String(offering.faculty_id || '') === String(match.faculty_id || '')
                && String(offering.course_code || '').trim().toUpperCase() === String(match.course_code || '').trim().toUpperCase();
        });


    /*
    |--------------------------------------------------------------------------
    | CHECK LOCKED CELLS
    |--------------------------------------------------------------------------
    */

    const isEditingAutoMergedEntry = selectedScheduleEntry?.cell
        && activeAutoMergeCells.length > 1
        && autoMergedEntryIsStillExact(activeAutoMergeCells)
        && activeAutoMergeCells.includes(selectedScheduleEntry.cell);
    const lockedCells =
        selectedCells.filter(
            cell =>
                cell.dataset.locked === '1'
        );


    if (lockedCells.length > 0) {

        const reservationCells =
            lockedCells.filter(
                cell => cell.dataset.reservationId
            );

        if (reservationCells.length > 0) {
            alert(
                'One or more selected cells have a instructor room reservation. The reservation must end before this slot can be scheduled.'
            );
        } else {
            alert(
                'One or more selected cells belong to another college and are locked.'
            );
        }

        return;
    }

    const confirmButton = document.getElementById('confirmScheduleEntryButton');
    if (confirmButton?.disabled) return;

    if (confirmButton) {
        confirmButton.disabled = true;
        confirmButton.textContent = 'Checking…';
    }

    try {
        const candidateCells = isEditingAutoMergedEntry
            ? activeAutoMergeCells
            : getScheduleAssignmentCells(selectedCells);
        const response = await fetch(scheduleConflictCheckUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': scheduleForm.querySelector('input[name="_token"]')?.value || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                room_id: scheduleForm.querySelector('input[name="room_id"]')?.value,
                term_id: scheduleConflictTermId,
                faculty_subject_id: match.id,
                course_code: match.course_code,
                allow_same_course_conflict: allowSameCourseConflict,
                slots: candidateCells.map(cell => ({
                    day: String(cell.dataset.day || '').toUpperCase(),
                    time: scheduleEntryTimeForCell(cell),
                    schedule_id: selectedScheduleInterval?.cell === cell
                        ? null
                        : (selectedScheduleEntry?.cell === cell
                            ? (selectedScheduleEntry.schedule_id || null)
                            : (cell.dataset.scheduleId || null)),
                })),
            }),
        });
        const result = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(result.message || 'Unable to verify instructor availability. Please try again.');
        }

        if (result.conflict) {
            const enteredCourseCode = String(match.course_code || '').trim().toUpperCase();
            const conflictingCourseCode = String(result.conflict_schedule?.course_code || '').trim().toUpperCase();
            if (!allowSameCourseConflict
                && result.can_override_same_course
                && enteredCourseCode
                && enteredCourseCode === conflictingCourseCode) {
                if (sameCourseConflictMessage) {
                    sameCourseConflictMessage.textContent = result.message || 'This instructor already has this same course scheduled at this time.';
                }
                if (sameCourseConflictConfirm) sameCourseConflictConfirm.hidden = false;
                return;
            }
            showError(result.message || 'This instructor is already scheduled at that time.');
            return;
        }
    } catch (error) {
        showError(error.message || 'Unable to verify instructor availability. Please try again.');
        return;
    } finally {
        if (confirmButton) {
            confirmButton.disabled = false;
            confirmButton.textContent = 'Confirm Entry';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | APPLY COURSE TO ALL SELECTED CELLS
    |--------------------------------------------------------------------------
    */

    const assignmentCells = isEditingAutoMergedEntry
        ? activeAutoMergeCells
        : getScheduleAssignmentCells(selectedCells);
    const assignmentTimes = new Map(assignmentCells.map(function(cell) {
        return [cell, scheduleEntryTimeForCell(cell)];
    }));

    assignmentCells.forEach(
        function(cell)
        {
            const assignedTime = assignmentTimes.get(cell) || cell.dataset.time || '';
            const assignedRange = parseScheduleRange(assignedTime);
            const assignedPosition = assignedRange
                ? scheduleRowPositionAtMinute(cell.dataset.day, cell.dataset.side, assignedRange.start)
                : null;

            const editingEntryForCell = selectedScheduleEntry && selectedScheduleEntry.cell === cell
                ? selectedScheduleEntry
                : (isEditingAutoMergedEntry
                    ? getCellScheduleEntries(cell).find(entry => String(entry.course || '').trim())
                    : null);
            if (editingEntryForCell) {
                Object.assign(editingEntryForCell, {
                    time: assignedTime,
                    base_time: editingEntryForCell.base_time || cell.dataset.time || '',
                    row_position: assignedPosition?.index ?? Number(cell.dataset.rowPosition || 0),
                    course: match.course_code,
                    subject: match.subject,
                    year: match.year_level,
                    major: match.major || '',
                    program: match.program || '',
                    instructor: match.instructor,
                    faculty_id: match.faculty_id || '',
                    faculty_subject_id: match.id || '',
                    shared_offering_ids: sharedOfferingIds,
                    department_id: chairDepartmentId,
                    color: color,
                    subject_type: subjectType,
                });

                if (getCellScheduleEntries(cell)[0] === editingEntryForCell) {
                    applyScheduleEntryToCell(cell, editingEntryForCell);
                }
                return;
            }

            const currentEntries = getCellScheduleEntries(cell);
            if (selectedScheduleInterval?.cell === cell && currentEntries.length > 0) {
                const newEntry = {
                    schedule_id: '',
                    day: String(cell.dataset.day || '').toUpperCase(),
                    time: assignedTime,
                    base_time: cell.dataset.time || '',
                    row_position: assignedPosition?.index ?? Number(cell.dataset.rowPosition || 0),
                    course: match.course_code,
                    subject: match.subject,
                    year: match.year_level,
                    major: match.major || '',
                    program: match.program || '',
                    instructor: match.instructor,
                    faculty_id: match.faculty_id || '',
                    faculty_subject_id: match.id || '',
                    shared_offering_ids: sharedOfferingIds,
                    department_id: chairDepartmentId,
                    color: color,
                    subject_type: subjectType,
                    cell: cell,
                };
                cell._scheduleEntries.push(newEntry);
                return;
            }

            /*
            |------------------------------------------------------------------
            | SAVE DATA TO CELL
            |------------------------------------------------------------------
            */

            cell.dataset.course =
                match.course_code;

            cell.dataset.subject =
                match.subject;

            cell.dataset.year =
                match.year_level;

            cell.dataset.major =
                match.major || '';

            cell.dataset.program =
                match.program || '';

            cell.dataset.instructor =
                match.instructor;

            /*
            | Keep existing schedule ID.
            | New schedules remain blank until saved.
            */

            cell.dataset.scheduleId =
                cell.dataset.scheduleId || '';

            cell.dataset.facultyId =
                match.faculty_id || '';

            cell.dataset.facultySubjectId =
                match.id || '';

            cell.dataset.sharedOfferingIds = sharedOfferingIds.join(',');

            cell.dataset.departmentId =
                chairDepartmentId;

            cell.dataset.color =
                color;

            cell.dataset.subjectType =
                subjectType;

            cell.dataset.scheduleTime =
                assignmentTimes.get(cell) || cell.dataset.time || '';
            cell.dataset.scheduleBaseTime =
                cell.dataset.scheduleBaseTime || cell.dataset.time || '';

            if (cell.dataset.rowPosition === undefined || cell.dataset.rowPosition === '') {
                cell.dataset.rowPosition = String(scheduleGridRows().indexOf(cell.parentElement));
            }

            cell.dataset.locked =
                '0';


            /*
            |------------------------------------------------------------------
            | REMOVE LOCKED STATUS
            |------------------------------------------------------------------
            */

            cell.classList.remove(
                'locked-cell'
            );


            /*
            |------------------------------------------------------------------
            | DISPLAY
            |------------------------------------------------------------------
            */

            let subjectTypeDisplay = '';


            if (subjectType) {

                subjectTypeDisplay =

                    '<div class="cell-subject-type">' +

                    escapeHtml(
                        subjectType
                    ) +

                    '</div>';
            }


            cell.innerHTML =

                '<div class="schedule-cell-content">' +
                '<div class="cell-course">' +
                escapeHtml(
                    match.course_code
                ) +
                '</div>' +

                '<div class="cell-subject">' +
                escapeHtml(
                    match.subject
                ) +
                '</div>' +

                '<div class="cell-instructor">' +
                escapeHtml(
                    match.instructor
                ) +
                '</div>' +

                '<div class="cell-year">' +
                escapeHtml(
                    match.year_level
                ) +
                '</div>' +

                '<div class="cell-major">' +
                escapeHtml(
                    match.major || ''
                ) +
                '</div>' +

                '<div class="cell-program">' +
                escapeHtml(
                    displayProgramCode(match.program)
                ) +
                '</div>' +

                subjectTypeDisplay +
                '</div>';


            /*
            |------------------------------------------------------------------
            | BACKGROUND COLOR
            |------------------------------------------------------------------
            */

            cell.style.backgroundColor =
                color;

            syncCellPrimaryEntry(cell);

        }
    );

    selectedCells.forEach(function(selected) {
        if (selected.dataset.manualMergeAxis !== 'vertical' || !selected.dataset.manualMergeId) return;
        getManualMergeMembers(selected).slice(1).forEach(clearScheduleEntryFromCell);
    });


    /*
    |--------------------------------------------------------------------------
    | CLEAR SELECTION
    |--------------------------------------------------------------------------
    */

    selectedCells.forEach(
        function(cell)
        {
            cell.classList.remove(
                'selected-cell'
            );
            cell.setAttribute('aria-selected', 'false');
        }
    );


    selectedCell = null;
    selectedScheduleEntry = null;
    selectedScheduleInterval = null;
    activeAutoMergeCells = [];
    updateMergeControls();
    renderScheduleBlocks();

    /*
    |--------------------------------------------------------------------------
    | SUCCESS MESSAGE
    |--------------------------------------------------------------------------
    */

    showSuccess(

        'Saved: ' +
        match.course_code +
        ' — ' +
        match.subject +
        ' — ' +
        match.instructor

    );

    closeScheduleEntryPopup();
}
    /*
|--------------------------------------------------------------------------
| CLEAR CELL
|--------------------------------------------------------------------------
*/

async function clearCell()
{

    const selectedCells =
        Array.from(
            document.querySelectorAll(
                '#scheduleTable .schedule-cell.selected-cell'
            )
        );


    /*
    |--------------------------------------------------------------------------
    | CHECK SELECTION
    |--------------------------------------------------------------------------
    */

    if (selectedCells.length === 0) {

        alert(
            'Please select a schedule cell first.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK LOCKED CELLS
    |--------------------------------------------------------------------------
    */

    const lockedCells =
        selectedCells.filter(
            cell =>
                cell.dataset.locked === '1'
        );


    if (lockedCells.length > 0) {

        alert(
            'One or more selected cells belong to another college and are locked.'
        );

        return;
    }

    const cellsToClear = getLogicalScheduleCells(selectedCells);
    const isAutoMergedEntrySelection = selectedScheduleEntry?.cell
        && activeAutoMergeCells.length > 1
        && autoMergedEntryIsStillExact(activeAutoMergeCells)
        && activeAutoMergeCells.includes(selectedScheduleEntry.cell);
    const entriesToClear = isAutoMergedEntrySelection
        ? activeAutoMergeCells.flatMap(cell => getCellScheduleEntries(cell))
        : selectedScheduleEntry?.cell
            ? [selectedScheduleEntry]
            : cellsToClear.flatMap(cell => getCellScheduleEntries(cell));
    const savedScheduleIds = [...new Set(entriesToClear
        .map(entry => String(entry.schedule_id || '').trim())
        .filter(Boolean))];

    if (savedScheduleIds.length > 0) {
        const clearButton = document.getElementById('clearCellButton');
        const originalLabel = clearButton?.textContent || 'Clear Cell';
        if (clearButton) {
            clearButton.disabled = true;
            clearButton.textContent = 'Deleting…';
        }

        try {
            const response = await fetch(scheduleClearCellsUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': scheduleForm.querySelector('input[name="_token"]')?.value || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    room_id: scheduleForm.querySelector('input[name="room_id"]')?.value,
                    term_id: scheduleConflictTermId,
                    schedule_ids: savedScheduleIds,
                }),
            });
            const result = await response.json().catch(() => ({}));
            if (!response.ok) {
                throw new Error(result.message || 'The saved schedule could not be deleted. Refresh the spreadsheet and try again.');
            }
        } catch (error) {
            showError(error.message || 'The saved schedule could not be deleted. Please try again.');
            return;
        } finally {
            if (clearButton) {
                clearButton.disabled = false;
                clearButton.textContent = originalLabel;
            }
        }
    }

    if (selectedScheduleEntry?.cell) {
        const cell = selectedScheduleEntry.cell;
        const clearTargets = isAutoMergedEntrySelection ? activeAutoMergeCells : [cell];
        clearTargets.forEach(function(target) {
            if (isAutoMergedEntrySelection) {
                clearScheduleEntryFromCell(target);
                return;
            }
            const remainingEntries = getCellScheduleEntries(target).filter(entry => entry !== selectedScheduleEntry);
            target._scheduleEntries = remainingEntries;
            if (remainingEntries.length) applyScheduleEntryToCell(target, remainingEntries[0]);
            else clearScheduleEntryFromCell(target);
        });
        selectedScheduleEntry = null;
        selectedScheduleInterval = null;
        clearTargets.forEach(function(target) {
            target.classList.remove('selected-cell');
            target.setAttribute('aria-selected', 'false');
        });
        activeAutoMergeCells = [];
        selectedCell = null;
        updateMergeControls();
        renderScheduleBlocks();
        showSuccess('The selected schedule entry was cleared.');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR ALL SELECTED CELLS
    |--------------------------------------------------------------------------
    */

    cellsToClear.forEach(
        function(cell)
        {

            cell._scheduleEntries = [];

            cell.dataset.scheduleId = '';

            cell.dataset.course =
                '';

            cell.dataset.subject =
                '';

            cell.dataset.subjectType =
                '';

            cell.dataset.year =
                '';

            cell.dataset.major =
                '';

            cell.dataset.instructor =
                '';

            cell.dataset.facultyId =
                '';

            cell.dataset.facultySubjectId =
                '';

            cell.dataset.sharedOfferingIds = '';

            cell.dataset.departmentId =
                '';

            cell.dataset.program =
                '';

            cell.dataset.color =
                '#ffffff';

            cell.dataset.scheduleTime = '';
            cell.dataset.scheduleBaseTime = '';
            cell.dataset.scheduleStartMinute = '';
            cell.dataset.scheduleEndMinute = '';

            cell.dataset.locked =
                '0';


            cell.classList.remove(
                'locked-cell'
            );


            cell.style.backgroundColor =
                '#ffffff';


            cell.innerHTML =
                '<span class="empty-cell">Empty</span>';


            /*
            | Remove selection highlight
            */

            cell.classList.remove(
                'selected-cell'
            );
            cell.setAttribute('aria-selected', 'false');

        }
    );


    selectedCell = null;
    updateMergeControls();
    renderScheduleBlocks();

    /*
    |--------------------------------------------------------------------------
    | CLEAR INPUT FIELDS
    |--------------------------------------------------------------------------
    */

    courseCodeInput.value =
        '';

    subjectInput.value =
        '';

    subjectTypeInput.value =
        '';

    yearLevelInput.value =
        '';

    majorInput.value =
        '';

    instructorInput.value =
        '';

    programInput.value =
        '';

    colorInput.value =
        '#ffffff';


    /*
    |--------------------------------------------------------------------------
    | CLEAR STATUS
    |--------------------------------------------------------------------------
    */

    clearStatus();

    if (savedScheduleIds.length > 0) {
        showSuccess(savedScheduleIds.length === 1
            ? 'The published schedule was deleted from the database.'
            : savedScheduleIds.length + ' published schedules were deleted from the database.');
    } else {
        showSuccess('The selected unsaved cells are clear.');
    }

    closeScheduleEntryPopup();

}

    /*
    |--------------------------------------------------------------------------
    | COLOR
    |--------------------------------------------------------------------------
    */

    colorInput.addEventListener(
        'input',
        function()
        {

            if (!selectedCell) {

                return;

            }


            if (
                selectedCell.dataset.locked ===
                '1'
            ) {

                return;

            }

            if (selectedScheduleEntry?.cell) {
                selectedScheduleEntry.color = this.value;
                if (getCellScheduleEntries(selectedScheduleEntry.cell)[0] === selectedScheduleEntry) {
                    selectedScheduleEntry.cell.dataset.color = this.value;
                }
                renderScheduleBlocks();
                return;
            }


            selectedCell.style.backgroundColor =
                this.value;


            selectedCell.dataset.color =
                this.value;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ADD TIME ROW
    |--------------------------------------------------------------------------
    */

   function createScheduleRow()
{
    const row = document.createElement('tr');

    function createTimeCell(side)
    {
        const timeCell = document.createElement('td');

        timeCell.dataset.side = side;
        timeCell.className = 'time-cell editable-time';
        timeCell.contentEditable = 'true';
        timeCell.spellcheck = false;
        timeCell.textContent = 'New Time';

        return timeCell;
    }

    function createScheduleCell(day, side)
    {
        const cell = document.createElement('td');

        cell.className = 'schedule-cell';
        cell.dataset.day = day;
        cell.dataset.side = side;
        cell.dataset.time = '';
        cell.dataset.scheduleBaseTime = '';
        cell.dataset.rowPosition = '';
        cell.dataset.scheduleTime = '';
        cell.dataset.course = '';
        cell.dataset.subject = '';
        cell.dataset.year = '';
        cell.dataset.major = '';
        cell.dataset.program = '';
        cell.dataset.instructor = '';
        cell.dataset.facultyId = '';
        cell.dataset.facultySubjectId = '';
        cell.dataset.departmentId = chairDepartmentId;
        cell.dataset.color = '#ffffff';
        cell.dataset.subjectType = '';
        cell.dataset.locked = '0';
        cell.dataset.reservationId = '';

        cell.innerHTML = '<span class="empty-cell">Empty</span>';

        return cell;
    }

    // LEFT: TIME + MON + WED + FRI
    row.appendChild(createTimeCell('left'));
    row.appendChild(createScheduleCell('MON', 'left'));
    row.appendChild(createScheduleCell('WED', 'left'));
    row.appendChild(createScheduleCell('FRI', 'left'));

    // MIDDLE: TIME + TUE + THU
    row.appendChild(createTimeCell('right'));
    row.appendChild(createScheduleCell('TUE', 'right'));
    row.appendChild(createScheduleCell('THU', 'right'));

    // SATURDAY: independent time + schedule cell
    row.appendChild(createTimeCell('sat'));
    row.appendChild(createScheduleCell('SAT', 'sat'));

    return row;
}

function finalizeNewScheduleRow(row)
{
    const leftTime = row.querySelector('.editable-time[data-side="left"]');
    const rightTime = row.querySelector('.editable-time[data-side="right"]');
    const saturdayTime = row.querySelector('.editable-time[data-side="sat"]');

    const syncRowTimes = function()
    {
        const leftValue = leftTime ? leftTime.innerText.trim() : '';
        const rightValue = rightTime ? rightTime.innerText.trim() : '';
        const saturdayValue = saturdayTime ? saturdayTime.innerText.trim() : '';

        row.querySelectorAll('.schedule-cell[data-side="left"]').forEach(function(cell) {
            cell.dataset.time = leftValue;
            cell.dataset.scheduleBaseTime = leftValue;
        });

        row.querySelectorAll('.schedule-cell[data-side="right"]').forEach(function(cell) {
            cell.dataset.time = rightValue;
            cell.dataset.scheduleBaseTime = rightValue;
        });

        row.querySelectorAll('.schedule-cell[data-side="sat"]').forEach(function(cell) {
            cell.dataset.time = saturdayValue;
            cell.dataset.scheduleBaseTime = saturdayValue;
        });

        const rowPosition = scheduleGridRows().indexOf(row);
        row.querySelectorAll('.schedule-cell').forEach(function(cell) {
            cell.dataset.rowPosition = String(rowPosition);
        });
    };

    syncRowTimes();
    attachCellEvents();
    attachTimeCellEvents();
    if (scheduleRowDeleteMode) syncScheduleRowDeleteControls();
    updateMergeControls();
    renderScheduleBlocks();

    row.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
}

function addRow()
{
    const tbody = document.getElementById('scheduleBody');
    const row = createScheduleRow();

    tbody.appendChild(row);
    finalizeNewScheduleRow(row);
}

function addRowBeforeBreak()
{
    const tbody = document.getElementById('scheduleBody');
    const row = createScheduleRow();

    // Put the new time row ABOVE the Add Time Row button.
    // The button stays immediately before the Noon Break.
    const addButtonRow = tbody.querySelector('.add-time-before-break-row');
    const breakRow = tbody.querySelector('.break-row');

    if (addButtonRow) {
        tbody.insertBefore(row, addButtonRow);
    } else if (breakRow) {
        tbody.insertBefore(row, breakRow);
    } else {
        tbody.appendChild(row);
    }

    finalizeNewScheduleRow(row);
}

    document
        .getElementById(
            'addRowButton'
        )
        .addEventListener(
            'click',
            addRow
        );

    const addRowBeforeBreakButton =
        document.getElementById(
            'addRowBeforeBreakButton'
        );

    if (addRowBeforeBreakButton) {
        addRowBeforeBreakButton.addEventListener(
            'click',
            addRowBeforeBreak
        );
    }

    const toggleDeleteRowsButton = document.getElementById('toggleDeleteRowsButton');
    const deleteSelectedRowsButton = document.getElementById('deleteSelectedRowsButton');
    const deleteRowsModeHint = document.getElementById('deleteRowsModeHint');
    const scheduleRowDeleteConfirm = document.getElementById('scheduleRowDeleteConfirm');
    const scheduleRowDeleteConfirmMessage = document.getElementById('scheduleRowDeleteConfirmMessage');
    const confirmScheduleRowDeleteButton = document.getElementById('confirmScheduleRowDelete');
    const cancelScheduleRowDeleteButton = document.getElementById('cancelScheduleRowDelete');

    function updateSelectedDeleteRowCount()
    {
        if (!deleteSelectedRowsButton) return;
        const count = document.querySelectorAll('#scheduleBody .schedule-row-checkbox:checked').length;
        deleteSelectedRowsButton.disabled = count === 0;
        deleteSelectedRowsButton.textContent = count > 0
            ? 'Delete Selected Rows (' + count + ')'
            : 'Delete Selected Rows';
    }

    function syncScheduleRowDeleteControls()
    {
        const rows = scheduleGridRows();
        if (!scheduleRowDeleteMode) {
            rows.forEach(function(row) {
                const timeCell = row.querySelector('.editable-time[data-side="left"]');
                timeCell?.querySelector('.schedule-row-check-label')?.remove();
                if (timeCell) {
                    timeCell.classList.remove('row-delete-selectable');
                    timeCell.contentEditable = 'true';
                }
                row.classList.remove('schedule-row-delete-selected');
            });
            updateSelectedDeleteRowCount();
            return;
        }

        rows.forEach(function(row) {
            const timeCell = row.querySelector('.editable-time[data-side="left"]');
            if (!timeCell) return;

            let checkbox = timeCell.querySelector('.schedule-row-checkbox');
            if (!checkbox) {
                const label = document.createElement('label');
                label.className = 'schedule-row-check-label';
                const input = document.createElement('input');
                input.type = 'checkbox';
                input.className = 'schedule-row-checkbox';
                input.setAttribute('aria-label', 'Select this time row for deletion');
                input.addEventListener('change', function() {
                    row.classList.toggle('schedule-row-delete-selected', input.checked);
                    updateSelectedDeleteRowCount();
                });
                label.appendChild(input);
                timeCell.appendChild(label);
                checkbox = input;
            }

            const hasLockedSlot = Array.from(row.querySelectorAll('.schedule-cell')).some(function(cell) {
                return cell.classList.contains('locked-cell')
                    || Boolean(String(cell.dataset.reservationId || '').trim());
            });
            checkbox.disabled = hasLockedSlot;
            checkbox.title = hasLockedSlot
                ? 'This row contains another college’s schedule or a room reservation.'
                : 'Select this row for deletion.';
            timeCell.classList.add('row-delete-selectable');
            timeCell.contentEditable = 'false';
        });

        updateSelectedDeleteRowCount();
    }

    function setScheduleRowDeleteMode(enabled)
    {
        scheduleRowDeleteMode = enabled;
        if (toggleDeleteRowsButton) {
            toggleDeleteRowsButton.textContent = enabled ? 'Cancel Row Delete' : 'Delete Row';
            toggleDeleteRowsButton.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        }
        if (deleteRowsModeHint) deleteRowsModeHint.hidden = !enabled;
        if (deleteSelectedRowsButton) deleteSelectedRowsButton.hidden = !enabled;
        syncScheduleRowDeleteControls();
    }

    function closeScheduleRowDeleteConfirm()
    {
        if (scheduleRowDeleteConfirm) scheduleRowDeleteConfirm.hidden = true;
    }

    toggleDeleteRowsButton?.addEventListener('click', function() {
        setScheduleRowDeleteMode(!scheduleRowDeleteMode);
    });

    deleteSelectedRowsButton?.addEventListener('click', function() {
        const selectedRows = Array.from(document.querySelectorAll('#scheduleBody tr'))
            .filter(row => row.querySelector('.schedule-row-checkbox:checked'));
        if (!selectedRows.length || !scheduleRowDeleteConfirm) return;

        const scheduledEntries = selectedRows.reduce(function(total, row) {
            return total + Array.from(row.querySelectorAll('.schedule-cell[data-course]'))
                .filter(cell => String(cell.dataset.course || '').trim()).length;
        }, 0);
        scheduleRowDeleteConfirmMessage.textContent = scheduledEntries > 0
            ? 'This will remove ' + selectedRows.length + ' selected time row(s) and ' + scheduledEntries + ' schedule cell(s) from the spreadsheet. Click Publish Schedule afterward to save the row and schedule deletions to the database.'
            : 'This will remove ' + selectedRows.length + ' selected empty time row(s) from the spreadsheet. Click Publish Schedule afterward to save the row changes.';
        scheduleRowDeleteConfirm.hidden = false;
        confirmScheduleRowDeleteButton?.focus();
    });

    cancelScheduleRowDeleteButton?.addEventListener('click', closeScheduleRowDeleteConfirm);
    scheduleRowDeleteConfirm?.addEventListener('click', function(event) {
        if (event.target === scheduleRowDeleteConfirm) closeScheduleRowDeleteConfirm();
    });

    confirmScheduleRowDeleteButton?.addEventListener('click', function() {
        const selectedRows = Array.from(document.querySelectorAll('#scheduleBody tr'))
            .filter(row => row.querySelector('.schedule-row-checkbox:checked'));
        if (!selectedRows.length) {
            closeScheduleRowDeleteConfirm();
            return;
        }

        const removedSelectedCell = selectedCell && selectedRows.some(row => row.contains(selectedCell));
        selectedRows.forEach(row => {
            row.querySelectorAll('.schedule-cell').forEach(cell => {
                cell.classList.remove('selected-cell');
                cell.setAttribute('aria-selected', 'false');
            });
            row.remove();
        });

        scheduleGridRows().forEach(function(row, rowPosition) {
            row.querySelectorAll('.schedule-cell').forEach(function(cell) {
                cell.dataset.rowPosition = String(rowPosition);
            });
        });

        const remainingMergeGroups = new Map();
        document.querySelectorAll('#scheduleTable .schedule-cell[data-manual-merge-id]').forEach(function(cell) {
            const mergeId = cell.dataset.manualMergeId;
            if (!remainingMergeGroups.has(mergeId)) remainingMergeGroups.set(mergeId, []);
            remainingMergeGroups.get(mergeId).push(cell);
        });
        remainingMergeGroups.forEach(function(members) {
            if (members.length > 1) return;
            members.forEach(function(cell) {
                cell.hidden = false;
                cell.removeAttribute('aria-hidden');
                cell.removeAttribute('colspan');
                cell.removeAttribute('rowspan');
                cell.classList.remove('manual-merged-schedule-cell');
                delete cell.dataset.manualMergeId;
                delete cell.dataset.manualMergeAxis;
            });
        });

        if (removedSelectedCell) {
            selectedCell = null;
            closeScheduleEntryPopup();
        }
        scheduleRowDeletionPending = true;
        closeScheduleRowDeleteConfirm();
        setScheduleRowDeleteMode(false);
        updateMergeControls();
        renderScheduleBlocks();
        showSuccess('Selected time rows were removed. Click Publish Schedule to save the row and schedule deletions.');
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && scheduleRowDeleteConfirm && !scheduleRowDeleteConfirm.hidden) {
            event.preventDefault();
            closeScheduleRowDeleteConfirm();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | BUTTON EVENTS
    |--------------------------------------------------------------------------
    */

document.getElementById('confirmScheduleEntryButton')?.addEventListener('click', function() { saveCell(false); });

    document.getElementById('cancelScheduleEntryButton')?.addEventListener('click', closeScheduleEntryPopup);

    courseCodeInput.addEventListener('keydown', function(event) {
        if (event.key !== 'Enter') return;
        event.preventDefault();
        if (subjectMatchSelector && !subjectMatchSelector.hidden) {
            subjectMatchSelector.focus();
            return;
        }
        saveCell();
    });

    subjectMatchSelector?.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            saveCell();
        }
    });


    document
        .getElementById(
            'clearCellButton'
        )
        .addEventListener(
            'click',
            clearCell
        );


    /*
    |--------------------------------------------------------------------------
    | PREPARE SCHEDULE
    |--------------------------------------------------------------------------
    */

  function prepareSchedule()
{
    const hidden =
        document.getElementById('hiddenInputs');

    hidden.innerHTML = '';

    const semester =
        document.getElementById('semester')?.value.trim() || '';

    const academicYear =
        document.getElementById('academic_year')?.value.trim() || '';

    function addHidden(name, value)
    {
        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = name;
        input.value = value ?? '';

        hidden.appendChild(input);
    }

    addHidden('semester', semester);
    addHidden('academic_year', academicYear);
    addHidden('delete_time_rows', scheduleRowDeletionPending ? '1' : '0');

    const rows =
        Array.from(
            document.querySelectorAll(
                '#scheduleTable tbody tr:not(.break-row):not(.add-time-before-break-row)'
            )
        );

    const rowPositionMap =
        new Map(
            rows.map(function(row, index) {
                return [row, index];
            })
        );

    // A vertical visual merge represents one continuous class block. Older
    // versions submitted the same course once per hidden row; fold those
    // matching entries into one schedule record when republishing.
    const verticalMergeCellsToSkip = new Set();
    const verticalMergeGroups = new Map();
    document.querySelectorAll('#scheduleTable .schedule-cell[data-manual-merge-id][data-manual-merge-axis="vertical"]')
        .forEach(function(cell) {
            const mergeId = cell.dataset.manualMergeId;
            if (!verticalMergeGroups.has(mergeId)) verticalMergeGroups.set(mergeId, []);
            verticalMergeGroups.get(mergeId).push(cell);
        });

    verticalMergeGroups.forEach(function(members) {
        members.sort(function(a, b) {
            return rowPositionMap.get(a.parentElement) - rowPositionMap.get(b.parentElement);
        });
        const scheduled = members.filter(function(cell) { return String(cell.dataset.course || '').trim(); });
        if (!scheduled.length) return;

        const source = scheduled[0];
        const sameClass = scheduled.every(function(cell) {
            return String(cell.dataset.course || '').trim().toUpperCase() === String(source.dataset.course || '').trim().toUpperCase()
                && String(cell.dataset.facultySubjectId || '') === String(source.dataset.facultySubjectId || '');
        });
        if (!sameClass) return;

        const combinedTime = scheduleEntryTimeForCell(source);
        if (combinedTime) {
            source.dataset.scheduleTime = combinedTime;
            const range = parseScheduleRange(combinedTime);
            const position = range
                ? scheduleRowPositionAtMinute(source.dataset.day, source.dataset.side, range.start)
                : null;
            if (position) {
                source.dataset.rowPosition = String(position.index);
                source.dataset.scheduleBaseTime = position.time;
            }
        }
        scheduled.filter(function(cell) { return cell !== source; }).forEach(function(cell) {
            verticalMergeCellsToSkip.add(cell);
        });
    });

  let counter = 0;
let timeSlotCounter = 0;
let roomTimeSlotCounter = 0;

    /*
    |--------------------------------------------------------------------------
    | SUBMIT EVERY OFFICIAL TIME ROW
    |--------------------------------------------------------------------------
    |
    | This keeps schedule_time_slots synchronized even when a new time row
    | has no class assigned yet.
    |--------------------------------------------------------------------------
    */

    rows.forEach(function(row) {

        const rowPosition =
            rowPositionMap.get(row);

        ['left', 'right', 'sat'].forEach(function(side) {

            const timeCell =
                row.querySelector(
                    '.editable-time[data-side="' + side + '"]'
                );

            const time =
                timeCell
                    ? timeCell.innerText.trim()
                    : '';

            if (!time) {
                return;
            }

            row.querySelectorAll(
                '.schedule-cell[data-side="' + side + '"]'
            ).forEach(function(cell) {
                cell.dataset.time = time;
            });

            const days = side === 'left'
                ? ['MON', 'WED', 'FRI']
                : (side === 'right' ? ['TUE', 'THU'] : ['SAT']);

           days.forEach(function(day) {

    /*
    |--------------------------------------------------------------------------
    | GLOBAL TIME SLOT
    |--------------------------------------------------------------------------
    */

    addHidden(
        'time_slots[' +
        timeSlotCounter +
        '][day]',
        day
    );

    addHidden(
        'time_slots[' +
        timeSlotCounter +
        '][time]',
        time
    );

    addHidden(
        'time_slots[' +
        timeSlotCounter +
        '][row_position]',
        rowPosition
    );

    timeSlotCounter++;


    /*
    |--------------------------------------------------------------------------
    | ROOM-SPECIFIC TIME SLOT
    |--------------------------------------------------------------------------
    |
    | This records that THIS ROOM actually has this timetable row.
    | It can therefore be Available even when the cell is empty.
    |
    */

    addHidden(
        'room_time_slots[' +
        roomTimeSlotCounter +
        '][day]',
        day
    );

    addHidden(
        'room_time_slots[' +
        roomTimeSlotCounter +
        '][time]',
        time
    );

    addHidden(
        'room_time_slots[' +
        roomTimeSlotCounter +
        '][row_position]',
        rowPosition
    );

    roomTimeSlotCounter++;
});
        });
    });

    /*
    |--------------------------------------------------------------------------
    | SUBMIT SCHEDULE CELLS
    |--------------------------------------------------------------------------
    |
    | Cell formatting does not combine the underlying schedule records.
    | Hidden cells in a manual merge are still submitted individually.
    |--------------------------------------------------------------------------
    */

    rows.forEach(function(row) {

        const rowPosition =
            rowPositionMap.get(row);

        row.querySelectorAll('.schedule-cell').forEach(function(cell) {
            if (cell.dataset.locked === '1') return;

            const entries = getCellScheduleEntries(cell);
            entries.forEach(function(entry, entryIndex) {
                if (verticalMergeCellsToSkip.has(cell) && entryIndex === 0) return;

                const course = String(entry.course || '').trim().toUpperCase();
                if (!course) return;

                const matches = findCourse(course);
                const assignmentId = entry.faculty_subject_id || '';
                const match = matches.length === 1
                    ? matches[0]
                    : matches.find(item => (item.assignment_ids || [item.id]).map(String).includes(String(assignmentId)));
                if (!match) return;

                const time = String(entry.time || cell.dataset.scheduleTime || cell.dataset.time || '').trim();
                const baseTime = String(entry.base_time || cell.dataset.time || '').trim();
                const day = String(entry.day || cell.dataset.day || '').trim().toUpperCase();
                if (!time || !day) return;

                const range = parseScheduleRange(time);
                const startPosition = range
                    ? scheduleRowPositionAtMinute(day, cell.dataset.side, range.start)
                    : null;
                const scheduleData = {
                    schedule_id: entry.schedule_id || '',
                    row_position: Number.isInteger(Number(entry.row_position))
                        ? Number(entry.row_position)
                        : (startPosition?.index ?? rowPosition),
                    day: day,
                    time: time,
                    base_time: baseTime,
                    course_code: match.course_code,
                    faculty_subject_id: match.id,
                    subject: match.subject,
                    year_level: match.year_level,
                    major: match.major || '',
                    program: match.program || '',
                    instructor: match.instructor,
                    faculty_id: match.faculty_id || '',
                    department_id: entry.department_id || chairDepartmentId,
                    subject_type: entry.subject_type || match.subject_type || (/\.1\s*$/.test(course) ? 'Lab' : 'Lec'),
                    color: entry.color || '#ffffff',
                };

                Object.keys(scheduleData).forEach(function(name) {
                    addHidden('schedule[' + counter + '][' + name + ']', scheduleData[name]);
                });

                const sharedIds = Array.isArray(entry.shared_offering_ids)
                    ? entry.shared_offering_ids
                    : String(entry.shared_offering_ids || '').split(',').filter(Boolean);
                sharedIds.forEach(function(offeringId) {
                    addHidden('schedule[' + counter + '][shared_faculty_subject_ids][]', offeringId);
                });

                counter++;
            });
        });
    });

    const manualMergeGroups = new Map();
    document.querySelectorAll('#scheduleTable .schedule-cell[data-manual-merge-id]').forEach(function(cell) {
        const mergeId = cell.dataset.manualMergeId;
        if (!manualMergeGroups.has(mergeId)) {
            manualMergeGroups.set(mergeId, {
                axis: cell.dataset.manualMergeAxis || '',
                cells: [],
            });
        }

        const mappedRowPosition = rowPositionMap.get(cell.parentElement);
        const physicalRowPosition = rows.indexOf(cell.parentElement);
        const storedRowPosition = Number(cell.dataset.rowPosition);
        const rowPosition = Number.isInteger(physicalRowPosition) && physicalRowPosition >= 0
            ? physicalRowPosition
            : (Number.isInteger(mappedRowPosition)
                ? mappedRowPosition
                : (Number.isInteger(storedRowPosition) && storedRowPosition >= 0 ? storedRowPosition : null));
        const side = String(cell.dataset.side || '').toLowerCase();
        const timeCell = cell.parentElement.querySelector('.editable-time[data-side="' + side + '"]');

        manualMergeGroups.get(mergeId).cells.push({
            day: String(cell.dataset.day || '').toUpperCase(),
            side: side,
            time: timeCell ? timeCell.innerText.trim() : '',
            row_position: rowPosition,
        });
    });

    let manualMergeIndex = 0;
    manualMergeGroups.forEach(function(merge) {
        if (merge.cells.length < 2 || merge.cells.some(function(cell) {
            return !Number.isInteger(cell.row_position) || cell.row_position < 0;
        })) return;

        addHidden('manual_merges[' + manualMergeIndex + '][axis]', merge.axis);
        merge.cells.forEach(function(cell, cellIndex) {
            addHidden('manual_merges[' + manualMergeIndex + '][cells][' + cellIndex + '][day]', cell.day);
            addHidden('manual_merges[' + manualMergeIndex + '][cells][' + cellIndex + '][side]', cell.side);
            addHidden('manual_merges[' + manualMergeIndex + '][cells][' + cellIndex + '][time]', cell.time);
            addHidden('manual_merges[' + manualMergeIndex + '][cells][' + cellIndex + '][row_position]', cell.row_position);
        });
        manualMergeIndex++;
    });

    return counter;
}

    scheduleForm.addEventListener('submit', function(event) {
        if (scheduleRowDeleteMode) {
            event.preventDefault();
            alert('Finish or cancel row deletion before publishing the schedule.');
            return;
        }

        const count = prepareSchedule();

        if (count === 0 && !scheduleRowDeletionPending) {
            event.preventDefault();
            alert('There is no schedule entry to upload. Select an editable or empty cell, enter a valid course code, and click Confirm Entry first.');
            return;
        }

        scheduleForm.action = "{{ route('chair.save.schedule') }}";
    });

    const scheduleExportRoomName = @json($room->room_name ?? 'Room');
    const scheduleExportTermName = @json(trim(($activeTerm->academic_year ?? '') . ' ' . ($activeTerm->semester ?? '')));
    const schedulePrintButton = document.getElementById('printScheduleSpreadsheet');
    const scheduleDownloadButton = document.getElementById('downloadScheduleSpreadsheet');

    function prepareScheduleExportTable()
    {
        const sourceTable = document.getElementById('scheduleTable');
        if (!sourceTable) return null;

        const exportTable = sourceTable.cloneNode(true);
        exportTable.removeAttribute('data-fit-schedule');

        const sourceElements = [sourceTable, ...sourceTable.querySelectorAll('*')];
        const exportElements = [exportTable, ...exportTable.querySelectorAll('*')];
        const exportBySource = new Map();
        const transferableStyles = [
            'background-color', 'color', 'font-family', 'font-size', 'font-weight',
            'font-style', 'text-align', 'vertical-align', 'line-height',
        ];

        sourceElements.forEach(function(sourceElement, index) {
            const exportElement = exportElements[index];
            if (!exportElement) return;
            exportBySource.set(sourceElement, exportElement);

            const computed = window.getComputedStyle(sourceElement);
            transferableStyles.forEach(function(property) {
                const value = computed.getPropertyValue(property);
                if (value) exportElement.style.setProperty(property, value);
            });

            exportElement.removeAttribute('contenteditable');
            exportElement.removeAttribute('spellcheck');
            exportElement.classList.remove('selected-cell', 'schedule-block-source');
        });

        const mergeGroups = new Map();
        sourceTable.querySelectorAll('.schedule-cell[data-manual-merge-id]').forEach(function(cell) {
            const mergeId = cell.dataset.manualMergeId;
            if (!mergeGroups.has(mergeId)) mergeGroups.set(mergeId, []);
            mergeGroups.get(mergeId).push(cell);
        });

        mergeGroups.forEach(function(members) {
            const anchor = members.find(member => !member.hidden) || members[0];
            const scheduledMember = members.find(member => String(member.dataset.course || '').trim());
            if (!scheduledMember || scheduledMember === anchor) return;

            const anchorCopy = exportBySource.get(anchor);
            const scheduledCopy = exportBySource.get(scheduledMember);
            const scheduledContent = scheduledCopy?.querySelector('.schedule-cell-content');
            if (anchorCopy && scheduledContent) {
                let anchorContent = anchorCopy.querySelector('.schedule-cell-content');
                if (!anchorContent) {
                    anchorContent = document.createElement('div');
                    anchorContent.className = 'schedule-cell-content';
                    anchorCopy.querySelector('.empty-cell')?.remove();
                    anchorCopy.appendChild(anchorContent);
                }
                anchorContent.innerHTML = scheduledContent.innerHTML;
                anchorCopy.style.backgroundColor = scheduledMember.dataset.color || '#ffffff';
            }
        });

        sourceTable.querySelectorAll('.schedule-cell').forEach(function(cell) {
            if (!String(cell.dataset.course || '').trim()) return;
            const scheduleTime = String(cell.dataset.scheduleTime || cell.dataset.time || '').trim();
            const baseTime = String(cell.dataset.scheduleBaseTime || cell.dataset.time || '').trim();
            if (!scheduleTime || scheduleTime === baseTime) return;

            const mergeId = cell.dataset.manualMergeId || '';
            const group = mergeId ? mergeGroups.get(mergeId) || [] : [cell];
            const target = group.find(member => !member.hidden) || cell;
            const targetContent = exportBySource.get(target)?.querySelector('.schedule-cell-content');
            if (!targetContent || targetContent.querySelector('.schedule-export-time')) return;

            const timeLabel = document.createElement('div');
            timeLabel.className = 'schedule-export-time';
            timeLabel.textContent = scheduleTime;
            targetContent.prepend(timeLabel);
        });

        exportTable.querySelectorAll('.schedule-cell[hidden], th[hidden], td[hidden]').forEach(function(cell) {
            cell.remove();
        });
        exportTable.querySelectorAll('tr.add-time-before-break-row').forEach(function(row) {
            row.remove();
        });
        exportTable.querySelectorAll('button, input, select, textarea, .schedule-row-check-label').forEach(function(control) {
            control.remove();
        });
        exportTable.querySelectorAll('.selected-cell, .schedule-block-source').forEach(function(element) {
            element.classList.remove('selected-cell', 'schedule-block-source');
        });

        return exportTable;
    }

    function scheduleExportCss(exportTable)
    {
        const headerCells = Array.from(exportTable.querySelectorAll('thead tr:first-child th'));
        const timeColumnCount = Math.max(1, headerCells.filter(cell => cell.classList.contains('time-header')).length);
        const dayColumnCount = Math.max(1, headerCells.filter(cell => !cell.classList.contains('time-header')).length);
        const sourceWidth = (timeColumnCount * 112) + (dayColumnCount * 190);
        const timeColumnWidth = (112 / sourceWidth * 100).toFixed(3);
        const dayColumnWidth = (190 / sourceWidth * 100).toFixed(3);

        return `
            @page { size: landscape; margin: 8mm; }
            html, body { margin:0; padding:0; background:#fff; color:#243746; font-family:Arial,sans-serif; }
            #scheduleTable { width:100% !important; min-width:0 !important; max-width:none !important; border-collapse:collapse !important; table-layout:fixed !important; font-size:9pt; }
            #scheduleTable th, #scheduleTable td { box-sizing:border-box; border:1px solid #74818a !important; padding:4px 5px !important; overflow:visible !important; overflow-wrap:anywhere; vertical-align:top; }
            #scheduleTable th { position:static !important; width:${dayColumnWidth}% !important; min-width:0 !important; height:auto !important; background:#edf2f5 !important; color:#344654 !important; text-align:center !important; font-weight:800 !important; }
            #scheduleTable th.time-header { width:${timeColumnWidth}% !important; }
            #scheduleTable td { height:auto !important; min-height:62px; }
            #scheduleTable td.time-cell { position:static !important; width:${timeColumnWidth}% !important; min-width:0 !important; height:auto !important; background:#f7f9fa !important; color:#334555 !important; text-align:center !important; vertical-align:middle !important; white-space:normal !important; font-weight:750 !important; }
            #scheduleTable td.schedule-cell { width:${dayColumnWidth}% !important; min-width:0 !important; height:auto !important; min-height:72px; }
            #scheduleTable .schedule-cell-content { display:block !important; height:auto !important; max-height:none !important; overflow:visible !important; visibility:visible !important; }
            #scheduleTable .schedule-export-time { display:block; margin:2px 0 5px; color:#334d3d; font-size:8pt; font-weight:800; }
            #scheduleTable .cell-course { color:#182b3a; font-size:9pt; font-weight:800; }
            #scheduleTable .cell-subject { color:#344758; font-size:8pt; }
            #scheduleTable .cell-instructor { color:#516170; font-size:7.5pt; }
            #scheduleTable .cell-year, #scheduleTable .cell-major, #scheduleTable .cell-program, #scheduleTable .cell-subject-type { font-size:7pt; }
            #scheduleTable .break-row td { height:auto !important; background:#f1f3f5 !important; text-align:center !important; }
        `;
    }

    function scheduleExportFilename()
    {
        const safePart = value => String(value || '')
            .normalize('NFKD')
            .replace(/[^A-Za-z0-9_-]+/g, '-')
            .replace(/^-+|-+$/g, '') || 'Schedule';
        return ['Class-Schedule', safePart(scheduleExportRoomName), safePart(scheduleExportTermName)].join('-');
    }

    function printScheduleSpreadsheet()
    {
        const exportTable = prepareScheduleExportTable();
        if (!exportTable) return;

        const printWindow = window.open('', '_blank');
        if (!printWindow) {
            showWarning('Allow pop-up windows to print the spreadsheet.');
            return;
        }

        const printDocument = `<!doctype html><html><head><meta charset="utf-8"><title>Class Schedule</title><style>${scheduleExportCss(exportTable)}</style></head><body>${exportTable.outerHTML}</body></html>`;
        printWindow.document.open();
        printWindow.document.write(printDocument);
        printWindow.document.close();
        printWindow.onafterprint = function() { printWindow.close(); };
        window.setTimeout(function() {
            if (printWindow.closed) return;
            printWindow.focus();
            printWindow.print();
        }, 350);
    }

    function downloadScheduleSpreadsheet()
    {
        const exportTable = prepareScheduleExportTable();
        if (!exportTable) return;

        const workbookHtml = `<!doctype html><html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="utf-8"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="ProgId" content="Excel.Sheet"><meta name="Generator" content="Microsoft Excel"><style>${scheduleExportCss(exportTable)}</style></head><body>${exportTable.outerHTML}</body></html>`;
        const blob = new Blob(['\ufeff', workbookHtml], { type: 'application/vnd.ms-excel;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = scheduleExportFilename() + '.xls';
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.setTimeout(() => URL.revokeObjectURL(url), 1000);
    }

    schedulePrintButton?.addEventListener('click', printScheduleSpreadsheet);
    scheduleDownloadButton?.addEventListener('click', downloadScheduleSpreadsheet);

    // =====================================================
// EDITABLE TIME CELLS
// =====================================================

function attachTimeCellEvents() {
    if (isPublishedScheduleView) return;
    document.querySelectorAll('.editable-time').forEach(timeCell => {

        if (timeCell.dataset.timeEventsAttached === '1') {
            return;
        }

        timeCell.dataset.timeEventsAttached = '1';

        function syncTimeToCells() {
            const row = timeCell.closest('tr');

            if (!row) {
                return;
            }

            const time =
                timeCell.innerText.trim();

            const side =
                timeCell.dataset.side;

            row.querySelectorAll(
                '.schedule-cell[data-side="' + side + '"]'
            ).forEach(function(cell) {
                cell.dataset.time = time;
            });

            const rowPosition = scheduleGridRows().indexOf(row);
            document.querySelectorAll(
                '#scheduleTable .schedule-cell[data-side="' + side + '"]'
            ).forEach(function(cell) {
                if (Number(cell.dataset.rowPosition) === rowPosition) {
                    cell.dataset.scheduleBaseTime = time;
                }
            });

            renderScheduleBlocks();
        }

        syncTimeToCells();

        timeCell.addEventListener('input', syncTimeToCells);
        timeCell.addEventListener('blur', syncTimeToCells);

        timeCell.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                this.blur();
            }
        });
    });
}

// ============================================================
// MERGE WITH EXISTING CLASS
// ============================================================

const searchMergeBtn =
    document.getElementById('searchMergeBtn');

const mergeCourseCode =
    document.getElementById('mergeCourseCode');

const mergeSearchStatus =
    document.getElementById('mergeSearchStatus');

const mergeResults =
    document.getElementById('mergeResults');


// ------------------------------------------------------------
// SEARCH EXISTING CLASS
// ------------------------------------------------------------

let mergeSearchSequence = 0;
let mergeSearchDebounce = null;

if (searchMergeBtn) {

    searchMergeBtn.addEventListener(
        'click',
        function () {

           const requestSequence = ++mergeSearchSequence;

           const courseCode =
    mergeCourseCode.value.trim();

if (!courseCode) {

    alert(
        'Please enter a course code.'
    );

    mergeCourseCode.focus();

    return;
}


            mergeSearchStatus.textContent =
                'Searching for existing classes...';

            mergeResults.innerHTML =
                '';

            mergeResults.style.display =
                'none';

const params =
    new URLSearchParams({

        course_code:
            courseCode

    });

const activeTermId = new URLSearchParams(window.location.search).get('term_id');
if (activeTermId) params.set('term_id', activeTermId);


            fetch(
                '{{ route("chair.search.merge.classes") }}?' +
                params.toString(),
                {
                    method: 'GET',

                    headers: {
                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'
                    }
                }
            )
            .then(function(response) {

                if (!response.ok) {

                    throw new Error(
                        'Search request failed.'
                    );

                }

                return response.json();

            })
            .then(function(data) {
                if (requestSequence !== mergeSearchSequence
                    || mergeCourseCode.value.trim().toUpperCase() !== courseCode.toUpperCase()) return;
                window.mergeRoomSchedules =
                data.schedules || [];
                if (
                    !data.classes ||
                    data.classes.length === 0
                ) {

                    mergeSearchStatus.textContent =
                        'No matching class was found in another college.';

                    return;
                }


                mergeSearchStatus.textContent =
                    data.classes.length +
                    ' matching class(es) found.';


                data.classes.forEach(
                    function(existingClass) {

                        const result =
                            document.createElement('div');

                        result.className =
                            'merge-result';

                        const mergedCourseLabels = Array.isArray(existingClass.merged_courses)
                            ? existingClass.merged_courses.map(function(course) {
                                return [course.course_code, course.course_title, course.program]
                                    .filter(Boolean)
                                    .map(escapeHtml)
                                    .join(' — ');
                            }).filter(Boolean)
                            : [];
                        const mergedCourseInfo = mergedCourseLabels.length
                            ? '<strong>Already shared with:</strong> ' + mergedCourseLabels.join('<br>') + '<br>'
                            : '';


                        result.innerHTML =

                            '<div class="merge-result-title">' +

                            escapeHtml(
                                existingClass.course_code
                            ) +

                            ' — ' +

                            escapeHtml(
                                existingClass.subject
                            ) +

                            '</div>' +

                            '<div class="merge-result-info">' +

                            '<strong>College:</strong> ' +
                            escapeHtml(
                                 existingClass.department
                                ) +

                            '<br>' +

                            '<strong>Program:</strong> ' +
                                escapeHtml(
                            existingClass.program ||
                            'Unknown Program'
                                ) +

                            '<br>' +

                            mergedCourseInfo +

                            '<strong>Instructor:</strong> ' +

                            escapeHtml(
                                existingClass.instructor
                            ) +

                            '<br>' +

                           '<strong>Building:</strong> ' +

escapeHtml(
    existingClass.building ||
    'Unknown Building'
) +

'<br>' +

'<strong>Room:</strong> ' +

escapeHtml(
    existingClass.room
) +

'<br>' +

                            '<strong>Day:</strong> ' +

                            escapeHtml(
                                existingClass.day
                            ) +

                            '<br>' +

                            '<strong>Time:</strong> ' +

                            escapeHtml(
                                existingClass.time
                            ) +

                            '</div>' +

                            '<button ' +

                            'type="button" ' +

                            'class="merge-select-btn btn btn-green" ' +

                            'data-schedule-id="' +

                            existingClass.id +

                            '">' +

                            'Select This Class' +

                            '</button>';


                        mergeResults.appendChild(
                            result
                        );

                    }
                );


                mergeResults.style.display =
    'block';

/*
|--------------------------------------------------------------------------
| SEARCH RESULT — SELECT CLASS
|--------------------------------------------------------------------------
*/

mergeResults
    .querySelectorAll('.merge-select-btn')
    .forEach(function(button)
    {
        button.addEventListener(
            'click',
            function()
            {
                const scheduleId =
                    this.dataset.scheduleId;

                const existingClass =
                    data.classes.find(
                        function(item)
                        {
                            return String(item.id) ===
                                String(scheduleId);
                        }
                    );

                if (!existingClass) {
                    alert(
                        'Unable to load the selected class.'
                    );
                    return;
                }

                if (window.openScheduleMergeRequest) {
                    window.openScheduleMergeRequest(existingClass, button);
                    return;
                }

                const existingProgram =
                    existingClass.program ||
                    'Unknown Program';

                const mergeProgramSelection =
    document.getElementById(
        'mergeProgramSelection'
    );

const mergeProgramSelect =
    document.getElementById(
        'mergeProgramSelect'
    );

if (
    !mergeProgramSelection ||
    !mergeProgramSelect
) {
    alert(
        'Program selection is unavailable.'
    );
    return;
}

/*
|--------------------------------------------------------------------------
| SAVE SEARCHED CLASS TEMPORARILY
|--------------------------------------------------------------------------
*/

window.selectedMergeSchedule = {
    id: existingClass.id,
    course_code: existingClass.course_code,
    subject: existingClass.subject,
    instructor: existingClass.instructor,
    department: existingClass.department,
    building: existingClass.building,
    room: existingClass.room,
    day: existingClass.day,
    time: existingClass.time,
    program:
        existingClass.program ||
        'Unknown Program'
};

/*
|--------------------------------------------------------------------------
| OPEN PROGRAM POPUP
|--------------------------------------------------------------------------
*/

mergeProgramSelect.value = '';

mergeProgramSelection.style.display =
    'flex';

loadMergePrograms();

return;

                const confirmed =
                    confirm(
                        'MERGE EXISTING CLASS?\n\n' +
                        'Course Code: ' +
                        existingClass.course_code +
                        '\n' +
                        'Subject: ' +
                        existingClass.subject +
                        '\n' +
                        'Instructor: ' +
                        existingClass.instructor +
                        '\n' +
                        'College: ' +
                        existingClass.department +
                        '\n' +
                        'Building: ' +
                        existingClass.building +
                        '\n' +
                        'Room: ' +
                        existingClass.room +
                        '\n' +
                        'Day: ' +
                        existingClass.day +
                        '\n' +
                        'Time: ' +
                        existingClass.time +
                        '\n\n' +
                        'Existing Program:\n' +
                        existingProgram +
                        '\n\n' +
                        'Program to Add:\n' +
                        mergingProgram.trim() +
                        '\n\n' +
                        'Result after approval:\n' +
                        existingProgram +
                        ' / ' +
                        mergingProgram.trim() +
                        '\n\n' +
                        'The original room, instructor, and schedule will remain unchanged.\n\n' +
                        'Send merge request to the owner college?'
                    );

                if (!confirmed) {
                    return;
                }

                fetch(
                    "{{ route('chair.merge.request') }}",
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'X-CSRF-TOKEN':
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).getAttribute('content')
                        },

                        body: JSON.stringify({
                            schedule_id:
                                scheduleId,

                            program:
                                mergingProgram.trim()
                        })
                    }
                )
                .then(function(response)
                {
                    return response.json()
                        .then(function(result)
                        {
                            if (!response.ok) {
                                throw new Error(
                                    result.message ||
                                    'Unable to submit merge request.'
                                );
                            }

                            return result;
                        });
                })
                .then(function(result)
                {
                    if (!result.success) {
                        throw new Error(
                            result.message ||
                            'Unable to submit merge request.'
                        );
                    }

                    alert(
                        'Merge request submitted successfully!\n\n' +
                        'Course: ' +
                        existingClass.course_code +
                        '\n' +
                        'Program: ' +
                        mergingProgram.trim() +
                        '\n' +
                        'Status: Pending'
                    );

                    button.disabled = true;
                    button.textContent =
                        'Merge Request Sent';
                })
                .catch(function(error)
                {
                    console.error(error);

                    alert(
                        error.message ||
                        'Unable to submit merge request.'
                    );
                });
            }
        );
    });

            })
            .catch(function(error) {

                if (requestSequence !== mergeSearchSequence) return;

                console.error(error);

                mergeSearchStatus.textContent =
                    'Unable to search for existing classes.';

            });

        }
    );

}

if (mergeCourseCode && searchMergeBtn) {
    mergeCourseCode.addEventListener('input', function() {
        window.clearTimeout(mergeSearchDebounce);
        const query = mergeCourseCode.value.trim();
        mergeSearchSequence++;

        if (!query) {
            mergeResults.replaceChildren();
            mergeResults.style.display = 'none';
            mergeSearchStatus.textContent = 'Type a course code to search scheduled classes.';
            return;
        }

        mergeSearchStatus.textContent = 'Searching scheduled course codes…';
        mergeSearchDebounce = window.setTimeout(function() {
            searchMergeBtn.click();
        }, 250);
    });

    mergeCourseCode.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            window.clearTimeout(mergeSearchDebounce);
            searchMergeBtn.click();
        }
    });
}


// ============================================================
// MANUAL MERGE BROWSE
// ============================================================

const mergeDepartments =
    document.getElementById('mergeDepartments');

const mergeScheduleView =
    document.getElementById('mergeScheduleView');


// ------------------------------------------------------------
// LOAD DEPARTMENTS
// ------------------------------------------------------------

function loadMergeDepartments()
{
    if (!mergeDepartments) {
        return;
    }

    mergeDepartments.innerHTML =
        '<div class="merge-loading">' +
        'Loading colleges...' +
        '</div>';

    fetch(
        '{{ route("chair.merge.departments") }}',
        {
            method: 'GET',

            headers: {
                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'
            }
        }
    )
    .then(function(response)
    {
        if (!response.ok) {
            throw new Error(
                'Unable to load colleges.'
            );
        }

        return response.json();
    })
    .then(function(data)
    {
        if (
            !data.success ||
            !data.departments ||
            data.departments.length === 0
        ) {
            mergeDepartments.innerHTML =
                '<div class="merge-empty">' +
                'No other colleges found.' +
                '</div>';

            return;
        }

        mergeDepartments.innerHTML = '';

        data.departments.forEach(
            function(department)
            {
                const departmentBox =
                    document.createElement('div');

                departmentBox.className =
                    'merge-department-box';

                departmentBox.innerHTML =
                    '<button ' +
                    'type="button" ' +
                    'class="merge-department-btn">' +

                    escapeHtml(
                        department.name
                    ) +

                    '</button>' +

                    '<div ' +
                    'class="merge-buildings" ' +
                    'style="display:none;">' +

                    '</div>';

                mergeDepartments.appendChild(
                    departmentBox
                );


                const departmentButton =
                    departmentBox.querySelector(
                        '.merge-department-btn'
                    );

                const buildingsContainer =
                    departmentBox.querySelector(
                        '.merge-buildings'
                    );


                departmentButton.addEventListener(
                    'click',
                    function()
                    {
                        const isOpen =
                            buildingsContainer.style.display ===
                            'block';

                        if (isOpen) {

                            buildingsContainer.style.display =
                                'none';

                            return;
                        }


                        buildingsContainer.innerHTML =
                            '';


                        if (
                            !department.buildings ||
                            department.buildings.length === 0
                        ) {

                            buildingsContainer.innerHTML =
                                '<div class="merge-empty">' +
                                'No buildings found.' +
                                '</div>';

                            buildingsContainer.style.display =
                                'block';

                            return;
                        }


                        department.buildings.forEach(
                            function(building)
                            {
                                const buildingBox =
                                    document.createElement('div');

                                buildingBox.className =
                                    'merge-building-box';


                                buildingBox.innerHTML =
                                    '<button ' +
                                    'type="button" ' +
                                    'class="merge-building-btn">' +

                                    escapeHtml(
                                        building.name
                                    ) +

                                    '</button>' +

                                    '<div ' +
                                    'class="merge-rooms" ' +
                                    'style="display:none;">' +

                                    '</div>';


                                buildingsContainer.appendChild(
                                    buildingBox
                                );


                                const buildingButton =
                                    buildingBox.querySelector(
                                        '.merge-building-btn'
                                    );

                                const roomsContainer =
                                    buildingBox.querySelector(
                                        '.merge-rooms'
                                    );


                                buildingButton.addEventListener(
                                    'click',
                                    function()
                                    {
                                        const isOpen =
                                            roomsContainer.style.display ===
                                            'block';

                                        if (isOpen) {

                                            roomsContainer.style.display =
                                                'none';

                                            return;
                                        }


                                        roomsContainer.innerHTML =
                                            '';


                                        if (
                                            !building.rooms ||
                                            building.rooms.length === 0
                                        ) {

                                            roomsContainer.innerHTML =
                                                '<div class="merge-empty">' +
                                                'No rooms found.' +
                                                '</div>';

                                            roomsContainer.style.display =
                                                'block';

                                            return;
                                        }


                                        building.rooms.forEach(
                                            function(room)
                                            {
                                                const roomButton =
                                                    document.createElement(
                                                        'button'
                                                    );

                                                roomButton.type =
                                                    'button';

                                                roomButton.className =
                                                    'merge-room-btn';

                                                roomButton.textContent =
                                                    room.name;

                                                roomButton.dataset.roomId =
                                                    room.id;

                                                roomsContainer.appendChild(
                                                    roomButton
                                                );


                                                roomButton.addEventListener(
                                                    'click',
                                                    function()
                                                    {
                                                        openMergeRoomSchedule(
                                                            room.id,
                                                            department.name,
                                                            building.name,
                                                            room.name
                                                        );
                                                    }
                                                );
                                            }
                                        );


                                        roomsContainer.style.display =
                                            'block';
                                    }
                                );
                            }
                        );


                        buildingsContainer.style.display =
                            'block';
                    }
                );
            }
        );
    })
    .catch(function(error)
    {
        console.error(error);

        mergeDepartments.innerHTML =
            '<div class="merge-error">' +
            'Unable to load colleges.' +
            '</div>';
    });
}


// ------------------------------------------------------------
// OPEN ROOM SCHEDULE IN SEPARATE CARD
// ------------------------------------------------------------

function openMergeRoomSchedule(
    roomId,
    departmentName,
    buildingName,
    roomName
)
{
    if (!mergeScheduleView) {
        return;
    }

    /*
     |--------------------------------------------------------------------------
     | SHOW THE SCHEDULE CARD
     |--------------------------------------------------------------------------
     */

    mergeScheduleView.style.display =
        'block';

    mergeScheduleView.innerHTML =

        '<div class="merge-schedule-card">' +

            '<div class="merge-schedule-card-header">' +

                '<div>' +

                    '<div class="merge-schedule-card-title">' +
                        'Room Schedule' +
                    '</div>' +

                    '<div class="merge-schedule-card-info">' +

                        '<strong>College:</strong> ' +
                        escapeHtml(departmentName) +

                        ' &nbsp; | &nbsp; ' +

                        '<strong>Building:</strong> ' +
                        escapeHtml(buildingName) +

                        ' &nbsp; | &nbsp; ' +

                        '<strong>Room:</strong> ' +
                        escapeHtml(roomName) +

                    '</div>' +

                '</div>' +

                '<button ' +
                    'type="button" ' +
                    'class="merge-close-schedule-btn"' +
                '>' +
                    'Close' +
                '</button>' +

            '</div>' +

            '<div class="merge-schedule-loading">' +
                'Loading schedule...' +
            '</div>' +

        '</div>';


    /*
     |--------------------------------------------------------------------------
     | CLOSE BUTTON
     |--------------------------------------------------------------------------
     */

    const closeButton =
        mergeScheduleView.querySelector(
            '.merge-close-schedule-btn'
        );

    closeButton.addEventListener(
        'click',
        function()
        {
            mergeScheduleView.style.display =
                'none';

            mergeScheduleView.innerHTML =
                '';
        }
    );


    /*
     |--------------------------------------------------------------------------
     | LOAD ROOM SCHEDULE
     |--------------------------------------------------------------------------
     */

    fetch(
        '{{ route("chair.merge.room", ":roomId") }}'
            .replace(
                ':roomId',
                roomId
            ),
        {
            method: 'GET',

            headers: {
                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'
            }
        }
    )
    .then(function(response)
    {
       if (!response.ok) {

    return response.text().then(function(text) {

        console.error(
            'Room schedule error:',
            response.status,
            text
        );

        throw new Error(
            'Server error ' +
            response.status +
            ': ' +
            text
        );

    });

}
        return response.json();
    })
    .then(function(data)
    {
        if (
            !data.success ||
            !data.room
        ) {

            throw new Error(
                'Invalid room schedule response.'
            );

        }

        window.activeMergeRoom = data.room;


        /*
         |--------------------------------------------------------------------------
         | FIND THE CARD
         |--------------------------------------------------------------------------
         */

        const card =
            mergeScheduleView.querySelector(
                '.merge-schedule-card'
            );

        const hasSaturdaySchedule = (data.schedules || []).some(function(schedule) {
            return ['SAT', 'SATURDAY'].includes(String(schedule.day || '').trim().toUpperCase());
        });


        /*
         |--------------------------------------------------------------------------
         | BUILD SCHEDULE
         |--------------------------------------------------------------------------
         */

        let scheduleHtml =

            '<div class="merge-readonly-notice">' +

                'This schedule is read-only. ' +

                'Only existing occupied classes can be selected ' +

                'for a merge request.' +

            '</div>' +

            '<div class="merge-room-schedule-table-wrapper schedule-sheet" data-schedule-sheet data-fit-schedule-container>' +

                '<div class="schedule-sheet-controls" aria-label="Schedule sheet size controls">' +
                    '<button type="button" class="schedule-sheet-control" data-schedule-maximize aria-label="Maximize schedule"><span aria-hidden="true">⛶</span> Maximize</button>' +
                    '<button type="button" class="schedule-sheet-control" data-schedule-minimize aria-label="Minimize schedule" hidden><span aria-hidden="true">↙</span> Minimize</button>' +
                '</div>' +

            '<table class="merge-room-schedule-table" data-fit-schedule>' +

                '<thead>' +

                    '<tr>' +

                        '<th>TIME</th>' +

                        '<th>MON</th>' +

                        '<th>WED</th>' +

                        '<th>FRI</th>' +

                        '<th>TIME</th>' +

                        '<th>TUE</th>' +

                        '<th>THU</th>' +
                        (hasSaturdaySchedule ? '<th>TIME</th><th>SAT</th>' : '') +

                    '</tr>' +

                '</thead>' +

                '<tbody>';


        /*
         |--------------------------------------------------------------------------
         | TIME ROWS
         |--------------------------------------------------------------------------
         */

        const timeRows = [
            '7:00-8:00',
            '8:00-9:00',
            '9:00-10:00',
            '10:00-11:00',
            '11:00-12:00',
            '1:00-2:00',
            '2:00-3:00',
            '3:00-4:00',
            '4:00-5:00',
            '5:00-6:00',
            '6:00-7:00'
        ];


        timeRows.forEach(
            function(time, rowIndex)
            {
                /*
                 |--------------------------------------------------------------------------
                 | NOON BREAK
                 |--------------------------------------------------------------------------
                 */

                if (rowIndex === 5) {

                    scheduleHtml +=

                        '<tr class="merge-noon-break">' +

                            '<td colspan="' + (hasSaturdaySchedule ? '9' : '7') + '">' +
                                'NOON BREAK' +
                            '</td>' +

                        '</tr>';

                }


                /*
                 |--------------------------------------------------------------------------
                 | FIND CELLS
                 |--------------------------------------------------------------------------
                 */

                const leftTime =
                    time;

                const rightTime =
                    time;


                const monday =
                    findMergeSchedule(
                        data.schedules,
                        'MON',
                        rowIndex
                    );

                const wednesday =
                    findMergeSchedule(
                        data.schedules,
                        'WED',
                        rowIndex
                    );

                const friday =
                    findMergeSchedule(
                        data.schedules,
                        'FRI',
                        rowIndex
                    );

                const tuesday =
                    findMergeSchedule(
                        data.schedules,
                        'TUE',
                        rowIndex
                    );

                const thursday =
                    findMergeSchedule(
                        data.schedules,
                        'THU',
                        rowIndex
                    );

                const saturday = hasSaturdaySchedule
                    ? findMergeSchedule(data.schedules, 'SAT', rowIndex)
                    : null;


                scheduleHtml +=

                    '<tr>' +

                        '<td class="merge-time-cell">' +
                            escapeHtml(leftTime) +
                        '</td>' +

                        buildMergeScheduleCell(
                            monday
                        ) +

                        buildMergeScheduleCell(
                            wednesday
                        ) +

                        buildMergeScheduleCell(
                            friday
                        ) +

                        '<td class="merge-time-cell">' +
                            escapeHtml(rightTime) +
                        '</td>' +

                        buildMergeScheduleCell(
                            tuesday
                        ) +

                        buildMergeScheduleCell(
                            thursday
                        ) +

                        (hasSaturdaySchedule
                            ? '<td class="merge-time-cell">' + escapeHtml(time) + '</td>' + buildMergeScheduleCell(saturday)
                            : '') +

                    '</tr>';
            }
        );


        scheduleHtml +=

                '</tbody>' +

            '</table>' +

            '</div>';


        /*
         |--------------------------------------------------------------------------
         | REPLACE LOADING MESSAGE
         |--------------------------------------------------------------------------
         */

        const loading =
            card.querySelector(
                '.merge-schedule-loading'
            );

        if (loading) {

            loading.outerHTML =
                scheduleHtml;

        }
    })
    .catch(function(error)
    {
        console.error(error);

        const card =
            mergeScheduleView.querySelector(
                '.merge-schedule-card'
            );

        if (card) {

            const loading =
                card.querySelector(
                    '.merge-schedule-loading'
                );

            if (loading) {

                loading.innerHTML =
                    'Unable to load room schedule.';

            }

        }
    });
}

function loadMergePrograms()
{
    const mergeProgramSelect =
        document.getElementById(
            'mergeProgramSelect'
        );

    if (!mergeProgramSelect) {
        return;
    }

    mergeProgramSelect.innerHTML =
        '<option value="">Loading programs...</option>';

    fetch(
        "{{ route('chair.merge.programs') }}",
        {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }
    )
    .then(function(response)
    {
        if (!response.ok) {
            throw new Error(
                'Failed to load programs.'
            );
        }

        return response.json();
    })
    .then(function(data)
    {
        mergeProgramSelect.innerHTML =
            '<option value="">Select Program</option>';

        if (
            !data.success ||
            !Array.isArray(data.programs) ||
            data.programs.length === 0
        ) {
            mergeProgramSelect.innerHTML =
                '<option value="">No programs available</option>';

            return;
        }

        data.programs.forEach(
            function(program)
            {
                const option =
                    document.createElement('option');

                option.value = program;
                option.textContent = program;

                mergeProgramSelect.appendChild(
                    option
                );
            }
        );
    })
    .catch(function(error)
    {
        console.error(
            'Merge programs error:',
            error
        );

        mergeProgramSelect.innerHTML =
            '<option value="">Unable to load programs</option>';
    });
}
// ------------------------------------------------------------
// FIND SCHEDULE FOR A DAY / ROW
// ------------------------------------------------------------

function findMergeSchedule(
    schedules,
    day,
    rowIndex
)
{
    if (!schedules) {
        return null;
    }

    const timeRows = [
        '7:00-8:00', '8:00-9:00', '9:00-10:00', '10:00-11:00', '11:00-12:00',
        '1:00-2:00', '2:00-3:00', '3:00-4:00', '4:00-5:00', '5:00-6:00', '6:00-7:00'
    ];

    function minutes(value)
    {
        const match = String(value || '').trim().match(/^(\d{1,2}):(\d{2})(?:\s*(AM|PM))?$/i);
        if (!match) return null;
        let hour = Number(match[1]);
        const minute = Number(match[2]);
        const meridiem = String(match[3] || '').toUpperCase();
        if (meridiem === 'PM' && hour < 12) hour += 12;
        if (meridiem === 'AM' && hour === 12) hour = 0;
        if (!meridiem && hour >= 1 && hour <= 6) hour += 12;
        return hour <= 23 && minute <= 59 ? hour * 60 + minute : null;
    }

    function startPosition(value)
    {
        const start = minutes(String(value || '').trim().replace(/[–—]/g, '-').split('-')[0]);
        if (start === null) return null;
        for (let index = 0; index < timeRows.length; index++) {
            const parts = timeRows[index].split('-');
            const from = minutes(parts[0]);
            const until = minutes(parts[1]);
            if (from !== null && until !== null && start >= from && start < until) return index;
        }
        return null;
    }

    return schedules.find(
        function(schedule)
        {
            const scheduleDay = String(schedule.day || '').trim().toUpperCase().slice(0, 3);
            const positionByTime = startPosition(schedule.time);
            return (
                scheduleDay === day &&
                (positionByTime !== null
                    ? positionByTime === rowIndex
                    : Number(schedule.row_position) === rowIndex)
            );
        }
    ) || null;
}


// ------------------------------------------------------------
// BUILD READ-ONLY SCHEDULE CELL
// ------------------------------------------------------------
function buildMergeScheduleCell(
    schedule
)
{
    if (!schedule) {
        return (
            '<td class="merge-schedule-cell merge-empty-cell">' +
                '<span>' +
                    'Empty' +
                '</span>' +
            '</td>'
        );
    }

    return (
        '<td ' +
            'class="merge-schedule-cell merge-occupied-cell" ' +
            'data-schedule-id="' +
                schedule.id +
            '" ' +
            'data-merge-schedule-id="' + schedule.id + '" ' +
            'data-merge-course-code="' + escapeHtml(schedule.course_code || '') + '" ' +
            'data-merge-subject="' + escapeHtml(schedule.subject || '') + '" ' +
            'data-merge-instructor="' + escapeHtml(schedule.instructor || '') + '" ' +
            'data-merge-department="' + escapeHtml(window.activeMergeRoom?.department || '') + '" ' +
            'data-merge-program="' + escapeHtml(schedule.program || '') + '" ' +
            'data-merge-building="' + escapeHtml(window.activeMergeRoom?.building || '') + '" ' +
            'data-merge-room="' + escapeHtml(window.activeMergeRoom?.name || '') + '" ' +
            'data-merge-day="' + escapeHtml(schedule.day || '') + '" ' +
            'data-merge-time="' + escapeHtml(schedule.time || '') + '" ' +
            'data-merge-semester="' + escapeHtml(schedule.semester || '') + '" ' +
            'data-merge-academic-year="' + escapeHtml(schedule.academic_year || '') + '" ' +
            'data-program="' +
                escapeHtml(
                    schedule.program || ''
                ) +
            '"' +
        '>' +

            '<div class="merge-class-course">' +
                escapeHtml(
                    schedule.course_code || ''
                ) +
            '</div>' +

            '<div class="merge-class-subject">' +
                escapeHtml(
                    schedule.subject || ''
                ) +
            '</div>' +

            '<div class="merge-class-time">Time: ' +
                escapeHtml(formatScheduleTimeForDisplay(schedule.time || '')) +
            '</div>' +

            '<div class="merge-class-instructor">' +
                escapeHtml(
                    schedule.instructor || ''
                ) +
            '</div>' +

            (Array.isArray(schedule.merged_courses) ? schedule.merged_courses : []).map(function(course) {
                return '<div class="merge-class-shared-course"><strong>' +
                    escapeHtml(course.course_code || '') +
                    '</strong> ' + escapeHtml(course.course_title || '') +
                    (course.program ? '<small>' + escapeHtml(course.program) + '</small>' : '') +
                    '</div>';
            }).join('') +

            '<button type="button" class="schedule-merge-request-action" data-merge-request-button ' +
                'data-merge-schedule-id="' + schedule.id + '" ' +
                'data-merge-course-code="' + escapeHtml(schedule.course_code || '') + '" ' +
                'data-merge-subject="' + escapeHtml(schedule.subject || '') + '" ' +
                'data-merge-instructor="' + escapeHtml(schedule.instructor || '') + '" ' +
                'data-merge-department="' + escapeHtml(window.activeMergeRoom?.department || '') + '" ' +
                'data-merge-program="' + escapeHtml(schedule.program || '') + '" ' +
                'data-merge-building="' + escapeHtml(window.activeMergeRoom?.building || '') + '" ' +
                'data-merge-room="' + escapeHtml(window.activeMergeRoom?.name || '') + '" ' +
                'data-merge-day="' + escapeHtml(schedule.day || '') + '" ' +
                'data-merge-time="' + escapeHtml(schedule.time || '') + '" ' +
                'data-merge-semester="' + escapeHtml(schedule.semester || '') + '" ' +
                'data-merge-academic-year="' + escapeHtml(schedule.academic_year || '') + '">Request to join class</button>' +

            '<div class="merge-select-hint">' +
                'Right-click to request a course merge' +
            '</div>' +

        '</td>'
    );
}
document.addEventListener(
    'click',
    function(event)
    {
        const cell =
            event.target.closest(
                '.merge-occupied-cell'
            );

        if (event.target.closest('[data-merge-request-button]')) {
            return;
        }

        if (!cell) {
            return;
        }

        const scheduleId =
            cell.dataset.scheduleId;

        if (!scheduleId) {
            return;
        }

        /*
         |--------------------------------------------------------------------------
         | GET THE SELECTED CLASS
         |--------------------------------------------------------------------------
         */

        const schedule =
            mergeScheduleView.querySelector(
                '[data-schedule-id="' +
                scheduleId +
                '"]'
            );

        if (!schedule) {
            return;
        }

        /*
         |--------------------------------------------------------------------------
         | GET CLASS INFORMATION
         |--------------------------------------------------------------------------
         */

        const course =
            schedule.querySelector(
                '.merge-class-course'
            )?.textContent.trim() || '';

        const subject =
            schedule.querySelector(
                '.merge-class-subject'
            )?.textContent.trim() || '';

        const instructor =
            schedule.querySelector(
                '.merge-class-instructor'
            )?.textContent.trim() || '';

      /*
 |--------------------------------------------------------------------------
 | GET PROGRAM FROM THE SELECTED CELL
 |--------------------------------------------------------------------------
 */

const existingProgram =
    cell.dataset.program &&
    cell.dataset.program.trim()
        ? cell.dataset.program.trim()
        : 'Unknown Program';

        window.selectedMergeSchedule = {
    id: scheduleId,

    course_code: course,

    subject: subject,

    instructor: instructor,

    program: existingProgram
};

if (window.openScheduleMergeRequest) {
    window.openScheduleMergeRequest(window.selectedMergeSchedule, cell);
    return;
}

console.log(
    'Selected schedule ID:',
    scheduleId
);

console.log(
    'Existing program:',
    existingProgram
);
        /*
|--------------------------------------------------------------------------
| PROGRAM TO MERGE
|--------------------------------------------------------------------------
*/

const programSelection =
    document.getElementById(
        'mergeProgramSelection'
    );

const mergeProgramSelect =
    document.getElementById(
        'mergeProgramSelect'
    );

if (
    !programSelection ||
    !mergeProgramSelect
) {
    alert(
        'Program selection is not available.'
    );

    return;
}

/*
|--------------------------------------------------------------------------
| SHOW PROGRAM SELECTION
|--------------------------------------------------------------------------
*/

programSelection.style.display = 'block';

mergeProgramSelect.innerHTML =
    '<option value="">Loading your programs...</option>';

loadMergePrograms();

programSelection.scrollIntoView({
    behavior: 'smooth',
    block: 'nearest'
});
/*
|--------------------------------------------------------------------------
| WHEN PROGRAM IS SELECTED
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| CONTINUE BUTTON — SUBMIT MERGE REQUEST
|--------------------------------------------------------------------------
*/

const confirmMergeProgram =
    document.getElementById(
        'confirmMergeProgram'
    );

if (confirmMergeProgram) {

    confirmMergeProgram.onclick =
        function()
        {
            const mergingProgram =
                mergeProgramSelect.value.trim();

            if (!mergingProgram) {

                alert(
                    'Please select a program first.'
                );

                return;
            }

            const selectedMerge =
                window.selectedMergeSchedule;

            if (!selectedMerge) {

                alert(
                    'No class has been selected for merging.'
                );

                return;
            }

            const course =
                selectedMerge.course_code || '';

            const subject =
                selectedMerge.subject || '';

            const instructor =
                selectedMerge.instructor ||
                'Unknown Instructor';

            const existingProgram =
                selectedMerge.program ||
                'Unknown Program';

            const scheduleId =
                selectedMerge.id;

            if (!scheduleId) {

                alert(
                    'The selected class is invalid.'
                );

                return;
            }

            const confirmed =
                confirm(
                    'MERGE EXISTING CLASS?\n\n' +

                    'Course Code: ' +
                    course +
                    '\n' +

                    'Subject: ' +
                    subject +
                    '\n' +

                    'Instructor: ' +
                    instructor +
                    '\n\n' +

                    'Existing Program:\n' +
                    existingProgram +
                    '\n\n' +

                    'Program to Add:\n' +
                    mergingProgram +
                    '\n\n' +

                    'Result after approval:\n' +
                    existingProgram +
                    ' / ' +
                    mergingProgram +
                    '\n\n' +

                    'The original room, instructor, and class schedule will remain unchanged.\n\n' +

                    'Send merge request to the owner college?'
                );

            if (!confirmed) {
                return;
            }

            confirmMergeProgram.disabled = true;

            fetch(
                "{{ route('chair.merge.request') }}",
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).getAttribute('content')
                    },

                    body: JSON.stringify({
                        schedule_id:
                            scheduleId,

                        program:
                            mergingProgram
                    })
                }
            )
            .then(function(response)
            {
                return response.json()
                    .then(function(data)
                    {
                        return {
                            ok:
                                response.ok,

                            data:
                                data
                        };
                    });
            })

            .then(function(result)
            {
                if (
                    !result.ok ||
                    !result.data.success
                ) {

                    alert(
                        result.data.message ||
                        'Unable to create merge request.'
                    );

                    return;
                }

                alert(
                    'Merge request submitted successfully!\n\n' +

                    'Course: ' +
                    course +
                    '\n' +

                    'Program: ' +
                    mergingProgram +
                    '\n' +

                    'Status: Pending'
                );

                programSelection.style.display =
                    'none';

                mergeProgramSelect.value = '';

                window.selectedMergeSchedule =
                    null;
            })

            .catch(function(error)
            {
                console.error(
                    'Merge request error:',
                    error
                );

                alert(
                    'An error occurred while submitting the merge request.'
                );
            })

            .finally(function()
            {
                confirmMergeProgram.disabled =
                    false;
            });
        };
}

    }
);


    /*
    |--------------------------------------------------------------------------
    | HALF-HOUR SCHEDULE BLOCKS
    |--------------------------------------------------------------------------
    */

    function scheduleGridRows()
    {
        return Array.from(document.querySelectorAll(
            '#scheduleBody > tr:not(.break-row):not(.add-time-before-break-row)'
        ));
    }

    function parseScheduleClockMinutes(value)
    {
        const match = String(value || '').trim().match(/^(\d{1,2})(?::(\d{2}))?\s*(AM|PM)?$/i);
        if (!match) return null;

        let hour = Number(match[1]);
        const minute = Number(match[2] || 0);
        const meridiem = String(match[3] || '').toUpperCase();
        if (minute > 59) return null;

        if (meridiem) {
            if (hour < 1 || hour > 12) return null;
            if (meridiem === 'AM' && hour === 12) hour = 0;
            if (meridiem === 'PM' && hour !== 12) hour += 12;
        } else if (hour >= 1 && hour <= 6) {
            // Chair timetable labels use 1:00–6:00 for afternoon slots.
            hour += 12;
        }

        return hour * 60 + minute;
    }

    function parseScheduleRange(value)
    {
        const parts = String(value || '').replace(/[–—]/g, '-').split(/\s*-\s*/, 2);
        if (parts.length !== 2) return null;

        const start = parseScheduleClockMinutes(parts[0]);
        let end = parseScheduleClockMinutes(parts[1]);
        if (start === null || end === null) return null;

        // The timetable labels omit AM/PM. In the final afternoon row,
        // "6:00-7:00" parses as 18:00-07:00 unless the end inherits PM.
        // Apply that inheritance only when the end has no explicit period.
        const endHasPeriod = /\b(?:AM|PM)\s*$/i.test(String(parts[1]).trim());
        if (!endHasPeriod && end <= start) {
            const endHour = Number(String(parts[1]).trim().match(/^(\d{1,2})/)?.[1] || 0);
            if (endHour >= 1 && endHour <= 11) end += 12 * 60;
        }

        if (end <= start) return null;

        return { start: start, end: end };
    }

    function formatScheduleClock(minutes)
    {
        const normalized = ((minutes % 1440) + 1440) % 1440;
        const hour = Math.floor(normalized / 60);
        const minute = normalized % 60;
        return String(hour).padStart(2, '0') + ':' + String(minute).padStart(2, '0');
    }

    function formatScheduleRange(start, end)
    {
        return formatScheduleClock(start) + '-' + formatScheduleClock(end);
    }

    function formatScheduleRangeLabel(start, end)
    {
        const label = function(minutes) {
            const hour24 = Math.floor(minutes / 60) % 24;
            const minute = minutes % 60;
            const hour12 = hour24 % 12 || 12;
            const meridiem = hour24 < 12 ? 'AM' : 'PM';
            return hour12 + ':' + String(minute).padStart(2, '0') + ' ' + meridiem;
        };
        return label(start) + '–' + label(end);
    }

    function formatScheduleTimeForDisplay(value)
    {
        const range = parseScheduleRange(value);
        return range ? formatScheduleRangeLabel(range.start, range.end) : String(value || '');
    }

    function scheduleTimeCellForRow(row, side)
    {
        return row.querySelector('.editable-time[data-side="' + side + '"]');
    }

    function timeRangeForScheduleRow(row, side)
    {
        const timeCell = scheduleTimeCellForRow(row, side);
        return timeCell
            ? { cell: timeCell, range: parseScheduleRange(timeCell.innerText.trim()) }
            : null;
    }

    function scheduleYAtMinute(day, side, minute, edge)
    {
        const rows = scheduleGridRows();
        let lastMatch = null;

        for (let index = 0; index < rows.length; index++) {
            const rowTime = timeRangeForScheduleRow(rows[index], side);
            const range = rowTime?.range;
            if (!range || minute < range.start || minute > range.end) continue;

            const rect = rowTime.cell.getBoundingClientRect();
            const fraction = (minute - range.start) / (range.end - range.start);
            const point = rect.top + (rect.height * fraction);
            lastMatch = { point: point, index: index, cell: rowTime.cell, range: range };

            if (edge === 'start' && minute < range.end) return point;
            if (edge === 'end' && minute > range.start) return point;
        }

        return lastMatch ? lastMatch.point : null;
    }

    function scheduleMinuteAtY(day, side, clientY)
    {
        const boundaries = [];
        const timedRows = [];

        for (const row of scheduleGridRows()) {
            const rowTime = timeRangeForScheduleRow(row, side);
            const range = rowTime?.range;
            if (!range) continue;

            const rect = rowTime.cell.getBoundingClientRect();
            if (rect.height <= 0) continue;
            timedRows.push({ rect: rect, range: range });
            boundaries.push({ y: rect.top, minute: range.start });
            boundaries.push({ y: rect.bottom, minute: range.end });

            if (clientY < rect.top || clientY > rect.bottom) continue;

            const fraction = Math.max(0, Math.min(1, (clientY - rect.top) / rect.height));
            const rawMinute = range.start + (fraction * (range.end - range.start));
            return Math.round(rawMinute / 30) * 30;
        }

        if (!timedRows.length || !boundaries.length) return null;

        // Continue snapping when the pointer travels just past a row edge,
        // including below the last row, where the class must stop at 7:00 PM.
        boundaries.sort((a, b) => Math.abs(clientY - a.y) - Math.abs(clientY - b.y));
        return boundaries[0].minute;
    }

    function scheduleRowPositionAtMinute(day, side, minute)
    {
        const rows = scheduleGridRows();

        for (let index = 0; index < rows.length; index++) {
            const rowTime = timeRangeForScheduleRow(rows[index], side);
            const range = rowTime?.range;
            if (!range) continue;

            if (minute >= range.start && minute < range.end) {
                return { index: index, time: rowTime.cell.innerText.trim() };
            }
        }

        const lastIndex = rows.length - 1;
        const lastRowTime = lastIndex >= 0 ? timeRangeForScheduleRow(rows[lastIndex], side) : null;
        const lastRange = lastRowTime?.range;
        if (lastRange && minute === lastRange.end) {
            return { index: lastIndex, time: lastRowTime.cell.innerText.trim() };
        }

        return null;
    }

    function scheduleRangeCrossesBreak(day, side, start, end)
    {
        const startY = scheduleYAtMinute(day, side, start, 'start');
        const endY = scheduleYAtMinute(day, side, end, 'end');
        if (startY === null || endY === null) return true;

        return Array.from(document.querySelectorAll(
            '#scheduleBody > tr.break-row, #scheduleBody > tr.add-time-before-break-row'
        )).some(function(row) {
            const rect = row.getBoundingClientRect();
            return startY < rect.bottom && endY > rect.top;
        });
    }

    function scheduleResizeWouldConflict(sourceCell, start, end)
    {
        const day = sourceCell.dataset.day;
        const otherCells = Array.from(document.querySelectorAll('#scheduleTable .schedule-cell'));

        for (const other of otherCells) {
            if (other === sourceCell || other.dataset.day !== day) continue;
            for (const entry of getCellScheduleEntries(other)) {
                if (sourceCell.dataset.scheduleId
                    && String(entry.schedule_id || '') === String(sourceCell.dataset.scheduleId)) continue;
                const otherRange = parseScheduleRange(entry.time || '');
                if (entry.course && otherRange && start < otherRange.end && end > otherRange.start) return true;
            }

            if (other.dataset.reservationId) {
                const reservedRange = parseScheduleRange(other.dataset.reservationTime || other.dataset.time || '');
                if (reservedRange && start < reservedRange.end && end > reservedRange.start) {
                    return true;
                }
            }
        }

        const sourceEntries = getCellScheduleEntries(sourceCell);
        const sourceEntry = sourceEntries.find(function(entry) {
            return String(entry.schedule_id || '') === String(sourceCell.dataset.scheduleId || '');
        }) || sourceEntries[0];
        for (const entry of sourceEntries) {
            if (entry === sourceEntry) continue;
            const otherRange = parseScheduleRange(entry.time || '');
            if (entry.course && otherRange && start < otherRange.end && end > otherRange.start) return true;
        }

        return false;
    }

    function buildScheduleResizePlan(sourceCell, candidate, original, edge, snapshots)
    {
        if (candidate.end - candidate.start < 30) return null;
        if (candidate.start < (13 * 60) && candidate.end > (12 * 60)) return null;
        // Let the chair resize the visible block freely. The publish action
        // performs the authoritative schedule conflict checks before saving.
        const plan = new Map([[sourceCell, candidate]]);
        if (sourceCell.dataset.manualMergeAxis === 'horizontal' && sourceCell.dataset.manualMergeId) {
            getManualMergeMembers(sourceCell).forEach(function(member) {
                if (member !== sourceCell && member.dataset.course) {
                    plan.set(member, { start: candidate.start, end: candidate.end });
                }
            });
        }
        return plan;
    }

    function selectedScheduleSourceCell(visualCell = selectedCell)
    {
        if (!visualCell) return null;
        if (visualCell.dataset.course && visualCell.dataset.locked !== '1' && !visualCell.dataset.reservationId) {
            return visualCell;
        }

        const mergeId = visualCell.dataset.manualMergeId || '';
        if (!mergeId) return null;

        return Array.from(document.querySelectorAll('#scheduleTable .schedule-cell')).find(function(member) {
            return member.dataset.manualMergeId === mergeId
                && member.dataset.course
                && member.dataset.locked !== '1'
                && !member.dataset.reservationId;
        }) || null;
    }

    function updateScheduleResizeCell(cell, visualCell, start, end)
    {
        if (end - start < 30) return 'A class must be at least 30 minutes long.';
        if (scheduleRangeCrossesBreak(cell.dataset.day, cell.dataset.side, start, end)) {
            return 'A class cannot cross the lunch break.';
        }
        if (scheduleResizeWouldConflict(cell, start, end)) {
            return 'That time overlaps another class or room reservation.';
        }

        cell.dataset.scheduleTime = formatScheduleRange(start, end);
        cell.dataset.scheduleStartMinute = String(start);
        cell.dataset.scheduleEndMinute = String(end);

        const position = scheduleRowPositionAtMinute(cell.dataset.day, cell.dataset.side, start);
        if (position) {
            cell.dataset.rowPosition = String(position.index);
            cell.dataset.scheduleBaseTime = position.time;
        }

        return null;
    }

    function getScheduleBlockLayer()
    {
        if (scheduleBlockLayer && scheduleBlockLayer.isConnected) return scheduleBlockLayer;

        const wrapper = document.querySelector('[data-schedule-sheet]');
        if (!wrapper) return null;

        scheduleBlockLayer = wrapper.querySelector('.schedule-block-layer');
        if (!scheduleBlockLayer) {
            scheduleBlockLayer = document.createElement('div');
            scheduleBlockLayer.className = 'schedule-block-layer';
            scheduleBlockLayer.setAttribute('aria-label', 'Adjustable schedule blocks');
            wrapper.appendChild(scheduleBlockLayer);
        }

        return scheduleBlockLayer;
    }

    function positionScheduleBlock(block, cell, start, end, visualCell = cell, spanCells = [visualCell])
    {
        const wrapper = document.querySelector('[data-schedule-sheet]');
        const layer = getScheduleBlockLayer();
        const startY = scheduleYAtMinute(cell.dataset.day, cell.dataset.side, start, 'start');
        const endY = scheduleYAtMinute(cell.dataset.day, cell.dataset.side, end, 'end');
        if (!wrapper || !layer || startY === null || endY === null || endY <= startY) return false;

        const anchorRect = visualCell.getBoundingClientRect();
        spanCells = Array.isArray(spanCells) && spanCells.length ? spanCells : [visualCell];
        const lastRect = spanCells.length > 1 ? spanCells[spanCells.length - 1].getBoundingClientRect() : anchorRect;
        const wrapperRect = wrapper.getBoundingClientRect();
        block.style.left = (anchorRect.left - wrapperRect.left - wrapper.clientLeft + wrapper.scrollLeft) + 'px';
        block.style.top = (startY - wrapperRect.top - wrapper.clientTop + wrapper.scrollTop) + 'px';
        block.style.width = Math.max(anchorRect.width, lastRect.right - anchorRect.left) + 'px';
        block.style.height = (endY - startY) + 'px';
        block.dataset.duration = String(end - start);
        block.title = [cell.dataset.course, cell.dataset.subject, cell.dataset.instructor, formatScheduleRangeLabel(start, end), 'Drag the top or bottom grip to adjust by 30 minutes.'].filter(Boolean).join(' · ');
        block.setAttribute('aria-label', [cell.dataset.course || 'Scheduled class', cell.dataset.subject, cell.dataset.instructor, formatScheduleRangeLabel(start, end)].filter(Boolean).join(', ') + '.');
        block.classList.toggle('is-selected', visualCell.classList.contains('selected-cell'));
        return true;
    }

    function exactCellScheduleSignature(cell)
    {
        if (!cell || cell.dataset.manualMergeId || cell.dataset.reservationId) return '';
        const crossScheduleView = document.body.classList.contains('cross-scheduling-editor');
        if (cell.dataset.locked === '1' && (!crossScheduleView || !cell.dataset.scheduleId)) return '';
        const entries = getCellScheduleEntries(cell).filter(entry => String(entry.course || '').trim());
        if (entries.length !== 1) return '';
        const entry = entries[0];
        const range = parseScheduleRange(entry.time || cell.dataset.scheduleTime || cell.dataset.time || '');
        if (!range) return '';
        const sharedIds = Array.isArray(entry.shared_offering_ids)
            ? entry.shared_offering_ids.map(String).sort().join('|')
            : String(entry.shared_offering_ids || '').split(',').map(value => value.trim()).filter(Boolean).sort().join('|');
        return JSON.stringify([
            entry.course, entry.subject, entry.instructor, entry.faculty_id, entry.faculty_subject_id,
            entry.year, entry.major, entry.program, entry.department_id, entry.color, entry.subject_type,
            range.start, range.end, sharedIds
        ].map(value => String(value ?? '').trim().toLowerCase()));
    }

    function getAdjacentIdenticalScheduleCells(anchor, allCells)
    {
        const signature = exactCellScheduleSignature(anchor);
        if (!signature) return [anchor];
        const row = anchor.parentElement;
        const side = String(anchor.dataset.side || '');
        const rowCells = Array.from(row.querySelectorAll('.schedule-cell'))
            .filter(cell => String(cell.dataset.side || '') === side && !cell.hidden)
            .sort((a, b) => a.getBoundingClientRect().left - b.getBoundingClientRect().left);
        const anchorIndex = rowCells.indexOf(anchor);
        if (anchorIndex < 0) return [anchor];
        const run = [anchor];
        for (let index = anchorIndex + 1; index < rowCells.length; index++) {
            if (exactCellScheduleSignature(rowCells[index]) !== signature) break;
            const previousRect = run[run.length - 1].getBoundingClientRect();
            const nextRect = rowCells[index].getBoundingClientRect();
            if (Math.abs(nextRect.left - previousRect.right) > 3) break;
            run.push(rowCells[index]);
        }
        return run;
    }

    function selectAutoMergedScheduleCells(cells, primary, toggle = true)
    {
        const alreadySelected = cells.length > 0 && cells.every(cell => cell.classList.contains('selected-cell'));
        if (toggle && alreadySelected) {
            cells.forEach(function(cell) {
                cell.classList.remove('selected-cell');
                cell.setAttribute('aria-selected', 'false');
            });
            selectedCell = null;
            selectedScheduleEntry = null;
            activeAutoMergeCells = [];
            updateMergeControls();
            renderScheduleBlocks();
            return false;
        }
        document.querySelectorAll('#scheduleTable .schedule-cell.selected-cell').forEach(function(cell) {
            cell.classList.remove('selected-cell');
            cell.setAttribute('aria-selected', 'false');
        });
        cells.forEach(function(cell) {
            cell.classList.add('selected-cell');
            cell.setAttribute('aria-selected', 'true');
        });
        selectedCell = primary;
        activeAutoMergeCells = cells.slice();
        selectCell(primary, false);
        updateMergeControls();
        renderScheduleBlocks();
        return true;
    }

    function fitScheduleBlockContent(block)
    {
        const content = block?.querySelector('.schedule-cell-content');
        if (!content) return;

        block.classList.add('schedule-content-fit');
        const blockBottom = block.getBoundingClientRect().bottom - 4;
        let scale = 1;
        const minimumScale = 0.64;

        const contentBottom = function() {
            const children = Array.from(content.children);
            if (!children.length) return content.getBoundingClientRect().top;
            return Math.max.apply(null, children.map(function(child) {
                return child.getBoundingClientRect().bottom;
            }));
        };

        while (scale > minimumScale && contentBottom() > blockBottom) {
            scale = Math.max(minimumScale, scale - 0.04);
            block.style.setProperty('--schedule-fit-scale', scale.toFixed(2));
        }

        block.dataset.contentScale = scale.toFixed(2);
    }

    function autoMergedEntryIsStillExact(cells)
    {
        if (!Array.isArray(cells) || cells.length < 2) return false;
        const signature = exactCellScheduleSignature(cells[0]);
        return Boolean(signature) && cells.every(cell => exactCellScheduleSignature(cell) === signature);
    }

    function addScheduleResizeHandle(block, cell, edge, visualCell = cell, spanCells = [visualCell])
    {
        const handle = document.createElement('button');
        handle.type = 'button';
        handle.className = 'schedule-resize-handle schedule-resize-handle-' + edge;
        handle.dataset.resizeEdge = edge;
        handle.setAttribute('aria-label', edge === 'start' ? 'Adjust schedule start time' : 'Adjust schedule end time');
        handle.title = edge === 'start'
            ? 'Drag up or down to adjust the class start time in 30-minute steps'
            : 'Drag up or down to adjust the class end time in 30-minute steps';
        handle.innerHTML = '<span></span>';
        let resizeGestureActive = false;

        function beginResize(event)
        {
            if (event.button !== undefined && event.button !== 0) return;
            if (resizeGestureActive) return;
            event.preventDefault();
            event.stopPropagation();

            // Select the cell first; the next drag of a resize grip adjusts it.
            if (!visualCell.classList.contains('selected-cell')) {
                visualCell.click();
                return;
            }
            resizeGestureActive = true;
            if (event.pointerId !== undefined && handle.setPointerCapture) {
                try { handle.setPointerCapture(event.pointerId); } catch (error) { /* Window-level tracking remains available. */ }
            }
            const pointerId = event.pointerId;

            const initial = parseScheduleRange(cell.dataset.scheduleTime || cell.dataset.time || '');
            if (!initial) {
                resizeGestureActive = false;
                return;
            }

            const original = { start: initial.start, end: initial.end };
            const spanDays = new Set(spanCells.map(item => String(item.dataset.day || '')));
            const snapshots = new Map(Array.from(document.querySelectorAll('#scheduleTable .schedule-cell'))
                .filter(function(item) {
                    return spanDays.has(String(item.dataset.day || ''))
                        && item.dataset.side === cell.dataset.side
                        && item.dataset.course
                        && !item.dataset.reservationId;
                })
                .map(function(item) {
                    const range = parseScheduleRange(item.dataset.scheduleTime || item.dataset.time || '');
                    return [item, range ? {
                        range: range,
                        scheduleTime: item.dataset.scheduleTime || item.dataset.time || '',
                        startMinute: item.dataset.scheduleStartMinute || '',
                        endMinute: item.dataset.scheduleEndMinute || '',
                        baseTime: item.dataset.scheduleBaseTime || item.dataset.time || '',
                        rowPosition: item.dataset.rowPosition || '',
                        entryTimes: getCellScheduleEntries(item).map(entry => ({
                            entry: entry,
                            time: entry.time || '',
                            base_time: entry.base_time || '',
                            row_position: entry.row_position,
                        })),
                    } : null];
                })
                .filter(function(entry) { return entry[1] !== null; }));
            let current = { start: initial.start, end: initial.end };
            let lastError = '';
            let didResize = false;
            block.classList.add('is-resizing');
            const move = function(moveEvent) {
                if (moveEvent.cancelable) moveEvent.preventDefault();

                const minute = scheduleMinuteAtY(cell.dataset.day, cell.dataset.side, moveEvent.clientY);
                if (minute === null) return;

                const candidate = {
                    start: edge === 'start' ? minute : original.start,
                    end: edge === 'end' ? minute : original.end
                };

                const plans = [];
                const plannedDays = new Set();
                let invalidPlan = false;
                spanCells.forEach(function(spanCell) {
                    const spanRange = parseScheduleRange(spanCell.dataset.scheduleTime || spanCell.dataset.time || '');
                    if (!spanRange) {
                        invalidPlan = true;
                        return;
                    }
                    const day = String(spanCell.dataset.day || '');
                    if (plannedDays.has(day)) return;
                    plannedDays.add(day);
                    const daySnapshots = new Map(Array.from(snapshots.entries()).filter(([snapshotCell]) => snapshotCell.dataset.day === day));
                    const dayOriginal = { start: spanRange.start, end: spanRange.end };
                    const dayPlan = buildScheduleResizePlan(spanCell, candidate, dayOriginal, edge, daySnapshots);
                    if (!dayPlan) {
                        invalidPlan = true;
                        return;
                    }
                    plans.push(dayPlan);
                });
                if (invalidPlan || !plans.length) {
                    lastError = 'A class must be at least 30 minutes long and cannot cross the lunch break.';
                    block.classList.add('resize-conflict');
                    return;
                }

                snapshots.forEach(function(snapshot, plannedCell) {
                    plannedCell.dataset.scheduleTime = snapshot.scheduleTime;
                    plannedCell.dataset.scheduleStartMinute = snapshot.startMinute;
                    plannedCell.dataset.scheduleEndMinute = snapshot.endMinute;
                    plannedCell.dataset.scheduleBaseTime = snapshot.baseTime;
                    plannedCell.dataset.rowPosition = snapshot.rowPosition;
                    snapshot.entryTimes.forEach(function(saved) {
                        saved.entry.time = saved.time;
                        saved.entry.base_time = saved.base_time;
                        saved.entry.row_position = saved.row_position;
                    });
                });
                plans.forEach(function(plan) {
                    plan.forEach(function(range, plannedCell) {
                        plannedCell.dataset.scheduleTime = formatScheduleRange(range.start, range.end);
                        plannedCell.dataset.scheduleStartMinute = String(range.start);
                        plannedCell.dataset.scheduleEndMinute = String(range.end);
                        const position = scheduleRowPositionAtMinute(plannedCell.dataset.day, plannedCell.dataset.side, range.start);
                        if (position) {
                            plannedCell.dataset.rowPosition = String(position.index);
                            plannedCell.dataset.scheduleBaseTime = position.time;
                        }
                        getCellScheduleEntries(plannedCell).forEach(function(entry) {
                            entry.time = formatScheduleRange(range.start, range.end);
                            entry.base_time = plannedCell.dataset.scheduleBaseTime || plannedCell.dataset.time || '';
                            entry.row_position = Number(plannedCell.dataset.rowPosition || 0);
                        });
                    });
                });

                lastError = '';
                block.classList.remove('resize-conflict');
                current = candidate;
                didResize = current.start !== original.start || current.end !== original.end;
                const timeLabel = block.querySelector('.schedule-resize-time');
                if (timeLabel) {
                    timeLabel.textContent = formatScheduleRangeLabel(current.start, current.end);
                }
                positionScheduleBlock(block, cell, current.start, current.end, visualCell, spanCells);
            };

            const finish = function(upEvent) {
                if (upEvent && pointerId !== undefined && upEvent.pointerId !== pointerId) return;
                window.removeEventListener('pointermove', move);
                window.removeEventListener('mousemove', move);
                window.removeEventListener('pointerup', finish);
                window.removeEventListener('pointercancel', finish);
                window.removeEventListener('mouseup', finish);
                resizeGestureActive = false;
                block.classList.remove('is-resizing');

                if (lastError) {
                    showWarning(lastError);
                } else if (didResize) {
                    suppressScheduleBlockClickUntil = Date.now() + 500;
                    showSuccess('Adjusted to ' + formatScheduleRangeLabel(current.start, current.end) + '. Save the schedule to keep this time.');
                }

                renderScheduleBlocks();
            };

            window.addEventListener('pointermove', move);
            window.addEventListener('mousemove', move);
            window.addEventListener('pointerup', finish, { once: true });
            window.addEventListener('pointercancel', finish, { once: true });
            window.addEventListener('mouseup', finish, { once: true });
        }

        handle.addEventListener('pointerdown', beginResize);
        handle.addEventListener('mousedown', beginResize);
        block.appendChild(handle);
        return beginResize;
    }

    function renderScheduleBlocks()
    {
        const layer = getScheduleBlockLayer();
        if (!layer) return;
        layer.replaceChildren();

        const allCells = Array.from(document.querySelectorAll('#scheduleTable .schedule-cell'));

        if (isPublishedScheduleView) {
            allCells.forEach(function(cell) {
                cell.classList.remove('published-override-cell-highlight');
                if (!selectedPublishedOverride || String(selectedPublishedOverride.roomId || '') !== String(publishedRoomId)) return;
                if (String(cell.dataset.day || '').trim().toUpperCase().slice(0, 3) !== selectedPublishedOverride.day) return;
                const targetRange = selectedPublishedOverride.range;
                if (!targetRange) return;
                const hasMatchingClass = getCellScheduleEntries(cell).some(function(entry) {
                    const range = parseScheduleRange(entry.time || cell.dataset.scheduleTime || cell.dataset.time || '');
                    return range && range.start < targetRange.end && range.end > targetRange.start;
                });
                if (hasMatchingClass) cell.classList.add('published-override-cell-highlight');
            });
        }

        const handledAutoMergedCells = new Set();
        allCells.forEach(function(visualCell) {
            if (handledAutoMergedCells.has(visualCell)) return;
            if (visualCell.hidden) {
                visualCell.classList.remove('schedule-block-source');
                return;
            }

            const mergeId = visualCell.dataset.manualMergeId || '';
            const autoMergedCells = mergeId ? [visualCell] : getAdjacentIdenticalScheduleCells(visualCell, allCells);
            if (autoMergedCells.length > 1) autoMergedCells.forEach(cell => handledAutoMergedCells.add(cell));
            const mergeMembers = mergeId
                ? allCells.filter(function(member) { return member.dataset.manualMergeId === mergeId; })
                : autoMergedCells;
            const entries = (mergeId ? mergeMembers : autoMergedCells).flatMap(function(member) {
                if (member.dataset.reservationId) return [];
                return getCellScheduleEntries(member).map(function(entry) {
                    return { cell: member, entry: entry };
                });
            }).filter(function(item) { return String(item.entry.course || '').trim(); });
            const groupedEntries = mergeId || autoMergedCells.length === 1
                ? entries
                : entries.slice(0, 1);

            groupedEntries.forEach(function(item, entryIndex) {
                const cell = item.cell;
                const entry = item.entry;
                const range = parseScheduleRange(entry.time || cell.dataset.scheduleTime || cell.dataset.time || '');
                if (!range) return;

                entry.cell = cell;
                entry.time = formatScheduleRange(range.start, range.end);
                if (cell.dataset.course === entry.course && String(cell.dataset.scheduleId || '') === String(entry.schedule_id || '')) {
                    cell.dataset.scheduleTime = entry.time;
                    cell.dataset.scheduleStartMinute = String(range.start);
                    cell.dataset.scheduleEndMinute = String(range.end);
                }

                const block = document.createElement('div');
                block.className = 'schedule-resizable-block'
                    + (range.end - range.start <= 30 ? ' schedule-half-hour-entry' : '')
                    + (cell.dataset.locked === '1' ? ' schedule-merge-requestable' : '');
                if (cell.dataset.locked === '1') block.title = 'Right-click to request a course merge';
                if (!isPublishedScheduleView) {
                    block.tabIndex = 0;
                    block.setAttribute('role', 'button');
                } else {
                    block.classList.add('published-schedule-block');
                    block.style.cursor = 'default';
                }
                block.dataset.scheduleId = String(entry.schedule_id || '');
                block.dataset.entryIndex = String(entryIndex);
                block.style.backgroundColor = entry.color || cell.dataset.color || '#ffffff';

                const content = cell.dataset.course === entry.course
                    && String(cell.dataset.scheduleId || '') === String(entry.schedule_id || '')
                    ? cell.querySelector('.schedule-cell-content')?.innerHTML
                    : '<div class="schedule-cell-content">'
                        + '<div class="cell-course">' + escapeHtml(entry.course || '') + '</div>'
                        + '<div class="cell-subject">' + escapeHtml(entry.subject || '') + '</div>'
                        + '<div class="cell-instructor">' + escapeHtml(entry.instructor || '') + '</div>'
                        + '<div class="cell-year">' + escapeHtml(entry.year || '') + '</div>'
                        + '<div class="cell-major">' + escapeHtml(entry.major || '') + '</div>'
                        + '<div class="cell-program">' + escapeHtml(displayProgramCode(entry.program || '')) + '</div>'
                        + (entry.subject_type ? '<div class="cell-subject-type">' + escapeHtml(entry.subject_type) + '</div>' : '')
                        + '</div>';
                block.innerHTML = content || '';
                if (!mergeId && autoMergedCells.length > 1) {
                    const daysLabel = autoMergedCells.map(function(member) {
                        return String(member.dataset.day || '').trim().toUpperCase().slice(0, 3);
                    }).filter(Boolean).join(' · ');
                    const contentRoot = block.querySelector('.schedule-cell-content');
                    if (contentRoot && daysLabel) {
                        const daysBadge = document.createElement('div');
                        daysBadge.className = 'cell-days';
                        daysBadge.textContent = daysLabel;
                        contentRoot.prepend(daysBadge);
                    }
                }

                if (isPublishedScheduleView && selectedPublishedOverride) {
                    const targetDay = String(cell.dataset.day || '').trim().toUpperCase().slice(0, 3);
                    const targetRange = selectedPublishedOverride.range;
                    const correctRoom = String(selectedPublishedOverride.roomId || '') === String(publishedRoomId);
                    const correctDay = targetDay === selectedPublishedOverride.day;
                    const overlaps = targetRange && range.start < targetRange.end && range.end > targetRange.start;
                    if (correctRoom && correctDay && overlaps) {
                        block.classList.add('published-override-highlight');
                    }
                }

                const timeLabel = document.createElement('span');
                timeLabel.className = 'schedule-resize-time';
                timeLabel.textContent = formatScheduleRangeLabel(range.start, range.end);
                block.appendChild(timeLabel);

                if (!isPublishedScheduleView && cell.dataset.locked !== '1') block.addEventListener('click', function(event) {
                    if (Date.now() < suppressScheduleBlockClickUntil) {
                        event.preventDefault();
                        event.stopPropagation();
                        return;
                    }
                    if (event.target.closest('.schedule-resize-handle')) return;
                    event.preventDefault();
                    event.stopPropagation();
                    const groupSelected = autoMergedCells.length > 1
                        ? selectAutoMergedScheduleCells(autoMergedCells, visualCell)
                        : (visualCell.click(), visualCell.classList.contains('selected-cell'));
                    selectedScheduleEntry = groupSelected ? entry : null;
                    selectedScheduleInterval = null;
                });

                if (!isPublishedScheduleView) block.addEventListener('contextmenu', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (visualCell.classList.contains('locked-cell')) selectCell(visualCell, false, true);
                    else if (autoMergedCells.length > 1) selectAutoMergedScheduleCells(autoMergedCells, visualCell, false);
                    else if (!visualCell.classList.contains('selected-cell')) visualCell.click();
                    selectedScheduleEntry = entry;
                    selectedScheduleInterval = null;
                    openScheduleEntryPopup(visualCell, entry, null);
                });

                if (!isPublishedScheduleView && cell.dataset.locked !== '1') block.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        if (autoMergedCells.length > 1) selectAutoMergedScheduleCells(autoMergedCells, visualCell);
                        else visualCell.click();
                        selectedScheduleEntry = entry;
                        selectedScheduleInterval = null;
                    }
                });

                const canResize = !isPublishedScheduleView && cell.dataset.locked !== '1' && entryIndex === 0;
                let resizeStart = null;
                let resizeEnd = null;
                if (canResize) {
                    resizeStart = addScheduleResizeHandle(block, cell, 'start', visualCell, autoMergedCells);
                    resizeEnd = addScheduleResizeHandle(block, cell, 'end', visualCell, autoMergedCells);
                    block.addEventListener('pointerdown', function(event) {
                        if (event.target.closest('.schedule-resize-handle')) return;
                        const rect = block.getBoundingClientRect();
                        const edgeSize = Math.min(28, Math.max(18, rect.height * 0.22));
                        if (event.clientY - rect.top <= edgeSize) resizeStart(event);
                        else if (rect.bottom - event.clientY <= edgeSize) resizeEnd(event);
                    });
                }

                layer.appendChild(block);
                if (!positionScheduleBlock(block, cell, range.start, range.end, visualCell, !mergeId ? autoMergedCells : [visualCell])) {
                    block.remove();
                    return;
                }
                fitScheduleBlockContent(block);
                const blockActionHint = isPublishedScheduleView
                    ? 'Published schedule'
                    : (cell.dataset.locked === '1' ? 'Right-click to request a course merge.' : 'Right-click to edit this entry.');
                block.title = [entry.course, entry.subject, entry.instructor, formatScheduleRangeLabel(range.start, range.end), blockActionHint].filter(Boolean).join(' · ');
                if (!isPublishedScheduleView) block.setAttribute('aria-label', [entry.course || 'Scheduled class', entry.subject, entry.instructor, formatScheduleRangeLabel(range.start, range.end), blockActionHint].filter(Boolean).join(', ') + '.');
                visualCell.classList.add('schedule-block-source');
                if (cell !== visualCell) cell.classList.add('schedule-block-source');
            });

            if (!entries.length) visualCell.classList.remove('schedule-block-source');
        });

    }

    if (isPublishedScheduleView) {
        document.querySelectorAll('.published-override-button').forEach(function(button) {
            button.addEventListener('click', function() {
                const wasSelected = button.getAttribute('aria-pressed') === 'true';
                document.querySelectorAll('.published-override-button').forEach(function(item) {
                    item.setAttribute('aria-pressed', 'false');
                });
                if (wasSelected) {
                    selectedPublishedOverride = null;
                } else {
                    button.setAttribute('aria-pressed', 'true');
                    const range = parseScheduleRange(button.dataset.overrideStart + '-' + button.dataset.overrideEnd);
                    selectedPublishedOverride = {
                        roomId: String(button.dataset.overrideRoomId || ''),
                        day: String(button.dataset.overrideDay || '').toUpperCase().slice(0, 3),
                        range: range,
                    };
                }
                renderScheduleBlocks();
            });
        });
    }


    /*
|--------------------------------------------------------------------------
| CLOSE PROGRAM POPUP
|--------------------------------------------------------------------------
*/

const closeMergeProgramPopup =
    document.getElementById(
        'closeMergeProgramPopup'
    );

const cancelMergeProgram =
    document.getElementById(
        'cancelMergeProgram'
    );

const mergeProgramModal =
    document.getElementById(
        'mergeProgramSelection'
    );


/*
|--------------------------------------------------------------------------
| X BUTTON
|--------------------------------------------------------------------------
*/

if (closeMergeProgramPopup) {

    closeMergeProgramPopup.onclick =
        function()
        {
            mergeProgramModal.style.display =
                'none';

            document.getElementById('mergeProgramSelect').value = '';
        };
}


/*
|--------------------------------------------------------------------------
| CANCEL BUTTON
|--------------------------------------------------------------------------
*/

if (cancelMergeProgram) {

    cancelMergeProgram.onclick =
        function()
        {
            mergeProgramModal.style.display =
                'none';

            document.getElementById('mergeProgramSelect').value = '';
        };
}


/*
|--------------------------------------------------------------------------
| CLICK OUTSIDE POPUP
|--------------------------------------------------------------------------
*/

const mergeProgramOverlay =
    document.querySelector(
        '.program-popup-overlay'
    );

if (mergeProgramOverlay) {

    mergeProgramOverlay.onclick =
        function()
        {
            mergeProgramModal.style.display =
                'none';

            document.getElementById('mergeProgramSelect').value = '';
        };
}
// ------------------------------------------------------------
// Existing time-cell events
attachTimeCellEvents();

const scheduleBlockWrapper = document.querySelector('[data-schedule-sheet]');
scheduleBlockWrapper?.addEventListener('scroll', renderScheduleBlocks, { passive: true });
window.addEventListener('resize', renderScheduleBlocks);
if (window.ResizeObserver) {
    const scheduleBlockResizeObserver = new ResizeObserver(renderScheduleBlocks);
    scheduleBlockResizeObserver.observe(document.getElementById('scheduleTable'));
}

</script>

@php
    /*
    |--------------------------------------------------------------------------
    | RESERVATION AUTO-REFRESH DATA
    |--------------------------------------------------------------------------
    */
    $reservationRefreshData = ($reservations ?? collect())
        ->map(function ($reservation) {
            return [
                'status' => $reservation->status,
                'date' => $reservation->date
                    ? \Carbon\Carbon::parse($reservation->date)->format('Y-m-d')
                    : null,
                'end_time' => $reservation->end_time
                    ? \Carbon\Carbon::parse($reservation->end_time)->format('H:i:s')
                    : null,
            ];
        })
        ->values()
        ->toArray();
@endphp

<script>
/* ================================================================
   TEMPORARY RESERVATION AUTO-REFRESH
   ---------------------------------------------------------------
================================================================ */
(function () {
    const reservations = @json($reservationRefreshData);

    let nearestExpiry = null;

    reservations.forEach(function (reservation) {
        if (!reservation.date || !reservation.end_time) {
            return;
        }

        if (reservation.status !== 'Reserved') {
            return;
        }

        const target = new Date(
            reservation.date + 'T' + reservation.end_time
        );

        if (Number.isNaN(target.getTime())) {
            return;
        }

        if (target <= new Date()) {
            return;
        }

        if (!nearestExpiry || target < nearestExpiry) {
            nearestExpiry = target;
        }
    });

    if (nearestExpiry) {
        const delay = Math.max(
            1000,
            nearestExpiry.getTime() - Date.now() + 1000
        );

        setTimeout(function () {
            window.location.reload();
        }, delay);
    }
})();
</script>

<script>
window.applyApprovedScheduleMerge = function (scheduleIds, course) {
    const ids = new Set((Array.isArray(scheduleIds) ? scheduleIds : [scheduleIds]).map(String));
    if (!course || !ids.size) return;

    const uniqueValues = function (values) {
        const seen = new Set();
        return values.map(value => String(value || '').trim()).filter(function (value) {
            const key = value.toLocaleLowerCase();
            if (!value || seen.has(key)) return false;
            seen.add(key);
            return true;
        });
    };
    const programCode = function (program) {
        return String(program || '').split(/\s*\/\s*/).map(function (value) {
            const match = value.trim().match(/\(([^()]*)\)\s*$/);
            return match ? match[1].trim() : value.trim();
        }).filter(Boolean).join(' / ');
    };

    const cells = Array.from(document.querySelectorAll('#scheduleTable .schedule-cell'))
        .filter(cell => ids.has(String(cell.dataset.scheduleId || '')));

    cells.forEach(function (cell) {
        let merged = [];
        try { merged = JSON.parse(cell.dataset.approvedMergedCourses || '[]'); } catch (error) {}
        if (!merged.some(item => String(item.id) === String(course.id))) merged.push(course);
        cell.dataset.approvedMergedCourses = JSON.stringify(merged);

        const content = cell.querySelector('.schedule-cell-content');
        if (!content) return;
        const setText = function (selector, values) {
            const displayValues = uniqueValues(values);
            let node = content.querySelector(selector);
            if (!node && displayValues.length) {
                node = document.createElement('div');
                node.className = selector.slice(1);
                content.appendChild(node);
            }
            if (node) node.textContent = displayValues.join(' / ');
        };

        setText('.cell-course', [cell.dataset.course, ...merged.map(item => item.course_code)]);
        setText('.cell-subject', [cell.dataset.subject, ...merged.map(item => item.course_title)]);
        setText('.cell-year', [cell.dataset.year, ...merged.map(item => item.year_level)]);
        setText('.cell-major', [cell.dataset.major, ...merged.map(item => item.major)]);
        setText('.cell-subject-type', [cell.dataset.subjectType, ...merged.map(item => item.subject_type)]);

        const programs = uniqueValues([
            programCode(cell.dataset.program),
            ...merged.map(item => programCode(item.program_code || item.program))
        ]);
        let programNode = content.querySelector('.cell-program');
        if (!programNode && programs.length) {
            programNode = document.createElement('div');
            programNode.className = 'cell-program';
            content.appendChild(programNode);
        }
        if (programNode) programNode.textContent = programs.join(' / ');
    });

    if (typeof renderScheduleBlocks === 'function') renderScheduleBlocks();
};
</script>


@include('chair.partials.schedule-merge-request-modal')

<div id="excelActionConfirm" class="excel-action-confirm" role="alertdialog" aria-modal="true" aria-labelledby="excelActionConfirmTitle" aria-describedby="excelActionConfirmMessage" hidden>
    <section class="excel-action-confirm-card">
        <div class="excel-action-confirm-icon" aria-hidden="true"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h2 id="excelActionConfirmTitle">Are you sure?</h2>
        <p id="excelActionConfirmMessage">Permanently delete the selected published schedule entries from the database? This cannot be undone.</p>
        <div class="excel-action-confirm-actions">
            <button type="button" id="excelActionConfirmCancel">Go back</button>
            <button type="button" class="confirm" id="excelActionConfirmAccept">Clear</button>
        </div>
    </section>
</div>

<script>
(function () {
    const trigger = document.getElementById('clearCellButton');
    const dialog = document.getElementById('excelActionConfirm');
    const accept = document.getElementById('excelActionConfirmAccept');
    const cancel = document.getElementById('excelActionConfirmCancel');
    if (!trigger || !dialog || !accept || !cancel) return;

    let approved = false;
    let previousFocus = null;
    const closeDialog = function () {
        dialog.hidden = true;
        document.body.classList.remove('system-confirm-open');
        if (previousFocus?.isConnected) previousFocus.focus();
        previousFocus = null;
    };

    trigger.addEventListener('click', function (event) {
        if (approved) {
            approved = false;
            return;
        }
        event.preventDefault();
        event.stopImmediatePropagation();
        previousFocus = document.activeElement;
        dialog.hidden = false;
        document.body.classList.add('system-confirm-open');
        accept.focus();
    }, true);

    accept.addEventListener('click', function () {
        closeDialog();
        approved = true;
        trigger.click();
    });
    cancel.addEventListener('click', closeDialog);
    dialog.addEventListener('click', function (event) {
        if (event.target === dialog) closeDialog();
    });
    document.addEventListener('keydown', function (event) {
        if (!dialog.hidden && event.key === 'Escape') {
            event.preventDefault();
            closeDialog();
        }
    });
})();
</script>

</body>

</html>
