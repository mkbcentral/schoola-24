<?php

namespace App\Services\CategoryFee;

use App\Models\CategoryFee;
use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Support\Collection;

class CategoryFeeService
{
    /**
     * Récupérer toutes les catégories de frais
     *
     * @param int|null $schoolId
     * @param int|null $schoolYearId
     * @return Collection
     */
    public function getAllCategoryFees(?int $schoolId = null, ?int $schoolYearId = null): Collection
    {
        $schoolId = $schoolId ?? School::DEFAULT_SCHOOL_ID();
        $schoolYearId = $schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        return CategoryFee::where('school_id', $schoolId)
            ->where('school_year_id', $schoolYearId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Trouver la catégorie Minerval par défaut
     *
     * @param Collection $categoryFees
     * @return int|null
     */
    public function findMinervalCategoryId(Collection $categoryFees): ?int
    {
        $minerval = $categoryFees->first(
            fn($cat) => stripos($cat->name, 'MINERVAL') !== false
        );

        return $minerval?->id ?? $categoryFees->first()?->id;
    }
}
