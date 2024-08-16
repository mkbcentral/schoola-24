<?php

namespace App\Domain\Contract\Finance;

interface ISavingMoney
{
    public static function getList(
        ?string $date,
        ?string $month,
        ?string $currency
    ): mixed;
    public static function getAmountTotal(
        ?string $date,
        ?string $month,
        ?string $currency
    ): mixed;
}
