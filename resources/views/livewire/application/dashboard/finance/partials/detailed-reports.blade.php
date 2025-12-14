{{-- Filtres de rapports --}}
<div class="card mb-4 border-0" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
    <div class="card-header  border-bottom" style="padding: 1.5rem;">
        <div class="d-flex align-items-center">
            <div class="rounded-circle p-2 me-3" style="background: #f8f9fa; border: 2px solid #e9ecef;">
                <i class="bi bi-sliders text-secondary"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-semibold">Configuration du Rapport</h6>
                <small class="text-muted" style="font-size: 0.85rem;">Sélectionnez vos critères</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            {{-- Type de rapport --}}
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-file-earmark-text"></i> Type de Rapport
                </label>
                <select class="form-select" wire:model.live='report_type'>
                    <option value="daily">Date Spécifique</option>
                    <option value="monthly">Mois Spécifique</option>
                    <option value="predefined">Période Prédéfinie</option>
                    <option value="period">Période Personnalisée</option>
                    <option value="payment">Par Paiement</option>
                </select>
            </div>

            {{-- Filtres conditionnels selon le type --}}
            @if ($report_type === 'daily')
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-calendar-day"></i> Date
                    </label>
                    <input type="date" class="form-control" wire:model.live='report_date'>
                </div>
            @endif

            @if ($report_type === 'monthly')
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-calendar-month"></i> Mois
                    </label>
                    <x-widget.list-month-fr wire:model.live='report_month' :error="'report_month'" />
                </div>
            @endif

            @if ($report_type === 'predefined')
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-clock-history"></i> Période
                    </label>
                    <select class="form-select" wire:model.live='predefined_period'>
                        <option value="week">1 Semaine</option>
                        <option value="2weeks">2 Semaines</option>
                        <option value="1month" selected>1 Mois</option>
                        <option value="3months">3 Mois</option>
                        <option value="6months">6 Mois</option>
                        <option value="9months">9 Mois</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted">
                        <i class="bi bi-calendar-range"></i> Période Calculée
                    </label>
                    <input type="text" class="form-control bg-light"
                        value="{{ date('d/m/Y', strtotime($report_start_date)) }} - {{ date('d/m/Y', strtotime($report_end_date)) }}"
                        readonly>
                </div>
            @endif

            @if ($report_type === 'period')
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-calendar-check"></i> Date Début
                    </label>
                    <input type="date" class="form-control" wire:model.live='report_start_date'>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-calendar-x"></i> Date Fin
                    </label>
                    <input type="date" class="form-control" wire:model.live='report_end_date'>
                </div>
            @endif

            @if ($report_type === 'payment')
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-check-circle"></i> Type Paiement
                    </label>
                    <select class="form-select" wire:model.live='report_payment_type'>
                        <option value="all">Tous</option>
                        <option value="paid">Payés</option>
                        <option value="unpaid">Non payés</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-calendar-month"></i> Mois (optionnel)
                    </label>
                    <x-widget.list-month-fr wire:model.live='report_month' :error="'report_month'" />
                </div>
            @endif

            {{-- Catégorie (toujours visible) --}}
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-tag"></i> Catégorie
                </label>
                <select class="form-select" wire:model.live='report_category_id'>
                    <option value="">Toutes</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Source (toujours visible) --}}
            <div class="col-md-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-box-seam"></i> Source
                </label>
                <select class="form-select" wire:model.live='report_source'>
                    <option value="">Toutes les sources</option>
                    <option value="scolar_fee">Frais Scolaires</option>
                    <option value="other_revenue">Autres Recettes</option>
                    <option value="expense_fee">Dépenses sur Frais</option>
                    <option value="expense_other">Autres Dépenses</option>
                </select>
            </div>
        </div>

        {{-- Indicateur de chargement --}}
        <div wire:loading
            wire:target="report_type,report_date,report_month,predefined_period,report_start_date,report_end_date,report_payment_type,report_category_id,report_source"
            class="mt-3">
            <div class="alert alert-info d-flex align-items-center">
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <div>Génération du rapport en cours...</div>
            </div>
        </div>
    </div>
</div>

