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
    <div class="col-md-4 mb-3">
        <x-dashboard.stat-card title="Recettes Globales" :value="$total_revenue" :currency="$currency" icon="cash-coin"
            type="success" />
    </div>

    <div class="col-md-4 mb-3">
        <x-dashboard.stat-card title="Dépenses Globales" :value="$total_expense" :currency="$currency" icon="wallet2"
            type="danger" />
    </div>

    <div class="col-md-4 mb-3">
        <x-dashboard.stat-card title="Solde Net" :value="$balance" :currency="$currency" icon="calculator"
            :type="$balance >= 0 ? 'primary' : 'warning'" />
    </div>
</div>

{{-- Graphiques --}}
<div class="row">
    <div class="col-lg-6 col-md-12 mb-4">
        <x-dashboard.chart-card title="Évolution mensuelle ({{ $currency }})" chartId="chartMonthly"
            headerClass="bg-primary" icon="bar-chart-line" />
    </div>

    <div class="col-lg-6 col-md-12 mb-4">
        <x-dashboard.chart-card title="Comparaison Annuelle ({{ $currency }})" chartId="chartComparison"
            headerClass="bg-dark text-white" icon="bar-chart-fill" />
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
                                <th class="summary-table__cell-month">Mois</th>
                                <th class="summary-table__cell-revenue">Recettes
                                    ({{ $currency === 'USD' ? '$' : 'FC' }})</th>
                                <th class="summary-table__cell-expense">Dépenses
                                    ({{ $currency === 'USD' ? '$' : 'FC' }})</th>
                                <th class="summary-table__cell-balance">Solde ({{ $currency === 'USD' ? '$' : 'FC' }})
                                </th>
                                <th class="text-center">Statut</th>
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
                                    <td class="summary-table__cell-month">{{ $month }}</td>
                                    <td class="summary-table__cell-revenue">
                                        {{ app_format_number($revenue, 2) }}
                                    </td>
                                    <td class="summary-table__cell-expense">
                                        {{ app_format_number($expense, 2) }}
                                    </td>
                                    <td
                                        class="summary-table__cell-balance {{ $balance >= 0 ? 'summary-table__cell-balance--positive' : 'summary-table__cell-balance--negative' }}">
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
                        <tfoot class="summary-table__footer">
                            <tr>
                                <td class="summary-table__cell-month">TOTAL</td>
                                <td class="summary-table__cell-revenue">
                                    {{ app_format_number(array_sum($chartData['revenues']), 2) }}
                                </td>
                                <td class="summary-table__cell-expense">
                                    {{ app_format_number(array_sum($chartData['expenses']), 2) }}
                                </td>
                                <td
                                    class="summary-table__cell-balance {{ array_sum($chartData['balance']) >= 0 ? 'summary-table__cell-balance--positive' : 'summary-table__cell-balance--negative' }}">
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
