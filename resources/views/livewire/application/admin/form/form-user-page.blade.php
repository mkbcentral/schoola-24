<div>
    <x-modal.build-modal-fixed idModal='form-user' bg='bg-indigo' size='lg'
        headerLabel="{{ $user == null ? 'CREATION' : 'EDITION' }}UTILISATEUR"
        headerLabelIcon="  {{ $user == null ? 'bi bi-person-fill-add' : 'bi bi-pencil-fill' }}">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
        </div>
        <form wire:submit='handlerSubmit'>
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('Nom complet') }}" />
                        <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'"
                            style="height: 40px" />
                        <x-errors.validation-error value='form.name' />
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('Pseudo') }}" />
                        <x-form.input type='text' wire:model.blur='form.username' :error="'form.username'"
                            style="height: 40px" />
                        <x-errors.validation-error value='form.username' />
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('N° Tél') }}" />
                        <x-form.input-phone mask="data-mask-phone" wire:model.blur='form.phone' :error="'form.phone'" />
                        <x-errors.validation-error value='form.phone' />
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('Adresse mail') }}" />
                        <x-form.input type='email' wire:model.blur='form.email' :error="'form.email'"
                            style="height: 40px" />
                        <x-errors.validation-error value='form.email' />
                    </div>
                </div>

            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('Role') }}" />
                        <x-widget.data.list-school-role wire:model.blur='form.role_id' :error="'form.role_id'"
                            style="height: 40px" />
                        <x-errors.validation-error value='form.role_id' />
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="mt-4">
                        <x-form.input-check-box idAndFor="activateUser" color="primary" text="Activer le compte"
                            wire:model.blur='form.is_active' :error="'form.is_active'" />
                        <x-errors.validation-error value='form.is_active' />
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <x-form.app-button type='submit' textButton="{{ $user == null ? 'Sauvegarder' : 'Mettre àjour' }}"
                    icon="{{ $user == null ? 'fa fa-save' : 'fa fa-sync' }}" class="btn-primary" />
            </div>
        </form>

    </x-modal.build-modal-fixed>
    @push('js')
        <script type="module">
            //Open  form  modal
            window.addEventListener('close-form-user', e => {
                $('#form-user').modal('hide')
            });
        </script>
    @endpush
</div>
