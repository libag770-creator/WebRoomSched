<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>@yield('title')</title>

    {{-- COMMON SYSTEM CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    {{-- FONT AWESOME --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

</head>

<body>

<header class="top-header">

    <div class="header-left">

        <img
            src="{{ asset('images/images.jpg') }}"
            class="logo"
            alt="BSU Logo"
        >

        <div class="header-title">

            <small>
                BSU Bokod Campus
            </small>

            <h3>
                College of Applied Technology
            </h3>

        </div>

    </div>


    <div class="header-right">

        <button
            type="button"
            class="header-action notification"
            title="Notifications"
        >
            <i class="fa-solid fa-bell"></i>
        </button>


        <button
            type="button"
            class="header-action profile"
            title="Profile"
        >
            <i class="fa-solid fa-user"></i>
        </button>


        <form
            action="{{ route('faculty.logout') }}"
            method="POST"
            class="logout-form"
        >

            @csrf

            <button
                type="submit"
                class="header-action logout"
                title="Logout"
            >
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>

        </form>

    </div>

</header>