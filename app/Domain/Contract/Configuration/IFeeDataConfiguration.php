<?php

namespace App\Domain\Contract\Configuration;

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
     * @param int $categoryId
     * @param int $optionId
     * @param int $classRoomId
     * @param int $per_page
     * @return mixed
     */
    public static function getListScalarFee(
        int $categoryId,
        int $optionId,
        int $classRoomId,
        int $per_page
    ): mixed;
}
