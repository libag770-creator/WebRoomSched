@php($chairSidebarDepartment = auth()->user()?->department)

<aside class="sidebar faculty-sidebar portal-sidebar chair-sidebar" aria-label="Department chair navigation">

    <div class="faculty-sidebar-workspace">
        <span class="faculty-workspace-mark chair-workspace-department-logo">
            @if($chairSidebarDepartment?->logo)
                <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
                <img src="{{ asset('storage/' . $chairSidebarDepartment->logo) }}" alt="{{ $chairSidebarDepartment->name }} logo" onerror="this.remove()">
            @else
                <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
            @endif
        </span>
        <div>
            <span class="faculty-workspace-label">WORKSPACE</span>
            <strong>Department Chair Portal</strong>
        </div>
    </div>

    <nav class="faculty-sidebar-nav" aria-label="Department chair portal">
        <span class="faculty-sidebar-section">OVERVIEW</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('chair.dashboard', 'chair.dashboard.page') ? 'active' : '' }}">
                <a href="{{ route('chair.dashboard') }}" @if(request()->routeIs('chair.dashboard', 'chair.dashboard.page')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-table-columns" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">SCHEDULING</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('chair.schedule.library', 'chair.academic.terms.*') ? 'active' : '' }}">
                <a href="{{ route('chair.schedule.library') }}" @if(request()->routeIs('chair.schedule.library', 'chair.academic.terms.*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-box-archive" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Schedule Library</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('chair.setschedule', 'chair.excel') ? 'active' : '' }}">
                <a href="{{ route('chair.setschedule') }}" @if(request()->routeIs('chair.setschedule', 'chair.excel')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Set Schedule</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('chair.drafts*') ? 'active' : '' }}">
                <a href="{{ route('chair.drafts') }}" @if(request()->routeIs('chair.drafts*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-file-pen" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Drafts</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('chair.modifyschedule') ? 'active' : '' }}">
                <a href="{{ route('chair.modifyschedule') }}" @if(request()->routeIs('chair.modifyschedule')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Cross Scheduling</span>
                </a>
            </li>
        </ul>

        <span class="faculty-sidebar-section">DEPARTMENT</span>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('chair.setFaculty', 'chair.faculty.subjects*') ? 'active' : '' }}">
                <a href="{{ route('chair.setFaculty') }}" @if(request()->routeIs('chair.setFaculty', 'chair.faculty.subjects*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-user-gear" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Faculty &amp; Subjects</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('chair.managesubjects*') ? 'active' : '' }}">
                <a href="{{ route('chair.managesubjects') }}" @if(request()->routeIs('chair.managesubjects*')) aria-current="page" @endif>
                    <span class="faculty-nav-icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></span>
                    <span class="sidebar-text">Manage Subjects</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="faculty-sidebar-footer">
        <span class="faculty-footer-indicator" aria-hidden="true"></span>
        <div>
            <strong>Department workspace</strong>
            <span>Schedules, faculty, and subjects</span>
        </div>
    </div>
</aside>

<style>
    .chair-sidebar,
    .chair-sidebar .faculty-sidebar-brand,
    .chair-sidebar .faculty-sidebar-workspace,
    .chair-sidebar .faculty-workspace-mark,
    .chair-sidebar .sidebar-menu a,
    .chair-sidebar .sidebar-menu a:hover { box-shadow:none !important; }
    .chair-sidebar .faculty-sidebar-brand { border-radius:0; }
    .chair-sidebar .sidebar-menu a { transition:background .16s ease,color .16s ease; }
    .chair-sidebar .sidebar-menu a:hover { transform:none; }
    .chair-sidebar .sidebar-menu li.active a { box-shadow:inset 3px 0 #9bc9a7 !important; }
    .chair-workspace-department-logo { position:relative; overflow:hidden; padding:0; background:#102633; }
    .chair-workspace-department-logo > i { position:absolute; inset:0; display:grid; place-items:center; }
    .chair-workspace-department-logo img { position:relative; z-index:1; display:block; width:100%; height:100%; padding:4px; border-radius:inherit; object-fit:contain; background:#0e1d28; }
</style>
