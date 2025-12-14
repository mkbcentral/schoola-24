<?php

namespace App\Actions\ScolarFee;

use App\Models\ScolarFee;
use Illuminate\Support\Facades\DB;

class DeleteScolarFeeAction
{
    /**
     * Supprimer un frais scolaire
     */
    public function execute(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $scolarFee = ScolarFee::findOrFail($id);
            return $scolarFee->delete();
        });
    }
}
