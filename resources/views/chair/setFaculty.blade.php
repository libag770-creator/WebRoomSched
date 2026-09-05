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


                /* =====================================================
                   TITLE
                ===================================================== */

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


                /* =====================================================
                   DEPARTMENT INFO
                ===================================================== */

                .department-info {
                    background: #e8f5e9;
                    border-left: 5px solid #2e7d32;
                    padding: 16px 18px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                }

                .department-info-label {
                    color: #777;
                    font-size: 12px;
                    margin-bottom: 4px;
                }

                .department-info-name {
                    color: #2e7d32;
                    font-size: 21px;
                    font-weight: bold;
                }


                /* =====================================================
                   SECTION
                ===================================================== */

                .section {
                    background: white;
                    padding: 25px;
                    border-radius: 12px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 10px rgba(0,0,0,.07);
                }

                .section-title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 18px;
                }


                /* =====================================================
                   FACULTY
                ===================================================== */

                .faculty-grid {
                    display: grid;
                    grid-template-columns:
                        repeat(auto-fill, minmax(210px, 1fr));
                    gap: 15px;
                }

                .faculty-card {
                    display: block;
                    background: white;
                    border: 2px solid #ddd;
                    border-radius: 12px;
                    padding: 20px;
                    text-align: center;
                    cursor: pointer;
                    transition: .2s;
                    text-decoration: none;
                    color: inherit;
                }

                .faculty-card:hover {
                    border-color: #f9a825;
                    transform: translateY(-2px);
                }

                .faculty-card.active {
                    border-color: #2e7d32;
                    background: #f1f8f2;
                }

                .faculty-icon {
                    width: 65px;
                    height: 65px;
                    border-radius: 50%;
                    background: #e8f5e9;
                    color: #2e7d32;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 12px;
                    font-size: 30px;
                }

                .faculty-name {
                    font-weight: bold;
                    color: #2e7d32;
                    word-break: break-word;
                }

                .faculty-count {
                    color: #777;
                    font-size: 12px;
                    margin-top: 7px;
                }


                /* =====================================================
                   SUBJECT MANAGER
                ===================================================== */

                .subject-section {
                    background: #fafafa;
                    border-top: 4px solid #2e7d32;
                    padding: 20px;
                    border-radius: 10px;
                }

                .profile {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    margin-bottom: 20px;
                }

                .profile .faculty-icon {
                    margin: 0;
                    width: 55px;
                    height: 55px;
                    font-size: 25px;
                    flex-shrink: 0;
                }

                .profile-name {
                    font-weight: bold;
                    color: #2e7d32;
                    font-size: 19px;
                }

                .profile-department {
                    color: #777;
                    font-size: 13px;
                    margin-top: 3px;
                }


                /* =====================================================
                   SUBJECT TABLE
                ===================================================== */

                .table-wrapper {
                    width: 100%;
                    overflow-x: auto;
                }

                .subject-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .subject-table th {
                    background: #2e7d32;
                    color: white;
                    padding: 11px;
                    text-align: left;
                    white-space: nowrap;
                }

                .subject-table td {
                    padding: 11px;
                    border-bottom: 1px solid #ddd;
                }

                .subject-table tr:hover {
                    background: #f9fff9;
                }


                /* =====================================================
                   ADD SUBJECT
                ===================================================== */

                .add-subject-title {
                    color: #2e7d32;
                    font-size: 17px;
                    font-weight: bold;
                    margin-top: 25px;
                    margin-bottom: 10px;
                }

                .subject-form {
                    margin-top: 15px;
                    display: grid;
                    grid-template-columns:
                        1fr
                        2fr
                        1fr
                        auto;
                    gap: 10px;
                    align-items: end;
                }


                /* =====================================================
                   FORM
                ===================================================== */

                label {
                    display: block;
                    font-size: 13px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                input,
                select {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    background: white;
                }

                input:focus,
                select:focus {
                    outline: none;
                    border-color: #2e7d32;
                    box-shadow: 0 0 0 2px rgba(46,125,50,.08);
                }


                /* =====================================================
                   BUTTONS
                ===================================================== */

                .btn {
                    border: none;
                    padding: 9px 14px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: bold;
                    text-decoration: none;
                    display: inline-block;
                }

                .green {
                    background: #2e7d32;
                    color: white;
                }

                .green:hover {
                    background: #1b5e20;
                }

                .yellow {
                    background: #f9a825;
                    color: #1b5e20;
                }

                .yellow:hover {
                    background: #e69b00;
                    color: white;
                }

                .red {
                    background: #c62828;
                    color: white;
                }

                .red:hover {
                    background: #a61b1b;
                }

                .gray {
                    background: #777;
                    color: white;
                }

                .gray:hover {
                    background: #616161;
                }


                /* =====================================================
                   MESSAGES
                ===================================================== */

                .message {
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }

                .success {
                    background: #e8f5e9;
                    color: #2e7d32;
                    border-left: 4px solid #2e7d32;
                }

                .error {
                    background: #ffebee;
                    color: #b71c1c;
                    border-left: 4px solid #c62828;
                }


                /* =====================================================
                   EMPTY
                ===================================================== */

                .empty {
                    padding: 30px 20px;
                    text-align: center;
                    color: #777;
                    background: #fafafa;
                    border: 1px dashed #ccc;
                    border-radius: 8px;
                }


                /* =====================================================
                   SET SCHEDULE LINK
                ===================================================== */

                .schedule-link-wrapper {
                    margin-top: 20px;
                    text-align: right;
                }


                /* =====================================================
                   MODAL
                ===================================================== */

                .modal {
                    display: none;
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,.5);
                    z-index: 9999;
                    padding: 30px;
                }

                .modal-content {
                    max-width: 500px;
                    background: white;
                    margin: 50px auto;
                    padding: 25px;
                    border-radius: 10px;
                    border-top: 5px solid #2e7d32;
                }


                /* =====================================================
                   RESPONSIVE
                ===================================================== */

                @media(max-width: 800px) {

                    .subject-form {
                        grid-template-columns: 1fr;
                    }

                    .faculty-grid {
                        grid-template-columns: 1fr 1fr;
                    }

                }

                @media(max-width: 550px) {

                    .faculty-grid {
                        grid-template-columns: 1fr;
                    }

                    .profile {
                        align-items: flex-start;
                    }

                    .subject-table {
                        font-size: 13px;
                    }

                    .subject-table th,
                    .subject-table td {
                        padding: 8px;
                    }

                    .schedule-link-wrapper {
                        text-align: center;
                    }

                }

            </style>


            <div class="page">


                <!-- =================================================
                     TITLE
                ================================================== -->

                <div class="title">
                    Faculty Setup
                </div>

                <div class="subtitle">
                    Manage faculty members and their assigned subjects.
                </div>


                <!-- =================================================
                     SUCCESS
                ================================================== -->

                @if(session('success'))

                    <div class="message success">
                        {{ session('success') }}
                    </div>

                @endif


                <!-- =================================================
                     ERROR
                ================================================== -->

                @if(session('error'))

                    <div class="message error">
                        {{ session('error') }}
                    </div>

                @endif


                <!-- =================================================
                     VALIDATION ERRORS
                ================================================== -->

                @if($errors->any())

                    <div class="message error">

                        <ul style="margin:0;padding-left:20px;">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <!-- =================================================
                     CHAIR'S DEPARTMENT
                ================================================== -->

                <div class="department-info">

                    <div class="department-info-label">
                        Your Department
                    </div>

                    <div class="department-info-name">

                        {{ $department->code ?? '' }}

                        @if(
                            isset($department->name)
                            && $department->name
                        )

                            -
                            {{ $department->name }}

                        @endif

                    </div>

                </div>


                <!-- =================================================
                     FACULTY MEMBERS
                ================================================== -->

                <div class="section">

                    <div class="section-title">
                        Faculty Members
                    </div>


                    <div class="faculty-grid">

                        @forelse(
                            $department->users
                                ->where('role', 'faculty')
                            as $faculty
                        )

                            <a
                                href="{{ route(
                                    'chair.setFaculty',
                                    [
                                        'faculty' =>
                                            $faculty->id
                                    ]
                                ) }}"
                                class="
                                    faculty-card
                                    {{
                                        $selectedFaculty
                                        && $selectedFaculty->id == $faculty->id
                                            ? 'active'
                                            : ''
                                    }}
                                "
                            >

                                <div class="faculty-icon">
                                    👤
                                </div>


                                <div class="faculty-name">
                                    {{ $faculty->name }}
                                </div>


                                <div class="faculty-count">

                                    {{
                                        $faculty
                                            ->facultySubjects
                                            ->count()
                                    }}

                                    subject(s)

                                </div>

                            </a>

                        @empty

                            <div class="empty">

                                No faculty members are currently
                                assigned to {{ $department->name }}.

                            </div>

                        @endforelse

                    </div>

                </div>


                <!-- =================================================
                     SELECTED FACULTY
                ================================================== -->

                @if($selectedFaculty)

                    <div class="section">

                        <div class="subject-section">


                            <!-- =================================================
                                 FACULTY PROFILE
                            ================================================== -->

                            <div class="profile">

                                <div class="faculty-icon">
                                    👤
                                </div>


                                <div>

                                    <div class="profile-name">
                                        {{ $selectedFaculty->name }}
                                    </div>


                                    <div class="profile-department">

                                        Department:

                                        <strong
                                            style="color:#2e7d32;"
                                        >

                                            {{
                                                $selectedFaculty
                                                    ->department
                                                    ->code
                                                ?? ''
                                            }}

                                            @if(
                                                $selectedFaculty->department
                                            )

                                                -

                                                {{
                                                    $selectedFaculty
                                                        ->department
                                                        ->name
                                                }}

                                            @endif

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            <!-- =================================================
                                 ASSIGNED SUBJECTS
                            ================================================== -->

                            <h3 style="color:#2e7d32;margin-top:0;">
                                Assigned Subjects
                            </h3>


                            @if(
                                $selectedFaculty
                                    ->facultySubjects
                                    ->count()
                            )

                                <div class="table-wrapper">

                                    <table class="subject-table">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Course Code
                                                </th>

                                                <th>
                                                    Subject
                                                </th>

                                                <th>
                                                    Year Level
                                                </th>

                                                <th>
                                                    Actions
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach(
                                                $selectedFaculty
                                                    ->facultySubjects
                                                as $facultySubject
                                            )

                                                <tr>

                                                    <td>

                                                        <strong>

                                                            {{
                                                                $facultySubject
                                                                    ->course_code
                                                            }}

                                                        </strong>

                                                    </td>


                                                    <td>

                                                        {{
                                                            $facultySubject
                                                                ->subject
                                                        }}

                                                    </td>


                                                    <td>

                                                        {{
                                                            $facultySubject
                                                                ->year_level
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    <td>

                                                        <!-- EDIT -->

                                                        <button
                                                            type="button"
                                                            class="btn yellow"
                                                            onclick="editSubject(
                                                                {{ $facultySubject->id }},
                                                                @js($facultySubject->course_code),
                                                                @js($facultySubject->subject),
                                                                @js($facultySubject->year_level)
                                                            )"
                                                        >
                                                            Edit
                                                        </button>


                                                        <!-- REMOVE -->

                                                        <form
                                                            action="{{ route(
                                                                'chair.faculty.subjects.delete',
                                                                $facultySubject->id
                                                            ) }}"
                                                            method="POST"
                                                            style="
                                                                display:inline;
                                                            "
                                                        >

                                                            @csrf

                                                            @method('DELETE')


                                                            <button
                                                                type="submit"
                                                                class="btn red"
                                                                onclick="
                                                                    return confirm(
                                                                        'Remove this subject?'
                                                                    )
                                                                "
                                                            >
                                                                Remove
                                                            </button>

                                                        </form>

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            @else

                                <div class="empty">

                                    No subjects assigned yet.

                                </div>

                            @endif


                            <!-- =================================================
                                 ADD SUBJECT
                            ================================================== -->

                            <div class="add-subject-title">
                                Add Subject
                            </div>


                            <form
                                action="{{ route(
                                    'chair.faculty.subjects.store'
                                ) }}"
                                method="POST"
                                class="subject-form"
                            >

                                @csrf


                                <input
                                    type="hidden"
                                    name="faculty_id"
                                    value="{{ $selectedFaculty->id }}"
                                >


                                <div>

                                    <label>
                                        Course Code
                                    </label>

                                    <input
                                        type="text"
                                        name="course_code"
                                        placeholder="IT 111"
                                        value="{{ old('course_code') }}"
                                        required
                                    >

                                </div>


                                <div>

                                    <label>
                                        Subject
                                    </label>

                                    <input
                                        type="text"
                                        name="subject"
                                        placeholder="Database"
                                        value="{{ old('subject') }}"
                                        required
                                    >

                                </div>


                                <div>

                                    <label>
                                        Year Level
                                    </label>

                                    <select
                                        name="year_level"
                                        required
                                    >

                                        <option value="">
                                            Select
                                        </option>

                                        <option
                                            value="1st Year"
                                            {{ old('year_level') == '1st Year' ? 'selected' : '' }}
                                        >
                                            1st Year
                                        </option>

                                        <option
                                            value="2nd Year"
                                            {{ old('year_level') == '2nd Year' ? 'selected' : '' }}
                                        >
                                            2nd Year
                                        </option>

                                        <option
                                            value="3rd Year"
                                            {{ old('year_level') == '3rd Year' ? 'selected' : '' }}
                                        >
                                            3rd Year
                                        </option>

                                        <option
                                            value="4th Year"
                                            {{ old('year_level') == '4th Year' ? 'selected' : '' }}
                                        >
                                            4th Year
                                        </option>

                                    </select>

                                </div>


                                <button
                                    type="submit"
                                    class="btn green"
                                >
                                    Save Subject
                                </button>

                            </form>


                            <!-- =================================================
                                 GO TO SET SCHEDULE
                            ================================================== -->

                            <div class="schedule-link-wrapper">

                                <a
                                    href="{{ route(
                                        'chair.setschedule'
                                    ) }}"
                                    class="btn green"
                                >
                                    Set Classroom Schedule →
                                </a>

                            </div>


                        </div>

                    </div>

                @endif


                <!-- =================================================
                     EDIT SUBJECT MODAL
                ================================================== -->

                <div
                    id="editSubjectModal"
                    class="modal"
                >

                    <div class="modal-content">

                        <h3 style="color:#2e7d32;">
                            Edit Subject
                        </h3>


                        <form
                            id="editSubjectForm"
                            method="POST"
                        >

                            @csrf

                            @method('PUT')


                            <label>
                                Course Code
                            </label>

                            <input
                                type="text"
                                id="editSubjectCode"
                                name="course_code"
                                required
                            >


                            <br><br>


                            <label>
                                Subject
                            </label>

                            <input
                                type="text"
                                id="editSubjectName"
                                name="subject"
                                required
                            >


                            <br><br>


                            <label>
                                Year Level
                            </label>

                            <select
                                id="editSubjectYear"
                                name="year_level"
                                required
                            >

                                <option value="1st Year">
                                    1st Year
                                </option>

                                <option value="2nd Year">
                                    2nd Year
                                </option>

                                <option value="3rd Year">
                                    3rd Year
                                </option>

                                <option value="4th Year">
                                    4th Year
                                </option>

                            </select>


                            <div
                                style="
                                    margin-top:20px;
                                    display:flex;
                                    justify-content:flex-end;
                                    gap:8px;
                                "
                            >

                                <button
                                    type="button"
                                    class="btn gray"
                                    onclick="closeEditSubject()"
                                >
                                    Cancel
                                </button>


                                <button
                                    type="submit"
                                    class="btn green"
                                >
                                    Save Changes
                                </button>

                            </div>

                        </form>

                    </div>

                </div>


            </div>


            <script>

                /*
                |--------------------------------------------------------------------------
                | EDIT SUBJECT
                |--------------------------------------------------------------------------
                */

                function editSubject(
                    id,
                    courseCode,
                    subject,
                    yearLevel
                ) {

                    document.getElementById(
                        'editSubjectCode'
                    ).value =
                        courseCode;


                    document.getElementById(
                        'editSubjectName'
                    ).value =
                        subject;


                    document.getElementById(
                        'editSubjectYear'
                    ).value =
                        yearLevel || '';


                    document.getElementById(
                        'editSubjectForm'
                    ).action =
                        "{{ url('/chair/faculty/subjects') }}/" +
                        id;


                    document.getElementById(
                        'editSubjectModal'
                    ).style.display =
                        'block';

                }


                /*
                |--------------------------------------------------------------------------
                | CLOSE MODAL
                |--------------------------------------------------------------------------
                */

                function closeEditSubject()
                {

                    document.getElementById(
                        'editSubjectModal'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | CLOSE MODAL WHEN CLICKING OUTSIDE
                |--------------------------------------------------------------------------
                */

                window.addEventListener(
                    'click',
                    function(event)
                    {

                        const modal =
                            document.getElementById(
                                'editSubjectModal'
                            );


                        if (
                            modal &&
                            event.target === modal
                        ) {

                            closeEditSubject();

                        }

                    }
                );

            </script>


        </main>

    </div>

    @include('footerheader.footer')

</div>