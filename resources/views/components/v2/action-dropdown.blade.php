@props([
    'label' => 'Actions',
])

<div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
        type="button" 
        data-bs-toggle="dropdown" 
        aria-expanded="false"
        aria-label="{{ $label }}">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        {{ $slot }}
    </ul>
</div>
