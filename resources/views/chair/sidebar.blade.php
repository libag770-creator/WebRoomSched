<aside class="sidebar">

    <ul>

        <!-- =========================================
             DASHBOARD
        ========================================== -->

        <li class="{{ request()->routeIs('chair.dashboard') ? 'active' : '' }}">

            <a href="{{ route('chair.dashboard') }}">

                <i class="fa-solid fa-table-columns"></i>

                <span>
                    Dashboard
                </span>

            </a>

        </li>


        <!-- =========================================
             SET FACULTY
        ========================================== -->

        <li class="{{ request()->routeIs('chair.setFaculty') ? 'active' : '' }}">

            <a href="{{ route('chair.setFaculty') }}">

                <i class="fa-solid fa-user-gear"></i>

                <span>
                    Set Faculty
                </span>

            </a>

        </li>


        <!-- =========================================
             SET SCHEDULE
        ========================================== -->

        <li class="{{ request()->routeIs('chair.setschedule', 'chair.excel') ? 'active' : '' }}">

            <a href="{{ route('chair.setschedule') }}">

                <i class="fa-solid fa-calendar-days"></i>

                <span>
                    Set Schedule
                </span>

            </a>

        </li>


        <!-- =========================================
             DRAFTS
        ========================================== -->

        <li class="{{ request()->routeIs('chair.drafts') ? 'active' : '' }}">

            <a href="{{ route('chair.drafts') }}">

                <i class="fa-solid fa-file-pen"></i>

                <span>
                    Drafts
                </span>

            </a>

        </li>


        <!-- =========================================
             MODIFY SCHEDULE
        ========================================== -->

        <li class="{{ request()->routeIs('chair.modifyschedule') ? 'active' : '' }}">

            <a href="{{ route('chair.modifyschedule') }}">

                <i class="fa-solid fa-right-left"></i>

                <span>
                    Modify Schedule
                </span>

            </a>

        </li>

    </ul>

</aside>