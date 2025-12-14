@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'loadingText' => 'Chargement...',
    'pill' => false,
    'outline' => false,
])

@php
    $classes = [
        'btn',
        $outline ? "btn-outline-{$variant}" : "btn-{$variant}",
        $size !== 'md' ? "btn-{$size}" : '',
        $pill ? 'rounded-pill' : '',
        isset($attributes['class']) ? $attributes['class'] : '',
    ];
    $finalClass = implode(' ', array_filter($classes));
@endphp

<button {{ $attributes->merge(['class' => $finalClass]) }}>
    @if ($loading)
        <span class="spinner-border spinner-border-sm me-2"></span>
        <span>{{ $loadingText }}</span>
    @else
        @if ($icon && $iconPosition === 'left')
            <i class="bi bi-{{ $icon }} me-1"></i>
        @endif
        {{ $slot }}
        @if ($icon && $iconPosition === 'right')
            <i class="bi bi-{{ $icon }} ms-1"></i>
        @endif
    @endif
</button>
