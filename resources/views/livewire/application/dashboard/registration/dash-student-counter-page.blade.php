<div>
    <div class="card">
        <div class="card-header">
            Effectif par section
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($sections as $section => $count)
                    <div class="col-sm-4">
                        <x-widget.student-counter-widget label="{{ $section }}" value="{{ $count }}">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>Voir
                                    Details</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Imprimer
                                    Liste</a></li>
                        </x-widget.student-counter-widget>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    Effectif par option
                </div>
                <div class="card-body ">
                    <div class="row">
                        @foreach ($options as $option => $co)
                            <div class="col-sm-4 mb-2" style="cursor: pointer"
                                wire:click="changeOption('{{ $option }}')">
                                <x-widget.student-counter-widget label="{{ $option }}" value="{{ $co }}"
                                    bg="{{ $selectedOption->name == $option ? 'bg-primary' : '' }}">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>
                                            Voir
                                            Details</a></li>
                                    <li><a class="dropdown-item" href="#"><i
                                                class="bi bi-printer me-2"></i>Imprimer
                                            Liste</a></li>
                                </x-widget.student-counter-widget>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">

            <div class="card">
                <div class="card-header">
                    <h5 class="text-primary text-uppercase"> Détail {{ $selectedOption->name }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-light table-sm">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th class="text-center">Effectif</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedOption->classRooms as $classRoom)
                                <tr>
                                    <td>{{ $classRoom->getOriginalClassRoomName() }}</td>
                                    <td class="text-center">{{ $classRoom->registrations->count() }}</td>
                                    <td class="text-center">
                                        <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                            class=" btn-sm">
                                            <x-others.dropdown-link iconLink='bi bi-wallet2' target="_blank"
                                                labelText='Imprimer les paiements'
                                                href="{{ route('print.student.payemnts.by.classroom', $classRoom->id) }}" />
                                        </x-others.dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
