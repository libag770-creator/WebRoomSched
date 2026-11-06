<aside class="sidebar faculty-sidebar admin-sidebar" aria-label="Administrator navigation">
    <a class="portal-brand" href="{{ route('admin.dashboard') }}" aria-label="WebRoomSched administration home">
        <span class="portal-brand-mark" aria-hidden="true">W</span>
        <span class="portal-brand-copy"><strong>WebRoomSched</strong><small>Campus administration</small></span>
    </a>

    <nav class="faculty-sidebar-nav" aria-label="Administrator portal">
        <span class="faculty-sidebar-section">OVERVIEW</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-table-columns" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Campus overview</span>
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
                    <span class="sidebar-text">Colleges</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.buildings*') ? 'active' : '' }}">
                <a href="{{ route('admin.buildings') }}" @if(request()->routeIs('admin.buildings*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Buildings &amp; rooms</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.roomreassignment*') ? 'active' : '' }}">
                <a href="{{ route('admin.roomreassignment') }}" @if(request()->routeIs('admin.roomreassignment*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Schedule overrides</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">ACCESS</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.manageusers*') ? 'active' : '' }}">
                <a href="{{ route('admin.manageusers') }}" @if(request()->routeIs('admin.manageusers*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                    <span class="sidebar-text">User accounts</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="faculty-sidebar-footer">
        <span class="faculty-footer-indicator" aria-hidden="true"></span>
        <div><strong>BSU Bokod Campus</strong><span>Campus operations</span></div>
    </div>
</aside>
