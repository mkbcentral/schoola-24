<div>
    <x-modal.build-modal-fixed idModal='form-payment-regularization' size='md'
        headerLabel="{{ $paymentRegularization == null ? 'CREATION PAYEMENT' : 'MODIFICATION PAIEMENT' }} "
        headerLabelIcon="{{ $paymentRegularization == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }} ">
        <div class="row">
            <form wire:submit='handlerSubmit'>
                <div class="row">
                    <div class="col">
                        <x-form.label value="{{ __('Nom élève') }}" />
                        <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                        <x-errors.validation-error value='form.name' />
                    </div>
                    <div class="col">
                        <x-form.label value="{{ __('Mois') }}" class="me-2" />
                        <x-widget.list-month-fr wire:model.live='form.month' :error="'form.month'" />
                        <x-errors.validation-error value='form.month' />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <x-form.label value="{{ __('Montant') }}" />
                        <x-form.input type='text' wire:model.blur='form.amount' :error="'form.amount'" />
                        <x-errors.validation-error value='form.amount' />
                    </div>
                    <div class="col">
                        <x-form.label value="{{ __('TYpe de frais') }}" />
                        <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='form.category_fee_id'
                            :error="'form.category_fee_id'" />
                        <x-errors.validation-error value='form.category_fee_id' />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <x-form.label value="{{ __('Option') }}" class="me-2" />
                        <x-widget.data.list-option wire:model.live='form.option_id' />
                        <x-errors.validation-error value='form.option_id' />
                    </div>
                    <div class="col">
                        <x-form.label value="{{ __('Classe') }}" class="me-2" />
                        <x-widget.data.list-class-room-by-option optionId='{{ $selectedOptionId }}'
                            wire:model.live='form.class_room_id' />
                        <x-errors.validation-error value='form.class_room_id' />
                    </div>
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('Date paiement') }}" />
                    <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" />
                    <x-errors.validation-error value='form.created_at' />
                </div>
                <div class="d-flex justify-content-end">
                    <div class="mt-4">
                        <x-form.app-button type='submit' textButton="Passer paiement" icon="bi bi-arrow-left-righ"
                            class="app-btn" />
                    </div>
                </div>
            </form>
        </div>
    </x-modal.build-modal-fixed>
</div>
