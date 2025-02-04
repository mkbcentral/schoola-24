<div>
    <x-navigation.bread-crumb icon='bi bi-houses-fill' label="Classes">
        <x-navigation.bread-crumb-item label='Classes' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card p-4">

            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between">
                        <div>
                            <x-others.list-title title='Liste des classes' icon='bi bi-card-checklist' />
                        </div>
                        <x-widget.loading-circular-md wire:loading />
                        <div class="d-flex align-items-center">
                            <x-form.label value="{{ __('Option') }}" class="me-2" />
                            <x-widget.data.list-option wire:model.live='option_filer' />
                        </div>
                    </div>
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="bg-app">
                            <tr>
                                <th class="text-center">#</th>
                                <th wire:click="sortData('class_rooms.name')" class="cursor-hand">
                                    Classe
                                    <x-form.sort-icon sortField="class_rooms.name" :sortAsc="$sortAsc"
                                        :sortBy="$sortBy" />
                                </th>
                                <th>Option</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($classRooms->isEmpty())
                                <tr>
                                    <td colspan="4"><x-errors.data-empty /></td>
                                </tr>
                            @else
                                @foreach ($classRooms as $index => $classRoom)
                                    <tr wire:key='{{ $classRoom->id }}'>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $classRoom->name }}

                                        </td>
                                        <td>{{ $classRoom?->option?->name }}</td>
                                        <td class="text-center">
                                            <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                                class="btn-sm btn-primary" wire:click='edit({{ $classRoom }})' />
                                            <x-form.app-button type='button' icon="bi bi-trash-fill"
                                                class="btn-secondary btn-sm" wire:confirm='Etês-vous sûr de supprimer ?'
                                                wire:click='delete({{ $classRoom }})' />
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>

                    </table>
                    <span>{{ $classRooms->links('livewire::bootstrap') }}</span>
                </div>
                <div class="col-md-6">
                    <livewire:application.config.form.form-class-room-page />
                </div>
            </div>
        </div>

    </x-content.main-content-page>
</div>
