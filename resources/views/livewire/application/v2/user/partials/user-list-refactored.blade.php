<x-v2.table-card 
    title="Liste des utilisateurs"
    icon="bi bi-people"
    buttonText="Nouvel utilisateur"
    buttonClick="openCreateUser"
    buttonColor="primary">

    <!-- Filters -->
    <x-v2.table-filters
        searchModel="userSearch"
        searchPlaceholder="Rechercher un utilisateur..."
        resetMethod="resetUserFilters"
        perPageModel="userPerPage">
        
        <x-slot:filters>
            <div class="col-md-2">
                <select wire:model.live="userStatusFilter" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>
            </div>
            <div class="col-md-2">
                <select wire:model.live="userActivityFilter" class="form-select">
                    <option value="">Toutes activités</option>
                    <option value="1">En ligne</option>
                    <option value="0">Hors ligne</option>
                </select>
            </div>
        </x-slot:filters>
    </x-v2.table-filters>

    <!-- Users Table -->
    <x-v2.table-wrapper :modern="true">
        <thead>
            <tr>
                <th class="text-center" style="width: 60px;">#</th>
                <x-v2.sortable-header field="name" :sortBy="$userSortBy" :sortAsc="$userSortAsc" method="sortUserData">
                    Nom complet
                </x-v2.sortable-header>
                <x-v2.sortable-header field="username" :sortBy="$userSortBy" :sortAsc="$userSortAsc" method="sortUserData">
                    Pseudo
                </x-v2.sortable-header>
                <x-v2.sortable-header field="email" :sortBy="$userSortBy" :sortAsc="$userSortAsc" method="sortUserData">
                    Email
                </x-v2.sortable-header>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th class="text-center">Statut</th>
                <th class="text-center">Activité</th>
                <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr wire:key="user-{{ $user->id }}">
                    <td class="text-center">{{ $users->firstItem() + $index }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        <span class="badge text-bg-info rounded-pill">
                            <i class="bi bi-shield-check me-1"></i>{{ $user->role?->name }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="status-toggle">
                            <input type="checkbox" 
                                class="status-toggle-checkbox" 
                                id="status-{{ $user->id }}"
                                wire:click="toggleUserStatus({{ $user->id }})"
                                {{ $user->is_active ? 'checked' : '' }}>
                            <label class="status-toggle-label" for="status-{{ $user->id }}"></label>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge rounded-pill {{ $user->is_on_line ? 'text-bg-primary' : 'text-bg-warning' }}">
                            <i class="bi bi-{{ $user->is_on_line ? 'circle-fill' : 'circle' }} me-1"></i>
                            {{ $user->is_on_line ? 'En ligne' : 'Hors ligne' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <x-v2.action-dropdown :label="'Actions pour ' . $user->name">
                            <li>
                                <a class="dropdown-item" href="#" wire:click.prevent="editUser({{ $user->id }})">
                                    <i class="bi bi-pencil me-2"></i>Éditer
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    wire:click.prevent="$dispatch('reset-password-dialog', { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email ?? '') }}' })">
                                    <i class="bi bi-key me-2"></i>Réinitialiser mot de passe
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#"
                                    wire:click.prevent="$dispatch('delete-user-dialog', { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}' })">
                                    <i class="bi bi-trash me-2"></i>Supprimer
                                </a>
                            </li>
                        </x-v2.action-dropdown>
                    </td>
                </tr>
            @empty
                <x-v2.table-empty colspan="9" message="Aucun utilisateur trouvé" />
            @endforelse
        </tbody>
    </x-v2.table-wrapper>

    <!-- Pagination -->
    <x-v2.table-pagination :items="$users" />

</x-v2.table-card>

@script
    <script>
        // Confirmation de réinitialisation de mot de passe avec SweetAlert
        $wire.on('reset-password-dialog', (event) => {
            const data = event;
            Swal.fire({
                title: 'Réinitialiser le mot de passe?',
                html: `<div class="text-start">
                <p class="mb-2"><strong>Utilisateur:</strong> ${data.name}</p>
                <p class="mb-2"><strong>Email:</strong> ${data.email || 'Non défini'}</p>
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    Le mot de passe sera réinitialisé à: <strong>password</strong>
                </div>
                <p class="text-danger mt-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    L'utilisateur devra changer son mot de passe à la prochaine connexion.
                </p>
            </div>`,
                icon: 'warning',
                theme: 'auto',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-key me-2"></i>Oui, réinitialiser',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.resetUserPassword(data.id);
                }
            });
        });

        // Confirmation de suppression avec SweetAlert
        $wire.on('delete-user-dialog', (event) => {
            const data = event;
            Swal.fire({
                title: 'Supprimer cet utilisateur?',
                html: `<div class="text-start">
                <p class="mb-2"><strong>Utilisateur:</strong> ${data.name}</p>
                <div class="alert alert-danger mt-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Cette action est <strong>irréversible</strong>. Toutes les données associées à cet utilisateur seront supprimées.
                </div>
            </div>`,
                icon: 'warning',
                theme: 'auto',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-2"></i>Oui, supprimer',
                cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteUser(data.id);
                }
            });
        });
    </script>
@endscript
<x-v2.table-styles />
