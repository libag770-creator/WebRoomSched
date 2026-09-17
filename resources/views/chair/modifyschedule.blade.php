 @include('footerheader.header')
<div class="wrapper">

   

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content">

            <style>

                * {
                    box-sizing: border-box;
                }

                body {
                    background: #f5f5f5;
                }

                .page {
                    max-width: 1200px;
                    margin: auto;
                }

                .title {
                    font-size: 28px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 5px;
                }

                .subtitle {
                    color: #777;
                    margin-bottom: 25px;
                }

                .section {
                    background: white;
                    padding: 25px;
                    border-radius: 12px;
                    box-shadow: 0 2px 10px rgba(0,0,0,.07);
                    margin-bottom: 20px;
                }

                .filters {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 15px;
                }

                .filter-group label {
                    display: block;
                    font-size: 13px;
                    font-weight: bold;
                    color: #555;
                    margin-bottom: 6px;
                }

                .filter-group select {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    background: white;
                }

                .schedule-title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 15px;
                }

                .room-info {
                    background: #e8f5e9;
                    padding: 12px 15px;
                    border-radius: 7px;
                    margin-bottom: 20px;
                    color: #2e7d32;
                }

                .schedule-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .schedule-table th {
                    background: #2e7d32;
                    color: white;
                    padding: 11px;
                    text-align: center;
                    font-size: 13px;
                }

                .schedule-table td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                    min-width: 130px;
                    height: 90px;
                    vertical-align: middle;
                }

                .time-cell {
                    background: #f5f5f5;
                    font-weight: bold;
                    color: #555;
                    width: 130px;
                }

                .schedule-cell {
                    background: white;
                }

                .filled {
                    background: #e8f5e9 !important;
                    color: #333;
                }

                .locked {
                    display: inline-block;
                    margin-top: 5px;
                    font-size: 11px;
                    color: #777;
                }

                .empty-slot {
                    background: #fafafa;
                    color: #aaa;
                }

                .add-button {
                    display: inline-block;
                    margin-top: 6px;
                    padding: 6px 10px;
                    border: none;
                    border-radius: 5px;
                    background: #2e7d32;
                    color: white;
                    font-size: 11px;
                    font-weight: bold;
                    cursor: pointer;
                }

                .add-button:hover {
                    background: #1b5e20;
                }

                .not-allowed {
                    display: inline-block;
                    margin-top: 5px;
                    font-size: 11px;
                    color: #999;
                }

                .schedule-course {
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 4px;
                }

                .schedule-subject {
                    font-size: 12px;
                    margin-bottom: 3px;
                }

                .schedule-instructor {
                    font-size: 11px;
                    color: #777;
                }

                .schedule-year {
                    font-size: 11px;
                    color: #777;
                }

                .message {
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }

                .success {
                    background: #e8f5e9;
                    color: #2e7d32;
                }

                .error {
                    background: #ffebee;
                    color: #b71c1c;
                }

                .permission-info {
                    padding: 10px 14px;
                    background: #fff8e1;
                    border-left: 4px solid #f9a825;
                    border-radius: 5px;
                    color: #6d4c00;
                    font-size: 13px;
                    margin-bottom: 20px;
                }

                .empty {
                    padding: 30px;
                    text-align: center;
                    color: #777;
                }

                /* =========================================================
   PROFESSIONAL CLASSROOM NAVIGATION
========================================================= */

.navigation-section {
    background: #ffffff;

    border: 1px solid #e3e8e5;

    border-radius: 14px;

    padding: 25px;

    margin-bottom: 20px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.04);
}

.navigation-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding-bottom: 20px;

    border-bottom: 1px solid #edf0ee;

    margin-bottom: 20px;
}

.navigation-title {
    display: flex;
    align-items: center;
    gap: 9px;

    color: #1f2937;

    font-size: 18px;

    font-weight: 700;
}

.navigation-title i {
    color: #2e7d32;
}

.navigation-subtitle {
    margin-top: 5px;

    color: #667085;

    font-size: 12px;
}


/* =========================================================
   LEVEL TITLE
========================================================= */

.level-title {
    display: flex;
    align-items: center;
    gap: 8px;

    color: #344054;

    font-size: 14px;

    font-weight: 700;

    margin-bottom: 12px;
}

.level-title i {
    color: #2e7d32;
}


