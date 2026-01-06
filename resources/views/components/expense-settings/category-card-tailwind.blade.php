@props(['category'])

<div wire:key="category-{{ $category->id }}" 
    class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1">
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1 min-w-0">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                    <i class="bi bi-tag text-blue-600 dark:text-blue-400 mr-2"></i>
                    {{ $category->name }}
                </h5>
                <small class="text-gray-500 dark:text-gray-400 text-sm flex items-center">
                    <i class="bi bi-calendar3 mr-1"></i>
                    Créée le {{ $category->created_at->format('d/m/Y') }}
                </small>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" 
                    type="button" 
                    @click="open = !open"
                    @click.outside="open = false">
                    <i class="bi bi-three-dots-vertical text-gray-600 dark:text-gray-400"></i>
                </button>
                <div x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10"
                    style="display: none;">
                    <div class="py-1">
                        <button class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center text-gray-700 dark:text-gray-300 transition-colors" 
                            data-bs-toggle="offcanvas"
                            data-bs-target="#categoryFormOffcanvas"
                            wire:click="openEditCategoryModal({{ $category->id }})"
                            @click="open = false">
                            <i class="bi bi-pencil text-blue-600 dark:text-blue-400 mr-2"></i>
                            Modifier
                        </button>
                        @php
                            $totalExpenses = $category->expenseFee->count() + $category->otherExpenses->count();
                        @endphp
                        @if ($totalExpenses === 0)
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <button class="w-full px-4 py-2 text-left text-sm hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center text-red-600 dark:text-red-400 transition-colors"
                                wire:click="confirmDeleteCategory({{ $category->id }})"
                                @click="open = false">
                                <i class="bi bi-trash mr-2"></i>
                                Supprimer
                            </button>
                        @else
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <div class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500 flex items-center cursor-not-allowed">
                                <i class="bi bi-lock mr-2"></i>
                                {{ $totalExpenses }} dépense(s)
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 gap-3 mt-4">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center border border-blue-100 dark:border-blue-800">
                <small class="text-gray-600 dark:text-gray-400 text-xs block mb-1">Dép. sur Frais</small>
                <strong class="text-blue-600 dark:text-blue-400 text-xl">{{ $category->expenseFee->count() }}</strong>
            </div>
            <div class="p-3 bg-cyan-50 dark:bg-cyan-900/20 rounded-lg text-center border border-cyan-100 dark:border-cyan-800">
                <small class="text-gray-600 dark:text-gray-400 text-xs block mb-1">Autres Dép.</small>
                <strong class="text-cyan-600 dark:text-cyan-400 text-xl">{{ $category->otherExpenses->count() }}</strong>
            </div>
        </div>
    </div>
</div>
