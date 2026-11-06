@php($isDirectLogin = request()->routeIs('login'))

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#173b33">
    <title>WebRoomSched | BSU Bokod Campus</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/brand-system.css') }}?v=20261107-mobile-responsive-v1">

    {{-- Responsive overrides. These work with the existing brand-system.css design. --}}
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }

        body.welcome-page {
            width: 100%;
            min-width: 320px;
            min-height: 100vh;
            min-height: 100dvh;
            margin: 0;
            overflow-x: hidden;
        }

        .public-shell {
            width: min(100% - 48px, 1240px);
            max-width: 1240px;
            margin-inline: auto;
        }

        .public-topbar,
        .public-card,
        .public-content-panel,
        .public-brand-panel,
        .public-footer {
            min-width: 0;
        }

        .public-card {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(300px, .92fr);
            width: 100%;
            overflow: hidden;
        }

        .public-content-panel {
            min-width: 0;
        }

        .welcome-content,
        .login-content,
        .login-content-inner {
            min-width: 0;
            max-width: 100%;
        }

        .public-brand-panel {
            min-width: 0;
        }

        .brand-emblem {
            display: block;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .public-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .public-actions > a,
        .public-actions > button {
            min-width: 0;
        }

        .public-primary-action,
        .public-secondary-action,
        .login-button {
            min-height: 48px;
        }

        .public-form-group,
        .public-input-wrap {
            width: 100%;
            min-width: 0;
        }

        .public-input-wrap input {
            width: 100%;
            min-width: 0;
            font-size: 16px;
        }

        .public-error {
            overflow-wrap: anywhere;
        }

        .public-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 8px 20px;
        }

        /* Tablet */
        @media (max-width: 900px) {
            .public-shell {
                width: min(100% - 36px, 760px);
            }

            .public-card {
                grid-template-columns: minmax(0, 1fr) minmax(250px, .8fr);
            }

            .public-content-panel {
                padding: clamp(28px, 5vw, 48px);
            }

            .public-brand-panel {
                padding: 28px 22px;
            }

            .public-feature-row {
                flex-wrap: wrap;
                gap: 18px;
            }

            .public-feature-row > div {
                min-width: 0;
            }
        }

        /* Mobile phones */
        @media (max-width: 640px) {
            body.welcome-page {
                min-height: 100dvh;
            }

            .public-shell {
                width: calc(100% - 28px);
                max-width: 480px;
                margin: 0 auto;
                padding: 14px 0 18px;
            }

            .public-topbar {
                min-height: auto;
                padding: 8px 2px 18px;
                gap: 10px;
                align-items: center;
            }

            .public-brand {
                min-width: 0;
                gap: 9px;
            }

            .public-brand strong {
                font-size: 15px;
            }

            .public-brand small {
                font-size: 11px;
            }

            .public-brand-mark {
                flex: 0 0 auto;
            }

            .public-topbar-note {
                max-width: 145px;
                font-size: 10px;
                line-height: 1.35;
                text-align: right;
            }

            .public-card {
                display: flex;
                flex-direction: column;
                width: 100%;
                border-radius: 18px;
            }

            .public-content-panel {
                order: 1;
                width: 100%;
                padding: 30px 22px;
            }

            .welcome-content,
            .login-content {
                width: 100%;
            }

            .public-eyebrow {
                display: inline-block;
                max-width: 100%;
                font-size: 10px;
                letter-spacing: .12em;
                line-height: 1.5;
            }

            .welcome-content h1 {
                font-size: clamp(38px, 11vw, 54px);
                line-height: 1.02;
                overflow-wrap: anywhere;
            }

            .login-content h1 {
                font-size: clamp(32px, 9vw, 44px);
                line-height: 1.08;
            }

            .public-intro,
            .login-intro {
                max-width: 100%;
                font-size: 14px;
                line-height: 1.7;
            }

            .public-actions {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 10px;
                margin-top: 24px;
            }

            .public-actions > a,
            .public-actions > button {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                min-height: 50px;
                margin: 0;
                padding: 13px 16px;
                text-align: center;
                font-size: 14px;
            }

            .public-feature-row {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 16px;
                margin-top: 28px;
            }

            .public-feature-row > div {
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 0;
            }

            .public-feature-row > div > span:last-child {
                min-width: 0;
            }

            .public-feature-row strong,
            .public-feature-row small {
                display: block;
                overflow-wrap: anywhere;
            }

            .public-brand-panel {
                order: 2;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                width: 100%;
                min-height: 250px;
                padding: 20px 22px;
                border-radius: 0 0 18px 18px;
            }

            .brand-panel-topline,
            .brand-panel-bottom {
                width: 100%;
                gap: 8px;
                font-size: 9px;
            }

            .brand-panel-center {
                padding: 20px 0;
            }

            .brand-emblem-wrap {
                width: clamp(76px, 22vw, 105px);
                height: clamp(76px, 22vw, 105px);
                margin-inline: auto;
            }

            .brand-emblem {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .brand-panel-kicker {
                font-size: 10px;
                text-align: center;
            }

            .brand-panel-center > p {
                margin: 10px 0;
                font-size: clamp(30px, 9vw, 42px);
                line-height: 1;
                text-align: center;
            }

            .brand-grid-ornament {
                max-width: 130px;
                margin-inline: auto;
            }

            .login-content-inner {
                width: 100%;
            }

            .back-link-button {
                display: inline-flex;
                align-items: center;
                min-height: 40px;
                max-width: 100%;
                margin-bottom: 22px;
                font-size: 13px;
            }

            .public-login-form {
                width: 100%;
            }

            .public-form-group {
                margin-bottom: 18px;
            }

            .public-form-group label {
                font-size: 13px;
            }

            .public-input-wrap {
                width: 100%;
                min-height: 50px;
            }

            .public-input-wrap input {
                min-height: 48px;
                padding-top: 10px;
                padding-bottom: 10px;
                font-size: 16px;
            }

            .password-toggle {
                flex: 0 0 42px;
                min-width: 42px;
                min-height: 44px;
            }

            .remember-option {
                display: flex;
                align-items: center;
                gap: 9px;
                min-height: 42px;
                font-size: 13px;
            }

            .remember-option input {
                width: 18px;
                height: 18px;
                flex: 0 0 auto;
            }

            .login-button {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                min-height: 50px;
                margin-top: 16px;
                font-size: 14px;
            }

            .login-footnote {
                font-size: 11px;
                line-height: 1.6;
            }

            .public-footer {
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 5px;
                padding: 16px 4px 4px;
                text-align: center;
                font-size: 10px;
                line-height: 1.5;
            }
        }

        /* Very small phones */
        @media (max-width: 360px) {
            .public-shell {
                width: calc(100% - 20px);
            }

            .public-content-panel {
                padding: 26px 17px;
            }

            .public-topbar-note {
                max-width: 112px;
                font-size: 9px;
            }

            .public-brand strong {
                font-size: 14px;
            }

            .welcome-content h1 {
                font-size: 36px;
            }

            .public-brand-panel {
                padding-inline: 17px;
            }
        }

        /* Respect users who prefer reduced motion. */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="welcome-page {{ $isDirectLogin ? 'login-state' : 'welcome-state' }}">
    <main class="public-shell">
        <header class="public-topbar">
            <a class="public-brand" href="{{ route('welcome') }}" aria-label="WebRoomSched home">
                <span class="public-brand-mark" aria-hidden="true">W</span>
                <span>
                    <strong>WebRoomSched</strong>
                    <small>BSU Bokod Campus</small>
                </span>
            </a>

            <div class="public-topbar-note">
                <span class="live-dot" aria-hidden="true"></span>
                Campus room &amp; class planning
            </div>
        </header>

        <section class="public-card" aria-label="WebRoomSched access">
            <div class="public-content-panel">
                <section
                    class="welcome-content"
                    aria-labelledby="welcome-title"
                    aria-hidden="{{ $isDirectLogin ? 'true' : 'false' }}"
                >
                    <span class="public-eyebrow">A more considered campus day</span>

                    <h1 id="welcome-title">BSU BOKOD<br><em>CAMPUS</em></h1>

                    <p class="public-intro">
                        Find class schedules, check room availability, and keep your day moving with one clear view of campus.
                    </p>

                    <div class="public-actions">
                        <a href="{{ route('student.schedule') }}" class="public-primary-action page-transition">
                            Browse schedules
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>

                        <button type="button" class="public-secondary-action" id="openLogin">
                            <i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i>
                            Staff sign in
                        </button>
                    </div>

                    <div class="public-feature-row" aria-label="What you can do">
                        <div>
                            <span class="public-feature-icon">
                                <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                            </span>
                            <span>
                                <strong>Class schedules</strong>
                                <small>See the campus day</small>
                            </span>
                        </div>

                        <div>
                            <span class="public-feature-icon">
                                <i class="fa-solid fa-door-open" aria-hidden="true"></i>
                            </span>
                            <span>
                                <strong>Room availability</strong>
                                <small>Find a place to meet</small>
                            </span>
                        </div>
                    </div>
                </section>

                <section
                    class="login-content"
                    aria-labelledby="login-title"
                    aria-hidden="{{ $isDirectLogin ? 'false' : 'true' }}"
                >
                    <button type="button" class="back-link-button" id="backToWelcome">
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        Back to overview
                    </button>

                    <div class="login-content-inner">
                        <span class="public-eyebrow">Staff workspace</span>

                        <h1 id="login-title">Welcome back.</h1>

                        <p class="login-intro">
                            Sign in to manage schedules, rooms, and college activity.
                        </p>

                        @if(session('error'))
                            <div class="public-error" role="alert">
                                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="public-error" role="alert">
                                @foreach($errors->all() as $error)
                                    <div>
                                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('login.submit') }}" method="POST" id="loginForm" class="public-login-form">
                            @csrf

                            <div class="public-form-group">
                                <label for="username">Username</label>

                                <div class="public-input-wrap">
                                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                                    <input
                                        type="text"
                                        id="username"
                                        name="username"
                                        value="{{ old('username') }}"
                                        placeholder="Your username"
                                        autocomplete="username"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="public-form-group">
                                <label for="password">Password</label>

                                <div class="public-input-wrap">
                                    <i class="fa-solid fa-lock" aria-hidden="true"></i>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Your password"
                                        autocomplete="current-password"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        id="togglePassword"
                                        aria-label="Show password"
                                    >
                                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <label class="remember-option">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    {{ old('remember') ? 'checked' : '' }}
                                >
                                <span>Remember me</span>
                            </label>

                            <button type="submit" class="public-primary-action login-button" id="loginButton">
                                <i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i>
                                <span id="loginText">Sign in</span>
                            </button>
                        </form>

                        <p class="login-footnote">
                            For administrators, college chairs, and instructors.
                        </p>
                    </div>
                </section>
            </div>

            <aside class="public-brand-panel" aria-label="WebRoomSched at BSU Bokod Campus">
                <div class="brand-panel-topline">
                    <span>BSU</span>
                    <span class="brand-topline-rule"></span>
                    <span>BOKOD CAMPUS</span>
                </div>

                <div class="brand-panel-center">
                    <div class="brand-emblem-wrap">
                        <img
                            src="{{ asset('images/images.jpg') }}"
                            alt="BSU Bokod Campus emblem"
                            class="brand-emblem"
                        >
                    </div>

                    <span class="brand-panel-kicker">Thoughtful spaces. Clear schedules.</span>

                    <p>WebRoom<br><span>Sched</span></p>

                    <div class="brand-grid-ornament" aria-hidden="true">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                </div>

                <div class="brand-panel-bottom">
                    <span>WEBROOMSCHED</span>
                    <span>ROOM &amp; CLASS PLANNING</span>
                </div>
            </aside>
        </section>

        <footer class="public-footer">
            <span>© {{ date('Y') }} BSU Bokod Campus</span>
            <span>Designed for a better campus day</span>
        </footer>
    </main>

    <script>
        const pageBody = document.body;
        const openLoginButton = document.getElementById('openLogin');
        const backToWelcomeButton = document.getElementById('backToWelcome');
        const loginPanel = document.querySelector('.login-content');
        const welcomePanel = document.querySelector('.welcome-content');

        function setPage(mode) {
            const showLogin = mode === 'login';

            pageBody.classList.toggle('login-state', showLogin);
            pageBody.classList.toggle('welcome-state', !showLogin);

            if (loginPanel) {
                loginPanel.setAttribute('aria-hidden', String(!showLogin));
            }

            if (welcomePanel) {
                welcomePanel.setAttribute('aria-hidden', String(showLogin));
            }

            if (showLogin) {
                window.setTimeout(() => {
                    document.getElementById('username')?.focus();
                }, 180);
            }
        }

        openLoginButton?.addEventListener('click', () => setPage('login'));
        backToWelcomeButton?.addEventListener('click', () => setPage('welcome'));

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && pageBody.classList.contains('login-state')) {
                setPage('welcome');
            }
        });

        document.querySelectorAll('.page-transition').forEach((link) => {
            link.addEventListener('click', (event) => {
                if (
                    event.ctrlKey ||
                    event.metaKey ||
                    event.shiftKey ||
                    event.altKey ||
                    event.button !== 0
                ) {
                    return;
                }

                event.preventDefault();
                pageBody.classList.add('page-exit');

                window.setTimeout(() => {
                    window.location.href = link.href;
                }, 180);
            });
        });

        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('togglePassword');

        passwordToggle?.addEventListener('click', () => {
            if (!passwordInput) return;

            const reveal = passwordInput.type === 'password';
            passwordInput.type = reveal ? 'text' : 'password';

            passwordToggle.setAttribute(
                'aria-label',
                reveal ? 'Hide password' : 'Show password'
            );

            passwordToggle.innerHTML = reveal
                ? '<i class="fa-regular fa-eye-slash" aria-hidden="true"></i>'
                : '<i class="fa-regular fa-eye" aria-hidden="true"></i>';
        });

        document.getElementById('loginForm')?.addEventListener('submit', () => {
            const button = document.getElementById('loginButton');
            button?.classList.add('loading');

            const text = document.getElementById('loginText');
            if (text) {
                text.textContent = 'Signing in…';
            }
        });
    </script>
</body>
</html>
