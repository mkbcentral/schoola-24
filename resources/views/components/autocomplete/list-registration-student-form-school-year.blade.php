@php
    $registrations = App\Models\Registration::with([
        'student',
        'registrationFee',
        'classRoom.option',
        'classRoom',
        'schoolYear',
        'payments',
        'rate',
    ])
        ->join('students', 'students.id', '=', 'registrations.student_id')
        ->join('class_rooms', 'class_rooms.id', '=', 'registrations.class_room_id')
        ->join('options', 'options.id', '=', 'class_rooms.option_id')
        ->where('school_year_id', App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
        ->orderBy('students.name', 'ASC')
        ->select(
            'registrations.*',
            'students.name',
            \DB::raw("CONCAT(class_rooms.name, '/', options.name) as nameClasse"),
        )
        ->get();
@endphp

@props(['error' => '', 'model' => null])

<div x-data="{
    open: false,
    search: '',
    searchLabel: '',
    selectedReg: null,
    registrations: @js($registrations),
    filtered() {
        if (this.searchLabel === '') return [];
        return this.registrations.filter(s =>
            s.name.toLowerCase().includes(this.searchLabel.toLowerCase()) ||
            s.nameClasse.toLowerCase().includes(this.searchLabel.toLowerCase())
        ).slice(0, 10);
    },
    select(registration) {
        this.search = registration.id;
        this.searchLabel = registration.name;
        this.selectedReg = registration;
        this.open = false;
        $wire.set('{{ $model }}', registration.id);
    },
    clear() {
        this.search = '';
        this.searchLabel = '';
        this.selectedReg = null;
        this.open = false;
        $wire.set('{{ $model }}', null);
    },
    watchInput() {
        const found = this.registrations.find(s => s.name === this.searchLabel);
        if (!found && this.searchLabel !== '') {
            this.search = '';
        }
    }
}" x-init="if ($wire.{{ $model }}) {
    const reg = registrations.find(s => s.id == $wire.{{ $model }});
    if (reg) {
        search = reg.id;
        searchLabel = reg.name;
        selectedReg = reg;
    }
}" @click.away="open = false"
    class="position-relative student-autocomplete">

    <!-- Input avec design amélioré -->
    <div class="position-relative">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-body border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" x-model="searchLabel" @focus="open = true" @input="open = true; watchInput()"
                @keydown.escape="open = false"
                @keydown.arrow-down.prevent="$refs.dropdown?.querySelector('button')?.focus()"
                class="form-control border-start-0 ps-0{{ $error ? ' is-invalid' : '' }}"
                placeholder="Tapez le nom d'un élève..." autocomplete="off" style="padding-right: 40px;">

            <!-- Bouton clear -->
            <button type="button" x-show="searchLabel" @click="clear()"
                class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted"
                style="z-index: 10; padding: 0.375rem 0.75rem; text-decoration: none;" title="Effacer">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>

        <!-- Badge de sélection -->
        <div x-show="selectedReg && !open" x-cloak class="mt-2">
            <div
                class="d-flex align-items-center gap-2 p-2 rounded bg-primary bg-opacity-10 border border-primary border-opacity-25">
                <i class="bi bi-person-check-fill text-primary"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold text-primary" x-text="selectedReg.name"></div>
                    <small class="text-muted">
                        <i class="bi bi-diagram-3 me-1"></i>
                        <span x-text="selectedReg.nameClasse"></span>
                    </small>
                </div>
                <span x-show="selectedReg.is_old" class="badge bg-secondary">Ancien</span>
                <span x-show="!selectedReg.is_old" class="badge bg-success">Nouveau</span>
                <button type="button" @click="clear()" class="btn btn-sm btn-link text-danger p-0" title="Retirer">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <input type="hidden" name="{{ $attributes->get('name') }}" x-model="search">

        <!-- Dropdown avec résultats -->
        <div x-show="open && filtered().length" x-cloak x-ref="dropdown"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
            class="position-absolute w-100 mt-1 shadow-lg rounded border bg-body"
            style="z-index: 1050; max-height: 320px; overflow-y: auto;">

            <div class="p-2 border-bottom bg-body-secondary">
                <small class="text-muted fw-medium">
                    <i class="bi bi-funnel me-1"></i>
                    <span x-text="filtered().length"></span> résultat(s)
                </small>
            </div>

            <template x-for="(registration, index) in filtered()" :key="registration.id">
                <button type="button" class="list-group-item list-group-item-action border-0 p-3 student-item"
                    @mousedown.prevent="select(registration)" @keydown.enter.prevent="select(registration)"
                    @keydown.escape="open = false" @keydown.arrow-down.prevent="$el.nextElementSibling?.focus()"
                    @keydown.arrow-up.prevent="$el.previousElementSibling?.focus()">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-person-circle text-primary"></i>
                                <span class="fw-semibold" x-text="registration.name"></span>
                            </div>
                            <div class="text-muted small">
                                <i class="bi bi-diagram-3 me-1"></i>
                                <span x-text="registration.nameClasse"></span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <span x-show="registration.is_old" class="badge bg-secondary">Ancien</span>
                            <span x-show="!registration.is_old" class="badge bg-success">Nouveau</span>
                        </div>
                    </div>
                </button>
            </template>
        </div>

        <!-- Message aucun résultat -->
        <div x-show="open && searchLabel && filtered().length === 0" x-cloak x-transition
            class="position-absolute w-100 mt-1 shadow rounded border bg-body" style="z-index: 1050;">
            <div class="p-4 text-center text-muted">
                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                <p class="mb-0 mt-2">Aucun élève trouvé</p>
                <small>Essayez avec un autre nom ou classe</small>
            </div>
        </div>

        @error($error)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('styles')
    <style>
        .student-autocomplete .student-item {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .student-autocomplete .student-item:hover,
        .student-autocomplete .student-item:focus {
            background-color: var(--bs-primary-bg-subtle) !important;
            border-left: 3px solid var(--bs-primary) !important;
            outline: none;
        }

        .student-autocomplete .input-group-text {
            transition: all 0.2s ease;
        }

        .student-autocomplete .form-control:focus~.input-group-text {
            border-color: var(--bs-primary);
        }

        /* Scrollbar personnalisée */
        .student-autocomplete [style*="overflow-y"] {
            scrollbar-width: thin;
            scrollbar-color: var(--bs-secondary) var(--bs-body-bg);
        }

        .student-autocomplete [style*="overflow-y"]::-webkit-scrollbar {
            width: 6px;
        }

        .student-autocomplete [style*="overflow-y"]::-webkit-scrollbar-track {
            background: var(--bs-body-bg);
        }

        .student-autocomplete [style*="overflow-y"]::-webkit-scrollbar-thumb {
            background-color: var(--bs-secondary);
            border-radius: 10px;
        }
    </style>
@endpush
