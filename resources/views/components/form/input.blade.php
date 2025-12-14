@props(['disabled' => false, 'error' => '', 'icon' => ''])

<div class="input-group">
    @if ($icon != '')
        <span class="input-group-text bg-body border-secondary">
            <i class="{{ $icon }}"></i>
        </span>
    @endif
    <input {{ $disabled ? 'disabled' : '' }} {{ $attributes }}
        class="form-control border-secondary @error($error) is-invalid @enderror">
</div>
