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
        int|null    $otherSourceExpenseId,
        int|null    $categoryExpenseId,
        string|null $currency
    ): float|int {
        $filters = self::getFilters($date, $month, $otherSourceExpenseId, $categoryExpenseId, $currency);
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
        int|null    $otherSourceExpenseId,
        int|null    $categoryExpenseId,
        string|null $currency,
        int|null    $per_page
    ): mixed {
        $filters = self::getFilters($date, $month, $otherSourceExpenseId, $categoryExpenseId, $currency);
        return OtherExpense::query()
            ->filter($filters)
            ->paginate($per_page);
    }

    /**
     * @param mixed $date
     * @param mixed $month
     * @param mixed $otherSourceExpenseId
     * @param mixed $categoryExpenseId
     * @param mixed $currency
     * @return array
     */
    public static function getFilters(mixed $date, mixed $month, mixed $otherSourceExpenseId, mixed $categoryExpenseId, mixed $currency): array
    {
        return [
            'date' => $date,
            'month' => $month,
            'otherSourceExpenseId' => $otherSourceExpenseId,
            'categoryExpenseId' => $categoryExpenseId,
            'currency' => $currency,
        ];
    }
}
