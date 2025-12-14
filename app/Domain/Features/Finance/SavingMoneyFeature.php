<?php

namespace App\Domain\Features\Finance;

use App\Domain\Contract\Finance\ISavingMoney;
use App\Models\SavingMoney;

class SavingMoneyFeature implements ISavingMoney
{
    /**
     * {@inheritDoc}
     */
    public static function getAmountTotal(
        ?string $date,
        ?string $month,
        ?string $currency
    ): mixed {
        $total = 0;
        $filters = [
            'date' => $date,
            'month' => $month,
            'currency' => $currency,
        ];
        $savingMoneys = SavingMoney::query()
            ->filter($filters)
            ->get();
        foreach ($savingMoneys as $savingMoney) {
            $total += $savingMoney->amount;
        }

        return $total;
    }

    /**
     * {@inheritDoc}
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?string $currency,
        ?int $per_page,
    ): mixed {
        $filters = [
            'date' => $date,
            'month' => $month,
            'currency' => $currency,
        ];

        return SavingMoney::query()
            ->filter($filters)
            ->paginate($per_page);
    }
}
