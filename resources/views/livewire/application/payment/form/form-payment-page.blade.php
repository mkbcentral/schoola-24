<div>
    <x-modal.build-modal-fixed idModal='form-payment' size='md' headerLabel="PAYEMENT FRAIS"
        headerLabelIcon='bi bi-arrow-left-right'>
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='save' />
            <x-widget.loading-circular-md wire:loading wire:target='getRegistration' />
        </div>
        @if ($registration != null)
            <div class="card p-2">
                <div>
                    <span class="fw-bold">Nom:</span>
                    <span>{{ $registration->student->name }}</span>
                </div>
                <div>
                    <span class="fw-bold">Classe:</span>
                    <span>{{ $registration->classRoom->getOriginalClassRoomName() }}</span>
                </div>
            </div>
            <form wire:submit='save'>
                <div class="">
                    <x-form.label value="{{ __('Mois') }}" class="me-2" />
                    <x-widget.list-month-fr wire:model.live='form.month' :error="'form.month'" />
                    <x-errors.validation-error value='form.month' />
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('TYpe de frais') }}" class=" mt-2" />
                    <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='form.category_fee_id'
                        :error="'form.category_fee_id'" />
                    <x-errors.validation-error value='form.category_fee_id' />
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('Frais') }}" class="me-2" />
                    <x-widget.data.list-fee-by-category selectedCategoryId='{{ $selectedCategoryFeeId }}'
                        classRoomId='{{ $selectedIdClassRoom }}' wire:model.live='form.scolar_fee_id' />
                    <x-errors.validation-error value='form.scolar_fee_id' />
                </div>
                <div class="mt-4">
                    <x-form.app-button type='submit' textButton="Payer" icon="bi bi-arrow-left-righ" class="app-btn" />
                </div>
            </form>
        @endif
    </x-modal.build-modal-fixed>
</div>
