<div class="card">
    <div class="card-header bg-app">
        <h4 class=" text-uppercase">
            <i class="{{ $categoryFeeSelected == null ? 'bi bi-plus-circle' : 'bi bi-pencil-fill' }}"
                aria-hidden="true"></i>
            {{ $categoryFeeSelected == null ? 'Création' : 'Edition' }} Categorie
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
                <x-form.input-check-box idAndFor="isOld" color="primary" text="C'est un frais de l'état ?"
                    wire:model='form.is_state_fee' :error="'form.is_state_fee'" />
                <x-errors.validation-error value='form.is_state_fee' />
            </div>
            <div>
                <x-form.input-check-box idAndFor="isPaidInInstallment" color="primary" text="A payer par tranche ?"
                    wire:model='form.is_paid_in_installment' :error="'form.is_paid_in_installment'" />
                <x-errors.validation-error value='form.is_paid_in_installment' />
            </div>
            <div>
                <x-form.input-check-box idAndFor="is_paidForRegistration" color="primary"
                    text="A payer à l'inscription ?" wire:model='form.is_paid_for_registration' :error="'form.is_paid_for_registration'" />
                <x-errors.validation-error value='form.is_paid_for_registration' />
            </div>
            <div class="d-flex justify-content-between mt-4">
                @if ($categoryFeeSelected != null)
                    <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                        class="btn-danger" />
                @endif
                <x-form.app-button type='submit' textButton="Sauvegarder"
                    icon="{{ $categoryFeeSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                    class="app-btn ml-2" />

            </div>
        </form>
    </div>
</div>
