<?php

namespace App\Domain\Features\Finance;

use App\Domain\Contract\Finance\IOtherExpense;
use App\Models\OtherExpense;

class OtherExpenseFeature implements IOtherExpense
{

    /**
     * @inheritDoc
     */
    public static function getAmountTotal(
        string|null $date,
        string|null $month,
        int|null $otherSourceExpenseId,
        int|null $categoryExenseId,
        string|null $currency
    ): float|int {
        $filters = [
            'date' => $date,
            'month' => $month,
            'otherSourceExpenseId' => $otherSourceExpenseId,
            'categoryExenseId' => $categoryExenseId,
            'currency' => $currency,
        ];
        $total = 0;
        $expenseFees = OtherExpense::query()
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
        int|null $otherSourceExpenseId,
        int|null $categoryExenseId,
        string|null $currency,
        int|null $per_page
    ): mixed {
        $filters = [
            'date' => $date,
            'month' => $month,
            'otherSourceExpenseId' => $otherSourceExpenseId,
            'categoryExenseId' => $categoryExenseId,
            'currency' => $currency,
        ];
        return OtherExpense::query()
            ->filter($filters)
            ->paginate($per_page);
    }
}
