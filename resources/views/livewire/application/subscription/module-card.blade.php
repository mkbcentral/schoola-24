<div class="card h-100 border-0 shadow-sm">
    <div class="card-header bg-{{ $subscription->module->color ?? 'primary' }} bg-gradient text-white border-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="{{ $subscription->module->icon ?? 'fas fa-puzzle-piece' }}"></i>
                {{ $subscription->module->name }}
            </h5>
            <div>
                {!! $subscription->getStatusBadge() !!}
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Informations principales -->
        <p class="text-muted mb-3">{{ $subscription->module->description }}</p>

        <!-- Informations clés -->
        <div class="mb-3">
            <div class="d-flex align-items-start mb-2">
                <div class="text-{{ $subscription->module->color ?? 'primary' }} me-2">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <div class="flex-grow-1">
                    <small class="text-muted d-block">Expiration</small>
                    <span class="{{ $this->isExpiringSoon ? 'text-warning fw-bold' : 'fw-semibold' }}">
                        {{ $subscription->getFormattedExpirationDate() }}
                        @if ($this->isExpiringSoon)
                            <i class="fas fa-exclamation-triangle text-warning ms-1"></i>
                        @endif
                    </span>
                </div>
            </div>

            @if ($subscription->plan)
                <div class="d-flex align-items-start">
                    <div class="text-{{ $subscription->module->color ?? 'primary' }} me-2">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small class="text-muted d-block">Plan</small>
                        <span class="fw-semibold">{{ $subscription->plan->name }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Navigation Links -->
        @if (count($this->navigationLinks) > 0)
            <div class="mb-3">
                <strong><i class="fas fa-link"></i> Accès rapides :</strong>
                <div class="mt-2">
                    @foreach ($this->navigationLinks as $link)
                        @if (isset($link['route']) && Route::has($link['route']))
                            <a href="{{ route($link['route']) }}"
                               class="btn btn-sm btn-outline-secondary btn-block text-left mb-1">
                                <i class="{{ $link['icon'] ?? 'fas fa-arrow-right' }}"></i>
                                {{ $link['name'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Bouton principal -->
        <button wire:click="navigateToModule" class="btn btn-{{ $subscription->module->color ?? 'primary' }} btn-block">
            <i class="fas fa-arrow-right"></i> Accéder au module
        </button>

        <!-- Bouton détails -->
        <button wire:click="toggleDetails" class="btn btn-sm btn-link btn-block">
            <i class="fas fa-{{ $showDetails ? 'chevron-up' : 'chevron-down' }}"></i>
            {{ $showDetails ? 'Masquer' : 'Voir' }} les détails
        </button>

        <!-- Détails supplémentaires -->
        @if ($showDetails)
            <div class="border-top pt-3 mt-2">
                <small class="text-muted">
                    <div class="row">
                        <div class="col-6">
                            <strong>Démarré le :</strong><br>
                            {{ $subscription->started_at?->format('d/m/Y') ?? 'N/A' }}
                        </div>
                        @if ($subscription->is_trial)
                            <div class="col-6">
                                <strong>Fin essai :</strong><br>
                                {{ $subscription->trial_ends_at?->format('d/m/Y') ?? 'N/A' }}
                            </div>
                        @endif
                    </div>
                    @if ($subscription->auto_renew)
                        <div class="mt-2">
                            <i class="fas fa-sync-alt text-success"></i>
                            <span class="text-success">Renouvellement automatique activé</span>
                        </div>
                    @endif
                </small>
            </div>
        @endif
    </div>
</div>
