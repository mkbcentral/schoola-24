<div>
    <x-modal.build-modal-fixed idModal='form-responsible-student' size='lg'
        headerLabel="
            {{ $responsibleStudent == null ? 'CREATION' : 'EDITION' }}
             RESPONSABLE DE L'ELEVE
        "
        headerLabelIcon="{{ $responsibleStudent == null ? 'bi bi-person-fill-add' : 'bi bi-pencil-fill' }} ">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
        </div>
        <form wire:submit='handlerSubmit'>
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('Nom responsable') }}" />
                        <x-form.input type='text' wire:model.blur='form.name' icon='bi bi-person-fill'
                            :error="'form.name'" />
                        <x-errors.validation-error value='form.name' />
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('N° Téléphone') }}" />
                        <x-form.input-phone mask="data-mask-phone" wire:model.blur='form.phone' :error="'form.phone'" />
                        <x-errors.validation-error value='form.phone' />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('Autre N° Téléphone') }}" />
                        <x-form.input-phone mask="data-mask-phone" wire:model.blur='form.other_phone'
                            :error="'form.other_phone'" />
                        <x-errors.validation-error value='form.other_phone' />
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <x-form.label value="{{ __('Adresse mail') }}" />
                        <x-form.input type='email' wire:model.blur='form.email' icon='bi-envelope-at-fill'
                            :error="'form.email'" />
                        <x-errors.validation-error value='form.email' />
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <x-form.app-button tooltipText="Annuler" type='button' textButton="Annuler" icon="bi bi-x"
                    class="btn-danger me-2" data-bs-dismiss="modal" aria-label="Close" />
                <x-form.app-button type='submit'
                    tooltipText="{{ $responsibleStudent == null ? 'Sauvegarder' : 'Mettre à jour' }}"
                    textButton="{{ $responsibleStudent == null ? 'Sauvegarder' : 'Mettre àjour' }}"
                    icon="{{ $responsibleStudent == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                    class="btn-main" />
            </div>

        </form>

    </x-modal.build-modal-fixed>
    @push('js')
        <script type="module">
            // Open form modal (Bootstrap 5)
            window.addEventListener('close-form-responsible-student', e => {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('form-responsible-student'));
                modal.hide();
            });
        </script>
    @endpush
</div>
