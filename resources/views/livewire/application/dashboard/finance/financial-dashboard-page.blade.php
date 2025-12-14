<div>
    <div class="container-fluid">
        {{-- En-tête --}}
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold">
                        <i class="bi bi-graph-up-arrow text-primary"></i>
                        DASHBOARD FINANCIER
                    </h3>
                    <p class="text-muted">Vue d'ensemble des recettes et dépenses</p>
                </div>
                <div>
                    {{-- Toggle de devise --}}
                    <button class="btn btn-lg {{ $currency === 'USD' ? 'btn-primary' : 'btn-outline-primary' }}"
                        wire:click='toggleCurrency'>
                        <i class="bi bi-currency-dollar"></i> USD
                    </button>
                    <button class="btn btn-lg {{ $currency === 'CDF' ? 'btn-success' : 'btn-outline-success' }}"
                        wire:click='toggleCurrency'>
                        <i class="bi bi-cash"></i> CDF
                    </button>
                </div>
            </div>
        </div>

        {{-- Navigation par tabs simple (comme expense/manage) --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="btn-group w-100" role="group">
                    <button type="button"
                        class="btn btn-{{ $activeTab === 'reports' ? 'primary' : 'outline-primary' }}"
                        wire:click="changeTab('reports')" wire:loading.attr="disabled"
                        wire:target="changeTab('reports')">
                        <i class="bi bi-file-earmark-bar-graph me-2" wire:loading.remove
                            wire:target="changeTab('reports')"></i>
                        <span class="spinner-border spinner-border-sm me-2" wire:loading
                            wire:target="changeTab('reports')"></span>
                        Rapports Détaillés
                    </button>
                    <button type="button" class="btn btn-{{ $activeTab === 'global' ? 'primary' : 'outline-primary' }}"
                        wire:click="changeTab('global')" wire:loading.attr="disabled" wire:target="changeTab('global')">
                        <i class="bi bi-speedometer2 me-2" wire:loading.remove wire:target="changeTab('global')"></i>
                        <span class="spinner-border spinner-border-sm me-2" wire:loading
                            wire:target="changeTab('global')"></span>
                        Rapport Global
                    </button>
                </div>
            </div>
        </div>

        {{-- Contenu des tabs --}}
        <div class="tab-content">
            {{-- Tab Rapports Détaillés --}}
            <div class="tab-pane fade {{ $activeTab === 'reports' ? 'show active' : '' }}">
                @include('livewire.application.dashboard.finance.partials.detailed-reports')
            </div>

            {{-- Tab Rapport Global --}}
            <div class="tab-pane fade {{ $activeTab === 'global' ? 'show active' : '' }}">
                @include('livewire.application.dashboard.finance.partials.global-view')
            </div>
        </div>
    </div>
    @script
        <script>
            let chartMonthly = null;
            let chartComparison = null;

            function updateCharts(chartData) {
                // Attendre que Chart.js soit chargé avec retry
                if (typeof Chart === 'undefined') {
                    setTimeout(() => updateCharts(chartData), 100);
                    return;
                }

                const currency = chartData.currency;
                const currencySymbol = currency === 'USD' ? '$' : 'FC';

                // Détruire les graphiques existants
                if (chartMonthly) {
                    chartMonthly.destroy();
                    chartMonthly = null;
                }
                if (chartComparison) {
                    chartComparison.destroy();
                    chartComparison = null;
                }

                // Configuration commune pour les graphiques
                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' ' + currencySymbol;
                                }
                            }
                        }
                    }
                };

                // Graphique mensuel
                const ctxMonthly = document.getElementById('chartMonthly');
                if (ctxMonthly) {
                    chartMonthly = new Chart(ctxMonthly, {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                    label: 'Recettes (' + currency + ')',
                                    data: chartData.revenues,
                                    borderColor: '#28a745',
                                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                    tension: 0.4,
                                    fill: true
                                },
                                {
                                    label: 'Dépenses (' + currency + ')',
                                    data: chartData.expenses,
                                    borderColor: '#dc3545',
                                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                                    tension: 0.4,
                                    fill: true
                                },
                                {
                                    label: 'Solde (' + currency + ')',
                                    data: chartData.balance,
                                    borderColor: '#007bff',
                                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    borderDash: [5, 5]
                                }
                            ]
                        },
                        options: commonOptions
                    });
                }

                // Graphique de comparaison (Bar chart)
                const ctxComparison = document.getElementById('chartComparison');
                if (ctxComparison) {
                    chartComparison = new Chart(ctxComparison, {
                        type: 'bar',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                    label: 'Recettes (' + currency + ')',
                                    data: chartData.revenues,
                                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                    borderColor: '#28a745',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Dépenses (' + currency + ')',
                                    data: chartData.expenses,
                                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                                    borderColor: '#dc3545',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Solde (' + currency + ')',
                                    data: chartData.balance,
                                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                                    borderColor: '#007bff',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Recettes vs Dépenses vs Solde (' + currency + ')'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString() + ' ' + currencySymbol;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Initialiser au chargement avec un délai pour lazy loading
            setTimeout(() => {
                updateCharts(@json($chartData));
            }, 150);

            // Écouter les mises à jour Livewire
            $wire.on('charts-updated', (data) => {
                updateCharts(data[0]);
            });

            // Réinitialiser après navigation Livewire (pour lazy loading)
            document.addEventListener('livewire:navigated', () => {
                setTimeout(() => {
                    updateCharts(@json($chartData));
                }, 150);
            });
        </script>
    @endscript
</div>

```
<div class="card-header bg-light">
    <h6 class="mb-0 text-dark">
        <i class="bi bi-funnel text-primary"></i>
        Filtres
    </h6>
</div>
<div class="card-body">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-bold">Mois</label>
            <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Date spécifique</label>
            <input type="date" class="form-control" wire:model.live='date_filter'>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Catégorie</label>
            <select class="form-control" wire:model.live='category_fee_id_filter'>
                <option value="">Toutes les catégories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-secondary w-100" wire:click='resetFilters'>
                <i class="bi bi-arrow-clockwise"></i> Réinitialiser
            </button>
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
                                    <td
                                        class="text-end fw-bold {{ $balance >= 0 ? 'text-primary' : 'text-warning' }}">
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
</div>
@script
    <script>
        let chartMonthly = null;
        let chartComparison = null;

        function updateCharts(chartData) {
            // Attendre que Chart.js soit chargé avec retry
            if (typeof Chart === 'undefined') {
                setTimeout(() => updateCharts(chartData), 100);
                return;
            }

            const currency = chartData.currency;
            const currencySymbol = currency === 'USD' ? '$' : 'FC';

            // Détruire les graphiques existants
            if (chartMonthly) {
                chartMonthly.destroy();
                chartMonthly = null;
            }
            if (chartComparison) {
                chartComparison.destroy();
                chartComparison = null;
            }

            // Configuration commune pour les graphiques
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' ' + currencySymbol;
                            }
                        }
                    }
                }
            };

            // Graphique mensuel
            const ctxMonthly = document.getElementById('chartMonthly');
            if (ctxMonthly) {
                chartMonthly = new Chart(ctxMonthly, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                                label: 'Recettes (' + currency + ')',
                                data: chartData.revenues,
                                borderColor: '#28a745',
                                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Dépenses (' + currency + ')',
                                data: chartData.expenses,
                                borderColor: '#dc3545',
                                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Solde (' + currency + ')',
                                data: chartData.balance,
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                tension: 0.4,
                                fill: true,
                                borderDash: [5, 5]
                            }
                        ]
                    },
                    options: commonOptions
                });
            }

            // Graphique de comparaison (Bar chart)
            const ctxComparison = document.getElementById('chartComparison');
            if (ctxComparison) {
                chartComparison = new Chart(ctxComparison, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                                label: 'Recettes (' + currency + ')',
                                data: chartData.revenues,
                                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                borderColor: '#28a745',
                                borderWidth: 1
                            },
                            {
                                label: 'Dépenses (' + currency + ')',
                                data: chartData.expenses,
                                backgroundColor: 'rgba(220, 53, 69, 0.7)',
                                borderColor: '#dc3545',
                                borderWidth: 1
                            },
                            {
                                label: 'Solde (' + currency + ')',
                                data: chartData.balance,
                                backgroundColor: 'rgba(0, 123, 255, 0.7)',
                                borderColor: '#007bff',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Recettes vs Dépenses vs Solde (' + currency + ')'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString() + ' ' + currencySymbol;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Initialiser au chargement avec un délai pour lazy loading
        setTimeout(() => {
            updateCharts(@json($chartData));
        }, 150);

        // Écouter les mises à jour Livewire
        $wire.on('charts-updated', (data) => {
            updateCharts(data[0]);
        });

        // Réinitialiser après navigation Livewire (pour lazy loading)
        document.addEventListener('livewire:navigated', () => {
            setTimeout(() => {
                updateCharts(@json($chartData));
            }, 150);
        });
    </script>
@endscript
</div>
