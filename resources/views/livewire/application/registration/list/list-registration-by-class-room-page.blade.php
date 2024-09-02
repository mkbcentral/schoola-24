<div>
    <x-navigation.bread-crumb icon='bi bi-people-fill' label="Effectif par classe">
        <x-navigation.bread-crumb-item label='Effectif par classe' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="d-flex justify-content-between align-items-center">
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <x-others.list-title icon='bi bi-list-task' color='text-primary h3'
                title='Liste des élèves : {{ $classRoom->getOriginalClassRoomName() }}' />
            <h4>
                <span class="badge text-bg-info">(Total:
                    {{ $count <= 1 ? $count . ' Elève' : $count . ' Elèves' }})</span>
            </h4>
            <div class="d-flex">
                <x-form.search-input wire:model.live='q' />
                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical" class="btn-secondary btn-sm ms-2">
                    <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer'
                        href="{{ route('print.students.by.classRomm', [$classRoomId, $sortAsc]) }}" target='_blank' />
                </x-others.dropdown>
            </div>
        </div>

        <div class="">
            <div class="d-flex justify-content-center mt-2">
                <x-widget.loading-circular-md wire:loading />
            </div>
        </div>
        <table class="table table-bordered table-sm">
            <thead class="bg-app">
                <tr class="cursor-hand bg-app">
                    <th class="text-center">#</th>
                    <th class="">S-ID</th>
                    <th wire:click="sortData('students.name')">
                        <span>NOM COMPLET</span>
                        <x-form.sort-icon sortField="students.name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                    </th>
                    <th class="text-center" wire:click="sortData('students.date_of_birth')">
                        <span>AGE</span>
                        <x-form.sort-icon sortField="students.date_of_birth" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                    </th>
                    <th class="text-center">GENRE</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            @if ($registrations->isEmpty())
                <tr>
                    <td colspan="7"><x-errors.data-empty /></td>
                </tr>
            @else
                <tbody>
                    @foreach ($registrations as $index => $registration)
                        <tr wire:key='{{ $registration->id }}'>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $registration->code }}</td>
                            <td>{{ $registration->name }}</td>
                            <td class="text-center">{{ $registration->student->getFormattedAg() }}</td>
                            <td class="text-center">{{ $registration->student->gender }}</td>
                            <td class="text-center">
                                <span class="badge text-bg-{{ $registration->is_old ? 'warning' : 'info' }}">
                                    {{ $registration->is_old ? 'Ancien' : 'Nouveau' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-secondary btn-sm">
                                    @can('manage-student')
                                        <x-others.dropdown-link iconLink='bi bi-pencil-fill' data-bs-toggle="modal"
                                            data-bs-target="#form-edit-student" labelText='Editer' href="#"
                                            wire:click='edit({{ $registration->student }})' class="text-primary" />
                                    @endcan
                                    <x-others.dropdown-link iconLink='bi bi-info-circle-fill' labelText='Voir détails'
                                        href="{{ route('student.detail', $registration) }}" class="text-primary" />
                                </x-others.dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    </x-content.main-content-page>
    <livewire:application.student.form.form-edit-student-page />
</div>
