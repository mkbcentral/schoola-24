<div class="card card-primary">
    <div class="card-header">
        <h4 class=" text-uppercase">
            <i class="{{ $roleSelected == null ? 'bi bi-plus-circle' : 'bi bi-pencil-fill' }}" aria-hidden="true"></i>
            {{ $roleSelected == null ? 'Création' : 'Edition' }} Role
        </h4>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target />
        </div>
        <form wire:submit='handlerSubmit'>
            <div>
                <x-form.label value="{{ __('Role') }}" class="text-secondary" />
                <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                <x-errors.validation-error value='form.name' />
            </div>
            <div>
                <x-form.input-check-box idAndFor="isFormSchool" color="primary" text="Role pour une école ?"
                    wire:model='form.is_for_school' :error="'form.is_for_school'" />
                <x-errors.validation-error value='form.is_for_school' />
            </div>
            <div class="d-flex justify-content-between mt-4">
                @if ($roleSelected != null)
                    <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                        class="btn-danger" />
                @endif
                <x-form.app-button type='submit' textButton="Sauvegarder"
                    icon="{{ $roleSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                    class="app-btn ml-2" />

            </div>
        </form>
    </div>
</div>
