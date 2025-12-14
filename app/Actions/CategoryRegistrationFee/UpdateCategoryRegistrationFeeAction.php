<?php

namespace App\Actions\CategoryRegistrationFee;

use App\DTOs\Fee\CategoryRegistrationFeeDTO;
use App\Models\CategoryRegistrationFee;
use Illuminate\Support\Facades\DB;

class UpdateCategoryRegistrationFeeAction
{
    /**
     * Mettre à jour une catégorie de frais d'inscription
     */
    public function execute(int $id, CategoryRegistrationFeeDTO $dto): CategoryRegistrationFee
    {
        return DB::transaction(function () use ($id, $dto) {
            $categoryRegistrationFee = CategoryRegistrationFee::findOrFail($id);
            $categoryRegistrationFee->update($dto->toUpdateArray());
            return $categoryRegistrationFee->fresh();
        });
    }
}
