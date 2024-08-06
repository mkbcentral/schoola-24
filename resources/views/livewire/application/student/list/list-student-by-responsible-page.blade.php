<div>
    <x-modal.build-modal-fixed idModal='list-student-by-responsible' size='lg'
        headerLabel="LISTE DES ELEVES PAR RESPONSABLE" headerLabelIcon='bi bi-person-lines-fill'>
        <div class="d-flex justify-content-center">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <div>
            @if ($responsibleStudent)
                <div>
                    <span class="fw-bold">Nom:</span>
                    <span>{{ $responsibleStudent->name }}</span>
                </div>
                <div>
                    <span class="fw-bold">Contact:</span>
                    <span>{{ $responsibleStudent?->phone . ' | ' . $responsibleStudent?->other_phone }}</span>
                </div>
                <div>
                    <span class="fw-bold">Email:</span>
                    <span>{{ $responsibleStudent->email == null ? 'Pas de mail' : $responsibleStudent->email }}</span>
                </div>

                <table class="table table-bordered table-sm mt-2">
                    <thead class="bg-app">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Nom complet</th>
                            <th class="text-center">Age</th>
                            <th>Lieu de naissance</th>
                            <th>Classe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $index => $registration)
                            <tr wire:key='{{ $registration->id }}'>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $registration->student->name }}</td>
                                <td class="text-center">{{ $registration->student->getFormattedAg() }}</td>
                                <td>{{ $registration->student->place_of_birth }}</td>
                                <td>{{ $registration->classRoom->getOriginalClassRoomName() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </x-modal.build-modal-fixed>
</div>
