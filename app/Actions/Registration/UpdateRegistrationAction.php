<?php

namespace App\Actions\Registration;

use App\DTOs\Registration\UpdateRegistrationDTO;
use App\Models\Registration;

class UpdateRegistrationAction
{
    /**
     * Update an existing registration
     */
    public function execute(Registration $registration, UpdateRegistrationDTO $dto): Registration
    {
        $data = $dto->toArray();

        if (!empty($data)) {
            $registration->update($data);
            $registration->refresh();
        }

        return $registration;
    }
}
