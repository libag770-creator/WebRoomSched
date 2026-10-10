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

        /* =========================================================
           DASHBOARD SUMMARY
           One clean reservation card with the three statuses inside.
        ========================================================== */

        .dashboard-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 22px;
            align-items: stretch;
        }

        .summary-card {
            min-width: 0;
            min-height: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            transition:
                transform .2s ease,
                box-shadow .2s ease,
                border-color .2s ease;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 5px;
            height: 100%;
            background: var(--primary);
        }

        .summary-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(25, 55, 30, .11);
            border-color: #c9dccb;
        }

        .summary-card.clickable-card {
            cursor: pointer;
        }

        .reservation-summary-card {
            width: 100%;
        }

        .reservation-summary-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
        }

        .reservation-summary-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .reservation-summary-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-soft);
            color: var(--primary);
            flex: 0 0 42px;
        }

        .reservation-summary-title h3 {
            margin: 0;
            color: var(--text);
            font-size: 17px;
            font-weight: 800;
        }

        .reservation-summary-title p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .reservation-summary-total {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border-radius: 999px;
            background: #f0f5f0;
            color: var(--primary-dark);
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .reservation-status-filter {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 22px;
            background: #fafcfb;
            border-bottom: 1px solid var(--border);
        }

        .reservation-status-filter-label {
            margin-right: 3px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
        }

        .reservation-filter-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 33px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 11px;
            background: #ffffff;
            color: #657066;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            transition:
                background .18s ease,
                color .18s ease,
                border-color .18s ease,
                transform .18s ease,
                box-shadow .18s ease;
        }

        .reservation-filter-btn:hover {
            transform: translateY(-1px);
            border-color: #c8dacb;
            color: var(--primary);
            box-shadow: 0 4px 12px rgba(25,55,30,.06);
        }

        .reservation-filter-btn.active {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 5px 12px rgba(46,125,50,.16);
        }

        .reservation-filter-count {
            min-width: 19px;
            height: 19px;
            padding: 0 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #eef2ee;
            color: inherit;
            font-size: 9px;
            font-weight: 800;
        }

        .reservation-filter-btn.active .reservation-filter-count {
            background: rgba(255,255,255,.20);
            color: #ffffff;
        }

        .reservation-view-panel.status-hidden {
            display: none;
        }

        .card-header,
        .card-number,
        .card-description,
        .card-mini-stats {
            display: none;
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


        /* =========================================================
           FACULTY DASHBOARD DETAILS
        ========================================================== */

        .faculty-overview {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, .65fr);
            gap: 16px;
            margin-bottom: 22px;
        }

        .overview-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .overview-panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
        }

        .overview-panel-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .overview-panel-title-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-soft);
            color: var(--primary);
            flex: 0 0 36px;
        }

        .overview-panel-title h3 {
            margin: 0;
            color: var(--text);
            font-size: 15px;
            font-weight: 800;
        }

        .overview-panel-title p {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: 11px;
        }

        .overview-date {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            background: #f5f8f5;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: #526056;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .overview-metrics {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            padding: 16px 20px 20px;
        }

        .overview-metric {
            border: 1px solid var(--border);
            border-radius: 11px;
            padding: 12px;
            background: #fbfdfb;
        }

        .overview-metric-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .overview-metric-label i {
            color: var(--primary);
        }

        .overview-metric strong {
            display: block;
            margin-top: 7px;
            color: var(--text);
            font-size: 24px;
            line-height: 1;
        }

        .overview-metric span {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 9px;
            line-height: 1.35;
        }

        .next-reservation-body {
            padding: 18px 20px 20px;
        }

        .next-reservation-empty {
            min-height: 135px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--muted);
        }

        .next-reservation-empty i {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #f1f4f1;
            color: #9aa49c;
            margin-bottom: 9px;
        }

        .next-reservation-empty strong {
            color: var(--text);
            font-size: 13px;
        }

        .next-reservation-empty span {
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .next-reservation-card {
            border: 1px solid #cfe3d2;
            border-radius: 12px;
            background: #f6fbf7;
            padding: 14px;
        }

        .next-reservation-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .next-reservation-room {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .next-reservation-room-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e1f2e4;
            color: var(--primary);
            flex: 0 0 38px;
        }

        .next-reservation-room strong {
            display: block;
            color: var(--text);
            font-size: 13px;
        }

        .next-reservation-room span {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 9px;
        }

        .next-reservation-badge {
            padding: 6px 9px;
            border-radius: 999px;
            background: #e8f6ea;
            color: #216b27;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .next-reservation-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            margin-top: 12px;
        }

        .next-reservation-detail {
            padding: 9px;
            border-radius: 9px;
            background: #ffffff;
            border: 1px solid #deebe0;
        }

        .next-reservation-detail small {
            display: block;
            color: var(--muted);
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .next-reservation-detail strong {
            display: block;
            margin-top: 3px;
            color: var(--text);
            font-size: 10px;
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .quick-link {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
            padding: 11px 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #ffffff;
            color: #37423a;
            text-decoration: none;
            transition: .18s ease;
        }

        .quick-link:hover {
            transform: translateY(-1px);
            border-color: #c9dccb;
            box-shadow: 0 6px 14px rgba(25,55,30,.06);
        }

        .quick-link-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-soft);
            color: var(--primary);
            flex: 0 0 30px;
        }

        .quick-link strong {
            display: block;
            font-size: 10px;
        }

        .quick-link span {
            display: block;
            margin-top: 2px;
            color: var(--muted);
            font-size: 8px;
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
            .swap-history-grid {
                grid-template-columns: 1fr;
            }

            .reservation-status-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .content {
                padding: 18px;
            }

            .faculty-overview {
                grid-template-columns: 1fr;
            }

            .overview-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .quick-links {
                grid-template-columns: 1fr;
            }


            .reservation-status-filter {
                padding: 12px 15px;
            }

            .reservation-status-filter-label {
                width: 100%;
                margin-right: 0;
            }

            .dashboard-header h2 {
                font-size: 24px;
            }

            .reservation-summary-top {
                flex-direction: column;
            }

            .reservation-summary-total {
                align-self: flex-start;
            }

            .reservation-status-grid {
                grid-template-columns: 1fr;
                gap: 9px;
            }

            .reservation-status-card-count {
                font-size: 25px;
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

    <main class="content faculty-dashboard-pro">

        <div class="dashboard-header">

            <div>
                <div class="dashboard-eyebrow">
                    Faculty Workspace
                </div>

                <h2>
                    Faculty Dashboard
                </h2>

                <p>
                    Welcome, {{ Auth::user()->name ?? 'Faculty' }}.
                    View your teaching activity, rooms, and faculty tools in one place.
                </p>
            </div>

            <div class="dashboard-date-card">
                <i class="fas fa-calendar-day"></i>

                <div>
                    <strong id="dashboardDate">
                        {{ now()->format('M d, Y') }}
                    </strong>

                    <span id="dashboardDay">
                        {{ now()->format('l') }}
                    </span>
                </div>
            </div>

        </div>


        <!-- =========================================================
             FACULTY AT A GLANCE
        ========================================================== -->

        <section class="faculty-stats-grid">

            <div class="faculty-stat-card">
                <div class="faculty-stat-icon schedule">
                    <i class="fas fa-calendar-days"></i>
                </div>

                <div>
                    <span class="faculty-stat-label">
                        My Classes
                    </span>

                    <strong>
                        {{ $weeklyScheduleCount ?? 0 }}
                    </strong>

                    <small>
                        Assigned schedule entries
                    </small>
                </div>
            </div>


            <div class="faculty-stat-card">
                <div class="faculty-stat-icon today">
                    <i class="fas fa-clock"></i>
                </div>

                <div>
                    <span class="faculty-stat-label">
                        Today's Classes
                    </span>

                    <strong>
                        {{ $todayScheduleCount ?? 0 }}
                    </strong>

                    <small>
                        Published classes today
                    </small>
                </div>
            </div>


            <div class="faculty-stat-card">
                <div class="faculty-stat-icon rooms">
                    <i class="fas fa-door-open"></i>
                </div>

                <div>
                    <span class="faculty-stat-label">
                        Campus Rooms
                    </span>

                    <strong>
                        {{ $totalRooms ?? 0 }}
                    </strong>

                    <small>
                        Registered classrooms
                    </small>
                </div>
            </div>


            <div class="faculty-stat-card">
                <div class="faculty-stat-icon swap">
                    <i class="fas fa-right-left"></i>
                </div>

                <div>
                    <span class="faculty-stat-label">
                        Room Swap Requests
                    </span>

                    <strong>
                        {{ isset($roomSwapRequests) ? $roomSwapRequests->count() : 0 }}
                    </strong>

                    <small>
                        Waiting for your action
                    </small>
                </div>
            </div>

        </section>


        <!-- =========================================================
             TODAY'S SCHEDULE + QUICK ACCESS
        ========================================================== -->

        <section class="dashboard-workspace">

            @php
                // Requests sent by the currently logged-in faculty member.
                $swapRelations = [
                    'requester', 'targetUser', 'requesterRoom', 'targetRoom',
                    'requesterSchedule', 'targetSchedule'
                ];

                // Outgoing requests belong to the logged-in faculty member.
                $myRoomSwapRequests = \App\Models\RoomSwapRequest::with($swapRelations)
                    ->where('requester_id', Auth::id())
                    ->latest()
                    ->get();

                

/*
|--------------------------------------------------------------------------
| INCOMING ROOM SWAP REQUESTS
|--------------------------------------------------------------------------
| Only requests addressed to the currently logged-in faculty member.
| These are the requests that must appear under "Others' Requests".
|--------------------------------------------------------------------------
*/

$pendingIncomingSwapRequests = \App\Models\RoomSwapRequest::with([
    'requester',
    'targetUser',
    'requesterRoom',
    'targetRoom',
    'requesterSchedule',
    'targetSchedule',
])
    ->where(function ($query) {
        $faculty = Auth::user();

        // Primary match: target_user_id
        $query->where('target_user_id', $faculty->id);

        // Fallback: match the target schedule instructor
        if (!empty($faculty->name)) {
            $query->orWhereHas('targetSchedule', function ($scheduleQuery) use ($faculty) {
                $scheduleQuery->whereRaw(
                    'LOWER(TRIM(instructor)) = ?',
                    [mb_strtolower(trim($faculty->name))]
                );
            });
        }
    })
    ->whereRaw("LOWER(TRIM(status)) = ?", ['pending'])
    ->latest()
    ->get();

            @endphp

            <div class="dashboard-panel schedule-panel room-swap-dashboard-card">
                <div class="dashboard-panel-header">
                    <div class="panel-title">
                        <span class="panel-title-icon"><i class="fas fa-right-left"></i></span>
                        <div>
                            <h3>Room Swap Center</h3>
                            <p>Track the requests you sent or review requests from other faculty.</p>
                        </div>
                    </div>
                    <a href="{{ route('faculty.room-swap') }}" class="panel-action">
                        New Room Swap <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="swap-dashboard-tabs" role="tablist" aria-label="Room swap request sections">
                    <button type="button" class="swap-dashboard-tab active" id="mySwapTab"
                            role="tab" aria-selected="true" aria-controls="mySwapPanel"
                            onclick="switchRoomSwapTab('my')">
                        <i class="fas fa-paper-plane"></i>
                        My Room Swap Requests
                        <span class="swap-tab-count">{{ $myRoomSwapRequests->count() }}</span>
                    </button>
                    <button type="button" class="swap-dashboard-tab" id="receivedSwapTab"
                            role="tab" aria-selected="false" aria-controls="receivedSwapPanel"
                            onclick="switchRoomSwapTab('received')">
                        <i class="fas fa-inbox"></i>
                        Others’ Requests
                        <span class="swap-tab-count">{{ $pendingIncomingSwapRequests->count() }}</span>
                    </button>
                </div>

                {{-- MY REQUESTS: outgoing requests and their current status --}}
                <div class="swap-tab-panel" id="mySwapPanel" role="tabpanel" aria-labelledby="mySwapTab">
                    <div class="swap-tab-intro">Requests you sent to other faculty members.</div>
                    <div class="swap-request-container">
                        @forelse($myRoomSwapRequests as $swap)
                            @php
                                $requesterRoomName = $swap->requesterRoom?->room_name ?? $swap->requesterRoom?->name ?? 'Room not assigned';
                                $targetRoomName = $swap->targetRoom?->room_name ?? $swap->targetRoom?->name ?? 'Room not assigned';
                                $swapDay = $swap->requesterSchedule?->day ?? $swap->targetSchedule?->day ?? $swap->swap_date ?? 'N/A';
                                $swapTime = $swap->requesterSchedule?->time ?? $swap->targetSchedule?->time ?? (($swap->start_time && $swap->end_time) ? $swap->start_time.' - '.$swap->end_time : 'N/A');
                                $swapStatus = strtolower((string) $swap->status);
                            @endphp
                            <article class="swap-request-card-item outgoing-swap-card status-{{ $swapStatus }}">
                                <div class="swap-request-header">
                                    <div class="swap-request-person">
                                        <span class="swap-request-avatar"><i class="fas fa-user"></i></span>
                                        <div>
                                            <h4>To: {{ $swap->targetUser?->name ?? 'Unknown Faculty' }}</h4>
                                            <p>You requested to exchange room assignments.</p>
                                        </div>
                                    </div>
                                    <span class="swap-state-badge {{ $swapStatus }}">
                                        @if($swapStatus === 'pending') <i class="fas fa-clock"></i> Pending
                                        @elseif($swapStatus === 'approved') <i class="fas fa-circle"></i> Room Swap Active
                                        @elseif($swapStatus === 'expired') <i class="fas fa-rotate-left"></i> Completed
                                        @elseif($swapStatus === 'declined') <i class="fas fa-times-circle"></i> Declined
                                        @elseif($swapStatus === 'cancelled') <i class="fas fa-ban"></i> Cancelled
                                        @else {{ ucfirst($swapStatus ?: 'Unknown') }} @endif
                                    </span>
                                </div>
                                <div class="swap-room-comparison">
                                    <div class="swap-room-box"><span class="swap-room-label">Your Current Room</span><strong>{{ $requesterRoomName }}</strong></div>
                                    <span class="swap-exchange-icon"><i class="fas fa-right-left"></i></span>
                                    <div class="swap-room-box"><span class="swap-room-label">Requested Room</span><strong>{{ $targetRoomName }}</strong></div>
                                </div>
                                <div class="swap-request-meta">
                                    <div><span><i class="fas fa-calendar-day"></i> Day / Date</span><strong>{{ $swapDay }}</strong></div>
                                    <div><span><i class="fas fa-clock"></i> Schedule Time</span><strong>{{ $swapTime }}</strong></div>
                                </div>
                                @if($swap->reason)
                                    <div class="swap-request-reason"><i class="fas fa-comment-dots"></i><div><strong>Your Reason</strong><p>{{ $swap->reason }}</p></div></div>
                                @endif
                                @if($swapStatus === 'pending')
                                    <div class="swap-status-note"><i class="fas fa-hourglass-half"></i> Waiting for {{ $swap->targetUser?->name ?? 'the other faculty member' }} to accept or decline.</div>

                                    <div class="swap-cancel-action">
                                        <form method="POST"
                                              action="{{ route('faculty.room-swap.cancel', $swap->id) }}"
                                              onsubmit="return confirm('Cancel this room swap request? The other faculty member will no longer be able to accept it, and no room assignments will be changed.');">
                                            @csrf
                                            <button type="submit" class="swap-cancel-button">
                                                <i class="fas fa-ban"></i>
                                                Cancel Room Swap Request
                                            </button>
                                        </form>
                                    </div>
                                @elseif($swapStatus === 'approved')
                                    <div class="swap-status-note success"><i class="fas fa-circle"></i> Accepted. The two selected schedules are temporarily using each other’s rooms until {{ $swap->end_time ? \Carbon\Carbon::parse($swap->end_time)->format('g:i A') : 'the scheduled end time' }}.</div>
                                @elseif($swapStatus === 'expired')
                                    <div class="swap-status-note expired"><i class="fas fa-rotate-left"></i> The temporary room swap has ended and the original room assignments have been restored.</div>
                                @elseif($swapStatus === 'declined')
                                    <div class="swap-status-note declined"><i class="fas fa-info-circle"></i> This request was declined. Your schedule was not changed.</div>
                                @elseif($swapStatus === 'cancelled')
                                    <div class="swap-status-note cancelled"><i class="fas fa-ban"></i> You cancelled this room swap request. No room assignments were changed.</div>
                                @endif
                            </article>
                        @empty
                            <div class="schedule-empty-state">
                                <span class="schedule-empty-icon"><i class="fas fa-paper-plane"></i></span>
                                <strong>You haven’t sent any room swap requests</strong>
                                <p>When you send a request, you can track its status here.</p>
                                <a href="{{ route('faculty.room-swap') }}" class="empty-state-button"><i class="fas fa-right-left"></i> Create Room Swap Request</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- OTHERS' REQUESTS: only incoming pending requests with approve/decline actions --}}
                <div class="swap-tab-panel" id="receivedSwapPanel" role="tabpanel" aria-labelledby="receivedSwapTab" hidden>
                    <div class="swap-tab-intro">Requests sent to you by other faculty members. Accept or decline them here.</div>
                    <div class="swap-request-container">
                        @forelse($pendingIncomingSwapRequests as $swap)
                            @php
                                $requesterRoomName = $swap->requesterRoom?->room_name ?? $swap->requesterRoom?->name ?? 'Room not assigned';
                                $targetRoomName = $swap->targetRoom?->room_name ?? $swap->targetRoom?->name ?? 'Room not assigned';
                                $swapDay = $swap->targetSchedule?->day ?? $swap->requesterSchedule?->day ?? $swap->swap_date ?? 'N/A';
                                $swapTime = $swap->targetSchedule?->time ?? $swap->requesterSchedule?->time ?? (($swap->start_time && $swap->end_time) ? $swap->start_time.' - '.$swap->end_time : 'N/A');
                            @endphp
                            <article class="swap-request-card-item incoming-swap-card">
                                <div class="swap-request-header">
                                    <div class="swap-request-person">
                                        <span class="swap-request-avatar"><i class="fas fa-user"></i></span>
                                        <div><h4>{{ $swap->requester?->name ?? 'Unknown Faculty' }}</h4><p>Requested to exchange classroom assignments with you.</p></div>
                                    </div>
                                    <span class="swap-state-badge pending"><i class="fas fa-clock"></i> Pending Your Action</span>
                                </div>
                                <div class="swap-room-comparison">
                                    <div class="swap-room-box"><span class="swap-room-label">Your Current Room</span><strong>{{ $targetRoomName }}</strong></div>
                                    <span class="swap-exchange-icon"><i class="fas fa-right-left"></i></span>
                                    <div class="swap-room-box"><span class="swap-room-label">Room You Will Receive</span><strong>{{ $requesterRoomName }}</strong></div>
                                </div>
                                <div class="swap-request-meta">
                                    <div><span><i class="fas fa-calendar-day"></i> Day / Date</span><strong>{{ $swapDay }}</strong></div>
                                    <div><span><i class="fas fa-clock"></i> Schedule Time</span><strong>{{ $swapTime }}</strong></div>
                                </div>
                                <div class="swap-request-reason"><i class="fas fa-comment-dots"></i><div><strong>Reason</strong><p>{{ $swap->reason ?: 'No reason provided.' }}</p></div></div>
                                <div class="swap-action-buttons">
                                    <form method="POST" action="{{ route('faculty.room-swap.approve', $swap->id) }}" onsubmit="return confirm('Accept this room swap request? The two selected schedules will exchange rooms.');">
                                        @csrf
                                        <button type="submit" class="swap-accept-button"><i class="fas fa-check"></i> Accept Request</button>
                                    </form>
                                    <form method="POST" action="{{ route('faculty.room-swap.decline', $swap->id) }}" onsubmit="return confirm('Decline this room swap request?');">
                                        @csrf
                                        <button type="submit" class="swap-decline-button"><i class="fas fa-times"></i> Decline Request</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="schedule-empty-state">
                                <span class="schedule-empty-icon"><i class="fas fa-inbox"></i></span>
                                <strong>No Pending Requests From Others</strong>
                                <p>New room swap requests sent to you will appear here.</p>
                                <a href="{{ route('faculty.room-swap') }}" class="empty-state-button"><i class="fas fa-right-left"></i> Room Swap Page</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <style>
                .room-swap-dashboard-card { overflow: hidden; }
                .swap-dashboard-tabs { display:flex; gap:8px; padding:16px 20px 0; border-bottom:1px solid var(--border); flex-wrap:wrap; }
                .swap-dashboard-tab { display:inline-flex; align-items:center; justify-content:center; gap:9px; border:1px solid transparent; border-bottom:3px solid transparent; background:transparent; color:#657168; padding:12px 15px; font-weight:800; font-size:13px; cursor:pointer; border-radius:10px 10px 0 0; transition:all .18s ease; }
                .swap-dashboard-tab:hover { background:#f4f8f4; color:var(--primary-dark); }
                .swap-dashboard-tab.active { color:var(--primary-dark); background:#eef7ef; border-bottom-color:var(--primary); }
                .swap-tab-count { display:inline-flex; align-items:center; justify-content:center; min-width:23px; height:23px; padding:0 7px; border-radius:999px; background:#e7ece8; color:#445148; font-size:11px; }
                .swap-dashboard-tab.active .swap-tab-count { background:var(--primary); color:#fff; }
                .swap-tab-panel[hidden] { display:none !important; }
                .swap-tab-intro { padding:15px 22px 0; color:var(--muted); font-size:12px; }
                .swap-state-badge { display:inline-flex; align-items:center; gap:6px; padding:7px 10px; border-radius:999px; font-size:10px; font-weight:800; white-space:nowrap; }
                .swap-state-badge.pending { background:#fff0d2; color:#985700; }
                .swap-state-badge.approved { background:#ffe8e8; color:#b32626; }
                .swap-state-badge.expired { background:#eef1ef; color:#5f6963; }
                .swap-state-badge.declined { background:#fde8e7; color:#a12828; }
                .outgoing-swap-card.status-pending { border-left:4px solid #ed9b20; }
                .outgoing-swap-card.status-approved { border-left:4px solid #d13b3b; }
                .outgoing-swap-card.status-expired { border-left:4px solid #8b9690; }
                .outgoing-swap-card.status-declined { border-left:4px solid #c94343; }
                .outgoing-swap-card.status-cancelled { border-left:4px solid #7b8580; }
                .swap-status-note { display:flex; gap:8px; align-items:flex-start; margin-top:14px; padding:11px 13px; border-radius:9px; background:#fff6e5; color:#8b590c; font-size:12px; font-weight:650; }
                .swap-cancel-action { display:flex; justify-content:flex-end; margin-top:10px; }
                .swap-cancel-action form { margin:0; }
                .swap-cancel-button {
                    display:inline-flex;
                    align-items:center;
                    justify-content:center;
                    gap:7px;
                    min-height:36px;
                    padding:8px 12px;
                    border:1px solid #e2bcbc;
                    border-radius:9px;
                    background:#fff5f5;
                    color:#b32626;
                    font-size:11px;
                    font-weight:800;
                    cursor:pointer;
                    transition:background .16s ease, border-color .16s ease, transform .16s ease;
                }
                .swap-cancel-button:hover {
                    background:#ffe8e8;
                    border-color:#d99696;
                    transform:translateY(-1px);
                }
                .swap-status-note.success { background:#fff0f0; color:#a12828; }
                .swap-status-note.expired { background:#eef1ef; color:#5f6963; }
                .swap-status-note.declined { background:#fff0ef; color:#a12828; }
                .swap-status-note.cancelled { background:#f0f2f1; color:#5f6963; }
                @media(max-width:650px) { .swap-dashboard-tabs { padding:12px 12px 0; } .swap-dashboard-tab { flex:1 1 100%; justify-content:flex-start; border-radius:9px; border:1px solid var(--border); } .swap-dashboard-tab.active { border-color:#b9d8be; } .swap-tab-intro { padding:12px 14px 0; } }
            </style>

            <script>
                function switchRoomSwapTab(tab) {
                    const mine = tab === 'my';
                    const myPanel = document.getElementById('mySwapPanel');
                    const receivedPanel = document.getElementById('receivedSwapPanel');
                    const myButton = document.getElementById('mySwapTab');
                    const receivedButton = document.getElementById('receivedSwapTab');
                    if (!myPanel || !receivedPanel || !myButton || !receivedButton) return;
                    myPanel.hidden = !mine;
                    receivedPanel.hidden = mine;
                    myButton.classList.toggle('active', mine);
                    receivedButton.classList.toggle('active', !mine);
                    myButton.setAttribute('aria-selected', mine ? 'true' : 'false');
                    receivedButton.setAttribute('aria-selected', mine ? 'false' : 'true');
                }
            </script>

            <style>
                .swap-pending-count {
                    display: inline-flex;
                    align-items: center;
                    padding: 7px 10px;
                    border-radius: 999px;
                    background: #fff5dc;
                    color: #8b6100;
                    font-size: 10px;
                    font-weight: 800;
                    white-space: nowrap;
                }
                .swap-request-container { padding: 14px; }
                .swap-request-card-item {
                    padding: 16px;
                    margin-bottom: 13px;
                    border: 1px solid var(--border);
                    border-left: 4px solid #d99a00;
                    border-radius: 12px;
                    background: #fff;
                }
                .swap-request-card-item:last-child { margin-bottom: 0; }
                .swap-request-header {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 12px;
                    margin-bottom: 15px;
                }
                .swap-request-person { display: flex; align-items: center; gap: 10px; min-width: 0; }
                .swap-request-avatar {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 38px;
                    height: 38px;
                    flex: 0 0 38px;
                    border-radius: 11px;
                    background: var(--primary-soft);
                    color: var(--primary);
                }
                .swap-request-person h4 { margin: 0 0 4px; color: var(--text); font-size: 13px; }
                .swap-request-person p { margin: 0; color: var(--muted); font-size: 10px; line-height: 1.45; }
                .swap-pending-label {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    padding: 6px 8px;
                    border-radius: 999px;
                    background: #fff5dc;
                    color: #8b6100;
                    font-size: 9px;
                    font-weight: 800;
                    white-space: nowrap;
                }
                .swap-room-comparison { display: grid; grid-template-columns: minmax(0,1fr) 26px minmax(0,1fr); align-items: center; gap: 8px; }
                .swap-room-box { min-width: 0; padding: 11px; border: 1px solid #e2e9e3; border-radius: 10px; background: #f8faf8; }
                .swap-room-label { display: block; margin-bottom: 5px; color: var(--muted); font-size: 9px; font-weight: 800; text-transform: uppercase; }
                .swap-room-box strong { display: block; color: var(--text); font-size: 12px; overflow-wrap: anywhere; }
                .swap-exchange-icon { color: var(--primary); text-align: center; font-size: 12px; }
                .swap-request-meta { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 8px; margin-top: 10px; }
                .swap-request-meta > div { padding: 10px; border-radius: 9px; background: #f8faf8; }
                .swap-request-meta span { display: block; margin-bottom: 5px; color: var(--muted); font-size: 9px; }
                .swap-request-meta span i { margin-right: 4px; color: var(--primary); }
                .swap-request-meta strong { display: block; color: var(--text); font-size: 11px; overflow-wrap: anywhere; }
                .swap-request-reason { display: flex; align-items: flex-start; gap: 9px; margin-top: 10px; padding: 11px; border: 1px solid #f1dfae; border-radius: 9px; background: #fff8e8; color: #795600; }
                .swap-request-reason > i { margin-top: 2px; }
                .swap-request-reason strong { display: block; font-size: 10px; }
                .swap-request-reason p { margin: 4px 0 0; font-size: 11px; line-height: 1.45; overflow-wrap: anywhere; }
                .swap-action-buttons { display: flex; gap: 9px; margin-top: 14px; }
                .swap-action-buttons form { flex: 1; min-width: 0; margin: 0; }
                .swap-action-buttons button { width: 100%; min-height: 39px; padding: 9px 10px; border: 0; border-radius: 8px; color: #fff; font-size: 10px; font-weight: 800; cursor: pointer; transition: opacity .2s ease, transform .2s ease; }
                .swap-action-buttons button:hover { opacity: .9; transform: translateY(-1px); }
                .swap-accept-button { background: #2e7d32; }
                .swap-decline-button { background: #c62828; }
                @media (max-width: 520px) {
                    .swap-request-header { flex-direction: column; }
                    .swap-room-comparison { grid-template-columns: 1fr; }
                    .swap-exchange-icon { transform: rotate(90deg); }
                    .swap-action-buttons { flex-direction: column; }
                }
            </style>

            <div class="dashboard-side-column">

                <div class="dashboard-panel campus-snapshot-panel">
                    <div class="dashboard-panel-header">
                        <div class="panel-title">
                            <span class="panel-title-icon"><i class="fas fa-chart-pie"></i></span>
                            <div>
                                <h3>Campus Snapshot</h3>
                                <p>System-wide scheduling and resource overview.</p>
                            </div>
                        </div>
                    </div>
                    <div class="campus-snapshot-grid">
                        <div class="campus-snapshot-item"><span>Active schedules</span><strong>{{ number_format($systemActiveScheduleCount ?? 0) }}</strong></div>
                        <div class="campus-snapshot-item"><span>Today’s classes</span><strong>{{ number_format($systemTodayScheduleCount ?? 0) }}</strong></div>
                        <div class="campus-snapshot-item"><span>Departments</span><strong>{{ number_format($totalDepartments ?? 0) }}</strong></div>
                        <div class="campus-snapshot-item"><span>Buildings</span><strong>{{ number_format($totalBuildings ?? 0) }}</strong></div>
                        <div class="campus-snapshot-item"><span>Faculty members</span><strong>{{ number_format($systemFacultyCount ?? 0) }}</strong></div>
                        <div class="campus-snapshot-item"><span>Room reservations</span><strong>{{ number_format($currentReservationsCount ?? 0) }}</strong></div>
                    </div>
                </div>
                <div class="dashboard-panel">

                    <div class="dashboard-panel-header">

                        <div class="panel-title">

                            <span class="panel-title-icon">
                                <i class="fas fa-circle-info"></i>
                            </span>

                            <div>
                                <h3>
                                    Today at a Glance
                                </h3>

                                <p>
                                    Current faculty activity.
                                </p>
                            </div>

                        </div>

                    </div>


                    <div class="today-info">

                        <div class="today-clock">
                            <span>Current Time</span>

                            <strong id="liveFacultyClock">
                                {{ now()->format('h:i:s A') }}
                            </strong>
                        </div>


                        <div class="today-info-row">
                            <span>
                                <i class="fas fa-calendar-day"></i>
                                Today's Classes
                            </span>

                            <strong>
                                {{ $todayScheduleCount ?? 0 }}
                            </strong>
                        </div>


                        <div class="today-info-row">
                            <span>
                                <i class="fas fa-layer-group"></i>
                                Total Classes
                            </span>

                            <strong>
                                {{ $weeklyScheduleCount ?? 0 }}
                            </strong>
                        </div>


                        <div class="today-info-row">
                            <span>
                                <i class="fas fa-door-open"></i>
                                Campus Rooms
                            </span>

                            <strong>
                                {{ $totalRooms ?? 0 }}
                            </strong>
                        </div>


                        <div class="today-info-row">
                            <span>
                                <i class="fas fa-right-left"></i>
                                Swap Requests
                            </span>

                            <strong>
                                {{ isset($roomSwapRequests) ? $roomSwapRequests->count() : 0 }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

        </section>


    </main>

</div>


<style>
.dashboard-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
}

.dashboard-eyebrow {
    margin-bottom: 5px;
    color: var(--primary);
    font-size: 10px;
    font-weight: 900;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.dashboard-date-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 13px;
    border: 1px solid var(--border);
    border-radius: 11px;
    background: var(--surface);
    box-shadow: 0 4px 15px rgba(25,55,30,.05);
    white-space: nowrap;
}

.dashboard-date-card > i {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: var(--primary-soft);
    color: var(--primary);
}

.dashboard-date-card strong {
    display: block;
    color: var(--text);
    font-size: 11px;
}

.dashboard-date-card span {
    display: block;
    margin-top: 2px;
    color: var(--muted);
    font-size: 9px;
}

.faculty-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 18px;
}

.faculty-stat-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
    padding: 15px;
    border: 1px solid var(--border);
    border-radius: 13px;
    background: var(--surface);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.faculty-stat-card::after {
    content: '';
    position: absolute;
    width: 70px;
    height: 70px;
    right: -28px;
    bottom: -35px;
    border-radius: 50%;
    background: rgba(46,125,50,.05);
}

.faculty-stat-icon {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
}

.faculty-stat-icon.schedule {
    background: #eaf6ec;
    color: #2e7d32;
}

.faculty-stat-icon.today {
    background: #fff6df;
    color: #a46f00;
}

.faculty-stat-icon.rooms {
    background: #eef4fb;
    color: #3e6b9e;
}

.faculty-stat-icon.swap {
    background: #f1ecf8;
    color: #72509a;
}

.faculty-stat-label {
    display: block;
    color: var(--muted);
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
}

.faculty-stat-card strong {
    display: block;
    margin-top: 3px;
    color: var(--text);
    font-size: 25px;
    line-height: 1;
}

.faculty-stat-card small {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 9px;
    line-height: 1.35;
}

.dashboard-workspace {
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(300px, .75fr);
    gap: 16px;
    margin-bottom: 18px;
}

.dashboard-panel {
    min-width: 0;
    border: 1px solid var(--border);
    border-radius: 16px;
    background: var(--surface);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.dashboard-panel-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 17px 19px;
    border-bottom: 1px solid var(--border);
}

.panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.panel-title-icon {
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: var(--primary-soft);
    color: var(--primary);
}

.panel-title h3 {
    margin: 0;
    color: var(--text);
    font-size: 15px;
    font-weight: 800;
}

.panel-title p {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 10px;
}

.panel-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding-top: 8px;
    color: var(--primary);
    text-decoration: none;
    font-size: 9px;
    font-weight: 800;
    white-space: nowrap;
}

.today-schedule-list {
    padding: 10px 14px 14px;
}

.today-schedule-item {
    display: grid;
    grid-template-columns: 125px minmax(0, 1fr);
    gap: 13px;
    padding: 14px 5px;
    border-bottom: 1px solid #eef1ee;
}

.today-schedule-item:last-child {
    border-bottom: 0;
}

.schedule-time-block {
    min-height: 52px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    border-radius: 11px;
    background: #f7faf7;
    border: 1px solid #e2e9e3;
    color: var(--primary);
    text-align: center;
}

.schedule-time-block strong {
    color: var(--text);
    font-size: 10px;
    line-height: 1.35;
}

.schedule-class-info {
    min-width: 0;
}

.schedule-code-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
}

.schedule-code-row > strong {
    color: var(--primary-dark);
    font-size: 12px;
}

.schedule-type-badge {
    padding: 4px 7px;
    border-radius: 999px;
    background: #eef2ee;
    color: #5d675f;
    font-size: 8px;
    font-weight: 800;
}

.schedule-class-info h4 {
    margin: 4px 0 8px;
    color: var(--text);
    font-size: 14px;
    line-height: 1.25;
}

.schedule-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.schedule-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 7px;
    border-radius: 7px;
    background: #f8faf8;
    color: #5f6861;
    font-size: 8px;
    font-weight: 700;
}

.schedule-meta i {
    color: var(--primary);
}

.schedule-empty-state {
    min-height: 260px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 30px;
}

.schedule-empty-icon {
    width: 54px;
    height: 54px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
    background: #f1f4f1;
    color: #9aa49c;
    font-size: 22px;
}

.schedule-empty-state strong {
    margin-top: 12px;
    color: var(--text);
    font-size: 13px;
}

.schedule-empty-state p {
    max-width: 350px;
    margin: 5px 0 12px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.5;
}

.empty-state-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 11px;
    border-radius: 8px;
    background: var(--primary);
    color: #ffffff;
    text-decoration: none;
    font-size: 9px;
    font-weight: 800;
}

.dashboard-side-column {
    display: grid;
    gap: 16px;
    align-content: start;
}

.today-info {
    padding: 14px 17px 17px;
}

.today-clock {
    padding: 13px;
    border-radius: 11px;
    background: linear-gradient(135deg, #eff8f1, #f9fcf9);
    border: 1px solid #d6ead9;
}

.today-clock span {
    display: block;
    color: var(--muted);
    font-size: 8px;
    font-weight: 800;
    text-transform: uppercase;
}

.today-clock strong {
    display: block;
    margin-top: 6px;
    color: var(--primary-dark);
    font-size: 20px;
    line-height: 1;
}

.today-info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 11px 0;
    border-bottom: 1px solid #eef1ee;
}

.today-info-row:last-child {
    border-bottom: 0;
}

.today-info-row span {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: var(--muted);
    font-size: 9px;
}

.today-info-row i {
    color: var(--primary);
}

.today-info-row strong {
    color: var(--text);
    font-size: 11px;
}

.room-swap-notice {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    padding: 13px 15px;
    border: 1px solid #eadcb2;
    border-radius: 12px;
    background: #fffaf0;
}

.room-swap-notice-icon {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #fff0c7;
    color: #9b7000;
}

.room-swap-notice-content {
    min-width: 0;
    flex: 1;
}

.room-swap-notice-content strong {
    display: block;
    color: #765500;
    font-size: 11px;
}

.room-swap-notice-content span {
    display: block;
    margin-top: 3px;
    color: #8a6b22;
    font-size: 9px;
}

.room-swap-notice-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 11px;
    border-radius: 8px;
    background: #9b7000;
    color: #ffffff;
    text-decoration: none;
    font-size: 9px;
    font-weight: 800;
    white-space: nowrap;
}

@media (max-width: 1100px) {
    .faculty-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-workspace {
        grid-template-columns: 1fr;
    }

    .dashboard-side-column {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 760px) {
    .dashboard-header {
        flex-direction: column;
    }

    .dashboard-date-card {
        align-self: flex-start;
    }

    .faculty-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-side-column {
        grid-template-columns: 1fr;
    }

    .today-schedule-item {
        grid-template-columns: 1fr;
    }

    .schedule-time-block {
        align-items: flex-start;
        flex-direction: row;
        padding: 8px 10px;
    }

    .room-swap-notice {
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .room-swap-notice-button {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .faculty-stats-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-panel-header {
        flex-direction: column;
    }

    .panel-action {
        padding-top: 0;
    }
}

/* Refined faculty dashboard theme: keeps the existing schedule,
   reservation and room-swap content while removing quick navigation. */
.faculty-dashboard-pro { background:#f4f7f5 !important; padding:30px !important; }
.faculty-dashboard-pro .dashboard-header {
    position:relative; overflow:hidden; align-items:center !important; min-height:174px;
    padding:30px 34px !important; margin-bottom:22px !important; border-radius:20px;
    color:#fff; background:linear-gradient(115deg,#123d2d,#155c3e 65%,#16734b);
    box-shadow:0 15px 34px rgba(18,61,45,.13);
}
.faculty-dashboard-pro .dashboard-header::after {
    content:""; position:absolute; width:290px; height:290px; right:130px; top:-170px;
    border:1px solid rgba(255,255,255,.12); border-radius:50%;
    box-shadow:0 0 0 35px rgba(255,255,255,.035),0 0 0 75px rgba(255,255,255,.025);
    pointer-events:none;
}
.faculty-dashboard-pro .dashboard-header > div:first-child,
.faculty-dashboard-pro .dashboard-date-card { position:relative; z-index:1; }
.faculty-dashboard-pro .dashboard-eyebrow { color:#bfe0ca !important; font-size:10px !important; letter-spacing:.14em !important; }
.faculty-dashboard-pro .dashboard-header h2 { color:#fff !important; font-size:30px !important; letter-spacing:-.04em; }
.faculty-dashboard-pro .dashboard-header p { color:rgba(255,255,255,.76) !important; line-height:1.6; }
.faculty-dashboard-pro .dashboard-date-card { border-color:rgba(255,255,255,.18) !important; background:rgba(255,255,255,.09) !important; box-shadow:none !important; }
.faculty-dashboard-pro .dashboard-date-card > i { background:rgba(255,255,255,.14) !important; color:#d9efe0 !important; }
.faculty-dashboard-pro .dashboard-date-card strong { color:#fff !important; }
.faculty-dashboard-pro .dashboard-date-card span { color:rgba(255,255,255,.7) !important; }
.faculty-dashboard-pro .faculty-stats-grid { grid-template-columns:repeat(4,minmax(0,1fr)) !important; gap:14px !important; margin-bottom:18px !important; }
.faculty-dashboard-pro .faculty-stat-card {
    min-height:120px; padding:19px !important; gap:14px !important; border:1px solid #e4ebe6 !important;
    border-radius:16px !important; background:#fff !important; box-shadow:0 8px 24px rgba(21,45,32,.045) !important;
}
.faculty-dashboard-pro .faculty-stat-icon { width:42px !important; height:42px !important; flex:0 0 42px; border-radius:13px !important; }
.faculty-dashboard-pro .faculty-stat-label { color:#69786f !important; font-size:11px !important; }
.faculty-dashboard-pro .faculty-stat-card strong { color:#123d2d !important; font-size:27px !important; line-height:1.1; }
.faculty-dashboard-pro .faculty-stat-card small { color:#8a968e !important; font-size:10px !important; }
.faculty-dashboard-pro .dashboard-workspace { grid-template-columns:minmax(0,1.65fr) minmax(290px,.85fr) !important; gap:16px !important; }
.faculty-dashboard-pro .dashboard-panel {
    overflow:hidden; border:1px solid #e4ebe6 !important; border-radius:17px !important;
    background:#fff !important; box-shadow:0 8px 24px rgba(21,45,32,.045) !important;
}
.faculty-dashboard-pro .dashboard-panel-header { padding:18px 20px !important; border-bottom:1px solid #edf1ee !important; }
.faculty-dashboard-pro .dashboard-panel-header h3 { color:#17251e !important; font-size:14px !important; font-weight:750 !important; }
.faculty-dashboard-pro .dashboard-panel-header p { color:#7a887f !important; font-size:11px !important; }
.faculty-dashboard-pro .panel-title-icon { border-radius:11px !important; background:#eaf4ee !important; color:#16734b !important; }
.faculty-dashboard-pro .dashboard-side-column { display:grid; grid-template-columns:1fr !important; gap:16px !important; align-content:start; }
.faculty-dashboard-pro .campus-snapshot-grid { display:grid; grid-template-columns:1fr 1fr; gap:9px; padding:15px 16px 17px; }
.faculty-dashboard-pro .campus-snapshot-item { padding:11px 12px; border:1px solid #e9efeb; border-radius:11px; background:#fafcfb; }
.faculty-dashboard-pro .campus-snapshot-item span { display:block; color:#7b8980; font-size:10px; }
.faculty-dashboard-pro .campus-snapshot-item strong { display:block; margin-top:6px; color:#145c3d; font-size:19px; }
.faculty-dashboard-pro .today-schedule-item { margin:0 18px !important; border-color:#edf1ee !important; }
.faculty-dashboard-pro .schedule-time-block { border-radius:11px !important; background:#eaf4ee !important; color:#145c3d !important; }
.faculty-dashboard-pro .reservation-summary-card,
.faculty-dashboard-pro .reservation-card,
.faculty-dashboard-pro .swap-request-card-item { border-color:#e4ebe6 !important; border-radius:14px !important; box-shadow:0 5px 16px rgba(21,45,32,.035) !important; }
.faculty-dashboard-pro .reservation-status-filter { background:#f8faf8 !important; border-color:#e8eee9 !important; }
.faculty-dashboard-pro .reservation-filter-btn { border-radius:9px !important; }
.faculty-dashboard-pro .reservation-filter-btn.active { border-color:#16734b !important; background:#eaf4ee !important; color:#145c3d !important; }
.faculty-dashboard-pro .swap-dashboard-tab.active { background:#eaf4ee !important; color:#145c3d !important; border-bottom-color:#16734b !important; }
.faculty-dashboard-pro .swap-request-card-item { padding:17px !important; background:#fff !important; }
.faculty-dashboard-pro .today-info { padding:5px 18px 14px !important; }
.faculty-dashboard-pro .today-clock { border-radius:12px !important; background:#f4f8f5 !important; }
@media(max-width:1100px) {
    .faculty-dashboard-pro .faculty-stats-grid { grid-template-columns:repeat(2,minmax(0,1fr)) !important; }
    .faculty-dashboard-pro .dashboard-workspace { grid-template-columns:1fr !important; }
    .faculty-dashboard-pro .dashboard-side-column { grid-template-columns:repeat(2,minmax(0,1fr)) !important; }
}
@media(max-width:760px) {
    .faculty-dashboard-pro { padding:20px 15px !important; }
    .faculty-dashboard-pro .dashboard-header { align-items:flex-start !important; flex-direction:column; padding:25px 22px !important; }
    .faculty-dashboard-pro .dashboard-side-column { grid-template-columns:1fr !important; }
}
@media(max-width:480px) {
    .faculty-dashboard-pro .faculty-stats-grid { grid-template-columns:1fr 1fr !important; }
    .faculty-dashboard-pro .faculty-stat-card { min-height:108px; padding:13px !important; gap:10px !important; }
    .faculty-dashboard-pro .faculty-stat-icon { width:35px !important; height:35px !important; flex-basis:35px; }
    .faculty-dashboard-pro .faculty-stat-card strong { font-size:22px !important; }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {

    function updateFacultyClock() {

        const now = new Date();

        const time = now.toLocaleTimeString(
            'en-US',
            {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }
        );

        const date = now.toLocaleDateString(
            'en-US',
            {
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            }
        );

        const day = now.toLocaleDateString(
            'en-US',
            {
                weekday: 'long'
            }
        );

        const clock =
            document.getElementById(
                'liveFacultyClock'
            );

        const dashboardDate =
            document.getElementById(
                'dashboardDate'
            );

        const dashboardDay =
            document.getElementById(
                'dashboardDay'
            );

        if (clock) {
            clock.textContent = time;
        }

        if (dashboardDate) {
            dashboardDate.textContent = date;
        }

        if (dashboardDay) {
            dashboardDay.textContent = day;
        }
    }

    updateFacultyClock();

    setInterval(
        updateFacultyClock,
        1000
    );

});
</script>
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

    const target =
        document.getElementById(tabId);

    if (target) {
        target.classList.add('active');
    }

    const filterBar =
        document.getElementById(
            'reservationStatusFilter'
        );

    if (filterBar) {
        filterBar.style.display =
            tabId === 'allReservations'
                ? 'flex'
                : 'none';
    }

    /*
     * Opening My Reservations always starts with ALL.
     */
    if (tabId === 'allReservations') {

        const allButton =
            document.querySelector(
                '.reservation-filter-btn[data-filter="allReservations"]'
            );

        if (allButton) {
            filterReservations(
                'allReservations',
                allButton,
                false
            );
        }
    }
}


function filterReservations(
    statusId,
    button,
    scrollToSection = true
)
{
    document
        .querySelectorAll('.reservation-view-panel')
        .forEach(function(panel) {

            panel.classList.add('status-hidden');
            panel.classList.remove('active');
        });

    const target =
        document.getElementById(statusId);

    if (target) {

        target.classList.remove('status-hidden');
        target.classList.add('active');
    }

    document
        .querySelectorAll('.reservation-filter-btn')
        .forEach(function(filterButton) {
            filterButton.classList.remove('active');
        });

    if (button) {
        button.classList.add('active');
    }

    /*
     * These are filters inside My Reservations.
     * Keep the main My Reservations tab selected.
     */
    const mainReservationsButton =
        document.querySelector(
            '.tab-btn[onclick*="allReservations"]'
        );

    document
        .querySelectorAll('.tab-btn')
        .forEach(function(mainButton) {
            mainButton.classList.remove('active');
        });

    if (mainReservationsButton) {
        mainReservationsButton.classList.add('active');
    }

    const filterBar =
        document.getElementById(
            'reservationStatusFilter'
        );

    if (filterBar) {
        filterBar.style.display = 'flex';
    }

    if (scrollToSection) {

        const section =
            document.getElementById(
                'my-reservations'
            );

        if (section) {
            section.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
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

