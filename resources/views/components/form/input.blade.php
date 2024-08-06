@props(['disabled' => false, 'error' => '', 'icon' => ''])

<div class="input-group">
    @if ($icon != '')
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="{{ $icon }}"></i>
            </span>
        </div>
    @endif
    <input {{ $disabled ? 'disabled' : '' }} {{ $attributes }}
        class="form-control @error($error) is-invalid @enderror">
</div>
