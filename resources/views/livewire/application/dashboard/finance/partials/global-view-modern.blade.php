{{-- Vue Globale Financière - Version Tailwind CSS Moderne --}}
<div class="space-y-4">
    {{-- Filtres --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 px-6 py-3 border-b border-blue-700 dark:border-blue-800">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 dark:bg-black/20 rounded-lg p-2">
                    <i class="bi bi-funnel text-white"></i>
                </div>
                <h2 class="text-white font-semibold text-lg">Filtres</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Mois --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Mois</label>
                    <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            wire:model.live='month_filter'>
                        <option value="">Tous les mois</option>
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

                {{-- Date spécifique --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Date spécifique</label>
                    <input type="date"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           wire:model.live='date_filter'>
                </div>

                {{-- Catégorie --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Catégorie</label>
                    <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            wire:model.live='category_fee_id_filter'>
                        <option value="">Toutes les catégories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }} ({{ $category->currency }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Bouton de réinitialisation --}}
                <div class="flex items-end">
                    <button class="w-full px-4 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
                            wire:click='resetFilters'>
                        <i class="bi bi-arrow-clockwise"></i>
                        Réinitialiser
                    </button>
                </div>
            </div>

            {{-- Info catégorie sélectionnée --}}
            @if ($report_category_id)
                @php
                    $selectedCategory = collect($categories)->firstWhere('id', (int) $report_category_id);
                @endphp
                @if ($selectedCategory)
                    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-center gap-2 text-blue-700 dark:text-blue-300">
                            <i class="bi bi-info-circle"></i>
                            <span class="text-sm">
                                Affichage des données pour: <strong>{{ $selectedCategory->name }}</strong>
                            </span>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Cartes de statistiques principales --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Recettes Globales --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-md transition-shadow overflow-hidden">
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                            Recettes Globales
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-2">
                        <i class="bi bi-cash-coin text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ app_format_number($total_revenue, 2) }}
                    </h1>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Dépenses Globales --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-md transition-shadow overflow-hidden">
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                            Dépenses Globales
                        </p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-2">
                        <i class="bi bi-wallet2 text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ app_format_number($total_expense, 2) }}
                    </h1>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Solde Net --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow hover:shadow-md transition-shadow overflow-hidden">
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                            Solde Net
                        </p>
                    </div>
                    <div class="{{ $balance >= 0 ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-orange-100 dark:bg-orange-900/30' }} rounded-lg p-2">
                        <i class="bi bi-calculator text-2xl {{ $balance >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold {{ $balance >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}">
                        {{ app_format_number($balance, 2) }}
                    </h1>
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-medium">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>
                <div class="space-y-3">
                    <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight">
                        {{ app_format_number($balance, 2) }}
                    </h1>
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/25 backdrop-blur-sm rounded-xl font-semibold">
                        <i class="bi bi-currency-{{ $currency === 'USD' ? 'dollar' : 'exchange' }}"></i>
                        {{ $currency }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Graphique Évolution mensuelle --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-base flex items-center gap-2">
                    <i class="bi bi-bar-chart-line"></i>
                    Évolution mensuelle ({{ $currency }})
                </h3>
            </div>
            <div class="p-6">
                <div wire:ignore style="height: 300px;">
                    <canvas id="chartMonthly"></canvas>
                </div>
            </div>
        </div>

        {{-- Graphique de comparaison --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-base flex items-center gap-2">
                    <i class="bi bi-bar-chart-fill"></i>
                    Comparaison Annuelle ({{ $currency }})
                </h3>
            </div>
            <div class="p-6">
                <div wire:ignore style="height: 300px;">
                    <canvas id="chartComparison"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau Récapitulatif Mensuel --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
            <h3 class="text-gray-700 dark:text-gray-200 font-semibold text-base flex items-center gap-2">
                <i class="bi bi-table"></i>
                Tableau Récapitulatif Mensuel ({{ $currency }})
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">
                            Mois
                        </th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">
                            Recettes ({{ $currency === 'USD' ? '$' : 'FC' }})
                        </th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">
                            Dépenses ({{ $currency === 'USD' ? '$' : 'FC' }})
                        </th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">
                            Solde ({{ $currency === 'USD' ? '$' : 'FC' }})
                        </th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">
                            Statut
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($chartData['labels'] as $index => $month)
                        @php
                            $revenue = $chartData['revenues'][$index] ?? 0;
                            $expense = $chartData['expenses'][$index] ?? 0;
                            $balance = $chartData['balance'][$index] ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-3 text-center">
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $month }}</span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span class="font-medium text-green-600 dark:text-green-400">
                                    {{ app_format_number($revenue, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span class="font-medium text-red-600 dark:text-red-400">
                                    {{ app_format_number($expense, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span class="font-semibold {{ $balance >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ app_format_number($balance, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if ($balance > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-sm font-semibold">
                                        <i class="bi bi-arrow-up"></i>
                                    </span>
                                @elseif ($balance < 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-sm font-semibold">
                                        <i class="bi bi-arrow-down"></i>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-700 dark:text-gray-300 rounded-full text-sm font-semibold">
                                        -
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-linear-to-r from-gray-100 to-blue-50 dark:from-gray-800 dark:to-gray-800 border-t-2 border-gray-300 dark:border-gray-600">
                    <tr>
                        <td class="px-6 py-4 text-center">
                            <span class="font-extrabold text-gray-900 dark:text-gray-100 text-base uppercase">Total</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-green-600 dark:text-green-400 text-base">
                                {{ app_format_number(array_sum($chartData['revenues']), 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-red-600 dark:text-red-400 text-base">
                                {{ app_format_number(array_sum($chartData['expenses']), 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold {{ array_sum($chartData['balance']) >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400' }} text-base">
                                {{ app_format_number(array_sum($chartData['balance']), 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if (array_sum($chartData['balance']) > 0)
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-full text-sm font-bold shadow-lg">
                                    <i class="bi bi-check-circle"></i>
                                    Positif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-full text-sm font-bold shadow-lg">
                                    <i class="bi bi-x-circle"></i>
                                    Négatif
                                </span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
