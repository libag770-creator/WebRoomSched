@include('footerheader.header')

<style>
    body.portal-shell.chair-portal { --chair-sidebar-width:252px; --chair-shell-gutter:28px; background:#e9eee8; }
    body.portal-shell.chair-portal .wrapper,.chair-room-availability .main-layout { min-height:100vh; background:#e9eee8; }
    body.portal-shell.chair-portal .main-layout { display:grid; grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); align-items:stretch; }
    body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:sticky; top:0; align-self:start; width:var(--chair-sidebar-width); height:100vh; min-height:100vh; padding:25px 15px 18px; border:0; background:linear-gradient(165deg,#112f29 0%,#173b33 54%,#285342 100%); color:#f7f5eb; box-shadow:12px 0 32px rgba(20,47,37,.1); }
    body.portal-shell.chair-portal .top-header { position:fixed; top:16px; right:var(--chair-shell-gutter); left:calc(var(--chair-sidebar-width) + var(--chair-shell-gutter)); z-index:1000; min-height:72px; padding:10px 15px 10px 22px; border:1px solid rgba(224,231,224,.96); border-radius:18px; background:rgba(255,255,255,.95); box-shadow:0 12px 34px rgba(31,57,43,.09); backdrop-filter:blur(18px); }
    body.portal-shell.chair-portal .sidebar-toggle { top:36px; left:calc(var(--chair-sidebar-width) - 15px); z-index:1200; }
    body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
    body.portal-shell.chair-portal.sidebar-collapsed .main-layout { grid-template-columns:76px minmax(0,1fr); }
    body.portal-shell.chair-portal.sidebar-collapsed .main-layout > .chair-sidebar { width:76px; padding-right:10px; padding-left:10px; }
    .chair-room-availability { min-height:100vh; padding:124px clamp(18px,3vw,40px) 40px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }
    .chair-room-availability .page-header { display:flex; align-items:center; min-height:155px; margin:0 0 18px; padding:clamp(24px,3vw,36px); border-radius:22px; background:linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); color:#fff; }
    .chair-room-availability .title-area { display:flex; align-items:center; gap:16px; }
    .chair-room-availability .title-icon { display:grid; width:50px; height:50px; place-items:center; border:1px solid rgba(255,255,255,.24); border-radius:15px; background:rgba(255,255,255,.11); color:#e2c987; font-size:21px; }
    .chair-room-availability .title { margin:0 0 7px; color:#fff !important; font:800 clamp(30px,3vw,42px)/1.15 var(--wr-display,Arial,sans-serif); }
    .chair-room-availability .subtitle { color:#e1e8df; font-size:14px; font-weight:600; line-height:1.5; }
    .chair-room-availability .editor-mode-switch { display:flex; align-items:center; flex-wrap:wrap; gap:8px; margin:0 0 16px; padding:6px; border:1px solid #dfe7dd; border-radius:12px; background:#fff; }
    .chair-room-availability .editor-mode-link { display:inline-flex; min-height:40px; align-items:center; gap:8px; padding:0 14px; border-radius:8px; color:#53675a; font-size:13px; font-weight:800; text-decoration:none; }
    .chair-room-availability .editor-mode-link.active { background:#24543e; color:#fff; }
    .chair-room-availability .editor-mode-search { display:flex; flex:0 1 360px; align-items:center; gap:10px; margin-left:auto; }
    .chair-room-availability .editor-mode-search .room-overview-search { width:100%; min-width:0; min-height:40px; }
    .chair-room-availability .editor-mode-search .room-overview-count { flex:0 0 auto; white-space:nowrap; }
    @media(max-width:760px) {
        body.portal-shell.chair-portal { --chair-shell-gutter:10px; }
        body.portal-shell.chair-portal .main-layout { display:block; }
        body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:fixed; top:0; bottom:0; left:0; z-index:1100; width:262px; height:100dvh; min-height:0; transform:translateX(0); }
        body.portal-shell.chair-portal.sidebar-collapsed .main-layout > .chair-sidebar { transform:translateX(-110%); }
        body.portal-shell.chair-portal .top-header { left:10px; right:10px; }
        body.portal-shell.chair-portal .main-layout > .chair-room-availability { padding:110px 12px 24px; }
        .chair-room-availability .page-header { min-height:130px; padding:20px; }
        .chair-room-availability .title-area { align-items:flex-start; gap:12px; }
        .chair-room-availability .editor-mode-search { flex-basis:100%; margin:4px 0 0; }
    }
</style>

<div class="wrapper">
    <div class="main-layout">
        @include('chair.sidebar')
        <main class="content chair-room-availability">
            <section class="page-header">
                <div class="title-area">
                    <div class="title-icon"><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i></div>
                    <div>
                        <h1 class="title">All-Room Availability</h1>
                        <div class="subtitle">Active term: {{ $activeTerm->academic_year ?? '-' }} · {{ $activeTerm->semester ?? '-' }}. View room schedules across colleges, then select a time to open it in the matching schedule editor.</div>
                    </div>
                </div>
            </section>

            <nav class="editor-mode-switch" aria-label="Set schedule pages">
                <a class="editor-mode-link" href="{{ route('chair.setschedule') }}"><i class="fa-solid fa-building" aria-hidden="true"></i> Schedule editor</a>
                <a class="editor-mode-link" href="{{ route('chair.modifyschedule', ['term_id' => $activeTerm->id]) }}"><i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i> Cross-scheduling</a>
                <a class="editor-mode-link active" aria-current="page" href="{{ route('chair.roomavailability', ['term_id' => $activeTerm->id]) }}"><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i> All-room availability</a>
                <div class="editor-mode-search">
                    <input type="search" class="room-overview-search" id="roomOverviewSearch" aria-label="Search rooms" placeholder="Search room, building, or college" autocomplete="off">
                    <span class="room-overview-count" id="roomOverviewCount" role="status" aria-live="polite"></span>
                </div>
            </nav>

            @include('chair.partials.room-availability-overview', ['overviewCrossMode' => false, 'overviewStandalone' => true])
        </main>
    </div>
</div>
