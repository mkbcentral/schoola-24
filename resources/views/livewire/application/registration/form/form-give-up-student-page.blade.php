<div>
    <x-modal.build-modal-fixed idModal='form-give-up-student' size='md' headerLabel="MARQUER ABANDON"
        headerLabelIcon="bi bi-journal-x">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
        </div>
        @if ($registration)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nom: {{ $registration->student->name }}</h5>
                </div>
            </div>
            <form wire:submit='makeGiveUpStudent'>
                <div class="mt-2">
                    <x-form.label value="{{ __('Mois abandon') }}" />
                    <x-widget.list-month-fr wire:model.live='month' :error="'month'" />
                    <x-errors.validation-error value='month' />
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <x-form.app-button type='submit' textButton="Marquer abandon" icon="bi bi-journal-x"
                        class="app-btn" />
                </div>
            </form>
        @endif


    </x-modal.build-modal-fixed>
</div>
