@props(['error' => '', 'maleId' => 'genderM', 'femaleId' => 'genderF'])

<div>
    <div class="btn-group w-100" role="group" style="height: 38px;">
        <input type="radio" {{ $attributes->whereStartsWith('wire:model') }} value="M" class="btn-check"
            id="{{ $maleId }}" required>
        <label class="btn btn-outline-primary d-flex align-items-center justify-content-center border-secondary"
            for="{{ $maleId }}" style="height: 38px;">
            <i class="bi bi-gender-male me-1"></i> M
        </label>

        <input type="radio" {{ $attributes->whereStartsWith('wire:model') }} value="F" class="btn-check"
            id="{{ $femaleId }}" required>
        <label class="btn btn-outline-danger d-flex align-items-center justify-content-center border-secondary"
            for="{{ $femaleId }}" style="height: 38px;">
            <i class="bi bi-gender-female me-1"></i> F
        </label>
    </div>
    @error($error)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
