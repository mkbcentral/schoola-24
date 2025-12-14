<div class="card">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des frais d'inscription</h5>
        <button class="btn btn-primary" wire:click="openCreateRegistrationFee">
            <i class="bi bi-plus-circle me-1"></i>Nouveau frais
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Montant</th>
                        <th>Option</th>
                        <th>Catégorie</th>
                        <th>Année scolaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrationFees as $registrationFee)
                        <tr>
                            <td>{{ $registrationFee->name }}</td>
                            <td>
                                {{ number_format($registrationFee->amount, 2) }}
                                <span class="text-muted">{{ $registrationFee->currency }}</span>
                            </td>
                            <td>{{ $registrationFee->option->name ?? 'N/A' }}</td>
                            <td>{{ $registrationFee->category_registration_fee->name ?? 'N/A' }}</td>
                            <td>{{ $registrationFee->school_year->name ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                    wire:click="editRegistrationFee({{ $registrationFee->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="deleteRegistrationFee({{ $registrationFee->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce frais ?">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Aucun frais d'inscription
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
