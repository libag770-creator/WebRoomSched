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

    .portal-shell.admin-portal .main-layout > .content.admin-manage-users-view { min-width:0; min-height:100vh; padding:124px clamp(20px,3vw,42px) 46px; background:radial-gradient(ellipse at 92% 2%,rgba(214,194,143,.22),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }
    .admin-manage-users-view,.admin-manage-users-view * { box-sizing:border-box; }
    .admin-manage-users-view { color:#293d31; font:15px/1.55 'DM Sans',Arial,sans-serif; }
    .mu-page { width:min(100%,1480px); margin:0 auto; }
    .mu-heading { position:relative; display:flex; align-items:center; justify-content:space-between; gap:26px; min-height:200px; overflow:hidden; margin:0 0 22px; padding:clamp(27px,4vw,40px); border:1px solid rgba(231,214,173,.25); border-radius:24px; background:radial-gradient(circle at 83% 20%,rgba(215,194,143,.18),transparent 24%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.15); color:#fff; }
    .mu-heading::after { position:absolute; right:-45px; bottom:-175px; width:340px; height:340px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; }
    .mu-heading > div:first-child { position:relative; z-index:1; max-width:720px; }
    .mu-title { margin:0; color:#fff !important; font:850 clamp(32px,3.5vw,44px)/1.12 'Manrope','DM Sans',sans-serif; letter-spacing:-.04em; }
    .mu-subtitle { max-width:620px; margin:12px 0 0; color:#d8e2d8; font-size:15px; font-weight:650; line-height:1.65; }
    .mu-heading-actions { position:relative; z-index:1; display:flex; flex-wrap:wrap; justify-content:flex-end; gap:9px; }
    .mu-btn { display:inline-flex; min-height:39px; align-items:center; justify-content:center; gap:7px; padding:0 12px; border:1px solid transparent; border-radius:9px; cursor:pointer; font:850 12px 'DM Sans',Arial,sans-serif; text-decoration:none; white-space:nowrap; transition:transform .16s ease,background .16s ease; }
    .mu-btn:hover { transform:translateY(-1px); }
    .mu-heading-actions .mu-btn { min-height:44px; padding:0 15px; font-size:13px; }
    .mu-primary { border-color:#e8d7ae; background:#e8d7ae; color:#203d34; }
    .mu-primary:hover { background:#f1e3c3; }
    .mu-heading-actions .mu-secondary { border-color:rgba(255,255,255,.25); background:rgba(255,255,255,.1); color:#fff; }
    .mu-heading-actions .mu-secondary:hover { background:rgba(255,255,255,.17); }
    .mu-secondary { border-color:#dfe7dc; background:#f1f5ef; color:#375442; }
    .mu-neutral { border-color:#d7e4e8; background:#edf5f7; color:#315968; }
    .mu-success { border-color:#d5e7d8; background:#edf6ed; color:#2f7048; }
    .mu-warning { border-color:#ebdfc8; background:#f8f5ec; color:#79683d; }
    .mu-danger { border-color:#f0d9d5; background:#fff7f5; color:#a7443d; }
    .mu-alert { margin:0 0 15px; padding:14px 16px; border:1px solid #dbe6dc; border-radius:12px; background:#f2f7f1; color:#31583c; font-size:14px; font-weight:750; }
    .mu-alert-success { border-color:#d5e7d8; background:#eff7ef; color:#2e6942; }
    .mu-alert-error { border-color:#f0ceca; background:#fff5f3; color:#853d37; }
    .mu-alert ul { font-size:13px; font-weight:700; }
    .mu-panel { overflow:hidden; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .mu-panel-header { display:flex; align-items:center; justify-content:space-between; gap:18px; padding:20px 22px; border-bottom:1px solid #e8eee6; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .mu-panel-header h2 { margin:0; color:#173b33; font:850 21px 'Manrope','DM Sans',sans-serif; letter-spacing:-.025em; }
    .mu-count { display:inline-flex; min-height:28px; align-items:center; margin-top:7px; padding:0 10px; border:1px solid #dbe6d9; border-radius:999px; background:#edf4eb; color:#2d5b3b; font-size:12px; font-weight:850; }
    .mu-search { width:min(360px,44%); min-height:45px; padding:10px 13px; border:1px solid #d7e0d6; border-radius:10px; outline:none; background:#fff; color:#263c30; font:650 13px 'DM Sans',Arial,sans-serif; }
    .mu-search:focus,.mu-input:focus,.mu-select:focus { border-color:#658e70; box-shadow:0 0 0 3px rgba(73,123,83,.13); }
    .mu-search::placeholder { color:#89968c; }
    .mu-table-wrap { width:100%; overflow:auto; }
    .mu-table { width:100%; min-width:1000px; border-collapse:separate; border-spacing:0; text-align:left; }
    .mu-table th { padding:13px 14px; border-bottom:1px solid #e4ebe2; background:#fafbf8; color:#718077; font-size:11px; font-weight:850; letter-spacing:.08em; text-transform:uppercase; white-space:nowrap; }
    .mu-table td { padding:14px; border-bottom:1px solid #edf1eb; color:#3e5246; font-size:13px; font-weight:650; vertical-align:middle; }
    .mu-table tbody tr:last-child td { border-bottom:0; }
    .mu-table tbody tr.mu-user-row:hover { background:#fbfcfa; }
    .mu-user-name { display:block; color:#294437; font-size:15px; font-weight:850; line-height:1.4; }
    .mu-muted { display:block; margin-top:3px; color:#77847a; font-size:12px; font-weight:650; line-height:1.4; overflow-wrap:anywhere; }
    .mu-role,.mu-status { display:inline-flex; min-height:29px; align-items:center; padding:0 9px; border:1px solid #e3e9df; border-radius:999px; background:#f4f7f2; color:#496052; font-size:11px; font-weight:850; white-space:nowrap; }
    .mu-role { border-color:#e5dfc9; background:#f8f6ec; color:#79683d; }
    .mu-status-active { border-color:#d5e7d8; background:#edf6ed; color:#2f7048; }
    .mu-status-inactive { border-color:#eadfce; background:#f8f3ea; color:#796444; }
    .mu-select-col { width:48px; }
    .mu-select-all,.mu-select-user { width:17px; height:17px; accent-color:#285940; cursor:pointer; }
    .mu-actions { display:flex; align-items:center; justify-content:flex-end; }
    .mu-menu-trigger { display:grid; width:38px; height:38px; place-items:center; border:1px solid #dfe7dc; border-radius:10px; background:#fff; color:#375442; font-size:16px; cursor:pointer; transition:background .16s ease,border-color .16s ease,transform .16s ease; }
    .mu-menu-trigger:hover,.mu-menu-trigger[aria-expanded="true"] { transform:translateY(-1px); border-color:#b8cbb8; background:#f1f6ef; }
    .mu-menu-trigger:focus-visible,.mu-menu-item:focus-visible { outline:3px solid rgba(73,123,83,.24); outline-offset:2px; }
    .mu-actions-menu { position:fixed; z-index:3200; display:none; width:min(228px,calc(100vw - 24px)); max-height:min(320px,calc(100vh - 24px)); overflow:auto; padding:6px; border:1px solid #dfe7dc; border-radius:12px; background:#fff; box-shadow:0 16px 40px rgba(24,54,37,.18); }
    .mu-actions-menu.is-open { display:grid; gap:3px; }
    .mu-menu-item { display:flex; width:100%; min-height:40px; align-items:center; gap:10px; padding:0 10px; border:0; border-radius:8px; background:transparent; color:#344b3b; font:750 13px 'DM Sans',Arial,sans-serif; text-align:left; text-decoration:none; cursor:pointer; }
    .mu-menu-item:hover { background:#f1f6ef; color:#1f5134; }
    .mu-menu-item > i { width:17px; color:#718278; text-align:center; }
    .mu-menu-item.danger { color:#a7443d; }
    .mu-menu-item.danger > i { color:#b65b50; }
    .mu-menu-form { margin:0; }
    .mu-menu-separator { height:1px; margin:4px 2px; background:#edf1eb; }
    .mu-selection-toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 20px; border-bottom:1px solid #e8eee6; background:#f7faf5; color:#344b3b; font-size:13px; font-weight:750; }
    .mu-selection-toolbar[hidden] { display:none !important; }
    .mu-selection-toolbar strong { color:#1f5134; font-size:14px; }
    .mu-selection-actions { display:flex; align-items:center; justify-content:flex-end; flex-wrap:wrap; gap:7px; }
    .mu-selection-actions .mu-btn { min-height:35px; padding:0 10px; font-size:11px; }
    .mu-selection-actions .mu-btn:disabled { cursor:wait; opacity:.58; transform:none; }
    .mu-empty { padding:34px 18px !important; color:#65756a !important; font-size:14px !important; font-weight:800 !important; text-align:center; }
    .mu-modal { position:fixed; inset:0; z-index:3000; display:none; align-items:center; justify-content:center; overflow:auto; padding:20px; background:rgba(15,34,26,.56); backdrop-filter:blur(5px); }
    .mu-modal.mu-open { display:flex; }
    .mu-modal-card { width:min(760px,100%); max-height:calc(100vh - 40px); overflow:auto; border:1px solid rgba(255,255,255,.7); border-radius:20px; background:#fff; box-shadow:0 24px 70px rgba(9,35,22,.26); color:#293d31; }
    .mu-review-modal { z-index:3300; }
    .mu-review-card { width:min(520px,100%); }
    .mu-review-list { display:grid; gap:0; margin:18px 0 0; border:1px solid #e3ebe1; border-radius:12px; overflow:hidden; }
    .mu-review-list > div { display:grid; grid-template-columns:125px minmax(0,1fr); gap:12px; padding:11px 13px; border-bottom:1px solid #e9eee7; }
    .mu-review-list > div:last-child { border-bottom:0; }
    .mu-review-list dt { color:#68786d; font-size:13px; font-weight:800; }
    .mu-review-list dd { min-width:0; margin:0; overflow-wrap:anywhere; color:#263c30; font-size:14px; font-weight:750; }
    .mu-status-review-list { display:grid; gap:7px; max-height:240px; overflow:auto; margin:14px 0 0; padding:0; list-style:none; }
    .mu-status-review-list li { padding:10px 12px; border:1px solid #e3ebe1; border-radius:9px; color:#263c30; font-size:13px; font-weight:750; }
    .mu-status-review-list span { display:block; margin-top:2px; color:#68786d; font-size:12px; font-weight:600; }
    .mu-modal-wide { width:min(1020px,100%); }
    .mu-modal-head { position:sticky; top:0; z-index:2; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px 22px; border-bottom:1px solid #e8eee6; background:rgba(250,251,248,.97); }
    .mu-modal-head h2 { margin:0; color:#183c2e; font:850 22px 'Manrope','DM Sans',sans-serif; letter-spacing:-.03em; }
    .mu-close { display:grid; flex:0 0 36px; width:36px; height:36px; place-items:center; border:0; border-radius:50%; background:#edf2ec; color:#52665a; font-size:24px; cursor:pointer; }
    .mu-modal-body { padding:21px 22px; }
    .mu-form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
    .mu-field { min-width:0; }
    .mu-field-full { grid-column:1 / -1; }
    .mu-field label { display:block; margin:0 0 7px; color:#35483c; font-size:14px; font-weight:850; }
    .mu-input,.mu-select { display:block; width:100%; min-height:46px; padding:10px 12px; border:1px solid #d7e0d6; border-radius:10px; outline:none; background:#fff; color:#263c30; font:650 14px 'DM Sans',Arial,sans-serif; }
    .mu-help { display:block; margin-top:6px; color:#68786d; font-size:12px; font-weight:700; line-height:1.5; }
    .mu-password-wrap { display:flex; align-items:center; gap:7px; }
    .mu-password-wrap .mu-input { min-width:0; flex:1 1 auto; }
    .mu-password-wrap .mu-btn { min-height:39px; }
    .mu-modal-body > p { color:#5f7065; font-size:14px !important; font-weight:650; line-height:1.6; }
    .mu-modal-body > p strong { color:#2a4a36; font-size:15px; font-weight:850; }
    .mu-modal-footer { display:flex; justify-content:flex-end; flex-wrap:wrap; gap:9px; padding:16px 22px 20px; border-top:1px solid #edf1eb; }
    .mu-modal-footer .mu-btn { min-height:42px; padding:0 15px; font-size:13px; }
    .admin-manage-users-view [aria-hidden="true"].mu-modal { display:none; }
    @media(max-width:1120px) {
        .portal-shell.admin-portal { --dashboard-sidebar-width:226px; --dashboard-shell-gutter:22px; }
        .portal-shell.admin-portal.sidebar-collapsed { --dashboard-sidebar-width:76px; }
        .portal-shell.admin-portal .main-layout { grid-template-columns:var(--dashboard-sidebar-width) minmax(0,1fr); }
        .portal-shell.admin-portal .main-layout > .content.admin-manage-users-view { padding-right:24px; padding-left:24px; }
    }
    @media(max-width:760px) {
        .portal-shell.admin-portal .main-layout > .content.admin-manage-users-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .mu-heading { align-items:flex-start; flex-direction:column; justify-content:center; min-height:0; padding:27px 23px; }
        .mu-heading-actions { justify-content:flex-start; }
        .mu-panel-header { align-items:flex-start; flex-direction:column; }
        .mu-search { width:100%; }
        .mu-selection-toolbar { align-items:flex-start; flex-direction:column; }
        .mu-selection-actions { justify-content:flex-start; }
    }
    @media(max-width:560px) {
        .mu-subtitle { font-size:14px; }
        .mu-heading-actions { width:100%; }
        .mu-heading-actions .mu-btn { flex:1 1 auto; }
        .mu-modal { align-items:flex-start; padding:12px; }
        .mu-modal-card { max-height:calc(100vh - 24px); }
        .mu-modal-head,.mu-modal-body { padding-right:17px; padding-left:17px; }
        .mu-form-grid { grid-template-columns:minmax(0,1fr); }
        .mu-field-full { grid-column:auto; }
        .mu-modal-footer { padding-right:17px; padding-left:17px; }
        .mu-modal-footer .mu-btn { flex:1 1 auto; }
    }

</style>

<div class="wrapper">
    <div class="main-layout">
        @include('admin.sidebar')

        <main class="content admin-manage-users-view">

         

            <div class="mu-page">

                <div class="mu-heading">
                    <div>
                        <h1 class="mu-title">Manage Users</h1>
                        <p class="mu-subtitle">
                            Add, edit, activate, deactivate, archive, restore,
                            and reset user passwords.
                        </p>
                    </div>

                    <div class="mu-heading-actions">
                        <button type="button"
                                class="mu-btn mu-primary"
                                onclick="muOpen('muAddModal')">
                            + Add User
                        </button>

                        <button type="button"
                                class="mu-btn mu-secondary"
                                onclick="muOpen('muArchiveModal')">
                            Archived Users ({{ isset($archivedUsers) ? $archivedUsers->count() : 0 }})
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mu-alert mu-alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mu-alert mu-alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mu-alert mu-alert-error">
                        <strong>Please correct the following:</strong>
                        <ul style="margin:8px 0 0;padding-left:20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <section class="mu-panel">

                    <div class="mu-panel-header">
                        <div>
                            <h2>Current Users</h2>
                            <span class="mu-count" id="muAccountCount">
                                {{ $users->count() }} account(s)
                            </span>
                        </div>

                        <input type="search"
                               id="muSearch"
                               class="mu-search"
                               placeholder="Search name, username, email, role..."
                               aria-label="Search users">
                    </div>

                    <div class="mu-selection-toolbar" id="muSelectionToolbar" hidden>
                        <div><strong id="muSelectedCount">0</strong> accounts selected</div>
                        <div class="mu-selection-actions">
                            <button type="button" class="mu-btn mu-success"
                                    onclick="muBulkAction('activate')">
                                <i class="fa-solid fa-toggle-on" aria-hidden="true"></i>
                                Activate
                            </button>
                            <button type="button" class="mu-btn mu-warning"
                                    onclick="muBulkAction('deactivate')">
                                <i class="fa-solid fa-toggle-off" aria-hidden="true"></i>
                                Deactivate
                            </button>
                            <button type="button" class="mu-btn mu-danger"
                                    onclick="muBulkAction('archive')">
                                <i class="fa-solid fa-box-archive" aria-hidden="true"></i>
                                Archive
                            </button>
                            <button type="button" class="mu-btn mu-secondary"
                                    onclick="muClearSelection()">
                                Clear selection
                            </button>
                        </div>
                    </div>

                    <div class="mu-table-wrap">
                        <table class="mu-table" id="muUsersTable">
                            <thead>
                                <tr>
                                    <th class="mu-select-col">
                                        <input type="checkbox" class="mu-select-all" id="muSelectAll"
                                               aria-label="Select visible users">
                                    </th>
                                    <th>User</th>
                                    <th>Username / Email</th>
                                    <th>College</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($users as $user)
                                    <tr class="mu-user-row" data-status="{{ $user->status ?? 'active' }}">
                                        <td class="mu-select-col">
                                            <input type="checkbox" class="mu-select-user"
                                                   data-status="{{ $user->status ?? 'active' }}"
                                                   aria-label="Select {{ $user->name }}">
                                        </td>
                                        <td>
                                            <span class="mu-user-name">
                                                {{ $user->name }}
                                            </span>
                                            <span class="mu-muted">
                                                ID: {{ $user->id }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="mu-user-username">{{ $user->username }}</div>
                                            <span class="mu-muted mu-email">
                                                {{ $user->email }}
                                            </span>
                                        </td>

                                        <td>
                                            @if($user->department)
                                                <span class="mu-college">
                                                {{ $user->department->name
                                                    ?? $user->department->code
                                                    ?? 'Assigned' }}
                                                </span>
                                            @else
                                                <span class="mu-muted mu-college">Not assigned</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="mu-role">
                                                {{ $user->role === 'faculty' ? 'Instructor' : ucfirst($user->role) }}
                                            </span>
                                        </td>

                                        <td>
                                            @if(($user->status ?? 'active') === 'inactive')
                                                <span class="mu-status mu-status-inactive">
                                                    Inactive
                                                </span>
                                            @else
                                                <span class="mu-status mu-status-active">
                                                    Active
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="mu-actions">
                                                <button type="button" class="mu-menu-trigger"
                                                        aria-label="Actions for {{ $user->name }}"
                                                        aria-haspopup="menu" aria-expanded="false"
                                                        onclick="muToggleActionsMenu(this)">
                                                    <i class="fa-solid fa-ellipsis-vertical" aria-hidden="true"></i>
                                                </button>

                                                <div class="mu-actions-menu" role="menu">
                                                    <button type="button" class="mu-menu-item" role="menuitem"
                                                            onclick="muOpenEdit(this)"
                                                            data-id="{{ $user->id }}"
                                                            data-name="{{ $user->name }}"
                                                            data-username="{{ $user->username }}"
                                                            data-email="{{ $user->email }}"
                                                            data-role="{{ $user->role }}"
                                                            data-department="{{ $user->department_id }}"
                                                            data-status="{{ $user->status ?? 'active' }}">
                                                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                                        Edit user
                                                    </button>

                                                    <button type="button" class="mu-menu-item" role="menuitem"
                                                            onclick="muOpenReset(this)"
                                                            data-id="{{ $user->id }}"
                                                            data-name="{{ $user->name }}">
                                                        <i class="fa-solid fa-key" aria-hidden="true"></i>
                                                        Reset password
                                                    </button>

                                                    <div class="mu-menu-separator" role="separator"></div>

                                                    <form method="POST"
                                                          class="mu-menu-form mu-status-form"
                                                          action="{{ route('admin.manageusers.status', $user->id) }}"
                                                          data-user-name="{{ $user->name }}"
                                                          data-user-username="{{ $user->username }}"
                                                          data-user-email="{{ $user->email }}"
                                                          data-user-role="{{ ucfirst($user->role) }}"
                                                          data-user-college="{{ $user->department->name ?? $user->department->code ?? 'Not assigned' }}"
                                                          data-confirm="{{ ($user->status ?? 'active') === 'inactive' ? 'Reactivate this account and restore login access? Any saved instructor assignments will appear in chair lists again.' : 'Deactivate this account? The user will be signed out and blocked from logging in. Instructors will be hidden from chair assignment lists, while saved assignments and published schedules are kept.' }}"
                                                          onsubmit="return muReviewStatusForm(this)">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit" class="mu-menu-item" role="menuitem">
                                                            <i class="fa-solid {{ ($user->status ?? 'active') === 'inactive' ? 'fa-toggle-on' : 'fa-toggle-off' }}" aria-hidden="true"></i>
                                                            {{ ($user->status ?? 'active') === 'inactive' ? 'Activate account' : 'Deactivate account' }}
                                                        </button>
                                                    </form>

                                                    <form method="POST"
                                                          class="mu-menu-form mu-archive-form"
                                                          action="{{ route('admin.manageusers.delete', $user->id) }}"
                                                          data-confirm-delete="Archive this user account? It will be hidden from the active user list."
                                                          data-confirm="Archive this user account? It will be hidden from the active user list."
                                                          onsubmit="return muConfirmForm(this)">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="mu-menu-item danger" role="menuitem">
                                                            <i class="fa-solid fa-box-archive" aria-hidden="true"></i>
                                                            Archive account
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="mu-empty">
                                            No user accounts found.
                                        </td>
                                    </tr>
                                @endforelse

                                <tr id="muNoSearchResults" style="display:none;">
                                    <td colspan="7" class="mu-empty">
                                        No users match your search.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            {{-- ADD USER MODAL --}}
            <div class="mu-modal" id="muAddModal" aria-hidden="true">
                <div class="mu-modal-card">
                    <form method="POST" id="muAddUserForm" action="{{ route('admin.manageusers.store') }}"
                          onsubmit="return muReviewNewUser(this)">
                        @csrf

                        <div class="mu-modal-head">
                            <h2>Add User</h2>
                            <button type="button" class="mu-close"
                                    onclick="muClose('muAddModal')">&times;</button>
                        </div>

                        <div class="mu-modal-body">
                            <div class="mu-form-grid">

                                <div class="mu-field">
                                    <label for="muAddName">Full Name</label>
                                    <input class="mu-input" id="muAddName"
                                           name="name" required
                                           value="{{ old('name') }}">
                                </div>

                                <div class="mu-field">
                                    <label for="muAddUsername">Username</label>
                                    <input class="mu-input" id="muAddUsername"
                                           name="username" required
                                           value="{{ old('username') }}">
                                </div>

                                <div class="mu-field mu-field-full">
                                    <label for="muAddEmail">Email</label>
                                    <input class="mu-input" id="muAddEmail"
                                           name="email" type="email" required
                                           value="{{ old('email') }}">
                                </div>

                                <div class="mu-field">
                                    <label for="muAddPassword">Password</label>
                                    <div class="mu-password-wrap">
                                        <input class="mu-input" id="muAddPassword"
                                               name="password" type="password"
                                               minlength="6" required>
                                        <button type="button" class="mu-btn mu-secondary"
                                                onclick="muTogglePassword('muAddPassword', this)">
                                            Show
                                        </button>
                                    </div>
                                </div>

                                <div class="mu-field">
                                    <label for="muAddPasswordConfirmation">
                                        Confirm Password
                                    </label>
                                    <input class="mu-input"
                                           id="muAddPasswordConfirmation"
                                           name="password_confirmation"
                                           type="password" minlength="6" required>
                                </div>

                                <div class="mu-field">
                                    <label for="muAddRole">Role</label>
                                    <select class="mu-select" id="muAddRole"
                                            name="role" required
                                            onchange="muDepartmentRequired('muAddRole','muAddDepartment')">
                                        <option value="">Select role</option>
                                        <option value="admin">Admin</option>
                                        <option value="chair">Chair</option>
                                        <option value="faculty">Instructor</option>
                                    </select>
                                </div>

                                <div class="mu-field">
                                    <label for="muAddDepartment">College</label>
                                    <select class="mu-select" id="muAddDepartment"
                                            name="department_id">
                                        <option value="">No college selected</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">
                                                {{ $department->name ?? $department->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="mu-help">
                                        Required for Chair and Instructor accounts.
                                    </span>
                                </div>

                            </div>
                        </div>

                        <div class="mu-modal-footer">
                            <button type="button" class="mu-btn mu-secondary"
                                    onclick="muClose('muAddModal')">Cancel</button>
                            <button type="submit" class="mu-btn mu-primary">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mu-modal mu-review-modal" id="muAddReviewModal" aria-hidden="true"
                 role="dialog" aria-modal="true" aria-labelledby="muReviewTitle">
                <div class="mu-modal-card mu-review-card">
                    <div class="mu-modal-head"><h2 id="muReviewTitle">Review new user</h2></div>
                    <div class="mu-modal-body">
                        <p>Check these account details before creating the user.</p>
                        <dl class="mu-review-list">
                            <div><dt>Full name</dt><dd id="muReviewName"></dd></div>
                            <div><dt>Username</dt><dd id="muReviewUsername"></dd></div>
                            <div><dt>Email</dt><dd id="muReviewEmail"></dd></div>
                            <div><dt>Role</dt><dd id="muReviewRole"></dd></div>
                            <div><dt>College</dt><dd id="muReviewCollege"></dd></div>
                            <div><dt>Password</dt><dd>Entered and hidden for security</dd></div>
                        </dl>
                    </div>
                    <div class="mu-modal-footer">
                        <button type="button" class="mu-btn mu-secondary" onclick="muClose('muAddReviewModal')">Back to edit</button>
                        <button type="button" class="mu-btn mu-primary" id="muConfirmCreateUser" onclick="muSubmitNewUser()">Confirm and create</button>
                    </div>
                </div>
            </div>

            <div class="mu-modal mu-review-modal" id="muStatusReviewModal" aria-hidden="true"
                 role="dialog" aria-modal="true" aria-labelledby="muStatusReviewTitle">
                <div class="mu-modal-card mu-review-card">
                    <div class="mu-modal-head"><h2 id="muStatusReviewTitle">Confirm account status</h2></div>
                    <div class="mu-modal-body">
                        <p id="muStatusReviewMessage"></p>
                        <div id="muStatusReviewUserDetails"></div>
                        <ul class="mu-status-review-list" id="muStatusReviewUsers"></ul>
                    </div>
                    <div class="mu-modal-footer">
                        <button type="button" class="mu-btn mu-secondary" onclick="muClose('muStatusReviewModal')">Cancel</button>
                        <button type="button" class="mu-btn mu-primary" id="muConfirmStatusAction" onclick="muConfirmStatusChange()">Confirm</button>
                    </div>
                </div>
            </div>

            {{-- EDIT USER MODAL --}}
            <div class="mu-modal" id="muEditModal" aria-hidden="true">
                <div class="mu-modal-card">
                    <form method="POST" id="muEditForm"
                          data-confirm="Save these changes to the user account?"
                          onsubmit="return muConfirmForm(this)">
                        @csrf
                        @method('PUT')

                        <div class="mu-modal-head">
                            <h2>Edit User</h2>
                            <button type="button" class="mu-close"
                                    onclick="muClose('muEditModal')">&times;</button>
                        </div>

                        <div class="mu-modal-body">
                            <div class="mu-form-grid">

                                <div class="mu-field">
                                    <label for="muEditName">Full Name</label>
                                    <input class="mu-input" id="muEditName"
                                           name="name" required>
                                </div>

                                <div class="mu-field">
                                    <label for="muEditUsername">Username</label>
                                    <input class="mu-input" id="muEditUsername"
                                           name="username" required>
                                </div>

                                <div class="mu-field mu-field-full">
                                    <label for="muEditEmail">Email</label>
                                    <input class="mu-input" id="muEditEmail"
                                           name="email" type="email" required>
                                </div>

                                <div class="mu-field">
                                    <label for="muEditRole">Role</label>
                                    <select class="mu-select" id="muEditRole"
                                            name="role" required
                                            onchange="muDepartmentRequired('muEditRole','muEditDepartment')">
                                        <option value="admin">Admin</option>
                                        <option value="chair">Chair</option>
                                        <option value="faculty">Instructor</option>
                                    </select>
                                </div>

                                <div class="mu-field">
                                    <label for="muEditDepartment">College</label>
                                    <select class="mu-select" id="muEditDepartment"
                                            name="department_id">
                                        <option value="">No college selected</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">
                                                {{ $department->name ?? $department->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mu-field mu-field-full">
                                    <label for="muEditStatus">Account Status</label>
                                    <select class="mu-select" id="muEditStatus"
                                            name="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="mu-modal-footer">
                            <button type="button" class="mu-btn mu-secondary"
                                    onclick="muClose('muEditModal')">Cancel</button>
                            <button type="submit" class="mu-btn mu-primary">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RESET PASSWORD MODAL --}}
            <div class="mu-modal" id="muResetModal" aria-hidden="true">
                <div class="mu-modal-card">
                    <form method="POST" id="muResetForm"
                          data-confirm="Update this user's password?"
                          onsubmit="return muConfirmForm(this)">
                        @csrf

                        <div class="mu-modal-head">
                            <h2>Reset Password</h2>
                            <button type="button" class="mu-close"
                                    onclick="muClose('muResetModal')">&times;</button>
                        </div>

                        <div class="mu-modal-body">
                            <p style="margin-top:0;color:#64748b;font-size:13px;">
                                Reset password for
                                <strong id="muResetUserName"></strong>.
                            </p>

                            <div class="mu-field" style="margin-bottom:16px;">
                                <label for="muResetPassword">New Password</label>
                                <div class="mu-password-wrap">
                                    <input class="mu-input" id="muResetPassword"
                                           name="password" type="password"
                                           minlength="6" required>
                                    <button type="button" class="mu-btn mu-secondary"
                                            onclick="muTogglePassword('muResetPassword', this)">
                                        Show
                                    </button>
                                </div>
                            </div>

                            <div class="mu-field">
                                <label for="muResetConfirmation">Confirm New Password</label>
                                <input class="mu-input" id="muResetConfirmation"
                                       name="password_confirmation"
                                       type="password" minlength="6" required>
                            </div>
                        </div>

                        <div class="mu-modal-footer">
                            <button type="button" class="mu-btn mu-secondary"
                                    onclick="muClose('muResetModal')">Cancel</button>
                            <button type="submit" class="mu-btn mu-primary">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ARCHIVED USERS MODAL --}}
            <div class="mu-modal" id="muArchiveModal" aria-hidden="true">
                <div class="mu-modal-card mu-modal-wide">
                    <div class="mu-modal-head">
                        <h2>Archived Users</h2>
                        <button type="button" class="mu-close"
                                onclick="muClose('muArchiveModal')">&times;</button>
                    </div>

                    <div class="mu-modal-body" style="padding:0;">
                        <div class="mu-table-wrap">
                            <table class="mu-table" style="min-width:600px;">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Username / Email</th>
                                        <th>College</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse(($archivedUsers ?? collect()) as $archivedUser)
                                        <tr>
                                            <td>
                                                <span class="mu-user-name">
                                                    {{ $archivedUser->name }}
                                                </span>
                                                <span class="mu-muted">
                                                    ID: {{ $archivedUser->id }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $archivedUser->username }}
                                                <span class="mu-muted">
                                                    {{ $archivedUser->email }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $archivedUser->department->name
                                                    ?? $archivedUser->department->code
                                                    ?? 'Not assigned' }}
                                            </td>

                                            <td>
                                                <form method="POST"
                                                      action="{{ route('admin.manageusers.restore', $archivedUser->id) }}"
                                                      data-confirm="Restore this user account?"
                                                      onsubmit="return muConfirmForm(this)">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="mu-btn mu-success">
                                                        Restore
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="mu-empty">
                                                No archived users.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mu-modal-footer">
                        <button type="button" class="mu-btn mu-secondary"
                                onclick="muClose('muArchiveModal')">Close</button>
                    </div>
                </div>
            </div>

            <script>
                let muPendingNewUserForm = null;

                function muReviewNewUser(form) {
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return false;
                    }

                    const value = id => document.getElementById(id).value.trim();
                    const selectedText = (id, fallback) => {
                        const select = document.getElementById(id);
                        return select.selectedOptions[0]?.textContent.trim() || fallback;
                    };

                    document.getElementById('muReviewName').textContent = value('muAddName');
                    document.getElementById('muReviewUsername').textContent = value('muAddUsername');
                    document.getElementById('muReviewEmail').textContent = value('muAddEmail');
                    document.getElementById('muReviewRole').textContent = selectedText('muAddRole', 'Not selected');
                    document.getElementById('muReviewCollege').textContent = selectedText('muAddDepartment', 'No college selected');
                    muPendingNewUserForm = form;
                    muOpen('muAddReviewModal');
                    return false;
                }

                function muSubmitNewUser() {
                    if (!muPendingNewUserForm) return;
                    const button = document.getElementById('muConfirmCreateUser');
                    button.disabled = true;
                    button.textContent = 'Creating…';
                    muPendingNewUserForm.submit();
                }

                let muPendingStatusAction = null;

                function muReviewStatusForm(form) {
                    const willActivate = (form.querySelector('input[name="_method"]')?.value || '').toUpperCase() === 'PATCH'
                        && form.closest('tr')?.dataset.status === 'inactive';
                    const action = willActivate ? 'activate' : 'deactivate';
                    muShowStatusReview(action, [{
                        name: form.dataset.userName || 'User account',
                        detail: [form.dataset.userUsername, form.dataset.userEmail, form.dataset.userRole, form.dataset.userCollege]
                            .filter(Boolean).join(' · ')
                    }], function() { form.submit(); });
                    return false;
                }

                function muShowStatusReview(action, users, onConfirm) {
                    const activating = action === 'activate';
                    const title = activating ? 'Activate user account?' : 'Deactivate user account?';
                    document.getElementById('muStatusReviewTitle').textContent = users.length > 1
                        ? (activating ? 'Activate selected users?' : 'Deactivate selected users?')
                        : title;
                    document.getElementById('muStatusReviewMessage').textContent = activating
                        ? 'These accounts will be able to sign in again. Instructor accounts will return to chair assignment lists.'
                        : 'These accounts will be signed out and blocked from logging in. Instructor assignments and published schedules will be kept.';

                    const details = document.getElementById('muStatusReviewUserDetails');
                    details.replaceChildren();
                    const list = document.getElementById('muStatusReviewUsers');
                    list.replaceChildren();
                    users.forEach(function(user) {
                        const item = document.createElement('li');
                        item.textContent = user.name || 'User account';
                        if (user.detail) {
                            const detail = document.createElement('span');
                            detail.textContent = user.detail;
                            item.appendChild(detail);
                        }
                        list.appendChild(item);
                    });

                    const button = document.getElementById('muConfirmStatusAction');
                    button.textContent = activating ? 'Activate account' : 'Deactivate account';
                    button.classList.toggle('mu-warning', !activating);
                    button.classList.toggle('mu-primary', activating);
                    muPendingStatusAction = onConfirm;
                    muOpen('muStatusReviewModal');
                }

                function muConfirmStatusChange() {
                    if (!muPendingStatusAction) return;
                    const action = muPendingStatusAction;
                    muPendingStatusAction = null;
                    muClose('muStatusReviewModal');
                    action();
                }

                function muConfirmForm(form) {
                    return window.confirm(form.dataset.confirm || 'Continue with this action?');
                }

                function muCloseActionMenus() {
                    document.querySelectorAll('.mu-actions-menu.is-open').forEach(function(menu) {
                        menu.classList.remove('is-open');
                        const trigger = menu.parentElement.querySelector('.mu-menu-trigger');
                        if (trigger) trigger.setAttribute('aria-expanded', 'false');
                    });
                }

                function muToggleActionsMenu(trigger) {
                    const menu = trigger.parentElement.querySelector('.mu-actions-menu');
                    if (!menu) return;

                    const wasOpen = menu.classList.contains('is-open');
                    muCloseActionMenus();
                    if (wasOpen) return;

                    menu.classList.add('is-open');
                    trigger.setAttribute('aria-expanded', 'true');

                    const triggerRect = trigger.getBoundingClientRect();
                    const menuRect = menu.getBoundingClientRect();
                    const left = Math.max(
                        12,
                        Math.min(triggerRect.right - menuRect.width, window.innerWidth - menuRect.width - 12)
                    );
                    let top = triggerRect.bottom + 6;

                    if (top + menuRect.height > window.innerHeight - 12) {
                        top = triggerRect.top - menuRect.height - 6;
                    }

                    menu.style.left = left + 'px';
                    menu.style.top = Math.max(12, top) + 'px';
                }

                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.mu-actions')) {
                        muCloseActionMenus();
                        return;
                    }

                    if (event.target.closest('.mu-menu-item')) {
                        muCloseActionMenus();
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') muCloseActionMenus();
                });
                window.addEventListener('resize', muCloseActionMenus);
                window.addEventListener('scroll', muCloseActionMenus, true);

                const muSelectionToolbar = document.getElementById('muSelectionToolbar');
                const muSelectAll = document.getElementById('muSelectAll');

                function muUserRows() {
                    return Array.from(document.querySelectorAll('#muUsersTable tbody tr.mu-user-row'));
                }

                function muSelectedRows() {
                    return muUserRows().filter(function(row) {
                        const checkbox = row.querySelector('.mu-select-user');
                        return checkbox && checkbox.checked;
                    });
                }

                function muUpdateSelectionState() {
                    const rows = muUserRows();
                    const selectedCount = muSelectedRows().length;
                    const visibleCheckboxes = rows
                        .filter(function(row) { return row.style.display !== 'none'; })
                        .map(function(row) { return row.querySelector('.mu-select-user'); })
                        .filter(Boolean);

                    if (muSelectionToolbar) muSelectionToolbar.hidden = selectedCount === 0;
                    const count = document.getElementById('muSelectedCount');
                    if (count) count.textContent = selectedCount;

                    if (muSelectAll) {
                        const checkedVisible = visibleCheckboxes.filter(function(box) { return box.checked; }).length;
                        muSelectAll.checked = visibleCheckboxes.length > 0 && checkedVisible === visibleCheckboxes.length;
                        muSelectAll.indeterminate = checkedVisible > 0 && checkedVisible < visibleCheckboxes.length;
                    }
                }

                function muClearSelection() {
                    document.querySelectorAll('.mu-select-user').forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                    if (muSelectAll) {
                        muSelectAll.checked = false;
                        muSelectAll.indeterminate = false;
                    }
                    muUpdateSelectionState();
                }

                if (muSelectAll) {
                    muSelectAll.addEventListener('change', function() {
                        muUserRows().forEach(function(row) {
                            if (row.style.display === 'none') return;
                            const checkbox = row.querySelector('.mu-select-user');
                            if (checkbox) checkbox.checked = muSelectAll.checked;
                        });
                        muUpdateSelectionState();
                    });
                }

                document.querySelectorAll('.mu-select-user').forEach(function(checkbox) {
                    checkbox.addEventListener('change', muUpdateSelectionState);
                });

                function muShowActionFeedback(message, isError) {
                    const panel = document.querySelector('.mu-panel');
                    if (!panel) return;

                    document.querySelectorAll('.mu-action-feedback').forEach(function(item) {
                        item.remove();
                    });

                    const feedback = document.createElement('div');
                    feedback.className = 'mu-alert mu-action-feedback ' +
                        (isError ? 'mu-alert-error' : 'mu-alert-success');
                    feedback.textContent = message;
                    panel.parentNode.insertBefore(feedback, panel);
                }

                function muUpdateRowStatus(row, status) {
                    row.dataset.status = status;
                    const checkbox = row.querySelector('.mu-select-user');
                    if (checkbox) {
                        checkbox.dataset.status = status;
                        checkbox.checked = false;
                    }

                    const badge = row.querySelector('.mu-status');
                    if (badge) {
                        badge.textContent = status === 'inactive' ? 'Inactive' : 'Active';
                        badge.classList.toggle('mu-status-inactive', status === 'inactive');
                        badge.classList.toggle('mu-status-active', status === 'active');
                    }

                    const form = row.querySelector('.mu-status-form');
                    const button = form && form.querySelector('button');
                    if (form && button) {
                        const willActivate = status === 'inactive';
                        form.dataset.confirm = willActivate
                            ? 'Reactivate this account and restore login access? Any saved instructor assignments will appear in chair lists again.'
                            : 'Deactivate this account? The user will be signed out and blocked from logging in. Instructors will be hidden from chair assignment lists, while saved assignments and published schedules are kept.';
                        button.innerHTML = '';
                        const icon = document.createElement('i');
                        icon.className = 'fa-solid ' + (willActivate ? 'fa-toggle-on' : 'fa-toggle-off');
                        icon.setAttribute('aria-hidden', 'true');
                        button.appendChild(icon);
                        button.appendChild(document.createTextNode(
                            willActivate ? 'Activate account' : 'Deactivate account'
                        ));
                    }
                }

                function muUpdateAccountCount() {
                    const count = document.getElementById('muAccountCount');
                    if (!count) return;
                    count.textContent = muUserRows().length + ' account(s)';
                }

                async function muBulkAction(action, confirmedByCard) {
                    const selected = muSelectedRows();
                    if (selected.length === 0) return;

                    let targets = selected;
                    let actionLabel = '';

                    if (action === 'activate') {
                        targets = selected.filter(function(row) { return row.dataset.status === 'inactive'; });
                        actionLabel = 'activate';
                    } else if (action === 'deactivate') {
                        targets = selected.filter(function(row) { return row.dataset.status !== 'inactive'; });
                        actionLabel = 'deactivate';
                    } else {
                        actionLabel = 'archive';
                    }

                    if (targets.length === 0) {
                        window.alert('The selected accounts already have that status.');
                        return;
                    }

                    const confirmMessage = action === 'archive'
                        ? 'Archive ' + targets.length + ' selected account(s)? They will be hidden from the active user list.'
                        : action === 'deactivate'
                            ? 'Deactivate ' + targets.length + ' selected account(s)? They will be signed out and blocked from logging in. Instructors will be hidden from chair assignment lists; existing assignments and schedules will be kept.'
                        : actionLabel.charAt(0).toUpperCase() + actionLabel.slice(1) +
                            ' ' + targets.length + ' selected account(s)?';
                    if ((action === 'activate' || action === 'deactivate') && !confirmedByCard) {
                        const users = targets.map(function(row) {
                            const name = row.querySelector('.mu-user-name')?.textContent.trim() || 'User account';
                            const username = row.querySelector('.mu-user-username')?.textContent.trim() || '';
                            const email = row.querySelector('.mu-user-email')?.textContent.trim() || '';
                            const role = row.querySelector('.mu-role')?.textContent.trim() || '';
                            const college = row.querySelector('.mu-college')?.textContent.trim() || '';
                            return { name: name, detail: [username, email, role, college].filter(Boolean).join(' · ') };
                        });
                        muShowStatusReview(action, users, function() { muBulkAction(action, true); });
                        return;
                    }

                    const confirmed = action === 'archive'
                        ? await window.showSystemConfirmation(confirmMessage, 'Archive')
                        : true;
                    if (!confirmed) return;

                    const actionButtons = muSelectionToolbar
                        ? Array.from(muSelectionToolbar.querySelectorAll('button'))
                        : [];
                    actionButtons.forEach(function(button) { button.disabled = true; });

                    let completed = 0;
                    const failed = [];

                    for (const row of targets) {
                        const form = row.querySelector(
                            action === 'archive' ? '.mu-archive-form' : '.mu-status-form'
                        );

                        if (!form) {
                            failed.push(row.querySelector('.mu-user-name')?.textContent.trim() || 'A user');
                            continue;
                        }

                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                body: new FormData(form),
                                credentials: 'same-origin',
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            });

                            if (!response.ok) throw new Error('Request failed');

                            completed += 1;
                            if (action === 'archive') {
                                row.remove();
                            } else {
                                muUpdateRowStatus(row, action === 'activate' ? 'active' : 'inactive');
                            }
                        } catch (error) {
                            failed.push(row.querySelector('.mu-user-name')?.textContent.trim() || 'A user');
                        }
                    }

                    actionButtons.forEach(function(button) { button.disabled = false; });
                    muUpdateAccountCount();
                    muUpdateSelectionState();
                    muFilterUsers();

                    if (muUserRows().length === 0) {
                        const emptyRow = document.createElement('tr');
                        emptyRow.id = 'muBulkEmptyUsers';
                        emptyRow.innerHTML = '<td colspan="7" class="mu-empty">No user accounts found.</td>';
                        document.querySelector('#muUsersTable tbody').insertBefore(
                            emptyRow,
                            document.getElementById('muNoSearchResults')
                        );
                    }

                    if (failed.length > 0) {
                        muShowActionFeedback(
                            'Completed ' + completed + ' of ' + targets.length +
                                ' actions. Refresh the page to verify any remaining accounts.',
                            true
                        );
                    } else {
                        muShowActionFeedback(
                            'Successfully ' + actionLabel + 'd ' + completed + ' account(s).',
                            false
                        );
                    }
                }

                function muOpen(id) {
                    const modal = document.getElementById(id);
                    if (!modal) return;

                    modal.classList.add('mu-open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function muClose(id) {
                    const modal = document.getElementById(id);
                    if (!modal) return;

                    modal.classList.remove('mu-open');
                    modal.setAttribute('aria-hidden', 'true');

                    if (!document.querySelector('.mu-modal.mu-open')) {
                        document.body.style.overflow = '';
                    }
                }

                document.querySelectorAll('.mu-modal').forEach(function(modal) {
                    modal.addEventListener('click', function(event) {
                        if (event.target === modal) {
                            muClose(modal.id);
                        }
                    });
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        document.querySelectorAll('.mu-modal.mu-open').forEach(function(modal) {
                            muClose(modal.id);
                        });
                    }
                });

                function muOpenEdit(button) {
                    const id = button.dataset.id;
                    const base = @json(url('/admin/manageusers'));

                    document.getElementById('muEditForm').action = base + '/' + id;
                    document.getElementById('muEditName').value = button.dataset.name || '';
                    document.getElementById('muEditUsername').value = button.dataset.username || '';
                    document.getElementById('muEditEmail').value = button.dataset.email || '';
                    document.getElementById('muEditRole').value = button.dataset.role || 'faculty';
                    document.getElementById('muEditDepartment').value = button.dataset.department || '';
                    document.getElementById('muEditStatus').value = button.dataset.status || 'active';

                    muDepartmentRequired('muEditRole', 'muEditDepartment');
                    muOpen('muEditModal');
                }

                function muOpenReset(button) {
                    const id = button.dataset.id;
                    const base = @json(url('/admin/manageusers'));

                    document.getElementById('muResetForm').action =
                        base + '/' + id + '/reset-password';

                    document.getElementById('muResetUserName').textContent =
                        button.dataset.name || 'this user';

                    document.getElementById('muResetPassword').value = '';
                    document.getElementById('muResetConfirmation').value = '';

                    muOpen('muResetModal');
                }

                function muDepartmentRequired(roleId, departmentId) {
                    const role = document.getElementById(roleId);
                    const department = document.getElementById(departmentId);

                    if (!role || !department) return;

                    const required = ['chair', 'faculty'].includes(role.value);
                    department.required = required;

                    if (role.value === 'admin') {
                        department.value = '';
                    }
                }

                function muTogglePassword(inputId, button) {
                    const input = document.getElementById(inputId);
                    if (!input) return;

                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    button.textContent = isPassword ? 'Hide' : 'Show';
                }

                function muFilterUsers() {
                    const search = document.getElementById('muSearch');
                    if (!search) return;

                    const term = search.value.toLowerCase().trim();
                    const rows = muUserRows();
                    let visibleCount = 0;

                    rows.forEach(function(row) {
                        const matches = row.textContent.toLowerCase().includes(term);
                        row.style.display = matches ? '' : 'none';
                        if (matches) visibleCount++;
                    });

                    const noResults = document.getElementById('muNoSearchResults');
                    if (noResults) {
                        noResults.style.display = visibleCount === 0 && rows.length > 0 ? '' : 'none';
                    }

                    const emptyUsers = document.getElementById('muBulkEmptyUsers');
                    if (emptyUsers) emptyUsers.style.display = rows.length === 0 ? '' : 'none';
                    muUpdateSelectionState();
                }

                const muSearch = document.getElementById('muSearch');
                if (muSearch) muSearch.addEventListener('input', muFilterUsers);

                // Reopen a modal if the controller redirects back with validation errors.
                @if($errors->any())
                    @if(old('_method') === 'PUT')
                        muOpen('muEditModal');
                    @elseif(request()->isMethod('post') && old('password_confirmation') !== null)
                        muOpen('muResetModal');
                    @else
                        muOpen('muAddModal');
                    @endif
                @endif

            </script>

        </main>
    </div>

</div>

