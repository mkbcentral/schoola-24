<?php

namespace App\Actions\RegistrationFee;

use App\Models\RegistrationFee;
use Illuminate\Support\Facades\DB;

class DeleteRegistrationFeeAction
{
    /**
     * Supprimer un frais d'inscription
     */
    public function execute(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $registrationFee = RegistrationFee::findOrFail($id);
            return $registrationFee->delete();
        });
    }
}
