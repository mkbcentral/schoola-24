@props(['expenseType' => 'fee'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-6">
    <div class="p-6">
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
            <div class="inline-flex rounded-lg shadow-sm" role="group">
                <button type="button" 
                    class="px-6 py-3 text-sm font-medium rounded-l-lg border transition-all duration-200
                    {{ $expenseType === 'fee' 
                        ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' 
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                    wire:click="switchExpenseType('fee')" 
                    wire:loading.attr="disabled" 
                    wire:target="switchExpenseType">
                    <i class="bi bi-receipt mr-2" wire:loading.remove wire:target="switchExpenseType('fee')"></i>
                    <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2" 
                        wire:loading wire:target="switchExpenseType('fee')"></span>
                    Dépenses sur Frais
                </button>
                <button type="button" 
                    class="px-6 py-3 text-sm font-medium rounded-r-lg border-t border-r border-b transition-all duration-200
                    {{ $expenseType === 'other' 
                        ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' 
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                    wire:click="switchExpenseType('other')" 
                    wire:loading.attr="disabled"
                    wire:target="switchExpenseType">
                    <i class="bi bi-box-seam mr-2" wire:loading.remove wire:target="switchExpenseType('other')"></i>
                    <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2" 
                        wire:loading wire:target="switchExpenseType('other')"></span>
                    Autres Dépenses
                </button>
            </div>

            <button class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm flex items-center justify-center" 
                type="button" 
                data-bs-toggle="offcanvas"
                data-bs-target="#expenseFormOffcanvas" 
                aria-controls="expenseFormOffcanvas"
                wire:click="openCreateModal">
                <i class="bi bi-plus-circle mr-2"></i>
                Nouvelle Dépense
            </button>
        </div>
    </div>
</div>
