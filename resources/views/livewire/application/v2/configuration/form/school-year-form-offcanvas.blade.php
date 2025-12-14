<div>
    {{-- Offcanvas Bootstrap pour formulaire d'année scolaire --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="schoolYearFormOffcanvas"
        aria-labelledby="schoolYearFormOffcanvasLabel" data-bs-backdrop="true" data-bs-scroll="false"
        data-bs-keyboard="true" wire:ignore.self style="width: 500px">

        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="schoolYearFormOffcanvasLabel">
                <span wire:loading.remove wire:target="openModal,openEditModal">
                    <i class="bi bi-{{ $schoolYear ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                    {{ $schoolYear ? 'Modifier' : 'Nouvelle' }} Année Scolaire
                </span>
                <span wire:loading wire:target="openModal,openEditModal">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Chargement...
                </span>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <div wire:loading.class="opacity-50" wire:target="openEditModal">
                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <!-- Nom de l'année scolaire -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar-event me-1"></i>
                                Nom de l'année scolaire
                            </label>
                            <x-form.input type="text" wire:model.blur="form.name" error="form.name"
                                icon="bi bi-calendar-event" placeholder="Ex: 2023-2024" />
                            @error('form.name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Format recommandé: YYYY-YYYY
                            </div>
                        </div>

                        <!-- Statut actif -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="isActiveSwitch" wire:model.live="form.is_active">
                                        <label class="form-check-label fw-bold" for="isActiveSwitch">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Définir comme année active
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Si activé, cette année deviendra l'année scolaire par défaut
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        @if ($schoolYear)
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Mode édition:</strong> Vous modifiez une année scolaire existante
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-grid gap-2 mt-4">
                        <x-form.button type="submit" class="btn-{{ $schoolYear ? 'warning' : 'success' }} btn-lg"
                            wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">
                                <i class="bi bi-{{ $schoolYear ? 'check-circle' : 'save' }} me-2"></i>
                                {{ $schoolYear ? 'Mettre à jour' : 'Enregistrer' }}
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Enregistrement...
                            </span>
                        </x-form.button>
                        <x-form.button type="button" class="btn-secondary" data-bs-dismiss="offcanvas">
                            <i class="bi bi-x-circle me-2"></i>
                            Annuler
                        </x-form.button>
                    </div>
                </form>
            </div>

            <!-- Loading overlay -->
            <div wire:loading wire:target="openEditModal" class="position-absolute top-50 start-50 translate-middle">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
        </div>
    </div>
</div>
