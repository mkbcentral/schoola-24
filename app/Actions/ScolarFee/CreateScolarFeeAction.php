<?php

namespace App\Actions\ScolarFee;

use App\DTOs\Fee\ScolarFeeDTO;
use App\Models\ScolarFee;
use Illuminate\Support\Facades\DB;

class CreateScolarFeeAction
{
    /**
     * Créer un nouveau frais scolaire
     */
    public function execute(ScolarFeeDTO $dto): ScolarFee
    {
        return DB::transaction(function () use ($dto) {
            return ScolarFee::create($dto->toCreateArray());
        });
    }
}
