 <?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="wrapper">

   

    <div class="main-layout">

        <?php echo $__env->make('chair.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="content">

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

                .section {
                    background: white;
                    padding: 25px;
                    border-radius: 12px;
                    box-shadow: 0 2px 10px rgba(0,0,0,.07);
                    margin-bottom: 20px;
                }

                .filters {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 15px;
                }

                .filter-group label {
                    display: block;
                    font-size: 13px;
                    font-weight: bold;
                    color: #555;
                    margin-bottom: 6px;
                }

                .filter-group select {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    background: white;
                }

                .schedule-title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 15px;
                }

                .room-info {
                    background: #e8f5e9;
                    padding: 12px 15px;
                    border-radius: 7px;
                    margin-bottom: 20px;
                    color: #2e7d32;
                }

                .room-schedule-meta {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 12px;
                    margin-bottom: 15px;
                }

                .room-schedule-meta-item {
                    background: #ffffff;
                    border: 1px solid #dfe7e2;
                    border-radius: 9px;
                    padding: 11px 14px;
                }

                .room-schedule-meta-label {
                    display: block;
                    color: #667085;
                    font-size: 10px;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: .4px;
                    margin-bottom: 4px;
                }

                .room-schedule-meta-item strong {
                    color: #176b3a;
                    font-size: 14px;
                }


                .schedule-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .schedule-table th {
                    background: #2e7d32;
                    color: white;
                    padding: 11px;
                    text-align: center;
                    font-size: 13px;
                }

                .schedule-table td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                    min-width: 130px;
                    height: 90px;
                    vertical-align: middle;
                }

                .time-cell {
                    background: #f5f5f5;
                    font-weight: bold;
                    color: #555;
                    width: 130px;
                }

                .schedule-cell {
                    background: white;
                }

                .filled {
                    background: #e8f5e9 !important;
                    color: #333;
                }

                .locked {
                    display: inline-block;
                    margin-top: 5px;
                    font-size: 11px;
                    color: #777;
                }

                .empty-slot {
                    background: #fafafa;
                    color: #aaa;
                }

                .add-button {
                    display: inline-block;
                    margin-top: 6px;
                    padding: 6px 10px;
                    border: none;
                    border-radius: 5px;
                    background: #2e7d32;
                    color: white;
                    font-size: 11px;
                    font-weight: bold;
                    cursor: pointer;
                }

                .add-button:hover {
                    background: #1b5e20;
                }

                .not-allowed {
                    display: inline-block;
                    margin-top: 5px;
                    font-size: 11px;
                    color: #999;
                }

                .schedule-course {
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 4px;
                }

                .schedule-subject {
                    font-size: 12px;
                    margin-bottom: 3px;
                }

                .schedule-instructor {
                    font-size: 11px;
                    color: #777;
                }

                .schedule-year {
                    font-size: 11px;
                    color: #777;
                }

                .message {
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }

                .success {
                    background: #e8f5e9;
                    color: #2e7d32;
                }

                .error {
                    background: #ffebee;
                    color: #b71c1c;
                }

                .permission-info {
                    padding: 10px 14px;
                    background: #fff8e1;
                    border-left: 4px solid #f9a825;
                    border-radius: 5px;
                    color: #6d4c00;
                    font-size: 13px;
                    margin-bottom: 20px;
                }

                .empty {
                    padding: 30px;
                    text-align: center;
                    color: #777;
                }

                /* =========================================================
   PROFESSIONAL CLASSROOM NAVIGATION
========================================================= */

.navigation-section {
    background: #ffffff;

    border: 1px solid #e3e8e5;

    border-radius: 14px;

    padding: 25px;

    margin-bottom: 20px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.04);
}

.navigation-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding-bottom: 20px;

    border-bottom: 1px solid #edf0ee;

    margin-bottom: 20px;
}

.navigation-title {
    display: flex;
    align-items: center;
    gap: 9px;

    color: #1f2937;

    font-size: 18px;

    font-weight: 700;
}

.navigation-title i {
    color: #2e7d32;
}

.navigation-subtitle {
    margin-top: 5px;

    color: #667085;

    font-size: 12px;
}


/* =========================================================
   LEVEL TITLE
========================================================= */