/* =========================================================
   DEPARTMENT CARDS
========================================================= */

.department-list,
.building-list {
    display: flex;
    flex-direction: column;

    gap: 10px;
}

.department-card,
.building-card {
    width: 100%;

    border: 1px solid #e1e7e3;

    background: #ffffff;

    border-radius: 10px;

    padding: 15px 17px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    text-align: left;

    cursor: pointer;

    transition:
        border-color .18s ease,
        background .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.department-card:hover,
.building-card:hover {
    background: #f7fbf8;

    border-color: #b8d8bd;

    transform: translateY(-1px);

    box-shadow:
        0 4px 10px rgba(0,0,0,.05);
}

.card-left {
    display: flex;

    align-items: center;

    gap: 13px;
}

.card-icon {
    width: 43px;
    height: 43px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #eaf6ed;

    color: #2e7d32;

    font-size: 17px;
}

.card-name {
    color: #1f2937;

    font-size: 14px;

    font-weight: 700;
}

.card-description {
    color: #98a2b3;

    font-size: 11px;

    margin-top: 3px;
}

.card-count {
    color: #667085;

    font-size: 11px;

    margin-top: 3px;
}

.card-arrow {
    width: 31px;
    height: 31px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 7px;

    color: #667085;

    background: #f6f8f7;

    transition:
        background .18s ease,
        color .18s ease;
}

.department-card:hover .card-arrow,
.building-card:hover .card-arrow {
    background: #e8f5e9;

    color: #2e7d32;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.breadcrumb {
    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 18px;

    font-size: 12px;

    color: #98a2b3;
}

.breadcrumb strong {
    color: #344054;
}

.back-button {
    border: none;

    background: transparent;

    color: #2e7d32;

    font-size: 12px;

    font-weight: 700;

    cursor: pointer;

    display: flex;

    align-items: center;

    gap: 6px;

    padding: 0;
}

.back-button:hover {
    color: #1b5e20;
}


/* =========================================================
   ROOM CARDS
========================================================= */

.room-selection-grid {
    display: grid;

    grid-template-columns:
        repeat(auto-fill, minmax(220px, 1fr));

    gap: 12px;
}

.room-selection-card {
    background: #ffffff;

    border: 1px solid #e1e7e3;

    border-radius: 10px;

    padding: 17px;

    cursor: pointer;

    transition:
        transform .18s ease,
        border-color .18s ease,
        box-shadow .18s ease;
}

.room-selection-card:hover {
    transform: translateY(-2px);

    border-color: #9fcaa6;

    box-shadow:
        0 6px 15px rgba(0,0,0,.06);
}

.room-selection-top {
    display: flex;

    align-items: center;

    gap: 11px;

    margin-bottom: 14px;
}

.room-selection-icon {
    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #eaf6ed;

    color: #2e7d32;

    border-radius: 8px;
}

.room-selection-name {
    color: #1f2937;

    font-size: 14px;

    font-weight: 700;
}

.room-selection-label {
    color: #98a2b3;

    font-size: 10px;

    margin-top: 2px;
}

.open-room-button {
    width: 100%;

    border: 1px solid #2e7d32;

    background: #2e7d32;

    color: #ffffff;

    padding: 9px;

    border-radius: 7px;

    font-size: 11px;

    font-weight: 700;

    cursor: pointer;
}

.open-room-button:hover {
    background: #256b2a;
}


/* =========================================================
   SCHEDULE SECTION
========================================================= */

.schedule-section {
    background: #ffffff;

    border: 1px solid #e1e7e3;

    border-radius: 14px;

    padding: 20px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.04);
}

.schedule-header {
    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 15px;
}

.schedule-main-title {
    display: flex;

    align-items: center;

    gap: 8px;

    color: #1f2937;

    font-size: 18px;

    font-weight: 700;
}

.schedule-main-title i {
    color: #2e7d32;
}

.schedule-room-name {
    color: #667085;

    font-size: 12px;

    margin-top: 4px;
}

.change-room-button {
    border: 1px solid #d0d5d2;

    background: #ffffff;

    color: #475467;

    padding: 9px 13px;

    border-radius: 7px;

    cursor: pointer;

    font-size: 11px;

    font-weight: 700;
}

.change-room-button:hover {
    border-color: #2e7d32;

    color: #2e7d32;

    background: #f8fbf9;
}


