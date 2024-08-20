<?php

namespace App\Domain\Contract\Finance;

interface IExpenseFee
{

    /**
     * Recuperer la liste des dépense
     * @param mixed $date
     * @param mixed $month
     * @param mixed $categoryFeeId
     * @param mixed $categoryExenseId
     * @param mixed $currency
     * @param mixed $per_page
     * @return mixed
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?int $categoryFeeId,
        ?int $categoryExenseId,
        ?string $currency,
        ?int $per_page
    ): mixed;

    /**
     * Récuprer le montant total des dépenses
     * @param mixed $date
     * @param mixed $month
     * @param mixed $categoryFeeId
     * @param mixed $categoryExenseId
     * @param mixed $currency
     * @return int|float
     */
    public static function getAmountTotal(
        ?string $date,
        ?string $month,
        ?int $categoryFeeId,
        ?int $categoryExenseId,
        ?string $currency
    ): int|float;
}
