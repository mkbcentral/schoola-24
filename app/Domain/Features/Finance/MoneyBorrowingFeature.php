<?php

namespace App\Domain\Features\Finance;

use App\Domain\Contract\Finance\IMoneyBorrowing;
use App\Models\MoneyBorrowing;
use App\Models\School;
use App\Models\SchoolYear;

class MoneyBorrowingFeature implements IMoneyBorrowing
{

    /**
     * @inheritDoc
     */
    public static function getAmount(
        string|null $date,
        string|null $month,
        string|null $currency
    ): float|int {
        $total = 0;
        $moneyBorrowings = MoneyBorrowing::query()
            ->when($date, function ($query, $val) {
                return $query->whereDate('created_at', $val);
            })
            ->when($month, function ($query, $val) {
                return $query->where('month', $val);
            })
            ->when($currency, function ($query, $val) {
                return $query->where('currency', $val);
            })
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->get();
        foreach ($moneyBorrowings as $moneyBorrowing) {
            $total += $moneyBorrowing->amount;
        }
        return $total;
    }

    /**
     * @inheritDoc
     */
    public static function getList(
        string|null $date,
        string|null $month,
        string|null $currency
    ): mixed {
        return MoneyBorrowing::query()
            ->when($date, function ($query, $val) {
                return $query->whereDate('created_at', $val);
            })
            ->when($month, function ($query, $val) {
                return $query->where('month', $val);
            })
            ->when($currency, function ($query, $val) {
                return $query->where('currency', $val);
            })
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->get();
    }
}
