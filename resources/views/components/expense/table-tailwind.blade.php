@props([
    'expenses' => [],
    'expenseType' => 'fee',
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-0 flex items-center">
            <i class="bi bi-table mr-2 text-blue-600 dark:text-blue-400"></i>
            Liste des {{ $expenseType === 'fee' ? 'Dépenses sur Frais' : 'Autres Dépenses' }}
        </h5>
    </div>
    <div class="p-6">
        @if ($expenses->isEmpty())
            <div class="text-center py-12">
                <i class="bi bi-inbox text-gray-300 dark:text-gray-600 text-7xl"></i>
                <p class="text-gray-500 dark:text-gray-400 mt-4 text-lg">Aucune dépense trouvée</p>
            </div>
        @else
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mois</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catégorie</th>
                                @if ($expenseType === 'fee')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type Frais</th>
                                @else
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Source</th>
                                @endif
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">USD ($)</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">CDF (FC)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($expenses as $expense)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <small class="text-gray-500 dark:text-gray-400 text-sm">
                                            {{ $expense->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $expense->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            {{ format_fr_month_name($expense->month) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 dark:bg-cyan-900/50 text-cyan-800 dark:text-cyan-200">
                                            {{ $expense->categoryExpense->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    @if ($expenseType === 'fee')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                {{ $expense->categoryFee->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                    @else
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                                                {{ $expense->otherSourceExpense->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if ($expense->currency === 'USD')
                                            <strong class="text-green-600 dark:text-green-400 font-semibold">
                                                {{ app_format_number($expense->amount, 1) }}
                                            </strong>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if ($expense->currency === 'CDF')
                                            <strong class="text-blue-600 dark:text-blue-400 font-semibold">
                                                {{ app_format_number($expense->amount, 0) }}
                                            </strong>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2"
                                            wire:key="validation-{{ $expense->id }}">
                                            <div class="flex items-center">
                                                <input class="expense-toggle-switch" type="checkbox"
                                                    role="switch" id="validation-{{ $expense->id }}"
                                                    wire:click="toggleValidation({{ $expense->id }})"
                                                    {{ $expense->is_validated ? 'checked' : '' }}
                                                    wire:loading.attr="disabled"
                                                    wire:target="toggleValidation({{ $expense->id }})">
                                            </div>
                                            <div wire:loading.remove wire:target="toggleValidation({{ $expense->id }})">
                                                @if ($expense->is_validated)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                                                        <i class="bi bi-check-circle mr-1"></i>Validée
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200">
                                                        <i class="bi bi-clock mr-1"></i>En attente
                                                    </span>
                                                @endif
                                            </div>
                                            <div wire:loading wire:target="toggleValidation({{ $expense->id }})">
                                                <span class="inline-block w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if (!$expense->is_validated)
                                            <div class="inline-flex rounded-lg shadow-sm" role="group">
                                                <button class="px-3 py-1.5 text-sm border border-blue-300 dark:border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-l-lg transition-colors" 
                                                    type="button"
                                                    data-bs-toggle="offcanvas" 
                                                    data-bs-target="#expenseFormOffcanvas"
                                                    aria-controls="expenseFormOffcanvas"
                                                    wire:click="openEditModal({{ $expense->id }})" 
                                                    title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="px-3 py-1.5 text-sm border-t border-r border-b border-red-300 dark:border-red-600 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-r-lg transition-colors"
                                                    wire:click="confirmDelete({{ $expense->id }})" 
                                                    title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600">
                                                <i class="bi bi-lock mr-1"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4" wire:loading.remove>
                {{ $expenses->links() }}
            </div>

            <!-- Indicateur de chargement pour la pagination -->
            <div class="text-center py-6" wire:loading>
                <div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Chargement...</p>
            </div>
        @endif
    </div>
</div>
