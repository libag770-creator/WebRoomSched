@include('footerheader.header')

<style>
    /* Chair dashboard: match the premium admin workspace and improve readability. */
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
    body.portal-shell.chair-portal .main-layout > .content.chair-dashboard-view { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }

    .chair-dashboard-view .chair-dashboard { width:min(100%,1480px); margin:0 auto; }
    .chair-dashboard-view .chair-dashboard-hero { display:block; min-height:250px; margin-bottom:25px; padding:clamp(28px,4vw,44px); padding-right:320px; border:1px solid rgba(231,214,173,.22); border-radius:24px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); }
    .chair-dashboard-view .chair-dashboard-hero::after { right:-45px; bottom:-175px; width:340px; height:340px; border-color:rgba(232,215,169,.25); box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); }
    .chair-dashboard-view .chair-hero-copy { max-width:720px; }
    .chair-dashboard-view .chair-eyebrow { margin-bottom:12px; color:#e2c987; font-size:11px; font-weight:800; }
    .chair-dashboard-view .chair-dashboard-hero h1 { max-width:750px; margin-bottom:12px; color:#fff; font:800 clamp(34px,3.2vw,46px)/1.12 var(--wr-display); letter-spacing:-.045em; }
    .chair-dashboard-view .chair-hero-copy > p { max-width:640px; color:#e1e8df; font-size:15px; font-weight:550; line-height:1.65; }
    .chair-dashboard-view .chair-hero-meta { gap:18px; margin-top:20px; color:#f0eee3; font-size:13px; font-weight:700; }
    .chair-dashboard-view .chair-hero-actions { position:absolute; right:30px; bottom:28px; z-index:3; display:grid; width:240px; max-width:none; gap:9px; }
    .chair-dashboard-view .chair-primary-action,.chair-dashboard-view .chair-secondary-action { min-height:44px; padding:0 15px; border-radius:11px; font-size:13px; font-weight:800; }
    .chair-dashboard-view .chair-hero-mark { position:absolute; top:24px; right:30px; z-index:0; display:grid; width:128px; height:128px; place-items:center; overflow:hidden; border:0; border-radius:0; background:transparent; box-shadow:none; color:rgba(232,215,174,.66); opacity:.2; font-size:88px; pointer-events:none; }
    .chair-dashboard-view .chair-hero-mark img { display:block; width:100%; height:100%; padding:0; border-radius:0; background:transparent; object-fit:contain; mix-blend-mode:screen; }
    .chair-dashboard-view .chair-hero-mark [hidden] { display:none !important; }

    .chair-dashboard-view .chair-metric-grid { gap:13px; margin:0 0 24px; }
    .chair-dashboard-view .chair-metric-card { min-height:150px; padding:19px 20px; border-color:#dfe7dd; border-radius:16px; box-shadow:0 7px 22px rgba(31,57,43,.05); }
    .chair-dashboard-view .chair-metric-icon { width:40px; height:40px; border:1px solid #e2e9df; border-radius:12px; font-size:16px; }
    .chair-dashboard-view .chair-metric-icon { display:grid; flex:0 0 40px; place-items:center; padding:0; line-height:1; }
    .chair-dashboard-view .chair-metric-icon > i,.chair-dashboard-view .chair-panel-icon > i,.chair-dashboard-view .chair-tool-icon > i { display:block; line-height:1; }
    .chair-dashboard-view .chair-eyebrow,.chair-dashboard-view .chair-hero-meta > span { display:inline-flex; align-items:center; gap:8px; }
    .chair-dashboard-view .chair-eyebrow > i,.chair-dashboard-view .chair-hero-meta > span > i { flex:0 0 auto; line-height:1; }
    .chair-dashboard-view .chair-metric-label { margin-top:11px; color:#43594d; font-size:13px; font-weight:800; line-height:1.35; }
    .chair-dashboard-view .chair-metric-value { margin:8px 0 4px; color:#173b33; font:800 36px/1 var(--wr-display); }
    .chair-dashboard-view .chair-metric-note { color:#596c60; font-size:12px; font-weight:600; line-height:1.45; }

    .chair-dashboard-view .chair-workspace-grid { grid-template-columns:minmax(0,1.35fr) minmax(310px,.8fr); gap:17px; }
    .chair-dashboard-view .chair-panel { border-color:#dfe7dd; border-radius:18px; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .chair-dashboard-view .chair-panel-header { align-items:flex-start; padding:20px 21px; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .chair-dashboard-view .chair-panel-header h2 { color:#173b33; font-size:20px; font-weight:800; }
    .chair-dashboard-view .chair-panel-header p { color:#586b5f; font-size:13px; font-weight:550; line-height:1.5; }
    .chair-dashboard-view .chair-panel-kicker { margin-bottom:7px; color:#917b4a; font-size:10px; font-weight:800; }
    .chair-dashboard-view .chair-panel-icon { width:38px; height:38px; border:1px solid #e2e9df; border-radius:12px; background:#edf3eb; color:#285940; font-size:16px; }
    .chair-dashboard-view .chair-panel-icon { display:grid; flex:0 0 38px; place-items:center; line-height:1; }
    .chair-dashboard-view .chair-dashboard-shortcuts { padding:7px 20px 15px; }
    .chair-dashboard-view .chair-dashboard-shortcuts .chair-tool-link { min-height:74px; margin:0; gap:13px; padding:13px 0; }
    .chair-dashboard-view .chair-dashboard-shortcuts .chair-tool-icon { display:grid; flex:0 0 42px; width:42px; height:42px; place-items:center; line-height:1; }
    .chair-dashboard-view .chair-dashboard-shortcuts .chair-tool-link > span:nth-child(2) { gap:5px; }
    .chair-dashboard-view .chair-shortcut-count { display:grid; flex:0 0 30px; width:30px; height:30px; place-items:center; border:1px solid #dfe8dd; border-radius:10px; background:#f2f6f0; color:#285940; font-size:12px; font-weight:850; }
    .chair-dashboard-view .chair-tabs { gap:8px; padding:12px 18px 0; }
    .chair-dashboard-view .chair-tab { min-height:44px; padding:0 13px; font-size:13px; font-weight:800; }
    .chair-dashboard-view .chair-tab-count { padding:3px 7px; font-size:11px; font-weight:800; }
    .chair-dashboard-view .chair-tab-panel { padding:16px 18px 18px; }
    .chair-dashboard-view .chair-request-card,.chair-dashboard-view .chair-swap-row { margin-bottom:12px; padding:16px; border-color:#e3e9e1; border-radius:14px; background:#fff; }
    .chair-dashboard-view .chair-request-heading { gap:12px; }
    .chair-dashboard-view .chair-request-icon,.chair-dashboard-view .chair-swap-icon { width:38px; height:38px; border-radius:12px; background:#edf3eb; color:#285940; font-size:15px; }
    .chair-dashboard-view .chair-request-title h3 { color:#203d30; font-size:16px; font-weight:800; }
    .chair-dashboard-view .chair-request-title p { margin-top:4px; color:#5d6e63; font-size:13px; font-weight:600; }
    .chair-dashboard-view .chair-status { padding:5px 9px; font-size:11px; font-weight:800; }
    .chair-dashboard-view .chair-request-details { gap:12px; margin:15px 0; }
    .chair-dashboard-view .chair-request-details span { color:#65746a; font-size:11px; font-weight:700; }
    .chair-dashboard-view .chair-request-details strong { color:#2f483a; font-size:13px; font-weight:800; line-height:1.4; }
    .chair-dashboard-view .chair-request-actions { gap:9px; }
    .chair-dashboard-view .chair-request-actions button { min-height:40px; padding:0 13px; border-radius:10px; font-size:12px; font-weight:800; }
    .chair-dashboard-view .chair-approved-note { color:#32684c; font-size:12px; font-weight:800; }
    .chair-dashboard-view .chair-empty-state { padding:36px 18px; }
    .chair-dashboard-view .chair-empty-state > span { width:48px; height:48px; margin-bottom:12px; font-size:20px; }
    .chair-dashboard-view .chair-empty-state h3 { color:#294437; font-size:16px; font-weight:800; }
    .chair-dashboard-view .chair-empty-state p { color:#647268; font-size:13px; font-weight:550; }

    .chair-dashboard-view .chair-swap-topline strong { color:#294437; font-size:14px; font-weight:800; }
    .chair-dashboard-view .chair-swap-content > p { margin:7px 0; color:#2c4939; font-size:14px; font-weight:750; }
    .chair-dashboard-view .chair-swap-meta { gap:12px; color:#5d6d62; font-size:12px; font-weight:650; }
    .chair-dashboard-view .chair-swap-reason { margin-top:10px; color:#5f7065; font-size:12px; line-height:1.55; }
    .chair-dashboard-view .chair-department-list { padding:3px 19px 14px; }
    .chair-dashboard-view .chair-department-row { gap:11px; padding:13px 0; }
    .chair-dashboard-view .chair-department-mark { width:34px; height:34px; border:1px solid #e3e9e0; border-radius:10px; background:#edf3eb; font-size:13px; }
    .chair-dashboard-view .chair-department-name strong { color:#2f483a; font-size:13px; font-weight:800; }
    .chair-dashboard-view .chair-department-name small { color:#637268; font-size:11px; font-weight:650; }
    .chair-dashboard-view .chair-current-label { color:#285940; font-size:10px; font-weight:800; }
    .chair-dashboard-view .chair-empty-inline { padding:17px 20px 20px; color:#637268; font-size:13px; font-weight:600; }
    .chair-dashboard-view .chair-tools-panel { padding-bottom:8px; }
    .chair-dashboard-view .chair-tools-panel .chair-tool-link { min-height:72px; margin:0 19px; gap:12px; padding:13px 0; }
    .chair-dashboard-view .chair-tool-icon { flex-basis:40px; width:40px; height:40px; border:1px solid #e3e9e0; border-radius:12px; background:#edf3eb; color:#285940; font-size:15px; }
    .chair-dashboard-view .chair-tool-link strong { color:#294437; font-size:13px; font-weight:800; }
    .chair-dashboard-view .chair-tool-link small { color:#5e6f64; font-size:11px; font-weight:600; line-height:1.45; }
    .chair-dashboard-view .chair-tool-chevron { color:#87978c; font-size:12px; }
    .chair-dashboard-view .chair-panel-anchor { scroll-margin-top:110px; }
    .chair-dashboard-view .chair-merge-section-title { margin:4px 0 12px; color:#294437; font-size:15px; font-weight:850; }
    .chair-dashboard-view .chair-sent-merge-requests { margin-top:24px; padding-top:19px; border-top:1px solid #e4ebe2; }
    .chair-dashboard-view .chair-request-title h3 { overflow-wrap:anywhere; }
    .chair-dashboard-view .chair-merge-note-input { width:min(280px,100%); min-height:38px; margin-right:6px; padding:0 10px; border:1px solid #ead5d0; border-radius:9px; color:#394d40; font:600 12px/1.4 var(--wr-body); }
    .chair-dashboard-view .chair-merge-decision-note { margin-top:12px; padding:10px 12px; border-left:3px solid #c6a355; border-radius:0 9px 9px 0; background:#fbf7ea; color:#665834; font-size:12px; line-height:1.5; }
    .chair-dashboard-view .chair-request-card.sent.approved { border-color:#d6e7da; }
    .chair-dashboard-view .chair-request-card.sent.rejected { border-color:#ead8d4; }
    .chair-dashboard-view .chair-empty-state.compact { margin-top:0; padding:20px; }

    @media(max-width:1100px) {
        body.portal-shell.chair-portal { --chair-sidebar-width:226px; --chair-shell-gutter:22px; }
        body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
        body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); }
        body.portal-shell.chair-portal .main-layout > .content.chair-dashboard-view { padding-right:24px; padding-left:24px; }
        .chair-dashboard-view .chair-dashboard-hero { display:flex; align-items:flex-start; flex-direction:column; min-height:0; gap:18px; padding:27px 23px; }
        .chair-dashboard-view .chair-hero-mark { position:absolute; top:20px; right:18px; bottom:auto; order:initial; width:92px; height:92px; opacity:.12; font-size:66px; }
        .chair-dashboard-view .chair-hero-actions { position:relative; right:auto; bottom:auto; order:2; display:flex; flex-wrap:wrap; width:100%; gap:9px; }
        .chair-dashboard-view .chair-primary-action,.chair-dashboard-view .chair-secondary-action { flex:1 1 200px; }
        .chair-dashboard-view .chair-workspace-grid { grid-template-columns:minmax(0,1fr); }
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
        body.portal-shell.chair-portal .main-layout > .content.chair-dashboard-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .chair-dashboard-view .chair-dashboard-hero { align-items:flex-start; flex-direction:column; min-height:0; gap:20px; padding:27px 23px; }
        .chair-dashboard-view .chair-hero-actions { width:100%; max-width:none; }
        .chair-dashboard-view .chair-metric-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:540px) {
        .chair-dashboard-view .chair-metric-grid { gap:9px; }
        .chair-dashboard-view .chair-metric-card { min-height:145px; padding:15px; }
        .chair-dashboard-view .chair-metric-label { font-size:12px; }
        .chair-dashboard-view .chair-metric-value { font-size:32px; }
        .chair-dashboard-view .chair-metric-note { font-size:11px; }
        .chair-dashboard-view .chair-hero-meta { flex-direction:column; gap:8px; font-size:12px; }
        .chair-dashboard-view .chair-request-details { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .chair-dashboard-view .chair-tabs { padding-right:12px; padding-left:12px; }
        .chair-dashboard-view .chair-tab-panel { padding:12px; }
        .chair-dashboard-view .chair-merge-note-input { flex:1 1 200px; margin:0; }
    }
</style>

@php
    $chairUser = auth()->user();
    $chairDepartment = collect($departments ?? [])->firstWhere('id', $chairUser?->department_id);
    $departmentRoomCount = (int) ($chairDepartment?->rooms_count ?? 0);
    $campusDepartmentCount = collect($departments ?? [])->count();
    $campusRoomCount = (int) collect($departments ?? [])->sum('rooms_count');
    $pendingMergeCount = collect($mergeRequests ?? [])->where('status', 'pending')->count();
    $swapRequestCount = collect($swapRequests ?? [])->count();
@endphp

<div class="wrapper">
    <div class="main-layout">
        @include('chair.sidebar')

        <main class="content chair-dashboard chair-dashboard-view">
            <section class="chair-dashboard-hero">
                <div class="chair-hero-copy">
                    <span class="chair-eyebrow"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> COLLEGE WORKSPACE</span>
                    <h1>Welcome back, {{ $chairUser?->name ?? 'Chair' }}</h1>
                    <p>A clear workspace for schedules, instructors, subjects, and college activity.</p>
                    <div class="chair-hero-meta">
                        <span><i class="fa-solid fa-building" aria-hidden="true"></i> {{ $chairDepartment?->name ?? 'Your college' }}</span>
                        <span><i class="fa-regular fa-calendar" aria-hidden="true"></i> {{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
                <div class="chair-hero-actions">
                    <a href="{{ route('chair.setschedule') }}" class="chair-primary-action">
                        <i class="fa-solid fa-calendar-plus" aria-hidden="true"></i> Set a schedule
                    </a>
                    <a href="{{ route('chair.schedule.library') }}" class="chair-secondary-action">
                        <i class="fa-solid fa-box-archive" aria-hidden="true"></i> Schedule library
                    </a>
                    <a href="{{ route('chair.requests') }}" class="chair-secondary-action">
                        <i class="fa-solid fa-inbox" aria-hidden="true"></i> Requests &amp; activity
                    </a>
                </div>
                <div class="chair-hero-mark" aria-hidden="true">
                    @if($chairDepartment?->logo)
                        <img src="{{ route('departments.logo', $chairDepartment) }}?v={{ urlencode($chairDepartment->logo) }}" alt="" onerror="this.hidden=true; this.nextElementSibling.hidden=false">
                        <i class="fa-solid fa-calendar-check" aria-hidden="true" hidden></i>
                    @else
                        <i class="fa-solid fa-calendar-check"></i>
                    @endif
                </div>
            </section>

            <section class="chair-metric-grid" aria-label="College overview">
                <article class="chair-metric-card">
                    <span class="chair-metric-icon green"><i class="fa-solid fa-door-open" aria-hidden="true"></i></span>
                    <div class="chair-metric-label">Rooms in your College</div>
                    <div class="chair-metric-value">{{ $departmentRoomCount }}</div>
                    <div class="chair-metric-note">Assigned to {{ $chairDepartment?->name ?? 'your college' }}</div>
                </article>
                <article class="chair-metric-card">
                    <span class="chair-metric-icon blue"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                    <div class="chair-metric-label">Colleges across campus</div>
                    <div class="chair-metric-value">{{ $campusDepartmentCount }}</div>
                    <div class="chair-metric-note">{{ $campusRoomCount }} rooms across campus</div>
                </article>
                <article class="chair-metric-card">
                    <span class="chair-metric-icon amber"><i class="fa-solid fa-code-branch" aria-hidden="true"></i></span>
                    <div class="chair-metric-label">Pending merge requests</div>
                    <div class="chair-metric-value">{{ $pendingMergeCount }}</div>
                    <div class="chair-metric-note">Waiting for your review</div>
                </article>
                <article class="chair-metric-card">
                    <span class="chair-metric-icon violet"><i class="fa-solid fa-right-left" aria-hidden="true"></i></span>
                    <div class="chair-metric-label">Room swap activity</div>
                    <div class="chair-metric-value">{{ $swapRequestCount }}</div>
                    <div class="chair-metric-note">Requests recorded in the system</div>
                </article>
            </section>

            <section class="chair-workspace-grid">
                <div class="chair-main-column">
                    <section class="chair-panel chair-overview-panel">
                        <header class="chair-panel-header">
                            <div>
                                <span class="chair-panel-kicker">YOUR WORKSPACE</span>
                                <h2>Chair tools</h2>
                                <p>Open a workspace to manage schedules, instructors, subjects, or college requests.</p>
                            </div>
                            <span class="chair-panel-icon" aria-hidden="true"><i class="fa-solid fa-compass"></i></span>
                        </header>
                        <div class="chair-dashboard-shortcuts">
                            <a class="chair-tool-link" href="{{ route('chair.setschedule') }}">
                                <span class="chair-tool-icon" aria-hidden="true"><i class="fa-solid fa-calendar-plus"></i></span>
                                <span><strong>Set Schedule</strong><small>Build and publish your college timetable</small></span>
                                <i class="fa-solid fa-chevron-right chair-tool-chevron" aria-hidden="true"></i>
                            </a>
                            <a class="chair-tool-link" href="{{ route('chair.schedule.library') }}">
                                <span class="chair-tool-icon" aria-hidden="true"><i class="fa-solid fa-box-archive"></i></span>
                                <span><strong>Schedule Library</strong><small>Manage academic terms and published schedules</small></span>
                                <i class="fa-solid fa-chevron-right chair-tool-chevron" aria-hidden="true"></i>
                            </a>
                            <a class="chair-tool-link" href="{{ route('chair.requests') }}">
                                <span class="chair-tool-icon" aria-hidden="true"><i class="fa-solid fa-inbox"></i></span>
                                <span><strong>Requests &amp; Activity</strong><small>Review course merge requests and room swaps</small></span>
                                <span class="chair-shortcut-count">{{ $pendingMergeCount + $swapRequestCount }}</span>
                                <i class="fa-solid fa-chevron-right chair-tool-chevron" aria-hidden="true"></i>
                            </a>
                            <a class="chair-tool-link" href="{{ route('chair.setFaculty') }}">
                                <span class="chair-tool-icon" aria-hidden="true"><i class="fa-solid fa-user-gear"></i></span>
                                <span><strong>Instructors &amp; Subjects</strong><small>Manage teaching assignments</small></span>
                                <i class="fa-solid fa-chevron-right chair-tool-chevron" aria-hidden="true"></i>
                            </a>
                            <a class="chair-tool-link" href="{{ route('chair.managesubjects') }}">
                                <span class="chair-tool-icon" aria-hidden="true"><i class="fa-solid fa-book-open"></i></span>
                                <span><strong>Manage Subjects</strong><small>Set up programs and subject catalogues</small></span>
                                <i class="fa-solid fa-chevron-right chair-tool-chevron" aria-hidden="true"></i>
                            </a>
                        </div>
                    </section>
                </div>

                <aside class="chair-side-column">
                    <section class="chair-panel chair-department-panel">
                        <header class="chair-panel-header compact">
                            <div>
                                <span class="chair-panel-kicker">CAMPUS DIRECTORY</span>
                                <h2>Colleges</h2>
                            </div>
                            <span class="chair-panel-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                        </header>

                        @if(collect($departments ?? [])->isNotEmpty())
                            <div class="chair-department-list">
                                @foreach($departments as $department)
                                    <div class="chair-department-row {{ (int) $department->id === (int) ($chairUser?->department_id ?? 0) ? 'current' : '' }}">
                                        <span class="chair-department-mark"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                                        <span class="chair-department-name">
                                            <strong>{{ $department->name }}</strong>
                                            <small>{{ (int) $department->rooms_count }} {{ (int) $department->rooms_count === 1 ? 'room' : 'rooms' }}</small>
                                        </span>
                                        @if((int) $department->id === (int) ($chairUser?->department_id ?? 0))
                                            <span class="chair-current-label">Your college</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="chair-empty-inline">No colleges have been added yet.</div>
                        @endif
                    </section>


                </aside>
            </section>
        </main>
    </div>
</div>



