@props(['source'])

<div wire:key="source-{{ $source->id }}" 
    class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1">
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1 min-w-0">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                    <i class="bi bi-folder2-open text-green-600 dark:text-green-400 mr-2"></i>
                    {{ $source->name }}
                </h5>
                <small class="text-gray-500 dark:text-gray-400 text-sm flex items-center">
                    <i class="bi bi-calendar3 mr-1"></i>
                    Créée le {{ $source->created_at->format('d/m/Y') }}
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
                            wire:click="openEditSourceModal({{ $source->id }})"
                            @click="open = false">
                            <i class="bi bi-pencil text-blue-600 dark:text-blue-400 mr-2"></i>
                            Modifier
                        </button>
                        @php
                            $totalExpenses = $source->otherExpenses->count();
                        @endphp
                        @if ($totalExpenses === 0)
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <button class="w-full px-4 py-2 text-left text-sm hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center text-red-600 dark:text-red-400 transition-colors"
                                wire:click="confirmDeleteSource({{ $source->id }})"
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
        <div class="mt-4">
            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center border border-green-100 dark:border-green-800">
                <small class="text-gray-600 dark:text-gray-400 text-sm block mb-2">Nombre de dépenses</small>
                <strong class="text-green-600 dark:text-green-400 text-3xl">{{ $source->otherExpenses->count() }}</strong>
            </div>
        </div>
    </div>
</div>
