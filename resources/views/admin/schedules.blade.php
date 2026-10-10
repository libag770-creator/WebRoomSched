@include('footerheader.header')
<div class="wrapper">

    

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>

                /* =========================================================
                   PAGE
                ========================================================= */

                body {
                    background: #f5f7f6;
                }

                .admin-schedule-page {
                    max-width: 1250px;
                    margin: 0 auto;
                    padding: 10px 0 35px;
                }


                /* =========================================================
                   HEADER
                ========================================================= */

                .schedule-header {
                    margin-bottom: 22px;
                }

                .schedule-title {
                    margin: 0;
                    font-size: 24px;
                    font-weight: 800;
                    color: #176b3a;
                }

                .schedule-subtitle {
                    margin-top: 6px;
                    color: #667085;
                    font-size: 13px;
                }


                /* =========================================================
                   DEPARTMENT CARDS
                ========================================================= */

                .department-list {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 16px;
                    margin-bottom: 22px;
                }

                .department-card {
                    background: #ffffff;
                    border: 1px solid #dfe5e1;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow:
                        0 3px 10px rgba(16, 24, 40, .06);

                    cursor: pointer;

                    transition:
                        transform .2s ease,
                        border-color .2s ease,
                        box-shadow .2s ease;
                }

                .department-card:hover {
                    transform: translateY(-2px);

                    border-color: #176b3a;

                    box-shadow:
                        0 7px 18px rgba(16, 24, 40, .10);
                }

                .department-card.active {
                    border: 2px solid #176b3a;
                    background: #f3faf6;
                }

                .department-button {
                    width: 100%;
                    min-height: 145px;

                    border: none;
                    background: transparent;

                    padding: 20px;

                    display: flex;
                    flex-direction: column;

                    align-items: flex-start;
                    justify-content: space-between;

                    cursor: pointer;
                    text-align: left;
                }

                .department-icon {
                    width: 42px;
                    height: 42px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    border-radius: 10px;

                    background: #eaf6ef;
                    color: #176b3a;

                    font-size: 19px;

                    margin-bottom: 14px;
                }

                .department-name {
                    font-size: 17px;
                    font-weight: 800;
                    color: #176b3a;

                    margin-bottom: 5px;
                }

                .department-info {
                    font-size: 11px;
                    color: #667085;
                }

                .department-arrow {
                    align-self: flex-end;

                    margin-top: -22px;

                    color: #176b3a;
                    font-size: 19px;

                    transition: transform .2s ease;
                }

                .department-card.active .department-arrow {
                    transform: translateX(5px);
                }


                /* =========================================================
                   DEPARTMENT CONTENT
                ========================================================= */

                .department-content {
                    display: none;
                }

                .department-content.open {
                    display: block;
                }

                .selected-department-header {
                    background: #ffffff;

                    border: 1px solid #dfe5e1;
                    border-radius: 12px;

                    padding: 17px 20px;

                    margin-bottom: 14px;

                    box-shadow:
                        0 2px 8px rgba(16, 24, 40, .05);
                }

                .selected-department-title {
                    margin: 0;

                    color: #176b3a;

                    font-size: 18px;
                    font-weight: 800;
                }

                .selected-department-subtitle {
                    margin: 5px 0 0;

                    color: #667085;

                    font-size: 11px;
                }


                /* =========================================================
                   BUILDINGS
                ========================================================= */

                .building-list {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }

                .building-card {
                    background: #ffffff;

                    border: 1px solid #dfe5e1;
                    border-radius: 12px;

                    overflow: hidden;

                    box-shadow:
                        0 2px 8px rgba(16, 24, 40, .05);
                }

                .building-button {
                    width: 100%;

                    border: none;
                    background: #ffffff;

                    padding: 17px 20px;

                    display: flex;
                    align-items: center;
                    justify-content: space-between;

                    cursor: pointer;

                    text-align: left;

                    transition:
                        background .2s ease,
                        color .2s ease;
                }

                .building-button:hover {
                    background: #f3faf6;
                }

                .building-button.active {
                    background: #eaf6ef;
                }

                .building-left {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .building-icon {
                    width: 38px;
                    height: 38px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    border-radius: 9px;

                    background: #eaf6ef;
                    color: #176b3a;

                    font-size: 17px;
                }

                .building-name {
                    font-size: 14px;
                    font-weight: 800;

                    color: #1f2937;
                }

                .building-department {
                    margin-top: 3px;

                    font-size: 11px;

                    color: #667085;
                }

                .building-arrow {
                    color: #176b3a;

                    font-size: 19px;

                    transition:
                        transform .2s ease;
                }

                .building-button.active .building-arrow {
                    transform: rotate(180deg);
                }


                /* =========================================================
                   ROOMS
                ========================================================= */

                .rooms-container {
                    display: none;

                    padding: 16px;

                    background: #f8faf9;

                    border-top: 1px solid #dfe5e1;

                    grid-template-columns:
                        repeat(3, minmax(0, 1fr));

                    gap: 12px;
                }

                .rooms-container.open {
                    display: grid;
                }

                .room-card {
                    border: 1px solid #dfe5e1;

                    background: #ffffff;

                    border-radius: 10px;

                    padding: 15px;

                    cursor: pointer;

                    transition:
                        border-color .2s ease,
                        transform .2s ease,
                        box-shadow .2s ease;
                }

                .room-card:hover {
                    border-color: #176b3a;

                    transform: translateY(-2px);

                    box-shadow:
                        0 6px 16px
                        rgba(16, 24, 40, .08);
                }

                .room-card.active {
                    border-color: #176b3a;

                    background: #f3faf6;
                }

                .room-top {
                    display: flex;

                    align-items: center;

                    justify-content: space-between;

                    gap: 10px;
                }

                .room-name {
                    color: #176b3a;

                    font-size: 14px;

                    font-weight: 800;
                }

                .room-arrow {
                    color: #176b3a;

                    font-size: 18px;
                }

                .room-info {
                    margin-top: 7px;

                    font-size: 11px;

                    color: #667085;
                }

                .room-action {
                    margin-top: 12px;

                    font-size: 10px;

                    font-weight: 800;

                    color: #176b3a;
                }


                /* =========================================================
                   EMPTY
                ========================================================= */

                .empty-building {
                    grid-column: 1 / -1;

                    padding: 25px;

                    text-align: center;

                    color: #98a2b3;

                    font-size: 12px;
                }

                .empty-department {
                    padding: 35px;

                    text-align: center;

                    background: #ffffff;

                    border: 1px dashed #cfd8d2;

                    border-radius: 12px;

                    color: #98a2b3;

                    font-size: 12px;
                }


                /* =========================================================
                   DEPARTMENT PLACEHOLDER
                ========================================================= */

                .department-placeholder {
                    background: #ffffff;

                    border: 1px dashed #cfd8d2;

                    border-radius: 12px;

                    padding: 45px 20px;

                    text-align: center;

                    color: #667085;
                }

                .department-placeholder-icon {
                    font-size: 32px;

                    margin-bottom: 10px;
                }

                .department-placeholder h3 {
                    margin: 0 0 5px;

                    color: #176b3a;

                    font-size: 16px;
                }

                .department-placeholder p {
                    margin: 0;

                    font-size: 12px;
                }


                /* =========================================================
                   SCHEDULE PANEL
                ========================================================= */

                .schedule-panel {
                    display: none;

                    margin-top: 22px;

                    background: #ffffff;

                    border: 1px solid #dfe5e1;

                    border-radius: 12px;

                    overflow: hidden;

                    box-shadow:
                        0 4px 15px
                        rgba(16, 24, 40, .06);
                }

                .schedule-panel.open {
                    display: block;
                }

                .schedule-panel-header {
                    background: #176b3a;

                    color: #ffffff;

                    padding: 17px 20px;

                    display: flex;

                    align-items: center;

                    justify-content: space-between;

                    gap: 15px;
                }

                .schedule-panel-title {
                    margin: 0;

                    font-size: 17px;

                    font-weight: 800;
                }

                .schedule-panel-subtitle {
                    margin-top: 4px;

                    font-size: 11px;

                    opacity: .88;
                }

                .close-schedule {
    flex-shrink: 0;

    border: 1px solid rgba(255,255,255,.45);

    background: rgba(255,255,255,.12);

    color: #ffffff;

    padding: 9px 15px;

    border-radius: 7px;

    cursor: pointer;

    font-size: 11px;

    font-weight: 800;

    transition:
        background .2s ease,
        transform .2s ease;
}

.close-schedule:hover {

    background:
        rgba(255,255,255,.22);

    transform:
        translateY(-1px);

}

.close-schedule:active {

    transform:
        translateY(0);

}


                /* =========================================================
                   LOADING
                ========================================================= */

                .schedule-loading {
    min-height: 180px;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    gap: 12px;

    padding: 30px;

    color: #667085;

    text-align: center;
}

.loading-spinner {
    width: 30px;
    height: 30px;

    border: 3px solid #e5eee8;
    border-top-color: #176b3a;

    border-radius: 50%;

    animation: scheduleSpin .8s linear infinite;
}

.loading-text {
    font-size: 12px;
    font-weight: 600;
}

@keyframes scheduleSpin {

    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }

}

