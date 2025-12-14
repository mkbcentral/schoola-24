<?php

namespace App\Actions\CategoryFee;

use App\DTOs\Fee\CategoryFeeDTO;
use App\Models\CategoryFee;
use Illuminate\Support\Facades\DB;

class CreateCategoryFeeAction
{
    /**
     * Créer une nouvelle catégorie de frais scolaires
     */
    public function execute(CategoryFeeDTO $dto): CategoryFee
    {
        return DB::transaction(function () use ($dto) {
            return CategoryFee::create($dto->toCreateArray());
        });
    }
}
