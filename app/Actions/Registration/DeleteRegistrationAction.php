<?php

namespace App\Actions\Registration;

use App\Models\Registration;

class DeleteRegistrationAction
{
    /**
     * Delete a registration
     *
     * @throws \Exception
     */
    public function execute(Registration $registration): bool
    {
        try {
            return $registration->delete();
        } catch (\Exception $e) {
            throw new \Exception("Impossible de supprimer l'inscription: {$e->getMessage()}");
        }
    }
}
