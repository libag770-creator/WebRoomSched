<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Class Program - {{ $room->room_name }}
    </title>


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            background: #f1f3f2;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
        }

        .page {
            max-width: 1400px;
            margin: 0 auto;
        }


        /* =========================================================
           TOP BAR
        ========================================================= */

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 14px;
            background: #777;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
        }

        .back-link:hover {
            background: #616161;
        }


        /* =========================================================
           MESSAGES
        ========================================================= */

        .message {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .message-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 5px solid #2e7d32;
        }

        .message-error {
            background: #ffebee;
            color: #b71c1c;
            border-left: 5px solid #c62828;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .program-header {
            background: white;
            border: 1px solid #aaa;
            padding: 18px 20px;
            text-align: center;
        }

        .program-title {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .program-semester {
            margin-top: 4px;
            font-size: 13px;
        }
.schedule-information {
    display: flex;
    gap: 20px;
    margin: 20px 0;
    flex-wrap: wrap;
}

.schedule-info-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.schedule-info-field label {
    font-weight: 600;
    font-size: 14px;
}

.schedule-info-field input {
    width: 220px;
    padding: 9px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

.merge-section {
    margin-top: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fafafa;
}

.merge-title {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 6px;
}

.merge-description {
    margin: 0 0 12px;
    font-size: 13px;
    color: #666;
}

.merge-search-status {
    margin-top: 10px;
    font-size: 13px;
}

.merge-results {
    margin-top: 12px;
}

.merge-result {
    padding: 12px;
    margin-bottom: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: #fff;
}

.merge-result-title {
    font-weight: bold;
    margin-bottom: 5px;
}

.merge-result-info {
    font-size: 13px;
    line-height: 1.5;
}

.merge-select-btn {
    margin-top: 8px;
    padding: 7px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

        .draft-mode {
            margin-top: 10px;
            display: inline-block;
            padding: 8px 12px;
            background: #fff8e1;
            color: #6d4c00;
            border-left: 5px solid #f9a825;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .program-meta {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 16px;
            text-align: left;
            font-size: 13px;
        }

        .meta-item {
            padding: 9px 10px;
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .meta-item strong {
            color: #2e7d32;
        }


        /* =========================================================
           EDITOR
        ========================================================= */

        .editor-panel {
            background: white;
            margin-top: 18px;
            padding: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .editor-title {
            color: #2e7d32;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .editor-subtitle {
            color: #777;
            font-size: 13px;
            margin-bottom: 18px;
        }


        /* =========================================================
           FORM
        ========================================================= */

        .form-grid {
            display: grid;
            grid-template-columns:
                1.2fr
                2fr
                1.2fr
                1.5fr
                2fr;
            gap: 10px;
            align-items: end;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 9px 10px;
            border: 1px solid #bbb;
            border-radius: 5px;
            background: white;
            font-size: 13px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2e7d32;
            box-shadow: 0 0 0 2px rgba(46,125,50,.1);
        }

        .form-group input[readonly] {
            background: #f5f5f5;
        }


        /* =========================================================
           LOOKUP STATUS
        ========================================================= */

        .lookup-status {
            margin-top: 10px;
            padding: 9px 12px;
            border-radius: 5px;
            font-size: 12px;
            display: none;
        }

        .lookup-success {
            display: block;
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }

        .lookup-error {
            display: block;
            background: #ffebee;
            color: #b71c1c;
            border-left: 4px solid #c62828;
        }

        .lookup-warning {
            display: block;
            background: #fff8e1;
            color: #6d4c00;
            border-left: 4px solid #f9a825;
        }


        /* =========================================================
           BUTTONS
        ========================================================= */

        .btn {
            border: none;
            padding: 9px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: .15s;
        }

        .btn:active {
            transform: scale(.98);
        }

        .btn-green {
            background: #2e7d32;
            color: white;
        }

        .btn-green:hover {
            background: #1b5e20;
        }

        .btn-yellow {
            background: #f9a825;
            color: #1b5e20;
        }

        .btn-yellow:hover {
            background: #e69b00;
            color: white;
        }

        .btn-red {
            background: #c62828;
            color: white;
        }

        .btn-red:hover {
            background: #a61b1b;
        }

        .btn-gray {
            background: #777;
            color: white;
        }

        .btn-gray:hover {
            background: #616161;
        }


        /* =========================================================
           CELL CONTROLS
        ========================================================= */

        .cell-controls {
            display: flex;
            align-items: end;
            gap: 10px;
            margin-top: 14px;
            flex-wrap: wrap;
        }

        .color-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .color-group label {
            font-size: 12px;
            font-weight: bold;
        }

        .color-group input[type="color"] {
            width: 60px;
            height: 38px;
            padding: 3px;
            cursor: pointer;
        }

        /* =====================================================
   MULTI-CELL SELECTION
   ===================================================== */

        .schedule-cell.selected-cell {
            outline: 3px solid #2e7d32;
            outline-offset: -3px;
            background: #e8f5e9 !important;
        }

        /* =========================================================
           INSTRUCTION
        ========================================================= */

        .instruction {
            margin-top: 15px;
            padding: 12px 14px;
            background: #fff8e1;
            color: #6d4c00;
            border-left: 5px solid #f9a825;
            border-radius: 5px;
            font-size: 12px;
        }


        /* =========================================================
           TABLE
        ========================================================= */

        .table-wrapper {
            margin-top: 18px;
            overflow-x: auto;
            background: white;
            border: 1px solid #888;
        }

      #scheduleTable {
    width: 100%;
    min-width: 1050px;
    border-collapse: collapse;
    table-layout: fixed;
}

        #scheduleTable th,
        #scheduleTable td {
            border: 1px solid #888;
        }

        #scheduleTable thead th {
            background: #e6ebe8;
            height: 40px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }
#scheduleTable thead th:first-child,
#scheduleTable thead th.time-header {
    width: 110px;
}

        .time-cell {
            background: #fafafa;
            min-height: 70px;
            height: 70px;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
            font-size: 11px;
            font-weight: bold;
            cursor: text;
        }

        .time-cell:focus {
            outline: 2px solid #2e7d32;
            outline-offset: -2px;
        }

        .schedule-cell {
            height: 90px;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background: white;
            transition: .15s;
        }

        .schedule-cell:hover {
            background: #f7fbf7;
        }

        .schedule-cell.selected {
            outline: 3px solid #2e7d32;
            outline-offset: -3px;
        }

.schedule-info-field select {
    width: 220px;
    padding: 9px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    background: white;
}

.cell-subject-type {
    font-size: 11px;
    font-weight: 600;
    margin-top: 3px;
}
        /* =========================================================
           LOCKED CELL
        ========================================================= */

        .locked-cell {
            background: #eeeeee !important;
            color: #777;
            cursor: not-allowed !important;
            opacity: .85;
            position: relative;
        }

        .locked-cell:hover {
            background: #e5e5e5 !important;
        }

        .locked-label {
            margin-top: 4px;
            color: #c62828;
            font-size: 8px;
            font-weight: bold;
        }


        /* =========================================================
           CELL CONTENT
        ========================================================= */

        .cell-course {
            font-size: 11px;
            font-weight: bold;
        }

        .cell-subject {
            margin-top: 3px;
            font-size: 10px;
        }

        .cell-instructor {
            margin-top: 3px;
            font-size: 9px;
            color: #555;
        }

        .cell-year {
            margin-top: 2px;
            font-size: 9px;
            color: #777;
        }

        .cell-major {
    margin-top: 2px;
    font-size: 9px;
    font-weight: bold;
}

.cell-program {
    margin-top: 2px;
    font-size: 8px;
    font-weight: normal;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.merge-room-schedule-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.merge-room-schedule-table th,
.merge-room-schedule-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
    vertical-align: middle;
}

.merge-room-schedule-table th {
    background: #2e7d32;
    color: white;
    font-weight: bold;
    height: 40px;
}

.merge-time-cell {
    width: 100px;
    background: #f5f5f5;
    font-weight: bold;
    white-space: nowrap;
}

.merge-schedule-cell {
    height: 70px;
    min-height: 70px;
}

.merge-empty-cell {
    background: #fafafa;
    color: #999;
}

.merge-occupied-cell {
    background: #e8f5e9;
    cursor: pointer;
}

.merge-class-course {
    font-weight: bold;
    font-size: 13px;
}

.merge-class-subject {
    font-size: 12px;
    margin-top: 4px;
}

.merge-class-instructor {
    font-size: 11px;
    margin-top: 4px;
}

.merge-noon-break td {
    background: #eeeeee;
    font-weight: bold;
    height: 35px;
}

        .empty-cell {
            color: #aaa;
            font-size: 10px;
            font-style: italic;
        }

        .break-row td {
            height: 28px;
            background: #efefef;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #555;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .table-footer {
            padding: 12px;
            text-align: center;
            background: #fafafa;
            border: 1px solid #bbb;
            border-top: none;
        }


        /* =========================================================
           ACTIONS
        ========================================================= */

        .editor-actions {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1000px) {

            .program-meta {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 650px) {

            body {
                padding: 12px;
            }

            .program-title {
                font-size: 21px;
            }

            .program-meta {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .editor-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .action-group {
                width: 100%;
            }

            .action-group .btn {
                flex: 1;
            }

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

        <div class="program-title">
            CLASS PROGRAM
        </div>

       <div class="schedule-information">

    <div class="schedule-info-field">
        <label for="semester">
            Semester
        </label>

        <input
            type="text"
            id="semester"
            name="semester"
            value="{{ old('semester', $semester ?? '') }}"
            placeholder="Example: 1st Semester"
        >
    </div>


    <div class="schedule-info-field">
        <label for="academic_year">
            Academic Year
        </label>

        <input
            type="text"
            id="academic_year"
            name="academic_year"
            value="{{ old('academic_year', $academicYear ?? '') }}"
            placeholder="Example: 2026-2027"
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
<div class="merge-section">

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

    <div
    id="mergeProgramSelection"
    style="display:none;"
>
    <label for="mergeProgramSelect">
        Program to Merge
    </label>

    <select
        id="mergeProgramSelect"
    >
        <option value="">
            Select Program
        </option>
    </select>
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
    | RIGHT = TTH
    |
    */

    $defaultTimes = [
        '8:00-9:00',
        '9:00-10:00',
        '10:00-11:00',
        '11:00-12:00',
        '12:00-1:00',
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
        in_array($day, ['TUE', 'THU'])
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
    ->unique()
    ->sortBy($sortTime)
    ->values();

$rightTimes = $rightTimes
    ->unique()
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

   @if(
    $leftTime === '12:00-1:00'
    &&
    $rightTime === '12:00-1:00'
)
    <tr class="break-row">
        <td colspan="7">
            N O O N
            &nbsp;&nbsp;
            B R E A K
        </td>
    </tr>
    @continue
@endif


    <tr>

        {{-- =====================================================
             LEFT TIME — MWF
             ===================================================== --}}

       <td class="time-cell editable-time" contenteditable="true" spellcheck="false">
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

                $isLocked =
                    $schedule
                    &&
                    isset($schedule->department_id)
                    &&
                    (int) $schedule->department_id
                    !==
                    (int) $chairDepartmentId;

            @endphp


            <td
                class="schedule-cell {{ $isLocked ? 'locked-cell' : '' }}"

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
             RIGHT TIME — TTH
             ===================================================== --}}

        <td class="time-cell editable-time" contenteditable="true" spellcheck="false">
        {{ $rightTime }}
        </td>


        {{-- =====================================================
             TUE / THU
             ===================================================== --}}

        @foreach(['TUE', 'THU'] as $day)

            @php

                $key =
                    $day
                    . '|'
                    . trim($rightTime);

                $schedule =
                    $scheduleMap[$key]
                    ?? null;

                $isLocked =
                    $schedule
                    &&
                    isset($schedule->department_id)
                    &&
                    (int) $schedule->department_id
                    !==
                    (int) $chairDepartmentId;

            @endphp


            <td
                class="schedule-cell {{ $isLocked ? 'locked-cell' : '' }}"

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


    attachCellEvents();


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

        alert(
            'One or more selected cells belong to another department and are locked.'
        );

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

   function addRow()
{
    const tbody =
        document.getElementById('scheduleBody');


    const row =
        document.createElement('tr');


    /*
    |--------------------------------------------------------------------------
    | CREATE TIME CELL
    |--------------------------------------------------------------------------
    */

    function createTimeCell()
{
    const timeCell =
        document.createElement('td');

    timeCell.className =
        'time-cell editable-time';

        timeCell.contentEditable =
            'true';

        timeCell.spellcheck =
            false;

        timeCell.textContent =
            'New Time';

        return timeCell;
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE SCHEDULE CELL
    |--------------------------------------------------------------------------
    */

    function createScheduleCell()
    {
        const cell =
            document.createElement('td');

        cell.className =
            'schedule-cell';

        cell.dataset.course =
            '';

        cell.dataset.subject =
            '';

        cell.dataset.year =
            '';

        cell.dataset.major =
            '';

        cell.dataset.program =
            '';

        cell.dataset.instructor =
            '';

        cell.dataset.facultyId =
            '';

        cell.dataset.departmentId =
            chairDepartmentId;

        cell.dataset.color =
            '#ffffff';

        cell.dataset.subjectType =
            '';

        cell.dataset.locked =
            '0';

        cell.innerHTML =
            '<span class="empty-cell">Empty</span>';

        return cell;
    }


    /*
    |--------------------------------------------------------------------------
    | LEFT SIDE
    | TIME + MON + WED + FRI
    |--------------------------------------------------------------------------
    */

    row.appendChild(
        createTimeCell()
    );

    row.appendChild(
        createScheduleCell()
    );

    row.appendChild(
        createScheduleCell()
    );

    row.appendChild(
        createScheduleCell()
    );


    /*
    |--------------------------------------------------------------------------
    | RIGHT SIDE
    | TIME + TUE + THU
    |--------------------------------------------------------------------------
    */

    row.appendChild(
        createTimeCell()
    );

    row.appendChild(
        createScheduleCell()
    );

    row.appendChild(
        createScheduleCell()
    );


    /*
    |--------------------------------------------------------------------------
    | ADD ROW
    |--------------------------------------------------------------------------
    */

    tbody.appendChild(row);


 /*
|--------------------------------------------------------------------------
| ATTACH CELL EVENTS
|--------------------------------------------------------------------------
*/

attachCellEvents();



attachTimeCellEvents();


    /*
    |--------------------------------------------------------------------------
    | SCROLL TO NEW ROW
    |--------------------------------------------------------------------------
    */

    row.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
}

    document
        .getElementById(
            'addRowButton'
        )
        .addEventListener(
            'click',
            addRow
        );


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
        document.getElementById(
            'hiddenInputs'
        );

    hidden.innerHTML = '';


    /*
    |--------------------------------------------------------------------------
    | SEMESTER
    |--------------------------------------------------------------------------
    */

    const semester =
        document.getElementById(
            'semester'
        )?.value.trim() || '';


    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

    const academicYear =
        document.getElementById(
            'academic_year'
        )?.value.trim() || '';


    /*
    |--------------------------------------------------------------------------
    | SEND SEMESTER
    |--------------------------------------------------------------------------
    */

    const semesterInput =
        document.createElement(
            'input'
        );

    semesterInput.type =
        'hidden';

    semesterInput.name =
        'semester';

    semesterInput.value =
        semester;

    hidden.appendChild(
        semesterInput
    );


    /*
    |--------------------------------------------------------------------------
    | SEND ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

    const academicYearInput =
        document.createElement(
            'input'
        );

    academicYearInput.type =
        'hidden';

    academicYearInput.name =
        'academic_year';

    academicYearInput.value =
        academicYear;

    hidden.appendChild(
        academicYearInput
    );


    /*
    |--------------------------------------------------------------------------
    | GET TABLE ROWS
    |--------------------------------------------------------------------------
    */

    const rows =
        document.querySelectorAll(
            '#scheduleTable tbody tr'
        );


    /*
    |--------------------------------------------------------------------------
    | COLUMN DEFINITIONS
    |-------------------------------------------------------------------------- 
    */

    const leftDays = [
        'MON',
        'WED',
        'FRI'
    ];

    const rightDays = [
        'TUE',
        'THU'
    ];


    let counter = 0;


    /*
    |--------------------------------------------------------------------------
    | PROCESS EACH ROW
    |--------------------------------------------------------------------------
    */

    rows.forEach(
        function(row)
        {

            /*
            |--------------------------------------------------------------------------
            | SKIP NOON BREAK
            |--------------------------------------------------------------------------
            */

            if (
                row.classList.contains(
                    'break-row'
                )
            ) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | LEFT TIME
            |--------------------------------------------------------------------------
            */

            const leftTimeCell =
                row.cells[0];


            /*
            |--------------------------------------------------------------------------
            | RIGHT TIME
            |--------------------------------------------------------------------------
            */

            const rightTimeCell =
                row.cells[4];


            const leftTime =
                leftTimeCell
                    ? leftTimeCell.innerText.trim()
                    : '';


            const rightTime =
                rightTimeCell
                    ? rightTimeCell.innerText.trim()
                    : '';


            /*
            |--------------------------------------------------------------------------
            | PROCESS LEFT SIDE
            | MON / WED / FRI
            |--------------------------------------------------------------------------
            */

            leftDays.forEach(
                function(day, index)
                {

                    const cell =
                        row.cells[index + 1];


                    if (!cell) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | LOCKED CELLS ARE NOT SUBMITTED
                    |--------------------------------------------------------------------------
                    */

                    if (
                        cell.dataset.locked ===
                        '1'
                    ) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | TIME REQUIRED
                    |--------------------------------------------------------------------------
                    */

                    if (!leftTime) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | COURSE
                    |--------------------------------------------------------------------------
                    */

                    const course =
                        (
                            cell.dataset.course ||
                            ''
                        ).trim();


                    if (!course) {
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
                        matches.length !== 1
                    ) {
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
                        (
                            cell.dataset.subjectType ||
                            ''
                        ).trim();


                    /*
                    |--------------------------------------------------------------------------
                    | SCHEDULE DATA
                    |--------------------------------------------------------------------------
                    */

                    const scheduleData = {

                        schedule_id: cell.dataset.scheduleId || '',

                        row_position: Array.from(
                        document.querySelectorAll('#scheduleBody > tr:not(.break-row)')
                        ).indexOf(row),

                        day: day,

                        time: leftTime,

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


                    /*
                    |--------------------------------------------------------------------------
                    | CREATE HIDDEN INPUTS
                    |--------------------------------------------------------------------------
                    */

                    Object.keys(
                        scheduleData
                    ).forEach(
                        function(name)
                        {

                            const input =
                                document.createElement(
                                    'input'
                                );

                            input.type =
                                'hidden';

                            input.name =
                                'schedule[' +
                                counter +
                                '][' +
                                name +
                                ']';

                            input.value =
                                scheduleData[name];

                            hidden.appendChild(
                                input
                            );

                        }
                    );


                    counter++;

                }
            );


            /*
            |--------------------------------------------------------------------------
            | PROCESS RIGHT SIDE
            | TUE / THU
            |--------------------------------------------------------------------------
            */

            rightDays.forEach(
                function(day, index)
                {

                    const cell =
                        row.cells[index + 5];


                    if (!cell) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | LOCKED CELLS ARE NOT SUBMITTED
                    |--------------------------------------------------------------------------
                    */

                    if (
                        cell.dataset.locked ===
                        '1'
                    ) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | TIME REQUIRED
                    |--------------------------------------------------------------------------
                    */

                    if (!rightTime) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | COURSE
                    |--------------------------------------------------------------------------
                    */

                    const course =
                        (
                            cell.dataset.course ||
                            ''
                        ).trim();


                    if (!course) {
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
                        matches.length !== 1
                    ) {
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
                        (
                            cell.dataset.subjectType ||
                            ''
                        ).trim();


                    /*
                    |--------------------------------------------------------------------------
                    | SCHEDULE DATA
                    |--------------------------------------------------------------------------
                    */

                    const scheduleData = {

                        schedule_id: cell.dataset.scheduleId || '',

                        row_position: Array.from(
                        document.querySelectorAll('#scheduleBody > tr:not(.break-row)')
                        ).indexOf(row),

                        day: day,

                        time: rightTime,

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


                    /*
                    |--------------------------------------------------------------------------
                    | CREATE HIDDEN INPUTS
                    |--------------------------------------------------------------------------
                    */

                    Object.keys(
                        scheduleData
                    ).forEach(
                        function(name)
                        {

                            const input =
                                document.createElement(
                                    'input'
                                );

                            input.type =
                                'hidden';

                            input.name =
                                'schedule[' +
                                counter +
                                '][' +
                                name +
                                ']';

                            input.value =
                                scheduleData[name];

                            hidden.appendChild(
                                input
                            );

                        }
                    );


                    counter++;

                }
            );

        }
    );


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

            const count =
                prepareSchedule();


            if (count === 0) {

                event.preventDefault();


                alert(
                    'There is no schedule entry to save. Select an editable or empty cell, enter a valid course code, and click Save Cell first.'
                );

                return;

            }


            const submitter =
                event.submitter;


            /*
            |--------------------------------------------------------------------------
            | SAVE DRAFT
            |--------------------------------------------------------------------------
            */

            if (
                submitter &&
                submitter.value ===
                    'draft'
            ) {

                scheduleForm.action =
                    "{{ route(
                        'chair.save.draft'
                    ) }}";

            }

            /*
            |--------------------------------------------------------------------------
            | UPLOAD
            |--------------------------------------------------------------------------
            */

            else {

                scheduleForm.action =
                    "{{ route(
                        'chair.save.schedule'
                    ) }}";

            }

        }
    );

    // =====================================================
