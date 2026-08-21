@php
use Illuminate\Support\Facades\Auth;
@endphp
<div class="wrapper">

    @include('footerheader.header')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        .content{
            padding:25px;
            background:#f5f5f5;
            min-height:100vh;
        }

        .dashboard-header h2{
            font-size:28px;
            font-weight:bold;
        }

        .dashboard-header p{
            color:#777;
            margin-bottom:20px;
            font-size:14px;
        }

        /* Dashboard Cards */

        .dashboard-cards{
            display:flex;
            gap:20px;
            margin-bottom:25px;
        }

        .summary-card{
            flex:1;
            background:#fff;
            border:2px solid #2e7d32;
            border-radius:8px;
            padding:15px;
            box-shadow:0 2px 8px rgba(0,0,0,.1);
        }

        .card-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            font-size:13px;
            font-weight:bold;
            color:#555;
        }

        .card-number{
            font-size:40px;
            font-weight:bold;
            margin-top:15px;
        }

        /* Reservation Box */

        .reservation-section{
            background:#fff;
            border:2px solid #222;
            border-radius:0 25px 25px 25px;
            overflow:hidden;
        }

        .reservation-tabs{
            display:flex;
            border-bottom:1px solid #ddd;
        }

        .tab-btn{
            border:none;
            padding:14px 35px;
            cursor:pointer;
            background:#ececec;
            color:#555;
            font-weight:bold;
            transition:.3s;
        }

        .tab-btn:hover{
            background:#ddd;
        }

        .tab-btn.active{
            background:#2e7d32;
            color:#fff;
        }

        /* Tab Content */

        .tab-content{
            display:none;
            min-height:500px;
            justify-content:center;
            align-items:center;
        }

        .tab-content.active{
            display:flex;
        }

        /* Empty State */

        .empty-room{
            text-align:center;
            color:#777;
        }

        .empty-room i{
            font-size:80px;
            color:#bbb;
            margin-bottom:15px;
        }

        .empty-room h3{
            margin-bottom:10px;
            color:#444;
        }

        .empty-room p{
            color:#888;
        }

        @media(max-width:768px){

            .dashboard-cards{
                flex-direction:column;
            }

            .reservation-tabs{
                flex-direction:column;
            }

            .tab-btn{
                width:100%;
            }

        }
    </style>

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content">

           <div class="dashboard-header">

    <h2>Faculty Dashboard</h2>

    <p>Welcome, {{ Auth::user()->name }}!</p>

</div>

            <!-- Cards -->

            <div class="dashboard-cards">

                <div class="summary-card">
                    <div class="card-header">
                        <span>Total of Rooms</span>
                        <i class="fas fa-building"></i>
                    </div>

                    <div class="card-number">0</div>
                </div>

                <div class="summary-card">
                    <div class="card-header">
                        <span>Room Reserved</span>
                        <i class="fas fa-check-circle" style="color:green"></i>
                    </div>

                    <div class="card-number">0</div>
                </div>

                <div class="summary-card">
                    <div class="card-header">
                        <span>Room Swapped</span>
                        <i class="fas fa-sync-alt" style="color:orange"></i>
                    </div>

                    <div class="card-number">0</div>
                </div>

            </div>

            <!-- Reservation Section -->

            <div class="reservation-section">

                <div class="reservation-tabs">

                    <button class="tab-btn active" onclick="showTab('reserved',this)">
                        Room Reserved
                    </button>

                    <button class="tab-btn" onclick="showTab('swapped',this)">
                        Room Swapped
                    </button>

                </div>

                <!-- Reserved -->

                <div id="reserved" class="tab-content active">

                   @if($reservations->count() > 0)

    @foreach($reservations as $reservation)

        <div class="reservation-card">

            <h3>
                {{ $reservation->room->room_name }}
            </h3>

            <p>
                Building:
                {{ $reservation->room->building }}
            </p>

            <p>
                Date:
                {{ $reservation->date }}
            </p>

            <p>
                Day:
                {{ $reservation->day }}
            </p>

            <p>
                Time:
                {{ $reservation->time }}
            </p>

            <p>
                Purpose:
                {{ $reservation->purpose }}
            </p>

            
       <p>
    Status:

    @php
        $isExpired = false;

        if (
            $reservation->status === 'Approved' &&
            $reservation->date &&
            $reservation->end_time
        ) {
            $reservationEnd = \Carbon\Carbon::parse(
                $reservation->date . ' ' . $reservation->end_time
            );

            $isExpired = now()->greaterThanOrEqualTo($reservationEnd);
        }
    @endphp

    @if($isExpired)

        <span style="color:#6c757d;font-weight:bold;">
            Expired
        </span>

    @elseif($reservation->status == 'Pending')

        <span style="color:orange;font-weight:bold;">
            Pending
        </span>

    @elseif($reservation->status == 'Approved')

        <span style="color:green;font-weight:bold;">
            Approved
        </span>

    @elseif($reservation->status == 'Declined')

        <span style="color:red;font-weight:bold;">
            Declined
        </span>

    @endif

</p>

@else

    <div class="empty-room">

        <i class="fas fa-calendar-times"></i>

        <h3>No Room Reservations yet</h3>

    </div>

@endif

                </div>

                <!-- Swapped -->

                <div id="swapped" class="tab-content">

                    <div class="empty-room">
                        <i class="fas fa-exchange-alt"></i>
                        <h3>No Room Swapped</h3>
                        <p>You don't have any swapped rooms yet.</p>
                    </div>

                </div>

            </div>

        </main>

    </div>

    @include('footerheader.footer')

</div>

<script>

function showTab(tabId, button){

    // Remove active class from buttons
    document.querySelectorAll('.tab-btn').forEach(function(btn){
        btn.classList.remove('active');
    });

    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(function(content){
        content.classList.remove('active');
    });

    // Activate clicked button
    button.classList.add('active');

    // Show selected content
    document.getElementById(tabId).classList.add('active');

}

</script>
