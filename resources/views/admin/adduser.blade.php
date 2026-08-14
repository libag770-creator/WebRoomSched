<h2>Add User</h2>

<form action="{{ route('admin.manageusers.store') }}" method="POST">
    @csrf

    <p>Name</p>
    <input type="text" name="name" required>

    <p>Username</p>
    <input type="text" name="username" required>

    <p>Email</p>
    <input type="email" name="email" required>

    <p>Password</p>
    <input type="password" name="password" required>

    <p>Role</p>

    <select name="role">
        <option value="admin">Admin</option>
        <option value="chair">Chair</option>
        <option value="faculty">Faculty</option>
    </select>

    <br><br>

    <button type="submit">
        Save User
    </button>

</form>