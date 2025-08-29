    @php
        $registrations = App\Models\Registration::with('student')
            ->join('students', 'students.id', '=', 'registrations.student_id')
            ->join('class_rooms', 'class_rooms.id', '=', 'registrations.class_room_id')
            ->where('school_year_id', App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->orderBy('name', direction: 'ASC')
            ->with(['student', 'registrationFee', 'classRoom', 'schoolYear', 'payments', 'rate'])
            ->select('registrations.*', 'students.name', 'class_rooms.name as nameClasse')
            ->get();

    @endphp
    @props(['error' => '', 'model' => null])
    <div x-data="{
        open: false,
        search: '',
        searchLabel: '',
        registrations: @js($registrations),
        filtered() {
            if (this.searchLabel === '') return [];
            return this.registrations.filter(s => s.name.toLowerCase().includes(this.searchLabel.toLowerCase()));
        },
        select(registration) {
            this.search = registration.id;
            this.searchLabel = '';
            this.open = false;
            $wire.set('{{ $model }}', registration.id);
        },
        watchInput() {
            const found = this.registrations.find(s => s.name === this.searchLabel);
            if (!found) {
                this.search = '';
                //$wire.set('{{ $model }}', 0);
            }
        }
    }" x-init="if ($wire.{{ $model }}) {
        const reg = registrations.find(s => s.id == $wire.{{ $model }});
        if (reg) {
            search = reg.id;
            searchLabel = reg.student.name;
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
            <template x-for="registration in filtered()" :key="registration.id">
                <button type="button" class="list-group-item list-group-item-action"
                    @mousedown.prevent="select(registration)"
                    style="background: linear-gradient(90deg, #f8fafc 0%, #e0e7ef 100%); transition: background 0.2s;">
                    <span x-text="registration.student.name"></span>
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
