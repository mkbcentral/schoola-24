<?php

namespace App\Domain\Contract\Configuration;

use App\Models\CategoryFee;

interface IFeeDataConfiguration
{
    /**
     * Retouner la liste des catégories des frais scolaire
     * @param int $per_page
     * @param string|null $search
     * @return mixed
     */
    public static function getListCategoryFee(
        int $per_page,
        ?string $search =''
    ): mixed;

    /**
     * Retourner la liste de categories en fonction du role du user
     * @param int $per_page
     * @param string|null $search
     * @return mixed
     */
    public static function getListCategoryFeeForSpecificUser(
        int $per_page,
        ?string $search =''
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

    /**
     * Retourner la liste des frais not paginées
     * @param int|null $categoryId
     * @param int|null $optionId
     * @param int|null $classRoomId
     * @return mixed
     */
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
