<div>
    <div class="container mx-auto px-4">
        {{-- En-tête --}}
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-2xl">
                        <i class="bi bi-graph-up-arrow text-blue-600"></i>
                        DASHBOARD FINANCIER
                    </h3>
                    <p class="text-gray-500">Vue d'ensemble des recettes et dépenses</p>
                </div>
                <div class="flex gap-2">
                    {{-- Toggle de devise --}}
                    <button class="px-4 py-2 text-lg rounded {{ $currency === 'USD' ? 'bg-blue-600 text-white' : 'border border-blue-600 text-blue-600 bg-white hover:bg-blue-50' }}"
                        wire:click='toggleCurrency'>
                        <i class="bi bi-currency-dollar"></i> USD
                    </button>
                    <button class="px-4 py-2 text-lg rounded {{ $currency === 'CDF' ? 'bg-green-600 text-white' : 'border border-green-600 text-green-600 bg-white hover:bg-green-50' }}"
                        wire:click='toggleCurrency'>
                        <i class="bi bi-cash"></i> CDF
                    </button>
                </div>
            </div>
        </div>

        {{-- Navigation par tabs moderne --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
            <div class="p-2 bg-gray-50 dark:bg-gray-900">
                <div class="flex gap-2" role="group">
                    <button type="button"
                        class="group relative flex-1 px-6 py-3 rounded-lg font-semibold text-base transition-all duration-200 disabled:opacity-50
                        {{ $activeTab === 'reports' 
                            ? 'bg-blue-600 text-white shadow' 
                            : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600' }}"
                        wire:click="changeTab('reports')" 
                        wire:loading.attr="disabled"
                        wire:target="changeTab('reports')">
                        
                        <div class="relative flex items-center justify-center gap-2">
                            <i class="bi bi-file-earmark-bar-graph" wire:loading.remove wire:target="changeTab('reports')"></i>
                            <span class="inline-block animate-spin border-2 border-t-transparent {{ $activeTab === 'reports' ? 'border-white' : 'border-blue-600' }} rounded-full w-4 h-4" 
                                  wire:loading wire:target="changeTab('reports')"></span>
                            <span>Rapports Détaillés</span>
                        </div>
                        
                        {{-- Barre de sélection --}}
                        @if($activeTab === 'reports')
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-12 h-0.5 bg-white"></div>
                        @endif
                    </button>
                    
                    <button type="button"
                        class="group relative flex-1 px-6 py-3 rounded-lg font-semibold text-base transition-all duration-200 disabled:opacity-50
                        {{ $activeTab === 'global' 
                            ? 'bg-blue-600 text-white shadow' 
                            : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600' }}"
                        wire:click="changeTab('global')" 
                        wire:loading.attr="disabled" 
                        wire:target="changeTab('global')">
                        

                        
                        <div class="relative flex items-center justify-center gap-2">
                            <i class="bi bi-speedometer2 text-xl" wire:loading.remove wire:target="changeTab('global')"></i>
                            <span class="inline-block animate-spin border-2 border-t-transparent {{ $activeTab === 'global' ? 'border-white' : 'border-blue-600' }} rounded-full w-5 h-5" 
                                  wire:loading wire:target="changeTab('global')"></span>
                            <span class="tracking-wide">Rapport Global</span>
                        </div>
                        
                        {{-- Barre de sélection --}}
                        @if($activeTab === 'global')
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-white rounded-t-full"></div>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        {{-- Contenu des tabs --}}
        <div>
            {{-- Tab Rapports Détaillés --}}
            <div class="{{ $activeTab === 'reports' ? 'block' : 'hidden' }}">
                @include('livewire.application.dashboard.finance.partials.detailed-reports-modern')
            </div>

            {{-- Tab Rapport Global --}}
            <div class="{{ $activeTab === 'global' ? 'block' : 'hidden' }}">
                @include('livewire.application.dashboard.finance.partials.global-view-modern')
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
<div class="bg-gray-50 rounded-t-lg px-4 py-3">
    <h6 class="mb-0 text-gray-900">
        <i class="bi bi-funnel text-blue-600"></i>
        Filtres
    </h6>
</div>
<div class="p-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <label class="block font-bold mb-2">Mois</label>
            <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
        </div>
        <div>
            <label class="block font-bold mb-2">Date spécifique</label>
            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.live='date_filter'>
        </div>
        <div>
            <label class="block font-bold mb-2">Catégorie</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" wire:model.live='category_fee_id_filter'>
                <option value="">Toutes les catégories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button class="w-full px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" wire:click='resetFilters'>
                <i class="bi bi-arrow-clockwise"></i> Réinitialiser
            </button>
        </div>
    </div>
</div>
</div>

{{-- Cartes de statistiques --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    {{-- Recettes --}}
    <div class="mb-3">
        <div class="bg-green-600 text-white h-full shadow-lg rounded-lg border-0">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-white/75 uppercase mb-1 font-semibold text-xs tracking-wide">
                            Recettes Globales
                        </p>
                    </div>
                    <div class="bg-white/25 rounded-lg p-2 shadow-sm">
                        <i class="bi bi-cash-coin text-3xl text-white"></i>
                    </div>
                </div>
                <div>
                    <h1 class="mb-2 font-bold text-white text-4xl">
                        {{ app_format_number($total_revenue, 2) }}
                    </h1>
                    <span class="inline-block bg-white/25 text-white px-3 py-2 text-base rounded">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency === 'USD' ? '$' : 'FC' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Dépenses --}}
    <div class="mb-3">
        <div class="bg-red-600 text-white h-full shadow-lg rounded-lg border-0">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-white/75 uppercase mb-1 font-semibold text-xs tracking-wide">
                            Dépenses Globales
                        </p>
                    </div>
                    <div class="bg-white/25 rounded-lg p-2 shadow-sm">
                        <i class="bi bi-wallet2 text-3xl text-white"></i>
                    </div>
                </div>
                <div>
                    <h1 class="mb-2 font-bold text-white text-4xl">
                        {{ app_format_number($total_expense, 2) }}
                    </h1>
                    <span class="inline-block bg-white/25 text-white px-3 py-2 text-base rounded">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency === 'USD' ? '$' : 'FC' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Solde --}}
    <div class="mb-3">
        <div class="{{ $balance >= 0 ? 'bg-blue-600' : 'bg-yellow-500' }} text-white h-full shadow-lg rounded-lg border-0">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-white/75 uppercase mb-1 font-semibold text-xs tracking-wide">
                            Solde Net
                        </p>
                    </div>
                    <div class="bg-white/25 rounded-lg p-2 shadow-sm">
                        <i class="bi bi-calculator text-3xl text-white"></i>
                    </div>
                </div>
                <div>
                    <h1 class="mb-2 font-bold text-white text-4xl">
                        {{ app_format_number($balance, 2) }}
                    </h1>
                    <span class="inline-block bg-white/25 text-white px-3 py-2 text-base rounded">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency === 'USD' ? '$' : 'FC' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Graphiques --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Graphique Évolution mensuelle --}}
    <div class="mb-4">
        <div class="bg-white shadow rounded-lg border h-full">
            <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg border-0">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart-line"></i>
                    Évolution mensuelle ({{ $currency }})
                </h6>
            </div>
            <div class="p-4" wire:ignore style="height: 300px;">
                <canvas id="chartMonthly"></canvas>
            </div>
        </div>
    </div>

    {{-- Graphique de comparaison --}}
    <div class="mb-4">
        <div class="bg-white shadow rounded-lg border h-full">
            <div class="bg-gray-900 text-white px-4 py-3 rounded-t-lg border-0">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart-fill"></i>
                    Comparaison Annuelle ({{ $currency }})
                </h6>
            </div>
            <div class="p-4" wire:ignore style="height: 300px;">
                <canvas id="chartComparison"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Tableau de comparaison --}}
