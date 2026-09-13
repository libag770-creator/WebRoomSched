<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Student Schedule</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #222;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1500px;
            margin: auto;
        }

        /* =========================================
           HEADER
        ========================================= */

        .page-header {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 28px;
            margin-bottom: 6px;
        }

        .page-header p {
            color: #777;
            font-size: 14px;
        }

        /* =========================================
           MAIN LAYOUT
        ========================================= */

        .main-layout {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        /* =========================================
           DEPARTMENT PANEL
        ========================================= */

        .department-panel {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 18px;
            position: sticky;
            top: 20px;
        }

        .panel-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 14px;
        }

        .search-box {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .search-box:focus {
            border-color: #222;
        }

        .department-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 600px;
            overflow-y: auto;
        }

        .department-button {
            width: 100%;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            padding: 14px;
            cursor: pointer;
            text-align: left;
            transition: 0.2s;
        }

        .department-button:hover {
            background: #f1f3f5;
        }

        .department-button.active {
            background: #222;
            color: white;
            border-color: #222;
        }

        .department-name {
            font-size: 15px;
            font-weight: bold;
        }

        .no-department {
            text-align: center;
            color: #888;
            padding: 20px 10px;
            font-size: 14px;
        }

        /* =========================================
           RIGHT PANEL
        ========================================= */

        .content-panel {
            min-width: 0;
        }

        /* =========================================
           EMPTY STATE
        ========================================= */

        .empty-state {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state h2 {
            font-size: 22px;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #777;
            font-size: 14px;
        }

        /* =========================================
           YEAR SECTION
        ========================================= */

        .year-section {
            display: none;
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .year-section.show {
            display: block;
        }

        .selected-department {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
        }

        .year-title {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .year-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .year-button {
            border: 1px solid #ccc;
            background: white;
            border-radius: 8px;
            padding: 14px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: 0.2s;
        }

        .year-button:hover {
            background: #f1f3f5;
        }

        .year-button.active {
            background: #222;
            color: white;
            border-color: #222;
        }

        /* =========================================
           SCHEDULE
        ========================================= */

        .schedule-wrapper {
            display: none;
        }

        .schedule-wrapper.show {
            display: block;
        }

        .schedule-paper {
            background: white;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .schedule-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .schedule-subtitle {
            text-align: center;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        .schedule-info {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .schedule-info strong {
            font-size: 14px;
        }

        /* =========================================
           TABLE
        ========================================= */

        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .schedule-table {
            width: 100%;
            min-width: 1050px;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 11px;
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #777;
            padding: 7px 5px;
            text-align: center;
            vertical-align: middle;
        }

        .schedule-table th {
            background: #e9ecef;
            font-weight: bold;
        }

        .course-code-column {
            width: 95px;
        }

        .description-column {
            width: 220px;
            text-align: left !important;
        }

        .units-column {
            width: 65px;
        }

        .instructor-column {
            width: 140px;
        }

        .major-column {
            width: 120px;
        }

        .room-column {
            width: 80px;
        }

        .time-column {
            width: 90px;
        }

        .day-column {
            width: 55px;
        }

        .remarks-column {
            width: 100px;
        }

        .course-code-text {
            font-weight: bold;
        }

        .major-text {
            font-weight: bold;
        }

        .no-schedule {
            padding: 40px 20px !important;
            text-align: center !important;
            color: #888;
        }

        .loading {
            padding: 40px 20px;
            text-align: center;
            color: #777;
        }

        /* =========================================
           FOOTER
        ========================================= */

        .schedule-footer {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            margin-top: 30px;
        }

        .signature {
            width: 220px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #555;
            margin-top: 35px;
            margin-bottom: 5px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
        }

        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 1000px) {

            body {
                padding: 12px;
            }

            .main-layout {
                grid-template-columns: 1fr;
            }

            .department-panel {
                position: static;
            }

            .department-list {
                max-height: 350px;
            }

            .year-buttons {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .page-header {
                padding: 18px;
            }

            .page-header h1 {
                font-size: 22px;
            }

            .department-panel,
            .year-section,
            .schedule-paper {
                padding: 15px;
            }

            .year-buttons {
                grid-template-columns: 1fr 1fr;
            }

            .year-button {
                padding: 12px 5px;
                font-size: 13px;
            }

            .schedule-title {
                font-size: 18px;
            }

            .schedule-info {
                flex-direction: column;
                gap: 5px;
            }

            .schedule-footer {
                flex-direction: column;
                align-items: center;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <!-- =========================================
         PAGE HEADER
    ========================================== -->

    <div class="page-header">

        <h1>
            Student Schedule
        </h1>

        <p>
            Select your department and year level
            to view the class schedule.
        </p>

    </div>


    <!-- =========================================
         MAIN
    ========================================== -->

    <div class="main-layout">


        <!-- =====================================
             LEFT: DEPARTMENTS
        ====================================== -->

        <div class="department-panel">

            <div class="panel-title">
                Select Department
            </div>

            <input
                type="text"
                id="departmentSearch"
                class="search-box"
                placeholder="🔍 Search department..."
                oninput="searchDepartments()"
            >

            <div
                class="department-list"
                id="departmentList"
            >

                @forelse($departments as $department)

                    <button
                        type="button"
                        class="department-button"
                        data-id="{{ $department->id }}"
                        data-name="{{ $department->name }}"
                        onclick="selectDepartment(this)"
                    >

                        <div class="department-name">
                            {{ $department->name }}
                        </div>

                    </button>

                @empty

                    <div class="no-department">
                        No departments available.
                    </div>

                @endforelse

            </div>

        </div>


        <!-- =====================================
             RIGHT: YEAR + SCHEDULE
        ====================================== -->

        <div class="content-panel">


            <!-- DEFAULT MESSAGE -->

            <div
                class="empty-state"
                id="emptyState"
            >

                <h2>
                    Select a Department
                </h2>

                <p>
                    Choose a department from the list
                    to continue.
                </p>

            </div>


            <!-- YEAR LEVEL -->

            <div
                class="year-section"
                id="yearSection"
            >

                <div
                    class="selected-department"
                    id="selectedDepartment"
                >
                </div>

                <div class="year-title">
                    Select Year Level
                </div>

                <div class="year-buttons">

                    <button
                        type="button"
                        class="year-button"
                        onclick="selectYear('1st Year', this)"
                    >
                        1st Year
                    </button>

                    <button
                        type="button"
                        class="year-button"
                        onclick="selectYear('2nd Year', this)"
                    >
                        2nd Year
                    </button>

                    <button
                        type="button"
                        class="year-button"
                        onclick="selectYear('3rd Year', this)"
                    >
                        3rd Year
                    </button>

                    <button
                        type="button"
                        class="year-button"
                        onclick="selectYear('4th Year', this)"
                    >
                        4th Year
                    </button>

                </div>

            </div>


            <!-- SCHEDULE -->

            <div
                class="schedule-wrapper"
                id="scheduleWrapper"
            >

                <div class="schedule-paper">

                    <div class="schedule-title">
                        CLASS PROGRAM
                    </div>

                    <div class="schedule-subtitle">

                        {{ strtoupper($semester) }},
                        A.Y. {{ $academicYear }}

                    </div>


                    <div class="schedule-info">

                        <div>

                            <strong>
                                Department:
                            </strong>

                            <span id="scheduleDepartment">
                            </span>

                        </div>

                        <div>

                            <strong>
                                Year Level:
                            </strong>

                            <span id="scheduleYear">
                            </span>

                        </div>

                    </div>


                    <!-- RESPONSIVE TABLE -->

                    <div class="table-container">

                        <table class="schedule-table">

                            <thead>

                                <tr>

                                    <th
                                        rowspan="2"
                                        class="course-code-column"
                                    >
                                        COURSE<br>CODE
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="description-column"
                                    >
                                        DESCRIPTIVE TITLE
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="units-column"
                                    >
                                        TYPE
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="instructor-column"
                                    >
                                        INSTRUCTOR
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="major-column"
                                    >
                                        MAJOR
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="room-column"
                                    >
                                        ROOM
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="time-column"
                                    >
                                        TIME
                                    </th>

                                    <th colspan="5">
                                        DAY
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="remarks-column"
                                    >
                                        REMARKS
                                    </th>

                                </tr>

                                <tr>

                                    <th class="day-column">
                                        MON
                                    </th>

                                    <th class="day-column">
                                        TUE
                                    </th>

                                    <th class="day-column">
                                        WED
                                    </th>

                                    <th class="day-column">
                                        THU
                                    </th>

                                    <th class="day-column">
                                        FRI
                                    </th>

                                </tr>

                            </thead>

                            <tbody id="scheduleBody">

                            </tbody>

                        </table>

                    </div>


                    <!-- FOOTER -->

                    <div class="schedule-footer">

                        <div class="signature">

                            Prepared by:

                            <div class="signature-line"></div>

                            <div class="signature-name">
                                PROGRAM COORDINATOR
                            </div>

                        </div>


                        <div class="signature">

                            Approved by:

                            <div class="signature-line"></div>

                            <div class="signature-name">
                                DEAN
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

    /*
    =====================================================
    SELECTED VALUES
    =====================================================
    */

    let selectedDepartmentId = null;
    let selectedDepartmentName = "";
    let selectedYearLevel = "";


    /*
    =====================================================
    SELECT DEPARTMENT
    =====================================================
    */

    function selectDepartment(button)
    {

        selectedDepartmentId =
            button.dataset.id;

        selectedDepartmentName =
            button.dataset.name;


        /*
        Remove active department
        */

        document
            .querySelectorAll(".department-button")
            .forEach(function(item) {

                item.classList.remove("active");

            });


        /*
        Activate selected department
        */

        button.classList.add("active");


        /*
        Display selected department
        */

        document.getElementById(
            "selectedDepartment"
        ).textContent =
            selectedDepartmentName;


        /*
        Reset year
        */

        selectedYearLevel = "";


        document
            .querySelectorAll(".year-button")
            .forEach(function(button) {

                button.classList.remove("active");

            });


        /*
        Hide old schedule
        */

        document
            .getElementById("scheduleWrapper")
            .classList.remove("show");


        /*
        Hide empty state
        */

        document
            .getElementById("emptyState")
            .style.display = "none";


        /*
        Show year section
        */

        document
            .getElementById("yearSection")
            .classList.add("show");

    }


    /*
    =====================================================
    SEARCH DEPARTMENTS
    =====================================================
    */

    function searchDepartments()
    {

        const search =
            document
                .getElementById("departmentSearch")
                .value
                .toLowerCase()
                .trim();


        const departments =
            document.querySelectorAll(
                ".department-button"
            );


        let found = false;


        departments.forEach(function(department) {

            const name =
                department.dataset.name
                    .toLowerCase();


            if (
                search === "" ||
                name.includes(search)
            ) {

                department.style.display =
                    "block";

                found = true;

            } else {

                department.style.display =
                    "none";

            }

        });


        let noResult =
            document.getElementById(
                "departmentNoResult"
            );


        if (!found) {

            if (!noResult) {

                noResult =
                    document.createElement("div");

                noResult.id =
                    "departmentNoResult";

                noResult.className =
                    "no-department";

                noResult.textContent =
                    "No department found.";

                document
                    .getElementById("departmentList")
                    .appendChild(noResult);

            }

        } else {

            if (noResult) {

                noResult.remove();

            }

        }

    }


    /*
    =====================================================
    SELECT YEAR
    =====================================================
    */

    function selectYear(year, button)
    {

        if (!selectedDepartmentId) {

            alert(
                "Please select a department first."
            );

            return;

        }


        selectedYearLevel = year;


        /*
        Active button
        */

        document
            .querySelectorAll(".year-button")
            .forEach(function(item) {

                item.classList.remove("active");

            });


        button.classList.add("active");


        /*
        Update schedule information
        */

        document.getElementById(
            "scheduleDepartment"
        ).textContent =
            selectedDepartmentName;


        document.getElementById(
            "scheduleYear"
        ).textContent =
            selectedYearLevel;


        /*
        Load schedules
        */

        loadSchedules();

    }


    /*
    =====================================================
    LOAD SCHEDULES
    =====================================================
    */

    function loadSchedules()
    {

        const wrapper =
            document.getElementById(
                "scheduleWrapper"
            );


        const body =
            document.getElementById(
                "scheduleBody"
            );


        wrapper.classList.add("show");


        body.innerHTML = `

            <tr>

                <td
                    colspan="13"
                    class="loading"
                >
                    Loading schedule...
                </td>

            </tr>

        `;


        const url =
            "{{ route('student.schedule.data') }}" +
            "?department_id=" +
            encodeURIComponent(
                selectedDepartmentId
            ) +
            "&year_level=" +
            encodeURIComponent(
                selectedYearLevel
            );


        fetch(url, {

            method: "GET",

            headers: {

                "Accept":
                    "application/json",

                "X-Requested-With":
                    "XMLHttpRequest"

            }

        })

        .then(function(response) {

            if (!response.ok) {

                throw new Error(
                    "Failed to load schedule."
                );

            }

            return response.json();

        })

        .then(function(data) {

            if (!data.success) {

                throw new Error(
                    "Unable to load schedule."
                );

            }


            displaySchedules(
                data.schedules
            );

        })

        .catch(function(error) {

            console.error(error);


            body.innerHTML = `

                <tr>

                    <td
                        colspan="13"
                        class="no-schedule"
                    >
                        Unable to load schedule.
                    </td>

                </tr>

            `;

        });

    }


    /*
    =====================================================
    DISPLAY SCHEDULES
    =====================================================
    */

    function displaySchedules(schedules)
    {

        const body =
            document.getElementById(
                "scheduleBody"
            );


        body.innerHTML = "";


        /*
        No schedules
        */

        if (
            !schedules ||
            schedules.length === 0
        ) {

            body.innerHTML = `

                <tr>

                    <td
                        colspan="13"
                        class="no-schedule"
                    >

                        No schedule found for
                        ${escapeHtml(
                            selectedDepartmentName
                        )}
                        -
                        ${escapeHtml(
                            selectedYearLevel
                        )}

                    </td>

                </tr>

            `;

            return;

        }


        /*
        Sort schedules by day and time
        */

        schedules.sort(function(a, b) {

            const dayOrder = {

                "Monday": 1,
                "Tuesday": 2,
                "Wednesday": 3,
                "Thursday": 4,
                "Friday": 5,

                "MON": 1,
                "TUE": 2,
                "WED": 3,
                "THU": 4,
                "FRI": 5

            };


            const dayA =
                dayOrder[a.day] || 99;

            const dayB =
                dayOrder[b.day] || 99;


            if (dayA !== dayB) {

                return dayA - dayB;

            }


            return String(a.time || "")
                .localeCompare(
                    String(b.time || "")
                );

        });


        /*
        Create schedule rows
        */

        schedules.forEach(function(item) {

            const row =
                document.createElement("tr");


            const day =
                normalizeDay(item.day);


            row.innerHTML = `

                <td class="course-code-text">
                    ${escapeHtml(
                        item.course_code || ""
                    )}
                </td>

                <td
                    style="text-align:left;"
                >
                    ${escapeHtml(
                        item.subject || ""
                    )}
                </td>

                <td>
                    ${escapeHtml(
                        item.subject_type || ""
                    )}
                </td>

                <td>
                    ${escapeHtml(
                        item.instructor || ""
                    )}
                </td>

                <td class="major-text">
                    ${escapeHtml(
                        item.major || ""
                    )}
                </td>

                <td>
                    ${escapeHtml(
                        item.room || ""
                    )}
                </td>

                <td>
                    ${escapeHtml(
                        item.time || ""
                    )}
                </td>

                <td>
                    ${
                        day === "MON"
                            ? "●"
                            : ""
                    }
                </td>

                <td>
                    ${
                        day === "TUE"
                            ? "●"
                            : ""
                    }
                </td>

                <td>
                    ${
                        day === "WED"
                            ? "●"
                            : ""
                    }
                </td>

                <td>
                    ${
                        day === "THU"
                            ? "●"
                            : ""
                    }
                </td>

                <td>
                    ${
                        day === "FRI"
                            ? "●"
                            : ""
                    }
                </td>

                <td>
                    ${escapeHtml(
                        item.description || ""
                    )}
                </td>

            `;


            body.appendChild(row);

        });

    }


    /*
    =====================================================
    NORMALIZE DAY
    =====================================================
    */

    function normalizeDay(day)
    {

        if (!day) {

            return "";

        }


        const value =
            String(day)
                .trim()
                .toUpperCase();


        if (
            value === "MON" ||
            value === "MONDAY"
        ) {

            return "MON";

        }


        if (
            value === "TUE" ||
            value === "TUESDAY"
        ) {

            return "TUE";

        }


        if (
            value === "WED" ||
            value === "WEDNESDAY"
        ) {

            return "WED";

        }


        if (
            value === "THU" ||
            value === "THURSDAY"
        ) {

            return "THU";

        }


        if (
            value === "FRI" ||
            value === "FRIDAY"
        ) {

            return "FRI";

        }


        return value;

    }


    /*
    =====================================================
    ESCAPE HTML
    =====================================================
    */

    function escapeHtml(value)
    {

        const div =
            document.createElement("div");

        div.textContent =
            value ?? "";

        return div.innerHTML;

    }

</script>

</body>

</html>