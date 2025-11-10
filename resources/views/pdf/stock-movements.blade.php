<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mouvements de Stock - {{ $article->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0dcaf0;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #0dcaf0;
            margin: 0 0 10px 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-item {
            flex: 1;
            text-align: center;
        }

        .info-label {
            font-size: 10px;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
            color: #0dcaf0;
        }

        .info-value.success {
            color: #198754;
        }

        .info-value.danger {
            color: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background: #0dcaf0;
            color: white;
        }

        th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-danger {
            background: #f8d7da;
            color: #842029;
        }

        .badge-warning {
            background: #fff3cd;
            color: #664d03;
        }

        .badge-secondary {
            background: #e2e3e5;
            color: #41464b;
        }

        .text-success {
            color: #198754;
        }

        .text-danger {
            color: #dc3545;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .filter-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <!-- En-tête -->
    <div class="header">
        <h1>📦 Historique des Mouvements de Stock</h1>
        <p><strong>Article :</strong> {{ $article->name }}</p>
        @if ($article->reference)
            <p><strong>Référence :</strong> {{ $article->reference }}</p>
        @endif
        <p><strong>Date d'édition :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Informations de filtrage -->
    @if ($hasFilters)
        <div class="filter-info">
            <strong>⚠️ Filtres appliqués :</strong>
            @if ($filterType !== 'all')
                Type: <strong>{{ $filterType === 'in' ? 'Entrées' : 'Sorties' }}</strong>
            @endif
            @if ($filterPeriod !== 'all')
                | Période: <strong>
                    @if ($filterPeriod === 'today')
                        Aujourd'hui
                    @elseif($filterPeriod === 'week')
                        Cette semaine
                    @elseif($filterPeriod === 'month')
                        Ce mois
                    @elseif($filterPeriod === 'custom')
                        Du {{ $filterDateStart }} au {{ $filterDateEnd }}
                    @endif
                </strong>
            @endif
        </div>
    @endif

    <!-- Résumé du stock -->
    <div class="info-box">
        <div class="info-row" style="display: table; width: 100%;">
            <div class="info-item" style="display: table-cell; width: 33.33%;">
                <span class="info-label">Stock Actuel</span>
                <span class="info-value">{{ $article->stock }}</span>
            </div>
            <div class="info-item" style="display: table-cell; width: 33.33%;">
                <span class="info-label">Total Entrées (clôturées)</span>
                <span class="info-value success">+{{ $totalIn }}</span>
            </div>
            <div class="info-item" style="display: table-cell; width: 33.33%;">
                <span class="info-label">Total Sorties (clôturées)</span>
                <span class="info-value danger">-{{ $totalOut }}</span>
            </div>
        </div>
    </div>

    <!-- Tableau des mouvements -->
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Date</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 10%;">Quantité</th>
                <th style="width: 25%;">Motif</th>
                <th style="width: 15%;">Utilisateur</th>
                <th style="width: 10%;">Statut</th>
                <th style="width: 18%;">Clôturé le</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $movement)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($movement->movement_date)->format('d/m/Y') }}</td>
                    <td>
                        @if ($movement->type === 'in')
                            <span class="badge badge-success">↓ Entrée</span>
                        @else
                            <span class="badge badge-danger">↑ Sortie</span>
                        @endif
                    </td>
                    <td
                        style="font-weight: bold; {{ $movement->type === 'in' ? 'color: #198754' : 'color: #dc3545' }}">
                        {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                    </td>
                    <td>{{ $movement->reason ?? '-' }}</td>
                    <td>{{ $movement->user->name ?? 'N/A' }}</td>
                    <td>
                        @if ($movement->is_closed)
                            <span class="badge badge-secondary">✓ Clôturé</span>
                        @else
                            <span class="badge badge-warning">⏳ Ouvert</span>
                        @endif
                    </td>
                    <td>
                        @if ($movement->is_closed && $movement->closed_at)
                            {{ \Carbon\Carbon::parse($movement->closed_at)->format('d/m/Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #999;">
                        Aucun mouvement trouvé
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pied de page -->
    <div class="footer">
        <p>Document généré automatiquement le {{ now()->format('d/m/Y à H:i:s') }}</p>
        <p>Total de mouvements : {{ count($movements) }}</p>
    </div>
</body>

</html>
