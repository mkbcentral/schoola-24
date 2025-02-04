<div>
    <x-navigation.bread-crumb icon='bi bi-fingerprint' label="GESTION DES ROLES" color='text-secondary'>
        <x-navigation.bread-crumb-item label='Role' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card">
            <div class="row card-body">
                <div class="col-md-6">
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <x-others.list-title title='Liste des roles' icon='bi bi-card-checklist' />
                        </div>
                        <div class="mt-2">
                            <div class="d-flex justify-content-center pb-2">
                                <x-widget.loading-circular-md wire:loading />
                            </div>
                            <div class="d-flex">
                                <x-form.search-input wire:model.live='q' />
                            </div>
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="bg-app">
                                    <tr class="cursor-hand">
                                        <th class="text-center">#</th>
                                        <th>
                                            NOM COMPLET
                                        </th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($roles->isEmpty())
                                        <tr>
                                            <td colspan="5"><x-errors.data-empty /></td>
                                        </tr>
                                    @else
                                        @foreach ($roles as $index => $role)
                                            <tr wire:key='{{ $role->id }}'>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td class="text-center">
                                                    <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                                        class="btn-sm btn-primary"
                                                        wire:click='edit({{ $role }})' />
                                                    <x-form.app-button
                                                        wire:confirm="Est-vous sur de réaliser l'opération"
                                                        type='button' icon="bi bi-trash-fill" class="btn-danger btn-sm"
                                                        wire:click='delete({{ $role }})' />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $roles->links('livewire::bootstrap') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <livewire:application.admin.form.form-role-page />
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>
