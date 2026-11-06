@include('footerheader.header')

<style>
    body.portal-shell.chair-portal { --chair-sidebar-width:252px; --chair-shell-gutter:28px; background:#e9eee8; }
    body.portal-shell.chair-portal .wrapper { min-height:100vh; background:#e9eee8; }
    body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); align-items:stretch; min-height:100vh; }
    body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:sticky; top:0; align-self:start; width:var(--chair-sidebar-width); height:100vh; min-height:100vh; padding:25px 15px 18px; border:0; background:linear-gradient(165deg,#112f29 0%,#173b33 54%,#285342 100%); color:#f7f5eb; box-shadow:12px 0 32px rgba(20,47,37,.1); }
    body.portal-shell.chair-portal .top-header { position:fixed; top:16px; right:var(--chair-shell-gutter); left:calc(var(--chair-sidebar-width) + var(--chair-shell-gutter)); z-index:1000; min-height:72px; padding:10px 15px 10px 22px; border:1px solid rgba(224,231,224,.96); border-radius:18px; background:rgba(255,255,255,.95); box-shadow:0 12px 34px rgba(31,57,43,.09); backdrop-filter:blur(18px); }
    body.portal-shell.chair-portal .header-title small { color:#9a8450; font-size:11px; }
    body.portal-shell.chair-portal .header-title h3 { color:#173b33; font-size:20px; }
    body.portal-shell.chair-portal .header-action { color:#375448; }
    body.portal-shell.chair-portal .sidebar-toggle { top:36px; left:calc(var(--chair-sidebar-width) - 15px); z-index:1200; border-color:rgba(216,193,140,.52); background:#f8f5eb; color:#224336; }
    body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
    body.portal-shell.chair-portal.sidebar-collapsed .main-layout { grid-template-columns:76px minmax(0,1fr); }
    body.portal-shell.chair-portal.sidebar-collapsed .main-layout > .chair-sidebar { width:76px; padding-right:10px; padding-left:10px; }
    body.portal-shell.chair-portal.sidebar-collapsed .sidebar-toggle { left:61px; }
    body.portal-shell.chair-portal .main-layout > .content.chair-subjects-view { min-height:100vh; padding:124px clamp(20px,3vw,42px) 42px; background:radial-gradient(ellipse at 90% 2%,rgba(214,194,143,.23),transparent 28%),linear-gradient(145deg,#eef2ec 0%,#f8f7f2 58%,#f1f0e8 100%); }
    .chair-subjects-view .subjects-page { width:min(100%,1480px); margin:0 auto; color:#20372a; }
    .chair-subjects-view .subjects-hero { position:relative; overflow:hidden; margin:0 0 20px; padding:clamp(28px,4vw,43px); border:1px solid rgba(231,214,173,.22); border-radius:24px; background:radial-gradient(circle at 82% 20%,rgba(215,194,143,.18),transparent 23%),linear-gradient(118deg,#163b33 0%,#245543 58%,#37664d 100%); box-shadow:0 18px 40px rgba(28,65,48,.16); color:#fff; }
    .chair-subjects-view .subjects-hero::after { position:absolute; right:-48px; bottom:-165px; width:330px; height:330px; border:1px solid rgba(232,215,169,.25); border-radius:50%; box-shadow:0 0 0 26px rgba(232,215,169,.035),0 0 0 62px rgba(232,215,169,.025),0 0 0 101px rgba(232,215,169,.02); content:""; pointer-events:none; }
    .chair-subjects-view .subjects-kicker { display:block; position:relative; z-index:1; margin-bottom:11px; color:#e2c987; font-size:11px; font-weight:800; letter-spacing:.15em; text-transform:uppercase; }
    .chair-subjects-view .subjects-hero h1 { position:relative; z-index:1; margin:0 0 10px; color:#fff; font:800 clamp(34px,3.2vw,45px)/1.12 var(--wr-display); letter-spacing:-.045em; }
    .chair-subjects-view .subjects-hero > p { position:relative; z-index:1; max-width:850px; margin:0; color:#e1e8df; font-size:15px; font-weight:600; line-height:1.65; }
    .chair-subjects-view .subjects-steps { position:relative; z-index:1; display:flex; flex-wrap:wrap; gap:8px; margin-top:23px; }
    .chair-subjects-view .subjects-step { display:inline-flex; align-items:center; min-height:34px; padding:0 11px; border:1px solid rgba(255,255,255,.18); border-radius:99px; background:rgba(255,255,255,.075); color:#e4e9e0; font-size:12px; font-weight:700; }
    .chair-subjects-view .subjects-step.active { border-color:#e8d7ae; background:#e8d7ae; color:#203d34; font-weight:800; }
    .chair-subjects-view .subjects-alert { margin:0 0 15px; padding:14px 17px; border:1px solid; border-radius:12px; font-size:14px; font-weight:700; line-height:1.5; }
    .chair-subjects-view .subjects-alert.success { border-color:#b9d9bd; background:#edf7ee; color:#28603a; }
    .chair-subjects-view .subjects-alert.error { border-color:#edc8c2; background:#fff3f1; color:#8f3e36; }
    .chair-subjects-view .subjects-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); align-items:start; gap:16px; margin-bottom:16px; }
    .chair-subjects-view .subjects-card { min-width:0; margin-bottom:16px; padding:21px; border:1px solid #dfe7dd; border-radius:18px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .chair-subjects-view .subjects-card h2 { margin:0 0 8px; color:#173b33; font-size:21px; font-weight:800; letter-spacing:-.03em; line-height:1.3; }
    .chair-subjects-view .subjects-card > p { margin:0 0 17px; color:#5b6d61; font-size:13px; font-weight:550; line-height:1.6; }
    .chair-subjects-view .subjects-field { display:grid; gap:6px; margin:0 0 13px; }
    .chair-subjects-view .subjects-field label { color:#43594d; font-size:13px; font-weight:800; }
    .chair-subjects-view .subjects-field input,.chair-subjects-view .subjects-field select,.chair-subjects-view .subjects-field textarea { width:100%; min-height:44px; padding:10px 12px; border:1px solid #d7e0d7; border-radius:10px; background:#fff; color:#273f32; font:600 13px/1.45 var(--wr-body); }
    .chair-subjects-view .subjects-field textarea { min-height:94px; resize:vertical; }
    .chair-subjects-view .subjects-field input:focus,.chair-subjects-view .subjects-field select:focus,.chair-subjects-view .subjects-field textarea:focus { outline:3px solid rgba(49,104,76,.15); border-color:#648a70; }
    .chair-subjects-view .subjects-btn { display:inline-flex; align-items:center; justify-content:center; gap:7px; min-height:41px; padding:0 14px; border:1px solid #24543e; border-radius:10px; background:#24543e; color:#fff; font:800 12px/1.2 var(--wr-body); text-decoration:none; white-space:nowrap; cursor:pointer; transition:transform .16s ease,background .16s ease; }
    .chair-subjects-view .subjects-btn:hover { transform:translateY(-1px); background:#173b33; }
    .chair-subjects-view .subjects-btn.secondary { border-color:#dce6dc; background:#f5f8f3; color:#315b43; }
    .chair-subjects-view .subjects-btn.danger { border-color:#ecd2ce; background:#fff8f7; color:#9b4c43; }
    .chair-subjects-view .subjects-empty { padding:18px; border:1px dashed #d7e1d7; border-radius:12px; background:#f8faf6; color:#637268; font-size:13px; font-weight:600; line-height:1.55; }
    .chair-subjects-view .program-list,.chair-subjects-view .memorandum-list { display:grid; gap:12px; }
    .chair-subjects-view .program-card,.chair-subjects-view .memorandum-card { min-width:0; padding:17px; border:1px solid #e2e9e1; border-radius:14px; background:#fff; }
    .chair-subjects-view .program-card h3,.chair-subjects-view .memorandum-card h3 { margin:0 0 7px; color:#203d30; font-size:16px; font-weight:800; line-height:1.4; }
    .chair-subjects-view .program-card p,.chair-subjects-view .memorandum-card p { margin:5px 0; color:#5e6f64; font-size:12px; font-weight:550; line-height:1.55; overflow-wrap:anywhere; }
    .chair-subjects-view .card-actions { display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-top:13px; }
    .chair-subjects-view .card-actions form { margin:0; }
    .chair-subjects-view .program-edit,.chair-subjects-view .memo-edit { margin-top:12px; border:1px solid #e2e9e1; border-radius:10px; background:#f8faf6; }
    .chair-subjects-view .program-edit summary,.chair-subjects-view .memo-edit summary { padding:11px 12px; color:#315b43; font-size:12px; font-weight:800; cursor:pointer; }
    .chair-subjects-view .program-edit form,.chair-subjects-view .memo-edit form { padding:0 12px 13px; }
    .chair-subjects-view .subjects-back { display:inline-flex; align-items:center; min-height:40px; margin:0 0 14px; padding:0 13px; border:1px solid #dce6dc; border-radius:10px; background:#fff; color:#315b43; font-size:13px; font-weight:800; text-decoration:none; }
    .chair-subjects-view .memo-identity { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; margin:17px 0; }
    .chair-subjects-view .memo-identity > div { min-width:0; padding:13px; border:1px solid #e2e9e1; border-radius:11px; background:#f7f9f5; }
    .chair-subjects-view .memo-identity span { display:block; margin-bottom:5px; color:#7b866f; font-size:10px; font-weight:800; letter-spacing:.09em; text-transform:uppercase; }
    .chair-subjects-view .memo-identity strong { display:block; color:#294b39; font-size:13px; line-height:1.5; overflow-wrap:anywhere; }
    .chair-subjects-view .semester-badge { flex:0 0 auto; padding:8px 11px; border:1px solid #dfe8dc; border-radius:10px; background:#f1f6ef; color:#315b43; font-size:11px; font-weight:800; }
    .chair-subjects-view .memo-actions { display:flex; align-items:center; flex-wrap:wrap; gap:9px; margin-top:14px; }
    .chair-subjects-view .year-sheet { overflow:hidden; margin:0 0 17px; border:1px solid #dfe7dd; border-radius:16px; background:#fff; box-shadow:0 8px 24px rgba(31,57,43,.045); }
    .chair-subjects-view .year-sheet-heading { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:17px 20px; border-bottom:1px solid #e4eae1; background:linear-gradient(110deg,#f4f7f1,#fff); }
    .chair-subjects-view .year-sheet-heading h3 { margin:0; color:#203d30; font-size:18px; font-weight:800; }
    .chair-subjects-view .year-sheet-heading p { margin:4px 0 0; color:#708076; font-size:12px; line-height:1.5; }
    .chair-subjects-view .sheet-actions { display:flex; align-items:center; flex-wrap:wrap; gap:8px; padding:13px 17px 0; }
    .chair-subjects-view .sheet-grid-wrap { width:100%; overflow:auto; padding:13px 17px 4px; }
    .chair-subjects-view .sheet-grid { width:100%; min-width:890px; border-collapse:separate; border-spacing:0; table-layout:fixed; }
    .chair-subjects-view .sheet-grid th { padding:10px 9px; border:1px solid #dfe7dd; background:#edf3eb; color:#365644; text-align:left; font-size:11px; font-weight:800; }
    .chair-subjects-view .sheet-grid thead tr:first-child th { background:#e2ebdf; text-align:center; font-size:12px; }
    .chair-subjects-view .sheet-grid td { padding:4px; border-right:1px solid #e4eae1; border-bottom:1px solid #e4eae1; background:#fff; }
    .chair-subjects-view .sheet-grid tbody tr:nth-child(even) td { background:#fbfcfa; }
    .chair-subjects-view .sheet-grid tbody tr:focus-within td { background:#f0f7ef; }
    .chair-subjects-view .sheet-grid input[type="text"] { display:block; width:100%; min-height:38px; padding:7px 8px; border:1px solid transparent; border-radius:6px; background:transparent; color:#273f32; font:600 12px/1.4 var(--wr-body); }
    .chair-subjects-view .sheet-grid input[type="text"]:hover { border-color:#dce5dc; background:#fff; }
    .chair-subjects-view .sheet-grid input[type="text"]:focus { outline:2px solid rgba(49,104,76,.2); border-color:#648a70; background:#fff; }
    .chair-subjects-view .sheet-row-number { width:38px; color:#8b968c; text-align:center; font-size:11px; font-weight:700; }
    .chair-subjects-view .sheet-remove { width:37px; height:34px; border:1px solid #e7d9d6; border-radius:7px; background:#fff8f7; color:#96534b; cursor:pointer; }
    .chair-subjects-view .sheet-remove:hover { background:#fcebe8; }
    .chair-subjects-view .sheet-footer { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px 17px 17px; }
    .chair-subjects-view .sheet-footer small { color:#76847a; font-size:11px; line-height:1.5; }
    @media(max-width:1100px) {
        body.portal-shell.chair-portal { --chair-sidebar-width:226px; --chair-shell-gutter:22px; }
        body.portal-shell.chair-portal.sidebar-collapsed { --chair-sidebar-width:76px; }
        body.portal-shell.chair-portal .main-layout { grid-template-columns:var(--chair-sidebar-width) minmax(0,1fr); }
        body.portal-shell.chair-portal .main-layout > .content.chair-subjects-view { padding-right:24px; padding-left:24px; }
        .chair-subjects-view .subjects-grid { grid-template-columns:minmax(0,1fr); }
        .chair-subjects-view .memo-identity { grid-template-columns:repeat(2,minmax(0,1fr)); }
    }
    @media(max-width:760px) {
        body.portal-shell.chair-portal .top-header { position:sticky; top:0; right:auto; left:auto; min-height:64px; margin:0; padding:9px 12px 9px 54px; border:0; border-radius:0 0 15px 15px; box-shadow:0 5px 18px rgba(31,57,43,.08); }
        body.portal-shell.chair-portal .header-title h3 { font-size:17px; }
        body.portal-shell.chair-portal .wrapper,body.portal-shell.chair-portal .main-layout { min-height:calc(100vh - 64px); }
        body.portal-shell.chair-portal .main-layout { display:block; }
        body.portal-shell.chair-portal .main-layout > .chair-sidebar { position:fixed; top:0; bottom:0; left:0; z-index:1100; width:262px; height:100dvh; min-height:0; padding:20px 15px; transform:translateX(0); }
        body.portal-shell.chair-portal.sidebar-collapsed .main-layout > .chair-sidebar { width:262px; transform:translateX(-110%); }
        body.portal-shell.chair-portal .sidebar-backdrop { position:fixed; inset:0; z-index:1050; }
        body.portal-shell.chair-portal .sidebar-toggle,body.portal-shell.chair-portal.sidebar-collapsed .sidebar-toggle { top:17px; left:14px; z-index:1200; }
        body.portal-shell.chair-portal:not(.sidebar-collapsed) .sidebar-toggle { top:17px; left:270px; }
        body.portal-shell.chair-portal .main-layout > .content.chair-subjects-view { min-height:calc(100vh - 64px); padding:22px 17px 32px; }
        .chair-subjects-view .subjects-hero { padding:27px 23px; }
    }
    @media(max-width:540px) {
        .chair-subjects-view .subjects-card { padding:17px; }
        .chair-subjects-view .memo-identity { grid-template-columns:minmax(0,1fr); }
        .chair-subjects-view .year-sheet-heading { align-items:flex-start; flex-direction:column; }
        .chair-subjects-view .sheet-footer { align-items:stretch; flex-direction:column; }
        .chair-subjects-view .sheet-footer .subjects-btn { width:100%; }
        .chair-subjects-view .subjects-hero > p { font-size:14px; }
    }
</style>

<div class="wrapper">
    <div class="main-layout">
        @include('chair.sidebar')
        <main class="content chair-workspace-redesign chair-subjects-redesign chair-subjects-view">
            <div class="subjects-page">
                <header class="subjects-hero">
                    <span class="subjects-kicker">College workspace</span>
                    <h1>Manage Subjects</h1>
                    <p>Set up a program, add one or more curriculum memoranda, then edit both semesters together in year-level sheets.</p>
                    <div class="subjects-steps" aria-label="Setup steps">
                        <span class="subjects-step {{ !$selectedProgram ? 'active' : '' }}">1. Add a program</span>
                        <span class="subjects-step {{ $selectedProgram && !$selectedMemorandum ? 'active' : '' }}">2. Add a memorandum</span>
                        <span class="subjects-step {{ $selectedMemorandum ? 'active' : '' }}">3. Edit subject sheets</span>
                    </div>
                </header>

                @if(session('success'))<div class="subjects-alert success" role="status">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="subjects-alert error" role="alert">{{ session('error') }}</div>@endif
                @if($errors->any())<div class="subjects-alert error" role="alert">{{ $errors->first() }}</div>@endif

                @if(!$selectedProgram)
                    <div class="subjects-grid">
                        <section class="subjects-card">
                            <h2>Add a program</h2>
                            <p>Start with the program name and code. Curriculum memoranda and majors are added inside the program.</p>
                            <form method="POST" action="{{ route('chair.managesubjects.program.store') }}">
                                @csrf
                                <div class="subjects-field">
                                    <label for="program_name">Program name</label>
                                    <input id="program_name" name="name" value="{{ old('name') }}" placeholder="Bachelor of Science in Information Technology" required maxlength="200">
                                </div>
                                <div class="subjects-field">
                                    <label for="program_code">Program code</label>
                                    <input id="program_code" name="code" value="{{ old('code') }}" placeholder="BSIT" required maxlength="40">
                                </div>
                                <button class="subjects-btn" type="submit"><i class="fa-solid fa-plus" aria-hidden="true"></i> Save program</button>
                            </form>
                        </section>

                        <section class="subjects-card">
                            <h2>Saved programs</h2>
                            <p>Open a program to add its curriculum memorandum entries.</p>
                            @if($programGroups->isEmpty())
                                <div class="subjects-empty">No programs yet. Create your first program to begin.</div>
                            @else
                                <div class="program-list">
                                    @foreach($programGroups as $group)
                                        <article class="program-card">
                                            <h3>{{ $group->program->name }} <span>({{ $group->program->code }})</span></h3>
                                            <p>{{ $group->memoranda->count() }} memorandum(s) · {{ $group->subjects_count }} subject(s)</p>
                                            <div class="card-actions">
                                                <a class="subjects-btn" href="{{ route('chair.managesubjects.program', $group->program->id) }}">Open program</a>
                                                <form method="POST" action="{{ route('chair.managesubjects.program.delete', $group->program->id) }}" data-confirm-delete="Delete this program, all memorandum entries, and its catalog subjects? Existing instructor assignments keep their saved details." onsubmit="return confirm('Delete this program, all memorandum entries, and its catalog subjects? Existing instructor assignments keep their saved details.');">
                                                    @csrf @method('DELETE')
                                                    <button class="subjects-btn danger" type="submit">Delete program</button>
                                                </form>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>
                @elseif(!$selectedMemorandum)
                    <a class="subjects-back" href="{{ route('chair.managesubjects') }}">← All programs</a>

                    <section class="subjects-card">
                        <h2>{{ $selectedProgram->name }} <span>({{ $selectedProgram->code }})</span></h2>
                        <p>Add a separate memorandum and major for every curriculum version. A memorandum can apply to the whole program or one year level.</p>
                        <details class="program-edit">
                            <summary>Edit program name or code</summary>
                            <form method="POST" action="{{ route('chair.managesubjects.program.update', $selectedProgram->id) }}">
                                @csrf @method('PUT')
                                <div class="subjects-field"><label for="edit_program_name">Program name</label><input id="edit_program_name" name="name" value="{{ $selectedProgram->name }}" required maxlength="200"></div>
                                <div class="subjects-field"><label for="edit_program_code">Program code</label><input id="edit_program_code" name="code" value="{{ $selectedProgram->code }}" required maxlength="40"></div>
                                <button class="subjects-btn" type="submit">Save program details</button>
                            </form>
                        </details>
                    </section>

                    <div class="subjects-grid">
                        <section class="subjects-card">
                            <h2>Add curriculum memorandum</h2>
                            <p>Memorandum, major, and year level are saved as one curriculum entry.</p>
                            <form method="POST" action="{{ route('chair.managesubjects.memorandum.store', $selectedProgram->id) }}">
                                @csrf
                                <div class="subjects-field">
                                    <label for="new_memorandum">Curriculum memorandum</label>
                                    <textarea id="new_memorandum" name="memorandum" placeholder="CHED Memorandum Order No. 75, s. 2017" required maxlength="500">{{ old('memorandum') }}</textarea>
                                </div>
                                <div class="subjects-field">
                                    <label for="new_major">Major <span>(optional)</span></label>
                                    <input id="new_major" name="major" value="{{ old('major') }}" placeholder="Information Systems" maxlength="120">
                                </div>
                                <div class="subjects-field">
                                    <label for="new_memo_year">Applies to</label>
                                    <select id="new_memo_year" name="year_level">
                                        <option value="">All year levels</option>
                                        @foreach($yearLevels as $yearLevel)
                                            <option value="{{ $yearLevel }}" {{ old('year_level') === $yearLevel ? 'selected' : '' }}>{{ $yearLevel }} only</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="subjects-btn" type="submit"><i class="fa-solid fa-plus" aria-hidden="true"></i> Save memorandum</button>
                            </form>
                        </section>

                        <section class="subjects-card">
                            <h2>Curriculum memoranda</h2>
                            <p>Open a memorandum to edit its year-level sheets.</p>
                            @if($selectedMemoranda->isEmpty())
                                <div class="subjects-empty">No memorandum entries yet. Add one here to start entering subjects.</div>
                            @else
                                <div class="memorandum-list">
                                    @foreach($selectedMemoranda as $memorandum)
                                        <article class="memorandum-card">
                                            <h3>{{ $memorandum->memorandum }}</h3>
                                            <p><strong>Major:</strong> {{ $memorandum->major ?: 'None specified' }}</p>
                                            <p><strong>Applies to:</strong> {{ $memorandum->year_level ?: 'All year levels' }} · {{ $memorandum->subjects_count }} subject(s)</p>
                                            <div class="card-actions">
                                                <a class="subjects-btn" href="{{ route('chair.managesubjects.memorandum', [$selectedProgram->id, $memorandum->id]) }}">Open memorandum</a>
                                                <details class="memo-edit">
                                                    <summary>Edit details</summary>
                                                    <form method="POST" action="{{ route('chair.managesubjects.memorandum.update', [$selectedProgram->id, $memorandum->id]) }}">
                                                        @csrf @method('PUT')
                                                        <div class="subjects-field"><label>Curriculum memorandum</label><textarea name="memorandum" required maxlength="500">{{ $memorandum->memorandum }}</textarea></div>
                                                        <div class="subjects-field"><label>Major (optional)</label><input name="major" value="{{ $memorandum->major }}" maxlength="120"></div>
                                                        <div class="subjects-field">
                                                            <label>Applies to</label>
                                                            <select name="year_level">
                                                                <option value="" {{ !$memorandum->year_level ? 'selected' : '' }}>All year levels</option>
                                                                @foreach($yearLevels as $yearLevel)
                                                                    <option value="{{ $yearLevel }}" {{ $memorandum->year_level === $yearLevel ? 'selected' : '' }}>{{ $yearLevel }} only</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button class="subjects-btn" type="submit">Save memorandum details</button>
                                                    </form>
                                                </details>
                                                <form method="POST" action="{{ route('chair.managesubjects.memorandum.delete', [$selectedProgram->id, $memorandum->id]) }}" data-confirm-delete="Delete this memorandum and its subject sheets? Existing instructor assignments keep their saved details." onsubmit="return confirm('Delete this memorandum and its subject sheets? Existing instructor assignments keep their saved details.');">
                                                    @csrf @method('DELETE')
                                                    <button class="subjects-btn danger" type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>
                @else
                    <nav aria-label="Breadcrumb">
                        <a class="subjects-back" href="{{ route('chair.managesubjects') }}">← All programs</a>
                        <a class="subjects-back" href="{{ route('chair.managesubjects.program', $selectedProgram->id) }}">{{ $selectedProgram->name }} ({{ $selectedProgram->code }})</a>
                    </nav>

                    <section class="subjects-card">
                        <div class="memo-actions" style="justify-content:space-between;margin-top:0">
                            <div>
                                <span class="subjects-kicker" style="color:#9a8450">CURRICULUM MEMORANDUM</span>
                                <h2>{{ $selectedProgram->name }} · {{ $selectedProgram->code }}</h2>
                            </div>
                            <a class="subjects-btn secondary" href="{{ route('chair.managesubjects.program', $selectedProgram->id) }}">All memoranda</a>
                        </div>
                        <div class="memo-identity">
                            <div><span>Program name</span><strong>{{ $selectedProgram->name }}</strong></div>
                            <div><span>Program code</span><strong>{{ $selectedProgram->code }}</strong></div>
                            <div><span>Memorandum</span><strong>{{ $selectedMemorandum->memorandum }}</strong></div>
                            <div><span>Major</span><strong>{{ $selectedMemorandum->major ?: 'None specified' }}</strong></div>
                        </div>
                        <p>Enter courses in the paired semester columns. Add or remove rows as needed, then save the year-level sheet.</p>
                        <details class="memo-edit">
                            <summary>Edit memorandum, major, or year-level scope</summary>
                            <form method="POST" action="{{ route('chair.managesubjects.memorandum.update', [$selectedProgram->id, $selectedMemorandum->id]) }}">
                                @csrf @method('PUT')
                                <div class="subjects-field"><label>Curriculum memorandum</label><textarea name="memorandum" required maxlength="500">{{ $selectedMemorandum->memorandum }}</textarea></div>
                                <div class="subjects-field"><label>Major (optional)</label><input name="major" value="{{ $selectedMemorandum->major }}" maxlength="120"></div>
                                <div class="subjects-field">
                                    <label>Applies to</label>
                                    <select name="year_level">
                                        <option value="" {{ !$selectedMemorandum->year_level ? 'selected' : '' }}>All year levels</option>
                                        @foreach($yearLevels as $yearLevel)
                                            <option value="{{ $yearLevel }}" {{ $selectedMemorandum->year_level === $yearLevel ? 'selected' : '' }}>{{ $yearLevel }} only</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="subjects-btn" type="submit">Save memorandum details</button>
                            </form>
                        </details>
                    </section>

                    @foreach($sheetYearLevels as $yearLevel)
                        @php
                            $yearSubjects = $subjectsByYear->get($yearLevel, collect());
                            $firstSemesterSubjects = $yearSubjects->where('semester', '1st Semester')->values();
                            $secondSemesterSubjects = $yearSubjects->where('semester', '2nd Semester')->values();
                            $oldSheetRows = old('sheet_year') === $yearLevel ? old('rows') : null;
                            $sheetRowCount = is_array($oldSheetRows)
                                ? max(count($oldSheetRows), 1)
                                : max($firstSemesterSubjects->count(), $secondSemesterSubjects->count(), 5);
                        @endphp
                        <section class="year-sheet" id="year-sheet-{{ $loop->index }}">
                            <header class="year-sheet-heading">
                                <div>
                                    <h3>{{ $yearLevel }}</h3>
                                    <p>{{ $firstSemesterSubjects->count() }} first-semester and {{ $secondSemesterSubjects->count() }} second-semester subject(s)</p>
                                </div>
                                <span class="semester-badge">{{ $selectedMemorandum->year_level ?: 'Program-wide memorandum' }}</span>
                            </header>

                            <form method="POST" action="{{ route('chair.managesubjects.sheet.save', ['program' => $selectedProgram->id, 'memorandum' => $selectedMemorandum->id, 'year' => $yearLevel]) }}" data-curriculum-sheet data-next-row="{{ $sheetRowCount }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="sheet_year" value="{{ $yearLevel }}">
                                <div class="sheet-actions">
                                    <button class="subjects-btn secondary" type="button" data-add-sheet-row><i class="fa-solid fa-plus" aria-hidden="true"></i> Add row</button>
                                </div>
                                <div class="sheet-grid-wrap">
                                    <table class="sheet-grid">
                                        <thead>
                                            <tr><th rowspan="2" class="sheet-row-number">#</th><th colspan="2">1st Semester</th><th colspan="2">2nd Semester</th><th rowspan="2" style="width:45px">Row</th></tr>
                                            <tr><th style="width:17%">Course code</th><th style="width:31%">Course title</th><th style="width:17%">Course code</th><th style="width:31%">Course title</th></tr>
                                        </thead>
                                        <tbody>
                                            @for($rowIndex = 0; $rowIndex < $sheetRowCount; $rowIndex++)
                                                @php
                                                    $firstSubject = $firstSemesterSubjects->get($rowIndex);
                                                    $secondSubject = $secondSemesterSubjects->get($rowIndex);
                                                    $sheetRow = is_array($oldSheetRows) ? ($oldSheetRows[$rowIndex] ?? []) : [];
                                                @endphp
                                                <tr>
                                                    <td class="sheet-row-number">{{ $rowIndex + 1 }}</td>
                                                    <td>
                                                        <input type="hidden" name="rows[{{ $rowIndex }}][first_id]" value="{{ $sheetRow['first_id'] ?? ($firstSubject->id ?? '') }}">
                                                        <input type="text" name="rows[{{ $rowIndex }}][first_code]" value="{{ $sheetRow['first_code'] ?? ($firstSubject->course_code ?? '') }}" aria-label="{{ $yearLevel }} first semester course code, row {{ $rowIndex + 1 }}" maxlength="50">
                                                    </td>
                                                    <td><input type="text" name="rows[{{ $rowIndex }}][first_title]" value="{{ $sheetRow['first_title'] ?? ($firstSubject->course_title ?? '') }}" aria-label="{{ $yearLevel }} first semester course title, row {{ $rowIndex + 1 }}" maxlength="255"></td>
                                                    <td>
                                                        <input type="hidden" name="rows[{{ $rowIndex }}][second_id]" value="{{ $sheetRow['second_id'] ?? ($secondSubject->id ?? '') }}">
                                                        <input type="text" name="rows[{{ $rowIndex }}][second_code]" value="{{ $sheetRow['second_code'] ?? ($secondSubject->course_code ?? '') }}" aria-label="{{ $yearLevel }} second semester course code, row {{ $rowIndex + 1 }}" maxlength="50">
                                                    </td>
                                                    <td><input type="text" name="rows[{{ $rowIndex }}][second_title]" value="{{ $sheetRow['second_title'] ?? ($secondSubject->course_title ?? '') }}" aria-label="{{ $yearLevel }} second semester course title, row {{ $rowIndex + 1 }}" maxlength="255"></td>
                                                    <td><button class="sheet-remove" type="button" data-remove-sheet-row aria-label="Remove row {{ $rowIndex + 1 }}"><i class="fa-solid fa-minus" aria-hidden="true"></i></button></td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                                <footer class="sheet-footer">
                                    <small>Arrow keys move between cells; Enter moves down to the next row. Changes are saved to this memorandum’s catalog, and linked instructor assignments are updated.</small>
                                    <button class="subjects-btn" type="submit"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Save {{ $yearLevel }} sheet</button>
                                </footer>
                            </form>
                        </section>
                    @endforeach
                @endif
            </div>
        </main>
    </div>
</div>

<script>
document.querySelectorAll('[data-curriculum-sheet]').forEach(function (form) {
    var rowNumber = parseInt(form.dataset.nextRow || '0', 10);
    var body = form.querySelector('tbody');

    function reindexRows() {
        var rows = body.querySelectorAll('tr');
        rows.forEach(function (row, index) {
            row.querySelectorAll('input').forEach(function (input) {
                input.name = input.name.replace(/rows\[\d+\]/, 'rows[' + index + ']');
                var ariaLabel = input.getAttribute('aria-label');
                if (ariaLabel) {
                    input.setAttribute('aria-label', ariaLabel.replace(/row \d+$/, 'row ' + (index + 1)));
                }
            });
            var numberCell = row.querySelector('.sheet-row-number');
            if (numberCell) numberCell.textContent = String(index + 1);
            var removeButton = row.querySelector('[data-remove-sheet-row]');
            if (removeButton) removeButton.setAttribute('aria-label', 'Remove row ' + (index + 1));
        });
        rowNumber = rows.length;
        form.dataset.nextRow = String(rowNumber);
    }

    function appendSheetRow() {
        var template = body.querySelector('tr');
        if (!template) return null;
        var newRow = template.cloneNode(true);
        newRow.querySelectorAll('input').forEach(function (input) {
            input.name = input.name.replace(/rows\[\d+\]/, 'rows[' + rowNumber + ']');
            input.value = '';
            var ariaLabel = input.getAttribute('aria-label');
            if (ariaLabel) {
                input.setAttribute('aria-label', ariaLabel.replace(/row \d+$/, 'row ' + (rowNumber + 1)));
            }
        });
        var numberCell = newRow.querySelector('.sheet-row-number');
        if (numberCell) numberCell.textContent = String(rowNumber + 1);
        var removeButton = newRow.querySelector('[data-remove-sheet-row]');
        if (removeButton) removeButton.setAttribute('aria-label', 'Remove row ' + (rowNumber + 1));
        body.appendChild(newRow);
        rowNumber += 1;
        form.dataset.nextRow = String(rowNumber);
        return newRow;
    }

    form.querySelector('[data-add-sheet-row]')?.addEventListener('click', function () {
        appendSheetRow();
    });

    body.addEventListener('click', function (event) {
        var button = event.target.closest('[data-remove-sheet-row]');
        if (!button) return;
        if (body.querySelectorAll('tr').length <= 1) {
            body.querySelectorAll('input').forEach(function (input) { input.value = ''; });
            return;
        }
        button.closest('tr').remove();
        reindexRows();
    });

    body.addEventListener('keydown', function (event) {
        var currentInput = event.target;
        if (!currentInput.matches('input[type="text"]')) return;

        var rows = Array.from(body.querySelectorAll('tr'));
        var currentRow = currentInput.closest('tr');
        var rowIndex = rows.indexOf(currentRow);
        var currentRowInputs = Array.from(currentRow.querySelectorAll('input[type="text"]'));
        var columnIndex = currentRowInputs.indexOf(currentInput);
        if (rowIndex < 0 || columnIndex < 0) return;

        var targetRowIndex = rowIndex;
        var targetColumnIndex = columnIndex;

        if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
            event.preventDefault();
            targetColumnIndex += event.key === 'ArrowLeft' ? -1 : 1;
            if (targetColumnIndex < 0) {
                targetRowIndex -= 1;
                targetColumnIndex = 3;
            } else if (targetColumnIndex > 3) {
                targetRowIndex += 1;
                targetColumnIndex = 0;
            }
        } else if (event.key === 'ArrowUp' || event.key === 'ArrowDown' || event.key === 'Enter') {
            event.preventDefault();
            var moveUp = event.key === 'ArrowUp' || (event.key === 'Enter' && event.shiftKey);
            targetRowIndex += moveUp ? -1 : 1;

            if (targetRowIndex >= rows.length && event.key === 'Enter' && !event.shiftKey) {
                var appendedRow = appendSheetRow();
                if (appendedRow) targetRowIndex = rows.length;
            }
        } else {
            return;
        }

        if (targetRowIndex < 0 || targetRowIndex >= body.querySelectorAll('tr').length) return;
        var targetRow = body.querySelectorAll('tr')[targetRowIndex];
        var targetInput = targetRow.querySelectorAll('input[type="text"]')[targetColumnIndex];
        if (targetInput) targetInput.focus();
    });
});
</script>
