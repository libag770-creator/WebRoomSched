<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Student Schedule</title>

    <link rel="stylesheet" href="{{ asset('css/professional.css') }}">
    <link rel="stylesheet" href="{{ asset('css/schedule-sheet-controls.css') }}?v=20261023-fit-controls-v1">
    <script src="{{ asset('js/schedule-sheet-controls.js') }}?v=20261023-fit-controls-v1" defer></script>

    <style>

        /* =========================================================
           GLOBAL
        ========================================================= */

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #1f4f8f;
            --primary-dark: #173b6b;
            --primary-light: #eef5ff;

            --success: #16805c;
            --success-light: #ecfdf5;

            --danger: #c24141;
            --danger-light: #fff1f1;

            --warning: #a66a00;
            --warning-light: #fff8e7;

            --text: #172033;
            --muted: #667085;
            --border: #e4e7ec;
            --background: #f5f7fb;
            --white: #ffffff;

            --shadow-sm:
                0 1px 3px rgba(16, 24, 40, 0.08);

            --shadow-md:
                0 8px 24px rgba(16, 24, 40, 0.08);

            --radius: 14px;
        }


        body {
            font-family:
                Inter,
                "Segoe UI",
                Arial,
                Helvetica,
                sans-serif;

            background: var(--background);
            color: var(--text);

            min-height: 100vh;
        }


        button,
        input {
            font: inherit;
        }


        button {
            cursor: pointer;
        }


        /* =========================================================
           PAGE
        ========================================================= */

        .page-container {
            width: 100%;
            max-width: 1550px;

            margin: 0 auto;

            padding: 28px;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .page-header {
            position: relative;

            background:
                linear-gradient(
                    135deg,
                    #1f4f8f 0%,
                    #2765b2 55%,
                    #2f75c9 100%
                );

            color: white;

            border-radius: 18px;

            padding: 30px 34px;

            margin-bottom: 22px;

            box-shadow:
                0 12px 30px rgba(31, 79, 143, 0.20);

            overflow: hidden;
        }


        .page-header::after {
            content: "";

            position: absolute;

            width: 230px;
            height: 230px;

            right: -80px;
            top: -110px;

            border-radius: 50%;

            background: rgba(255,255,255,0.08);
        }


        .page-header-content {
            position: relative;
            z-index: 2;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 25px;
        }


        .header-left {
            display: flex;

            align-items: center;

            gap: 18px;
        }


        .header-icon {
            width: 58px;
            height: 58px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 15px;

            background: rgba(255,255,255,0.16);

            font-size: 27px;
        }


        .page-header h1 {
            font-size: 27px;

            font-weight: 750;

            letter-spacing: -0.4px;

            margin-bottom: 5px;
        }


        .page-header p {
            font-size: 14px;

            color: rgba(255,255,255,0.82);
        }


        .academic-info {
            display: flex;

            flex-direction: column;

            align-items: flex-end;

            gap: 4px;

            font-size: 12px;

            color: rgba(255,255,255,0.82);
        }


        .academic-info strong {
            color: white;

            font-size: 14px;
        }


        .active-term-badge {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            margin-top: 5px;

            padding: 6px 10px;

            border-radius: 999px;

            background: rgba(255,255,255,0.14);

            border: 1px solid rgba(255,255,255,0.20);

            color: rgba(255,255,255,0.92);

            font-size: 10px;

            font-weight: 700;

            white-space: nowrap;
        }


        .active-term-dot {
            width: 7px;

            height: 7px;

            flex-shrink: 0;

            border-radius: 50%;

            background: #68d391;

            box-shadow:
                0 0 0 3px rgba(104,211,145,0.14);
        }


        .inactive-term-badge {
            background: rgba(255,255,255,0.10);

            border-color: rgba(255,255,255,0.16);

            color: rgba(255,255,255,0.82);
        }


        /* =========================================================
           STEP INDICATOR
        ========================================================= */

        .steps {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 12px;

            margin-bottom: 22px;
        }


        .step {
            display: flex;

            align-items: center;

            gap: 12px;

            background: white;

            border: 1px solid var(--border);

            border-radius: 12px;

            padding: 14px 16px;

            box-shadow: var(--shadow-sm);

            transition: 0.2s ease;
        }


        .step-number {
            width: 32px;
            height: 32px;

            flex-shrink: 0;

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #eef2f7;

            color: #667085;

            font-size: 13px;

            font-weight: 700;
        }


        .step-text {
            display: flex;
            flex-direction: column;

            gap: 2px;
        }


        .step-title {
            font-size: 13px;

            font-weight: 700;
        }


        .step-description {
            font-size: 11px;

            color: var(--muted);
        }


        .step.active {
            border-color: #bfd4f0;

            background: var(--primary-light);
        }


        .step.active .step-number {
            background: var(--primary);

            color: white;
        }


        .step.completed .step-number {
            background: var(--success);

            color: white;
        }


        /* =========================================================
           MAIN LAYOUT
        ========================================================= */

        .main-layout {
            display: grid;

            grid-template-columns:
                315px
                minmax(0, 1fr);

            gap: 22px;

            align-items: start;
        }


        /* =========================================================
           LEFT PANEL
        ========================================================= */

        .department-panel {
            position: sticky;

            top: 20px;

            background: white;

            border: 1px solid var(--border);

            border-radius: var(--radius);

            box-shadow: var(--shadow-sm);

            overflow: hidden;
        }


        .panel-header {
            padding: 19px 20px;

            border-bottom: 1px solid var(--border);
        }


        .panel-title {
            display: flex;

            align-items: center;

            gap: 9px;

            font-size: 15px;

            font-weight: 750;

            margin-bottom: 4px;
        }


        .panel-title-icon {
            color: var(--primary);

            font-size: 17px;
        }


        .panel-subtitle {
            color: var(--muted);

            font-size: 12px;
        }


        .department-content {
            padding: 16px;
        }


        .search-wrapper {
            position: relative;

            margin-bottom: 13px;
        }


        .search-icon {
            position: absolute;

            left: 13px;
            top: 50%;

            transform: translateY(-50%);

            color: #98a2b3;

            font-size: 14px;
        }


        .search-box {
            width: 100%;

            border: 1px solid var(--border);

            border-radius: 10px;

            padding:
                11px
                12px
                11px
                37px;

            outline: none;

            background: #fafbfc;

            color: var(--text);

            font-size: 13px;

            transition: 0.2s ease;
        }


        .search-box:focus {
            border-color: var(--primary);

            background: white;

            box-shadow:
                0 0 0 3px rgba(31,79,143,0.08);
        }


        .department-list {
            display: flex;

            flex-direction: column;

            gap: 7px;

            max-height: 560px;

            overflow-y: auto;

            padding-right: 2px;
        }


        .department-button {
            width: 100%;

            display: flex;

            align-items: center;

            gap: 11px;

            text-align: left;

            border: 1px solid transparent;

            background: #f8fafc;

            border-radius: 10px;

            padding: 12px;

            color: var(--text);

            transition:
                background 0.18s ease,
                border-color 0.18s ease,
                transform 0.18s ease;
        }


        .department-button:hover {
            background: #f0f5fb;

            border-color: #d9e5f3;

            transform: translateX(2px);
        }


        .department-button.active {
            background: var(--primary);

            border-color: var(--primary);

            color: white;

            box-shadow:
                0 5px 12px rgba(31,79,143,0.16);
        }


        .department-number {
            width: 30px;
            height: 30px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: white;

            color: var(--primary);

            font-size: 12px;

            font-weight: 800;
        }


        .department-button.active
        .department-number {
            background: rgba(255,255,255,0.18);

            color: white;
        }


        .department-name {
            font-size: 13px;

            line-height: 1.35;

            font-weight: 650;
        }


        .no-department {
            padding: 25px 12px;

            text-align: center;

            color: var(--muted);

            font-size: 13px;
        }


        /* =========================================================
           RIGHT CONTENT
        ========================================================= */

        .content-panel {
            min-width: 0;
        }


        /* =========================================================
           EMPTY STATE
        ========================================================= */

        .empty-state {
            min-height: 460px;

            display: flex;

            flex-direction: column;

            align-items: center;
            justify-content: center;

            text-align: center;

            background: white;

            border: 1px solid var(--border);

            border-radius: var(--radius);

            box-shadow: var(--shadow-sm);

            padding: 40px;
        }


        .empty-icon {
            width: 70px;
            height: 70px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 18px;

            background: var(--primary-light);

            color: var(--primary);

            font-size: 29px;

            margin-bottom: 18px;
        }


        .empty-state h2 {
            font-size: 20px;

            margin-bottom: 7px;
        }


        .empty-state p {
            max-width: 430px;

            color: var(--muted);

            font-size: 13px;

            line-height: 1.6;
        }


        /* =========================================================
           SELECTION CARD
        ========================================================= */

        .selection-section {
            display: none;

            background: white;

            border: 1px solid var(--border);

            border-radius: var(--radius);

            box-shadow: var(--shadow-sm);

            padding: 21px;

            margin-bottom: 18px;
        }


        .selection-section.show {
            display: block;

            animation: fadeUp 0.22s ease;
        }


        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        .selection-heading {
            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 18px;
        }


        .selection-heading-left {
            min-width: 0;
        }


        .selection-label {
            color: var(--primary);

            font-size: 11px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.7px;

            margin-bottom: 5px;
        }


        .selection-title {
            font-size: 19px;

            font-weight: 750;
        }


        .selection-context {
            color: var(--muted);

            font-size: 12px;

            margin-top: 4px;
        }


        /* =========================================================
           PROGRAM BUTTONS
        ========================================================= */

        .program-buttons {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 11px;
        }


        .program-button {
            position: relative;

            display: flex;

            align-items: flex-start;

            gap: 12px;

            width: 100%;

            border: 1px solid var(--border);

            background: #fafbfc;

            border-radius: 11px;

            padding: 15px;

            text-align: left;

            color: var(--text);

            transition: 0.2s ease;
        }


        .program-button:hover {
            border-color: #b7cce6;

            background: #f4f8fd;

            transform: translateY(-1px);
        }


        .program-button.active {
            background: var(--primary-light);

            border-color: #9dbce0;

            color: var(--primary-dark);
        }


        .program-icon {
            width: 34px;
            height: 34px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: #e8f0fa;

            color: var(--primary);

            font-size: 14px;

            font-weight: 800;
        }


        .program-button.active
        .program-icon {
            background: var(--primary);

            color: white;
        }


        .program-name {
            font-size: 13px;

            line-height: 1.45;

            font-weight: 650;
        }


        /* =========================================================
           YEAR BUTTONS
        ========================================================= */

        .year-buttons {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 10px;
        }


        .year-button {
            border: 1px solid var(--border);

            background: #fafbfc;

            border-radius: 10px;

            padding: 14px 10px;

            color: var(--text);

            font-size: 13px;

            font-weight: 700;

            transition: 0.2s ease;
        }


        .year-button:hover {
            border-color: #b7cce6;

            background: #f4f8fd;

            transform: translateY(-1px);
        }


        .year-button.active {
            background: var(--primary);

            border-color: var(--primary);

            color: white;

            box-shadow:
                0 5px 12px rgba(31,79,143,0.15);
        }


        /* =========================================================
           LOADING / ERROR
        ========================================================= */

        .loading-state,
        .program-loading,
        .year-loading {
            min-height: 90px;

            display: flex;

            align-items: center;
            justify-content: center;

            gap: 10px;

            color: var(--muted);

            font-size: 13px;
        }


        .spinner {
            width: 17px;
            height: 17px;

            border: 2px solid #dbe4ee;

            border-top-color: var(--primary);

            border-radius: 50%;

            animation: spin 0.7s linear infinite;
        }


        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }


        .message-box {
            padding: 15px;

            border-radius: 10px;

            text-align: center;

            font-size: 13px;
        }


        .message-box.empty {
            background: #f8fafc;

            color: var(--muted);

            border: 1px dashed #d6dbe3;
        }


        .message-box.error {
            background: var(--danger-light);

            color: var(--danger);

            border: 1px solid #f2cccc;
        }


        /* =========================================================
           SCHEDULE
        ========================================================= */

        .schedule-wrapper {
            display: none;
        }


        .schedule-wrapper.show {
            display: block;

            animation: fadeUp 0.25s ease;
        }


        .schedule-paper {
            background: white;

            border: 1px solid var(--border);

            border-radius: var(--radius);

            box-shadow: var(--shadow-md);

            overflow: hidden;
        }


        .schedule-top {
            padding: 25px 27px 20px;

            border-bottom: 1px solid var(--border);
        }


        .schedule-title-row {
            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 20px;
        }


        .schedule-title {
            font-size: 21px;

            font-weight: 800;

            letter-spacing: -0.3px;
        }


        .schedule-subtitle {
            color: var(--muted);

            font-size: 12px;

            margin-top: 4px;
        }


        .schedule-actions {
            display: flex;

            gap: 8px;
        }


        .action-button {
            border: 1px solid var(--border);

            background: white;

            color: var(--text);

            border-radius: 9px;

            padding: 9px 13px;

            font-size: 12px;

            font-weight: 700;

            transition: 0.2s ease;
        }


        .action-button:hover {
            background: #f5f7fa;

            border-color: #cdd3dc;
        }


        .action-button.primary {
            background: var(--primary);

            color: white;

            border-color: var(--primary);
        }


        .action-button.primary:hover {
            background: var(--primary-dark);
        }


        .schedule-info-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 10px;
        }


        .info-item {
            background: #f8fafc;

            border: 1px solid #edf0f4;

            border-radius: 9px;

            padding: 11px 13px;
        }


        .info-label {
            display: block;

            color: var(--muted);

            font-size: 10px;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            font-weight: 700;

            margin-bottom: 4px;
        }


        .info-value {
            display: block;

            font-size: 13px;

            font-weight: 700;

            word-break: break-word;
        }


        .schedule-count {
            color: var(--primary);
        }


        /* =========================================================
           TABLE
        ========================================================= */

        .table-container {
            width: 100%;

            overflow-x: auto;

            -webkit-overflow-scrolling: touch;
        }


        .schedule-table {
            width: 100%;

            min-width: 1120px;

            border-collapse: collapse;

            table-layout: fixed;

            font-size: 11px;
        }


        .schedule-table th,
        .schedule-table td {
            border: 1px solid #d9dde4;

            padding: 9px 7px;

            text-align: center;

            vertical-align: middle;
        }


        .schedule-table thead th {
            background: #f2f5f9;

            color: #344054;

            font-weight: 800;

            font-size: 10px;

            letter-spacing: 0.2px;
        }


        .schedule-table tbody tr {
            transition: background 0.15s ease;
        }


        .schedule-table tbody tr:hover {
            background: #f8fbff;
        }


        .course-code-column {
            width: 95px;
        }


        .description-column {
            width: 225px;
        }


        .type-column {
            width: 70px;
        }


        .instructor-column {
            width: 150px;
        }


        .major-column {
            width: 120px;
        }


        .room-column {
            width: 80px;
        }


        .time-column {
            width: 100px;
        }


        .day-column {
            width: 47px;
        }


        .remarks-column {
            width: 115px;
        }


        .course-code-text {
            color: var(--primary-dark);

            font-weight: 800;
        }


        .subject-text {
            text-align: left !important;

            font-weight: 600;
        }


        .major-text {
            font-weight: 650;
        }


        .day-active {
            color: var(--primary);

            font-size: 15px;

            font-weight: 900;
        }


        .no-schedule {
            padding: 50px 20px !important;

            color: var(--muted);

            text-align: center !important;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .schedule-footer {
            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 80px;

            padding: 30px 35px 35px;
        }


        .signature {
            text-align: center;

            color: #475467;

            font-size: 11px;
        }


        .signature-line {
            max-width: 230px;

            margin:
                35px auto 6px;

            border-bottom: 1px solid #667085;
        }


        .signature-name {
            font-size: 11px;

            font-weight: 800;

            color: var(--text);
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1100px) {

            .main-layout {
                grid-template-columns: 260px minmax(0, 1fr);
            }

            .program-buttons {
                grid-template-columns: 1fr;
            }

            .year-buttons {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }


        @media (max-width: 850px) {

            .page-container {
                padding: 16px;
            }

            .page-header-content {
                flex-direction: column;

                align-items: flex-start;
            }

            .academic-info {
                align-items: flex-start;
            }

            .active-term-badge {
                white-space: normal;

                text-align: left;
            }

            .steps {
                grid-template-columns: 1fr;
            }

            .main-layout {
                grid-template-columns: 1fr;
            }

            .department-panel {
                position: static;
            }

            .department-list {
                max-height: 320px;
            }

            .schedule-info-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 600px) {

            .page-container {
                padding: 10px;
            }

            .page-header {
                padding: 22px;
            }

            .header-icon {
                width: 48px;
                height: 48px;

                font-size: 22px;
            }

            .page-header h1 {
                font-size: 21px;
            }

            .selection-section {
                padding: 16px;
            }

            .selection-heading {
                flex-direction: column;
            }

            .program-buttons {
                grid-template-columns: 1fr;
            }

            .year-buttons {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .schedule-top {
                padding: 19px 17px;
            }

            .schedule-title-row {
                flex-direction: column;
            }

            .schedule-actions {
                width: 100%;
            }

            .action-button {
                flex: 1;
            }

            .schedule-footer {
                grid-template-columns: 1fr;

                gap: 20px;
            }

        }


        /* =========================================================
           PRINT
        ========================================================= */

        @media print {

            body {
                background: white;
            }

            .page-container {
                max-width: none;

                padding: 0;
            }

            .page-header,
            .steps,
            .department-panel,
            .selection-section,
            .schedule-actions {
                display: none !important;
            }

            .main-layout {
                display: block;
            }

            .schedule-wrapper {
                display: block !important;
            }

            .schedule-paper {
                border: none;

                box-shadow: none;

                border-radius: 0;
            }

            .schedule-top {
                padding: 0 0 15px;
            }

            .schedule-table {
                min-width: 0;

                font-size: 9px;
            }

            .schedule-table th,
            .schedule-table td {
                padding: 5px 3px;
            }

            .schedule-footer {
                padding-left: 0;
                padding-right: 0;
            }

        }

        /* Student schedules refinement: campus brand, clear selection steps, and legible timetable. */
        body.student-schedule-body {
            --primary:#245940;
            --primary-dark:#173b33;
            --primary-light:#edf5eb;
            --success:#32704d;
            --success-light:#f0f7ef;
            --danger:#a33d34;
            --danger-light:#fff3f1;
            --warning:#855c15;
            --warning-light:#fff8e8;
            --text:#203d30;
            --muted:#617268;
            --border:#dce6dc;
            --background:#f2f4ed;
            --white:#fff;
            background:
                radial-gradient(ellipse at 88% 2%,rgba(215,195,145,.22),transparent 25%),
                radial-gradient(ellipse at 8% 96%,rgba(204,224,208,.32),transparent 30%),
                linear-gradient(145deg,#edf2ec 0%,#f8f7f2 58%,#f1f0e8 100%);
            color:#203d30;
            font-family:'DM Sans',Arial,sans-serif;
        }
        .student-schedule-body,
        .student-schedule-body * { font-family:'DM Sans',Arial,sans-serif; }
        .student-schedule-body .page-container { width:100%; max-width:1640px; margin:0 auto; padding:28px clamp(18px,3vw,42px) 44px; }
        .student-schedule-body .page-header {
            position:relative;
            isolation:isolate;
            min-height:205px;
            display:flex;
            align-items:center;
            margin-bottom:19px;
            padding:clamp(28px,4vw,42px) clamp(25px,4vw,44px);
            border:1px solid rgba(232,215,175,.22);
            border-radius:24px;
            background:radial-gradient(circle at 78% 18%,rgba(217,196,143,.17),transparent 25%),linear-gradient(118deg,#153a32 0%,#215440 58%,#37684e 100%);
            box-shadow:0 20px 44px rgba(24,58,43,.16);
        }
        .student-schedule-body .page-header::before { position:absolute; z-index:-1; top:0; bottom:0; left:0; width:5px; border-radius:24px 0 0 24px; background:#d8c18c; content:""; }
        .student-schedule-body .page-header::after { top:-145px; right:95px; width:330px; height:330px; border:1px solid rgba(244,232,200,.22); background:transparent; box-shadow:0 0 0 27px rgba(244,232,200,.035),0 0 0 63px rgba(244,232,200,.026),0 0 0 102px rgba(244,232,200,.018); }
        .student-schedule-body .page-header-content { width:100%; align-items:center; gap:24px; }
        .student-schedule-body .header-left { gap:16px; }
        .student-schedule-body .header-icon { width:54px; height:54px; flex:0 0 54px; border:1px solid rgba(255,255,255,.16); border-radius:15px; background:rgba(255,255,255,.12); color:#e6d39f; font-size:23px; }
        .student-schedule-body .page-header h1 { margin-bottom:10px; color:#fff; font:800 clamp(31px,3vw,42px)/1.12 'Manrope','DM Sans',sans-serif; letter-spacing:-.045em; }
        .student-schedule-body .page-header p { max-width:660px; color:#e0e9e1; font-size:14px; font-weight:550; line-height:1.6; }
        .student-schedule-body .academic-info { position:relative; z-index:1; align-items:flex-end; gap:5px; color:#d6e1d7; font-size:12px; font-weight:650; }
        .student-schedule-body .academic-info strong { color:#fff; font-size:16px; font-weight:850; }
        .student-schedule-body .active-term-badge { margin-top:8px; padding:7px 11px; border-color:rgba(255,255,255,.22); background:rgba(255,255,255,.1); color:#f3eddb; font-size:11px; font-weight:850; }
        .student-schedule-body .active-term-dot { background:#d6bf7d; box-shadow:0 0 0 3px rgba(214,191,125,.16); }
        .student-schedule-body .steps { gap:12px; margin-bottom:20px; }
        .student-schedule-body .step { min-height:77px; gap:12px; padding:15px 17px; border-color:#dce6dc; border-radius:15px; background:linear-gradient(145deg,#fff,#fbfcf9); box-shadow:0 6px 17px rgba(31,57,43,.045); }
        .student-schedule-body .step-number { width:36px; height:36px; border:1px solid #dce6dc; background:#f2f5ef; color:#5d7064; font-size:14px; font-weight:850; }
        .student-schedule-body .step-title { color:#294437; font-size:14px; font-weight:850; }
        .student-schedule-body .step-description { color:#65756a; font-size:12px; font-weight:550; line-height:1.4; }
        .student-schedule-body .step.active { border-color:#9fbea2; background:#f0f6ec; box-shadow:0 0 0 3px rgba(50,112,77,.08); }
        .student-schedule-body .step.active .step-number { border-color:#245940; background:#245940; color:#fff; }
        .student-schedule-body .step.completed .step-number { border-color:#32704d; background:#32704d; color:#fff; }
        .student-schedule-body .main-layout { grid-template-columns:minmax(260px,300px) minmax(0,1fr); gap:18px; align-items:start; }
        .student-schedule-body .department-panel,.student-schedule-body .content-panel { border:1px solid #dce6dc; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.05); }
        .student-schedule-body .department-panel { top:18px; }
        .student-schedule-body .panel-header { padding:19px 20px; border-color:#e6ece4; background:linear-gradient(110deg,#f5f8f1,#fff); }
        .student-schedule-body .panel-title { gap:10px; margin-bottom:6px; color:#294437; font:800 17px/1.3 'Manrope','DM Sans',sans-serif; }
        .student-schedule-body .panel-title-icon { color:#245940; font-size:18px; }
        .student-schedule-body .panel-subtitle { color:#65756a; font-size:13px; font-weight:550; line-height:1.55; }
        .student-schedule-body .department-content { padding:16px; }
        .student-schedule-body .search-wrapper { margin-bottom:14px; }
        .student-schedule-body .search-icon { color:#718277; }
        .student-schedule-body .search-box { min-height:45px; border-color:#dce6dc; border-radius:10px; background:#f9fbf8; color:#294437; font-size:14px; }
        .student-schedule-body .search-box::placeholder { color:#7c8a80; opacity:1; }
        .student-schedule-body .search-box:focus { border-color:#4f8663; background:#fff; box-shadow:0 0 0 3px rgba(50,112,77,.11); }
        .student-schedule-body .department-list { gap:8px; max-height:calc(100vh - 340px); }
        .student-schedule-body .department-button { min-height:52px; gap:11px; padding:10px; border-color:#eef1eb; border-radius:11px; background:#f9fbf8; color:#294437; }
        .student-schedule-body .department-button:hover { border-color:#cbdcc9; background:#f2f7ef; }
        .student-schedule-body .department-button.active { border-color:#245940; background:#245940; color:#fff; box-shadow:0 5px 13px rgba(36,89,64,.16); }
        .student-schedule-body .department-number { width:32px; height:32px; border:1px solid #e4ebe1; border-radius:9px; background:#edf3eb; color:#245940; font-size:12px; font-weight:850; }
        .student-schedule-body .department-button.active .department-number { border-color:rgba(255,255,255,.18); background:rgba(255,255,255,.13); color:#fff; }
        .student-schedule-body .department-name { color:inherit; font-size:14px; font-weight:750; line-height:1.4; }
        .student-schedule-body .no-department { color:#65756a; font-size:13px; }
        .student-schedule-body .content-panel { min-height:460px; padding:18px; }
        .student-schedule-body .empty-state { min-height:420px; padding:36px 25px; border-color:#e7ede4; border-radius:15px; background:linear-gradient(145deg,#fff,#fafbf8); box-shadow:none; }
        .student-schedule-body .empty-icon { width:64px; height:64px; margin-bottom:15px; border:1px solid #e0e8dc; border-radius:17px; background:#edf3eb; color:#285940; font-size:25px; }
        .student-schedule-body .empty-state h2 { margin-bottom:8px; color:#294437; font:800 21px/1.3 'Manrope','DM Sans',sans-serif; }
        .student-schedule-body .empty-state p { color:#65756a; font-size:14px; font-weight:550; line-height:1.65; }
        .student-schedule-body .selection-section { margin-bottom:15px; padding:19px; border-color:#dfe8de; border-radius:15px; background:#fff; box-shadow:0 5px 17px rgba(31,57,43,.04); }
        .student-schedule-body .selection-heading { margin-bottom:15px; }
        .student-schedule-body .selection-label { color:#927c49; font-size:11px; font-weight:850; letter-spacing:.1em; }
        .student-schedule-body .selection-title { color:#203d30; font:800 19px/1.3 'Manrope','DM Sans',sans-serif; }
        .student-schedule-body .selection-context { color:#65756a; font-size:13px; font-weight:600; line-height:1.5; }
        .student-schedule-body .program-buttons { gap:10px; }
        .student-schedule-body .program-button { min-height:68px; gap:12px; padding:14px; border-color:#e1e9df; border-radius:12px; background:#f9fbf8; color:#294437; }
        .student-schedule-body .program-button:hover { border-color:#9dbba0; background:#f3f7f0; }
        .student-schedule-body .program-button.active { border-color:#94b698; background:#eef5e9; color:#245940; }
        .student-schedule-body .program-icon { width:38px; height:38px; border:1px solid #e3eadd; border-radius:11px; background:#edf3eb; color:#285940; font-size:15px; }
        .student-schedule-body .program-button.active .program-icon { border-color:#245940; background:#245940; color:#fff; }
        .student-schedule-body .program-name { color:inherit; font-size:14px; font-weight:750; line-height:1.5; }
        .student-schedule-body .year-buttons { gap:9px; }
        .student-schedule-body .year-button { min-height:49px; border-color:#dfe8de; border-radius:10px; background:#f9fbf8; color:#405a49; font-size:14px; font-weight:800; }
        .student-schedule-body .year-button:hover { border-color:#9dbba0; background:#f1f6ed; }
        .student-schedule-body .year-button.active { border-color:#245940; background:#245940; color:#fff; box-shadow:0 5px 12px rgba(36,89,64,.14); }
        .student-schedule-body .loading-state,.student-schedule-body .program-loading,.student-schedule-body .year-loading { min-height:90px; color:#617268; font-size:14px; font-weight:650; }
        .student-schedule-body .spinner { border-color:#dce6dc; border-top-color:#245940; }
        .student-schedule-body .message-box { padding:15px; border-radius:11px; font-size:14px; line-height:1.55; }
        .student-schedule-body .message-box.empty { border-color:#dce6dc; background:#f8faf6; color:#617268; }
        .student-schedule-body .message-box.error { border-color:#edc9c4; background:#fff4f2; color:#a33d34; }
        .student-schedule-body .schedule-paper { border-color:#dce6dc; border-radius:18px; box-shadow:0 11px 30px rgba(31,57,43,.07); }
        .student-schedule-body .schedule-top { padding:21px 22px 19px; border-color:#e6ece4; background:linear-gradient(110deg,#f5f8f1,#fff); }
        .student-schedule-body .schedule-title-row { align-items:center; margin-bottom:17px; }
        .student-schedule-body .schedule-title { color:#203d30; font:800 22px/1.3 'Manrope','DM Sans',sans-serif; letter-spacing:-.025em; }
        .student-schedule-body .schedule-subtitle { color:#65756a; font-size:13px; font-weight:600; }
        .student-schedule-body .schedule-actions { gap:9px; }
        .student-schedule-body .action-button { min-height:42px; padding:0 13px; border-color:#dce6dc; border-radius:10px; background:#fff; color:#405a49; font-size:13px; font-weight:800; }
        .student-schedule-body .action-button:hover { border-color:#b8ccb5; background:#f5f8f2; }
        .student-schedule-body .action-button.primary { border-color:#245940; background:#245940; color:#fff; }
        .student-schedule-body .action-button.primary:hover { background:#173b33; }
        .student-schedule-body .schedule-info-grid { gap:9px; }
        .student-schedule-body .info-item { padding:12px 13px; border-color:#e2e9df; border-radius:10px; background:#f8faf6; }
        .student-schedule-body .info-label { color:#64746a; font-size:10px; font-weight:850; }
        .student-schedule-body .info-value { color:#294437; font-size:14px; font-weight:850; line-height:1.45; }
        .student-schedule-body .schedule-count { color:#245940; }
        .student-schedule-body .table-container { padding:14px; background:#fff; scrollbar-color:#bacdbb #f5f8f3; scrollbar-width:thin; }
        .student-schedule-body .table-container::-webkit-scrollbar { width:10px; height:10px; }
        .student-schedule-body .table-container::-webkit-scrollbar-thumb { border:2px solid #f5f8f3; border-radius:99px; background:#bacdbb; }
        .student-schedule-body .schedule-table { min-width:1120px; border:1px solid #dfe8de; border-collapse:separate; border-spacing:0; border-radius:12px; overflow:hidden; color:#294437; font-size:12px; }
        .student-schedule-body .schedule-table th,.student-schedule-body .schedule-table td { padding:10px 8px; border-color:#e3ebe3; line-height:1.4; }
        .student-schedule-body .schedule-table thead th { background:#edf3eb; color:#245940; font-size:11px; font-weight:850; letter-spacing:.04em; }
        .student-schedule-body .schedule-table tbody tr:hover { background:#f6f9f3; }
        .student-schedule-body .schedule-table tbody tr.student-temporary-room-swap,
        .student-schedule-body .schedule-table tbody tr.student-temporary-room-swap:hover { background:#fff1dd; box-shadow:inset 4px 0 #d9821d; }
        .student-schedule-body .schedule-table tbody td { color:#405a49; font-size:12px; font-weight:600; }
        .student-schedule-body .course-code-text { color:#245940; font-weight:850; }
        .student-schedule-body .subject-text { color:#294437; font-size:12px; font-weight:750; }
        .student-schedule-body .major-text { color:#536a5b; font-weight:700; }
        .student-schedule-body .day-active { color:#245940; font-size:15px; font-weight:900; }
        .student-schedule-body .no-schedule { color:#77867a; font-size:13px; font-weight:600; }
        .student-schedule-body .schedule-footer { gap:50px; padding:25px 30px 29px; border-top:1px solid #e7ede5; background:#fcfdfb; }
        .student-schedule-body .signature { color:#64746a; font-size:12px; }
        .student-schedule-body .signature-line { border-color:#8b998e; }
        .student-schedule-body .signature-name { color:#294437; font-size:12px; font-weight:850; }
        @media(max-width:1100px) {
            .student-schedule-body .main-layout { grid-template-columns:260px minmax(0,1fr); gap:14px; }
            .student-schedule-body .page-container { padding-right:22px; padding-left:22px; }
            .student-schedule-body .program-buttons { grid-template-columns:1fr; }
            .student-schedule-body .year-buttons { grid-template-columns:repeat(2,minmax(0,1fr)); }
        }
        @media(max-width:850px) {
            .student-schedule-body .page-container { padding:18px 16px 32px; }
            .student-schedule-body .page-header { min-height:0; padding:27px 23px; border-radius:19px; }
            .student-schedule-body .page-header::after { top:-85px; right:-90px; width:260px; height:260px; }
            .student-schedule-body .page-header-content { align-items:flex-start; flex-direction:column; }
            .student-schedule-body .academic-info { align-items:flex-start; }
            .student-schedule-body .steps { grid-template-columns:1fr; gap:9px; }
            .student-schedule-body .main-layout { grid-template-columns:1fr; }
            .student-schedule-body .department-panel { position:static; }
            .student-schedule-body .department-list { max-height:300px; }
            .student-schedule-body .content-panel { min-height:0; padding:14px; }
            .student-schedule-body .schedule-info-grid { grid-template-columns:repeat(3,minmax(0,1fr)); }
        }
        @media(max-width:600px) {
            .student-schedule-body .page-container { padding:12px 11px 26px; }
            .student-schedule-body .page-header { padding:24px 20px; }
            .student-schedule-body .header-left { align-items:flex-start; gap:12px; }
            .student-schedule-body .header-icon { width:44px; height:44px; flex-basis:44px; border-radius:12px; font-size:19px; }
            .student-schedule-body .page-header h1 { margin-bottom:7px; font-size:29px; }
            .student-schedule-body .page-header p { font-size:13px; }
            .student-schedule-body .step { min-height:68px; padding:12px; }
            .student-schedule-body .selection-section { padding:15px; }
            .student-schedule-body .program-buttons { grid-template-columns:1fr; }
            .student-schedule-body .year-buttons { grid-template-columns:repeat(2,minmax(0,1fr)); }
            .student-schedule-body .schedule-top { padding:17px 14px; }
            .student-schedule-body .schedule-title-row { align-items:flex-start; flex-direction:column; gap:13px; }
            .student-schedule-body .schedule-actions { width:100%; }
            .student-schedule-body .action-button { flex:1; }
            .student-schedule-body .schedule-info-grid { grid-template-columns:1fr; }
            .student-schedule-body .table-container { padding:9px; }
            .student-schedule-body .schedule-footer { grid-template-columns:1fr; gap:18px; padding:21px 16px; }
        }
        @media print {
            .student-schedule-body { background:#fff; }
            .student-schedule-body .page-container { max-width:none; padding:0; }
            .student-schedule-body .page-header,.student-schedule-body .steps,.student-schedule-body .department-panel,.student-schedule-body .selection-section,.student-schedule-body .schedule-actions { display:none !important; }
            .student-schedule-body .main-layout { display:block; }
            .student-schedule-body .schedule-wrapper { display:block !important; }
            .student-schedule-body .schedule-paper { border:0; border-radius:0; box-shadow:none; }
            .student-schedule-body .schedule-top { padding:0 0 15px; }
            .student-schedule-body .schedule-table { min-width:0 !important; font-size:9px; }
            .student-schedule-body .schedule-table th,.student-schedule-body .schedule-table td { padding:5px 3px; }
            .student-schedule-body .schedule-footer { padding-right:0; padding-left:0; }
        }

        .student-schedule-body .student-excel-wrapper { position:relative; max-height:none; min-height:0; overflow:visible; border:1px solid #dce6dc; border-radius:14px; background:#fff; }
        .student-schedule-body .student-excel-empty-state { position:absolute; z-index:9; top:66px; left:50%; display:grid; gap:5px; width:min(380px,calc(100% - 28px)); padding:16px 18px; transform:translateX(-50%); border:1px solid #dce6dc; border-radius:12px; background:rgba(255,255,255,.96); box-shadow:0 8px 22px rgba(31,57,43,.09); color:#65756a; text-align:center; font-size:12px; line-height:1.5; }
        .student-schedule-body .student-excel-empty-state[hidden] { display:none !important; }
        .student-schedule-body .student-excel-empty-state strong { color:#294437; font-size:14px; font-weight:850; }
        .student-schedule-body .student-excel-table { position:relative; width:100%; min-width:0; table-layout:fixed; border-spacing:0; border-collapse:separate; overflow:visible; }
        .student-schedule-body .student-excel-table th { height:52px; padding:11px 8px; background:#245940; color:#fff; font-size:12px; font-weight:850; letter-spacing:.055em; text-align:center; }
        .student-schedule-body .student-excel-table th:first-child,.student-schedule-body .student-excel-table th:nth-child(5) { background:#173b33; }
        .student-schedule-body .student-excel-table .student-time-column { width:110px; min-width:110px; max-width:110px; background:#f4f7f2; color:#405a49; font-size:11px; font-weight:850; }
        .student-schedule-body .student-excel-table td { height:128px; padding:0; border-color:#e3ebe3; vertical-align:top; }
        .student-schedule-body .student-excel-table td.student-time-column { padding:9px 6px; background:#f4f7f2; color:#405a49; font-size:10px; font-weight:800; text-align:center; vertical-align:middle; white-space:nowrap; }
        .student-schedule-body .student-excel-cell { position:relative; background:#fff; }
        .student-schedule-body .student-excel-table .student-noon-break td { height:30px; padding:7px; background:#f3f1e8; color:#8d7d56; font-size:10px; font-weight:850; letter-spacing:.14em; text-align:center; }
        .student-schedule-block-layer { position:absolute; inset:0; z-index:8; pointer-events:none; }
        .student-schedule-block { position:absolute; display:flex; min-height:0; flex-direction:column; gap:3px; padding:7px 8px; overflow:hidden; border:1px solid rgba(50,112,77,.32); border-left:4px solid #32704d; border-radius:9px; background:linear-gradient(145deg,#f0f7ef,#fbfdf9); box-shadow:0 3px 9px rgba(31,57,43,.08); box-sizing:border-box; color:#34483d; pointer-events:auto; }
        .student-schedule-block.student-temporary-room-swap { border-color:#c9a7e8; border-left-color:#7e22ce; background:linear-gradient(145deg,#f6edff,#fcf9ff); box-shadow:0 3px 9px rgba(126,34,206,.12); }
        .student-schedule-block::-webkit-scrollbar { display:none; }
        .student-schedule-block .student-block-heading { display:flex; min-width:0; align-items:flex-start; justify-content:space-between; gap:4px; }
        .student-schedule-block .student-block-days { width:max-content; max-width:100%; padding:2px 6px; border-radius:5px; background:#e3efe0; color:#315540; font-size:9px; font-weight:850; line-height:1.2; }
        .student-schedule-block .student-block-code { min-width:0; color:#1f6844; font-size:14px; font-weight:900; line-height:1.2; overflow-wrap:anywhere; }
        .student-schedule-block .student-block-time { flex:0 0 auto; padding:3px 5px; border:1px solid rgba(52,76,97,.12); border-radius:5px; background:rgba(255,255,255,.92); color:#344b5d; font-size:11px; font-weight:850; line-height:1.2; white-space:nowrap; }
        .student-schedule-block .student-block-title { color:#294437; font-size:13px; font-weight:800; line-height:1.25; overflow-wrap:anywhere; }
        .student-schedule-block .student-block-instructor,.student-schedule-block .student-block-room { color:#435d4b; font-size:11px; font-weight:750; line-height:1.2; overflow-wrap:anywhere; }
        .student-schedule-block .student-block-meta { display:flex; flex-wrap:wrap; gap:3px 4px; margin-top:0; }
        .student-schedule-block .student-block-meta span { padding:2px 4px; border:1px solid #d9e8d7; border-radius:5px; background:#eaf3e7; color:#245940; font-size:10px; font-weight:800; line-height:1.15; overflow-wrap:anywhere; }
        .student-schedule-block .student-block-shared-majors { display:flex; flex-wrap:wrap; gap:3px 5px; align-items:baseline; color:#40584a; font-size:10px; line-height:1.2; }
        .student-schedule-block .student-block-shared-majors strong { color:#28543a; font-size:10px; font-weight:900; }
        .student-schedule-block .student-block-shared-majors span { font-weight:750; overflow-wrap:anywhere; }
        .student-schedule-block { container-type:inline-size; overflow:auto; scrollbar-width:thin; scrollbar-color:#b8c8ba transparent; }
        .student-schedule-block .student-block-code { font-size:clamp(9px,3.4cqw,14px); }
        .student-schedule-block .student-block-time { font-size:clamp(8px,2.8cqw,11px); }
        .student-schedule-block .student-block-instructor,.student-schedule-block .student-block-room { font-size:clamp(8px,2.5cqw,11px); }
        .student-schedule-block[data-duration-minutes="30"] { gap:2px; padding:4px 5px; }
        .student-schedule-block[data-duration-minutes="30"] .student-block-code { font-size:11px; }
        .student-schedule-block[data-duration-minutes="30"] .student-block-time { padding:1px 3px; font-size:9px; }
        .student-schedule-block[data-duration-minutes="30"] .student-block-instructor,
        .student-schedule-block[data-duration-minutes="30"] .student-block-room { font-size:9px; line-height:1.05; }
        .student-schedule-body .student-mobile-sheet { display:none; }
        .student-mobile-sheet { padding:12px; background:#fff; }
        .student-mobile-days { display:grid; grid-template-columns:repeat(6,minmax(0,1fr)); gap:5px; margin-bottom:12px; }
        .student-mobile-day { min-width:0; min-height:39px; padding:7px 3px; border:1px solid #dce6dc; border-radius:8px; background:#f7faf6; color:#405a49; font-size:11px; font-weight:850; }
        .student-mobile-day[aria-pressed="true"] { border-color:#245940; background:#245940; color:#fff; }
        .student-mobile-sheet-title { margin:0 0 8px; color:#294437; font-size:14px; font-weight:850; }
        .student-mobile-table { width:100%; table-layout:fixed; border-collapse:collapse; }
        .student-mobile-table th { padding:8px 7px; border:1px solid #dfe8de; background:#edf3eb; color:#245940; font-size:11px; font-weight:850; text-align:left; }
        .student-mobile-table th:first-child,.student-mobile-table td:first-child { width:34%; }
        .student-mobile-table td { padding:7px; border:1px solid #e3ebe3; vertical-align:top; }
        .student-mobile-time { background:#f7f9f5; color:#344b3e; font-size:11px; font-weight:850; line-height:1.35; }
        .student-mobile-entry { display:grid; gap:3px; min-width:0; }
        .student-mobile-entry.student-temporary-room-swap { padding:7px 8px; border:1px solid #dec8f2; border-left:4px solid #7e22ce; border-radius:8px; background:#f8f1ff; }
        .student-mobile-entry strong { color:#1f6844; font-size:13px; font-weight:900; line-height:1.25; overflow-wrap:anywhere; }
        .student-mobile-entry span { color:#40584a; font-size:12px; font-weight:700; line-height:1.35; overflow-wrap:anywhere; }
        .student-mobile-empty { padding:18px 10px !important; color:#718075; font-size:12px; text-align:center; }
        @media(max-width:760px) {
            .student-schedule-body .student-excel-wrapper { display:block !important; width:100%; max-height:none; min-height:0; overflow:visible; -webkit-overflow-scrolling:touch; }
            .student-schedule-body .student-excel-table,.student-schedule-body .student-schedule-block-layer { display:none !important; }
            .student-schedule-body .student-mobile-sheet { display:block; width:100%; }
            .student-schedule-body .student-excel-empty-state { display:none !important; }
            .student-schedule-body .schedule-paper { overflow:visible; }

            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-table { display:table !important; width:100%; min-width:0; table-layout:fixed; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block-layer { display:block !important; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-mobile-sheet { display:block; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-empty-state { display:grid !important; top:100px; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-empty-state[hidden] { display:none !important; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-table th { height:34px; padding:3px 1px; font-size:7px; letter-spacing:0; line-height:1.1; overflow-wrap:anywhere; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-table th:not(.student-time-column) { width:auto; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-table .student-time-column { width:12% !important; min-width:0; max-width:none; padding:2px 1px; font-size:6px; line-height:1.1; white-space:normal; overflow-wrap:anywhere; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-table td { height:82px; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-table td.student-time-column { padding:2px 1px; font-size:6px; white-space:normal; overflow-wrap:anywhere; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-excel-table .student-noon-break td { height:21px; padding:3px 1px; font-size:6px; letter-spacing:.04em; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block { gap:1px; padding:2px; border-left-width:2px; border-radius:3px; box-shadow:none; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block .student-block-heading { display:block; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block .student-block-code { display:block; font-size:7px; line-height:1.05; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block .student-block-time { display:block; width:max-content; max-width:100%; padding:1px 2px; font-size:6px; line-height:1.05; white-space:normal; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block .student-block-instructor,
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block .student-block-room { display:block; font-size:6px; line-height:1.05; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block[data-duration-minutes="30"] { gap:0; padding:1px; }
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block[data-duration-minutes="30"] .student-block-code,
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block[data-duration-minutes="30"] .student-block-time,
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block[data-duration-minutes="30"] .student-block-instructor,
            .student-schedule-body .student-excel-wrapper[data-mobile-view="all"] .student-schedule-block[data-duration-minutes="30"] .student-block-room { font-size:7px; line-height:1.05; }
        }
        @media(max-width:420px) {
            .student-mobile-sheet { padding:8px; }
            .student-mobile-days { gap:3px; }
            .student-mobile-day { min-height:36px; font-size:10px; }
            .student-mobile-table th:first-child,.student-mobile-table td:first-child { width:36%; }
            .student-mobile-table th,.student-mobile-table td { padding:6px 5px; }
            .student-mobile-time { font-size:10px; }
            .student-mobile-entry strong { font-size:12px; }
            .student-mobile-entry span { font-size:11px; }
        }
        @media(max-width:380px) {
            .student-schedule-body .schedule-actions { flex-wrap:wrap; }
            .student-schedule-body .schedule-sheet-controls { width:100%; }
        }
        @media print {
            .student-schedule-body .student-excel-wrapper { display:block !important; max-height:none !important; overflow:visible !important; }
        }
    </style>

</head>


<body class="student-schedule-body">

<div class="page-container">


    <!-- =========================================================
         HEADER
    ========================================================== -->

    <header class="page-header">

        <div class="page-header-content">

            <div class="header-left">

                <div class="header-icon">
                    📅
                </div>

                <div>

                    <h1>
                        Student Class Schedule
                    </h1>

                    <p>
                        View your published class schedule by college,
                        program, and year level.
                    </p>

                </div>

            </div>


            <div class="academic-info">

                <span>
                    Current Academic Period
                </span>

                <strong>
                    {{ strtoupper($semester) }}
                </strong>

                <span>
                    A.Y. {{ $academicYear }}
                </span>

                @if($activeTerm)
                    <div class="active-term-badge">
                        <span class="active-term-dot"></span>
                        ACTIVE TERM
                    </div>
                @else
                    <div class="active-term-badge inactive-term-badge">
                        NO ACTIVE TERM
                    </div>
                @endif

            </div>

        </div>

    </header>


    <!-- =========================================================
         STEP INDICATOR
    ========================================================== -->

    <div class="steps">

        <div
            class="step active"
            id="stepDepartment"
        >

            <div class="step-number">
                1
            </div>

            <div class="step-text">

                <span class="step-title">
                    College
                </span>

                <span class="step-description">
                    Choose your college
                </span>

            </div>

        </div>


        <div
            class="step"
            id="stepProgram"
        >

            <div class="step-number">
                2
            </div>

            <div class="step-text">

                <span class="step-title">
                    Program
                </span>

                <span class="step-description">
                    Choose your program
                </span>

            </div>

        </div>


        <div
            class="step"
            id="stepYear"
        >

            <div class="step-number">
                3
            </div>

            <div class="step-text">

                <span class="step-title">
                    Year Level
                </span>

                <span class="step-description">
                    View your schedule
                </span>

            </div>

        </div>

    </div>


    <!-- =========================================================
         MAIN
    ========================================================== -->

    <div class="main-layout">


        <!-- =====================================================
             DEPARTMENTS
        ====================================================== -->

        <aside class="department-panel">

            <div class="panel-header">

                <div class="panel-title">

                    <span class="panel-title-icon">
                        ▦
                    </span>

                    Select College

                </div>

                <div class="panel-subtitle">
                    Choose the college that manages your program.
                </div>

            </div>


            <div class="department-content">

                <div class="search-wrapper">

                    <span class="search-icon">
                        🔍
                    </span>

                    <input
                        type="text"
                        id="departmentSearch"
                        class="search-box"
                        placeholder="Search college..."
                        autocomplete="off"
                    >

                </div>


                <div
                    class="department-list"
                    id="departmentList"
                >

                    @forelse($departments as $index => $department)

                        <button
                            type="button"
                            class="department-button"
                            data-id="{{ $department->id }}"
                            data-name="{{ $department->name }}"
                        >

                            <span class="department-number">
                                {{ $index + 1 }}
                            </span>

                            <span class="department-name">
                                {{ $department->name }}
                            </span>

                        </button>

                    @empty

                        <div class="no-department">
                            No colleges are currently available.
                        </div>

                    @endforelse

                </div>

            </div>

        </aside>


        <!-- =====================================================
             RIGHT CONTENT
        ====================================================== -->

        <main class="content-panel">


            <!-- =================================================
                 EMPTY STATE
            ================================================== -->

            <div
                class="empty-state"
                id="emptyState"
            >

                <div class="empty-icon">
                    📚
                </div>

                <h2>
                    @if($activeTerm)
                        Select Your College
                    @else
                        No Active Academic Term
                    @endif
                </h2>

                <p>
                    @if($activeTerm)
                        Start by selecting your college from the
                        list. The available programs will automatically
                        be loaded from the published schedules for
                        <strong>
                            {{ $activeTerm->academic_year }}
                            •
                            {{ $activeTerm->semester }}
                        </strong>.
                    @else
                        There is currently no active academic term.
                        Student schedules will become available when
                        an academic term is activated.
                    @endif
                </p>

            </div>


            <!-- =================================================
                 PROGRAM
            ================================================== -->

            <section
                class="selection-section"
                id="programSection"
            >

                <div class="selection-heading">

                    <div class="selection-heading-left">

                        <div class="selection-label">
                            Step 2
                        </div>

                        <div class="selection-title">
                            Select Program
                        </div>

                        <div
                            class="selection-context"
                            id="programSelectedDepartment"
                        >
                        </div>

                    </div>

                </div>


                <div
                    class="program-buttons"
                    id="programButtons"
                >

                    <div class="program-loading">
                        <span class="spinner"></span>
                        Loading programs...
                    </div>

                </div>

            </section>


            <!-- =================================================
                 YEAR LEVEL
            ================================================== -->

            <section
                class="selection-section"
                id="yearSection"
            >

                <div class="selection-heading">

                    <div class="selection-heading-left">

                        <div class="selection-label">
                            Step 3
                        </div>

                        <div class="selection-title">
                            Select Year Level
                        </div>

                        <div
                            class="selection-context"
                            id="yearSelectedDepartment"
                        >
                        </div>

                        <div
                            class="selection-context"
                            id="selectedProgram"
                        >
                        </div>

                    </div>

                </div>


                <div
                    class="year-buttons"
                    id="yearButtons"
                >

                    <div class="year-loading">
                        Select a program first.
                    </div>

                </div>

            </section>


            <!-- =================================================
                 SCHEDULE
            ================================================== -->

            <section
                class="schedule-wrapper"
                id="scheduleWrapper"
            >

                <div class="schedule-paper schedule-sheet" data-schedule-sheet>


                    <!-- SCHEDULE HEADER -->

                    <div class="schedule-top">

                        <div class="schedule-title-row">

                            <div>

                                <div class="schedule-title">
                                    CLASS SCHEDULE
                                </div>

                                <div class="schedule-subtitle">

    @if($activeTerm)

        {{ strtoupper($activeTerm->semester) }}

        •

        A.Y. {{ $activeTerm->academic_year }}

    @else

        No Active Academic Term

    @endif

</div>

                            </div>


                            <div class="schedule-actions">

                                <button
                                    type="button"
                                    class="action-button primary"
                                    onclick="loadSchedules()"
                                >
                                    ↻ Refresh
                                </button>

                                @include('components.schedule-sheet-controls')

                            </div>

                        </div>


                        <div class="schedule-info-grid">


                            <div class="info-item">

                                <span class="info-label">
                                    College
                                </span>

                                <span
                                    class="info-value"
                                    id="scheduleDepartment"
                                >
                                </span>

                            </div>


                            <div class="info-item">

                                <span class="info-label">
                                    Program
                                </span>

                                <span
                                    class="info-value"
                                    id="scheduleProgram"
                                >
                                </span>

                            </div>


                            <div class="info-item">

                                <span class="info-label">
                                    Year Level
                                </span>

                                <span
                                    class="info-value"
                                    id="scheduleYear"
                                >
                                </span>

                            </div>

                        </div>

                    </div>


                    <!-- SCHEDULE TABLE -->

                    <div class="table-container student-excel-wrapper">
                        <div id="studentMobileSheet" class="student-mobile-sheet" aria-label="Mobile class timetable"></div>
                        <table class="schedule-table student-excel-table">
                            <thead id="studentExcelHead"></thead>
                            <tbody id="scheduleBody"></tbody>
                        </table>
                        <div id="studentScheduleEmptyState" class="student-excel-empty-state" hidden>
                            <strong>No published classes yet</strong>
                            <span>The selected program and year level do not have a schedule in the active term.</span>
                        </div>
                    </div>

                    <!-- FOOTER -->

                    <div class="schedule-footer">

                        <div class="signature">

                            Prepared by:

                            <div class="signature-line"></div>

                            <div class="signature-name">
                                PROGRAM COORDINATOR
                            </div>

                        </div>


                        <div class="signature">

                            Approved by:

                            <div class="signature-line"></div>

                            <div class="signature-name">
                                DEAN
                            </div>

                        </div>

                    </div>

                </div>

            </section>
        </main>

    </div>

</div>


<script>

    /* =========================================================
       SELECTED VALUES
    ========================================================= */

    let selectedDepartmentId = null;

    let selectedDepartmentName = "";

    let selectedProgram = "";

    let programDisplayLabels = {};

    let selectedYearLevel = "";

    function displayProgramCode(program) {
        const value = String(program || '').trim();
        if (programDisplayLabels[value]) return programDisplayLabels[value];

        return value
            .split(/\s*\/\s*/)
            .map(function(item) {
                const label = item.trim();
                const match = label.match(/\(([^()]*)\)\s*$/);
                return match ? match[1].trim() : label;
            })
            .filter(Boolean)
            .join(' / ');
    }


    /*
     * =========================================================
     * ACTIVE ACADEMIC TERM
     * =========================================================
     *
     * The active term is supplied by StudentController.
     * Every program, year-level, and schedule request sends
     * this term ID so the backend can return only records
     * belonging to the active academic term.
     */
    const activeAcademicTermId =
        @json($activeTerm?->id);


    const activeAcademicYear =
        @json($activeTerm?->academic_year);


    const activeSemester =
        @json($activeTerm?->semester);


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const departmentButtons =
        document.querySelectorAll(
            ".department-button"
        );


    /* =========================================================
       DEPARTMENT SELECTION
    ========================================================= */

    departmentButtons.forEach(function(button) {

        button.addEventListener(
            "click",
            function() {

                selectDepartment(button);

            }
        );

    });


    function selectDepartment(button)
    {

        if (!activeAcademicTermId) {

            document
                .getElementById("emptyState")
                .style.display = "flex";

            document
                .getElementById("programSection")
                .classList.remove("show");

            document
                .getElementById("yearSection")
                .classList.remove("show");

            document
                .getElementById("scheduleWrapper")
                .classList.remove("show");

            return;

        }


        selectedDepartmentId =
            button.dataset.id;

        selectedDepartmentName =
            button.dataset.name || "";


        selectedProgram = "";

        selectedYearLevel = "";


        /* Active department */

        document
            .querySelectorAll(".department-button")
            .forEach(function(item) {

                item.classList.remove("active");

            });


        button.classList.add("active");


        /* Hide default */

        document
            .getElementById("emptyState")
            .style.display = "none";


        /* Show program */

        document
            .getElementById("programSection")
            .classList.add("show");


        /* Hide year */

        document
            .getElementById("yearSection")
            .classList.remove("show");


        /* Hide schedule */

        document
            .getElementById("scheduleWrapper")
            .classList.remove("show");


        /* Update text */

        document
            .getElementById("programSelectedDepartment")
            .textContent =
                "College: " +
                selectedDepartmentName;


        document
            .getElementById("yearSelectedDepartment")
            .textContent = "";


        document
            .getElementById("selectedProgram")
            .textContent = "";


        /* Update steps */

        updateSteps(2);


        /* Reset program area */

        document
            .getElementById("programButtons")
            .innerHTML = `

                <div class="program-loading">
                    <span class="spinner"></span>
                    Loading programs...
                </div>

            `;


        loadPrograms();

    }


    /* =========================================================
       LOAD PROGRAMS
    ========================================================= */

    function loadPrograms()
    {

        if (!selectedDepartmentId) {

            return;

        }


        const container =
            document.getElementById(
                "programButtons"
            );


        const url =
            "{{ route('student.schedule.programs') }}" +
            "?department_id=" +
            encodeURIComponent(
                selectedDepartmentId
            ) +
            "&term_id=" +
            encodeURIComponent(
                activeAcademicTermId
            );


        console.log(
            "Loading programs:",
            url
        );


        container.innerHTML = `

            <div class="program-loading">
                <span class="spinner"></span>
                Loading programs...
            </div>

        `;


        fetch(url, {

            method: "GET",

            headers: {
                "Accept": "application/json",

                "X-Requested-With":
                    "XMLHttpRequest"
            }

        })

        .then(function(response) {

            return response.text()
                .then(function(text) {

                    let data;

                    try {

                        data = JSON.parse(text);

                    } catch (error) {

                        throw new Error(
                            "The server returned an invalid response."
                        );

                    }


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            "Unable to load programs."
                        );

                    }


                    return data;

                });

        })

        .then(function(data) {

            container.innerHTML = "";
            programDisplayLabels = data.program_labels || {};


            if (
                !data.success ||
                !Array.isArray(data.programs) ||
                data.programs.length === 0
            ) {

                container.innerHTML = `

                    <div class="message-box empty">

                        No published programs are available
                        for this department.

                    </div>

                `;

                return;

            }


            data.programs.forEach(
                function(program, index) {

                    const button =
                        document.createElement(
                            "button"
                        );


                    button.type = "button";

                    button.className =
                        "program-button";


                    button.innerHTML = `

                        <span class="program-icon">
                            ${index + 1}
                        </span>

                        <span class="program-name">
                            ${escapeHtml(displayProgramCode(program))}
                        </span>

                    `;


                    button.addEventListener(
                        "click",
                        function() {

                            selectProgram(
                                program,
                                button
                            );

                        }
                    );


                    container.appendChild(
                        button
                    );

                }
            );

        })

        .catch(function(error) {

            console.error(
                "Program loading error:",
                error
            );


            container.innerHTML = `

                <div class="message-box error">

                    ${escapeHtml(
                        error.message ||
                        "Unable to load programs."
                    )}

                    <br><br>

                    <button
                        type="button"
                        class="action-button"
                        onclick="loadPrograms()"
                    >
                        Try Again
                    </button>

                </div>

            `;

        });

    }


    /* =========================================================
       PROGRAM SELECTION
    ========================================================= */

    function selectProgram(
        program,
        button
    )
    {

        selectedProgram = program;

        selectedYearLevel = "";


        /* Active program */

        document
            .querySelectorAll(".program-button")
            .forEach(function(item) {

                item.classList.remove("active");

            });


        button.classList.add("active");


        /* Show year */

        document
            .getElementById("yearSection")
            .classList.add("show");


        /* Hide schedule */

        document
            .getElementById("scheduleWrapper")
            .classList.remove("show");


        /* Update information */

        document
            .getElementById("yearSelectedDepartment")
            .textContent =
                "College: " +
                selectedDepartmentName;


        document
            .getElementById("selectedProgram")
            .textContent =
                "Program: " +
                displayProgramCode(selectedProgram);


        /* Update steps */

        updateSteps(3);


        /* Reset years */

        document
            .getElementById("yearButtons")
            .innerHTML = `

                <div class="year-loading">
                    <span class="spinner"></span>
                    Loading year levels...
                </div>

            `;


        loadYearLevels();

    }


    /* =========================================================
       LOAD YEAR LEVELS
    ========================================================= */

    function loadYearLevels()
    {

        if (
            !selectedDepartmentId ||
            !selectedProgram
        ) {

            return;

        }


        const container =
            document.getElementById(
                "yearButtons"
            );


        const url =
            "{{ route('student.schedule.years') }}" +

            "?department_id=" +

            encodeURIComponent(
                selectedDepartmentId
            ) +

            "&program=" +

            encodeURIComponent(
                selectedProgram
            ) +

            "&term_id=" +

            encodeURIComponent(
                activeAcademicTermId
            );


        console.log(
            "Loading year levels:",
            url
        );


        fetch(url, {

            method: "GET",

            headers: {
                "Accept": "application/json",

                "X-Requested-With":
                    "XMLHttpRequest"
            }

        })

        .then(function(response) {

            return response.text()
                .then(function(text) {

                    let data;

                    try {

                        data = JSON.parse(text);

                    } catch (error) {

                        throw new Error(
                            "The server returned an invalid response."
                        );

                    }


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            "Unable to load year levels."
                        );

                    }


                    return data;

                });

        })

        .then(function(data) {

            container.innerHTML = "";


            if (
                !data.success ||
                !Array.isArray(
                    data.year_levels
                ) ||
                data.year_levels.length === 0
            ) {

                container.innerHTML = `

                    <div class="message-box empty">

                        No published year levels are
                        available for this program.

                    </div>

                `;

                return;

            }


            data.year_levels.forEach(
                function(year) {

                    const button =
                        document.createElement(
                            "button"
                        );


                    button.type = "button";

                    button.className =
                        "year-button";


                    button.textContent =
                        year;


                    button.addEventListener(
                        "click",
                        function() {

                            selectYear(
                                year,
                                button
                            );

                        }
                    );


                    container.appendChild(
                        button
                    );

                }
            );

        })

        .catch(function(error) {

            console.error(
                "Year level loading error:",
                error
            );


            container.innerHTML = `

                <div class="message-box error">

                    ${escapeHtml(
                        error.message ||
                        "Unable to load year levels."
                    )}

                    <br><br>

                    <button
                        type="button"
                        class="action-button"
                        onclick="loadYearLevels()"
                    >
                        Try Again
                    </button>

                </div>

            `;

        });

    }


    /* =========================================================
       YEAR SELECTION
    ========================================================= */

    function selectYear(
        year,
        button
    )
    {

        if (!selectedDepartmentId) {

            return;

        }


        if (!selectedProgram) {

            return;

        }


        selectedYearLevel = year;


        /* Active year */

        document
            .querySelectorAll(".year-button")
            .forEach(function(item) {

                item.classList.remove("active");

            });


        button.classList.add("active");


        /* Schedule information */

        document
            .getElementById("scheduleDepartment")
            .textContent =
                selectedDepartmentName;


        document
            .getElementById("scheduleProgram")
            .textContent =
                displayProgramCode(selectedProgram);


        document
            .getElementById("scheduleYear")
            .textContent =
                selectedYearLevel;


        updateSteps(4);


        loadSchedules();

    }


    /* =========================================================
       LOAD SCHEDULE
    ========================================================= */

    function loadSchedules()
    {

        if (
            !selectedDepartmentId ||
            !selectedProgram ||
            !selectedYearLevel
        ) {

            return;

        }


        const wrapper =
            document.getElementById(
                "scheduleWrapper"
            );


        const body =
            document.getElementById(
                "scheduleBody"
            );


        wrapper.classList.add("show");
        renderStudentExcelGrid(false);
        renderStudentScheduleBlocks([]);
        const emptyState = document.getElementById('studentScheduleEmptyState');
        if (emptyState) emptyState.hidden = true;


        body.innerHTML = `

            <tr>

                <td
                    colspan="7"
                    class="no-schedule"
                >

                    <div class="loading-state">
                        <span class="spinner"></span>
                        Loading published schedule...
                    </div>

                </td>

            </tr>

        `;


        const url =
            "{{ route('student.schedule.data') }}" +

            "?department_id=" +

            encodeURIComponent(
                selectedDepartmentId
            ) +

            "&program=" +

            encodeURIComponent(
                selectedProgram
            ) +

            "&year_level=" +

            encodeURIComponent(
                selectedYearLevel
            ) +

            "&term_id=" +

            encodeURIComponent(
                activeAcademicTermId
            );


        console.log(
            "Loading schedule:",
            url
        );


        fetch(url, {

            method: "GET",

            headers: {
                "Accept": "application/json",

                "X-Requested-With":
                    "XMLHttpRequest"
            }

        })

        .then(function(response) {

            return response.text()
                .then(function(text) {

                    let data;

                    try {

                        data = JSON.parse(text);

                    } catch (error) {

                        throw new Error(
                            "The server returned an invalid response."
                        );

                    }


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            "Unable to load schedule."
                        );

                    }


                    return data;

                });

        })

        .then(function(data) {

            if (!data.success) {

                throw new Error(
                    data.message ||
                    "Unable to load schedule."
                );

            }


            /*
             * Final client-side safety check.
             *
             * The backend should already filter by academic_term_id.
             * This extra check prevents an old schedule from being
             * displayed if an older endpoint/version returns records
             * from another term.
             */
            let activeSchedules =
                Array.isArray(data.schedules)
                    ? data.schedules
                    : [];


            if (activeAcademicTermId) {

                activeSchedules =
                    activeSchedules.filter(
                        function(item) {

                            /*
                             * Preferred check:
                             * exact academic_term_id.
                             */
                            if (
                                item.academic_term_id !== undefined &&
                                item.academic_term_id !== null &&
                                String(item.academic_term_id) !== ""
                            ) {

                                return (
                                    String(item.academic_term_id) ===
                                    String(activeAcademicTermId)
                                );

                            }


                            /*
                             * Compatibility check for older records
                             * returned without academic_term_id.
                             */
                            const itemYear =
                                String(
                                    item.academic_year || ""
                                ).trim();


                            const itemSemester =
                                String(
                                    item.semester || ""
                                ).trim();


                            return (
                                itemYear ===
                                    String(
                                        activeAcademicYear || ""
                                    ).trim()
                                &&
                                itemSemester.toLowerCase() ===
                                    String(
                                        activeSemester || ""
                                    ).trim().toLowerCase()
                            );

                        }
                    );

            }


            displayStudentExcelSchedules(
                activeSchedules
            );

        })

        .catch(function(error) {

            console.error(
                "Schedule loading error:",
                error
            );


            body.innerHTML = `

                <tr>

                    <td
                        colspan="${document.getElementById('studentExcelHead')?.querySelector('tr')?.children.length || 7}"
                        class="no-schedule"
                    >

                        <div class="message-box error">

                            ${escapeHtml(
                                error.message ||
                                "Unable to load schedule."
                            )}

                        </div>

                    </td>

                </tr>

            `;

        });

    }


    /* =========================================================
       DISPLAY SCHEDULE
    ========================================================= */

    function renderStudentExcelGrid(hasSaturday) {
        const head = document.getElementById('studentExcelHead');
        const body = document.getElementById('scheduleBody');
        if (!head || !body) return;

        const headers = [
            '<th class="student-time-column">TIME</th>',
            '<th>MON</th><th>WED</th><th>FRI</th>',
            '<th class="student-time-column">TIME</th>',
            '<th>TUE</th><th>THU</th>'
        ];
        if (hasSaturday) headers.push('<th class="student-time-column">TIME</th><th>SAT</th>');
        head.innerHTML = '<tr>' + headers.join('') + '</tr>';

        const morning = [
            ['7:00 AM - 8:00 AM', 420, 480],
            ['8:00 AM - 9:00 AM', 480, 540],
            ['9:00 AM - 10:00 AM', 540, 600],
            ['10:00 AM - 11:00 AM', 600, 660],
            ['11:00 AM - 12:00 PM', 660, 720]
        ];
        const afternoon = [
            ['1:00 PM - 2:00 PM', 780, 840],
            ['2:00 PM - 3:00 PM', 840, 900],
            ['3:00 PM - 4:00 PM', 900, 960],
            ['4:00 PM - 5:00 PM', 960, 1020],
            ['5:00 PM - 6:00 PM', 1020, 1080],
            ['6:00 PM - 7:00 PM', 1080, 1140]
        ];

        function createTimeCell(slot, side) {
            const [label, start, end] = slot;
            return '<td class="student-time-column" data-time-side="' + side + '" data-time-start="' + start + '" data-time-end="' + end + '">' + label + '</td>';
        }

        function createScheduleRow(slot) {
            return '<tr>' + createTimeCell(slot, 'left') +
                ['MON', 'WED', 'FRI'].map(day => '<td class="student-excel-cell" data-day="' + day + '"></td>').join('') +
                createTimeCell(slot, 'right') +
                ['TUE', 'THU'].map(day => '<td class="student-excel-cell" data-day="' + day + '"></td>').join('') +
                (hasSaturday ? createTimeCell(slot, 'sat') + '<td class="student-excel-cell" data-day="SAT"></td>' : '') +
                '</tr>';
        }

        const rows = morning.map(createScheduleRow);
        rows.push('<tr class="student-noon-break"><td colspan="' + (hasSaturday ? 9 : 7) + '">NOON BREAK</td></tr>');
        afternoon.forEach(slot => rows.push(createScheduleRow(slot)));
        body.innerHTML = rows.join('');
    }

    function studentScheduleRange(value) {
        const parts = String(value || '').trim().match(/^(.+?)\s*(?:-|–|—|\bto\b)\s*(.+)$/i);
        if (!parts) return null;

        function readClock(token, inheritedPeriod) {
            const match = String(token || '').trim().match(/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i);
            if (!match) return null;
            let hour = Number(match[1]);
            const minute = Number(match[2]);
            let period = (match[3] || inheritedPeriod || '').toUpperCase();
            if (hour > 23 || minute > 59) return null;
            if (!period && hour <= 6 && hour > 0) period = 'PM';
            if (!period && hour >= 7 && hour <= 11) period = 'AM';
            if (!period && hour === 12) period = 'PM';
            if (hour <= 12 && period === 'PM' && hour < 12) hour += 12;
            if (hour === 12 && period === 'AM') hour = 0;
            return hour * 60 + minute;
        }

        const startMatch = String(parts[1]).trim().match(/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i);
        const endMatch = String(parts[2]).trim().match(/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i);
        if (!startMatch || !endMatch) return null;

        const startHour = Number(startMatch[1]);
        const startPeriod = (startMatch[3] || (startHour >= 1 && startHour <= 6 ? 'PM' : startHour === 12 ? 'PM' : startHour > 12 ? 'PM' : 'AM')).toUpperCase();
        const start = readClock(parts[1], startPeriod);
        const inferredEndPeriod = Number(endMatch[1]) === 12 && start < 720 ? 'PM' : startPeriod;
        let end = readClock(parts[2], inferredEndPeriod);
        if (start === null || end === null) return null;
        if (end <= start && !endMatch[3]) end += 720;
        return end > start ? { start: start, end: end } : null;
    }

    function studentFormatScheduleTime(value) {
        const range = studentScheduleRange(value);
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

    function renderStudentScheduleBlocks(schedules) {
        const wrapper = document.querySelector('.student-excel-wrapper');
        const table = wrapper?.querySelector('.student-excel-table');
        if (!wrapper || !table) return;

        wrapper._studentSchedules = schedules;
        if (window.ResizeObserver && !wrapper._studentScheduleResizeObserver) {
            wrapper._studentScheduleResizeObserver = new ResizeObserver(function() {
                renderStudentScheduleBlocks(wrapper._studentSchedules || []);
            });
            wrapper._studentScheduleResizeObserver.observe(wrapper);
        }
        if (!wrapper._studentScheduleScrollBound) {
            wrapper.addEventListener('scroll', function() {
                renderStudentScheduleBlocks(wrapper._studentSchedules || []);
            }, { passive: true });
            wrapper._studentScheduleScrollBound = true;
        }

        let layer = wrapper.querySelector('.student-schedule-block-layer');
        if (!layer) {
            layer = document.createElement('div');
            layer.className = 'student-schedule-block-layer';
            layer.setAttribute('aria-hidden', 'false');
            wrapper.appendChild(layer);
        }
        layer.replaceChildren();

        function yAtMinute(side, minute, edge) {
            const rows = Array.from(table.tBodies[0]?.rows || []).filter(row => !row.classList.contains('student-noon-break'));
            for (const row of rows) {
                const timeCell = row.querySelector('[data-time-side="' + side + '"]');
                if (!timeCell) continue;
                const start = Number(timeCell.dataset.timeStart);
                const end = Number(timeCell.dataset.timeEnd);
                if (edge === 'start' && minute === end) continue;
                if (edge === 'end' && minute === start) continue;
                if (minute < start || minute > end) continue;
                const rect = timeCell.getBoundingClientRect();
                return rect.top + rect.height * ((minute - start) / (end - start));
            }
            return null;
        }

        const wrapperRect = wrapper.getBoundingClientRect();
        const sideByDay = { MON: 'left', WED: 'left', FRI: 'left', TUE: 'right', THU: 'right', SAT: 'sat' };
        const dayOrderBySide = { left: ['MON', 'WED', 'FRI'], right: ['TUE', 'THU'], sat: ['SAT'] };
        const entriesByGroup = new Map();

        (Array.isArray(schedules) ? schedules : []).forEach(function(item) {
            const day = normalizeDay(item.day);
            const side = sideByDay[day];
            const range = studentScheduleRange(item.time);
            const cell = table.querySelector('td[data-day="' + day + '"]');
            if (!side || !cell || !range) return;

            const top = yAtMinute(side, range.start, 'start');
            const bottom = yAtMinute(side, range.end, 'end');
            if (top === null || bottom === null || bottom <= top) return;

            const approvedSharedOfferings = (Array.isArray(item.merges) ? item.merges : [])
                .filter(merge => String(merge.share_type || '').toLowerCase() === 'same_faculty'
                    && String(merge.status || '').toLowerCase() === 'approved')
                .map(merge => {
                    const subject = merge.subject || merge.faculty_subject?.subject_catalog;
                    const program = subject?.program || {};
                    return [
                        subject?.course_code || merge.faculty_subject?.course_code || '',
                        subject?.course_title || merge.faculty_subject?.subject || '',
                        program.code || program.name || merge.faculty_subject?.program || '',
                        subject?.major || program.major || merge.faculty_subject?.major || '',
                        subject?.year_level || merge.faculty_subject?.year_level || ''
                    ].join('|').toLowerCase();
                })
                .sort();
            const identity = [
                item.course_code,
                item.subject,
                item.faculty_id || item.instructor,
                item.faculty_subject_id,
                item.department_id,
                studentFormatScheduleTime(item.time),
                item.room_id,
                item.program,
                item.year_level,
                item.major,
                item.subject_type,
                item.color,
                item.section,
                item.semester,
                item.academic_year,
                approvedSharedOfferings.join(';')
            ].map(value => String(value || '').trim().toLowerCase()).join('¦');
            const groupKey = [side, range.start, range.end, identity].join('::');
            const dayOrder = dayOrderBySide[side].indexOf(day);
            if (dayOrder < 0) return;

            if (!entriesByGroup.has(groupKey)) entriesByGroup.set(groupKey, []);
            entriesByGroup.get(groupKey).push({ item: item, day: day, side: side, range: range, cell: cell, top: top, bottom: bottom, dayOrder: dayOrder });
        });

        const visualRuns = [];
        entriesByGroup.forEach(function(entries) {
            const entriesByDay = new Map();
            entries.forEach(function(entry) {
                const roomName = String(entry.item.room || '').trim();
                let dayEntry = entriesByDay.get(entry.day);
                if (!dayEntry) {
                    dayEntry = Object.assign({}, entry, { roomNames: [] });
                    entriesByDay.set(entry.day, dayEntry);
                }
                if (roomName && !dayEntry.roomNames.some(value => value.toLowerCase() === roomName.toLowerCase())) {
                    dayEntry.roomNames.push(roomName);
                }
            });

            const dayEntries = Array.from(entriesByDay.values())
                .sort((a, b) => a.dayOrder - b.dayOrder);
            let run = [];
            dayEntries.forEach(function(entry) {
                const previous = run[run.length - 1];
                const roomSignature = entry.roomNames.map(value => value.toLowerCase()).sort().join('|');
                const previousRoomSignature = previous
                    ? previous.roomNames.map(value => value.toLowerCase()).sort().join('|')
                    : roomSignature;
                if (run.length && (entry.dayOrder !== previous.dayOrder + 1 || roomSignature !== previousRoomSignature)) {
                    visualRuns.push(run);
                    run = [];
                }
                run.push(entry);
            });
            if (run.length) visualRuns.push(run);
        });

        visualRuns.forEach(function(run) {
            const first = run[0];
            const item = first.item;
            const range = first.range;
            const roomNames = [...new Set(run.flatMap(entry => entry.roomNames))];
            const firstRect = first.cell.getBoundingClientRect();
            const lastRect = run[run.length - 1].cell.getBoundingClientRect();
            const block = document.createElement('article');
            block.className = 'student-schedule-block' + (run.length > 1 ? ' student-schedule-block-merged' : '');
            if (item.room_swap?.swap_type === 'temporary') block.classList.add('student-temporary-room-swap');
            if (item.id) block.id = 'student-schedule-' + Number(item.id);
            block.dataset.durationMinutes = String(range.end - range.start);
            block.setAttribute('aria-label', [run.map(entry => entry.day).join(', '), item.course_code, item.subject, item.instructor, item.time, roomNames.join(', ')].filter(Boolean).join(' · '));
            block.style.left = (firstRect.left - wrapperRect.left - wrapper.clientLeft + wrapper.scrollLeft) + 'px';
            block.style.top = (first.top - wrapperRect.top - wrapper.clientTop + wrapper.scrollTop) + 'px';
            block.style.width = (lastRect.right - firstRect.left) + 'px';
            block.style.height = (first.bottom - first.top) + 'px';

            const instructorName = String(item.instructor || item.faculty?.name || '').trim();
            const roomLabel = roomNames.length ? roomNames.join(' · ') : String(item.room || item.room_name || '').trim();
            block.title = [
                run.map(entry => entry.day).join(', '),
                item.course_code,
                instructorName ? 'Instructor: ' + instructorName : '',
                'Time: ' + studentFormatScheduleTime(item.time || ''),
                roomLabel ? 'Room: ' + roomLabel : ''
            ].filter(Boolean).join(' · ');
            block.setAttribute('aria-label', block.title);
            block.innerHTML =
                '<div class="student-block-heading">' +
                    '<strong class="student-block-code">' + escapeHtml(item.course_code || 'Course') + '</strong>' +
                    '<span class="student-block-time">Time: ' + escapeHtml(studentFormatScheduleTime(item.time || '')) + '</span>' +
                '</div>' +
                (instructorName ? '<div class="student-block-instructor">Instructor: ' + escapeHtml(instructorName) + '</div>' : '') +
                (roomLabel ? '<div class="student-block-room">Room: ' + escapeHtml(roomLabel) + '</div>' : '');
            layer.appendChild(block);
        });
    }

    function renderStudentMobileSheet(schedules, hasSaturday) {
        const host = document.getElementById('studentMobileSheet');
        if (!host) return;

        const weekdays = [
            { code: 'MON', short: 'Mon', name: 'Monday' },
            { code: 'TUE', short: 'Tue', name: 'Tuesday' },
            { code: 'WED', short: 'Wed', name: 'Wednesday' },
            { code: 'THU', short: 'Thu', name: 'Thursday' },
            { code: 'FRI', short: 'Fri', name: 'Friday' }
        ];
        if (hasSaturday) weekdays.push({ code: 'SAT', short: 'Sat', name: 'Saturday' });
        const views = [{ code: 'ALL', short: 'All' }, ...weekdays];

        host._studentSchedules = Array.isArray(schedules) ? schedules : [];
        if (!views.some(day => day.code === host.dataset.activeDay)) {
            const firstScheduledDay = weekdays.find(day => host._studentSchedules.some(item => normalizeDay(item.day) === day.code));
            host.dataset.activeDay = (firstScheduledDay || weekdays[0]).code;
        }

        if (!host.dataset.dayTabsBound) {
            host.addEventListener('click', function(event) {
                const button = event.target.closest('[data-mobile-day]');
                if (!button || !host.contains(button)) return;
                host.dataset.activeDay = button.dataset.mobileDay;
                renderStudentMobileSheet(host._studentSchedules, host._hasSaturday);
            });
            host.dataset.dayTabsBound = 'true';
        }
        host._hasSaturday = hasSaturday;

        const activeDay = views.find(day => day.code === host.dataset.activeDay) || weekdays[0];
        const isAllDays = activeDay.code === 'ALL';
        const activeWeekday = isAllDays ? null : activeDay;
        const wrapper = host.closest('.student-excel-wrapper');
        if (wrapper) wrapper.dataset.mobileView = isAllDays ? 'all' : 'day';
        const dayEntries = host._studentSchedules.filter(item =>
            weekdays.some(day => day.code === normalizeDay(item.day))
                && (isAllDays || normalizeDay(item.day) === activeDay.code)
        );
        const grouped = new Map();
        dayEntries.forEach(item => {
            const day = normalizeDay(item.day);
            const key = [day, item.time, item.course_code, item.instructor].map(value => String(value || '').trim().toLowerCase()).join('|');
            if (!grouped.has(key)) grouped.set(key, { item, day, rooms: [] });
            const room = String(item.room || item.room_name || '').trim();
            if (room && !grouped.get(key).rooms.some(existing => existing.toLowerCase() === room.toLowerCase())) {
                grouped.get(key).rooms.push(room);
            }
        });

        const entries = [...grouped.values()].sort((a, b) => {
            const dayOrder = { MON: 1, TUE: 2, WED: 3, THU: 4, FRI: 5, SAT: 6 };
            if (isAllDays && dayOrder[a.day] !== dayOrder[b.day]) return (dayOrder[a.day] || 99) - (dayOrder[b.day] || 99);
            const first = studentScheduleRange(a.item.time);
            const second = studentScheduleRange(b.item.time);
            return (first?.start ?? 0) - (second?.start ?? 0);
        });

        const dayTable = isAllDays ? '' :
            '<table class="student-mobile-table"><thead><tr><th scope="col">Time</th><th scope="col">Class</th></tr></thead><tbody>' +
                (entries.length ? entries.map(entry => {
                    const item = entry.item;
                    const instructor = String(item.instructor || item.faculty?.name || '').trim();
                    const room = entry.rooms.length ? entry.rooms.join(' · ') : String(item.room || item.room_name || 'Room not set').trim();
                    return '<tr><td class="student-mobile-time">' + escapeHtml(studentFormatScheduleTime(item.time || '')) + '</td>' +
                        '<td><div class="student-mobile-entry' + (item.room_swap?.swap_type === 'temporary' ? ' student-temporary-room-swap' : '') + '"><strong>' + escapeHtml(item.course_code || 'Course') + '</strong>' +
                            (instructor ? '<span>Instructor: ' + escapeHtml(instructor) + '</span>' : '') +
                            '<span>Room: ' + escapeHtml(room) + '</span></div></td></tr>';
                }).join('') : '<tr><td colspan="2" class="student-mobile-empty">No classes scheduled for this day.</td></tr>') +
                '</tbody></table>';

        host.innerHTML =
            '<div class="student-mobile-days" role="tablist" aria-label="Choose a schedule view" style="grid-template-columns:repeat(' + views.length + ',minmax(0,1fr))">' +
                views.map(day => '<button type="button" class="student-mobile-day" role="tab" data-mobile-day="' + day.code + '" aria-selected="' + (day.code === activeDay.code ? 'true' : 'false') + '" aria-pressed="' + (day.code === activeDay.code ? 'true' : 'false') + '">' + day.short + '</button>').join('') +
            '</div>' +
            '<h3 class="student-mobile-sheet-title">' + (isAllDays ? 'Full weekly spreadsheet' : activeWeekday.name) + '</h3>' +
            dayTable;

        if (isAllDays && wrapper) {
            requestAnimationFrame(() => renderStudentScheduleBlocks(host._studentSchedules));
        }
    }

    function displayStudentExcelSchedules(schedules) {
        const body = document.getElementById('scheduleBody');
        const emptyState = document.getElementById('studentScheduleEmptyState');
        const scheduleTitle = document.querySelector('.schedule-title');
        const rows = Array.isArray(schedules) ? [...schedules] : [];
        if (window.studentRoomSwapTransitionTimer) {
            window.clearTimeout(window.studentRoomSwapTransitionTimer);
            window.studentRoomSwapTransitionTimer = null;
        }
        const currentTimestamp = Date.now();
        const nextSwapTransition = rows
            .flatMap(item => item.room_swap ? [item.room_swap.start_at, item.room_swap.end_at] : [])
            .map(value => value ? new Date(value).getTime() : NaN)
            .filter(value => Number.isFinite(value) && value > currentTimestamp)
            .sort((first, second) => first - second)[0];
        if (nextSwapTransition) {
            window.studentRoomSwapTransitionTimer = window.setTimeout(function() {
                window.location.reload();
            }, Math.min(nextSwapTransition - currentTimestamp + 300, 2147480000));
        }
        const dayOrder = { MON: 1, TUE: 2, WED: 3, THU: 4, FRI: 5, SAT: 6 };
        rows.sort(function(a, b) {
            const dayDifference = (dayOrder[normalizeDay(a.day)] || 99) - (dayOrder[normalizeDay(b.day)] || 99);
            return dayDifference || String(a.time || '').localeCompare(String(b.time || ''));
        });

        const hasSaturday = rows.some(item => normalizeDay(item.day) === 'SAT');
        renderStudentExcelGrid(hasSaturday);
        renderStudentMobileSheet(rows, hasSaturday);
        if (scheduleTitle) {
            scheduleTitle.textContent = 'CLASS SCHEDULE (' + rows.length + ' SUBJECT' + (rows.length === 1 ? '' : 'S') + ')';
        }

        if (!rows.length) {
            if (emptyState) emptyState.hidden = false;
        } else {
            if (emptyState) emptyState.hidden = true;
        }

        renderStudentScheduleBlocks(rows);
    }

    /* =========================================================
       SEARCH DEPARTMENT
    ========================================================= */

    const departmentSearch =
        document.getElementById(
            "departmentSearch"
        );


    if (departmentSearch) {

        departmentSearch.addEventListener(
            "input",
            searchDepartments
        );

    }


    function searchDepartments()
    {

        const search =
            document
                .getElementById(
                    "departmentSearch"
                )
                .value
                .toLowerCase()
                .trim();


        const departments =
            document.querySelectorAll(
                ".department-button"
            );


        let found = false;


        departments.forEach(
            function(department) {

                const name =
                    (
                        department.dataset.name ||
                        ""
                    )
                    .toLowerCase();


                if (
                    search === "" ||
                    name.includes(search)
                ) {

                    department.style.display =
                        "flex";

                    found = true;

                } else {

                    department.style.display =
                        "none";

                }

            }
        );


        let noResult =
            document.getElementById(
                "departmentNoResult"
            );


        if (!found) {

            if (!noResult) {

                noResult =
                    document.createElement(
                        "div"
                    );


                noResult.id =
                    "departmentNoResult";


                noResult.className =
                    "no-department";


                noResult.textContent =
                    "No college found.";


                document
                    .getElementById(
                        "departmentList"
                    )
                    .appendChild(
                        noResult
                    );

            }

        } else {

            if (noResult) {

                noResult.remove();

            }

        }

    }


    /* =========================================================
       STEP INDICATOR
    ========================================================= */

    function updateSteps(step)
    {

        const departmentStep =
            document.getElementById(
                "stepDepartment"
            );


        const programStep =
            document.getElementById(
                "stepProgram"
            );


        const yearStep =
            document.getElementById(
                "stepYear"
            );


        [
            departmentStep,
            programStep,
            yearStep
        ].forEach(function(item) {

            item.classList.remove(
                "active",
                "completed"
            );

        });


        if (step >= 1) {

            departmentStep.classList.add(
                step > 1
                    ? "completed"
                    : "active"
            );

        }


        if (step >= 2) {

            programStep.classList.add(
                step > 2
                    ? "completed"
                    : "active"
            );

        }


        if (step >= 3) {

            yearStep.classList.add(
                "active"
            );

        }

    }


    /* =========================================================
       NORMALIZE DAY
    ========================================================= */

    function normalizeDay(day)
    {

        if (!day) {

            return "";

        }


        const value =
            String(day)
                .trim()
                .toUpperCase();


        if (
            value === "MON" ||
            value === "MONDAY"
        ) {

            return "MON";

        }


        if (
            value === "TUE" ||
            value === "TUESDAY"
        ) {

            return "TUE";

        }


        if (
            value === "WED" ||
            value === "WEDNESDAY"
        ) {

            return "WED";

        }


        if (
            value === "THU" ||
            value === "THURSDAY"
        ) {

            return "THU";

        }


        if (
            value === "FRI" ||
            value === "FRIDAY"
        ) {

            return "FRI";

        }

        if (value === "SAT" || value === "SATURDAY") {
            return "SAT";
        }


        return value;

    }


    /* =========================================================
       ESCAPE HTML
    ========================================================= */

    function escapeHtml(value)
    {

        const div =
            document.createElement(
                "div"
            );


        div.textContent =
            value ?? "";


        return div.innerHTML;

    }


</script>

    
</body>

</html>
