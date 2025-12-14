<?php

namespace App\Domain\Contract\Finance;

interface ISavingMoney
{
    /**
     * Recupérer la liste epargnes par école
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
     * Récupérer le montant total des epargnes
     *
     * @param  mixed  $date
     * @param  mixed  $month
     * @param  mixed  $currency
     */
    public static function getAmountTotal(
        ?string $date,
        ?string $month,
        ?string $currency
    ): mixed;
}
