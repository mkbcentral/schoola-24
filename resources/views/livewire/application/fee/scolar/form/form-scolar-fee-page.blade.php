<div>
    <x-modal.build-modal-fixed idModal='form-scolar-fee' size='md'
        headerLabel="
            {{ $scolarFeeSelected == null ? 'CREATION' : 'EDITION' }}
             FRAIS SCOLAIRE
        "
        headerLabelIcon="{{ $scolarFeeSelected == null ? 'bi bi-wallet' : 'bi bi-pencil-fill' }} ">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-bold">Type frais: {{ $categoryFeeSelected?->name }}</h5>
            </div>
        </div>
        <form wire:submit='handlerSubmit'>
            <div class="row">
                <div class="col">
                    <x-form.label value="{{ __('Nom frais') }}" class="fw-bold" />
                    <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                    <x-errors.validation-error value='form.name' />
                </div>
                <div class="col">
                    <x-form.label value="{{ __('Montant frais') }}" class="fw-bold" />
                    <x-form.input type='text' wire:model.blur='form.amount' :error="'form.amount'" />
                    <x-errors.validation-error value='form.amount' />
                </div>
            </div>

            <div class="row mt-2">
                <div class="col">
                    <x-form.label value="{{ __('Option') }}" class="fw-bold" />
                    <x-widget.data.list-option type='text' wire:model.live='option_filter' :error="'option_filter'" />
                    <x-errors.validation-error value='option_filter' />
                </div>
                <div class="col">
                    <x-form.label value="{{ __('Classe') }}" class="fw-bold" />
                    <x-widget.data.list-class-room-by-option type='text' optionId='{{ $selectedOption }}'
                        wire:model.blur='form.class_room_id' :error="'form.class_room_id'" />
                    <x-errors.validation-error value='form.class_room_id' />
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                @if ($scolarFeeSelected != null)
                    <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                        class="btn-danger" />
                @endif
                <x-form.app-button type='submit' textButton="Sauvegarder"
                    icon="{{ $scolarFeeSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                    class="app-btn" />
            </div>
        </form>

    </x-modal.build-modal-fixed>
</div>
