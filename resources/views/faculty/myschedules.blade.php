@include('footerheader.header')

<div class="wrapper">

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content faculty-my-schedules-view">

            <style>

                /* =========================================================
                   PAGE
                ========================================================= */

                body {
                    background: #f5f7f6;
                }

                .my-schedules-page {
                    width: 100%;
                    max-width: 1250px;
                    margin: 0 auto;
                    padding: 10px 0 40px;
                }


                /* =========================================================
                   HEADER
                ========================================================= */

                .schedule-header {
                    margin-bottom: 20px;
                }

                .schedule-title {
                    margin: 0;
                    color: #176b3a;
                    font-size: 25px;
                    font-weight: 900;
                }

                .schedule-subtitle {
                    margin-top: 5px;
                    color: #667085;
                    font-size: 13px;
                }


                /* =========================================================
                   FACULTY INFO
                ========================================================= */

                .faculty-info-card {
                    background: #ffffff;
                    border: 1px solid #dfe5e1;
                    border-radius: 12px;

                    padding: 15px 18px;
                    margin-bottom: 18px;

                    display: flex;
                    align-items: center;
                    justify-content: space-between;

                    gap: 15px;

                    box-shadow:
                        0 3px 10px rgba(16, 24, 40, .05);
                }

                .faculty-info-left {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .faculty-avatar {
                    width: 42px;
                    height: 42px;

                    border-radius: 50%;

                    background: #eaf6ef;
                    color: #176b3a;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    font-size: 17px;
                }

                .faculty-name {
                    font-size: 15px;
                    font-weight: 900;
                    color: #1f2937;
                }

                .faculty-label {
                    margin-top: 3px;
                    font-size: 11px;
                    color: #667085;
                }

                .term-badge {
                    padding: 7px 11px;

                    border-radius: 999px;

                    background: #eaf6ef;
                    color: #176b3a;

                    font-size: 10px;
                    font-weight: 900;

                    white-space: nowrap;
                }


                /* =========================================================
                   TABLE WRAPPER
                ========================================================= */

                .excel-wrapper {
                    width: 100%;
                    overflow-x: auto;

                    background: #ffffff;

                    border: 1px solid #dfe5e1;
                    border-radius: 12px;

                    box-shadow:
                        0 4px 15px rgba(16, 24, 40, .06);
                }


                /* =========================================================
                   TABLE
                ========================================================= */

                .excel-table {
                    width: 100%;
                    min-width: 1300px;

                    border-collapse: collapse;
                    table-layout: fixed;
                }

                .excel-table th,
                .excel-table td {
                    border: 1px solid #dfe5e1;
                    box-sizing: border-box;
                }


                /* =========================================================
                   HEADER
                ========================================================= */

                .excel-table thead th {
                    height: 48px;

                    padding: 8px;

                    background: #176b3a;
                    color: #ffffff;

                    font-size: 11px;
                    font-weight: 900;

                    text-align: center;
                    vertical-align: middle;
                }


                /* =========================================================
                   TIME COLUMN
                ========================================================= */

                .time-column {
                    width: 125px;
                    min-width: 125px;
                    max-width: 125px;

                    background: #f7faf8;
                    color: #344054;

                    font-size: 11px;
                    font-weight: 900;

                    text-align: center;
                    vertical-align: middle;

                    white-space: nowrap;
                }


                /* =========================================================
                   SCHEDULE CELL
                ========================================================= */

                .schedule-cell {
                    height: 100px;

                    padding: 6px;

                    background: #ffffff;

                    vertical-align: top;
                    text-align: left;
                }


                /* =========================================================
                   EMPTY CELL
                ========================================================= */

                .empty-cell {
                    min-height: 86px;

                    display: flex;

                    align-items: center;
                    justify-content: center;

                    color: #98a2b3;

                    font-size: 10px;
                    font-style: italic;
                }


                /* =========================================================
                   CLASS ENTRY
                ========================================================= */

                .class-entry {
                    position: relative;

                    width: 100%;
                    min-height: 84px;

                    padding: 9px;
                    padding-right: 54px;

                    box-sizing: border-box;

                    border-radius: 8px;

                    background: #f3faf6;
                    border: 1px solid #cce6d4;

                    text-align: left;

                    overflow: hidden;
                }


                /* =========================================================
                   TEMPORARY RESERVATION ENTRY
                ========================================================= */

                .temporary-reservation-entry {
                    position: relative;
                    width: 100%;
                    min-height: 84px;
                    padding: 9px;
                    box-sizing: border-box;
                    border-radius: 8px;
                    background: #fff8e8;
                    border: 1px solid #f2d18b;
                    text-align: left;
                    overflow: hidden;
                }

                .temporary-reservation-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    padding: 4px 7px;
                    border-radius: 999px;
                    background: #fff0c7;
                    color: #8a5a00;
                    font-size: 8px;
                    font-weight: 900;
                    text-transform: uppercase;
                    letter-spacing: .3px;
                }

                .temporary-reservation-course {
                    margin-top: 7px;
                    color: #8a5a00;
                    font-size: 11px;
                    font-weight: 900;
                    line-height: 1.2;
                }

                .temporary-reservation-detail {
                    margin-top: 4px;
                    color: #667085;
                    font-size: 9px;
                    line-height: 1.3;
                }

                .temporary-reservation-time {
                    margin-top: 5px;
                    color: #344054;
                    font-size: 9px;
                    font-weight: 800;
                }

                /* =========================================================
                   COURSE
                ========================================================= */

                .class-course {
                    color: #176b3a;

                    font-size: 12px;
                    font-weight: 900;

                    line-height: 1.2;

                    padding-right: 5px;
                }


                /* =========================================================
                   SUBJECT
                ========================================================= */

                .class-subject {
                    margin-top: 3px;

                    color: #1f2937;

                    font-size: 10.5px;
                    font-weight: 700;

                    line-height: 1.2;
                }


                /* =========================================================
                   PROGRAM
                ========================================================= */

                .class-detail {
                    margin-top: 4px;

                    color: #667085;

                    font-size: 9px;

                    line-height: 1.25;
                }

                .class-detail strong {
                    color: #344054;
                }


                /* =========================================================
                   YEAR LEVEL - TOP RIGHT
                ========================================================= */

                .year-level {
                    position: absolute;

                    top: 7px;
                    right: 7px;

                    max-width: 48px;

                    padding: 3px 6px;

                    border-radius: 999px;

                    background: #e8f4ec;
                    color: #176b3a;

                    font-size: 7.5px;
                    font-weight: 900;

                    line-height: 1;

                    white-space: nowrap;

                    text-align: center;
                }


                /* =========================================================
                   ROOM
                ========================================================= */

                .room-name {
                    margin-top: 5px;

                    display: flex;

                    align-items: center;
                    gap: 4px;

                    color: #344054;

                    font-size: 9px;
                    font-weight: 800;

                    line-height: 1.2;
                }

                .room-name i {
                    color: #176b3a;
                }


                /* =========================================================
                   BUILDING
                ========================================================= */

                .building-name {
                    margin-top: 3px;

                    color: #667085;

                    font-size: 8.5px;

                    line-height: 1.2;
                }


                /* =========================================================
                   NO SCHEDULE
                ========================================================= */

                .empty-schedule-page {
                    background: #ffffff;

                    border: 1px dashed #cfd8d2;

                    border-radius: 12px;

                    padding: 60px 20px;

                    text-align: center;

                    color: #667085;
                }

                .empty-schedule-page i {
                    display: block;

                    margin-bottom: 12px;

                    color: #176b3a;

                    font-size: 34px;
                }

                .empty-schedule-page strong {
                    display: block;

                    color: #176b3a;

                    font-size: 15px;
                }

                .empty-schedule-page span {
                    display: block;

                    margin-top: 5px;

                    font-size: 11px;
                }


                /* =========================================================
                   RESPONSIVE
                ========================================================= */

                @media(max-width: 800px) {

                    .my-schedules-page {
                        padding: 10px 8px 30px;
                    }

                    .faculty-info-card {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                }

                    .schedule-cell.merged-schedule-cell{
            vertical-align:middle;
            text-align:center;
        }

        .schedule-cell.merged-schedule-cell .class-entry{
            width:100%;
        }

        /* Polished weekly timetable styling */
        .faculty-my-schedules-view { background:#f5f8f6; }
        .my-schedules-page { max-width:1320px; padding:12px 0 42px; }
        .schedule-header {
            position:relative; margin-bottom:18px; padding:25px 28px; overflow:hidden;
            border:1px solid #dfeae2; border-radius:20px;
            background:linear-gradient(115deg,#fff 0%,#f0f8f2 100%);
            box-shadow:0 8px 24px rgba(22,73,45,.045);
        }
        .schedule-header::before { display:block; margin-bottom:8px; color:#56816a; content:"FACULTY WORKSPACE  /  PERSONAL TIMETABLE"; font-size:9px; font-weight:800; letter-spacing:.14em; }
        .schedule-header::after { position:absolute; top:-62px; right:-30px; width:210px; height:210px; border:1px solid rgba(28,117,74,.10); border-radius:50%; box-shadow:0 0 0 24px rgba(28,117,74,.035),0 0 0 48px rgba(28,117,74,.025); content:""; }
        .schedule-title { color:#193e2d; font-size:27px; font-weight:760; letter-spacing:-.035em; }
        .schedule-subtitle { max-width:620px; color:#708078; font-size:12px; line-height:1.65; }
        .faculty-info-card { position:relative; margin-bottom:16px; padding:14px 17px; border-color:#e2ebe5; border-radius:15px; box-shadow:0 4px 14px rgba(25,57,39,.04); }
        .faculty-avatar { width:42px; height:42px; border:1px solid #dce9df; background:#edf6ef; color:#19764d; }
        .faculty-name { color:#244b35; font-size:14px; font-weight:750; }
        .faculty-label { color:#829087; font-size:10px; }
        .term-badge { border:1px solid #dce9df; background:#f1f8f3; color:#19764d; font-size:10px; font-weight:750; }
        .excel-wrapper { border-color:#e2ebe5; border-radius:16px; box-shadow:0 7px 22px rgba(25,57,39,.05); }
        .excel-table { border-collapse:separate; border-spacing:0; }
        .excel-table th,.excel-table td { border-color:#e5ede7; }
        .excel-table thead th { height:48px; background:#19764d; color:#fff; font-size:10px; letter-spacing:.055em; }
        .excel-table thead th:first-child { background:#145f3d; }
        .time-column { background:#f7faf8; color:#53645a; font-size:10px; }
        .schedule-cell { height:102px; background:#fff; }
        .empty-cell { min-height:88px; color:#b0bbb3; font-size:9px; }
        .class-entry { min-height:86px; border-color:#d8e9dc; border-radius:10px; background:linear-gradient(145deg,#f2f9f4,#fbfdfb); box-shadow:inset 3px 0 #2d8a5c; }
        .class-course { color:#176e48; font-size:11px; }
        .class-subject { color:#2d4537; font-size:10px; }
        .class-detail { color:#75837a; font-size:8.5px; }
        .class-detail strong { color:#516258; }
        .year-level { background:#e7f2e9; color:#176e48; }
        .room-name { color:#456050; }
        .room-name i { color:#19764d; }
        .building-name { color:#869289; }
        .temporary-reservation-entry { min-height:86px; border-color:#f0dca9; border-radius:10px; background:linear-gradient(145deg,#fff8e8,#fffdf8); box-shadow:inset 3px 0 #d69a22; }
        .temporary-reservation-badge { background:#fff0c7; color:#8a5a00; }
        .my-room-swap-entry { display:grid; gap:4px; margin-top:8px; padding:9px; border:1px solid #c8d9f2; border-radius:9px; background:#f2f7ff; color:#425c83; font-size:9px; line-height:1.35; }
        .my-room-swap-entry strong { color:#385786; font-size:9px; }
        .my-schedule-override-entry { display:grid; gap:4px; margin-top:8px; padding:9px; border:1px solid #d3a749; border-radius:9px; background:#fff6df; color:#513b12; font-size:12px; line-height:1.4; }
        .my-schedule-override-entry strong { color:#533c11; font-size:12px; }
        .empty-schedule-page { border-color:#dce7df; border-radius:16px; box-shadow:0 5px 16px rgba(25,57,39,.035); }
        .empty-schedule-page i,.empty-schedule-page strong { color:#19764d; }
        /* Keep timetable rows calm when several dated items share one weekly slot. */
        .excel-table { min-width: 1380px; }
        .excel-table thead th { position: sticky; top: 0; z-index: 3; }
        .schedule-cell { height: 118px; padding: 6px; vertical-align: top; }
        .schedule-cell-stack { display: grid; max-height: 154px; gap: 5px; overflow-y: auto; overflow-x: hidden; padding: 1px 3px 1px 1px; scrollbar-width: thin; scrollbar-color: #c4d8ca transparent; }
        .schedule-cell-stack::-webkit-scrollbar { width: 5px; }
        .schedule-cell-stack::-webkit-scrollbar-thumb { border-radius: 9px; background: #c4d8ca; }
        .schedule-cell .class-entry, .schedule-cell .temporary-reservation-entry { min-height: 0; padding: 8px 9px; border-radius: 9px; }
        .schedule-cell .class-entry { padding-right: 56px; }
        .schedule-cell .class-course, .schedule-cell .temporary-reservation-course { margin-top: 0; font-size: 10px; line-height: 1.3; }
        .schedule-cell .class-subject { margin-top: 2px; font-size: 9px; line-height: 1.3; }
        .schedule-cell .class-detail, .schedule-cell .temporary-reservation-detail { margin-top: 3px; font-size: 8px; line-height: 1.25; }
        .schedule-cell .room-name { margin-top: 4px; font-size: 8px; }
        .schedule-cell .building-name { margin-top: 2px; font-size: 7.5px; }
        .schedule-cell .year-level { top: 6px; right: 6px; }
        .schedule-cell .temporary-reservation-badge { margin-bottom: 5px; padding: 3px 6px; font-size: 7px; }
        .schedule-cell .temporary-reservation-time { margin-top: 4px; font-size: 8px; }
        .my-room-swap-entry { gap: 2px; margin-top: 5px; padding: 6px 7px; font-size: 7.5px; line-height: 1.3; }
        .my-room-swap-entry strong { font-size: 8px; }
        .schedule-cell .empty-cell { min-height: 100px; }
        @media(max-width:800px) {
            .excel-table { min-width: 1240px; }
            .schedule-cell-stack { max-height: 138px; }
        }
        @media(max-width:800px) {
            .my-schedules-page { padding:4px 0 28px; }
            .schedule-header { padding:21px 19px; border-radius:16px; }
            .schedule-header::after { right:-115px; }
            .schedule-title { font-size:23px; }
            .faculty-info-card { align-items:flex-start; }
            .excel-wrapper { border-radius:13px; }
        }

        /* Faculty timetable refinement: clear title hierarchy and legible schedule entries. */
        body.portal-shell.faculty-portal .main-layout > .content.faculty-my-schedules-view {
            min-height:100vh;
            padding:128px clamp(20px,3vw,42px) 46px !important;
            background:
                radial-gradient(ellipse at 88% 2%,rgba(215,195,145,.25),transparent 25%),
                radial-gradient(ellipse at 8% 96%,rgba(204,224,208,.36),transparent 30%),
                linear-gradient(145deg,#edf2ec 0%,#f8f7f2 58%,#f1f0e8 100%);
            color:#263a2f;
            font-family:'DM Sans',Arial,sans-serif;
        }
        .faculty-my-schedules-view,
        .faculty-my-schedules-view * { font-family:'DM Sans',Arial,sans-serif; }
        .faculty-my-schedules-view .my-schedules-page { width:min(100%,1480px); max-width:1480px; margin:0 auto; padding:0 0 42px; }
        .faculty-my-schedules-view .schedule-header {
            position:relative;
            isolation:isolate;
            overflow:hidden;
            min-height:205px;
            display:flex;
            flex-direction:column;
            justify-content:center;
            margin:0 0 21px;
            padding:clamp(30px,4vw,44px) clamp(25px,4vw,46px) !important;
            border:1px solid rgba(232,215,175,.22);
            border-radius:24px;
            background:radial-gradient(circle at 78% 18%,rgba(217,196,143,.17),transparent 25%),linear-gradient(118deg,#153a32 0%,#215440 58%,#37684e 100%);
            box-shadow:0 20px 44px rgba(24,58,43,.16);
        }
        .faculty-my-schedules-view .schedule-header::before {
            position:relative;
            z-index:1;
            margin:0 0 12px;
            color:#e2ca8d;
            content:"FACULTY WORKSPACE  /  PERSONAL TIMETABLE";
            font-size:11px;
            font-weight:850;
            letter-spacing:.16em;
        }
        .faculty-my-schedules-view .schedule-header::after {
            top:-150px;
            right:95px;
            width:335px;
            height:335px;
            border-color:rgba(244,232,200,.23);
            box-shadow:0 0 0 27px rgba(244,232,200,.035),0 0 0 63px rgba(244,232,200,.026),0 0 0 102px rgba(244,232,200,.018);
        }
        .faculty-my-schedules-view .schedule-title { position:relative; z-index:1; margin:0 0 10px; color:#fff !important; font:800 clamp(34px,3.2vw,44px)/1.12 'Manrope','DM Sans',sans-serif; letter-spacing:-.045em; }
        .faculty-my-schedules-view .schedule-subtitle { position:relative; z-index:1; max-width:650px; margin:0; color:#e0e9e1; font-size:15px; font-weight:550; line-height:1.65; }
        .faculty-my-schedules-view .faculty-info-card { min-height:78px; margin-bottom:17px; padding:15px 19px; border-color:#dce6dc; border-radius:15px; background:linear-gradient(110deg,#fff,#fbfcf9); box-shadow:0 7px 20px rgba(31,57,43,.045); }
        .faculty-my-schedules-view .faculty-info-left { gap:13px; }
        .faculty-my-schedules-view .faculty-avatar { width:46px; height:46px; border:1px solid #e2e9df; background:#edf3eb; color:#285940; font-size:18px; }
        .faculty-my-schedules-view .faculty-name { color:#203d30; font-size:15px; font-weight:850; }
        .faculty-my-schedules-view .faculty-label { margin-top:4px; color:#64746a; font-size:12px; font-weight:600; }
        .faculty-my-schedules-view .term-badge { padding:8px 12px; border:1px solid #d9e6d8; border-radius:999px; background:#edf5eb; color:#24563d; font-size:12px; font-weight:850; }
        .faculty-my-schedules-view .excel-wrapper { position:relative; max-height:calc(100vh - 310px); min-height:420px; overflow:auto; border:1px solid #dce6dc; border-radius:17px; background:#fff; box-shadow:0 10px 28px rgba(31,57,43,.06); scrollbar-color:#b9cdbd #f5f8f4; scrollbar-width:thin; }
        /* The shared sheet controls intentionally make other schedules fit their container.
           Keep this personal timetable at its readable spreadsheet width and scroll on small screens. */
        .faculty-my-schedules-view [data-fit-schedule-container] { min-width:0 !important; max-width:100% !important; overflow:auto !important; scrollbar-width:thin !important; }
        .faculty-my-schedules-view table.excel-table[data-fit-schedule] { display:table !important; width:1320px !important; min-width:1320px !important; max-width:none !important; table-layout:fixed !important; }
        .faculty-my-schedules-view table.excel-table[data-fit-schedule] th,
        .faculty-my-schedules-view table.excel-table[data-fit-schedule] td { min-width:0 !important; max-width:none !important; overflow-wrap:normal; }
        .faculty-my-schedules-view table.excel-table[data-fit-schedule] .time-column { width:120px !important; min-width:120px !important; max-width:120px !important; }
        .faculty-my-schedules-view .my-schedule-block-layer { position:absolute; inset:0; z-index:8; pointer-events:none; }
        .faculty-my-schedules-view .my-schedule-source-hidden { visibility:hidden !important; height:0 !important; min-height:0 !important; max-height:0 !important; margin:0 !important; padding:0 !important; border-width:0 !important; overflow:hidden !important; }
        .faculty-my-schedules-view .my-schedule-duration-block { position:absolute !important; min-height:0 !important; max-height:none; margin:0; padding:6px 8px; overflow:hidden; box-sizing:border-box; z-index:1; }
        .faculty-my-schedules-view .my-schedule-duration-block { pointer-events:none; cursor:default; }
        .faculty-my-schedules-view .my-schedule-duration-block .my-schedule-majors-trigger { position:relative; z-index:3; pointer-events:auto; }
        .faculty-my-schedules-view .my-schedule-majors-trigger { display:inline-flex; align-items:center; width:max-content; max-width:100%; margin-top:5px; padding:4px 8px; border:1px solid #cfe2d2; border-radius:999px; background:#edf6ee; color:#245940; font-family:inherit; font-size:9px; font-weight:800; line-height:1.2; white-space:nowrap; cursor:pointer; }
        .faculty-my-schedules-view .my-schedule-majors-trigger:hover,.faculty-my-schedules-view .my-schedule-majors-trigger:focus-visible { border-color:#78a987; background:#e0efe2; outline:2px solid rgba(50,112,77,.18); outline-offset:1px; }
        .my-schedule-majors-overlay[hidden] { display:none !important; }
        body.my-schedule-majors-open { overflow:hidden; }
        .my-schedule-majors-overlay { position:fixed; inset:0; z-index:1002000; display:grid; place-items:center; padding:18px; background:rgb(18 31 25 / 48%); backdrop-filter:blur(3px); }
        .my-schedule-majors-dialog { width:min(480px,100%); max-height:min(78vh,620px); overflow:auto; border:1px solid #dce7dc; border-radius:16px; background:#fff; color:#263b30; box-shadow:0 24px 70px rgb(15 35 25 / 25%); }
        .my-schedule-majors-dialog header { position:sticky; top:0; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:15px 18px; border-bottom:1px solid #e5ece5; background:#fff; }
        .my-schedule-majors-dialog h2 { margin:0; color:#173b33; font-size:17px; font-weight:850; }
        .my-schedule-majors-close { width:36px; height:36px; border:1px solid #dce5dc; border-radius:9px; background:#f6f8f5; color:#385744; font-size:23px; cursor:pointer; }
        .my-schedule-majors-list { display:grid; gap:7px; margin:0; padding:16px 18px 19px; list-style:none; }
        .my-schedule-majors-list li { padding:9px 11px; border:1px solid #e2ebe2; border-radius:8px; background:#f7faf6; color:#263b30; font-size:13px; line-height:1.4; overflow-wrap:anywhere; }
        .my-schedule-majors-dialog :focus-visible { outline:2px solid #32704d; outline-offset:2px; }
        @media(max-width:520px) { .my-schedule-majors-overlay { align-items:end; padding:8px; } .my-schedule-majors-dialog { width:100%; max-height:85vh; border-radius:15px 15px 10px 10px; } }
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] { padding:5px 6px !important; }
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .class-course { padding-right:64px; font-size:9px; line-height:1.05; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .class-time { top:5px !important; right:5px !important; max-width:calc(100% - 10px); padding:2px 4px; font-size:7.5px; line-height:1.1; }
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .class-subject { margin-top:2px; font-size:8px; line-height:1.08; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .class-detail,
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .room-name { margin-top:2px; font-size:7.4px; line-height:1.08; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"].temporary-reservation-entry { gap:2px; padding:4px 5px; }
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .temporary-reservation-detail,
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .temporary-reservation-meta { display:none; }
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .temporary-reservation-badge,
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .temporary-reservation-course,
        .faculty-my-schedules-view .my-schedule-duration-block[data-duration-minutes="30"] .temporary-reservation-time { font-size:7.5px; line-height:1.1; }
        .faculty-my-schedules-view .excel-wrapper::-webkit-scrollbar { width:10px; height:10px; }
        .faculty-my-schedules-view .excel-wrapper::-webkit-scrollbar-thumb { border:2px solid #f5f8f4; border-radius:99px; background:#b9cdbd; }
        .faculty-my-schedules-view .excel-table { width:100%; min-width:1320px; border-spacing:0; border-collapse:separate; background:#fff; }
        .faculty-my-schedules-view .excel-table th,
        .faculty-my-schedules-view .excel-table td { border-color:#e3ebe3; }
        .faculty-my-schedules-view .excel-table thead th { height:54px; padding:11px 9px; background:#245940; color:#fff; font-size:12px; font-weight:850; letter-spacing:.055em; }
        .faculty-my-schedules-view .excel-table thead th:first-child,
        .faculty-my-schedules-view .excel-table thead th:nth-child(5) { background:#173b33; }
        .faculty-my-schedules-view .time-column { width:120px; min-width:120px; max-width:120px; background:#f4f7f2; color:#405a49; font-size:11px; font-weight:850; }
        .faculty-my-schedules-view .schedule-cell { height:122px; min-height:122px; max-height:122px; padding:7px; background:#fff; }
        .faculty-my-schedules-view .schedule-cell-stack { height:104px; min-height:0; max-height:104px; gap:6px; padding:2px 4px 2px 1px; overflow:auto; }
        .faculty-my-schedules-view .empty-cell { min-height:102px; color:#99a79c; font-size:11px; font-style:normal; font-weight:600; }
        .faculty-my-schedules-view .class-entry { min-height:100px; padding:10px; padding-right:10px; border-color:#d7e7d9; border-radius:11px; background:linear-gradient(145deg,#f0f7ef,#fbfdf9); box-shadow:inset 3px 0 #32704d; }
        .faculty-my-schedules-view .class-entry.room-swapped-temporary { border-color:#c9a7e8 !important; background:linear-gradient(145deg,#f6edff,#fcf9ff) !important; box-shadow:inset 4px 0 #7e22ce,0 2px 7px rgba(126,34,206,.09) !important; }
        .faculty-my-schedules-view .class-course { margin-top:0; padding-right:82px; color:#1f6844; font-size:13px; font-weight:900; line-height:1.2; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .class-subject { margin-top:5px; padding-right:4px; color:#294437; font-size:11px; font-weight:800; line-height:1.3; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .class-time { position:absolute !important; top:7px !important; right:7px !important; z-index:3; width:max-content; max-width:calc(100% - 14px); margin:0 !important; padding:4px 6px; border:1px solid #dce8dc; border-radius:5px; background:#fff; color:#294b38; font-size:9.5px; font-weight:900; line-height:1.2; text-align:right; white-space:nowrap; }
        .faculty-my-schedules-view .class-detail { display:flex; flex-wrap:wrap; gap:5px; margin-top:7px; color:#596e60; font-size:10px; font-weight:650; line-height:1.3; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .class-meta-item { display:inline-flex; flex-wrap:wrap; gap:3px; min-width:0; padding:3px 6px; border:1px solid #e1e9df; border-radius:5px; background:#f7faf5; }
        .faculty-my-schedules-view .class-detail strong { color:#355343; font-weight:850; }
        .faculty-my-schedules-view .class-detail-separator,.faculty-my-schedules-view .room-building-separator { padding:0 2px; color:#94a398; }
        .faculty-my-schedules-view .room-name { display:block; margin-top:6px; padding-top:5px; border-top:1px solid #e8eee7; color:#365644; font-size:10px; font-weight:850; line-height:1.35; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .room-name i { color:#32704d; }
        .faculty-my-schedules-view .temporary-reservation-entry { display:grid; align-content:start; gap:5px; min-width:0; min-height:0; max-height:none; padding:8px 9px; overflow:visible; border-color:#ead9ad; border-radius:10px; background:linear-gradient(145deg,#fff7e6,#fffdf8); box-shadow:inset 3px 0 #c99736; }
        .faculty-my-schedules-view .my-reservation-date-line { display:block; min-width:0; color:#786744; font-size:9px; line-height:1.25; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .my-reservation-details-trigger { position:relative; z-index:3; width:max-content; max-width:100%; margin-top:3px; padding:4px 7px; border:1px solid #dfc982; border-radius:999px; background:#fff3d5; color:#755514; font-family:inherit; font-size:9px; font-weight:800; line-height:1.2; cursor:pointer; pointer-events:auto; }
        .my-reservation-details-overlay[hidden] { display:none !important; }
        .my-reservation-details-overlay { position:fixed; inset:0; z-index:1002001; display:grid; place-items:center; padding:18px; background:rgb(18 31 25 / 48%); backdrop-filter:blur(3px); }
        .my-reservation-details-dialog { width:min(520px,100%); max-height:min(82vh,640px); overflow:auto; border:1px solid #e7d7ad; border-radius:16px; background:#fff; color:#34483a; box-shadow:0 24px 70px rgb(15 35 25 / 25%); }
        .my-reservation-details-dialog header { position:sticky; top:0; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:15px 18px; border-bottom:1px solid #eee7d6; background:#fffcf4; }
        .my-reservation-details-dialog h2 { margin:0; color:#604817; font-size:17px; font-weight:850; }
        .my-reservation-details-dialog header button { width:36px; height:36px; border:1px solid #e5dcc6; border-radius:9px; background:#fff; color:#685d45; font-size:23px; cursor:pointer; }
        .my-reservation-details-list { display:grid; gap:8px; padding:15px 18px 18px; }
        .my-reservation-detail-card { display:grid; gap:5px; padding:11px; border:1px solid #eee4ca; border-radius:10px; background:#fffdf8; color:#5d5138; font-size:12px; }
        .my-reservation-detail-card strong { color:#79551a; }
        .my-reservation-detail-card :focus-visible { outline:2px solid #32704d; outline-offset:2px; }
        .faculty-my-schedules-view .temporary-reservation-header { display:grid; grid-template-columns:minmax(0,1fr); align-items:start; gap:4px; min-width:0; }
        .faculty-my-schedules-view .temporary-reservation-badge { justify-self:start; min-width:0; padding:3px 6px; border-radius:5px; background:#fff0c7; color:#79551a; font-size:9px; font-weight:900; white-space:normal; }
        .faculty-my-schedules-view .temporary-reservation-course { min-width:0; margin:0; color:#79551a; font-size:12px; font-weight:900; line-height:1.25; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .temporary-reservation-detail { min-width:0; margin:0; color:#4f5f53; font-size:10px; font-weight:750; line-height:1.35; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .temporary-reservation-meta { display:flex; flex-wrap:wrap; gap:5px; min-width:0; }
        .faculty-my-schedules-view .temporary-reservation-meta span { padding:3px 6px; border:1px solid #eadfbe; border-radius:5px; background:#fffdf7; color:#66572f; font-size:9px; font-weight:850; line-height:1.3; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .temporary-reservation-meta strong { margin-right:2px; color:#79551a; }
        .faculty-my-schedules-view .temporary-reservation-time { margin:0; color:#354d3d; font-size:9.5px; font-weight:900; line-height:1.35; overflow-wrap:anywhere; }
        .faculty-my-schedules-view .temporary-reservation-more { margin:0; color:#67552e; font-size:8px; line-height:1.2; }
        .faculty-my-schedules-view .temporary-reservation-more summary { cursor:pointer; font-weight:850; }
        .faculty-my-schedules-view .my-room-swap-entry { gap:4px; margin-top:7px; padding:8px; border-color:#dec8f2; border-radius:10px; background:#f8f1ff; color:#65418a; font-size:10px; font-weight:650; line-height:1.4; }
        .faculty-my-schedules-view .my-room-swap-entry strong { color:#6b21a8; font-size:10px; font-weight:900; }
        .faculty-my-schedules-view .my-schedule-override-entry { gap:5px; margin-top:7px; padding:9px; border-color:#e6d19b; border-radius:10px; background:#fff7e2; color:#5b451d; font-size:11px; font-weight:600; line-height:1.45; }
        .faculty-my-schedules-view .my-schedule-override-entry strong { color:#604819; font-size:11px; font-weight:850; }
        .faculty-my-schedules-view .my-schedule-override-panel { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:9px; margin:0 0 12px; padding:13px; border:1px solid #f0d2d0; border-radius:12px; background:#fff8f7; }
        .faculty-my-schedules-view .my-schedule-override-panel[hidden] { display:none; }
        .faculty-my-schedules-view .my-schedule-override-panel-heading { grid-column:1/-1; margin:0; color:#8e312d; font-size:12px; font-weight:850; }
        .faculty-my-schedules-view .my-schedule-override-panel-hint { grid-column:1/-1; margin:-5px 0 2px; color:#765553; font-size:10px; }
        .faculty-my-schedules-view .my-schedule-override-entry { display:grid; width:100%; gap:3px; margin:0; padding:9px 10px; border:1px solid #efc4c1; border-left:4px solid #c63f3a; border-radius:8px; background:#fff; color:#63302d; font:inherit; font-size:11px; font-weight:500; line-height:1.4; text-align:left; cursor:pointer; }
        .faculty-my-schedules-view .my-schedule-override-entry:hover,.faculty-my-schedules-view .my-schedule-override-entry[aria-pressed="true"] { border-color:#c63f3a; background:#ffe9e7; box-shadow:0 0 0 2px rgba(198,63,58,.12); }
        .faculty-my-schedules-view .my-schedule-override-entry strong { color:#b33330; font-size:11px; font-weight:900; }
        .faculty-my-schedules-view .my-schedule-override-match-status { display:block; margin-top:2px; color:#7b4d48; font-size:10px; font-weight:750; }
        .faculty-my-schedules-view .my-schedule-duration-block.my-schedule-override-overlap { border-color:#d87973 !important; background:linear-gradient(145deg,#fff0ef,#fff8f7) !important; box-shadow:inset 4px 0 #c63f3a,0 3px 9px rgba(138,36,32,.1) !important; }
        .faculty-my-schedules-view .my-schedule-override-day-highlight { position:absolute; top:0; bottom:0; z-index:0; border-left:4px solid #c63f3a; background:rgba(198,63,58,.26); pointer-events:none; }
        .faculty-my-schedules-view .my-schedule-duration-block > :not(.my-schedule-override-day-highlight) { position:relative; z-index:1; }
        .faculty-my-schedules-view .empty-schedule-page { min-height:330px; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:38px 22px; border:1px dashed #d4e1d5; border-radius:18px; background:rgba(255,255,255,.88); box-shadow:0 8px 24px rgba(31,57,43,.04); }
        .faculty-my-schedules-view .empty-schedule-page i { width:58px; height:58px; display:grid; place-items:center; margin:0 0 15px; border:1px solid #e1e9de; border-radius:17px; background:#edf3eb; color:#32704d; font-size:23px; }
        .faculty-my-schedules-view .empty-schedule-page strong { color:#294437; font-size:17px; font-weight:850; }
        .faculty-my-schedules-view .empty-schedule-page span { max-width:460px; margin-top:7px; color:#65756a; font-size:13px; font-weight:550; line-height:1.6; }
        @media(max-width:800px) {
            body.portal-shell.faculty-portal .main-layout > .content.faculty-my-schedules-view { min-height:calc(100vh - 64px); padding:22px 17px 32px !important; }
            .faculty-my-schedules-view .my-schedules-page { padding:0 0 28px; }
            .faculty-my-schedules-view .schedule-header { min-height:0; padding:27px 23px !important; border-radius:19px; }
            .faculty-my-schedules-view .schedule-header::after { top:-85px; right:-90px; width:260px; height:260px; }
            .faculty-my-schedules-view .schedule-title { font-size:34px; }
            .faculty-my-schedules-view .schedule-subtitle { font-size:14px; }
            .faculty-my-schedules-view .faculty-info-card { align-items:flex-start; }
            .faculty-my-schedules-view .excel-wrapper { max-height:calc(100vh - 265px); min-height:360px; border-radius:14px; }
            .faculty-my-schedules-view .excel-table { min-width:1320px; }
        }
        @media(max-width:520px) {
            .faculty-my-schedules-view .schedule-header { padding:24px 20px !important; }
            .faculty-my-schedules-view .schedule-title { font-size:31px; }
            .faculty-my-schedules-view .schedule-header::before { font-size:9px; }
            .faculty-my-schedules-view .faculty-info-card { flex-direction:column; gap:12px; padding:14px; }
            .faculty-my-schedules-view .faculty-info-left { align-items:flex-start; }
            .faculty-my-schedules-view .faculty-avatar { width:40px; height:40px; }
            .faculty-my-schedules-view .term-badge { white-space:normal; }
        }

</style>


            <div class="my-schedules-page">


                <!-- =====================================================
                     PAGE HEADER
                ====================================================== -->

                <div class="schedule-header">

                    <h1 class="schedule-title">
                        My Schedules
                    </h1>

                    <p class="schedule-subtitle">
                        View only the classes assigned to your instructor account.
                    </p>

                </div>


                <!-- =====================================================
                     FACULTY INFORMATION
                ====================================================== -->

                <div class="faculty-info-card">

                    <div class="faculty-info-left">

                        <div class="faculty-avatar">

                            <i class="fa-solid fa-user"></i>

                        </div>

                        <div>

                            <div class="faculty-name">

                                {{ Auth::user()->name }}

                            </div>

                            <div class="faculty-label">

                                Logged-in Instructor Schedule

                            </div>

                        </div>

                    </div>


                    @if($activeTerm)

                        <div class="term-badge">

                            {{ $activeTerm->academic_year }}

                            •

                            {{ $activeTerm->semester }}

                        </div>

                    @else

                        <div class="term-badge">

                            Current Schedule

                        </div>

                    @endif

                </div>


                @if(($mySchedules ?? collect())->count() || ($temporaryReservations ?? collect())->count() || ($roomSwapRequests ?? collect())->count())

                    @php
                        // Keep Saturday visible in the weekly sheet even when it is empty,
                        // so the timetable layout stays consistent for every instructor.
                        $hasSaturdaySchedule = true;
                    @endphp


                    <!-- =====================================================
                         EXCEL TABLE
                    ====================================================== -->

                    <div class="excel-wrapper schedule-sheet" data-schedule-sheet data-fit-schedule-container>

                        @include('components.schedule-sheet-controls')

                        @php
                            $defaultTimes = [
                                '7:00-8:00', '8:00-9:00', '9:00-10:00', '10:00-11:00', '11:00-12:00',
                                '1:00-2:00', '2:00-3:00', '3:00-4:00', '4:00-5:00', '5:00-6:00', '6:00-7:00',
                            ];
                            $normalizeScheduleDay = function ($day) {
                                $day = strtoupper(substr(trim((string) $day), 0, 3));
                                return in_array($day, ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'], true) ? $day : null;
                            };
                            $clockMinutes = function ($value) {
                                if (!$value) return null;
                                try {
                                    $clock = \Carbon\Carbon::parse($value);
                                    return ($clock->hour * 60) + $clock->minute;
                                } catch (\Throwable $exception) {
                                    return null;
                                }
                            };
                            $overrideEntriesByDay = [];
                            foreach (($facultyScheduleOverrides ?? collect()) as $override) {
                                $day = $normalizeScheduleDay($override->override_date?->format('D'));
                                $dayGroup = $day ?: 'UNKNOWN';
                                $startRaw = $override->start_time;
                                $endRaw = $override->end_time;
                                $start = $clockMinutes($startRaw);
                                $end = $clockMinutes($endRaw);
                                $validRange = $start !== null && $end !== null && $end > $start;
                                $startLabel = $validRange ? \Carbon\Carbon::createFromTime(intdiv($start, 60), $start % 60)->format('g:i A') : null;
                                $endLabel = $validRange ? \Carbon\Carbon::createFromTime(intdiv($end, 60), $end % 60)->format('g:i A') : null;
                                $overrideEntriesByDay[$dayGroup][] = [
                                    'override' => $override,
                                    'start' => $start,
                                    'end' => $end,
                                    'time' => $validRange ? $startLabel . ' – ' . $endLabel : 'Time unavailable',
                                ];
                            }
                        @endphp

                        @if(!empty($overrideEntriesByDay))
                            <section class="my-schedule-override-panel" aria-label="Administrator schedule overrides">
                                <h3 class="my-schedule-override-panel-heading">Administrator overrides</h3>
                                <p class="my-schedule-override-panel-hint">Select an override to highlight only the affected classes in its room, day, and time. Select it again to clear the highlight.</p>
                                @foreach($overrideEntriesByDay as $overrideDay => $entries)
                                    @foreach($entries as $entry)
                                        @php
                                            $override = $entry['override'];
                                            $overrideRoomId = $override->room_id ?: $override->schedule?->room_id;
                                            $overrideRoomName = $override->room?->room_name ?: ($override->schedule?->room?->room_name ?: ($overrideRoomId ? 'Room ' . $overrideRoomId : 'Room unavailable'));
                                            $overrideDateLabel = $override->override_date?->format('M j, Y') ?? 'Date unavailable';
                                            $overrideDayLabel = $override->override_date?->format('l') ?? 'Day unavailable';
                                            $overridePurpose = $override->reason ?: 'Schedule override';
                                        @endphp
                                        <button type="button" class="my-schedule-override-entry" aria-pressed="false"
                                            data-override-id="{{ $override->id }}"
                                            data-override-day="{{ $overrideDay === 'UNKNOWN' ? '' : $overrideDay }}"
                                            data-override-room-id="{{ $overrideRoomId }}"
                                            data-override-start="{{ $entry['start'] ?? '' }}"
                                            data-override-end="{{ $entry['end'] ?? '' }}">
                                            <strong>Admin override · {{ $overrideDayLabel }}, {{ $overrideDateLabel }} · {{ $entry['time'] }}</strong>
                                            <span>Room: {{ $overrideRoomName }}</span>
                                            @if(!empty($override->affected_schedule_labels))
                                                <span>Affected schedules: {{ implode(', ', $override->affected_schedule_labels) }}</span>
                                            @endif
                                            <span>Room occupant: {{ $override->occupied_by ?: 'Students and Faculties' }}</span>
                                            <span>Purpose: {{ $overridePurpose }}</span>
                                            @if($override->details)<span>{{ $override->details }}</span>@endif
                                            <span class="my-schedule-override-match-status" aria-live="polite">Select to locate matching schedule blocks.</span>
                                        </button>
                                    @endforeach
                                @endforeach
                            </section>
                        @endif

                        <table class="excel-table" data-fit-schedule data-manual-cell-merges="{{ e(json_encode($manualCellMerges ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}">

                            <thead>

                                <tr>

                                    <th class="time-column">
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

                                    <th class="time-column">
                                        TIME
                                    </th>

                                    <th>
                                        TUE
                                    </th>

                                    <th>
                                        THU
                                    </th>

                                    @if($hasSaturdaySchedule)
                                        <th class="time-column">TIME</th>
                                        <th>SAT</th>
                                    @endif

                                </tr>

                            </thead>


                            <tbody>


                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | DEFAULT TIME ROWS
                                    |--------------------------------------------------------------------------
                                    */

                                    $positionFromScheduleTime = function ($scheduleTime) use ($defaultTimes) {
                                        $parseClock = function ($value) {
                                            if (!preg_match('/^(\d{1,2}):(\d{2})(?:\s*(AM|PM))?$/i', trim((string) $value), $matches)) {
                                                return null;
                                            }

                                            $hour = (int) $matches[1];
                                            $minute = (int) $matches[2];
                                            $meridiem = strtoupper($matches[3] ?? '');
                                            if ($meridiem === 'PM' && $hour < 12) $hour += 12;
                                            if ($meridiem === 'AM' && $hour === 12) $hour = 0;
                                            if ($meridiem === '' && $hour >= 1 && $hour <= 6) $hour += 12;
                                            return $hour <= 23 && $minute <= 59 ? $hour * 60 + $minute : null;
                                        };

                                        $parts = preg_split('/\s*[-–—]\s*/u', trim((string) $scheduleTime));
                                        if (count($parts) !== 2) return null;
                                        $start = $parseClock($parts[0]);
                                        if ($start === null) return null;

                                        foreach ($defaultTimes as $index => $timeSlot) {
                                            $slotParts = explode('-', $timeSlot, 2);
                                            $slotStart = $parseClock($slotParts[0] ?? '');
                                            $slotEnd = $parseClock($slotParts[1] ?? '');
                                            if ($slotStart !== null && $slotEnd !== null && $start >= $slotStart && $start < $slotEnd) {
                                                return $index;
                                            }
                                        }

                                        return null;
                                    };

                                    $formatScheduleDisplayTime = function ($value) {
                                        $parts = preg_split('/\s*[-–—]\s*/u', trim((string) $value), 2);
                                        if (count($parts) !== 2) return trim((string) $value);
                                        $parse = function ($token) {
                                            if (!preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i', trim($token), $matches)) return null;
                                            $hour = (int) $matches[1];
                                            $minute = (int) $matches[2];
                                            $period = strtoupper($matches[3] ?? '');
                                            if ($minute > 59 || $hour > 23) return null;
                                            if ($period === 'PM' && $hour < 12) $hour += 12;
                                            if ($period === 'AM' && $hour === 12) $hour = 0;
                                            if ($period === '' && $hour >= 1 && $hour <= 6) $hour += 12;
                                            if ($period === '' && $hour === 12) $hour = 12;
                                            return $hour * 60 + $minute;
                                        };
                                        $start = $parse($parts[0]);
                                        if ($start === null) return trim((string) $value);
                                        $startHour = (int) floor($start / 60) % 24;
                                        $startPeriod = $startHour < 12 ? 'AM' : 'PM';
                                        $end = $parse($parts[1]);
                                        if ($end === null) return trim((string) $value);
                                        if ($end <= $start && preg_match('/^(\d{1,2}):\d{2}(?!\s*(?:AM|PM))/i', trim($parts[1]))) $end += 12 * 60;
                                        $label = function ($minutes) {
                                            $hour24 = (int) floor($minutes / 60) % 24;
                                            return ($hour24 % 12 ?: 12) . ':' . str_pad((string) ($minutes % 60), 2, '0', STR_PAD_LEFT);
                                        };
                                        $endPeriod = ((int) floor($end / 60) % 24) < 12 ? 'AM' : 'PM';
                                        return $startPeriod === $endPeriod
                                            ? $label($start) . '–' . $label($end) . ' ' . $endPeriod
                                            : $label($start) . ' ' . $startPeriod . '–' . $label($end) . ' ' . $endPeriod;
                                    };


                                    /*
                                    |--------------------------------------------------------------------------
                                    | GROUP SCHEDULES
                                    |--------------------------------------------------------------------------
                                    */

                                    $scheduleRows = [];


                                    foreach (
                                        $mySchedules
                                        as $schedule
                                    ) {

                                        $day = strtoupper(
                                            trim(
                                                (string)
                                                $schedule->day
                                            )
                                        );


                                        /*
                                        |--------------------------------------------------------------------------
                                        | NORMALIZE DAY
                                        |--------------------------------------------------------------------------
                                        */

                                        $dayMap = [

                                            'MONDAY' =>
                                                'MON',

                                            'TUESDAY' =>
                                                'TUE',

                                            'WEDNESDAY' =>
                                                'WED',

                                            'THURSDAY' =>
                                                'THU',

                                            'FRIDAY' =>
                                                'FRI',

                                            'SATURDAY' =>
                                                'SAT',

                                            'SUNDAY' =>
                                                'SUN',

                                        ];


                                        if (
                                            isset(
                                                $dayMap[$day]
                                            )
                                        ) {

                                            $day =
                                                $dayMap[$day];

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | FIND ROW POSITION
                                        |--------------------------------------------------------------------------
                                        */

                                        // The chair's saved row position is authoritative. The time string
                                        // remains the source for the exact block height and displayed range.
                                        $position = is_numeric($schedule->row_position)
                                            ? (int) $schedule->row_position
                                            : $positionFromScheduleTime($schedule->time);


                                        /*
                                        |--------------------------------------------------------------------------
                                        | FALLBACK BY TIME
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            $position === null ||
                                            $position === ''
                                        ) {

                                            $savedTime =
                                                trim(
                                                    (string)
                                                    $schedule->time
                                                );


                                            /*
                                            | Convert common AM/PM forms
                                            */

                                            $normalizedTime =
                                                strtolower(
                                                    str_replace(
                                                        ' ',
                                                        '',
                                                        $savedTime
                                                    )
                                                );


                                            $position =
                                                array_search(
                                                    $normalizedTime,
                                                    array_map(
                                                        function (
                                                            $time
                                                        ) {

                                                            return strtolower(
                                                                str_replace(
                                                                    ' ',
                                                                    '',
                                                                    $time
                                                                )
                                                            );

                                                        },
                                                        $defaultTimes
                                                    )
                                                );

                                        }


                                        if (
                                            $position === false ||
                                            $position === null
                                        ) {

                                            continue;

                                        }


                                        $position =
                                            (int)
                                            $position;


                                        if ($position < 0) {

                                            continue;

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | CREATE ROW
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            !isset(
                                                $scheduleRows[
                                                    $position
                                                ]
                                            )
                                        ) {

                                            $scheduleRows[
                                                $position
                                            ] = [];

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | CREATE DAY
                                        |--------------------------------------------------------------------------
                                        */

                                        if (
                                            !isset(
                                                $scheduleRows[
                                                    $position
                                                ][$day]
                                            )
                                        ) {

                                            $scheduleRows[
                                                $position
                                            ][$day] = [];

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | STORE SCHEDULE
                                        |--------------------------------------------------------------------------
                                        */

                                        $scheduleRows[
                                            $position
                                        ][$day][] =
                                            $schedule;

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | TEMPORARY RESERVATION ROWS
                                    |--------------------------------------------------------------------------
                                    |
                                    | Reservations are date-specific temporary entries.
                                    | Put them into the same weekly grid as normal schedules.
                                    |--------------------------------------------------------------------------
                                    */

                                    $reservationRows = [];

                                    $renderReservationCell =
                                        function ($reservation) {

                                            $facultySubject =
                                                $reservation->facultySubject ?? null;

                                            $catalogSubject = $reservation->subject ?? null;

                                            $course =
                                                $catalogSubject?->course_code
                                                ?: $facultySubject?->course_code
                                                ?: 'Room Reservation';

                                            $subject =
                                                $catalogSubject?->course_title
                                                ?: $facultySubject?->subject
                                                ?: ($reservation->purpose ?? 'Reserved Room');

                                            $program =
                                                $catalogSubject?->program?->code
                                                ?: $facultySubject?->subjectCatalog?->program?->code
                                                ?: $facultySubject?->program
                                                ?: $catalogSubject?->program?->name;

                                            $yearLevel =
                                                $catalogSubject?->year_level
                                                ?: $facultySubject?->year_level;

                                            $room =
                                                $reservation->room?->room_name
                                                ?: 'Room Not Set';

                                            $time =
                                                trim(
                                                    (string) (
                                                        ($reservation->start_time && $reservation->end_time)
                                                            ? Carbon\Carbon::parse($reservation->start_time)->format('g:i A')
                                                                . ' – ' . Carbon\Carbon::parse($reservation->end_time)->format('g:i A')
                                                            : ($reservation->time ?: '')
                                                    )
                                                );
                                            $reservationBlockTime = $reservation->start_time && $reservation->end_time
                                                ? Carbon\Carbon::parse($reservation->start_time)->format('H:i') . '-' . Carbon\Carbon::parse($reservation->end_time)->format('H:i')
                                                : '';

                                            $reservationDate = $reservation->date
                                                ? Carbon\Carbon::parse($reservation->date)->format('M j, Y')
                                                : 'Date unavailable';

                                            $reservationDateIso = $reservation->date
                                                ? Carbon\Carbon::parse($reservation->date)->format('Y-m-d')
                                                : '';
                                            $reservationCourseCode = $reservation->subject?->course_code ?? $reservation->facultySubject?->course_code ?? '';
                                            $reservationInstructor = $reservation->user?->name ?? 'Instructor';

                                            return '
                                                <div class="temporary-reservation-entry" data-class-time="' . e($reservationBlockTime) . '" data-class-room-id="' . e((string) ($reservation->room_id ?? '')) . '" data-reservation-date="' . e($reservationDateIso) . '" data-reservation-course="' . e($reservationCourseCode) . '" data-reservation-room="' . e($room) . '" data-reservation-user-id="' . e((string) ($reservation->user_id ?? '')) . '" data-reservation-user-name="' . e($reservationInstructor) . '">
                                                    <div class="temporary-reservation-header"><div class="temporary-reservation-badge"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Reserved</div><div class="temporary-reservation-time">' . e($time) . '</div></div>
                                                    ' . ($reservationCourseCode !== '' ? '<div class="temporary-reservation-course">' . e($reservationCourseCode) . '</div>' : '') . '
                                                    <div class="temporary-reservation-time"><i class="fa-solid fa-door-open" aria-hidden="true"></i> ' . e($room) . '</div>
                                                    <div class="temporary-reservation-time"><i class="fa-regular fa-calendar" aria-hidden="true"></i> ' . e($reservationDate) . '</div>
                                                </div>
                                            ';
                                        };

                                    foreach (($temporaryReservations ?? collect()) as $reservation) {

                                        try {

                                            $reservationDay =
                                                strtoupper(
                                                    Carbon\Carbon::parse(
                                                        $reservation->date
                                                    )->format('D')
                                                );

                                        } catch (Throwable $e) {

                                            continue;
                                        }

                                        $dayMap = [
                                            'MON' => 'MON',
                                            'TUE' => 'TUE',
                                            'WED' => 'WED',
                                            'THU' => 'THU',
                                            'FRI' => 'FRI',
                                            'SAT' => 'SAT',
                                            'SUN' => 'SUN',
                                        ];

                                        if (!isset($dayMap[$reservationDay])) {
                                            continue;
                                        }

                                        $reservationDay = $dayMap[$reservationDay];

                                        $startTime =
                                            trim(
                                                (string) $reservation->start_time
                                            );

                                        if ($startTime === '') {
                                            continue;
                                        }

                                        $reservationPosition = null;
                                        $reservationStartMinutes = $clockMinutes($startTime);

                                        foreach ($defaultTimes as $defaultPosition => $defaultTime) {
                                            $defaultParts = explode('-', $defaultTime, 2);
                                            $defaultStart = trim($defaultParts[0] ?? '');
                                            $defaultEnd = trim($defaultParts[1] ?? '');
                                            if ($defaultPosition >= 5) {
                                                if (preg_match('/^(\d{1,2}):(\d{2})$/', $defaultStart, $timeParts) && (int) $timeParts[1] < 12) {
                                                    $defaultStart = sprintf('%02d:%s', (int) $timeParts[1] + 12, $timeParts[2]);
                                                }
                                                if (preg_match('/^(\d{1,2}):(\d{2})$/', $defaultEnd, $timeParts) && (int) $timeParts[1] < 12) {
                                                    $defaultEnd = sprintf('%02d:%s', (int) $timeParts[1] + 12, $timeParts[2]);
                                                }
                                            }
                                            $defaultStartMinutes = $clockMinutes($defaultStart);
                                            $defaultEndMinutes = $clockMinutes($defaultEnd);

                                            if ($defaultStartMinutes !== null && $defaultEndMinutes !== null
                                                && $reservationStartMinutes >= $defaultStartMinutes && $reservationStartMinutes < $defaultEndMinutes) {

                                                $reservationPosition =
                                                    $defaultPosition;

                                                break;
                                            }
                                        }

                                        if ($reservationPosition === null) {
                                            continue;
                                        }

                                        if (
                                            !isset(
                                                $reservationRows[
                                                    $reservationPosition
                                                ]
                                            )
                                        ) {

                                            $reservationRows[
                                                $reservationPosition
                                            ] = [];

                                        }

                                        if (
                                            !isset(
                                                $reservationRows[
                                                    $reservationPosition
                                                ][$reservationDay]
                                            )
                                        ) {

                                            $reservationRows[
                                                $reservationPosition
                                            ][$reservationDay] = [];

                                        }

                                        $reservationRows[
                                            $reservationPosition
                                        ][$reservationDay][] =
                                            $reservation;
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | CELL RENDER HELPER
                                    |--------------------------------------------------------------------------
                                    */

                                    $roomSwapByScheduleId = [];
                                    foreach (($roomSwapRequests ?? collect()) as $swapRequest) {
                                        $ownedSwapSchedule = (int) $swapRequest->requester_id === (int) Auth::id()
                                            ? $swapRequest->requesterSchedule
                                            : $swapRequest->targetSchedule;
                                        if ($ownedSwapSchedule) {
                                            $roomSwapByScheduleId[(int) $ownedSwapSchedule->id] = $swapRequest;
                                        }
                                    }

        $renderScheduleCell =
                                        function ($schedule) use ($roomSwapByScheduleId, $formatScheduleDisplayTime) {

                                            $swapRequest = $roomSwapByScheduleId[(int) $schedule->id] ?? null;
                                            $isTemporarySwap = $swapRequest
                                                && strtolower((string) $swapRequest->status) === 'approved'
                                                && strtolower((string) $swapRequest->swap_type) === 'temporary';

                                            $room =
                                                $schedule->room?->room_name
                                                ?: 'Room Not Set';


                                            $building =
                                                null;


                                            if (
                                                $schedule->room?->building
                                            ) {

                                                $building =
                                                    $schedule
                                                        ->room
                                                        ->building
                                                        ->name
                                                    ??
                                                    $schedule
                                                        ->room
                                                        ->building
                                                        ->building_name
                                                    ??
                                                    null;

                                            }

                                            $swapMarkup = '';
                                            if ($swapRequest) {
                                                $isRequester = (int) $swapRequest->requester_id === (int) Auth::id();
                                                $swapDate = $swapRequest->swap_date?->format('M j, Y') ?? 'Date unavailable';
                                                $partner = $isRequester
                                                    ? ($swapRequest->targetUser?->name ?? 'Other instructor')
                                                    : ($swapRequest->requester?->name ?? 'Other instructor');
                                                $temporaryRoom = $isRequester
                                                    ? ($swapRequest->targetRoom?->room_name ?? 'Other room')
                                                    : ($swapRequest->requesterRoom?->room_name ?? 'Other room');
                                                $swapTime = $swapRequest->start_time && $swapRequest->end_time
                                                    ? Carbon\Carbon::parse($swapRequest->start_time)->format('g:i A') . ' – ' . Carbon\Carbon::parse($swapRequest->end_time)->format('g:i A')
                                                    : 'Time unavailable';
                                                $swapStartAt = $swapRequest->swap_date && $swapRequest->start_time
                                                    ? Carbon\Carbon::parse($swapRequest->swap_date->format('Y-m-d') . ' ' . $swapRequest->start_time)->format('Y-m-d\\TH:i:s')
                                                    : '';
                                                $swapEndAt = $swapRequest->swap_date && $swapRequest->end_time
                                                    ? Carbon\Carbon::parse($swapRequest->swap_date->format('Y-m-d') . ' ' . $swapRequest->end_time)->format('Y-m-d\\TH:i:s')
                                                    : '';

                                                if ($isTemporarySwap) {
                                                    $destinationRoom = $isRequester ? $swapRequest->targetRoom : $swapRequest->requesterRoom;
                                                    if ($destinationRoom) {
                                                        $room = $destinationRoom->room_name ?: $temporaryRoom;
                                                        $building = $destinationRoom->building?->name
                                                            ?? $destinationRoom->building?->building_name
                                                            ?? $building;
                                                    }
                                                }

                                                $swapMarkup = '<div class="my-room-swap-entry" data-swap-start="' . e($swapStartAt) . '" data-swap-end="' . e($swapEndAt) . '"><strong><i class="fa-solid fa-right-left"></i> Temporary room · ' . e($temporaryRoom) . '</strong><span>' . e($swapDate) . ' · ' . e($swapTime) . ' · With ' . e($partner) . '</span></div>';
                                            }

                                            $displayRoomId = $schedule->room_id ?? '';
                                            if ($isTemporarySwap) {
                                                $displayRoomId = (int) $swapRequest->requester_id === (int) Auth::id()
                                                    ? ($swapRequest->target_room_id ?? $displayRoomId)
                                                    : ($swapRequest->requester_room_id ?? $displayRoomId);
                                            }

                                            $approvedMergedCourses = collect($schedule->merges ?? [])
                                                ->where('status', 'approved')
                                                ->map(function ($merge) {
                                                    $mergedSubject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                                                    if (!$mergedSubject) return null;
                                                    return [
                                                        'course_code' => $mergedSubject->course_code ?? $merge->facultySubject?->course_code,
                                                        'course_title' => $mergedSubject->course_title ?? $merge->facultySubject?->subject,
                                                        'program_code' => $mergedSubject->program?->code
                                                            ?? $merge->facultySubject?->subjectCatalog?->program?->code
                                                            ?? $merge->facultySubject?->program,
                                                        'major' => $mergedSubject->major
                                                            ?? $mergedSubject->program?->major
                                                            ?? $merge->facultySubject?->major,
                                                        'year_level' => $mergedSubject->year_level ?? $merge->facultySubject?->year_level,
                                                        'subject_id' => (int) ($merge->subject_id ?? $merge->facultySubject?->subject_id ?? 0),
                                                        'share_type' => $merge->share_type ?? 'merge',
                                                    ];
                                                })
                                                ->filter()
                                                ->values();
                                            $uniqueDisplayValues = static function ($values) {
                                                return collect($values)
                                                    ->filter(fn ($value) => trim((string) $value) !== '')
                                                    ->unique(fn ($value) => mb_strtolower(trim((string) $value)))
                                                    ->values();
                                            };
                                            $courseCodes = $uniqueDisplayValues(collect([$schedule->course_code])->merge($approvedMergedCourses->pluck('course_code')));
                                            $programCodes = $uniqueDisplayValues(collect([$schedule->program_code ?: $schedule->program])->merge($approvedMergedCourses->pluck('program_code')));
                                            $yearLevels = $uniqueDisplayValues(collect([$schedule->year_level])->merge($approvedMergedCourses->pluck('year_level')));
                                            $majorNames = $uniqueDisplayValues(collect([$schedule->major])->merge($approvedMergedCourses->pluck('major')));
                                            $course = $courseCodes->isNotEmpty() ? $courseCodes->implode(' / ') : 'No Course Code';
                                            $program = $programCodes->isNotEmpty() ? $programCodes->implode(' / ') : 'Program Not Set';
                                            $classDetailItems = '';
                                            if ($programCodes->isNotEmpty()) {
                                                $classDetailItems .= '<span class="class-meta-item"><strong>Program</strong> ' . e($program) . '</span>';
                                            }
                                            if ($yearLevels->isNotEmpty()) {
                                                $classDetailItems .= '<span class="class-meta-item"><strong>Year level</strong> ' . e($yearLevels->implode(' / ')) . '</span>';
                                            }
                                            $classDetailsMarkup = $classDetailItems !== ''
                                                ? '<div class="class-detail">' . $classDetailItems . '</div>'
                                                : '';
                                            $majorListMarkup = '';
                                            if ($majorNames->isNotEmpty()) {
                                                $majorListMarkup = '<button type="button" class="my-schedule-majors-trigger" data-major-list="'
                                                    . e(json_encode($majorNames->values()->all(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                                                    . '" aria-haspopup="dialog" aria-label="Show ' . $majorNames->count() . ' included majors">Majors · '
                                                    . $majorNames->count() . '</button>';
                                            }
                                            $roomMarkup = '<div class="room-name"><i class="fa-solid fa-door-open"></i> '
                                                . e($room)
                                                . ($building ? ' <span class="room-building-separator">·</span> ' . e($building) : '')
                                                . '</div>';

                                            $mergeKey = json_encode([
                                                // Use the saved, user-facing assignment details. Internal catalog/link IDs
                                                // may differ between weekday copies of one chair-assigned M/W/F class.
                                                // Major membership is combined across those copies below.
                                                'course_codes' => $courseCodes->map(fn ($value) => preg_replace('/\s+/u', '', mb_strtolower(trim((string) $value))))->all(),
                                                'programs' => $programCodes->map(fn ($value) => mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $value))))->all(),
                                                'years' => $yearLevels->map(fn ($value) => mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $value))))->all(),
                                                'faculty_id' => (string) ($schedule->faculty_id ?? ''),
                                                'instructor' => mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) ($schedule->instructor ?? '')))),
                                                'department_id' => (string) ($schedule->department_id ?? ''),
                                                'subject' => mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) ($schedule->subject ?? '')))),
                                                'subject_type' => mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) ($schedule->subject_type ?? '')))),
                                                'section' => mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) ($schedule->section ?? '')))),
                                                'time' => mb_strtolower(trim((string) $formatScheduleDisplayTime($schedule->time ?? ''))),
                                                'room_id' => (string) $displayRoomId,
                                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);


                                            return '<div class="class-entry' . ($isTemporarySwap ? ' room-swapped-temporary' : '') . '" data-schedule-id="' . e((string) $schedule->id) . '" data-class-time="' . e($schedule->time ?? '') . '" data-class-room-id="' . e($displayRoomId) . '" data-source-room-id="' . e((string) ($schedule->room_id ?? '')) . '" data-class-row-position="' . e((string) ($schedule->row_position ?? '')) . '" data-class-room-swap="' . ($isTemporarySwap ? '1' : '0') . '" data-class-merge-key="' . e($mergeKey ?: '') . '">'
                                                . '<div class="class-course">' . e($course) . '</div>'
                                                . '<div class="class-time">' . e($formatScheduleDisplayTime($schedule->time ?? '')) . '</div>'
                                                . $classDetailsMarkup
                                                . $majorListMarkup
                                                . $roomMarkup
                                                . '</div>';

                                        };


                                @endphp


                                @php
                                    /*
                                    |--------------------------------------------------------------------------
                                    | PUBLISHED ROW COUNT
                                    |--------------------------------------------------------------------------
                                    | A newly published custom time row can have a
                                    | row_position greater than the original 0-8 range.
                                    |--------------------------------------------------------------------------
                                    */
                                    $maxSchedulePosition = -1;

                                    foreach (array_keys($scheduleRows) as $schedulePosition) {
                                        if (is_numeric($schedulePosition)) {
                                            $maxSchedulePosition = max(
                                                $maxSchedulePosition,
                                                (int) $schedulePosition
                                            );
                                        }
                                    }

                                    $maxReservationPosition = $reservationRows
                                        ? max(array_keys($reservationRows))
                                        : -1;
                                    $maxOfficialTimePosition = $timeSlots->isNotEmpty()
                                        ? (int) $timeSlots->max('row_position')
                                        : -1;
                                    $maxRoomTimePosition = ($roomTimeSlots ?? collect())->isNotEmpty()
                                        ? (int) $roomTimeSlots->max('row_position')
                                        : -1;

                                    $publishedRowCount = max(
                                        9,
                                        $maxSchedulePosition + 1,
                                        $maxReservationPosition + 1,
                                        $maxOfficialTimePosition + 1,
                                        $maxRoomTimePosition + 1
                                    );

                                    $breakBeforePosition = 5;

                                    $timeLabelForSide = function ($side, $position) use ($roomTimeSlots, $timeSlots, $defaultTimes, $formatScheduleDisplayTime) {
                                        $days = [
                                            'left' => ['MON', 'WED', 'FRI'],
                                            'right' => ['TUE', 'THU'],
                                            'sat' => ['SAT'],
                                        ][$side] ?? [];
                                        $formatSlot = static function ($slot) {
                                            try {
                                                return \Carbon\Carbon::parse($slot->start_time)->format('g:i A')
                                                    . '–' . \Carbon\Carbon::parse($slot->end_time)->format('g:i A');
                                            } catch (\Throwable $exception) {
                                                return '';
                                            }
                                        };
                                        $roomLabels = ($roomTimeSlots ?? collect())
                                            ->filter(fn ($slot) => (int) $slot->row_position === (int) $position
                                                && in_array(strtoupper(substr((string) $slot->day, 0, 3)), $days, true))
                                            ->map($formatSlot)->filter()->unique()->values();
                                        if ($roomLabels->isNotEmpty()) return $roomLabels->implode(' / ');
                                        $officialLabels = ($timeSlots ?? collect())
                                            ->filter(fn ($slot) => (int) $slot->row_position === (int) $position
                                                && in_array(strtoupper(substr((string) $slot->day, 0, 3)), $days, true))
                                            ->map($formatSlot)->filter()->unique()->values();
                                        return $officialLabels->isNotEmpty()
                                            ? $officialLabels->implode(' / ')
                                            : ($defaultTimes[$position] ?? '');
                                    };
                                    $timeRangesForSide = function ($side, $position) use ($roomTimeSlots, $timeSlots) {
                                        $days = [
                                            'left' => ['MON', 'WED', 'FRI'],
                                            'right' => ['TUE', 'THU'],
                                            'sat' => ['SAT'],
                                        ][$side] ?? [];
                                        $ranges = ['rooms' => [], 'days' => []];
                                        foreach (($roomTimeSlots ?? collect())->filter(fn ($slot) => (int) $slot->row_position === (int) $position
                                            && in_array(strtoupper(substr((string) $slot->day, 0, 3)), $days, true)) as $slot) {
                                            $day = strtoupper(substr((string) $slot->day, 0, 3));
                                            $roomId = (string) $slot->room_id;
                                            $ranges['rooms'][$roomId][$day] ??= Carbon\Carbon::parse($slot->start_time)->format('H:i') . '-' . Carbon\Carbon::parse($slot->end_time)->format('H:i');
                                        }
                                        foreach (($timeSlots ?? collect())->filter(fn ($slot) => (int) $slot->row_position === (int) $position
                                            && in_array(strtoupper(substr((string) $slot->day, 0, 3)), $days, true)) as $slot) {
                                            $day = strtoupper(substr((string) $slot->day, 0, 3));
                                            $ranges['days'][$day] ??= Carbon\Carbon::parse($slot->start_time)->format('H:i') . '-' . Carbon\Carbon::parse($slot->end_time)->format('H:i');
                                        }
                                        return $ranges;
                                    };
                                @endphp

                                @for(
                                    $position = 0;
                                    $position < $publishedRowCount;
                                    $position++
                                )


                                    <!-- =================================================
                                         NOON BREAK
                                    ================================================== -->

                                    @if($position === $breakBeforePosition)

                                        <tr class="noon-break-row">

                                            <td
                                                colspan="{{ $hasSaturdaySchedule ? 9 : 7 }}"
                                                style="
                                                    background:#f0f8f3;
                                                    color:#176b3a;
                                                    text-align:center;
                                                    font-size:10px;
                                                    font-weight:900;
                                                    height:34px;
                                                "
                                            >

                                                NOON BREAK

                                            </td>

                                        </tr>

                                    @endif


                                    @php

                                        /*
                                        |--------------------------------------------------------------------------
                                        | LEFT TIME
                                        |--------------------------------------------------------------------------
                                        */

                                        $leftTime = $timeLabelForSide('left', $position);


                                        /*
                                        |--------------------------------------------------------------------------
                                        | RIGHT TIME
                                        |--------------------------------------------------------------------------
                                        */

                                        $rightTime = $timeLabelForSide('right', $position);


                                        $saturdayTime =
                                            $timeLabelForSide('sat', $position);

                                    @endphp


                                    <tr data-row-position="{{ $position }}">


                                        <!-- =================================================
                                             LEFT TIME
                                        ================================================== -->

                                        <td class="time-column" data-time-side="left" data-time-ranges="{{ e(json_encode($timeRangesForSide('left', $position), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}">

                                            {{ $leftTime }}

                                        </td>


                                        <!-- =================================================
                                             MONDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="MON">
<div class="schedule-cell-stack">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['MON']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['MON']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        
</div>
</td>


                                        <!-- =================================================
                                             WEDNESDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="WED">
<div class="schedule-cell-stack">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['WED']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['WED']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        
</div>
</td>


                                        <!-- =================================================
                                             FRIDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="FRI">
<div class="schedule-cell-stack">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['FRI']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['FRI']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        
</div>
</td>


                                        <!-- =================================================
                                             RIGHT TIME
                                        ================================================== -->

                                        <td class="time-column" data-time-side="right" data-time-ranges="{{ e(json_encode($timeRangesForSide('right', $position), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}">

                                            {{ $rightTime }}

                                        </td>


                                        <!-- =================================================
                                             TUESDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="TUE">
<div class="schedule-cell-stack">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['TUE']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['TUE']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        
</div>
</td>


                                        <!-- =================================================
                                             THURSDAY
                                        ================================================== -->

                                        <td class="schedule-cell" data-day="THU">
<div class="schedule-cell-stack">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['THU']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['THU']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        
</div>
</td>



                                        <!-- =================================================
                                             SATURDAY
                                        ================================================== -->

                                        @if($hasSaturdaySchedule)
                                        <td class="time-column" data-time-side="sat" data-time-ranges="{{ e(json_encode($timeRangesForSide('sat', $position), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}">{{ $saturdayTime }}</td>

                                        <td class="schedule-cell" data-day="SAT">
<div class="schedule-cell-stack">

                                            @php

                                                $items = array_merge(
                                                    $scheduleRows[
                                                        $position
                                                    ]['SAT']
                                                    ??
                                                    [],
                                                    $reservationRows[
                                                        $position
                                                    ]['SAT']
                                                    ??
                                                    []
                                                );

                                            @endphp


                                            @if(count($items))


                                                @foreach(
                                                    $items
                                                    as $item
                                                )

                                                    @if($item instanceof \App\Models\Schedule)

                                                        {!! $renderScheduleCell(
                                                            $item
                                                        ) !!}

                                                    @else

                                                        {!! $renderReservationCell(
                                                            $item
                                                        ) !!}

                                                    @endif

                                                @endforeach


                                            @else

                                                <div class="empty-cell">
                                                    Empty
                                                </div>

                                            @endif

                                        
</div>
</td>

                                        @endif


                                    </tr>


                                @endfor


                            </tbody>

                        </table>

                    </div>


                @else


                    <!-- =====================================================
                         NO SCHEDULES
                    ====================================================== -->

                    <div class="empty-schedule-page">

                        <i class="fa-solid fa-calendar-xmark"></i>

                        <strong>
                            No Schedules Found
                        </strong>

                        <span>

                            There are no schedules or active room reservations assigned to
                            {{ Auth::user()->name }}
                            for the current week.

                        </span>

                    </div>


                @endif


            </div>

        </main>

    </div>

</div>

<div class="my-schedule-majors-overlay" id="myScheduleMajorsDialog" hidden>
    <section class="my-schedule-majors-dialog" role="dialog" aria-modal="true" aria-labelledby="myScheduleMajorsTitle" tabindex="-1">
        <header>
            <h2 id="myScheduleMajorsTitle">Included majors</h2>
            <button type="button" class="my-schedule-majors-close" data-close-my-schedule-majors aria-label="Close major list">×</button>
        </header>
        <ul class="my-schedule-majors-list" id="myScheduleMajorsList"></ul>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const majorDialog = document.getElementById('myScheduleMajorsDialog');
    const majorList = document.getElementById('myScheduleMajorsList');
    let majorDialogReturnFocus = null;

    function closeMajorDialog()
    {
        if (!majorDialog || majorDialog.hidden) return;
        majorDialog.hidden = true;
        document.body.classList.remove('my-schedule-majors-open');
        const returnTarget = majorDialogReturnFocus;
        majorDialogReturnFocus = null;
        if (returnTarget?.isConnected) returnTarget.focus({ preventScroll: true });
    }

    document.addEventListener('click', function(event) {
        const target = event.target instanceof Element ? event.target : null;
        if (!target) return;
        if (target.closest('[data-close-my-schedule-majors]') || target === majorDialog) {
            closeMajorDialog();
            return;
        }
        const trigger = target.closest('.my-schedule-majors-trigger');
        if (!trigger || !majorDialog || !majorList) return;
        let majors = [];
        try { majors = JSON.parse(trigger.dataset.majorList || '[]'); } catch (_) {}
        if (!Array.isArray(majors) || !majors.length) return;
        majorList.replaceChildren();
        [...new Set(majors.map(value => String(value || '').trim()).filter(Boolean))].forEach(function(major) {
            const item = document.createElement('li');
            item.textContent = major;
            majorList.appendChild(item);
        });
        majorDialogReturnFocus = trigger;
        majorDialog.hidden = false;
        document.body.classList.add('my-schedule-majors-open');
        majorDialog.querySelector('[data-close-my-schedule-majors]')?.focus({ preventScroll: true });
    });

    document.addEventListener('keydown', function(event) {
        if (majorDialog && !majorDialog.hidden && event.key === 'Escape') {
            event.preventDefault();
            closeMajorDialog();
        } else if (majorDialog && !majorDialog.hidden && event.key === 'Tab') {
            event.preventDefault();
            majorDialog.querySelector('[data-close-my-schedule-majors]')?.focus({ preventScroll: true });
        }
    });

    function scheduleSignature(cell)
    {
        if (!cell) {
            return '';
        }

        if (cell.querySelector('.temporary-reservation-entry')) {
            return '';
        }

        const entries = cell.querySelectorAll('.class-entry');

        if (entries.length !== 1) {
            return '';
        }

        if (entries[0].querySelector('.my-room-swap-entry')) {
            return '';
        }

        if (entries[0].dataset.classRoomSwap === '1') {
            return '';
        }

        return entries[0].dataset.classMergeKey || '';
    }

    function mergeMajorLists(scheduleNodes)
    {
        const primary = scheduleNodes.find(node => node instanceof Element);
        if (!primary) return;

        const majors = [];
        scheduleNodes.forEach(function(node) {
            const trigger = node.querySelector('.my-schedule-majors-trigger');
            if (!trigger) return;
            try {
                const values = JSON.parse(trigger.dataset.majorList || '[]');
                if (Array.isArray(values)) majors.push(...values);
            } catch (_) {}
        });
        const uniqueMajors = [...new Map(majors
            .map(value => String(value || '').trim())
            .filter(Boolean)
            .map(value => [value.toLocaleLowerCase(), value])).values()];
        if (!uniqueMajors.length) return;

        let trigger = primary.querySelector('.my-schedule-majors-trigger');
        if (!trigger) {
            trigger = document.createElement('button');
            trigger.type = 'button';
            trigger.className = 'my-schedule-majors-trigger';
            trigger.setAttribute('aria-haspopup', 'dialog');
            const roomLine = primary.querySelector('.room-name');
            primary.insertBefore(trigger, roomLine || null);
        }
        trigger.dataset.majorList = JSON.stringify(uniqueMajors);
        trigger.setAttribute('aria-label', 'Show ' + uniqueMajors.length + ' included majors');
        trigger.textContent = 'Majors · ' + uniqueMajors.length;
    }

    function mergeGroup(row, days)
    {
        let index = 0;

        while (index < days.length) {

            const first =
                row.querySelector(
                    'td[data-day="' + days[index] + '"]'
                );

            if (!first) {
                index++;
                continue;
            }

            const signature = scheduleSignature(first);

            if (!signature) {
                index++;
                continue;
            }

            let end = index + 1;

            while (end < days.length) {

                const next =
                    row.querySelector(
                        'td[data-day="' + days[end] + '"]'
                    );

                if (!next || scheduleSignature(next) !== signature) {
                    break;
                }

                end++;
            }

            const count = end - index;

            if (count >= 2) {

                const mergedCells = days.slice(index, end).map(day =>
                    row.querySelector('td[data-day="' + day + '"]')
                ).filter(Boolean);
                mergeMajorLists(mergedCells.flatMap(cell =>
                    Array.from(cell.querySelectorAll('.schedule-cell-stack > .class-entry'))
                ));

                first.classList.add(
                    'merged-schedule-cell'
                );

                first.colSpan = count;

                for (
                    let removeIndex = index + 1;
                    removeIndex < end;
                    removeIndex++
                ) {
                    const cell =
                        row.querySelector(
                            'td[data-day="' +
                            days[removeIndex] +
                            '"]'
                        );

                    if (cell) {
                        cell.remove();
                    }
                }
            }

            index = end;
        }
    }

    function applySavedHorizontalMerges(row, dayGroups, merges)
    {
        const dayOrder = { MON: 0, WED: 1, FRI: 2, TUE: 0, THU: 1, SAT: 0 };
        (merges || []).filter(merge => String(merge.axis || '').toLowerCase() === 'horizontal').forEach(function(merge) {
            const coordinates = (Array.isArray(merge.cells) ? merge.cells : [])
                .map(function(coordinate) {
                    return {
                        day: String(coordinate.day || '').toUpperCase().slice(0, 3),
                        row: Number(coordinate.row_position),
                        side: String(coordinate.side || '').toLowerCase(),
                    };
                })
                .filter(coordinate => Number.isInteger(coordinate.row) && dayOrder[coordinate.day] !== undefined);
            if (coordinates.length < 2) return;
            const rowPosition = Number(row.dataset.rowPosition);
            const rowCoordinates = coordinates.filter(coordinate => coordinate.row === rowPosition);
            if (rowCoordinates.length < 2) return;
            const group = dayGroups.find(days => rowCoordinates.every(coordinate => days.includes(coordinate.day)));
            if (!group) return;
            rowCoordinates.sort((a, b) => group.indexOf(a.day) - group.indexOf(b.day));
            const firstIndex = group.indexOf(rowCoordinates[0].day);
            const consecutiveDays = group.slice(firstIndex, firstIndex + rowCoordinates.length);
            if (consecutiveDays.length !== rowCoordinates.length || !consecutiveDays.every((day, index) => day === rowCoordinates[index].day)) return;

            const cells = consecutiveDays.map(day => row.querySelector('td[data-day="' + day + '"]'));
            if (cells.some(cell => !cell || cell.querySelector('.temporary-reservation-entry'))) return;
            const scheduleNodes = cells.map(cell => Array.from(cell.querySelectorAll('.schedule-cell-stack > .class-entry')));
            if (scheduleNodes.some(nodes => nodes.length > 1)) return;
            const populated = scheduleNodes.flat();
            if (!populated.length || populated.some(node => String(node.dataset.sourceRoomId || '') !== String(merge.room_id || ''))) return;
            const signatures = [...new Set(populated.map(node => node.dataset.classMergeKey || ''))];
            if (signatures.length !== 1 || !signatures[0]) return;

            const anchor = cells[0];
            const anchorStack = anchor.querySelector('.schedule-cell-stack');
            if (!anchorStack) return;
            const primaryNode = scheduleNodes[0][0] || populated[0];
            mergeMajorLists(populated);
            if (primaryNode.parentElement !== anchorStack) {
                anchorStack.querySelector('.empty-cell')?.remove();
                anchorStack.appendChild(primaryNode);
            }
            scheduleNodes.flat().filter(node => node !== primaryNode).forEach(node => node.remove());
            anchor.classList.add('merged-schedule-cell');
            anchor.colSpan = cells.length;
            cells.slice(1).forEach(cell => cell.remove());
        });
    }

    let savedCellMerges = [];
    try {
        savedCellMerges = JSON.parse(document.querySelector('.excel-table')?.dataset.manualCellMerges || '[]');
        if (!Array.isArray(savedCellMerges)) savedCellMerges = [];
    } catch (_) {
        savedCellMerges = [];
    }

    document
        .querySelectorAll('.excel-table tbody tr')
        .forEach(function (row) {

            if (row.querySelector('td[colspan="7"]')) {
                return;
            }

            applySavedHorizontalMerges(row, [['MON', 'WED', 'FRI'], ['TUE', 'THU'], ['SAT']], savedCellMerges);

            mergeGroup(
                row,
                ['MON', 'WED', 'FRI']
            );

            mergeGroup(
                row,
                ['TUE', 'THU']
            );
        });

    function myScheduleClockMinutes(value)
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

    function myScheduleRange(value)
    {
        const parts = String(value || '').trim().replace(/[–—]/g, '-').split('-');
        if (parts.length !== 2) return null;
        const start = myScheduleClockMinutes(parts[0]);
        const end = myScheduleClockMinutes(parts[1]);
        return start !== null && end !== null && end > start ? { start: start, end: end } : null;
    }

    function renderMyScheduleDurationBlocks()
    {
        const wrapper = document.querySelector('.faculty-my-schedules-view .excel-wrapper');
        const table = wrapper?.querySelector('.excel-table');
        if (!wrapper || !table) return;

        if (window.ResizeObserver && !wrapper._myScheduleResizeObserver) {
            wrapper._myScheduleResizeObserver = new ResizeObserver(renderMyScheduleDurationBlocks);
            wrapper._myScheduleResizeObserver.observe(wrapper);
        }

        let layer = wrapper.querySelector('.my-schedule-block-layer');
        if (!layer) {
            layer = document.createElement('div');
            layer.className = 'my-schedule-block-layer';
            layer.setAttribute('role', 'group');
            layer.setAttribute('aria-label', 'Instructor schedule blocks and major list controls');
            wrapper.appendChild(layer);
        }
        layer.replaceChildren();

        function yAtMinute(side, minute, edge, day, roomId)
        {
            const rows = Array.from(table.tBodies[0]?.rows || []).filter(function(row) {
                return !row.classList.contains('noon-break-row');
            });

            for (const row of rows) {
                const timeCell = row.querySelector('[data-time-side="' + side + '"]');
                let rowTime = '';
                try {
                    const timeRanges = JSON.parse(timeCell?.dataset.timeRanges || '{}');
                    rowTime = timeRanges.rooms?.[String(roomId || '')]?.[String(day || '').toUpperCase()]
                        || timeRanges.days?.[String(day || '').toUpperCase()]
                        || '';
                } catch (_) {}
                const range = myScheduleRange(rowTime || timeCell?.textContent || '');
                if (!range) continue;
                if (edge === 'start' && minute === range.end) continue;
                if (edge === 'end' && minute === range.start) continue;
                if (minute < range.start || minute > range.end) continue;

                const rect = timeCell.getBoundingClientRect();
                return rect.top + rect.height * ((minute - range.start) / (range.end - range.start));
            }

            return null;
        }

        const wrapperRect = wrapper.getBoundingClientRect();
        const sideByDay = { MON: 'left', WED: 'left', FRI: 'left', TUE: 'right', THU: 'right', SAT: 'sat' };
        const selectedOverride = wrapper._selectedOverride;
        const daysBySide = { left: ['MON', 'WED', 'FRI'], right: ['TUE', 'THU'], sat: ['SAT'] };

        const durationSources = Array.from(table.querySelectorAll('.schedule-cell-stack > .class-entry[data-class-time], .schedule-cell-stack > .temporary-reservation-entry[data-class-time]'));
        const reservationSourceGroups = new Map();
        durationSources.filter(source => source.matches('.temporary-reservation-entry')).forEach(function(source) {
            const cell = source.closest('td[data-day]');
            const range = myScheduleRange(source.dataset.classTime);
            if (!cell || !range) return;
            const key = [cell.dataset.day, source.dataset.classRoomId, range.start, range.end].join('|');
            if (!reservationSourceGroups.has(key)) reservationSourceGroups.set(key, []);
            reservationSourceGroups.get(key).push(source);
        });
        const renderedReservationGroups = new Set();

        durationSources.forEach(function(source) {
            const cell = source.closest('td[data-day]');
            const range = myScheduleRange(source.dataset.classTime);
            const side = sideByDay[String(cell?.dataset.day || '').toUpperCase().slice(0, 3)];
            const isReservation = source.matches('.temporary-reservation-entry');
            if (!cell || !range || !side) return;
            if (!isReservation && (cell.querySelectorAll('.class-entry[data-class-time]').length !== 1 || cell.querySelector('.temporary-reservation-entry[data-class-time]'))) return;
            let reservationSources = [source];
            if (isReservation) {
                const groupKey = [cell.dataset.day, source.dataset.classRoomId, range.start, range.end].join('|');
                if (renderedReservationGroups.has(groupKey)) return;
                renderedReservationGroups.add(groupKey);
                reservationSources = reservationSourceGroups.get(groupKey) || reservationSources;
            }

            const scheduleDay = String(cell.dataset.day || '').toUpperCase().slice(0, 3);
            const sourceRoomId = source.dataset.sourceRoomId || source.dataset.classRoomId || '';
            const top = yAtMinute(side, range.start, 'start', scheduleDay, sourceRoomId);
            const bottom = yAtMinute(side, range.end, 'end', scheduleDay, sourceRoomId);
            if (top === null || bottom === null || bottom <= top) return;

            const cellRect = cell.getBoundingClientRect();
            const block = source.cloneNode(true);
            block.classList.add('my-schedule-duration-block');
            block.classList.remove('my-schedule-source-hidden');
            if (isReservation && reservationSources.length > 1) {
                const items = reservationSources.map(function(item) {
                    return {
                        user_id: item.dataset.reservationUserId || '',
                        user_name: item.dataset.reservationUserName || 'Instructor',
                        date: item.dataset.reservationDate || '',
                        course_code: item.dataset.reservationCourse || '',
                        room_name: item.dataset.reservationRoom || '',
                        start_time: String(Math.floor(range.start / 60)).padStart(2, '0') + ':' + String(range.start % 60).padStart(2, '0'),
                        end_time: String(Math.floor(range.end / 60)).padStart(2, '0') + ':' + String(range.end % 60).padStart(2, '0'),
                    };
                }).sort(function(a, b) { return a.date.localeCompare(b.date) || a.start_time - b.start_time; });
                const distinctUsers = new Set(items.map(item => item.user_id).filter(Boolean));
                const distinctDates = new Set(items.map(item => item.date).filter(Boolean));
                const popupAllowed = distinctUsers.size >= 2 && distinctDates.size >= 2;
                const escapeText = function(value) { return String(value || '').replace(/[&<>"']/g, function(char) { return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[char]; }); };
                const dateLines = items.map(function(item) {
                    const date = item.date ? new Date(item.date + 'T12:00:00').toLocaleDateString(undefined, {month:'short',day:'numeric',year:'numeric'}) : 'Date unavailable';
                    const code = item.course_code ? ' · ' + escapeText(item.course_code) : '';
                    return '<span class="my-reservation-date-line"><i class="fa-regular fa-calendar" aria-hidden="true"></i> ' + escapeText(date) + code + '</span>';
                }).join('');
                const timeText = block.querySelector('.temporary-reservation-time')?.textContent.trim() || '';
                const room = items[0].room_name || '';
                block.innerHTML = '<div class="temporary-reservation-header"><div class="temporary-reservation-badge"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Reserved</div><div class="temporary-reservation-time">' + timeText + '</div></div>'
                    + '<div class="temporary-reservation-time"><i class="fa-solid fa-door-open" aria-hidden="true"></i> ' + escapeText(room) + '</div>'
                    + dateLines
                    + (popupAllowed ? '<button type="button" class="my-reservation-details-trigger">' + items.length + ' reservations · View details</button>' : '');
                const detailsTrigger = block.querySelector('.my-reservation-details-trigger');
                if (detailsTrigger) {
                    detailsTrigger._reservationItems = items;
                    detailsTrigger.setAttribute('aria-haspopup', 'dialog');
                    detailsTrigger.setAttribute('aria-label', 'View details for ' + items.length + ' instructors’ reservations.');
                }
            }
            const sourceDay = String(cell.dataset.day || '').toUpperCase().slice(0, 3);
            const span = Math.max(1, Number(cell.colSpan || 1));
            const sideDays = daysBySide[side] || [sourceDay];
            const firstDayIndex = Math.max(0, sideDays.indexOf(sourceDay));
            const cellDays = sideDays.slice(firstDayIndex, firstDayIndex + span);
            const dayIndex = cellDays.indexOf(String(selectedOverride?.day || '').toUpperCase());
            const matchesOverride = selectedOverride
                && !isReservation
                && String(source.dataset.classRoomId || '') === String(selectedOverride.roomId || '')
                && dayIndex >= 0
                && Number.isFinite(selectedOverride.start)
                && Number.isFinite(selectedOverride.end)
                && range.start < selectedOverride.end
                && range.end > selectedOverride.start;
            if (matchesOverride) {
                const overlapStart = Math.max(range.start, selectedOverride.start);
                const overlapEnd = Math.min(range.end, selectedOverride.end);
                const overlapTop = yAtMinute(side, overlapStart, 'start', scheduleDay, sourceRoomId);
                const overlapBottom = yAtMinute(side, overlapEnd, 'end', scheduleDay, sourceRoomId);
                if (overlapTop !== null && overlapBottom !== null && overlapBottom > overlapTop) {
                    const segment = document.createElement('span');
                    segment.className = 'my-schedule-override-day-highlight';
                    segment.style.left = (dayIndex * 100 / cellDays.length) + '%';
                    segment.style.top = (overlapTop - top) + 'px';
                    segment.style.bottom = 'auto';
                    segment.style.width = (100 / cellDays.length) + '%';
                    segment.style.height = (overlapBottom - overlapTop) + 'px';
                    block.dataset.overrideHighlight = '1';
                    block.appendChild(segment);
                }
            }
            block.style.visibility = 'visible';
            block.dataset.durationMinutes = String(range.end - range.start);
            block.style.left = (cellRect.left - wrapperRect.left - wrapper.clientLeft + wrapper.scrollLeft) + 'px';
            block.style.top = (top - wrapperRect.top - wrapper.clientTop + wrapper.scrollTop) + 'px';
            block.style.width = cellRect.width + 'px';
            block.style.height = (bottom - top) + 'px';
            layer.appendChild(block);
            if (isReservation) reservationSources.forEach(item => item.classList.add('my-schedule-source-hidden'));
            else source.classList.add('my-schedule-source-hidden');
        });
    }

    document.querySelectorAll('.faculty-my-schedules-view .my-schedule-override-entry').forEach(function(button) {
        button.addEventListener('click', function() {
            const wrapper = document.querySelector('.faculty-my-schedules-view .excel-wrapper');
            if (!wrapper) return;
            const alreadySelected = button.getAttribute('aria-pressed') === 'true';
            document.querySelectorAll('.faculty-my-schedules-view .my-schedule-override-entry').forEach(function(item) {
                item.setAttribute('aria-pressed', 'false');
                if (item !== button) {
                    const previousStatus = item.querySelector('.my-schedule-override-match-status');
                    if (previousStatus) previousStatus.textContent = 'Select to locate matching schedule blocks.';
                }
            });
            if (alreadySelected) {
                wrapper._selectedOverride = null;
            } else {
                button.setAttribute('aria-pressed', 'true');
                wrapper._selectedOverride = {
                    day: String(button.dataset.overrideDay || '').toUpperCase(),
                    roomId: button.dataset.overrideRoomId || '',
                    start: Number(button.dataset.overrideStart),
                    end: Number(button.dataset.overrideEnd),
                };
            }
            renderMyScheduleDurationBlocks();
            const matches = Array.from(wrapper.querySelectorAll('.my-schedule-duration-block[data-override-highlight="1"]'));
            const status = button.querySelector('.my-schedule-override-match-status');
            if (status) status.textContent = alreadySelected
                ? 'Highlight cleared.'
                : matches.length
                    ? `Highlighted ${matches.length} affected schedule ${matches.length === 1 ? 'block' : 'blocks'}.`
                    : 'No matching schedule block found for the saved room, day, and time.';
            if (!alreadySelected) matches[0]?.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
        });
    });

    renderMyScheduleDurationBlocks();
    window.addEventListener('resize', renderMyScheduleDurationBlocks);

    function myReservationEscape(value)
    {
        return String(value || '').replace(/[&<>"']/g, function(char) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[char];
        });
    }

    function myReservationClock(value)
    {
        const match = String(value || '').match(/^(\d{1,2}):(\d{2})/);
        if (!match) return String(value || '');
        const hour = Number(match[1]);
        return (hour % 12 || 12) + ':' + match[2] + ' ' + (hour >= 12 ? 'PM' : 'AM');
    }

    function openMyReservationDetails(trigger)
    {
        const reservations = trigger._reservationItems || [];
        const cards = reservations.slice().sort(function(a, b) { return String(a.date || '').localeCompare(String(b.date || '')); }).map(function(item) {
            const date = item.date ? new Date(item.date + 'T12:00:00').toLocaleDateString(undefined, {year:'numeric',month:'long',day:'numeric'}) : 'Date unavailable';
            const time = [myReservationClock(item.start_time), myReservationClock(item.end_time)].filter(Boolean).join(' – ');
            return '<article class="my-reservation-detail-card"><strong><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> ' + myReservationEscape(date) + '</strong>'
                + (item.course_code ? '<span>' + myReservationEscape(item.course_code) + '</span>' : '')
                + '<span><i class="fa-solid fa-door-open" aria-hidden="true"></i> ' + myReservationEscape(item.room_name || 'Room unavailable') + '</span>'
                + '<span><i class="fa-regular fa-clock" aria-hidden="true"></i> ' + myReservationEscape(time) + '</span>'
                + '<span><i class="fa-solid fa-user" aria-hidden="true"></i> ' + myReservationEscape(item.user_name || 'Instructor') + '</span></article>';
        }).join('');
        let modal = document.getElementById('myReservationDetailsModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'myReservationDetailsModal';
            modal.className = 'my-reservation-details-overlay';
            modal.innerHTML = '<section class="my-reservation-details-dialog" role="dialog" aria-modal="true" aria-labelledby="myReservationDetailsTitle"><header><h2 id="myReservationDetailsTitle">Reservations for this schedule slot</h2><button type="button" data-close-my-reservation-details aria-label="Close">×</button></header><div class="my-reservation-details-list"></div></section>';
            document.body.appendChild(modal);
        }
        modal._returnFocus = trigger;
        modal.querySelector('.my-reservation-details-list').innerHTML = cards;
        modal.hidden = false;
        modal.querySelector('[data-close-my-reservation-details]')?.focus();
    }

    document.addEventListener('click', function(event) {
        const target = event.target instanceof Element ? event.target : null;
        if (!target) return;
        const modal = document.getElementById('myReservationDetailsModal');
        if (target.closest('[data-close-my-reservation-details]') || (modal && target === modal)) {
            const returnFocus = modal?._returnFocus;
            modal?.remove();
            returnFocus?.focus();
            return;
        }
        const trigger = target.closest('.my-reservation-details-trigger');
        if (trigger) openMyReservationDetails(trigger);
    });

    document.addEventListener('keydown', function(event) {
        const modal = document.getElementById('myReservationDetailsModal');
        if (event.key !== 'Escape' || !modal) return;
        const returnFocus = modal._returnFocus;
        modal.remove();
        returnFocus?.focus();
    });

    const swapTransitions = Array.from(document.querySelectorAll('.my-room-swap-entry'))
        .flatMap(function (entry) {
            return [entry.dataset.swapStart, entry.dataset.swapEnd]
                .map(function (value) { return value ? new Date(value).getTime() : NaN; })
                .filter(function (value) { return Number.isFinite(value) && value > Date.now(); });
        })
        .sort(function (a, b) { return a - b; });

    if (swapTransitions.length) {
        window.setTimeout(function () { window.location.reload(); }, Math.min(swapTransitions[0] - Date.now() + 500, 2147480000));
    }

    if (document.querySelector('.my-schedule-override-entry')) {
        const now = new Date();
        const nextMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 2);
        window.setTimeout(function () { window.location.reload(); }, Math.min(nextMidnight.getTime() - now.getTime(), 2147480000));
    }

});
</script>

