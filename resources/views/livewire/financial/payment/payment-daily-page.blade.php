<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête avec titre et bouton nouveau paiement -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-cash-stack text-white text-xl"></i>
                        </div>
                        <span>Paiements du jour</span>
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Gérez et suivez tous les paiements quotidiens
                    </p>
                </div>
                
                <button wire:click="openPaymentModal" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="bi bi-plus-circle text-xl mr-2"></i>
                    Nouveau Paiement
                </button>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total paiements -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Paiements</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $dailyStats['total_payments'] }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="bi bi-receipt text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Montant total -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Montant Total</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ number_format($dailyStats['total_amount'], 0, ',', ' ') }} {{ $dailyStats['currency'] }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="bi bi-cash-coin text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Paiements validés -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Validés</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $dailyStats['paid_count'] }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Paiements en attente -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En Attente</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                            {{ $dailyStats['pending_count'] }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                        <i class="bi bi-clock-history text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                <!-- Filtre par date -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 flex-1">
                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300 whitespace-nowrap">
                        <i class="bi bi-calendar-event text-xl"></i>
                        <span class="font-semibold">Date :</span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <input type="date" 
                            wire:model.live="selectedDate"
                            class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                        
                        <div class="flex gap-2">
                            <button wire:click="$set('selectedDate', '{{ now()->format('Y-m-d') }}')"
                                class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors whitespace-nowrap">
                                Aujourd'hui
                            </button>
                            <button wire:click="$set('selectedDate', '{{ now()->subDay()->format('Y-m-d') }}')"
                                class="px-4 py-2.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-lg transition-colors whitespace-nowrap">
                                Hier
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Séparateur vertical -->
                <div class="hidden lg:block w-px h-12 bg-gray-200 dark:bg-gray-700"></div>
                
                <!-- Filtre par catégorie -->
                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300 whitespace-nowrap">
                        <i class="bi bi-tag text-xl"></i>
                        <span class="font-semibold">Catégorie :</span>
                    </div>
                    <div class="relative flex-1 lg:flex-initial lg:w-64">
                        <select wire:model.live="filterCategoryFeeId" 
                            class="w-full appearance-none px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm font-medium cursor-pointer transition-all hover:border-blue-400 dark:hover:border-blue-500">
                            <option value="" class="font-normal">📋 Toutes les catégories</option>
                            @foreach($categoryFees as $category)
                                <option value="{{ $category->id }}" class="font-normal">
                                    {{ ucfirst($category->name) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="bi bi-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des paiements -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-list-ul"></i>
                    Liste des paiements
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Élève
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Catégorie
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Mois
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Montant
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ substr($payment->registration->student->name ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $payment->registration->student->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                <i class="bi bi-diagram-3 text-xs"></i>
                                                {{ $payment->registration->classRoom->getOriginalClassRoomName() ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            {{ $payment->scolarFee->categoryFee->name ?? 'N/A' }}
                                        </span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $payment->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ \App\Domain\Helpers\DateFormatHelper::getFrenchMonthName($payment->month) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($payment->scolarFee->amount ?? 0, 0, ',', ' ') }} 
                                        <span class="text-sm text-gray-500">{{ $payment->scolarFee->categoryFee->currency ?? 'FC' }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($payment->is_paid)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            <i class="bi bi-check-circle mr-1"></i>
                                            Payé
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                            <i class="bi bi-clock-history mr-1"></i>
                                            En attente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if(!$payment->is_paid)
                                            <button wire:click="markAsPaid({{ $payment->id }})" 
                                                wire:confirm="Confirmer le paiement ?"
                                                class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                                title="Valider le paiement">
                                                <i class="bi bi-check-circle text-lg"></i>
                                            </button>
                                            <button wire:click="deletePayment({{ $payment->id }})" 
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer ce paiement ?"
                                                class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                title="Supprimer">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        @else
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs text-gray-500 dark:text-gray-400 italic">
                                                    Par {{ $payment->user->name ?? 'N/A' }}
                                                </span>
                                                
                                                <!-- Bouton renvoyer SMS pour paiements validés -->
                                                <button 
                                                    wire:click="sendSmsNotification({{ $payment->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="sendSmsNotification"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                    title="Renvoyer SMS au parent">
                                                    <i class="bi bi-chat-dots text-lg" wire:loading.remove wire:target="sendSmsNotification({{ $payment->id }})"></i>
                                                    <i class="bi bi-arrow-repeat animate-spin text-lg" wire:loading wire:target="sendSmsNotification({{ $payment->id }})"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg">Aucun paiement trouvé pour cette date</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Personnalisée -->
            @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Informations de pagination -->
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Affichage de 
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $payments->firstItem() ?? 0 }}</span>
                            à 
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $payments->lastItem() ?? 0 }}</span>
                            sur 
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $payments->total() }}</span>
                            résultats
                        </div>

                        <!-- Boutons de pagination -->
                        <div class="flex items-center gap-2">
                            {{-- Bouton Première page --}}
                            @if($payments->onFirstPage())
                                <button disabled 
                                    class="pagination-button px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                    <i class="bi bi-chevron-double-left"></i>
                                </button>
                            @else
                                <button wire:click="gotoPage(1)" 
                                    class="pagination-button px-3 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 hover:shadow-md">
                                    <i class="bi bi-chevron-double-left"></i>
                                </button>
                            @endif

                            {{-- Bouton Précédent --}}
                            @if($payments->onFirstPage())
                                <button disabled 
                                    class="pagination-button px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed flex items-center gap-2">
                                    <i class="bi bi-chevron-left"></i>
                                    <span class="hidden sm:inline">Précédent</span>
                                </button>
                            @else
                                <button wire:click="previousPage" 
                                    class="pagination-button px-4 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 hover:shadow-md flex items-center gap-2">
                                    <i class="bi bi-chevron-left"></i>
                                    <span class="hidden sm:inline">Précédent</span>
                                </button>
                            @endif

                            {{-- Numéros de pages --}}
                            <div class="flex items-center gap-1">
                                @php
                                    $currentPage = $payments->currentPage();
                                    $lastPage = $payments->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp

                                @for($page = $start; $page <= $end; $page++)
                                    @if($page == $currentPage)
                                        <button 
                                            class="pagination-button px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold shadow-lg transform scale-105 relative z-10">
                                            {{ $page }}
                                        </button>
                                    @else
                                        <button wire:click="gotoPage({{ $page }})" 
                                            class="pagination-button px-4 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 hover:shadow-md">
                                            {{ $page }}
                                        </button>
                                    @endif
                                @endfor

                                @if($end < $lastPage)
                                    <span class="px-2 text-gray-500 dark:text-gray-400">...</span>
                                    <button wire:click="gotoPage({{ $lastPage }})" 
                                        class="pagination-button px-4 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 hover:shadow-md">
                                        {{ $lastPage }}
                                    </button>
                                @endif
                            </div>

                            {{-- Bouton Suivant --}}
                            @if($payments->hasMorePages())
                                <button wire:click="nextPage" 
                                    class="pagination-button px-4 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 hover:shadow-md flex items-center gap-2">
                                    <span class="hidden sm:inline">Suivant</span>
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            @else
                                <button disabled 
                                    class="pagination-button px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed flex items-center gap-2">
                                    <span class="hidden sm:inline">Suivant</span>
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            @endif

                            {{-- Bouton Dernière page --}}
                            @if($payments->hasMorePages())
                                <button wire:click="gotoPage({{ $payments->lastPage() }})" 
                                    class="pagination-button px-3 py-2 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 hover:shadow-md">
                                    <i class="bi bi-chevron-double-right"></i>
                                </button>
                            @else
                                <button disabled 
                                    class="pagination-button px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                    <i class="bi bi-chevron-double-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Nouveau Paiement -->
    @if($showPaymentModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fadeIn">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden animate-slideUp">
                <!-- En-tête du modal -->
                <div class="sticky top-0 z-10 px-6 py-5 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-plus-circle-fill text-white text-lg"></i>
                        </div>
                        Nouveau Paiement
                    </h3>
                    <button wire:click="closePaymentModal" 
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl p-2.5 transition-all">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Partie gauche : Formulaire -->
                        <div class="space-y-6">
                            <!-- Recherche élève -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="bi bi-search mr-1"></i>
                                    Rechercher un élève
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                        wire:model.live.debounce.500ms="studentSearch"
                                        placeholder="Nom de l'élève ou code..."
                                        autocomplete="off"
                                        class="w-full px-4 py-3 pl-10 pr-20 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    
                                    <!-- Zone de droite : Bouton effacer et indicateur de chargement -->
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
                                        <!-- Indicateur de chargement pendant la frappe -->
                                        <div wire:loading wire:target="studentSearch">
                                            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- Bouton effacer (visible seulement si du texte) -->
                                        @if(strlen($studentSearch) > 0)
                                            <button 
                                                type="button"
                                                wire:click="$set('studentSearch', '')"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 hover:bg-gray-100 dark:hover:bg-gray-600 rounded"
                                                title="Effacer la recherche">
                                                <i class="bi bi-x-circle text-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <!-- Dropdown résultats recherche -->
                                    @if($showSearchDropdown && count($searchResults) > 0)
                                        <div class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-700 rounded-xl shadow-xl border border-gray-200 dark:border-gray-600 max-h-64 overflow-y-auto animate-fadeIn">
                                            @foreach($searchResults as $result)
                                                <button type="button"
                                                    wire:click="selectStudent({{ $result['id'] }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="selectStudent"
                                                    class="search-dropdown-item w-full px-4 py-3 text-left hover:bg-blue-50 dark:hover:bg-gray-600 active:bg-blue-100 dark:active:bg-gray-500 transition-all duration-150 border-b border-gray-100 dark:border-gray-600 last:border-0 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold shadow flex-shrink-0">
                                                            {{ substr($result['name'], 0, 1) }}
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="font-semibold text-gray-900 dark:text-white truncate">{{ $result['name'] }}</div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                                <i class="bi bi-hash"></i> {{ $result['code'] }} • <i class="bi bi-diagram-3"></i> {{ $result['class_room'] }}
                                                            </div>
                                                        </div>
                                                        <!-- Indicateur de chargement lors de la sélection -->
                                                        <div wire:loading wire:target="selectStudent" class="flex-shrink-0">
                                                            <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </div>
                                                        <i class="bi bi-arrow-right text-gray-400 dark:text-gray-500 flex-shrink-0" wire:loading.remove wire:target="selectStudent"></i>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <!-- Message si aucun résultat -->
                                    @if($showSearchDropdown && count($searchResults) === 0 && strlen($studentSearch) >= 2 && !$isSearching)
                                        <div class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-700 rounded-xl shadow-xl border border-gray-200 dark:border-gray-600 p-4 text-center animate-fadeIn">
                                            <i class="bi bi-search text-gray-400 text-2xl mb-2"></i>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun élève trouvé</p>
                                        </div>
                                    @endif
                                </div>
                                @error('selectedRegistrationId')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                @error('search')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Élève sélectionné -->
                            @if($selectedStudent)
                                <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 rounded-xl p-4 border-2 border-blue-200 dark:border-blue-500">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                            {{ substr($selectedStudent['name'], 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-lg text-gray-900 dark:text-white">{{ $selectedStudent['name'] }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $selectedStudent['code'] }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-1">
                                                <i class="bi bi-diagram-3"></i>
                                                {{ $selectedStudent['class_room'] }}
                                            </p>
                                        </div>
                                        <button wire:click="resetPaymentForm" 
                                            class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 p-2 rounded-lg">
                                            <i class="bi bi-x-circle text-xl"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Formulaire de paiement -->
                                <div class="space-y-5">
                                    <!-- Catégorie et Mois en grille -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Catégorie de frais -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-tag mr-1"></i>
                                                Catégorie de frais
                                            </label>
                                            <select wire:model.live="categoryFeeId"
                                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all">
                                                <option value="">Sélectionner...</option>
                                                @foreach($categoryFees as $fee)
                                                    <option value="{{ $fee->id }}">{{ $fee->name }} ({{ $fee->currency }})</option>
                                                @endforeach
                                            </select>
                                            @error('categoryFeeId')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Mois -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-month mr-1"></i>
                                                Mois
                                            </label>
                                            <select wire:model="selectedMonth"
                                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-all">
                                                <option value="">Sélectionner...</option>
                                                @foreach($schoolMonths as $month)
                                                    <option value="{{ $month['number'] }}">{{ $month['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedMonth')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Statut du paiement -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" 
                                                wire:model="isPaid"
                                                id="isPaid"
                                                class="w-5 h-5 text-green-600 rounded focus:ring-green-500 focus:ring-offset-2">
                                            <label for="isPaid" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer flex items-center gap-2">
                                                <i class="bi bi-check-circle"></i>
                                                Marquer comme payé immédiatement
                                            </label>
                                        </div>
                                        
                                        <!-- Montant du frais -->
                                        @if($selectedCategoryFeeAmount)
                                            <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 rounded-lg border-2 border-green-200 dark:border-green-800 shadow-sm">
                                                <i class="bi bi-cash-coin text-green-600 dark:text-green-400 text-lg"></i>
                                                <span class="text-base font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($selectedCategoryFeeAmount->amount, 0, ',', ' ') }}
                                                </span>
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                    {{ $selectedCategoryFeeAmount->categoryFee->currency ?? 'FC' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Bouton enregistrer -->
                                    <button wire:click="savePayment" 
                                        wire:loading.attr="disabled"
                                        class="w-full px-6 py-3.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                        <span wire:loading.remove wire:target="savePayment" class="flex items-center gap-2">
                                            <i class="bi bi-check-circle text-lg"></i>
                                            <span>Enregistrer le paiement</span>
                                        </span>
                                        <span wire:loading wire:target="savePayment" class="flex items-center gap-2">
                                            <i class="bi bi-arrow-repeat animate-spin mr-2"></i>
                                            Enregistrement...
                                        </span>
                                    </button>

                                    @error('payment')
                                        <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                            <p class="text-sm text-red-600 dark:text-red-400 flex items-center gap-2">
                                                <i class="bi bi-exclamation-triangle-fill"></i>
                                                {{ $message }}
                                            </p>
                                        </div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <!-- Partie droite : Historique des paiements -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="bi bi-clock-history"></i>
                                Historique des paiements
                            </h4>

                            @if($selectedStudent && count($studentPaymentHistory) > 0)
                                <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                                    @foreach($studentPaymentHistory as $history)
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex-1">
                                                    <div class="font-semibold text-gray-900 dark:text-white">
                                                        {{ $history['category'] ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                        {{ $history['month'] ?? 'N/A' }}
                                                    </div>
                                                </div>
                                                @if($history['is_paid'] ?? false)
                                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-medium rounded-full">
                                                        Payé
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 text-xs font-medium rounded-full">
                                                        En attente
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ number_format($history['amount'] ?? 0, 0, ',', ' ') }} {{ $history['currency'] ?? 'FC' }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $history['date'] ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-12">
                                    <i class="bi bi-inbox text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-center">
                                        @if($selectedStudent)
                                            Aucun historique de paiement
                                        @else
                                            Sélectionnez un élève pour voir l'historique
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Styles pour animations -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }
        
        .animate-slideUp {
            animation: slideUp 0.3s ease-out;
        }
        
        /* Animation douce pour les transitions */
        [wire\:loading] {
            transition: opacity 0.15s ease-in-out;
        }
        
        /* Effet de survol amélioré */
        button:active:not(:disabled) {
            transform: scale(0.98);
        }
        
        /* Transitions fluides pour le dropdown */
        .search-dropdown-item {
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .search-dropdown-item:hover {
            transform: translateX(4px);
        }
        
        /* Animations pagination */
        .pagination-button {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .pagination-button:not(:disabled):hover {
            transform: translateY(-2px);
        }
        
        .pagination-button:not(:disabled):active {
            transform: translateY(0);
        }
        
        /* Effet ripple sur les boutons de pagination */
        .pagination-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }
        
        .pagination-button:not(:disabled):hover::before {
            width: 100%;
            height: 100%;
        }
        
        /* Loading state pour pagination */
        [wire\:loading] .pagination-button {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</div>
