<?php

namespace App\Actions\CategoryRegistrationFee;

use App\Models\CategoryRegistrationFee;
use Illuminate\Support\Facades\DB;

class DeleteCategoryRegistrationFeeAction
{
    /**
     * Supprimer une catégorie de frais d'inscription
     */
    public function execute(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $categoryRegistrationFee = CategoryRegistrationFee::findOrFail($id);
            return $categoryRegistrationFee->delete();
        });
    }
}
