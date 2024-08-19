<div>
    <div class="card">
        <div class="card-header">
            <h4>
                <i class="{{ $salary == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"></i>
                {{ $salary == null ? 'CREATION SITUATION SALAIRE' : 'EDITION SITUATION SALAIRE' }}
            </h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading />
            </div>
            <form wire:submit='handlerSubmit'>
                <div class="mt-2 ">
                    <x-form.label value="{{ __('Mois') }}" class="me-2" />
                    <x-widget.list-month-fr wire:model.blurr='form.month' :error="'form.month'" />
                    <x-errors.validation-error value='form.month' />
                </div>
                <div class="mt-2 ">
                    <x-form.label value="{{ __('Date dépot') }}" />
                    <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" />
                    <x-errors.validation-error value='form.created_at' />
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        @if ($salary != null)
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
