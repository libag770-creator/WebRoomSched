<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>

                /* =========================================

                   PAGE

                ========================================= */

                .department-page {

                    width: 100%;

                }

                .title {

                    font-size: 26px;

                    font-weight: 700;

                    color: #2e7d32;

                    margin-bottom: 5px;

                }

                .subtitle {

                    color: #777;

                    font-size: 14px;

                    margin-bottom: 25px;

                }

                /* =========================================

                   MESSAGES

                ========================================= */

                .success-message {

                    background: #e8f5e9;

                    color: #2e7d32;

                    border-left: 5px solid #2e7d32;

                    padding: 13px 16px;

                    border-radius: 7px;

                    margin-bottom: 20px;

                }

                .error-message {

                    background: #ffebee;

                    color: #b71c1c;

                    border-left: 5px solid #c62828;

                    padding: 13px 16px;

                    border-radius: 7px;

                    margin-bottom: 20px;

                }

                .error-message ul {

                    margin-bottom: 0;

                }

                /* =========================================

                   ADD DEPARTMENT

                ========================================= */

                .add-card {

                    background: white;

                    padding: 22px;

                    border-radius: 12px;

                    margin-bottom: 25px;

                    box-shadow:

                        0 3px 12px rgba(0,0,0,.08);

                    border-top: 4px solid #2e7d32;

                }

                .add-card h3 {

                    margin-top: 0;

                    margin-bottom: 18px;

                    color: #2e7d32;

                    font-size: 19px;

                }

                .add-form {

                    display: grid;

                    grid-template-columns: 180px minmax(220px, 1fr) 180px 1fr auto;

                    gap: 15px;

                    align-items: end;

                }

                .form-group label {

                    display: block;

                    font-size: 14px;

                    font-weight: bold;

                    margin-bottom: 7px;

                }

                input {

                    width: 100%;

                    padding: 11px 12px;

                    border: 1px solid #ccc;

                    border-radius: 6px;

                    font-size: 14px;

                    outline: none;

                }

                input:focus {

                    border-color: #2e7d32;

                    box-shadow:

                        0 0 0 2px

                        rgba(46,125,50,.12);

                }

                .color-input {

                    min-height: 42px;

                    padding: 4px;

                    cursor: pointer;

                }

                .file-input {

                    min-height: 42px;

                    padding: 7px;

                    background: #fff;

                }

                .department-brand {

                    display: flex;

                    align-items: center;

                    gap: 10px;

                }

                .department-logo-preview {

                    width: 42px;

                    height: 42px;

                    object-fit: contain;

                    border: 1px solid #e5e7eb;

                    border-radius: 8px;

                    padding: 3px;

                    background: #fff;

                }

                .department-color-swatch {

                    display: inline-flex;

                    align-items: center;

                    gap: 8px;

                    font-size: 12px;

                    color: #555;

                }

                .department-color-dot {

                    width: 22px;

                    height: 22px;

                    border: 1px solid rgba(0,0,0,.15);

                    border-radius: 6px;

                    display: inline-block;

                }

                /* =========================================

                   BUTTONS

                ========================================= */

                .btn {

                    border: none;

                    padding: 9px 15px;

                    border-radius: 6px;

                    cursor: pointer;

                    font-size: 14px;

                    font-weight: 600;

                    text-decoration: none;

                    display: inline-flex;

                    align-items: center;

                    justify-content: center;

                    gap: 6px;

                    transition: .2s;

                }

                .btn:active {

                    transform: scale(.97);

                }

                .green {

                    background: #2e7d32;

                    color: white;

                }

                .green:hover {

                    background: #1b5e20;

                }

                .green:hover {

                    background: #1b5e20;

                }

                /* ADD COLLEGE DEPARTMENT BUTTON - BLUE */

                .add-department-btn {

                    background: #2563eb;

                    color: #ffffff;

                    box-shadow: 0 2px 5px rgba(37, 99, 235, 0.22);

                }

                .add-department-btn:hover {

                    background: #1d4ed8;

                    color: #ffffff;

                    box-shadow: 0 4px 9px rgba(37, 99, 235, 0.28);

                    transform: translateY(-1px);

                }

                .add-department-btn:focus-visible {

                    outline: 3px solid rgba(37, 99, 235, 0.30);

                    outline-offset: 2px;

                }

                .yellow {

                    background: #f9a825;

                    color: #1b5e20;

                }

                .yellow:hover {

                    background: #e89a00;

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

                    background: #757575;

                    color: white;

                }

                .gray:hover {

                    background: #616161;

                }

                /* =========================================

                   TABLE

                ========================================= */

                .table-card {

                    background: white;

                    border-radius: 12px;

                    overflow: hidden;

                    box-shadow:

                        0 3px 12px rgba(0,0,0,.08);

                }

                .table-header {

                    display: flex;

                    justify-content: space-between;

                    align-items: center;

                    padding: 18px 20px;

                    border-bottom: 1px solid #e5e5e5;

                }

                .table-header h3 {

                    margin: 0;

                    color: #2e7d32;

                    font-size: 19px;

                }

                .department-count {

                    background: #e8f5e9;

                    color: #2e7d32;

                    padding: 6px 10px;

                    border-radius: 20px;

                    font-size: 13px;

                    font-weight: bold;

                }

                .table-container {

                    width: 100%;

                    overflow-x: auto;

                }

                table {

                    width: 100%;

                    border-collapse: collapse;

                }

                th {

                    background: #2e7d32;

                    color: white;

                    padding: 14px 16px;

                    text-align: left;

                    font-size: 13px;

                }

                td {

                    padding: 14px 16px;

                    border-bottom: 1px solid #eeeeee;

                    font-size: 14px;

                    vertical-align: middle;

                }

                tbody tr:hover {

                    background: #f9fff9;

                }

                tbody tr:last-child td {

                    border-bottom: none;

                }

                /* =========================================

                   DEPARTMENT CODE

                ========================================= */

                .department-code {

                    display: inline-block;

                    background: #e8f5e9;

                    color: #2e7d32;

                    padding: 6px 10px;

                    border-radius: 6px;

                    font-weight: bold;

                    font-size: 13px;

                    min-width: 55px;

                    text-align: center;

                }

                .department-name {

                    font-weight: 600;

                    color: #333;

                }

                .id-number {

                    color: #777;

                    font-weight: bold;

                }

                .building-count {

                    color: #666;

                }

                /* =========================================

                   ACTIONS

                ========================================= */

                .actions {

                    display: flex;

                    gap: 7px;

                    align-items: center;

                }

                .actions form {

                    margin: 0;

                }

                /* =========================================

                   EMPTY STATE

                ========================================= */

                .empty-state {

                    text-align: center;

                    padding: 50px 20px;

                    color: #777;

                }

                .empty-state-icon {

                    font-size: 35px;

                    margin-bottom: 10px;

                }

                .empty-state h3 {

                    margin: 0 0 5px;

                    color: #555;

                }

                .empty-state p {

                    margin: 0;

                    font-size: 14px;

                }

                /* =========================================

                   EDIT MODAL

                ========================================= */

                .modal {

                    display: none;

                    position: fixed;

                    z-index: 9999;

                    left: 0;

                    top: 0;

                    width: 100%;

                    height: 100%;

                    background: rgba(0,0,0,.55);

                    overflow-y: auto;

                    padding: 30px 15px;

                }

                .modal-content {

                    width: 500px;

                    max-width: 100%;

                    background: white;

                    margin: 50px auto;

                    padding: 28px;

                    border-radius: 12px;

                    box-shadow:

                        0 10px 35px rgba(0,0,0,.25);

                    border-top: 5px solid #2e7d32;

                    animation:

                        modalOpen .2s ease;

                }

                @keyframes modalOpen {

                    from {

                        opacity: 0;

                        transform:

                            translateY(-15px)

                            scale(.98);

                    }

                    to {

                        opacity: 1;

                        transform:

                            translateY(0)

                            scale(1);

                    }

                }

                .modal-content h3 {

                    margin-top: 0;

                    margin-bottom: 7px;

                    color: #2e7d32;

                    font-size: 20px;

                }

                .modal-subtitle {

                    color: #777;

                    font-size: 14px;

                    margin-bottom: 22px;

                }

                .modal-form-group {

                    margin-bottom: 16px;

                }

                .modal-form-group label {

                    display: block;

                    font-size: 14px;

                    font-weight: bold;

                    margin-bottom: 7px;

                }

                .modal-buttons {

                    display: flex;

                    justify-content: flex-end;

                    gap: 9px;

                    margin-top: 22px;

                }

                /* =========================================

                   RESPONSIVE

                ========================================= */

                @media(max-width: 900px) {

                    .add-form {

                        grid-template-columns: 1fr 1fr;

                    }

                    .add-button {

                        grid-column: 1 / -1;

                    }

                }

                @media(max-width: 700px) {

                    .add-form {

                        grid-template-columns: 1fr;

                    }

                    .add-button {

                        grid-column: auto;

                    }

                    .table-header {

                        flex-direction: column;

                        align-items: flex-start;

                        gap: 10px;

                    }

                    .actions {

                        flex-wrap: wrap;

                    }

                    .modal-content {

                        margin: 20px auto;

                        padding: 22px;

                    }

                }

