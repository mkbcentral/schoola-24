<div>
    <x-navigation.bread-crumb icon='bi bi-person-gear' label="ADMINISTRATION">
        <x-navigation.bread-crumb-item label='Membres' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card card-outline card-indigo p-4">
            <x-others.list-title title=' Liste des utilisateurs' icon='bi bi-person-lines-fill' />
            <div class="mt-2">
                <div class="d-flex justify-content-center pb-2">
                    <x-widget.loading-circular-md wire:loading />
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <x-form.search-input wire:model.live='q' />
                    <x-form.app-button wire:click='addNewUser' textButton='Nouveau' icon="bi bi-plus-circle"
                        data-bs-toggle="modal" data-bs-target="#form-user" class="btn-primary" />
                </div>
                <table class="table table-bordered table-hover table-sm mt-2">
                    <thead class="bg-primary text-white">
                        <tr class="cursor-hand">
                            <th class="text-center">#</th>
                            <th class="text-center">AVATAR</th>
                            <th wire:click="sortData('name')">
                                <span>NOM COMPLET</span>
                                <x-form.sort-icon sortField="name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                            </th>
                            <th wire:click="sortData('name')">
                                <span>PSEUDO</span>
                                <x-form.sort-icon sortField="username" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                            </th>
                            <th wire:click="sortData('email')">
                                <span>EMAIL</span>
                                <x-form.sort-icon sortField="email" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                            </th>
                            <th wire:click="sortData('phone')">
                                <span>CONTACT</span>
                                <x-form.sort-icon sortField="phone" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                            </th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">ACTIVITE</th>
                            <th>ROLE</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="10"><x-errors.data-empty /></td>
                            </tr>
                        @else
                            @foreach ($users as $index => $user)
                                <tr wire:key='{{ $user->id }}'>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset($user->avatar == null ? 'images/defautl-user.jpg' : 'storage/' . $user->avatar) }}"
                                            alt="avatar-{{ $user->id }}" width="40px" height="40px"
                                            class="mr-2">
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td class="">{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $user->is_active == true ? 'text-bg-success' : 'text-bg-danger' }} ">
                                            {{ $user->is_active == true ? 'ACTIF' : 'NON ACTIF' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $user->is_on_line == true ? ' text-bg-primary' : 'text-bg-warning' }} ">
                                            {{ $user->is_on_line == true ? 'En ligne' : 'Hors ligne' }}
                                        </span>
                                    </td>
                                    <td>{{ $user?->role?->name }}</td>
                                    <td class="text-center">
                                        <x-others.dropdown icon="bi bi-three-dots-vertical"
                                            class="btn-outline-secondary btn-sm">
                                            <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer'
                                                href="#" data-bs-toggle="modal" data-bs-target="#form-user"
                                                wire:click='edit({{ $user }})' class="" />
                                            <x-others.dropdown-link wire:confirm="Est-vous sur de réaliser l'opération"
                                                iconLink="{{ $user->is_active == true ? 'bi bi-x-circle' : 'bi bi-check-lg' }}"
                                                labelText="{{ $user->is_active == true ? 'Déactiver' : 'Activer' }}"
                                                wire:click='activateUser({{ $user }})' href="#"
                                                class="{{ $user->is_active == true ? 'text-danger' : 'text-primary' }}" />
                                            <x-others.dropdown-link iconLink='bi bi-link'
                                                labelText='Attacher un simple menu'
                                                href="{{ route('admin.attach.single.menu', $user) }}" class="" />
                                            <x-others.dropdown-link iconLink='bi bi-link'
                                                labelText='Attacher un multi menu'
                                                href="{{ route('admin.attach.multi.menu', $user) }}" class="" />
                                            <x-others.dropdown-link iconLink='bi bi-link'
                                                labelText='Attacher un sous menu'
                                                href="{{ route('admin.attach.sub.menu', $user) }}" class="" />
                                            <x-others.dropdown-link iconLink='bi bi-key-fill'
                                                labelText='Réinitialiser le mot de passe' class="text-secondary"
                                                wire:confirm="Est-vous sur de réaliser l'opération" href="#"
                                                wire:click='resetUserPassword({{ $user }})' />
                                            <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                                class="text-secondary"
                                                wire:confirm="Est-vous sur de réaliser l'opération" href="#"
                                                wire:click='delete({{ $user }})' />
                                        </x-others.dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center">
                    <span>{{ $users->links('livewire::bootstrap') }}</span>
                    @if ($users->count() >= 10)
                        <x-others.table-page-number wire:model.live='per_page' />
                    @endif

                </div>
            </div>
        </div>
    </x-content.main-content-page>
    <livewire:application.admin.form.form-user-page />
</div>
