<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">
    <link rel="stylesheet" href="{{ public_path('bootstrap-4.6.2-dist/css/bootstrap.min.css') }}">
</head>

<body class="antialiased">
    <div class="d-flex justify-content-center">
        <img src="{{ public_path('aquila.jpg') }}" alt="Logo" width="100px">
        <div>
            <h6>C.S AQUILA</h6>
        </div>
    </div>
    {{ $slot }}
</body>

</html>