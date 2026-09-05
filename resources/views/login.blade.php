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

            box-shadow:
                0 3px 10px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #1b5e20;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;

            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input:focus {
            outline: none;
            border-color: #2e7d32;

            box-shadow:
                0 0 0 2px rgba(46,125,50,0.12);
        }


        /* ================================
           LOGIN BUTTON
           ================================ */

        button {
            width: 100%;
            padding: 11px;

            margin-top: 20px;

            cursor: pointer;

            background: #2e7d32;
            color: white;

            border: none;
            border-radius: 5px;

            font-size: 15px;
            font-weight: 600;

            transition: 0.25s ease;
        }

        button:hover {
            background: #1b5e20;
        }


        /* ================================
           ERROR
           ================================ */

        .error {
            color: #721c24;

            background: #ffe5e5;

            padding: 10px;

            margin-bottom: 15px;

            text-align: center;

            border-radius: 4px;
        }


        /* ================================
           BACK BUTTON
           ================================ */

        .back-button {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 6px;

            width: 100%;

            padding: 10px;

            margin-top: 10px;

            box-sizing: border-box;

            background: #6c757d;

            color: white;

            text-align: center;

            text-decoration: none;

            border-radius: 5px;

            font-weight: 500;

            transition: 0.25s ease;
        }

        .back-button:hover {
            background: #5a6268;
        }


        /* ================================
           VIEW SCHEDULE BUTTON
           ================================ */

        .schedule-button {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            width: 100%;

            padding: 11px;

            margin-top: 10px;

            box-sizing: border-box;

            background: #f9a825;

            color: #1b5e20;

            text-align: center;

            text-decoration: none;

            border-radius: 5px;

            font-size: 15px;

            font-weight: 700;

            transition: 0.25s ease;
        }

        .schedule-button:hover {
            background: #e89a00;

            color: white;

            transform: translateY(-1px);

            box-shadow:
                0 3px 8px rgba(0,0,0,0.12);
        }


        /* ================================
           MOBILE
           ================================ */

        @media (max-width: 500px) {

            .login-container {
                width: calc(100% - 40px);

                margin: 50px auto;

                padding: 25px;
            }

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


    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    <form action="{{ route('login.submit') }}" method="POST">

        @csrf


        <!-- USERNAME -->

        <label>
            Username
        </label>

        <input
            type="text"
            name="username"
            value="{{ old('username') }}"
            required
        >


        <!-- PASSWORD -->

        <label>
            Password
        </label>

        <input
            type="password"
            name="password"
            required
        >


        <!-- LOGIN -->

        <button type="submit">
            Login
        </button>


        <!-- BACK TO WELCOME -->

        <a
            href="{{ route('welcome') }}"
            class="back-button"
        >
            ← Back
        </a>


        <!-- VIEW SCHEDULE -->

        <a
            href="{{ route('student.dashboard') }}"
            class="schedule-button"
        >
            📅 View Schedule
        </a>


    </form>

</div>

</body>

</html>