/* =========================================================
   ROOM INFO
========================================================= */

.room-info {
    background: #f0f8f2;

    border: 1px solid #d8eadb;

    padding: 12px 15px;

    border-radius: 8px;

    margin-bottom: 12px;

    color: #176b3a;

    font-size: 12px;
}


/* =========================================================
   PERMISSION INFO
========================================================= */

.permission-info {
    display: flex;

    align-items: center;

    gap: 8px;

    padding: 10px 13px;

    background: #fff9e8;

    border: 1px solid #f1dfad;

    border-radius: 7px;

    color: #765b13;

    font-size: 11px;

    margin-bottom: 15px;
}

.permission-info i {
    color: #d99a00;
}


/* =========================================================
   EXCEL TABLE
========================================================= */

.excel-wrapper {
    width: 100%;

    overflow-x: auto;

    border: 1px solid #dfe5e1;

    border-radius: 8px;
}

.excel-schedule {
    width: 100%;

    min-width: 850px;

    border-collapse: collapse;

    table-layout: fixed;

    background: #ffffff;
}

.excel-schedule th {
    background: #2e7d32;

    color: #ffffff;

    border: 1px solid #276b2b;

    height: 42px;

    padding: 8px;

    text-align: center;

    font-size: 11px;

    font-weight: 700;

    letter-spacing: .3px;
}

.excel-schedule th.time-header {
    width: 105px;
}

.excel-schedule td {
    border: 1px solid #dfe5e1;

    height: 72px;

    padding: 6px;

    text-align: center;

    vertical-align: middle;

    font-size: 11px;
}

.excel-schedule td.time-cell {
    background: #f7f9f8;

    color: #475467;

    font-weight: 700;

    font-size: 10px;
}

.excel-schedule td.schedule-cell {
    background: #ffffff;

    transition:
        background .15s ease;
}

.excel-schedule td.schedule-cell:hover {
    background: #f7fbf8;
}


/* =========================================================
   FILLED SCHEDULE
========================================================= */

.excel-schedule td.filled {
    background: #eaf6ed !important;

    color: #1f2937;
}

.schedule-course {
    color: #176b3a;

    font-weight: 800;

    font-size: 11px;

    margin-bottom: 3px;
}

.schedule-subject {
    color: #344054;

    font-size: 10px;

    line-height: 1.25;

    margin-bottom: 3px;
}

.schedule-instructor {
    color: #667085;

    font-size: 9px;

    line-height: 1.2;
}

.schedule-year {
    color: #667085;

    font-size: 9px;

    margin-top: 2px;
}

.editable-label {
    display: block;

    color: #2e7d32;

    font-size: 8px;

    font-weight: 700;

    margin-top: 4px;
}

.locked {
    display: block;

    color: #98a2b3;

    font-size: 9px;

    margin-top: 4px;
}


/* =========================================================
   EMPTY SLOT
========================================================= */

.excel-schedule td.empty-slot {
    background: #fafcfb;

    color: #98a2b3;
}

.excel-schedule td.allowed-other {
    background: #f7fbf8;
}

.add-button {
    margin-top: 5px;

    padding: 5px 8px;

    border: none;

    border-radius: 5px;

    background: #2e7d32;

    color: #ffffff;

    font-size: 9px;

    font-weight: 700;

    cursor: pointer;
}

.add-button:hover {
    background: #1b5e20;
}

.not-allowed {
    display: inline-block;

    margin-top: 5px;

    color: #98a2b3;

    font-size: 9px;
}


/* =========================================================
   NOON BREAK
========================================================= */

