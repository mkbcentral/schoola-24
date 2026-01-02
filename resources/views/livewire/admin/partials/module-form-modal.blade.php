{{-- Modal Créer/Modifier Module - Bootstrap Native --}}
<div class="modal fade" id="moduleFormModal" tabindex="-1" aria-labelledby="moduleFormModalLabel" aria-hidden="true"
     wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="moduleFormModalLabel">
                    <i class="bi bi-{{ $isEditing ? 'pencil-square' : 'plus-circle' }} text-primary me-2"></i>
                    {{ $isEditing ? 'Modifier le module' : 'Créer un module' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <form wire:submit.prevent="save">
                <div class="modal-body p-4">
                    {{-- Informations de base --}}
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.input-group
                                label="Nom"
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
                                label="Code"
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

                    <div class="row">
                        <div class="col-md-6">
                            <x-form.input-group
                                label="Prix (FC)"
                                icon="cash"
                                :required="true"
                                :useSlot="true"
                                :error="$errors->first('price')">
                                <input type="number" wire:model="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       step="0.01" placeholder="50000">
                            </x-form.input-group>
                        </div>

                        <div class="col-md-6">
                            <x-form.input-group
                                label="Icône Bootstrap"
                                icon="emoji-smile"
                                :useSlot="true"
                                :error="$errors->first('icon')">
                                <input type="text" wire:model="icon"
                                       class="form-control @error('icon') is-invalid @enderror"
                                       placeholder="bi bi-puzzle-fill">
                                <x-slot name="helperSlot">
                                    <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Voir les icônes
                                    </a>
                                </x-slot>
                            </x-form.input-group>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <x-form.input-group
                                label="Description"
                                icon="text-paragraph"
                                :useSlot="true"
                                :error="$errors->first('description')">
                                <textarea wire:model="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="3" placeholder="Description du module..."></textarea>
                            </x-form.input-group>
                        </div>

                        <div class="col-md-3">
                            <x-form.input-group
                                label="Ordre"
                                icon="arrow-down-up"
                                :useSlot="true"
                                :error="$errors->first('sort_order')">
                                <input type="number" wire:model="sort_order"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       placeholder="1">
                            </x-form.input-group>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" wire:model="is_active" class="form-check-input" id="is_active" role="switch">
                            <label for="is_active" class="form-check-label fw-semibold">
                                <i class="bi bi-toggle-on me-1"></i>Module actif
                            </label>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Fonctionnalités --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="fw-bold mb-0">
                                <i class="bi bi-list-stars me-2"></i>Fonctionnalités du module
                            </label>
                            <button type="button" class="btn btn-success btn-sm" wire:click="addFeature">
                                <i class="bi bi-plus-lg me-1"></i>Ajouter
                            </button>
                        </div>

                        @foreach ($features as $index => $feature)
                            <div class="card mb-3 border">
                                <div class="card-body p-3">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-4">
                                            <input type="text"
                                                   wire:model="features.{{ $index }}.name"
                                                   class="form-control form-control-sm @error('features.'.$index.'.name') is-invalid @enderror"
                                                   placeholder="Nom de la fonctionnalité">
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text"
                                                   wire:model="features.{{ $index }}.url"
                                                   class="form-control form-control-sm @error('features.'.$index.'.url') is-invalid @enderror"
                                                   placeholder="Route (ex: payment.list)">
                                        </div>

                                        <div class="col-md-3">
                                            <input type="text"
                                                   wire:model="features.{{ $index }}.icon"
                                                   class="form-control form-control-sm"
                                                   placeholder="bi bi-icon">
                                        </div>

                                        <div class="col-md-1 text-end">
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    wire:click="removeFeature({{ $index }})"
                                                    title="Supprimer">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if (empty($features))
                            <div class="text-center py-4 text-muted border rounded">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                <p class="mb-0">Aucune fonctionnalité ajoutée</p>
                                <small>Cliquez sur "Ajouter" pour créer une fonctionnalité</small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-{{ $isEditing ? 'check-lg' : 'plus-lg' }} me-1"></i>
                        {{ $isEditing ? 'Mettre à jour' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@script
<script>
    const moduleModal = new bootstrap.Modal(document.getElementById('moduleFormModal'));

    // Écouter l'événement Livewire pour ouvrir le modal
    $wire.on('open-module-modal', () => {
        moduleModal.show();
    });

    // Écouter l'événement Livewire pour fermer le modal
    $wire.on('close-module-modal', () => {
        moduleModal.hide();
    });
</script>
@endscript
