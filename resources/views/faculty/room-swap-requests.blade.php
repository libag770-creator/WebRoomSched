@include('footerheader.header')

@php
    $pendingCount = $requests->count();
    $sentPendingCount = $sentRequests->where('status', 'pending')->count();
    $sentHistoryCount = $sentRequests->count();
@endphp

<div class="wrapper">
    <div class="main-layout">
        @include('faculty.sidebar')
        <main class="content faculty-room-swap-inbox-view">
            <style>
                body.portal-shell.faculty-portal .main-layout > .content.faculty-room-swap-inbox-view {
                    min-height:100vh;
                    padding:126px clamp(20px,3vw,42px) 46px !important;
                    background:radial-gradient(ellipse at 88% 2%,rgba(215,195,145,.22),transparent 25%),linear-gradient(145deg,#edf2ec 0%,#f8f7f2 58%,#f1f0e8 100%);
                    color:#263a2f;
                    font-family:'DM Sans',Arial,sans-serif;
                }
                .faculty-room-swap-inbox-view * { box-sizing:border-box; }
                .swap-inbox-page { width:min(100%,1440px); margin:0 auto; }
                .swap-inbox-hero { position:relative; isolation:isolate; display:flex; min-height:190px; align-items:center; gap:20px; margin:0 0 19px; padding:clamp(26px,3.8vw,40px); overflow:hidden; border:1px solid rgba(232,215,175,.22); border-radius:23px; background:radial-gradient(circle at 80% 18%,rgba(217,196,143,.17),transparent 25%),linear-gradient(118deg,#153a32 0%,#215440 58%,#37684e 100%); box-shadow:0 20px 44px rgba(24,58,43,.15); }
                .swap-inbox-hero::after { position:absolute; z-index:-1; top:-150px; right:10%; width:340px; height:340px; border:1px solid rgba(244,232,200,.2); border-radius:50%; box-shadow:0 0 0 28px rgba(244,232,200,.035),0 0 0 66px rgba(244,232,200,.025),0 0 0 106px rgba(244,232,200,.018); content:""; }
                .swap-inbox-hero-icon { display:grid; flex:0 0 56px; width:56px; height:56px; place-items:center; border:1px solid rgba(255,255,255,.19); border-radius:16px; background:rgba(255,255,255,.1); color:#e6d39f; font-size:23px; }
                .swap-inbox-hero-copy { position:relative; z-index:1; }
                .swap-inbox-eyebrow { margin:0 0 8px; color:#e2ca8d; font-size:11px; font-weight:850; letter-spacing:.15em; text-transform:uppercase; }
                .swap-inbox-hero h1 { margin:0; color:#fff; font:800 clamp(30px,3vw,40px)/1.15 'Manrope','DM Sans',Arial,sans-serif; letter-spacing:-.04em; }
                .swap-inbox-hero p { max-width:720px; margin:9px 0 0; color:#e0e9e1; font-size:14px; font-weight:550; line-height:1.6; }
                .swap-inbox-hero-link { display:inline-flex; min-height:42px; align-items:center; justify-content:center; gap:8px; margin-left:auto; padding:0 14px; border:1px solid rgba(255,255,255,.28); border-radius:10px; background:rgba(255,255,255,.1); color:#fff; font-size:12px; font-weight:850; text-decoration:none; white-space:nowrap; transition:background .15s ease,transform .15s ease; }
                .swap-inbox-hero-link:hover { transform:translateY(-1px); background:rgba(255,255,255,.18); color:#fff; }
                .swap-inbox-alert { display:flex; align-items:flex-start; gap:10px; margin:0 0 15px; padding:13px 15px; border:1px solid; border-radius:12px; font-size:13px; font-weight:700; line-height:1.5; }
                .swap-inbox-alert.success { border-color:#b9d9bd; background:#edf7ee; color:#28603a; }
                .swap-inbox-alert.error { border-color:#edc8c2; background:#fff3f1; color:#8f3e36; }
                .swap-inbox-metrics { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin:0 0 19px; }
                .swap-inbox-metric { display:flex; min-width:0; align-items:center; gap:12px; padding:14px 16px; border:1px solid #dce6dc; border-radius:14px; background:rgba(255,255,255,.92); box-shadow:0 5px 16px rgba(31,57,43,.04); }
                .swap-inbox-metric-icon { display:grid; flex:0 0 38px; width:38px; height:38px; place-items:center; border-radius:11px; background:#edf4e9; color:#2d6848; font-size:15px; }
                .swap-inbox-metric strong { display:block; color:#203d30; font-size:19px; font-weight:900; line-height:1.1; }
                .swap-inbox-metric span:last-child { display:block; margin-top:4px; color:#68776d; font-size:11px; font-weight:750; }
                .swap-inbox-section-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:12px; margin:0 0 11px; }
                .swap-inbox-section-heading h2 { margin:0; color:#203d30; font:800 20px/1.3 'Manrope','DM Sans',Arial,sans-serif; }
                .swap-inbox-section-heading p { margin:4px 0 0; color:#68776d; font-size:12px; line-height:1.5; }
                .swap-inbox-section-count { display:inline-flex; min-height:27px; align-items:center; padding:0 9px; border:1px solid #dbe6da; border-radius:999px; background:#f7faf5; color:#42624d; font-size:11px; font-weight:850; white-space:nowrap; }
                .swap-request-tabs { display:flex; gap:8px; margin:0 0 18px; padding:6px; border:1px solid #dce6dc; border-radius:14px; background:rgba(255,255,255,.86); box-shadow:0 5px 16px rgba(31,57,43,.04); }
                .swap-request-tab { display:inline-flex; min-height:42px; align-items:center; justify-content:center; gap:9px; padding:0 15px; border:1px solid transparent; border-radius:10px; background:transparent; color:#617166; font:inherit; font-size:12px; font-weight:850; cursor:pointer; transition:background .16s ease,color .16s ease; }
                .swap-request-tab span { display:inline-grid; min-width:22px; height:22px; place-items:center; padding:0 6px; border-radius:999px; background:#edf1eb; color:#506454; font-size:10px; }
                .swap-request-tab:hover { background:#f3f7f0; color:#245940; }
                .swap-request-tab.is-active { background:#245940; color:#fff; box-shadow:0 4px 12px rgba(36,89,64,.16); }
                .swap-request-tab.is-active span { background:rgba(255,255,255,.18); color:#fff; }
                .swap-request-panel[hidden] { display:none !important; }
                .sent-swap-card.status-pending { border-left:4px solid #d89a24; }
                .sent-swap-card.status-approved { border-left:4px solid #448156; }
                .sent-swap-card.status-expired,.sent-swap-card.status-cancelled { border-left:4px solid #8b9690; }
                .sent-swap-card.status-declined { border-left:4px solid #c45a52; }
                .swap-inbox-badge.swap-status-approved { border-color:#c8e0ca; background:#eef8ef; color:#316943; }
                .swap-inbox-badge.swap-status-declined { border-color:#edcfca; background:#fff1ef; color:#9a3e36; }
                .swap-inbox-badge.swap-status-expired,.swap-inbox-badge.swap-status-cancelled { border-color:#dce2dd; background:#f2f4f2; color:#626d65; }
                .sent-decline-note { border-left-color:#c45a52; background:#fff4f2; color:#79433e; }
                .sent-swap-actions { display:flex; justify-content:flex-end; margin-top:12px; }
                .sent-swap-actions form { margin:0; }
                .sent-swap-actions button { display:inline-flex; min-height:38px; align-items:center; justify-content:center; gap:7px; padding:0 13px; border:1px solid #e2c7c3; border-radius:9px; background:#fff5f5; color:#934941; font:inherit; font-size:11px; font-weight:850; cursor:pointer; }
                .sent-swap-actions button:hover { background:#ffe9e6; border-color:#d8a7a0; }
                .swap-inbox-empty .swap-inbox-hero-link { margin:8px 0 0; border-color:#245940; background:#245940; color:#fff; }
                .swap-inbox-list { display:grid; gap:13px; }
                .swap-inbox-card { min-width:0; overflow:hidden; border:1px solid #dce6dc; border-radius:17px; background:#fff; box-shadow:0 7px 22px rgba(31,57,43,.055); }
                .swap-inbox-card-head { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; padding:17px 19px; border-bottom:1px solid #e8eee6; background:linear-gradient(110deg,#f7faf5,#fff); }
                .swap-inbox-sender { display:flex; min-width:0; align-items:center; gap:12px; }
                .swap-inbox-avatar { display:grid; flex:0 0 42px; width:42px; height:42px; place-items:center; border:1px solid #dce8d9; border-radius:13px; background:#eaf2e7; color:#2b6041; font-size:16px; }
                .swap-inbox-sender-copy { min-width:0; }
                .swap-inbox-sender-copy small { display:block; margin-bottom:3px; color:#758278; font-size:10px; font-weight:850; letter-spacing:.08em; text-transform:uppercase; }
                .swap-inbox-sender-copy h3 { margin:0; overflow-wrap:anywhere; color:#203d30; font-size:16px; font-weight:850; line-height:1.35; }
                .swap-inbox-badges { display:flex; flex:0 0 auto; flex-wrap:wrap; justify-content:flex-end; gap:7px; }
                .swap-inbox-badge { display:inline-flex; min-height:27px; align-items:center; gap:6px; padding:0 9px; border:1px solid #dce7da; border-radius:999px; background:#f3f7f0; color:#42624d; font-size:10px; font-weight:850; white-space:nowrap; }
                .swap-inbox-badge.pending { border-color:#efdfb7; background:#fff8e8; color:#866b35; }
                .swap-inbox-badge.permanent { border-color:#d8e0f0; background:#f1f5fc; color:#45608a; }
                .swap-inbox-card-body { padding:17px 19px; }
                .swap-inbox-class-line { display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin:0 0 13px; }
                .swap-inbox-class-line strong { display:block; color:#245940; font-size:14px; font-weight:900; }
                .swap-inbox-class-line span { display:block; margin-top:3px; color:#67766c; font-size:12px; font-weight:600; line-height:1.5; }
                .swap-inbox-comparison { display:grid; grid-template-columns:minmax(0,1fr) 38px minmax(0,1fr); align-items:stretch; gap:11px; }
                .swap-inbox-side { min-width:0; padding:13px 14px; border:1px solid #e1e9df; border-radius:13px; background:#f8faf6; }
                .swap-inbox-side.yours { border-color:#dbe6dc; background:#f6f9f5; }
                .swap-inbox-side-label { display:flex; align-items:center; gap:7px; margin:0 0 8px; color:#68776d; font-size:10px; font-weight:900; letter-spacing:.07em; text-transform:uppercase; }
                .swap-inbox-side-label i { color:#34724e; }
                .swap-inbox-room { margin:0; color:#294437; font-size:15px; font-weight:900; line-height:1.35; overflow-wrap:anywhere; }
                .swap-inbox-course { margin:4px 0 0; color:#607166; font-size:12px; font-weight:650; line-height:1.45; }
                .swap-inbox-time { display:flex; flex-wrap:wrap; gap:7px; margin-top:10px; }
                .swap-inbox-time span { display:inline-flex; min-height:27px; align-items:center; gap:6px; padding:0 8px; border:1px solid #e2e9df; border-radius:8px; background:#fff; color:#405a49; font-size:11px; font-weight:800; }
                .swap-inbox-time i { color:#4b7757; }
                .swap-inbox-arrow { display:grid; place-items:center; color:#7c9580; font-size:17px; }
                .swap-inbox-meta { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; margin-top:12px; }
                .swap-inbox-meta-item { min-width:0; padding:10px 12px; border:1px solid #e7ece5; border-radius:10px; background:#fff; }
                .swap-inbox-meta-item small { display:block; margin-bottom:4px; color:#78857b; font-size:10px; font-weight:850; letter-spacing:.05em; text-transform:uppercase; }
                .swap-inbox-meta-item p { margin:0; color:#435a4a; font-size:12px; font-weight:650; line-height:1.5; overflow-wrap:anywhere; }
                .swap-inbox-note { margin:12px 0 0; padding:11px 13px; border-left:3px solid #d7c18b; border-radius:0 9px 9px 0; background:#fbf8ef; color:#5d594a; font-size:12px; line-height:1.55; }
                .swap-inbox-actions { display:flex; justify-content:flex-end; gap:9px; padding:13px 19px; border-top:1px solid #e8eee6; background:#fbfcfa; }
                .swap-inbox-actions button { display:inline-flex; min-height:42px; align-items:center; justify-content:center; gap:8px; padding:0 15px; border:1px solid #245940; border-radius:10px; background:#245940; color:#fff; font-size:12px; font-weight:850; cursor:pointer; transition:background .15s ease,transform .15s ease; }
                .swap-inbox-actions button:hover { transform:translateY(-1px); background:#173b33; }
                .swap-inbox-actions .decline { border-color:#e2c7c3; background:#fff; color:#934941; }
                .swap-inbox-actions .decline:hover { border-color:#a63b36; background:#fff4f2; color:#8c342e; }
                .swap-decline-overlay { position:fixed; inset:0; z-index:10050; display:none; align-items:center; justify-content:center; padding:18px; background:rgba(18,35,28,.56); backdrop-filter:blur(3px); }
                .swap-decline-overlay.is-open { display:flex; }
                .swap-decline-card { width:min(100%,520px); padding:24px; border:1px solid #e0e7de; border-radius:18px; background:#fff; box-shadow:0 24px 70px rgba(0,0,0,.25); }
                .swap-approve-overlay { position:fixed; inset:0; z-index:10051; display:none; align-items:center; justify-content:center; padding:18px; background:rgba(18,35,28,.56); backdrop-filter:blur(3px); }
                .swap-approve-overlay.is-open { display:flex; }
                .swap-approve-card { width:min(100%,520px); padding:24px; border:1px solid #e0e7de; border-radius:18px; background:#fff; box-shadow:0 24px 70px rgba(0,0,0,.25); }
                .swap-action-summary { display:grid; gap:9px; margin:15px 0; padding:13px; border:1px solid #e3eae1; border-radius:11px; background:#f7faf5; }
                .swap-action-summary div { display:grid; gap:3px; }
                .swap-action-summary small { color:#78857b; font-size:10px; font-weight:850; letter-spacing:.06em; text-transform:uppercase; }
                .swap-action-summary strong { color:#294437; font-size:13px; line-height:1.45; overflow-wrap:anywhere; }
                .swap-decline-card h2 { margin:0 0 7px; color:#243c30; font-size:21px; font-weight:850; }
                .swap-decline-card p { margin:0 0 14px; color:#68776d; font-size:13px; line-height:1.55; }
                .swap-decline-card label { display:block; margin:0 0 7px; color:#354b3d; font-size:12px; font-weight:850; }
                .swap-decline-card textarea { display:block; width:100%; min-height:116px; resize:vertical; padding:12px; border:1px solid #cbd8cb; border-radius:10px; color:#243c30; font:inherit; font-size:14px; line-height:1.5; }
                .swap-decline-card textarea:focus { outline:3px solid rgba(36,89,64,.14); border-color:#245940; }
                .swap-decline-error { margin:7px 0 0 !important; color:#a33a32 !important; font-size:12px !important; font-weight:700; }
                .swap-decline-footer { display:flex; justify-content:flex-end; gap:9px; margin-top:17px; }
                .swap-decline-footer button { min-height:41px; padding:0 14px; border:1px solid #d7e0d7; border-radius:9px; background:#fff; color:#40564a; font:inherit; font-size:12px; font-weight:850; cursor:pointer; }
                .swap-decline-footer button[type="submit"] { border-color:#a63b36; background:#a63b36; color:#fff; }
                @media(max-width:560px) { .swap-decline-card { padding:20px; } .swap-decline-footer { flex-direction:column-reverse; } .swap-decline-footer button { width:100%; } }
                .swap-inbox-empty { display:grid; justify-items:center; gap:9px; padding:42px 20px; border:1px dashed #ccd9ca; border-radius:17px; background:rgba(255,255,255,.78); color:#68776d; text-align:center; }
                .swap-inbox-empty i { display:grid; width:52px; height:52px; place-items:center; border-radius:16px; background:#edf4e9; color:#39704e; font-size:21px; }
                .swap-inbox-empty h3 { margin:2px 0 0; color:#294437; font-size:17px; font-weight:850; }
                .swap-inbox-empty p { max-width:420px; margin:0; font-size:13px; line-height:1.55; }
                @media(max-width:760px) {
                    body.portal-shell.faculty-portal .main-layout > .content.faculty-room-swap-inbox-view { min-height:calc(100vh - 64px); padding:22px 17px 32px !important; }
                    .swap-inbox-hero { min-height:0; align-items:flex-start; gap:13px; padding:25px 21px; border-radius:19px; }
                    .swap-inbox-hero-icon { flex-basis:44px; width:44px; height:44px; border-radius:13px; font-size:19px; }
                    .swap-inbox-hero-link { margin-left:0; }
                    .swap-inbox-hero { flex-wrap:wrap; }
                    .swap-inbox-hero-copy { flex:1 1 calc(100% - 58px); }
                    .swap-inbox-hero-link { margin-left:57px; }
                    .swap-inbox-metrics { gap:8px; }
                    .swap-inbox-metric { gap:8px; padding:11px 10px; }
                    .swap-inbox-metric-icon { flex-basis:32px; width:32px; height:32px; font-size:13px; }
                    .swap-inbox-card-head { flex-direction:column; padding:15px; }
                    .swap-inbox-badges { justify-content:flex-start; padding-left:54px; }
                    .swap-inbox-card-body { padding:15px; }
                    .swap-inbox-comparison { grid-template-columns:minmax(0,1fr); gap:7px; }
                    .swap-inbox-arrow { min-height:20px; transform:rotate(90deg); }
                    .swap-inbox-actions { padding:12px 15px; }
                    .swap-inbox-actions form { flex:1 1 0; }
                    .swap-inbox-actions button { width:100%; }
                }
                @media(max-width:480px) {
                    body.portal-shell.faculty-portal .main-layout > .content.faculty-room-swap-inbox-view { padding-right:12px; padding-left:12px; }
                    .swap-inbox-hero { padding:22px 17px; }
                    .swap-inbox-hero h1 { font-size:28px; }
                    .swap-inbox-hero p { font-size:13px; }
                    .swap-inbox-metrics { grid-template-columns:1fr; }
                    .swap-request-tabs { flex-direction:column; }
                    .swap-request-tab { justify-content:flex-start; }
                    .swap-inbox-metric { min-height:58px; }
                    .swap-inbox-section-heading h2 { font-size:18px; }
                    .swap-inbox-class-line { flex-direction:column; }
                    .swap-inbox-meta { grid-template-columns:1fr; }
                    .swap-inbox-actions { align-items:stretch; flex-direction:column; }
                    .swap-inbox-actions form { flex:auto; }
                }
            </style>

            <div class="swap-inbox-page">
                <header class="swap-inbox-hero">
                    <div class="swap-inbox-hero-icon" aria-hidden="true"><i class="fa-solid fa-inbox"></i></div>
                    <div class="swap-inbox-hero-copy">
                        <div class="swap-inbox-eyebrow">Room coordination</div>
                        <h1>Room Swap Requests</h1>
                        <p>Respond to incoming requests and track the status of room swaps you have sent, all in one place.</p>
                    </div>
                    <a href="{{ route('faculty.room-swap') }}" class="swap-inbox-hero-link"><i class="fa-solid fa-plus" aria-hidden="true"></i> Create a request</a>
                </header>

                @if(session('success'))
                    <div class="swap-inbox-alert success" role="status"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>{{ session('success') }}</span></div>
                @endif
                @if(session('error'))
                    <div class="swap-inbox-alert error" role="alert"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i><span>{{ session('error') }}</span></div>
                @endif

                <section class="swap-inbox-metrics" aria-label="Request summary">
                    <div class="swap-inbox-metric"><span class="swap-inbox-metric-icon"><i class="fa-regular fa-envelope" aria-hidden="true"></i></span><div><strong>{{ $pendingCount }}</strong><span>Awaiting your response</span></div></div>
                    <div class="swap-inbox-metric"><span class="swap-inbox-metric-icon"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i></span><div><strong>{{ $sentPendingCount }}</strong><span>Sent, awaiting response</span></div></div>
                    <div class="swap-inbox-metric"><span class="swap-inbox-metric-icon"><i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i></span><div><strong>{{ $sentHistoryCount }}</strong><span>Sent requests and history</span></div></div>
                </section>

                <nav class="swap-request-tabs" role="tablist" aria-label="Room swap request lists">
                    <button type="button" class="swap-request-tab is-active" id="receivedRequestsTab" role="tab" aria-selected="true" aria-controls="receivedRequestsPanel" data-swap-tab="received">
                        <i class="fa-solid fa-inbox" aria-hidden="true"></i> Received <span>{{ $pendingCount }}</span>
                    </button>
                    <button type="button" class="swap-request-tab" id="sentRequestsTab" role="tab" aria-selected="false" aria-controls="sentRequestsPanel" data-swap-tab="sent">
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Sent &amp; history <span>{{ $sentHistoryCount }}</span>
                    </button>
                </nav>

                <section id="receivedRequestsPanel" class="swap-request-panel" role="tabpanel" aria-labelledby="receivedRequestsTab">
                    <div class="swap-inbox-section-heading">
                        <div><h2 id="swapInboxListTitle">Requests needing your response</h2><p>Accept a permanent request to exchange both saved room assignments, or accept a temporary swap for the selected meeting.</p></div>
                        <span class="swap-inbox-section-count">{{ $pendingCount }} {{ \Illuminate\Support\Str::plural('request', $pendingCount) }}</span>
                    </div>

                    @if($requests->isNotEmpty())
                        <div class="swap-inbox-list">
                            @foreach($requests as $swapRequest)
                                @php
                                    $source = $swapRequest->requesterSchedule;
                                    $target = $swapRequest->targetSchedule;
                                    $isPermanent = ($swapRequest->swap_type ?? 'temporary') === 'permanent';
                                    $requesterName = $swapRequest->requester?->name ?? 'Instructor';
                                @endphp
                                <article class="swap-inbox-card">
                                    <header class="swap-inbox-card-head">
                                        <div class="swap-inbox-sender">
                                            <span class="swap-inbox-avatar" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                                            <div class="swap-inbox-sender-copy"><small>Request sent by</small><h3>{{ $requesterName }}</h3></div>
                                        </div>
                                        <div class="swap-inbox-badges">
                                            <span class="swap-inbox-badge pending"><i class="fa-regular fa-hourglass-half" aria-hidden="true"></i> Awaiting response</span>
                                            <span class="swap-inbox-badge {{ $isPermanent ? 'permanent' : '' }}"><i class="fa-solid {{ $isPermanent ? 'fa-right-left' : 'fa-calendar-day' }}" aria-hidden="true"></i> {{ $isPermanent ? 'Permanent swap' : 'Temporary swap' }}</span>
                                        </div>
                                    </header>

                                    <div class="swap-inbox-card-body">
                                        <div class="swap-inbox-class-line">
                                            <div><strong>{{ $target?->course_code ?? 'Your class' }} <span style="display:inline;color:#52665a;font-weight:650">— {{ $target?->subject ?? 'Subject details unavailable' }}</span></strong><span>The other instructor is requesting an exchange with this scheduled class.</span></div>
                                        </div>

                                        <div class="swap-inbox-comparison">
                                            <div class="swap-inbox-side">
                                                <div class="swap-inbox-side-label"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> Requester’s current room</div>
                                                <p class="swap-inbox-room">{{ $swapRequest->requesterRoom?->room_name ?? 'Room unavailable' }}</p>
                                                <p class="swap-inbox-course">{{ $source?->course_code ?? 'Class' }} — {{ $source?->subject ?? 'Subject details unavailable' }}</p>
                                                <div class="swap-inbox-time"><span><i class="fa-regular fa-calendar" aria-hidden="true"></i>{{ $source?->day ?? 'Day unavailable' }}</span><span><i class="fa-regular fa-clock" aria-hidden="true"></i>{{ $source?->time ?? 'Time unavailable' }}</span></div>
                                            </div>
                                            <div class="swap-inbox-arrow" aria-hidden="true"><i class="fa-solid fa-right-left"></i></div>
                                            <div class="swap-inbox-side yours">
                                                <div class="swap-inbox-side-label"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Your current room</div>
                                                <p class="swap-inbox-room">{{ $swapRequest->targetRoom?->room_name ?? 'Room unavailable' }}</p>
                                                <p class="swap-inbox-course">{{ $target?->course_code ?? 'Class' }} — {{ $target?->subject ?? 'Subject details unavailable' }}</p>
                                                <div class="swap-inbox-time"><span><i class="fa-regular fa-calendar" aria-hidden="true"></i>{{ $target?->day ?? 'Day unavailable' }}</span><span><i class="fa-regular fa-clock" aria-hidden="true"></i>{{ $target?->time ?? 'Time unavailable' }}</span></div>
                                            </div>
                                        </div>

                                        <div class="swap-inbox-meta">
                                            <div class="swap-inbox-meta-item"><small>Swap arrangement</small><p>{{ $isPermanent ? 'Both saved schedules will exchange rooms after approval.' : 'Only the selected meeting will use the exchanged rooms.' }}</p></div>
                                            @if(!$isPermanent && $swapRequest->swap_date)
                                                <div class="swap-inbox-meta-item"><small>Requested date and time</small><p>{{ $swapRequest->swap_date->format('M j, Y') }}@if($swapRequest->start_time) · {{ \Carbon\Carbon::parse($swapRequest->start_time)->format('g:i A') }}@endif @if($swapRequest->end_time)–{{ \Carbon\Carbon::parse($swapRequest->end_time)->format('g:i A') }}@endif</p></div>
                                            @endif
                                        </div>
                                        @if($swapRequest->reason)
                                            <p class="swap-inbox-note"><strong>Reason:</strong> {{ $swapRequest->reason }}</p>
                                        @endif
                                    </div>

                                    <footer class="swap-inbox-actions">
                                        <button type="button" data-approve-id="{{ $swapRequest->id }}" data-approve-url="{{ route('faculty.room-swap.approve', $swapRequest->id) }}" data-permanent="{{ $isPermanent ? 'true' : 'false' }}" data-requester="{{ $requesterName }}" data-requester-class="{{ trim(($source?->course_code ?? 'Class') . ' — ' . ($source?->subject ?? 'Subject unavailable')) }}" data-requester-room="{{ $swapRequest->requesterRoom?->room_name ?? 'Room unavailable' }}" data-target-class="{{ trim(($target?->course_code ?? 'Class') . ' — ' . ($target?->subject ?? 'Subject unavailable')) }}" data-target-room="{{ $swapRequest->targetRoom?->room_name ?? 'Room unavailable' }}" data-swap-when="{{ $isPermanent ? 'Ongoing schedule' : trim(($swapRequest->swap_date?->format('M j, Y') ?? 'Selected date') . ' · ' . ($swapRequest->start_time ? \Carbon\Carbon::parse($swapRequest->start_time)->format('g:i A') : '') . ($swapRequest->end_time ? '–' . \Carbon\Carbon::parse($swapRequest->end_time)->format('g:i A') : '')) }}"><i class="fa-solid fa-check" aria-hidden="true"></i> Accept swap</button>
                                        <button type="button" class="decline" data-decline-id="{{ $swapRequest->id }}" data-decline-url="{{ route('faculty.room-swap.decline', $swapRequest->id) }}" data-requester="{{ $requesterName }}" data-requester-class="{{ trim(($source?->course_code ?? 'Class') . ' — ' . ($source?->subject ?? 'Subject unavailable')) }}" data-requester-room="{{ $swapRequest->requesterRoom?->room_name ?? 'Room unavailable' }}" data-target-class="{{ trim(($target?->course_code ?? 'Class') . ' — ' . ($target?->subject ?? 'Subject unavailable')) }}" data-target-room="{{ $swapRequest->targetRoom?->room_name ?? 'Room unavailable' }}"><i class="fa-solid fa-xmark" aria-hidden="true"></i> Decline</button>
                                    </footer>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="swap-inbox-empty">
                            <i class="fa-regular fa-envelope-open" aria-hidden="true"></i>
                            <h3>You’re all caught up</h3>
                            <p>New room swap requests addressed to you will appear here, with both class schedules and the requested swap details.</p>
                        </div>
                    @endif
                </section>

                <section id="sentRequestsPanel" class="swap-request-panel" role="tabpanel" aria-labelledby="sentRequestsTab" hidden>
                    <div class="swap-inbox-section-heading">
                        <div><h2>Requests you sent</h2><p>Track pending requests, completed swaps, and decisions from the other instructor.</p></div>
                        <span class="swap-inbox-section-count">{{ $sentHistoryCount }} {{ \Illuminate\Support\Str::plural('request', $sentHistoryCount) }}</span>
                    </div>
                    @if($sentRequests->isNotEmpty())
                        <div class="swap-inbox-list">
                            @foreach($sentRequests as $sentRequest)
                                @php
                                    $sentStatus = strtolower((string) $sentRequest->status);
                                    $sentPermanent = ($sentRequest->swap_type ?? 'temporary') === 'permanent';
                                    $sentSchedule = $sentRequest->requesterSchedule;
                                    $sentDay = $sentSchedule?->day ?? $sentRequest->targetSchedule?->day ?? 'Day unavailable';
                                    $sentTime = $sentSchedule?->time ?? $sentRequest->targetSchedule?->time ?? 'Time unavailable';
                                    $statusLabel = match ($sentStatus) {
                                        'pending' => 'Awaiting response',
                                        'approved' => 'Accepted',
                                        'expired' => 'Completed',
                                        'declined' => 'Declined',
                                        'cancelled' => 'Cancelled',
                                        default => ucfirst($sentStatus ?: 'Unknown'),
                                    };
                                @endphp
                                <article class="swap-inbox-card sent-swap-card status-{{ $sentStatus }}">
                                    <header class="swap-inbox-card-head">
                                        <div class="swap-inbox-sender">
                                            <span class="swap-inbox-avatar" aria-hidden="true"><i class="fa-solid fa-paper-plane"></i></span>
                                            <div class="swap-inbox-sender-copy"><small>Request sent to</small><h3>{{ $sentRequest->targetUser?->name ?? 'Instructor unavailable' }}</h3></div>
                                        </div>
                                        <div class="swap-inbox-badges">
                                            <span class="swap-inbox-badge swap-status-{{ $sentStatus }}"><i class="fa-solid {{ $sentStatus === 'pending' ? 'fa-hourglass-half' : ($sentStatus === 'approved' ? 'fa-check' : 'fa-circle-info') }}" aria-hidden="true"></i> {{ $statusLabel }}</span>
                                            <span class="swap-inbox-badge {{ $sentPermanent ? 'permanent' : '' }}"><i class="fa-solid {{ $sentPermanent ? 'fa-right-left' : 'fa-calendar-day' }}" aria-hidden="true"></i> {{ $sentPermanent ? 'Permanent swap' : 'Temporary swap' }}</span>
                                        </div>
                                    </header>
                                    <div class="swap-inbox-card-body">
                                        <div class="swap-inbox-class-line">
                                            <div><strong>{{ $sentSchedule?->course_code ?? 'Your class' }} <span style="display:inline;color:#52665a;font-weight:650">— {{ $sentSchedule?->subject ?? 'Subject details unavailable' }}</span></strong><span>{{ $sentPermanent ? 'Recurring room change for both saved schedules.' : 'Room change for the selected meeting only.' }}</span></div>
                                        </div>
                                        <div class="swap-inbox-comparison">
                                            <div class="swap-inbox-side"><div class="swap-inbox-side-label"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Your current room</div><p class="swap-inbox-room">{{ $sentRequest->requesterRoom?->room_name ?? 'Room unavailable' }}</p><p class="swap-inbox-course">{{ $sentSchedule?->course_code ?? 'Class' }} — {{ $sentSchedule?->subject ?? 'Subject details unavailable' }}</p><div class="swap-inbox-time"><span><i class="fa-regular fa-calendar" aria-hidden="true"></i>{{ $sentDay }}</span><span><i class="fa-regular fa-clock" aria-hidden="true"></i>{{ $sentTime }}</span></div></div>
                                            <div class="swap-inbox-arrow" aria-hidden="true"><i class="fa-solid fa-right-left"></i></div>
                                            <div class="swap-inbox-side yours"><div class="swap-inbox-side-label"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Requested room</div><p class="swap-inbox-room">{{ $sentRequest->targetRoom?->room_name ?? 'Room unavailable' }}</p><p class="swap-inbox-course">{{ $sentRequest->targetSchedule?->course_code ?? 'Other instructor’s class' }} — {{ $sentRequest->targetSchedule?->subject ?? 'Subject details unavailable' }}</p></div>
                                        </div>
                                        <div class="swap-inbox-meta">
                                            <div class="swap-inbox-meta-item"><small>Swap arrangement</small><p>{{ $sentPermanent ? ($sentStatus === 'approved' ? 'Both saved schedules now use their exchanged rooms permanently.' : 'The room assignments exchange permanently after acceptance.') : ($sentStatus === 'expired' ? 'The temporary swap ended and the original rooms were restored.' : 'Only the selected meeting uses the exchanged rooms.') }}</p></div>
                                            @if(!$sentPermanent && $sentRequest->swap_date)<div class="swap-inbox-meta-item"><small>Requested date and time</small><p>{{ $sentRequest->swap_date->format('M j, Y') }}@if($sentRequest->start_time) · {{ \Carbon\Carbon::parse($sentRequest->start_time)->format('g:i A') }}@endif @if($sentRequest->end_time)–{{ \Carbon\Carbon::parse($sentRequest->end_time)->format('g:i A') }}@endif</p></div>@endif
                                        </div>
                                        @if($sentRequest->reason)<p class="swap-inbox-note"><strong>Your reason:</strong> {{ $sentRequest->reason }}</p>@endif
                                        @if($sentStatus === 'declined' && $sentRequest->decline_reason)<p class="swap-inbox-note sent-decline-note"><strong>Decline reason:</strong> {{ $sentRequest->decline_reason }}</p>@endif
                                        @if($sentStatus === 'pending')
                                            <div class="sent-swap-actions"><form method="POST" action="{{ route('faculty.room-swap.cancel', $sentRequest->id) }}" onsubmit="return confirm('Cancel this pending room swap request? The other instructor will no longer be able to accept it.');">@csrf<button type="submit"><i class="fa-solid fa-ban" aria-hidden="true"></i> Cancel request</button></form></div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="swap-inbox-empty"><i class="fa-regular fa-paper-plane" aria-hidden="true"></i><h3>No sent requests yet</h3><p>When you send a room swap request, its status and any response will appear here.</p><a href="{{ route('faculty.room-swap') }}" class="swap-inbox-hero-link"><i class="fa-solid fa-plus" aria-hidden="true"></i> Create a request</a></div>
                    @endif
                </section>
            </div>
            <div class="swap-approve-overlay" id="swapApproveOverlay" aria-hidden="true">
                <form class="swap-approve-card" id="swapApproveForm" method="POST" action="">
                    @csrf
                    <h2 id="swapApproveTitle">Accept room swap?</h2>
                    <p id="swapApproveDescription">Review both room assignments before accepting.</p>
                    <div class="swap-action-summary">
                        <div><small>Requester’s class and current room</small><strong id="approveRequesterSummary"></strong></div>
                        <div><small>Your class and current room</small><strong id="approveTargetSummary"></strong></div>
                        <div><small>When</small><strong id="approveWhenSummary"></strong></div>
                    </div>
                    <div class="swap-decline-footer">
                        <button type="button" id="cancelSwapApprove">Go back</button>
                        <button type="submit" id="confirmSwapApprove"><i class="fa-solid fa-check" aria-hidden="true"></i> Confirm acceptance</button>
                    </div>
                </form>
            </div>
            <div class="swap-decline-overlay" id="swapDeclineOverlay" aria-hidden="true">
                <form class="swap-decline-card" id="swapDeclineForm" method="POST" action="">
                    @csrf
                    <input type="hidden" id="declineRequestId" name="decline_request_id" value="{{ old('decline_request_id') }}">
                    <h2>Decline room swap request?</h2>
                    <p>Review the request and enter a reason. The instructor who sent it will receive your reason with the decline notice.</p>
                    <div class="swap-action-summary">
                        <div><small>Requester’s class and current room</small><strong id="declineRequesterSummary"></strong></div>
                        <div><small>Your class and current room</small><strong id="declineTargetSummary"></strong></div>
                    </div>
                    <label for="declineReasonInput">Reason for declining <span aria-hidden="true">*</span></label>
                    <textarea id="declineReasonInput" name="decline_reason" minlength="10" maxlength="1000" required placeholder="Explain why you cannot accept this room swap (at least 10 characters).">{{ old('decline_reason') }}</textarea>
                    @error('decline_reason')<p class="swap-decline-error" role="alert">{{ $message }}</p>@enderror
                    <div class="swap-decline-footer">
                        <button type="button" id="cancelSwapDecline">Go back</button>
                        <button type="submit"><i class="fa-solid fa-xmark" aria-hidden="true"></i> Confirm decline</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
<script>
(() => {
    const tabs = Array.from(document.querySelectorAll('[data-swap-tab]'));
    const panels = {
        received: document.getElementById('receivedRequestsPanel'),
        sent: document.getElementById('sentRequestsPanel')
    };
    const activateTab = name => {
        tabs.forEach(tab => {
            const active = tab.dataset.swapTab === name;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        Object.entries(panels).forEach(([key, panel]) => { if (panel) panel.hidden = key !== name; });
    };
    tabs.forEach(tab => tab.addEventListener('click', () => activateTab(tab.dataset.swapTab)));

    const declineOverlay = document.getElementById('swapDeclineOverlay');
    const declineForm = document.getElementById('swapDeclineForm');
    const reason = document.getElementById('declineReasonInput');
    const requestId = document.getElementById('declineRequestId');
    const approveOverlay = document.getElementById('swapApproveOverlay');
    const approveForm = document.getElementById('swapApproveForm');
    const closeDecline = () => { declineOverlay?.classList.remove('is-open'); declineOverlay?.setAttribute('aria-hidden', 'true'); };
    const closeApprove = () => { approveOverlay?.classList.remove('is-open'); approveOverlay?.setAttribute('aria-hidden', 'true'); };
    const fillSummary = (button, prefix) => {
        document.getElementById(prefix + 'RequesterSummary').textContent = `${button.dataset.requesterClass} · ${button.dataset.requesterRoom}`;
        document.getElementById(prefix + 'TargetSummary').textContent = `${button.dataset.targetClass} · ${button.dataset.targetRoom}`;
    };
    document.querySelectorAll('[data-approve-url]').forEach(button => button.addEventListener('click', () => {
        approveForm.action = button.dataset.approveUrl;
        fillSummary(button, 'approve');
        document.getElementById('approveWhenSummary').textContent = button.dataset.swapWhen || 'Selected schedule';
        const permanent = button.dataset.permanent === 'true';
        document.getElementById('swapApproveTitle').textContent = permanent ? 'Accept permanent room swap?' : 'Accept temporary room swap?';
        document.getElementById('swapApproveDescription').textContent = permanent
            ? 'Both selected schedules will exchange rooms permanently. Their merged class details and full scheduled time move with each schedule.'
            : 'The selected meeting will use the exchanged rooms for the approved date and time.';
        document.getElementById('confirmSwapApprove').innerHTML = `<i class="fa-solid fa-check" aria-hidden="true"></i> ${permanent ? 'Confirm permanent swap' : 'Confirm acceptance'}`;
        approveOverlay.classList.add('is-open');
        approveOverlay.setAttribute('aria-hidden', 'false');
        setTimeout(() => document.getElementById('confirmSwapApprove')?.focus(), 0);
    }));
    document.querySelectorAll('[data-decline-url]').forEach(button => button.addEventListener('click', () => {
        declineForm.action = button.dataset.declineUrl;
        requestId.value = button.dataset.declineId;
        fillSummary(button, 'decline');
        declineOverlay.classList.add('is-open');
        declineOverlay.setAttribute('aria-hidden', 'false');
        setTimeout(() => reason?.focus(), 0);
    }));
    document.getElementById('cancelSwapDecline')?.addEventListener('click', closeDecline);
    document.getElementById('cancelSwapApprove')?.addEventListener('click', closeApprove);
    declineOverlay?.addEventListener('click', event => { if (event.target === declineOverlay) closeDecline(); });
    approveOverlay?.addEventListener('click', event => { if (event.target === approveOverlay) closeApprove(); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') { closeDecline(); closeApprove(); } });
    @if($errors->has('decline_reason'))
        @if(old('decline_request_id'))
            declineForm.action = @json(route('faculty.room-swap.decline', old('decline_request_id')));
            const failedDeclineButton = Array.from(document.querySelectorAll('[data-decline-id]'))
                .find(button => button.dataset.declineId === @json((string) old('decline_request_id')));
            if (failedDeclineButton) fillSummary(failedDeclineButton, 'decline');
        @endif
        declineOverlay?.classList.add('is-open');
        declineOverlay?.setAttribute('aria-hidden', 'false');
        setTimeout(() => reason?.focus(), 0);
    @endif
})();
</script>
