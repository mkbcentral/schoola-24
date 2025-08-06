<div>
    <x-modal.build-modal-fixed idModal='form-edit-student' size='lg'
        headerLabel="METTRE A JOUR LE FICHE D'INSCRIPTION" headerLabelIcon='bi bi-pencil-fill'>
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
            <x-widget.loading-circular-md wire:loading wire:target='getResponsibleStudent' />
        </div>
        @if ($student)
            <form wire:submit='update'>
                <div class="row">
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Nom de l\'élève') }}" />
                        <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                        <x-errors.validation-error value='form.name' />
                    </div>
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Date de naissance') }}" />
                        <x-form.input type='date' wire:model.blur='form.date_of_birth' :error="'form.date_of_birth'" />
                        <x-errors.validation-error value='form.date_of_birth' />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Lieu de naissance') }}" />
                        <x-form.input type='text' wire:model.blur='form.place_of_birth' :error="'form.place_of_birth'" />
                        <x-errors.validation-error value='form.place_of_birth' />
                    </div>
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Genre') }}" />
                        <x-widget.gender-widget wire:model.blur='gender' />
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Option') }}" />
                        <x-widget.data.list-option type='text' wire:model.live='form.option_id' :error="'form.option_id'" />
                        <x-errors.validation-error value='form.option_id' />
                    </div>
                    <div class="col-md-6">
                        <x-form.label value="{{ __('Classe') }}" />
                        <x-widget.data.list-class-room-by-option optionId="{{ $selectedOption }}" type='text'
                            wire:model.blur='form.class_room_id' :error="'form.class_room_id'" />
                        <x-errors.validation-error value='form.class_room_id' />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <x-form.label value="{{ __('Type inscription') }}" />
                        <x-widget.data.list-registration-fee-by-option optionId='{{ $selectedOption }}'
                            isOld="{{ $isOldSelected }}" type='text' wire:model.blur='form.registration_fee_id'
                            :error="'form.registration_fee_id'" />
                        <x-errors.validation-error value='form.registration_fee_id' />
                    </div>
                    <div class="col">
                        <x-form.label value="{{ __('Date inscription') }}" />
                        <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" />
                        <x-errors.validation-error value='form.created_at' />
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <x-form.input-check-box idAndFor="isOld" color="primary" text="Pour une réinscription ?"
                            wire:model.live='form.is_old' :error="'form.is_old'" />
                        <x-errors.validation-error value='form.is_old' />
                    </div>
                    <x-form.app-button type='submit' textButton="Modifier" icon="bi bi-pencil-fill"
                        class="btn-primary" />
                </div>
            </form>
        @endif

    </x-modal.build-modal-fixed>
    @push('js')
        <script type="module">
            //close  form  modal
            window.addEventListener('close-form-edit-student', e => {
                //new bootstrap.Modal($('#form-edit-student')).hide();
            });
        </script>
    @endpush
</div>
