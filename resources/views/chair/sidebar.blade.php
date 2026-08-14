<aside class="sidebar">

    <ul>

        <li class="{{ request()->routeIs('chair.dashboard') ? 'active' : '' }}">
            <a href="{{ route('chair.dashboard') }}">
                <i class="fa-solid fa-table-columns"></i>
                Dashboard
            </a>
        </li>

        <li class="{{ request()->routeIs('chair.setschedule') ? 'active' : '' }}">
            <a href="{{ route('chair.setschedule') }}">
                <i class="fa-solid fa-calendar-days"></i>
                Set Schedule
            </a>
        </li>

        <li class="{{ request()->routeIs('chair.drafts') ? 'active' : '' }}">
            <a href="{{ route('chair.drafts') }}">
                <i class="fa-solid fa-door-open"></i>
                Drafts
            </a>
        </li>

        <li class="{{ request()->routeIs('chair.modifyschedule') ? 'active' : '' }}">
            <a href="{{ route('chair.modifyschedule') }}">
                <i class="fa-solid fa-right-left"></i>
                Modify Schedule
            </a>
        </li>


    </ul>

</aside>