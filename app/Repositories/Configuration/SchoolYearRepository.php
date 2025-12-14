<?php

namespace App\Repositories\Configuration;

use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class SchoolYearRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = [];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    public function __construct(private SchoolYear $model) {}

    /**
     * Récupérer toutes les années scolaires
     */
    public function all(): Collection
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Récupérer toutes les années scolaires d'une école
     */
    public function getBySchool(int $schoolId): Collection
    {
        return Cache::remember(
            "school_years_school_{$schoolId}",
            self::CACHE_TTL,
            fn() => $this->model->newQuery()
                ->where('school_id', $schoolId)
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    /**
     * Récupérer l'année scolaire active d'une école
     */
    public function getActiveBySchool(int $schoolId): ?SchoolYear
    {
        return Cache::remember(
            "active_school_year_school_{$schoolId}",
            self::CACHE_TTL,
            fn() => $this->model->newQuery()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->first()
        );
    }

    /**
     * Récupérer une année scolaire par ID
     */
    public function findById(int $id): ?SchoolYear
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->find($id);
    }

    /**
     * Récupérer une année scolaire par nom et école
     */
    public function findByNameAndSchool(string $name, int $schoolId): ?SchoolYear
    {
        return $this->model->newQuery()
            ->where('name', $name)
            ->where('school_id', $schoolId)
            ->first();
    }

    /**
     * Vérifier si une année scolaire existe
     */
    public function exists(int $id): bool
    {
        return $this->model->newQuery()->where('id', $id)->exists();
    }

    /**
     * Compter les inscriptions d'une année scolaire
     */
    public function countRegistrations(int $schoolYearId): int
    {
        $schoolYear = $this->findById($schoolYearId);
        return $schoolYear ? $schoolYear->registrations()->count() : 0;
    }

    /**
     * Vider le cache des années scolaires
     */
    public function clearCache(?int $schoolId = null): void
    {
        if ($schoolId) {
            Cache::forget("school_years_school_{$schoolId}");
            Cache::forget("active_school_year_school_{$schoolId}");
        } else {
            Cache::flush();
        }
    }

    /**
     * Récupérer les statistiques d'une année scolaire
     */
    public function getStatistics(int $schoolYearId): array
    {
        $schoolYear = $this->findById($schoolYearId);

        if (!$schoolYear) {
            return [];
        }

        return [
            'total_registrations' => $schoolYear->registrations()->count(),
            'active_registrations' => $schoolYear->registrations()
                ->where('is_old_student', false)
                ->count(),
            'old_students' => $schoolYear->registrations()
                ->where('is_old_student', true)
                ->count(),
        ];
    }
}
