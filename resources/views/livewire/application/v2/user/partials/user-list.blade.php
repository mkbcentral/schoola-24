<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-people me-2"></i>
            Liste des utilisateurs
        </h5>
        <button wire:click="openCreateUser" class="btn btn-primary ">
            <i class="bi bi-plus-circle me-1"></i>
            Nouvel utilisateur
        </button>
    </div>

    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-4">
                <x-form.search-input wire:model.live.debounce.300ms="userSearch"
                    placeholder="Rechercher un utilisateur..." />
            </div>
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
            <div class="col-md-2">
                <button wire:click="resetUserFilters" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Réinitialiser
                </button>
            </div>
            <div class="col-md-2">
                <select wire:model.live="userPerPage" class="form-select">
                    <option value="10">10 par page</option>
                    <option value="15">15 par page</option>
                    <option value="25">25 par page</option>
                    <option value="50">50 par page</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <div wire:loading.remove class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>

                        <th wire:click="sortUserData('name')" style="cursor: pointer;">
                            Nom complet
                            @if ($userSortBy === 'name')
                                <i class="bi bi-{{ $userSortAsc ? 'sort-up' : 'sort-down' }}"></i>
                            @endif
                        </th>
                        <th wire:click="sortUserData('username')" style="cursor: pointer;">
                            Pseudo
                            @if ($userSortBy === 'username')
                                <i class="bi bi-{{ $userSortAsc ? 'sort-up' : 'sort-down' }}"></i>
                            @endif
                        </th>
                        <th wire:click="sortUserData('email')" style="cursor: pointer;">
                            Email
                            @if ($userSortBy === 'email')
                                <i class="bi bi-{{ $userSortAsc ? 'sort-up' : 'sort-down' }}"></i>
                            @endif
                        </th>
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
                                <span
                                    class="badge rounded-pill {{ $user->is_on_line ? 'text-bg-primary' : 'text-bg-warning' }}">
                                    <i class="bi bi-{{ $user->is_on_line ? 'circle-fill' : 'circle' }} me-1"></i>
                                    {{ $user->is_on_line ? 'En ligne' : 'Hors ligne' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                        aria-label="Actions pour {{ $user->name }}">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                wire:click.prevent="editUser({{ $user->id }})">
                                                <i class="bi bi-pencil me-2"></i>Éditer
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                wire:click.prevent="$dispatch('reset-password-dialog', { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email ?? '') }}' })">
                                                <i class="bi bi-key me-2"></i>Réinitialiser mot de passe
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                                wire:click.prevent="$dispatch('delete-user-dialog', { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}' })">
                                                <i class="bi bi-trash me-2"></i>Supprimer
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted text-center">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Aucun utilisateur trouvé
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if (method_exists($users, 'hasPages') && $users->hasPages())
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

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
                    Livewire.on('success-message', (event) => {
                        const message = event.message || 'Opération réussie !';
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,

                            theme: 'auto',
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: message
                        });
                    });

                    // Écouter l'événement d'erreur
                    Livewire.on('error-message', (event) => {
                        const message = event.message || 'Une erreur est survenue';
                        Toast.fire({
                            icon: "error",
                            title: message
                        });
                    });
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
                    Livewire.on('success-message', (event) => {
                        const message = event.message || 'Opération réussie !';
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            theme: 'auto',
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: message
                        });
                    });

                    // Écouter l'événement d'erreur
                    Livewire.on('error-message', (event) => {
                        const message = event.message || 'Une erreur est survenue';
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            theme: 'auto',
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "warning",
                            title: message
                        });
                    });
                }
            });
        });    
    </script>
@endscript

<style>
    .status-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .status-toggle-checkbox {
        display: none;
    }

    .status-toggle-label {
        width: 44px;
        height: 24px;
        background: #ccc;
        border-radius: 12px;
        position: relative;
        cursor: pointer;
        transition: background 0.2s ease;
        margin: 0;
    }

    .status-toggle-label::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        background: white;
        border-radius: 50%;
        top: 3px;
        left: 3px;
        transition: transform 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .status-toggle-checkbox:checked + .status-toggle-label {
        background: #198754;
    }

    .status-toggle-checkbox:checked + .status-toggle-label::after {
        transform: translateX(20px);
    }

    .status-toggle-label:hover {
        opacity: 0.9;
    }
</style>
