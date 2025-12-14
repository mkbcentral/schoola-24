@props([
    'expenseType' => 'fee',
    'dateRange' => '',
    'dateDebut' => null,
    'dateFin' => null,
    'filterMonth' => '',
    'filterCategoryFee' => '0',
    'filterOtherSource' => '0',
    'categoryFees' => [],
    'otherSources' => [],
])

<!-- Offcanvas pour filtres supplémentaires -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilters" aria-labelledby="offcanvasFiltersLabel"
    wire:ignore.self>
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title" id="offcanvasFiltersLabel">
            <i class="bi bi-funnel-fill"></i> Filtres supplémentaires
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row g-3">
            <!-- Plage de dates prédéfinies -->
            <div class="col-12">
                <label class="form-label fw-bold">
                    <i class="bi bi-calendar-range"></i> Période prédéfinie
                </label>
                <select class="form-select" wire:model.live="dateRange">
                    <option value="">Sélectionner une période</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="yesterday">Hier</option>
                    <option value="this_week">Cette semaine</option>
                    <option value="last_week">Semaine dernière</option>
                    <option value="this_month">Ce mois</option>
                    <option value="last_month">Mois dernier</option>
                    <option value="this_quarter">Ce trimestre</option>
                    <option value="last_quarter">Trimestre dernier</option>
                    <option value="this_year">Cette année</option>
                    <option value="last_year">Année dernière</option>
                </select>
            </div>

            <!-- Dates début et fin personnalisées -->
            <div class="col-12">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-event"></i> Date début
                        </label>
                        <input type="date" wire:model.live="dateDebut" class="form-control"
                            {{ $dateRange ? 'disabled' : '' }}>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-check"></i> Date fin
                        </label>
                        <input type="date" wire:model.live="dateFin" class="form-control"
                            {{ $dateRange ? 'disabled' : '' }}>
                    </div>
                </div>
                @if (!$dateRange)
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-info-circle"></i> Ou utilisez les dates manuelles
                    </small>
                @endif
            </div>

            <hr class="my-3">

            <!-- Mois spécifique -->
            <div class="col-12">
                <label class="form-label fw-bold">
                    <i class="bi bi-calendar-month"></i> Mois spécifique
                </label>
                <x-widget.list-month-fr wire:model.live='filterMonth' :error="'filterMonth'" />
            </div>

            <hr class="my-3">

            <!-- Filtres spécifiques au type -->
            @if ($expenseType === 'fee')
                <div class="col-12">
                    <label class="form-label fw-bold">
                        <i class="bi bi-receipt"></i> Type de Frais
                    </label>
                    <select class="form-select" wire:model.live="filterCategoryFee">
                        <option value="0">Tous les types</option>
                        @foreach ($categoryFees as $fee)
                            <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="col-12">
                    <label class="form-label fw-bold">
                        <i class="bi bi-box-seam"></i> Source de Dépense
                    </label>
                    <select class="form-select" wire:model.live="filterOtherSource">
                        <option value="0">Toutes les sources</option>
                        @foreach ($otherSources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="mt-4 d-grid gap-2">
            <button class="btn btn-warning" wire:click="resetFilters" data-bs-dismiss="offcanvas"
                wire:loading.attr="disabled" wire:target="resetFilters">
                <i class="bi bi-arrow-clockwise" wire:loading.remove wire:target="resetFilters"></i>
                <span class="spinner-border spinner-border-sm" wire:loading wire:target="resetFilters"></span>
                Réinitialiser tous les filtres
            </button>
            <button class="btn btn-secondary" data-bs-dismiss="offcanvas">
                <i class="bi bi-x-lg"></i> Fermer
            </button>
        </div>
    </div>
</div>
