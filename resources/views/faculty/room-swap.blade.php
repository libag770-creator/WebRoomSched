@include('footerheader.header')

<div class="wrapper">

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content">

            <style>

                * {
                    box-sizing: border-box;
                }

                .swap-container {
                    max-width: 1400px;
                    margin: 0 auto;
                    padding: 25px;
                }

                .swap-title {
                    font-size: 28px;
                    font-weight: 800;
                    color: #176b3a;
                    margin-bottom: 5px;
                }

                .swap-subtitle {
                    color: #667085;
                    margin-bottom: 25px;
                }

                /* =====================================================
                   ALERTS
                ===================================================== */

                .alert-success {
                    background: #e8f5e9;
                    color: #176b3a;
                    border: 1px solid #b7dfbd;
                    padding: 14px 18px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    font-weight: 600;
                }

                .alert-error {
                    background: #fdecec;
                    color: #b42318;
                    border: 1px solid #f2b8b5;
                    padding: 14px 18px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    font-weight: 600;
                }

                /* =====================================================
                   STEP
                ===================================================== */

                .step {
                    background: #ffffff;
                    border-radius: 14px;
                    padding: 24px;
                    margin-bottom: 25px;
                    border: 1px solid #e5e7eb;
                    box-shadow: 0 3px 12px rgba(0,0,0,.05);
                }

                .step-title {
                    font-size: 19px;
                    font-weight: 800;
                    color: #176b3a;
                    margin-bottom: 6px;
                }

                .step-description {
                    color: #667085;
                    font-size: 13px;
                    margin-bottom: 20px;
                }

                /* =====================================================
                   STEP 1
                ===================================================== */

                .booking-grid {
                    display: grid;
                    grid-template-columns:
                        repeat(auto-fill, minmax(280px, 1fr));
                    gap: 15px;
                }

                .booking-card {
                    background: #ffffff;
                    border: 1px solid #d9dee5;
                    border-radius: 10px;
                    padding: 16px;
                    cursor: pointer;
                    transition: .2s ease;
                }

                .booking-card:hover {
                    border-color: #176b3a;
                    box-shadow: 0 4px 12px rgba(23,107,58,.12);
                    transform: translateY(-2px);
                }

                .booking-card.selected {
                    border: 2px solid #176b3a;
                    background: #f0faf3;
                    box-shadow: 0 4px 14px rgba(23,107,58,.15);
                }

                .booking-card strong {
                    display: block;
                    color: #176b3a;
                    font-size: 16px;
                    margin-bottom: 6px;
                }

                .booking-card small {
                    color: #667085;
                    line-height: 1.6;
                }

                .selected-swap-box {
                    display: none;
                    margin-top: 18px;
                    padding: 15px;
                    border-radius: 10px;
                    background: #f0faf3;
                    border: 1px solid #b7dfbd;
                    color: #176b3a;
                }

                /* =====================================================
                   SEARCH
                ===================================================== */

                .swap-search-box {
                    background: #f8faf9;
                    border: 1px solid #dfe8e1;
                    border-radius: 10px;
                    padding: 15px;
                    margin-bottom: 18px;
                }

                .swap-search-label {
                    color: #176b3a;
                    font-size: 13px;
                    font-weight: 800;
                    margin-bottom: 8px;
                }

                .swap-search-row {
                    position: relative;
                    display: flex;
                    align-items: center;
                }

                .swap-search-input {
                    width: 100%;
                    height: 44px;
                    border: 1px solid #d0d5d9;
                    border-radius: 8px;
                    padding: 0 45px 0 13px;
                    font-size: 13px;
                    outline: none;
                    background: #ffffff;
                }

                .swap-search-input:focus {
                    border-color: #176b3a;
                    box-shadow: 0 0 0 3px rgba(23,107,58,.10);
                }

                .swap-search-clear {
                    position: absolute;
                    right: 8px;
                    width: 30px;
                    height: 30px;
                    border: none;
                    border-radius: 6px;
                    background: transparent;
                    color: #667085;
                    cursor: pointer;
                }

                .swap-search-clear:hover {
                    background: #eaecf0;
                    color: #176b3a;
                }

                .swap-search-help {
                    margin-top: 7px;
                    color: #667085;
                    font-size: 11px;
                }

                .swap-room-search-results {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
                    gap: 12px;
                    margin-bottom: 20px;
                }

                .swap-search-room-card {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    background: #ffffff;
                    border: 1px solid #dfe4e8;
                    border-radius: 10px;
                    padding: 14px;
                    transition: .2s ease;
                }

                .swap-search-room-card:hover {
                    border-color: #176b3a;
                    box-shadow: 0 4px 12px rgba(23,107,58,.10);
                }

                .swap-search-room-icon {
                    width: 42px;
                    height: 42px;
                    flex-shrink: 0;
                    border-radius: 8px;
                    background: #eaf6ef;
                    color: #176b3a;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .swap-search-room-details {
                    flex: 1;
                    min-width: 0;
                }

                .swap-search-room-details strong {
                    display: block;
                    color: #176b3a;
                    font-size: 14px;
                    margin-bottom: 4px;
                }

                .swap-search-room-details span {
                    display: block;
                    color: #667085;
                    font-size: 10px;
                    margin-top: 2px;
                }

                .swap-search-room-button {
                    border: none;
                    background: #176b3a;
                    color: #ffffff;
                    border-radius: 7px;
                    padding: 8px 10px;
                    cursor: pointer;
                    font-size: 10px;
                    font-weight: 800;
                    white-space: nowrap;
                }

                .swap-search-room-button:hover {
                    background: #12572f;
                }

                .swap-merge-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    margin-top: 4px;
                    padding: 3px 7px;
                    border-radius: 12px;
                    background: #fff4d6;
                    color: #8a5a00;
                    font-size: 8px;
                    font-weight: 900;
                }

                .swap-merge-program {
                    font-size: 9px;
                    font-weight: 900;
                    color: #8a5a00;
                    margin-top: 3px;
                }

                .swap-selected-room-note {
                    margin-top: 8px;
                    padding: 8px 10px;
                    border-radius: 7px;
                    background: #fff4d6;
                    color: #8a5a00;
                    font-size: 11px;
                    font-weight: 700;
                }


                .my-schedule-card-header {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 12px;
                }

                .my-schedule-card-header strong {
                    color: #176b3a;
                    font-size: 16px;
                }

                .my-schedule-subject {
                    color: #344054;
                    font-size: 12px;
                    margin-top: 4px;
                }

                .schedule-room-badge {
                    background: #eaf6ef;
                    color: #176b3a;
                    border-radius: 20px;
                    padding: 5px 9px;
                    font-size: 10px;
                    font-weight: 800;
                    white-space: nowrap;
                }

                .my-schedule-details {
                    margin-top: 13px;
                    display: grid;
                    gap: 7px;
                }

                .my-schedule-details div {
                    color: #667085;
                    font-size: 11px;
                }

                .my-schedule-details i {
                    width: 16px;
                    color: #176b3a;
                }

                .my-schedule-details strong {
                    display: inline;
                    color: #344054;
                    font-size: 11px;
                }


                /* =====================================================
                   STEP 2 NAVIGATION
                ===================================================== */

                .swap-navigation-title {
                    display: flex;
                    align-items: center;
                    gap: 9px;
                    color: #176b3a;
                    font-size: 15px;
                    font-weight: 800;
                    margin-bottom: 14px;
                }

                .swap-navigation-list {
                    display: grid;
                    grid-template-columns:
                        repeat(auto-fill, minmax(280px, 1fr));
                    gap: 12px;
                }

                .swap-navigation-card {
                    width: 100%;
                    border: 1px solid #dfe4e8;
                    background: #ffffff;
                    border-radius: 10px;
                    padding: 16px;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    text-align: left;
                    transition: .2s ease;
                }

                .swap-navigation-card:hover {
                    border-color: #176b3a;
                    background: #f7fcf8;
                    transform: translateY(-1px);
                }

                .swap-navigation-left {
                    display: flex;
                    align-items: center;
                    gap: 13px;
                }

                .swap-navigation-icon {
                    width: 42px;
                    height: 42px;
                    border-radius: 9px;
                    background: #eaf6ef;
                    color: #176b3a;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 17px;
                }

                .swap-navigation-name {
                    color: #1f2937;
                    font-weight: 800;
                    font-size: 14px;
                }

                .swap-navigation-description {
                    color: #667085;
                    font-size: 12px;
                    margin-top: 3px;
                }

                .swap-navigation-arrow {
                    color: #98a2b3;
                }

                .swap-breadcrumb {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-bottom: 18px;
                    color: #667085;
                    font-size: 13px;
                }

                .swap-breadcrumb button {
                    border: none;
                    background: transparent;
                    color: #176b3a;
                    cursor: pointer;
                    font-weight: 700;
                }

                /* =====================================================
                   ROOMS
                ===================================================== */

                .swap-room-grid {
                    display: grid;
                    grid-template-columns:
                        repeat(auto-fill, minmax(320px, 1fr));
                    gap: 16px;
                }

                .swap-room-card {
                    border: 1px solid #e0e5e9;
                    border-radius: 12px;
                    padding: 18px;
                    background: #ffffff;
                }

                .swap-room-header {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    margin-bottom: 15px;
                }

                .swap-room-icon {
                    width: 44px;
                    height: 44px;
                    background: #eaf6ef;
                    color: #176b3a;
                    border-radius: 9px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .swap-room-name {
                    font-size: 16px;
                    font-weight: 800;
                    color: #176b3a;
                }

                .swap-room-building {
                    font-size: 12px;
                    color: #667085;
                    margin-top: 3px;
                }

                .swap-room-information {
                    display: grid;
                    grid-template-columns:
                        repeat(2, 1fr);
                    gap: 8px;
                    margin-bottom: 15px;
                }

                .swap-room-info-item {
                    background: #f8faf9;
                    border-radius: 7px;
                    padding: 9px;
                }

                .swap-room-info-label {
                    color: #667085;
                    font-size: 10px;
                    text-transform: uppercase;
                    font-weight: 700;
                }

                .swap-room-info-value {
                    color: #1f2937;
                    font-size: 12px;
                    font-weight: 700;
                    margin-top: 3px;
                }

                .swap-room-info-value.available {
                    color: #176b3a;
                }

                .swap-view-room-button {
                    width: 100%;
                    border: none;
                    border-radius: 7px;
                    padding: 11px;
                    background: #176b3a;
                    color: white;
                    cursor: pointer;
                    font-weight: 700;
                }

                .swap-view-room-button:hover {
                    background: #12572f;
                }

                /* =====================================================
                   SCHEDULE
                ===================================================== */

                .swap-schedule-filter {
                    margin-bottom: 15px;
                    background: #f0faf3;
                    border: 1px solid #cce8d2;
                    border-radius: 9px;
                    padding: 13px 15px;
                }

                .swap-schedule-filter strong {
                    color: #176b3a;
                }

                .swap-schedule-filter span {
                    color: #344054;
                }

                .swap-excel-wrapper {
                    overflow-x: auto;
                    border: 1px solid #dfe4e8;
                    border-radius: 8px;
                }

                .swap-excel-table {
                    width: 100%;
                    min-width: 900px;
                    border-collapse: collapse;
                    table-layout: fixed;
                }

                .swap-excel-table th {
                    background: #176b3a;
                    color: white;
                    padding: 11px 7px;
                    font-size: 11px;
                    border: 1px solid #d0d5d9;
                    text-align: center;
                }

                .swap-excel-table td {
                    border: 1px solid #dfe4e8;
                    height: 78px;
                    padding: 6px;
                    vertical-align: middle;
                    text-align: center;
                }

                .swap-excel-time {
                    background: #fafafa;
                    font-weight: 800;
                    font-size: 10px;
                    color: #344054;
                    width: 95px;
                }

                .swap-excel-empty {
                    color: #98a2b3;
                    background: #ffffff;
                    font-size: 10px;
                }

                .swap-excel-occupied {
                    background: #eaf6ef;
                    cursor: pointer;
                    transition: .15s ease;
                }
                .swap-excel-occupied.disabled-swap {
    background: #f2f4f7;
    color: #98a2b3;
    cursor: not-allowed;
    opacity: .65;
}

                .swap-excel-occupied:hover {
                    background: #d8f0df;
                }

             .swap-excel-occupied.selected {
    outline: 4px solid #176b3a !important;
    outline-offset: -4px !important;
    background: #bfe5c8 !important;
    box-shadow:
        inset 0 0 0 2px rgba(23,107,58,.20),
        0 0 0 2px rgba(23,107,58,.08);
    position: relative;
    z-index: 2;
}

                /*
                Target schedules that are NOT allowed because
                day/time does not match Step 1.
                */

                .swap-excel-occupied.disabled-swap {
                    background: #f2f4f7;
                    color: #98a2b3;
                    cursor: not-allowed;
                    opacity: .65;
                }

                .swap-excel-occupied.disabled-swap:hover {
                    background: #f2f4f7;
                }

                .swap-excel-course {
                    font-size: 12px;
                    font-weight: 900;
                    color: #176b3a;
                }

                .swap-excel-subject {
                    font-size: 10px;
                    font-weight: 700;
                    color: #344054;
                    margin-top: 3px;
                }

                .swap-excel-instructor {
                    font-size: 9px;
                    color: #667085;
                    margin-top: 3px;
                }

                .swap-excel-program {
                    font-size: 9px;
                    font-weight: 800;
                    color: #176b3a;
                    margin-top: 3px;
                }

                .swap-noon-break td {
                    height: 32px !important;
                    background: #f8faf9;
                    color: #667085;
                    font-size: 10px;
                    font-weight: 900;
                    letter-spacing: 2px;
                }

                .swap-schedule-instruction {
                    margin-top: 12px;
                    padding: 12px;
                    border-radius: 8px;
                    background: #f8faf9;
                    color: #667085;
                    font-size: 12px;
                }

                .selected-target-schedule {
                    margin-top: 15px;
                    padding: 15px;
                    border-radius: 9px;
                    background: #eaf6ef;
                    border: 1px solid #b7dfbd;
                    color: #176b3a;
                    display: none;
                }

                /* =====================================================
                   REASON
                ===================================================== */

                .reason-options {
                    display: grid;
                    grid-template-columns:
                        repeat(auto-fit, minmax(220px, 1fr));
                    gap: 10px;
                    margin-bottom: 12px;
                }

                .reason-option {
                    border: 1px solid #d0d5d9;
                    background: white;
                    border-radius: 8px;
                    padding: 12px;
                    cursor: pointer;
                    text-align: center;
                    font-size: 13px;
                    font-weight: 700;
                }

                .reason-option:hover {
                    border-color: #176b3a;
                }

                .reason-option.selected {
                    border: 2px solid #176b3a;
                    background: #eaf6ef;
                    color: #176b3a;
                }

                #otherReason {
                    width: 100%;
                    min-height: 100px;
                    padding: 12px;
                    border: 1px solid #d0d5d9;
                    border-radius: 8px;
                    resize: vertical;
                }

                /* =====================================================
                   BUTTONS
                ===================================================== */

                .buttons {
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                    margin-top: 20px;
                }

                .btn {
                    border: none;
                    border-radius: 8px;
                    padding: 11px 20px;
                    cursor: pointer;
                    font-weight: 800;
                }

                .btn-reset {
                    background: #eaecf0;
                    color: #344054;
                }

                .btn-send {
                    background: #176b3a;
                    color: white;
                }

                .btn-send:hover {
                    background: #12572f;
                }

                .empty-swap-message {
                    padding: 30px;
                    text-align: center;
                    color: #667085;
                    background: #f8faf9;
                    border-radius: 10px;
                }

                .empty-swap-message strong {
                    display: block;
                    margin: 8px 0 3px;
                    color: #344054;
                }



                /* =====================================================
                   STEP 1 — MY ROOM SCHEDULE CARDS
                ===================================================== */

                .my-room-schedule-list {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                    gap: 12px;
                }

                .my-room-schedule-card {
                    border: 1px solid #dfe4e8;
                    border-radius: 10px;
                    background: #ffffff;
                    padding: 15px;
                    cursor: pointer;
                    transition: .2s ease;
                }

                .my-room-schedule-card:hover {
                    border-color: #176b3a;
                    background: #f7fcf8;
                    box-shadow: 0 4px 12px rgba(23,107,58,.10);
                    transform: translateY(-1px);
                }

                .my-room-schedule-card.selected {
                    border: 2px solid #176b3a;
                    background: #eaf6ef;
                    box-shadow: 0 4px 14px rgba(23,107,58,.15);
                }

                .my-room-schedule-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 10px;
                    margin-bottom: 8px;
                }

                .my-room-schedule-course {
                    color: #176b3a;
                    font-size: 15px;
                    font-weight: 900;
                }

                .my-room-schedule-day {
                    background: #eaf6ef;
                    color: #176b3a;
                    border-radius: 20px;
                    padding: 5px 9px;
                    font-size: 10px;
                    font-weight: 800;
                    white-space: nowrap;
                }

                .my-room-schedule-subject {
                    color: #344054;
                    font-size: 12px;
                    font-weight: 700;
                    margin-bottom: 10px;
                }

                .my-room-schedule-details {
                    display: grid;
                    gap: 6px;
                }

                .my-room-schedule-details div {
                    color: #667085;
                    font-size: 11px;
                }

                .my-room-schedule-details strong {
                    color: #344054;
                }

                .my-room-schedule-details i {
                    width: 16px;
                    color: #176b3a;
                }

                .my-room-selected-note {
                    margin-top: 12px;
                    padding: 10px 12px;
                    background: #eaf6ef;
                    border: 1px solid #b7dfbd;
                    border-radius: 8px;
                    color: #176b3a;
                    font-size: 11px;
                    font-weight: 700;
                }

            </style>


            <div class="swap-container">

                <div class="swap-title">
                    Room Swap Request
                </div>

                <div class="swap-subtitle">
                    Exchange your scheduled room with another faculty member.
                    Both schedules must have the same day and time.
                </div>


                {{-- SUCCESS --}}
                @if(session('success'))

                    <div class="alert-success">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ERROR --}}
                @if(session('error'))

                    <div class="alert-error">
                        {{ session('error') }}
                    </div>

                @endif


                {{-- VALIDATION ERRORS --}}
                @if($errors->any())

                    <div class="alert-error">

                        @foreach($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                @endif


                <form
                    action="{{ route('faculty.room.swap.store') }}"
                    method="POST"
                    id="swapForm"
                >

                    @csrf


                    {{-- =================================================
                         STEP 1
                    ================================================== --}}

                    <div class="step">

                        <div class="step-title">
                            Step 1 — Select Your Schedule
                        </div>

                        <div class="step-description">
                            Select your building, room, and then one of your scheduled classes.
                        </div>

                        <div class="swap-search-box">
                            <div class="swap-search-label">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search My Buildings or Rooms
                            </div>

                            <div class="swap-search-row">
                                <input
                                    type="text"
                                    id="myScheduleSearch"
                                    class="swap-search-input"
                                    placeholder="Search building, room, course code, or subject..."
                                    oninput="filterMySwapRooms()"
                                >

                                <button
                                    type="button"
                                    class="swap-search-clear"
                                    onclick="clearMyScheduleSearch()"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <div class="swap-search-help">
                                First select a building, then a room. Only your scheduled classes in that room will be shown.
                            </div>
                        </div>

                        {{-- MY BUILDINGS --}}
                        <div id="mySwapBuildings">
                            <div class="swap-navigation-title">
                                <i class="fa-solid fa-building"></i>
                                My Buildings
                            </div>

                            <div
                                id="mySwapBuildingList"
                                class="swap-navigation-list"
                            ></div>
                        </div>

                        {{-- MY ROOMS --}}
                        <div
                            id="mySwapRooms"
                            style="display:none;"
                        >
                            <div class="swap-breadcrumb">
                                <button
                                    type="button"
                                    onclick="backToMySwapBuildings()"
                                >
                                    <i class="fa-solid fa-arrow-left"></i>
                                    My Buildings
                                </button>
                                <span>/</span>
                                <strong id="mySwapBuildingName">
                                    Building
                                </strong>
                            </div>

                            <div class="swap-navigation-title">
                                <i class="fa-solid fa-door-open"></i>
                                My Rooms
                            </div>

                            <div
                                id="mySwapRoomList"
                                class="swap-room-grid"
                            ></div>
                        </div>

                        {{-- MY ROOM SCHEDULES --}}
                        <div
                            id="mySwapRoomSchedule"
                            style="display:none;"
                        >
                            <div class="swap-breadcrumb">
                                <button
                                    type="button"
                                    onclick="backToMySwapRooms()"
                                >
                                    <i class="fa-solid fa-arrow-left"></i>
                                    My Rooms
                                </button>
                                <span>/</span>
                                <strong id="mySwapRoomName">
                                    Room
                                </strong>
                            </div>

                            <div
                                id="mySwapRoomInformation"
                                class="swap-room-information"
                            ></div>

                            <div class="swap-navigation-title">
                                <i class="fa-solid fa-calendar-days"></i>
                                My Scheduled Classes in This Room
                            </div>

                            <div
                                id="mySwapScheduleList"
                                class="my-room-schedule-list"
                            ></div>
                        </div>

                        <div
                            id="selectedRequesterSchedule"
                            class="selected-swap-box"
                        ></div>

                    </div>


                    {{-- =================================================
                         STEP 2
                    ================================================== --}}

                    <div class="step">

                        <div class="step-title">
                            Step 2 — Select Another Faculty Schedule
                        </div>

                        <div class="step-description">
                            You can access all departments. Select a department,
                            building, room, and then a schedule.
                            Only schedules with the same day and time as Step 1
                            can be selected.
                        </div>


                        {{-- =================================================
                             SEARCH ROOMS
                        ================================================== --}}

                        <div class="swap-search-box">

                            <div class="swap-search-label">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search Rooms
                            </div>

                            <div class="swap-search-row">

                                <input
                                    type="text"
                                    id="swapRoomSearch"
                                    class="swap-search-input"
                                    placeholder="Search room, building, or department..."
                                    oninput="filterSwapRoomSearch()"
                                >

                                <button
                                    type="button"
                                    class="swap-search-clear"
                                    onclick="clearSwapRoomSearch()"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>

                            </div>

                            <div class="swap-search-help">
                                Search directly or manually browse Department → Building → Room.
                            </div>

                        </div>

                        <div
                            id="swapRoomSearchResults"
                            class="swap-room-search-results"
                            style="display:none;"
                        ></div>

                        <div
                            id="swapSelectedRoomNote"
                            class="swap-selected-room-note"
                            style="display:none;"
                        ></div>


                        {{-- =============================================
                             DEPARTMENTS
                        ============================================== --}}

                        <div id="swapDepartments">

                            <div class="swap-navigation-title">

                                <i class="fa-solid fa-building-columns"></i>

                                All Departments

                            </div>


                            <div class="swap-navigation-list">

                                @forelse($departments as $department)

                                    {{-- IMPORTANT:
                                         NO department filter here.
                                         This includes the user's own department.
                                    --}}

                                    <button
                                        type="button"
                                        class="swap-navigation-card"
                                        onclick="openSwapDepartment(
                                            {{ $department->id }}
                                        )"
                                    >

                                        <div class="swap-navigation-left">

                                            <div class="swap-navigation-icon">

                                                <i class="fa-solid fa-building-columns"></i>

                                            </div>

                                            <div>

                                                <div class="swap-navigation-name">

                                                    {{ $department->name }}

                                                </div>

                                                <div class="swap-navigation-description">

                                                    {{ $department->buildings->count() }}

                                                    {{ $department->buildings->count() == 1
                                                        ? 'building'
                                                        : 'buildings'
                                                    }}

                                                </div>

                                            </div>

                                        </div>


                                        <i class="
                                            fa-solid
                                            fa-chevron-right
                                            swap-navigation-arrow
                                        "></i>

                                    </button>

                                @empty

                                    <div class="empty-swap-message">

                                        <i class="fa-solid fa-building-circle-xmark"></i>

                                        <strong>
                                            No departments found
                                        </strong>

                                    </div>

                                @endforelse

                            </div>

                        </div>


                        {{-- =============================================
                             BUILDINGS
                        ============================================== --}}

                        <div
                            id="swapBuildings"
                            style="display:none;"
                        >

                            <div class="swap-breadcrumb">

                                <button
                                    type="button"
                                    onclick="backToSwapDepartments()"
                                >
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Departments
                                </button>

                                <span>/</span>

                                <strong id="swapDepartmentName">
                                    Department
                                </strong>

                            </div>


                            <div class="swap-navigation-title">

                                <i class="fa-solid fa-building"></i>

                                Buildings

                            </div>


                            <div
                                id="swapBuildingList"
                                class="swap-navigation-list"
                            ></div>

                        </div>


                        {{-- =============================================
                             ROOMS
                        ============================================== --}}

                        <div
                            id="swapRooms"
                            style="display:none;"
                        >

                            <div class="swap-breadcrumb">

                                <button
                                    type="button"
                                    onclick="backToSwapBuildings()"
                                >
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Buildings
                                </button>

                                <span>/</span>

                                <strong id="swapBuildingName">
                                    Building
                                </strong>

                            </div>


                            <div class="swap-navigation-title">

                                <i class="fa-solid fa-door-open"></i>

                                Rooms

                            </div>


                            <div
                                id="swapRoomList"
                                class="swap-room-grid"
                            ></div>

                        </div>


                        {{-- =============================================
                             ROOM SCHEDULE
                        ============================================== --}}

                        <div
                            id="swapRoomSchedule"
                            style="display:none;"
                        >

                            <div class="swap-breadcrumb">

                                <button
                                    type="button"
                                    onclick="backToSwapRooms()"
                                >
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Rooms
                                </button>

                                <span>/</span>

                                <strong id="swapRoomName">
                                    Room
                                </strong>

                            </div>


                            <div
                                id="swapRoomInformation"
                                class="swap-room-information"
                            ></div>


                            {{-- SAME DAY/TIME INDICATOR --}}

                            <div
                                id="swapScheduleFilter"
                                class="swap-schedule-filter"
                                style="display:none;"
                            >

                                <strong>
                                    Allowed schedule:
                                </strong>

                                <span id="swapAllowedDay"></span>

                                <strong style="margin-left:10px;">
                                    •
                                </strong>

                                <span id="swapAllowedTime"></span>

                            </div>


                            <div class="swap-navigation-title">

                                <i class="fa-solid fa-calendar-days"></i>

                                Room Schedule

                            </div>


                            <div class="swap-excel-wrapper">

                                <table
                                    class="swap-excel-table"
                                    id="swapExcelTable"
                                >

                                    <thead>

                                        <tr>

                                            <th>TIME</th>

                                            <th>MON</th>

                                            <th>WED</th>

                                            <th>FRI</th>

                                            <th>TIME</th>

                                            <th>TUE</th>

                                            <th>THU</th>

                                        </tr>

                                    </thead>


                                    <tbody id="swapExcelBody"></tbody>

                                </table>

                            </div>


                            <div
                                id="swapScheduleInstruction"
                                class="swap-schedule-instruction"
                            >

                                <i class="fa-solid fa-circle-info"></i>

                                Select an occupied schedule that has the
                                <strong>same day and same time</strong>
                                as your Step 1 schedule.

                            </div>


                            <div
                                id="selectedTargetSchedule"
                                class="selected-target-schedule"
                            ></div>

                        </div>

                    </div>


                    {{-- =================================================
                         STEP 3
                    ================================================== --}}

                    <div class="step">

                        <div class="step-title">
                            Step 3 — Reason for Room Swap
                        </div>

                        <div class="step-description">
                            Select a reason for your room swap request.
                        </div>


                        <div class="reason-options">

                            <div
                                class="reason-option"
                                onclick="selectReason(
                                    'Need computers',
                                    this
                                )"
                            >
                                Need computers
                            </div>


                            <div
                                class="reason-option"
                                onclick="selectReason(
                                    'Need projector/TV',
                                    this
                                )"
                            >
                                Need projector/TV
                            </div>


                            <div
                                class="reason-option"
                                onclick="selectReason(
                                    'Need 40+ room capacity',
                                    this
                                )"
                            >
                                Need 40+ room capacity
                            </div>


                            <div
                                class="reason-option"
                                onclick="selectReason(
                                    'Other',
                                    this
                                )"
                            >
                                Other
                            </div>

                        </div>


                        <textarea
                            id="otherReason"
                            placeholder="Enter other reason..."
                        ></textarea>


                        {{-- =================================================
                             HIDDEN VALUES
                        ================================================== --}}

                        <input
                            type="hidden"
                            name="requester_type"
                            value="schedule"
                        >

                        <input
                            type="hidden"
                            name="target_type"
                            value="schedule"
                        >

                        <input
                            type="hidden"
                            name="requester_schedule_id"
                            id="requester_schedule_id"
                        >

                        <input
                            type="hidden"
                            name="requester_room_id"
                            id="requester_room_id"
                        >

                        <input
                            type="hidden"
                            name="target_schedule_id"
                            id="target_schedule_id"
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


                        {{-- BUTTONS --}}

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
                                <i class="fa-solid fa-paper-plane"></i>

                                Send Room Swap Request

                            </button>

                        </div>

                    </div>

                </form>

            </div>


            <script>

                /*
                |--------------------------------------------------------------------------
                | DATA
                |--------------------------------------------------------------------------
                */

                const swapDepartmentData =
                    @json($departments);


                /*
                |--------------------------------------------------------------------------
                | PREPARE MERGED PROGRAM DATA
                |--------------------------------------------------------------------------
                |
                | Every approved merge attached to a schedule is converted to
                | merged_programs so Step 2 can display:
                |
                | BSIT / EDUC
                |
                | without changing the original schedule program.
                |
                */

                swapDepartmentData.forEach(
                    function(department)
                    {
                        (
                            department.buildings ||
                            []
                        ).forEach(
                            function(building)
                            {
                                (
                                    building.rooms ||
                                    []
                                ).forEach(
                                    function(room)
                                    {
                                        (
                                            room.schedules ||
                                            []
                                        ).forEach(
                                            function(schedule)
                                            {
                                                const programs = [];

                                                if (
                                                    schedule.program
                                                ) {
                                                    programs.push(
                                                        String(
                                                            schedule.program
                                                        ).trim()
                                                    );
                                                }

                                                (
                                                    schedule.merges ||
                                                    []
                                                ).forEach(
                                                    function(merge)
                                                    {
                                                        if (
                                                            String(
                                                                merge.status ||
                                                                ''
                                                            ).toLowerCase() !==
                                                            'approved'
                                                        ) {
                                                            return;
                                                        }

                                                        const mergeProgram =
                                                            merge.faculty_subject
                                                                ?.program ||
                                                            merge.facultySubject
                                                                ?.program ||
                                                            '';

                                                        if (
                                                            String(
                                                                mergeProgram
                                                            ).trim() &&
                                                            !programs.includes(
                                                                String(
                                                                    mergeProgram
                                                                ).trim()
                                                            )
                                                        ) {
                                                            programs.push(
                                                                String(
                                                                    mergeProgram
                                                                ).trim()
                                                            );
                                                        }
                                                    }
                                                );

                                                schedule.merged_programs =
                                                    programs
                                                        .filter(Boolean)
                                                        .filter(
                                                            function(value, index, array)
                                                            {
                                                                return array.indexOf(value) === index;
                                                            }
                                                        );
                                            }
                                        );
                                    }
                                );
                            }
                        );
                    }
                );


                let selectedRequesterSchedule =
                    null;

                let selectedSwapDepartment =
                    null;

                let selectedSwapBuilding =
                    null;

                let selectedSwapRoom =
                    null;

                let selectedTargetSchedule =
                    null;


                /*
                |--------------------------------------------------------------------------
                | ESCAPE HTML
                |--------------------------------------------------------------------------
                */

                function escapeSwapHtml(value)
                {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }


                /*
                |--------------------------------------------------------------------------
                | STEP 1 DATA
                |--------------------------------------------------------------------------
                */

                const mySwapScheduleData =
                    @json($mySchedules);

                let selectedMySwapBuilding = null;
                let selectedMySwapRoom = null;


                /*
                |--------------------------------------------------------------------------
                | BUILD MY BUILDINGS
                |--------------------------------------------------------------------------
                |
                | Only buildings that contain schedules belonging to the
                | currently logged-in faculty are shown.
                */

                function getMySwapBuildings()
                {
                    const buildingMap = {};

                    mySwapScheduleData.forEach(
                        function(schedule)
                        {
                            if (!schedule.room) {
                                return;
                            }

                            const building =
                                schedule.room.building || {};

                            const buildingId =
                                building.id ||
                                schedule.room.building_id;

                            if (!buildingId) {
                                return;
                            }

                            if (!buildingMap[buildingId]) {
                                buildingMap[buildingId] = {
                                    id: Number(buildingId),
                                    name: building.name || 'Building',
                                    rooms: {}
                                };
                            }

                            const roomId =
                                schedule.room.id ||
                                schedule.room_id;

                            if (!roomId) {
                                return;
                            }

                            if (!buildingMap[buildingId].rooms[roomId]) {
                                buildingMap[buildingId].rooms[roomId] = {
                                    id: Number(roomId),
                                    room_name:
                                        schedule.room.room_name || 'Room',
                                    department:
                                        schedule.room.department || {},
                                    schedules: []
                                };
                            }

                            buildingMap[buildingId]
                                .rooms[roomId]
                                .schedules
                                .push(schedule);
                        }
                    );

                    return Object.values(buildingMap).map(
                        function(building)
                        {
                            building.rooms =
                                Object.values(building.rooms);

                            return building;
                        }
                    );
                }


                function renderMySwapBuildings()
                {
                    const list =
                        document.getElementById(
                            'mySwapBuildingList'
                        );

                    if (!list) {
                        return;
                    }

                    list.innerHTML = '';

                    const buildings =
                        getMySwapBuildings();

                    if (!buildings.length) {
                        list.innerHTML = `
                            <div class="empty-swap-message">
                                <i class="fa-solid fa-building-circle-xmark"></i>
                                <strong>No scheduled buildings found</strong>
                                <span>
                                    You currently have no scheduled classes assigned to a room.
                                </span>
                            </div>
                        `;
                        return;
                    }

                    buildings.forEach(
                        function(building)
                        {
                            const card =
                                document.createElement('button');

                            card.type = 'button';
                            card.className =
                                'swap-navigation-card my-swap-building-card';

                            card.dataset.search =
                                (
                                    String(building.name || '') +
                                    ' ' +
                                    building.rooms.map(
                                        function(room) {
                                            return room.room_name;
                                        }
                                    ).join(' ')
                                ).toLowerCase();

                            card.onclick =
                                function()
                                {
                                    openMySwapBuilding(
                                        building.id
                                    );
                                };

                            card.innerHTML = `
                                <div class="swap-navigation-left">
                                    <div class="swap-navigation-icon">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <div>
                                        <div class="swap-navigation-name">
                                            ${escapeSwapHtml(building.name)}
                                        </div>
                                        <div class="swap-navigation-description">
                                            ${building.rooms.length}
                                            ${building.rooms.length === 1 ? 'room' : 'rooms'}
                                            with your schedule
                                        </div>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right swap-navigation-arrow"></i>
                            `;

                            list.appendChild(card);
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | OPEN MY BUILDING
                |--------------------------------------------------------------------------
                */

                function openMySwapBuilding(buildingId)
                {
                    const buildings =
                        getMySwapBuildings();

                    selectedMySwapBuilding =
                        buildings.find(
                            function(building)
                            {
                                return Number(building.id) ===
                                    Number(buildingId);
                            }
                        );

                    if (!selectedMySwapBuilding) {
                        return;
                    }

                    document.getElementById(
                        'mySwapBuildingName'
                    ).textContent =
                        selectedMySwapBuilding.name;

                    const roomList =
                        document.getElementById(
                            'mySwapRoomList'
                        );

                    roomList.innerHTML = '';

                    const rooms =
                        selectedMySwapBuilding.rooms || [];

                    if (!rooms.length) {
                        roomList.innerHTML = `
                            <div class="empty-swap-message">
                                <i class="fa-solid fa-door-closed"></i>
                                <strong>No scheduled rooms found</strong>
                                <span>
                                    You have no scheduled classes in this building.
                                </span>
                            </div>
                        `;
                    } else {
                        rooms.forEach(
                            function(room)
                            {
                                const card =
                                    document.createElement('div');

                                card.className =
                                    'swap-room-card my-swap-room-card';

                                card.innerHTML = `
                                    <div class="swap-room-header">
                                        <div class="swap-room-icon">
                                            <i class="fa-solid fa-door-open"></i>
                                        </div>
                                        <div>
                                            <div class="swap-room-name">
                                                ${escapeSwapHtml(room.room_name)}
                                            </div>
                                            <div class="swap-room-building">
                                                ${escapeSwapHtml(selectedMySwapBuilding.name)}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="swap-room-information">
                                        ${buildRoomInfo(room)}
                                    </div>

                                    <div class="my-room-selected-note">
                                        <i class="fa-solid fa-calendar-check"></i>
                                        ${room.schedules.length}
                                        ${room.schedules.length === 1 ? 'scheduled class' : 'scheduled classes'}
                                    </div>

                                    <button
                                        type="button"
                                        class="swap-view-room-button"
                                        onclick="openMySwapRoom(${room.id})"
                                    >
                                        <i class="fa-solid fa-calendar-days"></i>
                                        View My Classes
                                    </button>
                                `;

                                roomList.appendChild(card);
                            }
                        );
                    }

                    document.getElementById(
                        'mySwapBuildings'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRooms'
                    ).style.display = 'block';

                    document.getElementById(
                        'mySwapRoomSchedule'
                    ).style.display = 'none';
                }


                /*
                |--------------------------------------------------------------------------
                | OPEN MY ROOM
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | This does NOT build an Excel timetable.
                | It displays only the schedules belonging to the
                | logged-in faculty in this selected room.
                */

                function openMySwapRoom(roomId)
                {
                    if (!selectedMySwapBuilding) {
                        return;
                    }

                    selectedMySwapRoom =
                        selectedMySwapBuilding.rooms.find(
                            function(room)
                            {
                                return Number(room.id) ===
                                    Number(roomId);
                            }
                        );

                    if (!selectedMySwapRoom) {
                        return;
                    }

                    document.getElementById(
                        'mySwapRoomName'
                    ).textContent =
                        selectedMySwapRoom.room_name;

                    document.getElementById(
                        'mySwapRoomInformation'
                    ).innerHTML =
                        buildRoomInfo(selectedMySwapRoom);

                    renderMyRoomSchedules(
                        selectedMySwapRoom.schedules || []
                    );

                    document.getElementById(
                        'mySwapBuildings'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRooms'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRoomSchedule'
                    ).style.display = 'block';
                }


                /*
                |--------------------------------------------------------------------------
                | RENDER ONLY FACULTY SCHEDULES
                |--------------------------------------------------------------------------
                */

                function renderMyRoomSchedules(schedules)
                {
                    const list =
                        document.getElementById(
                            'mySwapScheduleList'
                        );

                    list.innerHTML = '';

                    if (!schedules.length) {
                        list.innerHTML = `
                            <div class="empty-swap-message">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <strong>No scheduled classes</strong>
                                <span>
                                    You do not have a scheduled class in this room.
                                </span>
                            </div>
                        `;
                        return;
                    }

                    schedules
                        .slice()
                        .sort(
                            function(a, b)
                            {
                                return Number(a.row_position ?? 9999) -
                                    Number(b.row_position ?? 9999);
                            }
                        )
                        .forEach(
                            function(schedule)
                            {
                                const card =
                                    document.createElement('div');

                                card.className =
                                    'my-room-schedule-card';

                                card.dataset.scheduleId =
                                    schedule.id;

                                card.onclick =
                                    function()
                                    {
                                        selectMyRoomSchedule(
                                            schedule.id,
                                            card
                                        );
                                    };

                                const programs = [];

                                if (schedule.program) {
                                    programs.push(
                                        String(schedule.program).trim()
                                    );
                                }

                                (schedule.merged_programs || [])
                                    .forEach(
                                        function(program)
                                        {
                                            const value =
                                                String(program || '').trim();

                                            if (
                                                value &&
                                                !programs.includes(value)
                                            ) {
                                                programs.push(value);
                                            }
                                        }
                                    );

                                card.innerHTML = `
                                    <div class="my-room-schedule-top">
                                        <div class="my-room-schedule-course">
                                            ${escapeSwapHtml(schedule.course_code || '-')}
                                        </div>
                                        <div class="my-room-schedule-day">
                                            ${escapeSwapHtml(schedule.day || '')}
                                            •
                                            ${escapeSwapHtml(schedule.time || '')}
                                        </div>
                                    </div>

                                    <div class="my-room-schedule-subject">
                                        ${escapeSwapHtml(schedule.subject || '-')}
                                    </div>

                                    <div class="my-room-schedule-details">
                                        <div>
                                            <i class="fa-solid fa-user"></i>
                                            <strong>Faculty:</strong>
                                            ${escapeSwapHtml(schedule.instructor || 'Unknown Faculty')}
                                        </div>

                                        ${
                                            programs.length
                                                ? `
                                                    <div>
                                                        <i class="fa-solid fa-graduation-cap"></i>
                                                        <strong>Program:</strong>
                                                        ${escapeSwapHtml(programs.join(' / '))}
                                                    </div>
                                                `
                                                : ''
                                        }

                                        ${
                                            schedule.year_level
                                                ? `
                                                    <div>
                                                        <i class="fa-solid fa-layer-group"></i>
                                                        <strong>Year:</strong>
                                                        ${escapeSwapHtml(schedule.year_level)}
                                                    </div>
                                                `
                                                : ''
                                        }

                                        ${
                                            schedule.major
                                                ? `
                                                    <div>
                                                        <i class="fa-solid fa-bookmark"></i>
                                                        <strong>Major:</strong>
                                                        ${escapeSwapHtml(schedule.major)}
                                                    </div>
                                                `
                                                : ''
                                        }
                                    </div>
                                `;

                                list.appendChild(card);
                            }
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | SELECT MY ROOM SCHEDULE
                |--------------------------------------------------------------------------
                */

                function selectMyRoomSchedule(
                    scheduleId,
                    element
                )
                {
                    const schedule =
                        (selectedMySwapRoom?.schedules || [])
                            .find(
                                function(item)
                                {
                                    return Number(item.id) ===
                                        Number(scheduleId);
                                }
                            );

                    if (!schedule) {
                        return;
                    }

                    document
                        .querySelectorAll(
                            '.my-room-schedule-card'
                        )
                        .forEach(
                            function(card)
                            {
                                card.classList.remove('selected');
                            }
                        );

                    element.classList.add('selected');

                    const room =
                        selectedMySwapRoom;

                    const buildingName =
                        selectedMySwapBuilding?.name ||
                        'Building';

                    selectedRequesterSchedule = {
                        id: Number(schedule.id),
                        room_id: Number(schedule.room_id),
                        day: String(schedule.day || '')
                            .trim()
                            .toUpperCase(),
                        time: String(schedule.time || '')
                            .trim(),
                        course_code: schedule.course_code || '',
                        subject: schedule.subject || '',
                        room: room?.room_name || 'Room',
                        building: buildingName,
                        department:
                            room?.department?.name ||
                            'Department'
                    };

                    document.getElementById(
                        'requester_schedule_id'
                    ).value = schedule.id;

                    document.getElementById(
                        'requester_room_id'
                    ).value = schedule.room_id;

                    document.getElementById(
                        'swap_date'
                    ).value =
                        new Date()
                            .toISOString()
                            .split('T')[0];

                    const timeParts =
                        String(schedule.time || '').split('-');

                    if (timeParts.length === 2) {
                        document.getElementById(
                            'start_time'
                        ).value = timeParts[0].trim();

                        document.getElementById(
                            'end_time'
                        ).value = timeParts[1].trim();
                    }

                    const selectedBox =
                        document.getElementById(
                            'selectedRequesterSchedule'
                        );

                    selectedBox.innerHTML = `
                        <strong>
                            <i class="fa-solid fa-circle-check"></i>
                            Your Schedule Selected
                        </strong>

                        <div style="margin-top:6px;">
                            ${escapeSwapHtml(schedule.course_code || '-')}
                            —
                            ${escapeSwapHtml(schedule.subject || '-')}
                        </div>

                        <div style="margin-top:4px;">
                            Department:
                            ${escapeSwapHtml(room?.department?.name || 'Department')}
                        </div>

                        <div style="margin-top:4px;">
                            Building:
                            ${escapeSwapHtml(buildingName)}
                        </div>

                        <div style="margin-top:4px;">
                            Room:
                            ${escapeSwapHtml(room?.room_name || 'Room')}
                        </div>

                        <div style="margin-top:4px;">
                            Schedule:
                            ${escapeSwapHtml(schedule.day || '')}
                            •
                            ${escapeSwapHtml(schedule.time || '')}
                        </div>
                    `;

                    selectedBox.style.display = 'block';

                    selectedTargetSchedule = null;

                    document.getElementById(
                        'target_schedule_id'
                    ).value = '';

                    document.getElementById(
                        'target_room_id'
                    ).value = '';

                    document.getElementById(
                        'target_user_id'
                    ).value = '';

                    document.getElementById(
                        'selectedTargetSchedule'
                    ).style.display = 'none';
                }


                /*
                |--------------------------------------------------------------------------
                | SEARCH MY BUILDINGS / ROOMS
                |--------------------------------------------------------------------------
                */

                function filterMySwapRooms()
                {
                    const input =
                        document.getElementById(
                            'myScheduleSearch'
                        );

                    const search =
                        String(input?.value || '')
                            .trim()
                            .toLowerCase();

                    document
                        .querySelectorAll(
                            '.my-swap-building-card'
                        )
                        .forEach(
                            function(card)
                            {
                                const text =
                                    String(card.dataset.search || '')
                                        .toLowerCase();

                                card.style.display =
                                    !search || text.includes(search)
                                        ? ''
                                        : 'none';
                            }
                        );

                    /* If a room is currently open, filter the schedule cards too. */
                    if (selectedMySwapRoom) {
                        const schedules =
                            selectedMySwapRoom.schedules || [];

                        document
                            .querySelectorAll(
                                '.my-room-schedule-card'
                            )
                            .forEach(
                                function(card)
                                {
                                    const schedule =
                                        schedules.find(
                                            function(item)
                                            {
                                                return Number(item.id) ===
                                                    Number(card.dataset.scheduleId);
                                            }
                                        );

                                    if (!schedule) {
                                        card.style.display = 'none';
                                        return;
                                    }

                                    const text = (
                                        String(schedule.course_code || '') + ' ' +
                                        String(schedule.subject || '') + ' ' +
                                        String(schedule.day || '') + ' ' +
                                        String(schedule.time || '')
                                    ).toLowerCase();

                                    card.style.display =
                                        !search || text.includes(search)
                                            ? ''
                                            : 'none';
                                }
                            );
                    }
                }


                function clearMyScheduleSearch()
                {
                    const input =
                        document.getElementById(
                            'myScheduleSearch'
                        );

                    if (!input) {
                        return;
                    }

                    input.value = '';
                    filterMySwapRooms();
                }


                function backToMySwapBuildings()
                {
                    selectedMySwapBuilding = null;
                    selectedMySwapRoom = null;

                    document.getElementById(
                        'mySwapBuildings'
                    ).style.display = 'block';

                    document.getElementById(
                        'mySwapRooms'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRoomSchedule'
                    ).style.display = 'none';
                }


                function backToMySwapRooms()
                {
                    selectedMySwapRoom = null;

                    document.getElementById(
                        'mySwapBuildings'
                    ).style.display = 'none';

                    document.getElementById(
                        'mySwapRooms'
                    ).style.display = 'block';

                    document.getElementById(
                        'mySwapRoomSchedule'
                    ).style.display = 'none';
                }


                /* Show My Buildings immediately when Step 1 loads. */
                renderMySwapBuildings();


                /*
                |--------------------------------------------------------------------------
                | STEP 2 ROOM SEARCH
                |--------------------------------------------------------------------------
                */

                function filterSwapRoomSearch()
                {
                    const input =
                        document.getElementById(
                            'swapRoomSearch'
                        );

                    const results =
                        document.getElementById(
                            'swapRoomSearchResults'
                        );

                    if (!input || !results) {
                        return;
                    }

                    const search =
                        input.value
                            .trim()
                            .toLowerCase();

                    if (!search) {
                        results.innerHTML = '';
                        results.style.display = 'none';
                        return;
                    }

                    const rooms = [];

                    swapDepartmentData.forEach(
                        function(department)
                        {
                            (
                                department.buildings ||
                                []
                            ).forEach(
                                function(building)
                                {
                                    (
                                        building.rooms ||
                                        []
                                    ).forEach(
                                        function(room)
                                        {
                                            if (
                                                selectedRequesterSchedule &&
                                                Number(room.id) ===
                                                Number(
                                                    selectedRequesterSchedule.room_id
                                                )
                                            ) {
                                                return;
                                            }

                                            const searchable =
                                                (
                                                    String(room.room_name || '') +
                                                    ' ' +
                                                    String(building.name || '') +
                                                    ' ' +
                                                    String(department.name || '')
                                                ).toLowerCase();

                                            if (
                                                searchable.includes(
                                                    search
                                                )
                                            ) {
                                                rooms.push({
                                                    room: room,
                                                    building: building,
                                                    department: department
                                                });
                                            }
                                        }
                                    );
                                }
                            );
                        }
                    );

                    results.innerHTML = '';

                    if (!rooms.length) {
                        results.innerHTML = `
                            <div class="empty-swap-message">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <strong>No rooms found</strong>
                                <span>
                                    Try another room, building, or department.
                                </span>
                            </div>
                        `;

                        results.style.display = 'block';
                        return;
                    }

                    rooms.forEach(
                        function(item)
                        {
                            const card =
                                document.createElement(
                                    'div'
                                );

                            card.className =
                                'swap-search-room-card';

                            card.innerHTML = `
                                <div class="swap-search-room-icon">
                                    <i class="fa-solid fa-door-open"></i>
                                </div>

                                <div class="swap-search-room-details">

                                    <strong>
                                        ${escapeSwapHtml(
                                            item.room.room_name
                                        )}
                                    </strong>

                                    <span>
                                        ${escapeSwapHtml(
                                            item.department.name
                                        )}
                                    </span>

                                    <span>
                                        ${escapeSwapHtml(
                                            item.building.name
                                        )}
                                    </span>

                                </div>

                                <button
                                    type="button"
                                    class="swap-search-room-button"
                                >
                                    View Schedule
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            `;

                            card
                                .querySelector(
                                    '.swap-search-room-button'
                                )
                                .onclick =
                                function()
                                {
                                    selectedSwapDepartment =
                                        item.department;

                                    selectedSwapBuilding =
                                        item.building;

                                    openSwapRoomFromSearch(
                                        item.room.id
                                    );
                                };

                            results.appendChild(
                                card
                            );
                        }
                    );

                    results.style.display =
                        'grid';
                }


                function clearSwapRoomSearch()
                {
                    const input =
                        document.getElementById(
                            'swapRoomSearch'
                        );

                    if (!input) {
                        return;
                    }

                    input.value = '';

                    filterSwapRoomSearch();
                }


                function openSwapRoomFromSearch(
                    roomId
                )
                {
                    if (
                        !selectedSwapBuilding
                    ) {
                        return;
                    }

                    selectedSwapRoom =
                        (
                            selectedSwapBuilding.rooms ||
                            []
                        ).find(
                            function(room)
                            {
                                return Number(room.id) ===
                                    Number(roomId);
                            }
                        );

                    if (!selectedSwapRoom) {
                        return;
                    }

                    if (
                        selectedRequesterSchedule &&
                        Number(selectedSwapRoom.id) ===
                        Number(
                            selectedRequesterSchedule.room_id
                        )
                    ) {
                        alert(
                            'You cannot select the same room you selected in Step 1.'
                        );
                        return;
                    }

                    openSelectedSwapRoom();
                }


                function openSelectedSwapRoom()
                {
                    if (!selectedSwapRoom) {
                        return;
                    }

                    document.getElementById(
                        'swapRoomName'
                    ).textContent =
                        selectedSwapRoom.room_name;

                    document.getElementById(
                        'swapRoomInformation'
                    ).innerHTML =
                        buildRoomInfo(
                            selectedSwapRoom
                        );

                    buildSwapExcelSchedule(
                        selectedSwapRoom
                    );

                    const searchResults =
                        document.getElementById(
                            'swapRoomSearchResults'
                        );

                    if (searchResults) {
                        searchResults.style.display = 'none';
                    }

                    document.getElementById(
                        'swapDepartments'
                    ).style.display = 'none';

                    document.getElementById(
                        'swapBuildings'
                    ).style.display = 'none';

                    document.getElementById(
                        'swapRooms'
                    ).style.display = 'none';

                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display = 'block';
                }


                /*
                |--------------------------------------------------------------------------
                | OPEN DEPARTMENT
                |--------------------------------------------------------------------------
                */

                function openSwapDepartment(
                    departmentId
                )
                {

                    selectedSwapDepartment =
                        swapDepartmentData.find(
                            function(department)
                            {
                                return Number(
                                    department.id
                                ) ===
                                Number(
                                    departmentId
                                );
                            }
                        );


                    if (
                        !selectedSwapDepartment
                    ) {
                        return;
                    }


                    document.getElementById(
                        'swapDepartmentName'
                    ).textContent =
                        selectedSwapDepartment.name;


                    const buildingList =
                        document.getElementById(
                            'swapBuildingList'
                        );


                    buildingList.innerHTML = '';


                    const buildings =
                        selectedSwapDepartment.buildings ||
                        [];


                    if (
                        buildings.length === 0
                    ) {

                        buildingList.innerHTML = `

                            <div class="empty-swap-message">

                                <i class="
                                    fa-solid
                                    fa-building-circle-xmark
                                "></i>

                                <strong>
                                    No buildings found
                                </strong>

                                <span>
                                    This department has no buildings assigned.
                                </span>

                            </div>

                        `;

                    } else {

                        buildings.forEach(
                            function(building)
                            {

                                const rooms =
                                    building.rooms ||
                                    [];


                                const card =
                                    document.createElement(
                                        'button'
                                    );


                                card.type =
                                    'button';


                                card.className =
                                    'swap-navigation-card';


                                card.onclick =
                                    function()
                                    {
                                        openSwapBuilding(
                                            building.id
                                        );
                                    };


                                card.innerHTML = `

                                    <div class="swap-navigation-left">

                                        <div class="swap-navigation-icon">

                                            <i class="
                                                fa-solid
                                                fa-building
                                            "></i>

                                        </div>

                                        <div>

                                            <div class="swap-navigation-name">

                                                ${escapeSwapHtml(
                                                    building.name
                                                )}

                                            </div>

                                            <div class="swap-navigation-description">

                                                ${rooms.length}

                                                ${
                                                    rooms.length === 1
                                                        ? 'room'
                                                        : 'rooms'
                                                }

                                            </div>

                                        </div>

                                    </div>


                                    <i class="
                                        fa-solid
                                        fa-chevron-right
                                        swap-navigation-arrow
                                    "></i>

                                `;


                                buildingList.appendChild(
                                    card
                                );

                            }
                        );

                    }


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | OPEN BUILDING
                |--------------------------------------------------------------------------
                */

                function openSwapBuilding(
                    buildingId
                )
                {

                    if (
                        !selectedSwapDepartment
                    ) {
                        return;
                    }


                    selectedSwapBuilding =
                        (
                            selectedSwapDepartment.buildings ||
                            []
                        ).find(
                            function(building)
                            {
                                return Number(
                                    building.id
                                ) ===
                                Number(
                                    buildingId
                                );
                            }
                        );


                    if (
                        !selectedSwapBuilding
                    ) {
                        return;
                    }


                    document.getElementById(
                        'swapBuildingName'
                    ).textContent =
                        selectedSwapBuilding.name;


                    const roomList =
                        document.getElementById(
                            'swapRoomList'
                        );


                    roomList.innerHTML = '';


                    const rooms =
                        (
                            selectedSwapBuilding.rooms ||
                            []
                        ).filter(
                            function(room)
                            {
                                if (
                                    !selectedRequesterSchedule
                                ) {
                                    return true;
                                }

                                return Number(room.id) !==
                                    Number(
                                        selectedRequesterSchedule.room_id
                                    );
                            }
                        );


                    if (
                        rooms.length === 0
                    ) {

                        roomList.innerHTML = `

                            <div class="empty-swap-message">

                                <i class="
                                    fa-solid
                                    fa-door-closed
                                "></i>

                                <strong>
                                    No rooms found
                                </strong>

                                <span>
                                    This building has no rooms assigned.
                                </span>

                            </div>

                        `;

                    } else {

                        rooms.forEach(
                            function(room)
                            {

                                const card =
                                    document.createElement(
                                        'div'
                                    );


                                card.className =
                                    'swap-room-card';


                                card.innerHTML = `

                                    <div class="swap-room-header">

                                        <div class="swap-room-icon">

                                            <i class="
                                                fa-solid
                                                fa-door-open
                                            "></i>

                                        </div>

                                        <div>

                                            <div class="swap-room-name">

                                                ${escapeSwapHtml(
                                                    room.room_name
                                                )}

                                            </div>

                                            <div class="swap-room-building">

                                                ${escapeSwapHtml(
                                                    selectedSwapBuilding.name
                                                )}

                                            </div>

                                        </div>

                                    </div>


                                    <div class="swap-room-information">

                                        ${buildRoomInfo(room)}

                                    </div>


                                    <button
                                        type="button"
                                        class="swap-view-room-button"
                                        onclick="
                                            openSwapRoom(
                                                ${room.id}
                                            )
                                        "
                                    >

                                        <i class="
                                            fa-solid
                                            fa-calendar-days
                                        "></i>

                                        View Room Schedule

                                    </button>

                                `;


                                roomList.appendChild(
                                    card
                                );

                            }
                        );

                    }


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | ROOM INFORMATION
                |--------------------------------------------------------------------------
                */

                function buildRoomInfo(
                    room
                )
                {

                    let html = '';


                    if (
                        room.capacity !== null &&
                        room.capacity !== undefined &&
                        room.capacity !== ''
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    Capacity
                                </div>

                                <div class="swap-room-info-value">

                                    ${escapeSwapHtml(
                                        room.capacity
                                    )}
                                    students

                                </div>

                            </div>

                        `;

                    }


                    if (
                        room.computers !== null &&
                        room.computers !== undefined &&
                        room.computers !== '' &&
                        Number(room.computers) > 0
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    Computers
                                </div>

                                <div class="swap-room-info-value">

                                    ${escapeSwapHtml(
                                        room.computers
                                    )}

                                </div>

                            </div>

                        `;

                    }


                    if (
                        room.has_tv
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    TV
                                </div>

                                <div class="
                                    swap-room-info-value
                                    available
                                ">
                                    ✓ Available
                                </div>

                            </div>

                        `;

                    }


                    if (
                        room.has_projector
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    Projector
                                </div>

                                <div class="
                                    swap-room-info-value
                                    available
                                ">
                                    ✓ Available
                                </div>

                            </div>

                        `;

                    }


                    if (
                        room.purpose
                    ) {

                        html += `

                            <div class="swap-room-info-item">

                                <div class="swap-room-info-label">
                                    Purpose
                                </div>

                                <div class="swap-room-info-value">

                                    ${escapeSwapHtml(
                                        room.purpose
                                    )}

                                </div>

                            </div>

                        `;

                    }


                    return html;

                }


                /*
                |--------------------------------------------------------------------------
                | OPEN ROOM
                |--------------------------------------------------------------------------
                */

                function openSwapRoom(
                    roomId
                )
                {

                    if (
                        !selectedSwapBuilding
                    ) {
                        return;
                    }


                    selectedSwapRoom =
                        (
                            selectedSwapBuilding.rooms ||
                            []
                        ).find(
                            function(room)
                            {
                                return Number(
                                    room.id
                                ) ===
                                Number(
                                    roomId
                                );
                            }
                        );


                    if (
                        !selectedSwapRoom
                    ) {
                        return;
                    }


                    if (
                        selectedRequesterSchedule &&
                        Number(selectedSwapRoom.id) ===
                        Number(
                            selectedRequesterSchedule.room_id
                        )
                    ) {
                        alert(
                            'You cannot select the same room you selected in Step 1.'
                        );
                        return;
                    }

                    openSelectedSwapRoom();

                }


                /*
                |--------------------------------------------------------------------------
                | NORMALIZE DAY
                |--------------------------------------------------------------------------
                */

               function normalizeSwapDay(day)
{
    const value =
        String(day || '')
            .trim()
            .toUpperCase();

    const days = {
        'MON': 'MON',
        'MONDAY': 'MON',

        'TUE': 'TUE',
        'TUES': 'TUE',
        'TUESDAY': 'TUE',

        'WED': 'WED',
        'WEDNESDAY': 'WED',

        'THU': 'THU',
        'THUR': 'THU',
        'THURS': 'THU',
        'THURSDAY': 'THU',

        'FRI': 'FRI',
        'FRIDAY': 'FRI'
    };

    return days[value] || value;
}

                /*
                |--------------------------------------------------------------------------
                | NORMALIZE TIME
                |--------------------------------------------------------------------------
                */
function normalizeSwapTime(time)
{
    return String(time || '')
        .trim()
        .toLowerCase()
        .replace(/\s+/g, '')
        .replace(/–/g, '-')
        .replace(/—/g, '-');
}

                /*
                |--------------------------------------------------------------------------
                | CHECK SAME DAY/TIME
                |--------------------------------------------------------------------------
                */

                function canSelectTargetSchedule(schedule)
{
    if (!selectedRequesterSchedule) {
        return false;
    }


    const requesterDay =
        normalizeSwapDay(
            selectedRequesterSchedule.day
        );


    const targetDay =
        normalizeSwapDay(
            schedule.day
        );


    const requesterTime =
        normalizeSwapTime(
            selectedRequesterSchedule.time
        );


    const targetTime =
        normalizeSwapTime(
            schedule.time
        );


    console.log(
        'ROOM SWAP CHECK:',
        {
            requesterDay:
                requesterDay,

            targetDay:
                targetDay,

            requesterTime:
                requesterTime,

            targetTime:
                targetTime
        }
    );


    return (
        requesterDay === targetDay &&
        requesterTime === targetTime
    );
}

                /*
                |--------------------------------------------------------------------------
                | BUILD EXCEL SCHEDULE
                |--------------------------------------------------------------------------
                */

                function buildSwapExcelSchedule(
                    room
                )
                {

                    const body =
                        document.getElementById(
                            'swapExcelBody'
                        );


                    body.innerHTML = '';


                    const schedules =
                        room.schedules ||
                        [];


                    /*
                    | Show selected allowed day/time
                    */

                    const filterBox =
                        document.getElementById(
                            'swapScheduleFilter'
                        );


                    if (
                        selectedRequesterSchedule
                    ) {

                        document.getElementById(
                            'swapAllowedDay'
                        ).textContent =
                            selectedRequesterSchedule.day;


                        document.getElementById(
                            'swapAllowedTime'
                        ).textContent =
                            selectedRequesterSchedule.time;


                        filterBox.style.display =
                            'block';

                    } else {

                        filterBox.style.display =
                            'none';

                    }


                    /*
                    | Default Excel rows
                    */

                    const defaultTimes = [

                        '8:00-9:00',

                        '9:00-10:00',

                        '10:00-11:00',

                        '11:00-12:00',

                        '12:00-1:00',

                        '1:00-2:00',

                        '2:00-3:00',

                        '3:00-4:00',

                        '4:00-5:00'

                    ];


                   function findSchedule(day, time)
{
    return schedules.find(
        function(schedule)
        {
            const scheduleDay =
                normalizeSwapDay(
                    schedule.day
                );

            const scheduleTime =
                normalizeSwapTime(
                    schedule.time
                );

            const wantedDay =
                normalizeSwapDay(
                    day
                );

            const wantedTime =
                normalizeSwapTime(
                    time
                );


            return (
                scheduleDay === wantedDay &&
                scheduleTime === wantedTime
            );
        }
    );
}

                    defaultTimes.forEach(
                        function(
                            time,
                            index
                        )
                        {

                            /*
                            | NOON BREAK
                            */

                            if (
                                index === 4
                            ) {

                                body.innerHTML += `

                                    <tr class="swap-noon-break">

                                        <td colspan="7">

                                            N O O N &nbsp; B R E A K

                                        </td>

                                    </tr>

                                `;

                            }


                            const mon =
                                findSchedule(
                                    'MON',
                                    time
                                );


                            const wed =
                                findSchedule(
                                    'WED',
                                    time
                                );


                            const fri =
                                findSchedule(
                                    'FRI',
                                    time
                                );


                            const tue =
                                findSchedule(
                                    'TUE',
                                    time
                                );


                            const thu =
                                findSchedule(
                                    'THU',
                                    time
                                );


                            body.innerHTML += `

                                <tr>

                                    <td class="swap-excel-time">

                                        ${escapeSwapHtml(
                                            time
                                        )}

                                    </td>


                                    ${buildSwapScheduleCell(
                                        mon
                                    )}


                                    ${buildSwapScheduleCell(
                                        wed
                                    )}


                                    ${buildSwapScheduleCell(
                                        fri
                                    )}


                                    <td class="swap-excel-time">

                                        ${escapeSwapHtml(
                                            time
                                        )}

                                    </td>


                                    ${buildSwapScheduleCell(
                                        tue
                                    )}


                                    ${buildSwapScheduleCell(
                                        thu
                                    )}

                                </tr>

                            `;

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | BUILD SCHEDULE CELL
                |--------------------------------------------------------------------------
                */

                function buildSwapScheduleCell(
                    schedule
                )
                {

                    if (
                        !schedule
                    ) {

                        return `

                            <td class="swap-excel-empty">
                                Empty
                            </td>

                        `;

                    }


                    const allowed =
                        canSelectTargetSchedule(
                            schedule
                        );


                    const programs = [];


                    if (
                        schedule.program
                    ) {

                        programs.push(
                            schedule.program
                        );

                    }


                    const mergedPrograms =
                        schedule.merged_programs ||
                        [];


                    mergedPrograms.forEach(
                        function(program)
                        {

                            if (
                                program &&
                                !programs.includes(
                                    program
                                )
                            ) {

                                programs.push(
                                    program
                                );

                            }

                        }
                    );


                    const isMergedClass =
                        programs.length > 1;


                    const mergeLabel =
                        isMergedClass
                            ? `
                                <div class="swap-merge-badge">
                                    <i class="fa-solid fa-link"></i>
                                    Merged Class
                                </div>

                                <div class="swap-merge-program">
                                    ${escapeSwapHtml(
                                        programs.join(' / ')
                                    )}
                                </div>
                            `
                            : '';


                    /*
                    | WRONG DAY/TIME
                    */

                    if (
                        !allowed
                    ) {

                        return `

                            <td
                                class="
                                    swap-excel-occupied
                                    disabled-swap
                                "
                                title="
                                    Only the same day and time
                                    as your selected schedule
                                    can be swapped.
                                "
                            >

                                <div class="swap-excel-course">

                                    ${escapeSwapHtml(
                                        schedule.course_code ||
                                        ''
                                    )}

                                </div>

                                <div class="swap-excel-subject">

                                    ${escapeSwapHtml(
                                        schedule.subject ||
                                        ''
                                    )}

                                </div>

                                ${mergeLabel}

                                <div style="
                                    font-size:9px;
                                    margin-top:5px;
                                    color:#98a2b3;
                                    font-weight:700;
                                ">

                                    Same day/time required

                                </div>

                            </td>

                        `;

                    }


                    /*
                    | ALLOWED
                    */

                    return `

                        <td
                            class="
                                swap-excel-occupied
                            "
                            data-schedule-id="
                                ${schedule.id}
                            "
                            onclick="
                                selectTargetSwapSchedule(
                                    ${schedule.id}
                                )
                            "
                        >

                            <div class="swap-excel-course">

                                ${escapeSwapHtml(
                                    schedule.course_code ||
                                    ''
                                )}

                            </div>


                            <div class="swap-excel-subject">

                                ${escapeSwapHtml(
                                    schedule.subject ||
                                    ''
                                )}

                            </div>


                            <div class="swap-excel-instructor">

                                ${escapeSwapHtml(
                                    schedule.instructor ||
                                    'Unknown Faculty'
                                )}

                            </div>


                            ${
                                isMergedClass
                                    ? mergeLabel
                                    : (
                                        programs.length
                                            ? `

                                                <div class="
                                                    swap-excel-program
                                                ">

                                                    ${escapeSwapHtml(
                                                        programs.join(
                                                            ' / '
                                                        )
                                                    )}

                                                </div>

                                            `
                                            : ''
                                    )
                            }

                        </td>

                    `;

                }


                /*
                |--------------------------------------------------------------------------
                | SELECT TARGET SCHEDULE
                |--------------------------------------------------------------------------
                */

                function selectTargetSwapSchedule(scheduleId)
{
    /*
    |--------------------------------------------------------------------------
    | STEP 1 MUST BE SELECTED FIRST
    |--------------------------------------------------------------------------
    */

    if (!selectedRequesterSchedule) {

        alert(
            'Please select your schedule first.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | FIND TARGET SCHEDULE
    |--------------------------------------------------------------------------
    */

    const schedules =
        selectedSwapRoom?.schedules || [];


    const schedule =
        schedules.find(
            function(item)
            {
                return Number(item.id) ===
                    Number(scheduleId);
            }
        );


    if (!schedule) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SAME DAY + SAME TIME
    |--------------------------------------------------------------------------
    */

    if (
        !canSelectTargetSchedule(
            schedule
        )
    ) {

        alert(
            'You can only swap with a schedule that has the same day and same time as your selected schedule.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SAME SCHEDULE
    |--------------------------------------------------------------------------
    */

    if (
        Number(schedule.id) ===
        Number(
            selectedRequesterSchedule.id
        )
    ) {

        alert(
            'You cannot swap a schedule with itself.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | DIFFERENT ROOM
    |--------------------------------------------------------------------------
    */

    if (
        Number(schedule.room_id) ===
        Number(
            selectedRequesterSchedule.room_id
        )
    ) {

        alert(
            'Both schedules are already assigned to the same room.'
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE TARGET
    |--------------------------------------------------------------------------
    */

    selectedTargetSchedule =
        schedule;


    /*
    |--------------------------------------------------------------------------
    | HIDDEN FORM VALUES
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'target_schedule_id'
    ).value =
        schedule.id;


    document.getElementById(
        'target_room_id'
    ).value =
        schedule.room_id;


    document.getElementById(
        'target_user_id'
    ).value =
        schedule.faculty_id || '';


    /*
    |--------------------------------------------------------------------------
    | REMOVE OLD HIGHLIGHT
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '#swapExcelBody .swap-excel-occupied'
        )
        .forEach(
            function(cell)
            {
                cell.classList.remove(
                    'selected'
                );
            }
        );


    /*
    |--------------------------------------------------------------------------
    | ADD HIGHLIGHT TO CLICKED CELL
    |--------------------------------------------------------------------------
    */

    const selectedCell =
        document.querySelector(
            '#swapExcelBody ' +
            '.swap-excel-occupied' +
            '[data-schedule-id="' +
            schedule.id +
            '"]'
        );


    if (selectedCell) {

        selectedCell.classList.add(
            'selected'
        );


        /*
        | Scroll slightly to the selected cell
        | if necessary.
        */

        selectedCell.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'nearest'
        });

    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SELECTED TARGET
    |--------------------------------------------------------------------------
    */

    const selectedBox =
        document.getElementById(
            'selectedTargetSchedule'
        );


    selectedBox.innerHTML = `

        <div style="
            display:flex;
            align-items:center;
            gap:8px;
            margin-bottom:8px;
            font-weight:900;
        ">

            <i
                class="fa-solid fa-circle-check"
                style="color:#176b3a;"
            ></i>

            Target Schedule Selected

        </div>


        <div>

            <strong>
                ${escapeSwapHtml(
                    schedule.course_code || ''
                )}
            </strong>

            —

            ${escapeSwapHtml(
                schedule.subject || ''
            )}

        </div>


        <div style="margin-top:5px;">

            <strong>
                Day:
            </strong>

            ${escapeSwapHtml(
                schedule.day || ''
            )}

        </div>


        <div style="margin-top:3px;">

            <strong>
                Time:
            </strong>

            ${escapeSwapHtml(
                schedule.time || ''
            )}

        </div>


        <div style="margin-top:3px;">

            <strong>
                Faculty:
            </strong>

            ${escapeSwapHtml(
                schedule.instructor ||
                'Unknown Faculty'
            )}

        </div>


        <div style="margin-top:3px;">

            <strong>
                Room:
            </strong>

            ${escapeSwapHtml(
                selectedSwapRoom.room_name
            )}

        </div>

    `;


    selectedBox.style.display =
        'block';


    /*
    |--------------------------------------------------------------------------
    | DEBUG
    |--------------------------------------------------------------------------
    */

    console.log(
        'TARGET SCHEDULE SELECTED:',
        {
            id: schedule.id,
            day: schedule.day,
            time: schedule.time,
            room_id: schedule.room_id,
            faculty_id: schedule.faculty_id
        }
    );
}

                /*
                |--------------------------------------------------------------------------
                | REASON
                |--------------------------------------------------------------------------
                */

                function selectReason(
                    reason,
                    element
                )
                {

                    document
                        .querySelectorAll(
                            '.reason-option'
                        )
                        .forEach(
                            function(option)
                            {

                                option.classList.remove(
                                    'selected'
                                );

                            }
                        );


                    element.classList.add(
                        'selected'
                    );


                    const reasonField =
                        document.getElementById(
                            'reason'
                        );


                    const otherReason =
                        document.getElementById(
                            'otherReason'
                        );


                    if (
                        reason === 'Other'
                    ) {

                        reasonField.value =
                            '';


                        otherReason.focus();

                    } else {

                        reasonField.value =
                            reason;

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | BACK TO DEPARTMENTS
                |--------------------------------------------------------------------------
                */

                function backToSwapDepartments()
                {

                    selectedSwapDepartment =
                        null;

                    selectedSwapBuilding =
                        null;

                    selectedSwapRoom =
                        null;


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | BACK TO BUILDINGS
                |--------------------------------------------------------------------------
                */

                function backToSwapBuildings()
                {

                    selectedSwapBuilding =
                        null;

                    selectedSwapRoom =
                        null;


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | BACK TO ROOMS
                |--------------------------------------------------------------------------
                */

                function backToSwapRooms()
                {

                    selectedSwapRoom =
                        null;


                    document.getElementById(
                        'swapDepartments'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapBuildings'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'swapRooms'
                    ).style.display =
                        'block';


                    document.getElementById(
                        'swapRoomSchedule'
                    ).style.display =
                        'none';

                }


                /*
                |--------------------------------------------------------------------------
                | RESET
                |--------------------------------------------------------------------------
                */

                function resetSwap()
                {

                    document
                        .getElementById(
                            'swapForm'
                        )
                        .reset();


                    selectedRequesterSchedule =
                        null;

                    selectedSwapDepartment =
                        null;

                    selectedSwapBuilding =
                        null;

                    selectedSwapRoom =
                        null;

                    selectedTargetSchedule =
                        null;


                    document
                        .querySelectorAll(
                            '.booking-card'
                        )
                        .forEach(
                            function(card)
                            {

                                card.classList.remove(
                                    'selected'
                                );

                            }
                        );


                    document
                        .querySelectorAll(
                            '.reason-option'
                        )
                        .forEach(
                            function(option)
                            {

                                option.classList.remove(
                                    'selected'
                                );

                            }
                        );


                    document.getElementById(
                        'selectedRequesterSchedule'
                    ).style.display =
                        'none';


                    document.getElementById(
                        'selectedTargetSchedule'
                    ).style.display =
                        'none';

                    const mySearch =
                        document.getElementById(
                            'myScheduleSearch'
                        );

                    if (mySearch) {
                        mySearch.value = '';
                    }

                    const roomSearch =
                        document.getElementById(
                            'swapRoomSearch'
                        );

                    if (roomSearch) {
                        roomSearch.value = '';
                    }

                    const roomResults =
                        document.getElementById(
                            'swapRoomSearchResults'
                        );

                    if (roomResults) {
                        roomResults.innerHTML = '';
                        roomResults.style.display = 'none';
                    }

                    const selectedRoomNote =
                        document.getElementById(
                            'swapSelectedRoomNote'
                        );

                    if (selectedRoomNote) {
                        selectedRoomNote.style.display = 'none';
                    }

                    backToSwapDepartments();

                }


                /*
                |--------------------------------------------------------------------------
                | BEFORE SUBMIT
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById(
                        'swapForm'
                    )
                    .addEventListener(
                        'submit',
                        function(event)
                        {

                            /*
                            | Make sure requester schedule exists
                            */

                            if (
                                !selectedRequesterSchedule
                            ) {

                                event.preventDefault();

                                alert(
                                    'Please select your schedule first.'
                                );

                                return;

                            }


                            /*
                            | Make sure target exists
                            */

                            if (
                                !selectedTargetSchedule
                            ) {

                                event.preventDefault();

                                alert(
                                    'Please select another faculty schedule.'
                                );

                                return;

                            }


                            /*
                            | FINAL SAME DAY CHECK
                            */

                            const requesterDay =
                                normalizeSwapDay(
                                    selectedRequesterSchedule.day
                                );


                            const targetDay =
                                normalizeSwapDay(
                                    selectedTargetSchedule.day
                                );


                            if (
                                requesterDay !==
                                targetDay
                            ) {

                                event.preventDefault();

                                alert(
                                    'Room swap requires the same day.'
                                );

                                return;

                            }


                            /*
                            | FINAL SAME TIME CHECK
                            */

                            const requesterTime =
                                normalizeSwapTime(
                                    selectedRequesterSchedule.time
                                );


                            const targetTime =
                                normalizeSwapTime(
                                    selectedTargetSchedule.time
                                );


                            if (
                                requesterTime !==
                                targetTime
                            ) {

                                event.preventDefault();

                                alert(
                                    'Room swap requires the same time.'
                                );

                                return;

                            }


                            /*
                            | Same room
                            */

                            if (
                                Number(
                                    selectedRequesterSchedule.room_id
                                ) ===
                                Number(
                                    selectedTargetSchedule.room_id
                                )
                            ) {

                                event.preventDefault();

                                alert(
                                    'Both schedules already use the same room.'
                                );

                                return;

                            }


                            /*
                            | Reason
                            */

                            const reasonField =
                                document.getElementById(
                                    'reason'
                                );


                            const selectedReason =
                                document.querySelector(
                                    '.reason-option.selected'
                                );


                            if (
                                selectedReason &&
                                selectedReason.innerText.trim()
                                    === 'Other'
                            ) {

                                reasonField.value =
                                    document
                                        .getElementById(
                                            'otherReason'
                                        )
                                        .value
                                        .trim();

                            }


                            if (
                                !reasonField.value.trim()
                            ) {

                                event.preventDefault();

                                alert(
                                    'Please select or enter a reason.'
                                );

                                return;

                            }


                            /*
                            | IMPORTANT:
                            | No preventDefault here.
                            | The form is allowed to submit.
                            */

                        }
                    );

            </script>

        </main>

    </div>

</div>

@include('footerheader.footer')