@props([
    'date' => null,
    'filterPeriod' => '',
    'filterCurrency' => '',
    'filterCategoryExpense' => '0',
    'categoryExpenses' => [],
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-0 flex items-center">
            <i class="bi bi-funnel mr-2 text-blue-600 dark:text-blue-400"></i>
            Filtres rapides
        </h5>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Date spécifique -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date spécifique</label>
                <input type="date" 
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all" 
                    wire:model.live="date" />
            </div>

            <!-- Période -->
            <div class="lg:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Période</label>
                <select class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all" 
                    wire:model.live="filterPeriod">
                    <option value="">Toutes</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="this_week">Cette semaine</option>
                    <option value="this_month">Ce mois</option>
                    <option value="last_month">Mois dernier</option>
                </select>
            </div>

            <!-- Devise -->
            <div class="lg:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Devise</label>
                <select class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all" 
                    wire:model.live="filterCurrency">
                    <option value="">Toutes</option>
                    <option value="USD">USD ($)</option>
                    <option value="CDF">CDF (FC)</option>
                </select>
            </div>

            <!-- Catégorie -->
            <div class="lg:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catégorie</label>
                <select class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all" 
                    wire:model.live="filterCategoryExpense">
                    <option value="0">Toutes</option>
                    @foreach ($categoryExpenses as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bouton filtres supplémentaires -->
            <div class="lg:col-span-1 flex items-end">
                <button class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 flex items-center justify-center" 
                    type="button" 
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasFilters" 
                    aria-controls="offcanvasFilters" 
                    title="Plus de filtres">
                    <i class="bi bi-funnel-fill mr-2"></i>
                    Plus de filtres
                </button>
            </div>
        </div>
    </div>
</div>
