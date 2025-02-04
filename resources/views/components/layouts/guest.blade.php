<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <script src="{{ asset('moment/moment.min.js') }}"></script>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">
    @vite(['resources/sass/guest.scss', 'resources/js/app.js'])
    @stack('style')
</head>

<body>
    <div class="login-page">
        <div class="login-box">
            <div class="login-content">
                <div class="brand-logo">
                    <img class="" src="{{ asset('images/logo.svg') }}" alt="Logo" width="260px">
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
    @stack('js')
</body>

</html>
