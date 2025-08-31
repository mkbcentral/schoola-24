<div>
    <div class="card">
        <div class="card-header">
            <h4 class="text-uppercase text-secondary">Recettes {{ $date != null ? 'jounrnalières' : 'Mensulles' }}</h4>
        </div>
        <div class="card-body">
            <div>
                <div
                    class="btn-group btn-group-sm d-flex flex-column flex-md-row align-items-stretch align-items-md-center mb-3 gap-2 gap-md-0">
                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center w-100">
                        <label for="dateFilter" class="me-md-3 mb-1 mb-md-0">Date</label>
                        <input wire:model.live='date' type="date" class="form-control me-md-2 mb-2 mb-md-0"
                            id="dateFilter">
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center w-100">
                        <label for="monthFilter" class="me-md-3 mb-1 mb-md-0">Mois</label>
                        <x-widget.list-month-fr wire:model.live='month' class="mb-2 mb-md-0" />
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mt-2">
                        <thead>
                            <tr>
                                <th>Categorie</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($payments->isEmpty())
                                <tr>
                                    <td colspan="2" class="text-center text-secondary">Aucune paiment trouvé</td>
                                </tr>
                            @else
                                @foreach ($payments as $payment)
                                    @if ($payment->total_amount != 0)
                                        <tr>
                                            <td class="text-uppercase">{{ $payment->category_name }}</td>
                                            <td class="text-end fw-bold">
                                                {{ app_format_number($payment->total_amount, 1) }}
                                                {{ $payment->currency }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
