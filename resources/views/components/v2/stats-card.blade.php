@props(['icon', 'label', 'value', 'iconColor' => 'text-primary', 'iconBg' => 'bg-light'])

<div class="card border-0 shadow-sm h-100" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
    <div class="card-body p-3">
        <div class="d-flex align-items-center">
            <div class="rounded-circle {{ $iconBg }} p-3 me-3">
                <i class="bi bi-{{ $icon }} {{ $iconColor }} fs-4"></i>
            </div>
            <div>
                <small class="text-muted d-block mb-1">{{ $label }}</small>
                <h4 class="mb-0 fw-bold">{{ $value }}</h4>
            </div>
        </div>
    </div>
</div>
