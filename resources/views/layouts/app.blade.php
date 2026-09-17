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

    <title>
        @yield('title')
    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >

</head>

<body>

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