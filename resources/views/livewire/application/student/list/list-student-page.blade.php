<div>
    <x-navigation.bread-crumb icon='bi bi-people-fill' label="Getionnaire élèves" color=''>
        <x-navigation.bread-crumb-item label='Getionnaire élèves' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="">
            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading />
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <x-form.search-input wire:model.live='q' />
                    <x-form.app-button type='button' wire:click='refreshData' textButton=''
                        icon="bi bi-arrow-clockwise" class="app-btn ms-1" />
                    @if ($selectedRegistrations != [])
                        <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical" class="btn-secondary ms-2">
                            <x-others.dropdown-link wire:click='generateQrcodeItems'
                                wire:confirm="Etês-vous sûre de réaliser l'opération" iconLink='bi bi-qr-code-scan'
                                labelText='Générer Qrcode' href="#" />
                        </x-others.dropdown>
                    @endif
                </div>
                <div>
                    <h4> Nbre Total: {{ $counter <= 1 ? $counter . 'Elève' : $counter . ' Elèves' }}</h4>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <x-form.label value="{{ __('Option') }}" class="me-2" />
                        <x-widget.data.list-option type='text' wire:model.live='option_filter' :error="'form.option_id'" />
                    </div>
                    <div class="d-flex align-items-center">
                        <x-form.label value="{{ __('Classe') }}" class="me-2" />
                        <x-widget.data.list-class-room-by-option optionId='{{ $selectedOptionId }}'
                                                                 wire:model.live='class_room_filter' />
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-sm table-hover mt-2">
                <thead class="table-primary">
                    <tr class="">
                        <th class="text-center">
                            <x-form.input-check-box
                                idAndFor="select-all" wire:model.live='selectPageRows'
                                color="primary" value='' />
                        </th>
                        <th class="text-center">N°</th>
                        <th wire:click="sortData('registrations.code')" class="cursor-hand">
                            <span>CODE</span>
                            <x-form.sort-icon sortField="registrations.code" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                        </th>
                        <th wire:click="sortData('students.name')" class="cursor-hand">
                            <span>NOM COMPLET</span>
                            <x-form.sort-icon sortField="students.name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                        </th>
                        <th class="text-center cursor-hand" wire:click="sortData('students.date_of_birth')">
                            <span>AGE</span>
                            <x-form.sort-icon sortField="students.date_of_birth" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                        </th>
                        <th class="text-center">GENRE</th>
                        <th>CLASSE</th>
                        <th class="text-center"> STATUS</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if ($registrations->count()==0)
                    <tr>
                        <td colspan="9">
                            <x-errors.data-empty/>
                        </td>
                    </tr>
                @else
                    @foreach ($registrations as $index => $registration)
                        <tr wire:key='{{ $registration->student->id }}' class=" ">
                            <td class="text-center {{ $registration->class_changed == true ? 'bg-danger' : '' }}">
                                <x-form.input-check-box idAndFor="{{ $registration->id }}" color="primary"
                                                        wire:model.live='selectedRegistrations' value='{{ $registration->id }}' />
                            </td>
                            <td class="text-center {{ $registration->abandoned == true ? 'bg-warning' : '' }}">
                                {{ $index + 1 }}
                            </td>
                            <td class="text-uppercase {{ $registration->abandoned == true ? 'bg-warning' : '' }}">
                                @if ($registration->qr_code)
                                    <i class="bi bi-qr-code"></i>
                                @endif
                                {{ $registration->code }}
                            </td>
                            <td>

                                {{ $registration->student->name }}
                            </td>
                            <td class="text-center">{{ $registration->student->getFormattedAg() }}</td>
                            <td class="text-center">{{ $registration->student->gender }}</td>
                            <td>{{ $registration?->classRoom?->getOriginalClassRoomName() }}
                            </td>
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
                                                                wire:click='edit({{ $registration->student }})' />
                                        <x-others.dropdown-link iconLink='bi bi-arrow-left-right'
                                                                labelText='Basculuer la classe' data-bs-toggle="modal"
                                                                data-bs-target="#form-change-class-student"
                                                                wire:click='changeClassStudent({{ $registration }})' href="#" />
                                        <x-others.dropdown-link iconLink='bi bi-journal-x' labelText='Marquer abandon'
                                                                data-bs-toggle="modal" data-bs-target="#form-give-up-student"
                                                                wire:click='openMakeGiveUpStudentFom({{ $registration }})' href="#" />
                                        <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                                                href="#" wire:click='showDeleteDialog({{ $registration->student }})' />
                                    @endcan
                                    <x-others.dropdown-link iconLink='bi bi-info-circle-fill' labelText='Voir détails'
                                                            href="{{ route('student.detail', $registration) }}" />
                                    @if (!$registration->qr_code)
                                        <x-others.dropdown-link wire:click='generateQRCode({{ $registration }})'
                                                                wire:confirm="Etês-vous sûre de réaliser l'opération"
                                                                iconLink='bi bi-qr-code-scan' labelText='Générer Qrcode' href="#" />
                                    @endif
                                </x-others.dropdown>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            @if($registrations->count()>9)
                <div class="d-flex justify-content-between align-items-center">
                    <span>{{ $registrations->links('livewire::bootstrap') }}</span>
                    <x-others.table-page-number wire:model.live='per_page' />
                </div>
            @endif
        </div>
    </x-content.main-content-page>
    @push('js')
        <script type="module">
            //Confirmation dialog for delete product
            window.addEventListener('delete-student-dialog', event => {
                Swal.fire({
                    title: 'Voulez-vous vraimant ',
                    text: "Retirer l'élève " + event.detail[0]['name'] + "?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Non'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deletedStudentListner');
                    }
                })
            });
            window.addEventListener('student-deleted', event => {
                Swal.fire(
                    'Suppression',
                    event.detail[0].message,
                    'success'
                );
            });
            window.addEventListener('delete-student-failed', event => {
                Swal.fire(
                    'Suppression',
                    event.detail[0].message,
                    'error'
                );
            });
        </script>
    @endpush

    <livewire:application.student.form.form-edit-student-page />
    <livewire:application.registration.form.form-give-up-student-page />
    <livewire:application.registration.form.form-change-class-student-page />
</div>
