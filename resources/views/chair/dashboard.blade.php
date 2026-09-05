@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="wrapper">

    @include('footerheader.header')

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>

        /* =========================
           DASHBOARD
        ========================= */

        .content {
            padding: 30px;
        }

        .dashboard-header {
            margin-bottom: 25px;
        }

        .dashboard-header h2 {
            margin: 0 0 8px;
            font-size: 28px;
            color: #222;
        }

        .dashboard-header p {
            margin: 5px 0;
            color: #777;
        }

        .dashboard-header .welcome {
            font-size: 16px;
            color: #2e7d32;
            font-weight: 600;
        }
/* ================================
   DEPARTMENT SUMMARY CARDS
================================ */

.department-card {
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.department-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.department-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.department-card .card-header span {
    font-size: 18px;
    font-weight: 700;
}

.department-card .card-header i {
    font-size: 22px;
    color: #f9a825;
}

.department-card .card-number {
    margin-top: 12px;
    font-size: 38px;
    font-weight: 700;
    color: #2e7d32;
}

.card-description {
    margin-top: -3px;
    font-size: 14px;
    color: #777;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 18px;
    padding-top: 12px;
    border-top: 1px solid #eeeeee;
    font-size: 12px;
    color: #777;
}

.card-footer i {
    margin-right: 5px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2e7d32;
    display: inline-block;
}

        /* =========================
           SUMMARY CARDS
        ========================= */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

      .summary-card {
    background: #ffffff;
    border: 1px solid #e4e4e4;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
    transition: 0.2s;
}

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.09);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #666;
            font-size: 14px;
            font-weight: 600;
        }

        .card-header i {
            font-size: 22px;
        }

        .card-number {
            margin-top: 15px;
            font-size: 30px;
            font-weight: bold;
            color: #2e7d32;
        }


        /* =========================
           REQUEST / HISTORY CARD
        ========================= */

        .management-card {
            background: #fff;
            border: 1px solid #e2e2e2;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.07);
            overflow: hidden;
        }

        .management-header {
            padding: 22px 25px;
            border-bottom: 1px solid #eee;
        }

        .management-header h3 {
            margin: 0 0 5px;
            font-size: 21px;
            color: #222;
        }

        .management-header p {
            margin: 0;
            color: #777;
            font-size: 14px;
        }


        /* =========================
           TABS
        ========================= */

        .reservation-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            background: #fafafa;
        }

        .tab-btn {
            border: none;
            background: transparent;
            padding: 15px 22px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #777;
            border-bottom: 3px solid transparent;
            transition: 0.2s;
        }

        .tab-btn:hover {
            color: #2e7d32;
            background: #f5f5f5;
        }

        .tab-btn.active {
            color: #2e7d32;
            border-bottom: 3px solid #2e7d32;
            background: #fff;
        }

        .tab-btn i {
            margin-right: 7px;
        }


        /* =========================
           TAB CONTENT
        ========================= */

        .tab-content {
            display: none;
            padding: 25px;
        }

        .tab-content.active {
            display: block;
        }


        /* =========================
           SECTION INTRO
        ========================= */

        .section-intro {
            background: #f8f9fa;
            border-left: 4px solid #f9a825;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .section-intro strong {
            display: block;
            margin-bottom: 4px;
            color: #333;
        }

        .section-intro span {
            color: #777;
            font-size: 13px;
        }


        /* =========================
           RESERVATION CARD
        ========================= */

        .reservation-card {
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            background: #fff;
            transition: 0.2s;
        }

        .reservation-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        }

        .reservation-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 15px;
        }

        .reservation-title {
            margin: 0;
            font-size: 18px;
            color: #222;
        }

        .reservation-subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: #777;
        }

        .reservation-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 25px;
            margin-top: 15px;
        }

        .detail-item {
            font-size: 14px;
            color: #555;
        }

        .detail-item strong {
            color: #333;
        }


        /* =========================
           STATUS
        ========================= */

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-declined {
            background: #f8d7da;
            color: #721c24;
        }


        /* =========================
           ACTION BUTTONS
        ========================= */

        .reservation-actions {
            margin-top: 18px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }

        .btn-approve,
        .btn-decline {
            border: none;
            padding: 9px 17px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
        }

        .btn-approve {
            background: #2e7d32;
            color: white;
        }

        .btn-approve:hover {
            background: #256b29;
        }

        .btn-decline {
            background: #dc3545;
            color: white;
        }

        .btn-decline:hover {
            background: #bb2d3b;
        }


        /* =========================
           SWAP HISTORY
        ========================= */

        .swap-card {
            border: 1px solid #e2e2e2;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            background: #fff;
        }

        .swap-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .swap-card-header h4 {
            margin: 0;
            font-size: 17px;
        }

        .swap-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 25px;
        }

        .swap-detail {
            font-size: 14px;
            color: #555;
        }

        .swap-detail strong {
            color: #333;
        }


        /* =========================
           EMPTY STATE
        ========================= */

        .empty-room {
            text-align: center;
            padding: 50px 20px;
            color: #888;
        }

        .empty-room i {
            font-size: 40px;
            margin-bottom: 15px;
            color: #bbb;
        }

        .empty-room h3 {
            margin: 0 0 5px;
            color: #555;
        }

        .empty-room p {
            margin: 0;
            font-size: 14px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media(max-width: 900px) {

            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .reservation-details,
            .swap-details {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width: 600px) {

            .content {
                padding: 20px;
            }

            .reservation-tabs {
                overflow-x: auto;
            }

            .tab-btn {
                white-space: nowrap;
            }

            .reservation-top,
            .swap-card-header {
                flex-direction: column;
            }

        }

    </style>


    <div class="main-layout">

        @include('chair.sidebar')


        <main class="content">


            <!-- =========================
                 DASHBOARD HEADER
            ========================= -->

            <div class="dashboard-header">

                <h2>
                    Department Chair Dashboard
                </h2>

                <p class="welcome">
                    Welcome, {{ Auth::user()?->name ?? 'Chair' }}!
                </p>

                <p>
                    Manage classroom schedules, room requests, and faculty room activities from one place.
                </p>

            </div>


       <!-- DEPARTMENT SUMMARY CARDS -->

<!-- =========================================================
     DEPARTMENT SUMMARY CARDS
     Automatically loads ALL departments from database
========================================================= -->

<div class="dashboard-cards">

    @forelse($departments as $department)

        <div class="summary-card department-card">

            <div class="card-header">

                <span>
                    {{ $department->name }}
                </span>

                <i class="fas fa-building"></i>

            </div>


            <div class="card-number">

                {{ $department->rooms_count }}

            </div>


            <div class="card-description">

                {{ $department->rooms_count == 1
                    ? 'Room'
                    : 'Rooms'
                }}

            </div>

        </div>

    @empty

        <div
            class="summary-card"
            style="
                grid-column: 1 / -1;
                text-align: center;
            "
        >

            <i
                class="fas fa-building"
                style="
                    font-size: 30px;
                    color: #bbb;
                    margin-bottom: 8px;
                "
            ></i>

            <h3 style="margin: 0 0 5px;">
                No Departments
            </h3>

            <p style="margin: 0; color: #777;">
                No departments have been created yet.
            </p>

        </div>

    @endforelse

</div>


            <!-- =========================
                 MANAGEMENT CARD
            ========================= -->

            <div class="management-card">


                <!-- HEADER -->

                <div class="management-header">

                    <h3>
                        Faculty Requests & Activities
                    </h3>

                    <p>
                        Review room reservation requests and monitor room swap activity submitted by faculty members.
                    </p>

                </div>


                <!-- =========================
                     TABS
                ========================= -->

                <div class="reservation-tabs">


                    <button
                        class="tab-btn active"
                        onclick="showTab('reserved', this)"
                    >

                        <i class="fas fa-calendar-check"></i>

                        Room Requests

                        @if($reservations->count() > 0)

                            <span
                                style="
                                    background:#f9a825;
                                    color:#fff;
                                    border-radius:20px;
                                    padding:2px 7px;
                                    font-size:11px;
                                    margin-left:5px;
                                "
                            >
                                {{ $reservations->count() }}
                            </span>

                        @endif

                    </button>


                    <button
                        class="tab-btn"
                        onclick="showTab('swapped', this)"
                    >

                        <i class="fas fa-exchange-alt"></i>

                        Room Swap History

                    </button>


                </div>


                <!-- =========================
                     ROOM REQUESTS
                ========================= -->

                <div
                    id="reserved"
                    class="tab-content active"
                >


                    <div class="section-intro">

                        <strong>
                            Room Reservation Requests
                        </strong>

                        <span>
                            Review faculty requests and approve or decline room reservations.
                        </span>

                    </div>


                    @if($reservations->count() > 0)


                        @foreach($reservations as $reservation)


                            <div class="reservation-card">


                                <div class="reservation-top">


                                    <div>

                                        <h3 class="reservation-title">

                                            <i
                                                class="fas fa-door-open"
                                                style="color:#2e7d32;"
                                            ></i>

                                            {{ $reservation->room->room_name }}

                                        </h3>

                                        <div class="reservation-subtitle">

                                            {{ $reservation->room->building }}

                                        </div>

                                    </div>


                                    <span class="status-badge status-pending">

                                        Pending

                                    </span>


                                </div>


                                <div class="reservation-details">


                                    <div class="detail-item">

                                        <strong>
                                            Requested by:
                                        </strong>

                                        {{ $reservation->user->name }}

                                    </div>


                                    <div class="detail-item">

                                        <strong>
                                            Date:
                                        </strong>

                                        {{ \Carbon\Carbon::parse($reservation->date)->format('F d, Y') }}

                                    </div>


                                    <div class="detail-item">

                                        <strong>
                                            Day:
                                        </strong>

                                        {{ $reservation->day }}

                                    </div>


                                    <div class="detail-item">

                                        <strong>
                                            Time:
                                        </strong>

                                        @if($reservation->start_time && $reservation->end_time)

                                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }}

                                            -

                                            {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}

                                        @else

                                            {{ $reservation->time }}

                                        @endif

                                    </div>


                                    <div class="detail-item">

                                        <strong>
                                            Purpose:
                                        </strong>

                                        {{ $reservation->purpose }}

                                    </div>


                                </div>


                                <!-- ACTIONS -->

                                <div class="reservation-actions">


                                    <form
                                        action="{{ route('chair.reservation.approve', $reservation->id) }}"
                                        method="POST"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn-approve"
                                        >

                                            <i class="fas fa-check"></i>

                                            Approve Request

                                        </button>

                                    </form>


                                    <form
                                        action="{{ route('chair.reservation.decline', $reservation->id) }}"
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


                        <div class="empty-room">

                            <i class="fas fa-calendar-check"></i>

                            <h3>
                                No Pending Room Requests
                            </h3>

                            <p>
                                New faculty reservation requests will appear here.
                            </p>

                        </div>


                    @endif


                </div>


                <!-- =========================
                     ROOM SWAP HISTORY
                ========================= -->

                <div
                    id="swapped"
                    class="tab-content"
                >


                    <div class="section-intro">

                        <strong>
                            Room Swap History
                        </strong>

                        <span>
                            View room swap requests and activities submitted by faculty members.
                        </span>

                    </div>


                    @if(isset($swapRequests) && $swapRequests->count() > 0)


                        @foreach($swapRequests as $swap)


                            <div class="swap-card">


                                <div class="swap-card-header">


                                    <h4>

                                        <i
                                            class="fas fa-exchange-alt"
                                            style="color:#f9a825;"
                                        ></i>

                                        Room Swap Request

                                    </h4>


                                    @if($swap->status === 'Approved')

                                        <span class="status-badge status-approved">
                                            Approved
                                        </span>

                                    @elseif($swap->status === 'Declined')

                                        <span class="status-badge status-declined">
                                            Declined
                                        </span>

                                    @else

                                        <span class="status-badge status-pending">
                                            Pending
                                        </span>

                                    @endif


                                </div>


                                <div class="swap-details">


                                    <div class="swap-detail">

                                        <strong>
                                            Requested by:
                                        </strong>

                                        {{ $swap->user->name ?? 'Unknown User' }}

                                    </div>


                                    <div class="swap-detail">

                                        <strong>
                                            Current Room:
                                        </strong>

                                        {{ $swap->currentRoom->room_name ?? 'N/A' }}

                                    </div>


                                    <div class="swap-detail">

                                        <strong>
                                            Requested Room:
                                        </strong>

                                        {{ $swap->requestedRoom->room_name ?? 'N/A' }}

                                    </div>


                                    <div class="swap-detail">

                                        <strong>
                                            Date:
                                        </strong>

                                        {{ isset($swap->created_at)
                                            ? $swap->created_at->format('F d, Y')
                                            : 'N/A'
                                        }}

                                    </div>


                                </div>


                            </div>


                        @endforeach


                    @else


                        <div class="empty-room">

                            <i class="fas fa-exchange-alt"></i>

                            <h3>
                                No Room Swap History
                            </h3>

                            <p>
                                Faculty room swap activities will appear here.
                            </p>

                        </div>


                    @endif


                </div>


            </div>


        </main>

    </div>


    @include('footerheader.footer')

</div>


<script>

function showTab(tabId, button)
{

    // Remove active state from all buttons
    document
        .querySelectorAll('.tab-btn')
        .forEach(function(btn)
        {
            btn.classList.remove('active');
        });


    // Hide all tab contents
    document
        .querySelectorAll('.tab-content')
        .forEach(function(content)
        {
            content.classList.remove('active');
        });


    // Activate selected button
    button.classList.add('active');


    // Show selected tab
    document
        .getElementById(tabId)
        .classList.add('active');

}

</script>