<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">
    {{-- Header avec breadcrumb --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 backdrop-blur-sm bg-white/95 dark:bg-gray-800/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="bi bi-cash-stack text-white text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Gestion des Dépenses</h1>
            </div>
            <nav class="flex items-center gap-2 text-sm">
                <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <i class="bi bi-house-door"></i>
                </a>
                <i class="bi bi-chevron-right text-gray-400 text-xs"></i>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Dépenses</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        {{-- Switch entre types de dépenses --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Type de dépense</label>
                    <div class="relative inline-flex items-center" x-data="{ checked: @entangle('expenseType').live === 'school_fee' }">
                        <input type="checkbox" 
                            wire:model.live="expenseType" 
                            class="sr-only peer"
                            value="school_fee"
                            x-model="checked">
                        <div class="w-20 h-10 rounded-full shadow-inner cursor-pointer transition-all duration-300 peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800"
                            :class="checked ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-yellow-400 to-orange-500'">
                            <div class="absolute top-1 left-1 bg-white rounded-full h-8 w-8 shadow-md transition-transform duration-300 flex items-center justify-center"
                                :class="checked ? 'translate-x-10' : 'translate-x-0'">
                                <i class="bi" :class="checked ? 'bi-check-circle-fill text-green-600' : 'bi-circle text-yellow-600'"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200"
                            :class="$wire.expenseType === 'other' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : 'text-gray-500 dark:text-gray-400'">
                            <i class="bi bi-wallet2 mr-1"></i>
                            Autres Dépenses
                        </span>
                        <span class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200"
                            :class="$wire.expenseType === 'school_fee' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'text-gray-500 dark:text-gray-400'">
                            <i class="bi bi-mortarboard mr-1"></i>
                            Dépenses Frais Scolaires
                        </span>
                    </div>
                </div>
                <button wire:click="openCreateModal"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 group">
                    <i class="bi bi-plus-circle text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Nouvelle Dépense</span>
                </button>
            </div>
        </div>

        {{-- Statistiques --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Dépenses --}}
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 opacity-10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-cash-stack text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">
                        Nombre de dépenses
                    </h3>
                    <p class="text-3xl font-black text-gray-900 dark:text-gray-100">
                        {{ $statistics['count'] }}
                    </p>
                </div>
            </div>

            {{-- Total USD --}}
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500 to-emerald-600 opacity-10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-currency-dollar text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-medium text-gray-600 dark:text-gray-400">
                            USD
                        </span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">
                        Total USD
                    </h3>
                    <p class="text-3xl font-black text-gray-900 dark:text-gray-100">
                        {{ app_format_number($statistics['totalUSD'], 2) }}
                    </p>
                </div>
            </div>

            {{-- Total CDF --}}
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500 to-pink-600 opacity-10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-cash text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-medium text-gray-600 dark:text-gray-400">
                            CDF
                        </span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">
                        Total CDF
                    </h3>
                    <p class="text-3xl font-black text-gray-900 dark:text-gray-100">
                        {{ app_format_number($statistics['totalCDF'], 0) }}
                    </p>
                </div>
            </div>

            {{-- Montant moyen --}}
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-500 to-red-600 opacity-10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="bi bi-graph-up text-orange-600 dark:text-orange-400 text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">
                        Montant moyen
                    </h3>
                    <p class="text-3xl font-black text-gray-900 dark:text-gray-100">
                        {{ app_format_number($statistics['averageAmount'], 2) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Filtres rapides --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-funnel text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Filtres rapides</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="bi bi-calendar3 mr-1"></i>Date
                    </label>
                    <input type="date" 
                        wire:model.live="date"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
                </div>

                {{-- Période --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="bi bi-clock-history mr-1"></i>Période
                    </label>
                    <select wire:model.live="filterPeriod"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
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
                        <i class="bi bi-currency-exchange mr-1"></i>Devise
                    </label>
                    <select wire:model.live="filterCurrency"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
                        <option value="">Toutes les devises</option>
                        <option value="USD">USD</option>
                        <option value="CDF">CDF</option>
                    </select>
                </div>

                {{-- Catégorie --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="bi bi-tag mr-1"></i>Catégorie
                    </label>
                    <select wire:model.live="filterCategoryExpense"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categoryExpenses as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Bouton filtres avancés --}}
            <div class="mt-4 flex items-center justify-between">
                <button 
                    type="button"
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#advancedFiltersOffcanvas"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200 flex items-center gap-2">
                    <i class="bi bi-sliders"></i>
                    <span>Filtres avancés</span>
                </button>
                
                @if ($date || $filterPeriod || $filterCurrency || $filterCategoryExpense)
                    <button wire:click="resetFilters"
                        class="px-4 py-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 font-medium rounded-xl transition-all duration-200 flex items-center gap-2">
                        <i class="bi bi-x-circle"></i>
                        <span>Réinitialiser</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Tableau des dépenses --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="bi bi-table text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Liste des dépenses</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $expenses->total() }} dépense(s) trouvée(s)</p>
                        </div>
                    </div>
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
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Devise</th>
                            @if ($expenseType === 'school_fee')
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Mois</th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Validation</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($expenses as $expense)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150 {{ $expense->is_validated ? 'bg-green-50/50 dark:bg-green-900/10' : '' }}">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $loop->iteration + ($expenses->currentPage() - 1) * $expenses->perPage() }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-calendar3 text-gray-400"></i>
                                        {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="max-w-xs truncate" title="{{ $expense->description }}">
                                        {{ $expense->description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        <i class="bi bi-tag-fill mr-1"></i>
                                        @if ($expenseType === 'school_fee')
                                            {{ $expense->categoryFee->name ?? 'N/A' }}
                                        @else
                                            {{ $expense->categoryExpense->name ?? 'N/A' }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">
                                    {{ app_format_number($expense->amount, $expense->currency === 'USD' ? 2 : 0) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $expense->currency === 'USD' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' }}">
                                        {{ $expense->currency }}
                                    </span>
                                </td>
                                @if ($expenseType === 'school_fee')
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $expense->month ?? 'N/A' }}
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                        <input type="checkbox" 
                                            wire:change="toggleValidation({{ $expense->id }})"
                                            {{ $expense->is_validated ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div class="w-14 h-7 bg-gradient-to-r from-gray-300 to-gray-400 peer-checked:from-green-400 peer-checked:to-emerald-500 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 transition-all duration-300 shadow-inner">
                                            <div class="absolute top-0.5 left-0.5 bg-white rounded-full h-6 w-6 shadow-md transition-transform duration-300 peer-checked:translate-x-7 flex items-center justify-center">
                                                <i class="bi text-xs {{ $expense->is_validated ? 'bi-check-lg text-green-600' : 'bi-x-lg text-gray-400' }}"></i>
                                            </div>
                                        </div>
                                    </label>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="openEditModal({{ $expense->id }})"
                                            class="p-2 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-lg transition-all duration-200 hover:scale-110"
                                            title="Modifier">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $expense->id }})"
                                            class="p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-lg transition-all duration-200 hover:scale-110"
                                            title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $expenseType === 'school_fee' ? '9' : '8' }}" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <i class="bi bi-inbox text-gray-400 text-3xl"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Aucune dépense trouvée</p>
                                        <button wire:click="openCreateModal"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                            <i class="bi bi-plus-circle mr-2"></i>Ajouter une dépense
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
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

        {{-- Taux de change --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl shadow-lg border border-blue-200 dark:border-blue-800 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="bi bi-currency-exchange text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Taux de change actuel</p>
                    <p class="text-2xl font-black text-blue-900 dark:text-blue-100">
                        1 USD = {{ app_format_number($currentRate, 0) }} CDF
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal formulaire de dépense avec Alpine.js --}}
    <div x-data="{ 
        showExpenseModal: false,
        expenseType: @entangle('expenseType').live,
        formData: @entangle('expenseFormData').live
    }"
        x-show="showExpenseModal"
        @open-expense-form-modal.window="showExpenseModal = true"
        @close-expense-form-modal.window="showExpenseModal = false"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gradient-to-br from-gray-900/80 via-blue-900/50 to-purple-900/60 backdrop-blur-md transition-opacity"
            @click="showExpenseModal = false"></div>

        {{-- Modal Container --}}
        <div class="flex min-h-screen items-center justify-center p-4">
            <div @click.away="showExpenseModal = false"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden border border-gray-200 dark:border-gray-700">
                
                {{-- Header --}}
                <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-purple-600 px-6 py-5 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/5"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-40 h-40 bg-black/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                    
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <i class="bi bi-receipt-cutoff text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white" x-text="expenseType === 'school_fee' ? 'Dépense sur Frais Scolaires' : 'Autre Dépense'"></h3>
                                <p class="text-white/80 text-sm">Remplissez le formulaire ci-dessous</p>
                            </div>
                        </div>
                        <button @click="showExpenseModal = false"
                            class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-110">
                            <i class="bi bi-x-lg text-white text-xl"></i>
                        </button>
                    </div>
                </div>

                {{-- Body avec formulaire --}}
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]" wire:loading.class="opacity-50">
                    <form wire:submit.prevent="saveExpense" class="space-y-5">
                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-pencil mr-1"></i>Description
                            </label>
                            <textarea wire:model="expenseFormData.description"
                                rows="3"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 resize-none transition-all duration-200"
                                placeholder="Entrez une description détaillée..."></textarea>
                            @error('expenseFormData.description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Mois --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="bi bi-calendar mr-1"></i>Mois
                                </label>
                                <select wire:model="expenseFormData.month"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
                                    <option value="">Sélectionner</option>
                                    <option value="Janvier">Janvier</option>
                                    <option value="Février">Février</option>
                                    <option value="Mars">Mars</option>
                                    <option value="Avril">Avril</option>
                                    <option value="Mai">Mai</option>
                                    <option value="Juin">Juin</option>
                                    <option value="Juillet">Juillet</option>
                                    <option value="Août">Août</option>
                                    <option value="Septembre">Septembre</option>
                                    <option value="Octobre">Octobre</option>
                                    <option value="Novembre">Novembre</option>
                                    <option value="Décembre">Décembre</option>
                                </select>
                                @error('expenseFormData.month')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Montant --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="bi bi-cash mr-1"></i>Montant
                                </label>
                                <input type="number" step="0.01" wire:model="expenseFormData.amount"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200"
                                    placeholder="0.00">
                                @error('expenseFormData.amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Devise --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="bi bi-currency-dollar mr-1"></i>Devise
                                </label>
                                <select wire:model="expenseFormData.currency"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
                                    <option value="USD">USD ($)</option>
                                    <option value="CDF">CDF (FC)</option>
                                </select>
                                @error('expenseFormData.currency')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Catégorie de Dépense --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="bi bi-tag mr-1"></i>Catégorie de Dépense
                                </label>
                                <select wire:model="expenseFormData.categoryExpenseId"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($categoryExpenses as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('expenseFormData.categoryExpenseId')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Type de Frais OU Source de Dépense --}}
                            <div x-show="expenseType === 'school_fee'">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="bi bi-receipt mr-1"></i>Type de Frais
                                </label>
                                <select wire:model="expenseFormData.categoryFeeId"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($categoryFees as $fee)
                                        <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                    @endforeach
                                </select>
                                @error('expenseFormData.categoryFeeId')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="expenseType === 'other'">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="bi bi-box-seam mr-1"></i>Source de Dépense
                                </label>
                                <select wire:model="expenseFormData.otherSourceExpenseId"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($otherSources as $source)
                                        <option value="{{ $source->id }}">{{ $source->name }}</option>
                                    @endforeach
                                </select>
                                @error('expenseFormData.otherSourceExpenseId')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="bg-gradient-to-r from-gray-50 to-gray-100/80 dark:from-gray-700/80 dark:to-gray-700/60 backdrop-blur-sm px-6 py-4 border-t border-gray-200/50 dark:border-gray-600/50 flex justify-end gap-3">
                    <button @click="showExpenseModal = false"
                        class="px-6 py-2.5 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-semibold rounded-xl transition-all duration-200 flex items-center gap-2">
                        <i class="bi bi-x-circle"></i>
                        Annuler
                    </button>
                    <button wire:click="saveExpense" wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="bi bi-check-circle" wire:loading.remove wire:target="saveExpense"></i>
                        <svg wire:loading wire:target="saveExpense" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveExpense">Enregistrer</span>
                        <span wire:loading wire:target="saveExpense">Enregistrement...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Offcanvas pour filtres avancés --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="advancedFiltersOffcanvas">
        <div class="offcanvas-header bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <h5 class="offcanvas-title font-bold flex items-center gap-2">
                <i class="bi bi-sliders"></i>
                Filtres avancés
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body space-y-4">
            {{-- Type de plage --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type de plage</label>
                <select wire:model.live="dateRange" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100">
                    <option value="all">Toutes les dates</option>
                    <option value="custom">Période personnalisée</option>
                </select>
            </div>

            @if ($dateRange === 'custom')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date début</label>
                        <input type="date" wire:model.live="dateDebut" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date fin</label>
                        <input type="date" wire:model.live="dateFin" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100">
                    </div>
                </div>
            @endif

            @if ($expenseType === 'school_fee')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mois</label>
                    <select wire:model.live="filterMonth" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100">
                        <option value="">Tous les mois</option>
                        <option value="Janvier">Janvier</option>
                        <option value="Février">Février</option>
                        <option value="Mars">Mars</option>
                        <option value="Avril">Avril</option>
                        <option value="Mai">Mai</option>
                        <option value="Juin">Juin</option>
                        <option value="Juillet">Juillet</option>
                        <option value="Août">Août</option>
                        <option value="Septembre">Septembre</option>
                        <option value="Octobre">Octobre</option>
                        <option value="Novembre">Novembre</option>
                        <option value="Décembre">Décembre</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie frais</label>
                    <select wire:model.live="filterCategoryFee" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categoryFees as $fee)
                            <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Source</label>
                    <select wire:model.live="filterOtherSource" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100">
                        <option value="">Toutes les sources</option>
                        @foreach ($otherSources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    {{-- Indicateur de chargement --}}
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />
</div>

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
                    ${data.month ? `<p class="mb-2"><strong>Mois:</strong> ${data.month}</p>` : ''}
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
