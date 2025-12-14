<?php

namespace App\Actions\School;

use App\Services\SchoolManagementService;

class CreateSchoolUserAction
{
    public function __construct(
        private SchoolManagementService $schoolManagementService
    ) {}

    /**
     * Exécuter l'action de création d'un utilisateur pour une école
     */
    public function execute(int $schoolId, array $data): array
    {
        // Validation basique
        if ($schoolId <= 0) {
            throw new \InvalidArgumentException('ID d\'école invalide.');
        }

        $requiredFields = ['name', 'username', 'email', 'role_id'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Le champ {$field} est requis.");
            }
        }

        return $this->schoolManagementService->createSchoolUser($schoolId, $data);
    }
}
