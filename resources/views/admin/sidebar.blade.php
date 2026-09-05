<aside class="sidebar">

    <ul>

        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-table-columns"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.manageusers') ? 'active' : '' }}">
            <a href="{{ route('admin.manageusers') }}">
                <i class="fa-solid fa-users"></i>
                <span>Manage Users</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.buildings') ? 'active' : '' }}">
            <a href="{{ route('admin.buildings') }}">
                <i class="fa-solid fa-building"></i>
                <span>Manage Buildings</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.departments') ? 'active' : '' }}">
    <a href="{{ route('admin.departments') }}">
        <i class="fa-solid fa-building-columns"></i>
        <span>Manage College Departments</span>
    </a>
</li>

        <li class="{{ request()->routeIs('admin.schedules') ? 'active' : '' }}">
            <a href="{{ route('admin.schedules') }}">
                <i class="fa-solid fa-calendar"></i>
                <span>View Schedules</span>
            </a>
        </li>

    </ul>

</aside>