/* =========================================

   CENTER SUCCESS / ERROR POPUP

\\========================================= */

.action-message-overlay {

    position: fixed !important;

    inset: 0 !important;

    top: 0 !important;

    left: 0 !important;

    right: 0 !important;

    bottom: 0 !important;

    width: 100vw;

    height: 100vh;

    background: rgba(0, 0, 0, .45);

    backdrop-filter: blur(3px);

    display: flex !important;

    align-items: center !important;

    justify-content: center !important;

    padding: 20px;

    box-sizing: border-box;

    z-index: 999999 !important;

    margin: 0;

    animation: actionFadeIn .2s ease;

}

.action-message-modal {

    position: relative;

    width: 420px;

    max-width: 100%;

    max-height: calc(100vh - 40px);

    overflow-y: auto;

    margin: 0 !important;

    background: #fff;

    border-radius: 18px;

    padding: 38px 35px 32px;

    text-align: center;

    box-sizing: border-box;

    box-shadow: 0 20px 60px rgba(0,0,0,.20);

    animation: actionPop .25s ease;

}

.action-message-close {

    position: absolute;

    top: 12px;

    right: 15px;

    width: 34px;

    height: 34px;

    border: none;

    background: transparent;

    color: #777;

    font-size: 28px;

    line-height: 30px;

    cursor: pointer;

    border-radius: 50%;

}

.action-message-close:hover {

    background: #f3f4f6;

    color: #333;

}

.action-message-icon {

    width: 78px;

    height: 78px;

    margin: 0 auto 20px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

}

.action-message-icon.success {

    background: #dcfce7;

}

.action-message-icon.error {

    background: #fee2e2;

}

.action-message-icon svg {

    width: 52px;

    height: 52px;

    fill: none;

    stroke-width: 3;

    stroke-linecap: round;

    stroke-linejoin: round;

}

.action-message-icon.success svg {

    stroke: #15803d;

}

.action-message-icon.error svg {

    stroke: #dc2626;

}

.action-message-modal h2 {

    margin: 0 0 10px;

    font-size: 25px;

    font-weight: 700;

}

