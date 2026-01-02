<div>
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 fw-semibold" style="color: #2d3748;">
                    <i class="bi bi-currency-dollar me-2"></i>Rapport de Rentabilité
                </h4>
                <p class="text-muted mb-0">Analysez la rentabilité par source de revenus et identifiez les opportunités
                    d'optimisation</p>
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
                        <small class="text-muted" style="font-size: 0.85rem;">Sélectionnez la période d'analyse</small>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <div class="row g-3">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
            {{-- Ratios Financiers --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-percent text-primary mb-3" style="font-size: 2rem;"></i>
                            <small class="text-muted text-uppercase d-block" style="font-size: 0.75rem;">Marge
                                Bénéficiaire</small>
                            <h2 class="mb-0 mt-2"
                                style="color: {{ $report['financial_ratios']['profit_margin'] >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ number_format($report['financial_ratios']['profit_margin'], 2) }}%
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-graph-down text-danger mb-3" style="font-size: 2rem;"></i>
                            <small class="text-muted text-uppercase d-block" style="font-size: 0.75rem;">Ratio de
                                Dépenses</small>
                            <h2 class="mb-0 mt-2" style="color: #2d3748;">
                                {{ number_format($report['financial_ratios']['expense_ratio'], 2) }}%
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-arrow-return-right text-success mb-3" style="font-size: 2rem;"></i>
                            <small class="text-muted text-uppercase d-block" style="font-size: 0.75rem;">Retour sur
                                Revenus</small>
                            <h2 class="mb-0 mt-2" style="color: #2d3748;">
                                {{ number_format($report['financial_ratios']['return_on_revenue'], 2) }}%
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-speedometer text-info mb-3" style="font-size: 2rem;"></i>
                            <small class="text-muted text-uppercase d-block" style="font-size: 0.75rem;">Seuil de
                                Rentabilité</small>
                            <h2 class="mb-0 mt-2" style="color: #2d3748;">
                                {{ number_format($report['financial_ratios']['break_even_ratio'], 2) }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Marges --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                            <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                                <i class="bi bi-cash-stack me-2"></i>Marge Brute
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <small class="text-muted">Montant</small>
                                <h3 class="mb-0"
                                    style="color: {{ $report['margins']['gross_profit'] >= 0 ? '#10b981' : '#ef4444' }};">
                                    {{ app_format_number($report['margins']['gross_profit'], 2) }} {{ $currency }}
                                </h3>
                            </div>
                            <div>
                                <small class="text-muted">Pourcentage</small>
                                <h3 class="mb-0" style="color: #2d3748;">
                                    {{ number_format($report['margins']['gross_margin_percentage'], 2) }}%
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                            <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                                <i class="bi bi-wallet2 me-2"></i>Marge Nette
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <small class="text-muted">Montant</small>
                                <h3 class="mb-0"
                                    style="color: {{ $report['margins']['net_profit'] >= 0 ? '#10b981' : '#ef4444' }};">
                                    {{ app_format_number($report['margins']['net_profit'], 2) }} {{ $currency }}
                                </h3>
                            </div>
                            <div>
                                <small class="text-muted">Pourcentage</small>
                                <h3 class="mb-0" style="color: #2d3748;">
                                    {{ number_format($report['margins']['net_margin_percentage'], 2) }}%
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Revenus par source --}}
            <div class="card border-0 mb-4" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                    <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                        <i class="bi bi-pie-chart me-2"></i>Revenus par Source
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" style="border-collapse: separate; border-spacing: 0;">
                            <thead style="background: #e2e8f0;">
                                <tr>
                                    <th
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Source</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Montant</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Contribution %</th>
                                    <th class="text-center"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report['profitability_analysis'] as $source)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 0.875rem 1rem; font-weight: 600; color: #2d3748;">
                                            {{ $source['label'] }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: #10b981; font-weight: 500;">
                                            {{ app_format_number($source['amount'], 2) }}
                                        </td>
                                        <td class="text-end" style="padding: 0.875rem 1rem; color: #2d3748;">
                                            {{ number_format($source['contribution_percentage'], 2) }}%
                                        </td>
                                        <td class="text-center" style="padding: 0.875rem 1rem;">
                                            <span class="badge"
                                                style="background: {{ $source['performance'] === 'excellent' ? '#f0fdf4' : ($source['performance'] === 'good' ? '#dbeafe' : ($source['performance'] === 'average' ? '#fef3c7' : '#fef2f2')) }}; color: {{ $source['performance'] === 'excellent' ? '#10b981' : ($source['performance'] === 'good' ? '#3b82f6' : ($source['performance'] === 'average' ? '#f59e0b' : '#ef4444')) }};">
                                                {{ $source['performance'] === 'excellent' ? 'Excellent' : ($source['performance'] === 'good' ? 'Bon' : ($source['performance'] === 'average' ? 'Moyen' : 'Faible')) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Dépenses par catégorie --}}
            <div class="card border-0 mb-4" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                    <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                        <i class="bi bi-list-ul me-2"></i>Dépenses par Catégorie
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" style="border-collapse: separate; border-spacing: 0;">
                            <thead style="background: #e2e8f0;">
                                <tr>
                                    <th
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Catégorie</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Montant</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Part %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report['expenses_by_category'] as $category)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 0.875rem 1rem; font-weight: 600; color: #2d3748;">
                                            {{ $category['label'] }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: #ef4444; font-weight: 500;">
                                            {{ app_format_number($category['amount'], 2) }}
                                        </td>
                                        <td class="text-end" style="padding: 0.875rem 1rem; color: #2d3748;">
                                            {{ number_format($category['percentage'], 2) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Recommandations --}}
            @if (count($report['recommendations']) > 0)
                <div class="card border-0" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                    <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                        <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                            <i class="bi bi-lightbulb me-2"></i>Recommandations d'Optimisation
                        </h6>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        @foreach ($report['recommendations'] as $recommendation)
                            <div class="alert mb-3"
                                style="background: {{ $recommendation['type'] === 'success' ? '#f0fdf4' : ($recommendation['type'] === 'warning' ? '#fef3c7' : ($recommendation['type'] === 'alert' ? '#fef2f2' : '#eff6ff')) }}; border-left: 4px solid {{ $recommendation['type'] === 'success' ? '#10b981' : ($recommendation['type'] === 'warning' ? '#f59e0b' : ($recommendation['type'] === 'alert' ? '#ef4444' : '#3b82f6')) }}; border-radius: 0.5rem;">
                                <div class="d-flex align-items-start">
                                    <i class="bi {{ $recommendation['type'] === 'success' ? 'bi-check-circle' : ($recommendation['type'] === 'warning' ? 'bi-exclamation-triangle' : ($recommendation['type'] === 'alert' ? 'bi-x-circle' : 'bi-info-circle')) }} me-2 mt-1"
                                        style="color: {{ $recommendation['type'] === 'success' ? '#10b981' : ($recommendation['type'] === 'warning' ? '#f59e0b' : ($recommendation['type'] === 'alert' ? '#ef4444' : '#3b82f6')) }}; font-size: 1.25rem;"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold" style="color: #2d3748;">
                                            {{ ucfirst($recommendation['category']) }}
                                        </p>
                                        <p class="mb-0" style="color: #4a5568;">{{ $recommendation['message'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