.excel-schedule .noon-break-row td {
    height: 34px;

    background: #f7f9f8;

    color: #667085;

    font-size: 10px;

    font-weight: 800;

    letter-spacing: 2px;

    text-align: center;

    border-top: 1px solid #dfe5e1;

    border-bottom: 1px solid #dfe5e1;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 700px) {

    .navigation-section,
    .schedule-section {
        padding: 15px;
    }

    .schedule-header {
        align-items: flex-start;

        gap: 10px;
    }

    .schedule-main-title {
        font-size: 16px;
    }

    .room-selection-grid {
        grid-template-columns: 1fr;
    }

}

                /* MODAL */

                .modal {
                    display: none;
                    position: fixed;
                    z-index: 9999;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,.5);
                }

                .modal-content {
                    background: white;
                    width: 450px;
                    max-width: 90%;
                    margin: 7% auto;
                    padding: 25px;
                    border-radius: 10px;
                    position: relative;
                }

                .modal-title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 20px;
                }

                .close {
                    position: absolute;
                    right: 18px;
                    top: 12px;
                    font-size: 25px;
                    cursor: pointer;
                    color: #777;
                }

                .form-group {
                    margin-bottom: 14px;
                }

                .form-group label {
                    display: block;
                    font-size: 13px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .form-group input,
                .form-group select {
                    width: 100%;
                    padding: 9px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                }

                .save-button {
                    width: 100%;
                    padding: 11px;
                    border: none;
                    border-radius: 6px;
                    background: #2e7d32;
                    color: white;
                    font-weight: bold;
                    cursor: pointer;
                }

                .save-button:hover {
                    background: #1b5e20;
                }

                
                @media(max-width: 800px) {

                    .filters {
                        grid-template-columns: 1fr;
                    }

                    .schedule-table {
                        font-size: 11px;
                    }

                    .schedule-table td {
                        min-width: 100px;
                    }

                }

            </style>


            <div class="page">

                <div class="title">
                    Modify Schedule
                </div>

                <div class="subtitle">
                    View schedules from all departments and add
                    schedules to available empty slots.
                </div>


                {{-- SUCCESS --}}

                @if(session('success'))

                    <div class="message success">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ERROR --}}

                @if(session('error'))

                    <div class="message error">
                        {{ session('error') }}
                    </div>

                @endif

{{-- =====================================================
     DEPARTMENT → BUILDING → ROOM
====================================================== --}}

<div class="navigation-section">


    {{-- =================================================
         NAVIGATION HEADER
    ================================================== --}}

    <div class="navigation-header">

        <div>

            <div class="navigation-title">

                <i class="fa-solid fa-location-dot"></i>

                Select Classroom

            </div>

            <div class="navigation-subtitle">

                Select a department, building, and room
                to view its schedule.

            </div>

        </div>

    </div>


    {{-- =================================================
         DEPARTMENT LEVEL
    ================================================== --}}

    <div id="departmentLevel">

        <div class="level-title">

            <i class="fa-solid fa-building-columns"></i>

            Departments

        </div>


        <div class="department-list">

            @foreach($departments as $department)

                <button
                    type="button"
                    class="department-card"
                    onclick="selectDepartment(
                        {{ $department->id }}
                    )"
                >

                    <div class="card-left">

                        <div class="card-icon department-icon">

                            <i class="fa-solid fa-building-columns"></i>

                        </div>

                        <div>

                            <div class="card-name">

                                {{ $department->name }}

                            </div>

                            <div class="card-description">

                                Department

                            </div>

                        </div>

                    </div>


                    <div class="card-arrow">

                        <i class="fa-solid fa-chevron-right"></i>

                    </div>

                </button>

            @endforeach

        </div>

    </div>


    {{-- =================================================
         BUILDING LEVEL
    ================================================== --}}

    <div
        id="buildingLevel"
        style="display:none;"
    >

        <div class="breadcrumb">

            <button
                type="button"
                onclick="backToDepartments()"
                class="back-button"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Departments

            </button>

            <span>
                /
            </span>

            <strong id="selectedDepartmentName">
                Department
            </strong>

        </div>


        <div class="level-title">

            <i class="fa-solid fa-building"></i>

            Buildings

        </div>


        <div
            id="buildingList"
            class="building-list"
        >
        </div>

    </div>


    {{-- =================================================
         ROOM LEVEL
    ================================================== --}}

    <div
        id="roomLevel"
        style="display:none;"
    >

        <div class="breadcrumb">

            <button
                type="button"
                onclick="backToBuildings()"
                class="back-button"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Buildings

            </button>

            <span>
                /
            </span>

            <strong id="selectedBuildingName">
                Building
            </strong>

        </div>


        <div class="level-title">

            <i class="fa-solid fa-door-open"></i>

            Rooms

        </div>


        <div
            id="roomList"
            class="room-selection-grid"
        >
        </div>

    </div>

</div>


               {{-- =====================================================
     ROOM SCHEDULE
====================================================== --}}

<div
    class="schedule-section"
    id="scheduleSection"
    style="display:none;"
