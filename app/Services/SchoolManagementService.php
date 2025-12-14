<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use App\Models\Role;
use App\Enums\RoleType;
use App\DTOs\School\CreateSchoolDTO;
use App\DTOs\School\UpdateSchoolDTO;
use App\Repositories\SchoolRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SchoolManagementService
{
    public function __construct(
        private SchoolRepository $schoolRepository
    ) {}

    /**
     * Créer une école avec un administrateur par défaut
     */
    public function createSchoolWithAdmin(CreateSchoolDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            // 1. Créer l'école
            $school = $this->schoolRepository->create($dto->toSchoolArray());

            // 2. Générer un mot de passe temporaire
            $tempPassword = $this->generateSecurePassword();

            // 3. Créer l'utilisateur ADMIN_SCHOOL
            $adminRole = $this->getRoleByName(RoleType::ADMIN_SCHOOL);
            
            $user = User::create([
                'name' => $dto->adminName,
                'username' => $dto->adminUsername,
                'email' => $dto->adminEmail,
                'phone' => $dto->adminPhone,
                'password' => Hash::make($tempPassword),
                'role_id' => $adminRole->id,
                'school_id' => $school->id,
                'is_active' => true,
                'work_on_year' => null, // Sera défini lors de la première connexion
            ]);

            // 4. Envoyer l'email avec les identifiants
            $this->sendWelcomeEmail($user, $tempPassword, $school);

            return [
                'school' => $school->fresh(),
                'admin' => $user,
                'temp_password' => $tempPassword,
            ];
        });
    }

    /**
     * Mettre à jour une école
     */
    public function updateSchool(UpdateSchoolDTO $dto): bool
    {
        return $this->schoolRepository->update($dto->id, $dto->toArray());
    }

    /**
     * Supprimer une école
     */
    public function deleteSchool(int $schoolId): bool
    {
        try {
            return $this->schoolRepository->delete($schoolId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Récupérer tous les utilisateurs d'une école
     */
    public function getSchoolUsers(int $schoolId)
    {
        return $this->schoolRepository->getSchoolUsersPaginated($schoolId);
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleUserStatus(int $userId): bool
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        return $user->update(['is_active' => !$user->is_active]);
    }

    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function resetUserPassword(int $userId): string
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('Utilisateur introuvable.');
        }

        $newPassword = $this->generateSecurePassword();
        $user->update(['password' => Hash::make($newPassword)]);

        // Envoyer l'email avec le nouveau mot de passe
        $this->sendPasswordResetEmail($user, $newPassword);

        return $newPassword;
    }

    /**
     * Supprimer un utilisateur d'une école
     */
    public function deleteSchoolUser(int $userId, int $schoolId): bool
    {
        $user = User::where('id', $userId)
            ->where('school_id', $schoolId)
            ->first();

        if (!$user) {
            throw new \Exception('Utilisateur introuvable.');
        }

        // Empêcher la suppression du dernier ADMIN_SCHOOL
        if ($this->isLastAdminSchool($user)) {
            throw new \Exception('Impossible de supprimer le dernier administrateur de l\'école.');
        }

        return $user->delete();
    }

    /**
     * Créer un nouvel utilisateur pour une école
     */
    public function createSchoolUser(int $schoolId, array $data): array
    {
        $school = $this->schoolRepository->findById($schoolId);
        if (!$school) {
            throw new \Exception('École introuvable.');
        }

        $tempPassword = $this->generateSecurePassword();

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'password' => Hash::make($tempPassword),
            'role_id' => $data['role_id'],
            'school_id' => $schoolId,
            'is_active' => true,
        ]);

        // Envoyer l'email
        $this->sendWelcomeEmail($user, $tempPassword, $school);

        return [
            'user' => $user,
            'temp_password' => $tempPassword,
        ];
    }

    /**
     * Obtenir les statistiques générales des écoles
     */
    public function getGeneralStats(): array
    {
        return [
            'total_schools' => $this->schoolRepository->countAll(),
            'active_schools' => $this->schoolRepository->countActive(),
            'inactive_schools' => $this->schoolRepository->countAll() - $this->schoolRepository->countActive(),
        ];
    }

    /**
     * Activer/Désactiver une école
     */
    public function toggleSchoolStatus(int $schoolId): bool
    {
        return $this->schoolRepository->toggleStatus($schoolId);
    }

    /**
     * Récupérer un rôle par son nom
     */
    private function getRoleByName(string $roleName): Role
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            throw new \Exception("Le rôle {$roleName} n'existe pas dans la base de données.");
        }

        return $role;
    }

    /**
     * Générer un mot de passe sécurisé
     */
    private function generateSecurePassword(int $length = 12): string
    {
        return Str::password($length, true, true, true, false);
    }

    /**
     * Vérifier si c'est le dernier ADMIN_SCHOOL de l'école
     */
    private function isLastAdminSchool(User $user): bool
    {
        if ($user->role->name !== RoleType::ADMIN_SCHOOL) {
            return false;
        }

        $adminRole = $this->getRoleByName(RoleType::ADMIN_SCHOOL);
        
        $count = User::where('school_id', $user->school_id)
            ->where('role_id', $adminRole->id)
            ->where('is_active', true)
            ->count();

        return $count <= 1;
    }

    /**
     * Envoyer l'email de bienvenue
     */
    private function sendWelcomeEmail(User $user, string $password, School $school): void
    {
        // TODO: Implémenter l'envoi d'email avec les identifiants
        // Mail::to($user->email)->send(new WelcomeSchoolAdmin($user, $password, $school));
    }

    /**
     * Envoyer l'email de réinitialisation de mot de passe
     */
    private function sendPasswordResetEmail(User $user, string $newPassword): void
    {
        // TODO: Implémenter l'envoi d'email avec le nouveau mot de passe
        // Mail::to($user->email)->send(new PasswordResetNotification($user, $newPassword));
    }
}
