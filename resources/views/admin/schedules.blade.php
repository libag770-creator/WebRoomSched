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

    .faculty-schedules-view {
        --schedule-forest: var(--wr-forest, #173b33);
        --schedule-pine: var(--wr-pine, #255a4b);
        --schedule-paper: var(--wr-paper, #f7f6f1);
        --schedule-ink: var(--wr-ink, #1e2d29);
        --schedule-muted: var(--wr-muted, #718078);
        --schedule-line: var(--wr-line, #e2e8e1);
        background: var(--schedule-paper);
    }
    
    .faculty-schedule-page { width: min(100%, 1420px); margin: 0 auto; color: var(--schedule-ink); }
    .schedule-header { display:flex; align-items:flex-end; justify-content:space-between; gap:24px; margin:0 0 24px; padding:0 0 21px; border-bottom:1px solid var(--schedule-line); }
    .schedule-heading-copy { max-width:760px; }
    .schedule-kicker,.selected-department-kicker { display:block; margin:0 0 8px; color:#9b8558; font:700 9px/1.4 "DM Sans",sans-serif; letter-spacing:.16em; }
    .schedule-title { margin:0; color:var(--schedule-forest); font:700 clamp(27px,3vw,38px)/1.1 "Manrope",sans-serif; letter-spacing:-.055em; }
    .schedule-subtitle { max-width:600px; margin:8px 0 0; color:var(--schedule-muted); font:400 12px/1.65 "DM Sans",sans-serif; }
    .schedule-header-note { display:flex; align-items:center; gap:11px; flex:0 0 auto; padding:11px 14px; border:1px solid var(--schedule-line); border-radius:13px; background:#fff; }
    .schedule-header-note-icon { display:grid; width:35px; height:35px; place-items:center; border-radius:11px; background:#eef3ec; color:var(--schedule-pine); }
    .schedule-header-note > span:last-child { display:grid; gap:3px; }
    .schedule-header-note strong { color:var(--schedule-forest); font:700 10px/1.2 "Manrope",sans-serif; }
    .schedule-header-note small { color:#8b9890; font:400 9px/1.3 "DM Sans",sans-serif; }
    .schedule-journey { display:flex; align-items:center; gap:13px; margin:0 0 29px; padding:13px 16px; border:1px solid #e5e9e2; border-radius:15px; background:rgba(255,255,255,.76); }
    .schedule-step { display:flex; align-items:center; gap:9px; min-width:0; color:#96a199; }
    .schedule-step > span { display:grid; flex:0 0 29px; width:29px; height:29px; place-items:center; border:1px solid #e2e8df; border-radius:50%; background:#f8f9f5; color:#87948b; font:700 9px/1 "DM Sans",sans-serif; }
    .schedule-step > div { display:grid; gap:3px; }
    .schedule-step strong { color:#6e7d73; font:700 10px/1.2 "Manrope",sans-serif; }
    .schedule-step small { color:#9aa49d; font:400 8px/1.3 "DM Sans",sans-serif; }
    .schedule-step.current > span,.schedule-step.complete > span { border-color:var(--schedule-forest); background:var(--schedule-forest); color:#fff; }
    .schedule-step.current strong,.schedule-step.complete strong { color:var(--schedule-forest); }
    .schedule-step-rule { flex:1; min-width:14px; height:1px; background:#dfe6de; }
    .schedule-section-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:14px; margin:0 0 13px; }
    .faculty-schedules-section-heading { margin:0 0 4px; color:var(--schedule-forest); font:700 16px/1.2 "Manrope",sans-serif; letter-spacing:-.035em; }
    .schedule-section-heading p { margin:0; color:#86938b; font:400 10px/1.45 "DM Sans",sans-serif; }
    .schedule-count-note { padding:5px 9px; border:1px solid var(--schedule-line); border-radius:30px; background:#fff; color:#75847a; font:600 9px/1 "DM Sans",sans-serif; white-space:nowrap; }
    .department-list { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; margin:0 0 18px; }
    .department-card { position:relative; display:flex; align-items:center; gap:12px; width:100%; min-height:84px; padding:14px; border:1px solid #e2e8e1; border-radius:15px; background:#fff; color:var(--schedule-ink); text-align:left; cursor:pointer; transition:border-color .16s ease,background .16s ease,transform .16s ease,box-shadow .16s ease; }
    .department-card:hover { transform:translateY(-2px); border-color:#c7d7c9; box-shadow:0 10px 24px rgba(27,57,43,.07); }
    .department-card.active { border-color:#9db6a2; background:#f4f7f1; box-shadow:inset 3px 0 var(--schedule-pine); }
    .department-icon { display:grid; flex:0 0 40px; width:40px; height:40px; place-items:center; border-radius:12px; background:#eff4ed; color:var(--schedule-pine); font-size:15px; }
    .department-copy { display:grid; min-width:0; flex:1; gap:4px; }
    .department-label { color:#9a895f; font:700 7px/1.2 "DM Sans",sans-serif; letter-spacing:.14em; }
    .department-name { overflow:hidden; color:#2a3e34; font:700 12px/1.25 "Manrope",sans-serif; text-overflow:ellipsis; }
    .department-info { color:#89968e; font:400 9px/1.2 "DM Sans",sans-serif; }
    .department-arrow { display:grid; flex:0 0 27px; width:27px; height:27px; place-items:center; border:1px solid #e8ede6; border-radius:9px; color:#8d9a91; font-size:9px; }
    .department-card.active .department-arrow { border-color:#d7e2d6; color:var(--schedule-pine); }
    .department-content { display:none; margin:17px 0 18px; padding:19px; border:1px solid #e1e8e0; border-radius:17px; background:#fff; box-shadow:0 7px 22px rgba(34,57,44,.035); scroll-margin-top:95px; }
    .department-content.open { display:block; animation:schedule-appear .2s ease both; }
    @keyframes schedule-appear { from{opacity:0;transform:translateY(5px)} to{opacity:1;transform:translateY(0)} }
    .selected-department-header { display:flex; align-items:center; justify-content:space-between; gap:15px; margin:0 0 13px; padding:0 0 15px; border-bottom:1px solid #edf0ea; }
    .selected-department-kicker { margin-bottom:5px; font-size:7px; }
    .selected-department-title { margin:0; color:var(--schedule-forest); font:700 17px/1.25 "Manrope",sans-serif; letter-spacing:-.04em; }
    .selected-department-subtitle { margin:4px 0 0; color:#849188; font:400 10px/1.5 "DM Sans",sans-serif; }
    .selected-department-mark { display:grid; flex:0 0 38px; width:38px; height:38px; place-items:center; border:1px solid #e2e9df; border-radius:12px; background:#f7f8f4; color:var(--schedule-pine); }
    .building-list { display:grid; gap:8px; }
    .building-card { overflow:hidden; border:1px solid #e5eae3; border-radius:12px; background:#fff; }
    .building-button { display:flex; align-items:center; gap:12px; width:100%; min-height:58px; padding:9px 13px; border:0; background:#fff; color:var(--schedule-ink); text-align:left; cursor:pointer; transition:background .16s ease; }
    .building-button:hover,.building-button.active { background:#f8faf6; }
    .building-left { display:flex; align-items:center; gap:10px; flex:1; min-width:0; }
    .building-icon { display:grid; flex:0 0 34px; width:34px; height:34px; place-items:center; border-radius:10px; background:#f0f4ee; color:#607d6b; font-size:12px; }
    .building-copy { display:grid; min-width:0; gap:3px; }
    .building-name { overflow:hidden; color:#34473d; font:700 11px/1.2 "Manrope",sans-serif; text-overflow:ellipsis; }
    .building-department { color:#929d95; font:400 8px/1.2 "DM Sans",sans-serif; }
    .building-room-count { padding:5px 8px; border-radius:99px; background:#f2f4ef; color:#76847b; font:600 8px/1 "DM Sans",sans-serif; white-space:nowrap; }
    .building-arrow { display:grid; width:25px; height:25px; place-items:center; color:#87958c; font-size:9px; transition:transform .16s ease; }
    .building-button.active .building-arrow { color:var(--schedule-pine); transform:rotate(180deg); }
    .rooms-container { display:none; grid-template-columns:repeat(4,minmax(0,1fr)); gap:8px; padding:0 12px 12px; }
    .rooms-container.open { display:grid; }
    .rooms-container[aria-hidden="true"] { display:none; }
    .room-card { display:flex; flex-direction:column; align-items:stretch; min-width:0; min-height:93px; padding:11px; border:1px solid #e6ebe4; border-radius:11px; background:#fbfcf9; color:var(--schedule-ink); text-align:left; cursor:pointer; transition:border-color .16s ease,background .16s ease,transform .16s ease; }
    .room-card:hover { transform:translateY(-1px); border-color:#b7cdbb; background:#f4f8f2; }
    .room-card.active { border-color:#6f9780; background:#edf4ec; box-shadow:inset 0 0 0 1px #d6e3d6; }
    .room-top { display:flex; align-items:center; justify-content:space-between; gap:8px; }
    .room-name { color:#344a3e; font:700 12px/1.2 "Manrope",sans-serif; }
    .room-arrow { color:#83958a; font-size:9px; }
    .room-info { margin-top:7px; color:#89968e; font:400 8px/1.3 "DM Sans",sans-serif; }
    .room-action { display:flex; align-items:center; gap:5px; margin-top:auto; padding-top:10px; color:var(--schedule-pine); font:700 8px/1 "DM Sans",sans-serif; }
    .room-action::after { content:"→"; font-size:10px; }
    .empty-building,.empty-department { padding:15px; border:1px dashed #dfe6dd; border-radius:10px; color:#87948b; font:400 10px/1.5 "DM Sans",sans-serif; }
    .department-placeholder { display:grid; justify-items:center; margin:18px 0; padding:28px 20px; border:1px dashed #dbe4db; border-radius:15px; background:rgba(255,255,255,.55); text-align:center; }
    .department-placeholder-icon { display:grid; width:40px; height:40px; place-items:center; margin-bottom:9px; border-radius:13px; background:#edf3eb; color:var(--schedule-pine); font-size:15px; }
    .department-placeholder h3 { margin:0; color:#40564a; font:700 12px/1.3 "Manrope",sans-serif; }
    .department-placeholder p { max-width:360px; margin:5px 0 0; color:#89958d; font:400 9px/1.5 "DM Sans",sans-serif; }
    .schedule-panel { display:none; margin:18px 0 0; overflow:hidden; border:1px solid #dfe7de; border-radius:17px; background:#fff; box-shadow:0 12px 32px rgba(30,55,41,.06); scroll-margin-top:86px; }
    .schedule-panel.open { display:block; animation:schedule-appear .2s ease both; }
    .schedule-panel-header { display:flex; align-items:center; justify-content:space-between; gap:15px; padding:17px 19px; border-bottom:1px solid #e9eee7; background:#fff; }
    .schedule-panel-title { margin:0; color:var(--schedule-forest); font:700 16px/1.25 "Manrope",sans-serif; letter-spacing:-.04em; }
    .schedule-panel-subtitle { margin-top:4px; color:#839087; font:400 9px/1.4 "DM Sans",sans-serif; }
    .close-schedule { display:inline-flex; align-items:center; gap:7px; min-height:34px; padding:0 11px; border:1px solid #e1e8df; border-radius:9px; background:#f8faf6; color:#60736a; font:600 9px/1 "DM Sans",sans-serif; cursor:pointer; }
    .close-schedule:hover { border-color:#cbd9cb; background:#eff4ed; color:var(--schedule-forest); }
    .schedule-legend { display:flex; flex-wrap:wrap; align-items:center; gap:7px 10px; padding:11px 17px; border-bottom:1px solid #edf0ea; background:#fafbf8; color:#859188; font:400 8px/1.4 "DM Sans",sans-serif; }
    .schedule-legend-badge { display:inline-flex; align-items:center; gap:5px; padding:5px 7px; border:1px solid #e4e9e2; border-radius:99px; background:#fff; color:#62766a; font:700 8px/1 "DM Sans",sans-serif; }
    .schedule-legend-badge i { color:var(--schedule-pine); }
    .room-override-panel { display:grid; gap:9px; margin:16px 0 4px; padding:14px; border:1px solid #e7d49f; border-radius:12px; background:#fff8e8; color:#59451c; }
    .room-override-panel h3 { margin:0; color:#574116; font:800 13px/1.35 "Manrope",sans-serif; }
    .room-override-panel p { margin:0 0 2px; color:#76694d; font:500 11px/1.45 "DM Sans",sans-serif; }
    .room-override-entry { display:grid; gap:4px; padding:10px 12px; border:1px solid #f0e1b9; border-radius:9px; background:#fffdf7; }
    button.room-override-entry { width:100%; font:inherit; text-align:left; cursor:pointer; }
    button.room-override-entry:hover,button.room-override-entry[aria-pressed="true"] { border-color:#c8473d; background:#fff0ef; box-shadow:0 0 0 2px rgba(200,71,61,.12); }
    .room-override-entry strong { color:#5d4719; font:800 12px/1.4 "Manrope",sans-serif; }
    .room-override-entry span { color:#655b40; font:500 11px/1.45 "DM Sans",sans-serif; }
    .schedule-override-notice { display:grid; gap:2px; margin-top:4px; padding:5px 6px; border:1px solid #efc7c3; border-left:3px solid #c8473d; border-radius:6px; background:#fff1ef; color:#7d302b; font:600 8px/1.25 "DM Sans",sans-serif; }
    .schedule-override-notice strong { color:#9f332d; font:800 8px/1.25 "Manrope",sans-serif; }
    .schedule-override-notice span { overflow-wrap:anywhere; }
    .published-schedule-block .schedule-override-notice { width:100%; font-size:10px; line-height:1.2; text-align:left !important; }
    .published-schedule-block.schedule-override-highlight { border-color:#c8473d !important; background:linear-gradient(145deg,#ffe9e7,#fff7f6) !important; box-shadow:inset 4px 0 #c8473d,0 3px 9px rgba(138,36,32,.13) !important; }
    .published-schedule-override-day-highlight { position:absolute; top:0; bottom:0; z-index:0; border-left:4px solid #c8473d; background:rgba(200,71,61,.26); pointer-events:none; }
    .published-schedule-block > :not(.published-schedule-override-day-highlight) { position:relative; z-index:1; }
    #scheduleContent { padding:13px; }
    .schedule-loading,.schedule-error { display:grid; justify-items:center; gap:8px; padding:32px 16px; color:#7f8d84; font:500 10px/1.5 "DM Sans",sans-serif; text-align:center; }
    .loading-spinner { width:22px; height:22px; border:2px solid #e4ebe2; border-top-color:var(--schedule-pine); border-radius:50%; animation:schedule-spin .7s linear infinite; }
    @keyframes schedule-spin { to{transform:rotate(360deg)} }
    .schedule-error-title { color:#914d45; font:700 12px "Manrope",sans-serif; }
    .schedule-error-message { max-width:460px; }
    .retry-schedule-btn { min-height:33px; padding:0 10px; border:0; border-radius:8px; background:var(--schedule-forest); color:#fff; font:600 9px "DM Sans",sans-serif; cursor:pointer; }
    .timetable-wrapper { width:100%; overflow:auto; border:1px solid #e5eae3; border-radius:11px; scrollbar-color:#ccd8cc #f7f8f5; }
    .faculty-timetable { width:100%; min-width:980px; border-collapse:separate; border-spacing:0; background:#fff; }
.timetable-wrapper { position:relative; }
.published-schedule-block-layer { position:absolute; inset:0; z-index:8; pointer-events:none; }
.published-schedule-source-hidden { visibility:hidden !important; height:0 !important; min-height:0 !important; max-height:0 !important; margin:0 !important; padding:0 !important; border-width:0 !important; overflow:hidden !important; }
.published-schedule-block { position:absolute !important; min-height:0 !important; max-height:none; margin:0; padding:5px 7px; overflow:hidden; border:1px solid rgba(50,112,77,.32); border-radius:8px; box-sizing:border-box; justify-content:flex-start; z-index:1; }
.published-schedule-block[data-duration-minutes="30"] { padding:3px 4px; }
.published-schedule-block[data-duration-minutes="30"] .class-course { font-size:8px; line-height:1.05; }
.published-schedule-block[data-duration-minutes="30"] .class-subject,.published-schedule-block[data-duration-minutes="30"] .class-instructor,.published-schedule-block[data-duration-minutes="30"] .class-program,.published-schedule-block[data-duration-minutes="30"] .class-major,.published-schedule-block[data-duration-minutes="30"] .class-type,.published-schedule-block[data-duration-minutes="30"] .merged-course-details,.published-schedule-block[data-duration-minutes="30"] .schedule-override-notice,.published-schedule-block[data-duration-minutes="30"] .room-swap-details { display:block !important; margin:1px 0 0; font-size:7px; line-height:1.05; }
    .faculty-timetable th { position:sticky; top:0; z-index:1; padding:10px 7px; border-right:1px solid #e7ece5; border-bottom:1px solid #dfe7de; background:#f4f6f1; color:#68796e; font:700 8px/1.2 "DM Sans",sans-serif; letter-spacing:.08em; text-align:center; }
    .faculty-timetable th:first-child,.faculty-timetable td.time-column { background:#f8f9f6; }
    .faculty-timetable td { min-width:100px; height:91px; padding:5px; border-right:1px solid #edf0eb; border-bottom:1px solid #edf0eb; vertical-align:top; }
    .faculty-timetable td.time-column { min-width:72px; width:72px; color:#617269; font:600 8px/1.4 "DM Sans",sans-serif; text-align:center; vertical-align:middle; }
    .faculty-timetable tr:last-child td { border-bottom:0; }
    .faculty-timetable th:last-child,.faculty-timetable td:last-child { border-right:0; }
    .faculty-timetable .noon-break-row td { height:auto; padding:6px; background:#f3f1e8; color:#8d7d56; font:700 8px "DM Sans",sans-serif; letter-spacing:.12em; text-align:center; }
    .empty-schedule-cell { display:block; padding:5px 3px; color:#c0c8c0; font:400 8px "DM Sans",sans-serif; text-align:center; }
    .class-entry { display:flex; min-height:78px; flex-direction:column; gap:3px; padding:7px; overflow:hidden; border:1px solid rgba(44,82,59,.12); border-left:3px solid #60836a; border-radius:7px; color:#34483d; }
    .class-entry.room-swapped-temporary { border-color:#c9a7e8 !important; border-left:4px solid #7e22ce !important; background:linear-gradient(145deg,#f6edff,#fcf9ff) !important; box-shadow:inset 0 0 0 1px rgba(126,34,206,.08),0 3px 9px rgba(126,34,206,.1) !important; }
    .class-entry.room-swapped-temporary .room-swap-badge { border-color:#dec8f2; background:#f3e8ff; color:#6b21a8; }
    .class-course { color:#284b39; font:800 8px/1.25 "Manrope",sans-serif; }
    .class-subject { color:#43574b; font:600 8px/1.35 "DM Sans",sans-serif; }
    .class-type,.class-major,.class-program { color:#738077; font:400 7px/1.3 "DM Sans",sans-serif; }
    .class-instructor { margin-top:auto; color:#57675e; font:600 7px/1.3 "DM Sans",sans-serif; }
    .room-swap-badge,.reservation-badge { display:inline-flex; align-items:center; gap:4px; width:max-content; max-width:100%; padding:4px 5px; border-radius:99px; background:#e7efe6; color:#3b694d; font:700 6px/1.25 "DM Sans",sans-serif; }
    .room-swap-details,.room-swap-original,.reservation-purpose,.reservation-requester,.reservation-expiration,.reservation-course { color:#627168; font:400 6px/1.35 "DM Sans",sans-serif; }
    .shared-reservation-cell .class-entry,.temporary-reservation-entry { border-left-color:#a88a4d; }
    .room-reservation-details-overlay { position:fixed; inset:0; z-index:2000; display:grid; place-items:center; padding:20px; background:rgba(18,37,30,.48); backdrop-filter:blur(3px); }
    .room-reservation-details-dialog { width:min(520px,100%); max-height:min(620px,90vh); overflow:auto; border:1px solid #e2e8e1; border-radius:17px; background:#fff; box-shadow:0 24px 70px rgba(15,36,26,.23); }
    .room-reservation-details-dialog > header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:16px 18px; border-bottom:1px solid #edf0eb; }
    .room-reservation-details-dialog > header span { color:#9a8659; font:700 7px "DM Sans",sans-serif; letter-spacing:.15em; }
    .room-reservation-details-dialog > header h2 { margin:4px 0 0; color:var(--schedule-forest); font:700 14px "Manrope",sans-serif; }
    .room-reservation-details-dialog > header button { width:30px; height:30px; border:1px solid #e5eae3; border-radius:9px; background:#fafbf8; color:#6f7e74; cursor:pointer; }
    .room-reservation-details-list { display:grid; gap:8px; padding:13px 18px 18px; }
    .room-reservation-detail-card { display:grid; grid-template-columns:1fr auto; gap:4px 10px; padding:11px; border:1px solid #e7ece5; border-radius:10px; background:#fbfcfa; }
    .room-reservation-detail-card > div { display:grid; gap:3px; }
    .room-reservation-detail-card strong { color:#3a4e42; font:700 9px "Manrope",sans-serif; }
    .room-reservation-detail-card span,.room-reservation-detail-card p,.room-reservation-detail-card small { margin:0; color:#829087; font:400 8px/1.4 "DM Sans",sans-serif; }
    .room-reservation-detail-card p,.room-reservation-detail-card small { grid-column:1/-1; }
    .shared-reservation-entry { display:grid; align-content:start; gap:4px; width:100%; min-width:0; min-height:82px; padding:8px; overflow:hidden; border-color:#ead9ad; border-left:4px solid #c99736; border-radius:10px; background:linear-gradient(145deg,#fff8e8,#fffdf8); color:#34483a; text-align:left; }
    .shared-reservation-entry:hover,.shared-reservation-entry:focus-visible { border-color:#c99736; background:#fff4d8; box-shadow:0 0 0 2px rgba(201,151,54,.14); }
    .reservation-entry-top { display:flex; align-items:center; justify-content:space-between; gap:5px; min-width:0; }
    .shared-reservation-entry .reservation-badge { min-width:0; max-width:65%; margin:0; padding:4px 6px; overflow:hidden; background:#fff0c7; color:#79551a; font-size:8px; white-space:nowrap; }
    .shared-reservation-entry .reservation-time { flex:0 0 auto; color:#47574b; font-size:9px; font-weight:850; white-space:nowrap; }
    .shared-reservation-entry .reservation-course { margin:0; color:#79551a; font-size:11px; font-weight:900; line-height:1.2; overflow-wrap:anywhere; }
    .shared-reservation-entry .reservation-purpose { display:-webkit-box; margin:0; overflow:hidden; color:#5f6d62; font-size:9px; font-weight:650; line-height:1.25; -webkit-box-orient:vertical; -webkit-line-clamp:2; }
    .shared-reservation-entry .reservation-requester { margin:0; color:#64746a; font-size:9px; line-height:1.2; }
    .shared-reservation-entry .reservation-expiration { margin:0; color:#79551a; font-size:8px; font-weight:850; }
    .room-reservation-detail-card .reservation-detail-top { display:flex; align-items:center; justify-content:space-between; gap:12px; grid-column:1/-1; }
    .room-reservation-detail-card .reservation-detail-code { grid-column:1/-1; color:#79551a; font-size:13px; font-weight:900; }
    .room-reservation-detail-card .reservation-detail-title { grid-column:1/-1; margin:0; color:#435449; font-size:12px; line-height:1.45; }
    .room-reservation-detail-card .reservation-detail-footer { display:flex; grid-column:1/-1; color:#68776d; font-size:11px; }
    @media(max-width:1050px) { .department-list { grid-template-columns:repeat(2,minmax(0,1fr)); } .rooms-container { grid-template-columns:repeat(3,minmax(0,1fr)); } }
    @media(max-width:760px) { .schedule-header { align-items:flex-start; flex-direction:column; gap:14px; } .schedule-header-note { display:none; } .department-list { grid-template-columns:repeat(2,minmax(0,1fr)); } .rooms-container { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:520px) { .portal-shell .main-layout > .content.faculty-schedules-view { padding:20px 14px; } .schedule-journey { gap:6px; padding:11px 9px; } .schedule-step { gap:6px; } .schedule-step > span { flex-basis:25px; width:25px; height:25px; font-size:8px; } .schedule-step strong { font-size:8px; } .schedule-step small { display:none; } .schedule-step-rule { min-width:7px; } .department-list { grid-template-columns:minmax(0,1fr); } .department-card { min-height:72px; } .department-content { padding:13px; } .rooms-container { grid-template-columns:repeat(2,minmax(0,1fr)); padding:0 8px 9px; } .room-card { min-height:88px; } .schedule-panel-header { align-items:flex-start; padding:14px; } .schedule-panel-title { font-size:14px; } .close-schedule { flex:0 0 auto; padding:0 8px; font-size:8px; } #scheduleContent { padding:8px; } .schedule-legend { padding:9px; } }

    /* Readable type scale for the schedule directory and timetable. */
    .schedule-kicker,.selected-department-kicker { font-size:11px; font-weight:700; }
    .schedule-subtitle { color:#526258; font-size:14px; }
    .schedule-header-note strong { font-size:13px; }
    .schedule-header-note small { color:#65736a; font-size:11px; }
    .schedule-step > span { font-size:11px; }
    .schedule-step strong { color:#45574c; font-size:13px; font-weight:700; }
    .schedule-step small { color:#647369; font-size:11px; }
    .faculty-schedules-section-heading { font-size:20px; }
    .schedule-section-heading p { color:#5f7065; font-size:13px; }
    .schedule-count-note { color:#4f6256; font-size:11px; font-weight:700; }
    .department-card { min-height:94px; }
    .department-label { color:#776540; font-size:10px; }
    .department-name { color:#243a2f; font-size:15px; font-weight:800; }
    .department-info { color:#596b60; font-size:12px; }
    .selected-department-title { font-size:20px; }
    .selected-department-subtitle { color:#5c6d62; font-size:13px; }
    .building-button { min-height:66px; }
    .building-name { color:#293e33; font-size:15px; font-weight:700; }
    .building-department { color:#5f7066; font-size:11px; }
    .building-room-count { color:#4f6255; font-size:11px; font-weight:700; }
    .rooms-container { grid-template-columns:repeat(3,minmax(0,1fr)); }
    .room-card { min-height:102px; padding:13px; }
    .room-name { color:#293e33; font-size:15px; font-weight:800; }
    .room-info { color:#596b60; font-size:12px; }
    .room-action { font-size:12px; font-weight:700; }
    .empty-building,.empty-department { color:#596b60; font-size:13px; }
    .department-placeholder h3 { font-size:16px; }
    .department-placeholder p { color:#596b60; font-size:13px; }
    .schedule-panel-title { font-size:20px; }
    .schedule-panel-subtitle { color:#596b60; font-size:12px; }
    .close-schedule { color:#40584a; font-size:12px; font-weight:700; }
    .schedule-legend { color:#56685d; font-size:11px; }
    .schedule-legend-badge { color:#40584a; font-size:10px; font-weight:700; }
    .schedule-loading,.schedule-error { color:#526459; font-size:13px; }
    .schedule-error-title { font-size:15px; }
    .retry-schedule-btn { font-size:12px; font-weight:700; }
    .faculty-timetable { min-width:1080px; }
    .faculty-timetable th { color:#43574b; font-size:11px; font-weight:800; }
    .faculty-timetable td { min-width:112px; height:100px; }
    .faculty-timetable td.time-column { color:#43574b; font-size:11px; font-weight:700; }
    .faculty-timetable .noon-break-row td { color:#665a3e; font-size:11px; }
    .empty-schedule-cell { color:#69786e; font-size:10px; }
    .class-entry { min-height:88px; gap:4px; padding:8px; }
    .class-course { color:#234a35; font-size:11px; font-weight:800; }
    .class-subject { color:#354c3e; font-size:11px; font-weight:700; }
    .class-type,.class-major,.class-program { color:#53645a; font-size:10px; font-weight:500; }
    .class-instructor { color:#42564a; font-size:10px; font-weight:700; }
    .room-swap-badge,.reservation-badge { font-size:9px; font-weight:800; }
    .room-swap-details,.room-swap-original,.reservation-purpose,.reservation-requester,.reservation-expiration,.reservation-course { color:#485b4f; font-size:9px; font-weight:500; }
    .room-reservation-details-dialog > header span { font-size:10px; }
    .room-reservation-details-dialog > header h2 { font-size:17px; }
    .room-reservation-detail-card strong { font-size:13px; }
    .room-reservation-detail-card span,.room-reservation-detail-card p,.room-reservation-detail-card small { color:#526459; font-size:11px; font-weight:500; }
    @media(max-width:760px) { .rooms-container { grid-template-columns:repeat(2,minmax(0,1fr)); } }

        /* Schedule page hero and component accents. */
    .portal-shell .faculty-schedule-page { max-width:1480px; }
    .portal-shell .schedule-header { position:relative; overflow:hidden; align-items:center; min-height:190px; margin:0 0 22px; padding:30px clamp(24px,4vw,43px); border:0; border-radius:24px; background:radial-gradient(circle at 83% 18%,rgba(214,192,139,.2),transparent 23%),linear-gradient(118deg,#173b33 0%,#245543 62%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); }
    .portal-shell .schedule-header::after { position:absolute; top:-135px; right:-55px; width:320px; height:320px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 27px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.03),0 0 0 103px rgba(232,215,169,.025); content:""; pointer-events:none; }
    .portal-shell .schedule-heading-copy,.portal-shell .schedule-header-note { position:relative; z-index:1; }
    .portal-shell .schedule-kicker { color:#e2c987; }
    .portal-shell .schedule-title { max-width:720px; color:#fffdf7; font-size:clamp(31px,3.3vw,44px); }
    .portal-shell .schedule-subtitle { max-width:650px; color:#e1e9df; font-size:15px; }
    .portal-shell .schedule-header-note { border-color:rgba(255,255,255,.2); background:rgba(255,255,255,.09); backdrop-filter:blur(8px); }
    .portal-shell .schedule-header-note-icon { background:#d8c18c; color:#173b33; }
    .portal-shell .schedule-header-note strong { color:#fffdf7; }
    .portal-shell .schedule-header-note small { color:#dce6dc; }
    .portal-shell .schedule-journey { margin-bottom:30px; padding:15px 18px; border-color:#e5e9df; background:rgba(255,255,255,.78); box-shadow:0 8px 22px rgba(31,57,43,.035); }
    .portal-shell .schedule-step.current > span,.portal-shell .schedule-step.complete > span { border-color:#b89b5a; background:#b89b5a; color:#fff; }
    .portal-shell .schedule-section-heading { margin-bottom:15px; }
    .portal-shell .department-card { border-color:#dfe7dd; box-shadow:0 7px 20px rgba(31,57,43,.045); }
    .portal-shell .department-card.active { border-color:#91ad99; background:#f0f5ed; box-shadow:inset 4px 0 #2b624b,0 8px 22px rgba(31,57,43,.06); }
    .portal-shell .department-icon { background:#e6eee4; color:#285940; }
    .portal-shell .schedule-panel { border-color:#d9e3d8; box-shadow:0 16px 38px rgba(31,57,43,.09); }
    .portal-shell .schedule-panel-header { background:linear-gradient(105deg,#f3f6ef,#fff); }
    .portal-shell .selected-department-mark { border-color:#d8e3d6; background:#eaf0e7; color:#285940; }
    .portal-shell .building-button.active { background:#f0f5ed; }
    @media(max-width:760px) {
        .portal-shell .schedule-header { min-height:0; align-items:flex-start; flex-direction:column; gap:14px; padding:26px 24px; }
        .portal-shell .schedule-header-note { display:none; }
    }
    @media(max-width:520px) {
        .portal-shell .schedule-header { margin-bottom:18px; padding:24px 20px; border-radius:19px; }
        .portal-shell .schedule-subtitle { font-size:14px; }
        .portal-shell .schedule-journey { margin-bottom:24px; padding:12px 9px; }
        .portal-shell .schedule-step strong { font-size:12px; }
    }

    .portal-shell.admin-portal .main-layout > .content.faculty-schedules-view { min-width:0; min-height:100vh; padding:124px clamp(20px,3vw,42px) 46px; background:radial-gradient(ellipse at 92% 2%,rgba(214,194,143,.22),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }
    .faculty-schedule-page { font-size:15px; line-height:1.6; }
    .schedule-kicker,.selected-department-kicker { font-size:12px; font-weight:850; }
    .schedule-title { font-size:clamp(34px,3.6vw,47px); font-weight:850; }
    .schedule-subtitle { font-size:16px; font-weight:600; }
    .schedule-header-note strong { font-size:14px; font-weight:850; }
    .schedule-header-note small { font-size:12px; font-weight:650; }
    .schedule-step > span { font-size:12px; font-weight:850; }
    .schedule-step strong { font-size:14px; font-weight:850; }
    .schedule-step small { font-size:12px; font-weight:650; }
    .faculty-schedules-section-heading { font-size:23px; font-weight:850; }
    .schedule-section-heading p { font-size:14px; font-weight:600; }
    .schedule-count-note { font-size:12px; font-weight:800; }
    .department-card { min-height:100px; }
    .department-label { font-size:11px; font-weight:850; }
    .department-name { font-size:16px; font-weight:850; }
    .department-info { font-size:13px; font-weight:650; }
    .selected-department-title { font-size:22px; font-weight:850; }
    .selected-department-subtitle { font-size:14px; font-weight:600; }
    .building-button { min-height:70px; }
    .building-name { font-size:17px; font-weight:850; }
    .building-department,.building-room-count { font-size:12px; font-weight:750; }
    .room-card { min-height:108px; }
    .room-name { font-size:16px; font-weight:850; }
    .room-info { font-size:13px; font-weight:650; }
    .room-action { font-size:13px; font-weight:850; }
    .empty-building,.empty-department { font-size:14px; font-weight:650; }
    .department-placeholder h3 { font-size:19px; font-weight:850; }
    .department-placeholder p { font-size:14px; font-weight:600; }
    .schedule-panel-title { font-size:22px; font-weight:850; }
    .schedule-panel-subtitle { font-size:14px; font-weight:600; }
    .close-schedule { min-height:40px; font-size:13px; font-weight:800; }
    .schedule-legend { font-size:13px; font-weight:600; }
    .schedule-legend-badge { font-size:12px; font-weight:850; }
    .schedule-loading,.schedule-error { font-size:14px; font-weight:650; }
    .schedule-error-title { font-size:18px; font-weight:850; }
    .retry-schedule-btn { min-height:39px; font-size:13px; font-weight:850; }
    .faculty-timetable th { font-size:12px; font-weight:850; }
    .faculty-timetable td.time-column,.faculty-timetable .noon-break-row td { font-size:12px; font-weight:800; }
    .faculty-timetable td { min-width:126px; height:108px; }
    .empty-schedule-cell { font-size:12px; font-weight:600; }
    .class-entry { min-height:96px; gap:5px; padding:9px; }
    .class-course { font-size:13px; font-weight:850; }
    .class-subject { font-size:12px; font-weight:750; }
    .class-type,.class-major,.class-program { font-size:11px; font-weight:650; }
    .class-instructor { font-size:11px; font-weight:800; }
    .room-swap-badge,.reservation-badge { font-size:10px; font-weight:850; }
    .room-swap-details,.room-swap-original,.reservation-purpose,.reservation-requester,.reservation-expiration,.reservation-course { font-size:10px; font-weight:650; }
    .room-reservation-details-dialog > header span { font-size:11px; }
    .room-reservation-details-dialog > header h2 { font-size:20px; font-weight:850; }
    .room-reservation-detail-card strong { font-size:14px; font-weight:850; }
    .room-reservation-detail-card span,.room-reservation-detail-card p,.room-reservation-detail-card small { font-size:12px; font-weight:650; }
    @media(max-width:760px) {
        .portal-shell.admin-portal .main-layout > .content.faculty-schedules-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .portal-shell .schedule-header { padding:26px 24px; }
        .portal-shell .schedule-title { font-size:clamp(32px,6vw,40px); }
        .schedule-step strong { font-size:13px; }
        .schedule-step small { font-size:11px; }
    }
    @media(max-width:520px) {
        .portal-shell.admin-portal .main-layout > .content.faculty-schedules-view { padding:20px 14px 30px; }
        .portal-shell .schedule-subtitle { font-size:14px; }
        .schedule-step strong { font-size:12px; }
        .schedule-panel-title { font-size:19px; }
        .close-schedule { font-size:12px; }
        .faculty-timetable td { min-width:120px; }
    }

    .published-schedule-block { display:flex !important; align-items:stretch !important; justify-content:flex-start !important; align-content:flex-start; gap:3px; padding:7px; overflow:hidden; text-align:left !important; }
    .published-schedule-block .class-course { width:100%; padding-right:62px; color:#245940; font-size:13px; line-height:1.2; text-align:left !important; }
    .published-schedule-block .class-time { position:absolute; top:5px; right:6px; margin:0; padding:2px 4px; border:1px solid #dce6dc; border-radius:5px; background:#fff; color:#344b5d; font-size:10px; font-weight:850; line-height:1.15; white-space:nowrap; }
    .published-schedule-block .class-subject { width:100%; color:#294437; font-size:12px; line-height:1.25; text-align:left !important; }
    .published-schedule-block .class-instructor { width:100%; margin-top:0; color:#536a5b; font-size:11px; line-height:1.2; text-align:left !important; }
    .published-schedule-block .class-program,.published-schedule-block .class-major,.published-schedule-block .class-type,
    .published-schedule-block .merged-course-details,.published-schedule-block .schedule-override-notice,.published-schedule-block .room-swap-details { width:100%; color:#536a5b; font-size:10px; line-height:1.2; text-align:left !important; }
    .published-schedule-block[data-duration-minutes="30"] { gap:2px; padding:4px 5px; }
    .published-schedule-block[data-duration-minutes="30"] .class-course { font-size:10px; line-height:1.15; }
    .published-schedule-block[data-duration-minutes="30"] .class-subject,
    .published-schedule-block[data-duration-minutes="30"] .class-instructor { display:block !important; margin:1px 0 0; font-size:9px; line-height:1.15; }
    .published-schedule-block[data-duration-minutes="30"] .class-program,
    .published-schedule-block[data-duration-minutes="30"] .class-major,
    .published-schedule-block[data-duration-minutes="30"] .class-type,
    .published-schedule-block[data-duration-minutes="30"] .merged-course-details,
    .published-schedule-block[data-duration-minutes="30"] .room-swap-details { display:none !important; }
    .published-schedule-block[data-duration-minutes="30"] .schedule-override-notice { display:grid !important; gap:1px; margin-top:2px; padding:2px 3px; font-size:7px; line-height:1.05; }
    .published-schedule-block[data-duration-minutes="30"] .schedule-override-notice strong,
    .published-schedule-block[data-duration-minutes="30"] .schedule-override-notice span { font-size:7px; line-height:1.05; }

    /* Keep schedule details readable as a block grows or shrinks. */
    .published-schedule-block { container-type:inline-size; overflow:auto; scrollbar-width:thin; scrollbar-color:#b8c8ba transparent; }
    .published-schedule-block .class-course { font-size:clamp(9px,3.2cqw,13px); overflow-wrap:anywhere; }
    .published-schedule-block .class-subject { font-size:clamp(8px,2.9cqw,12px); line-height:1.2; overflow-wrap:anywhere; }
    .published-schedule-block .class-instructor,.published-schedule-block .class-program,
    .published-schedule-block .class-major,.published-schedule-block .class-type,
    .published-schedule-block .merged-course-details,.published-schedule-block .room-swap-details { font-size:clamp(7px,2.4cqw,10px); line-height:1.15; overflow-wrap:anywhere; }
    .published-schedule-block[data-duration-minutes="30"] .class-program,
    .published-schedule-block[data-duration-minutes="30"] .class-major,
    .published-schedule-block[data-duration-minutes="30"] .class-type,
    .published-schedule-block[data-duration-minutes="30"] .merged-course-details,
    .published-schedule-block[data-duration-minutes="30"] .room-swap-details { display:block !important; font-size:7px; line-height:1.05; }

</style>

<div class="wrapper">

    

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content faculty-schedules-view">

           
            <div class="faculty-schedule-page">


                <!-- =====================================================
                     HEADER
                ====================================================== -->

                <div class="schedule-header">
                    <div class="schedule-heading-copy">
                        <span class="schedule-kicker">CAMPUS DIRECTORY</span>
                        <h1 class="schedule-title">Find a classroom schedule</h1>
                        <p class="schedule-subtitle">Choose a college, building, and room to see the weekly timetable and current room activity.</p>
                    </div>
                    <div class="schedule-header-note">
                        <span class="schedule-header-note-icon"><i class="fa-regular fa-calendar-check" aria-hidden="true"></i></span>
                        <span><strong>Campus schedules</strong><small>One view for every college</small></span>
                    </div>
                </div>

                <section class="schedule-journey" aria-label="Three steps to view a schedule">
                    <div class="schedule-step current" id="scheduleStepDepartment" aria-current="step">
                        <span>01</span><div><strong>College</strong><small>Choose an academic unit</small></div>
                    </div>
                    <span class="schedule-step-rule" aria-hidden="true"></span>
                    <div class="schedule-step" id="scheduleStepBuilding">
                        <span>02</span><div><strong>Building</strong><small>Open a building</small></div>
                    </div>
                    <span class="schedule-step-rule" aria-hidden="true"></span>
                    <div class="schedule-step" id="scheduleStepRoom">
                        <span>03</span><div><strong>Room</strong><small>View the timetable</small></div>
                    </div>
                </section>

                <div class="schedule-section-heading">
                    <div><h2 class="faculty-schedules-section-heading">Browse colleges</h2><p>Start with the college that owns the classroom.</p></div>
                    <span class="schedule-count-note">{{ $departments->count() }} {{ $departments->count() === 1 ? 'department' : 'departments' }}</span>
                </div>


                <!-- =====================================================
                     DEPARTMENT CARDS
                ====================================================== -->

                <div class="department-list">

                    @forelse($departments as $department)

                        <button type="button" class="department-card" aria-label="Open {{ $department['name'] }} department" aria-pressed="false"
                            data-department-id="{{ $department['id'] }}" onclick="showDepartment({{ $department['id'] }})">
                            <span class="department-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                            <span class="department-copy">
                                <span class="department-label">COLLEGE</span>
                                <strong class="department-name">{{ $department['name'] }}</strong>
                                <span class="department-info">{{ $department['buildings']->count() }} {{ $department['buildings']->count() === 1 ? 'building' : 'buildings' }}</span>
                            </span>
                            <span class="department-arrow" aria-hidden="true"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                        </button>

                    @empty

                        <div class="empty-building">

                            No colleges are available.

                        </div>

                    @endforelse

                </div>


                <!-- =====================================================
                     DEPARTMENT CONTENT
                ====================================================== -->

                @forelse($departments as $department)

                    <div
                        id="department-{{ $department['id'] }}"
                        class="department-content"
                        aria-hidden="true"
                    >


                        <!-- SELECTED DEPARTMENT HEADER -->

                        <div class="selected-department-header">
                            <div>
                                <span class="selected-department-kicker">SELECTED COLLEGE</span>
                                <h2 class="selected-department-title">{{ $department['name'] }}</h2>
                                <p class="selected-department-subtitle">Open a building to choose one of its classrooms.</p>
                            </div>
                            <span class="selected-department-mark"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                        </div>


                        <!-- =================================================
                             BUILDINGS
                        ================================================== -->

                        <div class="building-list">

                            @forelse($department['buildings'] as $building)

                                <div class="building-card">


                                    <!-- BUILDING HEADER -->

                                    <button type="button" class="building-button" aria-expanded="false" onclick="toggleBuilding(this)">
                                        <span class="building-left">
                                            <span class="building-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                                            <span class="building-copy">
                                                <strong class="building-name">{{ $building['name'] }}</strong>
                                                <small class="building-department">{{ $department['name'] }}</small>
                                            </span>
                                        </span>
                                        <span class="building-room-count">{{ $building['rooms']->count() }} {{ $building['rooms']->count() === 1 ? 'room' : 'rooms' }}</span>
                                        <span class="building-arrow" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                                    </button>


                                    <!-- =================================================
                                         ROOMS
                                    ================================================== -->

                                    <div class="rooms-container" aria-hidden="true">

                                        @forelse(
                                            $building['rooms']
                                            as $room
                                        )

                                            <button type="button" class="room-card" aria-label="View schedule for {{ $room->room_name }}"
                                                data-room-id="{{ $room->id }}" data-room-name="{{ $room->room_name }}" data-building-name="{{ $building['name'] }}"
                                                onclick="loadRoomScheduleFromCard(this)">


                                                <span class="room-top"><strong class="room-name">{{ $room->room_name }}</strong><span class="room-arrow" aria-hidden="true"><i class="fa-solid fa-arrow-right"></i></span></span>
                                                <span class="room-info">View weekly timetable</span>
                                                <span class="room-action">Open schedule</span>
                                            </button>

                                        @empty

                                            <div class="empty-building">

                                                No rooms available
                                                in this building.

                                            </div>

                                        @endforelse

                                    </div>

                                </div>

                            @empty

                                <div class="empty-department">

                                    No buildings are available
                                    for this college.

                                </div>

                            @endforelse

                        </div>

                    </div>

                @empty

                    <div class="department-placeholder">

                        <div class="department-placeholder-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></div>

                        <h3>
                            No Colleges Available
                        </h3>

                        <p>
                            There are currently no colleges
                            with classroom information.
                        </p>

                    </div>

                @endforelse


                <!-- =====================================================
                     DEFAULT MESSAGE
                ====================================================== -->

                @if($departments->count() > 0)

                    <div
                        id="departmentPlaceholder"
                        class="department-placeholder"
                    >

                        <div class="department-placeholder-icon">
                            🏫
                        </div>

                        <h3>
                            Select a College
                        </h3>

                        <p>
                            Choose a college above to see its buildings and classrooms.
                        </p>

                    </div>

                @endif


                <!-- =====================================================
                     SCHEDULE PANEL
                ====================================================== -->

                <div
                    id="schedulePanel"
                    class="schedule-panel"
                    role="region"
                    aria-labelledby="scheduleRoomName"
                    aria-live="polite"
                    data-schedule-sheet
                >


                    <div class="schedule-panel-header">

                        <div>

                            <h2
                                id="scheduleRoomName"
                                class="schedule-panel-title"
                            >
                                Classroom Schedule
                            </h2>


                            <div
                                id="scheduleRoomBuilding"
                                class="schedule-panel-subtitle"
                            >
                                View the current schedule for this classroom.
                            </div>

                        </div>


                        <div class="schedule-panel-actions">
                            @include('components.schedule-sheet-controls')
                            <button
                                type="button"
                                class="close-schedule"
                                onclick="closeSchedule()"
                            >
                                <i class="fa-solid fa-xmark" aria-hidden="true"></i> Close
                            </button>
                        </div>

                    </div>

                    <div class="schedule-legend">
                        <span class="schedule-legend-badge"><i class="fa-solid fa-right-left" aria-hidden="true"></i> Room swap</span>
                        <span>Class is temporarily assigned to another room.</span>
                        <span class="schedule-legend-badge reservation-legend-badge"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Reservation</span>
                        <span>Room is reserved for a limited time.</span>
                    </div>


                    <div id="scheduleContent">

                        <div class="schedule-loading">

                            Select a room to view its schedule.

                        </div>

                    </div>

                </div>

            </div>


          <script>

let reservationExpiryTimer = null;
let roomSwapTransitionTimer = null;

function updateScheduleProgress() {
    const activeDepartment = document.querySelector('.department-content.open');
    const activeRoom = activeDepartment?.querySelector('.room-card.active');
    const activeBuilding = activeDepartment?.querySelector('.building-button.active');
    const currentStep = activeRoom ? 2 : activeBuilding ? 2 : activeDepartment ? 1 : 0;
    const stepIds = ['scheduleStepDepartment', 'scheduleStepBuilding', 'scheduleStepRoom'];

    stepIds.forEach(function(id, index) {
        const step = document.getElementById(id);
        if (!step) return;
        const active = index === currentStep;
        step.classList.toggle('current', active);
        step.classList.toggle('complete', index < currentStep);
        if (active) step.setAttribute('aria-current', 'step');
        else step.removeAttribute('aria-current');
    });
}


/* =========================================================
   SHOW DEPARTMENT
========================================================= */

function showDepartment(departmentId)
{
    document
        .querySelectorAll('.department-content')
        .forEach(function(section) {

            section.classList.remove('open');
            section.setAttribute('aria-hidden', 'true');

        });

    document
        .querySelectorAll('.department-card')
        .forEach(function(card) {

            card.classList.remove('active');
            card.setAttribute('aria-pressed', 'false');

        });

    const selectedCard =
        document.querySelector(
            '.department-card[data-department-id="' +
            departmentId +
            '"]'
        );

    if (selectedCard) {

        selectedCard.classList.add('active');
        selectedCard.setAttribute('aria-pressed', 'true');

    }

    const selectedDepartment =
        document.getElementById(
            'department-' +
            departmentId
        );

    if (selectedDepartment) {

        selectedDepartment.classList.add('open');
        selectedDepartment.setAttribute('aria-hidden', 'false');

    }

    const placeholder =
        document.getElementById(
            'departmentPlaceholder'
        );

    if (placeholder) {

        placeholder.style.display =
            'none';

    }

    closeSchedule();
    updateScheduleProgress();
    if (selectedDepartment) {
        window.setTimeout(function() {
            selectedDepartment.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 40);
    }
}


/* =========================================================
   BUILDING TOGGLE
========================================================= */

function toggleBuilding(button)
{
    const rooms =
        button.nextElementSibling;

    const isOpen =
        rooms.classList.contains('open');

    const department = button.closest('.department-content');
    department?.querySelectorAll('.building-button').forEach(function(otherButton) {
        if (otherButton === button) return;
        otherButton.classList.remove('active');
        otherButton.setAttribute('aria-expanded', 'false');
        otherButton.nextElementSibling?.classList.remove('open');
        otherButton.nextElementSibling?.setAttribute('aria-hidden', 'true');
    });

    if (isOpen) {

        rooms.classList.remove('open');
        rooms.setAttribute('aria-hidden', 'true');

        button.classList.remove('active');
        button.setAttribute('aria-expanded', 'false');
        updateScheduleProgress();

        return;

    }

    rooms.classList.add('open');
    rooms.setAttribute('aria-hidden', 'false');

    button.classList.add('active');
    button.setAttribute('aria-expanded', 'true');
    closeSchedule();
    updateScheduleProgress();
}

function loadRoomScheduleFromCard(roomElement) {
    if (!roomElement) return;
    loadRoomSchedule(
        Number(roomElement.dataset.roomId),
        roomElement,
        roomElement.dataset.roomName || 'Classroom',
        roomElement.dataset.buildingName || 'Building'
    );
}


/* =========================================================
   LOAD ROOM SCHEDULE
========================================================= */

function loadRoomSchedule(
    roomId,
    roomElement,
    roomName,
    buildingName
) {

    const publishedSheetUrl = @json(route('schedules.published.room', ['room' => '__ROOM__']));
    window.location.href = publishedSheetUrl.replace('__ROOM__', encodeURIComponent(roomId));
    return;

    if (roomSwapTransitionTimer) {
        clearTimeout(roomSwapTransitionTimer);
        roomSwapTransitionTimer = null;
    }

    document
        .querySelectorAll('.room-card')
        .forEach(function(room) {

            room.classList.remove('active');

        });

    if (roomElement) {

        roomElement.classList.add('active');

    }
    updateScheduleProgress();

    const schedulePanel =
        document.getElementById(
            'schedulePanel'
        );

    const scheduleContent =
        document.getElementById(
            'scheduleContent'
        );

    const roomTitle =
        document.getElementById(
            'scheduleRoomName'
        );

    const roomBuilding =
        document.getElementById(
            'scheduleRoomBuilding'
        );

    if (
        !schedulePanel ||
        !scheduleContent
    ) {

        console.error(
            'Schedule panel elements not found.'
        );

        return;

    }

    schedulePanel.classList.add('open');

    schedulePanel.style.display =
        'block';

    if (roomTitle) {

        roomTitle.textContent =
            roomName;

    }

    if (roomBuilding) {

        roomBuilding.textContent =
            buildingName +
            ' • Administrator Schedule';

    }

    scheduleContent.innerHTML = `

        <div class="schedule-loading">
            <div class="loading-spinner"></div>
            <div class="loading-text">
                Loading classroom schedule...
            </div>
        </div>

    `;

    fetch(
        "{{ url('/admin/view-schedule') }}/" +
        roomId,
        {
            method: 'GET',

            headers: {

                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'

            }
        }
    )

    .then(
        async function(response)
        {

            let data = null;

            try {

                data =
                    await response.json();

            }
            catch(error) {

                throw new Error(
                    'The server returned an invalid response.'
                );

            }

            console.log(
                'ROOM SCHEDULE RESPONSE:',
                data
            );

            if (!response.ok) {

                throw new Error(
                    data.error ||
                    data.message ||
                    'Unable to load room schedule.'
                );

            }

            if (!data.success) {

                throw new Error(
                    data.error ||
                    data.message ||
                    'Schedule data is unavailable.'
                );

            }

            return data;

        }
    )

    .then(
        function(data)
        {

            if (roomTitle) {

                roomTitle.textContent =
                    data.room.room_name;

            }

            if (roomBuilding) {

                roomBuilding.textContent =
                    data.room.building +
                    ' • ' +
                    data.room.department;

            }

            /* -------------------------------------------------
               RENDER TIMETABLE
            ------------------------------------------------- */

            renderSchedule(
                data.schedules || [],
                data.reservations || [],
                data.room_time_slots || data.time_slots || [],
                data.manual_merges || []
            );

            renderRoomOverrides(data.schedules || [], data.room_overrides || []);

            scheduleReservationExpiry(
                data.reservations || [],
                roomId,
                roomName,
                buildingName
            );

            scheduleRoomSwapTransition(
                data.schedules || [],
                roomId,
                roomName,
                buildingName
            );

            setTimeout(
                function()
                {

                    schedulePanel.scrollIntoView({

                        behavior:
                            'smooth',

                        block:
                            'start'

                    });

                },
                100
            );

        }
    )

    .catch(
        function(error)
        {

            console.error(
                'Schedule loading error:',
                error
            );

            scheduleContent.innerHTML = `

                <div class="schedule-error">

                    <div class="schedule-error-title">
                        Unable to load schedule
                    </div>

                    <div class="schedule-error-message">
                        ${escapeHtml(
                            error.message ||
                            'Please try again.'
                        )}
                    </div>

                    <button
                        type="button"
                        class="retry-schedule-btn"
                        onclick="
                            loadRoomSchedule(
                                ${roomId},
                                document.querySelector(
                                    '[data-room-id="${roomId}"]'
                                ),
                                '${String(roomName).replace(/'/g, "\\'")}',
                                '${String(buildingName).replace(/'/g, "\\'")}'
                            )
                        "
                    >
                        Try Again
                    </button>

                </div>

            `;

        }
    );

}


/* =========================================================
   RESERVATION EXPIRY
========================================================= */

function scheduleReservationExpiry(
    reservations,
    roomId,
    roomName,
    buildingName
)
{
    reservations =
        (reservations || [])
            .filter(function(reservation) {

                return String(
                    reservation.status || ''
                ).toLowerCase() === 'reserved';

            });

    if (reservationExpiryTimer) {

        clearTimeout(
            reservationExpiryTimer
        );

        reservationExpiryTimer = null;

    }

    const now =
        new Date();

    const expirationTimes =
        (reservations || [])
            .map(function(reservation) {

                if (!reservation.end_time) {

                    return null;

                }

                const endParts =
                    String(
                        reservation.end_time
                    ).split(':');

                if (endParts.length < 2) {

                    return null;

                }

                const reservationDate = String(reservation.date || '');
                if (!reservationDate) return null;
                const expiration = new Date(
                    reservationDate + 'T' +
                    String(endParts[0]).padStart(2, '0') + ':' +
                    String(endParts[1]).padStart(2, '0') + ':' +
                    String(endParts[2] || 0).padStart(2, '0')
                );

                return expiration;

            })
            .filter(function(date) {

                return date &&
                       date > now;

            })
            .sort(function(a, b) {

                return a - b;

            });

    if (!expirationTimes.length) {

        return;

    }

    const millisecondsUntilExpiry =
        expirationTimes[0].getTime() -
        now.getTime();

    reservationExpiryTimer =
        setTimeout(
            function() {

                const activeRoom =
                    document.querySelector(
                        '.room-card.active'
                    );

                loadRoomSchedule(
                    roomId,
                    activeRoom,
                    roomName,
                    buildingName
                );

            },
            Math.min(millisecondsUntilExpiry + 1000, 2147480000)
        );
}

function scheduleRoomSwapTransition(schedules, roomId, roomName, buildingName)
{
    if (roomSwapTransitionTimer) {
        clearTimeout(roomSwapTransitionTimer);
        roomSwapTransitionTimer = null;
    }

    const now = Date.now();
    const transitionTimes = (schedules || [])
        .map(function(schedule) {
            const swap = schedule.room_swap;
            if (!swap) return [];
            return [swap.start_at, swap.end_at]
                .map(value => value ? new Date(value).getTime() : NaN)
                .filter(value => Number.isFinite(value) && value > now);
        })
        .flat()
        .sort((a, b) => a - b);

    if (!transitionTimes.length) return;

    const delay = Math.min(transitionTimes[0] - now + 250, 2147480000);
    roomSwapTransitionTimer = window.setTimeout(function() {
        const activeRoom = document.querySelector('.room-card.active');
        loadRoomSchedule(roomId, activeRoom, roomName, buildingName);
    }, Math.max(delay, 250));
}


/* =========================================================
   SCHEDULE MERGE SIGNATURE
========================================================= */

/*
|--------------------------------------------------------------------------
| This checks whether two schedules are really the same.
|--------------------------------------------------------------------------
|
| If all these values are identical, the cells can be merged.
|
*/

function facultyScheduleMergeSignature(schedule)
{
    if (!schedule) {

        return '';

    }

    const sharedPrograms = Array.isArray(schedule.merged_programs)
        ? schedule.merged_programs.map(value => String(value ?? '').trim().toLowerCase()).filter(Boolean).sort().join('|')
        : '';
    const sharedCourses = Array.isArray(schedule.merged_courses)
        ? schedule.merged_courses.map(function(course) {
            return [course.course_code, course.course_title, course.program, course.major, course.share_type]
                .map(value => String(value ?? '').trim().toLowerCase()).join('::');
        }).sort().join('|')
        : '';

    return [
        schedule.course_code,
        schedule.subject,
        schedule.year_level,
        schedule.major,
        schedule.program,
        schedule.instructor,
        schedule.faculty_id,
        schedule.faculty_subject_id,
        schedule.department_id,
        schedule.room_id,
        schedule.color,
        schedule.subject_type,
        schedule.time,
        schedule.section,
        schedule.semester,
        schedule.academic_year,
        sharedPrograms,
        sharedCourses
    ]
    .map(function(value) {

        return String(
            value ?? ''
        )
        .trim()
        .toLowerCase();

    })
    .join('||');
}


/* =========================================================
   BUILD MERGED SCHEDULE GROUP
========================================================= */

/*
|--------------------------------------------------------------------------
| Example:
|
| MON = same
| WED = same
| FRI = same
|
| Result:
|
| <td colspan="3">
|
|--------------------------------------------------------------------------
|
| If only MON + WED are the same:
|
| <td colspan="2">
|
|--------------------------------------------------------------------------
|
| Reservations and empty cells are NOT merged.
|--------------------------------------------------------------------------
*/

function buildFacultyScheduleGroup(items)
{
    let html = '';

    let index = 0;

    while (
        index < items.length
    ) {

        const current =
            items[index];

        const currentSchedule =
            current.schedule;

        const currentReservation =
            current.reservation;

        /*
        |--------------------------------------------------------------------------
        | Reservation
        |--------------------------------------------------------------------------
        */

        if (
            currentReservation &&
            currentReservation.length > 0
        ) {

            html +=
                buildScheduleCell(
                    currentSchedule,
                    currentReservation
                );

            index++;

            continue;

        }

        /*
        |--------------------------------------------------------------------------
        | Empty cell
        |--------------------------------------------------------------------------
        */

        if (!currentSchedule) {

            html +=
                buildScheduleCell(
                    null,
                    currentReservation
                );

            index++;

            continue;

        }

        /*
        |--------------------------------------------------------------------------
        | Check if schedule is occupied
        |--------------------------------------------------------------------------
        */

        const currentOccupied =
            currentSchedule &&
            (
                String(
                    currentSchedule.course_code || ''
                ).trim() !== '' ||

                String(
                    currentSchedule.subject || ''
                ).trim() !== '' ||

                String(
                    currentSchedule.instructor || ''
                ).trim() !== ''
            );

        if (!currentOccupied) {

            html +=
                buildScheduleCell(
                    currentSchedule,
                    currentReservation
                );

            index++;

            continue;

        }

        /*
        |--------------------------------------------------------------------------
        | Current signature
        |--------------------------------------------------------------------------
        */

        const signature =
            facultyScheduleMergeSignature(
                currentSchedule
            );

        /*
        |--------------------------------------------------------------------------
        | Find matching schedules
        |--------------------------------------------------------------------------
        */

        let end =
            index + 1;

        while (
            end < items.length
        ) {

            const next =
                items[end];

            /*
            |--------------------------------------------------------------------------
            | Do not merge over reservations
            |--------------------------------------------------------------------------
            */

            if (
                next.reservation &&
                next.reservation.length > 0
            ) {

                break;

            }

            /*
            |--------------------------------------------------------------------------
            | Do not merge empty cells
            |--------------------------------------------------------------------------
            */

            if (!next.schedule) {

                break;

            }

            const nextOccupied =
                next.schedule &&
                (
                    String(
                        next.schedule.course_code || ''
                    ).trim() !== '' ||

                    String(
                        next.schedule.subject || ''
                    ).trim() !== '' ||

                    String(
                        next.schedule.instructor || ''
                    ).trim() !== ''
                );

            if (!nextOccupied) {

                break;

            }

            /*
            |--------------------------------------------------------------------------
            | Compare schedules
            |--------------------------------------------------------------------------
            */

            const nextSignature =
                facultyScheduleMergeSignature(
                    next.schedule
                );

            if (
                nextSignature !==
                signature
            ) {

                break;

            }

            end++;

        }

        /*
        |--------------------------------------------------------------------------
        | Number of merged columns
        |--------------------------------------------------------------------------
        */

        const colspan =
            end - index;

        /*
        |--------------------------------------------------------------------------
        | Build the normal schedule cell
        |--------------------------------------------------------------------------
        */

        let cellHtml =
            buildScheduleCell(
                currentSchedule,
                null
            );

        /*
        |--------------------------------------------------------------------------
        | Apply COLSPAN
        |--------------------------------------------------------------------------
        */

        if (
            colspan > 1
        ) {

            cellHtml =
                cellHtml.replace(
                    '<td>',
                    '<td colspan="' +
                    colspan +
                    '" class="merged-schedule-cell">'
                );

        }

        html +=
            cellHtml;

        /*
        |--------------------------------------------------------------------------
        | Skip cells already merged
        |--------------------------------------------------------------------------
        */

        index =
            end;

    }

    return html;
}


/* =========================================================
   RENDER SCHEDULE
========================================================= */

function displayProgramCode(program)
{
    return String(program || '')
        .split(/\s*\/\s*/)
        .map(function(value) {
            const label = value.trim();
            const match = label.match(/\(([^()]*)\)\s*$/);
            return match ? match[1].trim() : label;
        })
        .filter(Boolean)
        .join(' / ');
}

function renderRoomOverrides(schedules, roomOverrides) {
    const content = document.getElementById('scheduleContent');
    const entriesById = new Map();
    (Array.isArray(roomOverrides) ? roomOverrides : []).forEach(function(item) {
        entriesById.set(String(item.id), item);
    });
    (Array.isArray(schedules) ? schedules : []).forEach(function(schedule) {
        (Array.isArray(schedule.schedule_overrides) ? schedule.schedule_overrides : []).forEach(function(item) {
            entriesById.set(String(item.id), Object.assign({}, item, {
                room_id: item.room_id || schedule.room_id,
                course_code: schedule.course_code || '',
            }));
        });
    });
    const entries = Array.from(entriesById.values());
    if (!content || !entries.length) return;

    const panel = document.createElement('section');
    panel.className = 'room-override-panel';
    panel.setAttribute('aria-label', 'Room schedule overrides');
    const heading = document.createElement('h3');
    heading.textContent = 'Administrator overrides';
    panel.appendChild(heading);
    const hint = document.createElement('p');
    hint.textContent = 'Select an override to highlight classes only in this room, on its day, and during its time range. Select it again to clear.';
    panel.appendChild(hint);

    entries.forEach(function (item) {
        const card = document.createElement('button');
        card.type = 'button';
        card.className = 'room-override-entry';
        card.setAttribute('aria-pressed', 'false');
        card.dataset.overrideId = item.id || '';
        card.dataset.overrideDay = String(item.day || '').toUpperCase().slice(0, 3);
        card.dataset.overrideRoomId = item.room_id || '';
        card.dataset.overrideStart = item.start_time || '';
        card.dataset.overrideEnd = item.end_time || '';
        const date = item.date
            ? new Date(item.date + 'T12:00:00').toLocaleDateString(undefined, {year:'numeric', month:'long', day:'numeric'})
            : 'Date unavailable';
        const formatTime = function (value) {
            if (!value) return '';
            const parsed = new Date('2000-01-01T' + value);
            return Number.isNaN(parsed.getTime()) ? value : parsed.toLocaleTimeString(undefined, {hour:'numeric', minute:'2-digit'});
        };
        const range = [formatTime(item.start_time), formatTime(item.end_time)].filter(Boolean).join('–');
        const title = document.createElement('strong');
        title.textContent = 'Admin override · ' + date + (range ? ' · ' + range : '');
        const detail = document.createElement('span');
        detail.textContent = 'Room occupant: ' + (item.occupied_by || 'Students and Faculties');
        card.append(title, detail);
        const purpose = document.createElement('span');
        purpose.textContent = 'Purpose: ' + (item.reason || 'Schedule override');
        card.appendChild(purpose);
        if (item.details) {
            const notes = document.createElement('span');
            notes.textContent = item.details;
            card.appendChild(notes);
        }
        panel.appendChild(card);
    });

    const timetable = content.querySelector('.timetable-wrapper');
    if (timetable) content.insertBefore(panel, timetable);
    else content.prepend(panel);

    const wrapper = content.querySelector('.timetable-wrapper');
    panel.querySelectorAll('.room-override-entry').forEach(function(button) {
        button.addEventListener('click', function() {
            const wasSelected = button.getAttribute('aria-pressed') === 'true';
            panel.querySelectorAll('.room-override-entry').forEach(function(entry) { entry.setAttribute('aria-pressed', 'false'); });
            if (wasSelected) {
                if (wrapper) wrapper._selectedOverride = null;
            } else {
                button.setAttribute('aria-pressed', 'true');
                if (wrapper) wrapper._selectedOverride = {
                    day: button.dataset.overrideDay,
                    roomId: String(button.dataset.overrideRoomId || ''),
                    start: publishedScheduleRange(button.dataset.overrideStart + '-' + button.dataset.overrideEnd)?.start,
                    end: publishedScheduleRange(button.dataset.overrideStart + '-' + button.dataset.overrideEnd)?.end,
                };
            }
            renderPublishedScheduleBlocks(content);
        });
    });
}

function renderSchedule(
    schedules,
    reservations = [],
    timeSlots = [],
    manualMerges = []
) {

    /*
    |--------------------------------------------------------------------------
    | Active reservations only
    |--------------------------------------------------------------------------
    */

    reservations =
        (reservations || [])
            .filter(function(reservation) {

                return String(
                    reservation.status || ''
                ).toLowerCase() === 'reserved';

            });

    const content =
        document.getElementById(
            'scheduleContent'
        );

    /*
    |--------------------------------------------------------------------------
    | DEFAULT TIMES
    |--------------------------------------------------------------------------
    */

    const defaultTimes = [
        '07:00-08:00',
        '08:00-09:00',
        '09:00-10:00',
        '10:00-11:00',
        '11:00-12:00',
        '13:00-14:00',
        '14:00-15:00',
        '15:00-16:00',
        '16:00-17:00',
        '17:00-18:00',
        '18:00-19:00'
    ];

    /*
    |--------------------------------------------------------------------------
    | CREATE STANDARD TIMETABLE ROWS
    |--------------------------------------------------------------------------
    */

    const rows = {};

    for (
        let position = 0;
        position < defaultTimes.length;
        position++
    ) {

        rows[position] = {

            leftTime:
                defaultTimes[position],

            rightTime:
                defaultTimes[position],

            saturdayTime:
                defaultTimes[position],

            MON: null,

            WED: null,

            FRI: null,

            TUE: null,

            THU: null,

            SAT: null,

            MON_RESERVATION: [],
            WED_RESERVATION: [],
            FRI_RESERVATION: [],
            TUE_RESERVATION: [],
            THU_RESERVATION: [],
            SAT_RESERVATION: []

        };

    }

    (timeSlots || []).forEach(function(slot) {
        const position = publishedScheduleRowPosition(
            slot.time || [slot.start_time, slot.end_time].filter(Boolean).join('-'),
            timeSlots,
            slot.day,
            defaultTimes
        );
        if (position === null || position < 0 || position >= defaultTimes.length || !slot.time) return;

        const day = String(slot.day || '').trim().toUpperCase().slice(0, 3);
        if (['MON', 'WED', 'FRI'].includes(day)) rows[position].leftTime = slot.time;
        else if (['TUE', 'THU'].includes(day)) rows[position].rightTime = slot.time;
        else if (day === 'SAT') rows[position].saturdayTime = slot.time;
    });

    /*
    |--------------------------------------------------------------------------
    | ADD DATABASE SCHEDULES
    |--------------------------------------------------------------------------
    */

    (
        schedules || []
    ).forEach(
        function(schedule)
        {

            let position =
                publishedScheduleRowPosition(schedule.time, timeSlots, schedule.day, defaultTimes);

            if (position === null) {
                position = Number(schedule.row_position);
            }

            /*
            |--------------------------------------------------------------------------
            | FALLBACK FOR OLD DATA
            |--------------------------------------------------------------------------
            */

            if (
                Number.isNaN(position) ||
                position < 0 ||
                position >= defaultTimes.length
            ) {

                const savedTime =
                    String(
                        schedule.time ||
                        ''
                    )
                    .trim();

                position =
                    defaultTimes.indexOf(
                        savedTime
                    );

            }

            /*
            |--------------------------------------------------------------------------
            | INVALID POSITION
            |--------------------------------------------------------------------------
            */

            if (
                position < 0 ||
                position >= defaultTimes.length
            ) {

                return;

            }

            /*
            |--------------------------------------------------------------------------
            | DAY
            |--------------------------------------------------------------------------
            */

            const day =
                String(
                    schedule.day ||
                    ''
                )
                .trim()
                .toUpperCase();

            /*
            |--------------------------------------------------------------------------
            | MONDAY
            |--------------------------------------------------------------------------
            */

            if (
                day === 'MON' ||
                day === 'MONDAY'
            ) {

                rows[position].MON =
                    schedule;


            }

            /*
            |--------------------------------------------------------------------------
            | WEDNESDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'WED' ||
                day === 'WEDNESDAY'
            ) {

                rows[position].WED =
                    schedule;


            }

            /*
            |--------------------------------------------------------------------------
            | FRIDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'FRI' ||
                day === 'FRIDAY'
            ) {

                rows[position].FRI =
                    schedule;


            }

            /*
            |--------------------------------------------------------------------------
            | TUESDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'TUE' ||
                day === 'TUESDAY'
            ) {

                rows[position].TUE =
                    schedule;


            }

            /*
            |--------------------------------------------------------------------------
            | THURSDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'THU' ||
                day === 'THURSDAY'
            ) {

                rows[position].THU =
                    schedule;


            }

            /*
            |--------------------------------------------------------------------------
            | SATURDAY
            |--------------------------------------------------------------------------
            */

            else if (
                day === 'SAT' ||
                day === 'SATURDAY'
            ) {

                rows[position].SAT =
                    schedule;


            }

        }
    );

    /*
    |--------------------------------------------------------------------------
    | ADD ACTIVE ROOM RESERVATIONS
    |--------------------------------------------------------------------------
    */

    (
        reservations || []
    ).forEach(
        function(reservation)
        {

            const day =
                String(
                    reservation.day || ''
                )
                .trim()
                .toUpperCase();

            const reservationStart =
                normalizeScheduleStartTime(
                    reservation.start_time ||
                    reservation.time ||
                    ''
                );

            if (!reservationStart) {

                return;

            }

            const dayKeyMap = {

                MON:
                    'MON_RESERVATION',

                WED:
                    'WED_RESERVATION',

                FRI:
                    'FRI_RESERVATION',

                TUE:
                    'TUE_RESERVATION',

                THU:
                    'THU_RESERVATION',

                SAT:
                    'SAT_RESERVATION'

            };

            const normalizedDay = day.slice(0, 3);
            const reservationKey = dayKeyMap[normalizedDay];

            if (!reservationKey) {

                return;

            }

            // Match by the actual reservation time first. Room-specific slot
            // rows may use a default row_position of 0, which would otherwise
            // place every booking in the first row.
            let reservationPosition = defaultTimes.findIndex(function(slot) {
                return normalizeScheduleStartTime(slot) === reservationStart;
            });

            if (reservationPosition < 0) {
                const suppliedPosition = reservation.row_position;
                const parsedPosition = suppliedPosition === null || suppliedPosition === ''
                    ? NaN
                    : Number(suppliedPosition);
                if (Number.isInteger(parsedPosition) && parsedPosition >= 0 && parsedPosition < defaultTimes.length) {
                    reservationPosition = parsedPosition;
                }
            }

            if (
                reservationPosition < 0
            ) {

                return;

            }

            rows[reservationPosition][reservationKey].push(reservation);

            rows[
                reservationPosition
            ].leftTime =
                defaultTimes[
                    reservationPosition
                ];

            rows[
                reservationPosition
            ].rightTime =
                defaultTimes[
                    reservationPosition
                ];

        }
    );

    /*
    |--------------------------------------------------------------------------
    | BUILD TABLE
    |--------------------------------------------------------------------------
    */

    const hasSaturdaySchedule = (schedules || []).some(function(schedule) {
        return ['SAT', 'SATURDAY'].includes(String(schedule.day || '').trim().toUpperCase());
    });

    let html = `

        <div class="timetable-wrapper" data-fit-schedule-container>

            <table class="faculty-timetable" data-fit-schedule>

                <thead>

                    <tr>

                        <th>TIME</th>

                        <th>MON</th>

                        <th>WED</th>

                        <th>FRI</th>

                        <th>TIME</th>

                        <th>TUE</th>

                        <th>THU</th>

                        ${hasSaturdaySchedule ? '<th>TIME</th><th>SAT</th>' : ''}

                    </tr>

                </thead>

                <tbody>

    `;

    /*
    |--------------------------------------------------------------------------
    | CREATE 9 ROWS
    |--------------------------------------------------------------------------
    */

    for (
        let position = 0;
        position < defaultTimes.length;
        position++
    ) {

        const row =
            rows[position];

        /*
        |--------------------------------------------------------------------------
        | NOON BREAK
        |--------------------------------------------------------------------------
        */

        if (
            position === 5
        ) {

            html += `

                <tr class="noon-break-row">

                    <td colspan="${hasSaturdaySchedule ? 9 : 7}">
                        NOON BREAK
                    </td>

                </tr>

            `;

        }

        /*
        |--------------------------------------------------------------------------
        | NORMAL ROW
        |--------------------------------------------------------------------------
        */

        html += `

            <tr data-row-position="${position}">

                <td class="time-column" data-time-side="left">

                    ${escapeHtml(
                        row.leftTime
                    )}

                </td>


                <!-- =========================================
                     MON / WED / FRI
                ========================================== -->

                ${buildFacultyScheduleGroup([

                    {
                        schedule:
                            row.MON,

                        reservation:
                            row.MON_RESERVATION
                    },

                    {
                        schedule:
                            row.WED,

                        reservation:
                            row.WED_RESERVATION
                    },

                    {
                        schedule:
                            row.FRI,

                        reservation:
                            row.FRI_RESERVATION
                    }

                ])}


                <td class="time-column" data-time-side="right">

                    ${escapeHtml(
                        row.rightTime
                    )}

                </td>


                <!-- =========================================
                     TUE / THU
                ========================================== -->

                ${buildFacultyScheduleGroup([

                    {
                        schedule:
                            row.TUE,

                        reservation:
                            row.TUE_RESERVATION
                    },

                    {
                        schedule:
                            row.THU,

                        reservation:
                            row.THU_RESERVATION
                    },

                ])}

                ${hasSaturdaySchedule
                    ? '<td class="time-column" data-time-side="sat">' + escapeHtml(row.saturdayTime) + '</td>' + buildFacultyScheduleGroup([{
                        schedule: row.SAT,
                        reservation: row.SAT_RESERVATION
                    }])
                    : ''}

            </tr>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | CLOSE TABLE
    |--------------------------------------------------------------------------
    */

    html += `

                </tbody>

            </table>

        </div>

    `;

    content.innerHTML =
        html;

    tagPublishedScheduleCells(content.querySelector('.faculty-timetable'));
    renderPublishedScheduleBlocks(content, manualMerges);

}


/* =========================================================
   NORMALIZE SCHEDULE START TIME
========================================================= */

function normalizeScheduleStartTime(value)
{
    const raw =
        String(value || '')
            .trim()
            .replace(/–/g, '-')
            .replace(/—/g, '-');

    if (!raw) {

        return '';

    }

    const start =
        raw
            .split('-')[0]
            .trim();

    const match =
        start.match(
            /^(\d{1,2}):(\d{2})/
        );

    if (!match) {

        return start.toUpperCase();

    }

    return (

        String(
            Number(match[1])
        )
        .padStart(2, '0') +

        ':' +

        match[2]

    );
}

function publishedClockMinutes(value)
{
    const match = String(value || '').trim().match(/^(\d{1,2}):(\d{2})(?:\s*(AM|PM))?$/i);
    if (!match) return null;

    let hour = Number(match[1]);
    const minute = Number(match[2]);
    const meridiem = String(match[3] || '').toUpperCase();
    if (meridiem === 'PM' && hour < 12) hour += 12;
    if (meridiem === 'AM' && hour === 12) hour = 0;
    if (!meridiem && hour >= 1 && hour <= 6) hour += 12;
    if (hour > 23 || minute > 59) return null;

    return hour * 60 + minute;
}

function publishedScheduleRange(value)
{
    const parts = String(value || '').trim().replace(/[–—]/g, '-').split('-');
    if (parts.length !== 2) return null;

    const start = publishedClockMinutes(parts[0]);
    const end = publishedClockMinutes(parts[1]);
    return start !== null && end !== null && end > start ? { start: start, end: end } : null;
}

function publishedFormatScheduleTime(value)
{
    const range = publishedScheduleRange(value);
    if (!range) return String(value || '');
    const clock = minute => {
        const hour = Math.floor(minute / 60) % 24;
        return (hour % 12 || 12) + ':' + String(minute % 60).padStart(2, '0');
    };
    const period = minute => Math.floor(minute / 60) % 24 < 12 ? 'AM' : 'PM';
    const startPeriod = period(range.start);
    const endPeriod = period(range.end);
    return startPeriod === endPeriod
        ? clock(range.start) + '–' + clock(range.end) + ' ' + endPeriod
        : clock(range.start) + ' ' + startPeriod + '–' + clock(range.end) + ' ' + endPeriod;
}

function publishedScheduleRowPosition(value, timeSlots, day, defaultTimes)
{
    const scheduleRange = publishedScheduleRange(value);
    if (!scheduleRange) return null;

    const normalizedDay = String(day || '').trim().toUpperCase().slice(0, 3);
    const matchingSlot = (timeSlots || []).find(function(item) {
        const slotDay = String(item.day || '').trim().toUpperCase().slice(0, 3);
        const slotRange = publishedScheduleRange(item.time || [item.start_time, item.end_time].filter(Boolean).join('-'));
        return slotDay === normalizedDay && slotRange
            && scheduleRange.start >= slotRange.start && scheduleRange.start < slotRange.end;
    });

    const targetStart = matchingSlot
        ? publishedScheduleRange(matchingSlot.time || [matchingSlot.start_time, matchingSlot.end_time].filter(Boolean).join('-'))?.start
        : scheduleRange.start;

    for (let index = 0; index < defaultTimes.length; index++) {
        const slot = publishedScheduleRange(defaultTimes[index]);
        if (slot && targetStart >= slot.start && targetStart < slot.end) {
            return index;
        }
    }

    return null;
}

function tagPublishedScheduleCells(table)
{
    if (!table) return;
    const dayLists = { left: ['MON', 'WED', 'FRI'], right: ['TUE', 'THU'], sat: ['SAT'] };
    Array.from(table.tBodies[0]?.rows || []).forEach(function(row) {
        if (row.classList.contains('noon-break-row')) return;
        const rowPosition = row.dataset.rowPosition || '';
        const nextDay = { left: 0, right: 0, sat: 0 };
        const rowTime = {};
        let side = 'left';
        Array.from(row.cells).forEach(function(cell) {
            if (cell.classList.contains('time-column')) {
                side = cell.dataset.timeSide || side;
                rowTime[side] = cell.textContent.trim();
                return;
            }
            const span = Math.max(1, Number(cell.colSpan || 1));
            cell.dataset.manualDays = dayLists[side].slice(nextDay[side], nextDay[side] + span).join(',');
            cell.dataset.manualSide = side;
            cell.dataset.manualRowPosition = rowPosition;
            cell.dataset.manualRowTime = rowTime[side] || '';
            nextDay[side] += span;
        });
    });
}

function renderPublishedScheduleBlocks(content, manualMerges = null)
{
    const wrapper = content?.querySelector('.timetable-wrapper');
    const table = wrapper?.querySelector('.faculty-timetable');
    if (!wrapper || !table) return;
    if (Array.isArray(manualMerges)) wrapper._manualMerges = manualMerges;
    manualMerges = wrapper._manualMerges || [];

    if (window.ResizeObserver && !wrapper._publishedScheduleResizeObserver) {
        wrapper._publishedScheduleResizeObserver = new ResizeObserver(function() {
            renderPublishedScheduleBlocks(content);
        });
        wrapper._publishedScheduleResizeObserver.observe(wrapper);
    }

    let layer = wrapper.querySelector('.published-schedule-block-layer');
    if (!layer) {
        layer = document.createElement('div');
        layer.className = 'published-schedule-block-layer';
        layer.setAttribute('aria-hidden', 'true');
        wrapper.appendChild(layer);
    }
    layer.replaceChildren();

    function yAtMinute(side, minute, edge)
    {
        const rows = Array.from(table.tBodies[0]?.rows || []).filter(function(row) {
            return !row.classList.contains('noon-break-row');
        });

        for (const row of rows) {
            const timeCell = row.querySelector('[data-time-side="' + side + '"]');
            const range = publishedScheduleRange(timeCell?.textContent || '');
            if (!range) continue;

            if (edge === 'start' && minute === range.end) continue;
            if (edge === 'end' && minute === range.start) continue;
            if (minute < range.start || minute > range.end) continue;

            const rect = timeCell.getBoundingClientRect();
            const fraction = (minute - range.start) / (range.end - range.start);
            return rect.top + rect.height * fraction;
        }

        return null;
    }

    const wrapperRect = wrapper.getBoundingClientRect();
    const tableRect = table.getBoundingClientRect();
    layer.style.position = 'absolute';
    layer.style.inset = 'auto';
    layer.style.left = (tableRect.left - wrapperRect.left - wrapper.clientLeft + wrapper.scrollLeft) + 'px';
    layer.style.top = (tableRect.top - wrapperRect.top - wrapper.clientTop + wrapper.scrollTop) + 'px';
    layer.style.width = tableRect.width + 'px';
    layer.style.height = tableRect.height + 'px';
    const sideByDay = { MON: 'left', WED: 'left', FRI: 'left', TUE: 'right', THU: 'right', SAT: 'sat' };

    const usedHorizontalMerges = new Set();
    Array.from(table.querySelectorAll('td[data-view-schedule-time]')).forEach(function(cell) {
        const range = publishedScheduleRange(cell.dataset.viewScheduleTime);
        const source = cell.querySelector('.class-entry');
        const side = sideByDay[String(cell.dataset.viewScheduleDay || '').toUpperCase().slice(0, 3)];
        if (!range || !source || !side) return;

        let top = yAtMinute(side, range.start, 'start');
        let bottom = yAtMinute(side, range.end, 'end');
        if (top === null || bottom === null || bottom <= top) return;

        const cellRect = cell.getBoundingClientRect();
        const day = String(cell.dataset.viewScheduleDay || '').trim().toUpperCase().slice(0, 3);
        const rowPosition = Number(cell.closest('tr')?.dataset.rowPosition);
        const rowTime = cell.closest('td')?.dataset.manualRowTime || '';
        const mergeIndex = manualMerges.findIndex(function(merge) {
            return merge.axis === 'horizontal' && (merge.cells || []).some(function(coordinate) {
                if (String(coordinate.day || '').toUpperCase() !== day) return false;
                const coordinatePosition = Number(coordinate.row_position);
                if (Number.isFinite(coordinatePosition) && coordinatePosition === rowPosition) return true;
                const coordinateRange = publishedScheduleRange(coordinate.time || '');
                const rowRange = publishedScheduleRange(rowTime);
                return coordinateRange && rowRange && coordinateRange.start === rowRange.start;
            });
        });
        let mergedCells = [];
        let geometryAxis = '';
        if (mergeIndex >= 0) {
            geometryAxis = 'horizontal';
            const merge = manualMerges[mergeIndex];
            if (usedHorizontalMerges.has(mergeIndex)) {
                source.classList.add('published-schedule-source-hidden');
                return;
            }
            mergedCells = (merge.cells || []).map(function(coordinate) {
                const coordinateRange = publishedScheduleRange(coordinate.time || '');
                return Array.from(table.querySelectorAll('td[data-manual-row-position]')).find(function(candidate) {
                    if (candidate.dataset.manualSide !== String(coordinate.side || '')) return false;
                    if (!(candidate.dataset.manualDays || '').split(',').includes(String(coordinate.day || '').toUpperCase())) return false;
                    const samePosition = Number(candidate.dataset.manualRowPosition) === Number(coordinate.row_position);
                    const candidateRange = publishedScheduleRange(candidate.dataset.manualRowTime || '');
                    return samePosition || (coordinateRange && candidateRange && coordinateRange.start === candidateRange.start);
                });
            }).filter(Boolean);
            if (mergedCells.length > 1) usedHorizontalMerges.add(mergeIndex);
        } else {
            const verticalIndex = manualMerges.findIndex(function(merge) {
                return merge.axis === 'vertical' && (merge.cells || []).some(function(coordinate) {
                    if (String(coordinate.day || '').toUpperCase() !== day) return false;
                    const coordinatePosition = Number(coordinate.row_position);
                    if (Number.isFinite(coordinatePosition) && coordinatePosition === rowPosition) return true;
                    const coordinateRange = publishedScheduleRange(coordinate.time || '');
                    const rowRange = publishedScheduleRange(rowTime);
                    return coordinateRange && rowRange && coordinateRange.start === rowRange.start;
                });
            });
            if (verticalIndex >= 0) {
                geometryAxis = 'vertical';
                mergedCells = (manualMerges[verticalIndex].cells || []).map(function(coordinate) {
                    const coordinateRange = publishedScheduleRange(coordinate.time || '');
                    return Array.from(table.querySelectorAll('td[data-manual-row-position]')).find(function(candidate) {
                        if (candidate.dataset.manualSide !== String(coordinate.side || '')) return false;
                        if (!(candidate.dataset.manualDays || '').split(',').includes(String(coordinate.day || '').toUpperCase())) return false;
                        const samePosition = Number(candidate.dataset.manualRowPosition) === Number(coordinate.row_position);
                        const candidateRange = publishedScheduleRange(candidate.dataset.manualRowTime || '');
                        return samePosition || (coordinateRange && candidateRange && coordinateRange.start === candidateRange.start);
                    });
                }).filter(Boolean);
            }
        }
        const block = source.cloneNode(true);
        block.classList.add('published-schedule-block');
        block.classList.remove('published-schedule-source-hidden');
        block.style.visibility = 'visible';
        block.dataset.durationMinutes = String(range.end - range.start);
        const mergeRects = mergedCells.map(function(item) { return item.getBoundingClientRect(); });
        const mergedLeft = mergeRects.length ? Math.min(...mergeRects.map(function(rect) { return rect.left; })) : cellRect.left;
        const mergedRight = mergeRects.length ? Math.max(...mergeRects.map(function(rect) { return rect.right; })) : cellRect.right;
        if (mergeRects.length && geometryAxis === 'vertical') {
            top = Math.min(...mergeRects.map(function(rect) { return rect.top; }));
            bottom = Math.max(...mergeRects.map(function(rect) { return rect.bottom; }));
        }
        block.style.left = (mergedLeft - tableRect.left) + 'px';
        block.style.top = (top - tableRect.top) + 'px';
        block.style.width = (mergedRight - mergedLeft) + 'px';
        block.style.height = (bottom - top) + 'px';
        const selectedOverride = wrapper._selectedOverride;
        const cellDays = (cell.dataset.manualDays || day).split(',').map(function(value) { return value.trim().toUpperCase(); }).filter(Boolean);
        if (selectedOverride && String(cell.dataset.viewScheduleRoomId || '') === String(selectedOverride.roomId || '')
            && cellDays.includes(selectedOverride.day)
            && range.start < selectedOverride.end && range.end > selectedOverride.start) {
            const matchingDayCell = mergedCells.find(function(candidate) {
                return (candidate.dataset.manualDays || '').split(',').map(function(value) { return value.trim().toUpperCase(); }).includes(selectedOverride.day);
            });
            if (geometryAxis === 'horizontal' && matchingDayCell && cellDays.length > 1) {
                const matchingRect = matchingDayCell.getBoundingClientRect();
                const segment = document.createElement('span');
                segment.className = 'published-schedule-override-day-highlight';
                segment.style.left = (matchingRect.left - mergedLeft) + 'px';
                segment.style.width = matchingRect.width + 'px';
                block.appendChild(segment);
            } else {
                block.classList.add('schedule-override-highlight');
            }
        }
        const timeBadge = document.createElement('span');
        timeBadge.className = 'class-time';
        timeBadge.textContent = publishedFormatScheduleTime(cell.dataset.viewScheduleTime || '');
        block.appendChild(timeBadge);
        block.title = [publishedFormatScheduleTime(cell.dataset.viewScheduleTime || ''), source.innerText || ''].filter(Boolean).join(' · ');
        layer.appendChild(block);
        source.classList.add('published-schedule-source-hidden');
    });
}

window.addEventListener('resize', function() {
    document.querySelectorAll('#scheduleContent').forEach(renderPublishedScheduleBlocks);
});


/* =========================================================
   BUILD RESERVATION CELL
========================================================= */

function buildReservationCell(reservations)
{
    const first = reservations[0] || {};
    // Keep JSON safe inside the generated HTML attribute. Text escaping does
    // not escape quotes for an attribute, while URI encoding does.
    const encoded = encodeURIComponent(JSON.stringify(reservations));
    const dateLabel = reservations.length === 1 && first.date
        ? new Date(first.date + 'T12:00:00').toLocaleDateString(undefined, {month:'short',day:'numeric',year:'numeric'})
        : reservations.length + ' reservation dates';
    const timeLabel = [formatReservationClock(first.start_time), formatReservationClock(first.end_time)].filter(Boolean).join(' – ');
    const courseCode = first.course_code || 'Room reservation';
    const courseTitle = first.subject || (reservations.length > 1 ? 'Open to review all reserved dates' : (first.purpose || 'Room reservation'));

    return `
        <td class="shared-reservation-cell">
            <button type="button" class="temporary-reservation-entry shared-reservation-entry"
                data-reservations="${encoded}"
                aria-label="${escapeHtml(courseCode)}. ${escapeHtml(dateLabel)}. Open reservation details.">
                <span class="reservation-entry-top"><span class="reservation-badge"><i class="fa-solid fa-calendar-check"></i> Room reserved</span><time class="reservation-time">${escapeHtml(timeLabel)}</time></span>
                <span class="reservation-course">${escapeHtml(courseCode)}</span>
                <span class="reservation-purpose">${escapeHtml(courseTitle)}</span>
                <span class="reservation-requester">${escapeHtml(dateLabel)}</span>
                <span class="reservation-expiration"><i class="fa-solid fa-arrow-up-right-from-square"></i> View details</span>
            </button>
        </td>
    `;
}

function formatReservationClock(value)
{
    const match = String(value || '').match(/^(\d{1,2}):(\d{2})/);
    if (!match) return String(value || '');
    const hour = Number(match[1]);
    const suffix = hour >= 12 ? 'PM' : 'AM';
    return `${hour % 12 || 12}:${match[2]} ${suffix}`;
}

window.openRoomReservationDetails = function(button)
{
    let reservations = [];
    try {
        reservations = JSON.parse(decodeURIComponent(button.dataset.reservations || ''));
    } catch (error) {
        console.error('Could not read the room reservation details.', error);
        return;
    }
    const items = reservations.map(item => {
        const date = item.date ? new Date(item.date + 'T12:00:00').toLocaleDateString(undefined, {year:'numeric',month:'long',day:'numeric'}) : 'Date unavailable';
        const time = [formatReservationClock(item.start_time), formatReservationClock(item.end_time)].filter(Boolean).join(' – ');
        const code = item.course_code || 'Room reservation';
        const title = item.subject || item.purpose || 'General room use';
        return `<article class="room-reservation-detail-card"><div class="reservation-detail-top"><strong>${escapeHtml(date)}</strong><span>${escapeHtml(time)}</span></div><strong class="reservation-detail-code">${escapeHtml(code)}</strong><p class="reservation-detail-title">${escapeHtml(title)}</p><div class="reservation-detail-footer"><span><i class="fa-solid fa-user"></i> ${escapeHtml(item.user_name || 'Instructor')}</span></div></article>`;
    }).join('');

    let modal = document.getElementById('roomReservationDetailsModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'roomReservationDetailsModal';
        modal.className = 'room-reservation-details-overlay';
        modal.innerHTML = `<section class="room-reservation-details-dialog" role="dialog" aria-modal="true" aria-labelledby="roomReservationDetailsTitle"><header><div><span>ROOM RESERVATIONS</span><h2 id="roomReservationDetailsTitle">Reservation details</h2></div><button type="button" data-close-room-reservation-details aria-label="Close">×</button></header><div class="room-reservation-details-list"></div></section>`;
        document.body.appendChild(modal);
    }
    modal.querySelector('.room-reservation-details-list').innerHTML = items || '<p>No active reservations.</p>';
}

// Reservation cells are created dynamically after the room schedule loads.
// Delegate clicks from the document so the details action remains active on
// dynamically inserted cells and their child elements.
document.addEventListener('click', function(event) {
    const target = event.target instanceof Element ? event.target : null;
    if (!target) return;

    const closeButton = target.closest('[data-close-room-reservation-details]');
    if (closeButton) {
        document.getElementById('roomReservationDetailsModal')?.remove();
        return;
    }

    const overlay = target.closest('#roomReservationDetailsModal');
    if (overlay && target === overlay) {
        overlay.remove();
        return;
    }

    const reservationButton = target.closest('.shared-reservation-entry');
    if (reservationButton) {
        window.openRoomReservationDetails(reservationButton);
    }
});


/* =========================================================
   BUILD SCHEDULE CELL
========================================================= */

function buildScheduleCell(
    schedule,
    reservation = null
)
{

    /*
    |--------------------------------------------------------------------------
    | CHECK IF SCHEDULE IS OCCUPIED
    |--------------------------------------------------------------------------
    */

    const scheduleOccupied =
        schedule &&
        (
            String(
                schedule.course_code || ''
            ).trim() !== '' ||

            String(
                schedule.subject || ''
            ).trim() !== '' ||

            String(
                schedule.instructor || ''
            ).trim() !== ''
        );

    /*
    |--------------------------------------------------------------------------
    | RESERVATION
    |--------------------------------------------------------------------------
    */

    // A dated room reservation takes precedence for the selected timeslot.
    // The weekly schedule reappears after the reservation expires.
    if (Array.isArray(reservation) && reservation.length > 0) {

        return buildReservationCell(
            reservation
        );

    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY CELL
    |--------------------------------------------------------------------------
    */

    if (!schedule) {

        return `

            <td>

                <span
                    class="empty-schedule-cell"
                >
                    Empty
                </span>

            </td>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | PROGRAMS
    |--------------------------------------------------------------------------
    */

    let programs = [];

    /*
    |--------------------------------------------------------------------------
    | ORIGINAL PROGRAM
    |--------------------------------------------------------------------------
    */

    if (
        (schedule.program_code || schedule.program) &&
        String(
            schedule.program_code || schedule.program
        ).trim() !== ''
    ) {

        programs.push(
            String(
                schedule.program_code || schedule.program
            ).trim()
        );

    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED MERGED PROGRAMS
    |--------------------------------------------------------------------------
    */

    if (
        Array.isArray(
            schedule.merged_programs
        )
    ) {

        schedule.merged_programs
            .forEach(
                function(program)
                {

                    if (
                        program &&
                        String(
                            program
                        ).trim() !== '' &&
                        !programs.includes(
                            String(
                                program
                            ).trim()
                        )
                    ) {

                        programs.push(
                            String(
                                program
                            ).trim()
                        );

                    }

                }
            );

    }

    /*
    |--------------------------------------------------------------------------
    | PROGRAM DISPLAY
    |--------------------------------------------------------------------------
    */

    programs = Array.from(new Set(programs.map(displayProgramCode).filter(Boolean)));

    let programHtml = '';

    if (
        programs.length > 0
    ) {

        programHtml = `

            <div class="class-program">

                ${escapeHtml(
                    programs.join(' / ')
                )}

            </div>

        `;

    }

    const mergedCourses = Array.isArray(schedule.merged_courses) ? schedule.merged_courses : [];
    const primaryMajor = String(schedule.major || '').trim().toLowerCase();
    const sharedMajors = [...new Set(mergedCourses
        .filter(course => String(course.share_type || '').toLowerCase() === 'same_faculty')
        .map(course => String(course.major || '').trim())
        .filter(major => major && major.toLowerCase() !== primaryMajor))];
    const mergedCoursesHtml = [
        sharedMajors.length ? `<div class="merged-course-details cell-shared-majors" style="margin-top:4px;padding:4px 6px;border-left:2px solid #568468;background:rgba(255,255,255,.78);color:#40584a;font-size:10px;line-height:1.35"><strong>Also shared with</strong><span>${sharedMajors.map(escapeHtml).join(' · ')}</span></div>` : '',
        ...mergedCourses.filter(course => String(course.share_type || '').toLowerCase() !== 'same_faculty').map(function(course) {
            const label = [course.course_code, course.course_title].filter(Boolean).join(' — ');
            if (!label) return '';
            return `<div class="merged-course-details" style="margin-top:4px;padding:4px 6px;border-left:2px solid #568468;background:rgba(255,255,255,.78);color:#40584a;font-size:10px;line-height:1.35"><strong>${escapeHtml(label)}</strong>${course.program ? `<span style="display:block;color:#68776d">${escapeHtml(displayProgramCode(course.program))}</span>` : ''}</div>`;
        })
    ].filter(Boolean).join('');

    /*
    |--------------------------------------------------------------------------
    | LEC / LAB
    |--------------------------------------------------------------------------
    */

    let typeHtml = '';

    if (
        schedule.subject_type &&
        String(
            schedule.subject_type
        ).trim() !== ''
    ) {

        typeHtml = `

            <div class="class-type">

                ${escapeHtml(
                    schedule.subject_type
                )}

            </div>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | MAJOR
    |--------------------------------------------------------------------------
    */

    let majorHtml = '';

    if (
        schedule.major &&
        String(
            schedule.major
        ).trim() !== ''
    ) {

        majorHtml = `

            <div class="class-major">

                Major:
                ${escapeHtml(
                    schedule.major
                )}

            </div>

        `;

    }

      /*
    |--------------------------------------------------------------------------
    | ROOM SWAP STATUS
    |--------------------------------------------------------------------------
    */

    let roomSwapHtml = '';

    if (
        schedule.room_swap &&
        schedule.room_swap.swap_type !== 'permanent' &&
        (schedule.room_swap.active || schedule.room_swap.scheduled)
    ) {

        const originalRoom =
            schedule.room_swap.original_room_name ||
            'Original room';

        const swappedRoom =
            schedule.room_swap.swapped_to_room_name ||
            '';

        const endTime =
            schedule.room_swap.end_time ||
            '';

        const swapDate = schedule.room_swap.swap_date
            ? new Date(schedule.room_swap.swap_date + 'T12:00:00').toLocaleDateString(undefined, {year:'numeric',month:'short',day:'numeric'})
            : 'Scheduled date';

        const swapState = schedule.room_swap.active ? 'Temporary room swap active' : 'Temporary room swap scheduled';

        roomSwapHtml = `

            <div class="room-swap-badge">

                <i class="fa-solid fa-right-left"></i>

                ${escapeHtml(swapState)}

            </div>

            ${
                swappedRoom
                    ? `
                        <div class="room-swap-details">

                            Now in room:
                            ${escapeHtml(swappedRoom)}

                        </div>
                      `
                    : ''
            }

            <div class="room-swap-details">

                ${escapeHtml(swapDate)} · ${escapeHtml(schedule.room_swap.start_time || '')}–${escapeHtml(endTime)}

            </div>

            <div class="room-swap-original">

                Original Room:
                ${escapeHtml(originalRoom)}

            </div>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | COLOR
    |--------------------------------------------------------------------------
    */

    const isTemporaryRoomSwap = Boolean(
        schedule.room_swap &&
        schedule.room_swap.swap_type !== 'permanent' &&
        (schedule.room_swap.active || schedule.room_swap.scheduled)
    );
    const background = isTemporaryRoomSwap
        ? '#f3e8ff'
        : (schedule.color || '#eaf6ef');

    /*
    |--------------------------------------------------------------------------
    | CLASS CELL
    |--------------------------------------------------------------------------
    */

    return `

        <td
            data-view-schedule-time="${escapeHtml(schedule.time || '')}"
            data-view-schedule-day="${escapeHtml(schedule.day || '')}"
            data-view-schedule-room-id="${escapeHtml(schedule.room_id || '')}"
        >

            <div
                class="class-entry${isTemporaryRoomSwap ? ' room-swapped-temporary' : ''}"
                style="
                    background:
                        ${escapeHtml(
                            background
                        )};
                "
            >

                <div class="class-course">

                    ${escapeHtml(
                        schedule.course_code || ''
                    )}

                </div>

                <div class="class-subject">

                    ${escapeHtml(
                        schedule.subject || ''
                    )}

                </div>

                ${mergedCoursesHtml}

                ${typeHtml}

                <div class="class-instructor">

                    ${escapeHtml(
                        schedule.instructor || ''
                    )}

                </div>

                ${programHtml}

                ${majorHtml}

                ${roomSwapHtml}

            </div>

        </td>

    `;

}


/* =========================================================
   CLOSE SCHEDULE
========================================================= */

function closeSchedule()
{

    if (reservationExpiryTimer) {

        clearTimeout(
            reservationExpiryTimer
        );

        reservationExpiryTimer =
            null;

    }

    const panel =
        document.getElementById(
            'schedulePanel'
        );

    const content =
        document.getElementById(
            'scheduleContent'
        );

    const roomTitle =
        document.getElementById(
            'scheduleRoomName'
        );

    const roomBuilding =
        document.getElementById(
            'scheduleRoomBuilding'
        );

    /*
    |--------------------------------------------------------------------------
    | CLOSE PANEL
    |--------------------------------------------------------------------------
    */

    if (panel) {

        panel.classList.remove(
            'open'
        );

        panel.style.display =
            'none';

    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE ACTIVE ROOM
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.room-card.active'
        )
        .forEach(
            function(card)
            {

                card.classList.remove(
                    'active'
                );

            }
        );

    /*
    |--------------------------------------------------------------------------
    | RESET ROOM TITLE
    |--------------------------------------------------------------------------
    */

    if (roomTitle) {

        roomTitle.textContent =
            'Classroom Schedule';

    }

    /*
    |--------------------------------------------------------------------------
    | RESET BUILDING
    |--------------------------------------------------------------------------
    */

    if (roomBuilding) {

        roomBuilding.textContent =
            'Select a room to view its schedule';

    }

    /*
    |--------------------------------------------------------------------------
    | RESET CONTENT
    |--------------------------------------------------------------------------
    */

    if (content) {

        content.innerHTML = `

            <div class="schedule-loading">

                Select a room to view its schedule.

            </div>

        `;

    }

    updateScheduleProgress();

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(value)
{
    const div =
        document.createElement('div');

    div.textContent =
        value ?? '';

    return div.innerHTML;
}

</script>

        </main>

    </div>

    

</div>



