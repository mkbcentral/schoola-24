<div>
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 fw-semibold" style="color: #2d3748;">
                    <i class="bi bi-graph-up-arrow me-2"></i>Rapport de Prévisions
                </h4>
                <p class="text-muted mb-0">Anticipez vos performances financières futures basées sur les tendances
                    historiques</p>
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
                        <small class="text-muted" style="font-size: 0.85rem;">Définissez la période d'analyse et
                            l'horizon de prévision</small>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color: #4a5568;">Période d'analyse (base)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small">Date début</label>
                                <input type="date" wire:model="base_period_start" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Date fin</label>
                                <input type="date" wire:model="base_period_end" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color: #4a5568;">Horizon de prévision</label>
                        <select wire:model="forecast_months" class="form-select">
                            <option value="3">3 mois</option>
                            <option value="6">6 mois</option>
                            <option value="9">9 mois</option>
                            <option value="12">12 mois</option>
                            <option value="18">18 mois</option>
                            <option value="24">24 mois</option>
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
                            <i class="bi bi-play-fill"></i> Générer les prévisions
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
            {{-- Tendances --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                            <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                                <i class="bi bi-arrow-up-circle me-2" style="color: #10b981;"></i>Tendances des Recettes
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <small class="text-muted">Moyenne historique</small>
                                <h4 class="mb-0" style="color: #2d3748;">
                                    {{ app_format_number($report['trends']['revenues']['average'], 2) }}
                                    {{ $currency }}
                                </h4>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Taux de croissance</small>
                                <h4 class="mb-0"
                                    style="color: {{ $report['trends']['revenues']['growth_rate'] >= 0 ? '#10b981' : '#ef4444' }};">
                                    {{ number_format($report['trends']['revenues']['growth_rate'], 2) }}%
                                </h4>
                            </div>
                            <div>
                                <small class="text-muted">Tendance</small>
                                <span class="badge"
                                    style="background: {{ $report['trends']['revenues']['trend'] === 'increasing' ? '#f0fdf4' : ($report['trends']['revenues']['trend'] === 'decreasing' ? '#fef2f2' : '#f7fafc') }}; color: {{ $report['trends']['revenues']['trend'] === 'increasing' ? '#10b981' : ($report['trends']['revenues']['trend'] === 'decreasing' ? '#ef4444' : '#64748b') }}; padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    <i
                                        class="bi {{ $report['trends']['revenues']['trend'] === 'increasing' ? 'bi-graph-up' : ($report['trends']['revenues']['trend'] === 'decreasing' ? 'bi-graph-down' : 'bi-dash') }}"></i>
                                    {{ $report['trends']['revenues']['trend'] === 'increasing' ? 'En hausse' : ($report['trends']['revenues']['trend'] === 'decreasing' ? 'En baisse' : 'Stable') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 h-100" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                        <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                            <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                                <i class="bi bi-arrow-down-circle me-2" style="color: #ef4444;"></i>Tendances des
                                Dépenses
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <small class="text-muted">Moyenne historique</small>
                                <h4 class="mb-0" style="color: #2d3748;">
                                    {{ app_format_number($report['trends']['expenses']['average'], 2) }}
                                    {{ $currency }}
                                </h4>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Taux de croissance</small>
                                <h4 class="mb-0"
                                    style="color: {{ $report['trends']['expenses']['growth_rate'] >= 0 ? '#ef4444' : '#10b981' }};">
                                    {{ number_format($report['trends']['expenses']['growth_rate'], 2) }}%
                                </h4>
                            </div>
                            <div>
                                <small class="text-muted">Tendance</small>
                                <span class="badge"
                                    style="background: {{ $report['trends']['expenses']['trend'] === 'increasing' ? '#fef2f2' : ($report['trends']['expenses']['trend'] === 'decreasing' ? '#f0fdf4' : '#f7fafc') }}; color: {{ $report['trends']['expenses']['trend'] === 'increasing' ? '#ef4444' : ($report['trends']['expenses']['trend'] === 'decreasing' ? '#10b981' : '#64748b') }}; padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    <i
                                        class="bi {{ $report['trends']['expenses']['trend'] === 'increasing' ? 'bi-graph-up' : ($report['trends']['expenses']['trend'] === 'decreasing' ? 'bi-graph-down' : 'bi-dash') }}"></i>
                                    {{ $report['trends']['expenses']['trend'] === 'increasing' ? 'En hausse' : ($report['trends']['expenses']['trend'] === 'decreasing' ? 'En baisse' : 'Stable') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Prévisions --}}
            <div class="card border-0 mb-4" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                    <h6 class="mb-0 fw-semibold" style="color: #2d3748;">
                        <i class="bi bi-calendar-range me-2"></i>Prévisions Mensuelles
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" style="border-collapse: separate; border-spacing: 0;">
                            <thead style="background: #e2e8f0;">
                                <tr>
                                    <th
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Mois</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Recettes Prévues</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Dépenses Prévues</th>
                                    <th class="text-end"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Solde Prévu</th>
                                    <th class="text-center"
                                        style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                        Confiance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report['forecasts'] as $forecast)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 0.875rem 1rem; font-weight: 600; color: #2d3748;">
                                            {{ $forecast['month_name'] }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: #10b981; font-weight: 500;">
                                            {{ app_format_number($forecast['forecasted_revenues'], 2) }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: #ef4444; font-weight: 500;">
                                            {{ app_format_number($forecast['forecasted_expenses'], 2) }}
                                        </td>
                                        <td class="text-end"
                                            style="padding: 0.875rem 1rem; color: {{ $forecast['forecasted_balance'] >= 0 ? '#2d3748' : '#f59e0b' }}; font-weight: 600;">
                                            {{ app_format_number($forecast['forecasted_balance'], 2) }}
                                        </td>
                                        <td class="text-center" style="padding: 0.875rem 1rem;">
                                            <span class="badge"
                                                style="background: {{ $forecast['confidence'] === 'high' ? '#f0fdf4' : ($forecast['confidence'] === 'medium' ? '#fef3c7' : '#fef2f2') }}; color: {{ $forecast['confidence'] === 'high' ? '#10b981' : ($forecast['confidence'] === 'medium' ? '#f59e0b' : '#ef4444') }};">
                                                {{ $forecast['confidence'] === 'high' ? 'Haute' : ($forecast['confidence'] === 'medium' ? 'Moyenne' : 'Faible') }}
                                            </span>
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
                            <i class="bi bi-lightbulb me-2"></i>Recommandations
                        </h6>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        @foreach ($report['recommendations'] as $recommendation)
                            <div class="alert mb-3"
                                style="background: {{ $recommendation['type'] === 'warning' ? '#fef3c7' : ($recommendation['type'] === 'alert' ? '#fef2f2' : '#eff6ff') }}; border-left: 4px solid {{ $recommendation['type'] === 'warning' ? '#f59e0b' : ($recommendation['type'] === 'alert' ? '#ef4444' : '#3b82f6') }}; border-radius: 0.5rem;">
                                <div class="d-flex align-items-start">
                                    <i class="bi {{ $recommendation['type'] === 'warning' ? 'bi-exclamation-triangle' : ($recommendation['type'] === 'alert' ? 'bi-exclamation-circle' : 'bi-info-circle') }} me-2 mt-1"
                                        style="color: {{ $recommendation['type'] === 'warning' ? '#f59e0b' : ($recommendation['type'] === 'alert' ? '#ef4444' : '#3b82f6') }}; font-size: 1.25rem;"></i>
                                    <p class="mb-0" style="color: #2d3748;">{{ $recommendation['message'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
