@include('footerheader.header')

<div class="wrapper">

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content faculty-my-schedules-view">

            <style>

                /* =========================================================
                   PAGE
                ========================================================= */

                body {
                    background: #f5f7f6;
                }

                .my-schedules-page {
                    width: 100%;
                    max-width: 1250px;
                    margin: 0 auto;
                    padding: 10px 0 40px;
                }


                /* =========================================================
                   HEADER
                ========================================================= */

                .schedule-header {
                    margin-bottom: 20px;
                }

                .schedule-title {
                    margin: 0;
                    color: #176b3a;
                    font-size: 25px;
                    font-weight: 900;
                }

                .schedule-subtitle {
                    margin-top: 5px;
                    color: #667085;
                    font-size: 13px;
                }


                /* =========================================================
                   FACULTY INFO
                ========================================================= */

                .faculty-info-card {
                    background: #ffffff;
                    border: 1px solid #dfe5e1;
                    border-radius: 12px;

                    padding: 15px 18px;
                    margin-bottom: 18px;

                    display: flex;
                    align-items: center;
                    justify-content: space-between;

                    gap: 15px;

                    box-shadow:
                        0 3px 10px rgba(16, 24, 40, .05);
                }

                .faculty-info-left {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .faculty-avatar {
                    width: 42px;
                    height: 42px;

                    border-radius: 50%;

                    background: #eaf6ef;
                    color: #176b3a;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    font-size: 17px;
                }

                .faculty-name {
                    font-size: 15px;
                    font-weight: 900;
                    color: #1f2937;
                }

                .faculty-label {
                    margin-top: 3px;
                    font-size: 11px;
                    color: #667085;
                }

                .term-badge {
                    padding: 7px 11px;

                    border-radius: 999px;

                    background: #eaf6ef;
                    color: #176b3a;

                    font-size: 10px;
                    font-weight: 900;

                    white-space: nowrap;
                }


                /* =========================================================
                   TABLE WRAPPER
                ========================================================= */

                .excel-wrapper {
                    width: 100%;
                    overflow-x: auto;

                    background: #ffffff;

                    border: 1px solid #dfe5e1;
                    border-radius: 12px;

                    box-shadow:
                        0 4px 15px rgba(16, 24, 40, .06);
                }


                /* =========================================================
                   TABLE
                ========================================================= */

                .excel-table {
                    width: 100%;
                    min-width: 1300px;

                    border-collapse: collapse;
                    table-layout: fixed;
                }

                .excel-table th,
                .excel-table td {
                    border: 1px solid #dfe5e1;
                    box-sizing: border-box;
                }


                /* =========================================================
                   HEADER
                ========================================================= */

                .excel-table thead th {
                    height: 48px;

                    padding: 8px;

                    background: #176b3a;
                    color: #ffffff;

                    font-size: 11px;
                    font-weight: 900;

                    text-align: center;
                    vertical-align: middle;
                }


                /* =========================================================
                   TIME COLUMN
                ========================================================= */

                .time-column {
                    width: 125px;
                    min-width: 125px;
                    max-width: 125px;

                    background: #f7faf8;
                    color: #344054;

                    font-size: 11px;
                    font-weight: 900;

                    text-align: center;
                    vertical-align: middle;

                    white-space: nowrap;
                }


                /* =========================================================
                   SCHEDULE CELL
                ========================================================= */

                .schedule-cell {
                    height: 100px;

                    padding: 6px;

                    background: #ffffff;

                    vertical-align: top;
                    text-align: left;
                }


                /* =========================================================
                   EMPTY CELL
                ========================================================= */

                .empty-cell {
                    min-height: 86px;

                    display: flex;

                    align-items: center;
                    justify-content: center;

                    color: #98a2b3;

                    font-size: 10px;
                    font-style: italic;
                }


                /* =========================================================
                   CLASS ENTRY
                ========================================================= */

                .class-entry {
                    position: relative;

                    width: 100%;
                    min-height: 84px;

                    padding: 9px;
                    padding-right: 54px;

                    box-sizing: border-box;

                    border-radius: 8px;

                    background: #f3faf6;
                    border: 1px solid #cce6d4;

                    text-align: left;

                    overflow: hidden;
                }


                /* =========================================================
                   TEMPORARY RESERVATION ENTRY
                ========================================================= */

                .temporary-reservation-entry {
                    position: relative;
                    width: 100%;
                    min-height: 84px;
                    padding: 9px;
                    box-sizing: border-box;
                    border-radius: 8px;
                    background: #fff8e8;
                    border: 1px solid #f2d18b;
                    text-align: left;
                    overflow: hidden;
                }

                .temporary-reservation-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    padding: 4px 7px;
                    border-radius: 999px;
                    background: #fff0c7;
                    color: #8a5a00;
                    font-size: 8px;
                    font-weight: 900;
                    text-transform: uppercase;
                    letter-spacing: .3px;
                }

                .temporary-reservation-course {
                    margin-top: 7px;
                    color: #8a5a00;
                    font-size: 11px;
                    font-weight: 900;
                    line-height: 1.2;
                }

                .temporary-reservation-detail {
                    margin-top: 4px;
                    color: #667085;
                    font-size: 9px;
                    line-height: 1.3;
                }

                .temporary-reservation-time {
                    margin-top: 5px;
                    color: #344054;
                    font-size: 9px;
                    font-weight: 800;
                }

                /* =========================================================
                   COURSE
                ========================================================= */

                .class-course {
                    color: #176b3a;

                    font-size: 12px;
                    font-weight: 900;

                    line-height: 1.2;

                    padding-right: 5px;
                }


                /* =========================================================
                   SUBJECT
                ========================================================= */

                .class-subject {
                    margin-top: 3px;

                    color: #1f2937;

                    font-size: 10.5px;
                    font-weight: 700;

                    line-height: 1.2;
                }


                /* =========================================================
                   PROGRAM
                ========================================================= */

                .class-detail {
                    margin-top: 4px;

                    color: #667085;

                    font-size: 9px;

                    line-height: 1.25;
                }

                .class-detail strong {
                    color: #344054;
                }


                /* =========================================================
                   YEAR LEVEL - TOP RIGHT
                ========================================================= */

                .year-level {
                    position: absolute;

                    top: 7px;
                    right: 7px;

                    max-width: 48px;

                    padding: 3px 6px;

                    border-radius: 999px;

                    background: #e8f4ec;
                    color: #176b3a;

                    font-size: 7.5px;
                    font-weight: 900;

                    line-height: 1;

                    white-space: nowrap;

                    text-align: center;
                }


                /* =========================================================
                   ROOM
                ========================================================= */

                .room-name {
                    margin-top: 5px;

                    display: flex;

                    align-items: center;
                    gap: 4px;

                    color: #344054;

                    font-size: 9px;
                    font-weight: 800;

                    line-height: 1.2;
                }

                .room-name i {
                    color: #176b3a;
                }


                /* =========================================================
                   BUILDING
                ========================================================= */

                .building-name {
                    margin-top: 3px;

                    color: #667085;

                    font-size: 8.5px;

                    line-height: 1.2;
                }


                /* =========================================================
                   NO SCHEDULE
                ========================================================= */

                .empty-schedule-page {
                    background: #ffffff;

                    border: 1px dashed #cfd8d2;

                    border-radius: 12px;

                    padding: 60px 20px;

                    text-align: center;

                    color: #667085;
                }

                .empty-schedule-page i {
                    display: block;

                    margin-bottom: 12px;

                    color: #176b3a;

                    font-size: 34px;
                }

                .empty-schedule-page strong {
                    display: block;

                    color: #176b3a;

                    font-size: 15px;
                }

                .empty-schedule-page span {
                    display: block;

                    margin-top: 5px;

                    font-size: 11px;
                }


                /* =========================================================
                   RESPONSIVE
                ========================================================= */

                @media(max-width: 800px) {

                    .my-schedules-page {
                        padding: 10px 8px 30px;
                    }

                    .faculty-info-card {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                }

                    .schedule-cell.merged-schedule-cell{
            vertical-align:middle;
            text-align:center;
        }

        .schedule-cell.merged-schedule-cell .class-entry{
            width:100%;
        }

        /* Polished weekly timetable styling */
        .faculty-my-schedules-view { background:#f5f8f6; }
        .my-schedules-page { max-width:1320px; padding:12px 0 42px; }
        .schedule-header {
            position:relative; margin-bottom:18px; padding:25px 28px; overflow:hidden;
            border:1px solid #dfeae2; border-radius:20px;
            background:linear-gradient(115deg,#fff 0%,#f0f8f2 100%);
            box-shadow:0 8px 24px rgba(22,73,45,.045);
        }
        .schedule-header::before { display:block; margin-bottom:8px; color:#56816a; content:"FACULTY WORKSPACE  /  PERSONAL TIMETABLE"; font-size:9px; font-weight:800; letter-spacing:.14em; }
        .schedule-header::after { position:absolute; top:-62px; right:-30px; width:210px; height:210px; border:1px solid rgba(28,117,74,.10); border-radius:50%; box-shadow:0 0 0 24px rgba(28,117,74,.035),0 0 0 48px rgba(28,117,74,.025); content:""; }
        .schedule-title { color:#193e2d; font-size:27px; font-weight:760; letter-spacing:-.035em; }
        .schedule-subtitle { max-width:620px; color:#708078; font-size:12px; line-height:1.65; }
        .faculty-info-card { position:relative; margin-bottom:16px; padding:14px 17px; border-color:#e2ebe5; border-radius:15px; box-shadow:0 4px 14px rgba(25,57,39,.04); }
        .faculty-avatar { width:42px; height:42px; border:1px solid #dce9df; background:#edf6ef; color:#19764d; }
        .faculty-name { color:#244b35; font-size:14px; font-weight:750; }
        .faculty-label { color:#829087; font-size:10px; }
        .term-badge { border:1px solid #dce9df; background:#f1f8f3; color:#19764d; font-size:10px; font-weight:750; }
        .excel-wrapper { border-color:#e2ebe5; border-radius:16px; box-shadow:0 7px 22px rgba(25,57,39,.05); }
        .excel-table { border-collapse:separate; border-spacing:0; }
        .excel-table th,.excel-table td { border-color:#e5ede7; }
        .excel-table thead th { height:48px; background:#19764d; color:#fff; font-size:10px; letter-spacing:.055em; }
        .excel-table thead th:first-child { background:#145f3d; }
        .time-column { background:#f7faf8; color:#53645a; font-size:10px; }
        .schedule-cell { height:102px; background:#fff; }
        .empty-cell { min-height:88px; color:#b0bbb3; font-size:9px; }
        .class-entry { min-height:86px; border-color:#d8e9dc; border-radius:10px; background:linear-gradient(145deg,#f2f9f4,#fbfdfb); box-shadow:inset 3px 0 #2d8a5c; }
        .class-course { color:#176e48; font-size:11px; }
        .class-subject { color:#2d4537; font-size:10px; }
        .class-detail { color:#75837a; font-size:8.5px; }
        .class-detail strong { color:#516258; }
        .year-level { background:#e7f2e9; color:#176e48; }
        .room-name { color:#456050; }
        .room-name i { color:#19764d; }
        .building-name { color:#869289; }
        .temporary-reservation-entry { min-height:86px; border-color:#f0dca9; border-radius:10px; background:linear-gradient(145deg,#fff8e8,#fffdf8); box-shadow:inset 3px 0 #d69a22; }
        .temporary-reservation-badge { background:#fff0c7; color:#8a5a00; }
        .empty-schedule-page { border-color:#dce7df; border-radius:16px; box-shadow:0 5px 16px rgba(25,57,39,.035); }
        .empty-schedule-page i,.empty-schedule-page strong { color:#19764d; }
        @media(max-width:800px) {
            .my-schedules-page { padding:4px 0 28px; }
            .schedule-header { padding:21px 19px; border-radius:16px; }
            .schedule-header::after { right:-115px; }
            .schedule-title { font-size:23px; }
            .faculty-info-card { align-items:flex-start; }
            .excel-wrapper { border-radius:13px; }
        }

</style>


            <div class="my-schedules-page">


                <!-- =====================================================
                     PAGE HEADER
                ====================================================== -->

                <div class="schedule-header">

                    <h1 class="schedule-title">
                        My Schedules
                    </h1>

                    <p class="schedule-subtitle">
                        View only the classes assigned to your faculty account.
                    </p>

                </div>


                <!-- =====================================================
                     FACULTY INFORMATION
                ====================================================== -->

                <div class="faculty-info-card">

                    <div class="faculty-info-left">

                        <div class="faculty-avatar">

                            <i class="fa-solid fa-user"></i>

                        </div>

                        <div>

                            <div class="faculty-name">

                                {{ Auth::user()->name }}

                            </div>

                            <div class="faculty-label">

                                Logged-in Faculty Schedule

                            </div>

                        </div>

                    </div>


                    @if($activeTerm)

                        <div class="term-badge">

                            {{ $activeTerm->academic_year }}

                            •

                            {{ $activeTerm->semester }}

                        </div>

                    @else

                        <div class="term-badge">

                            Current Schedule

                        </div>

                    @endif

                </div>


                @if(($mySchedules ?? collect())->count() || ($temporaryReservations ?? collect())->count())


                    <!-- =====================================================
                         EXCEL TABLE
                    ====================================================== -->

                    <div class="excel-wrapper">

                        <table class="excel-table">

                            <thead>

                                <tr>

                                    <th class="time-column">
                                        TIME
                                    </th>

                                    <th>
                                        MON
                                    </th>

                                    <th>
                                        WED
                                    </th>

                                    <th>
                                        FRI
                                    </th>

                                    <th class="time-column">
                                        TIME
                                    </th>

                                    <th>
                                        TUE
                                    </th>

                                    <th>
                                        THU
                                    </th>

                                    <th>
                                        SAT
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | DEFAULT TIME ROWS
                                    |--------------------------------------------------------------------------
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
                                    | GROUP SCHEDULES
                                    |--------------------------------------------------------------------------
                                    */

                                    $scheduleRows = [];


                                    foreach (
                                        $mySchedules
                                        as $schedule
                                    ) {

                                        $day = strtoupper(
                                            trim(
                                                (string)
                                                $schedule->day
                                            )
                                        );


                                        /*
                                        |--------------------------------------------------------------------------
                                        | NORMALIZE DAY
                                        |--------------------------------------------------------------------------
                                        */

                                        $dayMap = [

                                            'MONDAY' =>
                                                'MON',

                                            'TUESDAY' =>
                                                'TUE',

                                            'WEDNESDAY' =>
                                                'WED',

                                            'THURSDAY' =>
                                                'THU',

                                            'FRIDAY' =>
                                                'FRI',

                                            'SATURDAY' =>
                                                'SAT',

                                            'SUNDAY' =>
                                                'SUN',

                                        ];


                                        if (
                                            isset(
                                                $dayMap[$day]
                                            )
                                        ) {

                                            $day =
                                                $dayMap[$day];

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | FIND ROW POSITION
                                        |--------------------------------------------------------------------------
                                        */

                                        $position =
                                            $schedule->row_position;


                                        /*
                                        |--------------------------------------------------------------------------
                                        | FALLBACK BY TIME
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            $position === null ||
                                            $position === ''
                                        ) {

                                            $savedTime =
                                                trim(
                                                    (string)
                                                    $schedule->time
                                                );


                                            /*
                                            | Convert common AM/PM forms
                                            */

                                            $normalizedTime =
                                                strtolower(
                                                    str_replace(
                                                        ' ',
                                                        '',
                                                        $savedTime
                                                    )
                                                );


                                            $position =
                                                array_search(
                                                    $normalizedTime,
                                                    array_map(
                                                        function (
                                                            $time
                                                        ) {

                                                            return strtolower(
                                                                str_replace(
                                                                    ' ',
                                                                    '',
                                                                    $time
                                                                )
                                                            );

                                                        },
                                                        $defaultTimes
                                                    )
                                                );

                                        }


                                        if (
                                            $position === false ||
                                            $position === null
                                        ) {

                                            continue;

                                        }


                                        $position =
                                            (int)
                                            $position;


                                        if (
                                            $position < 0 ||
                                            $position > 8
                                        ) {

                                            continue;

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | CREATE ROW
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            !isset(
                                                $scheduleRows[
                                                    $position
                                                ]
                                            )
                                        ) {

                                            $scheduleRows[
                                                $position
                                            ] = [];

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | CREATE DAY
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            !isset(
                                                $scheduleRows[
                                                    $position
                                                ][$day]
                                            )
                                        ) {

                                            $scheduleRows[
                                                $position
                                            ][$day] = [];

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | STORE SCHEDULE
                                        |--------------------------------------------------------------------------
                                        */

                                        $scheduleRows[
                                            $position
                                        ][$day][] =
                                            $schedule;

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | TEMPORARY RESERVATION ROWS
                                    |--------------------------------------------------------------------------
                                    |
                                    | Reservations are date-specific temporary entries.
                                    | Put them into the same weekly grid as normal schedules.
                                    |--------------------------------------------------------------------------
                                    */

                                    $reservationRows = [];

                                    $renderReservationCell =
                                        function ($reservation) {

                                            $facultySubject =
                                                $reservation->facultySubject ?? null;

                                            $course =
                                                $facultySubject?->course_code
                                                ?: 'Room Reservation';

                                            $subject =
                                                $facultySubject?->subject
                                                ?: ($reservation->purpose ?? 'Reserved Room');

                                            $program =
                                                $facultySubject?->program
                                                ?: 'Temporary Reservation';

                                            $room =
                                                $reservation->room?->room_name
                                                ?: 'Room Not Set';

                                            $building = '';

                                            if ($reservation->room?->building) {

                                                $building =
                                                    $reservation->room->building->name
                                                    ??
                                                    $reservation->room->building->building_name
                                                    ??
                                                    '';

                                            }

                                            $time =
                                                trim(
                                                    (string) (
                                                        $reservation->time
                                                        ?: (
                                                            ($reservation->start_time ?? '')
                                                            . ' - ' .
                                                            ($reservation->end_time ?? '')
                                                        )
                                                    )
                                                );

                                            return '
                                                <div class="temporary-reservation-entry">

                                                    <div class="temporary-reservation-badge">
                                                        <i class="fa-solid fa-calendar-check"></i>
                                                        Temporary Reservation
                                                    </div>

                                                    <div class="temporary-reservation-course">
                                                        ' . e($course) . '
                                                    </div>

                                                    <div class="temporary-reservation-detail">
                                                        ' . e($subject) . '
                                                    </div>

                                                    <div class="temporary-reservation-detail">
                                                        <strong>Program:</strong>
                                                        ' . e($program) . '
                                                    </div>

                                                    <div class="temporary-reservation-detail">
                                                        <strong>Room:</strong>
                                                        ' . e($room) . '
                                                    </div>

                                                    ' .
                                                    (
                                                        $building
                                                        ? '<div class="temporary-reservation-detail">
                                                            <strong>Building:</strong>
                                                            ' . e($building) . '
                                                        </div>'
                                                        : ''
                                                    )
                                                    .

                                                    '<div class="temporary-reservation-time">
                                                        <i class="fa-solid fa-clock"></i>
                                                        ' . e($time) . '
                                                    </div>

                                                </div>
                                            ';
                                        };

                                    foreach (($temporaryReservations ?? collect()) as $reservation) {

                                        try {

                                            $reservationDay =
                                                strtoupper(
                                                    Carbon\Carbon::parse(
                                                        $reservation->date
                                                    )->format('D')
                                                );

                                        } catch (Throwable $e) {

                                            continue;
                                        }

                                        $dayMap = [
                                            'MON' => 'MON',
                                            'TUE' => 'TUE',
                                            'WED' => 'WED',
                                            'THU' => 'THU',
                                            'FRI' => 'FRI',
                                            'SAT' => 'SAT',
                                            'SUN' => 'SUN',
                                        ];

                                        if (!isset($dayMap[$reservationDay])) {
                                            continue;
                                        }

                                        $reservationDay = $dayMap[$reservationDay];

                                        $startTime =
                                            trim(
                                                (string) $reservation->start_time
                                            );

                                        if ($startTime === '') {
                                            continue;
                                        }

                                        $reservationPosition = null;

                                        foreach ($defaultTimes as $defaultPosition => $defaultTime) {

                                            $defaultStart =
                                                trim(
                                                    explode('-', $defaultTime, 2)[0]
                                                );

                                            if (
                                                substr($defaultStart, 0, 5) ===
                                                substr($startTime, 0, 5)
                                            ) {

                                                $reservationPosition =
                                                    $defaultPosition;

                                                break;
                                            }
                                        }

                                        if ($reservationPosition === null) {
                                            continue;
                                        }

                                        if (
                                            !isset(
                                                $reservationRows[
                                                    $reservationPosition
                                                ]
                                            )
                                        ) {

                                            $reservationRows[
                                                $reservationPosition
                                            ] = [];

                                        }

                                        if (
                                            !isset(
                                                $reservationRows[
                                                    $reservationPosition
                                                ][$reservationDay]
                                            )
                                        ) {

                                            $reservationRows[
                                                $reservationPosition
                                            ][$reservationDay] = [];

                                        }

                                        $reservationRows[
                                            $reservationPosition
                                        ][$reservationDay][] =
                                            $reservation;
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | CELL RENDER HELPER
                                    |--------------------------------------------------------------------------
                                    */

                                    $renderScheduleCell =
                                        function ($schedule) {

                                            $course =
                                                $schedule->course_code
                                                ?: 'No Course Code';


                                            $subject =
                                                $schedule->subject
                                                ?: 'No Subject';


                                            $program =
                                                $schedule->program
                                                ?: 'Program Not Set';


                                            $yearLevel =
                                                $schedule->year_level
                                                ?: 'Year Level';


                                            $room =
                                                $schedule->room?->room_name
                                                ?: 'Room Not Set';


                                            $building =
                                                null;


                                            if (
                                                $schedule->room?->building
                                            ) {

                                                $building =
                                                    $schedule
                                                        ->room
                                                        ->building
                                                        ->name
                                                    ??
                                                    $schedule
                                                        ->room
                                                        ->building
                                                        ->building_name
                                                    ??
                                                    null;

                                            }


                                            return '

                                                <div class="class-entry">

                                                    <div class="year-level">
                                                        '
                                                        . e(
                                                            $yearLevel
                                                        )
                                                        .
                                                    '</div>

                                                    <div class="class-course">
                                                        '
                                                        . e(
                                                            $course
                                                        )
                                                        .
                                                    '</div>

                                                    <div class="class-subject">
                                                        '
                                                        . e(
                                                            $subject
                                                        )
                                                        .
                                                    '</div>

                                                    <div class="class-detail">
                                                        <strong>
                                                            Program:
                                                        </strong>
                                                        '
                                                        . e(
                                                            $program
                                                        )
                                                        .
                                                    '</div>

                                                    <div class="room-name">

                                                        <i class="fa-solid fa-door-open"></i>

                                                        Room:
                                                        '
                                                        . e(
                                                            $room
                                                        )
                                                        .
                                                    '</div>

                                                    '
                                                    .
                                                    (
                                                        $building
                                                        ?
                                                        '

                                                        <div class="building-name">

                                                            Building:
                                                            '
                                                            . e(
                                                                $building
                                                            )
                                                            .

                                                        '</div>

                                                        '
                                                        :
                                                        ''
                                                    )
                                                    .

                                                '</div>

                                            ';

                                        };


                                @endphp


                                @php
                                    /*
                                    |--------------------------------------------------------------------------
                                    | PUBLISHED ROW COUNT
                                    |--------------------------------------------------------------------------
                                    | A newly published custom time row can have a
                                    | row_position greater than the original 0-8 range.
                                    |--------------------------------------------------------------------------
                                    */
                                    $maxSchedulePosition = -1;

                                    foreach (array_keys($scheduleRows) as $schedulePosition) {
                                        if (is_numeric($schedulePosition)) {
                                            $maxSchedulePosition = max(
                                                $maxSchedulePosition,
                                                (int) $schedulePosition
                                            );
                                        }
                                    }

                                    $publishedRowCount = max(
                                        9,
                                        $maxSchedulePosition + 1
                                    );

                                    /*
                                    |--------------------------------------------------------------------------
                                    | MOVE NOON BREAK WHEN POSITION 4 IS A CUSTOM ROW
                                    |--------------------------------------------------------------------------
                                    */
                                    $breakBeforePosition = 4;

                                    $positionFourTime = null;

                                    foreach (['MON','WED','FRI','TUE','THU','SAT'] as $breakDay) {
                                        $candidate =
                                            $scheduleRows[4][$breakDay][0] ?? null;

                                        if ($candidate && !empty($candidate->time)) {
                                            $positionFourTime = trim($candidate->time);
                                            break;
                                        }
                                    }

                                    if (
                                        $positionFourTime !== null &&
                                        strtolower(str_replace(' ', '', $positionFourTime))
                                            !== strtolower(str_replace(' ', '', '12:00-1:00'))
                                    ) {
                                        $postNoonDefaults = [
                                            '1:00-2:00',
                                            '2:00-3:00',
                                            '3:00-4:00',
                                            '4:00-5:00',
                                        ];

                                        for (
                                            $candidatePosition = 4;
                                            $candidatePosition < $publishedRowCount;
                                            $candidatePosition++
                                        ) {
                                            $candidateTime = null;

                                            foreach (['MON','WED','FRI','TUE','THU','SAT'] as $breakDay) {
                                                $candidate =
                                                    $scheduleRows[$candidatePosition][$breakDay][0] ?? null;

                                                if ($candidate && !empty($candidate->time)) {
                                                    $candidateTime = trim($candidate->time);
                                                    break;
                                                }
                                            }

                                            if (
                                                $candidateTime !== null &&
                                                in_array(
                                                    strtolower(str_replace(' ', '', $candidateTime)),
                                                    array_map(
                                                        function ($time) {
                                                            return strtolower(str_replace(' ', '', $time));
                                                        },
                                                        $postNoonDefaults
                                                    ),
                                                    true
                                                )
                                            ) {
                                                $breakBeforePosition = $candidatePosition;
                                                break;
                                            }

                                            $breakBeforePosition = $publishedRowCount;
                                        }
                                    }
                                @endphp

                                @for(
                                    $position = 0;
                                    $position < $publishedRowCount;
                                    $position++
                                )


                                    <!-- =================================================
                                         NOON BREAK
                                    ================================================== -->

                                    @if($position === $breakBeforePosition)

                                        <tr>

                                            <td
                                                colspan="7"
                                                style="
                                                    background:#f0f8f3;
                                                    color:#176b3a;
                                                    text-align:center;
                                                    font-size:10px;
                                                    font-weight:900;
                                                    height:34px;
                                                "
                                            >

                                                NOON BREAK

                                            </td>

                                        </tr>

                                    @endif


                                    @php

                                        /*
                                        |--------------------------------------------------------------------------
                                        | LEFT TIME
                                        |--------------------------------------------------------------------------
                                        */

                                        $leftTime =
                                            $defaultTimes[
                                                $position
                                            ] ?? '';


                                        foreach (
                                            ['MON','WED','FRI']
                                            as $leftDay
                                        ) {

                                            $firstSchedule =
                                                $scheduleRows[
                                                    $position
                                                ][$leftDay][0]
                                                ?? null;


                                            if (
                                                $firstSchedule
                                            ) {

                                                if (
                                                    !empty(
                                                        $firstSchedule->time
                                                    )
                                                ) {

                                                    $leftTime =
                                                        $firstSchedule->time;

                                                }

                                                break;

                                            }

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | RIGHT TIME
                                        |--------------------------------------------------------------------------
                                        */

                                        $rightTime =
                                            $defaultTimes[
                                                $position
                                            ] ?? '';


                                        foreach (
                                            ['TUE','THU']
                                            as $rightDay
                                        ) {

                                            $firstSchedule =
                                                $scheduleRows[
                                                    $position
                                                ][$rightDay][0]
                                                ?? null;


                                            if (
                                                $firstSchedule
                                            ) {

                                                if (
                                                    !empty(
                                                        $firstSchedule->time
                                                    )
                                                ) {

                                                    $rightTime =
                                                        $firstSchedule->time;

                                                }

                                                break;

                                            }

                                        }

                                    @endphp


                                    <tr>


                                        <!-- =================================================
                                             LEFT TIME
                                        ================================================== -->

                                        <td class="time-column">

                                            {{ $leftTime }}

                                        </td>


                                        <!-- =================================================
                                             MONDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="MON">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['MON']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['MON']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        </td>


                                        <!-- =================================================
                                             WEDNESDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="WED">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['WED']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['WED']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        </td>


                                        <!-- =================================================
                                             FRIDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="FRI">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['FRI']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['FRI']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        </td>


                                        <!-- =================================================
                                             RIGHT TIME
                                        ================================================== -->

                                        <td class="time-column">

                                            {{ $rightTime }}

                                        </td>


                                        <!-- =================================================
                                             TUESDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="TUE">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['TUE']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['TUE']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        </td>


                                        <!-- =================================================
                                             THURSDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="THU">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['THU']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['THU']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        </td>



                                        <!-- =================================================
                                             SATURDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="SAT">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['SAT']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['SAT']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        </td>


                                    </tr>


                                @endfor


                            </tbody>

                        </table>

                    </div>


                @else


                    <!-- =====================================================
                         NO SCHEDULES
                    ====================================================== -->

                    <div class="empty-schedule-page">

                        <i class="fa-solid fa-calendar-xmark"></i>

                        <strong>
                            No Schedules Found
                        </strong>

                        <span>

                            There are no schedules or active room reservations assigned to
                            {{ Auth::user()->name }}
                            for the current week.

                        </span>

                    </div>


                @endif


            </div>

        </main>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function scheduleSignature(cell)
    {
        if (!cell) {
            return '';
        }

        if (cell.querySelector('.temporary-reservation-entry')) {
            return '';
        }

        const entries = cell.querySelectorAll('.class-entry');

        if (entries.length !== 1) {
            return '';
        }

        const clone = entries[0].cloneNode(true);

        clone.querySelectorAll(
            '.room-name, .building-name'
        ).forEach(function (element) {
            element.remove();
        });

        return clone.innerHTML
            .replace(/\s+/g, ' ')
            .trim()
            .toUpperCase();
    }

    function mergeGroup(row, days)
    {
        let index = 0;

        while (index < days.length) {

            const first =
                row.querySelector(
                    'td[data-day="' + days[index] + '"]'
                );

            if (!first) {
                index++;
                continue;
            }

            const signature = scheduleSignature(first);

            if (!signature) {
                index++;
                continue;
            }

            let end = index + 1;

            while (end < days.length) {

                const next =
                    row.querySelector(
                        'td[data-day="' + days[end] + '"]'
                    );

                if (
                    !next ||
                    scheduleSignature(next) !== signature
                ) {
                    break;
                }

                end++;
            }

            const count = end - index;

            if (count >= 2) {

                first.classList.add(
                    'merged-schedule-cell'
                );

                first.colSpan = count;

                for (
                    let removeIndex = index + 1;
                    removeIndex < end;
                    removeIndex++
                ) {
                    const cell =
                        row.querySelector(
                            'td[data-day="' +
                            days[removeIndex] +
                            '"]'
                        );

                    if (cell) {
                        cell.remove();
                    }
                }
            }

            index = end;
        }
    }

    document
        .querySelectorAll('.excel-table tbody tr')
        .forEach(function (row) {

            if (row.querySelector('td[colspan="7"]')) {
                return;
            }

            mergeGroup(
                row,
                ['MON', 'WED', 'FRI']
            );

            mergeGroup(
                row,
                ['TUE', 'THU', 'SAT']
            );
        });

});
</script>
