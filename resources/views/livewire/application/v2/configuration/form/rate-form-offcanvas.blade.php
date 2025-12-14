<div>
    {{-- Offcanvas Bootstrap pour formulaire de taux --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="rateFormOffcanvas" aria-labelledby="rateFormOffcanvasLabel"
        data-bs-backdrop="true" data-bs-scroll="false" data-bs-keyboard="true" wire:ignore.self style="width: 500px">

        <div class="offcanvas-header text-white">
            <h5 class="offcanvas-title" id="rateFormOffcanvasLabel">
                <span wire:loading.remove wire:target="openModal,openEditModal">
                    <i class="bi bi-{{ $rate ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                    {{ $rate ? 'Modifier' : 'Nouveau' }} Taux de Change
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
                        <!-- Montant du taux -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-currency-exchange me-1"></i>
                                Montant (1 USD = X CDF)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-currency-dollar"></i>
                                </span>
                                <input type="number" step="0.01"
                                    class="form-control @error('form.amount') is-invalid @enderror" wire:model.blur="form.amount"
                                    placeholder="Ex: 2500">
                                <span class="input-group-text">CDF</span>
                            </div>
                            @error('form.amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Entrez le montant en Francs Congolais (CDF)
                        </div>
                    </div>

                    <!-- Aperçu du taux -->
                    @if ($form->amount > 0)
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="bi bi-calculator me-2"></i>
                                    Aperçu du taux
                                </h6>
                                <hr>
                                <p class="mb-0">
                                    <strong>1 USD</strong> = <strong
                                        class="text-primary">{{ app_format_number($form->amount, 0) }} CDF</strong>
                                </p>
                                <p class="mb-0 mt-2">
                                    <strong>10 USD</strong> = <strong
                                        class="text-primary">{{ app_format_number($form->amount * 10, 0) }} CDF</strong>
                                </p>
                                <p class="mb-0 mt-2">
                                    <strong>100 USD</strong> = <strong
                                        class="text-primary">{{ app_format_number($form->amount * 100, 0) }} CDF</strong>
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Statut du taux -->
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="isChangedSwitch"
                                        wire:model.live="form.is_changed">
                                    <label class="form-check-label fw-bold" for="isChangedSwitch">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Marquer comme taux modifié
                                    </label>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Si décoché, ce taux sera défini comme taux par défaut
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Informations supplémentaires -->
                    @if ($rate)
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Attention:</strong> La modification d'un taux peut affecter les calculs
                                existants
                            </div>
                        </div>
                    @endif
            </div>

            <!-- Boutons d'action -->
            <div class="d-grid gap-2 mt-4">
                <x-form.button type="submit" class="btn-{{ $rate ? 'warning' : 'success' }} btn-lg"
                    wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        <i class="bi bi-{{ $rate ? 'check-circle' : 'save' }} me-2"></i>
                        {{ $rate ? 'Mettre à jour' : 'Enregistrer' }}
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
