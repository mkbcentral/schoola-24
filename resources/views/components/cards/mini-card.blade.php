@props([
    'title' => '',
    'value' => '',
    'icon' => 'bi bi-star',
    'variant' => 'primary',
    'badge' => null,
    'gradient' => false,
    'compact' => false,
])

@php
    $classes = 'mini-card';
    if ($gradient) {
        $classes .= ' mini-card-gradient';
    } elseif ($variant) {
        $classes .= ' mini-card-' . $variant;
    }
    if ($compact) {
        $classes .= ' mini-card-compact';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if ($badge)
        <span class="mini-card-badge">{{ $badge }}</span>
    @endif

    <div class="mini-card-icon">
        <i class="{{ $icon }}"></i>
    </div>

    @if ($title)
        <div class="mini-card-title">{{ $title }}</div>
    @endif

    <div class="mini-card-value">{{ $value }}</div>

    @if ($slot->isNotEmpty())
        <div class="mt-2">
            {{ $slot }}
        </div>
    @endif
</div>
