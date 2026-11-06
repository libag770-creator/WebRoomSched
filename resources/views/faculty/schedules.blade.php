@include('footerheader.header')
<div class="wrapper">

    

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content faculty-schedules-view">

            <style>

                /* =========================================================
                   PAGE
                ========================================================= */

                body {
                    background: #f5f7f6;
                }

                .faculty-schedule-page {
                    max-width: 1250px;
                    margin: 0 auto;
                    padding: 10px 0 35px;
                }


                /* =========================================================
                   HEADER
                ========================================================= */

                .schedule-header {
                    margin-bottom: 22px;
                }

                .schedule-title {
                    margin: 0;
                    font-size: 24px;
                    font-weight: 800;
                    color: #176b3a;
                }

                .schedule-subtitle {
                    margin-top: 6px;
                    color: #667085;
                    font-size: 13px;
                }


                /* =========================================================
                   DEPARTMENT CARDS
                ========================================================= */

                .department-list {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 16px;
                    margin-bottom: 22px;
                }

                .department-card {
                    background: #ffffff;
                    border: 1px solid #dfe5e1;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow:
                        0 3px 10px rgba(16, 24, 40, .06);

                    cursor: pointer;

                    transition:
                        transform .2s ease,
                        border-color .2s ease,
                        box-shadow .2s ease;
                }

                .department-card:hover {
                    transform: translateY(-2px);

                    border-color: #176b3a;

                    box-shadow:
                        0 7px 18px rgba(16, 24, 40, .10);
                }

                .department-card.active {
                    border: 2px solid #176b3a;
                    background: #f3faf6;
                }

                .department-button {
                    width: 100%;
                    min-height: 145px;

                    border: none;
                    background: transparent;

                    padding: 20px;

                    display: flex;
                    flex-direction: column;

                    align-items: flex-start;
                    justify-content: space-between;

                    cursor: pointer;
                    text-align: left;
                }

                .department-icon {
                    width: 42px;
                    height: 42px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    border-radius: 10px;

                    background: #eaf6ef;
                    color: #176b3a;

                    font-size: 19px;

                    margin-bottom: 14px;
                }

                .department-name {
                    font-size: 17px;
                    font-weight: 800;
                    color: #176b3a;

                    margin-bottom: 5px;
                }

                .department-info {
                    font-size: 11px;
                    color: #667085;
                }

                .department-arrow {
                    align-self: flex-end;

                    margin-top: -22px;

                    color: #176b3a;
                    font-size: 19px;

                    transition: transform .2s ease;
                }

                .department-card.active .department-arrow {
                    transform: translateX(5px);
                }


                /* =========================================================
                   DEPARTMENT CONTENT
                ========================================================= */

                .department-content {
                    display: none;
                }

                .department-content.open {
                    display: block;
                }

                .selected-department-header {
                    background: #ffffff;

                    border: 1px solid #dfe5e1;
                    border-radius: 12px;

                    padding: 17px 20px;

                    margin-bottom: 14px;

                    box-shadow:
                        0 2px 8px rgba(16, 24, 40, .05);
                }

                .selected-department-title {
                    margin: 0;

                    color: #176b3a;

                    font-size: 18px;
                    font-weight: 800;
                }

                .selected-department-subtitle {
                    margin: 5px 0 0;

                    color: #667085;

                    font-size: 11px;
                }


                /* =========================================================
                   BUILDINGS
                ========================================================= */

                .building-list {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }

                .building-card {
                    background: #ffffff;

                    border: 1px solid #dfe5e1;
                    border-radius: 12px;

                    overflow: hidden;

                    box-shadow:
                        0 2px 8px rgba(16, 24, 40, .05);
                }

                .building-button {
                    width: 100%;

                    border: none;
                    background: #ffffff;

                    padding: 17px 20px;

                    display: flex;
                    align-items: center;
                    justify-content: space-between;

                    cursor: pointer;

                    text-align: left;

                    transition:
                        background .2s ease,
                        color .2s ease;
                }

                .building-button:hover {
                    background: #f3faf6;
                }

                .building-button.active {
                    background: #eaf6ef;
                }

                .building-left {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .building-icon {
                    width: 38px;
                    height: 38px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    border-radius: 9px;

                    background: #eaf6ef;
                    color: #176b3a;

                    font-size: 17px;
                }

                .building-name {
                    font-size: 14px;
                    font-weight: 800;

                    color: #1f2937;
                }

                .building-department {
                    margin-top: 3px;

                    font-size: 11px;

                    color: #667085;
                }

                .building-arrow {
                    color: #176b3a;

                    font-size: 19px;

                    transition:
                        transform .2s ease;
                }

                .building-button.active .building-arrow {
                    transform: rotate(180deg);
                }


                /* =========================================================
                   ROOMS
                ========================================================= */

                .rooms-container {
                    display: none;

                    padding: 16px;

                    background: #f8faf9;

                    border-top: 1px solid #dfe5e1;

                    grid-template-columns:
                        repeat(3, minmax(0, 1fr));

                    gap: 12px;
                }

                .rooms-container.open {
                    display: grid;
                }

                .room-card {
                    border: 1px solid #dfe5e1;

                    background: #ffffff;

                    border-radius: 10px;

                    padding: 15px;

                    cursor: pointer;

                    transition:
                        border-color .2s ease,
                        transform .2s ease,
                        box-shadow .2s ease;
                }

                .room-card:hover {
                    border-color: #176b3a;

                    transform: translateY(-2px);

                    box-shadow:
                        0 6px 16px
                        rgba(16, 24, 40, .08);
                }

                .room-card.active {
                    border-color: #176b3a;

                    background: #f3faf6;
                }

                .room-top {
                    display: flex;

                    align-items: center;

                    justify-content: space-between;

                    gap: 10px;
                }

                .room-name {
                    color: #176b3a;

                    font-size: 14px;

                    font-weight: 800;
                }

                .room-arrow {
                    color: #176b3a;

                    font-size: 18px;
                }

                .room-info {
                    margin-top: 7px;

                    font-size: 11px;

                    color: #667085;
                }

                .room-action {
                    margin-top: 12px;

                    font-size: 10px;

                    font-weight: 800;

                    color: #176b3a;
                }


                /* =========================================================
                   EMPTY
                ========================================================= */

                .empty-building {
                    grid-column: 1 / -1;

                    padding: 25px;

                    text-align: center;

                    color: #98a2b3;

                    font-size: 12px;
                }

                .empty-department {
                    padding: 35px;

                    text-align: center;

                    background: #ffffff;

                    border: 1px dashed #cfd8d2;

                    border-radius: 12px;

                    color: #98a2b3;

                    font-size: 12px;
                }


                /* =========================================================
                   DEPARTMENT PLACEHOLDER
                ========================================================= */

                .department-placeholder {
                    background: #ffffff;

                    border: 1px dashed #cfd8d2;

                    border-radius: 12px;

                    padding: 45px 20px;

                    text-align: center;

                    color: #667085;
                }

                .department-placeholder-icon {
                    font-size: 32px;

                    margin-bottom: 10px;
                }

                .department-placeholder h3 {
                    margin: 0 0 5px;

                    color: #176b3a;

                    font-size: 16px;
                }

                .department-placeholder p {
                    margin: 0;

                    font-size: 12px;
                }


                /* =========================================================
                   SCHEDULE PANEL
                ========================================================= */

                .schedule-panel {
                    display: none;

                    margin-top: 22px;

                    background: #ffffff;

                    border: 1px solid #dfe5e1;

                    border-radius: 12px;

                    overflow: hidden;

                    box-shadow:
                        0 4px 15px
                        rgba(16, 24, 40, .06);
                }

                .schedule-panel.open {
                    display: block;
                }

                .schedule-panel-header {
                    background: #176b3a;

                    color: #ffffff;

                    padding: 17px 20px;

                    display: flex;

                    align-items: center;

                    justify-content: space-between;

                    gap: 15px;
                }

                .schedule-panel-title {
                    margin: 0;

                    font-size: 17px;

                    font-weight: 800;
                }

                .schedule-panel-subtitle {
                    margin-top: 4px;

                    font-size: 11px;

                    opacity: .88;
                }

                .close-schedule {
    flex-shrink: 0;

    border: 1px solid rgba(255,255,255,.45);

    background: rgba(255,255,255,.12);

    color: #ffffff;

    padding: 9px 15px;

    border-radius: 7px;

    cursor: pointer;

    font-size: 11px;

    font-weight: 800;

    transition:
        background .2s ease,
        transform .2s ease;
}

.close-schedule:hover {

    background:
        rgba(255,255,255,.22);

    transform:
        translateY(-1px);

}

.close-schedule:active {

    transform:
        translateY(0);

}


                /* =========================================================
                   LOADING
                ========================================================= */

                .schedule-loading {
    min-height: 180px;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    gap: 12px;

    padding: 30px;

    color: #667085;

    text-align: center;
}

.loading-spinner {
    width: 30px;
    height: 30px;

    border: 3px solid #e5eee8;
    border-top-color: #176b3a;

    border-radius: 50%;

    animation: scheduleSpin .8s linear infinite;
}

.loading-text {
    font-size: 12px;
    font-weight: 600;
}

@keyframes scheduleSpin {

    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }

}

