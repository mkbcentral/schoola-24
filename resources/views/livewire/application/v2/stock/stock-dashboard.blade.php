<div>
    <x-navigation.bread-crumb icon='bi bi-graph-up-arrow' label="Stock">
        <x-navigation.bread-crumb-item label='Dashboard' />
        <x-navigation.bread-crumb-item label='Catalogue' isLinked=true link="stock.main" />
        <x-navigation.bread-crumb-item label='Inventaire' isLinked=true link="stock.inventory" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body py-3 text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold">
                                    <i class="bi bi-graph-up-arrow me-2"></i>Dashboard Stock
                                </h4>
                                <p class="mb-0 opacity-75">
                                    <i
                                        class="bi bi-calendar3 me-1"></i>{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                                </p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('stock.main') }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-box-seam me-1"></i>
                                    Catalogue
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="shrink-0">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="bi bi-box-seam fs-3 text-primary"></i>
                                </div>
                            </div>
                            <div class="grow ms-3">
                                <h6 class="text-muted mb-1 small">Total Articles</h6>
                                <h3 class="mb-0 fw-bold">{{ $totalArticles }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="shrink-0">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-boxes fs-3 text-success"></i>
                                </div>
                            </div>
                            <div class="grow ms-3">
                                <h6 class="text-muted mb-1 small">Stock Total</h6>
                                <h3 class="mb-0 fw-bold">{{ number_format($totalStock, 0, ',', ' ') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="shrink-0">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                    <i class="bi bi-arrow-left-right fs-3 text-info"></i>
                                </div>
                            </div>
                            <div class="grow ms-3">
                                <h6 class="text-muted mb-1 small">Mouvements ce mois</h6>
                                <h3 class="mb-0 fw-bold">{{ $monthlyMovements }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="shrink-0">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="bi bi-exclamation-triangle fs-3 text-warning"></i>
                                </div>
                            </div>
                            <div class="grow ms-3">
                                <h6 class="text-muted mb-1 small">Alertes Stock</h6>
                                <h3 class="mb-0 fw-bold">{{ $lowStockCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row g-3 mb-4">
            <!-- Graphique Entrées vs Sorties -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart me-2 text-primary"></i>Répartition des Mouvements
                        </h5>
                        <small class="text-muted">Total des entrées et sorties clôturées</small>
                    </div>
                    <div class="card-body">
                        @if ($movementsByType['entries'] > 0 || $movementsByType['exits'] > 0)
                            <canvas id="movementsChart" height="250"></canvas>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted">Aucun mouvement clôturé</p>
                                <small class="text-muted">Les données apparaîtront une fois les mouvements
                                    clôturés</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Graphique Évolution -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2 text-success"></i>Évolution du stock (6 mois)
                        </h5>
                        <small class="text-muted">Tendance du stock total sur 6 mois</small>
                    </div>
                    <div class="card-body">
                        @php
                            $hasData = collect($stockEvolution['data'])->filter(fn($val) => $val != 0)->isNotEmpty();
                        @endphp
                        @if ($hasData)
                            <canvas id="evolutionChart" height="250"></canvas>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-graph-up fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted">Pas encore de données d'évolution</p>
                                <small class="text-muted">Le graphique se remplira au fil des mouvements</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Articles les plus utilisés -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-trophy me-2 text-warning"></i>Top 10 Articles
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Article</th>
                                        <th>Catégorie</th>
                                        <th class="text-center">Mouvements</th>
                                        <th class="text-center">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topArticles as $index => $article)
                                        <tr>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill {{ $index < 3 ? 'text-bg-warning text-dark' : 'text-bg-secondary' }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $article->name }}</strong>
                                                @if ($article->reference)
                                                    <br><small class="text-muted">Réf:
                                                        {{ $article->reference }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($article->category)
                                                    <span class="badge"
                                                        style="background-color: {{ $article->category->color }};">
                                                        {{ $article->category->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class=" badge text-bg-info">
                                                    {{ $article->stock_movements_count }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class=" badge text-bg-{{ $article->stock > 10 ? 'success' : 'warning' }}">
                                                    {{ $article->stock }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                Aucun mouvement enregistré
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertes de stock -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Alertes de Stock
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Article</th>
                                        <th>Catégorie</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Min</th>
                                        <th class="text-center">État</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockArticles as $article)
                                        <tr>
                                            <td>
                                                <strong>{{ $article->name }}</strong>
                                                @if ($article->reference)
                                                    <br><small class="text-muted">Réf:
                                                        {{ $article->reference }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($article->category)
                                                    <span class="badge"
                                                        style="background-color: {{ $article->category->color }};">
                                                        {{ $article->category->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-{{ $article->is_critical_stock ? 'danger' : 'warning' }} fs-6">
                                                    {{ $article->stock }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted">{{ $article->stock_min }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($article->is_critical_stock)
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Critique
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>Faible
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <span class="text-center">
                                                    <i class="bi bi-check-circle fs-3 text-success  mb-2"></i>
                                                    Aucune alerte de stock
                                                </span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques par catégorie -->
        @if ($categoryStats->isNotEmpty())
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="bi bi-tags me-2 text-info"></i>Répartition par Catégorie
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ($categoryStats as $stat)
                                    <div class="col-md-3">
                                        <div class="card border"
                                            style="border-color: {{ $stat->color }} !important;">
                                            <div class="card-body text-center">
                                                <div class="rounded-circle mx-auto mb-2"
                                                    style="width: 60px; height: 60px; background-color: {{ $stat->color }}; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-tag-fill text-white fs-4"></i>
                                                </div>
                                                <h6 class="mb-1">{{ $stat->category_name ?? 'Sans catégorie' }}</h6>
                                                <h4 class="mb-0 fw-bold">{{ $stat->total_articles }}</h4>
                                                <small class="text-muted">articles</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-content.main-content-page>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Graphique Entrées vs Sorties (Doughnut)
                const movementsCanvas = document.getElementById('movementsChart');
                if (movementsCanvas) {
                    const movementsCtx = movementsCanvas.getContext('2d');
                    new Chart(movementsCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Entrées', 'Sorties'],
                            datasets: [{
                                data: [{{ $movementsByType['entries'] }},
                                    {{ $movementsByType['exits'] }}
                                ],
                                backgroundColor: ['#198754', '#dc3545'],
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed.toLocaleString() +
                                                ' unités';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Graphique Évolution (Line)
                const evolutionCanvas = document.getElementById('evolutionChart');
                if (evolutionCanvas) {
                    const evolutionCtx = evolutionCanvas.getContext('2d');
                    new Chart(evolutionCtx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($stockEvolution['labels']) !!},
                            datasets: [{
                                label: 'Stock Total',
                                data: {!! json_encode($stockEvolution['data']) !!},
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: '#0d6efd',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Stock: ' + context.parsed.y.toLocaleString() +
                                                ' unités';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</div>
