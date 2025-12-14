<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRegistrationFee" data-bs-backdrop="static"
        data-bs-keyboard="false" wire:ignore.self>
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title">
                <i class="bi bi-person-check me-2"></i>
                {{ $isEditing ? "Modifier le frais d'inscription" : "Nouveau frais d'inscription" }}
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                wire:click="resetForm"></button>
        </div>
        <div class="offcanvas-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="bi bi-tag me-1"></i>Nom du frais <span class="text-danger">*</span>
                    </label>
                    <x-form.input type="text" id="name" wire:model="name"
                        placeholder="Ex: Frais inscription complète" error="name" />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">
                        <i class="bi bi-cash me-1"></i>Montant <span class="text-danger">*</span>
                    </label>
                    <x-form.input type="number" id="amount" wire:model="amount" step="0.01" placeholder="0.00"
                        error="amount" />
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="optionId" class="form-label">
                        <i class="bi bi-list-ul me-1"></i>Option <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('optionId') is-invalid @enderror" id="optionId"
                        wire:model="optionId">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($options as $option)
                            <option value="{{ $option->id }}">
                                {{ $option->section->name ?? '' }} - {{ $option->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('optionId')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="categoryRegistrationFeeId" class="form-label">
                        <i class="bi bi-folder me-1"></i>Catégorie d'inscription <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('categoryRegistrationFeeId') is-invalid @enderror"
                        id="categoryRegistrationFeeId" wire:model="categoryRegistrationFeeId">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($categoryRegistrationFees as $categoryRegistrationFee)
                            <option value="{{ $categoryRegistrationFee->id }}">
                                {{ $categoryRegistrationFee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoryRegistrationFeeId')
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
        $wire.on('open-offcanvas-registration-fee', () => {
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasRegistrationFee'));
            offcanvas.show();
        });

        $wire.on('close-offcanvas-registration-fee', () => {
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasRegistrationFee'));
            if (offcanvas) {
                offcanvas.hide();
            }
        });
    </script>
@endscript