.schedule-error {
    min-height: 180px;

    display: flex;
    flex-direction: column;

    align-items: center;
    justify-content: center;

    gap: 8px;

    padding: 30px;

    text-align: center;
}

.schedule-error-title {
    color: #344054;

    font-size: 14px;
    font-weight: 800;
}

.schedule-error-message {
    max-width: 500px;

    color: #667085;

    font-size: 11px;
}

.retry-schedule-btn {
    margin-top: 8px;

    padding: 9px 16px;

    border: none;
    border-radius: 7px;

    background: #176b3a;
    color: #ffffff;

    font-size: 11px;
    font-weight: 700;

    cursor: pointer;
}

.retry-schedule-btn:hover {
    background: #12582f;
}


    /* =========================================================
   FACULTY TIMETABLE
========================================================= */

.timetable-wrapper {
    width: 100%;
    overflow-x: auto;

    padding: 18px;

    box-sizing: border-box;
}

.faculty-timetable {
    width: 100%;

    min-width: 1300px;

    table-layout: fixed;

    border-collapse: separate;
    border-spacing: 0;

    background: #ffffff;

    border: 1px solid #dfe5e1;

    border-radius: 10px;

    overflow: hidden;
}

.timetable-wrapper {
    position: relative;
}

.published-schedule-block-layer {
    position: absolute;
    inset: 0;
    z-index: 8;
    pointer-events: none;
}
.published-schedule-block.schedule-override-highlight { border-color:#c8473d !important; background:linear-gradient(145deg,#ffe9e7,#fff7f6) !important; box-shadow:inset 4px 0 #c8473d,0 3px 9px rgba(138,36,32,.13) !important; }
.published-schedule-override-day-highlight { position:absolute; top:0; bottom:0; z-index:0; border-left:4px solid #c8473d; background:rgba(200,71,61,.26); pointer-events:none; }
.published-schedule-block > :not(.published-schedule-override-day-highlight) { position:relative; z-index:1; }

.published-schedule-source-hidden {
    visibility: hidden !important;
    height: 0 !important;
    min-height: 0 !important;
    max-height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    border-width: 0 !important;
    overflow: hidden !important;
}

.published-schedule-block {
    position: absolute !important;
    min-height: 0 !important;
    max-height: none;
    margin: 0;
    padding: 5px 7px;
    overflow: hidden;
    border: 1px solid rgba(50, 112, 77, .32);
    border-radius: 8px;
    box-sizing: border-box;
    justify-content: flex-start;
    z-index: 1;
}

.published-schedule-block[data-duration-minutes="30"] { padding:3px 4px; }
.published-schedule-block[data-duration-minutes="30"] .class-course { font-size:8px; line-height:1.05; }
.published-schedule-block[data-duration-minutes="30"] .class-subject,
.published-schedule-block[data-duration-minutes="30"] .class-instructor,
.published-schedule-block[data-duration-minutes="30"] .class-program,
.published-schedule-block[data-duration-minutes="30"] .class-major,
.published-schedule-block[data-duration-minutes="30"] .class-type,
.published-schedule-block[data-duration-minutes="30"] .merged-course-details,
.published-schedule-block[data-duration-minutes="30"] .schedule-override-notice,
.published-schedule-block[data-duration-minutes="30"] .room-swap-details { display:block !important; margin:1px 0 0; font-size:7px; line-height:1.05; }


/* =========================================================
   TABLE CELLS
========================================================= */

.faculty-timetable th,
.faculty-timetable td {
    border-right: 1px solid #dfe5e1;
    border-bottom: 1px solid #dfe5e1;

    box-sizing: border-box;
}


/* =========================================================
   TABLE HEADER
========================================================= */

.faculty-timetable th {
    height: 46px;

    padding: 10px 8px;

    background: #f0f8f3;

    color: #176b3a;

    font-size: 12px;

    font-weight: 800;

    text-align: center;

    vertical-align: middle;
}


/* =========================================================
   TIME COLUMNS
========================================================= */

.faculty-timetable th:nth-child(1),
.faculty-timetable th:nth-child(5),
.faculty-timetable .time-column {
    width: 125px;

    min-width: 125px;

    max-width: 125px;
}

.faculty-timetable .time-column {
    height: 82px;

    padding: 10px;

    background: #fafcfb;

    color: #344054;

    font-size: 12px;

    font-weight: 800;

    text-align: center;

    vertical-align: middle;

    white-space: nowrap;
}


/* =========================================================
   CLASSROOM CELLS
========================================================= */

.faculty-timetable td:not(.time-column) {
    height: 82px;

    padding: 6px;

    text-align: center;

    vertical-align: middle;
}


/* =========================================================
   REMOVE EXTRA BORDERS
========================================================= */

.faculty-timetable th:last-child,
.faculty-timetable td:last-child {
    border-right: none;
}

.faculty-timetable tbody tr:last-child td {
    border-bottom: none;
}


/* =========================================================
   EMPTY CELL
========================================================= */

.empty-schedule-cell {
    display: inline-block;

    color: #98a2b3;

    font-size: 10px;

    font-style: italic;
}


/* =========================================================
   CLASS ENTRY
========================================================= */

.class-entry {
    width: 100%;

    min-height: 68px;

    padding: 9px;

    box-sizing: border-box;

    border-radius: 8px;

    text-align: center;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;
}

/* =========================================================
   MERGED SCHEDULE CELL
========================================================= */

.merged-schedule-cell {
    text-align: center !important;
    vertical-align: middle !important;
}

.merged-schedule-cell .class-entry {
    width: 100%;

    text-align: center;

    align-items: center;

    justify-content: center;
}

.merged-schedule-cell .class-course,
.merged-schedule-cell .class-subject,
.merged-schedule-cell .class-instructor,
.merged-schedule-cell .class-program,
.merged-schedule-cell .class-type,
.merged-schedule-cell .class-major {
    width: 100%;
    text-align: center;
}


/* =========================================================
   COURSE
========================================================= */

.class-course {
    color: #176b3a;

    font-size: 12px;

    font-weight: 900;

    line-height: 1.2;
}


/* =========================================================
   SUBJECT
========================================================= */

.class-subject {
    margin-top: 4px;

    color: #1f2937;

    font-size: 11px;

    font-weight: 600;

    line-height: 1.2;
}


/* =========================================================
   INSTRUCTOR
========================================================= */

.class-instructor {
    margin-top: 5px;

    color: #667085;

    font-size: 10px;

    line-height: 1.2;
}


/* =========================================================
   PROGRAM
========================================================= */

/* =========================================================
   ROOM SWAP STATUS
========================================================= */

.room-swap-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    width: fit-content;
    margin-top: 7px;
    padding: 4px 7px;
    border-radius: 999px;
    background: #fff7e6;
    border: 1px solid #f2d18b;
    color: #8a5a00;
    font-size: 8px;
    font-weight: 900;
    line-height: 1;
    letter-spacing: .35px;
    text-transform: uppercase;
}

.room-swap-badge i {
    font-size: 8px;
}

.room-swap-details {
    margin-top: 4px;
    color: #8a5a00;
    font-size: 9px;
    font-weight: 700;
    line-height: 1.35;
}

.room-swap-original {
    color: #667085;
    font-size: 9px;
    font-weight: 600;
}

.schedule-override-notice {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-top: 8px;
    padding: 8px 9px;
    border: 1px solid #d3a749;
    border-radius: 8px;
    background: #fff6df;
    color: #513b12;
    font-size: 12px;
    line-height: 1.4;
}
.schedule-override-notice strong { color: #533c11; font-size: 12px; }
.schedule-override-notice span { font-size: 12px; }

.room-override-panel { display:grid; gap:10px; margin:18px 0 4px; padding:15px; border:1px solid #e7d49f; border-radius:12px; background:#fff8e8; color:#59451c; }
.room-override-panel h3 { margin:0; color:#574116; font-size:15px; font-weight:850; }
.room-override-panel p { margin:0 0 2px; color:#76694d; font-size:12px; line-height:1.45; }
.room-override-entry { display:grid; gap:4px; padding:10px 12px; border:1px solid #f0e1b9; border-radius:9px; background:#fffdf7; }
button.room-override-entry { width:100%; font:inherit; text-align:left; cursor:pointer; }
button.room-override-entry:hover,button.room-override-entry[aria-pressed="true"] { border-color:#c8473d; background:#fff0ef; box-shadow:0 0 0 2px rgba(200,71,61,.12); }
.room-override-entry strong { color:#5d4719; font-size:13px; font-weight:850; }
.room-override-entry span { color:#655b40; font-size:12px; line-height:1.45; }

.temporary-reservation-entry {
    width: 100%;
    min-height: 68px;
    padding: 9px;
    box-sizing: border-box;
    border-radius: 8px;
    text-align: left;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: #fff8e8;
    border: 1px solid #f2d18b;
}

.temporary-reservation-entry.pending {
    background: #f5f7ff;
    border-color: #b8c4f2;
}

.reservation-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    width: fit-content;
    margin-bottom: 5px;
    padding: 4px 7px;
    border-radius: 999px;
    background: #fff0c7;
    color: #8a5a00;
    font-size: 8px;
    font-weight: 900;
    text-transform: uppercase;
    line-height: 1;
}

.temporary-reservation-entry.pending .reservation-badge {
    background: #e9edff;
    color: #4656a3;
}

.reservation-course {
    color: #8a5a00;
    font-size: 11px;
    font-weight: 900;
    line-height: 1.2;
}

.temporary-reservation-entry.pending .reservation-course {
    color: #4656a3;
}

.reservation-purpose {
    margin-top: 4px;
    color: #344054;
    font-size: 10px;
    font-weight: 700;
    line-height: 1.2;
}

.reservation-requester {
    margin-top: 4px;
    color: #667085;
    font-size: 9px;
    line-height: 1.2;
}

.reservation-expiration {
    margin-top: 6px;
    color: #8a5a00;
    font-size: 8px;
    font-weight: 800;
    line-height: 1.3;
}

.temporary-reservation-entry.pending .reservation-expiration {
    color: #4656a3;
}

.schedule-legend {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    padding: 11px 18px;
    background: #fbfdfc;
    border-bottom: 1px solid #e5ebe7;
    color: #667085;
    font-size: 10px;
    font-weight: 600;
}

.schedule-legend-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 7px;
    border-radius: 999px;
    background: #fff7e6;
    border: 1px solid #f2d18b;
    color: #8a5a00;
    font-size: 8px;
    font-weight: 900;
    text-transform: uppercase;
}

.schedule-panel-subtitle .swap-live-indicator {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-left: 8px;
    padding: 3px 7px;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.28);
    color: #ffffff;
    font-size: 8px;
    font-weight: 900;
    text-transform: uppercase;
}

.schedule-panel-subtitle .swap-live-indicator i {
    color: #ffd166;
}

@media (max-width: 600px) {
    .schedule-legend {
        align-items: flex-start;
        flex-direction: column;
    }
}

.class-program {
    margin-top: 5px;

    color: #176b3a;

    font-size: 10px;

    font-weight: 800;

    line-height: 1.2;
}
.class-type {
    margin-top: 3px;
    color: #667085;
    font-size: 9px;
    font-weight: 800;
    line-height: 1.2;
    text-transform: uppercase;
}

.class-major {
    margin-top: 3px;
    color: #667085;
    font-size: 10px;
    font-weight: 600;
    line-height: 1.2;
}



/* =========================================================
   NOON BREAK
========================================================= */

.noon-break-row td {
    height: 34px !important;

    padding: 7px !important;

    background: #f8faf9;

    color: #667085;

    font-size: 11px !important;

    font-weight: 800;

    text-align: center !important;

    letter-spacing: .5px;
}


                /* =========================================================
                   RESPONSIVE
                ========================================================= */

                @media (max-width: 900px) {

                    .department-list {
                        grid-template-columns:
                            repeat(2, 1fr);
                    }

                    .rooms-container {
                        grid-template-columns:
                            repeat(2, minmax(0, 1fr));
                    }

                }


                @media (max-width: 600px) {

                    .faculty-schedule-page {
                        padding: 5px 0 20px;
                    }

                    .schedule-title {
                        font-size: 20px;
                    }

                    .department-list {
                        grid-template-columns: 1fr;
                    }

                    .rooms-container {
                        grid-template-columns: 1fr;
                    }

                    .department-button {
                        min-height: 125px;
                    }

                    .schedule-panel-header {
                        align-items: flex-start;

                        flex-direction: column;
                    }

                    .close-schedule {
                        width: 100%;
                    }

                }

                /* Refined faculty schedules workspace */
                .faculty-schedules-view { background: #f5f8f6; }
                .faculty-schedule-page { max-width: 1320px; padding: 12px 0 42px; }
                .schedule-header {
                    position: relative;
                    margin-bottom: 25px;
                    padding: 25px 28px;
                    overflow: hidden;
                    border: 1px solid #dfeae2;
                    border-radius: 20px;
                    background: linear-gradient(115deg, #fff 0%, #f0f8f2 100%);
                    box-shadow: 0 8px 24px rgba(22, 73, 45, .045);
                }
                .schedule-header::after {
                    position: absolute;
                    top: -55px;
                    right: -32px;
                    width: 210px;
                    height: 210px;
                    border: 1px solid rgba(28, 117, 74, .10);
                    border-radius: 50%;
                    box-shadow: 0 0 0 24px rgba(28, 117, 74, .035), 0 0 0 48px rgba(28, 117, 74, .025);
                    content: "";
                }
                .schedule-header::before {
                    display: block;
                    margin-bottom: 8px;
                    color: #56816a;
                    content: "FACULTY WORKSPACE  /  SCHEDULES";
                    font-size: 9px;
                    font-weight: 800;
                    letter-spacing: .14em;
                }
                .schedule-title { color: #193e2d; font-size: 27px; font-weight: 760; letter-spacing: -.035em; }
                .schedule-subtitle { max-width: 580px; margin-top: 7px; color: #708078; font-size: 12px; line-height: 1.65; }
                .faculty-schedules-section-heading { margin: 0 0 12px; color: #284735; font-size: 14px; font-weight: 750; }
                .department-list { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 25px; }
                .department-card { border-color: #e4ebe6; border-radius: 16px; box-shadow: 0 4px 14px rgba(25, 57, 39, .04); }
                .department-card:hover { transform: translateY(-2px); border-color: #b9d7c3; box-shadow: 0 12px 24px rgba(25, 80, 49, .08); }
                .department-card.active { border: 1px solid #19764d; background: #f3faf5; box-shadow: 0 0 0 3px rgba(25, 118, 77, .08); }
                .department-button { min-height: 136px; padding: 18px 19px; }
                .department-icon { width: 39px; height: 39px; margin-bottom: 12px; border-radius: 12px; background: #edf6ef; color: #19764d; font-size: 16px; }
                .department-name { color: #244b35; font-size: 14px; font-weight: 750; line-height: 1.45; }
                .department-info { color: #829087; font-size: 10px; }
                .department-arrow { color: #619175; font-size: 17px; }
                .selected-department-header, .building-card, .schedule-panel { border-color: #e4ebe6; border-radius: 15px; box-shadow: 0 5px 16px rgba(25, 57, 39, .045); }
                .selected-department-header { padding: 18px 20px; }
                .selected-department-title { color: #244b35; font-size: 16px; }
                .selected-department-subtitle { color: #829087; font-size: 11px; }
                .building-list { gap: 10px; }
                .building-card { overflow: hidden; }
                .building-button { padding: 14px 18px; }
                .building-button:hover, .building-button.active { background: #f1f8f3; }
                .building-icon { border-radius: 11px; background: #edf6ef; color: #19764d; }
                .building-name { color: #2d4738; font-size: 13px; font-weight: 750; }
                .building-department { color: #89958d; font-size: 10px; }
                .rooms-container { border-top-color: #e9efeb; background: #f8fbf9; }
                .room-card { border-color: #e4ebe6; border-radius: 12px; box-shadow: 0 2px 8px rgba(25, 57, 39, .025); }
                .room-card:hover { border-color: #b9d7c3; box-shadow: 0 8px 18px rgba(25, 80, 49, .07); }
                .room-card.active { border-color: #19764d; background: #f1f8f3; }
                .room-name { color: #244b35; font-size: 12px; }
                .room-info { color: #7d8a81; font-size: 10px; line-height: 1.5; }
                .room-action { color: #19764d; font-size: 10px; }
                .department-placeholder, .empty-department { border-color: #dce7df; border-radius: 15px; background: #fff; }
                .department-placeholder h3 { color: #244b35; }
                .schedule-panel { overflow: hidden; }
                .schedule-panel-header { background: linear-gradient(100deg, #176b45, #218457); padding: 17px 20px; }
                .schedule-panel-title { font-size: 15px; }
                .schedule-panel-subtitle { font-size: 10px; }
                .faculty-timetable { border-color: #e3ebe5; border-radius: 12px; }
                .faculty-timetable th { background: #f1f7f3; color: #315c43; font-size: 11px; }
                .faculty-timetable th, .faculty-timetable td { border-color: #e6ede8; }
                .faculty-timetable .time-column { background: #fafcfb; color: #526158; font-size: 11px; }
                .class-entry { border-radius: 9px; }
                .schedule-error-title { color: #294638; }
                .retry-schedule-btn { border-radius: 9px; background: #19764d; }
                @media (max-width: 900px) { .department-list { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
                @media (max-width: 600px) {
                    .faculty-schedule-page { padding-top: 4px; }
                    .schedule-header { margin-bottom: 19px; padding: 21px 19px; border-radius: 16px; }
                    .schedule-header::after { right: -115px; }
                    .schedule-title { font-size: 23px; }
                    .department-list { grid-template-columns: 1fr; gap: 10px; }
                    .department-button { min-height: 116px; }
                }

                .shared-reservation-entry { display:flex; flex-direction:column; align-items:flex-start; width:100%; min-height:90px; padding:9px; border:1px solid #ead9ad; border-radius:9px; background:#fff8e8; color:#344054; text-align:left; cursor:pointer; }
                .shared-reservation-entry:hover { border-color:#d5b66e; background:#fff4d8; box-shadow:0 4px 12px rgba(117,83,12,.09); }
                .shared-reservation-entry .reservation-badge { display:inline-flex; align-items:center; gap:4px; padding:4px 6px; border-radius:999px; background:#fff0c7; color:#8a5a00; font-size:8px; font-weight:850; }
                .shared-reservation-entry .reservation-course { margin-top:6px; color:#71500e; font-size:10px; font-weight:850; line-height:1.3; }
                .shared-reservation-entry .reservation-purpose,.shared-reservation-entry .reservation-requester { margin-top:4px; color:#6c6656; font-size:9px; }
                .shared-reservation-entry .reservation-expiration { margin-top:auto; padding-top:6px; color:#8a5a00; font-size:8px; font-weight:750; }
                .room-reservation-details-overlay { position:fixed; inset:0; z-index:100000; display:grid; place-items:center; padding:18px; background:rgba(17,39,27,.5); backdrop-filter:blur(3px); }
                .room-reservation-details-dialog { width:min(520px,100%); max-height:85vh; overflow:auto; border:1px solid #dce7df; border-radius:17px; background:#fff; box-shadow:0 24px 70px rgba(0,0,0,.22); }
                .room-reservation-details-dialog header { display:flex; justify-content:space-between; gap:15px; align-items:flex-start; padding:18px 20px; border-bottom:1px solid #e7eee9; background:#f7faf8; }
                .room-reservation-details-dialog header span { color:#56816a; font-size:9px; font-weight:800; letter-spacing:.12em; }
                .room-reservation-details-dialog h2 { margin:5px 0 0; color:#193e2d; font-size:17px; }
                .room-reservation-details-dialog header button { width:32px; height:32px; border:1px solid #e1e9e3; border-radius:9px; background:#fff; color:#66756d; font-size:22px; cursor:pointer; }
                .room-reservation-details-list { display:grid; gap:9px; padding:16px 20px 20px; }
                .room-reservation-detail-card { padding:12px; border:1px solid #e4ebe6; border-radius:11px; background:#fbfdfb; }
                .room-reservation-detail-card > div { display:flex; justify-content:space-between; gap:12px; color:#315b41; font-size:11px; }
                .room-reservation-detail-card p { margin:8px 0 3px; color:#34483a; font-size:12px; font-weight:750; }
                .room-reservation-detail-card small { color:#748178; font-size:10px; }

                /* Faculty schedules refinement: clearer browsing hierarchy and timetable details. */
                body.portal-shell.faculty-portal .main-layout > .content.faculty-schedules-view {
                    min-height:100vh;
                    padding:128px clamp(20px,3vw,42px) 46px !important;
                    background:
                        radial-gradient(ellipse at 88% 2%,rgba(215,195,145,.25),transparent 25%),
                        radial-gradient(ellipse at 8% 96%,rgba(204,224,208,.36),transparent 30%),
                        linear-gradient(145deg,#edf2ec 0%,#f8f7f2 58%,#f1f0e8 100%);
                    color:#263a2f;
                    font-family:'DM Sans',Arial,sans-serif;
                }
                .faculty-schedules-view,
                .faculty-schedules-view * { font-family:'DM Sans',Arial,sans-serif; }
                .faculty-schedules-view .faculty-schedule-page { width:min(100%,1480px); max-width:1480px; margin:0 auto; padding:0 0 42px; }
                .faculty-schedules-view .schedule-header {
                    position:relative;
                    isolation:isolate;
                    min-height:210px;
                    display:flex;
                    flex-direction:column;
                    justify-content:center;
                    margin:0 0 24px;
                    padding:clamp(30px,4vw,44px) clamp(25px,4vw,46px);
                    border:1px solid rgba(232,215,175,.22);
                    border-radius:24px;
                    background:radial-gradient(circle at 78% 18%,rgba(217,196,143,.17),transparent 25%),linear-gradient(118deg,#153a32 0%,#215440 58%,#37684e 100%);
                    box-shadow:0 20px 44px rgba(24,58,43,.16);
                }
                .faculty-schedules-view .schedule-header::before { position:relative; z-index:1; margin:0 0 12px; color:#e2ca8d; content:"FACULTY WORKSPACE  /  CLASSROOM SCHEDULES"; font-size:11px; font-weight:850; letter-spacing:.16em; }
                .faculty-schedules-view .schedule-header::after { top:-145px; right:95px; width:330px; height:330px; border-color:rgba(244,232,200,.22); box-shadow:0 0 0 27px rgba(244,232,200,.035),0 0 0 63px rgba(244,232,200,.026),0 0 0 102px rgba(244,232,200,.018); }
                .faculty-schedules-view .schedule-title { position:relative; z-index:1; margin:0 0 10px; color:#fff !important; font:800 clamp(34px,3.2vw,44px)/1.12 'Manrope','DM Sans',sans-serif; letter-spacing:-.045em; }
                .faculty-schedules-view .schedule-subtitle { position:relative; z-index:1; max-width:680px; margin:0; color:#e0e9e1; font-size:15px; font-weight:550; line-height:1.65; }
                .faculty-schedules-view .faculty-schedules-section-heading { display:flex; align-items:center; gap:11px; margin:0 0 13px; color:#294437; font:800 19px/1.3 'Manrope','DM Sans',sans-serif; }
                .faculty-schedules-view .faculty-schedules-section-heading::before { width:4px; height:24px; border-radius:4px; background:#d1b76f; content:""; }
                .faculty-schedules-view .department-list { grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; margin-bottom:23px; }
                .faculty-schedules-view .department-card { border-color:#dfe8de; border-radius:17px; background:linear-gradient(155deg,#fff,#fbfcf9); box-shadow:0 7px 20px rgba(31,57,43,.045); }
                .faculty-schedules-view .department-card:hover { border-color:#9cbda1; box-shadow:0 12px 25px rgba(31,57,43,.08); }
                .faculty-schedules-view .department-card.active { border:1px solid #32704d; background:#f3f8f0; box-shadow:0 0 0 3px rgba(50,112,77,.1); }
                .faculty-schedules-view .department-button { min-height:152px; padding:19px 20px; }
                .faculty-schedules-view .department-icon { width:44px; height:44px; margin-bottom:13px; border:1px solid #e2e9df; border-radius:14px; background:#edf3eb; color:#285940; font-size:18px; }
                .faculty-schedules-view .department-name { margin-bottom:5px; color:#203d30; font-size:16px; font-weight:850; line-height:1.4; }
                .faculty-schedules-view .department-info { color:#65756a; font-size:12px; font-weight:600; }
                .faculty-schedules-view .department-arrow { color:#9a8450; font-size:19px; }
                .faculty-schedules-view .selected-department-header,
                .faculty-schedules-view .building-card,
                .faculty-schedules-view .schedule-panel { border-color:#dce6dc; border-radius:17px; box-shadow:0 8px 24px rgba(31,57,43,.05); }
                .faculty-schedules-view .selected-department-header { margin-bottom:14px; padding:19px 21px; background:linear-gradient(110deg,#f6f8f3,#fff); }
                .faculty-schedules-view .selected-department-title { color:#203d30; font:800 21px/1.3 'Manrope','DM Sans',sans-serif; }
                .faculty-schedules-view .selected-department-subtitle { margin-top:5px; color:#65756a; font-size:13px; font-weight:550; line-height:1.5; }
                .faculty-schedules-view .building-list { gap:12px; }
                .faculty-schedules-view .building-card { overflow:hidden; background:#fff; }
                .faculty-schedules-view .building-button { min-height:72px; padding:15px 19px; background:#fff; }
                .faculty-schedules-view .building-button:hover,.faculty-schedules-view .building-button.active { background:#f4f8f1; }
                .faculty-schedules-view .building-left { gap:13px; }
                .faculty-schedules-view .building-icon { width:42px; height:42px; border:1px solid #e2e9df; border-radius:13px; background:#edf3eb; color:#285940; font-size:17px; }
                .faculty-schedules-view .building-name { color:#294437; font-size:15px; font-weight:850; }
                .faculty-schedules-view .building-department { margin-top:4px; color:#65756a; font-size:12px; font-weight:600; }
                .faculty-schedules-view .building-arrow { color:#84958a; }
                .faculty-schedules-view .rooms-container { gap:10px; padding:15px 18px 18px; border-top-color:#e6ece5; background:#f7f9f5; }
                .faculty-schedules-view .room-card { min-height:105px; padding:14px; border-color:#e1e9df; border-radius:12px; background:#fff; box-shadow:0 4px 12px rgba(31,57,43,.035); }
                .faculty-schedules-view .room-card:hover { border-color:#9cbda1; box-shadow:0 8px 17px rgba(31,57,43,.065); }
                .faculty-schedules-view .room-card.active { border-color:#32704d; background:#f3f8f0; }
                .faculty-schedules-view .room-name { color:#294437; font-size:15px; font-weight:850; }
                .faculty-schedules-view .room-info { margin-top:5px; color:#65756a; font-size:12px; font-weight:550; line-height:1.45; }
                .faculty-schedules-view .room-action { margin-top:9px; color:#245940; font-size:11px; font-weight:850; letter-spacing:.04em; }
                .faculty-schedules-view .department-placeholder,
                .faculty-schedules-view .empty-department { border-color:#dce6dc; border-radius:17px; background:rgba(255,255,255,.9); box-shadow:0 7px 20px rgba(31,57,43,.04); }
                .faculty-schedules-view .department-placeholder { padding:42px 24px; }
                .faculty-schedules-view .department-placeholder-icon { width:58px; height:58px; border:1px solid #e2e9df; border-radius:17px; background:#edf3eb; color:#285940; font-size:24px; }
                .faculty-schedules-view .department-placeholder h3 { color:#294437; font-size:18px; font-weight:850; }
                .faculty-schedules-view .department-placeholder p { color:#65756a; font-size:13px; line-height:1.6; }
                .faculty-schedules-view .schedule-panel { margin-top:21px; }
                .faculty-schedules-view .schedule-panel-header { align-items:center; padding:20px 22px; background:linear-gradient(118deg,#153a32,#285b43); }
                .faculty-schedules-view .schedule-panel-title { color:#fff; font:800 21px/1.3 'Manrope','DM Sans',sans-serif; }
                .faculty-schedules-view .schedule-panel-subtitle { margin-top:5px; color:#e0e9e1; font-size:13px; font-weight:550; line-height:1.5; }
                .faculty-schedules-view .close-schedule { min-height:42px; padding:0 14px; border-color:rgba(255,255,255,.3); border-radius:10px; background:rgba(255,255,255,.11); font-size:12px; font-weight:850; }
                .faculty-schedules-view .close-schedule:hover { background:rgba(255,255,255,.19); }
                .faculty-schedules-view .schedule-legend { gap:9px 12px; padding:13px 18px; border-color:#e5ebe5; background:#f8faf6; color:#596d60; font-size:12px; line-height:1.45; }
                .faculty-schedules-view .schedule-legend-badge { padding:6px 9px; border-color:#ead9ad; background:#fff6df; color:#77571b; font-size:10px; font-weight:850; }
                .faculty-schedules-view .schedule-legend-badge:not(.reservation-legend-badge) { border-color:#dce6dc; background:#edf3eb; color:#285940; }
                .faculty-schedules-view .timetable-wrapper { padding:16px; overflow:auto; }
                .faculty-schedules-view .faculty-timetable { min-width:1280px; border-color:#dfe8de; border-radius:13px; background:#fff; }
                .faculty-schedules-view .faculty-timetable th,
                .faculty-schedules-view .faculty-timetable td { border-color:#e3ebe3; }
                .faculty-schedules-view .faculty-timetable th { position:sticky; top:0; z-index:3; height:52px; padding:11px 8px; background:#edf3eb; color:#245940; font-size:12px; font-weight:850; letter-spacing:.04em; }
                .faculty-schedules-view .faculty-timetable th:nth-child(1),
                .faculty-schedules-view .faculty-timetable th:nth-child(5),
                .faculty-schedules-view .faculty-timetable .time-column { width:120px; min-width:120px; max-width:120px; }
                .faculty-schedules-view .faculty-timetable .time-column { background:#f5f8f3; color:#405a49; font-size:12px; font-weight:850; }
                .faculty-schedules-view .faculty-timetable td:not(.time-column) { height:90px; padding:7px; }
                .faculty-schedules-view .empty-schedule-cell { color:#8b9a8e; font-size:11px; font-style:normal; font-weight:600; }
                .faculty-schedules-view .class-entry { min-height:78px; padding:9px; border:1px solid #d7e7d9; border-radius:10px; background:linear-gradient(145deg,#f0f7ef,#fbfdf9); box-shadow:inset 3px 0 #32704d; }
                .faculty-schedules-view .class-entry.room-swapped-temporary { border-color:#c9a7e8 !important; border-left:4px solid #7e22ce !important; background:linear-gradient(145deg,#f6edff,#fcf9ff) !important; box-shadow:inset 0 0 0 1px rgba(126,34,206,.08),0 3px 9px rgba(126,34,206,.1) !important; }
                .faculty-schedules-view .class-course { color:#245940; font-size:12px; font-weight:850; line-height:1.35; }
                .faculty-schedules-view .class-subject { color:#294437; font-size:11px; font-weight:750; line-height:1.35; }
                .faculty-schedules-view .class-instructor { color:#617268; font-size:11px; line-height:1.35; }
                .faculty-schedules-view .class-program,.faculty-schedules-view .class-major { color:#536a5b; font-size:10px; font-weight:700; line-height:1.35; }
                .faculty-schedules-view .class-type { color:#617268; font-size:10px; font-weight:800; }
                .faculty-schedules-view .room-swap-badge { border-color:#dec8f2; background:#f3e8ff; color:#6b21a8; font-size:9px; }
                .faculty-schedules-view .room-swap-details,.faculty-schedules-view .room-swap-original { color:#65418a; font-size:10px; line-height:1.4; }
                .faculty-schedules-view .class-entry.room-swapped-temporary .room-swap-badge { border-color:#dec8f2; background:#f3e8ff; color:#6b21a8; }
                .faculty-schedules-view .schedule-override-notice { padding:10px; border-color:#e6d19b; border-radius:10px; background:#fff7e2; color:#5b451d; font-size:11px; line-height:1.45; }
                .faculty-schedules-view .schedule-override-notice strong,.faculty-schedules-view .schedule-override-notice span { color:#604819; font-size:11px; }
                .faculty-schedules-view .temporary-reservation-entry { min-height:78px; padding:9px; border-color:#ead9ad; border-radius:10px; background:#fff8e8; }
                .faculty-schedules-view .reservation-badge { padding:5px 7px; background:#fff0c7; color:#79551a; font-size:9px; font-weight:850; }
                .faculty-schedules-view .reservation-course { color:#79551a; font-size:12px; font-weight:850; line-height:1.35; }
                .faculty-schedules-view .reservation-purpose { color:#5f6d62; font-size:11px; font-weight:700; line-height:1.4; }
                .faculty-schedules-view .reservation-requester { color:#64746a; font-size:10px; line-height:1.4; }
                .faculty-schedules-view .reservation-expiration { color:#77571b; font-size:10px; font-weight:800; }
                .faculty-schedules-view .loading-text { color:#52665a; font-size:13px; font-weight:700; }
                .faculty-schedules-view .schedule-error-title { color:#294437; font-size:17px; font-weight:850; }
                .faculty-schedules-view .schedule-error-message { color:#65756a; font-size:13px; line-height:1.6; }
                .faculty-schedules-view .retry-schedule-btn { min-height:42px; padding:0 15px; border-radius:10px; background:#245940; font-size:13px; font-weight:850; }
                .faculty-schedules-view .retry-schedule-btn:hover { background:#173b33; }
                .faculty-schedules-view .room-reservation-details-dialog { border-color:#dce6dc; border-radius:18px; box-shadow:0 24px 70px rgba(17,45,31,.22); }
                .faculty-schedules-view .room-reservation-details-dialog header { padding:19px 21px; border-color:#e5ebe5; background:#f6f8f3; }
                .faculty-schedules-view .room-reservation-details-dialog header span { color:#927c49; font-size:10px; font-weight:850; }
                .faculty-schedules-view .room-reservation-details-dialog h2 { color:#203d30; font:800 20px/1.3 'Manrope','DM Sans',sans-serif; }
                .faculty-schedules-view .room-reservation-detail-card { border-color:#e2e9df; border-radius:11px; background:#f9fbf8; }
                .faculty-schedules-view .room-reservation-detail-card > div { color:#315943; font-size:12px; }
                .faculty-schedules-view .room-reservation-detail-card p { color:#294437; font-size:14px; }
                .faculty-schedules-view .room-reservation-detail-card small { color:#64746a; font-size:12px; }
                @media(max-width:900px) { .faculty-schedules-view .department-list { grid-template-columns:repeat(2,minmax(0,1fr)); } }
                @media(max-width:760px) {
                    body.portal-shell.faculty-portal .main-layout > .content.faculty-schedules-view { min-height:calc(100vh - 64px); padding:22px 17px 32px !important; }
                    .faculty-schedules-view .faculty-schedule-page { padding:0 0 30px; }
                    .faculty-schedules-view .schedule-header { min-height:0; padding:27px 23px; border-radius:19px; }
                    .faculty-schedules-view .schedule-header::after { top:-85px; right:-90px; width:260px; height:260px; }
                    .faculty-schedules-view .schedule-title { font-size:34px; }
                    .faculty-schedules-view .schedule-subtitle { font-size:14px; }
                    .faculty-schedules-view .department-button { min-height:138px; }
                    .faculty-schedules-view .schedule-panel-header { align-items:flex-start; flex-direction:column; }
                    .faculty-schedules-view .close-schedule { width:100%; }
                    .faculty-schedules-view .timetable-wrapper { padding:10px; }
                }
                @media(max-width:520px) {
                    .faculty-schedules-view .schedule-header { padding:24px 20px; }
                    .faculty-schedules-view .schedule-title { font-size:31px; }
                    .faculty-schedules-view .schedule-header::before { font-size:9px; }
                    .faculty-schedules-view .department-list { grid-template-columns:1fr; gap:10px; }
                    .faculty-schedules-view .department-button { min-height:125px; }
                    .faculty-schedules-view .selected-department-header { padding:16px; }
                    .faculty-schedules-view .building-button { padding:13px; }
                    .faculty-schedules-view .rooms-container { padding:12px; }
                    .faculty-schedules-view .schedule-legend { align-items:flex-start; flex-direction:column; }
                }

                .faculty-schedules-view .published-schedule-block { display:flex !important; align-items:stretch !important; justify-content:flex-start !important; align-content:flex-start; gap:3px; padding:7px; overflow:hidden; text-align:left !important; }
                .faculty-schedules-view .published-schedule-block .class-course { width:100%; padding-right:62px; color:#245940; font-size:13px; line-height:1.2; text-align:left !important; }
                .faculty-schedules-view .published-schedule-block .class-time { position:absolute; top:5px; right:6px; margin:0; padding:2px 4px; border:1px solid #dce6dc; border-radius:5px; background:#fff; color:#344b5d; font-size:10px; font-weight:850; line-height:1.15; white-space:nowrap; }
                .faculty-schedules-view .published-schedule-block .class-subject { width:100%; color:#294437; font-size:12px; line-height:1.25; text-align:left !important; }
                .faculty-schedules-view .published-schedule-block .class-instructor { width:100%; margin-top:0; color:#536a5b; font-size:11px; line-height:1.2; text-align:left !important; }
                .faculty-schedules-view .published-schedule-block .class-program,.faculty-schedules-view .published-schedule-block .class-major,
                .faculty-schedules-view .published-schedule-block .class-type,.faculty-schedules-view .published-schedule-block .merged-course-details,
                .faculty-schedules-view .published-schedule-block .schedule-override-notice,.faculty-schedules-view .published-schedule-block .room-swap-details { width:100%; color:#536a5b; font-size:10px; line-height:1.2; text-align:left !important; }
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] { gap:2px; padding:4px 5px; }
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-course { font-size:10px; line-height:1.15; }
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-subject,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-instructor { display:block !important; margin:1px 0 0; font-size:9px; line-height:1.15; }
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-program,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-major,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-type,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .merged-course-details,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .room-swap-details { display:none !important; }
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .schedule-override-notice { display:grid !important; gap:1px; margin-top:2px; padding:2px 3px; font-size:7px; line-height:1.05; }
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .schedule-override-notice strong,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .schedule-override-notice span { font-size:7px; line-height:1.05; }

                /* Let full schedule details adapt to the available cell space. */
                .faculty-schedules-view .published-schedule-block { container-type:inline-size; overflow:auto; scrollbar-width:thin; scrollbar-color:#b8c8ba transparent; }
                .faculty-schedules-view .published-schedule-block .class-course { font-size:clamp(9px,3.2cqw,13px); overflow-wrap:anywhere; }
                .faculty-schedules-view .published-schedule-block .class-subject { font-size:clamp(8px,2.9cqw,12px); line-height:1.2; overflow-wrap:anywhere; }
                .faculty-schedules-view .published-schedule-block .class-instructor,
                .faculty-schedules-view .published-schedule-block .class-program,
                .faculty-schedules-view .published-schedule-block .class-major,
                .faculty-schedules-view .published-schedule-block .class-type,
                .faculty-schedules-view .published-schedule-block .merged-course-details,
                .faculty-schedules-view .published-schedule-block .room-swap-details { font-size:clamp(7px,2.4cqw,10px); line-height:1.15; overflow-wrap:anywhere; }
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-program,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-major,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .class-type,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .merged-course-details,
                .faculty-schedules-view .published-schedule-block[data-duration-minutes="30"] .room-swap-details { display:block !important; font-size:7px; line-height:1.05; }

                .faculty-schedules-view .shared-reservation-entry { display:grid; align-content:start; gap:4px; width:100%; min-width:0; min-height:82px; padding:8px; overflow:hidden; border-color:#ead9ad; border-left:4px solid #c99736; border-radius:10px; background:linear-gradient(145deg,#fff8e8,#fffdf8); text-align:left; }
                .faculty-schedules-view .shared-reservation-entry:hover,.faculty-schedules-view .shared-reservation-entry:focus-visible { border-color:#c99736; background:#fff4d8; box-shadow:0 0 0 2px rgba(201,151,54,.14); }
                .faculty-schedules-view .reservation-entry-top { display:flex; align-items:center; justify-content:space-between; gap:5px; min-width:0; }
                .faculty-schedules-view .shared-reservation-entry .reservation-badge { min-width:0; max-width:65%; margin:0; padding:4px 6px; overflow:hidden; background:#fff0c7; color:#79551a; font-size:8px; white-space:nowrap; }
                .faculty-schedules-view .shared-reservation-entry .reservation-time { flex:0 0 auto; color:#47574b; font-size:9px; font-weight:850; white-space:nowrap; }
                .faculty-schedules-view .shared-reservation-entry .reservation-course { margin:0; color:#79551a; font-size:11px; font-weight:900; line-height:1.2; overflow-wrap:anywhere; }
                .faculty-schedules-view .shared-reservation-entry .reservation-purpose { display:-webkit-box; margin:0; overflow:hidden; color:#5f6d62; font-size:9px; font-weight:650; line-height:1.25; -webkit-box-orient:vertical; -webkit-line-clamp:2; }
                .faculty-schedules-view .shared-reservation-entry .reservation-requester { margin:0; color:#64746a; font-size:9px; line-height:1.2; }
                .faculty-schedules-view .shared-reservation-entry .reservation-expiration { margin:0; padding:0; color:#79551a; font-size:8px; font-weight:850; }
                .faculty-schedules-view .room-reservation-detail-card .reservation-detail-top { display:flex; align-items:center; justify-content:space-between; gap:12px; grid-column:1/-1; }
                .faculty-schedules-view .room-reservation-detail-card .reservation-detail-code { grid-column:1/-1; color:#79551a; font-size:13px; font-weight:900; }
                .faculty-schedules-view .room-reservation-detail-card .reservation-detail-title { grid-column:1/-1; margin:0; color:#435449; font-size:12px; line-height:1.45; }
                .faculty-schedules-view .room-reservation-detail-card .reservation-detail-footer { display:flex; grid-column:1/-1; color:#68776d; font-size:11px; }

            </style>


            <div class="faculty-schedule-page">


                <!-- =====================================================
                     HEADER
                ====================================================== -->

                <div class="schedule-header">

                    <h1 class="schedule-title">
                        Classroom Schedule
                    </h1>

                    <div class="schedule-subtitle">
                        Select a college, building, and room
                        to view the current classroom schedule.
                    </div>

                </div>

                <h2 class="faculty-schedules-section-heading">Browse colleges</h2>


                <!-- =====================================================
                     DEPARTMENT CARDS
                ====================================================== -->

                <div class="department-list">

                    @forelse($departments as $department)

                        <div
                            class="department-card"
                            data-department-id="{{ $department['id'] }}"
                            onclick="
                                showDepartment(
                                    {{ $department['id'] }}
                                )
                            "
                        >

                            <button
                                type="button"
                                class="department-button"
                            >

                                <div class="department-icon">
                                    🏛
                                </div>


                                <div>

                                    <div class="department-name">
                                        {{ $department['name'] }}
                                    </div>


                                    <div class="department-info">

                                        {{ $department['buildings']->count() }}

                                        Building(s)

                                    </div>

                                </div>


                                <div class="department-arrow">
                                    →
                                </div>

                            </button>

                        </div>

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
                    >


                        <!-- SELECTED DEPARTMENT HEADER -->

                        <div class="selected-department-header">

                            <h2 class="selected-department-title">

                                {{ $department['name'] }}

                            </h2>


                            <p class="selected-department-subtitle">

                                Select a building below to view
                                its available classrooms.

                            </p>

                        </div>


                        <!-- =================================================
                             BUILDINGS
                        ================================================== -->

                        <div class="building-list">

                            @forelse($department['buildings'] as $building)

                                <div class="building-card">


                                    <!-- BUILDING HEADER -->

                                    <button
                                        type="button"
                                        class="building-button"
                                        onclick="
                                            toggleBuilding(this)
                                        "
                                    >

                                        <div class="building-left">

                                            <div class="building-icon">
                                                🏫
                                            </div>


                                            <div>

                                                <div class="building-name">

                                                    {{ $building['name'] }}

                                                </div>


                                                <div class="building-department">

                                                    {{ $department['name'] }}

                                                </div>

                                            </div>

                                        </div>


                                        <div class="building-arrow">
                                            ▼
                                        </div>

                                    </button>


                                    <!-- =================================================
                                         ROOMS
                                    ================================================== -->

                                    <div class="rooms-container">

                                        @forelse(
                                            $building['rooms']
                                            as $room
                                        )

                                            <div
                                                class="room-card"
                                                data-room-id="{{ $room->id }}"
                                                onclick="
                                                    loadRoomSchedule(
                                                        {{ $room->id }},
                                                        this,
                                                        '{{ addslashes($room->room_name) }}',
                                                        '{{ addslashes($building['name']) }}'
                                                    )
                                                "
                                            >


                                                <div class="room-top">

                                                    <div class="room-name">

                                                        {{ $room->room_name }}

                                                    </div>


                                                    <div class="room-arrow">
                                                        →
                                                    </div>

                                                </div>


                                                <div class="room-info">

                                                    View classroom schedule

                                                </div>


                                                <div class="room-action">

                                                    VIEW SCHEDULE

                                                </div>

                                            </div>

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

                        <div class="department-placeholder-icon">
                            🏫
                        </div>

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
                            Choose CAT, CCJEPA, or CED above
                            to view its buildings and rooms.
                        </p>

                    </div>

                @endif


                <!-- =====================================================
                     SCHEDULE PANEL
                ====================================================== -->

                <div
                    id="schedulePanel"
                    class="schedule-panel"
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
                                Close Schedule
                            </button>
                        </div>

                    </div>

                    <div class="schedule-legend">
                        <span class="schedule-legend-badge">
                            <i class="fa-solid fa-right-left"></i>
                            Room Swap Active
                        </span>
                        <span>
                            This class is temporarily using a different room.
                        </span>

                        <span class="schedule-legend-badge reservation-legend-badge">
                            <i class="fa-solid fa-calendar-check"></i>
                            Temporary Reservation
                        </span>
                        <span>
                            The room is temporarily reserved until the requested time ends.
                        </span>
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


/* =========================================================
   SHOW DEPARTMENT
========================================================= */

function showDepartment(departmentId)
{
    document
        .querySelectorAll('.department-content')
        .forEach(function(section) {

            section.classList.remove('open');

        });

    document
        .querySelectorAll('.department-card')
        .forEach(function(card) {

            card.classList.remove('active');

        });

    const selectedCard =
        document.querySelector(
            '.department-card[data-department-id="' +
            departmentId +
            '"]'
        );

    if (selectedCard) {

        selectedCard.classList.add('active');

    }

    const selectedDepartment =
        document.getElementById(
            'department-' +
            departmentId
        );

    if (selectedDepartment) {

        selectedDepartment.classList.add('open');

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

    if (isOpen) {

        rooms.classList.remove('open');

        button.classList.remove('active');

        return;

    }

    rooms.classList.add('open');

    button.classList.add('active');
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
            ' • College Chair Schedule';

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
        "{{ url('/faculty/schedule/room') }}/" +
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
            return [
                course.course_code,
                course.course_title,
                course.program,
                course.major,
                course.share_type
            ].map(value => String(value ?? '').trim().toLowerCase()).join('::');
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

        const currentSchedules = Array.isArray(current.schedule)
            ? current.schedule.filter(Boolean)
            : (current.schedule ? [current.schedule] : []);
        const currentSchedule = currentSchedules.length === 1
            ? currentSchedules[0]
            : currentSchedules;

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

        if (currentSchedules.length === 0) {

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

        const currentOccupied = currentSchedules.some(function(schedule) {
            return String(schedule.course_code || '').trim() !== ''
                || String(schedule.subject || '').trim() !== ''
                || String(schedule.instructor || '').trim() !== '';
        });

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

        const signature = currentSchedules
            .map(facultyScheduleMergeSignature)
            .sort()
            .join('###');

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

            const nextSchedules = Array.isArray(next.schedule)
                ? next.schedule.filter(Boolean)
                : (next.schedule ? [next.schedule] : []);
            if (nextSchedules.length === 0) {

                break;

            }

            const nextOccupied = nextSchedules.some(function(schedule) {
                return String(schedule.course_code || '').trim() !== ''
                    || String(schedule.subject || '').trim() !== ''
                    || String(schedule.instructor || '').trim() !== '';
            });

            if (!nextOccupied) {

                break;

            }

            /*
            |--------------------------------------------------------------------------
            | Compare schedules
            |--------------------------------------------------------------------------
            */

            const nextSignature = nextSchedules
                .map(facultyScheduleMergeSignature)
                .sort()
                .join('###');

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

            cellHtml = cellHtml.replace(/^<td\b([^>]*)>/i, function(match, attributes) {
                const classMatch = attributes.match(/\sclass=(['"])(.*?)\1/i);
                const classes = [classMatch ? classMatch[2] : '', 'merged-schedule-cell'].filter(Boolean).join(' ');
                const cleanAttributes = attributes.replace(/\sclass=(['"])(.*?)\1/i, '');
                return '<td' + cleanAttributes + ' colspan="' + colspan + '" class="' + classes + '">';
            });

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


function renderRoomOverrides(schedules, roomOverrides) {
    const content = document.getElementById('scheduleContent');
    const entriesById = new Map();
    (Array.isArray(roomOverrides) ? roomOverrides : []).forEach(function(item) { entriesById.set(String(item.id), item); });
    (Array.isArray(schedules) ? schedules : []).forEach(function(schedule) {
        (Array.isArray(schedule.schedule_overrides) ? schedule.schedule_overrides : []).forEach(function(item) {
            entriesById.set(String(item.id), Object.assign({}, item, { room_id: item.room_id || schedule.room_id, course_code: schedule.course_code || '' }));
        });
    });
    const entries = Array.from(entriesById.values());
    if (!content || !entries.length) return;

    const panel = document.createElement('section');
    panel.className = 'room-override-panel';
    panel.setAttribute('aria-label', 'Room reservations');
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
        const date = item.date ? new Date(item.date + 'T12:00:00').toLocaleDateString(undefined, {year:'numeric',month:'long',day:'numeric'}) : 'Date unavailable';
        const start = item.start_time ? new Date('2000-01-01T' + item.start_time).toLocaleTimeString(undefined, {hour:'numeric',minute:'2-digit'}) : '';
        const end = item.end_time ? new Date('2000-01-01T' + item.end_time).toLocaleTimeString(undefined, {hour:'numeric',minute:'2-digit'}) : '';
        const range = [start, end].filter(Boolean).join('–');
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
                const range = publishedScheduleRange(button.dataset.overrideStart + '-' + button.dataset.overrideEnd);
                if (wrapper) wrapper._selectedOverride = {
                    day: button.dataset.overrideDay,
                    roomId: String(button.dataset.overrideRoomId || ''),
                    start: range?.start,
                    end: range?.end,
                };
            }
            renderPublishedScheduleBlocks(content);
        });
    });
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
    | CREATE STANDARD 7 AM–7 PM ROWS
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

            MON: [],

            WED: [],

            FRI: [],

            TUE: [],

            THU: [],

            SAT: [],

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
        if (position === null || position < 0 || position >= defaultTimes.length) return;
        const day = String(slot.day || '').trim().toUpperCase().slice(0, 3);
        const label = slot.time || [slot.start_time, slot.end_time].filter(Boolean).join('-');
        if (!label) return;
        if (['MON', 'WED', 'FRI'].includes(day)) rows[position].leftTime = label;
        else if (['TUE', 'THU'].includes(day)) rows[position].rightTime = label;
        else if (day === 'SAT') rows[position].saturdayTime = label;
    });

    // Keep the room's saved row labels. Published schedule blocks use the
    // exact stored start/end times and align over these rows.

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

            if (position === null && schedule.row_position !== null && schedule.row_position !== '') {
                const savedPosition = Number(schedule.row_position);
                position = Number.isInteger(savedPosition) ? savedPosition : null;
            }

            /*
            |--------------------------------------------------------------------------
            | FALLBACK FOR OLD DATA
            |--------------------------------------------------------------------------
            */

            if (
                position === null ||
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
                position === null ||
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

                rows[position].MON.push(schedule);


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

                rows[position].WED.push(schedule);


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

                rows[position].FRI.push(schedule);


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

                rows[position].TUE.push(schedule);


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

                rows[position].THU.push(schedule);


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

                rows[position].SAT.push(schedule);


            }

        }
    );

    ['MON', 'WED', 'FRI', 'TUE', 'THU', 'SAT'].forEach(function(day) {
        Object.values(rows).forEach(function(row) {
            row[day].sort(function(left, right) {
                const leftRange = publishedScheduleRange(left.time);
                const rightRange = publishedScheduleRange(right.time);
                return (leftRange?.start ?? 0) - (rightRange?.start ?? 0);
            });
        });
    });

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
    | CREATE STANDARD 7 AM–7 PM ROWS
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
    Array.from(table.querySelectorAll('.class-entry[data-view-schedule-time]')).forEach(function(source) {
        const range = publishedScheduleRange(source.dataset.viewScheduleTime);
        const cell = source.closest('td');
        const side = sideByDay[String(source.dataset.viewScheduleDay || '').toUpperCase().slice(0, 3)];
        if (!range || !cell || !side) return;

        let top = yAtMinute(side, range.start, 'start');
        let bottom = yAtMinute(side, range.end, 'end');
        if (top === null || bottom === null || bottom <= top) return;

        const cellRect = cell.getBoundingClientRect();
        const day = String(source.dataset.viewScheduleDay || '').trim().toUpperCase().slice(0, 3);
        const rowPosition = Number(source.closest('tr')?.dataset.rowPosition);
        const rowTime = cell.dataset.manualRowTime || '';
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
        block.title = [cell.dataset.viewScheduleTime || '', source.innerText || ''].filter(Boolean).join(' · ');
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

    if (Array.isArray(schedule)) {
        const scheduleCards = schedule.map(function(item) {
            return buildScheduleCell(item).replace(/^\s*<td[^>]*>/i, '').replace(/<\/td>\s*$/i, '');
        }).join('');

        return `<td class="schedule-entry-stack">${scheduleCards}</td>`;
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

    if (Array.isArray(schedule.schedule_overrides) && schedule.schedule_overrides.length) {
        if (!window.scheduleOverrideRefreshTimer) {
            const now = new Date();
            const nextMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 2);
            window.scheduleOverrideRefreshTimer = window.setTimeout(function () {
                window.location.reload();
            }, Math.min(nextMidnight.getTime() - now.getTime(), 2147480000));
        }
    }

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
                data-view-schedule-time="${escapeHtml(schedule.time || '')}"
                data-view-schedule-day="${escapeHtml(schedule.day || '')}"
                data-view-schedule-room-id="${escapeHtml(schedule.room_id || '')}"
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

                <div class="class-time">
                    ${escapeHtml(publishedFormatScheduleTime(schedule.time || ''))}
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

