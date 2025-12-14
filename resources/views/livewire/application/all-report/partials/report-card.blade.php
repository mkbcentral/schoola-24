<div class="card border-0 mb-4" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
    <div class="card-header bg-white border-bottom" style="padding: 1.5rem;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-2 me-3" style="background: #f8f9fa; border: 2px solid #e9ecef;">
                    <i class="bi {{ $icon ?? 'bi-file-earmark-text' }} text-secondary"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-semibold" style="color: #2d3748;">{{ $title }}</h5>
                    @if (isset($subtitle))
                        <small class="text-muted" style="font-size: 0.85rem;">{{ $subtitle }}</small>
                    @endif
                </div>
            </div>
            @if (isset($actions))
                <div>
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
    <div class="card-body" style="padding: 1.5rem;">
        {{ $slot }}
    </div>
</div>
