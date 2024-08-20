<div>
    <div class="card">
        <div class="card-header">
            <h4>
                <i class="{{ $otherExpense == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"></i>
                {{ $otherExpense == null ? 'CREATION DEPENSE' : 'MODIFICATION DEPENSE' }}
            </h4>

        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading wire:target='getotherExpense' />
                <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
            </div>
            <form wire:submit='handlerSubmit'>
                <div>
                    <x-form.label value="{{ __('Dévise') }}" class="fw-bold" />
                    <x-widget.currency-widget wire:model.blur='form.currency' :error="'form.currency'" />
                    <x-errors.validation-error value='form.currency' />
                </div>
                <div class="mt-2 ">
                    <x-form.label value="{{ __('Description') }}" class="fw-bold" />
                    <x-form.input type='text' wire:model.blur='form.description' :error="'form.description'" />
                    <x-errors.validation-error value='form.description' />
                </div>
                <div class="row">
                    <div class="mt-2 col">
                        <x-form.label value="{{ __('Montant dépense') }}" class="fw-bold" />
                        <x-form.input type='text' wire:model.blur='form.amount' :error="'form.amount'" />
                        <x-errors.validation-error value='form.amount' />
                    </div>
                    <div class="mt-2 col">
                        <x-form.label value="{{ __('Mois') }}" class="me-2" class="fw-bold" />
                        <x-widget.list-month-fr wire:model.blurr='form.month' :error="'form.month'" />
                        <x-errors.validation-error value='form.month' />
                    </div>
                </div>
                <div class="row">
                    <div class="mt-2 col">
                        <x-form.label value="{{ __('Source dépense') }}" class="fw-bold" />
                        <x-widget.data.list-other-source-expense type='text'
                            wire:model.live='form.other_source_expense_id' :error="'form.other_source_expense_id'" />
                        <x-errors.validation-error value='form.other_source_expense_id' />
                    </div>
                    <div class="mt-2 col">
                        <x-form.label value="{{ __('Categorie dépense') }}" class="me-2" class="fw-bold" />
                        <x-widget.data.list-category-expense type='text' wire:model.blurr='form.category_expense_id'
                            :error="'form.category_expense_id'" />
                        <x-errors.validation-error value='form.category_expense_id' />
                    </div>
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('Date dépot') }}" class="fw-bold" />
                    <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" />
                    <x-errors.validation-error value='form.created_at' />
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        @if ($otherExpense != null)
                            <x-form.app-button type='button' textButton="Annuer" icon="bi bi-x-lg"
                                wire:click='cancelUpdate' class="btn-danger" />
                        @endif
                    </div>
                    <x-form.app-button type='submit' textButton="Sauvegarder'" icon="bi bi-arrow-left-righ"
                        class="app-btn" />
                </div>
            </form>
        </div>
    </div>
</div>