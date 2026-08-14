<div class="wrapper">
@include('footerheader.header')

<div class="main-layout">

    @include('faculty.sidebar')

    <main class="content">
        @if(session('success'))

    <div style="
        background:#d4edda;
        color:#155724;
        padding:12px;
        margin-bottom:20px;
        border-radius:5px;
    ">
        {{ session('success') }}
    </div>

@endif

        <style>

            .vacant-container{
                max-width:1200px;
                margin:auto;
            }

            .vacant-title{
                font-size:24px;
                font-weight:bold;
                margin-bottom:5px;
            }

            .vacant-subtitle{
                color:#777;
                margin-bottom:25px;
            }

            .search-box{
                background:#fff;
                border:1px solid #ddd;
                border-radius:8px;
                padding:20px;
                margin-bottom:25px;
                box-shadow:0 2px 5px rgba(0,0,0,.08);
            }

            .search-row{
                display:flex;
                gap:20px;
                align-items:end;
                flex-wrap:wrap;
            }

            .form-group{
                display:flex;
                flex-direction:column;
            }

            .form-group label{
                font-weight:bold;
                margin-bottom:6px;
            }

            .form-group input,
            .form-group select{
                width:220px;
                padding:10px;
                border:1px solid #ccc;
                border-radius:5px;
            }

            .search-button{
                padding:10px 25px;
                background:#198754;
                color:white;
                border:none;
                border-radius:5px;
                cursor:pointer;
                font-weight:bold;
            }

            .search-button:hover{
                background:#157347;
            }

            .building{
                background:#fff;
                border:1px solid #ccc;
                border-radius:8px;
                margin-bottom:20px;
                overflow:hidden;
            }

            .building-title{
                background:#efefef;
                padding:12px 15px;
                font-weight:bold;
                font-size:18px;
            }

            .rooms{
                display:flex;
                flex-wrap:wrap;
                gap:15px;
                padding:15px;
            }

            .room-card{
                width:220px;
                min-height:150px;
                border:1px solid #ddd;
                border-radius:7px;
                padding:15px;
                box-sizing:border-box;
                background:#fff;
                box-shadow:0 1px 4px rgba(0,0,0,.08);
            }

            .room-name{
                font-weight:bold;
                font-size:17px;
                margin-bottom:5px;
            }

            .room-status{
                margin-top:12px;
                font-weight:bold;
            }

            .available{
                color:#198754;
            }

            .occupied{
                color:#dc3545;
            }

            .reserved{
                color:#fd7e14;
            }

            .reserve-button{
                margin-top:12px;
                padding:7px 15px;
                background:#198754;
                color:white;
                border:none;
                border-radius:4px;
                cursor:pointer;
            }

            .reserve-button:hover{
                background:#157347;
            }

            .no-results{
                padding:20px;
                background:#fff;
                border:1px solid #ddd;
                border-radius:8px;
                color:#777;
            }

        </style>


        <div class="vacant-container">

            <div class="vacant-title">
                View Vacant Rooms
            </div>

            <div class="vacant-subtitle">
                Check room availability by date and time
            </div>


            {{-- SEARCH FORM --}}

            <div class="search-box">

                <form action="{{ route('faculty.vacant') }}" method="GET">

                    <div class="search-row">

                        <div class="form-group">

                            <label>Date</label>

                            <input
                                type="date"
                                name="date"
                                value="{{ request('date') }}"
                                required>

                        </div>


                        <div class="form-group">

                            <label>Time</label>

                            <select name="time" required>

                                <option value="">
                                    Select Time
                                </option>

                                <option value="8:00-9:00"
                                    {{ request('time') == '8:00-9:00' ? 'selected' : '' }}>
                                    8:00-9:00
                                </option>

                                <option value="9:00-10:00"
                                    {{ request('time') == '9:00-10:00' ? 'selected' : '' }}>
                                    9:00-10:00
                                </option>

                                <option value="10:00-11:00"
                                    {{ request('time') == '10:00-11:00' ? 'selected' : '' }}>
                                    10:00-11:00
                                </option>

                            </select>

                        </div>


                        <button
                            type="submit"
                            class="search-button">

                            Search

                        </button>

                    </div>

                </form>

            </div>


            {{-- RESULTS --}}

            @if(request('date') && request('time'))

                @if(count($results) > 0)

                    @foreach(collect($results)->groupBy(function($item){

                        return $item['room']->building;

                    }) as $building => $buildingRooms)


                        <div class="building">

                            <div class="building-title">
                                {{ $building }}
                            </div>


                            <div class="rooms">

                                @foreach($buildingRooms as $item)

                                    <div class="room-card">

                                        <div class="room-name">

                                            {{ $item['room']->room_name }}

                                        </div>


                                        <div style="font-size:13px;color:#777;">

                                            {{ $item['room']->department->name ?? '' }}

                                        </div>


                                        @if($item['status'] == 'Available')

                                            <div class="room-status available">

                                                🟢 Available

                                            </div>


                                            <form action="{{ route('faculty.reserve.room', $item['room']->id) }}" method="POST">

    @csrf

    <input
        type="hidden"
        name="date"
        value="{{ request('date') }}"
    >

    <input
        type="hidden"
        name="time"
        value="{{ request('time') }}"
    >

    <input
        type="text"
        name="purpose"
        placeholder="Purpose"
        required
    >

    <button type="submit">
        Reserve
    </button>

</form>


                                        @elseif($item['status'] == 'Occupied')

                                            <div class="room-status occupied">

                                                🔴 Occupied

                                            </div>


                                        @else

                                            <div class="room-status reserved">

                                                🟡 Reserved

                                            </div>

                                        @endif

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endforeach

                @else

                    <div class="no-results">

                        No rooms found.

                    </div>

                @endif

            @else

                <div class="no-results">

                    Select a date and time to check room availability.

                </div>

            @endif


        </div>

    </main>

</div>

@include('footerheader.footer')

</div>