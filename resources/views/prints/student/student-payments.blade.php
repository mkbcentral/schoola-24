<x-print-layout>
    <x-widget.school-header-infos />
    <div class="">
        <h4 class="text-center"><u>PAYMENTS DES ELEVES</u></h4>
        <span><b>Nom élève: </b>{{ $registration->student->name }}</span><br>
        <span><b>Classe: </b>{{ $registration->classRoom->getOriginalClassRoomName() }}</span><br>
        <span><b>Responasable </b>{{ $registration->student->responsibleStudent->name }}</span> /
        <span><b>Contanct </b>{{ $registration->student->responsibleStudent->phone }}</span>

        <div style="width: 100%; display: table; border-spacing: 2px;margin-top: 10px">
            @foreach ($months as $month)
                @php
                    $payments = $registration
                        ->payments()
                        ->where('month', $month['number'])
                        ->where('is_paid', true)
                        ->get();
                @endphp
                @if (!$payments->isEmpty())
                    <div style="display: table-cell; width: 30%; border: 1px solid #000; padding: 5px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                            <thead>
                                <tr style="background-color: #000; color: #fff; text-align: center;">
                                    <th style="padding: 5px;">{{ $month['name'] }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td style="border: 1px solid #000; padding: 5px;">{{ $payment->scolarFee->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-print-layout>
