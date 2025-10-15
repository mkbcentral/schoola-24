<div>
    <x-navigation.bread-crumb icon='bi bi-cash-coin' label="Stock">
        <x-navigation.bread-crumb-item label='Gestion des articles' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header text-bg-primary  d-flex align-items-center justify-content-between">
                <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>Catalogue des articles</h4>
                <span class="badge bg-text-info fs-6">{{ count($articles) }} articles</span>
            </div>
            <div class="card-body">
                <!-- Formulaire d'ajout/modification d'article -->
                <form wire:submit.prevent="{{ $editMode ? 'updateArticle' : 'createArticle' }}"
                    class="mb-4 p-3 rounded  shadow-sm">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <x-form.label value="{{ __('Nom') }}" class="text-secondary" />
                            <x-form.input type="text" wire:model.blurr="name" :error="'name'" />
                            <x-errors.validation-error value="name" />
                        </div>
                        <div class="col-md-2">
                            <x-form.label value="{{ __('Référence') }}" class="text-secondary" />
                            <x-form.input type="text" wire:model.blurr="reference" :error="'reference'" />
                            <x-errors.validation-error value="reference" />
                        </div>
                        <div class="col-md-2">
                            <x-form.label value="{{ __('Unité') }}" class="text-secondary" />
                            <x-form.input type="text" wire:model.blurr="unit" :error="'unit'" />
                            <x-errors.validation-error value="unit" />
                        </div>
                        <div class="col-md-3">
                            <x-form.label value="{{ __('Description') }}" class="text-secondary" />
                            <x-form.input type="text" wire:model.blurr="description" :error="'description'" />
                            <x-errors.validation-error value="description" />
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="submit"
                                class="btn btn-primary me-2 mt-4">{{ $editMode ? 'Modifier' : 'Ajouter' }}</button>
                            @if ($editMode)
                                <button type="button" class="btn btn-secondary" wire:click="resetForm">Annuler</button>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Tableau des articles -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Nom</th>
                                <th>Référence</th>
                                <th>Unité</th>
                                <th>Description</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($articles as $article)
                                <tr>
                                    <td class="fw-bold">{{ $article->name }}</td>
                                    <td><span class="text-muted">{{ $article->reference }}</span></td>
                                    <td><span class="badge text-bg-secondary">{{ $article->unit }}</span></td>
                                    <td>{{ $article->description }}</td>
                                    <td>
                                        <span
                                            class="badge bg-gradient text-bg-{{ $article->stock > 0 ? 'success' : 'danger' }} fs-6">
                                            <i class="bi bi-archive me-1"></i>{{ $article->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm me-1" title="Voir mouvements"
                                            wire:click="showStockMovements({{ $article->id }})">
                                            <i class="bi bi-arrow-left-right"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" title="Éditer"
                                            wire:click="editArticle({{ $article->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Aucun article trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Section des mouvements de stock -->
                @if ($showMovements && $selectedArticle)
                    <div class="mt-4">
                        @livewire('stock.article-stock-movement-manager', ['articleId' => $selectedArticle->id], key('movements-' . $selectedArticle->id))
                    </div>
                @endif
            </div>
        </div>
    </x-content.main-content-page>
</div>
