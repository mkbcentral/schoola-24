{{-- Offcanvas Filters - Tailwind CSS + Alpine.js --}}
@props([
    'title' => 'Filtres supplémentaires',
    'icon' => 'bi-funnel-fill'
])

<div x-data="{ open: false }" 
     @keydown.escape.window="open = false"
     @open-filters.window="open = true"
     @close-filters.window="open = false">
    
    {{-- Overlay --}}
    <div x-show="open"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-linear-to-br from-blue-900/40 via-purple-900/30 to-gray-900/40 backdrop-blur-sm z-40"
         style="display: none;">
    </div>

    {{-- Offcanvas Panel --}}
    <div x-show="open"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full sm:w-96 bg-white/95 dark:bg-gray-800/95 backdrop-blur-md shadow-2xl border-l border-gray-200/50 dark:border-gray-700/50 z-50 flex flex-col"
         style="display: none;"
         @click.outside="open = false">
        
        {{-- Header --}}
        <div class="bg-linear-to-r from-gray-50/80 to-blue-50/80 dark:from-gray-700/80 dark:to-gray-800/80 backdrop-blur-sm px-6 py-4 border-b border-gray-200/50 dark:border-gray-600/50 flex items-center justify-between">
            <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-lg flex items-center gap-2">
                <i class="bi {{ $icon }}"></i>
                {{ $title }}
            </h3>
            <button @click="open = false"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Body (Scrollable) --}}
        <div class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </div>

        {{-- Footer Actions --}}
        @if (isset($actions))
            <div class="border-t border-gray-200/50 dark:border-gray-600/50 p-6 bg-gradient-to-r from-gray-50/80 to-blue-50/80 dark:from-gray-700/80 dark:to-gray-800/80 backdrop-blur-sm">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
