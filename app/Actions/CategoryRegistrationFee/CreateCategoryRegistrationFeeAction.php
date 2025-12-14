<?php

namespace App\Actions\CategoryRegistrationFee;

use App\DTOs\Fee\CategoryRegistrationFeeDTO;
use App\Models\CategoryRegistrationFee;
use Illuminate\Support\Facades\DB;

class CreateCategoryRegistrationFeeAction
{
    /**
     * Créer une nouvelle catégorie de frais d'inscription
     */
    public function execute(CategoryRegistrationFeeDTO $dto): CategoryRegistrationFee
    {
        return DB::transaction(function () use ($dto) {
            return CategoryRegistrationFee::create($dto->toCreateArray());
        });
    }
}
