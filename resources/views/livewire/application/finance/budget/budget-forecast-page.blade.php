<div>
    <x-navigation.bread-crumb icon='bi bi-bar-chart-fill' label="Dashboard">
        <x-navigation.bread-crumb-item label='Dashboard' />
    </x-navigation.bread-crumb>
    <!-- Bootstrap Nav Tabs -->
    <ul class="nav nav-tabs mb-3 rounded shadow-sm px-2 py-1 flex-nowrap overflow-auto" id="chartTabs" role="tablist"
        style="overflow-x:auto;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold text-primary dark:text-blue-400" id="monthly-movements-tab"
                data-bs-toggle="tab" data-bs-target="#monthly-movements" type="button" role="tab"
                aria-controls="monthly-movements" aria-selected="true" style="border-radius: 8px 8px 0 0;">
                <i class="bi bi-bar-chart-line"></i> Graphique Mensuels
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-success dark:text-green-400" id="budget-forecast-tab"
                data-bs-toggle="tab" data-bs-target="#budget-forecast" type="button" role="tab"
                aria-controls="budget-forecast" aria-selected="false" style="border-radius: 8px 8px 0 0;">
                <i class="bi bi-graph-up-arrow"></i> Graphique par catégorie
            </button>
        </li>
    </ul>
    <div class="tab-content mb-4 rounded-bottom shadow-sm p-4" id="chartTabsContent">
        <div class="tab-pane fade show active" id="monthly-movements" role="tabpanel"
            aria-labelledby="monthly-movements-tab">
            <div class="w-100" style="overflow-x:auto;">
                <canvas id="monthlyMovementsChart"></canvas>
            </div>
        </div>
        <div class="tab-pane fade" id="budget-forecast" role="tabpanel" aria-labelledby="budget-forecast-tab">
            <div class="w-100" style="overflow-x:auto;">
                <canvas id="budgetForecastChart"></canvas>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        @php
            // Filtrer les mois qui ont au moins une valeur non nulle dans toutes les catégories
            $filteredMonths = collect($months)
                ->filter(function ($month) use ($expensesByCategory) {
                    foreach ($expensesByCategory as $cat) {
                        if (
                            !empty($cat['monthly_amounts'][$month['number']]) &&
                            $cat['monthly_amounts'][$month['number']] != 0
                        ) {
                            return true;
                        }
                    }
                    return false;
                })
                ->values();
        @endphp
        <table class="table table-bordered dark:text-gray-200 dark:bg-gray-900 align-middle">
            <thead class="dark:bg-gray-800">
                <tr>
                    <th>Catégorie</th>
                    @foreach ($filteredMonths as $month)
                        <th>{{ $month['name'] }}</th>
                    @endforeach
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expensesByCategory as $category)
                    @if ($category['total_amount'] != 0)
                        <tr>
                            <td class="text-uppercase">{{ $category['category_name'] }}</td>
                            @foreach ($filteredMonths as $m)
                                @php
                                    $amount = $category['monthly_amounts'][$m['number']];
                                @endphp
                                <td
                                    class="text-end{{ $amount == 0 ? ' bg-light dark:bg-gray-800 text-secondary dark:text-gray-500' : '' }}">
                                    {{ $amount != 0 ? number_format($amount, 2, ',', ' ') . ' $' : '' }}
                                </td>
                            @endforeach
                            <td class="text-end"><strong>{{ number_format($category['total_amount'], 2, ',', ' ') }}
                                    $</strong>
                            </td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <th class="text-uppercase">Total par mois</th>
                    @php
                        $totalsByMonth = [];
                        foreach ($filteredMonths as $m) {
                            $totalsByMonth[$m['number']] = $expensesByCategory->sum(function ($cat) use ($m) {
                                return $cat['monthly_amounts'][$m['number']];
                            });
                        }
                        $globalTotal = array_sum($totalsByMonth);
                    @endphp
                    @foreach ($filteredMonths as $m)
                        @php
                            $totalMonth = $totalsByMonth[$m['number']];
                        @endphp
                        <th
                            class="text-end{{ $totalMonth == 0 ? ' bg-light dark:bg-gray-800 text-muted dark:text-gray-500' : '' }}">
                            {{ $totalMonth != 0 ? number_format($totalMonth, 2, ',', ' ') . ' $' : '' }}
                        </th>
                    @endforeach
                    <th class="text-end bg-primary dark:bg-blue-700 text-white" style="font-size: 1.3em;">
                        <strong>{{ number_format($globalTotal, 2, ',', ' ') }} $</strong>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>

    <style>
        @media (max-width: 768px) {
            .table-responsive table {
                font-size: 0.95em;
            }

            .nav-tabs .nav-link {
                white-space: nowrap;
                font-size: 0.95em;
                padding-left: 0.6rem;
                padding-right: 0.6rem;
            }
        }
    </style>
</div>
