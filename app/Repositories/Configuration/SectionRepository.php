<?php

namespace App\Repositories\Configuration;

use App\Models\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class SectionRepository
{
    /**
     * Relations à charger par défaut (Eager Loading)
     */
    private const DEFAULT_RELATIONS = ['options'];

    /**
     * Durée du cache en minutes
     */
    private const CACHE_TTL = 60;

    public function __construct(private Section $model) {}

    /**
     * Récupérer toutes les sections
     */
    public function all(): Collection
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->orderBy('name')
            ->get();
    }

    /**
     * Récupérer toutes les sections d'une école
     */
    public function getBySchool(int $schoolId): Collection
    {
        return Cache::remember(
            "sections_school_{$schoolId}",
            self::CACHE_TTL,
            fn() => $this->model->newQuery()
                ->where('school_id', $schoolId)
                ->with(self::DEFAULT_RELATIONS)
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Récupérer une section par ID
     */
    public function findById(int $id): ?Section
    {
        return $this->model->newQuery()
            ->with(self::DEFAULT_RELATIONS)
            ->find($id);
    }

    /**
     * Vérifier si une section existe
     */
    public function exists(int $id): bool
    {
        return $this->model->newQuery()->where('id', $id)->exists();
    }

    /**
     * Compter les options d'une section
     */
    public function countOptions(int $sectionId): int
    {
        $section = $this->findById($sectionId);
        return $section ? $section->options()->count() : 0;
    }

    /**
     * Vider le cache des sections
     */
    public function clearCache(?int $schoolId = null): void
    {
        if ($schoolId) {
            Cache::forget("sections_school_{$schoolId}");
        } else {
            Cache::flush();
        }
    }

    /**
     * Récupérer les statistiques d'une section
     */
    public function getStatistics(int $sectionId): array
    {
        $section = $this->findById($sectionId);

        if (!$section) {
            return [];
        }

        $totalClasses = 0;
        foreach ($section->options as $option) {
            $totalClasses += $option->classRooms()->count();
        }

        return [
            'total_options' => $section->options()->count(),
            'total_classes' => $totalClasses,
        ];
    }
}
