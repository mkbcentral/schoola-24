<div>
    <x-navigation.bread-crumb icon='bi bi-gear' label="Configuration Système">
        <x-navigation.bread-crumb-item label='Configuration' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- Switch entre types de configuration -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="btn-group w-100" role="group">
                    <button type="button"
                        class="btn btn-{{ $configType === 'school-year' ? 'primary' : 'outline-primary' }}"
                        wire:click="switchConfigType('school-year')" wire:loading.attr="disabled">
                        <i class="bi bi-calendar-event me-2"></i>
                        Années Scolaires
                    </button>
                    <button type="button" class="btn btn-{{ $configType === 'rate' ? 'primary' : 'outline-primary' }}"
                        wire:click="switchConfigType('rate')" wire:loading.attr="disabled">
                        <i class="bi bi-currency-exchange me-2"></i>
                        Taux de Change
                    </button>
                </div>
            </div>
        </div>

        <!-- Bouton d'ajout -->
        <div class="mb-3">
            <button class="btn btn-success" data-bs-toggle="offcanvas"
                data-bs-target="#{{ $configType === 'school-year' ? 'schoolYearFormOffcanvas' : 'rateFormOffcanvas' }}"
                wire:click="openCreateModal">
                <i class="bi bi-plus-circle me-2"></i>
                Nouveau {{ $configType === 'school-year' ? 'Année Scolaire' : 'Taux' }}
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

        <!-- Tableau des Années Scolaires -->
        @if ($configType === 'school-year')
            <div class="card" wire:key="school-year-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        Liste des Années Scolaires
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Statut</th>
                                    <th>Inscriptions</th>
                                    <th>Date de création</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schoolYears as $index => $schoolYear)
                                    <tr wire:key="school-year-{{ $schoolYear->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $schoolYear->name }}</strong>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input config-toggle-switch" type="checkbox"
                                                    role="switch" {{ $schoolYear->is_active ? 'checked' : '' }}
                                                    wire:click="toggleSchoolYearStatus({{ $schoolYear->id }})"
                                                    wire:loading.attr="disabled"
                                                    id="schoolYearSwitch{{ $schoolYear->id }}">
                                                <label class="form-check-label"
                                                    for="schoolYearSwitch{{ $schoolYear->id }}">
                                                    <span
                                                        class="badge bg-{{ $schoolYear->is_active ? 'success' : 'secondary' }}">
                                                        {{ $schoolYear->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-info">
                                                {{ $schoolYear->registrations->count() }} inscription(s)
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $schoolYear->created_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="offcanvas"
                                                data-bs-target="#schoolYearFormOffcanvas"
                                                wire:click="openEditModal({{ $schoolYear->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDeleteSchoolYear({{ $schoolYear->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">Aucune année scolaire trouvée</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tableau des Taux -->
        @if ($configType === 'rate')
            <div class="card" wire:key="rate-table">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-currency-exchange me-2"></i>
                        Liste des Taux de Change
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Montant (1 USD = X CDF)</th>
                                    <th>Statut</th>
                                    <th>Inscriptions</th>
                                    <th>Paiements</th>
                                    <th>Date de création</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rates as $index => $rate)
                                    <tr wire:key="rate-{{ $rate->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong class="text-primary">
                                                {{ app_format_number($rate->amount, 0) }} CDF
                                            </strong>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input config-toggle-switch" type="checkbox"
                                                    role="switch" {{ !$rate->is_changed ? 'checked' : '' }}
                                                    wire:click="toggleRateStatus({{ $rate->id }})"
                                                    wire:loading.attr="disabled" id="rateSwitch{{ $rate->id }}">
                                                <label class="form-check-label" for="rateSwitch{{ $rate->id }}">
                                                    <span
                                                        class="badge text-bg-{{ !$rate->is_changed ? 'success' : 'secondary' }}">
                                                        {{ !$rate->is_changed ? 'Par défaut' : 'Modifié' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-info">
                                                {{ $rate->registrations->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-info">
                                                {{ $rate->payments->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $rate->created_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="offcanvas"
                                                data-bs-target="#rateFormOffcanvas"
                                                wire:click="openEditModal({{ $rate->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="confirmDeleteRate({{ $rate->id }})"
                                                wire:loading.attr="disabled">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">Aucun taux trouvé</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </x-content.main-content-page>

    <!-- Composants Offcanvas -->
    @livewire('application.v2.configuration.form.school-year-form-offcanvas')
    @livewire('application.v2.configuration.form.rate-form-offcanvas')

    <!-- Indicateur de chargement -->
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />
</div>

@script
    <script>
        // Configuration Management - SweetAlert pour suppression année scolaire
        $wire.on('delete-school-year-dialog', (event) => {
            const data = event[0];
            Swal.fire({
                title: 'Supprimer cette année scolaire?',
                html: `<div class="text-start">
                    <p class="mb-2"><strong>Nom:</strong> ${data.name}</p>
                    <p class="mb-2"><strong>Statut:</strong> ${data.isActive ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'}</p>
                    <p class="text-danger mt-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cette action est irréversible!</p>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-2"></i>Oui, supprimer',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteSchoolYear(data.id);
                }
            });
        });

        // Configuration Management - SweetAlert pour suppression taux
        $wire.on('delete-rate-dialog', (event) => {
            const data = event[0];
            const amountFormatted = new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(data.amount);

            Swal.fire({
                title: 'Supprimer ce taux?',
                html: `<div class="text-start">
                    <p class="mb-2"><strong>Montant:</strong> ${amountFormatted} CDF</p>
                    <p class="mb-2"><strong>Statut:</strong> ${!data.isDefault ? '<span class="badge bg-success">Par défaut</span>' : '<span class="badge bg-secondary">Modifié</span>'}</p>
                    <p class="text-danger mt-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cette action est irréversible!</p>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-2"></i>Oui, supprimer',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteRate(data.id);
                }
            });
        });

        $wire.on('item-deleted', (event) => {
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

        $wire.on('delete-failed', (event) => {
            Swal.fire({
                title: 'Erreur!',
                text: event[0].message,
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        });

        // Gestion des offcanvas
        $wire.on('close-offcanvas', (event) => {
            const offcanvasElement = document.getElementById(event[0].offcanvasId);
            if (offcanvasElement) {
                const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (offcanvas) {
                    offcanvas.hide();
                }
            }
        });
    </script>
@endscript

@push('styles')
    <style>
        /* Toggle switch pour configuration */
        .form-check-input.config-toggle-switch {
            width: 3.5em !important;
            height: 1.8em !important;
            min-width: 3.5em !important;
            cursor: pointer !important;
            border-radius: 2em !important;
            transition: all 0.3s ease !important;
        }

        .form-check-input.config-toggle-switch:checked {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        .form-check-input.config-toggle-switch:not(:checked) {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
        }

        .form-check-input.config-toggle-switch:focus {
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25) !important;
        }

        /* Animation des badges */
        .badge {
            transition: all 0.3s ease;
        }

        /* Effet hover sur les lignes */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.025) !important;
        }
    </style>
@endpush
