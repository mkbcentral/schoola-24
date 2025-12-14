@props([
    'title' => '',
    'value' => 0,
    'icon' => 'check-circle-fill',
    'type' => 'success', // success, warning, primary
])

@php
    $typeClasses = [
        'success' => 'payment-card--success',
        'warning' => 'payment-card--warning',
        'primary' => 'payment-card--primary',
    ];

    $iconColors = [
        'success' => 'text-success',
        'warning' => 'text-warning',
        'primary' => 'text-primary',
    ];

    $cardClass = $typeClasses[$type] ?? $typeClasses['success'];
    $iconColor = $iconColors[$type] ?? $iconColors['success'];
@endphp

<div class="payment-card {{ $cardClass }} h-100">
    <div class="payment-card__body">
        <div class="payment-card__icon {{ $iconColor }}">
            <i class="bi bi-{{ $icon }}"></i>
        </div>
        <h6 class="payment-card__title">{{ $title }}</h6>
        <h3 class="payment-card__value">{{ is_numeric($value) ? app_format_number($value, 2) : $value }}</h3>
    </div>
</div>
