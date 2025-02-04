<div class="card">
    <div class="card-header bg-app">
        <h4 class="text-uppercase">
            <i class="{{ $registrationFeeSelected == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"
                aria-hidden="true"></i>
            {{ $registrationFeeSelected == null ? 'Création ' : 'Edition' }} Frais
        </h4>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-center">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
        </div>
        <form wire:submit='handlerSubmit'>
            <div>
                <x-form.label value="{{ __('Dévise') }}" class="fw-bold" />
                <x-widget.currency-widget wire:model.blur='form.currency' :error="'form.currency'" />
                <x-errors.validation-error value='form.currency' />
            </div>
            <div>
                <x-form.label value="{{ __('Nom frais') }}" class="mt-2" />
                <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                <x-errors.validation-error value='form.name' />
            </div>

            <div>
                <x-form.label value="{{ __('Montant frais') }}" class="" />
                <x-form.input type='text' wire:model.blur='form.amount' :error="'form.amount'" />
                <x-errors.validation-error value='form.amount' />
            </div>

            <div>
                <x-form.label value="{{ __('Option') }}" class="" />
                <x-widget.data.list-option type='text' wire:model.blur='form.option_id' :error="'form.option_id'" />
                <x-errors.validation-error value='form.option_id' />
            </div>
            <div>
                <x-form.label value="{{ __('Categorie') }}" class=" mt-2" />
                <x-widget.data.list-cat-registration-fee type='text'
                    wire:model.blur='form.category_registration_fee_id' :error="'form.category_registration_fee_id'" />
                <x-errors.validation-error value='form.category_registration_fee_id' />
            </div>

            <div class="d-flex justify-content-between mt-4">
                @if ($registrationFeeSelected != null)
                    <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                        class="btn-danger" />
                @endif
                <x-form.app-button type='submit' textButton="Sauvegarder"
                    icon="{{ $registrationFeeSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                    class="btn-primary" />
            </div>
        </form>
    </div>
</div>
