<div>
    <div class="card border-0 shadow-sm">
        <!-- En-tête -->
        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="{{ route('stock.main') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center"
                        title="Retourner au catalogue des articles">
                        <i class="bi bi-arrow-left me-1"></i>
                        Catalogue
                    </a>
                    <div class="">
                        <h4 class="mb-1 fw-bold d-flex align-items-center">
                            <i class="bi bi-arrow-left-right me-2"></i>
                            Gestion des mouvements
                        </h4>
                        <p class="mb-0 opacity-90 small">
                            <i class="bi bi-box-seam me-1"></i>
                            Article : <strong>{{ $selectedArticleName }}</strong>
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="badge bg-white text-info px-3 py-2 fs-6 fw-semibold shadow-sm">
                        <i class="bi bi-list-ul me-1"></i>
                        {{ $stockMovements->total() }} mouvement{{ $stockMovements->total() > 1 ? 's' : '' }}
                    </div>
                    @if ($selectedArticle)
                        <div class="badge bg-white text-dark px-3 py-2 shadow-sm">
                            <i class="bi bi-archive-fill me-1"></i>
                            Stock : <strong>{{ $selectedArticle->stock }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Résumé du stock en haut -->
            @if ($selectedArticle)
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <div class="row text-center g-3">
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <span class="text-muted small mb-1">Stock actuel</span>
                                <h3 class="mb-0 fw-bold text-primary">
                                    <i class="bi bi-archive-fill me-2"></i>{{ $selectedArticle->stock }}
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <span class="text-muted small mb-1">Total entrées (clôturées)</span>
                                <h4 class="mb-0 text-success">
                                    <i class="bi bi-arrow-down-circle me-1"></i>
                                    {{ $selectedArticle->stockMovements()->where('type', 'in')->where('is_closed', true)->sum('quantity') }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column">
                                <span class="text-muted small mb-1">Total sorties (clôturées)</span>
                                <h4 class="mb-0 text-danger">
                                    <i class="bi bi-arrow-up-circle me-1"></i>
                                    {{ $selectedArticle->stockMovements()->where('type', 'out')->where('is_closed', true)->sum('quantity') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire d'ajout/édition -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-{{ $editMovementMode ? 'pencil-square' : 'plus-circle-fill' }} me-2"></i>
                        {{ $editMovementMode ? 'Modifier le mouvement' : 'Ajouter un mouvement' }}
                    </h6>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="addStockMovement">
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-shuffle me-1 text-primary"></i>Type de mouvement *
                                </label>
                                <select class="form-select form-select-lg" wire:model="form.type">
                                    <option value="in">📥 Entrée en stock</option>
                                    <option value="out">📤 Sortie de stock</option>
                                </select>
                                <x-errors.validation-error value="form.type" />
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-123 me-1 text-primary"></i>Quantité *
                                </label>
                                <input type="number" class="form-control form-control-lg" placeholder="0"
                                    wire:model.blur="form.quantity" min="1" />
                                <x-errors.validation-error value="form.quantity" />
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event me-1 text-primary"></i>Date *
                                </label>
                                <input type="date" class="form-control form-control-lg"
                                    wire:model.blur="form.movement_date" />
                                <x-errors.validation-error value="form.movement_date" />
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-sticky me-1 text-primary"></i>Note
                                </label>
                                <input type="text" class="form-control form-control-lg"
                                    placeholder="Note optionnelle..." wire:model.blur="form.note" />
                                <x-errors.validation-error value="form.note" />
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-{{ $editMovementMode ? 'check-circle' : 'plus-circle' }} me-2"></i>
                                {{ $editMovementMode ? 'Modifier' : 'Ajouter' }} le mouvement
                            </button>
                            @if ($editMovementMode)
                                <button type="button" class="btn btn-outline-secondary btn-lg"
                                    wire:click="resetMovementForm">
                                    <i class="bi bi-x-circle me-1"></i>Annuler
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card border mb-3">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="bi bi-funnel me-2"></i>Filtres de recherche
                        </h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-danger" wire:click="exportPdf">
                                <i class="bi bi-file-earmark-pdf me-1"></i>Exporter PDF
                            </button>
                            @if ($filterType !== 'all' || $filterPeriod !== 'all')
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    wire:click="resetFilters">
                                    <i class="bi bi-x-circle me-1"></i>Réinitialiser
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Filtre par type -->
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-filter-circle me-1 text-primary"></i>Type de mouvement
                            </label>
                            <select class="form-select" wire:model.live="filterType">
                                <option value="all">Tous les mouvements</option>
                                <option value="in">📥 Entrées uniquement</option>
                                <option value="out">📤 Sorties uniquement</option>
                            </select>
                        </div>

                        <!-- Filtre par période -->
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-range me-1 text-primary"></i>Période
                            </label>
                            <select class="form-select" wire:model.live="filterPeriod">
                                <option value="all">Toute la période</option>
                                <option value="today">Aujourd'hui</option>
                                <option value="week">Cette semaine</option>
                                <option value="month">Ce mois</option>
                                <option value="custom">Personnalisée</option>
                            </select>
                        </div>

                        <!-- Date début (si période personnalisée) -->
                        @if ($filterPeriod === 'custom')
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-check me-1 text-primary"></i>Date début
                                </label>
                                <input type="date" class="form-control" wire:model.live="filterDateStart"
                                    max="{{ date('Y-m-d') }}">
                            </div>

                            <!-- Date fin (si période personnalisée) -->
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-x me-1 text-primary"></i>Date fin
                                </label>
                                <input type="date" class="form-control" wire:model.live="filterDateEnd"
                                    max="{{ date('Y-m-d') }}">
                            </div>
                        @endif
                    </div>

                    <!-- Statistiques filtrées -->
                    @if ($filterType !== 'all' || $filterPeriod !== 'all')
                        <div class="alert alert-primary border-0 mt-3 mb-0">
                            <div class="row text-center g-2">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Mouvements filtrés</small>
                                    <strong class="fs-5">{{ $filteredStats['count'] }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Entrées (clôturées)</small>
                                    <strong class="fs-5 text-success">+{{ $filteredStats['in'] }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Sorties (clôturées)</small>
                                    <strong class="fs-5 text-danger">-{{ $filteredStats['out'] }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historique des mouvements -->
            <div class="card border">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="bi bi-clock-history me-2"></i>Historique des mouvements
                        </h6>
                        <span class="badge bg-primary">{{ $stockMovements->total() }} résultat(s)</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">
                                        <i class="bi bi-calendar3 me-1"></i>Date
                                    </th>
                                    <th>
                                        <i class="bi bi-arrow-left-right me-1"></i>Type
                                    </th>
                                    <th class="text-end">
                                        <i class="bi bi-123 me-1"></i>Quantité
                                    </th>
                                    <th>
                                        <i class="bi bi-sticky me-1"></i>Note
                                    </th>
                                    <th class="text-center">
                                        <i class="bi bi-info-circle me-1"></i>Statut
                                    </th>
                                    <th class="text-center pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stockMovements as $movement)
                                    <tr class="{{ $movement->is_closed ? 'table-light' : '' }}">
                                        <td class="ps-3">
                                            <span class="fw-semibold">
                                                {{ \Carbon\Carbon::parse($movement->movement_date)->format('d/m/Y') }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($movement->created_at)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if ($movement->type === 'in')
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success">
                                                    <i class="bi bi-arrow-down-circle-fill me-1"></i>Entrée
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                                    <i class="bi bi-arrow-up-circle-fill me-1"></i>Sortie
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary fs-6 fw-bold px-3 py-2">
                                                {{ $movement->quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $movement->note ?: '-' }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if ($movement->is_closed)
                                                <span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">
                                                    <i class="bi bi-lock-fill me-1"></i>Clôturé
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                                    <i class="bi bi-unlock-fill me-1"></i>En cours
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3">
                                            @if (!$movement->is_closed)
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button class="btn btn-outline-warning"
                                                        wire:click="editStockMovement({{ $movement->id }})"
                                                        title="Éditer le mouvement">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success"
                                                        wire:click="closeStockMovement({{ $movement->id }})"
                                                        title="Clôturer le mouvement"
                                                        onclick="return confirm('Clôturer ce mouvement ? Cette action est irréversible.')">
                                                        <i class="bi bi-lock-fill"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                                @if ($filterType !== 'all' || $filterPeriod !== 'all')
                                                    <p class="mb-0 fw-semibold">Aucun mouvement trouvé avec ces
                                                        filtres.
                                                    </p>
                                                    <small>Essayez de modifier les critères de recherche.</small>
                                                @else
                                                    <p class="mb-0 fw-semibold">Aucun mouvement enregistré pour cet
                                                        article.
                                                    </p>
                                                    <small>Ajoutez votre premier mouvement ci-dessus.</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($stockMovements->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $stockMovements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
