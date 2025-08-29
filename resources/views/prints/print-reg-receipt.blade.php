<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .receipt {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 5px;
            width: 400px;
            position: relative;
        }

        .receipt::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;

            pointer-events: none;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            height: 100px;
        }

        .company-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .transaction-info {
            margin-bottom: 20px;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .item-name {
            position: relative;
            overflow: hidden;
        }

        .item-name::after {
            content: " ..........................................";
            position: absolute;
            padding-left: 5px;
        }

        .item-price {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
        }

        .total-row td {
            border-top: 2px solid #000;
            border-bottom: none;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }

        .items-table {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .items-table th:first-child,
        .items-table td:first-child {
            border-left: 1px solid #ddd;
        }

        .items-table th:last-child,
        .items-table td:last-child {
            border-right: 1px solid #ddd;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="company-info">
            <strong>COMPLEXE SCOLAIRE AQUILA</strong><br>
            Golf Plateau Q.MUKUNTO C/ANNEXE<br>
            Phone: (243) 9850135215 <br>
            Email: contact@aquila.com
        </div>
        <div class="transaction-info"><br>
            <strong>REGULARISATION<br></strong>
            <div>
                ***********************************************<br>
            </div>
            <strong>N° Réçu:</strong> #{{ $paymentRegularization->id }}-REG<br>
            <strong>Nom:</strong> {{ $paymentRegularization->name }}<br>
            <strong>Classe:</strong> {{ $paymentRegularization->classRoom->getOriginalClassRoomName() }}<br>
            <strong>Motif:</strong> {{ $paymentRegularization->categoryFee->name }}<br>
            <strong>Mois:</strong>
            {{ format_fr_month_name($paymentRegularization->month) }}/{{ $lastSchoolYear->name }}<br>
            <strong>Date:</strong> <span
                id="date">{{ $paymentRegularization->created_at->format('Y-m-d') }}</span><br>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Designation</th>
                    <th>Qté</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="item-name">{{ $paymentRegularization->categoryFee->name }}</td>
                    <td>1</td>
                    <td class="item-price">
                        {{ $paymentRegularization->categoryFee->currency == 'USD'
                            ? app_format_number($paymentRegularization->amount, 1)
                            : app_format_number($paymentRegularization->amount, 1) }}
                        Fc
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="2">Total</td>
                    <td class="item-price">
                        {{ app_format_number($paymentRegularization->amount, 1) }}
                        {{ $paymentRegularization->categoryFee->currency }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            *********** Education ! **********<br>
        </div>
    </div>
</body>

</html>
