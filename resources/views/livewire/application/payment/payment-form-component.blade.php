{{-- Formulaire de Paiement - Version Tailwind CSS Moderne --}}
<div>
    <div wire:ignore.self>
        {{-- Message mode édition --}}
        @if ($isEditMode)
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3 mb-4 flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-amber-600 dark:text-amber-400"></i>
                <div>
                    <span class="font-semibold text-amber-700 dark:text-amber-300">Mode édition</span>
                    <span class="text-amber-600 dark:text-amber-400"> - Vous modifiez un paiement existant</span>
                </div>
            </div>
        @endif

        <div>
            @if ($registration)
                <form wire:submit.prevent="save" class="space-y-4">
                    {{-- Catégorie de frais --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="bi bi-tags-fill mr-1"></i>Catégorie de frais
                            <span class="text-red-600">*</span>
                        </label>
                        <select wire:model.live="selectedCategoryFeeId"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Sélectionner une catégorie</option>
                            @foreach ($categoryFees as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }} ({{ $category->currency }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Montant (affiché si frais trouvé) --}}
                    @if ($scolarFee)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-currency-dollar mr-1"></i>Montant
                            </label>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex justify-between items-center">
                                <span class="text-3xl font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($scolarFee->amount, 0, ',', ' ') }}
                                </span>
                                <span class="bg-green-600 dark:bg-green-700 text-white px-3 py-1.5 rounded-md font-semibold">
                                    {{ $scolarFee->categoryFee->currency }}
                                </span>
                            </div>
                        </div>
                    @endif

                    {{-- Mois et Date côte à côte --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-calendar-month mr-1"></i>Mois
                                <span class="text-red-600">*</span>
                            </label>
                            <select wire:model="form.month"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                                <option value="01">Janvier</option>
                                <option value="02">Février</option>
                                <option value="03">Mars</option>
                                <option value="04">Avril</option>
                                <option value="05">Mai</option>
                                <option value="06">Juin</option>
                                <option value="07">Juillet</option>
                                <option value="08">Août</option>
                                <option value="09">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-calendar-event mr-1"></i>Date
                                <span class="text-red-600">*</span>
                            </label>
                            <input type="date" 
                                   wire:model="form.created_at"
                                   class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        </div>
                    </div>

                    {{-- Payer immédiatement --}}
                    <div>
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label for="isPaidSwitch" class="font-medium text-gray-900 dark:text-gray-100 cursor-pointer block">
                                        Payer immédiatement
                                    </label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                        Le paiement sera validé automatiquement
                                    </p>
                                </div>
                                <div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               wire:model="isPaid" 
                                               id="isPaidSwitch"
                                               class="sr-only peer">
                                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Boutons d'action --}}
                    <div class="flex gap-2">
                        @if ($isEditMode)
                            <button type="button" 
                                    wire:click="cancel"
                                    class="flex-1 px-4 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors flex items-center justify-center gap-2">
                                <i class="bi bi-x-circle"></i>Annuler
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>Mettre à jour
                            </button>
                        @else
                            <button type="submit"
                                    class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>Enregistrer le paiement
                            </button>
                        @endif
                    </div>
                </form>
            @else
                {{-- Message si aucun élève sélectionné --}}
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <i class="bi bi-person-plus-fill text-gray-400 dark:text-gray-500 text-4xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Aucun élève sélectionné</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Recherchez et sélectionnez un élève pour commencer</p>
                </div>
            @endif
        </div>
    </div>
</div>
