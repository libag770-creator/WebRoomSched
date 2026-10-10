<aside class="sidebar faculty-sidebar" aria-label="Faculty navigation">
  

    <div class="faculty-sidebar-workspace">
        <span class="faculty-workspace-mark"><i class="fa-solid fa-chalkboard-user" aria-hidden="true"></i></span>
        <div>
            <span class="faculty-workspace-label">WORKSPACE</span>
            <strong>Faculty Portal</strong>
        </div>
    </div>

    <nav class="faculty-sidebar-nav" aria-label="Faculty portal">
        <span class="faculty-sidebar-section">GENERAL</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                <a href="{{ route('faculty.dashboard') }}" @if(request()->routeIs('faculty.dashboard')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-table-columns" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">SCHEDULE &amp; ROOMS</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('faculty.schedules') ? 'active' : '' }}">
                <a href="{{ route('faculty.schedules') }}" @if(request()->routeIs('faculty.schedules')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></span>
                    <span class="sidebar-text">All Schedules</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('faculty.myschedules') ? 'active' : '' }}">
                <a href="{{ route('faculty.myschedules') }}" @if(request()->routeIs('faculty.myschedules')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></span>
                    <span class="sidebar-text">My Schedules</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('faculty.vacant') ? 'active' : '' }}">
                <a href="{{ route('faculty.vacant') }}" @if(request()->routeIs('faculty.vacant')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-door-open" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Vacant Rooms</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">RESERVATIONS</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('faculty.room-swap') ? 'active' : '' }}">
                <a href="{{ route('faculty.room-swap') }}" @if(request()->routeIs('faculty.room-swap')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-right-left" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Room Swap</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('faculty.bookings') ? 'active' : '' }}">
                <a href="{{ route('faculty.bookings') }}" @if(request()->routeIs('faculty.bookings')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-regular fa-calendar-check" aria-hidden="true"></i></span>
                    <span class="sidebar-text">My Reservations</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="faculty-sidebar-footer">
        <span class="faculty-footer-indicator" aria-hidden="true"></span>
        <div>
            <strong>Scheduling workspace</strong>
            <span>Room and class coordination</span>
        </div>
    </div>
</aside>
