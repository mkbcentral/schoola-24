{{-- Rapports Détaillés - Version Tailwind CSS Moderne --}}
<div class="space-y-4">
    {{-- Configuration du Rapport --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        {{-- En-tête --}}
        <div class="bg-gray-700 dark:bg-gray-900 px-6 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="bg-gray-600 dark:bg-gray-800 rounded-lg p-2">
                    <i class="bi bi-sliders text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-white font-semibold text-lg">Configuration du Rapport</h2>
                    <p class="text-gray-300 text-xs">Sélectionnez vos critères de filtrage</p>
                </div>
            </div>
        </div>

        {{-- Corps --}}
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Type de rapport --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <i class="bi bi-file-earmark-text text-indigo-600 dark:text-indigo-400"></i>
                        Type de Rapport
                    </label>
                    <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            wire:model.live='report_type'>
                        <option value="daily">Date Spécifique</option>
                        <option value="monthly">Mois Spécifique</option>
                        <option value="predefined">Période Prédéfinie</option>
                        <option value="period">Période Personnalisée</option>
                        <option value="payment">Par Paiement</option>
                    </select>
                </div>

                {{-- Filtres conditionnels selon le type --}}
                @if ($report_type === 'daily')
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="bi bi-calendar-day text-blue-600 dark:text-blue-400"></i>
                            Date
                        </label>
                        <input type="date"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               wire:model.live='report_date'>
                    </div>
                @endif

                @if ($report_type === 'monthly')
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="bi bi-calendar-month text-green-600 dark:text-green-400"></i>
                            Mois
                        </label>
                        <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                wire:model.live='report_month'>
                            <option value="">Sélectionner un mois</option>
                            <option value="1">Janvier</option>
                            <option value="2">Février</option>
                            <option value="3">Mars</option>
                            <option value="4">Avril</option>
                            <option value="5">Mai</option>
                            <option value="6">Juin</option>
                            <option value="7">Juillet</option>
                            <option value="8">Août</option>
                            <option value="9">Septembre</option>
                            <option value="10">Octobre</option>
                            <option value="11">Novembre</option>
                            <option value="12">Décembre</option>
                        </select>
                    </div>
                @endif

                @if ($report_type === 'predefined')
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="bi bi-clock-history text-purple-600 dark:text-purple-400"></i>
                            Période
                        </label>
                        <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                wire:model.live='predefined_period'>
                            <option value="week">1 Semaine</option>
                            <option value="2weeks">2 Semaines</option>
                            <option value="1month" selected>1 Mois</option>
                            <option value="3months">3 Mois</option>
                            <option value="6months">6 Mois</option>
                            <option value="9months">9 Mois</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-500 dark:text-gray-400">
                            <i class="bi bi-calendar-range"></i>
                            Période Calculée
                        </label>
                        <input type="text"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 cursor-not-allowed"
                               value="{{ date('d/m/Y', strtotime($report_start_date)) }} - {{ date('d/m/Y', strtotime($report_end_date)) }}"
                               readonly>
                    </div>
                @endif

                @if ($report_type === 'period')
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="bi bi-calendar-check text-teal-600 dark:text-teal-400"></i>
                            Date Début
                        </label>
                        <input type="date"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                               wire:model.live='report_start_date'>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="bi bi-calendar-x text-red-600 dark:text-red-400"></i>
                            Date Fin
                        </label>
                        <input type="date"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                               wire:model.live='report_end_date'>
                    </div>
                @endif

                @if ($report_type === 'payment')
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="bi bi-check-circle text-emerald-600 dark:text-emerald-400"></i>
                            Type Paiement
                        </label>
                        <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                wire:model.live='report_payment_type'>
                            <option value="all">Tous</option>
                            <option value="paid">Payés</option>
                            <option value="unpaid">Non payés</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="bi bi-calendar-month text-blue-600 dark:text-blue-400"></i>
                            Mois (optionnel)
                        </label>
                        <x-widget.list-month-fr wire:model.live='report_month' :error="'report_month'" />
                    </div>
                @endif

                {{-- Catégorie (toujours visible) --}}

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <i class="bi bi-tag text-amber-600 dark:text-amber-400"></i>
                        Catégorie
                    </label>
                    <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                            wire:model.live='report_category_id'>
                        <option value="">Toutes</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Source (toujours visible) --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <i class="bi bi-box-seam text-cyan-600 dark:text-cyan-400"></i>
                        Source
                    </label>
                    <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                            wire:model.live='report_source'>
                        <option value="">Toutes les sources</option>
                        <option value="scolar_fee">Frais Scolaires</option>
                        <option value="other_revenue">Autres Recettes</option>
                        <option value="expense_fee">Dépenses sur Frais</option>
                        <option value="expense_other">Autres Dépenses</option>
                    </select>
                </div>
            </div>

            {{-- Indicateur de chargement --}}
            <div wire:loading
                wire:target="report_type,report_date,report_month,predefined_period,report_start_date,report_end_date,report_payment_type,report_category_id,report_source"
                class="mt-6">
                <div class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 dark:border-blue-400"></div>
                    <span class="text-blue-700 dark:text-blue-300 font-medium">Génération du rapport en cours...</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Résultats du rapport --}}
    @if (!empty($detailedReport))
        {{-- En-tête du rapport --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 flex-shrink-0">
                        <i class="bi bi-file-earmark-bar-graph text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-2">
                            Rapport {{ ucfirst($detailedReport['type'] ?? '') }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-2 text-white/90">
                            @if ($detailedReport['type'] === 'daily')
                                <i class="bi bi-calendar-day"></i>
                                <span>{{ $detailedReport['formatted_date'] ?? '' }}</span>
                            @elseif($detailedReport['type'] === 'monthly')
                                <i class="bi bi-calendar-month"></i>
                                <span>{{ $detailedReport['month_name'] ?? '' }} {{ $detailedReport['year'] ?? '' }}</span>
                            @elseif($detailedReport['type'] === 'period')
                                @if (isset($detailedReport['predefined_label']) && $detailedReport['predefined_label'])
                                    <i class="bi bi-clock-history"></i>
                                    <span class="px-3 py-1 bg-white/25 rounded-full text-sm font-semibold">
                                        {{ $detailedReport['predefined_label'] }}
                                    </span>
                                @else
                                    <i class="bi bi-calendar-range"></i>
                                    <span>Période Personnalisée</span>
                                @endif
                                <span class="mx-2">•</span>
                                <span>Du {{ $detailedReport['formatted_start'] ?? '' }} au {{ $detailedReport['formatted_end'] ?? '' }}</span>
                                <span class="px-3 py-1 bg-white/25 rounded-full text-sm">
                                    {{ $detailedReport['duration_days'] ?? 0 }} jours
                                </span>
                            @elseif($detailedReport['type'] === 'payment')
                                <i class="bi bi-check-circle"></i>
                                <span>Type: {{ ucfirst($detailedReport['payment_type'] ?? 'all') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center gap-2 px-5 py-3 bg-white/25 backdrop-blur-sm rounded-xl text-lg font-bold">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Statistiques principales --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Recettes --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Recettes</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-2">
                        <i class="bi bi-arrow-up-circle text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-green-600 dark:text-green-400">
                        {{ app_format_number($detailedReport['revenues'] ?? 0, 2) }}
                    </h2>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>

            {{-- Dépenses --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Dépenses</p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-2">
                        <i class="bi bi-arrow-down-circle text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-red-600 dark:text-red-400">
                        {{ app_format_number($detailedReport['expenses'] ?? 0, 2) }}
                    </h2>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>

            {{-- Solde Net --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Solde Net</p>
                    </div>
                    <div class="{{ ($detailedReport['balance'] ?? 0) >= 0 ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-orange-100 dark:bg-orange-900/30' }} rounded-lg p-2">
                        <i class="bi bi-wallet2 text-2xl {{ ($detailedReport['balance'] ?? 0) >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold {{ ($detailedReport['balance'] ?? 0) >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}">
                        {{ app_format_number($detailedReport['balance'] ?? 0, 2) }}
                    </h2>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Informations supplémentaires pour rapports de période --}}
        @if ($detailedReport['type'] === 'period' && isset($detailedReport['average_daily_revenue']))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-2">
                            <i class="bi bi-graph-up-arrow text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-medium">Moyenne Journalière</p>
                            <p class="text-gray-900 dark:text-gray-100 text-sm font-semibold">Recettes</p>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ app_format_number($detailedReport['average_daily_revenue'], 2) }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-2">
                            <i class="bi bi-graph-down-arrow text-2xl text-red-600 dark:text-red-400"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-medium">Moyenne Journalière</p>
                            <p class="text-gray-900 dark:text-gray-100 text-sm font-semibold">Dépenses</p>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ app_format_number($detailedReport['average_daily_expense'], 2) }}
                    </div>
                </div>
            </div>
        @endif

        {{-- Informations de paiement --}}
        @if ($detailedReport['type'] === 'payment' && isset($detailedReport['payment_rate']))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-2">
                            <i class="bi bi-check-circle-fill text-xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium text-sm">Payés</p>
                    </div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ app_format_number($detailedReport['paid_revenues'] ?? 0, 2) }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-amber-100 dark:bg-amber-900/30 rounded-lg p-2">
                            <i class="bi bi-clock-history text-xl text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium text-sm">Non Payés</p>
                    </div>
                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                        {{ app_format_number($detailedReport['unpaid_revenues'] ?? 0, 2) }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2">
                            <i class="bi bi-percent text-xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium text-sm">Taux de Paiement</p>
                    </div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $detailedReport['payment_rate'] ?? 0 }}%
                    </div>
                </div>
            </div>
        @endif

        {{-- Ventilation par devise --}}
        @if (isset($detailedReport['details']))
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-base flex items-center gap-2">
                        <i class="bi bi-cash-coin"></i>
                        Ventilation par Devise
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Devise</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Recettes</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Dépenses</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Solde</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-2 font-medium text-gray-900 dark:text-gray-100 text-sm">
                                            <i class="bi bi-currency-dollar text-green-600 dark:text-green-400"></i>
                                            USD
                                        </span>
                                    </td>
                                    <td class="text-right py-3 px-4 font-medium text-green-600 dark:text-green-400">
                                        {{ app_format_number($detailedReport['details']['revenues']['usd'] ?? 0, 2) }}
                                    </td>
                                    <td class="text-right py-4 px-4 font-semibold text-red-600 dark:text-red-400">
                                        {{ app_format_number($detailedReport['details']['expenses']['usd'] ?? 0, 2) }}
                                    </td>
                                    <td class="text-right py-3 px-4 font-semibold text-blue-600 dark:text-blue-400">
                                        {{ app_format_number($detailedReport['details']['balance']['usd'] ?? 0, 2) }}
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-2 font-medium text-gray-900 dark:text-gray-100 text-sm">
                                            <i class="bi bi-cash text-green-600 dark:text-green-400"></i>
                                            CDF
                                        </span>
                                    </td>
                                    <td class="text-right py-3 px-4 font-medium text-green-600 dark:text-green-400">
                                        {{ app_format_number($detailedReport['details']['revenues']['cdf'] ?? 0, 2) }}
                                    </td>
                                    <td class="text-right py-3 px-4 font-medium text-red-600 dark:text-red-400">
                                        {{ app_format_number($detailedReport['details']['expenses']['cdf'] ?? 0, 2) }}
                                    </td>
                                    <td class="text-right py-3 px-4 font-semibold text-blue-600 dark:text-blue-400">
                                        {{ app_format_number($detailedReport['details']['balance']['cdf'] ?? 0, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Ventilation quotidienne (rapport mensuel) --}}
        @if ($detailedReport['type'] === 'monthly' && isset($detailedReport['daily_breakdown']) && count($detailedReport['daily_breakdown']) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-base flex items-center gap-2">
                        <i class="bi bi-calendar-week"></i>
                        Ventilation Quotidienne
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto max-h-96 overflow-y-auto">
                        <table class="w-full">
                            <thead class="sticky top-0 bg-gray-50 dark:bg-gray-700">
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Date</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Recettes</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Dépenses</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Solde</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($detailedReport['daily_breakdown'] as $day)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center justify-center w-7 h-7 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md font-semibold text-xs">
                                                    {{ $day['day'] }}
                                                </span>
                                                <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $day['formatted_date'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-right py-3 px-4 font-medium text-green-600 dark:text-green-400">
                                            {{ app_format_number($day['revenues'], 2) }}
                                        </td>
                                        <td class="text-right py-3 px-4 font-medium text-red-600 dark:text-red-400">
                                            {{ app_format_number($day['expenses'], 2) }}
                                        </td>
                                        <td class="text-right py-3 px-4 font-semibold {{ $day['balance'] >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400' }}">
                                            {{ app_format_number($day['balance'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Ventilation mensuelle (rapport de période) --}}
        @if ($detailedReport['type'] === 'period' && isset($detailedReport['monthly_breakdown']) && count($detailedReport['monthly_breakdown']) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-base flex items-center gap-2">
                        <i class="bi bi-calendar3"></i>
                        Ventilation Mensuelle
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Mois</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Recettes</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Dépenses</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-sm">Solde</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($detailedReport['monthly_breakdown'] as $month)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-900 dark:text-gray-100 text-sm">
                                            {{ $month['month_name'] }} {{ $month['year'] }}
                                        </td>
                                        <td class="text-right py-3 px-4 font-medium text-green-600 dark:text-green-400">
                                            {{ app_format_number($month['revenues'], 2) }}
                                        </td>
                                        <td class="text-right py-3 px-4 font-medium text-red-600 dark:text-red-400">
                                            {{ app_format_number($month['expenses'], 2) }}
                                        </td>
                                        <td class="text-right py-3 px-4 font-semibold {{ $month['balance'] >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400' }}">
                                            {{ app_format_number($month['balance'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    @else
        {{-- État vide --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-12">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                    <i class="bi bi-file-earmark-bar-graph text-4xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    Aucun rapport généré
                </h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto">
                    Sélectionnez les filtres ci-dessus pour générer votre rapport financier détaillé
                </p>
            </div>
        </div>
    @endif
</div>
