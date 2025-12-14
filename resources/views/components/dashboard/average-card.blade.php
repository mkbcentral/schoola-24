@props([
    'title' => '',
    'value' => 0,
    'currency' => 'USD',
    'icon' => 'graph-up-arrow',
    'iconBg' => 'success', // success, danger
])

@php
    $bgClasses = [
        'success' => 'average-card__icon--success',
        'danger' => 'average-card__icon--danger',
    ];

    $iconClass = $bgClasses[$iconBg] ?? $bgClasses['success'];
@endphp

<div class="average-card">
    <div class="average-card__body">
        <div class="average-card__header">
            <div class="average-card__icon {{ $iconClass }}">
                <i class="bi bi-{{ $icon }}"></i>
            </div>
            <h6 class="average-card__title">{{ $title }}</h6>
        </div>
        <h3 class="average-card__value">
            {{ app_format_number($value, 2) }}
            <span class="average-card__currency">{{ $currency }}</span>
        </h3>
    </div>
</div>