.level-title {
    display: flex;
    align-items: center;
    gap: 8px;

    color: #344054;

    font-size: 14px;

    font-weight: 700;

    margin-bottom: 12px;
}

.level-title i {
    color: #2e7d32;
}


/* =========================================================
   DEPARTMENT CARDS
========================================================= */

.department-list,
.building-list {
    display: flex;
    flex-direction: column;

    gap: 10px;
}

.department-card,
.building-card {
    width: 100%;

    border: 1px solid #e1e7e3;

    background: #ffffff;

    border-radius: 10px;

    padding: 15px 17px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    text-align: left;

    cursor: pointer;

    transition:
        border-color .18s ease,
        background .18s ease,
        transform .18s ease,
        box-shadow .18s ease;
}

.department-card:hover,
.building-card:hover {
    background: #f7fbf8;

    border-color: #b8d8bd;

    transform: translateY(-1px);

    box-shadow:
        0 4px 10px rgba(0,0,0,.05);
}

.card-left {
    display: flex;

    align-items: center;

    gap: 13px;
}

.card-icon {
    width: 43px;
    height: 43px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #eaf6ed;

    color: #2e7d32;

    font-size: 17px;
}

.card-name {
    color: #1f2937;

    font-size: 14px;

    font-weight: 700;
}

.card-description {
    color: #98a2b3;

    font-size: 11px;

    margin-top: 3px;
}

.card-count {
    color: #667085;

    font-size: 11px;

    margin-top: 3px;
}

.card-arrow {
    width: 31px;
    height: 31px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 7px;

    color: #667085;

    background: #f6f8f7;

    transition:
        background .18s ease,
        color .18s ease;
}

.department-card:hover .card-arrow,
.building-card:hover .card-arrow {
    background: #e8f5e9;

    color: #2e7d32;
}


/* =========================================================
   BREADCRUMB
========================================================= */

.breadcrumb {
    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 18px;

    font-size: 12px;

    color: #98a2b3;
}

.breadcrumb strong {
    color: #344054;
}

.back-button {
    border: none;

    background: transparent;

    color: #2e7d32;

    font-size: 12px;

    font-weight: 700;

    cursor: pointer;

    display: flex;

    align-items: center;

    gap: 6px;

    padding: 0;
}

.back-button:hover {
    color: #1b5e20;
}


/* =========================================================
   ROOM CARDS
========================================================= */

.room-selection-grid {
    display: grid;

    grid-template-columns:
        repeat(auto-fill, minmax(220px, 1fr));

    gap: 12px;
}

.room-selection-card {
    background: #ffffff;

    border: 1px solid #e1e7e3;

    border-radius: 10px;

    padding: 17px;

    cursor: pointer;

    transition:
        transform .18s ease,
        border-color .18s ease,
        box-shadow .18s ease;
}

.room-selection-card:hover {
    transform: translateY(-2px);

    border-color: #9fcaa6;

    box-shadow:
        0 6px 15px rgba(0,0,0,.06);
}

.room-selection-top {
    display: flex;

    align-items: center;

    gap: 11px;

    margin-bottom: 14px;
}

.room-selection-icon {
    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #eaf6ed;

    color: #2e7d32;

    border-radius: 8px;
}

.room-selection-name {
    color: #1f2937;

    font-size: 14px;

    font-weight: 700;
}

.room-selection-label {
    color: #98a2b3;

    font-size: 10px;

    margin-top: 2px;
}

.open-room-button {
    width: 100%;

    border: 1px solid #2e7d32;

    background: #2e7d32;

    color: #ffffff;

    padding: 9px;

    border-radius: 7px;

    font-size: 11px;

    font-weight: 700;

    cursor: pointer;
}

.open-room-button:hover {
    background: #256b2a;
}


/* =========================================================
   SCHEDULE SECTION
========================================================= */

.schedule-section {
    background: #ffffff;

    border: 1px solid #e1e7e3;

    border-radius: 14px;

    padding: 20px;

    box-shadow:
        0 2px 8px rgba(0,0,0,.04);
}

.schedule-header {
    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 15px;
}

