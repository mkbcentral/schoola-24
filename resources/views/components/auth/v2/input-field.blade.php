{{-- Composant : Champ de saisie avec icône --}}
@props([
    'label',
    'icon',
    'type' => 'text',
    'name',
    'model',
    'placeholder' => '',
    'autocomplete' => 'off',
    'autofocus' => false,
    'disabled' => false,
    'delay' => '0.1s',
    'error' => null
])

<div class="mb-4 animate-slide-up" style="animation-delay: {{ $delay }};">
    <label for="{{ $name }}" class="form-label fw-semibold text-dark label-text">
        <i class="bi bi-{{ $icon }}-fill me-1"></i>
        {{ $label }}
    </label>
    <div class="input-group has-validation @error($name) is-invalid-group @enderror">
        <span class="input-group-text input-icon @error($name) text-danger @else text-muted @enderror">
            <i class="bi bi-{{ $icon == 'person' ? 'at' : 'key' }}"></i>
        </span>
        <input
            type="{{ $type }}"
            class="form-control input-field @error($name) is-invalid @enderror"
            id="{{ $name }}"
            wire:model.live.debounce.300ms="{{ $model }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            @if($autofocus) autofocus @endif
            @if($disabled) disabled @endif
        >
        {{ $slot }}
    </div>
    @error($name)
        <div class="error-message mt-2">
            <i class="bi bi-exclamation-circle-fill me-1"></i>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>