>


    {{-- =================================================
         SCHEDULE HEADER
    ================================================== --}}

    <div class="schedule-header">

        <div>

            <div class="schedule-main-title">

                <i class="fa-solid fa-calendar-days"></i>

                Room Schedule

            </div>

            <div
                class="schedule-room-name"
                id="scheduleRoomName"
            >
                Room
            </div>

        </div>


        <button
            type="button"
            class="change-room-button"
            onclick="backToRooms()"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Change Room

        </button>

    </div>


    <div
        class="room-info"
        id="roomInfo"
    >
    </div>


    <div class="permission-info">

        <i class="fa-solid fa-circle-info"></i>

        <span>
            Filled schedules are locked.
            Empty slots can be used when permission
            is available.
        </span>

    </div>


    {{-- =================================================
         EXCEL STYLE TABLE
    ================================================== --}}

    <div class="excel-wrapper">

        <table class="excel-schedule">

            <thead>

                <tr>

                    <th class="time-header">
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

                    <th class="time-header">
                        TIME
                    </th>

                    <th>
                        TUE
                    </th>

                    <th>
                        THU
                    </th>

                </tr>

            </thead>

            <tbody id="scheduleBody">

            </tbody>

        </table>

    </div>

</div>

{{-- =========================================================
     ADD SCHEDULE MODAL
========================================================== --}}

<div
    id="addScheduleModal"
    class="modal"
>

    <div class="modal-content">

        <span
            class="close"
            onclick="closeModal()"
        >
            &times;
        </span>


        <div class="modal-title">
            Add Schedule
        </div>


        <form
            method="POST"
            action="{{ route('chair.modify.schedule.store') }}"
        >

            @csrf


            {{-- ROOM --}}

            <input
                type="hidden"
                name="room_id"
                id="formRoomId"
            >


            {{-- DAY --}}

            <input
                type="hidden"
                name="day"
                id="formDay"
            >


            {{-- TIME --}}

            <input
                type="hidden"
                name="time"
                id="formTime"
            >


            {{-- SEMESTER --}}

            <div class="form-group">

                <label for="modifySemester">
                    Semester
                </label>

                <input
                    type="text"
                    name="semester"
                    id="modifySemester"
                    value="{{ $semester ?? '' }}"
                    placeholder="Example: 1st Semester"
                    required
                >

            </div>


            {{-- ACADEMIC YEAR --}}

            <div class="form-group">

                <label for="modifyAcademicYear">
                    Academic Year
                </label>

                <input
                    type="text"
                    name="academic_year"
                    id="modifyAcademicYear"
                    value="{{ $academicYear ?? '' }}"
                    placeholder="Example: 2026-2027"
                    required
                >

            </div>


            {{-- DEPARTMENT --}}

            <div class="form-group">

                <label>
                    Adding Department
                </label>

                <input
                    type="text"
                    value="{{ auth()->user()->department->name ?? 'Your Department' }}"
                    readonly
                >

            </div>


            {{-- COURSE CODE --}}

            <div class="form-group">

                <label for="modifyCourseCode">
                    Course Code
                </label>

                <input
                    type="text"
                    name="course_code"
                    id="modifyCourseCode"
                    placeholder="Example: IT 111"
                    required
                >

            </div>


            {{-- SUBJECT TYPE --}}

            <div class="form-group">

                <label for="modifySubjectType">
                    Subject Type
                </label>

                <select
                    name="subject_type"
                    id="modifySubjectType"
                >

                    <option value="">
                        Optional
                    </option>

                    <option value="Major">
                        Major
                    </option>

                    <option value="Non-major">
                        Non-major
                    </option>

                </select>

            </div>


            {{-- DESCRIPTION --}}

            <div class="form-group">

                <label for="modifyDescription">
                    Description
                </label>

                <input
                    type="text"
                    name="description"
                    id="modifyDescription"
                    placeholder="Optional"
                >

            </div>


            {{-- COLOR --}}

            <div class="form-group">

                <label for="modifyColor">
                    Color
                </label>

                <input
                    type="color"
                    name="color"
                    id="modifyColor"
                    value="#ffffff"
                >

            </div>


            {{-- SUBMIT --}}

            <button
                type="submit"
                class="save-button"
            >
                Add Schedule
            </button>

        </form>

    </div>

</div>
            <script>

                /* =========================================================
   DATA
========================================================= */

const departments =
    @json($departments);

