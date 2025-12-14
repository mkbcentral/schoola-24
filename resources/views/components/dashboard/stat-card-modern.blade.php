@props([
    'title' => '',
    'value' => 0,
    'currency' => 'USD',
    'icon' => 'cash-coin',
    'iconBg' => 'success', // success, danger, primary, warning
])

@php
    $bgClasses = [
        'success' => 'dashboard-modern-card__icon--success',
        'danger' => 'dashboard-modern-card__icon--danger',
        'primary' => 'dashboard-modern-card__icon--primary',
        'warning' => 'dashboard-modern-card__icon--warning',
    ];

    $iconClass = $bgClasses[$iconBg] ?? $bgClasses['success'];
@endphp

<div class="dashboard-modern-card">
    <div class="dashboard-modern-card__body">
        <div class="dashboard-modern-card__header">
            <div>
                <p class="dashboard-modern-card__label">{{ $title }}</p>
            </div>
            <div class="dashboard-modern-card__icon {{ $iconClass }}">
                <i class="bi bi-{{ $icon }}"></i>
            </div>
        </div>
        <h2 class="dashboard-modern-card__value">
            {{ app_format_number($value, 2) }}
        </h2>
        <span class="dashboard-modern-card__badge">
            {{ $currency }}
        </span>
    </div>
</div>
