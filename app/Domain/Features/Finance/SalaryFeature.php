<?php

namespace App\Domain\Features\Finance;

use App\Domain\Contract\Finance\ISalary;
use App\Models\Salary;
use App\Models\SalaryDetail;
use App\Models\School;
use App\Models\SchoolYear;

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
        $filters = self::getFilters($date, $month);
        $salaries = Salary::query()
           ->filter($filters)
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
    public static function getDetailAmountTotal(
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
    public static function getList(
        string|null $date,
        string|null $month,
        int|null $per_page
    ): mixed {
        $filters = self::getFilters($date, $month);
        return Salary::query()
            ->filter($filters)
            ->paginate($per_page);
    }

    /**
     * @param mixed $date
     * @param mixed $month
     * @return array
     */
    public static function getFilters(mixed $date, mixed $month): array
    {
        return [
            'date' => $date,
            'month' => $month,
        ];
    }
}
