
<!DOCTYPE html>
<html>

<head>

    <title>WebRoom Sched</title>

<style>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }


    /* =================================
       PAGE
       ================================= */

    body {
        min-height: 100vh;

        display: flex;
        justify-content: center;
        align-items: center;

        background: #1b5e20;

        overflow: hidden;

        /* Start invisible */
        opacity: 0;

        animation: pageIntro 1.2s ease forwards;
    }


    /* =================================
       INTRO CONTAINER
       ================================= */

    .intro {
        width: 90%;
        max-width: 850px;

        min-height: 500px;

        background: #ffffff;

        border-radius: 20px;

        display: flex;

        overflow: hidden;

        box-shadow:
            0 15px 40px rgba(0, 0, 0, 0.25);

        /* Smooth entrance */
        opacity: 0;

        transform:
            translateY(35px)
            scale(0.96);

        animation:
            introCard 1.2s
            cubic-bezier(.22, 1, .36, 1)
            0.2s
            forwards;
    }


    /* =================================
       LEFT SIDE
       ================================= */

    .left {
        width: 50%;

        background:
            linear-gradient(
                160deg,
                #2e7d32,
                #1b5e20
            );

        color: white;

        display: flex;

        justify-content: center;
        align-items: center;

        text-align: center;

        padding: 40px;

        position: relative;

        overflow: hidden;
    }


    /* Decorative circle */

    .left::before {
        content: "";

        position: absolute;

        width: 260px;
        height: 260px;

        background:
            rgba(249, 168, 37, 0.12);

        border-radius: 50%;

        top: -90px;
        left: -90px;

        animation:
            circleIntro 1.5s ease forwards;
    }


    .left::after {
        content: "";

        position: absolute;

        width: 190px;
        height: 190px;

        background:
            rgba(255, 255, 255, 0.08);

        border-radius: 50%;

        bottom: -70px;
        right: -60px;

        animation:
            circleIntro 1.7s ease forwards;
    }


    /* =================================
       LEFT CONTENT
       ================================= */

    .left-content {
        position: relative;

        z-index: 2;

        opacity: 0;

        transform: scale(0.7);

        animation:
            logoContentIntro 1s
            cubic-bezier(.22, 1, .36, 1)
            0.7s
            forwards;
    }


    /* =================================
       LOGO
       ================================= */

    .logo {
        width: 130px;
        height: 130px;

        object-fit: contain;

        background: #ffffff;

        padding: 12px;

        border-radius: 50%;

        border:
            5px solid #f9a825;

        box-shadow:
            0 8px 20px rgba(0, 0, 0, 0.2);
    }


    /* =================================
       RIGHT SIDE
       ================================= */

    .right {
        width: 50%;

        display: flex;

        flex-direction: column;

        justify-content: center;

        align-items: center;

        text-align: center;

        padding: 45px;
    }


    .welcome {
        color: #f9a825;

        font-size: 15px;

        font-weight: bold;

        text-transform: uppercase;

        letter-spacing: 2px;

        margin-bottom: 10px;

        opacity: 0;

        transform: translateY(15px);

        animation:
            textIntro .8s ease
            0.8s
            forwards;
    }


    h1 {
        color: #2e7d32;

        font-size: 48px;

        line-height: 1.05;

        margin-bottom: 15px;

        opacity: 0;

        transform: translateY(15px);

        animation:
            textIntro .8s ease
            0.95s
            forwards;
    }


    .campus {
        color: #555;

        font-size: 16px;

        margin-bottom: 20px;

        opacity: 0;

        transform: translateY(15px);

        animation:
            textIntro .8s ease
            1.1s
            forwards;
    }


    .description {
        max-width: 330px;

        color: #777;

        line-height: 1.6;

        font-size: 14px;

        margin-bottom: 30px;

        opacity: 0;

        transform: translateY(15px);

        animation:
            textIntro .8s ease
            1.25s
            forwards;
    }


    /* =================================
       LOADING
       ================================= */

    .loading {
        display: flex;

        flex-direction: column;

        align-items: center;

        gap: 10px;

        color: #2e7d32;

        font-size: 13px;

        opacity: 0;

        animation:
            loadingIntro .8s ease
            1.45s
            forwards;
    }


    .loader {
        width: 32px;
        height: 32px;

        border:
            4px solid #e8f5e9;

        border-top:
            4px solid #f9a825;

        border-radius: 50%;

        animation:
            spin 1s linear infinite;
    }


    /* =================================
       INTRO ANIMATIONS
       ================================= */

    @keyframes pageIntro {

        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }

    }


    @keyframes introCard {

        from {
            opacity: 0;

            transform:
                translateY(35px)
                scale(0.96);
        }

        to {
            opacity: 1;

            transform:
                translateY(0)
                scale(1);
        }

    }


    @keyframes logoContentIntro {

        from {
            opacity: 0;

            transform:
                scale(0.7);
        }

        to {
            opacity: 1;

            transform:
                scale(1);
        }

    }


    @keyframes circleIntro {

        from {
            opacity: 0;

            transform:
                scale(0.5);
        }

        to {
            opacity: 1;

            transform:
                scale(1);
        }

    }


    @keyframes textIntro {

        from {
            opacity: 0;

            transform:
                translateY(15px);
        }

        to {
            opacity: 1;

            transform:
                translateY(0);
        }

    }


    @keyframes loadingIntro {

        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }

    }


    @keyframes spin {

        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }

    }


    /* =================================
       OUTRO
       ================================= */

    body.page-exit {

        animation:
            pageOutro 0.8s
            cubic-bezier(.55, .085, .68, .53)
            forwards;
    }


    body.page-exit .intro {

        animation:
            cardOutro 0.8s
            cubic-bezier(.55, .085, .68, .53)
            forwards;
    }


    @keyframes pageOutro {

        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }

    }


    @keyframes cardOutro {

        from {
            opacity: 1;

            transform:
                translateY(0)
                scale(1);
        }

        to {
            opacity: 0;

            transform:
                translateY(-25px)
                scale(0.97);
        }

    }


    /* =================================
       MOBILE
       ================================= */

    @media (max-width: 700px) {

        body {
            padding: 20px;
        }


        .intro {
            flex-direction: column;

            width: 100%;

            max-width: 450px;
        }


        .left,
        .right {
            width: 100%;
        }


        .left {
            min-height: 260px;

            padding: 30px;
        }


        .right {
            padding: 35px 25px;
        }


        .logo {
            width: 100px;
            height: 100px;
        }


        h1 {
            font-size: 38px;
        }

    }


    /* =================================
       REDUCED MOTION
       ================================= */

    @media (prefers-reduced-motion: reduce) {

        body,
        .intro,
        .left-content,
        .welcome,
        h1,
        .campus,
        .description,
        .loading {
            animation: none !important;

            opacity: 1 !important;

            transform: none !important;
        }

    }

</style>



</head>


<body>


<div class="intro">


    <!-- ================================
         LEFT
         ================================ -->

    <div class="left">

        <div class="left-content">

            <img
                src="{{ asset('images/images.jpg') }}"
                alt="BSU Bokod Campus Logo"
                class="logo"
            >

        </div>

    </div>


    <!-- ================================
         RIGHT
         ================================ -->

    <div class="right">

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
            system for managing classrooms,
            schedules, and room availability.
        </p>


        <div class="loading">

            <div class="loader"></div>

            <span>
                Loading...
            </span>

        </div>

    </div>


</div>


<!-- =================================
     REDIRECT TO LOGIN
     ================================= -->

<script> 

setTimeout(function () { 
    document.body.classList.add('page-exit'); 
      setTimeout(function () { window.location.href = 
      "{{ route('login') }}"; }, 800); }, 3000);
       </script>


</body>

</html>
