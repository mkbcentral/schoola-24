<div wire:ignore.self>
    <div class="card shadow-sm">
        <div class="card-body">
            <h3>Changer le mot de passe</h3>
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading wire:target='update' />
            </div>
            <form wire:submit='updatePassword'>
                <div class="row">
                    <div class="mt-2">
                        <x-form.label value="{{ __('Ancien mot de passe') }}" />
                        <x-form.input type='password' wire:model.blur='old_password' icon='bi bi-key-fill'
                            :error="'old_password'" />
                        <x-errors.validation-error value='old_password' />
                    </div>
                    <div class="mt-2">
                        <x-form.label value="{{ __('Mot de passe actuel') }}" />
                        <x-form.input type='password' wire:model.blur='current_password' icon='bi bi-key-fill'
                            :error="'current_password'" />
                        <x-errors.validation-error value='current_password' />
                    </div>
                    <div class="mt-2">
                        <x-form.label value="{{ __('Confirmer le mot de passe') }}" />
                        <x-form.input type='password' wire:model.blur='confirm_password' icon='bi bi-key-fill'
                            :error="'confirm_password'" />
                        <x-errors.validation-error value='confirm_password' />
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
