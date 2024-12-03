<?php

namespace App\Domain\Contract\Finance;

interface IOtherExpense
{
    /**
     * Recuperer la liste des dépense
     * @param mixed $date
     * @param mixed $month
     * @param mixed $otherSourceExpenseId
     * @param mixed $categoryExpenseId
     * @param mixed $currency
     * @param mixed $per_page
     * @return mixed
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?int    $otherSourceExpenseId,
        ?int    $categoryExpenseId,
        ?string $currency,
        ?int    $per_page
    ): mixed;

    /**
     * Récuprer le montant total des dépenses
     * @param mixed $date
     * @param mixed $month
     * @param mixed $otherSourceExpenseId
     * @param mixed $categoryExpenseId
     * @param mixed $currency
     * @return int|float
     */
    public static function getAmountTotal(
        ?string $date,
        ?string $month,
        ?int    $otherSourceExpenseId,
        ?int    $categoryExpenseId,
        ?string $currency
    ): int|float;
}
