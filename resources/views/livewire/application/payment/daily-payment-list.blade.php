{{-- Liste des Paiements - Version Tailwind CSS Moderne --}}
<div class="space-y-4 p-2">
    {{-- Breadcrumb --}}
    {{-- En-tête avec statistiques --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        {{-- Titre et actions --}}
        <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h2 class="text-gray-700 dark:text-gray-200 font-semibold text-lg flex items-center gap-2">
                    <i class="bi bi-cash-stack"></i>
                    Liste des Paiements
                </h2>
                <div class="flex gap-2">
                    <button wire:click="generatePdf"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors flex items-center gap-2"
                        {{ !$filterCategoryFeeId ? 'disabled' : '' }}>
                        <i class="bi bi-file-earmark-pdf"></i> Exporter PDF
                    </button>
                    <button wire:click="resetFilters"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors flex items-center gap-2">
                        <i class="bi bi-arrow-clockwise"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </div>

        {{-- Statistiques rapides --}}
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Paiements --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Total Paiements
                            </p>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ number_format($totalCount) }}
                            </h3>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2">
                            <i class="bi bi-cash-stack text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Payés --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Payés
                            </p>
                            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($statistics['paid_count']) }}
                            </h3>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-2">
                            <i class="bi bi-check-circle-fill text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Non payés --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Non payés
                            </p>
                            <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ number_format($statistics['unpaid_count']) }}
                            </h3>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-2">
                            <i class="bi bi-x-circle-fill text-red-600 dark:text-red-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Taux de Paiement --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p
                                class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Taux de Paiement
                            </p>
                            <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($statistics['payment_rate'], 1) }}%
                            </h3>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2">
                            <i class="bi bi-graph-up-arrow text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Totaux par devise --}}
            @if (count($totalsByCurrency) > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
                    @foreach ($totalsByCurrency as $currency => $amount)
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">
                                        Total {{ $currency }}
                                    </p>
                                    <h5 class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">
                                        {{ number_format($amount, 2, ',', ' ') }}
                                    </h5>
                                </div>
                                <div
                                    class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-md font-medium text-sm">
                                    {{ $currency }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Filtres principaux --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
            <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-base flex items-center gap-2">
                <i class="bi bi-funnel"></i>
                Filtres principaux
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4">
                {{-- Catégorie de frais (OBLIGATOIRE) --}}
                <div class="lg:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span class="text-red-600">*</span> Catégorie de frais
                    </label>
                    <select wire:model.live="filterCategoryFeeId"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($categoryFees as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }} ({{ $category->currency }})
                            </option>
                        @endforeach
                    </select>
                    @if (!$filterCategoryFeeId)
                        <small class="text-red-600 text-xs mt-1 block">
                            Obligatoire pour afficher les paiements
                        </small>
                    @endif
                </div>

                {{-- Date --}}
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                    <input type="date" wire:model.live="date"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                {{-- Mois --}}
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mois</label>
                    <select wire:model.live="month"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Tous les mois</option>
                        <option value="1">Janvier</option>
                        <option value="2">Février</option>
                        <option value="3">Mars</option>
                        <option value="4">Avril</option>
                        <option value="5">Mai</option>
                        <option value="6">Juin</option>
                        <option value="7">Juillet</option>
                        <option value="8">Août</option>
                        <option value="9">Septembre</option>
                        <option value="10">Octobre</option>
                        <option value="11">Novembre</option>
                        <option value="12">Décembre</option>
                    </select>
                </div>

                {{-- Bouton filtres supplémentaires --}}
                <div class="lg:col-span-2 flex items-end">
                    <button
                        class="w-full px-4 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                        type="button" @click="$dispatch('open-filters')">
                        <i class="bi bi-funnel-fill"></i>Filtres
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Offcanvas pour filtres supplémentaires --}}
    <x-offcanvas-filters title="Filtres supplémentaires" icon="bi-funnel-fill">
        {{-- Contenu --}}
        <div class="space-y-4">
            {{-- Plage de dates prédéfinies --}}
            <div>
                <x-v2.date-range-select :dateRange="$dateRange" />
            </div>

            {{-- Dates début et fin --}}
            <div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="bi bi-calendar-event"></i> Date début
                        </label>
                        <input type="date" wire:model.live="dateDebut"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            {{ $dateRange ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="bi bi-calendar-check"></i> Date fin
                        </label>
                        <input type="date" wire:model.live="dateFin"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            {{ $dateRange ? 'disabled' : '' }}>
                    </div>
                </div>
                @if (!$dateRange)
                    <small class="text-gray-500 dark:text-gray-400 block mt-2 text-xs">
                        <i class="bi bi-info-circle"></i> Ou utilisez les dates manuelles
                    </small>
                @endif
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            {{-- Filtres académiques --}}
            <x-v2.academic-filters :sections="$sections" :options="$options" :classRooms="$classRooms" :sectionId="$sectionId"
                :optionId="$optionId" />

            <hr class="border-gray-200 dark:border-gray-700">

            {{-- Statut et Éléments par page --}}
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

        {{-- Actions --}}
        <x-slot name="actions">
            <div class="space-y-2">
                <button
                    class="w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                    wire:click="resetFilters" @click="$dispatch('close-filters')">
                    <i class="bi bi-arrow-clockwise"></i> Réinitialiser tous les filtres
                </button>
                <button
                    class="w-full px-4 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                    @click="$dispatch('close-filters')">
                    <i class="bi bi-x-lg"></i> Fermer
                </button>
            </div>
        </x-slot>
    </x-offcanvas-filters>

    {{-- Tableau des paiements --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
            <div class="flex justify-between items-center">
                <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-base flex items-center gap-2">
                    <i class="bi bi-table"></i>
                    Liste des paiements
                    <span
                        class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-1 rounded-md text-sm font-medium ml-2">
                        {{ $totalCount }}
                    </span>
                </h3>
            </div>
        </div>

        {{-- Barre de recherche --}}
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1 w-full md:w-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Rechercher un élève...">
                    </div>
                </div>

                @if ($filterCategoryFeeId && isset($categoryFees))
                    @php
                        $selectedCategory = collect($categoryFees)->firstWhere('id', $filterCategoryFeeId);
                    @endphp
                    @if ($selectedCategory)
                        <div
                            class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-medium">
                            <i class="bi bi-tag-fill"></i>
                            {{ $selectedCategory->name }} ({{ $selectedCategory->currency }})
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr class="border-b border-gray-200 dark:border-gray-600">
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                            #</th>

                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                            Élève</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                            Catégorie</th>
                        <th
                            class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                            Montant</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                            Mois</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                            Statut</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">
                            Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $loop->iteration + ($currentPage - 1) * 10 }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                                    {{ $payment->registration->student->name ?? 'N/A' }}
                                </div>
                                <small class="text-gray-500 dark:text-gray-400 text-xs flex items-center gap-1 mt-0.5">
                                    <i class="bi bi-diagram-3"></i>
                                    {{ ($payment->registration->classRoom->name ?? 'N/A') . ' - ' . ($payment->registration->classRoom->option->name ?? 'N/A') }}
                                </small>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-md text-xs font-medium">
                                    {{ $payment->scolarFee->categoryFee->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-semibold text-blue-600 dark:text-blue-400 text-sm">
                                    {{ number_format($payment->scolarFee->amount ?? 0, 1, ',', ' ') }}
                                </span>
                                <small class="text-gray-500 dark:text-gray-400 ml-1 text-xs">
                                    {{ $payment->scolarFee->categoryFee->currency ?? 'N/A' }}
                                </small>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 px-2 py-1 rounded-md text-xs font-medium">
                                    {{ format_fr_month_name($payment->month) ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($payment->is_paid)
                                    <span
                                        class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-2 py-1 rounded-full text-xs font-medium border border-green-300 dark:border-green-700">
                                        <i class="bi bi-check-circle-fill"></i> Payé
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-2 py-1 rounded-full text-xs font-medium border border-red-300 dark:border-red-700">
                                        <i class="bi bi-x-circle-fill"></i> Non payé
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <small
                                    class="text-gray-500 dark:text-gray-400 text-xs flex items-center justify-center gap-1">
                                    <i class="bi bi-clock"></i>
                                    {{ $payment->created_at->format('d/m/Y H:i') }}
                                </small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-4 mb-3">
                                        <i class="bi bi-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                                        Aucun paiement trouvé avec ces critères
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <x-v2.pagination-info :paginator="$paymentsPaginated" />
        </div>
    </div>

    {{-- Indicateur de chargement --}}
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />
</div>