// EDITABLE TIME CELLS
// =====================================================

function attachTimeCellEvents() {
    document.querySelectorAll('.editable-time').forEach(timeCell => {

        // Prevent Enter from creating a new line
        timeCell.addEventListener('keydown', function (event) {
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

                            '<td colspan="7">' +
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

mergeProgramSelect.value = '';

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

mergeProgramSelect.onchange =
    function()
    {
        const mergingProgram =
            this.value.trim();

        if (!mergingProgram) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CONFIRM MERGE
        |--------------------------------------------------------------------------
        */

        const confirmed = confirm(
            'Merge Existing Class?\n\n' +
            'Course: ' +
            course +
            '\n' +
            'Subject: ' +
            subject +
            '\n' +
            'Instructor: ' +
            instructor +
            '\n\n' +
            'Existing Program: ' +
            existingProgram +
            '\n' +
            'Your Program: ' +
            mergingProgram +
            '\n\n' +
            'After merging:\n' +
            existingProgram +
            ' / ' +
            mergingProgram +
            '\n\n' +
            'Do you want to request this merge?'
        );

        if (confirmed) {

            alert(
                'Ready to create merge request for schedule ID: ' +
                scheduleId
            );

            programSelection.style.display =
                'none';

            mergeProgramSelect.value = '';
        }
    };
    }
);
// ------------------------------------------------------------
// INITIALIZE MANUAL BROWSE
// ------------------------------------------------------------

loadMergeDepartments();


// Existing time-cell events
attachTimeCellEvents();

</script>


</body>

</html>