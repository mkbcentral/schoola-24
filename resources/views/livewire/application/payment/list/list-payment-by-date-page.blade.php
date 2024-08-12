<div wire:poll.15s>
    <div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Date') }}" class="me-2" />
                <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
            </div>
            <x-form.search-input wire:model.live='q' />
            <h3 class="text-primary text-uppercase">Total: {{ app_format_number($total_payments, 1) }} Fc</h3>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <table class="table table-bordered table-sm table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th class="cursor-hand">
                    <span>NOM COMPLET</span>
                </th>
                <th>CLASSE</th>
                <th>FRAIS</th>
                <th class="text-end">MONTANT</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($payments->isEmpty())
            <tr>
                <td colspan="7"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($payments as $index => $payment)
                    <tr wire:key='{{ $payment->id }}' class=" ">
                        <td class="text-center {{ $payment->is_paid == true ? 'bg-success' : ' bg-warning' }}">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $payment->registration->student->name }}</td>
                        <td>{{ $payment?->registration->classRoom?->getOriginalClassRoomName() }}</td>
                        <td>
                            {{ $payment->scolarFee->name }}/
                            {{ format_fr_month_name($payment->month) }}
                        </td>
                        <td class="text-end">{{ $payment->getAmountCDF() }}</td>
                        <td class="text-center">
                            <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                class="btn-secondary btn-sm">
                                <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer'
                                    data-bs-toggle="modal" data-bs-target="#form-edit-payment" href="#"
                                    wire:click='edit({{ $payment }})' class="text-primary" />
                                <x-others.dropdown-link
                                    iconLink="{{ $payment->is_paid == true ? 'bi bi-x-circle-fill' : 'bi bi-check-all' }}"
                                    labelText="{{ $payment->is_paid == true ? 'Annumer' : 'Valider' }}"
                                    class="text-primary" wire:confirm="Etês-vous sûre de réaliser l'action"
                                    href="#" wire:click='makeIsPaid({{ $payment }})' />
                                <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer'
                                    class="text-primary" target='_blanck'
                                    href="{{ route('print.payment.receipt', $payment) }}" />
                                <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                    class="text-secondary" wire:confirm='Etês-vous sûre de supprimer' href="#"
                                    wire:click='delete({{ $payment }})' />

                            </x-others.dropdown>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    <div class="d-flex justify-content-between align-items-center">
        <span>{{ $payments->links('livewire::bootstrap') }}</span>
        @if ($payments->count() > 9)
            <x-others.table-page-number wire:model.live='per_page' />
        @endif
    </div>
    @livewire('application.payment.form.form-edit-payment-page');
</div>
