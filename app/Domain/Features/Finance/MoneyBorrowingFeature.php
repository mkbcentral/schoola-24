<?php

namespace App\Domain\Features\Finance;

use App\Domain\Contract\Finance\IMoneyBorrowing;
use App\Models\MoneyBorrowing;

class MoneyBorrowingFeature implements IMoneyBorrowing
{
    /**
     * {@inheritDoc}
     */
    public static function getAmount(
        ?string $date,
        ?string $month,
        ?string $currency
    ): float|int {
        $total = 0;
        $filter = self::getFilters($date, $month, $currency);
        $moneyBorrowings = MoneyBorrowing::query()
            ->filter($filter)
            ->get();
        foreach ($moneyBorrowings as $moneyBorrowing) {
            $total += $moneyBorrowing->amount;
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
        ?int $per_page
    ): mixed {
        $filter = self::getFilters($date, $month, $currency);

        return MoneyBorrowing::query()
            ->filter($filter)
            ->paginate($per_page);
    }

    public static function getFilters(mixed $date, mixed $month, mixed $currency): array
    {
        return [
            'date' => $date,
            'month' => $month,
            'currency' => $currency,
        ];
    }
}
