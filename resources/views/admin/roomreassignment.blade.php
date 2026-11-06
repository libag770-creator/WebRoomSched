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

    .portal-shell.admin-portal .main-layout > .content.admin-room-reassignment-view { min-width:0; min-height:100vh; padding:124px clamp(20px,3vw,42px) 46px; background:radial-gradient(ellipse at 92% 2%,rgba(214,194,143,.22),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }
    .admin-room-reassignment-view,.admin-room-reassignment-view * { box-sizing:border-box; }
    .admin-room-reassignment-view { color:#293d31; font-family:'DM Sans',Arial,sans-serif; font-size:15px; line-height:1.55; }
    .so-page { width:min(100%,1480px); margin:0 auto; }
    .so-hero { position:relative; display:flex; align-items:center; justify-content:space-between; gap:26px; min-height:220px; overflow:hidden; margin:0 0 23px; padding:clamp(28px,4vw,42px); border:1px solid rgba(231,214,173,.25); border-radius:24px; background:radial-gradient(circle at 83% 20%,rgba(215,194,143,.18),transparent 24%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.15); color:#fff; }
    .so-hero > div:first-child { position:relative; z-index:1; max-width:800px; }
    .so-hero::after { position:absolute; right:-45px; bottom:-175px; width:340px; height:340px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .so-eyebrow { display:flex; align-items:center; gap:9px; margin:0 0 13px; color:#e2c987; font-size:12px; font-weight:850; letter-spacing:.14em; text-transform:uppercase; }
    .so-hero h1 { margin:0; color:#fffdf7; font-family:'Manrope','DM Sans',sans-serif; font-size:clamp(32px,3.5vw,46px); font-weight:850; letter-spacing:-.04em; line-height:1.12; }
    .so-hero p:not(.so-eyebrow) { max-width:730px; margin:13px 0 0; color:#d8e2d8; font-size:16px; font-weight:550; line-height:1.7; }
    .so-hero-icon { position:relative; z-index:1; display:grid; flex:0 0 138px; width:138px; height:138px; place-items:center; border:1px solid rgba(255,255,255,.21); border-radius:50%; background:rgba(255,255,255,.075); color:#e6d39e; font-size:43px; }
    .so-alert { display:flex; align-items:flex-start; gap:10px; margin:0 0 17px; padding:14px 16px; border:1px solid #dbe6dc; border-radius:12px; background:#f2f7f1; color:#31583c; font-size:14px; font-weight:750; }
    .so-alert > i { margin-top:3px; font-size:17px; }
    .so-alert.error { display:block; border-color:#f0ceca; background:#fff5f3; color:#853d37; }
    .so-alert.error ul { margin:7px 0 0; padding-left:22px; font-size:14px; font-weight:650; }
    .so-panel { overflow:hidden; margin:0 0 20px; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .so-panel-head { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; padding:21px 23px; border-bottom:1px solid #e8eee6; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .so-panel-head h2 { margin:0; color:#173b33; font-family:'Manrope','DM Sans',sans-serif; font-size:22px; font-weight:850; letter-spacing:-.025em; line-height:1.3; }
    .so-panel-intro { max-width:780px; margin:6px 0 0; color:#5c6d62; font-size:14px; font-weight:600; line-height:1.65; }
    .so-pill { display:inline-flex; min-height:31px; align-items:center; padding:0 11px; border:1px solid #eadfbf; border-radius:999px; background:#f8f4e8; color:#79683d; font-size:12px; font-weight:850; white-space:nowrap; }
    .so-steps { display:flex; align-items:center; gap:10px; overflow-x:auto; margin:0; padding:18px 23px; border-bottom:1px solid #edf1eb; list-style:none; }
    .so-step { display:inline-flex; align-items:center; gap:8px; color:#718077; font-size:13px; font-weight:800; white-space:nowrap; }
    .so-step > span { display:grid; width:29px; height:29px; place-items:center; border:1px solid #dfe7dc; border-radius:50%; background:#f5f7f4; color:#65756a; font-size:12px; font-weight:850; }
    .so-step.active { color:#285940; font-weight:900; }
    .so-step.active > span { border-color:#285940; background:#285940; color:#fff; box-shadow:0 4px 10px rgba(40,89,64,.17); }
    #soDepartmentLevel,#soBuildingLevel,#soRoomLevel,#soSchedulePanel { padding:21px 23px 23px; }
    .so-level-head { display:flex; align-items:center; justify-content:space-between; gap:14px; margin-bottom:14px; }
    .so-level-head h3 { margin:0; color:#244532; font-family:'Manrope','DM Sans',sans-serif; font-size:18px; font-weight:850; letter-spacing:-.02em; }
    .so-level-head p { margin:4px 0 0; color:#65756a; font-size:14px; font-weight:600; }
    .so-college-search-row { display:flex; justify-content:flex-end; margin:-2px 0 13px; }
    .so-college-search { position:relative; display:block; width:min(100%,380px); }
    .so-college-search > i { position:absolute; top:50%; left:14px; transform:translateY(-50%); color:#75877a; pointer-events:none; }
    .so-college-search input { width:100%; min-height:44px; padding:10px 13px 10px 40px; border:1px solid #d8e2d6; border-radius:11px; background:#fff; color:#294437; font:650 14px 'DM Sans',Arial,sans-serif; outline:none; box-shadow:0 4px 12px rgba(31,57,43,.035); }
    .so-college-search input:focus { border-color:#72947a; box-shadow:0 0 0 3px rgba(72,129,83,.12); }
    .so-college-search-empty { margin:0 0 12px; color:#68796e; font-size:13px; font-weight:700; text-align:right; }
    .so-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:11px; }
    .so-card { position:relative; display:grid; min-height:105px; grid-template-columns:44px minmax(0,1fr) 16px; grid-template-rows:auto auto; align-content:center; align-items:center; column-gap:12px; row-gap:4px; padding:15px 16px; border:1px solid #dfe7dd; border-radius:14px; background:#fff; color:#2d4537; text-align:left; cursor:pointer; box-shadow:0 5px 15px rgba(31,57,43,.035); transition:transform .16s ease,border-color .16s ease,background .16s ease,box-shadow .16s ease; }
    .so-card:hover { transform:translateY(-2px); border-color:#afc5b0; background:#fbfcfa; box-shadow:0 10px 21px rgba(31,57,43,.075); }
    .so-card:focus-visible,.so-back:focus-visible,.so-button:focus-visible,.so-cancel:focus-visible { outline:3px solid rgba(72,129,83,.28); outline-offset:2px; }
    .so-card.selected { border-color:#4c7c58; background:linear-gradient(115deg,#edf4eb,#fff); box-shadow:inset 4px 0 #34704a,0 8px 20px rgba(31,57,43,.06); }
    .so-card-icon { display:grid; grid-row:1 / span 2; width:44px; height:44px; place-items:center; border:1px solid #e2e9df; border-radius:13px; background:#edf3eb; color:#285940; font-size:17px; }
    .so-card-title { min-width:0; color:#294437; font-size:15px; font-weight:850; line-height:1.4; overflow-wrap:anywhere; }
    .so-card-sub { min-width:0; color:#68796e; font-size:12px; font-weight:700; line-height:1.45; overflow-wrap:anywhere; }
    .so-room-card { min-height:148px; grid-template-rows:auto auto auto; align-content:start; }
    .so-room-card .so-room-features { display:flex; grid-column:2 / -1; flex-wrap:wrap; align-self:start; gap:6px; min-width:0; margin-top:8px; }
    .so-room-feature { display:inline-flex; max-width:100%; align-items:center; gap:6px; padding:6px 8px; border:1px solid #e3e9df; border-radius:8px; background:#f6f8f4; color:#53675a; font-size:11px; font-weight:800; line-height:1.3; }
    .so-room-feature > i { flex:0 0 auto; color:#738578; }
    .so-room-feature > span { min-width:0; overflow-wrap:anywhere; }
    .so-room-feature.available { border-color:#d5e7d8; background:#edf6ed; color:#2f7048; }
    .so-room-feature.available > i { color:#2f7048; }
    .so-room-feature.unavailable { border-color:#e5e9e3; background:#f4f6f2; color:#78847b; }
    .so-room-feature.unavailable > i { color:#8b968d; }
    .so-room-description { grid-column:2 / -1; margin-top:2px; color:#637368; font-size:12px; font-weight:650; line-height:1.45; overflow-wrap:anywhere; }
    .so-arrow { grid-column:3; grid-row:1 / span 2; justify-self:end; color:#829287; font-size:24px; font-weight:800; }
    .so-empty { grid-column:1 / -1; padding:20px; border:1px dashed #d5dfd3; border-radius:12px; background:#f8faf7; color:#586b5e; font-size:14px; font-weight:750; text-align:center; }
    .so-back { display:inline-flex; min-height:40px; align-items:center; justify-content:center; padding:0 13px; border:1px solid #dce6db; border-radius:9px; background:#f4f7f2; color:#355540; font:800 13px 'DM Sans',Arial,sans-serif; cursor:pointer; white-space:nowrap; }
    .so-back:hover { background:#e9f1e7; }
    .so-room-banner { display:flex; align-items:center; gap:10px; margin:0 0 14px; padding:13px 15px; border:1px solid #dce7d9; border-radius:11px; background:#f3f7f1; color:#52675a; font-size:14px; font-weight:700; }
    .so-room-banner > i { color:#37664d; font-size:17px; }
    .so-room-banner strong { color:#294b36; font-size:15px; font-weight:850; }
    .so-spinner { width:19px; height:19px; border:2px solid #cddbcf; border-top-color:#2f7048; border-radius:50%; animation:so-spin .75s linear infinite; }
    .so-location-error { margin:0 23px 19px; padding:13px 15px; border:1px solid #f0ceca; border-radius:11px; background:#fff5f3; color:#853d37; font-size:14px; font-weight:750; }
    .so-schedule-list { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:11px; }
    .so-schedule-card { min-height:138px; }
    .so-schedule-meta { display:flex; grid-column:2 / -1; flex-wrap:wrap; gap:6px; margin-top:4px; }
    .so-schedule-meta .so-pill { min-height:25px; padding:0 8px; border-color:#e3e9df; background:#f4f7f2; color:#53685a; font-size:11px; }
    .so-existing { grid-column:2 / -1; margin-top:5px; color:#806d3e; font-size:12px; font-weight:800; line-height:1.45; }
    .so-form { margin-top:19px; padding:22px; border:1px solid #dfe7dd; border-radius:15px; background:#fbfcfa; }
    .so-form h3 { margin:0; color:#173b33; font-family:'Manrope','DM Sans',sans-serif; font-size:21px; font-weight:850; letter-spacing:-.025em; }
    .so-form-help { margin:6px 0 17px; color:#5f7065; font-size:14px; font-weight:600; line-height:1.6; }
    .so-selected-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:9px; margin-bottom:18px; }
    .so-summary-item { min-width:0; padding:11px 12px; border:1px solid #e3e9e1; border-radius:10px; background:#fff; }
    .so-summary-item small { display:block; margin-bottom:4px; color:#77847a; font-size:11px; font-weight:850; letter-spacing:.04em; text-transform:uppercase; }
    .so-summary-item strong { display:block; color:#30483a; font-size:13px; font-weight:850; line-height:1.45; overflow-wrap:anywhere; }
    .so-form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:15px; }
    .so-field { min-width:0; }
    .so-field.full { grid-column:1 / -1; }
    .so-field label { display:block; margin:0 0 7px; color:#35483c; font-size:14px; font-weight:850; }
    .so-input { display:block; width:100%; min-height:48px; padding:11px 13px; border:1px solid #d7e0d6; border-radius:10px; outline:none; background:#fff; color:#263c30; font:650 14px 'DM Sans',Arial,sans-serif; transition:border-color .16s ease,box-shadow .16s ease; }
    textarea.so-input { min-height:102px; resize:vertical; }
    .so-input:focus { border-color:#658e70; box-shadow:0 0 0 3px rgba(73,123,83,.13); }
    .so-input[aria-invalid="true"] { border-color:#bd534a; box-shadow:0 0 0 3px rgba(189,83,74,.1); }
    .so-field-hint { display:block; margin-top:7px; color:#6d7b71; font-size:12px; font-weight:650; line-height:1.5; }
    .so-date-message { display:block; margin-top:7px; color:#2f7048; font-size:12px; font-weight:850; line-height:1.5; }
    .so-date-message.error { color:#ad443c; }
    .so-conflicts,.so-existing-room-overrides { display:grid; gap:8px; margin-top:15px; padding:14px 16px; border:1px solid #e8d39f; border-radius:12px; background:#fff8e8; color:#624b1f; font-size:13px; font-weight:650; line-height:1.5; }
    .so-conflicts ul { display:grid; gap:5px; margin:0; padding-left:20px; }
    .so-conflicts p { margin:0; }
    .so-conflict-clear { border-color:#cfe2d0; background:#f1f8ef; color:#315b3b; }
    .so-conflict-error { color:#a33c35; }
    .so-conflict-ack { display:flex; align-items:flex-start; gap:9px; margin-top:12px; color:#594615; font-size:13px; font-weight:750; line-height:1.45; cursor:pointer; }
    .so-conflict-ack input { flex:0 0 18px; width:18px; height:18px; margin:1px 0 0; accent-color:#24583d; }
    .so-existing-room-overrides { border-color:#e2e8dd; background:#f7faf5; color:#445b49; }
    .so-existing-room-overrides strong { color:#264c35; }
    .so-room-features { display:flex; flex-wrap:wrap; gap:5px; margin-top:10px; }
    .so-room-features > span { padding:4px 7px; border:1px solid #e4ebe2; border-radius:7px; background:#f8faf7; color:#536a5b; font-size:10px; font-weight:700; }
    .so-actions { display:flex; justify-content:flex-end; flex-wrap:wrap; gap:9px; margin-top:19px; padding-top:16px; border-top:1px solid #e8eee6; }
    .so-button { display:inline-flex; min-height:45px; align-items:center; justify-content:center; gap:8px; padding:0 15px; border:1px solid transparent; border-radius:10px; cursor:pointer; font:850 13px 'DM Sans',Arial,sans-serif; text-decoration:none; transition:transform .16s ease,background .16s ease,box-shadow .16s ease; }
    .so-button:hover { transform:translateY(-1px); }
    .so-button.primary { background:#24583d; color:#fff; box-shadow:0 7px 15px rgba(36,88,61,.16); }
    .so-button.primary:hover { background:#19482f; }
    .so-button.secondary { border-color:#dfe7dc; background:#f1f5ef; color:#375442; }
    .so-button.secondary:hover { background:#e7efe4; }
    .so-button:disabled { opacity:.65; cursor:wait; transform:none; }
    .so-overrides { padding:0 23px 8px; }
    .so-override-row { display:grid; grid-template-columns:minmax(0,1.2fr) minmax(130px,.8fr) minmax(0,1fr) auto; align-items:center; gap:15px; padding:17px 0; border-bottom:1px solid #edf1eb; }
    .so-override-row:last-child { border-bottom:0; }
    .so-override-row > div { min-width:0; }
    .so-override-row small { display:block; margin-bottom:4px; color:#77847a; font-size:11px; font-weight:850; letter-spacing:.04em; text-transform:uppercase; }
    .so-override-row strong { display:block; color:#30483a; font-size:14px; font-weight:850; line-height:1.45; overflow-wrap:anywhere; }
    .so-override-row .so-card-sub { margin-top:3px; font-size:12px; font-weight:700; }
    .so-status { display:inline-flex; min-height:30px; align-items:center; padding:0 10px; border:1px solid #dfe7dc; border-radius:999px; background:#f4f6f2; color:#53665a; font-size:12px; font-weight:850; white-space:nowrap; }
    .so-status.scheduled { border-color:#d9e6d9; background:#edf5eb; color:#316341; }
    .so-cancel-confirm { position:relative; }
    .so-cancel { display:inline-flex; min-height:36px; align-items:center; padding:0 11px; border:1px solid #f0d9d5; border-radius:9px; background:#fff7f5; color:#a7443d; font-size:12px; font-weight:850; cursor:pointer; list-style:none; white-space:nowrap; }
    .so-cancel::-webkit-details-marker { display:none; }
    .so-confirm-inline { position:absolute; right:0; top:calc(100% + 7px); z-index:5; display:flex; min-width:230px; align-items:center; gap:9px; padding:10px; border:1px solid #e4e9e1; border-radius:11px; background:#fff; box-shadow:0 12px 26px rgba(31,57,43,.14); }
    .so-confirm-inline span { color:#526258; font-size:12px; font-weight:750; }
    .so-confirm-inline button { min-height:34px; padding:0 10px; border:0; border-radius:8px; background:#a9473f; color:#fff; font:850 11px 'DM Sans',Arial,sans-serif; cursor:pointer; }
    .admin-room-reassignment-view [hidden] { display:none !important; }
    @keyframes so-spin { to { transform:rotate(360deg); } }
    @media(max-width:1160px) {
        .portal-shell.admin-portal { --dashboard-sidebar-width:226px; --dashboard-shell-gutter:22px; }
        .portal-shell.admin-portal.sidebar-collapsed { --dashboard-sidebar-width:76px; }
        .portal-shell.admin-portal .main-layout { grid-template-columns:var(--dashboard-sidebar-width) minmax(0,1fr); }
        .portal-shell.admin-portal .main-layout > .content.admin-room-reassignment-view { padding-right:24px; padding-left:24px; }
        .so-override-row { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:760px) {
        .portal-shell.admin-portal .main-layout > .content.admin-room-reassignment-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .so-hero { align-items:flex-start; min-height:0; padding:28px 24px; }
        .so-hero-icon { flex-basis:108px; width:108px; height:108px; font-size:34px; }
        .so-selected-summary { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:560px) {
        .so-hero { flex-direction:column; gap:18px; }
        .so-hero-icon { display:none; }
        .so-hero p:not(.so-eyebrow) { font-size:14px; }
        .so-panel-head { flex-direction:column; padding:18px; }
        .so-steps { gap:13px; padding:15px 18px; }
        .so-step { font-size:11px; }
        .so-step > span { width:26px; height:26px; }
        #soDepartmentLevel,#soBuildingLevel,#soRoomLevel,#soSchedulePanel { padding:18px; }
        .so-level-head { align-items:flex-start; }
        .so-college-search-row { justify-content:stretch; }
        .so-college-search { width:100%; }
        .so-grid,.so-schedule-list { grid-template-columns:minmax(0,1fr); }
        .so-form { padding:18px; }
        .so-form-grid,.so-selected-summary { grid-template-columns:minmax(0,1fr); }
        .so-field.full { grid-column:auto; }
        .so-actions { flex-direction:column-reverse; }
        .so-button { width:100%; }
        .so-overrides { padding-right:18px; padding-left:18px; }
        .so-override-row { grid-template-columns:minmax(0,1fr); gap:10px; }
        .so-override-row > div:last-child { align-items:flex-start !important; justify-content:space-between !important; }
        .so-confirm-inline { right:auto; left:0; flex-wrap:wrap; }
    }
    @media(prefers-reduced-motion:reduce) {
        .admin-room-reassignment-view *, .so-spinner { scroll-behavior:auto !important; animation-duration:.01ms !important; transition-duration:.01ms !important; }
    }

</style>

<div class="wrapper">

    <div class="main-layout">
        @include('admin.sidebar')

        <main class="content admin-room-reassignment-view">
            @php
                $overrideStatusData = $overrides->map(function ($override) {
                    return [
                        'room_id' => (int) ($override->room_id ?: $override->schedule?->room_id),
                        'date' => $override->override_date?->format('Y-m-d'),
                        'start_time' => $override->start_time ? substr((string) $override->start_time, 0, 5) : null,
                        'end_time' => $override->end_time ? substr((string) $override->end_time, 0, 5) : null,
                        'fallback_time' => $override->schedule?->time,
                        'occupied_by' => $override->occupied_by,
                        'reason' => $override->reason,
                    ];
                })->values();
            @endphp


            <div class="so-page">
                <section class="so-hero" aria-labelledby="soPageTitle">
                    <div>
                        <p class="so-eyebrow"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Schedule management</p>
                        <h1 id="soPageTitle">Schedule Overrides</h1>
                        <p>Reserve a room for a specific date and time. The reservation appears across room schedule views and does not depend on the timetable’s editable time slots.</p>
                    </div>
                    <div class="so-hero-icon" aria-hidden="true"><i class="fa-solid fa-calendar-day"></i></div>
                </section>

                @if(session('success'))
                    <div class="so-alert success" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> {{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="so-alert error" role="alert">
                        <strong>Please review the following:</strong>
                        <ul style="margin:7px 0 0;padding-left:22px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <section class="so-panel" aria-labelledby="soChooseTitle">
                    <div class="so-panel-head">
                        <div>
                            <h2 id="soChooseTitle">Reserve a room</h2>
                            <p class="so-panel-intro">Choose a college, building, and room, then set the date, time, purpose, and occupant.</p>
                        </div>
                        @if($activeTerm)
                            <span class="so-pill"><i class="fa-solid fa-graduation-cap" aria-hidden="true" style="margin-right:7px"></i>{{ $activeTerm->academic_year }} · {{ $activeTerm->semester }}</span>
                        @endif
                    </div>

                    <ol class="so-steps" id="soSteps" aria-label="Selection steps">
                        <li class="so-step active"><span>1</span> College</li>
                        <li class="so-step"><span>2</span> Building</li>
                        <li class="so-step"><span>3</span> Room</li>
                        <li class="so-step"><span>4</span> Reservation</li>
                    </ol>

                    <section id="soDepartmentLevel">
                        <div class="so-level-head"><div><h3>1. Select a college</h3><p>Choose the college that owns the room.</p></div></div>
                        <div class="so-college-search-row">
                            <label class="so-college-search" for="soDepartmentSearch"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><input type="search" id="soDepartmentSearch" placeholder="Search colleges…" autocomplete="off" aria-label="Search colleges"></label>
                        </div>
                        <div class="so-grid" id="soDepartmentCards">
                            @forelse($departments as $department)
                                <button type="button" class="so-card" data-department-id="{{ $department->id }}" data-department-name="{{ $department->name }}">
                                    <span class="so-card-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                                    <span class="so-card-title">{{ $department->name }}</span>
                                    <span class="so-card-sub">Select to see its buildings</span><span class="so-arrow" aria-hidden="true">›</span>
                                </button>
                            @empty
                                <div class="so-empty">No colleges are available yet.</div>
                            @endforelse
                        </div>
                        <p class="so-college-search-empty" id="soDepartmentNoResults" hidden>No colleges match your search.</p>
                    </section>

                    <section id="soBuildingLevel" hidden>
                        <div class="so-level-head"><div><h3 id="soBuildingHeading">2. Select a building</h3><p>Choose the building that contains the room.</p></div><button type="button" class="so-back" id="soBackDepartments">← Colleges</button></div>
                        <div class="so-grid" id="soBuildingCards"></div>
                    </section>

                    <section id="soRoomLevel" hidden>
                        <div class="so-level-head"><div><h3 id="soRoomHeading">3. Select a room</h3><p>Choose the room to reserve.</p></div><button type="button" class="so-back" id="soBackBuildings">← Buildings</button></div>
                        <div class="so-grid" id="soRoomCards"></div>
                    </section>

                    <div id="soLoading" class="so-room-banner" hidden><span class="so-spinner" aria-hidden="true"></span><span>Loading locations…</span></div>
                    <div id="soLocationError" class="so-location-error" role="alert" hidden></div>

                    <section id="soSchedulePanel" hidden style="margin-top:20px">
                        <div class="so-level-head"><div><h3 id="soScheduleHeading">4. Set reservation details</h3><p>The room and time range are independent of the recurring timetable.</p></div></div>
                        <div class="so-room-banner"><i class="fa-solid fa-door-open" aria-hidden="true"></i><span>Room: <strong id="soSelectedRoomName"></strong> <span id="soSelectedRoomPath"></span></span></div>
                        <div id="soRoomExistingOverrides" class="so-existing-room-overrides" hidden></div>

                        <form class="so-form" id="soOverrideForm" method="POST" action="{{ route('admin.roomreassignment.store') }}" hidden>
                            @csrf
                            <input type="hidden" name="term_id" value="{{ $activeTerm?->id }}">
                            <input type="hidden" name="room_id" id="soRoomId">
                            <h3>Set the room reservation</h3>
                            <p class="so-form-help">The reservation remains visible for this room and date even if the chair changes the recurring timetable.</p>
                            <div class="so-selected-summary" aria-live="polite">
                                <div class="so-summary-item"><small>Room</small><strong id="soSummaryRoom">—</strong></div>
                                <div class="so-summary-item"><small>Date</small><strong id="soSummaryDate">—</strong></div>
                                <div class="so-summary-item"><small>Time</small><strong id="soSummaryTime">Choose a time</strong></div>
                                <div class="so-summary-item"><small>College · Building</small><strong id="soSummaryLocation">—</strong></div>
                            </div>
                            <div class="so-form-grid">
                                <div class="so-field">
                                    <label for="soOverrideDate">Date</label>
                                    <input class="so-input" type="date" name="override_date" id="soOverrideDate" min="{{ now()->toDateString() }}" required>
                                    <span class="so-field-hint">Choose the actual date. This reservation applies once.</span>
                                </div>
                                <div class="so-field">
                                    <label for="soStartTime">Start time</label>
                                    <input class="so-input" type="time" name="start_time" id="soStartTime" required>
                                </div>
                                <div class="so-field">
                                    <label for="soEndTime">End time</label>
                                    <input class="so-input" type="time" name="end_time" id="soEndTime" required>
                                </div>
                                <div class="so-field">
                                    <label for="soReason">Purpose</label>
                                    <input class="so-input" type="text" name="reason" id="soReason" maxlength="255" placeholder="For example: meeting" required>
                                </div>
                                <div class="so-field">
                                    <label for="soOccupiedBy">Who will occupy the room?</label>
                                    <input class="so-input" type="text" name="occupied_by" id="soOccupiedBy" maxlength="255" placeholder="Enter a person, office, or group" required>
                                </div>
                                <div class="so-field full">
                                    <label for="soDetails">Additional notes <span style="font-weight:500;color:#66778b">(optional)</span></label>
                                    <textarea class="so-input" name="details" id="soDetails" maxlength="2000" placeholder="Add helpful details for affected instructor"></textarea>
                                </div>
                            </div>
                            <div class="so-conflicts" id="soConflictPanel" hidden aria-live="polite"></div>
                            <label class="so-conflict-ack" id="soConflictAckWrap" hidden>
                                <input type="checkbox" name="acknowledge_conflicts" id="soConflictAck" value="1">
                                <span>I reviewed these conflicts and authorize this room reservation during the listed class or reservation times.</span>
                            </label>
                            <div class="so-actions">
                                <button type="button" class="so-button secondary" id="soClearSelection">Choose another room</button>
                                <button type="submit" class="so-button primary" id="soSubmit"><i class="fa-solid fa-bell" aria-hidden="true" style="margin-right:7px"></i>Reserve room and notify instructor</button>
                            </div>
                        </form>
                    </section>
                </section>

                <section class="so-panel" aria-labelledby="soActiveTitle">
                    <div class="so-panel-head">
                        <div><h2 id="soActiveTitle">Upcoming and current overrides</h2><p class="so-panel-intro">Overrides apply only to the listed day and return to the regular schedule automatically afterward.</p></div>
                    </div>
                    <div class="so-overrides">
                        @forelse($overrides as $override)
                            @php($overrideDate = $override->override_date)
                            <div class="so-override-row">
                                <div><small>Room</small><strong>{{ $override->room?->room_name ?: ($override->schedule?->room?->room_name ?: 'Room') }}</strong><div class="so-card-sub">{{ $override->room?->buildingRelation?->name ?: ($override->schedule?->room?->buildingRelation?->name ?: 'Building') }} · {{ $override->room?->department?->name ?: 'College' }}</div></div>
                                <div><small>Date and time</small><strong>{{ $overrideDate?->format('M j, Y') }}</strong><div class="so-card-sub">{{ $override->start_time ? date('g:i A', strtotime($override->start_time)) : ($override->schedule?->time ?: 'Time unavailable') }}{{ $override->end_time ? ' – ' . date('g:i A', strtotime($override->end_time)) : '' }}</div></div>
                                <div><small>Purpose / occupant</small><strong>{{ $override->reason }}</strong><div class="so-card-sub">Occupied by: {{ $override->occupied_by }}</div></div>
                                <div style="display:flex;align-items:center;gap:10px;justify-content:flex-end">
                                    <span class="so-status {{ $overrideDate?->isFuture() ? 'scheduled' : '' }}">{{ $overrideDate?->isToday() ? 'In effect today' : 'Scheduled' }}</span>
                                    <form method="POST" action="{{ route('admin.roomreassignment.cancel', $override) }}">
                                        @csrf @method('DELETE')
                                        <details class="so-cancel-confirm">
                                            <summary class="so-cancel" aria-label="Cancel override for {{ $overrideDate?->format('F j, Y') }}">Cancel</summary>
                                            <div class="so-confirm-inline"><span>End this override now?</span><button type="submit">Confirm cancellation</button></div>
                                        </details>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="so-empty">There are no current or upcoming schedule overrides.</div>
                        @endforelse
                    </div>
                </section>
            </div>

            
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const departmentsUrl = @json(route('admin.roomreassignment.buildings', ['department' => '__DEPARTMENT__']));
                const roomsUrl = @json(route('admin.roomreassignment.rooms', ['building' => '__BUILDING__']));
                const conflictUrl = @json(route('admin.roomreassignment.conflicts'));
                const activeTermId = @json($activeTerm?->id);
                const oldRoomId = @json(old('room_id'));
                const old = {date:@json(old('override_date')), start:@json(old('start_time')), end:@json(old('end_time')), reason:@json(old('reason')), occupant:@json(old('occupied_by')), details:@json(old('details'))};
                const activeOverrides = @json($overrideStatusData);
                const byId = id => document.getElementById(id);
                const departmentLevel = byId('soDepartmentLevel'), buildingLevel = byId('soBuildingLevel'), roomLevel = byId('soRoomLevel');
                const buildingCards = byId('soBuildingCards'), roomCards = byId('soRoomCards'), panel = byId('soSchedulePanel'), form = byId('soOverrideForm');
                const loading = byId('soLoading'), errorBox = byId('soLocationError'), conflictBox = byId('soConflictPanel'), ackWrap = byId('soConflictAckWrap'), ack = byId('soConflictAck');
                const dateInput = byId('soOverrideDate'), startInput = byId('soStartTime'), endInput = byId('soEndTime'), submit = byId('soSubmit');
                const departmentSearch = byId('soDepartmentSearch'), departmentNoResults = byId('soDepartmentNoResults');
                const departmentCards = Array.from(document.querySelectorAll('#soDepartmentCards [data-department-id]'));
                const steps = Array.from(document.querySelectorAll('#soSteps .so-step'));
                let room = null, conflicts = [], requestId = 0, locationRequestId = 0, departmentName = 'College', buildingName = 'Building';

                function setStep(step) { steps.forEach((item, index) => { item.classList.toggle('active', index + 1 === step); item.classList.toggle('complete', index + 1 < step); }); }
                function filterDepartments() {
                    const query = (departmentSearch?.value || '').trim().toLocaleLowerCase();
                    let visibleCount = 0;
                    departmentCards.forEach(card => {
                        const matches = !query || (card.dataset.departmentName || card.textContent).toLocaleLowerCase().includes(query);
                        card.hidden = !matches;
                        if (matches) visibleCount++;
                    });
                    if (departmentNoResults) departmentNoResults.hidden = !query || visibleCount > 0;
                }
                function showError(message) { errorBox.textContent = message; errorBox.hidden = false; }
                function clearError() { errorBox.textContent = ''; errorBox.hidden = true; }
                async function getJson(url) {
                    const response = await fetch(url, {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},credentials:'same-origin'});
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Could not load this list. Please try again.');
                    return data;
                }
                function makeCard(title, subtitle, icon, action, extraClass) {
                    const card = document.createElement('button'); card.type = 'button'; card.className = 'so-card' + (extraClass ? ' ' + extraClass : '');
                    const iconWrap = document.createElement('span'); iconWrap.className = 'so-card-icon';
                    const iconNode = document.createElement('i'); iconNode.className = icon; iconNode.setAttribute('aria-hidden','true'); iconWrap.appendChild(iconNode);
                    const titleNode = document.createElement('span'); titleNode.className = 'so-card-title'; titleNode.textContent = title;
                    const sub = document.createElement('span'); sub.className = 'so-card-sub'; sub.textContent = subtitle;
                    const arrow = document.createElement('span'); arrow.className = 'so-arrow'; arrow.textContent = '›';
                    card.append(iconWrap,titleNode,sub,arrow); card.addEventListener('click', action); return card;
                }
                function niceDate(value) {
                    if (!value) return 'Date unavailable'; const p = value.split('-').map(Number);
                    return new Date(p[0],p[1]-1,p[2]).toLocaleDateString(undefined,{year:'numeric',month:'short',day:'numeric'});
                }
                function niceTime(value) {
                    if (!value) return ''; const p = value.split(':').map(Number); const d = new Date(); d.setHours(p[0]||0,p[1]||0,0,0);
                    return d.toLocaleTimeString(undefined,{hour:'numeric',minute:'2-digit'});
                }
                async function openDepartment(id, name) {
                    const currentRequest = ++locationRequestId;
                    clearError(); departmentName = name; buildingCards.replaceChildren(); roomCards.replaceChildren(); panel.hidden = true;
                    departmentCards.forEach(card => card.classList.toggle('selected', card.dataset.departmentId === String(id)));
                    departmentLevel.hidden = true; buildingLevel.hidden = false; roomLevel.hidden = true;
                    byId('soBuildingHeading').textContent = '2. Select a building in ' + name; setStep(2); loading.hidden = false;
                    try {
                        const list = await getJson(departmentsUrl.replace('__DEPARTMENT__', encodeURIComponent(id)));
                        if (currentRequest !== locationRequestId) return;
                        if (!list.length) buildingCards.innerHTML = '<div class="so-empty">This college has no buildings yet.</div>';
                        list.forEach(item => buildingCards.appendChild(makeCard(item.name, Number(item.rooms_count) + ' rooms · Select to continue', 'fa-solid fa-building', () => openBuilding(item.id, item.name))));
                    } catch (error) { if (currentRequest === locationRequestId) showError(error.message); } finally { if (currentRequest === locationRequestId) loading.hidden = true; }
                }
                async function openBuilding(id, name) {
                    const currentRequest = ++locationRequestId;
                    clearError(); buildingName = name; roomCards.replaceChildren(); panel.hidden = true;
                    buildingLevel.hidden = true; roomLevel.hidden = false; byId('soRoomHeading').textContent = '3. Select a room in ' + name; setStep(3); loading.hidden = false;
                    try {
                        const list = await getJson(roomsUrl.replace('__BUILDING__', encodeURIComponent(id)));
                        if (currentRequest !== locationRequestId) return;
                        if (!list.length) roomCards.innerHTML = '<div class="so-empty">This building has no rooms yet.</div>';
                        list.forEach(item => {
                            const card = makeCard(item.name || item.room_name || 'Room', (item.building_name || name) + ' · Select to set a reservation', 'fa-solid fa-door-open', () => openRoom(item), 'so-room-card');
                            card.dataset.roomId = item.id;
                            const features = document.createElement('span'); features.className = 'so-room-features';
                            ['Capacity · ' + (item.capacity ? item.capacity + ' seats' : 'Not listed'), 'Computers · ' + (item.computers || 'Not listed'), 'TV · ' + (item.has_tv === null || item.has_tv === undefined ? 'Not listed' : (item.has_tv ? 'Available' : 'No')), 'Projector · ' + (item.has_projector === null || item.has_projector === undefined ? 'Not listed' : (item.has_projector ? 'Available' : 'No'))].forEach(value => { const feature = document.createElement('span'); feature.textContent = value; features.appendChild(feature); });
                            card.appendChild(features); roomCards.appendChild(card);
                        });
                    } catch (error) { if (currentRequest === locationRequestId) showError(error.message); } finally { if (currentRequest === locationRequestId) loading.hidden = true; }
                }
                function showRoomOverrides(item) {
                    const box = byId('soRoomExistingOverrides'); const matching = activeOverrides.filter(entry => Number(entry.room_id) === Number(item.id));
                    box.replaceChildren(); box.hidden = !matching.length; if (!matching.length) return;
                    const title = document.createElement('strong'); title.textContent = 'Already reserved for this room'; box.appendChild(title);
                    matching.forEach(entry => { const line = document.createElement('span'); const range = entry.start_time && entry.end_time ? niceTime(entry.start_time) + '–' + niceTime(entry.end_time) : (entry.fallback_time || 'Time unavailable'); line.textContent = niceDate(entry.date) + ' · ' + range + ' · ' + (entry.reason || 'Reservation') + ' · ' + (entry.occupied_by || 'Occupant'); box.appendChild(line); });
                }
                function updateSummary() {
                    byId('soSummaryRoom').textContent = room ? room.name : '—';
                    byId('soSummaryLocation').textContent = room ? departmentName + ' · ' + buildingName : '—';
                    byId('soSummaryDate').textContent = dateInput.value ? niceDate(dateInput.value) : 'Choose a date';
                    byId('soSummaryTime').textContent = startInput.value && endInput.value ? niceTime(startInput.value) + '–' + niceTime(endInput.value) : 'Choose a time';
                }
                function displayConflicts(list) {
                    conflicts = Array.isArray(list) ? list : []; conflictBox.replaceChildren(); ack.checked = false;
                    const blocked = conflicts.some(item => item.type === 'existing override');
                    const needsAck = conflicts.some(item => item.type !== 'existing override'); ackWrap.hidden = !needsAck;
                    if (!conflicts.length) {
                        const message = document.createElement('div'); message.className = 'so-conflict-clear'; message.textContent = 'No schedule or room booking overlaps this date and time.';
                        conflictBox.appendChild(message); conflictBox.hidden = false; submit.disabled = false; return;
                    }
                    const title = document.createElement('strong'); title.textContent = 'This time overlaps existing room use'; conflictBox.appendChild(title);
                    const listNode = document.createElement('ul');
                    conflicts.forEach(item => { const line = document.createElement('li'); line.textContent = [item.label,item.time,item.faculty ? 'With ' + item.faculty : ''].filter(Boolean).join(' · '); listNode.appendChild(line); });
                    conflictBox.appendChild(listNode);
                    if (needsAck) { const note = document.createElement('p'); note.textContent = 'Confirm below to reserve the room and notify affected instructor.'; conflictBox.appendChild(note); }
                    conflictBox.hidden = false; submit.disabled = blocked || (needsAck && !ack.checked);
                }
                async function refreshConflicts() {
                    updateSummary(); ack.checked = false; conflicts = []; conflictBox.hidden = true; ackWrap.hidden = true;
                    if (!room || !dateInput.value || !startInput.value || !endInput.value) { submit.disabled = false; return; }
                    if (endInput.value <= startInput.value) {
                        const message = document.createElement('div'); message.className = 'so-conflict-error'; message.textContent = 'End time must be later than start time.';
                        conflictBox.replaceChildren(message); conflictBox.hidden = false; submit.disabled = true; return;
                    }
                    const current = ++requestId; submit.disabled = true;
                    const query = new URLSearchParams({room_id:room.id,override_date:dateInput.value,start_time:startInput.value,end_time:endInput.value});
                    if (activeTermId) query.set('term_id', activeTermId);
                    try { const data = await getJson(conflictUrl + '?' + query.toString()); if (current === requestId) displayConflicts(data.conflicts || []); }
                    catch (error) { if (current !== requestId) return; const message = document.createElement('div'); message.className = 'so-conflict-error'; message.textContent = error.message; conflictBox.replaceChildren(message); conflictBox.hidden = false; submit.disabled = true; }
                }
                function openRoom(item) {
                    clearError(); room = Object.assign({}, item, {name:item.name || item.room_name || 'Room'});
                    roomCards.querySelectorAll('.so-card').forEach(card => card.classList.toggle('selected', card.dataset.roomId === String(item.id)));
                    byId('soSelectedRoomName').textContent = room.name; byId('soSelectedRoomPath').textContent = '· ' + (item.building_name || buildingName); byId('soRoomId').value = item.id;
                    panel.hidden = false; form.hidden = false; setStep(4); showRoomOverrides(item);
                    const restore = String(oldRoomId || '') === String(item.id);
                    dateInput.value = restore ? (old.date || '') : ''; startInput.value = restore ? (old.start || '') : ''; endInput.value = restore ? (old.end || '') : '';
                    byId('soReason').value = restore ? (old.reason || '') : ''; byId('soOccupiedBy').value = restore ? (old.occupant || '') : ''; byId('soDetails').value = restore ? (old.details || '') : '';
                    refreshConflicts(); panel.scrollIntoView({behavior:'smooth',block:'nearest'});
                }
                departmentCards.forEach(card => card.addEventListener('click', () => openDepartment(card.dataset.departmentId, card.dataset.departmentName)));
                departmentSearch?.addEventListener('input', filterDepartments);
                byId('soBackDepartments').addEventListener('click', () => { locationRequestId++; loading.hidden = true; clearError(); buildingLevel.hidden = true; roomLevel.hidden = true; panel.hidden = true; departmentLevel.hidden = false; setStep(1); filterDepartments(); });
                byId('soBackBuildings').addEventListener('click', () => { locationRequestId++; loading.hidden = true; clearError(); roomLevel.hidden = true; panel.hidden = true; buildingLevel.hidden = false; setStep(2); });
                byId('soClearSelection').addEventListener('click', () => { room = null; panel.hidden = true; form.hidden = true; roomCards.querySelectorAll('.so-card').forEach(card => card.classList.remove('selected')); setStep(3); });
                [dateInput,startInput,endInput].forEach(input => { input.addEventListener('change', refreshConflicts); input.addEventListener('input', refreshConflicts); });
                ack.addEventListener('change', () => { const blocked = conflicts.some(item => item.type === 'existing override'); const needsAck = conflicts.some(item => item.type !== 'existing override'); submit.disabled = blocked || (needsAck && !ack.checked); });
                form.addEventListener('submit', event => {
                    if (!room || !dateInput.value || !startInput.value || !endInput.value || endInput.value <= startInput.value) { event.preventDefault(); refreshConflicts(); return; }
                    if (conflicts.some(item => item.type === 'existing override') || (conflicts.some(item => item.type !== 'existing override') && !ack.checked)) { event.preventDefault(); ackWrap.scrollIntoView({behavior:'smooth',block:'center'}); return; }
                    submit.disabled = true;
                });
            });
            </script>
        </main>
    </div>
</div>

