@php
    use Illuminate\Support\Facades\Auth;
@endphp

@include('footerheader.header')

<div class="wrapper">
    <style>
        :root {
            --primary: #2e7d32;
            --primary-dark: #1b5e20;
            --primary-soft: #eaf6ec;
            --accent: #f4a62a;
            --danger: #c62828;
            --text: #17211a;
            --muted: #6b756d;
            --border: #e1e7e2;
            --surface: #ffffff;
            --page: #f5f7f5;
            --shadow: 0 8px 24px rgba(25, 55, 30, .08);
        }

        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        .content {
            padding: 28px;
            background: var(--page);
            min-height: 100vh;
        }

        .dashboard-header {
            margin-bottom: 22px;
        }

        .dashboard-header h2 {
            color: var(--text);
            font-size: 30px;
            line-height: 1.15;
            font-weight: 700;
            margin: 0 0 7px;
        }

        .dashboard-header p {
            color: var(--muted);
            margin: 0;
            font-size: 14px;
        }

        /* Alerts */
        .dashboard-alert {
            border-radius: 12px;
            padding: 13px 16px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .dashboard-alert.success {
            color: #1b5e20;
            background: #eaf6ec;
            border: 1px solid #b9dfbd;
        }

        .dashboard-alert.error {
            color: #9b1c1c;
            background: #fff1f1;
            border: 1px solid #f0b9b9;
        }

        /* Summary cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
            align-items: stretch;
        }

        .summary-card {
            min-width: 0;
            min-height: 150px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(25, 55, 30, .12);
            border-color: #c9dccb;
        }

        .summary-card.clickable-card {
            cursor: pointer;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--muted);
        }

        .card-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-soft);
            color: var(--primary);
            flex: 0 0 auto;
        }

        .card-number {
            color: var(--text);
            font-size: 38px;
            line-height: 1;
            font-weight: 800;
            margin-top: 22px;
        }

        .card-description {
            color: var(--muted);
            font-size: 12px;
            margin-top: 8px;
        }

        .card-description.pending {
            color: var(--primary);
            font-weight: 600;
        }

        .card-description.approved-text {
            color: #24732a;
            font-weight: 600;
        }

        .card-description.rejected-text {
            color: #b42323;
            font-weight: 600;
        }

        .card-mini-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 14px;
        }

        .card-mini-stats .mini {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
            background: #f3f6f3;
        }

        .card-mini-stats .mini.pending {
            color: #8b6100;
            background: #fff6df;
        }

        .card-mini-stats .mini.approved {
            color: #216b27;
            background: #e8f6ea;
        }

        .status-card.pending-card::before {
            background: #d99a00;
        }

        .status-card.approved-card::before {
            background: #2e7d32;
        }

        .status-card.rejected-card::before {
            background: #c62828;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        .tab-count {
            min-width: 21px;
            height: 21px;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #eef2ee;
            color: #526056;
            font-size: 10px;
            font-weight: 800;
        }

        .tab-btn.active .tab-count {
            background: var(--primary-soft);
            color: var(--primary-dark);
        }

        .reservation-list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 18px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        .reservation-list-header h3 {
            margin: 0 0 5px;
            color: var(--text);
            font-size: 18px;
        }

        .reservation-list-header p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
        }

        .reservation-total {
            flex: 0 0 auto;
            padding: 7px 11px;
            border-radius: 999px;
            background: #f0f5f0;
            color: var(--primary-dark);
            font-size: 11px;
            font-weight: 800;
        }

        .reservation-card {
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .reservation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(25, 55, 30, .09);
        }

        .reservation-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #9aa39c;
        }

        .reservation-card.reservation-status-pending::before {
            background: #d99a00;
        }

        .reservation-card.reservation-status-approved::before {
            background: #2e7d32;
        }

        .reservation-card.reservation-status-rejected::before {
            background: #c62828;
        }

        .reservation-card.reservation-status-expired::before {
            background: #7c8580;
        }

        .reservation-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
        }

        .reservation-room-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .reservation-room-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--primary-soft);
            color: var(--primary);
        }

        .reservation-card h3 {
            margin: 0 0 5px;
            font-size: 16px;
        }

        .reservation-building {
            margin: 0 !important;
            color: var(--muted) !important;
            font-size: 12px !important;
        }

        .reservation-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex: 0 0 auto;
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
        }

        .reservation-status-badge.pending {
            color: #8b6100;
            background: #fff5dc;
        }

        .reservation-status-badge.approved {
            color: #216b27;
            background: #e8f6ea;
        }

        .reservation-status-badge.rejected {
            color: #a42323;
            background: #ffeded;
        }

        .reservation-status-badge.expired {
            color: #657066;
            background: #eef0ef;
        }

        .reservation-details {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-top: 16px;
            padding-top: 15px;
            border-top: 1px solid var(--border);
        }

        .reservation-detail {
            min-width: 0;
            padding: 10px;
            border-radius: 10px;
            background: #f8faf8;
        }

        .detail-label {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .detail-label i {
            color: var(--primary);
        }

        .reservation-detail strong {
            display: block;
            color: var(--text);
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .reservation-message {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 14px;
            padding: 11px 12px;
            border-radius: 10px;
            font-size: 12px;
            line-height: 1.45;
        }

        .reservation-message i {
            margin-top: 2px;
            flex: 0 0 auto;
        }

        .pending-message {
            color: #795600;
            background: #fff8e8;
            border: 1px solid #f1dfae;
        }

        .approved-message {
            color: #216b27;
            background: #edf8ee;
            border: 1px solid #c5e4c8;
        }

        .rejected-message {
            color: #9f2626;
            background: #fff0f0;
            border: 1px solid #efc4c4;
        }

        .expired-message {
            color: #5f6861;
            background: #f1f3f1;
            border: 1px solid #dfe3df;
        }

        .reservation-empty {
            min-height: 350px;
        }

        /* Main activity panel */
        .reservation-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .reservation-tabs {
            display: flex;
            align-items: stretch;
            border-bottom: 1px solid var(--border);
            background: #fafcfb;
            overflow-x: auto;
            scrollbar-width: thin;
        }

        .tab-btn {
            flex: 1 1 0;
            min-width: 150px;
            border: 0;
            border-bottom: 3px solid transparent;
            padding: 15px 18px;
            cursor: pointer;
            background: transparent;
            color: #657066;
            font-weight: 700;
            font-size: 13px;
            transition: .2s ease;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: var(--primary);
            background: #f1f7f2;
        }

        .tab-btn.active {
            color: var(--primary-dark);
            background: #fff;
            border-bottom-color: var(--primary);
        }

        .tab-content {
            display: none;
            min-height: 430px;
        }

        .tab-content.active {
            display: block;
        }

        /* Reservation cards */
        .reservation-list {
            padding: 22px;
        }

        .reservation-card {
            background: #fbfdfb;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 12px;
        }

        .reservation-card h3 {
            color: var(--text);
            margin-bottom: 12px;
        }

        .reservation-card p {
            color: #59635b;
            font-size: 13px;
            margin: 7px 0;
        }

        .reservation-card hr {
            border: 0;
            border-top: 1px solid var(--border);
            margin: 12px 0;
        }

        /* Empty state */
        .empty-room {
            min-height: 430px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--muted);
            padding: 40px 20px;
        }

        .empty-room .empty-icon {
            width: 74px;
            height: 74px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: #f0f4f1;
            color: #a8b0aa;
            font-size: 34px;
            margin-bottom: 16px;
        }

        .empty-room h3 {
            margin: 0 0 7px;
            color: #3d463f;
            font-size: 17px;
        }

        .empty-room p {
            margin: 0;
            color: #858d87;
            font-size: 13px;
        }

        /* Room swap request cards */
        .swap-request-container {
            width: 100%;
            padding: 22px;
        }

        .swap-request-title {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 16px;
        }

        .swap-request-title i {
            color: var(--primary);
        }

        .swap-request-card-item {
            background: #fff;
            border: 1px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 5px 16px rgba(25, 55, 30, .06);
        }

        .swap-request-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 18px;
        }

        .swap-request-header h3 {
            margin: 0 0 6px;
            color: var(--text);
            font-size: 16px;
        }

        .swap-request-header p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .swap-pending,
        .swap-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .swap-pending {
            background: #fff5dc;
            color: #8b6100;
        }

        .swap-status.active {
            background: #e8f6ea;
            color: #216b27;
        }

        .swap-status.expired {
            background: #eef0ef;
            color: #657066;
        }

        .swap-status.declined {
            background: #ffeded;
            color: #a42323;
        }

        .swap-request-comparison {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 44px minmax(0, 1fr);
            gap: 14px;
            align-items: stretch;
        }

        .swap-side {
            background: #f8faf8;
            border: 1px solid var(--border);
            border-radius: 11px;
            padding: 16px;
            min-width: 0;
        }

        .swap-side-title {
            color: var(--primary);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 9px;
        }

        .swap-side h4 {
            margin: 0 0 11px;
            color: var(--text);
            font-size: 15px;
        }

        .swap-side p {
            font-size: 12px;
            color: #59635b;
            margin: 6px 0;
            line-height: 1.4;
        }

        .swap-arrow {
            align-self: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--primary-soft);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .swap-request-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            margin-top: 17px;
            padding-top: 15px;
            border-top: 1px solid var(--border);
        }

        .swap-request-actions form {
            margin: 0;
        }

        .swap-approve-btn,
        .swap-decline-btn {
            border: 0;
            border-radius: 9px;
            padding: 10px 16px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transition: .2s ease;
        }

        .swap-approve-btn {
            background: var(--primary);
            color: #fff;
        }

        .swap-approve-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .swap-decline-btn {
            background: #fff;
            color: var(--danger);
            border: 1px solid #e5caca;
        }

        .swap-decline-btn:hover {
            background: #fff2f2;
        }

        /* Swap history */
        .swap-history-grid {
            padding: 22px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .swap-history-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            background: #fff;
        }

        .swap-history-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 15px;
        }

        .swap-history-top h3 {
            margin: 0 0 5px;
            font-size: 15px;
            color: var(--text);
        }

        .swap-history-top p {
            margin: 0;
            font-size: 12px;
            color: var(--muted);
        }

        .swap-time-box {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 11px 12px;
            border-radius: 10px;
            background: #f6f9f6;
            color: #465148;
            font-size: 12px;
            margin-bottom: 12px;
        }

        .swap-time-box i {
            color: var(--primary);
        }

        .swap-room-flow {
            display: grid;
            grid-template-columns: 1fr 34px 1fr;
            gap: 8px;
            align-items: center;
        }

        .swap-room-box {
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 10px;
            background: #fbfdfb;
        }

        .swap-room-box span {
            display: block;
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .swap-room-box strong {
            display: block;
            color: var(--text);
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .swap-room-arrow {
            text-align: center;
            color: var(--primary);
        }

        .swap-expiration {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 11px;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .dashboard-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .swap-history-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .content {
                padding: 18px;
            }

            .dashboard-header h2 {
                font-size: 24px;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .summary-card {
                min-height: 130px;
            }

            .reservation-tabs {
                flex-direction: column;
                overflow-x: visible;
            }

            .tab-btn {
                width: 100%;
                min-width: 0;
                text-align: left;
                border-bottom: 1px solid var(--border);
                border-left: 3px solid transparent;
            }

            .tab-btn.active {
                border-left-color: var(--primary);
                border-bottom-color: var(--border);
            }

            .swap-request-container,
            .swap-history-grid,
            .reservation-list {
                padding: 15px;
            }

            .reservation-list-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .reservation-card-top {
                flex-direction: column;
            }

            .reservation-status-badge {
                align-self: flex-start;
            }

            .reservation-details {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .swap-request-comparison {
                grid-template-columns: 1fr;
            }

            .swap-arrow {
                transform: rotate(90deg);
                justify-self: center;
            }

            .swap-request-actions {
                flex-direction: column;
            }

            .swap-approve-btn,
            .swap-decline-btn {
                width: 100%;
            }

            .swap-history-card {
                padding: 15px;
            }
        }

        @media (max-width: 420px) {
            .content {
                padding: 13px;
            }

            .summary-card {
                padding: 16px;
            }

            .card-number {
                font-size: 34px;
            }

            .reservation-details {
                grid-template-columns: 1fr;
            }

            .reservation-room-heading {
                align-items: flex-start;
            }

            .reservation-room-icon {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
            }

            .swap-room-flow {
                grid-template-columns: 1fr;
            }

            .swap-room-arrow {
                transform: rotate(90deg);
            }
        }
    </style>

  <div class="main-layout">

    @include('faculty.sidebar')

    <main class="content">


        <!-- DASHBOARD HEADER -->

        <div class="dashboard-header">

            <h2>
                Faculty Dashboard
            </h2>

            <p>
                Welcome, {{ Auth::user()->name }}!
            </p>

        </div>


        <!-- DASHBOARD CARDS -->

        <div class="dashboard-cards">

            <!-- ALL RESERVATIONS -->
            <div
                class="summary-card clickable-card reservation-summary-card"
                onclick="openReservationTab('allReservations')"
                role="button"
                tabindex="0"
                onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openReservationTab('allReservations'); }"
            >
                <div class="card-header">
                    <span>My Reservations</span>
                    <span class="card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </span>
                </div>

                <div class="card-number">
                    {{ $allReservations->count() ?? $reservations->count() ?? 0 }}
                </div>

                <div class="card-description">
                    All room reservation requests
                </div>

                <div class="card-mini-stats">
                    <span class="mini pending">
                        <i class="fas fa-clock"></i>
                        {{ $pendingReservationsCount ?? 0 }} Pending
                    </span>
                    <span class="mini approved">
                        <i class="fas fa-check-circle"></i>
                        {{ $approvedReservationsCount ?? 0 }} Approved
                    </span>
                </div>
            </div>

            <!-- PENDING -->
            <div
                class="summary-card clickable-card status-card pending-card"
                onclick="openReservationTab('pendingReservations')"
                role="button"
                tabindex="0"
                onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openReservationTab('pendingReservations'); }"
            >
                <div class="card-header">
                    <span>Pending</span>
                    <span class="card-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </span>
                </div>

                <div class="card-number">
                    {{ $pendingReservationsCount ?? 0 }}
                </div>

                <div class="card-description pending">
                    Waiting for Chair approval
                </div>
            </div>

            <!-- APPROVED -->
            <div
                class="summary-card clickable-card status-card approved-card"
                onclick="openReservationTab('approvedReservations')"
                role="button"
                tabindex="0"
                onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openReservationTab('approvedReservations'); }"
            >
                <div class="card-header">
                    <span>Approved</span>
                    <span class="card-icon">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>

                <div class="card-number">
                    {{ $approvedReservationsCount ?? 0 }}
                </div>

                <div class="card-description approved-text">
                    Reservations approved by Chair
                </div>
            </div>

            <!-- REJECTED -->
            <div
                class="summary-card clickable-card status-card rejected-card"
                onclick="openReservationTab('rejectedReservations')"
                role="button"
                tabindex="0"
                onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openReservationTab('rejectedReservations'); }"
            >
                <div class="card-header">
                    <span>Rejected</span>
                    <span class="card-icon">
                        <i class="fas fa-circle-xmark"></i>
                    </span>
                </div>

                <div class="card-number">
                    {{ $rejectedReservationsCount ?? 0 }}
                </div>

                <div class="card-description rejected-text">
                    Reservation requests not approved
                </div>
            </div>

        </div>

        <!-- DASHBOARD ALERTS -->
        @if(session('success'))
            <div class="dashboard-alert success">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="dashboard-alert error">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- RESERVATION SECTION -->

        <div class="reservation-section">


            <!-- TABS -->

            <div class="reservation-tabs">

                <button
                    class="tab-btn active"
                    onclick="showTab('allReservations', this)"
                >
                    <i class="fas fa-calendar-days"></i>
                    My Reservations
                </button>

                <button
                    class="tab-btn"
                    onclick="showTab('pendingReservations', this)"
                >
                    <i class="fas fa-clock"></i>
                    Pending
                    <span class="tab-count">{{ $pendingReservationsCount ?? 0 }}</span>
                </button>

                <button
                    class="tab-btn"
                    onclick="showTab('approvedReservations', this)"
                >
                    <i class="fas fa-check-circle"></i>
                    Approved
                    <span class="tab-count">{{ $approvedReservationsCount ?? 0 }}</span>
                </button>

                <button
                    class="tab-btn"
                    onclick="showTab('rejectedReservations', this)"
                >
                    <i class="fas fa-xmark-circle"></i>
                    Rejected
                    <span class="tab-count">{{ $rejectedReservationsCount ?? 0 }}</span>
                </button>

                <button
                    class="tab-btn"
                    onclick="showTab('swapRequests', this)"
                >
                    <i class="fas fa-right-left"></i>
                    Room Swap Requests
                </button>

                <button
                    class="tab-btn"
                    onclick="showTab('swapped', this)"
                >
                    <i class="fas fa-right-left"></i>
                    Room Swap History
                </button>

               

            </div>



            <!-- ================================================= -->
            <!-- MY RESERVATIONS - ALL -->
            <!-- ================================================= -->

            @php
                $displayReservations = $allReservations ?? $reservations ?? collect();

                $reservationGroups = [
                    'allReservations' => $displayReservations,
                    'pendingReservations' => $displayReservations->filter(fn($r) => $r->status === 'Pending'),
                    'approvedReservations' => $displayReservations->filter(fn($r) => $r->status === 'Approved'),
                    'rejectedReservations' => $displayReservations->filter(fn($r) => $r->status === 'Declined'),
                ];
            @endphp

            @foreach($reservationGroups as $tabId => $reservationCollection)

                <div
                    id="{{ $tabId }}"
                    class="tab-content {{ $tabId === 'allReservations' ? 'active' : '' }}"
                >

                    <div class="reservation-list">

                        <div class="reservation-list-header">
                            <div>
                                <h3>
                                    @if($tabId === 'allReservations')
                                        My Room Reservations
                                    @elseif($tabId === 'pendingReservations')
                                        Pending Reservations
                                    @elseif($tabId === 'approvedReservations')
                                        Approved Reservations
                                    @else
                                        Rejected Reservations
                                    @endif
                                </h3>

                                <p>
                                    @if($tabId === 'allReservations')
                                        Track all your room reservation requests.
                                    @elseif($tabId === 'pendingReservations')
                                        These requests are waiting for the Chair's decision.
                                    @elseif($tabId === 'approvedReservations')
                                        These reservations have been approved by the Chair.
                                    @else
                                        These reservation requests were not approved.
                                    @endif
                                </p>
                            </div>

                            <span class="reservation-total">
                                {{ $reservationCollection->count() }}
                                {{ $reservationCollection->count() === 1 ? 'request' : 'requests' }}
                            </span>
                        </div>

                        @if($reservationCollection->count() > 0)

                            @foreach($reservationCollection as $reservation)

                                @php
                                    $isExpired = false;

                                    if (
                                        $reservation->status === 'Approved' &&
                                        $reservation->date &&
                                        $reservation->end_time
                                    ) {
                                        try {
                                            $reservationEnd = \Carbon\Carbon::parse(
                                                $reservation->date . ' ' . $reservation->end_time
                                            );

                                            $isExpired = now()->greaterThanOrEqualTo($reservationEnd);
                                        } catch (\Throwable $e) {
                                            $isExpired = false;
                                        }
                                    }

                                    $statusClass = match ($reservation->status) {
                                        'Pending' => 'pending',
                                        'Approved' => $isExpired ? 'expired' : 'approved',
                                        'Declined' => 'rejected',
                                        default => 'pending',
                                    };

                                    $statusLabel = match ($reservation->status) {
                                        'Pending' => 'Pending',
                                        'Approved' => $isExpired ? 'Expired' : 'Approved',
                                        'Declined' => 'Rejected',
                                        default => $reservation->status,
                                    };

                                    $buildingName = '-';

                                    if ($reservation->room?->building) {
                                        if (is_string($reservation->room->building)) {
                                            $buildingName = $reservation->room->building;
                                        } else {
                                            $buildingName =
                                                $reservation->room->building->name ??
                                                $reservation->room->building->building_name ??
                                                '-';
                                        }
                                    }
                                @endphp

                                <div class="reservation-card reservation-status-{{ $statusClass }}">

                                    <div class="reservation-card-top">

                                        <div class="reservation-room-heading">
                                            <div class="reservation-room-icon">
                                                <i class="fas fa-door-open"></i>
                                            </div>

                                            <div>
                                                <h3>
                                                    {{ $reservation->room->room_name ?? 'Room unavailable' }}
                                                </h3>

                                                <p class="reservation-building">
                                                    <i class="fas fa-building"></i>
                                                    {{ $buildingName }}
                                                </p>
                                            </div>
                                        </div>

                                        <span class="reservation-status-badge {{ $statusClass }}">
                                            @if($statusClass === 'pending')
                                                <i class="fas fa-clock"></i>
                                            @elseif($statusClass === 'approved')
                                                <i class="fas fa-circle-check"></i>
                                            @elseif($statusClass === 'rejected')
                                                <i class="fas fa-circle-xmark"></i>
                                            @else
                                                <i class="fas fa-calendar-xmark"></i>
                                            @endif

                                            {{ $statusLabel }}
                                        </span>

                                    </div>

                                    <div class="reservation-details">

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar"></i>
                                                Date
                                            </span>
                                            <strong>
                                                {{ $reservation->date ? \Carbon\Carbon::parse($reservation->date)->format('M d, Y') : '-' }}
                                            </strong>
                                        </div>

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-day"></i>
                                                Day
                                            </span>
                                            <strong>
                                                {{ $reservation->day ?? '-' }}
                                            </strong>
                                        </div>

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-clock"></i>
                                                Time
                                            </span>
                                            <strong>
                                                {{ $reservation->time ?? '-' }}
                                            </strong>
                                        </div>

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-bullseye"></i>
                                                Purpose
                                            </span>
                                            <strong>
                                                {{ $reservation->purpose ?? 'Room use' }}
                                            </strong>
                                        </div>

                                    </div>

                                    @if($reservation->status === 'Pending')
                                        <div class="reservation-message pending-message">
                                            <i class="fas fa-hourglass-half"></i>
                                            <span>
                                                Your request has been sent to the Chair and is waiting for approval.
                                            </span>
                                        </div>
                                    @elseif($reservation->status === 'Approved' && !$isExpired)
                                        <div class="reservation-message approved-message">
                                            <i class="fas fa-circle-check"></i>
                                            <span>
                                                Your reservation is approved. You may use the room during the scheduled time.
                                            </span>
                                        </div>
                                    @elseif($reservation->status === 'Declined')
                                        <div class="reservation-message rejected-message">
                                            <i class="fas fa-circle-xmark"></i>
                                            <span>
                                                Your reservation request was rejected by the Chair.
                                            </span>
                                        </div>
                                    @elseif($isExpired)
                                        <div class="reservation-message expired-message">
                                            <i class="fas fa-calendar-xmark"></i>
                                            <span>
                                                This reservation has ended and the room is available again.
                                            </span>
                                        </div>
                                    @endif

                                </div>

                            @endforeach

                        @else

                            <div class="empty-room reservation-empty">

                                <div class="empty-icon">
                                    @if($tabId === 'pendingReservations')
                                        <i class="fas fa-clock"></i>
                                    @elseif($tabId === 'approvedReservations')
                                        <i class="fas fa-calendar-check"></i>
                                    @elseif($tabId === 'rejectedReservations')
                                        <i class="fas fa-circle-xmark"></i>
                                    @else
                                        <i class="fas fa-calendar-plus"></i>
                                    @endif
                                </div>

                                <h3>
                                    @if($tabId === 'allReservations')
                                        No Room Reservations Yet
                                    @elseif($tabId === 'pendingReservations')
                                        No Pending Reservations
                                    @elseif($tabId === 'approvedReservations')
                                        No Approved Reservations
                                    @else
                                        No Rejected Reservations
                                    @endif
                                </h3>

                                <p>
                                    @if($tabId === 'pendingReservations')
                                        You do not have any reservation requests waiting for approval.
                                    @elseif($tabId === 'approvedReservations')
                                        Your approved room reservations will appear here.
                                    @elseif($tabId === 'rejectedReservations')
                                        Rejected reservation requests will appear here.
                                    @else
                                        Your room reservation requests will appear here.
                                    @endif
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            @endforeach


            <!-- ================================================= -->
            <!-- SWAPPED ROOMS -->
            <!-- ================================================= -->

            <div
                id="swapped"
                class="tab-content"
            >

                @if(isset($roomSwapHistory) && $roomSwapHistory->count() > 0)

                    <div class="swap-history-grid">

                        @foreach($roomSwapHistory as $swap)

                            @php
                                $status = strtolower((string) $swap->status);
                                $isApproved = $status === 'approved';
                                $isDeclined = $status === 'declined';
                                $isExpired = $status === 'expired';
                                $isActive = false;

                                if ($isApproved) {
                                    try {
                                        $isActive = $swap->isActive();
                                        $isExpired = $swap->isExpired();
                                    } catch (\Throwable $e) {
                                        $isActive = false;
                                    }
                                }
                            @endphp

                            <div class="swap-history-card">

                                <div class="swap-history-top">
                                    <div>
                                        <h3>
                                            <i class="fas fa-right-left" style="color:#2e7d32;"></i>
                                            Room Swap
                                        </h3>

                                        <p>
                                            With {{ $swap->requester->name ?? 'Unknown Faculty' }}
                                        </p>
                                    </div>

                                    @if($isActive)
                                        <span class="swap-status active">
                                            <i class="fas fa-circle"></i>
                                            Active
                                        </span>
                                    @elseif($isExpired)
                                        <span class="swap-status expired">
                                            <i class="fas fa-clock"></i>
                                            Expired
                                        </span>
                                    @elseif($isDeclined)
                                        <span class="swap-status declined">
                                            <i class="fas fa-xmark"></i>
                                            Declined
                                        </span>
                                    @else
                                        <span class="swap-status expired">
                                            {{ ucfirst($swap->status) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="swap-time-box">
                                    <i class="fas fa-calendar-day"></i>
                                    <strong>{{ $swap->swap_date ? $swap->swap_date->format('M d, Y') : '-' }}</strong>
                                    <span>•</span>
                                    <span>{{ $swap->start_time ? \Carbon\Carbon::parse($swap->start_time)->format('g:i A') : '-' }} – {{ $swap->end_time ? \Carbon\Carbon::parse($swap->end_time)->format('g:i A') : '-' }}</span>
                                </div>

                                <div class="swap-room-flow">

                                    <div class="swap-room-box">
                                        <span>Your Original Room</span>
                                        <strong>
                                            {{ $swap->targetRoom->room_name ?? '-' }}
                                        </strong>
                                    </div>

                                    <div class="swap-room-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>

                                    <div class="swap-room-box">
                                        <span>Temporary Room</span>
                                        <strong>
                                            {{ $swap->requesterRoom->room_name ?? '-' }}
                                        </strong>
                                    </div>

                                </div>

                                @if($isActive)
                                    <div class="swap-expiration">
                                        <i class="fas fa-hourglass-half"></i>
                                        Temporary room assignment is active until
                                        <strong>
                                            {{ $swap->end_time ? \Carbon\Carbon::parse($swap->end_time)->format('g:i A') : '-' }}
                                        </strong>.
                                    </div>
                                @elseif($isExpired)
                                    <div class="swap-expiration">
                                        <i class="fas fa-rotate-left"></i>
                                        The original room assignment has been restored.
                                    </div>
                                @elseif($isDeclined)
                                    <div class="swap-expiration">
                                        <i class="fas fa-ban"></i>
                                        This room swap request was declined.
                                    </div>
                                @endif

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-room">
                        <div class="empty-icon">
                            <i class="fas fa-right-left"></i>
                        </div>
                        <h3>No Room Swap History</h3>
                        <p>Approved, expired, and declined room swaps will appear here.</p>
                    </div>

                @endif

            </div>

<!-- ================================================= -->
<!-- ROOM SWAP REQUESTS -->
<!-- ================================================= -->

<div
    id="swapRequests"
    class="tab-content"
>

    <div class="swap-request-container">

        <div class="swap-request-title">
            <i class="fas fa-right-left"></i>
            Pending Room Swap Requests
        </div>

        @if(isset($roomSwapRequests) && $roomSwapRequests->count() > 0)

            @foreach($roomSwapRequests as $swap)

                <div class="swap-request-card-item">

                    <div class="swap-request-header">

                        <div>
                            <h3>
                                Room Swap Request
                            </h3>

                            <p>
                                From:
                                <strong>
                                    {{ $swap->requester->name ?? 'Unknown Faculty' }}
                                </strong>
                            </p>
                        </div>

                        <span class="swap-pending">
                            Pending
                        </span>

                    </div>


                    <div class="swap-request-comparison">

                        <!-- REQUESTER -->

                        <div class="swap-side">

                            <div class="swap-side-title">
                                Requesting Faculty
                            </div>

                            <h4>
                                {{ $swap->requester->name ?? 'Unknown Faculty' }}
                            </h4>

                            @if($swap->requesterSchedule)

                                <p>
                                    <strong>Course:</strong>
                                    {{ $swap->requesterSchedule->course_code ?? '-' }}
                                </p>

                                <p>
                                    <strong>Subject:</strong>
                                    {{ $swap->requesterSchedule->subject ?? '-' }}
                                </p>

                                <p>
                                    <strong>Day:</strong>
                                    {{ $swap->requesterSchedule->day ?? '-' }}
                                </p>

                                <p>
                                    <strong>Time:</strong>
                                    {{ $swap->requesterSchedule->time ?? '-' }}
                                </p>

                                <p>
                                    <strong>Room:</strong>
                                    {{ $swap->requesterRoom->room_name ?? '-' }}
                                </p>

                            @endif

                        </div>


                        <!-- ARROW -->

                        <div class="swap-arrow">
                            <i class="fas fa-right-left"></i>
                        </div>


                        <!-- TARGET -->

                        <div class="swap-side">

                            <div class="swap-side-title">
                                Your Schedule
                            </div>

                            <h4>
                                {{ Auth::user()->name }}
                            </h4>

                            @if($swap->targetSchedule)

                                <p>
                                    <strong>Course:</strong>
                                    {{ $swap->targetSchedule->course_code ?? '-' }}
                                </p>

                                <p>
                                    <strong>Subject:</strong>
                                    {{ $swap->targetSchedule->subject ?? '-' }}
                                </p>

                                <p>
                                    <strong>Day:</strong>
                                    {{ $swap->targetSchedule->day ?? '-' }}
                                </p>

                                <p>
                                    <strong>Time:</strong>
                                    {{ $swap->targetSchedule->time ?? '-' }}
                                </p>

                                <p>
                                    <strong>Room:</strong>
                                    {{ $swap->targetRoom->room_name ?? '-' }}
                                </p>

                            @endif

                        </div>

                    </div>


                    <!-- ACTIONS -->

                    <div class="swap-request-actions">

                        <form
                            method="POST"
                            action="{{ route('faculty.room-swap.approve', $swap->id) }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="swap-approve-btn"
                                onclick="return confirm('Are you sure you want to accept this room swap?')"
                            >
                                <i class="fas fa-check"></i>
                                Accept Swap
                            </button>

                        </form>


                        <form
                            method="POST"
                            action="{{ route('faculty.room-swap.decline', $swap->id) }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="swap-decline-btn"
                                onclick="return confirm('Are you sure you want to decline this room swap?')"
                            >
                                <i class="fas fa-xmark"></i>
                                Decline
                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        @else

            <div class="empty-room">

                <i class="fas fa-inbox"></i>

                <h3>
                    No Room Swap Requests
                </h3>

                <p>
                    You currently have no pending room swap requests.
                </p>

            </div>

        @endif

    </div>

</div>
        </div>


    </main>

</div>




</div>

@include('footerheader.footer')
<script>

function showTab(tabId, button)
{
    document
        .querySelectorAll('.tab-btn')
        .forEach(function(btn) {
            btn.classList.remove('active');
        });

    document
        .querySelectorAll('.tab-content')
        .forEach(function(content) {
            content.classList.remove('active');
        });

    if (button) {
        button.classList.add('active');
    }

    const target = document.getElementById(tabId);

    if (target) {
        target.classList.add('active');
    }
}


function openReservationTab(tabId)
{
    const buttons = document.querySelectorAll('.tab-btn');

    let targetButton = null;

    buttons.forEach(function(button) {
        const onclickValue = button.getAttribute('onclick') || '';

        if (onclickValue.includes("'" + tabId + "'")) {
            targetButton = button;
        }
    });

    if (targetButton) {
        showTab(tabId, targetButton);

        targetButton.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center'
        });
    }
}


function openSwapRequests()
{
    const buttons =
        document.querySelectorAll('.tab-btn');

    let requestButton = null;

    buttons.forEach(function(button) {

        if (
            button.textContent
                .trim()
                .includes('Room Swap Requests')
        ) {
            requestButton = button;
        }

    });

    if (requestButton) {

        showTab(
            'swapRequests',
            requestButton
        );

        requestButton.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });
    }
}

</script>
