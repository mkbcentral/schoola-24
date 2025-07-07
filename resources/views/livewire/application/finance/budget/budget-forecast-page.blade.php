<div>
    <x-navigation.bread-crumb icon='bi bi-bar-chart-fill' label="Dashboard">
        <x-navigation.bread-crumb-item label='Dashboard' />
    </x-navigation.bread-crumb>
    <!-- Bootstrap Nav Tabs -->
    <ul class="nav nav-tabs mb-3 rounded shadow-sm bg-white px-2 py-1" id="chartTabs" role="tablist"
        style="overflow-x:auto;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold text-primary" id="monthly-movements-tab" data-bs-toggle="tab"
                data-bs-target="#monthly-movements" type="button" role="tab" aria-controls="monthly-movements"
                aria-selected="true" style="border-radius: 8px 8px 0 0;">
                <i class="bi bi-bar-chart-line"></i> Graphique Mensuels
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold text-success" id="budget-forecast-tab" data-bs-toggle="tab"
                data-bs-target="#budget-forecast" type="button" role="tab" aria-controls="budget-forecast"
                aria-selected="false" style="border-radius: 8px 8px 0 0;">
                <i class="bi bi-graph-up-arrow"></i> Graphique par catégorie
            </button>
        </li>
    </ul>
    <div class="tab-content mb-4 rounded-bottom shadow-sm bg-light p-4" id="chartTabsContent">
        <div class="tab-pane fade show active" id="monthly-movements" role="tabpanel"
            aria-labelledby="monthly-movements-tab">
            <canvas id="monthlyMovementsChart"></canvas>
        </div>
        <div class="tab-pane fade" id="budget-forecast" role="tabpanel" aria-labelledby="budget-forecast-tab">
            <canvas id="budgetForecastChart"></canvas>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Catégorie</th>
                @foreach ($months as $month)
                    <th>{{ $month['name'] }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expensesByCategory as $category)
                <tr>
                    <td class="text-uppercase">{{ $category['category_name'] }}</td>
                    @foreach ($months as $m)
                        @php
                            $amount = $category['monthly_amounts'][$m['number']];
                        @endphp
                        <td class="text-end{{ $amount == 0 ? ' bg-light text-secondary' : '' }}">
                            {{ number_format($amount, 2, ',', ' ') }} $
                        </td>
                    @endforeach
                    <td class="text-end"><strong>{{ number_format($category['total_amount'], 2, ',', ' ') }}
                            $</strong>
                    </td>
                </tr>
            @endforeach
            <tr>
                <th class="text-uppercase">Total par mois</th>
                @php
                    $totalsByMonth = [];
                    foreach ($months as $m) {
                        $totalsByMonth[$m['number']] = $expensesByCategory->sum(function ($cat) use ($m) {
                            return $cat['monthly_amounts'][$m['number']];
                        });
                    }
                    $globalTotal = array_sum($totalsByMonth);
                @endphp
                @foreach ($months as $m)
                    @php
                        $totalMonth = $totalsByMonth[$m['number']];
                    @endphp
                    <th class="text-end{{ $totalMonth == 0 ? ' bg-light text-muted' : '' }}">
                        {{ number_format($totalMonth, 2, ',', ' ') }} $
                    </th>
                @endforeach
                <th class="text-end bg-primary text-white" style="font-size: 1.3em;">
                    <strong>{{ number_format($globalTotal, 2, ',', ' ') }} $</strong>
                </th>
            </tr>
        </tbody>
    </table>
</div>
