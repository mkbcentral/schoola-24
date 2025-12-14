<?php

namespace App\Actions\CategoryFee;

use App\Models\CategoryFee;
use Illuminate\Support\Facades\DB;

class DeleteCategoryFeeAction
{
    /**
     * Supprimer une catégorie de frais scolaires
     */
    public function execute(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $categoryFee = CategoryFee::findOrFail($id);
            return $categoryFee->delete();
        });
    }
}
