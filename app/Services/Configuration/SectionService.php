<?php

declare(strict_types=1);

namespace App\Services\Configuration;

use App\Actions\Section\CreateSectionAction;
use App\Actions\Section\UpdateSectionAction;
use App\Actions\Section\DeleteSectionAction;
use App\DTOs\Configuration\SectionDTO;
use App\Models\Section;
use App\Repositories\Configuration\SectionRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class SectionService
{
    public function __construct(
        private SectionRepository $repository,
        private CreateSectionAction $createAction,
        private UpdateSectionAction $updateAction,
        private DeleteSectionAction $deleteAction
    ) {}

    /**
     * Create a new section
     *
     * @param SectionDTO $dto
     * @return array{success: bool, message: string, data: Section|null}
     */
    public function create(SectionDTO $dto): array
    {
        try {
            $section = $this->createAction->execute($dto);

            return [
                'success' => true,
                'message' => 'Section créée avec succès',
                'data' => $section,
            ];
        } catch (Exception $e) {
            Log::error('Error creating section: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la création de la section: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Update an existing section
     *
     * @param int $sectionId
     * @param SectionDTO $dto
     * @return array{success: bool, message: string, data: Section|null}
     */
    public function update(int $sectionId, SectionDTO $dto): array
    {
        try {
            $section = $this->repository->findById($sectionId);

            if (!$section) {
                return [
                    'success' => false,
                    'message' => 'Section non trouvée',
                    'data' => null,
                ];
            }

            $section = $this->updateAction->execute($sectionId, $dto);

            return [
                'success' => true,
                'message' => 'Section mise à jour avec succès',
                'data' => $section,
            ];
        } catch (Exception $e) {
            Log::error('Error updating section: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la section: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Delete a section
     *
     * @param int $sectionId
     * @return array{success: bool, message: string, data: null}
     */
    public function delete(int $sectionId): array
    {
        try {
            $section = $this->repository->findById($sectionId);

            if (!$section) {
                return [
                    'success' => false,
                    'message' => 'Section non trouvée',
                    'data' => null,
                ];
            }

            $this->deleteAction->execute($sectionId);

            return [
                'success' => true,
                'message' => 'Section supprimée avec succès',
                'data' => null,
            ];
        } catch (Exception $e) {
            Log::error('Error deleting section: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression de la section: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Get section by ID
     *
     * @param int $sectionId
     * @return Section|null
     */
    public function findById(int $sectionId): ?Section
    {
        return $this->repository->findById($sectionId);
    }

    /**
     * Check if section exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->repository->exists($id);
    }

    /**
     * Get all sections for a school
     *
     * @param int $schoolId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBySchool(int $schoolId)
    {
        return $this->repository->getBySchool($schoolId);
    }

    /**
     * Get statistics for a section
     *
     * @param int $sectionId
     * @return array
     */
    public function getStatistics(int $sectionId): array
    {
        return $this->repository->getStatistics($sectionId);
    }

    /**
     * Count options in a section
     *
     * @param int $sectionId
     * @return int
     */
    public function countOptions(int $sectionId): int
    {
        return $this->repository->countOptions($sectionId);
    }
}
