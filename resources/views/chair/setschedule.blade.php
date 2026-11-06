@include('footerheader.header')

<style>
    /* Set schedule: a clear, branded department workspace with readable room cards. */
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
    body.portal-shell.chair-portal .main-layout > .content.chair-set-schedule-view { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }

    .chair-set-schedule-view .page-header { position:relative; display:flex; align-items:center; min-height:195px; margin:0 0 18px; padding:clamp(27px,4vw,42px); overflow:hidden; border:1px solid rgba(231,214,173,.22); border-radius:24px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); color:#fff; }
    .chair-set-schedule-view .page-header::after { position:absolute; right:-48px; bottom:-165px; width:330px; height:330px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .chair-set-schedule-view .title-area { position:relative; z-index:1; display:flex; align-items:center; gap:17px; }
    .chair-set-schedule-view .title-icon { display:grid; flex:0 0 52px; width:52px; height:52px; place-items:center; border:1px solid rgba(255,255,255,.24); border-radius:15px; background:rgba(255,255,255,.11); color:#e2c987; font-size:22px; }
    .chair-set-schedule-view .title { margin:0 0 8px; color:#fff !important; font:800 clamp(34px,3.2vw,45px)/1.12 var(--wr-display); letter-spacing:-.045em; }
    .chair-set-schedule-view .subtitle { color:#e1e8df; font-size:15px; font-weight:600; line-height:1.6; }
    .chair-set-schedule-view .message { margin:0 0 15px; padding:14px 17px; border:1px solid; border-radius:12px; font-size:14px; font-weight:700; line-height:1.5; }
    .chair-set-schedule-view .message.success { border-color:#b9d9bd; background:#edf7ee; color:#28603a; }
    .chair-set-schedule-view .message.error { border-color:#edc8c2; background:#fff3f1; color:#8f3e36; }
    .chair-set-schedule-view .active-term-panel { display:flex; align-items:center; justify-content:space-between; gap:18px; margin:0 0 17px; padding:17px 19px !important; border:1px solid #dfe7dd !important; border-radius:15px !important; background:linear-gradient(110deg,#f5f7f1,#fff) !important; box-shadow:0 7px 22px rgba(31,57,43,.045); }
    .chair-set-schedule-view .active-term-copy { display:grid; gap:5px; }
    .chair-set-schedule-view .active-term-label { color:#68776d !important; font-size:11px !important; font-weight:850 !important; letter-spacing:.1em !important; }
    .chair-set-schedule-view .active-term-name { color:#173b33 !important; font-size:19px !important; font-weight:850 !important; line-height:1.35; }
    .chair-set-schedule-view .active-term-help { color:#5e6f64 !important; font-size:12px !important; font-weight:550; line-height:1.45; }
    .chair-set-schedule-view .active-term-actions { display:flex; align-items:center; flex-wrap:wrap; gap:9px; }
    .chair-set-schedule-view .term-select { min-height:43px !important; padding:0 12px !important; border:1px solid #d7e0d7 !important; border-radius:10px !important; color:#273f32 !important; font-size:13px !important; font-weight:650 !important; }
    .chair-set-schedule-view .schedule-library-link { display:inline-flex; align-items:center; justify-content:center; min-height:42px; padding:0 14px !important; border:1px solid #24543e; border-radius:10px !important; background:#24543e !important; color:#fff !important; font-size:13px !important; font-weight:800 !important; text-decoration:none; white-space:nowrap; }
    .chair-set-schedule-view .schedule-library-link:hover { background:#173b33 !important; }
    .chair-set-schedule-view .editor-mode-switch { display:flex; align-items:center; flex-wrap:wrap; gap:8px; margin:-3px 0 16px; padding:6px; border:1px solid #dfe7dd; border-radius:12px; background:#fff; }
    .chair-set-schedule-view .editor-mode-link { display:inline-flex; min-height:40px; align-items:center; gap:8px; padding:0 14px; border-radius:8px; color:#53675a; font-size:13px; font-weight:800; text-decoration:none; }
    .chair-set-schedule-view .editor-mode-link.active { background:#24543e; color:#fff; }
    .chair-set-schedule-view .section { min-width:0; padding:21px; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .chair-set-schedule-view .department-display { display:flex; align-items:center; gap:12px; margin:0 0 20px; padding:14px 16px; border:1px solid #e2e9e1; border-radius:13px; background:linear-gradient(110deg,#f5f8f3,#fff); }
    .chair-set-schedule-view .department-icon { display:grid; flex:0 0 42px; width:42px; height:42px; place-items:center; border:1px solid #e2e9df; border-radius:13px; background:#edf3eb; color:#285940; font-size:17px; }
    .chair-set-schedule-view .department-label { color:#68776d; font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .chair-set-schedule-view .department-name { margin-top:3px; color:#203d30; font-size:16px; font-weight:850; }
    .chair-set-schedule-view .section-header { display:flex; align-items:flex-end; justify-content:space-between; gap:15px; margin:0 0 15px; }
    .chair-set-schedule-view .section-title { display:flex; align-items:center; gap:9px; color:#173b33; font-size:21px; font-weight:850; letter-spacing:-.03em; }
    .chair-set-schedule-view .section-title i { color:#35684a; font-size:17px; }
    .chair-set-schedule-view .section-help { color:#5f7065; font-size:13px; font-weight:600; }
    .chair-set-schedule-view .room-search-panel { display:grid; gap:7px; margin:0 0 16px; padding:14px; border:1px solid #e2e9e1; border-radius:13px; background:#f8faf7; }
    .chair-set-schedule-view .room-search-panel label { color:#43594d; font-size:12px; font-weight:800; }
    .chair-set-schedule-view .room-search-control { display:flex; min-height:46px; align-items:center; gap:10px; padding:0 12px; border:1px solid #d7e0d7; border-radius:10px; background:#fff; color:#65776a; }
    .chair-set-schedule-view .room-search-control:focus-within { outline:3px solid rgba(49,104,76,.14); border-color:#648a70; }
    .chair-set-schedule-view .room-search-control input { width:100%; min-width:0; border:0; outline:0; background:transparent; color:#273f32; font:600 14px/1.4 var(--wr-body); }
    .chair-set-schedule-view .room-search-control input::placeholder { color:#89968c; font-weight:500; }
    .chair-set-schedule-view .room-search-clear { display:grid; flex:0 0 30px; width:30px; height:30px; place-items:center; border:0; border-radius:8px; background:#eef3ed; color:#496052; cursor:pointer; }
    .chair-set-schedule-view .room-search-clear[hidden],.chair-set-schedule-view .room-search-empty[hidden] { display:none !important; }
    .chair-set-schedule-view .room-search-status { margin:0; color:#68776d; font-size:12px; font-weight:600; }
    .chair-set-schedule-view .editor-mode-search { display:grid; flex:0 1 340px; gap:4px; margin:0 0 0 auto; padding:0; border:0; background:transparent; }
    .chair-set-schedule-view .editor-mode-search label { position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; }
    .chair-set-schedule-view .editor-mode-search .room-search-control { min-height:40px; }
    .chair-set-schedule-view .building[hidden],.chair-set-schedule-view .room-card[hidden] { display:none !important; }
    .chair-set-schedule-view .building-list { display:grid; gap:11px; }
    .chair-set-schedule-view .building { overflow:hidden; border:1px solid #e0e8df; border-radius:14px; background:#fff; }
    .chair-set-schedule-view .building-toggle { display:flex; align-items:center; justify-content:space-between; gap:15px; width:100%; min-height:73px; padding:13px 16px; border:0; background:#f8faf6; color:#294437; text-align:left; cursor:pointer; transition:background .15s ease; }
    .chair-set-schedule-view .building-toggle:hover,.chair-set-schedule-view .building.open .building-toggle { background:#f0f6ee; }
    .chair-set-schedule-view .building-left { display:flex; align-items:center; gap:12px; min-width:0; }
    .chair-set-schedule-view .building-icon { display:grid; flex:0 0 40px; width:40px; height:40px; place-items:center; border:1px solid #e2e9df; border-radius:12px; background:#edf3eb; color:#285940; font-size:15px; }
    .chair-set-schedule-view .building-info { min-width:0; }
    .chair-set-schedule-view .building-name { color:#203d30; font-size:15px; font-weight:850; line-height:1.35; }
    .chair-set-schedule-view .building-meta { margin-top:4px; color:#65746a; font-size:12px; font-weight:650; }
    .chair-set-schedule-view .building-arrow { display:grid; flex:0 0 30px; width:30px; height:30px; place-items:center; border:1px solid #e1e8df; border-radius:9px; background:#fff; color:#61756a; transition:transform .15s ease; }
    .chair-set-schedule-view .building.open .building-arrow { transform:rotate(180deg); }
    .chair-set-schedule-view .rooms-container { display:none; padding:14px; border-top:1px solid #e7ece5; }
    .chair-set-schedule-view .building.open .rooms-container { display:block; }
    .chair-set-schedule-view .room-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .chair-set-schedule-view .room-card { display:flex; min-width:0; flex-direction:column; padding:15px; border:1px solid #e2e9e1; border-radius:14px; background:#fff; box-shadow:0 4px 14px rgba(31,57,43,.035); transition:transform .16s ease,border-color .16s ease,box-shadow .16s ease; }
    .chair-set-schedule-view .room-card:hover { transform:translateY(-2px); border-color:#adc5b0; box-shadow:0 10px 22px rgba(31,57,43,.08); }
    .chair-set-schedule-view .room-top { display:flex; align-items:center; gap:11px; }
    .chair-set-schedule-view .room-icon { display:grid; flex:0 0 40px; width:40px; height:40px; place-items:center; border:1px solid #e2e9df; border-radius:12px; background:#edf3eb; color:#285940; font-size:16px; }
    .chair-set-schedule-view .room-name { color:#203d30; font-size:16px; font-weight:850; line-height:1.35; overflow-wrap:anywhere; }
    .chair-set-schedule-view .room-label { margin-top:3px; color:#68776d; font-size:12px; font-weight:600; }
    .chair-set-schedule-view .room-features { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; margin:14px 0; }
    .chair-set-schedule-view .room-feature { display:flex; align-items:flex-start; gap:7px; min-width:0; padding:8px; border:1px solid #e5ebe3; border-radius:10px; background:#f8faf6; color:#69766e; }
    .chair-set-schedule-view .room-feature > i { margin-top:2px; color:#69836f; font-size:12px; }
    .chair-set-schedule-view .room-feature > span { display:grid; gap:2px; min-width:0; }
    .chair-set-schedule-view .room-feature strong { color:#43594d; font-size:10px; font-weight:800; }
    .chair-set-schedule-view .room-feature > span > span { color:#52675a; font-size:11px; font-weight:650; line-height:1.35; overflow-wrap:anywhere; }
    .chair-set-schedule-view .room-feature.feature-available { border-color:#d6e5d5; background:#f0f7ef; color:#35684a; }
    .chair-set-schedule-view .room-feature.feature-unavailable { background:#f7f8f6; color:#7d8981; }
    .chair-set-schedule-view .room-feature.room-purpose { grid-column:1/-1; }
    .chair-set-schedule-view .btn.green { display:inline-flex; align-items:center; justify-content:center; gap:8px; width:100%; min-height:42px; margin-top:auto; padding:0 13px; border:1px solid #24543e; border-radius:10px; background:#24543e; color:#fff; font-size:13px; font-weight:850; text-decoration:none; transition:transform .16s ease,background .16s ease; }
    .chair-set-schedule-view .btn.green:hover { transform:translateY(-1px); background:#173b33; }
    .chair-set-schedule-view .permission-box { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:11px; padding:10px 11px; border:1px solid #e5e9dc; border-radius:10px; background:#fbfbf7; }
    .chair-set-schedule-view .permission-label { display:flex; align-items:flex-start; gap:8px; color:#4e6256; font-size:11px; font-weight:700; line-height:1.4; cursor:pointer; }
    .chair-set-schedule-view .permission-label input { width:17px; height:17px; flex:0 0 17px; margin:0; accent-color:#285940; }
    .chair-set-schedule-view .permission-status { display:inline-flex; align-items:center; justify-content:center; min-width:42px; min-height:25px; padding:0 8px; border-radius:99px; font-size:10px; font-weight:850; }
    .chair-set-schedule-view .permission-status.status-on { background:#e8f3e8; color:#326447; }
    .chair-set-schedule-view .permission-status.status-off { background:#f0f2ef; color:#69766e; }
    .chair-set-schedule-view .empty { display:grid; justify-items:center; gap:8px; grid-column:1/-1; padding:25px; border:1px dashed #d7e1d7; border-radius:13px; background:#f8faf6; color:#637268; font-size:13px; font-weight:650; text-align:center; }
    .chair-set-schedule-view .empty > i { color:#78917d; font-size:22px; }

    @media(max-width:1100px) {
        body.portal-shell.chair-portal { --chair-sidebar-width:226px; --chair-shell-gutter:22px; }
        body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
        body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); }
        body.portal-shell.chair-portal .main-layout > .content.chair-set-schedule-view { padding-right:24px; padding-left:24px; }
        .chair-set-schedule-view .room-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
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
        body.portal-shell.chair-portal .main-layout > .content.chair-set-schedule-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .chair-set-schedule-view .active-term-panel { align-items:flex-start; flex-direction:column; padding:15px !important; }
        .chair-set-schedule-view .active-term-actions { width:100%; }
        .chair-set-schedule-view .term-select { width:100%; min-width:0 !important; }
        .chair-set-schedule-view .section-header { align-items:flex-start; flex-direction:column; }
        .chair-set-schedule-view .editor-mode-switch .editor-mode-search { flex-basis:100%; margin:4px 0 0; }
        .chair-set-schedule-view .room-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:540px) {
        .chair-set-schedule-view .page-header { min-height:0; padding:27px 22px; }
        .chair-set-schedule-view .title-area { align-items:flex-start; gap:13px; }
        .chair-set-schedule-view .title-icon { flex-basis:45px; width:45px; height:45px; font-size:20px; }
        .chair-set-schedule-view .active-term-actions { align-items:stretch; flex-direction:column; }
        .chair-set-schedule-view .schedule-library-link { width:100%; }
        .chair-set-schedule-view .section { padding:16px; }
        .chair-set-schedule-view .room-grid { grid-template-columns:minmax(0,1fr); }
        .chair-set-schedule-view .room-features { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
</style>
<div class="wrapper">

    

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content chair-set-schedule chair-workspace-redesign chair-set-schedule-view">




<div class="page-header">

    <div class="title-area">

        <div class="title-icon">

            <i class="fa-solid fa-calendar-days"></i>

        </div>

        <div>

            <h1 class="title">
                Set Schedule
            </h1>

            <div class="subtitle">
                Manage classroom schedules for your college.
            </div>

        </div>

    </div>

</div>

<nav class="editor-mode-switch" aria-label="Schedule editor mode">
    <a class="editor-mode-link active" aria-current="page" href="{{ route('chair.setschedule') }}"><i class="fa-solid fa-building"></i> Schedule editor</a>
    <a class="editor-mode-link" href="{{ route('chair.modifyschedule', ['term_id' => $activeTerm->id]) }}"><i class="fa-solid fa-arrow-right-arrow-left"></i> Cross-scheduling</a>
    <a class="editor-mode-link" href="{{ route('chair.roomavailability', ['term_id' => $activeTerm->id]) }}"><i class="fa-solid fa-table-cells-large"></i> All-room availability</a>
    <div class="room-search-panel editor-mode-search">
        <label for="setScheduleRoomSearch">Find a room</label>
        <div class="room-search-control">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input id="setScheduleRoomSearch" type="search" autocomplete="off" aria-label="Find a room" placeholder="Search rooms in your college">
            <button type="button" id="clearSetScheduleRoomSearch" class="room-search-clear" aria-label="Clear room search" hidden><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
        </div>
        <p class="room-search-status" id="setScheduleRoomSearchStatus" role="status" aria-live="polite">Search rooms across your college.</p>
    </div>
</nav>

                {{-- SUCCESS MESSAGE --}}

                @if(session('success'))

                    <div class="message success">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ERROR MESSAGE --}}

                @if(session('error'))

                    <div class="message error">
                        {{ session('error') }}
                    </div>

                @endif


                

                {{-- =============================================================
                     AVAILABLE ACADEMIC TERMS
                ============================================================== --}}

                @php
                    $availableTerms = $availableTerms ?? collect();
                @endphp


                {{-- =============================================================
                     ACTIVE / VIEW ACADEMIC TERM
                ============================================================== --}}

                <div class="active-term-panel"
                    style="
                        margin-bottom:18px;
                        padding:14px 16px;
                        background:#eef8ef;
                        border:1px solid #d3e8d6;
                        border-radius:10px;
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        gap:14px;
                        flex-wrap:wrap;
                    "
                >

                    {{-- =====================================================
                         CURRENT TERM INFORMATION
                    ====================================================== --}}

                    <div class="active-term-copy">

                        <div class="active-term-label"
                            style="
                                font-size:11px;
                                color:#667085;
                                text-transform:uppercase;
                                font-weight:800;
                                letter-spacing:.3px;
                            "
                        >
                            Active Academic Term
                        </div>


                        <div class="active-term-name"
                            style="
                                font-size:18px;
                                color:#176b3a;
                                font-weight:800;
                            "
                        >
                            {{ $activeTerm->academic_year }}
                            •
                            {{ $activeTerm->semester }}
                        </div>


                        <div class="active-term-help"
                            style="
                                font-size:11px;
                                color:#667085;
                                margin-top:3px;
                            "
                        >
                            All schedules you create here are saved under this active term.
                        </div>

                    </div>


                    {{-- =====================================================
                         TERM SELECTOR
                    ====================================================== --}}

                    <div class="active-term-actions"
                        style="
                            display:flex;
                            gap:8px;
                            align-items:center;
                            flex-wrap:wrap;
                        "
                    >

                        <form
                            method="POST"
                            action="{{ route('chair.academic.terms.activate-selected') }}"
                            style="
                                display:flex;
                                gap:7px;
                                align-items:center;
                            "
                        >

                            @csrf

                            <input type="hidden" name="return_to" value="setschedule">

                            <select
                                class="term-select"
                                name="term_id"
                                required
                                aria-label="Choose academic term to activate"
                                onchange="if (this.value) this.form.requestSubmit();"
                                style="
                                    padding:9px 11px;
                                    border:1px solid #2e7d32;
                                    border-radius:8px;
                                    min-width:230px;
                                    background:#ffffff;
                                    color:#1f2937;
                                    font-size:12px;
                                    font-weight:600;
                                    outline:none;
                                    cursor:pointer;
                                "
                            >

                                <option value="" disabled>
                                    Choose term to activate...
                                </option>


                                @foreach($availableTerms as $term)

                                    <option
                                        value="{{ $term->id }}"
                                        {{ (int) $term->id === (int) $activeTerm->id ? 'selected' : '' }}
                                    >

                                        {{ $term->academic_year }}
                                        •
                                        {{ $term->semester }}

                                        @if(
                                            (int) $term->id ===
                                            (int) $activeTerm->id
                                        )
                                            — Active
                                        @endif

                                    </option>

                                @endforeach


                            </select>

                            <button type="submit" class="schedule-library-link" style="border:0;cursor:pointer;">
                                Activate Term
                            </button>

                        </form>


                        {{-- SCHEDULE LIBRARY --}}

                        <a
                            href="{{ route('chair.schedule.library') }}"
                            class="schedule-library-link"
                            style="
                                padding:9px 12px;
                                border-radius:7px;
                                background:#2e7d32;
                                color:#ffffff;
                                text-decoration:none;
                                font-size:12px;
                                font-weight:700;
                            "
                        >

                            Schedule Library

                        </a>

                    </div>

                </div>


<div class="section">


                    {{-- =====================================================
                         DEPARTMENT
                    ====================================================== --}}

                   <div class="department-display">

    <div class="department-icon">

        <i class="fa-solid fa-building-columns"></i>

    </div>

    <div>

        <div class="department-label">
            Your College
        </div>

        <div class="department-name">
            {{ $department->name }}
        </div>

    </div>

</div>


                   {{-- =====================================================
     BUILDINGS AND ROOMS
====================================================== --}}

<div class="section-header">

    <div class="section-title">

        <i class="fa-solid fa-building"></i>

        <span>
            Buildings and Rooms
        </span>

    </div>

    <div class="section-help">
        Click a building to view its rooms
    </div>

</div>

<div class="empty room-search-empty" id="setScheduleRoomSearchEmpty" hidden>
    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
    <span>No rooms match your search.</span>
</div>


<div class="building-list">

    @forelse($buildings as $building)

        @php
            $roomCount = $building->rooms->count();
        @endphp


        <div class="building">


            {{-- =================================================
                 BUILDING HEADER
            ================================================== --}}

            <button
                type="button"
                class="building-toggle"
                onclick="toggleBuilding(this)"
            >

                <div class="building-left">

                    <div class="building-icon">

                        <i class="fa-solid fa-building"></i>

                    </div>


                    <div class="building-info">

                        <div class="building-name">

                            {{ $building->name }}

                        </div>


                        <div class="building-meta">

                            {{ $roomCount }}
                            {{ $roomCount == 1 ? 'room' : 'rooms' }}

                        </div>

                    </div>

                </div>


                <div class="building-arrow">

                    <i class="fa-solid fa-chevron-down"></i>

                </div>

            </button>


            {{-- =================================================
                 ROOMS
            ================================================== --}}

            <div class="rooms-container">

                <div class="room-grid">


                    @forelse($building->rooms as $room)

                        <div class="room-card">


                            <div class="room-top">

                                <div class="room-icon">

                                    <i class="fa-solid fa-door-open"></i>

                                </div>


                                <div>

                                    <div class="room-name">

                                        {{ $room->room_name }}

                                    </div>

                                    <div class="room-label">

                                        Classroom

                                    </div>

                                </div>

                            </div>


                            <div class="room-features" aria-label="Room facilities and capacity">
                                <div class="room-feature">
                                    <i class="fa-solid fa-users" aria-hidden="true"></i>
                                    <span><strong>Capacity</strong><span>{{ $room->capacity ?? 'Not specified' }}</span></span>
                                </div>
                                <div class="room-feature">
                                    <i class="fa-solid fa-computer" aria-hidden="true"></i>
                                    <span><strong>Computers</strong><span>{{ $room->computers ?? 0 }}</span></span>
                                </div>
                                <div class="room-feature {{ $room->has_tv ? 'feature-available' : 'feature-unavailable' }}">
                                    <i class="fa-solid fa-tv" aria-hidden="true"></i>
                                    <span><strong>TV</strong><span>{{ $room->has_tv ? 'Available' : 'No' }}</span></span>
                                </div>
                                <div class="room-feature {{ $room->has_projector ? 'feature-available' : 'feature-unavailable' }}">
                                    <i class="fa-solid fa-video" aria-hidden="true"></i>
                                    <span><strong>Projector</strong><span>{{ $room->has_projector ? 'Available' : 'No' }}</span></span>
                                </div>
                                @if(filled($room->purpose))
                                    <div class="room-feature room-purpose">
                                        <i class="fa-solid fa-book-open" aria-hidden="true"></i>
                                        <span><strong>Purpose</strong><span>{{ $room->purpose }}</span></span>
                                    </div>
                                @endif
                            </div>
                            {{-- SET SCHEDULE --}}

                            <a
                                href="{{ route(
                                    'chair.excel',
                                    $room->id
                                ) }}"
                                class="btn green"
                            >

                                <i class="fa-solid fa-calendar-days"></i>

                                Set Schedule

                            </a>


                            {{-- =================================================
                                 CROSS-DEPARTMENT PERMISSION
                            ================================================== --}}

                            <div class="permission-box">

                                <label
                                    class="permission-label"
                                    for="permission-{{ $room->id }}"
                                >

                                    <input
                                        type="checkbox"
                                        id="permission-{{ $room->id }}"

                                        {{ $room->allow_other_departments
                                            ? 'checked'
                                            : ''
                                        }}

                                        onchange="updateRoomPermission(
                                            {{ $room->id }},
                                            this
                                        )"
                                    >

                                    <span>
                                        Allow other colleges
                                        to add to empty slots
                                    </span>

                                </label>


                                <div
                                    id="status-{{ $room->id }}"
                                    class="permission-status
                                    {{
                                        $room->allow_other_departments
                                            ? 'status-on'
                                            : 'status-off'
                                    }}"
                                >

                                    {{
                                        $room->allow_other_departments
                                            ? 'ON'
                                            : 'OFF'
                                    }}

                                </div>

                            </div>


                        </div>


                    @empty

                        <div class="empty">

                            <i class="fa-solid fa-door-closed"></i>

                            <div style="margin-top:6px;">

                                No rooms in this building.

                            </div>

                        </div>

                    @endforelse


                </div>

            </div>

        </div>


    @empty

        <div class="empty">

            <i class="fa-solid fa-building-circle-xmark"></i>

            <div style="margin-top:6px;">

                No buildings are assigned
                to your college.

            </div>

        </div>

    @endforelse

</div>
</div>

            {{-- =============================================================
                 PERMISSION JAVASCRIPT
            ============================================================== --}}

            <script>

               function updateRoomPermission(roomId, checkbox)
{
    const allowed = checkbox.checked;

    fetch(
        "{{ url('/chair/room') }}/" + roomId + "/permission",
        {
            method: "PUT",

            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",

                "X-CSRF-TOKEN":
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).getAttribute('content')
            },

            body: JSON.stringify({
                allow_other_departments: allowed ? 1 : 0
            })
        }
    )
    .then(response => {

        if (!response.ok) {
            throw new Error(
                "HTTP Error: " + response.status
            );
        }

        return response.json();
    })
    .then(data => {

        if (!data.success) {
            throw new Error(
                data.message ||
                "Permission was not saved."
            );
        }

        const saved =
            Number(data.allow_other_departments) === 1;

        checkbox.checked = saved;

        const status =
            document.getElementById(
                "status-" + roomId
            );

        if (saved) {

            status.textContent = "ON";

            status.className =
                "permission-status status-on";

        } else {

            status.textContent = "OFF";

            status.className =
                "permission-status status-off";
        }

    })
    .catch(error => {

        console.error(error);

        checkbox.checked = !allowed;

        alert(
            "Unable to save permission.\n\n" +
            error.message
        );
    });


}
    function toggleBuilding(button)
{
    const building =
        button.closest('.building');

    if (!building) {
        return;
    }

    building.classList.toggle('open');
}

const setScheduleRoomSearch = document.getElementById('setScheduleRoomSearch');
const clearSetScheduleRoomSearch = document.getElementById('clearSetScheduleRoomSearch');
const setScheduleRoomSearchStatus = document.getElementById('setScheduleRoomSearchStatus');
const setScheduleRoomSearchEmpty = document.getElementById('setScheduleRoomSearchEmpty');
let roomSearchBuildingState = null;

function filterSetScheduleRooms()
{
    const query = String(setScheduleRoomSearch?.value || '').trim().toLocaleLowerCase();
    const buildings = Array.from(document.querySelectorAll('.chair-set-schedule-view .building-list .building'));
    let matchedRooms = 0;
    let totalRooms = 0;

    if (query && roomSearchBuildingState === null) {
        roomSearchBuildingState = new Map(buildings.map(building => [building, building.classList.contains('open')]));
    }

    buildings.forEach(function(building) {
        const buildingName = building.querySelector('.building-name')?.textContent || '';
        const roomCards = Array.from(building.querySelectorAll('.room-card'));
        let matchesInBuilding = 0;

        roomCards.forEach(function(roomCard) {
            totalRooms++;
            const searchText = [
                buildingName,
                roomCard.querySelector('.room-name')?.textContent || '',
                roomCard.querySelector('.room-features')?.textContent || '',
            ].join(' ').toLocaleLowerCase();
            const matches = !query || searchText.includes(query);
            roomCard.hidden = !matches;
            if (matches) {
                matchesInBuilding++;
                matchedRooms++;
            }
        });

        building.hidden = Boolean(query) && matchesInBuilding === 0;
        if (query && matchesInBuilding > 0) building.classList.add('open');
        if (!query && roomSearchBuildingState) {
            building.classList.toggle('open', roomSearchBuildingState.get(building) === true);
        }
    });

    if (!query) roomSearchBuildingState = null;
    if (clearSetScheduleRoomSearch) clearSetScheduleRoomSearch.hidden = !query;
    if (setScheduleRoomSearchEmpty) setScheduleRoomSearchEmpty.hidden = !query || matchedRooms > 0;
    if (setScheduleRoomSearchStatus) {
        setScheduleRoomSearchStatus.textContent = query
            ? `${matchedRooms} ${matchedRooms === 1 ? 'room' : 'rooms'} found.`
            : `${totalRooms} ${totalRooms === 1 ? 'room' : 'rooms'} available in your department.`;
    }
}

setScheduleRoomSearch?.addEventListener('input', filterSetScheduleRooms);
clearSetScheduleRoomSearch?.addEventListener('click', function() {
    if (!setScheduleRoomSearch) return;
    setScheduleRoomSearch.value = '';
    filterSetScheduleRooms();
    setScheduleRoomSearch.focus();
});
filterSetScheduleRooms();

                
            </script>

        </main>

    </div>

    

</div>


