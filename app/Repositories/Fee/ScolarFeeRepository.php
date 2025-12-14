<?php

namespace App\Repositories\Fee;

use App\Models\School;
use App\Models\SchoolYear;
use App\Models\ScolarFee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ScolarFeeRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = ['categoryFee', 'classRoom', 'payments'];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    /**
     * Récupérer tous les frais scolaires avec cache
     */
    public function all(): Collection
    {
        return Cache::remember(
            'scolar_fees_all',
            self::CACHE_TTL,
            fn() => ScolarFee::with(self::DEFAULT_RELATIONS)
                ->orderBy('name')
                ->whereHas('categoryFee', function ($query) {
                    $query->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                        ->where('school_id', School::DEFAULT_SCHOOL_ID());
                })
                ->get()
        );
    }

    /**
     * Récupérer les frais par catégorie
     */
    public function getByCategoryFee(int $categoryFeeId): Collection
    {
        return Cache::remember(
            "scolar_fees_category_{$categoryFeeId}",
            self::CACHE_TTL,
            fn() => ScolarFee::with(self::DEFAULT_RELATIONS)
                ->where('category_fee_id', $categoryFeeId)
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer les frais par classe
     */
    public function getByClassRoom(int $classRoomId): Collection
    {
        return Cache::remember(
            "scolar_fees_class_room_{$classRoomId}",
            self::CACHE_TTL,
            fn() => ScolarFee::with(self::DEFAULT_RELATIONS)
                ->where('class_room_id', $classRoomId)
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer les frais modifiés
     */
    public function getChanged(): Collection
    {
        return ScolarFee::with(self::DEFAULT_RELATIONS)
            ->where('is_changed', true)
            ->orderBy('name')
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->get();
    }

    /**
     * Trouver un frais par ID
     */
    public function findById(int $id): ?ScolarFee
    {
        return ScolarFee::with(self::DEFAULT_RELATIONS)->find($id);
    }

    /**
     * Vérifier si un frais existe
     */
    public function exists(string $name, int $categoryFeeId, int $classRoomId, ?int $excludeId = null): bool
    {
        $query = ScolarFee::where('name', $name)
            ->where('category_fee_id', $categoryFeeId)
            ->where('class_room_id', $classRoomId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Compter les frais par catégorie
     */
    public function countByCategory(int $categoryFeeId): int
    {
        return ScolarFee::where('category_fee_id', $categoryFeeId)
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->count();
    }

    /**
     * Effacer le cache
     */
    public function clearCache(): void
    {
        Cache::forget('scolar_fees_all');
        Cache::flush();
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(int $categoryFeeId): array
    {
        $total = $this->countByCategory($categoryFeeId);
        $changed = ScolarFee::where('category_fee_id', $categoryFeeId)
            ->where('is_changed', true)
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->count();
        $totalAmount = ScolarFee::where('category_fee_id', $categoryFeeId)
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->sum('amount');

        return [
            'total' => $total,
            'changed' => $changed,
            'total_amount' => $totalAmount,
        ];
    }
}