let selectedDepartment = null;
let selectedBuilding = null;
let selectedRoom = null;


/* =========================================================
   SELECT DEPARTMENT
========================================================= */

function selectDepartment(departmentId)
{
    selectedDepartment =
        departments.find(
            department =>
                Number(department.id) ===
                Number(departmentId)
        );

    if (!selectedDepartment) {
        return;
    }

    document.getElementById(
        'selectedDepartmentName'
    ).textContent =
        selectedDepartment.name;


    const buildingList =
        document.getElementById(
            'buildingList'
        );

    buildingList.innerHTML = '';


    const buildings =
        selectedDepartment.buildings || [];


    if (buildings.length === 0) {

        buildingList.innerHTML = `
            <div class="empty">
                No buildings are assigned
                to this department.
            </div>
        `;

    } else {

        buildings.forEach(
            function(building)
            {

                const rooms =
                    building.rooms || [];

                const card =
                    document.createElement('button');

                card.type = 'button';

                card.className =
                    'building-card';

                card.onclick =
                    function()
                    {
                        selectBuilding(
                            building.id
                        );
                    };


                card.innerHTML = `

                    <div class="card-left">

                        <div class="card-icon">

                            <i class="fa-solid fa-building"></i>

                        </div>

                        <div>

                            <div class="card-name">

                                ${escapeHtml(
                                    building.name
                                )}

                            </div>

                            <div class="card-count">

                                ${rooms.length}
                                ${rooms.length === 1
                                    ? 'room'
                                    : 'rooms'}

                            </div>

                        </div>

                    </div>


                    <div class="card-arrow">

                        <i class="fa-solid fa-chevron-right"></i>

                    </div>

                `;


                buildingList.appendChild(card);

            }
        );

    }


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'block';


    document.getElementById(
        'roomLevel'
    ).style.display = 'none';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';
}


/* =========================================================
   SELECT BUILDING
========================================================= */

function selectBuilding(buildingId)
{
    if (!selectedDepartment) {
        return;
    }


    selectedBuilding =
        (selectedDepartment.buildings || [])
            .find(
                building =>
                    Number(building.id) ===
                    Number(buildingId)
            );


    if (!selectedBuilding) {
        return;
    }


    document.getElementById(
        'selectedBuildingName'
    ).textContent =
        selectedBuilding.name;


    const roomList =
        document.getElementById(
            'roomList'
        );

    roomList.innerHTML = '';


    const rooms =
        selectedBuilding.rooms || [];


    if (rooms.length === 0) {

        roomList.innerHTML = `
            <div class="empty">
                No rooms are assigned
                to this building.
            </div>
        `;

    } else {

        rooms.forEach(
            function(room)
            {

                const card =
                    document.createElement('div');

                card.className =
                    'room-selection-card';


                card.innerHTML = `

                    <div class="room-selection-top">

                        <div class="room-selection-icon">

                            <i class="fa-solid fa-door-open"></i>

                        </div>

                        <div>

                            <div class="room-selection-name">

                                ${escapeHtml(
                                    room.room_name
                                )}

                            </div>

                            <div class="room-selection-label">

                                Classroom

                            </div>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="open-room-button"
                        onclick="openRoom(
                            ${room.id}
                        )"
                    >

                        <i class="fa-solid fa-calendar-days"></i>

                        Open Schedule

                    </button>

                `;


                roomList.appendChild(card);

            }
        );

    }


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'none';


    document.getElementById(
        'roomLevel'
    ).style.display = 'block';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';
}


/* =========================================================
   OPEN ROOM
========================================================= */

function openRoom(roomId)
{
    if (!selectedBuilding) {
        return;
    }


    selectedRoom =
        (selectedBuilding.rooms || [])
            .find(
                room =>
                    Number(room.id) ===
                    Number(roomId)
            );


    if (!selectedRoom) {
        return;
    }


    document.getElementById(
        'scheduleRoomName'
    ).textContent =
        selectedRoom.room_name;


    document.getElementById(
        'roomInfo'
    ).innerHTML = `

        <strong>
            ${escapeHtml(
                selectedDepartment.name
            )}
        </strong>

        &nbsp; / &nbsp;

        ${escapeHtml(
            selectedBuilding.name
        )}

        &nbsp; / &nbsp;

        <strong>
            ${escapeHtml(
                selectedRoom.room_name
            )}
        </strong>

    `;


    buildExcelSchedule(
        selectedRoom
    );


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'none';


    document.getElementById(
        'roomLevel'
    ).style.display = 'none';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'block';
}


