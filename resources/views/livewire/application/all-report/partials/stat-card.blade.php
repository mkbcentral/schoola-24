<div class="row mb-4">
    <div class="col-md-{{ $size ?? '4' }} mb-3">
        <div class="card h-100 border-0 stat-card"
            style="background: white; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s ease;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="mb-1 text-uppercase"
                            style="font-size: 0.75rem; color: #718096; letter-spacing: 0.5px; font-weight: 600;">
                            {{ $label }}
                        </p>
                    </div>
                    <div class="rounded-circle"
                        style="width: 48px; height: 48px; background: {{ $iconBg ?? '#f0fdf4' }}; display: flex; align-items: center; justify-content: center;">
                        <i class="bi {{ $icon }}"
                            style="color: {{ $iconColor ?? '#10b981' }}; font-size: 1.5rem;"></i>
                    </div>
                </div>
                <h2 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #1a202c;">
                    {{ $value }}
                </h2>
                @if (isset($badge))
                    <span class="badge"
                        style="background: #f7fafc; color: #4a5568; font-size: 0.85rem; font-weight: 500; padding: 0.35rem 0.75rem;">
                        {{ $badge }}
                    </span>
                @endif
                @if (isset($trend))
                    <div class="mt-2">
                        <span class="badge"
                            style="background: {{ $trend['type'] === 'up' ? '#f0fdf4' : ($trend['type'] === 'down' ? '#fef2f2' : '#f7fafc') }}; color: {{ $trend['type'] === 'up' ? '#10b981' : ($trend['type'] === 'down' ? '#ef4444' : '#64748b') }};">
                            <i
                                class="bi {{ $trend['type'] === 'up' ? 'bi-arrow-up' : ($trend['type'] === 'down' ? 'bi-arrow-down' : 'bi-dash') }}"></i>
                            {{ $trend['value'] }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    }
</style>
