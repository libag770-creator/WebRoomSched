@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Reservation;
    use Carbon\Carbon;

    /*
    |--------------------------------------------------------------------------
    | MY RESERVATIONS PAGE DATA
    |--------------------------------------------------------------------------
    |
    | This page is now independent from the Faculty Dashboard.
    | It loads all required data for the logged-in faculty member here,
    | so variables such as $allReservations always exist.
    |
    */

    $facultyUserId = Auth::id();

    $allReservations = Reservation::with([
        'room',
        'room.building',
        'facultySubject',
    ])
        ->where('user_id', $facultyUserId)
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | RESERVATION HISTORY
    |--------------------------------------------------------------------------
    |
    | Keep cancelled reservations in history so the user can see what was
    | cancelled. Legacy Declined records remain hidden.
    |--------------------------------------------------------------------------
    */

    $reservationHistory =
        $allReservations
            ->filter(function ($reservation) {
                return in_array(
                    $reservation->status,
                    ['Reserved', 'Expired', 'Cancelled'],
                    true
                );
            })
            ->values();

    /*
    |--------------------------------------------------------------------------
    | Keep backward-compatible $reservations available.
    |--------------------------------------------------------------------------
    */

    $reservations = $reservationHistory;

    $reservedReservationsCount =
        $reservationHistory
            ->where('status', 'Reserved')
            ->count();

    $expiredReservationsCount =
        $reservationHistory
            ->where('status', 'Expired')
            ->count();

    $cancelledReservationsCount =
        $reservationHistory
            ->where('status', 'Cancelled')
            ->count();
