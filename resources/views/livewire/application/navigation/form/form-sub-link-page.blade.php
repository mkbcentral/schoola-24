<div>
    <div class="card">
        <div class="card-header">
            <strong>
                <i class="{{ $subLink == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"></i>
                {{ $subLink == null ? 'CREATION SIMPLE MENU' : 'MODIFICATION SIMPLE MENU' }}
            </strong>

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
                <div class="mt-2">
                    <x-form.label value="{{ __('Route') }}" class="fw-bold" />
                    <x-form.input type='text' wire:model.blur='form.route' :error="'form.route'" />
                    <x-errors.validation-error value='form.route' />
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('Route') }}" class="fw-bold" />

                    <x-widget.data.list-sub-link type='text' wire:model.live='form.multi_app_link_id'
                        :error="'form.multi_app_link_id'" />
                    <x-errors.validation-error value='form.multi_app_link_id' />
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        @if ($subLink != null)
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
