<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des catégories de frais</h5>
        <button class="btn btn-primary" wire:click="openCreateCategoryFee">
            <i class="bi bi-plus-circle me-1"></i>Nouvelle catégorie
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Année scolaire</th>
                        <th>Devise</th>
                        <th>Frais d'état</th>
                        <th>Paiement échelonné</th>
                        <th>Nombre de frais</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoryFees as $categoryFee)
                        <tr>
                            <td>{{ $categoryFee->name }}</td>
                            <td>{{ $categoryFee->school_year->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge text-bg-secondary">{{ $categoryFee->currency }}</span>
                            </td>
                            <td>
                                @if ($categoryFee->is_state_fee)
                                    <span class="badge text-bg-info">Oui</span>
                                @else
                                    <span class="badge text-bg-secondary">Non</span>
                                @endif
                            </td>
                            <td>
                                @if ($categoryFee->is_paid_in_installment)
                                    <span class="badge text-bg-success">Oui</span>
                                @else
                                    <span class="badge text-bg-secondary">Non</span>
                                @endif
                            </td>
                            <td>{{ $categoryFee->scolar_fees_count ?? 0 }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                    wire:click="editCategoryFee({{ $categoryFee->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="deleteCategoryFee({{ $categoryFee->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette catégorie ?">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Aucune catégorie de frais
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
