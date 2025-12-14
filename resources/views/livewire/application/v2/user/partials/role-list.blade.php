<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-shield me-2"></i>
            Liste des rôles
        </h5>
        <button wire:click="openCreateRole" class="btn btn-warning">
            <i class="bi bi-plus-circle me-1"></i>
            Nouveau rôle
        </button>
    </div>

    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-5">
                <x-form.search-input wire:model.live.debounce.300ms="roleSearch" 
                    placeholder="Rechercher un rôle..." />
            </div>
            <div class="col-md-3">
                <select wire:model.live="roleForSchoolFilter" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="1">Pour l'école</option>
                    <option value="0">Pour l'application</option>
                </select>
            </div>
            <div class="col-md-2">
                <button wire:click="resetRoleFilters" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Réinitialiser
                </button>
            </div>
            <div class="col-md-2">
                <select wire:model.live="rolePerPage" class="form-select">
                    <option value="10">10 par page</option>
                    <option value="15">15 par page</option>
                    <option value="25">25 par page</option>
                    <option value="50">50 par page</option>
                </select>
            </div>
        </div>
        <!-- Roles Table -->
        <div wire:loading.remove class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-warning">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Nom du rôle</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Nombre d'utilisateurs</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $index => $role)
                        <tr wire:key="role-{{ $role->id }}">
                            <td class="text-center">{{ $roles->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $role->name }}</strong>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge rounded-pill {{ $role->is_for_school ? 'text-bg-success' : 'text-bg-primary' }}">
                                    <i class="bi bi-{{ $role->is_for_school ? 'building' : 'globe' }} me-1"></i>
                                    {{ $role->is_for_school ? 'École' : 'Application' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill text-bg-info">
                                    <i class="bi bi-people-fill me-1"></i>
                                    {{ $role->users_count }}
                                    <span
                                        class="visually-hidden">utilisateur{{ $role->users_count > 1 ? 's' : '' }}</span>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                        aria-label="Actions pour le rôle {{ $role->name }}">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                wire:click.prevent="editRole({{ $role->id }})">
                                                <i class="bi bi-pencil me-2"></i>Éditer
                                            </a>
                                        </li>
                                        @if ($role->users_count === 0)
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#"
                                                    wire:click.prevent="deleteRole({{ $role->id }})"
                                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce rôle ?">
                                                    <i class="bi bi-trash me-2"></i>Supprimer
                                                </a>
                                            </li>
                                        @else
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-muted disabled" href="#"
                                                    title="Impossible de supprimer un rôle avec des utilisateurs">
                                                    <i class="bi bi-trash me-2"></i>Supprimer ({{ $role->users_count }}
                                                    utilisateurs)
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Aucun rôle trouvé
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if (method_exists($roles, 'hasPages') && $roles->hasPages())
            <div class="mt-3">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
</div>
