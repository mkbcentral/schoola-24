<div>
    {{-- Offcanvas Bootstrap pour formulaire de section --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sectionFormOffcanvas"
        aria-labelledby="sectionFormOffcanvasLabel" data-bs-backdrop="true" data-bs-scroll="false" data-bs-keyboard="true"
        wire:ignore.self style="width: 500px">

        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="sectionFormOffcanvasLabel">
                <i class="bi bi-{{ $section ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                {{ $section ? 'Modifier' : 'Nouvelle' }} Section
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <!-- Nom de la section -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">
                            <i class="bi bi-collection me-1"></i>
                            Nom de la section
                        </label>
                        <x-form.input type="text" wire:model.blur="form.name" error="form.name" icon="bi bi-collection"
                            placeholder="Ex: Maternelle, Primaire" />
                        @error('form.name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Informations supplémentaires -->
                    @if ($section)
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Mode édition:</strong> Vous modifiez une section existante
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Boutons d'action -->
                <div class="d-grid gap-2 mt-4">
                    <x-form.button type="submit" class="btn-{{ $section ? 'warning' : 'success' }} btn-lg"
                        wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-{{ $section ? 'check-circle' : 'save' }} me-2"></i>
                            {{ $section ? 'Mettre à jour' : 'Enregistrer' }}
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
    </div>
</div>
