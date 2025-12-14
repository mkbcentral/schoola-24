<?php

namespace App\Actions\RegistrationFee;

use App\DTOs\Fee\RegistrationFeeDTO;
use App\Models\RegistrationFee;
use Illuminate\Support\Facades\DB;

class CreateRegistrationFeeAction
{
    /**
     * Créer un nouveau frais d'inscription
     */
    public function execute(RegistrationFeeDTO $dto): RegistrationFee
    {
        return DB::transaction(function () use ($dto) {
            return RegistrationFee::create($dto->toCreateArray());
        });
    }
}
