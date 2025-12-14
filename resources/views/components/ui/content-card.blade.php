@props([
    'title' => null,
    'icon' => null,
    'headerBg' => 'bg-body-tertiary',
    'noPadding' => false,
    'badge' => null,
])

<div class="card border-0 shadow-sm">
    @if ($title || isset($actions))
        <div class="card-header {{ $headerBg }} border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                @if ($title)
                    <h5 class="mb-0 fw-semibold text-body">
                        @if ($icon)
                            <i class="bi bi-{{ $icon }} me-2"></i>
                        @endif
                        {{ $title }}
                        @if ($badge)
                            <span class="badge bg-primary ms-2">{{ $badge }}</span>
                        @endif
                    </h5>
                @endif
                @if (isset($actions))
                    <div>{{ $actions }}</div>
                @endif
            </div>
        </div>
    @endif
    <div class="card-body {{ $noPadding ? 'p-0' : '' }}">
        {{ $slot }}
    </div>
</div>
