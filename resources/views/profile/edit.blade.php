@include('footerheader.header')

<div class="wrapper">
    <div class="main-layout">
        @if($user->role === 'admin')
            @include('admin.sidebar')
        @elseif($user->role === 'chair')
            @include('chair.sidebar')
        @else
            @include('faculty.sidebar')
        @endif

        <main class="content account-profile-view">
            <div class="account-profile-page">
                <header class="account-profile-heading">
                    <div>
                        <span class="account-profile-eyebrow">ACCOUNT SETTINGS</span>
                        <h1>My profile</h1>
                        <p>Manage the details and sign-in credentials for your WebRoomSched account.</p>
                    </div>
                    <span class="account-profile-heading-icon" aria-hidden="true"><i class="fa-regular fa-user"></i></span>
                </header>

                <section class="account-profile-card account-profile-summary" aria-label="Account overview">
                    <div class="account-profile-person">
                        <div class="account-profile-avatar">
                            @if($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" alt="Profile photo for {{ $user->name }}">
                            @else
                                <span aria-hidden="true">{{ strtoupper(substr((string) ($user->name ?: $user->username ?: 'U'), 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="account-profile-person-copy">
                            <span class="account-profile-label">YOUR ACCOUNT</span>
                            <h2>{{ $user->name ?: $user->username }}</h2>
                            <p>{{ '@' . $user->username }}</p>
                            <div class="account-profile-tags">
                                <span><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> {{ $user->role === 'faculty' ? 'Instructor' : ucfirst($user->role ?? 'User') }}</span>
                                @if($user->department)
                                    <span><i class="fa-solid fa-building-columns" aria-hidden="true"></i> {{ $user->department->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="account-profile-summary-details">
                        <div><span>EMAIL</span><strong>{{ $user->email }}</strong></div>
                        <div><span>COLLEGE</span><strong>{{ $user->department?->name ?? 'Not assigned' }}</strong></div>
                        <button type="button" class="account-profile-photo-button" onclick="openAccountProfileDialog()">
                            <i class="fa-regular fa-image" aria-hidden="true"></i> Change photo
                        </button>
                    </div>
                </section>

                <div class="account-profile-settings-grid">
                    <section class="account-profile-card account-profile-setting-card" aria-labelledby="username-settings-title">
                        <div class="account-profile-card-heading">
                            <span class="account-profile-card-icon"><i class="fa-solid fa-at" aria-hidden="true"></i></span>
                            <div>
                                <h2 id="username-settings-title">Sign-in username</h2>
                                <p>Change the username you enter on the login page.</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.username.update') }}" class="account-profile-form">
                            @csrf
                            @method('PUT')

                            <div class="account-profile-field">
                                <label for="profile_username">Username</label>
                                <input id="profile_username" name="username" type="text" value="{{ old('username', $user->username) }}" autocomplete="username" maxlength="255" required>
                                @if($errors->updateUsername->has('username'))
                                    <p class="account-profile-error">{{ $errors->updateUsername->first('username') }}</p>
                                @endif
                            </div>

                            <div class="account-profile-field">
                                <label for="username_current_password">Confirm with your current password</label>
                                <input id="username_current_password" name="current_password" type="password" autocomplete="current-password" required>
                                @if($errors->updateUsername->has('current_password'))
                                    <p class="account-profile-error">{{ $errors->updateUsername->first('current_password') }}</p>
                                @endif
                            </div>

                            <button type="submit" class="account-profile-submit"><i class="fa-solid fa-check" aria-hidden="true"></i> Save username</button>
                        </form>
                    </section>

                    <section class="account-profile-card account-profile-setting-card" aria-labelledby="password-settings-title">
                        <div class="account-profile-card-heading">
                            <span class="account-profile-card-icon"><i class="fa-solid fa-lock" aria-hidden="true"></i></span>
                            <div>
                                <h2 id="password-settings-title">Change password</h2>
                                <p>Choose a new password for your next sign-in.</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.password.update') }}" class="account-profile-form">
                            @csrf
                            @method('PUT')

                            <div class="account-profile-field">
                                <label for="profile_current_password">Current password</label>
                                <input id="profile_current_password" name="current_password" type="password" autocomplete="current-password" required>
                                @if($errors->updatePassword->has('current_password'))
                                    <p class="account-profile-error">{{ $errors->updatePassword->first('current_password') }}</p>
                                @endif
                            </div>

                            <div class="account-profile-field">
                                <label for="profile_new_password">New password</label>
                                <input id="profile_new_password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                                @if($errors->updatePassword->has('password'))
                                    <p class="account-profile-error">{{ $errors->updatePassword->first('password') }}</p>
                                @endif
                            </div>

                            <div class="account-profile-field">
                                <label for="profile_new_password_confirmation">Confirm new password</label>
                                <input id="profile_new_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                                @if($errors->updatePassword->has('password_confirmation'))
                                    <p class="account-profile-error">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                                @endif
                            </div>

                            <button type="submit" class="account-profile-submit"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Update password</button>
                            @if(session('status') === 'password-updated')
                                <p class="account-profile-success" role="status">Your new password is saved and ready for your next sign-in.</p>
                            @endif
                        </form>
                    </section>
                </div>
            </div>
        </main>
    </div>
</div>
