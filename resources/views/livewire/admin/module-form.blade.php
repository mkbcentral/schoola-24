<div>
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.modules.index') }}" wire:navigate class="text-decoration-none">
                                <i class="bi bi-puzzle me-1"></i>Modules
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $isEditing ? 'Modifier' : 'Créer' }}
                        </li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-{{ $isEditing ? 'pencil-square' : 'plus-circle' }} text-primary me-2"></i>
                    {{ $isEditing ? 'Modifier le module' : 'Créer un nouveau module' }}
                </h3>
                <p class="text-muted mb-0 mt-1">
                    {{ $isEditing ? 'Modifiez les informations du module' : 'Remplissez les informations pour créer un nouveau module' }}
                </p>
            </div>
            <button type="button" wire:click="cancel" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </button>
        </div>

        {{-- Messages Flash --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                 style="border-radius: 12px; border: none;">
                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert"
                 style="border-radius: 12px; border: none;">
                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Formulaire --}}
        <form wire:submit.prevent="save">
            <div class="row">
                {{-- Colonne principale --}}
                <div class="col-lg-8">
                    {{-- Informations de base --}}
                    <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-info-circle text-primary me-2"></i>Informations générales
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input-group
                                        label="Nom du module"
                                        icon="tag"
                                        :required="true"
                                        :useSlot="true"
                                        :error="$errors->first('name')">
                                        <input type="text" wire:model="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="Ex: Gestion des Paiements">
                                    </x-form.input-group>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input-group
                                        label="Code unique"
                                        icon="code-square"
                                        :required="true"
                                        :useSlot="true"
                                        :error="$errors->first('code')">
                                        <input type="text" wire:model="code"
                                               class="form-control @error('code') is-invalid @enderror"
                                               placeholder="Ex: payment">
                                    </x-form.input-group>
                                </div>
                            </div>

                            <x-form.input-group
                                label="Description"
                                icon="text-paragraph"
                                :useSlot="true"
                                :error="$errors->first('description')">
                                <textarea wire:model="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="4" placeholder="Décrivez les fonctionnalités et l'utilité de ce module..."></textarea>
                            </x-form.input-group>
                        </div>
                    </div>

                    {{-- Fonctionnalités --}}
                    <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-semibold">
                                    <i class="bi bi-list-stars text-primary me-2"></i>Fonctionnalités
                                    @if(count($features) > 0)
                                        <span class="badge bg-primary ms-2">{{ count($features) }}</span>
                                    @endif
                                </h5>
                                <button type="button" class="btn btn-success btn-sm" wire:click="addFeature">
                                    <i class="bi bi-plus-lg me-1"></i>Ajouter une fonctionnalité
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @foreach ($features as $index => $feature)
                                <div class="card mb-3 border" style="border-radius: 8px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-secondary">Fonctionnalité #{{ $index + 1 }}</span>
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    wire:click="removeFeature({{ $index }})"
                                                    title="Supprimer">
                                                <i class="bi bi-trash-fill me-1"></i>Supprimer
                                            </button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="form-label small fw-semibold">Nom <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       wire:model="features.{{ $index }}.name"
                                                       class="form-control @error('features.'.$index.'.name') is-invalid @enderror"
                                                       placeholder="Ex: Liste des paiements">
                                                @error('features.'.$index.'.name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Route <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       wire:model="features.{{ $index }}.url"
                                                       class="form-control @error('features.'.$index.'.url') is-invalid @enderror"
                                                       placeholder="Ex: payment.list">
                                                @error('features.'.$index.'.url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Icône</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="{{ $features[$index]['icon'] ?? 'bi bi-circle' }}"></i>
                                                    </span>
                                                    <input type="text"
                                                           wire:model="features.{{ $index }}.icon"
                                                           class="form-control"
                                                           placeholder="bi bi-icon">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if (empty($features))
                                <div class="text-center py-5 text-muted border rounded" style="border-style: dashed !important;">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                    <p class="mb-2 fw-semibold">Aucune fonctionnalité ajoutée</p>
                                    <p class="small mb-3">Les fonctionnalités permettent de définir les menus et accès du module</p>
                                    <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addFeature">
                                        <i class="bi bi-plus-lg me-1"></i>Ajouter la première fonctionnalité
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Colonne latérale --}}
                <div class="col-lg-4">
                    {{-- Configuration --}}
                    <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-gear text-primary me-2"></i>Configuration
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <x-form.input-group
                                label="Prix (FC)"
                                icon="cash"
                                :required="true"
                                :useSlot="true"
                                :error="$errors->first('price')">
                                <input type="number" wire:model="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       step="0.01" min="0" placeholder="50000">
                            </x-form.input-group>

                            <x-form.input-group
                                label="Ordre d'affichage"
                                icon="arrow-down-up"
                                :useSlot="true"
                                :error="$errors->first('sort_order')">
                                <input type="number" wire:model="sort_order"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       min="0" placeholder="1">
                            </x-form.input-group>

                            <x-form.input-group
                                label="Icône du module"
                                icon="emoji-smile"
                                :useSlot="true"
                                :error="$errors->first('icon')">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="{{ $icon ?: 'bi bi-puzzle-fill' }}"></i>
                                    </span>
                                    <input type="text" wire:model.live="icon"
                                           class="form-control @error('icon') is-invalid @enderror"
                                           placeholder="bi bi-puzzle-fill">
                                </div>
                                <x-slot name="helperSlot">
                                    <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Parcourir les icônes
                                    </a>
                                </x-slot>
                            </x-form.input-group>

                            <hr class="my-3">

                            <div class="form-check form-switch">
                                <input type="checkbox" wire:model="is_active" class="form-check-input" id="is_active" role="switch">
                                <label for="is_active" class="form-check-label fw-semibold">
                                    Module actif
                                </label>
                                <p class="text-muted small mb-0 mt-1">
                                    Les modules inactifs ne sont pas visibles pour les écoles
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <div class="card-body p-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-{{ $isEditing ? 'check-lg' : 'plus-lg' }} me-2"></i>
                                    {{ $isEditing ? 'Mettre à jour le module' : 'Créer le module' }}
                                </button>
                                <button type="button" wire:click="cancel" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-2"></i>Annuler
                                </button>
                            </div>

                            @if($isEditing)
                                <hr class="my-3">
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Module ID: <code>{{ $moduleId }}</code>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
