<div>
    <x-navigation.bread-crumb icon='bi bi-tag-fill' label="Stock">
        <x-navigation.bread-crumb-item label='Catégories' />
        <x-navigation.bread-crumb-item label='Catalogue' isLinked=true link="stock.main" />
        <x-navigation.bread-crumb-item label='Dashboard Stock' isLinked=true link="stock.dashboard" />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <!-- En-tête avec statistiques -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm text-bg-info ">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold">
                                    <i class="bi bi-tag-fill me-2"></i>Catégories d'articles
                                </h4>
                                <p class="mb-0 opacity-75">
                                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-end">
                                <h2 class="mb-0 fw-bold">{{ $categories->total() }}</h2>
                                <small class="opacity-75">Catégories</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de création/édition -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-info">
                        <i class="bi bi-{{ $editMode ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                        {{ $editMode ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
                    </h5>
                    @if ($editMode)
                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetForm">
                            <i class="bi bi-x-circle me-1"></i>Annuler
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body bg-light">
                <form wire:submit.prevent="{{ $editMode ? 'updateCategory' : 'createCategory' }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag me-1 text-info"></i>Nom de la catégorie *
                            </label>
                            <x-form.input type="text" wire:model.blur="form.name" :error="'form.name'"
                                placeholder="Ex: Fournitures scolaires" />
                            <x-errors.validation-error value="form.name" />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-card-text me-1 text-info"></i>Description
                            </label>
                            <x-form.input type="text" wire:model.blur="form.description" :error="'form.description'"
                                placeholder="Description courte (optionnel)" />
                            <x-errors.validation-error value="form.description" />
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-palette me-1 text-info"></i>Couleur *
                            </label>

                            <!-- Input color caché -->
                            <input type="hidden" wire:model="form.color">

                            <!-- Couleurs suggérées -->
                            <div class="d-flex gap-1 flex-wrap">
                                @foreach ($suggestedColors as $suggestedColor)
                                    <button type="button"
                                        class="btn btn-sm p-0 border {{ $form->color === $suggestedColor ? 'border-primary border-3' : '' }}"
                                        wire:click="$set('form.color', '{{ $suggestedColor }}')"
                                        style="width: 35px; height: 35px; background-color: {{ $suggestedColor }};"
                                        title="{{ $suggestedColor }}">
                                        @if ($form->color === $suggestedColor)
                                            <i class="bi bi-check text-white"></i>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                            <x-errors.validation-error value="form.color" />
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-info w-100 text-white">
                                <i class="bi bi-{{ $editMode ? 'check-circle' : 'plus-circle' }} me-1"></i>
                                {{ $editMode ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des catégories -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-info">
                            <i class="bi bi-list-ul me-2"></i>Liste des catégories
                        </h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('stock.main') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Retour au catalogue
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-info">
                            <tr>
                                <th style="width: 80px;" class="text-center">Couleur</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th style="width: 120px;" class="text-center">Articles</th>
                                <th style="width: 150px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge rounded-pill"
                                            style="background-color: {{ $category->color }}; width: 50px; height: 30px;">
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ $category->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            {{ $category->description ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-bg-secondary fs-6">
                                            {{ $category->articles_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="editCategory({{ $category->id }})"
                                            class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button wire:click="deleteCategory({{ $category->id }})"
                                            class="btn btn-sm btn-danger" title="Supprimer"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer cette catégorie ?"
                                            @if ($category->articles_count > 0) disabled @endif>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <div class="py-4">
                                            <i class="bi bi-inbox text-center fs-1  mb-3 text-info opacity-50"></i>
                                            <h5 class="text-muted">Aucune catégorie créée</h5>
                                            <p class="small mb-0">Commencez par créer votre première catégorie
                                                ci-dessus</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($categories->hasPages())
                    <div class="card-footer bg-white border-top">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </x-content.main-content-page>
</div>
