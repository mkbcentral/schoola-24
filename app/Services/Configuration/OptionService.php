<?php

declare(strict_types=1);

namespace App\Services\Configuration;

use App\Actions\Option\CreateOptionAction;
use App\Actions\Option\UpdateOptionAction;
use App\Actions\Option\DeleteOptionAction;
use App\DTOs\Configuration\OptionDTO;
use App\Models\Option;
use App\Repositories\Configuration\OptionRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class OptionService
{
    public function __construct(
        private OptionRepository $repository,
        private CreateOptionAction $createAction,
        private UpdateOptionAction $updateAction,
        private DeleteOptionAction $deleteAction
    ) {}

    /**
     * Create a new option
     *
     * @param OptionDTO $dto
     * @return array{success: bool, message: string, data: Option|null}
     */
    public function create(OptionDTO $dto): array
    {
        try {
            $option = $this->createAction->execute($dto);

            return [
                'success' => true,
                'message' => 'Option créée avec succès',
                'data' => $option,
            ];
        } catch (Exception $e) {
            Log::error('Error creating option: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la création de l\'option: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Update an existing option
     *
     * @param int $optionId
     * @param OptionDTO $dto
     * @return array{success: bool, message: string, data: Option|null}
     */
    public function update(int $optionId, OptionDTO $dto): array
    {
        try {
            $option = $this->repository->findById($optionId);

            if (!$option) {
                return [
                    'success' => false,
                    'message' => 'Option non trouvée',
                    'data' => null,
                ];
            }

            $option = $this->updateAction->execute($optionId, $dto);

            return [
                'success' => true,
                'message' => 'Option mise à jour avec succès',
                'data' => $option,
            ];
        } catch (Exception $e) {
            Log::error('Error updating option: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'option: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Delete an option
     *
     * @param int $optionId
     * @return array{success: bool, message: string, data: null}
     */
    public function delete(int $optionId): array
    {
        try {
            $option = $this->repository->findById($optionId);

            if (!$option) {
                return [
                    'success' => false,
                    'message' => 'Option non trouvée',
                    'data' => null,
                ];
            }

            $this->deleteAction->execute($optionId);

            return [
                'success' => true,
                'message' => 'Option supprimée avec succès',
                'data' => null,
            ];
        } catch (Exception $e) {
            Log::error('Error deleting option: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'option: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Get option by ID
     *
     * @param int $optionId
     * @return Option|null
     */
    public function findById(int $optionId): ?Option
    {
        return $this->repository->findById($optionId);
    }

    /**
     * Check if option exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->repository->exists($id);
    }

    /**
     * Get all options for a section
     *
     * @param int $sectionId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBySection(int $sectionId)
    {
        return $this->repository->getBySection($sectionId);
    }

    /**
     * Get statistics for an option
     *
     * @param int $optionId
     * @return array
     */
    public function getStatistics(int $optionId): array
    {
        return $this->repository->getStatistics($optionId);
    }

    /**
     * Count classrooms in an option
     *
     * @param int $optionId
     * @return int
     */
    public function countClassRooms(int $optionId): int
    {
        return $this->repository->countClassRooms($optionId);
    }
}