.action-message-modal.success h2 {

    color: #166534;

}

.action-message-modal.error h2 {

    color: #b91c1c;

}

.action-message-modal p {

    margin: 0 auto 25px;

    max-width: 330px;

    color: #555;

    font-size: 16px;

    line-height: 1.5;

}

.action-message-ok {

    width: 130px;

    padding: 11px 25px;

    border: none;

    border-radius: 8px;

    color: #fff;

    font-size: 15px;

    font-weight: 600;

    cursor: pointer;

    transition: all .2s ease;

}

.action-message-ok.success {

    background: #15803d;

}

.action-message-ok.success:hover {

    background: #166534;

    transform: translateY(-1px);

}

.action-message-ok.error {

    background: #dc2626;

}

.action-message-ok.error:hover {

    background: #b91c1c;

    transform: translateY(-1px);

}

@keyframes actionFadeIn {

    from { opacity: 0; }

    to { opacity: 1; }

}

@keyframes actionPop {

    from {

        opacity: 0;

        transform: scale(.85) translateY(10px);

    }

    to {

        opacity: 1;

        transform: scale(1) translateY(0);

    }

}

@media (max-width: 500px) {

    .action-message-modal {

        width: 100%;

        padding: 32px 22px 27px;

    }

    .action-message-modal h2 {

        font-size: 22px;

    }

    .action-message-modal p {

        font-size: 15px;

    }

}

</style>

