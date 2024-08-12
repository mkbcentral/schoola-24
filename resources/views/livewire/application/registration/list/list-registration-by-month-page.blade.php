<div>
    <x-navigation.bread-crumb icon='bi bi-person-fill-add' label="{{ $isOld == true ? 'Réincriptions' : 'Incriptions' }}">
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
        <x-navigation.bread-crumb-item label="{{ $isOld == true ? 'Réincriptions' : 'Incriptions' }} par mois" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="mt-2">
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading />
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <x-others.list-title color='text-primary' cl title="Mois: {{ format_fr_month_name($monthFilter) }}" />
                <x-form.search-input wire:model.live='q' />
            </div>
            <table class="table table-bordered table-sm">
                <thead class="bg-app">
                    <tr class="cursor-hand">
                        <th class="text-center">#</th>
                        <th class="">CODE</th>
                        <th wire:click="sortData('name')">
                            <span>NOM COMPLET</span>
                            <x-form.sort-icon sortField="name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                        </th>
                        <th>CLASSE</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($registrations->isEmpty())
                        <tr>
                            <td colspan="4"><x-errors.data-empty /></td>
                        </tr>
                    @else
                        @foreach ($registrations as $index => $registration)
                            <tr wire:key='{{ $registration->id }}'>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $registration->code }}</td>
                                <td>{{ $registration->student->name }}</td>
                                <td>{{ $registration->classRoom->getOriginalClassRoomName() }}</td>
                                <td class="text-center">
                                    @can('manage-student')
                                        <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                            class="btn-secondary btn-sm">
                                            <x-others.dropdown-link iconLink='bi bi-pencil-fill' data-bs-toggle="modal"
                                                data-bs-target="#form-edit-student" labelText='Editer' href="#"
                                                wire:click='edit({{ $registration->student }})' class="text-primary" />
                                            <x-others.dropdown-link iconLink='bi bi-info-circle-fill'
                                                labelText='Voir détails' href="{{ route('student.detail', $registration) }}"
                                                class="text-primary" />
                                            <x-others.dropdown-link iconLink='bi bi-arrow-left-right'
                                                labelText='Basculuer la classe' data-bs-toggle="modal"
                                                data-bs-target="#form-change-class-student"
                                                wire:click='changeClassStudent({{ $registration }})' href="#"
                                                class="text-secondary" />
                                            <x-others.dropdown-link iconLink='bi bi-journal-x' labelText='Marquer abandon'
                                                data-bs-toggle="modal" data-bs-target="#form-give-up-student"
                                                wire:click='openMakeGiveUpStudentFom({{ $registration }})' href="#"
                                                class="text-secondary" />
                                            <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                                class="text-secondary" href="#"
                                                wire:click='showDeleteDialog({{ $registration->student }})' />
                                        </x-others.dropdown>
                                    @endcan

                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <span>{{ $registrations->links() }}</span>
                @if ($registrations->count() >= 10)
                    <x-others.table-page-number wire:model.live='per_page' />
                @endif
            </div>
        </div>
    </x-content.main-content-page>
    <livewire:application.student.form.form-edit-student-page />
    <livewire:application.registration.form.form-give-up-student-page />
    <livewire:application.registration.form.form-change-class-student-page />
</div>
