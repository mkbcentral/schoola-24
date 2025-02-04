<div class="card">
    <div class="card-header bg-app">
        <h4 class=" text-uppercase">
            <i class="{{ $categoryRegistrationFeeSelected == null ? 'bi bi-plus-circle' : 'bi bi-pencil-fill' }}"
                aria-hidden="true"></i>
            {{ $categoryRegistrationFeeSelected == null ? 'Création' : 'Edition' }} Categorie
        </h4>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit,cancelUpdate' />
        </div>
        <form wire:submit='handlerSubmit'>
            <div>
                <x-form.label value="{{ __('Categorie') }}" />
                <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                <x-errors.validation-error value='form.name' />
            </div>
            <div>
                <x-form.input-check-box idAndFor="isOld" color="primary" text="C'est pour la réinscription ?"
                    wire:model='form.is_old' :error="'form.is_old'" />
                <x-errors.validation-error value='form.is_old' />
            </div>
            <div class="d-flex justify-content-between mt-4">
                @if ($categoryRegistrationFeeSelected != null)
                    <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                        class="btn-danger" />
                @endif
                <x-form.app-button type='submit' textButton="Sauvegarder"
                    icon="{{ $categoryRegistrationFeeSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                    class="btn-primary ml-2" />

            </div>
        </form>
    </div>
</div>
