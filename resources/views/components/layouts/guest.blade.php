<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <script src="{{ asset('moment/moment.min.js') }}"></script>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="hold-transition login-page antialiased"
    style="background: url({{ asset('images/my-bg.svg') }});background-size:cover;background-repeat: no-repeat">
    {{ $slot }}
</body>

</html>
