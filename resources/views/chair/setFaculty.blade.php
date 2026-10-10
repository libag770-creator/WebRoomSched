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
                   MASTER CURRICULUM COURSES
                ===================================================== */

                .master-curriculum-box {
                    margin-top: 18px;
                    background: #f8fbf9;
                    border: 1px solid #dfe9e1;
                    border-radius: 10px;
                    padding: 15px;
                }

                .master-curriculum-title {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 10px;
                    margin-bottom: 10px;
                    color: #2e7d32;
                    font-size: 17px;
                    font-weight: 800;
                }

                .master-curriculum-subtitle {
                    margin: 0 0 12px;
                    color: #667085;
                    font-size: 11px;
                }

                .master-course-table {
                    width: 100%;
                    border-collapse: collapse;
                    min-width: 850px;
                }

                .master-course-table th {
                    background: #e8f5e9;
                    color: #2e7d32;
                    padding: 9px;
                    text-align: left;
                    font-size: 11px;
                    white-space: nowrap;
                }

                .master-course-table td {
                    padding: 9px;
                    border-bottom: 1px solid #e7ece8;
                    font-size: 11px;
                }

                .sync-badge {
                    display: inline-block;
                    padding: 4px 7px;
                    border-radius: 999px;
                    background: #e8f5e9;
                    color: #2e7d32;
                    font-size: 9px;
                    font-weight: 800;
                }

                .manual-help {
                    display: block;
                    margin-top: 6px;
                    color: #667085;
                    font-size: 10px;
                    line-height: 1.4;
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

            
                /* Center Faculty Setup feedback messages in the viewport. */
                .faculty-feedback-overlay { position:fixed; inset:0; z-index:999990; display:flex; align-items:center; justify-content:center; padding:20px; background:rgba(15,23,42,.5); backdrop-filter:blur(3px); }
                .faculty-feedback-dialog { width:min(440px,100%); background:#fff; border-radius:16px; padding:30px; box-shadow:0 24px 70px rgba(0,0,0,.25); text-align:center; border-top:5px solid #087443; }
                .faculty-feedback-dialog.error { border-top-color:#dc2626; }
                .faculty-feedback-dialog button { border:0; border-radius:8px; padding:10px 28px; color:#fff; background:#087443; cursor:pointer; font-weight:600; margin-top:14px; }
                .faculty-feedback-dialog.error button { background:#dc2626; }

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

{{-- Import Faculty from Other Departments --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-800">
            Import Faculty from Another Department
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Select a faculty member from another department to make them
            available for your department's faculty assignments.
        </p>
    </div>

    <form action="{{ route('chair.faculty.import') }}" method="POST"
          class="flex flex-col sm:flex-row gap-3">
        @csrf

        <div class="flex-1">
            <label for="faculty_id"
                   class="block text-sm font-medium text-gray-700 mb-1">
                Select Faculty Member
            </label>

            <select name="faculty_id" id="faculty_id" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">-- Select Faculty --</option>

                @forelse ($availableFaculty as $faculty)
                    <option value="{{ $faculty->id }}">
                        {{ $faculty->name }}
                        — {{ $faculty->department->name ?? 'No Department' }}
                    </option>
                @empty
                    <option value="" disabled>
                        No faculty members available to import
                    </option>
                @endforelse
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white
                           font-medium px-5 py-2 rounded-lg transition">
                Import Faculty
            </button>
        </div>
    </form>

    @if (session('success'))
        <p class="text-green-600 text-sm mt-3">
            {{ session('success') }}
        </p>
    @endif

    @if (session('error'))
        <p class="text-red-600 text-sm mt-3">
            {{ session('error') }}
        </p>
    @endif
</div>


{{-- Imported Faculty Members --}}
<div class="section">
    <div class="section-title">
        Imported Faculty Members
    </div>

    <p class="subtitle">
        Click a faculty card to manage their assigned subjects.
    </p>

    <div class="faculty-grid">
        @forelse ($importedFaculty as $faculty)
            <div>
                <a
                    href="{{ route('chair.setFaculty', ['faculty' => $faculty->id]) }}"
                    class="faculty-card {{
                        $selectedFaculty && $selectedFaculty->id == $faculty->id
                            ? 'active'
                            : ''
                    }}"
                >
                    <div class="faculty-icon">👤</div>

                    <div class="faculty-name">
                        {{ $faculty->name }}
                    </div>

                    <div class="faculty-count">
                        {{ $faculty->facultySubjects->count() }}
                        subject(s)
                    </div>

                    <div class="faculty-count">
                        From:
                        {{ $faculty->department->code ?? $faculty->department->name ?? 'Other Department' }}
                    </div>

                    <div style="margin-top:12px;">
                        <span class="btn green">
                            Manage Subjects
                        </span>
                    </div>
                </a>

                <form
                    action="{{ route('chair.faculty.import.remove', $faculty->id) }}"
                    method="POST"
                    style="text-align:center;margin-top:8px;"
                    onsubmit="return confirm('Remove this faculty member from your imported list? Their account will not be deleted.');"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn red">
                        Remove Import
                    </button>
                </form>
            </div>
        @empty
            <div class="empty">
                No faculty members have been imported yet.
            </div>
        @endforelse
    </div>
</div>


                <!-- =================================================
                     MASTER CURRICULUM LIST

                     Hidden intentionally. Curriculum courses remain
                     connected to Faculty & Subjects through the selector
                     below, but the full master table is not displayed here.
                ================================================== -->

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
                                                    Course Description
                                                </th>

                                                <th>
                                                    Year Level
                                                </th>

                                                <th>
                                                     Major
                                                </th>

                                                 <th>
                                                     Program
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
        {{ $facultySubject->course_code }}
    </strong>
</td>

<td>
    {{ $facultySubject->subject }}
</td>

<td>
    {{ $facultySubject->year_level ?? '-' }}
</td>

<td>
    {{ $facultySubject->major ?? '-' }}
</td>

<td>
    {{ $facultySubject->program ?? '-' }}
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
    @js($facultySubject->year_level),
    @js($facultySubject->major),
    @js($facultySubject->program),
    @js($facultySubject->curriculum_course_id),
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

<div style="grid-column:1 / -1;">

    <label>
        Curriculum <span style="color:#777;font-weight:normal;">(for new manual course)</span>
    </label>

    <select
        name="curriculum_id"
        id="facultyCurriculumSelect"
        style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;"
    >
        <option value="">Select curriculum</option>

        @foreach(($curricula ?? collect()) as $curriculum)

            <option
                value="{{ $curriculum->id }}"
                data-program="{{ $curriculum->program }}"
                data-major="{{ $curriculum->major ?? '' }}"
            >
                {{ $curriculum->curriculum_code }} — {{ $curriculum->program }}{{ $curriculum->department ? ' — ' . $curriculum->department->name : '' }}
                @if($curriculum->major)
                    • {{ $curriculum->major }}
                @endif
            </option>

        @endforeach
    </select>

    <small class="manual-help">
        Choose an active curriculum when adding a new course manually.
    </small>

</div>


<div style="grid-column:1 / -1;">

    <label>
        Search Course Code
    </label>

    <input
        type="text"
        id="facultyCourseSearch"
        placeholder="Select curriculum first, then enter IT 111"
        autocomplete="off"
        style="width:100%;padding:11px 12px;border:1px solid #cfd8d1;border-radius:8px;outline:none;"
    >

    <div
        id="facultyCourseSearchStatus"
        style="margin-top:6px;font-size:12px;color:#667085;min-height:18px;"
    >
        Select a curriculum first. Then type a course code such as IT 111.
    </div>

</div>


<div style="grid-column:1 / -1;">

    <label>
        Curriculum Course <span style="color:#777;font-weight:normal;">(Auto-selected)</span>
    </label>

    <select
        name="curriculum_course_id"
        id="curriculumCourseSelect"
        style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;"
    >
        <option value="">Manual Entry</option>

        @foreach(($curriculumCourses ?? collect()) as $course)

            <option
                value="{{ $course->id }}"
                data-curriculum-id="{{ $course->curriculum_id }}"
                data-course-code="{{ $course->course_code }}"
                data-subject="{{ $course->subject }}"
                data-year-level="{{ $course->year_level }}"
                data-program="{{ $course->curriculum->program ?? '' }}"
                data-major="{{ $course->curriculum->major ?? '' }}"
                data-units="{{ $course->units }}"
                data-semester="{{ $course->semester }}"
                data-subject-type="{{ $course->subject_type ?? '' }}"
                data-prerequisite="{{ $course->prerequisite ?? '' }}"
            >
                {{ $course->course_code }} — {{ $course->subject }} ({{ $course->year_level }}, {{ $course->semester }})
            </option>

        @endforeach

    </select>

    <small style="display:block;margin-top:6px;color:#667085;font-size:11px;">
        Select an existing curriculum course to assign it to this faculty member.
        Manual Entry creates a new course in the selected active curriculum and links it automatically.
    </small>

</div>


<div>

    <label>
        Course Code
    </label>

    <input
        type="text"
        name="course_code"
        id="facultyCourseCode"
        placeholder="IT 111"
        value="{{ old('course_code') }}"
        required
    >

    <small
        id="courseTypeHint"
        style="
            display:block;
            margin-top:6px;
            color:#777;
            font-size:12px;
        "
    >
    </small>

</div>


                                <div>

                                    <label>
                                        Course Description
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

<div>

    <label>
        Units
    </label>

    <input
        type="number"
        name="units"
        id="facultyUnits"
        step="0.5"
        min="0"
        value="{{ old('units', 3) }}"
        required
    >

</div>


<div>

    <label>
        Semester
    </label>

    <select
        name="semester"
        id="facultySemester"
        required
    >
        <option value="1st Semester" {{ old('semester', '1st Semester') == '1st Semester' ? 'selected' : '' }}>
            1st Semester
        </option>
        <option value="2nd Semester" {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>
            2nd Semester
        </option>
    </select>

</div>


<div>

    <label>
    Major <span style="color:#777;font-weight:normal;">(Optional)</span>
</label>

<input
    type="text"
    name="major"
    placeholder="Enter major if applicable"
    value="{{ old('major') }}"
>

</div>

<div>

    <label>
        Program
    </label>

    <input
        type="text"
        name="program"
        placeholder="BSIT"
        value="{{ old('program') }}"
        required
    >

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

                            <label>Curriculum Course (Optional)</label>

                            <select
                                id="editCurriculumCourse"
                                name="curriculum_course_id"
                                style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;"
                            >
                                <option value="">Manual Entry</option>
                                @foreach(($curriculumCourses ?? collect()) as $course)
                                    <option
                                        value="{{ $course->id }}"
                                        data-course-code="{{ $course->course_code }}"
                                        data-subject="{{ $course->subject }}"
                                        data-year-level="{{ $course->year_level }}"
                                        data-program="{{ $course->curriculum->program ?? '' }}"
                                        data-major="{{ $course->curriculum->major ?? '' }}"
                                        data-units="{{ $course->units }}"
                                        data-semester="{{ $course->semester }}"
                                    >
                                        {{ $course->course_code }} — {{ $course->subject }}
                                    </option>
                                @endforeach
                            </select>

                            <br><br>

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
                                Units
                            </label>

                            <input
                                type="number"
                                id="editSubjectUnits"
                                name="units"
                                step="0.5"
                                min="0"
                                value="3"
                                required
                            >

                            <br><br>

                            <label>
                                Semester
                            </label>

                            <select
                                id="editSubjectSemester"
                                name="semester"
                                required
                            >
                                <option value="1st Semester">
                                    1st Semester
                                </option>
                                <option value="2nd Semester">
                                    2nd Semester
                                </option>
                            </select>

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

<br><br>

<label>
    Program
</label>

<input
    type="text"
    id="editSubjectProgram"
    name="program"
    required
>

<label>
    Major <span style="color:#777;font-weight:normal;">(Optional)</span>
</label>

<input
    type="text"
    id="editSubjectMajor"
    name="major"
    placeholder="Enter major if applicable"
>

<br><br>


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

function fillFacultyCourse(option) {
    if (!option || !option.value) return;

    const code = option.dataset.courseCode || '';
    const subject = option.dataset.subject || '';
    const year = option.dataset.yearLevel || '';
    const program = option.dataset.program || '';
    const major = option.dataset.major || '';
    const curriculumId = option.dataset.curriculumId || '';
    const units = option.dataset.units || '';
    const semester = option.dataset.semester || '';

    const codeInput = document.getElementById('facultyCourseCode');
    const subjectInput = document.querySelector('input[name="subject"]');
    const yearInput = document.querySelector('select[name="year_level"]');
    const programInput = document.querySelector('input[name="program"]');
    const majorInput = document.querySelector('input[name="major"]');
    const curriculumInput = document.getElementById('facultyCurriculumSelect');
    const unitsInput = document.getElementById('facultyUnits');
    const semesterInput = document.getElementById('facultySemester');
    const searchInput = document.getElementById('facultyCourseSearch');
    const status = document.getElementById('facultyCourseSearchStatus');

    if (codeInput) codeInput.value = code;
    if (subjectInput) subjectInput.value = subject;
    if (yearInput) yearInput.value = year;
    if (programInput) programInput.value = program;
    if (majorInput) majorInput.value = major;
    if (curriculumInput && curriculumId) curriculumInput.value = curriculumId;
    if (unitsInput && units !== '') unitsInput.value = units;
    if (semesterInput && semester !== '') semesterInput.value = semester;
    if (searchInput) searchInput.value = code;

    if (status) {
        status.textContent = 'Course found: ' + code + ' — ' + subject;
        status.style.color = '#2e7d32';
    }

    updateCourseTypeHint();
}

function updateCourseTypeHint() {
    const input = document.getElementById('facultyCourseCode');
    const hint = document.getElementById('courseTypeHint');
    if (!input || !hint) return;

    const code = input.value.trim().toUpperCase();
    if (!code) {
        hint.textContent = 'Enter course code';
        hint.style.color = '#777';
        return;
    }

    hint.textContent = code.includes('.') ? 'Subject Type: LAB' : 'Subject Type: LEC';
    hint.style.color = code.includes('.') ? '#c62828' : '#2e7d32';
}

function findCourseByCode() {
    const curriculumSelect = document.getElementById('facultyCurriculumSelect');
    const courseSelect = document.getElementById('curriculumCourseSelect');
    const searchInput = document.getElementById('facultyCourseSearch');
    const status = document.getElementById('facultyCourseSearchStatus');

    if (!curriculumSelect || !courseSelect || !searchInput) return;

    const curriculumId = curriculumSelect.value;
    const typedCode = searchInput.value.trim().toUpperCase();

    if (!curriculumId) {
        if (status) {
            status.textContent = 'Select a curriculum first.';
            status.style.color = '#c62828';
        }
        return;
    }

    if (!typedCode) {
        courseSelect.value = '';
        if (status) {
            status.textContent = 'Type a course code such as IT 111.';
            status.style.color = '#667085';
        }
        return;
    }

    let exact = null;

    Array.from(courseSelect.options).forEach(option => {
        if (!option.value) return;
        const optionCurriculum = option.dataset.curriculumId || '';
        const optionCode = (option.dataset.courseCode || '').trim().toUpperCase();

        if (optionCurriculum === curriculumId && optionCode === typedCode) {
            exact = option;
        }
    });

    if (exact) {
        courseSelect.value = exact.value;
        fillFacultyCourse(exact);
        return;
    }

    courseSelect.value = '';
    const codeInput = document.getElementById('facultyCourseCode');
    const subjectInput = document.querySelector('input[name="subject"]');
    const yearInput = document.querySelector('select[name="year_level"]');
    const unitsInput = document.getElementById('facultyUnits');
    const semesterInput = document.getElementById('facultySemester');

    if (codeInput) codeInput.value = typedCode;
    if (subjectInput) subjectInput.value = '';
    if (yearInput) yearInput.value = '';
    if (unitsInput) unitsInput.value = '';
    if (semesterInput) semesterInput.value = '';

    if (status) {
        status.textContent = 'No course with code ' + typedCode + ' was found in the selected curriculum.';
        status.style.color = '#c62828';
    }

    updateCourseTypeHint();
}

function editSubject(id, courseCode, subject, yearLevel, major, program, curriculumCourseId) {
    document.getElementById('editSubjectCode').value = courseCode || '';
    document.getElementById('editSubjectName').value = subject || '';
    document.getElementById('editSubjectYear').value = yearLevel || '';
    document.getElementById('editSubjectProgram').value = program || '';
    document.getElementById('editSubjectMajor').value = major || '';

    const courseSelect = document.getElementById('editCurriculumCourse');
    if (courseSelect) {
        courseSelect.value = curriculumCourseId || '';
        if (courseSelect.value) {
            const option = courseSelect.options[courseSelect.selectedIndex];
            const unitsInput = document.getElementById('editSubjectUnits');
            const semesterInput = document.getElementById('editSubjectSemester');
            if (unitsInput) unitsInput.value = option.dataset.units || 3;
            if (semesterInput) semesterInput.value = option.dataset.semester || '1st Semester';
        }
    }

    document.getElementById('editSubjectForm').action = "{{ url('/chair/faculty/subjects') }}/" + id;
    document.getElementById('editSubjectModal').style.display = 'block';
}

function closeEditSubject() {
    const modal = document.getElementById('editSubjectModal');
    if (modal) modal.style.display = 'none';
}

window.addEventListener('click', function(event) {
    const modal = document.getElementById('editSubjectModal');
    if (modal && event.target === modal) closeEditSubject();
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('facultyCourseSearch');
    const curriculumSelect = document.getElementById('facultyCurriculumSelect');
    const courseSelect = document.getElementById('curriculumCourseSelect');
    const codeInput = document.getElementById('facultyCourseCode');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            findCourseByCode();
        });

        searchInput.addEventListener('blur', function() {
            findCourseByCode();
        });
    }

    if (curriculumSelect) {
        curriculumSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const programInput = document.querySelector('input[name="program"]');
            const majorInput = document.querySelector('input[name="major"]');
            const status = document.getElementById('facultyCourseSearchStatus');

            if (programInput) programInput.value = option?.dataset.program || '';
            if (majorInput) majorInput.value = option?.dataset.major || '';

            if (searchInput) searchInput.value = '';
            if (courseSelect) courseSelect.value = '';

            if (status) {
                status.textContent = this.value
                    ? 'Curriculum selected. Enter a course code such as IT 111.'
                    : 'Select a curriculum first.';
                status.style.color = '#667085';
            }
        });
    }

    if (courseSelect) {
        courseSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option && option.value) fillFacultyCourse(option);
        });
    }

    if (codeInput) {
        codeInput.addEventListener('input', updateCourseTypeHint);
    }

    const editCurriculumCourse = document.getElementById('editCurriculumCourse');
    if (editCurriculumCourse) {
        editCurriculumCourse.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (!option || !option.value) return;

            document.getElementById('editSubjectCode').value = option.dataset.courseCode || '';
            document.getElementById('editSubjectName').value = option.dataset.subject || '';
            document.getElementById('editSubjectYear').value = option.dataset.yearLevel || '';
            document.getElementById('editSubjectProgram').value = option.dataset.program || '';
            document.getElementById('editSubjectMajor').value = option.dataset.major || '';
            document.getElementById('editSubjectUnits').value = option.dataset.units || 3;
            document.getElementById('editSubjectSemester').value = option.dataset.semester || '1st Semester';
        });
    }
});


