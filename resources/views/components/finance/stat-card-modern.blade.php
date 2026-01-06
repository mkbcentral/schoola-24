{{-- Composant de Carte Statistique Moderne --}}
@props([
    'title' => '',
    'value' => 0,
    'currency' => 'USD',
    'icon' => 'cash-coin',
    'color' => 'blue',
    'trend' => null, // 'up', 'down', null
    'trendValue' => null,
    'subtitle' => null
])

@php
    $colorClasses = [
        'blue' => 'from-blue-500 via-indigo-500 to-purple-600',
        'green' => 'from-emerald-500 via-green-500 to-teal-600',
        'red' => 'from-rose-500 via-red-500 to-pink-600',
        'amber' => 'from-amber-500 via-orange-500 to-red-600',
        'purple' => 'from-purple-500 via-violet-500 to-indigo-600',
        'cyan' => 'from-cyan-500 via-blue-500 to-indigo-600',
    ];
    
    $gradientClass = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div class="group relative bg-gradient-to-br {{ $gradientClass }} rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
    {{-- Effet de brillance au survol --}}
    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    
    {{-- Motif décoratif en arrière-plan --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white rounded-full"></div>
        <div class="absolute -left-4 -bottom-4 w-24 h-24 bg-white rounded-full"></div>
    </div>
    
    <div class="relative p-6 text-white">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <p class="text-white/80 uppercase text-xs font-bold tracking-wider mb-1">
                    {{ $title }}
                </p>
                @if ($subtitle)
                    <p class="text-white/60 text-xs">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="bg-white/20 backdrop-blur-md rounded-xl p-3 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                <i class="bi bi-{{ $icon }} text-3xl"></i>
            </div>
        </div>
        
        <div class="space-y-3">
            <div class="flex items-end gap-2">
                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight">
                    {{ app_format_number($value, 2) }}
                </h1>
                @if ($trend)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-white/25 rounded-lg text-sm font-bold mb-2">
                        @if ($trend === 'up')
                            <i class="bi bi-arrow-up"></i>
                        @else
                            <i class="bi bi-arrow-down"></i>
                        @endif
                        @if ($trendValue)
                            {{ $trendValue }}
                        @endif
                    </span>
                @endif
            </div>
            
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/25 backdrop-blur-sm rounded-xl font-semibold">
                <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                {{ $currency }}
            </span>
        </div>
    </div>
</div>
