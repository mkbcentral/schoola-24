<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <script src="{{ asset('moment/moment.min.js') }}"></script>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">
    @if (config('app.env') === 'production')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('style')
</head>

<body>
    <div class="">
           {{ $slot }}
    </div>
    @stack('js')
</body>

</html>
