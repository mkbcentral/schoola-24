<?php

namespace App\Actions\ScolarFee;

use App\DTOs\Fee\ScolarFeeDTO;
use App\Models\ScolarFee;
use Illuminate\Support\Facades\DB;

class UpdateScolarFeeAction
{
    /**
     * Mettre à jour un frais scolaire
     */
    public function execute(int $id, ScolarFeeDTO $dto): ScolarFee
    {
        return DB::transaction(function () use ($id, $dto) {
            $scolarFee = ScolarFee::findOrFail($id);
            $scolarFee->update($dto->toUpdateArray());
            return $scolarFee->fresh();
        });
    }
}
