<?php

namespace App\Actions\RegistrationFee;

use App\DTOs\Fee\RegistrationFeeDTO;
use App\Models\RegistrationFee;
use Illuminate\Support\Facades\DB;

class UpdateRegistrationFeeAction
{
    /**
     * Mettre à jour un frais d'inscription
     */
    public function execute(int $id, RegistrationFeeDTO $dto): RegistrationFee
    {
        return DB::transaction(function () use ($id, $dto) {
            $registrationFee = RegistrationFee::findOrFail($id);
            $registrationFee->update($dto->toUpdateArray());
            return $registrationFee->fresh();
        });
    }
}
