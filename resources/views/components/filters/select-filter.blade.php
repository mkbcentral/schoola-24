@props([
    'model',
    'label',
    'options' => [],
    'placeholder' => '-- Sélectionner --',
    'col' => 4,
    'required' => false,
    'helpText' => null,
])

<div class="col-md-{{ $col }}">
    <label class="form-label">
        @if ($required)
            <span class="text-danger">*</span>
        @endif
        {{ $label }}
    </label>
    <select wire:model.live="{{ $model }}" class="form-select" @if ($required) required @endif>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $option)
            <option value="{{ is_array($option) ? $option['id'] : $option->id }}">
                {{ is_array($option) ? $option['name'] : $option->name }}
                @if (isset($option['currency']) || (is_object($option) && isset($option->currency)))
                    ({{ is_array($option) ? $option['currency'] : $option->currency }})
                @endif
            </option>
        @endforeach
    </select>
    @if ($helpText)
        <small class="text-danger">{{ $helpText }}</small>
    @endif
</div>