/* =========================================================
   BACK TO DEPARTMENTS
========================================================= */

function backToDepartments()
{
    selectedDepartment = null;

    selectedBuilding = null;

    selectedRoom = null;


    document.getElementById(
        'departmentLevel'
    ).style.display = 'block';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'none';


    document.getElementById(
        'roomLevel'
    ).style.display = 'none';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';
}


/* =========================================================
   BACK TO BUILDINGS
========================================================= */

function backToBuildings()
{
    selectedBuilding = null;

    selectedRoom = null;


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'block';


    document.getElementById(
        'roomLevel'
    ).style.display = 'none';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';
}


/* =========================================================
   BACK TO ROOMS
========================================================= */

function backToRooms()
{
    selectedRoom = null;


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'none';


    document.getElementById(
        'roomLevel'
    ).style.display = 'block';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';
}


/* =========================================================
   EXCEL-STYLE SCHEDULE
========================================================= */

function buildExcelSchedule(room)
{
    const schedules =
        room.schedules || [];


    const currentDepartmentId =
        Number(
            window.chairDepartmentId
        );


    const roomDepartmentId =
        Number(
            room.department_id
        );


    const allowOtherDepartments =
        Number(
            room.allow_other_departments
        ) === 1;


    /*
     * Keep the same basic time structure
     * as Set Schedule.
     */

    const times = [

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


    const leftDays = [
        'MON',
        'WED',
        'FRI'
    ];


    const rightDays = [
        'TUE',
        'THU'
    ];


    let html = '';


    times.forEach(
        function(time, index)
        {

            /*
             * NOON BREAK
             */

            if (index === 4) {

                html += `

                    <tr class="noon-break-row">

                        <td colspan="7">

                            N O O N &nbsp; B R E A K

                        </td>

                    </tr>

                `;

            }


            html += '<tr>';


            /*
             * LEFT TIME
             */

            html += `

                <td class="time-cell">

                    ${time}

                </td>

            `;


            /*
             * MON / WED / FRI
             */

            leftDays.forEach(
                function(day)
                {

                    html += buildModifyCell(
                        schedules,
                        day,
                        time,
                        room,
                        currentDepartmentId,
                        roomDepartmentId,
                        allowOtherDepartments
                    );

                }
            );


            /*
             * RIGHT TIME
             */

            html += `

                <td class="time-cell">

                    ${time}

                </td>

            `;


            /*
             * TUE / THU
             */

            rightDays.forEach(
                function(day)
                {

                    html += buildModifyCell(
                        schedules,
                        day,
                        time,
                        room,
                        currentDepartmentId,
                        roomDepartmentId,
                        allowOtherDepartments
                    );

                }
            );


            html += '</tr>';

        }
    );


    document.getElementById(
        'scheduleBody'
    ).innerHTML = html;
}


/* =========================================================
   BUILD INDIVIDUAL CELL
========================================================= */

function buildModifyCell(
    schedules,
    day,
    time,
    room,
    currentDepartmentId,
    roomDepartmentId,
    allowOtherDepartments
)
{
    const schedule =
        schedules.find(
            function(item)
            {

                return (
                    String(
                        item.day || ''
                    )
                    .trim()
                    .toUpperCase() === day &&

                    String(
                        item.time || ''
                    )
                    .trim() === time
                );

            }
        );


    /*
     * EXISTING SCHEDULE
     */

    if (schedule) {

        const scheduleDepartmentId =
            Number(
                schedule.department_id
            );


        const isOwnDepartment =
            scheduleDepartmentId ===
            currentDepartmentId;


        /*
         * OWN DEPARTMENT
         */

        if (isOwnDepartment) {

            return `

                <td
                    class="schedule-cell filled"
                    onclick="openAddModal(
                        ${room.id},
                        '${day}',
                        '${time}'
                    )"
                >

                    <div class="schedule-course">

                        ${escapeHtml(
                            schedule.course_code || ''
                        )}

                    </div>

                    <div class="schedule-subject">

                        ${escapeHtml(
                            schedule.subject || ''
                        )}

                    </div>

                    <div class="schedule-instructor">

                        ${escapeHtml(
                            schedule.instructor || ''
                        )}

                    </div>

                    <div class="schedule-year">

                        ${escapeHtml(
                            schedule.year_level || ''
                        )}

                    </div>

                    ${
                        schedule.subject_type
                        ? `
                            <div class="schedule-year">
                                ${escapeHtml(
                                    schedule.subject_type
                                )}
                            </div>
                        `
                        : ''
                    }

                    <span class="editable-label">

                        ✏️ Your Schedule

                    </span>

                </td>

            `;

        }


        /*
         * OTHER DEPARTMENT
         */

        return `

            <td class="schedule-cell filled locked-cell">

                <div class="schedule-course">

                    ${escapeHtml(
                        schedule.course_code || ''
                    )}

                </div>

                <div class="schedule-subject">

                    ${escapeHtml(
                        schedule.subject || ''
                    )}

                </div>

                <div class="schedule-instructor">

                    ${escapeHtml(
                        schedule.instructor || ''
                    )}

                </div>

                <div class="schedule-year">

                    ${escapeHtml(
                        schedule.year_level || ''
                    )}

                </div>

                ${
                    schedule.subject_type
                    ? `
                        <div class="schedule-year">
                            ${escapeHtml(
                                schedule.subject_type
                            )}
                        </div>
                    `
                    : ''
                }

                <span class="locked">

                    🔒 Locked

                </span>

            </td>

        `;

    }


    /*
     * EMPTY OWN-DEPARTMENT ROOM
     */

    if (
        roomDepartmentId ===
        currentDepartmentId
    ) {

        return `

            <td
                class="schedule-cell empty-slot"
                onclick="openAddModal(
                    ${room.id},
                    '${day}',
                    '${time}'
                )"
            >

                Empty

                <br>

                <button
                    type="button"
                    class="add-button"
                    onclick="event.stopPropagation(); openAddModal(
                        ${room.id},
                        '${day}',
                        '${time}'
                    )"
                >

                    + Add Schedule

                </button>

            </td>

        `;

    }


    /*
     * EMPTY OTHER-DEPARTMENT ROOM
     * WITH PERMISSION
     */

    if (allowOtherDepartments) {

        return `

            <td
                class="schedule-cell empty-slot allowed-other"
                onclick="openAddModal(
                    ${room.id},
                    '${day}',
                    '${time}'
                )"
            >

                Empty

                <br>

                <button
                    type="button"
                    class="add-button"
                    onclick="event.stopPropagation(); openAddModal(
                        ${room.id},
                        '${day}',
                        '${time}'
                    )"
                >

                    + Add Schedule

                </button>

                <br>

                <span style="
                    font-size:9px;
                    color:#2e7d32;
                ">

                    Allowed by room owner

                </span>

            </td>

        `;

    }


    /*
     * EMPTY OTHER-DEPARTMENT ROOM
     * WITHOUT PERMISSION
     */

    return `

        <td class="schedule-cell empty-slot locked-cell">

            Empty

            <br>

            <span class="not-allowed">

                🔒 Not allowed

            </span>

        </td>

    `;
}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(value)
{
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


/* =========================================================
   CHAIR DEPARTMENT
========================================================= */

window.chairDepartmentId =
    {{ auth()->user()->department_id }};

            

                /*
                |--------------------------------------------------------------------------
                | OPEN MODAL
                |--------------------------------------------------------------------------
                */

                function openAddModal(
                    roomId,
                    day,
                    time
                )
                {

                    document.getElementById(
                        'formRoomId'
                    ).value = roomId;


                    document.getElementById(
                        'formDay'
                    ).value = day;


                    document.getElementById(
                        'formTime'
                    ).value = time;


                    document.getElementById(
                        'addScheduleModal'
                    ).style.display = 'block';

                }


                /*
                |--------------------------------------------------------------------------
                | CLOSE MODAL
                |--------------------------------------------------------------------------
                */

                function closeModal()
                {

                    document.getElementById(
                        'addScheduleModal'
                    ).style.display = 'none';

                }


                window.onclick =
                    function(event)
                    {

                        const modal =
                            document.getElementById(
                                'addScheduleModal'
                            );


                        if (
                            event.target === modal
                        ) {

                            closeModal();

                        }

                    };

            </script>

        </main>

    </div>

   

</div>

 @include('footerheader.footer')