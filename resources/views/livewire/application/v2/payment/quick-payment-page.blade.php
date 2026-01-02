{{-- Styles Quick Payment intégrés dans app.scss via pages/_quick-payment.scss --}}
<div>
    <x-navigation.bread-crumb icon='bi bi-cash-coin' label="Paiement Rapide">
        <x-navigation.bread-crumb-item label='Paiements' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>

        <div x-data="{
            showDropdown: @entangle('showDropdown'),
            closeDropdown() {
                showDropdown = false;
                $wire.closeDropdown();
            }
        }" @click.outside="closeDropdown()">

            <div class="row g-4">
                <!-- Colonne gauche : Zone de saisie (Recherche + Infos + Formulaire) -->
                <div class="col-lg-5 col-xl-4">
                    <div class="sticky-top" style="top: 1rem;">
                        <div class="card shadow-sm"
                            style="position: relative; z-index: var(--z-dropdown); overflow: visible;">
                            <div class="card-body p-4" style="overflow: visible;">
                                <!-- Section 1 : Recherche d'élève -->
                                <div class="mb-4">
                                    <h6 class="mb-3 fw-semibold text-primary">
                                        <i class="bi bi-search me-2"></i>Étape 1 : Rechercher l'élève
                                    </h6>
                                    <div class="position-relative">
                                        <label class="form-label small text-muted">
                                            Nom de l'élève
                                        </label>
                                        <div class="position-relative">
                                            <input type="text" wire:model.live.debounce.300ms="search"
                                                class="form-control form-control-lg qp-search-input"
                                                placeholder="Tapez le nom de l'élève..." @focus="showDropdown = true"
                                                autocomplete="off" style="padding-right: 45px;">

                                            <!-- Indicateur de chargement -->
                                            <div wire:loading wire:target="search"
                                                class="position-absolute top-50 end-0 translate-middle-y"
                                                style="z-index: 10; padding-right: 0.75rem;">
                                                <div class="spinner-border spinner-border-sm text-primary"
                                                    role="status" style="width: 1.25rem; height: 1.25rem;">
                                                    <span class="visually-hidden">Recherche en cours...</span>
                                                </div>
                                            </div>

                                            @if (strlen($search) > 0)
                                                <button type="button" wire:click="$set('search', '')"
                                                    wire:loading.remove wire:target="search"
                                                    class="btn btn-link position-absolute top-50 end-0 translate-middle-y"
                                                    aria-label="Vider la recherche"
                                                    style="z-index: 10; padding: 0.5rem 0.75rem; text-decoration: none;"
                                                    title="Vider la recherche">
                                                    <i class="bi bi-x-circle-fill text-muted" aria-hidden="true"
                                                        style="font-size: 1.25rem;"></i>
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Dropdown autocomplete avec z-index élevé -->
                                        @if (count($searchResults) > 0)
                                            <div x-show="showDropdown"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 -translate-y-1"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 -translate-y-1"
                                                class="position-absolute w-100 qp-dropdown qp-scrollable"
                                                style="z-index: var(--z-top); max-height: 400px; overflow-y: auto; top: 100%; margin-top: 0.5rem;">
                                                @foreach ($searchResults as $result)
                                                    <div wire:click="selectStudent({{ $result['id'] }}, '{{ addslashes($result['student_name']) }}')"
                                                        class="qp-dropdown-item">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="student-name">
                                                                    <i class="bi bi-person-circle me-2"
                                                                        style="color: #059669;"></i>
                                                                    {{ $result['student_name'] }}
                                                                </div>
                                                                <div class="student-info">
                                                                    <span
                                                                        class="badge bg-primary me-2">{{ $result['code'] }}</span>
                                                                    {{ $result['class_room'] }} -
                                                                    {{ $result['option'] }}
                                                                </div>
                                                            </div>
                                                            <i class="bi bi-arrow-right-circle"
                                                                style="color: #059669; font-size: 1.2rem;"></i>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (strlen($search) >= 2 && count($searchResults) === 0)
                                            <div x-show="showDropdown"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 -translate-y-1"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 -translate-y-1"
                                                class="position-absolute w-100 qp-dropdown qp-dropdown-empty"
                                                style="z-index: var(--z-top); top: 100%; margin-top: 0.5rem;">
                                                <div class="text-center py-4">
                                                    <i class="bi bi-inbox text-muted"
                                                        style="font-size: 2.5rem; opacity: 0.5;"></i>
                                                    <p class="mb-0 mt-3 text-muted fw-medium">Aucun élève trouvé</p>
                                                    <small class="text-muted">Essayez un autre nom</small>
                                                </div>
                                            </div>
                                        @endif

                                        <small class="text-muted d-block mt-2">
                                            <i class="bi bi-info-circle me-1"></i>Tapez au moins 2 caractères pour
                                            rechercher
                                        </small>
                                    </div>

                                    <!-- Indicateur de chargement -->
                                    <div wire:loading wire:target="updatedSearch" class="mt-3">
                                        <div class="alert alert-info d-flex align-items-center mb-0" role="alert"
                                            style="border-radius: 8px; border: none; background-color: #e0f2fe; color: #0369a1;">
                                            <div class="spinner-border spinner-border-sm me-3" role="status">
                                                <span class="visually-hidden">Chargement...</span>
                                            </div>
                                            <div>
                                                <strong>Recherche en cours...</strong>
                                                <div class="small">Veuillez patienter</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2 : Informations de l'élève sélectionné -->
                                @if (!empty($studentInfo))
                                    <div class="mb-4 p-3 rounded"
                                        style="background: linear-gradient(135deg, #5a8dee 0%, #764ba2 100%);">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center gap-2 grow">
                                                <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px; min-width: 40px;">
                                                    <i class="bi bi-person-badge-fill text-white"
                                                        style="font-size: 1.5rem;"></i>
                                                </div>
                                                <div class="grow min-w-0">
                                                    <div class="fw-bold text-white mb-1" style="font-size: 1rem;">
                                                        {{ $studentInfo['name'] ?? '' }}
                                                    </div>
                                                    @if (!empty($studentPaymentHistory))
                                                        <button type="button" class="btn btn-sm btn-light px-2 py-1"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            data-bs-custom-class="payment-history-tooltip"
                                                            title="{{ $popoverContent }}" style="font-size: 0.75rem;">
                                                            <i class="bi bi-info-circle me-1"></i>Historique
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <button type="button" wire:click="resetStudent"
                                                class="btn btn-sm rounded-circle p-0 ms-2"
                                                aria-label="Réinitialiser la sélection de l'élève"
                                                style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border: none;">
                                                <i class="bi bi-x text-white" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif <!-- Section 3 : Formulaire de paiement -->
                                <div class="mb-0">
                                    @livewire('application.payment.payment-form-component', key('payment-form'))
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Liste des paiements -->
                <div class="col-lg-7 col-xl-8">
                    <div class="card shadow-sm">
                        <div class="card-header border-0">
                            <h6 class="mb-0 fw-semibold text-info">
                                <i class="bi bi-list-check me-2"></i>Paiements d'aujourd'hui
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            @livewire('application.payment.daily-payment-list')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicateur de chargement pour actions spécifiques uniquement -->
            <div wire:loading.delay.longer wire:target="selectStudent,resetStudent,savePayment,deletePayment"
                class="position-fixed top-0 start-0 w-100 h-100"
                style="background: rgba(0, 0, 0, 0.3); z-index: var(--z-modal-backdrop); backdrop-filter: blur(2px);">
            </div>

            <div wire:loading.delay.longer wire:target="selectStudent,resetStudent,savePayment,deletePayment"
                class="position-fixed top-50 start-50 translate-middle" style="z-index: var(--z-modal);">
                <div class="card shadow-lg border-0" style="min-width: 200px;">
                    <div class="card-body text-center py-4">
                        <div class="spinner-border text-primary mb-3" role="status"
                            style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <div class="fw-bold text-primary">Traitement en cours...</div>
                        <small class="text-muted">Veuillez patienter</small>
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>