.schedule-error {
    min-height: 180px;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    gap: 8px;

    padding: 30px;

    text-align: center;
}

.schedule-error-title {
    color: #344054;

    font-size: 14px;
    font-weight: 800;
}

.schedule-error-message {
    max-width: 500px;

    color: #667085;

    font-size: 11px;
}

.retry-schedule-btn {
    margin-top: 8px;

    padding: 9px 16px;

    border: none;
    border-radius: 7px;

    background: #176b3a;
    color: #ffffff;

    font-size: 11px;
    font-weight: 700;

    cursor: pointer;
}

.retry-schedule-btn:hover {
    background: #12582f;
}


    /* =========================================================
   FACULTY TIMETABLE
========================================================= */

.timetable-wrapper {
    width: 100%;
    overflow-x: auto;

    padding: 18px;

    box-sizing: border-box;
}

.admin-timetable {
    width: 100%;

    min-width: 1300px;

    table-layout: fixed;

    border-collapse: separate;
    border-spacing: 0;

    background: #ffffff;

    border: 1px solid #dfe5e1;

    border-radius: 10px;

    overflow: hidden;
}


/* =========================================================
   TABLE CELLS
========================================================= */

.admin-timetable th,
.admin-timetable td {
    border-right: 1px solid #dfe5e1;
    border-bottom: 1px solid #dfe5e1;

    box-sizing: border-box;
}


/* =========================================================
   TABLE HEADER
========================================================= */

.admin-timetable th {
    height: 46px;

    padding: 10px 8px;

    background: #f0f8f3;

    color: #176b3a;

    font-size: 12px;

    font-weight: 800;

    text-align: center;

    vertical-align: middle;
}


/* =========================================================
   TIME COLUMNS
========================================================= */

.admin-timetable th:nth-child(1),
.admin-timetable th:nth-child(5),
.admin-timetable .time-column {
    width: 125px;

    min-width: 125px;

    max-width: 125px;
}

.admin-timetable .time-column {
    height: 82px;

    padding: 10px;

    background: #fafcfb;

    color: #344054;

    font-size: 12px;

    font-weight: 800;

    text-align: center;

    vertical-align: middle;

    white-space: nowrap;
}


/* =========================================================
   CLASSROOM CELLS
========================================================= */

.admin-timetable td:not(.time-column) {
    height: 82px;

    padding: 6px;

    text-align: center;

    vertical-align: middle;
}


/* =========================================================
   REMOVE EXTRA BORDERS
========================================================= */

.admin-timetable th:last-child,
.admin-timetable td:last-child {
    border-right: none;
}

.admin-timetable tbody tr:last-child td {
    border-bottom: none;
}


/* =========================================================
   EMPTY CELL
========================================================= */

.empty-schedule-cell {
    display: inline-block;

    color: #98a2b3;

    font-size: 10px;

    font-style: italic;
}


/* =========================================================
   CLASS ENTRY
========================================================= */

.class-entry {
    width: 100%;

    min-height: 68px;

    padding: 9px;

    box-sizing: border-box;

    border-radius: 8px;

    text-align: center;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;
}

/* =========================================================
   MERGED SCHEDULE CELL
========================================================= */

.merged-schedule-cell {
    text-align: center !important;
    vertical-align: middle !important;
}

.merged-schedule-cell .class-entry {
    width: 100%;

    text-align: center;

    align-items: center;

    justify-content: center;
}

.merged-schedule-cell .class-course,
.merged-schedule-cell .class-subject,
.merged-schedule-cell .class-instructor,
.merged-schedule-cell .class-program,
.merged-schedule-cell .class-type,
.merged-schedule-cell .class-major {
    width: 100%;
    text-align: center;
}


/* =========================================================
   COURSE
========================================================= */

.class-course {
    color: #176b3a;

    font-size: 12px;

    font-weight: 900;

    line-height: 1.2;
}


/* =========================================================
   SUBJECT
========================================================= */

.class-subject {
    margin-top: 4px;

    color: #1f2937;

    font-size: 11px;

    font-weight: 600;

    line-height: 1.2;
}


