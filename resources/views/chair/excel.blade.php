<!DOCTYPE html>
<html lang="en">

<head>
  

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/excel.css') }}">

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Class Program - {{ $room->room_name }}
    </title>



<style>
.schedule-cell.reserved-cell{background:#fff7ed!important;border:2px solid #f59e0b!important;cursor:not-allowed!important;position:relative}
.reservation-cell-label{display:inline-block;margin-top:6px;padding:3px 7px;border-radius:999px;background:#f59e0b;color:#fff;font-size:9px;font-weight:800;letter-spacing:.3px}
.reservation-cell-user{margin-top:4px;color:#92400e;font-size:10px;font-weight:800}
.reservation-cell-time{margin-top:2px;color:#92400e;font-size:9px;font-weight:700}
.reservation-cell-note{margin-top:4px;color:#92400e;font-size:9px;line-height:1.25}
.schedule-cell.merged-schedule-cell{vertical-align:middle;text-align:center;}
.schedule-cell.merged-schedule-cell .cell-course{font-size:13px;font-weight:800;}
.add-time-before-break-row td{
        padding:8px 10px;
        background:#f8fafc;
        border-top:1px dashed #cbd5e1;
        border-bottom:1px dashed #cbd5e1;
        text-align:center !important;
        vertical-align:middle;
    }

    .add-time-before-break-row .btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        margin:0 auto;
    }
</style>
</head>


<body>


<div class="page">


    <!-- =========================================================
         BACK
    ========================================================== -->

    <div class="top-bar">

        <a
            href="{{ route(
                'chair.setschedule',
                [
                    'department' =>
                        $room->department_id
                ]
            ) }}"
            class="back-link"
        >
            ← Back to Set Schedule
        </a>

    </div>


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

    <div class="program-header">

        <div><div class="program-title">
            CLASS PROGRAM
        </div><div style="margin-top:6px;color:#2e7d32;font-size:12px;font-weight:700;">Active Term: {{ $activeTerm->academic_year ?? ($academicYear ?? '') }} • {{ $activeTerm->semester ?? ($semester ?? '') }}</div></div>

       <div class="schedule-information">

    <div class="schedule-info-field">
        <label for="semester">
            Semester
        </label>

        <input
            type="text"
            id="semester"
            value="{{ $activeTerm->semester ?? ($semester ?? '') }}"
            readonly
        >
    </div>


    <div class="schedule-info-field">
        <label for="academic_year">
            Academic Year
        </label>

        <input
            type="text"
            id="academic_year"
            value="{{ $activeTerm->academic_year ?? ($academicYear ?? '') }}"
            readonly
        >
    </div>

</div>


        @php

            $isEditingDraft =
                $isEditingDraft ?? false;

        @endphp


        @if($isEditingDraft)

            <div class="draft-mode">
                EDITING SAVED DRAFT
            </div>

        @endif


        <div class="program-meta">

            <div class="meta-item">

                <strong>
                    Department:
                </strong>

                {{ $room->department->name ?? 'N/A' }}

            </div>


            <div class="meta-item">

                <strong>
                    Building:
                </strong>

                {{ $room->building->name ?? $room->building ?? 'N/A' }}

            </div>


            <div class="meta-item">

                <strong>
                    Room:
                </strong>

                {{ $room->room_name }}

            </div>


            <div class="meta-item">

                <strong>
                    Scheduling Department:
                </strong>

                {{ auth()->user()->department->name ?? 'N/A' }}

            </div>

        </div>

    </div>


    <!-- =========================================================
         FACULTY SUBJECT DATA
    ========================================================== -->

    @php

        $facultySubjectsData =
            $facultySubjects
                ->map(function ($item) {

                   return [
    'id' => $item->id,
    'course_code' => $item->course_code,
    'subject' => $item->subject,
    'year_level' => $item->year_level,
    'major' => $item->major,
    'program' => $item->program,

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
    >

        @csrf


        <input
            type="hidden"
            name="room_id"
            value="{{ $room->id }}"
        >


        <!-- =====================================================
             EDITOR
        ====================================================== -->

        <div class="editor-panel">


            <div class="editor-title">
                Schedule Entry
            </div>


            <div class="editor-subtitle">

                Select a timetable cell and enter a course code.
                The assigned subject, faculty member and year level
                will load automatically from Faculty Setup.

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
                        Faculty
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

<button type="button" id="mergeClassToggleBtn" class="merge-class-toggle-btn">
    Merge Class (optional)
</button>

            </div>


            <div
                id="lookupStatus"
                class="lookup-status"
            ></div>


            <!-- CELL CONTROLS -->

            <div class="cell-controls">

                <div class="color-group">

                    <label>
                        Cell Color
                    </label>

                    <input
                        type="color"
                        id="cellColor"
                        value="#ffffff"
                    >

                </div>


                <button
                    type="button"
                    class="btn btn-green"
                    id="saveCellButton"
                >
                    Save Cell
                </button>


                <button
                    type="button"
                    class="btn btn-red"
                    id="clearCellButton"
                >
                    Clear Cell
                </button>

            </div>

<!-- MERGE CLASS SECTION -->
<div
    class="merge-section"
    id="mergeExistingClass"
    style="display: none;"
>

    <div class="merge-title">
        Merge Existing Class
    </div>

    <p class="merge-description">
        Search for an existing class from another department,
        or browse the department schedules manually.
    </p>


    <!-- SEARCH BY COURSE CODE -->

    <div class="merge-search-box">

        <label for="mergeCourseCode">
            Search by Course Code
        </label>

        <div class="merge-search-row">

            <input
                type="text"
                id="mergeCourseCode"
                placeholder="Example: IT101"
            >

            <button
                type="button"
                id="searchMergeBtn"
                class="btn btn-green"
            >
                Search
            </button>

        </div>

    </div>


    <div class="merge-or">
        OR
    </div>


    <!-- MANUAL BROWSE -->

    <div class="merge-browse-box">

        <div class="merge-browse-title">
            Browse Departments
        </div>

        <div
            id="mergeDepartments"
            class="merge-departments"
        >
            Loading departments...
        </div>

    </div>


    <!-- SEARCH / BROWSE STATUS -->

    <div
        id="mergeSearchStatus"
        class="merge-search-status"
    ></div>


    <!-- RESULTS -->

    <div
        id="mergeResults"
        class="merge-results"
        style="display:none;"
    ></div>


    <!-- MANUAL SCHEDULE VIEW -->

    <div
        id="mergeScheduleView"
        class="merge-schedule-view"
        style="display:none;"
    ></div>

    <!-- PROGRAM SELECTION POPUP -->
<div id="mergeProgramSelection" class="program-popup" style="display:none;">

    <div class="program-popup-overlay"></div>

    <div class="program-popup-card">

        <!-- X BUTTON -->
        <button
            type="button"
            id="closeMergeProgramPopup"
            class="program-popup-close"
        >
            &times;
        </button>

        <h3>Select Program</h3>

        <p>
            Choose the program you want to merge.
        </p>

        <label for="mergeProgramSelect">
            Program
        </label>

        <select id="mergeProgramSelect">
            <option value="">
                Select Program
            </option>
        </select>

        <div class="program-popup-actions">

            <button
                type="button"
                id="cancelMergeProgram"
                class="program-popup-cancel"
            >
                Cancel
            </button>

            <button
                type="button"
                id="confirmMergeProgram"
                class="program-popup-confirm"
            >
                Continue
            </button>

        </div>

    </div>

</div>

</div>

            <div class="instruction">

                <strong>
                    How to use:
                </strong>

                Select an empty or editable timetable cell,
                enter the Course Code, then click
                <strong>Save Cell</strong>.

                <br><br>

                Subject, Faculty and Year Level are filled automatically.

                <br><br>

                <strong>
                    Time:
                </strong>

                Manually edit the TIME column.

                <br><br>

                <strong>
                    🔒 Locked:
                </strong>

                Existing schedules owned by another department
                cannot be changed.

            </div>


        </div>


        <!-- =====================================================
             TABLE
        ====================================================== -->

        <div class="table-wrapper">

            <table id="scheduleTable">

             <thead>
    <tr>
        <th class="time-header">TIME</th>

        <th data-day="MON">MON</th>
        <th data-day="WED">WED</th>
        <th data-day="FRI">FRI</th>

        <th class="time-header">TIME</th>

        <th data-day="TUE">TUE</th>
        <th data-day="THU">THU</th>
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

    $chairDepartmentId = auth()->user()->department_id;

    /*
    |--------------------------------------------------------------------------
    | SAFE COLLECTIONS
    |--------------------------------------------------------------------------
    */

    $drafts = $drafts ?? collect();
    $schedules = $schedules ?? collect();
    $reservations = $reservations ?? collect();

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

        if ($hour >= 1 && $hour <= 5) {
            $hour += 12;
        }

        return ($hour * 60) + $minute;
    };

    /*
    |--------------------------------------------------------------------------
    | SAFE DRAFT MODE
    |--------------------------------------------------------------------------
    */

    $isEditingDraft = $isEditingDraft ?? false;

    /*
    |--------------------------------------------------------------------------
    | SELECT SCHEDULE SOURCE
    |--------------------------------------------------------------------------
    */

    $scheduleRows = $isEditingDraft
        ? $drafts
        : $schedules;

    /*
    |--------------------------------------------------------------------------
    | DEFAULT TIME ROWS
    |--------------------------------------------------------------------------
    |
    | LEFT  = MWF
    | RIGHT = TTHS
    |
    */

    $defaultTimes = [
        '8:00-9:00',
        '9:00-10:00',
        '10:00-11:00',
        '11:00-12:00',
        '1:00-2:00',
        '2:00-3:00',
        '3:00-4:00',
        '4:00-5:00',
    ];

    /*
    |--------------------------------------------------------------------------
    | SCHEDULE MAP
    |--------------------------------------------------------------------------
    |
    | KEY = DAY|TIME
    |
    */

    $scheduleMap = [];

    foreach ($scheduleRows as $row) {

        $key =
            strtoupper(trim($row->day))
            . '|'
            . trim($row->time);

        $scheduleMap[$key] = $row;
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

   $leftTimes = collect($defaultTimes);
$rightTimes = collect($defaultTimes);

/*
|--------------------------------------------------------------------------
| Replace default row time with saved edited time
|--------------------------------------------------------------------------
| row_position identifies the visual timetable row.
| This prevents an edited time from becoming a second row.
*/

foreach ($scheduleRows as $row) {
    $existingTime = trim($row->time);

    if ($existingTime === '') {
        continue;
    }

    $position = $row->row_position;

    if ($position === null) {
        continue;
    }

    $position = (int) $position;

    $day = strtoupper(trim($row->day));

    if (
        in_array($day, ['MON', 'WED', 'FRI'])
        &&
        isset($leftTimes[$position])
    ) {
        $leftTimes[$position] = $existingTime;
    }

    if (
        in_array($day, ['TUE', 'THU', 'SAT'])
        &&
        isset($rightTimes[$position])
    ) {
        $rightTimes[$position] = $existingTime;
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

    // Treat 1:00-5:00 as afternoon times
    if ($hour >= 1 && $hour <= 5) {
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

    /*
    |--------------------------------------------------------------------------
    | MAKE ROW COUNT THE SAME
    |--------------------------------------------------------------------------
    */

    $rowCount = max(
        $leftTimes->count(),
        $rightTimes->count()
    );

@endphp


@for($i = 0; $i < $rowCount; $i++)

    @php

        $leftTime =
            $leftTimes[$i] ?? '';

        $rightTime =
            $rightTimes[$i] ?? '';

    @endphp


    {{-- =========================================================
         NOON BREAK
         ========================================================= --}}

  @if($i === 4)
    <tr class="add-time-before-break-row">
        <td colspan="8">
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
        <td colspan="8">
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

       <td data-side="left" class="time-cell editable-time" contenteditable="true" spellcheck="false">
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
                    $scheduleMap[$key]
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

                data-row-position="{{ $schedule ? $schedule->row_position : '' }}"

                data-course="{{ $schedule ? $schedule->course_code : '' }}"

                data-subject="{{ $schedule ? $schedule->subject : '' }}"

                data-year="{{ $schedule ? ($schedule->year_level ?? '') : '' }}"

                data-major="{{ $schedule ? ($schedule->major ?? '') : '' }}"

                data-program="{{ $schedule ? ($schedule->program ?? '') : '' }}"

                data-instructor="{{ $schedule ? $schedule->instructor : '' }}"

                data-faculty-id="{{ $schedule ? ($schedule->faculty_id ?? '') : '' }}"

                data-department-id="{{ $schedule ? ($schedule->department_id ?? '') : '' }}"

                data-color="{{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }}"

                data-locked="{{ $isLocked ? '1' : '0' }}"

                data-reservation-id="{{ $reservation ? $reservation->id : '' }}"

                data-subject-type="{{ $schedule ? ($schedule->subject_type ?? '') : '' }}"

                style="
                    background-color:
                    {{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }};
                "
            >

                @if($schedule)

                    <div class="cell-course">
                        {{ $schedule->course_code }}
                    </div>

                    <div class="cell-subject">
                        {{ $schedule->subject }}
                    </div>

                    <div class="cell-instructor">
                        {{ $schedule->instructor }}
                    </div>

                    <div class="cell-year">
                        {{ $schedule->year_level ?? '' }}
                    </div>

                    @if($schedule->major)
                        <div class="cell-major">
                            {{ $schedule->major }}
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
            $schedule->program
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVED MERGED PROGRAMS
    |--------------------------------------------------------------------------
    |
    | Published Schedule records may have merges.
    | ScheduleDraft records do not necessarily have
    | the merges relationship.
    |
    */

    $scheduleMerges =
        $schedule->merges ?? collect();

    foreach ($scheduleMerges as $merge) {

        if (
            $merge->status === 'approved' &&
            $merge->facultySubject &&
            !empty(
                $merge->facultySubject->program
            )
        ) {

            $programs->push(
                $merge->facultySubject->program
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE DUPLICATES
    |--------------------------------------------------------------------------
    */

  


    $programs = $programs
        ->filter()
        ->unique()
        ->values();
@endphp

@if($programs->count() > 0)
    <div class="cell-program">
        {{ $programs->implode(' / ') }}
    </div>
@endif

                    @if($schedule->subject_type)
                        <div class="cell-subject-type">
                            {{ $schedule->subject_type }}
                        </div>
                    @endif

                    @if($reservation)
                        <div class="reservation-cell-label">
                            {{ $reservation->status === 'Reserved' ? 'TEMPORARY RESERVATION' : 'RESERVATION' }}
                        </div>
                        <div class="reservation-cell-user">
                            {{ $reservation->user?->name ?? 'Faculty' }}
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

                @else

                    <span class="empty-cell">
                        Empty
                    </span>

                @endif

            </td>

        @endforeach


        {{-- =====================================================
             RIGHT TIME — TTHS
             ===================================================== --}}

        <td
            data-side="right"
            class="time-cell editable-time"
            contenteditable="true"
            spellcheck="false"
        >
            {{ $rightTime }}
        </td>


        {{-- =====================================================
             TUE / THU
             ===================================================== --}}

        @foreach(['TUE', 'THU', 'SAT'] as $day)

            @php

                $key =
                    $day
                    . '|'
                    . trim($rightTime);

                $schedule =
                    $scheduleMap[$key]
                    ?? null;

                $reservation = $reservations->first(function ($item) use ($day, $rightTime, $timeToMinutes) {
                    if (strtoupper(trim((string) $item->day)) !== $day) {
                        return false;
                    }

                    $slotParts = explode('-', trim($rightTime), 2);
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
                data-side="right"
                class="schedule-cell {{ $isLocked ? 'locked-cell' : '' }} {{ $reservation ? 'reserved-cell' : '' }}"

                data-schedule-id="{{ $schedule ? $schedule->id : '' }}"

                data-day="{{ $day }}"

                data-time="{{ $rightTime }}"

                data-row-position="{{ $schedule ? $schedule->row_position : '' }}"

                data-course="{{ $schedule ? $schedule->course_code : '' }}"

                data-subject="{{ $schedule ? $schedule->subject : '' }}"

                data-year="{{ $schedule ? ($schedule->year_level ?? '') : '' }}"

                data-major="{{ $schedule ? ($schedule->major ?? '') : '' }}"

                data-program="{{ $schedule ? ($schedule->program ?? '') : '' }}"

                data-instructor="{{ $schedule ? $schedule->instructor : '' }}"

                data-faculty-id="{{ $schedule ? ($schedule->faculty_id ?? '') : '' }}"

                data-department-id="{{ $schedule ? ($schedule->department_id ?? '') : '' }}"

                data-color="{{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }}"

                data-locked="{{ $isLocked ? '1' : '0' }}"

                data-reservation-id="{{ $reservation ? $reservation->id : '' }}"

                data-subject-type="{{ $schedule ? ($schedule->subject_type ?? '') : '' }}"

                style="
                    background-color:
                    {{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }};
                "
            >

                @if($schedule)

                    <div class="cell-course">
                        {{ $schedule->course_code }}
                    </div>

                    <div class="cell-subject">
                        {{ $schedule->subject }}
                    </div>

                    <div class="cell-instructor">
                        {{ $schedule->instructor }}
                    </div>

                    <div class="cell-year">
                        {{ $schedule->year_level ?? '' }}
                    </div>

                    @if($schedule->major)
                        <div class="cell-major">
                            {{ $schedule->major }}
                        </div>
                    @endif

                    @if($schedule->program)
                        <div class="cell-program">
                            {{ $schedule->program }}
                        </div>
                    @endif

                    @if($schedule->subject_type)
                        <div class="cell-subject-type">
                            {{ $schedule->subject_type }}
                        </div>
                    @endif

                    @if($reservation)
                        <div class="reservation-cell-label">
                            {{ $reservation->status === 'Reserved' ? 'TEMPORARY RESERVATION' : 'RESERVATION' }}
                        </div>
                        <div class="reservation-cell-user">
                            {{ $reservation->user?->name ?? 'Faculty' }}
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

        </div>


        <!-- =====================================================
             ACTIONS
        ====================================================== -->

        <div class="editor-actions">


            <div class="action-group">

                <a
                    href="{{ route(
                        'chair.setschedule',
                        [
                            'department' =>
                                $room->department_id
                        ]
                    ) }}"
                    class="btn btn-gray"
                >
                    ← Back to Set Schedule
                </a>

            </div>


            <div class="action-group">

                @if($isEditingDraft)

                    <button
                        type="submit"
                        class="btn btn-yellow"
                        name="save_type"
                        value="draft"
                    >
                        Update Draft
                    </button>

                @else

                    <button
                        type="submit"
                        class="btn btn-yellow"
                        name="save_type"
                        value="draft"
                    >
                        Save as Draft
                    </button>

                @endif


                <button
                    type="submit"
                    class="btn btn-green"
                    name="save_type"
                    value="upload"
                >
                    Upload Schedule
                </button>

            </div>

        </div>


        <!-- HIDDEN INPUTS -->

        <div id="hiddenInputs"></div>


    </form>


</div>

@if(session('schedule_conflict'))

<div
    id="scheduleConflictModal"
    class="schedule-conflict-overlay"
>

    <div class="schedule-conflict-modal">

        <div class="schedule-conflict-icon">
            ⚠
        </div>

        <h2>
            Schedule Conflict
        </h2>

        <p class="schedule-conflict-message">

            This room already has a published
            schedule.

            <br><br>

            Publishing this draft will
            <strong>
                replace the existing schedule
            </strong>
            in this room.

        </p>


        <div class="schedule-conflict-room">

            <strong>
                Room:
            </strong>

            {{ $room->room_name }}

        </div>


        <p class="schedule-conflict-warning">

            Do you want to override the
            existing schedule and publish
            this draft?

        </p>


        <div class="schedule-conflict-actions">

            <button
                type="button"
                id="cancelScheduleConflict"
                class="btn btn-gray"
            >
                Cancel
            </button>


            <form
                method="POST"
                action="{{ route(
                    'chair.drafts.publish',
                    $room->id
                ) }}"
            >

                @csrf

                <input
                    type="hidden"
                    name="override"
                    value="1"
                >

                <button
                    type="submit"
                    class="btn btn-green"
                >
                    Override & Publish
                </button>

            </form>

        </div>

    </div>

</div>

@endif
<script>

    /*
    |--------------------------------------------------------------------------
    | FACULTY SUBJECT DATA
    |--------------------------------------------------------------------------
    */

    const facultySubjects =
        @json($facultySubjectsData);


    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR DEPARTMENT
    |--------------------------------------------------------------------------
    */

    const chairDepartmentId =
        @json(auth()->user()->department_id);

    const isEditingDraft =
    @json($isEditingDraft ?? false);
    /*
    |--------------------------------------------------------------------------
    | SELECTED CELL
    |--------------------------------------------------------------------------
    */

    let selectedCell = null;


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const scheduleForm =
        document.getElementById(
            'scheduleForm'
        );


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


    const lookupStatus =
        document.getElementById(
            'lookupStatus'
        );

    const subjectTypeInput =
    document.getElementById(
        'subject_type'
    );

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
                    '" was not found in Faculty Setup.'
                );

                return;

            }


            if (
                matches.length > 1
            ) {

                showWarning(
                    'This course code is assigned to multiple faculty members. Please make the course code unique.'
                );

                return;

            }


            const match =
                matches[0];

// AUTOMATIC SUBJECT TYPE
// Course code with "." = Lab
// Course code without "." = Lec

const automaticSubjectType =
    code.includes('.')
        ? 'Lab'
        : 'Lec';

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

            showSuccess(

                'Course found: ' +
                match.subject +
                ' — ' +
                match.instructor +
                ' — ' +
                match.year_level

            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SELECT CELL
    |--------------------------------------------------------------------------
    */

    function selectCell(
        cell
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
            cell.dataset.locked ===
            '1'
        ) {

            alert(
                'This schedule belongs to another department and is locked.'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE PREVIOUS SELECTION
        |--------------------------------------------------------------------------
        */

        if (selectedCell) {

            selectedCell.classList.remove(
                'selected'
            );

        }


        selectedCell =
            cell;


        selectedCell.classList.add(
            'selected'
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD CELL DATA
        |--------------------------------------------------------------------------
        */

        courseCodeInput.value =
            cell.dataset.course || '';


        subjectInput.value =
            cell.dataset.subject || '';


        yearLevelInput.value =
            cell.dataset.year || '';


        instructorInput.value =
            cell.dataset.instructor || '';

                       majorInput.value =
    cell.dataset.major || '';
    

        programInput.value =
            cell.dataset.program || '';

 
            subjectTypeInput.value =
    cell.dataset.subjectType || '';

        colorInput.value =
            cell.dataset.color ||
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
                matches.length === 1
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


        courseCodeInput.focus();

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

                cell.onclick =
                    function()
                    {

                        // Do nothing if the cell has a faculty reservation
                        if (
                            this.classList.contains(
                                'reserved-cell'
                            )
                        ) {
                            alert(
                                'This timetable cell is blocked by a faculty room reservation and cannot be scheduled.'
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

                        // Toggle selection
                        this.classList.toggle(
                            'selected-cell'
                        );

                    };

            }
        );

}



    /*
    |--------------------------------------------------------------------------
    | HORIZONTAL SAME-SCHEDULE MERGE
    |--------------------------------------------------------------------------
    */

    function scheduleMergeSignature(cell)
    {
        const fields = [
            'course',
            'subject',
            'year',
            'major',
            'program',
            'instructor',
            'facultyId',
            'departmentId',
            'color',
            'subjectType'
        ];

        return fields.map(function(field) {
            return String(cell.dataset[field] || '');
        }).join('||');
    }

    function getCellForDay(row, day)
    {
        return Array.from(
            row.querySelectorAll('.schedule-cell')
        ).find(function(cell) {
            return (
                cell.dataset.day === day &&
                !cell.dataset.mergedDays
            );
        }) || null;
    }

    function mergeDayGroup(row, days)
    {
        let i = 0;

        while (i < days.length) {

            const firstCell = getCellForDay(row, days[i]);

            if (!firstCell) {
                i++;
                continue;
            }

            if (!String(firstCell.dataset.course || '').trim()) {
                i++;
                continue;
            }

            const signature = scheduleMergeSignature(firstCell);
            let end = i + 1;

            while (end < days.length) {

                const nextCell = getCellForDay(row, days[end]);

                if (!nextCell) {
                    break;
                }

                if (
                    !String(nextCell.dataset.course || '').trim() ||
                    scheduleMergeSignature(nextCell) !== signature
                ) {
                    break;
                }

                end++;
            }

            const runLength = end - i;

            if (runLength >= 2) {

                const runDays = days.slice(i, end);
                const scheduleIds = {};

                runDays.forEach(function(day) {
                    const cell = getCellForDay(row, day);
                    scheduleIds[day] =
                        cell?.dataset.scheduleId || '';
                });

                firstCell.dataset.mergedDays =
                    runDays.join(',');

                firstCell.dataset.scheduleIds =
                    JSON.stringify(scheduleIds);

                firstCell.classList.add(
                    'merged-schedule-cell'
                );

                firstCell.colSpan = runLength;

                for (
                    let removeIndex = i + 1;
                    removeIndex < end;
                    removeIndex++
                ) {
                    const cellToRemove =
                        getCellForDay(row, days[removeIndex]);

                    if (cellToRemove) {
                        cellToRemove.remove();
                    }
                }
            }

            i = end;
        }
    }

    function mergeHorizontalSchedules()
    {
        document
            .querySelectorAll(
                '#scheduleTable tbody tr:not(.break-row):not(.add-time-before-break-row)'
            )
            .forEach(function(row) {

                mergeDayGroup(
                    row,
                    ['MON', 'WED', 'FRI']
                );

                mergeDayGroup(
                    row,
                    ['TUE', 'THU', 'SAT']
                );
            });
    }

    function unmergeCell(cell)
    {
        const mergedDays = String(
            cell.dataset.mergedDays || ''
        )
        .split(',')
        .map(function(day) {
            return day.trim();
        })
        .filter(Boolean);

        if (mergedDays.length <= 1) {
            return;
        }

        const row = cell.parentElement;
        const side = cell.dataset.side ||
            (['MON', 'WED', 'FRI'].includes(mergedDays[0])
                ? 'left'
                : 'right');

        cell.removeAttribute('colspan');
        cell.classList.remove('merged-schedule-cell');

        cell.dataset.mergedDays = '';
        cell.dataset.scheduleIds = '';
        cell.dataset.scheduleId = '';
        cell.dataset.day = mergedDays[0];

        let insertAfter = cell;

        for (let i = 1; i < mergedDays.length; i++) {

            const clone = cell.cloneNode(true);

            clone.dataset.day = mergedDays[i];
            clone.dataset.side = side;
            clone.dataset.scheduleId = '';
            clone.dataset.mergedDays = '';
            clone.dataset.scheduleIds = '';
            clone.dataset.course = '';
            clone.dataset.subject = '';
            clone.dataset.year = '';
            clone.dataset.major = '';
            clone.dataset.program = '';
            clone.dataset.instructor = '';
            clone.dataset.facultyId = '';
            clone.dataset.departmentId = chairDepartmentId;
            clone.dataset.color = '#ffffff';
            clone.dataset.subjectType = '';
            clone.dataset.locked = '0';
            clone.dataset.reservationId = '';
            clone.style.backgroundColor = '#ffffff';
            clone.innerHTML =
                '<span class="empty-cell">Empty</span>';

            row.insertBefore(
                clone,
                insertAfter.nextSibling
            );

            insertAfter = clone;
        }

        cell.dataset.course = '';
        cell.dataset.subject = '';
        cell.dataset.year = '';
        cell.dataset.major = '';
        cell.dataset.program = '';
        cell.dataset.instructor = '';
        cell.dataset.facultyId = '';
        cell.dataset.departmentId = chairDepartmentId;
        cell.dataset.color = '#ffffff';
        cell.dataset.subjectType = '';
        cell.dataset.locked = '0';
        cell.dataset.reservationId = '';
        cell.style.backgroundColor = '#ffffff';
        cell.innerHTML =
            '<span class="empty-cell">Empty</span>';

        attachCellEvents();
    mergeHorizontalSchedules();
    }

    attachCellEvents();

mergeHorizontalSchedules();
    /*
|--------------------------------------------------------------------------
| SAVE CELL
|--------------------------------------------------------------------------
*/

function saveCell()
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
            '" was not found. Add the course under Set Faculty first.'
        );

        return;
    }


    if (
        matches.length > 1
    ) {

        alert(
            'This course code is assigned to multiple faculty members.'
        );

        return;
    }


    const match =
        matches[0];


    /*
    |--------------------------------------------------------------------------
    | SUBJECT TYPE
    |--------------------------------------------------------------------------
    */

    const subjectType =
        course.includes('.')
            ? 'Lab'
            : 'Lec';


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

        const reservationCells =
            lockedCells.filter(
                cell => cell.dataset.reservationId
            );

        if (reservationCells.length > 0) {
            alert(
                'One or more selected cells have a faculty room reservation. The reservation must end before this slot can be scheduled.'
            );
        } else {
            alert(
                'One or more selected cells belong to another department and are locked.'
            );
        }

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | APPLY COURSE TO ALL SELECTED CELLS
    |--------------------------------------------------------------------------
    */

    selectedCells.forEach(
        function(cell)
        {

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

            cell.dataset.departmentId =
                chairDepartmentId;

            cell.dataset.color =
                color;

            cell.dataset.subjectType =
                subjectType;

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
                    match.program || ''
                ) +
                '</div>' +

                subjectTypeDisplay;


            /*
            |------------------------------------------------------------------
            | BACKGROUND COLOR
            |------------------------------------------------------------------
            */

            cell.style.backgroundColor =
                color;

        }
    );


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
        }
    );


    mergeHorizontalSchedules();

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
}
    /*
|--------------------------------------------------------------------------
| CLEAR CELL
|--------------------------------------------------------------------------
*/

function clearCell()
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
            'One or more selected cells belong to another department and are locked.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR ALL SELECTED CELLS
    |--------------------------------------------------------------------------
    */

    selectedCells.forEach(
        function(cell)
        {

            if (cell.dataset.mergedDays) {
                unmergeCell(cell);
            }

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

            cell.dataset.departmentId =
                '';

            cell.dataset.program =
                '';

            cell.dataset.color =
                '#ffffff';

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

        }
    );


    mergeHorizontalSchedules();

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
        cell.dataset.scheduleIds = '';
        cell.dataset.mergedDays = '';
        cell.dataset.course = '';
        cell.dataset.subject = '';
        cell.dataset.year = '';
        cell.dataset.major = '';
        cell.dataset.program = '';
        cell.dataset.instructor = '';
        cell.dataset.facultyId = '';
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

    // RIGHT: TIME + TUE + THU + SAT
    row.appendChild(createTimeCell('right'));
    row.appendChild(createScheduleCell('TUE', 'right'));
    row.appendChild(createScheduleCell('THU', 'right'));
    row.appendChild(createScheduleCell('SAT', 'right'));

    return row;
}

