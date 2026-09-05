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
            min-width: 950px;
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

        #scheduleTable thead th:first-child {
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

                        'id' =>
                            $item->id,

                        'course_code' =>
                            $item->course_code,

                        'subject' =>
                            $item->subject,

                        'year_level' =>
                            $item->year_level,

                        'faculty_id' =>
                            $item->faculty
                                ? $item->faculty->id
                                : null,

                        'instructor' =>
                            $item->faculty
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
                        Subject
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

<!-- major -->
 <div class="schedule-info-field">
    <label for="subject_type">
        Subject Type
    </label>

    <select
        id="subject_type"
        name="subject_type"
    >
        <option value="">Optional</option>
        <option value="Lab">Major</option>
        <option value="">Non-major</option>
    </select>
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


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label>
                        Subject Type
                    </label>

                    <input
                        type="text"
                        id="description"
                        placeholder="Optional"
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
        <th>TIME</th>

        <th data-day="MON">MON</th>
        <th data-day="TUE">TUE</th>
        <th data-day="WED">WED</th>
        <th data-day="THU">THU</th>
        <th data-day="FRI">FRI</th>
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

    $chairDepartmentId =
        auth()->user()->department_id;


    /*
    |--------------------------------------------------------------------------
    | SAFE COLLECTIONS
    |--------------------------------------------------------------------------
    */

    $drafts =
        $drafts ?? collect();

    $schedules =
        $schedules ?? collect();


    /*
    |--------------------------------------------------------------------------
    | SAFE DRAFT MODE
    |--------------------------------------------------------------------------
    */

    $isEditingDraft =
        $isEditingDraft ?? false;


    /*
    |--------------------------------------------------------------------------
    | SELECT SCHEDULE SOURCE
    |--------------------------------------------------------------------------
    */

    if ($isEditingDraft) {

        $scheduleRows =
            $drafts;

    } else {

        $scheduleRows =
            $schedules;

    }


                        /*
                        |--------------------------------------------------------------------------
                        | DEFAULT TIMES
                        |--------------------------------------------------------------------------
                        */

                        $defaultTimes = [

                            '8:00-9:00',
                            '9:00-10:00',
                            '10:00-11:00',

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
                        */

                        $scheduleMap = [];


                        foreach (
                            $scheduleRows
                            as $row
                        ) {

                            $key =
                                strtoupper(
                                    trim(
                                        $row->day
                                    )
                                )
                                . '|'
                                .
                                trim(
                                    $row->time
                                );


                            $scheduleMap[$key] =
                                $row;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | TIME SLOTS
                        |--------------------------------------------------------------------------
                        */

                        $timeSlots =
                            collect(
                                $defaultTimes
                            );


                        foreach (
                            $scheduleRows
                            as $row
                        ) {

                            $existingTime =
                                trim(
                                    $row->time
                                );


                            if (
                                $existingTime !== ''
                                &&
                                !$timeSlots->contains(
                                    $existingTime
                                )
                            ) {

                                $timeSlots->push(
                                    $existingTime
                                );

                            }

                        }

                    @endphp


                    @foreach(
                        $timeSlots as $time
                    )


                        @if(
                            $time ===
                            '12:00-1:00'
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


                            <!-- TIME -->

                            <td
                                class="time-cell"
                                contenteditable="true"
                                spellcheck="false"
                            >
                                {{ $time }}
                            </td>


                            <!-- DAYS -->

                           @foreach(
    [
        'MON',
        'TUE',
        'WED',
        'THU',
        'FRI',
        'SAT'
    ] as $day
)


                                @php

                                    $key =
                                        $day .
                                        '|' .
                                        trim(
                                            $time
                                        );


                                    $schedule =
                                        $scheduleMap[$key]
                                        ?? null;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | OWNERSHIP
                                    |--------------------------------------------------------------------------
                                    */

                                    $isLocked =
                                        $schedule
                                        &&
                                        isset(
                                            $schedule->department_id
                                        )
                                        &&
                                        (int)
                                        $schedule->department_id
                                        !==
                                        (int)
                                        $chairDepartmentId;

                                @endphp


                                <td

                                    class="
                                        schedule-cell
                                        {{
                                            $isLocked
                                                ? 'locked-cell'
                                                : ''
                                        }}
                                    "

                                    data-course="{{
                                        $schedule
                                            ? $schedule->course_code
                                            : ''
                                    }}"

                                    data-subject="{{
                                        $schedule
                                            ? $schedule->subject
                                            : ''
                                    }}"

                                    data-year="{{
                                        $schedule
                                            ? (
                                                $schedule->year_level
                                                ?? ''
                                            )
                                            : ''
                                    }}"

                                    data-instructor="{{
                                        $schedule
                                            ? $schedule->instructor
                                            : ''
                                    }}"

                                    data-faculty-id="{{
                                        $schedule
                                            ? (
                                                $schedule->faculty_id
                                                ?? ''
                                            )
                                            : ''
                                    }}"

                                    data-department-id="{{
                                        $schedule
                                            ? (
                                                $schedule->department_id
                                                ?? ''
                                            )
                                            : ''
                                    }}"

                                    data-description="{{
                                        $schedule
                                            ? (
                                                $schedule->description
                                                ?? ''
                                            )
                                            : ''
                                    }}"

                                    data-color="{{
                                        $schedule
                                            ? (
                                                $schedule->color
                                                ?? '#ffffff'
                                            )
                                            : '#ffffff'
                                    }}"

                                    data-locked="{{
                                        $isLocked
                                            ? '1'
                                            : '0'
                                    }}"

                                    data-subject-type="{{
    $schedule
        ? ($schedule->subject_type ?? '')
        : ''
}}"

                                    style="
                                        background-color:
                                        {{
                                            $schedule
                                                ? (
                                                    $schedule->color
                                                    ?? '#ffffff'
                                                )
                                                : '#ffffff'
                                        }};
                                    "
                                >


                                    @if($schedule)

                                        <div class="cell-course">

                                            {{
                                                $schedule->course_code
                                            }}

                                        </div>


                                        <div class="cell-subject">

                                            {{
                                                $schedule->subject
                                            }}

                                        </div>


                                        <div class="cell-instructor">

                                            {{
                                                $schedule->instructor
                                            }}

                                        </div>


                                        <div class="cell-year">

                                            {{
                                                $schedule->year_level
                                                ?? ''
                                            }}

                                        </div>

                                    @if($schedule && $schedule->subject_type)
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

                    @endforeach


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


    const instructorInput =
        document.getElementById(
            'instructor'
        );


    const descriptionInput =
        document.getElementById(
            'description'
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


            subjectInput.value =
                match.subject;


            yearLevelInput.value =
                match.year_level;


            instructorInput.value =
                match.instructor;


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


        descriptionInput.value =
            cell.dataset.description || '';

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

                            selectCell(
                                this
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
    if (!selectedCell) {

        alert(
            'Please select a schedule cell first.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK LOCKED CELL
    |--------------------------------------------------------------------------
    */

    if (
        selectedCell.dataset.locked ===
        '1'
    ) {

        alert(
            'This schedule belongs to another department and is locked.'
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


    const description =
        descriptionInput.value
            .trim();


    const color =
        colorInput.value;


    /*
    |--------------------------------------------------------------------------
    | OPTIONAL SUBJECT TYPE
    |--------------------------------------------------------------------------
    |
    | Major
    | Non-major
    | Blank
    |
    */

    const subjectTypeInput =
        document.getElementById(
            'subject_type'
        );


    const subjectType =
        subjectTypeInput
            ? subjectTypeInput.value.trim()
            : '';


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
    | SAVE DATA TO CELL
    |--------------------------------------------------------------------------
    */

    selectedCell.dataset.course =
        match.course_code;


    selectedCell.dataset.subject =
        match.subject;


    selectedCell.dataset.year =
        match.year_level;


    selectedCell.dataset.instructor =
        match.instructor;


    selectedCell.dataset.facultyId =
        match.faculty_id ||
        '';


    selectedCell.dataset.departmentId =
        chairDepartmentId;


    selectedCell.dataset.description =
        description;


    selectedCell.dataset.color =
        color;


    /*
    |--------------------------------------------------------------------------
    | SAVE MAJOR / NON-MAJOR
    |--------------------------------------------------------------------------
    */

    selectedCell.dataset.subjectType =
        subjectType;


    /*
    |--------------------------------------------------------------------------
    | MARK AS EDITABLE
    |--------------------------------------------------------------------------
    */

    selectedCell.dataset.locked =
        '0';


    selectedCell.classList.remove(
        'locked-cell'
    );


    /*
    |--------------------------------------------------------------------------
    | DISPLAY
    |--------------------------------------------------------------------------
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


    selectedCell.innerHTML =

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

        subjectTypeDisplay;


    /*
    |--------------------------------------------------------------------------
    | BACKGROUND COLOR
    |--------------------------------------------------------------------------
    */

    selectedCell.style.backgroundColor =
        color;


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

        if (!selectedCell) {

            alert(
                'Please select a schedule cell first.'
            );

            return;

        }


        if (
            selectedCell.dataset.locked ===
            '1'
        ) {

            alert(
                'This schedule belongs to another department and is locked.'
            );

            return;

        }


        selectedCell.dataset.course =
            '';


        selectedCell.dataset.subject =
            '';

        selectedCell.dataset.subjectType =
             '';

         subjectTypeInput.value =
              '';

        selectedCell.dataset.year =
            '';


        selectedCell.dataset.instructor =
            '';


        selectedCell.dataset.facultyId =
            '';


        selectedCell.dataset.departmentId =
            '';


        selectedCell.dataset.description =
            '';


        selectedCell.dataset.color =
            '#ffffff';


        selectedCell.dataset.locked =
            '0';


        selectedCell.classList.remove(
            'locked-cell'
        );


        selectedCell.style.backgroundColor =
            '#ffffff';


        selectedCell.innerHTML =
            '<span class="empty-cell">Empty</span>';


        courseCodeInput.value =
            '';

        subjectInput.value =
            '';

        yearLevelInput.value =
            '';

        instructorInput.value =
            '';

        descriptionInput.value =
            '';

        colorInput.value =
            '#ffffff';


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
            document.getElementById(
                'scheduleBody'
            );


        const row =
            document.createElement(
                'tr'
            );


        /*
        |--------------------------------------------------------------------------
        | TIME
        |--------------------------------------------------------------------------
        */

        const timeCell =
            document.createElement(
                'td'
            );


        timeCell.className =
            'time-cell';


        timeCell.contentEditable =
            'true';


        timeCell.spellcheck =
            false;


        timeCell.textContent =
            'New Time';


        row.appendChild(
            timeCell
        );


        /*
        |--------------------------------------------------------------------------
        | DAYS
        |--------------------------------------------------------------------------
        */

       const dayCount =
    document.querySelectorAll(
        '#scheduleTable thead th[data-day]'
    ).length;

for (
    let i = 0;
    i < dayCount;
    i++
        ) {

            const cell =
                document.createElement(
                    'td'
                );


            cell.className =
                'schedule-cell';


            cell.dataset.course =
                '';


            cell.dataset.subject =
                '';


            cell.dataset.year =
                '';


            cell.dataset.instructor =
                '';


            cell.dataset.facultyId =
                '';


            cell.dataset.departmentId =
                chairDepartmentId;


            cell.dataset.description =
                '';


            cell.dataset.color =
                '#ffffff';


                cell.dataset.subjectType = '';

            cell.dataset.locked =
                '0';


            cell.innerHTML =
                '<span class="empty-cell">Empty</span>';


            row.appendChild(
                cell
            );

        }


        tbody.appendChild(
            row
        );


        attachCellEvents();


        row.scrollIntoView({

            behavior:
                'smooth',

            block:
                'center'

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
    | SEND SEMESTER AS TOP-LEVEL FIELD
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
    | SEND ACADEMIC YEAR AS TOP-LEVEL FIELD
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
    | GET DAY COLUMNS FROM TABLE
    |--------------------------------------------------------------------------
    */

    const headerRow =
        document.querySelector(
            '#scheduleTable thead tr'
        );


    if (!headerRow) {
        return 0;
    }


    const dayHeaders =
        headerRow.querySelectorAll(
            'th[data-day]'
        );


    const days = [];


    dayHeaders.forEach(
        function(header)
        {

            const day =
                (
                    header.dataset.day ||
                    header.innerText ||
                    ''
                )
                .trim()
                .toUpperCase();


            if (day) {

                days.push(
                    day
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FALLBACK DAYS
    |--------------------------------------------------------------------------
    */

    if (days.length === 0) {

        const fallbackDays = [
            'MON',
            'TUE',
            'WED',
            'THU',
            'FRI'
        ];


        fallbackDays.forEach(
            function(day)
            {
                days.push(day);
            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COUNTER
    |--------------------------------------------------------------------------
    */

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
            | SKIP BREAK
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
            | TIME CELL
            |--------------------------------------------------------------------------
            */

            const timeCell =
                row.cells[0];


            if (!timeCell) {
                return;
            }


            const time =
                timeCell.innerText
                    .trim();


            if (!time) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | PROCESS DAY CELLS
            |--------------------------------------------------------------------------
            */

            for (
                let j = 0;
                j < days.length;
                j++
            ) {

                const cell =
                    row.cells[j + 1];


                if (!cell) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | NEVER SUBMIT LOCKED CELLS
                |--------------------------------------------------------------------------
                */

                if (
                    cell.dataset.locked ===
                    '1'
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | GET COURSE
                |--------------------------------------------------------------------------
                */

                const course =
                    (
                        cell.dataset.course ||
                        ''
                    )
                    .trim();


                if (!course) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | FIND COURSE
                |--------------------------------------------------------------------------
                */

                const matches =
                    findCourse(
                        course
                    );


                if (
                    matches.length !== 1
                ) {
                    continue;
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
                    )
                    .trim();


                /*
                |--------------------------------------------------------------------------
                | SCHEDULE DATA
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | semester and academic_year are NOT
                | placed here.
                |
                */

                const scheduleData = {

                    day:
                        days[j],

                    time:
                        time,

                    course_code:
                        match.course_code,

                    subject:
                        match.subject,

                    year_level:
                        match.year_level,

                    instructor:
                        match.instructor,

                    faculty_id:
                        match.faculty_id ||
                        '',

                    department_id:
                        chairDepartmentId,

                    subject_type:
                        subjectType,

                    description:
                        cell.dataset.description ||
                        '',

                    color:
                        cell.dataset.color ||
                        '#ffffff'
                };


                /*
                |--------------------------------------------------------------------------
                | CREATE SCHEDULE INPUTS
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

</script>


</body>

</html>