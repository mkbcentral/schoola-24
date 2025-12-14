<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRole" data-bs-backdrop="static" data-bs-keyboard="false"
        wire:ignore.self>
        <div class="offcanvas-header bg-warning text-dark">
            <h5 class="offcanvas-title">
                <i class="bi bi-shield-plus me-2"></i>
                {{ $role ? 'Modifier le rôle' : 'Nouveau rôle' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" wire:click="closeOffcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <x-form.label for="name">
                        <i class="bi bi-tag me-1"></i>Nom du rôle <span class="text-danger">*</span>
                    </x-form.label>
                    <x-form.input type="text" id="name" wire:model="form.name" error="form.name"
                        icon="bi bi-tag" placeholder="Ex: Administrateur" />
                    @error('form.name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <x-form.label>
                        <i class="bi bi-gear me-1"></i>Type de rôle <span class="text-danger">*</span>
                    </x-form.label>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="is_for_school" id="is_for_school_yes"
                            wire:model="form.is_for_school" value="1">
                        <label class="form-check-label" for="is_for_school_yes">
                            <i class="bi bi-building me-1 text-success"></i>
                            <strong>Pour l'école</strong>
                            <br>
                            <small class="text-muted">Ce rôle sera associé à une école spécifique</small>
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_for_school" id="is_for_school_no"
                            wire:model="form.is_for_school" value="0">
                        <label class="form-check-label" for="is_for_school_no">
                            <i class="bi bi-globe me-1 text-primary"></i>
                            <strong>Pour l'application</strong>
                            <br>
                            <small class="text-muted">Ce rôle sera accessible à toutes les écoles</small>
                        </label>
                    </div>

                    @error('form.is_for_school')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>
                        <strong>Note :</strong> Les rôles pour l'école sont liés à une école spécifique,
                        tandis que les rôles pour l'application sont globaux à toute la plateforme.
                    </small>
                </div>

                <div class="d-grid gap-2">
                    <x-form.button type="submit" class="btn-warning" wire:loading.attr="disabled"
                        wire:loading.class="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-save me-1"></i>
                            {{ $role ? 'Mettre à jour' : 'Enregistrer' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-1" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </span>
                            Enregistrement...
                        </span>
                    </x-form.button>
                    <x-form.button type="button" class="btn-outline-secondary" data-bs-dismiss="offcanvas"
                        wire:click="closeOffcanvas" wire:loading.attr="disabled">
                        <i class="bi bi-x-circle me-1"></i>
                        Annuler
                    </x-form.button>
                </div>
            </form>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('show-role-offcanvas', () => {
        const offcanvasElement = document.getElementById('offcanvasRole');
        const offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    });

    $wire.on('hide-role-offcanvas', () => {
        const offcanvasElement = document.getElementById('offcanvasRole');
        const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
        if (offcanvas) {
            offcanvas.hide();
        }
    });
</script>
@endscript
