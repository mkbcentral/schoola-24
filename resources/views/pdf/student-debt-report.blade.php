<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport des Élèves avec Dettes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #4F46E5;
            font-size: 20pt;
            margin-bottom: 5px;
        }

        .header p {
            color: #6B7280;
            font-size: 9pt;
        }

        .info-section {
            background-color: #F3F4F6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
        }

        .info-value {
            color: #6B7280;
        }

        .statistics {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .stat-row {
            display: table-row;
        }

        .stat-box {
            display: table-cell;
            background-color: #EEF2FF;
            border: 1px solid #C7D2FE;
            padding: 12px;
            text-align: center;
            width: 25%;
        }

        .stat-box .stat-label {
            font-size: 8pt;
            color: #6366F1;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .stat-box .stat-value {
            font-size: 14pt;
            color: #312E81;
            font-weight: bold;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.data-table thead {
            background-color: #4F46E5;
            color: white;
        }

        table.data-table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: 600;
        }

        table.data-table tbody tr {
            border-bottom: 1px solid #E5E7EB;
        }

        table.data-table tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        table.data-table td {
            padding: 8px;
            font-size: 9pt;
        }

        .amount {
            text-align: right;
            font-weight: 600;
            color: #DC2626;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 600;
        }

        .badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .badge-danger {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .badge-critical {
            background-color: #DBEAFE;
            color: #1E3A8A;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 8pt;
            color: #9CA3AF;
        }

        .page-break {
            page-break-after: always;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #FFF7ED;
            border-left: 4px solid #F59E0B;
        }

        .summary-section h3 {
            color: #92400E;
            font-size: 11pt;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- En-tête --}}
        <div class="header">
            <h1>📊 Rapport des Élèves avec Dettes</h1>
            <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>

        {{-- Informations du rapport --}}
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Catégorie de frais:</span>
                <span class="info-value">{{ $categoryName }} ({{ $currency }})</span>
            </div>
            @if($filters['section'])
                <div class="info-row">
                    <span class="info-label">Section:</span>
                    <span class="info-value">{{ $filters['section'] }}</span>
                </div>
            @endif
            @if($filters['option'])
                <div class="info-row">
                    <span class="info-label">Option:</span>
                    <span class="info-value">{{ $filters['option'] }}</span>
                </div>
            @endif
            @if($filters['classRoom'])
                <div class="info-row">
                    <span class="info-label">Classe:</span>
                    <span class="info-value">{{ $filters['classRoom'] }}</span>
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">Minimum mois impayés:</span>
                <span class="info-value">{{ $filters['minMonthsUnpaid'] }} mois</span>
            </div>
        </div>

        {{-- Statistiques --}}
        <h2 style="color: #374151; font-size: 12pt; margin-bottom: 10px;">📈 Statistiques Globales</h2>
        <div class="statistics">
            <div class="stat-row">
                <div class="stat-box">
                    <span class="stat-label">TOTAL ÉLÈVES</span>
                    <span class="stat-value">{{ $statistics['total_students'] }}</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">MONTANT TOTAL</span>
                    <span class="stat-value">{{ number_format($statistics['total_debt_amount'], 2, ',', ' ') }} {{ $currency }}</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">MOYENNE MOIS</span>
                    <span class="stat-value">{{ number_format($statistics['average_months_unpaid'], 1, ',', ' ') }}</span>
                </div>
                <div class="stat-box">
                    <span class="stat-label">MAX MOIS</span>
                    <span class="stat-value">{{ $statistics['max_months_unpaid'] }}</span>
                </div>
            </div>
        </div>

        {{-- Distribution par gravité --}}
        <div class="summary-section">
            <h3>Répartition par niveau de gravité</h3>
            <div class="info-row">
                <span>2 mois impayés:</span>
                <span><strong>{{ $statistics['students_with_2_months'] }}</strong> élèves</span>
            </div>
            <div class="info-row">
                <span>3 mois impayés:</span>
                <span><strong>{{ $statistics['students_with_3_months'] }}</strong> élèves</span>
            </div>
            <div class="info-row">
                <span>4 mois ou plus:</span>
                <span><strong>{{ $statistics['students_with_4_plus_months'] }}</strong> élèves</span>
            </div>
        </div>

        {{-- Liste des élèves --}}
        <h2 style="color: #374151; font-size: 12pt; margin-top: 25px; margin-bottom: 10px;">👥 Liste Détaillée des Élèves</h2>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">N°</th>
                    <th style="width: 10%;">Code</th>
                    <th style="width: 27%;">Nom de l'élève</th>
                    <th style="width: 20%;">Classe</th>
                    <th style="width: 10%;" class="text-center">Mois</th>
                    <th style="width: 25%;" class="text-right">Dette ({{ $currency }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student['student_code'] }}</td>
                        <td>{{ $student['student_name'] }}</td>
                        <td>{{ $student['class_room_name'] }}</td>
                        <td class="text-center">
                            @if($student['months_unpaid'] >= 4)
                                <span class="badge badge-critical">{{ $student['months_unpaid'] }} mois</span>
                            @elseif($student['months_unpaid'] >= 3)
                                <span class="badge badge-danger">{{ $student['months_unpaid'] }} mois</span>
                            @else
                                <span class="badge badge-warning">{{ $student['months_unpaid'] }} mois</span>
                            @endif
                        </td>
                        <td class="amount">{{ number_format($student['total_debt_amount'], 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot style="background-color: #F3F4F6; font-weight: bold;">
                <tr>
                    <td colspan="5" style="text-align: right; padding: 10px;">TOTAL GÉNÉRAL:</td>
                    <td class="amount" style="padding: 10px; font-size: 11pt; color: #DC2626;">
                        {{ number_format($statistics['total_debt_amount'], 2, ',', ' ') }} {{ $currency }}
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Pied de page --}}
        <div class="footer">
            <p>Ce rapport a été généré automatiquement par le système de gestion scolaire</p>
            <p>Document confidentiel - Usage interne uniquement</p>
        </div>
    </div>
</body>
</html>
