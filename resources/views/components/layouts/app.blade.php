<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">
    <title>{{ config('app.name') }}</title>
    {{-- Ensure no inline <script> tags are present here --}}
    @if (config('app.env') === 'production')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @endif
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('style')
</head>

<body>
    <div class="wrapper">
        @include('components.layouts.partials.sidebar')
        <!-- Page Content -->
        <div id="content">
            @include('components.layouts.partials.navbar')
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </div>

    </div>
    {{-- Ensure no inline <script> tags are present here --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    @stack('js')
</body>

</html>
