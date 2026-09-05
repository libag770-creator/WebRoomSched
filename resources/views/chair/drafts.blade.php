<div class="wrapper">

    @include('footerheader.header')

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content">

            <style>

                body {
                    background: #f5f5f5;
                }

                .draft-page {
                    max-width: 1200px;
                    margin: auto;
                }

                .title {
                    font-size: 26px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 5px;
                }

                .subtitle {
                    color: #777;
                    margin-bottom: 25px;
                }

                .success {
                    background: #e8f5e9;
                    color: #2e7d32;
                    padding: 12px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }

                .draft-card {
                    background: white;
                    border-radius: 10px;
                    padding: 20px;
                    margin-bottom: 20px;

                    box-shadow:
                        0 2px 8px
                        rgba(0,0,0,.08);
                }

                .draft-room {
                    font-size: 19px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 5px;
                }
.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    padding: 9px 16px;

    background: #2e7d32;
    color: white;

    border-radius: 6px;

    text-decoration: none;

    font-size: 13px;

    font-weight: bold;

    transition: .2s;
}

.btn-edit:hover {
    background: #1b5e20;
    transform: translateY(-1px);
}
                .draft-date {
                    color: #777;
                    font-size: 13px;
                    margin-bottom: 15px;
                }

                .draft-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .draft-table th {
                    background: #2e7d32;
                    color: white;
                    padding: 10px;
                    text-align: left;
                }

                .draft-table td {
                    padding: 10px;
                    border-bottom: 1px solid #eee;
                }

                .draft-table tr:hover {
                    background: #f9fff9;
                }

                .year-badge {
    display: inline-block;
    padding: 4px 8px;
    background: #e8f5e9;
    color: #2e7d32;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
}
                .empty {
                    background: white;
                    padding: 40px;
                    text-align: center;
                    border-radius: 10px;
                    color: #777;
                }

            </style>


            <div class="draft-page">

                <div class="title">
                    Schedule Drafts
                </div>

                <div class="subtitle">
                    Saved schedule drafts that have not been uploaded yet.
                </div>


                @if(session('success'))

                    <div class="success">
                        {{ session('success') }}
                    </div>

                @endif


                @if($drafts->count())


                    @php

                        $groupedDrafts =
                            $drafts->groupBy('room_id');

                    @endphp


                    @foreach($groupedDrafts as $roomId => $roomDrafts)

                        @php
                            $room =
                                $roomDrafts
                                    ->first()
                                    ->room;
                        @endphp


                        <div class="draft-card">

                            <div class="draft-room">

                                {{ $room->room_name }}

                            </div>


                            <div class="draft-date">

                                Last saved:
                                {{
                                    $roomDrafts
                                        ->first()
                                        ->created_at
                                        ->format('M d, Y h:i A')
                                }}

                            </div>


                            <table class="draft-table">

                                <thead>

                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Course Code</th>
                                        <th>Subject</th>
                                        <th>Instructor</th>
                                        <th>Year Level</th>
                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($roomDrafts as $draft)

                                        <tr>

                                            <td>
                                                {{ $draft->day }}
                                            </td>

                                            <td>
                                                {{ $draft->time }}
                                            </td>

                                            <td>
                                                {{ $draft->course_code ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $draft->subject }}
                                            </td>

                                            <td>
                                                {{ $draft->instructor }}
                                            </td>

                                           <td>
    @if($draft->year_level)

        <span class="year-badge">
            {{ $draft->year_level }}
        </span>

    @else

        -

    @endif
</td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>
 <!-- EDIT DRAFT BUTTON -->

                        <div
                            style="
                                margin-top:15px;
                                display:flex;
                                justify-content:flex-end;
                            "
                        >

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
                    @endforeach


                @else

                    <div class="empty">

                        No saved schedule drafts yet.

                    </div>

                @endif

            </div>

        </main>

    </div>

    @include('footerheader.footer')

</div>