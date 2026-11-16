@include('footerheader.header')

@php
    $role = auth()->user()->role;
    $recordData = $auditLog->after_data ?? $auditLog->before_data ?? [];
    $snapshotBefore = $auditLog->snapshot_before ?? [];
    $snapshotAfter = $auditLog->snapshot_after ?? [];
    $focus = $snapshotAfter['focus'] ?? $snapshotBefore['focus'] ?? [];
    $roomLabels = collect($snapshotAfter['rooms'] ?? [])->pluck('name')->filter()->unique()->implode(' ↔ ');
    $day = $focus['day'] ?? ($recordData['day'] ?? null);
    $start = $focus['start_time'] ?? ($recordData['start_time'] ?? null);
    $end = $focus['end_time'] ?? ($recordData['end_time'] ?? null);
    $date = $focus['date'] ?? ($recordData['date'] ?? $recordData['override_date'] ?? null);
    $formatClock = function ($value) {
        if (!$value) return '';
        try { return \Carbon\Carbon::parse(trim((string) $value))->format('g:i A'); }
        catch (\Throwable $exception) { return trim((string) $value); }
    };
    $formatTimeRange = function ($value) use ($formatClock) {
        $value = trim((string) $value);
        if ($value === '') return '';
        if (!preg_match('/^\s*(.+?)\s*(?:-|–|—|\bto\b)\s*(.+?)\s*$/i', $value, $parts)) {
            return $formatClock($value);
        }
        $left = trim($parts[1]);
        $right = trim($parts[2]);
        if (!preg_match('/(?:AM|PM)$/i', $left) && preg_match('/(AM|PM)$/i', $right, $period)) $left .= ' ' . strtoupper($period[1]);
        if (!preg_match('/(?:AM|PM)$/i', $right) && preg_match('/(AM|PM)$/i', $left, $period)) $right .= ' ' . strtoupper($period[1]);
        return $formatClock($left) . '–' . $formatClock($right);
    };
    $roomNamesById = collect([$snapshotBefore, $snapshotAfter])
        ->flatMap(fn($snapshot) => $snapshot['rooms'] ?? [])
        ->mapWithKeys(fn($room) => [(int) $room['id'] => $room['name'] ?? 'Room unavailable']);
    $formatChange = function ($data) use ($roomNamesById, $formatClock, $formatTimeRange) {
        if (!$data) return 'No earlier state was recorded.';
        $source = isset($data['request']) && is_array($data['request']) ? array_merge($data['request'], $data) : $data;
        $rooms = collect([
            $source['requester_room_id'] ?? null,
            $source['target_room_id'] ?? null,
            $source['original_room_id'] ?? null,
            $source['new_room_id'] ?? null,
            $source['room_id'] ?? null,
        ])->filter()->unique()->map(fn($id) => $roomNamesById[(int) $id] ?? ('Room #' . $id))->implode(' ↔ ');
        $time = isset($source['time']) ? $formatTimeRange($source['time']) : null;
        if (!empty($source['start_time']) || !empty($source['end_time'])) {
            $time = $formatClock($source['start_time'] ?? '') . '–' . $formatClock($source['end_time'] ?? '');
        }
        return collect([
            'Status' => $source['status'] ?? null,
            'Room' => $rooms ?: null,
            'Occupied by / purpose' => $source['occupied_by'] ?? $source['purpose'] ?? null,
            'Date' => $source['override_date'] ?? $source['date'] ?? $source['swap_date'] ?? null,
            'Day' => $source['day'] ?? null,
            'Time' => $time,
            'Reason' => $source['reason'] ?? $source['decline_reason'] ?? null,
            'Details' => $source['details'] ?? null,
        ])->filter(fn($value) => $value !== null && $value !== '')
          ->map(fn($value, $label) => $label . ': ' . (is_scalar($value) ? $value : json_encode($value)))
          ->implode("\n");
    };
    try { $dateLabel = $date ? \Carbon\Carbon::parse($date)->format('M j, Y') : null; }
    catch (\Throwable $exception) { $dateLabel = $date; }
@endphp

