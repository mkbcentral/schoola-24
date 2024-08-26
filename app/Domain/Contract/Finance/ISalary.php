<?php

namespace App\Domain\Contract\Finance;

interface ISalary
{
    /**
     * Recuperer la liste de salaires
     * @param mixed $date
     * @param mixed $month
     * @param mixed $per_page
     * @return mixed
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?int $per_page
    ): mixed;

    /**
     * Récuperer le montant total de salair
     * @param mixed $date
     * @param mixed $month
     * @param mixed $currency
     * @return int|float
     */
    public static function getAmountTotal(?string $date, ?string $month, ?string $currency): int|float;

    /**
     * Recupérer le montant total pour chaque à partir d'un element salire
     * @param mixed $salaryId
     * @param mixed $currency
     * @return int|float
     */
    public static function getDetailAmountToatl(?int $salaryId, ?string $currency): int|float;
}