@push('styles')
    <style>
        /* Style personnalisé pour le tooltip d'historique */
        .payment-history-tooltip {
            --bs-tooltip-max-width: 350px;
        }

        .payment-history-tooltip .tooltip-inner {
            max-width: 350px;
            text-align: left;
            white-space: pre-line;
            font-size: 0.85rem;
            padding: 12px 16px;
            background-color: #1e293b;
            border-radius: 8px;
            line-height: 1.6;
        }

        .payment-history-tooltip .tooltip-arrow::before {
            border-right-color: #1e293b;
        }
    </style>
@endpush

@script
    <script>
        // Initialiser les tooltips Bootstrap
        function initTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                // Supprimer l'ancien tooltip s'il existe
                var existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                if (existingTooltip) {
                    existingTooltip.dispose();
                }
                // Créer un nouveau tooltip avec trigger manuel pour le maintien du clic
                var tooltip = new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'manual',
                    html: false,
                    delay: {
                        show: 0,
                        hide: 200
                    }
                });

                // Afficher au survol
                tooltipTriggerEl.addEventListener('mouseenter', function() {
                    tooltip.show();
                });

                // Masquer quand on quitte (sauf si on maintient le clic)
                tooltipTriggerEl.addEventListener('mouseleave', function(e) {
                    // Ne pas masquer si le bouton est maintenu enfoncé
                    if (e.buttons !== 1) {
                        tooltip.hide();
                    }
                });

                // Garder affiché tant qu'on maintient le clic
                tooltipTriggerEl.addEventListener('mousedown', function() {
                    tooltip.show();
                });

                // Masquer quand on relâche le clic
                tooltipTriggerEl.addEventListener('mouseup', function() {
                    tooltip.hide();
                });

                // Masquer aussi si on quitte la zone en maintenant le clic
                document.addEventListener('mouseup', function() {
                    tooltip.hide();
                });
            });
        }

        // Initialiser au chargement du composant
        initTooltips();

        // Ré-initialiser après sélection d'un élève
        $wire.on('studentSelected', () => {
            setTimeout(initTooltips, 150);
        });

        // Observer les changements DOM pour réinitialiser les tooltips
        const observer = new MutationObserver(() => {
            setTimeout(initTooltips, 100);
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    </script>
@endscript
