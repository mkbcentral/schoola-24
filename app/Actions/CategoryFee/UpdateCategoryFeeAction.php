<?php

namespace App\Actions\CategoryFee;

use App\DTOs\Fee\CategoryFeeDTO;
use App\Models\CategoryFee;
use Illuminate\Support\Facades\DB;

class UpdateCategoryFeeAction
{
    /**
     * Mettre à jour une catégorie de frais scolaires
     */
    public function execute(int $id, CategoryFeeDTO $dto): CategoryFee
    {
        return DB::transaction(function () use ($id, $dto) {
            $categoryFee = CategoryFee::findOrFail($id);
            $categoryFee->update($dto->toUpdateArray());
            return $categoryFee->fresh();
        });
    }
}
