<div class="wrapper">
    @include('footerheader.header')

    <div class="main-layout">
        @include('admin.sidebar')

        <main class="content">
            @php
                $overrideScheduleData = $schedules->map(function ($schedule) {
                    return [
                        'id' => (int) $schedule->id,
                        'room_id' => (int) $schedule->room_id,
                        'day' => (string) ($schedule->day_of_week ?: $schedule->day),
                        'time' => (string) $schedule->time,
                        'subject' => (string) ($schedule->subject ?? ''),
                        'course_code' => (string) ($schedule->course_code ?? ''),
                        'section' => (string) ($schedule->section ?? ''),
                        'instructor' => (string) ($schedule->faculty?->name ?: $schedule->instructor ?: 'Faculty not assigned'),
                        'schedule_start_date' => $schedule->schedule_start_date?->format('Y-m-d'),
                        'schedule_expiration_date' => $schedule->schedule_expiration_date?->format('Y-m-d'),
                    ];
                })->values();

                $overrideStatusData = $overrides->map(function ($override) {
                    return [
                        'schedule_id' => (int) $override->schedule_id,
                        'date' => $override->override_date?->format('Y-m-d'),
                        'occupied_by' => $override->occupied_by,
                    ];
                })->values();
            @endphp

            <style>
                .so-page{--so-ink:#1d2b40;--so-muted:#5d6c80;--so-blue:#245b91;--so-blue-soft:#e9f1fa;--so-border:#d8e1ec;--so-surface:#f4f7fb;color:var(--so-ink);max-width:1440px;margin:0 auto;padding:24px 26px 42px;font-size:16px}
                .so-hero{display:flex;justify-content:space-between;align-items:center;gap:20px;margin:0 0 22px;padding:24px 28px;background:linear-gradient(135deg,#fff 0%,#edf4fc 100%);border:1px solid #dce6f1;border-radius:20px;box-shadow:8px 8px 20px #dce3ec,-7px -7px 18px #fff}
                .so-eyebrow{display:flex;align-items:center;gap:8px;margin:0 0 7px;color:var(--so-blue);font-size:13px;font-weight:800;letter-spacing:.09em;text-transform:uppercase}
                .so-hero h1{margin:0;color:#1d2b40;font-size:clamp(27px,3vw,36px);font-weight:850;line-height:1.18}
                .so-hero p{max-width:760px;margin:9px 0 0;color:#52647a;font-size:17px;line-height:1.55}
                .so-hero-icon{display:grid;place-items:center;flex:0 0 72px;width:72px;height:72px;border-radius:22px;background:#edf4fc;color:#245b91;font-size:30px;box-shadow:inset 4px 4px 9px #d7e1ec,inset -4px -4px 9px #fff}
                .so-alert{margin:0 0 16px;padding:14px 17px;border-radius:12px;font-size:15px;line-height:1.5}
                .so-alert.success{color:#14532d;background:#ecf8f0;border:1px solid #b9dfc6}.so-alert.error{color:#8b2734;background:#fff1f2;border:1px solid #f3c4ca}
                .so-panel{margin:0 0 20px;padding:22px 24px;background:#f4f7fb;border:1px solid #e2e8f0;border-radius:18px;box-shadow:7px 7px 16px #dce3ec,-6px -6px 15px #fff}
                .so-panel-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:16px}
                .so-panel h2{margin:0;color:#1f3148;font-size:21px;font-weight:850;line-height:1.3}
                .so-panel-intro{margin:6px 0 0;color:var(--so-muted);font-size:15px;line-height:1.55}
                .so-steps{display:flex;gap:8px;flex-wrap:wrap;margin:18px 0 20px;padding:0;list-style:none}
                .so-step{display:flex;align-items:center;gap:8px;padding:8px 13px;border-radius:999px;background:#e7edf5;color:#52647a;font-size:14px;font-weight:750}
                .so-step span{display:grid;place-items:center;width:23px;height:23px;border-radius:50%;background:#fff;color:#52647a;font-size:13px}
                .so-step.active{background:#e1edf9;color:#174a7c}.so-step.active span{background:#245b91;color:white}
                .so-level-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:7px 0 13px}
                .so-level-head h3{margin:0;color:#21354d;font-size:18px;font-weight:800}.so-level-head p{margin:4px 0 0;color:#5d6c80;font-size:14px}
                .so-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:13px}
                .so-card{position:relative;display:flex;flex-direction:column;align-items:flex-start;min-height:116px;width:100%;padding:17px 43px 15px 17px;text-align:left;border:1px solid #d6e0eb;border-radius:14px;background:#f7f9fc;color:#24354b;cursor:pointer;box-shadow:4px 4px 9px #dce3ec,-4px -4px 9px #fff;transition:transform .16s,border-color .16s,box-shadow .16s}
                .so-card:hover{transform:translateY(-2px);border-color:#9bb7d4;box-shadow:6px 7px 13px #d7e0e9,-5px -5px 12px #fff}.so-card:focus-visible,.so-button:focus-visible,.so-input:focus-visible{outline:3px solid #77a8d6;outline-offset:2px}
                .so-card.selected{border-color:#4c82b4;background:#edf4fc;box-shadow:inset 3px 3px 7px #dce5ef,inset -3px -3px 7px #fff}
                .so-card-icon{display:grid;place-items:center;width:38px;height:38px;margin-bottom:11px;border-radius:12px;background:#e7eff8;color:#245b91;font-size:19px}
                .so-card-title{font-size:16px;font-weight:850;line-height:1.35;overflow-wrap:anywhere}.so-card-sub{margin-top:5px;color:#5d6c80;font-size:14px;line-height:1.4}
                .so-arrow{position:absolute;right:15px;top:50%;transform:translateY(-50%);color:#4a78a4;font-size:27px}
                .so-room-card{min-height:100px}.so-room-card .so-card-icon{background:#e9f4ef;color:#2d7251}
                .so-back{border:1px solid #cbd6e3;border-radius:9px;padding:9px 13px;background:#f7f9fc;color:#29405a;font-size:14px;font-weight:800;cursor:pointer;box-shadow:3px 3px 7px #dce3ec,-3px -3px 7px #fff}
                .so-empty{grid-column:1/-1;padding:24px;border:1px dashed #b9c8d8;border-radius:12px;background:#f8fafd;color:#5b6b80;text-align:center;font-size:15px;line-height:1.5}
                .so-location-error{margin-top:13px;padding:12px 14px;border-radius:10px;background:#fff1f2;color:#8b2734;border:1px solid #f3c4ca;font-size:15px}
                .so-room-banner{display:flex;align-items:center;gap:12px;margin:2px 0 16px;padding:13px 15px;border-radius:12px;background:#eaf1f8;color:#294766;font-size:15px}
                .so-schedule-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:13px}
                .so-schedule-card{min-height:154px}.so-schedule-card .so-card-icon{background:#edf1fa;color:#4a5f98}
                .so-schedule-meta{display:flex;flex-wrap:wrap;gap:6px;margin-top:10px}.so-pill{display:inline-flex;align-items:center;padding:5px 8px;border-radius:999px;background:#e7edf5;color:#3e5066;font-size:13px;font-weight:750}
                .so-existing{margin-top:9px;color:#885b14;font-size:13px;font-weight:750}
                .so-form{margin-top:20px;padding:21px;border:1px solid #d5e0eb;border-radius:15px;background:#f7f9fc;box-shadow:inset 3px 3px 7px #e2e8ef,inset -3px -3px 7px #fff}
                .so-form h3{margin:0;color:#1f3148;font-size:20px;font-weight:850}.so-form-help{margin:5px 0 17px;color:#5d6c80;font-size:15px;line-height:1.5}
                .so-selected-summary{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:9px;margin-bottom:17px}
                .so-summary-item{padding:11px 12px;border-radius:10px;background:#eef3f8;border:1px solid #dce5ee}.so-summary-item small{display:block;margin-bottom:4px;color:#596b80;font-size:12px;font-weight:750;text-transform:uppercase;letter-spacing:.04em}.so-summary-item strong{color:#21354d;font-size:15px;line-height:1.4}
                .so-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px}.so-field{display:flex;flex-direction:column;gap:7px}.so-field.full{grid-column:1/-1}.so-field label{color:#263b54;font-size:15px;font-weight:800}.so-field-hint{color:#5d6c80;font-size:13px;line-height:1.45}
                .so-input{box-sizing:border-box;width:100%;min-height:48px;padding:10px 12px;border:1px solid #bdcad8;border-radius:9px;background:#fff;color:#20334b;font:inherit;font-size:16px}.so-input::placeholder{color:#718197}.so-input:focus{border-color:#4c82b4;box-shadow:0 0 0 3px #d7e7f7;outline:none}.so-input[aria-invalid="true"]{border-color:#b42332}
                textarea.so-input{min-height:95px;resize:vertical}.so-date-message{min-height:20px;color:#245b91;font-size:14px;font-weight:700}.so-date-message.error{color:#a32e3a}
                .so-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:18px}.so-button{min-height:47px;padding:10px 18px;border:0;border-radius:10px;font-size:16px;font-weight:850;cursor:pointer}.so-button.primary{background:#245b91;color:#fff;box-shadow:0 5px 12px #c7d5e4}.so-button.primary:hover{background:#1d4b78}.so-button.secondary{background:#e5ebf2;color:#31455d}.so-button:disabled{opacity:.55;cursor:not-allowed}
                .so-overrides{display:flex;flex-direction:column;gap:10px}.so-override-row{display:grid;grid-template-columns:minmax(170px,1.2fr) minmax(140px,1fr) minmax(150px,1fr) auto;gap:13px;align-items:center;padding:14px 15px;border:1px solid #d9e2ec;border-radius:12px;background:#f7f9fc}
                .so-override-row small{display:block;margin-bottom:3px;color:#5d6c80;font-size:12px}.so-override-row strong{font-size:15px;color:#20334b}.so-status{display:inline-flex;padding:5px 9px;border-radius:999px;background:#e9f4ef;color:#276344;font-size:13px;font-weight:850}.so-status.scheduled{background:#eaf1f9;color:#245b91}
                .so-cancel{border:1px solid #e5b9be;border-radius:8px;padding:8px 11px;background:#fff5f5;color:#8a2c36;font-size:14px;font-weight:800;cursor:pointer}.so-cancel:hover{background:#ffe8e9}
                .so-cancel-confirm{position:relative}.so-cancel-confirm summary{list-style:none}.so-cancel-confirm summary::-webkit-details-marker{display:none}.so-confirm-inline{display:flex;align-items:center;gap:9px;flex-wrap:wrap;margin-top:8px;padding:10px;border:1px solid #e0c7c9;border-radius:9px;background:#fff7f7;color:#572b30;font-size:14px}.so-confirm-inline button{border:0;border-radius:7px;padding:7px 10px;background:#8f3440;color:#fff;font-size:13px;font-weight:800;cursor:pointer}
                .so-spinner{width:18px;height:18px;border:2px solid #d6e2ef;border-top-color:#245b91;border-radius:50%;animation:so-spin .75s linear infinite}@keyframes so-spin{to{transform:rotate(360deg)}}
                [hidden]{display:none!important}
                @media(max-width:900px){.so-selected-summary{grid-template-columns:repeat(2,minmax(0,1fr))}.so-override-row{grid-template-columns:1fr 1fr}.so-override-row form{grid-column:1/-1}}
                @media(max-width:650px){.so-page{padding:15px 12px 30px}.so-hero{padding:19px;border-radius:15px}.so-hero-icon{display:none}.so-panel{padding:17px 15px}.so-panel-head{display:block}.so-form-grid{grid-template-columns:1fr}.so-field.full{grid-column:auto}.so-selected-summary{grid-template-columns:1fr 1fr}.so-override-row{grid-template-columns:1fr}.so-override-row form{grid-column:auto}.so-actions{flex-direction:column-reverse}.so-button{width:100%}.so-steps{gap:6px}.so-step{font-size:13px;padding:7px 9px}}
            </style>

            <div class="so-page">
                <section class="so-hero" aria-labelledby="soPageTitle">
                    <div>
                        <p class="so-eyebrow"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Schedule management</p>
                        <h1 id="soPageTitle">Schedule Overrides</h1>
                        <p>Temporarily let another person use a scheduled class period. Choose the schedule, enter who will occupy it, and select a matching date. The original schedule returns automatically after that day.</p>
                    </div>
                    <div class="so-hero-icon" aria-hidden="true"><i class="fa-solid fa-calendar-day"></i></div>
                </section>

                @if(session('success'))
                    <div class="so-alert success" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> {{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="so-alert error" role="alert">
                        <strong>Please review the following:</strong>
                        <ul style="margin:7px 0 0;padding-left:22px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <section class="so-panel" aria-labelledby="soChooseTitle">
                    <div class="so-panel-head">
                        <div>
                            <h2 id="soChooseTitle">Choose a schedule to override</h2>
                            <p class="so-panel-intro">Follow the steps below. Select a department, building, room, then the exact schedule you want to temporarily occupy.</p>
                        </div>
                        @if($activeTerm)
                            <span class="so-pill"><i class="fa-solid fa-graduation-cap" aria-hidden="true" style="margin-right:7px"></i>{{ $activeTerm->academic_year }} · {{ $activeTerm->semester }}</span>
                        @endif
                    </div>

                    <ol class="so-steps" id="soSteps" aria-label="Selection steps">
                        <li class="so-step active"><span>1</span> Department</li>
                        <li class="so-step"><span>2</span> Building</li>
                        <li class="so-step"><span>3</span> Room</li>
                        <li class="so-step"><span>4</span> Schedule</li>
                    </ol>

                    <section id="soDepartmentLevel">
                        <div class="so-level-head"><div><h3>1. Select a department</h3><p>Choose the department that owns the room.</p></div></div>
                        <div class="so-grid" id="soDepartmentCards">
                            @forelse($departments as $department)
                                <button type="button" class="so-card" data-department-id="{{ $department->id }}" data-department-name="{{ $department->name }}">
                                    <span class="so-card-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                                    <span class="so-card-title">{{ $department->name }}</span>
                                    <span class="so-card-sub">Select to see its buildings</span><span class="so-arrow" aria-hidden="true">›</span>
                                </button>
                            @empty
                                <div class="so-empty">No departments are available yet.</div>
                            @endforelse
                        </div>
                    </section>

                    <section id="soBuildingLevel" hidden>
                        <div class="so-level-head"><div><h3 id="soBuildingHeading">2. Select a building</h3><p>Choose the building that contains the room.</p></div><button type="button" class="so-back" id="soBackDepartments">← Departments</button></div>
                        <div class="so-grid" id="soBuildingCards"></div>
                    </section>

                    <section id="soRoomLevel" hidden>
                        <div class="so-level-head"><div><h3 id="soRoomHeading">3. Select a room</h3><p>Then choose a scheduled class period in that room.</p></div><button type="button" class="so-back" id="soBackBuildings">← Buildings</button></div>
                        <div class="so-grid" id="soRoomCards"></div>
                    </section>

                    <div id="soLoading" class="so-room-banner" hidden><span class="so-spinner" aria-hidden="true"></span><span>Loading locations…</span></div>
                    <div id="soLocationError" class="so-location-error" role="alert" hidden></div>

                    <section id="soSchedulePanel" hidden style="margin-top:20px">
                        <div class="so-level-head"><div><h3 id="soScheduleHeading">4. Select a schedule</h3><p>Select the class period that will be occupied temporarily.</p></div></div>
                        <div class="so-room-banner"><i class="fa-solid fa-door-open" aria-hidden="true"></i><span>Room: <strong id="soSelectedRoomName"></strong> <span id="soSelectedRoomPath"></span></span></div>
                        <div class="so-schedule-list" id="soScheduleCards"></div>

                        <form class="so-form" id="soOverrideForm" method="POST" action="{{ route('admin.roomreassignment.store') }}" hidden>
                            @csrf
                            <input type="hidden" name="schedule_id" id="soScheduleId">
                            <input type="hidden" name="reason" id="soReasonValue">
                            <h3>Set the temporary override</h3>
                            <p class="so-form-help">The faculty member will receive a notification. The published schedule is preserved and returns after the selected day ends.</p>
                            <div class="so-selected-summary" aria-live="polite">
                                <div class="so-summary-item"><small>Class</small><strong id="soSummaryClass">—</strong></div>
                                <div class="so-summary-item"><small>Faculty</small><strong id="soSummaryFaculty">—</strong></div>
                                <div class="so-summary-item"><small>Schedule day</small><strong id="soSummaryDay">—</strong></div>
                                <div class="so-summary-item"><small>Room and time</small><strong id="soSummaryRoomTime">—</strong></div>
                            </div>
                            <div class="so-form-grid">
                                <div class="so-field">
                                    <label for="soReasonSelect">Reason for the override</label>
                                    <select class="so-input" id="soReasonSelect" required>
                                        <option value="">Choose a reason</option>
                                        <option value="Administrative meeting">Administrative meeting</option>
                                        <option value="Official campus activity">Official campus activity</option>
                                        <option value="Maintenance or inspection">Maintenance or inspection</option>
                                        <option value="Emergency use">Emergency use</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="so-field">
                                    <label for="soOccupiedBy">Who will occupy this schedule?</label>
                                    <input class="so-input" type="text" name="occupied_by" id="soOccupiedBy" maxlength="255" placeholder="Enter a person, office, or group" required>
                                </div>
                                <div class="so-field" id="soOtherReasonField" hidden>
                                    <label for="soOtherReason">Enter the reason</label>
                                    <input class="so-input" type="text" id="soOtherReason" maxlength="255" placeholder="Describe the reason">
                                </div>
                                <div class="so-field">
                                    <label for="soOverrideDate">Date of override</label>
                                    <input class="so-input" type="date" name="override_date" id="soOverrideDate" min="{{ now()->toDateString() }}" required>
                                    <span class="so-field-hint" id="soDateHint">Choose today or a future date that matches the schedule’s weekday. The override ends at 11:59 PM.</span>
                                    <span class="so-date-message" id="soDateMessage" aria-live="polite"></span>
                                </div>
                                <div class="so-field full">
                                    <label for="soDetails">Additional notes <span style="font-weight:500;color:#66778b">(optional)</span></label>
                                    <textarea class="so-input" name="details" id="soDetails" maxlength="2000" placeholder="Add helpful details for the faculty member"></textarea>
                                </div>
                            </div>
                            <div class="so-actions">
                                <button type="button" class="so-button secondary" id="soClearSelection">Choose another schedule</button>
                                <button type="submit" class="so-button primary" id="soSubmit"><i class="fa-solid fa-bell" aria-hidden="true" style="margin-right:7px"></i>Confirm override and notify faculty</button>
                            </div>
                        </form>
                    </section>
                </section>

                <section class="so-panel" aria-labelledby="soActiveTitle">
                    <div class="so-panel-head">
                        <div><h2 id="soActiveTitle">Upcoming and current overrides</h2><p class="so-panel-intro">Overrides apply only to the listed day and return to the regular schedule automatically afterward.</p></div>
                    </div>
                    <div class="so-overrides">
                        @forelse($overrides as $override)
                            @php($overrideDate = $override->override_date)
                            <div class="so-override-row">
                                <div><small>Schedule</small><strong>{{ $override->schedule?->subject ?: ($override->schedule?->course_code ?: 'Class schedule') }} · {{ $override->schedule?->room?->room_name ?: 'Room' }}</strong><div class="so-card-sub">{{ $override->schedule?->day }} · {{ $override->schedule?->time }}</div></div>
                                <div><small>Date and occupant</small><strong>{{ $overrideDate?->format('M j, Y') }}</strong><div class="so-card-sub">{{ $override->occupied_by }}</div></div>
                                <div><small>Reason / faculty</small><strong>{{ $override->reason }}</strong><div class="so-card-sub">Faculty: {{ $override->faculty?->name ?: 'Unavailable' }}</div></div>
                                <div style="display:flex;align-items:center;gap:10px;justify-content:flex-end">
                                    <span class="so-status {{ $overrideDate?->isFuture() ? 'scheduled' : '' }}">{{ $overrideDate?->isToday() ? 'In effect today' : 'Scheduled' }}</span>
                                    <form method="POST" action="{{ route('admin.roomreassignment.cancel', $override) }}">
                                        @csrf @method('DELETE')
                                        <details class="so-cancel-confirm">
                                            <summary class="so-cancel" aria-label="Cancel override for {{ $overrideDate?->format('F j, Y') }}">Cancel</summary>
                                            <div class="so-confirm-inline"><span>End this override now?</span><button type="submit">Confirm cancellation</button></div>
                                        </details>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="so-empty">There are no current or upcoming schedule overrides.</div>
                        @endforelse
                    </div>
                </section>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const scheduleItems = @json($overrideScheduleData);
                    const existingOverrides = @json($overrideStatusData);
                    const buildingsUrlTemplate = @json(route('admin.roomreassignment.buildings', ['department' => '__DEPARTMENT__']));
                    const roomsUrlTemplate = @json(route('admin.roomreassignment.rooms', ['building' => '__BUILDING__']));
                    const csrfToken = @json(csrf_token());

                    const departmentLevel = document.getElementById('soDepartmentLevel');
                    const buildingLevel = document.getElementById('soBuildingLevel');
                    const roomLevel = document.getElementById('soRoomLevel');
                    const schedulePanel = document.getElementById('soSchedulePanel');
                    const departmentCards = document.getElementById('soDepartmentCards');
                    const buildingCards = document.getElementById('soBuildingCards');
                    const roomCards = document.getElementById('soRoomCards');
                    const scheduleCards = document.getElementById('soScheduleCards');
                    const loading = document.getElementById('soLoading');
                    const locationError = document.getElementById('soLocationError');
                    const steps = Array.from(document.querySelectorAll('#soSteps .so-step'));
                    const form = document.getElementById('soOverrideForm');
                    const dateInput = document.getElementById('soOverrideDate');
                    const dateMessage = document.getElementById('soDateMessage');
                    const reasonSelect = document.getElementById('soReasonSelect');
                    const otherReasonField = document.getElementById('soOtherReasonField');
                    const otherReasonInput = document.getElementById('soOtherReason');
                    const reasonValue = document.getElementById('soReasonValue');
                    let selectedRoom = null;
                    let selectedSchedule = null;

                    const dayAliases = {
                        MON: 'Mon', MONDAY: 'Mon', TUE: 'Tue', TUES: 'Tue', TUESDAY: 'Tue',
                        WED: 'Wed', WEDS: 'Wed', WEDNESDAY: 'Wed', THU: 'Thu', THURS: 'Thu', THURSDAY: 'Thu',
                        FRI: 'Fri', FRIDAY: 'Fri', SAT: 'Sat', SATURDAY: 'Sat', SUN: 'Sun', SUNDAY: 'Sun'
                    };
                    const dayNumbers = { Sun: 0, Mon: 1, Tue: 2, Wed: 3, Thu: 4, Fri: 5, Sat: 6 };

                    function setStep(step) {
                        steps.forEach((item, index) => item.classList.toggle('active', index + 1 === step));
                    }
                    function clearError() { locationError.hidden = true; locationError.textContent = ''; }
                    function showError(message) { locationError.hidden = false; locationError.textContent = message; }
                    function clearSchedule() {
                        schedulePanel.hidden = true;
                        scheduleCards.replaceChildren();
                        form.hidden = true;
                        selectedRoom = null;
                        selectedSchedule = null;
                    }
                    function makeCard(title, subtitle, icon, handler, className = '') {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = `so-card ${className}`.trim();
                        const iconSpan = document.createElement('span');
                        iconSpan.className = 'so-card-icon';
                        iconSpan.innerHTML = `<i class="${icon}" aria-hidden="true"></i>`;
                        const titleSpan = document.createElement('span');
                        titleSpan.className = 'so-card-title';
                        titleSpan.textContent = title;
                        const subtitleSpan = document.createElement('span');
                        subtitleSpan.className = 'so-card-sub';
                        subtitleSpan.textContent = subtitle;
                        const arrow = document.createElement('span');
                        arrow.className = 'so-arrow';
                        arrow.textContent = '›';
                        button.append(iconSpan, titleSpan, subtitleSpan, arrow);
                        button.addEventListener('click', handler);
                        return button;
                    }
                    async function getJson(url) {
                        const response = await fetch(url, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'same-origin'
                        });
                        if (!response.ok) throw new Error('Could not load this list. Please try again.');
                        return response.json();
                    }
                    function showLoading(show) { loading.hidden = !show; }
                    async function openDepartment(id, name) {
                        clearError();
                        clearSchedule();
                        buildingCards.replaceChildren();
                        roomCards.replaceChildren();
                        document.getElementById('soBuildingHeading').textContent = `2. Select a building in ${name}`;
                        departmentLevel.hidden = true;
                        buildingLevel.hidden = false;
                        roomLevel.hidden = true;
                        setStep(2);
                        showLoading(true);
                        try {
                            const buildings = await getJson(buildingsUrlTemplate.replace('__DEPARTMENT__', encodeURIComponent(id)));
                            if (!buildings.length) buildingCards.innerHTML = '<div class="so-empty">This department has no buildings yet.</div>';
                            buildings.forEach(building => buildingCards.appendChild(makeCard(
                                building.name,
                                `${building.rooms_count} room${Number(building.rooms_count) === 1 ? '' : 's'} · Select to continue`,
                                'fa-solid fa-building',
                                () => openBuilding(building.id, building.name)
                            )));
                        } catch (error) { showError(error.message); }
                        finally { showLoading(false); }
                    }
                    async function openBuilding(id, name) {
                        clearError();
                        clearSchedule();
                        roomCards.replaceChildren();
                        document.getElementById('soRoomHeading').textContent = `3. Select a room in ${name}`;
                        buildingLevel.hidden = true;
                        roomLevel.hidden = false;
                        setStep(3);
                        showLoading(true);
                        try {
                            const rooms = await getJson(roomsUrlTemplate.replace('__BUILDING__', encodeURIComponent(id)));
                            if (!rooms.length) roomCards.innerHTML = '<div class="so-empty">This building has no rooms yet.</div>';
                            rooms.forEach(room => roomCards.appendChild(makeCard(
                                room.name,
                                `${room.building_name || name} · View schedules`,
                                'fa-solid fa-door-open',
                                () => openRoom(room, name),
                                'so-room-card'
                            )));
                        } catch (error) { showError(error.message); }
                        finally { showLoading(false); }
                    }
                    function openRoom(room, buildingName) {
                        clearError();
                        selectedRoom = room;
                        selectedSchedule = null;
                        form.hidden = true;
                        roomCards.querySelectorAll('.so-card').forEach(card => card.classList.remove('selected'));
                        const clickedCard = Array.from(roomCards.querySelectorAll('.so-card')).find(card => card.querySelector('.so-card-title')?.textContent === room.name);
                        clickedCard?.classList.add('selected');
                        schedulePanel.hidden = false;
                        document.getElementById('soSelectedRoomName').textContent = room.name;
                        document.getElementById('soSelectedRoomPath').textContent = `· ${buildingName}`;
                        const roomSchedules = scheduleItems.filter(item => Number(item.room_id) === Number(room.id));
                        scheduleCards.replaceChildren();
                        if (!roomSchedules.length) {
                            scheduleCards.innerHTML = '<div class="so-empty">This room has no published schedules in the selected academic term.</div>';
                        }
                        roomSchedules.forEach(schedule => {
                            const card = makeCard(
                                schedule.course_code || schedule.subject || 'Scheduled class',
                                schedule.subject && schedule.course_code ? schedule.subject : (schedule.instructor || 'Faculty not assigned'),
                                'fa-solid fa-book-open',
                                () => selectSchedule(schedule),
                                'so-schedule-card'
                            );
                            const meta = document.createElement('span');
                            meta.className = 'so-schedule-meta';
                            [schedule.day, schedule.time, schedule.section].filter(Boolean).forEach(value => {
                                const pill = document.createElement('span');
                                pill.className = 'so-pill';
                                pill.textContent = value;
                                meta.appendChild(pill);
                            });
                            card.appendChild(meta);
                            const upcoming = existingOverrides.filter(item => Number(item.schedule_id) === Number(schedule.id));
                            if (upcoming.length) {
                                const note = document.createElement('span');
                                note.className = 'so-existing';
                                note.textContent = upcoming.map(item => `${formatDate(item.date)} · ${item.occupied_by}`).join('  |  ');
                                card.appendChild(note);
                            }
                            scheduleCards.appendChild(card);
                        });
                        setStep(4);
                    }
                    function canonicalDay(value) {
                        const key = String(value || '').trim().toUpperCase();
                        return dayAliases[key] || key.slice(0, 3);
                    }
                    function localDateString(date) {
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`;
                    }
                    function formatDate(value) {
                        if (!value) return 'Date unavailable';
                        const [year, month, day] = value.split('-').map(Number);
                        return new Date(year, month - 1, day).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
                    }
                    function nextScheduleDate(schedule) {
                        const minimum = new Date();
                        minimum.setHours(0, 0, 0, 0);
                        const startDate = schedule.schedule_start_date ? new Date(`${schedule.schedule_start_date}T00:00:00`) : minimum;
                        let candidate = new Date(Math.max(minimum.getTime(), startDate.getTime()));
                        const expected = dayNumbers[canonicalDay(schedule.day)];
                        if (expected === undefined) return '';
                        while (candidate.getDay() !== expected) candidate.setDate(candidate.getDate() + 1);
                        if (schedule.schedule_expiration_date && localDateString(candidate) > schedule.schedule_expiration_date) return '';
                        return localDateString(candidate);
                    }
                    function validateDate(showMessage = true) {
                        if (!selectedSchedule || !dateInput.value) {
                            dateInput.setAttribute('aria-invalid', 'true');
                            dateMessage.textContent = 'Choose a date for this override.';
                            dateMessage.classList.add('error');
                            return false;
                        }
                        const date = new Date(`${dateInput.value}T00:00:00`);
                        const expected = canonicalDay(selectedSchedule.day);
                        const actual = date.toLocaleDateString('en-US', { weekday: 'short' });
                        const start = selectedSchedule.schedule_start_date;
                        const end = selectedSchedule.schedule_expiration_date;
                        const alreadyOverridden = existingOverrides.some(item =>
                            Number(item.schedule_id) === Number(selectedSchedule.id) && item.date === dateInput.value
                        );
                        let message = '';
                        if (actual !== expected) message = `Choose a ${expected} date to match this schedule.`;
                        else if (start && dateInput.value < start) message = `This schedule begins on ${formatDate(start)}.`;
                        else if (end && dateInput.value > end) message = `This schedule ends on ${formatDate(end)}.`;
                        else if (alreadyOverridden) message = 'This schedule already has an override on that date. Choose another matching day.';
                        else message = `Date matches the ${expected} schedule. It will return to normal after this day.`;
                        const valid = message.startsWith('Date matches');
                        dateInput.setAttribute('aria-invalid', valid ? 'false' : 'true');
                        dateMessage.textContent = message;
                        dateMessage.classList.toggle('error', !valid);
                        return valid;
                    }
                    function selectSchedule(schedule) {
                        selectedSchedule = schedule;
                        document.getElementById('soOccupiedBy').value = '';
                        document.getElementById('soDetails').value = '';
                        reasonSelect.value = '';
                        reasonValue.value = '';
                        otherReasonInput.value = '';
                        otherReasonInput.required = false;
                        otherReasonField.hidden = true;
                        document.getElementById('soScheduleId').value = schedule.id;
                        document.getElementById('soSummaryClass').textContent = [schedule.course_code, schedule.subject].filter(Boolean).join(' · ') || 'Scheduled class';
                        document.getElementById('soSummaryFaculty').textContent = schedule.instructor || 'Faculty not assigned';
                        document.getElementById('soSummaryDay').textContent = schedule.day || 'Day unavailable';
                        document.getElementById('soSummaryRoomTime').textContent = `${selectedRoom.name} · ${schedule.time || 'Time unavailable'}`;
                        const today = @json(now()->toDateString());
                        dateInput.min = schedule.schedule_start_date && schedule.schedule_start_date > today
                            ? schedule.schedule_start_date
                            : today;
                        dateInput.max = schedule.schedule_expiration_date || '';
                        dateInput.value = nextScheduleDate(schedule);
                        document.getElementById('soDateHint').textContent = `Choose today or a future ${canonicalDay(schedule.day)}. The override ends at 11:59 PM.`;
                        form.hidden = false;
                        scheduleCards.querySelectorAll('.so-schedule-card').forEach(card => card.classList.remove('selected'));
                        const clicked = Array.from(scheduleCards.querySelectorAll('.so-schedule-card')).find(card => card.querySelector('.so-card-title')?.textContent === (schedule.course_code || schedule.subject || 'Scheduled class'));
                        clicked?.classList.add('selected');
                        validateDate();
                        form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }

                    departmentCards.querySelectorAll('[data-department-id]').forEach(card => {
                        card.addEventListener('click', () => openDepartment(card.dataset.departmentId, card.dataset.departmentName));
                    });
                    document.getElementById('soBackDepartments').addEventListener('click', () => {
                        clearError(); clearSchedule(); buildingLevel.hidden = true; roomLevel.hidden = true; departmentLevel.hidden = false; setStep(1);
                    });
                    document.getElementById('soBackBuildings').addEventListener('click', () => {
                        clearError(); clearSchedule(); roomLevel.hidden = true; buildingLevel.hidden = false; setStep(2);
                    });
                    document.getElementById('soClearSelection').addEventListener('click', () => {
                        selectedSchedule = null; form.hidden = true; scheduleCards.querySelectorAll('.so-schedule-card').forEach(card => card.classList.remove('selected'));
                    });
                    dateInput.addEventListener('change', () => validateDate());
                    dateInput.addEventListener('input', () => validateDate());
                    reasonSelect.addEventListener('change', () => {
                        const isOther = reasonSelect.value === 'Other';
                        otherReasonField.hidden = !isOther;
                        otherReasonInput.required = isOther;
                        if (!isOther) otherReasonInput.value = '';
                    });
                    form.addEventListener('submit', function (event) {
                        if (!validateDate()) { event.preventDefault(); dateInput.focus(); return; }
                        if (reasonSelect.value === 'Other') {
                            const customReason = otherReasonInput.value.trim();
                            if (!customReason) { event.preventDefault(); otherReasonInput.focus(); return; }
                            reasonValue.value = customReason;
                        } else {
                            reasonValue.value = reasonSelect.value;
                        }
                        if (!reasonValue.value) { event.preventDefault(); reasonSelect.focus(); return; }
                        document.getElementById('soSubmit').disabled = true;
                    });
                });
            </script>
        </main>
    </div>
</div>
