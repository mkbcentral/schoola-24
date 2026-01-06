<div>
    <x-navigation.bread-crumb icon='bi bi-list-check' label="Liste des Paiements">
        <x-navigation.bread-crumb-item label='Paiements' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- En-tête avec statistiques -->
        <div class="mb-6">
            <div class="card border-0 shadow-sm bg-blue-50 dark:bg-blue-900/20">
                <div class="card-body p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <h5 class="text-xl font-normal text-blue-600 dark:text-blue-400 flex items-center gap-2 m-0">
                            <i class="bi bi-cash-stack"></i>Liste des Paiements
                        </h5>
                        <div class="flex gap-2">
                            <button wire:click="generatePdf" 
                                class="btn btn-sm btn-primary rounded-full px-4"
                                @if (!$categoryFeeId) disabled @endif>
                                <i class="bi bi-file-earmark-pdf mr-1"></i> Exporter PDF
                            </button>
                            <button wire:click="resetFilters"
                                class="btn btn-sm btn-outline-primary rounded-full px-4">
                                <i class="bi bi-arrow-clockwise mr-1"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div class="card border-0 shadow-sm h-full">
                            <div class="card-body p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 mb-1 uppercase text-xs tracking-wider">
                                            Total Paiements
                                        </p>
                                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 m-0">
                                            {{ number_format($totalCount) }}
                                        </h3>
                                    </div>
                                    <div class="rounded-full bg-blue-100 dark:bg-blue-900/50 p-3">
                                        <i class="bi bi-cash-stack text-blue-600 dark:text-blue-400 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm h-full">
                            <div class="card-body p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 mb-1 uppercase text-xs tracking-wider">
                                            Payés
                                        </p>
                                        <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 m-0">
                                            {{ number_format($statistics['paid_count']) }}
                                        </h3>
                                    </div>
                                    <div class="rounded-full bg-green-100 dark:bg-green-900/50 p-3">
                                        <i class="bi bi-check-circle-fill text-green-600 dark:text-green-400 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm h-full">
                            <div class="card-body p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 mb-1 uppercase text-xs tracking-wider">
                                            Non payés
                                        </p>
                                        <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 m-0">
                                            {{ number_format($statistics['unpaid_count']) }}
                                        </h3>
                                    </div>
                                    <div class="rounded-full bg-red-100 dark:bg-red-900/50 p-3">
                                        <i class="bi bi-x-circle-fill text-red-600 dark:text-red-400 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm h-full">
                            <div class="card-body p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 mb-1 uppercase text-xs tracking-wider">
                                            Taux de Paiement
                                        </p>
                                        <h3 class="text-2xl font-bold text-cyan-600 dark:text-cyan-400 m-0">
                                            {{ number_format($statistics['payment_rate'], 1) }}%
                                        </h3>
                                    </div>
                                    <div class="rounded-full bg-cyan-100 dark:bg-cyan-900/50 p-3">
                                        <i class="bi bi-graph-up-arrow text-cyan-600 dark:text-cyan-400 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Totaux par devise -->
                    @if (count($totalsByCurrency) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
                            @foreach ($totalsByCurrency as $currency => $amount)
                                <div class="card border-0 shadow-sm bg-gray-100 dark:bg-gray-800">
                                    <div class="card-body p-4">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <small class="text-gray-500 dark:text-gray-400 block text-sm">
                                                    Total {{ $currency }}
                                                </small>
                                                <h5 class="text-xl font-bold text-gray-900 dark:text-gray-100 m-0 mt-1">
                                                    {{ number_format($amount, 2, ',', ' ') }}
                                                </h5>
                                            </div>
                                            <div class="badge badge-primary rounded-full px-4 py-2">
                                                {{ $currency }}
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

        <!-- Filtres principaux (toujours visibles) -->
        <div class="mb-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-blue-50 dark:bg-blue-900/20 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-2 m-0">
                        <i class="bi bi-funnel"></i>
                        Filtres principaux
                    </h5>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4">
                        <!-- Catégorie de frais (OBLIGATOIRE) -->
                        <div class="lg:col-span-4">
                            <label class="form-label">
                                <span class="text-red-600">*</span> Catégorie de frais
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
                                <small class="text-red-600 text-sm mt-1 block">
                                    Obligatoire pour afficher les paiements
                                </small>
                            @endif
                        </div>

                        <!-- Date -->
                        <div class="lg:col-span-3">
                            <label class="form-label">Date</label>
                            <input type="date" wire:model.live="date" class="form-control">
                        </div>

                        <!-- Mois -->
                        <div class="lg:col-span-3">
                            <x-v2.month-select />
                        </div>

                        <!-- Bouton pour filtres supplémentaires -->
                        <div class="lg:col-span-2 flex items-end">
                            <button class="btn btn-outline-secondary w-full" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilters"
                                aria-controls="offcanvasFilters" title="Plus de filtres">
                                <i class="bi bi-funnel-fill mr-2"></i>Filtres
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offcanvas pour filtres supplémentaires -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilters"
            aria-labelledby="offcanvasFiltersLabel" wire:ignore.self>
            <div class="offcanvas-header bg-blue-50 dark:bg-blue-900/20 border-b border-gray-200 dark:border-gray-700">
                <h5 class="offcanvas-title text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-2" 
                    id="offcanvasFiltersLabel">
                    <i class="bi bi-funnel-fill"></i> Filtres supplémentaires
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="space-y-4">
                    <!-- Plage de dates prédéfinies -->
                    <div>
                        <x-v2.date-range-select :dateRange="$dateRange" />
                    </div>

                    <!-- Dates début et fin -->
                    <div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label font-semibold">
                                    <i class="bi bi-calendar-event"></i> Date début
                                </label>
                                <input type="date" wire:model.live="dateDebut" class="form-control"
                                    {{ $dateRange ? 'disabled' : '' }}>
                            </div>
                            <div>
                                <label class="form-label font-semibold">
                                    <i class="bi bi-calendar-check"></i> Date fin
                                </label>
                                <input type="date" wire:model.live="dateFin" class="form-control"
                                    {{ $dateRange ? 'disabled' : '' }}>
                            </div>
                        </div>
                        @if (!$dateRange)
                            <small class="text-gray-500 dark:text-gray-400 block mt-2 text-sm">
                                <i class="bi bi-info-circle"></i> Ou utilisez les dates manuelles
                            </small>
                        @endif
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700">

                    <!-- Filtres académiques -->
                    <x-v2.academic-filters :sections="$sections" :options="$options" :classRooms="$classRooms" 
                        :sectionId="$sectionId" :optionId="$optionId" />

                    <hr class="border-gray-200 dark:border-gray-700">

                    <!-- Statut et Éléments par page -->
                    <div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <x-v2.payment-status-select />
                            </div>
                            <div>
                                <x-v2.per-page-select />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <button class="btn btn-warning w-full" wire:click="resetFilters" data-bs-dismiss="offcanvas">
                        <i class="bi bi-arrow-clockwise"></i> Réinitialiser tous les filtres
                    </button>
                    <button class="btn btn-secondary w-full" data-bs-dismiss="offcanvas">
                        <i class="bi bi-x-lg"></i> Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Tableau des paiements -->
        <div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h5 class="font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2 m-0">
                            <i class="bi bi-table"></i>
                            Liste des paiements
                            <span class="badge badge-primary ml-2">{{ $totalCount }}</span>
                        </h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Barre de recherche avec catégorie sélectionnée -->
                    <div class="p-4 bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex-1 w-full md:w-auto">
                                <x-v2.search-bar placeholder="Rechercher un élève..." :resultCount="$totalCount"
                                    :searchTerm="$search" />
                            </div>

                            @if ($categoryFeeId && isset($categoryFees))
                                @php
                                    $selectedCategory = collect($categoryFees)->firstWhere('id', $categoryFeeId);
                                @endphp
                                @if ($selectedCategory)
                                    <div class="badge badge-primary rounded-full px-4 py-2 flex items-center gap-2">
                                        <i class="bi bi-tag-fill"></i>
                                        {{ $selectedCategory->name }} ({{ $selectedCategory->currency }})
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table table-hover mb-0">
                            <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-gray-600 dark:text-gray-400 font-semibold">#</th>
                                    <th class="py-3 text-gray-600 dark:text-gray-400 font-semibold">N° Paiement</th>
                                    <th class="py-3 text-gray-600 dark:text-gray-400 font-semibold">Élève</th>
                                    <th class="py-3 text-gray-600 dark:text-gray-400 font-semibold">Catégorie</th>
                                    <th class="py-3 text-right text-gray-600 dark:text-gray-400 font-semibold">Montant</th>
                                    <th class="py-3 text-center text-gray-600 dark:text-gray-400 font-semibold">Mois</th>
                                    <th class="py-3 text-center text-gray-600 dark:text-gray-400 font-semibold">Statut</th>
                                    <th class="py-3 text-center text-gray-600 dark:text-gray-400 font-semibold">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}
                                        </td>
                                        <td class="py-3">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $payment->payment_number }}
                                            </div>
                                            <small class="text-gray-500 dark:text-gray-400 text-sm flex items-center gap-1">
                                                <i class="bi bi-calendar3"></i>
                                                {{ $payment->created_at->format('d/m/Y') ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="py-3">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $payment->registration->student->name ?? 'N/A' }}
                                            </div>
                                            <small class="text-gray-500 dark:text-gray-400 text-sm flex items-center gap-1">
                                                <i class="bi bi-diagram-3"></i>
                                                {{ ($payment->registration->classRoom->name ?? 'N/A') . ' - ' . ($payment->registration->classRoom->option->name ?? 'N/A') }}
                                            </small>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                {{ $payment->scolarFee->categoryFee->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-right">
                                            <span class="font-bold text-blue-600 dark:text-blue-400">
                                                {{ number_format($payment->scolarFee->amount ?? 0, 1, ',', ' ') }}
                                            </span>
                                            <small class="text-gray-500 dark:text-gray-400 ml-1">
                                                {{ $payment->scolarFee->categoryFee->currency ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-cyan-100 dark:bg-cyan-900 text-cyan-700 dark:text-cyan-300">
                                                {{ format_fr_month_name($payment->month) ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-center">
                                            @if ($payment->is_paid)
                                                <span class="badge badge-success rounded-full border border-green-500">
                                                    <i class="bi bi-check-circle-fill"></i> Payé
                                                </span>
                                            @else
                                                <span class="badge badge-danger rounded-full border border-red-500">
                                                    <i class="bi bi-x-circle-fill"></i> Non payé
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-center">
                                            <small class="text-gray-500 dark:text-gray-400 text-sm flex items-center justify-center gap-1">
                                                <i class="bi bi-clock"></i>
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
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <x-v2.pagination-info :paginator="$payments" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicateur de chargement -->
        <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />
    </x-content.main-content-page>
</div>