<style>
    /* Department management: high contrast, generous controls, consistent neon accents. */
    .department-page { max-width: 1480px; margin: 0 auto; padding: 8px 0 36px; }
    .department-page .department-hero {
        display: flex; align-items: center; justify-content: space-between; gap: 28px;
        min-height: 190px; margin: 0 0 26px; padding: 30px clamp(24px, 4vw, 42px);
        overflow: hidden; border: 1px solid rgba(101,231,255,.25); border-radius: 20px;
        background: radial-gradient(ellipse at 85% 15%, rgba(84,81,166,.28), transparent 42%), linear-gradient(120deg, #142b38 0%, #12222f 55%, #171a35 100%);
        box-shadow: 0 18px 38px rgba(0,0,0,.22), inset 0 1px rgba(255,255,255,.035);
    }
    .department-page .department-hero-copy { min-width: 0; }
    .department-page .department-hero-eyebrow { display: flex; align-items: center; gap: 9px; margin: 0 0 12px; color: #9befff; font-size: 13px; font-weight: 800; letter-spacing: .12em; }
    .department-page .department-hero h1 { margin: 0 0 9px; color: #f1f9fc; font-size: clamp(32px, 3vw, 42px); font-weight: 850; line-height: 1.15; letter-spacing: -.03em; }
    .department-page .department-hero-description { margin: 0; color: #c5d5de; font-size: 17px; line-height: 1.6; }
    .department-page .department-hero-mark { display: flex; flex: 0 0 132px; flex-direction: column; align-items: center; justify-content: center; gap: 12px; width: 132px; height: 132px; border: 1px solid rgba(101,231,255,.25); border-radius: 22px; background: rgba(6,18,28,.35); color: #89eaff; box-shadow: 0 0 32px rgba(101,231,255,.08), inset 0 0 28px rgba(101,231,255,.04); }
    .department-page .department-hero-mark i { font-size: 42px; }
    .department-page .department-hero-mark span { color: #b7d2dd; font-size: 10px; font-weight: 800; letter-spacing: .1em; text-align: center; }

    .department-page .add-card,
    .department-page .table-card {
        overflow: hidden; border: 1px solid rgba(146,190,210,.24) !important;
        border-radius: 18px; background: #111f2a !important;
        box-shadow: 0 14px 32px rgba(0,0,0,.2) !important;
    }
    .department-page .add-card { margin-bottom: 24px; padding: 25px; border-top: 3px solid #65e7ff !important; }
    .department-page .add-card h3,
    .department-page .table-header h3 { margin: 0; color: #f0f8fc !important; font-size: 23px !important; line-height: 1.35; }
    .department-page .add-form { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 20px 24px; align-items: stretch; }
    .department-page .form-group { min-width: 0; }
    .department-page .form-group label { margin-bottom: 9px; color: #e1edf2 !important; font-size: 17px !important; line-height: 1.45; }
    .department-page .form-group small { display: block; margin-top: 8px; color: #b3c6d0 !important; font-size: 14px !important; line-height: 1.5; }
    .department-page .form-group input:not([type=color]):not([type=file]) {
        min-height: 52px !important; padding: 12px 14px !important; border: 1px solid rgba(157,194,211,.35) !important;
        border-radius: 10px !important; background: #0a151e !important; color: #f0f8fc !important; font-size: 17px !important;
    }
    .department-page .form-group input[type=file] {
        display: block; box-sizing: border-box; width: 100%; min-height: 52px !important; margin: 0 !important; padding: 8px 10px !important;
        border: 1px solid rgba(157,194,211,.35) !important; border-radius: 10px !important;
        background: #0a151e !important; color: #e0edf2 !important; font-size: 15px !important;
    }
    .department-page .add-department-action {
        display: flex; flex-direction: column; align-self: start; min-width: 0; padding: 0;
    }
    .department-page .add-department-action-spacer {
        display: block; height: auto; min-height: 24.65px; margin: 0 0 9px;
        visibility: hidden; font-size: 17px; line-height: 1.45; pointer-events: none;
    }
    .department-page .add-department-btn {
        width: 100%; min-height: 52px !important; margin: 0 !important; padding: 12px 18px !important;
        border: 1px solid rgba(101,231,255,.35) !important; border-radius: 11px !important;
        background: linear-gradient(120deg,#087c96,#5551a6) !important; color: #f4fcff !important;
        font-size: 17px !important; font-weight: 800 !important;
    }
    .department-page .add-department-btn:hover { filter: brightness(1.12); box-shadow: 0 0 22px rgba(101,231,255,.14); }

    .department-page .table-header {
        display: flex; align-items: center; justify-content: space-between; gap: 16px;
        padding: 22px 25px; border-bottom: 1px solid rgba(146,190,210,.2); background: #152631;
    }
    .department-page .department-count {
        display: inline-flex; align-items: center; justify-content: center; min-height: 40px;
        padding: 7px 13px; border: 1px solid rgba(101,231,255,.25); border-radius: 999px;
        background: rgba(101,231,255,.09); color: #c2f3fb; font-size: 15px; font-weight: 750;
    }
    .department-page .table-container { overflow-x: auto !important; background: #111f2a !important; }
    .department-page table { min-width: 820px; border-collapse: separate; border-spacing: 0; }
    .department-page table th {
        padding: 16px 17px !important; border-color: rgba(146,190,210,.2) !important;
        background: #1b2d39 !important; color: #e0f5fb !important; font-size: 15px !important;
        line-height: 1.45; text-align: left; white-space: nowrap;
    }
    .department-page table td {
        padding: 15px 17px !important; border-color: rgba(146,190,210,.15) !important;
        background: #111f2a !important; color: #dceaf0 !important;
        font-size: 16px !important; line-height: 1.5; vertical-align: middle;
    }
    .department-page table tbody tr:nth-child(even) td { background: #14232e !important; }
    .department-page table tbody tr:hover td { background: #1a303c !important; }
    .department-page .department-code,
    .department-page .id-number,
    .department-page .building-count {
        color: #d9edf4 !important; font-size: 15px !important; font-weight: 700;
    }
    .department-page .department-code { display: inline-flex; padding: 6px 9px; border: 1px solid rgba(101,231,255,.22); border-radius: 8px; background: rgba(101,231,255,.08); color: #a8effa !important; }
    .department-page .department-name { color: #f0f8fc !important; font-size: 17px !important; font-weight: 750; }
    .department-page .department-logo-preview { width: 52px; height: 52px; object-fit: cover; border: 1px solid rgba(146,190,210,.28); border-radius: 12px; background: #0a151e; }
    .department-page .actions { display: flex; flex-wrap: wrap; gap: 9px; }
    .department-page .actions form { margin: 0; }
    .department-page .actions .btn { min-width: 88px; min-height: 44px !important; padding: 9px 13px !important; border: 1px solid transparent !important; border-radius: 9px !important; font-size: 15px !important; font-weight: 750 !important; }
    .department-page .actions .btn.yellow { background: #49391a !important; border-color: #8f7135 !important; color: #ffe5a0 !important; }
    .department-page .actions .btn.red { background: #48232d !important; border-color: #a34e62 !important; color: #ffc2cb !important; }
    .department-page .actions .btn:hover { filter: brightness(1.18); }
    .department-page .empty-state { padding: 44px 20px; color: #cad8df !important; }
    .department-page .empty-state-icon { font-size: 44px; }
    .department-page .empty-state h3 { color: #eff8fc !important; font-size: 23px !important; }
    .department-page .empty-state p { color: #bdcfd8 !important; font-size: 16px !important; }

    body.admin-portal .modal { z-index: 10000; background: rgba(2,8,13,.82) !important; backdrop-filter: blur(5px); }
    body.admin-portal .modal-content {
        width: min(620px, 100%); margin: 26px auto; padding: 30px;
        border: 1px solid rgba(101,231,255,.28) !important; border-top: 3px solid #65e7ff !important;
        border-radius: 18px; background: #111f2a !important; color: #e8f4fb !important;
        box-shadow: 0 28px 80px rgba(0,0,0,.48) !important;
    }
    body.admin-portal .modal-content h3 { color: #f0f8fc !important; font-size: 26px !important; }
    body.admin-portal .modal-subtitle { margin-bottom: 24px; color: #bdcfd8 !important; font-size: 16px !important; line-height: 1.55; }
    body.admin-portal .modal-form-group label { margin-bottom: 8px; color: #e1edf2 !important; font-size: 17px !important; }
    body.admin-portal .modal-form-group input:not([type=color]):not([type=file]) {
        min-height: 50px !important; padding: 11px 13px !important; border: 1px solid rgba(157,194,211,.35) !important;
        border-radius: 10px !important; background: #0a151e !important; color: #f0f8fc !important; font-size: 17px !important;
    }
    body.admin-portal .modal-buttons { justify-content: stretch; gap: 12px; margin-top: 26px; }
    body.admin-portal .modal-buttons .btn { min-height: 48px !important; padding: 10px 18px !important; border-radius: 10px !important; font-size: 16px !important; }
    body.admin-portal .modal-buttons .btn.green { background: linear-gradient(120deg,#087c96,#5551a6) !important; color: #f4fcff !important; }
    body.admin-portal .modal-buttons .btn.gray { border: 1px solid rgba(146,190,210,.3) !important; background: #1b2b37 !important; color: #e4f0f5 !important; }

    .department-page .action-message-overlay { background: rgba(2,8,13,.82) !important; backdrop-filter: blur(5px); }
    .department-page .action-message-modal { width: min(520px, calc(100vw - 32px)); padding: 34px; border: 1px solid rgba(101,231,255,.3); border-radius: 20px; background: #111f2a !important; box-shadow: 0 28px 80px rgba(0,0,0,.5); }
    .department-page .action-message-modal h2 { color: #eff8fc !important; font-size: 27px !important; }
    .department-page .action-message-modal p { color: #c4d4dc !important; font-size: 17px !important; line-height: 1.6; }
    .department-page .action-message-close { width: 42px; height: 42px; border: 1px solid rgba(146,190,210,.25); border-radius: 10px; background: #1b2b37; color: #eaf6fb; font-size: 25px; }
    .department-page .action-message-ok { min-width: 112px; min-height: 48px !important; border-radius: 10px; font-size: 16px !important; }
    .department-page .action-message-ok.success { background: #087c96 !important; color: #f4fcff !important; }
    .department-page .action-message-ok.error { background: #8f3045 !important; color: #fff1f3 !important; }
    .department-page :focus-visible { outline: 3px solid #75eaff !important; outline-offset: 3px !important; }

    @media (max-width: 900px) {
        .department-page .add-form { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .department-page .add-department-action { grid-column: auto; }
    }
    @media (max-width: 700px) {
        .department-page { padding-top: 4px; }
        .department-page .department-hero { align-items: flex-start; min-height: 0; padding: 24px 21px; }
        .department-page .department-hero h1 { font-size: 31px; }
        .department-page .department-hero-description { font-size: 16px; }
        .department-page .department-hero-mark { display: none; }
        .department-page .add-card { padding: 19px; }
        .department-page .add-form { grid-template-columns: 1fr; gap: 16px; }
        .department-page .add-department-action-spacer { display: none; }
        .department-page .table-header { align-items: flex-start; flex-direction: column; padding: 19px; }
        .department-page .actions { flex-direction: column; align-items: stretch; }
        .department-page .actions .btn { width: 100%; }
        .department-page .modal-content { margin: 10px auto; padding: 22px; }
    }
</style>

<style>
    /* White sage neumorphic department management, with the existing Blade actions preserved. */
    .department-page {
        --dept-ink: #263b31;
        --dept-muted: #52665a;
        --dept-green: #36785c;
        --dept-line: #d8e2da;
        max-width: 1480px !important;
        margin: 0 auto !important;
        padding: 24px clamp(18px, 3vw, 38px) 44px !important;
        color: var(--dept-ink) !important;
        font-size: 17px;
        line-height: 1.55;
    }
    .department-page * { box-sizing: border-box; }
    .department-page .department-hero {
        position: relative;
        min-height: 190px;
        margin: 0 0 27px;
        padding: clamp(26px, 4vw, 42px);
        border: 1px solid #cbdacf !important;
        border-radius: 22px;
        background: linear-gradient(120deg, #e6eee7 0%, #f5f8f4 68%, #e9f0e9 100%) !important;
        box-shadow: 9px 9px 20px #dce3dc, -8px -8px 20px #fff !important;
    }
    .department-page .department-hero-eyebrow { color: #3c7358 !important; font-size: 14px !important; }
    .department-page .department-hero h1 { color: #203c2d !important; font-size: clamp(32px, 3vw, 42px) !important; line-height: 1.2; }
    .department-page .department-hero-description { color: #4c6254 !important; font-size: 18px !important; line-height: 1.6; }
    .department-page .department-hero-mark {
        border: 1px solid #d3dfd4 !important;
        border-radius: 20px;
        background: #eaf0ea !important;
        color: #36785c !important;
        box-shadow: 5px 5px 12px #dce3dc, -5px -5px 12px #fff !important;
    }
    .department-page .department-hero-mark span { color: #52665a !important; font-size: 12px !important; }
    .department-page .add-card,
    .department-page .table-card {
        overflow: hidden;
        border: 1px solid var(--dept-line) !important;
        border-radius: 18px;
        background: #f1f5f1 !important;
        box-shadow: 7px 7px 16px #dce3dc, -7px -7px 16px #fff !important;
        color: var(--dept-ink) !important;
    }
    .department-page .add-card { margin-bottom: 25px; padding: clamp(20px, 3vw, 28px); border-top: 3px solid #7da58a !important; }
    .department-page .add-card h3,
    .department-page .table-header h3 { color: #294b37 !important; font-size: 24px !important; line-height: 1.35; }
    .department-page .add-form { gap: 20px 24px; align-items: end; }
    .department-page .form-group { min-width: 0; }
    .department-page .form-group label { margin-bottom: 9px; color: #344a3b !important; font-size: 17px !important; line-height: 1.45; }
    .department-page .form-group small { color: #52665a !important; font-size: 15px !important; line-height: 1.5; }
    .department-page .form-group input:not([type=color]):not([type=file]),
    .department-page .form-group input[type=file] {
        min-height: 52px !important;
        padding: 12px 14px !important;
        border: 1px solid #cbdacf !important;
        border-radius: 11px !important;
        background: #edf2ed !important;
        color: #263b31 !important;
        font-size: 17px !important;
        box-shadow: inset 3px 3px 6px #dce3dc, inset -3px -3px 6px #fff !important;
    }
    .department-page input::placeholder { color: #69796e !important; opacity: 1; }
    .department-page .add-department-action-spacer { min-height: 24px; }
    .department-page .add-department-btn {
        min-height: 52px !important;
        padding: 12px 18px !important;
        border: 1px solid #6f9d7b !important;
        border-radius: 12px !important;
        background: #3f7b5d !important;
        color: #fff !important;
        font-size: 17px !important;
        font-weight: 800 !important;
        box-shadow: 4px 4px 9px #d1dbd2, -4px -4px 9px #fff !important;
        transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .department-page .add-department-btn:hover { background: #32684d !important; transform: translateY(-1px); box-shadow: 5px 6px 12px #d1dbd2, -4px -4px 9px #fff !important; }
    .department-page .table-header { padding: 22px 25px; border-bottom: 1px solid #dce5dc; background: #e9f0e9 !important; }
    .department-page .department-count { border: 1px solid #c8d9ca; background: #e4ede5; color: #315f43 !important; font-size: 16px; }
    .department-page .table-container { overflow-x: auto !important; background: #f1f5f1 !important; }
    .department-page table { min-width: 820px; border-collapse: separate; border-spacing: 0; }
    .department-page table th {
        padding: 16px 17px !important;
        border-color: #d5dfd6 !important;
        background: #e5ede6 !important;
        color: #294b37 !important;
        font-size: 16px !important;
        line-height: 1.45;
    }
    .department-page table td {
        padding: 15px 17px !important;
        border-color: #dce5dc !important;
        background: #f6f8f6 !important;
        color: #344a3b !important;
        font-size: 17px !important;
        line-height: 1.5;
    }
    .department-page table tbody tr:nth-child(even) td { background: #edf2ed !important; }
    .department-page table tbody tr:hover td { background: #e5eee6 !important; }
    .department-page .department-code,
    .department-page .id-number,
    .department-page .building-count { color: #344a3b !important; font-size: 16px !important; font-weight: 750; }
    .department-page .department-code { border: 1px solid #d2dfd3; border-radius: 8px; background: #e8efe8; color: #315f43 !important; }
    .department-page .department-name { color: #263b31 !important; font-size: 18px !important; font-weight: 800; }
    .department-page .department-logo-preview { border: 1px solid #d2dfd3; border-radius: 12px; background: #edf2ed; }
    .department-page .actions { gap: 9px; }
    .department-page .actions .btn { min-width: 92px; min-height: 46px !important; border-radius: 10px !important; font-size: 16px !important; font-weight: 750 !important; }
    .department-page .actions .btn.yellow { border-color: #b49a59 !important; background: #f4edda !important; color: #5d4918 !important; }
    .department-page .actions .btn.red { border-color: #ca9a9e !important; background: #f6e8e9 !important; color: #752e36 !important; }
    .department-page .actions .btn:hover { filter: brightness(.97); transform: translateY(-1px); }
    .department-page .empty-state { padding: 44px 20px; color: #52665a !important; }
    .department-page .empty-state-icon { color: #518066; font-size: 44px; }
    .department-page .empty-state h3 { color: #294b37 !important; font-size: 24px !important; }
    .department-page .empty-state p { color: #52665a !important; font-size: 17px !important; }
    body.admin-portal .modal { background: rgba(28, 45, 34, .42) !important; backdrop-filter: blur(4px); }
    body.admin-portal .modal-content {
        border: 1px solid #d3dfd4 !important;
        border-top: 3px solid #7da58a !important;
        border-radius: 18px;
        background: #f1f5f1 !important;
        color: #263b31 !important;
        box-shadow: 8px 8px 20px rgba(50,70,55,.18), -4px -4px 12px rgba(255,255,255,.8) !important;
    }
    body.admin-portal .modal-content h3 { color: #294b37 !important; font-size: 27px !important; }
    body.admin-portal .modal-subtitle { color: #52665a !important; font-size: 17px !important; line-height: 1.55; }
    body.admin-portal .modal-form-group label { color: #344a3b !important; font-size: 17px !important; }
    body.admin-portal .modal-form-group input:not([type=color]):not([type=file]) {
        min-height: 50px !important; padding: 11px 13px !important; border: 1px solid #cbdacf !important;
        border-radius: 10px !important; background: #edf2ed !important; color: #263b31 !important; font-size: 17px !important;
        box-shadow: inset 3px 3px 6px #dce3dc, inset -3px -3px 6px #fff !important;
    }
    body.admin-portal .modal-buttons { gap: 12px; margin-top: 26px; }
    body.admin-portal .modal-buttons .btn { min-height: 48px !important; border-radius: 10px !important; font-size: 16px !important; }
    body.admin-portal .modal-buttons .btn.green { background: #3f7b5d !important; color: #fff !important; }
    body.admin-portal .modal-buttons .btn.gray { border: 1px solid #cbdacf !important; background: #e8eee8 !important; color: #344a3b !important; }
    .department-page .action-message-overlay { background: rgba(28, 45, 34, .45) !important; backdrop-filter: blur(4px); }
    .department-page .action-message-modal { border: 1px solid #d3dfd4 !important; border-radius: 20px; background: #f1f5f1 !important; box-shadow: 8px 8px 24px rgba(50,70,55,.2), -4px -4px 12px rgba(255,255,255,.8); }
    .department-page .action-message-modal h2 { color: #294b37 !important; font-size: 27px !important; }
    .department-page .action-message-modal p { color: #52665a !important; font-size: 17px !important; line-height: 1.6; }
    .department-page .action-message-close { border: 1px solid #cbdacf; background: #e8eee8; color: #344a3b; }
    .department-page .action-message-ok.success { background: #3f7b5d !important; color: #fff !important; }
    .department-page .action-message-ok.error { background: #8b3b45 !important; color: #fff !important; }
    .department-page :focus-visible { outline: 3px solid #438463 !important; outline-offset: 3px !important; }
    @media (max-width: 700px) {
        .department-page { padding: 16px 14px 30px !important; }
        .department-page .department-hero { align-items: flex-start; min-height: 0; padding: 24px 21px; }
        .department-page .department-hero h1 { font-size: 31px !important; }
        .department-page .department-hero-mark { display: none; }
        .department-page .add-card { padding: 19px; }
        .department-page .add-form { grid-template-columns: 1fr; gap: 16px; }
        .department-page .add-department-action { grid-column: 1 / -1; }
        .department-page .add-department-action-spacer { display: none; }
        .department-page .table-header { align-items: flex-start; flex-direction: column; padding: 19px; }
        .department-page .actions { flex-direction: row; }
        .department-page .actions .btn { width: auto; }
        .department-page .modal-content { margin: 10px auto; padding: 22px; }
    }
</style>

            <div class="department-page">

                <!-- =====================================

                     TITLE

                ====================================== -->

                <header class="department-hero">
                    <div class="department-hero-copy">
                        <p class="department-hero-eyebrow"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> CAMPUS ADMINISTRATION</p>
                        <h1>Manage College Departments</h1>
                        <p class="department-hero-description">Add, edit and remove departments.</p>
                    </div>
                    <div class="department-hero-mark" aria-hidden="true">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>DEPARTMENT DIRECTORY</span>
                    </div>
                </header>

                <!-- =====================================

                {{-- SINGLE SUCCESS / ERROR MESSAGE POPUP --}}
                @php
                    $alertMessage = session('success')
                        ?? session('error')
                        ?? ($errors->any() ? $errors->first() : null);
                    $alertType = session()->has('success') ? 'success' : 'error';
                @endphp

                @if ($alertMessage)
                    <div id="departmentActionMessage" class="action-message-overlay"
                         role="alertdialog" aria-modal="true" aria-labelledby="actionMessageTitle">
                        <div class="action-message-modal {{ $alertType }}">
                            <button type="button" class="action-message-close"
                                    onclick="closeActionMessage()" aria-label="Close">&times;</button>

                            <div class="action-message-icon {{ $alertType }}" aria-hidden="true">
                                @if ($alertType === 'success')
                                    <svg viewBox="0 0 52 52">
                                        <circle cx="26" cy="26" r="24"></circle>
                                        <path d="M14 27l8 8 16-18"></path>
                                    </svg>
                                @else
                                    <svg viewBox="0 0 52 52">
                                        <circle cx="26" cy="26" r="24"></circle>
                                        <path d="M18 18l16 16M34 18L18 34"></path>
                                    </svg>
                                @endif
                            </div>

                            <h2 id="actionMessageTitle">
                                {{ $alertType === 'success' ? 'Successful!' : 'Please Check' }}
                            </h2>
                            <p>{{ $alertMessage }}</p>
                            <button type="button" class="action-message-ok {{ $alertType }}"
                                    onclick="closeActionMessage()">OK</button>
                        </div>
                    </div>
                @endif

                     ADD DEPARTMENT

                ====================================== -->

                <div class="add-card">

                    <h3>

                        Add New College Department

                    </h3>

                    <form

                        action="{{ route('admin.departments.store') }}"

                        method="POST"

                        enctype="multipart/form-data"

                    >

                        @csrf

                        <div class="add-form">

                            <!-- CODE -->

                            <div class="form-group">

                                <label>

                                    College Department Code

                                </label>

                                <input

                                    type="text"

                                    name="code"

                                    placeholder="Example: CAT"

                                    value="{{ old('code') }}"

                                    maxlength="50"

                                    required

                                >

                            </div>

                            <!-- NAME -->

                            <div class="form-group">

                                <label>

                                    College Department Name

                                </label>

                                <input

                                    type="text"

                                    name="name"

                                    placeholder="Example: College of Applied Technology"

                                    value="{{ old('name') }}"

                                    maxlength="255"

                                    required

                                >

                            </div>

                            <!-- DEPARTMENT LOGO -->

                            <div class="form-group">

                                <label for="departmentLogo">Department Logo (optional)</label>

                                <input class="file-input" type="file" id="departmentLogo" name="logo" accept="image/png,image/jpeg,image/webp">

                                <small>PNG, JPG, or WebP. Maximum 2 MB.</small>

                            </div>

                            <!-- ADD -->

                            <div class="add-department-action">

                                <span class="add-department-action-spacer" aria-hidden="true">Action</span>

                                <button

                                    type="submit"

                                    class="btn add-department-btn"

                                >

                                    + Add College Department

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

                <!-- =====================================

                     DEPARTMENT LIST

                ====================================== -->

                <div class="table-card">

                    <div class="table-header">

                        <h3>

                            College Department List

                        </h3>

                        <span class="department-count">

                            {{ $departments->count() }}

                            College Department(s)

                        </span>

                    </div>

                    <div class="table-container">

                        @if($departments->count() > 0)

                            <table>

                                <thead>

                                    <tr>

                                        <th>

                                            ID

                                        </th>

                                        <th>

                                            Code

                                        </th>

                                        <th>

                                            College Department Name

                                        </th>

                                        <th>Logo</th>


                                        <th>

                                            Buildings

                                        </th>

                                        <th>

                                            Actions

                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($departments as $department)

                                        <tr>

                                            <!-- ID -->

                                            <td>

                                                <span

                                                    class="id-number"

                                                >

                                                    {{ $department->id }}

                                                </span>

                                            </td>

                                            <!-- CODE -->

                                            <td>

                                                <span

                                                    class="department-code"

                                                >

                                                    {{ $department->code }}

                                                </span>

                                            </td>

                                            <!-- NAME -->

                                            <td>

                                                <span

                                                    class="department-name"

                                                >

                                                    {{ $department->name }}

                                                </span>

                                            </td>

                                            <!-- LOGO -->

                                            <td>

                                                @if($department->logo)

                                                    <img class="department-logo-preview" src="{{ asset('storage/' . $department->logo) }}" alt="{{ $department->name }} logo">

                                                @else

                                                    <span style="color:#9ca3af; font-size:12px;">No logo</span>

                                                @endif

                                            </td>

                                            <!-- BUILDINGS -->

                                            <td>

                                                <span

                                                    class="building-count"

                                                >

                                                    {{ $department->buildings_count }}

                                                    building(s)

                                                </span>

                                            </td>

                                            <!-- ACTIONS -->

                                            <td>

                                                <div class="actions">

                                                    <!-- EDIT -->

                                                    <button

                                                        type="button"

                                                        class="btn yellow"

                                                        onclick="openEditDepartment(

                                                            {{ $department->id }},

                                                            @js($department->code),

                                                            @js($department->name),

                                                            @js($department->logo)

                                                        )"

                                                    >

                                                        Edit

                                                    </button>

                                                    <!-- DELETE -->

                                                    <form

                                                        action="{{ route(

                                                            'admin.departments.delete',

                                                            $department->id

                                                        ) }}"

                                                        method="POST"

                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button

                                                            type="submit"

                                                            class="btn red"

                                                            onclick="return confirmDelete(

                                                                @js($department->name)

                                                            )"

                                                        >

                                                            Delete

                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        @else

                            <div class="empty-state">

                                <div class="empty-state-icon">

                                    🏢

                                </div>

                                <h3>

                                    No Departments Found

                                </h3>

                                <p>

                                    Add your first department above.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </main>

    </div>

 @include('footerheader.footer')

</div>

<!-- ==================================================

     EDIT DEPARTMENT MODAL

\\=================================================== -->

<div

    id="editDepartmentModal"

    class="modal"

>

    <div class="modal-content">

        <h3>

            Edit Department

        </h3>

        <div class="modal-subtitle">

            Update the department information.

        </div>

        <form

            id="editDepartmentForm"

            method="POST"

            enctype="multipart/form-data"

        >

            @csrf

            @method('PUT')

            <!-- CODE -->

            <div class="modal-form-group">

                <label>

                    Department Code

                </label>

                <input

                    type="text"

                    name="code"

                    id="editDepartmentCode"

                    maxlength="50"

                    required

                >

            </div>

            <!-- NAME -->

            <div class="modal-form-group">

                <label>

                    Department Name

                </label>

                <input

                    type="text"

                    name="name"

                    id="editDepartmentName"

                    maxlength="255"

                    required

                >

            </div>

            <div class="modal-form-group">

            </div>

            <div class="modal-form-group">

                <label for="editDepartmentLogo">Change Department Logo (optional)</label>

                <div id="editDepartmentCurrentLogo" style="margin:8px 0 12px;"></div>

                <input class="file-input" type="file" name="logo" id="editDepartmentLogo" accept="image/png,image/jpeg,image/webp">

                <small>Leave empty to keep the current logo. PNG, JPG, or WebP; maximum 2 MB.</small>

            </div>

            <div class="modal-buttons">

                <button

                    type="button"

                    class="btn gray"

                    onclick="closeEditDepartment()"

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

<script>

/*

\|--------------------------------------------------------------------------

\| OPEN EDIT DEPARTMENT

\|--------------------------------------------------------------------------

*/

function openEditDepartment(

    id,

    code,

    name,

    logoPath

)

{

    const modal =

        document.getElementById(

            'editDepartmentModal'

        );

    const form =

        document.getElementById(

            'editDepartmentForm'

        );

    const codeInput =

        document.getElementById(

            'editDepartmentCode'

        );

    const nameInput =

        document.getElementById(

            'editDepartmentName'

        );


    const logoPreview = document.getElementById('editDepartmentCurrentLogo');

    const logoInput = document.getElementById('editDepartmentLogo');

    if (

        !modal ||

        !form ||

        !codeInput ||

        !nameInput ||


        !logoPreview

    ) {

        console.error(

            'Edit Department modal not found.'

        );

        return;

    }

    codeInput.value = code;

    nameInput.value = name;


    logoPreview.innerHTML = logoPath

        ? '<img class="department-logo-preview" src="/storage/' + String(logoPath).replace(/^\/+/, '') + '" alt="Current department logo">'

        : '<span style="color:#9ca3af;font-size:12px;">No logo uploaded yet.</span>';

    if (logoInput) logoInput.value = '';

    form.action =

        '/admin/departments/' + id;

    modal.style.display = 'block';

    setTimeout(function() {

        codeInput.focus();

    }, 100);

}

/*

\|--------------------------------------------------------------------------

\| CLOSE EDIT MODAL

\|--------------------------------------------------------------------------

*/

function closeEditDepartment()

{

    const modal =

        document.getElementById(

            'editDepartmentModal'

        );

    if (modal) {

        modal.style.display = 'none';

    }

}

/*

\|--------------------------------------------------------------------------

\| DELETE CONFIRMATION

\|--------------------------------------------------------------------------

*/

function confirmDelete(name)

{

    return confirm(

        'Are you sure you want to delete "' +

        name +

        '"?'

    );

}

/*

\|--------------------------------------------------------------------------

\| CLICK OUTSIDE MODAL

\|--------------------------------------------------------------------------

*/

window.addEventListener(

    'click',

    function(event)

    {

        const modal =

            document.getElementById(

                'editDepartmentModal'

            );

        if (

            modal &&

            event.target === modal

        ) {

            closeEditDepartment();

        }

    }

);

/*

\|--------------------------------------------------------------------------

\| ESCAPE KEY

\|--------------------------------------------------------------------------

*/

document.addEventListener(

    'keydown',

    function(event)

    {

        if (event.key === 'Escape') {

            closeEditDepartment();

        }

    }

);

</script>

<script>
(function () {
    const message = document.getElementById('departmentActionMessage');

    function closeActionMessage() {
        if (!message) return;
        message.style.display = 'none';
        message.setAttribute('aria-hidden', 'true');
    }

    function prepareActionMessage() {
        if (!message) return;
        if (message.parentElement !== document.body) {
            document.body.appendChild(message);
        }
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

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeActionMessage();
    });
})();
</script>
