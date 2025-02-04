<div>
    <x-modal.build-modal-fixed idModal='form-category-salary' size='md'
        headerLabel=" {{ $categorySalarySelected == null ? 'CREATION CATEGORIE SALAIRE' : 'MODIFICATION CATEGORIE SALAIRE' }}"
        headerLabelIcon="{{ $categorySalarySelected == null ? 'bi bi-plus-circle' : 'bi bi-pencil-fill' }}">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <form wire:submit='handlerSubmit'>
            <div>
                <x-form.label value="{{ __('Categorie') }}" class="text-secondary" />
                <x-form.input type='text' wire:model='form.name' :error="'form.name'" />
                <x-errors.validation-error value='form.name' />
            </div>
            <div class="d-flex justify-content-between mt-4">
                @if ($categorySalarySelected != null)
                    <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                        class="btn-danger" />
                @endif
                <x-form.app-button type='submit' textButton="Sauvegarder"
                    icon="{{ $categorySalarySelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                    class="btn-primary ml-2" />
            </div>
        </form>
    </x-modal.build-modal-fixed>

</div>
