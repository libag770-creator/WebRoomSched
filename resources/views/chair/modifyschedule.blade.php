<div class="wrapper">

    @include('footerheader.header')

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
                     DEPARTMENT / BUILDING / ROOM
                ====================================================== --}}

                <div class="section">

                    <div class="schedule-title">
                        Select Classroom
                    </div>


                    <div class="filters">


                        {{-- DEPARTMENT --}}

                        <div class="filter-group">

                            <label>
                                Department
                            </label>

                            <select
                                id="departmentSelect"
                                onchange="loadBuildings()"
                            >

                                <option value="">
                                    Select Department
                                </option>

                                @foreach($departments as $department)

                                    <option
                                        value="{{ $department->id }}"
                                    >

                                        {{ $department->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- BUILDING --}}

                        <div class="filter-group">

                            <label>
                                Building
                            </label>

                            <select
                                id="buildingSelect"
                                onchange="loadRooms()"
                                disabled
                            >

                                <option value="">
                                    Select Building
                                </option>

                            </select>

                        </div>


                        {{-- ROOM --}}

                        <div class="filter-group">

                            <label>
                                Room
                            </label>

                            <select
                                id="roomSelect"
                                onchange="loadSchedule()"
                                disabled
                            >

                                <option value="">
                                    Select Room
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     SCHEDULE
                ====================================================== --}}

                <div
                    class="section"
                    id="scheduleSection"
                    style="display:none;"
                >

                    <div class="schedule-title">
                        Room Schedule
                    </div>


                    <div
                        class="room-info"
                        id="roomInfo"
                    >
                    </div>


                    <div class="permission-info">
                        <strong>Rule:</strong>

                        Filled schedules are locked.
                        You can only add a schedule to an
                        empty slot when the room owner has
                        allowed other departments to use
                        empty slots.
                    </div>


                    <div style="overflow-x:auto;">

                        <table class="schedule-table">

                            <thead>

                                <tr>

                                    <th>
                                        Time
                                    </th>

                                    <th>
                                        Monday
                                    </th>

                                    <th>
                                        Tuesday
                                    </th>

                                    <th>
                                        Wednesday
                                    </th>

                                    <th>
                                        Thursday
                                    </th>

                                    <th>
                                        Friday
                                    </th>

                                    <th>
                                        Saturday
                                    </th>

                                </tr>

                            </thead>

                            <tbody id="scheduleBody">

                            </tbody>

                        </table>

                    </div>

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

                /*
                |--------------------------------------------------------------------------
                | DATA FROM CONTROLLER
                |--------------------------------------------------------------------------
                */

                const departments =
                    @json($departments);


                /*
                |--------------------------------------------------------------------------
                | LOAD BUILDINGS
                |--------------------------------------------------------------------------
                */

                function loadBuildings()
                {

                    const departmentId =
                        document.getElementById(
                            'departmentSelect'
                        ).value;


                    const buildingSelect =
                        document.getElementById(
                            'buildingSelect'
                        );


                    const roomSelect =
                        document.getElementById(
                            'roomSelect'
                        );


                    buildingSelect.innerHTML =
                        '<option value="">Select Building</option>';

                    roomSelect.innerHTML =
                        '<option value="">Select Room</option>';


                    buildingSelect.disabled =
                        true;

                    roomSelect.disabled =
                        true;


                    if (!departmentId) {
                        return;
                    }


                    const department =
                        departments.find(
                            department =>
                                department.id ==
                                departmentId
                        );


                    if (!department) {
                        return;
                    }


                    department.buildings.forEach(
                        building => {

                            buildingSelect.innerHTML +=
                                `
                                <option value="${building.id}">
                                    ${building.name}
                                </option>
                                `;

                        }
                    );


                    buildingSelect.disabled =
                        false;

                }


                /*
                |--------------------------------------------------------------------------
                | LOAD ROOMS
                |--------------------------------------------------------------------------
                */

                function loadRooms()
                {

                    const departmentId =
                        document.getElementById(
                            'departmentSelect'
                        ).value;


                    const buildingId =
                        document.getElementById(
                            'buildingSelect'
                        ).value;


                    const roomSelect =
                        document.getElementById(
                            'roomSelect'
                        );


                    roomSelect.innerHTML =
                        '<option value="">Select Room</option>';

                    roomSelect.disabled =
                        true;


                    if (
                        !departmentId ||
                        !buildingId
                    ) {

                        return;

                    }


                    const department =
                        departments.find(
                            department =>
                                department.id ==
                                departmentId
                        );


                    if (!department) {
                        return;
                    }


                    const building =
                        department.buildings.find(
                            building =>
                                building.id ==
                                buildingId
                        );


                    if (!building) {
                        return;
                    }


                    building.rooms.forEach(
                        room => {

                            roomSelect.innerHTML +=
                                `
                                <option value="${room.id}">
                                    ${room.room_name}
                                </option>
                                `;

                        }
                    );


                    roomSelect.disabled =
                        false;

                }


                /*
                |--------------------------------------------------------------------------
                | LOAD SCHEDULE
                |--------------------------------------------------------------------------
                */

                function loadSchedule()
                {

                    const roomId =
                        document.getElementById(
                            'roomSelect'
                        ).value;


                    if (!roomId) {

                        document.getElementById(
                            'scheduleSection'
                        ).style.display = 'none';

                        return;

                    }


                    const departmentId =
                        document.getElementById(
                            'departmentSelect'
                        ).value;


                    const buildingId =
                        document.getElementById(
                            'buildingSelect'
                        ).value;


                    const department =
                        departments.find(
                            department =>
                                department.id ==
                                departmentId
                        );


                    const building =
                        department.buildings.find(
                            building =>
                                building.id ==
                                buildingId
                        );


                    const room =
                        building.rooms.find(
                            room =>
                                room.id ==
                                roomId
                        );


                    document.getElementById(
                        'roomInfo'
                    ).innerHTML = `

                        <strong>
                            ${department.name}
                        </strong>

                        &nbsp; →
                        ${building.name}

                        &nbsp; →
                        ${room.room_name}

                    `;


                    buildSchedule(room);

                    document.getElementById(
                        'scheduleSection'
                    ).style.display = 'block';

                }


                /*
                |--------------------------------------------------------------------------
                | BUILD SCHEDULE
                |--------------------------------------------------------------------------
                */

                 window.chairDepartmentId =
        {{ auth()->user()->department_id }};
        
