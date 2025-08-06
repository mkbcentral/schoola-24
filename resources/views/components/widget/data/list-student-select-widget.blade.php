    @php
        $students = \App\Models\Student::orderBy('name', 'asc')->get();
    @endphp
    @props(['error' => '', 'model' => null])
    <div x-data="{
        open: false,
        search: '',
        searchLabel: '',
        students: @js($students),
        filtered() {
            if (this.searchLabel === '') return [];
            return this.students.filter(s => s.name.toLowerCase().includes(this.searchLabel.toLowerCase()));
        },
        select(student) {
            this.search = student.id;
            this.searchLabel = '';
            this.open = false;
            $wire.set('{{ $model }}', student.id);
        },
        watchInput() {
            const found = this.students.find(s => s.name === this.searchLabel);
            if (!found) {
                this.search = '';
                //$wire.set('{{ $model }}', 0);
            }
        }
    }" x-init="if ($wire.{{ $model }}) {
        const stu = students.find(s => s.id == $wire.{{ $model }});
        if (stu) {
            search = stu.id;
            searchLabel = stu.name;
        }
    }" class="position-relative">
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" x-model="searchLabel" @focus="open = true" @input="open = true; watchInput()"
                @keydown.escape="open = false" class="form-control{{ $error ? ' is-invalid' : '' }}"
                placeholder="Rechercher un élève..." autocomplete="off">
        </div>
        <input type="hidden" name="{{ $attributes->get('name') }}" x-model="search">
        <div x-show="open && filtered().length" x-cloak class="list-group position-absolute w-100"
            style="z-index: 1000; max-height: 200px; overflow-y: auto;">
            <template x-for="student in filtered()" :key="student.id">
                <button type="button" class="list-group-item list-group-item-action"
                    @mousedown.prevent="select(student)"
                    style="background: linear-gradient(90deg, #f8fafc 0%, #e0e7ef 100%); transition: background 0.2s;">
                    <span x-text="student.name"></span>
                </button>
            </template>
        </div>
        <div x-show="open && searchLabel && filtered().length === 0" x-cloak class="list-group position-absolute w-100">
            <div class="list-group-item text-muted">Aucun élève trouvé</div>
        </div>
        @error($error)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