{{-- Résultats du rapport --}}
@if (!empty($detailedReport))
    {{-- En-tête du rapport --}}
    <div class="card mb-4 border-0" style=" box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle p-2 me-3" style="background: white; border: 2px solid #e9ecef;">
                            <i class="bi bi-file-earmark-bar-graph text-secondary"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold">
                            Rapport {{ ucfirst($detailedReport['type'] ?? '') }}
                        </h5>
                    </div>
                    <p class="text-muted mb-0">
                        @if ($detailedReport['type'] === 'daily')
                            <i class="bi bi-calendar-day"></i> {{ $detailedReport['formatted_date'] ?? '' }}
                        @elseif($detailedReport['type'] === 'monthly')
                            <i class="bi bi-calendar-month"></i> {{ $detailedReport['month_name'] ?? '' }}
                            {{ $detailedReport['year'] ?? '' }}
                        @elseif($detailedReport['type'] === 'period')
                            @if (isset($detailedReport['predefined_label']) && $detailedReport['predefined_label'])
                                <i class="bi bi-clock-history"></i>
                                <span class="badge bg-info">{{ $detailedReport['predefined_label'] }}</span>
                            @else
                                <i class="bi bi-calendar-range"></i> Période Personnalisée
                            @endif
                            - Du {{ $detailedReport['formatted_start'] ?? '' }} au
                            {{ $detailedReport['formatted_end'] ?? '' }}
                            ({{ $detailedReport['duration_days'] ?? 0 }} jours)
                        @elseif($detailedReport['type'] === 'payment')
                            <i class="bi bi-check-circle"></i> Type:
                            {{ ucfirst($detailedReport['payment_type'] ?? 'all') }}
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-primary px-3 py-2 fs-6">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Cartes statistiques du rapport --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 stat-card"
                style="background: white; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1 text-uppercase"
                                style="font-size: 0.75rem; color: #718096; letter-spacing: 0.5px; font-weight: 600;">
                                Recettes</p>
                        </div>
                        <div class="rounded-circle"
                            style="width: 48px; height: 48px; background: #f0fdf4; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-arrow-up-circle" style="color: #10b981; font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #1a202c;">
                        {{ app_format_number($detailedReport['revenues'] ?? 0, 2) }}
                    </h2>
                    <span class="badge"
                        style="background: #f7fafc; color: #4a5568; font-size: 0.85rem; font-weight: 500; padding: 0.35rem 0.75rem;">
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 stat-card"
                style="background: white; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1 text-uppercase"
                                style="font-size: 0.75rem; color: #718096; letter-spacing: 0.5px; font-weight: 600;">
                                Dépenses</p>
                        </div>
                        <div class="rounded-circle"
                            style="width: 48px; height: 48px; background: #fef2f2; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-arrow-down-circle" style="color: #ef4444; font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #1a202c;">
                        {{ app_format_number($detailedReport['expenses'] ?? 0, 2) }}
                    </h2>
                    <span class="badge"
                        style="background: #f7fafc; color: #4a5568; font-size: 0.85rem; font-weight: 500; padding: 0.35rem 0.75rem;">
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 stat-card"
                style="background: white; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="mb-1 text-uppercase"
                                style="font-size: 0.75rem; color: #718096; letter-spacing: 0.5px; font-weight: 600;">
                                Solde Net</p>
                        </div>
                        <div class="rounded-circle"
                            style="width: 48px; height: 48px; background: #eff6ff; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-wallet2" style="color: #3b82f6; font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <h2 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #1a202c;">
                        {{ app_format_number($detailedReport['balance'] ?? 0, 2) }}
                    </h2>
                    <span class="badge"
                        style="background: #f7fafc; color: #4a5568; font-size: 0.85rem; font-weight: 500; padding: 0.35rem 0.75rem;">
                        {{ $currency }}
                    </span>
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

    {{-- Informations additionnelles selon le type de rapport --}}
    @if ($detailedReport['type'] === 'period' && isset($detailedReport['average_daily_revenue']))
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card border-0" style="background: white; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle me-3"
                                style="width: 40px; height: 40px; background: #f0fdf4; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-graph-up-arrow" style="color: #10b981;"></i>
                            </div>
                            <h6 class="mb-0" style="color: #64748b; font-size: 0.875rem;">Moyenne Journalière -
                                Recettes</h6>
                        </div>
                        <h3 class="mb-0" style="font-weight: 600; color: #1a202c;">
                            {{ app_format_number($detailedReport['average_daily_revenue'], 2) }} <span
                                style="color: #718096; font-size: 1rem;">{{ $currency }}</span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card border-0" style="background: white; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle me-3"
                                style="width: 40px; height: 40px; background: #fef2f2; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-graph-down-arrow" style="color: #ef4444;"></i>
                            </div>
                            <h6 class="mb-0" style="color: #64748b; font-size: 0.875rem;">Moyenne Journalière -
                                Dépenses</h6>
                        </div>
                        <h3 class="mb-0" style="font-weight: 600; color: #1a202c;">
                            {{ app_format_number($detailedReport['average_daily_expense'], 2) }} <span
                                style="color: #718096; font-size: 1rem;">{{ $currency }}</span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($detailedReport['type'] === 'payment' && isset($detailedReport['payment_rate']))
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, transparent 100%); border-left: 4px solid #198754 !important;">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle mx-auto mb-3"
                            style="width: 60px; height: 60px; background: rgba(25, 135, 84, 0.15); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-check-circle-fill text-success fs-3"></i>
                        </div>
                        <h6 class="text-muted text-uppercase"
                            style="font-size: 0.75rem; letter-spacing: 1px; font-weight: 600;">Payés</h6>
                        <h3 class="fw-bold text-success mb-0">
                            {{ app_format_number($detailedReport['paid_revenues'] ?? 0, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, transparent 100%); border-left: 4px solid #ffc107 !important;">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle mx-auto mb-3"
                            style="width: 60px; height: 60px; background: rgba(255, 193, 7, 0.15); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-clock-history text-warning fs-3"></i>
                        </div>
                        <h6 class="text-muted text-uppercase"
                            style="font-size: 0.75rem; letter-spacing: 1px; font-weight: 600;">Non Payés</h6>
                        <h3 class="fw-bold text-warning mb-0">
                            {{ app_format_number($detailedReport['unpaid_revenues'] ?? 0, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, transparent 100%); border-left: 4px solid #0d6efd !important;">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle mx-auto mb-3"
                            style="width: 60px; height: 60px; background: rgba(13, 110, 253, 0.15); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-percent text-primary fs-3"></i>
                        </div>
                        <h6 class="text-muted text-uppercase"
                            style="font-size: 0.75rem; letter-spacing: 1px; font-weight: 600;">Taux de Paiement</h6>
                        <h3 class="fw-bold text-primary mb-0">
                            {{ $detailedReport['payment_rate'] ?? 0 }}%
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Ventilation détaillée par devise --}}
    @if (isset($detailedReport['details']))
        <div class="card mb-4 border-0" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
            <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle me-3"
                        style="width: 40px; height: 40px; background: #f8f9fa; border: 2px solid #e9ecef; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-cash-coin text-secondary"></i>
                    </div>
                    <h6 class="mb-0 fw-semibold" style="color: #2d3748;">Ventilation par Devise</h6>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0" style="border-collapse: separate; border-spacing: 0;">
                        <thead style="background: #e2e8f0;">
                            <tr>
                                <th
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Devise</th>
                                <th class="text-end"
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Recettes</th>
                                <th class="text-end"
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Dépenses</th>
                                <th class="text-end"
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 1rem; font-weight: 600; color: #2d3748;"><i
                                        class="bi bi-currency-dollar" style="color: #10b981;"></i> USD</td>
                                <td class="text-end" style="padding: 1rem; color: #10b981; font-weight: 500;">
                                    {{ app_format_number($detailedReport['details']['revenues']['usd'] ?? 0, 2) }}
                                </td>
                                <td class="text-end" style="padding: 1rem; color: #ef4444; font-weight: 500;">
                                    {{ app_format_number($detailedReport['details']['expenses']['usd'] ?? 0, 2) }}
                                </td>
                                <td class="text-end" style="padding: 1rem; color: #2d3748; font-weight: 700;">
                                    {{ app_format_number($detailedReport['details']['balance']['usd'] ?? 0, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 1rem; font-weight: 600; color: #2d3748;"><i class="bi bi-cash"
                                        style="color: #10b981;"></i> CDF</td>
                                <td class="text-end" style="padding: 1rem; color: #10b981; font-weight: 500;">
                                    {{ app_format_number($detailedReport['details']['revenues']['cdf'] ?? 0, 2) }}
                                </td>
                                <td class="text-end" style="padding: 1rem; color: #ef4444; font-weight: 500;">
                                    {{ app_format_number($detailedReport['details']['expenses']['cdf'] ?? 0, 2) }}
                                </td>
                                <td class="text-end" style="padding: 1rem; color: #2d3748; font-weight: 700;">
                                    {{ app_format_number($detailedReport['details']['balance']['cdf'] ?? 0, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Ventilation quotidienne (pour rapport mensuel) --}}
    @if (
        $detailedReport['type'] === 'monthly' &&
            isset($detailedReport['daily_breakdown']) &&
            count($detailedReport['daily_breakdown']) > 0)
        <div class="card mb-4 border-0" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
            <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle me-3"
                        style="width: 40px; height: 40px; background: #eff6ff; border: 2px solid #bfdbfe; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-calendar-week" style="color: #3b82f6;"></i>
                    </div>
                    <h6 class="mb-0 fw-semibold" style="color: #2d3748;">Ventilation Quotidienne</h6>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm mb-0" style="border-collapse: separate; border-spacing: 0;">
                        <thead class="sticky-top" style="background: #e2e8f0;">
                            <tr>
                                <th
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Date</th>
                                <th class="text-end"
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Recettes</th>
                                <th class="text-end"
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Dépenses</th>
                                <th class="text-end"
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailedReport['daily_breakdown'] as $day)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 0.875rem 1rem;">
                                        <span class="badge"
                                            style="background: #f1f5f9; color: #64748b; font-weight: 500; padding: 0.25rem 0.5rem;">{{ $day['day'] }}</span>
                                        <span
                                            style="color: #2d3748; margin-left: 0.5rem;">{{ $day['formatted_date'] }}</span>
                                    </td>
                                    <td class="text-end"
                                        style="padding: 0.875rem 1rem; color: #10b981; font-weight: 500;">
                                        {{ app_format_number($day['revenues'], 2) }}
                                    </td>
                                    <td class="text-end"
                                        style="padding: 0.875rem 1rem; color: #ef4444; font-weight: 500;">
                                        {{ app_format_number($day['expenses'], 2) }}
                                    </td>
                                    <td class="text-end"
                                        style="padding: 0.875rem 1rem; color: {{ $day['balance'] >= 0 ? '#2d3748' : '#f59e0b' }}; font-weight: 600;">
                                        {{ app_format_number($day['balance'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Ventilation mensuelle (pour rapport périodique) --}}
    @if (
        $detailedReport['type'] === 'period' &&
            isset($detailedReport['monthly_breakdown']) &&
            count($detailedReport['monthly_breakdown']) > 0)
        <div class="card mb-4 border-0" style="box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
            <div class="card-header bg-white border-bottom" style="padding: 1.25rem;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle me-3"
                        style="width: 40px; height: 40px; background: #fef3c7; border: 2px solid #fde68a; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-calendar3" style="color: #f59e0b;"></i>
                    </div>
                    <h6 class="mb-0 fw-semibold" style="color: #2d3748;">Ventilation Mensuelle</h6>
                </div>
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
                                    Recettes</th>
                                <th class="text-end"
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Dépenses</th>
                                <th class="text-end"
                                    style="padding: 0.875rem 1rem; color: #475569; font-weight: 600; font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e0;">
                                    Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailedReport['monthly_breakdown'] as $month)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 0.875rem 1rem; font-weight: 600; color: #2d3748;">
                                        {{ $month['month_name'] }} {{ $month['year'] }}
                                    </td>
                                    <td class="text-end"
                                        style="padding: 0.875rem 1rem; color: #10b981; font-weight: 500;">
                                        {{ app_format_number($month['revenues'], 2) }}
                                    </td>
                                    <td class="text-end"
                                        style="padding: 0.875rem 1rem; color: #ef4444; font-weight: 500;">
                                        {{ app_format_number($month['expenses'], 2) }}
                                    </td>
                                    <td class="text-end"
                                        style="padding: 0.875rem 1rem; color: {{ $month['balance'] >= 0 ? '#2d3748' : '#f59e0b' }}; font-weight: 600;">
                                        {{ app_format_number($month['balance'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@else
    {{-- Message si aucun rapport généré --}}
    <div class="card border-0" style="background: white; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                    style="width: 80px; height: 80px; background: #f8f9fa; border: 2px solid #e9ecef;">
                    <i class="bi bi-file-earmark-bar-graph" style="font-size: 2.5rem; color: #94a3b8;"></i>
                </div>
            </div>
            <h5 class="mb-2" style="color: #2d3748; font-weight: 600;">Aucun rapport généré</h5>
            <p class="mb-0" style="color: #64748b;">Sélectionnez les filtres ci-dessus pour générer votre rapport
            </p>
        </div>
    </div>
@endif
