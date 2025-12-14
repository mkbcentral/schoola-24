@props([
    'title' => '',
    'value' => 0,
    'currency' => 'USD',
    'icon' => 'cash-coin',
    'type' => 'primary', // primary, success, danger, warning
    'badge' => null,
])

@php
    $typeClasses = [
        'success' => 'dashboard-stat-card--success',
        'danger' => 'dashboard-stat-card--danger',
        'warning' => 'dashboard-stat-card--warning',
        'primary' => 'dashboard-stat-card--primary',
    ];

    $iconColors = [
        'success' => 'text-success',
        'danger' => 'text-danger',
        'warning' => 'text-warning',
        'primary' => 'text-primary',
    ];

    $cardClass = $typeClasses[$type] ?? $typeClasses['primary'];
    $iconColorClass = $iconColors[$type] ?? $iconColors['primary'];
@endphp

<div class="dashboard-stat-card {{ $cardClass }} h-100">
    <div class="dashboard-stat-card__body">
        <div class="dashboard-stat-card__header">
            <div>
                <p class="dashboard-stat-card__title">{{ $title }}</p>
            </div>
            <div class="dashboard-stat-card__icon-wrapper">
                <i class="bi bi-{{ $icon }} {{ $iconColorClass }}"></i>
            </div>
        </div>
        <div class="dashboard-stat-card__content">
            <h1 class="dashboard-stat-card__value">
                {{ app_format_number($value, 2) }}
            </h1>
            <span class="dashboard-stat-card__currency">
                <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                {{ $currency === 'USD' ? '$' : 'FC' }}
            </span>
            @if ($badge)
                <div class="mt-2">
                    {!! $badge !!}
                </div>
            @endif
        </div>
    </div>
</div>
