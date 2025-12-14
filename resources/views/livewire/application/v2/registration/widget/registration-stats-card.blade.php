<div class="row g-3">
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75 small">Total Inscriptions</p>
                        <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm h-100"
            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75 small">Garçons</p>
                        <h2 class="mb-0 fw-bold">{{ $stats['total_male'] }}</h2>
                        <small class="opacity-75">
                            {{ $stats['total'] > 0 ? round(($stats['total_male'] / $stats['total']) * 100, 1) : 0 }}%
                        </small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-gender-male"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm h-100"
            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75 small">Filles</p>
                        <h2 class="mb-0 fw-bold">{{ $stats['total_female'] }}</h2>
                        <small class="opacity-75">
                            {{ $stats['total'] > 0 ? round(($stats['total_female'] / $stats['total']) * 100, 1) : 0 }}%
                        </small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-gender-female"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm h-100"
            style="background: linear-gradient(135deg, #4facfe 0%, #43e97b 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 opacity-75 small">Sections</p>
                        <h2 class="mb-0 fw-bold">{{ count($stats['by_section']) }}</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
