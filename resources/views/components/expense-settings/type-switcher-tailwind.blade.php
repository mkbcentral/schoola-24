@props([
    'activeTab' => 'categories',
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0 mb-6">
    <div class="p-4">
        <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-4">
            <!-- Tab Buttons -->
            <div class="inline-flex rounded-lg shadow-sm" role="group">
                <button type="button"
                    class="px-6 py-3 text-sm font-medium rounded-l-lg border transition-all duration-200
                    {{ $activeTab === 'categories' 
                        ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' 
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                    wire:click="switchTab('categories')" 
                    wire:loading.attr="disabled">
                    <i class="bi bi-tags mr-2"></i>
                    Catégories de Dépenses
                </button>
                <button type="button"
                    class="px-6 py-3 text-sm font-medium rounded-r-lg border-t border-r border-b transition-all duration-200
                    {{ $activeTab === 'sources' 
                        ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' 
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                    wire:click="switchTab('sources')" 
                    wire:loading.attr="disabled">
                    <i class="bi bi-folder2-open mr-2"></i>
                    Sources Autres Dépenses
                </button>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <!-- Search Input -->
                <div class="relative w-full sm:w-80">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all" 
                        placeholder="Rechercher..." 
                        wire:model.live="search">
                </div>

                <!-- Add Button -->
                @if ($activeTab === 'categories')
                    <button type="button" 
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm flex items-center justify-center whitespace-nowrap" 
                        data-bs-toggle="offcanvas"
                        data-bs-target="#categoryFormOffcanvas" 
                        wire:click="openCreateCategoryModal">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Nouvelle Catégorie
                    </button>
                @else
                    <button type="button" 
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm flex items-center justify-center whitespace-nowrap" 
                        data-bs-toggle="offcanvas"
                        data-bs-target="#sourceFormOffcanvas" 
                        wire:click="openCreateSourceModal">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Nouvelle Source
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
