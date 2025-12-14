<div>
    <div class="card">
        <div class="card-header">
            DETAILS PAIEMET
        </div>
        <div class="card-body" wire:loading.class='d-none'>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover mt-2">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">N°</th>
                            <th>DATE PAIEMENT</th>
                            <th>TYPE FRAIS</th>
                            <th class="text-end">MONTANT</th>
                        </tr>
                    </thead>
                    @if ($registration->payments->isEmpty())
                        <tr>
                            <td colspan="4"><x-errors.data-empty /></td>
                        </tr>
                    @else
                        <tbody>
                            @foreach ($registration->payments()->where('is_paid', true)->orderBy('created_at', 'DESC')->get() as $index => $payment)
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
