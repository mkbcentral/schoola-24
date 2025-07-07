<div>
    <x-navigation.bread-crumb icon='bi bi-person-vcard-fill' label="Gestionnaire responsable">
        <x-navigation.bread-crumb-item label='Gestionnaire responsable' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="mt-2">
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading />
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    <x-form.search-input wire:model.live='q' />
                    <x-form.app-button type='button' wire:click='refreshData' textButton=''
                        icon="bi bi-arrow-clockwise" class="btn-primary ms-1" />
                </div>
                <x-form.app-button wire:click='openNewResponsibleStudent' data-bs-toggle="modal"
                    data-bs-target="#form-responsible-student" textButton='Nouveau responsable' icon="bi bi-plus-circle"
                    class="btn-primary" />
            </div>
            <table class="table table-bordered table-hover table-sm mt-2">
                <thead class="text-bg-primary">
                    <tr class="cursor-hand">
                        <th class="text-center">#</th>
                        <th wire:click="sortData('name')">
                            <span>NOM COMPLET</span>
                            <x-form.sort-icon sortField="name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                        </th>
                        <th class="text-center">NBRE ELEVES</th>
                        <th>CONTACT</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($responsibleStudents->isEmpty())
                        <tr>
                            <td colspan="5"><x-errors.data-empty /></td>
                        </tr>
                    @else
                        @foreach ($responsibleStudents as $index => $responsibleStudent)
                            <tr wire:key='{{ $responsibleStudent->id }}'>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $responsibleStudent->name }}</td>
                                <td class="text-center">{{ $responsibleStudent->students->count() }}</td>
                                <td>{{ $responsibleStudent->phone . ' | ' . $responsibleStudent->other_phone }}
                                </td>
                                <td class="text-center">
                                    <x-form.app-button type='button' icon="bi bi-person-fill-add"
                                        class="btn-sm btn-primary"
                                        wire:click='addNewInscription({{ $responsibleStudent }})' data-bs-toggle="modal"
                                        data-bs-target="#form-student" />
                                    <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                        class="btn-secondary btn-sm">
                                        <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer'
                                            data-bs-toggle="modal" data-bs-target="#form-responsible-student"
                                            href="#" wire:click='edit({{ $responsibleStudent }})'
                                            class="text-primary" />
                                        <x-others.dropdown-link iconLink='bi bi-info-circle-fill' labelText='Voir infos'
                                            data-bs-toggle="modal" data-bs-target="#list-student-by-responsible"
                                            wire:click='getListStudent({{ $responsibleStudent }})' href="#"
                                            class="text-primary" />
                                        <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                            class="text-secondary" href="#"
                                            wire:click='showDeleteDialog({{ $responsibleStudent }})' />
                                    </x-others.dropdown>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
            @if (!$responsibleStudents->isEmpty())
                <div class="d-flex justify-content-between align-items-center">
                    <span>{{ $responsibleStudents->links('livewire::bootstrap') }}</span>
                    <x-others.table-page-number wire:model.live='per_page' />
                </div>
            @endif
        </div>
        @push('js')
            <script type="module">
                //Confirmation dialog for delete product
                window.addEventListener('delete-responsibleStudent-dialog', event => {
                    Swal.fire({
                        title: 'Voulez-vous vraimant ',
                        text: "Retirer monsieur " + event.detail[0]['name'] + "?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Non'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('deletedResponsibleStudentListner');
                        }
                    })
                });
                window.addEventListener('responsibleStudent-deleted', event => {
                    Swal.fire(
                        'Suppression',
                        event.detail[0].message,
                        'success'
                    );
                });
                window.addEventListener('delete-responsibleStudent-failed', event => {
                    Swal.fire(
                        'Suppression',
                        event.detail[0].message,
                        'error'
                    );
                });
            </script>
        @endpush
        <livewire:application.student.form.form-responsible-student-page />
        <livewire:application.student.form.form-student-page />
        <livewire:application.student.list.list-student-by-responsible-page />
    </x-content.main-content-page>
</div>
