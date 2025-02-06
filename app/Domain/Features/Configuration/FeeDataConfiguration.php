<?php

namespace App\Domain\Features\Configuration;

use App\Domain\Contract\Configuration\IFeeDataConfiguration;
use App\Enums\RoleType;
use App\Models\CategoryFee;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\ScolarFee;
use Illuminate\Support\Facades\Auth;

class FeeDataConfiguration implements IFeeDataConfiguration
{

    /**
     * @param int $per_page
     * @param string|null $search
     * @return mixed
     */
    public static function getListCategoryFee(int $per_page, ?string $search = ''): mixed
    {
        // TODO: Implement getListCategoryFee() method.
        $filters=['search'=>$search];
        return CategoryFee::query()
            ->filter($filters)
            ->where('name', 'like', '%' . $search . '%')
            ->paginate($per_page);

    }

    /**
     * @param int $per_page
     * @param string|null $search
     * @return mixed
     */
    public static function getListCategoryFeeForSpecificUser(int $per_page, ?string $search = ''): mixed
    {
        // TODO: Implement getListCategoryFeeForSpecificUser() method.
        $filters=['search'=>$search];
        if (Auth::user()->role->name==RoleType::SCHOOL_GUARD){
            return CategoryFee::query()
                ->filter($filters)
                ->where('name', 'like', '%' . $search . '%')
                ->where('is_accessory', true)
                ->paginate($per_page);
        }else if (
            Auth::user()->role->name==RoleType::SCHOOL_MANAGER ||
            Auth::user()->role->name==RoleType::SCHOOL_BOSS){
            return CategoryFee::query()
                ->filter($filters)
                ->where('name', 'like', '%' . $search . '%')
                ->where('is_accessory', false)
                ->paginate($per_page);
        }else{
            return CategoryFee::query()
                ->filter($filters)
                ->where('name', 'like', '%' . $search . '%')
                ->paginate($per_page);
        }


    }

    /**
     * @param int|null $categoryId
     * @param int|null $optionId
     * @param int|null $classRoomId
     * @param int $per_page
     * @return mixed
     */
    public static function getListScalarFee(?int $categoryId, ?int $optionId, ?int $classRoomId, int $per_page = 10): mixed
    {
        // TODO: Implement getListScalarFee() method.
        $filters = self::getFilters($categoryId, $optionId, $classRoomId);
        return ScolarFee::query()
            ->filter($filters)
            ->paginate($per_page);

    }

    /**
     * @param int|null $categoryId
     * @param int|null $optionId
     * @param int|null $classRoomId
     * @return mixed
     */
    public static function getListScalarFeeNotPaginate(?int $categoryId, ?int $optionId, ?int $classRoomId,): mixed
    {
        // TODO: Implement getListScalarFeeNotPaginate() method.
        $filters = self::getFilters($categoryId, $optionId, $classRoomId);
        return ScolarFee::query()
            ->filter($filters)
            ->get();
    }

    /**
     * @return CategoryFee
     */
    public static function getFirstCategoryFee(): CategoryFee
    {
       
        if (Auth::user()->role->name == RoleType::SCHOOL_GUARD){
            return CategoryFee::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('is_accessory',true)
                ->first();
        }else{
            return CategoryFee::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->first();
        }

    }
    /**
     * @param int|null $categoryId
     * @param int|null $optionId
     * @param int|null $classRoomId
     * @return int[]|null[]
     */
    public static function getFilters(
        ?int $categoryId,
        ?int $optionId,
        ?int $classRoomId): array
    {
        return [
            'category_fee_id' => $categoryId
            , 'option_id' => $optionId,
            'class_room_id' => $classRoomId,
        ];
    }
}
