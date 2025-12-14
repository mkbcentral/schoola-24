<div class="card">
    <div class="card-header  d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Liste des frais scolaires</h5>
        <button class="btn btn-primary" wire:click="openCreateScolarFee">
            <i class="bi bi-plus-circle me-1"></i>Nouveau frais
        </button>
    </div>
    <div class="card-body">
        <!-- Filtres -->
        <div class="row mb-3 align-items-end">
            <div class="col-md-4">
                <label for="filterScolarCategoryFee" class="form-label">
                    <i class="bi bi-folder me-1"></i>Catégorie de frais
                </label>
                <select class="form-select" id="filterScolarCategoryFee" wire:model.live="filterScolarCategoryFeeId">
                    <option value="">-- Toutes les catégories --</option>
                    @foreach ($availableCategoryFees as $categoryFee)
                        <option value="{{ $categoryFee->id }}">{{ $categoryFee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterScolarClassRoom" class="form-label">
                    <i class="bi bi-door-open me-1"></i>Classe
                </label>
                <select class="form-select" id="filterScolarClassRoom" wire:model.live="filterScolarClassRoomId">
                    <option value="">-- Toutes les classes --</option>
                    @foreach ($availableClassRooms as $classRoom)
                        <option value="{{ $classRoom->id }}">{{ $classRoom->getOriginalClassRoomName() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-outline-secondary w-100" wire:click="resetScolarFilters">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser
                </button>
            </div>
        </div>

        <!-- Compteur de résultats -->
        <div class="mb-2">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                {{ method_exists($scolarFees, 'total') ? $scolarFees->total() : $scolarFees->count() }} frais
                scolaire(s) trouvé(s)
                @if ($filterScolarCategoryFeeId || $filterScolarClassRoomId)
                    <span class="badge bg-primary ms-1">Filtré</span>
                @endif
            </small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Montant</th>
                        <th>Catégorie</th>
                        <th>Classe</th>
                        <th>Modifié</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scolarFees as $scolarFee)
                        <tr>
                            <td>{{ $scolarFee->name }}</td>
                            <td>
                                {{ number_format($scolarFee->amount, 2) }}
                                <span class="text-muted">
                                    {{ $scolarFee->category_fee->currency ?? '' }}
                                </span>
                            </td>
                            <td>{{ $scolarFee->categoryFee->name ?? 'N/A' }}</td>
                            <td>{{ $scolarFee->classRoom->getOriginalClassRoomName() ?? 'N/A' }}</td>
                            <td>
                                @if ($scolarFee->is_changed)
                                    <span class="badge text-bg-warning">Oui</span>
                                @else
                                    <span class="badge text-bg-secondary">Non</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                    wire:click="editScolarFee({{ $scolarFee->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="deleteScolarFee({{ $scolarFee->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce frais ?">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Aucun frais scolaire
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if (method_exists($scolarFees, 'hasPages') && $scolarFees->hasPages())
            <div class="mt-3">
                {{ $scolarFees->links() }}
            </div>
        @endif
    </div>
</div>