function buildSchedule(room)
{
    /*
    |--------------------------------------------------------------------------
    | DAYS
    |--------------------------------------------------------------------------
    */

    const days = [
        {
            label: 'Monday',
            value: 'MON'
        },
        {
            label: 'Tuesday',
            value: 'TUE'
        },
        {
            label: 'Wednesday',
            value: 'WED'
        },
        {
            label: 'Thursday',
            value: 'THU'
        },
        {
            label: 'Friday',
            value: 'FRI'
        },
        {
            label: 'Saturday',
            value: 'SAT'
        }
    ];


    /*
    |--------------------------------------------------------------------------
    | TIME SLOTS
    |--------------------------------------------------------------------------
    */

    const times = [
        '8:00-9:00',
        '9:00-10:00',
        '10:00-11:00',
        '12:00-1:00',
        '1:00-2:00',
        '2:00-3:00',
        '3:00-4:00',
        '4:00-5:00'
    ];


    /*
    |--------------------------------------------------------------------------
    | ROOM SCHEDULES
    |--------------------------------------------------------------------------
    */

    const schedules =
        room.schedules || [];


    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR DEPARTMENT
    |--------------------------------------------------------------------------
    */

    const currentDepartmentId =
        Number(
            window.chairDepartmentId
        );


    /*
    |--------------------------------------------------------------------------
    | ROOM OWNER DEPARTMENT
    |--------------------------------------------------------------------------
    */

    const roomDepartmentId =
        Number(
            room.department_id
        );


    /*
    |--------------------------------------------------------------------------
    | OTHER DEPARTMENT PERMISSION
    |--------------------------------------------------------------------------
    */

    const allowOtherDepartments =
        Number(
            room.allow_other_departments
        ) === 1;


    let html = '';


    /*
    |--------------------------------------------------------------------------
    | BUILD TABLE
    |--------------------------------------------------------------------------
    */

    times.forEach(
        time => {

            html += `
                <tr>

                    <td class="time-cell">
                        ${time}
                    </td>
            `;


            days.forEach(
                day => {

                    /*
                    |--------------------------------------------------------------------------
                    | FIND SCHEDULE
                    |--------------------------------------------------------------------------
                    */

                    const schedule =
                        schedules.find(
                            item => {

                                const itemDay =
                                    String(
                                        item.day || ''
                                    )
                                    .trim()
                                    .toUpperCase();


                                const itemTime =
                                    String(
                                        item.time || ''
                                    )
                                    .trim();


                                return (
                                    itemDay === day.value &&
                                    itemTime === time
                                );

                            }
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | EXISTING SCHEDULE
                    |--------------------------------------------------------------------------
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
                        |--------------------------------------------------------------------------
                        | OWN DEPARTMENT SCHEDULE
                        |--------------------------------------------------------------------------
                        |
                        | The logged-in Chair can modify
                        | schedules belonging to their department.
                        |
                        */

                        if (isOwnDepartment) {

                            html += `

                                <td
                                    class="schedule-cell filled editable-own"
                                    onclick="openAddModal(
                                        ${room.id},
                                        '${day.value}',
                                        '${time}'
                                    )"
                                >

                                    <div class="schedule-course">
                                        ${schedule.course_code ?? ''}
                                    </div>

                                    <div class="schedule-subject">
                                        ${schedule.subject ?? ''}
                                    </div>

                                    <div class="schedule-instructor">
                                        ${schedule.instructor ?? ''}
                                    </div>

                                    <div class="schedule-year">
                                        ${schedule.year_level ?? ''}
                                    </div>

                                    ${
                                        schedule.subject_type
                                        ? `
                                            <div class="schedule-year">
                                                ${schedule.subject_type}
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
                        |--------------------------------------------------------------------------
                        | OTHER DEPARTMENT SCHEDULE
                        |--------------------------------------------------------------------------
                        |
                        | ALWAYS LOCKED.
                        |
                        */

                        else {

                            html += `

                                <td
                                    class="schedule-cell filled locked-cell"
                                >

                                    <div class="schedule-course">
                                        ${schedule.course_code ?? ''}
                                    </div>

                                    <div class="schedule-subject">
                                        ${schedule.subject ?? ''}
                                    </div>

                                    <div class="schedule-instructor">
                                        ${schedule.instructor ?? ''}
                                    </div>

                                    <div class="schedule-year">
                                        ${schedule.year_level ?? ''}
                                    </div>

                                    ${
                                        schedule.subject_type
                                        ? `
                                            <div class="schedule-year">
                                                ${schedule.subject_type}
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

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | EMPTY SLOT
                    |--------------------------------------------------------------------------
                    */

                    /*
                    |--------------------------------------------------------------------------
                    | ROOM BELONGS TO CURRENT CHAIR
                    |--------------------------------------------------------------------------
                    |
                    | Own department can always add to its
                    | own empty slots.
                    |
                    */

                    if (
                        roomDepartmentId ===
                        currentDepartmentId
                    ) {

                        html += `

                            <td
                                class="schedule-cell empty-slot editable-own"
                                onclick="openAddModal(
                                    ${room.id},
                                    '${day.value}',
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
                                        '${day.value}',
                                        '${time}'
                                    )"
                                >
                                    + Add Schedule
                                </button>

                            </td>

                        `;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | OTHER DEPARTMENT ROOM
                    |--------------------------------------------------------------------------
                    |
                    | Empty slot is available only if
                    | the room owner enabled permission.
                    |
                    */

                    else if (
                        allowOtherDepartments
                    ) {

                        html += `

                            <td
                                class="schedule-cell empty-slot allowed-other"
                                onclick="openAddModal(
                                    ${room.id},
                                    '${day.value}',
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
                                        '${day.value}',
                                        '${time}'
                                    )"
                                >
                                    + Add Schedule
                                </button>

                                <br>

                                <small>
                                    Allowed by room owner
                                </small>

                            </td>

                        `;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | OTHER DEPARTMENT ROOM - NOT ALLOWED
                    |--------------------------------------------------------------------------
                    */

                    else {

                        html += `

                            <td
                                class="schedule-cell empty-slot locked-cell"
                            >

                                Empty

                                <br>

                                <span class="not-allowed">
                                    🔒 Not allowed
                                </span>

                            </td>

                        `;

                    }

                }
            );


            html += `
                </tr>
            `;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DISPLAY
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'scheduleBody'
    ).innerHTML = html;
}
                    

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

    @include('footerheader.footer')

</div>