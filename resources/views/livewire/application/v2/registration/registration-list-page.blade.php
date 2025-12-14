<div>
    {{-- Breadcrumb --}}
    <x-navigation.bread-crumb icon='bi bi-person-vcard-fill' label="Gestion des inscriptions V2">
        <x-navigation.bread-crumb-item label='Inscriptions' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        {{-- Boutons d'action principaux --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-primary fw-semibold py-2 px-4 rounded-pill shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#registerExistingStudentModal">
                        <i class="bi bi-person-check"></i> Inscrire ancien élève
                    </button>

                    <button type="button" class="btn btn-success fw-semibold py-2 px-4 rounded-pill shadow-sm"
                        data-bs-toggle="offcanvas" data-bs-target="#registerNewStudentOffcanvas">
                        <i class="bi bi-person-plus"></i> Inscrire nouvel élève
                    </button>

                    <button type="button" class="btn btn-outline-primary fw-semibold py-2 px-4 rounded-pill shadow-sm"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilters">
                        <i class="bi bi-funnel-fill me-2"></i>
                        Filtres avancés
                        @if ($sectionId || $optionId || $classRoomId || $gender)
                            <span class="badge bg-danger ms-2">
                                {{ collect([$sectionId, $optionId, $classRoomId, $gender])->filter()->count() }}
                            </span>
                        @endif
                    </button>

                    @if ($sectionId || $optionId || $classRoomId || $gender || $dateFrom || $dateTo)
                        <button wire:click="resetFilters"
                            class="btn btn-outline-secondary fw-semibold py-2 px-4 rounded-pill shadow-sm">
                            <i class="bi bi-x-circle me-2"></i> Réinitialiser
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cartes de statistiques --}}
        <div class="row mb-4">
            <div class="col-12 col-md-3 mb-3">
                <div class="card border-0 shadow-sm bg-gradient-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 opacity-75 small">Total Inscriptions</p>
                                <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <div class="card border-0 shadow-sm bg-gradient-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 opacity-75 small">Garçons</p>
                                <h2 class="mb-0 fw-bold">{{ $stats['total_male'] }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="bi bi-gender-male"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <div class="card border-0 shadow-sm bg-gradient-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 opacity-75 small">Filles</p>
                                <h2 class="mb-0 fw-bold">{{ $stats['total_female'] }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="bi bi-gender-female"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3 mb-3">
                <div class="card border-0 shadow-sm bg-gradient-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 opacity-75 small">Sections</p>
                                <h2 class="mb-0 fw-bold">{{ count($stats['by_section']) }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtres rapides --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">
                            <i class="bi bi-diagram-3"></i> Section
                        </label>
                        <select wire:model.live="sectionId" class="form-select">
                            <option value="">Toutes les sections</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">
                            <i class="bi bi-bookmark"></i> Option
                        </label>
                        <select wire:model.live="optionId" class="form-select" @disabled(!$sectionId)>
                            <option value="">Toutes les options</option>
                            @foreach ($options as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">
                            <i class="bi bi-door-closed"></i> Classe
                        </label>
                        <select wire:model.live="classRoomId" class="form-select" @disabled(!$optionId)>
                            <option value="">Toutes les classes</option>
                            @foreach ($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-bold small">
                            <i class="bi bi-gender-ambiguous"></i> Genre
                        </label>
                        <select wire:model.live="gender" class="form-select">
                            <option value="">Tous</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>



        {{-- Table des inscriptions --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-list-ul text-primary"></i>
                        Liste des inscriptions
                        <span class="badge bg-primary ms-2">{{ $registrations->total() }}</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <select wire:model.live="perPage" class="form-select form-select-sm" style="width: auto;">
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-bold">Code</th>
                                <th class="fw-bold">Élève</th>
                                <th class="fw-bold text-center">Genre</th>
                                <th class="fw-bold">Classe</th>
                                <th class="fw-bold">Option</th>
                                <th class="fw-bold text-center">Type</th>
                                <th class="fw-bold text-center">Statut</th>
                                <th class="fw-bold">Date</th>
                                <th class="fw-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $registration)
                                <tr>
                                    <td class="align-middle">
                                        <code class="text-primary">{{ $registration->code }}</code>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                {{ substr($registration->student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $registration->student->name }}</div>
                                                <small
                                                    class="text-muted">{{ $registration->student->getFormattedAg() }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if ($registration->student->gender === 'M')
                                            <span class="badge text-bg-info">
                                                <i class="bi bi-gender-male"></i> M
                                            </span>
                                        @else
                                            <span class="badge text-bg-danger">
                                                <i class="bi bi-gender-female"></i> F
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge text-bg-secondary">
                                            {{ $registration->classRoom->name }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <small class="text-muted">{{ $registration->classRoom->option->name }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if ($registration->is_old)
                                            <span class="badge text-bg-primary">Ancien</span>
                                        @else
                                            <span class="badge text-bg-success">Nouveau</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @if ($registration->abandoned)
                                            <span class="badge text-bg-warning">
                                                <i class="bi bi-exclamation-triangle"></i> Abandonné
                                            </span>
                                        @elseif($registration->is_registered)
                                            <span class="badge text-bg-success">
                                                <i class="bi bi-check-circle"></i> Actif
                                            </span>
                                        @else
                                            <span class="badge text-bg-secondary">En attente</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <small>{{ $registration->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button
                                                wire:click="$dispatch('openRegistrationDetails', { registrationId: {{ $registration->id }} })"
                                                data-bs-toggle="modal" data-bs-target="#registrationDetailsModal"
                                                class="btn btn-outline-primary" title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button
                                                wire:click="$dispatch('openChangeClass', { registrationId: {{ $registration->id }} })"
                                                data-bs-toggle="modal" data-bs-target="#changeClassModal"
                                                class="btn btn-outline-warning" title="Changer de classe">
                                                <i class="bi bi-arrow-left-right"></i>
                                            </button>
                                            @if ($registration->abandoned)
                                                <button wire:click="markAsNotAbandoned({{ $registration->id }})"
                                                    wire:confirm="Retirer le statut d'abandon?"
                                                    class="btn btn-outline-success" title="Réactiver">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            @else
                                                <button wire:click="markAsAbandoned({{ $registration->id }})"
                                                    wire:confirm="Marquer cet élève comme ayant abandonné?"
                                                    class="btn btn-outline-warning" title="Marquer abandonné">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                </button>
                                            @endif
                                            <button wire:click="deleteRegistration({{ $registration->id }})"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cette inscription?"
                                                class="btn btn-outline-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                            <p class="mb-0">Aucune inscription trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($registrations->hasPages())
                <div class="card-footer border-0">
                    {{ $registrations->links() }}
                </div>
            @endif
        </div>

        {{-- Statistiques détaillées (sections collapsibles) --}}
        @if (count($stats['by_section']) > 0)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart-fill text-primary"></i> Statistiques détaillées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="statsAccordion">
                        {{-- Par Section --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSection">
                                    <i class="bi bi-diagram-3 me-2"></i> Par Section
                                </button>
                            </h2>
                            <div id="collapseSection" class="accordion-collapse collapse show"
                                data-bs-parent="#statsAccordion">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        @foreach ($stats['by_section'] as $section)
                                            <div class="col-md-4">
                                                <div class="card bg-body-secondary">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold">{{ $section['name'] }}</h6>
                                                        <h3 class="mb-0 text-primary">{{ $section['count'] }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Par Option --}}
                        @if (count($stats['by_option']) > 0)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseOption">
                                        <i class="bi bi-bookmark me-2"></i> Par Option
                                    </button>
                                </h2>
                                <div id="collapseOption" class="accordion-collapse collapse"
                                    data-bs-parent="#statsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            @foreach ($stats['by_option'] as $option)
                                                <div class="col-md-3">
                                                    <div class="card bg-body-secondary">
                                                        <div class="card-body">
                                                            <h6 class="fw-bold small">{{ $option['name'] }}</h6>
                                                            <small
                                                                class="text-muted d-block">{{ $option['section_name'] }}</small>
                                                            <h4 class="mb-0 text-primary">{{ $option['count'] }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Par Classe --}}
                        @if (count($stats['by_class']) > 0)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseClass">
                                        <i class="bi bi-door-closed me-2"></i> Par Classe
                                    </button>
                                </h2>
                                <div id="collapseClass" class="accordion-collapse collapse"
                                    data-bs-parent="#statsAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Classe</th>
                                                        <th>Option</th>
                                                        <th>Section</th>
                                                        <th class="text-end">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($stats['by_class'] as $class)
                                                        <tr>
                                                            <td><span
                                                                    class="badge bg-secondary">{{ $class['name'] }}</span>
                                                            </td>
                                                            <td><small>{{ $class['option_name'] }}</small></td>
                                                            <td><small>{{ $class['section_name'] }}</small></td>
                                                            <td class="text-end">
                                                                <strong>{{ $class['count'] }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </x-content.main-content-page>

    {{-- Offcanvas Filtres Avancés --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFilters"
        aria-labelledby="offcanvasFiltersLabel" wire:ignore.self>
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="offcanvasFiltersLabel">
                <i class="bi bi-funnel-fill"></i> Filtres avancés
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="bi bi-calendar-range"></i> Date début
                </label>
                <input type="date" wire:model.live="dateFrom" class="form-control">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="bi bi-calendar-range"></i> Date fin
                </label>
                <input type="date" wire:model.live="dateTo" class="form-control">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="bi bi-person-badge"></i> Type d'élève
                </label>
                <select wire:model.live="isOld" class="form-select">
                    <option value="">Tous</option>
                    <option value="1">Anciens élèves</option>
                    <option value="0">Nouveaux élèves</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="bi bi-exclamation-triangle"></i> Statut abandon
                </label>
                <select wire:model.live="abandoned" class="form-select">
                    <option value="">Tous</option>
                    <option value="0">Non abandonnés</option>
                    <option value="1">Abandonnés</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="bi bi-check-circle"></i> Statut inscription
                </label>
                <select wire:model.live="isRegistered" class="form-select">
                    <option value="">Tous</option>
                    <option value="1">Inscrits</option>
                    <option value="0">En attente</option>
                </select>
            </div>

            <div class="d-grid gap-2">
                <button wire:click="resetFilters" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">
                    <i class="bi bi-x-circle me-2"></i> Réinitialiser les filtres
                </button>
            </div>
        </div>
    </div>

    {{-- Composants modaux --}}
    <livewire:application.v2.registration.form.register-existing-student-form />
    <livewire:application.v2.registration.form.register-new-student-form />
    <livewire:application.v2.registration.form.change-class-form />
    <livewire:application.v2.registration.registration-details-modal />

    <!-- Indicateur de chargement -->
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />

    {{-- Styles personnalisés --}}
    @push('styles')
        <style>
            .avatar-circle {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: bold;
            }

            .bg-gradient-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .bg-gradient-info {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }

            .bg-gradient-danger {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }

            .bg-gradient-success {
                background: linear-gradient(135deg, #4facfe 0%, #43e97b 100%);
            }
        </style>
    @endpush

    @script
        <script>
            $wire.on('added', (event) => {
                Toastify({
                    text: event.message || 'Opération réussie!',
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    },
                }).showToast();
            });

            $wire.on('error', (event) => {
                Toastify({
                    text: event.message || 'Une erreur est survenue!',
                    duration: 4000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    },
                }).showToast();
            });
        </script>
    @endscript
</div>
