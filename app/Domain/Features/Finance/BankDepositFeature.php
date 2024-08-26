<?php

namespace App\Domain\Features\Finance;

use App\Domain\Contract\Finance\IBankDeposit;
use App\Models\BankDeposit;

class BankDepositFeature implements IBankDeposit
{

    /**
     * @inheritDoc
     */
    public static function getAmountTotal(
        string|null $date,
        string|null $month,
        string|null $currency
    ): float|int {
        $total = 0;
        $filters = [
            'date' => $date,
            'month' => $month,
            'currency' => $currency
        ];
        $bankDeposits = BankDeposit::query()
            ->filter($filters)
            ->get();
        foreach ($bankDeposits as $bankDeposit) {
            $total += $bankDeposit->amount;
        }
        return $total;
    }

    /**
     * @inheritDoc
     */
    public static function getList(
        string|null $date,
        string|null $month,
        string|null $currency,
        int|null $per_page
    ): mixed {
        $filters = [
            'date' => $date,
            'month' => $month,
            'currency' => $currency
        ];
        return BankDeposit::query()
            ->filter($filters)
            ->paginate($per_page);
    }
}
