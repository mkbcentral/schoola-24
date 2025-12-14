<div>
    <x-navigation.bread-crumb icon='bi bi-layers' label="Configuration Sections">
        <x-navigation.bread-crumb-item label='Configuration' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- Switch entre types de configuration -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="btn-group w-100" role="group">
                    <button type="button"
                        class="btn btn-{{ $configType === 'section' ? 'primary' : 'outline-primary' }}"
                        wire:click="switchConfigType('section')" wire:loading.attr="disabled">
                        <i class="bi bi-collection me-2"></i>
                        Sections
                    </button>
                    <button type="button"
                        class="btn btn-{{ $configType === 'option' ? 'primary' : 'outline-primary' }}"
                        wire:click="switchConfigType('option')" wire:loading.attr="disabled">
                        <i class="bi bi-diagram-3 me-2"></i>
                        Options
                    </button>
                    <button type="button"
                        class="btn btn-{{ $configType === 'class-room' ? 'primary' : 'outline-primary' }}"
                        wire:click="switchConfigType('class-room')" wire:loading.attr="disabled">
                        <i class="bi bi-door-open me-2"></i>
                        Classes
                    </button>
                </div>
            </div>
        </div>

        <!-- Bouton d'ajout -->
        <div class="mb-3">
            <button class="btn btn-success" data-bs-toggle="offcanvas"
                data-bs-target="#{{ $configType === 'section' ? 'sectionFormOffcanvas' : ($configType === 'option' ? 'optionFormOffcanvas' : 'classRoomFormOffcanvas') }}"
                wire:click="openCreateModal">
                <i class="bi bi-plus-circle me-2"></i>
                Nouveau {{ $configType === 'section' ? 'Section' : ($configType === 'option' ? 'Option' : 'Classe') }}
            </button>
        </div>

        <!-- Message d'alerte -->
        @if (session()->has('message'))
            <div class="alert alert-{{ session('type', 'info') }} alert-dismissible fade show" role="alert">
                <i class="bi bi-{{ session('type') === 'success' ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tableau des Sections -->
        @if ($configType === 'section')
            <div class="card" wire:key="section-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-collection me-2"></i>
                        Liste des Sections
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Options</th>
                                    <th>Date de création</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $section)
                                    <tr wire:key="section-{{ $section->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $section->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-info">
                                                {{ $section->options->count() }} option(s)
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $section->created_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="offcanvas" data-bs-target="#sectionFormOffcanvas"
                                                    wire:click="openEditModal({{ $section->id }})" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    wire:click="confirmDeleteSection({{ $section->id }})"
                                                    title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-inbox fs-1 text-muted"></i>
                                            <p class="mt-2 text-muted">Aucune section trouvée</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tableau des Options -->
        @if ($configType === 'option')
            <div class="card" wire:key="option-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-diagram-3 me-2"></i>
                        Liste des Options
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Section</th>
                                    <th>Classes</th>
                                    <th>Date de création</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $option)
                                    <tr wire:key="option-{{ $option->id }}">
                                        <td>{{ ($items->currentPage() - 1) * $items->perPage() + $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $option->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-primary">
                                                {{ $option->section->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-info">
                                                {{ $option->classRooms->count() }} classe(s)
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $option->created_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="offcanvas" data-bs-target="#optionFormOffcanvas"
                                                    wire:click="openEditModal({{ $option->id }})" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    wire:click="confirmDeleteOption({{ $option->id }})"
                                                    title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="bi bi-inbox fs-1 text-muted"></i>
                                            <p class="mt-2 text-muted">Aucune option trouvée</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($items->hasPages())
                    <div class="card-footer">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Tableau des Classes -->
        @if ($configType === 'class-room')
            <div class="card" wire:key="class-room-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-door-open me-2"></i>
                        Liste des Classes
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Option</th>
                                    <th>Section</th>
                                    <th>Inscriptions</th>
                                    <th>Date de création</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $classRoom)
                                    <tr wire:key="class-room-{{ $classRoom->id }}">
                                        <td>{{ ($items->currentPage() - 1) * $items->perPage() + $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $classRoom->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-primary">
                                                {{ $classRoom->option->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-secondary">
                                                {{ $classRoom->option->section->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-info">
                                                {{ $classRoom->registrations->count() }} inscription(s)
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $classRoom->created_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#classRoomFormOffcanvas"
                                                    wire:click="openEditModal({{ $classRoom->id }})"
                                                    title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    wire:click="confirmDeleteClassRoom({{ $classRoom->id }})"
                                                    title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-inbox fs-1 text-muted"></i>
                                            <p class="mt-2 text-muted">Aucune classe trouvée</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($items->hasPages())
                    <div class="card-footer">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        @endif
    </x-content.main-content-page>

    <!-- Indicateur de chargement -->
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />

    <!-- Offcanvas Forms -->
    <livewire:application.v2.configuration.form.section-form-offcanvas :schoolId="$school->id" />
    <livewire:application.v2.configuration.form.option-form-offcanvas :schoolId="$school->id" />
    <livewire:application.v2.configuration.form.class-room-form-offcanvas :schoolId="$school->id" />

    @push('scripts')
        <script>
            // Confirmation de suppression pour Section
            window.addEventListener('delete-section-dialog', event => {
                Swal.fire({
                    title: 'Supprimer la section ?',
                    html: `Êtes-vous sûr de vouloir supprimer la section <strong>${event.detail[0].name}</strong> ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteSection', event.detail[0].id);
                    }
                });
            });

            // Confirmation de suppression pour Option
            window.addEventListener('delete-option-dialog', event => {
                Swal.fire({
                    title: 'Supprimer l\'option ?',
                    html: `Êtes-vous sûr de vouloir supprimer l'option <strong>${event.detail[0].name}</strong> ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteOption', event.detail[0].id);
                    }
                });
            });

            // Confirmation de suppression pour ClassRoom
            window.addEventListener('delete-class-room-dialog', event => {
                Swal.fire({
                    title: 'Supprimer la classe ?',
                    html: `Êtes-vous sûr de vouloir supprimer la classe <strong>${event.detail[0].name}</strong> ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteClassRoom', event.detail[0].id);
                    }
                });
            });

            // Succès de suppression
            window.addEventListener('item-deleted', event => {
                Swal.fire({
                    title: 'Succès !',
                    text: event.detail[0].message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            // Échec de suppression
            window.addEventListener('delete-failed', event => {
                Swal.fire({
                    title: 'Erreur',
                    text: event.detail[0].message,
                    icon: 'error'
                });
            });

            // Fermeture de l'offcanvas
            window.addEventListener('close-offcanvas', event => {
                const offcanvases = document.querySelectorAll('.offcanvas.show');
                offcanvases.forEach(offcanvas => {
                    const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas);
                    if (bsOffcanvas) {
                        bsOffcanvas.hide();
                    }
                });
            });
        </script>
    @endpush
</div>
