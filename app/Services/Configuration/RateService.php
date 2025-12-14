<?php

namespace App\Services\Configuration;

use App\Actions\Rate\CreateRateAction;
use App\Actions\Rate\UpdateRateAction;
use App\Actions\Rate\DeleteRateAction;
use App\Actions\Rate\ToggleRateStatusAction;
use App\DTOs\Configuration\RateDTO;
use App\Models\Rate;
use App\Repositories\Configuration\RateRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Service de gestion des taux
 * Responsabilités : CRUD, récupération et statistiques
 */
class RateService
{
    public function __construct(
        private RateRepository $repository,
        private CreateRateAction $createAction,
        private UpdateRateAction $updateAction,
        private DeleteRateAction $deleteAction,
        private ToggleRateStatusAction $toggleStatusAction,
    ) {}

    /**
     * Récupérer tous les taux
     */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Récupérer les taux d'une école
     */
    public function getBySchool(int $schoolId): Collection
    {
        return $this->repository->getBySchool($schoolId);
    }

    /**
     * Récupérer le taux par défaut d'une école
     */
    public function getDefaultBySchool(int $schoolId): ?Rate
    {
        return $this->repository->getDefaultBySchool($schoolId);
    }

    /**
     * Créer un nouveau taux
     */
    public function create(RateDTO $dto): array
    {
        try {
            $rate = $this->createAction->execute(dto: $dto);

            // Vider le cache
            $this->repository->clearCache($dto->schoolId);

            return [
                'success' => true,
                'message' => 'Taux créé avec succès',
                'data' => $rate,
            ];
        } catch (\InvalidArgumentException $e) {
            Log::error('Erreur de validation lors de la création d\'un taux', [
                'error' => $e->getMessage(),
                'dto' => $dto,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => json_decode($e->getMessage(), true),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création d\'un taux', [
                'error' => $e->getMessage(),
                'dto' => $dto,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la création du taux',
            ];
        }
    }

    /**
     * Mettre à jour un taux
     */
    public function update(int $id, RateDTO $dto): array
    {
        try {
            $rate = $this->updateAction->execute($id, $dto);

            // Vider le cache
            $this->repository->clearCache($dto->schoolId);

            return [
                'success' => true,
                'message' => 'Taux mis à jour avec succès',
                'data' => $rate,
            ];
        } catch (\InvalidArgumentException $e) {
            Log::error('Erreur de validation lors de la mise à jour d\'un taux', [
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
            Log::error('Erreur lors de la mise à jour d\'un taux', [
                'error' => $e->getMessage(),
                'id' => $id,
                'dto' => $dto,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du taux',
            ];
        }
    }

    /**
     * Supprimer un taux
     */
    public function delete(int $id): array
    {
        try {
            $rate = $this->repository->findById($id);

            if (!$rate) {
                return [
                    'success' => false,
                    'message' => 'Taux non trouvé',
                ];
            }

            $this->deleteAction->execute($id);

            // Vider le cache
            $this->repository->clearCache($rate->school_id);

            return [
                'success' => true,
                'message' => 'Taux supprimé avec succès',
            ];
        } catch (\RuntimeException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression d\'un taux', [
                'error' => $e->getMessage(),
                'id' => $id,
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression du taux',
            ];
        }
    }

    /**
     * Basculer le statut is_changed d'un taux
     */
    public function toggleStatus(int $id): array
    {
        try {
            $rate = $this->toggleStatusAction->execute($id);

            // Vider le cache
            $this->repository->clearCache($rate->school_id);

            $message = !$rate->is_changed
                ? 'Taux défini comme taux par défaut'
                : 'Taux marqué comme modifié';

            return [
                'success' => true,
                'message' => $message,
                'data' => $rate,
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut d\'un taux', [
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
     * Récupérer un taux par ID
     */
    public function findById(int $id): ?Rate
    {
        return $this->repository->findById($id);
    }

    /**
     * Récupérer les statistiques d'un taux
     */
    public function getStatistics(int $rateId): array
    {
        return $this->repository->getStatistics($rateId);
    }

    /**
     * Vérifier si un taux existe
     */
    public function exists(int $id): bool
    {
        return $this->repository->exists($id);
    }
}
