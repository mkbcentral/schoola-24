<?php

namespace App\Domain\Features\Configuration;

use App\Domain\Contract\Configuration\IFeeDataConfiguration;
use App\Models\CategoryFee;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\ScolarFee;

class FeeDataConfiguration implements IFeeDataConfiguration
{

    /**
     * @inheritDoc
     */
    public static function getListCategoryFee(int $per_page): mixed
    {
        return CategoryFee::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->paginate($per_page);
    }
    /**
     * @inheritDoc
     */
    public static function getListScalarFee(
        int $categoryId,
        int $optionId,
        int $classRoomId,
        int $per_page
    ): mixed {
        return ScolarFee::query()
            ->join('category_fees', 'category_fees.id', 'scolar_fees.category_fee_id')
            ->join('class_rooms', 'class_rooms.id', 'scolar_fees.class_room_id')
            ->join('options', 'class_rooms.option_id', 'options.id')
            ->where('category_fee_id', $categoryId)
            ->when(
                $optionId,
                function ($query, $f) {
                    return $query->where('class_rooms.option_id', $f);
                }
            )
            ->when(
                $classRoomId,
                function ($query, $f) {
                    return $query->where('scolar_fees.class_room_id', $f);
                }
            )
            ->where('category_fees.school_id', School::DEFAULT_SCHOOL_ID())
            ->select('scolar_fees.*')
            ->paginate($per_page);
    }
    /**
     * @inheritDoc
     */
    public static function getListCategoryFeeForCurrentSchool(): CategoryFee
    {
        return CategoryFee::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->first();
    }
}
