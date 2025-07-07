<div class="container py-4 px-3 rounded shadow bg-white">
    {{-- Card infos responsable sélectionné --}}
    @if ($responsibleStudent)
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                Responsable sélectionné
            </div>
            <div class="card-body">
                <h5 class="card-title mb-2">{{ $responsibleStudent->name }}</h5>
                <p class="mb-1"><strong>Contact :</strong> {{ $responsibleStudent->phone }} |
                    {{ $responsibleStudent->other_phone }} </p>
            </div>
        </div>
    @endif

    <form wire:submit='handlerSubmit' class="mt-4">
        <div class="mb-3">
            <x-form.input-check-box idAndFor="isOld" color="primary" text="Pour une réinscription ?"
                textColor='text-danger' wire:model.live='form.is_old' :error="'form.is_old'" />
            <x-errors.validation-error value='form.is_old' />
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <x-form.label value="{{ __('Nom responsable') }}" class="mb-1 fw-semibold" />
                <x-form.input type='text' wire:model.blur='form.name' icon='bi bi-person-fill' :error="'form.name'"
                    class="form-control" />
                <x-errors.validation-error value='form.name' />
            </div>
            <div class="col-md-6">
                <x-form.label value="{{ __('Date de naissance') }}" class="mb-1 fw-semibold" />
                <x-form.input type='date' wire:model.blur='form.date_of_birth' :error="'form.date_of_birth'"
                    class="form-control" />
                <x-errors.validation-error value='form.date_of_birth' />
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <x-form.label value="{{ __('Lieu de naissance') }}" class="mb-1 fw-semibold" />
                <x-form.input type='text' icon='bi bi-geo-alt-fill' wire:model.blur='form.place_of_birth'
                    :error="'form.place_of_birth'" class="form-control" />
                <x-errors.validation-error value='form.place_of_birth' />
            </div>
            <div class="col-md-6">
                <x-form.label class="mb-2 fw-semibold" value="{{ __('Genre') }}" />
                <x-widget.gender-widget wire:model.blur='gender' />
            </div>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <x-form.label value="{{ __('Option') }}" class="mb-1 fw-semibold" />
                <x-widget.data.list-option type='text' wire:model.live='form.option_id' :error="'form.option_id'"
                    class="form-control" />
                <x-errors.validation-error value='form.option_id' />
            </div>
            <div class="col-md-6">
                <x-form.label value="{{ __('Classe') }}" class="mb-1 fw-semibold" />
                <x-widget.data.list-class-room-by-option optionId="{{ $selectedOption }}" type='text'
                    wire:model.blur='form.class_room_id' :error="'form.class_room_id'" class="form-control" />
                <x-errors.validation-error value='form.class_room_id' />
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <x-form.label value="{{ __('Type inscription') }}" class="mb-1 fw-semibold" />
                <x-widget.data.list-registration-fee-by-option optionId="{{ $selectedOption }}"
                    isOld="{{ $isOldSelected }}" type='text' wire:model.blur='form.registration_fee_id'
                    :error="'form.registration_fee_id'" class="form-control" />
                <x-errors.validation-error value='form.registration_fee_id' />
            </div>

            <div class="col-md-6">
                <x-form.label value="{{ __('Date inscription') }}" class="mb-1 fw-semibold" />
                <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" class="form-control" />
                <x-errors.validation-error value='form.created_at' />
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-4">
            <x-form.app-button type='submit' textButton="Sauvegarder" icon="bi bi-floppy-fill"
                class="btn btn-primary px-4 py-2" />
        </div>
    </form>
</div>
