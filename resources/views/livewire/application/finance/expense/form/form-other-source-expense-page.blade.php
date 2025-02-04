<div>
    <div class="card">
        <div class="card-header">
            <h4>
                <i class="{{ $otherSourceExpense == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"></i>
                {{ $otherSourceExpense == null ? 'CREATION AUTRE SOURCE' : 'EDITION AUTRE SOURCE' }}
            </h4>

        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading wire:target='getotherSourceExpense' />
                <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
            </div>
            <form wire:submit='handlerSubmit'>

                <div class="mt-2 ">
                    <x-form.label value="{{ __('Nom catégorie') }}" class="fw-bold" />
                    <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                    <x-errors.validation-error value='form.name' />
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        @if ($otherSourceExpense != null)
                            <x-form.app-button type='button' textButton="Annuer" icon="bi bi-x-lg"
                                wire:click='cancelUpdate' class="btn-danger" />
                        @endif
                    </div>
                    <x-form.app-button type='submit' textButton="Sauvegarder'" icon="bi bi-arrow-left-righ"
                        class="btn-primary" />
                </div>
            </form>
        </div>
    </div>
</div>
