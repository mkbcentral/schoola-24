<div>
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="{{ $module->icon }}"></i>
            Souscrire à {{ $module->name }}
        </h5>
        <button type="button" class="close" wire:click="$dispatch('close-modal')">
            <span>&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-info border-0">
            <i class="{{ $module->icon }} me-2"></i>
            {{ $module->description }}
        </div>

        <form wire:submit.prevent="subscribe">
            <!-- Sélection du plan -->
            <div class="form-group">
                <label class="fw-semibold mb-3">Choisissez un plan :</label>
                <div class="row">
                    @foreach ($plans as $plan)
                        <div class="col-md-6 mb-3">
                            <input type="radio"
                                   id="plan-{{ $plan->id }}"
                                   name="plan"
                                   value="{{ $plan->id }}"
                                   wire:model.live="selectedPlanId"
                                   class="btn-check">
                            <label class="btn btn-outline-primary w-100 h-100 text-start p-3" for="plan-{{ $plan->id }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong class="d-block">{{ $plan->name }}</strong>
                                    @if ($plan->discount_percentage > 0)
                                        <span class="badge bg-success">
                                            -{{ $plan->discount_percentage }}%
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted d-block mb-2">{{ $plan->getPeriodDescription() }}</small>
                                <p class="small mb-2">{{ $plan->description }}</p>
                                <div class="text-success fw-bold fs-5">
                                    {{ $plan->getFormattedFinalPrice() }}
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            @if (!empty($pricingDetails))
                <!-- Détails de tarification -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-3 fw-semibold">
                            <i class="fas fa-calculator text-primary me-2"></i>
                            Résumé
                        </h5>

                        @if (isset($pricingDetails['savings']['savings_amount']) && $pricingDetails['savings']['savings_amount'] > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Prix régulier :</span>
                                <span class="text-muted">
                                    <del>{{ number_format($pricingDetails['savings']['regular_price'], 0, ',', ' ') }} FC</del>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <span>Économies :</span>
                                <span class="text-success fw-semibold">
                                    -{{ number_format($pricingDetails['savings']['savings_amount'], 0, ',', ' ') }} FC
                                    <small>({{ $pricingDetails['savings']['savings_percentage'] }}%)</small>
                                </span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Total :</strong>
                            <strong class="text-success h4 mb-0">
                                {{ $pricingDetails['formatted_price'] }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between text-muted small">
                            <span>Par mois :</span>
                            <span>{{ $pricingDetails['formatted_monthly_price'] }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if ($module->trial_days > 0)
                <!-- Option essai gratuit -->
                <div class="custom-control custom-checkbox mt-3">
                    <input talert alert-info border-0 mt-3">
                    <div class="form-check">
                        <input type="checkbox"
                               class="form-check-input"
                               id="trial-checkbox"
                               wire:model="isTrial">
                        <label class="form-check-label" for="trial-checkbox">
                            <i class="fas fa-flask me-2"></i>
                            <strong>Commencer par un essai gratuit de {{ $module->trial_days }} jours</strong>
                        </label>
                    </div
            @endif

            @error('selectedPlanId')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$dispatch('close-modal')">
            Annuler
        </button>
        <button type="button" wire:click="subscribe" class="btn btn-primary">
            <i class="fas fa-check"></i>
            @if ($isTrial)
                Démarrer l'essai gratuit
            @else
                Confirmer la souscription
            @endif
        </button>
    </div>
</div>
