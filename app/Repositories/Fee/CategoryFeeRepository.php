<?php

namespace App\Repositories\Fee;

use App\Models\CategoryFee;
use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryFeeRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = ['schoolYear', 'scolarFees'];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    /**
     * Récupérer toutes les catégories de frais scolaires avec cache
     */
    public function all(): Collection
    {
        return Cache::remember(
            'category_fees_all',
            self::CACHE_TTL,
            fn() => CategoryFee::with(self::DEFAULT_RELATIONS)
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
            "category_fees_school_{$schoolId}",
            self::CACHE_TTL,
            fn() => CategoryFee::with(self::DEFAULT_RELATIONS)
                ->where('school_id', $schoolId ?? School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer les catégories par année scolaire
     */
    public function getBySchoolYear(?int $schoolYearId): Collection
    {
        return Cache::remember(
            "category_fees_school_year_{$schoolYearId}",
            self::CACHE_TTL,
            fn() => CategoryFee::with(self::DEFAULT_RELATIONS)
                ->where('school_year_id', $schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->orderBy('name')
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->get()
        );
    }

    /**
     * Récupérer les catégories de frais d'état
     */
    public function getStateFees(?int $schoolYearId): Collection
    {
        return CategoryFee::with(self::DEFAULT_RELATIONS)
            ->where('school_year_id', $schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('is_state_fee', true)
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->orderBy('name')
            ->get();
    }

    /**
     * Trouver une catégorie par ID
     */
    public function findById(int $id): ?CategoryFee
    {
        return CategoryFee::with(self::DEFAULT_RELATIONS)->find($id);
    }

    /**
     * Vérifier si une catégorie existe
     */
    public function exists(string $name, int $schoolYearId, ?int $excludeId = null): bool
    {
        $query = CategoryFee::where('name', $name)
            ->where('school_year_id', $schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Compter les catégories par école
     */
    public function countBySchool(int $schoolId): int
    {
        return CategoryFee::where('school_id', $schoolId ?? School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->count();
    }

    /**
     * Effacer le cache
     */
    public function clearCache(): void
    {
        Cache::forget('category_fees_all');
        // Effacer aussi les caches spécifiques
        Cache::flush();
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(?int $schoolId): array
    {
        $total = $this->countBySchool($schoolId);
        $stateFees = CategoryFee::where('school_id', $schoolId ?? School::DEFAULT_SCHOOL_ID())
            ->where('is_state_fee', true)
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->count();
        $installmentFees = CategoryFee::where('school_id', $schoolId ?? School::DEFAULT_SCHOOL_ID())
            ->where('is_paid_in_installment', true)
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->count();

        return [
            'total' => $total,
            'state_fees' => $stateFees,
            'installment_fees' => $installmentFees,
        ];
    }
}
