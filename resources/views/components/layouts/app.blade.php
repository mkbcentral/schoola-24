<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light" class="">

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
                document.documentElement.classList.add('dark'); // Tailwind dark mode
            }
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite([ 'resources/css/app.css', 'resources/js/app.js'])
    @stack('style')
</head>

<body>
    <div class="flex min-h-screen bg-gray-50 dark:bg-gray-900">
        @include('components.layouts.partials.sidebar')
        <!-- Page Content -->
        <div id="content" class="flex-1 flex flex-col ml-64 transition-all duration-300">
            @include('components.layouts.partials.navbar')
            <div class="flex-1 overflow-y-auto">
                <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    @stack('js')
</body>

</html>
