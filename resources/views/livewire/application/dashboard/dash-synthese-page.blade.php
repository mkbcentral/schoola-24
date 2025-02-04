<div>
    @php
        $totalPayments = 0;
        $totalExpenses = 0;
        $totalBalance = 0;
    @endphp
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Synthèse finance</h4>
            <div class="d-flex align-items-center">
                <label for="my-select">Categorie</label>
                <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='category_fee_filter'
                    :error="'category_fee_filter'" />
            </div>
        </div>
        <div class="card-body">
            <canvas id="expensesChart"></canvas>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Catégorie</th>
                            <th class="text-end">Paiements</th>
                            <th class="text-end">Dépenses</th>
                            <th class="text-end">Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($balances as $month => $categories)
                            @foreach ($categories as $category => $data)
                                @php
                                    $totalPayments += $data['payments'];
                                    $totalExpenses += $data['expenses'];
                                    $totalBalance += $data['balance'];
                                @endphp
                                <tr>
                                    <td class="text-uppercase">
                                        {{ $month }}
                                    </td>
                                    <td>{{ $category }}</td>
                                    <td class="text-end">{{ number_format($data['payments'], 2) }}</td>
                                    <td class="text-end">{{ number_format($data['expenses'], 2) }}</td>
                                    <td class="text-end">{{ number_format($data['balance'], 2) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot class="bg-dark text-uppercase">
                        <tr class="h4">
                            <th colspan="2" class="text-end">Total Général</th>
                            <th class="text-end">{{ number_format($totalPayments, 2) }}</th>
                            <th class="text-end">{{ number_format($totalExpenses, 2) }}</th>
                            <th class="text-end">{{ number_format($totalBalance, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
