@props([
    'modern' => false,
])

<div wire:loading.remove class="table-responsive {{ $modern ? 'modern-table-wrapper' : '' }}">
    <table {{ $attributes->merge(['class' => 'table table-hover align-middle ' . ($modern ? 'modern-table' : 'table-striped')]) }}>
        {{ $slot }}
    </table>
</div>
