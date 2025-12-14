<?php

namespace App\Livewire\Application\Admin\School;

use App\Actions\School\CreateSchoolUserAction;
use App\Models\School;
use App\Models\Role;
use App\Repositories\SchoolRepository;
use App\Services\SchoolManagementService;
use App\Enums\RoleType;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SchoolUsersPage extends Component
{
    use WithPagination;

    protected $listeners = ['userDataRefreshed' => '$refresh'];

    public School $school;
    public int $per_page = 15;

    #[Url(as: 'q')]
    public $q = '';

    // Formulaire de création d'utilisateur
    public $showCreateForm = false;
    public $name = '';
    public $username = '';
    public $email = '';
    public $phone = '';
    public $role_id = null;
    public $tempPassword = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'username' => 'required|string|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string',
        'role_id' => 'required|exists:roles,id',
    ];

    public function mount(int $schoolId)
    {
        $schoolRepository = app(SchoolRepository::class);
        $this->school = $schoolRepository->findById($schoolId);

        if (!$this->school) {
            abort(404, 'École introuvable');
        }

        $this->authorize('manageUsers', $this->school);
    }

    public function openCreateForm()
    {
        $this->authorize('createUser', $this->school);
        $this->showCreateForm = true;
        $this->resetForm();
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    public function createUser()
    {
        $this->authorize('createUser', $this->school);

        $this->validate();

        try {
            $action = app(CreateSchoolUserAction::class);
            $result = $action->execute($this->school->id, [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'role_id' => $this->role_id,
            ]);

            $this->tempPassword = $result['temp_password'];

            $this->dispatch('added', [
                'message' => 'Utilisateur créé avec succès. Mot de passe : ' . $this->tempPassword
            ]);

            $this->closeCreateForm();
            $this->dispatch('userDataRefreshed');

        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function toggleUserStatus(int $userId): void
    {
        try {
            $schoolManagementService = app(SchoolManagementService::class);
            $schoolManagementService->toggleUserStatus($userId);
            $this->dispatch('added', ['message' => 'Statut de l\'utilisateur modifié.']);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function resetUserPassword(int $userId): void
    {
        try {
            $schoolManagementService = app(SchoolManagementService::class);
            $newPassword = $schoolManagementService->resetUserPassword($userId);
            $this->dispatch('added', [
                'message' => 'Mot de passe réinitialisé. Nouveau mot de passe : ' . $newPassword
            ]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function deleteUser(int $userId): void
    {
        try {
            $schoolManagementService = app(SchoolManagementService::class);
            $schoolManagementService->deleteSchoolUser($userId, $this->school->id);
            $this->dispatch('added', ['message' => 'Utilisateur supprimé avec succès.']);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->phone = '';
        $this->role_id = null;
        $this->tempPassword = null;
        $this->resetValidation();
    }

    public function render()
    {
        $schoolRepository = app(SchoolRepository::class);
        $users = $schoolRepository->getSchoolUsersPaginated($this->school->id, $this->per_page, $this->q);
        
        // Récupérer les rôles disponibles pour l'école
        $roles = Role::where('is_for_school', true)
            ->orderBy('name')
            ->get();

        return view('livewire.application.admin.school.school-users-page', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
