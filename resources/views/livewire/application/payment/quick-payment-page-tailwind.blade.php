{{-- Quick Payment Page - Version Tailwind CSS --}}
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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <!-- Colonne gauche : Zone de saisie (Recherche + Infos + Formulaire) -->
                <div class="lg:col-span-5 xl:col-span-4">
                    <div class="sticky top-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm relative z-50 overflow-visible">
                            <div class="p-6 overflow-visible">
                                <!-- Section 1 : Recherche d'élève -->
                                <div class="mb-6">
                                    <h6 class="mb-4 font-semibold text-blue-600 dark:text-blue-400 text-base flex items-center">
                                        <i class="bi bi-search mr-2"></i>Étape 1 : Rechercher l'élève
                                    </h6>
                                    <div class="relative">
                                        <label class="block text-sm text-gray-500 dark:text-gray-400 mb-2">
                                            Nom de l'élève
                                        </label>
                                        <div class="relative">
                                            <input type="text" wire:model.live.debounce.300ms="search"
                                                class="w-full px-4 py-3 pr-12 text-base border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all duration-200"
                                                placeholder="Tapez le nom de l'élève..." @focus="showDropdown = true"
                                                autocomplete="off">

                                            <!-- Indicateur de chargement -->
                                            <div wire:loading wire:target="search"
                                                class="absolute top-1/2 right-0 -translate-y-1/2 z-10 pr-3">
                                                <div class="w-5 h-5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                                            </div>

                                            @if (strlen($search) > 0)
                                                <button type="button" wire:click="$set('search', '')"
                                                    wire:loading.remove wire:target="search"
                                                    class="absolute top-1/2 right-0 -translate-y-1/2 z-10 px-3 py-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                                    aria-label="Vider la recherche"
                                                    title="Vider la recherche">
                                                    <i class="bi bi-x-circle-fill text-xl"></i>
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
                                                class="absolute w-full z-[9999] max-h-96 overflow-y-auto top-full mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700">
                                                @foreach ($searchResults as $result)
                                                    <div wire:click="selectStudent({{ $result['id'] }}, '{{ addslashes($result['student_name']) }}')"
                                                        class="p-4 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 cursor-pointer transition-all duration-150 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                                        <div class="flex justify-between items-center">
                                                            <div class="flex-1 min-w-0">
                                                                <div class="font-semibold text-gray-900 dark:text-gray-100 mb-1 flex items-center">
                                                                    <i class="bi bi-person-circle mr-2 text-emerald-600 dark:text-emerald-400"></i>
                                                                    {{ $result['student_name'] }}
                                                                </div>
                                                                <div class="flex items-center gap-2 text-sm">
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-xs font-medium">
                                                                        {{ $result['code'] }}
                                                                    </span>
                                                                    <span class="text-gray-600 dark:text-gray-400">
                                                                        {{ $result['class_room'] }} - {{ $result['option'] }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <i class="bi bi-arrow-right-circle text-emerald-600 dark:text-emerald-400 text-xl ml-3"></i>
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
                                                class="absolute w-full z-[9999] top-full mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700">
                                                <div class="text-center py-8">
                                                    <i class="bi bi-inbox text-gray-400 dark:text-gray-500 text-5xl opacity-50"></i>
                                                    <p class="mt-3 text-gray-600 dark:text-gray-400 font-medium mb-0">Aucun élève trouvé</p>
                                                    <small class="text-gray-500 dark:text-gray-500">Essayez un autre nom</small>
                                                </div>
                                            </div>
                                        @endif

                                        <small class="block mt-2 text-gray-500 dark:text-gray-400 text-sm">
                                            <i class="bi bi-info-circle mr-1"></i>Tapez au moins 2 caractères pour rechercher
                                        </small>
                                    </div>

                                    <!-- Indicateur de chargement -->
                                    <div wire:loading wire:target="updatedSearch" class="mt-3">
                                        <div class="flex items-center p-3 rounded-lg bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300">
                                            <div class="w-5 h-5 border-2 border-sky-700 dark:border-sky-300 border-t-transparent rounded-full animate-spin mr-3"></div>
                                            <div>
                                                <strong class="block">Recherche en cours...</strong>
                                                <div class="text-sm">Veuillez patienter</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 2 : Informations de l'élève sélectionné -->
                                @if (!empty($studentInfo))
                                    <div class="mb-6 p-4 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                                <div class="w-10 h-10 min-w-[2.5rem] rounded-full bg-white/25 flex items-center justify-center">
                                                    <i class="bi bi-person-badge-fill text-white text-2xl"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-bold text-white mb-1 text-base">
                                                        {{ $studentInfo['name'] ?? '' }}
                                                    </div>
                                                    @if (!empty($studentPaymentHistory))
                                                        <button type="button" 
                                                            class="inline-flex items-center px-2 py-1 text-xs bg-white rounded hover:bg-gray-100 transition-colors"
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="bottom"
                                                            data-bs-custom-class="payment-history-tooltip"
                                                            title="{{ $popoverContent }}">
                                                            <i class="bi bi-info-circle mr-1"></i>Historique
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <button type="button" wire:click="resetStudent"
                                                class="w-7 h-7 rounded-full flex items-center justify-center ml-2 bg-white/20 hover:bg-white/30 transition-colors border-0"
                                                aria-label="Réinitialiser la sélection de l'élève">
                                                <i class="bi bi-x text-white text-xl"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <!-- Section 3 : Formulaire de paiement -->
                                <div>
                                    @livewire('application.payment.payment-form-component', key('payment-form'))
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Liste des paiements -->
                <div class="lg:col-span-7 xl:col-span-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h6 class="mb-0 font-semibold text-cyan-600 dark:text-cyan-400 flex items-center">
                                <i class="bi bi-list-check mr-2"></i>Paiements d'aujourd'hui
                            </h6>
                        </div>
                        <div class="p-0">
                            @livewire('application.payment.daily-payment-list')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicateur de chargement pour actions spécifiques uniquement -->
            <div wire:loading.delay.longer wire:target="selectStudent,resetStudent,savePayment,deletePayment"
                class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[9998]">
            </div>

            <div wire:loading.delay.longer wire:target="selectStudent,resetStudent,savePayment,deletePayment"
                class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[9999]">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-0 min-w-[200px]">
                    <div class="text-center py-6 px-8">
                        <div class="w-12 h-12 mx-auto mb-4 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                        <div class="font-bold text-blue-600 dark:text-blue-400 mb-1">Traitement en cours...</div>
                        <small class="text-gray-500 dark:text-gray-400">Veuillez patienter</small>
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
