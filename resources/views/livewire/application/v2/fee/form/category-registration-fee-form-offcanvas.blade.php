<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCategoryRegistrationFee" data-bs-backdrop="static"
        data-bs-keyboard="false" wire:ignore.self>
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title">
                <i class="bi bi-folder-plus me-2"></i>
                {{ $isEditing ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                wire:click="resetForm"></button>
        </div>
        <div class="offcanvas-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="bi bi-tag me-1"></i>Nom de la catégorie <span class="text-danger">*</span>
                    </label>
                    <x-form.input type="text" id="name" wire:model.defer="name"
                        placeholder="Ex: Frais d'inscription" error="name" />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="isOld" wire:model="isOld">
                        <label class="form-check-label" for="isOld">
                            <i class="bi bi-clock-history me-1"></i>
                            Ancien élève
                        </label>
                    </div>
                    <small class="text-muted">Cochez si cette catégorie concerne les anciens élèves</small>
                </div>

                <div class="d-grid gap-2">
                    <x-form.button type="submit" class="btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="bi bi-check-circle me-1"></i>
                            {{ $isEditing ? 'Mettre à jour' : 'Enregistrer' }}
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Enregistrement...
                        </span>
                    </x-form.button>
                    <x-form.button type="button" class="btn-secondary" data-bs-dismiss="offcanvas"
                        wire:click="resetForm">
                        <i class="bi bi-x-circle me-1"></i>Annuler
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('open-offcanvas-category-registration-fee', () => {
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasCategoryRegistrationFee'));
            offcanvas.show();
        });

        $wire.on('close-offcanvas-category-registration-fee', () => {
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById(
                'offcanvasCategoryRegistrationFee'));
            if (offcanvas) {
                offcanvas.hide();
            }
        });
    </script>
@endscript