.schedule-main-title {
    display: flex;

    align-items: center;

    gap: 8px;

    color: #1f2937;

    font-size: 18px;

    font-weight: 700;
}

.schedule-main-title i {
    color: #2e7d32;
}

.schedule-room-name {
    color: #667085;

    font-size: 12px;

    margin-top: 4px;
}

.change-room-button {
    border: 1px solid #d0d5d2;

    background: #ffffff;

    color: #475467;

    padding: 9px 13px;

    border-radius: 7px;

    cursor: pointer;

    font-size: 11px;

    font-weight: 700;
}

.change-room-button:hover {
    border-color: #2e7d32;

    color: #2e7d32;

    background: #f8fbf9;
}


/* =========================================================
   ROOM INFO
========================================================= */

.room-info {
    background: #f0f8f2;

    border: 1px solid #d8eadb;

    padding: 12px 15px;

    border-radius: 8px;

    margin-bottom: 12px;

    color: #176b3a;

    font-size: 12px;
}


/* =========================================================
   PERMISSION INFO
========================================================= */

.permission-info {
    display: flex;

    align-items: center;

    gap: 8px;

    padding: 10px 13px;

    background: #fff9e8;

    border: 1px solid #f1dfad;

    border-radius: 7px;

    color: #765b13;

    font-size: 11px;

    margin-bottom: 15px;
}

.permission-info i {
    color: #d99a00;
}


/* =========================================================
   EXCEL TABLE
========================================================= */

.excel-wrapper {
    width: 100%;

    overflow-x: auto;

    border: 1px solid #dfe5e1;

    border-radius: 8px;
}

.excel-schedule {
    width: 100%;

    min-width: 850px;

    border-collapse: collapse;

    table-layout: fixed;

    background: #ffffff;
}

.excel-schedule th {
    background: #2e7d32;

    color: #ffffff;

    border: 1px solid #276b2b;

    height: 42px;

    padding: 8px;

    text-align: center;

    font-size: 11px;

    font-weight: 700;

    letter-spacing: .3px;
}

.excel-schedule th.time-header {
    width: 105px;
}

.excel-schedule td {
    border: 1px solid #dfe5e1;

    height: 72px;

    padding: 6px;

    text-align: center;

    vertical-align: middle;

    font-size: 11px;
}

.excel-schedule td.time-cell {
    background: #f7f9f8;

    color: #475467;

    font-weight: 700;

    font-size: 10px;
}

.excel-schedule td.schedule-cell {
    background: #ffffff;

    transition:
        background .15s ease;
}

.excel-schedule td.schedule-cell:hover {
    background: #f7fbf8;
}


/* =========================================================
   FILLED SCHEDULE
========================================================= */

.excel-schedule td.filled {
    background: #eaf6ed !important;

    color: #1f2937;
}

.schedule-course {
    color: #176b3a;

    font-weight: 800;

    font-size: 11px;

    margin-bottom: 3px;
}

.schedule-subject {
    color: #344054;

    font-size: 10px;

    line-height: 1.25;

    margin-bottom: 3px;
}

.schedule-instructor {
    color: #667085;

    font-size: 9px;

    line-height: 1.2;
}

.schedule-year {
    color: #667085;

    font-size: 9px;

    margin-top: 2px;
}

.editable-label {
    display: block;

    color: #2e7d32;

    font-size: 8px;

    font-weight: 700;

    margin-top: 4px;
}

.locked {
    display: block;

    color: #98a2b3;

    font-size: 9px;

    margin-top: 4px;
}


/* =========================================================
   EMPTY SLOT
========================================================= */

.excel-schedule td.empty-slot {
    background: #fafcfb;

    color: #98a2b3;
}

.excel-schedule td.allowed-other {
    background: #f7fbf8;
}

.add-button {
    margin-top: 5px;

    padding: 5px 8px;

    border: none;

    border-radius: 5px;

    background: #2e7d32;

    color: #ffffff;

    font-size: 9px;

    font-weight: 700;

    cursor: pointer;
}

.add-button:hover {
    background: #1b5e20;
}

.not-allowed {
    display: inline-block;

    margin-top: 5px;

    color: #98a2b3;

    font-size: 9px;
}


/* =========================================================
   NOON BREAK
========================================================= */

