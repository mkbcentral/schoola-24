    <div class="card">
        <div class="card-header bg-app">
            <h4 class=" text-uppercase">
                <i class="{{ $classRoomSelected == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"
                    aria-hidden="true"></i>
                {{ $classRoomSelected == null ? 'Création nouvelle classe' : 'Edition classe' }}
            </h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
            </div>
            <form wire:submit='handlerSubmit'>
                <div>
                    <x-form.label value="{{ __('Nom classe') }}" />
                    <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                    <x-errors.validation-error value='form.name' />
                </div>
                <div>
                    <x-form.label value="{{ __('Type section') }}" />
                    <x-widget.data.list-option wire:model.blur='form.option_id' :error="'form.option_id'" />
                    <x-errors.validation-error value='form.option_id' />
                </div>
                <div class="d-flex justify-content-between mt-4">
                    @if ($classRoomSelected != null)
                        <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                            class="btn-danger" />
                    @endif
                    <x-form.app-button type='submit'
                        textButton="{{ $classRoomSelected == null ? 'Sauvegarder' : 'Modifier' }}"
                        icon="{{ $classRoomSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                        class="btn-primary ml-2" />
                </div>
            </form>
        </div>
    </div>
