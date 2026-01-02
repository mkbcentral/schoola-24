<div>
    @if($registrationId && !empty($studentInfo))
        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-info text-white border-0">
                <h5 class="mb-0">
                    <i class="bi bi-{{ $isEditing ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                    {{ $isEditing ? 'Modifier le paiement' : 'Nouveau paiement' }}
                </h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <!-- Catégorie de frais -->
                    <div class="mb-3">
                        <label for="category_fee_id" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i>Catégorie de frais *
                        </label>
                        <select 
                            id="category_fee_id"
                            wire:model.live="categoryFeeId"
                            class="form-select @error('categoryFeeId') is-invalid @enderror">
                            <option value="">-- Sélectionner une catégorie --</option>
                            @foreach($this->categoryFees as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('categoryFeeId')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Affichage du montant et devise après sélection -->
                    @if($selectedFeeInfo)
                        <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-2 fs-5"></i>
                                <div>
                                    <strong>{{ $selectedFeeInfo['category_name'] }}</strong>
                                    <div class="mt-1">
                                        <span class="badge bg-primary fs-6">
                                            {{ number_format($selectedFeeInfo['amount'], 0, ',', ' ') }} 
                                            {{ $selectedFeeInfo['currency'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Mois -->
                    <div class="mb-3">
                        <label for="month" class="form-label fw-semibold">
                            <i class="bi bi-calendar-month me-1"></i>Mois *
                        </label>
                        <select 
                            id="month"
                            wire:model="month"
                            class="form-select @error('month') is-invalid @enderror">
                            <option value="">-- Sélectionner un mois --</option>
                            @foreach($this->months as $monthOption)
                                <option value="{{ $monthOption['value'] }}">{{ $monthOption['label'] }}</option>
                            @endforeach
                        </select>
                        @error('month')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date de paiement -->
                    <div class="mb-3">
                        <label for="payment_date" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date de paiement *
                        </label>
                        <input 
                            type="date" 
                            id="payment_date"
                            wire:model="createdAt"
                            class="form-control @error('createdAt') is-invalid @enderror">
                        @error('createdAt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>Par défaut : date du jour
                        </small>
                    </div>

                    <!-- Toggle de validation -->
                    <div class="mb-4">
                        <div class="card bg-light border-0">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <label class="form-label mb-0 fw-semibold">
                                            <i class="bi bi-check-circle me-1"></i>Valider le paiement
                                        </label>
                                        <small class="text-muted d-block">
                                            Le paiement sera marqué comme payé
                                        </small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            role="switch"
                                            id="is_paid"
                                            wire:model="isPaid"
                                            style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex gap-2">
                        <button 
                            type="submit" 
                            class="btn btn-primary flex-grow-1"
                            wire:loading.attr="disabled">
                            <i class="bi bi-save me-1"></i>
                            <span wire:loading.remove wire:target="save">
                                {{ $isEditing ? 'Modifier' : 'Enregistrer' }}
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Traitement...
                            </span>
                        </button>
                        @if($isEditing)
                            <button 
                                type="button" 
                                wire:click="cancel"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Annuler
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3 mb-0">
                    Veuillez sélectionner un élève pour créer un paiement
                </p>
            </div>
        </div>
    @endif
</div>
