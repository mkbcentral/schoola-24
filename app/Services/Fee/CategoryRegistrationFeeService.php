<?php

declare(strict_types=1);

namespace App\Services\Fee;

use App\Actions\CategoryRegistrationFee\CreateCategoryRegistrationFeeAction;
use App\Actions\CategoryRegistrationFee\UpdateCategoryRegistrationFeeAction;
use App\Actions\CategoryRegistrationFee\DeleteCategoryRegistrationFeeAction;
use App\DTOs\Fee\CategoryRegistrationFeeDTO;
use App\Models\CategoryRegistrationFee;
use App\Repositories\Fee\CategoryRegistrationFeeRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryRegistrationFeeService
{
    public function __construct(
        private CategoryRegistrationFeeRepository $repository,
        private CreateCategoryRegistrationFeeAction $createAction,
        private UpdateCategoryRegistrationFeeAction $updateAction,
        private DeleteCategoryRegistrationFeeAction $deleteAction,
    ) {}

    /**
     * Créer une nouvelle catégorie de frais d'inscription
     */
    public function create(CategoryRegistrationFeeDTO $dto): ?CategoryRegistrationFee
    {
        try {
            // Vérifier si la catégorie existe déjà
            if ($this->repository->exists($dto->name, $dto->schoolId ?? 0)) {
                Log::warning('Tentative de création d\'une catégorie existante', [
                    'name' => $dto->name,
                    'school_id' => $dto->schoolId,
                ]);
                return null;
            }

            $categoryRegistrationFee = $this->createAction->execute($dto);
            $this->repository->clearCache();

            Log::info('Catégorie de frais d\'inscription créée', [
                'id' => $categoryRegistrationFee->id,
                'name' => $categoryRegistrationFee->name,
            ]);

            return $categoryRegistrationFee;
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la catégorie', [
                'error' => $e->getMessage(),
                'dto' => $dto->toArray(),
            ]);
            throw $e;
        }
    }

    /**
     * Mettre à jour une catégorie de frais d'inscription
     */
    public function update(int $id, CategoryRegistrationFeeDTO $dto): ?CategoryRegistrationFee
    {
        try {
            $existing = $this->repository->findById($id);
            if (!$existing) {
                Log::warning('Catégorie de frais d\'inscription non trouvée', ['id' => $id]);
                return null;
            }

            // Vérifier si le nouveau nom existe déjà
            if ($this->repository->exists($dto->name, $dto->schoolId ?? $existing->school_id, $id)) {
                Log::warning('Le nom de catégorie existe déjà', [
                    'name' => $dto->name,
                    'exclude_id' => $id,
                ]);
                return null;
            }

            $categoryRegistrationFee = $this->updateAction->execute($id, $dto);
            $this->repository->clearCache();

            Log::info('Catégorie de frais d\'inscription mise à jour', [
                'id' => $categoryRegistrationFee->id,
                'name' => $categoryRegistrationFee->name,
            ]);

            return $categoryRegistrationFee;
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la catégorie', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Supprimer une catégorie de frais d'inscription
     */
    public function delete(int $id): bool
    {
        try {
            $existing = $this->repository->findById($id);
            if (!$existing) {
                Log::warning('Catégorie de frais d\'inscription non trouvée pour suppression', ['id' => $id]);
                return false;
            }

            $result = $this->deleteAction->execute($id);
            $this->repository->clearCache();

            Log::info('Catégorie de frais d\'inscription supprimée', ['id' => $id]);

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
    public function findById(int $id): ?CategoryRegistrationFee
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
     * Récupérer les catégories par statut (ancien/nouveau)
     */
    public function getByOldStatus(int $schoolId, bool $isOld)
    {
        return $this->repository->getByOldStatus($schoolId, $isOld);
    }

    /**
     * Vérifier si une catégorie existe
     */
    public function exists(string $name, int $schoolId, ?int $excludeId = null): bool
    {
        return $this->repository->exists($name, $schoolId, $excludeId);
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(int $schoolId): array
    {
        return $this->repository->getStatistics($schoolId);
    }
}
