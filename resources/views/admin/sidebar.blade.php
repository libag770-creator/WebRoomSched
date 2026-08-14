<aside class="sidebar">

    <ul>

        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-table-columns"></i>
                Dashboard
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.manageusers') ? 'active' : '' }}">
            <a href="{{ route('admin.manageusers') }}">
                <i class="fa-solid fa-users"></i>
                Manage Users
            </a>
        </li>

    <li class="{{ request()->routeIs('admin.buildings') ? 'active' : '' }}">
    <a href="{{ route('admin.buildings') }}">
        <i class="fa-solid fa-building"></i>
        Manage Buildings
    </a>
</li>

<li class="{{ request()->routeIs('admin.roomreassignment') ? 'active' : '' }}">
    <a href="{{ route('admin.roomreassignment') }}">
        <i class="fa-solid fa-right-left"></i>
        Room Reassignment
    </a>
</li>

    </ul>

</aside>