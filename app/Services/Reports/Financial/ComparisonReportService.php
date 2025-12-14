<?php

namespace App\Services\Reports\Financial;

use App\Services\Reports\BaseReportService;
use Carbon\Carbon;

class ComparisonReportService extends BaseReportService
{
    /**
     * Générer un rapport de comparaison entre deux périodes
     */
    public function generate(array $filters): array
    {
        $this->validateFilters($filters, ['comparison_type', 'period_1_start', 'period_1_end', 'period_2_start', 'period_2_end']);

        $period1Start = Carbon::parse($filters['period_1_start']);
        $period1End = Carbon::parse($filters['period_1_end']);
        $period2Start = Carbon::parse($filters['period_2_start']);
        $period2End = Carbon::parse($filters['period_2_end']);

        // Données de la période 1
        $period1Revenues = $this->getRevenues($period1Start, $period1End, $filters['source'] ?? null);
        $period1Expenses = $this->getExpenses($period1Start, $period1End);
        $period1Balance = $this->calculateBalance($period1Revenues, $period1Expenses);

        // Données de la période 2
        $period2Revenues = $this->getRevenues($period2Start, $period2End, $filters['source'] ?? null);
        $period2Expenses = $this->getExpenses($period2Start, $period2End);
        $period2Balance = $this->calculateBalance($period2Revenues, $period2Expenses);

        // Calcul des variations
        $revenuesVariation = $this->calculateVariation($period2Revenues, $period1Revenues);
        $expensesVariation = $this->calculateVariation($period2Expenses, $period1Expenses);
        $balanceVariation = $this->calculateVariation($period2Balance, $period1Balance);

        return [
            'type' => 'comparison',
            'comparison_type' => $filters['comparison_type'],
            'currency' => $this->currency,
            'period_1' => [
                'label' => $this->formatPeriod($period1Start, $period1End),
                'start_date' => $period1Start->format('Y-m-d'),
                'end_date' => $period1End->format('Y-m-d'),
                'revenues' => $period1Revenues,
                'expenses' => $period1Expenses,
                'balance' => $period1Balance,
            ],
            'period_2' => [
                'label' => $this->formatPeriod($period2Start, $period2End),
                'start_date' => $period2Start->format('Y-m-d'),
                'end_date' => $period2End->format('Y-m-d'),
                'revenues' => $period2Revenues,
                'expenses' => $period2Expenses,
                'balance' => $period2Balance,
            ],
            'variations' => [
                'revenues' => $revenuesVariation,
                'expenses' => $expensesVariation,
                'balance' => $balanceVariation,
            ],
            'summary' => [
                'total_revenues' => $period1Revenues + $period2Revenues,
                'total_expenses' => $period1Expenses + $period2Expenses,
                'average_revenues' => ($period1Revenues + $period2Revenues) / 2,
                'average_expenses' => ($period1Expenses + $period2Expenses) / 2,
            ],
            'generated_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Générer une comparaison mois à mois
     */
    public function generateMonthToMonth(int $year, int $month1, int $month2): array
    {
        return $this->generate([
            'comparison_type' => 'month_to_month',
            'period_1_start' => Carbon::create($year, $month1, 1)->startOfMonth(),
            'period_1_end' => Carbon::create($year, $month1, 1)->endOfMonth(),
            'period_2_start' => Carbon::create($year, $month2, 1)->startOfMonth(),
            'period_2_end' => Carbon::create($year, $month2, 1)->endOfMonth(),
        ]);
    }

    /**
     * Générer une comparaison année à année
     */
    public function generateYearToYear(int $year1, int $year2): array
    {
        return $this->generate([
            'comparison_type' => 'year_to_year',
            'period_1_start' => Carbon::create($year1, 1, 1)->startOfYear(),
            'period_1_end' => Carbon::create($year1, 12, 31)->endOfYear(),
            'period_2_start' => Carbon::create($year2, 1, 1)->startOfYear(),
            'period_2_end' => Carbon::create($year2, 12, 31)->endOfYear(),
        ]);
    }
}
