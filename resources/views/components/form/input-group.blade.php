@props([
    'label' => '',
    'icon' => null,
    'error' => null,
    'required' => false,
    'helper' => null,
    'useSlot' => false,
])

<div class="mb-3">
    @if($label)
        <x-form.label :for="$attributes->get('id')" class="fw-semibold">
            @if($icon)
                <i class="bi bi-{{ $icon }} me-1"></i>
            @endif
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </x-form.label>
    @endif
    
    @if($useSlot)
        {{ $slot }}
    @elseif($attributes->get('type') === 'textarea')
        <textarea {{ $attributes->merge(['class' => 'form-control' . ($error ? ' is-invalid' : '')]) }} style="border-radius: 8px;">{{ $slot }}</textarea>
    @else
        <input {{ $attributes->merge(['class' => 'form-control' . ($error ? ' is-invalid' : '')]) }} style="border-radius: 8px;">
    @endif
    
    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
    
    @if(isset($helperSlot))
        <small class="text-muted">{{ $helperSlot }}</small>
    @elseif($helper)
        <small class="text-muted">{{ $helper }}</small>
    @endif
</div>
