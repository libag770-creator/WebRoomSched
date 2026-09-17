@include('footerheader.header')
<div class="wrapper">

    

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content">

            <style>

                /* =========================================================
                   PAGE
                ========================================================= */

                body {
                    background: #f5f7f6;
                }

                .faculty-schedule-page {
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

.faculty-timetable {
    width: 100%;

    min-width: 1180px;

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

.faculty-timetable th,
.faculty-timetable td {
    border-right: 1px solid #dfe5e1;
    border-bottom: 1px solid #dfe5e1;

    box-sizing: border-box;
}


/* =========================================================
   TABLE HEADER
========================================================= */

.faculty-timetable th {
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

.faculty-timetable th:nth-child(1),
.faculty-timetable th:nth-child(5),
.faculty-timetable .time-column {
    width: 125px;

    min-width: 125px;

    max-width: 125px;
}

.faculty-timetable .time-column {
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

.faculty-timetable td:not(.time-column) {
    height: 82px;

    padding: 6px;

    text-align: center;

    vertical-align: middle;
}


/* =========================================================
   REMOVE EXTRA BORDERS
========================================================= */

.faculty-timetable th:last-child,
.faculty-timetable td:last-child {
    border-right: none;
}

.faculty-timetable tbody tr:last-child td {
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

    text-align: left;

    display: flex;

    flex-direction: column;

    justify-content: center;
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

                    .faculty-schedule-page {
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


            <div class="faculty-schedule-page">


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

                @if($departments->count() > 0)

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


                    <div id="scheduleContent">

                        <div class="schedule-loading">

                            Select a room to view its schedule.

                        </div>

                    </div>

                </div>

            </div>


            <script>

                /* =========================================================
                   SHOW DEPARTMENT
                ========================================================= */

                function showDepartment(departmentId)
                {

                    /*
                    |--------------------------------------------------------------------------
                    | Hide all department contents
                    |--------------------------------------------------------------------------
                    */

                    document
                        .querySelectorAll(
                            '.department-content'
                        )
                        .forEach(function(section) {

                            section.classList.remove(
                                'open'
                            );

                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Remove active cards
                    |--------------------------------------------------------------------------
                    */

                    document
                        .querySelectorAll(
                            '.department-card'
                        )
                        .forEach(function(card) {

                            card.classList.remove(
                                'active'
                            );

                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Select department card
                    |--------------------------------------------------------------------------
                    */

                    const selectedCard =
                        document.querySelector(
                            '.department-card[data-department-id="' +
                            departmentId +
                            '"]'
                        );


                    if (selectedCard) {

                        selectedCard.classList.add(
                            'active'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Show selected department
                    |--------------------------------------------------------------------------
                    */

                    const selectedDepartment =
                        document.getElementById(
                            'department-' +
                            departmentId
                        );


                    if (selectedDepartment) {

                        selectedDepartment.classList.add(
                            'open'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Hide placeholder
                    |--------------------------------------------------------------------------
                    */

                    const placeholder =
                        document.getElementById(
                            'departmentPlaceholder'
                        );


                    if (placeholder) {

                        placeholder.style.display =
                            'none';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Close previous schedule
                    |--------------------------------------------------------------------------
                    */

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
                        rooms.classList.contains(
                            'open'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Close
                    |--------------------------------------------------------------------------
                    */

                    if (isOpen) {

                        rooms.classList.remove(
                            'open'
                        );

                        button.classList.remove(
                            'active'
                        );

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Open
                    |--------------------------------------------------------------------------
                    */

                    rooms.classList.add(
                        'open'
                    );

                    button.classList.add(
                        'active'
                    );

                }


                /* =========================================================
                   LOAD ROOM SCHEDULE
                ========================================================= */

             /* =========================================================
   LOAD ROOM SCHEDULE
========================================================= */

function loadRoomSchedule(
    roomId,
    roomElement,
    roomName,
    buildingName
) {

    /* ---------------------------------------------------------
       REMOVE ACTIVE ROOM
    --------------------------------------------------------- */

    document
        .querySelectorAll('.room-card')
        .forEach(function(room) {

            room.classList.remove(
                'active'
            );

        });


    /* ---------------------------------------------------------
       ACTIVE ROOM
    --------------------------------------------------------- */

    if (roomElement) {

        roomElement.classList.add(
            'active'
        );

    }


    /* ---------------------------------------------------------
       GET ELEMENTS
    --------------------------------------------------------- */

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


    /* ---------------------------------------------------------
       CHECK ELEMENTS
    --------------------------------------------------------- */

    if (
        !schedulePanel ||
        !scheduleContent
    ) {

        console.error(
            'Schedule panel elements not found.'
        );

        return;

    }


    /* ---------------------------------------------------------
       OPEN PANEL
    --------------------------------------------------------- */

    schedulePanel.classList.add(
        'open'
    );

    schedulePanel.style.display =
        'block';


    /* ---------------------------------------------------------
       SHOW ROOM INFORMATION
    --------------------------------------------------------- */

    if (roomTitle) {

        roomTitle.textContent =
            roomName;

    }

    if (roomBuilding) {

        roomBuilding.textContent =
            buildingName +
            ' • Department Chair Schedule';

    }


    /* ---------------------------------------------------------
       SHOW LOADING
    --------------------------------------------------------- */

    scheduleContent.innerHTML = `

        <div class="schedule-loading">

            <div class="loading-spinner"></div>

            <div class="loading-text">
                Loading classroom schedule...
            </div>

        </div>

    `;


    /* ---------------------------------------------------------
       FETCH SCHEDULE
    --------------------------------------------------------- */

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


            /* -------------------------------------------------
               READ JSON
            ------------------------------------------------- */

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


            /* -------------------------------------------------
               SERVER ERROR
            ------------------------------------------------- */

            if (!response.ok) {

                throw new Error(
                    data.error ||
                    data.message ||
                    'Unable to load room schedule.'
                );

            }


            /* -------------------------------------------------
               APPLICATION ERROR
            ------------------------------------------------- */

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

            /* -------------------------------------------------
               UPDATE HEADER
            ------------------------------------------------- */

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
                data.schedules || []
            );


            /* -------------------------------------------------
               SCROLL TO PANEL
            ------------------------------------------------- */

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


            /* -------------------------------------------------
               SHOW ERROR
            ------------------------------------------------- */

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
   RENDER SCHEDULE
========================================================= */

function renderSchedule(
    schedules
) {

    const content =
        document.getElementById(
            'scheduleContent'
        );


    /* ---------------------------------------------------------
       DEFAULT TIMES
    --------------------------------------------------------- */

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


    /* ---------------------------------------------------------
       CREATE ALL 9 ROWS
    --------------------------------------------------------- */

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

            THU: null

        };

    }


    /* ---------------------------------------------------------
       ADD DATABASE SCHEDULES
    --------------------------------------------------------- */

    (
        schedules || []
    ).forEach(
        function(schedule)
        {

            let position =
                Number(
                    schedule.row_position
                );


            /* -------------------------------------------------
               FALLBACK FOR OLD DATA
            ------------------------------------------------- */

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


            /* -------------------------------------------------
               INVALID POSITION
            ------------------------------------------------- */

            if (
                position < 0 ||
                position > 8
            ) {

                return;

            }


            /* -------------------------------------------------
               DAY
            ------------------------------------------------- */

            const day =
                String(
                    schedule.day ||
                    ''
                )
                .trim()
                .toUpperCase();


            /* =================================================
               MONDAY
            ================================================= */

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


            /* =================================================
               WEDNESDAY
            ================================================= */

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


            /* =================================================
               FRIDAY
            ================================================= */

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


            /* =================================================
               TUESDAY
            ================================================= */

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


            /* =================================================
               THURSDAY
            ================================================= */

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

        }
    );


    /* ---------------------------------------------------------
       BUILD TABLE
    --------------------------------------------------------- */

    let html = `

        <div class="timetable-wrapper">

            <table class="faculty-timetable">

                <thead>

                    <tr>

                        <th>TIME</th>

                        <th>MON</th>

                        <th>WED</th>

                        <th>FRI</th>

                        <th>TIME</th>

                        <th>TUE</th>

                        <th>THU</th>

                    </tr>

                </thead>

                <tbody>

    `;


    /* ---------------------------------------------------------
       CREATE 9 ROWS
    --------------------------------------------------------- */

    for (
        let position = 0;
        position < 9;
        position++
    ) {

        const row =
            rows[position];


        /* -----------------------------------------------------
           NOON BREAK
        ----------------------------------------------------- */

        if (
            position === 4
        ) {

            html += `

                <tr class="noon-break-row">

                    <td colspan="7">
                        NOON BREAK
                    </td>

                </tr>

            `;

        }


        /* -----------------------------------------------------
           NORMAL ROW
        ----------------------------------------------------- */

        html += `

            <tr>

                <td class="time-column">
                    ${escapeHtml(
                        row.leftTime
                    )}
                </td>


                ${buildScheduleCell(
                    row.MON
                )}


                ${buildScheduleCell(
                    row.WED
                )}


                ${buildScheduleCell(
                    row.FRI
                )}


                <td class="time-column">
                    ${escapeHtml(
                        row.rightTime
                    )}
                </td>


                ${buildScheduleCell(
                    row.TUE
                )}


                ${buildScheduleCell(
                    row.THU
                )}

            </tr>

        `;

    }


    /* ---------------------------------------------------------
       CLOSE TABLE
    --------------------------------------------------------- */

    html += `

                </tbody>

            </table>

        </div>

    `;


    content.innerHTML =
        html;

}
                /* =========================================================
                   BUILD SCHEDULE CELL
                ========================================================= */

                function buildScheduleCell(
                    schedule
                )
                {

                    /*
                    |--------------------------------------------------------------------------
                    | Empty cell
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
                    | Programs
                    |--------------------------------------------------------------------------
                    */

                    let programs = [];

/*
 * Original program
 */
if (
    schedule.program &&
    String(schedule.program).trim() !== ''
) {
    programs.push(
        String(schedule.program).trim()
    );
}

/*
 * Approved merged programs
 */
if (
    Array.isArray(schedule.merged_programs)
) {
    schedule.merged_programs.forEach(function(program) {

        if (
            program &&
            String(program).trim() !== '' &&
            !programs.includes(
                String(program).trim()
            )
        ) {
            programs.push(
                String(program).trim()
            );
        }

    });
}

/*
 * Program display
 */
let programHtml = '';

if (programs.length > 0) {
    programHtml = `
        <div class="class-program">
            ${escapeHtml(programs.join(' / '))}
        </div>
    `;
}

/*
 * LEC / LAB display
 */
let typeHtml = '';

if (
    schedule.subject_type &&
    String(schedule.subject_type).trim() !== ''
) {
    typeHtml = `
        <div class="class-type">
            ${escapeHtml(schedule.subject_type)}
        </div>
    `;
}

/*
 * Major display
 *
 * Major is optional.
 */
let majorHtml = '';

if (
    schedule.major &&
    String(schedule.major).trim() !== ''
) {
    majorHtml = `
        <div class="class-major">
            Major: ${escapeHtml(schedule.major)}
        </div>
    `;
}


                    /*
                    |--------------------------------------------------------------------------
                    | Color
                    |--------------------------------------------------------------------------
                    */

                    const background =
                        schedule.color ||
                        '#eaf6ef';


                    /*
                    |--------------------------------------------------------------------------
                    | Class cell
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
    ${escapeHtml(schedule.course_code || '')}
</div>

<div class="class-subject">
    ${escapeHtml(schedule.subject || '')}
</div>

${typeHtml}

<div class="class-instructor">
    ${escapeHtml(schedule.instructor || '')}
</div>


                                ${programHtml}

                                ${majorHtml}

                            </div>

                        </td>
                    `;

                }


                /* =========================================================
   CLOSE SCHEDULE
========================================================= */

function closeSchedule()
{

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


    /* ---------------------------------------------------------
       CLOSE PANEL
    --------------------------------------------------------- */

    if (panel) {

        panel.classList.remove(
            'open'
        );

        panel.style.display =
            'none';

    }


    /* ---------------------------------------------------------
       REMOVE ACTIVE ROOM
    --------------------------------------------------------- */

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


    /* ---------------------------------------------------------
       RESET ROOM TITLE
    --------------------------------------------------------- */

    if (roomTitle) {

        roomTitle.textContent =
            'Classroom Schedule';

    }


    /* ---------------------------------------------------------
       RESET BUILDING
    --------------------------------------------------------- */

    if (roomBuilding) {

        roomBuilding.textContent =
            'Select a room to view its schedule';

    }


    /* ---------------------------------------------------------
       RESET CONTENT
    --------------------------------------------------------- */

    if (content) {

        content.innerHTML = `

            <div class="schedule-loading">

                Select a room to view its schedule.

            </div>

        `;

    }

}
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