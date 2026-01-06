{{-- Composant de Graphique Moderne --}}
@props([
    'title' => '',
    'chartId' => '',
    'icon' => 'bar-chart-line',
    'headerColor' => 'blue', // blue, indigo, purple, green, red, cyan
    'height' => '300px'
])

@php
    $headerClasses = [
        'blue' => 'from-blue-600 to-indigo-700',
        'indigo' => 'from-indigo-600 to-purple-700',
        'purple' => 'from-purple-600 to-pink-700',
        'green' => 'from-green-600 to-emerald-700',
        'red' => 'from-red-600 to-rose-700',
        'cyan' => 'from-cyan-600 to-blue-700',
        'gray' => 'from-gray-700 to-gray-900',
    ];
    
    $gradientClass = $headerClasses[$headerColor] ?? $headerClasses['blue'];
@endphp

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden" {{ $attributes }}>
    {{-- En-tête --}}
    <div class="bg-gradient-to-r {{ $gradientClass }} px-6 py-4">
        <h3 class="text-white font-bold text-lg flex items-center gap-2">
            <i class="bi bi-{{ $icon }}"></i>
            {{ $title }}
        </h3>
    </div>
    
    {{-- Zone du graphique --}}
    <div class="p-6">
        <div wire:ignore style="height: {{ $height }};">
            <canvas id="{{ $chartId }}"></canvas>
        </div>
    </div>
</div>
