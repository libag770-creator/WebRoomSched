<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>WebRoomSched | BSU Bokod Campus</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --green-dark: #14532d;
            --green: #15803d;
            --green-light: #22c55e;
            --gold: #f9a825;
            --text: #17221b;
            --muted: #708078;
            --border: #dfe8e2;
            --soft: #f5f9f6;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", Arial, Helvetica, sans-serif;
            background:
                radial-gradient(
                    circle at 8% 18%,
                    rgba(34, 197, 94, .12),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 92% 82%,
                    rgba(21, 128, 61, .12),
                    transparent 30%
                ),
                #f2f6f3;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
            overflow: hidden;
            opacity: 0;
            animation: pageFadeIn .45s ease forwards;
        }

        body.page-exit {
            opacity: 0;
            transition: opacity .28s ease;
        }

        .page-shell {
            width: 100%;
            max-width: 1050px;
        }

        .split-card {
            position: relative;
            width: 100%;
            height: 620px;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 28px 70px rgba(20, 60, 36, .16);
            border: 1px solid rgba(20, 83, 45, .08);
        }

        .panel {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            will-change: transform;
            backface-visibility: hidden;
            transform: translate3d(0, 0, 0);
            transition: transform .78s cubic-bezier(.65, 0, .35, 1);
        }

        /* ==========================================
           WHITE PANEL
        ========================================== */

        .content-panel {
            left: 0;
            background: #ffffff;
            padding: 58px 60px;
            display: block;
            z-index: 3;
        }

        /* ==========================================
           GREEN PANEL
        ========================================== */

        .brand-panel {
            left: 50%;
            background: linear-gradient(
                145deg,
                #0b5d2a 0%,
                #08783a 48%,
                #159b4c 100%
            );
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 45px;
            z-index: 4;
            overflow: hidden;
        }

        .brand-panel::before,
        .brand-panel::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-panel::before {
            width: 390px;
            height: 390px;
            background: rgba(255,255,255,.07);
            top: -170px;
            right: -150px;
        }

        .brand-panel::after {
            width: 310px;
            height: 310px;
            background: rgba(249,168,37,.12);
            bottom: -150px;
            left: -130px;
        }

        .brand-inner {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .brand-logo {
            width: 178px;
            height: 178px;
            object-fit: contain;
            border-radius: 50%;
            background: #ffffff;
            padding: 14px;
            border: 5px solid var(--gold);
            box-shadow: 0 14px 34px rgba(0,0,0,.22);
            margin-bottom: 24px;
        }

        .brand-title {
            font-size: 30px;
            line-height: 1.15;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .brand-title span {
            color: #d7f7df;
        }

        .brand-text {
            color: rgba(255,255,255,.84);
            font-size: 14px;
            line-height: 1.55;
        }

        .brand-campus {
            margin-top: 14px;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
        }

        /* ==========================================
           WELCOME CONTENT
        ========================================== */

        .welcome-content {
            position: absolute;
            inset: 0;
            padding: 58px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            opacity: 1;
            visibility: visible;
            transform: translate3d(0, 0, 0);
            will-change: transform, opacity;
            transition:
                opacity .34s ease,
                transform .56s cubic-bezier(.65, 0, .35, 1),
                visibility 0s linear 0s;
        }

        .eyebrow {
            color: var(--gold);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.7px;
            margin-bottom: 13px;
        }

        .welcome-content h1 {
            color: var(--green-dark);
            font-size: clamp(43px, 5vw, 58px);
            line-height: 1.02;
            letter-spacing: -1.7px;
            margin-bottom: 15px;
        }

        .campus {
            color: #44564d;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .description {
            max-width: 500px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.75;
            margin-bottom: 32px;
        }

        /* ==========================================
           BUTTONS
        ========================================== */

        .buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            min-height: 49px;
            padding: 0 23px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition:
                transform .2s ease,
                box-shadow .2s ease,
                background .2s ease,
                border-color .2s ease;
        }

        .btn-schedule {
            background: linear-gradient(135deg, #15803d, #20a052);
            color: #ffffff;
            box-shadow: 0 9px 23px rgba(21, 128, 61, .22);
        }

        .btn-schedule:hover {
            transform: translateY(-2px);
            box-shadow: 0 13px 28px rgba(21, 128, 61, .28);
        }

        .btn-login {
            color: var(--green-dark);
            background: #ffffff;
            border: 1px solid #b9cabe;
        }

        .btn-login:hover {
            background: #f1f8f3;
            border-color: #8fb49a;
            transform: translateY(-2px);
        }

        /* ==========================================
           ACCESS INFO
        ========================================== */

        .access-info {
            margin-top: 30px;
            padding: 16px 17px;
            background: var(--soft);
            border: 1px solid #e0ece3;
            border-left: 4px solid var(--gold);
            border-radius: 11px;
            max-width: 525px;
        }

        .access-info strong {
            display: block;
            color: var(--green-dark);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .access-info p {
            color: #718078;
            font-size: 12.5px;
            line-height: 1.55;
        }

        /* ==========================================
           LOGIN CONTENT
        ========================================== */

        .login-content {
            position: absolute;
            inset: 0;
            padding: 58px 60px;
            display: flex;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transform: translate3d(34px, 0, 0);
            will-change: transform, opacity;
            transition:
                opacity .34s ease,
                transform .56s cubic-bezier(.65, 0, .35, 1),
                visibility 0s linear .56s;
        }

        .login-content-inner {
            width: 100%;
        }

        .login-panel-title {
            margin-bottom: 29px;
        }

        .small-title {
            color: #168344;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }

        .login-panel-title h2 {
            font-size: 31px;
            line-height: 1.15;
            color: #15251b;
            margin-bottom: 8px;
            letter-spacing: -.6px;
        }

        .login-panel-title p {
            color: #728078;
            font-size: 14px;
            line-height: 1.6;
        }

        .error-box {
            background: #fff1f1;
            border: 1px solid #fecaca;
            color: #b42318;
            padding: 13px 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .error-box div {
            margin: 3px 0;
        }

        .form-group {
            margin-bottom: 19px;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            color: #26352d;
            font-size: 13px;
            font-weight: 700;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b9991;
            font-size: 15px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 50px;
            padding: 0 45px;
            border: 1px solid #dce4df;
            border-radius: 13px;
            background: #fafcfb;
            color: #17221b;
            font-size: 14px;
            outline: none;
            transition: all .2s ease;
        }

        .form-input:hover {
            border-color: #b9c9bf;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: #1b8a48;
            box-shadow: 0 0 0 4px rgba(27, 138, 72, .10);
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #7c8982;
            cursor: pointer;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            transition: .2s ease;
        }

        .password-toggle:hover {
            color: #168344;
            background: #edf8f1;
        }

        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 3px 0 22px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #65736b;
            font-size: 13px;
            cursor: pointer;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #168344;
            cursor: pointer;
        }

        .login-button {
            width: 100%;
            height: 51px;
            border: none;
            border-radius: 13px;
            background: linear-gradient(135deg, #08783a, #159b4c);
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(8, 120, 58, .20);
            transition: all .2s ease;
        }

        .login-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 25px rgba(8, 120, 58, .27);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button.loading {
            pointer-events: none;
            opacity: .8;
        }

        .back-link {
            margin-top: 18px;
            text-align: center;
        }

        .back-link-button {
            border: none;
            background: transparent;
            color: #718078;
            font-size: 13px;
            cursor: pointer;
            transition: .2s ease;
            padding: 4px 7px;
        }

        .back-link-button:hover {
            color: #168344;
        }

        .footer-note {
            text-align: center;
            margin-top: 14px;
            color: #8b9991;
            font-size: 11px;
        }

        /* ==========================================
           PAGE STATES + SLIDE TRANSITION
        ========================================== */

        /* Welcome: white panel on left, green panel on right. */
        body.welcome-state .content-panel,
        body.transitioning-to-welcome .content-panel {
            transform: translate3d(0, 0, 0);
        }

        body.welcome-state .brand-panel,
        body.transitioning-to-welcome .brand-panel {
            transform: translate3d(0, 0, 0);
        }

        body.welcome-state .welcome-content {
            opacity: 1;
            visibility: visible;
            transform: translate3d(0, 0, 0);
        }

        body.welcome-state .login-content {
            opacity: 0;
            visibility: hidden;
            transform: translate3d(34px, 0, 0);
        }

        /* Login: green panel moves left, white panel moves right. */
        body.login-state .content-panel,
        body.transitioning-to-login .content-panel {
            transform: translate3d(100%, 0, 0);
        }

        body.login-state .brand-panel,
        body.transitioning-to-login .brand-panel {
            transform: translate3d(-100%, 0, 0);
        }

        /* During the slide, fade the old content out and new content in. */
        body.transitioning-to-login .welcome-content,
        body.login-state .welcome-content {
            opacity: 0;
            visibility: hidden;
            transform: translate3d(-28px, 0, 0);
            transition:
                opacity .28s ease,
                transform .52s cubic-bezier(.65, 0, .35, 1),
                visibility 0s linear .28s;
        }

        body.transitioning-to-login .login-content,
        body.login-state .login-content {
            opacity: 1;
            visibility: visible;
            transform: translate3d(0, 0, 0);
            transition:
                opacity .42s ease .16s,
                transform .56s cubic-bezier(.65, 0, .35, 1) .10s,
                visibility 0s linear 0s;
        }

        /* Reverse direction when returning to Welcome. */
        body.transitioning-to-welcome .welcome-content {
            opacity: 1;
            visibility: visible;
            transform: translate3d(0, 0, 0);
            transition:
                opacity .42s ease .16s,
                transform .56s cubic-bezier(.65, 0, .35, 1) .10s,
                visibility 0s linear 0s;
        }

        body.transitioning-to-welcome .login-content {
            opacity: 0;
            visibility: hidden;
            transform: translate3d(28px, 0, 0);
            transition:
                opacity .28s ease,
                transform .52s cubic-bezier(.65, 0, .35, 1),
                visibility 0s linear .28s;
        }

        /* ==========================================
           MOBILE
        ========================================== */

        @media (max-width: 850px) {

            body {
                padding: 18px;
                overflow-y: auto;
            }

            .split-card {
                height: auto;
                min-height: auto;
            }

            .panel {
                position: relative;
                left: 0 !important;
                width: 100%;
                transform: none !important;
            }

            .brand-panel {
                min-height: 320px;
                order: 1;
            }

            .content-panel {
                min-height: 470px;
                padding: 0;
                order: 2;
                position: relative;
            }

            .welcome-content,
            .login-content {
                position: relative;
                inset: auto;
                padding: 42px 30px;
                min-height: 470px;
            }

            .brand-logo {
                width: 145px;
                height: 145px;
            }

            .welcome-content h1 {
                font-size: 45px;
            }

            .login-content {
                height: auto !important;
            }

            body.login-state .login-content {
                min-height: 470px;
            }

            body.welcome-state .login-content,
            body.login-state .welcome-content {
                display: none;
            }

            body.welcome-state .welcome-content,
            body.login-state .login-content {
                display: flex;
            }

            body.transitioning-to-login .brand-panel,
            body.transitioning-to-login .content-panel,
            body.transitioning-to-welcome .brand-panel,
            body.transitioning-to-welcome .content-panel {
                transform: none !important;
            }
        }

        @media (max-width: 520px) {

            body {
                padding: 12px;
            }

            .brand-panel {
                min-height: 290px;
                padding: 30px 22px;
            }

            .brand-logo {
                width: 125px;
                height: 125px;
            }

            .brand-title {
                font-size: 25px;
            }

            .content-panel {
                padding: 35px 24px;
            }

            .welcome-content h1 {
                font-size: 40px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .login-panel-title h2 {
                font-size: 27px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }

        @keyframes pageFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>

<?php

    $isDirectLogin = request()->routeIs('login');
?>

<body class="<?php echo e($isDirectLogin ? 'login-state' : 'welcome-state'); ?>">

<main class="page-shell">

    <div class="split-card">

        <!-- =====================================================
             WHITE CONTENT PANEL
        ====================================================== -->

        <section class="panel content-panel">

            <!-- =========================
                 WELCOME CONTENT
            ========================== -->

            <div class="welcome-content">

                <div class="eyebrow">
                    Welcome to
                </div>

                <h1>
                    WebRoom<br>
                    Sched
                </h1>

                <div class="campus">
                    BSU Bokod Campus
                </div>

                <p class="description">
                    A simple and organized room scheduling system for viewing
                    classroom schedules, room availability, and academic information.
                </p>

                <div class="buttons">

                    <a href="<?php echo e(route('student.schedule')); ?>"
                       class="btn btn-schedule page-transition">

                        <i class="fa-solid fa-calendar-days"
                           style="margin-right:8px;"></i>

                        View Schedules

                    </a>

                    <!-- IMPORTANT:
                         This is a BUTTON, not an <a href>.
                         Clicking it never loads /login.
                    -->
                    <button
                        type="button"
                        class="btn btn-login"
                        id="openLogin"
                    >

                        <i class="fa-solid fa-right-to-bracket"
                           style="margin-right:8px;"></i>

                        Login

                    </button>

                </div>

                <div class="access-info">

                    <strong>
                        Easy Access
                    </strong>

                    <p>
                        Students can view room schedules directly without logging in.
                        Administrators, department chairs, and faculty members can log in
                        to manage schedules and room information.
                    </p>

                </div>

            </div>


            <!-- =========================
                 LOGIN CONTENT
            ========================== -->

            <div class="login-content">

                <div class="login-content-inner">

                    <div class="login-panel-title">

                        <div class="small-title">
                            Welcome back
                        </div>

                        <h2>
                            Sign in to your account
                        </h2>

                        <p>
                            Enter your credentials to continue to WebRoomSched.
                        </p>

                    </div>


                    <!-- SESSION ERROR -->

                    <?php if(session('error')): ?>

                        <div class="error-box">

                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?php echo e(session('error')); ?>


                        </div>

                    <?php endif; ?>


                    <!-- VALIDATION ERRORS -->

                    <?php if($errors->any()): ?>

                        <div class="error-box">

                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <div>

                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <?php echo e($error); ?>


                                </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    <?php endif; ?>


                    <!-- LOGIN FORM -->

                    <form
                        action="<?php echo e(route('login.submit')); ?>"
                        method="POST"
                        id="loginForm"
                    >

                        <?php echo csrf_field(); ?>

                        <!-- USERNAME -->

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="username"
                            >
                                Username
                            </label>

                            <div class="input-wrapper">

                                <i class="fa-solid fa-user input-icon"></i>

                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    class="form-input"
                                    value="<?php echo e(old('username')); ?>"
                                    placeholder="Enter your username"
                                    autocomplete="username"
                                    required
                                >

                            </div>

                        </div>


                        <!-- PASSWORD -->

                        <div class="form-group">

                            <label
                                class="form-label"
                                for="password"
                            >
                                Password
                            </label>

                            <div class="input-wrapper">

                                <i class="fa-solid fa-lock input-icon"></i>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-input"
                                    placeholder="Enter your password"
                                    autocomplete="current-password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="password-toggle"
                                    id="togglePassword"
                                    aria-label="Show password"
                                >

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </div>

                        </div>


                        <!-- REMEMBER ME -->

                        <div class="login-options">

                            <label class="remember">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    <?php echo e(old('remember') ? 'checked' : ''); ?>

                                >

                                <span>
                                    Remember me
                                </span>

                            </label>

                        </div>


                        <!-- LOGIN SUBMIT -->

                        <button
                            type="submit"
                            class="login-button"
                            id="loginButton"
                        >

                            <i class="fa-solid fa-right-to-bracket"></i>

                            <span id="loginText">
                                Sign In
                            </span>

                        </button>

                    </form>


                    <!-- BACK TO WELCOME
                         This is also a BUTTON so it does not navigate.
                    -->

                    <div class="back-link">

                        <button
                            type="button"
                            class="back-link-button"
                            id="backToWelcome"
                        >

                            <i class="fa-solid fa-arrow-left"></i>

                            Back to Welcome Page

                        </button>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             GREEN BRAND PANEL
        ====================================================== -->

        <section class="panel brand-panel">

            <div class="brand-inner">

                <img
                    src="<?php echo e(asset('images/images.jpg')); ?>"
                    alt="BSU Bokod Campus Logo"
                    class="brand-logo"
                >

                <div class="brand-title">
                    WebRoom<span>Sched</span>
                </div>

                <div class="brand-text">
                    Room Scheduling System
                </div>

                <div class="brand-campus">
                    BSU Bokod Campus
                </div>

            </div>

        </section>

    </div>

    <div class="footer-note">
        © <?php echo e(date('Y')); ?> BSU Bokod Campus — WebRoom Sched
    </div>

</main>


<script>

    /* =========================================================
       SMOOTH SINGLE-PAGE PANEL SWITCHING
    ========================================================== */

    let currentPage = <?php echo json_encode($isDirectLogin ? 'login' : 'welcome', 15, 512) ?>;

    const body = document.body;
    const openLogin = document.getElementById('openLogin');
    const backToWelcome = document.getElementById('backToWelcome');
    const splitCard = document.querySelector('.split-card');

    let isTransitioning = false;

    function finishTransition(target) {

        body.classList.remove(
            target === 'login'
                ? 'transitioning-to-login'
                : 'transitioning-to-welcome'
        );

        body.classList.remove(
            target === 'login'
                ? 'welcome-state'
                : 'login-state'
        );

        body.classList.add(
            target === 'login'
                ? 'login-state'
                : 'welcome-state'
        );

        isTransitioning = false;

        if (target === 'login') {

            const username = document.getElementById('username');

            if (username) {
                requestAnimationFrame(() => username.focus());
            }
        }
    }

    function switchPage(target) {

        if (isTransitioning || currentPage === target) {
            return;
        }

        isTransitioning = true;
        currentPage = target;

        const transitionClass =
            target === 'login'
                ? 'transitioning-to-login'
                : 'transitioning-to-welcome';

        const oldStateClass =
            target === 'login'
                ? 'welcome-state'
                : 'login-state';

        body.classList.remove(oldStateClass);
        body.classList.add(transitionClass);

        /*
         * Finish exactly when the card movement ends instead of using
         * a timer. This prevents visible snapping on slower/faster PCs.
         */
        if (splitCard) {

            let finished = false;

            const complete = () => {

                if (finished) {
                    return;
                }

                finished = true;

                splitCard.removeEventListener(
                    'transitionend',
                    onTransitionEnd
                );

                finishTransition(target);
            };

            const onTransitionEnd = (event) => {

                if (
                    event.propertyName === 'transform' &&
                    event.target.classList.contains('brand-panel')
                ) {
                    complete();
                }
            };

            splitCard.addEventListener(
                'transitionend',
                onTransitionEnd
            );

            window.setTimeout(complete, 900);
        } else {
            finishTransition(target);
        }
    }

    if (openLogin) {

        openLogin.addEventListener('click', function (event) {

            event.preventDefault();
            switchPage('login');

        });
    }

    if (backToWelcome) {

        backToWelcome.addEventListener('click', function (event) {

            event.preventDefault();
            switchPage('welcome');

        });
    }

    /* =========================================================
       VIEW SCHEDULES
       This one really needs to open another Laravel page,
       so normal navigation is kept with a small fade-out.
    ========================================================== */

    document.querySelectorAll('.page-transition').forEach(function (link) {

        link.addEventListener('click', function (event) {

            if (
                event.ctrlKey ||
                event.metaKey ||
                event.shiftKey ||
                event.altKey ||
                event.button !== 0
            ) {
                return;
            }

            const target = this.href;

            if (!target) {
                return;
            }

            event.preventDefault();

            body.classList.add('page-exit');

            setTimeout(function () {

                window.location.href = target;

            }, 280);

        });

    });


    /* =========================================================
       PASSWORD SHOW / HIDE
    ========================================================== */

    const passwordInput = document.getElementById('password');

    const togglePassword = document.getElementById('togglePassword');

    if (passwordInput && togglePassword) {

        togglePassword.addEventListener('click', function () {

            const icon = this.querySelector('i');

            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';

            icon.classList.toggle('fa-eye', !isHidden);
            icon.classList.toggle('fa-eye-slash', isHidden);

            this.setAttribute(
                'aria-label',
                isHidden ? 'Hide password' : 'Show password'
            );

        });
    }


    /* =========================================================
       LOGIN SUBMIT LOADING
       This one DOES submit to login.submit because this is
       the real authentication request.
    ========================================================== */

    const loginForm = document.getElementById('loginForm');

    const loginButton = document.getElementById('loginButton');

    const loginText = document.getElementById('loginText');

    if (loginForm) {

        loginForm.addEventListener('submit', function () {

            if (loginButton) {

                loginButton.classList.add('loading');

            }

            if (loginText) {

                loginText.textContent = 'Signing in...';

            }

            const icon = loginButton
                ? loginButton.querySelector('i')
                : null;

            if (icon) {

                icon.className = 'fa-solid fa-spinner fa-spin';

            }

        });
    }


    /* =========================================================
       ESC = BACK TO WELCOME
    ========================================================== */

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape' &&
            currentPage === 'login'
        ) {

            showWelcome();

        }

    });

</script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\Webclasssched\resources\views/welcome.blade.php ENDPATH**/ ?>