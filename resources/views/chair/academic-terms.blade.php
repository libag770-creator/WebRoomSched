@include('footerheader.header')

<style>
    /* Academic terms: a readable, branded workspace consistent with the chair dashboard. */
    body.portal-shell.chair-portal { --chair-sidebar-width:252px; --chair-shell-gutter:28px; background:#e9eee8; }
    body.portal-shell.chair-portal .wrapper { min-height:100vh; background:#e9eee8; }
    body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); align-items:stretch; min-height:100vh; }
    body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:sticky; top:0; align-self:start; width:var(--chair-sidebar-width); height:100vh; min-height:100vh; padding:25px 15px 18px; border:0; background:linear-gradient(165deg,#112f29 0%,#173b33 54%,#285342 100%); color:#f7f5eb; box-shadow:12px 0 32px rgba(20,47,37,.1); }
    body.portal-shell.chair-portal .top-header { position:fixed; top:16px; right:var(--chair-shell-gutter); left:calc(var(--chair-sidebar-width) + var(--chair-shell-gutter)); z-index:1000; min-height:72px; padding:10px 15px 10px 22px; border:1px solid rgba(224,231,224,.96); border-radius:18px; background:rgba(255,255,255,.95); box-shadow:0 12px 34px rgba(31,57,43,.09); backdrop-filter:blur(18px); }
    body.portal-shell.chair-portal .header-title small { color:#9a8450; font-size:11px; }
    body.portal-shell.chair-portal .header-title h3 { color:#173b33; font-size:20px; }
    body.portal-shell.chair-portal .header-action { color:#375448; }
    body.portal-shell.chair-portal .sidebar-toggle { top:36px; left:calc(var(--chair-sidebar-width) - 15px); z-index:1200; border-color:rgba(216,193,140,.52); background:#f8f5eb; color:#224336; }
    body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
    body.portal-shell.chair-portal.sidebar-collapsed .main-layout { grid-template-columns:76px minmax(0,1fr); }
    body.portal-shell.chair-portal.sidebar-collapsed .main-layout > .chair-sidebar { width:76px; padding-right:10px; padding-left:10px; }
    body.portal-shell.chair-portal.sidebar-collapsed .sidebar-toggle { left:61px; }
    body.portal-shell.chair-portal .main-layout > .content.academic-terms-view { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }

    .academic-terms-view .academic-library-page { width:min(100%,1480px); margin:0 auto; }
    .academic-terms-view .academic-library-hero { position:relative; display:flex; align-items:center; justify-content:space-between; gap:24px; min-height:220px; margin:0 0 22px; padding:clamp(28px,4vw,43px); overflow:hidden; border:1px solid rgba(231,214,173,.22); border-radius:24px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); color:#fff; }
    .academic-terms-view .academic-library-hero::after { position:absolute; right:-50px; bottom:-160px; width:330px; height:330px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .academic-terms-view .academic-library-hero-copy { position:relative; z-index:1; max-width:740px; }
    .academic-terms-view .academic-library-eyebrow { display:block; margin-bottom:12px; color:#e2c987; font-size:11px; font-weight:800; letter-spacing:.15em; }
    .academic-terms-view .academic-library-title { margin:0 0 11px; color:#fff; font:800 clamp(32px,3.2vw,44px)/1.12 var(--wr-display); letter-spacing:-.045em; }
    .academic-terms-view .academic-library-subtitle { margin:0; color:#e1e8df; font-size:15px; font-weight:600; line-height:1.6; }
    .academic-terms-view .active-term-badge { position:relative; z-index:1; display:grid; gap:7px; min-width:230px; padding:17px 19px; border:1px solid rgba(255,255,255,.23); border-radius:15px; background:rgba(255,255,255,.1); }
    .academic-terms-view .active-term-badge > span { color:#e2c987; font-size:10px; font-weight:800; letter-spacing:.14em; }
    .academic-terms-view .active-term-badge strong { color:#fff; font-size:15px; font-weight:800; line-height:1.4; }
    .academic-terms-view .academic-library-hero-mark { position:absolute; right:28%; color:#f5e8c4; font-size:140px; opacity:.07; }
    .academic-terms-view .library-message { margin:0 0 15px; padding:14px 17px; border:1px solid; border-radius:12px; font-size:14px; font-weight:700; line-height:1.5; }
    .academic-terms-view .library-message.success { border-color:#b9d9bd; background:#edf7ee; color:#28603a; }
    .academic-terms-view .library-message.error { border-color:#edc8c2; background:#fff3f1; color:#8f3e36; }

    .academic-terms-view .academic-library-grid { display:grid; grid-template-columns:minmax(330px,.88fr) minmax(0,1.45fr); align-items:start; gap:17px; }
    .academic-terms-view .library-sidebar,.academic-terms-view .library-preview { min-width:0; overflow:hidden; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .academic-terms-view .library-sidebar { display:grid; gap:0; }
    .academic-terms-view .sidebar-block { padding:20px; border-bottom:1px solid #edf0eb; }
    .academic-terms-view .sidebar-block:last-child { border-bottom:0; }
    .academic-terms-view .sidebar-heading { margin:0 0 15px; color:#173b33; font-size:19px; font-weight:800; letter-spacing:-.025em; }
    .academic-terms-view .term-create-form,.academic-terms-view .term-create-fields { display:grid; gap:13px; }
    .academic-terms-view .term-create-fields { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .academic-terms-view .term-field { display:grid; min-width:0; gap:6px; }
    .academic-terms-view .term-field label { color:#43594d; font-size:12px; font-weight:800; }
    .academic-terms-view .term-field input,.academic-terms-view .term-field select { width:100%; min-height:43px; padding:0 12px; border:1px solid #d7e0d7; border-radius:10px; background:#fff; color:#273f32; font:600 13px/1.35 var(--wr-body); }
    .academic-terms-view .term-field input::placeholder { color:#929d95; font-weight:500; }
    .academic-terms-view .term-field input:focus,.academic-terms-view .term-field select:focus { outline:3px solid rgba(49,104,76,.15); border-color:#648a70; }
    .academic-terms-view .active-checkbox { display:flex; align-items:center; gap:9px; color:#465b4e; font-size:13px; font-weight:700; line-height:1.4; }
    .academic-terms-view .active-checkbox input { width:17px; height:17px; accent-color:#285940; }
    .academic-terms-view .library-btn { display:inline-flex; align-items:center; justify-content:center; gap:7px; min-height:39px; padding:0 12px; border:1px solid transparent; border-radius:9px; font:800 12px/1.2 var(--wr-body); text-decoration:none; white-space:nowrap; cursor:pointer; transition:transform .16s ease,background .16s ease,border-color .16s ease; }
    .academic-terms-view .library-btn:hover { transform:translateY(-1px); }
    .academic-terms-view .library-btn-green { border-color:#24543e; background:#24543e; color:#fff; }
    .academic-terms-view .library-btn-green:hover { background:#173b33; }
    .academic-terms-view .library-btn-soft { border-color:#dce6dc; background:#f5f8f3; color:#315b43; }
    .academic-terms-view .library-btn-gray { border-color:#e3e8e3; background:#eef1ed; color:#5d6b62; }
    .academic-terms-view .library-btn-outline { border-color:#9cb6a1; background:#fff; color:#285940; }
    .academic-terms-view .library-btn-outline:hover { background:#f1f7f1; }
    .academic-terms-view .library-btn-danger { border-color:#ecd2ce; background:#fff8f7; color:#9b4c43; }
    .academic-terms-view .full-width { width:100%; min-height:44px; font-size:13px; }
    .academic-terms-view .term-list { display:grid; gap:12px; }
    .academic-terms-view .term-item { min-width:0; padding:15px; border:1px solid #e2e9e1; border-radius:14px; background:#fff; }
    .academic-terms-view .term-item.active-term { border-color:#b6cdb8; background:linear-gradient(135deg,#f8fbf7,#fff); box-shadow:inset 3px 0 #356a4d; }
    .academic-terms-view .term-item-header { display:flex; align-items:flex-start; justify-content:space-between; gap:11px; }
    .academic-terms-view .term-name { color:#203d30; font-size:14px; font-weight:800; line-height:1.45; }
    .academic-terms-view .term-dot { padding:0 3px; color:#a58c53; }
    .academic-terms-view .term-count { margin-top:5px; color:#68776d; font-size:12px; font-weight:600; }
    .academic-terms-view .term-status { display:inline-flex; align-items:center; min-height:25px; padding:0 9px; border-radius:99px; font-size:11px; font-weight:800; }
    .academic-terms-view .term-status.active { background:#e8f3e8; color:#326447; }
    .academic-terms-view .term-status.saved { background:#f1f3ef; color:#65736a; }
    .academic-terms-view .term-actions { display:flex; flex-wrap:wrap; gap:7px; margin-top:13px; }
    .academic-terms-view .reuse-panel { display:grid; gap:11px; margin-top:14px; padding:13px; border:1px solid #e5eadf; border-radius:12px; background:#f8f9f5; }
    .academic-terms-view .reuse-title { color:#304a3b; font-size:13px; font-weight:800; }
    .academic-terms-view .reuse-form { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); align-items:end; gap:10px; }
    .academic-terms-view .reuse-form .reuse-full { grid-column:1/-1; width:100%; }
    .academic-terms-view .reuse-note { color:#67756b; font-size:11px; font-weight:550; line-height:1.55; }

    .academic-terms-view .preview-header { padding:20px 22px; border-bottom:1px solid #e7ece5; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .academic-terms-view .preview-header-row { display:flex; align-items:center; justify-content:space-between; gap:16px; }
    .academic-terms-view .preview-title { margin:0 0 5px; color:#173b33; font-size:20px; font-weight:800; line-height:1.35; }
    .academic-terms-view .preview-description { margin:0; color:#596c60; font-size:13px; font-weight:600; line-height:1.5; }
    .academic-terms-view .preview-count { flex:0 0 auto; padding:9px 12px; border:1px solid #dfe8dc; border-radius:10px; background:#f1f6ef; color:#315b43; font-size:12px; font-weight:800; }
    .academic-terms-view .preview-body { min-height:300px; padding:18px; }
    .academic-terms-view .empty-preview { display:grid; min-height:260px; place-items:center; padding:20px; border:1px dashed #dce5dc; border-radius:14px; background:linear-gradient(145deg,#fafbf8,#f6f8f4); color:#617166; text-align:center; font-size:14px; font-weight:550; line-height:1.65; }
    .academic-terms-view .empty-preview-box { max-width:390px; }
    .academic-terms-view .empty-preview-icon { display:grid; width:52px; height:52px; place-items:center; margin:0 auto 12px; border:1px solid #e1e9df; border-radius:15px; background:#edf3eb; color:#35684a; font-size:22px; }
    .academic-terms-view .empty-preview-title { margin-bottom:6px; color:#294437; font-size:17px; font-weight:800; }
    .academic-terms-view .empty-preview strong { color:#285940; font-weight:800; }
    .academic-terms-view .schedule-meta { display:flex; flex-wrap:wrap; justify-content:space-between; gap:10px; margin-bottom:14px; padding:12px 14px; border:1px solid #e4eae1; border-radius:11px; background:#f8faf6; color:#5a6c60; font-size:12px; font-weight:600; }
    .academic-terms-view .schedule-meta strong { color:#2f493b; font-weight:800; }
    .academic-terms-view .schedule-table-wrapper { width:100%; overflow:auto; border:1px solid #e2e9e1; border-radius:12px; }
    .academic-terms-view .schedule-table { width:100%; min-width:950px; border-collapse:separate; border-spacing:0; background:#fff; }
    .academic-terms-view .schedule-table th { position:sticky; top:0; padding:13px 12px; border-bottom:1px solid #dfe7dd; background:#f3f6f1; color:#385344; text-align:left; font-size:11px; font-weight:800; letter-spacing:.04em; white-space:nowrap; }
    .academic-terms-view .schedule-table td { padding:13px 12px; border-bottom:1px solid #edf0eb; color:#34483c; vertical-align:top; font-size:12px; font-weight:600; line-height:1.45; }
    .academic-terms-view .schedule-table tbody tr:nth-child(even) { background:#fbfcfa; }
    .academic-terms-view .schedule-table tbody tr:hover { background:#f2f7f1; }
    .academic-terms-view .schedule-table tbody tr:last-child td { border-bottom:0; }
    .academic-terms-view .schedule-course { color:#285940 !important; font-weight:800 !important; }
    .academic-terms-view .schedule-subject { min-width:150px; color:#273f32 !important; font-weight:750 !important; }

    .term-delete-confirm[hidden] { display:none !important; }
    .term-delete-confirm { position:fixed; inset:0; z-index:20000; display:grid; place-items:center; padding:18px; background:rgba(18,35,27,.48); backdrop-filter:blur(4px); }
    .term-delete-confirm-card { width:min(460px,100%); padding:26px; border:1px solid #e2e8df; border-radius:18px; background:#fff; color:#243a2d; box-shadow:0 24px 70px rgba(18,35,27,.28); }
    .term-delete-confirm-icon { display:grid; width:48px; height:48px; place-items:center; margin:0 0 15px; border:1px solid #f0d1cc; border-radius:14px; background:#fff3f1; color:#b33f35; font-size:20px; }
    .term-delete-confirm-card h2 { margin:0 0 8px; color:#20392c; font-size:20px; font-weight:850; }
    .term-delete-confirm-card p { margin:0; color:#5b6c61; font-size:13px; line-height:1.6; }
    .term-delete-confirm-name { display:block; margin-top:10px; color:#264a36; font-weight:850; }
    .term-delete-confirm-actions { display:flex; justify-content:flex-end; gap:9px; margin-top:22px; }
    .term-delete-confirm-actions button { min-height:41px; padding:0 14px; border:1px solid #dce5db; border-radius:9px; background:#f6f8f5; color:#3d5747; font:800 12px/1 var(--wr-body); cursor:pointer; }
    .term-delete-confirm-actions .danger { border-color:#a63d35; background:#b74840; color:#fff; }
    .term-delete-confirm-actions .danger:hover { background:#97372f; }
    @media(max-width:480px) { .term-delete-confirm-actions { flex-direction:column-reverse; } .term-delete-confirm-actions button { width:100%; } }

    @media(max-width:1100px) {
        body.portal-shell.chair-portal { --chair-sidebar-width:226px; --chair-shell-gutter:22px; }
        body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
        body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); }
        body.portal-shell.chair-portal .main-layout > .content.academic-terms-view { padding-right:24px; padding-left:24px; }
        .academic-terms-view .academic-library-grid { grid-template-columns:minmax(0,1fr); }
    }
    @media(max-width:760px) {
        body.portal-shell.chair-portal .top-header { position:sticky; top:0; right:auto; left:auto; min-height:64px; margin:0; padding:9px 12px 9px 54px; border:0; border-radius:0 0 15px 15px; box-shadow:0 5px 18px rgba(31,57,43,.08); }
        body.portal-shell.chair-portal .header-title h3 { font-size:17px; }
        body.portal-shell.chair-portal .wrapper,body.portal-shell.chair-portal .main-layout { min-height:calc(100vh - 64px); }
        body.portal-shell.chair-portal .main-layout { display:block; }
        body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:fixed; top:0; bottom:0; left:0; z-index:1100; width:262px; height:100dvh; min-height:0; padding:20px 15px; transform:translateX(0); }
        body.portal-shell.chair-portal.sidebar-collapsed .main-layout > .chair-sidebar { width:262px; transform:translateX(-110%); }
        body.portal-shell.chair-portal .sidebar-backdrop { position:fixed; inset:0; z-index:1050; }
        body.portal-shell.chair-portal .sidebar-toggle,body.portal-shell.chair-portal.sidebar-collapsed .sidebar-toggle { top:17px; left:14px; z-index:1200; }
        body.portal-shell.chair-portal:not(.sidebar-collapsed) .sidebar-toggle { top:17px; left:270px; }
        body.portal-shell.chair-portal .main-layout > .content.academic-terms-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .academic-terms-view .academic-library-hero { align-items:flex-start; flex-direction:column; min-height:0; gap:18px; padding:27px 23px; }
        .academic-terms-view .active-term-badge { min-width:0; width:100%; }
    }
    @media(max-width:540px) {
        .academic-terms-view .term-create-fields,.academic-terms-view .reuse-form { grid-template-columns:minmax(0,1fr); }
        .academic-terms-view .reuse-form .reuse-full { grid-column:auto; }
        .academic-terms-view .preview-header-row { align-items:flex-start; flex-direction:column; }
        .academic-terms-view .preview-count { align-self:flex-start; }
        .academic-terms-view .sidebar-block,.academic-terms-view .preview-header { padding:17px; }
        .academic-terms-view .preview-body { padding:12px; }
        .academic-terms-view .schedule-meta { align-items:flex-start; flex-direction:column; }
        .academic-terms-view .term-actions .library-btn { min-height:38px; }
    }
</style>

<div class="wrapper">

    <div class="main-layout">

        {{-- =========================================================
             CHAIR SIDEBAR
        ========================================================== --}}

        @include('chair.sidebar')


        {{-- =========================================================
             MAIN CONTENT
        ========================================================== --}}

        <main class="content chair-workspace-redesign chair-library-redesign academic-terms-view">

          

            {{-- =========================================================
                 PAGE
            ========================================================== --}}

            <div class="academic-library-page">


                {{-- =====================================================
                     PAGE HEADER
                ====================================================== --}}

                <section class="academic-library-hero">
                    <div class="academic-library-hero-copy">
                        <span class="academic-library-eyebrow"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i> COLLEGE WORKSPACE</span>
                        <h1 class="academic-library-title">Academic Terms &amp; Schedule Library</h1>
                        <p class="academic-library-subtitle">Create terms, review saved schedules, and choose the active semester.</p>
                    </div>
                    <div class="active-term-badge">
                        <span>ACTIVE TERM</span>
                        <strong>{{ $activeTerm->academic_year }} <span aria-hidden="true">•</span> {{ $activeTerm->semester }}</strong>
                    </div>
                    <i class="academic-library-hero-mark fa-solid fa-calendar-days" aria-hidden="true"></i>
                </section>


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

                                            @if((int) $activeTerm->id !== (int) $term->id)
                                                <form method="POST"
                                                      action="{{ route('chair.academic.terms.delete', $term->id) }}"
                                                      data-term-delete
                                                      data-term-name="{{ $term->academic_year }} • {{ $term->semester }}"
                                                      data-schedule-count="{{ (int) $term->total_schedule_count }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="confirmed_schedule_count" value="">
                                                    <button type="submit" class="library-btn library-btn-danger">
                                                        <i class="fa-solid fa-trash" aria-hidden="true"></i> Delete
                                                    </button>
                                                </form>
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

</div>

<div class="term-delete-confirm" id="termDeleteConfirm" role="alertdialog" aria-modal="true" aria-labelledby="termDeleteConfirmTitle" aria-describedby="termDeleteConfirmMessage" hidden>
    <section class="term-delete-confirm-card">
        <div class="term-delete-confirm-icon" aria-hidden="true"><i class="fa-solid fa-trash-can"></i></div>
        <h2 id="termDeleteConfirmTitle">Delete academic term?</h2>
        <p id="termDeleteConfirmMessage"></p>
        <strong class="term-delete-confirm-name" id="termDeleteConfirmName"></strong>
        <div class="term-delete-confirm-actions">
            <button type="button" id="cancelTermDelete">Cancel</button>
            <button type="button" class="danger" id="confirmTermDelete">Delete Term</button>
        </div>
    </section>
</div>

<script>
    (function () {
        const dialog = document.getElementById('termDeleteConfirm');
        const message = document.getElementById('termDeleteConfirmMessage');
        const termName = document.getElementById('termDeleteConfirmName');
        const cancelButton = document.getElementById('cancelTermDelete');
        const confirmButton = document.getElementById('confirmTermDelete');
        let pendingForm = null;
        let previousBodyOverflow = '';

        function closeDialog() {
            if (!dialog) return;
            dialog.hidden = true;
            document.body.style.overflow = previousBodyOverflow;
            pendingForm = null;
        }

        document.querySelectorAll('form[data-term-delete]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (form.dataset.confirmed === 'true') return;
                event.preventDefault();
                pendingForm = form;

                const count = Number(form.dataset.scheduleCount || 0);
                const label = count === 1 ? 'schedule record' : 'schedule records';
                message.textContent = count > 0
                    ? `This term contains ${count} ${label}. Deleting it will permanently remove these schedules across all departments and their saved timetable data.`
                    : 'This term has no saved schedules. Are you sure you want to permanently delete it?';
                termName.textContent = form.dataset.termName || 'Selected academic term';
                confirmButton.textContent = count > 0 ? 'Delete Term and Schedules' : 'Delete Term';
                previousBodyOverflow = document.body.style.overflow;
                dialog.hidden = false;
                document.body.style.overflow = 'hidden';
                confirmButton.focus();
            });
        });

        cancelButton?.addEventListener('click', closeDialog);
        dialog?.addEventListener('click', function (event) {
            if (event.target === dialog) closeDialog();
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && dialog && !dialog.hidden) closeDialog();
        });
        confirmButton?.addEventListener('click', function () {
            if (!pendingForm) return;
            const confirmationField = pendingForm.querySelector('[name="confirmed_schedule_count"]');
            if (confirmationField) confirmationField.value = String(Number(pendingForm.dataset.scheduleCount || 0));
            pendingForm.dataset.confirmed = 'true';
            pendingForm.requestSubmit();
        });
    })();
</script>
