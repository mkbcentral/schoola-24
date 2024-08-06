<?php

namespace App\Domain\Features\Configuration;

use App\Domain\Contract\Configuration\IFeeDataConfiguration;
use App\Models\CategoryFee;
use App\Models\ScolarFee;

class FeeDataConfiguration implements IFeeDataConfiguration
{

    /**
     * @inheritDoc
     */
    public static function getListCategoryFee(int $per_page): mixed
    {
        return CategoryFee::query()->paginate($per_page);
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
            ->join('class_rooms', 'class_rooms.id', 'scolar_fees.class_room_id')
            ->join('options', 'class_rooms.option_id', 'options.id')
            ->where('category_fee_id', $categoryId)
            ->when(
                $optionId,
                function ($query, $f) {
                    return $query->where('scolar_fees.class_room_id', $f);
                }
            )
            ->when(
                $classRoomId,
                function ($query, $f) {
                    return $query->where('class_rooms.option_id', $f);
                }
            )
            ->select('scolar_fees.*')
            ->paginate($per_page);
    }
}
