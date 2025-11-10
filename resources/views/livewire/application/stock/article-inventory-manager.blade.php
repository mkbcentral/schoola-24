<div>
    <x-navigation.bread-crumb icon='bi bi-clipboard-check' label="Stock">
        <x-navigation.bread-crumb-item label='Inventaire Physique' />
        <x-navigation.bread-crumb-item label='Catalogue' isLinked=true link="stock.main" />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="stock.dashboard" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- En-tête avec statistiques -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0">
                                    <i class="bi bi-clipboard-check text-primary me-2"></i>
                                    Inventaire Physique
                                </h4>
                                <p class="text-muted mb-0 small">Gestion des comptages et ajustements de stock</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-primary" wire:click="openCreateModal">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Nouvel Inventaire
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-clipboard-data fs-4 text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-0 small">Total</h6>
                                <h4 class="mb-0 fw-bold">{{ $stats['total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="bi bi-check-circle fs-4 text-success"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-0 small">Conformes</h6>
                                <h4 class="mb-0 fw-bold">{{ $stats['conforme'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                <i class="bi bi-arrow-up-circle fs-4 text-info"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-0 small">Excédents</h6>
                                <h4 class="mb-0 fw-bold">{{ $stats['excedent'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="bi bi-exclamation-circle fs-4 text-danger"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-0 small">Manquants</h6>
                                <h4 class="mb-0 fw-bold">{{ $stats['manquant'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small">Rechercher</label>
                        <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                            placeholder="Nom ou référence de l'article...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Statut</label>
                        <select class="form-select" wire:model.live="statusFilter">
                            <option value="">Tous</option>
                            <option value="conforme">Conforme</option>
                            <option value="excedent">Excédent</option>
                            <option value="manquant">Manquant</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Date début</label>
                        <input type="date" class="form-control" wire:model.live="dateFrom">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Date fin</label>
                        <input type="date" class="form-control" wire:model.live="dateTo">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des inventaires -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Article</th>
                                <th>Catégorie</th>
                                <th class="text-center">Stock Théorique</th>
                                <th class="text-center">Stock Réel</th>
                                <th class="text-center">Écart</th>
                                <th class="text-center">Statut</th>
                                <th>Agent</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventories as $inventory)
                                <tr>
                                    <td>
                                        <span class="badge text-bg-light text-dark">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $inventory->inventory_date->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $inventory->article->name }}</strong>
                                        @if ($inventory->article->reference)
                                            <br><small class="text-muted">Réf:
                                                {{ $inventory->article->reference }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($inventory->article->category)
                                            <span class="badge text-white"
                                                style="background-color: {{ $inventory->article->category->color }};">
                                                {{ $inventory->article->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-bg-secondary fs-6">
                                            {{ $inventory->expected_quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-bg-primary fs-6">
                                            {{ $inventory->actual_quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-bg-{{ $inventory->status_badge }} fs-6 fw-bold">
                                            {{ $inventory->difference > 0 ? '+' : '' }}{{ $inventory->difference }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-bg-{{ $inventory->status_badge }}">
                                            <i class="bi {{ $inventory->status_icon }} me-1"></i>
                                            {{ $inventory->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $inventory->user->name ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Modifier"
                                                wire:click="editInventory({{ $inventory->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Supprimer"
                                                wire:click="deleteInventory({{ $inventory->id }})"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cet inventaire ?">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @if ($inventory->note)
                                    <tr class="table-light">
                                        <td colspan="9" class="py-2 text-center">
                                            <small class="text-center d-flex justify-content-center">
                                                <i class="bi bi-chat-left-text me-1"></i>
                                                <strong>Note:</strong> {{ $inventory->note }}
                                            </small>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Aucun inventaire enregistré</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($inventories->hasPages())
                <div class="card-footer text-bg-white border-top">
                    {{ $inventories->links() }}
                </div>
            @endif
        </div>
    </x-content.main-content-page>

    <!-- Modal Créer/Éditer -->
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-clipboard-check me-2"></i>
                            {{ $isEditing ? 'Modifier l\'inventaire' : 'Nouvel inventaire' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cancel"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="{{ $isEditing ? 'updateInventory' : 'createInventory' }}">
                            <div class="row g-3">
                                <!-- Article -->
                                <div class="col-md-12">
                                    <label class="form-label">Article <span class="text-danger">*</span></label>
                                    <select class="form-select @error('form.article_id') is-invalid @enderror"
                                        wire:model.live="form.article_id" {{ $isEditing ? 'disabled' : '' }}>
                                        <option value="">Sélectionner un article</option>
                                        @foreach ($articles as $article)
                                            <option value="{{ $article->id }}">
                                                {{ $article->name }}
                                                @if ($article->reference)
                                                    (Réf: {{ $article->reference }})
                                                @endif
                                                - Stock actuel: {{ $article->stock }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('form.article_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date -->
                                <div class="col-md-6">
                                    <label class="form-label">Date d'inventaire <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control @error('form.inventory_date') is-invalid @enderror"
                                        wire:model="form.inventory_date">
                                    @error('form.inventory_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Stock théorique -->
                                <div class="col-md-6">
                                    <label class="form-label">Stock Théorique <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control @error('form.expected_quantity') is-invalid @enderror"
                                        wire:model.live="form.expected_quantity" min="0">
                                    @error('form.expected_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Stock réel compté -->
                                <div class="col-md-12">
                                    <label class="form-label">Stock Réel Compté <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control form-control-lg @error('form.actual_quantity') is-invalid @enderror"
                                        wire:model.live="form.actual_quantity" min="0"
                                        placeholder="Entrez la quantité comptée physiquement">
                                    @error('form.actual_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Résultat calculé automatiquement -->
                                @if ($form->difference !== 0 || $form->actual_quantity > 0)
                                    <div class="col-md-12">
                                        <div
                                            class="alert alert-{{ $form->status == 'conforme' ? 'success' : ($form->status == 'excedent' ? 'info' : 'danger') }} mb-0">
                                            <div class="d-flex align-items-center">
                                                <i
                                                    class="bi {{ $form->difference == 0 ? 'bi-check-circle-fill' : ($form->difference > 0 ? 'bi-arrow-up-circle-fill' : 'bi-exclamation-circle-fill') }} fs-3 me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">
                                                        Écart:
                                                        <strong>{{ $form->difference > 0 ? '+' : '' }}{{ $form->difference }}</strong>
                                                        unités
                                                    </h6>
                                                    <p class="mb-0 small">
                                                        Statut: <strong>{{ ucfirst($form->status) }}</strong>
                                                        @if ($form->difference > 0)
                                                            - Surplus de {{ $form->difference }} unité(s)
                                                        @elseif ($form->difference < 0)
                                                            - Manque de {{ abs($form->difference) }} unité(s)
                                                        @else
                                                            - Stock conforme
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Notes -->
                                <div class="col-md-12">
                                    <label class="form-label">Notes / Observations</label>
                                    <textarea class="form-control @error('form.note') is-invalid @enderror" wire:model="form.note" rows="3"
                                        placeholder="Ajoutez des observations sur cet inventaire..."></textarea>
                                    @error('form.note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancel">
                            <i class="bi bi-x-circle me-1"></i>
                            Annuler
                        </button>
                        <button type="button" class="btn btn-primary"
                            wire:click="{{ $isEditing ? 'updateInventory' : 'createInventory' }}">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ $isEditing ? 'Mettre à jour' : 'Enregistrer' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
