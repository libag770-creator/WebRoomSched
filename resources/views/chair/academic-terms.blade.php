@include('footerheader.header')

<div class="wrapper">

    <div class="main-layout">

        {{-- =========================================================
             CHAIR SIDEBAR
        ========================================================== --}}

        @include('chair.sidebar')


        {{-- =========================================================
             MAIN CONTENT
        ========================================================== --}}

        <main class="content chair-workspace-redesign chair-library-redesign">

            <style>
                * {
                    box-sizing: border-box;
                }

                body {
                    background: #f3f6fb;
                    margin: 0;
                }

                .academic-library-page {
                    width: 100%;
                    max-width: 1500px;
                    margin: 0 auto;
                    padding: 18px 24px 45px;
                }


                /* =====================================================
                   PAGE HEADER
                ===================================================== */

                .academic-library-header {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 20px;
                    margin-bottom: 20px;
                }

                .academic-library-title {
                    margin: 0;
                    font-size: 28px;
                    line-height: 1.15;
                    font-weight: 800;
                    color: #183b20;
                }

                .academic-library-subtitle {
                    margin: 6px 0 0;
                    color: #718078;
                    font-size: 13px;
                }

                .active-term-badge {
                    flex: 0 0 auto;
                    background: #eaf6ec;
                    border: 1px solid #b9dabf;
                    color: #1d6c2a;
                    border-radius: 10px;
                    padding: 10px 15px;
                    font-size: 13px;
                    font-weight: 700;
                    white-space: nowrap;
                }


                /* =====================================================
                   ALERTS
                ===================================================== */

                .library-message {
                    width: 100%;
                    padding: 11px 14px;
                    margin-bottom: 15px;
                    border-radius: 9px;
                    font-size: 12px;
                    line-height: 1.4;
                }

                .library-message.success {
                    background: #eaf7ec;
                    border: 1px solid #c4e3c9;
                    color: #216b2d;
                }

                .library-message.error {
                    background: #fff0f0;
                    border: 1px solid #efc9c9;
                    color: #9c3030;
                }


                /* =====================================================
                   MAIN TWO COLUMN LAYOUT
                ===================================================== */

                .academic-library-grid {
                    display: grid;
                    grid-template-columns: 285px minmax(0, 1fr);
                    gap: 16px;
                    align-items: start;
                }


                /* =====================================================
                   LEFT SIDEBAR PANEL
                ===================================================== */

                .library-sidebar {
                    background: #ffffff;
                    border: 1px solid #dce7df;
                    border-radius: 15px;
                    box-shadow: 0 4px 15px rgba(30, 70, 35, .06);
                    overflow: hidden;
                }

                .sidebar-block {
                    padding: 15px;
                    border-bottom: 1px solid #edf1ee;
                }

                .sidebar-block:last-child {
                    border-bottom: 0;
                }

                .sidebar-heading {
                    margin: 0 0 12px;
                    color: #183b20;
                    font-size: 15px;
                    font-weight: 800;
                }


                /* =====================================================
                   CREATE TERM FORM
                ===================================================== */

                .term-create-form {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }

                .term-create-fields {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 8px;
                }

                .term-field {
                    min-width: 0;
                }

                .term-field label {
                    display: block;
                    margin-bottom: 5px;
                    font-size: 10px;
                    font-weight: 800;
                    color: #5d6861;
                    text-transform: uppercase;
                    letter-spacing: .03em;
                }

                .term-field input,
                .term-field select {
                    width: 100%;
                    min-height: 37px;
                    padding: 8px 9px;
                    border: 1px solid #cbd6cf;
                    border-radius: 8px;
                    outline: none;
                    background: #ffffff;
                    color: #1f2937;
                    font-size: 12px;
                }

                .term-field input:focus,
                .term-field select:focus {
                    border-color: #2e7d32;
                    box-shadow: 0 0 0 3px rgba(46, 125, 50, .08);
                }

                .active-checkbox {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    color: #59645d;
                    font-size: 12px;
                }

                .active-checkbox input {
                    width: 14px;
                    height: 14px;
                    accent-color: #2e7d32;
                }


                /* =====================================================
                   BUTTONS
                ===================================================== */

                .library-btn {
                    min-height: 35px;
                    border-radius: 8px;
                    padding: 8px 11px;
                    border: 0;
                    cursor: pointer;
                    font-size: 11px;
                    font-weight: 800;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    transition: .15s ease;
                    white-space: nowrap;
                }

                .library-btn-green {
                    background: #2e7d32;
                    color: #ffffff;
                }

                .library-btn-green:hover {
                    background: #256a2a;
                    color: #ffffff;
                }

                .library-btn-soft {
                    background: #edf7ef;
                    border: 1px solid #c9e2cd;
                    color: #267232;
                }

                .library-btn-soft:hover {
                    background: #e2f2e5;
                    color: #21672a;
                }

                .library-btn-outline {
                    background: #ffffff;
                    border: 1px solid #b9d7bd;
                    color: #277333;
                }

                .library-btn-outline:hover {
                    background: #f2faf3;
                }

                .library-btn-gray {
                    background: #f4f6f5;
                    border: 1px solid #e0e4e1;
                    color: #6a746e;
                    cursor: default;
                }

                .full-width {
                    width: 100%;
                }


                /* =====================================================
                   SAVED TERMS
                ===================================================== */

                .term-list {
                    display: flex;
                    flex-direction: column;
                    gap: 9px;
                    max-height: 680px;
                    overflow-y: auto;
                    padding-right: 2px;
                }

                .term-item {
                    border: 1px solid #dfe8e2;
                    background: #fbfdfb;
                    border-radius: 11px;
                    padding: 10px;
                }

                .term-item.active-term {
                    background: #f3fbf4;
                    border-color: #8dc897;
                }

                .term-item-header {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 8px;
                }

                .term-name {
                    color: #183b20;
                    font-size: 13px;
                    font-weight: 800;
                    line-height: 1.35;
                }

                .term-name .term-dot {
                    color: #7a877f;
                    padding: 0 3px;
                }

                .term-count {
                    margin-top: 4px;
                    color: #7b8580;
                    font-size: 10px;
                }

                .term-status {
                    flex-shrink: 0;
                    padding: 4px 7px;
                    border-radius: 999px;
                    font-size: 8px;
                    font-weight: 900;
                    text-transform: uppercase;
                }

                .term-status.active {
                    background: #dff2e2;
                    color: #22702c;
                }

                .term-status.saved {
                    background: #eef1ef;
                    color: #69746e;
                }

                .term-actions {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 6px;
                    margin-top: 9px;
                }

                .term-actions form {
                    width: 100%;
                    margin: 0;
                }

                .term-actions .library-btn {
                    width: 100%;
                }


                /* =====================================================
                   REUSE PANEL
                ===================================================== */

                .reuse-panel {
                    margin-top: 9px;
                    padding: 10px;
                    background: #fff9e9;
                    border: 1px solid #efdda7;
                    border-radius: 10px;
                }

                .reuse-title {
                    margin: 0 0 8px;
                    color: #705200;
                    font-size: 11px;
                    font-weight: 900;
                }

                .reuse-form {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 7px;
                }

                .reuse-form .term-field input,
                .reuse-form .term-field select {
                    min-height: 34px;
                    font-size: 11px;
                }

                .reuse-full {
                    grid-column: 1 / -1;
                }

                .reuse-note {
                    margin-top: 7px;
                    color: #8b7444;
                    font-size: 9px;
                    line-height: 1.45;
                }


                /* =====================================================
                   RIGHT PREVIEW PANEL
                ===================================================== */

                .library-preview {
                    min-width: 0;
                    min-height: 640px;
                    background: #ffffff;
                    border: 1px solid #dce7df;
                    border-radius: 15px;
                    box-shadow: 0 4px 15px rgba(30, 70, 35, .06);
                    overflow: hidden;
                }

                .preview-header {
                    padding: 16px 18px 14px;
                    background: linear-gradient(
                        to bottom,
                        #ffffff,
                        #fbfdfb
                    );
                    border-bottom: 1px solid #e7ede9;
                }

                .preview-header-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 15px;
                }

                .preview-title {
                    margin: 0;
                    color: #183b20;
                    font-size: 17px;
                    font-weight: 800;
                }

                .preview-description {
                    margin: 4px 0 0;
                    color: #7d8780;
                    font-size: 10px;
                }

                .preview-count {
                    flex-shrink: 0;
                    padding: 8px 10px;
                    border-radius: 8px;
                    background: #eef7ef;
                    border: 1px solid #cbe2ce;
                    color: #287232;
                    font-size: 10px;
                    font-weight: 800;
                    white-space: nowrap;
                }

                .preview-body {
                    padding: 14px;
                }


                /* =====================================================
                   NO SELECTION
                ===================================================== */

                .empty-preview {
                    min-height: 500px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    padding: 30px;
                }

                .empty-preview-box {
                    max-width: 430px;
                    color: #78837d;
                    font-size: 12px;
                    line-height: 1.6;
                }

                .empty-preview-icon {
                    width: 55px;
                    height: 55px;
                    margin: 0 auto 11px;
                    border-radius: 50%;
                    background: #edf7ef;
                    color: #2e7d32;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    font-weight: 800;
                }

                .empty-preview-title {
                    margin-bottom: 5px;
                    color: #31513a;
                    font-size: 16px;
                    font-weight: 800;
                }


                /* =====================================================
                   SCHEDULE META
                ===================================================== */

                .schedule-meta {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 10px;
                    margin-bottom: 10px;
                    color: #7a847f;
                    font-size: 10px;
                }

                .schedule-meta strong {
                    color: #31733a;
                }


                /* =====================================================
                   TABLE
                ===================================================== */

                .schedule-table-wrapper {
                    width: 100%;
                    overflow-x: auto;
                    border: 1px solid #dce5df;
                    border-radius: 10px;
                }

                .schedule-table {
                    width: 100%;
                    min-width: 880px;
                    border-collapse: collapse;
                    table-layout: fixed;
                }

                .schedule-table th,
                .schedule-table td {
                    padding: 8px 9px;
                    border-bottom: 1px solid #e5ebe7;
                    text-align: left;
                    vertical-align: middle;
                    font-size: 10px;
                    white-space: nowrap;
                }

                .schedule-table th {
                    background: #f1f7f2;
                    color: #47544c;
                    font-size: 9px;
                    font-weight: 900;
                    text-transform: uppercase;
                    letter-spacing: .03em;
                }

                .schedule-table tr:last-child td {
                    border-bottom: 0;
                }

                .schedule-table tbody tr:hover {
                    background: #f9fcfa;
                }

                .schedule-course {
                    color: #23712d;
                    font-weight: 900;
                }

                .schedule-subject {
                    color: #344054;
                }

                .no-schedule-records {
                    color: #6e7973;
                    font-size: 11px;
                }


                /* =====================================================
                   RESPONSIVE
                ===================================================== */

                @media (max-width: 1050px) {

                    .academic-library-grid {
                        grid-template-columns: 250px minmax(0, 1fr);
                    }

                    .academic-library-page {
                        padding-left: 18px;
                        padding-right: 18px;
                    }

                }


                @media (max-width: 900px) {

                    .academic-library-grid {
                        grid-template-columns: 1fr;
                    }

                    .term-list {
                        max-height: none;
                    }

                    .library-preview {
                        min-height: 500px;
                    }

                }


                @media (max-width: 650px) {

                    .academic-library-page {
                        padding: 12px;
                    }

                    .academic-library-header {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .active-term-badge {
                        width: 100%;
                        text-align: center;
                        white-space: normal;
                    }

                    .preview-header-row {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .preview-count {
                        text-align: center;
                    }

                    .term-create-fields {
                        grid-template-columns: 1fr;
                    }

                    .reuse-form {
                        grid-template-columns: 1fr;
                    }

                    .reuse-full {
                        grid-column: auto;
                    }

                    .term-actions {
                        grid-template-columns: 1fr;
                    }

                }

                /* White and blue neumorphic theme, matched to the chair dashboard. */
                body { background:#f3f6fb !important; color:#1e2d43; }
                main.content:has(.academic-library-page) { background:#f3f6fb !important; }
                .academic-library-page {
                    --library-ink:#1e2d43;
                    --library-muted:#52647b;
                    --library-blue:#315f9b;
                    --library-line:#dbe4ef;
                    color:var(--library-ink) !important;
                    font-size:16px;
                    line-height:1.55;
                    padding:24px clamp(16px,2.4vw,32px) 42px;
                }
                .academic-library-hero {
                    position:relative;
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                    gap:26px;
                    min-height:218px;
                    margin:0 0 22px;
                    padding:clamp(24px,3vw,36px);
                    overflow:hidden;
                    border:1px solid var(--library-line);
                    border-radius:22px;
                    background:linear-gradient(145deg,#fff,#f0f4fa) !important;
                    box-shadow:10px 10px 22px #dce3ec,-8px -8px 20px #fff,inset 1px 1px 0 #fff !important;
                }
                .academic-library-hero-copy { position:relative; z-index:1; max-width:760px; }
                .academic-library-eyebrow { display:inline-flex; align-items:center; gap:9px; color:#355d8f !important; font-size:13px; font-weight:800; letter-spacing:.09em; }
                .academic-library-eyebrow i { color:var(--library-blue); }
                .academic-library-title,.academic-library-hero .academic-library-title {
                    margin:12px 0 9px;
                    color:#1d2b40 !important;
                    font-size:clamp(30px,3vw,40px) !important;
                    line-height:1.2;
                    letter-spacing:-.025em;
                }
                .academic-library-subtitle,.academic-library-hero .academic-library-subtitle { margin:0; color:#52647b !important; font-size:17px !important; line-height:1.6; }
                .active-term-badge {
                    position:relative;
                    z-index:1;
                    display:grid;
                    gap:5px;
                    min-width:220px;
                    padding:15px 17px;
                    white-space:normal;
                    border:1px solid #d2deed;
                    border-radius:14px;
                    background:#edf2f8 !important;
                    color:#294b75 !important;
                    box-shadow:inset 4px 4px 9px #dfe5ed,inset -4px -4px 9px #fff !important;
                }
                .active-term-badge > span { color:#526b89 !important; font-size:12px; font-weight:800; letter-spacing:.09em; }
                .active-term-badge strong { color:#244b7c !important; font-size:16px; line-height:1.45; }
                .academic-library-hero-mark { right:260px; color:rgba(49,95,155,.055) !important; }
                .library-message { padding:14px 17px; margin-bottom:16px; border-radius:12px; font-size:15px; line-height:1.55; }
                .library-message.success { border-color:#b9dfc7; background:#eaf6ee !important; color:#205d3d !important; }
                .library-message.error { border-color:#e8c5c4; background:#fff0ef !important; color:#8f302e !important; }
                .academic-library-grid { grid-template-columns:minmax(315px,.82fr) minmax(0,1.55fr); gap:20px; align-items:stretch; }
                .library-sidebar,.library-preview {
                    min-width:0;
                    border:1px solid var(--library-line) !important;
                    border-radius:18px;
                    background:linear-gradient(145deg,#fff,#f0f4fa) !important;
                    box-shadow:8px 8px 18px #dfe5ed,-7px -7px 18px #fff,inset 1px 1px 0 rgba(255,255,255,.9) !important;
                }
                .library-sidebar { overflow:hidden; }
                .sidebar-block { padding:20px; border-color:#dce4ef !important; }
                .sidebar-heading { margin-bottom:16px; color:#1e3049 !important; font-size:20px; line-height:1.35; }
                .term-create-form { gap:15px; }
                .term-create-fields { gap:12px; }
                .term-field { min-width:0; }
                .term-field label { margin-bottom:7px; color:#354c69 !important; font-size:15px; font-weight:700; letter-spacing:0; line-height:1.4; }
                .term-field input,.term-field select {
                    width:100%;
                    min-height:48px !important;
                    padding:10px 13px !important;
                    border:1px solid #cbd8e8 !important;
                    border-radius:10px !important;
                    background:#f0f4fa !important;
                    color:#243850 !important;
                    font-size:16px !important;
                    box-shadow:inset 4px 4px 8px #e0e6ef,inset -4px -4px 8px #fff !important;
                }
                .term-field input::placeholder { color:#657a95 !important; opacity:1; }
                .term-field input:focus,.term-field select:focus { outline:3px solid rgba(49,95,155,.17); border-color:#7293bd !important; }
                .active-checkbox { gap:10px; color:#354c69 !important; font-size:15px; line-height:1.5; }
                .active-checkbox input { width:19px; height:19px; accent-color:#315f9b; }
                .library-btn {
                    min-height:44px;
                    padding:9px 12px;
                    border-radius:10px;
                    font-size:14px;
                    line-height:1.35;
                    box-shadow:5px 5px 11px #dce3ec,-4px -4px 10px #fff,inset 1px 1px 0 rgba(255,255,255,.4) !important;
                    transition:transform .18s ease,box-shadow .18s ease,background .18s ease;
                }
                .library-btn:hover { transform:translateY(-1px); }
                .library-btn:focus-visible { outline:3px solid rgba(49,95,155,.28); outline-offset:2px; }
                .library-btn-green { border:1px solid #315f9b !important; background:#315f9b !important; color:#fff !important; }
                .library-btn-green:hover { background:#284f83 !important; color:#fff !important; }
                .library-btn-soft,.library-btn-outline { border:1px solid #cbd8e8 !important; background:#edf2f8 !important; color:#294b75 !important; }
                .library-btn-soft:hover,.library-btn-outline:hover { background:#e3ebf5 !important; color:#1f477a !important; }
                .library-btn-gray { border:1px solid #d7dfea !important; background:#e8edf4 !important; color:#52647b !important; box-shadow:inset 3px 3px 7px #dce3ec,inset -3px -3px 7px #fff !important; }
                .library-btn-danger { border:1px solid #e3c4c2 !important; background:#fff0ef !important; color:#8f302e !important; }
                .library-btn-danger:hover { background:#fbe3e1 !important; color:#762624 !important; }
                .term-list { gap:12px; max-height:760px; padding:2px 5px 2px 2px; }
                .term-item { padding:15px; border:1px solid #dbe4ef !important; border-radius:14px; background:linear-gradient(145deg,#fff,#f2f5fa) !important; box-shadow:5px 5px 12px #e0e6ee,-5px -5px 12px #fff,inset 1px 1px 0 #fff !important; }
                .term-item.active-term { border-color:#b8cbe3 !important; background:#edf3fb !important; box-shadow:inset 4px 4px 9px #e0e6ef,inset -4px -4px 9px #fff !important; }
                .term-item-header { align-items:flex-start; gap:12px; }
                .term-name { color:#203650 !important; font-size:16px; line-height:1.45; overflow-wrap:anywhere; }
                .term-name .term-dot { color:#647791 !important; }
                .term-count { color:#52647b !important; font-size:14px; line-height:1.45; }
                .term-status { padding:6px 9px; font-size:12px; }
                .term-status.active { background:#e4f2e9 !important; color:#205d3d !important; }
                .term-status.saved { background:#e8edf4 !important; color:#40536d !important; }
                .term-actions { grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; margin-top:13px; }
                .term-actions .library-btn { white-space:normal; }
                .reuse-panel { margin-top:13px; padding:15px; border:1px solid #d7e0eb !important; border-radius:12px; background:#edf2f8 !important; box-shadow:inset 4px 4px 9px #e0e6ef,inset -4px -4px 9px #fff !important; }
                .reuse-title { margin-bottom:10px; color:#294b75 !important; font-size:16px; }
                .reuse-form { gap:10px; }
                .reuse-form .term-field input,.reuse-form .term-field select { min-height:46px; font-size:15px !important; }
                .reuse-note { color:#52647b !important; font-size:14px; line-height:1.55; }
                .library-preview { min-height:640px; }
                .preview-header { padding:21px 23px; border-color:#dce4ef !important; background:linear-gradient(145deg,#fff,#f0f4fa) !important; }
                .preview-title { color:#1e3049 !important; font-size:22px; line-height:1.35; }
                .preview-description { color:#52647b !important; font-size:15px; line-height:1.5; }
                .preview-count { padding:9px 12px; border:1px solid #d2deed !important; border-radius:10px; background:#eaf0f8 !important; color:#294b75 !important; font-size:14px; }
                .preview-body { padding:19px; }
                .empty-preview-box { color:#52647b !important; font-size:16px; line-height:1.7; }
                .empty-preview-icon { width:64px; height:64px; border:1px solid #d6e1ef; background:#e8eef7 !important; color:#315f9b !important; box-shadow:inset 4px 4px 9px #dce4ef,inset -4px -4px 9px #fff !important; }
                .empty-preview-title { color:#203650 !important; font-size:20px; }
                .schedule-meta { margin-bottom:14px; color:#52647b !important; font-size:14px; line-height:1.5; }
                .schedule-meta strong { color:#294b75 !important; }
                .schedule-table-wrapper { border-color:#d5dfeb !important; border-radius:12px; background:#f5f7fb; box-shadow:5px 5px 12px #e0e6ee,-4px -4px 10px #fff; }
                .schedule-table { min-width:980px; }
                .schedule-table th,.schedule-table td { padding:12px 13px; border-color:#dfe6ef !important; color:#283c55 !important; font-size:14px !important; line-height:1.45; }
                .schedule-table th { background:#e8eef7 !important; color:#294b75 !important; font-size:13px !important; }
                .schedule-table tbody tr:nth-child(even) { background:#f8faff; }
                .schedule-table tbody tr:hover { background:#edf3fb !important; }
                .schedule-course { color:#244f88 !important; }
                .schedule-subject { color:#283c55 !important; }
                .no-schedule-records { color:#52647b !important; font-size:15px; }
                @media (max-width:1000px) {
                    .academic-library-grid { grid-template-columns:minmax(0,1fr); }
                    .term-list { max-height:none; }
                    .library-preview { min-height:520px; }
                }
                @media (max-width:700px) {
                    .academic-library-page { padding:16px 13px 30px; }
                    .academic-library-hero { align-items:flex-start; flex-direction:column; min-height:0; padding:23px; }
                    .academic-library-hero-copy { padding-right:58px; }
                    .academic-library-hero .active-term-badge { width:100%; min-width:0; }
                    .academic-library-hero-mark { top:20px; right:18px; transform:none; font-size:72px; }
                    .term-create-fields,.reuse-form { grid-template-columns:1fr; }
                    .reuse-full { grid-column:auto; }
                    .preview-header-row { align-items:flex-start; flex-direction:column; }
                    .preview-count { white-space:normal; }
                }
                @media (max-width:480px) {
                    .academic-library-title,.academic-library-hero .academic-library-title { font-size:28px !important; }
                    .academic-library-subtitle,.academic-library-hero .academic-library-subtitle { font-size:16px !important; }
                    .academic-library-hero-copy { padding-right:0; }
                    .academic-library-hero-mark { opacity:.45; }
                    .sidebar-block { padding:16px; }
                    .term-actions { grid-template-columns:1fr; }
                    .term-item-header { flex-direction:column; }
                    .preview-body { padding:12px; }
                    .schedule-meta { align-items:flex-start; flex-direction:column; }
                }

            </style>
            <link rel="stylesheet" href="{{ asset('css/chair-workspace.css') }}">



            {{-- =========================================================
                 PAGE
            ========================================================== --}}

            <div class="academic-library-page">


                {{-- =====================================================
                     PAGE HEADER
                ====================================================== --}}

                <section class="academic-library-hero">
                    <div class="academic-library-hero-copy">
                        <span class="academic-library-eyebrow"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i> DEPARTMENT WORKSPACE</span>
                        <h1 class="academic-library-title">Academic Terms &amp; Schedule Library</h1>
                        <p class="academic-library-subtitle">Create terms, review saved schedules, and choose the active semester.</p>
                    </div>
                    <div class="active-term-badge">
                        <span>ACTIVE TERM</span>
                        <strong>{{ $activeTerm->academic_year }} <span aria-hidden="true">•</span> {{ $activeTerm->semester }}</strong>
                    </div>
                    <i class="academic-library-hero-mark fa-solid fa-calendar-days" aria-hidden="true"></i>
                </section>


                {{-- =====================================================
                     SUCCESS MESSAGE
                ====================================================== --}}

                @if(session('success'))

                    <div class="library-message success">

                        {{ session('success') }}

                    </div>

                @endif


                {{-- =====================================================
                     ERROR MESSAGE
                ====================================================== --}}

                @if(session('error'))

                    <div class="library-message error">

                        {{ session('error') }}

                    </div>

                @endif


                {{-- =====================================================
                     VALIDATION ERRORS
                ====================================================== --}}

                @if($errors->any())

                    <div class="library-message error">

                        {{ $errors->first() }}

                    </div>

                @endif


                {{-- =====================================================
                     TWO COLUMN AREA
                ====================================================== --}}

                <div class="academic-library-grid">


                    {{-- =================================================
                         LEFT
                    ================================================== --}}

                    <aside class="library-sidebar">


                        {{-- =================================================
                             CREATE TERM
                        ================================================== --}}

                        <div class="sidebar-block">

                            <h2 class="sidebar-heading">
                                Create Academic Term
                            </h2>


                            <form
                                method="POST"
                                action="{{ route('chair.academic.terms.store') }}"
                                class="term-create-form"
                            >

                                @csrf


                                <div class="term-create-fields">


                                    {{-- ACADEMIC YEAR --}}

                                    <div class="term-field">

                                        <label for="academic_year">
                                            Academic Year
                                        </label>

                                        <input
                                            id="academic_year"
                                            type="text"
                                            name="academic_year"
                                            placeholder="2027-2028"
                                            value="{{ old('academic_year') }}"
                                            required
                                        >

                                    </div>


                                    {{-- SEMESTER --}}

                                    <div class="term-field">

                                        <label for="semester">
                                            Semester
                                        </label>

                                        <select
                                            id="semester"
                                            name="semester"
                                            required
                                        >

                                            <option
                                                value="1st Semester"
                                                {{ old('semester', '1st Semester') === '1st Semester' ? 'selected' : '' }}
                                            >
                                                1st Semester
                                            </option>

                                            <option
                                                value="2nd Semester"
                                                {{ old('semester') === '2nd Semester' ? 'selected' : '' }}
                                            >
                                                2nd Semester
                                            </option>

                                        </select>

                                    </div>

                                </div>


                                {{-- MAKE ACTIVE --}}

                                <label class="active-checkbox">

                                    <input
                                        type="checkbox"
                                        name="make_active"
                                        value="1"
                                    >

                                    <span>
                                        Make this the active term
                                    </span>

                                </label>


                                {{-- CREATE --}}

                                <button
                                    type="submit"
                                    class="library-btn library-btn-green full-width"
                                >
                                    + Create Term
                                </button>

                            </form>

                        </div>


                        {{-- =================================================
                             SAVED TERMS
                        ================================================== --}}

                        <div class="sidebar-block">

                            <h2 class="sidebar-heading">
                                Saved Academic Terms
                            </h2>


                            <div class="term-list">


                                @forelse($terms as $term)


                                    <div
                                        class="
                                            term-item
                                            {{ (int) $activeTerm->id === (int) $term->id
                                                ? 'active-term'
                                                : ''
                                            }}
                                        "
                                    >


                                        {{-- =================================
                                             TERM HEADER
                                        ================================== --}}

                                        <div class="term-item-header">

                                            <div>

                                                <div class="term-name">

                                                    {{ $term->academic_year }}

                                                    <span class="term-dot">
                                                        •
                                                    </span>

                                                    {{ $term->semester }}

                                                </div>


                                                <div class="term-count">

                                                    {{ (int) $term->schedule_count }}

                                                    schedule record(s)

                                                </div>

                                            </div>


                                            {{-- STATUS --}}

                                            <span
                                                class="
                                                    term-status
                                                    {{ $term->is_active
                                                        ? 'active'
                                                        : 'saved'
                                                    }}
                                                "
                                            >

                                                {{ $term->is_active
                                                    ? 'Active'
                                                    : 'Saved'
                                                }}

                                            </span>

                                        </div>


                                        {{-- =================================
                                             VIEW / ACTIVE BUTTONS
                                        ================================== --}}

                                        <div class="term-actions">


                                            {{-- VIEW --}}

                                            <a
                                                href="{{ route(
                                                    'chair.schedule.library',
                                                    ['view' => $term->id]
                                                ) }}"
                                                class="
                                                    library-btn
                                                    library-btn-soft
                                                "
                                            >

                                                ◉ View

                                            </a>


                                            {{-- SET ACTIVE --}}

                                            @if(
                                                (int) $activeTerm->id !==
                                                (int) $term->id
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'chair.academic.terms.activate',
                                                        $term->id
                                                    ) }}"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="
                                                            library-btn
                                                            library-btn-green
                                                        "
                                                    >

                                                        ✓ Set Active

                                                    </button>

                                                </form>

                                            @else

                                                <div
                                                    class="
                                                        library-btn
                                                        library-btn-gray
                                                    "
                                                >

                                                    ✓ Active

                                                </div>

                                            @endif

                                            @if((int) $activeTerm->id !== (int) $term->id)
                                                <form method="POST"
                                                      action="{{ route('chair.academic.terms.delete', $term->id) }}"
                                                      onsubmit="return confirm('Delete this academic term? Terms with schedules or drafts cannot be deleted.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="library-btn library-btn-danger">
                                                        <i class="fa-solid fa-trash" aria-hidden="true"></i> Delete
                                                    </button>
                                                </form>
                                            @endif

                                        </div>


                                        {{-- =================================
                                             REUSE
                                        ================================== --}}

                                        <div class="reuse-panel">

                                            <div class="reuse-title">

                                                Reuse this schedule

                                            </div>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'chair.schedule.library.reuse',
                                                    $term->id
                                                ) }}"
                                                class="reuse-form"
                                            >

                                                @csrf


                                                {{-- TARGET YEAR --}}

                                                <div class="term-field">

                                                    <label>
                                                        New Academic Year
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="target_academic_year"
                                                        placeholder="2027-2028"
                                                        required
                                                    >

                                                </div>


                                                {{-- TARGET SEMESTER --}}

                                                <div class="term-field">

                                                    <label>
                                                        Target Semester
                                                    </label>

                                                    <select
                                                        name="target_semester"
                                                        required
                                                    >

                                                        <option value="1st Semester">
                                                            1st Semester
                                                        </option>

                                                        <option value="2nd Semester">
                                                            2nd Semester
                                                        </option>

                                                    </select>

                                                </div>


                                                {{-- COPY BUTTON --}}

                                                <button
                                                    type="submit"
                                                    class="
                                                        library-btn
                                                        library-btn-outline
                                                        reuse-full
                                                    "
                                                >

                                                    Copy as New Schedule

                                                </button>

                                            </form>


                                            <div class="reuse-note">

                                                The source schedule stays unchanged.
                                                The copied schedule becomes a new
                                                editable academic term.

                                            </div>

                                        </div>


                                    </div>


                                @empty


                                    <div
                                        style="
                                            padding: 6px 0;
                                            color: #7b8580;
                                            font-size: 11px;
                                        "
                                    >

                                        No academic terms have been created yet.

                                    </div>


                                @endforelse


                            </div>

                        </div>

                    </aside>


                    {{-- =================================================
                         RIGHT
                    ================================================== --}}

                    <section class="library-preview">


                        {{-- =================================================
                             PREVIEW HEADER
                        ================================================== --}}

                        <div class="preview-header">

                            <div class="preview-header-row">


                                <div>

                                    @if($selectedTerm)

                                        <h2 class="preview-title">

                                            {{ $selectedTerm->academic_year }}

                                            •

                                            {{ $selectedTerm->semester }}

                                        </h2>

                                        <p class="preview-description">

                                            @if($selectedTerm->is_active)

                                                Active schedule

                                            @else

                                                Historical schedule

                                            @endif

                                            — view only.

                                        </p>

                                    @else

                                        <h2 class="preview-title">

                                            Schedule Preview

                                        </h2>

                                        <p class="preview-description">

                                            Select a saved academic term and click View.

                                        </p>

                                    @endif

                                </div>


                                {{-- COUNT --}}

                                @if($selectedTerm)

                                    <div class="preview-count">

                                        {{ (int) $selectedSchedules->count() }}

                                        schedule record(s)

                                    </div>

                                @endif


                            </div>

                        </div>


                        {{-- =================================================
                             PREVIEW BODY
                        ================================================== --}}

                        <div class="preview-body">


                            {{-- =================================================
                                 NO TERM SELECTED
                            ================================================== --}}

                            @if(!$selectedTerm)


                                <div class="empty-preview">

                                    <div class="empty-preview-box">

                                        <div class="empty-preview-icon">
                                            ▦
                                        </div>

                                        <div class="empty-preview-title">

                                            No term selected

                                        </div>

                                        <div>

                                            Click
                                            <strong>View</strong>
                                            beside an academic term to open
                                            its schedule.

                                        </div>

                                    </div>

                                </div>


                            {{-- =================================================
                                 EMPTY TERM
                            ================================================== --}}

                            @elseif($selectedSchedules->isEmpty())


                                <div class="empty-preview">

                                    <div class="empty-preview-box">

                                        <div class="empty-preview-icon">
                                            ✓
                                        </div>

                                        <div class="empty-preview-title">

                                            Empty Schedule Sheet

                                        </div>

                                        <div>

                                            This academic term has no schedules yet.

                                            It is ready for a new schedule.

                                            Old schedules from another academic
                                            term are not displayed here.

                                        </div>

                                    </div>

                                </div>


                            {{-- =================================================
                                 SCHEDULE FOUND
                            ================================================== --}}

                            @else


                                <div class="schedule-meta">

                                    <div>

                                        Showing schedules for:

                                        <strong>
                                            {{ $selectedTerm->academic_year }}
                                            •
                                            {{ $selectedTerm->semester }}
                                        </strong>

                                    </div>

                                    <div>

                                        Term ID:
                                        <strong>
                                            {{ $selectedTerm->id }}
                                        </strong>

                                    </div>

                                </div>


                                <div class="schedule-table-wrapper">

                                    <table class="schedule-table">

                                        <thead>

                                            <tr>

                                                <th>ROOM</th>

                                                <th>BUILDING</th>

                                                <th>DAY</th>

                                                <th>TIME</th>

                                                <th>COURSE</th>

                                                <th>SUBJECT</th>

                                                <th>INSTRUCTOR</th>

                                                <th>PROGRAM</th>

                                            </tr>

                                        </thead>


                                        <tbody>


                                            @foreach(
                                                $selectedSchedules
                                                ->sortBy(function ($schedule) {
                                                    return [
                                                        (int) ($schedule->room_id ?? 0),
                                                        (int) ($schedule->row_position ?? 99999),
                                                        strtoupper((string) ($schedule->day ?? '')),
                                                        (string) ($schedule->time ?? '')
                                                    ];
                                                })
                                                as $schedule
                                            )


                                                <tr>


                                                    {{-- ROOM --}}

                                                    <td>

                                                        {{
                                                            $schedule->room->room_name
                                                            ?? $schedule->room->name
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- BUILDING --}}

                                                    <td>

                                                        {{
                                                            $schedule->room->building->name
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- DAY --}}

                                                    <td>

                                                        {{
                                                            $schedule->day
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- TIME --}}

                                                    <td>

                                                        {{
                                                            $schedule->time
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- COURSE --}}

                                                    <td class="schedule-course">

                                                        {{
                                                            $schedule->course_code
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- SUBJECT --}}

                                                    <td class="schedule-subject">

                                                        {{
                                                            $schedule->subject
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- INSTRUCTOR --}}

                                                    <td>

                                                        {{
                                                            $schedule->faculty->name
                                                            ?? $schedule->instructor
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                    {{-- PROGRAM --}}

                                                    <td>

                                                        {{
                                                            $schedule->program
                                                            ?? '-'
                                                        }}

                                                    </td>


                                                </tr>


                                            @endforeach


                                        </tbody>

                                    </table>

                                </div>


                            @endif


                        </div>

                    </section>

                </div>

            </div>


        </main>

    </div>



    @include('footerheader.footer')

</div>
