<aside class="sidebar faculty-sidebar admin-sidebar" aria-label="Administrator navigation">
    <a class="portal-brand" href="{{ route('admin.dashboard') }}" aria-label="WebRoomSched administration home">
        <span class="portal-brand-mark" aria-hidden="true">W</span>
        <span class="portal-brand-copy"><strong>WebRoomSched</strong><small>Campus administration</small></span>
    </a>


    <nav class="faculty-sidebar-nav" aria-label="College chair portal">
        <span class="faculty-sidebar-section">OVERVIEW</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('chair.dashboard', 'chair.dashboard.page') ? 'active' : '' }}">
                <a href="{{ route('chair.dashboard') }}" @if(request()->routeIs('chair.dashboard', 'chair.dashboard.page')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-table-columns" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('chair.requests') ? 'active' : '' }}">
                <a href="{{ route('chair.requests') }}" @if(request()->routeIs('chair.requests')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-inbox" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Requests &amp; Activity</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">SCHEDULING</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('chair.schedule.library', 'chair.academic.terms.*') ? 'active' : '' }}">
                <a href="{{ route('chair.schedule.library') }}" @if(request()->routeIs('chair.schedule.library', 'chair.academic.terms.*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-box-archive" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Schedule Library</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('chair.setschedule', 'chair.excel', 'chair.modifyschedule', 'chair.roomavailability') ? 'active' : '' }}">
                <a href="{{ route('chair.setschedule') }}" @if(request()->routeIs('chair.setschedule', 'chair.excel', 'chair.modifyschedule', 'chair.roomavailability')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Set Schedule</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">COLLEGE</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('chair.setFaculty', 'chair.faculty.subjects*') ? 'active' : '' }}">
                <a href="{{ route('chair.setFaculty') }}" @if(request()->routeIs('chair.setFaculty', 'chair.faculty.subjects*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-user-gear" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Instructors & Subjects</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('chair.managesubjects*') ? 'active' : '' }}">
                <a href="{{ route('chair.managesubjects') }}" @if(request()->routeIs('chair.managesubjects*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Manage Subjects</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="faculty-sidebar-footer">
        <span class="faculty-footer-indicator" aria-hidden="true"></span>
        <div>
            <strong>College workspace</strong>
            <span>Schedules, instructor, and subjects</span>
        </div>
    </div>
</aside>

