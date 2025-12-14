@props([
    'searchModel',
    'searchPlaceholder',
    'resetMethod',
    'perPageModel',
])

<div class="row mb-3">
    <!-- Search Input -->
    <div class="{{ isset($filters) ? 'col-md-4' : 'col-md-6' }}">
        <x-form.search-input 
            wire:model.live.debounce.300ms="{{ $searchModel }}"
            placeholder="{{ $searchPlaceholder }}" />
    </div>

    <!-- Additional Filters Slot -->
    @isset($filters)
        {{ $filters }}
    @endisset

    <!-- Reset Button -->
    <div class="col-md-2">
        <button wire:click="{{ $resetMethod }}" class="btn btn-outline-secondary w-100">
            <i class="bi bi-arrow-counterclockwise"></i>
            Réinitialiser
        </button>
    </div>

    <!-- Per Page -->
    <div class="col-md-2">
        <select wire:model.live="{{ $perPageModel }}" class="form-select">
            <option value="10">10 par page</option>
            <option value="15">15 par page</option>
            <option value="25">25 par page</option>
            <option value="50">50 par page</option>
        </select>
    </div>
</div>
