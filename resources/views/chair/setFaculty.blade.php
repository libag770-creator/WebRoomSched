@include('footerheader.header')
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

        <main class="content chair-workspace-redesign chair-faculty-redesign">

            <style>

                * {
                    box-sizing: border-box;
                }

                body {
                    background: #f5f5f5;
                }

                .page {
                    max-width: 1200px;
                    margin: auto;
                }


                /* =====================================================
                   TITLE
                ===================================================== */

                .title {
                    font-size: 28px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 5px;
                }

                .subtitle {
                    color: #777;
                    margin-bottom: 25px;
                }


                /* =====================================================
                   DEPARTMENT INFO
                ===================================================== */

                .department-info {
                    background: #e8f5e9;
                    border-left: 5px solid #2e7d32;
                    padding: 16px 18px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                }

                .department-info-label {
                    color: #777;
                    font-size: 12px;
                    margin-bottom: 4px;
                }

                .department-info-name {
                    color: #2e7d32;
                    font-size: 21px;
                    font-weight: bold;
                }


                /* =====================================================
                   SECTION
                ===================================================== */

                .section {
                    background: white;
                    padding: 25px;
                    border-radius: 12px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 10px rgba(0,0,0,.07);
                }

                .section-title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 18px;
                }


                /* =====================================================
                   FACULTY
                ===================================================== */

                .faculty-grid {
                    display: grid;
                    grid-template-columns:
                        repeat(auto-fill, minmax(210px, 1fr));
                    gap: 15px;
                }

                .faculty-card {
                    display: block;
                    background: white;
                    border: 2px solid #ddd;
                    border-radius: 12px;
                    padding: 20px;
                    text-align: center;
                    cursor: pointer;
                    transition: .2s;
                    text-decoration: none;
                    color: inherit;
                }

                .faculty-card:hover {
                    border-color: #f9a825;
                    transform: translateY(-2px);
                }

                .faculty-card.active {
                    border-color: #2e7d32;
                    background: #f1f8f2;
                }

                .faculty-icon {
                    width: 65px;
                    height: 65px;
                    border-radius: 50%;
                    background: #e8f5e9;
                    color: #2e7d32;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 12px;
                    font-size: 30px;
                }

                .faculty-name {
                    font-weight: bold;
                    color: #2e7d32;
                    word-break: break-word;
                }

                .faculty-count {
                    color: #777;
                    font-size: 12px;
                    margin-top: 7px;
                }


                /* =====================================================
                   SUBJECT MANAGER
                ===================================================== */

                .subject-section {
                    background: #fafafa;
                    border-top: 4px solid #2e7d32;
                    padding: 20px;
                    border-radius: 10px;
                }

                .profile {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    margin-bottom: 20px;
                }

                .profile .faculty-icon {
                    margin: 0;
                    width: 55px;
                    height: 55px;
                    font-size: 25px;
                    flex-shrink: 0;
                }

                .profile-name {
                    font-weight: bold;
                    color: #2e7d32;
                    font-size: 19px;
                }

                .profile-department {
                    color: #777;
                    font-size: 13px;
                    margin-top: 3px;
                }


                /* =====================================================
                   SUBJECT TABLE
                ===================================================== */

                .table-wrapper {
                    width: 100%;
                    overflow-x: auto;
                }

                .subject-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .subject-table th {
                    background: #2e7d32;
                    color: white;
                    padding: 11px;
                    text-align: left;
                    white-space: nowrap;
                }

                .subject-table td {
                    padding: 11px;
                    border-bottom: 1px solid #ddd;
                }

                .subject-table tr:hover {
                    background: #f9fff9;
                }


                /* =====================================================
                   MASTER CURRICULUM COURSES
                ===================================================== */

                .master-curriculum-box {
                    margin-top: 18px;
                    background: #f8fbf9;
                    border: 1px solid #dfe9e1;
                    border-radius: 10px;
                    padding: 15px;
                }

                .master-curriculum-title {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 10px;
                    margin-bottom: 10px;
                    color: #2e7d32;
                    font-size: 17px;
                    font-weight: 800;
                }

                .master-curriculum-subtitle {
                    margin: 0 0 12px;
                    color: #667085;
                    font-size: 11px;
                }

                .master-course-table {
                    width: 100%;
                    border-collapse: collapse;
                    min-width: 850px;
                }

                .master-course-table th {
                    background: #e8f5e9;
                    color: #2e7d32;
                    padding: 9px;
                    text-align: left;
                    font-size: 11px;
                    white-space: nowrap;
                }

                .master-course-table td {
                    padding: 9px;
                    border-bottom: 1px solid #e7ece8;
                    font-size: 11px;
                }

                .sync-badge {
                    display: inline-block;
                    padding: 4px 7px;
                    border-radius: 999px;
                    background: #e8f5e9;
                    color: #2e7d32;
                    font-size: 9px;
                    font-weight: 800;
                }

                .manual-help {
                    display: block;
                    margin-top: 6px;
                    color: #667085;
                    font-size: 10px;
                    line-height: 1.4;
                }


                /* =====================================================
                   ADD SUBJECT
                ===================================================== */

                .add-subject-title {
                    color: #2e7d32;
                    font-size: 17px;
                    font-weight: bold;
                    margin-top: 25px;
                    margin-bottom: 10px;
                }

                .subject-form {
                    margin-top: 15px;
                    display: grid;
                    grid-template-columns:
                        1fr
                        2fr
                        1fr
                        auto;
                    gap: 10px;
                    align-items: end;
                }


                /* =====================================================
                   FORM
                ===================================================== */

                label {
                    display: block;
                    font-size: 13px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                input,
                select {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    background: white;
                }

                input:focus,
                select:focus {
                    outline: none;
                    border-color: #2e7d32;
                    box-shadow: 0 0 0 2px rgba(46,125,50,.08);
                }


                /* =====================================================
                   BUTTONS
                ===================================================== */

                .btn {
                    border: none;
                    padding: 9px 14px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: bold;
                    text-decoration: none;
                    display: inline-block;
                }

                .green {
                    background: #2e7d32;
                    color: white;
                }

                .green:hover {
                    background: #1b5e20;
                }

                .yellow {
                    background: #f9a825;
                    color: #1b5e20;
                }

                .yellow:hover {
                    background: #e69b00;
                    color: white;
                }

                .red {
                    background: #c62828;
                    color: white;
                }

                .red:hover {
                    background: #a61b1b;
                }

                .gray {
                    background: #777;
                    color: white;
                }

                .gray:hover {
                    background: #616161;
                }


                /* =====================================================
                   MESSAGES
                ===================================================== */

                .message {
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }

                .success {
                    background: #e8f5e9;
                    color: #2e7d32;
                    border-left: 4px solid #2e7d32;
                }

                .error {
                    background: #ffebee;
                    color: #b71c1c;
                    border-left: 4px solid #c62828;
                }


                /* =====================================================
                   EMPTY
                ===================================================== */

                .empty {
                    padding: 30px 20px;
                    text-align: center;
                    color: #777;
                    background: #fafafa;
                    border: 1px dashed #ccc;
                    border-radius: 8px;
                }


                /* =====================================================
                   SET SCHEDULE LINK
                ===================================================== */

                .schedule-link-wrapper {
                    margin-top: 20px;
                    text-align: right;
                }


                /* =====================================================
                   MODAL
                ===================================================== */

                .modal {
                    display: none;
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,.5);
                    z-index: 9999;
                    padding: 30px;
                }

                .modal-content {
                    max-width: 500px;
                    background: white;
                    margin: 50px auto;
                    padding: 25px;
                    border-radius: 10px;
                    border-top: 5px solid #2e7d32;
                }


                /* =====================================================
                   RESPONSIVE
                ===================================================== */

                @media(max-width: 800px) {

                    .subject-form {
                        grid-template-columns: 1fr;
                    }

                    .faculty-grid {
                        grid-template-columns: 1fr 1fr;
                    }

                }

                @media(max-width: 550px) {

                    .faculty-grid {
                        grid-template-columns: 1fr;
                    }

                    .profile {
                        align-items: flex-start;
                    }

                    .subject-table {
                        font-size: 13px;
                    }

                    .subject-table th,
                    .subject-table td {
                        padding: 8px;
                    }

                    .schedule-link-wrapper {
                        text-align: center;
                    }

                }

            
                /* Center Faculty Setup feedback messages in the viewport. */
                .faculty-feedback-overlay { position:fixed; inset:0; z-index:999990; display:flex; align-items:center; justify-content:center; padding:20px; background:rgba(15,23,42,.5); backdrop-filter:blur(3px); }
                .faculty-feedback-dialog { width:min(440px,100%); background:#fff; border-radius:16px; padding:30px; box-shadow:0 24px 70px rgba(0,0,0,.25); text-align:center; border-top:5px solid #087443; }
                .faculty-feedback-dialog.error { border-top-color:#dc2626; }
                .faculty-feedback-dialog button { border:0; border-radius:8px; padding:10px 28px; color:#fff; background:#087443; cursor:pointer; font-weight:600; margin-top:14px; }
                .faculty-feedback-dialog.error button { background:#dc2626; }

                /* Chair Faculty & Subjects refresh */
                .faculty-setup-page { max-width: 1320px; margin: 0 auto; }
                .faculty-setup-hero { position:relative; display:flex; align-items:center; justify-content:space-between; gap:24px; overflow:hidden; margin-bottom:18px; padding:28px 30px; border:1px solid #dfeae2; border-radius:20px; background:linear-gradient(115deg,#fff 0%,#f4faf5 72%,#eaf5ed 100%); box-shadow:0 12px 30px rgba(21,45,32,.045); }
                .faculty-setup-hero::after { content:""; position:absolute; right:30px; top:-54px; width:190px; height:190px; border:1px solid rgba(23,110,72,.08); border-radius:50%; box-shadow:0 0 0 22px rgba(23,110,72,.025),0 0 0 48px rgba(23,110,72,.02); }
                .faculty-setup-hero-copy,.faculty-setup-department { position:relative; z-index:1; }
                .faculty-setup-eyebrow { display:flex; align-items:center; gap:7px; margin-bottom:9px; color:#71857a; font-size:9px; font-weight:800; letter-spacing:.14em; }
                .faculty-setup-eyebrow i { color:#287b50; }
                .faculty-setup-hero h1 { margin:0; color:#17251e; font-size:clamp(25px,3vw,34px); font-weight:780; letter-spacing:-.045em; line-height:1.15; }
                .faculty-setup-hero p { margin:8px 0 0; color:#748178; font-size:12px; line-height:1.6; }
                .faculty-setup-department { display:flex; align-items:center; gap:11px; min-width:190px; padding:13px 15px; border:1px solid #dce9df; border-radius:14px; background:rgba(255,255,255,.8); }
                .faculty-setup-department-icon { display:grid; width:37px; height:37px; flex:0 0 37px; place-items:center; border-radius:11px; background:#eaf5ed; color:#287b50; }
                .faculty-setup-department > div:last-child { display:grid; gap:3px; }
                .faculty-setup-department small { color:#8b978f; font-size:8px; font-weight:800; letter-spacing:.12em; }
                .faculty-setup-department strong { color:#365543; font-size:11px; }
                .faculty-setup-metrics { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin:0 0 18px; }
                .faculty-setup-metric { display:flex; align-items:center; gap:12px; min-height:78px; padding:14px 16px; border:1px solid #e4ebe6; border-radius:15px; background:#fff; box-shadow:0 7px 20px rgba(21,45,32,.03); }
                .faculty-setup-metric-icon { display:grid; width:37px; height:37px; flex:0 0 37px; place-items:center; border-radius:11px; background:#edf6ef; color:#287b50; font-size:13px; }
                .faculty-setup-metric:nth-child(2) .faculty-setup-metric-icon { background:#eef4fb;color:#557eaa; }
                .faculty-setup-metric:nth-child(3) .faculty-setup-metric-icon { background:#f4f0fb;color:#826aa6; }
                .faculty-setup-metric-copy { display:grid; gap:3px; }
                .faculty-setup-metric-copy strong { color:#263c2e; font-size:18px; line-height:1; }
                .faculty-setup-metric-copy span { color:#829087; font-size:9px; }
                .faculty-setup-page > .section { margin-bottom:16px !important; padding:20px !important; border:1px solid #e4ebe6 !important; border-radius:16px !important; background:#fff !important; box-shadow:0 9px 24px rgba(21,45,32,.035) !important; }
                .faculty-setup-page .section-title { display:flex; align-items:center; gap:9px; margin-bottom:14px; color:#24392d; font-size:14px; font-weight:750; }
                .faculty-setup-page .section-title::before { content:""; width:4px; height:17px; border-radius:4px; background:#258052; }
                .faculty-setup-page .department-info { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; padding:12px 15px; border:1px solid #e0ece3; border-left:3px solid #258052; border-radius:12px; background:#f5faf6; }
                .faculty-setup-page .department-info-label { margin:0; color:#829087; font-size:9px; font-weight:750; letter-spacing:.1em; text-transform:uppercase; }
                .faculty-setup-page .department-info-name { color:#31543e; font-size:12px; font-weight:750; }
                .faculty-setup-page .faculty-grid { grid-template-columns:repeat(auto-fill,minmax(175px,1fr)); gap:10px; }
                .faculty-setup-page .faculty-card { min-height:130px; padding:16px 13px; border:1px solid #e5ece7; border-radius:14px; background:#fff; text-align:left; box-shadow:0 5px 15px rgba(21,45,32,.025); }
                .faculty-setup-page .faculty-card:hover { transform:translateY(-2px); border-color:#c9dfd0; box-shadow:0 10px 20px rgba(21,45,32,.07); }
                .faculty-setup-page .faculty-card.active { border-color:#a9cfb5; background:#f4faf5; box-shadow:inset 3px 0 #258052; }
                .faculty-setup-page .faculty-icon { width:40px; height:40px; margin:0 0 12px; border-radius:12px; background:#edf6ef; color:#287b50; font-size:16px; }
                .faculty-setup-page .faculty-name { color:#294233; font-size:11px; line-height:1.4; }
                .faculty-setup-page .faculty-count { margin-top:5px; color:#89958d; font-size:9px; }
                .faculty-total-badge { display:inline-grid; min-width:21px; height:21px; place-items:center; padding:0 6px; border-radius:999px; background:#edf6ef; color:#287b50; font-size:9px; font-weight:800; vertical-align:middle; }
                .faculty-directory-note { margin:-7px 0 14px; color:#849087; font-size:10px; line-height:1.5; }
                .faculty-directory-origin { display:inline-flex; align-items:center; gap:5px; width:max-content; max-width:100%; margin:0 0 12px; padding:5px 8px; overflow:hidden; border-radius:999px; background:#edf6ef; color:#34774e; font-size:8px; font-weight:800; text-overflow:ellipsis; white-space:nowrap; }
                .faculty-directory-origin.imported { background:#eef3fb; color:#5577a0; }
                .faculty-directory-item.imported { display:flex; min-height:130px; flex-direction:column; overflow:hidden; border:1px solid #e5ece7; border-radius:14px; background:#fff; box-shadow:0 5px 15px rgba(21,45,32,.025); transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease; }
                .faculty-directory-item.imported { border-color:#d5e2f2; background:#f6f9fe; }
                .faculty-directory-item.imported:hover { transform:translateY(-2px); border-color:#abc4e1; box-shadow:0 10px 20px rgba(21,45,32,.07); }
                .faculty-setup-page .faculty-directory-item .faculty-card { display:flex; min-height:0; flex:1; flex-direction:column; border:0; border-radius:0; box-shadow:none; }
                .faculty-setup-page .faculty-directory-item .faculty-card:hover { transform:none; border:0; box-shadow:none; }
                .faculty-setup-page .faculty-directory-item .faculty-card.active { background:#edf4fc; box-shadow:inset 3px 0 #648bb8; }
                .faculty-manage-label { display:inline-flex; align-items:center; gap:6px; margin-top:auto; padding-top:10px; color:#287b50; font-size:9px; font-weight:750; }
                .faculty-remove-import-form { padding:0 12px 10px; text-align:center; }
                .faculty-remove-import-form button { width:100%; padding:7px 9px; border:1px solid #ece4e2; border-radius:8px; background:#fff8f7; color:#9a5149; font-size:8px; font-weight:750; cursor:pointer; }
                .faculty-remove-import-form button:hover { border-color:#e7c9c5; background:#fff0ee; }
                .faculty-directory-empty { grid-column:1/-1; }
                .faculty-import-tile { display:flex; min-height:130px; align-items:center; justify-content:center; flex-direction:column; gap:8px; padding:16px 13px; border:1px dashed #aebfb3; border-radius:14px; background:#f8fbf9; color:#53705e; cursor:pointer; transition:all .2s ease; }
                .faculty-import-tile:hover { transform:translateY(-2px); border-color:#258052; background:#eff7f1; box-shadow:0 10px 20px rgba(21,45,32,.07); }
                .faculty-import-plus { display:grid; width:40px; height:40px; place-items:center; border-radius:12px; background:#e5f1e8; color:#258052; font-size:17px; }
                .faculty-import-tile strong { color:#294233; font-size:11px; }
                .faculty-import-tile span:last-child { color:#89958d; font-size:9px; }
                .faculty-import-modal { position:fixed; inset:0; z-index:10000; display:none; align-items:center; justify-content:center; padding:20px; background:rgba(14,35,24,.56); backdrop-filter:blur(4px); }
                .faculty-import-modal.is-open { display:flex; }
                .faculty-import-dialog { width:min(100%,480px); overflow:hidden; border:1px solid #e1e9e3; border-radius:18px; background:#fff; box-shadow:0 24px 65px rgba(15,35,24,.24); }
                .faculty-import-dialog-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; padding:22px 23px 15px; }
                .faculty-import-dialog-head h2 { margin:0; color:#24392d; font-size:17px; font-weight:750; }
                .faculty-import-dialog-head p { margin:6px 0 0; color:#839087; font-size:11px; line-height:1.55; }
                .faculty-import-close { display:grid; width:32px; height:32px; flex:0 0 32px; place-items:center; border:0; border-radius:9px; background:#f2f5f3; color:#65746b; cursor:pointer; }
                .faculty-import-dialog form { padding:0 23px 22px; }
                .faculty-import-dialog label { display:block; margin:0 0 7px; color:#53635a; font-size:10px; font-weight:700; }
                .faculty-import-dialog select { width:100%; min-height:43px; padding:0 12px; border:1px solid #dce5de; border-radius:10px; background:#fff; color:#34483a; font-size:12px; }
                .faculty-import-actions { display:flex; justify-content:flex-end; gap:9px; margin-top:18px; }
                .faculty-import-actions button { min-height:38px; padding:0 15px; border:1px solid #dce5de; border-radius:9px; background:#fff; color:#53635a; font-size:11px; font-weight:700; cursor:pointer; }
                .faculty-import-actions button[type="submit"] { border-color:#258052; background:#258052; color:#fff; }
                .faculty-import-actions button[type="submit"]:disabled { border-color:#d4ddd6; background:#d4ddd6; cursor:not-allowed; }
                .faculty-setup-page .subject-section { padding:18px; border:1px solid #e4ebe6; border-top:3px solid #258052; border-radius:15px; background:#fff; }
                .faculty-setup-page .profile { padding:14px; border:1px solid #e9efea; border-radius:13px; background:#f8faf8; }
                .faculty-setup-page .profile .faculty-icon { margin:0; }
                .faculty-setup-page .profile-name { color:#24392d; font-size:15px; }
                .faculty-setup-page .profile-department { color:#849087; font-size:10px; }
                .faculty-setup-page .subject-table { min-width:760px; border-spacing:0; border-radius:12px; overflow:hidden; }
                .faculty-setup-page .subject-table th { padding:11px 12px; background:#f3f7f4 !important; color:#65746b !important; font-size:9px !important; }
                .faculty-setup-page .subject-table td { padding:11px 12px; border-bottom:1px solid #edf1ee; color:#526158; font-size:10px; }
                .faculty-setup-page .subject-table tbody tr:hover { background:#f8fbf9; }
                .faculty-setup-page .subject-table tbody tr.faculty-subject-original { opacity:.48; background:#f3f5f6; }
                .faculty-setup-page .subject-table tbody tr.faculty-subject-original:hover { background:#f3f5f6; }
                .faculty-original-label { display:inline-flex; align-items:center; gap:5px; margin-top:4px; color:#7d8790; font-size:8px; font-weight:700; }
                .faculty-setup-page .add-subject-title { margin:23px 0 10px; padding-top:19px; border-top:1px solid #e9efea; color:#294233; font-size:14px; font-weight:750; }
                .faculty-setup-page .subject-form { gap:12px; }
                .faculty-setup-page .subject-form label { color:#53635a; font-size:10px; }
                .faculty-setup-page .schedule-link-wrapper { padding-top:15px; border-top:1px solid #edf1ee; }
                .faculty-setup-page .modal { background:rgba(14,35,24,.52); backdrop-filter:blur(4px); }
                .faculty-setup-page .modal-content { border:1px solid #e1e9e3; border-top:4px solid #258052; border-radius:17px; box-shadow:0 24px 65px rgba(15,35,24,.2); }
                @media(max-width:760px) { .faculty-setup-hero { align-items:flex-start; flex-direction:column; padding:22px; }.faculty-setup-department { width:100%; }.faculty-setup-metrics { gap:8px; }.faculty-setup-metric { padding:12px; } }
                @media(max-width:520px) { .faculty-setup-metrics { grid-template-columns:1fr; }.faculty-setup-page > .section { padding:15px !important; }.faculty-setup-page .faculty-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }.faculty-setup-page .faculty-card { min-height:120px; }.faculty-setup-page .department-info { align-items:flex-start; flex-direction:column; gap:5px; } }

            </style>
            <link rel="stylesheet" href="{{ asset('css/chair-workspace.css') }}">



            <div class="page faculty-setup-page">


                <!-- =================================================
                     TITLE
                ================================================== -->

                <header class="faculty-setup-hero">
                    <div class="faculty-setup-hero-copy">
                        <div class="faculty-setup-eyebrow"><i class="fa-solid fa-users-gear" aria-hidden="true"></i> DEPARTMENT WORKSPACE</div>
                        <h1>Faculty &amp; Subjects</h1>
                        <p>Organize faculty access and maintain each instructor’s course assignments.</p>
                    </div>
                    <div class="faculty-setup-department">
                        <span class="faculty-setup-department-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                        <div><small>YOUR DEPARTMENT</small><strong>{{ $department->code ?? $department->name ?? 'Department' }}</strong></div>
                    </div>
                </header>

                <section class="faculty-setup-metrics" aria-label="Faculty setup summary">
                    <div class="faculty-setup-metric"><span class="faculty-setup-metric-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span><div class="faculty-setup-metric-copy"><strong>{{ $facultyMemberCount }}</strong><span>Department faculty</span></div></div>
                    <div class="faculty-setup-metric"><span class="faculty-setup-metric-icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span><div class="faculty-setup-metric-copy"><strong>{{ $assignedSubjectCount }}</strong><span>Assigned subjects</span></div></div>
                    <div class="faculty-setup-metric"><span class="faculty-setup-metric-icon"><i class="fa-solid fa-user-plus" aria-hidden="true"></i></span><div class="faculty-setup-metric-copy"><strong>{{ $importedFacultyCount }}</strong><span>Imported faculty</span></div></div>
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
                     CHAIR'S DEPARTMENT
                ================================================== -->

                <div class="department-info">

                    <div class="department-info-label">
                        Your Department
                    </div>

                    <div class="department-info-name">

                        {{ $department->code ?? '' }}

                        @if(
                            isset($department->name)
                            && $department->name
                        )

                            -
                            {{ $department->name }}

                        @endif

                    </div>

                </div>


                <!-- =================================================
                     FACULTY MEMBERS
                ================================================== -->

                <div class="section">

                    <div class="section-title">
                        Faculty Members <span class="faculty-total-badge">{{ $facultyMemberCount + $importedFacultyCount }}</span>
                    </div>

                    <p class="faculty-directory-note">Department faculty and imported faculty appear together. Each card is labeled by its source.</p>
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

                                <div class="faculty-directory-origin original"><i class="fa-solid fa-building" aria-hidden="true"></i> Department faculty</div>
                                <div class="faculty-icon"><i class="fa-solid fa-user" aria-hidden="true"></i></div>
                                <div class="faculty-name">
                                    {{ $faculty->name }}
                                </div>
                                <div class="faculty-count">
                                    {{ $faculty->facultySubjects->count() }} assigned subject(s)
                                </div>
                            </a>
                        @endforeach

                        @foreach($importedFaculty as $faculty)
                            <article class="faculty-directory-item imported">
                                <a
                                    href="{{ route('chair.setFaculty', ['faculty' => $faculty->id]) }}"
                                    class="faculty-card {{ $selectedFaculty && $selectedFaculty->id == $faculty->id ? 'active' : '' }}"
                                >
                                    <div class="faculty-directory-origin imported"><i class="fa-solid fa-arrow-down-to-bracket" aria-hidden="true"></i> Imported · {{ $faculty->department->code ?? $faculty->department->name ?? 'Other department' }}</div>
                                    <div class="faculty-icon"><i class="fa-solid fa-user" aria-hidden="true"></i></div>
                                    <div class="faculty-name">{{ $faculty->name }}</div>
                                    <div class="faculty-count">{{ $faculty->departmentSubjects->count() }} department subject(s) · {{ $faculty->facultySubjects->count() - $faculty->departmentSubjects->count() }} original</div>
                                    <span class="faculty-manage-label">Manage subjects <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                                </a>
                                <form
                                    action="{{ route('chair.faculty.import.remove', $faculty->id) }}"
                                    method="POST"
                                    class="faculty-remove-import-form"
                                    onsubmit="return confirm('Remove this faculty member from your imported list? Their account will not be deleted.');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"><i class="fa-solid fa-link-slash" aria-hidden="true"></i> Remove import</button>
                                </form>
                            </article>
                        @endforeach

                        @if($facultyMemberCount === 0 && $importedFacultyCount === 0)
                            <div class="empty faculty-directory-empty">No faculty members are assigned to {{ $department->name }} or imported yet.</div>
                        @endif

                        <button type="button" class="faculty-import-tile" id="openFacultyImportModal" aria-haspopup="dialog" aria-controls="facultyImportModal">
                            <span class="faculty-import-plus"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
                            <strong>Import faculty</strong>
                            <span>Add a colleague from another department</span>
                        </button>

                    </div>

                </div>

<div class="faculty-import-modal" id="facultyImportModal" role="dialog" aria-modal="true" aria-labelledby="facultyImportTitle" aria-hidden="true">
    <div class="faculty-import-dialog">
        <div class="faculty-import-dialog-head">
            <div>
                <h2 id="facultyImportTitle">Import faculty member</h2>
                <p>Choose a colleague from another department. Their existing subject assignments will remain with their home department.</p>
            </div>
            <button type="button" class="faculty-import-close" id="closeFacultyImportModal" aria-label="Close dialog"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
        </div>
        <form action="{{ route('chair.faculty.import') }}" method="POST">
            @csrf
            <label for="faculty_id">Faculty member</label>
            <select name="faculty_id" id="faculty_id" required {{ $availableFaculty->isEmpty() ? 'disabled' : '' }}>
                <option value="">{{ $availableFaculty->isEmpty() ? 'No faculty members available to import' : 'Select a faculty member' }}</option>
                @foreach($availableFaculty as $availableMember)
                    <option value="{{ $availableMember->id }}">{{ $availableMember->name }} — {{ $availableMember->department->code ?? $availableMember->department->name ?? 'Other department' }}</option>
                @endforeach
            </select>
            <div class="faculty-import-actions">
                <button type="button" id="cancelFacultyImport">Cancel</button>
                <button type="submit" {{ $availableFaculty->isEmpty() ? 'disabled' : '' }}>Import faculty</button>
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

                                        Department:

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
        <span class="faculty-original-label"><i class="fa-solid fa-lock" aria-hidden="true"></i> Original department subject · read only</span>
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

                            <div class="add-subject-title">
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
    <p id="facultyCourseSearchStatus" role="status" aria-live="polite" style="margin:8px 0;color:#40566c;font-size:14px;min-height:20px;">Enter a course code already added in Manage Subjects.</p>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px;margin-top:14px;">
        <div><label for="facultySubjectTitleDisplay">Course title</label><input id="facultySubjectTitleDisplay" type="text" readonly aria-readonly="true" placeholder="Filled from Manage Subjects"></div>
        <div><label for="facultyYearLevelDisplay">Year level</label><input id="facultyYearLevelDisplay" type="text" readonly aria-readonly="true" placeholder="Filled from subject catalog"></div>
        <div><label for="facultySemesterDisplay">Semester</label><input id="facultySemesterDisplay" type="text" readonly aria-readonly="true" placeholder="Filled from subject catalog"></div>
        <div><label for="facultyProgramDisplay">Program</label><input id="facultyProgramDisplay" type="text" readonly aria-readonly="true" placeholder="Filled from subject catalog"></div>
        <div><label for="facultyMajorDisplay">Major</label><input id="facultyMajorDisplay" type="text" readonly aria-readonly="true" placeholder="No major specified"></div>
        <div><label for="facultySubjectTypeDisplay">Subject type</label><input id="facultySubjectTypeDisplay" type="text" readonly aria-readonly="true" placeholder="Lec or Lab"></div>
    </div>
    <button type="submit" class="btn green" id="assignCatalogSubjectButton" disabled style="margin-top:16px;">Assign Subject to Faculty</button>
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

    document.getElementById('editSubjectForm').action = "{{ url('/chair/faculty/subjects') }}/" + id;
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
        status.textContent = 'Subject found. Confirm the details, then assign it to this faculty member.';
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

@include('footerheader.footer')