<div class="grid grid-cols-1">
    <div class="mb-4">
        <div class="bg-white shadow rounded-lg border">
            <div class="bg-cyan-500 text-white px-4 py-3 rounded-t-lg border-0">
                <h6 class="mb-0">
                    <i class="bi bi-table"></i>
                    Tableau Récapitulatif Mensuel ({{ $currency }})
                </h6>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="text-center px-4 py-2" style="width: 10%;">Mois</th>
                                <th class="text-right px-4 py-2" style="width: 25%;">Recettes
                                    ({{ $currency === 'USD' ? '$' : 'FC' }})</th>
                                <th class="text-right px-4 py-2" style="width: 25%;">Dépenses
                                    ({{ $currency === 'USD' ? '$' : 'FC' }})</th>
                                <th class="text-right px-4 py-2" style="width: 25%;">Solde
                                    ({{ $currency === 'USD' ? '$' : 'FC' }})</th>
                                <th class="text-center px-4 py-2" style="width: 15%;">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($chartData['labels'] as $index => $month)
                                @php
                                    $revenue = $chartData['revenues'][$index] ?? 0;
                                    $expense = $chartData['expenses'][$index] ?? 0;
                                    $balance = $chartData['balance'][$index] ?? 0;
                                @endphp
                                <tr class="hover:bg-gray-50 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="font-bold text-center px-4 py-2">{{ $month }}</td>
                                    <td class="text-right text-green-600 font-semibold px-4 py-2">
                                        {{ app_format_number($revenue, 2) }}
                                    </td>
                                    <td class="text-right text-red-600 font-semibold px-4 py-2">
                                        {{ app_format_number($expense, 2) }}
                                    </td>
                                    <td
                                        class="text-right font-bold px-4 py-2 {{ $balance >= 0 ? 'text-blue-600' : 'text-yellow-500' }}">
                                        {{ app_format_number($balance, 2) }}
                                    </td>
                                    <td class="text-center px-4 py-2">
                                        @if ($balance > 0)
                                            <span class="inline-block bg-green-500 text-white px-2 py-1 rounded text-xs"><i class="bi bi-arrow-up"></i></span>
                                        @elseif ($balance < 0)
                                            <span class="inline-block bg-red-500 text-white px-2 py-1 rounded text-xs"><i class="bi bi-arrow-down"></i></span>
                                        @else
                                            <span class="inline-block bg-gray-500 text-white px-2 py-1 rounded text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-200">
                            <tr class="font-bold">
                                <td class="text-center px-4 py-2">TOTAL</td>
                                <td class="text-right text-green-600 px-4 py-2">
                                    {{ app_format_number(array_sum($chartData['revenues']), 2) }}
                                </td>
                                <td class="text-right text-red-600 px-4 py-2">
                                    {{ app_format_number(array_sum($chartData['expenses']), 2) }}
                                </td>
                                <td
                                    class="text-right px-4 py-2 {{ array_sum($chartData['balance']) >= 0 ? 'text-blue-600' : 'text-yellow-500' }}">
                                    {{ app_format_number(array_sum($chartData['balance']), 2) }}
                                </td>
                                <td class="text-center px-4 py-2">
                                    @if (array_sum($chartData['balance']) > 0)
                                        <span class="inline-block bg-green-500 text-white px-2 py-1 rounded text-xs">
                                            <i class="bi bi-check-circle"></i> Positif
                                        </span>
                                    @else
                                        <span class="inline-block bg-red-500 text-white px-2 py-1 rounded text-xs">
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
