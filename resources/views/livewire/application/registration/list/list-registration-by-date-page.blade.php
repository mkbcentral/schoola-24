<div>
    @php
        $label = $isOld ? 'Réincriptions' : 'Incriptions';
        $labelByDate = $label . ' par date';
    @endphp
    <x-navigation.bread-crumb icon='bi bi-person-vcard-fill' label="Inscriptio par jour">
        <x-navigation.bread-crumb-item label='Gestionnaire inscriptions' />
        <x-navigation.bread-crumb-item label='Nouvelle inscription' isLinked=true link="responsible.main" />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div>
            <div class="card mt-2">
                <div class="card-body d-flex align-items-center flex-wrap gap-2">
                    <input type="date" class="form-control form-control-sm" wire:model.live="dateFilter"
                        style="max-width: 120px;" />
                    <h6 class="mb-0 ms-2">
                        <span class="badge bg-primary">
                            Total: {{ $count }} {{ $count <= 1 ? 'Elève' : 'Elèves' }}
                        </span>
                    </h6>
                    <div class="btn-group ms-2" role="group" aria-label="Type d'inscription">
                        <button type="button"
                            class="btn btn-sm {{ is_null($isOld) ? 'btn-primary active shadow-sm' : 'btn-outline-primary' }}"
                            style="border-radius: 20px 0 0 20px; padding: 0.25rem 0.75rem; font-weight: 500; min-width: unset;"
                            wire:click="$set('isOld', null)">
                            Tout
                        </button>
                        <button type="button"
                            class="btn btn-sm {{ $isOld === true ? 'btn-success active shadow-sm' : 'btn-outline-success' }}"
                            style="border-radius: 0; padding: 0.25rem 0.75rem; font-weight: 500; min-width: unset;"
                            wire:click="$set('isOld', true)">
                            Anciens
                        </button>
                        <button type="button"
                            class="btn btn-sm {{ $isOld === false ? 'btn-info active shadow-sm text-white' : 'btn-outline-info' }}"
                            style="border-radius: 0 20px 20px 0; padding: 0.25rem 0.75rem; font-weight: 500; min-width: unset;"
                            wire:click="$set('isOld', false)">
                            Nouveau
                        </button>
                    </div>
                    <div class="ms-2" style="min-width: 200px;">
                        <x-form.search-input wire:model.live='q' />
                    </div>
                    <div class="d-flex align-items-center ms-2 gap-2" style="min-width: 250px;">
                        <x-form.label :value="__('Option')" class="me-2 mb-0" />
                        <x-widget.data.list-option type='text' wire:model.live='option_filter' :error="'form.option_id'" />
                        <x-others.dropdown wire:ignore.self icon="bi bi-printer-fill">
                            <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer'
                                href="{{ route('print.students.by.date', [$dateFilter, $isOld ?? 0, $sortAsc]) }}"
                                target='_blank' />
                        </x-others.dropdown>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mt-2">
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
                                            class="btn-sm">
                                            <x-others.dropdown-link iconLink='bi bi-info-circle-fill'
                                                labelText='Voir détails'
                                                href="{{ route('student.detail', $registration) }}" />
                                            @can('manage-student')
                                                <x-others.dropdown-link iconLink='bi bi-pencil-fill' data-bs-toggle="modal"
                                                    data-bs-target="#form-edit-student" labelText='Editer' href="#"
                                                    wire:click='edit({{ $registration->student }})' />
                                                <x-others.dropdown-link iconLink='bi bi-arrow-left-right'
                                                    labelText='Basculuer la classe' data-bs-toggle="modal"
                                                    data-bs-target="#form-change-class-student"
                                                    wire:click='changeClassStudent({{ $registration }})' href="#" />
                                                <x-others.dropdown-link iconLink='bi bi-journal-x'
                                                    labelText='Marquer abandon' data-bs-toggle="modal"
                                                    data-bs-target="#form-give-up-student"
                                                    wire:click='openMakeGiveUpStudentFom({{ $registration }})'
                                                    href="#" />
                                                <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                                    href="#"
                                                    wire:click='showDeleteDialog({{ $registration->student }})' />
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

        </div>
    </x-content.main-content-page>
</div>
