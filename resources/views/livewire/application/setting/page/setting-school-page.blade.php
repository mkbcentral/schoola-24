<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Paramètres école</h5>
            <div class="d-flex justify-content-center">
                <livewire:application.admin.school.updatelogo-school-page />
            </div>
            <form wire:submit='update'>
                <div class="d-flex justify-content-center">
                    <x-errors.validation-error value='form.logo' />
                </div>
                <div class="row">
                    <div class="col">
                        <x-form.label value="{{ __('Nom école') }}" class="" />
                        <x-form.input type='text' wire:model.blurr='form.name' icon='bi bi-house-gear-fill'
                            :error="'form.name'" />
                        <x-errors.validation-error value='form.name' />
                    </div>
                    <div class="col">
                        <x-form.label value="{{ __('Type') }}" class="" />
                        <x-form.input type='text' wire:model.blurr='form.type' icon='bi bi-house-gear-fill'
                            :error="'form.type'" />
                        <x-errors.validation-error value='form.type' />
                    </div>

                </div>
                <div class="row mt-2">
                    <div class="col">
                        <x-form.label value="{{ __('Adresse email') }}" class="" />
                        <x-form.input type='text' wire:model.blurr='form.email' icon='bi bi-envelope-at-fill'
                            :error="'form.email'" />
                        <x-errors.validation-error value='form.email' />
                    </div>
                    <div class="col">
                        <x-form.label value="{{ __('Contact') }}" class="" />
                        <x-form.input-phone mask="data-mask-phone" wire:model.blurr='form.phone'
                            icon='bi bi-telephone-fill' :error="'form.phone'" />
                        <x-errors.validation-error value='form.phone' />
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <x-form.app-button type='submit' textButton="Sauvegarder" icon="bi bi-floppy-fill"
                        class="btn-primary ml-2" />
                </div>
            </form>
        </div>
    </div>


</div>
