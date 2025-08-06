<div>
    @props(['error' => '', 'model' => null])
    <div x-data="{
        open: false,
        search: '',
        searchLabel: '',
        responsibles: @js($responsibleStudents),
        filtered() {
            if (this.searchLabel === '') return [];
            return this.responsibles.filter(r => r.name.toLowerCase().includes(this.searchLabel.toLowerCase()));
        },
        select(responsible) {
            this.search = responsible.id;
            this.searchLabel = '';
            this.open = false;
            $wire.set('{{ $model }}', responsible.id);
        },
        watchInput() {
            const found = this.responsibles.find(r => r.name === this.searchLabel);
            if (!found) {
                this.search = '';
                //$wire.set('{{ $model }}', 0);
            }
        }
    }" x-init="if ($wire.{{ $model }}) {
        const resp = responsibles.find(r => r.id == $wire.{{ $model }});
        if (resp) {
            search = resp.id;
            searchLabel = resp.name;
        }
    }" class="position-relative">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" x-model="searchLabel" @focus="open = true" @input="open = true; watchInput()"
                @keydown.escape="open = false" class="form-control{{ $error ? ' is-invalid' : '' }}"
                placeholder="Rechercher un responsable..." autocomplete="off">
        </div>
        <input type="hidden" name="{{ $attributes->get('name') }}" x-model="search">
        <div x-show="open && filtered().length" x-cloak class="list-group position-absolute w-100"
            style="z-index: 1000; max-height: 200px; overflow-y: auto;">
            <template x-for="responsible in filtered()" :key="responsible.id">
                <button type="button" class="list-group-item list-group-item-action"
                    @mousedown.prevent="select(responsible)">
                    <span x-text="responsible.name"></span>
                </button>
            </template>
        </div>
        <div x-show="open && searchLabel && filtered().length === 0" x-cloak class="list-group position-absolute w-100">
            <div class="list-group-item text-muted">Aucun responsable trouvé</div>
        </div>
        @error($error)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>
