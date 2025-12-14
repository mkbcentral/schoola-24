<?php

namespace App\Domain\Contract\Finance;

interface IBankDeposit
{
    /**
     * Recuprer la liste des dépot bank
     *
     * @param  mixed  $date
     * @param  mixed  $month
     * @param  mixed  $currency
     * @param  mixed  $per_page
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?string $currency,
        ?int $per_page
    ): mixed;

    /**
     * Recuperer le montant total des dépots banque
     *
     * @param  mixed  $date
     * @param  mixed  $month
     * @param  mixed  $currency
     */
    public static function getAmountTotal(
        ?string $date,
        ?string $month,
        ?string $currency,
    ): int|float;
}
