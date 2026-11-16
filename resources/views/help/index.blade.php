@include('footerheader.header')

@php
    $dashboardRoute = match ($role) {
        'admin' => 'admin.dashboard',
        'chair' => 'chair.dashboard',
        default => 'faculty.dashboard',
    };
    $roleName = match ($role) {
        'admin' => 'Administrator',
        'chair' => 'Department chair',
        default => 'Instructor',
    };
@endphp

<div class="wrapper">
    <div class="main-layout">
        @include('partials.sidebar')

        <main class="content help-center" id="helpCenter">
            <style>
                .help-center { color:#263b30; }
                .help-wrap { width:min(1120px,100%); margin:0 auto; }
                .help-hero { display:flex; align-items:center; justify-content:space-between; gap:24px; margin-bottom:20px; padding:clamp(22px,4vw,36px); border:1px solid #d9e4d9; border-radius:20px; background:linear-gradient(120deg,#173b33,#32654a); color:#fff; box-shadow:0 15px 35px rgba(31,57,43,.12); }
                .help-hero-copy { max-width:700px; }
                .help-kicker { margin:0 0 8px; color:#e3d29f; font-size:11px; font-weight:850; letter-spacing:.12em; text-transform:uppercase; }
                .help-hero h1 { margin:0; font:800 clamp(27px,4vw,38px)/1.12 var(--wr-display,Manrope,sans-serif); letter-spacing:-.035em; }
                .help-hero p:last-child { max-width:650px; margin:12px 0 0; color:#e4ece5; font-size:14px; line-height:1.65; }
                .help-home-link { display:inline-flex; flex:0 0 auto; align-items:center; gap:8px; min-height:42px; padding:0 14px; border:1px solid rgba(255,255,255,.35); border-radius:10px; color:#fff; font-size:12px; font-weight:800; text-decoration:none; }
                .help-home-link:hover { background:rgba(255,255,255,.12); }
                .help-tools { display:grid; grid-template-columns:minmax(0,1fr) auto; align-items:center; gap:12px; margin-bottom:16px; }
                .help-search { display:flex; align-items:center; gap:10px; min-height:48px; padding:0 14px; border:1px solid #d9e3d9; border-radius:12px; background:#fff; box-shadow:0 3px 10px rgba(27,59,41,.035); }
                .help-search i { color:#65806e; }
                .help-search input { width:100%; border:0; outline:0; background:transparent; color:#263b30; font:600 13px/1.4 var(--wr-body,Arial,sans-serif); }
                .help-search input::placeholder { color:#89958c; }
                .help-result-count { color:#68786e; font-size:11px; font-weight:700; white-space:nowrap; }
                .help-shortcuts { display:flex; flex-wrap:wrap; gap:8px; margin:0 0 18px; }
                .help-shortcuts a { display:inline-flex; align-items:center; gap:7px; min-height:34px; padding:0 11px; border:1px solid #dce5db; border-radius:999px; background:#fff; color:#3b5947; font-size:11px; font-weight:750; text-decoration:none; }
                .help-shortcuts a:hover { border-color:#a9bfaa; background:#f2f7f0; }
                .help-section { scroll-margin-top:112px; margin:0 0 14px; border:1px solid #dfe7dd; border-radius:15px; background:#fff; box-shadow:0 7px 20px rgba(31,57,43,.04); overflow:hidden; }
                .help-section[hidden] { display:none !important; }
                .help-section summary { display:flex; align-items:center; gap:12px; min-height:68px; padding:15px 19px; cursor:pointer; list-style:none; }
                .help-section summary::-webkit-details-marker { display:none; }
                .help-section summary::after { content:'+'; margin-left:auto; color:#66806d; font-size:20px; font-weight:500; }
                .help-section[open] summary::after { content:'−'; }
                .help-section-icon { display:grid; flex:0 0 38px; width:38px; height:38px; place-items:center; border:1px solid #e2e9df; border-radius:12px; background:#edf3eb; color:#2e6545; }
                .help-section-title { min-width:0; }
                .help-section-title strong { display:block; color:#294437; font-size:14px; font-weight:850; }
                .help-section-title small { display:block; margin-top:3px; color:#758279; font-size:11px; line-height:1.4; }
                .help-section-body { padding:0 20px 20px 69px; color:#4d5e53; font-size:13px; line-height:1.65; }
                .help-section-body p { margin:0 0 10px; }
                .help-section-body ol,.help-section-body ul { display:grid; gap:7px; margin:8px 0 13px; padding-left:19px; }
                .help-section-body li { padding-left:2px; }
                .help-section-body strong { color:#2d4938; }
                .help-section-body a { color:#246342; font-weight:750; text-underline-offset:2px; }
                .help-callout { margin-top:12px; padding:12px 14px; border:1px solid #e6e5d6; border-radius:10px; background:#f8f7ef; color:#59614c; font-size:12px; line-height:1.55; }
                .help-empty { padding:26px; border:1px dashed #cbd8cc; border-radius:13px; color:#64746a; background:#fff; text-align:center; font-size:13px; }
                .help-footer { margin:20px 0 0; padding:16px 18px; border:1px solid #dce5da; border-radius:13px; background:rgba(255,255,255,.74); color:#637269; font-size:12px; line-height:1.6; }
                @media(max-width:640px) {
                    .help-hero { display:block; }
                    .help-home-link { margin-top:16px; }
                    .help-tools { grid-template-columns:1fr; }
                    .help-result-count { padding-left:2px; }
                    .help-section summary { padding:13px; }
                    .help-section-body { padding:0 15px 17px; }
                }
            </style>

            <div class="help-wrap">
                <section class="help-hero" aria-labelledby="helpTitle">
                    <div class="help-hero-copy">
                        <p class="help-kicker"><i class="fa-solid fa-compass" aria-hidden="true"></i> WebRoomSched Help Center</p>
                        <h1 id="helpTitle">A practical guide for {{ $roleName }}s</h1>
                        <p>Find clear steps for the tools in your workspace. Search a task or open a guide below; each section links directly to the page where you can do it.</p>
                    </div>
                    <a class="help-home-link" href="{{ route($dashboardRoute) }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to dashboard</a>
                </section>

                <div class="help-tools">
                    <label class="help-search" for="helpSearch">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input type="search" id="helpSearch" placeholder="Search help, e.g. publish a schedule" autocomplete="off">
                    </label>
                    <span class="help-result-count" id="helpResultCount" aria-live="polite"></span>
                </div>

                <nav class="help-shortcuts" aria-label="Help topics">
                    @if($role === 'admin')
                        <a href="#admin-setup"><i class="fa-solid fa-building-columns" aria-hidden="true"></i> Campus setup</a>
                        <a href="#admin-users"><i class="fa-solid fa-users" aria-hidden="true"></i> User accounts</a>
                        <a href="#admin-operations"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i> Schedules &amp; overrides</a>
                    @elseif($role === 'chair')
                        <a href="#chair-start"><i class="fa-solid fa-list-check" aria-hidden="true"></i> Start here</a>
                        <a href="#chair-schedule"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i> Build a schedule</a>
                        <a href="#chair-cross"><i class="fa-solid fa-people-arrows-left-right" aria-hidden="true"></i> Cross-scheduling</a>
                    @else
                        <a href="#faculty-classes"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i> My classes</a>
                        <a href="#faculty-rooms"><i class="fa-solid fa-door-open" aria-hidden="true"></i> Find a room</a>
                        <a href="#faculty-coordination"><i class="fa-solid fa-right-left" aria-hidden="true"></i> Reservations &amp; swaps</a>
                    @endif
                    <a href="#common-help"><i class="fa-regular fa-circle-question" aria-hidden="true"></i> General help</a>
                </nav>

                <div id="helpSections">
                    @if($role === 'admin')
                        <details class="help-section" id="admin-setup" open>
                            <summary><span class="help-section-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span><span class="help-section-title"><strong>Set up colleges, buildings, and rooms</strong><small>Prepare the campus structure before schedules are managed.</small></span></summary>
                            <div class="help-section-body">
                                <ol>
                                    <li>Open <a href="{{ route('admin.departments') }}">Colleges</a> and add each college with its name and code. A logo can be added or changed there.</li>
                                    <li>Open <a href="{{ route('admin.buildings') }}">Buildings &amp; rooms</a>. Add a building, then add its rooms and enter the room details, including seating capacity and available equipment.</li>
                                    <li>Review room ownership and capacity before chairs begin scheduling. Chairs use this information to pick rooms and receive enrollment-capacity warnings.</li>
                                </ol>
                                <p>Use the college and room directory to correct names, codes, logos, or room details when campus information changes.</p>
                            </div>
                        </details>

                        <details class="help-section" id="admin-users">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-user-gear" aria-hidden="true"></i></span><span class="help-section-title"><strong>Create and manage user accounts</strong><small>Set up administrators, department chairs, and instructors.</small></span></summary>
                            <div class="help-section-body">
                                <ol>
                                    <li>Go to <a href="{{ route('admin.manageusers') }}">User accounts</a> and choose the option to add an account.</li>
                                    <li>Enter the person’s account details, select the correct role, and associate the account with a college where applicable.</li>
                                    <li>Use the account list to review or edit users, reset access when needed, and deactivate or restore an account according to its available status.</li>
                                </ol>
                                <div class="help-callout"><strong>Role matters:</strong> chairs manage their college’s subjects and schedules; instructors view their teaching information and coordinate rooms. Check the role and college before saving.</div>
                            </div>
                        </details>

                        <details class="help-section" id="admin-operations">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></span><span class="help-section-title"><strong>Review schedules and create date-specific overrides</strong><small>Browse published timetables or reserve a room for a one-time event.</small></span></summary>
                            <div class="help-section-body">
                                <p>Open <a href="{{ route('admin.schedules') }}">Schedules</a> to browse rooms and view their published timetables. Use the schedule view to confirm what is already assigned.</p>
                                <p>For a special event, open <a href="{{ route('admin.roomreassignment') }}">Schedule Overrides</a> and follow the page’s steps:</p>
                                <ol>
                                    <li>Select the college that owns the room, then choose a building and room.</li>
                                    <li>Set the date, start and end time, and the occupant or purpose details requested by the form.</li>
                                    <li>Review any conflict information. Existing overrides block the reservation; timetable conflicts may require acknowledging the warning before saving.</li>
                                    <li>Submit the override and check the room schedule to confirm it appears at the correct date and time.</li>
                                </ol>
                                <div class="help-callout">A schedule override applies to the selected date and time. It is separate from a repeating class timetable.</div>
                            </div>
                        </details>

                        <details class="help-section">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-bell" aria-hidden="true"></i></span><span class="help-section-title"><strong>Keep an eye on campus activity</strong><small>Use the dashboard, notifications, and profile tools.</small></span></summary>
                            <div class="help-section-body">
                                <ul>
                                    <li>The <a href="{{ route('admin.dashboard') }}">Campus overview</a> summarizes campus information and links to common administration tasks.</li>
                                    <li>Choose the bell in the header to review recent notices. Open a notice for the related update.</li>
                                    <li>Choose your profile in the header to update your sign-in username, password, or profile photo.</li>
                                </ul>
                            </div>
                        </details>

                    @elseif($role === 'chair')
                        <details class="help-section" id="chair-start" open>
                            <summary><span class="help-section-icon"><i class="fa-solid fa-list-check" aria-hidden="true"></i></span><span class="help-section-title"><strong>Start a new scheduling cycle</strong><small>Choose the right term and prepare subjects and instructors first.</small></span></summary>
                            <div class="help-section-body">
                                <ol>
                                    <li>Open <a href="{{ route('chair.schedule.library') }}">Schedule Library</a>. Create or select the academic term you are preparing, and activate the term that should be used for current scheduling.</li>
                                    <li>Review <a href="{{ route('chair.managesubjects') }}">Manage Subjects</a>. Keep programs and course catalog entries up to date before assigning courses to instructors.</li>
                                    <li>Open <a href="{{ route('chair.setFaculty') }}">Instructors &amp; Subjects</a>. Select or import an instructor, assign the course, and enter the number of students. That count is shown in the schedule-entry dialog and used for room-capacity warnings.</li>
                                    <li>Go to <a href="{{ route('chair.setschedule') }}">Set Schedule</a> to choose a room and open its timetable.</li>
                                </ol>
                                <div class="help-callout">If a course is missing from the instructor’s assignment list, check the subject catalog and the selected instructor before opening the timetable.</div>
                            </div>
                        </details>

                        <details class="help-section" id="chair-schedule">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></span><span class="help-section-title"><strong>Build, check, and publish a room schedule</strong><small>Place assigned subjects into the timetable and save your work.</small></span></summary>
                            <div class="help-section-body">
                                <ol>
                                    <li>From <a href="{{ route('chair.setschedule') }}">Set Schedule</a>, choose the room you want to manage. Confirm its displayed capacity and facilities.</li>
                                    <li>In the timetable, select a cell and right-click it (or use the schedule editor’s cell action) to open Schedule Entry. Enter a course code and choose the matching instructor/year-level assignment if more than one match appears.</li>
                                    <li>Review the course, instructor, year, and student count shown in the dialog. Confirm the entry to place it in the selected cell.</li>
                                    <li>If enrollment exceeds the room capacity, review the warning. Choose <strong>Cancel entry</strong> to change the room, or <strong>Proceed anyway</strong> if you are authorized to use it.</li>
                                    <li>Repeat for other cells. Use the bottom <strong>Add Time Row</strong> button for another consecutive hourly slot. Existing time labels are fixed.</li>
                                    <li>Review the full timetable, then choose <strong>Upload Schedule</strong> or the save action shown on the page. A placed cell is still a draft in the browser until you save or upload.</li>
                                </ol>
                                <ul>
                                    <li>Use <strong>Delete Row</strong> to select rows for removal; check the confirmation before deleting.</li>
                                    <li>Use the merge controls for adjacent cells or classes that share a meeting. Existing schedules belonging to another college or a reservation may be locked.</li>
                                    <li>Use the schedule’s print or Excel download controls to keep a copy for review.</li>
                                </ul>
                            </div>
                        </details>

                        <details class="help-section" id="chair-cross">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-people-arrows-left-right" aria-hidden="true"></i></span><span class="help-section-title"><strong>Coordinate with another college</strong><small>Use Cross Scheduling and requests for shared rooms or classes.</small></span></summary>
                            <div class="help-section-body">
                                <ol>
                                    <li>Open <a href="{{ route('chair.modifyschedule') }}">Cross Scheduling</a> to find rooms and schedules associated with other colleges.</li>
                                    <li>Choose the relevant room to review its existing timetable. A locked or read-only room cannot be edited by your college until the room owner allows cross-college scheduling.</li>
                                    <li>When a room is available to your college, use the same cell-entry workflow as Set Schedule. The course assignment and capacity checks apply here too.</li>
                                    <li>Use <a href="{{ route('chair.requests') }}">Requests &amp; Activity</a> to review incoming coordination requests and follow their status. Notifications in the header link to relevant updates.</li>
                                </ol>
                                <p>For an existing class owned by another college, use the merge/request action in the editor when available. The owner must approve a request before a cross-college merge takes effect.</p>
                            </div>
                        </details>

                        <details class="help-section">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-box-archive" aria-hidden="true"></i></span><span class="help-section-title"><strong>Reuse a prior schedule and check availability</strong><small>Use the library and all-room view to plan efficiently.</small></span></summary>
                            <div class="help-section-body">
                                <ul>
                                    <li>In <a href="{{ route('chair.schedule.library') }}">Schedule Library</a>, review past terms and use the reuse action when you want to carry eligible schedule data into another term. Review the result before publishing.</li>
                                    <li>Open <a href="{{ route('chair.roomavailability') }}">All-Room Availability</a> to compare room schedules across colleges. Select a listed time to open the matching schedule editor when you have access.</li>
                                    <li>Use the header notifications or <a href="{{ route('chair.requests') }}">Requests &amp; Activity</a> to follow approvals and scheduling changes.</li>
                                </ul>
                            </div>
                        </details>

                    @else
                        <details class="help-section" id="faculty-classes" open>
                            <summary><span class="help-section-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span><span class="help-section-title"><strong>View your teaching schedule</strong><small>Find your assigned classes and check campus timetables.</small></span></summary>
                            <div class="help-section-body">
                                <ul>
                                    <li>Open <a href="{{ route('faculty.myschedules') }}">My Schedules</a> to review the classes assigned to you. Check the day, time, room, and course details.</li>
                                    <li>Open <a href="{{ route('faculty.schedules') }}">All Schedules</a> to browse campus timetables. Use the page’s filters to narrow the list, then open a room schedule for more detail.</li>
                                    <li>If a course is missing or its details look wrong, contact your department chair; instructors do not edit the published college timetable from these pages.</li>
                                </ul>
                            </div>
                        </details>

                        <details class="help-section" id="faculty-rooms">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-door-open" aria-hidden="true"></i></span><span class="help-section-title"><strong>Find and reserve a room</strong><small>Check vacancy before requesting a room.</small></span></summary>
                            <div class="help-section-body">
                                <ol>
                                    <li>Open <a href="{{ route('faculty.vacant') }}">Vacant Rooms</a> and filter by the date, time, building, or room details available on the page.</li>
                                    <li>Choose a room and time that show as available. Review the room details and any reservation rules displayed in the form.</li>
                                    <li>Submit the reservation request and wait for its status to update. A request is not confirmed until the page shows it as approved or reserved.</li>
                                    <li>Open <a href="{{ route('faculty.bookings') }}">My Reservations</a> to review upcoming, expired, or cancelled requests. Use the available cancel action if your plans change.</li>
                                </ol>
                                <div class="help-callout">Availability can change while you are deciding. If a time is rejected, refresh the room list and choose another open slot.</div>
                            </div>
                        </details>

                        <details class="help-section" id="faculty-coordination">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-right-left" aria-hidden="true"></i></span><span class="help-section-title"><strong>Request or respond to a room swap</strong><small>Coordinate a room change with another instructor.</small></span></summary>
                            <div class="help-section-body">
                                <ol>
                                    <li>Open <a href="{{ route('faculty.room-swap') }}">Room Swap</a> and choose the class or room you want to change, then select the proposed replacement and provide the requested details.</li>
                                    <li>Review the comparison and submit the request. It remains pending until the other instructor responds and any required approval is complete.</li>
                                    <li>Open <a href="{{ route('faculty.room.swap.requests') }}">Swap Requests &amp; History</a> to respond to requests sent to you or review requests you have made.</li>
                                    <li>Approve or decline a request using the controls on its card. You may cancel your own pending request when that option is available.</li>
                                </ol>
                                <p>Check your notifications for status changes. Confirm the updated class room and time in <a href="{{ route('faculty.myschedules') }}">My Schedules</a> after a swap is completed.</p>
                            </div>
                        </details>

                        <details class="help-section">
                            <summary><span class="help-section-icon"><i class="fa-solid fa-user-circle" aria-hidden="true"></i></span><span class="help-section-title"><strong>Manage your account</strong><small>Update sign-in details and follow schedule notices.</small></span></summary>
                            <div class="help-section-body">
                                <ul>
                                    <li>Choose your profile in the header to change your username, password, or profile photo.</li>
                                    <li>Choose the bell in the header to review recent schedule and room-coordination updates.</li>
                                    <li>Sign out using the exit icon when you finish on a shared computer.</li>
                                </ul>
                            </div>
                        </details>
                    @endif

                    <details class="help-section" id="common-help">
                        <summary><span class="help-section-icon"><i class="fa-regular fa-circle-question" aria-hidden="true"></i></span><span class="help-section-title"><strong>General help and common issues</strong><small>Quick checks when something is missing or will not save.</small></span></summary>
                        <div class="help-section-body">
                            <ul>
                                <li><strong>Can’t see a page?</strong> Use the links for your role in the left navigation. Some actions only appear to the role or college that owns the record.</li>
                                <li><strong>A schedule change is missing?</strong> Return to the schedule editor and confirm that you uploaded or saved it. Refresh the page after a successful save.</li>
                                <li><strong>A room or subject is unavailable?</strong> Check the selected term, room access, course assignment, and any conflicting schedule or reservation.</li>
                                <li><strong>Need help from campus staff?</strong> Share the page name, the course or room involved, and the day/time of the issue with your campus administrator or department chair. Do not include your password.</li>
                            </ul>
                        </div>
                    </details>
                </div>

                <div class="help-empty" id="helpNoResults" hidden>No help topics match that search. Try a shorter phrase, such as “room”, “schedule”, or “password”.</div>
                <p class="help-footer"><strong>Tip:</strong> Use the search above to filter this guide, or choose Help in the header from any administrator, chair, or instructor page to return here.</p>
            </div>
        </main>
    </div>
</div>

<script>
(() => {
    const search = document.getElementById('helpSearch');
    const sections = Array.from(document.querySelectorAll('#helpSections .help-section'));
    const count = document.getElementById('helpResultCount');
    const empty = document.getElementById('helpNoResults');
    const normalize = value => String(value || '').toLocaleLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    function filterHelp() {
        const query = normalize(search?.value).trim();
        let visible = 0;
        sections.forEach(section => {
            const matches = !query || normalize(section.innerText).includes(query);
            section.hidden = !matches;
            if (matches) visible++;
        });
        if (count) count.textContent = query ? `${visible} ${visible === 1 ? 'topic' : 'topics'} found` : `${visible} help topics`;
        if (empty) empty.hidden = visible !== 0;
    }

    search?.addEventListener('input', filterHelp);
    filterHelp();
})();
</script>
