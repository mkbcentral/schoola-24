<?php

namespace App\Actions\School;

use App\Services\SchoolManagementService;

class DeleteSchoolAction
{
    public function __construct(
        private SchoolManagementService $schoolManagementService
    ) {}

    /**
     * Exécuter l'action de suppression d'école
     */
    public function execute(int $schoolId): bool
    {
        if ($schoolId <= 0) {
            throw new \InvalidArgumentException('ID d\'école invalide.');
        }

        return $this->schoolManagementService->deleteSchool($schoolId);
    }
}
