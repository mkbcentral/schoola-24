<?php

namespace App\Repositories;

use App\DTOs\User\UserFilterDTO;
use App\Models\User;
use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * Relations à charger par défaut
     */
    private const DEFAULT_RELATIONS = [
        'role',
        'school',
    ];

    public function __construct(private User $model) {}

    /**
     * Récupérer tous les utilisateurs avec filtres
     */
    public function getAllWithFilters(UserFilterDTO $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Eager loading
        $query->with(self::DEFAULT_RELATIONS);

        // Filtres
        if ($filters->search) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters->search . '%')
                    ->orWhere('username', 'like', '%' . $filters->search . '%')
                    ->orWhere('email', 'like', '%' . $filters->search . '%')
                    ->orWhere('phone', 'like', '%' . $filters->search . '%');
            });
        }

        if ($filters->role_id) {
            $query->where('role_id', $filters->role_id);
        }

        if ($filters->is_active !== null) {
            $query->where('is_active', $filters->is_active);
        }

        if ($filters->is_on_line !== null) {
            $query->where('is_on_line', $filters->is_on_line);
        }

        if ($filters->school_id) {
            $query->where('school_id', $filters->school_id);
        }

        if ($filters->work_on_year) {
            $query->where('work_on_year', $filters->work_on_year);
        }

        return $query->latest('created_at')->paginate($perPage);
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function findById(int $id): ?User
    {
        return $this->model->with(self::DEFAULT_RELATIONS)->find($id);
    }

    /**
     * Créer un utilisateur
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(int $id, array $data): bool
    {
        $user = $this->model->find($id);

        if (!$user) {
            return false;
        }

        return $user->update($data);
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id): bool
    {
        $user = $this->model->find($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    /**
     * Statistiques des utilisateurs pour une école
     */
    public function getStatistics(int $schoolId): array
    {
        $baseQuery = $this->model->where('school_id', $schoolId);

        return [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive' => (clone $baseQuery)->where('is_active', false)->count(),
            'online' => (clone $baseQuery)->where('is_on_line', true)->count(),
            'offline' => (clone $baseQuery)->where('is_on_line', false)->count(),
            'by_role' => (clone $baseQuery)
                ->select('role_id', DB::raw('count(*) as count'))
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->addSelect('roles.name as role_name')
                ->groupBy('role_id', 'roles.name')
                ->get()
                ->keyBy('role_name')
                ->map(fn($item) => $item->count)
                ->toArray(),
        ];
    }

    /**
     * Récupérer les utilisateurs connectés
     */
    public function getOnlineUsers(int $schoolId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(self::DEFAULT_RELATIONS)
            ->where('school_id', $schoolId)
            ->where('is_on_line', true)
            ->latest('updated_at')
            ->paginate($perPage);
    }

    /**
     * Récupérer les utilisateurs déconnectés
     */
    public function getOfflineUsers(int $schoolId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(self::DEFAULT_RELATIONS)
            ->where('school_id', $schoolId)
            ->where('is_on_line', false)
            ->latest('updated_at')
            ->paginate($perPage);
    }

    /**
     * Mettre à jour le statut en ligne d'un utilisateur
     */
    public function updateOnlineStatus(int $userId, bool $isOnline): bool
    {
        return $this->model
            ->where('id', $userId)
            ->update(['is_on_line' => $isOnline]);
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(int $userId, string $newPassword): bool
    {
        return $this->model
            ->where('id', $userId)
            ->update(['password' => bcrypt($newPassword)]);
    }

    /**
     * Récupérer tous les utilisateurs d'une école
     */
    public function getAllBySchool(int $schoolId): Collection
    {
        return $this->model
            ->with(self::DEFAULT_RELATIONS)
            ->where('school_id', $schoolId)
            ->get();
    }

    /**
     * Compter les utilisateurs par rôle
     */
    public function countByRole(int $schoolId, int $roleId): int
    {
        return $this->model
            ->where('school_id', $schoolId)
            ->where('role_id', $roleId)
            ->count();
    }
}
