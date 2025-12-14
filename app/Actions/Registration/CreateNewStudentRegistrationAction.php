<?php

namespace App\Actions\Registration;

use App\DTOs\Registration\CreateRegistrationDTO;
use App\DTOs\Registration\CreateStudentDTO;
use App\Models\Registration;

class CreateNewStudentRegistrationAction
{
    public function __construct(
        private readonly CreateStudentAction $createStudentAction,
        private readonly CreateRegistrationAction $createRegistrationAction,
    ) {}

    /**
     * Create a new student and their registration in a single transaction
     */
    public function execute(
        CreateStudentDTO $studentDTO,
        CreateRegistrationDTO $registrationDTO
    ): Registration {
        return \DB::transaction(function () use ($studentDTO, $registrationDTO) {
            // Créer l'étudiant
            $student = $this->createStudentAction->execute($studentDTO);

            // Créer l'inscription avec l'ID de l'étudiant
            $registrationData = $registrationDTO->toArray();
            $registrationData['student_id'] = $student->id;

            $newRegistrationDTO = CreateRegistrationDTO::fromArray($registrationData);

            return $this->createRegistrationAction->execute($newRegistrationDTO);
        });
    }
}
