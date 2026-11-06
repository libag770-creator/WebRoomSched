@include('footerheader.header')

<div class="wrapper">
    <div class="main-layout">
        @include('chair.sidebar')

        <main class="content chair-workspace-redesign chair-subjects-redesign">
            <style>
                .subjects-page{max-width:1500px;margin:0 auto;padding:28px 24px;color:#183526;font-family:Inter,Arial,sans-serif}
                .subjects-hero,.subjects-card,.program-card,.year-card,.semester-card{background:#fff;border:1px solid #cbdacc;border-radius:16px;box-shadow:0 5px 18px rgba(24,53,38,.055)}
                .subjects-hero{padding:30px;margin-bottom:24px;border-left:5px solid #1f6846;background:#f5faf6}
                .subjects-kicker{color:#426b53;font-size:13px;font-weight:750;letter-spacing:.08em;text-transform:uppercase}
                .subjects-hero h1{font-size:clamp(31px,4vw,42px);line-height:1.18;margin:8px 0;color:#183526;font-weight:800;letter-spacing:-.025em}
                .subjects-hero p{font-size:17px;line-height:1.6;color:#52665a;margin:0;max-width:900px}
                .subjects-steps{display:flex;gap:10px;flex-wrap:wrap;margin-top:20px}
                .subjects-step{padding:9px 14px;border-radius:999px;background:#eaf2ec;color:#385343;font-weight:700;font-size:14px}
                .subjects-step.active{background:#1f6846;color:#fff}
                .subjects-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:18px}
                .subjects-card{padding:24px;margin-bottom:20px}
                .subjects-card h2{font-size:23px;line-height:1.3;margin:0 0 9px;color:#183526;font-weight:750}
                .subjects-card h3{color:#1f6846!important}
                .subjects-card p,.subjects-muted{font-size:16px;line-height:1.55;color:#52665a}
                .subjects-field{margin:16px 0}
                .subjects-field label{display:block;font-size:16px;font-weight:700;color:#263f30;margin:0 0 7px}
                .subjects-field input,.subjects-field select,.subjects-field textarea{width:100%;min-height:50px;padding:11px 13px;border:1px solid #bdcfc1;border-radius:10px;background:#fff;color:#1b3425;font-size:16px;font-family:inherit}
                .subjects-field textarea{min-height:104px;resize:vertical;line-height:1.55}
                .subjects-field input:focus,.subjects-field select:focus,.subjects-field textarea:focus{outline:3px solid rgba(31,104,70,.18);border-color:#397a56}
                .subjects-btn{display:inline-flex;justify-content:center;align-items:center;min-height:48px;padding:10px 17px;border:1px solid #1f6846;border-radius:9px;background:#1f6846;color:#fff;font-size:15px;font-weight:700;text-decoration:none;cursor:pointer;transition:background .16s ease,border-color .16s ease}
                .subjects-btn:hover{background:#18583b;border-color:#18583b;color:#fff}
                .subjects-btn.secondary{border-color:#cbdacc;background:#edf5ef;color:#174a35}
                .subjects-btn.secondary:hover{background:#e0eee3;color:#123d2b}
                .subjects-btn.danger{border-color:#a83c35;background:#a83c35;color:#fff}
                .subjects-btn.danger:hover{border-color:#8e302a;background:#8e302a}
                .program-list,.year-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px}
                .program-card,.year-card{padding:20px;border-color:#ccdace}
                .semester-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;margin:16px 0 22px}
                .semester-card{padding:20px;border-color:#ccdace;background:#fff}
                .semester-card h3{font-size:20px;margin:0 0 8px;color:#183526}
                .semester-card p{font-size:16px;line-height:1.5;color:#52665a;margin:5px 0 15px}
                .semester-card.active{border:2px solid #568368;background:#f2f8f3;box-shadow:0 4px 14px rgba(31,104,70,.08)}
                .semester-heading{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap}
                .semester-badge{display:inline-flex;align-items:center;min-height:34px;padding:6px 12px;border-radius:999px;background:#e4efe6;color:#174a35;font-size:14px;font-weight:750}
                .program-card h3,.year-card h3{font-size:20px;margin:0 0 8px;color:#183526;line-height:1.4;overflow-wrap:anywhere}
                .program-card p,.year-card p{font-size:15px;line-height:1.5;color:#52665a;margin:6px 0;overflow-wrap:anywhere}
                .program-card strong,.year-card strong{color:#263f30}
                .card-actions{display:flex;gap:9px;align-items:center;flex-wrap:wrap;margin-top:16px}
                .subjects-table-wrap{overflow-x:auto;border:1px solid #cbdacc;border-radius:12px;background:#fff}
                .subjects-table{width:100%;border-collapse:collapse;min-width:700px}
                .subjects-table th,.subjects-table td{text-align:left;padding:14px 16px;border-bottom:1px solid #e0e9e2;font-size:15px;line-height:1.5;color:#304a38;vertical-align:top}
                .subjects-table th{background:#edf5ef;color:#244b34;font-size:14px;font-weight:750}
                .subjects-table tbody tr:hover{background:#f7faf7}
                .subjects-table tr:last-child td{border-bottom:0}
                .subjects-alert{padding:14px 16px;border-radius:10px;margin:0 0 18px;font-size:15px;font-weight:650;line-height:1.5}
                .subjects-alert.success{background:#edf6ef;color:#185c35;border:1px solid #bfd7c5}
                .subjects-alert.error{background:#fff2f0;color:#8b271e;border:1px solid #e5c3be}
                .subjects-empty{padding:22px;text-align:center;border:1px dashed #abc1b0;border-radius:12px;background:#f6faf7;color:#40594a;font-size:16px;line-height:1.55}
                details.subject-edit summary,details.program-edit summary{color:#1f6846;font-size:15px;font-weight:700;cursor:pointer}
                details.subject-edit summary:hover,details.program-edit summary:hover{text-decoration:underline}
                details.subject-edit form{min-width:245px;padding-top:12px}
                details.program-edit{margin-top:12px}
                details.program-edit form{padding-top:8px}
                .subjects-back{display:inline-block;margin-bottom:18px;font-size:16px;font-weight:700;color:#1f6846;text-decoration:none}
                .subjects-back:hover{text-decoration:underline}
                .subjects-page button:focus-visible,.subjects-page a:focus-visible,.subjects-page input:focus-visible,.subjects-page select:focus-visible,.subjects-page textarea:focus-visible,.subjects-page summary:focus-visible{outline:3px solid rgba(31,104,70,.22);outline-offset:2px}
                @media(max-width:680px){.subjects-page{padding:18px 14px}.subjects-hero,.subjects-card{padding:20px}.subjects-hero p{font-size:16px}.subjects-step{font-size:14px}.subjects-grid{grid-template-columns:1fr}}

                /* Keep button labels readable against global anchor/button theme rules. */
                .subjects-page .subjects-btn,
                .subjects-page a.subjects-btn,
                .subjects-page button.subjects-btn,
                .subjects-page .subjects-btn:link,
                .subjects-page .subjects-btn:visited,
                .subjects-page .subjects-btn:hover,
                .subjects-page .subjects-btn:focus { color:#fff !important; -webkit-text-fill-color:#fff !important; opacity:1 !important; text-shadow:none !important; }
                .subjects-page .subjects-btn,
                .subjects-page a.subjects-btn,
                .subjects-page button.subjects-btn { background:#1f6846 !important; border-color:#1f6846 !important; }
                .subjects-page .subjects-btn.secondary,
                .subjects-page a.subjects-btn.secondary,
                .subjects-page button.subjects-btn.secondary,
                .subjects-page .subjects-btn.secondary:hover,
                .subjects-page .subjects-btn.secondary:focus { color:#174a35 !important; -webkit-text-fill-color:#174a35 !important; background:#edf5ef !important; border-color:#cbdacc !important; }
                .subjects-page .subjects-btn.danger,
                .subjects-page a.subjects-btn.danger,
                .subjects-page button.subjects-btn.danger,
                .subjects-page .subjects-btn.danger:hover,
                .subjects-page .subjects-btn.danger:focus { color:#fff !important; -webkit-text-fill-color:#fff !important; background:#a83c35 !important; border-color:#a83c35 !important; }
                .subjects-page .subjects-btn:disabled,
                .subjects-page button.subjects-btn:disabled { color:#fff !important; -webkit-text-fill-color:#fff !important; opacity:.72 !important; }
            </style>
            <link rel="stylesheet" href="{{ asset('css/chair-workspace.css') }}">


            <div class="subjects-page">
                <header class="subjects-hero">
                    <span class="subjects-kicker">Department workspace</span>
                    <h1>Manage Subjects</h1>
                    <p>Set up each program once, organize subjects by year level and semester, then assign them to faculty by course code.</p>
                    <div class="subjects-steps" aria-label="Setup steps">
                        <span class="subjects-step {{ !$selectedProgram ? 'active' : '' }}">1. Create a program</span>
                        <span class="subjects-step {{ $selectedProgram && !$selectedYear ? 'active' : '' }}">2. Choose a year level</span>
                        <span class="subjects-step {{ $selectedYear && !$selectedSemester ? 'active' : '' }}">3. Choose a semester</span>
                        <span class="subjects-step {{ $selectedSemester ? 'active' : '' }}">4. Add subjects</span>
                    </div>
                </header>

                @if(session('success'))<div class="subjects-alert success" role="status">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="subjects-alert error" role="alert">{{ session('error') }}</div>@endif
                @if($errors->any())<div class="subjects-alert error" role="alert">{{ $errors->first() }}</div>@endif

                @if(!$selectedProgram)
                    <div class="subjects-grid">
                        <section class="subjects-card">
                            <h2>Create a program</h2>
                            <p>Enter the program information first. You can add the four year levels and subjects after saving.</p>
                            <form method="POST" action="{{ route('chair.managesubjects.program.store') }}">
                                @csrf
                                <div class="subjects-field"><label for="program_name">Program name</label><input id="program_name" name="name" value="{{ old('name') }}" placeholder="Bachelor of Science in Information Technology" required maxlength="200"></div>
                                <div class="subjects-field"><label for="program_code">Program code</label><input id="program_code" name="code" value="{{ old('code') }}" placeholder="BSIT" required maxlength="40"></div>
                                <div class="subjects-field"><label for="memorandum">Curriculum memorandum</label><textarea id="memorandum" name="memorandum" placeholder="2018 Curriculum as per CHED Memorandum Order No. 75, s. 2017/Board Resolution No. 26a, s. 2023" required maxlength="500">{{ old('memorandum') }}</textarea></div>
                                <div class="subjects-field"><label for="program_major">Major <span class="subjects-muted">(optional)</span></label><input id="program_major" name="major" value="{{ old('major') }}" placeholder="Major in Science" maxlength="120"></div>
                                <button class="subjects-btn" type="submit">Save program</button>
                            </form>
                        </section>

                        <section class="subjects-card">
                            <h2>Step 1 · Open a program</h2>
                            <p>Choose a saved program to add subjects to its year levels.</p>
                            @if($programs->isEmpty())
                                <div class="subjects-empty">No programs yet. Create your first program in the form.</div>
                            @else
                                <div class="program-list">
                                    @foreach($programs as $program)
                                        <article class="program-card">
                                            <h3>{{ $program->name }}@if(strcasecmp($program->name, $program->code) !== 0) ({{ $program->code }}) @endif</h3>
                                            <p>Memorandum: <strong>{{ $program->memorandum }}</strong></p>
                                            @if($program->major)<p>Major: {{ $program->major }}</p>@endif
                                            <p>{{ $program->subjects_count }} subject(s)</p>
                                            <details class="program-edit">
                                                <summary>Edit program details</summary>
                                                <form method="POST" action="{{ route('chair.managesubjects.program.update', $program) }}">
                                                    @csrf @method('PUT')
                                                    <div class="subjects-field"><label>Program name</label><input name="name" value="{{ $program->name }}" required maxlength="200"></div>
                                                    <div class="subjects-field"><label>Program code</label><input name="code" value="{{ $program->code }}" required maxlength="40"></div>
                                                    <div class="subjects-field"><label>Curriculum memorandum</label><textarea name="memorandum" required maxlength="500">{{ $program->memorandum }}</textarea></div>
                                                    <div class="subjects-field"><label>Major (optional)</label><input name="major" value="{{ $program->major }}" maxlength="120"></div>
                                                    <button class="subjects-btn" type="submit">Save program details</button>
                                                </form>
                                            </details>
                                            <div class="card-actions">
                                                <a class="subjects-btn" href="{{ route('chair.managesubjects', ['program' => $program->id]) }}">Open program</a>
                                                <form method="POST" action="{{ route('chair.managesubjects.program.delete', $program) }}" onsubmit="return confirm('Delete this program and its catalog subjects? Existing faculty assignments will keep their saved details.');">
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
                    <a class="subjects-back" href="{{ route('chair.managesubjects') }}">← All programs</a>
                    <section class="subjects-card">
                        <h2>{{ $selectedProgram->name }}@if(strcasecmp($selectedProgram->name, $selectedProgram->code) !== 0) ({{ $selectedProgram->code }}) @endif</h2>
                        <p>Memorandum: <strong>{{ $selectedProgram->memorandum }}</strong>@if($selectedProgram->major) · Program major: <strong>{{ $selectedProgram->major }}</strong>@endif</p>
                        <h3 style="font-size:20px;color:#294d6b;margin:22px 0 12px">Step 2 · Choose a year level</h3>
                        <div class="year-list">
                            @foreach($yearLevels as $yearLevel)
                                <article class="year-card">
                                    <h3>{{ $yearLevel }}</h3>
                                    <p>{{ $selectedProgram->subjects->where('year_level', $yearLevel)->count() }} subject(s) added across both semesters</p>
                                    <a class="subjects-btn {{ $selectedYear === $yearLevel ? 'secondary' : '' }}" href="{{ route('chair.managesubjects', ['program' => $selectedProgram->id, 'year' => $yearLevel]) }}">{{ $selectedYear === $yearLevel ? 'Year level open' : 'Choose year level' }}</a>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    @if($selectedYear)
                        <section class="subjects-card">
                            <div class="semester-heading">
                                <div>
                                    <h2>Step 3 · Choose a semester</h2>
                                    <p>Select where the subjects will be taught. Each semester has a separate subject list.</p>
                                </div>
                                @if($selectedSemester)<span class="semester-badge">{{ $selectedYear }} · {{ $selectedSemester }}</span>@endif
                            </div>
                            <div class="semester-list">
                                @foreach($semesterOptions as $semesterOption)
                                    @php($semesterCount = $selectedProgram->subjects->where('year_level', $selectedYear)->where('semester', $semesterOption)->count())
                                    <article class="semester-card {{ $selectedSemester === $semesterOption ? 'active' : '' }}">
                                        <h3>{{ $semesterOption }}</h3>
                                        <p>{{ $semesterCount }} subject(s) added</p>
                                        <a class="subjects-btn {{ $selectedSemester === $semesterOption ? 'secondary' : '' }}" href="{{ route('chair.managesubjects', ['program' => $selectedProgram->id, 'year' => $selectedYear, 'semester' => $semesterOption]) }}">
                                            {{ $selectedSemester === $semesterOption ? 'Selected semester' : 'Open subject list' }}
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        @if($selectedSemester)
                            <div class="subjects-grid">
                                <section class="subjects-card">
                                    <h2>Step 4 · Add a subject</h2>
                                    <p>Add the course to <strong>{{ $selectedYear }} · {{ $selectedSemester }}</strong>. The program major applies automatically.</p>
                                    <form method="POST" action="{{ route('chair.managesubjects.subject.store', ['program' => $selectedProgram, 'year' => $selectedYear]) }}">
                                        @csrf
                                        <input type="hidden" name="semester" value="{{ $selectedSemester }}">
                                        <div class="subjects-field"><label for="course_code">Course code</label><input id="course_code" name="course_code" value="{{ old('course_code') }}" placeholder="IT 101" required maxlength="50"></div>
                                        <div class="subjects-field"><label for="course_title">Course title</label><input id="course_title" name="course_title" value="{{ old('course_title') }}" placeholder="Introduction to Computing" required maxlength="255"></div>
                                        <button class="subjects-btn" type="submit">Add subject to {{ $selectedSemester }}</button>
                                    </form>
                                </section>

                                <section class="subjects-card">
                                    <h2>{{ $selectedSemester }} subjects</h2>
                                    <p>Codes ending in “.1” are identified as labs when assigned to faculty; other codes are lectures.</p>
                                    @if($subjects->isEmpty())
                                        <div class="subjects-empty">No subjects in this semester yet. Add the first subject using the form.</div>
                                    @else
                                        <div class="subjects-table-wrap">
                                            <table class="subjects-table">
                                                <thead><tr><th>Course code</th><th>Course title</th><th>Program major</th><th>Manage</th></tr></thead>
                                                <tbody>
                                                @foreach($subjects as $subject)
                                                    <tr>
                                                        <td><strong>{{ $subject->course_code }}</strong></td>
                                                        <td>{{ $subject->course_title }}</td>
                                                        <td>{{ $selectedProgram->major ?: '—' }}</td>
                                                        <td>
                                                            <div class="card-actions" style="margin:0">
                                                                <details class="subject-edit"><summary>Edit</summary>
                                                                    <form method="POST" action="{{ route('chair.managesubjects.subject.update', ['program' => $selectedProgram, 'year' => $selectedYear, 'subject' => $subject]) }}">
                                                                        @csrf @method('PUT')
                                                                        <div class="subjects-field"><label>Semester</label><select name="semester" required><option value="1st Semester" {{ $subject->semester === '1st Semester' ? 'selected' : '' }}>1st Semester</option><option value="2nd Semester" {{ $subject->semester === '2nd Semester' ? 'selected' : '' }}>2nd Semester</option></select></div>
                                                                        <div class="subjects-field"><label>Course code</label><input name="course_code" value="{{ $subject->course_code }}" required maxlength="50"></div>
                                                                        <div class="subjects-field"><label>Course title</label><input name="course_title" value="{{ $subject->course_title }}" required maxlength="255"></div>
                                                                        <button class="subjects-btn" type="submit">Save changes</button>
                                                                    </form>
                                                                </details>
                                                                <form method="POST" action="{{ route('chair.managesubjects.subject.delete', ['program' => $selectedProgram, 'year' => $selectedYear, 'subject' => $subject, 'semester' => $selectedSemester]) }}" onsubmit="return confirm('Delete this subject from the catalog? Existing faculty assignments keep their saved details.');">
                                                                    @csrf @method('DELETE')<button class="subjects-btn danger" type="submit">Delete</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </section>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </main>
    </div>
</div>

@include('footerheader.footer')
