<?php

namespace App\Domain\Features\Finance;

use App\Domain\Contract\Finance\ISalary;
use App\Models\Salary;
use App\Models\SalaryDetail;
use App\Models\School;

class SalaryFeature implements ISalary
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
        $salaries = Salary::query()
            ->when($date, function ($query, $val) {
                return $query->whereDate('creared_at', $val);
            })
            ->when($month, function ($query, $val) {
                return $query->where('month', $val);
            })
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->get();
        foreach ($salaries as $salary) {
            foreach ($salary->salaryDetails()->where('currency', $currency)->get() as $salaryDetail) {
                $total += $salaryDetail->amount;
            }
        }
        return $total;
    }

    /**
     * @inheritDoc
     */
    public static function getDetailAmountToatl(
        ?int $salaryId,
        ?string $currency
    ): float|int {
        $total = 0;
        $salaryDetails = SalaryDetail::query()
            ->where('salary_id', $salaryId)
            ->where('currency', $currency)
            ->get();
        foreach ($salaryDetails as $salaryDetail) {
            $total += $salaryDetail->amount;
        }
        return $total;
    }

    /**
     * @inheritDoc
     */
    public static function getList(string|null $date, string|null $month): mixed
    {
        return Salary::query()
            ->when($date, function ($query, $val) {
                return $query->whereDate('creared_at', $val);
            })
            ->when($month, function ($query, $val) {
                return $query->where('month', $val);
            })
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->get();
    }
}
