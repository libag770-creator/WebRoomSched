@include('footerheader.header')

<style>
    .portal-shell.admin-portal { --dept-sidebar-width:252px; --dept-shell-gutter:28px; background:#e9eee8; }
    .portal-shell.admin-portal .wrapper { min-height:100vh; background:#e9eee8; }
    .portal-shell.admin-portal .main-layout { grid-template-columns:var(--dept-sidebar-width) minmax(0,1fr); align-items:stretch; min-height:100vh; }
    .portal-shell.admin-portal .main-layout > .sidebar.admin-sidebar { position:sticky; top:0; align-self:start; width:var(--dept-sidebar-width); height:100vh; min-height:100vh; padding:25px 15px 18px; border:0; background:linear-gradient(165deg,#112f29 0%,#173b33 54%,#285342 100%); color:#f7f5eb; box-shadow:12px 0 32px rgba(20,47,37,.1); scrollbar-color:#496a59 transparent; }
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
    .portal-shell.admin-portal .top-header { position:fixed; top:16px; right:var(--dept-shell-gutter); left:calc(var(--dept-sidebar-width) + var(--dept-shell-gutter)); z-index:1000; min-height:72px; padding:10px 15px 10px 22px; border:1px solid rgba(224,231,224,.96); border-radius:18px; background:rgba(255,255,255,.95); box-shadow:0 12px 34px rgba(31,57,43,.09); backdrop-filter:blur(18px); }
    .portal-shell.admin-portal .header-title small { color:#9a8450; font-size:10px; }
    .portal-shell.admin-portal .header-title h3 { color:#173b33; font-size:20px; }
    .portal-shell.admin-portal .header-action { color:#375448; }
    .portal-shell.admin-portal .sidebar-toggle { top:36px; left:calc(var(--dept-sidebar-width) - 15px); z-index:1200; border-color:rgba(216,193,140,.52); background:#f8f5eb; color:#224336; }
    .portal-shell.admin-portal.sidebar-collapsed { --dept-sidebar-width:76px; }
    .portal-shell.admin-portal.sidebar-collapsed .main-layout { grid-template-columns:76px minmax(0,1fr); }
    .portal-shell.admin-portal.sidebar-collapsed .main-layout > .sidebar.admin-sidebar { width:76px; padding-right:10px; padding-left:10px; }
    .portal-shell.admin-portal.sidebar-collapsed .sidebar-toggle { left:61px; }
    .portal-shell.admin-portal .main-layout > .content.admin-departments-view { min-width:0; min-height:100vh; padding:124px clamp(20px,3vw,42px) 44px; background:radial-gradient(ellipse at 92% 2%,rgba(214,194,143,.22),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }

    .admin-departments-view,.admin-departments-view * { box-sizing:border-box; }
    .admin-departments-view { color:#25392e; font-family:'DM Sans',Arial,sans-serif; }
    .department-page { width:min(100%,1480px); margin:0 auto; }
    .department-hero { position:relative; display:flex; align-items:center; justify-content:space-between; gap:28px; min-height:205px; overflow:hidden; margin-bottom:24px; padding:clamp(26px,4vw,40px); border:1px solid rgba(231,214,173,.25); border-radius:24px; background:radial-gradient(circle at 83% 20%,rgba(215,194,143,.18),transparent 24%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.15); color:#fff; }
    .department-hero::after { position:absolute; right:-45px; bottom:-175px; width:340px; height:340px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .department-hero-copy { position:relative; z-index:1; max-width:760px; }
    .department-hero-eyebrow { display:flex; align-items:center; gap:9px; margin:0 0 13px; color:#e2c987; font-size:11px; font-weight:800; letter-spacing:.15em; }
    .department-hero h1 { margin:0; color:#fffdf7; font-family:'Manrope','DM Sans',sans-serif; font-size:clamp(30px,3.4vw,44px); font-weight:800; letter-spacing:-.04em; line-height:1.12; }
    .department-hero-description { max-width:620px; margin:13px 0 0; color:#d8e2d8; font-size:15px; line-height:1.65; }
    .department-hero-mark { position:relative; z-index:1; display:grid; flex:0 0 150px; justify-items:center; gap:12px; width:150px; height:150px; align-content:center; border:1px solid rgba(255,255,255,.21); border-radius:50%; background:rgba(255,255,255,.075); color:#e6d39e; }
    .department-hero-mark i { font-size:38px; }
    .department-hero-mark span { color:#e8e6dc; font-size:9px; font-weight:800; letter-spacing:.12em; text-align:center; }
    .department-content-grid { display:grid; grid-template-columns:minmax(300px,.76fr) minmax(0,1.55fr); align-items:start; gap:18px; }
    .department-panel { overflow:hidden; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .department-panel-heading { display:flex; align-items:flex-start; gap:12px; padding:20px 21px; border-bottom:1px solid #e9eee7; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .department-panel-icon { display:grid; flex:0 0 38px; width:38px; height:38px; place-items:center; border:1px solid #e2e9df; border-radius:12px; background:#edf3eb; color:#285940; font-size:15px; }
    .department-panel-heading h2 { margin:1px 0 4px; color:#173b33; font-family:'Manrope','DM Sans',sans-serif; font-size:18px; font-weight:800; letter-spacing:-.025em; }
    .department-panel-heading p { margin:0; color:#5c6d62; font-size:12px; line-height:1.55; }
    .department-add-form { display:grid; gap:16px; padding:21px; }
    .department-form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
    .department-form-group { min-width:0; }
    .department-form-group label,.modal-form-group label { display:block; margin:0 0 7px; color:#35483c; font-size:12px; font-weight:750; }
    .department-form-group input,.modal-form-group input { display:block; width:100%; min-height:45px; padding:10px 12px; border:1px solid #d7e0d6; border-radius:10px; outline:none; background:#fff; color:#263c30; font:500 13px 'DM Sans',Arial,sans-serif; transition:border-color .16s ease,box-shadow .16s ease; }
    .department-form-group input:focus,.modal-form-group input:focus { border-color:#658e70; box-shadow:0 0 0 3px rgba(73,123,83,.13); }
    .department-form-group input::placeholder { color:#9aa69c; }
    .department-form-group small,.modal-form-group small { display:block; margin-top:7px; color:#748178; font-size:11px; line-height:1.5; }
    .department-form-group input[type=file],.modal-form-group input[type=file] { min-height:46px; padding:7px; background:#f9fbf8; font-size:11px; }
    .department-form-group input[type=file]::file-selector-button,.modal-form-group input[type=file]::file-selector-button { margin-right:10px; padding:7px 10px; border:0; border-radius:7px; background:#eaf1e8; color:#31543b; font:700 11px 'DM Sans',Arial,sans-serif; cursor:pointer; }
    .department-field-error { display:block; margin-top:6px; color:#ae3b36; font-size:11px; }
    .department-form-submit { display:flex; justify-content:flex-end; padding-top:2px; }
    .department-primary-button,.department-action-button,.modal-buttons button { display:inline-flex; min-height:43px; align-items:center; justify-content:center; gap:8px; padding:0 15px; border:1px solid transparent; border-radius:10px; cursor:pointer; font:750 12px 'DM Sans',Arial,sans-serif; text-decoration:none; transition:transform .16s ease,background .16s ease,box-shadow .16s ease; }
    .department-primary-button { background:#24583d; color:#fff; box-shadow:0 7px 15px rgba(36,88,61,.16); }
    .department-primary-button:hover { transform:translateY(-1px); background:#19482f; box-shadow:0 10px 20px rgba(36,88,61,.2); }
    .department-list-panel { min-width:0; }
    .department-list-heading { align-items:center; justify-content:space-between; }
    .department-list-heading-copy { display:flex; align-items:flex-start; gap:12px; }
    .department-count { display:inline-flex; min-height:30px; align-items:center; gap:6px; padding:0 10px; border:1px solid #dbe6d9; border-radius:999px; background:#edf4eb; color:#2d5b3b; font-size:11px; font-weight:800; white-space:nowrap; }
    .department-table-scroll { width:100%; overflow-x:auto; }
    .department-table { width:100%; border-collapse:separate; border-spacing:0; text-align:left; }
    .department-table th { padding:12px 13px; border-bottom:1px solid #e4ebe2; background:#fafbf8; color:#768379; font-size:10px; font-weight:800; letter-spacing:.09em; text-transform:uppercase; white-space:nowrap; }
    .department-table td { padding:14px 13px; border-bottom:1px solid #edf1eb; color:#405247; font-size:12px; vertical-align:middle; }
    .department-table tbody tr:last-child td { border-bottom:0; }
    .department-table tbody tr:hover { background:#fbfcfa; }
    .department-id { color:#88958b; font-size:11px; font-weight:700; }
    .department-code { display:inline-flex; min-height:27px; align-items:center; padding:0 9px; border:1px solid #e5e9dc; border-radius:7px; background:#f7f6ed; color:#79683d; font-size:11px; font-weight:800; letter-spacing:.04em; }
    .department-name { display:block; min-width:160px; color:#2f4538; font-size:12px; font-weight:750; line-height:1.5; }
    .department-logo-preview,.department-logo-placeholder { display:block; width:42px; height:42px; object-fit:cover; border:1px solid #e4e9e2; border-radius:11px; background:#f7f9f5; }
    .department-logo-placeholder { display:grid; place-items:center; color:#829287; font-size:15px; }
    .department-building-count { display:inline-flex; align-items:center; gap:6px; color:#52685a; font-size:11px; font-weight:700; white-space:nowrap; }
    .department-row-actions { display:flex; align-items:center; gap:7px; }
    .department-action-button { min-height:34px; padding:0 10px; border-radius:8px; font-size:11px; }
    .department-action-button.edit { border-color:#dfe7dc; background:#f1f5ef; color:#31583c; }
    .department-action-button.edit:hover { background:#e5efe2; }
    .department-action-button.delete { border-color:#f0d9d5; background:#fff7f5; color:#a7443d; }
    .department-action-button.delete:hover { background:#ffebe7; }
    .department-empty-state { display:grid; justify-items:center; padding:52px 20px; text-align:center; }
    .department-empty-icon { display:grid; width:54px; height:54px; place-items:center; margin-bottom:13px; border:1px solid #e1e9de; border-radius:17px; background:#eef4eb; color:#37664d; font-size:21px; }
    .department-empty-state h3 { margin:0; color:#294437; font-family:'Manrope','DM Sans',sans-serif; font-size:17px; font-weight:800; }
    .department-empty-state p { margin:6px 0 0; color:#68786d; font-size:12px; }

    .department-edit-modal,.action-message-overlay { position:fixed; inset:0; z-index:2000; display:none; align-items:center; justify-content:center; padding:20px; background:rgba(15,34,26,.54); backdrop-filter:blur(5px); }
    .department-edit-modal { overflow-y:auto; }
    .department-edit-dialog { position:relative; width:min(520px,100%); max-height:calc(100vh - 40px); overflow:auto; padding:26px; border:1px solid rgba(255,255,255,.7); border-radius:20px; background:#fff; box-shadow:0 24px 70px rgba(9,35,22,.26); }
    .department-edit-dialog h2 { margin:0; color:#183c2e; font-family:'Manrope','DM Sans',sans-serif; font-size:22px; font-weight:800; letter-spacing:-.035em; }
    .department-modal-subtitle { margin:6px 0 20px; color:#65746a; font-size:12px; line-height:1.55; }
    .modal-form-group { margin-top:15px; }
    .department-current-logo { display:flex; align-items:center; gap:10px; min-height:44px; margin:8px 0 12px; color:#77857b; font-size:11px; }
    .department-current-logo .department-logo-preview { width:48px; height:48px; }
    .modal-buttons { display:flex; justify-content:flex-end; gap:9px; margin-top:22px; padding-top:17px; border-top:1px solid #edf1eb; }
    .modal-buttons .btn.gray { border-color:#e0e6df; background:#f5f7f4; color:#526258; }
    .modal-buttons .btn.green { background:#24583d; color:#fff; }
    .modal-buttons button:hover { transform:translateY(-1px); }
    .department-modal-close,.action-message-close { position:absolute; top:12px; right:13px; display:grid; width:34px; height:34px; place-items:center; border:0; border-radius:50%; background:#f1f4ef; color:#53665a; font-size:22px; cursor:pointer; }
    .department-modal-close:hover,.action-message-close:hover { background:#e7eee5; color:#243d2f; }
    .action-message-overlay { z-index:3000; }
    .action-message-modal { position:relative; width:min(410px,100%); padding:30px 26px 25px; border:1px solid rgba(255,255,255,.75); border-radius:21px; background:#fff; box-shadow:0 24px 70px rgba(9,35,22,.27); text-align:center; animation:departmentMessageIn .2s ease-out; }
    .action-message-modal.success { border-top:4px solid #328255; }
    .action-message-modal.error { border-top:4px solid #bd534a; }
    .action-message-icon { width:58px; height:58px; margin:0 auto 14px; }
    .action-message-icon svg { display:block; width:100%; height:100%; fill:none; stroke:currentColor; stroke-width:2.5; stroke-linecap:round; stroke-linejoin:round; }
    .action-message-icon.success { color:#328255; }
    .action-message-icon.error { color:#bd534a; }
    .action-message-modal h2 { margin:0 0 7px; color:#203d2e; font-family:'Manrope','DM Sans',sans-serif; font-size:20px; font-weight:800; }
    .action-message-modal p { margin:0; color:#617167; font-size:13px; line-height:1.6; }
    .action-message-ok { min-width:92px; min-height:39px; margin-top:19px; border:0; border-radius:9px; color:#fff; font:750 12px 'DM Sans',Arial,sans-serif; cursor:pointer; }
    .action-message-ok.success { background:#2e7048; }
    .action-message-ok.error { background:#a9473f; }
    @keyframes departmentMessageIn { from { opacity:0; transform:translateY(8px) scale(.97); } to { opacity:1; transform:translateY(0) scale(1); } }

    @media(max-width:1120px) {
        .portal-shell.admin-portal { --dept-sidebar-width:226px; --dept-shell-gutter:22px; }
        .portal-shell.admin-portal.sidebar-collapsed { --dept-sidebar-width:76px; }
        .portal-shell.admin-portal .main-layout { grid-template-columns:var(--dept-sidebar-width) minmax(0,1fr); }
        .portal-shell.admin-portal .main-layout > .content.admin-departments-view { padding-right:24px; padding-left:24px; }
        .department-content-grid { grid-template-columns:minmax(0,1fr); }
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
        .portal-shell.admin-portal .main-layout > .content.admin-departments-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .department-hero { align-items:flex-start; min-height:0; padding:27px 23px; }
        .department-hero-mark { flex-basis:112px; width:112px; height:112px; }
        .department-hero-mark i { font-size:29px; }
        .department-hero-mark span { font-size:8px; }
    }
    @media(max-width:560px) {
        .department-hero { flex-direction:column; gap:20px; }
        .department-hero-mark { display:none; }
        .department-hero-description { font-size:13px; }
        .department-form-grid { grid-template-columns:minmax(0,1fr); }
        .department-panel-heading { padding:17px; }
        .department-add-form { padding:17px; }
        .department-list-heading { align-items:flex-start; flex-direction:column; }
        .department-table { min-width:720px; }
        .department-edit-dialog { padding:23px 19px; }
        .department-row-actions { flex-wrap:wrap; }
    }
    @media(prefers-reduced-motion:reduce) {
        .admin-departments-view *, .action-message-modal { scroll-behavior:auto !important; animation-duration:.01ms !important; transition-duration:.01ms !important; }
    }

    /* Readability scale shared with the other redesigned admin pages. */
    .admin-departments-view { font-size:15px; line-height:1.6; }
    .department-hero-eyebrow { font-size:12px; font-weight:850; }
    .department-hero h1 { font-size:clamp(32px,3.5vw,46px); font-weight:850; }
    .department-hero-description { font-size:16px; font-weight:600; }
    .department-panel-heading h2 { font-size:20px; font-weight:850; }
    .department-panel-heading p { font-size:14px; font-weight:600; }
    .department-panel-icon { flex-basis:42px; width:42px; height:42px; font-size:16px; }
    .department-count { min-height:32px; font-size:12px; font-weight:850; }
    .department-form-group label,.modal-form-group label { font-size:14px; font-weight:850; }
    .department-form-group input,.modal-form-group input { min-height:48px; font-size:14px; font-weight:650; }
    .department-form-group small,.modal-form-group small { font-size:12px; font-weight:600; }
    .department-primary-button { min-height:46px; font-size:14px; font-weight:850; }
    .department-table th { font-size:12px; font-weight:850; }
    .department-table td { font-size:14px; font-weight:650; }
    .department-id { font-size:12px; font-weight:750; }
    .department-code { font-size:12px; font-weight:850; }
    .department-name { font-size:14px; font-weight:850; }
    .department-building-count { font-size:13px; font-weight:750; }
    .department-action-button { min-height:37px; font-size:12px; font-weight:850; }
    .department-empty-state h3 { font-size:19px; font-weight:850; }
    .department-empty-state p { font-size:14px; font-weight:600; }
    .department-edit-dialog h2 { font-size:24px; font-weight:850; }
    .department-modal-subtitle { font-size:14px; font-weight:600; }
    .modal-form-group input { min-height:48px; }
    .modal-buttons button { min-height:43px; font-size:13px; font-weight:850; }
    .action-message-modal h2 { font-size:22px; font-weight:850; }
    .action-message-modal p { font-size:14px; font-weight:600; }
    .action-message-ok { min-height:42px; font-size:13px; font-weight:850; }
    .action-message-close { font-weight:700; }
    @media(max-width:560px) {
        .department-hero-description { font-size:14px; }
        .department-panel-heading p { font-size:13px; }
        .department-table th { font-size:11px; }
    }
</style>

<div class="wrapper">
    <div class="main-layout">
        @include('admin.sidebar')

        <main class="content admin-departments-view">
            <div class="department-page">
                <header class="department-hero">
                    <div class="department-hero-copy">
                        <p class="department-hero-eyebrow"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> CAMPUS ADMINISTRATION</p>
                        <h1>Manage Colleges</h1>
                        <p class="department-hero-description">Keep your campus directory accurate. Add a College, update its details, and see how each one connects to your buildings.</p>
                    </div>
                    <div class="department-hero-mark" aria-hidden="true">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>COLLEGE DIRECTORY</span>
                    </div>
                </header>

                @php
                    $alertMessage = session('success')
                        ?? session('error')
                        ?? ($errors->any() ? $errors->first() : null);
                    $alertType = session()->has('success') ? 'success' : 'error';
                @endphp

                @if ($alertMessage)
                    <div id="departmentActionMessage" class="action-message-overlay" role="alertdialog" aria-modal="true" aria-labelledby="actionMessageTitle" aria-hidden="true">
                        <div class="action-message-modal {{ $alertType }}">
                            <button type="button" class="action-message-close" onclick="closeActionMessage()" aria-label="Close">&times;</button>
                            <div class="action-message-icon {{ $alertType }}" aria-hidden="true">
                                @if ($alertType === 'success')
                                    <svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="24"></circle><path d="M14 27l8 8 16-18"></path></svg>
                                @else
                                    <svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="24"></circle><path d="M18 18l16 16M34 18L18 34"></path></svg>
                                @endif
                            </div>
                            <h2 id="actionMessageTitle">{{ $alertType === 'success' ? 'Successful!' : 'Please check' }}</h2>
                            <p>{{ $alertMessage }}</p>
                            <button type="button" class="action-message-ok {{ $alertType }}" onclick="closeActionMessage()">OK</button>
                        </div>
                    </div>
                @endif

                <div class="department-content-grid">
                    <section class="department-panel" aria-labelledby="addDepartmentHeading">
                        <div class="department-panel-heading">
                            <span class="department-panel-icon" aria-hidden="true"><i class="fa-solid fa-plus"></i></span>
                            <div>
                                <h2 id="addDepartmentHeading">Add a college</h2>
                                <p>Create a campus college entry.</p>
                            </div>
                        </div>

                        <form class="department-add-form" action="{{ route('admin.departments.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="department-form-grid">
                                <div class="department-form-group">
                                    <label for="departmentCode">College code</label>
                                    <input type="text" id="departmentCode" name="code" placeholder="Example: CAT" value="{{ old('code') }}" maxlength="50" required>
                                    @error('code')<span class="department-field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="department-form-group">
                                    <label for="departmentName">College name</label>
                                    <input type="text" id="departmentName" name="name" placeholder="College of Applied Technology" value="{{ old('name') }}" maxlength="255" required>
                                    @error('name')<span class="department-field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="department-form-group">
                                <label for="departmentLogo">College logo <span style="color:#7d8b80;font-weight:500;">(optional)</span></label>
                                <input type="file" id="departmentLogo" name="logo" accept="image/png,image/jpeg,image/webp">
                                <small>PNG, JPG, or WebP. Maximum file size: 2 MB.</small>
                                @error('logo')<span class="department-field-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="department-form-submit">
                                <button type="submit" class="department-primary-button"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add college</button>
                            </div>
                        </form>
                    </section>

                    <section class="department-panel department-list-panel" aria-labelledby="departmentListHeading">
                        <div class="department-panel-heading department-list-heading">
                            <div class="department-list-heading-copy">
                                <span class="department-panel-icon" aria-hidden="true"><i class="fa-solid fa-list"></i></span>
                                <div>
                                    <h2 id="departmentListHeading">College directory</h2>
                                    <p>Review names, codes, logos, and building connections.</p>
                                </div>
                            </div>
                            <span class="department-count"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> {{ $departments->count() }} total</span>
                        </div>

                        <div class="department-table-scroll">
                            @if($departments->count() > 0)
                                <table class="department-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">College</th>
                                            <th scope="col">Logo</th>
                                            <th scope="col">Buildings</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($departments as $department)
                                            <tr>
                                                <td><span class="department-id">{{ $department->id }}</span></td>
                                                <td><span class="department-code">{{ $department->code }}</span></td>
                                                <td><span class="department-name">{{ $department->name }}</span></td>
                                                <td>
                                                    @if($department->logo)
                                                        <img class="department-logo-preview" src="{{ route('departments.logo', $department) }}?v={{ urlencode($department->logo) }}" alt="{{ $department->name }} logo">
                                                    @else
                                                        <span class="department-logo-placeholder" aria-label="No logo uploaded"><i class="fa-regular fa-image" aria-hidden="true"></i></span>
                                                    @endif
                                                </td>
                                                <td><span class="department-building-count"><i class="fa-solid fa-building" aria-hidden="true"></i> {{ $department->buildings_count }}</span></td>
                                                <td>
                                                    <div class="department-row-actions">
                                                        <button type="button" class="department-action-button edit"
                                                                onclick="openEditDepartment({{ $department->id }}, @js($department->code), @js($department->name), @js($department->logo))">
                                                            <i class="fa-solid fa-pen" aria-hidden="true"></i> Edit
                                                        </button>
                                                        <form action="{{ route('admin.departments.delete', $department->id) }}" method="POST" data-confirm-delete="Delete {{ $department->name }}? This action cannot be undone.">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="department-action-button delete"
                                                                    onclick="return confirmDelete(@js($department->name))">
                                                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="department-empty-state">
                                    <span class="department-empty-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                                    <h3>No Colleges yet</h3>
                                    <p>Add your first college using the form.</p>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <div id="editDepartmentModal" class="department-edit-modal" role="dialog" aria-modal="true" aria-labelledby="editDepartmentHeading" aria-hidden="true">
        <div class="department-edit-dialog">
            <button type="button" class="department-modal-close" onclick="closeEditDepartment()" aria-label="Close edit college dialog">&times;</button>
            <h2 id="editDepartmentHeading">Edit college</h2>
            <p class="department-modal-subtitle">Update the college details shown in the campus directory.</p>
            <form id="editDepartmentForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-form-group">
                    <label for="editDepartmentCode">College code</label>
                    <input type="text" name="code" id="editDepartmentCode" maxlength="50" required>
                </div>
                <div class="modal-form-group">
                    <label for="editDepartmentName">College name</label>
                    <input type="text" name="name" id="editDepartmentName" maxlength="255" required>
                </div>
                <div class="modal-form-group">
                    <label for="editDepartmentLogo">Change college logo <span style="color:#7d8b80;font-weight:500;">(optional)</span></label>
                    <div id="editDepartmentCurrentLogo" class="department-current-logo"></div>
                    <input type="file" name="logo" id="editDepartmentLogo" accept="image/png,image/jpeg,image/webp">
                    <small>Leave empty to keep the current logo. PNG, JPG, or WebP; maximum 2 MB.</small>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn gray" onclick="closeEditDepartment()">Cancel</button>
                    <button type="submit" class="btn green"><i class="fa-solid fa-check" aria-hidden="true"></i> Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditDepartment(id, code, name, logoPath) {
        const modal = document.getElementById('editDepartmentModal');
        const form = document.getElementById('editDepartmentForm');
        const codeInput = document.getElementById('editDepartmentCode');
        const nameInput = document.getElementById('editDepartmentName');
        const logoPreview = document.getElementById('editDepartmentCurrentLogo');
        const logoInput = document.getElementById('editDepartmentLogo');

        if (!modal || !form || !codeInput || !nameInput || !logoPreview) {
            console.error('Edit College modal not found.');
            return;
        }

        codeInput.value = code;
        nameInput.value = name;
        logoPreview.innerHTML = logoPath
            ? '<img class="department-logo-preview" src="' + @js(route('departments.logo', ['department' => '__department__'])).replace('__department__', encodeURIComponent(id)) + '?v=' + encodeURIComponent(logoPath) + '" alt="Current college logo">'
            : '<span><i class="fa-regular fa-image" aria-hidden="true"></i> No logo uploaded yet.</span>';
        if (logoInput) logoInput.value = '';
        form.action = @js(route('admin.departments.update', ['id' => '__department__'])).replace('__department__', encodeURIComponent(id));
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        window.setTimeout(function () { codeInput.focus(); }, 50);
    }

    function closeEditDepartment() {
        const modal = document.getElementById('editDepartmentModal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function confirmDelete(name) {
        return window.confirm('Delete "' + name + '"? This action cannot be undone.');
    }

    window.addEventListener('click', function (event) {
        const modal = document.getElementById('editDepartmentModal');
        if (modal && event.target === modal) closeEditDepartment();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeEditDepartment();
            if (typeof window.closeActionMessage === 'function') window.closeActionMessage();
        }
    });

    (function () {
        const message = document.getElementById('departmentActionMessage');

        function closeActionMessage() {
            if (!message) return;
            message.style.display = 'none';
            message.setAttribute('aria-hidden', 'true');
        }

        function prepareActionMessage() {
            if (!message) return;
            if (message.parentElement !== document.body) document.body.appendChild(message);
            message.style.display = 'flex';
            message.setAttribute('aria-hidden', 'false');
            window.setTimeout(closeActionMessage, 4000);
        }

        window.closeActionMessage = closeActionMessage;
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', prepareActionMessage, { once: true });
        } else {
            prepareActionMessage();
        }

        document.addEventListener('click', function (event) {
            if (message && event.target === message) closeActionMessage();
        });
    })();
</script>