/* =========================================================
   INSTRUCTOR
========================================================= */

.class-instructor {
    margin-top: 5px;

    color: #667085;

    font-size: 10px;

    line-height: 1.2;
}


/* =========================================================
   PROGRAM
========================================================= */

/* =========================================================
   ROOM SWAP STATUS
========================================================= */

.room-swap-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    width: fit-content;
    margin-top: 7px;
    padding: 4px 7px;
    border-radius: 999px;
    background: #fff7e6;
    border: 1px solid #f2d18b;
    color: #8a5a00;
    font-size: 8px;
    font-weight: 900;
    line-height: 1;
    letter-spacing: .35px;
    text-transform: uppercase;
}

.room-swap-badge i {
    font-size: 8px;
}

.room-swap-details {
    margin-top: 4px;
    color: #8a5a00;
    font-size: 9px;
    font-weight: 700;
    line-height: 1.35;
}

.room-swap-original {
    color: #667085;
    font-size: 9px;
    font-weight: 600;
}

.temporary-reservation-entry {
    width: 100%;
    min-height: 68px;
    padding: 9px;
    box-sizing: border-box;
    border-radius: 8px;
    text-align: left;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: #fff8e8;
    border: 1px solid #f2d18b;
}

.temporary-reservation-entry.pending {
    background: #f5f7ff;
    border-color: #b8c4f2;
}

.reservation-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    width: fit-content;
    margin-bottom: 5px;
    padding: 4px 7px;
    border-radius: 999px;
    background: #fff0c7;
    color: #8a5a00;
    font-size: 8px;
    font-weight: 900;
    text-transform: uppercase;
    line-height: 1;
}

.temporary-reservation-entry.pending .reservation-badge {
    background: #e9edff;
    color: #4656a3;
}

.reservation-course {
    color: #8a5a00;
    font-size: 11px;
    font-weight: 900;
    line-height: 1.2;
}

.temporary-reservation-entry.pending .reservation-course {
    color: #4656a3;
}

.reservation-purpose {
    margin-top: 4px;
    color: #344054;
    font-size: 10px;
    font-weight: 700;
    line-height: 1.2;
}

.reservation-requester {
    margin-top: 4px;
    color: #667085;
    font-size: 9px;
    line-height: 1.2;
}

.reservation-expiration {
    margin-top: 6px;
    color: #8a5a00;
    font-size: 8px;
    font-weight: 800;
    line-height: 1.3;
}

.temporary-reservation-entry.pending .reservation-expiration {
    color: #4656a3;
}

.schedule-legend {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    padding: 11px 18px;
    background: #fbfdfc;
    border-bottom: 1px solid #e5ebe7;
    color: #667085;
    font-size: 10px;
    font-weight: 600;
}

.schedule-legend-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 7px;
    border-radius: 999px;
    background: #fff7e6;
    border: 1px solid #f2d18b;
    color: #8a5a00;
    font-size: 8px;
    font-weight: 900;
    text-transform: uppercase;
}

.schedule-panel-subtitle .swap-live-indicator {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-left: 8px;
    padding: 3px 7px;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.28);
    color: #ffffff;
    font-size: 8px;
    font-weight: 900;
    text-transform: uppercase;
}

.schedule-panel-subtitle .swap-live-indicator i {
    color: #ffd166;
}

@media (max-width: 600px) {
    .schedule-legend {
        align-items: flex-start;
        flex-direction: column;
    }
}

.class-program {
    margin-top: 5px;

    color: #176b3a;

    font-size: 10px;

    font-weight: 800;

    line-height: 1.2;
}
.class-type {
    margin-top: 3px;
    color: #667085;
    font-size: 9px;
    font-weight: 800;
    line-height: 1.2;
    text-transform: uppercase;
}

.class-major {
    margin-top: 3px;
    color: #667085;
    font-size: 10px;
    font-weight: 600;
    line-height: 1.2;
}



/* =========================================================
   NOON BREAK
========================================================= */

