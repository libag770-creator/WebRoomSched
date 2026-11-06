<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>@yield('title')</title>

    {{-- WebRoomSched type system: quiet interface sans with an editorial display face. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">

    {{-- Icon set --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

    {{-- Shared WebRoomSched visual system --}}
    <link rel="stylesheet" href="{{ asset('css/brand-system.css') }}?v=20261020-global-canvas-v2">
    <link rel="stylesheet" href="{{ asset('css/schedule-sheet-controls.css') }}?v=20261023-fit-controls-v1">

    <style>
        :root { --department-theme-color: #276747; }
        .header-action.profile { display:flex; align-items:center; gap:8px; max-width:240px; }
        .header-left { gap:12px; }
        .header-department-logo { display:block; flex:0 0 44px; width:44px; height:44px; padding:4px; border:1px solid #e0e8e1; border-radius:11px; background:#fff; object-fit:contain; }
        .header-notification-wrap { position:relative; }
        .header-notification-button { position:relative; }
        .header-notification-count { position:absolute; top:-4px; right:-4px; min-width:17px; height:17px; padding:0 4px; display:grid; place-items:center; border:2px solid #fff; border-radius:999px; background:#dc2626; color:#fff; font-size:9px; line-height:1; font-weight:800; }
        .header-notification-menu { position:absolute; top:calc(100% + 12px); right:0; z-index:1000100; width:min(360px,calc(100vw - 24px)); max-height:min(470px,calc(100vh - 100px)); overflow:auto; border:1px solid #e2e8e4; border-radius:14px; background:#fff; box-shadow:0 18px 50px rgba(15,23,42,.18); }
        .header-notification-menu[hidden] { display:none !important; }
        .header-notification-heading { display:flex; align-items:center; justify-content:space-between; padding:14px 16px; border-bottom:1px solid #edf1ee; color:#20392c; font-size:13px; font-weight:800; }
        .header-notification-heading small { color:#718078; font-size:10px; font-weight:650; }
        .header-notification-item { display:block; padding:13px 16px; border-bottom:1px solid #f0f3f1; color:#34443a; text-decoration:none; }
        .header-notification-item:last-child { border-bottom:0; }
        .header-notification-item:hover { background:#f5faf6; }
        .header-notification-item.unread { background:#f0f8f2; box-shadow:inset 3px 0 var(--department-theme-color); }
        .header-notification-item strong { display:block; color:#1f3e2c; font-size:12px; }
        .header-notification-item span { display:block; margin-top:4px; color:#64736a; font-size:11px; line-height:1.45; }
        .header-notification-item time { display:block; margin-top:6px; color:#87928b; font-size:9px; }
        .header-notification-empty { padding:22px 16px; color:#718078; text-align:center; font-size:11px; }
        .header-profile-photo { width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid #c8dccd; }
        .header-profile-name { max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:13px; font-weight:600; }
        .profile-photo-overlay, .global-message-overlay { position:fixed; inset:0; z-index:1000000; display:flex; align-items:center; justify-content:center; padding:20px; background:rgba(15,23,42,.52); backdrop-filter:blur(3px); }
        .profile-photo-dialog, .global-message-dialog { position:relative; width:min(440px,100%); max-height:calc(100vh - 40px); overflow:auto; background:#fff; color:#1f2937; border-radius:18px; padding:30px; box-shadow:0 24px 70px rgba(0,0,0,.25); text-align:center; animation:globalModalPop .2s ease-out; }
        .profile-photo-dialog h2, .global-message-dialog h2 { margin:0 0 10px; font-size:22px; }
        .profile-photo-dialog p, .global-message-dialog p { color:#64748b; line-height:1.5; }
        .profile-photo-preview { width:92px; height:92px; border-radius:50%; object-fit:cover; border:3px solid #c8dccd; margin:8px auto 18px; display:block; }
        .profile-photo-dialog input[type=file] { display:block; width:100%; margin:18px 0; }
        .profile-dialog-actions { display:flex; justify-content:center; gap:10px; flex-wrap:wrap; margin-top:18px; }
        .profile-dialog-actions button { border:0; border-radius:8px; padding:10px 18px; cursor:pointer; font-weight:600; }
        .profile-save { background:#276747; color:#fff; }
        .profile-cancel { background:#e5e7eb; color:#1f2937; }
        .global-message-dialog.success { border-top:5px solid #16a34a; }
        .global-message-dialog.error { border-top:5px solid #dc2626; }
        .global-message-icon { width:64px;height:64px;border-radius:50%;display:grid;place-items:center;margin:0 auto 16px;font-size:30px; }
        .global-message-dialog.success .global-message-icon { background:#dcfce7;color:#15803d; }
        .global-message-dialog.error .global-message-icon { background:#fee2e2;color:#b91c1c; }
        body.system-confirm-open { overflow:hidden; }
        .system-confirm-overlay { position:fixed; inset:0; z-index:1000200; display:grid; place-items:center; padding:20px; background:rgba(15,23,42,.48); backdrop-filter:blur(4px); }
        .system-confirm-overlay[hidden] { display:none !important; }
        .system-confirm-card { width:min(430px,100%); padding:27px; border:1px solid #e7e8e5; border-radius:18px; background:#fff; color:#26352c; box-shadow:0 28px 80px rgba(15,23,42,.24); text-align:center; animation:globalModalPop .18s ease-out; }
        .system-confirm-icon { display:grid; width:54px; height:54px; place-items:center; margin:0 auto 14px; border:1px solid #f1d6d1; border-radius:17px; background:#fff3f1; color:#ad453d; font-size:23px; }
        .system-confirm-card h2 { margin:0 0 9px; color:#253b2d; font:800 21px/1.25 var(--wr-display,Manrope,sans-serif); }
        .system-confirm-card p { margin:0; color:#67756b; font-size:13px; line-height:1.55; }
        .system-confirm-actions { display:flex; justify-content:center; gap:10px; flex-wrap:wrap; margin-top:22px; }
        .system-confirm-actions button { min-width:112px; min-height:42px; padding:0 16px; border:1px solid #dce4dc; border-radius:9px; background:#f6f8f5; color:#395544; font:750 12px/1 var(--wr-body,Arial,sans-serif); cursor:pointer; }
        .system-confirm-actions button:focus-visible { outline:3px solid rgba(49,104,76,.24); outline-offset:2px; }
        .system-confirm-actions .system-confirm-accept { border-color:#a63f38; background:#b74840; color:#fff; }
        .system-confirm-actions .system-confirm-accept:hover { background:#963a34; }
        @keyframes globalModalPop { from {opacity:0;transform:translateY(8px) scale(.97)} to {opacity:1;transform:translateY(0) scale(1)} }
        @media(max-width:600px) { .header-profile-name { display:none; } .profile-photo-dialog,.global-message-dialog { padding:24px 20px; } }
    </style>

    {{-- Shared authenticated portal layout and the schedules page design. --}}
    <link rel="stylesheet" href="{{ asset('css/webroomsched-shell.css') }}?v=20261020-shell-v3">
    <link rel="stylesheet" href="{{ asset('css/account-profile.css') }}?v=20261021-profile-popup-v1">
    @if(auth()->user()?->role === 'admin' && request()->routeIs('admin.schedules', 'admin.view.schedule'))
        <link rel="stylesheet" href="{{ asset('css/admin-schedules.css') }}?v=20261020-schedules-v2">
    @endif

    <script src="{{ asset('js/sidebar.js') }}?v=20261020-brand-v1" defer></script>
    <script src="{{ asset('js/schedule-sheet-controls.js') }}?v=20261023-fit-controls-v1" defer></script>

</head>

<body class="portal-shell {{ auth()->user()?->role ? auth()->user()->role . '-portal' : '' }}">

<script>
    // Apply the saved sidebar state before the sidebar markup is parsed, so a
    // collapsed sidebar never flashes open during a full-page navigation.
    (function () {
        try {
            const saved = localStorage.getItem('webroomsched-sidebar-collapsed');
            const collapsed = saved === null
                ? window.matchMedia('(max-width: 760px)').matches
                : saved === 'true';
            document.body.classList.toggle('sidebar-collapsed', collapsed);
        } catch (error) {
            document.body.classList.toggle(
                'sidebar-collapsed',
                window.matchMedia('(max-width: 760px)').matches
            );
        }
    })();
</script>

<?php
    $headerNotifications = auth()->check()
        ? auth()->user()->notifications()->latest()->limit(8)->get()
        : collect();

    $headerUnreadNotificationCount = auth()->check()
        ? auth()->user()->unreadNotifications()->count()
        : 0;
?>

<header class="top-header">

    <div class="header-left">
        @php
            $headerDepartment = in_array(auth()->user()?->role, ['chair', 'faculty'], true)
                ? auth()->user()?->department
                : null;
        @endphp
        @if($headerDepartment?->logo)
            <img
                class="header-department-logo"
                src="{{ route('departments.logo', $headerDepartment) }}?v={{ urlencode($headerDepartment->logo) }}"
                alt="{{ $headerDepartment->name }} college logo"
                onerror="this.hidden=true"
            >
        @endif
        <div class="header-title">
            @php
                $headerRole = auth()->user()?->role;
                $headerRoleLabel = match ($headerRole) {
                    'admin' => 'ADMINISTRATOR PORTAL',
                    'chair' => 'COLLEGE CHAIR PORTAL',
                    'faculty' => 'INSTRUCTOR PORTAL',
                    default => 'WEBROOMSCHED',
                };
                $headerPageTitle = match (true) {
                    $headerRole === 'admin' && request()->routeIs('admin.dashboard') => 'Campus overview',
                    $headerRole === 'admin' && request()->routeIs('admin.schedules', 'admin.view.schedule') => 'Schedules',
                    $headerRole === 'admin' && request()->routeIs('admin.departments*') => 'Colleges',
                    $headerRole === 'admin' && request()->routeIs('admin.buildings*') => 'Buildings & Rooms',
                    $headerRole === 'admin' && request()->routeIs('admin.roomreassignment*') => 'Schedule Overrides',
                    $headerRole === 'admin' && request()->routeIs('admin.manageusers*') => 'Manage Users',
                    $headerRole === 'chair' && request()->routeIs('chair.dashboard', 'chair.dashboard.page') => 'College overview',
                    $headerRole === 'chair' && request()->routeIs('chair.requests') => 'Requests & Activity',
                    $headerRole === 'chair' && request()->routeIs('chair.schedule.library', 'chair.academic.terms.*') => 'Schedule library',
                    $headerRole === 'chair' && request()->routeIs('chair.setschedule', 'chair.excel') => 'Build a schedule',
                    $headerRole === 'chair' && request()->routeIs('chair.modifyschedule') => 'Cross scheduling',
                    $headerRole === 'chair' && request()->routeIs('chair.roomavailability') => 'All-Room Availability',
                    $headerRole === 'chair' && request()->routeIs('chair.setFaculty', 'chair.faculty.subjects*') => 'Instructors & Subjects',
                    $headerRole === 'chair' && request()->routeIs('chair.managesubjects*') => 'Subject catalog',
                    $headerRole === 'faculty' && request()->routeIs('faculty.dashboard') => 'Instructor overview',
                    $headerRole === 'faculty' && request()->routeIs('faculty.schedules') => 'Campus schedules',
                    $headerRole === 'faculty' && request()->routeIs('faculty.myschedules') => 'My teaching schedule',
                    $headerRole === 'faculty' && request()->routeIs('faculty.vacant') => 'Find a room',
                    $headerRole === 'faculty' && request()->routeIs('faculty.room-swap', 'faculty.room.swap.requests') => 'Room coordination',
                    $headerRole === 'faculty' && request()->routeIs('faculty.bookings') => 'My reservations',
                    request()->routeIs('profile.edit') => 'My profile',
                    default => 'Workspace',
                };
            @endphp
            <small>{{ $headerRoleLabel }}</small>
            <h3>{{ $headerPageTitle }}</h3>
        </div>
    </div>

    @if($headerRole === 'admin' && request()->routeIs('admin.dashboard'))
        <label class="header-search" for="adminDashboardSearch">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input id="adminDashboardSearch" type="search" placeholder="Search dashboard" autocomplete="off">
        </label>
    @endif

    <div class="header-right">

        <?php if (auth()->check()): ?>
            <div class="header-notification-wrap">
                <button type="button" class="header-action notification header-notification-button"
                        id="headerNotificationButton" title="Notifications" aria-label="Notifications"
                        data-latest-url="{{ route('notifications.latest') }}"
                        aria-expanded="false" aria-controls="headerNotificationMenu">
                    <i class="fa-solid fa-bell"></i>
                    @if($headerUnreadNotificationCount > 0)
                        <span class="header-notification-count">{{ $headerUnreadNotificationCount > 9 ? '9+' : $headerUnreadNotificationCount }}</span>
                    @endif
                </button>
                <div class="header-notification-menu" id="headerNotificationMenu" hidden>
                    <div class="header-notification-heading">
                        <span>Notifications</span>
                        <small>{{ $headerUnreadNotificationCount }} unread</small>
                    </div>
                    @forelse($headerNotifications as $headerNotification)
                        <a class="header-notification-item {{ $headerNotification->read_at ? '' : 'unread' }}"
                           href="{{ route('notifications.open', $headerNotification->id) }}">
                            <strong>{{ $headerNotification->data['title'] ?? 'Notification' }}</strong>
                            <span>{{ $headerNotification->data['message'] ?? 'You have a new notification.' }}</span>
                            <time>{{ $headerNotification->created_at?->diffForHumans() }}</time>
                        </a>
                    @empty
                        <div class="header-notification-empty">You’re all caught up. Schedule changes and room swap updates will appear here.</div>
                    @endforelse
                </div>
            </div>
        <?php endif; ?>


        @auth
            <button type="button" class="header-action profile" title="My profile" aria-label="Open profile and account settings" aria-haspopup="dialog" aria-controls="accountProfileOverlay" onclick="openAccountProfileDialog()">
                @if(auth()->user()->profile_photo_path)
                    <img class="header-profile-photo" src="{{ auth()->user()->profile_photo_url }}" alt="Profile photo">
                @else
                    <span class="header-profile-photo" style="display:grid;place-items:center;background:#e5e7eb;color:#475569;"><i class="fa-solid fa-user"></i></span>
                @endif
                <span class="header-profile-name">{{ auth()->user()->name ?? auth()->user()->username ?? 'My Profile' }}</span>
            </button>
        @endauth


        <form
            action="{{ route('faculty.logout') }}"
            method="POST"
            class="logout-form"
        >

            @csrf

            <button
                type="submit"
                class="header-action logout"
                title="Logout"
            >
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>

        </form>

    </div>

</header>

@php
    $accountProfileShouldOpen = session('account_profile_open')
        || $errors->updateUsername->any()
        || $errors->updatePassword->any()
        || $errors->updatePhoto->any();
@endphp
@auth
@php
    $headerProfileUser = auth()->user();
@endphp
<div id="accountProfileOverlay" class="account-profile-overlay" role="dialog" aria-modal="true" aria-labelledby="accountProfileTitle" @unless($accountProfileShouldOpen) hidden @endunless onclick="if (event.target === this) closeAccountProfileDialog()">
    <div class="account-profile-dialog">
        <header class="account-profile-dialog-heading">
            <div class="account-profile-dialog-avatar">
                @if($headerProfileUser->profile_photo_url)
                    <img id="profilePhotoPreview" src="{{ $headerProfileUser->profile_photo_url }}" alt="Profile photo for {{ $headerProfileUser->name }}">
                @else
                    <span id="profilePhotoPlaceholder" aria-hidden="true">{{ strtoupper(substr((string) ($headerProfileUser->name ?: $headerProfileUser->username ?: 'U'), 0, 1)) }}</span>
                    <img id="profilePhotoPreview" src="" alt="Photo preview" style="display:none">
                @endif
            </div>
            <div class="account-profile-dialog-title">
                <span>ACCOUNT SETTINGS</span>
                <h2 id="accountProfileTitle">My profile</h2>
                <p>{{ $headerProfileUser->name ?: $headerProfileUser->username }} · {{ $headerProfileUser->role === 'faculty' ? 'Instructor' : ucfirst($headerProfileUser->role ?? 'User') }}{{ $headerProfileUser->department ? ' · ' . $headerProfileUser->department->name : '' }}</p>
            </div>
            <button type="button" class="account-profile-close" aria-label="Close profile" onclick="closeAccountProfileDialog()"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
        </header>

        @if(session('error'))
            <div class="account-profile-notice account-profile-notice-error" role="alert">{{ session('error') }}</div>
        @endif
        @if(session('status') === 'username-updated')
            <div class="account-profile-notice account-profile-notice-success" role="status">Username saved. Use <strong>{{ $headerProfileUser->username }}</strong> the next time you sign in.</div>
        @elseif(session('status') === 'password-updated')
            <div class="account-profile-notice account-profile-notice-success" role="status">Password saved. Use your new password the next time you sign in.</div>
        @elseif(session('status') === 'photo-updated')
            <div class="account-profile-notice account-profile-notice-success" role="status">Your profile photo has been updated.</div>
        @endif

        <div class="account-profile-modal-grid">
            <section class="account-profile-modal-card" aria-labelledby="modalUsernameTitle">
                <div class="account-profile-modal-card-heading">
                    <span class="account-profile-modal-icon"><i class="fa-solid fa-at" aria-hidden="true"></i></span>
                    <div><h3 id="modalUsernameTitle">Sign-in username</h3><p>Update the username you use to log in.</p></div>
                </div>
                <form method="POST" action="{{ route('profile.username.update') }}" class="account-profile-modal-form">
                    @csrf
                    @method('PUT')
                    <div class="account-profile-field">
                        <label for="modal_profile_username">Username</label>
                        <input id="modal_profile_username" name="username" type="text" value="{{ old('username', $headerProfileUser->username) }}" autocomplete="username" maxlength="255" required>
                        @if($errors->updateUsername->has('username'))<p class="account-profile-error">{{ $errors->updateUsername->first('username') }}</p>@endif
                    </div>
                    <div class="account-profile-field">
                        <label for="modal_username_current_password">Current password</label>
                        <input id="modal_username_current_password" name="current_password" type="password" autocomplete="current-password" required>
                        @if($errors->updateUsername->has('current_password'))<p class="account-profile-error">{{ $errors->updateUsername->first('current_password') }}</p>@endif
                    </div>
                    <button type="submit" class="account-profile-submit"><i class="fa-solid fa-check" aria-hidden="true"></i> Save username</button>
                </form>
            </section>

            <section class="account-profile-modal-card" aria-labelledby="modalPasswordTitle">
                <div class="account-profile-modal-card-heading">
                    <span class="account-profile-modal-icon"><i class="fa-solid fa-lock" aria-hidden="true"></i></span>
                    <div><h3 id="modalPasswordTitle">Change password</h3><p>Set a new password for your next sign-in.</p></div>
                </div>
                <form method="POST" action="{{ route('profile.password.update') }}" class="account-profile-modal-form">
                    @csrf
                    @method('PUT')
                    <div class="account-profile-field">
                        <label for="modal_profile_current_password">Current password</label>
                        <input id="modal_profile_current_password" name="current_password" type="password" autocomplete="current-password" required>
                        @if($errors->updatePassword->has('current_password'))<p class="account-profile-error">{{ $errors->updatePassword->first('current_password') }}</p>@endif
                    </div>
                    <div class="account-profile-field">
                        <label for="modal_profile_new_password">New password</label>
                        <input id="modal_profile_new_password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                        @if($errors->updatePassword->has('password'))<p class="account-profile-error">{{ $errors->updatePassword->first('password') }}</p>@endif
                    </div>
                    <div class="account-profile-field">
                        <label for="modal_profile_password_confirmation">Confirm new password</label>
                        <input id="modal_profile_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                        @if($errors->updatePassword->has('password_confirmation'))<p class="account-profile-error">{{ $errors->updatePassword->first('password_confirmation') }}</p>@endif
                    </div>
                    <button type="submit" class="account-profile-submit"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Update password</button>
                </form>
            </section>
        </div>

        <footer class="account-profile-dialog-footer">
            <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="account-profile-photo-form">
                @csrf
                <label for="profile_photo"><i class="fa-regular fa-image" aria-hidden="true"></i> Change profile photo</label>
                <input id="profile_photo" name="profile_photo" type="file" accept="image/png,image/jpeg,image/webp" required>
                <span>JPG, PNG, or WebP · Up to 2 MB</span>
                @if($errors->updatePhoto->has('profile_photo'))<p class="account-profile-error">{{ $errors->updatePhoto->first('profile_photo') }}</p>@endif
                <button type="submit" class="account-profile-photo-save">Save photo</button>
            </form>
            <a href="{{ route('profile.edit') }}" class="account-profile-full-page-link">Open full profile page <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
        </footer>
    </div>
</div>
@endauth

@if((session('success') || session('error') || $errors->any()) && !$accountProfileShouldOpen)
    <div id="globalMessageOverlay" class="global-message-overlay" role="dialog" aria-modal="true">
        <div class="global-message-dialog {{ session('success') ? 'success' : 'error' }}">
            <div class="global-message-icon">{{ session('success') ? '✓' : '!' }}</div>
            <h2>{{ session('success') ? 'Successful!' : (session('error') ? 'Action Failed' : 'Please Check') }}</h2>
            <p>
                @if(session('success')) {{ session('success') }}
                @elseif(session('error')) {{ session('error') }}
                @else {{ $errors->first() }}
                @endif
            </p>
            <button type="button" class="profile-save" style="border:0;border-radius:8px;padding:10px 28px;cursor:pointer" onclick="closeGlobalMessage()">OK</button>
        </div>
    </div>
@endif

<div id="systemDeleteConfirm" class="system-confirm-overlay" role="alertdialog" aria-modal="true" aria-labelledby="systemDeleteConfirmTitle" aria-describedby="systemDeleteConfirmMessage" hidden>
    <section class="system-confirm-card" tabindex="-1">
        <div class="system-confirm-icon" aria-hidden="true"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h2 id="systemDeleteConfirmTitle">Are you sure?</h2>
        <p id="systemDeleteConfirmMessage">Are you sure you want to delete this? This action cannot be undone.</p>
        <div class="system-confirm-actions">
            <button type="button" id="systemDeleteCancel">Go back</button>
            <button type="button" id="systemDeleteAccept" class="system-confirm-accept">Delete</button>
        </div>
    </section>
</div>

<script>
function openAccountProfileDialog() {
    const overlay = document.getElementById('accountProfileOverlay');
    if (!overlay) return;
    overlay.hidden = false;
    overlay.style.display = 'flex';
    document.body.classList.add('account-profile-modal-open');
    window.setTimeout(() => overlay.querySelector('input[name="username"]')?.focus(), 40);
}
function closeAccountProfileDialog() {
    const overlay = document.getElementById('accountProfileOverlay');
    if (!overlay) return;
    overlay.hidden = true;
    overlay.style.display = '';
    document.body.classList.remove('account-profile-modal-open');
    document.querySelector('.header-action.profile')?.focus();
}
function closeGlobalMessage() { const el = document.getElementById('globalMessageOverlay'); if (el) el.remove(); }
document.addEventListener('DOMContentLoaded', function () {
    const accountProfileOverlay = document.getElementById('accountProfileOverlay');
    if (accountProfileOverlay && !accountProfileOverlay.hidden) {
        document.body.classList.add('account-profile-modal-open');
        accountProfileOverlay.querySelector('.account-profile-dialog')?.focus();
    }
    const notificationButton = document.getElementById('headerNotificationButton');
    const notificationMenu = document.getElementById('headerNotificationMenu');
    if (notificationButton && notificationMenu) {
        const refreshNotifications = async function () {
            try {
                const response = await fetch(notificationButton.dataset.latestUrl, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                });
                if (!response.ok) return;
                const payload = await response.json();
                const unreadCount = Number(payload.unread_count || 0);
                let badge = notificationButton.querySelector('.header-notification-count');
                if (unreadCount > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'header-notification-count';
                        notificationButton.appendChild(badge);
                    }
                    badge.textContent = unreadCount > 9 ? '9+' : String(unreadCount);
                } else if (badge) {
                    badge.remove();
                }

                notificationMenu.replaceChildren();
                const heading = document.createElement('div');
                heading.className = 'header-notification-heading';
                const headingTitle = document.createElement('span');
                headingTitle.textContent = 'Notifications';
                const unreadLabel = document.createElement('small');
                unreadLabel.textContent = unreadCount + ' unread';
                heading.append(headingTitle, unreadLabel);
                notificationMenu.appendChild(heading);

                const notifications = payload.notifications || [];
                if (!notifications.length) {
                    const empty = document.createElement('div');
                    empty.className = 'header-notification-empty';
                    empty.textContent = 'You’re all caught up. New room swap requests will appear here.';
                    notificationMenu.appendChild(empty);
                    return;
                }

                notifications.forEach(function (item) {
                    const link = document.createElement('a');
                    link.className = 'header-notification-item' + (item.read ? '' : ' unread');
                    link.href = item.url;
                    const title = document.createElement('strong');
                    title.textContent = item.title || 'Notification';
                    const body = document.createElement('span');
                    body.textContent = item.message || 'You have a new notification.';
                    const time = document.createElement('time');
                    time.textContent = item.created_at ? new Date(item.created_at).toLocaleString() : '';
                    link.append(title, body, time);
                    notificationMenu.appendChild(link);
                });
            } catch (error) {
                // Leave the server-rendered notifications available if polling fails.
            }
        };

        notificationButton.addEventListener('click', function (event) {
            event.stopPropagation();
            const willOpen = notificationMenu.hidden;
            notificationMenu.hidden = !willOpen;
            notificationButton.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.header-notification-wrap')) {
                notificationMenu.hidden = true;
                notificationButton.setAttribute('aria-expanded', 'false');
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                notificationMenu.hidden = true;
                notificationButton.setAttribute('aria-expanded', 'false');
            }
        });
        refreshNotifications();
        window.setInterval(refreshNotifications, 20000);
    }
    const photoInput = document.getElementById('profile_photo');
    if (photoInput) photoInput.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) return;
        const preview = document.getElementById('profilePhotoPreview');
        const placeholder = document.getElementById('profilePhotoPlaceholder');
        if (preview) { preview.src = URL.createObjectURL(file); preview.style.display = 'block'; }
        if (placeholder) placeholder.style.display = 'none';
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeAccountProfileDialog(); closeGlobalMessage(); }
    });
    const message = document.getElementById('globalMessageOverlay');
    if (message) {
        // Keep one message only: remove page-level copies of the same flash message.
        const dialogText = (message.querySelector('.global-message-dialog p')?.textContent || '').trim().replace(/\s+/g, ' ');
        if (dialogText) {
            const duplicateSelectors = [
                '.message.success', '.message.error', '.alert-success', '.alert-error',
                '.alert-danger', '.success-message', '.error-message', '.success-alert',
                '.error-alert', '.error-box', '.mu-alert-success', '.mu-alert-error',
                '.alert.success-alert', '.alert.error-alert'
            ];
            document.querySelectorAll(duplicateSelectors.join(',')).forEach(function (item) {
                const itemText = (item.textContent || '').trim().replace(/\s+/g, ' ');
                if (itemText && (itemText.includes(dialogText) || dialogText.includes(itemText))) item.remove();
            });
        }
        message.addEventListener('click', function (event) { if (event.target === message) closeGlobalMessage(); });
    }
});
</script>

<script>
(function () {
    const overlay = document.getElementById('systemDeleteConfirm');
    const message = document.getElementById('systemDeleteConfirmMessage');
    const acceptButton = document.getElementById('systemDeleteAccept');
    const cancelButton = document.getElementById('systemDeleteCancel');
    if (!overlay || !message || !acceptButton || !cancelButton) return;

    let pendingAction = null;
    let approvedForm = null;
    let approvedControl = null;
    let previouslyFocused = null;
    let confirmationResolver = null;

    function hiddenMethod(form) {
        return String(form?.querySelector('input[name="_method"]')?.value || form?.method || '').toUpperCase();
    }

    function controlLabel(control, form) {
        return [
            control?.innerText,
            control?.value,
            control?.getAttribute('aria-label'),
            control?.getAttribute('title'),
            control?.className,
            form?.getAttribute('action'),
            form?.getAttribute('class'),
            form?.dataset.confirmDelete,
            form?.dataset.confirmRemove,
            control?.dataset.confirmDelete,
            control?.dataset.confirmRemove,
            control?.dataset.delete,
            control?.dataset.remove
        ].filter(Boolean).join(' ').trim();
    }

    function destructiveVerb(label, form, control) {
        if (/\bclear\b/i.test(label) && (form?.hasAttribute('data-confirm-remove') || control?.hasAttribute('data-confirm-remove'))) return 'clear';
        if (/\bcancel|end\b/i.test(label)) return 'cancel';
        if (/\bremove\b/i.test(label) || form?.hasAttribute('data-confirm-remove')) return 'remove';
        return 'delete';
    }

    function shouldConfirm(control, form) {
        if (control?.hasAttribute('data-skip-system-confirm')) return false;
        if (hiddenMethod(form) === 'DELETE') return true;
        if (form?.hasAttribute('data-confirm-delete') || form?.hasAttribute('data-confirm-remove')) return true;
        if (control?.hasAttribute('data-delete') || control?.hasAttribute('data-remove')
            || control?.hasAttribute('data-confirm-delete') || control?.hasAttribute('data-confirm-remove')) return true;

        const label = controlLabel(control, form);
        return /\b(delete|remove)\b/i.test(label)
            || /(?:^|[\/_-])(delete|remove)(?:$|[\/?#_.-])/i.test(String(form?.action || control?.getAttribute('href') || ''));
    }

    function openConfirmation(control, form, submitter) {
        const label = controlLabel(control, form);
        const verb = destructiveVerb(label, form, control);
        const explicitMessage = form?.dataset.confirmDelete
            || form?.dataset.confirmRemove
            || control?.dataset.confirmDelete
            || control?.dataset.confirmRemove;

        pendingAction = { control, form, submitter, verb };
        message.textContent = explicitMessage || `Are you sure you want to ${verb} this? This action cannot be undone.`;
        acceptButton.textContent = verb === 'remove' ? 'Remove' : (verb === 'clear' ? 'Clear' : (verb === 'cancel' ? 'Cancel' : 'Delete'));
        previouslyFocused = document.activeElement;
        overlay.hidden = false;
        document.body.classList.add('system-confirm-open');
        acceptButton.focus();
    }

    function closeConfirmation() {
        const resolveConfirmation = confirmationResolver;
        confirmationResolver = null;
        overlay.hidden = true;
        document.body.classList.remove('system-confirm-open');
        pendingAction = null;
        if (previouslyFocused?.isConnected) previouslyFocused.focus();
        previouslyFocused = null;
        if (resolveConfirmation) resolveConfirmation(false);
    }

    function runApprovedAction() {
        if (confirmationResolver) {
            const resolveConfirmation = confirmationResolver;
            confirmationResolver = null;
            closeConfirmation();
            resolveConfirmation(true);
            return;
        }

        const action = pendingAction;
        if (!action) return;
        closeConfirmation();

        const originalConfirm = window.confirm;
        window.confirm = function () { return true; };
        try {
            if (action.form && action.submitter && action.submitter.form === action.form
                && (action.submitter.matches('input[type="submit"],input[type="image"]')
                    || (action.submitter.tagName === 'BUTTON' && (action.submitter.type || 'submit') === 'submit'))) {
                approvedForm = action.form;
                action.form.requestSubmit(action.submitter);
                window.setTimeout(function () { if (approvedForm === action.form) approvedForm = null; }, 0);
            } else if (action.form && !action.control) {
                approvedForm = action.form;
                action.form.requestSubmit();
                window.setTimeout(function () { if (approvedForm === action.form) approvedForm = null; }, 0);
            } else if (action.control?.tagName === 'A' && action.control.href) {
                window.location.assign(action.control.href);
            } else if (action.control?.isConnected) {
                approvedControl = action.control;
                action.control.click();
                window.setTimeout(function () { if (approvedControl === action.control) approvedControl = null; }, 0);
            } else if (action.form) {
                approvedForm = action.form;
                action.form.requestSubmit();
                window.setTimeout(function () { if (approvedForm === action.form) approvedForm = null; }, 0);
            }
        } catch (error) {
            approvedForm = null;
            approvedControl = null;
            console.error('Could not continue the confirmed action.', error);
        } finally {
            window.confirm = originalConfirm;
        }
    }

    window.showSystemConfirmation = function (confirmationMessage, confirmLabel) {
        if (!overlay.hidden) return Promise.resolve(false);

        return new Promise(function (resolve) {
            confirmationResolver = resolve;
            pendingAction = { control: null, form: null, submitter: null, verb: 'remove' };
            message.textContent = confirmationMessage || 'Are you sure you want to remove this?';
            acceptButton.textContent = confirmLabel || 'Confirm';
            previouslyFocused = document.activeElement;
            overlay.hidden = false;
            document.body.classList.add('system-confirm-open');
            acceptButton.focus();
        });
    };

    document.addEventListener('click', function (event) {
        if (overlay.contains(event.target)) return;
        const control = event.target.closest('button,a,input[type="submit"],input[type="image"],[role="button"]');
        if (!control) return;
        if (approvedControl === control) {
            approvedControl = null;
            return;
        }

        const form = control.form || control.closest('form');
        if (!shouldConfirm(control, form)) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        openConfirmation(control, form, control);
    }, true);

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (approvedForm === form) {
            approvedForm = null;
            return;
        }
        if (!shouldConfirm(event.submitter, form)) return;

        event.preventDefault();
        event.stopImmediatePropagation();
        openConfirmation(event.submitter, form, event.submitter);
    }, true);

    acceptButton.addEventListener('click', runApprovedAction);
    cancelButton.addEventListener('click', closeConfirmation);
    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) closeConfirmation();
    });
    document.addEventListener('keydown', function (event) {
        if (overlay.hidden) return;
        if (event.key === 'Escape') {
            event.preventDefault();
            event.stopImmediatePropagation();
            closeConfirmation();
        } else if (event.key === 'Tab') {
            event.preventDefault();
            event.stopImmediatePropagation();
            (document.activeElement === acceptButton ? cancelButton : acceptButton).focus();
        }
    });
})();
</script>


