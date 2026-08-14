<h2>Reset Password</h2>

<h3>{{ $user->name }}</h3>

<form action="{{ route('admin.manageusers.reset.save', $user->id) }}" method="POST">

    @csrf

    <p>New Password</p>
    <input type="password" name="password" required>

    <p>Confirm Password</p>
    <input type="password" name="password_confirmation" required>

    <br><br>

    <button type="submit">
        Reset Password
    </button>

</form>