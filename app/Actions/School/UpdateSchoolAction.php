<?php

namespace App\Actions\School;

use App\DTOs\School\UpdateSchoolDTO;
use App\Services\SchoolManagementService;

class UpdateSchoolAction
{
    public function __construct(
        private SchoolManagementService $schoolManagementService
    ) {}

    /**
     * Exécuter l'action de mise à jour d'école
     */
    public function execute(UpdateSchoolDTO $dto): bool
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode($errors));
        }

        // Mettre à jour l'école
        return $this->schoolManagementService->updateSchool($dto);
    }
}
