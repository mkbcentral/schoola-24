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
    /**
     * Liste paginée des catégories de frais.
     */
    public static function getListCategoryFee(int $per_page, ?string $search = ''): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $filters = ['search' => $search];
        return CategoryFee::query()
            ->filter($filters)
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate($per_page);
    }

    /**
     * @param int $per_page
     * @param string|null $search
     * @return mixed
     */
    /**
     * Liste paginée des catégories de frais selon le rôle utilisateur.
     */
    public static function getListCategoryFeeForSpecificUser(int $per_page, ?string $search = ''): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if (!Auth::check() || !Auth::user()?->role) {
            return CategoryFee::query()->paginate($per_page);
        }
        $role = Auth::user()->role->name;
        $filters = ['search' => $search];
        $query = CategoryFee::query()->filter($filters)->when($search, fn($q) => $q->where('name', 'like', "%$search%"));
        if ($role === RoleType::SCHOOL_GUARD) {
            $query->where('is_accessory', true);
        } elseif (in_array($role, [RoleType::SCHOOL_MANAGER, RoleType::SCHOOL_BOSS])) {
            $query->where('is_accessory', false);
        }
        return $query->paginate($per_page);
    }

    /**
     * @param int|null $categoryId
     * @param int|null $optionId
     * @param int|null $classRoomId
     * @param int $per_page
     * @return mixed
     */
    /**
     * Liste paginée des scolarités filtrées.
     */
    public static function getListScalarFee(?int $categoryId, ?int $optionId, ?int $classRoomId, int $per_page = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
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
    /**
     * Liste non paginée des scolarités filtrées.
     */
    public static function getListScalarFeeNotPaginate(?int $categoryId, ?int $optionId, ?int $classRoomId): \Illuminate\Support\Collection
    {
        $filters = self::getFilters($categoryId, $optionId, $classRoomId);
        return ScolarFee::query()
            ->filter($filters)
            ->get();
    }


    /**
     * Premier CategoryFee selon le rôle utilisateur.
     */
    public static function getFirstCategoryFee(): ?CategoryFee
    {
        if (!Auth::check() || !Auth::user()?->role) {
            return null;
        }
        $role = Auth::user()->role->name;
        $query = CategoryFee::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
        if ($role === RoleType::SCHOOL_GUARD) {
            $query->where('is_accessory', true);
        }
        return $query->first() ?? null;
    }
    /**
     * @param int|null $categoryId
     * @param int|null $optionId
     * @param int|null $classRoomId
     * @return int[]|null[]
     */
    /**
     * Génère un tableau de filtres pour les requêtes.
     */
    public static function getFilters(
        ?int $categoryId,
        ?int $optionId,
        ?int $classRoomId
    ): array {
        return [
            'category_fee_id' => $categoryId,
            'option_id' => $optionId,
            'class_room_id' => $classRoomId,
        ];
    }
}
