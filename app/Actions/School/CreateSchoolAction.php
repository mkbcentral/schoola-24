<?php

namespace App\Actions\School;

use App\DTOs\School\CreateSchoolDTO;
use App\Services\SchoolManagementService;

class CreateSchoolAction
{
    public function __construct(
        private SchoolManagementService $schoolManagementService
    ) {}

    /**
     * Exécuter l'action de création d'école avec admin
     */
    public function execute(CreateSchoolDTO $dto): array
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException(json_encode($errors));
        }

        // Créer l'école avec son administrateur
        return $this->schoolManagementService->createSchoolWithAdmin($dto);
    }
}
