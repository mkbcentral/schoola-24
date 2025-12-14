{{-- Sélecteur de catégorie --}}
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <label for="category_fee_filter" class="form-label fw-semibold mb-2">
                    <i class="bi bi-tag-fill me-1"></i>
                    Catégorie de Frais
                </label>
                <select wire:model.live="category_fee_id_filter" id="category_fee_filter" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }} ({{ $category->currency }})
                        </option>
                    @endforeach
                </select>
                @if ($category_fee_id_filter)
                    @php
                        $selectedCategory = collect($categories)->firstWhere('id', (int) $category_fee_id_filter);
                    @endphp
                    @if ($selectedCategory)
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Affichage des données pour: <strong>{{ $selectedCategory->name }}</strong>
                        </small>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Cartes de statistiques --}}
<div class="row mb-4">
    {{-- Recettes --}}
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white h-100 shadow-lg border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <p class="text-white text-opacity-75 text-uppercase mb-1 fw-semibold"
                            style="font-size: 0.85rem; letter-spacing: 0.5px;">
                            Recettes Globales
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 shadow-sm">
                        <i class="bi bi-cash-coin fs-3 text-white"></i>
                    </div>
                </div>
                <div>
                    <h1 class="mb-2 fw-bold text-white" style="font-size: 2.5rem;">
                        {{ app_format_number($total_revenue, 2) }}
                    </h1>
                    <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 fs-6">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency === 'USD' ? '$' : 'FC' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Dépenses --}}
    <div class="col-md-4 mb-3">
        <div class="card bg-danger text-white h-100 shadow-lg border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <p class="text-white text-opacity-75 text-uppercase mb-1 fw-semibold"
                            style="font-size: 0.85rem; letter-spacing: 0.5px;">
                            Dépenses Globales
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 shadow-sm">
                        <i class="bi bi-wallet2 fs-3 text-white"></i>
                    </div>
                </div>
                <div>
                    <h1 class="mb-2 fw-bold text-white" style="font-size: 2.5rem;">
                        {{ app_format_number($total_expense, 2) }}
                    </h1>
                    <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 fs-6">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency === 'USD' ? '$' : 'FC' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Solde --}}
    <div class="col-md-4 mb-3">
        <div class="card {{ $balance >= 0 ? 'bg-primary' : 'bg-warning' }} text-white h-100 shadow-lg border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <p class="text-white text-opacity-75 text-uppercase mb-1 fw-semibold"
                            style="font-size: 0.85rem; letter-spacing: 0.5px;">
                            Solde Net
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 shadow-sm">
                        <i class="bi bi-calculator fs-3 text-white"></i>
                    </div>
                </div>
                <div>
                    <h1 class="mb-2 fw-bold text-white" style="font-size: 2.5rem;">
                        {{ app_format_number($balance, 2) }}
                    </h1>
                    <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 fs-6">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency === 'USD' ? '$' : 'FC' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Graphiques --}}
<div class="row">
    {{-- Graphique Évolution mensuelle --}}
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-primary  border-0">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart-line"></i>
                    Évolution mensuelle ({{ $currency }})
                </h6>
            </div>
            <div class="card-body" wire:ignore style="height: 300px;">
                <canvas id="chartMonthly"></canvas>
            </div>
        </div>
    </div>

    {{-- Graphique de comparaison --}}
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm border h-100">
            <div class="card-header bg-dark text-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart-fill"></i>
                    Comparaison Annuelle ({{ $currency }})
                </h6>
            </div>
            <div class="card-body" wire:ignore style="height: 300px;">
                <canvas id="chartComparison"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Tableau de comparaison --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border">
            <div class="card-header bg-info text-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-table"></i>
                    Tableau Récapitulatif Mensuel ({{ $currency }})
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 10%;">Mois</th>
                                <th class="text-end" style="width: 25%;">Recettes
                                    ({{ $currency === 'USD' ? '$' : 'FC' }})</th>
                                <th class="text-end" style="width: 25%;">Dépenses
                                    ({{ $currency === 'USD' ? '$' : 'FC' }})</th>
                                <th class="text-end" style="width: 25%;">Solde
                                    ({{ $currency === 'USD' ? '$' : 'FC' }})</th>
                                <th class="text-center" style="width: 15%;">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chartData['labels'] as $index => $month)
                                @php
                                    $revenue = $chartData['revenues'][$index] ?? 0;
                                    $expense = $chartData['expenses'][$index] ?? 0;
                                    $balance = $chartData['balance'][$index] ?? 0;
                                @endphp
                                <tr>
                                    <td class="fw-bold text-center">{{ $month }}</td>
                                    <td class="text-end text-success fw-semibold">
                                        {{ app_format_number($revenue, 2) }}
                                    </td>
                                    <td class="text-end text-danger fw-semibold">
                                        {{ app_format_number($expense, 2) }}
                                    </td>
                                    <td class="text-end fw-bold {{ $balance >= 0 ? 'text-primary' : 'text-warning' }}">
                                        {{ app_format_number($balance, 2) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($balance > 0)
                                            <span class="badge bg-success"><i class="bi bi-arrow-up"></i></span>
                                        @elseif ($balance < 0)
                                            <span class="badge bg-danger"><i class="bi bi-arrow-down"></i></span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr class="fw-bold">
                                <td class="text-center">TOTAL</td>
                                <td class="text-end text-success">
                                    {{ app_format_number(array_sum($chartData['revenues']), 2) }}
                                </td>
                                <td class="text-end text-danger">
                                    {{ app_format_number(array_sum($chartData['expenses']), 2) }}
                                </td>
                                <td
                                    class="text-end {{ array_sum($chartData['balance']) >= 0 ? 'text-primary' : 'text-warning' }}">
                                    {{ app_format_number(array_sum($chartData['balance']), 2) }}
                                </td>
                                <td class="text-center">
                                    @if (array_sum($chartData['balance']) > 0)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Positif
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> Négatif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
