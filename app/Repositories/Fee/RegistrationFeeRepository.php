<?php

namespace App\Repositories\Fee;

use App\Models\RegistrationFee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class RegistrationFeeRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = ['option', 'categoryRegistrationFee', 'registrations'];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    /**
     * Récupérer tous les frais d'inscription avec cache
     */
    public function all(): Collection
    {
        return Cache::remember(
            'registration_fees_all',
            self::CACHE_TTL,
            fn() => RegistrationFee::with(self::DEFAULT_RELATIONS)
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer les frais par option
     */
    public function getByOption(int $optionId): Collection
    {
        return Cache::remember(
            "registration_fees_option_{$optionId}",
            self::CACHE_TTL,
            fn() => RegistrationFee::with(self::DEFAULT_RELATIONS)
                ->where('option_id', $optionId)
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer les frais par catégorie
     */
    public function getByCategoryRegistrationFee(int $categoryRegistrationFeeId): Collection
    {
        return Cache::remember(
            "registration_fees_category_{$categoryRegistrationFeeId}",
            self::CACHE_TTL,
            fn() => RegistrationFee::with(self::DEFAULT_RELATIONS)
                ->where('category_registration_fee_id', $categoryRegistrationFeeId)
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer les frais par année scolaire
     */
    public function getBySchoolYear(int $schoolYearId): Collection
    {
        return Cache::remember(
            "registration_fees_school_year_{$schoolYearId}",
            self::CACHE_TTL,
            fn() => RegistrationFee::with(self::DEFAULT_RELATIONS)
                ->where('school_year_id', $schoolYearId)
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Trouver un frais par ID
     */
    public function findById(int $id): ?RegistrationFee
    {
        return RegistrationFee::with(self::DEFAULT_RELATIONS)->find($id);
    }

    /**
     * Vérifier si un frais existe
     */
    public function exists(string $name, int $optionId, int $categoryRegistrationFeeId, ?int $excludeId = null): bool
    {
        $query = RegistrationFee::where('name', $name)
            ->where('option_id', $optionId)
            ->where('category_registration_fee_id', $categoryRegistrationFeeId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Compter les frais par catégorie
     */
    public function countByCategory(int $categoryRegistrationFeeId): int
    {
        return RegistrationFee::where('category_registration_fee_id', $categoryRegistrationFeeId)->count();
    }

    /**
     * Effacer le cache
     */
    public function clearCache(): void
    {
        Cache::forget('registration_fees_all');
        Cache::flush();
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(int $categoryRegistrationFeeId): array
    {
        $total = $this->countByCategory($categoryRegistrationFeeId);
        $totalAmount = RegistrationFee::where('category_registration_fee_id', $categoryRegistrationFeeId)
            ->sum('amount');
        $byOption = RegistrationFee::where('category_registration_fee_id', $categoryRegistrationFeeId)
            ->groupBy('option_id')
            ->count();

        return [
            'total' => $total,
            'total_amount' => $totalAmount,
            'options_count' => $byOption,
        ];
    }
}
