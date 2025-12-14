<div>
    <x-navigation.bread-crumb icon='bi bi-people' label="UTILISATEURS DE L'ÉCOLE">
        <x-navigation.bread-crumb-item label='{{ $school->name }}' />
        <x-navigation.bread-crumb-item label='Écoles' isLinked=true link="admin.schools.index" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="card card-outline card-info p-4">
            <x-others.list-title title='Utilisateurs de {{ $school->name }}' icon='bi bi-people-fill' />

            <div class="d-flex justify-content-center pb-2">
                <x-widget.loading-circular-md wire:loading />
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <x-form.search-input wire:model.live='q' placeholder="Rechercher un utilisateur..." />
                @can('createUser', $school)
                    <x-form.app-button wire:click='openCreateForm' textButton='Nouvel Utilisateur' 
                        icon="bi bi-plus-circle" class="btn-primary" />
                @endcan
            </div>

            <!-- Formulaire de création (modal ou inline) -->
            @if($showCreateForm)
                <div class="card mb-3 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-person-plus"></i> Créer un Nouvel Utilisateur</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent='createUser'>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom complet <span class="text-danger">*</span></label>
                                        <input type="text" wire:model='name' 
                                            class="form-control @error('name') is-invalid @enderror">
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom d'utilisateur <span class="text-danger">*</span></label>
                                        <input type="text" wire:model='username' 
                                            class="form-control @error('username') is-invalid @enderror">
                                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" wire:model='email' 
                                            class="form-control @error('email') is-invalid @enderror">
                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Téléphone</label>
                                        <input type="text" wire:model='phone' class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Rôle <span class="text-danger">*</span></label>
                                        <select wire:model='role_id' 
                                            class="form-control @error('role_id') is-invalid @enderror">
                                            <option value="">-- Sélectionner un rôle --</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            @if($tempPassword)
                                <div class="alert alert-success">
                                    <strong>Mot de passe généré : </strong> {{ $tempPassword }}
                                </div>
                            @endif

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" wire:click='closeCreateForm' class="btn btn-secondary">
                                    Annuler
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Créer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Liste des utilisateurs -->
            <table class="table table-bordered table-hover table-sm">
                <thead class="bg-info text-white">
                    <tr>
                        <th class="text-center">#</th>
                        <th>NOM</th>
                        <th>USERNAME</th>
                        <th>EMAIL</th>
                        <th>TÉLÉPHONE</th>
                        <th>RÔLE</th>
                        <th class="text-center">STATUT</th>
                        <th class="text-center">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="8"><x-errors.data-empty /></td>
                        </tr>
                    @else
                        @foreach ($users as $index => $user)
                            <tr wire:key='user-{{ $user->id }}'>
                                <td class="text-center">{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ $user->role->name }}</span>
                                </td>
                                <td class="text-center">
                                    @if($user->is_active)
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-danger">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @can('updateUser', [App\Models\School::class, $user])
                                            <button wire:click='toggleUserStatus({{ $user->id }})' 
                                                class="btn btn-sm btn-warning" 
                                                title="Activer/Désactiver"
                                                wire:confirm="Changer le statut de {{ $user->name }}?">
                                                <i class="bi bi-toggle-on"></i>
                                            </button>

                                            <button wire:click='resetUserPassword({{ $user->id }})' 
                                                class="btn btn-sm btn-info" 
                                                title="Réinitialiser le mot de passe"
                                                wire:confirm="Réinitialiser le mot de passe de {{ $user->name }}?">
                                                <i class="bi bi-key"></i>
                                            </button>
                                        @endcan

                                        @can('deleteUser', [App\Models\School::class, $user])
                                            <button wire:click='deleteUser({{ $user->id }})' 
                                                class="btn btn-sm btn-danger" 
                                                title="Supprimer"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer {{ $user->name }}?">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </x-content.main-content-page>
</div>
