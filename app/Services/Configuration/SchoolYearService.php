<?php

namespace App\Services\Configuration;

use App\Actions\SchoolYear\CreateSchoolYearAction;
use App\Actions\SchoolYear\UpdateSchoolYearAction;
use App\Actions\SchoolYear\DeleteSchoolYearAction;
use App\Actions\SchoolYear\ToggleSchoolYearStatusAction;
use App\DTOs\Configuration\SchoolYearDTO;
use App\Models\SchoolYear;
use App\Repositories\Configuration\SchoolYearRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Service de gestion des années scolaires
 * Responsabilités : CRUD, récupération et statistiques
 */
class SchoolYearService
{
    public function __construct(
        private SchoolYearRepository $repository,
        private CreateSchoolYearAction $createAction,
        private UpdateSchoolYearAction $updateAction,
        private DeleteSchoolYearAction $deleteAction,
        private ToggleSchoolYearStatusAction $toggleStatusAction,
    ) {}

    /**
     * Récupérer toutes les années scolaires
     */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Récupérer les années scolaires d'une école
     */
    public function getBySchool(int $schoolId): Collection
    {
        return $this->repository->getBySchool($schoolId);
    }

    /**
     * Récupérer l'année scolaire active d'une école
     */
    public function getActiveBySchool(int $schoolId): ?SchoolYear
    {
        return $this->repository->getActiveBySchool($schoolId);
    }

    /**
     * Créer une nouvelle année scolaire
     */
    public function create(SchoolYearDTO $dto): array
    {
        try {
            $schoolYear = $this->createAction->execute($dto);

            // Vider le cache
            $this->repository->clearCache($dto->schoolId);

            return [
                'success' => true,
                'message' => 'Année scolaire créée avec succès',
                'data' => $schoolYear,
            ];
        } catch (\InvalidArgumentException $e) {
            Log::error('Erreur de validation lors de la création d\'une année scolaire', [
                'error' => $e->getMessage(),
                'dto' => $dto,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => json_decode($e->getMessage(), true),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création d\'une année scolaire', [
                'error' => $e->getMessage(),
                'dto' => $dto,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la création de l\'année scolaire',
            ];
        }
    }

    /**
     * Mettre à jour une année scolaire
     */
    public function update(int $id, SchoolYearDTO $dto): array
    {
        try {
            $schoolYear = $this->updateAction->execute($id, $dto);

            // Vider le cache
            $this->repository->clearCache($dto->schoolId);

            return [
                'success' => true,
                'message' => 'Année scolaire mise à jour avec succès',
                'data' => $schoolYear,
            ];
        } catch (\InvalidArgumentException $e) {
            Log::error('Erreur de validation lors de la mise à jour d\'une année scolaire', [
                'error' => $e->getMessage(),
                'id' => $id,
                'dto' => $dto,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => json_decode($e->getMessage(), true),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour d\'une année scolaire', [
                'error' => $e->getMessage(),
                'id' => $id,
                'dto' => $dto,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'année scolaire',
            ];
        }
    }

    /**
     * Supprimer une année scolaire
     */
    public function delete(int $id): array
    {
        try {
            $schoolYear = $this->repository->findById($id);

            if (!$schoolYear) {
                return [
                    'success' => false,
                    'message' => 'Année scolaire non trouvée',
                ];
            }

            $this->deleteAction->execute($id);

            // Vider le cache
            $this->repository->clearCache($schoolYear->school_id);

            return [
                'success' => true,
                'message' => 'Année scolaire supprimée avec succès',
            ];
        } catch (\RuntimeException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression d\'une année scolaire', [
                'error' => $e->getMessage(),
                'id' => $id,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'année scolaire',
            ];
        }
    }

    /**
     * Basculer le statut actif d'une année scolaire
     */
    public function toggleStatus(int $id): array
    {
        try {
            $schoolYear = $this->toggleStatusAction->execute($id);

            // Vider le cache
            $this->repository->clearCache($schoolYear->school_id);

            $message = $schoolYear->is_active
                ? 'Année scolaire activée avec succès'
                : 'Année scolaire désactivée avec succès';

            return [
                'success' => true,
                'message' => $message,
                'data' => $schoolYear,
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut d\'une année scolaire', [
                'error' => $e->getMessage(),
                'id' => $id,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors du changement de statut',
            ];
        }
    }

    /**
     * Récupérer une année scolaire par ID
     */
    public function findById(int $id): ?SchoolYear
    {
        return $this->repository->findById($id);
    }

    /**
     * Récupérer les statistiques d'une année scolaire
     */
    public function getStatistics(int $schoolYearId): array
    {
        return $this->repository->getStatistics($schoolYearId);
    }

    /**
     * Vérifier si une année scolaire existe
     */
    public function exists(int $id): bool
    {
        return $this->repository->exists($id);
    }
}
