<div>
    <x-modal.build-modal-fixed idModal='form-school' size='lg'
        headerLabel="
            {{ $school == null ? 'CREATION' : 'EDITION' }}ECOLE"
        headerLabelIcon="{{ $school == null ? 'bi bi-house-add-fill' : 'bi bi-house-dash-fill' }} ">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
        </div>
        <form wire:submit='handlerSubmit'>
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
                    <x-form.input-phone mask="data-mask-phone" wire:model.blurr='form.phone' icon='bi bi-telephone-fill'
                        :error="'form.phone'" />
                    <x-errors.validation-error value='form.phone' />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <div class="form-group">
                        <x-form.label value="{{ __('Mode') }}" class="" />
                        <select class="form-select" aria-label="School status" wire:model.blurr='form.app_status'>
                            <option selected>Choisir</option>
                            @foreach (App\Enums\SchoolAppEnum::getValues() as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        <x-errors.validation-error value='form.app_status' />
                    </div>
                </div>
                <div class="col form-group mt-2">
                    <label for="my-select">Status</label>
                    <select class="form-select" aria-label="Default select example"
                        wire:model.blurr.blur='form.school_status'>
                        <option selected>Choisir</option>
                        @foreach (App\Enums\SchoolEnum::getValues() as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <x-errors.validation-error value='form.school_status' />
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                @if ($school != null)
                    <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                        class="btn-danger" />
                @endif
                <x-form.app-button type='submit' textButton="Sauvegarder"
                    icon="{{ $school == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                    class="btn-primary ml-2" />

            </div>
        </form>
    </x-modal.build-modal-fixed>

</div>
