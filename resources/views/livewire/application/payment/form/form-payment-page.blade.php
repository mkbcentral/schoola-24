<div>
    @php
        $months = collect(App\Domain\Helpers\DateFormatHelper::getSchoolFrMonths())
            ->reject(fn($month) => in_array(strtoupper($month['name']), ['JUILLET', 'AOUT']))
            ->values()
            ->all();
        $paymentStatus = false;
    @endphp
    <x-modal.build-modal-fixed idModal='form-payment' size='lg' headerLabel="PAYEMENT FRAIS"
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
                    <form wire:submit.prevent='save'>
                        <div class="">
                            <x-form.label value="{{ __('Mois') }}" class="me-2" />
                            <x-widget.list-month-fr wire:model='form.month' :error="'form.month'" />
                            <x-errors.validation-error value='form.month' />
                        </div>
                        <div class="mt-2">
                            <x-form.label value="{{ __('TYpe de frais') }}" class=" mt-2" />
                            <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='form.category_fee_id'
                                :error="'form.category_fee_id'" />
                            <x-errors.validation-error value='form.category_fee_id' />
                        </div>
                        <div class="mt-2">
                            <x-form.label value="{{ __('Date paiement') }}" />
                            <x-form.input type='date' wire:model='form.created_at' :error="'form.created_at'" />
                            <x-errors.validation-error value='form.created_at' />
                        </div>
                        @if ($scolarFee != null)
                            <h5 class="mt-2 text-danger">Montant:
                                {{ app_format_number($scolarFee->amount, 1) . ' ' . $scolarFee->categoryFee->currency }}
                                </h3>
                        @endif
                        <div class="mt-4">
                            @if ($scolarFee)
                                <x-form.app-button type='submit' textButton="Payer" icon="bi bi-arrow-left-right"
                                    class="btn-primary" />
                            @endif
                        </div>
                    </form>

                </div>
                <div class="col-md-8">
                    <livewire:application.payment.list.list-payment-by-student :registration="$registration" />
                    <div wire:loading.class='d-none'>
                        @if ($lastRegistration)
                            <div class="card mt-2">
                                <div class="card-body">
                                    <h5>Liste dettes du miverval sur de l'année passée</h5>
                                    <ul>
                                        @foreach ($months as $month)
                                            @php
                                                $paymentStatus = $lastRegistration->getStatusPayment(
                                                    $lastRegistration->id,
                                                    1,
                                                    1,
                                                    $month['number'],
                                                );
                                            @endphp
                                            @if ($paymentStatus == false)
                                                <li>{{ $month['name'] }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </x-modal.build-modal-fixed>

</div>
