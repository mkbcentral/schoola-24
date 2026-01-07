{{-- Paramètres des Dépenses - Version Tailwind CSS --}}
<div x-data="{ activeTab: @entangle('activeTab').live }">
    {{-- Breadcrumb --}}
    <x-navigation.bread-crumb icon='bi bi-gear' label="Paramètres des Dépenses">
        <x-navigation.bread-crumb-item label='Dashboard Financier' isLinked=true link="finance.dashboard" isFirst=true />
        <x-navigation.bread-crumb-item label='Paramètres' />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="space-y-6 max-w-full overflow-hidden">
            {{-- En-tête avec recherche --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div>
                        <h2 class="text-gray-700 dark:text-gray-200 font-semibold text-lg flex items-center gap-2">
                            <i class="bi bi-gear text-blue-600 dark:text-blue-400"></i>
                            Paramètres des Dépenses
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Gérer les catégories et sources de dépenses
                        </p>
                    </div>
                    
                    {{-- Barre de recherche --}}
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <div class="relative flex-1 lg:w-80">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-search text-gray-400"></i>
                            </div>
                            <input type="text" wire:model.live="search" 
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Rechercher...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs Navigation --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px">
                        <button wire:click="switchTab('categories')" 
                            class="group inline-flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-all duration-200"
                            :class="activeTab === 'categories' 
                                ? 'border-blue-500 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' 
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'">
                            <i class="bi bi-tags mr-2"></i>
                            Catégories de Dépenses
                            @if($categoryExpenses->count() > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full" 
                                    :class="activeTab === 'categories' 
                                        ? 'bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200' 
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">
                                    {{ $categoryExpenses->count() }}
                                </span>
                            @endif
                        </button>
                        
                        <button wire:click="switchTab('sources')" 
                            class="group inline-flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-all duration-200"
                            :class="activeTab === 'sources' 
                                ? 'border-purple-500 text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20' 
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'">
                            <i class="bi bi-folder2-open mr-2"></i>
                            Sources de Dépenses
                            @if($otherSourceExpenses->count() > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full" 
                                    :class="activeTab === 'sources' 
                                        ? 'bg-purple-100 dark:bg-purple-800 text-purple-800 dark:text-purple-200' 
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'">
                                    {{ $otherSourceExpenses->count() }}
                                </span>
                            @endif
                        </button>
                    </nav>
                </div>

                {{-- Tab Content --}}
                <div class="p-6">
                    {{-- Categories Tab --}}
                    <div x-show="activeTab === 'categories'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100">
                        
                        @if($categoryExpenses->isEmpty())
                            {{-- Empty State --}}
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full mb-4">
                                    <i class="bi bi-tags text-blue-600 dark:text-blue-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    Aucune catégorie de dépense
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">
                                    Commencez par créer votre première catégorie
                                </p>
                                <button wire:click="openCreateCategoryModal" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Créer une Catégorie
                                </button>
                            </div>
                        @else
                            {{-- Header with Action --}}
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                        Liste des Catégories
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $categoryExpenses->count() }} catégorie(s) trouvée(s)
                                    </p>
                                </div>
                                <button wire:click="openCreateCategoryModal" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Nouvelle Catégorie
                                </button>
                            </div>

                            {{-- Categories Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($categoryExpenses as $category)
                                    <div class="bg-gradient-to-br from-white to-blue-50 dark:from-gray-800 dark:to-blue-900/10 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-all duration-200 group">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2">
                                                    <i class="bi bi-tag-fill text-blue-600 dark:text-blue-400"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $category->name }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                                {{ $category->is_active ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </div>
                                        
                                        @if($category->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                                {{ $category->description }}
                                            </p>
                                        @endif

                                        <div class="flex items-center gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <button wire:click="openEditCategoryModal({{ $category->id }})" 
                                                class="flex-1 inline-flex items-center justify-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg transition-colors">
                                                <i class="bi bi-pencil mr-1"></i>
                                                Modifier
                                            </button>
                                            <button wire:click="confirmDeleteCategory({{ $category->id }})" 
                                                class="flex-1 inline-flex items-center justify-center px-3 py-1.5 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 text-sm font-medium rounded-lg transition-colors">
                                                <i class="bi bi-trash mr-1"></i>
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Sources Tab --}}
                    <div x-show="activeTab === 'sources'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100">
                        
                        @if($otherSourceExpenses->isEmpty())
                            {{-- Empty State --}}
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-100 dark:bg-purple-900/30 rounded-full mb-4">
                                    <i class="bi bi-folder2-open text-purple-600 dark:text-purple-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    Aucune source de dépense
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">
                                    Commencez par créer votre première source
                                </p>
                                <button wire:click="openCreateSourceModal" 
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Créer une Source
                                </button>
                            </div>
                        @else
                            {{-- Header with Action --}}
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                        Liste des Sources
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $otherSourceExpenses->count() }} source(s) trouvée(s)
                                    </p>
                                </div>
                                <button wire:click="openCreateSourceModal" 
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Nouvelle Source
                                </button>
                            </div>

                            {{-- Sources Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($otherSourceExpenses as $source)
                                    <div class="bg-gradient-to-br from-white to-purple-50 dark:from-gray-800 dark:to-purple-900/10 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-all duration-200 group">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-2">
                                                    <i class="bi bi-folder-fill text-purple-600 dark:text-purple-400"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $source->name }}
                                                    </h4>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $source->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                                {{ $source->is_active ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </div>
                                        
                                        @if($source->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                                {{ $source->description }}
                                            </p>
                                        @endif

                                        <div class="flex items-center gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <button wire:click="openEditSourceModal({{ $source->id }})" 
                                                class="flex-1 inline-flex items-center justify-center px-3 py-1.5 bg-purple-100 dark:bg-purple-900/30 hover:bg-purple-200 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 text-sm font-medium rounded-lg transition-colors">
                                                <i class="bi bi-pencil mr-1"></i>
                                                Modifier
                                            </button>
                                            <button wire:click="confirmDeleteSource({{ $source->id }})" 
                                                class="flex-1 inline-flex items-center justify-center px-3 py-1.5 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 text-sm font-medium rounded-lg transition-colors">
                                                <i class="bi bi-trash mr-1"></i>
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>

@script
    <script>
        // SweetAlert2 for delete confirmation - Category
        $wire.on('confirm-delete-category', (event) => {
            const data = event[0];

            Swal.fire({
                title: 'Confirmer la suppression ?',
                html: `<div class="text-start">
                    <p class="mb-2"><strong>Catégorie :</strong> ${data.name}</p>
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
                    $wire.deleteCategory(data.id);
                }
            });
        });

        $wire.on('category-deleted', (event) => {
            Swal.fire({
                title: 'Supprimée !',
                text: event[0].message,
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        });

        // SweetAlert2 for delete confirmation - Source
        $wire.on('confirm-delete-source', (event) => {
            const data = event[0];

            Swal.fire({
                title: 'Confirmer la suppression ?',
                html: `<div class="text-start">
                    <p class="mb-2"><strong>Source :</strong> ${data.name}</p>
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
                    $wire.deleteSource(data.id);
                }
            });
        });

        $wire.on('source-deleted', (event) => {
            Swal.fire({
                title: 'Supprimée !',
                text: event[0].message,
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        });

        $wire.on('delete-failed', (event) => {
            Swal.fire({
                title: 'Erreur !',
                text: event[0].message,
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endscript
