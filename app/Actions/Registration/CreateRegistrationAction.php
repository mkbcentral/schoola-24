<?php

namespace App\Actions\Registration;

use App\DTOs\Registration\CreateRegistrationDTO;
use App\Models\Registration;
use App\Models\SchoolYear;

class CreateRegistrationAction
{
    /**
     * Create a new registration
     */
    public function execute(CreateRegistrationDTO $dto): Registration
    {
        $data = $dto->toArray();

        // Si school_year_id n'est pas fourni, utiliser l'année scolaire par défaut
        if (!isset($data['school_year_id'])) {
            $data['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        }

        // Générer un code unique si non fourni
        if (!isset($data['code'])) {
            $data['code'] = $this->generateRegistrationCode();
        }

        return Registration::create($data);
    }

    /**
     * Generate a unique registration code
     */
    private function generateRegistrationCode(): string
    {
        $year = date('Y');
        $lastRegistration = Registration::query()
            ->where('code', 'like', "REG-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRegistration) {
            $lastNumber = (int) substr($lastRegistration->code, -6);
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '000001';
        }

        return "REG-{$year}-{$newNumber}";
    }
}