$todayDate = Carbon::today();

    $activeReservedCount = $allReservations
        ->filter(function ($reservation) {
            if ($reservation->status !== 'Reserved') {
                return false;
            }

            if (!$reservation->date || !$reservation->end_time) {
                return true;
            }

            try {
                return Carbon::parse(
                    $reservation->date . ' ' . $reservation->end_time
                )->greaterThanOrEqualTo(now());
            } catch (\Throwable $e) {
                return true;
            }
        })
        ->count();

    $todayReservedCount = $allReservations
        ->filter(function ($reservation) use ($todayDate) {
            if (
                $reservation->status !== 'Reserved' ||
                !$reservation->date
            ) {
                return false;
            }

            try {
                return Carbon::parse(
                    $reservation->date
                )->isSameDay($todayDate);
            } catch (\Throwable $e) {
                return false;
            }
        })
        ->count();

    $nextReservedReservation = $allReservations
        ->filter(function ($reservation) {
            if (
                $reservation->status !== 'Reserved' ||
                !$reservation->date
            ) {
                return false;
            }

            try {
                $start =
                    $reservation->start_time
                    ?? $reservation->time
                    ?? null;

                if (!$start) {
                    return true;
                }

                return Carbon::parse(
                    $reservation->date . ' ' . $start
                )->greaterThanOrEqualTo(now());

            } catch (\Throwable $e) {
                return false;
            }
        })
        ->sortBy(function ($reservation) {
            try {
                $start =
                    $reservation->start_time
                    ?? $reservation->time
                    ?? '00:00';

                return Carbon::parse(
                    $reservation->date . ' ' . $start
                )->timestamp;

            } catch (\Throwable $e) {
                return PHP_INT_MAX;
            }
        })
        ->first();

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

        .reservation-remove-actions { display:flex; justify-content:flex-end; margin-top:13px; }
        .reservation-remove-button { display:inline-flex; min-height:38px; align-items:center; justify-content:center; gap:8px; padding:0 13px; border:1px solid #e5c9c5; border-radius:9px; background:#fff7f6; color:#9a433d; font:inherit; font-size:11px; font-weight:850; cursor:pointer; transition:background .15s ease,border-color .15s ease; }
        .reservation-remove-button:hover { border-color:#d7a7a1; background:#ffefed; }
        .reservation-delete-overlay[hidden] { display:none !important; }
        .reservation-delete-overlay { position:fixed; inset:0; z-index:100100; display:grid; place-items:center; padding:18px; background:rgba(17,39,27,.56); backdrop-filter:blur(4px); }
        .reservation-delete-dialog { width:min(100%,480px); padding:23px; border:1px solid #eadedb; border-radius:18px; background:#fff; box-shadow:0 24px 75px rgba(17,45,31,.24); }
        .reservation-delete-heading { display:flex; align-items:flex-start; gap:13px; }
        .reservation-delete-icon { display:grid; width:42px; height:42px; flex:0 0 42px; place-items:center; border:1px solid #f1d7d3; border-radius:13px; background:#fff2f0; color:#a94740; font-size:16px; }
        .reservation-delete-heading h2 { margin:1px 0 5px; color:#243d30; font:800 18px/1.3 'Manrope','DM Sans',sans-serif; }
        .reservation-delete-heading p { margin:0; color:#65756a; font-size:12px; line-height:1.55; }
        .reservation-delete-summary { margin-top:16px; padding:12px 13px; border:1px solid #e7ece5; border-radius:11px; background:#f8faf7; color:#40564a; font-size:12px; font-weight:750; line-height:1.5; }
        .reservation-delete-actions { display:flex; justify-content:flex-end; gap:9px; margin-top:18px; }
        .reservation-delete-actions button { min-height:40px; padding:0 14px; border:1px solid #dfe6de; border-radius:9px; background:#f7f9f6; color:#40564a; font:inherit; font-size:12px; font-weight:800; cursor:pointer; }
        .reservation-delete-actions .reservation-delete-confirm { border-color:#a94740; background:#a94740; color:#fff; }
        .reservation-delete-actions .reservation-delete-confirm:hover { background:#873a35; }
        body.reservation-delete-open { overflow:hidden; }

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

    <style>
        /* My Bookings visual system: stronger hierarchy with the Faculty portal palette. */
        body.portal-shell.faculty-portal .main-layout > .content.faculty-my-bookings-view {
            min-height:100vh;
            padding:128px clamp(20px,3vw,42px) 46px !important;
            background:
                radial-gradient(ellipse at 88% 2%,rgba(215,195,145,.25),transparent 25%),
                radial-gradient(ellipse at 8% 96%,rgba(204,224,208,.36),transparent 30%),
                linear-gradient(145deg,#edf2ec 0%,#f8f7f2 58%,#f1f0e8 100%);
            color:#263a2f;
            font-family:'DM Sans',Arial,sans-serif;
        }
        .faculty-my-bookings-view,
        .faculty-my-bookings-view * { font-family:'DM Sans',Arial,sans-serif; }
        .faculty-my-bookings-view > *:not(style) { width:min(100%,1480px); margin-right:auto; margin-left:auto; }
        .faculty-my-bookings-view .dashboard-cards { gap:0; margin-bottom:22px; }
        .faculty-my-bookings-view .reservation-summary-card {
            position:relative;
            isolation:isolate;
            min-height:205px;
            display:flex;
            align-items:center;
            padding:clamp(30px,4vw,46px) clamp(25px,4vw,48px);
            border:1px solid rgba(232,215,175,.22);
            border-radius:24px;
            background:radial-gradient(circle at 78% 18%,rgba(217,196,143,.17),transparent 25%),linear-gradient(118deg,#153a32 0%,#215440 58%,#37684e 100%);
            box-shadow:0 20px 44px rgba(24,58,43,.16);
        }
        .faculty-my-bookings-view .reservation-summary-card::before {
            z-index:-1;
            width:5px;
            background:#d8c18c;
        }
        .faculty-my-bookings-view .reservation-summary-card::after {
            content:"";
            position:absolute;
            z-index:-1;
            top:-155px;
            right:100px;
            width:340px;
            height:340px;
            border:1px solid rgba(244,232,200,.23);
            border-radius:50%;
            box-shadow:0 0 0 27px rgba(244,232,200,.035),0 0 0 63px rgba(244,232,200,.026),0 0 0 102px rgba(244,232,200,.018);
            pointer-events:none;
        }
        .faculty-my-bookings-view .reservation-summary-top { position:relative; z-index:1; align-items:center; gap:24px; width:100%; }
        .faculty-my-bookings-view .reservation-summary-title { gap:16px; }
        .faculty-my-bookings-view .reservation-summary-icon { width:54px; height:54px; flex:0 0 54px; border:1px solid rgba(255,255,255,.16); border-radius:16px; background:rgba(255,255,255,.12); color:#e6d39f; font-size:22px; }
        .faculty-my-bookings-view .reservation-summary-eyebrow { margin:0 0 7px; color:#e2ca8d; font-size:10px; font-weight:850; letter-spacing:.15em; text-transform:uppercase; }
        .faculty-my-bookings-view .reservation-summary-title h1 { margin:0 0 9px; color:#fff; font:800 clamp(32px,3.1vw,43px)/1.12 'Manrope','DM Sans',sans-serif; letter-spacing:-.04em; }
        .faculty-my-bookings-view .reservation-summary-title p { max-width:650px; margin:0; color:#e0e9e1; font-size:15px; font-weight:550; line-height:1.65; }
        .faculty-my-bookings-view .reservation-summary-actions { display:flex; flex:0 0 auto; flex-wrap:wrap; align-items:center; justify-content:flex-end; gap:9px; }
        .faculty-my-bookings-view .reservation-summary-total { flex:0 0 auto; display:inline-flex; align-items:center; gap:10px; padding:13px 16px; border:1px solid rgba(255,255,255,.2); border-radius:14px; background:rgba(255,255,255,.09); color:#fff; font-size:16px; font-weight:800; }
        .faculty-my-bookings-view .reservation-summary-total i { color:#e6d39f; }
        .faculty-my-bookings-view .reservation-create-link { display:inline-flex; min-height:43px; align-items:center; justify-content:center; gap:8px; padding:0 14px; border:1px solid rgba(255,255,255,.35); border-radius:10px; background:#f5f1e5; color:#214533; font-size:12px; font-weight:850; text-decoration:none; white-space:nowrap; transition:transform .15s ease,background .15s ease; }
        .faculty-my-bookings-view .reservation-create-link:hover { transform:translateY(-1px); background:#fff; color:#173b33; }

        .faculty-my-bookings-view .faculty-overview { grid-template-columns:minmax(0,1.4fr) minmax(310px,.8fr); gap:16px; margin-bottom:22px; }
        .faculty-my-bookings-view .overview-panel,
        .faculty-my-bookings-view .reservation-section { border:1px solid #dce6dc; border-radius:19px; background:#fff; box-shadow:0 9px 26px rgba(31,57,43,.05); }
        .faculty-my-bookings-view .overview-panel-header { align-items:center; gap:14px; padding:20px 22px; border-color:#e9eee8; background:linear-gradient(110deg,#f6f8f3,#fff); }
        .faculty-my-bookings-view .overview-panel-title { gap:12px; }
        .faculty-my-bookings-view .overview-panel-title-icon { width:42px; height:42px; flex-basis:42px; border:1px solid #e2e9df; border-radius:13px; background:#edf3eb; color:#285940; font-size:16px; }
        .faculty-my-bookings-view .overview-panel-title h3 { color:#203d30; font:800 18px/1.25 'Manrope','DM Sans',sans-serif; }
        .faculty-my-bookings-view .overview-panel-title p { margin-top:5px; color:#65756a; font-size:13px; font-weight:550; line-height:1.5; }
        .faculty-my-bookings-view .overview-date { padding:8px 11px; border-color:#e2e9df; border-radius:10px; background:#f5f8f3; color:#3e5b49; font-size:12px; font-weight:800; }
        .faculty-my-bookings-view .overview-metrics { gap:10px; padding:16px 19px 19px; }
        .faculty-my-bookings-view .overview-metric { min-height:105px; padding:13px; border-color:#e2e9e0; border-radius:12px; background:#f8faf6; }
        .faculty-my-bookings-view .overview-metric-label { color:#506557; font-size:11px; font-weight:850; line-height:1.4; }
        .faculty-my-bookings-view .overview-metric-label i { color:#32704d; }
        .faculty-my-bookings-view .overview-metric strong { margin-top:8px; color:#173b33; font:800 30px/1 'Manrope','DM Sans',sans-serif; }
        .faculty-my-bookings-view .overview-metric span { margin-top:6px; color:#64746a; font-size:11px; font-weight:600; line-height:1.45; }
        .faculty-my-bookings-view .next-reservation-body { padding:17px 19px 19px; }
        .faculty-my-bookings-view .next-reservation-empty { min-height:145px; }
        .faculty-my-bookings-view .next-reservation-empty i { width:44px; height:44px; border:1px solid #e3e9df; border-radius:13px; background:#edf3eb; color:#78917e; font-size:18px; }
        .faculty-my-bookings-view .next-reservation-empty strong { color:#294437; font-size:15px; font-weight:850; }
        .faculty-my-bookings-view .next-reservation-empty span { color:#65756a; font-size:12px; font-weight:550; line-height:1.5; }
        .faculty-my-bookings-view .next-reservation-card { padding:15px; border-color:#dce8d9; border-radius:14px; background:#f5f9f3; }
        .faculty-my-bookings-view .next-reservation-room-icon { width:42px; height:42px; flex-basis:42px; border-radius:13px; background:#e7f0e4; color:#285940; font-size:16px; }
        .faculty-my-bookings-view .next-reservation-room strong { color:#203d30; font-size:15px; font-weight:850; }
        .faculty-my-bookings-view .next-reservation-room span { color:#65756a; font-size:12px; font-weight:600; }
        .faculty-my-bookings-view .next-reservation-badge { padding:6px 10px; font-size:11px; font-weight:850; }
        .faculty-my-bookings-view .next-reservation-details { gap:9px; margin-top:13px; }
        .faculty-my-bookings-view .next-reservation-detail { padding:11px; border-color:#e2e9df; border-radius:10px; }
        .faculty-my-bookings-view .next-reservation-detail small { color:#627369; font-size:10px; font-weight:850; }
        .faculty-my-bookings-view .next-reservation-detail strong { margin-top:5px; color:#294437; font-size:12px; font-weight:800; line-height:1.45; }

        .faculty-my-bookings-view .reservation-section { overflow:hidden; }
        .faculty-my-bookings-view .reservation-tabs { gap:4px; padding:0 14px; border-color:#e6ece5; background:#f8faf7; }
        .faculty-my-bookings-view .tab-btn { min-height:59px; padding:15px 17px; border-bottom-width:3px; color:#596c60; font-size:13px; font-weight:800; }
        .faculty-my-bookings-view .tab-btn:hover { background:#f0f5ed; color:#24563d; }
        .faculty-my-bookings-view .tab-btn.active { border-bottom-color:#d1b76f; background:#fff; color:#1e553a; }
        .faculty-my-bookings-view .tab-count { min-width:22px; padding:4px 7px; border-radius:999px; background:#e8efe5; color:#315943; font-size:11px; font-weight:850; }
        .faculty-my-bookings-view .reservation-status-filter { gap:9px; margin:18px 22px 0; padding:12px 13px; border:1px solid #e4ebe2; border-radius:12px; background:#f8faf7; }
        .faculty-my-bookings-view .reservation-status-filter-label { color:#4e6355; font-size:12px; font-weight:850; }
        .faculty-my-bookings-view .reservation-filter-btn { min-height:38px; padding:0 12px; border-radius:9px; color:#586b5d; font-size:12px; font-weight:800; }
        .faculty-my-bookings-view .reservation-filter-btn.active { border-color:#c7dbc8; background:#eaf3e8; color:#1e553a; }
        .faculty-my-bookings-view .reservation-filter-count { min-width:20px; font-size:11px; font-weight:850; }
        .faculty-my-bookings-view .reservation-list { padding:21px 22px 24px; }
        .faculty-my-bookings-view .reservation-list-header { align-items:center; gap:14px; margin-bottom:15px; }
        .faculty-my-bookings-view .reservation-list-header h3 { margin:0 0 5px; color:#203d30; font:800 20px/1.25 'Manrope','DM Sans',sans-serif; }
        .faculty-my-bookings-view .reservation-list-header p { margin:0; color:#65756a; font-size:13px; font-weight:550; line-height:1.5; }
        .faculty-my-bookings-view .reservation-total { padding:7px 11px; border:1px solid #e2e9df; border-radius:999px; background:#f5f8f3; color:#3e5b49; font-size:12px; font-weight:850; }
        .faculty-my-bookings-view .reservation-card { margin-bottom:13px; padding:19px; border-color:#dfe8de; border-radius:15px; background:#fff; box-shadow:0 5px 17px rgba(31,57,43,.04); }
        .faculty-my-bookings-view .reservation-card-top { gap:14px; }
        .faculty-my-bookings-view .reservation-room-heading { gap:12px; }
        .faculty-my-bookings-view .reservation-room-icon { width:44px; height:44px; flex:0 0 44px; border-radius:13px; background:#edf3eb; color:#285940; font-size:17px; }
        .faculty-my-bookings-view .reservation-card h3 { margin:0 0 5px; color:#203d30; font-size:17px; font-weight:850; line-height:1.35; }
        .faculty-my-bookings-view .reservation-building { color:#64746a; font-size:13px; font-weight:600; }
        .faculty-my-bookings-view .reservation-status-badge { padding:7px 11px; border-radius:999px; font-size:11px; font-weight:850; }
        .faculty-my-bookings-view .reservation-details { gap:10px; margin-top:15px; }
        .faculty-my-bookings-view .reservation-detail { min-height:74px; padding:12px; border:1px solid #e8eee6; border-radius:11px; background:#f9fbf8; }
        .faculty-my-bookings-view .detail-label { margin-bottom:6px; color:#64746a; font-size:10px; font-weight:850; }
        .faculty-my-bookings-view .detail-label i { color:#32704d; }
        .faculty-my-bookings-view .reservation-detail strong { color:#294437; font-size:13px; font-weight:800; line-height:1.45; }
        .faculty-my-bookings-view .reservation-message { padding:12px 14px; border-radius:11px; font-size:12px; font-weight:700; line-height:1.55; }
        .faculty-my-bookings-view .reservation-remove-button { min-height:40px; padding:0 14px; border-radius:10px; font-size:12px; }
        .faculty-my-bookings-view .reservation-empty { min-height:280px; }
        .faculty-my-bookings-view .empty-room { min-height:340px; padding:36px 24px; }
        .faculty-my-bookings-view .empty-room .empty-icon { width:60px; height:60px; margin-bottom:14px; border:1px solid #e3e9df; border-radius:17px; background:#edf3eb; color:#78917e; font-size:24px; }
        .faculty-my-bookings-view .empty-room h3 { color:#294437; font-size:17px; font-weight:850; }
        .faculty-my-bookings-view .empty-room p { max-width:480px; color:#65756a; font-size:13px; font-weight:550; line-height:1.6; }

        .faculty-my-bookings-view .swap-request-container { padding:20px 22px; }
        .faculty-my-bookings-view .swap-request-title { margin-bottom:15px; color:#203d30; font-size:18px; font-weight:850; }
        .faculty-my-bookings-view .swap-request-title i { color:#285940; }
        .faculty-my-bookings-view .swap-request-card-item,
        .faculty-my-bookings-view .swap-history-card { padding:18px; border-color:#dfe8de; border-radius:15px; background:#fff; box-shadow:0 5px 17px rgba(31,57,43,.04); }
        .faculty-my-bookings-view .swap-request-header h3 { color:#203d30; font-size:16px; font-weight:850; }
        .faculty-my-bookings-view .swap-request-header p { color:#64746a; font-size:13px; font-weight:550; line-height:1.5; }
        .faculty-my-bookings-view .swap-pending,.faculty-my-bookings-view .swap-status { padding:7px 10px; font-size:11px; font-weight:850; }
        .faculty-my-bookings-view .swap-side { padding:15px; border-color:#e1e9df; border-radius:12px; background:#f8faf6; }
        .faculty-my-bookings-view .swap-side-title { color:#32704d; font-size:11px; font-weight:850; }
        .faculty-my-bookings-view .swap-side h4 { color:#294437; font-size:15px; font-weight:850; }
        .faculty-my-bookings-view .swap-side p { color:#5d7064; font-size:13px; line-height:1.55; }
        .faculty-my-bookings-view .swap-approve-btn,.faculty-my-bookings-view .swap-decline-btn { min-height:42px; padding:0 15px; border-radius:10px; font-size:12px; font-weight:850; }
        .faculty-my-bookings-view .swap-history-grid { gap:13px; padding:20px 22px; }
        .faculty-my-bookings-view .swap-history-top h3 { color:#203d30; font-size:16px; font-weight:850; }
        .faculty-my-bookings-view .swap-history-top p { color:#64746a; font-size:12px; line-height:1.5; }
        .faculty-my-bookings-view .swap-time-box { padding:12px; border:1px solid #e8eee6; border-radius:11px; background:#f8faf6; color:#405a49; font-size:13px; font-weight:700; }
        .faculty-my-bookings-view .swap-room-box { padding:12px; border-color:#e2e9df; border-radius:10px; background:#f9fbf8; }
        .faculty-my-bookings-view .swap-room-box span { color:#637368; font-size:10px; font-weight:850; }
        .faculty-my-bookings-view .swap-room-box strong { color:#294437; font-size:13px; font-weight:850; }

        @media(max-width:1100px) {
            .faculty-my-bookings-view .faculty-overview { grid-template-columns:1fr; }
        }
        @media(max-width:760px) {
            body.portal-shell.faculty-portal .main-layout > .content.faculty-my-bookings-view { min-height:calc(100vh - 64px); padding:22px 17px 32px !important; }
            .faculty-my-bookings-view .reservation-summary-card { min-height:0; padding:28px 24px; }
            .faculty-my-bookings-view .reservation-summary-top { align-items:flex-start; flex-direction:column; }
            .faculty-my-bookings-view .reservation-summary-actions { width:100%; justify-content:flex-start; }
            .faculty-my-bookings-view .overview-metrics { grid-template-columns:repeat(2,minmax(0,1fr)); }
            .faculty-my-bookings-view .reservation-details { grid-template-columns:repeat(2,minmax(0,1fr)); }
            .faculty-my-bookings-view .reservation-tabs { padding:0 8px; }
            .faculty-my-bookings-view .tab-btn { flex:0 0 auto; min-width:160px; }
        }
        @media(max-width:520px) {
            .faculty-my-bookings-view .reservation-summary-card { padding:24px 20px; }
            .faculty-my-bookings-view .reservation-summary-card::after { top:-90px; right:-100px; width:250px; height:250px; }
            .faculty-my-bookings-view .reservation-summary-title { align-items:flex-start; gap:12px; }
            .faculty-my-bookings-view .reservation-summary-icon { width:44px; height:44px; flex-basis:44px; border-radius:13px; font-size:18px; }
            .faculty-my-bookings-view .reservation-summary-title h1 { font-size:32px; }
            .faculty-my-bookings-view .reservation-summary-title p { font-size:14px; }
            .faculty-my-bookings-view .reservation-summary-total { padding:10px 13px; font-size:14px; }
            .faculty-my-bookings-view .reservation-summary-actions { align-items:stretch; flex-direction:column; }
            .faculty-my-bookings-view .reservation-create-link { width:100%; }
            .faculty-my-bookings-view .overview-panel-header { align-items:flex-start; flex-direction:column; padding:17px; }
            .faculty-my-bookings-view .overview-metrics { gap:8px; padding:13px; }
            .faculty-my-bookings-view .overview-metric { min-height:110px; padding:11px; }
            .faculty-my-bookings-view .overview-metric-label { font-size:10px; }
            .faculty-my-bookings-view .overview-metric strong { font-size:27px; }
            .faculty-my-bookings-view .overview-metric span { font-size:10px; }
            .faculty-my-bookings-view .reservation-tabs { gap:0; }
            .faculty-my-bookings-view .tab-btn { min-width:145px; padding:13px 12px; font-size:12px; }
            .faculty-my-bookings-view .reservation-status-filter { align-items:flex-start; flex-wrap:wrap; margin:14px 12px 0; padding:10px; }
            .faculty-my-bookings-view .reservation-status-filter-label { flex-basis:100%; }
            .faculty-my-bookings-view .reservation-list { padding:17px 12px 19px; }
            .faculty-my-bookings-view .reservation-list-header { align-items:flex-start; flex-direction:column; }
            .faculty-my-bookings-view .reservation-list-header h3 { font-size:18px; }
            .faculty-my-bookings-view .reservation-card { padding:14px; }
            .faculty-my-bookings-view .reservation-card-top { align-items:flex-start; flex-direction:column; }
            .faculty-my-bookings-view .reservation-details { grid-template-columns:1fr; }
            .faculty-my-bookings-view .reservation-detail { min-height:0; }
            .faculty-my-bookings-view .reservation-remove-actions { justify-content:stretch; }
            .faculty-my-bookings-view .reservation-remove-button { width:100%; }
            .faculty-my-bookings-view .reservation-delete-dialog { padding:19px; }
            .faculty-my-bookings-view .reservation-delete-actions { flex-direction:column-reverse; }
            .faculty-my-bookings-view .reservation-delete-actions button { width:100%; }
            .faculty-my-bookings-view .swap-request-container,.faculty-my-bookings-view .swap-history-grid { padding:14px 12px; }
            .faculty-my-bookings-view .swap-request-header { align-items:flex-start; flex-direction:column; }
            .faculty-my-bookings-view .swap-request-comparison { grid-template-columns:1fr; gap:9px; }
            .faculty-my-bookings-view .swap-arrow { justify-self:center; transform:rotate(90deg); }
            .faculty-my-bookings-view .swap-request-actions { align-items:stretch; flex-direction:column; }
            .faculty-my-bookings-view .swap-request-actions form,.faculty-my-bookings-view .swap-request-actions button { width:100%; }
            .faculty-my-bookings-view .swap-history-grid { grid-template-columns:1fr; }
        }
    </style>

  <div class="main-layout">

    @include('faculty.sidebar')

    <main class="content faculty-my-bookings-view">


        

        <!-- DASHBOARD CARDS -->

        <div class="dashboard-cards">

            <div
                class="summary-card reservation-summary-card"
            >

                <div class="reservation-summary-top">

                    <div class="reservation-summary-title">

                        <span class="reservation-summary-icon">
                            <i class="fas fa-calendar-check"></i>
                        </span>

                        <div>
                            <div class="reservation-summary-eyebrow">Room coordination</div>
                            <h1>
                                My Reservations
                            </h1>

                            <p>
                                Review your reserved rooms, upcoming bookings, and reservation history in one place.
                            </p>
                        </div>

                    </div>

                    <div class="reservation-summary-actions">
                        <span class="reservation-summary-total">
                            <i class="fas fa-layer-group"></i>
                            {{ $reservationHistory->count() }}
                            {{ $reservationHistory->count() === 1 ? 'reservation' : 'reservations' }}
                        </span>
                        <a class="reservation-create-link" href="{{ route('faculty.vacant') }}">
                            <i class="fas fa-plus" aria-hidden="true"></i> Reserve a room
                        </a>
                    </div>

                </div>


            </div>


        <!-- FACULTY OVERVIEW -->

        @php
            $displayReservations =
                $allReservations ??
                $reservations ??
                collect();

            $todayDate =
                \Carbon\Carbon::today();

            $activeReservedCount =
                $displayReservations
                    ->filter(function ($reservation) {
                        if ($reservation->status !== 'Reserved') {
                            return false;
                        }

                        if (!$reservation->date || !$reservation->end_time) {
                            return true;
                        }

                        try {
                            return now()->lt(
                                \Carbon\Carbon::parse(
                                    $reservation->date . ' ' . $reservation->end_time
                                )
                            );
                        } catch (\Throwable $e) {
                            return true;
                        }
                    })
                    ->count();

            $todayReservedCount =
                $displayReservations
                    ->filter(function ($reservation) use ($todayDate) {
                        if ($reservation->status !== 'Reserved' || !$reservation->date) {
                            return false;
                        }

                        try {
                            return \Carbon\Carbon::parse(
                                $reservation->date
                            )->isSameDay($todayDate);
                        } catch (\Throwable $e) {
                            return false;
                        }
                    })
                    ->count();

            $nextReservedReservation =
                $displayReservations
                    ->filter(function ($reservation) {
                        if ($reservation->status !== 'Reserved' || !$reservation->date) {
                            return false;
                        }

                        try {
                            $start = $reservation->start_time
                                ?? $reservation->time
                                ?? null;

                            if (!$start) {
                                return true;
                            }

                            return \Carbon\Carbon::parse(
                                $reservation->date . ' ' . $start
                            )->greaterThanOrEqualTo(now());
                        } catch (\Throwable $e) {
                            return false;
                        }
                    })
                    ->sortBy(function ($reservation) {
                        try {
                            $start = $reservation->start_time
                                ?? $reservation->time
                                ?? '00:00';

                            return \Carbon\Carbon::parse(
                                $reservation->date . ' ' . $start
                            )->timestamp;
                        } catch (\Throwable $e) {
                            return PHP_INT_MAX;
                        }
                    })
                    ->first();
        @endphp

        <section class="faculty-overview">

            <div class="overview-panel">

                <div class="overview-panel-header">

                    <div class="overview-panel-title">

                        <span class="overview-panel-title-icon">
                            <i class="fas fa-chart-simple"></i>
                        </span>

                        <div>
                            <h3>
                                Instructor Overview
                            </h3>

                            <p>
                                A quick view of your current room activity.
                            </p>
                        </div>

                    </div>

                    <span class="overview-date">
                        <i class="fas fa-calendar-day"></i>
                        {{ $todayDate->format('M d, Y') }}
                    </span>

                </div>

                <div class="overview-metrics">

                    <div class="overview-metric">
                        <div class="overview-metric-label">
                            <i class="fas fa-layer-group"></i>
                            Total Requests
                        </div>

                        <strong>
                            {{ $displayReservations->count() }}
                        </strong>

                        <span>
                            All room reservations
                        </span>
                    </div>


                    <div class="overview-metric">
                        <div class="overview-metric-label">
                            <i class="fas fa-clock"></i>
                            Reserved
                        </div>

                        <strong>
                            {{ $reservedReservationsCount ?? 0 }}
                        </strong>

                        <span>
                            Reserved immediately
                        </span>
                    </div>


                    <div class="overview-metric">
                        <div class="overview-metric-label">
                            <i class="fas fa-circle-check"></i>
                            Active Reserved
                        </div>

                        <strong>
                            {{ $activeReservedCount }}
                        </strong>

                        <span>
                            Reserved and not yet ended
                        </span>
                    </div>


                    <div class="overview-metric">
                        <div class="overview-metric-label">
                            <i class="fas fa-calendar-check"></i>
                            Today
                        </div>

                        <strong>
                            {{ $todayReservedCount }}
                        </strong>

                        <span>
                            Reserved rooms today
                        </span>
                    </div>

                </div>

                <div style="padding:0 20px 20px;">

                    <div class="quick-links">

                        <a
                            href="{{ route('faculty.schedules') }}"
                            class="quick-link"
                        >
                            <span class="quick-link-icon">
                                <i class="fas fa-calendar-days"></i>
                            </span>

                            <span>
                                <strong>Schedules</strong>
                                <span>View your timetable</span>
                            </span>
                        </a>


                        <a
                            href="{{ route('faculty.vacant') }}"
                            class="quick-link"
                        >
                            <span class="quick-link-icon">
                                <i class="fas fa-door-open"></i>
                            </span>

                            <span>
                                <strong>Vacant Rooms</strong>
                                <span>Find available rooms</span>
                            </span>
                        </a>


                        <a
                            href="{{ route('faculty.bookings') }}"
                            class="quick-link"
                        >
                            <span class="quick-link-icon">
                                <i class="fas fa-calendar-check"></i>
                            </span>

                            <span>
                                <strong>My Reservations</strong>
                                <span>Review request history</span>
                            </span>
                        </a>

                    </div>

                </div>

            </div>


            <div class="overview-panel">

                <div class="overview-panel-header">

                    <div class="overview-panel-title">

                        <span class="overview-panel-title-icon">
                            <i class="fas fa-forward"></i>
                        </span>

                        <div>
                            <h3>
                                Next Reserved Room
                            </h3>

                            <p>
                                Your next scheduled reserved room.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="next-reservation-body">

                    @if($nextReservedReservation)

                        @php
                            $nextBuildingName = '-';

                            if ($nextReservedReservation->room?->building) {
                                if (is_string($nextReservedReservation->room->building)) {
                                    $nextBuildingName =
                                        $nextReservedReservation->room->building;
                                } else {
                                    $nextBuildingName =
                                        $nextReservedReservation->room->building->name ??
                                        $nextReservedReservation->room->building->building_name ??
                                        '-';
                                }
                            }
                        @endphp

                        <div class="next-reservation-card">

                            <div class="next-reservation-top">

                                <div class="next-reservation-room">

                                    <span class="next-reservation-room-icon">
                                        <i class="fas fa-door-open"></i>
                                    </span>

                                    <div>
                                        <strong>
                                            {{ $nextReservedReservation->room->room_name ?? 'Room unavailable' }}
                                        </strong>

                                        <span>
                                            {{ $nextBuildingName }}
                                        </span>
                                    </div>

                                </div>

                                <span class="next-reservation-badge">
                                    Reserved
                                </span>

                            </div>

                            <div class="next-reservation-details">

                                <div class="next-reservation-detail">
                                    <small>Date</small>
                                    <strong>
                                        {{ $nextReservedReservation->date
                                            ? \Carbon\Carbon::parse($nextReservedReservation->date)->format('M d, Y')
                                            : '-' }}
                                    </strong>
                                </div>

                                <div class="next-reservation-detail">
                                    <small>Time</small>
                                    <strong>
                                        {{ $nextReservedReservation->time ?? '-' }}
                                    </strong>
                                </div>

                                <div class="next-reservation-detail">
                                    <small>Subject</small>
                                    <strong>
                                        @if($nextReservedReservation->subject)
                                            {{ $nextReservedReservation->subject->course_code }}
                                            - {{ $nextReservedReservation->subject->course_title }}
                                        @elseif($nextReservedReservation->facultySubject)
                                            {{ $nextReservedReservation->facultySubject->course_code }}
                                            - {{ $nextReservedReservation->facultySubject->subject }}
                                        @else
                                            {{ $nextReservedReservation->purpose ?? 'Room use' }}
                                        @endif
                                    </strong>
                                </div>

                                <div class="next-reservation-detail">
                                    <small>Day</small>
                                    <strong>
                                        {{ $nextReservedReservation->day ?? '-' }}
                                    </strong>
                                </div>

                            </div>

                        </div>

                    @else

                        <div class="next-reservation-empty">

                            <i class="fas fa-calendar-xmark"></i>

                            <strong>
                                No upcoming reserved rooms
                            </strong>

                            <span>
                                Your next reserved room schedule will appear here.
                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </section>

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

        <div class="reservation-section" id="my-reservations">


            <div
                id="reservationStatusFilter"
                class="reservation-status-filter"
            >

                <span class="reservation-status-filter-label">
                    My Reservations:
                </span>

                <button
                    type="button"
                    class="reservation-filter-btn active"
                    data-filter="allReservations"
                    onclick="filterReservations('allReservations', this)"
                >
                    <i class="fas fa-list"></i>
                    All
                    <span class="reservation-filter-count">
                        {{ $reservationHistory->count() }}
                    </span>
                </button>

                <button
                    type="button"
                    class="reservation-filter-btn"
                    data-filter="reservedReservations"
                    onclick="filterReservations('reservedReservations', this)"
                >
                    <i class="fas fa-clock"></i>
                    Reserved
                    <span class="reservation-filter-count">
                        {{ $reservedReservationsCount ?? 0 }}
                    </span>
                </button>

                <button
                    type="button"
                    class="reservation-filter-btn"
                    data-filter="expiredReservations"
                    onclick="filterReservations('expiredReservations', this)"
                >
                    <i class="fas fa-calendar-xmark"></i>
                    Expired
                    <span class="reservation-filter-count">
                        {{ $expiredReservationsCount ?? 0 }}
                    </span>
                </button>

                <button
                    type="button"
                    class="reservation-filter-btn"
                    data-filter="cancelledReservations"
                    onclick="filterReservations('cancelledReservations', this)"
                >
                    <i class="fas fa-ban"></i>
                    Cancelled
                    <span class="reservation-filter-count">
                        {{ $cancelledReservationsCount ?? 0 }}
                    </span>
                </button>

            </div>


            <!-- ================================================= -->
            <!-- MY RESERVATIONS - ALL -->
            <!-- ================================================= -->

            @php
                $displayReservations =
                    $reservationHistory
                    ?? $allReservations
                    ?? $reservations
                    ?? collect();

                $reservationGroups = [
                    'allReservations' => $displayReservations,
                    'reservedReservations' => $displayReservations->filter(
                        fn($r) => $r->status === 'Reserved'
                    ),
                    'expiredReservations' => $displayReservations->filter(
                        fn($r) => $r->status === 'Expired'
                    ),
                    'cancelledReservations' => $displayReservations->filter(
                        fn($r) => $r->status === 'Cancelled'
                    ),
                ];
            @endphp

            @foreach($reservationGroups as $tabId => $reservationCollection)

                <div
                    id="{{ $tabId }}"
                    class="tab-content reservation-view-panel {{ $tabId === 'allReservations' ? 'active' : 'status-hidden' }}"
                    data-reservation-status="{{ $tabId }}"
                >

                    <div class="reservation-list">

                        <div class="reservation-list-header">
                            <div>
                                <h3>
                                    @if($tabId === 'allReservations')
                                        My Room Reservations
                                    @elseif($tabId === 'reservedReservations')
                                        Reserved Rooms
                                    @else
                                        Reservation History
                                    @endif
                                </h3>

                                <p>
                                    @if($tabId === 'allReservations')
                                        Track your active and completed room reservations.
                                    @elseif($tabId === 'reservedReservations')
                                        These rooms are currently reserved by you.
                                    @else
                                        These reservations have already ended.
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
                                        $reservation->status === 'Reserved' &&
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
                                        'Reserved' => $isExpired ? 'expired' : 'approved',
                                        'Expired' => 'expired',
                                        'Cancelled' => 'cancelled',
                                        default => 'expired',
                                    };

                                    $statusLabel = match ($reservation->status) {
                                        'Reserved' => $isExpired ? 'Expired' : 'Reserved',
                                        'Expired' => 'Expired',
                                        'Cancelled' => 'Cancelled',
                                        default => 'Expired',
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
                                            @if($statusClass === 'approved')
                                                <i class="fas fa-circle-check"></i>
                                            @elseif($statusClass === 'cancelled')
                                                <i class="fas fa-ban"></i>
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
                                                <i class="fas fa-book"></i>
                                                Subject
                                            </span>
                                            <strong>
                                                @if($reservation->subject)
                                                    {{ $reservation->subject->course_code }} - {{ $reservation->subject->course_title }}
                                                @elseif($reservation->facultySubject)
                                                    {{ $reservation->facultySubject->course_code }} - {{ $reservation->facultySubject->subject }}
                                                @else
                                                    {{ $reservation->purpose ?? 'Room use' }}
                                                @endif
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
                                    @if($reservation->status === 'Reserved' && !$isExpired)
                                        <div class="reservation-message approved-message">
                                            <i class="fas fa-circle-check"></i>
                                            <span>
                                                Your room is reserved. You may use the room during the scheduled time.
                                            </span>
                                        </div>

                                    @elseif($isExpired)
                                        <div class="reservation-message expired-message">
                                            <i class="fas fa-calendar-xmark"></i>
                                            <span>
                                                This reservation has ended and the room is available again.
                                            </span>
                                        </div>
                                    @elseif($reservation->status === 'Cancelled')
                                        <div class="reservation-message expired-message">
                                            <i class="fas fa-ban"></i>
                                            <span>This reservation was cancelled. The room is available again.</span>
                                        </div>
                                    @endif

                                    <div class="reservation-remove-actions">
                                        <button
                                            type="button"
                                            class="reservation-remove-button"
                                            data-skip-system-confirm
                                            data-reservation-action
                                            data-action-url="{{ $reservation->status === 'Reserved' && !$isExpired ? route('faculty.bookings.cancel', $reservation->id) : route('faculty.bookings.destroy', $reservation->id) }}"
                                            data-action-type="{{ $reservation->status === 'Reserved' && !$isExpired ? 'cancel' : 'remove' }}"
                                            data-room="{{ $reservation->room->room_name ?? 'Room unavailable' }}"
                                            data-date="{{ $reservation->date ? \Carbon\Carbon::parse($reservation->date)->format('M j, Y') : 'Date unavailable' }}"
                                            data-time="{{ $reservation->time ?? 'Time unavailable' }}"
                                        >
                                            <i class="fas {{ $reservation->status === 'Reserved' && !$isExpired ? 'fa-ban' : 'fa-trash-can' }}" aria-hidden="true"></i>
                                            {{ $reservation->status === 'Reserved' && !$isExpired ? 'Cancel reservation' : 'Remove from history' }}
                                        </button>
                                    </div>

                                </div>

                            @endforeach

                        @else

                            <div class="empty-room reservation-empty">

                                <div class="empty-icon">
                                    @if($tabId === 'reservedReservations')
                                        <i class="fas fa-clock"></i>
                                    @elseif($tabId === 'expiredReservations')
                                        <i class="fas fa-circle-xmark"></i>
                                    @elseif($tabId === 'cancelledReservations')
                                        <i class="fas fa-ban"></i>
                                    @else
                                        <i class="fas fa-calendar-plus"></i>
                                    @endif
                                </div>

                                <h3>
                                    @if($tabId === 'allReservations')
                                        No Room Reservations Yet
                                    @elseif($tabId === 'reservedReservations')
                                        No Reserved Rooms
                                    @elseif($tabId === 'cancelledReservations')
                                        No Cancelled Reservations
                                    @else
                                        No Expired Reservations
                                    @endif
                                </h3>

                                <p>
                                    @if($tabId === 'reservedReservations')
                                        Your reserved rooms will appear here.

                                    @elseif($tabId === 'expiredReservations')
                                        Expired reservations will appear here.
                                    @elseif($tabId === 'cancelledReservations')
                                        Cancelled reservations will remain here for your reference.
                                    @else
                                        Your room reservation requests will appear here.
                                    @endif
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            @endforeach



        </div>


        <div class="reservation-delete-overlay" id="reservationDeleteModal" aria-hidden="true" hidden>
            <section class="reservation-delete-dialog" role="dialog" aria-modal="true" aria-labelledby="reservationDeleteTitle" aria-describedby="reservationDeleteMessage">
                <div class="reservation-delete-heading">
                    <span class="reservation-delete-icon"><i class="fas fa-circle-question" aria-hidden="true"></i></span>
                    <div>
                        <h2 id="reservationDeleteTitle">Confirm reservation action</h2>
                        <p id="reservationDeleteMessage">Review the reservation and confirm the action.</p>
                    </div>
                </div>
                <div class="reservation-delete-summary" id="reservationDeleteSummary"></div>
                <form method="POST" id="reservationDeleteForm">
                    @csrf
                    <input type="hidden" name="_method" id="reservationDeleteMethod" value="DELETE">
                    <div class="reservation-delete-actions">
                        <button type="button" data-close-reservation-delete>Keep it</button>
                        <button type="submit" class="reservation-delete-confirm" id="reservationDeleteConfirm">Confirm</button>
                    </div>
                </form>
            </section>
        </div>

    </main>

</div>




</div>

<script>

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

const reservationDeleteModal = document.getElementById('reservationDeleteModal');
const reservationDeleteForm = document.getElementById('reservationDeleteForm');
let reservationDeleteTrigger = null;

function closeReservationDeleteModal() {
    if (!reservationDeleteModal) return;
    reservationDeleteModal.hidden = true;
    reservationDeleteModal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('reservation-delete-open');
    if (reservationDeleteTrigger) reservationDeleteTrigger.focus();
    reservationDeleteTrigger = null;
}

document.addEventListener('click', function(event) {
    const target = event.target instanceof Element ? event.target : null;
    if (!target) return;

    const actionButton = target.closest('[data-reservation-action]');
    if (actionButton && reservationDeleteModal && reservationDeleteForm) {
        reservationDeleteTrigger = actionButton;
        const isCancel = actionButton.dataset.actionType === 'cancel';
        reservationDeleteForm.action = actionButton.dataset.actionUrl || '';
        document.getElementById('reservationDeleteMethod').value = isCancel ? 'PATCH' : 'DELETE';
        document.getElementById('reservationDeleteTitle').textContent = isCancel
            ? 'Cancel this reservation?'
            : 'Remove this reservation from history?';
        document.getElementById('reservationDeleteConfirm').textContent = isCancel
            ? 'Cancel reservation'
            : 'Remove history';
        document.getElementById('reservationDeleteSummary').textContent = [
            actionButton.dataset.room || 'Room unavailable',
            actionButton.dataset.date || 'Date unavailable',
            actionButton.dataset.time || 'Time unavailable'
        ].join(' · ');
        document.getElementById('reservationDeleteMessage').textContent =
            isCancel
                ? 'The room will be released and this reservation will disappear from schedule views. A Cancelled record will remain in your history.'
                : 'This permanently deletes this past or cancelled reservation record from your history.';
        reservationDeleteModal.hidden = false;
        reservationDeleteModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('reservation-delete-open');
        reservationDeleteModal.querySelector('[data-close-reservation-delete]')?.focus();
        return;
    }

    if (target.closest('[data-close-reservation-delete]') || target === reservationDeleteModal) {
        closeReservationDeleteModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && reservationDeleteModal && !reservationDeleteModal.hidden) {
        closeReservationDeleteModal();
    }
});


</script>
