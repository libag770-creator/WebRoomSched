<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/faculty.css') }}">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<header class="top-header">

    <div class="header-left">

        <img src="{{ asset('images/images.jpg') }}" class="logo">

        <div>

            <small>BSU Bokod Campus</small>

            <h3>College of Applied Technology</h3>

        </div>

    </div>

    <div class="header-right">

        <button class="notification">

            <i class="fa-solid fa-bell"></i>

        </button>

        <button class="profile">

            <i class="fa-solid fa-user"></i>

        </button>

    

        <form action="{{ route('faculty.logout') }}" method="POST">
    @csrf
    <button type="submit">
        Logout
    </button>
</form>

    </div>

</header>