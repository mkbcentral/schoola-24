<div>
    <div class="container-fluid py-4">
        {{-- Messages de feedback --}}
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 fw-semibold" style="color: #2d3748;">
                    <i class="bi bi-bar-chart-line me-2"></i>Rapport de Comparaison
                </h4>
                <p class="text-muted mb-0">Comparez les performances financières entre deux périodes</p>
            </div>
        </div>

        {{-- Filtres --}}
        <div class="card border-0 mb-4" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
            <div class="card-header bg-white border-bottom" style="padding: 1.5rem;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-3" style="background: #f8f9fa; border: 2px solid #e9ecef;">
                        <i class="bi bi-sliders text-secondary"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-semibold" style="color: #2d3748;">Configuration</h5>
                        <small class="text-muted" style="font-size: 0.85rem;">Sélectionnez les périodes à
                            comparer</small>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #4a5568;">Type de comparaison</label>
                        <select wire:model.live="comparison_type" class="form-select">
                            @foreach ($comparisonTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #4a5568;">Devise</label>
                        <select wire:model="currency" class="form-select">
                            <option value="USD">USD</option>
                            <option value="CDF">CDF</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background: #f8f9fa; border: 1px solid #e9ecef;">
                            <h6 class="fw-semibold mb-3" style="color: #2d3748;">Période 1</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small">Date début</label>
                                    <input type="date" wire:model="period_1_start"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small">Date fin</label>
                                    <input type="date" wire:model="period_1_end"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background: #f8f9fa; border: 1px solid #e9ecef;">
                            <h6 class="fw-semibold mb-3" style="color: #2d3748;">Période 2</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small">Date début</label>
                                    <input type="date" wire:model="period_2_start"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small">Date fin</label>
                                    <input type="date" wire:model="period_2_end"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button wire:click="generateReport" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="generateReport">
                            <i class="bi bi-play-fill"></i> Générer le rapport
                        </span>
                        <span wire:loading wire:target="generateReport">
                            <span class="spinner-border spinner-border-sm me-1"></span> Génération...
                        </span>
                    </button>
                    @if ($report)
                        <button wire:click="resetReport" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Réinitialiser
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Résultats --}}
        @if ($report)
            <div class="row g-3 mt-4">
                {{-- Période 1 --}}
                <div class="col-md-6">
                    <div class="card border-0" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 fw-semibold">{{ $report['period_1']['label'] }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Recettes</small>
                                <h4 class="mb-0" style="color: #10b981;">
                                    {{ app_format_number($report['period_1']['revenues'], 2) }} {{ $currency }}
                                </h4>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Dépenses</small>
                                <h4 class="mb-0" style="color: #ef4444;">
                                    {{ app_format_number($report['period_1']['expenses'], 2) }} {{ $currency }}
                                </h4>
                            </div>
                            <div>
                                <small class="text-muted">Solde</small>
                                <h4 class="mb-0" style="color: #2d3748;">
                                    {{ app_format_number($report['period_1']['balance'], 2) }} {{ $currency }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Période 2 --}}
                <div class="col-md-6">
                    <div class="card border-0" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 fw-semibold">{{ $report['period_2']['label'] }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Recettes</small>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0" style="color: #10b981;">
                                        {{ app_format_number($report['period_2']['revenues'], 2) }}
                                        {{ $currency }}
                                    </h4>
                                    <span class="badge"
                                        style="background: {{ $report['variations']['revenues']['trend'] === 'up' ? '#f0fdf4' : '#fef2f2' }}; color: {{ $report['variations']['revenues']['trend'] === 'up' ? '#10b981' : '#ef4444' }};">
                                        <i
                                            class="bi {{ $report['variations']['revenues']['trend'] === 'up' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        {{ abs($report['variations']['revenues']['percentage']) }}%
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Dépenses</small>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0" style="color: #ef4444;">
                                        {{ app_format_number($report['period_2']['expenses'], 2) }}
                                        {{ $currency }}
                                    </h4>
                                    <span class="badge"
                                        style="background: {{ $report['variations']['expenses']['trend'] === 'up' ? '#fef2f2' : '#f0fdf4' }}; color: {{ $report['variations']['expenses']['trend'] === 'up' ? '#ef4444' : '#10b981' }};">
                                        <i
                                            class="bi {{ $report['variations']['expenses']['trend'] === 'up' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        {{ abs($report['variations']['expenses']['percentage']) }}%
                                    </span>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted">Solde</small>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0" style="color: #2d3748;">
                                        {{ app_format_number($report['period_2']['balance'], 2) }} {{ $currency }}
                                    </h4>
                                    <span class="badge"
                                        style="background: {{ $report['variations']['balance']['trend'] === 'up' ? '#f0fdf4' : '#fef2f2' }}; color: {{ $report['variations']['balance']['trend'] === 'up' ? '#10b981' : '#ef4444' }};">
                                        <i
                                            class="bi {{ $report['variations']['balance']['trend'] === 'up' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        {{ abs($report['variations']['balance']['percentage']) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
