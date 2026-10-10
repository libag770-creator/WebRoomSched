@include('footerheader.header')

<div class="wrapper">

    <div class="main-layout">

        {{-- =========================================================
             CHAIR SIDEBAR
        ========================================================== --}}

        @include('chair.sidebar')


        {{-- =========================================================
             MAIN CONTENT
        ========================================================== --}}

        <main class="content">

            <style>
                * {
                    box-sizing: border-box;
                }

                body {
                    background: #f5f7f5;
                    margin: 0;
                }

                .academic-library-page {
                    width: 100%;
                    max-width: 1500px;
                    margin: 0 auto;
                    padding: 18px 24px 45px;
                }


                /* =====================================================
                   PAGE HEADER
                ===================================================== */

                .academic-library-header {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 20px;
                    margin-bottom: 20px;
                }

                .academic-library-title {
                    margin: 0;
                    font-size: 28px;
                    line-height: 1.15;
                    font-weight: 800;
                    color: #183b20;
                }

                .academic-library-subtitle {
                    margin: 6px 0 0;
                    color: #718078;
                    font-size: 13px;
                }

                .active-term-badge {
                    flex: 0 0 auto;
                    background: #eaf6ec;
                    border: 1px solid #b9dabf;
                    color: #1d6c2a;
                    border-radius: 10px;
                    padding: 10px 15px;
                    font-size: 13px;
                    font-weight: 700;
                    white-space: nowrap;
                }


                /* =====================================================
                   ALERTS
                ===================================================== */

                .library-message {
                    width: 100%;
                    padding: 11px 14px;
                    margin-bottom: 15px;
                    border-radius: 9px;
                    font-size: 12px;
                    line-height: 1.4;
                }

                .library-message.success {
                    background: #eaf7ec;
                    border: 1px solid #c4e3c9;
                    color: #216b2d;
                }

                .library-message.error {
                    background: #fff0f0;
                    border: 1px solid #efc9c9;
                    color: #9c3030;
                }


                /* =====================================================
                   MAIN TWO COLUMN LAYOUT
                ===================================================== */

                .academic-library-grid {
                    display: grid;
                    grid-template-columns: 285px minmax(0, 1fr);
                    gap: 16px;
                    align-items: start;
                }


                /* =====================================================
                   LEFT SIDEBAR PANEL
                ===================================================== */

                .library-sidebar {
                    background: #ffffff;
                    border: 1px solid #dce7df;
                    border-radius: 15px;
                    box-shadow: 0 4px 15px rgba(30, 70, 35, .06);
                    overflow: hidden;
                }

                .sidebar-block {
                    padding: 15px;
                    border-bottom: 1px solid #edf1ee;
                }

                .sidebar-block:last-child {
                    border-bottom: 0;
                }

                .sidebar-heading {
                    margin: 0 0 12px;
                    color: #183b20;
                    font-size: 15px;
                    font-weight: 800;
                }


                /* =====================================================
                   CREATE TERM FORM
                ===================================================== */

                .term-create-form {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }

                .term-create-fields {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 8px;
                }

                .term-field {
                    min-width: 0;
                }

                .term-field label {
                    display: block;
                    margin-bottom: 5px;
                    font-size: 10px;
                    font-weight: 800;
                    color: #5d6861;
                    text-transform: uppercase;
                    letter-spacing: .03em;
                }

                .term-field input,
                .term-field select {
                    width: 100%;
                    min-height: 37px;
                    padding: 8px 9px;
                    border: 1px solid #cbd6cf;
                    border-radius: 8px;
                    outline: none;
                    background: #ffffff;
                    color: #1f2937;
                    font-size: 12px;
                }

                .term-field input:focus,
                .term-field select:focus {
                    border-color: #2e7d32;
                    box-shadow: 0 0 0 3px rgba(46, 125, 50, .08);
                }

                .active-checkbox {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    color: #59645d;
                    font-size: 12px;
                }

                .active-checkbox input {
                    width: 14px;
                    height: 14px;
                    accent-color: #2e7d32;
                }


                /* =====================================================
                   BUTTONS
                ===================================================== */

                .library-btn {
                    min-height: 35px;
                    border-radius: 8px;
                    padding: 8px 11px;
                    border: 0;
                    cursor: pointer;
                    font-size: 11px;
                    font-weight: 800;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    transition: .15s ease;
                    white-space: nowrap;
                }

                .library-btn-green {
                    background: #2e7d32;
                    color: #ffffff;
                }

                .library-btn-green:hover {
                    background: #256a2a;
                    color: #ffffff;
                }

                .library-btn-soft {
                    background: #edf7ef;
                    border: 1px solid #c9e2cd;
                    color: #267232;
                }

                .library-btn-soft:hover {
                    background: #e2f2e5;
                    color: #21672a;
                }

                .library-btn-outline {
                    background: #ffffff;
                    border: 1px solid #b9d7bd;
                    color: #277333;
                }

                .library-btn-outline:hover {
                    background: #f2faf3;
                }

                .library-btn-gray {
                    background: #f4f6f5;
                    border: 1px solid #e0e4e1;
                    color: #6a746e;
                    cursor: default;
                }

                .full-width {
                    width: 100%;
                }


                /* =====================================================
                   SAVED TERMS
                ===================================================== */

                .term-list {
                    display: flex;
                    flex-direction: column;
                    gap: 9px;
                    max-height: 680px;
                    overflow-y: auto;
                    padding-right: 2px;
                }

                .term-item {
                    border: 1px solid #dfe8e2;
                    background: #fbfdfb;
                    border-radius: 11px;
                    padding: 10px;
                }

                .term-item.active-term {
                    background: #f3fbf4;
                    border-color: #8dc897;
                }

                .term-item-header {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 8px;
                }

                .term-name {
                    color: #183b20;
                    font-size: 13px;
                    font-weight: 800;
                    line-height: 1.35;
                }

                .term-name .term-dot {
                    color: #7a877f;
                    padding: 0 3px;
                }

                .term-count {
                    margin-top: 4px;
                    color: #7b8580;
                    font-size: 10px;
                }

                .term-status {
                    flex-shrink: 0;
                    padding: 4px 7px;
                    border-radius: 999px;
                    font-size: 8px;
                    font-weight: 900;
                    text-transform: uppercase;
                }

                .term-status.active {
                    background: #dff2e2;
                    color: #22702c;
                }

                .term-status.saved {
                    background: #eef1ef;
                    color: #69746e;
                }

                .term-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 6px;
                    margin-top: 9px;
                }

                .term-actions form {
                    width: 100%;
                    margin: 0;
                }

                .term-actions .library-btn {
                    width: 100%;
                }


                /* =====================================================
                   REUSE PANEL
                ===================================================== */

                .reuse-panel {
                    margin-top: 9px;
                    padding: 10px;
                    background: #fff9e9;
                    border: 1px solid #efdda7;
                    border-radius: 10px;
                }

                .reuse-title {
                    margin: 0 0 8px;
                    color: #705200;
                    font-size: 11px;
                    font-weight: 900;
                }

                .reuse-form {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 7px;
                }

                .reuse-form .term-field input,
                .reuse-form .term-field select {
                    min-height: 34px;
                    font-size: 11px;
                }

                .reuse-full {
                    grid-column: 1 / -1;
                }

                .reuse-note {
                    margin-top: 7px;
                    color: #8b7444;
                    font-size: 9px;
                    line-height: 1.45;
                }


                /* =====================================================
                   RIGHT PREVIEW PANEL
                ===================================================== */

                .library-preview {
                    min-width: 0;
                    min-height: 640px;
                    background: #ffffff;
                    border: 1px solid #dce7df;
                    border-radius: 15px;
                    box-shadow: 0 4px 15px rgba(30, 70, 35, .06);
                    overflow: hidden;
                }

                .preview-header {
                    padding: 16px 18px 14px;
                    background: linear-gradient(
                        to bottom,
                        #ffffff,
                        #fbfdfb
                    );
                    border-bottom: 1px solid #e7ede9;
                }

                .preview-header-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 15px;
                }

                .preview-title {
                    margin: 0;
                    color: #183b20;
                    font-size: 17px;
                    font-weight: 800;
                }

                .preview-description {
                    margin: 4px 0 0;
                    color: #7d8780;
                    font-size: 10px;
                }

                .preview-count {
                    flex-shrink: 0;
                    padding: 8px 10px;
                    border-radius: 8px;
                    background: #eef7ef;
                    border: 1px solid #cbe2ce;
                    color: #287232;
                    font-size: 10px;
                    font-weight: 800;
                    white-space: nowrap;
                }

                .preview-body {
                    padding: 14px;
                }


                /* =====================================================
                   NO SELECTION
                ===================================================== */

                .empty-preview {
                    min-height: 500px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    padding: 30px;
                }

                .empty-preview-box {
                    max-width: 430px;
                    color: #78837d;
                    font-size: 12px;
                    line-height: 1.6;
                }

                .empty-preview-icon {
                    width: 55px;
                    height: 55px;
                    margin: 0 auto 11px;
                    border-radius: 50%;
                    background: #edf7ef;
                    color: #2e7d32;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    font-weight: 800;
                }

                .empty-preview-title {
                    margin-bottom: 5px;
                    color: #31513a;
                    font-size: 16px;
                    font-weight: 800;
                }


                /* =====================================================
                   SCHEDULE META
                ===================================================== */

                .schedule-meta {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 10px;
                    margin-bottom: 10px;
                    color: #7a847f;
                    font-size: 10px;
                }

                .schedule-meta strong {
                    color: #31733a;
                }


                /* =====================================================
                   TABLE
                ===================================================== */

                .schedule-table-wrapper {
                    width: 100%;
                    overflow-x: auto;
                    border: 1px solid #dce5df;
                    border-radius: 10px;
                }

                .schedule-table {
                    width: 100%;
                    min-width: 880px;
                    border-collapse: collapse;
                    table-layout: fixed;
                }

                .schedule-table th,
                .schedule-table td {
                    padding: 8px 9px;
                    border-bottom: 1px solid #e5ebe7;
                    text-align: left;
                    vertical-align: middle;
                    font-size: 10px;
                    white-space: nowrap;
                }

                .schedule-table th {
                    background: #f1f7f2;
                    color: #47544c;
                    font-size: 9px;
                    font-weight: 900;
                    text-transform: uppercase;
                    letter-spacing: .03em;
                }

                .schedule-table tr:last-child td {
                    border-bottom: 0;
                }

                .schedule-table tbody tr:hover {
                    background: #f9fcfa;
                }

                .schedule-course {
                    color: #23712d;
                    font-weight: 900;
                }

                .schedule-subject {
                    color: #344054;
                }

                .no-schedule-records {
                    color: #6e7973;
                    font-size: 11px;
                }


                /* =====================================================
                   RESPONSIVE
                ===================================================== */

                @media (max-width: 1050px) {

                    .academic-library-grid {
                        grid-template-columns: 250px minmax(0, 1fr);
                    }

                    .academic-library-page {
                        padding-left: 18px;
                        padding-right: 18px;
                    }

                }


                @media (max-width: 900px) {

                    .academic-library-grid {
                        grid-template-columns: 1fr;
                    }

                    .term-list {
                        max-height: none;
                    }

                    .library-preview {
                        min-height: 500px;
                    }

                }


                @media (max-width: 650px) {

                    .academic-library-page {
                        padding: 12px;
                    }

                    .academic-library-header {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .active-term-badge {
                        width: 100%;
                        text-align: center;
                        white-space: normal;
                    }

                    .preview-header-row {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .preview-count {
                        text-align: center;
                    }

                    .term-create-fields {
                        grid-template-columns: 1fr;
                    }

                    .reuse-form {
                        grid-template-columns: 1fr;
                    }

                    .reuse-full {
                        grid-column: auto;
                    }

                    .term-actions {
                        grid-template-columns: 1fr;
                    }

                }

            </style>


            {{-- =========================================================
                 PAGE
            ========================================================== --}}

            <div class="academic-library-page">


                {{-- =====================================================
                     PAGE HEADER
                ====================================================== --}}

                <div class="academic-library-header">

                    <div>

                        <h1 class="academic-library-title">
                            Schedule Library
                        </h1>

                        <p class="academic-library-subtitle">
                            Save, view, activate, and reuse complete semester schedules.
                        </p>

                    </div>


                    <div class="active-term-badge">

                        Active Term:

                        {{ $activeTerm->academic_year }}

                        •

                        {{ $activeTerm->semester }}

                    </div>

                </div>


                {{-- =====================================================
                     SUCCESS MESSAGE
                ====================================================== --}}

                @if(session('success'))

                    <div class="library-message success">

                        {{ session('success') }}

                    </div>

                @endif


                {{-- =====================================================
                     ERROR MESSAGE
                ====================================================== --}}

                @if(session('error'))

                    <div class="library-message error">

                        {{ session('error') }}

                    </div>

                @endif


                {{-- =====================================================
                     VALIDATION ERRORS
                ====================================================== --}}

                @if($errors->any())

                    <div class="library-message error">

                        {{ $errors->first() }}

                    </div>

                @endif


                {{-- =====================================================
                     TWO COLUMN AREA
                ====================================================== --}}

                <div class="academic-library-grid">


                    {{-- =================================================
                         LEFT
                    ================================================== --}}

                    <aside class="library-sidebar">


                        {{-- =================================================
                             CREATE TERM
                        ================================================== --}}

                        <div class="sidebar-block">

                            <h2 class="sidebar-heading">
                                Create Academic Term
                            </h2>


                            <form
                                method="POST"
                                action="{{ route('chair.academic.terms.store') }}"
                                class="term-create-form"
                            >

                                @csrf


                                <div class="term-create-fields">


                                    {{-- ACADEMIC YEAR --}}

                                    <div class="term-field">

                                        <label for="academic_year">
                                            Academic Year
                                        </label>

                                        <input
                                            id="academic_year"
                                            type="text"
                                            name="academic_year"
                                            placeholder="2027-2028"
                                            value="{{ old('academic_year') }}"
                                            required
                                        >

                                    </div>


                                    {{-- SEMESTER --}}

                                    <div class="term-field">

                                        <label for="semester">
                                            Semester
                                        </label>

                                        <select
                                            id="semester"
                                            name="semester"
                                            required
                                        >

                                            <option
                                                value="1st Semester"
                                                {{ old('semester', '1st Semester') === '1st Semester' ? 'selected' : '' }}
                                            >
                                                1st Semester
                                            </option>

                                            <option
                                                value="2nd Semester"
                                                {{ old('semester') === '2nd Semester' ? 'selected' : '' }}
                                            >
                                                2nd Semester
                                            </option>

                                        </select>

                                    </div>

                                </div>


                                {{-- MAKE ACTIVE --}}

                                <label class="active-checkbox">

                                    <input
                                        type="checkbox"
                                        name="make_active"
                                        value="1"
                                    >

                                    <span>
                                        Make this the active term
                                    </span>

                                </label>


                                {{-- CREATE --}}

                                <button
                                    type="submit"
                                    class="library-btn library-btn-green full-width"
                                >
                                    + Create Term
                                </button>

                            </form>

                        </div>


                        {{-- =================================================
                             SAVED TERMS
                        ================================================== --}}

                        <div class="sidebar-block">

                            <h2 class="sidebar-heading">
                                Saved Academic Terms
                            </h2>


                            <div class="term-list">


                                @forelse($terms as $term)


                                    <div
                                        class="
                                            term-item
                                            {{ (int) $activeTerm->id === (int) $term->id
                                                ? 'active-term'
                                                : ''
                                            }}
                                        "
                                    >


                                        {{-- =================================
                                             TERM HEADER
                                        ================================== --}}

                                        <div class="term-item-header">

                                            <div>

                                                <div class="term-name">

                                                    {{ $term->academic_year }}

                                                    <span class="term-dot">
                                                        •
                                                    </span>

                                                    {{ $term->semester }}

                                                </div>


                                                <div class="term-count">

                                                    {{ (int) $term->schedule_count }}

                                                    schedule record(s)

                                                </div>

                                            </div>


                                            {{-- STATUS --}}

                                            <span
                                                class="
                                                    term-status
                                                    {{ $term->is_active
                                                        ? 'active'
                                                        : 'saved'
                                                    }}
                                                "
                                            >

                                                {{ $term->is_active
                                                    ? 'Active'
                                                    : 'Saved'
                                                }}

                                            </span>

                                        </div>


                                        {{-- =================================
                                             VIEW / ACTIVE BUTTONS
                                        ================================== --}}

                                        <div class="term-actions">


                                            {{-- VIEW --}}

                                            <a
                                                href="{{ route(
                                                    'chair.schedule.library',
                                                    ['view' => $term->id]
                                                ) }}"
                                                class="
                                                    library-btn
                                                    library-btn-soft
                                                "
                                            >

                                                ◉ View

                                            </a>


                                            {{-- SET ACTIVE --}}

                                            @if(
                                                (int) $activeTerm->id !==
                                                (int) $term->id
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'chair.academic.terms.activate',
                                                        $term->id
                                                    ) }}"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="
                                                            library-btn
                                                            library-btn-green
                                                        "
                                                    >

                                                        ✓ Set Active

                                                    </button>

                                                </form>

                                            @else

                                                <div
                                                    class="
                                                        library-btn
                                                        library-btn-gray
                                                    "
                                                >

                                                    ✓ Active

                                                </div>

                                            @endif

                                        </div>


                                        {{-- =================================
                                             REUSE
                                        ================================== --}}

                                        <div class="reuse-panel">

                                            <div class="reuse-title">

                                                Reuse this schedule

                                            </div>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'chair.schedule.library.reuse',
                                                    $term->id
                                                ) }}"
                                                class="reuse-form"
                                            >

                                                @csrf


                                                {{-- TARGET YEAR --}}

                                                <div class="term-field">

                                                    <label>
                                                        New Academic Year
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="target_academic_year"
                                                        placeholder="2027-2028"
                                                        required
                                                    >

                                                </div>


                                                {{-- TARGET SEMESTER --}}

                                                <div class="term-field">

                                                    <label>
                                                        Target Semester
                                                    </label>

                                                    <select
                                                        name="target_semester"
                                                        required
                                                    >

                                                        <option value="1st Semester">
                                                            1st Semester
                                                        </option>

                                                        <option value="2nd Semester">
                                                            2nd Semester
                                                        </option>

                                                    </select>

                                                </div>


                                                {{-- COPY BUTTON --}}

                                                <button
                                                    type="submit"
                                                    class="
                                                        library-btn
                                                        library-btn-outline
                                                        reuse-full
                                                    "
                                                >

                                                    Copy as New Schedule

                                                </button>

                                            </form>


                                            <div class="reuse-note">

                                                The source schedule stays unchanged.
                                                The copied schedule becomes a new
                                                editable academic term.

                                            </div>

                                        </div>


                                    </div>


                                @empty


                                    <div
                                        style="
                                            padding: 6px 0;
                                            color: #7b8580;
                                            font-size: 11px;
                                        "
                                    >

                                        No academic terms have been created yet.

                                    </div>


                                @endforelse


                            </div>

                        </div>

                    </aside>


                    {{-- =================================================
                         RIGHT
                    ================================================== --}}

                    <section class="library-preview">


                        {{-- =================================================
                             PREVIEW HEADER
                        ================================================== --}}

                        <div class="preview-header">

                            <div class="preview-header-row">


                                <div>

                                    @if($selectedTerm)

                                        <h2 class="preview-title">

                                            {{ $selectedTerm->academic_year }}

                                            •

                                            {{ $selectedTerm->semester }}

                                        </h2>

                                        <p class="preview-description">

                                            @if($selectedTerm->is_active)

                                                Active schedule

                                            @else

                                                Historical schedule

                                            @endif

                                            — view only.

                                        </p>

                                    @else

                                        <h2 class="preview-title">

                                            Schedule Preview

                                        </h2>

                                        <p class="preview-description">

                                            Select a saved academic term and click View.

                                        </p>

                                    @endif

                                </div>


                                {{-- COUNT --}}

                                @if($selectedTerm)

                                    <div class="preview-count">

                                        {{ (int) $selectedSchedules->count() }}

                                        schedule record(s)

                                    </div>

                                @endif


                            </div>

                        </div>


                        {{-- =================================================
                             PREVIEW BODY
                        ================================================== --}}

                        <div class="preview-body">


                            {{-- =================================================
                                 NO TERM SELECTED
                            ================================================== --}}

                            @if(!$selectedTerm)


                                <div class="empty-preview">

                                    <div class="empty-preview-box">

                                        <div class="empty-preview-icon">
                                            ▦
                                        </div>

                                        <div class="empty-preview-title">

                                            No term selected

                                        </div>

                                        <div>

                                            Click
                                            <strong>View</strong>
                                            beside an academic term to open
                                            its schedule.

                                        </div>

                                    </div>

                                </div>


                            {{-- =================================================
                                 EMPTY TERM
                            ================================================== --}}

                            @elseif($selectedSchedules->isEmpty())


                                <div class="empty-preview">

                                    <div class="empty-preview-box">

                                        <div class="empty-preview-icon">
                                            ✓
                                        </div>

                                        <div class="empty-preview-title">

                                            Empty Schedule Sheet

                                        </div>

                                        <div>

                                            This academic term has no schedules yet.

                                            It is ready for a new schedule.

                                            Old schedules from another academic
                                            term are not displayed here.

                                        </div>

                                    </div>

                                </div>


                            {{-- =================================================
                                 SCHEDULE FOUND
                            ================================================== --}}

                            @else


                                <div class="schedule-meta">

                                    <div>

                                        Showing schedules for:

                                        <strong>
                                            {{ $selectedTerm->academic_year }}
                                            •
                                            {{ $selectedTerm->semester }}
                                        </strong>

                                    </div>

                                    <div>

                                        Term ID:
                                        <strong>
                                            {{ $selectedTerm->id }}
                                        </strong>

                                    </div>

                                </div>


                                <div class="schedule-table-wrapper">

                                    <table class="schedule-table">

                                        <thead>

                                            <tr>

                                                <th>ROOM</th>

                                                <th>BUILDING</th>

                                                <th>DAY</th>

                                                <th>TIME</th>

                                                <th>COURSE</th>

                                                <th>SUBJECT</th>

                                                <th>INSTRUCTOR</th>

                                                <th>PROGRAM</th>

                                            </tr>

                                        </thead>


                                        <tbody>


                                            @foreach(
                                                $selectedSchedules
                                                ->sortBy(function ($schedule) {
                                                    return [
                                                        (int) ($schedule->room_id ?? 0),
                                                        (int) ($schedule->row_position ?? 99999),
                                                        strtoupper((string) ($schedule->day ?? '')),
                                                        (string) ($schedule->time ?? '')
                                                    ];
                                                })
                                                as $schedule
                                            )


                                                <tr>


                                                    {{-- ROOM --}}

                                                    <td>

                                                        {{
                                                            $schedule->room->room_name
                                                            ?? $schedule->room->name
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- BUILDING --}}

                                                    <td>

                                                        {{
                                                            $schedule->room->building->name
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- DAY --}}

                                                    <td>

                                                        {{
                                                            $schedule->day
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- TIME --}}

                                                    <td>

                                                        {{
                                                            $schedule->time
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- COURSE --}}

                                                    <td class="schedule-course">

                                                        {{
                                                            $schedule->course_code
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- SUBJECT --}}

                                                    <td class="schedule-subject">

                                                        {{
                                                            $schedule->subject
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- INSTRUCTOR --}}

                                                    <td>

                                                        {{
                                                            $schedule->faculty->name
                                                            ?? $schedule->instructor
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- PROGRAM --}}

                                                    <td>

                                                        {{
                                                            $schedule->program
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                </tr>


                                            @endforeach


                                        </tbody>

                                    </table>

                                </div>


                            @endif


                        </div>

                    </section>

                </div>

            </div>


        </main>

    </div>



    @include('footerheader.footer')

</div>