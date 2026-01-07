{{-- Expense Settings Page - Version Tailwind CSS --}}
<div>
    <x-navigation.bread-crumb icon='bi bi-gear' label="Paramètres des Dépenses">
        <x-navigation.bread-crumb-item label='Paramètres' />
        <x-navigation.bread-crumb-item label='Dashboard Financier' isLinked=true link="finance.dashboard" isFirst=true />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- Type Switcher -->
        <x-expense-settings.type-switcher-tailwind :activeTab="$activeTab" />

        <!-- Content Area -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if ($activeTab === 'categories')
                <!-- Categories Section -->
                @if ($categoryExpenses->isEmpty())
                    <div class="col-span-full">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0">
                            <div class="p-12 text-center">
                                <i class="bi bi-tags text-gray-300 dark:text-gray-600 text-7xl"></i>
                                <h5 class="mt-4 text-gray-600 dark:text-gray-400 text-xl font-semibold">Aucune catégorie de dépense</h5>
                                <p class="text-gray-500 dark:text-gray-500 mt-2">Commencez par créer votre première catégorie</p>
                                <button type="button" 
                                    class="mt-4 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors inline-flex items-center shadow-sm" 
                                    wire:click="openCreateCategoryModal">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Créer une Catégorie
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach ($categoryExpenses as $category)
                        <x-expense-settings.category-card-tailwind :category="$category" />
                    @endforeach
                @endif
            @else
                <!-- Sources Section -->
                @if ($otherSourceExpenses->isEmpty())
                    <div class="col-span-full">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0">
                            <div class="p-12 text-center">
                                <i class="bi bi-folder2-open text-gray-300 dark:text-gray-600 text-7xl"></i>
                                <h5 class="mt-4 text-gray-600 dark:text-gray-400 text-xl font-semibold">Aucune source de dépense</h5>
                                <p class="text-gray-500 dark:text-gray-500 mt-2">Commencez par créer votre première source</p>
                                <button type="button" 
                                    class="mt-4 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors inline-flex items-center shadow-sm" 
                                    wire:click="openCreateSourceModal">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Créer une Source
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach ($otherSourceExpenses as $source)
                        <x-expense-settings.source-card-tailwind :source="$source" />
                    @endforeach
                @endif
            @endif
        </div>
    </x-content.main-content-page>

    <!-- Category Modal -->
    @if($showCategoryModal)
    <div x-data="{ show: @entangle('showCategoryModal').live }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true"
         @keydown.escape.window="$wire.closeCategoryModal()">

        {{-- Overlay avec effet de flou --}}
        <div class="fixed inset-0 bg-gradient-to-br from-gray-900/80 via-blue-900/50 to-gray-900/80 backdrop-blur-sm transition-all"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-sm"
             x-transition:leave-end="opacity-0 backdrop-blur-none"
             @click="$wire.closeCategoryModal()"></div>

        {{-- Modal Content avec effet glassmorphism --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.stop>
                {{-- Header avec gradient subtil --}}
                <div class="bg-gradient-to-r from-blue-50/80 to-blue-100/80 dark:from-gray-700/80 dark:to-gray-700/80 backdrop-blur-sm px-6 py-4 border-b border-gray-200/50 dark:border-gray-600/50 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            @if($editingCategoryId)
                                <i class="bi bi-pencil-square text-blue-600 dark:text-blue-400"></i>Modifier la Catégorie
                            @else
                                <i class="bi bi-plus-circle text-blue-600 dark:text-blue-400"></i>Nouvelle Catégorie
                            @endif
                        </h3>
                        <button type="button" wire:click="closeCategoryModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4">

                    <form id="categoryForm" wire:submit.prevent="saveCategory">
                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="category_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="category_name" 
                                   wire:model="categoryFormData.name" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="Nom de la catégorie">
                            @error('categoryFormData.name') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-4">
                            <label for="category_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea id="category_description" 
                                      wire:model="categoryFormData.description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                      placeholder="Description de la catégorie"></textarea>
                            @error('categoryFormData.description') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       wire:model="categoryFormData.is_active" 
                                       class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">Catégorie active</span>
                            </label>
                        </div>

                        </div>
                    </form>
                </div>

                {{-- Footer avec gradient subtil --}}
                <div class="bg-gradient-to-r from-gray-50/80 to-gray-100/80 dark:from-gray-700/80 dark:to-gray-700/80 backdrop-blur-sm px-6 py-4 border-t border-gray-200/50 dark:border-gray-600/50 rounded-b-2xl flex justify-end gap-3">
                    <button type="button" 
                            wire:click="closeCategoryModal"
                            class="px-4 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 font-medium rounded-lg transition-colors flex items-center gap-2">
                        <i class="bi bi-x-circle"></i>Annuler
                    </button>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            form="categoryForm"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                        <span wire:loading.remove wire:target="saveCategory">
                            <i class="bi bi-check-circle"></i>
                            @if($editingCategoryId) Modifier @else Créer @endif
                        </span>
                        <span wire:loading wire:target="saveCategory">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Source Modal -->
    @if($showSourceModal)
    <div x-data="{ show: @entangle('showSourceModal').live }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true"
         @keydown.escape.window="$wire.closeSourceModal()">

        {{-- Overlay avec effet de flou --}}
        <div class="fixed inset-0 bg-gradient-to-br from-gray-900/80 via-purple-900/50 to-gray-900/80 backdrop-blur-sm transition-all"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-sm"
             x-transition:leave-end="opacity-0 backdrop-blur-none"
             @click="$wire.closeSourceModal()"></div>

        {{-- Modal Content avec effet glassmorphism --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.stop>
                {{-- Header avec gradient subtil --}}
                <div class="bg-gradient-to-r from-purple-50/80 to-purple-100/80 dark:from-gray-700/80 dark:to-gray-700/80 backdrop-blur-sm px-6 py-4 border-b border-gray-200/50 dark:border-gray-600/50 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            @if($editingSourceId)
                                <i class="bi bi-pencil-square text-purple-600 dark:text-purple-400"></i>Modifier la Source
                            @else
                                <i class="bi bi-plus-circle text-purple-600 dark:text-purple-400"></i>Nouvelle Source
                            @endif
                        </h3>
                        <button type="button" wire:click="closeSourceModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4">

                    <form id="sourceForm" wire:submit.prevent="saveSource">
                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="source_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="source_name" 
                                   wire:model="sourceFormData.name" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="Nom de la source">
                            @error('sourceFormData.name') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-4">
                            <label for="source_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea id="source_description" 
                                      wire:model="sourceFormData.description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                      placeholder="Description de la source"></textarea>
                            @error('sourceFormData.description') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       wire:model="sourceFormData.is_active" 
                                       class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">Source active</span>
                            </label>
                        </div>

                        </div>
                    </form>
                </div>

                {{-- Footer avec gradient subtil --}}
                <div class="bg-gradient-to-r from-gray-50/80 to-gray-100/80 dark:from-gray-700/80 dark:to-gray-700/80 backdrop-blur-sm px-6 py-4 border-t border-gray-200/50 dark:border-gray-600/50 rounded-b-2xl flex justify-end gap-3">
                    <button type="button" 
                            wire:click="closeSourceModal"
                            class="px-4 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 font-medium rounded-lg transition-colors flex items-center gap-2">
                        <i class="bi bi-x-circle"></i>Annuler
                    </button>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            form="sourceForm"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                        <span wire:loading.remove wire:target="saveSource">
                            <i class="bi bi-check-circle"></i>
                            @if($editingSourceId) Modifier @else Créer @endif
                        </span>
                        <span wire:loading wire:target="saveSource">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        [x-cloak] { display: none !important; }
    </style>

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

        // Success notifications for category/source save
        $wire.on('category-saved', (event) => {
            Swal.fire({
                title: 'Succès !',
                text: event[0].message,
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        });

        $wire.on('source-saved', (event) => {
            Swal.fire({
                title: 'Succès !',
                text: event[0].message,
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        });

        $wire.on('save-failed', (event) => {
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
</div>
