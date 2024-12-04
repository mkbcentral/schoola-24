<div>
    <x-modal.build-modal-fixed
        idModal='form-bank-deposit' size='md'
        headerLabel="{{ $bankDeposit == null ? 'CREATION DEPOT BANQUE' : 'MODIFICATION DEPOT BANQUE' }}"
        headerLabelIcon="{{ $bankDeposit == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <form wire:submit='handlerSubmit'>
            <div>
                <x-form.label value="{{ __('Dévise') }}" class="fw-bold" />
                <x-widget.currency-widget wire:model='form.currency' :error="'form.currency'" />
                <x-errors.validation-error value='form.currency' />
            </div>
            <div class="mt-2">
                <x-form.label value="{{ __('Description') }}" />
                <x-form.input type='text' wire:model='form.description' :error="'form.description'" />
                <x-errors.validation-error value='form.description' />
            </div>
            <div class="row">
                <div class="mt-2 col">
                    <x-form.label value="{{ __('Montant dépot') }}" />
                    <x-form.input type='text' wire:model='form.amount' :error="'form.amount'" />
                    <x-errors.validation-error value='form.amount' />
                </div>
                <div class="mt-2 col">
                    <x-form.label value="{{ __('Mois') }}" class="me-2" />
                    <x-widget.list-month-fr wire:model='form.month' :error="'form.month'" />
                    <x-errors.validation-error value='form.month' />
                </div>
            </div>
            <div class="row">
                <div class="mt-2 col">
                    <x-form.label value="{{ __('Source opération') }}" />
                    <x-widget.data.list-cat-scolar-fee wire:model='form.category_fee_id' :error="'form.category_fee_id'" />
                    <x-errors.validation-error value='form.category_fee_id' />
                </div>
                <div class="mt-2 col">
                    <x-form.label value="{{ __('Date dépot') }}" />
                    <x-form.input type='date' wire:model='form.created_at' :error="'form.created_at'" />
                    <x-errors.validation-error value='form.created_at' />
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-between">
                <div>
                    @if ($bankDeposit != null)
                        <x-form.app-button type='button' textButton="Annuer" icon="bi bi-x-lg"
                                           wire:click='cancelUpdate' class="btn-danger" />
                    @endif
                </div>
                <x-form.app-button type='submit' textButton="Sauvegarder'" icon="bi bi-arrow-left-right"
                                   class="app-btn" />
            </div>
        </form>
    </x-modal.build-modal-fixed>
</div>
