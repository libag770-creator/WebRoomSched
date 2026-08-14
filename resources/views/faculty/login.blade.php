<!DOCTYPE html>
<html>
<head>

    <title>Login</title>

    <style>

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 350px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            cursor: pointer;
        }

        .error {
            color: red;
            background: #ffe5e5;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
        }

    </style>

</head>

<body>

<div class="login-container">

    <h2>Login</h2>

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('faculty.login.submit') }}" method="POST">

        @csrf

        <label>
            Username
        </label>

        <input
            type="text"
            name="username"
            value="{{ old('username') }}"
            required
        >


        <label>
            Password
        </label>

        <input
            type="password"
            name="password"
            required
        >


        <label>
            Login as
        </label>

        <select name="role" required>

            <option value="">
                -- Select Role --
            </option>

            <option value="faculty"
                {{ old('role') == 'faculty' ? 'selected' : '' }}>
                Faculty
            </option>

            <option value="chair"
                {{ old('role') == 'chair' ? 'selected' : '' }}>
                Department Chair
            </option>

             <option value="admin"
            {{ old('role') == 'admin' ? 'selected' : '' }}>
            Admin
        </option>

        </select>


        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>
</html>