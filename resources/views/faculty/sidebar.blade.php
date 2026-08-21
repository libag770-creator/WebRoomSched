<aside class="sidebar">

    <ul>

        <li class="{{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
            <a href="{{ route('faculty.dashboard') }}">
                <i class="fa-solid fa-table-columns"></i>
                Dashboard
            </a>
        </li>

        <li class="{{ request()->routeIs('faculty.schedules') ? 'active' : '' }}">
            <a href="{{ route('faculty.schedules') }}">
                <i class="fa-solid fa-calendar-days"></i>
                Schedules
            </a>
        </li>

        <li class="{{ request()->routeIs('faculty.vacant') ? 'active' : '' }}">
            <a href="{{ route('faculty.vacant') }}">
                <i class="fa-solid fa-door-open"></i>
                Vacant Rooms
            </a>
        </li>

        <li class="{{ request()->routeIs('faculty.room-swap') ? 'active' : '' }}">
            <a href="{{ route('faculty.room-swap') }}">
                <i class="fa-solid fa-right-left"></i>
                Room Swap
            </a>
        </li>

        <li class="{{ request()->routeIs('faculty.bookings') ? 'active' : '' }}">
            <a href="{{ route('faculty.bookings') }}">
                <i class="fa-solid fa-book"></i>
                My Bookings
            </a>
        </li>

    </ul>

</aside>