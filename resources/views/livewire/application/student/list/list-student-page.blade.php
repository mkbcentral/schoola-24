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
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <x-form.label value="{{ __('Option') }}" class="me-2" />
                        <x-widget.data.list-option type='text' wire:model.live='option_filter' :error="'form.option_id'" />
                    </div>
                    <x-form.app-button type='button' data-bs-toggle="modal" data-bs-target="#form-payment"
                        textButton='Mes paiements' icon="bi bi-list-check" class="app-btn ms-1" />
                </div>
            </div>
            <table class="table table-bordered table-sm table-hover mt-2">
                <thead class="table-primary">
                    <tr class="">
                        <th class="text-center">#</th>
                        <th class="text-center">N°</th>
                        <th class="">CODE</th>
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
                            <tr wire:key='{{ $registration->student->id }}' class=" ">
                                <td class="text-center {{ $registration->class_changed == true ? 'bg-danger' : '' }}">
                                    <x-form.input-check-box idAndFor="{{ $registration->id }}" color="primary"
                                        wire:model.live='selectedRegistrations' value='{{ $registration->id }}' />
                                </td>
                                <td class="text-center {{ $registration->abandoned == true ? 'bg-warning' : '' }}">
                                    {{ $index + 1 }}
                                </td>
                                <td class="text-uppercase {{ $registration->abandoned == true ? 'bg-warning' : '' }}">
                                    {{ $registration->code }}
                                </td>
                                <td>{{ $registration->student->name }}</td>
                                <td class="text-center">{{ $registration->student->getFormattedAg() }}</td>
                                <td class="text-center">{{ $registration->student->gender }}</td>
                                <td>{{ $registration?->classRoom?->getOriginalClassRoomName() }}
                                </td>
                                <td class="text-center">
                                    <x-form.app-button type='button' textButton="Payer"
                                        wire:click='openPaymentForm({{ $registration }})' icon="bi bi-arrow-left-right"
                                        class="btn-sm app-btn" data-bs-toggle="modal" data-bs-target="#form-payment" />
                                    <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                        class="btn-secondary btn-sm">
                                        <x-others.dropdown-link iconLink='bi bi-pencil-fill' data-bs-toggle="modal"
                                            data-bs-target="#form-edit-student" labelText='Editer' href="#"
                                            wire:click='edit({{ $registration->student }})' class="text-primary" />
                                        <x-others.dropdown-link iconLink='bi bi-info-circle-fill'
                                            labelText='Voir détails'
                                            href="{{ route('student.detail', $registration) }}" class="text-primary" />

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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <span>{{ $registrations->links('livewire::bootstrap') }}</span>
                @if ($registrations->count() > 9)
                    <x-others.table-page-number wire:model.live='per_page' />
                @endif
            </div>
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
    <livewire:application.payment.form.form-payment-page />
    <livewire:application.payment.list.list-payment-by-date-page>
</div>
