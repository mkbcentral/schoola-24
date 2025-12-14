@props(['placeholder' => 'Rechercher...', 'model' => 'search', 'resultCount' => null, 'searchTerm' => null])

<div class="mb-4">
    <div class="position-relative" style="max-width: 500px;">
        <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
        <input type="text" wire:model.live.debounce.300ms="{{ $model }}"
            class="form-control form-control-lg ps-5 pe-5 rounded-pill shadow-sm" placeholder="{{ $placeholder }}"
            style="transition: all 0.3s ease;">
        @if ($searchTerm)
            <button class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted" type="button"
                wire:click="$set('{{ $model }}', '')" style="text-decoration: none; padding-right: 1rem;">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        @endif
    </div>
    @if ($searchTerm && $resultCount !== null)
        <small class="text-muted d-block mt-2 ms-2">
            <i class="bi bi-funnel-fill"></i> {{ $resultCount }} résultat(s) pour "{{ $searchTerm }}"
        </small>
    @endif
</div>