function finalizeNewScheduleRow(row)
{
    const leftTime = row.querySelector('.editable-time[data-side="left"]');
    const rightTime = row.querySelector('.editable-time[data-side="right"]');

    const syncRowTimes = function()
    {
        const leftValue = leftTime ? leftTime.innerText.trim() : '';
        const rightValue = rightTime ? rightTime.innerText.trim() : '';

        row.querySelectorAll('.schedule-cell[data-side="left"]').forEach(function(cell) {
            cell.dataset.time = leftValue;
        });

        row.querySelectorAll('.schedule-cell[data-side="right"]').forEach(function(cell) {
            cell.dataset.time = rightValue;
        });
    };

    syncRowTimes();
    attachCellEvents();
    attachTimeCellEvents();
    mergeHorizontalSchedules();

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

    /*
    |--------------------------------------------------------------------------
    | BUTTON EVENTS
    |--------------------------------------------------------------------------
    */

    document
        .getElementById(
            'saveCellButton'
        )
        .addEventListener(
            'click',
            saveCell
        );


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

        ['left', 'right'].forEach(function(side) {

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

            const days =
                side === 'left'
                    ? ['MON', 'WED', 'FRI']
                    : ['TUE', 'THU', 'SAT'];

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
    | A horizontally merged cell represents multiple weekdays. The same
    | class is submitted once for every weekday represented by that cell.
    | No vertical merging is performed.
    |--------------------------------------------------------------------------
    */

    rows.forEach(function(row) {

        const rowPosition =
            rowPositionMap.get(row);

        row.querySelectorAll('.schedule-cell').forEach(function(cell) {

            if (cell.dataset.locked === '1') {
                return;
            }

            const course =
                String(cell.dataset.course || '')
                    .trim()
                    .toUpperCase();

            if (!course) {
                return;
            }

            const matches =
                findCourse(course);

            if (matches.length !== 1) {
                return;
            }

            const match = matches[0];

            const subjectType =
                String(cell.dataset.subjectType || '').trim();

            const time =
                String(
                    cell.dataset.time ||
                    ''
                ).trim();

            if (!time) {
                return;
            }

            let days =
                String(
                    cell.dataset.mergedDays ||
                    cell.dataset.day ||
                    ''
                )
                .split(',')
                .map(function(day) {
                    return day.trim().toUpperCase();
                })
                .filter(Boolean);

            if (days.length === 0) {
                return;
            }

            let scheduleIds = {};

            try {
                scheduleIds =
                    JSON.parse(
                        cell.dataset.scheduleIds || '{}'
                    ) || {};
            } catch (error) {
                scheduleIds = {};
            }

            days.forEach(function(day) {

                const scheduleData = {

                    schedule_id:
                        scheduleIds[day] ||
                        (
                            days.length === 1
                                ? cell.dataset.scheduleId || ''
                                : ''
                        ),

                    row_position:
                        rowPosition,

                    day:
                        day,

                    time:
                        time,

                    course_code:
                        match.course_code,

                    subject:
                        match.subject,

                    year_level:
                        match.year_level,

                    major:
                        match.major || '',

                    program:
                        match.program || '',

                    instructor:
                        match.instructor,

                    faculty_id:
                        match.faculty_id || '',

                    department_id:
                        chairDepartmentId,

                    subject_type:
                        subjectType,

                    color:
                        cell.dataset.color ||
                        '#ffffff'
                };

                Object.keys(scheduleData).forEach(
                    function(name)
                    {
                        addHidden(
                            'schedule[' +
                            counter +
                            '][' +
                            name +
                            ']',
                            scheduleData[name]
                        );
                    }
                );

                counter++;
            });
        });
    });

    return counter;
}

    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    scheduleForm.addEventListener(
    'submit',
    function(event)
    {
        const submitter =
            event.submitter;

        /*
        |--------------------------------------------------------------------------
        | SAVE AS DRAFT
        |--------------------------------------------------------------------------
        */

        if (
            submitter &&
            submitter.value === 'draft'
        ) {

            const count =
                prepareSchedule();

            if (count === 0) {

                event.preventDefault();

                alert(
                    'There is no schedule entry to save. Select an editable or empty cell, enter a valid course code, and click Save Cell first.'
                );

                return;
            }

            scheduleForm.action =
                "{{ route('chair.save.draft') }}";

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL UPLOAD
        |--------------------------------------------------------------------------
        */

        if (!isEditingDraft) {

            const count =
                prepareSchedule();

            if (count === 0) {

                event.preventDefault();

                alert(
                    'There is no schedule entry to save. Select an editable or empty cell, enter a valid course code, and click Save Cell first.'
                );

                return;
            }

            scheduleForm.action =
                "{{ route('chair.save.schedule') }}";

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | EDITING A DRAFT
        |--------------------------------------------------------------------------
        |
        | Do NOT immediately send the draft to saveSchedule().
        |
        | First check whether this room already contains
        | a published schedule.
        |
        */

        event.preventDefault();

        const count =
            prepareSchedule();

        if (count === 0) {

            alert(
                'There is no schedule entry to publish. Select an editable or empty cell, enter a valid course code, and click Save Cell first.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ASK FOR OVERRIDE
        |--------------------------------------------------------------------------
        */

        const confirmed =
            confirm(
                'This room already has a published schedule.\n\n' +
                'Publishing this edited draft will replace the existing schedule in this room.\n\n' +
                'Do you want to override the existing schedule and publish this draft?'
            );


        /*
        |--------------------------------------------------------------------------
        | CANCEL
        |--------------------------------------------------------------------------
        */

        if (!confirmed) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | OVERRIDE AND PUBLISH
        |--------------------------------------------------------------------------
        */

        scheduleForm.action =
            "{{ route(
                'chair.drafts.publish',
                $room->id
            ) }}";


        /*
        |--------------------------------------------------------------------------
        | ADD OVERRIDE FLAG
        |--------------------------------------------------------------------------
        */

        let overrideInput =
            scheduleForm.querySelector(
                'input[name="override"]'
            );

        if (!overrideInput) {

            overrideInput =
                document.createElement('input');

            overrideInput.type =
                'hidden';

            overrideInput.name =
                'override';

            scheduleForm.appendChild(
                overrideInput
            );
        }

        overrideInput.value =
            '1';


        /*
        |--------------------------------------------------------------------------
        | SUBMIT AGAIN
        |--------------------------------------------------------------------------
        */

        scheduleForm.submit();
    }
);

    // =====================================================
// EDITABLE TIME CELLS
// =====================================================

function attachTimeCellEvents() {
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

const findMergeClassBtn =
    document.getElementById('findMergeClassBtn');

const mergeSearchForm =
    document.getElementById('mergeSearchForm');

const searchMergeBtn =
    document.getElementById('searchMergeBtn');

const mergeCourseCode =
    document.getElementById('mergeCourseCode');

const mergeSearchStatus =
    document.getElementById('mergeSearchStatus');

const mergeResults =
    document.getElementById('mergeResults');


// ------------------------------------------------------------
// SHOW MERGE SEARCH FORM
// ------------------------------------------------------------

if (findMergeClassBtn) {

    findMergeClassBtn.addEventListener(
        'click',
        function () {

            mergeSearchForm.style.display =
                'block';

            mergeSearchStatus.textContent =
                '';

            mergeResults.innerHTML =
                '';

            mergeResults.style.display =
                'none';

            mergeCourseCode.focus();

        }
    );

}


// ------------------------------------------------------------
// SEARCH EXISTING CLASS
// ------------------------------------------------------------

if (searchMergeBtn) {

    searchMergeBtn.addEventListener(
        'click',
        function () {

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
                window.mergeRoomSchedules =
                data.schedules || [];
                if (
                    !data.classes ||
                    data.classes.length === 0
                ) {

                    mergeSearchStatus.textContent =
                        'No matching class was found in another department.';

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

                            '<strong>Department:</strong> ' +
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
                        'Department: ' +
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
                        'Send merge request to the owner department?'
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

                console.error(error);

                mergeSearchStatus.textContent =
                    'Unable to search for existing classes.';

            });

        }
    );

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
        'Loading departments...' +
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
                'Unable to load departments.'
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
                'No other departments found.' +
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
            'Unable to load departments.' +
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

                        '<strong>Department:</strong> ' +
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


        /*
         |--------------------------------------------------------------------------
         | FIND THE CARD
         |--------------------------------------------------------------------------
         */

        const card =
            mergeScheduleView.querySelector(
                '.merge-schedule-card'
            );


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

            '<div class="merge-room-schedule-table-wrapper">' +

            '<table class="merge-room-schedule-table">' +

                '<thead>' +

                    '<tr>' +

                        '<th>TIME</th>' +

                        '<th>MON</th>' +

                        '<th>WED</th>' +

                        '<th>FRI</th>' +

                        '<th>TIME</th>' +

                        '<th>TUE</th>' +

                        '<th>THU</th>' +

                        '<th>SAT</th>' +

                    '</tr>' +

                '</thead>' +

                '<tbody>';


        /*
         |--------------------------------------------------------------------------
         | TIME ROWS
         |--------------------------------------------------------------------------
         */

        const timeRows = [
            '8:00-9:00',
            '9:00-10:00',
            '10:00-11:00',
            '11:00-12:00',
            '12:00-1:00',
            '1:00-2:00',
            '2:00-3:00',
            '3:00-4:00',
            '4:00-5:00'
        ];


        timeRows.forEach(
            function(time, rowIndex)
            {
                /*
                 |--------------------------------------------------------------------------
                 | NOON BREAK
                 |--------------------------------------------------------------------------
                 */

                if (rowIndex === 4) {

                    scheduleHtml +=

                        '<tr class="merge-noon-break">' +

                            '<td colspan="8">' +
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

                        buildMergeScheduleCell(
                            saturday
                        ) +

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

    return schedules.find(
        function(schedule)
        {
            return (
                schedule.day === day &&
                Number(schedule.row_position) ===
                    rowIndex
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

            '<div class="merge-class-instructor">' +
                escapeHtml(
                    schedule.instructor || ''
                ) +
            '</div>' +

            '<div class="merge-select-hint">' +
                'Click to request merge' +
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

                    'Send merge request to the owner department?'
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

            mergeProgramSelect.value = '';
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

            mergeProgramSelect.value = '';
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

            mergeProgramSelect.value = '';
        };
}
    }
);
// ------------------------------------------------------------
// INITIALIZE MANUAL BROWSE
// ------------------------------------------------------------

loadMergeDepartments();


// Existing time-cell events
attachTimeCellEvents();

// ============================================================
// MERGE CLASS TOGGLE
// ============================================================

const mergeClassToggleBtn =
    document.getElementById('mergeClassToggleBtn');

const mergeExistingClass =
    document.getElementById('mergeExistingClass');

if (
    mergeClassToggleBtn &&
    mergeExistingClass
) {
    mergeClassToggleBtn.addEventListener(
        'click',
        function () {

            if (
                mergeExistingClass.style.display === 'none'
            ) {

                // SHOW MERGE EXISTING CLASS
                mergeExistingClass.style.display = 'block';

            } else {

                // HIDE MERGE EXISTING CLASS
                mergeExistingClass.style.display = 'none';

            }

        }
    );
    const cancelScheduleConflict =
    document.getElementById(
        'cancelScheduleConflict'
    );

const scheduleConflictModal =
    document.getElementById(
        'scheduleConflictModal'
    );


if (
    cancelScheduleConflict &&
    scheduleConflictModal
) {

    cancelScheduleConflict.addEventListener(
        'click',
        function()
        {
            scheduleConflictModal.remove();
        }
    );

}
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



</body>

</html>