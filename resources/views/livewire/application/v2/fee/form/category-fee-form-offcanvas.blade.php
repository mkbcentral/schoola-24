<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCategoryFee" data-bs-backdrop="static"
        data-bs-keyboard="false" wire:ignore.self>
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title">
                <i class="bi bi-folder-plus me-2"></i>
                {{ $isEditing ? 'Modifier la catégorie de frais' : 'Nouvelle catégorie de frais' }}
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
                    <x-form.input type="text" id="name" wire:model="name" placeholder="Ex: Frais scolaire"
                        error="name" />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="schoolYearId" class="form-label">
                        <i class="bi bi-calendar me-1"></i>Année scolaire <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('schoolYearId') is-invalid @enderror" id="schoolYearId"
                        wire:model="schoolYearId">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}">{{ $schoolYear->name }}</option>
                        @endforeach
                    </select>
                    @error('schoolYearId')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="currency" class="form-label">
                        <i class="bi bi-currency-dollar me-1"></i>Devise <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('currency') is-invalid @enderror" id="currency"
                        wire:model="currency">
                        <option value="USD">USD ($)</option>
                        <option value="CDF">CDF (FC)</option>
                    </select>
                    @error('currency')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-gear me-1"></i>Options
                    </label>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="isStateFee" wire:model="isStateFee">
                        <label class="form-check-label" for="isStateFee">
                            Frais d'état
                        </label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="isPaidInInstallment"
                            wire:model="isPaidInInstallment">
                        <label class="form-check-label" for="isPaidInInstallment">
                            Paiement échelonné
                        </label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="isPaidForRegistration"
                            wire:model="isPaidForRegistration">
                        <label class="form-check-label" for="isPaidForRegistration">
                            Payé à l'inscription
                        </label>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="isForDash" wire:model="isForDash">
                        <label class="form-check-label" for="isForDash">
                            Afficher au tableau de bord
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="isAccessory" wire:model="isAccessory">
                        <label class="form-check-label" for="isAccessory">
                            Frais accessoire
                        </label>
                    </div>
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
        $wire.on('open-offcanvas-category-fee', () => {
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasCategoryFee'));
            offcanvas.show();
        });

        $wire.on('close-offcanvas-category-fee', () => {
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasCategoryFee'));
            if (offcanvas) {
                offcanvas.hide();
            }
        });
    </script>
@endscript
