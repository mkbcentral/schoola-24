<div>
    @php
        $total = 0;
    @endphp
    <x-modal.build-modal-fixed idModal='form-cost-recipe' size='fullscreen' headerLabel="RECETTES ANNULLES"
        headerLabelIcon="bi bi-person-fill-add">
        <div class="card">
            <div class="card-header  d-flex justify-content-between align-items-center">
                <h4></h4>
                <div class="d-flex align-items-center">
                    <label for="my-select">Categorie</label>
                    <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='category_fee_filter'
                        :error="'category_fee_filter'" />
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table financial-summary">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Etat</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($payments->isEmpty())
                                <tr>
                                    <td colspan='3'> <x-errors.data-empty /></td>
                                </tr>
                            @else
                                @foreach ($payments as $payment)
                                    @if ($payment->total_amount != 0)
                                        <tr>
                                            <td class=" text-uppercase">{{ format_fr_month_name($payment->month) }}</td>
                                            <td class="w-50">
                                                @php
                                                    $pourcentage =
                                                        ($payment->total_amount / $payments->max('total_amount')) * 100;
                                                @endphp
                                                <div class="progress " role="progressbar"
                                                    aria-label="Animated striped example" aria-valuenow="75"
                                                    aria-valuemin="10" aria-valuemax="100">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated
                                            @if ($pourcentage >= 100) bg-success
                                            @elseif ($pourcentage >= 50) bg-warning
                                            @else bg-danger @endif"
                                                        style="width: {{ $pourcentage }}%">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class=" fw-bold">
                                                    {{ app_format_number($payment->total_amount, 1) }}
                                                </span>{{ $payment->currency_name }}
                                            </td>
                                        </tr>
                                        @php
                                            $total += $payment->total_amount;
                                        @endphp
                                    @endif
                                @endforeach
                                <tr class="bg-dark text-uppercase h3 fw-bold">
                                    <td class="text-end">Total</td>
                                    <td class="text-end"></td>
                                    <td class="text-end">{{ app_format_number($total, 1) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-modal.build-modal-fixed>

</div>
