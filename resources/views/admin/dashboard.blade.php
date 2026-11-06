@include('footerheader.header')

<div class="wrapper">
    <div class="main-layout">
        @include('admin.sidebar')

        <main class="content">
            <style>
                .admin-home {
                    --ah-green: #087443;
                    --ah-green-dark: #075b36;
                    --ah-ink: #17251e;
                    --ah-muted: #708078;
                    --ah-line: #e8eeea;
                    --ah-soft: #f5f8f6;
                    width: min(1480px, 100%);
                    margin: 0 auto;
                    padding: 30px clamp(18px, 3vw, 42px) 38px;
                    color: var(--ah-ink);
                }
                .admin-home * { box-sizing: border-box; }
                .ah-welcome {
                    position: relative;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 24px;
                    min-height: 190px;
                    overflow: hidden;
                    padding: 34px clamp(24px, 4vw, 48px);
                    border-radius: 22px;
                    color: #fff;
                    background: linear-gradient(115deg, #075d38 0%, #0a8050 57%, #159267 100%);
                    box-shadow: 0 14px 34px rgba(8, 116, 67, .16);
                }
                .ah-welcome::before, .ah-welcome::after {
                    position: absolute;
                    content: "";
                    border: 1px solid rgba(255,255,255,.13);
                    border-radius: 50%;
                    pointer-events: none;
                }
                .ah-welcome::before { width: 310px; height: 310px; right: 85px; top: -175px; }
                .ah-welcome::after { width: 230px; height: 230px; right: -55px; bottom: -150px; }
                .ah-welcome-copy, .ah-date { position: relative; z-index: 1; }
                .ah-eyebrow {
                    display: flex; align-items: center; gap: 8px;
                    margin: 0 0 12px; color: rgba(255,255,255,.74);
                    font-size: 11px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase;
                }
                .ah-welcome h1 { margin: 0; color: #fff; font-size: clamp(27px, 3vw, 38px); line-height: 1.1; letter-spacing: -.04em; }
                .ah-welcome-copy > p { max-width: 560px; margin: 12px 0 0; color: rgba(255,255,255,.79); font-size: 14px; line-height: 1.65; }
                .ah-date {
                    display: flex; align-items: center; gap: 12px; flex-shrink: 0;
                    padding: 13px 16px; border: 1px solid rgba(255,255,255,.24);
                    border-radius: 14px; background: rgba(255,255,255,.1); backdrop-filter: blur(8px);
                }
                .ah-date i { font-size: 17px; }
                .ah-date span { display: block; color: rgba(255,255,255,.68); font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
                .ah-date strong { display: block; margin-top: 3px; color: #fff; font-size: 13px; font-weight: 700; }
                .ah-section-heading { display:flex; justify-content:space-between; align-items:end; gap:16px; margin: 30px 0 15px; }
                .ah-section-heading h2 { margin:0; color:var(--ah-ink); font-size:17px; font-weight:800; letter-spacing:-.02em; }
                .ah-section-heading p { margin:5px 0 0; color:var(--ah-muted); font-size:12px; }
                .ah-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:15px; }
                .ah-stat {
                    min-width:0; padding:19px 20px 18px; border:1px solid var(--ah-line); border-radius:16px;
                    background:#fff; box-shadow:0 3px 12px rgba(24,39,30,.035); transition:transform .18s ease,box-shadow .18s ease;
                }
                .ah-stat:hover { transform:translateY(-2px); box-shadow:0 10px 22px rgba(24,39,30,.075); }
                .ah-stat-top { display:flex; justify-content:space-between; align-items:center; gap:10px; }
                .ah-stat-label { color:#75827b; font-size:12px; font-weight:700; }
                .ah-stat-icon { display:grid; place-items:center; width:38px; height:38px; border-radius:12px; color:var(--ah-green); background:#edf7f1; font-size:15px; }
                .ah-stat-number { margin-top:17px; color:var(--ah-ink); font-size:31px; font-weight:800; line-height:1; letter-spacing:-.045em; }
                .ah-stat-note { margin-top:8px; color:#97a29c; font-size:11px; }
                .ah-main-grid { display:grid; grid-template-columns:minmax(0,1.45fr) minmax(285px,.85fr); gap:18px; margin-top:20px; align-items:start; }
                .ah-panel { min-width:0; overflow:hidden; border:1px solid var(--ah-line); border-radius:17px; background:#fff; box-shadow:0 3px 12px rgba(24,39,30,.035); }
                .ah-panel-head { display:flex; justify-content:space-between; align-items:center; gap:14px; padding:20px 22px 16px; border-bottom:1px solid #f0f3f1; }
                .ah-panel-head h2 { margin:0; color:var(--ah-ink); font-size:15px; font-weight:800; }
                .ah-panel-head p { margin:5px 0 0; color:var(--ah-muted); font-size:11px; }
                .ah-panel-body { padding:18px 22px 22px; }
                .ah-manage-list { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:11px; }
                .ah-manage-link {
                    display:flex; align-items:center; gap:13px; min-height:75px; padding:14px;
                    border:1px solid #eaf0ec; border-radius:13px; color:inherit; text-decoration:none;
                    background:#fff; transition:background .18s ease,border-color .18s ease,transform .18s ease;
                }
                .ah-manage-link:hover { transform:translateY(-1px); border-color:#b7d7c3; background:#f7fbf8; }
                .ah-manage-icon { display:grid; place-items:center; width:41px; height:41px; flex:0 0 41px; border-radius:12px; color:var(--ah-green); background:#edf7f1; font-size:16px; }
                .ah-manage-copy { min-width:0; flex:1; }
                .ah-manage-copy strong { display:block; color:#26362d; font-size:12px; font-weight:800; }
                .ah-manage-copy span { display:block; margin-top:4px; color:#849088; font-size:10px; line-height:1.4; }
                .ah-manage-link > i { color:#a2aea6; font-size:11px; }
                .ah-coverage { display:flex; flex-direction:column; gap:16px; }
                .ah-coverage-item { display:grid; grid-template-columns:35px 1fr auto; gap:11px; align-items:center; }
                .ah-coverage-icon { display:grid; place-items:center; width:35px; height:35px; border-radius:11px; color:#168054; background:#f0f7f3; font-size:13px; }
                .ah-coverage-copy strong { display:block; color:#344239; font-size:11px; font-weight:750; }
                .ah-coverage-copy span { display:block; margin-top:3px; color:#8b9790; font-size:10px; }
                .ah-coverage-value { color:#25342b; font-size:13px; font-weight:800; }
                .ah-callout { display:flex; gap:12px; margin-top:17px; padding:14px; border:1px solid #dceee3; border-radius:13px; background:#f5faf7; }
                .ah-callout-icon { display:grid; place-items:center; width:32px; height:32px; flex:0 0 32px; border-radius:10px; color:#087443; background:#e4f3e9; }
                .ah-callout strong { display:block; color:#28503a; font-size:11px; }
                .ah-callout p { margin:4px 0 0; color:#718078; font-size:10px; line-height:1.55; }
                .ah-footer { margin-top:22px; color:#9aa49e; text-align:center; font-size:10px; }
                @media(max-width:1100px) { .ah-stats { grid-template-columns:repeat(2,minmax(0,1fr)); } .ah-main-grid { grid-template-columns:1fr; } }
                @media(max-width:650px) {
                    .admin-home { padding:20px 14px 28px; }
                    .ah-welcome { display:block; padding:25px 22px; border-radius:18px; }
                    .ah-date { display:inline-flex; margin-top:20px; }
                    .ah-stats { gap:10px; }
                    .ah-stat { padding:15px; }
                    .ah-stat-number { font-size:27px; }
                    .ah-manage-list { grid-template-columns:1fr; }
                    .ah-panel-head,.ah-panel-body { padding-left:16px; padding-right:16px; }
                }
            </style>

            <style>
                /* Light neumorphic dashboard, coordinated with the shared shell */
                .admin-home { --ah-ink:#24382f; --ah-muted:#52675a; --ah-line:#dee6df; color:#30483d !important; }
                .ah-welcome { min-height:205px !important; padding:34px clamp(24px,4vw,46px) !important; color:#24382f !important; background:linear-gradient(115deg,#e5ece6,#f5f7f4 58%,#e9efe9) !important; border:1px solid #d5e0ec !important; border-radius:23px !important; box-shadow:9px 9px 20px #dce2dc,-9px -9px 20px #fff !important; }
                .ah-welcome::before,.ah-welcome::after { border-color:rgba(76,119,166,.12) !important; background:rgba(100,143,190,.05) !important; }
                .ah-eyebrow { color:#285c50 !important; font-size:16px !important; line-height:1.5 !important; letter-spacing:.08em !important; }
                .ah-welcome h1 { color:#24382f !important; font-size:clamp(35px,3.5vw,46px) !important; line-height:1.2 !important; }
                .ah-welcome-copy>p { color:#4e6658 !important; font-size:18px !important; line-height:1.7 !important; }
                .ah-date { color:#354d69 !important; background:#eef2ee !important; border:1px solid #d5e0ec !important; border-radius:15px !important; box-shadow:inset 4px 4px 8px #dbe2eb,inset -4px -4px 8px #fff !important; }
                .ah-date i { color:#337767 !important; font-size:20px !important; }
                .ah-date span { color:#526b5c !important; font-size:16px !important; }
                .ah-date strong { color:#2e4b3e !important; font-size:16px !important; }
                .ah-section-heading h2 { color:#24382f !important; font-size:27px !important; }
                .ah-section-heading p { color:#52675a !important; font-size:16px !important; line-height:1.6 !important; }
                .ah-stat,.ah-panel { color:#30483d !important; background:#f0f3ef !important; border:1px solid #dfe7e0 !important; border-radius:18px !important; box-shadow:7px 7px 16px #d7dee8,-7px -7px 16px #fff !important; }
                .ah-stat { padding:22px !important; }
                .ah-stat-label { color:#405873 !important; font-size:17px !important; }
                .ah-stat-icon,.ah-manage-icon,.ah-coverage-icon { color:#3c7764 !important; background:#e6eef7 !important; box-shadow:inset 3px 3px 6px #dbe2eb,inset -3px -3px 6px #fff !important; }
                .ah-stat-icon { width:46px !important; height:46px !important; font-size:19px !important; }
                .ah-stat-number { color:#24382f !important; font-size:39px !important; }
                .ah-stat-note { color:#536b5b !important; font-size:16px !important; line-height:1.55 !important; }
                .ah-panel-head { border-bottom-color:#dfe7e0 !important; padding:22px 24px 17px !important; }
                .ah-panel-head h2 { color:#2e4b3e !important; font-size:22px !important; }
                .ah-panel-head p { color:#52675a !important; font-size:16px !important; line-height:1.65 !important; }
                .ah-panel-body { padding:20px 24px 24px !important; }
                .ah-manage-link { min-height:88px !important; padding:16px !important; color:#30483d !important; background:#f0f3ef !important; border:1px solid #dfe7e0 !important; border-radius:15px !important; box-shadow:4px 4px 9px #d7dee8,-4px -4px 9px #fff !important; }
                .ah-manage-link:hover { background:#f7f8f6 !important; border-color:#c5d5e6 !important; }
                .ah-manage-icon { width:46px !important; height:46px !important; flex-basis:46px !important; font-size:19px !important; }
                .ah-manage-copy strong { color:#304b3b !important; font-size:17px !important; }
                .ah-manage-copy span { color:#52675a !important; font-size:16px !important; line-height:1.55 !important; }
                .ah-manage-link>i { color:#337767 !important; font-size:15px !important; }
                .ah-coverage-copy strong { color:#304b3b !important; font-size:16px !important; }
                .ah-coverage-copy span { color:#536b5b !important; font-size:16px !important; line-height:1.6 !important; }
                .ah-coverage-value { color:#24382f !important; font-size:18px !important; }
                .ah-callout { color:#344d40 !important; background:#e8eee9 !important; border:1px solid #d5e0ed !important; border-radius:15px !important; box-shadow:inset 3px 3px 6px #dfe6df,inset -3px -3px 6px #fff !important; }
                .ah-callout-icon { color:#3c7764 !important; background:#e2ebe4 !important; }
                .ah-callout strong { color:#304b3b !important; font-size:16px !important; }
                .ah-callout p { color:#4e6658 !important; font-size:16px !important; line-height:1.7 !important; }
                .ah-footer { color:#536b5b !important; font-size:16px !important; }
                @media(max-width:900px) { .ah-main-grid { grid-template-columns:1fr !important; } }
                @media(max-width:650px) { .ah-welcome{min-height:0!important;padding:25px 20px!important}.ah-welcome h1{font-size:32px!important}.ah-welcome-copy>p{font-size:16px!important}.ah-stats{grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:11px!important}.ah-stat{padding:16px!important}.ah-stat-label{font-size:16px!important}.ah-stat-number{font-size:34px!important}.ah-stat-note{font-size:15px!important}.ah-manage-list{grid-template-columns:1fr!important}.ah-panel-head,.ah-panel-body{padding-left:16px!important;padding-right:16px!important} }
                @media(max-width:390px) { .ah-stats{grid-template-columns:1fr!important} }
            </style>
            <section class="admin-home">
                <header class="ah-welcome">
                    <div class="ah-welcome-copy">
                        <p class="ah-eyebrow"><i class="fa-solid fa-shield-halved"></i> Administrator workspace</p>
                        <h1>Welcome, {{ Auth::user()->name ?? 'Administrator' }}</h1>
                        <p>Manage campus rooms, schedules, departments, and accounts from one place.</p>
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
                        <p>A live summary of the information managed in WebRoomSched.</p>
                    </div>
                </div>

                <div class="ah-stats">
                    <article class="ah-stat">
                        <div class="ah-stat-top"><span class="ah-stat-label">Departments</span><span class="ah-stat-icon"><i class="fa-solid fa-building-columns"></i></span></div>
                        <div class="ah-stat-number">{{ $totalDepartments ?? 0 }}</div>
                        <div class="ah-stat-note">Academic units in the campus</div>
                    </article>
                    <article class="ah-stat">
                        <div class="ah-stat-top"><span class="ah-stat-label">Buildings &amp; rooms</span><span class="ah-stat-icon"><i class="fa-solid fa-door-open"></i></span></div>
                        <div class="ah-stat-number">{{ $totalRooms ?? 0 }}</div>
                        <div class="ah-stat-note">{{ $totalBuildings ?? 0 }} buildings · registered spaces</div>
                    </article>
                    <article class="ah-stat">
                        <div class="ah-stat-top"><span class="ah-stat-label">User accounts</span><span class="ah-stat-icon"><i class="fa-solid fa-users"></i></span></div>
                        <div class="ah-stat-number">{{ $totalUsers ?? 0 }}</div>
                        <div class="ah-stat-note">Faculty, chairs, and administrators</div>
                    </article>
                    <article class="ah-stat">
                        <div class="ah-stat-top"><span class="ah-stat-label">Schedules</span><span class="ah-stat-icon"><i class="fa-regular fa-calendar-check"></i></span></div>
                        <div class="ah-stat-number">{{ $activeSchedules ?? 0 }}</div>
                        <div class="ah-stat-note">Schedules recorded in the system</div>
                    </article>
                </div>

                <div class="ah-main-grid">
                    <section class="ah-panel">
                        <div class="ah-panel-head">
                            <div>
                                <h2>Administration</h2>
                                <p>Go directly to the campus information you need to manage.</p>
                            </div>
                        </div>
                        <div class="ah-panel-body">
                            <div class="ah-manage-list">
                                <a class="ah-manage-link" href="{{ route('admin.departments') }}">
                                    <span class="ah-manage-icon"><i class="fa-solid fa-building-columns"></i></span>
                                    <span class="ah-manage-copy"><strong>Departments</strong><span>Review campus departments and their details</span></span>
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
                                <a class="ah-manage-link" href="{{ route('admin.roomreassignment') }}">
                                    <span class="ah-manage-icon"><i class="fa-solid fa-right-left"></i></span>
                                    <span class="ah-manage-copy"><strong>Room Reassignment</strong><span>Review and manage room transfers</span></span>
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
                                    <p>Current records by area</p>
                                </div>
                            </div>
                            <div class="ah-panel-body">
                                <div class="ah-coverage">
                                    <div class="ah-coverage-item">
                                        <span class="ah-coverage-icon"><i class="fa-solid fa-building-columns"></i></span>
                                        <span class="ah-coverage-copy"><strong>Departments</strong><span>Campus academic units</span></span>
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
                                        <p>Accurate room, schedule, and account information helps faculty and chairs plan around reliable availability.</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>

                <footer class="ah-footer">WebRoomSched · Administrator workspace</footer>
            </section>
        </main>
    </div>
</div>

<!-- @include('footerheader.footer') -->









