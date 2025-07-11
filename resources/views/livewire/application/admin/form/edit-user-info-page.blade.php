<div wire:ignore.self>
    <div class="card">
        <div class="card-header">
            <h3>Votre profile</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading wire:target='update' />
            </div>
            <form wire:submit='update'>
                <div class="row">
                    <div class="mt-2">
                        <x-form.label value="{{ __('Nom utilisateur') }}" />
                        <x-form.input type='text' wire:model.blur='name' icon='bi bi-person-fill' :error="'name'" />
                        <x-errors.validation-error value='name' />
                    </div>
                    <div class="mt-2">
                        <x-form.label value="{{ __('Adresse mail') }}" />
                        <x-form.input type='email' wire:model.blur='email' icon='bi bi-envelope-at-fill'
                            :error="'email'" />
                        <x-errors.validation-error value='email' />
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <x-form.app-button type='submit' textButton="Mettre àjour" icon="bi bi-arrow-repeat"
                        class="btn-primary" />
                </div>
            </form>
        </div>
    </div>
</div>
