 @php
    $initialRoomId = $initialRoomId ?? request()->query('room_id');
@endphp

@include('footerheader.header')

<style>
    /* Modify schedule: the Chair workspace theme with a readable room picker and timetable. */
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
    body.portal-shell.chair-portal .main-layout > .content.chair-modify-view { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }

    .chair-modify-view .page { width:min(100%,1480px); margin:0 auto; }
    .chair-modify-view .modify-hero { position:relative; display:flex; align-items:flex-start; gap:19px; min-height:205px; margin:0 0 20px; padding:clamp(27px,4vw,42px); overflow:hidden; border:1px solid rgba(231,214,173,.22); border-radius:24px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); color:#fff; }
    .chair-modify-view .modify-hero::after { position:absolute; right:-48px; bottom:-165px; width:330px; height:330px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .chair-modify-view .modify-hero-icon { position:relative; z-index:1; display:grid; flex:0 0 52px; width:52px; height:52px; place-items:center; border:1px solid rgba(255,255,255,.24); border-radius:15px; background:rgba(255,255,255,.11); color:#e2c987; font-size:22px; }
    .chair-modify-view .modify-hero-copy { position:relative; z-index:1; }
    .chair-modify-view .modify-eyebrow { margin:0 0 10px; color:#e2c987; font-size:11px; font-weight:800; letter-spacing:.15em; }
    .chair-modify-view .modify-title { margin:0 0 8px; color:#fff; font:800 clamp(34px,3.2vw,45px)/1.12 var(--wr-display); letter-spacing:-.045em; }
    .chair-modify-view .modify-subtitle { margin:0; color:#e1e8df; font-size:15px; font-weight:600; line-height:1.6; }
    .chair-modify-view .modify-term { display:flex; flex-wrap:wrap; align-items:center; gap:7px; margin-top:16px; color:#f2eee0; font-size:13px; font-weight:650; }
    .chair-modify-view .modify-term > i { color:#e2c987; }
    .chair-modify-view .modify-term strong { color:#fff; font-weight:800; }
    .chair-modify-view .modify-term-note { color:#d3e0d5; font-size:12px; font-weight:550; }
    .chair-modify-view .editor-mode-switch { display:flex; align-items:center; flex-wrap:wrap; gap:8px; margin:-3px 0 16px; padding:6px; border:1px solid #dfe7dd; border-radius:12px; background:#fff; }
    .chair-modify-view .editor-mode-link { display:inline-flex; min-height:40px; align-items:center; gap:8px; padding:0 14px; border-radius:8px; color:#53675a; font-size:13px; font-weight:800; text-decoration:none; }
    .chair-modify-view .editor-mode-link.active { background:#24543e; color:#fff; }
    .chair-modify-view .message { margin:0 0 15px; padding:14px 17px; border:1px solid; border-radius:12px; font-size:14px; font-weight:700; line-height:1.5; }
    .chair-modify-view .message.success { border-color:#b9d9bd; background:#edf7ee; color:#28603a; }
    .chair-modify-view .message.error { border-color:#edc8c2; background:#fff3f1; color:#8f3e36; }

    .chair-modify-view .navigation-section,.chair-modify-view .editor-panel,.chair-modify-view .schedule-section { min-width:0; margin:0 0 17px; overflow:hidden; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .chair-modify-view .navigation-header { padding:20px 22px; border-bottom:1px solid #e7ece5; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .chair-modify-view .navigation-title,.chair-modify-view .level-title { display:flex; align-items:center; gap:10px; color:#173b33; font-size:20px; font-weight:800; letter-spacing:-.025em; }
    .chair-modify-view .navigation-title i,.chair-modify-view .level-title i { color:#35684a; font-size:17px; }
    .chair-modify-view .navigation-subtitle { margin-top:6px; color:#5b6d61; font-size:13px; font-weight:550; line-height:1.5; }
    .chair-modify-view .room-search-panel { display:grid; gap:7px; margin:0 22px 17px; padding:14px; border:1px solid #e2e9e1; border-radius:13px; background:#f8faf7; }
    .chair-modify-view .editor-mode-switch .room-search-panel { flex:0 1 340px; gap:4px; margin:0 0 0 auto; padding:0; border:0; background:transparent; }
    .chair-modify-view .editor-mode-switch .room-search-panel label { position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; }
    .chair-modify-view .editor-mode-switch .room-search-control { min-height:40px; }
    .chair-modify-view .room-search-panel label { color:#43594d; font-size:12px; font-weight:800; }
    .chair-modify-view .room-search-control { display:flex; min-height:46px; align-items:center; gap:10px; padding:0 12px; border:1px solid #d7e0d7; border-radius:10px; background:#fff; color:#65776a; }
    .chair-modify-view .room-search-control:focus-within { outline:3px solid rgba(49,104,76,.14); border-color:#648a70; }
    .chair-modify-view .room-search-control input { width:100%; min-width:0; border:0; outline:0; background:transparent; color:#273f32; font:600 14px/1.4 var(--wr-body); }
    .chair-modify-view .room-search-control input::placeholder { color:#89968c; font-weight:500; }
    .chair-modify-view .room-search-clear { display:grid; flex:0 0 30px; width:30px; height:30px; place-items:center; border:0; border-radius:8px; background:#eef3ed; color:#496052; cursor:pointer; }
    .chair-modify-view .room-search-status { margin:0; color:#68776d; font-size:12px; font-weight:600; }
    .chair-modify-view .room-search-results { padding:0 22px 22px; }
    .chair-modify-view .room-search-results[hidden],.chair-modify-view .room-search-clear[hidden],.chair-modify-view .room-search-empty[hidden] { display:none !important; }
    .chair-modify-view .room-search-empty { margin:0 22px 22px; }
    .chair-modify-view #departmentLevel,.chair-modify-view #buildingLevel,.chair-modify-view #roomLevel { padding:19px 21px 22px; }
    .chair-modify-view .level-title { margin-bottom:14px; font-size:16px; }
    .chair-modify-view .department-list,.chair-modify-view .building-list { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:11px; }
    .chair-modify-view .department-card,.chair-modify-view .building-card { display:flex; align-items:center; justify-content:space-between; gap:13px; width:100%; min-height:78px; padding:14px 15px; border:1px solid #e2e9e1; border-radius:13px; background:#fff; color:#2f493b; text-align:left; cursor:pointer; transition:transform .16s ease,border-color .16s ease,background .16s ease,box-shadow .16s ease; }
    .chair-modify-view .department-card:hover,.chair-modify-view .building-card:hover { transform:translateY(-2px); border-color:#adc5b0; background:#f9fbf8; box-shadow:0 8px 18px rgba(31,57,43,.07); }
    .chair-modify-view .card-left { display:flex; align-items:center; gap:12px; min-width:0; }
    .chair-modify-view .card-icon { display:grid; flex:0 0 40px; width:40px; height:40px; place-items:center; border:1px solid #e3e9e0; border-radius:12px; background:#edf3eb; color:#285940; font-size:15px; }
    .chair-modify-view .card-name { color:#294437; font-size:14px; font-weight:800; line-height:1.35; overflow-wrap:anywhere; }
    .chair-modify-view .card-description,.chair-modify-view .card-count { margin-top:4px; color:#68776d; font-size:12px; font-weight:600; }
    .chair-modify-view .card-arrow { color:#819188; font-size:13px; }
    .chair-modify-view .breadcrumb { display:flex; align-items:center; flex-wrap:wrap; gap:9px; margin-bottom:17px; color:#8a978e; font-size:13px; }
    .chair-modify-view .breadcrumb strong { color:#294437; font-size:13px; font-weight:800; }
    .chair-modify-view .back-button { display:inline-flex; align-items:center; gap:8px; min-height:37px; padding:0 11px; border:1px solid #dce6dc; border-radius:9px; background:#f8faf6; color:#315b43; font:800 12px/1.2 var(--wr-body); cursor:pointer; }
    .chair-modify-view .back-button:hover { background:#eef5ec; }
    .chair-modify-view .room-selection-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .chair-modify-view .room-selection-card { display:grid; gap:13px; padding:15px; border:1px solid #e2e9e1; border-radius:14px; background:#fff; }
    .chair-modify-view .room-selection-top { display:flex; align-items:center; gap:11px; }
    .chair-modify-view .room-selection-icon { display:grid; flex:0 0 40px; width:40px; height:40px; place-items:center; border-radius:12px; background:#edf3eb; color:#285940; font-size:16px; }
    .chair-modify-view .room-selection-name { color:#203d30; font-size:15px; font-weight:800; line-height:1.35; }
    .chair-modify-view .room-selection-label { margin-top:4px; color:#68776d; font-size:12px; font-weight:600; }
    .chair-modify-view .open-room-button,.chair-modify-view .change-room-button { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:41px; padding:0 14px; border:1px solid #24543e; border-radius:10px; background:#24543e; color:#fff; font:800 12px/1.2 var(--wr-body); cursor:pointer; transition:transform .16s ease,background .16s ease; }
    .chair-modify-view .open-room-button:hover,.chair-modify-view .change-room-button:hover { transform:translateY(-1px); background:#173b33; }
    .chair-modify-view .empty { grid-column:1/-1; padding:17px; border:1px dashed #d7e1d7; border-radius:12px; background:#f8faf6; color:#637268; font-size:13px; font-weight:600; }

    .chair-modify-view .editor-panel { padding:20px 22px 21px; scroll-margin-top:105px; }
    .chair-modify-view .modify-entry-overlay { position:fixed; inset:0; z-index:2147483000; display:grid; place-items:center; padding:18px; pointer-events:none; }
    .chair-modify-view .modify-entry-overlay[hidden] { display:none !important; }
    .chair-modify-view .modify-entry-overlay .editor-panel { width:min(790px,calc(100vw - 36px)); max-height:min(88vh,820px); margin:0; overflow:auto; border-color:#dce5df; border-radius:17px; box-shadow:0 22px 70px rgba(28,47,37,.22); pointer-events:auto; }
    .chair-modify-view .modify-entry-heading { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; margin-bottom:17px; }
    .chair-modify-view .modify-entry-heading .editor-subtitle { margin-bottom:8px; }
    .chair-modify-view .modify-entry-target { display:inline-flex; min-height:28px; align-items:center; padding:4px 10px; border:1px solid #dce9df; border-radius:999px; background:#f1f7f2; color:#365d43; font-size:12px; font-weight:750; }
    .chair-modify-view .modify-entry-close { display:grid; flex:0 0 36px; width:36px; height:36px; place-items:center; border:1px solid #dce5df; border-radius:10px; background:#f8faf8; color:#53645a; font:500 24px/1 Arial,sans-serif; cursor:pointer; }
    .chair-modify-view .modify-entry-close:hover { background:#edf3ee; color:#1e392a; }
    .chair-modify-view .modify-entry-footer { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:14px; }
    .chair-modify-view .editor-title { display:flex; align-items:center; gap:10px; color:#173b33; font-size:20px; font-weight:800; }
    .chair-modify-view .editor-title i { color:#35684a; }
    .chair-modify-view .editor-subtitle { max-width:900px; margin:7px 0 17px; color:#5b6d61; font-size:13px; font-weight:550; line-height:1.6; }
    .chair-modify-view .editor-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:13px; }
    .chair-modify-view .editor-field { display:grid; min-width:0; gap:6px; }
    .chair-modify-view .editor-field label { color:#43594d; font-size:12px; font-weight:800; }
    .chair-modify-view .shared-audience-picker { margin-top:12px; padding:12px; border:1px solid #dbe6dd; border-radius:10px; background:#f7faf7; }
    .chair-modify-view .shared-audience-picker[hidden] { display:none !important; }
    .chair-modify-view .shared-audience-heading { margin-bottom:8px; color:#294533; font-size:12px; font-weight:800; }
    .chair-modify-view .shared-audience-choices { display:grid; gap:7px; max-height:135px; overflow:auto; }
    .chair-modify-view .shared-audience-option { display:flex; align-items:flex-start; gap:8px; padding:8px; border:1px solid #e1e9e2; border-radius:8px; background:#fff; color:#34483b; font-size:12px; line-height:1.4; cursor:pointer; }
    .chair-modify-view .shared-audience-option input { margin:2px 0 0; accent-color:#28583f; }
    .chair-modify-view .shared-audience-help { margin-top:8px; color:#68776c; font-size:11px; line-height:1.4; }
    .chair-modify-view .editor-field input,.chair-modify-view .editor-field select { width:100%; min-height:43px; padding:0 12px; border:1px solid #d7e0d7; border-radius:10px; background:#fff; color:#273f32; font:600 13px/1.4 var(--wr-body); }
    .chair-modify-view .editor-field input[readonly] { background:#f6f8f4; color:#4d6255; }
    .chair-modify-view .editor-field input::placeholder { color:#929d95; font-weight:500; }
    .chair-modify-view .editor-field input:focus,.chair-modify-view .editor-field select:focus { outline:3px solid rgba(49,104,76,.15); border-color:#648a70; }
    .chair-modify-view #modifySubjectChoice[hidden] { display:none !important; }
    .chair-modify-view #modifySubjectChoice:not([hidden]) { display:block !important; }
    .chair-modify-view .editor-status { margin-top:14px; padding:12px 14px; border:1px solid #dfe8dc; border-radius:11px; background:#f4f7f1; color:#40574a; font-size:13px; font-weight:700; line-height:1.5; }
    .chair-modify-view .editor-status.success { border-color:#b9d9bd; background:#edf7ee; color:#28603a; }
    .chair-modify-view .editor-status.warning { border-color:#e6d9b9; background:#fbf7ea; color:#806a39; }
    .chair-modify-view .editor-status.error { border-color:#edc8c2; background:#fff3f1; color:#8f3e36; }
    .chair-modify-view .editor-actions { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:15px; }
    .chair-modify-view .editor-actions-left,.chair-modify-view .editor-actions-right { display:flex; align-items:center; flex-wrap:wrap; gap:8px; }
    .chair-modify-view .editor-button { display:inline-flex; align-items:center; justify-content:center; gap:7px; min-height:41px; padding:0 13px; border:1px solid #dce5dc; border-radius:10px; background:#f7f9f6; color:#40574a; font:800 12px/1.2 var(--wr-body); cursor:pointer; }
    .chair-modify-view .editor-button:hover { background:#edf3eb; }
    .chair-modify-view .editor-clear { border-color:#ecd2ce; background:#fff8f7; color:#9b4c43; }
    .chair-modify-view .editor-save { border-color:#24543e; background:#24543e; color:#fff; }
    .chair-modify-view .editor-save:hover { background:#173b33; }
    .chair-modify-view .editor-color-inline { display:flex; align-items:center; gap:8px; min-height:41px; padding:0 11px; border:1px solid #dce5dc; border-radius:10px; background:#fff; color:#43594d; font-size:12px; font-weight:800; }
    .chair-modify-view .editor-color-inline input { width:30px; height:27px; padding:0; border:0; background:transparent; cursor:pointer; }

    .chair-modify-view .modify-sheet-toolbar { position:sticky; top:100px; z-index:1000101; display:flex; align-items:center; justify-content:space-between; gap:15px; margin:0 22px 12px; padding:11px 13px; border:1px solid #dfe7dd; border-radius:12px; background:#fff; box-shadow:0 4px 14px rgba(31,57,43,.045); }
    .chair-modify-view .modify-sheet-toolbar.is-entry-open { visibility:hidden; pointer-events:none; }
    .chair-modify-view .modify-sheet-toolbar-guide,.chair-modify-view .modify-sheet-toolbar-actions { display:flex; min-width:0; align-items:center; flex-wrap:wrap; gap:9px 14px; }
    .chair-modify-view .modify-sheet-toolbar-guide { color:#54675a; font-size:11px; font-weight:600; }
    .chair-modify-view .modify-sheet-toolbar-guide > strong { color:#284a35; font-size:13px; }
    .chair-modify-view .modify-sheet-toolbar-guide span { display:inline-flex; align-items:center; gap:6px; }
    .chair-modify-view .modify-sheet-toolbar-guide b { display:inline-grid; width:19px; height:19px; place-items:center; border-radius:50%; background:#e6f0e7; color:#2f6842; font-size:10px; }
    .chair-modify-view .modify-sheet-toolbar-actions { justify-content:flex-end; }
    .chair-modify-view .modify-sheet-toolbar-actions [hidden] { display:none !important; }
    .chair-modify-view .modify-sheet-toolbar .editor-color-inline { min-height:39px; }
    .chair-modify-view #modifyMergeClassButton:not(:disabled) { border-color:#bad0bc; background:#edf5ed; color:#285940; }
    .chair-modify-view #modifyMergeClassButton:not(:disabled):hover { background:#e2efe3; }
    .chair-modify-view .modify-sheet-toolbar .editor-button:disabled { opacity:.48; cursor:not-allowed; transform:none; }
    .chair-modify-view .modify-sheet-toolbar-left { display:flex; min-width:0; align-items:center; flex-wrap:wrap; gap:9px 13px; }
    .chair-modify-view .modify-merge-target-label { color:#64766a; font-size:11px; font-weight:650; }

    .chair-modify-view .schedule-section { scroll-margin-top:105px; }
    .chair-modify-view .schedule-header { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:19px 22px; border-bottom:1px solid #e7ece5; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .chair-modify-view .schedule-main-title { display:flex; align-items:center; gap:9px; color:#173b33; font-size:19px; font-weight:800; }
    .chair-modify-view .schedule-main-title i { color:#35684a; }
    .chair-modify-view .schedule-room-name { margin-top:4px; color:#344d3e; font-size:16px; font-weight:750; }
    .chair-modify-view .room-info { padding:12px 22px 0; color:#65746a; font-size:12px; font-weight:600; }
    .chair-modify-view .room-info strong { color:#304a3b; font-weight:800; }
    .chair-modify-view .room-schedule-meta { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; padding:13px 22px; }
    .chair-modify-view .room-schedule-meta-item { display:grid; gap:4px; padding:12px 13px; border:1px solid #e2e9e1; border-radius:11px; background:#f8faf6; }
    .chair-modify-view .room-schedule-meta-label { color:#68776d; font-size:11px; font-weight:700; }
    .chair-modify-view .room-schedule-meta-item strong { color:#294437; font-size:13px; font-weight:800; }
    .chair-modify-view .permission-info { display:flex; align-items:flex-start; gap:9px; margin:0 22px 14px; padding:12px 14px; border:1px solid #e6ddc4; border-radius:11px; background:#fbf8ee; color:#695b38; font-size:12px; font-weight:650; line-height:1.55; }
    .chair-modify-view .permission-info i { margin-top:2px; color:#967941; }
    .chair-modify-view .excel-wrapper { position:relative; width:calc(100% - 44px); margin:0 22px 22px; overflow:auto; border:1px solid #dfe7dd; border-radius:13px; }
    .chair-modify-view .excel-schedule { width:100%; min-width:1120px; border-collapse:separate; border-spacing:0; table-layout:fixed; background:#fff; }
    .chair-modify-view .excel-schedule th { position:sticky; top:0; z-index:2; height:43px; padding:9px; border-right:1px solid #dbe4d9; border-bottom:1px solid #dbe4d9; background:#f0f5ee; color:#365444; text-align:center; font-size:12px; font-weight:800; letter-spacing:.04em; }
    .chair-modify-view .excel-schedule th.time-header,.chair-modify-view .excel-schedule td.time-cell { width:100px; background:#f5f7f3; color:#465b4e; }
    .chair-modify-view .excel-schedule td { height:104px; padding:8px; border-right:1px solid #e6ebe4; border-bottom:1px solid #e6ebe4; color:#30473a; vertical-align:top; font-size:12px; line-height:1.35; }
    .chair-modify-view .excel-schedule td:last-child,.chair-modify-view .excel-schedule th:last-child { border-right:0; }
    .chair-modify-view .excel-schedule tbody tr:last-child td { border-bottom:0; }
    .chair-modify-view .excel-schedule td.time-cell { height:auto; vertical-align:middle; text-align:center; font-size:11px; font-weight:800; white-space:nowrap; }
    .chair-modify-view .excel-schedule .schedule-cell { transition:box-shadow .12s ease,filter .12s ease; }
    .chair-modify-view .modify-auto-merge-days { display:inline-flex; max-width:100%; margin:0 0 3px; padding:2px 5px; border-radius:5px; background:rgba(255,255,255,.82); color:#315540; font-size:10px; font-weight:850; line-height:1.1; }
    .chair-modify-view .modify-schedule-block { container-type:inline-size; overflow:auto; scrollbar-width:thin; scrollbar-color:#b8c8ba transparent; }
    .chair-modify-view .modify-schedule-block .schedule-course { font-size:clamp(9px,3.2cqw,13px); overflow-wrap:anywhere; }
    .chair-modify-view .modify-schedule-block .schedule-subject { font-size:clamp(8px,2.9cqw,12px); line-height:1.2; overflow-wrap:anywhere; }
    .chair-modify-view .modify-schedule-block .schedule-instructor,
    .chair-modify-view .modify-schedule-block .schedule-year,
    .chair-modify-view .modify-schedule-block .merged-course-details,
    .chair-modify-view .modify-schedule-block .schedule-merge-pending-label { font-size:clamp(7px,2.4cqw,10px); line-height:1.15; overflow-wrap:anywhere; }
    .chair-modify-view .excel-schedule .editable-cell { cursor:pointer; }
    .chair-modify-view .excel-schedule .editable-cell:hover { position:relative; z-index:1; box-shadow:inset 0 0 0 2px #5a8768; filter:saturate(1.06); }
    .chair-modify-view .merged-course-details { margin-top:5px; padding:5px 7px; border-left:2px solid #5b8b67; border-radius:0 6px 6px 0; background:rgba(255,255,255,.78); color:#3f5b49; font-size:10px; line-height:1.4; }
    .chair-modify-view .merged-course-details strong { color:#28543a; font-size:9px; text-transform:uppercase; letter-spacing:.04em; }
    .chair-modify-view .cell-shared-majors span { display:block; margin-top:2px; color:#40584a; font-size:10px; font-weight:700; }
    .chair-modify-view .excel-schedule .is-selected-edit { position:relative; z-index:1; box-shadow:inset 0 0 0 3px #285940 !important; }
    .chair-modify-view .excel-schedule .locked-cell { cursor:not-allowed; }
    .chair-modify-view .excel-schedule .locked-cell[data-merge-schedule-id] { cursor:pointer; }
    .chair-modify-view .excel-schedule .is-selected-merge { position:relative; z-index:1; box-shadow:inset 0 0 0 3px #d38a38 !important; }
    .chair-modify-view .excel-schedule .locked-cell.filled { filter:saturate(.62); }
    .chair-modify-view .excel-schedule .schedule-course { margin-bottom:3px; color:#173b33; font-size:13px; font-weight:850; line-height:1.3; }
    .chair-modify-view .excel-schedule .schedule-subject { margin-bottom:3px; color:#304a3b; font-size:12px; font-weight:750; line-height:1.35; }
    .chair-modify-view .excel-schedule .schedule-instructor { color:#4b6053; font-size:11px; font-weight:650; }
    .chair-modify-view .excel-schedule .schedule-time { margin:3px 0; color:#405a49; font-size:10px; font-weight:800; }
    .chair-modify-view .excel-schedule .schedule-year { margin-top:2px; color:#65746a; font-size:10px; font-weight:650; }
    .chair-modify-view .excel-schedule .empty-cell { color:#849187; font-size:11px; font-weight:700; }
    .chair-modify-view .excel-schedule .editable-label,.chair-modify-view .excel-schedule .locked,.chair-modify-view .excel-schedule .not-allowed { display:inline-flex; align-items:center; gap:5px; margin-top:5px; color:#35684a; font-size:10px; font-weight:800; line-height:1.35; }
    .chair-modify-view .excel-schedule .locked,.chair-modify-view .excel-schedule .not-allowed { color:#8b5a4e; }
    .chair-modify-view .excel-schedule .allowed-other .editable-label { color:#4d7654; }
    .chair-modify-view .excel-schedule .noon-break-row td { height:34px; padding:7px; background:#f5f1e4; color:#806a39; text-align:center; font-size:11px; font-weight:850; letter-spacing:.16em; }
    .chair-modify-view .excel-schedule td.modify-block-source > * { visibility:hidden; }
    .chair-modify-view .modify-schedule-block-layer { position:absolute; z-index:5; inset:0 auto auto 0; overflow:visible; pointer-events:none; }
    .chair-modify-view .modify-schedule-block { position:absolute; box-sizing:border-box; z-index:1; overflow:hidden; padding:7px 8px; border:1px solid rgb(52 76 97 / 35%); border-radius:5px; color:#243746; box-shadow:0 2px 5px rgb(22 40 55 / 14%); cursor:pointer; pointer-events:auto; touch-action:none; }
    .chair-modify-view .modify-schedule-block.is-selected { z-index:3; outline:3px solid #285940; outline-offset:-3px; }
    .chair-modify-view .modify-schedule-block.is-resizing { z-index:4; box-shadow:0 5px 16px rgb(22 40 55 / 25%); }
    .chair-modify-view .modify-schedule-block.resize-conflict { outline:3px solid #c74343; outline-offset:-3px; }
    .chair-modify-view .modify-schedule-block > .schedule-time { position:absolute; top:3px; right:4px; padding:1px 4px; border-radius:4px; background:#fff; color:#243746; font-size:10px; font-weight:800; }
    .chair-modify-view .modify-resize-handle { position:absolute; z-index:5; left:0; width:100%; height:20px; padding:0; border:0; background:transparent; cursor:ns-resize; visibility:hidden; opacity:0; pointer-events:none; touch-action:none; }
    .chair-modify-view .modify-resize-handle.start { top:0; }
    .chair-modify-view .modify-resize-handle.end { bottom:0; }
    .chair-modify-view .modify-resize-handle span { display:block; width:50px; height:5px; margin:0 auto; border-radius:999px; background:#245c90; }
    .chair-modify-view .modify-schedule-block.is-selected .modify-resize-handle { height:26px; visibility:visible; opacity:1; pointer-events:auto; }

    @media(max-width:1100px) {
        body.portal-shell.chair-portal { --chair-sidebar-width:226px; --chair-shell-gutter:22px; }
        body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
        body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); }
        body.portal-shell.chair-portal .main-layout > .content.chair-modify-view { padding-right:24px; padding-left:24px; }
        .chair-modify-view .room-selection-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .chair-modify-view .editor-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
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
        body.portal-shell.chair-portal .main-layout > .content.chair-modify-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .chair-modify-view .modify-hero { gap:14px; padding:27px 23px; }
        .chair-modify-view .department-list,.chair-modify-view .building-list { grid-template-columns:minmax(0,1fr); }
        .chair-modify-view .room-search-panel { margin-right:16px; margin-left:16px; }
        .chair-modify-view .editor-mode-switch .room-search-panel { flex-basis:100%; margin:4px 0 0; }
        .chair-modify-view .room-search-results { padding-right:16px; padding-left:16px; }
        .chair-modify-view .room-search-empty { margin-right:16px; margin-left:16px; }
        .chair-modify-view .editor-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:540px) {
        .chair-modify-view .modify-hero { flex-direction:column; }
        .chair-modify-view .modify-term { align-items:flex-start; flex-direction:column; gap:6px; }
        .chair-modify-view .room-selection-grid,.chair-modify-view .editor-grid { grid-template-columns:minmax(0,1fr); }
        .chair-modify-view #departmentLevel,.chair-modify-view #buildingLevel,.chair-modify-view #roomLevel { padding:16px; }
        .chair-modify-view .navigation-header,.chair-modify-view .schedule-header { padding:17px; }
        .chair-modify-view .schedule-header { align-items:flex-start; flex-direction:column; }
        .chair-modify-view .change-room-button { align-self:flex-start; }
        .chair-modify-view .room-schedule-meta { grid-template-columns:minmax(0,1fr); padding:12px 17px; }
        .chair-modify-view .room-info { padding-right:17px; padding-left:17px; line-height:1.6; }
        .chair-modify-view .permission-info { margin-right:17px; margin-left:17px; }
        .chair-modify-view .excel-wrapper { width:calc(100% - 34px); margin-right:17px; margin-left:17px; }
        .chair-modify-view .editor-panel { padding:17px; }
        .chair-modify-view .editor-actions { align-items:stretch; flex-direction:column; }
        .chair-modify-view .editor-actions-right { justify-content:flex-start; }
        .chair-modify-view .modify-sheet-toolbar { align-items:flex-start; flex-direction:column; margin-right:17px; margin-left:17px; }
        .chair-modify-view .modify-sheet-toolbar-actions { justify-content:flex-start; }
    }
</style>
<div class="wrapper">

   

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content chair-modify-schedule chair-workspace-redesign chair-modify-view {{ $initialRoomId ? "modify-room-page" : "" }}">

          

            <div class="page">
    <section class="modify-hero">
        <div class="modify-hero-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></div>
        <div class="modify-hero-copy">
            <div class="modify-eyebrow"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> CROSS-COLLEGE WORKSPACE</div>
            <h1 class="modify-title">Cross-Scheduling</h1>
            <p class="modify-subtitle">Browse rooms from other colleges. Their existing schedules stay protected; room owners control whether you can add classes to empty slots.</p>
            <div class="modify-term"><i class="fa-regular fa-calendar" aria-hidden="true"></i><span><strong>Active term</strong> {{ $activeTerm->academic_year ?? '-' }} · {{ $activeTerm->semester ?? '-' }}</span><span class="modify-term-note">Schedule changes apply to this term.</span></div>
        </div>
    </section>

    <nav class="editor-mode-switch" aria-label="Schedule editor mode">
        <a class="editor-mode-link" href="{{ route('chair.setschedule') }}"><i class="fa-solid fa-building"></i> Schedule editor</a>
        <a class="editor-mode-link active" aria-current="page" href="{{ route('chair.modifyschedule', ['term_id' => $activeTerm->id]) }}"><i class="fa-solid fa-arrow-right-arrow-left"></i> Cross-scheduling</a>
        <a class="editor-mode-link" href="{{ route('chair.roomavailability', ['term_id' => $activeTerm->id]) }}"><i class="fa-solid fa-table-cells-large"></i> All-room availability</a>
        <div class="room-search-panel">
            <label for="crossScheduleRoomSearch">Find a room</label>
            <div class="room-search-control">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input id="crossScheduleRoomSearch" type="search" autocomplete="off" aria-label="Find a room" placeholder="Search rooms across colleges">
                <button type="button" id="clearCrossScheduleRoomSearch" class="room-search-clear" aria-label="Clear room search" hidden><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
            </div>
            <p class="room-search-status" id="crossScheduleRoomSearchStatus" role="status" aria-live="polite">Search across colleges, buildings, and rooms.</p>
        </div>
    </nav>

    {{-- SUCCESS --}}

                @if(session('success'))

                    <div class="message success">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ERROR --}}

                @if(session('error'))

                    <div class="message error">
                        {{ session('error') }}
                    </div>

                @endif

{{-- =====================================================
     DEPARTMENT → BUILDING → ROOM
====================================================== --}}

<div class="navigation-section">


    {{-- =================================================
         NAVIGATION HEADER
    ================================================== --}}

    <div class="navigation-header">

        <div>

            <div class="navigation-title">

                <i class="fa-solid fa-location-dot"></i>

                Select Classroom

            </div>

            <div class="navigation-subtitle">Only other colleges are listed. Choose a room to open the shared Excel schedule editor.</div>

        </div>

    </div>

    <div class="room-selection-grid room-search-results" id="crossScheduleRoomSearchResults" hidden></div>
    <div class="empty room-search-empty" id="crossScheduleRoomSearchEmpty" role="status" hidden>No rooms match your search.</div>


    {{-- =================================================
         DEPARTMENT LEVEL
    ================================================== --}}

    <div id="departmentLevel">

        <div class="level-title">

            <i class="fa-solid fa-building-columns"></i>

            Colleges

        </div>


        <div class="department-list">

            @foreach($departments as $department)

                <button
                    type="button"
                    class="department-card"
                    onclick="selectDepartment(
                        {{ $department->id }}
                    )"
                >

                    <div class="card-left">

                        <div class="card-icon department-icon">

                            <i class="fa-solid fa-building-columns"></i>

                        </div>

                        <div>

                            <div class="card-name">

                                {{ $department->name }}

                            </div>

                            <div class="card-description">

                                College

                            </div>

                        </div>

                    </div>


                    <div class="card-arrow">

                        <i class="fa-solid fa-chevron-right"></i>

                    </div>

                </button>

            @endforeach

        </div>

    </div>


    {{-- =================================================
         BUILDING LEVEL
    ================================================== --}}

    <div
        id="buildingLevel"
        style="display:none;"
    >

        <div class="breadcrumb">

            <button
                type="button"
                onclick="backToDepartments()"
                class="back-button"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Colleges

            </button>

            <span>
                /
            </span>

            <strong id="selectedDepartmentName">
                College
            </strong>

        </div>


        <div class="level-title">

            <i class="fa-solid fa-building"></i>

            Buildings

        </div>


        <div
            id="buildingList"
            class="building-list"
        >
        </div>

    </div>


    {{-- =================================================
         ROOM LEVEL
    ================================================== --}}

    <div
        id="roomLevel"
        style="display:none;"
    >

        <div class="breadcrumb">

            <button
                type="button"
                onclick="backToBuildings()"
                class="back-button"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Buildings

            </button>

            <span>
                /
            </span>

            <strong id="selectedBuildingName">
                Building
            </strong>

        </div>


        <div class="level-title">

            <i class="fa-solid fa-door-open"></i>

            Rooms

        </div>


        <div
            id="roomList"
            class="room-selection-grid"
        >
        </div>

    </div>

</div>


@include('chair.partials.schedule-merge-request-modal')

               
{{-- =========================================================
     EXCEL-STYLE MODIFY EDITOR
========================================================= --}}

@php
    $modifyFacultySubjects = $facultySubjects ?? collect();
@endphp

<div class="modify-entry-overlay" id="modifyEntryOverlay" hidden>
<section class="editor-panel" id="modifyEditor" role="dialog" aria-modal="false" aria-labelledby="modifyEditorTitle">

    <div class="modify-entry-heading">
        <div>
            <div class="editor-title" id="modifyEditorTitle">
                <i class="fa-solid fa-table-cells-large"></i>
                Schedule Entry
            </div>
            <div class="editor-subtitle">
                Enter a course code, review the loaded details, then confirm to update the selected cell.
            </div>
            <div class="modify-entry-target" id="modifyEntryTarget">Selected timetable cell</div>
        </div>
        <button type="button" class="modify-entry-close" id="modifyEntryClose" aria-label="Close schedule entry">×</button>
    </div>

    <form
        method="POST"
        action="{{ route('chair.modify.schedule.store') }}"
        id="modifyCellForm"
    >
        @csrf

        <input type="hidden" name="room_id" id="modifyFormRoomId">
        <input type="hidden" name="day" id="modifyFormDay">
        <input type="hidden" name="time" id="modifyFormTime">
        <input type="hidden" name="schedule_id" id="modifyFormScheduleId">
        <input type="hidden" name="adjustments" id="modifyScheduleAdjustments" value="[]">
        <input type="hidden" name="faculty_subject_id" id="modifyFacultySubjectId">

        <div class="editor-grid">

            <div class="editor-field">
                <label for="modifyCourseCode">Course Code</label>

                <input
                    type="text"
                    name="course_code"
                    id="modifyCourseCode"
                    list="modifyCourseCodeList"
                    placeholder="Example: IT 111"
                    autocomplete="off"
                    required
                >

                <datalist id="modifyCourseCodeList">
                    @foreach(
                        $modifyFacultySubjects
                            ->unique('course_code')
                        as $facultySubject
                    )
                        <option value="{{ $facultySubject->course_code }}"></option>
                    @endforeach
                </datalist>
                <select id="modifySubjectChoice" hidden style="width:100%;margin-top:9px;padding:10px;border:1px solid #bdcddd;border-radius:8px;background:#fff;color:#20364b;">
                    <option value="">Choose the matching year level and program</option>
                </select>
            </div>

            <div class="editor-field">
                <label for="modifySubject">Subject</label>
                <input type="text" id="modifySubject" readonly placeholder="Automatic">
            </div>

            <div class="editor-field">
                <label for="modifyYearLevel">Year Level</label>
                <input type="text" id="modifyYearLevel" readonly placeholder="Automatic">
            </div>

            <div class="editor-field">
                <label for="modifyInstructor">Instructor</label>
                <input type="text" id="modifyInstructor" readonly placeholder="Automatic">
            </div>

            <div class="editor-field">
                <label for="modifyMajor">Major</label>
                <input type="text" name="major" id="modifyMajor" readonly placeholder="Automatic" required>
            </div>

            <div class="editor-field">
                <label for="modifyProgram">Program</label>
                <input type="text" name="program" id="modifyProgram" readonly placeholder="Automatic" required>
            </div>

            <input type="hidden" name="semester" id="modifySemesterValue" value="">
            <input type="hidden" name="academic_year" id="modifyAcademicYearValue" value="">

        </div>

        <div class="shared-audience-picker" id="modifySharedAudiencePicker" hidden>
            <div class="shared-audience-heading">Groups included in this assignment</div>
            <div class="shared-audience-choices" id="modifySharedAudienceChoices"></div>
            <div class="shared-audience-help">These groups were selected in Instructor Setup and will share this scheduled meeting automatically.</div>
        </div>

        <div class="editor-status" id="modifyEditorStatus">
            Select an editable cell from the timetable.
        </div>

        <div class="modify-entry-footer">
            <button type="button" class="editor-button editor-cancel" id="modifyCancelButton">
                <i class="fa-solid fa-xmark"></i>
                Cancel
            </button>
            <button type="submit" class="editor-button editor-save" id="modifyConfirmEntryButton">
                <i class="fa-solid fa-check"></i>
                Confirm Entry
            </button>
        </div>
    </form>

    <form
        method="POST"
        action="{{ route('chair.modify.schedule.clear') }}"
        id="modifyClearForm"
        style="display:none;"
    >
        @csrf


        <input type="hidden" name="room_id" id="modifyClearRoomId">
        <input
            type="hidden"
            name="schedule_id"
            id="modifyClearScheduleId"
        >
    </form>

</section>
</div>


{{-- =====================================================
     ROOM SCHEDULE
====================================================== --}}

<div
    class="schedule-section"
    id="scheduleSection"
    data-schedule-sheet
    style="display:none;"
>


    {{-- =================================================
         SCHEDULE HEADER
    ================================================== --}}

    <div class="schedule-header">

        <div>

            <div class="schedule-main-title">

                <i class="fa-solid fa-calendar-days"></i>

                Room Schedule

            </div>

            <div
                class="schedule-room-name"
                id="scheduleRoomName"
            >
                Room
            </div>

        </div>


        <div class="schedule-header-actions">
            <button
                type="button"
                class="change-room-button"
                onclick="backToRooms()"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Change Room
            </button>
        </div>

    </div>


    <div
        class="room-info"
        id="roomInfo"
    >
    </div>

    <div class="room-schedule-meta" id="roomScheduleMeta">

        <div class="room-schedule-meta-item">
            <span class="room-schedule-meta-label">Semester</span>
            <strong id="roomScheduleSemester">Not published</strong>
        </div>

        <div class="room-schedule-meta-item">
            <span class="room-schedule-meta-label">Academic Year</span>
            <strong id="roomScheduleAcademicYear">Not published</strong>
        </div>

    </div>


    <div class="permission-info">

        <i class="fa-solid fa-circle-info"></i>

        <span>
            Your college schedules can be modified.
            Other colleges' schedules are locked;
            empty slots in other colleges require permission.
        </span>

    </div>

    <div class="modify-sheet-toolbar" role="toolbar" aria-label="Modify schedule actions">
        <div class="modify-sheet-toolbar-left">
            <button type="button" class="editor-button" id="modifyMergeClassButton" data-merge-request-button disabled>Merge Class</button>
            <span class="modify-merge-target-label" id="modifyMergeTargetLabel">Select another college’s scheduled class to request a merge.</span>
            <div class="modify-sheet-toolbar-guide">
                <strong>Quick guide</strong>
                <span><b>1</b> Click a cell to select it.</span>
                <span><b>2</b> Right-click it to open course entry.</span>
                <span><b>3</b> Choose Confirm Entry to save the change.</span>
                <span><b>4</b> Select a cell to clear it or change its color.</span>
                <span><b>5</b> Drag a selected class edge by 30 minutes; adjacent classes move with it.</span>
            </div>
        </div>
        <div class="modify-sheet-toolbar-actions">
            @include('components.schedule-sheet-controls')
            <button type="button" class="editor-button editor-clear" id="modifyClearCellButton" disabled hidden>
                <i class="fa-solid fa-eraser"></i> Clear Cell
            </button>
            <label class="editor-color-inline" id="modifyColorControl" for="modifyColor" hidden>
                <span>Cell color</span>
                <input type="color" name="color" id="modifyColor" value="#ffffff" form="modifyCellForm" title="Choose selected cell color" disabled>
            </label>
        </div>
    </div>


    {{-- =================================================
         EXCEL STYLE TABLE
    ================================================== --}}

    <div class="excel-wrapper" data-fit-schedule-container>

        <table class="excel-schedule" data-fit-schedule>

            <thead>

                <tr>

                    <th class="time-header">
                        TIME
                    </th>

                    <th>
                        MON
                    </th>

                    <th>
                        WED
                    </th>

                    <th>
                        FRI
                    </th>

                    <th class="time-header">
                        TIME
                    </th>

                    <th>
                        TUE
                    </th>

                    <th>
                        THU
                    </th>

                    <th class="time-header" data-saturday-header hidden>TIME</th>
                    <th data-saturday-header hidden>SAT</th>

                </tr>

            </thead>

            <tbody id="scheduleBody">

            </tbody>

        </table>

    </div>

</div>

            <script>

                /* =========================================================
   DATA
========================================================= */


@php
    $modifyFacultySubjectsData = ($facultySubjects ?? collect())
        ->groupBy(fn ($item) => implode('|', [
            (int) $item->faculty_id,
            strtoupper(trim((string) $item->course_code)),
            mb_strtolower(trim((string) $item->subject)),
        ]))
        ->map(function ($group) {
            $item = $group->first();
            $cohorts = $item->sharedCohorts
                ->concat($group->skip(1)->map(fn ($assignment) => $assignment->subjectCatalog))
                ->filter()
                ->unique('id')
                ->values();
            return [
                'id' => $item->id,
                'assignment_ids' => $group->pluck('id')->values()->all(),
                'course_code' => $item->course_code,
                'subject' => $item->subject,
                'year_level' => $item->year_level,
                'major' => $item->major ?? '',
                'program' => $item->program ?? '',
                'faculty_id' => $item->faculty_id ?? null,
                'instructor' => optional($item->faculty)->name ?? '',
                'subject_type' => $item->subject_type ?: (preg_match('/\.1\s*$/', trim($item->course_code)) ? 'Lab' : 'Lec'),
                'shared_cohorts' => $cohorts->map(fn ($cohort) => [
                    'program' => $cohort->program?->name,
                    'program_code' => $cohort->program?->code,
                    'major' => $cohort->program?->major,
                    'year_level' => $cohort->year_level,
                    'semester' => $cohort->semester,
                ])->values()->all(),
            ];
        })
        ->values()
        ->toArray();
@endphp

const modifyFacultySubjects = @json($modifyFacultySubjectsData);

const departments =
    @json($departments);
const modifyRoomPickerUrl = @json(route('chair.modifyschedule', ['term_id' => $activeTerm->id]));
const crossScheduleExcelBaseUrl = @json(url('/chair/excel'));
const crossScheduleTermId = @json($activeTerm->id);
const initialModifyRoomId = @json($initialRoomId);
const isModifyRoomPage = Boolean(initialModifyRoomId);

const crossScheduleRoomSearch = document.getElementById('crossScheduleRoomSearch');
const clearCrossScheduleRoomSearch = document.getElementById('clearCrossScheduleRoomSearch');
const crossScheduleRoomSearchStatus = document.getElementById('crossScheduleRoomSearchStatus');
const crossScheduleRoomSearchResults = document.getElementById('crossScheduleRoomSearchResults');
const crossScheduleRoomSearchEmpty = document.getElementById('crossScheduleRoomSearchEmpty');

let selectedDepartment = null;
let selectedBuilding = null;
let selectedRoom = null;

function filterCrossScheduleRooms()
{
    const query = String(crossScheduleRoomSearch?.value || '').trim().toLocaleLowerCase();
    if (clearCrossScheduleRoomSearch) clearCrossScheduleRoomSearch.hidden = !query;

    if (!query) {
        if (crossScheduleRoomSearchResults) crossScheduleRoomSearchResults.hidden = true;
        if (crossScheduleRoomSearchEmpty) crossScheduleRoomSearchEmpty.hidden = true;
        document.getElementById('departmentLevel').style.display = selectedDepartment ? 'none' : 'block';
        document.getElementById('buildingLevel').style.display = selectedDepartment && !selectedBuilding ? 'block' : 'none';
        document.getElementById('roomLevel').style.display = selectedBuilding ? 'block' : 'none';
        if (crossScheduleRoomSearchStatus) crossScheduleRoomSearchStatus.textContent = 'Search across colleges, buildings, and rooms.';
        return;
    }

    document.getElementById('departmentLevel').style.display = 'none';
    document.getElementById('buildingLevel').style.display = 'none';
    document.getElementById('roomLevel').style.display = 'none';
    crossScheduleRoomSearchResults.replaceChildren();

    const matches = [];
    (departments || []).forEach(function(department) {
        (department.buildings || []).forEach(function(building) {
            (building.rooms || []).forEach(function(room) {
                const searchableText = [
                    room.room_name,
                    room.purpose,
                    room.capacity,
                    room.computers,
                    room.has_tv ? 'tv television available' : 'no tv',
                    room.has_projector ? 'projector available' : 'no projector',
                    building.name,
                    department.name,
                ].filter(value => value !== null && value !== undefined).join(' ').toLocaleLowerCase();

                if (searchableText.includes(query)) matches.push({ department, building, room });
            });
        });
    });

    matches.forEach(function(match) {
        const card = document.createElement('article');
        card.className = 'room-selection-card room-search-result-card';
        card.innerHTML = `
            <div class="room-selection-top">
                <div class="room-selection-icon"><i class="fa-solid fa-door-open" aria-hidden="true"></i></div>
                <div>
                    <div class="room-selection-name">${escapeHtml(match.room.room_name || 'Classroom')}</div>
                    <div class="room-selection-label">${escapeHtml(match.department.name || 'College')} · ${escapeHtml(match.building.name || 'Building')}</div>
                </div>
            </div>
            <button type="button" class="open-room-button"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i> ${match.room.allow_other_departments ? 'Open Excel editor' : 'View schedule'}</button>
        `;
        card.querySelector('.open-room-button').addEventListener('click', function() {
            openRoom(match.room.id);
        });
        crossScheduleRoomSearchResults.appendChild(card);
    });

    crossScheduleRoomSearchResults.hidden = matches.length === 0;
    crossScheduleRoomSearchEmpty.hidden = matches.length > 0;
    crossScheduleRoomSearchStatus.textContent = `${matches.length} ${matches.length === 1 ? 'room' : 'rooms'} found.`;
}

crossScheduleRoomSearch?.addEventListener('input', filterCrossScheduleRooms);
clearCrossScheduleRoomSearch?.addEventListener('click', function() {
    if (!crossScheduleRoomSearch) return;
    crossScheduleRoomSearch.value = '';
    filterCrossScheduleRooms();
    crossScheduleRoomSearch.focus();
});


/* =========================================================
   SELECT DEPARTMENT
========================================================= */

function selectDepartment(departmentId)
{
    selectedDepartment =
        departments.find(
            department =>
                Number(department.id) ===
                Number(departmentId)
        );

    if (!selectedDepartment) {
        return;
    }

    document.querySelector('.navigation-section').style.display = 'block';

    document.getElementById(
        'selectedDepartmentName'
    ).textContent =
        selectedDepartment.name;


    const buildingList =
        document.getElementById(
            'buildingList'
        );

    buildingList.innerHTML = '';


    const buildings =
        selectedDepartment.buildings || [];


    if (buildings.length === 0) {

        buildingList.innerHTML = `
            <div class="empty">
                No buildings are assigned
                to this department.
            </div>
        `;

    } else {

        buildings.forEach(
            function(building)
            {

                const rooms =
                    building.rooms || [];

                const card =
                    document.createElement('button');

                card.type = 'button';

                card.className =
                    'building-card';

                card.onclick =
                    function()
                    {
                        selectBuilding(
                            building.id
                        );
                    };


                card.innerHTML = `

                    <div class="card-left">

                        <div class="card-icon">

                            <i class="fa-solid fa-building"></i>

                        </div>

                        <div>

                            <div class="card-name">

                                ${escapeHtml(
                                    building.name
                                )}

                            </div>

                            <div class="card-count">

                                ${rooms.length}
                                ${rooms.length === 1
                                    ? 'room'
                                    : 'rooms'}

                            </div>

                        </div>

                    </div>


                    <div class="card-arrow">

                        <i class="fa-solid fa-chevron-right"></i>

                    </div>

                `;


                buildingList.appendChild(card);

            }
        );

    }


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'block';


    document.getElementById(
        'roomLevel'
    ).style.display = 'none';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';
}


/* =========================================================
   SELECT BUILDING
========================================================= */

function selectBuilding(buildingId)
{
    if (!selectedDepartment) {
        return;
    }


    selectedBuilding =
        (selectedDepartment.buildings || [])
            .find(
                building =>
                    Number(building.id) ===
                    Number(buildingId)
            );


    if (!selectedBuilding) {
        return;
    }

    document.querySelector('.navigation-section').style.display = 'block';


    document.getElementById(
        'selectedBuildingName'
    ).textContent =
        selectedBuilding.name;


    const roomList =
        document.getElementById(
            'roomList'
        );

    roomList.innerHTML = '';


    const rooms =
        selectedBuilding.rooms || [];


    if (rooms.length === 0) {

        roomList.innerHTML = `
            <div class="empty">
                No rooms are assigned
                to this building.
            </div>
        `;

    } else {

        rooms.forEach(
            function(room)
            {

                const card =
                    document.createElement('div');

                card.className =
                    'room-selection-card';


                card.innerHTML = `

                    <div class="room-selection-top">

                        <div class="room-selection-icon">

                            <i class="fa-solid fa-door-open"></i>

                        </div>

                        <div>

                            <div class="room-selection-name">

                                ${escapeHtml(
                                    room.room_name
                                )}

                            </div>

                            <div class="room-selection-label">${(room.schedules || []).length} scheduled classes · ${room.allow_other_departments ? 'You can add to open slots' : 'View only — permission is off'}</div>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="open-room-button"
                        onclick="openRoom(
                            ${room.id}
                        )"
                    >

                        <i class="fa-solid fa-calendar-days"></i>

                        ${room.allow_other_departments ? 'Open Excel editor' : 'View schedule'}

                    </button>

                `;


                roomList.appendChild(card);

            }
        );

    }


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'none';


    document.getElementById(
        'roomLevel'
    ).style.display = 'block';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';
}


/* =========================================================
   OPEN ROOM
========================================================= */

function renderRoomSchedule(roomId)
{
    if (!selectedBuilding) {
        return;
    }


    selectedRoom =
        (selectedBuilding.rooms || [])
            .find(
                room =>
                    Number(room.id) ===
                    Number(roomId)
            );


    if (!selectedRoom) {
        return;
    }


    document.getElementById(
        'scheduleRoomName'
    ).textContent =
        selectedRoom.room_name;


    document.getElementById(
        'roomInfo'
    ).innerHTML = `

        <strong>
            ${escapeHtml(
                selectedDepartment.name
            )}
        </strong>

        &nbsp; / &nbsp;

        ${escapeHtml(
            selectedBuilding.name
        )}

        &nbsp; / &nbsp;

        <strong>
            ${escapeHtml(
                selectedRoom.room_name
            )}
        </strong>

    `;


    const roomSchedules =
        selectedRoom.schedules || [];

    const roomMetaSchedule =
        roomSchedules.find(function(item) {
            return (
                String(item.semester || '').trim() ||
                String(item.academic_year || '').trim()
            );
        }) || roomSchedules[0] || null;

    modifyRoomMeta = {
        semester: roomMetaSchedule
            ? (roomMetaSchedule.semester || '')
            : '',
        academic_year: roomMetaSchedule
            ? (roomMetaSchedule.academic_year || '')
            : ''
    };

    modifySemesterValue.value =
        modifyRoomMeta.semester;

    modifyAcademicYearValue.value =
        modifyRoomMeta.academic_year;

    document.getElementById('roomScheduleSemester').textContent =
        modifyRoomMeta.semester || 'Not published';

    document.getElementById('roomScheduleAcademicYear').textContent =
        modifyRoomMeta.academic_year || 'Not published';

buildExcelSchedule(
        selectedRoom
    );


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'none';


    document.getElementById(
        'roomLevel'
    ).style.display = 'none';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'block';

    document.querySelector('.navigation-section').style.display = 'none';


    clearModifyEditor();
}


/* =========================================================
   BACK TO DEPARTMENTS
========================================================= */

function openRoom(roomId)
{
    window.location.href = crossScheduleExcelBaseUrl + '/' + encodeURIComponent(roomId)
        + '?term_id=' + encodeURIComponent(crossScheduleTermId) + '&mode=cross';
}
function backToDepartments()
{
    document.querySelector('.navigation-section').style.display = 'block';
    selectedDepartment = null;

    selectedBuilding = null;

    selectedRoom = null;


    document.getElementById(
        'departmentLevel'
    ).style.display = 'block';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'none';


    document.getElementById(
        'roomLevel'
    ).style.display = 'none';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';

    clearModifyEditor();
}


/* =========================================================
   BACK TO BUILDINGS
========================================================= */

function backToBuildings()
{
    document.querySelector('.navigation-section').style.display = 'block';
    selectedBuilding = null;

    selectedRoom = null;


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'block';


    document.getElementById(
        'roomLevel'
    ).style.display = 'none';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';

    clearModifyEditor();
}


/* =========================================================
   BACK TO ROOMS
========================================================= */

function backToRooms()
{    if (isModifyRoomPage) {
        window.location.href = modifyRoomPickerUrl;
        return;
    }

    document.querySelector('.navigation-section').style.display = 'block';

    selectedRoom = null;


    document.getElementById(
        'departmentLevel'
    ).style.display = 'none';


    document.getElementById(
        'buildingLevel'
    ).style.display = 'none';


    document.getElementById(
        'roomLevel'
    ).style.display = 'block';


    document.getElementById(
        'scheduleSection'
    ).style.display = 'none';

    clearModifyEditor();
}


/* =========================================================
   EXCEL-STYLE SCHEDULE
========================================================= */

function buildExcelSchedule(room)
{
    const schedules =
        room.schedules || [];

    const hasSaturdaySchedule = schedules.some(function(schedule) {
        return ['SAT', 'SATURDAY'].includes(String(schedule.day || '').trim().toUpperCase());
    });

    document.querySelectorAll('.excel-schedule [data-saturday-header]').forEach(function(header) {
        header.hidden = !hasSaturdaySchedule;
    });


    const currentDepartmentId =
        Number(
            window.chairDepartmentId
        );


    const roomDepartmentId =
        Number(
            room.department_id
        );


    const allowOtherDepartments =
        Number(
            room.allow_other_departments
        ) === 1;


    /*
     * Keep the same basic time structure
     * as Set Schedule.
     */

    const times = [
        '7:00-8:00',
        '8:00-9:00',
        '9:00-10:00',
        '10:00-11:00',
        '11:00-12:00',
        '1:00-2:00',
        '2:00-3:00',
        '3:00-4:00',
        '4:00-5:00',
        '5:00-6:00',
        '6:00-7:00'
    ];


    const leftDays = [
        'MON',
        'WED',
        'FRI'
    ];


    const rightDays = [
        'TUE',
        'THU'
    ];


    let html = '';


    times.forEach(
        function(time, index)
        {

            /*
             * NOON BREAK
             */

            if (index === 5) {

                html += `

                    <tr class="noon-break-row">

                        <td colspan="${hasSaturdaySchedule ? 9 : 7}">

                            N O O N &nbsp; B R E A K

                        </td>

                    </tr>

                `;

            }


            html += '<tr>';


            /*
             * LEFT TIME
             */

            html += `

                <td class="time-cell">

                    ${time}

                </td>

            `;


            /*
             * MON / WED / FRI
             */

            leftDays.forEach(
                function(day)
                {

                    html += buildModifyCell(
                        schedules,
                        day,
                        time,
                        room,
                        currentDepartmentId,
                        roomDepartmentId,
                        allowOtherDepartments
                    );

                }
            );


            /*
             * RIGHT TIME
             */

            html += `

                <td class="time-cell">

                    ${time}

                </td>

            `;


            /*
             * TUE / THU
             */

            rightDays.forEach(
                function(day)
                {

                    html += buildModifyCell(
                        schedules,
                        day,
                        time,
                        room,
                        currentDepartmentId,
                        roomDepartmentId,
                        allowOtherDepartments
                    );

                }
            );

            if (hasSaturdaySchedule) {
                html += `<td class="time-cell">${escapeHtml(time)}</td>`;
                html += buildModifyCell(
                    schedules,
                    'SAT',
                    time,
                    room,
                    currentDepartmentId,
                    roomDepartmentId,
                    allowOtherDepartments
                );
            }


            html += '</tr>';

        }
    );


    document.getElementById(
        'scheduleBody'
    ).innerHTML = html;
    renderModifyScheduleBlocks();
}


/* =========================================================
   BUILD INDIVIDUAL CELL
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

function renderMergedCourseDetails(schedule)
{
    const merges = Array.isArray(schedule.merges) ? schedule.merges : [];
    const approved = merges.filter(function(merge) { return String(merge.status || '').toLowerCase() === 'approved'; });
    const primaryMajor = String(schedule.major || '').trim().toLowerCase();
    const sharedMajors = [...new Set(approved
        .filter(function(merge) { return String(merge.share_type || '').toLowerCase() === 'same_faculty'; })
        .map(function(merge) {
            const catalog = merge.subject || merge.faculty_subject?.subject_catalog || {};
            return String(catalog.program?.major || merge.faculty_subject?.major || '').trim();
        })
        .filter(function(major) { return major && major.toLowerCase() !== primaryMajor; }))];
    const details = approved
        .filter(function(merge) { return String(merge.share_type || '').toLowerCase() !== 'same_faculty'; })
        .map(function(merge) {
            const catalog = merge.subject || merge.faculty_subject?.subject_catalog || {};
            const code = catalog.course_code || merge.faculty_subject?.course_code || '';
            const title = catalog.course_title || merge.faculty_subject?.subject || '';
            const program = catalog.program?.code || merge.faculty_subject?.subject_catalog?.program?.code || displayProgramCode(catalog.program?.name || merge.faculty_subject?.program || '');
            if (!code && !title) return '';
            return escapeHtml([code, title].filter(Boolean).join(' — ') + (program ? ' · ' + program : ''));
        })
        .filter(Boolean);

    if (sharedMajors.length) {
        details.unshift(`<span class="cell-shared-majors"><strong>Also shared with</strong><span>${sharedMajors.map(escapeHtml).join(' · ')}</span></span>`);
    }

    return details.length
        ? `<div class="merged-course-details">${details.join('<br>')}</div>`
        : '';
}

function renderPendingMergeDetails(schedule, incoming)
{
    const currentDepartmentId = Number(window.chairDepartmentId || 0);
    const pending = (Array.isArray(schedule.merges) ? schedule.merges : [])
        .filter(function(merge) {
            if (String(merge.status || '').toLowerCase() !== 'pending') return false;
            const requesterDepartmentId = Number(merge.department_id || merge.department?.id || 0);
            return incoming
                ? requesterDepartmentId !== currentDepartmentId
                : requesterDepartmentId === currentDepartmentId;
        });

    if (!pending.length) return '';
    return `<div class="schedule-merge-pending-label">${incoming ? pending.length + ' merge request' + (pending.length === 1 ? '' : 's') + ' waiting for your review' : 'Your merge request is waiting for approval'}</div>`;
}

function modifyClockMinutes(value)
{
    const match = String(value || '').trim().match(/^(\d{1,2}):(\d{2})(?:\s*(AM|PM))?$/i);
    if (!match) return null;

    let hour = Number(match[1]);
    const minute = Number(match[2]);
    const meridiem = String(match[3] || '').toUpperCase();
    if (meridiem === 'PM' && hour < 12) hour += 12;
    if (meridiem === 'AM' && hour === 12) hour = 0;
    if (!meridiem && hour >= 1 && hour <= 6) hour += 12;
    return hour <= 23 && minute <= 59 ? hour * 60 + minute : null;
}

function modifyScheduleStartsInSlot(scheduleTime, slotTime)
{
    const scheduleParts = String(scheduleTime || '').trim().replace(/[–—]/g, '-').split('-');
    const slotParts = String(slotTime || '').trim().replace(/[–—]/g, '-').split('-');
    if (scheduleParts.length !== 2 || slotParts.length !== 2) return false;

    const start = modifyClockMinutes(scheduleParts[0]);
    const slotStart = modifyClockMinutes(slotParts[0]);
    const slotEnd = modifyClockMinutes(slotParts[1]);
    return start !== null && slotStart !== null && slotEnd !== null
        && start >= slotStart && start < slotEnd;
}

function modifyParseRange(value)
{
    const parts = String(value || '').trim().replace(/[–—]/g, '-').split(/\s*-\s*/, 2);
    if (parts.length !== 2) return null;
    const start = modifyClockMinutes(parts[0]);
    let end = modifyClockMinutes(parts[1]);
    if (start === null || end === null) return null;
    if (!/\b(?:AM|PM)\s*$/i.test(parts[1]) && end <= start) {
        const hour = Number(String(parts[1]).match(/^(\d{1,2})/)?.[1] || 0);
        if (hour >= 1 && hour <= 11) end += 720;
    }
    return end > start ? { start: start, end: end } : null;
}

function modifyFormatRange(start, end)
{
    const clock = function(minutes) {
        const hour = Math.floor(minutes / 60) % 24;
        return String(hour).padStart(2, '0') + ':' + String(minutes % 60).padStart(2, '0');
    };
    return clock(start) + '-' + clock(end);
}

function modifyFormatRangeLabel(start, end)
{
    const clock = function(minutes) {
        const hour = Math.floor(minutes / 60) % 24;
        return (hour % 12 || 12) + ':' + String(minutes % 60).padStart(2, '0');
    };
    const startPeriod = Math.floor(start / 60) % 24 < 12 ? 'AM' : 'PM';
    const endPeriod = Math.floor(end / 60) % 24 < 12 ? 'AM' : 'PM';
    return startPeriod === endPeriod
        ? clock(start) + '–' + clock(end) + ' ' + endPeriod
        : clock(start) + ' ' + startPeriod + '–' + clock(end) + ' ' + endPeriod;
}

function modifyFormatScheduleTime(value)
{
    const range = modifyParseRange(value);
    return range ? modifyFormatRangeLabel(range.start, range.end) : String(value || '');
}

function modifyCellRange(cell)
{
    return modifyParseRange(cell?.dataset.scheduleTime || cell?.dataset.time || '');
}

function refreshModifyAdjustmentInput(excludeScheduleId = '')
{
    const adjustments = Array.from(document.querySelectorAll('#scheduleBody .schedule-cell.filled[data-schedule-id]'))
        .filter(function(cell) {
            if (String(cell.dataset.scheduleId || '') === String(excludeScheduleId || '')) return false;
            const original = modifyParseRange(cell.dataset.originalTime || '');
            const current = modifyCellRange(cell);
            return original && current && (original.start !== current.start || original.end !== current.end);
        })
        .map(cell => ({ schedule_id: cell.dataset.scheduleId, day: cell.dataset.day, time: cell.dataset.scheduleTime || cell.dataset.time }));
    const input = document.getElementById('modifyScheduleAdjustments');
    if (input) input.value = JSON.stringify(adjustments);
}

function prepareModifyEmptyCellTime(cell)
{
    const base = modifyParseRange(cell.dataset.time || '');
    if (!base || base.end - base.start !== 60) return;
    const halves = [{ start: base.start, end: base.start + 30 }, { start: base.start + 30, end: base.end }];
    const available = halves.filter(function(candidate) {
        return !Array.from(document.querySelectorAll('#scheduleBody .schedule-cell.filled')).some(function(other) {
            if (other === cell || other.dataset.day !== cell.dataset.day) return false;
            const range = modifyCellRange(other);
            return range && candidate.start < range.end && candidate.end > range.start;
        });
    });
    const range = available.length === 1 ? available[0] : base;
    cell.dataset.scheduleTime = modifyFormatRange(range.start, range.end);
}

function modifyTimeIndexForCell(cell)
{
    return cell.cellIndex <= 3 ? 0 : cell.cellIndex <= 6 ? 4 : 7;
}

function modifyYAtMinute(cell, minute, edge)
{
    const timeIndex = modifyTimeIndexForCell(cell);
    const rows = Array.from(document.querySelectorAll('#scheduleBody > tr:not(.noon-break-row)'));
    let lastPoint = null;
    for (const row of rows) {
        const timeCell = row.cells[timeIndex];
        const range = timeCell ? modifyParseRange(timeCell.textContent.trim()) : null;
        if (!range || minute < range.start || minute > range.end) continue;
        const rect = timeCell.getBoundingClientRect();
        lastPoint = rect.top + ((minute - range.start) / (range.end - range.start)) * rect.height;
        if (edge === 'start' && minute < range.end) return lastPoint;
        if (edge === 'end' && minute > range.start) return lastPoint;
    }
    return lastPoint;
}

function modifyMinuteAtY(cell, clientY)
{
    const timeIndex = modifyTimeIndexForCell(cell);
    const rows = Array.from(document.querySelectorAll('#scheduleBody > tr:not(.noon-break-row)'));
    const boundaries = [];
    for (const row of rows) {
        const timeCell = row.cells[timeIndex];
        const range = timeCell ? modifyParseRange(timeCell.textContent.trim()) : null;
        if (!range) continue;
        const rect = timeCell.getBoundingClientRect();
        boundaries.push({ y: rect.top, minute: range.start }, { y: rect.bottom, minute: range.end });
        if (clientY >= rect.top && clientY <= rect.bottom) {
            const fraction = Math.max(0, Math.min(1, (clientY - rect.top) / rect.height));
            return Math.round((range.start + fraction * (range.end - range.start)) / 30) * 30;
        }
    }
    boundaries.sort((a, b) => Math.abs(clientY - a.y) - Math.abs(clientY - b.y));
    return boundaries.length ? boundaries[0].minute : null;
}

function modifyBuildResizePlan(source, candidate, original, edge, snapshots = new Map())
{
    const dayStart = 7 * 60;
    const dayEnd = 19 * 60;
    const { start, end } = candidate;
    const insideClassHours = (start >= dayStart && end <= 12 * 60)
        || (start >= 13 * 60 && end <= dayEnd);
    if (!insideClassHours || end - start < 30) return null;

    for (const other of document.querySelectorAll('#scheduleBody .schedule-cell.filled[data-schedule-id]')) {
        if (other === source || other.dataset.day !== source.dataset.day) continue;
        const range = snapshots.get(other) || modifyCellRange(other);
        if (range && start < range.end && end > range.start) return null;
    }
    return new Map([[source, candidate]]);
}

function modifyResizeHasConflict(source, start, end)
{
    const candidate = { start: start, end: end };
    const initial = modifyCellRange(source);
    return !initial || !modifyBuildResizePlan(source, candidate, initial, end !== initial.end ? 'end' : 'start');
}

function positionModifyScheduleBlock(block, cell, range, spanCells = [cell])
{
    const wrapper = document.querySelector('.chair-modify-view .excel-wrapper');
    if (!wrapper || !block || !cell || !range) return;
    const wrapperRect = wrapper.getBoundingClientRect();
    const cellRect = cell.getBoundingClientRect();
    const top = modifyYAtMinute(cell, range.start, 'start');
    const bottom = modifyYAtMinute(cell, range.end, 'end');
    if (top === null || bottom === null || bottom <= top) return;
    block.style.left = (cellRect.left - wrapperRect.left - wrapper.clientLeft + wrapper.scrollLeft) + 'px';
    block.style.top = (top - wrapperRect.top - wrapper.clientTop + wrapper.scrollTop) + 'px';
    const lastRect = spanCells.length > 1 ? spanCells[spanCells.length - 1].getBoundingClientRect() : cellRect;
    block.style.width = Math.max(cellRect.width, lastRect.right - cellRect.left) + 'px';
    block.style.height = (bottom - top) + 'px';
    const detailsTime = block.querySelector('.schedule-time:not(.modify-time-badge)');
    if (detailsTime) detailsTime.textContent = 'Time: ' + modifyFormatRangeLabel(range.start, range.end);
    const badge = block.querySelector('.modify-time-badge');
    if (badge) badge.textContent = modifyFormatRangeLabel(range.start, range.end);
}

function renderModifyScheduleBlocks()
{
    const wrapper = document.querySelector('.chair-modify-view .excel-wrapper');
    const table = wrapper?.querySelector('.excel-schedule');
    if (!wrapper || !table) return;
    let layer = wrapper.querySelector('.modify-schedule-block-layer');
    if (!layer) {
        layer = document.createElement('div');
        layer.className = 'modify-schedule-block-layer';
        wrapper.appendChild(layer);
    }
    layer.replaceChildren();
    layer.style.width = table.scrollWidth + 'px';
    layer.style.height = table.scrollHeight + 'px';
    const wrapperRect = wrapper.getBoundingClientRect();

    const sourceCells = Array.from(document.querySelectorAll('#scheduleBody .schedule-cell.filled'));
    const signatureFor = function(cell) {
        if (!cell.classList.contains('editable-cell') || cell.dataset.locked === '1') return '';
        const range = modifyCellRange(cell);
        if (!range) return '';
        return JSON.stringify([
            cell.dataset.course, cell.dataset.subject, cell.dataset.instructor,
            cell.dataset.facultySubjectId, cell.dataset.sharedOfferingIds,
            cell.dataset.year, cell.dataset.major, cell.dataset.program,
            cell.dataset.semester, cell.dataset.academicYear, cell.dataset.color,
            range.start, range.end
        ].map(value => String(value || '').trim().toLowerCase()));
    };
    const handled = new Set();
    sourceCells.filter(cell => cell.classList.contains('editable-cell') && cell.dataset.scheduleId).forEach(function(cell) {
        if (handled.has(cell)) return;
        cell.classList.remove('modify-block-source');
        const range = modifyCellRange(cell);
        if (!range) return;
        const rowCells = Array.from(cell.parentElement.querySelectorAll('.schedule-cell.filled'))
            .filter(candidate => candidate.classList.contains('editable-cell'))
            .sort((a, b) => a.getBoundingClientRect().left - b.getBoundingClientRect().left);
        const index = rowCells.indexOf(cell);
        const signature = signatureFor(cell);
        const spanCells = [cell];
        for (let cursor = index + 1; signature && cursor > -1 && cursor < rowCells.length; cursor++) {
            const candidate = rowCells[cursor];
            if (signatureFor(candidate) !== signature) break;
            const previousRect = spanCells[spanCells.length - 1].getBoundingClientRect();
            const candidateRect = candidate.getBoundingClientRect();
            if (Math.abs(candidateRect.left - previousRect.right) > 3) break;
            spanCells.push(candidate);
        }
        spanCells.forEach(candidate => handled.add(candidate));
        const block = document.createElement('div');
        block.className = 'modify-schedule-block';
        block.dataset.scheduleId = cell.dataset.scheduleId || '';
        block.style.backgroundColor = cell.dataset.color || '#eaf6ed';
        block.innerHTML = cell.innerHTML;
        block.querySelector('.editable-label')?.remove();
        const timeLabel = document.createElement('span');
        timeLabel.className = 'schedule-time modify-time-badge';
        timeLabel.textContent = modifyFormatRangeLabel(range.start, range.end);
        block.appendChild(timeLabel);
        if (spanCells.length > 1) {
            const dayLabel = document.createElement('div');
            dayLabel.className = 'modify-auto-merge-days';
            dayLabel.textContent = spanCells.map(item => String(item.dataset.day || '').toUpperCase().slice(0, 3)).join(' · ');
            block.prepend(dayLabel);
        }
        positionModifyScheduleBlock(block, cell, range, spanCells);
        block.classList.toggle('is-selected', spanCells.includes(selectedModifyCell));

        block.addEventListener('click', function(event) {
            if (event.target.closest('.modify-resize-handle')) return;
            event.preventDefault();
            event.stopPropagation();
            if (spanCells.length > 1) {
                document.querySelectorAll('#scheduleBody .schedule-cell.selected-cell').forEach(item => item.classList.remove('selected-cell'));
                spanCells.forEach(item => item.classList.add('selected-cell'));
                selectedModifyCell = cell;
                renderModifyScheduleBlocks();
            } else cell.click();
        });
        block.addEventListener('contextmenu', function(event) {
            event.preventDefault();
            event.stopPropagation();
            if (selectedModifyCell !== cell) {
                if (spanCells.length > 1) {
                    document.querySelectorAll('#scheduleBody .schedule-cell.selected-cell').forEach(item => item.classList.remove('selected-cell'));
                    spanCells.forEach(item => item.classList.add('selected-cell'));
                    selectedModifyCell = cell;
                    renderModifyScheduleBlocks();
                } else cell.click();
            }
            openAddModal(selectedRoom ? selectedRoom.id : '', cell.dataset.day || '', cell.dataset.time || '', cell);
        });

        ['start', 'end'].forEach(function(edge) {
            const handle = document.createElement('button');
            handle.type = 'button';
            handle.className = 'modify-resize-handle ' + edge;
            handle.innerHTML = '<span></span>';
            handle.title = 'Drag to adjust by 30 minutes';
            handle.setAttribute('aria-label', edge === 'start' ? 'Adjust start time' : 'Adjust end time');
            handle.addEventListener('pointerdown', function(event) {
                if (event.button !== undefined && event.button !== 0) return;
                event.preventDefault();
                event.stopPropagation();
                if (selectedModifyCell !== cell) {
                    cell.click();
                    return;
                }
                if (event.pointerId !== undefined && handle.setPointerCapture) {
                    try { handle.setPointerCapture(event.pointerId); } catch (error) { /* Keep document tracking as fallback. */ }
                }
                const initial = modifyCellRange(cell);
                if (!initial) return;
                const original = { start: initial.start, end: initial.end };
                const snapshots = new Map(Array.from(document.querySelectorAll('#scheduleBody .schedule-cell.filled[data-schedule-id]'))
                    .filter(item => item.dataset.day === cell.dataset.day)
                    .map(item => [item, modifyCellRange(item)])
                    .filter(item => item[1]));
                const pointerId = event.pointerId;
                const move = function(moveEvent) {
                    if (pointerId !== undefined && moveEvent.pointerId !== pointerId) return;
                    if (moveEvent.cancelable) moveEvent.preventDefault();
                    const minute = modifyMinuteAtY(cell, moveEvent.clientY);
                    if (minute === null) return;
                    const next = { start: edge === 'start' ? minute : original.start, end: edge === 'end' ? minute : original.end };
                    if (next.end - next.start < 30) {
                        block.classList.add('resize-conflict');
                        return;
                    }
                    const plan = modifyBuildResizePlan(cell, next, original, edge, snapshots);
                    if (!plan) {
                        block.classList.add('resize-conflict');
                        return;
                    }
                    block.classList.remove('resize-conflict');
                    snapshots.forEach(function(range, plannedCell) {
                        plannedCell.dataset.scheduleTime = modifyFormatRange(range.start, range.end);
                    });
                    plan.forEach(function(range, plannedCell) {
                        plannedCell.dataset.scheduleTime = modifyFormatRange(range.start, range.end);
                        const plannedBlock = document.querySelector('.modify-schedule-block[data-schedule-id="' + String(plannedCell.dataset.scheduleId || '').replace(/"/g, '\\"') + '"]');
                    if (plannedBlock) positionModifyScheduleBlock(plannedBlock, plannedCell, range);
                    });
                    refreshModifyAdjustmentInput(cell.dataset.scheduleId || '');
                    modifyFormTime.value = cell.dataset.scheduleTime;
                };
                const finish = function() {
                    document.removeEventListener('pointermove', move);
                    document.removeEventListener('pointerup', finish);
                    document.removeEventListener('pointercancel', finish);
                    renderModifyScheduleBlocks();
                };
                document.addEventListener('pointermove', move, { passive: false });
                document.addEventListener('pointerup', finish, { once: true });
                document.addEventListener('pointercancel', finish, { once: true });
            });
            block.appendChild(handle);
        });
        cell.classList.add('modify-block-source');
        layer.appendChild(block);
    });
}

window.addEventListener('resize', renderModifyScheduleBlocks);

function buildModifyCell(
    schedules,
    day,
    time,
    room,
    currentDepartmentId,
    roomDepartmentId,
    allowOtherDepartments
)
{
    const schedule =
        schedules.find(
            function(item)
            {
                return (
                    String(item.day || '')
                        .trim()
                        .toUpperCase() === day &&
                    modifyScheduleStartsInSlot(item.time, time)
                );
            }
        );

    /* Existing schedule: editable only when it belongs to the logged-in chair's department. */
    if (schedule) {

        const scheduleDepartmentId =
            Number(schedule.department_id || roomDepartmentId);

        const isOwnSchedule =
            scheduleDepartmentId === currentDepartmentId;

        if (isOwnSchedule) {

            return `
                <td
                    class="schedule-cell filled editable-cell"
                    data-day="${day}"
                    data-time="${escapeHtml(time)}"
                    data-schedule-time="${escapeHtml(schedule.time || time)}"
                    data-original-time="${escapeHtml(schedule.time || time)}"
                    data-schedule-id="${Number(schedule.id || 0)}"
                    data-faculty-subject-id="${escapeHtml(schedule.faculty_subject_id || '')}"
                    data-shared-offering-ids="${escapeHtml((Array.isArray(schedule.merges) ? schedule.merges : []).filter(function(merge) { return merge.status === 'approved' && merge.share_type === 'same_faculty'; }).map(function(merge) { return merge.subject_id; }).filter(Boolean).join(','))}"
                    data-course="${escapeHtml(schedule.course_code || '')}"
                    data-subject="${escapeHtml(schedule.subject || '')}"
                    data-year="${escapeHtml(schedule.year_level || '')}"
                    data-instructor="${escapeHtml(schedule.instructor || '')}"
                    data-major="${escapeHtml(schedule.major || '')}"
                    data-program="${escapeHtml(schedule.program || '')}"
                    data-semester="${escapeHtml(schedule.semester || '')}"
                    data-academic-year="${escapeHtml(schedule.academic_year || '')}"
                    data-color="${escapeHtml(schedule.color || '#eaf6ed')}"
                    title="Your college schedule — click to select, then right-click to edit"
                    style="background-color:${escapeHtml(schedule.color || '#eaf6ed')};"
                >

                    <div class="schedule-course">
                        ${escapeHtml(schedule.course_code || '')}
                    </div>

                    <div class="schedule-subject">
                        ${escapeHtml(schedule.subject || '')}
                    </div>

                    <div class="schedule-instructor">
                        ${escapeHtml(schedule.instructor || '')}
                    </div>

                    <div class="schedule-time">
                        Time: ${escapeHtml(modifyFormatScheduleTime(schedule.time || time))}
                    </div>

                    <div class="schedule-year">
                        ${escapeHtml(schedule.year_level || '')}
                    </div>

                    ${
                        schedule.major
                            ? `<div class="schedule-year">${escapeHtml(schedule.major)}</div>`
                            : ''
                    }

                    ${
                        schedule.program
                            ? `<div class="schedule-year">${escapeHtml(displayProgramCode(schedule.program_display || schedule.program))}</div>`
                            : ''
                    }

                    ${renderMergedCourseDetails(schedule)}

                    ${renderPendingMergeDetails(schedule, true)}

                    <span class="editable-label">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Your Schedule — Click to Edit
                    </span>

                </td>
            `;
        }

        const hasOutgoingMergeRequest = (Array.isArray(schedule.merges) ? schedule.merges : []).some(function(merge) {
            return String(merge.status || '').toLowerCase() === 'pending'
                && Number(merge.department_id || merge.department?.id || 0) === currentDepartmentId;
        });

        return `
            <td
                class="schedule-cell filled locked-cell"
                data-day="${day}"
                data-time="${escapeHtml(time)}"
                data-schedule-time="${escapeHtml(schedule.time || time)}"
                data-original-time="${escapeHtml(schedule.time || time)}"
                data-schedule-id="${Number(schedule.id || 0)}"
                data-merge-schedule-id="${Number(schedule.id || 0)}"
                data-merge-course-code="${escapeHtml(schedule.course_code || '')}"
                data-merge-subject="${escapeHtml(schedule.subject || '')}"
                data-merge-instructor="${escapeHtml(schedule.instructor || '')}"
                data-merge-department="${escapeHtml(schedule.college?.name || '')}"
                data-merge-program="${escapeHtml(schedule.program || '')}"
                data-merge-building="${escapeHtml(room.building?.name || room.building || '')}"
                data-merge-room="${escapeHtml(room.room_name || '')}"
                data-merge-day="${day}"
                data-merge-time="${escapeHtml(time)}"
                data-merge-semester="${escapeHtml(schedule.semester || '')}"
                data-merge-academic-year="${escapeHtml(schedule.academic_year || '')}"
                data-merge-pending="${hasOutgoingMergeRequest ? '1' : '0'}"
                title="This schedule belongs to another college and is locked."
                style="background-color:${escapeHtml(schedule.color || '#eaf6ed')};"
            >

                <div class="schedule-course">
                    ${escapeHtml(schedule.course_code || '')}
                </div>

                <div class="schedule-subject">
                    ${escapeHtml(schedule.subject || '')}
                </div>

                <div class="schedule-instructor">
                    ${escapeHtml(schedule.instructor || '')}
                </div>

                <div class="schedule-time">
                    Time: ${escapeHtml(modifyFormatScheduleTime(schedule.time || time))}
                </div>

                <div class="schedule-year">
                    ${escapeHtml(schedule.year_level || '')}
                </div>

                ${
                    schedule.major
                        ? `<div class="schedule-year">${escapeHtml(schedule.major)}</div>`
                        : ''
                }

                ${
                    schedule.program
                        ? `<div class="schedule-year">${escapeHtml(displayProgramCode(schedule.program_display || schedule.program))}</div>`
                        : ''
                }

                    ${renderMergedCourseDetails(schedule)}

                    ${renderPendingMergeDetails(schedule, false)}

                <span class="locked">
                    <i class="fa-solid fa-lock"></i>
                    Locked — Other College
                </span>

            </td>
        `;
    }

    /* Empty cell in chair's own room/department. */
    if (roomDepartmentId === currentDepartmentId) {

        return `
            <td
                class="schedule-cell empty-slot editable-cell"
                data-day="${day}"
                data-time="${escapeHtml(time)}"
                data-schedule-id=""
                data-course=""
                data-subject=""
                data-year=""
                data-instructor=""
                data-major=""
                data-program=""
                data-semester=""
                data-academic-year=""
                data-color="#ffffff"
                title="Click to select this cell, then right-click to add a schedule"
            >

                <span class="empty-cell">Empty</span>
                <br>
                <span class="editable-label">
                    <i class="fa-solid fa-plus"></i>
                    Click to Add
                </span>

            </td>
        `;
    }

    /* Empty cell in another department's room: permission required. */
    if (allowOtherDepartments) {

        return `
            <td
                class="schedule-cell empty-slot allowed-other editable-cell"
                data-day="${day}"
                data-time="${escapeHtml(time)}"
                data-schedule-id=""
                data-course=""
                data-subject=""
                data-year=""
                data-instructor=""
                data-major=""
                data-program=""
                data-semester=""
                data-academic-year=""
                data-color="#ffffff"
                title="Click to select this cell, then right-click to add a schedule"
            >

                <span class="empty-cell">Empty</span>
                <br>
                <span class="editable-label">
                    <i class="fa-solid fa-unlock"></i>
                    Click to Add
                </span>
                <br>
                <span style="font-size:9px;color:#2e7d32;">
                    Allowed by room owner
                </span>

            </td>
        `;
    }

    return `
        <td class="schedule-cell empty-slot locked-cell">
            <span class="empty-cell">Empty</span>
            <br>
            <span class="not-allowed">
                <i class="fa-solid fa-lock"></i>
                Not allowed
            </span>
        </td>
    `;
}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(value)
{
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}


/* =========================================================
   CHAIR DEPARTMENT
========================================================= */

window.chairDepartmentId =
    {{ auth()->user()->department_id }};

            

                /*
                |--------------------------------------------------------------------------
                | MODIFY EXCEL EDITOR
                |--------------------------------------------------------------------------
                */

                let selectedModifyCell = null;
                let selectedMergeCell = null;

                 let modifyRoomMeta = {
                     semester: '',
                     academic_year: ''
                 };

                const modifyFormRoomId =
                    document.getElementById('modifyFormRoomId');

                const modifyFormDay =
                    document.getElementById('modifyFormDay');

                const modifyFormTime =
                    document.getElementById('modifyFormTime');

                const modifyFormScheduleId =
                    document.getElementById('modifyFormScheduleId');

                const modifyFacultySubjectId =
                    document.getElementById('modifyFacultySubjectId');

                const modifySubjectChoice =
                    document.getElementById('modifySubjectChoice');

                const modifySharedAudiencePicker = document.getElementById('modifySharedAudiencePicker');
                const modifySharedAudienceChoices = document.getElementById('modifySharedAudienceChoices');

                const modifyClearScheduleId =
                    document.getElementById('modifyClearScheduleId');

                const modifyCourseCode =
                    document.getElementById('modifyCourseCode');

                const modifySubject =
                    document.getElementById('modifySubject');

                const modifyYearLevel =
                    document.getElementById('modifyYearLevel');

                const modifyInstructor =
                    document.getElementById('modifyInstructor');

                const modifyMajor =
                    document.getElementById('modifyMajor');

                const modifyProgram =
                    document.getElementById('modifyProgram');

                const modifySemesterValue =
                     document.getElementById('modifySemesterValue');

                 const modifyAcademicYearValue =
                     document.getElementById('modifyAcademicYearValue');

                 const modifyColor =
                     document.getElementById('modifyColor');

                const modifyColorControl =
                    document.getElementById('modifyColorControl');

                const modifyEditor =
                    document.getElementById('modifyEditor');

                const modifyEntryOverlay =
                    document.getElementById('modifyEntryOverlay');

                const modifySheetToolbar =
                    document.querySelector('.modify-sheet-toolbar');

                function setModifyEntryOpen(isOpen)
                {
                    if (modifyEntryOverlay) modifyEntryOverlay.hidden = !isOpen;
                    modifySheetToolbar?.classList.toggle('is-entry-open', isOpen);
                }

                const modifyEntryTarget =
                    document.getElementById('modifyEntryTarget');

                const modifyEditorStatus =
                    document.getElementById('modifyEditorStatus');

                const modifyCellForm =
                    document.getElementById('modifyCellForm');

                const modifyClearForm =
                    document.getElementById('modifyClearForm');

                const modifyMergeClassButton =
                    document.getElementById('modifyMergeClassButton');

                const modifyMergeTargetLabel =
                    document.getElementById('modifyMergeTargetLabel');

                const modifyClearCellButton =
                    document.getElementById('modifyClearCellButton');

                modifyMergeClassButton?.addEventListener('click', function() {
                    this._mergeTargetCell = selectedMergeCell;
                });


                function clearMergeClassTarget()
                {
                    if (selectedMergeCell) selectedMergeCell.classList.remove('is-selected-merge');
                    selectedMergeCell = null;
                    if (modifyMergeClassButton) {
                        modifyMergeClassButton.disabled = true;
                        modifyMergeClassButton.textContent = 'Merge Class';
                        delete modifyMergeClassButton.dataset.mergePending;
                        ['scheduleId','courseCode','subject','instructor','department','program','building','room','day','time','semester','academicYear'].forEach(function(key) {
                            delete modifyMergeClassButton.dataset['merge' + key.charAt(0).toUpperCase() + key.slice(1)];
                        });
                    }
                    if (modifyMergeTargetLabel) {
                        modifyMergeTargetLabel.textContent = 'Select another college’s scheduled class to request a merge.';
                    }
                }


                function setMergeClassTarget(cell)
                {
                    clearMergeClassTarget();
                    if (!cell || !cell.dataset.mergeScheduleId || !modifyMergeClassButton) return;

                    selectedMergeCell = cell;
                    selectedMergeCell.classList.add('is-selected-merge');
                    const fieldMap = {
                        scheduleId: 'mergeScheduleId',
                        courseCode: 'mergeCourseCode',
                        subject: 'mergeSubject',
                        instructor: 'mergeInstructor',
                        department: 'mergeDepartment',
                        program: 'mergeProgram',
                        building: 'mergeBuilding',
                        room: 'mergeRoom',
                        day: 'mergeDay',
                        time: 'mergeTime',
                        semester: 'mergeSemester',
                        academicYear: 'mergeAcademicYear'
                    };
                    Object.entries(fieldMap).forEach(function(entry) {
                        modifyMergeClassButton.dataset[entry[1]] = cell.dataset[entry[1]] || '';
                    });

                    const isPending = cell.dataset.mergePending === '1';
                    modifyMergeClassButton.disabled = isPending;
                    modifyMergeClassButton.textContent = isPending ? 'Request Pending' : 'Merge Class';
                    if (modifyMergeTargetLabel) {
                        modifyMergeTargetLabel.textContent = [
                            cell.dataset.mergeCourseCode,
                            cell.dataset.mergeDay,
                            cell.dataset.mergeTime
                        ].filter(Boolean).join(' · ') + (isPending ? ' · request pending' : ' · selected');
                    }
                }


                function setModifyStatus(message, type = '')
                {
                    modifyEditorStatus.textContent =
                        message;

                    modifyEditorStatus.className =
                        'editor-status' +
                        (type ? ' ' + type : '');
                }


                function findModifyCourse(code)
                {
                    const searchCode =
                        String(code || '')
                            .trim()
                            .toUpperCase();

                    if (!searchCode) {
                        return [];
                    }

                    return modifyFacultySubjects.filter(
                        function(item)
                        {
                            return (
                                String(item.course_code || '')
                                    .trim()
                                    .toUpperCase()
                                ===
                                searchCode
                            );
                        }
                    );
                }

                function renderModifySharedAudiences(primary)
                {
                    if (!modifySharedAudiencePicker || !modifySharedAudienceChoices) return;
                    modifySharedAudienceChoices.replaceChildren();
                    if (!primary) {
                        modifySharedAudiencePicker.hidden = true;
                        return;
                    }

                    const cohorts = Array.isArray(primary.shared_cohorts) ? primary.shared_cohorts : [];
                    modifySharedAudiencePicker.hidden = cohorts.length === 0;
                    cohorts.forEach(item => {
                        const details = document.createElement('div');
                        details.className = 'shared-audience-option';
                        details.textContent = [item.program_code || item.program, item.major ? 'Major: ' + item.major : '', item.year_level, item.semester].filter(Boolean).join(' · ');
                        modifySharedAudienceChoices.appendChild(details);
                    });
                }


                function clearModifyEditor()
                {
                    if (selectedModifyCell) {
                        selectedModifyCell.classList.remove(
                            'is-selected-edit'
                        );
                    }

                    selectedModifyCell = null;
                    setModifyEntryOpen(false);
                    clearMergeClassTarget();
                    if (modifyClearCellButton) modifyClearCellButton.disabled = true;
                    if (modifyClearCellButton) modifyClearCellButton.hidden = true;
                    if (modifyColorControl) modifyColorControl.hidden = true;
                    if (modifyColor) modifyColor.disabled = true;

                    modifyFormRoomId.value = '';
                    modifyFormDay.value = '';
                    modifyFormTime.value = '';
                    modifyFormScheduleId.value = '';
                    modifyFacultySubjectId.value = '';
                    modifySubjectChoice.hidden = true;

                    modifyCourseCode.value = '';
                    modifySubject.value = '';
                    modifyYearLevel.value = '';
                    modifyInstructor.value = '';
                    modifyMajor.value = '';
                    modifyProgram.value = '';
                    modifySemesterValue.value = '';
                    modifyAcademicYearValue.value = '';
                    modifyColor.value = '#ffffff';
                    modifySharedAudiencePicker.hidden = true;
                    modifySharedAudienceChoices.replaceChildren();

                    setModifyStatus('Click a cell to select it, then right-click to enter a course.');
                }


                function populateModifyCourse()
                {
                    const matches =
                        findModifyCourse(
                            modifyCourseCode.value
                        );

                    modifySubject.value = '';
                    modifyYearLevel.value = '';
                    modifyInstructor.value = '';
                    modifyMajor.value = '';
                    modifyProgram.value = '';
                    modifyFacultySubjectId.value = '';

                    if (!modifyCourseCode.value.trim()) {
                        modifySubjectChoice.hidden = true;
                        renderModifySharedAudiences(null);
                        setModifyStatus(
                            'Enter a course code from Set Instructor.',
                            'warning'
                        );
                        return;
                    }

                    if (matches.length === 0) {
                        modifySubjectChoice.hidden = true;
                        renderModifySharedAudiences(null);
                        setModifyStatus(
                            'Course code was not found in the Instructor Setup entries.',
                            'error'
                        );
                        return;
                    }

                    if (matches.length > 1 && !modifySubjectChoice.value) {
                        const yearLevels = [...new Set(matches.map(item => item.year_level))];
                        modifySubjectChoice.replaceChildren(new Option('Choose the correct subject', ''));
                        matches.forEach(item => {
                            const label = [item.year_level, item.program, item.instructor, item.major ? `Major: ${item.major}` : ''].filter(Boolean);
                            modifySubjectChoice.add(new Option(label.join(' · '), String(item.id)));
                        });
                        modifySubjectChoice.hidden = false;
                        setModifyStatus(
                            yearLevels.length > 1
                                ? 'This course code appears in multiple year levels. Choose the correct year level and instructor.'
                                : 'Choose the correct program, major, and instructor assignment.',
                            'warning'
                        );
                        return;
                    }

                    const match = matches.length === 1
                        ? matches[0]
                        : matches.find(item => String(item.id) === String(modifySubjectChoice.value));
                    if (!match) return;

                    modifySubjectChoice.hidden = true;
                    modifyFacultySubjectId.value = match.id;

                    modifySubject.value =
                        match.subject || '';

                    modifyYearLevel.value =
                        match.year_level || '';

                    modifyInstructor.value =
                        match.instructor || '';

                    modifyMajor.value =
                        match.major || '';

                    modifyProgram.value =
                        match.program || '';

                    renderModifySharedAudiences(match);

                    setModifyStatus(
                        'Course loaded: ' +
                        match.subject +
                        ' — ' +
                        match.instructor +
                        ' — ' +
                        match.year_level,
                        'success'
                    );
                }


                modifyCourseCode.addEventListener('input', function () {
                    modifySubjectChoice.value = '';
                    modifySubjectChoice.hidden = true;
                    populateModifyCourse();
                });

                modifySubjectChoice.addEventListener('change', populateModifyCourse);


                function openAddModal(
    roomId,
    day,
    time,
    sourceCell = null
)
{
    if (!selectedRoom) {
        return;
    }

    const selector =
        '#scheduleBody td.schedule-cell.editable-cell[data-day="' +
        String(day).replace(/"/g, '\\"') +
        '"][data-time="' +
        String(time).replace(/"/g, '\\"') +
        '"]';

    const clickedCell = sourceCell || document.querySelector(selector);

    if (!clickedCell) {
        return;
    }

    if (selectedModifyCell) {
        selectedModifyCell.classList.remove('is-selected-edit');
    }

    selectedModifyCell = clickedCell;
    selectedModifyCell.classList.add('is-selected-edit');

    modifyFormRoomId.value = roomId;
    modifyFormDay.value = day;
    const effectiveTime = clickedCell.dataset.scheduleTime || time;
    modifyFormTime.value = effectiveTime;

    if (modifyEntryTarget) {
        modifyEntryTarget.textContent = [day, effectiveTime].filter(Boolean).join(' · ');
    }

    modifySemesterValue.value =
        modifyRoomMeta.semester || '';

    modifyAcademicYearValue.value =
        modifyRoomMeta.academic_year || '';

    const scheduleId =
        selectedModifyCell.dataset.scheduleId || '';

    modifyFormScheduleId.value = scheduleId;
    refreshModifyAdjustmentInput(scheduleId);

    if (scheduleId) {

        modifyCourseCode.value =
            selectedModifyCell.dataset.course || '';

        modifyFacultySubjectId.value =
            selectedModifyCell.dataset.facultySubjectId || '';

        modifySubject.value =
            selectedModifyCell.dataset.subject || '';

        modifyYearLevel.value =
            selectedModifyCell.dataset.year || '';

        modifyInstructor.value =
            selectedModifyCell.dataset.instructor || '';

        modifyMajor.value =
            selectedModifyCell.dataset.major || '';

        modifyProgram.value =
            selectedModifyCell.dataset.program || '';

        const existingFacultySubject = modifyFacultySubjects.find(item => (item.assignment_ids || [item.id]).map(String).includes(String(modifyFacultySubjectId.value)));
        renderModifySharedAudiences(existingFacultySubject);

        modifyColor.value =
            selectedModifyCell.dataset.color || '#ffffff';

        setModifyStatus(
            'Your college schedule is selected: ' +
            day +
            ' ' +
            effectiveTime +
            '. Update the course code, then choose Confirm Entry.',
            'success'
        );

    } else {

        modifyCourseCode.value = '';
        modifyFacultySubjectId.value = '';
        modifySubjectChoice.value = '';
        modifySubjectChoice.hidden = true;
        modifySubject.value = '';
        modifyYearLevel.value = '';
        modifyInstructor.value = '';
        modifyMajor.value = '';
        modifyProgram.value = '';
        renderModifySharedAudiences(null);
        modifyColor.value = '#ffffff';

        setModifyStatus(
            'Empty cell selected: ' +
            day +
            ' ' +
            effectiveTime +
            '. Enter a course code, then choose Confirm Entry.',
            'warning'
        );

    }

    setModifyEntryOpen(true);
    window.requestAnimationFrame(function() {
        modifyCourseCode.focus();
        modifyCourseCode.select();
    });
                }

                const modifyScheduleBody = document.getElementById('scheduleBody');
                modifyScheduleBody?.addEventListener('click', function(event) {
                    const cell = event.target.closest('.schedule-cell');
                    if (!cell) return;

                    if (event.target.closest('[data-merge-request-button]')) return;

                    if (cell.dataset.mergeScheduleId && cell.classList.contains('locked-cell')) {
                        if (selectedModifyCell) selectedModifyCell.classList.remove('is-selected-edit');
                        selectedModifyCell = null;
                        setModifyEntryOpen(false);
                        modifyClearCellButton.disabled = true;
                        modifyClearCellButton.hidden = true;
                        modifyColorControl.hidden = true;
                        modifyColor.disabled = true;
                        setMergeClassTarget(cell);
                        setModifyStatus('Shared class selected. Use Merge Class at the top left to choose your course.');
                        return;
                    }

                    if (!cell.classList.contains('editable-cell') || cell.classList.contains('locked-cell') || cell.classList.contains('reserved-cell')) {
                        return;
                    }

                    if (selectedModifyCell === cell) {
                        clearModifyEditor();
                        return;
                    }

                    clearMergeClassTarget();
                    if (selectedModifyCell) selectedModifyCell.classList.remove('is-selected-edit');
                    if (!cell.dataset.scheduleId) prepareModifyEmptyCellTime(cell);
                    selectedModifyCell = cell;
                    selectedModifyCell.classList.add('is-selected-edit');
                    setModifyEntryOpen(false);
                    modifyClearCellButton.disabled = false;
                    modifyClearCellButton.hidden = !Boolean(cell.dataset.scheduleId);
                    modifyColorControl.hidden = false;
                    modifyColor.disabled = false;
                    modifyFormRoomId.value = selectedRoom ? selectedRoom.id : '';
                    modifyFormDay.value = cell.dataset.day || '';
                    modifyFormTime.value = cell.dataset.scheduleTime || cell.dataset.time || '';
                    modifyFormScheduleId.value = cell.dataset.scheduleId || '';
                    refreshModifyAdjustmentInput(cell.dataset.scheduleId || '');
                    modifySemesterValue.value = modifyRoomMeta.semester || '';
                    modifyAcademicYearValue.value = modifyRoomMeta.academic_year || '';
                    modifyColor.value = cell.dataset.color || '#ffffff';
                    setModifyStatus('Selected ' + (cell.dataset.day || '') + ' ' + (cell.dataset.scheduleTime || cell.dataset.time || '') + '. Right-click to open course entry.');
                    renderModifyScheduleBlocks();
                });

                modifyScheduleBody?.addEventListener('contextmenu', function(event) {
                    const cell = event.target.closest('.schedule-cell.editable-cell');
                    if (!cell || cell.classList.contains('locked-cell') || cell.classList.contains('reserved-cell')) return;

                    event.preventDefault();
                    if (selectedModifyCell !== cell) {
                        setModifyStatus('Click the cell once to select it, then right-click again to open course entry.', 'warning');
                        return;
                    }

                    openAddModal(selectedRoom ? selectedRoom.id : '', cell.dataset.day || '', cell.dataset.time || '', cell);
                });

                document.getElementById('modifyEntryClose')?.addEventListener('click', function() {
                    setModifyEntryOpen(false);
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && modifyEntryOverlay && !modifyEntryOverlay.hidden) {
                        setModifyEntryOpen(false);
                    }
                });


                modifyCellForm.addEventListener(
                    'submit',
                    function(event)
                    {
                        if (!selectedModifyCell) {

                            event.preventDefault();

                            setModifyStatus(
                                'Please select an editable cell first.',
                                'error'
                            );

                            return;
                        }

                        const course =
                            modifyCourseCode.value
                                .trim()
                                .toUpperCase();

                        const matches =
                            findModifyCourse(course);

                        if (!course) {

                            event.preventDefault();

                            setModifyStatus(
                                'Please enter a course code.',
                                'error'
                            );

                            return;
                        }

                        if (matches.length === 0) {

                            event.preventDefault();

                            setModifyStatus(
                                'This course code is not in the Set Instructor entries.',
                                'error'
                            );

                            return;
                        }

                        const match = matches.find(
                            item => String(item.id) === String(modifyFacultySubjectId.value)
                        );

                        if (!match) {
                            event.preventDefault();
                            setModifyStatus(
                                'Choose the matching year level and instructor assignment first.',
                                'warning'
                            );
                            modifySubjectChoice.focus();
                            return;
                        }

                        const proposedRange = modifyParseRange(modifyFormTime.value);
                        if (proposedRange && modifyResizeHasConflict(selectedModifyCell, proposedRange.start, proposedRange.end)) {
                            event.preventDefault();
                            setModifyStatus('This time overlaps another scheduled class. Choose the available half-hour or adjust the cell.', 'error');
                            return;
                        }

                        modifySubject.value =
                            match.subject || '';

                        modifyYearLevel.value =
                            match.year_level || '';

                        modifyInstructor.value =
                            match.instructor || '';

                        modifyMajor.value =
                            match.major || '';

                        modifyProgram.value =
                            match.program || '';

                        modifyFacultySubjectId.value =
                            match.id;

                        modifyFormScheduleId.value =
                            selectedModifyCell.dataset.scheduleId || '';

                        setModifyStatus(
                            'Confirmed. Saving ' +
                            match.course_code +
                            ' to ' +
                            modifyFormDay.value +
                            ' ' +
                            modifyFormTime.value +
                            '...',
                            'success'
                        );
                    }
                );


                document
                    .getElementById('modifyClearCellButton')
                    .addEventListener(
                        'click',
                        async function()
                        {
                            if (!selectedModifyCell) {

                                setModifyStatus(
                                    'Please select a cell first.',
                                    'error'
                                );

                                return;
                            }

                            const scheduleId =
                                selectedModifyCell.dataset.scheduleId || '';

                            if (scheduleId) {

                                const confirmed = await window.showSystemConfirmation(
                                    'Are you sure you want to clear this saved schedule from the cell?',
                                    'Clear schedule'
                                );
                                if (!confirmed) {
                                    return;
                                }

                                modifyClearScheduleId.value =
                                    scheduleId;
                                document.getElementById('modifyClearRoomId').value = selectedRoom ? selectedRoom.id : '';

                                modifyClearForm.submit();

                                return;
                            }

                            clearModifyEditor();
                        }
                    );


                document
                    .getElementById('modifyCancelButton')
                    .addEventListener(
                        'click',
                        clearModifyEditor
                    );


                modifyColor.addEventListener(
                    'input',
                    function()
                    {
                        if (!selectedModifyCell) {
                            return;
                        }

                        selectedModifyCell.style.backgroundColor =
                            this.value;
                        selectedModifyCell.dataset.color = this.value;
                    }
                );


                clearModifyEditor();

            
if (initialModifyRoomId) {
    const roomIdToOpen = Number(initialModifyRoomId);
    let foundRoom = false;

    for (const department of departments) {
        const building = (department.buildings || []).find(function(item) {
            return (item.rooms || []).some(function(room) {
                return Number(room.id) === roomIdToOpen;
            });
        });

        if (!building) continue;
        selectDepartment(department.id);
        selectBuilding(building.id);
        renderRoomSchedule(roomIdToOpen);
        foundRoom = true;
        break;
    }

    if (!foundRoom) window.location.replace(modifyRoomPickerUrl);
}</script>

        </main>

    </div>

   

</div>


