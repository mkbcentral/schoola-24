<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleRepository
{
    /**
     * Relations à charger par défaut
     */
    private const DEFAULT_RELATIONS = ['users'];

    public function __construct(private Role $model) {}

    /**
     * Récupérer tous les rôles
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Récupérer tous les rôles avec pagination
     */
    public function getAllPaginated(int $perPage = 15, bool $forSchool = true): LengthAwarePaginator
    {
        return $this->model
            ->where('is_for_school', $forSchool)
            ->withCount('users')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Récupérer tous les rôles avec filtres
     */
    public function getAllWithFilters(?string $search = null, ?bool $isForSchool = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->withCount('users')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($isForSchool !== null, function ($query) use ($isForSchool) {
                $query->where('is_for_school', $isForSchool);
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Récupérer les rôles pour une école
     */
    public function getForSchool(): Collection
    {
        return $this->model
            ->where('is_for_school', true)
            ->get();
    }

    /**
     * Récupérer un rôle par ID
     */
    public function findById(int $id): ?Role
    {
        return $this->model
            ->withCount('users')
            ->find($id);
    }

    /**
     * Créer un rôle
     */
    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    /**
     * Mettre à jour un rôle
     */
    public function update(int $id, array $data): bool
    {
        $role = $this->model->find($id);

        if (!$role) {
            return false;
        }

        return $role->update($data);
    }

    /**
     * Supprimer un rôle
     */
    public function delete(int $id): bool
    {
        $role = $this->model->find($id);

        if (!$role) {
            return false;
        }

        // Vérifier si le rôle est utilisé
        if ($role->users()->count() > 0) {
            return false;
        }

        return $role->delete();
    }

    /**
     * Statistiques des rôles
     */
    public function getStatistics(): array
    {
        return [
            'total' => $this->model->count(),
            'for_school' => $this->model->where('is_for_school', true)->count(),
            'system' => $this->model->where('is_for_school', false)->count(),
        ];
    }

    /**
     * Vérifier si un rôle est utilisé
     */
    public function isUsed(int $id): bool
    {
        $role = $this->model->find($id);

        if (!$role) {
            return false;
        }

        return $role->users()->count() > 0;
    }

    /**
     * Récupérer un rôle par nom
     */
    public function findByName(string $name): ?Role
    {
        return $this->model->where('name', $name)->first();
    }
}
