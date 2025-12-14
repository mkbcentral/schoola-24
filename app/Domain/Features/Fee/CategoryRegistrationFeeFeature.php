<?php

namespace App\Domain\Features\Fee;

use App\Domain\Contract\Fee\ICategoryRegistrationFee;
use App\Models\CategoryRegistrationFee;
use App\Models\School;

class CategoryRegistrationFeeFeature implements ICategoryRegistrationFee
{
    /**
     * {@inheritDoc}
     */
    public static function getListCategoryRegistrationFee(
        string $q,
        int $per_page = 5
    ): mixed {
        return CategoryRegistrationFee::query()
            ->where('name', 'like', '%' . $q . '%')
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->paginate($per_page);
    }
}
