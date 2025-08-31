<div>
    <x-modal.build-modal-fixed idModal='form-other-expense-fee' size='md'
        headerLabel="{{ $otherExpense == null ? 'CREATION AUTRES DEPENSES' : 'MODIFICATION AUTRES DEPENSES' }}"
        headerLabelIcon="{{ $otherExpense == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <form wire:submit='handlerSubmit'>
            <div class="mb-2">
                <x-form.label value="{{ __('Dévise') }}" class="fw-bold" />
                <x-widget.currency-widget wire:model='form.currency' :error="'form.currency'" />
                <x-errors.validation-error value='form.currency' />
            </div>
            <div class="mb-2">
                <x-form.label value="{{ __('Description') }}" class="fw-bold" />
                <x-form.input type='text' wire:model='form.description' :error="'form.description'" />
                <x-errors.validation-error value='form.description' />
            </div>
            <div class="row g-2">
                <div class="col-12 col-md-6 mb-2">
                    <x-form.label value="{{ __('Montant dépense') }}" class="fw-bold" />
                    <x-form.input type='text' wire:model='form.amount' :error="'form.amount'" />
                    <x-errors.validation-error value='form.amount' />
                </div>
                <div class="col-12 col-md-6 mb-2">
                    <x-form.label value="{{ __('Mois') }}" class="fw-bold" />
                    <x-widget.list-month-fr wire:model='form.month' :error="'form.month'" />
                    <x-errors.validation-error value='form.month' />
                </div>
            </div>
            <div class="row g-2">
                <div class="col-12 col-md-6 mb-2">
                    <x-form.label value="{{ __('Source dépense') }}" class="fw-bold" />
                    <x-widget.data.list-other-source-expense type='text' wire:model='form.other_source_expense_id'
                        :error="'form.other_source_expense_id'" />
                    <x-errors.validation-error value='form.other_source_expense_id' />
                </div>
                <div class="col-12 col-md-6 mb-2">
                    <x-form.label value="{{ __('Categorie dépense') }}" class="fw-bold" />
                    <x-widget.data.list-category-expense type='text' wire:model='form.category_expense_id'
                        :error="'form.category_expense_id'" />
                    <x-errors.validation-error value='form.category_expense_id' />
                </div>
            </div>
            <div class="mb-2">
                <x-form.label value="{{ __('Date dépot') }}" class="fw-bold" />
                <x-form.input type='date' wire:model='form.created_at' :error="'form.created_at'" />
                <x-errors.validation-error value='form.created_at' />
            </div>
            <div class="mt-4 d-flex flex-column flex-md-row justify-content-between gap-2">
                <div>
                    @if ($otherExpense != null)
                        <x-form.app-button type='button' textButton="Annuler" icon="bi bi-x-lg"
                            wire:click='cancelUpdate' class="btn-danger w-100" />
                    @endif
                </div>
                <x-form.app-button type='submit' textButton="Sauvegarder" icon="bi bi-arrow-left-right"
                    class="btn-primary w-100" />
            </div>
        </form>
    </x-modal.build-modal-fixed>
</div>
