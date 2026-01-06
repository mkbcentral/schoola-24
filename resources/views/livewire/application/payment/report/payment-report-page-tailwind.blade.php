{{-- Rapports de Paiement - Version Tailwind CSS Moderne --}}
<div class="space-y-4" x-data="{ showEmailModal: false }">
    {{-- Breadcrumb --}}
    <x-navigation.bread-crumb icon='bi bi-file-earmark-text' label="Rapports de Paiement">
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="finance.dashboard" isFirst=true />
        <x-navigation.bread-crumb-item label='Rapports' />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        {{-- En-tête avec Actions --}}
        @if (!empty($report))
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4 mb-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                    <div>
                        <h2 class="text-gray-700 dark:text-gray-200 font-semibold text-lg flex items-center gap-2">
                            <i class="bi bi-file-earmark-text"></i>
                            Rapport {{ $report['label'] ?? '' }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="bi bi-clock"></i> Généré le {{ $report['generated_at'] ?? now()->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <button @click="showEmailModal = true; $wire.loadRecipients()"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-envelope"></i> Envoyer par Email
                        </button>
                        <a href="{{ $this->getExportPdfUrl() }}"
                            class="px-4 py-2 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-download"></i> Télécharger
                        </a>
                        <a href="{{ $this->getPreviewPdfUrl() }}" target="_blank"
                            class="px-4 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 font-medium rounded-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-eye"></i> Aperçu
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Messages Flash --}}
        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-600 dark:border-red-500 rounded-lg p-4 mb-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 dark:text-red-400 text-xl"></i>
                    <div>
                        <p class="text-red-800 dark:text-red-300 font-medium">Erreur</p>
                        <p class="text-red-700 dark:text-red-400 text-sm mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (empty($report) && !session('error'))
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-600 dark:border-yellow-500 rounded-lg p-4 mb-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle-fill text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    <div>
                        <p class="text-yellow-800 dark:text-yellow-300 font-medium">Information</p>
                        <p class="text-yellow-700 dark:text-yellow-400 text-sm mt-1">Aucun paiement trouvé pour la période sélectionnée</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filtres et Résumé --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            {{-- Filtres --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-base mb-4 pb-3 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                    <i class="bi bi-funnel"></i> Filtres & Paramètres
                </h3>

                {{-- Type de Rapport --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="bi bi-file-earmark-bar-graph"></i> Type de Rapport
                    </label>
                    <select wire:model.live="reportType"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="daily">Rapport Journalier</option>
                        <option value="weekly">Rapport Hebdomadaire</option>
                        <option value="monthly">Rapport Mensuel</option>
                        <option value="custom">Période Personnalisée</option>
                        <option value="last_30_days">Derniers 30 Jours</option>
                        <option value="last_12_months">Derniers 12 Mois</option>
                    </select>
                </div>

                {{-- Filtres Dynamiques --}}
                @if ($reportType === 'daily')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="bi bi-calendar-event"></i> Date
                        </label>
                        <input type="date" wire:model.live="selectedDate"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                @elseif ($reportType === 'weekly')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="bi bi-calendar-week"></i> Semaine Contenant la Date
                        </label>
                        <input type="date" wire:model.live="selectedDate"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                @elseif ($reportType === 'monthly')
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-calendar-month"></i> Mois
                            </label>
                            <select wire:model.live="selectedMonth"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">
                                        {{ ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'][$i - 1] }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-calendar"></i> Année
                            </label>
                            <select wire:model.live="selectedYear"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @for ($y = now()->year - 5; $y <= now()->year; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                @elseif ($reportType === 'custom')
                    <div class="space-y-3 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-calendar-event"></i> Date de Début
                            </label>
                            <input type="date" wire:model.live="customStartDate"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-calendar-check"></i> Date de Fin
                            </label>
                            <input type="date" wire:model.live="customEndDate"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button wire:click="updateCustomDates"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="bi bi-arrow-clockwise"></i> Générer Rapport
                        </button>
                    </div>
                @endif
            </div>

            {{-- Résumé Global --}}
            @if (!empty($report))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-base mb-4 pb-3 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                        <i class="bi bi-graph-up-arrow"></i> Résumé Financier
                    </h3>

                    {{-- Total Paiements --}}
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-l-4 border-blue-600 dark:border-blue-500 rounded-lg p-4 mb-4 text-center">
                        <p class="text-gray-600 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                            Total Paiements
                        </p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $report['total_payments'] ?? 0 }}
                        </h3>
                    </div>

                    {{-- Devises avec Totaux --}}
                    <div class="space-y-3">
                        @foreach ($report['total_by_currency'] as $index => $currency)
                            @php
                                $colors = [
                                    'USD' => [
                                        'bg' => 'bg-green-50 dark:bg-green-900/20',
                                        'border' => 'border-green-600 dark:border-green-500',
                                        'text' => 'text-green-700 dark:text-green-300',
                                        'badge' => 'bg-green-600 dark:bg-green-700',
                                    ],
                                    'CDF' => [
                                        'bg' => 'bg-red-50 dark:bg-red-900/20',
                                        'border' => 'border-red-600 dark:border-red-500',
                                        'text' => 'text-red-700 dark:text-red-300',
                                        'badge' => 'bg-red-600 dark:bg-red-700',
                                    ],
                                    'EUR' => [
                                        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                                        'border' => 'border-blue-600 dark:border-blue-500',
                                        'text' => 'text-blue-700 dark:text-blue-300',
                                        'badge' => 'bg-blue-600 dark:bg-blue-700',
                                    ],
                                ];
                                $colorScheme = $colors[$currency['currency']] ?? [
                                    'bg' => 'bg-gray-50 dark:bg-gray-900/20',
                                    'border' => 'border-gray-600 dark:border-gray-500',
                                    'text' => 'text-gray-700 dark:text-gray-300',
                                    'badge' => 'bg-gray-600 dark:bg-gray-700',
                                ];
                            @endphp
                            <div class="{{ $colorScheme['bg'] }} border-l-4 {{ $colorScheme['border'] }} rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                            {{ $currency['currency'] }}
                                        </p>
                                        <p class="{{ $colorScheme['text'] }} text-xl font-bold">
                                            {{ number_format($currency['total'], 0, ',', ' ') }}
                                        </p>
                                    </div>
                                    <span class="{{ $colorScheme['badge'] }} text-white px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $currency['payment_count'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-4 flex items-center gap-1">
                        <i class="bi bi-calendar-range"></i>
                        Période : <strong class="text-gray-900 dark:text-gray-100">{{ $report['label'] }}</strong>
                    </p>
                </div>
            @endif
        </div>

        {{-- Détails par Catégorie --}}
        @if (!empty($report))
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-base flex items-center gap-2">
                        <i class="bi bi-list-ul"></i> Détails par Catégorie
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Catégorie
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    USD
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    CDF
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($report['categories'] as $category)
                                @php
                                    $usdData = collect($category['by_currency'])->firstWhere('currency', 'USD');
                                    $cdfData = collect($category['by_currency'])->firstWhere('currency', 'CDF');
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $category['name'] }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($usdData)
                                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                {{ number_format($usdData['total'], 0, ',', ' ') }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                ({{ $usdData['payment_count'] }})
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($cdfData)
                                            <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                                                {{ number_format($cdfData['total'], 0, ',', ' ') }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                ({{ $cdfData['payment_count'] }})
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-4 mb-3">
                                                <i class="bi bi-inbox text-4xl text-gray-400 dark:text-gray-500"></i>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                                Aucune catégorie trouvée
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Totaux --}}
                <div class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-200 dark:border-gray-600 px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Catégories
                            </p>
                            <h4 class="text-gray-900 dark:text-gray-100 text-2xl font-bold">
                                {{ count($report['categories']) }}
                            </h4>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Total USD
                            </p>
                            <h4 class="text-green-600 dark:text-green-400 text-2xl font-bold">
                                @php
                                    $totalUsd = collect($report['total_by_currency'])->firstWhere('currency', 'USD')['total'] ?? 0;
                                @endphp
                                {{ number_format($totalUsd, 0, ',', ' ') }}
                            </h4>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-600 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                Total CDF
                            </p>
                            <h4 class="text-red-600 dark:text-red-400 text-2xl font-bold">
                                @php
                                    $totalCdf = collect($report['total_by_currency'])->firstWhere('currency', 'CDF')['total'] ?? 0;
                                @endphp
                                {{ number_format($totalCdf, 0, ',', ' ') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rapports Détaillés (Derniers 30 Jours) --}}
            @if ($reportType === 'last_30_days' && !empty($report['daily_reports']))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-base flex items-center gap-2">
                            <i class="bi bi-calendar-day"></i> Détail Quotidien
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Devise
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Paiements
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($report['daily_reports'] as $day)
                                    @foreach ($day['by_currency'] as $currency)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $currency['currency'] === 'USD' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                                    {{ $currency['currency'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ number_format($currency['total'], 0, ',', ' ') }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                    {{ $currency['payment_count'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Rapports Détaillés (Derniers 12 Mois) --}}
            @if ($reportType === 'last_12_months' && !empty($report['monthly_reports']))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-base flex items-center gap-2">
                            <i class="bi bi-calendar-month"></i> Détail Mensuel
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Mois
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Devise
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Paiements
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($report['monthly_reports'] as $month)
                                    @foreach ($month['by_currency'] as $currency)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $month['label'] }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $currency['currency'] === 'USD' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                                    {{ $currency['currency'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ number_format($currency['total'], 0, ',', ' ') }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                    {{ $currency['payment_count'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Pied de Page avec Infos de Génération --}}
            <div class="bg-gray-50 dark:bg-gray-800 border-l-4 border-gray-900 dark:border-gray-600 rounded-lg p-4">
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                    <i class="bi bi-info-circle"></i>
                    <span>Rapport généré le {{ $report['generated_at'] ?? now()->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        @endif

        {{-- Indicateur de chargement --}}
        <x-v2.loading-overlay title="Chargement en cours..." subtitle="Génération du rapport" />

        {{-- Modal d'envoi par email avec Alpine.js --}}
        <div x-show="showEmailModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true"
             @keydown.escape.window="showEmailModal = false">

            {{-- Overlay avec effet de flou --}}
            <div class="fixed inset-0 bg-gradient-to-br from-gray-900/80 via-blue-900/50 to-gray-900/80 backdrop-blur-sm transition-all"
                 x-show="showEmailModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 backdrop-blur-none"
                 x-transition:enter-end="opacity-100 backdrop-blur-sm"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 backdrop-blur-sm"
                 x-transition:leave-end="opacity-0 backdrop-blur-none"
                 @click="showEmailModal = false"></div>

            {{-- Modal Content avec effet glassmorphism --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 dark:border-gray-700/50"
                     x-show="showEmailModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.stop>

                    {{-- Header avec gradient subtil --}}
                    <div class="bg-gradient-to-r from-green-50/80 to-emerald-50/80 dark:from-gray-700/80 dark:to-gray-700/80 backdrop-blur-sm px-6 py-4 border-b border-gray-200/50 dark:border-gray-600/50 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <i class="bi bi-envelope text-green-600 dark:text-green-400"></i>
                                Envoyer le Rapport par Email
                            </h3>
                            <button @click="showEmailModal = false"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                            Le rapport financier sera envoyé en pièce jointe au format PDF aux destinataires suivants :
                        </p>

                        {{-- Liste des destinataires --}}
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-4 max-h-64 overflow-y-auto">
                            @if (empty($recipients))
                                <div class="text-center py-8">
                                    <i class="bi bi-info-circle text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun destinataire chargé</p>
                                </div>
                            @else
                                <div class="space-y-2">
                                    @foreach ($recipients as $index => $recipient)
                                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3 flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="bi bi-person-circle text-green-600 dark:text-green-400"></i>
                                                    <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm">
                                                        {{ $recipient['name'] }}
                                                    </span>
                                                </div>
                                                <div class="text-gray-600 dark:text-gray-400 text-xs flex items-center gap-1 mb-1">
                                                    <i class="bi bi-envelope"></i>
                                                    {{ $recipient['email'] }}
                                                </div>
                                                @if ($recipient['is_default'])
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                        {{ $recipient['role'] }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                        Personnalisé
                                                    </span>
                                                @endif
                                            </div>
                                            <button wire:click="removeRecipient({{ $index }})"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                    @if($isSendingEmail) disabled @endif>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Formulaire d'ajout d'email --}}
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-4">
                            <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                                <i class="bi bi-plus-circle text-green-600 dark:text-green-400"></i>
                                Ajouter un email personnalisé
                            </label>
                            <div class="flex gap-2">
                                <input type="email"
                                       wire:model="newEmail"
                                       placeholder="exemple@email.com"
                                       class="flex-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       @if($isSendingEmail) disabled @endif>
                                <button wire:click="addEmail"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap"
                                        @if($isSendingEmail) disabled @endif>
                                    <i class="bi bi-plus-lg"></i> Ajouter
                                </button>
                            </div>
                            @error('newEmail')
                                <p class="text-red-600 dark:text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info du rapport --}}
                        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-600 dark:border-green-500 rounded-lg p-3">
                            <p class="text-sm text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <i class="bi bi-info-circle text-green-600 dark:text-green-400"></i>
                                <strong>Type de rapport :</strong>
                                <span>
                                    @switch($reportType)
                                        @case('daily') Journalier @break
                                        @case('weekly') Hebdomadaire @break
                                        @case('monthly') Mensuel @break
                                        @case('custom') Personnalisé @break
                                        @case('last_30_days') Derniers 30 jours @break
                                        @case('last_12_months') Derniers 12 mois @break
                                    @endswitch
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Footer avec gradient subtil --}}
                    <div class="bg-gradient-to-r from-gray-50/80 to-gray-100/80 dark:from-gray-700/80 dark:to-gray-700/80 backdrop-blur-sm px-6 py-4 border-t border-gray-200/50 dark:border-gray-600/50 rounded-b-2xl flex justify-end gap-2">
                        <button @click="showEmailModal = false"
                                class="px-4 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 font-medium rounded-lg transition-colors flex items-center gap-2"
                                @if($isSendingEmail) disabled @endif>
                            <i class="bi bi-x-circle"></i> Annuler
                        </button>
                        <button wire:click="sendReportByEmail"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors flex items-center gap-2"
                                @if($isSendingEmail) disabled @endif>
                            @if ($isSendingEmail)
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Envoi en cours...
                            @else
                                <i class="bi bi-send"></i> Envoyer
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
