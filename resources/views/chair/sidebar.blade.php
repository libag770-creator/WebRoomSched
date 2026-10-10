<aside class="sidebar">

    <div class="sidebar-brand">
        <img src="{{ asset('images/images.jpg') }}" alt="BSU Bokod Campus">
        <div>
            <strong>WebRoomSched</strong>
            <span>Department Chair Portal</span>
        </div>
    </div>

    <ul class="sidebar-menu">

        <li class="{{ request()->routeIs('chair.dashboard', 'chair.dashboard.page') ? 'active' : '' }}">
            <a href="{{ route('chair.dashboard') }}">
                <span class="sidebar-icon"><i class="fa-solid fa-table-columns"></i></span>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('chair.setFaculty', 'chair.faculty.subjects*') ? 'active' : '' }}">
            <a href="{{ route('chair.setFaculty') }}">
                <span class="sidebar-icon"><i class="fa-solid fa-user-gear"></i></span>
                <span class="sidebar-text">Faculty & Subjects</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('chair.schedule.library', 'chair.academic.terms.*') ? 'active' : '' }}">
            <a href="{{ route('chair.schedule.library') }}">
                <span class="sidebar-icon"><i class="fa-solid fa-box-archive"></i></span>
                <span class="sidebar-text">Schedule Library</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('chair.setschedule', 'chair.excel') ? 'active' : '' }}">
            <a href="{{ route('chair.setschedule') }}">
                <span class="sidebar-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span class="sidebar-text">Set Schedule</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('chair.drafts') || request()->routeIs('chair.drafts.*') ? 'active' : '' }}">
            <a href="{{ route('chair.drafts') }}">
                <span class="sidebar-icon"><i class="fa-solid fa-file-pen"></i></span>
                <span class="sidebar-text">Drafts</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('chair.modifyschedule') ? 'active' : '' }}">
            <a href="{{ route('chair.modifyschedule') }}">
                <span class="sidebar-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                <span class="sidebar-text">Schedule Editor</span>
            </a>
        </li>

        
        <li class="{{ request()->routeIs('chair.curriculum') ? 'active' : '' }}">
            <a href="{{ route('chair.curriculum') }}">
                <span class="sidebar-icon"><i class="fa-solid fa-book-open"></i></span>
                <span class="sidebar-text">Curriculum</span>
            </a>
        </li>
    </ul>

</aside>