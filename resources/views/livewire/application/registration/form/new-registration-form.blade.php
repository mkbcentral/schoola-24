<div class="p-4 rounded shadow ">
    <div class="flex-grow-1">
        <x-widget.data.list-resp-select-widget model="responsible_student_id" :error="$errors->first('responsible_student_id')"
            name="responsible_student_id" class="w-100" />
    </div>
    {{-- Card infos responsable sélectionné --}}
    @if ($responsibleStudent)
        <div class="mb-4 mt-2">
            <h5 class="card-title mb-2 text-primary fw-bold">
                <i class="bi bi-person-badge me-2"></i>Responsable
            </h5>
            <h6 class="card-title mb-2">{{ $responsibleStudent->name }}</h6>
            <p class="mb-1">
                <strong>Contact :</strong>
                <i class="bi bi-telephone me-1"></i>{{ $responsibleStudent->phone }}
                @if ($responsibleStudent->other_phone)
                    | <i class="bi bi-telephone-forward me-1"></i>{{ $responsibleStudent->other_phone }}
                @endif
            </p>
        </div>
        <hr>
    @endif

    <form wire:submit='handlerSubmit' class="mt-3">
        <div class="row g-2">
            <div class="col-md-4">
                <x-form.label value="{{ __('Nom complet') }}" class="mb-1 fw-semibold" />
                <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                <x-errors.validation-error value='form.name' class="mt-1" />
            </div>
            <div class="col-md-4">
                <x-form.label value="{{ __('Date de naissance') }}" class="mb-1 fw-semibold" />
                <x-form.input type='date' wire:model.blur='form.date_of_birth' :error="'form.date_of_birth'" />
                <x-errors.validation-error value='form.date_of_birth' class="mt-1" />
            </div>
            <div class="col-md-4">
                <x-form.label value="{{ __('Lieu de naissance') }}" class="mb-1 fw-semibold" />
                <x-form.input type='text' wire:model.blur='form.place_of_birth' :error="'form.place_of_birth'" />
                <x-errors.validation-error value='form.place_of_birth' class="mt-1" />
            </div>
        </div>
        <div class="row g-2 mt-1">
            <div class="col-md-4">
                <x-form.label class="mb-1 fw-semibold" value="{{ __('Genre') }}" />
                <div class="bg-light rounded px-2 py-1">
                    <x-widget.gender-widget wire:model.blur='gender' />
                </div>
            </div>
            <div class="col-md-4">
                <x-form.label value="{{ __('Option') }}" class="mb-1 fw-semibold" />
                <x-widget.data.list-option type='text' wire:model.live='form.option_id' :error="'form.option_id'" />
                <x-errors.validation-error value='form.option_id' class="mt-1" />
            </div>
            <div class="col-md-4">
                <x-form.label value="{{ __('Classe') }}" class="mb-1 fw-semibold" />
                <x-widget.data.list-class-room-by-option optionId="{{ $selectedOption }}" type='text'
                    wire:model.blur='form.class_room_id' :error="'form.class_room_id'" />
                <x-errors.validation-error value='form.class_room_id' class="mt-1" />
            </div>
        </div>
        <div class="row g-2 mt-1">
            <div class="col-md-6">
                <x-form.label value="{{ __('Type inscription') }}" class="mb-1 fw-semibold" />
                <x-widget.data.list-registration-fee-by-option optionId="{{ $selectedOption }}"
                    isOld="{{ $isOldSelected }}" type='text' wire:model.blur='form.registration_fee_id'
                    :error="'form.registration_fee_id'" />
                <x-errors.validation-error value='form.registration_fee_id' class="mt-1" />
            </div>
            <div class="col-md-6">
                <x-form.label value="{{ __('Date inscription') }}" class="mb-1 fw-semibold" />
                <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'"
                    style="min-width: 150px;" />
                <x-errors.validation-error value='form.created_at' class="mt-1" />
            </div>
        </div>
        <div class="d-flex justify-content-end align-items-center mt-3">
            <x-form.app-button type='submit' class="btn-main mt-3" icon='bi bi-check' textButton='Se Connecter'
                tooltipText='Sauvegarder' />
        </div>
    </form>
</div>
