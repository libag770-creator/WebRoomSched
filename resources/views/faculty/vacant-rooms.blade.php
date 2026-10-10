@include('footerheader.header')

<div class="wrapper">

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content faculty-vacant-view">

            @if(session('success'))
                <div class="alert success-alert">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert error-alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif


            <style>

                /* =========================================================
                   MAIN CONTAINER
                ========================================================= */

                .vacant-container {
                    max-width: 1250px;
                    margin: 0 auto;
                    padding: 10px 0 40px;
                }

                .vacant-title {
                    font-size: 28px;
                    font-weight: 800;
                    color: #173b27;
                    margin-bottom: 5px;
                }

                .vacant-subtitle {
                    color: #6b7280;
                    margin-bottom: 25px;
                    font-size: 14px;
                }


                /* =========================================================
                   ALERTS
                ========================================================= */

                .alert {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 13px 16px;
                    margin-bottom: 20px;
                    border-radius: 10px;
                    font-size: 14px;
                    font-weight: 600;
                }

                .success-alert {
                    background: #e8f7ed;
                    color: #176b3a;
                    border: 1px solid #b9e2c7;
                }

                .error-alert {
                    background: #fdecec;
                    color: #b42318;
                    border: 1px solid #f3b5b0;
                }


                /* =========================================================
                   SEARCH BOX
                ========================================================= */

                .search-box {
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 14px;
                    padding: 22px;
                    margin-bottom: 25px;
                    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
                }

                .search-row {
                    display: flex;
                    gap: 18px;
                    align-items: end;
                    flex-wrap: wrap;
                }

                .form-group {
                    display: flex;
                    flex-direction: column;
                    gap: 7px;
                }

                .form-group label {
                    font-size: 13px;
                    font-weight: 700;
                    color: #344054;
                }

                .form-group input,
                .form-group select {
                    width: 220px;
                    height: 44px;
                    padding: 0 13px;
                    border: 1px solid #d0d5d2;
                    border-radius: 9px;
                    background: #fff;
                    font-size: 14px;
                    outline: none;
                    box-sizing: border-box;
                }

                .form-group input:focus,
                .form-group select:focus {
                    border-color: #198754;
                    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.10);
                }

                .time-search-wrap {
                    position: relative;
                    width: 320px;
                }

                .time-search-wrap input {
                    width: 100%;
                    padding-left: 40px;
                    padding-right: 42px;
                }

                .time-search-icon {
                    position: absolute;
                    left: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #198754;
                    pointer-events: none;
                    z-index: 2;
                }

                .clear-time-button {
                    position: absolute;
                    right: 8px;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 30px;
                    height: 30px;
                    border: none;
                    border-radius: 50%;
                    background: #eef3f0;
                    color: #66736c;
                    cursor: pointer;
                    z-index: 3;
                }

                .clear-time-button:hover {
                    background: #dfe8e2;
                    color: #198754;
                }

                .time-search-help {
                    margin-top: 6px;
                    color: #7a8580;
                    font-size: 11px;
                    line-height: 1.4;
                    max-width: 320px;
                }

                .searching-time {
                    font-size: 11px;
                    color: #198754;
                    margin-top: 5px;
                    display: none;
                }

                .search-button {
                    height: 44px;
                    padding: 0 25px;
                    background: #198754;
                    color: #ffffff;
                    border: none;
                    border-radius: 9px;
                    cursor: pointer;
                    font-weight: 700;
                    transition: .2s;
                }

                .search-button:hover {
                    background: #157347;
                    transform: translateY(-1px);
                }


                /* =========================================================
                   FILTER BAR
                ========================================================= */

                .filter-wrapper {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 15px;
                    flex-wrap: wrap;
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 14px;
                    padding: 15px;
                    margin-bottom: 25px;
                    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
                }

                .filter-title {
                    font-size: 14px;
                    font-weight: 800;
                    color: #344054;
                }

                .filter-buttons {
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                }

                .filter-btn {
                    border: 1px solid #d9dfdc;
                    background: #ffffff;
                    color: #344054;
                    padding: 9px 14px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 13px;
                    font-weight: 700;
                    transition: .2s;
                }

                .filter-btn:hover {
                    border-color: #198754;
                    color: #198754;
                }

                .filter-btn.active {
                    background: #198754;
                    color: #ffffff;
                    border-color: #198754;
                }

                .filter-count {
                    margin-left: 4px;
                    opacity: .8;
                }


                /* =========================================================
                   DEPARTMENT
                ========================================================= */

                .department-section {
                    margin-bottom: 30px;
                }

                .department-title {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    font-size: 20px;
                    font-weight: 800;
                    color: #173b27;
                    margin-bottom: 12px;
                }

                .department-title i {
                    color: #198754;
                }


                /* =========================================================
                   BUILDING
                ========================================================= */

                .building {
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 14px;
                    margin-bottom: 18px;
                    overflow: hidden;
                    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
                }

                .building-title {
                    display: flex;
                    align-items: center;
                    gap: 9px;
                    background: #f5f8f6;
                    padding: 14px 17px;
                    font-weight: 800;
                    font-size: 16px;
                    color: #344054;
                    border-bottom: 1px solid #e5e9e6;
                }

                .building-title i {
                    color: #198754;
                }

                .rooms {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 15px;
                    padding: 17px;
                }


                /* =========================================================
                   ROOM CARD
                ========================================================= */

                .room-card {
                    border: 1px solid #e1e7e3;
                    border-radius: 12px;
                    padding: 17px;
                    background: #ffffff;
                    box-shadow: 0 2px 7px rgba(0, 0, 0, 0.04);
                    transition: .2s;
                    min-width: 0;
                }

                .room-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
                    border-color: #b9d7c4;
                }

                .room-card.hidden {
                    display: none;
                }

                .room-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 10px;
                    margin-bottom: 8px;
                }

                .room-name {
                    font-size: 17px;
                    font-weight: 800;
                    color: #172b1e;
                }

                .department-name {
                    font-size: 12px;
                    color: #7a8580;
                    margin-bottom: 13px;
                }


               /* =========================================================
   ROOM STATUS
========================================================= */

