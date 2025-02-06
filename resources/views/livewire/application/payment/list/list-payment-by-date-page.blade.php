<div wire:poll.45s class="card">
    <div class="card-body">
        <h3 class="text-primary text-uppercase text-end">
            Total: {{ app_format_number($total_payments, 1) }}
            {{ $categoryFeeSelected->currency }}
            ({{ $categoryFeeSelected->name }})
        </h3>
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Date') }}" class="me-2" />
                <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
            </div>
            <x-form.search-input wire:model.live='q' />
            <div class="form-group d-flex align-items-center ms-2">
                <div class="d-flex align-items-center">
                    <label for="my-select">Type Frais</label>
                    <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='category_fee_filter'
                        :error="'category_fee_filter'" />
                </div>
                <x-others.dropdown wire:ignore.self icon="bi bi-printer-fill" class="ms-2">
                    <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer rapport'
                        href="{{ route('print.payment.date', [$date_filter, $category_fee_filter, 0, 0, 0, 0]) }}"
                        target='_blank' />
                    <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Bordereau de versement'
                        href="{{ route('print.payment.slip.date', [$date_filter]) }}" target='_blank' />
                </x-others.dropdown>
            </div>
        </div>

        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">PAIEMENTS JOURNALIERS</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>NOMS </th>
                                <th>CLASSE</th>
                                <th>MOIS</th>
                                <th>MONTANT</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $index => $payment)
                                <tr class="">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2"> {{ $payment->registration->student->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $payment?->registration->classRoom?->getOriginalClassRoomName() }}</td>
                                    <td>{{ $payment->month }}</td>
                                    <td>{{ app_format_number($payment->getAmount(), 1) }}
                                        {{ $payment->scolarFee->categoryFee->currency }}</td>
                                    <td><span
                                            class="badge {{ $payment->is_paid == true ? 'bg-success' : ' bg-warning' }} bg-success">{{ $payment->is_paid == true ? 'SUCCESS' : 'PENDING' }}</span>
                                    </td>
                                    <td>
                                        <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                            class=" btn-sm">
                                            <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer'
                                                data-bs-toggle="modal" data-bs-target="#form-edit-payment"
                                                href="#" wire:click='edit({{ $payment }})'
                                                class="text-primary" />
                                            <x-others.dropdown-link
                                                iconLink="{{ $payment->is_paid == true ? 'bi bi-x-circle-fill' : 'bi bi-check-all' }}"
                                                labelText="{{ $payment->is_paid == true ? 'Annumer' : 'Valider' }}"
                                                class="text-primary" wire:confirm="Etês-vous sûre de réaliser l'action"
                                                href="#" wire:click='makeIsPaid({{ $payment }})' />
                                            <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer'
                                                class="text-primary" target='_blanck'
                                                href="{{ route('print.payment.receipt', $payment) }}" />
                                            <hr class="dropdown-divider">
                                            <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                                class="text-secondary" wire:confirm='Etês-vous sûre de supprimer'
                                                href="#" wire:click='delete({{ $payment }})' />
                                        </x-others.dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <livewire:application.payment.form.form-edit-payment-page>
</div>
