@props([
    'title' => 'Filtres',
    'icon' => 'funnel',
    'collapsible' => false,
    'defaultExpanded' => true,
])

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary bg-opacity-10 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-{{ $icon }} me-2"></i>
                {{ $title }}
            </h5>
            @if ($collapsible)
                <button class="btn btn-sm btn-link" data-bs-toggle="collapse" data-bs-target="#filterPanel">
                    <i class="bi bi-chevron-down"></i>
                </button>
            @endif
        </div>
    </div>
    <div class="card-body collapse {{ $defaultExpanded ? 'show' : '' }}" id="filterPanel">
        <div class="row g-3">
            {{ $slot }}
        </div>
    </div>
</div>
