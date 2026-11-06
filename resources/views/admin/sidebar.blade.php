<aside class="sidebar faculty-sidebar portal-sidebar" aria-label="Administrator navigation">
    <div class="faculty-sidebar-brand">
        <img src="{{ asset('images/images.jpg') }}" alt="BSU Bokod Campus">
        <div class="faculty-sidebar-brand-copy">
            <strong>WebRoomSched</strong>
            <span>BSU Bokod Campus</span>
        </div>
    </div>

 

    <nav class="faculty-sidebar-nav" aria-label="Administrator portal">
        <span class="faculty-sidebar-section">OVERVIEW</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-table-columns" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.schedules', 'admin.view.schedule') ? 'active' : '' }}">
                <a href="{{ route('admin.schedules') }}" @if(request()->routeIs('admin.schedules', 'admin.view.schedule')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Schedules</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">CAMPUS</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.departments*') ? 'active' : '' }}">
                <a href="{{ route('admin.departments') }}" @if(request()->routeIs('admin.departments*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Departments</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.buildings*') ? 'active' : '' }}">
                <a href="{{ route('admin.buildings') }}" @if(request()->routeIs('admin.buildings*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Buildings &amp; Rooms</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.roomreassignment*') ? 'active' : '' }}">
                <a href="{{ route('admin.roomreassignment') }}" @if(request()->routeIs('admin.roomreassignment*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Schedule Overrides</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">PEOPLE</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.manageusers*') ? 'active' : '' }}">
                <a href="{{ route('admin.manageusers') }}" @if(request()->routeIs('admin.manageusers*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Manage Users</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="faculty-sidebar-footer">
        <span class="faculty-footer-indicator" aria-hidden="true"></span>
        <div>
            <strong>Administration workspace</strong>
            <span>Campus rooms, schedules, and users</span>
        </div>
    </div>
</aside>
