@include('footerheader.header')

<div class="wrapper">

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content">

            <style>

                /* =========================================================
                   PAGE
                ========================================================= */

                body {
                    background: #f4f7f5;
                }

                .draft-page {
                    max-width: 1150px;
                    margin: 0 auto;
                    padding: 10px 0 40px;
                }


                /* =========================================================
                   PAGE HEADER
                ========================================================= */

                .page-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;

                    margin-bottom: 28px;
                }

                .page-header-left {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                }

                .page-header-icon {
                    width: 52px;
                    height: 52px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    background: #e8f5e9;
                    color: #2e7d32;

                    border-radius: 12px;

                    font-size: 21px;

                    box-shadow:
                        0 2px 6px rgba(46, 125, 50, .08);
                }

                .page-title {
                    margin: 0;

                    font-size: 25px;
                    font-weight: 700;

                    color: #1f2937;

                    letter-spacing: -.3px;
                }

                .page-subtitle {
                    margin: 4px 0 0;

                    font-size: 13px;

                    color: #667085;
                }


                /* =========================================================
                   DRAFT COUNT
                ========================================================= */

                .draft-count {
                    display: flex;
                    align-items: center;
                    gap: 8px;

                    padding: 9px 14px;

                    background: #ffffff;

                    border: 1px solid #e1e7e3;

                    border-radius: 8px;

                    color: #475467;

                    font-size: 12px;
                    font-weight: 600;

                    box-shadow:
                        0 1px 3px rgba(0,0,0,.04);
                }

                .draft-count i {
                    color: #2e7d32;
                }


                /* =========================================================
                   SUCCESS MESSAGE
                ========================================================= */

                .success-message {
                    display: flex;
                    align-items: center;
                    gap: 10px;

                    background: #ecfdf3;

                    border: 1px solid #b7e4c7;

                    color: #176b3a;

                    padding: 13px 16px;

                    border-radius: 8px;

                    margin-bottom: 20px;

                    font-size: 13px;
                    font-weight: 600;
                }

                .success-message i {
                    font-size: 16px;
                }


                /* =========================================================
                   ROOM LIST
                ========================================================= */

                .rooms-section {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }


                /* =========================================================
                   ROOM CARD
                ========================================================= */

                .room-card {
                    background: #ffffff;

                    border: 1px solid #e5e9e6;

                    border-radius: 12px;

                    min-height: 82px;

                    padding: 15px 18px;

                    display: flex;
                    align-items: center;
                    justify-content: space-between;

                    box-shadow:
                        0 2px 6px rgba(0, 0, 0, .04);

                    transition:
                        transform .18s ease,
                        box-shadow .18s ease,
                        border-color .18s ease;
                }

                .room-card:hover {
                    transform: translateY(-2px);

                    border-color: #c9dfce;

                    box-shadow:
                        0 7px 18px rgba(0, 0, 0, .07);
                }


                /* =========================================================
                   ROOM INFORMATION
                ========================================================= */

                .room-left {
                    display: flex;
                    align-items: center;
                    gap: 15px;

                    min-width: 0;
                }

                .room-icon {
                    width: 48px;
                    height: 48px;

                    flex-shrink: 0;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    background: #f0f8f2;

                    border: 1px solid #dcecdf;

                    border-radius: 10px;

                    color: #2e7d32;

                    font-size: 19px;
                }

                .room-details {
                    min-width: 0;
                }

                .room-name {
                    margin: 0;

                    font-size: 16px;

                    font-weight: 700;

                    color: #1f2937;
                }

                .room-status {
                    display: flex;
                    align-items: center;
                    gap: 6px;

                    margin-top: 5px;

                    font-size: 11px;

                    color: #667085;
                }

                .status-dot {
                    width: 7px;
                    height: 7px;

                    border-radius: 50%;

                    background: #2e7d32;
                }


                /* =========================================================
                   ROOM RIGHT SIDE
                ========================================================= */

                .room-action {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }

                .draft-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;

                    padding: 7px 10px;

                    border-radius: 7px;

                    background: #fff8e1;

                    border: 1px solid #f2df9b;

                    color: #856404;

                    font-size: 10px;

                    font-weight: 700;

                    text-transform: uppercase;

                    letter-spacing: .4px;
                }

                .draft-badge i {
                    font-size: 10px;
                }


                /* =========================================================
                   EDIT BUTTON
                ========================================================= */

                .btn-edit {
                    display: inline-flex;

                    align-items: center;
                    justify-content: center;

                    gap: 8px;

                    min-width: 125px;

                    padding: 10px 16px;

                    background: #2e7d32;

                    color: #ffffff;

                    border: 1px solid #2e7d32;

                    border-radius: 7px;

                    text-decoration: none;

                    font-size: 12px;

                    font-weight: 700;

                    transition:
                        background .18s ease,
                        border-color .18s ease,
                        transform .18s ease,
                        box-shadow .18s ease;
                }

                .btn-edit:hover {
                    background: #256b2a;

                    border-color: #256b2a;

                    color: #ffffff;

                    transform: translateY(-1px);

                    box-shadow:
                        0 4px 10px rgba(46, 125, 50, .18);
                }

                .btn-edit:active {
                    transform: translateY(0);
                }


                /* =========================================================
                   EMPTY STATE
                ========================================================= */

                .empty-state {
                    background: #ffffff;

                    border: 1px solid #e5e9e6;

                    border-radius: 12px;

                    padding: 65px 30px;

                    text-align: center;

                    box-shadow:
                        0 2px 6px rgba(0,0,0,.04);
                }

                .empty-icon {
                    width: 64px;
                    height: 64px;

                    margin: 0 auto 16px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    border-radius: 50%;

                    background: #f0f8f2;

                    color: #2e7d32;

                    font-size: 25px;
                }

                .empty-title {
                    margin-bottom: 6px;

                    color: #344054;

                    font-size: 17px;

                    font-weight: 700;
                }

                .empty-text {
                    color: #667085;

                    font-size: 12px;
                }


                /* =========================================================
                   MOBILE
                ========================================================= */

                @media (max-width: 750px) {

                    .draft-page {
                        padding: 5px 15px 30px;
                    }

                    .page-header {
                        align-items: flex-start;
                        gap: 15px;
                    }

                    .draft-count {
                        display: none;
                    }

                    .page-title {
                        font-size: 21px;
                    }

                    .page-subtitle {
                        font-size: 12px;
                    }

                    .room-card {
                        padding: 14px;

                        min-height: auto;

                        gap: 15px;

                        align-items: flex-start;
                    }

                    .room-action {
                        flex-direction: column;
                        align-items: flex-end;
                        gap: 8px;
                    }

                    .draft-badge {
                        display: none;
                    }

                    .btn-edit {
                        min-width: 110px;
                    }

                }


                @media (max-width: 500px) {

                    .room-card {
                        flex-direction: column;
                    }

                    .room-left {
                        width: 100%;
                    }

                    .room-action {
                        width: 100%;
                        align-items: stretch;
                    }

                    .btn-edit {
                        width: 100%;
                    }

                }

            </style>


            <div class="draft-page">


                <!-- =====================================================
                     PAGE HEADER
                ====================================================== -->

                <div class="page-header">

                    <div class="page-header-left">

                        <div class="page-header-icon">

                            <i class="fa-solid fa-file-pen"></i>

                        </div>

                        <div>

                            <h1 class="page-title">
                                Schedule Drafts
                            </h1>

                            <p class="page-subtitle">
                                Continue editing your saved classroom schedules.
                            </p>

                        </div>

                    </div>


                    @if($drafts->count())

                        @php
                            $draftRoomCount =
                                $drafts->groupBy('room_id')->count();
                        @endphp

                        <div class="draft-count">

                            <i class="fa-solid fa-door-open"></i>

                            {{ $draftRoomCount }}
                            {{ $draftRoomCount == 1 ? 'Room' : 'Rooms' }}

                        </div>

                    @endif

                </div>


                <!-- =====================================================
                     SUCCESS MESSAGE
                ====================================================== -->

                @if(session('success'))

                    <div class="success-message">

                        <i class="fa-solid fa-circle-check"></i>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                @endif


                <!-- =====================================================
                     ROOMS
                ====================================================== -->

                @if($drafts->count())

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Group drafts by room
                        |--------------------------------------------------------------------------
                        */

                        $groupedDrafts =
                            $drafts->groupBy('room_id');

                    @endphp


                    

                    <div style="margin-bottom:18px;padding:12px 14px;background:#eef8ef;border:1px solid #d7e8da;border-radius:9px;color:#176b3a;font-size:12px;font-weight:700;">Active Term: {{ $activeTerm->academic_year ?? '-' }} • {{ $activeTerm->semester ?? '-' }} — Drafts shown here belong only to the active term.</div>

                    <div class="rooms-section">


                        @foreach($groupedDrafts as $roomId => $roomDrafts)

                            @php

                                $room =
                                    $roomDrafts
                                        ->first()
                                        ->room;

                            @endphp


                            <!-- ROOM CARD -->

                            <div class="room-card">


                                <!-- ROOM INFORMATION -->

                                <div class="room-left">

                                    <div class="room-icon">

                                        <i class="fa-solid fa-door-open"></i>

                                    </div>


                                    <div class="room-details">

                                        <h2 class="room-name">

                                            {{ $room->room_name }}

                                        </h2>


                                        <div class="room-status">

                                            <span class="status-dot"></span>

                                            Saved schedule draft

                                        </div>

                                    </div>

                                </div>


                                <!-- ROOM ACTION -->

                                <div class="room-action">


                                    <div class="draft-badge">

                                        <i class="fa-solid fa-file-pen"></i>

                                        Draft

                                    </div>


                                    <a
                                        href="{{ route(
                                            'chair.drafts.edit',
                                            $room->id
                                        ) }}"
                                        class="btn-edit"
                                    >

                                        <i class="fa-solid fa-pen-to-square"></i>

                                        Edit Draft

                                    </a>


                                </div>

                            </div>

                        @endforeach


                    </div>


                @else


                    <!-- =================================================
                         EMPTY STATE
                    ================================================== -->

                    <div class="empty-state">

                        <div class="empty-icon">

                            <i class="fa-regular fa-file-lines"></i>

                        </div>


                        <div class="empty-title">

                            No Schedule Drafts

                        </div>


                        <div class="empty-text">

                            Rooms with saved schedule drafts will appear here.

                        </div>

                    </div>


                @endif


            </div>

        </main>

    </div>

</div>

@include('footerheader.footer')