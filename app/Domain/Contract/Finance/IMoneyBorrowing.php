<?php

namespace App\Domain\Contract\Finance;

interface IMoneyBorrowing
{
    /**
     * Recuperer la liste des emprunts
     *
     * @param  mixed  $date
     * @param  mixed  $month
     * @param  mixed  $currency
     */
    public static function getList(
        string $date,
        ?string $month,
        ?string $currency,
        ?int $per_page
    ): mixed;

    /**
     * Recuperer le montant total des emprunts
     *
     * @param  mixed  $date
     * @param  mixed  $month
     * @param  mixed  $currency
     */
    public static function getAmount(
        ?string $date,
        ?string $month,
        ?string $currency
    ): int|float;
}
