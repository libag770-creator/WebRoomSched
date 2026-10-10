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
        content="<?php echo e(csrf_token()); ?>"
    >

    <title><?php echo $__env->yieldContent('title'); ?></title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    
    <link
        rel="stylesheet"
        href="<?php echo e(asset('css/style.css')); ?>"
    >

    <link
        rel="stylesheet"
        href="<?php echo e(asset('css/modern.css')); ?>"
    >

    <link rel="stylesheet" href="<?php echo e(asset('css/professional.css')); ?>">

    
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

    <style>
        :root {
            --department-theme-color: <?php echo e(auth()->user()?->department?->theme_color ?? '#087443'); ?>;
            --department-theme-rgb: 8,116,67;
        }
        .top-header { border-top: 5px solid var(--department-theme-color); }
        .header-action.profile { display:flex; align-items:center; gap:8px; max-width:240px; }
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

</head>

<body>

<header class="top-header">

    <div class="header-left">

        <?php ($headerDepartment = auth()->user()?->department); ?>
        <img
            src="<?php echo e($headerDepartment?->logo ? asset('storage/' . $headerDepartment->logo) : asset('images/images.jpg')); ?>"
            class="logo"
            alt="<?php echo e($headerDepartment?->name ?? 'BSU Logo'); ?>"
            onerror="this.onerror=null;this.src='<?php echo e(asset('images/images.jpg')); ?>';"
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

        <button
            type="button"
            class="header-action notification"
            title="Notifications"
        >
            <i class="fa-solid fa-bell"></i>
        </button>


        <?php if(auth()->guard()->check()): ?>
            <button type="button" class="header-action profile" title="Update profile photo" onclick="openProfilePhotoDialog()">
                <?php if(auth()->user()->profile_photo_path): ?>
                    <img class="header-profile-photo" src="<?php echo e(asset('storage/' . auth()->user()->profile_photo_path)); ?>" alt="Profile photo">
                <?php else: ?>
                    <span class="header-profile-photo" style="display:grid;place-items:center;background:#e5e7eb;color:#475569;"><i class="fa-solid fa-user"></i></span>
                <?php endif; ?>
                <span class="header-profile-name"><?php echo e(auth()->user()->name ?? auth()->user()->username ?? 'My Profile'); ?></span>
            </button>
        <?php endif; ?>


        <form
            action="<?php echo e(route('faculty.logout')); ?>"
            method="POST"
            class="logout-form"
        >

            <?php echo csrf_field(); ?>

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

<?php if(auth()->guard()->check()): ?>
<div id="profilePhotoOverlay" class="profile-photo-overlay" style="display:none" role="dialog" aria-modal="true" aria-labelledby="profilePhotoTitle">
    <div class="profile-photo-dialog">
        <h2 id="profilePhotoTitle">My Profile</h2>
        <p><?php echo e(auth()->user()->name ?? auth()->user()->username); ?><?php echo e(auth()->user()->department ? ' · ' . auth()->user()->department->name : ''); ?></p>
        <?php if(auth()->user()->profile_photo_path): ?>
            <img id="profilePhotoPreview" class="profile-photo-preview" src="<?php echo e(asset('storage/' . auth()->user()->profile_photo_path)); ?>" alt="Current profile photo">
        <?php else: ?>
            <div id="profilePhotoPlaceholder" class="profile-photo-preview" style="display:grid;place-items:center;background:#f1f5f9;color:#64748b;font-size:32px"><i class="fa-solid fa-user"></i></div>
            <img id="profilePhotoPreview" class="profile-photo-preview" src="" alt="Photo preview" style="display:none">
        <?php endif; ?>
        <form action="<?php echo e(route('profile.photo')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
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
<?php endif; ?>

<?php if(session('success') || session('error') || $errors->any()): ?>
    <div id="globalMessageOverlay" class="global-message-overlay" role="dialog" aria-modal="true">
        <div class="global-message-dialog <?php echo e(session('success') ? 'success' : 'error'); ?>">
            <div class="global-message-icon"><?php echo e(session('success') ? '✓' : '!'); ?></div>
            <h2><?php echo e(session('success') ? 'Successful!' : (session('error') ? 'Action Failed' : 'Please Check')); ?></h2>
            <p>
                <?php if(session('success')): ?> <?php echo e(session('success')); ?>

                <?php elseif(session('error')): ?> <?php echo e(session('error')); ?>

                <?php else: ?> <?php echo e($errors->first()); ?>

                <?php endif; ?>
            </p>
            <button type="button" class="profile-save" style="border:0;border-radius:8px;padding:10px 28px;cursor:pointer" onclick="closeGlobalMessage()">OK</button>
        </div>
    </div>
<?php endif; ?>

<script>
function openProfilePhotoDialog() { const el = document.getElementById('profilePhotoOverlay'); if (el) el.style.display = 'flex'; }
function closeProfilePhotoDialog() { const el = document.getElementById('profilePhotoOverlay'); if (el) el.style.display = 'none'; }
function closeGlobalMessage() { const el = document.getElementById('globalMessageOverlay'); if (el) el.remove(); }
document.addEventListener('DOMContentLoaded', function () {
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

<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/footerheader/header.blade.php ENDPATH**/ ?>