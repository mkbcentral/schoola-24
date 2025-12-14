<?php

declare(strict_types=1);

namespace App\Services\Configuration;

use App\Actions\ClassRoom\CreateClassRoomAction;
use App\Actions\ClassRoom\UpdateClassRoomAction;
use App\Actions\ClassRoom\DeleteClassRoomAction;
use App\DTOs\Configuration\ClassRoomDTO;
use App\Models\ClassRoom;
use App\Repositories\Configuration\ClassRoomRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class ClassRoomService
{
    public function __construct(
        private ClassRoomRepository $repository,
        private CreateClassRoomAction $createAction,
        private UpdateClassRoomAction $updateAction,
        private DeleteClassRoomAction $deleteAction
    ) {}

    /**
     * Create a new classroom
     *
     * @param ClassRoomDTO $dto
     * @return array{success: bool, message: string, data: ClassRoom|null}
     */
    public function create(ClassRoomDTO $dto): array
    {
        try {
            $classRoom = $this->createAction->execute($dto);

            return [
                'success' => true,
                'message' => 'Classe créée avec succès',
                'data' => $classRoom,
            ];
        } catch (Exception $e) {
            Log::error('Error creating classroom: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la création de la classe: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Update an existing classroom
     *
     * @param int $classRoomId
     * @param ClassRoomDTO $dto
     * @return array{success: bool, message: string, data: ClassRoom|null}
     */
    public function update(int $classRoomId, ClassRoomDTO $dto): array
    {
        try {
            $classRoom = $this->repository->findById($classRoomId);

            if (!$classRoom) {
                return [
                    'success' => false,
                    'message' => 'Classe non trouvée',
                    'data' => null,
                ];
            }

            $classRoom = $this->updateAction->execute($classRoomId, $dto);

            return [
                'success' => true,
                'message' => 'Classe mise à jour avec succès',
                'data' => $classRoom,
            ];
        } catch (Exception $e) {
            Log::error('Error updating classroom: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la classe: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Delete a classroom
     *
     * @param int $classRoomId
     * @return array{success: bool, message: string, data: null}
     */
    public function delete(int $classRoomId): array
    {
        try {
            $classRoom = $this->repository->findById($classRoomId);

            if (!$classRoom) {
                return [
                    'success' => false,
                    'message' => 'Classe non trouvée',
                    'data' => null,
                ];
            }

            $this->deleteAction->execute($classRoomId);

            return [
                'success' => true,
                'message' => 'Classe supprimée avec succès',
                'data' => null,
            ];
        } catch (Exception $e) {
            Log::error('Error deleting classroom: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression de la classe: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Get classroom by ID
     *
     * @param int $classRoomId
     * @return ClassRoom|null
     */
    public function findById(int $classRoomId): ?ClassRoom
    {
        return $this->repository->findById($classRoomId);
    }

    /**
     * Check if classroom exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->repository->exists($id);
    }

    /**
     * Get all classrooms for an option
     *
     * @param int $optionId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByOption(int $optionId)
    {
        return $this->repository->getByOption($optionId);
    }

    /**
     * Get statistics for a classroom
     *
     * @param int $classRoomId
     * @return array
     */
    public function getStatistics(int $classRoomId): array
    {
        return $this->repository->getStatistics($classRoomId);
    }

    /**
     * Count registrations in a classroom
     *
     * @param int $classRoomId
     * @return int
     */
    public function countRegistrations(int $classRoomId): int
    {
        return $this->repository->countRegistrations($classRoomId);
    }
}