.room-status {
    display: block;

    width: 100%;

    box-sizing: border-box;

    margin-top: 14px;

    padding: 12px;

    border-radius: 10px;

    border: 1px solid #e1e7e3;

    background: #fafcfb;

    overflow: hidden;
}


/* =========================================================
   STATUS LABEL
========================================================= */

.room-status-label {
    display: block;

    font-size: 10px;

    text-transform: uppercase;

    letter-spacing: .04em;

    font-weight: 800;

    color: #667085;

    line-height: 1.3;

    white-space: normal;
}


/* =========================================================
   STATUS VALUE
========================================================= */

.room-status-value {
    display: block;

    margin-top: 6px;

    font-size: 15px;

    font-weight: 900;

    line-height: 1.3;

    white-space: normal;

    overflow-wrap: anywhere;
}


/* =========================================================
   STATUS HELP
========================================================= */

.room-status-help {
    display: block;

    margin-top: 6px;

    font-size: 10px;

    color: #667085;

    line-height: 1.4;

    white-space: normal;

    overflow-wrap: anywhere;
}


/* =========================================================
   AVAILABLE
========================================================= */

.room-status.available {
    background: #f3fbf6;

    border-color: #b9dfc7;
}

.room-status.available .room-status-value {
    color: #176b3a;
}


/* =========================================================
   OCCUPIED
========================================================= */

.room-status.occupied {
    background: #fff5f5;

    border-color: #f3c2c2;
}

.room-status.occupied .room-status-value {
    color: #b42318;
}


/* =========================================================
   RESERVED
========================================================= */

.room-status.reserved {
    background: #fff8ef;

    border-color: #f1d29e;
}

