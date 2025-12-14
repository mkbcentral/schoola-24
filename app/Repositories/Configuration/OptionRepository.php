<?php

namespace App\Repositories\Configuration;

use App\Models\Option;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class OptionRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = ['section', 'classRooms'];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    public function __construct(private Option $model) {}

    /**
     * Récupérer toutes les options
     */
    public function all(): Collection
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->orderBy('name')
            ->get();
    }

    /**
     * Récupérer toutes les options d'une section
     */
    public function getBySection(int $sectionId): Collection
    {
        return Cache::remember(
            "options_section_{$sectionId}",
            self::CACHE_TTL,
            fn() => $this->model->newQuery()
                ->where('section_id', $sectionId)
                ->with(self::DEFAULT_RELATIONS)
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer une option par ID
     */
    public function findById(int $id): ?Option
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->find($id);
    }

    /**
     * Vérifier si une option existe
     */
    public function exists(int $id): bool
    {
        return $this->model->newQuery()->where('id', $id)->exists();
    }

    /**
     * Compter les classes d'une option
     */
    public function countClassRooms(int $optionId): int
    {
        $option = $this->findById($optionId);
        return $option ? $option->classRooms()->count() : 0;
    }

    /**
     * Vider le cache des options
     */
    public function clearCache(?int $sectionId = null): void
    {
        if ($sectionId) {
            Cache::forget("options_section_{$sectionId}");
        } else {
            Cache::flush();
        }
    }

    /**
     * Récupérer les statistiques d'une option
     */
    public function getStatistics(int $optionId): array
    {
        $option = $this->findById($optionId);

        if (!$option) {
            return [];
        }

        return [
            'total_classes' => $option->classRooms()->count(),
        ];
    }
}
