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
    .chair-faculty-view .department-info { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:0 0 16px; padding:14px 17px; border:1px solid #dfe7dd; border-radius:13px; background:linear-gradient(110deg,#f5f7f1,#fff); }
    .chair-faculty-view .department-info-label { color:#68776d; font-size:12px; font-weight:750; }
    .chair-faculty-view .department-info-name { color:#244a35; font-size:14px; font-weight:850; }
    .chair-faculty-view .section { min-width:0; margin:0 0 17px; padding:21px; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .chair-faculty-view .section-title { display:flex; align-items:center; gap:10px; margin:0 0 6px; color:#173b33; font-size:21px; font-weight:800; letter-spacing:-.03em; }
    .chair-faculty-view .faculty-total-badge { display:inline-grid; min-width:27px; height:27px; place-items:center; padding:0 7px; border-radius:99px; background:#edf3eb; color:#285940; font-size:12px; font-weight:850; }
    .chair-faculty-view .faculty-directory-note { margin:0 0 16px; color:#5b6d61; font-size:13px; font-weight:550; line-height:1.55; }
    .chair-faculty-view .faculty-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; }
    .chair-faculty-view .faculty-card { position:relative; display:flex; min-width:0; min-height:135px; flex-direction:column; align-items:flex-start; padding:13px 14px; overflow:hidden; border:1px solid #e2e9e1; border-radius:14px; background:#fff; color:inherit; text-decoration:none; transition:transform .16s ease,border-color .16s ease,box-shadow .16s ease; }
    .chair-faculty-view .faculty-card:hover { transform:translateY(-2px); border-color:#adc5b0; box-shadow:0 10px 22px rgba(31,57,43,.08); }
    .chair-faculty-view .faculty-card.active { border-color:#78a080; background:linear-gradient(145deg,#f1f7ef,#fff); box-shadow:inset 3px 0 #356a4d; }
    .chair-faculty-view .faculty-directory-origin { display:flex; align-items:center; gap:6px; min-height:22px; margin-bottom:7px; padding:0 8px; border:1px solid #e1e8df; border-radius:99px; background:#f6f8f4; color:#53675a; font-size:11px; font-weight:800; line-height:1.35; }
    .chair-faculty-view .faculty-directory-origin.imported { border-color:#e6ddc4; background:#fbf8ee; color:#806a39; }
    .chair-faculty-view .faculty-icon { display:grid; flex:0 0 40px; width:40px; height:40px; place-items:center; margin-bottom:7px; border:1px solid #e2e9df; border-radius:50%; background:#edf3eb; color:#285940; font-size:17px; }
    .chair-faculty-view img.faculty-icon { object-fit:cover; }
    .chair-faculty-view .faculty-icon[hidden] { display:none !important; }
    .chair-faculty-view .faculty-name { color:#203d30; font-size:16px; font-weight:850; line-height:1.35; overflow-wrap:anywhere; }
    .chair-faculty-view .faculty-count { margin-top:4px; color:#53675a; font-size:13px; font-weight:650; line-height:1.4; }
    .chair-faculty-view .faculty-manage-label { display:inline-flex; align-items:center; gap:7px; margin-top:auto; padding-top:8px; color:#315b43; font-size:12px; font-weight:800; }
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
                            <a
                                href="{{ route(
                                    'chair.setFaculty',
                                    [
                                        'faculty' =>
                                            $faculty->id
                                    ]
                                ) }}"
                                class="
                                    faculty-card
                                    {{
                                        $selectedFaculty
                                        && $selectedFaculty->id == $faculty->id
                                            ? 'active'
                                            : ''
                                    }}
                                "
                            >

                                <div class="faculty-directory-origin original"><i class="fa-solid fa-building" aria-hidden="true"></i> College instructors</div>
                                @if($faculty->profile_photo_path)
                                    <img class="faculty-icon" src="{{ $faculty->profile_photo_url }}" alt="{{ $faculty->name }} profile photo" onerror="this.hidden=true; this.nextElementSibling.hidden=false">
                                    <span class="faculty-icon" aria-hidden="true" hidden><i class="fa-solid fa-user"></i></span>
                                @else
                                    <div class="faculty-icon"><i class="fa-solid fa-user" aria-hidden="true"></i></div>
                                @endif
                                <div class="faculty-name">
                                    {{ $faculty->name }}
                                </div>
                                <div class="faculty-count">
                                    {{ $faculty->facultySubjects->count() }} assigned subject(s)
                                </div>
                                <span class="faculty-manage-label">Manage assignments <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                            </a>
                        @endforeach

                        @foreach($importedFaculty as $faculty)
                            <article class="faculty-directory-item imported">
                                <a
                                    href="{{ route('chair.setFaculty', ['faculty' => $faculty->id]) }}"
                                    class="faculty-card {{ $selectedFaculty && $selectedFaculty->id == $faculty->id ? 'active' : '' }}"
                                >
                                    <div class="faculty-directory-origin imported"><i class="fa-solid fa-arrow-down-to-bracket" aria-hidden="true"></i> Imported · {{ $faculty->department->code ?? $faculty->department->name ?? 'Other college' }}</div>
                                    @if($faculty->profile_photo_path)
                                        <img class="faculty-icon" src="{{ $faculty->profile_photo_url }}" alt="{{ $faculty->name }} profile photo" onerror="this.hidden=true; this.nextElementSibling.hidden=false">
                                        <span class="faculty-icon" aria-hidden="true" hidden><i class="fa-solid fa-user"></i></span>
                                    @else
                                        <div class="faculty-icon"><i class="fa-solid fa-user" aria-hidden="true"></i></div>
                                    @endif
                                    <div class="faculty-name">{{ $faculty->name }}</div>
                                    <div class="faculty-count">{{ $faculty->departmentSubjects->count() }} college subject(s) · {{ $faculty->facultySubjects->count() - $faculty->departmentSubjects->count() }} original</div>
                                    <span class="faculty-manage-label">Manage subjects <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                                </a>
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
    {{ $facultySubject->major ?? '-' }}
</td>

<td>
    {{ $facultySubject->program ?? '-' }}
    @if($facultySubject->sharedCohorts->isNotEmpty())
        <div style="margin-top:5px;color:#557060;font-size:11px;line-height:1.45;">
            Also serves: {{ $facultySubject->sharedCohorts->map(fn ($cohort) => trim(($cohort->program?->code ?: $cohort->program?->name ?: '') . ($cohort->program?->major ? ' · ' . $cohort->program->major : '') . ($cohort->year_level ? ' · ' . $cohort->year_level : '')))->filter()->unique()->implode('; ') }}
        </div>
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
    <input type="text" name="course_code" id="facultyCourseCode" placeholder="Example: IT 101" autocomplete="off" required>
    <input type="hidden" name="subject_id" id="facultySubjectId">
    <div id="facultySubjectChoiceWrap" hidden style="margin-top:14px;">
        <label for="facultySubjectChoice">Choose the correct subject</label>
        <select id="facultySubjectChoice" style="width:100%;padding:11px;border:1px solid #bdcddd;border-radius:8px;background:#fff;font-size:15px;">
            <option value="">Choose a year level</option>
        </select>
    </div>
    <div id="facultyCohortChoiceWrap" hidden style="margin-top:14px;padding:14px;border:1px solid #d9e5da;border-radius:12px;background:#f7faf7;">
        <strong style="display:block;color:#294533;font-size:13px;margin-bottom:5px;">Also assign this course to these groups</strong>
        <p id="facultyCohortChoiceHelp" style="margin:0 0 10px;color:#68776c;font-size:12px;line-height:1.45;">The selected course above is the primary group. Check any other matching programs or majors taught by this instructor.</p>
        <div id="facultyCohortChoices" style="display:grid;gap:7px;max-height:190px;overflow:auto;"></div>
    </div>
    <p id="facultyCourseSearchStatus" role="status" aria-live="polite" style="margin:8px 0;color:#40566c;font-size:14px;min-height:20px;">Enter a course code already added in Manage Subjects.</p>
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

function editSubject(id, courseCode, subject, yearLevel, major, program) {
    document.getElementById('editSubjectCode').value = courseCode || '';
    document.getElementById('editSubjectName').value = subject || '';
    document.getElementById('editSubjectYear').value = yearLevel || '';
    document.getElementById('editSubjectYearDisplay').value = yearLevel || '';
    document.getElementById('editSubjectProgram').value = program || '';
    document.getElementById('editSubjectMajor').value = major || '';

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
document.addEventListener('keydown', event => {
    if (event.key === 'Escape') setFacultyImportModal(false);
});

window.addEventListener('click', function(event) {
    const modal = document.getElementById('editSubjectModal');
    if (modal && event.target === modal) closeEditSubject();
});

// Display success/error/validation feedback as a centered modal instead of inline boxes.
document.addEventListener('DOMContentLoaded', function () {
    const messages = Array.from(document.querySelectorAll('.page .message'));
    if (document.getElementById('globalMessageOverlay')) { messages.forEach(message => message.remove()); return; }
    if (!messages.length) return;
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
            label.style.cssText = 'display:flex;align-items:flex-start;gap:9px;padding:9px 10px;border:1px solid #e2eae2;border-radius:9px;background:#fff;color:#34483b;font-size:12px;line-height:1.45;cursor:pointer;';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'cohort_subject_ids[]';
            checkbox.value = String(item.id);
            checkbox.style.cssText = 'margin:2px 0 0;accent-color:#28583f;';
            const detail = document.createElement('span');
            detail.textContent = [item.program, item.major ? 'Major: ' + item.major : '', item.year_level, item.semester, item.memorandum ? 'Memorandum: ' + item.memorandum : ''].filter(Boolean).join(' · ');
            label.append(checkbox, detail);
            cohortChoices.appendChild(label);
        });
    };
    const selectSubject = item => {
        if (!item) return clearSelection();
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
        clearSelection();
        resetChoice();
        const code = codeInput.value.trim();
        if (!code) {
            status.textContent = 'Enter a course code already added in Manage Subjects.';
            status.style.color = '#40566c';
            return;
        }
        status.textContent = 'Looking up this course code…';
        status.style.color = '#40566c';
        timer = window.setTimeout(async () => {
            try {
                const url = new URL(@json(route('chair.subjects.lookup')), window.location.origin);
                url.searchParams.set('course_code', code);
                const response = await fetch(url, { headers: { Accept: 'application/json' } });
                if (!response.ok) throw new Error('lookup failed');
                const data = await response.json();
                matches = data.subjects || [];
                if (!matches.length) {
                    status.textContent = 'No matching subject was found. Add it in Manage Subjects first.';
                    status.style.color = '#9b2c2c';
                    return;
                }
                if (matches.length === 1) {
                    selectSubject(matches[0]);
                    return;
                }
                const yearLevels = [...new Set(matches.map(item => item.year_level))];
                const semesters = [...new Set(matches.map(item => item.semester).filter(Boolean))];
                status.textContent = yearLevels.length > 1 || semesters.length > 1
                    ? 'This course code appears in more than one year level or semester. Choose the correct year, semester, and program below.'
                    : 'More than one program or major uses this course code. Choose the correct entry below.';
                status.style.color = '#8a5a13';
                choice.replaceChildren(new Option('Choose the correct subject', ''));
                matches.forEach(item => {
                    const details = [item.year_level, item.semester, item.program, item.major ? `Major: ${item.major}` : 'No major'];
                    choice.add(new Option(details.join(' · '), String(item.id)));
                });
                choiceWrap.hidden = false;
            } catch (error) {
                status.textContent = 'Could not check the course code. Please try again.';
                status.style.color = '#9b2c2c';
            }
        }, 250);
    });

    choice?.addEventListener('change', () => {
        selectSubject(matches.find(item => String(item.id) === choice.value));
    });
});
</script>
        </main>

    </div>

</div>


