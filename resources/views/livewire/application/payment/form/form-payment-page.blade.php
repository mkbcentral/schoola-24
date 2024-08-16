<div>
    <x-modal.build-modal-fixed idModal='form-payment' size='xl' headerLabel="PAYEMENT FRAIS"
        headerLabelIcon='bi bi-arrow-left-right'>
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        @if ($registration != null)
            <div class="row">
                <div class="col-md-4">
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
                        <div class="mt-2">
                            <x-form.label value="{{ __('Date paiement') }}" />
                            <x-form.input type='date' wire:model.blur='form.created_at' :error="'form.created_at'" />
                            <x-errors.validation-error value='form.created_at' />
                        </div>
                        @if ($scolarFee != null)
                            <h3 class="mt-2">Montant:
                                {{ app_format_number($scolarFee->amount, 1) . ' ' . $scolarFee->categoryFee->currency }}
                            </h3>
                        @endif
                        <div class="mt-4">
                            <x-form.app-button type='submit' textButton="Payer" icon="bi bi-arrow-left-righ"
                                class="app-btn" />
                        </div>
                    </form>

                </div>
                <div class="col-md-8">
                    <div class="d-flex justify-content-center pb-2">
                        <x-widget.loading-circular-md wire:loading wire:target='getRegistration' />
                    </div>
                    <div class="card">
                        <div class="card-header">
                            DETAILS PAIEMET
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm table-hover mt-2">
                                <thead class="table-primary">
                                    <tr class="">
                                        <th class="text-center">N°</th>
                                        <th class="">
                                            DATE PAIEMENT
                                        </th>
                                        <th>TYPE FRAIS</th>
                                        <th class="text-end">MONTANT</th>
                                    </tr>
                                </thead>
                                @if ($registration->payments->isEmpty())
                                    <tr>
                                        <td colspan="7"><x-errors.data-empty /></td>
                                    </tr>
                                @else
                                    <tbody>
                                        @foreach ($registration->payments()->where('is_paid', true)->get() as $index => $payment)
                                            <tr wire:key='{{ $payment->id }}' class=" ">
                                                <td class="text-center">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td>{{ $payment->created_at->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ $payment->scolarFee->name }}
                                                </td>
                                                <td class="text-end"> {{ format_fr_month_name($payment->month) }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-modal.build-modal-fixed>
</div>
