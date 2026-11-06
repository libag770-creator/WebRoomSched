@include('footerheader.header')
<div class="wrapper">

    

    <div class="main-layout">

        @include('chair.sidebar')

        <main class="content chair-set-schedule chair-workspace-redesign">

            <style>
/* Soft blue neumorphism for the chair room and schedule workspace. */
:root {
    --chair-ink:#1e2d43;
    --chair-text:#344b68;
    --chair-muted:#52647b;
    --chair-blue:#315f9b;
    --chair-blue-deep:#284f83;
    --chair-border:#dbe4ef;
    --chair-shadow:8px 8px 18px #dfe5ed,-7px -7px 18px #ffffff,inset 1px 1px 0 rgba(255,255,255,.92);
    --chair-inset:inset 4px 4px 9px #dfe5ed,inset -4px -4px 9px #ffffff;
}
.chair-set-schedule {
    min-height:100%;
    background:#f1f4f9 !important;
    color:var(--chair-ink) !important;
    font-size:16px;
    line-height:1.55;
}
.chair-set-schedule * { box-sizing:border-box; }
.chair-set-schedule .page { width:100%; max-width:1540px; margin:0 auto; padding:24px clamp(15px,2.2vw,32px) 44px; }
.chair-set-schedule .page-header {
    display:flex; align-items:center; justify-content:space-between; gap:20px;
    min-height:156px; margin:0 0 23px; padding:clamp(23px,3vw,35px);
    border:1px solid var(--chair-border); border-radius:23px;
    background:linear-gradient(145deg,#fff,#eff3fa) !important;
    box-shadow:10px 10px 22px #dce3ec,-8px -8px 20px #ffffff,inset 1px 1px 0 #fff !important;
}
.chair-set-schedule .title-area { display:flex; align-items:center; gap:19px; min-width:0; }
.chair-set-schedule .title-icon {
    display:flex; flex:0 0 62px; align-items:center; justify-content:center; width:62px; height:62px;
    border:1px solid #d4dfed; border-radius:19px; background:#eaf0f8 !important; color:#315f9b !important;
    font-size:25px; box-shadow:inset 4px 4px 9px #dce4ef,inset -4px -4px 9px #fff !important;
}
.chair-set-schedule .title { margin:0; color:#1d2b40 !important; font-size:clamp(31px,3vw,40px); font-weight:850; line-height:1.18; letter-spacing:-.025em; }
.chair-set-schedule .subtitle { margin:8px 0 0; color:#52647b !important; font-size:17px; line-height:1.55; }
.chair-set-schedule .section {
    padding:clamp(18px,2.3vw,28px); border:1px solid var(--chair-border) !important; border-radius:21px;
    background:linear-gradient(145deg,#fff,#f0f4fa) !important;
    color:var(--chair-ink) !important;
    box-shadow:9px 9px 20px #dfe5ed,-8px -8px 19px #fff,inset 1px 1px 0 #fff !important;
}
.chair-set-schedule .department-display {
    display:flex; align-items:center; gap:15px; margin:0 0 24px; padding:16px 19px;
    border:1px solid #d7e1ee; border-radius:17px; background:#eaf0f8 !important; box-shadow:var(--chair-inset);
}
.chair-set-schedule .department-icon {
    display:flex; flex:0 0 50px; align-items:center; justify-content:center; width:50px; height:50px;
    border:1px solid #d5e0ee; border-radius:16px; background:#edf2f9 !important; color:#315f9b !important;
    font-size:20px; box-shadow:5px 5px 10px #d7dfeb,-5px -5px 10px #fff,inset 1px 1px 0 #fff;
}
.chair-set-schedule .department-label { margin-bottom:3px; color:#52647b !important; font-size:14px; font-weight:700; }
.chair-set-schedule .department-name { color:#203650 !important; font-size:21px; font-weight:850; line-height:1.35; overflow-wrap:anywhere; }
.chair-set-schedule .section-header { display:flex; align-items:flex-end; justify-content:space-between; gap:16px; margin:0 0 16px; }
.chair-set-schedule .section-title { display:flex; align-items:center; gap:11px; color:#1e3049 !important; font-size:22px; font-weight:850; line-height:1.35; }
.chair-set-schedule .section-title i { color:#315f9b !important; }
.chair-set-schedule .section-subtitle { margin:6px 0 0; color:#52647b !important; font-size:15px; }
.chair-set-schedule .section-help { color:#52647b !important; font-size:14px; font-weight:650; }
.chair-set-schedule .building-list { display:flex; flex-direction:column; gap:14px; }
.chair-set-schedule .building {
    overflow:hidden; border:1px solid #d8e1ed !important; border-radius:18px; background:linear-gradient(145deg,#f7f9fd,#edf2f9) !important;
    box-shadow:6px 6px 14px #dce3ec,-6px -6px 14px #fff,inset 1px 1px 0 #fff !important;
    transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease;
}
.chair-set-schedule .building.open { border-color:#b9cbe2 !important; box-shadow:inset 4px 4px 9px #dfe5ed,inset -4px -4px 9px #fff !important; }
.chair-set-schedule .building-toggle {
    display:flex; align-items:center; justify-content:space-between; gap:16px; width:100%; min-height:78px; padding:15px 19px;
    border:0; background:transparent !important; color:#203650 !important; text-align:left; cursor:pointer;
    transition:background .18s ease,color .18s ease;
}
.chair-set-schedule .building-toggle:hover,.chair-set-schedule .building.open .building-toggle { background:#eaf0f8 !important; color:#244f88 !important; }
.chair-set-schedule .building-left { display:flex; align-items:center; gap:14px; min-width:0; }
.chair-set-schedule .building-icon {
    display:flex; flex:0 0 46px; align-items:center; justify-content:center; width:46px; height:46px;
    border:1px solid #d6e1ef; border-radius:15px; background:#e9eff8 !important; color:#315f9b !important;
    font-size:18px; box-shadow:inset 3px 3px 7px #dce4ef,inset -3px -3px 7px #fff !important;
}
.chair-set-schedule .building-info { display:flex; flex-direction:column; gap:3px; min-width:0; }
.chair-set-schedule .building-name { color:#203650 !important; font-size:18px; font-weight:800; line-height:1.35; overflow-wrap:anywhere; }
.chair-set-schedule .building-meta { color:#52647b !important; font-size:14px; }
.chair-set-schedule .building-arrow { display:flex; flex:0 0 36px; align-items:center; justify-content:center; width:36px; height:36px; border:1px solid #d7e1ee; border-radius:13px; background:#edf2f9; color:#41638e !important; box-shadow:inset 2px 2px 5px #dfe5ed,inset -2px -2px 5px #fff; transition:transform .22s ease; }
.chair-set-schedule .building.open .building-arrow { transform:rotate(180deg); }
.chair-set-schedule .rooms-container { display:none; padding:17px; border-top:1px solid #dce4ef; background:#e9eef6 !important; }
.chair-set-schedule .building.open .rooms-container { display:block; }
.chair-set-schedule .room-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(min(100%,285px),1fr)); gap:14px; }
.chair-set-schedule .room-card {
    min-width:0; padding:17px; border:1px solid #d6dfec !important; border-radius:18px;
    background:linear-gradient(145deg,#f3f6fc,#e9eff8) !important; color:#263d59 !important;
    box-shadow:7px 7px 15px #d2dbe8,-7px -7px 15px #fff,inset 1px 1px 0 #fff !important;
    transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease;
}
.chair-set-schedule .room-card:hover { transform:translateY(-2px); border-color:#b9cbe2 !important; box-shadow:9px 9px 18px #cdd7e5,-8px -8px 16px #fff,inset 1px 1px 0 #fff !important; }
.chair-set-schedule .room-top { display:flex; align-items:center; gap:12px; min-height:48px; margin-bottom:15px; }
.chair-set-schedule .room-icon { display:flex; flex:0 0 44px; align-items:center; justify-content:center; width:44px; height:44px; border:1px solid #d5e0ee; border-radius:15px; background:#e8eff9 !important; color:#315f9b !important; font-size:19px; box-shadow:inset 3px 3px 7px #dce4ef,inset -3px -3px 7px #fff !important; }
.chair-set-schedule .room-icon i { color:#315f9b !important; opacity:1 !important; }
.chair-set-schedule .room-name { color:#203650 !important; font-size:18px; font-weight:850; line-height:1.35; overflow-wrap:anywhere; }
.chair-set-schedule .room-label { margin-top:3px; color:#52647b !important; font-size:14px; }
.chair-set-schedule .room-features { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; margin:0 0 15px; }
.chair-set-schedule .room-feature { display:flex; align-items:center; gap:9px; min-width:0; min-height:58px; padding:9px 10px; border:1px solid #d8e1ed; border-radius:13px; background:#eaf0f8 !important; color:#344b68 !important; box-shadow:inset 3px 3px 7px #dfe5ed,inset -3px -3px 7px #fff !important; }
.chair-set-schedule .room-feature > i { flex:0 0 19px; color:#315f9b !important; text-align:center; font-size:16px; }
.chair-set-schedule .room-feature > span { display:flex; flex-direction:column; gap:2px; min-width:0; }
.chair-set-schedule .room-feature strong { color:#52647b !important; font-size:12px; font-weight:800; line-height:1.3; }
.chair-set-schedule .room-feature > span > span { color:#233a56 !important; font-size:14px; font-weight:750; line-height:1.35; overflow-wrap:anywhere; }
.chair-set-schedule .room-feature.feature-available { border-color:#cbded3; background:#eaf3ed !important; }
.chair-set-schedule .room-feature.feature-available > i { color:#2e7250 !important; }
.chair-set-schedule .room-feature.feature-unavailable { background:#e8edf4 !important; }
.chair-set-schedule .room-feature.feature-unavailable > i { color:#64748b !important; }
.chair-set-schedule .room-feature.room-purpose { grid-column:1/-1; }
.chair-set-schedule .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:48px; padding:11px 15px; border-radius:14px; font-size:15px; font-weight:800; line-height:1.35; text-align:center; text-decoration:none; box-shadow:5px 5px 11px #cbd4e1,-5px -5px 11px #fff,inset 1px 1px 0 rgba(255,255,255,.35) !important; transition:transform .18s ease,box-shadow .18s ease,background .18s ease; }
.chair-set-schedule .btn:hover { transform:translateY(-1px); }
.chair-set-schedule .btn:focus-visible,.chair-set-schedule button:focus-visible,.chair-set-schedule a:focus-visible,.chair-set-schedule input:focus-visible,.chair-set-schedule select:focus-visible { outline:3px solid rgba(49,95,155,.28); outline-offset:3px; }
.chair-set-schedule .btn.green { width:100%; border:1px solid #315f9b !important; background:#315f9b !important; color:#fff !important; }
.chair-set-schedule .btn.green:hover { background:#284f83 !important; color:#fff !important; }
.chair-set-schedule .permission-box { margin-top:14px; padding:13px 14px; border:1px solid #d8e1ed; border-radius:14px; background:#e8eef7; box-shadow:var(--chair-inset); }
.chair-set-schedule .permission-label { display:flex; align-items:flex-start; gap:10px; color:#344b68 !important; font-size:14px; font-weight:650; line-height:1.5; cursor:pointer; }
.chair-set-schedule .permission-label input { flex:0 0 19px; width:19px; height:19px; margin:1px 0 0; accent-color:#315f9b; cursor:pointer; }
.chair-set-schedule .permission-status { margin:7px 0 0 29px; font-size:13px; font-weight:800; }
.chair-set-schedule .status-on { color:#246344 !important; }
.chair-set-schedule .status-off { color:#52647b !important; }
.chair-set-schedule .empty { padding:24px; border:1px dashed #c6d2e2; border-radius:16px; background:#e9eff8 !important; color:#52647b !important; text-align:center; font-size:16px; line-height:1.6; box-shadow:var(--chair-inset); }
.chair-set-schedule .message { display:flex; align-items:center; gap:10px; margin:0 0 17px; padding:14px 17px; border-radius:14px; font-size:15px; font-weight:700; line-height:1.5; box-shadow:var(--chair-shadow); }
.chair-set-schedule .message.success { border:1px solid #c5ddce; background:#eaf5ee !important; color:#205d3d !important; }
.chair-set-schedule .message.error { border:1px solid #e5c5c4; background:#fff0ef !important; color:#8f302e !important; }
.chair-set-schedule [style*="margin-bottom:18px"][style*="background:#eef8ef"],.chair-set-schedule [style*="margin-bottom:18px"][style*="background: #eef8ef"] { display:flex; gap:16px; padding:17px 19px !important; border:1px solid #d8e1ed !important; border-radius:17px !important; background:#eaf0f8 !important; box-shadow:var(--chair-inset); }
.chair-set-schedule [style*="margin-bottom:18px"] > div:first-child > div:first-child { color:#52647b !important; font-size:13px !important; line-height:1.4; }
.chair-set-schedule [style*="margin-bottom:18px"] > div:first-child > div:nth-child(2) { color:#244b7c !important; font-size:20px !important; line-height:1.4; }
.chair-set-schedule [style*="margin-bottom:18px"] > div:first-child > div:nth-child(3) { color:#52647b !important; font-size:14px !important; line-height:1.5; }
.chair-set-schedule select[aria-label="Choose academic term to activate"] { min-width:min(280px,100%); min-height:48px; padding:9px 13px !important; border:1px solid #cbd8e8 !important; border-radius:12px !important; background:#edf2f9 !important; color:#243850 !important; font-size:15px !important; box-shadow:var(--chair-inset); }
.chair-set-schedule select option { background:#fff; color:#243850; }
.chair-set-schedule a[href*="schedule-library"],.chair-set-schedule a[href*="schedule/library"] { display:inline-flex; align-items:center; justify-content:center; min-height:46px; padding:10px 15px !important; border:1px solid #315f9b !important; border-radius:13px !important; background:#315f9b !important; color:#fff !important; font-size:15px !important; font-weight:800 !important; text-decoration:none; box-shadow:5px 5px 10px #cbd4e1,-5px -5px 10px #fff !important; }
.chair-set-schedule a[href*="schedule-library"]:hover,.chair-set-schedule a[href*="schedule/library"]:hover { background:#284f83 !important; }
@media(max-width:1050px) {
    .chair-set-schedule .page { padding-left:18px; padding-right:18px; }
    .chair-set-schedule .room-grid { grid-template-columns:repeat(auto-fill,minmax(min(100%,260px),1fr)); }
}
@media(max-width:760px) {
    .chair-set-schedule .page { padding:17px 13px 30px; }
    .chair-set-schedule .page-header { align-items:flex-start; padding:22px 20px; }
    .chair-set-schedule .section-header { align-items:flex-start; flex-direction:column; gap:5px; }
    .chair-set-schedule .section-help { font-size:14px; }
    .chair-set-schedule .room-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
}
@media(max-width:560px) {
    .chair-set-schedule .page-header { min-height:0; padding:19px 16px; border-radius:19px; }
    .chair-set-schedule .title-area { align-items:flex-start; gap:12px; }
    .chair-set-schedule .title-icon { flex-basis:48px; width:48px; height:48px; border-radius:15px; font-size:20px; }
    .chair-set-schedule .title { font-size:28px; }
    .chair-set-schedule .subtitle { font-size:15px; }
    .chair-set-schedule .section { padding:16px 13px; }
    .chair-set-schedule .department-display { padding:13px; }
    .chair-set-schedule .department-name { font-size:18px; }
    .chair-set-schedule .section-title { font-size:19px; }
    .chair-set-schedule .building-toggle { min-height:70px; padding:12px; }
    .chair-set-schedule .building-name { font-size:16px; }
    .chair-set-schedule .rooms-container { padding:12px; }
    .chair-set-schedule .room-grid { grid-template-columns:1fr; }
    .chair-set-schedule [style*="margin-bottom:18px"][style*="background:#eef8ef"],.chair-set-schedule [style*="margin-bottom:18px"][style*="background: #eef8ef"] { align-items:stretch; flex-direction:column; }
    .chair-set-schedule [style*="margin-bottom:18px"] > div:nth-child(2),.chair-set-schedule [style*="margin-bottom:18px"] form { width:100%; }
    .chair-set-schedule [style*="margin-bottom:18px"] form { display:flex !important; flex-direction:column; align-items:stretch !important; }
    .chair-set-schedule select[aria-label="Choose academic term to activate"] { width:100%; min-width:0; }
    .chair-set-schedule a[href*="schedule-library"],.chair-set-schedule a[href*="schedule/library"] { width:100%; margin-top:9px; }
}
@media(prefers-reduced-motion:reduce) {
    .chair-set-schedule *, .chair-set-schedule *::before, .chair-set-schedule *::after { scroll-behavior:auto !important; transition-duration:.01ms !important; animation-duration:.01ms !important; }
}
</style>
            <link rel="stylesheet" href="{{ asset('css/chair-workspace.css') }}">



<div class="page-header">

    <div class="title-area">

        <div class="title-icon">

            <i class="fa-solid fa-calendar-days"></i>

        </div>

        <div>

            <h1 class="title">
                Set Schedule
            </h1>

            <div class="subtitle">
                Manage classroom schedules for your department.
            </div>

        </div>

    </div>

</div>

                {{-- SUCCESS MESSAGE --}}

                @if(session('success'))

                    <div class="message success">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- ERROR MESSAGE --}}

                @if(session('error'))

                    <div class="message error">
                        {{ session('error') }}
                    </div>

                @endif


                

                {{-- =============================================================
                     AVAILABLE ACADEMIC TERMS
                ============================================================== --}}

                @php
                    $availableTerms = $availableTerms ?? collect();
                @endphp


                {{-- =============================================================
                     ACTIVE / VIEW ACADEMIC TERM
                ============================================================== --}}

                <div
                    style="
                        margin-bottom:18px;
                        padding:14px 16px;
                        background:#eef8ef;
                        border:1px solid #d3e8d6;
                        border-radius:10px;
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        gap:14px;
                        flex-wrap:wrap;
                    "
                >

                    {{-- =====================================================
                         CURRENT TERM INFORMATION
                    ====================================================== --}}

                    <div>

                        <div
                            style="
                                font-size:11px;
                                color:#667085;
                                text-transform:uppercase;
                                font-weight:800;
                                letter-spacing:.3px;
                            "
                        >
                            Active Academic Term
                        </div>


                        <div
                            style="
                                font-size:18px;
                                color:#176b3a;
                                font-weight:800;
                            "
                        >
                            {{ $activeTerm->academic_year }}
                            •
                            {{ $activeTerm->semester }}
                        </div>


                        <div
                            style="
                                font-size:11px;
                                color:#667085;
                                margin-top:3px;
                            "
                        >
                            All schedules you create here are saved under this active term.
                        </div>

                    </div>


                    {{-- =====================================================
                         TERM SELECTOR
                    ====================================================== --}}

                    <div
                        style="
                            display:flex;
                            gap:8px;
                            align-items:center;
                            flex-wrap:wrap;
                        "
                    >

                        <form
                            method="GET"
                            action="{{ route('chair.setschedule') }}"
                            style="
                                display:flex;
                                gap:7px;
                                align-items:center;
                            "
                        >

                            <select
                                name="term_id"
                                aria-label="Choose academic term to activate"
                                onchange="
                                    this.form.submit();
                                "
                                style="
                                    padding:9px 11px;
                                    border:1px solid #2e7d32;
                                    border-radius:8px;
                                    min-width:230px;
                                    background:#ffffff;
                                    color:#1f2937;
                                    font-size:12px;
                                    font-weight:600;
                                    outline:none;
                                    cursor:pointer;
                                "
                            >

                                <option value="">
                                    Choose term to activate...
                                </option>


                                @foreach($availableTerms as $term)

                                    <option
                                        value="{{ $term->id }}"
                                        {{ (int) $term->id === (int) $activeTerm->id ? 'selected' : '' }}
                                    >

                                        {{ $term->academic_year }}
                                        •
                                        {{ $term->semester }}

                                        @if(
                                            (int) $term->id ===
                                            (int) $activeTerm->id
                                        )
                                            — Active
                                        @endif

                                    </option>

                                @endforeach


                            </select>

                        </form>


                        {{-- SCHEDULE LIBRARY --}}

                        <a
                            href="{{ route('chair.schedule.library') }}"
                            style="
                                padding:9px 12px;
                                border-radius:7px;
                                background:#2e7d32;
                                color:#ffffff;
                                text-decoration:none;
                                font-size:12px;
                                font-weight:700;
                            "
                        >

                            Schedule Library

                        </a>

                    </div>

                </div>


<div class="section">


                    {{-- =====================================================
                         DEPARTMENT
                    ====================================================== --}}

                   <div class="department-display">

    <div class="department-icon">

        <i class="fa-solid fa-building-columns"></i>

    </div>

    <div>

        <div class="department-label">
            Your Department
        </div>

        <div class="department-name">
            {{ $department->name }}
        </div>

    </div>

</div>


                   {{-- =====================================================
     BUILDINGS AND ROOMS
====================================================== --}}

<div class="section-header">

    <div class="section-title">

        <i class="fa-solid fa-building"></i>

        <span>
            Buildings and Rooms
        </span>

    </div>

    <div class="section-help">
        Click a building to view its rooms
    </div>

</div>


<div class="building-list">

    @forelse($buildings as $building)

        @php
            $roomCount = $building->rooms->count();
        @endphp


        <div class="building">


            {{-- =================================================
                 BUILDING HEADER
            ================================================== --}}

            <button
                type="button"
                class="building-toggle"
                onclick="toggleBuilding(this)"
            >

                <div class="building-left">

                    <div class="building-icon">

                        <i class="fa-solid fa-building"></i>

                    </div>


                    <div class="building-info">

                        <div class="building-name">

                            {{ $building->name }}

                        </div>


                        <div class="building-meta">

                            {{ $roomCount }}
                            {{ $roomCount == 1 ? 'room' : 'rooms' }}

                        </div>

                    </div>

                </div>


                <div class="building-arrow">

                    <i class="fa-solid fa-chevron-down"></i>

                </div>

            </button>


            {{-- =================================================
                 ROOMS
            ================================================== --}}

            <div class="rooms-container">

                <div class="room-grid">


                    @forelse($building->rooms as $room)

                        <div class="room-card">


                            <div class="room-top">

                                <div class="room-icon">

                                    <i class="fa-solid fa-door-open"></i>

                                </div>


                                <div>

                                    <div class="room-name">

                                        {{ $room->room_name }}

                                    </div>

                                    <div class="room-label">

                                        Classroom

                                    </div>

                                </div>

                            </div>


                            <div class="room-features" aria-label="Room facilities and capacity">
                                <div class="room-feature">
                                    <i class="fa-solid fa-users" aria-hidden="true"></i>
                                    <span><strong>Capacity</strong><span>{{ $room->capacity ?? 'Not specified' }}</span></span>
                                </div>
                                <div class="room-feature">
                                    <i class="fa-solid fa-computer" aria-hidden="true"></i>
                                    <span><strong>Computers</strong><span>{{ $room->computers ?? 0 }}</span></span>
                                </div>
                                <div class="room-feature {{ $room->has_tv ? 'feature-available' : 'feature-unavailable' }}">
                                    <i class="fa-solid fa-tv" aria-hidden="true"></i>
                                    <span><strong>TV</strong><span>{{ $room->has_tv ? 'Available' : 'No' }}</span></span>
                                </div>
                                <div class="room-feature {{ $room->has_projector ? 'feature-available' : 'feature-unavailable' }}">
                                    <i class="fa-solid fa-video" aria-hidden="true"></i>
                                    <span><strong>Projector</strong><span>{{ $room->has_projector ? 'Available' : 'No' }}</span></span>
                                </div>
                                @if(filled($room->purpose))
                                    <div class="room-feature room-purpose">
                                        <i class="fa-solid fa-book-open" aria-hidden="true"></i>
                                        <span><strong>Purpose</strong><span>{{ $room->purpose }}</span></span>
                                    </div>
                                @endif
                            </div>
                            {{-- SET SCHEDULE --}}

                            <a
                                href="{{ route(
                                    'chair.excel',
                                    $room->id
                                ) }}"
                                class="btn green"
                            >

                                <i class="fa-solid fa-calendar-days"></i>

                                Set Schedule

                            </a>


                            {{-- =================================================
                                 CROSS-DEPARTMENT PERMISSION
                            ================================================== --}}

                            <div class="permission-box">

                                <label
                                    class="permission-label"
                                    for="permission-{{ $room->id }}"
                                >

                                    <input
                                        type="checkbox"
                                        id="permission-{{ $room->id }}"

                                        {{ $room->allow_other_departments
                                            ? 'checked'
                                            : ''
                                        }}

                                        onchange="updateRoomPermission(
                                            {{ $room->id }},
                                            this
                                        )"
                                    >

                                    <span>
                                        Allow other departments
                                        to add to empty slots
                                    </span>

                                </label>


                                <div
                                    id="status-{{ $room->id }}"
                                    class="permission-status
                                    {{
                                        $room->allow_other_departments
                                            ? 'status-on'
                                            : 'status-off'
                                    }}"
                                >

                                    {{
                                        $room->allow_other_departments
                                            ? 'ON'
                                            : 'OFF'
                                    }}

                                </div>

                            </div>


                        </div>


                    @empty

                        <div class="empty">

                            <i class="fa-solid fa-door-closed"></i>

                            <div style="margin-top:6px;">

                                No rooms in this building.

                            </div>

                        </div>

                    @endforelse


                </div>

            </div>

        </div>


    @empty

        <div class="empty">

            <i class="fa-solid fa-building-circle-xmark"></i>

            <div style="margin-top:6px;">

                No buildings are assigned
                to your department.

            </div>

        </div>

    @endforelse

</div>
</div>

            {{-- =============================================================
                 PERMISSION JAVASCRIPT
            ============================================================== --}}

            <script>

               function updateRoomPermission(roomId, checkbox)
{
    const allowed = checkbox.checked;

    fetch(
        "{{ url('/chair/room') }}/" + roomId + "/permission",
        {
            method: "PUT",

            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",

                "X-CSRF-TOKEN":
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).getAttribute('content')
            },

            body: JSON.stringify({
                allow_other_departments: allowed ? 1 : 0
            })
        }
    )
    .then(response => {

        if (!response.ok) {
            throw new Error(
                "HTTP Error: " + response.status
            );
        }

        return response.json();
    })
    .then(data => {

        if (!data.success) {
            throw new Error(
                data.message ||
                "Permission was not saved."
            );
        }

        const saved =
            Number(data.allow_other_departments) === 1;

        checkbox.checked = saved;

        const status =
            document.getElementById(
                "status-" + roomId
            );

        if (saved) {

            status.textContent = "ON";

            status.className =
                "permission-status status-on";

        } else {

            status.textContent = "OFF";

            status.className =
                "permission-status status-off";
        }

    })
    .catch(error => {

        console.error(error);

        checkbox.checked = !allowed;

        alert(
            "Unable to save permission.\n\n" +
            error.message
        );
    });


}
    function toggleBuilding(button)
{
    const building =
        button.closest('.building');

    if (!building) {
        return;
    }

    building.classList.toggle('open');
}

                
            </script>

        </main>

    </div>

    

</div>

@include('footerheader.footer')