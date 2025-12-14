@props(['model' => 'perPage', 'label' => 'Par page', 'options' => [10, 15, 25, 50, 100]])

<label class="form-label fw-bold">{{ $label }}</label>
<select wire:model.live="{{ $model }}" class="form-select">
    @foreach ($options as $option)
        <option value="{{ $option }}">{{ $option }}</option>
    @endforeach
</select>
