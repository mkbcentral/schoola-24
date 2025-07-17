<div wire:poll.30s>
    <h3 class="text-primary text-uppercase text-end">
        Total: {{ app_format_number($total_payments, 1) }}
        {{ $categoryFeeSelected?->currency }}
        ({{ $categoryFeeSelected?->name }})
    </h3>
    <div class="table-responsive">
        <table class="table table-hover mt-2">
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
                                @if ($payment->smsPayment)
                                    <i class="bi bi-chat-fill text-info"></i>
                                @endif
                            </div>
                        </td>
                        <td>{{ $payment?->registration->classRoom?->getOriginalClassRoomName() }}</td>
                        <td>{{ $payment->month }}</td>
                        <td>{{ app_format_number($payment->getAmount(), 1) }}
                            {{ $payment->scolarFee->categoryFee->currency }}</td>
                        <td><span
                                class="badge {{ $payment->is_paid == true ? 'bg-success' : ' bg-warning' }} bg-success">{{ $payment->is_paid == true ? 'SUCCESS' : 'PENDING' }}</span>
                        </td>
                        <td class="text-center">
                            <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical" class=" btn-sm">
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
                                @if ($payment->is_paid == true)
                                    <x-others.dropdown-link iconLink='bi bi-chat-left-text-fill'
                                        wire:click='sendSms({{ $payment }})' labelText='Envoyer sms'
                                        class="text-primary" href="#" />
                                @endif
                                <hr class="dropdown-divider">
                                <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                    class="text-secondary" wire:confirm='Etês-vous sûre de supprimer' href="#"
                                    wire:click='delete({{ $payment }})' />
                            </x-others.dropdown>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <livewire:application.payment.form.form-edit-payment-page>
</div>
