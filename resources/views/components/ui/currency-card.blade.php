@props(['currency', 'amount', 'decimals' => 2])

<div class="card border-0 shadow-sm bg-body-secondary">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted d-block">Total {{ $currency }}</small>
                <h5 class="mb-0 fw-bold text-body">
                    {{ number_format($amount, $decimals, ',', ' ') }}
                </h5>
            </div>
            <div class="badge bg-primary rounded-pill px-3 py-2">
                {{ $currency }}
            </div>
        </div>
    </div>
</div>
