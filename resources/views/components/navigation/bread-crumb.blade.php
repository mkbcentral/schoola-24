@props(['color' => '', 'icon' => '', 'label' => ''])

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4 px-4 lg:px-0">
    {{-- Titre avec icône --}}
    <div class="flex items-center gap-3">
        @if ($icon)
            <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2.5">
                <i class="{{ $icon }} text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
        @endif
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 {{ $color }}">
            {{ $label }}
        </h1>
    </div>
    
    {{-- Breadcrumb navigation --}}
    <nav aria-label="breadcrumb" class="flex items-center">
        <ol class="flex items-center gap-2 text-sm">
            {{ $slot }}
        </ol>
    </nav>
</div>
