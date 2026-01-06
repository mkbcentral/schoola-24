{{-- Student Info Page - Version Tailwind CSS --}}
<div x-data="{
    showDropdown: @entangle('showDropdown'),
    closeDropdown() {
        showDropdown = false;
        $wire.closeDropdown();
    }
}" @click.outside="closeDropdown()">

    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-gray-900 dark:text-gray-100 text-3xl font-bold flex items-center">
            <i class="bi bi-person-badge text-green-600 dark:text-green-400 mr-3"></i> 
            Informations Élève
        </h1>
    </div>

    <!-- Barre de recherche toujours affichée -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-6 border-0">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <label class="block font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            <i class="bi bi-search mr-2"></i>Rechercher un élève
                        </label>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="w-full px-4 py-3 text-base border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 transition-all"
                            placeholder="Tapez le nom de l'élève..."
                            @focus="showDropdown = true" autocomplete="off">

                        <!-- Dropdown autocomplete -->
                        @if (count($searchResults) > 0)
                            <div x-show="showDropdown" x-transition
                                class="absolute w-full bg-white dark:bg-gray-800 rounded-lg shadow-xl mt-2 z-[9999] max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700">
                                @foreach ($searchResults as $result)
                                    <div wire:click="selectStudent({{ $result['id'] }}, '{{ addslashes($result['student_name']) }}')"
                                        class="p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="flex justify-between items-center">
                                            <div class="flex-1 min-w-0">
                                                <div class="font-semibold text-gray-900 dark:text-gray-100 text-base flex items-center mb-1">
                                                    <i class="bi bi-person-circle mr-2 text-emerald-600 dark:text-emerald-400"></i>
                                                    {{ $result['student_name'] }}
                                                </div>
                                                <div class="text-gray-600 dark:text-gray-400 text-sm flex items-center gap-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-xs font-medium">{{ $result['code'] }}</span>
                                                    {{ $result['class_room'] }} - {{ $result['option'] }}
                                                </div>
                                            </div>
                                            <i class="bi bi-arrow-right-circle text-emerald-600 dark:text-emerald-400 text-xl ml-3"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif (strlen($search) >= 2 && count($searchResults) === 0)
                            <div x-show="showDropdown" x-transition
                                class="absolute w-full bg-white dark:bg-gray-800 rounded-lg shadow-xl mt-2 p-4 z-[9999] border border-gray-200 dark:border-gray-700">
                                <div class="text-center text-gray-500 dark:text-gray-400">
                                    <i class="bi bi-search mr-2"></i>Aucun élève trouvé
                                </div>
                            </div>
                        @endif

                        <small class="block mt-2 text-gray-500 dark:text-gray-400 text-sm">
                            <i class="bi bi-info-circle mr-1"></i>Tapez au moins 2 caractères pour rechercher
                        </small>
                    </div>

                    <!-- Indicateur de chargement -->
                    <div wire:loading wire:target="updatedSearch" class="mt-3">
                        <div class="flex items-center p-3 rounded-lg bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 border-0">
                            <div class="w-5 h-5 border-2 border-sky-700 dark:border-sky-300 border-t-transparent rounded-full animate-spin mr-3"></div>
                            <div>
                                <strong class="block">Recherche en cours...</strong>
                                <div class="text-sm">Veuillez patienter</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($studentInfo)
                    <div class="lg:text-right flex-shrink-0">
                        <button type="button" wire:click="resetStudentInfo"
                            class="px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm inline-flex items-center">
                            <i class="bi bi-arrow-clockwise mr-2"></i>Réinitialiser
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Affichage des informations de l'élève -->
    @if ($studentInfo)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Section gauche: Formulaire de paiement -->
            <div class="lg:col-span-4">
                <div class="sticky top-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 rounded-t-xl">
                        <h6 class="mb-0 text-gray-900 dark:text-gray-100 font-semibold flex items-center">
                            <i class="bi bi-cash-coin mr-2 text-green-600 dark:text-green-400"></i>Nouveau Paiement
                        </h6>
                    </div>
                    <div class="p-4">
                        <!-- Formulaire de paiement -->
                        <form>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catégorie de frais</label>
                                <select wire:model.live="selectedCategoryId" 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    @foreach ($availableCategories as $category)
                                        <option value="{{ $category['id'] }}">
                                            {{ $category['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Mois</label>
                                <select class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option>Sélectionner un mois</option>
                                    <option>Septembre</option>
                                    <option>Octobre</option>
                                    <option>Novembre</option>
                                    <option>Décembre</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Montant</label>
                                <input type="number" 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                    placeholder="0">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Devise</label>
                                <select class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option>CDF</option>
                                    <option>USD</option>
                                </select>
                            </div>

                            <button type="button" 
                                class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-sm flex items-center justify-center">
                                <i class="bi bi-check-circle mr-2"></i>Enregistrer le paiement
                            </button>
                        </form>

                        <!-- Stats rapides -->
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-3 font-semibold">Résumé</div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Mois payés</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                    {{ $studentInfo['summary']['total_months_paid'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Mois impayés</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                    {{ $studentInfo['summary']['total_months_unpaid'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Total paiements</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                    {{ $studentInfo['summary']['total_payments_made'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section droite: Informations détaillées -->
            <div class="lg:col-span-8">
                <div class="space-y-6">
                    <!-- Identité et Inscription combinées -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 rounded-t-xl">
                            <h6 class="mb-0 text-gray-900 dark:text-gray-100 font-semibold flex items-center">
                                <i class="bi bi-person-vcard mr-2 text-blue-600 dark:text-blue-400"></i>Identité et Inscription
                            </h6>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Section Identité -->
                                <div>
                                    <h6 class="text-blue-600 dark:text-blue-400 mb-4 text-sm font-semibold flex items-center">
                                        <i class="bi bi-person mr-2"></i>IDENTITÉ DE L'ÉLÈVE
                                    </h6>
                                    <div class="mb-4">
                                        <strong class="text-gray-600 dark:text-gray-400 block mb-1">Nom complet:</strong>
                                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $studentInfo['student']['name'] }}
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <strong class="text-gray-600 dark:text-gray-400 block mb-1">Date de naissance:</strong>
                                            <div class="text-gray-900 dark:text-gray-100">{{ $studentInfo['student']['date_of_birth'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <strong class="text-gray-600 dark:text-gray-400 block mb-1">Âge:</strong>
                                            <div class="text-gray-900 dark:text-gray-100">{{ $studentInfo['student']['age'] ?? 'N/A' }} ans</div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <strong class="text-gray-600 dark:text-gray-400 block mb-1">Lieu de naissance:</strong>
                                        <div class="text-gray-900 dark:text-gray-100">{{ $studentInfo['student']['place_of_birth'] ?? 'N/A' }}</div>
                                    </div>
                                    <div>
                                        <strong class="text-gray-600 dark:text-gray-400 block mb-1">Genre:</strong>
                                        <div class="text-gray-900 dark:text-gray-100">{{ $studentInfo['student']['gender'] ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <!-- Séparateur vertical + Section Inscription -->
                                <div class="lg:border-l lg:border-gray-200 dark:border-gray-700 lg:pl-6">
                                    <h6 class="text-blue-600 dark:text-blue-400 mb-4 text-sm font-semibold flex items-center">
                                        <i class="bi bi-building mr-2"></i>INSCRIPTION
                                    </h6>
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <strong class="text-gray-600 dark:text-gray-400 block mb-1">Code:</strong>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 text-base font-medium">
                                                    {{ $studentInfo['registration']['code'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <strong class="text-gray-600 dark:text-gray-400 block mb-1">Classe:</strong>
                                            <div class="text-gray-900 dark:text-gray-100">{{ $studentInfo['registration']['class_room'] }}</div>
                                        </div>
                                        <div>
                                            <strong class="text-gray-600 dark:text-gray-400 block mb-1">Option:</strong>
                                            <div class="text-gray-900 dark:text-gray-100">{{ $studentInfo['registration']['option'] ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <strong class="text-gray-600 dark:text-gray-400 block mb-1">Année scolaire:</strong>
                                            <div class="text-gray-900 dark:text-gray-100">{{ $studentInfo['registration']['school_year'] }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <strong class="text-gray-600 dark:text-gray-400 block mb-1">Date d'inscription:</strong>
                                        <div class="text-gray-900 dark:text-gray-100">{{ $studentInfo['registration']['registration_date'] }}</div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <strong class="text-gray-600 dark:text-gray-400 block mb-1">Ancien élève:</strong>
                                            <div>
                                                @if ($studentInfo['registration']['is_old'])
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">Oui</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Non</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <strong class="text-gray-600 dark:text-gray-400 block mb-1">Sous dérogation:</strong>
                                            <div>
                                                @if ($studentInfo['registration']['is_under_derogation'])
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">Oui</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">Non</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paiements mensuels et Autres frais en grid -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <!-- Paiements mensuels -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0 h-full">
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 rounded-t-xl">
                                <h6 class="mb-0 text-gray-900 dark:text-gray-100 font-semibold">
                                    <i class="bi bi-calendar-check mr-2 text-green-600 dark:text-green-400"></i>Paiements Mensuels
                                    <small class="text-gray-500 dark:text-gray-400 font-normal">({{ $studentInfo['monthly_payments']['category_name'] }})</small>
                                </h6>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border-2 border-green-500 dark:border-green-600">
                                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                            {{ $studentInfo['monthly_payments']['paid_months_count'] }}
                                        </div>
                                        <div class="text-green-700 dark:text-green-500 font-semibold text-sm">Mois payés</div>
                                    </div>
                                    <div class="text-center p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border-2 border-red-500 dark:border-red-600">
                                        <div class="text-3xl font-bold text-red-600 dark:text-red-400">
                                            {{ $studentInfo['monthly_payments']['unpaid_months_count'] }}
                                        </div>
                                        <div class="text-red-700 dark:text-red-500 font-semibold text-sm">Mois impayés</div>
                                    </div>
                                </div>

                                <!-- Liste des mois payés -->
                                @if (count($studentInfo['monthly_payments']['paid_months']) > 0)
                                    <div class="mb-4">
                                        <h6 class="text-green-600 dark:text-green-400 font-semibold mb-3 flex items-center">
                                            <i class="bi bi-check-circle mr-2"></i>Mois payés
                                        </h6>
                                        <div class="max-h-64 overflow-y-auto space-y-2">
                                            @foreach ($studentInfo['monthly_payments']['paid_months'] as $month)
                                                <div class="flex justify-between items-center p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-600">
                                                    <div>
                                                        <strong class="text-gray-900 dark:text-gray-100 block">{{ $month['month'] }}</strong>
                                                        <small class="text-gray-500 dark:text-gray-400 text-xs">{{ $month['paid_at'] }}</small>
                                                    </div>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                                        {{ number_format($month['amount'], 0, ',', ' ') }} {{ $month['currency'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Liste des mois impayés -->
                                @if (count($studentInfo['monthly_payments']['unpaid_months']) > 0)
                                    <div>
                                        <h6 class="text-red-600 dark:text-red-400 font-semibold mb-3 flex items-center">
                                            <i class="bi bi-x-circle mr-2"></i>Mois impayés
                                        </h6>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($studentInfo['monthly_payments']['unpaid_months'] as $month)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                                    {{ $month['month'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Autres frais -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0 h-full">
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 rounded-t-xl">
                                <h6 class="mb-0 text-gray-900 dark:text-gray-100 font-semibold flex items-center">
                                    <i class="bi bi-cash-stack mr-2 text-yellow-600 dark:text-yellow-400"></i>Autres Frais
                                </h6>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-500 dark:border-blue-600">
                                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ $studentInfo['fees_payments']['paid_fees_count'] }}
                                        </div>
                                        <div class="text-blue-700 dark:text-blue-500 font-semibold text-sm">Frais payés</div>
                                    </div>
                                    <div class="text-center p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-500 dark:border-yellow-600">
                                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">
                                            {{ $studentInfo['fees_payments']['unpaid_fees_count'] }}
                                        </div>
                                        <div class="text-yellow-700 dark:text-yellow-500 font-semibold text-sm">Frais impayés</div>
                                    </div>
                                </div>

                                <!-- Frais payés -->
                                @if (count($studentInfo['fees_payments']['paid_fees']) > 0)
                                    <div class="mb-4">
                                        <h6 class="text-blue-600 dark:text-blue-400 font-semibold mb-3 flex items-center">
                                            <i class="bi bi-check-circle mr-2"></i>Frais payés
                                        </h6>
                                        <div class="max-h-52 overflow-y-auto space-y-2">
                                            @foreach ($studentInfo['fees_payments']['paid_fees'] as $fee)
                                                <div class="flex justify-between items-center p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-600">
                                                    <div>
                                                        <strong class="text-gray-900 dark:text-gray-100 block">{{ $fee['category_name'] }}</strong>
                                                        <small class="text-gray-500 dark:text-gray-400 text-xs">{{ $fee['paid_at'] }}</small>
                                                    </div>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                        {{ number_format($fee['amount'], 0, ',', ' ') }} {{ $fee['currency'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Frais impayés -->
                                @if (count($studentInfo['fees_payments']['unpaid_fees']) > 0)
                                    <div>
                                        <h6 class="text-yellow-600 dark:text-yellow-400 font-semibold mb-3 flex items-center">
                                            <i class="bi bi-exclamation-circle mr-2"></i>Frais impayés
                                        </h6>
                                        <div class="max-h-52 overflow-y-auto space-y-2">
                                            @foreach ($studentInfo['fees_payments']['unpaid_fees'] as $fee)
                                                <div class="flex justify-between items-center p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 dark:border-yellow-600">
                                                    <strong class="text-gray-900 dark:text-gray-100">{{ $fee['category_name'] }}</strong>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                                        {{ number_format($fee['amount'], 0, ',', ' ') }} {{ $fee['currency'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Totaux -->
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                                    <div class="flex justify-between items-center">
                                        <strong class="text-gray-700 dark:text-gray-300">Total payé:</strong>
                                        <strong class="text-green-600 dark:text-green-400">
                                            {{ number_format($studentInfo['fees_payments']['total_paid_amount'], 0, ',', ' ') }}
                                        </strong>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <strong class="text-gray-700 dark:text-gray-300">Total impayé:</strong>
                                        <strong class="text-red-600 dark:text-red-400">
                                            {{ number_format($studentInfo['fees_payments']['total_unpaid_amount'], 0, ',', ' ') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé général -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 rounded-t-xl">
                            <h6 class="mb-0 text-gray-900 dark:text-gray-100 font-semibold flex items-center">
                                <i class="bi bi-bar-chart mr-2 text-blue-600 dark:text-blue-400"></i>Résumé Général
                            </h6>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div class="p-4 rounded-lg bg-gray-100 dark:bg-gray-700">
                                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ $studentInfo['summary']['total_payments_made'] }}
                                    </div>
                                    <div class="text-gray-600 dark:text-gray-400 font-semibold text-sm mt-1">Total paiements</div>
                                </div>
                                <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/20">
                                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                        {{ $studentInfo['summary']['total_months_paid'] }}
                                    </div>
                                    <div class="text-green-700 dark:text-green-500 font-semibold text-sm mt-1">Mois payés</div>
                                </div>
                                <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ $studentInfo['summary']['total_fees_paid'] }}
                                    </div>
                                    <div class="text-blue-700 dark:text-blue-500 font-semibold text-sm mt-1">Frais payés</div>
                                </div>
                                <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20">
                                    <div class="text-3xl font-bold text-red-600 dark:text-red-400">
                                        {{ $studentInfo['summary']['total_months_unpaid'] + $studentInfo['summary']['total_fees_unpaid'] }}
                                    </div>
                                    <div class="text-red-700 dark:text-red-500 font-semibold text-sm mt-1">Total impayés</div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <small class="text-gray-500 dark:text-gray-400 flex items-center justify-center">
                                    <i class="bi bi-clock mr-1"></i>Généré le {{ $studentInfo['generated_at'] }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading indicator -->
    <div wire:loading wire:target="loadStudentInfo" class="text-center my-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-0 inline-block">
            <div class="p-12">
                <div class="w-12 h-12 mx-auto mb-4 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <h5 class="text-blue-600 dark:text-blue-400 text-xl font-semibold mb-2">Chargement des informations...</h5>
                <p class="text-gray-500 dark:text-gray-400 mb-0">Veuillez patienter pendant que nous récupérons les données de l'élève</p>
            </div>
        </div>
    </div>

</div>
