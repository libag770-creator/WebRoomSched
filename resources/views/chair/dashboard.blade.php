@include('footerheader.header')

@php
    $chairUser = auth()->user();
    $chairDepartment = collect($departments ?? [])->firstWhere('id', $chairUser?->department_id);
    $departmentRoomCount = (int) ($chairDepartment?->rooms_count ?? 0);
    $campusDepartmentCount = collect($departments ?? [])->count();
    $campusRoomCount = (int) collect($departments ?? [])->sum('rooms_count');
    $pendingMergeCount = collect($mergeRequests ?? [])->where('status', 'pending')->count();
    $swapRequestCount = collect($swapRequests ?? [])->count();
@endphp

<div class="wrapper">
    <div class="main-layout">
        @include('chair.sidebar')

        <main class="content chair-dashboard">
            <section class="chair-dashboard-hero">
                <div class="chair-hero-copy">
                    <span class="chair-eyebrow"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> DEPARTMENT WORKSPACE</span>
                    <h1>Welcome back, {{ $chairUser?->name ?? 'Chair' }}</h1>
                    <p>Manage schedules, faculty, subjects, and department requests from one place.</p>
                    <div class="chair-hero-meta">
                        <span><i class="fa-solid fa-building" aria-hidden="true"></i> {{ $chairDepartment?->name ?? 'Your department' }}</span>
                        <span><i class="fa-regular fa-calendar" aria-hidden="true"></i> {{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
                <div class="chair-hero-actions">
                    <a href="{{ route('chair.setschedule') }}" class="chair-primary-action">
                        <i class="fa-solid fa-calendar-plus" aria-hidden="true"></i> Set a schedule
                    </a>
                    <a href="{{ route('chair.schedule.library') }}" class="chair-secondary-action">
                        <i class="fa-solid fa-box-archive" aria-hidden="true"></i> Schedule library
                    </a>
                </div>
                <div class="chair-hero-mark" aria-hidden="true">
                    @if($chairDepartment?->logo)
                        <img src="{{ asset('storage/' . $chairDepartment->logo) }}" alt="" onerror="this.remove()">
                    @else
                        <i class="fa-solid fa-calendar-check"></i>
                    @endif
                </div>
            </section>

            <section class="chair-metric-grid" aria-label="Department overview">
                <article class="chair-metric-card">
                    <span class="chair-metric-icon green"><i class="fa-solid fa-door-open" aria-hidden="true"></i></span>
                    <div class="chair-metric-label">Rooms in your department</div>
                    <div class="chair-metric-value">{{ $departmentRoomCount }}</div>
                    <div class="chair-metric-note">Assigned to {{ $chairDepartment?->name ?? 'your department' }}</div>
                </article>
                <article class="chair-metric-card">
                    <span class="chair-metric-icon blue"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                    <div class="chair-metric-label">Campus departments</div>
                    <div class="chair-metric-value">{{ $campusDepartmentCount }}</div>
                    <div class="chair-metric-note">{{ $campusRoomCount }} rooms across campus</div>
                </article>
                <article class="chair-metric-card">
                    <span class="chair-metric-icon amber"><i class="fa-solid fa-code-branch" aria-hidden="true"></i></span>
                    <div class="chair-metric-label">Pending merge requests</div>
                    <div class="chair-metric-value">{{ $pendingMergeCount }}</div>
                    <div class="chair-metric-note">Waiting for your review</div>
                </article>
                <article class="chair-metric-card">
                    <span class="chair-metric-icon violet"><i class="fa-solid fa-right-left" aria-hidden="true"></i></span>
                    <div class="chair-metric-label">Room swap activity</div>
                    <div class="chair-metric-value">{{ $swapRequestCount }}</div>
                    <div class="chair-metric-note">Requests recorded in the system</div>
                </article>
            </section>

            <section class="chair-workspace-grid">
                <div class="chair-main-column">
                    <section class="chair-panel chair-requests-panel">
                        <header class="chair-panel-header">
                            <div>
                                <span class="chair-panel-kicker">REQUEST CENTER</span>
                                <h2>Faculty requests &amp; activity</h2>
                                <p>Review cross-department merge requests and follow room swap activity.</p>
                            </div>
                            <span class="chair-panel-icon"><i class="fa-solid fa-inbox" aria-hidden="true"></i></span>
                        </header>

                        <div class="chair-tabs" role="tablist" aria-label="Faculty request categories">
                            <button type="button" class="chair-tab active" id="chairMergeTab" role="tab" aria-selected="true" aria-controls="chairMergePanel" data-tab="chairMergePanel">
                                <i class="fa-solid fa-code-branch" aria-hidden="true"></i>
                                Merge requests
                                @if($pendingMergeCount > 0)<span class="chair-tab-count">{{ $pendingMergeCount }}</span>@endif
                            </button>
                            <button type="button" class="chair-tab" id="chairSwapsTab" role="tab" aria-selected="false" aria-controls="chairSwapsPanel" data-tab="chairSwapsPanel" tabindex="-1">
                                <i class="fa-solid fa-right-left" aria-hidden="true"></i> Room swaps
                                @if($swapRequestCount > 0)<span class="chair-tab-count muted">{{ $swapRequestCount }}</span>@endif
                            </button>
                        </div>

                        <div class="chair-tab-panel" id="chairMergePanel" role="tabpanel" aria-labelledby="chairMergeTab">
                            @if(collect($mergeRequests ?? [])->isNotEmpty())
                                <div class="chair-request-list">
                                    @foreach($mergeRequests as $merge)
                                        @php
                                            $mergeStatus = strtolower((string) ($merge->status ?? 'pending'));
                                            $courseCode = $merge->facultySubject?->course_code ?? $merge->schedule?->course_code ?? 'Course request';
                                            $existingProgram = $merge->schedule?->program ?? 'N/A';
                                            $requestedProgram = $merge->facultySubject?->program ?? 'N/A';
                                        @endphp
                                        <article class="chair-request-card">
                                            <div class="chair-request-heading">
                                                <span class="chair-request-icon"><i class="fa-solid fa-code-branch" aria-hidden="true"></i></span>
                                                <div class="chair-request-title">
                                                    <h3>{{ $courseCode }}</h3>
                                                    <p>{{ $merge->schedule?->subject ?? 'Subject not specified' }}</p>
                                                </div>
                                                <span class="chair-status {{ $mergeStatus }}">{{ ucfirst($mergeStatus) }}</span>
                                            </div>

                                            <div class="chair-request-details">
                                                <div><span>Current program</span><strong>{{ $existingProgram }}</strong></div>
                                                <div><span>Requested program</span><strong>{{ $requestedProgram }}</strong></div>
                                                <div><span>Requesting department</span><strong>{{ $merge->department?->name ?? 'N/A' }}</strong></div>
                                                <div><span>Room</span><strong>{{ $merge->schedule?->room?->room_name ?? 'N/A' }}</strong></div>
                                                <div><span>Instructor</span><strong>{{ $merge->schedule?->instructor ?? 'N/A' }}</strong></div>
                                                <div><span>Time</span><strong>{{ $merge->schedule?->time ?? 'N/A' }}</strong></div>
                                            </div>

                                            @if($mergeStatus === 'pending')
                                                <div class="chair-request-actions">
                                                    <form action="{{ route('chair.merge.approve', $merge->id) }}" method="POST" onsubmit="return confirm('Approve this merge request?');">
                                                        @csrf
                                                        <button type="submit" class="chair-approve"><i class="fa-solid fa-check" aria-hidden="true"></i> Approve request</button>
                                                    </form>
                                                    <form action="{{ route('chair.merge.reject', $merge->id) }}" method="POST" onsubmit="return confirm('Decline this merge request?');">
                                                        @csrf
                                                        <button type="submit" class="chair-decline"><i class="fa-solid fa-xmark" aria-hidden="true"></i> Decline</button>
                                                    </form>
                                                </div>
                                            @elseif($mergeStatus === 'approved')
                                                <div class="chair-request-actions">
                                                    <span class="chair-approved-note"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Merge is active</span>
                                                    <form action="{{ route('chair.merge.remove', $merge->id) }}" method="POST" onsubmit="return confirm('Remove this merged program? The original class will remain unchanged.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="chair-decline"><i class="fa-solid fa-link-slash" aria-hidden="true"></i> Remove merge</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="chair-empty-state">
                                    <span><i class="fa-solid fa-inbox" aria-hidden="true"></i></span>
                                    <h3>No merge requests to review</h3>
                                    <p>New requests from other departments will appear here.</p>
                                </div>
                            @endif
                        </div>

                        <div class="chair-tab-panel" id="chairSwapsPanel" role="tabpanel" aria-labelledby="chairSwapsTab" hidden>
                            @if(collect($swapRequests ?? [])->isNotEmpty())
                                <div class="chair-swap-list">
                                    @foreach($swapRequests as $swap)
                                        @php
                                            $swapStatus = strtolower((string) ($swap->status ?? 'pending'));
                                            $swapDate = $swap->swap_date?->format('M j, Y') ?? 'Date not specified';
                                            $swapTime = ($swap->start_time ? \Carbon\Carbon::parse($swap->start_time)->format('g:i A') : '—')
                                                . ' – ' . ($swap->end_time ? \Carbon\Carbon::parse($swap->end_time)->format('g:i A') : '—');
                                        @endphp
                                        <article class="chair-swap-row">
                                            <span class="chair-swap-icon"><i class="fa-solid fa-right-left" aria-hidden="true"></i></span>
                                            <div class="chair-swap-content">
                                                <div class="chair-swap-topline">
                                                    <strong>{{ $swap->requester?->name ?? 'Faculty member' }}</strong>
                                                    <span class="chair-status {{ $swapStatus }}">{{ ucfirst($swapStatus) }}</span>
                                                </div>
                                                <p>{{ $swap->requesterRoom?->room_name ?? 'Room unavailable' }} <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i> {{ $swap->targetRoom?->room_name ?? 'Room unavailable' }}</p>
                                                <div class="chair-swap-meta">
                                                    <span><i class="fa-regular fa-calendar" aria-hidden="true"></i> {{ $swapDate }}</span>
                                                    <span><i class="fa-regular fa-clock" aria-hidden="true"></i> {{ $swapTime }}</span>
                                                    <span><i class="fa-solid fa-user" aria-hidden="true"></i> With {{ $swap->targetUser?->name ?? 'Faculty member' }}</span>
                                                </div>
                                                @if($swap->reason)
                                                    <div class="chair-swap-reason">{{ $swap->reason }}</div>
                                                @endif
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="chair-empty-state">
                                    <span><i class="fa-solid fa-right-left" aria-hidden="true"></i></span>
                                    <h3>No room swap activity yet</h3>
                                    <p>Faculty room swap requests will be listed here.</p>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>

                <aside class="chair-side-column">
                    <section class="chair-panel chair-department-panel">
                        <header class="chair-panel-header compact">
                            <div>
                                <span class="chair-panel-kicker">CAMPUS DIRECTORY</span>
                                <h2>Departments</h2>
                            </div>
                            <span class="chair-panel-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                        </header>

                        @if(collect($departments ?? [])->isNotEmpty())
                            <div class="chair-department-list">
                                @foreach($departments as $department)
                                    <div class="chair-department-row {{ (int) $department->id === (int) ($chairUser?->department_id ?? 0) ? 'current' : '' }}">
                                        <span class="chair-department-mark"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                                        <span class="chair-department-name">
                                            <strong>{{ $department->name }}</strong>
                                            <small>{{ (int) $department->rooms_count }} {{ (int) $department->rooms_count === 1 ? 'room' : 'rooms' }}</small>
                                        </span>
                                        @if((int) $department->id === (int) ($chairUser?->department_id ?? 0))
                                            <span class="chair-current-label">Your department</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="chair-empty-inline">No departments have been added yet.</div>
                        @endif
                    </section>

                    <section class="chair-panel chair-tools-panel">
                        <header class="chair-panel-header compact">
                            <div>
                                <span class="chair-panel-kicker">WORKSPACE</span>
                                <h2>Department tools</h2>
                            </div>
                        </header>
                        <a class="chair-tool-link" href="{{ route('chair.setFaculty') }}">
                            <span class="chair-tool-icon"><i class="fa-solid fa-user-gear" aria-hidden="true"></i></span>
                            <span><strong>Faculty &amp; subjects</strong><small>Manage teaching assignments</small></span>
                            <i class="fa-solid fa-chevron-right chair-tool-chevron" aria-hidden="true"></i>
                        </a>
                        <a class="chair-tool-link" href="{{ route('chair.managesubjects') }}">
                            <span class="chair-tool-icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span>
                            <span><strong>Manage Subjects</strong><small>Set up programs and subject catalogues</small></span>
                            <i class="fa-solid fa-chevron-right chair-tool-chevron" aria-hidden="true"></i>
                        </a>
                        <a class="chair-tool-link" href="{{ route('chair.drafts') }}">
                            <span class="chair-tool-icon"><i class="fa-solid fa-file-pen" aria-hidden="true"></i></span>
                            <span><strong>Schedule drafts</strong><small>Continue unpublished work</small></span>
                            <i class="fa-solid fa-chevron-right chair-tool-chevron" aria-hidden="true"></i>
                        </a>
                    </section>
                </aside>
            </section>
        </main>
    </div>
</div>

<style>
    .chair-dashboard { --chair-green:#176e48; --chair-ink:#17251e; --chair-muted:#7b8980; --chair-line:#e4ebe6; }
    .chair-dashboard-hero { position:relative; display:flex; align-items:center; justify-content:space-between; gap:28px; overflow:hidden; min-height:208px; margin:0 0 22px; padding:30px 34px; border:1px solid #dfeae2; border-radius:22px; background:linear-gradient(120deg,#fff 2%,#f4faf5 65%,#eaf5ed 100%); box-shadow:0 14px 34px rgba(21,45,32,.055); }
    .chair-hero-copy { position:relative; z-index:1; max-width:650px; }
    .chair-eyebrow,.chair-panel-kicker { display:inline-flex; align-items:center; gap:7px; color:#71857a; font-size:9px; font-weight:800; letter-spacing:.14em; }
    .chair-eyebrow i { color:var(--chair-green); }
    .chair-hero-copy h1 { margin:12px 0 8px; color:var(--chair-ink); font-size:clamp(25px,3vw,36px); font-weight:780; letter-spacing:-.045em; line-height:1.15; }
    .chair-hero-copy > p { margin:0; color:#718078; font-size:13px; line-height:1.65; }
    .chair-hero-meta { display:flex; flex-wrap:wrap; gap:9px 19px; margin-top:20px; color:#627168; font-size:11px; font-weight:650; }
    .chair-hero-meta span { display:inline-flex; align-items:center; gap:7px; }
    .chair-hero-meta i { color:#438765; }
    .chair-hero-actions { position:relative; z-index:2; display:grid; gap:9px; min-width:168px; }
    .chair-primary-action,.chair-secondary-action { display:flex; min-height:42px; align-items:center; justify-content:center; gap:8px; padding:0 14px; border:1px solid transparent; border-radius:11px; font-size:11px; font-weight:750; text-decoration:none; transition:transform .16s ease,box-shadow .16s ease,background .16s ease; }
    .chair-primary-action { background:#176e48; color:#fff; box-shadow:0 7px 15px rgba(23,110,72,.17); }
    .chair-primary-action:hover { transform:translateY(-1px); box-shadow:0 10px 19px rgba(23,110,72,.22); color:#fff; }
    .chair-secondary-action { border-color:#dce9df; background:rgba(255,255,255,.8); color:#38634a; }
    .chair-secondary-action:hover { background:#fff; color:#176e48; }
    .chair-hero-mark { position:absolute; right:185px; top:50%; transform:translateY(-50%) rotate(-10deg); color:rgba(23,110,72,.055); font-size:144px; pointer-events:none; }
    .chair-metric-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:13px; margin-bottom:22px; }
    .chair-metric-card { position:relative; min-height:139px; padding:17px 18px; border:1px solid var(--chair-line); border-radius:16px; background:#fff; box-shadow:0 8px 24px rgba(21,45,32,.035); }
    .chair-metric-icon { position:absolute; top:16px; right:16px; display:grid; width:34px; height:34px; place-items:center; border-radius:11px; font-size:13px; }
    .chair-metric-icon.green { background:#eaf5ed;color:#218152; }.chair-metric-icon.blue { background:#edf4fb;color:#4b78a8; }.chair-metric-icon.amber { background:#fff5e3;color:#b9872e; }.chair-metric-icon.violet { background:#f3effb;color:#8066ad; }
    .chair-metric-label { max-width:calc(100% - 36px); color:#77857c; font-size:10px; font-weight:700; line-height:1.4; }
    .chair-metric-value { margin-top:15px; color:var(--chair-ink); font-size:29px; font-weight:780; letter-spacing:-.04em; line-height:1; }
    .chair-metric-note { margin-top:8px; color:#9aa49d; font-size:9px; line-height:1.4; }
    .chair-workspace-grid { display:grid; grid-template-columns:minmax(0,1.65fr) minmax(285px,.85fr); align-items:start; gap:17px; }
    .chair-main-column,.chair-side-column { display:grid; gap:17px; min-width:0; }
    .chair-panel { overflow:hidden; border:1px solid var(--chair-line); border-radius:17px; background:#fff; box-shadow:0 9px 26px rgba(21,45,32,.04); }
    .chair-panel-header { display:flex; align-items:center; justify-content:space-between; gap:15px; padding:20px 22px; border-bottom:1px solid #edf1ee; }
    .chair-panel-header h2 { margin:6px 0 4px; color:var(--chair-ink); font-size:17px; font-weight:750; letter-spacing:-.025em; }
    .chair-panel-header p { margin:0; color:#859188; font-size:10px; line-height:1.5; }
    .chair-panel-header.compact { padding:18px 19px; }
    .chair-panel-header.compact h2 { margin-bottom:0; }
    .chair-panel-icon { display:grid; width:37px; height:37px; flex:0 0 37px; place-items:center; border-radius:12px; background:#edf6ef; color:#367b53; font-size:14px; }
    .chair-tabs { display:flex; gap:7px; padding:13px 18px 0; border-bottom:1px solid #edf1ee; }
    .chair-tab { display:inline-flex; min-height:39px; align-items:center; gap:8px; padding:0 12px; border:1px solid transparent; border-bottom:0; border-radius:10px 10px 0 0; background:transparent; color:#75837a; font-size:10px; font-weight:700; cursor:pointer; }
    .chair-tab:hover { background:#f7faf8; color:#256b47; }
    .chair-tab.active { border-color:#e5eee7; background:#f6faf7; color:#176e48; }
    .chair-tab-count { display:inline-grid; min-width:18px; height:18px; place-items:center; padding:0 5px; border-radius:999px; background:#fff0d6; color:#956917; font-size:9px; font-weight:800; }
    .chair-tab-count.muted { background:#edf2ef; color:#6d7a71; }
    .chair-tab-panel[hidden] { display:none !important; }
    .chair-request-list,.chair-swap-list { display:grid; gap:11px; padding:15px; }
    .chair-request-card { padding:16px; border:1px solid #e8eee9; border-radius:14px; background:#fff; }
    .chair-request-heading { display:flex; align-items:center; gap:10px; }
    .chair-request-icon,.chair-swap-icon { display:grid; width:35px; height:35px; flex:0 0 35px; place-items:center; border-radius:11px; background:#f0f6f1; color:#43845d; font-size:13px; }
    .chair-request-title { min-width:0; flex:1; }
    .chair-request-title h3 { margin:0; color:#243a2d; font-size:13px; font-weight:750; }
    .chair-request-title p { margin:4px 0 0; color:#87938b; font-size:10px; }
    .chair-status { display:inline-flex; align-items:center; padding:5px 9px; border-radius:999px; background:#edf2ef; color:#647269; font-size:9px; font-weight:800; white-space:nowrap; }
    .chair-status.pending { background:#fff4dc; color:#946811; }.chair-status.approved { background:#eaf5ed; color:#23734a; }.chair-status.declined,.chair-status.rejected { background:#fff0ee; color:#a7463b; }.chair-status.expired { background:#f0f2f1; color:#748078; }
    .chair-request-details { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; margin-top:15px; padding:13px; border-radius:11px; background:#f8faf8; }
    .chair-request-details div { display:grid; gap:4px; min-width:0; }
    .chair-request-details span { color:#929d95; font-size:9px; }
    .chair-request-details strong { overflow:hidden; color:#45564b; font-size:10px; font-weight:700; text-overflow:ellipsis; white-space:nowrap; }
    .chair-request-actions { display:flex; align-items:center; justify-content:flex-end; gap:8px; margin-top:13px; }
    .chair-request-actions form { margin:0; }
    .chair-approve,.chair-decline { display:inline-flex; min-height:34px; align-items:center; justify-content:center; gap:6px; padding:0 11px; border:1px solid transparent; border-radius:9px; font-size:9px; font-weight:750; cursor:pointer; }
    .chair-approve { background:#176e48; color:#fff; }.chair-approve:hover { background:#0d5d3a; }
    .chair-decline { border-color:#e8eae8; background:#fff; color:#67756c; }.chair-decline:hover { border-color:#f0d7d4; background:#fff7f6; color:#a7463b; }
    .chair-approved-note { margin-right:auto; color:#338058; font-size:10px; font-weight:700; }
    .chair-empty-state { display:grid; justify-items:center; padding:43px 20px 48px; text-align:center; }
    .chair-empty-state > span { display:grid; width:47px; height:47px; place-items:center; border-radius:15px; background:#f1f6f2; color:#699579; font-size:17px; }
    .chair-empty-state h3 { margin:13px 0 5px; color:#394b40; font-size:13px; }
    .chair-empty-state p { margin:0; color:#909b93; font-size:10px; }
    .chair-swap-row { display:flex; align-items:flex-start; gap:11px; padding:14px; border:1px solid #e8eee9; border-radius:13px; }
    .chair-swap-icon { background:#f3f0fa; color:#8169a5; }
    .chair-swap-content { min-width:0; flex:1; }
    .chair-swap-topline { display:flex; align-items:center; justify-content:space-between; gap:10px; }
    .chair-swap-topline strong { color:#2d4034; font-size:11px; }
    .chair-swap-content > p { margin:7px 0 9px; color:#57675d; font-size:11px; font-weight:700; }
    .chair-swap-content > p i { margin:0 5px; color:#8b9990; font-size:9px; }
    .chair-swap-meta { display:flex; flex-wrap:wrap; gap:7px 13px; color:#89958d; font-size:9px; }
    .chair-swap-meta span { display:inline-flex; align-items:center; gap:5px; }
    .chair-swap-meta i { color:#73927e; }
    .chair-swap-reason { margin-top:9px; padding:8px 10px; border-radius:9px; background:#f8faf8; color:#78857d; font-size:9px; line-height:1.5; }
    .chair-department-list { display:grid; padding:7px 12px 12px; }
    .chair-department-row { display:flex; align-items:center; gap:10px; min-width:0; padding:11px 7px; border-bottom:1px solid #f0f3f1; }
    .chair-department-row:last-child { border-bottom:0; }
    .chair-department-row.current { margin:3px 0; padding:11px 8px; border:1px solid #e2eee5; border-radius:11px; background:#f4faf5; }
    .chair-department-mark { display:grid; width:31px; height:31px; flex:0 0 31px; place-items:center; border-radius:9px; background:#f2f6f3; color:#5c886b; font-size:11px; }
    .chair-department-name { display:grid; gap:3px; min-width:0; flex:1; }
    .chair-department-name strong { overflow:hidden; color:#405247; font-size:10px; text-overflow:ellipsis; white-space:nowrap; }
    .chair-department-name small { color:#929d95; font-size:9px; }
    .chair-current-label { color:#51815f; font-size:8px; font-weight:750; white-space:nowrap; }
    .chair-empty-inline { padding:21px; color:#89958d; text-align:center; font-size:10px; }
    .chair-tools-panel { padding-bottom:7px; }
    .chair-tool-link { display:flex; align-items:center; gap:10px; margin:0 11px; padding:11px 8px; border-bottom:1px solid #f0f3f1; text-decoration:none; }
    .chair-tool-link:last-child { border-bottom:0; }
    .chair-tool-icon { display:grid; width:32px; height:32px; flex:0 0 32px; place-items:center; border-radius:10px; background:#f1f6f2; color:#48835d; font-size:12px; }
    .chair-tool-link > span:nth-child(2) { display:grid; gap:3px; min-width:0; flex:1; }
    .chair-tool-link strong { color:#3c4f43; font-size:10px; }
    .chair-tool-link small { color:#929d95; font-size:8px; }
    .chair-tool-chevron { color:#aab4ad; font-size:9px; }
    .chair-tool-link:hover { background:#f8fbf8; }
    .chair-tool-link:hover strong,.chair-tool-link:hover .chair-tool-chevron { color:#176e48; }
    @media(max-width:1100px) { .chair-metric-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }.chair-workspace-grid { grid-template-columns:minmax(0,1fr); }.chair-side-column { grid-template-columns:repeat(2,minmax(0,1fr)); align-items:start; } }
    @media(max-width:760px) { .chair-dashboard-hero { align-items:flex-start; flex-direction:column; padding:24px; }.chair-hero-actions { width:100%; grid-template-columns:repeat(2,minmax(0,1fr)); }.chair-hero-mark { right:20px; top:22px; transform:none; font-size:80px; }.chair-side-column { grid-template-columns:1fr; } }
    @media(max-width:560px) { .chair-metric-grid { grid-template-columns:1fr 1fr; gap:9px; }.chair-metric-card { min-height:126px; padding:14px; }.chair-metric-icon { top:12px;right:12px;width:30px;height:30px; }.chair-request-details { grid-template-columns:repeat(2,minmax(0,1fr)); }.chair-request-actions { align-items:stretch; flex-direction:column; }.chair-request-actions form,.chair-approve,.chair-decline { width:100%; }.chair-approved-note { margin:0 auto 3px 0; }.chair-tabs { padding-left:10px;padding-right:10px; }.chair-tab { gap:6px;padding:0 8px;font-size:9px; }.chair-panel-header { padding:17px; }.chair-request-list,.chair-swap-list { padding:10px; }.chair-request-card { padding:12px; }.chair-current-label { display:none; } }
</style>

<style>
    /* Chair dashboard: clear white and blue neumorphic workspace. */
    .chair-dashboard {
        --chair-green:#315f9b;
        --chair-ink:#1e2d43;
        --chair-muted:#52647b;
        --chair-line:#dbe4ef;
        color:var(--chair-ink);
        font-size:16px;
    }
    .chair-dashboard-hero,
    .chair-metric-card,
    .chair-panel {
        border:1px solid #dbe4ef !important;
        background:linear-gradient(145deg,#ffffff,#f0f4fa) !important;
        box-shadow:8px 8px 18px #dfe5ed,-7px -7px 18px #ffffff,inset 1px 1px 0 rgba(255,255,255,.9) !important;
    }
    .chair-dashboard-hero {
        min-height:225px;
        gap:25px;
        padding:clamp(24px,3vw,36px);
        border-radius:22px;
        box-shadow:10px 10px 22px #dce3ec,-8px -8px 20px #ffffff,inset 1px 1px 0 #fff !important;
    }
    .chair-hero-copy { max-width:660px; }
    .chair-eyebrow,.chair-panel-kicker {
        color:#355d8f !important;
        font-size:13px !important;
        letter-spacing:.09em;
    }
    .chair-eyebrow i { color:#315f9b !important; }
    .chair-hero-copy h1 {
        color:#1d2b40 !important;
        font-size:clamp(30px,3vw,40px) !important;
        line-height:1.2;
    }
    .chair-hero-copy > p { color:#52647b !important; font-size:17px !important; line-height:1.65; }
    .chair-hero-meta { margin-top:20px; color:#344b68 !important; font-size:15px !important; }
    .chair-hero-meta i { color:#315f9b !important; }
    .chair-hero-actions { min-width:205px; }
    .chair-primary-action,.chair-secondary-action {
        min-height:50px;
        border-radius:12px;
        font-size:15px !important;
        box-shadow:5px 5px 11px #dce3ec,-4px -4px 10px #fff,inset 1px 1px 0 rgba(255,255,255,.4) !important;
        transition:transform .18s ease,box-shadow .18s ease,background .18s ease;
    }
    .chair-primary-action { background:#315f9b !important; color:#fff !important; }
    .chair-primary-action:hover { background:#284f83 !important; color:#fff !important; }
    .chair-secondary-action { border-color:#cbd8e8; background:#edf2f8; color:#294b75; }
    .chair-secondary-action:hover { background:#e4ebf5; color:#1f477a; }
    .chair-hero-mark {
        z-index:0;
        right:190px;
        display:grid;
        width:175px;
        height:175px;
        place-items:center;
        overflow:hidden;
        border:1px solid rgba(49,95,155,.12);
        border-radius:50%;
        background:rgba(232,239,249,.62);
        color:rgba(49,95,155,.11);
        box-shadow:inset 5px 5px 12px rgba(205,216,231,.55),inset -5px -5px 12px rgba(255,255,255,.8);
    }
    .chair-hero-mark > i { font-size:108px; }
    .chair-hero-mark > img { width:100%; height:100%; padding:18px; object-fit:contain; opacity:.72; mix-blend-mode:multiply; }
    .chair-metric-grid { gap:16px; margin-bottom:24px; }
    .chair-metric-card { min-height:158px; padding:21px; border-radius:17px; transition:transform .18s ease,box-shadow .18s ease; }
    .chair-metric-card:hover { transform:translateY(-2px); box-shadow:10px 10px 21px #dce3ec,-8px -8px 20px #fff,inset 1px 1px 0 #fff !important; }
    .chair-metric-icon.green,.chair-metric-icon.blue,.chair-metric-icon.amber,.chair-metric-icon.violet {
        border:1px solid #d6e1ef;
        background:#eaf0f8;
        color:#315f9b;
        box-shadow:inset 3px 3px 7px #dce4ef,inset -3px -3px 7px #fff !important;
    }
    .chair-metric-label { color:#354c69 !important; font-size:15px !important; line-height:1.5; }
    .chair-metric-value { margin-top:18px; color:#1d2b40 !important; font-size:36px !important; }
    .chair-metric-note { color:#52647b !important; font-size:14px !important; line-height:1.5; }
    .chair-workspace-grid { gap:19px; }
    .chair-main-column,.chair-side-column { gap:19px; }
    .chair-panel { border-radius:17px; }
    .chair-panel-header { padding:21px 23px; border-color:#dce4ef !important; }
    .chair-panel-header h2 { color:#1e3049 !important; font-size:21px !important; }
    .chair-panel-header p { color:#52647b !important; font-size:14px !important; }
    .chair-panel-header.compact { padding:19px 20px; }
    .chair-panel-icon { background:#e8eef7; color:#315f9b; box-shadow:inset 3px 3px 7px #dce4ef,inset -3px -3px 7px #fff; }
    .chair-tabs { gap:9px; border-color:#dce4ef !important; }
    .chair-tab { min-height:46px; padding:0 15px; color:#435a77 !important; font-size:15px !important; }
    .chair-tab:hover,.chair-tab.active { border-color:#d5dfec; background:#edf2f8; color:#244d82 !important; }
    .chair-tab.active { box-shadow:inset 3px 3px 7px #dfe5ed,inset -3px -3px 7px #fff; }
    .chair-tab-count,.chair-tab-count.muted { min-width:23px; height:23px; background:#e2ebf7; color:#244d82; font-size:13px; }
    .chair-request-list,.chair-swap-list { gap:13px; padding:17px; }
    .chair-request-card,.chair-swap-row {
        border:1px solid #dce4ef !important;
        border-radius:15px;
        background:linear-gradient(145deg,#f8faff,#eef3f9) !important;
        box-shadow:5px 5px 12px #e0e6ee,-5px -5px 12px #fff,inset 1px 1px 0 #fff;
    }
    .chair-request-card { padding:18px; }
    .chair-request-heading { gap:13px; }
    .chair-request-icon,.chair-swap-icon {
        width:42px; height:42px; flex-basis:42px;
        border:1px solid #d6e1ef;
        background:#e8eef7;
        color:#315f9b;
        font-size:16px;
        box-shadow:inset 3px 3px 7px #dce4ef,inset -3px -3px 7px #fff;
    }
    .chair-request-title h3 { color:#203650 !important; font-size:18px !important; }
    .chair-request-title p { color:#52647b !important; font-size:14px !important; line-height:1.5; }
    .chair-status { padding:6px 10px; background:#e9eef5; color:#40536d; font-size:13px !important; }
    .chair-status.pending { background:#fff1d9; color:#755016; }
    .chair-status.approved { background:#e4f2e9; color:#205d3d; }
    .chair-status.declined,.chair-status.rejected { background:#fde9e8; color:#8f302e; }
    .chair-status.expired { background:#edf0f4; color:#52647b; }
    .chair-request-details { gap:13px; margin-top:17px; padding:15px; border:1px solid #dce4ef; background:#eef3f9 !important; box-shadow:inset 4px 4px 9px #e1e7ef,inset -4px -4px 9px #fff; }
    .chair-request-details span { color:#52647b !important; font-size:13px !important; }
    .chair-request-details strong { color:#263d5a !important; font-size:14px !important; }
    .chair-request-actions { gap:10px; margin-top:15px; }
    .chair-approve,.chair-decline { min-height:44px; padding:0 15px; border-radius:10px; font-size:14px !important; }
    .chair-approve { background:#315f9b; color:#fff; box-shadow:4px 4px 9px #dce3ec,-3px -3px 8px #fff; }
    .chair-approve:hover { background:#284f83; }
    .chair-decline { border-color:#cbd7e5; background:#edf2f8; color:#394f6d; box-shadow:4px 4px 9px #dce3ec,-3px -3px 8px #fff; }
    .chair-decline:hover { border-color:#e3b9b7; background:#fff1f0; color:#8f302e; }
    .chair-approved-note { color:#205d3d !important; font-size:14px !important; }
    .chair-empty-state > span { background:#e8eef7; color:#315f9b; box-shadow:inset 3px 3px 7px #dce4ef,inset -3px -3px 7px #fff; }
    .chair-empty-state h3 { color:#203650 !important; font-size:19px !important; }
    .chair-empty-state p { color:#52647b !important; font-size:15px !important; }
    .chair-swap-row { padding:16px; }
    .chair-swap-topline strong { color:#203650 !important; font-size:16px !important; }
    .chair-swap-content > p { color:#344b68 !important; font-size:15px !important; }
    .chair-swap-content > p i,.chair-swap-meta i { color:#315f9b !important; }
    .chair-swap-meta { color:#52647b !important; font-size:14px !important; }
    .chair-swap-reason { border:1px solid #dce4ef; background:#eef3f9 !important; color:#394f6d !important; font-size:14px !important; line-height:1.55; box-shadow:inset 3px 3px 7px #e1e7ef,inset -3px -3px 7px #fff; }
    .chair-department-list { padding:9px 14px 14px; }
    .chair-department-row { gap:12px; padding:13px 8px; border-color:#e3e9f1 !important; }
    .chair-department-row.current { border-color:#cbd9eb !important; background:#edf3fb !important; box-shadow:inset 4px 4px 9px #e1e7ef,inset -4px -4px 9px #fff; }
    .chair-department-mark,.chair-tool-icon { background:#e8eef7; color:#315f9b; box-shadow:inset 3px 3px 7px #dce4ef,inset -3px -3px 7px #fff; }
    .chair-department-name strong,.chair-tool-link strong { color:#263d5a !important; font-size:15px !important; }
    .chair-department-name small,.chair-tool-link small { color:#52647b !important; font-size:13px !important; }
    .chair-current-label { color:#244f88 !important; font-size:12px !important; }
    .chair-empty-inline { color:#52647b !important; font-size:14px !important; }
    .chair-tools-panel { padding-bottom:9px; }
    .chair-tool-link { gap:12px; margin:0 13px; padding:13px 9px; border-color:#e3e9f1 !important; border-radius:10px; }
    .chair-tool-link:hover { background:#eef3f9 !important; }
    .chair-tool-link:hover strong,.chair-tool-link:hover .chair-tool-chevron { color:#244f88 !important; }
    .chair-tool-chevron { color:#647791; font-size:12px; }
    @media(max-width:760px) {
        .chair-dashboard-hero { gap:20px; padding:23px; }
        .chair-hero-mark { top:22px; right:20px; width:100px; height:100px; transform:none; }
        .chair-hero-mark > i { font-size:68px; }
        .chair-hero-mark > img { padding:12px; }
        .chair-hero-copy { padding-right:90px; }
        .chair-hero-actions { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:560px) {
        .chair-dashboard-hero { padding:20px; }
        .chair-hero-mark { top:15px; right:14px; width:66px; height:66px; }
        .chair-hero-mark > i { font-size:45px; }
        .chair-hero-mark > img { padding:8px; }
        .chair-hero-copy { padding-right:55px; }
        .chair-hero-copy h1 { font-size:27px !important; }
        .chair-hero-copy > p { font-size:15px !important; }
        .chair-hero-meta { flex-direction:column; gap:8px; font-size:14px !important; }
        .chair-hero-actions { grid-template-columns:1fr; }
        .chair-metric-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
        .chair-metric-card { min-height:142px; padding:14px; }
        .chair-metric-label { font-size:14px !important; }
        .chair-metric-value { font-size:31px !important; }
        .chair-metric-note { font-size:12px !important; }
        .chair-metric-icon { top:11px; right:11px; width:30px; height:30px; }
        .chair-request-details { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .chair-request-actions { align-items:stretch; flex-direction:column; }
        .chair-request-actions form,.chair-approve,.chair-decline { width:100%; }
        .chair-approved-note { margin:0 auto 3px 0; }
        .chair-tabs { padding-right:10px; padding-left:10px; }
        .chair-tab { min-height:44px; gap:6px; padding:0 9px; font-size:13px !important; }
        .chair-panel-header { padding:17px; }
        .chair-request-list,.chair-swap-list { padding:11px; }
        .chair-request-card { padding:13px; }
        .chair-current-label { display:none; }
    }
    @media(prefers-reduced-motion:reduce) {
        .chair-dashboard *, .chair-dashboard *::before, .chair-dashboard *::after { transition:none !important; scroll-behavior:auto !important; }
    }
</style>

<style>
    /* Flat ledger-green palette shared with the Chair sidebar. */
    .chair-dashboard {
        --chair-green: #1f6846;
        --chair-ink: #183526;
        --chair-muted: #52665a;
        --chair-line: #c7d8cc;
        color: var(--chair-ink) !important;
        font-family: Inter, Arial, sans-serif !important;
        font-size: 16px !important;
    }

    .chair-dashboard .chair-dashboard-hero,
    .chair-dashboard .chair-metric-card,
    .chair-dashboard .chair-panel {
        border: 1px solid var(--chair-line) !important;
        border-radius: 16px !important;
        background: #fff !important;
        box-shadow: 0 5px 18px rgba(24, 53, 38, .055) !important;
    }

    .chair-dashboard .chair-dashboard-hero {
        min-height: 218px;
        padding: clamp(24px, 3vw, 34px);
        background: #f5faf6 !important;
        box-shadow: 0 6px 20px rgba(24, 53, 38, .06) !important;
    }

    .chair-dashboard .chair-eyebrow,
    .chair-dashboard .chair-panel-kicker {
        color: #426b53 !important;
        font-size: 13px !important;
        font-weight: 750 !important;
        letter-spacing: .08em !important;
    }

    .chair-dashboard .chair-eyebrow i,
    .chair-dashboard .chair-hero-meta i,
    .chair-dashboard .chair-panel-icon,
    .chair-dashboard .chair-request-icon,
    .chair-dashboard .chair-swap-icon,
    .chair-dashboard .chair-tool-icon,
    .chair-dashboard .chair-department-mark {
        color: #286747 !important;
    }

    .chair-dashboard .chair-hero-copy h1 {
        color: #183526 !important;
        font-size: clamp(30px, 3vw, 40px) !important;
        line-height: 1.2 !important;
    }

    .chair-dashboard .chair-hero-copy > p,
    .chair-dashboard .chair-panel-header p {
        color: #52665a !important;
        font-size: 16px !important;
        line-height: 1.6 !important;
    }

    .chair-dashboard .chair-hero-meta {
        color: #385343 !important;
        font-size: 15px !important;
        line-height: 1.5 !important;
    }

    .chair-dashboard .chair-hero-actions { min-width: 210px; }

    .chair-dashboard .chair-primary-action,
    .chair-dashboard .chair-secondary-action {
        min-height: 48px;
        border: 1px solid #d5e2d8 !important;
        border-radius: 10px !important;
        box-shadow: none !important;
        font-size: 15px !important;
        font-weight: 700 !important;
    }

    .chair-dashboard .chair-primary-action {
        border-color: #1f6846 !important;
        background: #1f6846 !important;
        color: #fff !important;
    }

    .chair-dashboard .chair-primary-action:hover { background: #18583b !important; }

    .chair-dashboard .chair-secondary-action {
        background: #fff !important;
        color: #1c5c3e !important;
    }

    .chair-dashboard .chair-secondary-action:hover { background: #edf5ef !important; }

    .chair-dashboard .chair-hero-mark {
        border: 0 !important;
        background: #e7f1e9 !important;
        box-shadow: none !important;
        color: rgba(31, 104, 70, .12) !important;
    }

    .chair-dashboard .chair-hero-mark > img {
        opacity: .72;
        mix-blend-mode: multiply;
    }

    .chair-dashboard .chair-metric-grid { gap: 15px; }

    .chair-dashboard .chair-metric-card {
        min-height: 150px;
        padding: 19px;
    }

    .chair-dashboard .chair-metric-icon,
    .chair-dashboard .chair-panel-icon,
    .chair-dashboard .chair-request-icon,
    .chair-dashboard .chair-swap-icon,
    .chair-dashboard .chair-tool-icon,
    .chair-dashboard .chair-department-mark,
    .chair-dashboard .chair-empty-state > span {
        border: 0 !important;
        background: #eaf3ec !important;
        box-shadow: none !important;
        color: #286747 !important;
    }

    .chair-dashboard .chair-metric-label {
        color: #3c5545 !important;
        font-size: 14px !important;
        line-height: 1.45 !important;
    }

    .chair-dashboard .chair-metric-value {
        color: #183526 !important;
        font-size: 34px !important;
    }

    .chair-dashboard .chair-metric-note {
        color: #52665a !important;
        font-size: 13px !important;
        line-height: 1.45 !important;
    }

    .chair-dashboard .chair-workspace-grid,
    .chair-dashboard .chair-main-column,
    .chair-dashboard .chair-side-column { gap: 16px; }

    .chair-dashboard .chair-panel-header { border-color: #e4ece6 !important; }

    .chair-dashboard .chair-panel-header h2 {
        color: #183526 !important;
        font-size: 22px !important;
    }

    .chair-dashboard .chair-panel-header.compact h2 { font-size: 20px !important; }

    .chair-dashboard .chair-tabs { border-color: #e4ece6 !important; }

    .chair-dashboard .chair-tab {
        min-height: 44px;
        color: #40594a !important;
        font-size: 15px !important;
    }

    .chair-dashboard .chair-tab:hover,
    .chair-dashboard .chair-tab.active {
        border-color: transparent !important;
        background: #eaf3ec !important;
        color: #174a35 !important;
        box-shadow: none !important;
    }

    .chair-dashboard .chair-tab-count,
    .chair-dashboard .chair-tab-count.muted {
        background: #dcebe0 !important;
        color: #174a35 !important;
        font-size: 13px !important;
    }

    .chair-dashboard .chair-status {
        font-size: 14px !important;
        line-height: 1.35 !important;
    }

    .chair-dashboard .chair-request-card,
    .chair-dashboard .chair-swap-row {
        border: 1px solid #ccdacf !important;
        border-radius: 12px !important;
        background: #fff !important;
        box-shadow: none !important;
    }

    .chair-dashboard .chair-request-title h3,
    .chair-dashboard .chair-swap-topline strong {
        color: #183526 !important;
        font-size: 17px !important;
    }

    .chair-dashboard .chair-request-title p,
    .chair-dashboard .chair-swap-content > p {
        color: #40594a !important;
        font-size: 15px !important;
        line-height: 1.5 !important;
    }

    .chair-dashboard .chair-request-details {
        border: 1px solid #d5e1d8 !important;
        background: #f4f8f5 !important;
        box-shadow: none !important;
    }

    .chair-dashboard .chair-request-details span,
    .chair-dashboard .chair-swap-meta,
    .chair-dashboard .chair-department-name small,
    .chair-dashboard .chair-tool-link small {
        color: #52665a !important;
        font-size: 14px !important;
        line-height: 1.45 !important;
    }

    .chair-dashboard .chair-request-details strong,
    .chair-dashboard .chair-department-name strong,
    .chair-dashboard .chair-tool-link strong {
        color: #263f30 !important;
        font-size: 15px !important;
        line-height: 1.4 !important;
    }

    .chair-dashboard .chair-approve,
    .chair-dashboard .chair-decline {
        min-height: 46px;
        border-radius: 9px !important;
        box-shadow: none !important;
        font-size: 15px !important;
    }

    .chair-dashboard .chair-approve {
        background: #1f6846 !important;
        color: #fff !important;
    }

    .chair-dashboard .chair-decline {
        border-color: #d8e3db !important;
        background: #fff !important;
        color: #41564a !important;
    }

    .chair-dashboard .chair-empty-state h3,
    .chair-dashboard .chair-empty-inline {
        color: #304a38 !important;
        font-size: 15px !important;
    }

    .chair-dashboard .chair-empty-state p,
    .chair-dashboard .chair-current-label {
        color: #52665a !important;
        font-size: 14px !important;
    }

    .chair-dashboard .chair-swap-reason {
        border: 0 !important;
        background: #f4f8f5 !important;
        color: #40594a !important;
        font-size: 14px !important;
        line-height: 1.55 !important;
        box-shadow: none !important;
    }

    .chair-dashboard .chair-department-row { border-color: #d8e3da !important; }

    .chair-dashboard .chair-department-row.current {
        border: 1px solid #c9d9cd !important;
        background: #edf5ef !important;
        box-shadow: none !important;
    }

    .chair-dashboard .chair-tool-link {
        margin: 0 13px 8px !important;
        border: 1px solid #d8e3da !important;
        border-radius: 9px !important;
    }

    .chair-dashboard .chair-tool-link:hover { background: #f2f7f3 !important; }

    .chair-dashboard .chair-tool-link:hover strong,
    .chair-dashboard .chair-tool-link:hover .chair-tool-chevron { color: #1f6846 !important; }

    @media (max-width: 560px) {
        .chair-dashboard .chair-hero-copy h1 { font-size: 27px !important; }
        .chair-dashboard .chair-hero-copy > p { font-size: 15px !important; }
        .chair-dashboard .chair-metric-label { font-size: 14px !important; }
        .chair-dashboard .chair-metric-note { font-size: 14px !important; }
        .chair-dashboard .chair-tab { font-size: 14px !important; }
    }
</style>

@include('footerheader.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = Array.from(document.querySelectorAll('.chair-tab'));
    const panels = Array.from(document.querySelectorAll('.chair-tab-panel'));

    function activateTab(tab) {
        tabs.forEach(function (item) {
            const active = item === tab;
            item.classList.toggle('active', active);
            item.setAttribute('aria-selected', active ? 'true' : 'false');
            item.tabIndex = active ? 0 : -1;
        });

        panels.forEach(function (panel) {
            panel.hidden = panel.id !== tab.dataset.tab;
        });
    }

    tabs.forEach(function (tab, index) {
        tab.addEventListener('click', function () { activateTab(tab); });
        tab.addEventListener('keydown', function (event) {
            if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
            event.preventDefault();
            const direction = event.key === 'ArrowRight' ? 1 : -1;
            const next = tabs[(index + direction + tabs.length) % tabs.length];
            activateTab(next);
            next.focus();
        });
    });
});
</script>

