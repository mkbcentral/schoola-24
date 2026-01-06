{{-- Expense Settings Page - Version Tailwind CSS --}}
<div>
    <x-navigation.bread-crumb icon='bi bi-gear' label="Paramètres des Dépenses">
        <x-navigation.bread-crumb-item label='Paramètres' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
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
                                    data-bs-toggle="modal"
                                    data-bs-target="#categoryFormModal" 
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
                                    data-bs-toggle="modal"
                                    data-bs-target="#sourceFormModal" 
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

    <!-- Category Form Modal -->
    @livewire('application.finance.expense.settings.category-expense-form-modal', key('category-modal'))

    <!-- Source Form Modal -->
    @livewire('application.finance.expense.settings.other-source-expense-form-modal', key('source-modal'))

    <!-- Loading Overlay -->
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter"
        wire:loading.delay.long="search,switchTab,openCreateCategoryModal,openCreateSourceModal" />
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
