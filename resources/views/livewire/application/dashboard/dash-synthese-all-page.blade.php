<div>
    @php
        $totalPayments = 0;
        $totalExpenses = 0;
        $totalOtherExpenses = 0;
        $totalBalance = 0;
    @endphp
    <div class="card">
        <div
            class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <h4 class="text-uppercase mb-2 mb-md-0">SYNTHÈSE FINANCIÈRE SUR LES FRAIS SCOLAIRES</h4>
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                <label for="my-select" class="me-2 mb-1 mb-sm-0">Categorie</label>
                <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='category_fee_filter'
                    :error="'category_fee_filter'" />
            </div>
            <button class="btn btn-primary mt-2 mt-md-0" onclick="printTable()">Imprimer le tableau</button>
        </div>
        <div class="card-body">
            <div class="w-100" style="overflow-x:auto;">
                <canvas id="expensesChart"></canvas>
            </div>
            <div class="table-responsive mt-4" id="printable-table">
                <table class="table table-striped table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Catégorie</th>
                            <th class="text-end">Recettes</th>
                            <th class="text-end">Dépenses</th>
                            <th class="text-end">Emprunt</th>
                            <th class="text-end">Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($balances as $month => $categories)
                            @foreach ($categories as $category => $data)
                                @php
                                    $totalPayments += $data['payments'];
                                    $totalExpenses += $data['expenses'];
                                    $totalOtherExpenses += $data['other_expenses'];
                                    $totalBalance += $data['balance'];
                                @endphp
                                <tr>
                                    <td class="text-uppercase">
                                        {{ $month }}
                                    </td>
                                    <td>{{ $category }}</td>
                                    <td class="text-end">{{ number_format($data['payments'], 2) }}</td>
                                    <td class="text-end">{{ number_format($data['expenses'], 2) }}</td>
                                    <td class="text-end">{{ number_format($data['other_expenses'], 2) }}</td>
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
                            <th class="text-end">{{ number_format($totalOtherExpenses, 2) }}</th>
                            <th class="text-end">{{ number_format($totalBalance, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            .card-header>* {
                width: 100%;
            }

            .table-responsive {
                font-size: 0.95rem;
            }

            .card-header h4 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .card-header h4 {
                font-size: 1rem;
            }
        }
    </style>
    @push('js')
        <script></script>
    @endpush
</div>
