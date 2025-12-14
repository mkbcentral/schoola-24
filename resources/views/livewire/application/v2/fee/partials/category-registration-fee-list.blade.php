<div class="card">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des catégories d'inscription</h5>
        <button class="btn btn-primary" wire:click="openCreateCategoryRegistrationFee">
            <i class="bi bi-plus-circle me-1"></i>Nouvelle catégorie
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Ancienne</th>
                        <th>Nombre de frais</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoryRegistrationFees as $categoryRegistrationFee)
                        <tr>
                            <td>{{ $categoryRegistrationFee->name }}</td>
                            <td>
                                @if ($categoryRegistrationFee->is_old)
                                    <span class="badge text-bg-warning">Oui</span>
                                @else
                                    <span class="badge text-bg-success">Non</span>
                                @endif
                            </td>
                            <td>{{ $categoryRegistrationFee->registration_fees_count ?? 0 }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                    wire:click="editCategoryRegistrationFee({{ $categoryRegistrationFee->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="deleteCategoryRegistrationFee({{ $categoryRegistrationFee->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette catégorie ?">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Aucune catégorie d'inscription
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