.room-status.reserved .room-status-value {
    color: #b54708;
}

         
              

                /* =========================================================
                   ROOM FEATURES
                ========================================================= */

                .matched-time-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 6px;
                    margin-top: 2px;
                }

                .matched-time {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    margin-top: 2px;
                    padding: 6px 9px;
                    border-radius: 7px;
                    background: #eef8f1;
                    color: #176b3a;
                    font-size: 11px;
                    font-weight: 700;
                }

                .room-features {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 8px;
                    margin-top: 13px;
                }

                .feature {
                    background: #f7f9f8;
                    border: 1px solid #e9eeeb;
                    border-radius: 7px;
                    padding: 8px;
                    min-width: 0;
                }

                .feature-label {
                    display: block;
                    font-size: 10px;
                    text-transform: uppercase;
                    letter-spacing: .03em;
                    color: #7a8580;
                    margin-bottom: 2px;
                }

                .feature-value {
                    display: block;
                    font-size: 12px;
                    font-weight: 700;
                    color: #344054;
                    overflow-wrap: anywhere;
                }


                /* =========================================================
                   PURPOSE / DESCRIPTION
                ========================================================= */

                .room-purpose {
                    margin-top: 10px;
                    font-size: 12px;
                    color: #66736c;
                }

                .room-description {
                    margin-top: 8px;
                    font-size: 12px;
                    color: #66736c;
                    line-height: 1.5;
                }


                /* =========================================================
                   RESERVE BUTTON
                ========================================================= */

                .reserve-form {
                    margin-top: 15px;
                }

                .reserve-button {
                    width: 100%;
                    height: 40px;
                    border: none;
                    border-radius: 8px;
                    background: #198754;
                    color: #ffffff;
                    font-size: 13px;
                    font-weight: 800;
                    cursor: pointer;
                    transition: .2s;
                }

                .reserve-button:hover {
                    background: #157347;
                }


                /* =========================================================
                   EMPTY RESULT
                ========================================================= */

                .no-results {
                    padding: 30px;
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 12px;
                    color: #777;
                    text-align: center;
                }


                /* =========================================================
                   RESPONSIVE
                ========================================================= */

                @media (max-width: 1000px) {

                    .rooms {
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                    }

                }

                @media (max-width: 700px) {

                    .vacant-container {
                        padding: 5px 0 30px;
                    }

                    .vacant-title {
                        font-size: 23px;
                    }

                    .search-row {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .form-group input,
                    .form-group select,
                    .search-button,
                    .time-search-wrap {
                        width: 100%;
                    }

                    .filter-wrapper {
                        align-items: flex-start;
                        flex-direction: column;
                    }

                    .filter-buttons {
                        width: 100%;
                    }

                    .filter-btn {
                        flex: 1;
                    }

                    .rooms {
                        grid-template-columns: 1fr;
                    }

                }

                /* Refined vacant-room search and results */
                .faculty-vacant-view { background: #f5f8f6; }
                .vacant-container { max-width: 1320px; padding: 12px 0 42px; }
                .vacant-heading {
                    position: relative;
                    margin-bottom: 21px;
                    padding: 25px 28px;
                    overflow: hidden;
                    border: 1px solid #dfeae2;
                    border-radius: 20px;
                    background: linear-gradient(115deg, #fff 0%, #f0f8f2 100%);
                    box-shadow: 0 8px 24px rgba(22, 73, 45, .045);
                }
                .vacant-heading::after {
                    position: absolute; top: -62px; right: -30px; width: 210px; height: 210px;
                    border: 1px solid rgba(28,117,74,.10); border-radius: 50%;
                    box-shadow: 0 0 0 24px rgba(28,117,74,.035), 0 0 0 48px rgba(28,117,74,.025); content: "";
                }
                .vacant-eyebrow { margin: 0 0 8px; color: #56816a; font-size: 9px; font-weight: 800; letter-spacing: .14em; }
                .vacant-title { margin: 0; color: #193e2d; font-size: 27px; font-weight: 760; letter-spacing: -.035em; }
                .vacant-subtitle { max-width: 650px; margin: 7px 0 0; color: #708078; font-size: 12px; line-height: 1.65; }
                .search-box { margin-bottom: 24px; padding: 20px; border-color: #e3ebe5; border-radius: 17px; box-shadow: 0 5px 18px rgba(25,57,39,.045); }
                .search-row { align-items: flex-end; gap: 15px; }
                .form-group { gap: 7px; }
                .form-group label { color: #405348; font-size: 11px; font-weight: 750; }
                .select-control { position: relative; width: 255px; }
                .select-control > i:first-child { position: absolute; z-index: 1; top: 50%; left: 14px; transform: translateY(-50%); color: #538167; font-size: 13px; pointer-events: none; }
                .form-group .select-control select {
                    width: 100%; height: 48px; padding: 0 40px 0 40px; appearance: none; -webkit-appearance: none;
                    border: 1px solid #dce6df; border-radius: 12px; outline: 0; background-color: #fbfdfb;
                    color: #294638; font-size: 12px; font-weight: 650; cursor: pointer;
                    transition: border-color .16s ease, box-shadow .16s ease, background .16s ease;
                }
                .form-group .select-control select:hover { border-color: #b5d0bd; background-color: #fff; }
                .form-group .select-control select:focus { border-color: #278257; background-color: #fff; box-shadow: 0 0 0 4px rgba(39,130,87,.11); }
                .form-group .select-control select:disabled { color: #9aa79f; background-color: #f3f6f4; cursor: not-allowed; }
                .select-control::after { position: absolute; top: 50%; right: 15px; color: #75877b; content: ""; transform: translateY(-65%) rotate(45deg); width: 7px; height: 7px; border-right: 1.5px solid currentColor; border-bottom: 1.5px solid currentColor; pointer-events: none; }
                .search-button { height: 48px; padding: 0 22px; border-radius: 12px; background: #19764d; box-shadow: 0 5px 12px rgba(25,118,77,.15); font-size: 12px; }
                .search-button:hover { background: #145f3d; }
                .department-section { margin-bottom: 25px; }
                .department-title { color: #284735; font-size: 16px; }
                .building { border-color: #e3ebe5; border-radius: 15px; box-shadow: 0 5px 16px rgba(25,57,39,.04); }
                .building-title { padding: 14px 17px; border-color: #e9efeb; background: #f7faf8; color: #334c3d; font-size: 13px; }
                .rooms { gap: 13px; padding: 15px; }
                .room-card { border-color: #e3ebe5; border-radius: 13px; box-shadow: 0 3px 10px rgba(25,57,39,.035); }
                .room-card:hover { transform: translateY(-2px); border-color: #b9d7c4; box-shadow: 0 9px 20px rgba(25,80,49,.08); }
                .room-name { color: #244b35; font-size: 15px; }
                .room-features .feature { border-color: #e8efea; border-radius: 9px; background: #f8fbf9; }
                .reserve-button { height: 42px; border-radius: 10px; background: #19764d; }
                .reserve-button:hover { background: #145f3d; }
                .no-results { border-color: #e3ebe5; border-radius: 14px; color: #708078; }
                @media (max-width: 700px) {
                    .vacant-container { padding-top: 4px; }
                    .vacant-heading { margin-bottom: 16px; padding: 21px 19px; border-radius: 16px; }
                    .vacant-heading::after { right: -115px; }
                    .vacant-title { font-size: 23px; }
                    .search-box { padding: 16px; }
                    .select-control { width: 100%; }
                    .form-group .select-control select { height: 46px; }
                    .search-button { height: 46px; }
                }

            </style>


            <div class="vacant-container">

                <div class="vacant-heading">
                    <p class="vacant-eyebrow">FACULTY WORKSPACE / ROOM AVAILABILITY</p>
                    <h1 class="vacant-title">Find a vacant room</h1>
                    <p class="vacant-subtitle">
                        Choose a day and official time slot to check classroom availability. Time slots follow the Chair's schedule.
                    </p>
                </div>


            {{-- =====================================================
                 SEARCH
            ====================================================== --}}

            <div class="search-box">

                <form
                    action="{{ route('faculty.vacant') }}"
                    method="GET"
                    id="vacantSearchForm"
                >

                    <div class="search-row">

                        {{-- DAY --}}
                        <div class="form-group">

                            <label for="daySearch">
                                Day
                            </label>

                            <div class="select-control">
                                <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                                <select id="daySearch" name="day" required aria-label="Choose a day">

                                <option value="">
                                    Select Day
                                </option>

                                @php
                                    $dayNames = [
                                        'MON' => 'Monday',
                                        'TUE' => 'Tuesday',
                                        'WED' => 'Wednesday',
                                        'THU' => 'Thursday',
                                        'FRI' => 'Friday',
                                        'SAT' => 'Saturday',
                                    ];

                                    $selectedDayValue = strtoupper(
                                        (string) ($selectedDay ?? request('day', ''))
                                    );
                                @endphp

                                @foreach($dayNames as $dayCode => $dayName)

                                    <option
                                        value="{{ $dayCode }}"
                                        {{ $selectedDayValue === $dayCode ? 'selected' : '' }}
                                    >
                                        {{ $dayName }}
                                    </option>

                                @endforeach

                                </select>
                            </div>

                        </div>


                        {{-- TIME SLOT --}}
                        <div class="form-group">

                            <label for="timeSlotSearch">
                                Time
                            </label>

                            <div class="select-control">
                                <i class="fa-regular fa-clock" aria-hidden="true"></i>
                                <select id="timeSlotSearch" name="time_slot_id" required aria-label="Choose a time slot">

                                <option value="">
                                    Select Day First
                                </option>

                                @foreach(($timeSlots ?? collect()) as $slot)

                                    @php
                                        $slotDay = strtoupper(
                                            trim((string) $slot->day)
                                        );

                                        $slotStart = \Carbon\Carbon::parse(
                                            $slot->start_time
                                        )->format('H:i');

                                        $slotEnd = \Carbon\Carbon::parse(
                                            $slot->end_time
                                        )->format('H:i');

                                        $slotLabel =
                                            \Carbon\Carbon::parse(
                                                $slot->start_time
                                            )->format('g:i A')
                                            .
                                            ' - '
                                            .
                                            \Carbon\Carbon::parse(
                                                $slot->end_time
                                            )->format('g:i A');
                                    @endphp

                                    <option
                                        value="{{ $slot->id }}"
                                        data-day="{{ $slotDay }}"
                                        data-time="{{ $slotStart }}-{{ $slotEnd }}"
                                        {{ (string) ($selectedTimeSlotId ?? request('time_slot_id', '')) === (string) $slot->id ? 'selected' : '' }}
                                    >
                                        {{ $slotLabel }}
                                    </option>

                                @endforeach

                                </select>
                            </div>

                        </div>


                        {{-- SEARCH --}}
                        <button
                            type="submit"
                            class="search-button"
                            id="searchButton"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Search
                        </button>

                    </div>

                </form>

            </div>


            {{-- =====================================================
                 RESULTS
            ====================================================== --}}

            <div id="vacantResults">

                @include('faculty.partials.vacant-results')

            </div>

        </main>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const daySearch =
        document.getElementById('daySearch');

    const timeSlotSearch =
        document.getElementById('timeSlotSearch');

    const searchForm =
        document.getElementById('vacantSearchForm');

    const resultsContainer =
        document.getElementById('vacantResults');

    let activeRequest = null;


    /*
    |--------------------------------------------------------------------------
    | FILTER TIME SLOTS BY DAY
    |--------------------------------------------------------------------------
    */

    function filterTimeSlots() {

        if (!daySearch || !timeSlotSearch) {
            return;
        }

        const selectedDay =
            daySearch.value.trim().toUpperCase();

        const currentValue =
            timeSlotSearch.value;

        let hasVisibleSlot = false;

        Array.from(
            timeSlotSearch.options
        ).forEach(function (option, index) {

            if (index === 0) {
                option.hidden = false;
                option.disabled = false;
                return;
            }

            const optionDay =
                (option.dataset.day || '')
                    .trim()
                    .toUpperCase();

            const matches =
                selectedDay !== '' &&
                optionDay === selectedDay;

            option.hidden = !matches;
            option.disabled = !matches;

            if (matches) {
                hasVisibleSlot = true;
            }
        });


        const currentOption =
            timeSlotSearch.querySelector(
                'option[value="' +
                CSS.escape(currentValue) +
                '"]'
            );


        if (
            !currentOption ||
            currentOption.disabled ||
            currentOption.hidden
        ) {

            timeSlotSearch.value = '';

        }


        if (!selectedDay) {

            timeSlotSearch.value = '';

            timeSlotSearch.options[0].textContent =
                'Select Day First';

        } else if (!hasVisibleSlot) {

            timeSlotSearch.value = '';

            timeSlotSearch.options[0].textContent =
                'No Time Slots Available';

        } else {

            timeSlotSearch.options[0].textContent =
                'Select Time';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    filterTimeSlots();


    if (daySearch) {

        daySearch.addEventListener(
            'change',
            function () {

                filterTimeSlots();

                /*
                 * Search automatically when both
                 * values are available.
                 */
                if (
                    daySearch.value &&
                    timeSlotSearch &&
                    timeSlotSearch.value
                ) {
                    runSearch();
                }
            }
        );

    }


    if (timeSlotSearch) {

        timeSlotSearch.addEventListener(
            'change',
            function () {

                if (
                    daySearch &&
                    daySearch.value &&
                    timeSlotSearch.value
                ) {
                    runSearch();
                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    function runSearch() {

        if (
            !searchForm ||
            !daySearch ||
            !timeSlotSearch ||
            !resultsContainer
        ) {
            return;
        }


        const day =
            daySearch.value.trim();

        const timeSlotId =
            timeSlotSearch.value.trim();


        if (!day || !timeSlotId) {

            return;

        }


        if (activeRequest) {

            activeRequest.abort();

        }


        resultsContainer.innerHTML = `
            <div class="no-results">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <div style="margin-top:8px;">
                    Loading room availability...
                </div>
            </div>
        `;


        const url =
            new URL(
                searchForm.action,
                window.location.origin
            );


        url.searchParams.set(
            'day',
            day
        );

        url.searchParams.set(
            'time_slot_id',
            timeSlotId
        );

        url.searchParams.set(
            'partial',
            '1'
        );


        activeRequest =
            new XMLHttpRequest();


        activeRequest.open(
            'GET',
            url.toString(),
            true
        );


        activeRequest.setRequestHeader(
            'X-Requested-With',
            'XMLHttpRequest'
        );


        activeRequest.setRequestHeader(
            'Accept',
            'text/html'
        );


        activeRequest.onload =
    function () {

        if (
            activeRequest.status >= 200 &&
            activeRequest.status < 300
        ) {

            const responseText =
                activeRequest.responseText;

            /*
            |--------------------------------------------------------------------------
            | CHECK IF SERVER RETURNED ONLY THE PARTIAL
            |--------------------------------------------------------------------------
            */

            const parser =
                new DOMParser();

            const responseDocument =
                parser.parseFromString(
                    responseText,
                    'text/html'
                );

            /*
            |--------------------------------------------------------------------------
            | FIND THE ACTUAL VACANT RESULTS
            |--------------------------------------------------------------------------
            |
            | If the controller accidentally returns the complete
            | faculty.vacant page, extract only #vacantResults.
            |
            | This prevents the header/sidebar/page from appearing
            | inside the existing page.
            |--------------------------------------------------------------------------
            */

            const returnedResults =
                responseDocument.querySelector(
                    '#vacantResults'
                );

            if (returnedResults) {

                resultsContainer.innerHTML =
                    returnedResults.innerHTML;

            } else {

                /*
                |--------------------------------------------------------------------------
                | NORMAL PARTIAL RESPONSE
                |--------------------------------------------------------------------------
                */

                resultsContainer.innerHTML =
                    responseText;

            }

        } else {

            resultsContainer.innerHTML = `

                <div class="no-results">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <div style="margin-top:8px;">

                        Unable to load room availability.
                        Please try again.

                    </div>

                </div>

            `;

        }

    };


        activeRequest.onerror =
            function () {

                resultsContainer.innerHTML = `
                    <div class="no-results">
                        Unable to connect to the server.
                        Please try again.
                    </div>
                `;

            };


        activeRequest.send();

    }


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    if (searchForm) {

        searchForm.addEventListener(
            'submit',
            function (event) {

                event.preventDefault();

                if (
                    daySearch &&
                    timeSlotSearch &&
                    daySearch.value &&
                    timeSlotSearch.value
                ) {

                    runSearch();

                }

            }
        );

    }

});
</script>

@include('footerheader.footer')
