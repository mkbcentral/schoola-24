<?php

namespace App\Repositories;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SchoolRepository
{
    /**
     * Récupérer toutes les écoles avec pagination
     */
    public function getAllPaginated(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return School::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->withCount('users')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Récupérer toutes les écoles actives
     */
    public function getAllActive(): Collection
    {
        return School::where('app_status', 'active')
            ->orderBy('name')
            ->get();
    }

    /**
     * Trouver une école par son ID
     */
    public function findById(int $id): ?School
    {
        return School::with(['users', 'sections'])->find($id);
    }

    /**
     * Créer une nouvelle école
     */
    public function create(array $data): School
    {
        return School::create($data);
    }

    /**
     * Mettre à jour une école
     */
    public function update(int $id, array $data): bool
    {
        return School::where('id', $id)->update($data);
    }

    /**
     * Supprimer une école
     */
    public function delete(int $id): bool
    {
        $school = School::find($id);
        if (!$school) {
            return false;
        }

        // Vérifier s'il y a des utilisateurs
        if ($school->users()->count() > 0) {
            throw new \Exception('Impossible de supprimer une école ayant des utilisateurs.');
        }

        return $school->delete();
    }

    /**
     * Récupérer tous les utilisateurs d'une école
     */
    public function getSchoolUsers(int $schoolId): Collection
    {
        return User::where('school_id', $schoolId)
            ->with('role')
            ->orderBy('name')
            ->get();
    }

    /**
     * Récupérer les utilisateurs d'une école avec pagination
     */
    public function getSchoolUsersPaginated(int $schoolId, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return User::where('school_id', $schoolId)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%"))
            ->with('role')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Compter le nombre d'écoles actives
     */
    public function countActive(): int
    {
        return School::where('app_status', 'active')->count();
    }

    /**
     * Compter le nombre total d'écoles
     */
    public function countAll(): int
    {
        return School::count();
    }

    /**
     * Récupérer les statistiques d'une école
     */
    public function getSchoolStats(int $schoolId): array
    {
        $school = School::withCount(['users', 'sections'])->find($schoolId);

        if (!$school) {
            return [];
        }

        return [
            'total_users' => $school->users_count,
            'total_sections' => $school->sections_count,
            'created_at' => $school->created_at,
            'status' => $school->app_status,
        ];
    }

    /**
     * Activer/Désactiver une école
     */
    public function toggleStatus(int $id): bool
    {
        $school = School::find($id);
        if (!$school) {
            return false;
        }

        $newStatus = $school->app_status === 'active' ? 'inactive' : 'active';
        return $school->update([
            'app_status' => $newStatus,
            'school_status' => $newStatus,
        ]);
    }
}
