<style>
    .room-overview-launch { display:inline-flex; min-height:40px; align-items:center; gap:8px; margin:0 0 16px; padding:0 14px; border:1px solid #24543e; border-radius:9px; background:#fff; color:#24543e; font:800 13px/1 Arial,sans-serif; cursor:pointer; }
    .room-overview-launch:hover { background:#eff5ed; }
    .room-overview-overlay[hidden] { display:none !important; }
    .room-overview-overlay { position:fixed; inset:0; z-index:100500; display:grid; place-items:center; padding:20px; background:rgba(15,30,22,.56); backdrop-filter:blur(5px); }
    .room-overview-dialog { display:flex; width:min(1320px,100%); max-height:min(92vh,1000px); flex-direction:column; overflow:hidden; border:1px solid #dfe7dd; border-radius:18px; background:#f6f8f4; box-shadow:0 28px 85px rgba(15,30,22,.3); }
    .room-overview-header { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; padding:19px 22px; border-bottom:1px solid #e1e8df; background:#fff; }
    .room-overview-header h2 { margin:0; color:#173b33; font:800 21px/1.3 var(--wr-display,Arial,sans-serif); }
    .room-overview-header p { margin:5px 0 0; color:#64746a; font:550 12px/1.5 var(--wr-body,Arial,sans-serif); }
    .room-overview-close { display:grid; flex:0 0 36px; width:36px; height:36px; place-items:center; border:1px solid #e0e7df; border-radius:9px; background:#f7f9f6; color:#405a49; font-size:20px; cursor:pointer; }
    .room-overview-tools { display:flex; align-items:center; gap:12px; padding:12px 20px; border-bottom:1px solid #e1e8df; background:#fbfcfa; }
    .room-overview-search { width:min(420px,100%); min-height:40px; padding:0 12px; border:1px solid #d8e2d7; border-radius:9px; background:#fff; color:#294437; font:600 13px var(--wr-body,Arial,sans-serif); }
    .room-overview-count { color:#68776d; font:700 12px var(--wr-body,Arial,sans-serif); }
    .room-overview-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; overflow:auto; padding:15px; }
    .room-overview-card { min-width:0; overflow:hidden; border:1px solid #dfe7dd; border-radius:12px; background:#fff; }
    .room-overview-department-group { grid-column:1/-1; display:grid; gap:10px; }
    .room-overview-department-heading { display:flex; align-items:center; gap:9px; margin:4px 0 0; padding:11px 13px; border:1px solid #dce6dc; border-radius:10px; background:linear-gradient(100deg,#eaf1e8,#f8faf7); color:#214633; font:850 13px/1.3 var(--wr-body,Arial,sans-serif); }
    .room-overview-department-heading i { color:#56805d; }
    .room-overview-group-cards { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
    .room-overview-card.locked { border-color:#e2d9d2; background:#fbfaf9; }
    .room-overview-card.locked .room-overview-card-header { background:#f6f2ef; }
    .room-overview-card[hidden] { display:none !important; }
    .room-overview-card-header { display:flex; align-items:center; justify-content:space-between; gap:10px; padding:11px 13px; border-bottom:1px solid #e7ece5; background:#fbfcfa; }
    .room-overview-card-header strong { display:block; color:#294437; font:800 13px/1.3 var(--wr-body,Arial,sans-serif); }
    .room-overview-card-header small { display:block; margin-top:3px; color:#758278; font:600 10px/1.35 var(--wr-body,Arial,sans-serif); }
    .room-overview-count-badge { flex:0 0 auto; padding:5px 7px; border-radius:99px; background:#eef4eb; color:#356348; font:800 10px var(--wr-body,Arial,sans-serif); }
    .room-overview-table-wrap { overflow:auto; }
    .room-overview-table { width:100%; min-width:550px; border-collapse:collapse; table-layout:fixed; }
    .room-overview-table th,.room-overview-table td { padding:3px; border-right:1px solid #e5ebe3; border-bottom:1px solid #e5ebe3; text-align:center; }
    .room-overview-table th { height:29px; background:#f3f6f1; color:#52675a; font:800 9px var(--wr-body,Arial,sans-serif); }
    .room-overview-table th:first-child,.room-overview-table .room-overview-time { width:91px; background:#f7f8f5; color:#52675a; font:750 9px var(--wr-body,Arial,sans-serif); white-space:nowrap; }
    .room-overview-slot { display:flex; width:100%; min-height:29px; flex-direction:column; align-items:center; justify-content:center; padding:3px 2px; border:1px solid transparent; border-radius:5px; background:#fff; color:#718075; font:750 9px/1.2 var(--wr-body,Arial,sans-serif); cursor:pointer; }
    .room-overview-slot:hover { border-color:#b7c8b7; background:#f7faf6; }
    .room-overview-slot.occupied { border-color:#87b99a; background:#dcefe2; color:#215b39; font-weight:850; }
    .room-overview-slot.partial { border-color:#dcb95e; background:#fff0c7; color:#745312; font-weight:850; }
    .room-overview-slot.locked,.room-overview-slot.locked:hover { border-color:#e4e9e2; background:#fff; color:#718075; cursor:not-allowed; filter:none; opacity:1; }
    .room-overview-slot.locked.occupied,.room-overview-slot.locked.occupied:hover { border-color:#87b99a; background:#dcefe2; color:#215b39; }
    .room-overview-slot.locked.partial,.room-overview-slot.locked.partial:hover { border-color:#dcb95e; background:#fff0c7; color:#745312; }
    .room-overview-slot small { margin-top:2px; color:inherit; font-size:8px; font-weight:600; }
    .room-overview-no-results { grid-column:1/-1; padding:30px; border:1px dashed #ccd9cb; border-radius:12px; color:#64746a; text-align:center; font:650 13px var(--wr-body,Arial,sans-serif); }
    .room-overview-lunch td { height:23px; background:#f5f1e4; color:#806a39; font:800 8px var(--wr-body,Arial,sans-serif); letter-spacing:.13em; }
    .room-overview-standalone { position:static; inset:auto; display:block; padding:0; background:transparent; backdrop-filter:none; }
    .room-overview-standalone .room-overview-dialog { width:100%; max-height:none; overflow:visible; border:0; background:transparent; box-shadow:none; }
    .room-overview-standalone .room-overview-header { padding:0 0 14px; border:0; background:transparent; }
    .room-overview-standalone .room-overview-close { display:none; }
    .room-overview-standalone .room-overview-tools { margin-bottom:14px; padding:12px; border:1px solid #e1e8df; border-radius:12px; }
    .room-overview-standalone .room-overview-grid { overflow:visible; padding:0; }
    .room-overview-standalone .room-overview-group-cards { grid-template-columns:repeat(2,minmax(0,1fr)); }
    @media(max-width:760px) { .room-overview-overlay { padding:8px; } .room-overview-dialog { max-height:96vh; border-radius:13px; } .room-overview-grid { grid-template-columns:minmax(0,1fr); padding:9px; } .room-overview-group-cards,.room-overview-standalone .room-overview-group-cards { grid-template-columns:minmax(0,1fr); } .room-overview-header { padding:15px; } .room-overview-tools { padding:9px 12px; } }
</style>

@unless($overviewStandalone ?? false)
<button type="button" class="room-overview-launch" id="openRoomAvailabilityOverview"><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i> All-room availability</button>
@endunless

<div class="room-overview-overlay {{ ($overviewStandalone ?? false) ? 'room-overview-standalone' : '' }}" id="roomAvailabilityOverview" @unless($overviewStandalone ?? false) hidden @endunless>
    <section class="room-overview-dialog" role="{{ ($overviewStandalone ?? false) ? 'region' : 'dialog' }}" @unless($overviewStandalone ?? false) aria-modal="true" @endunless aria-labelledby="roomOverviewTitle">
        <header class="room-overview-header">
            <div><h2 id="roomOverviewTitle">All-room availability</h2><p>Green is open, amber is partly occupied, and gray is scheduled. Select a time cell to open that room’s Excel schedule.</p></div>
            <button type="button" class="room-overview-close" id="closeRoomAvailabilityOverview" aria-label="Close room overview">&times;</button>
        </header>
        @unless($overviewStandalone ?? false)
            <div class="room-overview-tools">
                <input type="search" class="room-overview-search" id="roomOverviewSearch" placeholder="Filter by room, building, or college" autocomplete="off">
                <span class="room-overview-count" id="roomOverviewCount" role="status"></span>
            </div>
        @endunless
        <div class="room-overview-grid" id="roomOverviewGrid"></div>
    </section>
</div>

<script>
(() => {
    const rooms = @json($availabilityRooms ?? []);
    const isCrossScheduling = @json((bool) ($overviewCrossMode ?? false));
    const isStandalone = @json((bool) ($overviewStandalone ?? false));
    const termId = @json($activeTerm->id);
    const excelBaseUrl = @json(url('/chair/excel'));
    const overlay = document.getElementById('roomAvailabilityOverview');
    const grid = document.getElementById('roomOverviewGrid');
    const search = document.getElementById('roomOverviewSearch');
    const count = document.getElementById('roomOverviewCount');
    const dayColumns = [
        ['MON', 'MON'], ['TUE', 'TUE'], ['WED', 'WED'],
        ['THU', 'THU'], ['FRI', 'FRI'], ['SAT', 'SAT']
    ];
    const rows = [
        ['07:00', '08:00'], ['08:00', '09:00'], ['09:00', '10:00'],
        ['10:00', '11:00'], ['11:00', '12:00'], ['13:00', '14:00'],
        ['14:00', '15:00'], ['15:00', '16:00'], ['16:00', '17:00'],
        ['17:00', '18:00'], ['18:00', '19:00']
    ];

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({
            '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'
        }[character]));
    }

    function clockMinutes(value) {
        const match = String(value || '').trim().match(/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i);
        if (!match) return null;
        let hour = Number(match[1]);
        const minute = Number(match[2]);
        const meridiem = String(match[3] || '').toUpperCase();
        if (meridiem === 'PM' && hour < 12) hour += 12;
        if (meridiem === 'AM' && hour === 12) hour = 0;
        if (!meridiem && hour >= 1 && hour <= 6) hour += 12;
        return hour <= 23 && minute <= 59 ? hour * 60 + minute : null;
    }

    function range(value) {
        const parts = String(value || '').replace(/[–—]/g, '-').split('-');
        if (parts.length !== 2) return null;
        let start = clockMinutes(parts[0]);
        let end = clockMinutes(parts[1]);
        if (start === null || end === null) return null;
        if (end <= start && !/\b(?:AM|PM)\b/i.test(parts[1])) {
            const firstHour = Number((parts[0].match(/^(\d{1,2})/) || [])[1]);
            const secondHour = Number((parts[1].match(/^(\d{1,2})/) || [])[1]);
            if (firstHour >= 1 && firstHour <= 11 && secondHour >= 1 && secondHour <= 11) end += 12 * 60;
        }
        return end > start ? { start, end } : null;
    }

    function normalizedDay(value) {
        const key = String(value || '').trim().toUpperCase();
        return key.length > 3 ? key.slice(0, 3) : key;
    }

    function openExcel(roomId, day, start, end, crossRoom) {
        const query = new URLSearchParams({term_id: String(termId), focus_day: day, focus_time: start + '-' + end});
        if (crossRoom || isCrossScheduling) query.set('mode', 'cross');
        window.location.href = excelBaseUrl + '/' + encodeURIComponent(roomId) + '?' + query.toString();
    }

    function render() {
        if (!grid) return;
        grid.replaceChildren();
        const groups = new Map();
        rooms.forEach(function(room) {
            const departmentName = String(room.department_name || 'Unassigned college');
            if (!groups.has(departmentName)) {
                const group = document.createElement('section');
                group.className = 'room-overview-department-group';
                group.dataset.department = departmentName.toLowerCase();
                group.innerHTML = '<h3 class="room-overview-department-heading"><i class="fa-solid fa-building-columns" aria-hidden="true"></i><span>College / College: ' + escapeHtml(departmentName) + '</span></h3><div class="room-overview-group-cards"></div>';
                groups.set(departmentName, group);
                grid.appendChild(group);
            }
            const groupCards = groups.get(departmentName).querySelector('.room-overview-group-cards');
            const card = document.createElement('article');
            const isLocked = room.is_current_department === false && !room.allow_other_departments;
            card.className = 'room-overview-card' + (isLocked ? ' locked' : '');
            card.dataset.search = [room.room_name, room.building_name, room.department_name].join(' ').toLowerCase();
            const schedules = Array.isArray(room.schedules) ? room.schedules : [];
            const tableRows = rows.map(function(slot, index) {
                const lunchRow = index === 5
                    ? '<tr class="room-overview-lunch"><td colspan="7">NOON BREAK</td></tr>'
                    : '';
                const start = clockMinutes(slot[0]);
                const end = clockMinutes(slot[1]);
                const timeLabel = (Number(slot[0].slice(0, 2)) % 12 || 12) + ':00–' + (Number(slot[1].slice(0, 2)) % 12 || 12) + ':00';
                const cells = dayColumns.map(function(day) {
                    const matches = schedules.filter(function(item) {
                        const scheduleRange = range(item.time);
                        return normalizedDay(item.day) === day[0] && scheduleRange
                            && scheduleRange.start < end && scheduleRange.end > start;
                    });
                    const courseCodes = [...new Set(matches.map(item => String(item.course_code || '').trim()).filter(Boolean))];
                    const label = matches.length ? courseCodes.join(' · ') : 'Open slot';
                    const details = matches.map(item => [item.time, item.subject].filter(Boolean).join(' · ')).join('\n');
                    const state = !matches.length ? 'free' : (matches.some(item => {
                        const scheduleRange = range(item.time);
                        return scheduleRange && scheduleRange.start <= start && scheduleRange.end >= end;
                    }) ? 'occupied' : 'partial');
                    return '<td><button type="button" ' + (isLocked ? 'disabled ' : '') + 'class="room-overview-slot ' + state + (isLocked ? ' locked' : '') + '" data-room-id="' + Number(room.id) + '" data-cross-room="' + (room.is_current_department === false ? '1' : '0') + '" data-day="' + day[0] + '" data-start="' + slot[0] + '" data-end="' + slot[1] + '" title="' + escapeHtml(isLocked ? 'Locked: this college has not enabled cross-scheduling.' : (details || 'Open in the Excel editor')) + '">' + escapeHtml(label) + '</button></td>';
                }).join('');
                return lunchRow + '<tr><th class="room-overview-time">' + escapeHtml(timeLabel) + '</th>' + cells + '</tr>';
            }).join('');

            const accessLabel = room.is_current_department === false
                ? (room.allow_other_departments ? ' · Cross-scheduling enabled' : ' · Locked · permission off')
                : ' · Your college';
            const accessBadge = isLocked ? '<span class="room-overview-count-badge"><i class="fa-solid fa-lock" aria-hidden="true"></i> Locked</span>' : '<span class="room-overview-count-badge">' + schedules.length + ' classes</span>';
            card.innerHTML = '<header class="room-overview-card-header"><span><strong>' + escapeHtml(room.room_name || 'Room') + '</strong><small>' + escapeHtml([room.department_name, room.building_name].filter(Boolean).join(' · ') + accessLabel) + '</small></span>' + accessBadge + '</header><div class="room-overview-table-wrap"><table class="room-overview-table"><thead><tr><th>TIME</th>' + dayColumns.map(day => '<th>' + day[1] + '</th>').join('') + '</tr></thead><tbody>' + tableRows + '</tbody></table></div>';
            groupCards.appendChild(card);
        });
        filterRooms();
    }

    function filterRooms() {
        const query = String(search?.value || '').trim().toLowerCase();
        const cards = Array.from(grid?.querySelectorAll('.room-overview-card') || []);
        let visible = 0;
        cards.forEach(card => {
            card.hidden = Boolean(query) && !card.dataset.search.includes(query);
            if (!card.hidden) visible++;
        });
        grid?.querySelectorAll('.room-overview-department-group').forEach(group => {
            const visibleCards = group.querySelectorAll('.room-overview-card:not([hidden])').length;
            group.hidden = visibleCards === 0;
        });
        if (count) count.textContent = visible + ' ' + (visible === 1 ? 'room' : 'rooms');
        let empty = grid?.querySelector('.room-overview-no-results');
        if (visible === 0 && grid && !empty) {
            empty = document.createElement('div');
            empty.className = 'room-overview-no-results';
            empty.textContent = 'No rooms match this search.';
            grid.appendChild(empty);
        } else if (visible > 0) empty?.remove();
    }

    document.getElementById('openRoomAvailabilityOverview')?.addEventListener('click', function() {
        render();
        overlay.hidden = false;
        search?.focus();
    });
    document.getElementById('closeRoomAvailabilityOverview')?.addEventListener('click', () => overlay.hidden = true);
    if (!isStandalone) overlay?.addEventListener('click', event => { if (event.target === overlay) overlay.hidden = true; });
    search?.addEventListener('input', filterRooms);
    grid?.addEventListener('click', function(event) {
        const slot = event.target.closest('.room-overview-slot');
        if (slot) openExcel(slot.dataset.roomId, slot.dataset.day, slot.dataset.start, slot.dataset.end, slot.dataset.crossRoom === '1');
    });
    if (!isStandalone) document.addEventListener('keydown', event => { if (event.key === 'Escape' && overlay && !overlay.hidden) overlay.hidden = true; });
    if (isStandalone) render();
})();
</script>
