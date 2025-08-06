<div>
    <form wire:submit='addNewRegistration'>
        <x-widget.data.list-student-select-widget model="student_id" :error="$errors->first('student_id')" name="student_id"
            class="w-100 mb-2" />
        @if (isset($selectedStudent))
            <div class="alert alert-info my-2 mt-2">
                <div class="mb-2">
                    <strong>Nom:</strong> <span class="text-primary">{{ $selectedStudent->name ?? '' }}</span><br>
                    <strong>Genre:</strong>
                    <span class="badge bg-info text-dark">{{ $selectedStudent->gender ?? '' }}</span>
                    <span class="ms-2 text-muted">({{ $selectedStudent->getFormattedAg() ?? '' }})</span><br>
                    <strong>Dernière classe:</strong> <span class="text-secondary">{{ $lastClassRoom }}</span><br>
                </div>
                <strong>Responsable:</strong> {{ $selectedStudent->responsibleStudent->name ?? '' }}
                (Tél:
                {{ $selectedStudent->responsibleStudent->phone . ' | ' ?? '' }}{{ $selectedStudent->responsibleStudent->other_phone ?? '' }})<br>
            </div>
            <div>
                <div class="row g-2 mt-1">
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Option') }}" class="mb-1 fw-semibold" />
                        <x-widget.data.list-option type='text' wire:model.blur='option_id' :error="'option_id'" />
                        <x-errors.validation-error value='option_id' class="mt-1" />
                    </div>
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Classe') }}" class="mb-1 fw-semibold" />
                        <x-widget.data.list-class-room-by-option optionId="{{ $selectedOption }}" type='text'
                            wire:model.blur='class_room_id' :error="'class_room_id'" />
                        <x-errors.validation-error value='class_room_id' class="mt-1" />
                    </div>
                </div>
                <div class="row g-2 mt-1">
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Type inscription') }}" class="mb-1 fw-semibold" />
                        <x-widget.data.list-registration-fee-by-option optionId="{{ $selectedOption }}"
                            isOld="{{ $isOldSelected }}" type='text' wire:model.blur='registration_fee_id'
                            :error="'registration_fee_id'" />
                        <x-errors.validation-error value='registration_fee_id' class="mt-1" />
                    </div>
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Date inscription') }}" icon='' class="mb-1 fw-semibold" />
                        <x-form.input type='date' wire:model.blur='created_at' :error="'created_at'" />
                        <x-errors.validation-error value='created_at' class="mt-1" />
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center mt-3">
                    <x-form.app-button type='submit' class="btn-main mt-3" icon='bi bi-check' textButton='Se Connecter'
                        tooltipText='Se Connecter' />
                </div>
            </div>
        @endif
    </form>
</div>
