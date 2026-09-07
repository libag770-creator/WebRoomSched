<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>WebRoom Sched | BSU Bokod Campus</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1b5e20, #2e7d32);
            color: #333;
        }

        /* ================================
           HEADER
        ================================= */

        header {
            width: 100%;
            padding: 20px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.12);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .brand-text h2 {
            color: #1b5e20;
            font-size: 23px;
            margin-bottom: 3px;
        }

        .brand-text span {
            color: #777;
            font-size: 13px;
        }

        .login-button {
            text-decoration: none;
            background: #f9a825;
            color: #1b1b1b;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .login-button:hover {
            background: #f57f17;
            color: white;
            transform: translateY(-2px);
        }

        /* ================================
           HERO
        ================================= */

        .hero {
            min-height: calc(100vh - 95px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 7%;
        }

        .hero-container {
            width: 100%;
            max-width: 1150px;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        /* ================================
           LEFT
        ================================= */

        .hero-left {
            width: 48%;
            min-height: 560px;

            background:
                linear-gradient(
                    145deg,
                    #2e7d32,
                    #1b5e20
                );

            display: flex;
            justify-content: center;
            align-items: center;

            position: relative;
            overflow: hidden;
        }

        .hero-left::before {
            content: "";
            position: absolute;

            width: 350px;
            height: 350px;

            background: rgba(249, 168, 37, 0.12);

            border-radius: 50%;

            top: -130px;
            left: -120px;
        }

        .hero-left::after {
            content: "";
            position: absolute;

            width: 280px;
            height: 280px;

            background: rgba(255, 255, 255, 0.07);

            border-radius: 50%;

            bottom: -130px;
            right: -100px;
        }

        .logo-container {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .logo {
            width: 190px;
            height: 190px;

            object-fit: contain;

            background: white;
            padding: 15px;

            border-radius: 50%;

            border: 6px solid #f9a825;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.25);
        }

        .logo-container h3 {
            color: white;
            margin-top: 25px;
            font-size: 25px;
        }

        .logo-container p {
            color: rgba(255, 255, 255, 0.85);
            margin-top: 8px;
            font-size: 14px;
        }

        /* ================================
           RIGHT
        ================================= */

        .hero-right {
            width: 52%;
            padding: 60px;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome {
            color: #f9a825;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .hero-right h1 {
            color: #1b5e20;
            font-size: 55px;
            line-height: 1.05;
            margin-bottom: 15px;
        }

        .campus {
            color: #555;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .description {
            color: #777;
            font-size: 15px;
            line-height: 1.7;
            max-width: 500px;
            margin-bottom: 35px;
        }

        /* ================================
           BUTTONS
        ================================= */

        .buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 14px 25px;

            border-radius: 9px;

            text-decoration: none;

            font-size: 15px;
            font-weight: bold;

            transition: all 0.3s ease;
        }

        .btn-schedule {
            background: #2e7d32;
            color: white;
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.25);
        }

        .btn-schedule:hover {
            background: #1b5e20;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.35);
        }

        .btn-login {
            background: white;
            color: #1b5e20;
            border: 2px solid #2e7d32;
        }

        .btn-login:hover {
            background: #e8f5e9;
            transform: translateY(-3px);
        }

        /* ================================
           USER INFO
        ================================= */

        .access-info {
            margin-top: 35px;
            padding: 18px;

            background: #f5f9f5;

            border-left: 4px solid #f9a825;

            border-radius: 8px;
        }

        .access-info strong {
            display: block;
            color: #1b5e20;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .access-info p {
            color: #777;
            font-size: 13px;
            line-height: 1.5;
        }

        /* ================================
           FOOTER
        ================================= */

        footer {
            text-align: center;
            padding: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
        }

        /* ================================
           MOBILE
        ================================= */

        @media (max-width: 800px) {

            header {
                padding: 15px 5%;
            }

            .brand-text h2 {
                font-size: 18px;
            }

            .brand-text span {
                font-size: 11px;
            }

            .brand img {
                width: 45px;
                height: 45px;
            }

            .login-button {
                padding: 10px 15px;
                font-size: 13px;
            }

            .hero {
                padding: 30px 5%;
            }

            .hero-container {
                flex-direction: column;
            }

            .hero-left,
            .hero-right {
                width: 100%;
            }

            .hero-left {
                min-height: 330px;
                padding: 40px 20px;
            }

            .logo {
                width: 135px;
                height: 135px;
            }

            .logo-container h3 {
                font-size: 20px;
            }

            .hero-right {
                padding: 40px 30px;
            }

            .hero-right h1 {
                font-size: 42px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

    </style>

</head>


<body>


    <!-- ================================
         HEADER
    ================================= -->

    <header>

        <div class="brand">

            <img
                src="{{ asset('images/images.jpg') }}"
                alt="BSU Bokod Campus Logo"
            >

            <div class="brand-text">

                <h2>WebRoom Sched</h2>

                <span>
                    BSU Bokod Campus
                </span>

            </div>

        </div>


        <!-- Staff Login -->

        <a
            href="{{ route('login') }}"
            class="login-button"
        >
            Login
        </a>

    </header>



    <!-- ================================
         MAIN WELCOME SECTION
    ================================= -->

    <main class="hero">

        <div class="hero-container">


            <!-- ================================
                 LEFT SIDE
            ================================= -->

            <section class="hero-left">

                <div class="logo-container">

                    <img
                        src="{{ asset('images/images.jpg') }}"
                        alt="BSU Bokod Campus Logo"
                        class="logo"
                    >

                    <h3>
                        BSU Bokod Campus
                    </h3>

                    <p>
                        Room Scheduling System
                    </p>

                </div>

            </section>



            <!-- ================================
                 RIGHT SIDE
            ================================= -->

            <section class="hero-right">

                <div class="welcome">
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

                    A simple and organized room scheduling
                    system for viewing classroom schedules,
                    room availability, and academic information.

                </p>



                <!-- ================================
                     ACTION BUTTONS
                ================================= -->

                <div class="buttons">

                    <!-- Students do NOT need to login -->
<a
    href="{{ route('student.dashboard') }}"
    class="btn btn-schedule"
>
    View Schedules
</a>


                    <!-- Admin / Chair / Faculty -->

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-login"
                    >
                        Admin / Chair / Faculty Login
                    </a>

                </div>



                <!-- ================================
                     ACCESS INFORMATION
                ================================= -->

                <div class="access-info">

                    <strong>
                        Easy Access
                    </strong>

                    <p>
                        Students can view room schedules directly
                        without logging in. Administrators, department
                        chairs, and faculty members can log in to
                        manage schedules and room information.
                    </p>

                </div>

            </section>


        </div>

    </main>



    <!-- ================================
         FOOTER
    ================================= -->

    <footer>

        © {{ date('Y') }} BSU Bokod Campus —
        WebRoom Sched

    </footer>


</body>

</html>