{{-- Paiement Rapide - Version Tailwind CSS Moderne --}}
<div class="space-y-4">
    {{-- Breadcrumb --}}
    <x-navigation.bread-crumb icon='bi bi-cash-coin' label="Paiement Rapide">
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="finance.dashboard" isFirst=true />
        <x-navigation.bread-crumb-item label='Paiements' />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div x-data="{
            showDropdown: @entangle('showDropdown'),
            closeDropdown() {
                showDropdown = false;
                $wire.closeDropdown();
            }
        }" @click.outside="closeDropdown()">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- Colonne gauche : Zone de saisie (35% de largeur) --}}
                <div class="lg:col-span-5 xl:col-span-4">
                    <div class="sticky top-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-visible relative z-10">
                            <div class="p-6">
                                {{-- Section 1 : Recherche d'élève --}}
                                <div class="mb-6">
                                    <h3 class="text-base font-semibold text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                                        <i class="bi bi-search"></i>
                                        Étape 1 : Rechercher l'élève
                                    </h3>
                                    
                                    <div class="relative">
                                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                                            Nom de l'élève
                                        </label>
                                        
                                        <div class="relative">
                                            <input type="text" 
                                                   wire:model.live.debounce.300ms="search"
                                                   @focus="showDropdown = true"
                                                   class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-lg"
                                                   placeholder="Tapez le nom de l'élève..."
                                                   autocomplete="off">

                                            {{-- Indicateur de chargement --}}
                                            <div wire:loading wire:target="search"
                                                 class="absolute right-3 top-1/2 -translate-y-1/2">
                                                <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-500 border-t-transparent"></div>
                                            </div>

                                            {{-- Bouton clear --}}
                                            @if (strlen($search) > 0)
                                                <button type="button" 
                                                        wire:click="$set('search', '')"
                                                        wire:loading.remove 
                                                        wire:target="search"
                                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                                        aria-label="Vider la recherche">
                                                    <i class="bi bi-x-circle-fill text-xl"></i>
                                                </button>
                                            @endif
                                        </div>

                                        {{-- Dropdown autocomplete --}}
                                        @if (count($searchResults) > 0)
                                            <div x-show="showDropdown"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                                 x-transition:enter-end="opacity-100 translate-y-0"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 translate-y-0"
                                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                                 class="absolute w-full mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto z-50"
                                                 style="display: none;">
                                                @foreach ($searchResults as $result)
                                                    <div wire:click="selectStudent({{ $result['id'] }}, '{{ addslashes($result['student_name']) }}')"
                                                         class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                                        <div class="flex justify-between items-center">
                                                            <div class="flex-1">
                                                                <div class="font-semibold text-gray-900 dark:text-gray-100 mb-1 flex items-center gap-2">
                                                                    <i class="bi bi-person-circle text-green-600 dark:text-green-400"></i>
                                                                    {{ $result['student_name'] }}
                                                                </div>
                                                                <div class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                                                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded text-xs font-medium">
                                                                        {{ $result['code'] }}
                                                                    </span>
                                                                    {{ $result['class_room'] }} - {{ $result['option'] }}
                                                                </div>
                                                            </div>
                                                            <i class="bi bi-arrow-right-circle text-green-600 dark:text-green-400 text-xl"></i>
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
                                                 class="absolute w-full mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
                                                 style="display: none;">
                                                <div class="p-8 text-center">
                                                    <i class="bi bi-inbox text-gray-400 dark:text-gray-600 text-5xl mb-3 block"></i>
                                                    <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Aucun élève trouvé</p>
                                                    <small class="text-gray-500 dark:text-gray-400">Essayez un autre nom</small>
                                                </div>
                                            </div>
                                        @endif

                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-1">
                                            <i class="bi bi-info-circle"></i>
                                            Tapez au moins 2 caractères pour rechercher
                                        </p>
                                    </div>

                                    {{-- Indicateur de chargement global --}}
                                    <div wire:loading wire:target="updatedSearch" class="mt-3">
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 flex items-center gap-3">
                                            <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-500 border-t-transparent"></div>
                                            <div>
                                                <p class="font-semibold text-blue-700 dark:text-blue-300 text-sm">Recherche en cours...</p>
                                                <p class="text-xs text-blue-600 dark:text-blue-400">Veuillez patienter</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section 2 : Informations de l'élève sélectionné --}}
                                @if (!empty($studentInfo))
                                    <div class="mb-6 p-4 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 text-white relative">
                                        <div class="flex items-start gap-3">
                                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-2.5 flex-shrink-0">
                                                <i class="bi bi-person-badge-fill text-xl"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-white mb-2">
                                                    {{ $studentInfo['name'] ?? '' }}
                                                </h4>
                                                @if (!empty($studentPaymentHistory))
                                                    <button type="button" 
                                                            class="bg-white/20 hover:bg-white/30 backdrop-blur-sm px-3 py-1 rounded-md text-sm font-medium transition-colors"
                                                            @click="$tooltip('{{ addslashes($popoverContent) }}')">
                                                        <i class="bi bi-info-circle mr-1"></i>Historique
                                                    </button>
                                                @endif
                                            </div>
                                            <button type="button" 
                                                    wire:click="resetStudent"
                                                    class="bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full w-7 h-7 flex items-center justify-center transition-colors flex-shrink-0"
                                                    aria-label="Réinitialiser">
                                                <i class="bi bi-x text-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                {{-- Section 3 : Formulaire de paiement --}}
                                <div>
                                    @livewire('application.payment.payment-form-component', key('payment-form'))
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Colonne droite : Liste des paiements (65% de largeur) --}}
                <div class="lg:col-span-7 xl:col-span-8">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-base font-semibold text-blue-600 dark:text-blue-400 flex items-center gap-2">
                                <i class="bi bi-list-check"></i>
                                Paiements d'aujourd'hui
                            </h3>
                        </div>
                        <div>
                            @livewire('application.payment.daily-payment-list')
                        </div>
                    </div>
                </div>
            </div>

            {{-- Overlay de chargement --}}
            <div wire:loading.delay.longer 
                 wire:target="selectStudent,resetStudent,savePayment,deletePayment"
                 class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40">
            </div>

            {{-- Modal de chargement --}}
            <div wire:loading.delay.longer 
                 wire:target="selectStudent,resetStudent,savePayment,deletePayment"
                 class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700 p-8 min-w-[250px]">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-500 border-t-transparent mx-auto mb-4"></div>
                        <p class="font-bold text-blue-600 dark:text-blue-400 mb-1">Traitement en cours...</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Veuillez patienter</p>
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>

@script
    <script>
        // Tooltip simple avec Alpine.js
        Alpine.magic('tooltip', () => {
            return (content) => {
                // Créer tooltip simple
                alert(content.replace(/<br>/g, '\n'));
            }
        });

        // Initialiser après sélection d'un élève
        $wire.on('studentSelected', () => {
            console.log('Student selected');
        });
    </script>
@endscript
