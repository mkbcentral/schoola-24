<x-print-layout>
    <x-widget.school-header-infos />
    <div class="">
        <h4 class="text-center"><u>LISTE DES EFFECTIFS</u></h4>
        @if ($option)
            <span><b>Option: </b>{{ $option->name }}</span><br>
        @endif
        @if ($classRoom)
            <span><b>Classe: </b>{{ $classRoom->name }}</span><br>
        @endif
        <span><b>Nbre Elèves: </b>{{ $registrations->count() }}</span><br>
    </div>
    <table class="table table-striped table-sm mt-3">
        <thead class="bg-secondary text-white">
            <tr>
                <th>#</th>
                <th>NOM DE L'ÉLÈVE</th>
                <th class="text-center">GENRE</th>
                <th class="text-center">AGE</th>
                <th class="text-right">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registrations as $index => $registration)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $registration->student->name }}</td>
                    <td class="text-center">{{ $registration->student->gender }}</td>
                    <td class="text-center">{{ $registration->student->getFormattedAg() }}</td>
                    <td class="text-right">
                        <span class="badge text-uppercase bg-{{ $registration->is_old ? 'warning' : 'info' }}">
                            {{ $registration->is_old ? 'Ancien' : 'Nouveau' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-print-layout>
