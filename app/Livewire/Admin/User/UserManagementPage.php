<?php

namespace App\Livewire\Admin\User;

use App\DTOs\User\UserFilterDTO;
use App\Services\UserService;
use App\Services\RoleService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagementPage extends Component
{
    use WithPagination;

    public string $activeTab = 'users';

    // User Filters
    public ?string $userSearch = '';
    public ?string $userStatusFilter = null;
    public ?string $userActivityFilter = null;
    public string $userSortBy = 'name';
    public bool $userSortAsc = true;
    public int $userPerPage = 15;

    // Role Filters
    public ?string $roleSearch = '';
    public ?bool $roleForSchoolFilter = null;
    public int $rolePerPage = 15;

    protected $listeners = [
        'user-saved' => 'refreshData',
        'role-saved' => 'refreshData',
    ];

    public function mount() {}

    public function refreshData()
    {
        $this->resetPage();
    }

    public function setActiveTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // User methods
    public function openCreateUser()
    {
        $this->dispatch('open-create-user');
    }

    public function editUser(int $id)
    {
        $this->dispatch('edit-user', id: $id);
    }

    public function toggleUserStatus(int $userId, UserService $userService)
    {
        try {
            $user = $userService->findById($userId);
            
            if (!$user) {
                $this->dispatch('error-message', message: 'Utilisateur introuvable');
                return;
            }

            // Utiliser directement le repository pour la mise à jour simple
            $user->is_active = !$user->is_active;
            $user->save();
            
            $this->dispatch('success-message', message: 'Statut modifié avec succès');
            $this->refreshData();
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    public function resetUserPassword(int $userId, UserService $userService)
    {
        try {
            $defaultPassword = 'password';
            $result = $userService->resetPassword($userId, $defaultPassword);

            if ($result) {
                $this->dispatch('success-message', message: 'Mot de passe réinitialisé avec succès');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur: ' . $e->getMessage());
        }
    }

    public function deleteUser(int $id, UserService $userService)
    {
        try {
            if ($userService->delete($id)) {
                $this->dispatch('success-message', message: 'Utilisateur supprimé avec succès');
                $this->refreshData();
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la suppression: ');
            Log::error('Error deleting user: ' . $e->getMessage());
        }
    }

    public function sortUserData(string $field)
    {
        if ($this->userSortBy === $field) {
            $this->userSortAsc = !$this->userSortAsc;
        } else {
            $this->userSortBy = $field;
            $this->userSortAsc = true;
        }
    }

    public function resetUserFilters()
    {
        $this->userSearch = '';
        $this->userStatusFilter = null;
        $this->userActivityFilter = null;
        $this->userSortBy = 'name';
        $this->userSortAsc = true;
        $this->resetPage();
    }

    // Role methods
    public function openCreateRole()
    {
        $this->dispatch('open-create-role');
    }

    public function editRole(int $id)
    {
        $this->dispatch('edit-role', id: $id);
    }

    public function deleteRole(int $id, RoleService $roleService)
    {
        try {
            if ($roleService->delete($id)) {
                $this->dispatch('success-message', message: 'Rôle supprimé avec succès');
                $this->refreshData();
            } else {
                $this->dispatch('error-message', message: 'Impossible de supprimer ce rôle');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function resetRoleFilters()
    {
        $this->roleSearch = '';
        $this->roleForSchoolFilter = null;
        $this->resetPage();
    }

    public function render(UserService $userService, RoleService $roleService)
    {
        $data = $this->activeTab === 'users' 
            ? $this->loadUsersData($userService)
            : $this->loadRolesData($roleService);

        return view('livewire.application.v2.user.user-management-page', $data);
    }

    /**
     * Charger les données des utilisateurs avec filtres et statistiques
     */
    private function loadUsersData(UserService $userService): array
    {
        $filters = new UserFilterDTO(
            search: $this->userSearch,
            role_id: null,
            is_active: $this->userStatusFilter !== null ? filter_var($this->userStatusFilter, FILTER_VALIDATE_BOOLEAN) : null,
            is_on_line: $this->userActivityFilter !== null ? filter_var($this->userActivityFilter, FILTER_VALIDATE_BOOLEAN) : null,
            school_id: null,
            work_on_year: null
        );

        // Note: Le tri sera géré dans le repository si nécessaire

        return [
            'users' => $userService->getAllWithFilters($filters, $this->userPerPage),
            'roles' => collect(),
            'statistics' => $this->getAllStatistics($userService, app(RoleService::class)),
        ];
    }

    /**
     * Charger les données des rôles avec filtres et statistiques
     */
    private function loadRolesData(RoleService $roleService): array
    {
        $roles = $roleService->getAllWithFilters(
            search: $this->roleSearch,
            isForSchool: $this->roleForSchoolFilter,
            perPage: $this->rolePerPage
        );

        return [
            'users' => collect(),
            'roles' => $roles,
            'statistics' => $this->getAllStatistics(app(UserService::class), $roleService),
        ];
    }

    /**
     * Obtenir toutes les statistiques (users et roles)
     */
    private function getAllStatistics(UserService $userService, RoleService $roleService): array
    {
        return [
            'users' => [
                'total' => \App\Models\User::count(),
                'active' => \App\Models\User::where('is_active', true)->count(),
                'online' => \App\Models\User::where('is_on_line', true)->count(),
            ],
            'roles' => $roleService->getStatistics(),
        ];
    }
}
