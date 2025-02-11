<div>
    <x-modal.build-modal-fixed idModal='form-edit-payment' size='md' headerLabel="MODIFIER FRAIS"
        headerLabelIcon='bi bi-arrow-left-right'>
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='save' />
            <x-widget.loading-circular-md wire:loading wire:target='getRegistration' />
        </div>
        @if ($payment != null)
            <div class="card p-2">
                <div>
                    <span class="fw-bold">Nom:</span>
                    <span>{{ $payment->registration->student->name }}</span>
                </div>
                <div>
                    <span class="fw-bold">Classe:</span>
                    <span>{{ $payment->registration->classRoom->getOriginalClassRoomName() }}</span>
                </div>
            </div>
            <form wire:submit='update'>
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
                    <select id="my-select" class="form-control" wire:model.live='scolar_fee_id'>
                        <option value="">Choisir...</option>
                        @foreach ($scolarFees as $scolarFee)
                            <option class="text-uppercase" value="{{ $scolarFee->id }}">{{ $scolarFee->name }}</option>
                        @endforeach
                    </select>
                    <x-errors.validation-error value='scolar_fee_id' />
                </div>
                <div class="">
                    <x-form.label value="{{ __('Date paiement') }}" />
                    <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" />
                    <x-errors.validation-error value='form.created_at' />
                </div>
                <div class="mt-4">
                    <x-form.app-button type='submit' textButton="Modifier" icon="bi bi-arrow-left-righ"
                        class="btn-primary" />
                </div>
            </form>
        @endif
    </x-modal.build-modal-fixed>
</div>