.excel-schedule .noon-break-row td {
    height: 34px;

    background: #f7f9f8;

    color: #667085;

    font-size: 10px;

    font-weight: 800;

    letter-spacing: 2px;

    text-align: center;

    border-top: 1px solid #dfe5e1;

    border-bottom: 1px solid #dfe5e1;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 700px) {

    .navigation-section,
    .schedule-section {
        padding: 15px;
    }

    .schedule-header {
        align-items: flex-start;

        gap: 10px;
    }

    .schedule-main-title {
        font-size: 16px;
    }

    .room-selection-grid {
        grid-template-columns: 1fr;
    }

}


                /* =========================================================
                   EXCEL EDITOR CONTROLS
                ========================================================= */

                @keyframes modifyEditorReveal {
                    from {
                        opacity: 0;
                        transform: translateY(-8px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .editor-panel {
                    animation: modifyEditorReveal .25s ease-out;
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 12px;
                    padding: 20px;
                    margin-bottom: 18px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.04);
                }

                .editor-title {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    color: #1f2937;
                    font-size: 18px;
                    font-weight: 700;
                    margin-bottom: 5px;
                }

                .editor-title i { color: #2e7d32; }

                .editor-subtitle {
                    color: #667085;
                    font-size: 12px;
                    line-height: 1.55;
                    margin-bottom: 18px;
                }

                .editor-grid {
                    display: grid;
                    grid-template-columns: repeat(6, minmax(0, 1fr));
                    gap: 10px;
                    align-items: end;
                }

                .editor-field label {
                    display: block;
                    color: #475467;
                    font-size: 11px;
                    font-weight: 700;
                    margin-bottom: 5px;
                }

                .editor-field input,
                .editor-field select {
                    width: 100%;
                    height: 39px;
                    padding: 8px 10px;
                    border: 1px solid #d0d5d2;
                    border-radius: 7px;
                    background: #ffffff;
                    color: #1f2937;
                    font-size: 12px;
                    outline: none;
                }

                .editor-field input:focus,
                .editor-field select:focus {
                    border-color: #2e7d32;
                    box-shadow: 0 0 0 3px rgba(46,125,50,.08);
                }

                .editor-field input[readonly] {
                    background: #f7f9f8;
                    color: #667085;
                }

                .editor-color {
                    display: flex;
                    align-items: flex-end;
                }

                .editor-color input[type="color"] {
                    width: 52px;
                    height: 39px;
                    padding: 3px;
                    border: 1px solid #d0d5d2;
                    border-radius: 7px;
                    background: #ffffff;
                    cursor: pointer;
                }

                .editor-metadata {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 8px;
                    margin-top: 12px;
                }
                .editor-meta-item {
                    background: #f7f9f8;
                    border: 1px solid #e1e7e3;
                    border-radius: 7px;
                    padding: 9px 10px;
                    min-width: 0;
                }
                .editor-meta-label {
                    display: block;
                    color: #98a2b3;
                    font-size: 10px;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: .3px;
                    margin-bottom: 3px;
                }
                .editor-meta-value {
                    display: block;
                    color: #344054;
                    font-size: 11px;
                    font-weight: 700;
                    line-height: 1.35;
                    word-break: break-word;
                }
                .editor-color-inline {
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    padding: 7px 9px;
                    border: 1px solid #d0d5d2;
                    border-radius: 7px;
                    background: #ffffff;
                    color: #475467;
                    font-size: 11px;
                    font-weight: 700;
                }
                .editor-color-inline input[type="color"] {
                    width: 32px;
                    height: 28px;
                    padding: 2px;
                    border: 1px solid #d0d5d2;
                    border-radius: 5px;
                    background: #ffffff;
                    cursor: pointer;
                }
                .editor-status {
                    min-height: 36px;
                    display: flex;
                    align-items: center;
                    padding: 9px 12px;
                    margin-top: 12px;
                    border-radius: 7px;
                    background: #f7f9f8;
                    color: #667085;
                    font-size: 11px;
                }

                .editor-status.success {
                    background: #e8f5e9;
                    color: #2e7d32;
                }

                .editor-status.warning {
                    background: #fff8e1;
                    color: #6d4c00;
                }

                .editor-status.error {
                    background: #ffebee;
                    color: #b71c1c;
                }

                .editor-actions {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 10px;
                    flex-wrap: wrap;
                    margin-top: 15px;
                }

                .editor-actions-left,
                .editor-actions-right {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    flex-wrap: wrap;
                }

                .editor-button {
                    border: none;
                    padding: 9px 14px;
                    border-radius: 7px;
                    cursor: pointer;
                    font-size: 11px;
                    font-weight: 700;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    transition: .15s ease;
                }

                .editor-button:hover { transform: translateY(-1px); }

                .editor-save { background: #2e7d32; color: #ffffff; }
                .editor-save:hover { background: #1b5e20; }

                .editor-clear { background: #c62828; color: #ffffff; }
                .editor-clear:hover { background: #a61b1b; }

                .editor-cancel { background: #777; color: #ffffff; }
                .editor-cancel:hover { background: #616161; }

                .schedule-cell.editable-cell { cursor: pointer; }

                .schedule-cell.editable-cell:hover {
                    background: #f1f9f3 !important;
                }

                .schedule-cell.is-selected-edit {
                    outline: 3px solid #2e7d32 !important;
                    outline-offset: -3px;
                    box-shadow: inset 0 0 0 2px rgba(46,125,50,.12);
                }

                @media (max-width: 1100px) {
                    .editor-grid {
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                    }
                }

                @media (max-width: 700px) {
                    .editor-grid {
                        grid-template-columns: 1fr 1fr;
                    }

                    .editor-metadata {
                        grid-template-columns: 1fr;
                    }
                }

                /* MODAL */

                .modal {
                    display: none;
                    position: fixed;
                    z-index: 9999;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,.5);
                }

                .modal-content {
                    background: white;
                    width: 450px;
                    max-width: 90%;
                    margin: 7% auto;
                    padding: 25px;
                    border-radius: 10px;
                    position: relative;
                }

                .modal-title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 20px;
                }

                .close {
                    position: absolute;
                    right: 18px;
                    top: 12px;
                    font-size: 25px;
                    cursor: pointer;
                    color: #777;
                }

                .form-group {
                    margin-bottom: 14px;
                }

                .form-group label {
                    display: block;
                    font-size: 13px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .form-group input,
                .form-group select {
                    width: 100%;
                    padding: 9px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                }

                .save-button {
                    width: 100%;
                    padding: 11px;
                    border: none;
                    border-radius: 6px;
                    background: #2e7d32;
                    color: white;
                    font-weight: bold;
                    cursor: pointer;
                }

                .save-button:hover {
                    background: #1b5e20;
                }

                
                @media(max-width: 800px) {

                    .filters {
                        grid-template-columns: 1fr;
                    }

                    .schedule-table {
                        font-size: 11px;
                    }

                    .schedule-table td {
                        min-width: 100px;
                    }

                }

            </style>


            <div class="page">

                <div class="title">
                    Modify Schedule
                </div>

                <div class="subtitle">
                    View schedules from all departments and add
                    schedules to available empty slots.
                </div>

                <div style="margin:15px 0 20px;padding:12px 14px;background:#eef8ef;border:1px solid #d7e8da;border-radius:9px;color:#176b3a;font-size:12px;font-weight:700;">
                    Active Term: <?php echo e($activeTerm->academic_year ?? '-'); ?> • <?php echo e($activeTerm->semester ?? '-'); ?> — Schedule Editor is working on this term only.
                </div>


                

                <?php if(session('success')): ?>

                    <div class="message success">
                        <?php echo e(session('success')); ?>

                    </div>

                <?php endif; ?>


                

                <?php if(session('error')): ?>

                    <div class="message error">
                        <?php echo e(session('error')); ?>

                    </div>

                <?php endif; ?>



<div class="navigation-section">


    

    <div class="navigation-header">

        <div>

            <div class="navigation-title">

                <i class="fa-solid fa-location-dot"></i>

                Select Classroom

            </div>

            <div class="navigation-subtitle">

                Select a department, building, and room
                to view its schedule.

            </div>

        </div>

    </div>


    

    <div id="departmentLevel">

        <div class="level-title">

            <i class="fa-solid fa-building-columns"></i>

            Departments

        </div>


        <div class="department-list">

            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <button
                    type="button"
                    class="department-card"
                    onclick="selectDepartment(
                        <?php echo e($department->id); ?>

                    )"
                >

                    <div class="card-left">

                        <div class="card-icon department-icon">

                            <i class="fa-solid fa-building-columns"></i>

                        </div>

                        <div>

                            <div class="card-name">

                                <?php echo e($department->name); ?>


                            </div>

                            <div class="card-description">

                                Department

                            </div>

                        </div>

                    </div>


                    <div class="card-arrow">

                        <i class="fa-solid fa-chevron-right"></i>

                    </div>

                </button>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    </div>


    

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

                Departments

            </button>

            <span>
                /
            </span>

            <strong id="selectedDepartmentName">
                Department
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


               


<?php
    $modifyFacultySubjects = $facultySubjects ?? collect();
?>

<div class="editor-panel" id="modifyEditor" style="display:none;">

    <div class="editor-title">
        <i class="fa-solid fa-table-cells-large"></i>
        Schedule Cell Editor
    </div>

    <div class="editor-subtitle">
        Click an editable timetable cell. The course code uses the same
        Faculty Setup entries used by Set Schedule Excel. Subject, year level,
        faculty, major, and program are filled automatically.
    </div>

    <form
        method="POST"
        action="<?php echo e(route('chair.modify.schedule.store')); ?>"
        id="modifyCellForm"
    >
        <?php echo csrf_field(); ?>

        <input type="hidden" name="room_id" id="modifyFormRoomId">
        <input type="hidden" name="day" id="modifyFormDay">
        <input type="hidden" name="time" id="modifyFormTime">
        <input type="hidden" name="schedule_id" id="modifyFormScheduleId">

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
                    <?php $__currentLoopData = $modifyFacultySubjects
                            ->unique('course_code'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facultySubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($facultySubject->course_code); ?>"></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </datalist>
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
                <label for="modifyInstructor">Faculty</label>
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

        <div class="editor-status" id="modifyEditorStatus">
            Select an editable cell from the timetable.
        </div>

        <div class="editor-actions">

            <div class="editor-actions-left">
                <button
                    type="button"
                    class="editor-button editor-cancel"
                    id="modifyCancelButton"
                >
                    <i class="fa-solid fa-xmark"></i>
                    Cancel Selection
                </button>
            </div>

            <div class="editor-actions-right">

                <label class="editor-color-inline" for="modifyColor">
                    <span>Color</span>
                    <input
                        type="color"
                        name="color"
                        id="modifyColor"
                        value="#ffffff"
                        title="Choose cell color"
                    >
                </label>

                <button
                    type="button"
                    class="editor-button editor-clear"
                    id="modifyClearCellButton"
                >
                    <i class="fa-solid fa-eraser"></i>
                    Clear Cell
                </button>

                <button
                    type="submit"
                    class="editor-button editor-save"
                    id="modifySaveCellButton"
                >
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Cell
                </button>

            </div>

        </div>
    </form>

    <form
        method="POST"
        action="<?php echo e(route('chair.modify.schedule.clear')); ?>"
        id="modifyClearForm"
        style="display:none;"
    >
        <?php echo csrf_field(); ?>

        <input
            type="hidden"
            name="schedule_id"
            id="modifyClearScheduleId"
        >
    </form>

</div>




<div
    class="schedule-section"
    id="scheduleSection"
    style="display:none;"
>


    

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


        <button
            type="button"
            class="change-room-button"
            onclick="backToRooms()"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Change Room

        </button>

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
            Your department schedules can be modified.
            Other departments' schedules are locked;
            empty slots in other departments require permission.
        </span>

    </div>


    

    <div class="excel-wrapper">

        <table class="excel-schedule">

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


<?php
    $modifyFacultySubjectsData = ($facultySubjects ?? collect())
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'course_code' => $item->course_code,
                'subject' => $item->subject,
                'year_level' => $item->year_level,
                'major' => $item->major ?? '',
                'program' => $item->program ?? '',
                'faculty_id' => $item->faculty_id ?? null,
                'instructor' => optional($item->faculty)->name ?? '',
            ];
        })
        ->values()
        ->toArray();
?>

const modifyFacultySubjects = <?php echo json_encode($modifyFacultySubjectsData, 15, 512) ?>;

const departments =
    <?php echo json_encode($departments, 15, 512) ?>;

let selectedDepartment = null;
let selectedBuilding = null;
let selectedRoom = null;


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

                            <div class="room-selection-label">

                                Classroom

                            </div>

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

                        Open Schedule

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

function openRoom(roomId)
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


    /*
     * SHOW THE SCHEDULE CELL EDITOR ONLY AFTER A ROOM IS OPENED
     */

    if (modifyEditor) {
        modifyEditor.style.display = 'block';

        modifyEditor.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    clearModifyEditor();
}


/* =========================================================
   BACK TO DEPARTMENTS
========================================================= */

function backToDepartments()
{
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

    if (modifyEditor) {
        modifyEditor.style.display = 'none';
    }

    clearModifyEditor();
}


/* =========================================================
   BACK TO BUILDINGS
========================================================= */

function backToBuildings()
{
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

    if (modifyEditor) {
        modifyEditor.style.display = 'none';
    }

    clearModifyEditor();
}


/* =========================================================
   BACK TO ROOMS
========================================================= */

function backToRooms()
{
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

    if (modifyEditor) {
        modifyEditor.style.display = 'none';
    }

    clearModifyEditor();
}


/* =========================================================
   EXCEL-STYLE SCHEDULE
========================================================= */

function buildExcelSchedule(room)
{
    const schedules =
        room.schedules || [];


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

            if (index === 4) {

                html += `

                    <tr class="noon-break-row">

                        <td colspan="7">

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


            html += '</tr>';

        }
    );


    document.getElementById(
        'scheduleBody'
    ).innerHTML = html;
}


/* =========================================================
   BUILD INDIVIDUAL CELL
========================================================= */

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

                    String(item.time || '')
                        .trim() === time
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
                    data-schedule-id="${Number(schedule.id || 0)}"
                    data-course="${escapeHtml(schedule.course_code || '')}"
                    data-subject="${escapeHtml(schedule.subject || '')}"
                    data-year="${escapeHtml(schedule.year_level || '')}"
                    data-instructor="${escapeHtml(schedule.instructor || '')}"
                    data-major="${escapeHtml(schedule.major || '')}"
                    data-program="${escapeHtml(schedule.program || '')}"
                    data-semester="${escapeHtml(schedule.semester || '')}"
                    data-academic-year="${escapeHtml(schedule.academic_year || '')}"
                    data-color="${escapeHtml(schedule.color || '#eaf6ed')}"
                    onclick="openAddModal(${room.id}, '${day}', '${time}')"
                    title="Your department schedule — click to modify"
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
                            ? `<div class="schedule-year">${escapeHtml(schedule.program)}</div>`
                            : ''
                    }

                    <span class="editable-label">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Your Schedule — Click to Edit
                    </span>

                </td>
            `;
        }

        return `
            <td
                class="schedule-cell filled locked-cell"
                data-day="${day}"
                data-time="${escapeHtml(time)}"
                data-schedule-id="${Number(schedule.id || 0)}"
                title="This schedule belongs to another department and is locked."
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
                        ? `<div class="schedule-year">${escapeHtml(schedule.program)}</div>`
                        : ''
                }

                <span class="locked">
                    <i class="fa-solid fa-lock"></i>
                    Locked — Other Department
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
                onclick="openAddModal(${room.id}, '${day}', '${time}')"
                title="Click to add a schedule"
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
                onclick="openAddModal(${room.id}, '${day}', '${time}')"
                title="Permission allowed: click to add a schedule"
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
    <?php echo e(auth()->user()->department_id); ?>;

            

                /*
                |--------------------------------------------------------------------------
                | MODIFY EXCEL EDITOR
                |--------------------------------------------------------------------------
                */

                let selectedModifyCell = null;

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

                const modifyEditor =
                    document.getElementById('modifyEditor');

                const modifyEditorStatus =
                    document.getElementById('modifyEditorStatus');

                const modifyCellForm =
                    document.getElementById('modifyCellForm');

                const modifyClearForm =
                    document.getElementById('modifyClearForm');


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


                function clearModifyEditor()
                {
                    if (selectedModifyCell) {
                        selectedModifyCell.classList.remove(
                            'is-selected-edit'
                        );
                    }

                    selectedModifyCell = null;

                    modifyFormRoomId.value = '';
                    modifyFormDay.value = '';
                    modifyFormTime.value = '';
                    modifyFormScheduleId.value = '';

                    modifyCourseCode.value = '';
                    modifySubject.value = '';
                    modifyYearLevel.value = '';
                    modifyInstructor.value = '';
                    modifyMajor.value = '';
                    modifyProgram.value = '';
                    modifySemesterValue.value = '';
                    modifyAcademicYearValue.value = '';
                    modifyColor.value = '#ffffff';

                    setModifyStatus(
                        'Select an editable cell from the timetable.'
                    );
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

                    if (!modifyCourseCode.value.trim()) {
                        setModifyStatus(
                            'Enter a course code from Set Faculty.',
                            'warning'
                        );
                        return;
                    }

                    if (matches.length === 0) {
                        setModifyStatus(
                            'Course code was not found in the Faculty Setup entries.',
                            'error'
                        );
                        return;
                    }

                    if (matches.length > 1) {
                        setModifyStatus(
                            'This course code has multiple faculty assignments.',
                            'warning'
                        );
                        return;
                    }

                    const match = matches[0];

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


                modifyCourseCode.addEventListener(
                    'input',
                    populateModifyCourse
                );


                function openAddModal(
    roomId,
    day,
    time
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

    const clickedCell =
        document.querySelector(selector);

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
    modifyFormTime.value = time;

    modifySemesterValue.value =
        modifyRoomMeta.semester || '';

    modifyAcademicYearValue.value =
        modifyRoomMeta.academic_year || '';

    const scheduleId =
        selectedModifyCell.dataset.scheduleId || '';

    modifyFormScheduleId.value = scheduleId;

    if (scheduleId) {

        modifyCourseCode.value =
            selectedModifyCell.dataset.course || '';

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

        modifyColor.value =
            selectedModifyCell.dataset.color || '#ffffff';

        setModifyStatus(
            'Your department schedule is selected: ' +
            day +
            ' ' +
            time +
            '. You can modify it and click Save Cell.',
            'success'
        );

    } else {

        modifyCourseCode.value = '';
        modifySubject.value = '';
        modifyYearLevel.value = '';
        modifyInstructor.value = '';
        modifyMajor.value = '';
        modifyProgram.value = '';
        modifyColor.value = '#ffffff';

        setModifyStatus(
            'Empty cell selected: ' +
            day +
            ' ' +
            time +
            '. Enter a course code and click Save Cell.',
            'warning'
        );

    }

    modifyCourseCode.focus();
                }


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
                                'This course code is not in the Set Faculty entries.',
                                'error'
                            );

                            return;
                        }

                        if (matches.length > 1) {

                            event.preventDefault();

                            setModifyStatus(
                                'This course code has multiple faculty assignments.',
                                'warning'
                            );

                            return;
                        }

                        const match =
                            matches[0];

                        const confirmed =
                            window.confirm(
                                'Save this schedule to ' +
                                modifyFormDay.value +
                                ' ' +
                                modifyFormTime.value +
                                '?\n\nCourse: ' +
                                match.course_code +
                                '\nSubject: ' +
                                (match.subject || '') +
                                '\nFaculty: ' +
                                (match.instructor || '')
                            );

                        if (!confirmed) {

                            event.preventDefault();

                            setModifyStatus(
                                'Save cancelled. The cell was not changed.',
                                'warning'
                            );

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
                        function()
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

                                if (
                                    !confirm(
                                        'Clear this saved schedule from the cell?'
                                    )
                                ) {
                                    return;
                                }

                                modifyClearScheduleId.value =
                                    scheduleId;

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
                    }
                );


                clearModifyEditor();

            </script>

        </main>

    </div>

   

</div>

 <?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/chair/modifyschedule.blade.php ENDPATH**/ ?>