<div>
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 fw-semibold" style="color: #2d3748;">
                    <i class="bi bi-cash-stack me-2"></i>Rapport de Trésorerie
                </h4>
                <p class="text-muted mb-0">Analysez vos flux de trésorerie et anticipez vos besoins en liquidités</p>
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
                        <small class="text-muted" style="font-size: 0.85rem;">Sélectionnez la période et la granularité
                            d'analyse</small>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color: #4a5568;">Période d'analyse</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small">Date début</label>
                                <input type="date" wire:model="start_date" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Date fin</label>
                                <input type="date" wire:model="end_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color: #4a5568;">Granularité</label>
                        <select wire:model="granularity" class="form-select">
                            <option value="daily">Quotidien</option>
                            <option value="weekly">Hebdomadaire</option>
                            <option value="monthly">Mensuel</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color: #4a5568;">Devise</label>
                        <select wire:model="currency" class="form-select">
                            <option value="USD">USD</option>
                            <option value="CDF">CDF</option>
                        </select>
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
            {{-- Soldes --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-body p-4">
                            <small class="text-muted text-uppercase" style="font-size: 0.75rem;">Solde
                                d'ouverture</small>
                            <h3 class="mb-0 mt-2" style="color: #2d3748;">
                                {{ app_format_number($report['balances']['opening'], 2) }}
                            </h3>
                            <small class="text-muted">{{ $currency }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-body p-4">
                            <small class="text-muted text-uppercase" style="font-size: 0.75rem;">Solde de
                                clôture</small>
                            <h3 class="mb-0 mt-2"
                                style="color: {{ $report['balances']['closing'] >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ app_format_number($report['balances']['closing'], 2) }}
                            </h3>
                            <small class="text-muted">{{ $currency }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-body p-4">
                            <small class="text-muted text-uppercase" style="font-size: 0.75rem;">Variation</small>
                            <h3 class="mb-0 mt-2"
                                style="color: {{ $report['balances']['variation'] >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ app_format_number($report['balances']['variation'], 2) }}
                            </h3>
                            <span class="badge mt-1"
                                style="background: {{ $report['balances']['variation'] >= 0 ? '#f0fdf4' : '#fef2f2' }}; color: {{ $report['balances']['variation'] >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ number_format(abs($report['balances']['variation_percentage']), 2) }}%
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-body p-4">
                            <small class="text-muted text-uppercase" style="font-size: 0.75rem;">Solde moyen</small>
                            <h3 class="mb-0 mt-2" style="color: #2d3748;">
                                {{ app_format_number($report['summary']['average_daily_balance'], 2) }}
                            </h3>
                            <small class="text-muted">{{ $currency }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flux de trésorerie --}}
            <div class="card border-0 mb-4" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                    <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                        <i class="bi bi-graph-up me-2"></i>Flux de Trésorerie
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-sm mb-0" style="border-collapse: separate; border-spacing: 0;">
                            <thead class="sticky-top" style="background: #e2e8f0;">
                                <tr>
                                    <th
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Date</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Entrées</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Sorties</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Flux Net</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Solde</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report['cash_flows'] as $flow)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 0.875rem 1rem; font-weight: 600; color: #2d3748;">
                                            {{ $flow['label'] }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: #10b981; font-weight: 500;">
                                            {{ app_format_number($flow['inflows'], 2) }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: #ef4444; font-weight: 500;">
                                            {{ app_format_number($flow['outflows'], 2) }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: {{ $flow['net_flow'] >= 0 ? '#10b981' : '#ef4444' }}; font-weight: 600;">
                                            {{ app_format_number($flow['net_flow'], 2) }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: {{ $flow['balance'] >= 0 ? '#2d3748' : '#f59e0b' }}; font-weight: 700;">
                                            {{ app_format_number($flow['balance'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Prévisions court terme --}}
            @if (isset($report['forecast']) && count($report['forecast']) > 0)
                <div class="card border-0 mb-4" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                    <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                        <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                            <i class="bi bi-clock-history me-2"></i>Prévisions Court Terme (7 jours)
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0" style="border-collapse: separate; border-spacing: 0;">
                                <thead style="background: #e2e8f0;">
                                    <tr>
                                        <th
                                            style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                            Date</th>
                                        <th class="text-end"
                                            style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                            Entrées Prévues</th>
                                        <th class="text-end"
                                            style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                            Sorties Prévues</th>
                                        <th class="text-end"
                                            style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                            Solde Prévu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($report['forecast'] as $prediction)
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 0.875rem 1rem; font-weight: 600; color: #2d3748;">
                                                {{ $prediction['label'] }}
                                            </td>
                                            <td class="text-end"
                                                style="padding: 0.875rem 1rem; color: #10b981; font-weight: 500;">
                                                {{ app_format_number($prediction['predicted_inflows'], 2) }}
                                            </td>
                                            <td class="text-end"
                                                style="padding: 0.875rem 1rem; color: #ef4444; font-weight: 500;">
                                                {{ app_format_number($prediction['predicted_outflows'], 2) }}
                                            </td>
                                            <td class="text-end"
                                                style="padding: 0.875rem 1rem; color: {{ $prediction['predicted_balance'] >= 0 ? '#2d3748' : '#f59e0b' }}; font-weight: 600;">
                                                {{ app_format_number($prediction['predicted_balance'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Alertes --}}
            @if (count($report['alerts']) > 0)
                <div class="card border-0" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                    <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                        <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                            <i class="bi bi-exclamation-triangle me-2"></i>Alertes de Trésorerie
                        </h6>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        @foreach ($report['alerts'] as $alert)
                            <div class="alert mb-3"
                                style="background: {{ $alert['level'] === 'critical' ? '#fef2f2' : ($alert['level'] === 'warning' ? '#fef3c7' : '#eff6ff') }}; border-left: 4px solid {{ $alert['level'] === 'critical' ? '#ef4444' : ($alert['level'] === 'warning' ? '#f59e0b' : '#3b82f6') }}; border-radius: 0.5rem;">
                                <div class="d-flex align-items-start">
                                    <i class="bi {{ $alert['level'] === 'critical' ? 'bi-x-circle' : ($alert['level'] === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle') }} me-2 mt-1"
                                        style="color: {{ $alert['level'] === 'critical' ? '#ef4444' : ($alert['level'] === 'warning' ? '#f59e0b' : '#3b82f6') }}; font-size: 1.25rem;"></i>
                                    <p class="mb-0" style="color: #2d3748;">{{ $alert['message'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
