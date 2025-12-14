@props([
    'model' => 'date',
    'label' => 'Date',
    'col' => 4,
    'disabled' => false,
])

<div class="col-md-{{ $col }}">
    <label class="form-label">{{ $label }}</label>
    <input type="date" wire:model.live="{{ $model }}" class="form-control"
        @if ($disabled) disabled @endif>
</div>
