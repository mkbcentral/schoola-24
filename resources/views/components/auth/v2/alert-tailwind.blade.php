{{-- Composant Alert - Version Tailwind CSS --}}
@props([
    'type' => 'danger',
    'icon' => 'exclamation-triangle-fill',
    'title' => null,
    'message',
    'dismissible' => true
])

@php
$bgColors = [
    'danger' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
    'warning' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200',
    'success' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
    'info' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
];

$iconColors = [
    'danger' => 'text-red-600 dark:text-red-400',
    'warning' => 'text-yellow-600 dark:text-yellow-400',
    'success' => 'text-green-600 dark:text-green-400',
    'info' => 'text-blue-600 dark:text-blue-400',
];
@endphp

<div class="flex items-start gap-3 p-4 mb-4 rounded-lg border {{ $bgColors[$type] }} transition-all duration-300" role="alert">
    <i class="bi bi-{{ $icon }} {{ $iconColors[$type] }} @if(!$title) text-2xl @endif flex-shrink-0 mt-0.5"></i>
    <div class="flex-1 min-w-0">
        @if($title)
            <strong class="block font-semibold mb-1">{{ $title }}</strong>
        @endif
        <small class="block text-sm">{{ $message }}</small>
    </div>
    @if($dismissible)
        <button type="button" 
            class="flex-shrink-0 inline-flex items-center justify-center w-6 h-6 rounded-md hover:bg-black/5 dark:hover:bg-white/5 transition-colors" 
            onclick="this.parentElement.remove()" 
            aria-label="Close">
            <i class="bi bi-x text-lg"></i>
        </button>
    @endif
</div>
