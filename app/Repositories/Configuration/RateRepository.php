<?php

namespace App\Repositories\Configuration;

use App\Models\Rate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class RateRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = [];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    public function __construct(private Rate $model) {}

    /**
     * Récupérer tous les taux
     */
    public function all(): Collection
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Récupérer tous les taux d'une école
     */
    public function getBySchool(int $schoolId): Collection
    {
        return Cache::remember(
            "rates_school_{$schoolId}",
            self::CACHE_TTL,
            fn() => $this->model->newQuery()
                ->where('school_id', $schoolId)
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    /**
     * Récupérer le taux par défaut (non modifié) d'une école
     */
    public function getDefaultBySchool(int $schoolId): ?Rate
    {
        return Cache::remember(
            "default_rate_school_{$schoolId}",
            self::CACHE_TTL,
            fn() => $this->model->newQuery()
                ->where('school_id', $schoolId)
                ->where('is_changed', false)
                ->first()
        );
    }

    /**
     * Récupérer un taux par ID
     */
    public function findById(int $id): ?Rate
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->find($id);
    }

    /**
     * Vérifier si un taux existe
     */
    public function exists(int $id): bool
    {
        return $this->model->newQuery()->where('id', $id)->exists();
    }

    /**
     * Compter les inscriptions utilisant un taux
     */
    public function countRegistrations(int $rateId): int
    {
        $rate = $this->findById($rateId);
        return $rate ? $rate->registrations()->count() : 0;
    }

    /**
     * Compter les paiements utilisant un taux
     */
    public function countPayments(int $rateId): int
    {
        $rate = $this->findById($rateId);
        return $rate ? $rate->payments()->count() : 0;
    }

    /**
     * Vider le cache des taux
     */
    public function clearCache(?int $schoolId = null): void
    {
        if ($schoolId) {
            Cache::forget("rates_school_{$schoolId}");
            Cache::forget("default_rate_school_{$schoolId}");
        } else {
            Cache::flush();
        }
    }

    /**
     * Récupérer les statistiques d'un taux
     */
    public function getStatistics(int $rateId): array
    {
        $rate = $this->findById($rateId);

        if (!$rate) {
            return [];
        }

        return [
            'total_registrations' => $rate->registrations()->count(),
            'total_payments' => $rate->payments()->count(),
            'total_amount_paid' => $rate->payments()->sum('amount'),
        ];
    }
}
