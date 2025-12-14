<?php

namespace App\Repositories\Fee;

use App\Models\CategoryRegistrationFee;
use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRegistrationFeeRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = ['registrationFees'];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    /**
     * Récupérer toutes les catégories de frais d'inscription avec cache
     */
    public function all(): Collection
    {
        return Cache::remember(
            'category_registration_fees_all',
            self::CACHE_TTL,
            fn() => CategoryRegistrationFee::with(self::DEFAULT_RELATIONS)
                ->orderBy('name')
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->get()
        );
    }

    /**
     * Récupérer les catégories par école
     */
    public function getBySchool(?int $schoolId): Collection
    {
        return Cache::remember(
            "category_registration_fees_school_{$schoolId}",
            self::CACHE_TTL,
            fn() => CategoryRegistrationFee::with(self::DEFAULT_RELATIONS)
                ->where('school_id', $schoolId ?? School::DEFAULT_SCHOOL_ID())

                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer les catégories anciennes ou nouvelles
     */
    public function getByOldStatus(?int $schoolId, bool $isOld): Collection
    {
        return CategoryRegistrationFee::with(self::DEFAULT_RELATIONS)
            ->where('school_id', $schoolId ?? School::DEFAULT_SCHOOL_ID())

            ->where('is_old', $isOld)
            ->orderBy('name')
            ->get();
    }

    /**
     * Trouver une catégorie par ID
     */
    public function findById(int $id): ?CategoryRegistrationFee
    {
        return CategoryRegistrationFee::with(self::DEFAULT_RELATIONS)->find($id);
    }

    /**
     * Vérifier si une catégorie existe
     */
    public function exists(string $name, int $schoolId, ?int $excludeId = null): bool
    {
        $query = CategoryRegistrationFee::where('name', $name)
            ->where('school_id', $schoolId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Compter les catégories par école
     */
    public function countBySchool(?int $schoolId): int
    {
        return CategoryRegistrationFee::where('school_id', $schoolId ?? School::DEFAULT_SCHOOL_ID())->count();
    }

    /**
     * Effacer le cache
     */
    public function clearCache(): void
    {
        Cache::forget('category_registration_fees_all');
        // Effacer aussi les caches par école si nécessaire
        Cache::flush();
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(?int $schoolId): array
    {
        $total = $this->countBySchool($schoolId);
        $old = CategoryRegistrationFee::where('school_id', $schoolId ?? School::DEFAULT_SCHOOL_ID())
            ->where('is_old', true)
            ->count();
        $new = $total - $old;

        return [
            'total' => $total,
            'old' => $old,
            'new' => $new,
        ];
    }
}
