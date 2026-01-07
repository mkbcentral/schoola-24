{{-- Gestion des Dépenses - Version Tailwind CSS Pure --}}
<div x-data="{
    showExpenseModal: false,
    showFilters: false,
    expenseType: @entangle('expenseType').live
}">
    {{-- Breadcrumb --}}
    <x-navigation.bread-crumb icon='bi bi-cash-stack' label="Gestion des Dépenses">
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="finance.dashboard" isFirst=true />
        <x-navigation.bread-crumb-item label='Dépenses' />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="space-y-4 max-w-full overflow-hidden">
            {{-- En-tête avec actions --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div>
                        <h2 class="text-gray-700 dark:text-gray-200 font-semibold text-lg flex items-center gap-2">
                            <i class="bi bi-cash-stack text-blue-600 dark:text-blue-400"></i>
                            Gestion des Dépenses
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Gérer et suivre toutes les dépenses de l'établissement
                        </p>
                    </div>
                    <div class="flex gap-2 flex-wrap items-center">
                        {{-- Switch Type de Dépense --}}
                        <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                            <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">Type:</span>
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" wire:model.live="expenseType" value="fee" class="sr-only peer"
                                    {{ $expenseType === 'fee' ? 'checked' : '' }}>
                                <label for="expenseType" wire:click="switchExpenseType('{{ $expenseType === 'fee' ? 'other' : 'fee' }}')"
                                    class="cursor-pointer w-16 h-8 rounded-full shadow-inner transition-all duration-300"
                                    :class="expenseType === 'fee' ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-yellow-400 to-orange-500'">
                                    <div class="absolute top-0.5 left-0.5 bg-white rounded-full h-7 w-7 shadow-md transition-transform duration-300 flex items-center justify-center"
                                        :class="expenseType === 'fee' ? 'translate-x-8' : 'translate-x-0'">
                                        <i class="bi text-xs" :class="expenseType === 'fee' ? 'bi-check-circle-fill text-green-600' : 'bi-circle text-yellow-600'"></i>
                                    </div>
                                </label>
                            </div>
                            <span class="text-xs font-medium transition-all duration-200"
                                :class="expenseType === 'fee' ? 'text-green-700 dark:text-green-400' : 'text-yellow-700 dark:text-yellow-400'">
                                <i class="bi mr-1" :class="expenseType === 'fee' ? 'bi-mortarboard' : 'bi-wallet2'"></i>
                                <span x-text="expenseType === 'fee' ? 'Frais Scolaires' : 'Autres Dépenses'"></span>
                            </span>
                        </div>

                        <button @click="showFilters = !showFilters"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-funnel"></i>
                            <span x-text="showFilters ? 'Masquer filtres' : 'Afficher filtres'"></span>
                        </button>
                        <button wire:click="openCreateModal"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-plus-circle"></i> Nouvelle Dépense
                        </button>
                    </div>
                </div>
            </div>

            {{-- Filtres --}}
            <div x-show="showFilters"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                    <h3 class="text-white font-semibold text-base flex items-center gap-2">
                        <i class="bi bi-sliders"></i> Filtres de recherche
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        {{-- Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date spécifique
                            </label>
                            <input type="date" wire:model.live="date"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Période --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Période prédéfinie
                            </label>
                            <select wire:model.live="filterPeriod"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Toutes les périodes</option>
                                <option value="today">Aujourd'hui</option>
                                <option value="week">Cette semaine</option>
                                <option value="month">Ce mois</option>
                                <option value="year">Cette année</option>
                            </select>
                        </div>

                        {{-- Devise --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-currency-exchange"></i> Devise
                            </label>
                            <select wire:model.live="filterCurrency"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Toutes les devises</option>
                                <option value="USD">USD</option>
                                <option value="CDF">CDF</option>
                            </select>
                        </div>

                        {{-- Catégorie générale --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-tag-fill"></i> Catégorie générale
                            </label>
                            <select wire:model.live="filterCategoryExpense"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Toutes les catégories</option>
                                @foreach ($categoryExpenses as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtres spécifiques selon le type --}}
                        @if($expenseType === 'fee')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Catégorie frais
                                </label>
                                <select wire:model.live="filterCategoryFee"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Toutes les catégories</option>
                                    @foreach ($categoryFees as $fee)
                                        <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Mois
                                </label>
                                <select wire:model.live="filterMonth"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Tous les mois</option>
                                    @foreach(['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'] as $index => $month)
                                        <option value="{{ $index + 1 }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Source de dépense
                                </label>
                                <select wire:model.live="filterOtherSource"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Toutes les sources</option>
                                    @foreach ($otherSources as $source)
                                        <option value="{{ $source->id }}">{{ $source->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- Statut validation --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Statut de validation
                            </label>
                            <select wire:model.live="filterIsValidated"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Tous les statuts</option>
                                <option value="1">Validées</option>
                                <option value="0">Non validées</option>
                            </select>
                        </div>

                        {{-- Plage personnalisée --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Plage de dates
                            </label>
                            <select wire:model.live="dateRange"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">Aucune</option>
                                <option value="custom">Période personnalisée</option>
                            </select>
                        </div>

                        @if ($dateRange === 'custom')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date début
                                </label>
                                <input type="date" wire:model.live="dateDebut"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date fin
                                </label>
                                <input type="date" wire:model.live="dateFin"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        @endif

                        {{-- Bouton reset --}}
                        <div class="flex items-end">
                            <button wire:click="resetFilters"
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="bi bi-x-circle"></i> Réinitialiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistiques --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" wire:loading.remove.delay.longer>
                {{-- Nombre de dépenses --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Nombre de dépenses
                            </p>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $statistics['count'] }}
                            </h3>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                            <i class="bi bi-cash-stack text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Total USD --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Total USD
                            </p>
                            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ app_format_number($statistics['totalUSD'], 2) }}
                            </h3>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                            <i class="bi bi-currency-dollar text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Total CDF --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Total CDF
                            </p>
                            <h3 class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                {{ app_format_number($statistics['totalCDF'], 0) }}
                            </h3>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-3">
                            <i class="bi bi-cash text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Montant moyen --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Montant moyen
                            </p>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ app_format_number($statistics['averageAmount'], 2) }}
                            </h3>
                        </div>
                        <div class="bg-orange-100 dark:bg-orange-900/30 rounded-lg p-3">
                            <i class="bi bi-graph-up text-orange-600 dark:text-orange-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tableau des dépenses --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden" wire:loading.remove.delay.longer>
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-base flex items-center gap-2">
                            Liste des dépenses
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                {{ $expenses->total() }}
                            </span>
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Par page:</span>
                            <select wire:model.live="perPage"
                                class="px-3 py-1.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Catégorie</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Montant</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Devise</th>
                                @if ($expenseType === 'fee')
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Mois</th>
                                @endif
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Validation</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($expenses as $expense)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $expense->is_validated ? 'bg-green-50/30 dark:bg-green-900/10' : '' }}">
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $loop->iteration + ($expenses->currentPage() - 1) * $expenses->perPage() }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-calendar3 text-gray-400"></i>
                                            {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="max-w-xs truncate" title="{{ $expense->description }}">
                                            {{ Str::limit($expense->description, 25) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            @if ($expenseType === 'fee')
                                                {{ $expense->categoryFee->name ?? 'N/A' }}
                                            @else
                                                {{ $expense->categoryExpense->name ?? 'N/A' }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-100 text-right">
                                        {{ app_format_number($expense->amount, $expense->currency === 'USD' ? 2 : 0) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $expense->currency === 'USD' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' }}">
                                            {{ $expense->currency }}
                                        </span>
                                    </td>
                                    @if ($expenseType === 'fee')
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                {{ $expense->month ?? 'N/A' }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-4 py-3 text-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:change="toggleValidation({{ $expense->id }})"
                                                {{ $expense->is_validated ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-checked:bg-green-500 rounded-full peer peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 transition-all duration-300">
                                                <div class="absolute top-0.5 left-0.5 bg-white rounded-full h-5 w-5 shadow-md transition-transform duration-300 peer-checked:translate-x-5 flex items-center justify-center">
                                                    <i class="bi text-[10px] {{ $expense->is_validated ? 'bi-check-lg text-green-600' : 'bi-x-lg text-gray-400' }}"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="openEditModal({{ $expense->id }})"
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg transition-colors"
                                                title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $expense->id }})"
                                                class="inline-flex items-center px-2 py-1 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-colors"
                                                title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $expenseType === 'fee' ? '9' : '8' }}" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-6 w-20 h-20 flex items-center justify-center">
                                                <i class="bi bi-inbox text-gray-400 text-4xl"></i>
                                            </div>
                                            <h5 class="text-gray-700 dark:text-gray-300 font-semibold text-lg">
                                                Aucune dépense trouvée
                                            </h5>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                                Commencez par ajouter votre première dépense
                                            </p>
                                            <button wire:click="openCreateModal"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                                                <i class="bi bi-plus-circle"></i> Ajouter une dépense
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($expenses->hasPages())
                    <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            {{-- Informations de pagination --}}
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Affichage de
                                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $expenses->firstItem() }}</span>
                                à
                                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $expenses->lastItem() }}</span>
                                sur
                                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $expenses->total() }}</span>
                                résultats
                            </div>

                            {{-- Navigation de pagination --}}
                            <nav class="flex items-center gap-1">
                                {{-- Bouton Précédent --}}
                                @if ($expenses->onFirstPage())
                                    <span class="px-3 py-2 text-sm font-medium text-gray-400 dark:text-gray-600 bg-gray-100 dark:bg-gray-800 rounded-lg cursor-not-allowed">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                                @else
                                    <button wire:click="previousPage" wire:loading.attr="disabled"
                                        class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                @endif

                                {{-- Numéros de page --}}
                                @foreach ($expenses->getUrlRange(max(1, $expenses->currentPage() - 2), min($expenses->lastPage(), $expenses->currentPage() + 2)) as $page => $url)
                                    @if ($page == $expenses->currentPage())
                                        <span class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-200">
                                            {{ $page }}
                                        </button>
                                    @endif
                                @endforeach

                                {{-- Bouton Suivant --}}
                                @if ($expenses->hasMorePages())
                                    <button wire:click="nextPage" wire:loading.attr="disabled"
                                        class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                @else
                                    <span class="px-3 py-2 text-sm font-medium text-gray-400 dark:text-gray-600 bg-gray-100 dark:bg-gray-800 rounded-lg cursor-not-allowed">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Taux de change --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg shadow border border-blue-200 dark:border-blue-800 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-currency-exchange text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Taux de change actuel</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            1 USD = {{ app_format_number($currentRate, 0) }} CDF
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Équivalence</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                            {{ app_format_number($statistics['totalUSD'] * $currentRate, 0) }} CDF
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Ajouter/Modifier Dépense --}}
        @if($showExpenseModal)
            <div class="fixed inset-0 z-50 overflow-y-auto"
                 x-data="{ show: true }"
                 x-init="show = true"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 aria-labelledby="modal-title"
                 role="dialog"
                 aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Overlay --}}
                    <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     @click="$wire.closeExpenseModal()"></div>

                {{-- Center modal --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                     @click.stop>

                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                                <i class="bi bi-cash-stack"></i>
                                <span x-text="$wire.expenseId ? 'Modifier la dépense' : 'Nouvelle dépense'"></span>
                            </h3>
                            <button @click="showExpenseModal = false" class="text-white hover:text-gray-200 transition-colors">
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Date --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="date"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Montant --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Montant <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" wire:model="amount"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="0.00">
                                @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Devise --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Devise <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="currency"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Sélectionner...</option>
                                    <option value="USD">USD</option>
                                    <option value="CDF">CDF</option>
                                </select>
                                @error('currency') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Catégorie générale --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Catégorie générale <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="categoryExpenseId"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Sélectionner...</option>
                                    @foreach ($categoryExpenses as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoryExpenseId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Catégorie frais (si type = fee) --}}
                            @if($expenseType === 'fee')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Catégorie frais <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="categoryFeeId"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Sélectionner...</option>
                                        @foreach ($categoryFees as $fee)
                                            <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('categoryFeeId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mois <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="month"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Sélectionner...</option>
                                        @foreach(['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'] as $index => $monthName)
                                            <option value="{{ $index + 1 }}">{{ $monthName }}</option>
                                        @endforeach
                                    </select>
                                    @error('month') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Source de dépense <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="otherSourceId"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Sélectionner...</option>
                                        @foreach ($otherSources as $source)
                                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('otherSourceId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="description" rows="3"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Décrivez la dépense..."></textarea>
                                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex flex-col sm:flex-row gap-3 justify-end">
                        <button wire:click="closeExpenseModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors">
                            <i class="bi bi-x-circle mr-2"></i> Annuler
                        </button>
                        <button wire:click="saveExpense"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveExpense">
                                <i class="bi bi-check-circle mr-2"></i>
                                <span x-text="$wire.expenseId ? 'Mettre à jour' : 'Enregistrer'"></span>
                            </span>
                            <span wire:loading wire:target="saveExpense">
                                <i class="bi bi-arrow-repeat animate-spin mr-2"></i> Enregistrement...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Indicateur de chargement --}}
        <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />
    </x-content.main-content-page>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@script
    <script>
        // Expense Management - SweetAlert pour confirmation de suppression
        $wire.on('delete-expense-dialog', (event) => {
            const data = event[0];
            const amountFormatted = new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: data.currency === 'USD' ? 2 : 0,
                maximumFractionDigits: data.currency === 'USD' ? 2 : 0
            }).format(data.amount);

            Swal.fire({
                title: 'Voulez-vous vraiment supprimer?',
                html: `<div class="text-start">
                    <p class="mb-2"><strong>Description:</strong> ${data.description}</p>
                    <p class="mb-2"><strong>Montant:</strong> ${amountFormatted} ${data.currency}</p>
                    <p class="mb-2"><strong>Catégorie:</strong> ${data.category}</p>
                    <p class="mb-2"><strong>Mois:</strong> ${data.month}</p>
                    <p class="text-danger mt-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cette action est irréversible!</p>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-2"></i>Oui, supprimer',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Annuler',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteExpense(data.expenseId);
                }
            });
        });

        $wire.on('expense-deleted', (event) => {
            Swal.fire({
                title: 'Suppression réussie!',
                text: event[0].message,
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        });

        $wire.on('delete-expense-failed', (event) => {
            Swal.fire({
                title: 'Erreur!',
                text: event[0].message,
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endscript

@push('styles')
    <style>
        /* Toggle switch amélioré pour les dépenses - Version Tailwind */
        .expense-toggle-switch {
            width: 3.5em !important;
            height: 1.8em !important;
            min-width: 3.5em !important;
            cursor: pointer !important;
            border-radius: 2em !important;
            transition: all 0.3s ease !important;
            background-size: contain !important;
        }

        .expense-toggle-switch:checked {
            background-color: #22c55e !important;
            border-color: #22c55e !important;
        }

        .expense-toggle-switch:not(:checked) {
            background-color: #eab308 !important;
            border-color: #eab308 !important;
        }

        .expense-toggle-switch:focus {
            box-shadow: 0 0 0 0.25rem rgba(34, 197, 94, 0.25) !important;
            border-color: #22c55e !important;
        }

        .expense-toggle-switch:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
        }

        /* Effet sur la ligne validée */
        tr:has(.expense-toggle-switch:checked) {
            background-color: rgba(34, 197, 94, 0.05) !important;
        }

        tr:has(.expense-toggle-switch):hover {
            background-color: rgba(0, 0, 0, 0.025) !important;
        }

        tr:has(.expense-toggle-switch:checked):hover {
            background-color: rgba(34, 197, 94, 0.08) !important;
        }

        /* Dark mode */
        .dark tr:has(.expense-toggle-switch:checked) {
            background-color: rgba(34, 197, 94, 0.1) !important;
        }

        .dark tr:has(.expense-toggle-switch):hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .dark tr:has(.expense-toggle-switch:checked):hover {
            background-color: rgba(34, 197, 94, 0.15) !important;
        }
    </style>
@endpush
