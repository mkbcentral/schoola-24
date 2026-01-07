<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Authentification</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">

    <!-- Bootstrap 4.6.2 CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap-4.6.2-dist/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    @if (config('app.env') === 'production')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @endif

    <!-- Moment.js -->
    <script src="{{ asset('moment/moment.min.js') }}"></script>

    @vite(['resources/js/app.js'])
    @stack('style')
</head>

<body>
    <div class="">
           {{ $slot }}
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="{{ asset('bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js') }}"></script>

    @stack('js')
</body>

</html>
