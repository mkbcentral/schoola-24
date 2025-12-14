<div>
    {{-- Modal Détails de l'Inscription --}}
    <x-modal.build-modal-fixed idModal="registrationDetailsModal" size="lg" bg="bg-info text-white"
        headerLabel="Détails de l'inscription" headerLabelIcon="bi bi-eye">

        @if ($registration)
            <div class="row g-3">
                {{-- Informations Élève --}}
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-primary mb-3">
                                <i class="bi bi-person-fill"></i> Informations de l'élève
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-2"><strong>Nom:</strong> {{ $registration->student->name }}</p>
                                    <p class="mb-2"><strong>Genre:</strong>
                                        @if ($registration->student->gender === 'M')
                                            <span class="badge bg-info">Masculin</span>
                                        @else
                                            <span class="badge bg-danger">Féminin</span>
                                        @endif
                                    </p>
                                    <p class="mb-2"><strong>Âge:</strong>
                                        {{ $registration->student->getFormattedAg() }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2"><strong>Lieu de naissance:</strong>
                                        {{ $registration->student->place_of_birth }}</p>
                                    <p class="mb-2"><strong>Date de naissance:</strong>
                                        {{ \Carbon\Carbon::parse($registration->student->date_of_birth)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informations Inscription --}}
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title fw-bold text-primary mb-3">
                                <i class="bi bi-card-checklist"></i> Informations d'inscription
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-2"><strong>Code:</strong>
                                        <code class="text-primary">{{ $registration->code }}</code>
                                    </p>
                                    <p class="mb-2"><strong>Section:</strong>
                                        {{ $registration->classRoom->option->section->name }}</p>
                                    <p class="mb-2"><strong>Option:</strong>
                                        {{ $registration->classRoom->option->name }}</p>
                                    <p class="mb-2"><strong>Classe:</strong>
                                        <span class="badge bg-secondary">{{ $registration->classRoom->name }}</span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2"><strong>Type:</strong>
                                        @if ($registration->is_old)
                                            <span class="badge bg-primary">Ancien élève</span>
                                        @else
                                            <span class="badge bg-success">Nouvel élève</span>
                                        @endif
                                    </p>
                                    <p class="mb-2"><strong>Statut:</strong>
                                        @if ($registration->abandoned)
                                            <span class="badge bg-warning">Abandonné</span>
                                        @elseif($registration->is_registered)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">En attente</span>
                                        @endif
                                    </p>
                                    <p class="mb-2"><strong>Date d'inscription:</strong>
                                        {{ $registration->created_at->format('d/m/Y') }}
                                    </p>
                                    @if ($registration->class_changed)
                                        <p class="mb-2">
                                            <span class="badge bg-info">
                                                <i class="bi bi-arrow-left-right"></i> Classe changée
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Frais d'inscription --}}
                @if ($registration->registrationFee)
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-primary mb-3">
                                    <i class="bi bi-cash-coin"></i> Frais d'inscription
                                </h6>
                                <p class="mb-2">
                                    <strong>Montant:</strong>
                                    <span class="text-success fw-bold">
                                        {{ number_format($registration->registrationFee->amount, 2) }} FC
                                    </span>
                                </p>
                                @if ($registration->is_fee_exempted)
                                    <p class="mb-0">
                                        <span class="badge bg-warning">
                                            <i class="bi bi-exclamation-triangle"></i> Exempté des frais
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Responsable --}}
                @if ($registration->student->responsibleStudent)
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-primary mb-3">
                                    <i class="bi bi-person-badge"></i> Responsable
                                </h6>
                                <p class="mb-2"><strong>Nom:</strong>
                                    {{ $registration->student->responsibleStudent->name }}</p>
                                @if ($registration->student->responsibleStudent->phone)
                                    <p class="mb-2"><strong>Téléphone:</strong>
                                        {{ $registration->student->responsibleStudent->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Bouton de fermeture --}}
                <div class="col-12">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i> Fermer
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </x-modal.build-modal-fixed>


</div>
