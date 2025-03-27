<x-print-layout>
    @foreach ($registrations as $registration)
        <x-widget.school-header-infos />
        <div class="">
            <h4 class="text-center"><u>EVOLUTION DE PAYMENT DES FRAIS</u></h4>
            <span><b>Nom élève: </b>{{ $registration->student->name }}</span><br>
            <span><b>Classe: </b>{{ $registration->classRoom->getOriginalClassRoomName() }}</span><br>
            <span><b>Responasable </b>{{ $registration->student->responsibleStudent->name }}</span> /
            <span><b>Contanct </b>{{ $registration->student->responsibleStudent->phone }}</span>
            @foreach ($months as $month)
                @php
                    $payments = $registration
                        ->payments()
                        ->where('month', $month['number'])
                        ->where('is_paid', true)
                        ->get();
                @endphp
                @if (!$payments->isEmpty())
                    <div class="avoid-page-break">
                        <table class="table table-bordered table-sm mt-2" style="border: 1px solid black;">
                            <thead class="table-dark" style="border: 1px solid black;">
                                <tr class="cursor-hand bg-app" style="border: 1px solid black;">
                                    <td style="border: 1px solid black;">{{ $month['name'] }}</td>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.9em; border: 1px solid black;">
                                @foreach ($payments as $payment)
                                    <tr style="border: 1px solid black;">
                                        <td style="border: 1px solid black;">{{ $payment->scolarFee->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach

</x-print-layout>
