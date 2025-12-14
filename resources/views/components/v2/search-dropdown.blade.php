@props(['error' => '', 'items' => [], 'placeholder' => 'Rechercher...', 'emptyMessage' => 'Aucun résultat trouvé'])

<div x-data="{
    open: false,
    search: '',
    searchLabel: '',
    items: @js($items),
    filtered() {
        if (this.searchLabel === '') return [];
        return this.items.filter(item => item.name.toLowerCase().includes(this.searchLabel.toLowerCase()));
    },
    select(item) {
        this.search = item.id;
        this.searchLabel = item.name;
        this.open = false;
        $wire.set('{{ $attributes->wire('model')->value() }}', item.id);
    },
    watchInput() {
        const found = this.items.find(item => item.name === this.searchLabel);
        if (!found) {
            this.search = '';
        }
    }
}" x-init="const wireModel = '{{ $attributes->wire('model')->value() }}';
if ($wire[wireModel]) {
    const found = items.find(item => item.id == $wire[wireModel]);
    if (found) {
        search = found.id;
        searchLabel = found.name;
    }
}" class="position-relative" @click.away="open = false">
    <div class="input-group">
        <span class="input-group-text bg-body border-secondary">
            <i class="bi bi-search"></i>
        </span>
        <input type="text" x-model="searchLabel" @focus="open = true" @input="open = true; watchInput()"
            @keydown.escape="open = false" class="form-control border-secondary @error($error) is-invalid @enderror"
            placeholder="{{ $placeholder }}" autocomplete="off">
    </div>
    <input type="hidden" x-model="search">

    <div x-show="open && filtered().length" x-cloak class="list-group position-absolute w-100 shadow-sm bg-body"
        style="z-index: 1050; max-height: 250px; overflow-y: auto;">
        <template x-for="item in filtered()" :key="item.id">
            <button type="button" class="list-group-item list-group-item-action bg-body border-secondary"
                @mousedown.prevent="select(item)" style="transition: background 0.2s;">
                <span x-text="item.name"></span>
            </button>
        </template>
    </div>

    <div x-show="open && searchLabel && filtered().length === 0" x-cloak
        class="list-group position-absolute w-100 shadow-sm bg-body" style="z-index: 1050;">
        <div class="list-group-item text-muted bg-body border-secondary">{{ $emptyMessage }}</div>
    </div>

    @error($error)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
