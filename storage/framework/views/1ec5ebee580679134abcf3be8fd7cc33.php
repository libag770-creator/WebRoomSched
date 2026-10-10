<?php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Reservation;
    use App\Models\RoomSwapRequest;
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
    | Room reservations now use only Reserved and Expired.
    | Legacy Declined records are hidden because rejection is no longer
    | part of the room-reservation workflow.
    |--------------------------------------------------------------------------
    */

    $reservationHistory =
        $allReservations
            ->filter(function ($reservation) {
                return in_array(
                    $reservation->status,
                    ['Reserved', 'Expired'],
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

    /*
    |--------------------------------------------------------------------------
    | ROOM SWAP REQUESTS
    |--------------------------------------------------------------------------
    */

    $roomSwapRequests = RoomSwapRequest::with([
        'requester',
        'requesterSchedule',
        'targetSchedule',
        'requesterRoom',
        'targetRoom',
    ])
        ->where(
            'target_user_id',
            $facultyUserId
        )
        ->where(
            'status',
            'pending'
        )
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | ROOM SWAP HISTORY
    |--------------------------------------------------------------------------
    */

    $roomSwapHistory = RoomSwapRequest::with([
        'requester',
        'targetUser',
        'requesterSchedule',
        'targetSchedule',
        'requesterRoom',
        'targetRoom',
    ])
        ->where(function ($query) use ($facultyUserId) {
            $query
                ->where(
                    'requester_id',
                    $facultyUserId
                )
                ->orWhere(
                    'target_user_id',
                    $facultyUserId
                );
        })
        ->whereIn(
            'status',
            [
                'approved',
                'declined',
                'expired',
            ]
        )
        ->latest()
        ->get();
?>

<?php echo $__env->make('footerheader.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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

  <div class="main-layout">

    <?php echo $__env->make('faculty.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="content">


        

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
                            <h3>
                                My Reservations
                            </h3>

                            <p>
                                Track all your room reservation requests in one place.
                            </p>
                        </div>

                    </div>

                    <span class="reservation-summary-total">
                        <i class="fas fa-layer-group"></i>
                        <?php echo e($reservationHistory->count()); ?>

                        <?php echo e($reservationHistory->count() === 1 ? 'reservation' : 'reservations'); ?>

                    </span>

                </div>


            </div>


        <!-- FACULTY OVERVIEW -->

        <?php
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
        ?>

        <section class="faculty-overview">

            <div class="overview-panel">

                <div class="overview-panel-header">

                    <div class="overview-panel-title">

                        <span class="overview-panel-title-icon">
                            <i class="fas fa-chart-simple"></i>
                        </span>

                        <div>
                            <h3>
                                Faculty Overview
                            </h3>

                            <p>
                                A quick view of your current room activity.
                            </p>
                        </div>

                    </div>

                    <span class="overview-date">
                        <i class="fas fa-calendar-day"></i>
                        <?php echo e($todayDate->format('M d, Y')); ?>

                    </span>

                </div>

                <div class="overview-metrics">

                    <div class="overview-metric">
                        <div class="overview-metric-label">
                            <i class="fas fa-layer-group"></i>
                            Total Requests
                        </div>

                        <strong>
                            <?php echo e($displayReservations->count()); ?>

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
                            <?php echo e($reservedReservationsCount ?? 0); ?>

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
                            <?php echo e($activeReservedCount); ?>

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
                            <?php echo e($todayReservedCount); ?>

                        </strong>

                        <span>
                            Reserved rooms today
                        </span>
                    </div>

                </div>

                <div style="padding:0 20px 20px;">

                    <div class="quick-links">

                        <a
                            href="<?php echo e(route('faculty.schedules')); ?>"
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
                            href="<?php echo e(route('faculty.vacant')); ?>"
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
                            href="<?php echo e(route('faculty.bookings')); ?>"
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

                    <?php if($nextReservedReservation): ?>

                        <?php
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
                        ?>

                        <div class="next-reservation-card">

                            <div class="next-reservation-top">

                                <div class="next-reservation-room">

                                    <span class="next-reservation-room-icon">
                                        <i class="fas fa-door-open"></i>
                                    </span>

                                    <div>
                                        <strong>
                                            <?php echo e($nextReservedReservation->room->room_name ?? 'Room unavailable'); ?>

                                        </strong>

                                        <span>
                                            <?php echo e($nextBuildingName); ?>

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
                                        <?php echo e($nextReservedReservation->date
                                            ? \Carbon\Carbon::parse($nextReservedReservation->date)->format('M d, Y')
                                            : '-'); ?>

                                    </strong>
                                </div>

                                <div class="next-reservation-detail">
                                    <small>Time</small>
                                    <strong>
                                        <?php echo e($nextReservedReservation->time ?? '-'); ?>

                                    </strong>
                                </div>

                                <div class="next-reservation-detail">
                                    <small>Subject</small>
                                    <strong>
                                        <?php if($nextReservedReservation->facultySubject): ?>
                                            <?php echo e($nextReservedReservation->facultySubject->course_code); ?>

                                            - <?php echo e($nextReservedReservation->facultySubject->subject); ?>

                                        <?php else: ?>
                                            <?php echo e($nextReservedReservation->purpose ?? 'Room use'); ?>

                                        <?php endif; ?>
                                    </strong>
                                </div>

                                <div class="next-reservation-detail">
                                    <small>Day</small>
                                    <strong>
                                        <?php echo e($nextReservedReservation->day ?? '-'); ?>

                                    </strong>
                                </div>

                            </div>

                        </div>

                    <?php else: ?>

                        <div class="next-reservation-empty">

                            <i class="fas fa-calendar-xmark"></i>

                            <strong>
                                No upcoming reserved rooms
                            </strong>

                            <span>
                                Your next reserved room schedule will appear here.
                            </span>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </section>

        </div>

        <!-- DASHBOARD ALERTS -->
        <?php if(session('success')): ?>
            <div class="dashboard-alert success">
                <i class="fas fa-circle-check"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="dashboard-alert error">
                <i class="fas fa-circle-exclamation"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <!-- RESERVATION SECTION -->

        <div class="reservation-section" id="my-reservations">


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
                        <?php echo e($reservationHistory->count()); ?>

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
                        <?php echo e($reservedReservationsCount ?? 0); ?>

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
                        <?php echo e($expiredReservationsCount ?? 0); ?>

                    </span>
                </button>

            </div>


            <!-- ================================================= -->
            <!-- MY RESERVATIONS - ALL -->
            <!-- ================================================= -->

            <?php
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
                ];
            ?>

            <?php $__currentLoopData = $reservationGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tabId => $reservationCollection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div
                    id="<?php echo e($tabId); ?>"
                    class="tab-content reservation-view-panel <?php echo e($tabId === 'allReservations' ? 'active' : 'status-hidden'); ?>"
                    data-reservation-status="<?php echo e($tabId); ?>"
                >

                    <div class="reservation-list">

                        <div class="reservation-list-header">
                            <div>
                                <h3>
                                    <?php if($tabId === 'allReservations'): ?>
                                        My Room Reservations
                                    <?php elseif($tabId === 'reservedReservations'): ?>
                                        Reserved Rooms
                                    <?php else: ?>
                                        Reservation History
                                    <?php endif; ?>
                                </h3>

                                <p>
                                    <?php if($tabId === 'allReservations'): ?>
                                        Track your active and completed room reservations.
                                    <?php elseif($tabId === 'reservedReservations'): ?>
                                        These rooms are currently reserved by you.
                                    <?php else: ?>
                                        These reservations have already ended.
                                    <?php endif; ?>
                                </p>
                            </div>

                            <span class="reservation-total">
                                <?php echo e($reservationCollection->count()); ?>

                                <?php echo e($reservationCollection->count() === 1 ? 'request' : 'requests'); ?>

                            </span>
                        </div>

                        <?php if($reservationCollection->count() > 0): ?>

                            <?php $__currentLoopData = $reservationCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php
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
                                        default => 'expired',
                                    };

                                    $statusLabel = match ($reservation->status) {
                                        'Reserved' => $isExpired ? 'Expired' : 'Reserved',
                                        'Expired' => 'Expired',
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
                                ?>

                                <div class="reservation-card reservation-status-<?php echo e($statusClass); ?>">

                                    <div class="reservation-card-top">

                                        <div class="reservation-room-heading">
                                            <div class="reservation-room-icon">
                                                <i class="fas fa-door-open"></i>
                                            </div>

                                            <div>
                                                <h3>
                                                    <?php echo e($reservation->room->room_name ?? 'Room unavailable'); ?>

                                                </h3>

                                                <p class="reservation-building">
                                                    <i class="fas fa-building"></i>
                                                    <?php echo e($buildingName); ?>

                                                </p>
                                            </div>
                                        </div>

                                        <span class="reservation-status-badge <?php echo e($statusClass); ?>">
                                            <?php if($statusClass === 'approved'): ?>
                                                <i class="fas fa-circle-check"></i>
                                            <?php else: ?>
                                                <i class="fas fa-calendar-xmark"></i>
                                            <?php endif; ?>

                                            <?php echo e($statusLabel); ?>

                                        </span>

                                    </div>

                                    <div class="reservation-details">

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar"></i>
                                                Date
                                            </span>
                                            <strong>
                                                <?php echo e($reservation->date ? \Carbon\Carbon::parse($reservation->date)->format('M d, Y') : '-'); ?>

                                            </strong>
                                        </div>

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-day"></i>
                                                Day
                                            </span>
                                            <strong>
                                                <?php echo e($reservation->day ?? '-'); ?>

                                            </strong>
                                        </div>

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-clock"></i>
                                                Time
                                            </span>
                                            <strong>
                                                <?php echo e($reservation->time ?? '-'); ?>

                                            </strong>
                                        </div>

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-book"></i>
                                                Subject
                                            </span>
                                            <strong>
                                                <?php if($reservation->facultySubject): ?>
                                                    <?php echo e($reservation->facultySubject->course_code); ?> - <?php echo e($reservation->facultySubject->subject); ?>

                                                <?php else: ?>
                                                    <?php echo e($reservation->purpose ?? 'Room use'); ?>

                                                <?php endif; ?>
                                            </strong>
                                        </div>

                                        <div class="reservation-detail">
                                            <span class="detail-label">
                                                <i class="fas fa-bullseye"></i>
                                                Purpose
                                            </span>
                                            <strong>
                                                <?php echo e($reservation->purpose ?? 'Room use'); ?>

                                            </strong>
                                        </div>

                                    </div>
                                    <?php if($reservation->status === 'Reserved' && !$isExpired): ?>
                                        <div class="reservation-message approved-message">
                                            <i class="fas fa-circle-check"></i>
                                            <span>
                                                Your room is reserved. You may use the room during the scheduled time.
                                            </span>
                                        </div>

                                    <?php elseif($isExpired): ?>
                                        <div class="reservation-message expired-message">
                                            <i class="fas fa-calendar-xmark"></i>
                                            <span>
                                                This reservation has ended and the room is available again.
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php else: ?>

                            <div class="empty-room reservation-empty">

                                <div class="empty-icon">
                                    <?php if($tabId === 'reservedReservations'): ?>
                                        <i class="fas fa-clock"></i>
                                                                        <?php elseif($tabId === 'expiredReservations'): ?>
                                        <i class="fas fa-circle-xmark"></i>
                                    <?php else: ?>
                                        <i class="fas fa-calendar-plus"></i>
                                    <?php endif; ?>
                                </div>

                                <h3>
                                    <?php if($tabId === 'allReservations'): ?>
                                        No Room Reservations Yet
                                    <?php elseif($tabId === 'reservedReservations'): ?>
                                        No Reserved Rooms
                                    
                                    <?php else: ?>
                                        No Expired Reservations
                                    <?php endif; ?>
                                </h3>

                                <p>
                                    <?php if($tabId === 'reservedReservations'): ?>
                                        Your reserved rooms will appear here.

                                    <?php elseif($tabId === 'expiredReservations'): ?>
                                        Expired reservations will appear here.
                                    <?php else: ?>
                                        Your room reservation requests will appear here.
                                    <?php endif; ?>
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            <!-- ================================================= -->
            <!-- SWAPPED ROOMS -->
            <!-- ================================================= -->

            <div
                id="swapped"
                class="tab-content"
            >

                <?php if(isset($roomSwapHistory) && $roomSwapHistory->count() > 0): ?>

                    <div class="swap-history-grid">

                        <?php $__currentLoopData = $roomSwapHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $swap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
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
                            ?>

                            <div class="swap-history-card">

                                <div class="swap-history-top">
                                    <div>
                                        <h3>
                                            <i class="fas fa-right-left" style="color:#2e7d32;"></i>
                                            Room Swap
                                        </h3>

                                        <p>
                                            With <?php echo e($swap->requester->name ?? 'Unknown Faculty'); ?>

                                        </p>
                                    </div>

                                    <?php if($isActive): ?>
                                        <span class="swap-status active">
                                            <i class="fas fa-circle"></i>
                                            Active
                                        </span>
                                    <?php elseif($isExpired): ?>
                                        <span class="swap-status expired">
                                            <i class="fas fa-clock"></i>
                                            Expired
                                        </span>
                                    <?php elseif($isDeclined): ?>
                                        <span class="swap-status declined">
                                            <i class="fas fa-xmark"></i>
                                            Declined
                                        </span>
                                    <?php else: ?>
                                        <span class="swap-status expired">
                                            <?php echo e(ucfirst($swap->status)); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="swap-time-box">
                                    <i class="fas fa-calendar-day"></i>
                                    <strong><?php echo e($swap->swap_date ? $swap->swap_date->format('M d, Y') : '-'); ?></strong>
                                    <span>•</span>
                                    <span><?php echo e($swap->start_time ? \Carbon\Carbon::parse($swap->start_time)->format('g:i A') : '-'); ?> – <?php echo e($swap->end_time ? \Carbon\Carbon::parse($swap->end_time)->format('g:i A') : '-'); ?></span>
                                </div>

                                <div class="swap-room-flow">

                                    <div class="swap-room-box">
                                        <span>Your Original Room</span>
                                        <strong>
                                            <?php echo e($swap->targetRoom->room_name ?? '-'); ?>

                                        </strong>
                                    </div>

                                    <div class="swap-room-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>

                                    <div class="swap-room-box">
                                        <span>Temporary Room</span>
                                        <strong>
                                            <?php echo e($swap->requesterRoom->room_name ?? '-'); ?>

                                        </strong>
                                    </div>

                                </div>

                                <?php if($isActive): ?>
                                    <div class="swap-expiration">
                                        <i class="fas fa-hourglass-half"></i>
                                        Temporary room assignment is active until
                                        <strong>
                                            <?php echo e($swap->end_time ? \Carbon\Carbon::parse($swap->end_time)->format('g:i A') : '-'); ?>

                                        </strong>.
                                    </div>
                                <?php elseif($isExpired): ?>
                                    <div class="swap-expiration">
                                        <i class="fas fa-rotate-left"></i>
                                        The original room assignment has been restored.
                                    </div>
                                <?php elseif($isDeclined): ?>
                                    <div class="swap-expiration">
                                        <i class="fas fa-ban"></i>
                                        This room swap request was declined.
                                    </div>
                                <?php endif; ?>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                <?php else: ?>

                    <div class="empty-room">
                        <div class="empty-icon">
                            <i class="fas fa-right-left"></i>
                        </div>
                        <h3>No Room Swap History</h3>
                        <p>Approved, expired, and declined room swaps will appear here.</p>
                    </div>

                <?php endif; ?>

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

        <?php if(isset($roomSwapRequests) && $roomSwapRequests->count() > 0): ?>

            <?php $__currentLoopData = $roomSwapRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $swap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="swap-request-card-item">

                    <div class="swap-request-header">

                        <div>
                            <h3>
                                Room Swap Request
                            </h3>

                            <p>
                                From:
                                <strong>
                                    <?php echo e($swap->requester->name ?? 'Unknown Faculty'); ?>

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
                                <?php echo e($swap->requester->name ?? 'Unknown Faculty'); ?>

                            </h4>

                            <?php if($swap->requesterSchedule): ?>

                                <p>
                                    <strong>Course:</strong>
                                    <?php echo e($swap->requesterSchedule->course_code ?? '-'); ?>

                                </p>

                                <p>
                                    <strong>Subject:</strong>
                                    <?php echo e($swap->requesterSchedule->subject ?? '-'); ?>

                                </p>

                                <p>
                                    <strong>Day:</strong>
                                    <?php echo e($swap->requesterSchedule->day ?? '-'); ?>

                                </p>

                                <p>
                                    <strong>Time:</strong>
                                    <?php echo e($swap->requesterSchedule->time ?? '-'); ?>

                                </p>

                                <p>
                                    <strong>Room:</strong>
                                    <?php echo e($swap->requesterRoom->room_name ?? '-'); ?>

                                </p>

                            <?php endif; ?>

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
                                <?php echo e(Auth::user()->name); ?>

                            </h4>

                            <?php if($swap->targetSchedule): ?>

                                <p>
                                    <strong>Course:</strong>
                                    <?php echo e($swap->targetSchedule->course_code ?? '-'); ?>

                                </p>

                                <p>
                                    <strong>Subject:</strong>
                                    <?php echo e($swap->targetSchedule->subject ?? '-'); ?>

                                </p>

                                <p>
                                    <strong>Day:</strong>
                                    <?php echo e($swap->targetSchedule->day ?? '-'); ?>

                                </p>

                                <p>
                                    <strong>Time:</strong>
                                    <?php echo e($swap->targetSchedule->time ?? '-'); ?>

                                </p>

                                <p>
                                    <strong>Room:</strong>
                                    <?php echo e($swap->targetRoom->room_name ?? '-'); ?>

                                </p>

                            <?php endif; ?>

                        </div>

                    </div>


                    <!-- ACTIONS -->

                    <div class="swap-request-actions">

                        <form
                            method="POST"
                            action="<?php echo e(route('faculty.room-swap.approve', $swap->id)); ?>"
                        >

                            <?php echo csrf_field(); ?>

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
                            action="<?php echo e(route('faculty.room-swap.decline', $swap->id)); ?>"
                        >

                            <?php echo csrf_field(); ?>

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

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php else: ?>

            <div class="empty-room">

                <i class="fas fa-inbox"></i>

                <h3>
                    No Room Swap Requests
                </h3>

                <p>
                    You currently have no pending room swap requests.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>
        </div>


    </main>

</div>




</div>

<?php echo $__env->make('footerheader.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/faculty/my-bookings.blade.php ENDPATH**/ ?>