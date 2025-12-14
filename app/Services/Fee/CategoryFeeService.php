<?php

declare(strict_types=1);

namespace App\Services\Fee;

use App\Actions\CategoryFee\CreateCategoryFeeAction;
use App\Actions\CategoryFee\UpdateCategoryFeeAction;
use App\Actions\CategoryFee\DeleteCategoryFeeAction;
use App\DTOs\Fee\CategoryFeeDTO;
use App\Models\CategoryFee;
use App\Repositories\Fee\CategoryFeeRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryFeeService
{
    public function __construct(
        private CategoryFeeRepository $repository,
        private CreateCategoryFeeAction $createAction,
        private UpdateCategoryFeeAction $updateAction,
        private DeleteCategoryFeeAction $deleteAction,
    ) {}

    /**
     * Créer une nouvelle catégorie de frais scolaires
     */
    public function create(CategoryFeeDTO $dto): ?CategoryFee
    {
        try {
            // Vérifier si la catégorie existe déjà
            if ($this->repository->exists($dto->name, $dto->schoolYearId ?? 0)) {
                Log::warning('Tentative de création d\'une catégorie existante', [
                    'name' => $dto->name,
                    'school_year_id' => $dto->schoolYearId,
                ]);
                return null;
            }

            $categoryFee = $this->createAction->execute($dto);
            $this->repository->clearCache();

            Log::info('Catégorie de frais scolaires créée', [
                'id' => $categoryFee->id,
                'name' => $categoryFee->name,
            ]);

            return $categoryFee;
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la catégorie', [
                'error' => $e->getMessage(),
                'dto' => $dto->toArray(),
            ]);
            throw $e;
        }
    }

    /**
     * Mettre à jour une catégorie de frais scolaires
     */
    public function update(int $id, CategoryFeeDTO $dto): ?CategoryFee
    {
        try {
            $existing = $this->repository->findById($id);
            if (!$existing) {
                Log::warning('Catégorie de frais scolaires non trouvée', ['id' => $id]);
                return null;
            }

            // Vérifier si le nouveau nom existe déjà
            if ($this->repository->exists($dto->name, $existing->school_year_id, $id)) {
                Log::warning('Le nom de catégorie existe déjà', [
                    'name' => $dto->name,
                    'exclude_id' => $id,
                ]);
                return null;
            }

            $categoryFee = $this->updateAction->execute($id, $dto);
            $this->repository->clearCache();

            Log::info('Catégorie de frais scolaires mise à jour', [
                'id' => $categoryFee->id,
                'name' => $categoryFee->name,
            ]);

            return $categoryFee;
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la catégorie', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Supprimer une catégorie de frais scolaires
     */
    public function delete(int $id): bool
    {
        try {
            $existing = $this->repository->findById($id);
            if (!$existing) {
                Log::warning('Catégorie de frais scolaires non trouvée pour suppression', ['id' => $id]);
                return false;
            }

            $result = $this->deleteAction->execute($id);
            $this->repository->clearCache();

            Log::info('Catégorie de frais scolaires supprimée', ['id' => $id]);

            return $result;
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la catégorie', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Récupérer une catégorie par ID
     */
    public function findById(int $id): ?CategoryFee
    {
        return $this->repository->findById($id);
    }

    /**
     * Récupérer toutes les catégories
     */
    public function getAll()
    {
        return $this->repository->all();
    }

    /**
     * Récupérer les catégories par école
     */
    public function getBySchool(int $schoolId)
    {
        return $this->repository->getBySchool($schoolId);
    }

    /**
     * Récupérer les catégories par année scolaire
     */
    public function getBySchoolYear(int $schoolYearId)
    {
        return $this->repository->getBySchoolYear($schoolYearId);
    }

    /**
     * Récupérer les catégories de frais d'état
     */
    public function getStateFees(int $schoolYearId)
    {
        return $this->repository->getStateFees($schoolYearId);
    }

    /**
     * Vérifier si une catégorie existe
     */
    public function exists(string $name, int $schoolYearId, ?int $excludeId = null): bool
    {
        return $this->repository->exists($name, $schoolYearId, $excludeId);
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(int $schoolId): array
    {
        return $this->repository->getStatistics($schoolId);
    }
}
