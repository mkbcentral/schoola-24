<?php

declare(strict_types=1);

namespace App\Services\Fee;

use App\Actions\RegistrationFee\CreateRegistrationFeeAction;
use App\Actions\RegistrationFee\UpdateRegistrationFeeAction;
use App\Actions\RegistrationFee\DeleteRegistrationFeeAction;
use App\DTOs\Fee\RegistrationFeeDTO;
use App\Models\RegistrationFee;
use App\Repositories\Fee\RegistrationFeeRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class RegistrationFeeService
{
    public function __construct(
        private RegistrationFeeRepository $repository,
        private CreateRegistrationFeeAction $createAction,
        private UpdateRegistrationFeeAction $updateAction,
        private DeleteRegistrationFeeAction $deleteAction,
    ) {}

    /**
     * Créer un nouveau frais d'inscription
     */
    public function create(RegistrationFeeDTO $dto): ?RegistrationFee
    {
        try {
            // Vérifier si le frais existe déjà
            if ($this->repository->exists($dto->name, $dto->optionId ?? 0, $dto->categoryRegistrationFeeId ?? 0)) {
                Log::warning('Tentative de création d\'un frais existant', [
                    'name' => $dto->name,
                    'option_id' => $dto->optionId,
                    'category_registration_fee_id' => $dto->categoryRegistrationFeeId,
                ]);
                return null;
            }

            $registrationFee = $this->createAction->execute($dto);
            $this->repository->clearCache();

            Log::info('Frais d\'inscription créé', [
                'id' => $registrationFee->id,
                'name' => $registrationFee->name,
                'amount' => $registrationFee->amount,
            ]);

            return $registrationFee;
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du frais d\'inscription', [
                'error' => $e->getMessage(),
                'dto' => $dto->toArray(),
            ]);
            throw $e;
        }
    }

    /**
     * Mettre à jour un frais d'inscription
     */
    public function update(int $id, RegistrationFeeDTO $dto): ?RegistrationFee
    {
        try {
            $existing = $this->repository->findById($id);
            if (!$existing) {
                Log::warning('Frais d\'inscription non trouvé', ['id' => $id]);
                return null;
            }

            // Vérifier si le nouveau nom existe déjà
            if ($this->repository->exists($dto->name, $existing->option_id, $existing->category_registration_fee_id, $id)) {
                Log::warning('Le nom de frais existe déjà', [
                    'name' => $dto->name,
                    'exclude_id' => $id,
                ]);
                return null;
            }

            $registrationFee = $this->updateAction->execute($id, $dto);
            $this->repository->clearCache();

            Log::info('Frais d\'inscription mis à jour', [
                'id' => $registrationFee->id,
                'name' => $registrationFee->name,
                'amount' => $registrationFee->amount,
            ]);

            return $registrationFee;
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du frais d\'inscription', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Supprimer un frais d'inscription
     */
    public function delete(int $id): bool
    {
        try {
            $existing = $this->repository->findById($id);
            if (!$existing) {
                Log::warning('Frais d\'inscription non trouvé pour suppression', ['id' => $id]);
                return false;
            }

            $result = $this->deleteAction->execute($id);
            $this->repository->clearCache();

            Log::info('Frais d\'inscription supprimé', ['id' => $id]);

            return $result;
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du frais d\'inscription', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Récupérer un frais par ID
     */
    public function findById(int $id): ?RegistrationFee
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
     * Récupérer les frais par option
     */
    public function getByOption(int $optionId)
    {
        return $this->repository->getByOption($optionId);
    }

    /**
     * Récupérer les frais par catégorie
     */
    public function getByCategoryRegistrationFee(int $categoryRegistrationFeeId)
    {
        return $this->repository->getByCategoryRegistrationFee($categoryRegistrationFeeId);
    }

    /**
     * Récupérer les frais par année scolaire
     */
    public function getBySchoolYear(int $schoolYearId)
    {
        return $this->repository->getBySchoolYear($schoolYearId);
    }

    /**
     * Vérifier si un frais existe
     */
    public function exists(string $name, int $optionId, int $categoryRegistrationFeeId, ?int $excludeId = null): bool
    {
        return $this->repository->exists($name, $optionId, $categoryRegistrationFeeId, $excludeId);
    }

    /**
     * Obtenir les statistiques
     */
    public function getStatistics(int $categoryRegistrationFeeId): array
    {
        return $this->repository->getStatistics($categoryRegistrationFeeId);
    }
}
