@props(['model' => 'isPaid', 'label' => 'Statut'])

<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $label }}</label>
<select wire:model.live="{{ $model }}" 
        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
    <option value="">Tous</option>
    <option value="1">Payé</option>
    <option value="0">Non payé</option>
</select>
