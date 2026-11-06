<!DOCTYPE html>
<html lang="en">

<head>
  

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/excel.css') }}">

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Class Program - {{ $room->room_name }}
    </title>



<style>
/* Claymorphism: soft pastel surfaces, rounded edges and raised depth. */
:root {
    --clay-ink:#1d2b40;
    --clay-text:#344963;
    --clay-muted:#526780;
    --clay-blue:#426aa6;
    --clay-blue-deep:#34588f;
    --clay-border:#d5deeb;
    --clay-base:#eaf0f8;
    --clay-raised:7px 7px 15px #cbd4e1,-7px -7px 15px #ffffff,inset 1px 1px 0 rgba(255,255,255,.92);
    --clay-inset:inset 4px 4px 9px #d7dfeb,inset -4px -4px 9px #ffffff;
}
.chair-excel-clay {
    min-height:100vh;
    background:#e9eef6 !important;
    color:var(--clay-ink) !important;
    font-size:16px;
    line-height:1.55;
}
.chair-excel-clay * { box-sizing:border-box; }
.chair-excel-clay .page { width:min(100%,1680px); max-width:1680px; padding:22px clamp(14px,2vw,30px) 42px; }
.chair-excel-clay .top-bar { margin:0 0 18px; }
.chair-excel-clay .back-link {
    display:inline-flex; align-items:center; justify-content:center; min-height:46px; padding:10px 17px;
    border:1px solid #d5deeb; border-radius:15px; background:linear-gradient(145deg,#f3f6fc,#e6ecf5);
    color:#34516f; font-size:15px; font-weight:750; text-decoration:none;
    box-shadow:var(--clay-raised); transition:transform .18s ease,box-shadow .18s ease,color .18s ease;
}
.chair-excel-clay .back-link:hover { transform:translateY(-2px); color:#244c80; box-shadow:9px 9px 18px #cbd4e1,-8px -8px 17px #fff; }
.chair-excel-clay .program-header,
.chair-excel-clay .editor-panel,
.chair-excel-clay .merge-section {
    color:var(--clay-ink) !important;
    border:1px solid var(--clay-border) !important;
    border-radius:24px !important;
    background:linear-gradient(145deg,#f1f5fc,#e5ebf5) !important;
    box-shadow:10px 10px 22px #cbd4e1,-9px -9px 22px #fff,inset 1px 1px 0 rgba(255,255,255,.95) !important;
}
.chair-excel-clay .program-header { grid-template-columns:minmax(230px,1fr) auto; gap:20px 26px; padding:clamp(22px,3vw,34px); margin:0 0 23px; }
.chair-excel-clay .program-title { color:#1d2b40 !important; font-size:clamp(32px,3vw,42px) !important; font-weight:900; line-height:1.15; letter-spacing:-.035em; }
.chair-excel-clay .program-header > div:first-child > div[style] {
    display:inline-flex; align-items:center; margin-top:12px !important; padding:8px 13px; border:1px solid #cbd8ea; border-radius:12px;
    background:#e4ebf6; color:#345986 !important; font-size:15px !important; font-weight:800 !important; box-shadow:var(--clay-inset);
}
.chair-excel-clay .schedule-information { display:flex; gap:12px; padding:12px; border:1px solid #d6dfec; border-radius:18px; background:#e8edf6; box-shadow:var(--clay-inset); }
.chair-excel-clay .schedule-info-field { min-width:155px; }
.chair-excel-clay .schedule-info-field label,
.chair-excel-clay .form-group label,
.chair-excel-clay .program-popup-card label { display:block; margin:0 0 7px; color:#344963 !important; font-size:15px !important; font-weight:750 !important; line-height:1.4; letter-spacing:0; text-transform:none; }
.chair-excel-clay .schedule-info-field input,
.chair-excel-clay .schedule-info-field select { width:100%; min-height:46px; padding:9px 12px; border:1px solid #cfdae9; border-radius:12px; background:#edf2f9; color:#293f59; font-size:15px; box-shadow:var(--clay-inset); }
.chair-excel-clay .draft-mode { grid-column:1/-1; width:max-content; max-width:100%; padding:9px 14px; border:1px solid #e2cda1; border-radius:12px; background:#f7edd7; color:#684e1d; font-size:14px; font-weight:850; box-shadow:5px 5px 12px #d4d9df,-5px -5px 12px #fff; }
.chair-excel-clay .program-meta { grid-column:1/-1; display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin:0; padding:0; background:transparent; border:0; box-shadow:none; }
.chair-excel-clay .meta-item { min-width:0; min-height:76px; padding:14px 16px; border:1px solid #d6dfec; border-radius:17px; background:linear-gradient(145deg,#eef3fb,#e5ebf5); color:#2e435d; font-size:15px; line-height:1.5; overflow-wrap:anywhere; box-shadow:6px 6px 13px #d0d8e4,-6px -6px 13px #fff,inset 1px 1px 0 #fff; }
.chair-excel-clay .meta-item strong { display:block; margin-bottom:4px; color:#506987; font-size:13px; font-weight:850; letter-spacing:.03em; text-transform:uppercase; }
.chair-excel-clay .meta-item:hover { background:#f3f6fc; }
.chair-excel-clay .editor-panel,.chair-excel-clay .merge-section { padding:clamp(18px,2.2vw,27px); }
.chair-excel-clay .editor-title,.chair-excel-clay .merge-title { color:#1d2b40 !important; font-size:23px !important; font-weight:850; line-height:1.35; }
.chair-excel-clay .editor-title::before { width:7px; height:28px; border-radius:999px; background:linear-gradient(180deg,#6b91ca,#8c7ec0); }
.chair-excel-clay .editor-subtitle,.chair-excel-clay .merge-description { margin-top:9px; color:#526780 !important; font-size:16px !important; line-height:1.65; }
.chair-excel-clay .form-grid { grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; align-items:end; }
.chair-excel-clay .form-group { min-width:0; }
.chair-excel-clay input:not([type="color"]),
.chair-excel-clay select,
.chair-excel-clay textarea,
.chair-excel-clay .search-box,
.chair-excel-clay .program-popup-card select {
    width:100%; min-height:48px; padding:10px 13px; border:1px solid #cdd8e8 !important; border-radius:13px !important;
    background:#eaf0f8 !important; color:#243952 !important; font-size:16px !important;
    box-shadow:inset 4px 4px 8px #d7dfeb,inset -4px -4px 8px #fff !important;
}
.chair-excel-clay input::placeholder,.chair-excel-clay textarea::placeholder { color:#667991 !important; opacity:1; }
.chair-excel-clay input:focus,.chair-excel-clay select:focus,.chair-excel-clay textarea:focus,
.chair-excel-clay .program-popup-card select:focus { outline:3px solid rgba(66,106,166,.22); outline-offset:1px; border-color:#7895bd !important; }
.chair-excel-clay select option { background:#fff; color:#243952; }
.chair-excel-clay input[readonly] { color:#465d79 !important; }
.chair-excel-clay .lookup-status,.chair-excel-clay .instruction { margin-top:16px; padding:15px 17px; border:1px solid #d5deeb; border-radius:16px; background:#e9eff8; color:#3c526c; font-size:15px; line-height:1.65; box-shadow:var(--clay-inset); }
.chair-excel-clay .instruction strong { color:#243952; }
.chair-excel-clay .lookup-success { background:#e6f2e9; color:#25593c; border-color:#c2ddca; }
.chair-excel-clay .lookup-error { background:#f9e9e8; color:#842f34; border-color:#e8c4c1; }
.chair-excel-clay .lookup-warning { background:#f8f0dc; color:#6e531c; border-color:#e8d8aa; }
.chair-excel-clay .cell-controls { display:flex; align-items:end; gap:12px; flex-wrap:wrap; margin-top:18px; padding:17px; border:1px solid #d5deeb; border-radius:19px; background:#e6ecf5; box-shadow:var(--clay-inset); }
.chair-excel-clay .color-group { color:#344963; font-size:15px; font-weight:750; }
.chair-excel-clay input[type="color"] { width:54px; height:46px; padding:4px; border:1px solid #cdd8e8; border-radius:13px; background:#eaf0f8; box-shadow:5px 5px 10px #cbd4e1,-5px -5px 10px #fff; cursor:pointer; }
.chair-excel-clay .btn,
.chair-excel-clay .merge-class-toggle-btn,
.chair-excel-clay .editor-actions .btn {
    min-height:46px; padding:10px 16px; border-radius:14px; font-size:15px !important; font-weight:800;
    box-shadow:6px 6px 12px #cbd4e1,-6px -6px 12px #fff,inset 1px 1px 0 rgba(255,255,255,.45) !important;
    transition:transform .18s ease,box-shadow .18s ease,filter .18s ease;
}
.chair-excel-clay .btn:hover,.chair-excel-clay .merge-class-toggle-btn:hover { transform:translateY(-2px); filter:brightness(1.025); box-shadow:8px 8px 15px #c6cfdd,-7px -7px 14px #fff !important; }
.chair-excel-clay .btn:active,.chair-excel-clay .merge-class-toggle-btn:active { transform:translateY(0); box-shadow:var(--clay-inset) !important; }
.chair-excel-clay .btn:focus-visible,.chair-excel-clay button:focus-visible,.chair-excel-clay a:focus-visible { outline:3px solid rgba(66,106,166,.32); outline-offset:3px; }
.chair-excel-clay .btn-green { border:1px solid #3c629c !important; background:#426aa6 !important; color:#fff !important; }
.chair-excel-clay .btn-green:hover { background:#34588f !important; color:#fff !important; }
.chair-excel-clay .btn-yellow { border:1px solid #dfc98f !important; background:#f2e5c5 !important; color:#624b1d !important; }
.chair-excel-clay .btn-red { border:1px solid #e2bfc1 !important; background:#f2dfe2 !important; color:#78383e !important; }
.chair-excel-clay .btn-gray { border:1px solid #d1dbe8 !important; background:#e8edf5 !important; color:#354d68 !important; }
.chair-excel-clay .merge-class-toggle-btn { border:1px solid #cbd8eb !important; background:#e5ebf6 !important; color:#345986 !important; cursor:pointer; }
.chair-excel-clay .merge-section { margin-top:21px; }
.chair-excel-clay .merge-title { font-size:22px !important; }
.chair-excel-clay .merge-search-row { gap:12px; }
.chair-excel-clay .table-wrapper { position:relative; margin-top:20px; overflow:auto; border:1px solid #d0d9e7; border-radius:22px; background:#e7edf6; box-shadow:10px 10px 21px #cbd4e1,-9px -9px 20px #fff,inset 1px 1px 0 rgba(255,255,255,.9); scrollbar-color:#aebbd0 #e7edf6; }
.chair-excel-clay .table-wrapper::-webkit-scrollbar-track { background:#e5ebf4; }
.chair-excel-clay .table-wrapper::-webkit-scrollbar-thumb { background:#aebbd0; border:2px solid #e5ebf4; border-radius:99px; }
.chair-excel-clay #scheduleTable { width:100%; min-width:1300px; border-collapse:separate; border-spacing:4px 5px; table-layout:fixed; color:#243952; }
.chair-excel-clay #scheduleTable th,.chair-excel-clay #scheduleTable td { border:0 !important; }
.chair-excel-clay #scheduleTable thead th { position:sticky; top:0; z-index:5; height:54px; padding:10px 8px; border:1px solid #d2dced !important; border-radius:14px; background:linear-gradient(145deg,#e4ebf7,#dce5f3) !important; color:#2d4c73 !important; text-align:center; font-size:14px !important; font-weight:850; letter-spacing:.045em; box-shadow:5px 5px 10px #cbd4e1,-5px -5px 10px #fff,inset 1px 1px 0 #fff; }
.chair-excel-clay #scheduleTable thead th:first-child,.chair-excel-clay #scheduleTable thead th.time-header { width:120px; }
.chair-excel-clay #scheduleTable tbody td { border-radius:13px; }
.chair-excel-clay .time-cell { min-height:82px; height:82px; padding:9px 7px; border:1px solid #d5deeb !important; background:#e8edf5 !important; color:#344963 !important; text-align:center; vertical-align:middle; font-size:14px !important; font-weight:800; line-height:1.45; box-shadow:inset 3px 3px 7px #d9e0eb,inset -3px -3px 7px #fff; }
.chair-excel-clay .time-cell:hover,.chair-excel-clay .time-cell:focus { background:#eef3fa !important; color:#223d5c !important; }
.chair-excel-clay .schedule-cell { position:relative; height:112px; padding:9px 8px; border:1px solid rgba(187,199,217,.78) !important; color:#1d2b40 !important; text-align:center; vertical-align:middle; overflow:hidden; box-shadow:5px 5px 10px rgba(176,188,205,.58),-4px -4px 10px rgba(255,255,255,.92),inset 1px 1px 0 rgba(255,255,255,.65); transition:box-shadow .16s ease,transform .16s ease; }
.chair-excel-clay .schedule-cell[data-color="#ffffff" i],.chair-excel-clay .schedule-cell[data-color="#fff" i] { background:#f0f3f9 !important; }
.chair-excel-clay .schedule-cell[data-color="#ffffff" i][data-course]:not([data-course=""]),.chair-excel-clay .schedule-cell[data-color="#fff" i][data-course]:not([data-course=""]) { background:#f7f8fc !important; }
.chair-excel-clay .schedule-cell:not(.locked-cell):not(.reserved-cell):hover { box-shadow:inset 0 0 0 3px rgba(66,106,166,.48),5px 5px 10px rgba(176,188,205,.58),-4px -4px 10px #fff; }
.chair-excel-clay .schedule-cell.selected-cell,.chair-excel-clay .schedule-cell.selected { outline:3px solid #426aa6 !important; outline-offset:-3px; box-shadow:inset 0 0 0 3px rgba(66,106,166,.17),5px 5px 10px rgba(176,188,205,.58),-4px -4px 10px #fff !important; }
.chair-excel-clay .cell-course { color:#1c304a !important; font-size:15px !important; font-weight:850; line-height:1.3; overflow-wrap:anywhere; }
.chair-excel-clay .cell-subject { margin-top:4px; color:#2f455f !important; font-size:14px !important; font-weight:700; line-height:1.35; overflow-wrap:anywhere; }
.chair-excel-clay .cell-instructor,.chair-excel-clay .cell-year,.chair-excel-clay .cell-major,.chair-excel-clay .cell-program { margin-top:4px; color:#405773 !important; font-size:13px !important; font-weight:600; line-height:1.35; overflow-wrap:anywhere; }
.chair-excel-clay .cell-subject-type { display:inline-block; margin-top:5px; padding:4px 7px; border:1px solid #cdd9ea; border-radius:999px; background:#e5ecf7 !important; color:#345986 !important; font-size:12px !important; font-weight:800; line-height:1.3; }
.chair-excel-clay .empty-cell { display:inline-block; padding:5px 10px; border:1px dashed #bbc8dc; border-radius:999px; background:#e8edf5; color:#536984 !important; font-size:13px !important; font-style:normal; font-weight:700; }
.chair-excel-clay .locked-cell { background:repeating-linear-gradient(135deg,#e5e9f0,#e5e9f0 8px,#dce2ec 8px,#dce2ec 16px) !important; color:#485b73 !important; cursor:not-allowed !important; opacity:1 !important; }
.chair-excel-clay .locked-label { display:inline-block; margin-top:5px; padding:4px 7px; border-radius:999px; background:#dce4ef; color:#40536c; font-size:11px; font-weight:850; }
.chair-excel-clay .schedule-cell.reserved-cell { background:#f5e9ca !important; border:2px solid #c7a966 !important; color:#594618 !important; cursor:not-allowed !important; box-shadow:5px 5px 10px rgba(176,188,205,.58),-4px -4px 10px #fff,inset 1px 1px 0 rgba(255,255,255,.75) !important; }
.chair-excel-clay .reserved-cell .cell-course,.chair-excel-clay .reserved-cell .cell-subject,.chair-excel-clay .reserved-cell .cell-instructor,.chair-excel-clay .reserved-cell .reservation-cell-user,.chair-excel-clay .reserved-cell .reservation-cell-time,.chair-excel-clay .reserved-cell .reservation-cell-note { color:#594618 !important; }
.chair-excel-clay .reservation-cell-label { display:inline-block; margin-top:6px; padding:4px 8px; border-radius:999px; background:#957336; color:#fff; font-size:11px; font-weight:850; }
.chair-excel-clay .reservation-cell-user { margin-top:4px; color:#594618 !important; font-size:13px; font-weight:800; }
.chair-excel-clay .reservation-cell-time { margin-top:3px; color:#594618 !important; font-size:12px; font-weight:750; }
.chair-excel-clay .reservation-cell-note { margin-top:4px; color:#594618 !important; font-size:12px; line-height:1.35; }
.chair-excel-clay .break-row td,.chair-excel-clay .add-time-before-break-row td { padding:9px; border:1px dashed #c1cbda !important; border-radius:10px; background:#e5ebf4 !important; color:#405773 !important; text-align:center; font-size:13px; font-weight:750; }
.chair-excel-clay .add-time-before-break-row .btn { display:inline-flex; align-items:center; justify-content:center; margin:0 auto; }
.chair-excel-clay .table-footer { padding-top:15px; }
.chair-excel-clay .editor-actions { display:flex; justify-content:space-between; align-items:center; gap:14px; margin-top:20px; padding:15px; border:1px solid #d5deeb; border-radius:18px; background:#e8edf6; box-shadow:var(--clay-inset); }
.chair-excel-clay .editor-actions .btn { min-height:48px; padding:11px 18px; }
.chair-excel-clay .action-group { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.chair-excel-clay .message { margin:0 0 16px; padding:14px 17px; border:1px solid #d4deea; border-radius:15px; font-size:15px; line-height:1.55; box-shadow:var(--clay-raised); }
.chair-excel-clay .message-success { background:#e5f2e9 !important; color:#24583c !important; border-color:#c4decc !important; }
.chair-excel-clay .message-error { background:#f8e9e8 !important; color:#843235 !important; border-color:#e5c5c3 !important; }
.chair-excel-clay .program-popup-overlay,.chair-excel-clay .schedule-conflict-overlay { background:rgba(38,53,76,.48) !important; backdrop-filter:blur(5px); }
.chair-excel-clay .program-popup-card,.chair-excel-clay .schedule-conflict-modal { border:1px solid #d3ddec !important; border-radius:24px !important; background:linear-gradient(145deg,#f1f5fc,#e5ebf5) !important; color:#243952 !important; box-shadow:12px 12px 24px rgba(34,48,69,.2),-6px -6px 18px rgba(255,255,255,.65),inset 1px 1px 0 #fff !important; }
.chair-excel-clay .program-popup-card h3,.chair-excel-clay .schedule-conflict-modal h2 { color:#1d2b40 !important; font-size:24px !important; line-height:1.3; }
.chair-excel-clay .program-popup-card p,.chair-excel-clay .schedule-conflict-message,.chair-excel-clay .schedule-conflict-warning { color:#465d79 !important; font-size:16px !important; line-height:1.65; }
.chair-excel-clay .program-popup-close { width:40px; height:40px; border:1px solid #d4deeb; border-radius:13px; background:#e8eef7; color:#34516f !important; box-shadow:4px 4px 9px #cbd4e1,-4px -4px 9px #fff; }
.chair-excel-clay .program-popup-close:hover { background:#edf2f9; color:#213f65 !important; }
.chair-excel-clay .program-popup-actions { gap:11px; }
.chair-excel-clay .program-popup-actions button { min-width:100px; min-height:46px; height:auto; padding:10px 16px; border-radius:13px; font-size:15px; font-weight:800; box-shadow:5px 5px 11px #cbd4e1,-5px -5px 11px #fff; }
.chair-excel-clay .program-popup-cancel { border:1px solid #d0dbe9 !important; background:#e9eef6 !important; color:#354d68 !important; }
.chair-excel-clay .program-popup-confirm { border:1px solid #3c629c !important; background:#426aa6 !important; color:#fff !important; }
.chair-excel-clay .schedule-conflict-icon { color:#906f2f; }
@media(max-width:1100px) {
    .chair-excel-clay .program-header { grid-template-columns:1fr; }
    .chair-excel-clay .program-meta { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .chair-excel-clay .form-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
}
@media(max-width:700px) {
    .chair-excel-clay .page { padding:13px 12px 28px; }
    .chair-excel-clay .program-header { gap:17px; padding:21px 17px; }
    .chair-excel-clay .schedule-information { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); width:100%; }
    .chair-excel-clay .schedule-info-field { min-width:0; }
    .chair-excel-clay .program-meta,.chair-excel-clay .form-grid { grid-template-columns:1fr; }
    .chair-excel-clay .meta-item { min-height:unset; }
    .chair-excel-clay .editor-panel,.chair-excel-clay .merge-section { padding:18px 14px; }
    .chair-excel-clay .cell-controls { align-items:stretch; }
    .chair-excel-clay .editor-actions { align-items:stretch; flex-direction:column; }
    .chair-excel-clay .editor-actions .btn { width:100%; }
    .chair-excel-clay .action-group { width:100%; }
}
@media(max-width:420px) {
    .chair-excel-clay .schedule-information { grid-template-columns:1fr; }
    .chair-excel-clay .program-title { font-size:30px !important; }
    .chair-excel-clay .program-popup-actions { align-items:stretch; flex-direction:column-reverse; }
    .chair-excel-clay .program-popup-actions button { width:100%; }
}
@media(prefers-reduced-motion:reduce) {
    .chair-excel-clay *, .chair-excel-clay *::before, .chair-excel-clay *::after { scroll-behavior:auto !important; transition-duration:.01ms !important; animation-duration:.01ms !important; }
}
</style>
</head>


<body class="chair-excel-clay">


<div class="page">


    <!-- =========================================================
         BACK
    ========================================================== -->

    <div class="top-bar">

        <a
            href="{{ route(
                'chair.setschedule',
                [
                    'department' =>
                        $room->department_id
                ]
            ) }}"
            class="back-link"
        >
            ← Back to Set Schedule
        </a>

    </div>


    <!-- =========================================================
         MESSAGES
    ========================================================== -->

    @if(session('success'))

        <div class="message message-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="message message-error">
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="message message-error">

            <strong>
                Please fix the following:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="program-header">

        <div><div class="program-title">
            CLASS PROGRAM
        </div><div style="margin-top:6px;color:#2e7d32;font-size:12px;font-weight:700;">Active Term: {{ $activeTerm->academic_year ?? ($academicYear ?? '') }} • {{ $activeTerm->semester ?? ($semester ?? '') }}</div></div>

       <div class="schedule-information">

    <div class="schedule-info-field">
        <label for="semester">
            Semester
        </label>

        <input
            type="text"
            id="semester"
            value="{{ $activeTerm->semester ?? ($semester ?? '') }}"
            readonly
        >
    </div>


    <div class="schedule-info-field">
        <label for="academic_year">
            Academic Year
        </label>

        <input
            type="text"
            id="academic_year"
            value="{{ $activeTerm->academic_year ?? ($academicYear ?? '') }}"
            readonly
        >
    </div>

</div>


        @php

            $isEditingDraft =
                $isEditingDraft ?? false;

        @endphp


        @if($isEditingDraft)

            <div class="draft-mode">
                EDITING SAVED DRAFT
            </div>

        @endif


        <div class="program-meta">

            <div class="meta-item">

                <strong>
                    Department:
                </strong>

                {{ $room->department->name ?? 'N/A' }}

            </div>


            <div class="meta-item">

                <strong>
                    Building:
                </strong>

                {{ $room->building->name ?? $room->building ?? 'N/A' }}

            </div>


            <div class="meta-item">

                <strong>
                    Room:
                </strong>

                {{ $room->room_name }}

            </div>


            <div class="meta-item">

                <strong>
                    Scheduling Department:
                </strong>

                {{ auth()->user()->department->name ?? 'N/A' }}

            </div>

        </div>

    </div>


    <!-- =========================================================
         FACULTY SUBJECT DATA
    ========================================================== -->

    @php

        $facultySubjectsData =
            $facultySubjects
                ->map(function ($item) {

                   return [
    'id' => $item->id,
    'course_code' => $item->course_code,
    'subject' => $item->subject,
    'year_level' => $item->year_level,
    'major' => $item->major,
    'program' => $item->program,
    'subject_type' => $item->subject_type ?: (preg_match('/\.1\s*$/', trim($item->course_code)) ? 'Lab' : 'Lec'),

    'faculty_id' => $item->faculty
        ? $item->faculty->id
        : null,

    'instructor' => $item->faculty
        ? $item->faculty->name
        : '',
];
                })
                ->values()
                ->toArray();

    @endphp


    <!-- =========================================================
         FORM
    ========================================================== -->

    <form
        method="POST"
        action="{{ route('chair.save.schedule') }}"
        id="scheduleForm"
    >

        @csrf


        <input
            type="hidden"
            name="room_id"
            value="{{ $room->id }}"
        >


        <!-- =====================================================
             EDITOR
        ====================================================== -->

        <div class="editor-panel">


            <div class="editor-title">
                Schedule Entry
            </div>


            <div class="editor-subtitle">

                Select a timetable cell and enter a course code.
                The assigned subject, faculty member and year level
                will load automatically from Faculty Setup.

            </div>


            <div class="form-grid">


                <!-- COURSE CODE -->

                <div class="form-group">

                    <label>
                        Course Code
                    </label>

                    <input
                        type="text"
                        id="course_code"
                        list="courseCodeList"
                        placeholder="Example: IT 111"
                        autocomplete="off"
                    >


                    <datalist id="courseCodeList">

                        @foreach(
                            $facultySubjects
                                ->unique('course_code')
                            as $facultySubject
                        )

                            <option
                                value="{{ $facultySubject->course_code }}"
                            ></option>

                        @endforeach

                    </datalist>

                    <select id="subjectMatchSelector" hidden style="width:100%;margin-top:10px;padding:11px;border:1px solid #b7c8d9;border-radius:8px;background:#fff;color:#20364b;">
                        <option value="">Choose the matching year level and faculty</option>
                    </select>

                </div>


                <!-- SUBJECT -->

                <div class="form-group">

                    <label>
                        Course Description
                    </label>

                    <input
                        type="text"
                        id="subject"
                        readonly
                        placeholder="Automatic"
                    >

                </div>


                <!-- YEAR LEVEL -->

                <div class="form-group">

                    <label>
                        Year Level
                    </label>

                    <input
                        type="text"
                        id="year_level"
                        readonly
                        placeholder="Automatic"
                    >

                </div>

<!-- SUBJECT TYPE -->
<div class="form-group">
    <label>
        Subject Type
    </label>

    <input
        type="text"
        id="subject_type"
        name="subject_type"
        readonly
        placeholder="Automatic"
    >
</div>
                <!-- FACULTY -->

                <div class="form-group">

                    <label>
                        Faculty
                    </label>

                    <input
                        type="text"
                        id="instructor"
                        readonly
                        placeholder="Automatic"
                    >

                </div>


               <!-- MAJOR -->

<div class="form-group">

    <label>
        Major
    </label>

    <input
        type="text"
        id="major"
        readonly
        placeholder="Automatic"
    >

</div>


<!-- PROGRAM -->
<div class="form-group">
    <label for="program">
        Program
    </label>

    <input
        type="text"
        id="program"
        name="program"
        readonly
        placeholder="Automatic"
    >
</div>

<button type="button" id="mergeClassToggleBtn" class="merge-class-toggle-btn">
    Merge Class (optional)
</button>

            </div>


            <div
                id="lookupStatus"
                class="lookup-status"
            ></div>


            <!-- CELL CONTROLS -->

            <div class="cell-controls">

                <div class="color-group">

                    <label>
                        Cell Color
                    </label>

                    <input
                        type="color"
                        id="cellColor"
                        value="#ffffff"
                    >

                </div>


                <button
                    type="button"
                    class="btn btn-green"
                    id="saveCellButton"
                >
                    Save Cell
                </button>


                <button
                    type="button"
                    class="btn btn-red"
                    id="clearCellButton"
                >
                    Clear Cell
                </button>

            </div>

<!-- MERGE CLASS SECTION -->
<div
    class="merge-section"
    id="mergeExistingClass"
    style="display: none;"
>

    <div class="merge-title">
        Merge Existing Class
    </div>

    <p class="merge-description">
        Search for an existing class from another department,
        or browse the department schedules manually.
    </p>


    <!-- SEARCH BY COURSE CODE -->

    <div class="merge-search-box">

        <label for="mergeCourseCode">
            Search by Course Code
        </label>

        <div class="merge-search-row">

            <input
                type="text"
                id="mergeCourseCode"
                placeholder="Example: IT101"
            >

            <button
                type="button"
                id="searchMergeBtn"
                class="btn btn-green"
            >
                Search
            </button>

        </div>

    </div>


    <div class="merge-or">
        OR
    </div>


    <!-- MANUAL BROWSE -->

    <div class="merge-browse-box">

        <div class="merge-browse-title">
            Browse Departments
        </div>

        <div
            id="mergeDepartments"
            class="merge-departments"
        >
            Loading departments...
        </div>

    </div>


    <!-- SEARCH / BROWSE STATUS -->

    <div
        id="mergeSearchStatus"
        class="merge-search-status"
    ></div>


    <!-- RESULTS -->

    <div
        id="mergeResults"
        class="merge-results"
        style="display:none;"
    ></div>


    <!-- MANUAL SCHEDULE VIEW -->

    <div
        id="mergeScheduleView"
        class="merge-schedule-view"
        style="display:none;"
    ></div>

    <!-- PROGRAM SELECTION POPUP -->
<div id="mergeProgramSelection" class="program-popup" style="display:none;">

    <div class="program-popup-overlay"></div>

    <div class="program-popup-card">

        <!-- X BUTTON -->
        <button
            type="button"
            id="closeMergeProgramPopup"
            class="program-popup-close"
        >
            &times;
        </button>

        <h3>Select Program</h3>

        <p>
            Choose the program you want to merge.
        </p>

        <label for="mergeProgramSelect">
            Program
        </label>

        <select id="mergeProgramSelect">
            <option value="">
                Select Program
            </option>
        </select>

        <div class="program-popup-actions">

            <button
                type="button"
                id="cancelMergeProgram"
                class="program-popup-cancel"
            >
                Cancel
            </button>

            <button
                type="button"
                id="confirmMergeProgram"
                class="program-popup-confirm"
            >
                Continue
            </button>

        </div>

    </div>

</div>

</div>

            <div class="instruction">

                <strong>
                    How to use:
                </strong>

                Select an empty or editable timetable cell,
                enter the Course Code, then click
                <strong>Save Cell</strong>.

                <br><br>

                Subject, Faculty and Year Level are filled automatically.

                <br><br>

                <strong>
                    Time:
                </strong>

                Manually edit the TIME column.

                <br><br>

                <strong>
                    🔒 Locked:
                </strong>

                Existing schedules owned by another department
                cannot be changed.

            </div>


        </div>


        <!-- =====================================================
             TABLE
        ====================================================== -->

        <div class="table-wrapper">

            <table id="scheduleTable">

             <thead>
    <tr>
        <th class="time-header">TIME</th>

        <th data-day="MON">MON</th>
        <th data-day="WED">WED</th>
        <th data-day="FRI">FRI</th>

        <th class="time-header">TIME</th>

        <th data-day="TUE">TUE</th>
        <th data-day="THU">THU</th>
        <th data-day="SAT">SAT</th>
    </tr>
</thead>

                <tbody id="scheduleBody">

                    @php

    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR DEPARTMENT
    |--------------------------------------------------------------------------
    */

    $chairDepartmentId = auth()->user()->department_id;

    /*
    |--------------------------------------------------------------------------
    | SAFE COLLECTIONS
    |--------------------------------------------------------------------------
    */

    $drafts = $drafts ?? collect();
    $schedules = $schedules ?? collect();
    $reservations = $reservations ?? collect();

    /*
    |--------------------------------------------------------------------------
    | RESERVATION TIME HELPER
    |--------------------------------------------------------------------------
    */

    $timeToMinutes = function ($time) {
        $time = trim((string) $time);

        if (!preg_match('/^(\d{1,2}):(\d{2})$/', $time, $matches)) {
            return null;
        }

        $hour = (int) $matches[1];
        $minute = (int) $matches[2];

        if ($hour >= 1 && $hour <= 5) {
            $hour += 12;
        }

        return ($hour * 60) + $minute;
    };

    /*
    |--------------------------------------------------------------------------
    | SAFE DRAFT MODE
    |--------------------------------------------------------------------------
    */

    $isEditingDraft = $isEditingDraft ?? false;

    /*
    |--------------------------------------------------------------------------
    | SELECT SCHEDULE SOURCE
    |--------------------------------------------------------------------------
    */

    $scheduleRows = $isEditingDraft
        ? $drafts
        : $schedules;

    /*
    |--------------------------------------------------------------------------
    | DEFAULT TIME ROWS
    |--------------------------------------------------------------------------
    |
    | LEFT  = MWF
    | RIGHT = TTHS
    |
    */

    $defaultTimes = [
        '8:00-9:00',
        '9:00-10:00',
        '10:00-11:00',
        '11:00-12:00',
        '1:00-2:00',
        '2:00-3:00',
        '3:00-4:00',
        '4:00-5:00',
    ];

    /*
    |--------------------------------------------------------------------------
    | SCHEDULE MAP
    |--------------------------------------------------------------------------
    |
    | KEY = DAY|TIME
    |
    */

    $scheduleMap = [];

    foreach ($scheduleRows as $row) {

        $key =
            strtoupper(trim($row->day))
            . '|'
            . trim($row->time);

        $scheduleMap[$key] = $row;
    }

    /*
    |--------------------------------------------------------------------------
    | LEFT / RIGHT TIME SLOTS
    |--------------------------------------------------------------------------
    |
    | Both sides start with the same default times.
    | Existing custom times are also added to the
    | appropriate side.
    |
    */

   $leftTimes = collect($defaultTimes);
$rightTimes = collect($defaultTimes);

/*
|--------------------------------------------------------------------------
| Replace default row time with saved edited time
|--------------------------------------------------------------------------
| row_position identifies the visual timetable row.
| This prevents an edited time from becoming a second row.
*/

foreach ($scheduleRows as $row) {
    $existingTime = trim($row->time);

    if ($existingTime === '') {
        continue;
    }

    $position = $row->row_position;

    if ($position === null) {
        continue;
    }

    $position = (int) $position;

    $day = strtoupper(trim($row->day));

    if (
        in_array($day, ['MON', 'WED', 'FRI'])
        &&
        isset($leftTimes[$position])
    ) {
        $leftTimes[$position] = $existingTime;
    }

    if (
        in_array($day, ['TUE', 'THU', 'SAT'])
        &&
        isset($rightTimes[$position])
    ) {
        $rightTimes[$position] = $existingTime;
    }
}
/*
|--------------------------------------------------------------------------
| SORT TIME SLOTS
|--------------------------------------------------------------------------
|
| This prevents custom times from always appearing
| at the bottom.
|
*/

$sortTime = function ($time) {
    $start = trim(explode('-', $time)[0]);

    if (!preg_match('/^(\d{1,2}):(\d{2})$/', $start, $matches)) {
        return 9999;
    }

    $hour = (int) $matches[1];
    $minute = (int) $matches[2];

    // Treat 1:00-5:00 as afternoon times
    if ($hour >= 1 && $hour <= 5) {
        $hour += 12;
    }

    return ($hour * 60) + $minute;
};

$leftTimes = $leftTimes
    ->sortBy($sortTime)
    ->values();

$rightTimes = $rightTimes
    ->sortBy($sortTime)
    ->values();

    /*
    |--------------------------------------------------------------------------
    | MAKE ROW COUNT THE SAME
    |--------------------------------------------------------------------------
    */

    $rowCount = max(
        $leftTimes->count(),
        $rightTimes->count()
    );

@endphp


@for($i = 0; $i < $rowCount; $i++)

    @php

        $leftTime =
            $leftTimes[$i] ?? '';

        $rightTime =
            $rightTimes[$i] ?? '';

    @endphp


    {{-- =========================================================
         NOON BREAK
         ========================================================= --}}

  @if($i === 4)
    <tr class="add-time-before-break-row">
        <td colspan="8">
            <button
                type="button"
                class="btn btn-gray"
                id="addRowBeforeBreakButton"
            >
                + Add Time Row 
            </button>
        </td>
    </tr>

    <tr class="break-row">
        <td colspan="8">
            N O O N
            &nbsp;&nbsp;
            B R E A K
        </td>
    </tr>
@endif


    <tr>

        {{-- =====================================================
             LEFT TIME — MWF
             ===================================================== --}}

       <td data-side="left" class="time-cell editable-time" contenteditable="true" spellcheck="false">
         {{ $leftTime }}
       </td>


        {{-- =====================================================
             MON
             ===================================================== --}}

        @foreach(['MON', 'WED', 'FRI'] as $day)

            @php

                $key =
                    $day
                    . '|'
                    . trim($leftTime);

                $schedule =
                    $scheduleMap[$key]
                    ?? null;

                $reservation = $reservations->first(function ($item) use ($day, $leftTime, $timeToMinutes) {
                    if (strtoupper(trim((string) $item->day)) !== $day) {
                        return false;
                    }

                    $slotParts = explode('-', trim($leftTime), 2);
                    if (count($slotParts) !== 2) {
                        return false;
                    }

                    $slotStart = $timeToMinutes($slotParts[0]);
                    $slotEnd = $timeToMinutes($slotParts[1]);
                    $reservationStart = $timeToMinutes(\Carbon\Carbon::parse($item->start_time)->format('H:i'));
                    $reservationEnd = $timeToMinutes(\Carbon\Carbon::parse($item->end_time)->format('H:i'));

                    if ($slotStart === null || $slotEnd === null || $reservationStart === null || $reservationEnd === null) {
                        return false;
                    }

                    return $slotStart < $reservationEnd && $slotEnd > $reservationStart;
                });

                $isLocked =
                    (bool) $reservation
                    ||
                    (
                        $schedule
                        &&
                        isset($schedule->department_id)
                        &&
                        (int) $schedule->department_id
                        !==
                        (int) $chairDepartmentId
                    );

            @endphp


            <td
                data-side="left"
                class="schedule-cell {{ $isLocked ? 'locked-cell' : '' }} {{ $reservation ? 'reserved-cell' : '' }}"

                data-schedule-id="{{ $schedule ? $schedule->id : '' }}"

                data-day="{{ $day }}"

                data-time="{{ $leftTime }}"

                data-row-position="{{ $schedule ? $schedule->row_position : '' }}"

                data-course="{{ $schedule ? $schedule->course_code : '' }}"

                data-subject="{{ $schedule ? $schedule->subject : '' }}"

                data-year="{{ $schedule ? ($schedule->year_level ?? '') : '' }}"

                data-major="{{ $schedule ? ($schedule->major ?? '') : '' }}"

                data-program="{{ $schedule ? ($schedule->program ?? '') : '' }}"

                data-instructor="{{ $schedule ? $schedule->instructor : '' }}"

                data-faculty-id="{{ $schedule ? ($schedule->faculty_id ?? '') : '' }}"
                data-faculty-subject-id="{{ $schedule ? ($schedule->faculty_subject_id ?? '') : '' }}"

                data-department-id="{{ $schedule ? ($schedule->department_id ?? '') : '' }}"

                data-color="{{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }}"

                data-locked="{{ $isLocked ? '1' : '0' }}"

                data-reservation-id="{{ $reservation ? $reservation->id : '' }}"

                data-subject-type="{{ $schedule ? ($schedule->subject_type ?? '') : '' }}"

                style="
                    background-color:
                    {{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }};
                "
            >

                @if($schedule)

                    <div class="cell-course">
                        {{ $schedule->course_code }}
                    </div>

                    <div class="cell-subject">
                        {{ $schedule->subject }}
                    </div>

                    <div class="cell-instructor">
                        {{ $schedule->instructor }}
                    </div>

                    <div class="cell-year">
                        {{ $schedule->year_level ?? '' }}
                    </div>

                    @if($schedule->major)
                        <div class="cell-major">
                            {{ $schedule->major }}
                        </div>
                    @endif

        
    @php
    $programs = collect();

    /*
    |--------------------------------------------------------------------------
    | MAIN PROGRAM
    |--------------------------------------------------------------------------
    */

    if (!empty($schedule->program)) {
        $programs->push(
            $schedule->program
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVED MERGED PROGRAMS
    |--------------------------------------------------------------------------
    |
    | Published Schedule records may have merges.
    | ScheduleDraft records do not necessarily have
    | the merges relationship.
    |
    */

    $scheduleMerges =
        $schedule->merges ?? collect();

    foreach ($scheduleMerges as $merge) {

        if (
            $merge->status === 'approved' &&
            $merge->facultySubject &&
            !empty(
                $merge->facultySubject->program
            )
        ) {

            $programs->push(
                $merge->facultySubject->program
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE DUPLICATES
    |--------------------------------------------------------------------------
    */

  


    $programs = $programs
        ->filter()
        ->unique()
        ->values();
@endphp

@if($programs->count() > 0)
    <div class="cell-program">
        {{ $programs->implode(' / ') }}
    </div>
@endif

                    @if($schedule->subject_type)
                        <div class="cell-subject-type">
                            {{ $schedule->subject_type }}
                        </div>
                    @endif

                    @if($reservation)
                        <div class="reservation-cell-label">
                            {{ $reservation->status === 'Reserved' ? 'TEMPORARY RESERVATION' : 'RESERVATION' }}
                        </div>
                        <div class="reservation-cell-user">
                            {{ $reservation->user?->name ?? 'Faculty' }}
                        </div>
                        <div class="reservation-cell-time">
                            {{ \Carbon\Carbon::parse($reservation->date)->format('M d, Y') }} · {{ $reservation->time }}
                        </div>
                        <div class="reservation-cell-note">
                            This slot is temporarily blocked and cannot be scheduled.
                        </div>
                    @endif

                    @if($isLocked)
                        <div class="locked-label">
                            🔒 LOCKED
                        </div>
                    @endif

                @else

                    <span class="empty-cell">
                        Empty
                    </span>

                @endif

            </td>

        @endforeach


        {{-- =====================================================
             RIGHT TIME — TTHS
             ===================================================== --}}

        <td
            data-side="right"
            class="time-cell editable-time"
            contenteditable="true"
            spellcheck="false"
        >
            {{ $rightTime }}
        </td>


        {{-- =====================================================
             TUE / THU
             ===================================================== --}}

        @foreach(['TUE', 'THU', 'SAT'] as $day)

            @php

                $key =
                    $day
                    . '|'
                    . trim($rightTime);

                $schedule =
                    $scheduleMap[$key]
                    ?? null;

                $reservation = $reservations->first(function ($item) use ($day, $rightTime, $timeToMinutes) {
                    if (strtoupper(trim((string) $item->day)) !== $day) {
                        return false;
                    }

                    $slotParts = explode('-', trim($rightTime), 2);
                    if (count($slotParts) !== 2) {
                        return false;
                    }

                    $slotStart = $timeToMinutes($slotParts[0]);
                    $slotEnd = $timeToMinutes($slotParts[1]);
                    $reservationStart = $timeToMinutes(\Carbon\Carbon::parse($item->start_time)->format('H:i'));
                    $reservationEnd = $timeToMinutes(\Carbon\Carbon::parse($item->end_time)->format('H:i'));

                    if ($slotStart === null || $slotEnd === null || $reservationStart === null || $reservationEnd === null) {
                        return false;
                    }

                    return $slotStart < $reservationEnd && $slotEnd > $reservationStart;
                });

                $isLocked =
                    (bool) $reservation
                    ||
                    (
                        $schedule
                        &&
                        isset($schedule->department_id)
                        &&
                        (int) $schedule->department_id
                        !==
                        (int) $chairDepartmentId
                    );

            @endphp


            <td
                data-side="right"
                class="schedule-cell {{ $isLocked ? 'locked-cell' : '' }} {{ $reservation ? 'reserved-cell' : '' }}"

                data-schedule-id="{{ $schedule ? $schedule->id : '' }}"

                data-day="{{ $day }}"

                data-time="{{ $rightTime }}"

                data-row-position="{{ $schedule ? $schedule->row_position : '' }}"

                data-course="{{ $schedule ? $schedule->course_code : '' }}"

                data-subject="{{ $schedule ? $schedule->subject : '' }}"

                data-year="{{ $schedule ? ($schedule->year_level ?? '') : '' }}"

                data-major="{{ $schedule ? ($schedule->major ?? '') : '' }}"

                data-program="{{ $schedule ? ($schedule->program ?? '') : '' }}"

                data-instructor="{{ $schedule ? $schedule->instructor : '' }}"

                data-faculty-id="{{ $schedule ? ($schedule->faculty_id ?? '') : '' }}"
                data-faculty-subject-id="{{ $schedule ? ($schedule->faculty_subject_id ?? '') : '' }}"

                data-department-id="{{ $schedule ? ($schedule->department_id ?? '') : '' }}"

                data-color="{{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }}"

                data-locked="{{ $isLocked ? '1' : '0' }}"

                data-reservation-id="{{ $reservation ? $reservation->id : '' }}"

                data-subject-type="{{ $schedule ? ($schedule->subject_type ?? '') : '' }}"

                style="
                    background-color:
                    {{ $schedule ? ($schedule->color ?? '#ffffff') : '#ffffff' }};
                "
            >

                @if($schedule)

                    <div class="cell-course">
                        {{ $schedule->course_code }}
                    </div>

                    <div class="cell-subject">
                        {{ $schedule->subject }}
                    </div>

                    <div class="cell-instructor">
                        {{ $schedule->instructor }}
                    </div>

                    <div class="cell-year">
                        {{ $schedule->year_level ?? '' }}
                    </div>

                    @if($schedule->major)
                        <div class="cell-major">
                            {{ $schedule->major }}
                        </div>
                    @endif

                    @if($schedule->program)
                        <div class="cell-program">
                            {{ $schedule->program }}
                        </div>
                    @endif

                    @if($schedule->subject_type)
                        <div class="cell-subject-type">
                            {{ $schedule->subject_type }}
                        </div>
                    @endif

                    @if($reservation)
                        <div class="reservation-cell-label">
                            {{ $reservation->status === 'Reserved' ? 'TEMPORARY RESERVATION' : 'RESERVATION' }}
                        </div>
                        <div class="reservation-cell-user">
                            {{ $reservation->user?->name ?? 'Faculty' }}
                        </div>
                        <div class="reservation-cell-time">
                            {{ \Carbon\Carbon::parse($reservation->date)->format('M d, Y') }} · {{ $reservation->time }}
                        </div>
                        <div class="reservation-cell-note">
                            This slot is temporarily blocked and cannot be scheduled.
                        </div>
                    @endif

                    @if($isLocked)
                        <div class="locked-label">
                            🔒 LOCKED
                        </div>
                    @endif

                @else

                    <span class="empty-cell">
                        Empty
                    </span>

                @endif

            </td>

        @endforeach

    </tr>

@endfor


                </tbody>

            </table>

        </div>


        <!-- =====================================================
             ADD TIME ROW
        ====================================================== -->

        <div class="table-footer">

            <button
                type="button"
                class="btn btn-gray"
                id="addRowButton"
            >
                + Add Time Row
            </button>

        </div>


        <!-- =====================================================
             ACTIONS
        ====================================================== -->

        <div class="editor-actions">


            <div class="action-group">

                <a
                    href="{{ route(
                        'chair.setschedule',
                        [
                            'department' =>
                                $room->department_id
                        ]
                    ) }}"
                    class="btn btn-gray"
                >
                    ← Back to Set Schedule
                </a>

            </div>


            <div class="action-group">

                @if($isEditingDraft)

                    <button
                        type="submit"
                        class="btn btn-yellow"
                        name="save_type"
                        value="draft"
                    >
                        Update Draft
                    </button>

                @else

                    <button
                        type="submit"
                        class="btn btn-yellow"
                        name="save_type"
                        value="draft"
                    >
                        Save as Draft
                    </button>

                @endif


                <button
                    type="submit"
                    class="btn btn-green"
                    name="save_type"
                    value="upload"
                >
                    Upload Schedule
                </button>

            </div>

        </div>


        <!-- HIDDEN INPUTS -->

        <div id="hiddenInputs"></div>


    </form>


</div>

@if(session('schedule_conflict'))

<div
    id="scheduleConflictModal"
    class="schedule-conflict-overlay"
>

    <div class="schedule-conflict-modal">

        <div class="schedule-conflict-icon">
            ⚠
        </div>

        <h2>
            Schedule Conflict
        </h2>

        <p class="schedule-conflict-message">

            This room already has a published
            schedule.

            <br><br>

            Publishing this draft will
            <strong>
                replace the existing schedule
            </strong>
            in this room.

        </p>


        <div class="schedule-conflict-room">

            <strong>
                Room:
            </strong>

            {{ $room->room_name }}

        </div>


        <p class="schedule-conflict-warning">

            Do you want to override the
            existing schedule and publish
            this draft?

        </p>


        <div class="schedule-conflict-actions">

            <button
                type="button"
                id="cancelScheduleConflict"
                class="btn btn-gray"
            >
                Cancel
            </button>


            <form
                method="POST"
                action="{{ route(
                    'chair.drafts.publish',
                    $room->id
                ) }}"
            >

                @csrf

                <input
                    type="hidden"
                    name="override"
                    value="1"
                >

                <button
                    type="submit"
                    class="btn btn-green"
                >
                    Override & Publish
                </button>

            </form>

        </div>

    </div>

</div>

@endif
<script>

    /*
    |--------------------------------------------------------------------------
    | FACULTY SUBJECT DATA
    |--------------------------------------------------------------------------
    */

    const facultySubjects =
        @json($facultySubjectsData);


    /*
    |--------------------------------------------------------------------------
    | CURRENT CHAIR DEPARTMENT
    |--------------------------------------------------------------------------
    */

    const chairDepartmentId =
        @json(auth()->user()->department_id);

    const isEditingDraft =
    @json($isEditingDraft ?? false);
    /*
    |--------------------------------------------------------------------------
    | SELECTED CELL
    |--------------------------------------------------------------------------
    */

    let selectedCell = null;


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const scheduleForm =
        document.getElementById(
            'scheduleForm'
        );


    const courseCodeInput =
        document.getElementById(
            'course_code'
        );


    const subjectInput =
        document.getElementById(
            'subject'
        );


    const yearLevelInput =
        document.getElementById(
            'year_level'
        );

    const majorInput =
        document.getElementById(
            'major'
        );

    const programInput =
        document.getElementById(
            'program'
        );

    const instructorInput =
        document.getElementById(
            'instructor'
        );


    const colorInput =
        document.getElementById(
            'cellColor'
        );


    const lookupStatus =
        document.getElementById(
            'lookupStatus'
        );

    const subjectTypeInput =
    document.getElementById(
        'subject_type'
    );
    const subjectMatchSelector = document.getElementById('subjectMatchSelector');

    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(
        value
    )
    {

        const div =
            document.createElement(
                'div'
            );


        div.textContent =
            value ?? '';


        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    function showSuccess(
        message
    )
    {

        lookupStatus.textContent =
            message;

        lookupStatus.className =
            'lookup-status lookup-success';

    }


    function showError(
        message
    )
    {

        lookupStatus.textContent =
            message;

        lookupStatus.className =
            'lookup-status lookup-error';

    }


    function showWarning(
        message
    )
    {

        lookupStatus.textContent =
            message;

        lookupStatus.className =
            'lookup-status lookup-warning';

    }


    function clearStatus()
    {

        lookupStatus.textContent =
            '';

        lookupStatus.className =
            'lookup-status';

    }


    /*
    |--------------------------------------------------------------------------
    | FIND COURSE
    |--------------------------------------------------------------------------
    */

    function findCourse(
        code
    )
    {

        const searchCode =
            String(code)
                .trim()
                .toUpperCase();


        if (!searchCode) {

            return [];

        }


        return facultySubjects.filter(
            function(item)
            {

                return String(
                    item.course_code
                )
                .trim()
                .toUpperCase()
                ===
                searchCode;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COURSE CODE LOOKUP
    |--------------------------------------------------------------------------
    */

    courseCodeInput.addEventListener(
        'input',
        function()
        {

            const code =
                this.value
                    .trim()
                    .toUpperCase();


            subjectInput.value =
                '';

            yearLevelInput.value =
                '';

            instructorInput.value =
                '';

                majorInput.value =
    '';

     programInput.value =
    '';

            subjectTypeInput.value = 
                '';

            if (subjectMatchSelector) {
                subjectMatchSelector.hidden = true;
                subjectMatchSelector.replaceChildren(new Option('Choose the matching year level and faculty', ''));
            }

            clearStatus();


            if (!code) {

                return;

            }


            const matches =
                findCourse(code);


            if (
                matches.length === 0
            ) {

                showError(
                    'Course code "' +
                    code +
                    '" was not found in Faculty Setup.'
                );

                return;

            }


            if (matches.length > 1) {
                const yearLevels = [...new Set(matches.map(item => item.year_level))];
                showWarning(yearLevels.length > 1
                    ? 'This course code is used in multiple year levels. Choose the correct year level and faculty below.'
                    : 'This course code is assigned to more than one faculty member. Choose the correct assignment below.');
                subjectMatchSelector.replaceChildren(new Option('Choose the matching year level and faculty', ''));
                matches.forEach(item => {
                    const details = [item.year_level, item.program, item.instructor, item.major ? `Major: ${item.major}` : ''].filter(Boolean);
                    subjectMatchSelector.add(new Option(details.join(' · '), String(item.id)));
                });
                subjectMatchSelector.hidden = false;
                return;
            }

            if (subjectMatchSelector) subjectMatchSelector.hidden = true;
            applyFacultySubject(matches[0]);

        }
    );

    function applyFacultySubject(match) {
            if (!match) return;

// Use the subject type confirmed when the faculty assignment was created.
const automaticSubjectType =
    match.subject_type || (/\.1\s*$/.test(match.course_code || '') ? 'Lab' : 'Lec');

subjectTypeInput.value =
    automaticSubjectType;

            subjectInput.value =
                match.subject;


            yearLevelInput.value =
                match.year_level;


            instructorInput.value =
                match.instructor;

            majorInput.value =
                match.major || '';

            programInput.value =
                match.program || '';

            showSuccess(

                'Course found: ' +
                match.subject +
                ' — ' +
                match.instructor +
                ' — ' +
                match.year_level

            );
    }

    subjectMatchSelector?.addEventListener('change', function () {
        const selected = facultySubjects.find(item => String(item.id) === this.value);
        applyFacultySubject(selected);
    });


    /*
    |--------------------------------------------------------------------------
    | SELECT CELL
    |--------------------------------------------------------------------------
    */

    function selectCell(
        cell
    )
    {

        if (
            !cell.classList.contains(
                'schedule-cell'
            )
        ) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | BLOCK OTHER DEPARTMENT
        |--------------------------------------------------------------------------
        */

        if (
            cell.dataset.locked ===
            '1'
        ) {

            alert(
                'This schedule belongs to another department and is locked.'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE PREVIOUS SELECTION
        |--------------------------------------------------------------------------
        */

        if (selectedCell) {

            selectedCell.classList.remove(
                'selected'
            );

        }


        selectedCell =
            cell;


        selectedCell.classList.add(
            'selected'
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD CELL DATA
        |--------------------------------------------------------------------------
        */

        courseCodeInput.value =
            cell.dataset.course || '';


        subjectInput.value =
            cell.dataset.subject || '';


        yearLevelInput.value =
            cell.dataset.year || '';


        instructorInput.value =
            cell.dataset.instructor || '';

                       majorInput.value =
    cell.dataset.major || '';
    

        programInput.value =
            cell.dataset.program || '';

 
            subjectTypeInput.value =
    cell.dataset.subjectType || '';

        colorInput.value =
            cell.dataset.color ||
            '#ffffff';


        clearStatus();


        /*
        |--------------------------------------------------------------------------
        | COURSE INFORMATION
        |--------------------------------------------------------------------------
        */

        if (
            courseCodeInput.value
        ) {

            const matches =
                findCourse(
                    courseCodeInput.value
                );


            if (
                matches.length === 1
            ) {

                showSuccess(

                    matches[0].subject +
                    ' — ' +
                    matches[0].instructor +
                    ' — ' +
                    matches[0].year_level

                );

            }

        }


        courseCodeInput.focus();

    }


    /*
    |--------------------------------------------------------------------------
    | ATTACH EVENTS
    |--------------------------------------------------------------------------
    */

   function attachCellEvents()
{

    document
        .querySelectorAll(
            '#scheduleTable .schedule-cell'
        )
        .forEach(
            function(cell)
            {

                cell.onclick =
                    function()
                    {

                        // Do nothing if the cell has a faculty reservation
                        if (
                            this.classList.contains(
                                'reserved-cell'
                            )
                        ) {
                            alert(
                                'This timetable cell is blocked by a faculty room reservation and cannot be scheduled.'
                            );
                            return;
                        }

                        // Do nothing if the cell is locked
                        if (
                            this.classList.contains(
                                'locked-cell'
                            )
                        ) {
                            return;
                        }

                        // Toggle selection
                        this.classList.toggle(
                            'selected-cell'
                        );

                    };

            }
        );

}



    /*
    |--------------------------------------------------------------------------
    | HORIZONTAL SAME-SCHEDULE MERGE
    |--------------------------------------------------------------------------
    */

    function scheduleMergeSignature(cell)
    {
        const fields = [
            'course',
            'subject',
            'year',
            'major',
            'program',
            'instructor',
            'facultyId',
            'departmentId',
            'color',
            'subjectType'
        ];

        return fields.map(function(field) {
            return String(cell.dataset[field] || '');
        }).join('||');
    }

    function getCellForDay(row, day)
    {
        return Array.from(
            row.querySelectorAll('.schedule-cell')
        ).find(function(cell) {
            return (
                cell.dataset.day === day &&
                !cell.dataset.mergedDays
            );
        }) || null;
    }

    function mergeDayGroup(row, days)
    {
        let i = 0;

        while (i < days.length) {

            const firstCell = getCellForDay(row, days[i]);

            if (!firstCell) {
                i++;
                continue;
            }

            if (!String(firstCell.dataset.course || '').trim()) {
                i++;
                continue;
            }

            const signature = scheduleMergeSignature(firstCell);
            let end = i + 1;

            while (end < days.length) {

                const nextCell = getCellForDay(row, days[end]);

                if (!nextCell) {
                    break;
                }

                if (
                    !String(nextCell.dataset.course || '').trim() ||
                    scheduleMergeSignature(nextCell) !== signature
                ) {
                    break;
                }

                end++;
            }

            const runLength = end - i;

            if (runLength >= 2) {

                const runDays = days.slice(i, end);
                const scheduleIds = {};

                runDays.forEach(function(day) {
                    const cell = getCellForDay(row, day);
                    scheduleIds[day] =
                        cell?.dataset.scheduleId || '';
                });

                firstCell.dataset.mergedDays =
                    runDays.join(',');

                firstCell.dataset.scheduleIds =
                    JSON.stringify(scheduleIds);

                firstCell.classList.add(
                    'merged-schedule-cell'
                );

                firstCell.colSpan = runLength;

                for (
                    let removeIndex = i + 1;
                    removeIndex < end;
                    removeIndex++
                ) {
                    const cellToRemove =
                        getCellForDay(row, days[removeIndex]);

                    if (cellToRemove) {
                        cellToRemove.remove();
                    }
                }
            }

            i = end;
        }
    }

    function mergeHorizontalSchedules()
    {
        document
            .querySelectorAll(
                '#scheduleTable tbody tr:not(.break-row):not(.add-time-before-break-row)'
            )
            .forEach(function(row) {

                mergeDayGroup(
                    row,
                    ['MON', 'WED', 'FRI']
                );

                mergeDayGroup(
                    row,
                    ['TUE', 'THU', 'SAT']
                );
            });
    }

    function unmergeCell(cell)
    {
        const mergedDays = String(
            cell.dataset.mergedDays || ''
        )
        .split(',')
        .map(function(day) {
            return day.trim();
        })
        .filter(Boolean);

        if (mergedDays.length <= 1) {
            return;
        }

        const row = cell.parentElement;
        const side = cell.dataset.side ||
            (['MON', 'WED', 'FRI'].includes(mergedDays[0])
                ? 'left'
                : 'right');

        cell.removeAttribute('colspan');
        cell.classList.remove('merged-schedule-cell');

        cell.dataset.mergedDays = '';
        cell.dataset.scheduleIds = '';
        cell.dataset.scheduleId = '';
        cell.dataset.day = mergedDays[0];

        let insertAfter = cell;

        for (let i = 1; i < mergedDays.length; i++) {

            const clone = cell.cloneNode(true);

            clone.dataset.day = mergedDays[i];
            clone.dataset.side = side;
            clone.dataset.scheduleId = '';
            clone.dataset.mergedDays = '';
            clone.dataset.scheduleIds = '';
            clone.dataset.course = '';
            clone.dataset.subject = '';
            clone.dataset.year = '';
            clone.dataset.major = '';
            clone.dataset.program = '';
            clone.dataset.instructor = '';
            clone.dataset.facultyId = '';
            clone.dataset.facultySubjectId = '';
            clone.dataset.departmentId = chairDepartmentId;
            clone.dataset.color = '#ffffff';
            clone.dataset.subjectType = '';
            clone.dataset.locked = '0';
            clone.dataset.reservationId = '';
            clone.style.backgroundColor = '#ffffff';
            clone.innerHTML =
                '<span class="empty-cell">Empty</span>';

            row.insertBefore(
                clone,
                insertAfter.nextSibling
            );

            insertAfter = clone;
        }

        cell.dataset.course = '';
        cell.dataset.subject = '';
        cell.dataset.year = '';
        cell.dataset.major = '';
        cell.dataset.program = '';
        cell.dataset.instructor = '';
        cell.dataset.facultyId = '';
        cell.dataset.facultySubjectId = '';
        cell.dataset.departmentId = chairDepartmentId;
        cell.dataset.color = '#ffffff';
        cell.dataset.subjectType = '';
        cell.dataset.locked = '0';
        cell.dataset.reservationId = '';
        cell.style.backgroundColor = '#ffffff';
        cell.innerHTML =
            '<span class="empty-cell">Empty</span>';

        attachCellEvents();
    mergeHorizontalSchedules();
    }

    attachCellEvents();

mergeHorizontalSchedules();
    /*
|--------------------------------------------------------------------------
| SAVE CELL
|--------------------------------------------------------------------------
*/

function saveCell()
{
    const selectedCells =
        Array.from(
            document.querySelectorAll(
                '#scheduleTable .schedule-cell.selected-cell'
            )
        );

    /*
    |--------------------------------------------------------------------------
    | CHECK SELECTION
    |--------------------------------------------------------------------------
    */

    if (selectedCells.length === 0) {

        alert(
            'Please select at least one schedule cell first.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | GET INPUTS
    |--------------------------------------------------------------------------
    */

    const course =
        courseCodeInput.value
            .trim()
            .toUpperCase();


    const color =
        colorInput.value;


    /*
    |--------------------------------------------------------------------------
    | COURSE REQUIRED
    |--------------------------------------------------------------------------
    */

    if (!course) {

        alert(
            'Please enter a course code.'
        );

        courseCodeInput.focus();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | FIND COURSE
    |--------------------------------------------------------------------------
    */

    const matches =
        findCourse(course);


    if (
        matches.length === 0
    ) {

        alert(
            'Course code "' +
            course +
            '" was not found. Add the course under Set Faculty first.'
        );

        return;
    }


    const match = matches.length === 1
        ? matches[0]
        : matches.find(item => String(item.id) === String(subjectMatchSelector?.value || ''));

    if (!match) {
        alert('Choose the correct year level and faculty assignment for this course code first.');
        subjectMatchSelector?.focus();
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SUBJECT TYPE
    |--------------------------------------------------------------------------
    */

    const subjectType = match.subject_type || (/\.1\s*$/.test(course) ? 'Lab' : 'Lec');


    /*
    |--------------------------------------------------------------------------
    | CHECK LOCKED CELLS
    |--------------------------------------------------------------------------
    */

    const lockedCells =
        selectedCells.filter(
            cell =>
                cell.dataset.locked === '1'
        );


    if (lockedCells.length > 0) {

        const reservationCells =
            lockedCells.filter(
                cell => cell.dataset.reservationId
            );

        if (reservationCells.length > 0) {
            alert(
                'One or more selected cells have a faculty room reservation. The reservation must end before this slot can be scheduled.'
            );
        } else {
            alert(
                'One or more selected cells belong to another department and are locked.'
            );
        }

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | APPLY COURSE TO ALL SELECTED CELLS
    |--------------------------------------------------------------------------
    */

    selectedCells.forEach(
        function(cell)
        {

            /*
            |------------------------------------------------------------------
            | SAVE DATA TO CELL
            |------------------------------------------------------------------
            */

            cell.dataset.course =
                match.course_code;

            cell.dataset.subject =
                match.subject;

            cell.dataset.year =
                match.year_level;

            cell.dataset.major =
                match.major || '';

            cell.dataset.program =
                match.program || '';

            cell.dataset.instructor =
                match.instructor;

            /*
            | Keep existing schedule ID.
            | New schedules remain blank until saved.
            */

            cell.dataset.scheduleId =
                cell.dataset.scheduleId || '';

            cell.dataset.facultyId =
                match.faculty_id || '';

            cell.dataset.facultySubjectId =
                match.id || '';

            cell.dataset.departmentId =
                chairDepartmentId;

            cell.dataset.color =
                color;

            cell.dataset.subjectType =
                subjectType;

            cell.dataset.locked =
                '0';


            /*
            |------------------------------------------------------------------
            | REMOVE LOCKED STATUS
            |------------------------------------------------------------------
            */

            cell.classList.remove(
                'locked-cell'
            );


            /*
            |------------------------------------------------------------------
            | DISPLAY
            |------------------------------------------------------------------
            */

            let subjectTypeDisplay = '';


            if (subjectType) {

                subjectTypeDisplay =

                    '<div class="cell-subject-type">' +

                    escapeHtml(
                        subjectType
                    ) +

                    '</div>';
            }


            cell.innerHTML =

                '<div class="cell-course">' +
                escapeHtml(
                    match.course_code
                ) +
                '</div>' +

                '<div class="cell-subject">' +
                escapeHtml(
                    match.subject
                ) +
                '</div>' +

                '<div class="cell-instructor">' +
                escapeHtml(
                    match.instructor
                ) +
                '</div>' +

                '<div class="cell-year">' +
                escapeHtml(
                    match.year_level
                ) +
                '</div>' +

                '<div class="cell-major">' +
                escapeHtml(
                    match.major || ''
                ) +
                '</div>' +

                '<div class="cell-program">' +
                escapeHtml(
                    match.program || ''
                ) +
                '</div>' +

                subjectTypeDisplay;


            /*
            |------------------------------------------------------------------
            | BACKGROUND COLOR
            |------------------------------------------------------------------
            */

            cell.style.backgroundColor =
                color;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLEAR SELECTION
    |--------------------------------------------------------------------------
    */

    selectedCells.forEach(
        function(cell)
        {
            cell.classList.remove(
                'selected-cell'
            );
        }
    );


    mergeHorizontalSchedules();

    /*
    |--------------------------------------------------------------------------
    | SUCCESS MESSAGE
    |--------------------------------------------------------------------------
    */

    showSuccess(

        'Saved: ' +
        match.course_code +
        ' — ' +
        match.subject +
        ' — ' +
        match.instructor

    );
}
    /*
|--------------------------------------------------------------------------
| CLEAR CELL
|--------------------------------------------------------------------------
*/

function clearCell()
{

    const selectedCells =
        Array.from(
            document.querySelectorAll(
                '#scheduleTable .schedule-cell.selected-cell'
            )
        );


    /*
    |--------------------------------------------------------------------------
    | CHECK SELECTION
    |--------------------------------------------------------------------------
    */

    if (selectedCells.length === 0) {

        alert(
            'Please select a schedule cell first.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK LOCKED CELLS
    |--------------------------------------------------------------------------
    */

    const lockedCells =
        selectedCells.filter(
            cell =>
                cell.dataset.locked === '1'
        );


    if (lockedCells.length > 0) {

        alert(
            'One or more selected cells belong to another department and are locked.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR ALL SELECTED CELLS
    |--------------------------------------------------------------------------
    */

    selectedCells.forEach(
        function(cell)
        {

            if (cell.dataset.mergedDays) {
                unmergeCell(cell);
            }

            cell.dataset.course =
                '';

            cell.dataset.subject =
                '';

            cell.dataset.subjectType =
                '';

            cell.dataset.year =
                '';

            cell.dataset.major =
                '';

            cell.dataset.instructor =
                '';

            cell.dataset.facultyId =
                '';

            cell.dataset.facultySubjectId =
                '';

            cell.dataset.departmentId =
                '';

            cell.dataset.program =
                '';

            cell.dataset.color =
                '#ffffff';

            cell.dataset.locked =
                '0';


            cell.classList.remove(
                'locked-cell'
            );


            cell.style.backgroundColor =
                '#ffffff';


            cell.innerHTML =
                '<span class="empty-cell">Empty</span>';


            /*
            | Remove selection highlight
            */

            cell.classList.remove(
                'selected-cell'
            );

        }
    );


    mergeHorizontalSchedules();

    /*
    |--------------------------------------------------------------------------
    | CLEAR INPUT FIELDS
    |--------------------------------------------------------------------------
    */

    courseCodeInput.value =
        '';

    subjectInput.value =
        '';

    subjectTypeInput.value =
        '';

    yearLevelInput.value =
        '';

    majorInput.value =
        '';

    instructorInput.value =
        '';

    programInput.value =
        '';

    colorInput.value =
        '#ffffff';


    /*
    |--------------------------------------------------------------------------
    | CLEAR STATUS
    |--------------------------------------------------------------------------
    */

    clearStatus();

}

    /*
    |--------------------------------------------------------------------------
    | COLOR
    |--------------------------------------------------------------------------
    */

    colorInput.addEventListener(
        'input',
        function()
        {

            if (!selectedCell) {

                return;

            }


            if (
                selectedCell.dataset.locked ===
                '1'
            ) {

                return;

            }


            selectedCell.style.backgroundColor =
                this.value;


            selectedCell.dataset.color =
                this.value;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ADD TIME ROW
    |--------------------------------------------------------------------------
    */

   function createScheduleRow()
{
    const row = document.createElement('tr');

    function createTimeCell(side)
    {
        const timeCell = document.createElement('td');

        timeCell.dataset.side = side;
        timeCell.className = 'time-cell editable-time';
        timeCell.contentEditable = 'true';
        timeCell.spellcheck = false;
        timeCell.textContent = 'New Time';

        return timeCell;
    }

    function createScheduleCell(day, side)
    {
        const cell = document.createElement('td');

        cell.className = 'schedule-cell';
        cell.dataset.day = day;
        cell.dataset.side = side;
        cell.dataset.time = '';
        cell.dataset.scheduleIds = '';
        cell.dataset.mergedDays = '';
        cell.dataset.course = '';
        cell.dataset.subject = '';
        cell.dataset.year = '';
        cell.dataset.major = '';
        cell.dataset.program = '';
        cell.dataset.instructor = '';
        cell.dataset.facultyId = '';
        cell.dataset.facultySubjectId = '';
        cell.dataset.departmentId = chairDepartmentId;
        cell.dataset.color = '#ffffff';
        cell.dataset.subjectType = '';
        cell.dataset.locked = '0';
        cell.dataset.reservationId = '';

        cell.innerHTML = '<span class="empty-cell">Empty</span>';

        return cell;
    }

    // LEFT: TIME + MON + WED + FRI
    row.appendChild(createTimeCell('left'));
    row.appendChild(createScheduleCell('MON', 'left'));
    row.appendChild(createScheduleCell('WED', 'left'));
    row.appendChild(createScheduleCell('FRI', 'left'));

    // RIGHT: TIME + TUE + THU + SAT
    row.appendChild(createTimeCell('right'));
    row.appendChild(createScheduleCell('TUE', 'right'));
    row.appendChild(createScheduleCell('THU', 'right'));
    row.appendChild(createScheduleCell('SAT', 'right'));

    return row;
}

function finalizeNewScheduleRow(row)
{
    const leftTime = row.querySelector('.editable-time[data-side="left"]');
    const rightTime = row.querySelector('.editable-time[data-side="right"]');

    const syncRowTimes = function()
    {
        const leftValue = leftTime ? leftTime.innerText.trim() : '';
        const rightValue = rightTime ? rightTime.innerText.trim() : '';

        row.querySelectorAll('.schedule-cell[data-side="left"]').forEach(function(cell) {
            cell.dataset.time = leftValue;
        });

        row.querySelectorAll('.schedule-cell[data-side="right"]').forEach(function(cell) {
            cell.dataset.time = rightValue;
        });
    };

    syncRowTimes();
    attachCellEvents();
    attachTimeCellEvents();
    mergeHorizontalSchedules();

    row.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
}

function addRow()
{
    const tbody = document.getElementById('scheduleBody');
    const row = createScheduleRow();

    tbody.appendChild(row);
    finalizeNewScheduleRow(row);
}

function addRowBeforeBreak()
{
    const tbody = document.getElementById('scheduleBody');
    const row = createScheduleRow();

    // Put the new time row ABOVE the Add Time Row button.
    // The button stays immediately before the Noon Break.
    const addButtonRow = tbody.querySelector('.add-time-before-break-row');
    const breakRow = tbody.querySelector('.break-row');

    if (addButtonRow) {
        tbody.insertBefore(row, addButtonRow);
    } else if (breakRow) {
        tbody.insertBefore(row, breakRow);
    } else {
        tbody.appendChild(row);
    }

    finalizeNewScheduleRow(row);
}

    document
        .getElementById(
            'addRowButton'
        )
        .addEventListener(
            'click',
            addRow
        );

    const addRowBeforeBreakButton =
        document.getElementById(
            'addRowBeforeBreakButton'
        );

    if (addRowBeforeBreakButton) {
        addRowBeforeBreakButton.addEventListener(
            'click',
            addRowBeforeBreak
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUTTON EVENTS
    |--------------------------------------------------------------------------
    */

    document
        .getElementById(
            'saveCellButton'
        )
        .addEventListener(
            'click',
            saveCell
        );


    document
        .getElementById(
            'clearCellButton'
        )
        .addEventListener(
            'click',
            clearCell
        );


    /*
    |--------------------------------------------------------------------------
    | PREPARE SCHEDULE
    |--------------------------------------------------------------------------
    */

  function prepareSchedule()
{
    const hidden =
        document.getElementById('hiddenInputs');

    hidden.innerHTML = '';

    const semester =
        document.getElementById('semester')?.value.trim() || '';

    const academicYear =
        document.getElementById('academic_year')?.value.trim() || '';

    function addHidden(name, value)
    {
        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = name;
        input.value = value ?? '';

        hidden.appendChild(input);
    }

    addHidden('semester', semester);
    addHidden('academic_year', academicYear);

    const rows =
        Array.from(
            document.querySelectorAll(
                '#scheduleTable tbody tr:not(.break-row):not(.add-time-before-break-row)'
            )
        );

    const rowPositionMap =
        new Map(
            rows.map(function(row, index) {
                return [row, index];
            })
        );

  let counter = 0;
let timeSlotCounter = 0;
let roomTimeSlotCounter = 0;

    /*
    |--------------------------------------------------------------------------
    | SUBMIT EVERY OFFICIAL TIME ROW
    |--------------------------------------------------------------------------
    |
    | This keeps schedule_time_slots synchronized even when a new time row
    | has no class assigned yet.
    |--------------------------------------------------------------------------
    */

    rows.forEach(function(row) {

        const rowPosition =
            rowPositionMap.get(row);

        ['left', 'right'].forEach(function(side) {

            const timeCell =
                row.querySelector(
                    '.editable-time[data-side="' + side + '"]'
                );

            const time =
                timeCell
                    ? timeCell.innerText.trim()
                    : '';

            if (!time) {
                return;
            }

            row.querySelectorAll(
                '.schedule-cell[data-side="' + side + '"]'
            ).forEach(function(cell) {
                cell.dataset.time = time;
            });

            const days =
                side === 'left'
                    ? ['MON', 'WED', 'FRI']
                    : ['TUE', 'THU', 'SAT'];

           days.forEach(function(day) {

    /*
    |--------------------------------------------------------------------------
    | GLOBAL TIME SLOT
    |--------------------------------------------------------------------------
    */

    addHidden(
        'time_slots[' +
        timeSlotCounter +
        '][day]',
        day
    );

    addHidden(
        'time_slots[' +
        timeSlotCounter +
        '][time]',
        time
    );

    addHidden(
        'time_slots[' +
        timeSlotCounter +
        '][row_position]',
        rowPosition
    );

    timeSlotCounter++;


    /*
    |--------------------------------------------------------------------------
    | ROOM-SPECIFIC TIME SLOT
    |--------------------------------------------------------------------------
    |
    | This records that THIS ROOM actually has this timetable row.
    | It can therefore be Available even when the cell is empty.
    |
    */

    addHidden(
        'room_time_slots[' +
        roomTimeSlotCounter +
        '][day]',
        day
    );

    addHidden(
        'room_time_slots[' +
        roomTimeSlotCounter +
        '][time]',
        time
    );

    addHidden(
        'room_time_slots[' +
        roomTimeSlotCounter +
        '][row_position]',
        rowPosition
    );

    roomTimeSlotCounter++;
});
        });
    });

    /*
    |--------------------------------------------------------------------------
    | SUBMIT SCHEDULE CELLS
    |--------------------------------------------------------------------------
    |
    | A horizontally merged cell represents multiple weekdays. The same
    | class is submitted once for every weekday represented by that cell.
    | No vertical merging is performed.
    |--------------------------------------------------------------------------
    */

    rows.forEach(function(row) {

        const rowPosition =
            rowPositionMap.get(row);

        row.querySelectorAll('.schedule-cell').forEach(function(cell) {

            if (cell.dataset.locked === '1') {
                return;
            }

            const course =
                String(cell.dataset.course || '')
                    .trim()
                    .toUpperCase();

            if (!course) {
                return;
            }

            const matches = findCourse(course);
            const match = matches.length === 1
                ? matches[0]
                : matches.find(item => String(item.id) === String(cell.dataset.facultySubjectId || ''));
            if (!match) return;

            const subjectType =
                String(cell.dataset.subjectType || '').trim();

            const time =
                String(
                    cell.dataset.time ||
                    ''
                ).trim();

            if (!time) {
                return;
            }

            let days =
                String(
                    cell.dataset.mergedDays ||
                    cell.dataset.day ||
                    ''
                )
                .split(',')
                .map(function(day) {
                    return day.trim().toUpperCase();
                })
                .filter(Boolean);

            if (days.length === 0) {
                return;
            }

            let scheduleIds = {};

            try {
                scheduleIds =
                    JSON.parse(
                        cell.dataset.scheduleIds || '{}'
                    ) || {};
            } catch (error) {
                scheduleIds = {};
            }

            days.forEach(function(day) {

                const scheduleData = {

                    schedule_id:
                        scheduleIds[day] ||
                        (
                            days.length === 1
                                ? cell.dataset.scheduleId || ''
                                : ''
                        ),

                    row_position:
                        rowPosition,

                    day:
                        day,

                    time:
                        time,

                    course_code:
                        match.course_code,

                    faculty_subject_id:
                        match.id,

                    subject:
                        match.subject,

                    year_level:
                        match.year_level,

                    major:
                        match.major || '',

                    program:
                        match.program || '',

                    instructor:
                        match.instructor,

                    faculty_id:
                        match.faculty_id || '',

                    department_id:
                        chairDepartmentId,

                    subject_type:
                        subjectType,

                    color:
                        cell.dataset.color ||
                        '#ffffff'
                };

                Object.keys(scheduleData).forEach(
                    function(name)
                    {
                        addHidden(
                            'schedule[' +
                            counter +
                            '][' +
                            name +
                            ']',
                            scheduleData[name]
                        );
                    }
                );

                counter++;
            });
        });
    });

    return counter;
}

    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    scheduleForm.addEventListener(
    'submit',
    function(event)
    {
        const submitter =
            event.submitter;

        /*
        |--------------------------------------------------------------------------
        | SAVE AS DRAFT
        |--------------------------------------------------------------------------
        */

        if (
            submitter &&
            submitter.value === 'draft'
        ) {

            const count =
                prepareSchedule();

            if (count === 0) {

                event.preventDefault();

                alert(
                    'There is no schedule entry to save. Select an editable or empty cell, enter a valid course code, and click Save Cell first.'
                );

                return;
            }

            scheduleForm.action =
                "{{ route('chair.save.draft') }}";

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL UPLOAD
        |--------------------------------------------------------------------------
        */

        if (!isEditingDraft) {

            const count =
                prepareSchedule();

            if (count === 0) {

                event.preventDefault();

                alert(
                    'There is no schedule entry to save. Select an editable or empty cell, enter a valid course code, and click Save Cell first.'
                );

                return;
            }

            scheduleForm.action =
                "{{ route('chair.save.schedule') }}";

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | EDITING A DRAFT
        |--------------------------------------------------------------------------
        |
        | Do NOT immediately send the draft to saveSchedule().
        |
        | First check whether this room already contains
        | a published schedule.
        |
        */

        event.preventDefault();

        const count =
            prepareSchedule();

        if (count === 0) {

            alert(
                'There is no schedule entry to publish. Select an editable or empty cell, enter a valid course code, and click Save Cell first.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ASK FOR OVERRIDE
        |--------------------------------------------------------------------------
        */

        const confirmed =
            confirm(
                'This room already has a published schedule.\n\n' +
                'Publishing this edited draft will replace the existing schedule in this room.\n\n' +
                'Do you want to override the existing schedule and publish this draft?'
            );


        /*
        |--------------------------------------------------------------------------
        | CANCEL
        |--------------------------------------------------------------------------
        */

        if (!confirmed) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | OVERRIDE AND PUBLISH
        |--------------------------------------------------------------------------
        */

        scheduleForm.action =
            "{{ route(
                'chair.drafts.publish',
                $room->id
            ) }}";


        /*
        |--------------------------------------------------------------------------
        | ADD OVERRIDE FLAG
        |--------------------------------------------------------------------------
        */

        let overrideInput =
            scheduleForm.querySelector(
                'input[name="override"]'
            );

        if (!overrideInput) {

            overrideInput =
                document.createElement('input');

            overrideInput.type =
                'hidden';

            overrideInput.name =
                'override';

            scheduleForm.appendChild(
                overrideInput
            );
        }

        overrideInput.value =
            '1';


        /*
        |--------------------------------------------------------------------------
        | SUBMIT AGAIN
        |--------------------------------------------------------------------------
        */

        scheduleForm.submit();
    }
);

    // =====================================================
// EDITABLE TIME CELLS
// =====================================================

function attachTimeCellEvents() {
    document.querySelectorAll('.editable-time').forEach(timeCell => {

        if (timeCell.dataset.timeEventsAttached === '1') {
            return;
        }

        timeCell.dataset.timeEventsAttached = '1';

        function syncTimeToCells() {
            const row = timeCell.closest('tr');

            if (!row) {
                return;
            }

            const time =
                timeCell.innerText.trim();

            const side =
                timeCell.dataset.side;

            row.querySelectorAll(
                '.schedule-cell[data-side="' + side + '"]'
            ).forEach(function(cell) {
                cell.dataset.time = time;
            });
        }

        syncTimeToCells();

        timeCell.addEventListener('input', syncTimeToCells);
        timeCell.addEventListener('blur', syncTimeToCells);

        timeCell.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                this.blur();
            }
        });
    });
}

// ============================================================
// MERGE WITH EXISTING CLASS
// ============================================================

const findMergeClassBtn =
    document.getElementById('findMergeClassBtn');

const mergeSearchForm =
    document.getElementById('mergeSearchForm');

const searchMergeBtn =
    document.getElementById('searchMergeBtn');

const mergeCourseCode =
    document.getElementById('mergeCourseCode');

const mergeSearchStatus =
    document.getElementById('mergeSearchStatus');

const mergeResults =
    document.getElementById('mergeResults');


// ------------------------------------------------------------
// SHOW MERGE SEARCH FORM
// ------------------------------------------------------------

if (findMergeClassBtn) {

    findMergeClassBtn.addEventListener(
        'click',
        function () {

            mergeSearchForm.style.display =
                'block';

            mergeSearchStatus.textContent =
                '';

            mergeResults.innerHTML =
                '';

            mergeResults.style.display =
                'none';

            mergeCourseCode.focus();

        }
    );

}


// ------------------------------------------------------------
// SEARCH EXISTING CLASS
// ------------------------------------------------------------

if (searchMergeBtn) {

    searchMergeBtn.addEventListener(
        'click',
        function () {

           const courseCode =
    mergeCourseCode.value.trim();

if (!courseCode) {

    alert(
        'Please enter a course code.'
    );

    mergeCourseCode.focus();

    return;
}


            mergeSearchStatus.textContent =
                'Searching for existing classes...';

            mergeResults.innerHTML =
                '';

            mergeResults.style.display =
                'none';

const params =
    new URLSearchParams({

        course_code:
            courseCode

    });


            fetch(
                '{{ route("chair.search.merge.classes") }}?' +
                params.toString(),
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
            .then(function(response) {

                if (!response.ok) {

                    throw new Error(
                        'Search request failed.'
                    );

                }

                return response.json();

            })
            .then(function(data) {
                window.mergeRoomSchedules =
                data.schedules || [];
                if (
                    !data.classes ||
                    data.classes.length === 0
                ) {

                    mergeSearchStatus.textContent =
                        'No matching class was found in another department.';

                    return;
                }


                mergeSearchStatus.textContent =
                    data.classes.length +
                    ' matching class(es) found.';


                data.classes.forEach(
                    function(existingClass) {

                        const result =
                            document.createElement('div');

                        result.className =
                            'merge-result';


                        result.innerHTML =

                            '<div class="merge-result-title">' +

                            escapeHtml(
                                existingClass.course_code
                            ) +

                            ' — ' +

                            escapeHtml(
                                existingClass.subject
                            ) +

                            '</div>' +

                            '<div class="merge-result-info">' +

                            '<strong>Department:</strong> ' +
                            escapeHtml(
                                 existingClass.department
                                ) +

                            '<br>' +

                            '<strong>Program:</strong> ' +
                                escapeHtml(
                            existingClass.program ||
                            'Unknown Program'
                                ) +

                            '<br>' +

                            '<strong>Instructor:</strong> ' +

                            escapeHtml(
                                existingClass.instructor
                            ) +

                            '<br>' +

                           '<strong>Building:</strong> ' +

escapeHtml(
    existingClass.building ||
    'Unknown Building'
) +

'<br>' +

'<strong>Room:</strong> ' +

escapeHtml(
    existingClass.room
) +

'<br>' +

                            '<strong>Day:</strong> ' +

                            escapeHtml(
                                existingClass.day
                            ) +

                            '<br>' +

                            '<strong>Time:</strong> ' +

                            escapeHtml(
                                existingClass.time
                            ) +

                            '</div>' +

                            '<button ' +

                            'type="button" ' +

                            'class="merge-select-btn btn btn-green" ' +

                            'data-schedule-id="' +

                            existingClass.id +

                            '">' +

                            'Select This Class' +

                            '</button>';


                        mergeResults.appendChild(
                            result
                        );

                    }
                );


                mergeResults.style.display =
    'block';

/*
|--------------------------------------------------------------------------
| SEARCH RESULT — SELECT CLASS
|--------------------------------------------------------------------------
*/

mergeResults
    .querySelectorAll('.merge-select-btn')
    .forEach(function(button)
    {
        button.addEventListener(
            'click',
            function()
            {
                const scheduleId =
                    this.dataset.scheduleId;

                const existingClass =
                    data.classes.find(
                        function(item)
                        {
                            return String(item.id) ===
                                String(scheduleId);
                        }
                    );

                if (!existingClass) {
                    alert(
                        'Unable to load the selected class.'
                    );
                    return;
                }

                const existingProgram =
                    existingClass.program ||
                    'Unknown Program';

                const mergeProgramSelection =
    document.getElementById(
        'mergeProgramSelection'
    );

const mergeProgramSelect =
    document.getElementById(
        'mergeProgramSelect'
    );

if (
    !mergeProgramSelection ||
    !mergeProgramSelect
) {
    alert(
        'Program selection is unavailable.'
    );
    return;
}

/*
|--------------------------------------------------------------------------
| SAVE SEARCHED CLASS TEMPORARILY
|--------------------------------------------------------------------------
*/

window.selectedMergeSchedule = {
    id: existingClass.id,
    course_code: existingClass.course_code,
    subject: existingClass.subject,
    instructor: existingClass.instructor,
    department: existingClass.department,
    building: existingClass.building,
    room: existingClass.room,
    day: existingClass.day,
    time: existingClass.time,
    program:
        existingClass.program ||
        'Unknown Program'
};

/*
|--------------------------------------------------------------------------
| OPEN PROGRAM POPUP
|--------------------------------------------------------------------------
*/

mergeProgramSelect.value = '';

mergeProgramSelection.style.display =
    'flex';

loadMergePrograms();

return;

                const confirmed =
                    confirm(
                        'MERGE EXISTING CLASS?\n\n' +
                        'Course Code: ' +
                        existingClass.course_code +
                        '\n' +
                        'Subject: ' +
                        existingClass.subject +
                        '\n' +
                        'Instructor: ' +
                        existingClass.instructor +
                        '\n' +
                        'Department: ' +
                        existingClass.department +
                        '\n' +
                        'Building: ' +
                        existingClass.building +
                        '\n' +
                        'Room: ' +
                        existingClass.room +
                        '\n' +
                        'Day: ' +
                        existingClass.day +
                        '\n' +
                        'Time: ' +
                        existingClass.time +
                        '\n\n' +
                        'Existing Program:\n' +
                        existingProgram +
                        '\n\n' +
                        'Program to Add:\n' +
                        mergingProgram.trim() +
                        '\n\n' +
                        'Result after approval:\n' +
                        existingProgram +
                        ' / ' +
                        mergingProgram.trim() +
                        '\n\n' +
                        'The original room, instructor, and schedule will remain unchanged.\n\n' +
                        'Send merge request to the owner department?'
                    );

                if (!confirmed) {
                    return;
                }

                fetch(
                    "{{ route('chair.merge.request') }}",
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'X-CSRF-TOKEN':
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).getAttribute('content')
                        },

                        body: JSON.stringify({
                            schedule_id:
                                scheduleId,

                            program:
                                mergingProgram.trim()
                        })
                    }
                )
                .then(function(response)
                {
                    return response.json()
                        .then(function(result)
                        {
                            if (!response.ok) {
                                throw new Error(
                                    result.message ||
                                    'Unable to submit merge request.'
                                );
                            }

                            return result;
                        });
                })
                .then(function(result)
                {
                    if (!result.success) {
                        throw new Error(
                            result.message ||
                            'Unable to submit merge request.'
                        );
                    }

                    alert(
                        'Merge request submitted successfully!\n\n' +
                        'Course: ' +
                        existingClass.course_code +
                        '\n' +
                        'Program: ' +
                        mergingProgram.trim() +
                        '\n' +
                        'Status: Pending'
                    );

                    button.disabled = true;
                    button.textContent =
                        'Merge Request Sent';
                })
                .catch(function(error)
                {
                    console.error(error);

                    alert(
                        error.message ||
                        'Unable to submit merge request.'
                    );
                });
            }
        );
    });

            })
            .catch(function(error) {

                console.error(error);

                mergeSearchStatus.textContent =
                    'Unable to search for existing classes.';

            });

        }
    );

}


// ============================================================
// MANUAL MERGE BROWSE
// ============================================================

const mergeDepartments =
    document.getElementById('mergeDepartments');

const mergeScheduleView =
    document.getElementById('mergeScheduleView');


// ------------------------------------------------------------
// LOAD DEPARTMENTS
// ------------------------------------------------------------

function loadMergeDepartments()
{
    if (!mergeDepartments) {
        return;
    }

    mergeDepartments.innerHTML =
        '<div class="merge-loading">' +
        'Loading departments...' +
        '</div>';

    fetch(
        '{{ route("chair.merge.departments") }}',
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
    .then(function(response)
    {
        if (!response.ok) {
            throw new Error(
                'Unable to load departments.'
            );
        }

        return response.json();
    })
    .then(function(data)
    {
        if (
            !data.success ||
            !data.departments ||
            data.departments.length === 0
        ) {
            mergeDepartments.innerHTML =
                '<div class="merge-empty">' +
                'No other departments found.' +
                '</div>';

            return;
        }

        mergeDepartments.innerHTML = '';

        data.departments.forEach(
            function(department)
            {
                const departmentBox =
                    document.createElement('div');

                departmentBox.className =
                    'merge-department-box';

                departmentBox.innerHTML =
                    '<button ' +
                    'type="button" ' +
                    'class="merge-department-btn">' +

                    escapeHtml(
                        department.name
                    ) +

                    '</button>' +

                    '<div ' +
                    'class="merge-buildings" ' +
                    'style="display:none;">' +

                    '</div>';

                mergeDepartments.appendChild(
                    departmentBox
                );


                const departmentButton =
                    departmentBox.querySelector(
                        '.merge-department-btn'
                    );

                const buildingsContainer =
                    departmentBox.querySelector(
                        '.merge-buildings'
                    );


                departmentButton.addEventListener(
                    'click',
                    function()
                    {
                        const isOpen =
                            buildingsContainer.style.display ===
                            'block';

                        if (isOpen) {

                            buildingsContainer.style.display =
                                'none';

                            return;
                        }


                        buildingsContainer.innerHTML =
                            '';


                        if (
                            !department.buildings ||
                            department.buildings.length === 0
                        ) {

                            buildingsContainer.innerHTML =
                                '<div class="merge-empty">' +
                                'No buildings found.' +
                                '</div>';

                            buildingsContainer.style.display =
                                'block';

                            return;
                        }


                        department.buildings.forEach(
                            function(building)
                            {
                                const buildingBox =
                                    document.createElement('div');

                                buildingBox.className =
                                    'merge-building-box';


                                buildingBox.innerHTML =
                                    '<button ' +
                                    'type="button" ' +
                                    'class="merge-building-btn">' +

                                    escapeHtml(
                                        building.name
                                    ) +

                                    '</button>' +

                                    '<div ' +
                                    'class="merge-rooms" ' +
                                    'style="display:none;">' +

                                    '</div>';


                                buildingsContainer.appendChild(
                                    buildingBox
                                );


                                const buildingButton =
                                    buildingBox.querySelector(
                                        '.merge-building-btn'
                                    );

                                const roomsContainer =
                                    buildingBox.querySelector(
                                        '.merge-rooms'
                                    );


                                buildingButton.addEventListener(
                                    'click',
                                    function()
                                    {
                                        const isOpen =
                                            roomsContainer.style.display ===
                                            'block';

                                        if (isOpen) {

                                            roomsContainer.style.display =
                                                'none';

                                            return;
                                        }


                                        roomsContainer.innerHTML =
                                            '';


                                        if (
                                            !building.rooms ||
                                            building.rooms.length === 0
                                        ) {

                                            roomsContainer.innerHTML =
                                                '<div class="merge-empty">' +
                                                'No rooms found.' +
                                                '</div>';

                                            roomsContainer.style.display =
                                                'block';

                                            return;
                                        }


                                        building.rooms.forEach(
                                            function(room)
                                            {
                                                const roomButton =
                                                    document.createElement(
                                                        'button'
                                                    );

                                                roomButton.type =
                                                    'button';

                                                roomButton.className =
                                                    'merge-room-btn';

                                                roomButton.textContent =
                                                    room.name;

                                                roomButton.dataset.roomId =
                                                    room.id;

                                                roomsContainer.appendChild(
                                                    roomButton
                                                );


                                                roomButton.addEventListener(
                                                    'click',
                                                    function()
                                                    {
                                                        openMergeRoomSchedule(
                                                            room.id,
                                                            department.name,
                                                            building.name,
                                                            room.name
                                                        );
                                                    }
                                                );
                                            }
                                        );


                                        roomsContainer.style.display =
                                            'block';
                                    }
                                );
                            }
                        );


                        buildingsContainer.style.display =
                            'block';
                    }
                );
            }
        );
    })
    .catch(function(error)
    {
        console.error(error);

        mergeDepartments.innerHTML =
            '<div class="merge-error">' +
            'Unable to load departments.' +
            '</div>';
    });
}


// ------------------------------------------------------------
// OPEN ROOM SCHEDULE IN SEPARATE CARD
// ------------------------------------------------------------

function openMergeRoomSchedule(
    roomId,
    departmentName,
    buildingName,
    roomName
)
{
    if (!mergeScheduleView) {
        return;
    }

    /*
     |--------------------------------------------------------------------------
     | SHOW THE SCHEDULE CARD
     |--------------------------------------------------------------------------
     */

    mergeScheduleView.style.display =
        'block';

    mergeScheduleView.innerHTML =

        '<div class="merge-schedule-card">' +

            '<div class="merge-schedule-card-header">' +

                '<div>' +

                    '<div class="merge-schedule-card-title">' +
                        'Room Schedule' +
                    '</div>' +

                    '<div class="merge-schedule-card-info">' +

                        '<strong>Department:</strong> ' +
                        escapeHtml(departmentName) +

                        ' &nbsp; | &nbsp; ' +

                        '<strong>Building:</strong> ' +
                        escapeHtml(buildingName) +

                        ' &nbsp; | &nbsp; ' +

                        '<strong>Room:</strong> ' +
                        escapeHtml(roomName) +

                    '</div>' +

                '</div>' +

                '<button ' +
                    'type="button" ' +
                    'class="merge-close-schedule-btn"' +
                '>' +
                    'Close' +
                '</button>' +

            '</div>' +

            '<div class="merge-schedule-loading">' +
                'Loading schedule...' +
            '</div>' +

        '</div>';


    /*
     |--------------------------------------------------------------------------
     | CLOSE BUTTON
     |--------------------------------------------------------------------------
     */

    const closeButton =
        mergeScheduleView.querySelector(
            '.merge-close-schedule-btn'
        );

    closeButton.addEventListener(
        'click',
        function()
        {
            mergeScheduleView.style.display =
                'none';

            mergeScheduleView.innerHTML =
                '';
        }
    );


    /*
     |--------------------------------------------------------------------------
     | LOAD ROOM SCHEDULE
     |--------------------------------------------------------------------------
     */

    fetch(
        '{{ route("chair.merge.room", ":roomId") }}'
            .replace(
                ':roomId',
                roomId
            ),
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
    .then(function(response)
    {
       if (!response.ok) {

    return response.text().then(function(text) {

        console.error(
            'Room schedule error:',
            response.status,
            text
        );

        throw new Error(
            'Server error ' +
            response.status +
            ': ' +
            text
        );

    });

}
        return response.json();
    })
    .then(function(data)
    {
        if (
            !data.success ||
            !data.room
        ) {

            throw new Error(
                'Invalid room schedule response.'
            );

        }


        /*
         |--------------------------------------------------------------------------
         | FIND THE CARD
         |--------------------------------------------------------------------------
         */

        const card =
            mergeScheduleView.querySelector(
                '.merge-schedule-card'
            );


        /*
         |--------------------------------------------------------------------------
         | BUILD SCHEDULE
         |--------------------------------------------------------------------------
         */

        let scheduleHtml =

            '<div class="merge-readonly-notice">' +

                'This schedule is read-only. ' +

                'Only existing occupied classes can be selected ' +

                'for a merge request.' +

            '</div>' +

            '<div class="merge-room-schedule-table-wrapper">' +

            '<table class="merge-room-schedule-table">' +

                '<thead>' +

                    '<tr>' +

                        '<th>TIME</th>' +

                        '<th>MON</th>' +

                        '<th>WED</th>' +

                        '<th>FRI</th>' +

                        '<th>TIME</th>' +

                        '<th>TUE</th>' +

                        '<th>THU</th>' +

                        '<th>SAT</th>' +

                    '</tr>' +

                '</thead>' +

                '<tbody>';


        /*
         |--------------------------------------------------------------------------
         | TIME ROWS
         |--------------------------------------------------------------------------
         */

        const timeRows = [
            '8:00-9:00',
            '9:00-10:00',
            '10:00-11:00',
            '11:00-12:00',
            '12:00-1:00',
            '1:00-2:00',
            '2:00-3:00',
            '3:00-4:00',
            '4:00-5:00'
        ];


        timeRows.forEach(
            function(time, rowIndex)
            {
                /*
                 |--------------------------------------------------------------------------
                 | NOON BREAK
                 |--------------------------------------------------------------------------
                 */

                if (rowIndex === 4) {

                    scheduleHtml +=

                        '<tr class="merge-noon-break">' +

                            '<td colspan="8">' +
                                'NOON BREAK' +
                            '</td>' +

                        '</tr>';

                }


                /*
                 |--------------------------------------------------------------------------
                 | FIND CELLS
                 |--------------------------------------------------------------------------
                 */

                const leftTime =
                    time;

                const rightTime =
                    time;


                const monday =
                    findMergeSchedule(
                        data.schedules,
                        'MON',
                        rowIndex
                    );

                const wednesday =
                    findMergeSchedule(
                        data.schedules,
                        'WED',
                        rowIndex
                    );

                const friday =
                    findMergeSchedule(
                        data.schedules,
                        'FRI',
                        rowIndex
                    );

                const tuesday =
                    findMergeSchedule(
                        data.schedules,
                        'TUE',
                        rowIndex
                    );

                const thursday =
                    findMergeSchedule(
                        data.schedules,
                        'THU',
                        rowIndex
                    );


                scheduleHtml +=

                    '<tr>' +

                        '<td class="merge-time-cell">' +
                            escapeHtml(leftTime) +
                        '</td>' +

                        buildMergeScheduleCell(
                            monday
                        ) +

                        buildMergeScheduleCell(
                            wednesday
                        ) +

                        buildMergeScheduleCell(
                            friday
                        ) +

                        '<td class="merge-time-cell">' +
                            escapeHtml(rightTime) +
                        '</td>' +

                        buildMergeScheduleCell(
                            tuesday
                        ) +

                        buildMergeScheduleCell(
                            thursday
                        ) +

                        buildMergeScheduleCell(
                            saturday
                        ) +

                    '</tr>';
            }
        );


        scheduleHtml +=

                '</tbody>' +

            '</table>' +

            '</div>';


        /*
         |--------------------------------------------------------------------------
         | REPLACE LOADING MESSAGE
         |--------------------------------------------------------------------------
         */

        const loading =
            card.querySelector(
                '.merge-schedule-loading'
            );

        if (loading) {

            loading.outerHTML =
                scheduleHtml;

        }
    })
    .catch(function(error)
    {
        console.error(error);

        const card =
            mergeScheduleView.querySelector(
                '.merge-schedule-card'
            );

        if (card) {

            const loading =
                card.querySelector(
                    '.merge-schedule-loading'
                );

            if (loading) {

                loading.innerHTML =
                    'Unable to load room schedule.';

            }

        }
    });
}

function loadMergePrograms()
{
    const mergeProgramSelect =
        document.getElementById(
            'mergeProgramSelect'
        );

    if (!mergeProgramSelect) {
        return;
    }

    mergeProgramSelect.innerHTML =
        '<option value="">Loading programs...</option>';

    fetch(
        "{{ route('chair.merge.programs') }}",
        {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }
    )
    .then(function(response)
    {
        if (!response.ok) {
            throw new Error(
                'Failed to load programs.'
            );
        }

        return response.json();
    })
    .then(function(data)
    {
        mergeProgramSelect.innerHTML =
            '<option value="">Select Program</option>';

        if (
            !data.success ||
            !Array.isArray(data.programs) ||
            data.programs.length === 0
        ) {
            mergeProgramSelect.innerHTML =
                '<option value="">No programs available</option>';

            return;
        }

        data.programs.forEach(
            function(program)
            {
                const option =
                    document.createElement('option');

                option.value = program;
                option.textContent = program;

                mergeProgramSelect.appendChild(
                    option
                );
            }
        );
    })
    .catch(function(error)
    {
        console.error(
            'Merge programs error:',
            error
        );

        mergeProgramSelect.innerHTML =
            '<option value="">Unable to load programs</option>';
    });
}
// ------------------------------------------------------------
// FIND SCHEDULE FOR A DAY / ROW
// ------------------------------------------------------------

function findMergeSchedule(
    schedules,
    day,
    rowIndex
)
{
    if (!schedules) {
        return null;
    }

    return schedules.find(
        function(schedule)
        {
            return (
                schedule.day === day &&
                Number(schedule.row_position) ===
                    rowIndex
            );
        }
    ) || null;
}


// ------------------------------------------------------------
// BUILD READ-ONLY SCHEDULE CELL
// ------------------------------------------------------------
function buildMergeScheduleCell(
    schedule
)
{
    if (!schedule) {
        return (
            '<td class="merge-schedule-cell merge-empty-cell">' +
                '<span>' +
                    'Empty' +
                '</span>' +
            '</td>'
        );
    }

    return (
        '<td ' +
            'class="merge-schedule-cell merge-occupied-cell" ' +
            'data-schedule-id="' +
                schedule.id +
            '" ' +
            'data-program="' +
                escapeHtml(
                    schedule.program || ''
                ) +
            '"' +
        '>' +

            '<div class="merge-class-course">' +
                escapeHtml(
                    schedule.course_code || ''
                ) +
            '</div>' +

            '<div class="merge-class-subject">' +
                escapeHtml(
                    schedule.subject || ''
                ) +
            '</div>' +

            '<div class="merge-class-instructor">' +
                escapeHtml(
                    schedule.instructor || ''
                ) +
            '</div>' +

            '<div class="merge-select-hint">' +
                'Click to request merge' +
            '</div>' +

        '</td>'
    );
}
document.addEventListener(
    'click',
    function(event)
    {
        const cell =
            event.target.closest(
                '.merge-occupied-cell'
            );

        if (!cell) {
            return;
        }

        const scheduleId =
            cell.dataset.scheduleId;

        if (!scheduleId) {
            return;
        }

        /*
         |--------------------------------------------------------------------------
         | GET THE SELECTED CLASS
         |--------------------------------------------------------------------------
         */

        const schedule =
            mergeScheduleView.querySelector(
                '[data-schedule-id="' +
                scheduleId +
                '"]'
            );

        if (!schedule) {
            return;
        }

        /*
         |--------------------------------------------------------------------------
         | GET CLASS INFORMATION
         |--------------------------------------------------------------------------
         */

        const course =
            schedule.querySelector(
                '.merge-class-course'
            )?.textContent.trim() || '';

        const subject =
            schedule.querySelector(
                '.merge-class-subject'
            )?.textContent.trim() || '';

        const instructor =
            schedule.querySelector(
                '.merge-class-instructor'
            )?.textContent.trim() || '';

      /*
 |--------------------------------------------------------------------------
 | GET PROGRAM FROM THE SELECTED CELL
 |--------------------------------------------------------------------------
 */

const existingProgram =
    cell.dataset.program &&
    cell.dataset.program.trim()
        ? cell.dataset.program.trim()
        : 'Unknown Program';

        window.selectedMergeSchedule = {
    id: scheduleId,

    course_code: course,

    subject: subject,

    instructor: instructor,

    program: existingProgram
};

console.log(
    'Selected schedule ID:',
    scheduleId
);

console.log(
    'Existing program:',
    existingProgram
);
        /*
|--------------------------------------------------------------------------
| PROGRAM TO MERGE
|--------------------------------------------------------------------------
*/

const programSelection =
    document.getElementById(
        'mergeProgramSelection'
    );

const mergeProgramSelect =
    document.getElementById(
        'mergeProgramSelect'
    );

if (
    !programSelection ||
    !mergeProgramSelect
) {
    alert(
        'Program selection is not available.'
    );

    return;
}

/*
|--------------------------------------------------------------------------
| SHOW PROGRAM SELECTION
|--------------------------------------------------------------------------
*/

programSelection.style.display = 'block';

mergeProgramSelect.innerHTML =
    '<option value="">Loading your programs...</option>';

loadMergePrograms();

programSelection.scrollIntoView({
    behavior: 'smooth',
    block: 'nearest'
});
/*
|--------------------------------------------------------------------------
| WHEN PROGRAM IS SELECTED
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| CONTINUE BUTTON — SUBMIT MERGE REQUEST
|--------------------------------------------------------------------------
*/

const confirmMergeProgram =
    document.getElementById(
        'confirmMergeProgram'
    );

if (confirmMergeProgram) {

    confirmMergeProgram.onclick =
        function()
        {
            const mergingProgram =
                mergeProgramSelect.value.trim();

            if (!mergingProgram) {

                alert(
                    'Please select a program first.'
                );

                return;
            }

            const selectedMerge =
                window.selectedMergeSchedule;

            if (!selectedMerge) {

                alert(
                    'No class has been selected for merging.'
                );

                return;
            }

            const course =
                selectedMerge.course_code || '';

            const subject =
                selectedMerge.subject || '';

            const instructor =
                selectedMerge.instructor ||
                'Unknown Instructor';

            const existingProgram =
                selectedMerge.program ||
                'Unknown Program';

            const scheduleId =
                selectedMerge.id;

            if (!scheduleId) {

                alert(
                    'The selected class is invalid.'
                );

                return;
            }

            const confirmed =
                confirm(
                    'MERGE EXISTING CLASS?\n\n' +

                    'Course Code: ' +
                    course +
                    '\n' +

                    'Subject: ' +
                    subject +
                    '\n' +

                    'Instructor: ' +
                    instructor +
                    '\n\n' +

                    'Existing Program:\n' +
                    existingProgram +
                    '\n\n' +

                    'Program to Add:\n' +
                    mergingProgram +
                    '\n\n' +

                    'Result after approval:\n' +
                    existingProgram +
                    ' / ' +
                    mergingProgram +
                    '\n\n' +

                    'The original room, instructor, and class schedule will remain unchanged.\n\n' +

                    'Send merge request to the owner department?'
                );

            if (!confirmed) {
                return;
            }

            confirmMergeProgram.disabled = true;

            fetch(
                "{{ route('chair.merge.request') }}",
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).getAttribute('content')
                    },

                    body: JSON.stringify({
                        schedule_id:
                            scheduleId,

                        program:
                            mergingProgram
                    })
                }
            )
            .then(function(response)
            {
                return response.json()
                    .then(function(data)
                    {
                        return {
                            ok:
                                response.ok,

                            data:
                                data
                        };
                    });
            })

            .then(function(result)
            {
                if (
                    !result.ok ||
                    !result.data.success
                ) {

                    alert(
                        result.data.message ||
                        'Unable to create merge request.'
                    );

                    return;
                }

                alert(
                    'Merge request submitted successfully!\n\n' +

                    'Course: ' +
                    course +
                    '\n' +

                    'Program: ' +
                    mergingProgram +
                    '\n' +

                    'Status: Pending'
                );

                programSelection.style.display =
                    'none';

                mergeProgramSelect.value = '';

                window.selectedMergeSchedule =
                    null;
            })

            .catch(function(error)
            {
                console.error(
                    'Merge request error:',
                    error
                );

                alert(
                    'An error occurred while submitting the merge request.'
                );
            })

            .finally(function()
            {
                confirmMergeProgram.disabled =
                    false;
            });
        };
}


    /*
|--------------------------------------------------------------------------
| CLOSE PROGRAM POPUP
|--------------------------------------------------------------------------
*/

const closeMergeProgramPopup =
    document.getElementById(
        'closeMergeProgramPopup'
    );

const cancelMergeProgram =
    document.getElementById(
        'cancelMergeProgram'
    );

const mergeProgramModal =
    document.getElementById(
        'mergeProgramSelection'
    );


/*
|--------------------------------------------------------------------------
| X BUTTON
|--------------------------------------------------------------------------
*/

if (closeMergeProgramPopup) {

    closeMergeProgramPopup.onclick =
        function()
        {
            mergeProgramModal.style.display =
                'none';

            mergeProgramSelect.value = '';
        };
}


/*
|--------------------------------------------------------------------------
| CANCEL BUTTON
|--------------------------------------------------------------------------
*/

if (cancelMergeProgram) {

    cancelMergeProgram.onclick =
        function()
        {
            mergeProgramModal.style.display =
                'none';

            mergeProgramSelect.value = '';
        };
}


/*
|--------------------------------------------------------------------------
| CLICK OUTSIDE POPUP
|--------------------------------------------------------------------------
*/

const mergeProgramOverlay =
    document.querySelector(
        '.program-popup-overlay'
    );

if (mergeProgramOverlay) {

    mergeProgramOverlay.onclick =
        function()
        {
            mergeProgramModal.style.display =
                'none';

            mergeProgramSelect.value = '';
        };
}
    }
);
// ------------------------------------------------------------
// INITIALIZE MANUAL BROWSE
// ------------------------------------------------------------

loadMergeDepartments();


// Existing time-cell events
attachTimeCellEvents();

// ============================================================
// MERGE CLASS TOGGLE
// ============================================================

const mergeClassToggleBtn =
    document.getElementById('mergeClassToggleBtn');

const mergeExistingClass =
    document.getElementById('mergeExistingClass');

if (
    mergeClassToggleBtn &&
    mergeExistingClass
) {
    mergeClassToggleBtn.addEventListener(
        'click',
        function () {

            if (
                mergeExistingClass.style.display === 'none'
            ) {

                // SHOW MERGE EXISTING CLASS
                mergeExistingClass.style.display = 'block';

            } else {

                // HIDE MERGE EXISTING CLASS
                mergeExistingClass.style.display = 'none';

            }

        }
    );
    const cancelScheduleConflict =
    document.getElementById(
        'cancelScheduleConflict'
    );

const scheduleConflictModal =
    document.getElementById(
        'scheduleConflictModal'
    );


if (
    cancelScheduleConflict &&
    scheduleConflictModal
) {

    cancelScheduleConflict.addEventListener(
        'click',
        function()
        {
            scheduleConflictModal.remove();
        }
    );

}
}
</script>

@php
    /*
    |--------------------------------------------------------------------------
    | RESERVATION AUTO-REFRESH DATA
    |--------------------------------------------------------------------------
    */
    $reservationRefreshData = ($reservations ?? collect())
        ->map(function ($reservation) {
            return [
                'status' => $reservation->status,
                'date' => $reservation->date
                    ? \Carbon\Carbon::parse($reservation->date)->format('Y-m-d')
                    : null,
                'end_time' => $reservation->end_time
                    ? \Carbon\Carbon::parse($reservation->end_time)->format('H:i:s')
                    : null,
            ];
        })
        ->values()
        ->toArray();
@endphp

<script>
/* ================================================================
   TEMPORARY RESERVATION AUTO-REFRESH
   ---------------------------------------------------------------
================================================================ */
(function () {
    const reservations = @json($reservationRefreshData);

    let nearestExpiry = null;

    reservations.forEach(function (reservation) {
        if (!reservation.date || !reservation.end_time) {
            return;
        }

        if (reservation.status !== 'Reserved') {
            return;
        }

        const target = new Date(
            reservation.date + 'T' + reservation.end_time
        );

        if (Number.isNaN(target.getTime())) {
            return;
        }

        if (target <= new Date()) {
            return;
        }

        if (!nearestExpiry || target < nearestExpiry) {
            nearestExpiry = target;
        }
    });

    if (nearestExpiry) {
        const delay = Math.max(
            1000,
            nearestExpiry.getTime() - Date.now() + 1000
        );

        setTimeout(function () {
            window.location.reload();
        }, delay);
    }
})();
</script>



</body>

</html>
