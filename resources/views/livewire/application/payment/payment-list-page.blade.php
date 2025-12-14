<div>
    <x-navigation.bread-crumb icon='bi bi-list-check' label="Liste des Paiements">
        <x-navigation.bread-crumb-item label='Paiements' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- En-tête avec statistiques -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-primary bg-gradient bg-opacity-10">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0 fw-light text-primary">
                                <i class="bi bi-cash-stack me-2"></i>Liste des Paiements
                            </h5>
                            <div>
                                <button wire:click="generatePdf" class="btn btn-sm btn-primary rounded-pill px-3"
                                    @if (!$categoryFeeId) disabled @endif>
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Exporter PDF
                                </button>
                                <button wire:click="resetFilters"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 ms-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Réinitialiser
                                </button>
                            </div>
                        </div>

                        <!-- Statistiques rapides -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="text-muted mb-1 text-uppercase"
                                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                    Total Paiements
                                                </p>
                                                <h3 class="mb-0 fw-bold">{{ number_format($totalCount) }}</h3>
                                            </div>
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                                <i class="bi bi-cash-stack text-primary fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="text-muted mb-1 text-uppercase"
                                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                    Payés
                                                </p>
                                                <h3 class="mb-0 fw-bold text-success">
                                                    {{ number_format($statistics['paid_count']) }}</h3>
                                            </div>
                                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="text-muted mb-1 text-uppercase"
                                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                    Non payés
                                                </p>
                                                <h3 class="mb-0 fw-bold text-danger">
                                                    {{ number_format($statistics['unpaid_count']) }}</h3>
                                            </div>
                                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                                <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="text-muted mb-1 text-uppercase"
                                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                    Taux de Paiement
                                                </p>
                                                <h3 class="mb-0 fw-bold text-info">
                                                    {{ number_format($statistics['payment_rate'], 1) }}%</h3>
                                            </div>
                                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                                <i class="bi bi-graph-up-arrow text-info fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Totaux par devise -->
                        @if (count($totalsByCurrency) > 0)
                            <div class="row g-2 mt-2">
                                @foreach ($totalsByCurrency as $currency => $amount)
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm bg-body-secondary">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <small class="text-muted d-block">Total
                                                            {{ $currency }}</small>
                                                        <h5 class="mb-0 fw-bold text-body">
                                                            {{ number_format($amount, 2, ',', ' ') }}
                                                        </h5>
                                                    </div>
                                                    <div class="badge bg-primary rounded-pill px-3 py-2">
                                                        {{ $currency }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres principaux (toujours visibles) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary bg-opacity-10 border-bottom">
                        <h5 class="mb-0 text-primary fw-semibold">
                            <i class="bi bi-funnel me-2"></i>
                            Filtres principaux
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Catégorie de frais (OBLIGATOIRE) -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <span class="text-danger">*</span> Catégorie de frais
                                </label>
                                <select wire:model.live="categoryFeeId" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($categoryFees as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }} ({{ $category->currency }})
                                        </option>
                                    @endforeach
                                </select>
                                @if (!$categoryFeeId)
                                    <small class="text-danger">Obligatoire pour afficher les paiements</small>
                                @endif
                            </div>

                            <!-- Date -->
                            <div class="col-md-4">
                                <label class="form-label">Date</label>
                                <input type="date" wire:model.live="date" class="form-control">
                            </div>

                            <!-- Mois -->
                            <div class="col-md-2">
                                <x-v2.month-select />
                            </div>

                            <!-- Bouton pour filtres supplémentaires -->
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-outline-secondary w-100" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilters"
                                    aria-controls="offcanvasFilters" title="Plus de filtres">
                                    <i class="bi bi-funnel-fill">Filtres</i>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offcanvas pour filtres supplémentaires -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilters"
            aria-labelledby="offcanvasFiltersLabel" wire:ignore.self>
            <div class="offcanvas-header bg-primary bg-opacity-10 border-bottom">
                <h5 class="offcanvas-title text-primary fw-semibold" id="offcanvasFiltersLabel">
                    <i class="bi bi-funnel-fill"></i> Filtres supplémentaires
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="row g-3">
                    <!-- Plage de dates prédéfinies -->
                    <div class="col-12">
                        <x-v2.date-range-select :dateRange="$dateRange" />
                    </div>

                    <!-- Dates début et fin sur la même ligne -->
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

                    <!-- Filtres académiques -->
                    <x-v2.academic-filters :sections="$sections" :options="$options" :classRooms="$classRooms" :sectionId="$sectionId"
                        :optionId="$optionId" />

                    <hr class="my-3">

                    <!-- Statut et Éléments par page sur la même ligne -->
                    <div class="col-12">
                        <div class="row g-2">
                            <div class="col-6">
                                <x-v2.payment-status-select />
                            </div>
                            <div class="col-6">
                                <x-v2.per-page-select />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-grid gap-2">
                    <button class="btn btn-warning" wire:click="resetFilters" data-bs-dismiss="offcanvas">
                        <i class="bi bi-arrow-clockwise"></i> Réinitialiser tous les filtres
                    </button>
                    <button class="btn btn-secondary" data-bs-dismiss="offcanvas">
                        <i class="bi bi-x-lg"></i> Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Tableau des paiements -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-body-tertiary border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold text-body">
                                <i class="bi bi-table me-2"></i>
                                Liste des paiements
                                <span class="badge bg-primary ms-2">{{ $totalCount }}</span>
                            </h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Barre de recherche avec catégorie sélectionnée -->
                        <div class="p-3 bg-body-secondary border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <x-v2.search-bar placeholder="Rechercher un élève..." :resultCount="$totalCount"
                                    :searchTerm="$search" />

                                @if ($categoryFeeId && isset($categoryFees))
                                    @php
                                        $selectedCategory = collect($categoryFees)->firstWhere('id', $categoryFeeId);
                                    @endphp
                                    @if ($selectedCategory)
                                        <div class="badge bg-primary rounded-pill px-3 py-2 ms-3">
                                            <i class="bi bi-tag-fill me-1"></i>
                                            {{ $selectedCategory->name }} ({{ $selectedCategory->currency }})
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-body-secondary border-bottom">
                                    <tr>
                                        <th class="px-3 text-body-secondary fw-semibold">#</th>
                                        <th class="text-body-secondary fw-semibold">N° Paiement</th>
                                        <th class="text-body-secondary fw-semibold">Élève</th>
                                        <th class="text-body-secondary fw-semibold">Catégorie</th>
                                        <th class="text-end text-body-secondary fw-semibold">Montant</th>
                                        <th class="text-center text-body-secondary fw-semibold">Mois</th>
                                        <th class="text-center text-body-secondary fw-semibold">Statut</th>
                                        <th class="text-center text-body-secondary fw-semibold">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="px-3 fw-semibold">
                                                {{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $payment->payment_number }}</div>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $payment->created_at->format('d/m/Y') ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $payment->registration->student->name ?? 'N/A' }}</div>
                                                <small class="text-muted">
                                                    <i class="bi bi-diagram-3 me-1"></i>
                                                    {{ ($payment->registration->classRoom->name ?? 'N/A') . ' - ' . ($payment->registration->classRoom->option->name ?? 'N/A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    {{ $payment->scolarFee->categoryFee->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="fw-bold text-primary">{{ number_format($payment->scolarFee->amount ?? 0, 1, ',', ' ') }}</span>
                                                <small
                                                    class="text-muted">{{ $payment->scolarFee->categoryFee->currency ?? 'N/A' }}</small>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                    {{ format_fr_month_name($payment->month) ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($payment->is_paid)
                                                    <span
                                                        class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success">
                                                        <i class="bi bi-check-circle-fill"></i> Payé
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger">
                                                        <i class="bi bi-x-circle-fill"></i> Non payé
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $payment->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <x-v2.empty-state message="Aucun paiement trouvé avec ces critères"
                                            colspan="9" />
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="p-3 border-top bg-body-tertiary">
                            <x-v2.pagination-info :paginator="$payments" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicateur de chargement -->
        <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />
    </x-content.main-content-page>
</div>
