<?php

namespace App\Domain\Contract\Configuration;

use App\Models\CategoryFee;

interface IFeeDataConfiguration
{
    /**
     * Retouter la liste des catégories des frais scolaire
     * @param int $per_page
     * @return mixed
     */
    public static function getListCategoryFee(
        int $per_page
    ): mixed;

    /**
     * Retourner la liste des frais scolaire
     * @param mixed $categoryId
     * @param mixed $optionId
     * @param mixed $classRoomId
     * @param int $per_page
     * @return mixed
     */
    public static function getListScalarFee(
        ?int $categoryId,
        ?int $optionId,
        ?int $classRoomId,
        int $per_page = 10
    ): mixed;

    public static function getListScalarFeeNotPaginate(
        ?int $categoryId,
        ?int $optionId,
        ?int $classRoomId,
    ): mixed;

    /**
     * Recuperer la catégorie des frais pour chaque école et année scolaire
     * @return \App\Models\CategoryFee
     */
    public static function getFirstCategoryFee(): CategoryFee;
}
