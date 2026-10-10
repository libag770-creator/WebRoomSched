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

    </style>

</head>


<body>

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
                        View your published class schedule by department,
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
                    Department
                </span>

                <span class="step-description">
                    Choose your department
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

                    Select Department

                </div>

                <div class="panel-subtitle">
                    Choose the department that manages your program.
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
                        placeholder="Search department..."
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
                            No departments are currently available.
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
                        Select Your Department
                    @else
                        No Active Academic Term
                    @endif
                </h2>

                <p>
                    @if($activeTerm)
                        Start by selecting your department from the
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

                <div class="schedule-paper">


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
                                    class="action-button"
                                    onclick="printSchedule()"
                                >
                                    🖨 Print
                                </button>

                                <button
                                    type="button"
                                    class="action-button primary"
                                    onclick="loadSchedules()"
                                >
                                    ↻ Refresh
                                </button>

                            </div>

                        </div>


                        <div class="schedule-info-grid">


                            <div class="info-item">

                                <span class="info-label">
                                    Department
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

                    <div class="table-container">

                        <table class="schedule-table">

                            <thead>

                                <tr>

                                    <th
                                        rowspan="2"
                                        class="course-code-column"
                                    >
                                        COURSE<br>CODE
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="description-column"
                                    >
                                        DESCRIPTIVE TITLE
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="type-column"
                                    >
                                        TYPE
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="instructor-column"
                                    >
                                        INSTRUCTOR
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="major-column"
                                    >
                                        MAJOR
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="room-column"
                                    >
                                        ROOM
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="time-column"
                                    >
                                        TIME
                                    </th>

                                    <th colspan="5">
                                        DAY
                                    </th>

                                    <th
                                        rowspan="2"
                                        class="remarks-column"
                                    >
                                        REMARKS
                                    </th>

                                </tr>


                                <tr>

                                    <th class="day-column">
                                        MON
                                    </th>

                                    <th class="day-column">
                                        TUE
                                    </th>

                                    <th class="day-column">
                                        WED
                                    </th>

                                    <th class="day-column">
                                        THU
                                    </th>

                                    <th class="day-column">
                                        FRI
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="scheduleBody">

                            </tbody>

                        </table>

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

    let selectedYearLevel = "";


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
                "Department: " +
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
                            ${escapeHtml(program)}
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
                "Department: " +
                selectedDepartmentName;


        document
            .getElementById("selectedProgram")
            .textContent =
                "Program: " +
                selectedProgram;


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
                selectedProgram;


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


        body.innerHTML = `

            <tr>

                <td
                    colspan="13"
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


            displaySchedules(
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
                        colspan="13"
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

    function displaySchedules(
        schedules
    )
    {

        const body =
            document.getElementById(
                "scheduleBody"
            );


        body.innerHTML = "";


        if (
            !schedules ||
            schedules.length === 0
        ) {

            body.innerHTML = `

                <tr>

                    <td
                        colspan="13"
                        class="no-schedule"
                    >

                        <strong>
                            No published schedule found.
                        </strong>

                        <br><br>

                        ${escapeHtml(
                            selectedProgram
                        )}
                        —
                        ${escapeHtml(
                            selectedYearLevel
                        )}

                    </td>

                </tr>

            `;

            return;

        }


        /* -----------------------------------------------------
           Sort schedules by day and time
        ----------------------------------------------------- */

        schedules.sort(
            function(a, b) {

                const dayOrder = {

                    "Monday": 1,
                    "Tuesday": 2,
                    "Wednesday": 3,
                    "Thursday": 4,
                    "Friday": 5,

                    "MON": 1,
                    "TUE": 2,
                    "WED": 3,
                    "THU": 4,
                    "FRI": 5

                };


                const dayA =
                    dayOrder[a.day] || 99;


                const dayB =
                    dayOrder[b.day] || 99;


                if (dayA !== dayB) {

                    return dayA - dayB;

                }


                return String(
                    a.time || ""
                ).localeCompare(
                    String(
                        b.time || ""
                    )
                );

            }
        );


        /* -----------------------------------------------------
           Update schedule count
        ----------------------------------------------------- */

        const scheduleTitle =
            document.querySelector(
                ".schedule-title"
            );


        if (scheduleTitle) {

            scheduleTitle.textContent =
                "CLASS SCHEDULE (" +
                schedules.length +
                " SUBJECT" +
                (
                    schedules.length === 1
                        ? ""
                        : "S"
                ) +
                ")";

        }


        /* -----------------------------------------------------
           Create rows
        ----------------------------------------------------- */

        schedules.forEach(
            function(item) {

                const row =
                    document.createElement(
                        "tr"
                    );


                const day =
                    normalizeDay(
                        item.day
                    );


                row.innerHTML = `

                    <td class="course-code-text">
                        ${escapeHtml(
                            item.course_code || ""
                        )}
                    </td>


                    <td class="subject-text">
                        ${escapeHtml(
                            item.subject || ""
                        )}
                    </td>


                    <td>
                        ${escapeHtml(
                            item.subject_type || ""
                        )}
                    </td>


                    <td>
                        ${escapeHtml(
                            item.instructor || ""
                        )}
                    </td>


                    <td class="major-text">
                        ${escapeHtml(
                            item.major || ""
                        )}
                    </td>


                    <td>
                        ${escapeHtml(
                            item.room || ""
                        )}
                    </td>


                    <td>
                        ${escapeHtml(
                            item.time || ""
                        )}
                    </td>


                    <td class="${
                        day === "MON"
                            ? "day-active"
                            : ""
                    }">
                        ${
                            day === "MON"
                                ? "●"
                                : ""
                        }
                    </td>


                    <td class="${
                        day === "TUE"
                            ? "day-active"
                            : ""
                    }">
                        ${
                            day === "TUE"
                                ? "●"
                                : ""
                        }
                    </td>


                    <td class="${
                        day === "WED"
                            ? "day-active"
                            : ""
                    }">
                        ${
                            day === "WED"
                                ? "●"
                                : ""
                        }
                    </td>


                    <td class="${
                        day === "THU"
                            ? "day-active"
                            : ""
                    }">
                        ${
                            day === "THU"
                                ? "●"
                                : ""
                        }
                    </td>


                    <td class="${
                        day === "FRI"
                            ? "day-active"
                            : ""
                    }">
                        ${
                            day === "FRI"
                                ? "●"
                                : ""
                        }
                    </td>


                    <td>
                        ${escapeHtml(
                            item.description || ""
                        )}
                    </td>

                `;


                body.appendChild(row);

            }
        );

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
                    "No department found.";


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


    /* =========================================================
       PRINT
    ========================================================= */

    function printSchedule()
    {

        if (
            !selectedDepartmentId ||
            !selectedProgram ||
            !selectedYearLevel
        ) {

            return;

        }


        window.print();

    }

</script>

</body>

</html>
