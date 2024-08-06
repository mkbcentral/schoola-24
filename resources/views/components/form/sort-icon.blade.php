@props(['sortAsc', 'sortBy', 'sortField'])
@if ($sortBy == $sortField)
    @if ($sortAsc)
        <i class="bi bi-sort-alpha-up"></i>
    @else
        <i class="bi bi-sort-alpha-down-alt"></i>
    @endif
@endif
