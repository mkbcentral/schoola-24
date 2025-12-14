@props(['items'])

@if (method_exists($items, 'hasPages') && $items->hasPages())
    <div class="mt-3">
        {{ $items->links() }}
    </div>
@endif
