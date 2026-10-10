<aside class="sidebar">

    <div class="sidebar-brand">
        <img src="{{ asset('images/images.jpg') }}" alt="BSU Bokod Campus">
        <div>
            <strong>WebRoomSched</strong>
            <span>Administrator Portal</span>
        </div>
    </div>

    <ul class="sidebar-menu">

        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-table-columns"></i>
                </span>

                <span class="sidebar-text">
                    Dashboard
                </span>

            </a>
        </li>

          <li class="{{ request()->routeIs('admin.departments') ? 'active' : '' }}">
            <a href="{{ route('admin.departments') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-building-columns"></i>
                </span>

                <span class="sidebar-text">
                    Manage College Departments
                </span>

            </a>
        </li>


        <li class="{{ request()->routeIs('admin.manageusers') ? 'active' : '' }}">
            <a href="{{ route('admin.manageusers') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-users"></i>
                </span>

                <span class="sidebar-text">
                    Manage Users
                </span>

            </a>
        </li>

        {{-- ROOM REASSIGNMENT --}}
<li class="{{ request()->routeIs('admin.roomreassignment*') ? 'active' : '' }}">
    <a href="{{ route('admin.roomreassignment') }}">

        <span class="sidebar-icon">
            <i class="fa-solid fa-right-left"></i>
        </span>

        <span class="sidebar-text">
            Override Schedule
        </span>

    </a>
</li>

        <li class="{{ request()->routeIs('admin.buildings') ? 'active' : '' }}">
            <a href="{{ route('admin.buildings') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-building"></i>
                </span>

                <span class="sidebar-text">
                    Manage Buildings
                </span>

            </a>
        </li>


      


        <li class="{{ request()->routeIs('admin.schedules') ? 'active' : '' }}">
            <a href="{{ route('admin.schedules') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-calendar"></i>
                </span>

                <span class="sidebar-text">
                    View Schedules
                </span>

            </a>
        </li>

    </ul>

</aside>