<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasScolarFee" data-bs-backdrop="static"
        data-bs-keyboard="false" wire:ignore.self>
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title">
                <i class="bi bi-currency-exchange me-2"></i>
                {{ $isEditing ? 'Modifier le frais scolaire' : 'Nouveau frais scolaire' }}
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
                        placeholder="Ex: Frais de 1ère période" error="name" />
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
                    <label for="categoryFeeId" class="form-label">
                        <i class="bi bi-folder me-1"></i>Catégorie de frais <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('categoryFeeId') is-invalid @enderror" id="categoryFeeId"
                        wire:model="categoryFeeId">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($categoryFees as $categoryFee)
                            <option value="{{ $categoryFee->id }}">
                                {{ $categoryFee->name }} ({{ $categoryFee->currency }})
                            </option>
                        @endforeach
                    </select>
                    @error('categoryFeeId')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="optionId" class="form-label">
                        <i class="bi bi-list-ul me-1"></i>Option <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('optionId') is-invalid @enderror" id="optionId"
                        wire:model.live="optionId">
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

                @if (!$isEditing && $optionId)
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="applyToAllClasses"
                                wire:model.live="applyToAllClasses">
                            <label class="form-check-label" for="applyToAllClasses">
                                <i class="bi bi-stack me-1"></i>
                                Appliquer à toutes les classes de cette option
                            </label>
                        </div>
                        <small class="text-muted">
                            @if ($applyToAllClasses)
                                <i class="bi bi-info-circle me-1"></i>
                                Le frais sera créé pour toutes les {{ $classRooms->count() }} classe(s) de l'option
                                sélectionnée
                            @else
                                Cochez pour créer ce frais automatiquement dans toutes les classes
                            @endif
                        </small>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="classRoomId" class="form-label">
                        <i class="bi bi-door-open me-1"></i>Classe
                        @if (!$applyToAllClasses)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <select class="form-select @error('classRoomId') is-invalid @enderror" id="classRoomId"
                        wire:model="classRoomId" {{ !$optionId || $applyToAllClasses ? 'disabled' : '' }}>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}">
                                {{ $classRoom->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('classRoomId')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if (!$optionId)
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Veuillez d'abord sélectionner une option
                        </div>
                    @elseif ($applyToAllClasses)
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Le frais sera appliqué à toutes les classes
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="isChanged" wire:model="isChanged">
                        <label class="form-check-label" for="isChanged">
                            Frais modifié
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
        $wire.on('open-offcanvas-scolar-fee', () => {
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasScolarFee'));
            offcanvas.show();
        });

        $wire.on('close-offcanvas-scolar-fee', () => {
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasScolarFee'));
            if (offcanvas) {
                offcanvas.hide();
            }
        });
    </script>
@endscript
