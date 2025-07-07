<div>
    @php
        $label = $isOld ? 'Réincriptions' : 'Incriptions';
        $labelByDate = $label . ' par date';
    @endphp
    <div class="mt-2">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <input type="date" class="form-control form-control-sm me-2" wire:model.live="dateFilter"
                style="max-width: 180px;" />
            <x-form.search-input wire:model.live='q' />

            <div class="d-flex align-items-center">
                <x-form.label :value="__('Option')" class="me-2" />
                <x-widget.data.list-option type='text' wire:model.live='option_filter' :error="'form.option_id'" />
                <x-others.dropdown wire:ignore.self icon="bi bi-printer-fill" class="btn-secondary btn-sm ms-2">
                    <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer'
                        href="{{ route('print.students.by.date', [$dateFilter, $isOld ?? 0, $sortAsc]) }}"
                        target='_blank' />
                </x-others.dropdown>
            </div>
        </div>

        <h6>(Total: {{ $count }} {{ $count <= 1 ? 'Elève' : 'Elèves' }})</h6>

        <div class="table-responsive">
            <table class="table table-hover mt-2">
                <thead class="theme-dark">
                    <tr class="cursor-hand">
                        <th class="text-center">#</th>
                        <th>CODE</th>
                        <th wire:click="sortData('name')">
                            <span>NOM COMPLET</span>
                            <x-form.sort-icon sortField="name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                        </th>
                        <th>CLASSE</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registrations as $index => $registration)
                        <tr wire:key='{{ $registration->id }}'
                            oncontextmenu="event.preventDefault(); showContextMenu(event, {{ $registration->id }});"
                            data-registration-id="{{ $registration->id }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-uppercase">{{ $registration->code }}</td>
                            <td>{{ $registration->student->name }}</td>
                            <td>{{ $registration->classRoom->getOriginalClassRoomName() }}</td>
                            <td class="text-center">
                                <span class="badge text-bg-{{ $registration->is_old ? 'warning' : 'info' }}">
                                    {{ $registration->is_old ? 'Ancien' : 'Nouveau' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-secondary btn-sm">
                                    <x-others.dropdown-link iconLink='bi bi-info-circle-fill' labelText='Voir détails'
                                        href="{{ route('student.detail', $registration) }}" />
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
                                </x-others.dropdown>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"><x-errors.data-empty /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Menu contextuel personnalisé (affiché uniquement dans la table) -->
            @if ($registrations->count() > 0)
                <div id="contextMenu" style="display:none; position:fixed; z-index:9999; min-width:200px;"
                    class="card shadow-sm">
                    <ul class="list-group list-group-flush mb-0">
                        <li class="list-group-item" id="ctx-details" style="cursor:pointer;"><i
                                class="bi bi-info-circle-fill me-2"></i>Voir
                            détails
                        </li>
                        @can('manage-student')
                            <li class="list-group-item" id="ctx-edit" style="cursor:pointer;"><i
                                    class="bi bi-pencil-fill me-2"></i>Editer</li>
                            <li class="list-group-item" id="ctx-change-class" style="cursor:pointer;"><i
                                    class="bi bi-arrow-left-right me-2"></i>Basculuer la classe</li>
                            <li class="list-group-item" id="ctx-give-up" style="cursor:pointer;"><i
                                    class="bi bi-journal-x me-2"></i>Marquer abandon
                            </li>
                            <li class="list-group-item" id="ctx-delete" style="cursor:pointer;"><i
                                    class="bi bi-trash-fill me-2"></i>Supprimer</li>
                        @endcan
                    </ul>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <span>{{ $registrations->links('livewire::bootstrap') }}</span>
            @if ($registrations->count() >= 10)
                <x-others.table-page-number wire:model.live='per_page' />
            @endif
        </div>
    </div>
    <livewire:application.student.form.form-edit-student-page />
    <livewire:application.registration.form.form-give-up-student-page />
    <livewire:application.registration.form.form-change-class-student-page />
    <script>
        let currentRegistrationId = null;

        function showContextMenu(event, registrationId) {
            currentRegistrationId = registrationId;
            const menu = document.getElementById('contextMenu');
            const menuWidth = menu.offsetWidth || 200;
            const menuHeight = menu.offsetHeight || 180;
            let x = event.clientX;
            let y = event.clientY;

            // Ajustement horizontal
            if (x + menuWidth > window.innerWidth) {
                x = window.innerWidth - menuWidth - 10;
            }
            // Ajustement vertical
            if (y + menuHeight > window.innerHeight) {
                y = window.innerHeight - menuHeight - 10;
            }

            menu.style.display = 'block';
            menu.style.left = x + 'px';
            menu.style.top = y + 'px';
        }
        document.addEventListener('click', function() {
            document.getElementById('contextMenu').style.display = 'none';
        });

        // Actions context menu
        document.getElementById('ctx-details').onclick = function(e) {
            if (currentRegistrationId) {
                window.location.href = "{{ route('student.detail', ':id') }}".replace(':id', currentRegistrationId);
            }
        };
        @can('manage-student')
            document.getElementById('ctx-edit').onclick = function(e) {
                if (currentRegistrationId) {
                    Livewire.emit('edit', currentRegistrationId);
                    $('#form-edit-student').modal('show');
                }
            };
            document.getElementById('ctx-change-class').onclick = function(e) {
                if (currentRegistrationId) {
                    Livewire.emit('changeClassStudent', currentRegistrationId);
                    $('#form-change-class-student').modal('show');
                }
            };
            document.getElementById('ctx-give-up').onclick = function(e) {
                if (currentRegistrationId) {
                    Livewire.emit('openMakeGiveUpStudentFom', currentRegistrationId);
                    $('#form-give-up-student').modal('show');
                }
            };
            document.getElementById('ctx-delete').onclick = function(e) {
                if (currentRegistrationId) {
                    Livewire.emit('showDeleteDialog', currentRegistrationId);
                }
            };
        @endcan
    </script>
</div>
