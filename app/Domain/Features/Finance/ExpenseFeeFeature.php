<?php

namespace App\Domain\Features\Finance;

use App\Domain\Contract\Finance\IExpenseFee;
use App\Models\ExpenseFee;
use App\Models\SchoolYear;

class ExpenseFeeFeature implements IExpenseFee
{

    /**
     * @inheritDoc
     */
    public static function getAmountTotal(
        string|null $date,
        string|null $month,
        int|null $categoryFeeId,
        int|null $categoryExenseId,
        string|null $currency
    ): float|int {
        $filters = [
            'date' => $date,
            'month' => $month,
            'categoryFeeId' => $categoryFeeId,
            'categoryExpenseId' => $categoryExenseId,
            'currency' => $currency,
        ];
        $total = 0;
        $expenseFees = ExpenseFee::query()
            ->filter($filters)
            ->get();
        foreach ($expenseFees as $expenseFee) {
            $total += $expenseFee->amount;
        }
        return $total;
    }

    /**
     * @inheritDoc
     */
    public static function getList(
        string|null $date,
        string|null $month,
        int|null $categoryFeeId,
        int|null $categoryExenseId,
        string|null $currency,
        int|null $per_page
    ): mixed {
        $filters = [
            'date' => $date,
            'month' => $month,
            'categoryFeeId' => $categoryFeeId,
            'categoryExpenseId' => $categoryExenseId,
            'currency' => $currency,
        ];
        return ExpenseFee::query()
            ->filter($filters)
            ->paginate($per_page);
    }
}
