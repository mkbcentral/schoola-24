<div>
    <div class="card">
        <div class="card-header">
            <h4>
                <i class="{{ $multiAppLink == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"></i>
                {{ $multiAppLink == null ? 'CREATION MULTI MENU' : 'MODIFICATION MULTI MENU' }}
            </h4>

        </div>
        <div class="card-body">
            <form wire:submit='handlerSubmit'>
                <div>
                    <x-form.label value="{{ __('Description') }}" class="fw-bold" />
                    <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                    <x-errors.validation-error value='form.name' />
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('Icon') }}" class="fw-bold" />
                    <x-form.input type='text' wire:model.blur='form.icon' :error="'form.icon'" />
                    <x-errors.validation-error value='form.icon' />
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        @if ($multiAppLink != null)
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
