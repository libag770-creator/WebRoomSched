@include('footerheader.header')

@php
    $role = auth()->user()->role;
    $types = [
        'override' => 'Overrides',
        'room_swap' => 'Room Swaps',
        'reservation' => 'Reservations',
    ];
@endphp

<style>
    .audit-content { min-width:0; padding:124px clamp(18px,3vw,40px) 42px; min-height:100vh; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.2),transparent 28%),linear-gradient(145deg,#eef2ec,#f8f7f2 58%,#f1f0e8); }
    .audit-page { width:min(100%,1480px); margin:0 auto; color:#20372a; }
    .audit-hero { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin:0 0 22px; padding:26px 30px; border-radius:20px; background:linear-gradient(118deg,#163b33,#245543 60%,#37664d); color:#fff; box-shadow:0 15px 34px rgba(28,65,48,.13); }
    .audit-kicker { margin:0 0 7px; color:#e2c987; font-size:10px; font-weight:850; letter-spacing:.16em; text-transform:uppercase; }
    .audit-hero h1 { margin:0; color:#fff; font:800 clamp(26px,3vw,36px)/1.15 var(--wr-display,Arial,sans-serif); letter-spacing:-.03em; }
    .audit-hero p { max-width:700px; margin:9px 0 0; color:#dfe8df; font-size:13px; line-height:1.55; }
    .audit-count { flex:0 0 auto; padding:12px 15px; border:1px solid rgba(255,255,255,.18); border-radius:12px; background:rgba(255,255,255,.09); color:#f6f2e6; font-size:12px; font-weight:750; }
    .audit-panel { overflow:hidden; border:1px solid #dfe7dd; border-radius:17px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.05); }
    .audit-tabs { display:flex; gap:4px; padding:10px 12px 0; border-bottom:1px solid #e8ede7; background:#fbfcfa; }
    .audit-tabs a { display:inline-flex; align-items:center; gap:8px; margin-bottom:-1px; padding:13px 17px; border:1px solid transparent; border-bottom-color:#e8ede7; border-radius:10px 10px 0 0; color:#66746a; font-size:12px; font-weight:800; text-decoration:none; }
    .audit-tabs a:hover { color:#234b37; background:#f3f7f2; }
    .audit-tabs a.active { border-color:#e1e9df; border-bottom-color:#fff; background:#fff; color:#214d36; }
    .audit-filters { display:grid; grid-template-columns:minmax(180px,1.4fr) repeat(5,minmax(120px,1fr)) auto auto; gap:10px; padding:17px; border-bottom:1px solid #edf0eb; }
    .audit-filters label { display:block; margin:0 0 5px; color:#6b786e; font-size:10px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; }
    .audit-filters input,.audit-filters select { width:100%; min-height:40px; padding:0 11px; border:1px solid #dfe6de; border-radius:9px; background:#fff; color:#27382d; font-size:12px; }
    .audit-filters button,.audit-clear { display:inline-flex; align-items:center; justify-content:center; align-self:end; min-height:40px; padding:0 14px; border:1px solid #214d36; border-radius:9px; background:#214d36; color:#fff; font-size:12px; font-weight:800; text-decoration:none; cursor:pointer; }
    .audit-clear { border-color:#dfe6de; background:#f7f9f6; color:#526258; }
    .audit-table-wrap { overflow:auto; }
    .audit-table { width:100%; border-collapse:collapse; min-width:780px; }
    .audit-table th { padding:12px 15px; background:#f7f9f6; color:#718075; font-size:10px; font-weight:850; letter-spacing:.07em; text-align:left; text-transform:uppercase; white-space:nowrap; }
    .audit-table td { padding:14px 15px; border-top:1px solid #edf0eb; color:#394a3e; font-size:12px; vertical-align:middle; }
    .audit-table tbody tr:hover { background:#fbfcfa; }
    .audit-action { display:block; color:#234b37; font-size:12px; font-weight:850; text-decoration:none; }
    .audit-action:hover { text-decoration:underline; }
    .audit-sub { display:block; max-width:390px; margin-top:4px; color:#748077; line-height:1.45; }
    .audit-badge { display:inline-flex; align-items:center; min-height:23px; padding:3px 8px; border-radius:999px; background:#edf4ed; color:#306142; font-size:10px; font-weight:850; text-transform:capitalize; }
    .audit-muted { color:#7a857c; }
    .audit-empty { padding:52px 20px; text-align:center; color:#6b786e; }
    .audit-empty i { display:block; margin-bottom:12px; color:#a4b0a6; font-size:25px; }
    .audit-pagination { padding:14px 17px; border-top:1px solid #edf0eb; }
    .audit-row-actions { display:flex; align-items:center; justify-content:flex-end; gap:7px; }
    .audit-icon-action { display:grid; width:34px; height:34px; place-items:center; border:1px solid #dfe7dd; border-radius:9px; background:#fff; color:#486452; font-size:12px; text-decoration:none; cursor:pointer; transition:background .15s ease,border-color .15s ease,color .15s ease; }
    .audit-icon-action:hover { border-color:#b9cdbb; background:#f3f8f2; color:#214d36; }
    .audit-icon-action.delete { border-color:#f0d9d5; background:#fff8f7; color:#a94740; }
    .audit-icon-action.delete:hover { border-color:#dfaaa3; background:#fff0ee; color:#8f332d; }
    .audit-icon-action:focus-visible { outline:3px solid rgba(49,104,76,.2); outline-offset:2px; }
    .audit-delete-form { margin:0; }
    .audit-sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }
    @media(max-width:1050px) { .audit-filters { grid-template-columns:repeat(3,minmax(0,1fr)); } }
    @media(max-width:680px) { .audit-content { padding:88px 12px 26px; } .audit-hero { display:block; padding:22px; } .audit-count { display:inline-flex; margin-top:14px; } .audit-tabs { overflow:auto; white-space:nowrap; } .audit-tabs a { padding:12px 13px; } .audit-filters { grid-template-columns:repeat(2,minmax(0,1fr)); padding:13px; } .audit-filters > div:first-child { grid-column:1 / -1; } }
</style>

<div class="wrapper">
    <div class="main-layout">
        @include($role . '.sidebar')
        <main class="content audit-content">
            <div class="audit-page">
                <header class="audit-hero">
                    <div>
                        <p class="audit-kicker">Accountability &amp; history</p>
                        <h1>Audit Logs</h1>
                        <p>Review saved records of schedule overrides, room swaps, and reservations. Each entry keeps the room schedule as it appeared when the action happened.</p>
                    </div>
                    <div class="audit-count">{{ $logs->total() }} {{ Str::plural('record', $logs->total()) }}</div>
                </header>

                <section class="audit-panel" aria-label="Audit log records">
                    <nav class="audit-tabs" aria-label="Audit log categories">
                        @foreach($types as $key => $label)
                            <a class="{{ $type === $key ? 'active' : '' }}" href="{{ route('audit-logs.index', array_merge(request()->except('page'), ['type' => $key])) }}">
                                <i class="fa-solid {{ $key === 'override' ? 'fa-calendar-xmark' : ($key === 'room_swap' ? 'fa-right-left' : 'fa-calendar-check') }}" aria-hidden="true"></i>{{ $label }}
                            </a>
                        @endforeach
                    </nav>

                    <form class="audit-filters" method="GET" action="{{ route('audit-logs.index') }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div><label for="audit-search">Search</label><input id="audit-search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Action, reason, or room"></div>
                        <div><label for="audit-room">Room</label><input id="audit-room" name="room" value="{{ $filters['room'] ?? '' }}" placeholder="Room name"></div>
                        <div><label for="audit-actor">Actor</label><input id="audit-actor" name="actor" value="{{ $filters['actor'] ?? '' }}" placeholder="Name or role"></div>
                        <div><label for="audit-status">Status</label><select id="audit-status" name="status"><option value="">Any status</option>@foreach($statusOptions as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>@endforeach</select></div>
                        <div><label for="audit-term">Academic term</label><select id="audit-term" name="academic_term_id"><option value="">Any term</option>@foreach($academicTermOptions as $term)<option value="{{ $term['id'] }}" @selected((string) ($filters['academic_term_id'] ?? '') === (string) $term['id'])>{{ $term['label'] }}</option>@endforeach</select></div>
                        <div><label for="audit-from">From</label><input id="audit-from" type="date" name="from" value="{{ $filters['from'] ?? '' }}"></div>
                        <div><label for="audit-to">To</label><input id="audit-to" type="date" name="to" value="{{ $filters['to'] ?? '' }}"></div>
                        <button type="submit"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>&nbsp; Filter</button>
                        <a class="audit-clear" href="{{ route('audit-logs.index', ['type' => $type]) }}">Clear</a>
                    </form>

                    @if($logs->isEmpty())
                        <div class="audit-empty"><i class="fa-regular fa-folder-open" aria-hidden="true"></i><strong>No matching records</strong><div>Try a different date, room, actor, or status filter.</div></div>
                    @else
                        <div class="audit-table-wrap">
                            <table class="audit-table">
                                <thead><tr><th>Date &amp; time</th><th>Action</th><th>Academic term</th><th>Room</th><th>Actor</th><th>Status</th><th>Actions</th></tr></thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>{{ $log->occurred_at?->format('M j, Y') }}<span class="audit-sub">{{ $log->occurred_at?->format('g:i:s A') }}</span></td>
                                            <td><a class="audit-action" href="{{ route('audit-logs.show', $log) }}">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</a><span class="audit-sub">{{ $log->description ?: $log->reason ?: 'Saved action' }}</span></td>
                                            <td>{{ $log->academic_term_label ?: 'Term not recorded' }}</td>
                                            <td>{{ $log->room?->room_name ?? ($log->snapshot_after['rooms'][0]['name'] ?? 'Room unavailable') }}<span class="audit-sub">{{ $log->room?->department?->name ?? ($log->snapshot_after['rooms'][0]['department'] ?? '') }}</span></td>
                                            <td>{{ $log->actor_name }}<span class="audit-sub">{{ ucfirst($log->actor_role) }}</span></td>
                                            <td><span class="audit-badge">{{ $log->status_after ?? $log->status_before ?? 'Recorded' }}</span></td>
                                            <td>
                                                <div class="audit-row-actions">
                                                    <a class="audit-icon-action" href="{{ route('audit-logs.show', $log) }}" aria-label="Open audit details" title="View details"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                                                    <form class="audit-delete-form" action="{{ route('audit-logs.destroy', $log) }}" method="POST" data-confirm-delete="Permanently delete this {{ str_replace('_', ' ', $log->event_type) }} audit log? This removes the record from the database and cannot be undone.">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="audit-icon-action delete" type="submit" aria-label="Delete {{ str_replace('_', ' ', $log->event_type) }} audit log" title="Delete audit log"><i class="fa-solid fa-trash-can" aria-hidden="true"></i><span class="audit-sr-only">Delete audit log</span></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="audit-pagination">{{ $logs->links() }}</div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</div>
