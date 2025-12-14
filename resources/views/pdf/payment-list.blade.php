<x-layouts.print>
    <style>
        .page-break {
            page-break-after: always;
        }

        .avoid-page-break {
            page-break-inside: avoid;
        }

        .company-info {
            font-size: 11px;
            line-height: 1.4;
        }

        h5 {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <div>
        <!-- En-tête avec logo et infos de l'école -->
        <x-widget.school-header-infos />

        <!-- Titre du rapport -->
        <h4 class="text-center text-uppercase" style="margin: 15px 0;">LISTE DES PAIEMENTS</h4>

        <!-- Informations générales -->
        <div style="margin-bottom: 15px; font-size: 13px;">
            <span><strong>Année scolaire:</strong> {{ $schoolYear->name ?? '' }}</span><br>
            <span><strong>Généré le:</strong> {{ $generatedAt }}</span>
            @if (count($filters) > 0)
                <br><strong>Filtres appliqués:</strong>
                @foreach ($filters as $label => $value)
                    {{ $label }}: {{ $value }}@if (!$loop->last)
                        ,
                    @endif
                @endforeach
            @endif
        </div>

        <!-- Résumé Global -->
        <div style="margin-bottom: 20px;" class="avoid-page-break">
            <h5>RESUME GLOBAL</h5>
            <table class="table table-bordered table-sm">
                <tr>
                    <td style="width: 50%; font-weight: bold;">Total Paiements</td>
                    <td style="width: 50%;">{{ number_format($totalCount) }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Paiements Payés</td>
                    <td>{{ number_format($statistics['paid_count']) }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Paiements Non Payés</td>
                    <td>{{ number_format($statistics['unpaid_count']) }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Taux de Paiement</td>
                    <td>{{ number_format($statistics['payment_rate'], 1) }}%</td>
                </tr>
                @foreach ($totalsByCurrency as $currency => $amount)
                    <tr>
                        <td style="font-weight: bold;">Total {{ $currency }}</td>
                        <td>{{ number_format($amount, 0, ',', ' ') }} {{ $currency }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <!-- Liste détaillée des paiements -->
        <h5>LISTE DETAILLEE DES PAIEMENTS</h5>
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Élève</th>
                    <th>Classe</th>
                    <th>Catégorie</th>
                    <th class="text-right">Montant</th>
                    <th>Mois</th>

                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $payment->registration->student->name ?? 'N/A' }}</strong></td>
                        <td>
                            {{ $payment->registration->classRoom->name ?? 'N/A' }}
                            <small>({{ $payment->registration->classRoom->option->name ?? '' }})</small>
                        </td>
                        <td>{{ $payment->scolarFee->categoryFee->name ?? 'N/A' }}</td>
                        <td class="text-right">
                            {{ number_format($payment->scolarFee->amount ?? 0, 0, ',', ' ') }}
                            {{ $payment->scolarFee->categoryFee->currency ?? '' }}
                        </td>
                        <td>{{ format_fr_month_name($payment->month) ?? '-' }}</td>
                        </td>
                        <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Aucun paiement trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signature Section -->
        <div style="margin-top: 40px; text-align: right;" class="avoid-page-break">
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
</x-layouts.print>
