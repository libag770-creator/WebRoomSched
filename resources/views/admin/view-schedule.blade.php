@include('footerheader.header')
<div class="wrapper">
    <div class="main-layout">
        @include('admin.sidebar')
        <main class="content">
            <style>
                .admin-room-page{max-width:1500px;margin:0 auto;padding:20px 12px 42px;color:#24392b}
                .admin-room-hero{display:flex;align-items:center;gap:18px;padding:25px;margin-bottom:20px;background:#fff;border:1px solid #d8e2da;border-radius:16px;box-shadow:0 3px 14px rgba(25,55,37,.065)}
                .admin-room-icon{display:grid;place-items:center;flex:0 0 54px;width:54px;height:54px;border-radius:14px;background:#eaf2ec;color:#276747;font-size:22px}
                .admin-room-hero h1{margin:0;color:#20352a;font-size:clamp(28px,3vw,36px);line-height:1.2}
                .admin-room-hero p{margin:6px 0 0;color:#56685c;font-size:16px}
                .admin-room-panel{padding:22px;background:#fff;border:1px solid #d8e2da;border-radius:14px;box-shadow:0 3px 14px rgba(25,55,37,.065)}
                .admin-room-meta{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:18px}
                .admin-room-meta span{padding:9px 12px;border-radius:9px;background:#f1f6f2;color:#304b39;font-size:15px;font-weight:650}
                .admin-room-table-wrap{overflow:auto;border:1px solid #d8e2da;border-radius:12px}
                .admin-room-table{width:100%;min-width:780px;border-collapse:collapse}
                .admin-room-table th,.admin-room-table td{padding:13px 14px;border-bottom:1px solid #dce5de;text-align:left;color:#34483b;font-size:15px;line-height:1.5;vertical-align:top}
                .admin-room-table th{background:#edf3ee;color:#244633;font-size:14px;font-weight:800}
                .admin-room-empty{padding:26px;text-align:center;color:#56685c;font-size:16px}
                .admin-room-back{display:inline-flex;align-items:center;gap:8px;min-height:44px;margin:0 0 16px;padding:9px 14px;border:1px solid #cbd9ce;border-radius:9px;background:#edf3ee;color:#254634;text-decoration:none;font-weight:700}
            </style>
            <div class="admin-room-page">
                <a class="admin-room-back" href="{{ route('admin.schedules') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to schedules</a>
                <section class="admin-room-hero">
                    <span class="admin-room-icon"><i class="fa-solid fa-door-open" aria-hidden="true"></i></span>
                    <div><h1>{{ $room->room_name ?? 'Room schedule' }}</h1><p>View room availability and scheduled classes.</p></div>
                </section>
                <section class="admin-room-panel">
                    <div class="admin-room-meta">
                        <span>Building: {{ is_object($room->building) ? ($room->building->name ?? $room->building->building_name ?? '—') : ($room->building ?? '—') }}</span>
                        <span>Department: {{ $room->department?->name ?? '—' }}</span>
                        @if($activeTerm)<span>{{ $activeTerm->academic_year }} · {{ $activeTerm->semester }}</span>@endif
                    </div>
                    @if(collect($schedules)->isNotEmpty())
                        <div class="admin-room-table-wrap"><table class="admin-room-table"><thead><tr><th>Day</th><th>Time</th><th>Course code</th><th>Subject</th><th>Program / year</th><th>Faculty</th></tr></thead><tbody>
                            @foreach($schedules as $schedule)
                                <tr><td>{{ $schedule->day ?? '—' }}</td><td>{{ $schedule->time ?? '—' }}</td><td><strong>{{ $schedule->course_code ?? '—' }}</strong></td><td>{{ $schedule->subject ?? '—' }}</td><td>{{ $schedule->program ?? '—' }}{{ $schedule->year_level ? ' · ' . $schedule->year_level : '' }}</td><td>{{ $schedule->instructor ?? $schedule->faculty?->name ?? '—' }}</td></tr>
                            @endforeach
                        </tbody></table></div>
                    @else
                        <div class="admin-room-empty">No scheduled classes were found for this room in the selected term.</div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</div>
@include('footerheader.footer')
