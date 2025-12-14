@props([
    'title',
    'icon',
    'buttonText',
    'buttonClick',
    'buttonColor' => 'primary',
])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="{{ $icon }} me-2"></i>
            {{ $title }}
        </h5>
        <button wire:click="{{ $buttonClick }}" class="btn btn-{{ $buttonColor }}">
            <i class="bi bi-plus-circle me-1"></i>
            {{ $buttonText }}
        </button>
    </div>

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
