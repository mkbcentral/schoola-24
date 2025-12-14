<div>
    <x-navigation.bread-crumb icon='bi bi-list-check' label="Liste des Paiements">
        <x-navigation.bread-crumb-item label='Paiements' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- En-tête avec statistiques -->
        <div class="row mb-4">
            <div class="col-12">
                <x-ui.page-header title="Liste des Paiements" icon="cash-stack">
                    <x-slot:actions>
                        <x-ui.button wire:click="generatePdf" variant="primary" size="sm" icon="file-earmark-pdf"
                            pill class="px-3" :disabled="!$categoryFeeId">
                            Exporter PDF
                        </x-ui.button>
                        <x-ui.button wire:click="resetFilters" variant="primary" size="sm" icon="arrow-clockwise"
                            pill outline class="px-3 ms-2">
                            Réinitialiser
                        </x-ui.button>
                    </x-slot:actions>

                    <x-slot:stats>
                        <x-ui.stats-grid :columns="4">
                            <div class="col-md-3">
                                <x-ui.stat-card icon="cash-stack" label="Total Paiements" :value="number_format($totalCount)"
                                    color="primary" />
                            </div>
                            <div class="col-md-3">
                                <x-ui.stat-card icon="check-circle-fill" label="Payés" :value="number_format($statistics['paid_count'])"
                                    color="success" />
                            </div>
                            <div class="col-md-3">
                                <x-ui.stat-card icon="x-circle-fill" label="Non payés" :value="number_format($statistics['unpaid_count'])"
                                    color="danger" />
                            </div>
                            <div class="col-md-3">
                                <x-ui.stat-card icon="graph-up-arrow" label="Taux de Paiement" :value="number_format($statistics['payment_rate'], 1) . '%'"
                                    color="info" />
                            </div>
                        </x-ui.stats-grid>
                    </x-slot:stats>

                    <x-slot:currencies>
                        @if (count($totalsByCurrency) > 0)
                            @foreach ($totalsByCurrency as $currency => $amount)
                                <div class="col-md-4">
                                    <x-ui.currency-card :currency="$currency" :amount="$amount" :decimals="2" />
                                </div>
                            @endforeach
                        @endif
                    </x-slot:currencies>
                </x-ui.page-header>
            </div>
        </div>

        <!-- Filtres principaux -->
        <div class="row mb-4">
            <div class="col-12">
                <x-filters.filter-panel title="Filtres principaux">
                    <x-filters.select-filter model="categoryFeeId" label="Catégorie de frais" :options="$categoryFees"
                        :col="4" required :helpText="!$categoryFeeId ? 'Obligatoire pour afficher les paiements' : null" />

                    <x-filters.date-filter model="date" label="Date" :col="4" />

                    <div class="col-md-2">
                        <x-v2.month-select />
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasFilters" aria-controls="offcanvasFilters" title="Plus de filtres">
                            <i class="bi bi-funnel-fill"> Filtres</i>
                        </button>
                    </div>
                </x-filters.filter-panel>
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

                    <!-- Dates début et fin -->
                    <div class="col-12">
                        <div class="row g-2">
                            <x-filters.date-filter model="dateDebut" label="Date début" :col="6"
                                :disabled="(bool) $dateRange" />
                            <x-filters.date-filter model="dateFin" label="Date fin" :col="6"
                                :disabled="(bool) $dateRange" />
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

                    <!-- Statut et Éléments par page -->
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
                    <x-ui.button wire:click="resetFilters" variant="warning" icon="arrow-clockwise"
                        data-bs-dismiss="offcanvas">
                        Réinitialiser tous les filtres
                    </x-ui.button>
                    <x-ui.button variant="secondary" icon="x-lg" data-bs-dismiss="offcanvas">
                        Fermer
                    </x-ui.button>
                </div>
            </div>
        </div>

        <!-- Tableau des paiements -->
        <div class="row">
            <div class="col-12">
                <x-ui.content-card title="Liste des paiements" icon="table" :badge="$totalCount" noPadding>

                    <!-- Barre de recherche -->
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

                    <!-- Table -->
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
                                                {{ $payment->registration->student->name ?? 'N/A' }}
                                            </div>
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
                                            <span class="fw-bold text-primary">
                                                {{ number_format($payment->scolarFee->amount ?? 0, 1, ',', ' ') }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $payment->scolarFee->categoryFee->currency ?? 'N/A' }}
                                            </small>
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
                                        colspan="8" />
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-3 border-top bg-body-tertiary">
                        <x-v2.pagination-info :paginator="$payments" />
                    </div>
                </x-ui.content-card>
            </div>
        </div>

        <!-- Indicateur de chargement -->
        <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />
    </x-content.main-content-page>
</div>
