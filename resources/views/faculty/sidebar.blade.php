<aside class="sidebar">

    <ul class="sidebar-menu">

        <!-- =========================================
             DASHBOARD
        ========================================== -->

        <li class="{{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">

            <a href="{{ route('faculty.dashboard') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-table-columns"></i>
                </span>

                <span class="sidebar-text">
                    Dashboard
                </span>

            </a>

        </li>


        <!-- =========================================
             SCHEDULES
        ========================================== -->

        <li class="{{ request()->routeIs('faculty.schedules') ? 'active' : '' }}">

            <a href="{{ route('faculty.schedules') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </span>

                <span class="sidebar-text">
                    Schedules
                </span>

            </a>

        </li>


        <!-- =========================================
             VACANT ROOMS
        ========================================== -->

        <li class="{{ request()->routeIs('faculty.vacant') ? 'active' : '' }}">

            <a href="{{ route('faculty.vacant') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-door-open"></i>
                </span>

                <span class="sidebar-text">
                    Vacant Rooms
                </span>

            </a>

        </li>


        <!-- =========================================
             ROOM SWAP
        ========================================== -->

        <li class="{{ request()->routeIs('faculty.room-swap') ? 'active' : '' }}">

            <a href="{{ route('faculty.room-swap') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-right-left"></i>
                </span>

                <span class="sidebar-text">
                    Room Swap
                </span>

            </a>

        </li>


        <!-- =========================================
             MY BOOKINGS
        ========================================== -->

        <li class="{{ request()->routeIs('faculty.bookings') ? 'active' : '' }}">

            <a href="{{ route('faculty.bookings') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-book"></i>
                </span>

                <span class="sidebar-text">
                    My Bookings
                </span>

            </a>

        </li>

    </ul>

</aside>