<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>

@include('partials.header')

<div class="wrapper">

    @include('partials.sidebar')

    <main class="content">

        @yield('content')

    </main>

</div>

@include('partials.footer')

</body>
</html>