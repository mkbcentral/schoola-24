<?php

namespace App\Services;

use App\Models\CategoryFee;
use App\Models\SchoolYear;
use App\Models\ExpenseFee;
use App\Models\Rate;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialReportService
{
    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Obtenir le taux de change par défaut
     */
    private function getExchangeRate(): float
    {
        return (float) Rate::DEFAULT_RATE();
    }

    /**
     * Générer un rapport financier avec filtres avancés
     *
     * @param array $filters [
     *   'type' => 'daily'|'monthly'|'period'|'payment',
     *   'date' => 'Y-m-d',
     *   'month' => 'mm',
     *   'year' => yyyy,
     *   'start_date' => 'Y-m-d',
     *   'end_date' => 'Y-m-d',
     *   'category_fee_id' => int,
     *   'payment_type' => 'paid'|'unpaid'|'all',
     *   'currency' => 'USD'|'CDF'|'both'
     * ]
     * @return array
     */
    public function generateReport(array $filters = []): array
    {
        $type = $filters['type'] ?? 'monthly';
        $currency = $filters['currency'] ?? 'USD';

        return match ($type) {
            'daily' => $this->getDailyReport($filters, $currency),
            'monthly' => $this->getMonthlyReport($filters, $currency),
            'period' => $this->getPeriodReport($filters, $currency),
            'payment' => $this->getPaymentTypeReport($filters, $currency),
            default => $this->getMonthlyReport($filters, $currency),
        };
    }

    /**
     * Rapport journalier
     */
    private function getDailyReport(array $filters, string $currency): array
    {
        $date = $filters['date'] ?? date('Y-m-d');
        $categoryFeeId = $filters['category_fee_id'] ?? null;

        $revenues = $this->calculateRevenuesWithConversion(
            ['date' => $date],
            $categoryFeeId,
            $currency
        );

        $expenses = $this->calculateExpensesConverted(
            ['date' => $date],
            $categoryFeeId,
            $currency
        );

        $balance = $revenues - $expenses;

        return [
            'type' => 'daily',
            'date' => $date,
            'formatted_date' => Carbon::parse($date)->format('d/m/Y'),
            'revenues' => round($revenues, 2),
            'expenses' => round($expenses, 2),
            'balance' => round($balance, 2),
            'currency' => $currency,
            'details' => $this->getDetailedBreakdown(['date' => $date], $categoryFeeId),
        ];
    }

    /**
     * Rapport mensuel
     */
    private function getMonthlyReport(array $filters, string $currency): array
    {
        $month = $filters['month'] ?? date('m');
        $year = $filters['year'] ?? date('Y');
        $categoryFeeId = $filters['category_fee_id'] ?? null;

        $revenues = $this->calculateRevenuesWithConversion(
            ['month' => $month, 'year' => $year],
            $categoryFeeId,
            $currency
        );

        $expenses = $this->calculateExpensesConverted(
            ['month' => $month, 'year' => $year],
            $categoryFeeId,
            $currency
        );

        $balance = $revenues - $expenses;

        $monthNames = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre'
        ];

        return [
            'type' => 'monthly',
            'month' => $month,
            'year' => $year,
            'month_name' => $monthNames[$month] ?? '',
            'revenues' => round($revenues, 2),
            'expenses' => round($expenses, 2),
            'balance' => round($balance, 2),
            'currency' => $currency,
            'daily_breakdown' => $this->getDailyBreakdown($month, $year, $categoryFeeId, $currency),
            'details' => $this->getDetailedBreakdown(['month' => $month, 'year' => $year], $categoryFeeId),
        ];
    }

    /**
     * Rapport périodique (entre deux dates)
     */
    private function getPeriodReport(array $filters, string $currency): array
    {
        $startDate = $filters['start_date'] ?? date('Y-m-01');
        $endDate = $filters['end_date'] ?? date('Y-m-d');
        $categoryFeeId = $filters['category_fee_id'] ?? null;
        $predefinedLabel = $filters['predefined_label'] ?? null;

        $revenues = $this->calculateRevenuesWithConversion(
            ['start_date' => $startDate, 'end_date' => $endDate],
            $categoryFeeId,
            $currency
        );

        $expenses = $this->calculateExpensesConverted(
            ['start_date' => $startDate, 'end_date' => $endDate],
            $categoryFeeId,
            $currency
        );

        $balance = $revenues - $expenses;

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end) + 1;

        $result = [
            'type' => 'period',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'formatted_start' => $start->format('d/m/Y'),
            'formatted_end' => $end->format('d/m/Y'),
            'duration_days' => $days,
            'revenues' => round($revenues, 2),
            'expenses' => round($expenses, 2),
            'balance' => round($balance, 2),
            'average_daily_revenue' => $days > 0 ? round($revenues / $days, 2) : 0,
            'average_daily_expense' => $days > 0 ? round($expenses / $days, 2) : 0,
            'currency' => $currency,
            'monthly_breakdown' => $this->getMonthlyBreakdownForPeriod($startDate, $endDate, $categoryFeeId, $currency),
            'details' => $this->getDetailedBreakdown(['start_date' => $startDate, 'end_date' => $endDate], $categoryFeeId),
        ];

        if ($predefinedLabel) {
            $result['predefined_label'] = $predefinedLabel;
        }

        return $result;
    }

    /**
     * Rapport par type de paiement (payé/non payé)
     */
    private function getPaymentTypeReport(array $filters, string $currency): array
    {
        $paymentType = $filters['payment_type'] ?? 'all';
        $categoryFeeId = $filters['category_fee_id'] ?? null;
        $month = $filters['month'] ?? null;
        $date = $filters['date'] ?? null;

        $paidRevenues = $this->calculateRevenuesWithConversion(
            array_merge($filters, ['payment_status' => true]),
            $categoryFeeId,
            $currency
        );

        $unpaidRevenues = $this->calculateRevenuesWithConversion(
            array_merge($filters, ['payment_status' => false]),
            $categoryFeeId,
            $currency
        );

        $expenses = $this->calculateExpensesConverted($filters, $categoryFeeId, $currency);
        $totalRevenues = $paidRevenues + $unpaidRevenues;
        $balance = $paidRevenues - $expenses;

        return [
            'type' => 'payment',
            'payment_type' => $paymentType,
            'paid_revenues' => round($paidRevenues, 2),
            'unpaid_revenues' => round($unpaidRevenues, 2),
            'total_revenues' => round($totalRevenues, 2),
            'expenses' => round($expenses, 2),
            'balance' => round($balance, 2),
            'payment_rate' => $totalRevenues > 0 ? round(($paidRevenues / $totalRevenues) * 100, 2) : 0,
            'currency' => $currency,
            'details' => [
                'paid' => $this->getDetailedBreakdown(array_merge($filters, ['payment_status' => true]), $categoryFeeId),
                'unpaid' => $this->getDetailedBreakdown(array_merge($filters, ['payment_status' => false]), $categoryFeeId),
            ],
        ];
    }

    /**
     * Calculer les recettes avec conversion
     */
    private function calculateRevenuesWithConversion(array $filters, ?int $categoryId, string $targetCurrency): float
    {
        $query = DB::table('payments')
            ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
            ->join('registrations', 'payments.registration_id', '=', 'registrations.id')
            ->join('rates', 'payments.rate_id', '=', 'rates.id')
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        // Filtrer par statut de paiement si spécifié
        if (isset($filters['payment_status'])) {
            $query->where('payments.is_paid', $filters['payment_status']);
        } else {
            // Par défaut, uniquement les paiements validés
            $query->where('payments.is_paid', true);
        }

        // Filtre par date exacte
        if (isset($filters['date'])) {
            $query->whereDate('payments.created_at', $filters['date']);
        }

        // Filtre par mois et année
        if (isset($filters['month'])) {
            $query->where('payments.month', $filters['month']);
        }

        if (isset($filters['year'])) {
            $query->whereYear('payments.created_at', $filters['year']);
        }

        // Filtre par période
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('payments.created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59']);
        }

        // Filtre par catégorie
        if ($categoryId) {
            $query->where('category_fees.id', $categoryId);
        }

        $payments = $query->select(
            'category_fees.currency',
            'scolar_fees.amount',
            'rates.amount as rate_amount'
        )->get();

        $total = 0;

        foreach ($payments as $payment) {
            $amount = (float) $payment->amount;
            $rate = (float) $payment->rate_amount;

            if ($targetCurrency === 'USD') {
                if ($payment->currency === 'USD') {
                    $total += $amount;
                } elseif ($payment->currency === 'CDF') {
                    $total += $amount / $rate;
                }
            } else {
                if ($payment->currency === 'CDF') {
                    $total += $amount;
                } elseif ($payment->currency === 'USD') {
                    $total += $amount * $rate;
                }
            }
        }

        return $total;
    }

    /**
     * Calculer les dépenses avec conversion
     */
    private function calculateExpensesConverted(array $filters, ?int $categoryFeeId, string $targetCurrency): float
    {
        $query = ExpenseFee::query()
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        // Filtre par date exacte
        if (isset($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        // Filtre par mois et année
        if (isset($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        if (isset($filters['year'])) {
            $query->whereYear('created_at', $filters['year']);
        }

        // Filtre par période
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59']);
        }

        // Filtre par catégorie
        if ($categoryFeeId) {
            $query->where('category_fee_id', $categoryFeeId);
        }

        $expenses = $query->get();
        $rate = $this->getExchangeRate();
        $total = 0;

        foreach ($expenses as $expense) {
            $amount = (float) $expense->amount;

            if ($targetCurrency === 'USD') {
                if ($expense->currency === 'USD') {
                    $total += $amount;
                } elseif ($expense->currency === 'CDF') {
                    $total += $amount / $rate;
                }
            } else {
                if ($expense->currency === 'CDF') {
                    $total += $amount;
                } elseif ($expense->currency === 'USD') {
                    $total += $amount * $rate;
                }
            }
        }

        return $total;
    }

    /**
     * Obtenir une ventilation détaillée par devise
     */
    private function getDetailedBreakdown(array $filters, ?int $categoryId): array
    {
        $revenuesUsd = $this->calculateRevenuesWithConversion($filters, $categoryId, 'USD');
        $revenuesCdf = $this->calculateRevenuesWithConversion($filters, $categoryId, 'CDF');
        $expensesUsd = $this->calculateExpensesConverted($filters, $categoryId, 'USD');
        $expensesCdf = $this->calculateExpensesConverted($filters, $categoryId, 'CDF');

        return [
            'revenues' => [
                'usd' => round($revenuesUsd, 2),
                'cdf' => round($revenuesCdf, 2),
            ],
            'expenses' => [
                'usd' => round($expensesUsd, 2),
                'cdf' => round($expensesCdf, 2),
            ],
            'balance' => [
                'usd' => round($revenuesUsd - $expensesUsd, 2),
                'cdf' => round($revenuesCdf - $expensesCdf, 2),
            ],
        ];
    }

    /**
     * Obtenir la ventilation quotidienne pour un mois
     */
    private function getDailyBreakdown(string $month, int $year, ?int $categoryId, string $currency): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$month, $year);
        $breakdown = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

            $revenues = $this->calculateRevenuesWithConversion(['date' => $date], $categoryId, $currency);
            $expenses = $this->calculateExpensesConverted(['date' => $date], $categoryId, $currency);

            // N'inclure que les jours avec activité
            if ($revenues > 0 || $expenses > 0) {
                $breakdown[] = [
                    'day' => $day,
                    'date' => $date,
                    'formatted_date' => Carbon::parse($date)->format('d/m/Y'),
                    'revenues' => round($revenues, 2),
                    'expenses' => round($expenses, 2),
                    'balance' => round($revenues - $expenses, 2),
                ];
            }
        }

        return $breakdown;
    }

    /**
     * Obtenir la ventilation mensuelle pour une période
     */
    private function getMonthlyBreakdownForPeriod(string $startDate, string $endDate, ?int $categoryId, string $currency): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $breakdown = [];

        $current = $start->copy()->startOfMonth();

        while ($current->lte($end)) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();

            // Ajuster si le début/fin de période est dans ce mois
            if ($monthStart->lt($start)) {
                $monthStart = $start->copy();
            }
            if ($monthEnd->gt($end)) {
                $monthEnd = $end->copy();
            }

            $revenues = $this->calculateRevenuesWithConversion(
                ['start_date' => $monthStart->format('Y-m-d'), 'end_date' => $monthEnd->format('Y-m-d')],
                $categoryId,
                $currency
            );

            $expenses = $this->calculateExpensesConverted(
                ['start_date' => $monthStart->format('Y-m-d'), 'end_date' => $monthEnd->format('Y-m-d')],
                $categoryId,
                $currency
            );

            if ($revenues > 0 || $expenses > 0) {
                $breakdown[] = [
                    'month' => $current->format('m'),
                    'year' => $current->year,
                    'month_name' => $current->translatedFormat('F'),
                    'revenues' => round($revenues, 2),
                    'expenses' => round($expenses, 2),
                    'balance' => round($revenues - $expenses, 2),
                ];
            }

            $current->addMonth();
        }

        return $breakdown;
    }

    /**
     * Obtenir les catégories disponibles
     */
    public function getAvailableCategories()
    {
        return CategoryFee::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('is_for_dash', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Comparer deux périodes
     */
    public function comparePeriods(array $period1Filters, array $period2Filters, string $currency = 'USD'): array
    {
        $period1 = $this->generateReport(array_merge($period1Filters, ['currency' => $currency]));
        $period2 = $this->generateReport(array_merge($period2Filters, ['currency' => $currency]));

        $revenuesDiff = $period1['revenues'] - $period2['revenues'];
        $expensesDiff = $period1['expenses'] - $period2['expenses'];
        $balanceDiff = $period1['balance'] - $period2['balance'];

        $revenuesGrowth = $period2['revenues'] > 0
            ? round((($period1['revenues'] - $period2['revenues']) / $period2['revenues']) * 100, 2)
            : 0;

        $expensesGrowth = $period2['expenses'] > 0
            ? round((($period1['expenses'] - $period2['expenses']) / $period2['expenses']) * 100, 2)
            : 0;

        return [
            'period1' => $period1,
            'period2' => $period2,
            'comparison' => [
                'revenues_diff' => round($revenuesDiff, 2),
                'expenses_diff' => round($expensesDiff, 2),
                'balance_diff' => round($balanceDiff, 2),
                'revenues_growth' => $revenuesGrowth,
                'expenses_growth' => $expensesGrowth,
            ],
            'currency' => $currency,
        ];
    }
}
