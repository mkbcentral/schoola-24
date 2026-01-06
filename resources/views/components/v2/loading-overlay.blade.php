@props(['title' => 'Chargement en cours...', 'subtitle' => 'Veuillez patienter'])

<!-- Overlay sombre avec effet glassmorphism -->
<div wire:loading
    class="fixed inset-0 bg-gradient-to-br from-gray-900/40 via-blue-900/30 to-purple-900/40 backdrop-blur-sm transition-opacity duration-300"
    style="z-index: 9998;">
</div>

<!-- Indicateur de chargement centré avec animations -->
<div wire:loading
    class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 transition-all duration-300"
    style="z-index: 9999;">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-xl min-w-[280px] overflow-hidden">
        <!-- Barre de progression animée en haut -->
        <div class="h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 animate-pulse"></div>

        <div class="px-6 py-8 text-center">
            <!-- Spinner avec gradient animé -->
            <div class="relative inline-flex items-center justify-center mb-4">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full blur-xl opacity-50 animate-pulse"></div>
                <div class="relative w-16 h-16 border-4 border-t-blue-500 border-r-purple-500 border-b-pink-500 border-l-transparent rounded-full animate-spin">
                </div>
                <div class="absolute inset-4 border-4 border-t-transparent border-r-pink-400 border-b-purple-400 border-l-blue-400 rounded-full animate-spin-slow"></div>
            </div>

            <!-- Titre avec gradient -->
            <div class="font-bold text-lg bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400 bg-clip-text text-transparent mb-2">
                {{ $title }}
            </div>

            <!-- Sous-titre -->
            <div class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center gap-1">
                <span>{{ $subtitle }}</span>
                <span class="inline-flex gap-0.5 animate-pulse">
                    <span class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></span>
                    <span class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full animation-delay-200"></span>
                    <span class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full animation-delay-400"></span>
                </span>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(-360deg);
        }
    }

    .animate-spin-slow {
        animation: spin-slow 2s linear infinite;
    }

    .animation-delay-200 {
        animation-delay: 200ms;
    }

    .animation-delay-400 {
        animation-delay: 400ms;
    }
</style>
