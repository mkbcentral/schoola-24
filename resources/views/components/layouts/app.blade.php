<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/Vector-white.svg') }}">
    <title>{{ config('app.name') }}</title>

    {{-- Script inline pour éviter le flash de contenu (FOUC) --}}
    <script>
        (function() {
            const storedTheme = localStorage.getItem('schoola-theme');
            const preferredTheme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ?
                'dark' : 'light';
            const theme = storedTheme || preferredTheme;

            document.documentElement.setAttribute('data-bs-theme', theme);
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
        <div id="content" class="content-modern">
            @include('components.layouts.partials.navbar')
            <div class="main-content-wrapper">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    @stack('js')
</body>

</html>
