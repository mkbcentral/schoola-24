<div>
    <div class="card">
        <div class="card-header">
            <h4>CREATION DEPOT BANQUE</h4>
        </div>
        <div class="card-body">
            <form wire:submit='handlerSubmit'>
                <div>
                    <x-form.label value="{{ __('Dévise') }}" class="fw-bold" />
                    <x-widget.currency-widget wire:model.blur='form.currency' :error="'form.currency'" />
                    <x-errors.validation-error value='form.currency' />
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('Description') }}" />
                    <x-form.input type='text' wire:model.blur='form.description' :error="'form.description'" />
                    <x-errors.validation-error value='form.description' />
                </div>
                <div class="row">
                    <div class="mt-2 col">
                        <x-form.label value="{{ __('Montant dépot') }}" />
                        <x-form.input type='text' wire:model.blur='form.amount' :error="'form.amount'" />
                        <x-errors.validation-error value='form.amount' />
                    </div>
                    <div class="mt-2 col">
                        <x-form.label value="{{ __('Mois') }}" class="me-2" />
                        <x-widget.list-month-fr wire:model.live='form.month' :error="'form.month'" />
                        <x-errors.validation-error value='form.month' />
                    </div>
                </div>
                <div class="row">
                    <div class="mt-2 col">
                        <x-form.label value="{{ __('Source opération') }}" />
                        <x-widget.data.list-cat-scolar-fee wire:model.blur='form.category_fee_id' :error="'form.category_fee_id'" />
                        <x-errors.validation-error value='form.category_fee_id' />
                    </div>
                    <div class="mt-2 col">
                        <x-form.label value="{{ __('Date dépot') }}" />
                        <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" />
                        <x-errors.validation-error value='form.created_at' />
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end">
                    <x-form.app-button type='submit' textButton="Sauvegarder'" icon="bi bi-arrow-left-righ"
                        class="app-btn" />
                </div>
            </form>
        </div>
    </div>
</div>
