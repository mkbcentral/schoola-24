<?php

namespace App\Repositories\Configuration;

use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ClassRoomRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = ['option.section', 'registrations'];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    public function __construct(private ClassRoom $model) {}

    /**
     * Récupérer toutes les classes
     */
    public function all(): Collection
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->orderBy('name')
            ->get();
    }

    /**
     * Récupérer toutes les classes d'une option
     */
    public function getByOption(int $optionId): Collection
    {
        return Cache::remember(
            "classrooms_option_{$optionId}",
            self::CACHE_TTL,
            fn() => $this->model->newQuery()
                ->where('option_id', $optionId)
                ->with(self::DEFAULT_RELATIONS)
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer une classe par ID
     */
    public function findById(int $id): ?ClassRoom
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->find($id);
    }

    /**
     * Vérifier si une classe existe
     */
    public function exists(int $id): bool
    {
        return $this->model->newQuery()->where('id', $id)->exists();
    }

    /**
     * Compter les inscriptions d'une classe
     */
    public function countRegistrations(int $classRoomId): int
    {
        $classRoom = $this->findById($classRoomId);
        return $classRoom ? $classRoom->registrations()->count() : 0;
    }

    /**
     * Vider le cache des classes
     */
    public function clearCache(?int $optionId = null): void
    {
        if ($optionId) {
            Cache::forget("classrooms_option_{$optionId}");
        } else {
            Cache::flush();
        }
    }

    /**
     * Récupérer les statistiques d'une classe
     */
    public function getStatistics(int $classRoomId): array
    {
        $classRoom = $this->findById($classRoomId);

        if (!$classRoom) {
            return [];
        }

        return [
            'total_registrations' => $classRoom->registrations()->count(),
            'total_scolar_fees' => $classRoom->scolarFees()->count(),
        ];
    }
}
