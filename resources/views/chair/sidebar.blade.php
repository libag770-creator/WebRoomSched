<aside class="sidebar">

    <ul class="sidebar-menu">

        <!-- =========================================
             DASHBOARD
        ========================================== -->

        <li class="{{ request()->routeIs('chair.dashboard') ? 'active' : '' }}">

            <a href="{{ route('chair.dashboard') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-table-columns"></i>
                </span>

                <span class="sidebar-text">
                    Dashboard
                </span>

            </a>

        </li>


        <!-- =========================================
             SET FACULTY
        ========================================== -->

        <li class="{{ request()->routeIs('chair.setFaculty') ? 'active' : '' }}">

            <a href="{{ route('chair.setFaculty') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-user-gear"></i>
                </span>

                <span class="sidebar-text">
                    Set Faculty
                </span>

            </a>

        </li>


        <!-- =========================================
             SET SCHEDULE
        ========================================== -->

        <li class="{{ request()->routeIs('chair.setschedule', 'chair.excel') ? 'active' : '' }}">

            <a href="{{ route('chair.setschedule') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </span>

                <span class="sidebar-text">
                    Set Schedule
                </span>

            </a>

        </li>


        <!-- =========================================
             DRAFTS
        ========================================== -->

        <li class="{{ request()->routeIs('chair.drafts') ? 'active' : '' }}">

            <a href="{{ route('chair.drafts') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-file-pen"></i>
                </span>

                <span class="sidebar-text">
                    Drafts
                </span>

            </a>

        </li>


        <!-- =========================================
             MODIFY SCHEDULE
        ========================================== -->

        <li class="{{ request()->routeIs('chair.modifyschedule') ? 'active' : '' }}">

            <a href="{{ route('chair.modifyschedule') }}">

                <span class="sidebar-icon">
                    <i class="fa-solid fa-right-left"></i>
                </span>

                <span class="sidebar-text">
                    Modify Schedule
                </span>

            </a>

        </li>

    </ul>

</aside>