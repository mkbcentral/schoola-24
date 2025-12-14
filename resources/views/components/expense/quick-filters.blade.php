@props([
    'date' => null,
    'filterPeriod' => '',
    'filterCurrency' => '',
    'filterCategoryExpense' => '0',
    'categoryExpenses' => [],
])

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-funnel me-2"></i>
            Filtres rapides
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Date spécifique</label>
                <input type="date" class="form-select" wire:model.live="date" />
            </div>

            <div class="col-md-2">
                <label class="form-label">Période</label>
                <select class="form-select" wire:model.live="filterPeriod">
                    <option value="">Toutes</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="this_week">Cette semaine</option>
                    <option value="this_month">Ce mois</option>
                    <option value="last_month">Mois dernier</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Devise</label>
                <select class="form-select" wire:model.live="filterCurrency">
                    <option value="">Toutes</option>
                    <option value="USD">USD ($)</option>
                    <option value="CDF">CDF (FC)</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Catégorie</label>
                <select class="form-select" wire:model.live="filterCategoryExpense">
                    <option value="0">Toutes</option>
                    @foreach ($categoryExpenses as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bouton pour filtres supplémentaires -->
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasFilters" aria-controls="offcanvasFilters" title="Plus de filtres">
                    <i class="bi bi-funnel-fill me-1"></i>
                    Plus de filtres
                </button>
            </div>
        </div>
    </div>
</div>
