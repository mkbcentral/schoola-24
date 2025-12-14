<div>
    {{-- En-tête de la page --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-2 fw-bold mb-1">
                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                Eleves avec Dettes
            </h2>
            <p class="text-muted mb-0">
                Liste des eleves ayant des impayes de {{ $minMonthsUnpaid }} mois ou plus
            </p>
        </div>
        <div class="d-flex gap-2">
            <button wire:click="toggleFilters" class="btn btn-outline-secondary">
                <i class="bi bi-funnel"></i>
                {{ $showFilters ? 'Masquer' : 'Afficher' }} filtres
            </button>
            <button wire:click="exportData" class="btn btn-outline-primary">
                <i class="bi bi-download"></i>
                Exporter
            </button>
        </div>
    </div>

    {{-- Message si aucune catégorie sélectionnée --}}
    @if (!$categoryFeeId)
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            Veuillez sélectionner une catégorie de frais pour afficher les données.
        </div>
    @endif

    {{-- Section des filtres --}}
    @if ($showFilters)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-sliders me-2"></i>
                    Filtres de recherche
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- Catégorie de frais --}}
                    <div class="col-md-3">
                        <label for="category" class="form-label fw-semibold">
                            <i class="bi bi-tag-fill me-1"></i>
                            Categorie de Frais
                        </label>
                        <select wire:model.live="categoryFeeId" id="category" class="form-select">
                            <option value="">Toutes les categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}">
                                    {{ $category['name'] }} ({{ $category['currency'] ?? 'USD' }})
                                </option>
                            @endforeach
                        </select>
                        @if ($currency)
                            <small class="text-muted">
                                <i class="bi bi-currency-exchange me-1"></i>
                                Devise: <strong>{{ $currency }}</strong>
                            </small>
                        @endif
                    </div>

                    {{-- Section --}}
                    <div class="col-md-3">
                        <label for="section" class="form-label fw-semibold">Section</label>
                        <select wire:model.live="sectionId" id="section" class="form-select">
                            <option value="">Toutes les sections</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Option --}}
                    <div class="col-md-2">
                        <label for="option" class="form-label fw-semibold">Option</label>
                        <select wire:model.live="optionId" id="option" class="form-select"
                            {{ !$sectionId ? 'disabled' : '' }}>
                            <option value="">Toutes les options</option>
                            @foreach ($options as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Classe --}}
                    <div class="col-md-2">
                        <label for="classRoom" class="form-label fw-semibold">Classe</label>
                        <select wire:model.live="classRoomId" id="classRoom" class="form-select"
                            {{ !$optionId ? 'disabled' : '' }}>
                            <option value="">Toutes les classes</option>
                            @foreach ($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mois minimum impayes --}}
                    <div class="col-md-2">
                        <label for="minMonths" class="form-label fw-semibold">Mois impayes (min)</label>
                        <select wire:model.live="minMonthsUnpaid" id="minMonths" class="form-select">
                            <option value="1">1 mois ou plus</option>
                            <option value="2">2 mois ou plus</option>
                            <option value="3">3 mois ou plus</option>
                            <option value="4">4 mois ou plus</option>
                            <option value="5">5 mois ou plus</option>
                        </select>
                    </div>

                    {{-- Recherche --}}
                    <div class="col-md-7">
                        <label for="search" class="form-label fw-semibold">Rechercher un eleve</label>
                        <input type="text" wire:model.live.debounce.500ms="search" id="search"
                            class="form-control" placeholder="Nom ou code de l'eleve...">
                    </div>

                    {{-- Bouton reset --}}
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="resetFilters" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-1"></i>
                            Reinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Statistiques globales --}}
    @if (!empty($statistics) && $categoryFeeId)
        <div class="row g-3 mb-4" wire:loading.remove.delay.longer>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 text-uppercase"
                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    Total Eleves
                                </p>
                                <h3 class="mb-0 fw-bold">{{ $statistics['total_students'] }}</h3>
                            </div>
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="bi bi-people-fill text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 text-uppercase"
                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    Dette Totale ({{ $currency }})
                                </p>
                                <h3 class="mb-0 fw-bold text-danger">
                                    {{ number_format($statistics['total_debt_amount'], 0, ',', ' ') }}
                                </h3>
                            </div>
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="bi bi-cash-stack text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 text-uppercase"
                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    Mois Moyen Impaye
                                </p>
                                <h3 class="mb-0 fw-bold">{{ number_format($statistics['average_months_unpaid'], 1) }}
                                </h3>
                            </div>
                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="bi bi-calendar-x text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 text-uppercase"
                                    style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    Max Mois Impaye
                                </p>
                                <h3 class="mb-0 fw-bold text-danger">{{ $statistics['max_months_unpaid'] }}</h3>
                            </div>
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Tableau des eleves --}}
    @if (!empty($studentsWithDebt) && $categoryFeeId)
        <div class="card border-0 shadow-sm" wire:loading.remove.delay.longer>
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        Liste des eleves endettes
                        <span class="badge bg-danger ms-2">{{ count($studentsWithDebt) }}</span>
                    </h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">#</th>
                                <th>Code</th>
                                <th>Nom de l'eleve</th>
                                <th>Section/Option/Classe</th>
                                <th class="text-center">Mois Inscription</th>
                                <th class="text-center">Mois Dus</th>
                                <th class="text-center">Mois Payes</th>
                                <th class="text-center">Mois Impayes</th>
                                <th class="text-end">Dette (FC)</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentsWithDebt as $index => $student)
                                <tr>
                                    <td class="px-3 fw-semibold">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $student['student_code'] }}</span>
                                    </td>
                                    <td class="fw-semibold">{{ $student['student_name'] }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $student['section_name'] }} /
                                            {{ $student['option_name'] }} /
                                            {{ $student['class_room_name'] }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-bg-info">{{ $student['registration_month'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge text-bg-primary">{{ $student['total_months_expected'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-bg-success">{{ $student['total_months_paid'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-bg-danger">{{ $student['months_unpaid'] }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        {{ number_format($student['total_debt_amount'], 0, ',', ' ') }}
                                        {{ $currency }}
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="viewStudentDetails({{ $student['student_id'] }})"
                                            class="btn btn-sm btn-outline-info" title="Voir details">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif ($categoryFeeId)
        <div class="card border-0 shadow-sm" wire:loading.remove.delay.longer>
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-clipboard-check text-success" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-muted mb-2">Aucun eleve avec dette trouve</h5>
                <p class="text-muted mb-0">
                    Tous les eleves sont a jour avec leurs paiements ou aucun eleve ne correspond aux criteres
                    selectionnes.
                </p>
            </div>
        </div>
    @endif

    {{-- Modal des details d'un eleve --}}
    @if ($selectedStudent)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-gradient text-white border-0"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="modal-title">
                            <i class="bi bi-person-circle me-2"></i>
                            Details de l'eleve
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="closeStudentDetails"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong>Nom:</strong> {{ $selectedStudent['student_name'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Code:</strong> {{ $selectedStudent['student_code'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Section:</strong> {{ $selectedStudent['section_name'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Option:</strong> {{ $selectedStudent['option_name'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Classe:</strong> {{ $selectedStudent['class_room_name'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Date d'inscription:</strong> {{ $selectedStudent['registration_date'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Mois d'inscription:</strong> {{ $selectedStudent['registration_month'] }}
                            </div>
                            <div class="col-md-6">
                                <strong>Mois impayes:</strong>
                                <span class="badge bg-danger">{{ $selectedStudent['months_unpaid'] }}</span>
                            </div>
                            <div class="col-12">
                                <strong>Liste des mois impayes:</strong>
                                <div class="mt-2">
                                    @foreach ($selectedStudent['unpaid_months'] as $month)
                                        <span class="badge bg-warning text-dark me-1 mb-1">{{ $month }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <strong>Montant total du:</strong>
                                <span class="text-danger fw-bold">
                                    {{ number_format($selectedStudent['total_amount_due'], 0, ',', ' ') }}
                                    {{ $currency }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Montant paye:</strong>
                                <span class="text-success fw-bold">
                                    {{ number_format($selectedStudent['total_amount_paid'], 0, ',', ' ') }}
                                    {{ $currency }}
                                </span>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-danger mb-0">
                                    <strong>Dette totale:</strong>
                                    <span class="fs-5 fw-bold">
                                        {{ number_format($selectedStudent['total_debt_amount'], 0, ',', ' ') }}
                                        {{ $currency }}
                                    </span>
                                </div>
                            </div>
                            @if ($selectedStudent['responsible_name'])
                                <div class="col-md-6">
                                    <strong>Responsable:</strong> {{ $selectedStudent['responsible_name'] }}
                                </div>
                            @endif
                            @if ($selectedStudent['responsible_phone'])
                                <div class="col-md-6">
                                    <strong>Telephone:</strong> {{ $selectedStudent['responsible_phone'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" wire:click="closeStudentDetails">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
