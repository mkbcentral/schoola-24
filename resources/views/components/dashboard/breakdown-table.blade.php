@props([
    'title' => '',
    'icon' => 'table',
    'iconBg' => 'light', // light, primary, warning, info
    'headers' => [],
    'maxHeight' => null,
])

@php
    $iconBgClasses = [
        'light' => 'breakdown-table__icon--light',
        'primary' => 'breakdown-table__icon--primary',
        'warning' => 'breakdown-table__icon--warning',
        'info' => 'breakdown-table__icon--info',
    ];

    $iconClass = $iconBgClasses[$iconBg] ?? $iconBgClasses['light'];
@endphp

<div class="breakdown-table-card">
    <div class="breakdown-table-card__header">
        <div class="d-flex align-items-center">
            <div class="breakdown-table__icon {{ $iconClass }}">
                <i class="bi bi-{{ $icon }}"></i>
            </div>
            <h6 class="mb-0 fw-semibold">{{ $title }}</h6>
        </div>
    </div>
    <div class="breakdown-table-card__body">
        <div class="breakdown-table-wrapper"
            @if ($maxHeight) style="max-height: {{ $maxHeight }}; overflow-y: auto;" @endif>
            <table class="breakdown-table">
                <thead class="breakdown-table__head">
                    <tr>
                        @foreach ($headers as $header)
                            <th class="{{ $header['class'] ?? '' }}">{{ $header['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="breakdown-table__body">
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>
