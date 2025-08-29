<div>
    <x-modal.build-modal-fixed idModal='form-payment-regularization' size='xl'
        headerLabel="{{ $paymentRegularization == null ? 'CREATION PAYEMENT' : 'MODIFICATION PAIEMENT' }} "
        headerLabelIcon="{{ $paymentRegularization == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }} ">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
        </div>
        <div class="row">
            <form wire:submit='handlerSubmit'>
                <x-widget.data.list-student-select-widget model="student_id" :error="$errors->first('student_id')" name="student_id"
                    class="w-100 mb-2" />
                <div class="row">
                    <div class="col">
                        <x-form.label value="{{ __('Nom élève') }}" />
                        <x-form.input type='text' disabled wire:model.blur='form.name' :error="'form.name'" />
                        <x-errors.validation-error value='form.name' />
                    </div>
                    <div class="col">
                        <x-form.label value="{{ __('TYpe de frais') }}" />
                        <select id="my-select" class="form-select form-control" wire:model.live='form.category_fee_id'>
                            <option value="0">Choisir...</option>
                            @foreach ($categoryFees as $cat)
                                <option class="text-uppercase" value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        <x-errors.validation-error value='form.category_fee_id' />
                    </div>

                </div>
                <div class="row mt-2">
                    <div class="col">
                        <x-form.label value="{{ __('Mois de regularisation') }}" class="me-2" />
                        <select id="my-select" class="form-select form-control" wire:model.live='form.month'>
                            <option value="">Choisir...</option>
                            @foreach ($listMonths as $month)
                                <option class="text-uppercase" value="{{ $month['number'] }}">{{ $month['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <x-errors.validation-error value='form.month' />
                    </div>
                    <div class="col">
                        <x-form.label value="{{ __('Montant') }}" />
                        <div class="d-flex align-items-center">
                            <x-form.input type='text' wire:model.blur='form.amount' :error="'form.amount'" />
                            <h4>{{ $this->scolarFee?->categoryFee?->currency }}</h4>
                        </div>
                        <x-errors.validation-error value='form.amount' />
                    </div>
                </div>
                <div class="mt-2">
                    <x-form.label value="{{ __('Date paiement') }}" />
                    <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" />
                    <x-errors.validation-error value='form.created_at' />
                </div>
                <div class="d-flex justify-content-end">
                    <div class="mt-4">
                        <x-form.app-button type='submit' textButton="Passer paiement" icon="bi bi-arrow-left-righ"
                            class="btn-primary" />
                    </div>
                </div>
            </form>
        </div>
    </x-modal.build-modal-fixed>
</div>
