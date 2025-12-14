<div>
    <!-- Formulaire de paiement -->
    <div wire:ignore.self>
        @if ($isEditMode)
            <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    <strong>Mode édition</strong> - Vous modifiez un paiement existant
                </div>
            </div>
        @endif

        <div>
            @if ($registration)
                <form wire:submit.prevent="save">
                    <!-- Catégorie de frais -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-muted mb-2">
                            <i class="bi bi-tags-fill me-1"></i>Catégorie de frais
                            <span class="text-danger">*</span>
                        </label>
                        <select wire:model.live="selectedCategoryFeeId" class="form-select form-select-lg"
                            style="border-radius: 8px; border: 1.5px solid var(--bs-border-color); font-size: 0.95rem;">
                            <option value="">Sélectionner une catégorie</option>
                            @foreach ($categoryFees as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }} ({{ $category->currency }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Montant (affiché si frais trouvé) -->
                    @if ($scolarFee)
                        <div class="mb-3">
                            <label class="form-label fw-medium small text-muted mb-2">
                                <i class="bi bi-currency-dollar me-1"></i>Montant
                            </label>
                            <div class="p-3 rounded d-flex justify-content-between align-items-center"
                                style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.2);">
                                <span class="fw-bold" style="font-size: 1.5rem; color: #10b981;">
                                    {{ number_format($scolarFee->amount, 0, ',', ' ') }}
                                </span>
                                <span class="badge bg-success px-3 py-2" style="font-size: 0.9rem;">
                                    {{ $scolarFee->categoryFee->currency }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Mois et Date côte à côte -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium small text-muted mb-2">
                                <i class="bi bi-calendar-month me-1"></i>Mois
                                <span class="text-danger">*</span>
                            </label>
                            <select wire:model="form.month" class="form-select"
                                style="border-radius: 8px; border: 1.5px solid var(--bs-border-color); font-size: 0.9rem;">
                                <option value="01">Janvier</option>
                                <option value="02">Février</option>
                                <option value="03">Mars</option>
                                <option value="04">Avril</option>
                                <option value="05">Mai</option>
                                <option value="06">Juin</option>
                                <option value="07">Juillet</option>
                                <option value="08">Août</option>
                                <option value="09">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-medium small text-muted mb-2">
                                <i class="bi bi-calendar-event me-1"></i>Date
                                <span class="text-danger">*</span>
                            </label>
                            <input type="date" wire:model="form.created_at" class="form-control"
                                style="border-radius: 8px; border: 1.5px solid var(--bs-border-color); font-size: 0.9rem;">
                        </div>
                    </div>

                    <!-- Payer immédiatement -->
                    <div class="mb-4">
                        <div class="p-3 rounded"
                            style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%); border: 1px solid rgba(99, 102, 241, 0.15);">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">

                                    <div>
                                        <label class="mb-0 fw-medium" for="isPaidSwitch" style="cursor: pointer;">
                                            Payer immédiatement
                                        </label>
                                        <div class="small text-muted">
                                            Le paiement sera validé automatiquement
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="isPaid"
                                        id="isPaidSwitch" style="cursor: pointer; width: 3em; height: 1.5em;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex gap-2">
                        @if ($isEditMode)
                            <button type="button" wire:click="cancel" class="btn btn-outline-secondary flex-fill"
                                style="border-radius: 8px; border-width: 1.5px; font-weight: 500;">
                                <i class="bi bi-x-circle me-2"></i>Annuler
                            </button>
                            <button type="submit" class="btn btn-warning flex-fill"
                                style="border-radius: 8px; font-weight: 600;">
                                <i class="bi bi-check-circle-fill me-2"></i>Mettre à jour
                            </button>
                        @else
                            <button type="submit" class="btn btn-success w-100"
                                style="border-radius: 8px; font-weight: 600; padding: 0.75rem;">
                                <i class="bi bi-check-circle-fill me-2"></i>Enregistrer le paiement
                            </button>
                        @endif
                    </div>
                </form>
            @else
                <!-- Message si aucun élève sélectionné -->
                <div class="text-center py-5">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px; background: var(--bs-secondary-bg);">
                        <i class="bi bi-person-plus-fill text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="fw-semibold mb-2">Aucun élève sélectionné</h6>
                    <p class="text-muted small mb-0">Recherchez et sélectionnez un élève pour commencer</p>
                </div>
            @endif
        </div>
    </div>

</div>