.noon-break-row td {
    height: 34px !important;

    padding: 7px !important;

    background: #f8faf9;

    color: #667085;

    font-size: 11px !important;

    font-weight: 800;

    text-align: center !important;

    letter-spacing: .5px;
}


                /* =========================================================
                   RESPONSIVE
                ========================================================= */

                @media (max-width: 900px) {

                    .department-list {
                        grid-template-columns:
                            repeat(2, 1fr);
                    }

                    .rooms-container {
                        grid-template-columns:
                            repeat(2, minmax(0, 1fr));
                    }

                }


                @media (max-width: 600px) {

                    .admin-schedule-page {
                        padding: 5px 0 20px;
                    }

                    .schedule-title {
                        font-size: 20px;
                    }

                    .department-list {
                        grid-template-columns: 1fr;
                    }

                    .rooms-container {
                        grid-template-columns: 1fr;
                    }

                    .department-button {
                        min-height: 125px;
                    }

                    .schedule-panel-header {
                        align-items: flex-start;

                        flex-direction: column;
                    }

                    .close-schedule {
                        width: 100%;
                    }

                }

            </style>


            <div class="admin-schedule-page">


                <!-- =====================================================
                     HEADER
                ====================================================== -->

                <div class="schedule-header">

                    <h1 class="schedule-title">
                        Classroom Schedule
                    </h1>

                    <div class="schedule-subtitle">
                        Select a department, building, and room
                        to view the current classroom schedule.
                    </div>

                </div>


                <!-- =====================================================
                     DEPARTMENT CARDS
                ====================================================== -->

                <div class="department-list">

                    @forelse($departments as $department)

                        <div
                            class="department-card"
                            data-department-id="{{ $department['id'] }}"
                            onclick="
                                showDepartment(
                                    {{ $department['id'] }}
                                )
                            "
                        >

                            <button
                                type="button"
                                class="department-button"
                            >

                                <div class="department-icon">
                                    🏛
                                </div>


                                <div>

                                    <div class="department-name">
                                        {{ $department['name'] }}
                                    </div>


                                    <div class="department-info">

                                        {{ $department['buildings']->count() }}

                                        Building(s)

                                    </div>

                                </div>


                                <div class="department-arrow">
                                    →
                                </div>

                            </button>

                        </div>

                    @empty

                        <div class="empty-building">

                            No departments are available.

                        </div>

                    @endforelse

                </div>


                <!-- =====================================================
                     DEPARTMENT CONTENT
                ====================================================== -->

                @forelse($departments as $department)

                    <div
                        id="department-{{ $department['id'] }}"
                        class="department-content"
                    >


                        <!-- SELECTED DEPARTMENT HEADER -->

                        <div class="selected-department-header">

                            <h2 class="selected-department-title">

                                {{ $department['name'] }}

                            </h2>


                            <p class="selected-department-subtitle">

                                Select a building below to view
                                its available classrooms.

                            </p>

                        </div>


                        <!-- =================================================
                             BUILDINGS
                        ================================================== -->

                        <div class="building-list">

                            @forelse($department['buildings'] as $building)

                                <div class="building-card">


                                    <!-- BUILDING HEADER -->

                                    <button
                                        type="button"
                                        class="building-button"
                                        onclick="
                                            toggleBuilding(this)
                                        "
                                    >

                                        <div class="building-left">

                                            <div class="building-icon">
                                                🏫
                                            </div>


                                            <div>

                                                <div class="building-name">

                                                    {{ $building['name'] }}

                                                </div>


                                                <div class="building-department">

                                                    {{ $department['name'] }}

                                                </div>

                                            </div>

                                        </div>


                                        <div class="building-arrow">
                                            ▼
                                        </div>

                                    </button>


                                    <!-- =================================================
                                         ROOMS
                                    ================================================== -->

                                    <div class="rooms-container">

                                        @forelse(
                                            $building['rooms']
                                            as $room
                                        )

                                            <div
                                                class="room-card"
                                                data-room-id="{{ $room->id }}"
                                                onclick="
                                                    loadRoomSchedule(
                                                        {{ $room->id }},
                                                        this,
                                                        '{{ addslashes($room->room_name) }}',
                                                        '{{ addslashes($building['name']) }}'
                                                    )
                                                "
                                            >


                                                <div class="room-top">

                                                    <div class="room-name">

                                                        {{ $room->room_name }}

                                                    </div>


                                                    <div class="room-arrow">
                                                        →
                                                    </div>

                                                </div>


                                                <div class="room-info">

                                                    View classroom schedule

                                                </div>


                                                <div class="room-action">

                                                    VIEW SCHEDULE

                                                </div>

                                            </div>

                                        @empty

                                            <div class="empty-building">

                                                No rooms available
                                                in this building.

                                            </div>

                                        @endforelse

                                    </div>

                                </div>

                            @empty

                                <div class="empty-department">

                                    No buildings are available
                                    for this department.

                                </div>

                            @endforelse

                        </div>

                    </div>

                @empty

                    <div class="department-placeholder">

                        <div class="department-placeholder-icon">
                            🏫
                        </div>

                        <h3>
                            No Departments Available
                        </h3>

                        <p>
                            There are currently no departments
                            with classroom information.
                        </p>

                    </div>

                @endforelse


                <!-- =====================================================
                     DEFAULT MESSAGE
                ====================================================== -->

               @if(count($departments) > 0)

    <div
        id="departmentPlaceholder"
        class="department-placeholder"
    >

                        <div class="department-placeholder-icon">
                            🏫
                        </div>

                        <h3>
                            Select a Department
                        </h3>

                        <p>
                            Choose CAT, CCJEPA, or CED above
                            to view its buildings and rooms.
                        </p>

                    </div>

                @endif


                <!-- =====================================================
                     SCHEDULE PANEL
                ====================================================== -->

                <div
                    id="schedulePanel"
                    class="schedule-panel"
                >


                    <div class="schedule-panel-header">

                        <div>

                            <h2
                                id="scheduleRoomName"
                                class="schedule-panel-title"
                            >
                                Classroom Schedule
                            </h2>


                            <div
                                id="scheduleRoomBuilding"
                                class="schedule-panel-subtitle"
                            >
                                View the current schedule for this classroom.
                            </div>

                        </div>


                        <button
                            type="button"
                            class="close-schedule"
                            onclick="closeSchedule()"
                        >
                            Close Schedule
                        </button>

                    </div>

                    <div class="schedule-legend">
                        <span class="schedule-legend-badge">
                            <i class="fa-solid fa-right-left"></i>
                            Room Swap Active
                        </span>
                        <span>
                            This class is temporarily using a different room.
                        </span>

                        <span class="schedule-legend-badge reservation-legend-badge">
                            <i class="fa-solid fa-calendar-check"></i>
                            Temporary Reservation
                        </span>
                        <span>
                            The room is temporarily reserved until the requested time ends.
                        </span>
                    </div>


                    <div id="scheduleContent">

                        <div class="schedule-loading">

                            Select a room to view its schedule.

                        </div>

                    </div>

                </div>

            </div>


          <script>

let reservationExpiryTimer = null;


/* =========================================================
   SHOW DEPARTMENT
========================================================= */

function showDepartment(departmentId)
{
    document
        .querySelectorAll('.department-content')
        .forEach(function(section) {

            section.classList.remove('open');

        });

    document
        .querySelectorAll('.department-card')
        .forEach(function(card) {

            card.classList.remove('active');

        });

    const selectedCard =
        document.querySelector(
            '.department-card[data-department-id="' +
            departmentId +
            '"]'
        );

    if (selectedCard) {

        selectedCard.classList.add('active');

    }

    const selectedDepartment =
        document.getElementById(
            'department-' +
            departmentId
        );

    if (selectedDepartment) {

        selectedDepartment.classList.add('open');

    }

    const placeholder =
        document.getElementById(
            'departmentPlaceholder'
        );

    if (placeholder) {

        placeholder.style.display =
            'none';

    }

    closeSchedule();
}


/* =========================================================
   BUILDING TOGGLE
========================================================= */

function toggleBuilding(button)
{
    const rooms =
        button.nextElementSibling;

    const isOpen =
        rooms.classList.contains('open');

    if (isOpen) {

        rooms.classList.remove('open');

        button.classList.remove('active');

        return;

    }

    rooms.classList.add('open');

    button.classList.add('active');
}


/* =========================================================
   LOAD ROOM SCHEDULE
========================================================= */