<style>
    .audit-detail-content { min-width:0; padding:124px clamp(18px,3vw,40px) 42px; min-height:100vh; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.2),transparent 28%),linear-gradient(145deg,#eef2ec,#f8f7f2 58%,#f1f0e8); }
    .audit-detail { width:min(100%,1480px); margin:0 auto; color:#20372a; }
    .audit-back { display:inline-flex; align-items:center; gap:8px; margin:0 0 15px; color:#356047; font-size:12px; font-weight:800; text-decoration:none; }
    .audit-detail-hero { margin-bottom:17px; padding:25px 29px; border-radius:20px; background:linear-gradient(118deg,#163b33,#245543 60%,#37664d); color:#fff; box-shadow:0 15px 34px rgba(28,65,48,.13); }
    .audit-detail-hero h1 { margin:0; color:#fff; font:800 clamp(25px,3vw,35px)/1.15 var(--wr-display,Arial,sans-serif); letter-spacing:-.03em; text-transform:capitalize; }
    .audit-detail-hero p { margin:9px 0 0; color:#dfe8df; font-size:13px; line-height:1.55; }
    .audit-meta-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; margin:0 0 17px; }
    .audit-meta { min-width:0; padding:14px 16px; border:1px solid #dfe7dd; border-radius:13px; background:#fff; }
    .audit-meta span { display:block; color:#758177; font-size:9px; font-weight:850; letter-spacing:.08em; text-transform:uppercase; }
    .audit-meta strong { display:block; overflow-wrap:anywhere; margin-top:6px; color:#294435; font-size:13px; }
    .audit-detail-panel { margin-bottom:16px; overflow:hidden; border:1px solid #dfe7dd; border-radius:16px; background:#fff; box-shadow:0 7px 20px rgba(31,57,43,.045); }
    .audit-detail-panel h2 { margin:0; padding:16px 19px; border-bottom:1px solid #e8ede7; color:#234b37; font-size:16px; }
    .audit-change-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0; }
    .audit-change { padding:17px 19px; min-width:0; }
    .audit-change + .audit-change { border-left:1px solid #edf0eb; }
    .audit-change h3 { margin:0 0 11px; color:#6b786e; font-size:10px; font-weight:850; letter-spacing:.08em; text-transform:uppercase; }
    .audit-change pre { overflow:auto; margin:0; padding:13px; border-radius:10px; background:#f7f9f6; color:#35483a; font:12px/1.55 var(--wr-body,Arial,sans-serif); white-space:pre-wrap; overflow-wrap:anywhere; }
    .audit-snapshot-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,500px),1fr)); gap:13px; padding:15px; }
    .audit-sheet { overflow:auto; border:1px solid #dfe7dd; border-radius:12px; }
    .audit-sheet-head { padding:13px 15px; border-bottom:1px solid #e7ece5; background:#f8faf7; }
    .audit-sheet-head strong { display:block; color:#234b37; font-size:13px; }
    .audit-sheet-head span { display:block; margin-top:4px; color:#778279; font-size:10px; }
    .audit-sheet table { width:100%; min-width:680px; border-collapse:collapse; table-layout:fixed; }
    .audit-sheet th,.audit-sheet td { padding:8px; border-right:1px solid #e5eae3; border-bottom:1px solid #e5eae3; text-align:left; vertical-align:top; }
    .audit-sheet th { background:#f4f7f2; color:#68756c; font-size:9px; font-weight:850; text-transform:uppercase; }
    .audit-sheet td { height:58px; color:#34483a; font-size:10px; line-height:1.4; }
    .audit-sheet td.audit-time { width:102px; background:#fafbf9; color:#536459; font-weight:750; }
    .audit-sheet td.audit-affected { background:#fff0bf; box-shadow:inset 0 0 0 2px #d4a83e; }
    .audit-cell-entry { display:block; margin:0 0 4px; padding:5px; border-radius:5px; background:#e9f1e9; }
    .audit-cell-entry.audit-event { background:#fce8df; }
    .audit-cell-entry strong { display:block; color:#254d37; font-size:10px; }
    .audit-cell-entry small { display:block; margin-top:2px; color:#5d6e62; font-size:9px; }
    .audit-legend { padding:0 16px 14px; color:#68756c; font-size:10px; }
    .audit-legend i { display:inline-block; width:12px; height:12px; margin-right:6px; border:1px solid #d4a83e; background:#fff0bf; vertical-align:-2px; }
    .audit-history { width:100%; border-collapse:collapse; }
    .audit-history th,.audit-history td { padding:12px 16px; border-bottom:1px solid #edf0eb; color:#46574a; font-size:11px; text-align:left; }
    .audit-history th { background:#f7f9f6; color:#718075; font-size:9px; letter-spacing:.07em; text-transform:uppercase; }
    .audit-note { padding:16px 19px; color:#718075; font-size:12px; }
    @media(max-width:800px) { .audit-detail-content { padding:88px 12px 26px; } .audit-meta-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } .audit-change-grid { grid-template-columns:1fr; } .audit-change + .audit-change { border-top:1px solid #edf0eb; border-left:0; } }
</style>

<div class="wrapper">
    <div class="main-layout">
        @include($role . '.sidebar')
        <main class="content audit-detail-content">
            <article class="audit-detail">
                <a class="audit-back" href="{{ route('audit-logs.index', ['type' => $auditLog->event_type]) }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to {{ $auditLog->event_type === 'room_swap' ? 'Room Swaps' : ucfirst($auditLog->event_type) . 's' }}</a>
                <header class="audit-detail-hero">
                    <h1>{{ ucfirst(str_replace('_', ' ', $auditLog->action)) }}</h1>
                    <p>{{ $auditLog->description ?: 'Recorded schedule and room action.' }}</p>
                </header>

                <section class="audit-meta-grid" aria-label="Action details">
                    <div class="audit-meta"><span>Date &amp; time</span><strong>{{ $auditLog->occurred_at?->format('M j, Y · g:i:s A') ?? 'Timestamp unavailable' }}</strong></div>
                    <div class="audit-meta"><span>Actor</span><strong>{{ $auditLog->actor_name }} · {{ ucfirst($auditLog->actor_role) }}</strong></div>
                    <div class="audit-meta"><span>Room{{ count($snapshotAfter['rooms'] ?? []) > 1 ? 's' : '' }}</span><strong>{{ $roomLabels ?: ($auditLog->room?->room_name ?? 'Room unavailable') }}</strong></div>
                    <div class="audit-meta"><span>Status</span><strong>{{ $auditLog->status_before ?? '—' }}{{ $auditLog->status_after ? ' → ' . $auditLog->status_after : '' }}</strong></div>
                    <div class="audit-meta"><span>Day &amp; date</span><strong>{{ $day ? strtoupper($day) : '—' }}{{ $dateLabel ? ' · ' . $dateLabel : '' }}</strong></div>
                    <div class="audit-meta"><span>Time range</span><strong>{{ $start && $end ? $formatClock($start) . '–' . $formatClock($end) : '—' }}</strong></div>
                    <div class="audit-meta"><span>Reason / purpose</span><strong>{{ $auditLog->reason ?: 'No reason was saved' }}</strong></div>
                    <div class="audit-meta"><span>Record</span><strong>{{ ucfirst(str_replace('_', ' ', $auditLog->event_type)) }}{{ $auditLog->schedule_id ? ' · Schedule #' . $auditLog->schedule_id : '' }}</strong></div>
                </section>

                @if($auditLog->event_type === 'reservation')
                    <section class="audit-detail-panel">
                        <h2>Reservation details</h2>
                        @if($recordData)
                            <div class="audit-change-grid">
                                <div class="audit-change">
                                    <h3>Saved reservation information</h3>
                                    <pre>{{ $formatChange($recordData) }}</pre>
                                </div>
                                <div class="audit-change">
                                    <h3>Status history</h3>
                                    @if($statusHistory->isNotEmpty())
                                        <table class="audit-history"><thead><tr><th>When</th><th>Change</th><th>By</th></tr></thead><tbody>@foreach($statusHistory as $history)<tr><td>{{ $history->occurred_at?->format('M j, Y · g:i A') }}</td><td>{{ $history->status_before ?? '—' }} → {{ $history->status_after ?? ucfirst($history->action) }}</td><td>{{ $history->actor_name }}</td></tr>@endforeach</tbody></table>
                                    @else
                                        <div class="audit-note">No earlier status records are available for this reservation.</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="audit-note">Reservation details were not saved with this older record.</div>
                        @endif
                    </section>
                @endif

                @if($snapshotReconstructed ?? false)
                    <section class="audit-detail-panel" role="note">
                        <div class="audit-note"><strong>Reconstructed legacy view:</strong> This older audit record did not save a schedule snapshot when it was created. The view below combines its saved action details with the room’s current timetable, so it cannot show timetable changes made since the action.</div>
                    </section>
                @endif

                <section class="audit-detail-panel">
                    <h2>Before and after</h2>
                    @if($auditLog->before_data || $auditLog->after_data)
                        <div class="audit-change-grid">
                            <div class="audit-change"><h3>Before</h3><pre>{{ $formatChange($auditLog->before_data) }}</pre></div>
                            <div class="audit-change"><h3>After</h3><pre>{{ $formatChange($auditLog->after_data) }}</pre></div>
                        </div>
                    @else
                        <div class="audit-note">Before and after details are unavailable for this older record.</div>
                    @endif
                </section>

                @foreach(['Before' => $snapshotBefore, 'After' => $snapshotAfter] as $snapshotLabel => $snapshot)
                    @if(!empty($snapshot['rooms']))
                        <section class="audit-detail-panel">
                            <h2>{{ $snapshotLabel }} room schedule snapshot</h2>
                            <div class="audit-snapshot-grid">
                                @foreach($snapshot['rooms'] as $roomSheet)
                                    @php
                                        $entries = collect($roomSheet['entries'] ?? []);
                                        $periods = $entries->map(fn($entry) => [
                                            'key' => ($entry['start_time'] ?? '') . '|' . ($entry['end_time'] ?? '') . '|' . ($entry['time'] ?? ''),
                                            'time' => $entry['time'] ?: trim(($entry['start_time'] ?? '') . '–' . ($entry['end_time'] ?? '')),
                                            'start' => $entry['start_time'] ?? null,
                                            'end' => $entry['end_time'] ?? null,
                                        ])->unique('key')->sortBy('start')->values();
                                        $days = ['MON' => 'Monday', 'TUE' => 'Tuesday', 'WED' => 'Wednesday', 'THU' => 'Thursday', 'FRI' => 'Friday', 'SAT' => 'Saturday', 'SUN' => 'Sunday'];
                                        $focusIds = collect($snapshot['focus']['schedule_ids'] ?? [])->map(fn($id) => (int) $id);
                                        $focusDay = strtoupper(substr((string) ($snapshot['focus']['day'] ?? ''), 0, 3));
                                        $focusStart = $snapshot['focus']['start_time'] ?? null;
                                        $focusEnd = $snapshot['focus']['end_time'] ?? null;
                                    @endphp
                                    <div class="audit-sheet">
                                        <div class="audit-sheet-head"><strong>{{ $roomSheet['name'] }}</strong><span>{{ collect([$roomSheet['building'] ?? null, $roomSheet['department'] ?? null])->filter()->implode(' · ') ?: 'Room details unavailable' }}</span></div>
                                        @if($entries->isEmpty())
                                            <div class="audit-note">No schedule rows were available in this snapshot.</div>
                                        @else
                                            <table>
                                                <thead><tr><th class="audit-time">Time</th>@foreach($days as $short => $long)<th>{{ $long }}</th>@endforeach</tr></thead>
                                                <tbody>
                                                    @foreach($periods as $period)
                                                        <tr><td class="audit-time">{{ $period['time'] ? $formatTimeRange($period['time']) : 'Time unavailable' }}</td>
                                                            @foreach($days as $short => $long)
                                                                @php
                                                                    $cellEntries = $entries->filter(fn($entry) => strtoupper(substr((string) ($entry['day'] ?? ''), 0, 3)) === $short && (($entry['start_time'] ?? '') . '|' . ($entry['end_time'] ?? '') . '|' . ($entry['time'] ?? '')) === $period['key']);
                                                                    $affected = $cellEntries->contains(function($entry) use ($focusIds, $focusDay, $focusStart, $focusEnd, $short) {
                                                                        if (!empty($entry['highlight']) || ($entry['kind'] ?? '') === 'reservation') return true;
                                                                        if (isset($entry['id']) && is_numeric($entry['id']) && $focusIds->contains((int) $entry['id'])) return true;
                                                                        if ($focusDay !== $short || !$focusStart || !$focusEnd || empty($entry['start_time']) || empty($entry['end_time'])) return false;
                                                                        return $entry['start_time'] < $focusEnd && $entry['end_time'] > $focusStart;
                                                                    });
                                                                @endphp
                                                                <td class="{{ $affected ? 'audit-affected' : '' }}">
                                                                    @foreach($cellEntries as $entry)
                                                                        <span class="audit-cell-entry {{ ($entry['kind'] ?? '') === 'reservation' ? 'audit-event' : '' }}">
                                                                            <strong>{{ $entry['course_code'] ?: $entry['subject'] ?: 'Scheduled item' }}</strong>
                                                                            <small>{{ collect([$entry['subject'] ?? null, $entry['section'] ?? null, $entry['instructor'] ?? null])->filter()->implode(' · ') }}</small>
                                                                        </span>
                                                                    @endforeach
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="audit-legend"><i aria-hidden="true"></i> Highlighted cells overlap the action’s affected time range.</div>
                        </section>
                    @else
                        <section class="audit-detail-panel"><h2>{{ $snapshotLabel }} room schedule snapshot</h2><div class="audit-note">A schedule snapshot was not saved for this older record.</div></section>
                    @endif
                @endforeach
            </article>
        </main>
    </div>
</div>
