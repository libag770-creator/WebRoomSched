                @if(request('date') && request('time'))

                    @if(count($results) > 0)

                        {{-- =================================================
                             FILTER BUTTONS
                        ================================================== --}}

                        <div class="filter-wrapper">

                            <div class="filter-title">
                                <i class="fa-solid fa-filter"></i>
                                Filter Room Status
                            </div>

                            <div class="filter-buttons">

                                <button
                                    type="button"
                                    class="filter-btn active"
                                    data-filter="all"
                                >
                                    All Rooms
                                    <span class="filter-count">
                                        ({{ count($results) }})
                                    </span>
                                </button>


                                <button
                                    type="button"
                                    class="filter-btn"
                                    data-filter="Available"
                                >
                                    🟢 Available
                                    <span class="filter-count">
                                        ({{ collect($results)->where('status', 'Available')->count() }})
                                    </span>
                                </button>


                                <button
                                    type="button"
                                    class="filter-btn"
                                    data-filter="Occupied"
                                >
                                    🔴 Occupied
                                    <span class="filter-count">
                                        ({{ collect($results)->where('status', 'Occupied')->count() }})
                                    </span>
                                </button>


                                <button
                                    type="button"
                                    class="filter-btn"
                                    data-filter="Reserved"
                                >
                                    🟠 Reserved
                                    <span class="filter-count">
                                        ({{ collect($results)->where('status', 'Reserved')->count() }})
                                    </span>
                                </button>


                                <button
                                    type="button"
                                    class="filter-btn"
                                    data-filter="Pending"
                                >
                                    🟡 Pending
                                    <span class="filter-count">
                                        ({{ collect($results)->where('status', 'Pending')->count() }})
                                    </span>
                                </button>

                            </div>

                        </div>


                        {{-- =================================================
                             ORGANIZED BY DEPARTMENT
                        ================================================== --}}

                        @php

                            $organizedResults = collect($results)
                                ->groupBy(function ($item) {

                                    return $item['room']->department_id;

                                });

                        @endphp


                        @foreach($organizedResults as $departmentId => $departmentRooms)

                            @php

                                $department =
                                    $departmentRooms->first()['room']->department;

                                $organizedBuildings =
                                    $departmentRooms->groupBy(function ($item) {

                                        return $item['room']->building_id;

                                    });

                            @endphp


                            <div
                                class="department-section"
                                data-department-section
                            >

                                <div class="department-title">

                                    <i class="fa-solid fa-building-columns"></i>

                                    {{ $department->name ?? 'Department' }}

                                </div>


                                @foreach($organizedBuildings as $buildingId => $buildingRooms)

                                    @php

                                        $firstRoom =
                                            $buildingRooms->first()['room'];

                                        $buildingName =
                                            is_string($firstRoom->building)
                                                ? $firstRoom->building
                                                : (
                                                    $firstRoom->building?->name
                                                    ??
                                                    $firstRoom->building?->building_name
                                                    ??
                                                    'Building'
                                                );

                                    @endphp


                                    <div
                                        class="building"
                                        data-building-section
                                    >

                                        <div class="building-title">

                                            <i class="fa-solid fa-building"></i>

                                            {{ $buildingName }}

                                        </div>


                                        <div class="rooms">

                                            @foreach($buildingRooms as $item)

                                                @php

                                                    $room =
                                                        $item['room'];

                                                    $status =
                                                        $item['status'];

                                                    $purpose =
                                                        strtolower(
                                                            trim(
                                                                (string) $room->purpose
                                                            )
                                                        );

                                                    if (
                                                        $purpose === 'lab' ||
                                                        $purpose === 'laboratory'
                                                    ) {
                                                        $roomType =
                                                            'Laboratory';

                                                    } elseif (
                                                        $purpose === 'lecture' ||
                                                        $purpose === 'lec'
                                                    ) {
                                                        $roomType =
                                                            'Lecture Room';

                                                    } else {
                                                        $roomType =
                                                            $room->purpose
                                                            ?: 'Not specified';
                                                    }

                                                @endphp


                                                <div
                                                    class="room-card"
                                                    data-room-status="{{ $status }}"
                                                >

                                                    <div class="room-header">

                                                        <div class="room-name">
                                                            {{ $room->room_name }}
                                                        </div>


                                                        @if($status === 'Available')

                                                            <div class="room-status status-available">
                                                                🟢 Available
                                                            </div>

                                                        @elseif($status === 'Occupied')

                                                            <div class="room-status status-occupied">
                                                                🔴 Occupied
                                                            </div>

                                                        @elseif($status === 'Reserved')

                                                            <div class="room-status status-reserved">
                                                                🟠 Reserved
                                                            </div>

                                                        @elseif($status === 'Pending')

                                                            <div class="room-status status-pending">
                                                                🟡 Pending
                                                            </div>

                                                        @endif

                                                    </div>


                                                    <div class="department-name">

                                                        {{ $department->name ?? 'Department' }}

                                                    </div>

                                                    @if(!empty($item['matched_schedules']))
                                                        <div class="matched-time-list">
                                                            @foreach($item['matched_schedules'] as $matched)
                                                                <span class="matched-time">
                                                                    <i class="fa-regular fa-clock"></i>
                                                                    {{ $matched['time'] }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @elseif(!empty($item['matched_time']))
                                                        <div class="matched-time">
                                                            <i class="fa-regular fa-clock"></i>
                                                            Time: <strong>{{ $item['matched_time'] }}</strong>
                                                        </div>
                                                    @endif


                                                    {{-- ROOM FEATURES --}}

                                                    <div class="room-features">


                                                        <div class="feature">

                                                            <span class="feature-label">
                                                                Capacity
                                                            </span>

                                                            <span class="feature-value">

                                                                {{ $room->capacity ?? 0 }}
                                                                students

                                                            </span>

                                                        </div>


                                                        <div class="feature">

                                                            <span class="feature-label">
                                                                Room Type
                                                            </span>

                                                            <span class="feature-value">

                                                                {{ $roomType }}

                                                            </span>

                                                        </div>


                                                        <div class="feature">

                                                            <span class="feature-label">
                                                                Computers
                                                            </span>

                                                            <span class="feature-value">

                                                                {{ $room->computers ?? 0 }}

                                                            </span>

                                                        </div>


                                                        <div class="feature">

                                                            <span class="feature-label">
                                                                TV
                                                            </span>

                                                            <span class="feature-value">

                                                                {{ $room->has_tv ? 'Available' : 'Not Available' }}

                                                            </span>

                                                        </div>


                                                        <div class="feature">

                                                            <span class="feature-label">
                                                                Projector
                                                            </span>

                                                            <span class="feature-value">

                                                                {{ $room->has_projector ? 'Available' : 'Not Available' }}

                                                            </span>

                                                        </div>


                                                        <div class="feature">

                                                            <span class="feature-label">
                                                                Other Departments
                                                            </span>

                                                            <span class="feature-value">

                                                                {{ $room->allow_other_departments ? 'Allowed' : 'Not Allowed' }}

                                                            </span>

                                                        </div>

                                                    </div>


                                                    @if($room->purpose)

                                                        <div class="room-purpose">

                                                            <strong>Purpose:</strong>

                                                            {{ $room->purpose }}

                                                        </div>

                                                    @endif


                                                    @if($room->description)

                                                        <div class="room-description">

                                                            {{ $room->description }}

                                                        </div>

                                                    @endif


                                                    {{-- =========================================================
                                                         RESERVE ROOM
                                                         The selectable options come ONLY from faculty_subjects.
                                                         They are NOT taken from the Excel timetable/schedules table.
                                                    ========================================================== --}}

                                                    @if($status === 'Available')

                                                        @php
                                                            $reservationModalId =
                                                                'reservationDialog_' .
                                                                $room->id . '_' .
                                                                preg_replace(
                                                                    '/[^A-Za-z0-9_]/',
                                                                    '_',
                                                                    (string) ($item['start_time'] ?? request('time'))
                                                                );

                                                            $reservationTime =
                                                                $item['time'] ?? request('time');

                                                            $reservationStart =
                                                                $item['start_time'] ??
                                                                trim(explode('-', (string) $reservationTime)[0] ?? '');

                                                            $reservationEnd =
                                                                $item['end_time'] ??
                                                                trim(explode('-', (string) $reservationTime)[1] ?? '');

                                                            $buildingDisplay =
                                                                is_string($room->building)
                                                                    ? $room->building
                                                                    : (
                                                                        $room->building?->name ??
                                                                        $room->building?->building_name ??
                                                                        ''
                                                                    );
                                                        @endphp

                                                        <div class="reserve-form">

                                                            <button
                                                                type="button"
                                                                class="reserve-button"
                                                                onclick="document.getElementById('{{ $reservationModalId }}').showModal()"
                                                            >
                                                                <i class="fa-solid fa-calendar-plus"></i>
                                                                Request This Room
                                                            </button>

                                                        </div>

                                                        {{-- =========================================================
                                                             NATIVE DIALOG
                                                             No JavaScript is required, so this also works when
                                                             vacant-results.blade.php is loaded through AJAX.
                                                        ========================================================== --}}

                                                        <dialog
                                                            id="{{ $reservationModalId }}"
                                                            class="reservation-dialog"
                                                        >

                                                            <div class="reservation-dialog-card">

                                                                <div class="reservation-dialog-header">
                                                                    <div>
                                                                        <h3>
                                                                            <i class="fa-solid fa-calendar-check"></i>
                                                                            Select Schedule for Reservation
                                                                        </h3>
                                                                        <p>
                                                                            Choose one subject assigned to you in Set Faculty.
                                                                        </p>
                                                                    </div>

                                                                    <button
                                                                        type="button"
                                                                        class="reservation-dialog-close"
                                                                        onclick="document.getElementById('{{ $reservationModalId }}').close()"
                                                                    >
                                                                        &times;
                                                                    </button>
                                                                </div>

                                                                <div class="reservation-dialog-summary">
                                                                    <div>
                                                                        <span>Room</span>
                                                                        <strong>{{ $room->room_name }}</strong>
                                                                    </div>

                                                                    <div>
                                                                        <span>Building</span>
                                                                        <strong>{{ $buildingDisplay }}</strong>
                                                                    </div>

                                                                    <div>
                                                                        <span>Date</span>
                                                                        <strong>{{ request('date') }}</strong>
                                                                    </div>

                                                                    <div>
                                                                        <span>Reservation Time</span>
                                                                        <strong>{{ $reservationTime }}</strong>
                                                                    </div>
                                                                </div>

                                                                <form
                                                                    method="POST"
                                                                    action="{{ route('faculty.reserve.room', $room->id) }}"
                                                                >
                                                                    @csrf

                                                                    <input
                                                                        type="hidden"
                                                                        name="date"
                                                                        value="{{ request('date') }}"
                                                                    >

                                                                    <input
                                                                        type="hidden"
                                                                        name="time"
                                                                        value="{{ $reservationTime }}"
                                                                    >

                                                                    <input
                                                                        type="hidden"
                                                                        name="start_time"
                                                                        value="{{ $reservationStart }}"
                                                                    >

                                                                    <input
                                                                        type="hidden"
                                                                        name="end_time"
                                                                        value="{{ $reservationEnd }}"
                                                                    >

                                                                    <div class="reservation-dialog-section-title">
                                                                        Your Set Faculty Subjects
                                                                    </div>

                                                                    <div class="reservation-subject-list">

                                                                        @forelse($facultySubjectOptions ?? [] as $facultySubject)

                                                                            <label class="reservation-subject-option">

                                                                                <input
                                                                                    type="radio"
                                                                                    name="faculty_subject_id"
                                                                                    value="{{ $facultySubject['id'] }}"
                                                                                    required
                                                                                >

                                                                                <span class="reservation-subject-card">

                                                                                    <span class="reservation-subject-top">
                                                                                        <span>
                                                                                            <strong>
                                                                                                {{ $facultySubject['course_code'] ?: 'No Course Code' }}
                                                                                            </strong>

                                                                                            <small>
                                                                                                {{ $facultySubject['subject'] ?: 'No Subject' }}
                                                                                            </small>
                                                                                        </span>

                                                                                        @if(!empty($facultySubject['program']))
                                                                                            <b>
                                                                                                {{ $facultySubject['program'] }}
                                                                                            </b>
                                                                                        @endif
                                                                                    </span>

                                                                                    <span class="reservation-subject-details">

                                                                                        @if(!empty($facultySubject['year_level']))
                                                                                            <span>
                                                                                                Year: {{ $facultySubject['year_level'] }}
                                                                                            </span>
                                                                                        @endif

                                                                                        @if(!empty($facultySubject['major']))
                                                                                            <span>
                                                                                                Major: {{ $facultySubject['major'] }}
                                                                                            </span>
                                                                                        @endif

                                                                                        @if(!empty($facultySubject['subject_type']))
                                                                                            <span>
                                                                                                {{ $facultySubject['subject_type'] }}
                                                                                            </span>
                                                                                        @endif

                                                                                    </span>

                                                                                    <span class="reservation-subject-note">
                                                                                        Assigned to you in Set Faculty
                                                                                    </span>

                                                                                </span>

                                                                            </label>

                                                                        @empty

                                                                            <div class="reservation-no-subjects">
                                                                                <strong>No Set Faculty subjects are available.</strong>
                                                                                <br>
                                                                                Ask your Chair to assign a subject to your faculty account first.
                                                                            </div>

                                                                        @endforelse

                                                                    </div>

                                                                    <div class="reservation-dialog-footer">

                                                                        <button
                                                                            type="button"
                                                                            class="reservation-dialog-cancel"
                                                                            onclick="document.getElementById('{{ $reservationModalId }}').close()"
                                                                        >
                                                                            Cancel
                                                                        </button>

                                                                        <button
                                                                            type="submit"
                                                                            class="reservation-dialog-submit"
                                                                            @if(empty($facultySubjectOptions)) disabled @endif
                                                                        >
                                                                            <i class="fa-solid fa-paper-plane"></i>
                                                                            Continue Reservation
                                                                        </button>

                                                                    </div>

                                                                </form>

                                                            </div>

                                                        </dialog>

                                                    @endif

                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endforeach


                        {{-- =================================================
                             NO FILTER RESULT MESSAGE
                        ================================================== --}}

                        <div
                            id="no-filter-results"
                            class="no-results"
                            style="display:none;"
                        >
                            No rooms match the selected status.
                        </div>


                    @else

                        <div class="no-results">

                            <i class="fa-solid fa-door-open"></i>

                            <div style="margin-top:8px;">
                                No rooms found for the selected date and time.
                            </div>

                        </div>

                    @endif

                @else

                    <div class="no-results">

                        <i class="fa-regular fa-calendar"></i>

                        <div style="margin-top:8px;">
                            Select a date and time to check room availability.
                        </div>

                    </div>

                @endif

<style>
    /* =========================================================
       TEMPORARY RESERVATION SELECTION DIALOG
       ========================================================= */
    .reservation-dialog {
        width: min(680px, calc(100vw - 30px));
        max-width: 680px;
        max-height: 90vh;
        padding: 0;
        border: 0;
        border-radius: 16px;
        background: transparent;
        overflow: visible;
    }

    .reservation-dialog::backdrop {
        background: rgba(15, 23, 42, .58);
    }

    .reservation-dialog-card {
        background: #ffffff;
        border: 1px solid #dfe8e2;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,.20);
    }

    .reservation-dialog-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        padding: 20px 22px;
        border-bottom: 1px solid #e7ece9;
    }

    .reservation-dialog-header h3 {
        margin: 0;
        color: #176b3a;
        font-size: 19px;
        font-weight: 800;
    }

    .reservation-dialog-header p {
        margin: 5px 0 0;
        color: #66736c;
        font-size: 12px;
    }

    .reservation-dialog-close {
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 50%;
        background: #f1f5f3;
        color: #344054;
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
    }

    .reservation-dialog-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin: 16px 22px;
    }

    .reservation-dialog-summary > div {
        padding: 10px;
        border: 1px solid #d7eadc;
        border-radius: 9px;
        background: #f4faf6;
    }

    .reservation-dialog-summary span {
        display: block;
        color: #66736c;
        font-size: 9px;
        text-transform: uppercase;
        font-weight: 800;
    }

    .reservation-dialog-summary strong {
        display: block;
        margin-top: 3px;
        color: #176b3a;
        font-size: 11px;
    }

    .reservation-dialog-section-title {
        margin: 0 22px 9px;
        color: #344054;
        font-size: 13px;
        font-weight: 800;
    }

    .reservation-subject-list {
        max-height: 330px;
        overflow-y: auto;
        padding: 0 22px;
        display: grid;
        gap: 8px;
    }

    .reservation-subject-option {
        display: block;
        cursor: pointer;
    }

    .reservation-subject-option > input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .reservation-subject-card {
        display: block;
        padding: 12px;
        border: 1px solid #dfe7e2;
        border-radius: 10px;
        background: #ffffff;
        transition: .18s ease;
    }

    .reservation-subject-card:hover {
        border-color: #8fc5a1;
        background: #f8fcf9;
    }

    .reservation-subject-option > input:checked + .reservation-subject-card {
        border: 2px solid #198754;
        background: #effaf2;
        box-shadow: 0 0 0 2px rgba(25,135,84,.08);
    }

    .reservation-subject-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .reservation-subject-top strong {
        display: block;
        color: #176b3a;
        font-size: 13px;
        font-weight: 900;
    }

    .reservation-subject-top small {
        display: block;
        margin-top: 3px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .reservation-subject-top b {
        color: #66736c;
        font-size: 10px;
    }

    .reservation-subject-details {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }

    .reservation-subject-details span {
        padding: 4px 6px;
        border-radius: 6px;
        background: #f1f5f3;
        color: #475467;
        font-size: 9px;
        font-weight: 700;
    }

    .reservation-subject-note {
        display: block;
        margin-top: 7px;
        color: #66736c;
        font-size: 9px;
    }

    .reservation-no-subjects {
        padding: 18px;
        text-align: center;
        border: 1px dashed #d6dfda;
        border-radius: 10px;
        background: #fafcfb;
        color: #66736c;
        font-size: 12px;
        line-height: 1.6;
    }

    .reservation-dialog-footer {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding: 15px 22px 20px;
        margin-top: 15px;
        border-top: 1px solid #e7ece9;
    }

    .reservation-dialog-cancel,
    .reservation-dialog-submit {
        min-width: 130px;
        height: 40px;
        border-radius: 8px;
        padding: 0 15px;
        font-weight: 800;
        cursor: pointer;
    }

    .reservation-dialog-cancel {
        border: 1px solid #d5ded9;
        background: #ffffff;
        color: #475467;
    }

    .reservation-dialog-submit {
        border: 1px solid #198754;
        background: #198754;
        color: #ffffff;
    }

    .reservation-dialog-submit:disabled {
        border-color: #a8cdb4;
        background: #a8cdb4;
        cursor: not-allowed;
    }

    @media (max-width: 650px) {
        .reservation-dialog-summary {
            grid-template-columns: repeat(2, 1fr);
            margin-left: 15px;
            margin-right: 15px;
        }

        .reservation-dialog-header,
        .reservation-subject-list,
        .reservation-dialog-footer {
            padding-left: 15px;
            padding-right: 15px;
        }

        .reservation-dialog-section-title {
            margin-left: 15px;
            margin-right: 15px;
        }

        .reservation-dialog-footer {
            flex-direction: column-reverse;
        }

        .reservation-dialog-cancel,
        .reservation-dialog-submit {
            width: 100%;
        }
    }
</style>

