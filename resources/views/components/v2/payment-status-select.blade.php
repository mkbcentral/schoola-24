@props(['model' => 'isPaid', 'label' => 'Statut'])

<label class="form-label fw-bold">{{ $label }}</label>
<select wire:model.live="{{ $model }}" class="form-select">
    <option value="">Tous</option>
    <option value="1">Payé</option>
    <option value="0">Non payé</option>
</select>
