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

    {{-- Consistent system typography --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- COMMON SYSTEM CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/modern.css') }}"
    >

    <link rel="stylesheet" href="{{ asset('css/professional.css') }}">

    {{-- FONT AWESOME --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

    <style>
        :root {
            --department-theme-color: {{ auth()->user()?->department?->theme_color ?? '#087443' }};
            --department-theme-rgb: 8,116,67;
        }
        .top-header { border-top: 5px solid var(--department-theme-color); }
        .header-action.profile { display:flex; align-items:center; gap:8px; max-width:240px; }
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
        .header-profile-photo { width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid var(--department-theme-color); }
        .header-profile-name { max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:13px; font-weight:600; }
        .profile-photo-overlay, .global-message-overlay { position:fixed; inset:0; z-index:1000000; display:flex; align-items:center; justify-content:center; padding:20px; background:rgba(15,23,42,.52); backdrop-filter:blur(3px); }
        .profile-photo-dialog, .global-message-dialog { position:relative; width:min(440px,100%); max-height:calc(100vh - 40px); overflow:auto; background:#fff; color:#1f2937; border-radius:18px; padding:30px; box-shadow:0 24px 70px rgba(0,0,0,.25); text-align:center; animation:globalModalPop .2s ease-out; }
        .profile-photo-dialog h2, .global-message-dialog h2 { margin:0 0 10px; font-size:22px; }
        .profile-photo-dialog p, .global-message-dialog p { color:#64748b; line-height:1.5; }
        .profile-photo-preview { width:92px; height:92px; border-radius:50%; object-fit:cover; border:3px solid var(--department-theme-color); margin:8px auto 18px; display:block; }
        .profile-photo-dialog input[type=file] { display:block; width:100%; margin:18px 0; }
        .profile-dialog-actions { display:flex; justify-content:center; gap:10px; flex-wrap:wrap; margin-top:18px; }
        .profile-dialog-actions button { border:0; border-radius:8px; padding:10px 18px; cursor:pointer; font-weight:600; }
        .profile-save { background:var(--department-theme-color); color:#fff; }
        .profile-cancel { background:#e5e7eb; color:#1f2937; }
        .global-message-dialog.success { border-top:5px solid #16a34a; }
        .global-message-dialog.error { border-top:5px solid #dc2626; }
        .global-message-icon { width:64px;height:64px;border-radius:50%;display:grid;place-items:center;margin:0 auto 16px;font-size:30px; }
        .global-message-dialog.success .global-message-icon { background:#dcfce7;color:#15803d; }
        .global-message-dialog.error .global-message-icon { background:#fee2e2;color:#b91c1c; }
        @keyframes globalModalPop { from {opacity:0;transform:translateY(8px) scale(.97)} to {opacity:1;transform:translateY(0) scale(1)} }
        /* Department theme: sidebar, active links, buttons and shared accents */
        .sidebar-brand { border-color: color-mix(in srgb, var(--department-theme-color) 25%, white) !important; background: color-mix(in srgb, var(--department-theme-color) 7%, white) !important; }
        .sidebar-brand img { border-color: color-mix(in srgb, var(--department-theme-color) 35%, white) !important; }
        .sidebar-brand strong { color: var(--department-theme-color) !important; }
        .sidebar a:hover { background: color-mix(in srgb, var(--department-theme-color) 10%, white) !important; color: var(--department-theme-color) !important; }
        .sidebar a:hover i { color: var(--department-theme-color) !important; }
        .sidebar li.active a { background: var(--department-theme-color) !important; color: #fff !important; box-shadow: 0 5px 15px color-mix(in srgb, var(--department-theme-color) 24%, transparent) !important; }
        .sidebar li.active a i, .sidebar li.active a .sidebar-icon i { color: #fff !important; }
        .btn-primary, .btn-success, .button-primary, button.primary, .primary-btn, input[type=submit], .pagination .active span { background-color: var(--department-theme-color); border-color: var(--department-theme-color); }
        .btn-primary:hover, .btn-success:hover, .button-primary:hover, button.primary:hover, .primary-btn:hover, input[type=submit]:hover { filter: brightness(.92); }
        .top-header .header-action:hover { color: var(--department-theme-color); }
        .profile-photo-dialog { border-top: 5px solid var(--department-theme-color); }
        @media(max-width:600px) { .header-profile-name { display:none; } .profile-photo-dialog,.global-message-dialog { padding:24px 20px; } }
    </style>

    <link rel="stylesheet" href="{{ asset('css/neon-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shell-layout.css') }}?v=20261018-sidebar-3">
    <script src="{{ asset('js/sidebar.js') }}" defer></script>

</head>

<body class="{{ request()->routeIs('admin.*') ? 'admin-portal' : '' }}">

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

        @php($headerDepartment = auth()->user()?->department)
        <img
            src="{{ $headerDepartment?->logo ? asset('storage/' . $headerDepartment->logo) : asset('images/images.jpg') }}"
            class="logo"
            alt="{{ $headerDepartment?->name ?? 'BSU Logo' }}"
            onerror="this.onerror=null;this.src='{{ asset('images/images.jpg') }}';"
        >

        <div class="header-title">

            <small>
                BSU Bokod Campus
            </small>

            <h3>
                WebroomSched
            </h3>

        </div>

    </div>


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
            <button type="button" class="header-action profile" title="Update profile photo" onclick="openProfilePhotoDialog()">
                @if(auth()->user()->profile_photo_path)
                    <img class="header-profile-photo" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile photo">
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

@auth
<div id="profilePhotoOverlay" class="profile-photo-overlay" style="display:none" role="dialog" aria-modal="true" aria-labelledby="profilePhotoTitle">
    <div class="profile-photo-dialog">
        <h2 id="profilePhotoTitle">My Profile</h2>
        <p>{{ auth()->user()->name ?? auth()->user()->username }}{{ auth()->user()->department ? ' · ' . auth()->user()->department->name : '' }}</p>
        @if(auth()->user()->profile_photo_path)
            <img id="profilePhotoPreview" class="profile-photo-preview" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Current profile photo">
        @else
            <div id="profilePhotoPlaceholder" class="profile-photo-preview" style="display:grid;place-items:center;background:#f1f5f9;color:#64748b;font-size:32px"><i class="fa-solid fa-user"></i></div>
            <img id="profilePhotoPreview" class="profile-photo-preview" src="" alt="Photo preview" style="display:none">
        @endif
        <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="profile_photo"><strong>Change profile photo</strong></label>
            <input id="profile_photo" name="profile_photo" type="file" accept="image/png,image/jpeg,image/webp" required>
            <small>JPG, PNG, or WebP · Maximum 2 MB</small>
            <div class="profile-dialog-actions">
                <button type="button" class="profile-cancel" onclick="closeProfilePhotoDialog()">Cancel</button>
                <button type="submit" class="profile-save">Save Photo</button>
            </div>
        </form>
    </div>
</div>
@endauth

@if(session('success') || session('error') || $errors->any())
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

<script>
function openProfilePhotoDialog() { const el = document.getElementById('profilePhotoOverlay'); if (el) el.style.display = 'flex'; }
function closeProfilePhotoDialog() { const el = document.getElementById('profilePhotoOverlay'); if (el) el.style.display = 'none'; }
function closeGlobalMessage() { const el = document.getElementById('globalMessageOverlay'); if (el) el.remove(); }
document.addEventListener('DOMContentLoaded', function () {
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
        if (event.key === 'Escape') { closeProfilePhotoDialog(); closeGlobalMessage(); }
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

