<div>
    <div>
        @php
            $totalPayments = 0;
            $totalExpenses = 0;
            $totalOtherExpenses = 0;
            $totalBalance = 0;
        @endphp
        <div class="card">
            <div
                class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <h4 class="text-uppercase mb-2 mb-md-0">SYNTHÈSE FINANCIÈRE SUR LES FRAIS SCOLAIRES</h4>
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                    <label for="my-select" class="me-2 mb-1 mb-sm-0">Categorie</label>
                    <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='category_fee_filter'
                        :error="'category_fee_filter'" />
                </div>
            </div>
            <div class="card-body">
                <div class="w-100" style="overflow-x:auto;" wire:ignore>
                    <canvas id="expensesChart"></canvas>
                </div>
                <div class="table-responsive mt-4" id="printable-table">
                    <table class="table table-striped table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Catégorie</th>
                                <th class="text-end">Recettes</th>
                                <th class="text-end">Dépenses</th>
                                <th class="text-end">Emprunt</th>
                                <th class="text-end">Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($balances as $month => $categories)
                                @foreach ($categories as $category => $data)
                                    @php
                                        $totalPayments += $data['payments'];
                                        $totalExpenses += $data['expenses'];
                                        $totalOtherExpenses += $data['other_expenses'];
                                        $totalBalance += $data['balance'];
                                    @endphp
                                    <tr>
                                        <td class="text-uppercase">
                                            {{ $month }}
                                        </td>
                                        <td>{{ $category }}</td>
                                        <td class="text-end">{{ number_format($data['payments'], 2) }}</td>
                                        <td class="text-end">{{ number_format($data['expenses'], 2) }}</td>
                                        <td class="text-end">{{ number_format($data['other_expenses'], 2) }}</td>
                                        <td class="text-end">{{ number_format($data['balance'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot class="bg-dark text-uppercase">
                            <tr class="h4">
                                <th colspan="2" class="text-end">Total Général</th>
                                <th class="text-end">{{ number_format($totalPayments, 2) }}</th>
                                <th class="text-end">{{ number_format($totalExpenses, 2) }}</th>
                                <th class="text-end">{{ number_format($totalOtherExpenses, 2) }}</th>
                                <th class="text-end">{{ number_format($totalBalance, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <style>
            @media (max-width: 768px) {
                .card-header {
                    flex-direction: column !important;
                    align-items: stretch !important;
                }

                .card-header>* {
                    width: 100%;
                }

                .table-responsive {
                    font-size: 0.95rem;
                }

                .card-header h4 {
                    font-size: 1.1rem;
                }
            }

            @media (max-width: 576px) {
                .table-responsive {
                    font-size: 0.9rem;
                }

                .card-header h4 {
                    font-size: 1rem;
                }
            }
        </style>

        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                let expensesChart = null;

                // Détection du thème (clair/sombre)
                function getTheme() {
                    return document.documentElement.getAttribute('data-bs-theme') || 'light';
                }

                // Couleurs adaptatives selon le thème
                function getChartColors() {
                    const isDark = getTheme() === 'dark';
                    return {
                        text: isDark ? '#e9ecef' : '#212529',
                        grid: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                        titleColor: isDark ? '#ffffff' : '#212529'
                    };
                }

                function waitForChart(callback, retries = 20) {
                    if (typeof Chart !== 'undefined') {
                        callback();
                    } else if (retries > 0) {
                        setTimeout(() => waitForChart(callback, retries - 1), 100);
                    } else {
                        console.error('Chart.js n\'a pas pu être chargé');
                    }
                }

                function updateExpensesChart(balances) {
                    const ctx = document.getElementById('expensesChart');
                    if (!ctx) {
                        console.warn('Canvas expensesChart non trouvé');
                        return;
                    }

                    // Vérifier que nous avons des données
                    if (!balances || Object.keys(balances).length === 0) {
                        console.warn('Pas de données pour le graphique');
                        return;
                    }

                    // Obtenir les couleurs adaptatives
                    const colors = getChartColors();

                    // Préparer les données pour le graphique
                    const months = Object.keys(balances);
                    const payments = [];
                    const expenses = [];
                    const otherExpenses = [];
                    const totalExpenses = [];
                    const balanceData = [];

                    months.forEach(month => {
                        let monthPayments = 0;
                        let monthExpenses = 0;
                        let monthOtherExpenses = 0;
                        let monthTotalExpenses = 0;
                        let monthBalance = 0;

                        Object.values(balances[month]).forEach(category => {
                            monthPayments += category.payments || 0;
                            monthExpenses += category.expenses || 0;
                            monthOtherExpenses += category.other_expenses || 0;
                            monthTotalExpenses += category.total_expenses || 0;
                            monthBalance += category.balance || 0;
                        });

                        payments.push(monthPayments);
                        expenses.push(monthExpenses);
                        otherExpenses.push(monthOtherExpenses);
                        totalExpenses.push(monthTotalExpenses);
                        balanceData.push(monthBalance);
                    });

                    // Détruire le graphique existant
                    if (expensesChart) {
                        expensesChart.destroy();
                        expensesChart = null;
                    }

                    try {
                        expensesChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: months,
                                datasets: [{
                                        label: 'Recettes',
                                        data: payments,
                                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                                        borderColor: 'rgba(40, 167, 69, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Dépenses Frais',
                                        data: expenses,
                                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                                        borderColor: 'rgba(220, 53, 69, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Autres Dépenses',
                                        data: otherExpenses,
                                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                                        borderColor: 'rgba(255, 193, 7, 1)',
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Solde',
                                        data: balanceData,
                                        type: 'line',
                                        backgroundColor: 'rgba(13, 110, 253, 0.2)',
                                        borderColor: 'rgba(13, 110, 253, 1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            usePointStyle: true,
                                            padding: 15,
                                            color: colors.text
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Synthèse Financière Mensuelle',
                                        font: {
                                            size: 16,
                                            weight: 'bold'
                                        },
                                        color: colors.titleColor
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.dataset.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += new Intl.NumberFormat('fr-FR', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                }).format(context.parsed.y);
                                                return label;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: colors.text,
                                            callback: function(value) {
                                                return new Intl.NumberFormat('fr-FR', {
                                                    minimumFractionDigits: 0,
                                                    maximumFractionDigits: 0
                                                }).format(value);
                                            }
                                        },
                                        grid: {
                                            color: colors.grid
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            maxRotation: 45,
                                            minRotation: 45,
                                            color: colors.text
                                        },
                                        grid: {
                                            color: colors.grid
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Graphique expensesChart créé avec succès');
                    } catch (error) {
                        console.error('Erreur lors de la création du graphique:', error);
                    }
                }

                // Initialisation au chargement du composant
                const initialBalances = @json($balances ?? []);
                if (Object.keys(initialBalances).length > 0) {
                    waitForChart(() => updateExpensesChart(initialBalances));
                }

                // Écouter l'événement Livewire pour mettre à jour le graphique
                if (typeof Livewire !== 'undefined') {
                    Livewire.on('refresh-expenses', (event) => {
                        const balances = event.params || event[0]?.params || event;
                        if (balances && typeof balances === 'object') {
                            waitForChart(() => updateExpensesChart(balances));
                        }
                    });
                } else {
                    document.addEventListener('livewire:init', () => {
                        Livewire.on('refresh-expenses', (event) => {
                            const balances = event.params || event[0]?.params || event;
                            if (balances && typeof balances === 'object') {
                                waitForChart(() => updateExpensesChart(balances));
                            }
                        });
                    });
                }

                // Observer les changements de thème
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.attributeName === 'data-bs-theme') {
                            const balances = @json($balances ?? []);
                            if (Object.keys(balances).length > 0) {
                                updateExpensesChart(balances);
                            }
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['data-bs-theme']
                });
            });
        </script>
    </div>
</div>