function loadRoomSchedule(
    roomId,
    roomElement,
    roomName,
    buildingName
) {

    document
        .querySelectorAll('.room-card')
        .forEach(function(room) {

            room.classList.remove('active');

        });

    if (roomElement) {

        roomElement.classList.add('active');

    }

    const schedulePanel =
        document.getElementById(
            'schedulePanel'
        );

    const scheduleContent =
        document.getElementById(
            'scheduleContent'
        );

    const roomTitle =
        document.getElementById(
            'scheduleRoomName'
        );

    const roomBuilding =
        document.getElementById(
            'scheduleRoomBuilding'
        );

    if (
        !schedulePanel ||
        !scheduleContent
    ) {

        console.error(
            'Schedule panel elements not found.'
        );

        return;

    }

    schedulePanel.classList.add('open');

    schedulePanel.style.display =
        'block';

    if (roomTitle) {

        roomTitle.textContent =
            roomName;

    }

    if (roomBuilding) {

        roomBuilding.textContent =
            buildingName +
            ' • Administrator Schedule';

    }

    scheduleContent.innerHTML = `

        <div class="schedule-loading">

            <div class="loading-spinner"></div>

            <div class="loading-text">
                Loading classroom schedule...
            </div>

        </div>

    `;

    fetch(
        "{{ url('/faculty/schedule/room') }}/" +
        roomId,
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

    .then(
        async function(response)
        {

            let data = null;

            try {

                data =
                    await response.json();

            }
            catch(error) {

                throw new Error(
                    'The server returned an invalid response.'
                );

            }

            console.log(
                'ROOM SCHEDULE RESPONSE:',
                data
            );

            if (!response.ok) {

                throw new Error(
                    data.error ||
                    data.message ||
                    'Unable to load room schedule.'
                );

            }

            if (!data.success) {

                throw new Error(
                    data.error ||
                    data.message ||
                    'Schedule data is unavailable.'
                );

            }

            return data;

        }
    )

    .then(
        function(data)
        {

            if (roomTitle) {

                roomTitle.textContent =
                    data.room.room_name;

            }

            if (roomBuilding) {

                roomBuilding.textContent =
                    data.room.building +
                    ' • ' +
                    data.room.department;

            }

            /* -------------------------------------------------
               RENDER TIMETABLE
            ------------------------------------------------- */

            renderSchedule(
                data.schedules || [],
                data.reservations || []
            );

            scheduleReservationExpiry(
                data.reservations || [],
                roomId,
                roomName,
                buildingName
            );

            setTimeout(
                function()
                {

                    schedulePanel.scrollIntoView({

                        behavior:
                            'smooth',

                        block:
                            'start'

                    });

                },
                100
            );

        }
    )

    .catch(
        function(error)
        {

            console.error(
                'Schedule loading error:',
                error
            );

            scheduleContent.innerHTML = `

                <div class="schedule-error">

                    <div class="schedule-error-title">
                        Unable to load schedule
                    </div>

                    <div class="schedule-error-message">
                        ${escapeHtml(
                            error.message ||
                            'Please try again.'
                        )}
                    </div>

                    <button
                        type="button"
                        class="retry-schedule-btn"
                        onclick="
                            loadRoomSchedule(
                                ${roomId},
                                document.querySelector(
                                    '[data-room-id="${roomId}"]'
                                ),
                                '${String(roomName).replace(/'/g, "\\'")}',
                                '${String(buildingName).replace(/'/g, "\\'")}'
                            )
                        "
                    >
                        Try Again
                    </button>

                </div>

            `;

        }
    );

}


/* =========================================================
   RESERVATION EXPIRY
========================================================= */

function scheduleReservationExpiry(
    reservations,
    roomId,
    roomName,
    buildingName
)
{
    reservations =
        (reservations || [])
            .filter(function(reservation) {

                return String(
                    reservation.status || ''
                ).toLowerCase() === 'reserved';

            });

    if (reservationExpiryTimer) {

        clearTimeout(
            reservationExpiryTimer
        );

        reservationExpiryTimer = null;

    }

    const now =
        new Date();

    const expirationTimes =
        (reservations || [])
            .map(function(reservation) {

                if (!reservation.end_time) {

                    return null;

                }

                const endParts =
                    String(
                        reservation.end_time
                    ).split(':');

                if (endParts.length < 2) {

                    return null;

                }

                const expiration =
                    new Date();

                expiration.setHours(
                    Number(endParts[0]),
                    Number(endParts[1]),
                    endParts[2]
                        ? Number(endParts[2])
                        : 0,
                    0
                );

                return expiration;

            })
            .filter(function(date) {

                return date &&
                       date > now;

            })
            .sort(function(a, b) {

                return a - b;

            });

    if (!expirationTimes.length) {

        return;

    }

    const millisecondsUntilExpiry =
        expirationTimes[0].getTime() -
        now.getTime();

    reservationExpiryTimer =
        setTimeout(
            function() {

                const activeRoom =
                    document.querySelector(
                        '.room-card.active'
                    );

                loadRoomSchedule(
                    roomId,
                    activeRoom,
                    roomName,
                    buildingName
                );

            },
            millisecondsUntilExpiry + 1000
        );
}


/* =========================================================
   SCHEDULE MERGE SIGNATURE
========================================================= */

/*
|--------------------------------------------------------------------------
| This checks whether two schedules are really the same.
|--------------------------------------------------------------------------
|
| If all these values are identical, the cells can be merged.
|
*/

function facultyScheduleMergeSignature(schedule)
{
    if (!schedule) {

        return '';

    }

    return [

        schedule.course_code,
        schedule.subject,
        schedule.year_level,
        schedule.major,
        schedule.program,
        schedule.instructor,
        schedule.faculty_id,
        schedule.department_id,
        schedule.color,
        schedule.subject_type

    ]
    .map(function(value) {

        return String(
            value ?? ''
        )
        .trim()
        .toLowerCase();

    })
    .join('||');
}


/* =========================================================
   BUILD MERGED SCHEDULE GROUP
========================================================= */

/*
|--------------------------------------------------------------------------
| Example:
|
| MON = same
| WED = same
| FRI = same
|
| Result:
|
| <td colspan="3">
|
|--------------------------------------------------------------------------
|
| If only MON + WED are the same:
|
| <td colspan="2">
|
|--------------------------------------------------------------------------
|
| Reservations and empty cells are NOT merged.
|--------------------------------------------------------------------------
*/

function buildFacultyScheduleGroup(items)
{
    let html = '';

    let index = 0;

    while (
        index < items.length
    ) {

        const current =
            items[index];

        const currentSchedule =
            current.schedule;

        const currentReservation =
            current.reservation;

        /*
        |--------------------------------------------------------------------------
        | Reservation
        |--------------------------------------------------------------------------
        */

        if (
            currentReservation &&
            currentReservation.reservation &&
            !currentSchedule
        ) {

            html +=
                buildScheduleCell(
                    currentSchedule,
                    currentReservation
                );

            index++;

            continue;

        }

        /*
        |--------------------------------------------------------------------------
        | Empty cell
        |--------------------------------------------------------------------------
        */

        if (!currentSchedule) {

            html +=
                buildScheduleCell(
                    null,
                    currentReservation
                );

            index++;

            continue;

        }

        /*
        |--------------------------------------------------------------------------
        | Check if schedule is occupied
        |--------------------------------------------------------------------------
        */

        const currentOccupied =
            currentSchedule &&
            (
                String(
                    currentSchedule.course_code || ''
                ).trim() !== '' ||

                String(
                    currentSchedule.subject || ''
                ).trim() !== '' ||

                String(
                    currentSchedule.instructor || ''
                ).trim() !== ''
            );

        if (!currentOccupied) {

            html +=
                buildScheduleCell(
                    currentSchedule,
                    currentReservation
                );

            index++;

            continue;

        }

        /*
        |--------------------------------------------------------------------------
        | Current signature
        |--------------------------------------------------------------------------
        */

        const signature =
            facultyScheduleMergeSignature(
                currentSchedule
            );

        /*
        |--------------------------------------------------------------------------
        | Find matching schedules
        |--------------------------------------------------------------------------
        */

        let end =
            index + 1;

        while (
            end < items.length
        ) {

            const next =
                items[end];

            /*
            |--------------------------------------------------------------------------
            | Do not merge over reservations
            |--------------------------------------------------------------------------
            */

            if (
                next.reservation &&
                next.reservation.reservation
            ) {

                break;

            }

            /*
            |--------------------------------------------------------------------------
            | Do not merge empty cells
            |--------------------------------------------------------------------------
            */

            if (!next.schedule) {

                break;

            }

            const nextOccupied =
                next.schedule &&
                (
                    String(
                        next.schedule.course_code || ''
                    ).trim() !== '' ||

                    String(
                        next.schedule.subject || ''
                    ).trim() !== '' ||

                    String(
                        next.schedule.instructor || ''
                    ).trim() !== ''
                );

            if (!nextOccupied) {

                break;

            }

            /*
            |--------------------------------------------------------------------------
            | Compare schedules
            |--------------------------------------------------------------------------
            */

            const nextSignature =
                facultyScheduleMergeSignature(
                    next.schedule
                );

            if (
                nextSignature !==
                signature
            ) {

                break;

            }

            end++;

        }

        /*
        |--------------------------------------------------------------------------
        | Number of merged columns
        |--------------------------------------------------------------------------
        */

        const colspan =
            end - index;

        /*
        |--------------------------------------------------------------------------
        | Build the normal schedule cell
        |--------------------------------------------------------------------------
        */

        let cellHtml =
            buildScheduleCell(
                currentSchedule,
                null
            );

        /*
        |--------------------------------------------------------------------------
        | Apply COLSPAN
        |--------------------------------------------------------------------------
        */

        if (
            colspan > 1
        ) {

            cellHtml =
                cellHtml.replace(
                    '<td>',
                    '<td colspan="' +
                    colspan +
                    '" class="merged-schedule-cell">'
                );

        }

        html +=
            cellHtml;

        /*
        |--------------------------------------------------------------------------
        | Skip cells already merged
        |--------------------------------------------------------------------------
        */

        index =
            end;

    }

    return html;
}


/* =========================================================
   RENDER SCHEDULE
========================================================= */

function renderSchedule(
    schedules,
    reservations = []
) {

    /*
    |--------------------------------------------------------------------------
    | Active reservations only
    |--------------------------------------------------------------------------
    */

    reservations =
        (reservations || [])
            .filter(function(reservation) {

                return String(
                    reservation.status || ''
                ).toLowerCase() === 'reserved';

            });

    const content =
        document.getElementById(
            'scheduleContent'
        );

    /*
    |--------------------------------------------------------------------------
    | DEFAULT TIMES
    |--------------------------------------------------------------------------
    */

    const defaultTimes = [

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

    /*
    |--------------------------------------------------------------------------
    | CREATE 9 ROWS
    |--------------------------------------------------------------------------
    */

    const rows = {};

    for (
        let position = 0;
        position < 9;
        position++
    ) {

        rows[position] = {

            leftTime:
                defaultTimes[position],

            rightTime:
                defaultTimes[position],

            MON: null,

            WED: null,

            FRI: null,

            TUE: null,

            THU: null,

            SAT: null,

            MON_RESERVATION: null,

            WED_RESERVATION: null,

            FRI_RESERVATION: null,

            TUE_RESERVATION: null,

            THU_RESERVATION: null,

            SAT_RESERVATION: null

        };

    }

    /*
    |--------------------------------------------------------------------------
    | ADD DATABASE SCHEDULES
    |--------------------------------------------------------------------------
    */

    (
        schedules || []
    ).forEach(
        function(schedule)
        {

            let position =
                Number(
                    schedule.row_position
                );

            /*
            |--------------------------------------------------------------------------
            | FALLBACK FOR OLD DATA
            |--------------------------------------------------------------------------
            */

            if (
                Number.isNaN(position) ||
                position < 0 ||
                position > 8
            ) {

                const savedTime =
                    String(
                        schedule.time ||
                        ''
                    )
                    .trim();

                position =
                    defaultTimes.indexOf(
                        savedTime
                    );

            }

            /*
            |--------------------------------------------------------------------------
            | INVALID POSITION
            |--------------------------------------------------------------------------
            */

            if (
                position < 0 ||
                position > 8
            ) {

                return;

            }

            /*
            |--------------------------------------------------------------------------
            | DAY
            |--------------------------------------------------------------------------
            */

            const day =
                String(
                    schedule.day ||
                    ''
                )
                .trim()
                .toUpperCase();

            /*
            |--------------------------------------------------------------------------
            | MONDAY
            |--------------------------------------------------------------------------
            */

            if (
                day === 'MON' ||
                day === 'MONDAY'
            ) {

                rows[position].MON =
                    schedule;

                if (
                    schedule.time
                ) {

                    rows[position].leftTime =
                        schedule.time;

                }

            }

            /*
            |--------------------------------------------------------------------------
            | WEDNESDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'WED' ||
                day === 'WEDNESDAY'
            ) {

                rows[position].WED =
                    schedule;

                if (
                    schedule.time
                ) {

                    rows[position].leftTime =
                        schedule.time;

                }

            }

            /*
            |--------------------------------------------------------------------------
            | FRIDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'FRI' ||
                day === 'FRIDAY'
            ) {

                rows[position].FRI =
                    schedule;

                if (
                    schedule.time
                ) {

                    rows[position].leftTime =
                        schedule.time;

                }

            }

            /*
            |--------------------------------------------------------------------------
            | TUESDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'TUE' ||
                day === 'TUESDAY'
            ) {

                rows[position].TUE =
                    schedule;

                if (
                    schedule.time
                ) {

                    rows[position].rightTime =
                        schedule.time;

                }

            }

            /*
            |--------------------------------------------------------------------------
            | THURSDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'THU' ||
                day === 'THURSDAY'
            ) {

                rows[position].THU =
                    schedule;

                if (
                    schedule.time &&
                    !rows[position].TUE
                ) {

                    rows[position].rightTime =
                        schedule.time;

                }

            }

            /*
            |--------------------------------------------------------------------------
            | SATURDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'SAT' ||
                day === 'SATURDAY'
            ) {

                rows[position].SAT =
                    schedule;

                if (
                    schedule.time &&
                    !rows[position].TUE &&
                    !rows[position].THU
                ) {

                    rows[position].rightTime =
                        schedule.time;

                }

            }

        }
    );

    /*
    |--------------------------------------------------------------------------
    | ADD ACTIVE ROOM RESERVATIONS
    |--------------------------------------------------------------------------
    */

    (
        reservations || []
    ).forEach(
        function(reservation)
        {

            const day =
                String(
                    reservation.day || ''
                )
                .trim()
                .toUpperCase();

            const reservationStart =
                normalizeScheduleStartTime(
                    reservation.start_time ||
                    reservation.time ||
                    ''
                );

            if (!reservationStart) {

                return;

            }

            const reservationTarget = {

                reservation:
                    reservation,

                start:
                    reservationStart

            };

            const dayKeyMap = {

                MON:
                    'MON_RESERVATION',

                WED:
                    'WED_RESERVATION',

                FRI:
                    'FRI_RESERVATION',

                TUE:
                    'TUE_RESERVATION',

                THU:
                    'THU_RESERVATION',

                SAT:
                    'SAT_RESERVATION'

            };

            const reservationKey =
                dayKeyMap[day];

            if (!reservationKey) {

                return;

            }

            let reservationPosition =
                -1;

            for (
                let position = 0;
                position < defaultTimes.length;
                position++
            ) {

                const defaultStart =
                    normalizeScheduleStartTime(
                        defaultTimes[position]
                    );

                if (
                    defaultStart ===
                    reservationStart
                ) {

                    reservationPosition =
                        position;

                    break;

                }

            }

            if (
                reservationPosition < 0
            ) {

                return;

            }

            rows[
                reservationPosition
            ][
                reservationKey
            ] =
                reservationTarget;

            rows[
                reservationPosition
            ].leftTime =
                defaultTimes[
                    reservationPosition
                ];

            rows[
                reservationPosition
            ].rightTime =
                defaultTimes[
                    reservationPosition
                ];

        }
    );

    /*
    |--------------------------------------------------------------------------
    | BUILD TABLE
    |--------------------------------------------------------------------------
    */

    let html = `

        <div class="timetable-wrapper">

            <table class="admin-timetable">

                <thead>

                    <tr>

                        <th>TIME</th>

                        <th>MON</th>

                        <th>WED</th>

                        <th>FRI</th>

                        <th>TIME</th>

                        <th>TUE</th>

                        <th>THU</th>

                        <th>SAT</th>

                    </tr>

                </thead>

                <tbody>

    `;

    /*
    |--------------------------------------------------------------------------
    | CREATE 9 ROWS
    |--------------------------------------------------------------------------
    */

    for (
        let position = 0;
        position < 9;
        position++
    ) {

        const row =
            rows[position];

        /*
        |--------------------------------------------------------------------------
        | NOON BREAK
        |--------------------------------------------------------------------------
        */

        if (
            position === 4
        ) {

            html += `

                <tr class="noon-break-row">

                    <td colspan="8">
                        NOON BREAK
                    </td>

                </tr>

            `;

        }

        /*
        |--------------------------------------------------------------------------
        | NORMAL ROW
        |--------------------------------------------------------------------------
        */

        html += `

            <tr>

                <td class="time-column">

                    ${escapeHtml(
                        row.leftTime
                    )}

                </td>


                <!-- =========================================
                     MON / WED / FRI
                ========================================== -->

                ${buildFacultyScheduleGroup([

                    {
                        schedule:
                            row.MON,

                        reservation:
                            row.MON_RESERVATION
                    },

                    {
                        schedule:
                            row.WED,

                        reservation:
                            row.WED_RESERVATION
                    },

                    {
                        schedule:
                            row.FRI,

                        reservation:
                            row.FRI_RESERVATION
                    }

                ])}


                <td class="time-column">

                    ${escapeHtml(
                        row.rightTime
                    )}

                </td>


                <!-- =========================================
                     TUE / THU / SAT
                ========================================== -->

                ${buildFacultyScheduleGroup([

                    {
                        schedule:
                            row.TUE,

                        reservation:
                            row.TUE_RESERVATION
                    },

                    {
                        schedule:
                            row.THU,

                        reservation:
                            row.THU_RESERVATION
                    },

                    {
                        schedule:
                            row.SAT,

                        reservation:
                            row.SAT_RESERVATION
                    }

                ])}

            </tr>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | CLOSE TABLE
    |--------------------------------------------------------------------------
    */

    html += `

                </tbody>

            </table>

        </div>

    `;

    content.innerHTML =
        html;

}


/* =========================================================
   NORMALIZE SCHEDULE START TIME
========================================================= */

function normalizeScheduleStartTime(value)
{
    const raw =
        String(value || '')
            .trim()
            .replace(/–/g, '-')
            .replace(/—/g, '-');

    if (!raw) {

        return '';

    }

    const start =
        raw
            .split('-')[0]
            .trim();

    const match =
        start.match(
            /^(\d{1,2}):(\d{2})/
        );

    if (!match) {

        return start.toUpperCase();

    }

    return (

        String(
            Number(match[1])
        )
        .padStart(2, '0') +

        ':' +

        match[2]

    );
}


/* =========================================================
   BUILD RESERVATION CELL
========================================================= */

function buildReservationCell(
    reservation
)
{
    const badge =
        'Temporary Reservation';

    const requester =
        reservation.user_name ||
        reservation.requested_by ||
        'Faculty';

    const purpose =
        reservation.purpose ||
        'Room Reservation';

    const time =
        reservation.time ||
        (
            (reservation.start_time || '') +
            ' - ' +
            (reservation.end_time || '')
        );

    return `

        <td>

            <div class="temporary-reservation-entry">

                <div class="reservation-badge">

                    <i class="fa-solid fa-calendar-check"></i>

                    ${escapeHtml(
                        badge
                    )}

                </div>

                <div class="reservation-course">

                    ${escapeHtml(
                        purpose
                    )}

                </div>

                <div class="reservation-purpose">

                    Reserved:
                    ${escapeHtml(
                        requester
                    )}

                </div>

                <div class="reservation-requester">

                    ${escapeHtml(
                        time
                    )}

                </div>

                <div class="reservation-expiration">

                    <i class="fa-solid fa-hourglass-half"></i>

                    Temporary until
                    ${escapeHtml(
                        reservation.end_time || ''
                    )}

                </div>

            </div>

        </td>

    `;
}


/* =========================================================
   BUILD SCHEDULE CELL
========================================================= */

function buildScheduleCell(
    schedule,
    reservation = null
)
{

    /*
    |--------------------------------------------------------------------------
    | CHECK IF SCHEDULE IS OCCUPIED
    |--------------------------------------------------------------------------
    */

    const scheduleOccupied =
        schedule &&
        (
            String(
                schedule.course_code || ''
            ).trim() !== '' ||

            String(
                schedule.subject || ''
            ).trim() !== '' ||

            String(
                schedule.instructor || ''
            ).trim() !== ''
        );

    /*
    |--------------------------------------------------------------------------
    | RESERVATION
    |--------------------------------------------------------------------------
    */

    if (
        reservation &&
        reservation.reservation &&
        !scheduleOccupied
    ) {

        return buildReservationCell(
            reservation.reservation
        );

    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY CELL
    |--------------------------------------------------------------------------
    */

    if (!schedule) {

        return `

            <td>

                <span
                    class="empty-schedule-cell"
                >
                    Empty
                </span>

            </td>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | PROGRAMS
    |--------------------------------------------------------------------------
    */

    let programs = [];

    /*
    |--------------------------------------------------------------------------
    | ORIGINAL PROGRAM
    |--------------------------------------------------------------------------
    */

    if (
        schedule.program &&
        String(
            schedule.program
        ).trim() !== ''
    ) {

        programs.push(
            String(
                schedule.program
            ).trim()
        );

    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED MERGED PROGRAMS
    |--------------------------------------------------------------------------
    */

    if (
        Array.isArray(
            schedule.merged_programs
        )
    ) {

        schedule.merged_programs
            .forEach(
                function(program)
                {

                    if (
                        program &&
                        String(
                            program
                        ).trim() !== '' &&
                        !programs.includes(
                            String(
                                program
                            ).trim()
                        )
                    ) {

                        programs.push(
                            String(
                                program
                            ).trim()
                        );

                    }

                }
            );

    }

    /*
    |--------------------------------------------------------------------------
    | PROGRAM DISPLAY
    |--------------------------------------------------------------------------
    */

    let programHtml = '';

    if (
        programs.length > 0
    ) {

        programHtml = `

            <div class="class-program">

                ${escapeHtml(
                    programs.join(' / ')
                )}

            </div>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | LEC / LAB
    |--------------------------------------------------------------------------
    */

    let typeHtml = '';

    if (
        schedule.subject_type &&
        String(
            schedule.subject_type
        ).trim() !== ''
    ) {

        typeHtml = `

            <div class="class-type">

                ${escapeHtml(
                    schedule.subject_type
                )}

            </div>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | MAJOR
    |--------------------------------------------------------------------------
    */

    let majorHtml = '';

    if (
        schedule.major &&
        String(
            schedule.major
        ).trim() !== ''
    ) {

        majorHtml = `

            <div class="class-major">

                Major:
                ${escapeHtml(
                    schedule.major
                )}

            </div>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | ROOM SWAP STATUS
    |--------------------------------------------------------------------------
    */

    let roomSwapHtml = '';

    if (
        schedule.room_swap &&
        schedule.room_swap.active
    ) {

        const originalRoom =
            schedule.room_swap.original_room_name ||
            'Original room';

        const endTime =
            schedule.room_swap.end_time ||
            '';

        roomSwapHtml = `

            <div class="room-swap-badge">

                <i class="fa-solid fa-right-left"></i>

                Room Swap Active

            </div>

            <div class="room-swap-details">

                Temporary room until
                ${escapeHtml(
                    endTime
                )}

            </div>

            <div class="room-swap-original">

                Original:
                ${escapeHtml(
                    originalRoom
                )}

            </div>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | COLOR
    |--------------------------------------------------------------------------
    */

    const background =
        schedule.color ||
        '#eaf6ef';

    /*
    |--------------------------------------------------------------------------
    | CLASS CELL
    |--------------------------------------------------------------------------
    */

    return `

        <td>

            <div
                class="class-entry"
                style="
                    background:
                        ${escapeHtml(
                            background
                        )};
                "
            >

                <div class="class-course">

                    ${escapeHtml(
                        schedule.course_code || ''
                    )}

                </div>

                <div class="class-subject">

                    ${escapeHtml(
                        schedule.subject || ''
                    )}

                </div>

                ${typeHtml}

                <div class="class-instructor">

                    ${escapeHtml(
                        schedule.instructor || ''
                    )}

                </div>

                ${programHtml}

                ${majorHtml}

                ${roomSwapHtml}

            </div>

        </td>

    `;

}


/* =========================================================
   CLOSE SCHEDULE
========================================================= */

function closeSchedule()
{

    if (reservationExpiryTimer) {

        clearTimeout(
            reservationExpiryTimer
        );

        reservationExpiryTimer =
            null;

    }

    const panel =
        document.getElementById(
            'schedulePanel'
        );

    const content =
        document.getElementById(
            'scheduleContent'
        );

    const roomTitle =
        document.getElementById(
            'scheduleRoomName'
        );

    const roomBuilding =
        document.getElementById(
            'scheduleRoomBuilding'
        );

    /*
    |--------------------------------------------------------------------------
    | CLOSE PANEL
    |--------------------------------------------------------------------------
    */

    if (panel) {

        panel.classList.remove(
            'open'
        );

        panel.style.display =
            'none';

    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE ACTIVE ROOM
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.room-card.active'
        )
        .forEach(
            function(card)
            {

                card.classList.remove(
                    'active'
                );

            }
        );

    /*
    |--------------------------------------------------------------------------
    | RESET ROOM TITLE
    |--------------------------------------------------------------------------
    */

    if (roomTitle) {

        roomTitle.textContent =
            'Classroom Schedule';

    }

    /*
    |--------------------------------------------------------------------------
    | RESET BUILDING
    |--------------------------------------------------------------------------
    */

    if (roomBuilding) {

        roomBuilding.textContent =
            'Select a room to view its schedule';

    }

    /*
    |--------------------------------------------------------------------------
    | RESET CONTENT
    |--------------------------------------------------------------------------
    */

    if (content) {

        content.innerHTML = `

            <div class="schedule-loading">

                Select a room to view its schedule.

            </div>

        `;

    }

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(value)
{
    const div =
        document.createElement('div');

    div.textContent =
        value ?? '';

    return div.innerHTML;
}

</script>

        </main>

    </div>

    

</div>

@include('footerheader.footer')