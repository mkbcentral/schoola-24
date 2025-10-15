<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
        <i class="bi bi-arrow-left-right me-2"></i>
        <h5 class="mb-0">Mouvements de stock pour : <span class="fw-bold">{{ $selectedArticleName }}</span></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive mb-3">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockMovements as $movement)
                        <tr>
                            <td>{{ $movement->movement_date }}</td>
                            <td>
                                @if ($movement->type === 'in')
                                    <span class="badge text-bg-success"><i
                                            class="bi bi-box-arrow-in-down me-1"></i>Entrée</span>
                                @else
                                    <span class="badge text-bg-danger"><i
                                            class="bi bi-box-arrow-up me-1"></i>Sortie</span>
                                @endif
                            </td>

                            <td class="fw-bold">{{ $movement->quantity }}</td>
                            <td>{{ $movement->note }}</td>
                            <td>
                                @if (!$movement->is_closed)
                                    <button class="btn btn-outline-warning btn-sm me-1"
                                        wire:click="editStockMovement({{ $movement->id }})" title="Éditer">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-sm"
                                        wire:click="closeStockMovement({{ $movement->id }})" title="Clôturer">
                                        <i class="bi bi-lock"></i>
                                    </button>
                                @else
                                    <span class="badge bg-secondary">Clôturé</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun mouvement trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Formulaire d'ajout/édition de mouvement -->
        <form wire:submit.prevent="addStockMovement" class=" p-3 rounded shadow-sm">
            <div class="row g-2 mb-2">
                <div class="col-md-3">
                    <select class="form-select" wire:model="movement_type">
                        <option value="in">Entrée</option>
                        <option value="out">Sortie</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" placeholder="Quantité" wire:model="movement_quantity"
                        min="1" required />
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" wire:model="movement_date" required />
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Note" wire:model="movement_note" />
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ $editMovementMode ? 'Modifier' : 'Ajouter' }}
                Mouvement</button>
            @if ($editMovementMode)
                <button type="button" class="btn btn-secondary ms-2" wire:click="resetMovementForm">Annuler</button>
            @endif
        </form>
    </div>
</div>
