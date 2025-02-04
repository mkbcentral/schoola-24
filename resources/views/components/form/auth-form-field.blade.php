@props(['disabled' => false, 'error' => ''])
<input {{ $disabled ? 'disabled' : '' }} {{ $attributes }} class="form-control @error($error) is-invalid @enderror">
