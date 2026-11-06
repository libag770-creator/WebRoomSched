@include('footerheader.header')

<style>
    /* Dashboard page and the matching WebRoomSched admin shell. */
    .portal-shell.admin-portal { --dashboard-sidebar-width:252px; --dashboard-shell-gutter:28px; background:#e9eee8; }
    .portal-shell.admin-portal .wrapper { min-height:100vh; background:#e9eee8; }
    .portal-shell.admin-portal .main-layout { grid-template-columns:var(--dashboard-sidebar-width) minmax(0,1fr); align-items:stretch; min-height:100vh; }
    .portal-shell.admin-portal .main-layout > .sidebar.admin-sidebar { position:sticky; top:0; align-self:start; width:var(--dashboard-sidebar-width); height:100vh; min-height:100vh; padding:25px 15px 18px; border:0; background:linear-gradient(165deg,#112f29 0%,#173b33 54%,#285342 100%); color:#f7f5eb; box-shadow:12px 0 32px rgba(20,47,37,.1); scrollbar-color:#496a59 transparent; }
    .portal-shell.admin-portal .portal-brand-mark { background:#d8c18c; color:#173b33; box-shadow:0 8px 18px rgba(0,0,0,.18); }
    .portal-shell.admin-portal .portal-brand-copy strong { color:#fffdf5; }
    .portal-shell.admin-portal .portal-brand-copy small { color:#bdc9bd; }
    .portal-shell.admin-portal .faculty-sidebar-section { color:#c3b27f; }
    .portal-shell.admin-portal .sidebar-menu a { color:#d1ddd3; }
    .portal-shell.admin-portal .sidebar-menu a:hover { border-color:rgba(255,255,255,.09); background:rgba(255,255,255,.08); color:#fff; }
    .portal-shell.admin-portal .sidebar-menu li.active > a,.portal-shell.admin-portal .sidebar-menu a[aria-current="page"] { border-color:rgba(220,199,143,.26); background:rgba(220,199,143,.13); color:#fff9e8; }
    .portal-shell.admin-portal .sidebar-menu li.active > a::before,.portal-shell.admin-portal .sidebar-menu a[aria-current="page"]::before { background:#d8c18c; }
    .portal-shell.admin-portal .faculty-nav-icon { color:#b5c5b9; }
    .portal-shell.admin-portal .sidebar-menu li.active .faculty-nav-icon,.portal-shell.admin-portal .sidebar-menu a[aria-current="page"] .faculty-nav-icon { color:#e2ca8d; }
    .portal-shell.admin-portal .faculty-sidebar-footer { border-color:rgba(255,255,255,.16); }
    .portal-shell.admin-portal .faculty-sidebar-footer strong { color:#f3f2e9; }
    .portal-shell.admin-portal .faculty-sidebar-footer span:not(.faculty-footer-indicator) { color:#b9c7bc; }
    .portal-shell.admin-portal .top-header { position:fixed; top:16px; right:var(--dashboard-shell-gutter); left:calc(var(--dashboard-sidebar-width) + var(--dashboard-shell-gutter)); z-index:1000; min-height:72px; padding:10px 15px 10px 22px; border:1px solid rgba(224,231,224,.96); border-radius:18px; background:rgba(255,255,255,.95); box-shadow:0 12px 34px rgba(31,57,43,.09); backdrop-filter:blur(18px); }
    .portal-shell.admin-portal .header-title small { color:#9a8450; font-size:10px; }
    .portal-shell.admin-portal .header-title h3 { color:#173b33; font-size:20px; }
    .portal-shell.admin-portal .header-action { color:#375448; }
    .portal-shell.admin-portal .sidebar-toggle { top:36px; left:calc(var(--dashboard-sidebar-width) - 15px); z-index:1200; border-color:rgba(216,193,140,.52); background:#f8f5eb; color:#224336; }
    .portal-shell.admin-portal.sidebar-collapsed { --dashboard-sidebar-width:76px; }
    .portal-shell.admin-portal.sidebar-collapsed .main-layout { grid-template-columns:76px minmax(0,1fr); }
    .portal-shell.admin-portal.sidebar-collapsed .main-layout > .sidebar.admin-sidebar { width:76px; padding-right:10px; padding-left:10px; }
    .portal-shell.admin-portal.sidebar-collapsed .sidebar-toggle { left:61px; }
    .portal-shell.admin-portal .main-layout > .content.admin-dashboard-view { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }

    .admin-dashboard-view .admin-home { width:min(100%,1480px); margin:0 auto; }
    .admin-dashboard-view .ah-welcome { min-height:235px; margin-bottom:29px; padding:clamp(27px,4vw,43px); border:1px solid rgba(231,214,173,.22); border-radius:24px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); }
    .admin-dashboard-view .ah-welcome::after { right:-45px; bottom:-175px; width:340px; height:340px; border-color:rgba(232,215,169,.25); box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); }
    .admin-dashboard-view .ah-welcome-copy { max-width:720px; }
    .admin-dashboard-view .ah-eyebrow { color:#e2c987; font-size:10px; }
    .admin-dashboard-view .ah-welcome h1 { max-width:750px; font-size:clamp(31px,3.2vw,45px); line-height:1.12; }
    .admin-dashboard-view .ah-welcome-copy > p:not(.ah-eyebrow) { max-width:640px; font-size:15px; }
    .admin-dashboard-view .ah-hero-actions { position:relative; z-index:2; display:flex; flex-wrap:wrap; gap:9px; margin-top:21px; }
    .admin-dashboard-view .ah-hero-action { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:42px; padding:0 15px; border:1px solid rgba(255,255,255,.24); border-radius:10px; color:#fff; font-size:12px; font-weight:700; text-decoration:none; transition:background .16s ease,transform .16s ease; }
    .admin-dashboard-view .ah-hero-action:hover { transform:translateY(-1px); background:rgba(255,255,255,.14); }
    .admin-dashboard-view .ah-hero-action.primary { border-color:#e8d7ae; background:#e8d7ae; color:#203d34; }
    .admin-dashboard-view .ah-hero-action.primary:hover { background:#f1e3c3; }
    .admin-dashboard-view .ah-hero-note { margin-top:13px !important; font-size:12px !important; }
    .admin-dashboard-view .ah-date { min-width:230px; padding:17px; border-color:rgba(255,255,255,.23); border-radius:15px; background:rgba(255,255,255,.1); }
    .admin-dashboard-view .ah-date > i { color:#e2c987; font-size:19px; }
    .admin-dashboard-view .ah-date span { color:#d8e2d8; font-size:10px; }
    .admin-dashboard-view .ah-date strong { color:#fff; font-size:13px; }
    .admin-dashboard-view .ah-section-heading { display:flex; justify-content:space-between; align-items:flex-end; margin:0 0 15px; }
    .admin-dashboard-view .ah-section-heading > div::before { content:"CAMPUS OVERVIEW"; display:block; margin-bottom:7px; color:#917b4a; font-size:10px; font-weight:800; letter-spacing:.15em; }
    .admin-dashboard-view .ah-section-heading h2 { color:#173b33; font-size:23px; }
    .admin-dashboard-view .ah-section-heading p { color:#5d6d63; font-size:13px; }
    .admin-dashboard-view .ah-stats { gap:13px; margin:0 0 25px; }
    .admin-dashboard-view a.ah-stat { display:block; min-height:145px; padding:19px 20px; border-color:#dfe7dd; color:inherit; text-decoration:none; }
    .admin-dashboard-view a.ah-stat:hover { transform:translateY(-3px); border-color:#9db7a3; box-shadow:0 14px 30px rgba(31,57,43,.09); }
    .admin-dashboard-view .ah-stat-label { color:#4e6256; font-size:12px; font-weight:700; }
    .admin-dashboard-view .ah-stat-icon { width:38px; height:38px; border:1px solid #e2e9df; background:#eef3eb; color:#285940; font-size:15px; }
    .admin-dashboard-view .ah-stat-number { margin-top:14px; color:#173b33; font-size:34px; }
    .admin-dashboard-view .ah-stat-note { color:#5b6c61; font-size:11px; }
    .admin-dashboard-view .ah-stat:nth-child(2) .ah-stat-icon { background:#f5f0e4; color:#917b4a; }
    .admin-dashboard-view .ah-stat:nth-child(3) .ah-stat-icon { background:#edf1ed; color:#536f60; }
    .admin-dashboard-view .ah-stat:nth-child(4) .ah-stat-icon { background:#e8f0e9; color:#32684c; }
    .admin-dashboard-view .ah-main-grid { grid-template-columns:minmax(0,1.35fr) minmax(300px,.8fr); gap:17px; }
    .admin-dashboard-view .ah-panel { border-color:#dfe7dd; border-radius:18px; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .admin-dashboard-view .ah-panel-head { padding:20px 21px; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .admin-dashboard-view .ah-panel-head h2 { color:#173b33; font-size:18px; }
    .admin-dashboard-view .ah-panel-head p { color:#5c6d62; font-size:12px; }
    .admin-dashboard-view .ah-panel-body { padding:16px 20px 20px; }
    .admin-dashboard-view .ah-manage-list { grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
    .admin-dashboard-view .ah-manage-link { position:relative; min-height:115px; align-items:flex-start; gap:12px; padding:15px; border:1px solid #e3e9e1; border-radius:13px; background:#fff; }
    .admin-dashboard-view .ah-manage-link:hover { border-color:#b5c9b7; background:#f8faf6; box-shadow:0 8px 19px rgba(31,57,43,.06); }
    .admin-dashboard-view .ah-manage-icon { flex-basis:38px; width:38px; height:38px; border:1px solid #e3e9e0; border-radius:12px; background:#edf3eb; color:#285940; font-size:14px; }
    .admin-dashboard-view .ah-manage-copy { padding:2px 17px 0 0; gap:5px; }
    .admin-dashboard-view .ah-manage-copy strong { color:#294437; font-size:13px; }
    .admin-dashboard-view .ah-manage-copy span { color:#5e6f64; font-size:11px; }
    .admin-dashboard-view .ah-manage-link > .fa-chevron-right { position:absolute; top:18px; right:15px; color:#87978c; }
    .admin-dashboard-view .ah-coverage-item { min-height:58px; gap:12px; }
    .admin-dashboard-view .ah-coverage-icon { width:34px; height:34px; border:1px solid #e3e9e0; border-radius:11px; background:#edf3eb; color:#285940; font-size:13px; }
    .admin-dashboard-view .ah-coverage-copy strong { color:#2f483a; font-size:12px; }
    .admin-dashboard-view .ah-coverage-copy span { color:#637268; font-size:10px; }
    .admin-dashboard-view .ah-coverage-value { color:#173b33; font-size:18px; }
    .admin-dashboard-view .ah-callout { margin-top:18px; padding:15px; border-color:#e5e5d7; background:#f7f6ee; }
    .admin-dashboard-view .ah-callout-icon { color:#8c7645; font-size:15px; }
    .admin-dashboard-view .ah-callout strong { color:#354b3e; font-size:12px; }
    .admin-dashboard-view .ah-callout p { color:#5d6b61; font-size:11px; }

    @media(max-width:1100px) {
        .portal-shell.admin-portal { --dashboard-sidebar-width:226px; --dashboard-shell-gutter:22px; }
        .portal-shell.admin-portal.sidebar-collapsed { --dashboard-sidebar-width:76px; }
        .portal-shell.admin-portal .main-layout { grid-template-columns:var(--dashboard-sidebar-width) minmax(0,1fr); }
        .portal-shell.admin-portal .main-layout > .content.admin-dashboard-view { padding-right:24px; padding-left:24px; }
        .admin-dashboard-view .ah-main-grid { grid-template-columns:minmax(0,1fr); }
    }
    @media(max-width:760px) {
        .portal-shell.admin-portal .top-header { position:sticky; top:0; right:auto; left:auto; min-height:64px; margin:0; padding:9px 12px 9px 54px; border:0; border-radius:0 0 15px 15px; box-shadow:0 5px 18px rgba(31,57,43,.08); }
        .portal-shell.admin-portal .header-title h3 { font-size:17px; }
        .portal-shell.admin-portal .wrapper,.portal-shell.admin-portal .main-layout { min-height:calc(100vh - 64px); }
        .portal-shell.admin-portal .main-layout { display:block; }
        .portal-shell.admin-portal .main-layout > .sidebar.admin-sidebar { position:fixed; top:0; bottom:0; left:0; z-index:1100; width:262px; height:100dvh; min-height:0; padding:20px 15px; transform:translateX(0); }
        .portal-shell.admin-portal.sidebar-collapsed .main-layout > .sidebar.admin-sidebar { width:262px; transform:translateX(-110%); }
        .portal-shell.admin-portal .sidebar-backdrop { position:fixed; inset:0; z-index:1050; }
        .portal-shell.admin-portal .sidebar-toggle,.portal-shell.admin-portal.sidebar-collapsed .sidebar-toggle { top:17px; left:14px; z-index:1200; }
        .portal-shell.admin-portal:not(.sidebar-collapsed) .sidebar-toggle { top:17px; left:270px; }
        .portal-shell.admin-portal .main-layout > .content.admin-dashboard-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .admin-dashboard-view .ah-welcome { align-items:flex-start; flex-direction:column; min-height:0; gap:20px; padding:27px 23px; }
        .admin-dashboard-view .ah-date { min-width:0; }
    }
    @media(max-width:540px) {
        .admin-dashboard-view .ah-stats { grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; }
        .admin-dashboard-view a.ah-stat { min-height:132px; padding:14px; }
        .admin-dashboard-view .ah-stat-label { font-size:11px; }
        .admin-dashboard-view .ah-stat-number { font-size:30px; }
        .admin-dashboard-view .ah-stat-note { font-size:10px; }
        .admin-dashboard-view .ah-manage-list { grid-template-columns:minmax(0,1fr); }
        .admin-dashboard-view .ah-manage-link { min-height:90px; }
    }
</style>

<div class="wrapper">
    <div class="main-layout">
        @include('admin.sidebar')

        <main class="content admin-dashboard admin-dashboard-view">
           
            <section class="admin-home">
                <header class="ah-welcome">
                    <div class="ah-welcome-copy">
                        <p class="ah-eyebrow"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Campus control center</p>
                        <h1>Welcome, {{ Auth::user()->name ?? 'Administrator' }}</h1>
                        <p>Keep colleges, classrooms, schedules, and campus access organized from one workspace.</p>
                        <div class="ah-hero-actions">
                            <a class="ah-hero-action primary" href="{{ route('admin.schedules') }}"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i> Browse schedules</a>
                            <a class="ah-hero-action" href="{{ route('admin.buildings') }}"><i class="fa-solid fa-building" aria-hidden="true"></i> Buildings &amp; rooms</a>
                        </div>
                        <p class="ah-hero-note"><i class="fa-solid fa-square-check" aria-hidden="true"></i> Campus records at a glance</p>
                    </div>
                    <div class="ah-date">
                        <i class="fa-regular fa-calendar"></i>
                        <div>
                            <span>Today</span>
                            <strong>{{ now()->format('l, F j, Y') }}</strong>
                        </div>
                    </div>
                </header>

                <div class="ah-section-heading">
                    <div>
                        <h2>Campus at a glance</h2>
                        <p>A clear snapshot of the information managed in WebRoomSched.</p>
                    </div>
                </div>

                <div class="ah-stats">
                    <a class="ah-stat" href="{{ route('admin.departments') }}" aria-label="Open college management">
                        <div class="ah-stat-top"><span class="ah-stat-label">Colleges</span><span class="ah-stat-icon"><i class="fa-solid fa-building-columns"></i></span></div>
                        <div class="ah-stat-number">{{ $totalDepartments ?? 0 }}</div>
                        <div class="ah-stat-note">Academic units in the campus</div>
                    </a>
                    <a class="ah-stat" href="{{ route('admin.buildings') }}" aria-label="Open buildings and room management">
                        <div class="ah-stat-top"><span class="ah-stat-label">Buildings &amp; rooms</span><span class="ah-stat-icon"><i class="fa-solid fa-door-open"></i></span></div>
                        <div class="ah-stat-number">{{ $totalRooms ?? 0 }}</div>
                        <div class="ah-stat-note">{{ $totalBuildings ?? 0 }} buildings · registered spaces</div>
                    </a>
                    <a class="ah-stat" href="{{ route('admin.manageusers') }}" aria-label="Open user account management">
                        <div class="ah-stat-top"><span class="ah-stat-label">User accounts</span><span class="ah-stat-icon"><i class="fa-solid fa-users"></i></span></div>
                        <div class="ah-stat-number">{{ $totalUsers ?? 0 }}</div>
                        <div class="ah-stat-note">Instructor, chairs, and administrators</div>
                    </a>
                    <a class="ah-stat" href="{{ route('admin.schedules') }}" aria-label="Open campus schedules">
                        <div class="ah-stat-top"><span class="ah-stat-label">Schedules</span><span class="ah-stat-icon"><i class="fa-regular fa-calendar-check"></i></span></div>
                        <div class="ah-stat-number">{{ $activeSchedules ?? 0 }}</div>
                        <div class="ah-stat-note">Schedules recorded in the system</div>
                    </a>
                </div>

                <div class="ah-main-grid">
                    <section class="ah-panel">
                        <div class="ah-panel-head">
                                    <div>
                                        <h2>Quick actions</h2>
                                        <p>Jump directly to a campus task.</p>
                            </div>
                        </div>
                        <div class="ah-panel-body">
                            <div class="ah-manage-list">
                                <a class="ah-manage-link" href="{{ route('admin.departments') }}">
                                    <span class="ah-manage-icon"><i class="fa-solid fa-building-columns"></i></span>
                                    <span class="ah-manage-copy"><strong>Colleges</strong><span>Review campus Colleges and their details</span></span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                                <a class="ah-manage-link" href="{{ route('admin.buildings') }}">
                                    <span class="ah-manage-icon"><i class="fa-solid fa-building"></i></span>
                                    <span class="ah-manage-copy"><strong>Buildings &amp; Rooms</strong><span>Maintain buildings and classroom records</span></span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                                <a class="ah-manage-link" href="{{ route('admin.schedules') }}">
                                    <span class="ah-manage-icon"><i class="fa-solid fa-calendar-days"></i></span>
                                    <span class="ah-manage-copy"><strong>Schedules</strong><span>Browse schedules across campus rooms</span></span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                                <a class="ah-manage-link" href="{{ route('admin.manageusers') }}">
                                    <span class="ah-manage-icon"><i class="fa-solid fa-user-group"></i></span>
                                    <span class="ah-manage-copy"><strong>Manage Users</strong><span>Maintain accounts and access details</span></span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </section>

                    <aside>
                        <section class="ah-panel">
                            <div class="ah-panel-head">
                                <div>
                                    <h2>System overview</h2>
                                    <p>Campus data by area</p>
                                </div>
                            </div>
                            <div class="ah-panel-body">
                                <div class="ah-coverage">
                                    <div class="ah-coverage-item">
                                        <span class="ah-coverage-icon"><i class="fa-solid fa-building-columns"></i></span>
                                        <span class="ah-coverage-copy"><strong>Colleges</strong><span>Campus academic units</span></span>
                                        <span class="ah-coverage-value">{{ $totalDepartments ?? 0 }}</span>
                                    </div>
                                    <div class="ah-coverage-item">
                                        <span class="ah-coverage-icon"><i class="fa-solid fa-building"></i></span>
                                        <span class="ah-coverage-copy"><strong>Buildings</strong><span>Campus facilities</span></span>
                                        <span class="ah-coverage-value">{{ $totalBuildings ?? 0 }}</span>
                                    </div>
                                    <div class="ah-coverage-item">
                                        <span class="ah-coverage-icon"><i class="fa-solid fa-door-open"></i></span>
                                        <span class="ah-coverage-copy"><strong>Rooms</strong><span>Bookable and scheduled spaces</span></span>
                                        <span class="ah-coverage-value">{{ $totalRooms ?? 0 }}</span>
                                    </div>
                                    <div class="ah-coverage-item">
                                        <span class="ah-coverage-icon"><i class="fa-solid fa-users"></i></span>
                                        <span class="ah-coverage-copy"><strong>Accounts</strong><span>Registered system users</span></span>
                                        <span class="ah-coverage-value">{{ $totalUsers ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="ah-callout">
                                    <span class="ah-callout-icon"><i class="fa-solid fa-circle-check"></i></span>
                                    <div>
                                        <strong>Keep campus records current</strong>
                                        <p>Accurate room, schedule, and account information helps instructor and chairs plan around reliable availability.</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>

            </section>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const search = document.getElementById('adminDashboardSearch');
                    if (!search) return;

                    const searchableCards = document.querySelectorAll('.admin-home .ah-stat, .admin-home .ah-manage-link');
                    search.addEventListener('input', function () {
                        const query = search.value.trim().toLocaleLowerCase();
                        searchableCards.forEach(function (card) {
                            const matches = card.textContent.toLocaleLowerCase().includes(query);
                            card.hidden = !matches;
                        });
                    });
                });
            </script>
        </main>
    </div>
</div>









