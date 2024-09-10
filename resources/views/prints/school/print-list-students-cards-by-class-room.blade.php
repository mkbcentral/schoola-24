<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ public_path('bootstrap-4.6.2-dist/css/bootstrap.min.css') }}">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .card {
            width: 350px;
            height: 200px;
            background-color: white;
            border-radius: 15px;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .blue-section {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 60%;
            background-color: #000080;
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
            border-top-right-radius: 100px;
            border-bottom-right-radius: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            background-color: white;
            border-radius: 50%;
            margin-bottom: 10px;
            margin-top: 18px;
            margin-left: 50px
        }

        .logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .school-name {
            color: white;
            font-size: 1.4rem;
            font-weight: bold;
            text-align: center;
        }

        .schoola-id {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 1rem;
            font-weight: bold;
        }

        .qr-code {
            position: absolute;
            right: 20px;
            top: 50px;
            width: 80px;
            height: 80px;
        }

        .id-number {
            position: absolute;
            right: 20px;
            bottom: 40px;
            font-size: 0.9rem;
            font-weight: bold;
            color: #000080;
        }

        .website {
            position: absolute;
            right: 20px;
            bottom: 20px;
            color: #666;
            font-size: 0.7rem;
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <table class="table">
            <tbody>
                @foreach ($registrationns as $registrationn)
                    <tr>
                        <td class="border bodern">
                            <div class="card mb-2">
                                <div class="blue-section">
                                    <div class="text-center">
                                        <div class="logo">
                                            <img src="{{ public_path(Auth::user()->school->logo != null ? 'storage/' . Auth::user()->school->logo : 'images/defautl-user.jpg') }}"
                                                alt="Logo" width="50px" height="50px">
                                        </div>
                                        <div class="school-name">{{ Auth::user()->school->name }}</div>
                                    </div>
                                </div>
                                <div class="schoola-id">SCHOOLA-ID</div>
                                <img src="{{ public_path($registrationn->qr_code == null ? 'empty.png' : $registrationn->qr_code) }}"
                                    alt="QR Code" class="qr-code">
                                <div class="id-number">ID:{{ $registrationn->code }}</div>
                                <div class="website">www.schoola.app</div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</body>

</html>
