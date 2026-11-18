@include('footerheader.header')

<style>
    /* Faculty & subjects: branded Chair workspace with clearer directory and assignment hierarchy. */
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
    body.portal-shell.chair-portal .main-layout > .content.chair-faculty-view { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }

    .chair-faculty-view .faculty-setup-page { width:min(100%,1480px); margin:0 auto; }
    .chair-faculty-view .faculty-setup-hero { position:relative; display:flex; align-items:center; justify-content:space-between; gap:24px; min-height:160px; margin:0 0 17px; padding:clamp(24px,3vw,34px); overflow:hidden; border:1px solid rgba(231,214,173,.22); border-radius:24px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); color:#fff; }
    .chair-faculty-view .faculty-setup-hero::after { position:absolute; right:-48px; bottom:-165px; width:330px; height:330px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .chair-faculty-view .faculty-setup-hero-copy { position:relative; z-index:1; max-width:730px; }
    .chair-faculty-view .faculty-setup-eyebrow { margin-bottom:10px; color:#e2c987; font-size:11px; font-weight:800; letter-spacing:.15em; }
    .chair-faculty-view .faculty-setup-hero h1 { margin:0 0 9px; color:#fff; font:800 clamp(34px,3.2vw,45px)/1.12 var(--wr-display); letter-spacing:-.045em; }
    .chair-faculty-view .faculty-setup-hero p { margin:0; color:#e1e8df; font-size:15px; font-weight:600; line-height:1.6; }
    .chair-faculty-view .faculty-setup-department { position:relative; z-index:1; display:flex; align-items:center; gap:11px; min-width:220px; padding:14px 16px; border:1px solid rgba(255,255,255,.23); border-radius:14px; background:rgba(255,255,255,.1); }
    .chair-faculty-view .faculty-setup-department-icon { display:grid; flex:0 0 38px; width:38px; height:38px; place-items:center; border-radius:12px; background:rgba(232,215,174,.18); color:#e2c987; font-size:16px; }
    .chair-faculty-view .faculty-setup-department div { display:grid; gap:4px; min-width:0; }
    .chair-faculty-view .faculty-setup-department small { color:#d9e1d8; font-size:10px; font-weight:800; letter-spacing:.1em; }
    .chair-faculty-view .faculty-setup-department strong { color:#fff; font-size:14px; font-weight:800; overflow-wrap:anywhere; }
    .chair-faculty-view .faculty-setup-metrics { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin:0 0 18px; }
    .chair-faculty-view .faculty-setup-metric { display:flex; align-items:center; gap:13px; min-height:91px; padding:16px 18px; border:1px solid #dfe7dd; border-radius:15px; background:#fff; box-shadow:0 7px 22px rgba(31,57,43,.05); }
    .chair-faculty-view .faculty-setup-metric-icon { display:grid; flex:0 0 42px; width:42px; height:42px; place-items:center; border:1px solid #e2e9df; border-radius:13px; background:#edf3eb; color:#285940; font-size:16px; }
    .chair-faculty-view .faculty-setup-metric-copy { display:grid; gap:4px; }
    .chair-faculty-view .faculty-setup-metric-copy strong { color:#173b33; font-size:25px; font-weight:850; line-height:1; }
    .chair-faculty-view .faculty-setup-metric-copy span { color:#566b5e; font-size:12px; font-weight:750; }
    .chair-faculty-view .message { margin:0 0 14px; padding:14px 17px; border:1px solid; border-radius:12px; font-size:14px; font-weight:700; line-height:1.5; }
    .chair-faculty-view .message.success { border-color:#b9d9bd; background:#edf7ee; color:#28603a; }
    .chair-faculty-view .message.error { border-color:#edc8c2; background:#fff3f1; color:#8f3e36; }
    .chair-faculty-view .message ul { margin:0; padding-left:21px; }
    .faculty-assignment-toast { position:fixed; top:20px; left:50%; z-index:1000500; display:flex; align-items:center; gap:10px; max-width:min(420px,calc(100vw - 32px)); padding:13px 18px; border:1px solid #cde4d2; border-radius:12px; background:#fff; color:#244a32; box-shadow:0 14px 38px rgba(20,55,32,.2); font-size:13px; font-weight:750; opacity:0; transform:translate(-50%,-12px); pointer-events:none; transition:opacity .18s ease,transform .18s ease; }
    .faculty-assignment-toast.is-visible { opacity:1; transform:translate(-50%,0); }
    .faculty-assignment-toast i { color:#168044; font-size:16px; }
    .chair-faculty-view #faculty-add-subject { scroll-margin-top:125px; }
    .chair-faculty-view .department-info { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:0 0 16px; padding:14px 17px; border:1px solid #dfe7dd; border-radius:13px; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .chair-faculty-view .department-info-label { color:#68776d; font-size:12px; font-weight:750; }
    .chair-faculty-view .department-info-name { color:#244a35; font-size:14px; font-weight:850; }
    .chair-faculty-view .section { min-width:0; margin:0 0 17px; padding:21px; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .chair-faculty-view .section-title { display:flex; align-items:center; gap:10px; margin:0 0 6px; color:#173b33; font-size:21px; font-weight:800; letter-spacing:-.03em; }
    .chair-faculty-view .faculty-total-badge { display:inline-grid; min-width:27px; height:27px; place-items:center; padding:0 7px; border-radius:99px; background:#edf3eb; color:#285940; font-size:12px; font-weight:850; }
    .chair-faculty-view .faculty-directory-note { margin:0 0 16px; color:#5b6d61; font-size:13px; font-weight:550; line-height:1.55; }
    .chair-faculty-view .faculty-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .chair-faculty-view .faculty-card { position:relative; display:flex; min-width:0; min-height:205px; flex-direction:column; align-items:stretch; padding:14px; overflow:hidden; border:1px solid #e2e9e1; border-radius:15px; background:#fff; color:inherit; text-decoration:none; transition:transform .16s ease,border-color .16s ease,box-shadow .16s ease; }
    .chair-faculty-view .faculty-card:hover { transform:translateY(-2px); border-color:#adc5b0; box-shadow:0 10px 22px rgba(31,57,43,.08); }
    .chair-faculty-view .faculty-card.active { border-color:#78a080; background:linear-gradient(145deg,#f1f7ef,#fff); box-shadow:inset 3px 0 #356a4d; }
    .chair-faculty-view .faculty-directory-origin { display:flex; align-items:center; gap:6px; min-height:22px; margin-bottom:7px; padding:0 8px; border:1px solid #e1e8df; border-radius:99px; background:#f6f8f4; color:#53675a; font-size:11px; font-weight:800; line-height:1.35; }
    .chair-faculty-view .faculty-directory-origin.imported { border-color:#e6ddc4; background:#fbf8ee; color:#806a39; }
    .chair-faculty-view .faculty-card-person { display:flex; align-items:center; gap:11px; min-width:0; margin:2px 0 4px; }
    .chair-faculty-view .faculty-icon { display:grid; flex:0 0 44px; width:44px; height:44px; place-items:center; margin:0; border:1px solid #e2e9df; border-radius:50%; background:#edf3eb; color:#285940; font-size:17px; }
    .chair-faculty-view img.faculty-icon { object-fit:cover; }
    .chair-faculty-view .faculty-icon[hidden] { display:none !important; }
    .chair-faculty-view .faculty-name { color:#203d30; font-size:16px; font-weight:850; line-height:1.35; overflow-wrap:anywhere; }
    .chair-faculty-view .faculty-count { margin-top:4px; color:#718078; font-size:11px; font-weight:650; line-height:1.4; }
    .chair-faculty-view .faculty-card-actions { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:7px; width:100%; margin-top:auto; padding-top:12px; }
    .chair-faculty-view .faculty-card-action { display:inline-flex; align-items:center; justify-content:center; gap:6px; min-width:0; min-height:38px; padding:6px 8px; border:1px solid #cdddcf; border-radius:9px; background:#f7faf6; color:#315b43; font:800 10px/1.25 var(--wr-body); text-align:center; text-decoration:none; cursor:pointer; }
    .chair-faculty-view .faculty-card-action:hover { border-color:#84a78b; background:#edf5ed; }
    .chair-faculty-view .faculty-card-action:focus-visible { outline:3px solid rgba(49,104,76,.22); outline-offset:2px; }
    .chair-faculty-view .faculty-card-action.primary { border-color:#24543e; background:#24543e; color:#fff; }
    .chair-faculty-view .faculty-card-action.primary { grid-column:1/-1; }
    .chair-faculty-view .faculty-card-action.primary:hover { background:#173b33; }
    .chair-faculty-view .faculty-card-action i { flex:0 0 auto; }
    .chair-faculty-view .faculty-card-action[type="button"] { appearance:none; }
    .chair-faculty-view .faculty-schedule-overlay { position:fixed; inset:0; z-index:14000; display:none; align-items:center; justify-content:center; padding:20px; background:rgba(15,32,25,.62); backdrop-filter:blur(5px); }
    .chair-faculty-view .faculty-schedule-overlay.is-open { display:flex; }
    .chair-faculty-view .faculty-schedule-dialog { width:min(1480px,100%); max-height:calc(100vh - 40px); overflow:auto; padding:18px; border:1px solid #dfe7dd; border-radius:18px; background:#f7f8f4; box-shadow:0 28px 80px rgba(0,0,0,.3); }
    .chair-faculty-view .faculty-schedule-modal-content { color:#20372a; }
    .chair-faculty-view .faculty-schedule-preview-hero { position:relative; overflow:hidden; margin:0 0 14px; padding:20px 23px; border-radius:15px; background:linear-gradient(118deg,#163b33,#37664d); color:#fff; }
    .chair-faculty-view .faculty-schedule-preview-back { position:relative; z-index:1; display:inline-flex; align-items:center; gap:7px; min-height:35px; margin:0 0 12px; padding:0 11px; border:1px solid rgba(255,255,255,.28); border-radius:8px; background:rgba(255,255,255,.1); color:#fff; font:800 11px/1.2 var(--wr-body); text-decoration:none; cursor:pointer; }
    .chair-faculty-view .faculty-schedule-preview-kicker { display:block; margin-bottom:5px; color:#e2c987; font-size:9px; font-weight:850; letter-spacing:.14em; text-transform:uppercase; }
    .chair-faculty-view .faculty-schedule-preview-hero h1 { margin:0 0 5px; color:#fff; font:800 25px/1.2 var(--wr-display); }
    .chair-faculty-view .faculty-schedule-preview-hero p { margin:0; color:#e1e8df; font-size:12px; }
    .chair-faculty-view .faculty-schedule-preview-meta { display:flex; flex-wrap:wrap; gap:7px; margin:0 0 11px; }
    .chair-faculty-view .faculty-schedule-preview-meta span { padding:7px 10px; border:1px solid #dfe7dd; border-radius:8px; background:#fff; color:#365644; font-size:10px; font-weight:800; }
    .chair-faculty-view .faculty-schedule-preview-sheet { overflow:hidden; border:1px solid #dfe7dd; border-radius:12px; background:#fff; }
    .chair-faculty-view .faculty-schedule-preview-scroll { max-height:calc(100vh - 235px); overflow:auto; }
    .chair-faculty-view .faculty-schedule-preview-scroll table { width:100%; min-width:1320px; border-collapse:separate; border-spacing:0; table-layout:fixed; }
    .chair-faculty-view .faculty-schedule-preview-scroll thead th { position:sticky; top:0; z-index:2; height:42px; padding:9px; border-right:1px solid #557b63; border-bottom:1px solid #557b63; background:#245940; color:#fff; font-size:10px; font-weight:850; letter-spacing:.04em; text-align:center; }
    .chair-faculty-view .faculty-schedule-preview-scroll thead th:first-child { width:145px; background:#173b33; }
    .chair-faculty-view .faculty-schedule-preview-scroll thead th:nth-child(5),.chair-faculty-view .faculty-schedule-preview-scroll thead th:nth-child(8) { background:#173b33; }
    .chair-faculty-view .faculty-schedule-preview-scroll tbody th { width:145px; padding:10px; border-right:1px solid #dfe7dd; border-bottom:1px solid #e5ebe3; background:#f4f7f1; color:#355343; font-size:10px; font-weight:850; text-align:left; vertical-align:top; }
    .chair-faculty-view .faculty-schedule-preview-scroll td { min-height:75px; padding:6px; border-right:1px solid #e5ebe3; border-bottom:1px solid #e5ebe3; background:#fff; vertical-align:top; }
    .chair-faculty-view .faculty-schedule-preview-scroll tbody tr:nth-child(even) td { background:#fbfcfa; }
    .chair-faculty-view .faculty-schedule-preview-scroll tbody .faculty-schedule-time { width:120px; background:#f4f7f1; color:#355343; font-size:10px; font-weight:850; white-space:nowrap; }
    .chair-faculty-view .faculty-schedule-preview-scroll .faculty-schedule-noon-break td { height:32px; background:#edf3eb; color:#315b43; font-size:10px; font-weight:900; text-align:center; }
    .chair-faculty-view .faculty-schedule-empty-cell { display:block; padding:8px 4px; color:#a3ada5; font-size:9px; text-align:center; }
    .chair-faculty-view .faculty-schedule-class-card { display:grid; gap:3px; margin:0 0 5px; padding:7px; border:1px solid #d9e6d9; border-left:3px solid #32704d; border-radius:7px; background:linear-gradient(145deg,#f0f7ef,#fbfdf9); }
    .chair-faculty-view .faculty-schedule-class-card:last-child { margin-bottom:0; }
    .chair-faculty-view .faculty-schedule-class-heading { display:flex; align-items:flex-start; justify-content:space-between; gap:5px; }
    .chair-faculty-view .faculty-schedule-class-card strong { color:#1f6844; font-size:10px; font-weight:900; overflow-wrap:anywhere; }
    .chair-faculty-view .faculty-schedule-class-heading span { flex:0 0 auto; padding:2px 4px; border:1px solid #dce8dc; border-radius:4px; background:#fff; color:#294b38; font-size:8px; font-weight:900; white-space:nowrap; }
    .chair-faculty-view .faculty-schedule-class-card > span { color:#53675a; font-size:9px; font-weight:650; line-height:1.3; overflow-wrap:anywhere; }
    .chair-faculty-view .faculty-schedule-class-card small { color:#708078; font-size:8px; line-height:1.3; overflow-wrap:anywhere; }
    .chair-faculty-view .faculty-schedule-preview-empty { padding:30px 16px; color:#65756a; font-size:12px; text-align:center; }
    .chair-faculty-view .faculty-schedule-load-message { padding:36px 18px; color:#53675a; font-size:13px; text-align:center; }
    .chair-faculty-view .faculty-summary-metrics { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:9px; margin:0 0 13px; }
    .chair-faculty-view .faculty-summary-metrics article { display:grid; gap:6px; padding:12px 14px; border:1px solid #dfe7dd; border-radius:10px; background:#fff; }
    .chair-faculty-view .faculty-summary-metrics span { color:#64746a; font-size:10px; font-weight:750; }
    .chair-faculty-view .faculty-summary-metrics strong { color:#245940; font-size:22px; font-weight:900; line-height:1; }
    .chair-faculty-view .faculty-summary-table-wrap { max-height:calc(100vh - 270px); overflow:auto; border:1px solid #dfe7dd; border-radius:12px; background:#fff; }
    .chair-faculty-view .faculty-summary-table { width:100%; min-width:780px; border-collapse:separate; border-spacing:0; }
    .chair-faculty-view .faculty-summary-table th { position:sticky; top:0; z-index:2; padding:11px 13px; background:#245940; color:#fff; font-size:10px; font-weight:850; text-align:left; }
    .chair-faculty-view .faculty-summary-table td { padding:12px 13px; border-bottom:1px solid #e7ece5; color:#40574a; font-size:11px; vertical-align:top; }
    .chair-faculty-view .faculty-summary-table tbody tr:nth-child(even) { background:#fafcf9; }
    .chair-faculty-view .faculty-summary-table td > strong,.chair-faculty-view .faculty-summary-table td > span { display:block; }
    .chair-faculty-view .faculty-summary-table td > strong { color:#244b35; font-size:11px; font-weight:850; }
    .chair-faculty-view .faculty-summary-table td > span { margin-top:4px; color:#708078; line-height:1.45; }
    .chair-faculty-view .faculty-summary-meeting { display:flex; align-items:center; flex-wrap:wrap; gap:5px 8px; margin:0 0 6px; padding:7px 9px; border:1px solid #dfe9de; border-radius:8px; background:#f4f8f2; }
    .chair-faculty-view .faculty-summary-meeting:last-child { margin-bottom:0; }
    .chair-faculty-view .faculty-summary-meeting strong { color:#315b43; font-size:10px; font-weight:850; }
    .chair-faculty-view .faculty-summary-meeting span { color:#43594d; font-size:10px; font-weight:750; }
    .chair-faculty-view .faculty-summary-meeting small { flex-basis:100%; color:#718078; font-size:9px; }
    .chair-faculty-view .faculty-summary-count { display:inline-grid !important; width:32px; height:32px; place-items:center; margin:0 !important; border-radius:50%; background:#eaf4e8; color:#245940 !important; font-size:13px; font-weight:900; }
    .chair-faculty-view .faculty-summary-count.is-empty { background:#f0f2ef; color:#8a948d !important; }
    .chair-faculty-view .faculty-summary-unscheduled { color:#89948c; font-size:10px; font-style:italic; }
    .chair-faculty-view .faculty-summary-empty { padding:36px 18px; border:1px dashed #d7e1d7; border-radius:12px; background:#fff; color:#637268; font-size:13px; text-align:center; }
    body.faculty-schedule-modal-open { overflow:hidden; }
    .chair-faculty-view .faculty-directory-item.imported { display:flex; min-width:0; flex-direction:column; gap:7px; }
    .chair-faculty-view .faculty-directory-item.imported .faculty-card { flex:1; width:100%; }
    .chair-faculty-view .faculty-remove-import-form button { display:inline-flex; align-items:center; gap:6px; min-height:34px; padding:0 10px; border:1px solid #ecd2ce; border-radius:9px; background:#fff8f7; color:#9b4c43; font:800 11px/1.2 var(--wr-body); cursor:pointer; }
    .chair-faculty-view .faculty-import-tile { display:grid; align-content:center; justify-items:center; gap:8px; min-height:135px; padding:14px; border:1px dashed #a9c0aa; border-radius:14px; background:#f8fbf7; color:#315b43; text-align:center; cursor:pointer; transition:background .16s ease,border-color .16s ease,transform .16s ease; }
    .chair-faculty-view .faculty-import-tile:hover { transform:translateY(-2px); border-color:#668b6d; background:#f0f7ef; }
    .chair-faculty-view .faculty-import-plus { display:grid; width:40px; height:40px; place-items:center; border-radius:13px; background:#e7f0e5; color:#285940; font-size:17px; }
    .chair-faculty-view .faculty-import-tile strong { color:#294437; font-size:14px; font-weight:850; }
    .chair-faculty-view .faculty-import-tile > span:last-child { color:#627268; font-size:11px; font-weight:600; line-height:1.45; }
    .chair-faculty-view .empty { padding:18px; border:1px dashed #d7e1d7; border-radius:12px; background:#f8faf6; color:#637268; font-size:13px; font-weight:600; line-height:1.55; }
    .chair-faculty-view .faculty-directory-empty { grid-column:1/-1; }

    .chair-faculty-view .subject-section { min-width:0; }
    .chair-faculty-view .profile { display:flex; align-items:center; gap:13px; margin:0 0 19px; padding:16px; border:1px solid #e2e9e1; border-radius:14px; background:linear-gradient(110deg,#f5f8f3,#fff); }
    .chair-faculty-view .profile .faculty-icon { flex:0 0 48px; width:48px; height:48px; margin:0; border-radius:14px; font-size:19px; }
    .chair-faculty-view .profile-name { color:#173b33; font-size:19px; font-weight:850; line-height:1.3; }
    .chair-faculty-view .profile-department { margin-top:5px; color:#647268; font-size:12px; font-weight:650; }
    .chair-faculty-view .profile-department strong { color:#285940 !important; font-weight:850; }
    .chair-faculty-view .subject-section > h3 { margin:0 0 12px !important; color:#173b33 !important; font-size:19px; font-weight:850; }
    .chair-faculty-view .table-wrapper { width:100%; overflow:auto; border:1px solid #e2e9e1; border-radius:12px; }
    .chair-faculty-view .subject-table { width:100%; min-width:850px; border-collapse:separate; border-spacing:0; background:#fff; }
    .chair-faculty-view .subject-table th { padding:13px 12px; border-bottom:1px solid #dfe7dd; background:#f3f6f1; color:#385344; text-align:left; font-size:12px; font-weight:850; letter-spacing:.04em; white-space:nowrap; }
    .chair-faculty-view .subject-table td { padding:13px 12px; border-bottom:1px solid #edf0eb; color:#34483c; vertical-align:top; font-size:13px; font-weight:600; line-height:1.55; }
    .chair-faculty-view .subject-table tbody tr:nth-child(even) { background:#fbfcfa; }
    .chair-faculty-view .subject-table tbody tr:last-child td { border-bottom:0; }
    .chair-faculty-view .subject-table td strong { color:#285940; font-size:13px; font-weight:850; }
    .chair-faculty-view .faculty-subject-original { background:#fafbf8 !important; }
    .chair-faculty-view .faculty-original-label { display:inline-flex; align-items:center; gap:5px; margin-top:5px; padding:4px 7px; border:1px solid #e6ddc4; border-radius:8px; background:#fbf8ee; color:#806a39; font-size:10px; font-weight:800; line-height:1.4; }
    .chair-faculty-view .subject-table .faculty-original-label { display:flex; width:max-content; max-width:260px; }
    .chair-faculty-view .btn { display:inline-flex; align-items:center; justify-content:center; gap:6px; min-height:37px; padding:0 11px; border:1px solid transparent; border-radius:9px; font:800 11px/1.2 var(--wr-body); text-decoration:none; white-space:nowrap; cursor:pointer; transition:transform .16s ease,background .16s ease; }
    .chair-faculty-view .btn:hover { transform:translateY(-1px); }
    .chair-faculty-view .btn.green { border-color:#24543e; background:#24543e; color:#fff; }
    .chair-faculty-view .btn.green:hover { background:#173b33; }
    .chair-faculty-view .btn.yellow { border-color:#e6ddc4; background:#fbf8ee; color:#806a39; }
    .chair-faculty-view .btn.red { border-color:#ecd2ce; background:#fff8f7; color:#9b4c43; }
    .chair-faculty-view .btn.gray { border-color:#dce5dc; background:#f4f6f3; color:#53655a; }
    .chair-faculty-view .add-subject-title { margin:20px 0 11px; color:#173b33; font-size:18px; font-weight:850; scroll-margin-top:110px; }
    .chair-faculty-view .subject-form { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:13px; padding:17px; border:1px solid #e2e9e1; border-radius:14px; background:#f9fbf8; }
    .chair-faculty-view .subject-form > div { min-width:0; }
    .chair-faculty-view .subject-form label,.chair-faculty-view .modal-content label,.chair-faculty-view .faculty-import-dialog label { display:block; margin:0 0 6px; color:#43594d; font-size:12px; font-weight:800; }
    .chair-faculty-view .subject-form input,.chair-faculty-view .subject-form select,.chair-faculty-view .modal-content input,.chair-faculty-view .modal-content select,.chair-faculty-view .faculty-import-dialog select { width:100%; min-height:45px; padding:0 12px; border:1px solid #d7e0d7; border-radius:10px; background:#fff; color:#273f32; font:600 14px/1.4 var(--wr-body); }
    .chair-faculty-view .subject-form input[readonly],.chair-faculty-view .modal-content input[readonly] { background:#f0f4ee; color:#51675a; }
    .chair-faculty-view .subject-form input:focus,.chair-faculty-view .subject-form select:focus,.chair-faculty-view .modal-content input:focus,.chair-faculty-view .modal-content select:focus,.chair-faculty-view .faculty-import-dialog select:focus { outline:3px solid rgba(49,104,76,.15); border-color:#648a70; }
    .chair-faculty-view #facultyCourseSearchStatus { color:#40574a; font-size:13px; font-weight:600; }
    .chair-faculty-view .faculty-cohort-help { margin:0 0 10px; color:#64746a; font-size:12px; line-height:1.45; }
    .chair-faculty-view .faculty-cohort-options { display:grid; gap:7px; max-height:190px; overflow:auto; }
    .chair-faculty-view .faculty-cohort-option { display:grid; grid-template-columns:18px minmax(0,1fr); align-items:start; gap:10px; padding:9px 11px; border:1px solid #e2eae2; border-radius:9px; background:#fff; color:#34483b; font-size:12px; line-height:1.45; cursor:pointer; }
    .chair-faculty-view .faculty-cohort-option:hover { border-color:#adc5b0; background:#fbfdf9; }
    .chair-faculty-view .faculty-cohort-option input[type="checkbox"] { appearance:auto; width:17px !important; min-width:17px; height:17px !important; min-height:17px !important; margin:1px 0 0 !important; padding:0 !important; accent-color:#28583f; cursor:pointer; }
    .chair-faculty-view .faculty-cohort-option span { min-width:0; }
    .chair-faculty-view .faculty-shared-cohorts { margin-top:7px; }
    .chair-faculty-view .faculty-shared-cohorts summary { display:inline-flex; align-items:center; gap:6px; min-height:27px; padding:4px 9px; border:1px solid #d7e5d5; border-radius:7px; background:#f3f8f1; color:#315b43; font-size:10px; font-weight:800; line-height:1.3; cursor:pointer; list-style:none; }
    .chair-faculty-view .faculty-shared-cohorts summary::-webkit-details-marker { display:none; }
    .chair-faculty-view .faculty-shared-cohorts summary::before { content:"+"; font-size:14px; line-height:1; }
    .chair-faculty-view .faculty-shared-cohorts[open] summary::before { content:"−"; }
    .chair-faculty-view .faculty-shared-cohort-list { display:grid; gap:5px; margin:7px 0 0; padding:0; list-style:none; }
    .chair-faculty-view .faculty-shared-cohort-list li { padding:6px 8px; border:1px solid #e8eee6; border-radius:7px; background:#fbfcfa; color:#596b5f; font-size:10px; line-height:1.4; }
    .chair-faculty-view .schedule-link-wrapper { margin-top:14px; }

    .chair-faculty-view .modal,.chair-faculty-view .faculty-import-modal,.chair-faculty-view .faculty-feedback-overlay { position:fixed; inset:0; z-index:13000; align-items:center; justify-content:center; padding:20px; background:rgba(15,32,25,.55); backdrop-filter:blur(4px); }
    .chair-faculty-view .modal { display:none; overflow:auto; }
    .chair-faculty-view .modal[style*="display: block"] { display:flex !important; }
    .chair-faculty-view .modal-content,.chair-faculty-view .faculty-import-dialog,.chair-faculty-view .faculty-feedback-dialog { position:relative; width:min(540px,100%); max-height:calc(100vh - 40px); overflow:auto; padding:24px; border:1px solid #e1e8df; border-radius:18px; background:#fff; box-shadow:0 24px 70px rgba(0,0,0,.22); }
    .chair-faculty-view .modal-content h3,.chair-faculty-view .faculty-import-dialog h2,.chair-faculty-view .faculty-feedback-dialog h2 { margin:0 0 14px; color:#173b33 !important; font-size:21px; font-weight:850; }
    .chair-faculty-view .modal-content input,.chair-faculty-view .modal-content select { margin-bottom:13px; }
    .chair-faculty-view .faculty-import-modal { display:none; }
    .chair-faculty-view .faculty-import-modal.is-open { display:flex; }
    .chair-faculty-view .faculty-import-dialog { width:min(570px,100%); }
    .chair-faculty-view .faculty-import-dialog-head { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; margin-bottom:18px; }
    .chair-faculty-view .faculty-import-dialog-head h2 { margin-bottom:6px; }
    .chair-faculty-view .faculty-import-dialog-head p { margin:0; color:#5b6d61; font-size:13px; font-weight:550; line-height:1.55; }
    .chair-faculty-view .faculty-import-close { display:grid; flex:0 0 38px; width:38px; height:38px; place-items:center; border:1px solid #e2e9e1; border-radius:11px; background:#f7f9f6; color:#496052; font-size:15px; cursor:pointer; }
    .chair-faculty-view .faculty-import-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:18px; }
    .chair-faculty-view .faculty-import-actions button,.chair-faculty-view .faculty-feedback-dialog > button { min-height:40px; padding:0 14px; border:1px solid #dce5dc; border-radius:10px; background:#f4f6f3; color:#53655a; font:800 12px/1.2 var(--wr-body); cursor:pointer; }
    .chair-faculty-view .faculty-import-actions button[type=submit],.chair-faculty-view .faculty-feedback-dialog > button { border-color:#24543e; background:#24543e; color:#fff; }
    .chair-faculty-view .faculty-import-actions button:disabled { opacity:.55; cursor:not-allowed; }
    body.portal-shell.chair-portal .faculty-feedback-overlay { position:fixed; inset:0; z-index:13000; display:flex; align-items:center; justify-content:center; padding:20px; background:rgba(15,32,25,.55); backdrop-filter:blur(4px); }
    body.portal-shell.chair-portal .faculty-feedback-dialog { position:relative; display:grid; gap:14px; width:min(540px,100%); max-height:calc(100vh - 40px); overflow:auto; padding:24px; border:1px solid #e1e8df; border-radius:18px; background:#fff; box-shadow:0 24px 70px rgba(0,0,0,.22); text-align:center; }
    body.portal-shell.chair-portal .faculty-feedback-dialog h2 { margin:0; color:#173b33; font-size:21px; font-weight:850; }
    body.portal-shell.chair-portal .faculty-feedback-dialog .message { margin:0; padding:14px 17px; border:1px solid; border-radius:12px; text-align:left; font-size:14px; font-weight:700; line-height:1.5; }
    body.portal-shell.chair-portal .faculty-feedback-dialog .message.success { border-color:#b9d9bd; background:#edf7ee; color:#28603a; }
    body.portal-shell.chair-portal .faculty-feedback-dialog .message.error { border-color:#edc8c2; background:#fff3f1; color:#8f3e36; }
    body.portal-shell.chair-portal .faculty-feedback-dialog > button { justify-self:center; min-width:95px; }

    @media(max-width:1100px) {
        body.portal-shell.chair-portal { --chair-sidebar-width:226px; --chair-shell-gutter:22px; }
        body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
        body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); }
        body.portal-shell.chair-portal .main-layout > .content.chair-faculty-view { padding-right:24px; padding-left:24px; }
        .chair-faculty-view .faculty-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .chair-faculty-view .faculty-setup-metrics { grid-template-columns:repeat(3,minmax(0,1fr)); }
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
        body.portal-shell.chair-portal .main-layout > .content.chair-faculty-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .chair-faculty-view .faculty-setup-hero { align-items:flex-start; flex-direction:column; min-height:0; gap:17px; padding:27px 23px; }
        .chair-faculty-view .faculty-setup-department { width:100%; }
        .chair-faculty-view .faculty-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:540px) {
        .chair-faculty-view .faculty-setup-metrics { grid-template-columns:minmax(0,1fr); gap:8px; }
        .chair-faculty-view .faculty-setup-metric { min-height:75px; }
        .chair-faculty-view .faculty-grid,.chair-faculty-view .subject-form { grid-template-columns:minmax(0,1fr); }
        .chair-faculty-view .section { padding:16px; }
        .chair-faculty-view .department-info { align-items:flex-start; flex-direction:column; }
        .chair-faculty-view .subject-form > div[style*="grid-column"] { grid-column:1/-1; }
        .chair-faculty-view .modal-content,.chair-faculty-view .faculty-import-dialog { padding:20px; }
    }
</style>
@php
    $facultyMembers = collect($department->users ?? [])->where('role', 'faculty');
    $facultyMemberCount = $facultyMembers->count();
    $assignedSubjectCount = $facultyMembers->sum(function ($facultyMember) {
        return $facultyMember->facultySubjects->count();
    }) + collect($importedFaculty ?? [])->sum(function ($facultyMember) {
        return $facultyMember->departmentSubjects->count();
    });
    $importedFacultyCount = collect($importedFaculty ?? [])->count();
@endphp
<div class="wrapper">

    

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content chair-workspace-redesign chair-faculty-redesign chair-faculty-view">

          

            <div class="page faculty-setup-page">


                <!-- =================================================
                     TITLE
                ================================================== -->

                <header class="faculty-setup-hero">
                    <div class="faculty-setup-hero-copy">
                        <div class="faculty-setup-eyebrow"><i class="fa-solid fa-users-gear" aria-hidden="true"></i> COLLEGE WORKSPACE</div>
                        <h1>Instructors & Subjects</h1>
                        <p>Manage instructor access and course assignments for your college.</p>
                    </div>
                    <div class="faculty-setup-department">
                        <span class="faculty-setup-department-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                        <div><small>YOUR COLLEGE</small><strong>{{ $department->code ?: ($department->name ?? 'College') }}@if($department->code && $department->name)<span> · {{ $department->name }}</span>@endif</strong></div>
                    </div>
                </header>

                <section class="faculty-setup-metrics" aria-label="Instructor setup summary">
                    <div class="faculty-setup-metric"><span class="faculty-setup-metric-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span><div class="faculty-setup-metric-copy"><strong>{{ $facultyMemberCount }}</strong><span>College instructors</span></div></div>
                    <div class="faculty-setup-metric"><span class="faculty-setup-metric-icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span><div class="faculty-setup-metric-copy"><strong>{{ $assignedSubjectCount }}</strong><span>Assigned subjects</span></div></div>
                    <div class="faculty-setup-metric"><span class="faculty-setup-metric-icon"><i class="fa-solid fa-user-plus" aria-hidden="true"></i></span><div class="faculty-setup-metric-copy"><strong>{{ $importedFacultyCount }}</strong><span>Added instructors</span></div></div>
                </section>


                <!-- =================================================
                     SUCCESS
                ================================================== -->

                @if(session('success'))

                    <div class="message success">
                        {{ session('success') }}
                    </div>

                @endif


                <!-- =================================================
                     ERROR
                ================================================== -->

                @if(session('error'))

                    <div class="message error">
                        {{ session('error') }}
                    </div>

                @endif


                <!-- =================================================
                     VALIDATION ERRORS
                ================================================== -->

                @if($errors->any())

                    <div class="message error">

                        <ul style="margin:0;padding-left:20px;">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <!-- =================================================
                     INSTRUCTORS
                ================================================== -->

                <div class="section">

                    <div class="section-title">
                        Instructors <span class="faculty-total-badge">{{ $facultyMemberCount + $importedFacultyCount }}</span>
                    </div>

                    <p class="faculty-directory-note">College instructors and imported instructors appear together. Each card is labeled by its source.</p>
                    <div class="faculty-grid">
                        @foreach($department->users->where('role', 'faculty') as $faculty)
                            <article class="faculty-card {{ $selectedFaculty && $selectedFaculty->id == $faculty->id ? 'active' : '' }}">

                                <div class="faculty-directory-origin original"><i class="fa-solid fa-building" aria-hidden="true"></i> College instructors</div>
                                <div class="faculty-card-person">
                                @if($faculty->profile_photo_path)
                                    <img class="faculty-icon" src="{{ $faculty->profile_photo_url }}" alt="{{ $faculty->name }} profile photo" onerror="this.hidden=true; this.nextElementSibling.hidden=false">
                                    <span class="faculty-icon" aria-hidden="true" hidden><i class="fa-solid fa-user"></i></span>
                                @else
                                    <div class="faculty-icon"><i class="fa-solid fa-user" aria-hidden="true"></i></div>
                                @endif
                                    <div>
                                        <div class="faculty-name">{{ $faculty->name }}</div>
                                        <div class="faculty-count">{{ $faculty->facultySubjects->count() }} assigned subject(s)</div>
                                    </div>
                                </div>
                                <div class="faculty-card-actions" aria-label="{{ $faculty->name }} actions">
                                    <button type="button" class="faculty-card-action" data-faculty-schedule-url="{{ route('chair.faculty.schedule', $faculty->id) }}" title="View {{ $faculty->name }} schedule spreadsheet"><i class="fa-regular fa-calendar" aria-hidden="true"></i><span>View Schedule Subject</span></button>
                                    <button type="button" class="faculty-card-action" data-faculty-summary-url="{{ route('chair.faculty.schedule-summary', $faculty->id) }}" title="View {{ $faculty->name }} schedule summary"><i class="fa-solid fa-chart-column" aria-hidden="true"></i><span>View Schedule Summary</span></button>
                                    <a class="faculty-card-action primary" href="{{ route('chair.setFaculty', ['faculty' => $faculty->id]) }}#faculty-add-subject" title="Manage assigned subjects"><i class="fa-solid fa-layer-group" aria-hidden="true"></i><span>Manage Assigned Subjects</span></a>
                                </div>
                            </article>
                        @endforeach

                        @foreach($importedFaculty as $faculty)
                            <article class="faculty-directory-item imported">
                                <div class="faculty-card {{ $selectedFaculty && $selectedFaculty->id == $faculty->id ? 'active' : '' }}">
                                    <div class="faculty-directory-origin imported"><i class="fa-solid fa-arrow-down-to-bracket" aria-hidden="true"></i> Imported · {{ $faculty->department->code ?? $faculty->department->name ?? 'Other college' }}</div>
                                    <div class="faculty-card-person">
                                    @if($faculty->profile_photo_path)
                                        <img class="faculty-icon" src="{{ $faculty->profile_photo_url }}" alt="{{ $faculty->name }} profile photo" onerror="this.hidden=true; this.nextElementSibling.hidden=false">
                                        <span class="faculty-icon" aria-hidden="true" hidden><i class="fa-solid fa-user"></i></span>
                                    @else
                                        <div class="faculty-icon"><i class="fa-solid fa-user" aria-hidden="true"></i></div>
                                    @endif
                                        <div>
                                            <div class="faculty-name">{{ $faculty->name }}</div>
                                            <div class="faculty-count">{{ $faculty->departmentSubjects->count() }} college subject(s) · {{ $faculty->facultySubjects->count() - $faculty->departmentSubjects->count() }} original</div>
                                        </div>
                                    </div>
                                    <div class="faculty-card-actions" aria-label="{{ $faculty->name }} actions">
                                        <button type="button" class="faculty-card-action" data-faculty-schedule-url="{{ route('chair.faculty.schedule', $faculty->id) }}" title="View {{ $faculty->name }} schedule spreadsheet"><i class="fa-regular fa-calendar" aria-hidden="true"></i><span>View Schedule Subject</span></button>
                                        <button type="button" class="faculty-card-action" data-faculty-summary-url="{{ route('chair.faculty.schedule-summary', $faculty->id) }}" title="View {{ $faculty->name }} schedule summary"><i class="fa-solid fa-chart-column" aria-hidden="true"></i><span>View Schedule Summary</span></button>
                                        <a class="faculty-card-action primary" href="{{ route('chair.setFaculty', ['faculty' => $faculty->id]) }}#faculty-add-subject" title="Manage assigned subjects"><i class="fa-solid fa-layer-group" aria-hidden="true"></i><span>Manage Assigned Subjects</span></a>
                                    </div>
                                </div>
                                <form
                                    action="{{ route('chair.faculty.import.remove', $faculty->id) }}"
                                    method="POST"
                                    class="faculty-remove-import-form"
                                    data-confirm-remove="Remove this instructor from your imported list? Their account will not be deleted."
                                    onsubmit="return confirm('Remove this instructor from your imported list? Their account will not be deleted.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"><i class="fa-solid fa-link-slash" aria-hidden="true"></i> Remove import</button>
                                </form>
                            </article>
                        @endforeach

                        @if($facultyMemberCount === 0 && $importedFacultyCount === 0)
                            <div class="empty faculty-directory-empty">No instructors are assigned to {{ $department->name }} or imported yet.</div>
                        @endif

                        <button type="button" class="faculty-import-tile" id="openFacultyImportModal" aria-haspopup="dialog" aria-controls="facultyImportModal">
                            <span class="faculty-import-plus"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
                            <strong>Add instructors</strong>
                            <span>Add a colleague from another college</span>
                        </button>

                    </div>

                </div>

<div class="faculty-import-modal" id="facultyImportModal" role="dialog" aria-modal="true" aria-labelledby="facultyImportTitle" aria-hidden="true">
    <div class="faculty-import-dialog">
        <div class="faculty-import-dialog-head">
            <div>
                <h2 id="facultyImportTitle">Import instructor</h2>
                <p>Choose a colleague from another college. Their existing subject assignments will remain with their home college.</p>
            </div>
            <button type="button" class="faculty-import-close" id="closeFacultyImportModal" aria-label="Close dialog"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
        </div>
        <form action="{{ route('chair.faculty.import') }}" method="POST">
            @csrf
            <label for="faculty_id">Instructor</label>
            <select name="faculty_id" id="faculty_id" required {{ $availableFaculty->isEmpty() ? 'disabled' : '' }}>
                <option value="">{{ $availableFaculty->isEmpty() ? 'No instructors available to import' : 'Select an instructor' }}</option>
                @foreach($availableFaculty as $availableMember)
                    <option value="{{ $availableMember->id }}">{{ $availableMember->name }} — {{ $availableMember->department->code ?? $availableMember->department->name ?? 'Other college' }}</option>
                @endforeach
            </select>
            <div class="faculty-import-actions">
                <button type="button" id="cancelFacultyImport">Cancel</button>
                <button type="submit" {{ $availableFaculty->isEmpty() ? 'disabled' : '' }}>Import instructor</button>
            </div>
        </form>
    </div>
</div>

<div class="faculty-schedule-overlay" id="facultyScheduleOverlay" role="dialog" aria-modal="true" aria-label="Instructor schedule spreadsheet" aria-hidden="true">
    <section class="faculty-schedule-dialog" id="facultyScheduleDialog" tabindex="-1">
        <div class="faculty-schedule-load-message" role="status">Choose an instructor to load their schedule.</div>
    </section>
</div>


                <!-- =================================================
                     MASTER CURRICULUM LIST

                     The retired curriculum view is no longer part of this flow.
                ================================================== -->

                <!-- =================================================
                     SELECTED FACULTY
                ================================================== -->

                @if($selectedFaculty)

                    <div class="section">

                        <div class="subject-section">


                            <!-- =================================================
                                 FACULTY PROFILE
                            ================================================== -->

                            <div class="profile">

                                <div class="faculty-icon"><i class="fa-solid fa-user" aria-hidden="true"></i></div>


                                <div>

                                    <div class="profile-name">
                                        {{ $selectedFaculty->name }}
                                    </div>


                                    <div class="profile-department">

                                        College:

                                        <strong
                                            style="color:#2e7d32;"
                                        >

                                            {{
                                                $selectedFaculty
                                                    ->department
                                                    ->code
                                                ?? ''
                                            }}

                                            @if(
                                                $selectedFaculty->department
                                            )

                                                -

                                                {{
                                                    $selectedFaculty
                                                        ->department
                                                        ->name
                                                }}

                                            @endif

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            <!-- =================================================
                                 ASSIGNED SUBJECTS
                            ================================================== -->

                            <h3 style="color:#2e7d32;margin-top:0;">
                                Assigned Subjects
                            </h3>


                            @if(
                                $selectedFaculty
                                    ->facultySubjects
                                    ->count()
                            )

                                <div class="table-wrapper">

                                    <table class="subject-table">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Course Code
                                                </th>

                                                <th>
                                                    Course Description
                                                </th>

                                                <th>
                                                    Year Level
                                                </th>

                                                <th>Students</th>

                                                <th>
                                                     Major
                                                </th>

                                                 <th>
                                                     Program
                                                </th>

                                                <th>
                                                    Actions
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach(
                                                $selectedFaculty
                                                    ->facultySubjects
                                                as $facultySubject
                                            )
                                                @php
                                                    $subjectProgramDepartmentId = $facultySubject->subjectCatalog?->program?->department_id;
                                                    $isImportedSelectedFaculty = (int) $selectedFaculty->department_id !== (int) $department->id;
                                                    $isOriginalDepartmentSubject = $isImportedSelectedFaculty && (
                                                        $subjectProgramDepartmentId !== null
                                                            ? (int) $subjectProgramDepartmentId !== (int) $department->id
                                                            : (!$selectedFaculty->imported_at || !$facultySubject->created_at || $facultySubject->created_at->timestamp < strtotime((string) $selectedFaculty->imported_at))
                                                    );
                                                @endphp
                                                <tr class="{{ $isOriginalDepartmentSubject ? 'faculty-subject-original' : '' }}">

                                                  <td>
    <strong>
        {{ $facultySubject->course_code }}
    </strong>
    @if($isOriginalDepartmentSubject)
        <span class="faculty-original-label"><i class="fa-solid fa-lock" aria-hidden="true"></i> Original college subject · read only</span>
    @endif
</td>

<td>
    {{ $facultySubject->subject }}
</td>

<td>
    {{ $facultySubject->year_level ?? '-' }}
</td>

<td>
    @if(!$isOriginalDepartmentSubject)
        <form action="{{ route('chair.faculty.subjects.update', $facultySubject->id) }}" method="POST" style="display:flex;align-items:center;gap:5px;min-width:125px;">
            @csrf
            @method('PUT')
            <input type="number" name="student_count" min="0" max="10000" step="1" required value="{{ $facultySubject->student_count ?? 0 }}" aria-label="Number of students in {{ $facultySubject->course_code }}" style="width:75px;padding:6px;border:1px solid #d5dfd7;border-radius:6px;">
            <button type="submit" class="btn green" style="padding:6px 8px;">Save</button>
        </form>
    @else
        {{ $facultySubject->student_count ?? 0 }}
    @endif
</td>

<td>
    {{ $facultySubject->major ?? '-' }}
</td>

<td>
    {{ $facultySubject->program ?? '-' }}
    @if($facultySubject->sharedCohorts->isNotEmpty())
        @php
            $sharedCohortLabels = $facultySubject->sharedCohorts
                ->map(fn ($cohort) => trim(($cohort->program?->code ?: $cohort->program?->name ?: 'Program')
                    . ($cohort->program?->major ? ' · Major: ' . $cohort->program->major : '')
                    . ($cohort->year_level ? ' · ' . $cohort->year_level : '')
                    . ($cohort->semester ? ' · ' . $cohort->semester : '')))
                ->filter()
                ->unique()
                ->values();
        @endphp
        @if($sharedCohortLabels->isNotEmpty())
            <details class="faculty-shared-cohorts">
                <summary>Shared with {{ $sharedCohortLabels->count() }} additional {{ $sharedCohortLabels->count() === 1 ? 'group' : 'groups' }}</summary>
                <ul class="faculty-shared-cohort-list">
                    @foreach($sharedCohortLabels as $cohortLabel)
                        <li>{{ $cohortLabel }}</li>
                    @endforeach
                </ul>
            </details>
        @endif
    @endif
</td>

                                                    <td>
                                                        @if(!$isOriginalDepartmentSubject)

                                                        <!-- EDIT -->

                                                        @if(!$facultySubject->subject_id)
                                                            <button
                                                                type="button"
                                                                class="btn yellow"
                                                                onclick="editSubject(
    {{ $facultySubject->id }},
    @js($facultySubject->course_code),
    @js($facultySubject->subject),
    @js($facultySubject->year_level),
    @js($facultySubject->major),
    @js($facultySubject->program),
    @js($facultySubject->student_count ?? 0),
)"
                                                            >Edit</button>
                                                        @else
                                                            <span class="faculty-original-label">Catalog linked · edit in Manage Subjects</span>
                                                        @endif


                                                        <!-- REMOVE -->

                                                        <form
                                                            action="{{ route(
                                                                'chair.faculty.subjects.delete',
                                                                $facultySubject->id
                                                            ) }}"
                                                            method="POST"
                                                            data-confirm-delete="Remove this subject assignment from the instructor?"
                                                            style="
                                                                display:inline;
                                                            "
                                                        >

                                                            @csrf

                                                            @method('DELETE')


                                                            <button
                                                                type="submit"
                                                                class="btn red"
                                                                onclick="
                                                                    return confirm(
                                                                        'Remove this subject?'
                                                                    )
                                                                "
                                                            >
                                                                Remove
                                                            </button>

                                                        </form>
                                                        @else
                                                            <span class="faculty-original-label">View only</span>
                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            @else

                                <div class="empty">

                                    No subjects assigned yet.

                                </div>

                            @endif


                            <!-- =================================================
                                 ADD SUBJECT
                            ================================================== -->

                            <div class="add-subject-title" id="faculty-add-subject">
                                Add Subject
                            </div>


                            <form
                                action="{{ route(
                                    'chair.faculty.subjects.store'
                                ) }}"
                                method="POST"
                                class="subject-form"
                            >

                                @csrf


                                <input
                                    type="hidden"
                                    name="faculty_id"
                                    value="{{ $selectedFaculty->id }}"
                                >

<div style="grid-column:1 / -1;">
    <label for="facultyCourseCode">Course code</label>
    <input type="text" name="course_code" id="facultyCourseCode" placeholder="Type a course code to search" autocomplete="off" required>
    <input type="hidden" name="subject_id" id="facultySubjectId">
    <div id="facultySubjectChoiceWrap" hidden style="margin-top:14px;">
        <label for="facultySubjectChoice">Choose the correct subject</label>
        <select id="facultySubjectChoice" style="width:100%;padding:11px;border:1px solid #bdcddd;border-radius:8px;background:#fff;font-size:15px;">
            <option value="">Choose a year level</option>
        </select>
    </div>
    <div id="facultyCohortChoiceWrap" hidden style="margin-top:14px;padding:14px;border:1px solid #d9e5da;border-radius:12px;background:#f7faf7;">
        <strong style="display:block;color:#294533;font-size:13px;margin-bottom:5px;">Also assign this course to these groups</strong>
        <p id="facultyCohortChoiceHelp" class="faculty-cohort-help">Also assign this same course to any additional groups this instructor teaches.</p>
        <div id="facultyCohortChoices" class="faculty-cohort-options"></div>
    </div>
    <p id="facultyCourseSearchStatus" role="status" aria-live="polite" style="margin:8px 0;color:#40566c;font-size:14px;min-height:20px;">Enter a course code already added in Manage Subjects.</p>
    <div style="max-width:260px;margin-top:12px;">
        <label for="facultyStudentCount">Number of students</label>
        <input type="number" name="student_count" id="facultyStudentCount" min="0" max="10000" step="1" required value="{{ old('student_count') }}" placeholder="Enter enrolled students">
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;margin-top:14px;">
        <div><label for="facultySubjectTitleDisplay">Course title</label><input id="facultySubjectTitleDisplay" type="text" readonly aria-readonly="true" placeholder="Filled from Manage Subjects"></div>
        <div><label for="facultyYearLevelDisplay">Year level</label><input id="facultyYearLevelDisplay" type="text" readonly aria-readonly="true" placeholder="Filled from subject catalog"></div>
        <div><label for="facultySemesterDisplay">Semester</label><input id="facultySemesterDisplay" type="text" readonly aria-readonly="true" placeholder="Filled from subject catalog"></div>
        <div><label for="facultyProgramDisplay">Program</label><input id="facultyProgramDisplay" type="text" readonly aria-readonly="true" placeholder="Filled from subject catalog"></div>
        <div><label for="facultyMajorDisplay">Major</label><input id="facultyMajorDisplay" type="text" readonly aria-readonly="true" placeholder="No major specified"></div>
        <div><label for="facultySubjectTypeDisplay">Subject type</label><input id="facultySubjectTypeDisplay" type="text" readonly aria-readonly="true" placeholder="Lec or Lab"></div>
    </div>
    <button type="submit" class="btn green" id="assignCatalogSubjectButton" disabled style="margin-top:16px;">Assign Subject to Instructor</button>
</div>

                            </form>


                            <!-- =================================================
                                 GO TO SET SCHEDULE
                            ================================================== -->

                            <div class="schedule-link-wrapper">

                                <a
                                    href="{{ route(
                                        'chair.setschedule'
                                    ) }}"
                                    class="btn green"
                                >
                                    Set Classroom Schedule →
                                </a>

                            </div>


                        </div>

                    </div>

                @endif


                <!-- =================================================
                     EDIT SUBJECT MODAL
                ================================================== -->

                <div
                    id="editSubjectModal"
                    class="modal"
                >

                    <div class="modal-content">

                        <h3 style="color:#2e7d32;">
                            Edit Subject
                        </h3>


                        <form
                            id="editSubjectForm"
                            method="POST"
                        >

                            @csrf

                            @method('PUT')


                            <label>
                                Course Code
                            </label>

                            <input
                                type="text"
                                id="editSubjectCode"
                                name="course_code"
                                required
                            >


                            <br><br>


                            <label>
                                Units
                            </label>

                            <input
                                type="number"
                                id="editSubjectUnits"
                                name="units"
                                step="0.5"
                                min="0"
                                value="3"
                                required
                            >

                            <br><br>

                            <label>
                                Semester
                            </label>

                            <select
                                id="editSubjectSemester"
                                name="semester"
                                required
                            >
                                <option value="1st Semester">
                                    1st Semester
                                </option>
                                <option value="2nd Semester">
                                    2nd Semester
                                </option>
                            </select>

                            <br><br>

                            <label>
                                Subject
                            </label>

                            <input
                                type="text"
                                id="editSubjectName"
                                name="subject"
                                required
                            >


                            <br><br>


                            <label>Year Level</label>
                            <input type="text" id="editSubjectYearDisplay" placeholder="Choose a subject" readonly>
                            <input type="hidden" id="editSubjectYear" name="year_level" required>

<br><br>

<label>
    Program
</label>

<input
    type="text"
    id="editSubjectProgram"
    name="program"
    required
>

<label>
    Major <span style="color:#777;font-weight:normal;">(Optional)</span>
</label>

<input
    type="text"
    id="editSubjectMajor"
    name="major"
    placeholder="Enter major if applicable"
>

<label for="editSubjectStudentCount">Number of students</label>
<input type="number" id="editSubjectStudentCount" name="student_count" min="0" max="10000" step="1" required>

<br><br>


                            <div
                                style="
                                    margin-top:20px;
                                    display:flex;
                                    justify-content:flex-end;
                                    gap:8px;
                                "
                            >

                                <button
                                    type="button"
                                    class="btn gray"
                                    onclick="closeEditSubject()"
                                >
                                    Cancel
                                </button>


                                <button
                                    type="submit"
                                    class="btn green"
                                >
                                    Save Changes
                                </button>

                            </div>

                        </form>

                    </div>

                </div>


            </div>


            <script>

function editSubject(id, courseCode, subject, yearLevel, major, program, studentCount) {
    document.getElementById('editSubjectCode').value = courseCode || '';
    document.getElementById('editSubjectName').value = subject || '';
    document.getElementById('editSubjectYear').value = yearLevel || '';
    document.getElementById('editSubjectYearDisplay').value = yearLevel || '';
    document.getElementById('editSubjectProgram').value = program || '';
    document.getElementById('editSubjectMajor').value = major || '';
    document.getElementById('editSubjectStudentCount').value = studentCount ?? 0;

    document.getElementById('editSubjectForm').action = "{{ url('/chair/instructor/subjects') }}/" + id;
    document.getElementById('editSubjectModal').style.display = 'block';
}

function closeEditSubject() {
    const modal = document.getElementById('editSubjectModal');
    if (modal) modal.style.display = 'none';
}

function setFacultyImportModal(open) {
    const modal = document.getElementById('facultyImportModal');
    if (!modal) return;
    modal.classList.toggle('is-open', open);
    modal.setAttribute('aria-hidden', open ? 'false' : 'true');
    document.body.style.overflow = open ? 'hidden' : '';
    if (open) document.getElementById('faculty_id')?.focus();
}

document.getElementById('openFacultyImportModal')?.addEventListener('click', () => setFacultyImportModal(true));
document.getElementById('closeFacultyImportModal')?.addEventListener('click', () => setFacultyImportModal(false));
document.getElementById('cancelFacultyImport')?.addEventListener('click', () => setFacultyImportModal(false));
document.getElementById('facultyImportModal')?.addEventListener('click', event => {
    if (event.target.id === 'facultyImportModal') setFacultyImportModal(false);
});
const facultyScheduleOverlay = document.getElementById('facultyScheduleOverlay');
const facultyScheduleDialog = document.getElementById('facultyScheduleDialog');
let facultyScheduleReturnFocus = null;
function closeFacultyScheduleModal() {
    if (!facultyScheduleOverlay) return;
    facultyScheduleOverlay.classList.remove('is-open');
    facultyScheduleOverlay.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('faculty-schedule-modal-open');
    facultyScheduleReturnFocus?.focus();
}
function mergeInstructorScheduleCells() {
    const table = facultyScheduleDialog?.querySelector('.faculty-schedule-preview-scroll table');
    if (!table) return;
    const mergeCells = cells => {
        let index = 0;
        while (index < cells.length) {
            const cell = cells[index];
            const entries = cell?.querySelectorAll('.faculty-schedule-class-card') || [];
            const signature = entries.length === 1 ? entries[0].dataset.mergeSignature : '';
            if (!signature) { index++; continue; }
            let end = index + 1;
            while (end < cells.length) {
                const nextEntries = cells[end]?.querySelectorAll('.faculty-schedule-class-card') || [];
                if (nextEntries.length !== 1 || nextEntries[0].dataset.mergeSignature !== signature) break;
                end++;
            }
            if (end - index > 1) {
                cell.colSpan = end - index;
                for (let removeIndex = index + 1; removeIndex < end; removeIndex++) cells[removeIndex]?.remove();
            }
            index = end;
        }
    };
    table.querySelectorAll('tbody tr').forEach(row => {
        if (row.classList.contains('faculty-schedule-noon-break')) return;
        const cells = Array.from(row.cells);
        mergeCells([cells[1], cells[2], cells[3]]);
        mergeCells([cells[5], cells[6]]);
    });
}
async function openFacultyScheduleModal(button, summary = false) {
    if (!facultyScheduleOverlay || !facultyScheduleDialog) return;
    facultyScheduleReturnFocus = button;
    facultyScheduleDialog.innerHTML = '<div class="faculty-schedule-load-message" role="status">' + (summary ? 'Loading subject schedule summary…' : 'Loading instructor schedule…') + '</div>';
    facultyScheduleOverlay.classList.add('is-open');
    facultyScheduleOverlay.setAttribute('aria-hidden', 'false');
    facultyScheduleOverlay.setAttribute('aria-label', summary ? 'Instructor schedule summary' : 'Instructor schedule spreadsheet');
    document.body.classList.add('faculty-schedule-modal-open');
    facultyScheduleDialog.focus();
    try {
        const url = summary ? button.dataset.facultySummaryUrl : button.dataset.facultyScheduleUrl;
        const response = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
        });
        if (!response.ok) throw new Error('Schedule could not be loaded.');
        facultyScheduleDialog.innerHTML = await response.text();
        if (!summary) mergeInstructorScheduleCells();
    } catch (error) {
        facultyScheduleDialog.innerHTML = '<div class="faculty-schedule-load-message" role="alert">Unable to load this instructor information. Close this window and try again.</div>';
    }
}
document.querySelectorAll('[data-faculty-schedule-url], [data-faculty-summary-url]').forEach(button => {
    button.addEventListener('click', () => openFacultyScheduleModal(button, Boolean(button.dataset.facultySummaryUrl)));
});
facultyScheduleOverlay?.addEventListener('click', event => {
    if (event.target === facultyScheduleOverlay || event.target.closest('[data-close-faculty-schedule]')) closeFacultyScheduleModal();
});
document.addEventListener('keydown', event => {
    if (event.key === 'Escape') setFacultyImportModal(false);
    if (event.key === 'Escape') closeFacultyScheduleModal();
});

window.addEventListener('click', function(event) {
    const modal = document.getElementById('editSubjectModal');
    if (modal && event.target === modal) closeEditSubject();
});

// Show successful assignment feedback as a brief toast; keep errors visible in a dialog.
document.addEventListener('DOMContentLoaded', function () {
    const messages = Array.from(document.querySelectorAll('.page .message'));
    if (document.getElementById('globalMessageOverlay')) { messages.forEach(message => message.remove()); return; }
    if (!messages.length) return;
    const successMessages = messages.filter(message => message.classList.contains('success'));
    const hasErrors = messages.some(message => message.classList.contains('error'));
    if (successMessages.length && !hasErrors) {
        const toast = document.createElement('div');
        toast.className = 'faculty-assignment-toast';
        toast.setAttribute('role', 'status');
        toast.setAttribute('aria-live', 'polite');
        const icon = document.createElement('i');
        icon.className = 'fa-solid fa-circle-check';
        icon.setAttribute('aria-hidden', 'true');
        const text = document.createElement('span');
        text.textContent = successMessages.map(message => message.textContent.trim()).filter(Boolean).join(' ');
        toast.append(icon, text);
        document.body.appendChild(toast);
        messages.forEach(message => message.remove());
        requestAnimationFrame(() => toast.classList.add('is-visible'));
        window.setTimeout(() => {
            toast.classList.remove('is-visible');
            window.setTimeout(() => toast.remove(), 220);
        }, 2800);
        return;
    }
    const overlay = document.createElement('div');
    overlay.className = 'faculty-feedback-overlay';
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-modal', 'true');
    const dialog = document.createElement('div');
    dialog.className = 'faculty-feedback-dialog' + (messages.some(m => m.classList.contains('error')) ? ' error' : '');
    const heading = document.createElement('h2');
    heading.textContent = messages.some(m => m.classList.contains('error')) ? 'Please Check' : 'Successful!';
    const content = document.createElement('div');
    messages.forEach(message => { content.appendChild(message.cloneNode(true)); message.remove(); });
    const close = document.createElement('button');
    close.type = 'button'; close.textContent = 'OK';
    close.addEventListener('click', () => overlay.remove());
    dialog.append(heading, content, close); overlay.appendChild(dialog); document.body.appendChild(overlay);
    overlay.addEventListener('click', event => { if (event.target === overlay) overlay.remove(); });
    document.addEventListener('keydown', function onEscape(event) { if (event.key === 'Escape') { overlay.remove(); document.removeEventListener('keydown', onEscape); } });
});

</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const codeInput = document.getElementById('facultyCourseCode');
    const subjectId = document.getElementById('facultySubjectId');
    const choiceWrap = document.getElementById('facultySubjectChoiceWrap');
    const choice = document.getElementById('facultySubjectChoice');
    const cohortChoiceWrap = document.getElementById('facultyCohortChoiceWrap');
    const cohortChoices = document.getElementById('facultyCohortChoices');
    const status = document.getElementById('facultyCourseSearchStatus');
    const assignButton = document.getElementById('assignCatalogSubjectButton');
    const fields = {
        title: document.getElementById('facultySubjectTitleDisplay'),
        year: document.getElementById('facultyYearLevelDisplay'),
        semester: document.getElementById('facultySemesterDisplay'),
        program: document.getElementById('facultyProgramDisplay'),
        major: document.getElementById('facultyMajorDisplay'),
        type: document.getElementById('facultySubjectTypeDisplay'),
    };
    if (!codeInput || !subjectId || !status) return;

    let timer = null;
    let lookupController = null;
    let lookupSequence = 0;
    let matches = [];
    const clearSelection = () => {
        subjectId.value = '';
        if (assignButton) assignButton.disabled = true;
        Object.values(fields).forEach(field => { if (field) field.value = ''; });
        if (cohortChoiceWrap) cohortChoiceWrap.hidden = true;
        cohortChoices?.replaceChildren();
    };
    const showCohortOptions = primary => {
        if (!cohortChoiceWrap || !cohortChoices || !primary) return;
        cohortChoices.replaceChildren();
        const alternatives = matches.filter(item =>
            String(item.id) !== String(primary.id)
            && String(item.course_code || '').trim().toUpperCase() === String(primary.course_code || '').trim().toUpperCase()
            && String(item.course_title || '').trim().toLocaleLowerCase() === String(primary.course_title || '').trim().toLocaleLowerCase()
        );
        cohortChoiceWrap.hidden = alternatives.length === 0;
        alternatives.forEach(item => {
            const label = document.createElement('label');
            label.className = 'faculty-cohort-option';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'cohort_subject_ids[]';
            checkbox.value = String(item.id);
            const detail = document.createElement('span');
            detail.textContent = [item.program, item.major ? 'Major: ' + item.major : '', item.year_level, item.semester, item.memorandum ? 'Memorandum: ' + item.memorandum : ''].filter(Boolean).join(' · ');
            label.append(checkbox, detail);
            cohortChoices.appendChild(label);
        });
    };
    const selectSubject = item => {
        if (!item) return clearSelection();
        codeInput.value = item.course_code || codeInput.value;
        subjectId.value = item.id;
        fields.title.value = item.course_title || '';
        fields.year.value = item.year_level || '';
        fields.semester.value = item.semester || '';
        fields.program.value = item.program || '';
        fields.major.value = item.major || '—';
        fields.type.value = item.subject_type || 'Lec';
        assignButton.disabled = false;
        showCohortOptions(item);
        status.textContent = 'Subject found. Confirm the details, then assign it to this instructor.';
        status.style.color = '#216b36';
    };
    const resetChoice = () => {
        choiceWrap.hidden = true;
        choice.replaceChildren(new Option('Choose a year level', ''));
        matches = [];
    };

    codeInput.addEventListener('input', () => {
        window.clearTimeout(timer);
        if (lookupController) lookupController.abort();
        const requestId = ++lookupSequence;
        clearSelection();
        resetChoice();
        const code = codeInput.value.trim();
        if (!code) {
            status.textContent = 'Enter a course code already added in Manage Subjects.';
            status.style.color = '#40566c';
            return;
        }
        status.textContent = 'Searching course codes…';
        status.style.color = '#40566c';
        timer = window.setTimeout(async () => {
            lookupController = new AbortController();
            try {
                const url = new URL(@json(route('chair.subjects.lookup')), window.location.origin);
                url.searchParams.set('course_code', code);
                const response = await fetch(url, { headers: { Accept: 'application/json' }, signal: lookupController.signal });
                if (requestId !== lookupSequence) return;
                if (!response.ok) throw new Error('lookup failed');
                const data = await response.json();
                if (requestId !== lookupSequence) return;
                matches = data.subjects || [];
                if (!matches.length) {
                    status.textContent = 'No course codes start with “' + code + '”. Check the spelling or add it in Manage Subjects.';
                    status.style.color = '#9b2c2c';
                    return;
                }
                const exactMatches = matches.filter(item => String(item.course_code || '').trim().toUpperCase() === code.toUpperCase());
                if (exactMatches.length === 1) {
                    selectSubject(exactMatches[0]);
                    return;
                }
                status.textContent = exactMatches.length > 1
                    ? 'This course code has more than one subject assignment. Choose the correct group below.'
                    : 'Matching course codes appear below. Choose the exact course to continue.';
                status.style.color = '#52654f';
                choice.replaceChildren(new Option('Choose the correct subject', ''));
                matches.forEach(item => {
                    const details = [item.course_code, item.course_title, item.year_level, item.semester, item.program, item.major ? `Major: ${item.major}` : 'No major'];
                    choice.add(new Option(details.filter(Boolean).join(' · '), String(item.id)));
                });
                choiceWrap.hidden = false;
            } catch (error) {
                if (error.name === 'AbortError' || requestId !== lookupSequence) return;
                status.textContent = 'Could not check the course code. Please try again.';
                status.style.color = '#9b2c2c';
            }
        }, 100);
    });

    choice?.addEventListener('change', () => {
        selectSubject(matches.find(item => String(item.id) === choice.value));
    });
});
</script>
        </main>

    </div>

</div>


