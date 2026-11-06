<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="theme-color" content="#f2f4f1">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title')
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <link rel="stylesheet" href="{{ asset('css/modern.css') }}">
    <link rel="stylesheet" href="{{ asset('css/professional.css') }}">
    <script src="{{ asset('js/sidebar.js') }}" defer></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

    <link rel="stylesheet" href="{{ asset('css/neon-theme.css') }}?v=sage-neumorphic-2">
</head>

<body class="app-shell">

    @include('footerheader.header')


    <div class="wrapper">

        @include('partials.sidebar')

        <main class="content">

            @yield('content')

        </main>

    </div>


    @include('footerheader.footer')


</body>
</html>






