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

        @include('chair.sidebar')

        <main class="content">

           <div class="dashboard-header">

    <h2>Department Chair Dashboard</h2>

    <p>Welcome, {{ Auth::user()?->name ?? 'Chair' }}!</p>


    <p>Click Departments to view schedule</p>
</div>

            <!-- Cards -->

            <div class="dashboard-cards">

                <div class="summary-card">
                    <div class="card-header">
                        <span>CAT</span>
                        <i class="fas fa-building"></i>
                    </div>

                    <div class="card-number">0</div>
                </div>

                <div class="summary-card">
                    <div class="card-header">
                        <span>CCJEPA</span>
                        <i class="fas fa-check-circle" style="color:green"></i>
                    </div>

                    <div class="card-number">0</div>
                </div>

                <div class="summary-card">
                    <div class="card-header">
                        <span>CED</span>
                        <i class="fas fa-sync-alt" style="color:orange"></i>
                    </div>

                    <div class="card-number">0</div>
                </div>

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
                    <strong>Building:</strong>
                    {{ $reservation->room->building }}
                </p>


                <p>
                    <strong>Reserved by:</strong>
                    {{ $reservation->user->name }}
                </p>


                <p>
                    <strong>Date:</strong>
                    {{ \Carbon\Carbon::parse($reservation->date)->format('F d, Y') }}
                </p>


                <p>
                    <strong>Day:</strong>
                    {{ $reservation->day }}
                </p>


                <p>
                    <strong>Time:</strong>

                    @if($reservation->start_time && $reservation->end_time)

                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }}

                        -

                        {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}

                    @else

                        {{ $reservation->time }}

                    @endif

                </p>


                <p>
                    <strong>Purpose:</strong>
                    {{ $reservation->purpose }}
                </p>


                <p>
                    <strong>Status:</strong>

                    @if($reservation->status === 'Approved')

                        <span style="
                            color:green;
                            font-weight:bold;
                        ">
                            Approved
                        </span>

                    @elseif($reservation->status === 'Pending')

                        <span style="
                            color:orange;
                            font-weight:bold;
                        ">
                            Pending
                        </span>

                    @else

                        <span style="
                            color:red;
                            font-weight:bold;
                        ">
                            Declined
                        </span>

                    @endif

                </p>


                {{-- APPROVE / DECLINE BUTTONS --}}

                @if($reservation->status === 'Pending')

                    <div style="margin-top:15px;">

                        <form
                            action="{{ route('chair.reservation.approve', $reservation->id) }}"
                            method="POST"
                            style="display:inline;"
                        >

                            @csrf

                            <button
                                type="submit"
                                style="
                                    background:green;
                                    color:white;
                                    padding:8px 15px;
                                    border:0;
                                    border-radius:5px;
                                    cursor:pointer;
                                "
                            >
                                Approve
                            </button>

                        </form>


                        <form
                            action="{{ route('chair.reservation.decline', $reservation->id) }}"
                            method="POST"
                            style="display:inline;"
                        >

                            @csrf

                            <button
                                type="submit"
                                style="
                                    background:red;
                                    color:white;
                                    padding:8px 15px;
                                    border:0;
                                    border-radius:5px;
                                    cursor:pointer;
                                "
                            >
                                Decline
                            </button>

                        </form>

                    </div>

                @endif

            </div>

        @endforeach

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
                        <h3>No Room Swaps yet</h3>
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
