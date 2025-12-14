@props([
    'field',
    'sortBy',
    'sortAsc',
    'method',
])

<th wire:click="{{ $method }}('{{ $field }}')" class="sortable">
    {{ $slot }}
    @if ($sortBy === $field)
        <i class="bi bi-{{ $sortAsc ? 'sort-up' : 'sort-down' }}"></i>
    @endif
</th>
