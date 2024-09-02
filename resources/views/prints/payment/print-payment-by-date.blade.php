<x-print-layout>
    @php
        $total = 0;
    @endphp
    <div class="">
        <h4 class="text-center">RAPPORT DES PAIMENTS JOUNALIERS</h4>
        <span><strong>Type frais:</strong></span><span>{{ $categoryFee->name }}</span><br>
        <span><strong>Date :</strong></span><span>{{ (new DateTime($date))->format('d/m/Y') }}</span><br>
        @if ($section != null)
            <span><strong>Section :</strong></span><span>{{ $section->name }}</span><br>
        @endif
        @if ($option != null)
            <span><strong>option :</strong></span><span>{{ $option->name }}</span><br>
        @endif
        @if ($classRoom != null)
            <span><strong>Classe :</strong></span><span>{{ $classRoom->getOriginalClassRoomName() }}</span><br>
        @endif
        <table class="table table-bordered table-sm ">
            <thead class="table-dark border border-black">
                <tr class="cursor-hand bg-app">
                    <th class="text-center">#</th>
                    <th>NOM COMPLET</th>
                    <th>CLASSE</th>
                    <th class="text-right">MONTANT</th>
                </tr>
            </thead>
            @if ($payments->isEmpty())
                <tr>
                    <td colspan="7"><x-errors.data-empty /></td>
                </tr>
            @else
                <tbody class="">
                    @foreach ($payments as $index => $payment)
                        <tr wire:key='{{ $payment->id }}' class="border border-black">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $payment->registration->student->name }}</td>
                            <td>{{ $payment->registration->classRoom->getOriginalClassRoomName() }}
                            </td>
                            <td class="text-right">
                                {{ app_format_number($payment->getAmount(), 1) }}
                                {{ $payment->scolarFee->categoryFee->currency }}
                            </td>

                        </tr>
                        @php
                            $total += $payment->getAmount();
                        @endphp
                    @endforeach
                    <tr class="text-uppercase bg-dark text-white text-bold">
                        <td colspan="3" class="text-right">Total</td>
                        <td class="text-right">{{ app_format_number($total, 1) }} {{ $categoryFee->currency }}</td>
                    </tr>
                </tbody>
            @endif
        </table>
    </div>
</x-print-layout>
