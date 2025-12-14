{{-- Filtres de rapports --}}
<div class="report-config-card mb-4">
    <div class="report-config-card__header">
        <div class="d-flex align-items-center">
            <div class="report-config-card__icon">
                <i class="bi bi-sliders"></i>
            </div>
            <div>
                <h6 class="report-config-card__title mb-0">Configuration du Rapport</h6>
                <small class="report-config-card__subtitle">Sélectionnez vos critères</small>
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
    <div class="report-header-card mb-4">
        <div class="report-header-card__body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <div class="report-header-card__icon">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                        </div>
                        <h5 class="report-header-card__title mb-0">
                            Rapport {{ ucfirst($detailedReport['type'] ?? '') }}
                        </h5>
                    </div>
                    <p class="report-header-card__info">
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
            <x-dashboard.stat-card-modern title="Recettes" :value="$detailedReport['revenues'] ?? 0" :currency="$currency" icon="arrow-up-circle"
                iconBg="success" />
        </div>

        <div class="col-md-4 mb-3">
            <x-dashboard.stat-card-modern title="Dépenses" :value="$detailedReport['expenses'] ?? 0" :currency="$currency"
                icon="arrow-down-circle" iconBg="danger" />
        </div>

        <div class="col-md-4 mb-3">
            <x-dashboard.stat-card-modern title="Solde Net" :value="$detailedReport['balance'] ?? 0" :currency="$currency" icon="wallet2"
                iconBg="primary" />
        </div>
    </div>

    {{-- Informations additionnelles selon le type de rapport --}}
    @if ($detailedReport['type'] === 'period' && isset($detailedReport['average_daily_revenue']))
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <x-dashboard.average-card title="Moyenne Journalière - Recettes" :value="$detailedReport['average_daily_revenue']" :currency="$currency"
                    icon="graph-up-arrow" iconBg="success" />
            </div>
            <div class="col-md-6 mb-3">
                <x-dashboard.average-card title="Moyenne Journalière - Dépenses" :value="$detailedReport['average_daily_expense']" :currency="$currency"
                    icon="graph-down-arrow" iconBg="danger" />
            </div>
        </div>
    @endif

    @if ($detailedReport['type'] === 'payment' && isset($detailedReport['payment_rate']))
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <x-dashboard.payment-card title="Payés" :value="$detailedReport['paid_revenues'] ?? 0" icon="check-circle-fill"
                    type="success" />
            </div>
            <div class="col-md-4 mb-3">
                <x-dashboard.payment-card title="Non Payés" :value="$detailedReport['unpaid_revenues'] ?? 0" icon="clock-history" type="warning" />
            </div>
            <div class="col-md-4 mb-3">
                <x-dashboard.payment-card title="Taux de Paiement" :value="($detailedReport['payment_rate'] ?? 0) . '%'" icon="percent"
                    type="primary" />
            </div>
        </div>
    @endif

    {{-- Ventilation détaillée par devise --}}
    @if (isset($detailedReport['details']))
        <x-dashboard.breakdown-table title="Ventilation par Devise" icon="cash-coin" iconBg="light"
            :headers="[
                ['label' => 'Devise', 'class' => ''],
                ['label' => 'Recettes', 'class' => 'text-end'],
                ['label' => 'Dépenses', 'class' => 'text-end'],
                ['label' => 'Solde', 'class' => 'text-end'],
            ]" class="mb-4">
            <tr>
                <td class="fw-bold">
                    <i class="bi bi-currency-dollar text-success"></i> USD
                </td>
                <td class="text-end text-success">
                    {{ app_format_number($detailedReport['details']['revenues']['usd'] ?? 0, 2) }}
                </td>
                <td class="text-end text-danger">
                    {{ app_format_number($detailedReport['details']['expenses']['usd'] ?? 0, 2) }}
                </td>
                <td class="text-end fw-bold">
                    {{ app_format_number($detailedReport['details']['balance']['usd'] ?? 0, 2) }}
                </td>
            </tr>
            <tr>
                <td class="fw-bold">
                    <i class="bi bi-cash text-success"></i> CDF
                </td>
                <td class="text-end text-success">
                    {{ app_format_number($detailedReport['details']['revenues']['cdf'] ?? 0, 2) }}
                </td>
                <td class="text-end text-danger">
                    {{ app_format_number($detailedReport['details']['expenses']['cdf'] ?? 0, 2) }}
                </td>
                <td class="text-end fw-bold">
                    {{ app_format_number($detailedReport['details']['balance']['cdf'] ?? 0, 2) }}
                </td>
            </tr>
        </x-dashboard.breakdown-table>
    @endif

    {{-- Ventilation quotidienne (pour rapport mensuel) --}}
    @if (
        $detailedReport['type'] === 'monthly' &&
            isset($detailedReport['daily_breakdown']) &&
            count($detailedReport['daily_breakdown']) > 0)
        <x-dashboard.breakdown-table title="Ventilation Quotidienne" icon="calendar-week" iconBg="primary"
            maxHeight="400px" :headers="[
                ['label' => 'Date', 'class' => ''],
                ['label' => 'Recettes', 'class' => 'text-end'],
                ['label' => 'Dépenses', 'class' => 'text-end'],
                ['label' => 'Solde', 'class' => 'text-end'],
            ]" class="mb-4">
            @foreach ($detailedReport['daily_breakdown'] as $day)
                <tr>
                    <td>
                        <span class="report-day-badge">{{ $day['day'] }}</span>
                        <span class="ms-2">{{ $day['formatted_date'] }}</span>
                    </td>
                    <td class="text-end text-success">
                        {{ app_format_number($day['revenues'], 2) }}
                    </td>
                    <td class="text-end text-danger">
                        {{ app_format_number($day['expenses'], 2) }}
                    </td>
                    <td class="text-end fw-bold {{ $day['balance'] >= 0 ? '' : 'text-warning' }}">
                        {{ app_format_number($day['balance'], 2) }}
                    </td>
                </tr>
            @endforeach
        </x-dashboard.breakdown-table>
    @endif

    {{-- Ventilation mensuelle (pour rapport périodique) --}}
    @if (
        $detailedReport['type'] === 'period' &&
            isset($detailedReport['monthly_breakdown']) &&
            count($detailedReport['monthly_breakdown']) > 0)
        <x-dashboard.breakdown-table title="Ventilation Mensuelle" icon="calendar3" iconBg="warning"
            :headers="[
                ['label' => 'Mois', 'class' => ''],
                ['label' => 'Recettes', 'class' => 'text-end'],
                ['label' => 'Dépenses', 'class' => 'text-end'],
                ['label' => 'Solde', 'class' => 'text-end'],
            ]" class="mb-4">
            @foreach ($detailedReport['monthly_breakdown'] as $month)
                <tr>
                    <td class="fw-bold">
                        {{ $month['month_name'] }} {{ $month['year'] }}
                    </td>
                    <td class="text-end text-success">
                        {{ app_format_number($month['revenues'], 2) }}
                    </td>
                    <td class="text-end text-danger">
                        {{ app_format_number($month['expenses'], 2) }}
                    </td>
                    <td class="text-end fw-bold {{ $month['balance'] >= 0 ? '' : 'text-warning' }}">
                        {{ app_format_number($month['balance'], 2) }}
                    </td>
                </tr>
            @endforeach
        </x-dashboard.breakdown-table>
    @endif
@else
    {{-- Message si aucun rapport généré --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-light"
                    style="width: 80px; height: 80px; border: 2px solid #e9ecef;">
                    <i class="bi bi-file-earmark-bar-graph text-muted" style="font-size: 2.5rem;"></i>
                </div>
            </div>
            <h5 class="mb-2 fw-semibold">Aucun rapport généré</h5>
            <p class="text-muted mb-0">Sélectionnez les filtres ci-dessus pour générer votre rapport</p>
        </div>
    </div>
@endif
