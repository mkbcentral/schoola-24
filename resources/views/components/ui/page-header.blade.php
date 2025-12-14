@props(['title', 'icon' => null, 'subtitle' => null])

<div class="card border-0 shadow-sm bg-primary bg-gradient bg-opacity-10">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-light text-primary">
                @if ($icon)
                    <i class="bi bi-{{ $icon }} me-2"></i>
                @endif
                {{ $title }}
            </h5>
            @if (isset($actions))
                <div>
                    {{ $actions }}
                </div>
            @endif
        </div>

        @if (isset($stats))
            {{ $stats }}
        @endif

        @if (isset($currencies))
            <div class="row g-2 mt-2">
                {{ $currencies }}
            </div>
        @endif
    </div>
</div>