// Display success/error/validation feedback as a centered modal instead of inline boxes.
document.addEventListener('DOMContentLoaded', function () {
    const messages = Array.from(document.querySelectorAll('.page .message'));
    if (document.getElementById('globalMessageOverlay')) { messages.forEach(message => message.remove()); return; }
    if (!messages.length) return;
    const overlay = document.createElement('div');
    overlay.className = 'faculty-feedback-overlay';
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-modal', 'true');
    const dialog = document.createElement('div');
    dialog.className = 'faculty-feedback-dialog' + (messages.some(m => m.classList.contains('error')) ? ' error' : '');
    const heading = document.createElement('h2');
    heading.textContent = messages.some(m => m.classList.contains('error')) ? 'Please Check' : 'Successful!';
    const content = document.createElement('div');
    messages.forEach(message => { content.appendChild(message.cloneNode(true)); message.remove(); });
    const close = document.createElement('button');
    close.type = 'button'; close.textContent = 'OK';
    close.addEventListener('click', () => overlay.remove());
    dialog.append(heading, content, close); overlay.appendChild(dialog); document.body.appendChild(overlay);
    overlay.addEventListener('click', event => { if (event.target === overlay) overlay.remove(); });
    document.addEventListener('keydown', function onEscape(event) { if (event.key === 'Escape') { overlay.remove(); document.removeEventListener('keydown', onEscape); } });
});

</script>


        </main>

    </div>

    

</div>

@include('footerheader.footer')