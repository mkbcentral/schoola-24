<x-print-layout>

    <div class="">
        <h6>Total:
            {{ $registrationns->count() <= 1 ? $registrationns->count() . ' Elève' : $registrationns->count() . ' Elèves' }}
        </h6>
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr class="cursor-hand bg-app">
                    <th class="text-center">#</th>
                    <th>NOM COMPLET</th>
                    <th class="text-center">AGE</th>
                    <th class="text-center">GENRE</th>
                </tr>
            </thead>
            @if ($registrationns->isEmpty())
                <tr>
                    <td colspan="7"><x-errors.data-empty /></td>
                </tr>
            @else
                <tbody>
                    @foreach ($registrationns as $index => $registration)
                        <tr wire:key='{{ $registration->id }}'>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $registration->name }}</td>
                            <td class="text-center">{{ $registration->student->getFormattedAg() }}</td>
                            <td class="text-center">{{ $registration->student->gender }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>

    </div>
</x-print-layout>
