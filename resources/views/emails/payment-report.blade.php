<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #1a1f36 0%, #2d3561 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .description {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #1a1f36;
            margin: 20px 0;
            border-radius: 4px;
        }

        .summary {
            margin: 25px 0;
        }

        .summary-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1f36;
            margin-bottom: 15px;
        }

        .stats {
            display: table;
            width: 100%;
            margin: 15px 0;
        }

        .stat-row {
            display: table-row;
        }

        .stat-label {
            display: table-cell;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e1e4e8;
            font-weight: 500;
            width: 40%;
        }

        .stat-value {
            display: table-cell;
            padding: 10px 15px;
            background-color: #ffffff;
            border-bottom: 1px solid #e1e4e8;
            text-align: right;
            font-weight: 600;
        }

        .currency-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }

        .currency-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e1e4e8;
        }

        .currency-item:last-child {
            border-bottom: none;
        }

        .currency-name {
            font-weight: 600;
            color: #1a1f36;
            font-size: 16px;
        }

        .currency-amount {
            font-size: 18px;
            font-weight: 700;
        }

        .currency-usd {
            color: #059669;
        }

        .currency-cdf {
            color: #dc2626;
        }

        .currency-count {
            font-size: 12px;
            color: #6b7280;
            margin-left: 8px;
        }

        .attachment-notice {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .attachment-notice strong {
            color: #856404;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }

        .footer p {
            margin: 5px 0;
        }

        .divider {
            height: 1px;
            background-color: #e1e4e8;
            margin: 25px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📊 {{ $reportTitle }}</h1>
            <p>{{ $period }}</p>
        </div>

        <div class="content">
            <div class="greeting">
                Bonjour,
            </div>

            <p>
                Veuillez trouver ci-joint le rapport financier des paiements que vous avez demandé.
            </p>

            <div class="description">
                <strong>Description :</strong> {{ $reportDescription }}
            </div>

            <div class="summary">
                <div class="summary-title">Résumé du Rapport</div>

                <div class="stats">
                    <div class="stat-row">
                        <div class="stat-label">Nombre total de paiements</div>
                        <div class="stat-value">{{ $report['total_payments'] ?? 0 }}</div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-label">Nombre de catégories</div>
                        <div class="stat-value">{{ count($report['categories'] ?? []) }}</div>
                    </div>
                </div>

                @if (isset($report['total_by_currency']) && count($report['total_by_currency']) > 0)
                    <div class="currency-section">
                        <strong style="display: block; margin-bottom: 10px;">Totaux par devise :</strong>
                        @foreach ($report['total_by_currency'] as $currency)
                            <div class="currency-item">
                                <span class="currency-name">{{ $currency['currency'] }}</span>
                                <span>
                                    <span
                                        class="currency-amount {{ $currency['currency'] === 'USD' ? 'currency-usd' : ($currency['currency'] === 'CDF' ? 'currency-cdf' : '') }}">
                                        {{ number_format($currency['total'], 2, ',', ' ') }}
                                    </span>
                                    <span class="currency-count">({{ $currency['payment_count'] ?? 0 }}
                                        paiements)</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="divider"></div>

            <div class="attachment-notice">
                <strong>📎 Pièce jointe :</strong> Le rapport complet est disponible en format PDF ci-joint.
            </div>

            <p style="margin-top: 25px;">
                Pour toute question ou clarification, n'hésitez pas à nous contacter.
            </p>

            <p style="margin-top: 15px;">
                Cordialement,<br>
                <strong>L'équipe de gestion financière</strong>
            </p>
        </div>

        <div class="footer">
            <p>Cet email a été généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
            <p>Veuillez ne pas répondre directement à cet email.</p>
        </div>
    </div>
</body>

</html>
