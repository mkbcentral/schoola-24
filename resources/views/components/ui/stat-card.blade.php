@props([
    'icon',
    'label',
    'value',
    'color' => 'primary',
    'trend' => null,
    'iconBg' => 'bg-opacity-10',
    'size' => 'default', // default, sm, lg
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body {{ $size === 'sm' ? 'p-2' : 'p-3' }}">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <p class="text-muted mb-1 text-uppercase small fw-medium"
                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    {{ $label }}
                </p>
                <h3 class="mb-0 fw-bold text-{{ $color }}">{{ $value }}</h3>
                @if ($trend)
                    <small class="text-{{ $trend > 0 ? 'success' : 'danger' }} mt-1 d-block">
                        <i class="bi bi-arrow-{{ $trend > 0 ? 'up' : 'down' }}"></i>
                        {{ abs($trend) }}%
                    </small>
                @endif
                @if ($slot->isNotEmpty())
                    <div class="mt-2">
                        {{ $slot }}
                    </div>
                @endif
            </div>
            <div class="rounded-circle bg-{{ $color }} {{ $iconBg }} p-3 flex-shrink-0">
                <i class="bi bi-{{ $icon }} text-{{ $color }} fs-4"></i>
            </div>
        </div>
    </div>
</div>
