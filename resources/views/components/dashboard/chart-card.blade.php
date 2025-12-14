@props([
    'title' => '',
    'chartId' => '',
    'headerClass' => 'bg-primary',
    'icon' => 'bar-chart-line',
    'height' => '300px',
])

<div class="dashboard-chart-card h-100">
    <div class="dashboard-chart-card__header {{ $headerClass }}">
        <h6 class="mb-0">
            <i class="bi bi-{{ $icon }}"></i>
            {{ $title }}
        </h6>
    </div>
    <div class="dashboard-chart-card__body" wire:ignore style="height: {{ $height }};">
        <canvas id="{{ $chartId }}"></canvas>
    </div>
</div>
