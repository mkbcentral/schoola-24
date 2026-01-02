<x-print-layout>
    <div>
        <x-widget.school-header-infos />
        <h4 class="text-center text-uppercase" style="margin: 15px 0;">{{ $reportTitle }}</h4>
        <div style="margin-bottom: 15px; font-size: 13px;">
            <span><strong>Période :</strong> {{ $report['label'] }}</span><br>
            <span><strong>Généré le :</strong> {{ $generatedAt }}</span>
        </div>

        <!-- Résumé Global -->
        <div style="margin-bottom: 20px;">
            <h5 style="font-weight: bold; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px;">
                RESUME GLOBAL</h5>
            <table class="table table-bordered table-sm">
                <tr>
                    <td style="width: 50%; font-weight: bold;">Total Paiements</td>
                    <td style="width: 50%;">{{ $report['total_payments'] ?? 0 }}</td>
                </tr>
                @foreach ($report['total_by_currency'] ?? [] as $currency)
                    <tr>
                        <td style="font-weight: bold;">Total {{ $currency['currency'] }}</td>
                        <td>{{ number_format($currency['total'], 1, ',', ' ') }} ({{ $currency['payment_count'] }}
                            operations)</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <!-- Tableau des Catégories par Devise -->
        <h5 style="font-weight: bold; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px;">DETAILS
            PAR CATEGORIE</h5>
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>CATEGORIE</th>
                    <th class="text-right">USD</th>
                    <th class="text-right">CDF</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($report['categories'] ?? [] as $category)
                    @php
                        $usdData = null;
                        $cdfData = null;
                        foreach ($category['by_currency'] ?? [] as $curr) {
                            if ($curr['currency'] === 'USD') {
                                $usdData = $curr;
                            }
                            if ($curr['currency'] === 'CDF') {
                                $cdfData = $curr;
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $category['name'] }}</td>
                        <td class="text-right">
                            @if ($usdData)
                                {{ number_format($usdData['total'], 0, ',', ' ') }}
                                <small>({{ $usdData['payment_count'] }})</small>
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($cdfData)
                                {{ number_format($cdfData['total'], 0, ',', ' ') }}
                                <small>({{ $cdfData['payment_count'] }})</small>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Aucune categorie trouvee</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td class="text-right"><strong>TOTAL</strong></td>
                    <td class="text-right">
                        <strong>
                            @php
                                $totalUsd =
                                    collect($report['total_by_currency'] ?? [])->firstWhere('currency', 'USD')[
                                        'total'
                                    ] ?? 0;
                            @endphp
                            {{ number_format($totalUsd, 0, ',', ' ') }}
                        </strong>
                    </td>
                    <td class="text-right">
                        <strong>
                            @php
                                $totalCdf =
                                    collect($report['total_by_currency'] ?? [])->firstWhere('currency', 'CDF')[
                                        'total'
                                    ] ?? 0;
                            @endphp
                            {{ number_format($totalCdf, 0, ',', ' ') }}
                        </strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Signature Section -->
        <div style="margin-top: 40px; text-align: right;">
            <p style="margin-bottom: 5px;">
                <strong>Fait à Lubumbashi, Le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</strong>
            </p>
            <p style="margin-bottom: 5px;">
                <strong>{{ Auth::user()->name }}</strong>
            </p>
            @if (file_exists(public_path('sign.png')))
                <img src="{{ public_path('sign.png') }}" alt="Signature"
                    style="max-width: 200px; max-height: 100px; margin-top: 10px;">
            @endif
        </div>
    </div>
</x-print-layout>
