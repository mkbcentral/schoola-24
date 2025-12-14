<x-v2.table-card 
    title="Liste des rôles"
    icon="bi bi-shield"
    buttonText="Nouveau rôle"
    buttonClick="openCreateRole"
    buttonColor="warning">

    <!-- Filters -->
    <x-v2.table-filters
        searchModel="roleSearch"
        searchPlaceholder="Rechercher un rôle..."
        resetMethod="resetRoleFilters"
        perPageModel="rolePerPage">
    </x-v2.table-filters>

    <!-- Roles Table -->
    <x-v2.table-wrapper :modern="true">
        <thead>
            <tr>
                <th class="text-center" style="width: 60px;">#</th>
                <th>Nom du rôle</th>
                <th class="text-center">Nombre d'utilisateurs</th>
                <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $index => $role)
                <tr wire:key="role-{{ $role->id }}">
                    <td class="text-center">{{ $roles->firstItem() + $index }}</td>
                    <td><strong>{{ $role->name }}</strong></td>
                    <td class="text-center">
                        <span class="badge rounded-pill text-bg-info">
                            <i class="bi bi-people-fill me-1"></i>
                            {{ $role->users_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        <x-v2.action-dropdown :label="'Actions pour le rôle ' . $role->name">
                            <li>
                                <a class="dropdown-item" href="#" wire:click.prevent="editRole({{ $role->id }})">
                                    <i class="bi bi-pencil me-2"></i>Éditer
                                </a>
                            </li>
                            @if ($role->users_count === 0)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        wire:click.prevent="deleteRole({{ $role->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer ce rôle ?">
                                        <i class="bi bi-trash me-2"></i>Supprimer
                                    </a>
                                </li>
                            @else
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-muted disabled" href="#"
                                        title="Impossible de supprimer un rôle avec des utilisateurs">
                                        <i class="bi bi-trash me-2"></i>Supprimer ({{ $role->users_count }} utilisateurs)
                                    </a>
                                </li>
                            @endif
                        </x-v2.action-dropdown>
                    </td>
                </tr>
            @empty
                <x-v2.table-empty colspan="4" message="Aucun rôle trouvé" />
            @endforelse
        </tbody>
    </x-v2.table-wrapper>

    <!-- Pagination -->
    <x-v2.table-pagination :items="$roles" />

</x-v2.table-card>

<x-v2.table-styles />
