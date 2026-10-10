<style>
.time-only-results{display:flex;flex-direction:column;gap:18px}
.time-only-summary{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:14px 16px;border:1px solid #e1e7e3;border-radius:12px;background:#fff}
.time-only-summary strong{color:#173b27}.time-only-summary span{font-size:12px;color:#667085}
.department-section{border:1px solid #e1e7e3;border-radius:14px;background:#fff;overflow:hidden}
.department-title{display:flex;align-items:center;gap:9px;padding:13px 16px;background:#f3f8f5;border-bottom:1px solid #e1e7e3;font-weight:800;color:#176b3a}
.building{border-bottom:1px solid #edf0ee}.building:last-child{border-bottom:0}
.building-title{display:flex;align-items:center;gap:8px;padding:12px 16px;background:#fafcfb;color:#344054;font-weight:800;font-size:14px;border-bottom:1px solid #edf0ee}.building-title i{color:#198754}
.rooms{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px;padding:16px}
.room-card{border:1px solid #e1e7e3;border-radius:12px;padding:16px;background:#fff;box-shadow:0 2px 7px rgba(0,0,0,.04);min-width:0}
.room-name{font-size:17px;font-weight:800;color:#172b1e}.department-name{font-size:11px;color:#7a8580;margin-top:3px}
.room-time{display:inline-flex;margin-top:10px;padding:6px 9px;border-radius:8px;background:#eef8f1;color:#176b3a;font-size:11px;font-weight:800}
.room-status{margin-top:14px;padding:12px;border-radius:10px;border:1px solid #e1e7e3;background:#fafcfb}
.room-status-label{font-size:10px;text-transform:uppercase;letter-spacing:.04em;font-weight:800;color:#667085}.room-status-value{margin-top:5px;font-size:15px;font-weight:900}
.room-status.available{background:#f3fbf6;border-color:#b9dfc7}.room-status.available .room-status-value{color:#176b3a}
.room-status.occupied{background:#fff5f5;border-color:#f3c2c2}.room-status.occupied .room-status-value{color:#b42318}
.room-status.reserved{background:#fff8ef;border-color:#f1d29e}.room-status.reserved .room-status-value{color:#b54708}
.room-status-help{margin-top:5px;font-size:10px;color:#667085;line-height:1.35}
.room-features{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:13px}.feature{background:#f7f9f8;border:1px solid #e9eeeb;border-radius:7px;padding:8px;min-width:0}.feature-label{display:block;font-size:9px;text-transform:uppercase;letter-spacing:.03em;color:#7a8580;margin-bottom:2px}.feature-value{display:block;font-size:11px;font-weight:700;color:#344054;overflow-wrap:anywhere}
.room-purpose,.room-description{margin-top:9px;font-size:11px;color:#66736c;line-height:1.45}
.reserve-button{width:100%;height:40px;margin-top:15px;border:0;border-radius:8px;background:#198754;color:#fff;font-size:13px;font-weight:800;cursor:pointer;transition:.2s}.reserve-button:hover{background:#157347}
.reserve-button:disabled{background:#c8cfcb;cursor:not-allowed}
.no-results{padding:30px;background:#fff;border:1px solid #e1e7e3;border-radius:12px;color:#777;text-align:center}
.week-reserve-modal{display:none;position:fixed;inset:0;z-index:99999;background:rgba(16,24,40,.48);align-items:center;justify-content:center;padding:20px;box-sizing:border-box}.week-reserve-modal.show{display:flex}
.week-reserve-dialog{width:min(480px,100%);max-height:90vh;overflow:auto;background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.22)}
.week-reserve-header{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 18px;background:#f4faf6;border-bottom:1px solid #e4e9e6}.week-reserve-title{font-size:15px;font-weight:800;color:#176b3a}.week-reserve-close{border:0;background:transparent;font-size:24px;color:#667085;cursor:pointer}.week-reserve-body{padding:18px}
.week-reserve-summary{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px}.week-reserve-summary-item{padding:10px;border:1px solid #e4e9e6;border-radius:9px;background:#f8faf9}.week-reserve-label{font-size:9px;text-transform:uppercase;color:#667085;font-weight:700}.week-reserve-value{margin-top:3px;font-size:12px;color:#1f2937;font-weight:800}
.week-reserve-field{margin-top:12px}.week-reserve-field label{display:block;margin-bottom:6px;font-size:12px;font-weight:800;color:#344054}.week-reserve-field select{width:100%;padding:10px 12px;border:1px solid #d0d5dd;border-radius:9px;background:#fff;font-size:13px;color:#1f2937;box-sizing:border-box}.week-reserve-help{font-size:10px;color:#667085;margin-top:6px;line-height:1.35}.week-reserve-actions{display:flex;justify-content:flex-end;gap:8px;margin-top:18px}.week-reserve-cancel,.week-reserve-submit{border:0;border-radius:9px;padding:10px 15px;font-weight:800;cursor:pointer}.week-reserve-cancel{background:#eef2f0;color:#344054}.week-reserve-submit{background:#17894f;color:#fff}.week-reserve-submit:disabled{background:#b9c1bc;cursor:not-allowed}
body.week-reservation-open{overflow:hidden}
@media(max-width:950px){.rooms{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:650px){.rooms{grid-template-columns:1fr}.week-reserve-summary{grid-template-columns:1fr}}
</style>

@if(request('day') && request('time_slot_id') && count($results) > 0)

    @php
        $selectedDayCode = strtoupper((string) ($selectedDay ?? request('day')));
        $dayNames = [
            'MON' => 'Monday',
            'TUE' => 'Tuesday',
            'WED' => 'Wednesday',
            'THU' => 'Thursday',
            'FRI' => 'Friday',
            'SAT' => 'Saturday',
        ];
        $selectedDayName = $dayNames[$selectedDayCode] ?? $selectedDayCode;
        $searchedTime = $results[0]['time'] ?? 'Selected Time';
        $availableRooms = collect($results)->where('status', 'Available')->count();
    @endphp

    <div class="time-only-results">
        <div class="time-only-summary">
            <div>
                <strong>Room availability for {{ $selectedDayName }} • {{ $searchedTime }}</strong>
                <span>Availability is checked against the active academic term and date-specific reservations.</span>
            </div>
            <span>{{ $availableRooms }} room(s) available</span>
        </div>

        @php
            $organizedResults = collect($results)->groupBy(function($item) {
                return $item['room']->department_id;
            });
        @endphp

        @foreach($organizedResults as $departmentRooms)
            @php
                $department = $departmentRooms->first()['room']->department;
                $organizedBuildings = $departmentRooms->groupBy(function($item) {
                    return $item['room']->building_id;
                });
            @endphp

            <div class="department-section">
                <div class="department-title"><i class="fa-solid fa-building-columns"></i>{{ $department->name ?? 'Department' }}</div>

                @foreach($organizedBuildings as $buildingRooms)
                    @php
                        $firstRoom = $buildingRooms->first()['room'];
                        $buildingName = $firstRoom->building?->name ?? $firstRoom->building?->building_name ?? 'Building';
                    @endphp

                    <div class="building">
                        <div class="building-title"><i class="fa-solid fa-building"></i>{{ $buildingName }}</div>

                        <div class="rooms">
                            @foreach($buildingRooms as $item)
                                @php
                                    $room = $item['room'];
                                    $status = $item['status'] ?? 'Available';
                                    $statusClass = strtolower($status);
                                    $roomType = strtolower(trim((string) $room->purpose));
                                    $roomType = in_array($roomType, ['lab','laboratory'], true)
                                        ? 'Laboratory'
                                        : (in_array($roomType, ['lec','lecture'], true) ? 'Lecture Room' : ($room->purpose ?: 'Not specified'));
                                @endphp

                                <div class="room-card">
                                    <div class="room-name">{{ $room->room_name }}</div>
                                    <div class="department-name">{{ $department->name ?? 'Department' }}</div>
                                    <div class="room-time"><i class="fa-regular fa-clock"></i>&nbsp;{{ $item['time'] ?? $searchedTime }}</div>

                                    <div class="room-status {{ $statusClass }}">
                                        <div class="room-status-label">{{ $selectedDayName }} room status</div>
                                        <div class="room-status-value">
                                            @if($status === 'Available') 🟢 Available
                                            @elseif($status === 'Occupied') 🔴 Occupied
                                            @elseif($status === 'Reserved') 🟠 Reserved
                                            @else {{ $status }}
                                            @endif
                                        </div>
                                        <div class="room-status-help">
                                            @if($status === 'Available')
                                                This room is free for the selected recurring time. Choose a future calendar date below to reserve it.
                                            @elseif($status === 'Occupied')
                                                A regular class is already scheduled in this room during this time.
                                            @else
                                                This room already has an active reservation for the selected calendar date.
                                            @endif
                                        </div>
                                    </div>

                                    <div class="room-features">
                                        <div class="feature"><span class="feature-label">Capacity</span><span class="feature-value">{{ $room->capacity ?? 0 }} students</span></div>
                                        <div class="feature"><span class="feature-label">Room Type</span><span class="feature-value">{{ $roomType }}</span></div>
                                        <div class="feature"><span class="feature-label">Computers</span><span class="feature-value">{{ $room->computers ?? 0 }}</span></div>
                                        <div class="feature"><span class="feature-label">TV</span><span class="feature-value">{{ $room->has_tv ? 'Available' : 'Not Available' }}</span></div>
                                        <div class="feature"><span class="feature-label">Projector</span><span class="feature-value">{{ $room->has_projector ? 'Available' : 'Not Available' }}</span></div>
                                        <div class="feature"><span class="feature-label">Other Departments</span><span class="feature-value">{{ $room->allow_other_departments ? 'Allowed' : 'Not Allowed' }}</span></div>
                                    </div>

                                    @if($room->purpose)
                                        <div class="room-purpose"><strong>Purpose:</strong> {{ $room->purpose }}</div>
                                    @endif
                                    @if($room->description)
                                        <div class="room-description">{{ $room->description }}</div>
                                    @endif

                                    @if($status === 'Available')
                                        <button
                                            type="button"
                                            class="reserve-button"
                                            onclick="openWeeklyReservation(this)"
                                            data-room-id="{{ $room->id }}"
                                            data-room-name="{{ e($room->room_name) }}"
                                            data-building="{{ e($buildingName) }}"
                                            data-day="{{ $selectedDayCode }}"
                                            data-day-name="{{ $selectedDayName }}"
                                            data-time="{{ $item['time'] ?? $searchedTime }}"
                                            data-start-time="{{ \Carbon\Carbon::parse($timeSlots->firstWhere('id', (int)($item['time_slot_id'] ?? request('time_slot_id')))?->start_time ?? '00:00')->format('H:i') }}"
                                            data-end-time="{{ \Carbon\Carbon::parse($timeSlots->firstWhere('id', (int)($item['time_slot_id'] ?? request('time_slot_id')))?->end_time ?? '00:00')->format('H:i') }}"
                                            data-time-slot-id="{{ $item['time_slot_id'] ?? request('time_slot_id') }}"
                                        >
                                            <i class="fa-solid fa-calendar-plus"></i> Reserve This Room
                                        </button>
                                    @endif

                                    <div class="week-reserve-modal" id="weeklyReserveModal-{{ $room->id }}" aria-hidden="true">
                                        <div class="week-reserve-dialog" role="dialog" aria-modal="true" aria-labelledby="weeklyReserveTitle-{{ $room->id }}">
                                            <div class="week-reserve-header">
                                                <div>
                                                    <div class="week-reserve-title" id="weeklyReserveTitle-{{ $room->id }}">Reserve Room</div>
                                                    <div class="week-reserve-help">{{ $room->room_name }} • {{ $buildingName }}</div>
                                                </div>
                                                <button type="button" class="week-reserve-close" onclick="closeWeeklyReservation('{{ $room->id }}')" aria-label="Close">&times;</button>
                                            </div>

                                            <form method="POST" action="{{ route('faculty.reserve.room', $room->id) }}">
                                                @csrf
                                                <div class="week-reserve-body">
                                                    <div class="week-reserve-summary">
                                                        <div class="week-reserve-summary-item"><div class="week-reserve-label">Weekday</div><div class="week-reserve-value" id="weeklyReserveDay-{{ $room->id }}">{{ $selectedDayName }}</div></div>
                                                        <div class="week-reserve-summary-item"><div class="week-reserve-label">Time Slot</div><div class="week-reserve-value" id="weeklyReserveTime-{{ $room->id }}">{{ $item['time'] ?? $searchedTime }}</div></div>
                                                    </div>

                                                    <input type="hidden" name="time_slot_id" id="weeklyReserveTimeSlotInput-{{ $room->id }}" value="{{ $item['time_slot_id'] ?? request('time_slot_id') }}">
                                                    <input type="hidden" name="time" id="weeklyReserveTimeInput-{{ $room->id }}" value="{{ $item['time'] ?? $searchedTime }}">
                                                    <input type="hidden" name="start_time" id="weeklyReserveStartInput-{{ $room->id }}">
                                                    <input type="hidden" name="end_time" id="weeklyReserveEndInput-{{ $room->id }}">

                                                    <div class="week-reserve-field">
                                                        <label for="weeklyReserveDate-{{ $room->id }}">Reservation Date</label>
                                                        <select name="date" id="weeklyReserveDate-{{ $room->id }}" required></select>
                                                        <div class="week-reserve-help">Only future dates matching {{ $selectedDayName }} are shown.</div>
                                                    </div>

                                                    <div class="week-reserve-field">
                                                        <label for="weeklyReserveSubject-{{ $room->id }}">Set Faculty Subject</label>
                                                        <select name="faculty_subject_id" id="weeklyReserveSubject-{{ $room->id }}" required>
                                                            <option value="">Select subject</option>
                                                            @forelse(($facultySubjectOptions ?? collect()) as $subject)
                                                                <option value="{{ $subject['id'] ?? '' }}">{{ $subject['course_code'] ?? '' }} — {{ $subject['subject'] ?? '' }}</option>
                                                            @empty
                                                                <option value="" disabled>No Set Faculty subjects available</option>
                                                            @endforelse
                                                        </select>
                                                        <div class="week-reserve-help">Choose the subject assigned to your faculty account.</div>
                                                    </div>

                                                    <div class="week-reserve-actions">
                                                        <button type="button" class="week-reserve-cancel" onclick="closeWeeklyReservation('{{ $room->id }}')">Cancel</button>
                                                        <button type="submit" class="week-reserve-submit" @if(empty($facultySubjectOptions) || count($facultySubjectOptions) === 0) disabled @endif><i class="fa-solid fa-calendar-check"></i> Reserve Room</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

@elseif(request('day') && request('time_slot_id'))
    <div class="no-results"><i class="fa-solid fa-door-open"></i><div style="margin-top:8px;">No rooms were found for the selected day and time slot.</div></div>
@else
    <div class="no-results"><i class="fa-regular fa-clock"></i><div style="margin-top:8px;">Select a day and official time slot to see room availability.</div></div>
@endif

<script>
(function(){
    if (window.__officialTimeSlotReservationHandlersAttached) return;
    window.__officialTimeSlotReservationHandlersAttached = true;

    function pad(n){ return String(n).padStart(2,'0'); }
    function formatDate(date){
        const names=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const months=['January','February','March','April','May','June','July','August','September','October','November','December'];
        return names[date.getDay()] + ', ' + months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
    }
    function isoDate(date){ return date.getFullYear() + '-' + pad(date.getMonth()+1) + '-' + pad(date.getDate()); }

    window.openWeeklyReservation = function(button){
        const roomId=button.dataset.roomId || '';
        const modal=document.getElementById('weeklyReserveModal-' + roomId);
        if(!modal) return;

        const targetDayCode=(button.dataset.day || '').toUpperCase();
        const targetDayName=button.dataset.dayName || targetDayCode;
        const time=button.dataset.time || '';
        const startTime=button.dataset.startTime || '';
        const endTime=button.dataset.endTime || '';
        const dayIndexes={MON:1,TUE:2,WED:3,THU:4,FRI:5,SAT:6};
        const targetDay=dayIndexes[targetDayCode] ?? -1;

        const dayText=document.getElementById('weeklyReserveDay-' + roomId);
        const timeText=document.getElementById('weeklyReserveTime-' + roomId);
        const timeInput=document.getElementById('weeklyReserveTimeInput-' + roomId);
        const startInput=document.getElementById('weeklyReserveStartInput-' + roomId);
        const endInput=document.getElementById('weeklyReserveEndInput-' + roomId);
        const timeSlotInput=document.getElementById('weeklyReserveTimeSlotInput-' + roomId);
        const dateSelect=document.getElementById('weeklyReserveDate-' + roomId);

        if(dayText) dayText.textContent=targetDayName;
        if(timeText) timeText.textContent=time;
        if(timeInput) timeInput.value=time;
        if(startInput) startInput.value=startTime;
        if(endInput) endInput.value=endTime;
        if(timeSlotInput) timeSlotInput.value=button.dataset.timeSlotId || '';

        if(dateSelect){
            dateSelect.innerHTML='';
            const placeholder=document.createElement('option');
            placeholder.value='';
            placeholder.textContent='Select reservation date';
            placeholder.disabled=true;
            placeholder.selected=true;
            dateSelect.appendChild(placeholder);

            if(targetDay >= 1 && targetDay <= 6){
                const today=new Date();
                today.setHours(0,0,0,0);

                // Reservations are for a future calendar date.
                let cursor=new Date(today);
                cursor.setDate(cursor.getDate()+1);
                let added=0;
                let safety=0;
                while(added < 12 && safety < 400){
                    if(cursor.getDay() === targetDay){
                        const option=document.createElement('option');
                        option.value=isoDate(cursor);
                        option.textContent=formatDate(cursor) + (added===0 ? ' — nearest date' : '');
                        dateSelect.appendChild(option);
                        added++;
                    }
                    cursor.setDate(cursor.getDate()+1);
                    safety++;
                }
                if(added > 0) dateSelect.selectedIndex=1;
            } else {
                placeholder.textContent='Invalid weekday';
            }
        }

        modal.classList.add('show');
        modal.setAttribute('aria-hidden','false');
        document.body.classList.add('week-reservation-open');
    };

    window.closeWeeklyReservation=function(roomId){
        const modal=document.getElementById('weeklyReserveModal-' + roomId);
        if(!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden','true');
        document.body.classList.remove('week-reservation-open');
    };

    // Never allow the reservation form to submit without an official time slot
    // and a real future date. The controller performs the authoritative checks too.
    document.querySelectorAll('.week-reserve-dialog form').forEach(function(form){
        form.addEventListener('submit',function(event){
            const slotInput=form.querySelector('input[name="time_slot_id"]');
            const dateInput=form.querySelector('select[name="date"]');

            if(!slotInput || !slotInput.value){
                event.preventDefault();
                alert('Please select an official time slot before reserving the room.');
                return;
            }

            if(!dateInput || !dateInput.value){
                event.preventDefault();
                alert('Please select a reservation date.');
            }
        });
    });

    document.addEventListener('click',function(event){
        if(event.target.classList && event.target.classList.contains('week-reserve-modal')){
            event.target.classList.remove('show');
            event.target.setAttribute('aria-hidden','true');
            document.body.classList.remove('week-reservation-open');
        }
    });

    document.addEventListener('keydown',function(event){
        if(event.key !== 'Escape') return;
        document.querySelectorAll('.week-reserve-modal.show').forEach(function(modal){
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden','true');
        });
        document.body.classList.remove('week-reservation-open');
    });
})();
</script>
