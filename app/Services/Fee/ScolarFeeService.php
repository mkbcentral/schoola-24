<?php

declare(strict_types=1);

namespace App\Services\Fee;

use App\Actions\ScolarFee\CreateScolarFeeAction;
use App\Actions\ScolarFee\UpdateScolarFeeAction;
use App\Actions\ScolarFee\DeleteScolarFeeAction;
use App\DTOs\Fee\ScolarFeeDTO;
use App\Models\ScolarFee;
use App\Repositories\Fee\ScolarFeeRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class ScolarFeeService
{
    public function __construct(
        private ScolarFeeRepository $repository,
        private CreateScolarFeeAction $createAction,
        private UpdateScolarFeeAction $updateAction,
        private DeleteScolarFeeAction $deleteAction,
    ) {}

    /**
     * Créer un nouveau frais scolaire
     */
    public function create(ScolarFeeDTO $dto): ?ScolarFee
    {
        try {
            // Vérifier si le frais existe déjà
            if ($this->repository->exists($dto->name, $dto->categoryFeeId ?? 0, $dto->classRoomId ?? 0)) {
                Log::warning('Tentative de création d\'un frais existant', [
                    'name' => $dto->name,
                    'category_fee_id' => $dto->categoryFeeId,
                    'class_room_id' => $dto->classRoomId,
                ]);
                return null;
            }

            $scolarFee = $this->createAction->execute($dto);
            $this->repository->clearCache();

            Log::info('Frais scolaire créé', [
                'id' => $scolarFee->id,
                'name' => $scolarFee->name,
                'amount' => $scolarFee->amount,
            ]);

            return $scolarFee;
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du frais scolaire', [
                'error' => $e->getMessage(),
                'dto' => $dto->toArray(),
            ]);
            throw $e;
        }
    }

    /**
     * Mettre à jour un frais scolaire
     */
    public function update(int $id, ScolarFeeDTO $dto): ?ScolarFee
    {
        try {
            $existing = $this->repository->findById($id);
            if (!$existing) {
                Log::warning('Frais scolaire non trouvé', ['id' => $id]);
                return null;
            }

            // Vérifier si le nouveau nom existe déjà
            if ($this->repository->exists($dto->name, $existing->category_fee_id, $existing->class_room_id, $id)) {
                Log::warning('Le nom de frais existe déjà', [
                    'name' => $dto->name,
                    'exclude_id' => $id,
                ]);
                return null;
            }

            $scolarFee = $this->updateAction->execute($id, $dto);
            $this->repository->clearCache();

            Log::info('Frais scolaire mis à jour', [
                'id' => $scolarFee->id,
                'name' => $scolarFee->name,
                'amount' => $scolarFee->amount,
            ]);

            return $scolarFee;
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du frais scolaire', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Supprimer un frais scolaire
     */
    public function delete(int $id): bool
    {
        try {
            $existing = $this->repository->findById($id);
            if (!$existing) {
                Log::warning('Frais scolaire non trouvé pour suppression', ['id' => $id]);
                return false;
            }

            $result = $this->deleteAction->execute($id);
            $this->repository->clearCache();

            Log::info('Frais scolaire supprimé', ['id' => $id]);

            return $result;
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du frais scolaire', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Récupérer un frais par ID
     */
    public function findById(int $id): ?ScolarFee
    {
        return $this->repository->findById($id);
    }

    /**
     * Récupérer tous les frais
     */
    public function getAll()
    {
        return $this->repository->all();
    }

    /**
     * Récupérer les frais par catégorie
     */
    public function getByCategoryFee(int $categoryFeeId)
    {
        return $this->repository->getByCategoryFee($categoryFeeId);
    }

    /**
     * Récupérer les frais par classe
     */
    public function getByClassRoom(int $classRoomId)
    {
        return $this->repository->getByClassRoom($classRoomId);
    }

    /**
     * Récupérer les frais modifiés
     */
    public function getChanged()
    {
        return $this->repository->getChanged();
    }

    /**
     * Vérifier si un frais existe
     */
    public function exists(string $name, int $categoryFeeId, int $classRoomId, ?int $excludeId = null): bool
    {
        return $this->repository->exists($name, $categoryFeeId, $classRoomId, $excludeId);
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(int $categoryFeeId): array
    {
        return $this->repository->getStatistics($categoryFeeId);
    }
}
