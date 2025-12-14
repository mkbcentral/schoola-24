@props(['paginator'])

@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            Affichage de {{ $paginator->firstItem() ?? 0 }} à {{ $paginator->lastItem() ?? 0 }} sur
            {{ $paginator->total() }} résultats
        </div>
        <div>
            {{ $paginator->links() }}
        </div>
    </div>
@endif
