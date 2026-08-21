<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content">

            <style>
                body {
                    background: #f5f5f5;
                }

                .swap-container {
                    max-width: 1100px;
                    margin: 0 auto;
                }

                .swap-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .swap-subtitle {
                    color: #777;
                    margin-bottom: 25px;
                }

                .step {
                    background: white;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.08);
                }

                .step-title {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .step-description {
                    color: #777;
                    font-size: 14px;
                    margin-bottom: 18px;
                }

                .booking-section {
                    margin-bottom: 20px;
                }

                .booking-section h4 {
                    margin-bottom: 10px;
                }

                .booking-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                    gap: 12px;
                }

                .booking-card {
                    border: 2px solid #ddd;
                    border-radius: 8px;
                    padding: 15px;
                    cursor: pointer;
                    transition: .2s;
                    background: #fff;
                }

                .booking-card:hover {
                    border-color: #28a745;
                }

                .booking-card.selected {
                    border-color: #28a745;
                    background: #f0fff3;
                }

                .booking-card strong {
                    display: block;
                    font-size: 16px;
                    margin-bottom: 5px;
                }

                .booking-card small {
                    color: #666;
                }

                .search-row {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 15px;
                }

                .search-row input {
                    flex: 1;
                    padding: 11px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                }

                .filter-btn {
                    padding: 11px 18px;
                    border: none;
                    background: #555;
                    color: white;
                    border-radius: 6px;
                    cursor: pointer;
                }

                .filter-panel {
                    display: none;
                    background: #f8f8f8;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 15px;
                }

                .filter-panel select {
                    padding: 10px;
                    width: 200px;
                    margin-right: 10px;
                }

                .reason-options {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                    gap: 10px;
                    margin-bottom: 15px;
                }

                .reason-option {
                    border: 2px solid #ddd;
                    padding: 12px;
                    border-radius: 7px;
                    cursor: pointer;
                    text-align: center;
                    background: white;
                }

                .reason-option.selected {
                    border-color: #28a745;
                    background: #f0fff3;
                }

                textarea {
                    width: 100%;
                    min-height: 100px;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    resize: vertical;
                    box-sizing: border-box;
                }

                .buttons {
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                    margin-top: 20px;
                }

                .btn {
                    padding: 10px 20px;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-weight: bold;
                }

                .btn-reset {
                    background: #ddd;
                }

                .btn-send {
                    background: #28a745;
                    color: white;
                }

                .empty {
                    padding: 20px;
                    text-align: center;
                    color: #777;
                    background: #f8f8f8;
                    border-radius: 6px;
                }

                .alert-success {
                    background: #d4edda;
                    color: #155724;
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }

                .alert-error {
                    background: #f8d7da;
                    color: #721c24;
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }
            </style>


            <div class="swap-container">

                <div class="swap-title">
                    Room Swap Request
                </div>

                <div class="swap-subtitle">
                    Temporarily exchange rooms with another faculty member.
                </div>


                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))

                    <div class="alert-success">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ERROR MESSAGE --}}
                @if(session('error'))

                    <div class="alert-error">
                        {{ session('error') }}
                    </div>

                @endif


                <form
                    action="{{ route('faculty.room.swap.store') }}"
                    method="POST"
                    id="swapForm"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- STEP 1 --}}
                    {{-- ================================================= --}}

                    <div class="step">

                        <div class="step-title">
                            Step 1 — Select Your Room
                        </div>

                        <div class="step-description">
                            Select the scheduled room or approved reservation that you want to swap.
                        </div>


                        {{-- YOUR SCHEDULES --}}

                        <div class="booking-section">

                            <h4>
                                My Scheduled Rooms
                            </h4>

                            <div class="booking-grid">

                                @forelse($mySchedules as $schedule)

                                    <div
                                        class="booking-card"
                                        onclick="selectRequester(
                                            'schedule',
                                            {{ $schedule->id }},
                                            {{ $schedule->room_id }},
                                            '{{ $schedule->day }}',
                                            '{{ $schedule->time }}',
                                            this
                                        )"
                                    >

                                        <strong>
                                            {{ $schedule->room->room_name ?? 'Room' }}
                                        </strong>

                                        <small>
                                            {{ $schedule->course_code }}
                                        </small>

                                        <br>

                                        <small>
                                            {{ $schedule->subject }}
                                        </small>

                                        <br>

                                        <small>
                                            {{ $schedule->day }} |
                                            {{ $schedule->time }}
                                        </small>

                                    </div>

                                @empty

                                    <div class="empty">
                                        No scheduled rooms found.
                                    </div>

                                @endforelse

                            </div>

                        </div>


                        {{-- YOUR RESERVATIONS --}}

                        <div class="booking-section">

                            <h4>
                                My Approved Reservations
                            </h4>

                            <div class="booking-grid">

                                @forelse($myReservations as $reservation)

                                    <div
                                        class="booking-card"
                                        onclick="selectRequester(
                                            'reservation',
                                            {{ $reservation->id }},
                                            {{ $reservation->room_id }},
                                            '{{ $reservation->day }}',
                                            '{{ $reservation->time }}',
                                            this
                                        )"
                                    >

                                        <strong>
                                            {{ $reservation->room->room_name ?? 'Room' }}
                                        </strong>

                                        <small>
                                            {{ $reservation->purpose }}
                                        </small>

                                        <br>

                                        <small>
                                            {{ $reservation->date }}
                                        </small>

                                        <br>

                                        <small>
                                            {{ $reservation->time }}
                                        </small>

                                    </div>

                                @empty

                                    <div class="empty">
                                        No approved reservations found.
                                    </div>

                                @endforelse

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- STEP 2 --}}
                    {{-- ================================================= --}}

                    <div class="step">

                        <div class="step-title">
                            Step 2 — Select Another Faculty's Room
                        </div>

                        <div class="step-description">
                            Search for the room you want and select the faculty member's schedule or reservation.
                        </div>


                        <div class="search-row">

                            <input
                                type="text"
                                id="roomSearch"
                                placeholder="Search room..."
                                onkeyup="searchRooms()"
                            >

                            <button
                                type="button"
                                class="filter-btn"
                                onclick="toggleFilter()"
                            >
                                Filter
                            </button>

                        </div>


                        <div
                            class="filter-panel"
                            id="filterPanel"
                        >

                            <select id="departmentFilter">
                                <option value="">
                                    All Departments
                                </option>

                                @foreach($departments as $department)

                                    <option value="{{ $department->id }}">
                                        {{ $department->name }}
                                    </option>

                                @endforeach

                            </select>


                            <select id="buildingFilter">

                                <option value="">
                                    All Buildings
                                </option>

                                @foreach($buildings as $building)

                                    <option value="{{ $building->id }}">
                                        {{ $building->name }}
                                    </option>

                                @endforeach

                            </select>


                            <button
                                type="button"
                                class="filter-btn"
                                onclick="filterRooms()"
                            >
                                Apply Filter
                            </button>

                        </div>


                        <div class="booking-grid" id="targetRooms">

                            @forelse($targetBookings as $booking)

                                <div
                                    class="booking-card target-booking"
                                    data-room="{{ strtolower($booking['room_name']) }}"
                                    data-department="{{ $booking['department_id'] }}"
                                    data-building="{{ $booking['building_id'] }}"
                                    onclick="selectTarget(
                                        '{{ $booking['type'] }}',
                                        {{ $booking['id'] }},
                                        {{ $booking['room_id'] }},
                                        {{ $booking['user_id'] }},
                                        '{{ $booking['date'] }}',
                                        '{{ $booking['time'] }}',
                                        this
                                    )"
                                >

                                    <strong>
                                        {{ $booking['room_name'] }}
                                    </strong>

                                    <small>
                                        Faculty:
                                        {{ $booking['user_name'] }}
                                    </small>

                                    <br>

                                    <small>
                                        {{ $booking['department_name'] }}
                                    </small>

                                    <br>

                                    <small>
                                        {{ $booking['date'] }}
                                        |
                                        {{ $booking['time'] }}
                                    </small>

                                </div>

                            @empty

                                <div class="empty">
                                    No other faculty rooms are currently available for swapping.
                                </div>

                            @endforelse

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- STEP 3 --}}
                    {{-- ================================================= --}}

                    <div class="step">

                        <div class="step-title">
                            Step 3 — Reason for Room Swap
                        </div>

                        <div class="step-description">
                            Select a quick reason or enter your own reason.
                        </div>


                        <div class="reason-options">

                            <div
                                class="reason-option"
                                onclick="selectReason('Need computers', this)"
                            >
                                Need computers
                            </div>

                            <div
                                class="reason-option"
                                onclick="selectReason('Need projector/TV', this)"
                            >
                                Need projector/TV
                            </div>

                            <div
                                class="reason-option"
                                onclick="selectReason('Need 40+ room capacity', this)"
                            >
                                Need 40+ room capacity
                            </div>

                            <div
                                class="reason-option"
                                onclick="selectReason('Other', this)"
                            >
                                Other
                            </div>

                        </div>


                        <textarea
                            id="otherReason"
                            placeholder="Enter other reason..."
                        ></textarea>


                        {{-- HIDDEN FORM FIELDS --}}

                        <input
                            type="hidden"
                            name="requester_type"
                            id="requester_type"
                        >

                        <input
                            type="hidden"
                            name="requester_schedule_id"
                            id="requester_schedule_id"
                        >

                        <input
                            type="hidden"
                            name="requester_reservation_id"
                            id="requester_reservation_id"
                        >

                        <input
                            type="hidden"
                            name="requester_room_id"
                            id="requester_room_id"
                        >


                        <input
                            type="hidden"
                            name="target_type"
                            id="target_type"
                        >

                        <input
                            type="hidden"
                            name="target_schedule_id"
                            id="target_schedule_id"
                        >

                        <input
                            type="hidden"
                            name="target_reservation_id"
                            id="target_reservation_id"
                        >

                        <input
                            type="hidden"
                            name="target_room_id"
                            id="target_room_id"
                        >

                        <input
                            type="hidden"
                            name="target_user_id"
                            id="target_user_id"
                        >

                        <input
                            type="hidden"
                            name="swap_date"
                            id="swap_date"
                        >

                        <input
                            type="hidden"
                            name="start_time"
                            id="start_time"
                        >

                        <input
                            type="hidden"
                            name="end_time"
                            id="end_time"
                        >

                        <input
                            type="hidden"
                            name="reason"
                            id="reason"
                        >


                        <div class="buttons">

                            <button
                                type="button"
                                class="btn btn-reset"
                                onclick="resetSwap()"
                            >
                                Reset
                            </button>

                            <button
                                type="submit"
                                class="btn btn-send"
                            >
                                Send Request
                            </button>

                        </div>

                    </div>

                </form>

            </div>


            <script>

                /*
                |--------------------------------------------------------------------------
                | STEP 1
                |--------------------------------------------------------------------------
                */

                function selectRequester(
                    type,
                    id,
                    roomId,
                    day,
                    time,
                    element
                ){

                    document
                        .querySelectorAll('.booking-card')
                        .forEach(function(card){
                            card.classList.remove('selected');
                        });

                    element.classList.add('selected');


                    document.getElementById(
                        'requester_type'
                    ).value = type;


                    document.getElementById(
                        'requester_room_id'
                    ).value = roomId;


                    if(type === 'schedule'){

                        document.getElementById(
                            'requester_schedule_id'
                        ).value = id;

                        document.getElementById(
                            'requester_reservation_id'
                        ).value = '';

                    }else{

                        document.getElementById(
                            'requester_reservation_id'
                        ).value = id;

                        document.getElementById(
                            'requester_schedule_id'
                        ).value = '';

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | STEP 2
                |--------------------------------------------------------------------------
                */

                function selectTarget(
                    type,
                    id,
                    roomId,
                    userId,
                    date,
                    time,
                    element
                ){

                    document
                        .querySelectorAll('.target-booking')
                        .forEach(function(card){
                            card.classList.remove('selected');
                        });

                    element.classList.add('selected');


                    document.getElementById(
                        'target_type'
                    ).value = type;


                    document.getElementById(
                        'target_room_id'
                    ).value = roomId;


                    document.getElementById(
                        'target_user_id'
                    ).value = userId;


                    document.getElementById(
                        'swap_date'
                    ).value = date;


                    /*
                    |--------------------------------------------------------------------------
                    | Determine Schedule / Reservation
                    |--------------------------------------------------------------------------
                    */

                    if(type === 'schedule'){

                        document.getElementById(
                            'target_schedule_id'
                        ).value = id;

                        document.getElementById(
                            'target_reservation_id'
                        ).value = '';

                    }else{

                        document.getElementById(
                            'target_reservation_id'
                        ).value = id;

                        document.getElementById(
                            'target_schedule_id'
                        ).value = '';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Extract Time
                    |--------------------------------------------------------------------------
                    */

                    let parts = time.split('-');

                    if(parts.length === 2){

                        document.getElementById(
                            'start_time'
                        ).value = parts[0].trim();

                        document.getElementById(
                            'end_time'
                        ).value = parts[1].trim();

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Filter Panel
                |--------------------------------------------------------------------------
                */

                function toggleFilter(){

                    let panel =
                        document.getElementById(
                            'filterPanel'
                        );

                    if(panel.style.display === 'block'){

                        panel.style.display = 'none';

                    }else{

                        panel.style.display = 'block';

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Search Rooms
                |--------------------------------------------------------------------------
                */

                function searchRooms(){

                    let search =
                        document
                        .getElementById('roomSearch')
                        .value
                        .toLowerCase();

                    document
                        .querySelectorAll('.target-booking')
                        .forEach(function(card){

                            let room =
                                card.dataset.room;

                            if(room.includes(search)){

                                card.style.display = '';

                            }else{

                                card.style.display = 'none';

                            }

                        });

                }


                /*
                |--------------------------------------------------------------------------
                | Department / Building Filter
                |--------------------------------------------------------------------------
                */

                function filterRooms(){

                    let department =
                        document
                        .getElementById('departmentFilter')
                        .value;

                    let building =
                        document
                        .getElementById('buildingFilter')
                        .value;

                    document
                        .querySelectorAll('.target-booking')
                        .forEach(function(card){

                            let matchDepartment =
                                !department ||
                                card.dataset.department === department;

                            let matchBuilding =
                                !building ||
                                card.dataset.building === building;

                            if(
                                matchDepartment &&
                                matchBuilding
                            ){

                                card.style.display = '';

                            }else{

                                card.style.display = 'none';

                            }

                        });

                }


                /*
                |--------------------------------------------------------------------------
                | Reason
                |--------------------------------------------------------------------------
                */

                function selectReason(reason, element){

                    document
                        .querySelectorAll('.reason-option')
                        .forEach(function(option){

                            option.classList.remove('selected');

                        });

                    element.classList.add('selected');


                    if(reason === 'Other'){

                        document.getElementById(
                            'reason'
                        ).value = '';

                    }else{

                        document.getElementById(
                            'reason'
                        ).value = reason;

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Reset
                |--------------------------------------------------------------------------
                */

                function resetSwap(){

                    document
                        .getElementById('swapForm')
                        .reset();


                    document
                        .querySelectorAll('.booking-card')
                        .forEach(function(card){

                            card.classList.remove(
                                'selected'
                            );

                        });


                    document
                        .querySelectorAll('.reason-option')
                        .forEach(function(option){

                            option.classList.remove(
                                'selected'
                            );

                        });

                }


                /*
                |--------------------------------------------------------------------------
                | Before Submit
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById('swapForm')
                    .addEventListener(
                        'submit',
                        function(event){

                            /*
                            |--------------------------------------------------
                            | If "Other" is selected, use textarea
                            |--------------------------------------------------
                            */

                            let selectedReason =
                                document
                                .querySelector(
                                    '.reason-option.selected'
                                );

                            let reasonField =
                                document.getElementById(
                                    'reason'
                                );

                            let otherReason =
                                document.getElementById(
                                    'otherReason'
                                ).value.trim();


                            if(
                                selectedReason &&
                                selectedReason.innerText.trim()
                                    === 'Other'
                            ){

                                reasonField.value =
                                    otherReason;

                            }


                            /*
                            |--------------------------------------------------
                            | Basic selection check
                            |--------------------------------------------------
                            */

                            if(
                                !document.getElementById(
                                    'requester_room_id'
                                ).value
                            ){

                                event.preventDefault();

                                alert(
                                    'Please select your room first.'
                                );

                                return;

                            }


                            if(
                                !document.getElementById(
                                    'target_room_id'
                                ).value
                            ){

                                event.preventDefault();

                                alert(
                                    'Please select the other faculty room.'
                                );

                                return;

                            }


                            if(
                                !reasonField.value
                            ){

                                event.preventDefault();

                                alert(
                                    'Please select or enter a reason.'
                                );

                                return;

                            }

                        }
                    );

            </script>

        </main>

    </div>

    @include('footerheader.footer')

</div>