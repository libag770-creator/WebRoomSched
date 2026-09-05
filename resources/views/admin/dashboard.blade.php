<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('admin.sidebar')

        <main class="content">

            <style>

                /* ================================
                   ADMIN DASHBOARD
                ================================= */

                .admin-dashboard {
                    padding: 25px;
                    max-width: 1400px;
                    margin: auto;
                }

                .dashboard-header {
                    margin-bottom: 25px;
                }

                .dashboard-header h1 {
                    margin: 0;
                    font-size: 28px;
                    color: #222;
                }

                .dashboard-header p {
                    margin-top: 7px;
                    color: #777;
                    font-size: 15px;
                }


                /* ================================
                   SUMMARY CARDS
                ================================= */

                .summary-cards {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 20px;
                    margin-bottom: 30px;
                }

                .summary-card {
                    background: white;
                    border: 1px solid #e5e5e5;
                    border-radius: 10px;
                    padding: 20px;
                    box-shadow: 0 2px 7px rgba(0,0,0,.06);
                }

                .summary-card-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    color: #666;
                    font-size: 14px;
                    font-weight: bold;
                }

                .summary-card-header i {
                    font-size: 22px;
                }

                .summary-number {
                    font-size: 30px;
                    font-weight: bold;
                    margin-top: 10px;
                    color: #222;
                }


                /* ================================
                   MAIN GRID
                ================================= */

                .dashboard-grid {
                    display: grid;
                    grid-template-columns: 2fr 1fr;
                    gap: 25px;
                }


                /* ================================
                   PANEL
                ================================= */

                .dashboard-panel {
                    background: white;
                    border: 1px solid #e5e5e5;
                    border-radius: 10px;
                    box-shadow: 0 2px 7px rgba(0,0,0,.06);
                    overflow: hidden;
                    margin-bottom: 25px;
                }

                .panel-header {
                    padding: 18px 20px;
                    border-bottom: 1px solid #eee;
                }

                .panel-header h2 {
                    margin: 0;
                    font-size: 19px;
                    color: #222;
                }

                .panel-header p {
                    margin: 5px 0 0;
                    color: #777;
                    font-size: 13px;
                }

                .panel-body {
                    padding: 20px;
                }


                /* ================================
                   RESERVATION REQUEST
                ================================= */

                .reservation-card {
                    border: 1px solid #e3e3e3;
                    border-radius: 8px;
                    padding: 18px;
                    margin-bottom: 15px;
                    background: #fff;
                }

                .reservation-card:last-child {
                    margin-bottom: 0;
                }

                .reservation-top {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 12px;
                }

                .reservation-room {
                    font-size: 18px;
                    font-weight: bold;
                    color: #222;
                }

                .pending-badge {
                    background: #fff3cd;
                    color: #856404;
                    padding: 5px 10px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: bold;
                }

                .reservation-details {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 8px 20px;
                    font-size: 14px;
                    color: #555;
                }

                .reservation-details strong {
                    color: #222;
                }

                .reservation-actions {
                    margin-top: 15px;
                    padding-top: 15px;
                    border-top: 1px solid #eee;
                    display: flex;
                    gap: 10px;
                }

                .btn-approve {
                    background: #198754;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                }

                .btn-approve:hover {
                    background: #157347;
                }

                .btn-decline {
                    background: #dc3545;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                }

                .btn-decline:hover {
                    background: #bb2d3b;
                }


                /* ================================
                   QUICK NAVIGATION
                ================================= */

                .quick-links {
                    display: grid;
                    gap: 10px;
                }

                .quick-link {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 12px;
                    border: 1px solid #e5e5e5;
                    border-radius: 7px;
                    text-decoration: none;
                    color: #333;
                    background: #fafafa;
                    transition: .2s;
                }

                .quick-link:hover {
                    background: #f1f1f1;
                    transform: translateX(3px);
                }

                .quick-link i {
                    width: 25px;
                    text-align: center;
                    font-size: 17px;
                }

                .quick-link-title {
                    font-weight: bold;
                    font-size: 14px;
                }

                .quick-link-description {
                    font-size: 12px;
                    color: #777;
                    margin-top: 2px;
                }


                /* ================================
                   STEPS
                ================================= */

                .step {
                    display: flex;
                    gap: 12px;
                    margin-bottom: 16px;
                }

                .step:last-child {
                    margin-bottom: 0;
                }

                .step-number {
                    min-width: 28px;
                    height: 28px;
                    border-radius: 50%;
                    background: #198754;
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 13px;
                    font-weight: bold;
                }

                .step-content strong {
                    display: block;
                    font-size: 14px;
                    margin-bottom: 3px;
                }

                .step-content span {
                    font-size: 12px;
                    color: #777;
                    line-height: 1.4;
                }


                /* ================================
                   EMPTY STATE
                ================================= */

                .empty-state {
                    text-align: center;
                    padding: 35px 20px;
                    color: #777;
                }

                .empty-state i {
                    font-size: 35px;
                    margin-bottom: 10px;
                    color: #198754;
                }

                .empty-state h3 {
                    margin: 5px 0;
                    color: #444;
                    font-size: 17px;
                }

                .empty-state p {
                    margin: 0;
                    font-size: 13px;
                }


                /* ================================
                   RESPONSIVE
                ================================= */

                @media(max-width: 1000px) {

                    .summary-cards {
                        grid-template-columns: repeat(2, 1fr);
                    }

                    .dashboard-grid {
                        grid-template-columns: 1fr;
                    }

                }

                @media(max-width: 600px) {

                    .admin-dashboard {
                        padding: 15px;
                    }

                    .summary-cards {
                        grid-template-columns: 1fr;
                    }

                    .reservation-details {
                        grid-template-columns: 1fr;
                    }

                    .reservation-actions {
                        flex-direction: column;
                    }

                }

            </style>


            <div class="admin-dashboard">


                <!-- ================================
                     HEADER
                ================================= -->

                <div class="dashboard-header">
                <!-- <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}"> -->
                    <h1>
                        Welcome, {{ Auth::user()->name }}!
                    </h1>

                    <p>
                        Manage classroom schedules, users, room reservations,
                        and system activities from your admin dashboard.
                    </p>

                </div>


                <!-- ================================
                     SUMMARY CARDS
                ================================= -->

                <div class="summary-cards">

                    <div class="summary-card">

                        <div class="summary-card-header">

                            <span>Total Rooms</span>

                            <i class="fas fa-door-open"></i>

                        </div>

                        <div class="summary-number">
                            {{ $totalRooms ?? 0 }}
                        </div>

                    </div>


                    <div class="summary-card">

                        <div class="summary-card-header">

                            <span>Total Users</span>

                            <i class="fas fa-users"></i>

                        </div>

                        <div class="summary-number">
                            {{ $totalUsers ?? 0 }}
                        </div>

                    </div>


                    <div class="summary-card">

                        <div class="summary-card-header">

                            <span>Pending Reservations</span>

                            <i class="fas fa-clock"></i>

                        </div>

                        <div class="summary-number">
                            {{ $pendingReservations ?? 0 }}
                        </div>

                    </div>


                    <div class="summary-card">

                        <div class="summary-card-header">

                            <span>Active Schedules</span>

                            <i class="fas fa-calendar-alt"></i>

                        </div>

                        <div class="summary-number">
                            {{ $activeSchedules ?? 0 }}
                        </div>

                    </div>

                </div>


                <!-- ================================
                     MAIN CONTENT
                ================================= -->

                <div class="dashboard-grid">


                    <!-- LEFT SIDE -->

                    <div>


                        <!-- ROOM RESERVATION REQUESTS -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    Room Reservation Requests
                                </h2>

                                <p>
                                    Review and manage room reservation requests submitted by faculty members.
                                </p>

                            </div>


                            <div class="panel-body">


                                @if(isset($reservations) && $reservations->count() > 0)


                                    @foreach($reservations as $reservation)

                                        <div class="reservation-card">


                                            <div class="reservation-top">

                                                <div class="reservation-room">

                                                    {{ $reservation->room->room_name }}

                                                </div>

                                                <span class="pending-badge">
                                                    Pending
                                                </span>

                                            </div>


                                            <div class="reservation-details">

                                                <div>
                                                    <strong>Requested by:</strong>
                                                    {{ $reservation->user->name }}
                                                </div>

                                                <div>
                                                    <strong>Building:</strong>
                                                    {{ $reservation->room->building }}
                                                </div>

                                                <div>
                                                    <strong>Date:</strong>
                                                    {{ $reservation->date }}
                                                </div>

                                                <div>
                                                    <strong>Day:</strong>
                                                    {{ $reservation->day }}
                                                </div>

                                                <div>
                                                    <strong>Time:</strong>
                                                    {{ $reservation->time }}
                                                </div>

                                                <div>
                                                    <strong>Purpose:</strong>
                                                    {{ $reservation->purpose }}
                                                </div>

                                            </div>


                                            <!-- ACTIONS -->

                                            <div class="reservation-actions">


                                                <!-- APPROVE -->

                                                <form
                                                    action="{{ route('admin.reservation.approve', $reservation->id) }}"
                                                    method="POST"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn-approve"
                                                    >

                                                        <i class="fas fa-check"></i>

                                                        Approve

                                                    </button>

                                                </form>


                                                <!-- DECLINE -->

                                                <form
                                                    action="{{ route('admin.reservation.decline', $reservation->id) }}"
                                                    method="POST"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn-decline"
                                                    >

                                                        <i class="fas fa-times"></i>

                                                        Decline

                                                    </button>

                                                </form>


                                            </div>


                                        </div>

                                    @endforeach


                                @else


                                    <div class="empty-state">

                                        <i class="fas fa-calendar-check"></i>

                                        <h3>
                                            No Pending Reservations
                                        </h3>

                                        <p>
                                            There are currently no room reservation requests waiting for approval.
                                        </p>

                                    </div>


                                @endif


                            </div>

                        </div>


                        <!-- HOW IT WORKS -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    Admin Workflow
                                </h2>

                                <p>
                                    Recommended steps for managing the system.
                                </p>

                            </div>


                            <div class="panel-body">


                                <div class="step">

                                    <div class="step-number">
                                        1
                                    </div>

                                    <div class="step-content">

                                        <strong>
                                            Review Requests
                                        </strong>

                                        <span>
                                            Check the faculty member, room, date,
                                            time, and purpose of each reservation.
                                        </span>

                                    </div>

                                </div>


                                <div class="step">

                                    <div class="step-number">
                                        2
                                    </div>

                                    <div class="step-content">

                                        <strong>
                                            Check Availability
                                        </strong>

                                        <span>
                                            Make sure the requested room is not
                                            already occupied or reserved.
                                        </span>

                                    </div>

                                </div>


                                <div class="step">

                                    <div class="step-number">
                                        3
                                    </div>

                                    <div class="step-content">

                                        <strong>
                                            Approve or Decline
                                        </strong>

                                        <span>
                                            Approve valid requests or decline
                                            requests that cannot be accepted.
                                        </span>

                                    </div>

                                </div>


                                <div class="step">

                                    <div class="step-number">
                                        4
                                    </div>

                                    <div class="step-content">

                                        <strong>
                                            Monitor the System
                                        </strong>

                                        <span>
                                            Keep users, rooms, schedules, and
                                            reservations updated.
                                        </span>

                                    </div>

                                </div>


                            </div>

                        </div>


                    </div>


                    <!-- RIGHT SIDE -->

                    <div>


                        <!-- QUICK NAVIGATION -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    Quick Navigation
                                </h2>

                                <p>
                                    Frequently used admin functions.
                                </p>

                            </div>


                            <div class="panel-body">

                                <div class="quick-links">


                                    <a
                                        href="{{ route('admin.dashboard') }}"
                                        class="quick-link"
                                    >

                                        <i class="fas fa-home"></i>

                                        <div>

                                            <div class="quick-link-description">
                                                View system overview
                                            </div>

                                        </div>

                                    </a>


                                    <a
                                        href="{{ route('admin.manageusers') }}"
                                        class="quick-link"
                                    >

                                        <i class="fas fa-users"></i>

                                        <div>

                                            <div class="quick-link-title">
                                                Manage Users
                                            </div>

                                            <div class="quick-link-description">
                                                Add and manage system users
                                            </div>

                                        </div>

                                    </a>


                                    <a
                                        href="{{ route('admin.schedules') }}"
                                        class="quick-link"
                                    >

                                        <i class="fas fa-calendar-alt"></i>

                                        <div>

                                            <div class="quick-link-title">
                                                Schedules
                                            </div>

                                            <div class="quick-link-description">
                                                View classroom schedules
                                            </div>

                                        </div>

                                    </a>


                                    <a
                                        href="{{ route('admin.roomreassignment') }}"
                                        class="quick-link"
                                    >

                                        <i class="fas fa-exchange-alt"></i>

                                        <div>

                                            <div class="quick-link-title">
                                                Room Reassignment
                                            </div>

                                            <div class="quick-link-description">
                                                Transfer rooms between departments
                                            </div>

                                        </div>

                                    </a>


                                </div>

                            </div>

                        </div>


                        <!-- ADMIN NOTE -->

                        <div class="dashboard-panel">

                            <div class="panel-header">

                                <h2>
                                    System Notice
                                </h2>

                            </div>

                            <div class="panel-body">

                                <p style="
                                    font-size:14px;
                                    color:#666;
                                    line-height:1.6;
                                    margin:0;
                                ">

                                    Keep classroom information and user
                                    accounts updated to ensure that faculty
                                    members and department chairs receive
                                    accurate information.

                                </p>

                            </div>

                        </div>


                    </div>


                </div>


            </div>


        </main>

    </div>


    @include('footerheader.footer')

</div>