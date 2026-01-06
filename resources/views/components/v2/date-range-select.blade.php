@props(['model' => 'dateRange', 'dateRange' => null])

<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
    <i class="bi bi-calendar-range"></i> Période prédéfinie
</label>
<select wire:model.live="{{ $model }}" 
        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
    <option value="">-- Sélectionner --</option>
    <option value="this_week">Cette semaine</option>
    <option value="last_2_weeks">Il y a 2 semaines</option>
    <option value="last_3_weeks">Il y a 3 semaines</option>
    <option value="this_month">Ce mois</option>
    <option value="last_3_months">Il y a 3 mois</option>
    <option value="last_6_months">Il y a 6 mois</option>
    <option value="last_9_months">Il y a 9 mois</option>
</select>
@if ($dateRange)
    <small class="text-gray-500 dark:text-gray-400 block mt-2 text-xs">
        <i class="bi bi-info-circle"></i> Les dates manuelles seront ignorées
    </small>
@endif
